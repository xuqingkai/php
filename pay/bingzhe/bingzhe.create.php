<?php
include_once('./bingzhe.config.php');

$bingzhe['data'] = array();
$bingzhe['data']['contact']='contact';
//$bingzhe['data']['expireTime']='3';
$bingzhe['data']['hrefBackUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'];
$bingzhe['data']['merId']=$bingzhe['merId'];
$bingzhe['data']['money']=intval('1');
$bingzhe['data']['notifyAddress']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/')).'/notify.php';
//$bingzhe['data']['notifyAddress']='http://okqq.eu.org/callback/';
$bingzhe['data']['orderNo']=date('YmdHis').rand(1000,9999);
//$bingzhe['data']['payer']='payer';
$bingzhe['data']['payerIp']='127.0.0.1';
$bingzhe['data']['subject']='subject';
$bingzhe['data']['typeCode']='3';//码,1=支付宝，2=微信 3:微信H5,4:支付宝H5
if($bingzhe['data']['typeCode']=='3' || $bingzhe['data']['typeCode']=='4'){
	$bingzhe['data']['sceneType']='Wap';//通道类型为H5时必填(示例值：iOS, Android, Wap)
}

ksort($bingzhe['data']);
$bingzhe['str']='';
foreach($bingzhe['data'] as $key=>$val){ $bingzhe['str'].='&'.$key.'='.$val; }
$bingzhe['str']=substr($bingzhe['str'],1).'&key='.$bingzhe['key'];
$bingzhe['data']['sign']=md5($bingzhe['str']);
$bingzhe['request']=http_build_query($bingzhe['data']);

$bingzhe['url']=$bingzhe['host'].$bingzhe['path'].'?'.htmlspecialchars($bingzhe['request']);

$bingzhe['response']=file_get_contents($bingzhe['host'].$bingzhe['path'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/x-www-form-urlencoded",'content'=>$bingzhe['request']),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
$bingzhe['json']=json_decode($bingzhe['response'], true);
exit(json_encode($bingzhe));
if($bingzhe['json']['code']==200){
	exit('<a href="'.$bingzhe['url'].'">'.$bingzhe['url'].'</a>');
}else{
	exit($bingzhe['json']['msg']);
}

?>
