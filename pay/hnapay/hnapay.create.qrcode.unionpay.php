<?php
include_once('./hnapay.config.php');

$hnapay['data']=array();
$hnapay['data']['version']='2.1';
$hnapay['data']['tranCode']='WS01';
$hnapay['data']['merId']=$hnapay['merId'];
$hnapay['data']['merOrderNum']=$_POST['orderid'];
$hnapay['data']['tranAmt']=intval($_POST['money'])*100;
$hnapay['data']['submitTime']=date('YmdHis');
$hnapay['data']['payType']='QRCODE_B2C';
$hnapay['data']['orgCode']='UNIONPAY';
$hnapay['data']['tranIP']=$_SERVER['REMOTE_ADDR'];
$hnapay['data']['notifyUrl']='http'.(isset($_SERVER["HTTPS"])?'s':'').'://'.$_SERVER["HTTP_HOST"].'/pay/hnapay/hnapay.notify.unionpay.php';
//$hnapay['data']['weChatMchId']=$hnapay['weChatMchId'];
$hnapay['data']['charset']='1';//1：UTF-8
$hnapay['data']['signType']='1';//1：RSA，3：国密交易证书，4：国密密钥

//exit(json_encode($hnapay, JSON_UNESCAPED_UNICODE));

$hnapay['sign_str']='';
$hnapay['sign_str'].='tranCode=['.$hnapay['data']['tranCode'].']';
$hnapay['sign_str'].='version=['.$hnapay['data']['version'].']';
$hnapay['sign_str'].='merId=['.$hnapay['data']['merId'].']';
$hnapay['sign_str'].='submitTime=['.$hnapay['data']['submitTime'].']';
$hnapay['sign_str'].='merOrderNum=['.$hnapay['data']['merOrderNum'].']';
$hnapay['sign_str'].='tranAmt=['.$hnapay['data']['tranAmt'].']';
$hnapay['sign_str'].='payType=['.$hnapay['data']['payType'].']';
$hnapay['sign_str'].='orgCode=['.$hnapay['data']['orgCode'].']';
$hnapay['sign_str'].='notifyUrl=['.$hnapay['data']['notifyUrl'].']';
$hnapay['sign_str'].='charset=['.$hnapay['data']['charset'].']';
$hnapay['sign_str'].='signType=['.$hnapay['data']['signType'].']';

openssl_sign($hnapay['sign_str'], $hnapay['data']['signMsg'], $hnapay['shoukuan_private_key'], version_compare(PHP_VERSION,'5.4.8','>=') ? OPENSSL_ALGO_SHA1 : SHA1);
//$hnapay['data']['signMsg']=base64_encode($hnapay['data']['signMsg']);
$hnapay['data']['signMsg']=bin2hex($hnapay['data']['signMsg']);
$hnapay['data']['signMsg']=strtoupper($hnapay['data']['signMsg']);


$hnapay['query_string']=htmlspecialchars(http_build_query($hnapay['data']));

$hnapay['response_string']=file_get_contents($hnapay['host'].'/website/scanPay.do', false, stream_context_create(array(
	'http' => array(
		'method' => 'POST',
		'header'  => "Content-type:application/x-www-form-urlencoded\r\nUser-Agent:AppBrowser",
		'content' => http_build_query($hnapay['data'])
	),
	'ssl'=>array(
		'verify_peer'=>false,
		'verify_peer_name'=>false
	)
)));
//{\"charset\":\"1\",\"msgExt\":\"\",\"hnapayOrderId\":\"2023062913943508\",\"resultCode\":\"0000\",\"errorCode\":\"\",\"version\":\"2.1\",\"merOrderNum\":\"202306291711018705\",\"submitTime\":\"20230629171101\",\"qrCodeUrl\":\"https://qrcode.hnapay.com/qrcode.shtml?qrContent=https://bisgateway.hnapay.com/wechat/connect/auth.shtml?bankOrderId=2306291711012625741&sign=9BE4ECFBB278FDB16B81C720C4671907\",\"tranAmt\":\"1.01\",\"signType\":\"1\",\"merId\":\"11000007624\",\"tranCode\":\"WS01\",\"signMsg\":\"05ab7127d44435b4ee43d3b9cb77a3d735c40b1029525d1f33f12073ef2be5198134d774b5fdfa5a9c14392cb504a449ffcd04e45d09ec1c5cba9394cc2968af2f7c2e7fc5b6d8f795fd52c73e2ea0fe938046ccf7d2ab12b9750ab75b255b7090f41739ab6e2d4a4b113359b873953994942f7da74b241fdeab9bd5effd786b\"}
$hnapay['response']=json_decode($hnapay['response_string'], true);
if($hnapay['response']['resultCode']=='0000'){
    $hnapay['qrCodeUrl']=$hnapay['response']['qrCodeUrl'];
    $hnapay['qrCodeUrl']=explode('qrContent=',$hnapay['qrCodeUrl'])[1];
    $hnapay['qrCodeUrl']=explode('&sign=',$hnapay['qrCodeUrl'])[0];
    //exit($hnapay['qrCodeUrl'].'<br /><img src="'.$hnapay['response']['qrCodeUrl'].'" />');
}else{
    exit(json_encode($hnapay, JSON_UNESCAPED_UNICODE));
}
?>

    		  	 		
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!--[if IE 8]>
	<style>.ie8 .alert-circle, .ie8 .alert-footer {
		display: none
	}

	.ie8 .alert-box {
		padding-top: 75px
	}

	.ie8 .alert-sec-text {
		top: 45px
	}</style><![endif]-->
	<title>跳转提示 - 熊猫畔</title>

<style>
body {
	margin: 0;
	padding: 0;
	background: #E6EAEB;
	font-family: Arial, '微软雅黑', '宋体', sans-serif
}

.alert-box {
	display: none;
	position: relative;
	margin: 96px auto 0;
    padding: 180px 85px 22px;
    border-radius: 10px 10px 0 0;
	background: #FFF;
	box-shadow: 5px 9px 17px rgba(102, 102, 102, 0.75);
	width: 286px;
	color: #FFF;
	text-align: center
}

.alert-box p {
	margin: 0
}

.alert-circle {
	position: absolute;
	top: -70px;
	left: 111px
}

.alert-sec-circle {
	stroke-dashoffset: 0;
	stroke-dasharray: 735;
	transition: stroke-dashoffset 1s linear
}

.alert-sec-text {
	position: absolute;
	top: -10px;
	left: 190px;
	width: 76px;
	color: #000;
	font-size: 68px
}

.alert-sec-unit {
	font-size: 34px
}

.alert-body {
	margin: 35px 0
}

.alert-head {
	color: #242424;
	font-size: 28px
}

.alert-concent {
	margin: 25px 0 14px;
	color: #7B7B7B;
	font-size: 18px
}

.alert-concent p {
	line-height: 27px
}

.alert-btn {
	display: block;
	border-radius: 10px;
	background-color: #4AB0F7;
	height: 55px;
	line-height: 55px;
	width: 286px;
	color: #FFF;
	font-size: 20px;
	text-decoration: none;
	letter-spacing: 2px
}

.alert-btn:hover {
	background-color: #6BC2FF
}

.alert-footer {
	margin: 0 auto;
	height: 42px;
	width: 120px
}

.alert-footer-icon {
	float: left
}

.alert-footer-text {
	float: left;
	border-left: 2px solid #EEE;
	padding: 3px 0 0 5px;
	height: 40px;
	color: #0B85CC;
	font-size: 12px;
	text-align: left
}

.alert-footer-text p {
	color: #7A7A7A;
	font-size: 22px;
	line-height: 18px
}

.closeWindow {
	text-align: center;
	text-decoration: none;
	font-size: 14px;
	color: #867d7d;
	margin-top: 10px;
	display: block;
	cursor: pointer;
}

@media screen and (max-width: 700px) {
	/*当屏幕尺寸小于600px时，应用下面的CSS样式*/
	.alert-box {
		width: 54.5%;
		display: none;
		position: relative;
		margin: 80px auto 0;
		padding: 180px 85px 22px;
		border-radius: 10px 10px 0 0;
		background: #FFF;
		box-shadow: 5px 9px 17px rgba(102, 102, 102, 0.75);
		color: #FFF;
		text-align: center
	}

	.alert-circle {
		position: absolute;
		top: -70px;
		left: 70px
	}

	.alert-sec-text {
		left: 150px;
	}

	.alert-concent {
		margin: 25px 0 14px;
		color: #7B7B7B;
		font-size: 0.7rem;
	}

	.alert-btn {
		width: 100%;
	}

}

</style>
</head>
<body class="ie8">
<div id="js-alert-box" class="alert-box">
		<svg class="alert-circle" width="234" height="234">
		  <circle cx="117" cy="117" r="90" fill="#FFF" stroke="#43AEFA" stroke-width="17"></circle>
		  <circle id="js-sec-circle" class="alert-sec-circle" cx="117" cy="117" r="90" fill="transparent" stroke="#F4F1F1" stroke-width="18" transform="rotate(-90 117 117)"></circle>
		  <text class="alert-sec-unit" x="82" y="172" fill="#BDBDBD">secs</text>
	    </svg>
		<div id="js-sec-text" class="alert-sec-text"></div>

	<div class="alert-body">
		  <div id="js-alert-head" class="alert-head">请打开云闪付扫一扫...</div>
		  <div class="alert-concent">
		      <img src="<?php echo($hnapay['response']['qrCodeUrl']);?>" alt=""/>
		    </div>


		<a id="js-alert-btn" class="alert-btn" href="/">支付完毕</a>
  <a class="closeWindow" onclick="closePage()">关闭</a>
  </div>
  <div class="alert-footer clearfix"> <svg width="46px" height="42px" class="alert-footer-icon">
    <circle fill-rule="evenodd" clip-rule="evenodd" fill="#7B7B7B" stroke="#DEDFE0" stroke-width="2" stroke-miterlimit="10" cx="21.917" cy="21.25" r="17" />
    <path fill="#FFF" d="M22.907,27.83h-1.98l0.3-2.92c-0.37-0.22-0.61-0.63-0.61-1.1c0-0.71,0.58-1.29,1.3-1.29s1.3,0.58,1.3,1.29 c0,0.47-0.24,0.88-0.61,1.1L22.907,27.83z M18.327,17.51c0-1.98,1.61-3.59,3.59-3.59s3.59,1.61,3.59,3.59v2.59h-7.18V17.51z M27.687,20.1v-2.59c0-3.18-2.59-5.76-5.77-5.76s-5.76,2.58-5.76,5.76v2.59h-1.24v10.65h14V20.1H27.687z" />
    <circle fill-rule="evenodd" clip-rule="evenodd" fill="#FEFEFE" cx="35.417" cy="10.75" r="6.5" />
    <polygon fill="#7B7B7B" stroke="#7B7B7B" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" points="35.417,12.16 32.797,9.03 31.917,10.07 35.417,14.25 42.917,5.29 42.037,4.25 " />
    </svg>
    <div class="alert-footer-text">
      <p>secure</p>
      安全加密 </div>
  </div>
</div>
<script>
function alertSet ( e ) {
    document.getElementById ( "js-alert-box" ).style.display = "block";
    var t = 90,n = document.getElementById ( "js-sec-circle" );
    document.getElementById ( "js-sec-text" ).innerHTML = t,
        setInterval ( function () {
                if ( 0 < t ) {
                    t -= 1,
                    document.getElementById ( "js-sec-text" ).innerHTML = t;
                    n.style.strokeDashoffset = 735
                }
            } ,
            970 );
}

var viwport = document.documentElement; //html
var width = viwport.clientWidth;
var fontSize = width / 20;
viwport.style.fontSize = fontSize + "px";
</script>

<script>
    function closePage () {
        window.opener = null;
        window.open ( '' , '_self' , '' );
        window.close ();
        // window.location.href="about:blank";
        // window.close();
        if ( 'object' === typeof(WeixinJSBridge) ) {
            // 微信浏览器关闭
            WeixinJSBridge.call ( 'closeWindow' );
        }
    }
</script>
<script>alertSet ( '正在为您跳转...' );</script>
</body>
</html>

