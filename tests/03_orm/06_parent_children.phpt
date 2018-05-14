--TEST--
ORM/MY_Foreign

--FILE--
<?php
include_once dirname(__DIR__) . "/bootstrap.php";
$CI->load->model('test/subject_model');
$CI->subject_model->with_foreign('children');

$columns = ['id', 'parent_id', 'name', 'max_score', 'pass_score', 'is_removed'];
$CI->subject_model->where('parent_id', 0);
$rows = $CI->subject_model->all(false, 0, $columns);
echo 'Subjects:' . "\n";
foreach ($rows as $row) {
    $children = $row['children'];
    unset($row['children']);
    println($row);
    foreach ($children as $child) {
        println($child, 1);
    }
}
?>

--EXPECTF--
Subjects:
{"id":"1","parent_id":"0","name":"科目一","max_score":"100","pass_score":"90","is_removed":"0"}
{"id":"2","parent_id":"0","name":"科目二","max_score":"100","pass_score":"90","is_removed":"0"}
    {"id":"5","parent_id":"2","name":"科目二（手动）","max_score":"100","pass_score":"90","is_removed":"0"}
    {"id":"6","parent_id":"2","name":"科目二（自动）","max_score":"100","pass_score":"90","is_removed":"0"}
{"id":"3","parent_id":"0","name":"科目三","max_score":"100","pass_score":"90","is_removed":"0"}
    {"id":"7","parent_id":"3","name":"科目三（手动）","max_score":"100","pass_score":"90","is_removed":"0"}
    {"id":"8","parent_id":"3","name":"科目三（自动）","max_score":"100","pass_score":"90","is_removed":"0"}
{"id":"4","parent_id":"0","name":"科目四","max_score":"100","pass_score":"90","is_removed":"0"}
