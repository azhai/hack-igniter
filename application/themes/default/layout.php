<!DOCTYPE html>
<html lang="zh">
 <head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0" />
  <title><?= $site_title ?></title>
  <link rel="stylesheet" href="<?= $static_url ?>/css/lib.css?6cf3b9a0d6" />
  <link rel="stylesheet" href="<?= $static_url ?>/css/bolt.css?cd4c8f9e8b" />
  <link rel="shortcut icon" href="<?= $static_url ?>/images/favicon-bolt.ico?265b46d143" />
  <link rel="apple-touch-icon" sizes="57x57" href="<?= $static_url ?>/images/apple-touch-icon.png?3140e0c36d" />
  <link rel="apple-touch-icon" sizes="72x72" href="<?= $static_url ?>/images/apple-touch-icon-72x72.png?058a29df3a" />
  <link rel="apple-touch-icon" sizes="114x114" href="<?= $static_url ?>/images/apple-touch-icon-114x114.png?a739a665dd" />
  <link rel="apple-touch-icon" sizes="144x144" href="<?= $static_url ?>/images/apple-touch-icon-144x144.png?cdf0c6cd3e" />
 </head>
 <body class="<?= $layout_class ?>">
  <div id="navpage-wrapper" class="nav-secondary-collapsed nav-secondary-collapsed-hoverable">
<?= $this->block('header'); ?>
<?= $this->block('sidebar'); ?>
<?= $this->block('content'); ?>
  </div>

  <footer id="bolt-footer" class="hidden-xs bolt-footer-mini bolt-footer-hidden">
   <i class="fa fa-cog"></i>
   <a href="http://bolt.cm" target="_blank">Bolt 3.4.8</a> –
   <i class="fa fa-heart"></i>
   <a href="/bolt/about">关于</a>
  </footer>
  <script src="<?= $static_url ?>/js/lib.js?01f0aa63df"></script>
  <script src="<?= $static_url ?>/js/locale/datepicker/zh_CN.min.js?eb27a00385"></script>
  <script src="<?= $static_url ?>/js/locale/moment/zh_CN.min.js?0a762880e8"></script>
  <script src="<?= $static_url ?>/js/locale/select2/zh_CN.min.js?12119d6eb0"></script>
<?= $this->block('scripts'); ?>
 </body>
</html>
