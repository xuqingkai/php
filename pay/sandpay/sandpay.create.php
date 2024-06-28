<?php
include_once('./sandpay.config.php');

$sandpay['data']=array();
$sandpay['data']['charset']='utf-8';
$sandpay['data']['signType']='01';
$sandpay['data']['data']=array();
$sandpay['data']['data']['head']=array();
$sandpay['data']['data']['head']['version']='1.0';
$sandpay['data']['data']['head']['method']='sandpay.trade.precreate';
$sandpay['data']['data']['head']['productId']='00000006';
$sandpay['data']['data']['head']['accessType']='1';
$sandpay['data']['data']['head']['mid']=$sandpay['mid'];
$sandpay['data']['data']['head']['channelType']='07';
$sandpay['data']['data']['head']['reqTime']=date('YmdHis');

$sandpay['data']['data']['body']=array();
$sandpay['data']['data']['body']['payTool']='0401';//支付宝扫码0401，微信扫码0402，聚合码0403，杉德宝扫码0404
$sandpay['data']['data']['body']['payAccLimit']='alipay';//支付宝ALIPAY，微信WXPAY，银联支付CUPPAY
$sandpay['data']['data']['body']['orderCode']=date('YmdHis').rand(1000,9999);
$sandpay['data']['data']['body']['totalAmount']=substr('000000000000'.number_format("1.00", 2, '', ''),-12);
$sandpay['data']['data']['body']['subject']='subject';
$sandpay['data']['data']['body']['notifyUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"];
//$sandpay['data']['data']['body']['notifyUrl']='http://okqq.eu.org/callback/';
$sandpay['json']=json_encode($sandpay['data']['data']);

openssl_pkcs12_read(file_get_contents($sandpay['pfx_path']), $sandpay['private_rsa'], $sandpay['pfx_pwd']);
openssl_sign($sandpay['json'], $sandpay['data']['sign'], $sandpay['private_rsa']['pkey'], version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA1 : SHA1);
$sandpay['data']['sign']=base64_encode($sandpay['data']['sign']);
$sandpay['request_string']=http_build_query(array(
	'charset'=>$sandpay['data']['charset'],
	'signType'=>$sandpay['data']['signType'],
	'data'=>$sandpay['json'],
	'sign'=>$sandpay['data']['sign'],
));
$sandpay['response']=array();
//$sandpay['response']['result']='';
if(!isset($sandpay['response']['result'])){
	$sandpay['response']['result']=sandpay_http_post($sandpay['host'].$sandpay['path'], $sandpay['request_string']);
}
$sandpay['response']['string']=urldecode($sandpay['response']['result']);
if(substr($sandpay['response']['string'],0,8)!='charset='){
	exit($sandpay['response']['result']);
}
parse_str($sandpay['response']['string'],$sandpay['response']['param']);
$sandpay['response']['data']=json_decode($sandpay['response']['param']['data'], true);
if($sandpay['response']['data']['head']['respCode']!='000000'){
	exit($sandpay['response']['data']['head']['respMsg']);
}
$sandpay['qrcode']=$sandpay['response']['data']['body']['qrCode'];

$sandpay['public_key'] = "-----BEGIN CERTIFICATE-----\n".wordwrap(base64_encode(file_get_contents($sandpay['cer_path'])), 64, "\n", true)."-----END CERTIFICATE-----\n";
$sandpay['verify']=(bool)openssl_verify($sandpay['response']['param']['data'], base64_decode($sandpay['response']['param']['sign']), openssl_get_publickey($sandpay['public_key']), version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA1 : SHA1);

exit(json_encode($sandpay));



function sandpay_http_post($url, $data, $header = null){
	if($data){
		if($header == null){
			$header="Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser";
		}
		$response=file_get_contents($url, false, stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header'  => $header,
				'content' => $data
			),
			'ssl'=>array(
				'verify_peer'=>false,
				'verify_peer_name'=>false
			)
		)));
	}else{
		$response=file_get_contents($url);
	}
	return $response;
}
?>
