<?php $this->extendTpl($theme_dir.'/layout.php'); ?>


<?php $this->blockStart('content'); ?>
<div class="row border-bottom white-bg page-heading">
    <div class="col-sm-8">
        <h2>后台用户列表</h2>
    </div>
    <div class="col-sm-4 title-action">
        <a href="" class="btn btn-primary">导出Excel</a>
    </div>
</div>

<div class="row wrapper-content animated fadeInRight">
    <div class="row col-sm-12 ibox ibox-content">
        <div class="jqGrid_wrapper">
            <table id="table_list"></table>
            <div id="pager_list"></div>
        </div>
    </div>
</div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('styles'); ?>
<link href="<?php echo $static_url; ?>/css/plugins/jqgrid/ui.jqgrid.css?0820" rel="stylesheet">
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
<!-- jqGrid -->
<script src="<?php echo $static_url; ?>/js/plugins/jqgrid/i18n/grid.locale-cn.js?0820"></script>
<script src="<?php echo $static_url; ?>/js/plugins/jqgrid/jquery.jqGrid.min.js?0820"></script>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function () {

        $.jgrid.defaults.styleUI = 'Bootstrap';
        // Configuration for jqGrid
        $("#table_list").jqGrid({
            url: "/admin/user/user_data/",
            mtype: "post",
            datatype: "json",
            height: 525,
            autowidth: true,
            shrinkToFit: true,
            rowNum: 15,
            colNames: <?php echo json_encode($col_names); ?>,
            colModel: <?php echo json_encode($col_model); ?>,
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
