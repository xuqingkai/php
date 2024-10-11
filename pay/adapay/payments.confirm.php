<?php
//https://doc.adapay.tech/document/api/#/payment?id=%e5%88%9b%e5%bb%ba%e6%94%af%e4%bb%98%e7%a1%ae%e8%ae%a4%e5%af%b9%e8%b1%a1
include_once('adapay.config.php');
$adapay['api_path']='/v1/payments/confirm';
$adapay['api_method']='POST';

$adapay['data']=[];
$adapay['data']['app_id']=$adapay['api_id'];
$adapay['data']['payment_id']=$_GET['payment_id'];
$adapay['data']['order_no']=$_GET['order_no'];
$adapay['data']['confirm_amt']=$_GET['confirm_amt'];
$adapay['data']['fee_mode']='O';//O：商户手续费账户扣取手续费I：交易金额中扣取手续费
$adapay['data']['div_members']=[];
$adapay['data']['div_members'][]=['member_id'=>'test','amount'=>'100.00','fee_flag'=>'N'];

$adapay['response_data']=adapay_request();
$adapay['response']=json_decode($adapay['response_data'], true);
$adapay['response']['data']=json_decode($adapay['response']['data'], true);
exit(json_encode($adapay));
