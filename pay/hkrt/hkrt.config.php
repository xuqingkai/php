<?php
//http://opendocs.hkrt.cn:8181/docs/saas/saas-1b84emqc7p18m
$hkrt['host']='https://saas-front.hkrt.cn';
$hkrt['path']='/api/v2/pay/pre-pay';
$hkrt['merch_no_list']=explode(',','833466873720000');
$hkrt['merch_no']=$hkrt['merch_no_list'][rand(1,count($hkrt['merch_no_list']))-1];

$hkrt['agent_no']='';
$hkrt['accessid']='';
$hkrt['accesskey']='';

$hkrt['translatekey']='';
$hkrt['password']='';
