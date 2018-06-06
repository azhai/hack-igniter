<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 文件管理器</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="<?= $static_url ?>/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="<?= $static_url ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="<?= $static_url ?>/css/animate.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="file-manager">
                            <h5>显示：</h5>
                            <a href="file_manager.html#" class="file-control active">所有</a>
                            <a href="file_manager.html#" class="file-control">文档</a>
                            <a href="file_manager.html#" class="file-control">视频</a>
                            <a href="file_manager.html#" class="file-control">图片</a>
                            <div class="hr-line-dashed"></div>
                            <button class="btn btn-primary btn-block">上传文件</button>
                            <div class="hr-line-dashed"></div>
                            <h5>文件夹</h5>
                            <ul class="folder-list" style="padding: 0">
                                <li><a href="file_manager.html"><i class="fa fa-folder"></i> 文件</a>
                                </li>
                                <li><a href="file_manager.html"><i class="fa fa-folder"></i> 图片</a>
                                </li>
                                <li><a href="file_manager.html"><i class="fa fa-folder"></i> Web页面</a>
                                </li>
                                <li><a href="file_manager.html"><i class="fa fa-folder"></i> 插图</a>
                                </li>
                                <li><a href="file_manager.html"><i class="fa fa-folder"></i> 电影</a>
                                </li>
                                <li><a href="file_manager.html"><i class="fa fa-folder"></i> 书籍</a>
                                </li>
                            </ul>
                            <h5 class="tag-title">标签</h5>
                            <ul class="tag-list" style="padding: 0">
                                <li><a href="file_manager.html">爱人</a>
                                </li>
                                <li><a href="file_manager.html">工作</a>
                                </li>
                                <li><a href="file_manager.html">家庭</a>
                                </li>
                                <li><a href="file_manager.html">孩子</a>
                                </li>
                                <li><a href="file_manager.html">假期</a>
                                </li>
                                <li><a href="file_manager.html">音乐</a>
                                </li>
                                <li><a href="file_manager.html">照片</a>
                                </li>
                                <li><a href="file_manager.html">电影</a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9 animated fadeInRight">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p1.jpg">
                                    </div>
                                    <div class="file-name">
                                        Italy street.jpg
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p2.jpg">
                                    </div>
                                    <div class="file-name">
                                        My feel.png
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-music"></i>
                                    </div>
                                    <div class="file-name">
                                        Michal Jackson.mp3
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p3.jpg">
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="img-responsive fa fa-film"></i>
                                    </div>
                                    <div class="file-name">
                                        Monica's birthday.mpg4
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <a href="file_manager.html#">
                                <div class="file">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="file-name">
                                        Annual report 2014.xls
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p1.jpg">
                                    </div>
                                    <div class="file-name">
                                        Italy street.jpg
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p2.jpg">
                                    </div>
                                    <div class="file-name">
                                        My feel.png
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-music"></i>
                                    </div>
                                    <div class="file-name">
                                        Michal Jackson.mp3
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p3.jpg">
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="img-responsive fa fa-film"></i>
                                    </div>
                                    <div class="file-name">
                                        Monica's birthday.mpg4
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <a href="file_manager.html#">
                                <div class="file">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="file-name">
                                        Annual report 2014.xls
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p1.jpg">
                                    </div>
                                    <div class="file-name">
                                        Italy street.jpg
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>

                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p2.jpg">
                                    </div>
                                    <div class="file-name">
                                        My feel.png
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-music"></i>
                                    </div>
                                    <div class="file-name">
                                        Michal Jackson.mp3
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="image">
                                        <img alt="image" class="img-responsive" src="<?= $static_url ?>/img/p3.jpg">
                                    </div>
                                    <div class="file-name">
                                        Document_2014.doc
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <div class="file">
                                <a href="file_manager.html#">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="img-responsive fa fa-film"></i>
                                    </div>
                                    <div class="file-name">
                                        Monica's birthday.mpg4
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="file-box">
                            <a href="file_manager.html#">
                                <div class="file">
                                    <span class="corner"></span>

                                    <div class="icon">
                                        <i class="fa fa-bar-chart-o"></i>
                                    </div>
                                    <div class="file-name">
                                        Annual report 2014.xls
                                        <br/>
                                        <small>添加时间：2014-10-13</small>
                                    </div>
                                </div>
                            </a>
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


    <script>
        $(document).ready(function () {
            $('.file-box').each(function () {
                animationHover(this, 'pulse');
            });
        });
    </script>




</body>

</html>
