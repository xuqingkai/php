<?php
include_once('./usdpay.config.php');

$usdpay['data']=[];
$usdpay['data']['pid']=$usdpay['config']['id'];
$usdpay['data']['type']='alipc';
$usdpay['data']['out_trade_no']=date('YmdHis').rand(1000,9999);
$usdpay['data']['notify_url']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/')).'/usdpay.notify.php';
$usdpay['data']['return_url']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"];
$usdpay['data']['name']='name';
$usdpay['data']['money']=number_format($usdpay['config']['money']??'10.00', 2, '.', '');;
$usdpay['data']['sitename']='sitename';

$usdpay['temp']=[];
$usdpay['temp']['sign_str']='';
ksort($usdpay['data']);
foreach ($usdpay['data'] as $key=>$val){ $usdpay['temp']['sign_str'].='&'.$key.'='.$val; }
$usdpay['temp']['sign_str']=substr($usdpay['temp']['sign_str'],1).''.$usdpay['config']['key'];
$usdpay['temp']['sign_string']=htmlspecialchars($usdpay['temp']['sign_str']);
$usdpay['data']['sign']=md5($usdpay['temp']['sign_str']);
$usdpay['data']['sign_type']='md5';

$usdpay['request']=[];
$usdpay['request']['str']=http_build_query($usdpay['data']);
$usdpay['request']['string']=htmlspecialchars($usdpay['request']['str']);
$usdpay['request']['url']=$usdpay['config']['host'].$usdpay['config']['path'].'?'.$usdpay['request']['str'];
$usdpay['response']=[];
//header('Location:'.$usdpay['request']['url']);exit();
exit('<a href="'.$usdpay['request']['url'].'">'.$usdpay['request']['url'].'</a>');

