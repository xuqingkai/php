<?php
require 'vendor/autoload.php';

use WebSocket\Client;

$client = new Client("ws://127.0.0.1:12345");
$client->send('{"type":"message","from_id":"server_user","to_id":"client_user","content":"111222333"}');
echo $client->receive(); // 输出服务器返回的消息
$client->close();
?>

