<?php
include_once('./hkrt.config.php');
header('Content-type:application/json');

$hkrt['host']='http://saas.hkrt.cn:8080';
$hkrt['path']='/api/v1/merchant-terminal/new-bind';

$hkrt['request']=[];
$hkrt['request']['agent_no']=$hkrt['agent_no'];
$hkrt['request']['merch_no']=$hkrt['merch_no'];
$hkrt['request']['accessid']=$hkrt['accessid'];

$hkrt['request']['agent_apply_no']=date('YmdHis').rand(10000,99999);
$hkrt['request']['terminal_address']='上海市-上海市-浦东新区-五星路';
$hkrt['request']['sn']=md5($hkrt['request']['terminal_address']);
$hkrt['request']['code']='11';


$hkrt['sign_string']='';
ksort($hkrt['request']);
foreach($hkrt['request'] as $key=>$val){ $hkrt['sign_string'].='&'.$key.'='.$val; }
$hkrt['sign_string']=substr($hkrt['sign_string'],1).$hkrt['accesskey'];
$hkrt['request']['sign']=strtoupper(md5($hkrt['sign_string']));
$hkrt['request_string']=json_encode($hkrt['request'],JSON_UNESCAPED_UNICODE);
$hkrt['response_string']=file_get_contents($hkrt['host'].$hkrt['path'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/json;charset=UTF-8",'content'=>$hkrt['request_string']),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
$hkrt['response']=json_decode($hkrt['response_string'], true);

exit(json_encode($hkrt,JSON_UNESCAPED_UNICODE));

?>
