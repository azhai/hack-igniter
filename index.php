<?php
/**
 * hack-igniter
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @package hack-igniter
 * @author  Ryan Liu (azhai)
 * @link    http://azhai.surge.sh/
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

/*
 * --------------------------------------------------------------------
 * 判断环境，设置错误日志
 * --------------------------------------------------------------------
 */
date_default_timezone_set('Asia/Shanghai');
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');
switch (ENVIRONMENT) {
    case 'development':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 0);
        $err_level = E_ALL & ~E_NOTICE & ~E_STRICT;
        if (version_compare(PHP_VERSION, '5.3', '>=')) {
            $err_level = $err_level & ~E_DEPRECATED & ~E_USER_NOTICE & ~E_USER_DEPRECATED;
        }
        error_reporting($err_level);
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', true, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}


/*
 * --------------------------------------------------------------------
 * 设置项目根目录，结尾不带目录分隔符
 * --------------------------------------------------------------------
 */
defined('PROJPATH') or define('PROJPATH', __DIR__ . '/');
defined('VENDPATH') or define('VENDPATH', PROJPATH . 'vendor/');
$application_folder = PROJPATH . 'application';
$view_folder = VENDPATH . 'codeigniter/framework/application/views';
$system_path = VENDPATH . 'codeigniter/framework/system/';


/*
 * --------------------------------------------------------------------
 * 设置CodeIgniter的系统目录
 * --------------------------------------------------------------------
 */
// Set the current directory correctly for CLI requests
if (defined('STDIN')) {
    chdir(dirname(__FILE__));
}
// Now that we know the path, set the main path constants
define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);
define('VIEWPATH', $view_folder . DIRECTORY_SEPARATOR);
// The name of THIS file
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
// Path to the system directory
define('BASEPATH', $system_path);
// Path to the front controller (this file) directory
define('FCPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
// Name of the "system" directory
define('SYSDIR', basename(BASEPATH));

/*
 * --------------------------------------------------------------------
 * 启动CodeIgniter，或它的简化版HackIgniter（用于Yar的Server端）
 * --------------------------------------------------------------------
 */
require_once APPPATH . 'helpers/my_helper.php';
$req_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
if (PHP_SAPI === 'cli' && isset($_SERVER['CI_APP']) && 'tests' === $_SERVER['CI_APP']) {
    //phpt测试
    require_once APPPATH . 'core/HackIgniter.php';
    $CI = get_instance();
} elseif (PHP_SAPI !== 'cli' && strpos($req_uri, '/rpc/') !== false) {
    //yar过程调用
    require_once APPPATH . 'core/HackIgniter.php';
    $CI = get_instance();
    $service = new Yar_Server($CI);
    $service->handle();
} else {
    require_once BASEPATH . 'core/CodeIgniter.php';
}
