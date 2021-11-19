<?php

defined('BASEPATH') || exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Redis settings
| -------------------------------------------------------------------------
*/
$config = [
    'default' => [
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'password' => null,
        'database' => 0,
        'timeout' => 3600,
    ],
    'test' => [
        'hostname' => '127.0.0.1',
        'port' => 6379,
        'password' => null,
        'database' => 1,
        'timeout' => 3600,
    ],
];
