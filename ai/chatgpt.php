<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>ChatGPT</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<?php
ob_clean();
$authorization='';
$type=$_GET['type'];
$prompt=$_GET['prompt'];
if(!isset($prompt) || strlen($prompt)==0){
    exit('{"error":"请以Get方式传入prompt参数"}');
}

$data = '{"model":"text-davinci-003","prompt":"'.$prompt.'","temperature":0.1,"max_tokens":2048,"top_p":1,"frequency_penalty":0.1,"presence_penalty":0.6}';
$url='https://api.openai.com/v1/completions';

if($type=='image/create'){
  $url="https://api.openai.com/v1/images/generations";
  $data = '{"prompt":"'.$prompt.'","n":3}';
}

$response=file_get_contents($url, false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/json\r\nAuthorization:Bearer ".$authorization,'content'=>$data),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
exit($response);
?>
</body>
</html>
