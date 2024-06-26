<?php
include_once('./hnapay.config.php');
$hnapay['data']=$_POST;

$hnapay['sign_str']='';
$hnapay['sign_str'].='tranCode=['.$hnapay['data']['tranCode'].']';
$hnapay['sign_str'].='version=['.$hnapay['data']['version'].']';
$hnapay['sign_str'].='merId=['.$hnapay['data']['merId'].']';
$hnapay['sign_str'].='merOrderNum=['.$hnapay['data']['merOrderNum'].']';
$hnapay['sign_str'].='tranAmt=['.$hnapay['data']['tranAmt'].']';
$hnapay['sign_str'].='submitTime=['.$hnapay['data']['submitTime'].']';
$hnapay['sign_str'].='hnapayOrderId=['.$hnapay['data']['hnapayOrderId'].']';
$hnapay['sign_str'].='tranFinishTime=['.$hnapay['data']['tranFinishTime'].']';
$hnapay['sign_str'].='respCode=['.$hnapay['data']['respCode'].']';
$hnapay['sign_str'].='charset=['.$hnapay['data']['charset'].']';
$hnapay['sign_str'].='signType=['.$hnapay['data']['signType'].']';

$hnapay['public_rsa']=openssl_get_publickey("-----BEGIN PUBLIC KEY-----\n".wordwrap($hnapay['public_key'], 64, "\n", true)."\n-----END PUBLIC KEY-----");
$hnapay['sign_verify']=(bool)openssl_verify($hnapay['sign_str'], hex2bin($hnapay['data']['signMsg']), $hnapay['public_rsa'], version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA1 : SHA1);

if(!$hnapay['sign_verify']){ exit('RespCode=500'); }
if($hnapay['data']['resultCode']!='0000'){ exit('RespCode=501'); }
exit('RespCode=200');

