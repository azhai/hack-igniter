--TEST--
ORM/MY_Foreign

--FILE--
<?php
include_once dirname(__DIR__) . "/bootstrap.php";
$CI->load->model('test/student_model');
$CI->student_model->with_foreign('subjects');

$columns = ['id', 'name', 'gender', 'school_id', 'is_removed'];
$row = $CI->student_model->one(['gender' => 'F'], 'array', $columns);
echo 'Student and subjects:' . "\n";
$subjects = $row['subjects'];
unset($row['subjects']);
println($row);
foreach ($subjects as $subj) {
    println($subj, 1);
}
?>

--EXPECTF--
Student and subjects:
{"id":"1","name":"畅梅","gender":"F","school_id":"4","is_removed":"0"}
    {"id":"1","parent_id":"0","name":"科目一","max_score":"100","pass_score":"90","is_removed":"0","middle":{"id":"9","subject_id":"1","student_id":"1","term":"2018年第7周","score":"92","is_removed":"0"}}
    {"id":"6","parent_id":"2","name":"科目二（自动）","max_score":"100","pass_score":"90","is_removed":"0","middle":{"id":"24","subject_id":"6","student_id":"1","term":"2018年第10周","score":"90","is_removed":"0"}}
    {"id":"8","parent_id":"3","name":"科目三（自动）","max_score":"100","pass_score":"90","is_removed":"0","middle":{"id":"29","subject_id":"8","student_id":"1","term":"2018年第14周","score":"90","is_removed":"0"}}
    {"id":"4","parent_id":"0","name":"科目四","max_score":"100","pass_score":"90","is_removed":"0","middle":{"id":"33","subject_id":"4","student_id":"1","term":"2018年第15周","score":"100","is_removed":"0"}}
