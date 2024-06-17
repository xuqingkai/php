<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>HTTP_USER_AGENT</title>
</head>
<body>
<h1>【<?php echo(user_agent()); ?>】</h1>
<p><?php echo($_SERVER["HTTP_USER_AGENT"]); ?></p>
</body>
</html>


<?php
function user_agent(){
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	if(strpos($user_agent, " MicroMessenger/")>0)
	{
		return "weixin";
	}
	elseif(strpos($user_agent, " AlipayClient/")>0)
	{
		return "alipay";
	}
	elseif(strpos($user_agent, " QQ/")>0)
	{
		return "qq"; 
	}
	elseif(strpos($user_agent, "Android")>0 || strpos($user_agent, "iPhone")>0 || strpos($user_agent, "ios")>0 || strpos($user_agent, "iPod")>0)
	{
		return "mobile";
	}
	else
	{
		return "pcweb";
	}
}
?>
