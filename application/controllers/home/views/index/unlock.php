<?php
$this->extendTpl($theme_dir . '/layout.php'); ?>


<?php $this->blockStart('content'); ?>
    <div class="lock-word animated fadeInDown">
    </div>
    <div class="middle-box text-center lockscreen animated fadeInDown">
        <div>
            <div class="m-b-md">
                <img alt="image" class="img-circle circle-border" src="<?= $static_url ?>/img/a1.jpg">
            </div>
            <h3><?=$username?></h3>
            <p>您需要再次输入密码</p>
            <form class="m-t" role="form" method="post" action="">
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="请输入密码" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width">解 锁</button>
            </form>
        </div>
    </div>
<?php $this->blockEnd(); ?>


<?php $this->blockStart('scripts'); ?>
    <script src="<?= $static_url ?>/js/jquery.min.js?v=2.1.4"></script>
    <script src="<?= $static_url ?>/js/bootstrap.min.js?v=3.3.6"></script>
<?php $this->blockEnd(); ?>
