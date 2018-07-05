<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| 网站地址、静态文件网址和模版主题
|--------------------------------------------------------------------------
*/
defined('SITE_BASE_URL') or define('SITE_BASE_URL', 'http://hack.pony.car/');
defined('SITE_STATIC_URL') or define('SITE_STATIC_URL', '/static/');
defined('SITE_LOGIN_URL') or define('SITE_LOGIN_URL', '/admin/index/login/');
defined('SITE_THEME_NAME') or define('SITE_THEME_NAME', 'default');

/*
|--------------------------------------------------------------------------
| 数据库连接参数
|--------------------------------------------------------------------------
*/
defined('DB_DEFAULT_GROUP') or define('DB_DEFAULT_GROUP', 'default');
defined('DB_TABLE_PREFIX') or define('DB_TABLE_PREFIX', 't_');
defined('DB_RECONNECT_TIMEOUT') or define('DB_RECONNECT_TIMEOUT', 900);
