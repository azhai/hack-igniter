<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 树形视图</title>

    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="<?= $static_url ?>/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="<?= $static_url ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="<?= $static_url ?>/css/plugins/jsTree/style.min.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/animate.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content  animated fadeInRight">

        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">

                        <strong>jsTree</strong>是一个基于jQuery的Tree控件。支持 XML，JSON，Html三种数据源。提供创建，重命名，移动，删除，拖放节点操作。可以自己自定义创建，删除，嵌套，重命名，选择节点的规则。在这些操作上可以添加多种监听事件。
                        <br>更多信息请访问： <a href="http://www.jstree.com/" target="_blank">http://www.jstree.com/</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>基本示例 <small>使用Font Awesome图标</small></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div id="jstree1">
                            <ul>
                                <li class="jstree-open">H+主题
                                    <ul>
                                        <li>css
                                            <ul>
                                                <li data-jstree='{"type":"css"}'>animate.css</li>
                                                <li data-jstree='{"type":"css"}'>bootstrap.css</li>
                                                <li data-jstree='{"type":"css"}'>style.css</li>
                                            </ul>
                                        </li>
                                        <li>email-templates
                                            <ul>
                                                <li data-jstree='{"type":"html"}'>action.html</li>
                                                <li data-jstree='{"type":"html"}'>alert.html</li>
                                                <li data-jstree='{"type":"html"}'>billing.html</li>
                                            </ul>
                                        </li>
                                        <li>fonts
                                            <ul>
                                                <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.eot</li>
                                                <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.svg</li>
                                                <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.ttf</li>
                                                <li data-jstree='{"type":"svg"}'>glyphicons-halflings-regular.woff</li>
                                            </ul>
                                        </li>
                                        <li class="jstree-open">img
                                            <ul>
                                                <li data-jstree='{"type":"img"}'>profile_small.jpg</li>
                                                <li data-jstree='{"type":"img"}'>angular_logo.png</li>
                                                <li class="text-navy" data-jstree='{"type":"img"}'>html_logo.png</li>
                                                <li class="text-navy" data-jstree='{"type":"img"}'>mvc_logo.png</li>
                                            </ul>
                                        </li>
                                        <li class="jstree-open">js
                                            <ul>
                                                <li data-jstree='{"type":"js"}'>hplus.js</li>
                                                <li data-jstree='{"type":"js"}'>bootstrap.js</li>
                                                <li data-jstree='{"type":"js"}'>jquery-2.1.1.js</li>
                                                <li data-jstree='{"type":"js"}'>jquery-ui.custom.min.js</li>
                                                <li class="text-navy" data-jstree='{"type":"js"}'>jquery-ui-1.10.4.min.js</li>
                                            </ul>
                                        </li>
                                        <li data-jstree='{"type":"html"}'>affix.html</li>
                                        <li data-jstree='{"type":"html"}'>dashboard.html</li>
                                        <li data-jstree='{"type":"html"}'>buttons.html</li>
                                        <li data-jstree='{"type":"html"}'>calendar.html</li>
                                        <li data-jstree='{"type":"html"}'>contacts.html</li>
                                        <li data-jstree='{"type":"html"}'>css_animation.html</li>
                                        <li class="text-navy" data-jstree='{"type":"html"}'>flot_chart.html</li>
                                        <li class="text-navy" data-jstree='{"type":"html"}'>google_maps.html</li>
                                        <li data-jstree='{"type":"html"}'>icons.html</li>
                                        <li data-jstree='{"type":"html"}'>inboice.html</li>
                                        <li data-jstree='{"type":"html"}'>login.html</li>
                                        <li data-jstree='{"type":"html"}'>mailbox.html</li>
                                        <li data-jstree='{"type":"html"}'>profile.html</li>
                                        <li class="text-navy" data-jstree='{"type":"html"}'>register.html</li>
                                        <li data-jstree='{"type":"html"}'>timeline.html</li>
                                        <li data-jstree='{"type":"html"}'>video.html</li>
                                        <li data-jstree='{"type":"html"}'>widgets.html</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>JSON示例</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <div id="using_json"></div>

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


    <!-- jsTree plugin javascript -->
    <script src="<?= $static_url ?>/js/plugins/jsTree/jstree.min.js"></script>

    <style>
        .jstree-open > .jstree-anchor > .fa-folder:before {
            content: "\f07c";
        }

        .jstree-default .jstree-icon.none {
            width: 0;
        }
    </style>

    <script>
        $(document).ready(function () {

            $('#jstree1').jstree({
                'core': {
                    'check_callback': true
                },
                'plugins': ['types', 'dnd'],
                'types': {
                    'default': {
                        'icon': 'fa fa-folder'
                    },
                    'html': {
                        'icon': 'fa fa-file-code-o'
                    },
                    'svg': {
                        'icon': 'fa fa-file-picture-o'
                    },
                    'css': {
                        'icon': 'fa fa-file-code-o'
                    },
                    'img': {
                        'icon': 'fa fa-file-image-o'
                    },
                    'js': {
                        'icon': 'fa fa-file-text-o'
                    }

                }
            });

            $('#using_json').jstree({
                'core': {
                    'data': [
                    'Empty Folder',
                        {
                            'text': 'Resources',
                            'state': {
                                'opened': true
                            },
                            'children': [
                                {
                                    'text': 'css',
                                    'children': [
                                        {
                                            'text': 'animate.css',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'bootstrap.css',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'main.css',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'style.css',
                                            'icon': 'none'
                                    }
                                ],
                                    'state': {
                                        'opened': true
                                    }
                            },
                                {
                                    'text': 'js',
                                    'children': [
                                        {
                                            'text': 'bootstrap.js',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'hplus.min.js',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'jquery.min.js',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'jsTree.min.js',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'custom.min.js',
                                            'icon': 'none'
                                    }
                                ],
                                    'state': {
                                        'opened': true
                                    }
                            },
                                {
                                    'text': 'html',
                                    'children': [
                                        {
                                            'text': 'layout.html',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'navigation.html',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'navbar.html',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'footer.html',
                                            'icon': 'none'
                                    },
                                        {
                                            'text': 'sidebar.html',
                                            'icon': 'none'
                                    }
                                ],
                                    'state': {
                                        'opened': true
                                    }
                            }
                        ]
                    },
                    'Fonts',
                    'Images',
                    'Scripts',
                    'Templates',
                ]
                }
            });

        });
    </script>



</body>

</html>
