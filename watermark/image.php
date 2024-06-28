<?php
private function save_image($src_path, $water_path, $position){
      $src = getimagesize($src_path);
      if($src['mime']=='image/jpg'){
          $src_image = imagecreatefromjpeg($src_path);
      }elseif($src['mime']=='image/jpeg'){
          $src_image = imagecreatefromjpeg($src_path);
      }elseif($src['mime']=='image/png'){
          $src_image = imagecreatefrompng($src_path);
      }elseif($src['mime']=='image/gif'){
          $src_image = imagecreatefromgif($src_path);
      }elseif($src['mime']=='image/wbmp'){
          $src_image = imagecreatefromwbmp($src_path);
      }else{
          $src_image = null;
      }
      
      $water = getimagesize($water_path);
      if($water['mime']=='image/jpg'){
          $water_image = imagecreatefromjpeg($water_path);
      }elseif($water['mime']=='image/jpeg'){
          $water_image = imagecreatefromjpeg($water_path);
      }elseif($water['mime']=='image/png'){
          $water_image = imagecreatefrompng($water_path);
      }elseif($water['mime']=='image/gif'){
          $water_image = imagecreatefromgif($water_path);
      }elseif($water['mime']=='image/wbmp'){
          $water_image = imagecreatefromwbmp($water_path);
      }else{
          $water_image = null;
      }
      
    $w = $src[0]/4;
    $h = ($w / $water[0])*$water[1];
  
    if($position=='left_top'){
        $x = 0;
        $y = 0;
    }elseif($position=='center_top'){
        $x = ($src[0]-$w)/2;
        $y = 0;
    }elseif($position=='right_top'){
        $x = $src[0]-$w;
        $y = 0;
    }elseif($position=='left_middle'){
        $x = 0;
        $y = ($src[1]-$h)/2;
    }elseif($position=='center_middle'){
        $x = ($src[0]-$w)/2;
        $y = ($src[1]-$h)/2;
    }elseif($position=='right_middle'){
        $x = $src[0]-$w;
        $y = ($src[1]-$h)/2;
    }elseif($position=='left_bottom'){
        $x = 0;
        $y = $src[1]-$h;
    }elseif($position=='center_bottom'){
        $x = ($src[0]-$w)/2;
        $y = $src[1]-$h;
    }elseif($position=='right_bottom'){
        $x = $src[0]-$w;
        $y = $src[1]-$h;
    }else{
        $x = ($src[0]-$w)/2;
        $y = $src[1]-$h-$src[1]*0.12;
    }

    $new_water = imagecreatetruecolor($w, $h);
    imagecopyresized($new_water, $water_image, 0, 0, 0, 0, $w, $h, $water[0], $water[1]);
      
    imagecopymerge($src_image, $new_water, $x, $y, 0, 0, $w, $h, 100);//合并两个图片
    imagepng($src_image, $src_path);
    imagedestroy($src_image);
    imagedestroy($water_image);
}
?>
