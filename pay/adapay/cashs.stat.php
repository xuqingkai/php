<?php
//https://doc.adapay.tech/document/api/#/settlement?id=%e6%9f%a5%e8%af%a2%e5%8f%96%e7%8e%b0%e5%af%b9%e8%b1%a1
include_once('adapay.config.php');
$adapay['api_path']='/v1/cashs/stat';
$adapay['api_method']='GET';

$adapay['data']=[];
//$adapay['data']['app_id']=$adapay['api_id'];
$adapay['data']['order_no']=$_GET['order_no'];

$adapay['request_data']=json_encode($adapay['data']);

$adapay['response_data']=adapay_request();
$adapay['response']=json_decode($adapay['response_data'], true);
$adapay['response']['data']=json_decode($adapay['response']['data'], true);

if($adapay['response']['data']['status']=='succeeded'){
    exit(json_encode($adapay['response']['data']));
}else{
    exit(json_encode($adapay));
}   

