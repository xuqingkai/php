<?php
//header('Content-Type: application/json; charset=utf-8');
//error_reporting(0);//都不显示
//error_reporting(E_ALL);//都显示
//date_default_timezone_set('PRC');
function http_file($url, $data, $header){
  if(is_array($header)){$header=implode("\r\n", $header);}
  if(strlen($header)<=0){$header="Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser";}
  $response=file_get_contents($url, false, stream_context_create(array(
    'http'=>array(
        'method'=>(strlen($data)>0?"POST":"GET"),
        'header'=>$header,
        'content'=>$data
    ),
    'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false)
  )));
  return [$response, $http_response_header];
}
function http_cookie($http_response_header){
  $cookies='';
  foreach($http_response_header as $response_header){
    if(strtolower(substr($response_header,0,11))=='set-cookie:'){
      $cookie=substr($response_header,12);
      $cookie=substr($cookie,0,strpos($cookie,'; '));
      $cookies.='; '.$cookie;
    }
  }
  if(strlen($cookies)>0){$cookies=substr($cookies,2);}
}

