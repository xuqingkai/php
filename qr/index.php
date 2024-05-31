<?php
//需要开启GD库
error_reporting(0);//隐藏错误防止，导致无法显示图片
require_once('./phpqrcode.php');

$contents = $_GET["text"];
if(!$contents){$contents = $_GET["txt"];}
if(!$contents){$contents = $_GET["value"];}
if(!$contents){$contents = $_GET["url"];}
if(!$contents){$contents = $_GET["qr"];}
if(!$contents){$contents = $_GET["qrcode"];}
if(strlen($contents) == 0){$contents = "null";}

$level = strtoupper($_GET["level"]);
if(strlen($leveL) == 0 || substr_count('LMQH',$level)==0){ $level = 'Q';}

$size = round(intval('0'.$_GET["size"])/29);
if($size < 1){ $size = round(intval('0'.$_GET["width"])/29);}
if($size < 1){ $size = round(intval('0'.$_GET["height"])/29);}
if($size < 1){ $size = 8;}

$margin = intval('0'.$_GET["margin"]);
if($margin < 1){ $margin = 1;}

//@param $text  生成二位的的信息文本；
//@param $outfile  是否输出二维码图片 文件，默认否；
//@param $level  容错率，也就是有被覆盖的区域还能识别，分别是 L（QR_ECLEVEL_L，7%），M（QR_ECLEVEL_M， 15%），Q（QR_ECLEVEL_Q，25%），H（QR_ECLEVEL_H，30%）；
//@param $size  生成图片大小，默认是3；
//@param $margin  二维码周围边框空白区域间距值；
//@param $saveandprint  是否保存二维码并 显示。
QRcode::png($contents, false, "Q", $size, $margin, true);
?>
