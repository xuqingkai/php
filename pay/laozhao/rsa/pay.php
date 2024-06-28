<?php
$api_url='http://sdpay.yingbinginfocom.website/pay/create.aspx';
$api_id='';
$sign_key='';
$private_key='';
$public_key='';

$data['appId']=$api_id;
$data['payMethod']='992';
$data['amount']=$_GET['price'];
$data['orderId']=$_GET['id'].rand(0,10000);
$data['notifyUrl']='http://'.$_SERVER['HTTP_HOST'].'/';
$str='';foreach($data as $key=>$val){ $str.='&'.$key.'='.$val; }
//exit(substr($str,1).$sign_key);
$data['sign']=md5(substr($str,1).$sign_key);

$private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($private_key, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
//exit($private_key);
$public_key = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
openssl_public_encrypt($data['notifyUrl'], $notifyUrl, $public_key);
//exit(base64_encode($notifyUrl));
//exit(sslEn($data['notifyUrl'], $private_key));
$data['notifyUrl']=sslEn($data['notifyUrl'], $private_key);

$str='';foreach($data as $key=>$val){ $str.='&'.$key.'='.urlencode($val); }
$url=$api_url.'?'.substr($str,1);
//exit('<a href="'.$url.'">'.$url.'</a>');
header('Location: '.$url);exit();
