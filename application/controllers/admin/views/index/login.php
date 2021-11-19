<?php
$this->extendTpl($theme_dir.'/layout.php'); ?>


<?php $this->blockStart('content'); ?>
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-12">
            <form method="post" action="">
                <h4 class="no-margins">登录：</h4>
                <input type="text" name="username" class="form-control uname" placeholder="用户名"/>
                <input type="password" name="password" class="form-control pword m-b" placeholder="密码"/>
                <a href="">忘记密码了？</a>
                <button class="btn btn-success btn-block">登录</button>
            </form>
        </div>
    </div>
    <div class="signup-footer">
        <div class="pull-left">
            &copy; 管理后台
        </div>
    </div>
</div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('styles'); ?>
<link href="<?php echo $static_url; ?>/css/login.css" rel="stylesheet">
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
<?php $this->blockEnd(); ?>
