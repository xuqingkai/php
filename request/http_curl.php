<?php
//header('Content-Type: application/json; charset=utf-8');
//error_reporting(0);//都不显示
//error_reporting(E_ALL);//都显示
//date_default_timezone_set('PRC');
function http_curl($url,$body='',$header=array()){
    global $xuqingkai;
    $headers=array_merge($headers, array('Author'=>'xuqingkai'));
    $request_headers=array(); foreach($headers as $key=>$val){ $request_headers[]=$key.':'.$val; }
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, true);//是否返回headers信息
    curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
    curl_setopt($curl, CURLOPT_POST, strlen($body)>0);
    //curl_setopt($curl, CURLOPT_ENCODING,'gzip');
    curl_setopt($curl, CURLOPT_POSTFIELDS , $body);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);//忽略重定向
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

    $response_body=curl_exec($curl);
    $error = ($response_body === false?curl_error($curl):false);
    $header_size=curl_getinfo($curl,CURLINFO_HEADER_SIZE);
    $response_header=explode("\r\n",substr($response_body,0,$header_size));
    $response_body=substr($response_body,$header_size);
    curl_close($curl);
    return [$error, $response_header, $response_body];
}
//list($error, $header, $response)=http_curl();
