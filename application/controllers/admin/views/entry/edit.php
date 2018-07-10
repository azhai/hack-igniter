<?php $this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?=$the_row['name']?></h5>
                        <div class="ibox-tools">
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="i-checks" id="ids"></th>
                                        <th>城市</th>
                                        <th>驾校</th>
                                        <th>科目一</th>
                                        <th>科目二</th>
                                        <th>加入时间</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($page_rows as $row): ?>
                                    <tr>
                                        <td><input type="checkbox" class="i-checks" name="id[]" value="<?=$row['id']?>"></td>
                                        <td><span style="color:<?=$row['city_color']?>"><?=$row['city_name']?></span></td>
                                        <td><?=$row['name']?></td>
                                        <td>
                                            <span class="pie"><?=$row['s1_percent'].','.(100-$row['s1_percent'])?></span>
                                            <span><?=sprintf('%.2f', $row['s1_percent'])?>%</span>
                                        </td>
                                        <td>
                                            <span class="pie"><?=$row['s2_percent'].','.(100-$row['s2_percent'])?></span>
                                            <span><?=sprintf('%.2f', $row['s2_percent'])?>%</span>
                                        </td>
                                        <td><?=$row['created_at']?></td>
                                        <td><a href="#"><i class="fa fa-check text-navy"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('styles'); ?>
    <link href="<?= $static_url ?>/layui/css/layui.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
    <script src="<?= $static_url ?>/layui/layui.js"></script>
    <script src="<?= $static_url ?>/js/plugins/iCheck/icheck.min.js"></script>
    <script src="<?= $static_url ?>/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="<?= $static_url ?>/js/demo/peity-demo.js"></script>
    <script>
        $(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
            $('#ids').on('ifChecked', function(ev){
                $('.i-checks').iCheck('check');
            }).on('ifUnchecked', function(ev){
                $('.i-checks').iCheck('uncheck');
            });
        });
    </script>
<?php $this->blockEnd(); ?>
