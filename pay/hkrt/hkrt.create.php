<?php
include_once('./hkrt.config.php');
header('Content-type:application/json');

$hkrt['host']='https://saas-front.hkrt.cn';
$hkrt['path']='/api/v2/pay/pre-pay';
$hkrt['merch_no_list']=explode(',','833103181112245,833466873720541');
$hkrt['merch_no']=$hkrt['merch_no_list'][rand(1,count($hkrt['merch_no_list']))-1];

$hkrt['agent_no']='ISV003440';
$hkrt['accessid']='4028809891321ec60191454113981ef0';
$hkrt['accesskey']='526d274d850b470a97f4c8e38399bacb';

$hkrt['translatekey']='F36116F51B262562B0F0D537A2463485';
$hkrt['password']='';

$hkrt['request']=[];
$hkrt['request']['agent_no']=$hkrt['agent_no'];
$hkrt['request']['req_id']=date('YmdHis').rand(10000,9999);

$hkrt['request']['accessid']=$hkrt['accessid'];
$hkrt['request']['merch_no']=$hkrt['merch_no'];
$hkrt['request']['pay_type']='ALI';
$hkrt['request']['pay_mode']='NATIVE';
//$hkrt['request']['appid']='';//微信支付时使用：微信分配的公众账号 ID
//$hkrt['request']['openid']='';//pay_mode=JSAPI时此参数必传
$hkrt['request']['out_trade_no']=date('YmdHis').rand(10000,9999);
$hkrt['request']['total_amount']='1.23';
$hkrt['request']['notify_url']='http://www.meak.cn/callback/';
$hkrt['request']['pn']='WZ000001';

$hkrt['sign_string']='';
ksort($hkrt['request']);
foreach($hkrt['request'] as $key=>$val){ $hkrt['sign_string'].='&'.$key.'='.$val; }
$hkrt['sign_string']=substr($hkrt['sign_string'],1).$hkrt['accesskey'];
$hkrt['request']['sign']=strtoupper(md5($hkrt['sign_string']));
$hkrt['response_string']=file_get_contents($hkrt['host'].$hkrt['path'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/json;charset=UTF-8",'content'=>json_encode($hkrt['request'])),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
$hkrt['response']=json_decode($hkrt['response_string'], true);

exit(json_encode($hkrt));

?>
