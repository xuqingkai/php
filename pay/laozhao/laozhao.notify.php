<?php
include_once('./laozhao.config.php');

$laozhao['notify']=$_GET;
if(intval($laozhao['notify']['opstate'])==0){
  $laozhao['sign_string']='orderid='.$laozhao['notify']['orderid'].'&opstate='.$laozhao['notify']['opstate'].'&ovalue='.$laozhao['notify']['ovalue'].'';
  $laozhao['sign']=md5($laozhao['sign_string'].$laozhao['mch_key']);
  if($laozhao['notify']['sign']==$laozhao['sign']){
    
  }
}
