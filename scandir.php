<?php
error_reporting(0);//都不显示
header("Content-type:text/html;charset=utf-8");
$root = realpath('./');
$path = $root.'';
$files = getItem($path);
$file = $files[rand(0,count($files)-1)];
$file = substr($file,strlen($root));

exit($file);header('location:'.$file);

function getItem($path){
    $files = [];
    foreach(scandir($path) as $item) {
        $itemPath = $path .DIRECTORY_SEPARATOR . $item;
        if(substr($item,0,1)!="."){
             if(is_dir($itemPath)) {
                $files = array_merge($files, getItem($itemPath));
            }else if(is_file($itemPath)){
                $files[] = $itemPath;
            }
        }
    }
    return $files;
}
