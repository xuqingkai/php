<?php
//header('Content-Type: text/html; charset=utf-8');
//error_reporting(0);//都不显示
//error_reporting(E_ALL);//都显示
//date_default_timezone_set('PRC');
//商户号
$xpay_config_data = array();
//二维码生成地址
function xpay_qrurl(){
	return 'http://mobile.qq.com/qrcode/?url=';
}
//浏览器状态
function xpay_config($key){
	global $xpay_config_data;
	return $xpay_config_data[$key];
}
function xpay_config_add($key, $value){
	global $xpay_config_data;
	$xpay_config_data[$key]=$value;
}
function xpay_useragent_contains($browers){
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	if($browers == "weixin" && strpos($user_agent, " MicroMessenger/")>0)
	{
		return true;
	}
	elseif($browers == "alipay" && strpos($user_agent, " AlipayClient/")>0)
	{
		return true;
	}
	elseif($browers == "qq" && strpos($user_agent, " QQ/")>0)
	{
		return true; 
	}
	elseif($browers == "mobile" && (strpos($user_agent, "Android")>0 || strpos($user_agent, "iPhone")>0 || strpos($user_agent, "ios")>0 || strpos($user_agent, "iPod")>0))
	{
		return true;
	}
	elseif(strpos($user_agent, $browers)>0)
	{
		return true;
	}
	return false;
}

//获取POST数据
function xpay_post_data(){
	$post_data=file_get_contents('php://input');
	if(strlen($post_data)==0){$post_data=http_build_query($_POST);}
	return $post_data;
}

//格式化URL
function xpay_root_url($url){
	if(strtolower(substr($url,0,7)) != 'http://' && strtolower(substr($url,0,8)) != 'https://'){
		$root_url = 'http'.($_SERVER["HTTPS"] == 'on' ? 's' : '').'://'.$_SERVER["HTTP_HOST"];
		if(substr($url,0,1) != '/'){
			$root_url.=$_SERVER['REQUEST_URI'];
			$root_url=substr($root_url, 0, strrpos($root_url, '/') + 1);
		}
		$url=$root_url.$url;
	}
	return $url;
}

//XML转ARRAY
function xpay_xml_array($xml){
	$array=(array)simplexml_load_string($xml);
	return $array;
}

//ARRAY转XML
function xpay_array_xml($array){
	$xml='';
	foreach($array as $key => $value){
		$xml.='<'.$key.'><![CDATA['.$value.']]></'.$key.'>';
	}
	$xml='<xml>'.$xml.'</xml>';
	return $xml;
}

//ARRAY转JSON
function xpay_array_json($array){
	$json='';
	foreach($array as $key => $value){
		$json.='"'.$key.'":"'.$value.'"';
	}
	if(strlen($json)>0){$json=substr($json,1);}
	$json='{'.$json.'}';
	return $json;
}

//JSON转ARRAY
function xpay_json_array($json){
	return json_decode($json, true);
}

//签名字符串
function xpay_array_sign($array, $keys, $sort = 0){
	$data='';
	if($sort>0){
		foreach(explode(',', $keys) as $key){
			$data.='&'.$key.'='.$array[$key];	
		}		
	}else{
		if($sort<0){ksort($array);}
		foreach($array as $key => $value){
			if(strpos(','.$keys.',',','.strtolower($key).',')===false){
				$data.='&'.$key.'='.$value;	
			}
		}
	}
	if(strlen($data)>0){$data=substr($data,1);}
	return $data;
}

//MD5私钥签名
function xpay_md5($data){
	return md5($data);
}

//RSA私钥签名
function xpay_rsa_sign($private_key, $data){
	$private_key = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($private_key, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
	//$private_rsa = openssl_get_privatekey($private_key);
	openssl_sign($data, $sign, $private_key, version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA256 : SHA256);
	$sign = base64_encode($sign);
	return $sign;
}

//RSA公钥验签
function xpay_rsa_verify($public_key, $data, $sign){
	$public_key = "-----BEGIN PUBLIC KEY-----\n" . wordwrap($public_key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
	$public_rsa = openssl_get_publickey($public_key);
	$verify = (bool)openssl_verify($data, base64_decode($sign), $public_rsa, version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA256 : SHA256);
	return $verify;
}
//请求URL数据
function xpay_array_request($array){
	$request_string='';
	foreach($array as $key => $value){ $request_string.='&'.$key.'='.urlencode($value); }
	if(strlen($request_string)>0){$request_string = substr($request_string, 1);}
	return $request_string;
}

//模拟HTTP
function xpay_http_curl($url, $data){
	if($data){
		try{
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);//是否返回headers信息
			//curl_setopt($curl, CURLOPT_HTTPHEADER, array('header'=>"Content-type:application/json", 'Content-Length'=>strlen($data)));
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
	}else{
		$response=file_get_contents($url);
	}
	return $response;	
}
//日志
function xpay_http_post($url, $data, $header = null){
	if($data){
		if($header == null){
			$header="Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser";
		}
		$response=file_get_contents($url, false, stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header'  => $header,
				'content' => $data
			),
			'ssl'=>array(
				'verify_peer'=>false,
				'verify_peer_name'=>false
			)
		)));
	}else{
		$response=file_get_contents($url);
	}
	return $response;
}
function xpay_download_file($url, $fileName, $path, $gzip){
	$img = file_get_contents("compress.zlib://".$url);
	$data = file_put_contents($path.'.jpeg',$img);
}

//日志
function xpay_debug($log, $path){
	$text = '';
	$text .= '当前时间：'.date("Y-m-d H:i:s")."\r\n";
	$text .= '来源地址：'.$_SERVER['HTTP_REFERER']."\r\n";
	$text .= '当前地址：'.$_SERVER['REQUEST_URI']."\r\n";
	$text .= 'POST参数：'.file_get_contents("php://input")."\r\n";
	$text .= '调试内容：'.$log."\r\n";
	$text .= "----------\r\n";
	if(!$path){$path = './log.txt';}
	return file_put_contents($path, $text, FILE_APPEND);
}

//mysql连接数据库
function xpay_mysql_connect($ip, $db, $id, $pw){
	mysql_connect($ip, $id, $pw);
	mysql_select_db($db);
	mysql_set_charset('utf8');
}

//执行SQL文
function xpay_mysql_execute($sql){
	return mysql_fetch_array(mysql_query($sql));
}

//mysqli连接数据库
$xpay_mysql_connection = null;
function xpay_mysqli_connect($ip, $db, $id, $pw){
	$xpay_mysql_connection = mysqli_connect($ip, $id, $pw);
	mysqli_select_db($xpay_mysql_connection, $db);
	mysqli_set_charset($xpay_mysql_connection, 'utf8');
}

//执行SQL文
function xpay_mysqli_execute($sql){
	return mysql_fetch_array(mysqli_query($xpay_mysql_connection, $sql));
}

//pdo连接数据库
$xpay_pdo_connection = null;
function xpay_pdo_connect($ip, $db, $id, $pw)
{
	$xpay_pdo_connection = new PDO("mysql:dbname=".$db.";host=".$ip."", $id, $pw);
}
//执行SQL文
function xpay_pdo_execute($sql){
	$pdoStatement=$xpay_pdo_connection->prepare($sql);
	//$pdoStatement->bindValue(':id', 1);
	$pdoStatement->execute();
	$array=$pdoStatement->fetch(PDO::FETCH_ASSOC);
	//$array=$pdoStatement->fetchAll(PDO::FETCH_ASSOC);
	return $array;
}

?>
