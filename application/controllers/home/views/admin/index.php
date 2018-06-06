<?php
$this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
<div id="wrapper">
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <i class="fa fa-area-chart"></i>
                                        <strong class="font-bold">hAdmin</strong>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="logo-element">hAdmin
                        </div>
                    </li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">分类</span>
                    </li>
                    <li>
                        <a class="J_menuItem" href="/home/dashboard/index/">
                            <i class="fa fa-home"></i>
                            <span class="nav-label">主页</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa fa-bar-chart-o"></i>
                            <span class="nav-label">统计图表</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/home/graph/echarts/">百度ECharts</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/home/graph/flot/">Flot</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/home/graph/morris/">Morris.js</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/home/graph/rickshaw/">Rickshaw</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/home/graph/peity/">Peity</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/home/graph/sparkline/">Sparkline</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/home/graph/metrics/">图表组合</a>
                            </li>
                        </ul>
                    </li>
                    <li class="line dk"></li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">分类</span>
                    </li>
                    <li>
                        <a href="/home/mailbox/index/"><i class="fa fa-envelope"></i> <span class="nav-label">信箱 </span><span class="label label-warning pull-right">16</span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="/home/mailbox/index/">收件箱</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/mail/detail/">查看邮件</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/mail/compose/">写信</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-edit"></i> <span class="nav-label">表单</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="/home/form/basic/">基本表单</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/form/validate/">表单验证</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/form/advanced/">高级插件</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/form/wizard/">表单向导</a>
                            </li>
                            <li>
                                <a href="#">文件上传 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/form/webuploader/">百度WebUploader</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/form/file_upload/">DropzoneJS</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">编辑器 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/form/editors/">富文本编辑器</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/form/simditor/">simditor</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/form/markdown/">MarkDown编辑器</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/code/editor/">代码编辑器</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/layerdate/index/">日期选择器layerDate</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-desktop"></i> <span class="nav-label">页面</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="/home/contacts/index/">联系人</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/profile/index/">个人资料</a>
                            </li>
                            <li>
                                <a href="#">项目管理 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/projects/index/">项目</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/project/detail/">项目详情</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/teams/board/">团队管理</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/social/feed/">信息流</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/clients/index/">客户管理</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/file/manager/">文件管理器</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/calendar/index/">日历</a>
                            </li>
                            <li>
                                <a href="#">博客 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/blog/index/">文章列表</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/article/index/">文章详情</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/faq/index/">FAQ</a>
                            </li>
                            <li>
                                <a href="#">时间轴 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/timeline/index/">时间轴</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/timeline/v2/">时间轴v2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/pin/board/">标签墙</a>
                            </li>
                            <li>
                                <a href="#">单据 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/invoice/index/">单据</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/invoice/print/">单据打印</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/search/results/">搜索结果</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/forum/main/">论坛</a>
                            </li>
                            <li>
                                <a href="#">即时通讯 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/chat/view/">聊天窗口</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">登录注册相关 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a href="/home/login/index/" target="_blank">登录页面</a>
                                    </li>
                                    <li><a href="/home/login/v2/" target="_blank">登录页面v2</a>
                                    </li>
                                    <li><a href="/home/register/index/" target="_blank">注册页面</a>
                                    </li>
                                    <li><a href="/home/lockscreen/index/" target="_blank">登录超时</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/errors/404/">404页面</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/errors/500/">500页面</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/empty/page/">空白页</a>
                            </li>
                        </ul>
                    </li>
                    <li class="line dk"></li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">分类</span>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-flask"></i> <span class="nav-label">UI元素</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="/home/typography/index/">排版</a>
                            </li>
                            <li>
                                <a href="#">字体图标 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a class="J_menuItem" href="/home/fontawesome/index/">Font Awesome</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="/home/glyphicons/index/">Glyphicon</a>
                                    </li>
                                    <li>
                                        <a class="J_menuItem" href="/home/iconfont/index/">阿里巴巴矢量图标库</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">拖动排序 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/draggable/panels/">拖动面板</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/agile/board/">任务清单</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/buttons/index/">按钮</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/tabs/panels/">选项卡 &amp; 面板</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/notifications/index/">通知 &amp; 提示</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/badges/labels/">徽章，标签，进度条</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/home/grid/options/">栅格</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/plyr/index/">视频、音频</a>
                            </li>
                            <li>
                                <a href="#">弹框插件 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/layer/index/">Web弹层组件layer</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/modal/window/">模态窗口</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/sweetalert/index/">SweetAlert</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">树形视图 <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a class="J_menuItem" href="/home/jstree/index/">jsTree</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/tree/view/">Bootstrap Tree View</a>
                                    </li>
                                    <li><a class="J_menuItem" href="/home/nestable/list/">nestable</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="J_menuItem" href="/home/toastr/notifications/">Toastr通知</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/diff/index/">文本对比</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/spinners/index/">加载动画</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/widgets/index/">小部件</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-table"></i> <span class="nav-label">表格</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="/home/table/basic/">基本表格</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/table/data_tables/">DataTables</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/table/jqgrid/">jqGrid</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/table/foo_table/">Foo Tables</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/table/bootstrap/">Bootstrap Table
                                <span class="label label-danger pull-right">推荐</span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="line dk"></li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">分类</span>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-picture-o"></i> <span class="nav-label">相册</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="/home/basic/gallery/">基本图库</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/carousel/index/">图片切换</a>
                            </li>
                            <li><a class="J_menuItem" href="/home/blueimp/index/">Blueimp相册</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="J_menuItem" href="/home/css/animation/"><i class="fa fa-magic"></i> <span class="nav-label">CSS动画</span></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-cutlery"></i> <span class="nav-label">工具 </span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a class="J_menuItem" href="/home/form/builder/">表单构建器</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" method="post" action="/home/search/results/">
                            <div class="form-group">
                                <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li class="m-t-xs">
                                    <div class="dropdown-messages-box">
                                        <a href="/home/profile/index/" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?= $static_url ?>/img/a7.jpg">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right">46小时前</small>
                                            <strong>小四</strong> 是不是只有我死了,你们才不骂爵迹
                                            <br>
                                            <small class="text-muted">3天前 2014.11.8</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="/home/profile/index/" class="pull-left">
                                            <img alt="image" class="img-circle" src="<?= $static_url ?>/img/a4.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">25小时前</small>
                                            <strong>二愣子</strong> 呵呵
                                            <br>
                                            <small class="text-muted">昨天</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a class="J_menuItem" href="/home/mailbox/index/">
                                            <i class="fa fa-envelope"></i> <strong> 查看所有消息</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="/home/mailbox/index/">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> 您有16条未读消息
                                            <span class="pull-right text-muted small">4分钟前</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="/home/profile/index/">
                                        <div>
                                            <i class="fa fa-qq fa-fw"></i> 3条新回复
                                            <span class="pull-right text-muted small">12分钟钱</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a class="J_menuItem" href="/home/notifications/index/">
                                            <strong>查看所有 </strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row J_mainContent" id="content-main">
                <iframe id="J_iframe" width="100%" height="100%" src="/home/entry/index/" frameborder="0" seamless></iframe>
            </div>
        </div>
        <!--右侧部分结束-->
    </div>
    <div style="text-align:center;"></div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
    <script src="<?= $static_url ?>/js/jquery.min.js?v=2.1.4"></script>
    <script src="<?= $static_url ?>/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="<?= $static_url ?>/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?= $static_url ?>/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $static_url ?>/js/plugins/layer/layer.min.js"></script>
    <script src="<?= $static_url ?>/js/hAdmin.js?v=4.1.0"></script>
    <script src="<?= $static_url ?>/js/index.js"></script>
    <script src="<?= $static_url ?>/js/plugins/pace/pace.min.js"></script>
<?php $this->blockEnd(); ?>
