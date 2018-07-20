-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+08:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `t_accounts`;
CREATE TABLE `t_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `balance` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '余额',
  `currency` varchar(20) NOT NULL DEFAULT '' COMMENT '币种',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `balance` (`balance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户';

INSERT INTO `t_accounts` (`id`, `user_id`, `balance`, `currency`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 1,  12, 'COIN', '2018-05-31 16:00:00',  '2018-07-04 02:56:38',  0);

DROP TABLE IF EXISTS `t_account_history`;
CREATE TABLE `t_account_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '账户ID',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额',
  `after_balance` int(10) NOT NULL DEFAULT '0' COMMENT '更新后余额',
  `currency` varchar(20) NOT NULL DEFAULT '' COMMENT '币种',
  `cashier` varchar(30) DEFAULT NULL COMMENT '出纳员',
  `remark` text COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `amount` (`amount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户流水';

INSERT INTO `t_account_history` (`id`, `account_id`, `amount`, `after_balance`, `currency`, `cashier`, `remark`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 1,  3,  3,  'COIN', NULL, NULL, '0000-00-00 00:00:00',  '0000-00-00 00:00:00',  0),
(2, 1,  3,  6,  'COIN', NULL, NULL, '0000-00-00 00:00:00',  '0000-00-00 00:00:00',  0),
(3, 1,  3,  9,  'COIN', NULL, NULL, '2018-07-04 02:56:28',  '2018-07-04 02:56:28',  0),
(4, 1,  3,  12, 'COIN', NULL, NULL, '2018-07-04 02:56:38',  '2018-07-04 02:56:38',  0);

DROP TABLE IF EXISTS `t_admins`;
CREATE TABLE `t_admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(80) NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `gender` enum('F','M','X') NOT NULL DEFAULT 'X' COMMENT '性别',
  `email` varchar(50) DEFAULT NULL COMMENT '电子邮箱',
  `phone` varchar(50) DEFAULT NULL COMMENT '手机号码',
  `last_seen` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_ipaddr` varchar(15) DEFAULT NULL COMMENT '最后登录IP',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员';

INSERT INTO `t_admins` (`id`, `role_id`, `username`, `password`, `nickname`, `gender`, `email`, `phone`, `last_seen`, `last_ipaddr`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 2,  'admin',  '$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2', '管理员',  'M',  'admin@where.com',  NULL, NULL, NULL, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(2, 2,  'bob',  '$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2', 'Bob薄', 'M',  'bob@where.com',  NULL, NULL, NULL, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(3, 3,  'cherry', '$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2', 'Cherry陈',  'F',  'cherry@where.com', NULL, NULL, NULL, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(4, 3,  'david',  '$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2', 'David戴', 'M',  'david@where.com',  NULL, NULL, NULL, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(5, 4,  'emily',  '$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2', 'Emily艾', 'F',  'emily@where.com',  NULL, NULL, NULL, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0);

DROP TABLE IF EXISTS `t_checkins`;
CREATE TABLE `t_checkins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '金额',
  `expired_on` date DEFAULT NULL COMMENT '过期日期',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `amount` (`amount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='签到';


DROP TABLE IF EXISTS `t_entries`;
CREATE TABLE `t_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `datecreated` datetime NOT NULL,
  `datechanged` datetime NOT NULL,
  `datepublish` datetime DEFAULT NULL,
  `datedepublish` datetime DEFAULT NULL,
  `ownerid` int(11) DEFAULT NULL,
  `status` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `templatefields` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `title` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `teaser` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `body` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `image` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  `video` longtext CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '(DC2Type:json)',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `slug` (`slug`),
  KEY `datepublish` (`datepublish`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `t_entries` (`id`, `slug`, `datecreated`, `datechanged`, `datepublish`, `datedepublish`, `ownerid`, `status`, `templatefields`, `title`, `teaser`, `body`, `image`, `video`) VALUES
(1, 'etenim-semper-illud-extra-est-quod-arte-comprehenditur', '2018-02-22 19:59:50',  '2018-02-25 10:43:55',  '2017-04-13 14:18:57',  NULL, 1,  'published',  '[]', 'Etenim semper illud extra est, quod arte comprehenditur.', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Primum Theophrasti, Strato, physicum se voluit; <b>Non quam nostram quidem, inquit Pomponius iocans;</b> Qui enim existimabit posse se miserum esse beatus non erit. Istam voluptatem perpetuam quis potest praestare sapienti? Quis est enim, in quo sit cupiditas, quin recte cupidus dici possit? </p>',  '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href=\"http://loripsum.net/\" target=\"_blank\">Sed ego in hoc resisto;</a> Quae similitudo in genere etiam humano apparet. Quos quidem tibi studiose et diligenter tractandos magnopere censeo. Duo Reges: constructio interrete. Duo enim genera quae erant, fecit tria. <a href=\"http://loripsum.net/\" target=\"_blank\">Quod quidem iam fit etiam in Academia.</a> <b>Nam quid possumus facere melius?</b> Beatus sibi videtur esse moriens. Tu quidem reddes; <b>Sin aliud quid voles, postea.</b> </p>\n\n<p><a href=\"http://loripsum.net/\" target=\"_blank\">Qua ex cognitione facilior facta est investigatio rerum occultissimarum.</a> Facile est hoc cernere in primis puerorum aetatulis. Sed quid sentiat, non videtis. <mark>Si mala non sunt, iacet omnis ratio Peripateticorum.</mark> </p>\n\n<ul>\n <li>Nec tamen ille erat sapiens quis enim hoc aut quando aut ubi aut unde?</li>\n <li>Nec hoc ille non vidit, sed verborum magnificentia est et gloria delectatus.</li>\n <li>Rapior illuc, revocat autem Antiochus, nec est praeterea, quem audiamus.</li>\n <li>Nihil enim iam habes, quod ad corpus referas;</li>\n</ul>\n\n\n<ol>\n <li>Etsi ea quidem, quae adhuc dixisti, quamvis ad aetatem recte isto modo dicerentur.</li>\n <li>Sed et illum, quem nominavi, et ceteros sophistas, ut e Platone intellegi potest, lusos videmus a Socrate.</li>\n</ol>\n\n\n<p>Isto modo, ne si avia quidem eius nata non esset. Scripta sane et multa et polita, sed nescio quo pacto auctoritatem oratio non habet. <b>Aufert enim sensus actionemque tollit omnem.</b> <b>At coluit ipse amicitias.</b> Torquatus, is qui consul cum Cn. Si verbum sequimur, primum longius verbum praepositum quam bonum. </p>',  '{\"file\":\"carrot-cooking-eat-1398.jpg\",\"title\":\"Carrot Cooking Eat 1398.\",\"alt\":\"Carrot Cooking Eat 1398.\"}', NULL);

DROP TABLE IF EXISTS `t_menus`;
CREATE TABLE `t_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT '0' COMMENT '上级菜单',
  `title` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '中文名称',
  `url` varchar(30) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '网址',
  `icon` varchar(30) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '图标',
  `corner` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '角标',
  `is_virtual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟菜单',
  `seqno` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '次序',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='菜单';

INSERT INTO `t_menus` (`id`, `parent_id`, `title`, `url`, `icon`, `corner`, `is_virtual`, `seqno`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 0,  '管理面板', '/demo/dashboard/index/', 'fa fa-home', NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(2, 0,  '统计图表', '#',  'fa fa-bar-chart-o',  NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(3, 2,  '百度ECharts',  '/demo/graph/echarts/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(4, 2,  'Flot', '/demo/graph/flot/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(5, 2,  'Morris.js',  '/demo/graph/morris/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(6, 2,  'Rickshaw', '/demo/graph/rickshaw/',  NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(7, 2,  'Peity',  '/demo/graph/peity/', NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(8, 2,  'Sparkline',  '/demo/graph/sparkline/', NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(9, 2,  '图表组合', '/demo/graph/metrics/', NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(10,  0,  '信箱', '/demo/mailbox/index/', 'fa fa-envelope', NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(11,  10, '收件箱',  '/demo/mailbox/index/', NULL, 'label-warning\">16', 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(12,  10, '查看邮件', '/demo/mail/detail/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(13,  10, '写信', '/demo/mail/compose/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(14,  0,  '表单', '#',  'fa fa-edit', NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(15,  14, '基本表单', '/demo/form/basic/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(16,  14, '表单验证', '/demo/form/validate/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(17,  14, '高级插件', '/demo/form/advanced/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(18,  14, '表单向导', '/demo/form/wizard/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(19,  14, '文件上传', '#',  NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(20,  19, '百度WebUploader',  '/demo/form/webuploader/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(21,  19, 'DropzoneJS', '/demo/form/file_upload/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(22,  14, '编辑器',  '#',  NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(23,  22, '富文本编辑器', '/demo/form/editors/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(24,  22, 'simditor', '/demo/form/simditor/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(25,  22, 'MarkDown编辑器',  '/demo/form/markdown/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(26,  22, '代码编辑器',  '/demo/code/editor/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(27,  14, '日期选择器layerDate', '/demo/layerdate/index/', NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(28,  0,  '页面', '#',  'fa fa-desktop',  NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(29,  28, '联系人',  '/demo/contacts/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(30,  28, '个人资料', '/demo/profile/index/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(31,  28, '项目管理', '#',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(32,  31, '项目', '/demo/projects/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(33,  31, '项目详情', '/demo/project/detail/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(34,  28, '团队管理', '/demo/teams/board/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(35,  28, '信息流',  '/demo/social/feed/', NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(36,  28, '客户管理', '/demo/clients/index/', NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(37,  28, '文件管理器',  '/demo/file/manager/',  NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(38,  28, '日历', '/demo/calendar/index/',  NULL, NULL, 0,  80, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(39,  28, '博客', '#',  NULL, NULL, 0,  90, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(40,  39, '文章列表', '/demo/blog/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(41,  39, '文章详情', '/demo/article/index/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(42,  28, 'FAQ',  '/demo/faq/index/', NULL, NULL, 0,  100,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(43,  28, '时间轴',  '#',  NULL, NULL, 0,  110,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(44,  43, '时间轴',  '/demo/timeline/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(45,  43, '时间轴v2',  '/demo/timeline/v2/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(46,  28, '标签墙',  '/demo/pin/board/', NULL, NULL, 0,  120,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(47,  28, '单据', '#',  NULL, NULL, 0,  130,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(48,  47, '单据', '/demo/invoice/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(49,  47, '单据打印', '/demo/invoice/print/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(50,  28, '搜索结果', '/demo/search/results/',  NULL, NULL, 0,  140,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(51,  28, '论坛', '/demo/forum/main/',  NULL, NULL, 0,  150,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(52,  28, '即时通讯', '#',  NULL, NULL, 0,  160,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(53,  52, '聊天窗口', '/demo/chat/view/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(54,  28, '登录注册相关', '#',  NULL, NULL, 0,  170,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(55,  54, '登录页面', '/demo/login/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(56,  54, '登录页面v2', '/demo/login/v2/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(57,  54, '注册页面', '/demo/register/index/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(58,  54, '登录超时', '/demo/lockscreen/index/',  NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(59,  28, '404页面',  '/demo/errors/404/',  NULL, NULL, 0,  180,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(60,  28, '500页面',  '/demo/errors/500/',  NULL, NULL, 0,  190,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(61,  28, '空白页',  '/demo/empty/page/',  NULL, NULL, 0,  200,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(62,  0,  'UI元素', '#',  'fa fa-flask',  NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(63,  62, '排版', '/demo/typography/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(64,  62, '字体图标', '#',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(65,  64, 'Font Awesome', '/demo/fontawesome/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(66,  64, 'Glyphicon',  '/demo/glyphicons/index/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(67,  64, '阿里巴巴矢量图标库',  '/demo/iconfont/index/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(68,  62, '拖动排序', '#',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(69,  68, '拖动面板', '/demo/draggable/panels/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(70,  68, '任务清单', '/demo/agile/board/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(71,  62, '按钮', '/demo/buttons/index/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(72,  62, '选项卡 & 面板', '/demo/tabs/panels/', NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(73,  62, '通知 & 提示',  '/demo/notifications/index/', NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(74,  62, '徽章，标签，进度条',  '/demo/badges/labels/', NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(75,  62, '栅格', '/demo/grid/options/',  NULL, NULL, 0,  80, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(76,  62, '视频、音频',  '/demo/plyr/index/',  NULL, NULL, 0,  90, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(77,  62, '弹框插件', '#',  NULL, NULL, 0,  100,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(78,  77, 'Web弹层组件layer', '/demo/layer/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(79,  77, '模态窗口', '/demo/modal/window/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(80,  77, 'SweetAlert', '/demo/sweetalert/index/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(81,  62, '树形视图', '#',  NULL, NULL, 0,  110,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(82,  81, 'jsTree', '/demo/jstree/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(83,  81, 'Bootstrap Tree View',  '/demo/tree/view/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(84,  81, 'nestable', '/demo/nestable/list/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(85,  81, 'Toastr通知', '/demo/toastr/notifications/',  NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(86,  62, '文本对比', '/demo/diff/index/',  NULL, NULL, 0,  120,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(87,  62, '加载动画', '/demo/spinners/index/',  NULL, NULL, 0,  130,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(88,  62, '小部件',  '/demo/widgets/index/', NULL, NULL, 0,  140,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(89,  0,  '表格', '#',  'fa fa-table',  NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(90,  89, '基本表格', '/demo/table/basic/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(91,  89, 'DataTables', '/demo/table/data_tables/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(92,  89, 'jqGrid', '/demo/table/jqgrid/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(93,  89, 'Foo Tables', '/demo/table/foo_table/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(94,  89, 'Bootstrap Table',  '/demo/table/bootstrap/', NULL, 'label-primary\">强',  0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(95,  0,  '相册', '#',  'fa fa-picture-o',  NULL, 0,  80, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(96,  95, '基本图库', '/demo/basic/gallery/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(97,  95, '图片切换', '/demo/carousel/index/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(98,  95, 'Blueimp相册',  '/demo/blueimp/index/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(99,  0,  'CSS动画',  '/demo/css/animation/', 'fa fa-magic',  'label-danger\">新', 0,  90, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(100, 0,  '工具', '#',  'fa fa-cutlery',  NULL, 0,  100,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(101, 100,  '表单构建器',  '/demo/form/builder/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(102, 0,  '用户权限', '#',  'fa fa-users',  NULL, 0,  0,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  1),
(103, 0,  '用户', '', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  1),
(104, 0,  '角色', '', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  1),
(105, 0,  '菜单', '', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  1);

DROP TABLE IF EXISTS `t_privileges`;
CREATE TABLE `t_privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单',
  `operation` varchar(50) NOT NULL DEFAULT '' COMMENT '操作列表',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `depth` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '层级（深度）',
  `left_no` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '左侧编号',
  `right_no` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '右侧编号',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `menu_id` (`menu_id`) USING BTREE,
  KEY `operation` (`operation`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='实体';

INSERT INTO `t_privileges` (`id`, `menu_id`, `operation`, `remark`, `depth`, `left_no`, `right_no`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 0,  'show', '展示页面', 3,  3,  4,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(2, 0,  'list_more',  '列表翻页', 3,  5,  6,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(3, 0,  'list_find',  '列表搜索', 3,  7,  8,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(4, 0,  'detail', '查看详情', 2,  10, 11, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(5, 0,  'row_edit', '编辑行',  3,  13, 14, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(6, 0,  'row_delete', '删除行',  3,  15, 16, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(7, 0,  'row_add',  '增加行',  3,  17, 18, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(8, 0,  'all',  '所有操作', 1,  1,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(9, 0,  'list', '列表操作', 2,  2,  9,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(10,  0,  'write',  '可写操作', 2,  12, 19, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0);

DROP TABLE IF EXISTS `t_roles`;
CREATE TABLE `t_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '中文名称',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `is_super` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '超级角色，（几乎）拥有全部权限',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '创建时间',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '修改时间',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否被删除',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色';

INSERT INTO `t_roles` (`id`, `title`, `remark`, `is_super`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, '最高权限', '拥有所有菜单和权限',  1,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(2, '只读权限', '所有菜单但不能修改内容',  1,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(3, '演示权限', '演示界面下菜单',  0,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(4, '页面元素', '页面和元素两个大菜单', 0,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0);

DROP TABLE IF EXISTS `t_role_privileges`;
CREATE TABLE `t_role_privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色',
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '菜单',
  `privilege_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '权限',
  `is_revoked` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否被收回（等于role.is_super时有效）',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限';

INSERT INTO `t_role_privileges` (`id`, `role_id`, `menu_id`, `privilege_id`, `is_revoked`) VALUES
(1, 2,  99, 10, 1),
(2, 3,  1,  8,  0),
(3, 3,  2,  8,  0),
(4, 3,  10, 8,  0),
(5, 3,  14, 8,  0),
(6, 3,  28, 8,  0),
(7, 3,  62, 8,  0),
(8, 3,  89, 8,  0),
(9, 3,  95, 8,  0),
(10,  3,  99, 8,  0),
(11,  3,  100,  8,  0),
(12,  4,  28, 8,  0),
(13,  4,  62, 8,  0),
(14,  4,  9,  8,  0),
(15,  2,  97, 10, 1);

DROP TABLE IF EXISTS `t_schools`;
CREATE TABLE `t_schools` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prefix` char(2) NOT NULL DEFAULT '' COMMENT '车牌',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `s1_percent` decimal(4,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '科目一合格率',
  `s2_percent` decimal(4,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '科目二合格率',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `name` (`name`(30)),
  KEY `prefix` (`prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='学校';

INSERT INTO `t_schools` (`id`, `prefix`, `name`, `s1_percent`, `s2_percent`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, '粤C', '长胜驾校', 98.60,  79.30,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(2, '粤C', '立腾驾校', 95.10,  68.00,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(3, '粤C', '珠光吉大分校', 93.40,  63.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(4, '粤C', '珠光驾校', 92.70,  62.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(5, '粤C', '棠安驾校', 91.70,  70.70,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(6, '粤C', '新易通驾校',  95.20,  57.50,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(7, '粤C', '粤联驾校', 92.60,  51.10,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(8, '粤C', '威驾驾校', 88.60,  55.40,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(9, '粤C', '俊达驾校', 90.80,  54.10,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(10,  '粤C', '一新驾校', 92.80,  54.00,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(11,  '粤C', '裕达驾校', 91.90,  51.70,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(12,  '粤C', '蓝光驾校', 92.00,  64.80,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(13,  '粤C', '公爵驾校', 92.00,  49.20,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(14,  '粤C', '广顺驾校', 91.60,  54.80,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(15,  '粤C', '泰通驾校', 88.10,  56.30,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(16,  '粤C', '源力驾校', 92.00,  51.70,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(17,  '粤C', '鼎诚驾校', 95.80,  51.20,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(18,  '粤C', '合一驾校', 93.70,  50.30,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(19,  '粤C', '珠澳驾校', 88.20,  62.00,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(20,  '粤C', '一友驾校', 90.90,  45.10,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(21,  '粤C', '天天见驾校',  86.30,  53.70,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(22,  '粤C', '陆通驾校', 86.00,  51.10,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(23,  '粤C', '三人行驾校',  88.40,  44.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(24,  '粤C', '北理工驾校',  87.50,  48.20,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(25,  '粤C', '其昌驾校', 91.50,  44.90,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(26,  '粤C', '友仔记驾校',  89.20,  50.40,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(27,  '粤C', '日安驾校', 92.20,  45.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(28,  '粤C', '港信驾校', 90.85,  56.29,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(29,  '粤C', '柏宁驾校', 91.70,  43.70,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(30,  '粤C', '祥顺驾校', 89.40,  46.30,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(31,  '粤C', '安顺康驾校',  91.30,  45.20,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(32,  '粤C', '铭骏平沙分校', 85.70,  49.00,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(33,  '粤C', '铭骏驾校', 84.10,  48.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(34,  '粤C', '万里驾校', 88.10,  45.40,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(35,  '粤C', '顺合驾校', 92.10,  39.00,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(36,  '粤C', '安达驾校', 88.10,  43.70,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(37,  '粤C', '忠信驾校', 90.30,  45.90,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(38,  '粤C', '建华驾校', 92.80,  47.80,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(39,  '粤C', '南飞驾校', 94.10,  42.30,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(40,  '粤C', '乘昌驾校', 89.20,  36.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(41,  '粤C', '浩朋驾校', 94.10,  46.80,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(42,  '粤C', '永通驾校', 86.90,  40.00,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(43,  '粤C', '德信驾校', 90.60,  36.10,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(44,  '粤C', '安盛驾校', 93.60,  47.22,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(45,  '粤C', '顺通驾校', 89.00,  38.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(46,  '粤C', '名耀驾校', 91.00,  40.20,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(47,  '粤C', '汇利驾校', 89.20,  40.00,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(48,  '粤C', '路通驾校', 91.60,  37.40,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(49,  '粤C', '永昌驾校', 84.60,  37.50,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(50,  '粤C', '现代驾校', 89.40,  42.40,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(51,  '粤B', '深港驾校', 89.01,  47.57,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(52,  '粤B', '深港众一昊驾校',  80.40,  26.70,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(53,  '粤B', '银通驾校', 79.41,  28.51,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(54,  '粤B', '高戍达驾校',  50.00,  44.44,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(55,  '粤B', '通品驾校', 90.68,  48.99,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(56,  '粤B', '广仁驾校', 91.02,  42.36,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(57,  '粤B', '卡尔迅驾校',  82.98,  34.61,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(58,  '粤B', '方程式驾校',  75.91,  28.09,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(59,  '粤B', '深圳大学驾校', 80.42,  45.30,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(60,  '粤B', '粤港驾校', 80.47,  30.59,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(61,  '粤B', '福永照明驾校', 80.19,  35.81,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(62,  '粤B', '通达驾校', 89.71,  44.60,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(63,  '粤B', '标远驾校', 81.23,  29.30,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(64,  '粤B', '信发驾校', 85.89,  31.83,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(65,  '粤B', '鹏峰驾校', 83.05,  41.35,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(66,  '粤B', '金色里程驾校', 80.33,  27.51,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(67,  '粤B', '锦辉驾校', 91.48,  42.06,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(68,  '粤B', '畅通驾校', 81.43,  33.33,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(69,  '粤B', '新宝通驾校',  76.45,  33.47,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(70,  '粤B', '车博士驾校',  80.76,  30.84,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(71,  '粤B', '千里马驾校',  53.76,  25.95,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(72,  '粤B', '港安驾校', 88.79,  36.77,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(73,  '粤B', '二职驾校', 86.81,  45.35,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(74,  '粤B', '中港驾校', 81.19,  30.16,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(75,  '粤B', '宝华驾校', 81.93,  31.64,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(76,  '粤B', '安达诚驾校',  86.25,  40.72,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(77,  '粤B', '冠通驾校', 83.22,  36.59,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(78,  '粤B', '鹏城驾校', 92.73,  44.36,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(79,  '粤B', '东鹏驾校', 81.23,  31.05,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(80,  '粤B', '广深驾校', 80.62,  36.22,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(81,  '粤B', '天泓驾校', 78.40,  34.19,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(82,  '粤A', '穗通驾校', 77.47,  28.85,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(83,  '粤A', '学成驾校', 56.25,  50.17,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(84,  '粤A', '创丰驾校', 65.71,  17.81,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(85,  '粤A', '粤通驾校', 70.00,  32.97,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(86,  '粤A', '海珠驾校', 73.15,  42.76,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(87,  '粤A', '环球驾校', 71.83,  24.74,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(88,  '粤A', '广通驾校', 66.12,  21.25,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(89,  '粤A', '同挥驾校', 50.84,  40.62,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(90,  '粤A', '福华驾校', 87.11,  51.22,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(91,  '粤A', '正通驾校', 67.36,  38.76,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(92,  '粤A', '埔农驾校', 71.62,  24.38,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(93,  '粤A', '广安驾校', 67.28,  28.27,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(94,  '粤A', '新芳培驾校',  80.00,  31.05,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(95,  '粤A', '粤驰驾校', 39.39,  24.41,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(96,  '粤A', '同航驾校', 55.55,  35.25,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(97,  '粤A', '广新驾校', 66.66,  26.51,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(98,  '粤A', '凤安驾校', 68.51,  28.05,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(99,  '粤A', '一汽巴士驾校', 51.85,  14.28,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(100, '粤A', '安城驾校', 68.90,  25.04,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(101, '粤A', '安顺驾校', 69.67,  26.37,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(102, '粤A', '东诚驾校', 75.84,  34.49,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(103, '粤A', '达安驾校', 78.72,  47.61,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(104, '粤A', '白云龙湖驾校', 64.51,  21.58,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(105, '粤A', '盛发驾校', 75.83,  30.04,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(106, '粤A', '鸿程驾校', 78.43,  31.31,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(107, '粤A', '永通驾校', 72.39,  25.06,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(108, '粤A', '一运驾校', 56.47,  29.03,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(109, '粤A', '粤兴驾校', 68.26,  29.36,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(110, '粤A', '越秀驾校', 69.47,  26.63,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(111, '粤A', '利泰驾校', 74.50,  22.90,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(112, '粤A', '良安驾校', 70.67,  26.10,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(113, '粤A', '第二公汽驾校', 61.22,  21.31,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(114, '粤A', '白云新江高驾校',  60.11,  25.76,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(115, '粤A', '天东驾校', 69.89,  30.52,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(116, '粤A', '光大国际驾校', 69.76,  29.37,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(117, '粤A', '粤迅驾校', 59.22,  26.52,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(118, '粤A', '警辉驾校', 55.88,  26.76,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(119, '粤A', '中海驾校', 80.16,  26.14,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(120, '粤A', '粤安驾校', 75.98,  34.90,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(121, '粤A', '永安通驾校',  65.95,  30.43,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(122, '粤A', '白云区鸿华驾校',  80.68,  22.13,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(123, '粤A', '鹰式驾校', 63.76,  24.69,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(124, '粤A', '迅安驾校', 72.44,  23.52,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(125, '粤A', '交通集团驾校', 68.97,  30.14,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(126, '粤A', '程通驾校', 72.22,  26.08,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(127, '粤A', '景南驾校', 75.00,  27.79,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(128, '粤A', '华科大驾校',  78.26,  37.25,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(129, '粤A', '卡尔迅驾校',  71.91,  26.29,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(130, '粤A', '白云区交运驾校',  78.10,  22.66,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(131, '粤A', '梅山驾校', 73.51,  28.29,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(132, '粤A', '裕安驾校', 70.92,  24.64,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(133, '粤A', '锦辉驾校', 69.65,  25.80,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0),
(134, '粤A', '花都区芙蓉驾校',  62.12,  25.22,  '2018-04-30 16:00:00',  '2018-04-30 16:00:00',  0);

DROP TABLE IF EXISTS `t_scores`;
CREATE TABLE `t_scores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` int(10) unsigned NOT NULL COMMENT '科目',
  `student_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学员',
  `term` varchar(50) DEFAULT NULL COMMENT '学期',
  `score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '分数',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `subject_id` (`subject_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='成绩';

INSERT INTO `t_scores` (`id`, `subject_id`, `student_id`, `term`, `score`, `is_removed`) VALUES
(1, 1,  8,  '2018年第5周', 85, 0),
(2, 1,  4,  '2018年第5周', 85, 0),
(3, 1,  15, '2018年第5周', 85, 0),
(4, 1,  5,  '2018年第5周', 85, 0),
(5, 1,  18, '2018年第6周', 85, 0),
(6, 1,  17, '2018年第6周', 85, 0),
(7, 1,  6,  '2018年第6周', 85, 0),
(8, 1,  14, '2018年第6周', 88, 0),
(9, 1,  1,  '2018年第7周', 92, 0),
(10,  1,  11, '2018年第7周', 94, 0),
(11,  1,  7,  '2018年第7周', 97, 0),
(12,  1,  13, '2018年第7周', 96, 0),
(13,  1,  16, '2018年第8周', 97, 0),
(14,  1,  3,  '2018年第8周', 96, 0),
(15,  1,  8,  '2018年第8周', 91, 0),
(16,  5,  8,  '2018年第10周',  95, 0),
(17,  5,  4,  '2018年第7周', 90, 0),
(18,  5,  15, '2018年第7周', 95, 0),
(19,  5,  5,  '2018年第8周', 90, 0),
(20,  6,  18, '2018年第8周', 100,  0),
(21,  5,  17, '2018年第9周', 90, 0),
(22,  5,  6,  '2018年第9周', 100,  0),
(23,  5,  14, '2018年第10周',  90, 0),
(24,  6,  1,  '2018年第10周',  90, 0),
(25,  7,  8,  '2018年第12周',  90, 0),
(26,  7,  5,  '2018年第12周',  95, 0),
(27,  7,  15, '2018年第13周',  95, 0),
(28,  7,  4,  '2018年第13周',  90, 0),
(29,  8,  1,  '2018年第14周',  90, 0),
(30,  4,  8,  '2018年第14周',  93, 0),
(31,  4,  4,  '2018年第15周',  98, 0),
(32,  4,  15, '2018年第15周',  94, 0),
(33,  4,  1,  '2018年第15周',  100,  0);

DROP TABLE IF EXISTS `t_students`;
CREATE TABLE `t_students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '学校',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '姓名',
  `gender` enum('F','M','X') DEFAULT 'X' COMMENT '性别',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='学员';

INSERT INTO `t_students` (`id`, `school_id`, `name`, `gender`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 4,  '畅梅', 'F',  '2018-04-30 08:00:00',  NULL, 0),
(2, 6,  '计秀华',  'F',  '2018-04-30 08:00:00',  NULL, 0),
(3, 3,  '查琴', 'F',  '2018-04-30 08:00:00',  NULL, 0),
(4, 9,  '盛桂芝',  'F',  '2018-04-30 08:00:00',  NULL, 0),
(5, 7,  '米建华',  'M',  '2018-04-30 08:00:00',  NULL, 0),
(6, 8,  '欧阳馨予', 'F',  '2018-04-30 08:00:00',  NULL, 0),
(7, 3,  '翟佳', 'M',  '2018-04-30 08:00:00',  NULL, 0),
(8, 6,  '都刚', 'M',  '2018-04-30 08:00:00',  NULL, 0),
(9, 5,  '宗博涛',  'M',  '2018-04-30 08:00:00',  NULL, 0),
(10,  10, '萧婷', 'F',  '2018-04-30 08:00:00',  NULL, 0),
(11,  3,  '杜冬梅',  'F',  '2018-04-30 08:00:00',  NULL, 0),
(12,  5,  '聂珺', 'M',  '2018-04-30 08:00:00',  NULL, 0),
(13,  4,  '车鹰', 'M',  '2018-04-30 08:00:00',  NULL, 0),
(14,  7,  '鲁海燕',  'F',  '2018-04-30 08:00:00',  NULL, 0),
(15,  1,  '齐兰英',  'F',  '2018-04-30 08:00:00',  NULL, 0),
(16,  3,  '明倩', 'F',  '2018-04-30 08:00:00',  NULL, 0),
(17,  1,  '凌金凤',  'F',  '2018-04-30 08:00:00',  NULL, 0),
(18,  2,  '田伟', 'M',  '2018-04-30 08:00:00',  NULL, 0),
(19,  5,  '台洋', 'M',  '2018-04-30 08:00:00',  NULL, 0),
(20,  4,  '华娜', 'F',  '2018-04-30 08:00:00',  NULL, 0);

DROP TABLE IF EXISTS `t_subjects`;
CREATE TABLE `t_subjects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `max_score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '总分',
  `pass_score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '及格线',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='科目';

INSERT INTO `t_subjects` (`id`, `parent_id`, `name`, `max_score`, `pass_score`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 0,  '科目一',  100,  90, '2018-04-30 08:00:00',  NULL, 0),
(2, 0,  '科目二',  100,  90, '2018-04-30 08:00:00',  NULL, 0),
(3, 0,  '科目三',  100,  90, '2018-04-30 08:00:00',  NULL, 0),
(4, 0,  '科目四',  100,  90, '2018-04-30 08:00:00',  NULL, 0),
(5, 2,  '科目二（手动）',  100,  90, '2018-04-30 08:00:00',  NULL, 0),
(6, 2,  '科目二（自动）',  100,  90, '2018-04-30 08:00:00',  NULL, 0),
(7, 3,  '科目三（手动）',  100,  90, '2018-04-30 08:00:00',  NULL, 0),
(8, 3,  '科目三（自动）',  100,  90, '2018-04-30 08:00:00',  NULL, 0);

DROP TABLE IF EXISTS `t_users`;
CREATE TABLE `t_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(80) NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `email` varchar(50) DEFAULT NULL COMMENT '电子邮箱',
  `phone` varchar(50) DEFAULT NULL COMMENT '手机号码',
  `last_seen` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_ipaddr` varchar(15) DEFAULT NULL COMMENT '最后登录IP',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户';

INSERT INTO `t_users` (`id`, `password`, `nickname`, `email`, `phone`, `last_seen`, `last_ipaddr`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, '$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2', '管理员',  'admin@where.com',  NULL, NULL, NULL, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0);

-- 2018-07-10 20:14:57
