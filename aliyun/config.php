<?php
date_default_timezone_set('PRC');
function config($key=''){
    $config['AccessKeyId']='';
    $config['AccessKeySecret']='';
    return $key?$config[$key]:$config;
}

