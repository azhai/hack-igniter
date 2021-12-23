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
    ->setRules(array(
        '@PSR1' => true,
        '@PSR2' => true,
        //'@PHP54Migration' => true,
        // Each line of multi-line DocComments must have an asterisk [PSR-5] and must be aligned with the first one.
        //'align_multiline_comment' => true,
        // Each element of an array must be indented exactly once.
        //'array_indentation' => true,
        // Converts simple usages of `array_push($x, $y);` to `$x[] = $y;`.
        //'array_push' => true,
        // PHP arrays should be declared using the configured syntax.
        'array_syntax' => true,
        // Order the flags in `fopen` calls, `b` and `t` must be last.
        //'fopen_flag_order' => true,
        // The flags in `fopen` calls must omit `t`, and `b` must be omitted or included consistently.
        //'fopen_flags' => true,
        // Convert PHP4-style constructors to `__construct`.
        'no_php4_constructor' => true,
        // There must be no `sprintf` calls with only the first argument.
        //'no_useless_sprintf' => true,
        // Logical NOT operators (`!`) should have one trailing whitespace.
        'not_operator_with_successor_space' => true,
        // PHPUnit assertion method calls like `->assertSame(true, $foo)` should be written with dedicated method like `->assertTrue($foo)`.
        'php_unit_construct' => true,
        // Usages of `->setExpectedException*` methods MUST be replaced by `->expectException*` methods.
        //'php_unit_expectation' => true,
    ))
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
        "@PSR1",
        "@PSR2",
        "@PHP54Migration"
    ],
    "fixers": {
        "align_multiline_comment": true,
        "array_indentation": true,
        "array_syntax": true,
        "array_push": true,
        "fopen_flag_order": true,
        "fopen_flags": true,
        "no_php4_constructor": true,
        "no_useless_sprintf": true,
        "not_operator_with_successor_space": true,
        "php_unit_construct": true,
        "php_unit_expectation": true
    }
}
*/
