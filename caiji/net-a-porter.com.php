
<?php
$html=$_POST['html'];
if($html){
    error_reporting(E_ALL);
    //$html=file_get_contents('https://www.net-a-porter.com/zh-us/shop/%E9%9E%8B%E5%B1%A5?orderBy=10&pageNumber=1');
    $list=substr($html,strpos($html,'<script>window.state=')+strlen('<script>window.state='));
    $list=substr($list,0,strpos($list,'</script>'));
    //exit($list);
    $list=json_decode($list, true);
    echo('<table border="1">');$i=0;
    foreach ($list['plp']['listing']['visibleProducts'][0]['products'] as $item){$i++;
    
        //if($i<=30){continue;}
    
        
        $url='https://www.net-a-porter.com/zh-us/shop/product'; 
        $seo=explode('/', $item['seo']['seoURLKeyword']);
        foreach ($seo as $seoitem){ $url.='/'.urlencode($seoitem); }
        //echo($url.'<br />');continue;
        $html=file_get_contents($url);
        
        $detailhtml=substr($html,strpos($html,'<script>window.state=')+strlen('<script>window.state='));
        $detailhtml=substr($detailhtml,0,strpos($detailhtml,'</script>'));
        //exit($detail);
        
        $detail=json_decode($detailhtml, true);
        $goods=$detail['pdp']['detailsState']['response']['body']['products'][0]['productColours'][0];
        $img='https://www.net-a-porter.com/variants/images/'.$goods['partNumber'].'/in/w920_q60.jpg';
        echo('<tr>');
        echo('<td><a href="'.$url.'">'.$i.'</a></td>');
        echo('<td>'.$goods['partNumber'].'.<a href="'.$img.'" onclick="return false;">jpg</a></td>');
        //echo('<td><img src="'.$url.'" height="50" /></td>');
        $title=$goods['seo']['title'];
        if(strpos($title,"|")!==false){$title = substr($title,0,strpos($title,"|"));}
        echo('<td>'.$title.'</td>');
       // file_put_contents('./photo/'.$goods['partNumber'].'.jpg', file_get_contents($url));
        echo('<td>'.$goods['partNumber'].'.jpg</td>');
        echo('<td>'.($goods['price']['sellingPrice']['amount']/100).'</td>');
    
        $qianzhui='';$guige1=''; $guige2=''; 
        if(strpos($item['seo']['seoURLKeyword'],'鞋')!==false){
            $qianzhui='US';
        }
        foreach ($goods['attributes'] as $attr){
            if($attr['identifier']=='Brand Colour'){
                foreach ($attr['values'] as $item){ $guige1.=$item['label'].' '; }
            }
            if($attr['identifier']=='Brand Size'){
                foreach ($attr['values'] as $item){ $guige2.=$qianzhui.$item['label'].' '; }
            }
        }
        
        echo('<td>'.$guige1.'</td>');
        echo('<td>'.$guige2.'</td>');
        echo('<td>'.str_replace('<br>','',$goods['editorialDescription']).'</td>');
    
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
    <title>抓取</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

</head>
<body>
    <form method="post" action="index.php">
        <textarea name="html" style="width:99%;height:500px;">打开网页：https://www.net-a-porter.com/zh-us/shop/%E6%9C%8D%E8%A3%85/%E4%B8%8A%E8%A1%A3/%E5%A5%B3%E8%A1%AB?orderBy=10&pageNumber=1，复制当前页源代码到此提交</textarea>
        <br />
        <input type="submit" vallue="提交">
    </form>
</body>
</html>
