--TEST--
helpers/my_helper.ends_with

--FILE--
<?php
include_once dirname(__DIR__) . "/bootstrap.php";
echo 'EndsWith:' . "\n";
println(function_exists('ends_with'));
println(ends_with('ends_with', 'ith'));
println(ends_with('ith', 'ends_with'));
println(ends_with('ends_with', ''));
?>

--EXPECTF--
EndsWith:
true
true
false
true
