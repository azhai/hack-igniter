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
                                <a class="J_menuItem" href="/admin/user/index/">用户</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/admin/user/role/">角色</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/admin/user/menu/">菜单</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/admin/entry/index/">示例</a>
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
                        <form role="search" class="navbar-form-custom" method="post" action="/demo/search/results/">
                            <div class="form-group">
                                <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i>
                                <span class="label label-primary">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li class="m-t-xs">
                                    <div class="dropdown-messages-box">
                                        <a href="/demo/profile/index/" class="pull-left">
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
                                        <a href="/demo/profile/index/" class="pull-left">
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
                                        <a class="J_menuItem" href="/demo/mailbox/index/">
                                            <i class="fa fa-envelope"></i>
                                            <strong>查看所有消息</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-user"></i>
                                <strong> <?= $user['nickname'] ?></strong>
                            </a>
                            <div class="dropdown-menu dropdown-settings">
                                <div class="text-center col-sm-12" style="margin-bottom:20px">
                                    <img alt="image" src="<?= $static_url ?>/img/a1.jpg" class="img-circle m-t-xs img-responsive">
                                </div>
                                <div class="divider" style="clear:both"></div>
                                <div>
                                    <a class="J_menuItem" href="/demo/profile/index/">
                                        个人资料 <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                                <div class="divider"></div>
                                <div>
                                    <a class="J_menuItem" href="/demo/profile/index/">
                                        修改密码 <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                                <div class="divider"></div>
                                <div>
                                    <a href="<?= $logout_url ?>">
                                        退出 <i class="fa fa-angle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row J_mainContent" id="content-main">
                <iframe id="J_iframe" width="100%" height="100%" src="" frameborder="0" seamless></iframe>
            </div>
        </div>
        <!--右侧部分结束-->
    </div>
    <div style="text-align:center;"></div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
    <script src="<?= $static_url ?>/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?= $static_url ?>/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= $static_url ?>/js/plugins/layer/layer.min.js"></script>
    <script src="<?= $static_url ?>/js/plugins/pace/pace.min.js"></script>
    <script src="<?= $static_url ?>/js/js.cookie.min.js"></script>
    <script src="<?= $static_url ?>/js/hAdmin.js?v=4.1.0"></script>
    <script>
        $(function(){
            var url = Cookies ? Cookies.get('curr_url') : null;
            if (url = url || '<?= $default_url ?>') {
                var menu = $('a.J_menuItem[href="'+url+'"]');
                if (menu) {
                    menu.parents('li').addClass('active');
                    menu.parents('ul').addClass('in');
                }
                $("#J_iframe").attr('src', url);
            }
            //菜单点击 J_iframe
            $(".J_menuItem").on('click', function() {
                $('li.active').removeClass('active');
                $(this).parents('li').addClass('active');
                url = $(this).attr('href');
                $("#J_iframe").attr('src', url);
                if (Cookies) { //有效期2.4个小时
                    Cookies.set('curr_url', url, {expires: 0.1});
                }
                return false;
            });
        });
    </script>
<?php $this->blockEnd(); ?>
