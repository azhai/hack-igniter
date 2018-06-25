--TEST--
ORM/MY_Foreign

--FILE--
<?php
include_once dirname(__DIR__) . "/bootstrap.php";
$CI->load->model('test/student_model');
$CI->student_model->with_foreign('school');

$columns = ['id', 'name', 'gender', 'school_id', 'is_removed'];
$rows = $CI->student_model->all(3, 0, $columns);
echo 'Students:' . "\n";
foreach ($rows as $row) {
    println($row);
}
?>

--EXPECTF--
Students:
{"id":"1","name":"畅梅","gender":"F","school_id":"4","is_removed":"0","school":{"id":"4","city":"深圳市","name":"宝华驾校","is_removed":"0"}}
{"id":"2","name":"计秀华","gender":"F","school_id":"6","is_removed":"0","school":{"id":"6","city":"深圳市","name":"通品驾校","is_removed":"0"}}
{"id":"3","name":"查琴","gender":"F","school_id":"3","is_removed":"0","school":{"id":"3","city":"深圳市","name":"综安驾校","is_removed":"0"}}
