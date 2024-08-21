<?php
include_once('./hkrt.config.php');
header('Content-type:application/json');

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
$hkrt['request']['pn']='WZ000001';//通过PN接口获取

$hkrt['sign_string']='';
ksort($hkrt['request']);
foreach($hkrt['request'] as $key=>$val){ if(strtolower($key)!='sign' && strlen($val)>0){$hkrt['sign_string'].='&'.$key.'='.$val;} }
$hkrt['sign_string']=substr($hkrt['sign_string'],1).$hkrt['accesskey'];
$hkrt['request']['sign']=strtoupper(md5($hkrt['sign_string']));
$hkrt['request_string']=json_encode($hkrt['request'],JSON_UNESCAPED_UNICODE);
$hkrt['response_string']=file_get_contents($hkrt['host'].$hkrt['path'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/json;charset=UTF-8",'content'=>$hkrt['request_string']),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
$hkrt['response']=json_decode($hkrt['response_string'], true);
/*
{
    "merch_no": "",
    "sign": "8AC8F4D05CA231F829FAB56149D9F4E7",
    "ali_trade_no": null,
    "agent_no": "",
    "return_msg": "成功",
    "wc_pay_data": null,
    "result_msg": "请求成功",
    "pay_mode": "NATIVE",
    "out_trade_no": "2024082113424064047",
    "code_url": null,
    "uniqr_qr_code": null,
    "uniqr_redirect_url": null,
    "trade_no": "AL2408211342410001909600459",
    "pay_type": "ALI",
    "result_code": "10000",
    "ali_qr_code": "",
    "return_code": "SUCCESS"
}
*/
exit(json_encode($hkrt,JSON_UNESCAPED_UNICODE));

?>
