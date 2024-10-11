<?php
//https://doc.adapay.tech/document/api/#/member?id=%e5%88%9b%e5%bb%ba%e7%bb%93%e7%ae%97%e8%b4%a6%e6%88%b7%e5%af%b9%e8%b1%a1
include_once('adapay.config.php');
$adapay['api_path']='/v1/settle_accounts';
$adapay['api_method']='POST';

$post=$_POST;
if($post){
    $adapay['data']=[];
    $adapay['data']['app_id']=$adapay['api_id'];
    $adapay['data']['member_id']=$_GET['member_id'];
    $adapay['data']['channel']='bank_account';
    $adapay['data']['account_info']=$post;
    
    $adapay['data']['account_info']['bank_acct_type']='2';
    $adapay['data']['account_info']['cert_type']='00';
    
    $adapay['response_data']=adapay_request();
    
    //{"data":"{\"object\":\"settle_account\",\"status\":\"succeeded\",\"prod_mode\":\"true\",\"id\":\"0660117264920128\",\"create_time\":\"1728552314\",\"app_id\":\"app_****\",\"member_id\":\"****\",\"channel\":\"bank_account\",\"account_info\":{\"card_id\":\"6215340301********1\",\"card_name\":\"丁**\",\"cert_type\":\"00\",\"cert_id\":\"4107272002******37\",\"tel_no\":\"1663483****\",\"bank_code\":\"01050000\",\"bank_name\":\"中国建设银行\",\"bank_acct_type\":\"2\",\"prov_code\":\"0031\",\"area_code\":\"3100\"}}","signature":"a5h01jYeTcZN896w/LKu8acrPjK8P/kemgkyCfu2YQMHUqKZt6w9uaxZxPtwzcxaMVY4FkcV0JPzVer65X/TZ5ZBrFwFzebKHF90G14HDnHL3atEHjlXE7W6Fhe++IHEi6hO8URCUEh/kGsgILCD9MXB7Zz8jKj4C/mjXPJZt8Q="}
    $adapay['response']=json_decode($adapay['response_data'], true);
    $adapay['response']['data']=json_decode($adapay['response']['data'], true);
    file_put_contents('settle_accounts_'.$adapay['data']['member_id'].'.txt', json_encode($adapay));
    if($adapay['response']['data']['status']=='succeeded'){
        echo($adapay['response']['data']['account_info']);
        exit('<a href="payments.list.php?member_id='.$adapay['data']['member_id'].'">'.$adapay['data']['member_id'].'</a>');
    }else{
	header('Content-Type: application/json; charset=utf-8');
        exit(json_encode($adapay));
    }
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>绑定结算卡</title>
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
    <form action="./settle_accounts.php?member_id=<?php echo($_GET['member_id']);?>" method="post">
        <p>银行名称：<input type="text" class="uk-input" name="bank_name" /></p>
        <p>银行卡号：<input type="text" class="uk-input" name="card_id" /></p>
        <p>姓名：<input type="text" class="uk-input" name="card_name" /></p>
        <p>身份证号：<input type="text" class="uk-input" name="cert_id" /></p>
        <p>手机号：<input type="text" class="uk-input" name="tel_no" /></p>
        <p><input type="submit" class="uk-button" /></p>
    </form>
</body>
</html>
