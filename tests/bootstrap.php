<?php
defined('TESTDIR') || define('TESTDIR', __DIR__);
defined('PROJPATH') || define('PROJPATH', dirname(TESTDIR) . '/');

$_SERVER['CI_ENV'] = 'development';
$_SERVER['CI_APP'] = 'tests';
include_once PROJPATH . 'index.php';
$loader = load_class('Loader', 'core');


function println($result, $indents = 0)
{
    if ($indents > 0) {
        echo str_repeat("    ", $indents);
    }
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
    echo "\n";
}
