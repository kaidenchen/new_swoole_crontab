<?php
return [
    'site_name'=> '基础平台任务系统',
    'logo_url'=> '/static/smartadmin/img/logo-ttyun.jpg',
    'login_url' => WEBROOT . '/Page/index',
    'logout_url' => WEBROOT . '/page/logout/',
    'home_url' => WEBROOT . '/crontab/index/',
    //忽视权限验证
    'RBAC_EXCLUDE'=>[
        "password"=>[
            "modifyPassword"=>true
        ]
    ]
];
