--TEST--
helpers/my_helper.convert_string

--FILE--
<?php
include_once dirname(__DIR__) . "/bootstrap.php";
echo 'ToUTF8:' . "\n";
$word = '中文English混合test';
println(function_exists('convert_string'));
println(convert_string($word));
println(convert_string(iconv('UTF-8', 'GBK', $word)));
?>

--EXPECTF--
ToUTF8:
true
"中文English混合test"
"中文English混合test"
