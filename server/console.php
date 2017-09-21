#!/usr/bin/php

<?php
/**
 * Created by PhpStorm.
 * User: liuzhiming
 * Date: 16-10-20
 * Time: 下午2:18
 */
date_default_timezone_set("Asia/Shanghai");

define('SERVICE', true);
define('WEBPATH', __DIR__);
define('SWOOLE_SERVER', true);
define('CONFIG_PATH', __DIR__ . '/Config');
define('FRAMEWORK_PATH', __DIR__ .'/' );

set_include_path(get_include_path() . PATH_SEPARATOR . FRAMEWORK_PATH);

/*
|--------------------------------------------------------------------------
| 设置框架需要的常量
|--------------------------------------------------------------------------
*/
require WEBPATH."/Config/constants.php";

/*
|--------------------------------------------------------------------------
| 初始化配置文件
|--------------------------------------------------------------------------
*/
$configFile = CONFIG_PATH .'/' . ENV_NAME . '/config.php';
if ( is_file($configFile) ) {
    require $configFile;
} else {
    die("Config File $configFile is Not exists. " );
}


define("DEBUG", $config['debug']);
define("CENTER_HOST", $config['center_host']);

require_once FRAMEWORK_PATH. 'Framework/libs/lib_config.php';
Swoole::$php->config->setPath(CONFIG_PATH . '/' . ENV_NAME);//共有配置
Swoole::$php->config->setPath(CONFIG_PATH);//共有配置
Swoole\Loader::addNameSpace('App', __DIR__ . '/App');
Swoole\Loader::addNameSpace('Lib', __DIR__ . '/Lib');

$client = new Lib\Client(CENTER_HOST,CENTRE_PORT);
$ret = $client->call("Termlog::cleanLogs")->getResult(30);
var_dump($ret);
