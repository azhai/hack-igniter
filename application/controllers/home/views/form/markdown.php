<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - Bootstrap3 Markdown编辑器</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="<?= $static_url ?>/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="<?= $static_url ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="<?= $static_url ?>/css/animate.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $static_url ?>/css/plugins/markdown/bootstrap-markdown.min.css" />
    <link href="<?= $static_url ?>/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>Markdown文本编辑器</h5>
                                <div class="ibox-tools">
                                    <a class="collapse-link">
                                        <i class="fa fa-chevron-up"></i>
                                    </a>
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="form_editors.html#">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-user">
                                        <li><a href="form_editors.html#">选项1</a>
                                        </li>
                                        <li><a href="form_editors.html#">选项2</a>
                                        </li>
                                    </ul>
                                    <a class="close-link">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="ibox-content">
                                <textarea name="content" data-provide="markdown" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox float-e-margins">

                            <div class="ibox-content">
                                <div data-provide="markdown-editable">
                                    <h2>Bootstrap-Markdown简介</h2>
                                    <p>Bootstrap-Markdown是一款优秀的markdown编辑器，提供了实用的API，利用插件进行扩展。<code>你可以单击这段文字试试</code>
                                    </p>
                                </div>
                                <p class="alert alert-success alert-dismissable m-t">
                                    更多示例请访问：<a href="http://toopay.github.io/bootstrap-markdown/">http://toopay.github.io/bootstrap-markdown/</a>
                                    <br>GitHub：
                                    <a href="https://github.com/toopay/bootstrap-markdown">https://github.com/toopay/bootstrap-markdown</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <!-- 全局js -->
    <script src="<?= $static_url ?>/js/jquery.min.js?v=2.1.4"></script>
    <script src="<?= $static_url ?>/js/bootstrap.min.js?v=3.3.6"></script>

    <!-- 自定义js -->
    <script src="<?= $static_url ?>/js/content.js?v=1.0.0"></script>

    <!-- simditor -->
    <script type="text/javascript" src="<?= $static_url ?>/js/plugins/markdown/markdown.js"></script>
    <script type="text/javascript" src="<?= $static_url ?>/js/plugins/markdown/to-markdown.js"></script>
    <script type="text/javascript" src="<?= $static_url ?>/js/plugins/markdown/bootstrap-markdown.js"></script>
    <script type="text/javascript" src="<?= $static_url ?>/js/plugins/markdown/bootstrap-markdown.zh.js"></script>




</body>

</html>
