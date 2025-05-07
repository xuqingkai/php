<?php
//https://api.aliyun.com/document/Dysmsapi/2017-05-25/SendSms
include_once('./config.php');
include_once('./function.php');
function aliyun_sms($PhoneNumbers, $TemplateCode, $TemplateParam, $SignName){
    $data['Action']='SendSms';
    $data['Format']='JSON';
    $data['Version']='2017-05-25';
    $data['RegionId']='cn-hangzhou';
    if(is_array($PhoneNumbers)){
        $data['PhoneNumbers']=$PhoneNumbers;
        if(count($data['PhoneNumbers'])!=count($SignName)){
            return ['手机号码个数与签名个数不一致'];
        }
        $data['SignName']=$SignName;
        $data['TemplateCode']=$TemplateCode;
        if(count($data['PhoneNumbers'])!=count($TemplateParam)){
            return ['手机号码个数与签名模板个数不一致'];
        }
        $data['TemplateParam']=$TemplateParam;
        $res=aliyun_request($data);
    }else{
        $data['PhoneNumbers']=$PhoneNumbers;
        $data['SignName']=$SignName;
        $data['TemplateCode']=$TemplateCode;
        $data['TemplateParam']=json_encode($TemplateParam);
    }
    return aliyun_request('http://dysmsapi.aliyuncs.com', $data);
}
