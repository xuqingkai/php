<?php
if($_SERVER['REQUEST_METHOD']=='POST'){
    $allowed_types = ['image/jpeg', 'image/png'];
    $max_size = 5 * 1024 * 1024;

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];

        // 检查文件类型
        if (!in_array($file_type, $allowed_types)) {
            die("错误：只允许上传 JPEG、PNG 或 PDF 文件！");
        }

        // 检查文件大小
        if ($file_size > $max_size) {
            die("错误：文件大小不能超过 5MB！");
        }

        exit(base64_encode(file_get_contents($file_tmp)));
    } elseif($_POST['text']){
        exit(base64_encode($_POST['text']));
    } else {
        die("没有文件上传或上传出错！");
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>转BASE64</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
    <form action="<?php echo($_SERVER['SCRIPT_NAME']);?>" method="post" enctype="multipart/form-data">
        <label for="file">选择文件：</label>
        <input type="file" name="file" id="file">
        <br><br>
        <textarea name="text" style="width:50%;height:200px;"></textarea><br><br>
        <input type="submit" name="submit" value="上传文件">
    </form>
</body>
</html>