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

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Initialize the database
 *
 * @category    Database
 * @author  EllisLab Dev Team
 * @link    https://codeigniter.com/user_guide/database/
 *
 * @param   string|string[] $params
 * @param   bool        $query_builder_override
 *              Determines if query builder should be used or not
 */
function &MY_DB($params = '', $query_builder_override = null)
{
    // Load the DB config file if a DSN string wasn't passed
    if (is_string($params) && strpos($params, '://') === false) {
        // Is the config file in the environment folder?
        if (! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/database.php')
            && ! file_exists($file_path = APPPATH.'config/database.php')) {
            show_error('The configuration file database.php does not exist.');
        }

        include($file_path);

        // Make packages contain database config files,
        // given that the controller instance already exists
        if (class_exists('CI_Controller', false)) {
            foreach (get_instance()->load->get_package_paths() as $path) {
                if ($path !== APPPATH) {
                    if (file_exists($file_path = $path.'config/'.ENVIRONMENT.'/database.php')) {
                        include($file_path);
                    } elseif (file_exists($file_path = $path.'config/database.php')) {
                        include($file_path);
                    }
                }
            }
        }

        if (! isset($db) or count($db) === 0) {
            show_error('No database connection settings were found in the database config file.');
        }

        if ($params !== '') {
            $active_group = $params;
        }

        if (! isset($active_group)) {
            show_error('You have not specified a database connection group via $active_group in your config/database.php file.');
        } elseif (! isset($db[$active_group])) {
            show_error('You have specified an invalid database connection group ('.$active_group.') in your config/database.php file.');
        }

        $params = $db[$active_group];
    } elseif (is_string($params)) {
        /**
         * Parse the URL from the DSN string
         * Database settings can be passed as discreet
         * parameters or as a data source name in the first
         * parameter. DSNs must have this prototype:
         * $dsn = 'driver://username:password@hostname/database';
         */
        if (($dsn = @parse_url($params)) === false) {
            show_error('Invalid DB Connection String');
        }

        $params = array(
            'dbdriver'  => $dsn['scheme'],
            'hostname'  => isset($dsn['host']) ? rawurldecode($dsn['host']) : '',
            'port'      => isset($dsn['port']) ? rawurldecode($dsn['port']) : '',
            'username'  => isset($dsn['user']) ? rawurldecode($dsn['user']) : '',
            'password'  => isset($dsn['pass']) ? rawurldecode($dsn['pass']) : '',
            'database'  => isset($dsn['path']) ? rawurldecode(substr($dsn['path'], 1)) : ''
        );

        // Were additional config items set?
        if (isset($dsn['query'])) {
            parse_str($dsn['query'], $extra);

            foreach ($extra as $key => $val) {
                if (is_string($val) && in_array(strtoupper($val), array('TRUE', 'FALSE', 'NULL'))) {
                    $val = var_export($val, true);
                }

                $params[$key] = $val;
            }
        }
    }

    // No DB specified yet? Beat them senseless...
    if (empty($params['dbdriver'])) {
        show_error('You have not selected a database type to connect to.');
    }

    // Load the DB classes. Note: Since the query builder class is optional
    // we need to dynamically create a class that extends proper parent class
    // based on whether we're using the query builder class or not.
    if ($query_builder_override !== null) {
        $query_builder = $query_builder_override;
    }
    // Backwards compatibility work-around for keeping the
    // $active_record config variable working. Should be
    // removed in v3.1
    elseif (! isset($query_builder) && isset($active_record)) {
        $query_builder = $active_record;
    }

    $DB = MY_DB_load_driver($params);
    $DB->initialize();
    return $DB;
}


/**
 * Create and load the class CI_DB
 */
function MY_DB_load_builder()
{
    require_once(BASEPATH.'database/DB_driver.php');

    if (! isset($query_builder) or $query_builder === true) {
        require_once(BASEPATH.'database/DB_query_builder.php');
        if (! class_exists('CI_DB', false)) {
            /**
             * CI_DB
             *
             * Acts as an alias for both CI_DB_driver and CI_DB_query_builder.
             *
             * @see CI_DB_query_builder
             * @see CI_DB_driver
             */
            class CI_DB extends CI_DB_query_builder
            {
            }
        }
    } elseif (! class_exists('CI_DB', false)) {
        /**
         * @ignore
         */
        class CI_DB extends CI_DB_driver
        {
        }
    }
}


/**
 * Load the CI_DB_xxxxx class in drivers
 *
 * @param string $dbdriver  the name of the driver folder
 * @param string $classext  the name of the class extension
 * @param bool $is_silent   if not show error
 * @return string  the name of the class
 */
function MY_DB_load_class($dbdriver, $classext = '_driver', $is_silent = false)
{
    $class = 'CI_DB_'.$dbdriver.$classext;
    if (class_exists($class, false)) {
        return $class;
    }

    $filename = $dbdriver.'/'.$dbdriver.$classext.'.php';
    $class_file = BASEPATH.'database/drivers/'.$filename;
    if (! file_exists($class_file)) {
        $class_file = APPPATH.'database/drivers/'.$filename;
    }

    if (file_exists($class_file)) {
        require_once($class_file);
        return $class;
    } elseif (empty($is_silent)) {
        show_error('Invalid DB class');
    }
}


/**
 * Load the CI_DB_xxxxx class in subdrivers
 *
 * @param string $dbdriver  the name of the driver folder
 * @param string $subdriver  the name of the subdriver folder
 * @param string $classext  the name of the class extension
 * @param bool $is_silent   if not show error
 * @return string/null  the name of the class
 */
function MY_DB_load_subclass($dbdriver, $subdriver, $classext = '_driver', $is_silent = false)
{
    $class = 'CI_DB_'.$dbdriver.'_'.$subdriver.$classext;
    if (class_exists($class, false)) {
        return $class;
    }

    $filename = $dbdriver.'/subdrivers/'.$dbdriver.'_'.$subdriver.$classext.'.php';
    $class_file = BASEPATH.'database/drivers/'.$filename;
    if (! file_exists($class_file)) {
        $class_file = APPPATH.'database/drivers/'.$filename;
    }

    if (file_exists($class_file)) {
        require_once($class_file);
        return $class;
    } elseif (empty($is_silent)) {
        show_error('Invalid DB class');
    }
}


/**
 * Load the db driver
 *
 * @param array $params
 * @param string $dbdriver
 * @return object
 */
function MY_DB_load_driver(array $params, $dbdriver = '')
{
    // Load the DB driver
    if (empty($dbdriver) and isset($params['dbdriver'])) {
        $dbdriver = $params['dbdriver'];
    }
    $driver = MY_DB_load_class($dbdriver, '_driver');

    // Instantiate the DB adapter
    $DB = new $driver($params);
    // Check for a subdriver
    if (! empty($DB->subdriver)) {
        $driver = MY_DB_load_subclass($DB->dbdriver, $DB->subdriver, '_driver');
        if ($driver) {
            $DB = new $driver($params);
        }
    }

    return $DB;
}
