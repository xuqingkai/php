<?php
//https://help.aliyun.com/document_detail/159973.html
include_once('./config.php');
function aliyun_request1($url, $param){
    $data['AccessKeyId']=config('AccessKeyId');
    $data['Timestamp']=str_replace(' ', 'T',date('Y-m-d H:i:s',time()-8*60*60)) . 'Z';
    $data['SignatureMethod']='HMAC-SHA1';
    $data['SignatureVersion']='1.0';
    $data['SignatureNonce']=md5(date('YmdHis').''.rand(10000,99999));
    $data=array_merge($data, $param);

    $accessKeySecret=$data['AccessKeySecret']??config('AccessKeySecret')??'';
    unset($data['AccessKeySecret']);
    //exit(json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));

    ksort($data);
    $str='';
    foreach($data as $key=>$val){ $str.="&".$key."=".str_replace("%7E", "~", str_replace("*", "%2A", str_replace("+", "%20", urlencode($val)))); }
    $str="GET&%2F&".urlencode(substr($str, 1));
    $sign=hash_hmac('sha1', $str, $accessKeySecret.'&', true);
    $sign=base64_encode($sign);

    $requestString=http_build_query($data)."&Signature=".urlencode($sign);
    if(strpos($url,'.aliyuncs.com')===false){ $url.='.aliyuncs.com'; }
    if(strpos($url,'://')===false){ $url='https://'.$url; }
    //if($data['Action']=='ListDeploymentJob'){exit('<a href="'.$url.'?'.$requestString.'">'.$url.'?'.$requestString.'</a>');}
    //list($error,$response,$header)=http_file($url.'?'.$requestString);
    //if($error){ exit('<h1>'.$error.'</h1>'.$header.'<a href="'.$url.'?'.$requestString.'">'.$url.'?'.$requestString.'</a>'); }
    list($error,$response,$header)=http_file($url.'?'.$requestString);
    return $response;
}
function aliyun_request3($url, $data){
    $host=strpos($url,'://')===false?explode('/',$url)[0]:explode('/',$url)[2];//exit($host);
    $query=strpos($url,'?')===false?'':substr($url, strpos($url,'?')+1);
    $query=array();
    $body=json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);//exit($body);
    $httpRequestMethod='POST';
    $canonicalURI='/';
    $canonicalQueryString=str_replace("%7E", "~", str_replace("*", "%2A", str_replace("+", "%20",http_build_query($query))));
    $canonicalHeaders='';
    $signedHeaders='';
    $hashedRequestPayload=hash('SHA256',$body);

    $headers['Content-Type']='application/json; charset=utf-8';
    $headers['Host']=$host;
    $headers['X-Acs-Action']=$data['Action'];
    $headers['X-Acs-Content-Sha256']=$hashedRequestPayload;
    $headers['X-Acs-Date']=str_replace(' ', 'T',date('Y-m-d H:i:s',time()-8*60*60)) . 'Z';
    $headers['X-Acs-Signature-Nonce']=md5('nonce.'.date('YmdHis').'.'.rand(10000,99999));
    //$headers['X-Acs-Security-Token']='';
    $headers['X-Acs-Version']=$data['Version'];


    ksort($headers);
    foreach($headers as $key=>$val){
        $canonicalHeaders.=strtolower($key).':'.trim($val)."\n";
        if($signedHeaders){$signedHeaders.=';';}
        $signedHeaders.=strtolower($key);
    }

    $canonicalRequest=$httpRequestMethod."\n".$canonicalURI."\n".$canonicalQueryString."\n".$canonicalHeaders."\n".$signedHeaders."\n".$hashedRequestPayload;
    //file_put_contents('CanonicalRequest.txt',json_encode($canonicalRequest,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
    //exit($canonicalRequest);
    $hashedCanonicalRequest=hash('SHA256',$canonicalRequest);
    $headers['HashedCanonicalRequest']=$hashedCanonicalRequest;
    
    $accessKeyId=config('AccessKeyId');
    $accessKeySecret=config('AccessKeySecret');
    $signatureAlgorithm='ACS3-HMAC-SHA256';
    $signatureString=$signatureAlgorithm."\n".$hashedCanonicalRequest;
    $signature=hash_hmac('SHA256', $signatureString, $accessKeySecret);

    $authorization='ACS3-HMAC-SHA256 Credential='.$accessKeyId.',SignedHeaders='.$signedHeaders.',Signature='.$signature;
    $headers['Authorization']=$authorization;
    //foreach($headers as $key=>$val){ echo($key.':'.trim($val).'<br />'); } exit($body);
    list($error,$response_body,$response_headers)=http_file('https://'.$host.'/', $body, $headers);
    //exit(json_encode([$error,$response_body,$response_headers], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
    return [$error,$response_body,$response_headers];
}

function http_file($url, $body=false, $headers=array(), $request=array()){
    $request_headers=array();
    if(!$headers){
        $request_headers=array(
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
        //$response_body=error_get_last();
    }catch(Exception $ex){
        $error=$ex->getMessage();
       
    }
    return [$error, $response_body, $response_header];
}
function http_curl($url,$body=false,$headers=array(),$request=array()){
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
    if($body===false){
        curl_setopt($curl, CURLOPT_POST, false);
    }else{
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS , $body);
    }
    //curl_setopt($curl, CURLOPT_ENCODING,'gzip');
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
