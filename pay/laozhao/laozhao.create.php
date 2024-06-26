<?php
include_once('./laozhao.config.php');

$laozhao['data']=array();
$laozhao['data']['parter']=$laozhao['id'];
$laozhao['data']['type']='992';
$laozhao['data']['value']='1';
$laozhao['data']['orderid']=date('YmdHis').rand(10000,99999);
$laozhao['data']['callbackurl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"];

$laozhao['str']=''; foreach($laozhao['data'] as $key=>$val){ $laozhao['str'].='&'.$key.'='.$val; }
$laozhao['str']=substr($laozhao['str'],1).$laozhao['key'];
$laozhao['data']['sign']=md5($laozhao['str']);

$laozhao['query']=''; foreach($laozhao['data'] as $key=>$val){ $laozhao['query'].='&'.$key.'='.urlencode($val); }
$laozhao['url']=$laozhao['gateway'].'?'.substr($laozhao['query'],1);

//exit('<a href="'.$laozhao['url'].'">'.$laozhao['url'].'</a>');
header('location: '.$laozhao['url']); exit();
