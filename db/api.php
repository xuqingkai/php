<?php
$pdo_db['default']=array('dsn'=>'mysql:host=127.0.0.1;dbname=db;charset=utf8','user'=>'root','pwd'=>'123456','prefix'=>'xqk_');
$pdo_db['sqlite']=array('dsn'=>'sqlite:./database.sqlite3','prefix'=>'xqk_');

$path_info=$_SERVER['PATH_INFO'];
if(strpos($path_info,'/index.php')!== false && substr($path_info,strlen($path_info)-10)=='/index.php'){
    $path_info=substr($path_info,0,strlen($path_info)-10);
}
$arg=explode('/',urldecode($path_info)); 
if($arg[1]){
    $table=$arg[1];
    $db_key=array_keys($pdo_db)[0];
    if(strpos($table, '.')!==false){
        $db_key=substr($table,0,strpos($table, '.'));
        if(!array_key_exists($db_key,$pdo_db)){
            exit(json_encode(array('id'=>1,'code'=>'FAIL','message'=>'数据库key错误')));
        }
        $table=substr($table,strpos($table, '.')+1);
    }
    $db=$pdo_db[$db_key];
    $table=$db['prefix'].$table;
    $pdo_connection = new PDO($db['dsn'], $db['user'], $db['pwd']);

    $method=$_SERVER['REQUEST_METHOD'];
    $where=$arg[2]??'';
    if($method=='GET'){
        $sql='SELECT * FROM `'.$table.'`';
        if($where && strlen($where)>0){
            if(is_numeric(substr($where,0,1))){
                $id=intval($where);
                if($id<0){
                    $sql='DELETE FROM `'.$table.'` WHERE `id`='.$id;
                    exit_json(pdo_query($sql));
                }else{
                    $sql.=' WHERE `id`='.$id; 
                    //exit($sql);
                    exit_json(pdo_find($sql));
                }
            }else{
                if(substr($where,0,1)=='-'){
                    $sql='DELETE FROM `'.$table.'` WHERE '.substr($where,1);
                    exit_json(pdo_query($sql));
                }else if(substr($where,0,1)=='+'){
                    $page=$where;
                    $where=$arg[3];

                    $page=explode(',',substr($page,1).',10');
                    $page[0]=intval($page[0])-1;
                    if($page[0]<0){$page[0]=0;}
                    $page[1]=intval($page[1]);

                    if($where){$sql.=' WHERE '.$where;}
                    $sql.=' LIMIT '.($page[0]*$page[1]).','.($page[0]*$page[1]+$page[1]);
                    //exit($sql);
                    exit_json(pdo_select($sql));
                }else{
                    
                    if($where){$sql.=' WHERE '.$where;}
                    exit_json(pdo_select($sql));
                }
            }
        }else{
            exit_json(pdo_select($sql));
        }
    }
    if($method=='POST'){
        $sql='SELECT * FROM `'.$table.'`';
        if(strlen($where)>0){
            if(is_numeric($where)){
                $id=abs(intval($where));
                $data = pdo_find($sql.' WHERE `id`='.$id);
   
                if($id==0){
                    $fields='';
                    $values='';
                    foreach ($_POST as $key=>$val){
                        $fields.=',`'.$key.'`';
                        $values.=",'".addslashes($val)."'";
                    }
                    if(strlen($fields)>0){$fields=substr($fields,1);}
                    if(strlen($values)>0){$values=substr($values,1);}
                    $sql='INSERT INTO `'.$table.'` ('.$fields.') VALUES ('.$values.')';
                }else{
                    $sql='UPDATE `'.$table.'` SET';
                    foreach ($_POST as $key=>$val){
                        $sql.=" `".$key."`='".addslashes($val)."'";
                    }
                    $sql.=' WHERE `id`='.$id;
                }
                exit_json(pdo_query($sql));
            }else{
                $sql='UPDATE `'.$table.'` SET';
                foreach ($_POST as $key=>$val){
                    $sql.=" `".$key."`='".addslashes($val)."'";
                }
                $sql.=' WHERE '.$where;
                exit_json(pdo_query($sql));
            }
        }else{
            exit_json(pdo_select($sql));
        }
    }
}

function pdo_query($sql){
    global $pdo_connection;
    $pdoStatement=$pdo_connection->prepare($sql);
    $pdoStatement->execute(); 
    return true;
}
function pdo_find($sql){
    global $pdo_connection;
    $pdoStatement=$pdo_connection->prepare($sql);
    $pdoStatement->execute(); 
    $array=$pdoStatement->fetch(PDO::FETCH_ASSOC);
    return $array;
}
function pdo_select($sql){
    global $pdo_connection;
    $pdoStatement=$pdo_connection->prepare($sql);
    $pdoStatement->execute(); 
    $array=$pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    return $array;
}
function exit_json($data){
    if($data===true){
        $data=array('id'=>0,'code'=>'SUCCESS','message'=>'成功');
    }else if($data===false){
        $data=array('id'=>1,'code'=>'FAIL','message'=>'失败');
    }else{
        $data=array('id'=>0,'code'=>'SUCCESS','message'=>'成功','data'=>$data);
    }
    exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}

?>
