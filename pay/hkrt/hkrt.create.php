<?php
include_once('./hkrt.config.php');
header('Content-type:application/json');

$hkrt['data']=[];
$hkrt['data']['agent_no']=$hkrt['agent_no'];
$hkrt['data']['req_id']=date('YmdHis').rand(10000,9999);

$hkrt['data']['accessid']=$hkrt['accessid'];
$hkrt['data']['merch_no']=$hkrt['merch_no'];
$hkrt['data']['pay_type']='ALI';
$hkrt['data']['pay_mode']='NATIVE';
//$hkrt['data']['appid']='';//微信支付时使用：微信分配的公众账号 ID
//$hkrt['data']['openid']='';//pay_mode=JSAPI时此参数必传
$hkrt['data']['out_trade_no']=date('YmdHis').rand(10000,9999);
$hkrt['data']['total_amount']='1.23';
$hkrt['data']['notify_url']='http://www.meak.cn/callback/';
$hkrt['data']['pn']='WZ000001';

$hkrt['sign_str']='';
ksort($hkrt['data']);
foreach($hkrt['data'] as $key=>$val){ $hkrt['sign_str'].='&'.$key.'='.$val; }
$hkrt['sign_str']=substr($hkrt['sign_str'],1).$hkrt['accesskey'];
$hkrt['data']['sign']=strtoupper(md5($hkrt['sign_str']));
$hkrt['response_string']=file_get_contents($hkrt['host'].$hkrt['path'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/json;charset=UTF-8",'content'=>json_encode($hkrt['data'])),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
$hkrt['response']=json_decode($hkrt['response_string'], true);

exit(json_encode($hkrt));

?>
