<?php
error_reporting(E_ALL);//都显示
include_once('adapay.config.php');
$adapay['api_path']='/v1/cashs';
$adapay['api_method']='POST';

$adapay['data']=[];
$adapay['data']['app_id']=$adapay['api_id'];
$adapay['data']['order_no']='tixian'.date('YmdHis');
$adapay['data']['cash_type']='DM';
$adapay['data']['cash_amt']='1.00';
$adapay['data']['member_id']='test';

$adapay['request_data']=json_encode($adapay['data']);

$adapay['response_data']=adapay_request();
$adapay['response']=json_decode($adapay['response_data'], true);
$adapay['response']['data']=json_decode($adapay['response']['data'], true);
file_put_contents('cashs_'.$adapay['data']['member_id'].'.txt', json_encode($adapay));
exit($adapay['response_data']);
