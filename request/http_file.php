<?php
//header('Content-Type: application/json; charset=utf-8');
//error_reporting(0);//都不显示
//error_reporting(E_ALL);//都显示
//date_default_timezone_set('PRC');
function http_file($url, $body=false, $headers=array()){
    $request_headers=array();
    if(!$headers){
        $headers=array(
            'Content-Type:application/x-www-form-urlencode; charset=utf-8',
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36 Edg/135.0.0.0',
            'Referer:'.substr($url,0,strpos($url,'/',10))
        );
    }else{
        foreach($headers as $key=>$val){
            $request_headers[]=is_numeric($key)?$val:$key.':'.$val;
        }
    }
    $request_headers=array_merge(array('Author:xuqingkai'),$request_headers);

    $error=false;
    $response_header=array();
    $response_body='';
    try{
        $response_body=file_get_contents($url, false, stream_context_create(array(
            'http'=>array(
                'ignore_errors'=>true,//即使有HTTP错误也忽略，强行获取内容
                'method'=>$body===false?'GET':'POST',
                'header'=>implode("\r\n",$request_headers),
                'content'=>$body===false?'':$body
            ),
            'ssl'=>array('verify_peer'=>false,'verify_peer_name'=>false)
        )));
        $response_header=$http_response_header;
    }catch(Exception $ex){
        $error=$ex->getMessage();
    }
    return [$error, $response_body, $response_header];
}
//list($error, $header, $response)=http_curl();
