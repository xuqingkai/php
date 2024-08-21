<?php
include_once('./hkrt.config.php');

$hkrt['request']=$_POST;

$hkrt['sign_string']='';
ksort($hkrt['request']);
foreach($hkrt['request'] as $key=>$val){ if(strtolower($key)!='sign' && strlen($val)>0){$hkrt['sign_string'].='&'.$key.'='.$val;} }
$hkrt['sign_string']=substr($hkrt['sign_string'],1).$hkrt['accesskey'];

$hkrt['sign']=strtoupper(md5($hkrt['sign_string']));
if($hkrt['request']['sign']!=$hkrt['sign']){ exit('{"return_code":"SIGN_ERROR","return_msg":"签名错误"}'); }
if($hkrt['request']['trade_status']!='1'){ exit('{"return_code":"TRADE_STATUS_ERROR","return_msg":"交易状态非1"}'); }

$hkrt['request']['out_trade_no'];//商户本地单号
$hkrt['request']['trade_no'];//上级接口单号
$hkrt['request']['order_amount'];//订单金额

exit('{"return_code":"SUCCESS","return_msg":"回调处理成功"}');
//exit(json_encode($hkrt,JSON_UNESCAPED_UNICODE));
