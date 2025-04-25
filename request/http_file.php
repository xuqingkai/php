<?php
//header('Content-Type: application/json; charset=utf-8');
//error_reporting(0);//都不显示
//error_reporting(E_ALL);//都显示
//date_default_timezone_set('PRC');
function http_file($url, $body='', $headers=array()){
    if(!$headers){
        $headers=array(
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0',
            'Referer:'.substr($url,0,strpos($url,'/',10))
        );
    }
    $headers=array_merge($headers, array('Author:xuqingkai'));

    $error=false;
    $response_header=array();
    try{
        $response_body=file_get_contents($url, false, stream_context_create(array(
            'http'=>array(
                'method'=>strlen($body)>0?'POST':'GET',
                'header'=>implode("\r\n",$headers),
                'content'=>$body
            ),
            'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false)
        )));
        $response_header=$http_response_header;
    }catch(Exception $ex){
        $error=$ex->getMessage();
    }
    return [$error, $response_header, $response_body];
}
//list($error, $header, $response)=http_curl();
