<?php

defined('BASEPATH') || exit('No direct script access allowed');

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

$db['default'] = array(
    'dbdriver' => 'pdo',
    'subdriver' => 'mysql',
    'hostname' => '127.0.0.1',
    'port' => 3306,
    'username' => 'dba',
    'password' => 'pass',
    'database' => 'test',
    'dbprefix' => 't_',
    'db_debug' => (ENVIRONMENT !== 'production'),
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_general_ci',
);

$db['default_ro'] = array(
    'dbdriver' => 'pdo',
    'subdriver' => 'mysql',
    'hostname' => '127.0.0.1',
    'port' => 3306,
    'username' => 'dba',
    'password' => 'pass',
    'database' => 'test',
    'dbprefix' => 't_',
    'db_debug' => (ENVIRONMENT !== 'production'),
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_general_ci',
);
