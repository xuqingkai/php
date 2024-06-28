<?php
include_once('./19payment.config.php');

$_19payment['notify']=$_GET;
if(!count($_19payment['notify'])){$_19payment['notify']=$_GET;}
$_19payment['data']=[];
$_19payment['data']['version']=$_19payment['notify']['version'];
$_19payment['data']['status']=$_19payment['notify']['status'];
$_19payment['data']['parter']=$_19payment['notify']['parter'];
$_19payment['data']['orderno']=$_19payment['notify']['orderno'];
$_19payment['data']['amount']=$_19payment['notify']['amount'];

$_19payment['temp']=[];
$_19payment['temp']['sign_str']='';
foreach ($_19payment['data'] as $key=>$val){ $_19payment['temp']['sign_str'].='&'.$key.'='.$val; }
$_19payment['temp']['sign_str']=substr($_19payment['temp']['sign_str'],1).'&key='.$_19payment['config']['key'];
$_19payment['temp']['sign_string']=htmlspecialchars($_19payment['temp']['sign_str']);
$_19payment['data']['sign']=md5($_19payment['temp']['sign_str']);

exit(json_encode($_19payment));

if($_19payment['data']['sign']!=$_19payment['notify']['sign']){
    exit('sign_verify_fail:'.$_19payment['data']['sign']);
}elseif($_19payment['notify']['status']!='success'){
    exit('status!=success');
}else{
    exit('ok');
}

?>