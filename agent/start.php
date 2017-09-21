#!/usr/bin/php

<?php
/**
 * @file agent.php
 * @brief  modified by chenxuewei
 * @author xuewei.chen@ttyun.com
 * @version 
 * @date 2016-12-24
 */
date_default_timezone_set("Asia/Shanghai");

/*
|--------------------------------------------------------------------------
| 设置工作路径
|--------------------------------------------------------------------------
*/
defined('WEBPATH') OR define('WEBPATH', __DIR__);
defined('CONFIG_PATH') OR define('CONFIG_PATH', __DIR__ . '/Config');


/*
|--------------------------------------------------------------------------
| 设置框架需要的常量
|--------------------------------------------------------------------------
*/
require WEBPATH."/Config/constants.php";

/*
|--------------------------------------------------------------------------
| 自动加载
|--------------------------------------------------------------------------
*/
require WEBPATH."/Lib/Loader.php";
Lib\Loader::addNameSpace('Lib', __DIR__."/Lib");
Lib\Loader::addNameSpace('App', __DIR__."/App");
spl_autoload_register('\\Lib\\Loader::autoload');

/*
|--------------------------------------------------------------------------
| 初始化配置文件
|--------------------------------------------------------------------------
*/
$configFile = CONFIG_PATH .'/config_'.ENV_NAME.'.php';
if ( is_file($configFile) ) {
    require $configFile;
} else {
    die("Config File $configFile is Not exists. " );
}

define("DEBUG", $config['debug']);
define("CENTER_HOST", $config['center_host']);


/*
|--------------------------------------------------------------------------
| Run AgentServer, Default prot:8902
|--------------------------------------------------------------------------
*/
Lib\Server::setPidFile(getRunPath() . '/logs/agent'.PORT.'.pid');
Lib\Server::start(function () {
    $logger = new Lib\FileLog(['file' => getRunPath() . '/logs/agent'.PORT.'.log']);
    $AppSvr = new Lib\AgentServer;
    $AppSvr->setLogger($logger);

    $setting = array(
        'worker_num' => WORKER_NUM,
        'task_worker_num'=>TASK_NUM,
        'max_request' => 1000,
        'dispatch_mode' => 3,
        'log_file' => getRunPath() . '/logs/swoole.log',
        'open_length_check' => 1,
        'package_max_length' => $AppSvr->packet_maxlen,
        'package_length_type' => 'N',
        'package_body_offset' => Lib\SOAServer::HEADER_SIZE,
        'package_length_offset' => 0,
    );
    //重定向PHP错误日志到logs目录
    ini_set('error_log', getRunPath() . '/logs/php_errors.log');

    $listenHost = Lib\Util::listenHost();
    
    Lib\Process::init();//载入任务处理表

    $server = Lib\Server::autoCreate($listenHost, PORT);
    $server->setProtocol($AppSvr);
    $server->setProcessName("AgentServer");
    $server->run($setting);
});

function getRunPath()
{
    $path = Phar::running(false);
    if (empty($path)) return __DIR__;
    else return dirname($path)."/../agent_log";
}


