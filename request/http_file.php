<?php
//header('Content-Type: application/json; charset=utf-8');
//error_reporting(0);//都不显示
//error_reporting(E_ALL);//都显示
//date_default_timezone_set('PRC');
function http_file($url, $body='', $header=array()){
    global $xuqingkai;
    $headers=array_merge($headers, array('Author'=>'xuqingkai'));
    $request_headers=array(); foreach($headers as $key=>$val){ $request_headers[]=$key.':'.$val; }

    $error=false;
    $response_header=array();
    try{
        $response_body=file_get_contents($url, false, stream_context_create(array(
            'http'=>array(
                'method'=>strlen($body)>0?'POST':'GET',
                'header'=>implode("\r\n",$request_headers),
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
