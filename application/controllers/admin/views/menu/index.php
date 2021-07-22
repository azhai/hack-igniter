<?php $this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-sm-4">
            <div id="nestable-menu">
                <button type="button" data-action="expand-all" class="btn btn-white btn-sm">展开所有</button>
                <button type="button" data-action="collapse-all" class="btn btn-white btn-sm">收起所有</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>自定义主题</h5>
                </div>
                <div class="ibox-content">

                    <p class="m-b-lg">
                        每个列表可以自定义标准的CSS样式。每个单元响应所以你可以给它添加其他元素来改善功能列表。
                    </p>

                    <div class="dd" id="nestable">
                        <ol class="dd-list">
                            <li class="dd-item" data-id="1">
                                <div class="dd-handle">
                                    <span class="label label-info"><i class="fa fa-users"></i></span> 群组
                                </div>
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="2">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 12:00 </span>
                                            <span class="label label-info"><i class="fa fa-cog"></i></span> 设置
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="3">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 11:00 </span>
                                            <span class="label label-info"><i class="fa fa-bolt"></i></span> 筛选
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="4">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 11:00 </span>
                                            <span class="label label-info"><i class="fa fa-laptop"></i></span> 电脑
                                        </div>
                                    </li>
                                </ol>
                            </li>

                            <li class="dd-item" data-id="5">
                                <div class="dd-handle">
                                    <span class="label label-warning"><i class="fa fa-users"></i></span> 用户
                                </div>
                                <ol class="dd-list">
                                    <li class="dd-item" data-id="6">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 15:00 </span>
                                            <span class="label label-warning"><i class="fa fa-users"></i></span> 列用户表
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="7">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 16:00 </span>
                                            <span class="label label-warning"><i class="fa fa-bomb"></i></span> 炸弹
                                        </div>
                                    </li>
                                    <li class="dd-item" data-id="8">
                                        <div class="dd-handle">
                                            <span class="pull-right"> 21:00 </span>
                                            <span class="label label-warning"><i class="fa fa-child"></i></span> 子元素
                                        </div>
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </div>
                    <div class="m-t-md">
                        <h5>数据：</h5>
                    </div>
                    <textarea id="nestable-output" class="form-control"></textarea>


                </div>

            </div>
        </div>
    </div>
</div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
<!-- Nestable List -->
<script src="<?= $static_url ?>/js/plugins/nestable/jquery.nestable.min.js"></script>

<script>
    $(document).ready(function () {

        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            } else {
                output.val('浏览器不支持');
            }
        };
        // activate Nestable
        $('#nestable').nestable({
            group: 1
        }).on('change', updateOutput);
        // output initial serialised data
        updateOutput($('#nestable').data('output', $('#nestable-output')));

        $('#nestable-menu').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
    });
</script>
<?php $this->blockEnd(); ?>
