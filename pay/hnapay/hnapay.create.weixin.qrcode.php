<?php
include_once('./hnapay.config.php');

$hnapay['data']=array();
$hnapay['data']['version']='2.1';
$hnapay['data']['tranCode']='WS01';
$hnapay['data']['merId']=$hnapay['merId'];
$hnapay['data']['merOrderNum']=date('YmdHis').rand(1000,9999);
$hnapay['data']['tranAmt']=floatval('1.01'*100);
$hnapay['data']['submitTime']=date('YmdHis');
$hnapay['data']['payType']='QRCODE_B2C';
$hnapay['data']['orgCode']='WECHATPAY';
$hnapay['data']['tranIP']=$_SERVER['REMOTE_ADDR'];
$hnapay['data']['notifyUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"];
$hnapay['data']['notifyUrl']='http://okqq.eu.org/callback/';
$hnapay['data']['weChatMchId']=$hnapay['weChatMchId'];
$hnapay['data']['charset']='1';//1：UTF-8
$hnapay['data']['signType']='1';//1：RSA，3：国密交易证书，4：国密密钥

$hnapay['sign_str']='';
$hnapay['sign_str'].='tranCode=['.$hnapay['data']['tranCode'].']';
$hnapay['sign_str'].='version=['.$hnapay['data']['version'].']';
$hnapay['sign_str'].='merId=['.$hnapay['data']['merId'].']';
$hnapay['sign_str'].='submitTime=['.$hnapay['data']['submitTime'].']';
$hnapay['sign_str'].='merOrderNum=['.$hnapay['data']['merOrderNum'].']';
$hnapay['sign_str'].='tranAmt=['.$hnapay['data']['tranAmt'].']';
$hnapay['sign_str'].='payType=['.$hnapay['data']['payType'].']';
$hnapay['sign_str'].='orgCode=['.$hnapay['data']['orgCode'].']';
$hnapay['sign_str'].='notifyUrl=['.$hnapay['data']['notifyUrl'].']';
$hnapay['sign_str'].='charset=['.$hnapay['data']['charset'].']';
$hnapay['sign_str'].='signType=['.$hnapay['data']['signType'].']';

openssl_sign($hnapay['sign_str'], $hnapay['data']['signMsg'], "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($hnapay['weixin_private_key'], 64, "\n", true)."\n-----END RSA PRIVATE KEY-----", version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA1 : SHA1);
//$hnapay['data']['signMsg']=base64_encode($hnapay['data']['signMsg']);
$hnapay['data']['signMsg']=bin2hex($hnapay['data']['signMsg']);
$hnapay['data']['signMsg']=strtoupper($hnapay['data']['signMsg']);


$hnapay['query_string']=htmlspecialchars(http_build_query($hnapay['data']));

$hnapay['response_string']=file_get_contents($hnapay['host'].'/website/scanPay.do', false, stream_context_create(array(
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
//{\"charset\":\"1\",\"msgExt\":\"\",\"hnapayOrderId\":\"2023062913943508\",\"resultCode\":\"0000\",\"errorCode\":\"\",\"version\":\"2.1\",\"merOrderNum\":\"202306291711018705\",\"submitTime\":\"20230629171101\",\"qrCodeUrl\":\"https://qrcode.hnapay.com/qrcode.shtml?qrContent=https://bisgateway.hnapay.com/wechat/connect/auth.shtml?bankOrderId=2306291711012625741&sign=9BE4ECFBB278FDB16B81C720C4671907\",\"tranAmt\":\"1.01\",\"signType\":\"1\",\"merId\":\"11000007624\",\"tranCode\":\"WS01\",\"signMsg\":\"05ab7127d44435b4ee43d3b9cb77a3d735c40b1029525d1f33f12073ef2be5198134d774b5fdfa5a9c14392cb504a449ffcd04e45d09ec1c5cba9394cc2968af2f7c2e7fc5b6d8f795fd52c73e2ea0fe938046ccf7d2ab12b9750ab75b255b7090f41739ab6e2d4a4b113359b873953994942f7da74b241fdeab9bd5effd786b\"}
$hnapay['response']=json_decode($hnapay['response_string'], true);
if($hnapay['response']['resultCode']=='0000'){
    $hnapay['qrCodeUrl']=$hnapay['response']['qrCodeUrl'];
    $hnapay['qrCodeUrl']=explode('qrContent=',$hnapay['qrCodeUrl'])[1];
    $hnapay['qrCodeUrl']=explode('&sign=',$hnapay['qrCodeUrl'])[0];
    exit($hnapay['qrCodeUrl'].'<br /><img src="'.$hnapay['response']['qrCodeUrl'].'" />');
}else{
    exit(json_encode($hnapay));
}

