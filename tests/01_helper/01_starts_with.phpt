--TEST--
helpers/my_helper.starts_with

--FILE--
<?php
include_once dirname(__DIR__) . "/bootstrap.php";
echo 'StartsWith:' . "\n";
println(function_exists('starts_with'));
println(starts_with('starts_with', 'start'));
println(starts_with('start', 'starts_with'));
println(starts_with('starts_with', ''));
?>

--EXPECTF--
StartsWith:
true
true
false
true
