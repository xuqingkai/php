<?php
header("Access-Control-Allow-Origin:*");
$res=file_get_contents('https://opendata.baidu.com/api.php?query='.$_SERVER['REMOTE_ADDR'].'&co=&resource_id=6006&oe=utf8');
exit($res);
?>