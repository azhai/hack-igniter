<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 任务清单</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?= $static_url ?>/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="<?= $static_url ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="<?= $static_url ?>/css/animate.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="wrapper wrapper-content  animated fadeInRight">
    <div class="row">
        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-content">
                    <h3>任务列表</h3>
                    <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p>

                    <div class="input-group">
                        <input type="text" placeholder="添加新任务" class="input input-sm form-control">
                        <span class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-plus"></i> 添加</button>
                                </span>
                    </div>

                    <ul class="sortable-list connectList agile-list">
                        <li class="warning-element">
                            加强过程管理，及时统计教育经费使用情况，做到底码清楚，
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.01
                            </div>
                        </li>
                        <li class="success-element">
                            支持财会人员的继续培训工作。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>
                        <li class="info-element">
                            协同教导处搞好助学金、减免教科书费的工作。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.09.10
                            </div>
                        </li>
                        <li class="danger-element">
                            要求会计、出纳人员严格执行财务制度，遵守岗位职责，按时上报各种资料。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-primary">确定</a>
                                <i class="fa fa-clock-o"></i> 2015.06.10
                            </div>
                        </li>
                        <li class="warning-element">
                            做好职工公费医疗工作，按时发放门诊费。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.09
                            </div>
                        </li>
                        <li class="warning-element">
                            有计划地把课本复习三至五遍。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-primary">确定</a>
                                <i class="fa fa-clock-o"></i> 2015.08.04
                            </div>
                        </li>
                        <li class="success-element">
                            看一本高质量的高中语法书
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>
                        <li class="info-element">
                            选择一份较好的英语报纸，通过阅读提高英语学习效果。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.09.10
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-content">
                    <h3>进行中</h3>
                    <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p>
                    <ul class="sortable-list connectList agile-list">
                        <li class="success-element">
                            全面、较深入地掌握我们“产品”的功能、特色和优势并做到应用自如。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.01
                            </div>
                        </li>
                        <li class="success-element">
                            根据自己以前所了解的和从其他途径搜索到的信息，录入客户资料150家。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>
                        <li class="warning-element">
                            锁定有意向客户20家。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.09.10
                            </div>
                        </li>
                        <li class="warning-element">
                            力争完成销售指标。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.09
                            </div>
                        </li>
                        <li class="info-element">
                            在总结和摸索中前进。
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-primary">确定</a>
                                <i class="fa fa-clock-o"></i> 2015.08.04
                            </div>
                        </li>
                        <li class="success-element">
                            不断学习行业知识、产品知识，为客户带来实用介绍内容
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>
                        <li class="danger-element">
                            先友后单：与客户发展良好友谊，转换销售员角色，处处为客户着想
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.11.04
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="ibox">
                <div class="ibox-content">
                    <h3>已完成</h3>
                    <p class="small"><i class="fa fa-hand-o-up"></i> 在列表之间拖动任务面板</p>
                    <ul class="sortable-list connectList agile-list">
                        <li class="info-element">
                            制定工作日程表
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.09.10
                            </div>
                        </li>
                        <li class="warning-element">
                            每天坚持打40个有效电话，挖掘潜在客户
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.09
                            </div>
                        </li>
                        <li class="warning-element">
                            拜访客户之前要对该客户做全面的了解(客户的潜在需求、职位、权限以及个人性格和爱好)
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标签</a>
                                <i class="fa fa-clock-o"></i> 2015.09.09
                            </div>
                        </li>
                        <li class="warning-element">
                            提高自己电话营销技巧，灵活专业地与客户进行电话交流
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-primary">确定</a>
                                <i class="fa fa-clock-o"></i> 2015.08.04
                            </div>
                        </li>
                        <li class="success-element">
                            通过电话销售过程中了解各盛市的设备仪器使用、采购情况及相关重要追踪人
                            <div class="agile-detail">
                                <a href="#" class="pull-right btn btn-xs btn-white">标记</a>
                                <i class="fa fa-clock-o"></i> 2015.05.12
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>

    </div>


</div>

<!-- 全局js -->
<script src="<?= $static_url ?>/js/jquery.min.js?v=2.1.4"></script>
<script src="<?= $static_url ?>/js/bootstrap.min.js?v=3.3.6"></script>
<script src="<?= $static_url ?>/js/jquery-ui-1.10.4.min.js"></script>

<!-- 自定义js -->
<script src="<?= $static_url ?>/js/content.js?v=1.0.0"></script>

<script>
    $(document).ready(function () {
        $(".sortable-list").sortable({
            connectWith: ".connectList"
        }).disableSelection();

    });
</script>


</body>

</html>
