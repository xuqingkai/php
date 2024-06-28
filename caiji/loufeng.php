function caiji(){

    $img_host = 'https://www.imagecdn.com';
    $api_host = 'http://www.example.com';
    $api_path = '/api/json/data.html';
    $upload_folder = 'uploads';
    $result='';
    $all = intval('0'.$_GET['all']);//默认0,只采集新数据
    $page = intval('0'.$_GET['page']); if($page<1){$page=1;}
    $response = file_get_contents($api_host.$api_path), false, stream_context_create(array('http'=>array('method'=>'POST','header'=>"Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser\r\nX-Requested-With:XMLHttpRequest",'content'=>'page='.$page),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
    $json = json_decode($response,true);
    $total=0;
    foreach($json['data'] as $key=>$item){
        $indexs = date('Ymd',$item['create_time']/1000);
        $indexs = $item['indexs'];
        $img_path = '';
        $thumb_save = '../public/'.$upload_folder.'/'.$indexs.'/'.$item['id'].'/thumb/';
        //exit($thumb_save'----'.$item['publishedAt']);
        if(!is_dir($thumb_save)){ mkdir($thumb_save, 0777, true); }
        $item_thumb = $item['img'];
        $item_thumb = str_replace('../public','',$item_thumb);
        if($item_thumb && strpos($item_thumb,'.') !== false){
            $name = substr($item_thumb, 0, strrpos($item_thumb,'.'));
            $ext = substr($item_thumb, strrpos($item_thumb,'.') + 1);
            $thumb_img = $thumb_save.md5($ext).'.'.$ext;
            if(!file_exists($thumb_img)){
                $image = @file_get_contents($img_host.$item_thumb, false, stream_context_create(array('http'=>array('method'=>'GET','header'=>"Content-type:application/x-www-form-urlencoded\r\nReferer:".$api_host."\r\nUser-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0",'content'=>''),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
                if($image === false){
                    $thumb_img = '';
                }else{
                    $img_path=$item['img'];
                    file_put_contents($thumb_img, $image);
                }
            }else{
                $img_path='已有：'.$thumb_img;
            }
        }else{
            $thumb_img = '';
        }
        

        $file_save = '../public/'.$upload_folder.'/'$indexs.'/'.$item['id'].'/file/';
        if(!is_dir($file_save)){ mkdir($file_save, 0777, true); }
        
        $imgs_path=[];
        if($item['file']){
            $file_imgs = [];
            $files = substr($item['file'],0,1)=='[' ? json_decode($item['file'],true) : explode(',',$item['file']);
            if($files){
                foreach($files as $index => $file){
                    $item_file=$file;
                    if(strpos($item_file,'.') !== false){
                        $name = substr($item_file, 0, strrpos($item_file,'.'));
                        $ext = substr($item_file, strrpos($item_file,'.') + 1);
                        $file_path = $file_save.md5($name).'.'.$ext;
                        if(!file_exists($file_path)){
                            $image=@file_get_contents($img_host.$item_file, false, stream_context_create(array('http'=>array('method'=>'GET','header'=>"Content-type:application/x-www-form-urlencoded\r\nReferer:".$api_host."\r\nUser-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36 Edg/124.0.0.0",'content'=>''),'ssl'=>array('verify_peer'=>false, 'verify_peer_name'=>false))));
                            if($image===false){
                                
                            }else{
                                $imgs_path[] = $file;
                                file_put_contents($file_path, $image);
                                $file_imgs[] = $file_path;
                            }
                        }else{
                            $file_imgs[] = $file_path;
                        }
                    }
                 }
            }
            $file_imgs = json_encode($file_imgs);   
        }else{
            $file_imgs = '[]';
        }
        $only=md5($api_host.'/'.$item['id']);
        $only=$item['only'];
        $thread = [
            'title'=>$item['title'],
            'age'=>$item['age'],
            'price'=>$item['price'],
            'project'=>$item['project'],
            'process'=>$item['process'],
            'qq'=>$item['qq']??'',
            'wechat'=>$item['wechat']??'',
            'phone'=>$item['phone']??'',
            'address'=>$item['address']??'',
            'dz'=>$item['dz']??'',
            'pid'=>$item['pid']??'0',
            'cid'=>$item['cid']??'0',
            'status'=>$item['status']==2?'0':'0',
            'browse'=>$item['browse'],
            'create_time'=>$item['create_time'],//格式：10位数字时间戳
            'author'=>$item['author'],
            'file'=>$file_imgs,
            'img'=>$thumb_img,
            'face_value'=>$item['face_value'],
            'indexs'=>$indexs,//格式：20080808
            'only'=>$only,
        ];
        $id = db::table('common_thread')->where('only', $only)->value('id');
        if(!$id){
            Db::name('common_thread')->insert($thread);
            $result .= json_encode($thread,JSON_UNESCAPED_UNICODE).'<br >'.$key.'/'.$page.'----'.$item['id'].'----'.$item['title'].'----<a href="'.$img_host.$item['img'].'" target="_blank">'.$img_path.'</a>---'.json_encode($imgs_path).'<hr />'."\r\n";
            $total++;
        }else{
            Db::name('common_thread')->where('id', $id)->update(['img'=>$thumb_img,'file'=>$file_imgs]);
            $result .= json_encode($thread,JSON_UNESCAPED_UNICODE).'<br >'.$key.'/'.$page.'----'.$item['id'].'----'.$item['title'].'---已存在(<a href="/index/data/show.html?id='.$id.'">'.$id.'</a>)----'.$img_path.''.'<hr />'."\r\n";
            if($all>0){$total++;}
        }
    }
    if($total){
        $result .= '<script>window.setTimeout(function(){window.location.href="?all='.$all .'&page='.($page+1).'";},0);</script>';
    }else{
        $result = '<h1 id="seconds">3600</h1>'.$result.'<script>var seconds=document.getElementById("seconds").innerHTML*1;var second=0;window.setInterval(function(){second++;if(second>seconds){window.location.href="?all=0&page=1";}else{document.getElementById("seconds").innerHTML=(seconds-second)+"秒后刷新";}},1000);</script>';
    }
    return $result;
}
