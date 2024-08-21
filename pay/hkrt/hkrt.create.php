<?php
include_once('./hkrt.config.php');
header('Content-type:application/json');

$hkrt['path']='/api/v2/pay/pre-pay';
$hkrt['request']=[];
$hkrt['request']['agent_no']=$hkrt['agent_no'];
$hkrt['request']['merch_no']=$hkrt['merch_no'];
$hkrt['request']['accessid']=$hkrt['accessid'];

$hkrt['request']['req_id']=date('YmdHis').rand(10000,99999);
$hkrt['request']['pay_type']='ALI';
$hkrt['request']['pay_mode']='NATIVE';
//$hkrt['request']['appid']='';//微信支付时使用：微信分配的公众账号 ID
//$hkrt['request']['openid']='';//pay_mode=JSAPI时此参数必传
$hkrt['request']['out_trade_no']=date('YmdHis').rand(10000,99999);
$hkrt['request']['total_amount']='1.23';
$hkrt['request']['notify_url']='http://www.meak.cn/callback/';
$hkrt['request']['pn']='WZ000001';

$hkrt['sign_string']='';
ksort($hkrt['request']);
foreach($hkrt['request'] as $key=>$val){ $hkrt['sign_string'].='&'.$key.'='.$val; }
$hkrt['sign_string']=substr($hkrt['sign_string'],1).$hkrt['accesskey'];
$hkrt['request']['sign']=strtoupper(md5($hkrt['sign_string']));
$hkrt['request_string']=json_encode($hkrt['request'],JSON_UNESCAPED_UNICODE);
$hkrt['response_string']=file_get_contents($hkrt['host'].$hkrt['path'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/json;charset=UTF-8",'content'=>$hkrt['request_string']),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
$hkrt['response']=json_decode($hkrt['response_string'], true);

exit(json_encode($hkrt));

?>
