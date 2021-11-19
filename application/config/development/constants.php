<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 网站地址、静态文件网址和模版主题
|--------------------------------------------------------------------------
*/
defined('SITE_BASE_URL') || define('SITE_BASE_URL', '/');
defined('SITE_STATIC_URL') || define('SITE_STATIC_URL', '/static/');
defined('SITE_LOGIN_URL') || define('SITE_LOGIN_URL', '/admin/index/login/');
defined('SITE_THEME_NAME') || define('SITE_THEME_NAME', 'default');

/*
|--------------------------------------------------------------------------
| 数据库连接参数
|--------------------------------------------------------------------------
*/
defined('DB_DEFAULT_GROUP') || define('DB_DEFAULT_GROUP', 'default');
defined('DB_TABLE_PREFIX') || define('DB_TABLE_PREFIX', 't_');
defined('DB_RECONNECT_TIMEOUT') || define('DB_RECONNECT_TIMEOUT', 900);
