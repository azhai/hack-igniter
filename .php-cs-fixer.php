<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.2.1|configurator
 * you can change this configuration by importing this file.
 */

if (function_exists('xdebug_disable')) {
  xdebug_disable();
}

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP54Migration' => true,
        '@PHPUnit54Migration:risky' => true,
        '@PSR1' => true,
        '@PSR2' => true,
        '@PhpCsFixer:risky' => true,
        // Converts simple usages of `array_push($x, $y);` to `$x[] = $y;`.
        'array_push' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude('adminer.php')
        ->exclude('application/logs')
        ->exclude('vendor')
        ->in(__DIR__)
    )
;

/*
{
    "version": "3.2.1",
    "risky": true,
    "fixerSets": [
        "@PHP54Migration",
        "@PHPUnit54Migration:risky",
        "@PSR1",
        "@PSR2",
        "@PhpCsFixer:risky"
    ],
    "fixers": {
        "array_push": true
    }
}
*/