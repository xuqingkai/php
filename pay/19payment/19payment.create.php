<?php
include_once('./19payment.config.php');


$_19payment['data']=[];
$_19payment['data']['version']='2.1';
$_19payment['data']['parter']=$_19payment['config']['parter'];
//$_19payment['data']['appid']='应用ID，可为空';
//$_19payment['data']['username']='充值账号，可为空，如给应用充值，充值账号则不能为空';
$_19payment['data']['type']='ALIPAYWAP';//ALIPAY	支付宝,ALIPAYWAP	支付宝WAP,WEIXIN	微信支付,WEIXINWAP	微信WAP
$_19payment['data']['amount']=number_format("1.00", 2, '.', '');;
$_19payment['data']['orderno']=date('YmdHis');
$_19payment['data']['recefiveurl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["DOCUMENT_URI"].'?'.$_SERVER["QUERY_STRING"];
$_19payment['data']['notifyurl']='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["DOCUMENT_URI"];
$_19payment['data']['notifyurl']='http://test.d6m.cn/callback/';

$_19payment['data']['remark']='remark';
$_19payment['data']['orderencodetype']='MD5';


$_19payment['temp']=[];
$_19payment['temp']['sign_str']='';
foreach ($_19payment['data'] as $key=>$val){ $_19payment['temp']['sign_str'].='&'.$key.'='.$val; }
$_19payment['temp']['sign_str']=substr($_19payment['temp']['sign_str'],1).'&key='.$_19payment['config']['key'];
$_19payment['temp']['sign_string']=htmlspecialchars($_19payment['temp']['sign_str']);
$_19payment['data']['sign']=md5($_19payment['temp']['sign_str']);

$_19payment['request']=[];
$_19payment['request']['str']=http_build_query($_19payment['data']);
$_19payment['request']['string']=htmlspecialchars($_19payment['request']['str']);
$_19payment['request']['url']=$_19payment['config']['url'].'?'.$_19payment['request']['str'];
$_19payment['response']=[];
exit('<a href="'.$_19payment['request']['url'].'">'.$_19payment['request']['url'].'</a>');
//$_19payment['response']['str']=file_get_contents($_19payment['config']['url'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/x-www-form-urlencoded",'content'=>$_19payment['request']['str']),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
//$_19payment['response']['str']=file_get_contents($_19payment['config']['url'].'?'.$_19payment['request']['str']);
//$_19payment['response']['str']=curl($_19payment['config']['url'], $_19payment['request']['str']);
//$_19payment['response']['json']=json_decode($_19payment['response']['str'], true);
exit(json_encode($_19payment));
if($bingzhe['json']['code']==200){
	exit('<a href="'.$_19payment['url'].'">wap模式必须在手机上，否则报错</a>');
}else{
	exit($bingzhe['json']['msg']);
}

function curl($url, $data){
    try{
        $curl = curl_init($url);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($curl, CURLOPT_HEADER, false);//是否返回headers信息
    	curl_setopt($curl, CURLOPT_HTTPHEADER, array('header'=>"Content-type:application/x-www-form-urlencoded", 'Content-Length'=>strlen($data)));
    	curl_setopt($curl, CURLOPT_POST, true);
    	//curl_setopt($curl, CURLOPT_ENCODING,'gzip');
    	curl_setopt($curl, CURLOPT_POSTFIELDS , $data);
    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);//忽略重定向
    	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    	$response = curl_exec($curl);
    	if($response === false){ $response = 'curl:error:'.curl_error($curl); }
    	curl_close($curl);
    }catch(\Exception $e){
		$response = 'curl:exception:'.$e->getMessage();
	}
	return $response;
}
?>
