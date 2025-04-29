<?php
if($_SERVER['REQUEST_METHOD']!='GET'){
    $sm=$_POST['sm'];
    include_once('./'.$sm.'.php');
    $action=$_POST['action'];
    $key=$_POST['key'];
    $text=$_POST['text'];
    if($sm=='SM3'){
        $sm3=new SM3();
        if(strlen($key)>0){ $text=$key.$text; }
        $text=$sm3->sign($text);
    }elseif($sm=='SM4'){
        $sm4=new SM4();
        if($action=='encrypt'){
            $text=$sm4->encrypt($key,$text);
        }else{
            $text=$sm4->decrypt($key,$text);
        }
    }
    exit($text);
}
//exit(base64_encode(openssl_encrypt('prs@2022','DES-CBC', 'prs@2022', OPENSSL_RAW_DATA, 'prs@2022')));// OPENSSL_RAW_DATA 和 OPENSSL_ZERO_PADDING 或 OPENSSL_DONT_ZERO_PAD_KEY
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>SM</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3/dist/css/uikit.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/uikit@3/dist/js/uikit.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit@3/dist/js/uikit-icons.min.js"></script>
</head>
<body>
	<form class="uk-container" method="post" action="">
        <fieldset class="uk-fieldset">
            <legend class="uk-legend uk-text-center">SM</legend>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">算法</div>
                <div class="uk-width-expand">
                    <select class="uk-select" name="sm">
                        <option value="SM3">SM3</option>
                        <option value="SM4">SM4</option>
                    </select>
                </div>
            </div>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">操作</div>
                <div class="uk-width-expand">
                    <select class="uk-select" name="action">
                        <option value="encrypt">encrypt</option>
                        <option value="decrypt">decrypt</option>
                    </select>
                </div>
            </div>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">密钥</div>
                <div class="uk-width-expand"><input class="uk-input" type="text" name="key"></div>
            </div>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">数据</div>
                <div class="uk-width-expand">
                    <textarea class="uk-textarea" rows="5" name="text"></textarea>
                 </div>
            </div>
            <div class="uk-text-center">
                 <button class="uk-button uk-button-primary uk-button-large" type="button">submit</button>
            </div>
        </fieldset>
        <div id="response"></div>
    </form>
    <script type="text/javascript">
        const urlParams = new URLSearchParams(window.location.search).forEach((value,key)=>{
            document.querySelector('*[name='+key+']').value=value;
        });
        document.querySelector('button').addEventListener("click", function(){
            let data=new FormData();
            data.append('sm',document.querySelector('select[name=sm]').value);
            data.append('action',document.querySelector('select[name=action]').value);
            data.append('key',document.querySelector('input[name=key]').value);
            data.append('text',document.querySelector('textarea[name=text]').value);
            fetch(window.location.href, {
                method: 'POST', // 请求方法，默认为 'GET'
                headers: {}, //'Content-Type': 'application/json',
                body: data, // 请求体，适用于 'POST', 'PUT' 等方法
                credentials: 'include', // 控制是否发送 cookies，可选 'omit', 'same-origin', 'include'
                cache: 'no-cache', // 缓存策略，可选 'default', 'no-store', 'reload', 'no-cache', 'force-cache', 'only-if-cached'
            })
            // fetch() 返回的 Promise 在解析时会得到一个 Response 对象。要访问响应数据，需要调用 Response 对象上的方法，如 .json()、.text()、.blob()、.arrayBuffer()，它们各自返回一个新的 Promise，解析完成后提供对应类型的数据。
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok.');
                }
                return response.text();
            })
            .then(data => {
                document.querySelector('#response').insertAdjacentHTML('afterbegin','<fieldset><legend>'+new Date()+'</legend><textarea readonly disabled style="box-sizing:border-box;border:1px dashed #ccc;padding:10px;width:100%;"></textarea></fieldset>');
                let textarea=document.querySelector('#response textarea');
                textarea.innerText=data;
                textarea.style.height = textarea.scrollHeight + 'px';
                console.log(data);
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
