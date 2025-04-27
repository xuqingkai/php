<?php
error_reporting(E_ALL);//都显示

//$pdo=new PDO('mysql:host=127.0.0.1;dbname=xqk_db;charset=utf8mb4','root','123456');
$pdo=new PDO('sqlite:./sqlite3.db','123456');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{
    $prepare=$pdo->prepare('INSERT INTO `xqk_ip_log` (`ip`,`address`,`create_date`,`create_datetime`) VALUES (:ip,:address,:create_date,:create_datetime)');
    $prepare->execute(array(
        'ip'=>'127.0.0.1',
        'address'=>'男',
        'create_date'=>'1983-09-15000000',
        'create_datetime'=>'1983-09-15 00:00:00'
    ));
    $data=$pdo->lastInsertId();
    exit(json_encode($data));
}catch(PDOException $ex){
    exit($ex->getMessage());
}



try{
    $prepare=$pdo->prepare('SELECT * FROM `xqk_ip_log` WHERE `create_date`=:create_date');
    $prepare->execute(array(
        'create_date'=>'2024-07-01'
    ));
    $data=$prepare->fetchAll(PDO::FETCH_ASSOC);
    exit(json_encode($data));
}catch(PDOException $ex){
    exit($ex->getMessage());
}
