<?php
callback_log();
exit('success');

function callback_log($path=''){
    $header='';
    foreach($_SERVER as $key=>$val){ $header.=$key.":".$val."\r\n"; }
    $url='http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];
    $body=file_get_contents('php://input');
    $text=$header."-----------------\r\n".$url."\r\n-----------------\r\n".$body;
    file_put_contents($path."callback.".date('YmdHis').'.log', $text);
}
