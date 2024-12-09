<?php
require_once './Sm2Ecc.php';
require_once './Sm2Point.php';
require_once './Sm3.php';
require_once './Sm2Asn1.php';
require_once './SimpleSm2.php';

use Lpilp\Splsm2\smecc\SPLSM2\SimpleSm2;
use Lpilp\Splsm2\smecc\SPLSM2\Sm2Asn1;
use const Lpilp\Splsm2\smecc\SPLSM2\C1C2C3;

$publicKey = 'f7701f1f3d2034622dd7354fb7d1fd74855f08692a42f11c95cdc47da644639038568818c43fd6666a362e5e75d74085515a80bbb426a15aebfc7516f75cadae';
$privateKey = '00855c2c3574b32d5cb139f5e959313d8cf267e7fa4e3cdadcd55514a6e4349feb';
$ssm2 = new SimpleSm2($privateKey,$publicKey);
$userId = '1234567812345678';

$data = 'hello,';
$data = str_repeat($data, 2);
$encrypt = $ssm2->encrypt($publicKey, $data, C1C2C3);
echo($encrypt.'<hr />');
$decrypt = $ssm2->decrypt($privateKey,$encrypt, C1C2C3,true);
echo($decrypt.'<hr />');
$sign = $ssm2->sign($data, $privateKey);
echo($sign.'<hr />');
$verify = $ssm2->verify($data, $publicKey, $sign);
echo($verify.'<hr />');