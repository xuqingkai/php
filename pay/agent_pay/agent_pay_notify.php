<?php
include_once('./agent_pay_config.php');

$agent_pay['notify_data']=$_POST;
$agent_pay['orderCreateTime']=substr($agent_pay['notify_data']['orderNo'],0,4);
$agent_pay['orderCreateTime'].='-'.substr($agent_pay['notify_data']['orderNo'],4,2);
$agent_pay['orderCreateTime'].='-'.substr($agent_pay['notify_data']['orderNo'],6,2);
$agent_pay['orderCreateTime'].=' '.substr($agent_pay['notify_data']['orderNo'],8,2);
$agent_pay['orderCreateTime'].=':'.substr($agent_pay['notify_data']['orderNo'],10,2);
$agent_pay['orderCreateTime'].=':'.substr($agent_pay['notify_data']['orderNo'],12,2);

$agent_pay['data']=[];
$agent_pay['data']['interfaceType']='3';//微信扫码：interfaceType=2，支付宝H5：interfaceType=3
$agent_pay['data']['merchantId']=$agent_pay['merchantId'];
$agent_pay['data']['orderCreateTime']=$agent_pay['orderCreateTime'];
$agent_pay['data']['orderNo']=$agent_pay['notify_data']['orderNo'];
$agent_pay['data']['supType']='1';//1.新生支付 2.智付
$agent_pay['request_string']=json_encode($agent_pay['data'], JSON_UNESCAPED_UNICODE);

$agent_pay['response_string']=file_get_contents(trim($agent_pay['host'],'/').'/merchant/query', false, stream_context_create(array(
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
if($agent_pay['response_json']['code']==200){
    //交易状态1：订单交易成功2：订单支付中3：订单交易失效4：订单交易失败5：订单退款中6：订单已退款
    if($agent_pay['response_json']['data']['payStatus']=='1'){
        
    }
}
exit('200');
?>
