<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 阿里巴巴矢量图标库</title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?= $static_url ?>/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="<?= $static_url ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="<?= $static_url ?>/css/animate.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="row">
    <div class="col-sm-12">
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>阿里巴巴矢量图标库简介</h5>

                        </div>
                        <div class="ibox-content">
                            <p>
                                Iconfont.cn是由阿里巴巴UX部门推出的矢量图标管理网站，也是国内首家推广Webfont形式图标的平台。网站涵盖了1000多个常用图标并还在持续更新中，Iconfont平台为用户提供在线图标搜索、图标分捡下载、在线储存、矢量格式转换、个人图标库管理及项目图标管理等基础功能。同时iconfont.cn平台作为矢量图标倡导者，积极在线分享矢量图标制作经验、前端应用说明，及应用中常见的一些问题。</p>
                            <p>官网地址：<a href="http://www.iconfont.cn/" target="_blank">http://www.iconfont.cn/</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>图标搜索</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="col-md-8 col-md-offset-2 m-t m-b">
                                <p class="text-center">
                                    <img src="<?= $static_url ?>/img/iconfont-logo.png">
                                </p>
                                <form action="http://www.iconfont.cn/search" target="_blank">
                                    <div class="input-group">
                                        <input type="text" name="q" placeholder="请输入图标名称，支持中英文"
                                               class="input-sm form-control"> <span class="input-group-btn">
                                                    <span class="input-group-btn">
                                                        <button type="submit"
                                                                class="btn btn-sm btn-primary"> 查找图标</button>
                                                    </span>
                                    </div>
                                </form>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>使用教程</h5>
                        </div>
                        <div class="ibox-content">
                            <ul class="list-group clear-list" style="margin-bottom:0;">
                                <li class="list-group-item fist-item">
                                    <p>
                                        <span class="label label-success">1</span> font-face声明字体
                                    </p>
                                    <pre>@font-face {font-family: 'iconfont';
    src: url('iconfont.eot'); /* IE9*/
    src: url('iconfont.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
    url('iconfont.woff') format('woff'), /* chrome、firefox */
    url('iconfont.ttf') format('truetype'), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
    url('iconfont.svg#iconfont') format('svg'); /* iOS 4.1- */
}</pre>
                                </li>
                                <li class="list-group-item">
                                    <p>
                                        <span class="label label-info">2</span> 定义使用iconfont的样式
                                    </p>
                                    <pre>.iconfont{
    font-family:"iconfont";
    font-size:16px;font-style:normal;
}</pre>
                                </li>
                                <li class="list-group-item">
                                    <p>
                                        <span class="label label-primary">3</span> 挑选相应图标并获取字体编码，应用于页面
                                    </p>
                                    <pre>&lt;i class="iconfont"&gt;&amp;#33&lt;/i&gt;</pre>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <div class="footer">
            <div class="pull-right">
                By：<a href="http://www.huangyaoxin.com" target="_blank">zihan's blog</a>
            </div>
            <div>
                <strong>Copyright</strong> H+ &copy; 2014
            </div>
        </div>
    </div>
</div>


<!-- 全局js -->
<script src="<?= $static_url ?>/js/jquery.min.js?v=2.1.4"></script>
<script src="<?= $static_url ?>/js/bootstrap.min.js?v=3.3.6"></script>


<!-- 自定义js -->
<script src="<?= $static_url ?>/js/content.js?v=1.0.0"></script>


</body>

</html>
