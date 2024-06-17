<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>URI</title>
</head>
<body>
    <h1><a href="/url">/url</a></h1>
    <h1><a href="/url/">/url/</a></h1>
    <h1><a href="/url?id=1">/url?id=1</a></h1>
    <h1><a href="/url/?id=1">/url/?id=1</a></h1>
    <h1><a href="/url/index.php?id=1">/url/index.php?id=1</a></h1>
    <h1><a href="/url/index.php/news/detail?id=1">/url/index.php/news/detail?id=1</a></h1>
    <h1><a href="/url/index.php/news/detail/?id=1">/url/index.php/news/detail/?id=1</a></h1>
    <h1><a href="/url/index.php/news/detail/show.php?id=1">/url/index.php/news/detail/show.php?id=1</a></h1>
    
    <h1><?php echo(__FILE__); ?></h1>
    <code>__FILE__</code>
    
    <h1><?php echo($_SERVER["DOCUMENT_ROOT"]); ?></h1>
    <code>$_SERVER["DOCUMENT_ROOT"]</code>
    
    <h1><?php echo($_SERVER["SCRIPT_FILENAME"]); ?></h1>
    <code>$_SERVER["SCRIPT_FILENAME"]</code>

    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"]); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"]</code>

    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]</code>
    
    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],'/'))); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER["SCRIPT_NAME"],0,strrpos($_SERVER["SCRIPT_NAME"],'/'))</code>
    
    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/'))); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER['HTTP_HOST'].substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/'))</code>

    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]</code>
    
    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["DOCUMENT_URI"]); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["DOCUMENT_URI"]</code>
    
    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]</code>

    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["DOCUMENT_URI"].'?'.$_SERVER["QUERY_STRING"]); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["DOCUMENT_URI"].'?'.$_SERVER["QUERY_STRING"]</code>
    
    <h1><?php echo('http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"]); ?></h1>
    <code>'http'.($_SERVER["SERVER_PORT"]==443?'s':'').'://'.$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"]</code>
    
    <h1>DOCUMENT_URI与PHP_SELF区别，前者实际请求什么就是什么，后者如果请求的是/结尾的地址，会附带index.php</h1>
    <p>
        如请求：/1.php/index/index?dd=2131<br />
        DOCUMENT_URI：/1.php/index/index<br />
        PHP_SELF：/1.php/index/index<br />
          
        如请求：/1.php/index/index/?dd=2131<br />
        DOCUMENT_URI：/1.php/index/index<br />
        PHP_SELF：/1.php/index/index/index.php<br />
    </p>

</body>
</html>
