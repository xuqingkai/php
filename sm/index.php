<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>SM加密</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
	<?php
    $type=$_GET['type']; 
    if($type){
        require_once($type.'.php');

    }   
    ?>
</body>
</html>