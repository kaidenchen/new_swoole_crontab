<?php
$db['master'] = array(
    'type' => Swoole\Database::TYPE_MYSQLi,
    'host' => "172.168.6.24",
    'port' => 3306,
    'dbms' => 'mysql',
    'user' => "root",
    'passwd' => "redhat",
    'name' => "swoole_crontab",
    'charset' => "utf8",
    'setname' => true,
    'persistent' => false, //MySQL长连接
);
return $db;
