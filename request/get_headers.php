<?php
function get_location_cookie($url){
    $header=get_headers($url, 1);
    if (strpos($header[0], '301') !== false || strpos($header[0], '302') !== false) {
        if (is_array($header['Location'])) {
            $url=$header['Location'][count($header['Location']) - 1];
        }else{
            $url=$header['Location'];
        }
    }
    $cookies=[];
    foreach ($header['Set-Cookie'] as $cookie){
        $cookies[]=substr($cookie,0,strpos($cookie,';'));
    }
    return [$url,$cookies];
}
