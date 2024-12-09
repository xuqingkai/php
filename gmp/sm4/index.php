<?php
include './Sm4Helper.php';
function index(){
    $key = "asw34a5ses5w81wf";

    $sm4 = new Sm4Helper();
    $data = '123456';
    
    $enc = $sm4->encrypt($key, $data);
    return $enc;
    //echo "encrypt: $enc\n";
    
    //$decdata = $sm4->decrypt($key, $enc);
    //echo "decrypt: $decdata\n";
}

