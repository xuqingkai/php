<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>RSA</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit/dist/css/uikit.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/uikit/dist/js/uikit.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit/dist/js/uikit-icons.min.js"></script>
</head>
<body>
	<form class="uk-container">
        <fieldset class="uk-fieldset">
            <legend class="uk-legend uk-text-center">RSA</legend>
            <ul class="uk-flex-center" uk-tab>
                <li class="uk-active"><a href="#">加密</a></li>
                <li><a href="#">解密</a></li>
                <li><a href="#">签名</a></li>
                <li><a href="#">验签</a></li>
            </ul>
            <div class="uk-switcher uk-margin">
                <div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">data</div>
                        <div class="uk-width-expand"><input class="uk-input" type="text"></div>
                    </div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">public</div>
                        <div class="uk-width-expand">
                            <textarea class="uk-textarea" rows="5"></textarea>
                         </div>
                    </div>
                </div>
                <div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">data</div>
                        <div class="uk-width-expand"><input class="uk-input" type="text"></div>
                    </div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">private</div>
                        <div class="uk-width-expand">
                            <textarea class="uk-textarea" rows="5"></textarea>
                         </div>
                    </div>
                </div>
                <div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">data</div>
                        <div class="uk-width-expand"><input class="uk-input" type="text"></div>
                    </div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">mode</div>
                        <div class="uk-width-expand">
                            <label><input class="uk-radio" type="radio" name="mode" checked> MD5</label>
                            <label><input class="uk-radio" type="radio" name="mode"> SHA1</label>
                            <label><input class="uk-radio" type="radio" name="mode"> SHA256</label>
                        </div>
                    </div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">private</div>
                        <div class="uk-width-expand">
                            <textarea class="uk-textarea" rows="5"></textarea>
                         </div>
                    </div>
                </div>
                <div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">data</div>
                        <div class="uk-width-expand"><input class="uk-input" type="text"></div>
                    </div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">mode</div>
                        <div class="uk-width-expand">
                            <label><input class="uk-radio" type="radio" name="mode" checked> MD5</label>
                            <label><input class="uk-radio" type="radio" name="mode"> SHA1</label>
                            <label><input class="uk-radio" type="radio" name="mode"> SHA256</label>
                        </div>
                    </div>
                    <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                        <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">public</div>
                        <div class="uk-width-expand">
                            <textarea class="uk-textarea" rows="5"></textarea>
                         </div>
                    </div>
                </div>
            </div>
            <div class="uk-text-center">
                 <button class="uk-button uk-button-primary uk-button-large" type="submit">Generate</button>
            </div>
        </fieldset>
    </form>
</body>
</html>
