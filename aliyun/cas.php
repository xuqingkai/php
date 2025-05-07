<?php
//https://api.aliyun.com/document/Dysmsapi/2017-05-25/SendSms
include_once('./config.php');
include_once('./function.php');
function aliyun_cas($param){
	$data['Format']='JSON';
	$data['Version']='2020-04-07';
	$data['RegionId']='cn-hangzhou';
	$data=array_merge($data, $param);
	list($error,$body)=aliyun_request3('https://cas.aliyuncs.com',$data);
	if(!$error){
		$json=json_decode($body,true);
		if($json && $json['Code']){ $error='接口返回：'.$json['Message']; }
	}
	/*
	{
		"RequestId": "BD5BC838-286E-5460-8077-6F9A6DB8BB40",
		"Message": "Specified api is not found, please check your url and method.",
		"Recommend": "https://api.aliyun.com/troubleshoot?q=InvalidAction.NotFound&product=cas&requestId=BD5BC838-286E-5460-8077-6F9A6DB8BB40",
		"HostId": "cas.aliyuncs.com",
		"Code": "InvalidAction.NotFound"
	}
	*/
	return [$error,$body];
}
function aliyun_cas_cert_create($Domain, $ValidateType='DNS'){
	$data['Action']='CreateCertificateRequest';
	$data['ProductCode']='digicert-free-1-free';
	$data['Username']='xuqingkai';
	$data['Phone']='13012345678';
	$data['Email']=time().'@'.time().'.com';
	$data['Domain']=$Domain;
	$data['ValidateType']='DNS';

	list($error,$response)=aliyun_cas($data);
	$json=json_decode($response,true);
	return [$error,$json];
	
	/*
	{
		"RequestId": "F2770B9C-A7BB-5DFC-9A9C-5B56B1E3ED4A",
		"OrderId": 13822139
	}
	*/
}
//exit(aliyun_cas_cert_create('xuqingkai.cn'));

function aliyun_cas_cert_list(){
	$data['Action']='ListUserCertificateOrder';
	$data['OrderType']='CERT';
	//$req['Status']='WILLEXPIRED';

	list($error,$response)=aliyun_cas($data);
	$json=json_decode($response,true);
	return [$error,$json['CertificateOrderList']];

	/*
	{
		"TotalCount": 3,
		"RequestId": "B6334590-5522-5B3F-99B6-E2C8E2464CFB",
		"CurrentPage": 1,
		"ShowSize": 50,
		"CertificateOrderList": [
			{
				"Status": "ISSUED",
				"Sha2": "0F3517F16417959E383A5311E0C9C2D541B16E91512AB08C3D4E3E08ACA5FE32",
				"SerialNo": "0cb80c8221efb4f514ddb19db8aeddf4",
				"Fingerprint": "19FA7E44EBCEE977361FF7F455721F72E2F3C439",
				"ResourceGroupId": "rg-acfmx4snyahsiuy",
				"InstanceId": "cas-ivauto-j179g7",
				"Issuer": "DigiCert Inc",
				"CertEndTime": 1754179199000,
				"CertificateId": 18382705,
				"Upload": false,
				"OrgName": "",
				"Expired": false,
				"City": "",
				"CertStartTime": 1746403200000,
				"EndDate": "2025-08-02",
				"Province": "",
				"Name": "cert-13822284",
				"StartDate": "2025-05-05",
				"Sans": "xuqingkai.cn,www.xuqingkai.cn",
				"Country": "",
				"CommonName": "xuqingkai.cn"
			}
		]
	}
	*/
}
//exit(aliyun_cas_cert_list());

function aliyun_cas_cert_resource($domain){
	$data['Action']='ListCloudResources';
	$data['CloudName']='aliyun';
	//$req['CloudProduct']='FC';
	$data['Keyword']=$domain;

	list($error,$response)=aliyun_cas($data);
	$json=json_decode($response, true);
	return [$error,$json['Data']];

	/*
	{
		"RequestId": "616289CD-4077-5E05-AFE6-40978C8BF5E1",
		"CurrentPage": 1,
		"Total": 1,
		"ShowSize": 50,
		"Data": [
			{
				"CertId": 18382705,
				"CertEndTime": "1754179199000",
				"GmtModified": "1746451202000",
				"CloudName": "aliyun",
				"CertStartTime": "1746403200000",
				"CloudProduct": "FC",
				"GmtCreate": "1711441465000",
				"EnableHttps": 1,
				"UserId": 1353813254939731,
				"Id": 7159642,
				"CertName": "cert-13822284",
				"RegionId": "cn-hangzhou",
				"Domain": "www.xuqingkai.cn"
			}
		]
	}
	*/
}

function aliyun_cas_cert_contact(){
	$data['Action']='ListContact';
	$data['CloudName']='aliyun';

	list($error,$response)=aliyun_cas($data);
	$json=json_decode($response,true);
	return [$error,$json['ContactList']];
	
	/*
	{
		"TotalCount": 1,
		"RequestId": "9CEF4F25-0B23-5983-B1B8-243C2A8891F3",
		"ContactList": [
			{
				"Email": "xuqingkai@qq.com",
				"EmailStatus": 1,
				"Webhooks": "[]",
				"MobileStatus": 1,
				"ContactId": 507742,
				"Mobile": "18753772527",
				"Name": "徐清凯"
			}
		]
	}
	*/
}
//exit(aliyun_cas_cert_contact());


function aliyun_cas_cert_deploy($CertIds, $ResourceIds, $ContactIds,$taskName=null){
	$data['Action']='CreateDeploymentJob';  
	$data['Name']=$taskName??'deploy-task-'.time();
	$data['JobType']='user';
	$data['CertIds']=$CertIds.'';
	$data['ResourceIds']=$ResourceIds.'';
	$data['ContactIds']=$ContactIds.'';
	$data['ScheduleTime']=time().'000';
	
	list($error,$response)=aliyun_cas($data);
	$json=json_decode($response,true);
	return [$error,$json];

	/*
	{
		"RequestId": "688218F4-D766-5927-A74D-F453AB8A99BC",
		"JobId": 239351,239379,239384
	}
	*/
}
//

function aliyun_cas_cert_update($JobId){
	$data['Action']='UpdateDeploymentJobStatus';  
	$data['JobId']=$JobId;
	$data['Status']='pending';//pending：进入待执行状态。	scheduling：立即调度任务。	editing：回退到编辑状态。;
	
	list($error,$response)=aliyun_cas($data);
	$json=json_decode($response,true);
	return [$error,$json];
	/*
	{
		"RequestId": "688218F4-D766-5927-A74D-F453AB8A99BC",
		"JobId": 239351
	}
	*/
}



function aliyun_cas_deploy_list(){
	$data['Action']='ListDeploymentJob0';  
	$data['JobType']='user';
	
	list($error,$response)=aliyun_cas($data);
	$json=json_decode($response,true);
	return [$error,$json['Data']];
	/*
	{
    "RequestId": "B4E9C7DC-EC33-54BE-B9D4-7CE45C4C993D",
    "CurrentPage": 1,
    "Total": 5,
    "ShowSize": 50,
    "Data": [
        {
            "Status": "success",
            "CertDomain": "www.xuqingkai.cn,xuqingkai.cn",
            "EndTime": 1746537786000,
            "ProductName": "FC",
            "InstanceId": "cas-job-user-30rh60",
            "GmtModified": 1746537785000,
            "StartTime": 1746537785000,
            "CertType": "free",
            "Del": 0,
            "Name": "deploy-task-1746537765",
            "GmtCreate": 1746537765000,
            "JobType": "user",
            "ScheduleTime": 1746537765000,
            "UserId": 1353813254939731,
            "Id": 240527,
            "Rollback": 0
        },
        {
            "Status": "success",
            "CertDomain": "xuqingkai.com,www.xuqingkai.com",
            "EndTime": 1746537785000,
            "ProductName": "OSS",
            "InstanceId": "cas-job-user-a2e6tm",
            "GmtModified": 1746537785000,
            "StartTime": 1746537782000,
            "CertType": "free",
            "Del": 0,
            "Name": "deploy-task-1746537764",
            "GmtCreate": 1746537765000,
            "JobType": "user",
            "ScheduleTime": 1746537764000,
            "UserId": 1353813254939731,
            "Id": 240526,
            "Rollback": 0
        },
        {
            "Status": "success",
            "CertDomain": "xuqingkai.com,www.xuqingkai.com",
            "EndTime": 1746537782000,
            "ProductName": "FC",
            "InstanceId": "cas-job-user-0mjwh4",
            "GmtModified": 1746537782000,
            "StartTime": 1746537782000,
            "CertType": "free",
            "Del": 0,
            "Name": "deploy-task-1746537764",
            "GmtCreate": 1746537764000,
            "JobType": "user",
            "ScheduleTime": 1746537764000,
            "UserId": 1353813254939731,
            "Id": 240525,
            "Rollback": 0
        },
        {
            "Status": "success",
            "CertDomain": "www.xuqingkai.cn,xuqingkai.cn",
            "EndTime": 1746460870000,
            "ProductName": "FC",
            "InstanceId": "cas-job-user-0r12su",
            "GmtModified": 1746460870000,
            "StartTime": 1746460870000,
            "CertType": "free",
            "Del": 0,
            "Name": "certdeploy20250505232211",
            "GmtCreate": 1746458533000,
            "JobType": "user",
            "ScheduleTime": 1746458557000,
            "UserId": 1353813254939731,
            "Id": 239379,
            "Rollback": 0
        },
        {
            "Status": "success",
            "CertDomain": "www.xuqingkai.cn,xuqingkai.cn",
            "EndTime": 1746451203000,
            "ProductName": "FC",
            "InstanceId": "cas-job-user-ih9t53",
            "GmtModified": 1746451202000,
            "StartTime": 1746451202000,
            "CertType": "free",
            "Del": 0,
            "Name": "deploy-task-1746451118444",
            "GmtCreate": 1746451149000,
            "JobType": "user",
            "ScheduleTime": 1746451150000,
            "UserId": 1353813254939731,
            "Id": 239340,
            "Rollback": 0
        }
    ]
	}
	*/
}