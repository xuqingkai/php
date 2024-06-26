<?php
//https://api.aliyun.com/document/Dysmsapi/2017-05-25/SendSms
include_once('./config.php');
include_once('./function.php');
function aliyun_sms($PhoneNumbers, $TemplateCode, $TemplateParam, $SignName){
  $req['Action']='SendSms';
  $req['Format']='JSON';
  $req['Version']='2017-05-25';
  $req['RegionId']='cn-hangzhou';
  if(is_array($PhoneNumbers)){
    $req['PhoneNumbers']=$PhoneNumbers;
    if(count($req['PhoneNumbers'])!=count($SignName)){
      return ['手机号码个数与签名个数不一致'];
    }
    $req['SignName']=$SignName;
    $req['TemplateCode']=$TemplateCode;
    if(count($req['PhoneNumbers'])!=count($TemplateParam)){
      return ['手机号码个数与签名模板个数不一致'];
    }
    $req['TemplateParam']=$TemplateParam;
    $res=aliyun_request($req);
  }else{
    $req['PhoneNumbers']=$PhoneNumbers;
    $req['SignName']=$SignName;
    $req['TemplateCode']=$TemplateCode;
    $req['TemplateParam']=json_encode($TemplateParam);
  }

  return ;
}
