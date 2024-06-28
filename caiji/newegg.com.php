<?php
$html=$_POST['html'];
if($html){
    error_reporting(E_ALL);
    //$html=file_get_contents('https://www.net-a-porter.com/zh-us/shop/%E9%9E%8B%E5%B1%A5?orderBy=10&pageNumber=1');
    $list=substr($html,strpos($html,'window.__initialState__ = ')+strlen('window.__initialState__ = '));
    $list=substr($list,0,strpos($list,'</script>'));
    //exit($list);
    $list=json_decode($list, true);
    echo('<table border="1">');$i=0;
    foreach ($list['Products'] as $item){$i++;
    
            //if($i<=30){continue;}
        
        $img = $item['ItemCell']['Image']['ItemCellImageName'];
        $src = "https://c1.neweggimages.com/ProductImageCompressAll300/".$img;
        

        $guige1=$item['ItemCell']['Description']['IMDescription'];
        if(strpos($guige1,'\\')!==false){ 
            $guige1=substr($guige1, 0, strpos('\\'));
        }
    
        
        $title=$item['ItemCell']['Description']['Title'];
       // exit($title);
        
        $price=$item['ItemCell']['FinalPrice'];
        
        
        
        $contents=$item['ItemCell']['Description']['BulletDescription'];
        $contents=str_replace("\r\n","",$contents);
       
        $href='';
        echo('<tr>');
        echo('<td><a href="'.$href.'" target="_blank">'.$i.'</a></td>');
        echo('<td><a href="'.$src.'" onclick="return false;">jpg</a></td>');
        //echo('<td><img src="'.$url.'" height="50" /></td>');
        echo('<td>'.$title.'</td>');
       // file_put_contents('./photo/'.$goods['partNumber'].'.jpg', file_get_contents($url));
        echo('<td>'.$img.'</td>');
        echo('<td>'.$price.'</td>');
    
        
        echo('<td>'.$guige1.'</td>');
        echo('<td></td>');
        echo('<td>'.$contents.'</td>');
    
        echo('</tr>');
        
    }
    echo('</table>');
    exit();
}?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>newegg.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
    <form method="post" action="kouzhao.php">
        <textarea name="html" style="width:99%;height:500px;">打开网址：https://www.newegg.com/p/pl?d=mask，复制当前页源代码到此提交即可</textarea>
        <br />
        <input type="submit" vallue="提交">
    </form>
</body>
</html>
