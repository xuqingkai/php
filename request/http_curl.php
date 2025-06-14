<?php
//header('Content-Type: application/json; charset=utf-8');
//error_reporting(0);//都不显示
//error_reporting(E_ALL);//都显示
//date_default_timezone_set('PRC');
function http_curl($url, $body=false, $headers=array()){
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

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HEADER, true);//是否返回headers信息
    curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
    //curl_setopt($curl, CURLOPT_ENCODING,'gzip');
    if($body===false){
        curl_setopt($curl, CURLOPT_POST, false);
    }else{
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS , $body);
    }
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
    return [$error, $response_body, $response_header];
}
//list($error, $header, $response)=http_curl();
