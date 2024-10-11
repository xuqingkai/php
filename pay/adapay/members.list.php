<?php
//https://doc.adapay.tech/document/api/#/member?id=%e5%88%9b%e5%bb%ba%e7%94%a8%e6%88%b7%e5%af%b9%e8%b1%a1
include_once('adapay.config.php');
$adapay['api_path']='/v1/members/list';
$adapay['api_method']='GET';

$adapay['data']=[];
$adapay['data']['app_id']=$adapay['api_id'];

$adapay['response_data']=adapay_request();
$adapay['response']=json_decode($adapay['response_data'], true);
$adapay['response']['data']=json_decode($adapay['response']['data'], true);

header('Content-Type: application/json; charset=utf-8');
if($adapay['response']['data']['status']=='succeeded'){
  exit(json_encode($adapay['response']));
}else{
  exit(json_encode($adapay));
}
