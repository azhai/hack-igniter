<?php $this->extendTpl($theme_dir.'/layout.php'); ?>


<?php $this->blockStart('content'); ?>
<div class="wrapper wrapper-content animated fadeInUp">
    <div class="row">
        <div class="col-sm-12">

            <div class="ibox">
                <div class="ibox-title">
                    <h5>所有项目</h5>
                    <div class="ibox-tools">
                        <a href="projects.html" class="btn btn-primary btn-xs">创建新项目</a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row m-b-sm m-t-sm">
                        <div class="col-md-1">
                            <button type="button" id="loading-example-btn" class="btn btn-white btn-sm"><i
                                        class="fa fa-refresh"></i> 刷新
                            </button>
                        </div>
                        <div class="col-md-11">
                            <div class="input-group">
                                <input type="text" placeholder="请输入项目名称" class="input-sm form-control"> <span
                                        class="input-group-btn">
                                        <button type="button" class="btn btn-sm btn-primary"> 搜索</button> </span>
                            </div>
                        </div>
                    </div>

                    <div class="project-list">

                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">LIKE－一款能够让用户快速获得认同感的兴趣社交应用</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 48%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a1.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a2.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a4.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a5.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">米莫说｜MiMO Show</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 28%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 28%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a7.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a6.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-default">已取消
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">商家与购物用户的交互试衣应用</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 8%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 8%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a5.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">天狼---智能硬件项目</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 83%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 83%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a2.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a1.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a7.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">乐活未来</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 97%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 97%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a4.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">【私人医生项目】</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 48%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a1.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a2.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a4.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a5.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">快狗家居</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 28%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 28%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a7.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a6.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-default">已取消
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">线下超市+线上商城+物流配送互联系统</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 8%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 8%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a5.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">P司机汽车省钱专家</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 83%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 83%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a2.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a1.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">左左 靠谱男同交友</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 97%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 97%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a4.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="project-status">
                                            <span class="label label-primary">进行中
                                </td>
                                <td class="project-title">
                                    <a href="project_detail.html">程序员私活圈</a>
                                    <br/>
                                    <small>创建于 2014.08.15</small>
                                </td>
                                <td class="project-completion">
                                    <small>当前进度： 28%</small>
                                    <div class="progress progress-mini">
                                        <div style="width: 28%;" class="progress-bar"></div>
                                    </div>
                                </td>
                                <td class="project-people">
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a7.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a6.jpg"></a>
                                    <a href="projects.html"><img alt="image" class="img-circle"
                                                                 src="<?php echo $static_url; ?>/img/a3.jpg"></a>
                                </td>
                                <td class="project-actions">
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i>
                                        查看 </a>
                                    <a href="projects.html#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i>
                                        编辑 </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
<script>
    $(document).ready(function () {

        $('#loading-example-btn').click(function () {
            btn = $(this);
            simpleLoad(btn, true)

            // Ajax example
//                $.ajax().always(function () {
//                    simpleLoad($(this), false)
//                });

            simpleLoad(btn, false)
        });
    });

    function simpleLoad(btn, state) {
        if (state) {
            btn.children().addClass('fa-spin');
            btn.contents().last().replaceWith(" Loading");
        } else {
            setTimeout(function () {
                btn.children().removeClass('fa-spin');
                btn.contents().last().replaceWith(" Refresh");
            }, 2000);
        }
    }
</script>
<?php $this->blockEnd(); ?>
