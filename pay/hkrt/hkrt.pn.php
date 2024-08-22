<?php
include_once('./hkrt.config.php');
header('Content-type:application/json');

$hkrt['host']='http://saas.hkrt.cn:8080';
$hkrt['path']='/api/v1/merchant-terminal/new-bind';

$hkrt['request']=[];
$hkrt['request']['agent_no']=$hkrt['agent_no'];
foreach($hkrt['merch_no_pn'] as $merch_no=>$pn){
    if(strlen($pn)==0){
        $hkrt['request']['merch_no']=$merch_no.'';
        break;
    }
}
$hkrt['request']['accessid']=$hkrt['accessid'];

$hkrt['request']['agent_apply_no']=date('YmdHis').rand(10000,99999);
$hkrt['request']['terminal_address']='上海市-上海市-浦东新区-五星路';
$hkrt['request']['sn']=md5($hkrt['request']['terminal_address']);
$hkrt['request']['code']='11';

$hkrt['sign_string']='';
ksort($hkrt['request']);
foreach($hkrt['request'] as $key=>$val){ if(strtolower($key)!='sign' && strlen($val)>0){$hkrt['sign_string'].='&'.$key.'='.$val;} }
$hkrt['sign_string']=substr($hkrt['sign_string'],1).$hkrt['accesskey'];
$hkrt['request']['sign']=strtoupper(md5($hkrt['sign_string']));
$hkrt['request_string']=json_encode($hkrt['request'],JSON_UNESCAPED_UNICODE);
$hkrt['response_string']=file_get_contents($hkrt['host'].$hkrt['path'], false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/json;charset=UTF-8",'content'=>$hkrt['request_string']),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
$hkrt['response']=json_decode($hkrt['response_string'], true);

if(isset($hkrt['response']['pn'])){
    exit('<h1>修改config文件中merch_no_pn参数，商户号'.$hkrt['request']['merch_no'].'对应的值为：'.$hkrt['response']['pn'].'</h1>');
}
exit(json_encode($hkrt,JSON_UNESCAPED_UNICODE));

?>
