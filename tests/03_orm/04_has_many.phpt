--TEST--
ORM/MY_Foreign

--FILE--
<?php
include_once dirname(__DIR__) . "/bootstrap.php";
$CI->load->model('test/school_model');
$CI->school_model->with_foreign('students');

$columns = array('id', 'city', 'name', 'is_removed');
$rows = $CI->school_model->all(4, 0, $columns);
echo 'Schools:' . "\n";
foreach ($rows as $row) {
    $students = $row['students'];
    unset($row['students']);
    println($row);
    foreach ($students as $stud) {
        unset($stud['school']); //避免无限递归
        println($stud, 1);
    }
}
?>

--EXPECTF--
Schools:
{"id":"1","city":"深圳市","name":"深港驾校","is_removed":"0"}
    {"id":"15","name":"齐兰英","gender":"F","school_id":"1","is_removed":"0"}
    {"id":"17","name":"凌金凤","gender":"F","school_id":"1","is_removed":"0"}
{"id":"2","city":"深圳市","name":"鹏安驾校","is_removed":"0"}
    {"id":"18","name":"田伟","gender":"M","school_id":"2","is_removed":"0"}
{"id":"3","city":"深圳市","name":"综安驾校","is_removed":"0"}
    {"id":"3","name":"查琴","gender":"F","school_id":"3","is_removed":"0"}
    {"id":"7","name":"翟佳","gender":"M","school_id":"3","is_removed":"0"}
    {"id":"11","name":"杜冬梅","gender":"F","school_id":"3","is_removed":"0"}
    {"id":"16","name":"明倩","gender":"F","school_id":"3","is_removed":"0"}
{"id":"4","city":"深圳市","name":"宝华驾校","is_removed":"0"}
    {"id":"1","name":"畅梅","gender":"F","school_id":"4","is_removed":"0"}
    {"id":"13","name":"车鹰","gender":"M","school_id":"4","is_removed":"0"}
    {"id":"20","name":"华娜","gender":"F","school_id":"4","is_removed":"0"}
