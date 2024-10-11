<?php
//https://doc.adapay.tech/document/api/#/member?id=%e5%88%9b%e5%bb%ba%e7%94%a8%e6%88%b7%e5%af%b9%e8%b1%a1
include_once('adapay.config.php');
$adapay['api_path']='/v1/members';
$adapay['api_method']='POST';

$adapay['data']=[];
$adapay['data']['app_id']=$adapay['api_id'];
$adapay['data']['member_id']='test'.date('YmdHis');

$adapay['response_data']=adapay_request();

//{"data":"{\"app_id\":\"\",\"created_time\":\"1728552251\",\"disabled\":\"N\",\"identified\":\"N\",\"member_id\":\"\",\"object\":\"member\",\"status\":\"succeeded\",\"prod_mode\":\"true\"}","signature":""}
$adapay['response']=json_decode($adapay['response_data'], true);
$adapay['response']['data']=json_decode($adapay['response']['data'], true);
file_put_contents('members_'.$adapay['data']['member_id'].'.txt', json_encode($adapay));
if($adapay['response']['data']['status']=='succeeded'){
    exit('<a href="settle_accounts.php?member_id='.$adapay['response']['data']['member_id'].'">'.$adapay['response']['data']['member_id'].'</a>');
}else{
    exit(json_encode($adapay));
}

