<?php
$url='';
$data='';
$response=file_get_contents($url, false, stream_context_create(array(
  'http'=>array(
      'method'=>'POST',
      'header'=>"Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser",
      'content'=>$data
  ),
  'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false)
)));
foreach($http_response_header as $response_header){
  if(substr(strtolower($response_header),0,10)=='set-cookie'){
    
  }
}
