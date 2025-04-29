<?php
error_reporting(E_ALL);//都显示

//$pdo=new PDO('mysql:host=127.0.0.1;dbname=xqk_db;charset=utf8mb4','root','123456');
$pdo=new PDO('sqlite:./sqlite3.db','123456');
function pdo_query$sql,$param=false,$where=''){
    global $pdo;
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES utf8");
    $error=false; $data=false;
    try{
        if($param){//参数模式
            if(substr(strtoUpper($sql),0,7)=='SELECT '){
                $where='';
                foreach($param as $key=>$val){ $where.=(strlen($where)>0?' AND ':'').$key.'=:'.$key; }
                if($where){ $sql.=(strpos($sql, ' WHERE ')!==false?' AND ':' WHERE ').$where; }
            }elseif(substr(strtoUpper($sql),0,11)=='INSERT INTO'){
                $fields='';$values='';
                foreach($param as $key=>$val){
                    $fields.=strlen($fields)>0?','.$key:$key;
                    $values.=strlen($values)>0?',:'.$key:':'.$key;
                }
                if(strpos($sql, ' ) VALUES ( ')===false){ $sql.=' ('.$fields.') VALUES ('.$values.')'; }
            }elseif(substr(strtoUpper($sql),0,7)=='UPDATE '){
                $set='';
                foreach($param as $key=>$val){ $set.=(strlen($set)>0?',':'').$key.'=:'.$key; }
                if(strpos($sql, ' SET ')===false){ $sql.=' SET '.$set; }
                $sql.=(strpos($sql, ' WHERE ')!==false?' AND ':' WHERE ').$where;
            }elseif(substr(strtoUpper($sql),0,7)=='DELETE '){
                $where='';
                foreach($param as $key=>$val){ $where.=(strlen($where)>0?' AND ':'').$key.'=:'.$key; }
                if($where){ $sql.=(strpos($sql, ' WHERE ')!==false?' AND ':' WHERE ').$where; }
            }
            $prepare=$pdo->prepare($sql);
            $prepare->execute($param);
            if(substr(strtoUpper($sql),0,7)=='SELECT '){//返回查询数据
                $data=$prepare->fetchAll(PDO::FETCH_ASSOC);
            }else{
                if(substr(strtoUpper($sql),0,11)=='INSERT INTO'){//返回最后插入ID
                    $data=$pdo->lastInsertId();
                }else{//返回受影响行数
                    $data=$prepare->rowCount();
                }
            }
        }else{//纯语句模式
            
            if(substr(strtoUpper($sql),0,7)=='SELECT '){//返回查询数据
                $query=$pdo->query($sql);
                $data=$query->fetchAll(PDO::FETCH_ASSOC);
            }else{
                $data=$pdo->exec($sql);
                if(substr(strtoUpper($sql),0,11)=='INSERT INTO'){//返回最后插入ID
                    $data=$pdo->lastInsertId();
                }
            }   
        }
        
    }catch(PDOException $ex){
        $error=$ex->getMessage();
    }
    return [$error,$data];
}
list($error,$dat)=pdo_query("",array());
