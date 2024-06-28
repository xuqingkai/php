<?php
//接口文档
//https://open.sandpay.com.cn/product/detail/43305/43671/43673

//商户编号
$sandpay['mid'] = '';
//平台公钥
$sandpay['cer_path'] = './sandpay.cer';
//商户私钥
$sandpay['pfx_path'] = './shanghu.pfx';
//商户私钥密码
$sandpay['pfx_pwd'] = '';
//请求域名
$sandpay['host'] = "https://cashier.sandpay.com.cn";
//请求路径
$sandpay['path'] = "/qr/api/order/create";