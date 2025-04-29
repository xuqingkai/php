<?php
//if (is_file($_SERVER["DOCUMENT_ROOT"] . $_SERVER["SCRIPT_NAME"])) {
//    return false;
//} else {
    $uri=$_SERVER['REQUEST_URI'];
    if($uri=='/' || $uri=='/favicon.ico'){return false;}
    
    $content=file_get_contents('php://input');
    $text="\r\n\r\n";
    $text.=date('Y-m-d H:i:s')."\r\n";
    $text.="-----【URL】------------------------------------------------------------------\r\n";
    $text.=$_SERVER['REQUEST_URI']."\r\n";
    $text.="-----【REQUEST_HEADER】------------------------------------------------------------------\r\n";
    $text.= json_encode($_SERVER)."\r\n";
    $text.="-----【".$_SERVER['REQUEST_METHOD']."】------------------------------------------------------------------\r\n";
    $text.=$content."\r\n";
    $text.=json_encode($_FILES, JSON_UNESCAPED_UNICODE)."\r\n";
    foreach($_FILES as $file){ move_uploaded_file($file['tmp_name'], $file['name']); }
    file_put_contents(date('Y-m-d.H').'.txt', $text, FILE_APPEND);

    if(strpos($uri,'/rbmh-phis')!==false){
        $key='5ff7db748e8397825ff7db748e839782';
        setcookie('tk',$key);
        $content=openssl_decrypt(base64_decode($content),'AES-256-ECB',$key,OPENSSL_RAW_DATA);
        file_put_contents(date('Y-m-d.H').'.txt', $content."\r\n", FILE_APPEND);
        
        $uri=substr($uri,strlen('/rbmh-phis'));
        $text='';
        if($uri=='/logon/myRoles'){
            $text='{"userid":"6567e526b33b3d0011feec2f","id":"6567ed2735c9f000105ff482"}';
        }elseif($uri=='/api/chis.ehrHealthRecordService/findOhrHealthRecordList'){
            $text='{"items":[{"idPhr":"0000000000000"}]}';
        }elseif($uri=='/api/chis.ehrViewService/getPersonAllNode'){
            $text='[{"na":"健康体检","children":[{"id":"1111111111111"}]},{"na":"中医管理","children":[{"id":"222222222222"}]},{"na":"老年人自理评估","children":[{"id":"333333333333"}]}]';
        }elseif($uri=='/api/chis.hcHealthCheckService/saveHcHealthCheckList'){
            $text='["exam"]';
        }elseif($uri=='/api/iqac-chis.commonEvaluationService/saveEvaluation'){
            $text='{"record":{"idRecord":"44444444444"}}';
        }elseif($uri=='/api/chis.tcmRegisterListService/saveTcm'){
            $text='{"idRegister":"55555555555555"}';
        }elseif($uri=='/api/chis.tcmGuideService/saveTcmGuide'){
            $text='["tcm"]';
        }elseif($uri=='/api/iqac-chis.commonEvaluationService/saveEvaluation'){
            $text='{"record":{"idRecord":"55555555555555"}}';
        }elseif($uri=='/api/chis.cOhrAssessRecordService/saveOhrAssessQues'){
            $text='["zili"]';
        }else{
            $text='{"uri":"'.$uri.'"}';
        }
        exit('{"code":200,"body":"'.base64_encode(openssl_encrypt($text,'AES-256-ECB',$key,OPENSSL_RAW_DATA)).'"}');
    }else{
        exit('{"SCRIPT_NAME":"'.$_SERVER["SCRIPT_NAME"].'","REQUEST_URI":"'.$uri.'"}');
    }
//}