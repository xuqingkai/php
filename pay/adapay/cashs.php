<?php
error_reporting(E_ALL);//都显示
include_once('adapay.config.php');
$adapay['api_path']='/v1/cashs';
$adapay['api_method']='POST';

$adapay['data']=[];
$adapay['data']['app_id']=$adapay['api_id'];
$adapay['data']['order_no']='cashs'.date('YmdHis');
$adapay['data']['cash_type']='DM';
$adapay['data']['cash_amt']='1.00';
$adapay['data']['member_id']='test';

$adapay['request_data']=json_encode($adapay['data']);

$adapay['response_data']=adapay_request();
$adapay['response']=json_decode($adapay['response_data'], true);
$adapay['response']['data']=json_decode($adapay['response']['data'], true);
file_put_contents('cashs_'.$adapay['response']['data']['member_id'].'_'.$adapay['response']['data']['order_no'].'.txt', json_encode($adapay));

//{"data":{"app_id":"app_****","cash_amt":"1.00","cash_type":"DM","created_time":"1728611488","fee_amt":"0.00","id":"0021110690630794121383936","member_id":"****","object":"cash","order_no":"cashs20241011095128","real_amt":"1.00","status":"pending","prod_mode":"true"},"signature":"PE3+hgae5jekpZ0qw8q6UCFCHPSd6Kdf\/jCXq0nL657\/qwyBMhuUMVDaqKakRfSjQtYH73UGoxPslGnbf070+xWIl2HTB4ALtLl9ybvYGT7K+d4I7PCQVVoqisIgZlJYRTd+3qDfFropYJaaycZPQ1rxkn02XEnrb3pHQu16NkM="}
if($adapay['response']['data']['status']=='pending'){
    exit('<a href="cashs.stat.php?order_no='.$adapay['response']['data']['order_no'].'">'.$adapay['response']['data']['order_no'].'</a>');
}else{
    header('Content-Type: application/json; charset=utf-8');
    exit(json_encode($adapay));
} 
