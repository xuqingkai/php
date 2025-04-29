<?php
if($_SERVER['REQUEST_METHOD']!='GET'){
    $json=json_decode($_POST['text'],true);
    exit(json_encode($json, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
}
?>

<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>JSON格式化</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style type="text/css">
        textarea{box-sizing:border-box;border:1px dashed #ccc;padding:10px;width:100%;}
    </style>
</head>
<body>
	<form method="post" action="">
        <fieldset>
            <legend>JSON格式化</legend>
            <textarea name="text"></textarea> 
        </fieldset>
        <div id="response"></div>
    </form>
    <script type="text/javascript">
        var lastValue='';
        var textarea=document.querySelector('textarea');
        textarea.addEventListener("blur", function(){
            textarea.style.height=Math.min(textarea.scrollHeight,200) + 'px';
            let value=document.querySelector('textarea[name=text]').value;
            if(value!=lastValue){
                lastValue=value;
                let data=new FormData();
                data.append('text',lastValue);
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
                    document.querySelector('#response').insertAdjacentHTML('afterbegin','<fieldset><legend>'+new Date().toISOString().substr(0,19).replace('T',' ')
                    +'</legend><textarea readonly disabled></textarea></fieldset>');
                    let responseTextarea=document.querySelector('#response textarea');
                    responseTextarea.innerHTML=data;
                    responseTextarea.style.height = responseTextarea.scrollHeight + 'px';
                    console.log(data);
                })
                .catch(error => console.error('Error:', error));
            }
        });
    </script>
</body>
</html>
