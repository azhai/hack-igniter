
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `schools`;
CREATE TABLE `schools` (
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

INSERT INTO `schools` (`id`, `city`, `name`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, '深圳市',  '深港驾校', '2018-04-30 16:00:00',  NULL, 0),
(2, '深圳市',  '鹏安驾校', '2018-04-30 16:00:00',  NULL, 0),
(3, '深圳市',  '综安驾校', '2018-04-30 16:00:00',  NULL, 0),
(4, '深圳市',  '宝华驾校', '2018-04-30 16:00:00',  NULL, 0),
(5, '深圳市',  '广深驾校', '2018-04-30 16:00:00',  NULL, 0),
(6, '深圳市',  '通品驾校', '2018-04-30 16:00:00',  NULL, 0),
(7, '深圳市',  '启辰驾校', '2018-04-30 16:00:00',  NULL, 0),
(8, '深圳市',  '广仁驾校', '2018-04-30 16:00:00',  NULL, 0),
(9, '深圳市',  '方程式驾校',  '2018-04-30 16:00:00',  NULL, 0),
(10,  '深圳市',  '天鸿驾校', '2018-04-30 16:00:00',  NULL, 0);

DROP TABLE IF EXISTS `scores`;
CREATE TABLE `scores` (
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

INSERT INTO `scores` (`id`, `subject_id`, `student_id`, `term`, `score`, `is_removed`) VALUES
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

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
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

INSERT INTO `students` (`id`, `school_id`, `name`, `gender`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 4,  '畅梅', 'F',  '2018-04-30 16:00:00',  NULL, 0),
(2, 6,  '计秀华',  'F',  '2018-04-30 16:00:00',  NULL, 0),
(3, 3,  '查琴', 'F',  '2018-04-30 16:00:00',  NULL, 0),
(4, 9,  '盛桂芝',  'F',  '2018-04-30 16:00:00',  NULL, 0),
(5, 7,  '米建华',  'M',  '2018-04-30 16:00:00',  NULL, 0),
(6, 8,  '欧阳馨予', 'F',  '2018-04-30 16:00:00',  NULL, 0),
(7, 3,  '翟佳', 'M',  '2018-04-30 16:00:00',  NULL, 0),
(8, 6,  '都刚', 'M',  '2018-04-30 16:00:00',  NULL, 0),
(9, 5,  '宗博涛',  'M',  '2018-04-30 16:00:00',  NULL, 0),
(10,  10, '萧婷', 'F',  '2018-04-30 16:00:00',  NULL, 0),
(11,  3,  '杜冬梅',  'F',  '2018-04-30 16:00:00',  NULL, 0),
(12,  5,  '聂珺', 'M',  '2018-04-30 16:00:00',  NULL, 0),
(13,  4,  '车鹰', 'M',  '2018-04-30 16:00:00',  NULL, 0),
(14,  7,  '鲁海燕',  'F',  '2018-04-30 16:00:00',  NULL, 0),
(15,  1,  '齐兰英',  'F',  '2018-04-30 16:00:00',  NULL, 0),
(16,  3,  '明倩', 'F',  '2018-04-30 16:00:00',  NULL, 0),
(17,  1,  '凌金凤',  'F',  '2018-04-30 16:00:00',  NULL, 0),
(18,  2,  '田伟', 'M',  '2018-04-30 16:00:00',  NULL, 0),
(19,  5,  '台洋', 'M',  '2018-04-30 16:00:00',  NULL, 0),
(20,  4,  '华娜', 'F',  '2018-04-30 16:00:00',  NULL, 0);

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE `subjects` (
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

INSERT INTO `subjects` (`id`, `parent_id`, `name`, `max_score`, `pass_score`, `created_at`, `changed_at`, `is_removed`) VALUES
(1, 0,  '科目一',  100,  90, '2018-04-30 16:00:00',  NULL, 0),
(2, 0,  '科目二',  100,  90, '2018-04-30 16:00:00',  NULL, 0),
(3, 0,  '科目三',  100,  90, '2018-04-30 16:00:00',  NULL, 0),
(4, 0,  '科目四',  100,  90, '2018-04-30 16:00:00',  NULL, 0),
(5, 2,  '科目二（手动）',  100,  90, '2018-04-30 16:00:00',  NULL, 0),
(6, 2,  '科目二（自动）',  100,  90, '2018-04-30 16:00:00',  NULL, 0),
(7, 3,  '科目三（手动）',  100,  90, '2018-04-30 16:00:00',  NULL, 0),
(8, 3,  '科目三（自动）',  100,  90, '2018-04-30 16:00:00',  NULL, 0);
