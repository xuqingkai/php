<?php
//https://doc.adapay.tech/document/api/#/payment?id=%e6%9f%a5%e8%af%a2%e6%94%af%e4%bb%98%e5%af%b9%e8%b1%a1%e5%88%97%e8%a1%a8
include_once('adapay.config.php');
$adapay['api_path']='/v1/payments/list';
$adapay['api_method']='GET';

$adapay['data']=[];
$adapay['data']['app_id']=$adapay['api_id'];

//$adapay['data']['created_gte']=strtotime('2022-12-15').'000';
//$adapay['data']['created_lte']=strtotime('2022-12-20').'000';
//$adapay['data']['page_index']=1;

$adapay['response_data']=adapay_request();
$adapay['response']=json_decode($adapay['response_data'], true);
$adapay['response']['data']=json_decode($adapay['response']['data'], true);
if($adapay['response']['data']['status']=='succeeded'){
  exit('<a href="payments.confirm.php?member_id='.$adapay['data']['member_id'].'">'.$adapay['data']['member_id'].'</a>');
}else{
  exit(json_encode($adapay));
}
