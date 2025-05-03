<?php
require '../vendor/autoload.php';

use WebSocket\Client;
$from_id=$_GET['id']??$_GET['from']??$_GET['fromid']??$_GET['from_id']??'server_user';
$to_id=$_GET['to']??$_GET['toid']??$_GET['to_id']??'client_user';
$client = new Client("ws://127.0.0.1:12345");
$client->send('{"type":"message","from_id":"'.$from_id.'","to_id":"'.$to_id.'","content":"111222333"}');
echo $client->receive(); // 输出服务器返回的消息
$client->close();
?>

