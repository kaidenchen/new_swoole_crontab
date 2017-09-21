<?php

date_default_timezone_set("Asia/Shanghai");

define('WEBPATH', dirname(dirname(__DIR__)));

define('APPPATH', WEBPATH. '/apps');

$env = get_cfg_var('env.name');
switch($env) {
    case "dev":
    case "test":
    case "yf":
        define('DEBUG', 'on');
        break;
    case "product":
    default:
        $env = "product";
        define('DEBUG', 'off');
}
define('ENV_NAME', $env);
define('WEBROOT', '');

require_once WEBPATH .'/framework/libs/lib_config.php';


Swoole::$php->config->setPath(APPPATH . '/configs');
Swoole::$php->config->setPath(APPPATH . '/configs/' . ENV_NAME);
$php->runMVC();
