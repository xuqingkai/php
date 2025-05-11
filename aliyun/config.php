<?php
date_default_timezone_set('PRC');
function config($key=''){
    $config=array();
    foreach(explode("\n", file_get_contents($_SERVER['DOCUMENT_ROOT'].'\.env')) as $line){
        if(strpos($line,'=')!==false){
            $key=trim(substr($line,0,strpos($line,'=')));
            $val=trim(substr($line,strpos($line,'=')+1));
            if($key){
                if(strtolower($val)=='true'){
                    $val=true;
                }elseif(strtolower($val)=='false'){
                    $val=false;
                }elseif(is_numeric($val)){
                    $val=floatval($val);
                }
                $config[$key]=$val;
            }
        }
    }
    return $key?$config[$key]:$config;
}

