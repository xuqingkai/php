<?php
//接口文档
//https://www.yuque.com/chenyanfei-sjuaz/uhng8q/uoce7b

//网关请求地址
$hnapay['host']='https://gateway.hnapay.com';

//商户编号
//https://portal.hnapay.com/
$hnapay['merId']='';


//接口付款公钥
$hnapay['public_key']='';
$hnapay['public_key']=str_replace('-----BEGIN PUBLIC KEY-----','',str_replace('-----END PUBLIC KEY-----','',$hnapay['public_key']));
$hnapay['public_key']=str_replace("\r",'',str_replace("\n",'',$hnapay['public_key']));


//微信报备编号 https://merchant.hnapay.com
$hnapay['weChatMchId']='';
//微信接口付款公钥
$hnapay['weixin_public_key']='';
$hnapay['weixin_public_key']=str_replace('-----BEGIN PUBLIC KEY-----','',str_replace('-----END PUBLIC KEY-----','',$hnapay['weixin_public_key']));
$hnapay['weixin_public_key']=str_replace("\r",'',str_replace("\n",'',$hnapay['weixin_public_key']));
//微信商户私钥
$hnapay['weixin_private_key']='';
$hnapay['weixin_private_key']=str_replace('-----BEGIN RSA PRIVATE KEY-----','',str_replace('-----END RSA PRIVATE KEY-----','',$hnapay['weixin_private_key']));
$hnapay['weixin_private_key']=str_replace("\r",'',str_replace("\n",'',$hnapay['weixin_private_key']));

//支付宝报备编号 https://merchant.hnapay.com
$hnapay['merchantId']='';
//支付宝接口付款公钥
$hnapay['alipay_public_key']='';
$hnapay['alipay_public_key']=str_replace('-----BEGIN PUBLIC KEY-----','',str_replace('-----END PUBLIC KEY-----','',$hnapay['alipay_public_key']));
$hnapay['alipay_public_key']=str_replace("\r",'',str_replace("\n",'',$hnapay['alipay_public_key']));
//支付宝商户私钥
$hnapay['alipay_private_key']='';
$hnapay['alipay_private_key']=str_replace('-----BEGIN RSA PRIVATE KEY-----','',str_replace('-----END RSA PRIVATE KEY-----','',$hnapay['alipay_private_key']));
$hnapay['alipay_private_key']=str_replace("\r",'',str_replace("n",'',$hnapay['alipay_private_key']));
