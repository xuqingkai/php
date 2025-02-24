<?php
//接口文档
//https://www.yuque.com/chenyanfei-sjuaz/uhng8q/uoce7b

//网关请求地址
$hnapay['host']='https://gateway.hnapay.com';

//商户编号
//https://portal.hnapay.com/
$hnapay['merId']='';

//微信报备编号 https://merchant.hnapay.com
$hnapay['weChatMchId']='';

//支付宝报备编号 https://merchant.hnapay.com
$hnapay['merchantId']='';

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

/*
上述密钥下载后是个jks文件和一个说明文件（内含各种密码），需要按照以下炒作转为pen格式
- 安装OpenSSL
- 在jks文件所在目录下，执行以下命令，先转为pfx格式文件
- 其中000000为存储密码、111111为别名，会提示再次输入密码（第三个），如:222222

```
keytool -v -importkeystore -srckeystore demo.jks -srcstoretype jks -srcstorepass 000000 -destkeystore demo.pfx -deststoretype pkcs12 -deststorepass 000000 -destkeypass 111111
```

- 再进入到OpenSSL安装目录下的bin目录，执行以下代码，转为key格式
- 会提示输入一个密码，也就是前面的存储密码：000000
  
```
./openssl.exe pkcs12 -in demo.pfx -nocerts -nodes -out demo.key
```
- 生成的demo.key里就有pem格式密钥

*/
//接口公钥
$hnapay['public_key']='';
$hnapay['public_key']=base64_encode(hex2bin($hnapay['public_key']));

//新收款密钥
$hnapay['xinshoukuan_private_key']='-----BEGIN RSA PRIVATE KEY-----
-----END RSA PRIVATE KEY-----';

//收款密钥
$hnapay['shoukuan_private_key']='-----BEGIN RSA PRIVATE KEY-----
-----END RSA PRIVATE KEY-----';

//付款密钥
$hnapay['fukuan_private_key']='-----BEGIN RSA PRIVATE KEY-----
-----END RSA PRIVATE KEY-----';
