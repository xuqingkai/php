<?php
include_once('./5upay.config.php');

$_5upay['data']=array();
$_5upay['data']['merchantId']=$_5upay['merchantId'];
$_5upay['data']['orderAmount']=intval('1.00'*100);
$_5upay['data']['orderCurrency']='CNY';
$_5upay['data']['requestId']=date('YmdHis').rand(1000,9999);
$_5upay['data']['notifyUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/')).'/shouxinyi.notify.php';
$_5upay['data']['callbackUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'].'/';
$_5upay['data']['paymentModeCode']='BANK_CARD-EXPRESS_DEBIT';
/*
BANK_CARD-EXPRESS_DEBIT快捷支付借记卡
BANK_CARD-EXPRESS_CREDIT快捷支付信用卡
微信支付	
SCANCODE-WEIXIN_PAY-P2P	微信扫码（需要传入clientIp,接收返回的base64编码并解析成二维码图片）
MINIAPPS-WEIXIN_PAY-P2P	微信小程序
WECHAT-OFFICIAL_PAY-P2P	微信公众号
支付宝支付	
SCANCODE-ALI_PAY-P2P	支付宝扫码（需要传入clientIp,接收返回的base64编码并解析成二维码图片）
ALIPAY-WAP-P2P	支付宝H5支付
ALIPAY-OFFICIAL-P2P	支付宝生活号支付
MINIAPPS-ALI_PAY-P2P	支付宝小程序
*/
$_5upay['data']['productDetails']=[];
$_5upay['data']['productDetails'][]=['amount'=>$_5upay['data']['orderAmount'], 'name'=>'测试商品', 'quantity'=>1];
$_5upay['data']['payer']=['idType'=>'IDCARD'];
$_5upay['data']['clientIp']=$_SERVER['REMOTE_ADDR'];
$_5upay['data']['merchantUserId']=substr($_5upay['merchantUserId'].'@'.$_SERVER['HTTP_HOST'],0,30);

$_5upay['sign_string'] ='';
ksort($_5upay['data']);
foreach ($_5upay['data'] as $key=>$val){
    if(!is_array($val)){
        if(strlen($val)){$_5upay['sign_string'].=$val.'#';}
    }else{
        ksort($val);
        foreach ($val as $key1=>$val1){
            if(!is_array($val1)){
                if(strlen($val1)){$_5upay['sign_string'].=$val1.'#';}
            }else{
                ksort($val1);
                foreach ($val1 as $key2=>$val2){
                    if(!is_array($val2)){
                        if(strlen($val2)){$_5upay['sign_string'].=$val2.'#';}
                    }
                }
            }
        }
    }
}

openssl_pkcs12_read(file_get_contents($_5upay['mch_pfx_file_path']), $_5upay['private_rsa'], $_5upay['mch_pfx_file_password']);
openssl_sign(sha1($_5upay['sign_string'],true), $_5upay['data']['hmac'], $_5upay['private_rsa']['pkey'], OPENSSL_ALGO_MD5);
$_5upay['data']['hmac']=base64_encode($_5upay['data']['hmac']);
$_5upay['json']=json_encode($_5upay['data'], JSON_UNESCAPED_UNICODE);
$_5upay['aes_key']=substr(md5($_5upay['data']['requestId']),$_5upay['data']['orderAmount']%16,16);

$_5upay['aes_iv'] = 16 - (strlen($_5upay['json']) % 16);
$_5upay['data_aes_pad'] = $_5upay['json'];
$_5upay['data_aes_pad'] .= str_repeat(chr($_5upay['aes_iv']), $_5upay['aes_iv']);

$_5upay['request_string'] = openssl_encrypt($_5upay['data_aes_pad'], 'AES-128-ECB', $_5upay['aes_key'], OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING);
$_5upay['request_string'] = base64_encode($_5upay['request_string']);

$_5upay['public_key'] = file_get_contents($_5upay['api_cer_file_path']);
if(strpos($_5upay['public_key'],'-----') === false){
    $_5upay['public_key'] = "-----BEGIN CERTIFICATE-----\n".chunk_split(base64_encode(file_get_contents($_5upay['api_cer_file_path'])),64,"\n")."-----END CERTIFICATE-----\n";
}
openssl_public_encrypt($_5upay['aes_key'], $_5upay['encryptKey'], openssl_pkey_get_public($_5upay['public_key']));
$_5upay['encryptKey']=base64_encode($_5upay['encryptKey']);

$_5upay['response_string']=file_get_contents($_5upay['api_host'].'/onlinePay/order', false, stream_context_create(array(
	'http' => array(
		'method' => 'POST',
		'header'  => "Content-Type:application/vnd.5upay-v3.0+json\r\nencryptKey:".$_5upay['encryptKey']."\r\nmerchantId:".$_5upay['data']['merchantId']."\r\nrequestId:".$_5upay['data']['requestId'],
		'content' => $_5upay['request_string']
	),
	'ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false
	)
)));
$_5upay['http_response_header']=$http_response_header;
$_5upay['decryptKey']='';
foreach ($http_response_header as $header){
   if(strpos($header,'encryptKey:') !== false){
       $_5upay['decryptKey']=trim(substr($header,strpos($header,':')+1));
   } 
};
if($_5upay['decryptKey']){
    openssl_private_decrypt(base64_decode($_5upay['decryptKey']), $_5upay['aes_key_response'], openssl_pkey_get_private($_5upay['private_rsa']['pkey']));//私钥解密
    $_5upay['response_json']=json_decode($_5upay['response_string'], true);
    $_5upay['response_data'] = substr($_5upay['response_string'], 9);
    $_5upay['response_data'] = substr($_5upay['response_data'], 0, strlen($_5upay['response_data'])-2);
    
    $_5upay['response_decrypted'] = openssl_decrypt($_5upay['response_data'], "AES-128-ECB", $_5upay['aes_key_response']);
    $_5upay['response_decrypted'] = preg_replace('/[\x00-\x1F]/','', $_5upay['response_decrypted']);
    $_5upay['response'] = json_decode($_5upay['response_decrypted'],true);
    header('Content-Type:application/json');
    exit(json_encode($_5upay, JSON_UNESCAPED_UNICODE));


    if ($_5upay['response']["status"] == 'REDIRECT'){
        $_5upay['user_agent']=$_SERVER['HTTP_USER_AGENT'];
        if((strpos($_5upay['user_agent'], "Android")>0 || strpos($_5upay['user_agent'], "iPhone")>0 || strpos($_5upay['user_agent'], "ios")>0 || strpos($_5upay['user_agent'], "iPod")>0)){
            exit('<a href="'.$_5upay['response']['redirectUrl'].'" target="_blank">'.$_5upay['response']['redirectUrl'].'</a>');
        }else{
            exit('<a href="'.$_5upay['response']['redirectUrl'].'" target="_blank">PC端浏览器可能域名受限</a>');
        }
        //header("Location: ".$_5upay['response']['redirectUrl']);exit();
    }elseif($_5upay['response']["status"] == 'SUCCESS'){
        if(isset($_5upay['response']["scanCodeUrl"])){
            exit('<img src="'.$_5upay['response']["scanCodeUrl"].'" />');
        }
        if(isset($_5upay['response']["scanCode"])){
    		exit('<img src="data:image/jpeg;base64,'.$_5upay['response']["scanCode"].'" />');
    		//header('Content-type: image/jpg');print_r(base64_decode($_5upay['response']["scanCode"]));
        }
	}else{
		//exit($_5upay['response']["error"].'('.$_5upay['response']["cause"].')');
	}
}
//var_dump($_5upay);exit();
header('Content-Type:application/json');
exit(json_encode($_5upay, JSON_UNESCAPED_UNICODE));


