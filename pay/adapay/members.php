<?php
//https://doc.adapay.tech/document/api/#/member?id=%e5%88%9b%e5%bb%ba%e7%94%a8%e6%88%b7%e5%af%b9%e8%b1%a1
include_once('adapay.config.php');
$adapay['api_path']='/v1/members';
$adapay['api_method']='POST';

$post=$_POST;
if($post){
    $adapay['data']=[];
    $adapay['data']['app_id']=$adapay['api_id'];
    $adapay['data']['member_id']=$post['member_id'] ?? 'mid'.date('YmdHis');

    //{"data":"{\"app_id\":\"\",\"created_time\":\"1728552251\",\"disabled\":\"N\",\"identified\":\"N\",\"member_id\":\"\",\"object\":\"member\",\"status\":\"succeeded\",\"prod_mode\":\"true\"}","signature":""}
    $adapay['response_data']=adapay_request();
    $adapay['response']=json_decode($adapay['response_data'], true);
    $adapay['response']['data']=json_decode($adapay['response']['data'], true);
    file_put_contents('members_'.$adapay['data']['member_id'].'.txt', json_encode($adapay));
    if($adapay['response']['data']['status']=='succeeded'){
        exit('<a href="settle_accounts.php?member_id='.$adapay['response']['data']['member_id'].'">'.$adapay['response']['data']['member_id'].'</a>');
    }else{
	header('Content-Type: application/json; charset=utf-8');
        exit(json_encode($adapay));
    }
}
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>开通用户账号（member_id）</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit/dist/css/uikit.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/uikit/dist/js/uikit.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit/dist/js/uikit-icons.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
	<style type="text/css">
	    .uk-tab>*>a{font-size:1.0rem;}
	    .delete,.delete:hover{text-decoration: line-through;}
	</style>
</head>
<body>
    <form action="./members.php" method="post">
        <p>用户账号（member_id）：<input type="text" class="uk-input" name="member_id" /></p>
        <p><input type="submit" class="uk-button" /></p>
    </form>
</body>
