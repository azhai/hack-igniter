<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - jqGird</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?php echo $static_url; ?>/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="<?php echo $static_url; ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <!-- jqgrid-->
    <link href="<?php echo $static_url; ?>/css/plugins/jqgrid/ui.jqgrid.css?0820" rel="stylesheet">

    <link href="<?php echo $static_url; ?>/css/animate.css" rel="stylesheet">
    <link href="<?php echo $static_url; ?>/css/style.css?v=4.1.0" rel="stylesheet">

    <style>
        /* Additional style to fix warning dialog position */

        #alertmod_table_list_2 {
            top: 900px !important;
        }
    </style>

</head>

<body class="gray-bg">
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox ">
                <div class="ibox-title">
                    <h5>jQuery Grid Plugin – jqGrid</h5>
                </div>
                <div class="ibox-content">
                    <p>
                        <strong>jqGrid</strong> 是一个用来显示网格数据的jQuery插件，文档比较全面，附带中文版本。访问<a target="_blank"
                                                                                        href="http://www.trirand.com/blog/">
                            官网</a>
                    </p>
                    <p>jqGrid的主要特点为：</p>
                    <ol>
                        <li>基于jquery UI主题，开发者可以根据客户要求更换不同的主题</li>
                        <li>兼容目前所有流行的web浏览器</li>
                        <li>Ajax分页，可以控制每页显示的记录数</li>
                        <li>支持XML，JSON，数组形式的数据源</li>
                        <li>提供丰富的选项配置及方法事件接口</li>
                        <li>支持表格排序，支持拖动列、隐藏列</li>
                        <li>支持滚动加载数据</li>
                        <li>支持实时编辑保存数据内容</li>
                        <li>支持子表格及树形表格</li>
                        <li>支持多语言</li>
                        <li>免费</li>
                    </ol>
                    <hr>
                    <h4>基本示例</h4>

                    <div class="jqGrid_wrapper">
                        <table id="table_list_1"></table>
                        <div id="pager_list_1"></div>
                    </div>
                    <p>&nbsp;</p>
                    <h4 class="m-t">高级用法</h4>


                    <div class="jqGrid_wrapper">
                        <table id="table_list_2"></table>
                        <div id="pager_list_2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 全局js -->
<script src="<?php echo $static_url; ?>/js/jquery.min.js?v=2.1.4"></script>
<script src="<?php echo $static_url; ?>/js/bootstrap.min.js?v=3.3.6"></script>


<!-- Peity -->
<script src="<?php echo $static_url; ?>/js/plugins/peity/jquery.peity.min.js"></script>

<!-- jqGrid -->
<script src="<?php echo $static_url; ?>/js/plugins/jqgrid/i18n/grid.locale-cn.js?0820"></script>
<script src="<?php echo $static_url; ?>/js/plugins/jqgrid/jquery.jqGrid.min.js?0820"></script>

<!-- 自定义js -->
<script src="<?php echo $static_url; ?>/js/content.js?v=1.0.0"></script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function () {

        $.jgrid.defaults.styleUI = 'Bootstrap';
        // Examle data for jqGrid
        var mydata = [
            {
                id: "1",
                invdate: "2010-05-24",
                name: "test",
                note: "note",
                tax: "10.00",
                total: "2111.00"
            },
            {
                id: "2",
                invdate: "2010-05-25",
                name: "test2",
                note: "note2",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "3",
                invdate: "2007-09-01",
                name: "test3",
                note: "note3",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "4",
                invdate: "2007-10-04",
                name: "test",
                note: "note",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "5",
                invdate: "2007-10-05",
                name: "test2",
                note: "note2",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "6",
                invdate: "2007-09-06",
                name: "test3",
                note: "note3",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "7",
                invdate: "2007-10-04",
                name: "test",
                note: "note",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "8",
                invdate: "2007-10-03",
                name: "test2",
                note: "note2",
                amount: "300.00",
                tax: "21.00",
                total: "320.00"
            },
            {
                id: "9",
                invdate: "2007-09-01",
                name: "test3",
                note: "note3",
                amount: "400.00",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "11",
                invdate: "2007-10-01",
                name: "test",
                note: "note",
                amount: "200.00",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "12",
                invdate: "2007-10-02",
                name: "test2",
                note: "note2",
                amount: "300.00",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "13",
                invdate: "2007-09-01",
                name: "test3",
                note: "note3",
                amount: "400.00",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "14",
                invdate: "2007-10-04",
                name: "test",
                note: "note",
                amount: "200.00",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "15",
                invdate: "2007-10-05",
                name: "test2",
                note: "note2",
                amount: "300.00",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "16",
                invdate: "2007-09-06",
                name: "test3",
                note: "note3",
                amount: "400.00",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "17",
                invdate: "2007-10-04",
                name: "test",
                note: "note",
                amount: "200.00",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "18",
                invdate: "2007-10-03",
                name: "test2",
                note: "note2",
                amount: "300.00",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "19",
                invdate: "2007-09-01",
                name: "test3",
                note: "note3",
                amount: "400.00",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "21",
                invdate: "2007-10-01",
                name: "test",
                note: "note",
                amount: "200.00",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "22",
                invdate: "2007-10-02",
                name: "test2",
                note: "note2",
                amount: "300.00",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "23",
                invdate: "2007-09-01",
                name: "test3",
                note: "note3",
                amount: "400.00",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "24",
                invdate: "2007-10-04",
                name: "test",
                note: "note",
                amount: "200.00",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "25",
                invdate: "2007-10-05",
                name: "test2",
                note: "note2",
                amount: "300.00",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "26",
                invdate: "2007-09-06",
                name: "test3",
                note: "note3",
                amount: "400.00",
                tax: "30.00",
                total: "430.00"
            },
            {
                id: "27",
                invdate: "2007-10-04",
                name: "test",
                note: "note",
                amount: "200.00",
                tax: "10.00",
                total: "210.00"
            },
            {
                id: "28",
                invdate: "2007-10-03",
                name: "test2",
                note: "note2",
                amount: "300.00",
                tax: "20.00",
                total: "320.00"
            },
            {
                id: "29",
                invdate: "2007-09-01",
                name: "test3",
                note: "note3",
                amount: "400.00",
                tax: "30.00",
                total: "430.00"
            }
        ];

        // Configuration for jqGrid Example 1
        $("#table_list_1").jqGrid({
            data: mydata,
            datatype: "local",
            height: 250,
            autowidth: true,
            shrinkToFit: true,
            rowNum: 14,
            rowList: [10, 20, 30],
            colNames: ['序号', '日期', '客户', '金额', '运费', '总额', '备注'],
            colModel: [
                {
                    name: 'id',
                    index: 'id',
                    width: 60,
                    sorttype: "int"
                },
                {
                    name: 'invdate',
                    index: 'invdate',
                    width: 90,
                    sorttype: "date",
                    formatter: "date"
                },
                {
                    name: 'name',
                    index: 'name',
                    width: 100
                },
                {
                    name: 'amount',
                    index: 'amount',
                    width: 80,
                    align: "right",
                    sorttype: "float",
                    formatter: "number"
                },
                {
                    name: 'tax',
                    index: 'tax',
                    width: 80,
                    align: "right",
                    sorttype: "float"
                },
                {
                    name: 'total',
                    index: 'total',
                    width: 80,
                    align: "right",
                    sorttype: "float"
                },
                {
                    name: 'note',
                    index: 'note',
                    width: 150,
                    sortable: false
                }
            ],
            pager: "#pager_list_1",
            viewrecords: true,
            caption: "jqGrid 示例1",
            hidegrid: false
        });

        // Configuration for jqGrid Example 2
        $("#table_list_2").jqGrid({
            data: mydata,
            datatype: "local",
            height: 450,
            autowidth: true,
            shrinkToFit: true,
            rowNum: 20,
            rowList: [10, 20, 30],
            colNames: ['序号', '日期', '客户', '金额', '运费', '总额', '备注'],
            colModel: [
                {
                    name: 'id',
                    index: 'id',
                    editable: true,
                    width: 60,
                    sorttype: "int",
                    search: true
                },
                {
                    name: 'invdate',
                    index: 'invdate',
                    editable: true,
                    width: 90,
                    sorttype: "date",
                    formatter: "date"
                },
                {
                    name: 'name',
                    index: 'name',
                    editable: true,
                    width: 100
                },
                {
                    name: 'amount',
                    index: 'amount',
                    editable: true,
                    width: 80,
                    align: "right",
                    sorttype: "float",
                    formatter: "number"
                },
                {
                    name: 'tax',
                    index: 'tax',
                    editable: true,
                    width: 80,
                    align: "right",
                    sorttype: "float"
                },
                {
                    name: 'total',
                    index: 'total',
                    editable: true,
                    width: 80,
                    align: "right",
                    sorttype: "float"
                },
                {
                    name: 'note',
                    index: 'note',
                    editable: true,
                    width: 100,
                    sortable: false
                }
            ],
            pager: "#pager_list_2",
            viewrecords: true,
            caption: "jqGrid 示例2",
            add: true,
            edit: true,
            addtext: 'Add',
            edittext: 'Edit',
            hidegrid: false
        });

        // Add selection
        $("#table_list_2").setSelection(4, true);


        // Setup buttons
        $("#table_list_2").jqGrid('navGrid', '#pager_list_2', {
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
            $('#table_list_1').setGridWidth(width);
            $('#table_list_2').setGridWidth(width);
        });
    });
</script>


</body>

</html>
