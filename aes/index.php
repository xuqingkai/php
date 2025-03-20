<?php
$db=$_POST;

if($db){
    //exit(json_encode($db, JSON_UNESCAPED_UNICODE));
    header('Content-Type: text/plain');
    $text=$_POST['text'];
    if($_POST['action']=='encrypt'){
        $text=openssl_encrypt($text,$_POST['mode'],$_POST['key'],intval($_POST['padding']));
        $text=$_POST['format']=='Hex'?bin2hex($text):base64_encode($text);
    }else{
        $text=$_POST['format']=='Hex'?hex2bin($text):base64_decode($text);
        $text=openssl_decrypt($text,$_POST['mode'],$_POST['key'],intval($_POST['padding']));
    }
    exit($text);
    
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
  <meta charset="utf-8">
  <title>AES加密</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit/dist/css/uikit.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/uikit/dist/js/uikit.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/uikit/dist/js/uikit-icons.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3/dist/jquery.min.js"></script>
  <style type="text/css">
      .delete,.delete:hover{text-decoration: line-through;}
  </style>
</head>
<body>
  <form class="uk-container" method="post" action="<?php echo($_SERVER['SCRIPT_NAME']); ?>">
        <div class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">操作</div>
            <div class="uk-width-expand">
                <select class="uk-select" name="action">
                    <option value="decrypt">解密</option>
                    <option value="encrypt">加密</option>
                </select>
            </div>
        </div>    
        <div class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">模式</div>
            <div class="uk-width-expand">
                <select class="uk-select" name="mode">
                    <option value="AES-256-ECB">AES-256-ECB(32位)</option>
                    <option value="AES-192-ECB">AES-192-ECB(24位)</option>
                    <option value="AES-128-ECB">AES-128-ECB(16位)</option>
                    <option value="AES-128-CBC">AES-128-CBC(16位)</option>
                    <option value="AES-192-CBC">AES-192-CBC(24位)</option>
                    <option value="AES-256-CBC">AES-256-CBC(32位)</option>
                    <option value="AES-128-CFB">AES-128-CFB(16位)</option>
                    <option value="AES-192-CFB">AES-192-CFB(24位)</option>
                    <option value="AES-256-CFB">AES-256-CFB(32位)</option>
                    <option value="AES-128-OFB">AES-128-OFB(16位)</option>
                    <option value="AES-192-OFB">AES-192-OFB(24位)</option>
                    <option value="AES-256-OFB">AES-256-OFB(32位)</option>
                    <option value="AES-128-CTR">AES-128-CTR(16位)</option>
                    <option value="AES-192-CTR">AES-192-CTR(24位)</option>
                    <option value="AES-256-CTR">AES-256-CTR(32位)</option>
                    <option value="AES-128-GCM">AES-128-GCM(16位)</option>
                    <option value="AES-192-GCM">AES-192-GCM(24位)</option>
                    <option value="AES-256-GCM">AES-256-GCM(32位)</option>
                    <option value="DES-ECB">DES-ECB</option>
                    <option value="DES-CBC">DES-CBC</option>
                    <option value="DES-CTR">DES-CTR</option>
                    <option value="DES-OFB">DES-OFB</option>
                    <option value="DES-CF">ADES-CF</option>
                </select>
            </div>
        </div>
        <div class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">填充</div>
            <div class="uk-width-expand">
                <select class="uk-select" name="padding">
                    <option value="1">OPENSSL_RAW_DATA</option>
                    <option value="2">OPENSSL_ZERO_PADDING</option>
                    <option value="3">OPENSSL_NO_PADDING</option>
                    <option value="7">PKCS#7</option>
                    <option value="5">PKCS#5</option>
                </select>
            </div>
        </div>
        <div class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">类型</div>
            <div class="uk-width-expand">
                <select class="uk-select" name="format">
                    <option value="Base64">Base64</option>
                    <option value="Hex">Hex(16进制)</option>
                </select>
            </div>
        </div>
        <div class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">密钥</div>
            <div class="uk-width-expand"><input class="uk-input" type="text" name="key"></div>
        </div>
        <div class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">密文</div>
            <div class="uk-width-expand">
                <textarea class="uk-textarea" rows="5" name="text"></textarea>
            </div>
        </div>
        <div class="uk-grid-collapse uk-flex-middle" uk-grid>
            <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">&nbsp;</div>
            <div class="uk-width-expand"><button class="uk-button uk-button-default">提交</button></div>
        </div>
        <div id="result"></div>
    </form>
    <script>
        const urlParams = new URLSearchParams(window.location.search).forEach((value,key)=>{
            $('*[name='+key+']').val(value);
        });
        $('form').submit(function(e){
            e.preventDefault();
            $.post(window.location.href, $(this).serialize(),function(data){
                $('#result').prepend('<fieldset><legend>'+new Date()+'</legend><pre>'+data+'</pre></fieldset>');
            });
        });
    </script>
</body>
</html>
