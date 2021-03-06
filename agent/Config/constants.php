<?php

/*
|--------------------------------------------------------------------------
| 设置环境
|--------------------------------------------------------------------------
*/
$env = get_cfg_var('env.name');
if ( !$env ) {
    $env = "product";
}
defined('ENV_NAME') OR define('ENV_NAME', $env);
defined('SERVICE') OR define('SERVICE', true);
defined('SWOOLE_SERVER') OR define('SWOOLE_SERVER', true);



/*
|--------------------------------------------------------------------------
| 单个worker同时执行任务数量
|--------------------------------------------------------------------------
*/
defined('ROBOT_MAX_PROCESS') OR define('ROBOT_MAX_PROCESS', 128);

/*
|--------------------------------------------------------------------------
| 中心端口号
|--------------------------------------------------------------------------
*/
defined('CENTER_PORT') OR define('CENTER_PORT', 8901);


/*
|--------------------------------------------------------------------------
| WORKER_NUM
|--------------------------------------------------------------------------
*/
defined('WORKER_NUM') OR define('WORKER_NUM', 2);

/*
|--------------------------------------------------------------------------
| task进程数量
|--------------------------------------------------------------------------
*/
defined('TASK_NUM') OR define('TASK_NUM', 2);

/*
|--------------------------------------------------------------------------
| Port 
|--------------------------------------------------------------------------
*/
defined('PORT') OR define('PORT', 8902);


