<?php
/**
 * hack-igniter.
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @author  Ryan Liu (azhai)
 *
 * @see    http://azhai.surge.sh/
 *
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

/**
 * CodeIgniter Version.
 *
 * @var string
 */
const CI_VERSION = '3.1.11';

/*
 * ------------------------------------------------------
 *  Load the framework constants
 * ------------------------------------------------------
 */
if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/constants.php')) {
    require_once APPPATH.'config/'.ENVIRONMENT.'/constants.php';
}

if (file_exists(APPPATH.'config/constants.php')) {
    require_once APPPATH.'config/constants.php';
}

/*
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
require_once BASEPATH.'core/Common.php';

/*
 * ------------------------------------------------------
 *  Define a custom error handler so we can log PHP errors
 * ------------------------------------------------------
 */
set_error_handler('_error_handler');
set_exception_handler('_exception_handler');
register_shutdown_function('_shutdown_handler');

/*
 * ------------------------------------------------------
 *  Instantiate the config class
 * ------------------------------------------------------
 *
 * Note: It is important that Config is loaded first as
 * most other classes depend on it either directly or by
 * depending on another class that uses it.
 *
 */
$CFG = &load_class('Config', 'core');

/*
 * ------------------------------------------------------
 * Important charset-related stuff
 * ------------------------------------------------------
 *
 * Configure mbstring and/or iconv if they are enabled
 * and set MB_ENABLED and ICONV_ENABLED constants, so
 * that we don't repeatedly do extension_loaded() or
 * function_exists() calls.
 *
 * Note: UTF-8 class depends on this. It used to be done
 * in it's constructor, but it's _not_ class-specific.
 *
 */
define('MB_ENABLED', true);
define('ICONV_ENABLED', true);
define('UTF8_ENABLED', true);
$charset = strtoupper(config_item('charset'));
ini_set('default_charset', $charset);
if (is_php('5.6')) {
    ini_set('php.internal_encoding', $charset);
} else {
    @ini_set('mbstring.internal_encoding', $charset);
}
mb_substitute_character('none');

/*
 * ------------------------------------------------------
 *  Load compatibility features
 * ------------------------------------------------------
 */

require_once BASEPATH.'core/compat/mbstring.php';

require_once BASEPATH.'core/compat/hash.php';

require_once BASEPATH.'core/compat/password.php';

require_once BASEPATH.'core/compat/standard.php';

/*
 * ------------------------------------------------------
 *  Instantiate the routing class and set the routing
 * ------------------------------------------------------
 */
$RTR = &load_class('Router', 'core', isset($routing) ? $routing : null);

/*
 * ------------------------------------------------------
 *  Load the app controller and local controller
 * ------------------------------------------------------
 *
 */
// Load the base controller class
require_once BASEPATH.'core/Controller.php';

/**
 * Reference to the CI_Controller method.
 *
 * Returns current CI instance object
 *
 * @return object of CI_Controller
 */
function &get_instance()
{
    return CI_Controller::get_instance();
}

/**
 * Create object object of a subclass of CI_Controller.
 *
 * @param mixed $dir
 * @param mixed $class
 * @param mixed $base_class
 */
function create_instance($dir, $class, $base_class = false)
{
    if ($base_class && file_exists(APPPATH.'core/'.$base_class.'.php')) {
        require_once APPPATH.'core/'.$base_class.'.php';
    }
    $file_path = APPPATH.trim($dir, '/').'/'.$class.'.php';
    if (file_exists($file_path)) {
        require_once $file_path;

        return new $class();
    }
}

$class = $RTR->class ? ucfirst($RTR->class) : 'Index';
$prefix = $CFG->config['subclass_prefix'];
$CI = create_instance('services/'.$RTR->directory, $class, $prefix.'Service');
if (null === $CI) {
    $base_class = $prefix.'Controller';
    $CI = create_instance('controllers/'.$RTR->directory, $class, $base_class);
    if (null === $CI) {
        $CI = new $base_class();
    }
}
