
<html>
<head>
  <title>在线支付</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<script src="//cdn.iocdn.cc/npm/uikit/dist/js/uikit.min.js"></script>
	<script src="//cdn.iocdn.cc/npm/uikit/dist/js/uikit-icons.min.js"></script>
	<script src="//cdn.iocdn.cc/npm/jquery@3/dist/jquery.min.js"></script>
</head>
<body>
<form class="uk-form-horizontal uk-margin-large" method="post" action="pay.php">
    <div class="uk-margin">
        <label class="uk-form-label uk-text-default uk-text-right@s" for="pay_amount">充值金额</label>
        <div class="uk-form-controls">
            <input class="uk-input" id="pay_amount" name="pay_amount" type="text" placeholder="最低1.00元" value="10.00">
        </div>
    </div>
    <div class="uk-margin">
        <label class="uk-form-label uk-text-default uk-text-right@s" for="pay_type">支付方式</label>
        <div class="uk-form-controls">
            <select class="uk-select" id="pay_type" name="pay_type">
                <option value="wx.qr">微信扫码</option>
                <option value="ali.h5">支付宝H5</option>
            </select>
        </div>
    </div>
    <div class="uk-margin">
        <button class="uk-button uk-button-secondary uk-width-1-1">点击提交</button>
    </div>
</form>
</body>
</html>
