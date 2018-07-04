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
                                        <i class="fa fa-clone"></i>
                                        <strong class="font-bold">管理后台</strong>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="logo-element">
                            <i class="fa fa-clone"></i>
                        </div>
                    </li>
                    <?php if ($user['is_super']): ?>
                    <li>
                        <a href="#">
                            <i class="fa fa-users"></i>
                            <span class="nav-label">用户权限</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/home/graph/peity/">Peity</a>
                                <a class="J_menuItem" href="/home/graph/sparkline/">Sparkline</a>
                                <a class="J_menuItem" href="/home/graph/metrics/">图表组合</a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>
                    <li class="line dk"></li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">分类</span>
                    </li>
                    <?php foreach ($menus as $menu): ?>
                    <li>
                        <?php if ($menu['children']): ?>
                        <a href="#">
                            <i class="<?=$menu['icon']?>"></i>
                            <span class="nav-label"><?=$menu['title']?></span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <?php foreach ($menu['children'] as $submenu): ?>
                            <li>
                                <?php if ('#' === $submenu['url']): ?>
                                <a href="#"><?=$submenu['title']?> <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                    <?=implode("\n</li>\n<li>\n", $leaves[$submenu['id']])?>
                                    </li>
                                </ul>
                                <?php else:
                                    echo to_menu_link($submenu);
                                endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <a class="J_menuItem" href="<?=$menu['url']?>">
                            <i class="<?=$menu['icon']?>"></i>
                            <span class="nav-label"><?=$menu['title']?></span>
                            <?php if ($menu['corner']): echo to_corner_span($menu['corner']); endif; ?>
                        </a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
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
