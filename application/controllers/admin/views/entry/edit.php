<?php $this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="ibox row float-e-margins">
            <div class="ibox-title">
                <h5><?=$the_row['name']?></h5>
                <div class="ibox-tools">
                </div>
            </div>
            <div class="ibox-content">

                <form method="post" action="<?=$save_url?>" role="form" class="form-horizontal m-t">
                    <input type="hidden" name="id" value="<?=$the_row['id']?>">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">城市：</label>
                        <div class="row m-b col-sm-3">
                            <select class="form-control" name="prefix">
                            <?php foreach ($city_options as $key => $val):
                                $sel = ($key == $the_row['prefix']) ? ' selected="selected"' : '';
                                printf('<option value="%s"%s>%s</option>', $key, $sel, $val['name']);
                                echo "\n";
                            endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">驾校名称：</label>
                        <div class="row col-sm-6">
                            <input type="text" name="name" value="<?=$the_row['name']?>" class="form-control">
                            <span class="help-block m-b-none"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">科目一通过率：</label>
                        <div class="row input-group m-b col-sm-2">
                            <input type="text" name="s1_percent" value="<?=$the_row['s1_percent']?>" class="form-control">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">科目二通过率：</label>
                        <div class="row input-group m-b col-sm-2">
                            <input type="text" name="s2_percent" value="<?=$the_row['s2_percent']?>" class="form-control">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <div class="col-sm-12 col-sm-offset-3">
                            <button type="submit" class="btn btn-primary btn-w-m">保存</button>
                            <button type="reset" class="btn btn-white">取消</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('styles'); ?>
    <link href="<?= $static_url ?>/css/plugins/iCheck/custom.css" rel="stylesheet">
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
    <script src="<?= $static_url ?>/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="<?= $static_url ?>/js/demo/peity-demo.js"></script>
    <script>
    </script>
<?php $this->blockEnd(); ?>
