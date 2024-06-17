<?php
function handler($event, $context) {
  $logger = $GLOBALS['fcLogger'];
  $logger->info('event: ' . $event);
  //return array("statusCode"=>200,'headers'=>array("Content-Type"=>"application/json"),'isBase64Encoded'=>false,"body"=>$event);

  try {
    $evt = json_decode($event, true);
    $name='callback';
    $url=$evt['rawPath']??'';
    $headers=[];
    if($url){
        if(strpos($url,'/http:/')!==false || strpos($url,'/https:/')!==false){
            $headers=str_replace('/','',substr($url,0,strpos($url,'/http')));
            $url=substr($url,strpos($url,'/http')+1);
            if(strpos($url,'://')===false){ $url=str_replace(':/','://',$url); }
            $name=explode('/',$url)[2];
        }else{
            $headers=substr($url,1);
            if(strpos($headers,'/')!==false){$headers=substr($headers,0,strpos($headers,'/'));}
            $url='';
        }
        if($headers){
            $headers=explode('|',str_replace(',','|',$headers));
        }else{
            $headers=[];
        }
    }
    $file='./'.$name.'.txt';
    //return array("statusCode"=>200,'headers'=>array("Content-Type"=>"text/html"),'isBase64Encoded'=>false,"body"=>json_encode($headers).'<hr />'.$url.'<hr />'.$name.'<hr />'.$file);

    $response='';
    $query=$evt['queryParameters'];
    if(isset($query['view'])){
      $response='<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>callback</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style type="text/css">
      body{font-size:14px;}
      textarea{width:99%;height:90vh;font-size:16px;}
    </style>
</head>
<body>
    <form>
        <a style="float:right" href="?clear">清空</a><a href="?view">首页</a>
        <textarea>'.(is_file($file)?@file_get_contents($file):'').'</textarea>
    </form>
</body>
</html>';
    }else if(isset($query['clear'])){
      @unlink($file_path);
      $response='<script type="text/javascript">window.location.href="?view";</script>';
    }else if($evt['rawPath'] != '/favicon.ico'){
      $response=$name;
      $header=[];
      foreach($evt['headers'] as $key=>$val){
        foreach($headers as $item){
            if(str_replace('-','',strtoupper($key)) == str_replace('-','',strtoupper($item))){
              $header[]=$key.":".$val; 
            }
        }
      }
      //return array("statusCode"=>200,'headers'=>array("Content-Type"=>"application/json"),'isBase64Encoded'=>false,"body"=>json_encode(array((in_array('a',['A'],true)?'1':'0'),$headers,$header)));

      $method=$evt['requestContext']['http']['method'];
      $body = $evt['body'];
      if ($evt['isBase64Encoded']) { $body = base64_decode($evt['body']); }
      $content = $body;

      $text="\r\n\r\n";
      $text.=date('Y-m-d H:i:s')."\r\n";
      $text.="-----【URL】------------------------------------------------------------------\r\n";
      $text.=$evt['rawPath'].'?'.http_build_query($query)."\r\n";
      $text.="-----【HEADER】------------------------------------------------------------------\r\n";
      $text.= implode("\r\n",$header)."\r\n";
      $text.="-----【".$method."】------------------------------------------------------------------\r\n";
      $text.=$content."\r\n";
      if($url){
          if(!in_array('Content-Type',$headers)){ $header[]='Content-Type:application/x-www-form-urlencoded'; }
          $response=file_get_contents($url, false, stream_context_create(array(
            'http' => array(
              'method' => $method,
              'header'  => implode("\r\n",$header),
              'content' => $content
            ),
            'ssl'=>array(
              'verify_peer'=>false,
              'verify_peer_name'=>false
            )
          )));

          $text.="-----【RESPONSE】------------------------------------------------------------------\r\n";
          $text.=$response."\r\n";
      }
      $text.="=======================================================================\r\n";
      $text.=is_file($file)?@file_get_contents($file):'';
      @file_put_contents($file, $text);
    }

    return array(
      "statusCode" => 200,
      'headers' => array("Content-Type" => "text/html"),
      'isBase64Encoded' => false,
      "body" => $response
    );
  } catch (Exception $e) {
    return $e->getMessage();
  }
}
