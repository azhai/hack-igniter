<?php
$this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('header'); ?>
   <nav id="navpage-primary" class="navbar navbar-static-top navbar-inverse navbar-bolt">
    <div class="container-fluid">
     <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-xaction="bolt.sidebar.toggle()"> <i class="fa fa-fw fa-bars"></i> MENU </button>
      <a class="navbar-brand" href="/bolt"> Bolt </a>
      <a href="/bolt" class="navbar-site hidden-xs"><span>A sample site</span></a>
     </div>
     <ul class="nav navbar-top-links navbar-right">
      <li>
       <form class="navbar-form hidden-xs">
        <div class="form-group has-feedback omnisearch" role="search">
         <select class="form-control" data-omnisearch-url="/async/omnisearch"></select>
         <span class="form-control-feedback"><i class="fa fa-search"></i></span>
        </div>
       </form> </li>
      <li> <a href="/" target="_blank" class="hidden-xs"> <i class="fa fa-fw fa-external-link-square"></i> 查看站点 </a> </li>
      <li id="live_editor_save" class="save-live-editor"> <a> <i class="fa fa-fw fa-flag"></i> 保存entries </a> </li>
      <li class="close-live-editor"> <a> <i class="fa fa-fw fa-times-circle-o"></i> Close editor </a> </li>
      <li class="dropdown"> <a class="dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-fw fa-user"></i> <span>管理员</span> <i class="fa fa-caret-down"></i> </a>
       <ul class="dropdown-menu dropdown-user" role="menu">
        <li> <a href="/bolt/profile" role="menuitem" tabindex="-1"><i class="fa fa-fw fa-gear"></i> 个人资料</a> </li>
        <li>
         <form action="<?= $logout_url ?>" method="POST">
          <button class="btn btn-link btn-block" type="submit"> <span class="pull-left"><i class="fa fa-fw fa-sign-out" style="margin-left: 4px;"></i> 登出</span> </button>
         </form> </li>
       </ul> </li>
     </ul>
    </div>
   </nav>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('sidebar'); ?>
   <nav id="navpage-secondary" class="navbar-default navbar-static-side">
    <ul class="nav">
     <li class="search">
      <form class="navbar-form">
       <i class="icon fa fa-search"></i>
       <div class="form-group has-feedback omnisearch" role="search">
        <select class="form-control" data-omnisearch-url="/async/omnisearch"></select>
       </div>
      </form> </li>
     <li class="nav-secondary-dashboard"> <a href="/bolt"> <i class="fa fa-dashboard icon"></i> 操作台 </a> </li>
     <li class="visible-xs"> <a href="/" target="_blank"><i class="icon fa fa-external-link-square"></i> 查看站点</a> </li>
     <li class="divider"> <em> <i class="fa fa-file-text icon"></i> 内容 </em> </li>
     <li class="menu-pop-wide"> <a href="/bolt/overview/pages" class="menu-pop"> <i class="icon">P</i> Pages </a>
      <ul class="nav submenu">
       <li> <a href="/bolt/overview/pages"> <i class="fa fa-files-o fa-fw"></i> 查看Pages </a> </li>
       <li> <a href="/bolt/editcontent/pages"> <i class="fa fa-plus fa-fw"></i> 新建Page </a> </li>
       <li class="subdivider"> <a href="/bolt/editcontent/pages/5"> <i class="fa fa-file-text-o fa-fw"></i> <b>Negat esse eam, inquit, propter se expetendam.&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/pages/4"> <i class="fa fa-file-text-o fa-fw"></i> <b>Equidem, sed audistine modo de Carneade?&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/pages/3"> <i class="fa fa-file-text-o fa-fw"></i> <b>Quo modo?&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/pages/2"> <i class="fa fa-file-text-o fa-fw"></i> <b>Sed potestne rerum maior esse dissensio?&nbsp;</b> </a> </li>
      </ul> </li>
     <li class="menu-pop-wide active"> <a href="/bolt/overview/entries" class="menu-pop"> <i class="icon">E</i> Entries </a>
      <ul class="nav submenu">
       <li> <a href="/bolt/overview/entries"> <i class="fa fa-files-o fa-fw"></i> 查看Entries </a> </li>
       <li> <a href="/bolt/editcontent/entries"> <i class="fa fa-plus fa-fw"></i> 新建Entry </a> </li>
       <li class="subdivider"> <a href="/bolt/editcontent/entries/35"> <i class="fa fa-file-text-o fa-fw"></i> <b>ALIO MODO.&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/entries/34"> <i class="fa fa-file-text-o fa-fw"></i> <b>Nescio quo modo praetervolavit oratio.&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/entries/33"> <i class="fa fa-file-text-o fa-fw"></i> <b>Sin aliud quid voles, postea.&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/entries/32"> <i class="fa fa-file-text-o fa-fw"></i> <b>Quid enim est a Chrysippo praetermissum in Stoicis?&nbsp;</b> </a> </li>
      </ul> </li>
     <li class="menu-pop-wide"> <a href="/bolt/overview/showcases" class="menu-pop"> <i class="fa fa-gift icon"></i> Showcases </a>
      <ul class="nav submenu">
       <li> <a href="/bolt/overview/showcases"> <i class="fa fa-gift fa-fw"></i> 查看Showcases </a> </li>
       <li> <a href="/bolt/editcontent/showcases"> <i class="fa fa-plus fa-fw"></i> 新建Showcase </a> </li>
       <li class="subdivider"> <a href="/bolt/editcontent/showcases/5"> <i class="fa fa-gift fa-fw"></i> <b>Ne in odium veniam, si amicum destitero tueri.&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/showcases/4"> <i class="fa fa-gift fa-fw"></i> <b>Heri, inquam, ludis commissis ex urbe profectus veni ad vesperum.&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/showcases/3"> <i class="fa fa-gift fa-fw"></i> <b>Quamquam te quidem video minime esse deterritum.&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/showcases/2"> <i class="fa fa-gift fa-fw"></i> <b>Quid ergo?&nbsp;</b> </a> </li>
      </ul> </li>
     <li class="menu-pop-wide"> <a href="/bolt/overview/blocks" class="menu-pop"> <i class="fa fa-cubes icon"></i> Blocks </a>
      <ul class="nav submenu">
       <li> <a href="/bolt/overview/blocks"> <i class="fa fa-cubes fa-fw"></i> 查看Blocks </a> </li>
       <li> <a href="/bolt/editcontent/blocks"> <i class="fa fa-plus fa-fw"></i> 新建Block </a> </li>
       <li class="subdivider"> <a href="/bolt/editcontent/blocks/5"> <i class="fa fa-cube fa-fw"></i> <b>Quae sequuntur igitur?&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/blocks/4"> <i class="fa fa-cube fa-fw"></i> <b>404 Not Found&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/blocks/3"> <i class="fa fa-cube fa-fw"></i> <b>Search Teaser&nbsp;</b> </a> </li>
       <li> <a href="/bolt/editcontent/blocks/2"> <i class="fa fa-cube fa-fw"></i> <b>Address&nbsp;</b> </a> </li>
      </ul> </li>
     <li class="divider"> <em> <i class="fa fa-wrench icon"></i> 设置 </em> </li>
     <li> <a href="/bolt/users" class="menu-pop"> <i class="fa fa-cogs icon"></i> 配置 </a>
      <ul class="nav submenu">
       <li> <a href="/bolt/users"> <i class="fa fa-group fa-fw"></i> Users &amp; Permissions </a> </li>
       <li> <a href="/bolt/file/edit/config/config.yml"> <i class="fa fa-cog fa-fw"></i> 主配置文件 </a> </li>
       <li> <a href="/bolt/file/edit/config/contenttypes.yml"> <i class="fa fa-paint-brush fa-fw"></i> 内容格式 </a> </li>
       <li> <a href="/bolt/file/edit/config/taxonomy.yml"> <i class="fa fa-tags fa-fw"></i> 分类 </a> </li>
       <li class="subdivider"> <a href="/bolt/file/edit/config/menu.yml"> <i class="fa fa-list fa-fw"></i> 菜单设置 </a> </li>
       <li> <a href="/bolt/file/edit/config/routing.yml"> <i class="fa fa-random fa-fw"></i> Routing set up </a> </li>
       <li class="subdivider"> <a href="/bolt/dbcheck"> <i class="fa fa-database fa-fw"></i> 检查数据库 </a> </li>
       <li> <a href="/bolt/clearcache"> <i class="fa fa-eraser fa-fw"></i> 清理缓存 </a> </li>
       <li> <a href="/bolt/changelog"> <i class="fa fa-archive fa-fw"></i> Change Log </a> </li>
       <li> <a href="/bolt/systemlog"> <i class="fa fa-archive fa-fw"></i> System Log </a> </li>
       <li> <a href="/bolt/checks"> <i class="fa fa-support fa-fw"></i> Set-up checks </a> </li>
       <li class="subdivider"> <a href="/bolt/tr"> <i class="fa fa-flag fa-fw"></i> 翻译: 消息 </a> </li>
       <li> <a href="/bolt/tr/infos"> <i class="fa fa-flag fa-fw"></i> 翻译: 长信息 </a> </li>
      </ul> </li>
     <li> <a href="/bolt/files" class="menu-pop"> <i class="fa fa-folder-open icon"></i> 文件管理 </a>
      <ul class="nav submenu">
       <li> <a href="/bolt/files"> <i class="fa fa-folder-open-o fa-fw"></i> 已上传的文件 </a> </li>
       <li> <a href="/bolt/files/themes"> <i class="fa fa-desktop fa-fw"></i> 查看/编辑模板 </a> </li>
      </ul> </li>
     <li> <a href="/bolt/extensions"> <i class="fa fa-cubes icon"></i> 查看/安装扩展 </a> </li>
     <li class="nav-secondary-collapse"> <a href="#"> <i class="fa fa-compress icon"></i> 折叠侧边栏 </a> </li>
     <li class="nav-secondary-expand"> <a href="#"> <i class="fa fa-expand icon"></i> 展开 </a> </li>
    </ul>
   </nav>
<?php $this->blockEnd(); ?>
