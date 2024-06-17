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
foreach($_SERVER as $key=>$val){
	echo("<p>".$key.":".$val."</p>");
}
?>
</body>
</html>
