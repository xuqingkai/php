<?php
//https://help.aliyun.com/document_detail/159973.html
include_once('./config.php');
function aliyun_request($url, $req){
  $req['AccessKeyId']=config('AccessKeyId');
  $req['Timestamp']=str_replace(' ', 'T',date('Y-m-d H:i:s',time()-8*60*60)) . 'Z';
  $req['SignatureMethod']='HMAC-SHA1';
	$req['SignatureVersion']='1.0';
	$req['SignatureNonce']=md5(date('YmdHis').''.rand(10000,99999));
  ksort($req);
  $data = '';
  foreach ($req as $key => $val){
    $data .= "&" . $key . "=" . str_replace("%7E", "~", str_replace("*", "%2A", str_replace("+", "%20", urlencode($val))));
  }
  $data = "GET&%2F&" . urlencode(substr($data, 1));
  $sign = hash_hmac('sha1', $data, config('AccessKeySecret').'&', true);
  $sign = base64_encode($sign);

  $query = '';
  foreach ($req as $key => $val){ $query .= "&" . $key . "=" . urlencode($val);}
  $query = substr($query,1);
  $query .= "&Signature=".urlencode($sign);
  $url = $url.'?'.$query;
  return file_get_contents($url);
}
