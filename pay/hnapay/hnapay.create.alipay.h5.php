<?php
include_once('./hnapay.config.php');

$hnapay['data']=array();
$hnapay['data']['version']='2.0';
$hnapay['data']['tranCode']='MUP11';
$hnapay['data']['merId']=$hnapay['merId'];
$hnapay['data']['merOrderId']=date('YmdHis').rand(1000,9999);
$hnapay['data']['submitTime']=date('YmdHis');
$hnapay['msgCiphertext']=[];
$hnapay['msgCiphertext']['tranAmt']=floatval('1.01'*1);
$hnapay['msgCiphertext']['payType']='HnaZFB';
$hnapay['msgCiphertext']['exPayMode']='';
$hnapay['msgCiphertext']['cardNo']='';
$hnapay['msgCiphertext']['holderName']='';
$hnapay['msgCiphertext']['identityCode']='';
$hnapay['msgCiphertext']['merUserId']='';
$hnapay['msgCiphertext']['orderExpireTime']='';
$hnapay['msgCiphertext']['frontUrl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"];
$hnapay['msgCiphertext']['notifyUrl']='http://okgo.pp.ua/callback/';
$hnapay['msgCiphertext']['riskExpand']='';
$hnapay['msgCiphertext']['goodsInfo']='';
$hnapay['msgCiphertext']['orderSubject']='order'.$hnapay['data']['merOrderId'];
$hnapay['msgCiphertext']['orderDesc']='';
$hnapay['msgCiphertext']['merchantId']='{"02":"'.$hnapay['merchantId'].'"}';
$hnapay['msgCiphertext']['bizProtocolNo']='';
$hnapay['msgCiphertext']['payProtocolNo']='';
$hnapay['msgCiphertext']['merUserIp']=$_SERVER['REMOTE_ADDR'];
$hnapay['msgCiphertext']['payLimit']='';

$hnapay['encrypted']=array();
foreach(str_split(json_encode($hnapay['msgCiphertext'], JSON_UNESCAPED_UNICODE), 117) as $hnapay['item']){
    $hnapay['item_encrypted']='';
    openssl_public_encrypt($hnapay['item'], $hnapay['item_encrypted'], "-----BEGIN PUBLIC KEY-----\n".wordwrap($hnapay['alipay_public_key'], 64, "\n", true)."\n-----END PUBLIC KEY-----");
    $hnapay['encrypted'][]=$hnapay['item_encrypted'];
}
$hnapay['data']['msgCiphertext']=base64_encode(implode('',$hnapay['encrypted']));
$hnapay['data']['signType']='1';//1：RSA，3：国密交易证书，4：国密密钥
$hnapay['data']['merAttach']='merAttach';//1：UTF-8
$hnapay['data']['charset']='1';//1：UTF-8

$hnapay['sign_str']='';
$hnapay['sign_str'].='version=['.$hnapay['data']['version'].']';
$hnapay['sign_str'].='tranCode=['.$hnapay['data']['tranCode'].']';
$hnapay['sign_str'].='merId=['.$hnapay['data']['merId'].']';
$hnapay['sign_str'].='merOrderId=['.$hnapay['data']['merOrderId'].']';
$hnapay['sign_str'].='submitTime=['.$hnapay['data']['submitTime'].']';
$hnapay['sign_str'].='signType=['.$hnapay['data']['signType'].']';
$hnapay['sign_str'].='charset=['.$hnapay['data']['charset'].']';
$hnapay['sign_str'].='msgCiphertext=['.$hnapay['data']['msgCiphertext'].']';

openssl_sign($hnapay['sign_str'], $hnapay['data']['signValue'], "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($hnapay['alipay_private_key'], 64, "\n", true)."\n-----END RSA PRIVATE KEY-----", version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA1 : SHA1);
$hnapay['data']['signValue']=base64_encode($hnapay['data']['signValue']);
    
$html='<form action="'.$hnapay['host'].'/multipay/h5.do'.'" method="post" target="_blank">';
foreach($hnapay['data'] as $key=>$val){
    $html.=$key.'=<input type="text" name="'.$key.'" value="'.$val.'" ><br />';
}
$html.='<input type="submit" value="submit" ><br />';
$html.='</form>';
$html.='<script>document.getElementsByTagName("form")[0].submit();</script>';
$html='<!DOCTYPE html><html lang="zh"><head><meta http-equiv="Content-type" content="text/html; charset=utf-8" /><meta name="viewport" content="width=device-width, initial-scale=1.0" /></head><body>'.$html.'</body></html>';
exit($html);


