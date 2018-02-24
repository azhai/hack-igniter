<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
*/
if (defined('DB_DEFAULT_GROUP')) {
    $active_group = constant('DB_DEFAULT_GROUP');
} else {
    $active_group = 'default';
}
$query_builder = true;


$db['default'] = [
    'dbdriver' => 'pdo',
    'subdriver' => 'mysql',
    'hostname' => '127.0.0.1',
    'port' => 3306,
    'username' => 'root',
    'password' => '',
    'database' => 'test',
    'dbprefix' => '',
    'db_debug' => (ENVIRONMENT !== 'production'),
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
];
