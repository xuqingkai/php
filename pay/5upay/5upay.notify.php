<?php
include_once('./5upay.config.php');

$_5upay['notify_string'] = file_get_contents('php://input', 'r');
$_5upay['notify_encryptKey'] = $_SERVER['HTTP_ENCRYPTKEY'];
$_5upay['notify_merchantId'] = $_SERVER['HTTP_MERCHANTID'];
$_5upay['notify_requestId'] = $_SERVER['HTTP_REQUESTID'];

if(!$_5upay['notify_string']){
    $_5upay['notify_string']='{"data":"D+cWAr66zH+O3WUv2f11yP8W8P7sL0qQRVezifD4FHby0CCjPaXwsIlDG1FWwlhphIw44yxdvRkDbxYzPgr+VBxksHgYXfAxuKmoFZwC5hpMmlrTMKTJRrsLjxsJ6nn0uLCZkWqD26okO1hioaxBy1Q/FWKY1x4PgDc8fKRB4DWlO8bfrsmwol5rt45VicbAP3CcwKSF0ZxP2c8FIZRMpwEcmm+QvYFYLFhxOlPv4AXPjtJj9C4ap/9UZ1dSkGgD8Z9GNqDfnkp05vEc6M1EljshilPKto3kb7ht7Rec8C8WqabfXScJdmrBZkdvdwIP27yvpLssE4ULKA7Wc9DoGv4hKIe1cg3f2KB3dvbc8eWGNNqc7YbN3NvtnGS5iYvyDyt3zoQhPSz9Ffz9HCmPydnoZGEFYyhUL7MqIRq8nRhOY7VuHccQjZhex5L+ucD6JfM56V2dfkIT0QXnBTpc93biTf2ZzWgNbWhTmt5+SJot2TEv+srVpq1K7GqqcCinC2dHhKwHK8x+umGwBvMU/HNq1hdUeIhcvMZGXfyhRc0YnFy5GaQmunJ0uw6XMHoJJTQnUYMKMgAB2aOmIrPJDYiOVSOlwYPpKfaFM5sADOMxYyJNsPorSTe2E+n+jMMdBU6MByN2BubxW3cqd/8HLeGyIiLpqGq1tD2+cgMZ2T8gj7nytin4zVi7N/UljABL44Zhm4qDdVxcjZFjWesp97EGVUC/mrs7LVkQ/8PTHLL7JqAES/Lv+yKpDox9YURreCg1K5RF5YPwWLVTebVrF6vimQtd/6fbtiG24tkJPp9si12OMgxowCmAibfTVTcv6Z98ERXgGLOQ3wQ21J5G0eLCivtj5WiKRpo5r2zyhjw/Ly1Sa5Fz6qjSLd7eB/diDr6/xKfxG7lF2dIcVMo1Z02IvUSUUk8Qnd9axTwTK0XRSmYMJPSgL4D8xpDH3x7Vl+ii7N6nqfvEuOPVr2GVTLNMtUSPSKQNV1Iaz2bmD/21NhgSuyJI3Hp2JtjMYdk5W2p3ourhbqK9xS1IOjFjv9C84ga48x6XftHTGKnHoONNQtJMtmp8B23eeIRuJxUQg8VoEVlZPAYvCydtJby7TA=="}';
    $_5upay['notify_encryptKey']='=ECsWQtm5fv2rCAkOVzf3M+metRDm0NSj8X9uS8z/0h4kUUSU2Zv/+OTgRP82lxyc6DI2XY17d7FJKPnLq75DrCsqGlmTcGhGRldIZbxDfXwao6t/0HIL40ZrrYfiQltRa3u9kJJNtMfxL/JBDDwH8Q9LfeE/jIjm7ZyEi0X+UI6WD+oDCcmels0kK/9QAjSxzUz1i8Y1YalDcOTZOcsMSz2QO1kDU/61JvJYhA4ikGHUFjF4i8VE0j6hpWTqUFU/hqsvFHmXC+tR20ON722kwnk7aXscn30iE0sMeozqRXasMhlurZC+jdpfdMGN8k/NwzMcd6qD1sHgOk/lqW7A/g==';
}

openssl_pkcs12_read(file_get_contents($_5upay['mch_pfx_file_path']), $_5upay['private_rsa'], $_5upay['mch_pfx_file_password']);

openssl_private_decrypt(base64_decode($_5upay['notify_encryptKey']), $_5upay['aes_key'], openssl_pkey_get_private($_5upay['private_rsa']['pkey']));


$_5upay['notify_json'] = json_decode($_5upay['notify_string'], true);
$_5upay['notify_decrypted'] = openssl_decrypt($_5upay['notify_json']['data'], "AES-128-ECB", $_5upay['aes_key']);
$_5upay['notify_decrypted'] = preg_replace('/[\x00-\x1F]/','', $_5upay['notify_decrypted']);
$_5upay['notify'] = json_decode($_5upay['notify_decrypted'],true);
$_5upay['notify_hmac']=$_5upay['notify']['hmac'];
unset($_5upay['notify']['hmac']);

$_5upay['sign_string']='';
ksort($_5upay['notify']);
foreach ($_5upay['notify'] as $key=>$val){
    if(!is_array($val)){
        if(strlen($val)){$_5upay['sign_string'].=$val.'#';}
    }else{
        ksort($val);
        foreach ($val as $key1=>$val1){
            if(!is_array($val1)){
                if(strlen($val1)){$_5upay['sign_string'].=$val1.'#';}
            }else{
                ksort($val1);
                foreach ($val1 as $key2=>$val2){
                    if(!is_array($val2)){
                        if(strlen($val2)){$_5upay['sign_string'].=$val2.'#';}
                    }
                }
            }
        }
    }
}

$_5upay['public_key'] = file_get_contents($_5upay['api_cer_file_path']);
if(strpos($_5upay['public_key'],'-----') === false){
    $_5upay['public_key'] = "-----BEGIN CERTIFICATE-----\n".chunk_split(base64_encode(file_get_contents($_5upay['api_cer_file_path'])),64,"\n")."-----END CERTIFICATE-----\n";
}
$_5upay['verify'] = openssl_verify(sha1($_5upay['sign_string'], true), base64_decode($_5upay['notify_hmac']), openssl_pkey_get_public($_5upay['public_key']), OPENSSL_ALGO_MD5);
if(!$_5upay['verify']){
    exit('verify_fail');
}elseif($_5upay['notify']['status']!='SUCCESS'){
    exit('status='.$_5upay['notify']['status']);
}else{
    $_5upay['notify_forward']=['amount'=>$_5upay['notify']['orderAmount'],'orderNo'=>$_5upay['notify']['requestId'],'type'=>'0','serialNumber'=>$_5upay['notify']['serialNumber']];
    $_5upay['response_string']=file_get_contents($_5upay['notify_forward_url'], false, stream_context_create(array(
    	'http' => array(
    		'method' => 'POST',
    		'header'  => "Content-Type:application/json",
    		'content' => json_encode($_5upay['notify_forward'], JSON_UNESCAPED_UNICODE)
    	),
    	'ssl'=>array(
    		'verify_peer'=>false,
    		'verify_peer_name'=>false
    	)
    )));
    $_5upay['notify']['serialNumber'];
    $_5upay['notify']['requestId'];
    $_5upay['notify']['orderAmount']/100;
    
    
}

header('Content-Type:application/json');
exit(json_encode($_5upay, JSON_UNESCAPED_UNICODE));
