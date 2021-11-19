<?php $this->extendTpl($theme_dir.'/layout.php'); ?>


<?php $this->blockStart('content'); ?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox row float-e-margins">
        <div class="ibox-title">
            <h5>驾校列表</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content table-responsive">

            <table class="table table-striped">
                <thead>
                <tr>
                    <th><input type="checkbox" class="i-checks" id="ids" value=""></th>
                    <th>城市</th>
                    <th>驾校</th>
                    <th>科目一</th>
                    <th>科目二</th>
                    <th>加入时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($page_rows as $row) { ?>
                    <tr class="table-row<?php echo $row['is_removed'] ? ' removed' : ''; ?>" data-id="<?php echo $row['id']; ?>">
                        <td><input type="checkbox" class="i-checks" name="id[]" value="<?php echo $row['id']; ?>"></td>
                        <td><span style="color:<?php echo $row['city_color']; ?>"><?php echo $row['city_name']; ?></span></td>
                        <td><a href="<?php echo $edit_url.'?id='.$row['id']; ?>"><?php echo $row['name']; ?></a></td>
                        <td>
                            <span class="pie"><?php echo $row['s1_percent'].','.(100 - $row['s1_percent']); ?></span>
                            <span><?php echo sprintf('%.2f', $row['s1_percent']); ?>%</span>
                        </td>
                        <td>
                            <span class="pie"><?php echo $row['s2_percent'].','.(100 - $row['s2_percent']); ?></span>
                            <span><?php echo sprintf('%.2f', $row['s2_percent']); ?>%</span>
                        </td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td class="operations">
                            <a class="btn btn-default btn-rounded">
                                <i class="fa fa-edit" title="编辑"></i>
                            </a>
                            <?php if ($row['is_removed']) { ?>
                                <a class="btn btn-default btn-rounded">
                                    <i class="fa fa-recycle" title="恢复"></i>
                                </a>
                            <?php } else { ?>
                                <a class="btn btn-default btn-rounded">
                                    <i class="fa fa-remove" title="删除"></i>
                                </a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr class="table-row" data-id="CHECKS">
                    <td colspan="<?php echo $spans['left']; ?>">
                        <a class="btn btn-default btn-rounded">
                            <i class="fa fa-remove" title="删除"> 删除 </i>
                        </a>
                        <a class="btn btn-default btn-rounded">
                            <i class="fa fa-recycle" title="恢复"> 恢复 </i>
                        </a>
                        <a class="btn btn-default btn-rounded">
                            <i class="fa fa-share-square" title="全部导出"> 全部导出 </i>
                        </a>
                    </td>
                    <td colspan="<?php echo $spans['right']; ?>">
                        <div id="pagination" class="pull-right"></div>
                    </td>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('styles'); ?>
<link href="<?php echo $static_url; ?>/layui/css/layui.css" rel="stylesheet">
<link href="<?php echo $static_url; ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
<script src="<?php echo $static_url; ?>/layui/layui.js"></script>
<script src="<?php echo $static_url; ?>/js/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo $static_url; ?>/js/plugins/peity/jquery.peity.min.js"></script>
<script src="<?php echo $static_url; ?>/js/demo/peity-demo.js"></script>
<script>
    $(function () {
        //复选框
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
        $('#ids').on('ifChecked', function (ev) {
            $('.i-checks').iCheck('check');
        }).on('ifUnchecked', function (ev) {
            $('.i-checks').iCheck('uncheck');
        });

        function getCheckedIds(checkbox) {
            var ids = '';
            $(checkbox).each(function () {
                var el = $(this);
                if (el.prop('checked')) {
                    ids += el.val() + ',';
                }
            });
            return ids;
        }

        layui.use(['layer', 'laypage'], function () {
            var layer = layui.layer;
            var laypage = layui.laypage;
            //分页
            laypage.render({
                theme: '#46b8da',
                prev: '<i class="fa fa-chevron-left"></i>',
                next: '<i class="fa fa-chevron-right"></i>',
                elem: 'pagination',
                curr: <?php echo $pager['page_no']; ?>,
                limit: <?php echo $pager['per_page']; ?>,
                count: <?php echo $pager['total_rows']; ?>,
                jump: function (obj, first) {
                    if (!first) {
                        var url = "<?php echo $pager['base_url']; ?>";
                        window.location.href = url + '&page_no=' + obj.curr;
                    }
                }
            });
            //编辑
            $('i.fa-edit').parent('a.btn').on('click', function () {
                var id = $(this).parents('tr.table-row').data('id');
                window.location.href = "<?php echo $edit_url.'?id='; ?>" + id;
            });
            //删除
            $('i.fa-remove').parent('a.btn').on('click', function () {
                var id = $(this).parents('tr.table-row').data('id');
                if ('CHECKS' == id) {
                    id = getCheckedIds('input.i-checks');
                }
                if ('' == id) {
                    layer.msg('请选择一行或多行！', {icon: 0});
                } else {
                    layer.confirm('确定要删除？', {icon: 0}, function (idx) {
                        window.location.href = "<?php echo $remove_url.'?id='; ?>" + id;
                        layer.close(idx);
                    });
                }
            });
            //恢复
            $('i.fa-recycle').parent('a.btn').on('click', function () {
                var id = $(this).parents('tr.table-row').data('id');
                if ('CHECKS' == id) {
                    id = getCheckedIds('input.i-checks');

                }
                if ('' == id) {
                    layer.msg('请选择一行或多行！', {icon: 0});
                } else {
                    layer.confirm('确定要恢复？', {icon: 0}, function (idx) {
                        window.location.href = "<?php echo $remove_url.'?recycle=1&id='; ?>" + id;
                        layer.close(idx);
                    });
                }
            });
        });
    });
</script>
<?php $this->blockEnd(); ?>
