<?php
include_once('./hnapay.config.php');

$post=$_POST;
$hnapay['card']=array('card_no'=>'银行卡号','real_name'=>'真实姓名','card_date'=>'卡片有效期','cvv2'=>'CVV2码后3位','mobile_no'=>'手机号码','idcard_no'=>'身份证号');
$hnapay['path']='/exp/signSms2Step.do';
if(!isset($post['mobile_no'])){
    $html='<form action="" method="post" target="_blank">';
    foreach($hnapay['card'] as $key=>$name){
        $html.=$name.'=<input type="text" name="'.$key.'" value="" ><br />';
    }
    $html.='<input type="submit" value="下一步" ><br />';
    $html.='</form>';
    //$html.='<script>document.getElementsByTagName("form")[0].submit();</script>';
    $html='<!DOCTYPE html><html lang="zh">
    <head><meta http-equiv="Content-type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /></head>
    <body>'.$html.'</body></html>';
    exit($html);
}elseif(!isset($post['smsCode'])){
    
    $hnapay['data']=array();
    $hnapay['data']['version']='2.0';
    $hnapay['data']['tranCode']='EXP12';
    $hnapay['data']['merId']=$hnapay['merId'];
    $hnapay['data']['merOrderId']=date('YmdHis').rand(1000,9999);
    $hnapay['data']['submitTime']=date('YmdHis');
    $hnapay['msgCiphertext']=[];
    $hnapay['msgCiphertext']['tranAmt']=intval('1.01'*1);
    $hnapay['msgCiphertext']['payType']='2';//2：银行卡要素支付3:用户业务协议号+支付协议号
    $hnapay['msgCiphertext']['cardNo']=$post['card_no'];
    $hnapay['msgCiphertext']['holderName']=$post['real_name'];
    $hnapay['msgCiphertext']['cardAvailableDate']=$post['card_date'];
    $hnapay['msgCiphertext']['cvv2']=$post['cvv2'];
    $hnapay['msgCiphertext']['mobileNo']=$post['mobile_no'];
    $hnapay['msgCiphertext']['identityType']='01';
    $hnapay['msgCiphertext']['identityCode']=$post['idcard_no'];
    $hnapay['msgCiphertext']['bizProtocolNo']='';
    $hnapay['msgCiphertext']['payProtocolNo']='';
    $hnapay['msgCiphertext']['frontUrl']='http'.(isset($_SERVER["HTTPS"])?'s':'').'://'.$_SERVER["HTTP_HOST"];
    $hnapay['msgCiphertext']['notifyUrl']='http://landui.ixqk.cn/callback';
    $hnapay['msgCiphertext']['orderExpireTime']='';
    $hnapay['msgCiphertext']['merUserId']=md5($hnapay['msgCiphertext']['mobileNo']);
    $hnapay['msgCiphertext']['merUserIp']=$_SERVER['REMOTE_ADDR'];  
    $hnapay['msgCiphertext']['riskExpand']='';
    $hnapay['msgCiphertext']['goodsInfo']='';
    $hnapay_encrypted='';
    //exit(json_encode($hnapay['msgCiphertext'], JSON_UNESCAPED_UNICODE));
    foreach(str_split(json_encode($hnapay['msgCiphertext'], JSON_UNESCAPED_UNICODE), 117) as $hnapay_item){
    openssl_public_encrypt($hnapay_item,$hnapay_item_encrypted, openssl_get_publickey($hnapay['public_key']));
    $hnapay_encrypted.=$hnapay_item_encrypted;
    }
    $hnapay['data']['msgCiphertext']=base64_encode($hnapay_encrypted);
    //$hnapay['data']['msgCiphertext']=base64_encode("sdasdasd");
    $hnapay['data']['signType']='1';//1：RSA，3：国密交易证书，4：国密密钥
    $hnapay['data']['merAttach']='merAttach';//1：UTF-8
    $hnapay['data']['charset']='1';//1：UTF-8
    
    $hnapay['sign_str']='';//version=[]tranCode=[]merId=[]merOrderId=[]submitTime=[]msgCiphertext=[]
    $hnapay['sign_str'].='version=['.$hnapay['data']['version'].']';
    $hnapay['sign_str'].='tranCode=['.$hnapay['data']['tranCode'].']';
    $hnapay['sign_str'].='merId=['.$hnapay['data']['merId'].']';
    $hnapay['sign_str'].='merOrderId=['.$hnapay['data']['merOrderId'].']';
    $hnapay['sign_str'].='submitTime=['.$hnapay['data']['submitTime'].']';
    //$hnapay['sign_str'].='signType=['.$hnapay['data']['signType'].']';
    //$hnapay['sign_str'].='charset=['.$hnapay['data']['charset'].']';
    $hnapay['sign_str'].='msgCiphertext=['.$hnapay['data']['msgCiphertext'].']';
    
    openssl_sign($hnapay['sign_str'], $hnapay['data']['signValue'], $hnapay['xinshoukuan_private_key'], OPENSSL_ALGO_SHA1);
    
    $hnapay['data']['signValue']=base64_encode($hnapay['data']['signValue']);
    
    $hnapay['response_string']=file_get_contents($hnapay['host'].'/exp/payRequest2Step.do', false, stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header'  => "Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser",
      'content' => http_build_query($hnapay['data'])
    ),
    'ssl'=>array(
      'verify_peer'=>false,
      'verify_peer_name'=>false
    )
    )));
    $hnapay['response']=json_decode($hnapay['response_string'],true);
    if($hnapay['response']['resultCode']!='0000'){exit(json_encode($hnapay['response']['errorMsg']));}
    //exit(json_encode($hnapay['response']));
    $hnapay['card']['merOrderId']='下单单号';
    $post['merOrderId']=$hnapay['data']['merOrderId'];
    $hnapay['card']['hnapayOrderId']='响应单号';
    $post['hnapayOrderId']=$hnapay['response']['hnapayOrderId'];
    $hnapay['card']['smsCode']='验 证 码';
}else{
    $hnapay['path']='/exp/payConfirm2Step.do';
    $hnapay['data']=array();
    $hnapay['data']['version']='2.0';
    $hnapay['data']['tranCode']='EXP13';
    $hnapay['data']['merId']=$hnapay['merId'];
    $hnapay['data']['merOrderId']=$post['merOrderId'];
    $hnapay['data']['submitTime']=date('YmdHis');
    $hnapay['msgCiphertext']=[];
    $hnapay['msgCiphertext']['hnapayOrderId']=$post['hnapayOrderId'];
    $hnapay['msgCiphertext']['smsCode']=$post['smsCode'];
    
    $hnapay['msgCiphertext']['merUserIp']=$_SERVER['REMOTE_ADDR']; 
    $hnapay['msgCiphertext']['paymentTerminalInfo']='01|10001';
    $hnapay['msgCiphertext']['receiverTerminalInfo']='01|00001|CN|110000';
    $hnapay['msgCiphertext']['deviceInfo']='192.168.0.1||||||';
    
    
    $hnapay_encrypted='';
    //exit(json_encode($hnapay['msgCiphertext'], JSON_UNESCAPED_UNICODE));
    foreach(str_split(json_encode($hnapay['msgCiphertext'], JSON_UNESCAPED_UNICODE), 117) as $hnapay_item){
    openssl_public_encrypt($hnapay_item,$hnapay_item_encrypted, openssl_get_publickey($hnapay['public_key']));
    $hnapay_encrypted.=$hnapay_item_encrypted;
    }
    
    $hnapay['data']['msgCiphertext']=base64_encode($hnapay_encrypted);
    //$hnapay['data']['msgCiphertext']=base64_encode("sdasdasd");
    $hnapay['data']['signType']='1';//1：RSA，3：国密交易证书，4：国密密钥
    $hnapay['data']['merAttach']='merAttach';//1：UTF-8
    $hnapay['data']['charset']='1';//1：UTF-8
    
    $hnapay['sign_str']='';//version=[]tranCode=[]merId=[]merOrderId=[]submitTime=[]msgCiphertext=[]
    $hnapay['sign_str'].='version=['.$hnapay['data']['version'].']';
    $hnapay['sign_str'].='tranCode=['.$hnapay['data']['tranCode'].']';
    $hnapay['sign_str'].='merId=['.$hnapay['data']['merId'].']';
    $hnapay['sign_str'].='merOrderId=['.$hnapay['data']['merOrderId'].']';
    $hnapay['sign_str'].='submitTime=['.$hnapay['data']['submitTime'].']';
    //$hnapay['sign_str'].='signType=['.$hnapay['data']['signType'].']';
    //$hnapay['sign_str'].='charset=['.$hnapay['data']['charset'].']';
    $hnapay['sign_str'].='msgCiphertext=['.$hnapay['data']['msgCiphertext'].']';
    
    openssl_sign($hnapay['sign_str'], $hnapay['data']['signValue'], $hnapay['xinshoukuan_private_key'], OPENSSL_ALGO_SHA1);
    
    $hnapay['data']['signValue']=base64_encode($hnapay['data']['signValue']);
    
    $hnapay['response_string']=file_get_contents($hnapay['host'].$hnapay['path'], false, stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header'  => "Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser",
      'content' => http_build_query($hnapay['data'])
    ),
    'ssl'=>array(
      'verify_peer'=>false,
      'verify_peer_name'=>false
    )
    )));
    $hnapay['response']=json_decode($hnapay['response_string'],true);
    if($hnapay['response']['resultCode']!='0000'){exit(json_encode($hnapay['response']['errorMsg']));}
    exit('支付完成，请耐心等待跳转。');
}
$html='<form action="" method="post" target="_blank">';
foreach($hnapay['card'] as $key=>$name){
    $html.=$name.'=<input type="text" name="'.$key.'" value="'.$post[$key].'" ><br />';
}
$html.='<input type="submit" value="下一步" ><br />';
$html.='</form>';
//$html.='<script>document.getElementsByTagName("form")[0].submit();</script>';
$html='<!DOCTYPE html><html lang="zh">
<head><meta http-equiv="Content-type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /></head>
<body>'.$html.'</body></html>';
exit($html);
