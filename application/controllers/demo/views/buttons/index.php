<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 按钮</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?= $static_url ?>/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="<?= $static_url ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="<?= $static_url ?>/css/animate.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>按钮颜色</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="buttons.html#">选项1</a>
                        </li>
                        <li><a href="buttons.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <p>
                    可使用class来快速改变按钮的颜色，如<code>.btn-primary</code>
                </p>

                <h3 class="font-bold">
                    普通按钮
                </h3>
                <p>
                    <button type="button" class="btn btn-w-m btn-default">btn-default</button>
                    <button type="button" class="btn btn-w-m btn-primary">btn-primary</button>
                    <button type="button" class="btn btn-w-m btn-success">btn-success</button>
                    <button type="button" class="btn btn-w-m btn-info">btn-info</button>
                    <button type="button" class="btn btn-w-m btn-warning">btn-warning</button>
                    <button type="button" class="btn btn-w-m btn-danger">btn-danger</button>
                    <button type="button" class="btn btn-w-m btn-white">btn-white</button>
                    <button type="button" class="btn btn-w-m btn-link">btn-link</button>
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>按钮大小</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="buttons.html#">选项1</a>
                        </li>
                        <li><a href="buttons.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <p>
                    可以通过添加class的值为<code>.btn-lg</code>, <code>.btn-sm</code>, or <code>.btn-xs</code>来修改按钮的大小
                </p>
                <h3 class="font-bold">按钮尺寸</h3>
                <p>
                    <button type="button" class="btn btn-primary btn-lg">大按钮</button>
                    <button type="button" class="btn btn-default btn-lg">大按钮</button>
                    <br/>
                    <button type="button" class="btn btn-primary">默认按钮</button>
                    <button type="button" class="btn btn-default">默认按钮</button>
                    <br/>
                    <button type="button" class="btn btn-primary btn-sm">小按钮</button>
                    <button type="button" class="btn btn-default btn-sm">小按钮</button>
                    <br/>
                    <button type="button" class="btn btn-primary btn-xs">Mini按钮</button>
                    <button type="button" class="btn btn-default btn-xs">Mini按钮</button>
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>线性按钮</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="buttons.html#">选项1</a>
                        </li>
                        <li><a href="buttons.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <p>
                    要使用线性按钮，可添加class<code>.btn-block</code>或<code>.btn-outline</code>
                </p>

                <h3 class="font-bold">线性按钮</h3>
                <p>
                    <button type="button" class="btn btn-outline btn-default">默认</button>
                    <button type="button" class="btn btn-outline btn-primary">主要</button>
                    <button type="button" class="btn btn-outline btn-success">成功</button>
                    <button type="button" class="btn btn-outline btn-info">信息</button>
                    <button type="button" class="btn btn-outline btn-warning">警告</button>
                    <button type="button" class="btn btn-outline btn-danger">危险</button>
                    <button type="button" class="btn btn-outline btn-link">链接</button>
                </p>
                <h3 class="font-bold">块级按钮</h3>
                <p>
                    <button type="button" class="btn btn-block btn-outline btn-primary">这是一个块级按钮</button>
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>3D按钮</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="buttons.html#">选项1</a>
                        </li>
                        <li><a href="buttons.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <p>
                    可以通过添加<code>.dim</code>class来使用3D按钮.
                </p>
                <h3 class="font-bold">3D按钮</h3>

                <button class="btn btn-primary dim btn-large-dim" type="button"><i class="fa fa-money"></i>
                </button>
                <button class="btn btn-warning dim btn-large-dim" type="button"><i class="fa fa-warning"></i>
                </button>
                <button class="btn btn-danger  dim btn-large-dim" type="button"><i class="fa fa-heart"></i>
                </button>
                <button class="btn btn-primary  dim btn-large-dim" type="button"><i class="fa fa-dollar"></i>6</button>
                <button class="btn btn-info  dim btn-large-dim btn-outline" type="button"><i class="fa fa-ruble"></i>
                </button>
                <button class="btn btn-primary dim" type="button"><i class="fa fa-money"></i>
                </button>
                <button class="btn btn-warning dim" type="button"><i class="fa fa-warning"></i>
                </button>
                <button class="btn btn-primary dim" type="button"><i class="fa fa-check"></i>
                </button>
                <button class="btn btn-success  dim" type="button"><i class="fa fa-upload"></i>
                </button>
                <button class="btn btn-info  dim" type="button"><i class="fa fa-paste"></i>
                </button>
                <button class="btn btn-warning  dim" type="button"><i class="fa fa-warning"></i>
                </button>
                <button class="btn btn-default  dim " type="button"><i class="fa fa-star"></i>
                </button>
                <button class="btn btn-danger  dim " type="button"><i class="fa fa-heart"></i>
                </button>

                <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-money"></i>
                </button>
                <button class="btn btn-outline btn-warning dim" type="button"><i class="fa fa-warning"></i>
                </button>
                <button class="btn btn-outline btn-primary dim" type="button"><i class="fa fa-check"></i>
                </button>
                <button class="btn btn-outline btn-success  dim" type="button"><i class="fa fa-upload"></i>
                </button>
                <button class="btn btn-outline btn-info  dim" type="button"><i class="fa fa-paste"></i>
                </button>
                <button class="btn btn-outline btn-warning  dim" type="button"><i class="fa fa-warning"></i>
                </button>
                <button class="btn btn-outline btn-danger  dim " type="button"><i class="fa fa-heart"></i>
                </button>

            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>下拉按钮</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="buttons.html#">选项1</a>
                                </li>
                                <li><a href="buttons.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <p>
                            下拉按钮可使用任何颜色任何大小
                        </p>

                        <h3 class="font-bold">下拉按钮</h3>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-warning dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>

                        <br/>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-warning btn-sm dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-sm dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                        <br/>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-warning btn-xs dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">操作 <span
                                        class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="buttons.html#">置顶</a>
                                </li>
                                <li><a href="buttons.html#" class="font-bold">修改</a>
                                </li>
                                <li><a href="buttons.html#">禁用</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="buttons.html#">删除</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>按钮组</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="buttons.html#">选项1</a>
                                </li>
                                <li><a href="buttons.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">

                        <h3 class="font-bold">按钮组</h3>
                        <div class="btn-group">
                            <button class="btn btn-white" type="button">左</button>
                            <button class="btn btn-primary" type="button">中</button>
                            <button class="btn btn-white" type="button">右</button>
                        </div>
                        <br/>
                        <br/>
                        <div class="btn-group">
                            <button type="button" class="btn btn-white"><i class="fa fa-chevron-left"></i>
                            </button>
                            <button class="btn btn-white">1</button>
                            <button class="btn btn-white  active">2</button>
                            <button class="btn btn-white">3</button>
                            <button class="btn btn-white">4</button>
                            <button type="button" class="btn btn-white"><i class="fa fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>图标按钮 </h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="buttons.html#">选项1</a>
                        </li>
                        <li><a href="buttons.html#">选项2</a>
                        </li>
                    </ul>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <p>
                    任何按钮都可以在左侧或右侧添加图标
                </p>

                <h3 class="font-bold">图标按钮</h3>
                <p>
                    <button class="btn btn-primary " type="button"><i class="fa fa-check"></i>&nbsp;提交</button>
                    <button class="btn btn-success " type="button"><i class="fa fa-upload"></i>&nbsp;&nbsp;<span
                                class="bold">上传</span>
                    </button>
                    <button class="btn btn-info " type="button"><i class="fa fa-paste"></i> 编辑</button>
                    <button class="btn btn-warning " type="button"><i class="fa fa-warning"></i> <span
                                class="bold">警告</span>
                    </button>
                    <button class="btn btn-default " type="button"><i class="fa fa-map-marker"></i>&nbsp;&nbsp;百度地图
                    </button>

                    <a class="btn btn-success">
                        <i class="fa fa-weixin"> </i> 分享到微信
                    </a>
                    <a class="btn btn-success btn-outline">
                        <i class="fa fa-qq"> </i> 使用QQ账号登录
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-user-md"></i>
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-group"></i>
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-wrench"></i>
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-exchange"></i>
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-check-circle-o"></i>
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-road"></i>
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-ambulance"></i>
                    </a>
                    <a class="btn btn-white btn-bitbucket">
                        <i class="fa fa-star"></i> 收藏
                    </a>
                </p>

                <h3 class="font-bold">按钮切换</h3>
                <button data-toggle="button" class="btn btn-primary btn-outline" type="button">按钮1</button>
                <button data-toggle="button" class="btn btn-primary" type="button">按钮2</button>
                <div data-toggle="buttons-checkbox" class="btn-group">
                    <button class="btn btn-primary active" type="button"><i class="fa fa-bold"></i> 粗体</button>
                    <button class="btn btn-primary" type="button"><i class="fa fa-underline"></i> 下划线</button>
                    <button class="btn btn-primary active" type="button"><i class="fa fa-italic"></i> 斜体</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>圆形图标按钮</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="buttons.html#">选项1</a>
                                </li>
                                <li><a href="buttons.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <p>
                            要使用圆形图标按钮，可以通过添加class为<code>.btn-circle</code>实现
                        </p>

                        <h3 class="font-bold">圆形按钮</h3>
                        <br/>
                        <button class="btn btn-default btn-circle" type="button"><i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-primary btn-circle" type="button"><i class="fa fa-list"></i>
                        </button>
                        <button class="btn btn-success btn-circle" type="button"><i class="fa fa-link"></i>
                        </button>
                        <button class="btn btn-info btn-circle" type="button"><i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-warning btn-circle" type="button"><i class="fa fa-times"></i>
                        </button>
                        <button class="btn btn-danger btn-circle" type="button"><i class="fa fa-heart"></i>
                        </button>
                        <button class="btn btn-danger btn-circle btn-outline" type="button"><i class="fa fa-heart"></i>
                        </button>
                        <br/>
                        <br/>
                        <button class="btn btn-default btn-circle btn-lg" type="button"><i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-primary btn-circle btn-lg" type="button"><i class="fa fa-list"></i>
                        </button>
                        <button class="btn btn-success btn-circle btn-lg" type="button"><i class="fa fa-link"></i>
                        </button>
                        <button class="btn btn-info btn-circle btn-lg" type="button"><i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-warning btn-circle btn-lg" type="button"><i class="fa fa-times"></i>
                        </button>
                        <button class="btn btn-danger btn-circle btn-lg" type="button"><i class="fa fa-heart"></i>
                        </button>
                        <button class="btn btn-danger btn-circle btn-lg btn-outline" type="button"><i
                                    class="fa fa-heart"></i>
                        </button>

                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>圆角按钮</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="dropdown-toggle" data-toggle="dropdown" href="buttons.html#">
                                <i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-user">
                                <li><a href="buttons.html#">选项1</a>
                                </li>
                                <li><a href="buttons.html#">选项2</a>
                                </li>
                            </ul>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <p>
                            可以通过添加class的值微<code>.btn-rounded</code>来实现圆角按钮
                        </p>

                        <h3 class="font-bold">按钮组</h3>
                        <p>
                            <a class="btn btn-default btn-rounded" href="buttons.html#">默认</a>
                            <a class="btn btn-primary btn-rounded" href="buttons.html#">主要</a>
                            <a class="btn btn-success btn-rounded" href="buttons.html#">成果</a>
                            <a class="btn btn-info btn-rounded" href="buttons.html#">信息</a>
                            <a class="btn btn-warning btn-rounded" href="buttons.html#">警告</a>
                            <a class="btn btn-danger btn-rounded" href="buttons.html#">危险</a>
                            <a class="btn btn-danger btn-rounded btn-outline" href="buttons.html#">危险</a>
                            <br/>
                            <br/>
                            <a class="btn btn-primary btn-rounded btn-block" href="buttons.html#"><i
                                        class="fa fa-info-circle"></i> 圆角块级带图标按钮</a>
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


</body>

</html>
