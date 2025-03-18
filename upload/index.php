<?php
echo(json_encode($_FILES, JSON_UNESCAPED_UNICODE));
foreach($_FILES as $file){
    if (move_uploaded_file($file['tmp_name'], $file['name'])) {
        
    } else {
        
    }
}
?>
