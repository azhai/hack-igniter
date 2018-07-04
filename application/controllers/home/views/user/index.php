<?php $this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row col-sm-12 ibox ibox-content">
            <h4 class="m-t">后台用户列表</h4>
            <hr>
            <div class="jqGrid_wrapper">
                <table id="table_list"></table>
                <div id="pager_list"></div>
            </div>
        </div>
    </div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('styles'); ?>
    <link href="<?= $static_url ?>/css/plugins/jqgrid/ui.jqgrid.css?0820" rel="stylesheet">
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
    <!-- 全局js -->
    <script src="<?= $static_url ?>/js/jquery.min.js?v=2.1.4"></script>
    <script src="<?= $static_url ?>/js/bootstrap.min.js?v=3.3.6"></script>
    <!-- Peity -->
    <script src="<?= $static_url ?>/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- jqGrid -->
    <script src="<?= $static_url ?>/js/plugins/jqgrid/i18n/grid.locale-cn.js?0820"></script>
    <script src="<?= $static_url ?>/js/plugins/jqgrid/jquery.jqGrid.min.js?0820"></script>
    <!-- 自定义js -->
    <script src="<?= $static_url ?>/js/content.js?v=1.0.0"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function () {

            $.jgrid.defaults.styleUI = 'Bootstrap';
            // Configuration for jqGrid
            $("#table_list").jqGrid({
                url: "/home/user/user_data/",
                mtype: "post",
                datatype: "json",
                height: 525,
                autowidth: true,
                shrinkToFit: true,
                rowNum: 15,
                colNames: <?= json_encode($col_names) ?>,
                colModel: <?= json_encode($col_model) ?>,
                pager: "#pager_list",
                viewrecords: true,
                add: true,
                edit: true,
                addtext: 'Add',
                edittext: 'Edit',
                hidegrid: false
            });

            // Setup buttons
            $("#table_list").jqGrid('navGrid', '#pager_list', {
                edit: true,
                add: true,
                del: true,
                search: true
            }, {
                height: 200,
                reloadAfterSubmit: true
            });

            // Add responsive to jqGrid
            $(window).bind('resize', function () {
                var width = $('.jqGrid_wrapper').width();
                $('#table_list').setGridWidth(width);
            });
        });
    </script>
<?php $this->blockEnd(); ?>
