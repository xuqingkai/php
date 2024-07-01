<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>$_SERVER</title>
</head>
<body>
<h1>$_SERVER</h1>
<h1><a href="./?id=1">./?id=1</a></h1>
<h1><a href="./index.php?id=1">./index.php?id=1</a></h1>
<h1><a href="./index.php/news/detail?id=1">./index.php/news/detail?id=1</a></h1>
<?php 
$text='';
foreach($_SERVER as $key=>$val){
    $value = is_array($val) ||  is_object($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val;
    $text .= $key .":". $value ."\r\n";
	echo("<p>". $key .":". $value ."</p>");
	
}
@file_put_contents("./header.txt", $text);
?>
</body>
</html>
