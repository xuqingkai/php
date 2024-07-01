<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="utf-8">
	<title>DES</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3/dist/css/uikit.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/uikit@3/dist/js/uikit.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/uikit@3/dist/js/uikit-icons.min.js"></script>
</head>
<body>
	<form class="uk-container">
        <fieldset class="uk-fieldset">
            <legend class="uk-legend uk-text-center">DES</legend>
            
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">key</div>
                <div class="uk-width-expand"><input class="uk-input" type="text"></div>
            </div>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">padding</div>
                <div class="uk-width-expand">
                    <label><input class="uk-radio" type="radio" name="padding" checked> OPENSSL_RAW_DATA=1</label>
                    <label><input class="uk-radio" type="radio" name="padding"> OPENSSL_ZERO_PADDING=2</label>
                    <label><input class="uk-radio" type="radio" name="padding"> OPENSSL_NO_PADDING=3</label>
                    <label><input class="uk-radio" type="radio" name="padding"> pkcs5padding=5</label>
                </div>
            </div>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">iv</div>
                <div class="uk-width-auto"><input class="uk-input" type="text"></div>
            </div>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">mode</div>
                <div class="uk-width-expand">
                    <label><input class="uk-radio" type="radio" name="mode" checked> DES-ECB</label>
                    <label><input class="uk-radio" type="radio" name="mode"> CBC</label>
                    <label><input class="uk-radio" type="radio" name="mode"> CTR</label>
                    <label><input class="uk-radio" type="radio" name="mode"> OFB</label>
                    <label><input class="uk-radio" type="radio" name="mode"> CFB</label>
                    
                </div>
            </div>
            <div class="uk-grid-collapse uk-flex-middle" uk-grid>
                <div class="uk-width-1-4 uk-width-1-6@s uk-padding-small uk-text-right">data</div>
                <div class="uk-width-expand">
                    <textarea class="uk-textarea" rows="5"></textarea>
                 </div>
            </div>
            <div class="uk-text-center">
                 <button class="uk-button uk-button-primary uk-button-large" type="submit">Encrypt</button>
                 <button class="uk-button uk-button-primary uk-button-large" type="submit">Decrypt</button>
            </div>
        </fieldset>
    </form>
</body>
</html>
