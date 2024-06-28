<?php
/*
{
    "order_id":"202405311147507167",
    "mer_id":"",
    "ret_code":"0000",
    "status":"success"
}
*/
include_once('./ldpay.config.php');
$ldpay['data']=$_POST;
if(!isset($ldpay['data']['mer_id'] )){exit('mer_id_null');}
if($ldpay['data']['mer_id'] != $ldpay['mer_id']){ exit('mer_id_error'); }
if($ldpay['data']['ret_code']!='0000' || $ldpay['data']['status']!='success'){ exit('fail'); }
exit('success');

?>
