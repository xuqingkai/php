<?php
error_reporting(E_ALL);
header('Content-Type: text/html; charset=utf-8');
$path='/home/oss/xuqingkai-com/4gsms/index.html';
$method=$_SERVER['REQUEST_METHOD'];
if($method=='POST'){
    $post=file_get_contents('php://input');
    $json=json_decode($post,true);
    $post=str_replace("<br />#",'<br />日志行为: #',str_replace("<br /><br />",'<br />',str_replace("\n",'<br />',$json['contents'])));
    $post=str_replace("<br />运营商:",'<br />运营商名:',$post);
    $post=str_replace("<br />信号:",'<br />信号强度:',$post);
    $post=str_replace("<br />频段:",'<br />网络频段:',$post);
    $post=str_replace("<br />温度:",'<br />卡板温度:',$post);
    $post=str_replace("<br />电压:",'<br />卡板电压:',$post);
    $text=file_get_contents($path);
    if(count(explode('<hr />',$text))>=10){ $text=substr($text,0,strrpos($text,'<hr />')); }
    file_put_contents($path, '<hr />日志时间: '.date('Y-m-d H:i:s').'<br />'.$post.$text);
    exit();
}else{
    if(isset($_SERVER['QUERY_STRING']) && strpos(strtolower($_SERVER['QUERY_STRING']),'del') !== false){
        file_put_contents($path, '');
        header('location:'.$_SERVER['SCRIPT_NAME']);
        exit();
    }else{
        $body='<a href="?del">删除</a>'.file_get_contents($path);
    }
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>4gsms</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
<?php echo($body); ?>
</body>
</html>