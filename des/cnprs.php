<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>注册机</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style type="text/css">
        input{font-size:14px;padding:5px;margin-top:10px;width:300px;}
        textarea{color:#f00;padding:10px;border:1px solid #ccc;width:500px;}
    </style>
</head>
<body>
<?php
$org=isset($_POST["org"])?$_POST["org"]:"乡镇卫生院";
$date=(isset($_POST["date"]) && $_POST['date'])?$_POST["date"]:date("Ymd",time()+365*24*60*60);
$code=$_POST["code"];
?>
<form method="post" action="<?php echo($_SERVER['SCRIPT_NAME']);?>">
单位：<input type="text" name="org" value="<?php echo($org);?>" /><br />
日期：<input type="text" name="date" value="<?php echo($date);?>" /><br />
设备：<input type="text" name="code" value="<?php echo($code);?>" placeHolder="可为空" /><br />
<input type="submit" value="提交" />
</form>
<hr /><div>
<?php
if($_SERVER['REQUEST_METHOD']!='GET'){
    $key="prs@2022";
    $mode='DES-CBC';
    $padding=OPENSSL_RAW_DATA;
    $iv=$key;
    $text=$org.$date;
    if($org!='乡镇卫生院' && $org!=''){ $text.='_'.(strlen($code)>0 ? $code:'0'); }
    $encrypt=openssl_encrypt($text, $mode, $key, $padding, $iv);
    $encrypt=base64_encode($encrypt);

    $decrypt=base64_decode($encrypt);
    $decrypt=openssl_decrypt($decrypt, $mode, $key, $padding, $iv);
    echo($org.'的注册码为<br /><br /><textarea readonly disabled title="'.$decrypt.'">'.$encrypt.'</textarea>');
}
?>
<script type="text/javascript">
    let textarea=document.querySelector('textarea');
    textarea.style.height = textarea.scrollHeight + 'px';
</script>
</div>
</body>
</html>