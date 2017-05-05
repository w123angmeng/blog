

SET FOREIGN_KEY_CHECKS=0;


#####################Create food_info database######################################################################
DROP DATABASE IF EXISTS food_info;
CREATE DATABASE food_info DEFAULT CHARSET utf8;
USE  food_info;
#####################Create food_info database end##################################################################

-- ----------------------------
-- Table structure for food_info_adv
-- ----------------------------
DROP TABLE IF EXISTS `food_info_adv`;
CREATE TABLE `food_info_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advname` varchar(50) DEFAULT '',
  `link` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for food_info_article
-- ----------------------------
DROP TABLE IF EXISTS `food_info_article`;
CREATE TABLE `food_info_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_title` varchar(255) NOT NULL DEFAULT '' COMMENT '文章标题',
  `article_content` longtext,
  `article_category` int(11) NOT NULL DEFAULT '0' COMMENT '文章分类',
  `article_date_v` varchar(20) NOT NULL DEFAULT '' COMMENT '虚拟发布时间',
  `article_date` varchar(20) NOT NULL DEFAULT '' COMMENT '文章发布时间',
  `article_author` varchar(20) NOT NULL DEFAULT '' COMMENT '发布作者',
  `article_readnum_v` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟阅读量',
  `article_readnum` int(11) NOT NULL DEFAULT '0' COMMENT '真实阅读量',
  `article_likenum_v` int(11) NOT NULL DEFAULT '0' COMMENT '虚拟点赞数',
  `article_likenum` int(11) NOT NULL DEFAULT '0' COMMENT '真实点赞数',
  `article_linkurl` varchar(300) NOT NULL DEFAULT '' COMMENT '阅读原文链接',
  `page_set_option_nocopy` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止复制url',
  `page_set_option_noshare_tl` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止分享至朋友圈',
  `page_set_option_noshare_msg` int(1) NOT NULL DEFAULT '0' COMMENT '页面禁止发送给好友',
  `article_keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '页面关键字',
  `article_state` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_article_title` (`article_title`),
  KEY `idx_article_content` (`article_content`(10)),
  KEY `idx_article_keyword` (`article_keyword`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='文章';

-- ----------------------------
-- Table structure for food_info_article_category
-- ----------------------------
DROP TABLE IF EXISTS `food_info_article_category`;
CREATE TABLE `food_info_article_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  PRIMARY KEY (`id`),
  KEY `idx_category_name` (`category_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='营销表单分类';

-- ----------------------------
-- Table structure for food_info_users
-- ----------------------------
DROP TABLE IF EXISTS `food_info_users`;
CREATE TABLE `food_info_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `role` varchar(30) NOT NULL DEFAULT 'admin',
  `salt` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `joindate` int(10) unsigned NOT NULL,
  `joinip` varchar(15) NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL,
  `lastip` varchar(15) NOT NULL,
  `remark` varchar(500) NOT NULL,
  `starttime` int(10) unsigned NOT NULL,
  `endtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  KEY `index_role` (`role`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;
