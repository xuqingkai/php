<?php
//接口文档
//https://www.yuque.com/chenyanfei-sjuaz/uhng8q/uoce7b

//网关请求地址
$hnapay['host']='https://gateway.hnapay.com';

//商户编号
//https://portal.hnapay.com/
$hnapay['merId']='';


//接口付款公钥，位于demo里
$hnapay['public_key']='./test.cer';
if(is_file($hnapay['public_key'])){ $hnapay['public_key'] = file_get_contents($hnapay['public_key']);}
if(strpos($hnapay['public_key'], 'PUBLIC KEY')===false){
  $hnapay['public_key'] = "-----BEGIN PUBLIC KEY-----\n".wordwrap($hnapay['public_key'], 64, "\n", true)."\n-----END PUBLIC KEY-----";
  //$hnapay['public_key'] = "-----BEGIN CERTIFICATE-----\n".chunk_split($hnapay['public_key'], 64, "\n")."\n-----BEGIN CERTIFICATE-----";
}

/*
5.1 微信公众号&支付宝生活号
5.2 扫码支付（C扫B）
5.3 支付宝H5
5.4 付款到银行
5.5 查询接口-通用
5.6 查询接口-扫码API
5.7 查询接口-代付
5.8 退款接口
5.9 扫码支付（B扫C）
5.10 商户自助进件接口
5.1、5.3、5.5、5.8、5.9、5.10--新收款密钥     
5.2、5.6--收款密钥
5.4、5.7--付款密钥
*/

//微信报备编号 https://merchant.hnapay.com
$hnapay['weChatMchId']='';
//微信接口付款公钥
$hnapay['weixin_public_key']='-----BEGIN PUBLIC KEY-----
-----END PUBLIC KEY-----';
if(is_file($hnapay['weixin_public_key'])){ $hnapay['weixin_public_key'] = file_get_contents($hnapay['weixin_public_key']);}
if(strpos($hnapay['weixin_public_key'], 'PUBLIC KEY')===false){
  $hnapay['weixin_public_key'] = "-----BEGIN PUBLIC KEY-----\n".wordwrap($hnapay['weixin_public_key'], 64, "\n", true)."\n-----END PUBLIC KEY-----";
  //$hnapay['weixin_public_key'] = "-----BEGIN CERTIFICATE-----\n".chunk_split($hnapay['weixin_public_key'], 64, "\n")."\n-----BEGIN CERTIFICATE-----";
}
//微信商户私钥，jks文件转pem过来
$hnapay['weixin_private_key']='-----BEGIN RSA PRIVATE KEY-----
-----END RSA PRIVATE KEY-----';

//支付宝报备编号 https://merchant.hnapay.com
$hnapay['merchantId']='';
//支付宝接口付款公钥
$hnapay['alipay_public_key']='-----BEGIN PUBLIC KEY-----
-----END PUBLIC KEY-----';
//支付宝商户私钥
$hnapay['alipay_private_key']='-----BEGIN RSA PRIVATE KEY-----
-----END RSA PRIVATE KEY-----';
