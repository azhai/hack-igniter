-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+08:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `t_accounts`;
CREATE TABLE `t_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�û�ID',
  `balance` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '���',
  `currency` varchar(20) NOT NULL DEFAULT '' COMMENT '����',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `balance` (`balance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='�˻�';

INSERT INTO `t_accounts` (`id`, `user_id`, `balance`, `currency`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	1,	12,	'COIN',	'2018-05-31 16:00:00',	'2018-07-04 02:56:38',	0);

DROP TABLE IF EXISTS `t_account_history`;
CREATE TABLE `t_account_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�˻�ID',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '���',
  `after_balance` int(10) NOT NULL DEFAULT '0' COMMENT '���º����',
  `currency` varchar(20) NOT NULL DEFAULT '' COMMENT '����',
  `cashier` varchar(30) DEFAULT NULL COMMENT '����Ա',
  `remark` text COMMENT '��ע',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  KEY `amount` (`amount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='�˻���ˮ';

INSERT INTO `t_account_history` (`id`, `account_id`, `amount`, `after_balance`, `currency`, `cashier`, `remark`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	1,	3,	3,	'COIN',	NULL,	NULL,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(2,	1,	3,	6,	'COIN',	NULL,	NULL,	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(3,	1,	3,	9,	'COIN',	NULL,	NULL,	'2018-07-04 02:56:28',	'2018-07-04 02:56:28',	0),
(4,	1,	3,	12,	'COIN',	NULL,	NULL,	'2018-07-04 02:56:38',	'2018-07-04 02:56:38',	0);

DROP TABLE IF EXISTS `t_admins`;
CREATE TABLE `t_admins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '��ɫID',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '�û���',
  `password` varchar(80) NOT NULL DEFAULT '' COMMENT '����',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '�ǳ�',
  `gender` enum('F','M','X') NOT NULL DEFAULT 'X' COMMENT '�Ա�',
  `email` varchar(50) DEFAULT NULL COMMENT '��������',
  `phone` varchar(50) DEFAULT NULL COMMENT '�ֻ�����',
  `last_seen` datetime DEFAULT NULL COMMENT '����¼ʱ��',
  `last_ipaddr` varchar(15) DEFAULT NULL COMMENT '����¼IP',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='����Ա';

INSERT INTO `t_admins` (`id`, `role_id`, `username`, `password`, `nickname`, `gender`, `email`, `phone`, `last_seen`, `last_ipaddr`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	2,	'admin',	'$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2',	'����Ա',	'M',	'admin@where.com',	NULL,	NULL,	NULL,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(2,	2,	'bob',	'$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2',	'Bob��',	'M',	'bob@where.com',	NULL,	NULL,	NULL,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(3,	3,	'cherry',	'$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2',	'Cherry��',	'F',	'cherry@where.com',	NULL,	NULL,	NULL,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(4,	3,	'david',	'$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2',	'David��',	'M',	'david@where.com',	NULL,	NULL,	NULL,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(5,	4,	'emily',	'$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2',	'Emily��',	'F',	'emily@where.com',	NULL,	NULL,	NULL,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0);

DROP TABLE IF EXISTS `t_checkins`;
CREATE TABLE `t_checkins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�û�ID',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '���',
  `expired_on` date DEFAULT NULL COMMENT '��������',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `amount` (`amount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ǩ��';

DROP TABLE IF EXISTS `t_menus`;
CREATE TABLE `t_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT '0' COMMENT '�ϼ��˵�',
  `title` varchar(50) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '��������',
  `url` varchar(30) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT '��ַ',
  `icon` varchar(30) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'ͼ��',
  `corner` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '�Ǳ�',
  `is_virtual` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '�Ƿ�����˵�',
  `seqno` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '����',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '����ʱ��',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '�޸�ʱ��',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '�Ƿ�ɾ��',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='�˵�';

INSERT INTO `t_menus` (`id`, `parent_id`, `title`, `url`, `icon`, `corner`, `is_virtual`, `seqno`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	0,	'�������',	'/demo/dashboard/index/',	'fa fa-home',	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(2,	0,	'ͳ��ͼ��',	'#',	'fa fa-bar-chart-o',	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(3,	2,	'�ٶ�ECharts',	'/demo/graph/echarts/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(4,	2,	'Flot',	'/demo/graph/flot/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(5,	2,	'Morris.js',	'/demo/graph/morris/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(6,	2,	'Rickshaw',	'/demo/graph/rickshaw/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(7,	2,	'Peity',	'/demo/graph/peity/',	NULL,	NULL,	0,	50,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(8,	2,	'Sparkline',	'/demo/graph/sparkline/',	NULL,	NULL,	0,	60,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(9,	2,	'ͼ�����',	'/demo/graph/metrics/',	NULL,	NULL,	0,	70,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(10,	0,	'����',	'/demo/mailbox/index/',	'fa fa-envelope',	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(11,	10,	'�ռ���',	'/demo/mailbox/index/',	NULL,	'label-warning\">16',	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(12,	10,	'�鿴�ʼ�',	'/demo/mail/detail/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(13,	10,	'д��',	'/demo/mail/compose/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(14,	0,	'��',	'#',	'fa fa-edit',	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(15,	14,	'������',	'/demo/form/basic/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(16,	14,	'����֤',	'/demo/form/validate/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(17,	14,	'�߼����',	'/demo/form/advanced/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(18,	14,	'����',	'/demo/form/wizard/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(19,	14,	'�ļ��ϴ�',	'#',	NULL,	NULL,	0,	50,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(20,	19,	'�ٶ�WebUploader',	'/demo/form/webuploader/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(21,	19,	'DropzoneJS',	'/demo/form/file_upload/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(22,	14,	'�༭��',	'#',	NULL,	NULL,	0,	60,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(23,	22,	'���ı��༭��',	'/demo/form/editors/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(24,	22,	'simditor',	'/demo/form/simditor/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(25,	22,	'MarkDown�༭��',	'/demo/form/markdown/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(26,	22,	'����༭��',	'/demo/code/editor/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(27,	14,	'����ѡ����layerDate',	'/demo/layerdate/index/',	NULL,	NULL,	0,	70,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(28,	0,	'ҳ��',	'#',	'fa fa-desktop',	NULL,	0,	50,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(29,	28,	'��ϵ��',	'/demo/contacts/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(30,	28,	'��������',	'/demo/profile/index/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(31,	28,	'��Ŀ����',	'#',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(32,	31,	'��Ŀ',	'/demo/projects/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(33,	31,	'��Ŀ����',	'/demo/project/detail/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(34,	28,	'�Ŷӹ���',	'/demo/teams/board/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(35,	28,	'��Ϣ��',	'/demo/social/feed/',	NULL,	NULL,	0,	50,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(36,	28,	'�ͻ�����',	'/demo/clients/index/',	NULL,	NULL,	0,	60,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(37,	28,	'�ļ�������',	'/demo/file/manager/',	NULL,	NULL,	0,	70,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(38,	28,	'����',	'/demo/calendar/index/',	NULL,	NULL,	0,	80,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(39,	28,	'����',	'#',	NULL,	NULL,	0,	90,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(40,	39,	'�����б�',	'/demo/blog/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(41,	39,	'��������',	'/demo/article/index/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(42,	28,	'FAQ',	'/demo/faq/index/',	NULL,	NULL,	0,	100,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(43,	28,	'ʱ����',	'#',	NULL,	NULL,	0,	110,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(44,	43,	'ʱ����',	'/demo/timeline/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(45,	43,	'ʱ����v2',	'/demo/timeline/v2/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(46,	28,	'��ǩǽ',	'/demo/pin/board/',	NULL,	NULL,	0,	120,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(47,	28,	'����',	'#',	NULL,	NULL,	0,	130,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(48,	47,	'����',	'/demo/invoice/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(49,	47,	'���ݴ�ӡ',	'/demo/invoice/print/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(50,	28,	'�������',	'/demo/search/results/',	NULL,	NULL,	0,	140,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(51,	28,	'��̳',	'/demo/forum/main/',	NULL,	NULL,	0,	150,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(52,	28,	'��ʱͨѶ',	'#',	NULL,	NULL,	0,	160,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(53,	52,	'���촰��',	'/demo/chat/view/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(54,	28,	'��¼ע�����',	'#',	NULL,	NULL,	0,	170,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(55,	54,	'��¼ҳ��',	'/demo/login/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(56,	54,	'��¼ҳ��v2',	'/demo/login/v2/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(57,	54,	'ע��ҳ��',	'/demo/register/index/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(58,	54,	'��¼��ʱ',	'/demo/lockscreen/index/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(59,	28,	'404ҳ��',	'/demo/errors/404/',	NULL,	NULL,	0,	180,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(60,	28,	'500ҳ��',	'/demo/errors/500/',	NULL,	NULL,	0,	190,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(61,	28,	'�հ�ҳ',	'/demo/empty/page/',	NULL,	NULL,	0,	200,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(62,	0,	'UIԪ��',	'#',	'fa fa-flask',	NULL,	0,	60,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(63,	62,	'�Ű�',	'/demo/typography/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(64,	62,	'����ͼ��',	'#',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(65,	64,	'Font Awesome',	'/demo/fontawesome/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(66,	64,	'Glyphicon',	'/demo/glyphicons/index/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(67,	64,	'����Ͱ�ʸ��ͼ���',	'/demo/iconfont/index/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(68,	62,	'�϶�����',	'#',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(69,	68,	'�϶����',	'/demo/draggable/panels/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(70,	68,	'�����嵥',	'/demo/agile/board/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(71,	62,	'��ť',	'/demo/buttons/index/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(72,	62,	'ѡ� & ���',	'/demo/tabs/panels/',	NULL,	NULL,	0,	50,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(73,	62,	'֪ͨ & ��ʾ',	'/demo/notifications/index/',	NULL,	NULL,	0,	60,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(74,	62,	'���£���ǩ��������',	'/demo/badges/labels/',	NULL,	NULL,	0,	70,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(75,	62,	'դ��',	'/demo/grid/options/',	NULL,	NULL,	0,	80,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(76,	62,	'��Ƶ����Ƶ',	'/demo/plyr/index/',	NULL,	NULL,	0,	90,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(77,	62,	'������',	'#',	NULL,	NULL,	0,	100,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(78,	77,	'Web�������layer',	'/demo/layer/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(79,	77,	'ģ̬����',	'/demo/modal/window/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(80,	77,	'SweetAlert',	'/demo/sweetalert/index/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(81,	62,	'������ͼ',	'#',	NULL,	NULL,	0,	110,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(82,	81,	'jsTree',	'/demo/jstree/index/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(83,	81,	'Bootstrap Tree View',	'/demo/tree/view/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(84,	81,	'nestable',	'/demo/nestable/list/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(85,	81,	'Toastr֪ͨ',	'/demo/toastr/notifications/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(86,	62,	'�ı��Ա�',	'/demo/diff/index/',	NULL,	NULL,	0,	120,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(87,	62,	'���ض���',	'/demo/spinners/index/',	NULL,	NULL,	0,	130,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(88,	62,	'С����',	'/demo/widgets/index/',	NULL,	NULL,	0,	140,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(89,	0,	'���',	'#',	'fa fa-table',	NULL,	0,	70,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(90,	89,	'�������',	'/demo/table/basic/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(91,	89,	'DataTables',	'/demo/table/data_tables/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(92,	89,	'jqGrid',	'/demo/table/jqgrid/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(93,	89,	'Foo Tables',	'/demo/table/foo_table/',	NULL,	NULL,	0,	40,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(94,	89,	'Bootstrap Table',	'/demo/table/bootstrap/',	NULL,	'label-primary\">ǿ',	0,	50,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(95,	0,	'���',	'#',	'fa fa-picture-o',	NULL,	0,	80,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(96,	95,	'����ͼ��',	'/demo/basic/gallery/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(97,	95,	'ͼƬ�л�',	'/demo/carousel/index/',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(98,	95,	'Blueimp���',	'/demo/blueimp/index/',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(99,	0,	'CSS����',	'/demo/css/animation/',	'fa fa-magic',	'label-danger\">��',	0,	90,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(100,	0,	'����',	'#',	'fa fa-cutlery',	NULL,	0,	100,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(101,	100,	'��������',	'/demo/form/builder/',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(102,	0,	'�û�Ȩ��',	'#',	'fa fa-users',	NULL,	0,	0,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	1),
(103,	0,	'�û�',	'',	NULL,	NULL,	0,	10,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	1),
(104,	0,	'��ɫ',	'',	NULL,	NULL,	0,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	1),
(105,	0,	'�˵�',	'',	NULL,	NULL,	0,	30,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	1);

DROP TABLE IF EXISTS `t_privileges`;
CREATE TABLE `t_privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�˵�',
  `operation` varchar(50) NOT NULL DEFAULT '' COMMENT '�����б�',
  `remark` varchar(255) DEFAULT NULL COMMENT '����',
  `depth` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '�㼶����ȣ�',
  `left_no` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�����',
  `right_no` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�Ҳ���',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '����ʱ��',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '�޸�ʱ��',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '�Ƿ�ɾ��',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `menu_id` (`menu_id`) USING BTREE,
  KEY `operation` (`operation`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ʵ��';

INSERT INTO `t_privileges` (`id`, `menu_id`, `operation`, `remark`, `depth`, `left_no`, `right_no`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	0,	'show',	'չʾҳ��',	3,	3,	4,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(2,	0,	'list_more',	'�б�ҳ',	3,	5,	6,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(3,	0,	'list_find',	'�б�����',	3,	7,	8,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(4,	0,	'detail',	'�鿴����',	2,	10,	11,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(5,	0,	'row_edit',	'�༭��',	3,	13,	14,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(6,	0,	'row_delete',	'ɾ����',	3,	15,	16,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(7,	0,	'row_add',	'������',	3,	17,	18,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(8,	0,	'all',	'���в���',	1,	1,	20,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(9,	0,	'list',	'�б����',	2,	2,	9,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(10,	0,	'write',	'��д����',	2,	12,	19,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0);

DROP TABLE IF EXISTS `t_roles`;
CREATE TABLE `t_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL DEFAULT '' COMMENT '��������',
  `remark` varchar(255) DEFAULT NULL COMMENT '����',
  `is_super` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '������ɫ����������ӵ��ȫ��Ȩ��',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '����ʱ��',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '�޸�ʱ��',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '�Ƿ�ɾ��',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='��ɫ';

INSERT INTO `t_roles` (`id`, `title`, `remark`, `is_super`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	'���Ȩ��',	'ӵ�����в˵���Ȩ��',	1,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(2,	'ֻ��Ȩ��',	'���в˵��������޸�����',	1,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(3,	'��ʾȨ��',	'��ʾ�����²˵�',	0,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0),
(4,	'ҳ��Ԫ��',	'ҳ���Ԫ��������˵�',	0,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0);

DROP TABLE IF EXISTS `t_role_privileges`;
CREATE TABLE `t_role_privileges` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '��ɫ',
  `menu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�˵�',
  `privilege_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Ȩ��',
  `is_revoked` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '�Ƿ��ջأ�����role.is_superʱ��Ч��',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `role_id` (`role_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='��ɫȨ��';

INSERT INTO `t_role_privileges` (`id`, `role_id`, `menu_id`, `privilege_id`, `is_revoked`) VALUES
(1,	2,	99,	10,	1),
(2,	3,	1,	8,	0),
(3,	3,	2,	8,	0),
(4,	3,	10,	8,	0),
(5,	3,	14,	8,	0),
(6,	3,	28,	8,	0),
(7,	3,	62,	8,	0),
(8,	3,	89,	8,	0),
(9,	3,	95,	8,	0),
(10,	3,	99,	8,	0),
(11,	3,	100,	8,	0),
(12,	4,	28,	8,	0),
(13,	4,	62,	8,	0),
(14,	4,	9,	8,	0),
(15,	2,	97,	10,	1);

DROP TABLE IF EXISTS `t_schools`;
CREATE TABLE `t_schools` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(30) DEFAULT NULL COMMENT '����',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '����',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`),
  KEY `name` (`name`(30)),
  KEY `city` (`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ѧУ';

INSERT INTO `t_schools` (`id`, `city`, `name`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	'������',	'��ۼ�У',	'2018-04-30 08:00:00',	NULL,	0),
(2,	'������',	'������У',	'2018-04-30 08:00:00',	NULL,	0),
(3,	'������',	'�۰���У',	'2018-04-30 08:00:00',	NULL,	0),
(4,	'������',	'������У',	'2018-04-30 08:00:00',	NULL,	0),
(5,	'������',	'�����У',	'2018-04-30 08:00:00',	NULL,	0),
(6,	'������',	'ͨƷ��У',	'2018-04-30 08:00:00',	NULL,	0),
(7,	'������',	'������У',	'2018-04-30 08:00:00',	NULL,	0),
(8,	'������',	'���ʼ�У',	'2018-04-30 08:00:00',	NULL,	0),
(9,	'������',	'����ʽ��У',	'2018-04-30 08:00:00',	NULL,	0),
(10,	'������',	'����У',	'2018-04-30 08:00:00',	NULL,	0);

DROP TABLE IF EXISTS `t_scores`;
CREATE TABLE `t_scores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject_id` int(10) unsigned NOT NULL COMMENT '��Ŀ',
  `student_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ѧԱ',
  `term` varchar(50) DEFAULT NULL COMMENT 'ѧ��',
  `score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `subject_id` (`subject_id`),
  KEY `student_id` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='�ɼ�';

INSERT INTO `t_scores` (`id`, `subject_id`, `student_id`, `term`, `score`, `is_removed`) VALUES
(1,	1,	8,	'2018���5��',	85,	0),
(2,	1,	4,	'2018���5��',	85,	0),
(3,	1,	15,	'2018���5��',	85,	0),
(4,	1,	5,	'2018���5��',	85,	0),
(5,	1,	18,	'2018���6��',	85,	0),
(6,	1,	17,	'2018���6��',	85,	0),
(7,	1,	6,	'2018���6��',	85,	0),
(8,	1,	14,	'2018���6��',	88,	0),
(9,	1,	1,	'2018���7��',	92,	0),
(10,	1,	11,	'2018���7��',	94,	0),
(11,	1,	7,	'2018���7��',	97,	0),
(12,	1,	13,	'2018���7��',	96,	0),
(13,	1,	16,	'2018���8��',	97,	0),
(14,	1,	3,	'2018���8��',	96,	0),
(15,	1,	8,	'2018���8��',	91,	0),
(16,	5,	8,	'2018���10��',	95,	0),
(17,	5,	4,	'2018���7��',	90,	0),
(18,	5,	15,	'2018���7��',	95,	0),
(19,	5,	5,	'2018���8��',	90,	0),
(20,	6,	18,	'2018���8��',	100,	0),
(21,	5,	17,	'2018���9��',	90,	0),
(22,	5,	6,	'2018���9��',	100,	0),
(23,	5,	14,	'2018���10��',	90,	0),
(24,	6,	1,	'2018���10��',	90,	0),
(25,	7,	8,	'2018���12��',	90,	0),
(26,	7,	5,	'2018���12��',	95,	0),
(27,	7,	15,	'2018���13��',	95,	0),
(28,	7,	4,	'2018���13��',	90,	0),
(29,	8,	1,	'2018���14��',	90,	0),
(30,	4,	8,	'2018���14��',	93,	0),
(31,	4,	4,	'2018���15��',	98,	0),
(32,	4,	15,	'2018���15��',	94,	0),
(33,	4,	1,	'2018���15��',	100,	0);

DROP TABLE IF EXISTS `t_students`;
CREATE TABLE `t_students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ѧУ',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '����',
  `gender` enum('F','M','X') DEFAULT 'X' COMMENT '�Ա�',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='ѧԱ';

INSERT INTO `t_students` (`id`, `school_id`, `name`, `gender`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	4,	'��÷',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(2,	6,	'���㻪',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(3,	3,	'����',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(4,	9,	'ʢ��֥',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(5,	7,	'�׽���',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(6,	8,	'ŷ��ܰ��',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(7,	3,	'�Լ�',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(8,	6,	'����',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(9,	5,	'�ڲ���',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(10,	10,	'����',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(11,	3,	'�Ŷ�÷',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(12,	5,	'���B',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(13,	4,	'��ӥ',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(14,	7,	'³����',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(15,	1,	'����Ӣ',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(16,	3,	'��ٻ',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(17,	1,	'����',	'F',	'2018-04-30 08:00:00',	NULL,	0),
(18,	2,	'��ΰ',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(19,	5,	'̨��',	'M',	'2018-04-30 08:00:00',	NULL,	0),
(20,	4,	'����',	'F',	'2018-04-30 08:00:00',	NULL,	0);

DROP TABLE IF EXISTS `t_subjects`;
CREATE TABLE `t_subjects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '�ϼ�',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '����',
  `max_score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '�ܷ�',
  `pass_score` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '������',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='��Ŀ';

INSERT INTO `t_subjects` (`id`, `parent_id`, `name`, `max_score`, `pass_score`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	0,	'��Ŀһ',	100,	90,	'2018-04-30 08:00:00',	NULL,	0),
(2,	0,	'��Ŀ��',	100,	90,	'2018-04-30 08:00:00',	NULL,	0),
(3,	0,	'��Ŀ��',	100,	90,	'2018-04-30 08:00:00',	NULL,	0),
(4,	0,	'��Ŀ��',	100,	90,	'2018-04-30 08:00:00',	NULL,	0),
(5,	2,	'��Ŀ�����ֶ���',	100,	90,	'2018-04-30 08:00:00',	NULL,	0),
(6,	2,	'��Ŀ�����Զ���',	100,	90,	'2018-04-30 08:00:00',	NULL,	0),
(7,	3,	'��Ŀ�����ֶ���',	100,	90,	'2018-04-30 08:00:00',	NULL,	0),
(8,	3,	'��Ŀ�����Զ���',	100,	90,	'2018-04-30 08:00:00',	NULL,	0);

DROP TABLE IF EXISTS `t_users`;
CREATE TABLE `t_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(80) NOT NULL DEFAULT '' COMMENT '����',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '�ǳ�',
  `email` varchar(50) DEFAULT NULL COMMENT '��������',
  `phone` varchar(50) DEFAULT NULL COMMENT '�ֻ�����',
  `last_seen` datetime DEFAULT NULL COMMENT '����¼ʱ��',
  `last_ipaddr` varchar(15) DEFAULT NULL COMMENT '����¼IP',
  `created_at` timestamp NULL DEFAULT NULL COMMENT '�½�',
  `changed_at` timestamp NULL DEFAULT NULL COMMENT '����',
  `is_removed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'ɾ��',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='�û�';

INSERT INTO `t_users` (`id`, `password`, `nickname`, `email`, `phone`, `last_seen`, `last_ipaddr`, `created_at`, `changed_at`, `is_removed`) VALUES
(1,	'$2y$08$/1MF1YuhbyzKBH3SQONBj.wqwK0JxqEPCdWBrE0i6qyYKA4FD7Qf2',	'����Ա',	'admin@where.com',	NULL,	NULL,	NULL,	'2018-05-31 16:00:00',	'2018-05-31 16:00:00',	0);

-- 2018-07-05 15:11:17