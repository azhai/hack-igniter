#!/usr/bin/env php
<?php
$start = microtime(true);

$file_mask = '*/*.phpt';
if (count($_SERVER['argv']) > 1 && $_SERVER['argv'][1]) {
    $file_mask = $_SERVER['argv'][1];
    $_SERVER['argv'] = array_slice($_SERVER['argv'], 0, 1);
}
$tests = glob(__DIR__ . '/' . $file_mask, GLOB_NOSORT);
natsort($tests);

$pattern = "~^--TEST--\n(.*?)\n(?:--SKIPIF--\n(.*\n)?)?--FILE--\n(.*\n)?--EXPECTF--\n(.*)~s";
foreach ($tests as $filename) {
    ob_start();
    include $filename;
    if (!preg_match($pattern, str_replace("\r\n", "\n", ob_get_clean()), $matches)) {
        echo "wrong test in $filename\n";
    } elseif ($matches[2]) {
        echo "skipped $filename ($matches[1]): $matches[2]";
    } elseif (trim($matches[3], "\r\n") !== trim($matches[4], "\r\n")) {
        echo "failed $filename ($matches[1])\n";
        var_export($matches[3]);
        echo "\n";
        var_export($matches[4]);
        echo "\n\n";
    }
}

$mem_used = memory_get_peak_usage() / (1024 * 1024);
printf("%.3F s, %.2F MB\n", microtime(true) - $start, $mem_used);
