<?php
include_once('./agent_pay_config.php');

$agent_pay['data']=[];
$agent_pay['data']['frontUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"];
$agent_pay['data']['interfaceType']='3';//微信扫码：interfaceType=2，支付宝H5：interfaceType=3
//$agent_pay['data']['merUserId']=$agent_pay['merUserId'];//商户公众号id/生活号id 当serviceType = 1，3，7，8时必填
$agent_pay['data']['merchantId']=$agent_pay['merchantId'];
$agent_pay['data']['notifyUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"];
$agent_pay['data']['orderAmount']=number_format('1.00', 2, '.', '');
$agent_pay['data']['orderNo']=date('YmdHis').rand(1000,9999);
$agent_pay['data']['orderTime']=date('Y-m-d H:i:s');
//$agent_pay['data']['productDesc']='';
$agent_pay['data']['productName']='productName';
$agent_pay['data']['serviceType']='11';//微信扫码：serviceType＝10 ，支付宝H5：serviceType＝11
$agent_pay['data']['supType']='1';//1.新生支付 2.智付
//$agent_pay['data']['userId']='';//用户openId/用户id 当serviceType = 1，3，7，8时必填
$agent_pay['data']['userAccount']=$agent_pay['userAccount'];
$agent_pay['data']['password']=base64_decode($agent_pay['password']);
$agent_pay['request_string']=json_encode($agent_pay['data'], JSON_UNESCAPED_UNICODE);

$agent_pay['response_string']=file_get_contents(trim($agent_pay['host'],'/').$agent_pay['path'], false, stream_context_create(array(
	'http' => array(
		'method' => 'POST',
		'header'  => "Content-Type:application/json",
		'content' => $agent_pay['request_string']
	),
	'ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false
	)
)));
$agent_pay['response_json']=json_decode($agent_pay['response_string'], true);
if($agent_pay['response_json']['code']!=200){ exit($agent_pay['response_json']['msg']); }

$agent_pay['pay_url']=$agent_pay['response_json']['data'];
header('Content-Type:application/json');

exit(json_encode($agent_pay, JSON_UNESCAPED_UNICODE));
?>
