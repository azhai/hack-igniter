<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?= $site_title ?> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="<?= $static_url ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="<?= $static_url ?>/css/animate.css" rel="stylesheet">
    <link href="<?= $static_url ?>/css/style.css" rel="stylesheet">
<?= $this->block('styles'); ?>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <!--script>if(window.top !== window.self){ window.top.location = window.location;}</script-->
</head>

<body class="<?= $layout_class ?>">
<?= $this->block('sidebar'); ?>
<?= $this->block('content'); ?>
<?= $this->block('scripts'); ?>
 </body>
</html>
