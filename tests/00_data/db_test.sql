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
(1, 0,  '管理面板', '/home/dashboard/index/', 'fa fa-home', NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(2, 0,  '统计图表', '#',  'fa fa-bar-chart-o',  NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(3, 2,  '百度ECharts',  '/home/graph/echarts/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(4, 2,  'Flot', '/home/graph/flot/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(5, 2,  'Morris.js',  '/home/graph/morris/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(6, 2,  'Rickshaw', '/home/graph/rickshaw/',  NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(7, 2,  'Peity',  '/home/graph/peity/', NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(8, 2,  'Sparkline',  '/home/graph/sparkline/', NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(9, 2,  '图表组合', '/home/graph/metrics/', NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(10,  0,  '信箱', '/home/mailbox/index/', 'fa fa-envelope', NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(11,  10, '收件箱',  '/home/mailbox/index/', NULL, 'label-warning\">16', 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(12,  10, '查看邮件', '/home/mail/detail/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(13,  10, '写信', '/home/mail/compose/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(14,  0,  '表单', '#',  'fa fa-edit', NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(15,  14, '基本表单', '/home/form/basic/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(16,  14, '表单验证', '/home/form/validate/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(17,  14, '高级插件', '/home/form/advanced/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(18,  14, '表单向导', '/home/form/wizard/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(19,  14, '文件上传', '#',  NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(20,  19, '百度WebUploader',  '/home/form/webuploader/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(21,  19, 'DropzoneJS', '/home/form/file_upload/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(22,  14, '编辑器',  '#',  NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(23,  22, '富文本编辑器', '/home/form/editors/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(24,  22, 'simditor', '/home/form/simditor/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(25,  22, 'MarkDown编辑器',  '/home/form/markdown/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(26,  22, '代码编辑器',  '/home/code/editor/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(27,  14, '日期选择器layerDate', '/home/layerdate/index/', NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(28,  0,  '页面', '#',  'fa fa-desktop',  NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(29,  28, '联系人',  '/home/contacts/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(30,  28, '个人资料', '/home/profile/index/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(31,  28, '项目管理', '#',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(32,  31, '项目', '/home/projects/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(33,  31, '项目详情', '/home/project/detail/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(34,  28, '团队管理', '/home/teams/board/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(35,  28, '信息流',  '/home/social/feed/', NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(36,  28, '客户管理', '/home/clients/index/', NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(37,  28, '文件管理器',  '/home/file/manager/',  NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(38,  28, '日历', '/home/calendar/index/',  NULL, NULL, 0,  80, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(39,  28, '博客', '#',  NULL, NULL, 0,  90, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(40,  39, '文章列表', '/home/blog/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(41,  39, '文章详情', '/home/article/index/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(42,  28, 'FAQ',  '/home/faq/index/', NULL, NULL, 0,  100,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(43,  28, '时间轴',  '#',  NULL, NULL, 0,  110,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(44,  43, '时间轴',  '/home/timeline/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(45,  43, '时间轴v2',  '/home/timeline/v2/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(46,  28, '标签墙',  '/home/pin/board/', NULL, NULL, 0,  120,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(47,  28, '单据', '#',  NULL, NULL, 0,  130,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(48,  47, '单据', '/home/invoice/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(49,  47, '单据打印', '/home/invoice/print/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(50,  28, '搜索结果', '/home/search/results/',  NULL, NULL, 0,  140,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(51,  28, '论坛', '/home/forum/main/',  NULL, NULL, 0,  150,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(52,  28, '即时通讯', '#',  NULL, NULL, 0,  160,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(53,  52, '聊天窗口', '/home/chat/view/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(54,  28, '登录注册相关', '#',  NULL, NULL, 0,  170,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(55,  54, '登录页面', '/home/login/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(56,  54, '登录页面v2', '/home/login/v2/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(57,  54, '注册页面', '/home/register/index/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(58,  54, '登录超时', '/home/lockscreen/index/',  NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(59,  28, '404页面',  '/home/errors/404/',  NULL, NULL, 0,  180,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(60,  28, '500页面',  '/home/errors/500/',  NULL, NULL, 0,  190,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(61,  28, '空白页',  '/home/empty/page/',  NULL, NULL, 0,  200,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(62,  0,  'UI元素', '#',  'fa fa-flask',  NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(63,  62, '排版', '/home/typography/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(64,  62, '字体图标', '#',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(65,  64, 'Font Awesome', '/home/fontawesome/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(66,  64, 'Glyphicon',  '/home/glyphicons/index/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(67,  64, '阿里巴巴矢量图标库',  '/home/iconfont/index/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(68,  62, '拖动排序', '#',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(69,  68, '拖动面板', '/home/draggable/panels/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(70,  68, '任务清单', '/home/agile/board/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(71,  62, '按钮', '/home/buttons/index/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(72,  62, '选项卡 & 面板', '/home/tabs/panels/', NULL, NULL, 0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(73,  62, '通知 & 提示',  '/home/notifications/index/', NULL, NULL, 0,  60, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(74,  62, '徽章，标签，进度条',  '/home/badges/labels/', NULL, NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(75,  62, '栅格', '/home/grid/options/',  NULL, NULL, 0,  80, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(76,  62, '视频、音频',  '/home/plyr/index/',  NULL, NULL, 0,  90, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(77,  62, '弹框插件', '#',  NULL, NULL, 0,  100,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(78,  77, 'Web弹层组件layer', '/home/layer/index/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(79,  77, '模态窗口', '/home/modal/window/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(80,  77, 'SweetAlert', '/home/sweetalert/index/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(81,  62, '树形视图', '#',  NULL, NULL, 0,  110,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(82,  81, 'jsTree', '/home/jstree/index/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(83,  81, 'Bootstrap Tree View',  '/home/tree/view/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(84,  81, 'nestable', '/home/nestable/list/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(85,  81, 'Toastr通知', '/home/toastr/notifications/',  NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(86,  62, '文本对比', '/home/diff/index/',  NULL, NULL, 0,  120,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(87,  62, '加载动画', '/home/spinners/index/',  NULL, NULL, 0,  130,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(88,  62, '小部件',  '/home/widgets/index/', NULL, NULL, 0,  140,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(89,  0,  '表格', '#',  'fa fa-table',  NULL, 0,  70, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(90,  89, '基本表格', '/home/table/basic/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(91,  89, 'DataTables', '/home/table/data_tables/', NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(92,  89, 'jqGrid', '/home/table/jqgrid/',  NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(93,  89, 'Foo Tables', '/home/table/foo_table/', NULL, NULL, 0,  40, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(94,  89, 'Bootstrap Table',  '/home/table/bootstrap/', NULL, 'label-primary\">强',  0,  50, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(95,  0,  '相册', '#',  'fa fa-picture-o',  NULL, 0,  80, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(96,  95, '基本图库', '/home/basic/gallery/', NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(97,  95, '图片切换', '/home/carousel/index/',  NULL, NULL, 0,  20, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(98,  95, 'Blueimp相册',  '/home/blueimp/index/', NULL, NULL, 0,  30, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(99,  0,  'CSS动画',  '/home/css/animation/', 'fa fa-magic',  'label-danger\">新', 0,  90, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(100, 0,  '工具', '#',  'fa fa-cutlery',  NULL, 0,  100,  '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
(101, 100,  '表单构建器',  '/home/form/builder/',  NULL, NULL, 0,  10, '2018-05-31 16:00:00',  '2018-05-31 16:00:00',  0),
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
  `city` varchar(30) DEFAULT NULL COMMENT '城市',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '名称',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '新建',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '更改',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除',
  PRIMARY KEY (`id`),
  KEY `name` (`name`(30)),
  KEY `city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='学校';

INSERT INTO `t_schools` (`id`, `city`, `name`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, '深圳市',  '深港驾校', '2018-04-30 08:00:00',  NULL, 0),
(2, '深圳市',  '鹏安驾校', '2018-04-30 08:00:00',  NULL, 0),
(3, '深圳市',  '综安驾校', '2018-04-30 08:00:00',  NULL, 0),
(4, '深圳市',  '宝华驾校', '2018-04-30 08:00:00',  NULL, 0),
(5, '深圳市',  '广深驾校', '2018-04-30 08:00:00',  NULL, 0),
(6, '深圳市',  '通品驾校', '2018-04-30 08:00:00',  NULL, 0),
(7, '深圳市',  '启辰驾校', '2018-04-30 08:00:00',  NULL, 0),
(8, '深圳市',  '广仁驾校', '2018-04-30 08:00:00',  NULL, 0),
(9, '深圳市',  '方程式驾校',  '2018-04-30 08:00:00',  NULL, 0),
(10,  '深圳市',  '天鸿驾校', '2018-04-30 08:00:00',  NULL, 0);

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

-- 2018-07-04 15:12:44
