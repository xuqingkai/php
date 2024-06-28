<?php
include_once('./duolabao.config.php');

$duolabao['data']=$_GET;
$duolabao['timestamp']=$_SERVER['HTTP_TIMESTAMP'];
$duolabao['http_token']=$_SERVER['HTTP_TOKEN'];
$duolabao['token_str']='secretKey='.$duolabao['secretKey'].'&timestamp='.$duolabao['timestamp'];
$duolabao['token'] = strtoupper(sha1($duolabao['token_str']));
if($duolabao['token']!=$duolabao['http_token']){
    exit("sign_error");
}else{
    if($duolabao['data']['status']!='SUCCESS'){
        exit("status!=SUCCESS");
    }else{
        exit("success");
        exit(json_encode($duolabao['data']));
        $duolabao['data']['orderNum'];//流水号
        $duolabao['data']['requestNum'];//商户单号
        $duolabao['data']['orderAmount'];//金额
        $duolabao['data']['completeTime'];//时间
    }
}
?>
