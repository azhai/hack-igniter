<?php $this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-sm-4">
            <h2>标题</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">主页</a>
                </li>
                <li>
                    <strong>包屑导航</strong>
                </li>
            </ol>
        </div>
        <div class="col-sm-8">
            <div class="title-action">
                <a href="empty_page.html" class="btn btn-primary">活动区域</a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold">这里是页面内容</h3>

                    <div class="error-desc">
                        您可以在这里添加栅格，参考首页及其他页面完成不同的布局
                        <br/><a href="#" class="btn btn-primary m-t">打开主页</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
    <script src="<?= $static_url ?>/js/jquery.min.js?v=2.1.4"></script>
    <script src="<?= $static_url ?>/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="<?= $static_url ?>/js/content.js"></script>
<?php $this->blockEnd(); ?>
