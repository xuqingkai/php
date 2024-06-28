<?php
include_once('./usdpay.config.php');

$usdpay['notify']=$_GET;
$usdpay['data']=[];
$usdpay['data']['pid']=$usdpay['notify']['pid'];
$usdpay['data']['trade_no']=$usdpay['notify']['trade_no'];
$usdpay['data']['out_trade_no']=$usdpay['notify']['out_trade_no'];
$usdpay['data']['type']=$usdpay['notify']['type'];
$usdpay['data']['name']=$usdpay['notify']['name'];
$usdpay['data']['money']=$usdpay['notify']['money'];
$usdpay['data']['trade_status']=$usdpay['notify']['trade_status'];

$usdpay['temp']=[];
$usdpay['temp']['sign_str']='';
ksort($usdpay['data']);
foreach ($usdpay['data'] as $key=>$val){ $usdpay['temp']['sign_str'].='&'.$key.'='.$val; }
$usdpay['temp']['sign_str']=substr($usdpay['temp']['sign_str'],1).''.$usdpay['config']['key'];
$usdpay['temp']['sign_string']=htmlspecialchars($usdpay['temp']['sign_str']);
$usdpay['data']['sign']=md5($usdpay['temp']['sign_str']);

//exit(json_encode($usdpay));

if($usdpay['data']['sign']!=$usdpay['notify']['sign']){
    exit('sign_verify_fail:'.$usdpay['data']['sign']);
}elseif($usdpay['notify']['trade_status']!='TRADE_SUCCESS'){
    exit('trade_status!=TRADE_SUCCESS');
}else{
    $order_no = $usdpay['data']['out_trade_no'];
    $trade_no = $usdpay['data']['trade_no'];
    exit('success');
}

