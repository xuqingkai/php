<?php
header('Content-Type:application/json; charset=utf-8');
header('User-Agent:AppBrowser 1.0.0');
header('Test:'.$_SERVER['HTTP_TEST'].'@'.date('Y-m-d H:i:s'));
header('Timestamp:'.date('Y-m-d H:i:s'));

$content=file_get_contents('php://input');
exit(json_encode(['url'=>$_SERVER['REQUEST_URI'],'size'=>strlen($content)]));
