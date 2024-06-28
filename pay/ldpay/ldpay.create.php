<?php
include_once('./ldpay.config.php');

$ldpay['data']=array();
$ldpay['data']['mer_id']=$ldpay['mer_id'];
$ldpay['data']['order_id']=date('YmdHis').rand(1000,9999);
$ldpay['data']['amount']=floatval('1.01'*100);
$ldpay['data']['goods_inf']=$ldpay['data']['order_id'];
$ldpay['data']['notify_url']='http://okgo.pp.ua/callback/';

$ldpay['response_string']=file_get_contents($ldpay['host'].'/LDPayment/scancode_order', false, stream_context_create(array(
	'http' => array(
		'method' => 'POST',
		'header'  => "Content-type:application/json\r\nUser-Agent:AppBrowser",
		'content' => json_encode($ldpay['data'], JSON_UNESCAPED_UNICODE)
	),
	'ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false
	)
)));
$ldpay['response']=json_decode($ldpay['response_string'], true);
exit(json_encode($ldpay, JSON_UNESCAPED_UNICODE));
