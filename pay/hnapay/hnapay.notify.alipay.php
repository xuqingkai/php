<?php
include_once('./hnapay.config.php');
$hnapay['data']=$_POST;

$hnapay['sign_str']='';
$hnapay['sign_str'].='version=['.$hnapay['data']['version'].']';
$hnapay['sign_str'].='tranCode=['.$hnapay['data']['tranCode'].']';
$hnapay['sign_str'].='merOrderId=['.$hnapay['data']['merOrderId'].']';
$hnapay['sign_str'].='merId=['.$hnapay['data']['merId'].']';
$hnapay['sign_str'].='charset=['.$hnapay['data']['charset'].']';
$hnapay['sign_str'].='signType=['.$hnapay['data']['signType'].']';
$hnapay['sign_str'].='resultCode=['.$hnapay['data']['resultCode'].']';
$hnapay['sign_str'].='hnapayOrderId=['.$hnapay['data']['hnapayOrderId'].']';

//version=[]tranCode=[]merOrderId=[]merId=[]charset=[]signType=[]resultCode=[]hnapayOrderId=[]

$hnapay['public_rsa']=openssl_get_publickey("-----BEGIN PUBLIC KEY-----\n".wordwrap($hnapay['alipay_public_key'], 64, "\n", true)."\n-----END PUBLIC KEY-----");
$hnapay['sign_verify']=(bool)openssl_verify($hnapay['sign_str'], base64_decode($hnapay['data']['signValue']), $hnapay['public_rsa'], version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA1 : SHA1);

if(!$hnapay['sign_verify']){ exit('RespCode=500'); }
if($hnapay['data']['resultCode']!='0000'){ exit('RespCode=501'); }
exit('RespCode=200');

/*
charset=1
&bankCode=ALIPAY
&hnapayOrderId=2024052915814984
&cardType=
&resultCode=0000
&errorCode=
&tranFinishTime=20240529095037
&checkDate=
&version=2.0
&userId=2088612313248301
&buyerLogonId=972***@qq.com
&signValue=p1bdInsS7qCVIM1yFOKC2lM3e2wlanZn0bjcnyUZqEnz+FtdTdK9BhJBExybPUAeD+FT/RzSRy7h9zzsJ6goxyj6Kz7rk1t0fg8WqsLb2TUC4Qb37IOV/yxxYW6YvTNnWQ+IC+PzJcSKBZAcGtAYmgCKeqWgzEWySBNMxWx+6KQ=
&errorMsg=
&payProtocolNo=
&shortCardNo=
&tranAmt=1.00
&signType=1
&bizProtocolNo=
&merId=11000009357
&merAttach=merAttach
&tranCode=MUP11
&merTxnTm=20240529095026
&merOrderId=202405290950261324
*/
/*
charset=1
&bankCode=ALIPAY
&hnapayOrderId=2024052915836494
&cardType=
&resultCode=4444
&errorCode=A0001216
&tranFinishTime=
&checkDate=
&version=2.0
&userId=
&buyerLogonId=
&signValue=R45f8eI24rD9czSvx0V97F6BaV3RocTmQB12TstXlhdeuVuoKpfvoDLass+KGwlfpybm/273omQF08y1CK76XFbAaAW//EiaClyUxYJFaoa13sppF4HQlH1xcGDv9KhsWQp+ZX5VS/rbzVPPyEK5NhgH19rfixLRcoSBrclrqvQ=
&errorMsg=银行返回流水不存在
&payProtocolNo=
&shortCardNo=
&tranAmt=48.00
&signType=1
&bizProtocolNo=
&merId=11000009357
&merAttach=merAttach
&tranCode=MUP11
&merTxnTm=20240529112811
&merOrderId=202405291118387564
*/

