<?php
return [
    'database'=>[
        // 数据库类型
        'type'=> 'mysql',
        // SQLite
        'filepath'=>'sqlite3.db',
        // 服务器地址
        'hostname'=>'127.0.0.1',
        // 数据库名
        'database'=>'xqk_db',
        // 用户名
        'username'=>'root',
        // 密码
        'password'=>'123456',
        // 端口
        'hostport'=>'3306',
        // 数据库连接参数
        'params'=>[
            //\PDO::MYSQL_ATTR_SSL_CA => "/etc/ssl/certs/ca-certificates.crt",//Debian/Ubuntu
            //\PDO::MYSQL_ATTR_SSL_CA => "/etc/pki/tls/certs/ca-bundle.crt",//RedHat/Fedora/CentOS
        ],
        // 数据库编码默认采用utf8
        'charset'=>'utf8',
        // 数据库表前缀
        'prefix'=>'xqk_',
    ]
];
