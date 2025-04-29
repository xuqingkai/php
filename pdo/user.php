<?php
header('Content-Type: application/json');
$config=include_once('./config.php');

$callback=$_GET['callback'] ?? 'callback';
$list=[];
$dsn="mysql:host=".$config['database']['hostname'].";dbname=".$config['database']['database'];
if($config['database']['type']=='sqlite'){
    $dsn="sqlite:".$config['database']['filepath'];
}
$pdo = new \PDO($dsn, $config['database']['username'], $config['database']['password']);

$query = $pdo->query('SELECT * FROM xqk_user');
$rows = $query->fetchALL(\PDO::FETCH_ASSOC);
foreach($rows as $row){
    $list[]=['title'=>$row['user_name'],'nick_name'=>$row['nick_name'],'contents'=>$row['contents'],'create_datetime'=>$row['create_datetime']];
}
exit($callback.'({
    "id": 0,
    "code": "success",
    "message": "获取成功",
    "data": '.json_encode($list, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).'
});');
?>
