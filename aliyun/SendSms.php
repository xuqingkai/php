<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>首页</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<?php
$PhoneNumbers=$_POST['PhoneNumbers'];
if($PhoneNumbers)
{
	$dict['AccessKeyId']=$_POST['AccessKeyId'];
	$dict['Timestamp']=str_replace(' ', 'T',date('Y-m-d H:i:s',time()-8*60*60)) . 'Z';
	$dict['SignatureMethod']='HMAC-SHA1';
	$dict['SignatureVersion']='1.0';
	$dict['SignatureNonce']=md5(date('YmdHis').''.rand(10000,99999));
	$dict['Action']='SendSms';
	$dict['Version']='2017-05-25';
	$dict['RegionId']='cn-hangzhou';
	$dict['PhoneNumbers']=$PhoneNumbers;
	$dict['SignName']=$_POST['SignName'];
	$dict['TemplateCode']=$_POST['TemplateCode'];
	$dict['TemplateParam']='{"name":"aaa","count":"1","summary":"ccc"}';
	
	ksort($dict);
	$data = '';
	foreach ($dict as $key => $value)
	{
		$data .= "&" . $key . "=" . str_replace("%7E", "~", str_replace("*", "%2A", str_replace("+", "%20", urlencode($value))));
	}
	$data = "GET&%2F&" . urlencode(substr($data, 1));
	$sign = hash_hmac('sha1', $data, $_POST['SHA1Key'].'&', true);
	$sign = base64_encode($sign);
	
	$query = '';
	foreach ($dict as $key => $value)
	{
		$query .= "&" . $key . "=" . urlencode($value);
	}
	$query = substr($query,1);
	$query .= "&Signature=".urlencode($sign);
	$url = 'http://dysmsapi.aliyuncs.com/?'.$query;
	echo('<a href="'.$url.'" target="_blank">'.$url.'</a>');
	//$result = file_get_contents($url);
	//echo($result);
}
?>
<form method="post" action="?">
    <p>AccessKeyId=<input type="text" name="AccessKeyId" /></p>
    <p>SHA1Key=<input type="text" name="SHA1Key" /></p>
    <p>PhoneNumbers=<input type="text" name="PhoneNumbers" /></p>
    <p>SignName=<input type="text" name="SignName" /></p>
    <p>TemplateCode=<input type="text" name="TemplateCode" /></p>
    <p><input type="submit" value="提交" /></p>
</form>
</body>
</html>
