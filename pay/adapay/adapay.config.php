<?php
$adapay['api_id']='app_********-****-****-****-************';
$adapay['api_key']='api_live_********-****-****-****-***********';
$adapay['mch_rsa_private']='';
$adapay['api_rsa_public']='';
$adapay['api_host']='https://api.adapay.tech';


function adapay_request(){
    global $adapay;
    $url=$adapay['api_host'].$adapay['api_path'];
    if($adapay['api_method']=='GET'){
        $data=http_build_query($adapay['data']);
    }else{
        $data=json_encode($adapay['data']);
    }
    $rsa_private_key="-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($adapay['mch_rsa_private'], 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
    openssl_sign($url.$data, $signature, $rsa_private_key, OPENSSL_ALGO_SHA1);
    //exit(base64_encode($signature));
    $adapay['sign_string']=$url.$data;
    $adapay['signature']=base64_encode($signature);
    $adapay['authorization']=$adapay['api_key'];
    $headers=array("Content-Type: application/json", "Content-Length: ".strlen($data), "Authorization: ".$adapay['api_key'], "Signature: ".$adapay['signature']);
    //exit(implode("-----", $headers));
    if($adapay['api_method']=='GET'){
        $url.='?'.$data;
    }
  	$curl = curl_init($url);
  	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($curl, CURLOPT_HEADER, false);//是否返回headers信息
  	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  	if($adapay['api_method']=='GET'){
  	    curl_setopt($curl, CURLOPT_POST, false);
  	}else{
      	curl_setopt($curl, CURLOPT_POST, true);
      	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  	}
  	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);//忽略重定向
  	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
  	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
  	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
  	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
  	$response = curl_exec($curl);
  	if($response === false){ $response = 'curl:error:'.curl_error($curl); }
  	curl_close($curl);	
  	return $response;
    
}
