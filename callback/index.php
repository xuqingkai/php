<?php
$file_path='./callback.txt';
$contents = '';
$queryString = $_SERVER['QUERY_STRING'];
if($queryString=='view'){
    if(is_file($file_path)){ $contents = file_get_contents($file_path); }
}else if($queryString=='clear'){
    if(is_file($file_path)){ unlink($file_path); }
    exit('<script type="text/javascript">window.location.href="?view";</script>');
}else{
    $text = "\r\n\r\n";
    $text .= date('Y-m-d H:i:s')."\r\n";
    $text .= "-----------------------------------------------------------------------\r\n";
    $text .= $_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']."\r\n";
    $text .= "-----------------------------------------------------------------------\r\n";
    foreach($_SERVER as $key=>$val){ $text .=  "【".$key."】=".$val."\r\n"; }
    //foreach($_SERVER as $key=>$val){ if(substr($key, 0, 5)=='HTTP_'){ $text .=  "【".$key."】=".$val."\r\n"; } }
    $text .= "-----------------------------------------------------------------------\r\n";
    $text .= file_get_contents('php://input')."\r\n";
    $text .= "=======================================================================\r\n";
    $file = fopen($file_path,"a");
    fwrite($file, $text);
    fclose($file);
    $path_info = $_SERVER['PATH_INFO'];
    $response = $_GET['response'];if(strlen($response)){ exit($response); }
    $response = $_SERVER['HTTP_RESPONSE'];if(strlen($response)){exit($response);}
    $response = $_SERVER['HTTP_X_RESPONSE'];if(strlen($response)){exit($response);}
    $response = $_SERVER['PATH_INFO'];if(strlen($response)){exit(substr($response,1));}
    exit("success");
}?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Callback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style type="text/css">
      body{font-size:14px;}
      textarea{width:99%;height:500px;font-size:16px;}
    </style>
</head>
<body>
    <form>
        <a style="float:right" href="?clear">清空</a><a href="?view">首页</a>
        <textarea><?php echo($contents); ?></textarea>
    </form>
</body>
</html>
		
