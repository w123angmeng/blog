###################################################################################################################
# Copyright (C), 2017, Hermes, Ltd.
#  File name:      2017-2-21-update-food_info_article.sql
#  Author:  wangmeng     Version: 1.0.0       DATE: 2017.2.21
#  Description:   Use this file to upgrade food_info database
#  History:
#    1. DATE:
#       Author:
#       Modification:
###################################################################################################################


###################################################################################################################
#	Upgrade date: 2017.2.21
#	Upgrade author: wangmeng 
#	Upgrade reason: #UPDATE TABLE `food_info_article`
###################################################################################################################

use `food_info`;
#update food_info_article
ALTER TABLE `food_info_article` ADD COLUMN `article_thumb` varchar(255) DEFAULT NULL COMMENT '文章索引图' AFTER `article_content`;  





