###################################################################################################################
# Copyright (C), 2017-2017, Hercules, Ltd.
#  File name:      init.sql
#  Author:  wangfei     Version: 1.0.0       DATE: 2016.05.05
#  Description:   Use this file to init rrfx database after import a new rrfx database 
#  History:          
#    1. DATE:	
#       Author:  
#       Modification:   
###################################################################################################################


###################################################################################################################
#	Upgrade date: 2016.07.20
#	Upgrade author: wangfei
#	Upgrade reason: initial create this file
###################################################################################################################

SET FOREIGN_KEY_CHECKS=0;

USE `food_info`;

#######Clean All Tables############################################################################################
TRUNCATE food_info_adv;
TRUNCATE food_info_article;
TRUNCATE food_info_article_category;
TRUNCATE food_info_users;

#######Add system basic setting data#########################################################

#######food_info_adv################
INSERT INTO `food_info_adv` VALUES ('1', '这是第一张幻灯片', '', '', '100', '1');

#######food_info_article_category################
INSERT INTO `food_info_article_category` VALUES ('1', '国内资讯');
INSERT INTO `food_info_article_category` VALUES ('2', '国外资讯');
INSERT INTO `food_info_article_category` VALUES ('3', '热点资讯');
INSERT INTO `food_info_article_category` VALUES ('4', '食品专业知识');
INSERT INTO `food_info_article_category` VALUES ('5', '食疗养生');

#######food_info_article################
INSERT INTO `food_info_article` VALUES ('1', '国内文章-第一篇文章', '这是文章内容', '1', '', '', 'admin', '', '', '', '', '', '', '', '', '', '1');
INSERT INTO `food_info_article` VALUES ('2', '国外文章-第一篇文章', '这是文章内容', '2', '', '', 'admin', '', '', '', '', '', '', '', '', '', '1');
INSERT INTO `food_info_article` VALUES ('3', '热点资讯-第一篇文章', '这是文章内容', '3', '', '', 'admin', '', '', '', '', '', '', '', '', '', '1');
INSERT INTO `food_info_article` VALUES ('4', '食品专业知识-第一篇文章', '这是文章内容', '4', '', '', 'admin', '', '', '', '', '', '', '', '', '', '1');
INSERT INTO `food_info_article` VALUES ('5', '食品专业知识-第一篇文章', '这是文章内容', '5', '', '', 'admin', '', '', '', '', '', '', '', '', '', '1');

#######food_info_users################
INSERT INTO `food_info_users` VALUES ('1', 'admin', '', '', 'admin', '', '', '', '', '', '', '', '', '')