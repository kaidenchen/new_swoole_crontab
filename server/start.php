#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: liuzhiming
 * Date: 16-8-18
 * Time: 下午2:30
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


Swoole\Network\Server::setPidFile(getRunPath() . '/logs/center.pid');
Swoole\Network\Server::start(function () {
    $logger = new Swoole\Log\FileLog(['file' => getRunPath() . '/logs/center.log']);
    $AppSvr = new Lib\CenterServer;
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
        'package_body_offset' => \Swoole\Protocol\SOAServer::HEADER_SIZE,
        'package_length_offset' => 0,
    );
    //重定向PHP错误日志到logs目录
    ini_set('error_log', getRunPath() . '/logs/php_errors.log');

    \Lib\LoadTasks::init();//载入任务表
    \Lib\Donkeyid::init();//初始化donkeyid对象
    \Lib\Tasks::init();//创建task表
    \Lib\Robot::init();//创建任务处理服务表
    
    $server = Swoole\Network\Server::autoCreate(CENTER_HOST, CENTRE_PORT);
    $server->setProtocol($AppSvr);
    $server->setProcessName("CenterServer");
    $server->on("PipeMessage",array($AppSvr, 'onPipeMessage'));
    $server->run($setting);
});

function getRunPath()
{
    $path = Phar::running(false);
    if (empty($path)) return __DIR__;
    else return dirname($path)."/../crontab_log";
}

