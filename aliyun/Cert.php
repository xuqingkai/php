<?php
include_once('./cas.php');
$days=10*24*60*60;
//查询近日来已部署的任务中的所有域名
$certDomain='';
list($error,$deploys)=aliyun_cas_deploy_list();
if($error){ exit($error);}
foreach($deploys as $deploy){
    if(time()-substr($deploy['GmtCreate'],0,10)<$days){
        $certDomain.=','.$deploy['CertDomain'];
    }  
}
exit(json_encode($deploys, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));


//如果近日即将到期的证书域名不在上述已部署任务中，表示尚未申请和部署，则先重新申请对应的证书
list($error,$certs)=aliyun_cas_cert_list();
if($error){ exit($error);}
foreach($certs as $cert){
    if(strpos($certDomain,$cert['CommonName'])===false){
        if(substr($cert['CertEndTime'],0,10)-time()<$days){
            list($error,$create)=aliyun_cas_cert_create($cert['CommonName'], 'DNS');
            if($error){ exit($error);}
        }
    }
}
//exit(json_encode($certs, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));


//如果近日申请的证书域名不在上述已部署任务中，表示尚未部署，则获取对应的域名证书Id
$domains=array();
list($error,$certs)=aliyun_cas_cert_list();
if($error){ exit($error);}
foreach($certs as $cert){
    if(strpos($certDomain,$cert['CommonName'])===false){
        if(time()-substr($cert['CertStartTime'],0,10)<$days){
            $domains[$cert['CommonName']]=array('certificateId'=>$cert['CertificateId'],'resourceId'=>array());
        } 
    }
}
//exit(json_encode($domains, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
if(!$domains){ exit('暂无待部署证书'); }


//获取域名对应的云资源的所有ID，即使该域名被部署域多个资源而只有其中一个资源可用
foreach($domains as $domain=>$cert){
    list($error,$resources)=aliyun_cas_cert_resource($domain);
    if($error){ exit($error);}
    foreach($resources as $resource){
        if($resource['Domain']==$domain || $resource['Domain']=='www.'.$domain){
            $domains[$domain]['resourceId'][]=array($resource['Id']=>$resource['CloudProduct']);//所有设置了该域名的资源
        }
    }
}
//exit(json_encode($domains, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));


//获取联系人，取第一个即可
$contactId='';
list($error,$contacts)=aliyun_cas_cert_contact();
if($error){ exit($error);}
//exit(json_encode($contacts, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
foreach($contacts as $contact){
    $contactId=$contact['ContactId'];
    break;
}
if(!$contactId){ exit('暂无可用联系人'); }


//获取了域名的证书Id，对应的资源Id，联系人的Id，则创建部署任务，并更新其状态为开始执行
//exit(json_encode($domains, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
foreach($domains as $domain=>$cert){
    echo('部署域名【'.$domain.'】，资源数：'.count($cert['resourceId']).'<br />');
    foreach($cert['resourceId'] as $resourceId=>$cloudProduct){
        echo('到【'.$cloudProduct.'】，'.$cert['certificateId'].'，'.$resourceId.'，'.$contactId.'，<br />');
        list($error,$deploy)=aliyun_cas_cert_deploy($cert['certificateId'], $resourceId, $contactId, $domain.'_'.$cloudProduct.'_'.time());
        if($error){ exit($error);}
        if($deploy['JobId']){
            $update='{}';
            //list($error,$update)=aliyun_cas_cert_update($deploy['JobId']);
            //if($error){ exit($error);}
            echo(json_encode($update, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        }else{
            echo('部署任务创建成功<br />');
            echo(json_encode($deploy, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
        }
        echo('<hr />');
    }      
}

