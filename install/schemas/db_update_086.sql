SET default_storage_engine = MYISAM;

########################################
##              BUILD 087             ##
########################################
ALTER TABLE `phpbb_posts` ADD `post_images` MEDIUMTEXT NOT NULL AFTER `post_likes`;
##UPDATE `phpbb_ajax_shoutbox` SET `shout_room` = REPLACE(`shout_room`, '||', '|');



########################################
##              BUILD 088             ##
########################################
DROP TABLE IF EXISTS `___topics_watch___`;
CREATE TABLE `___topics_watch___` (
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`notify_status` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `topic_id` (`topic_id`),
	KEY `user_id` (`user_id`),
	KEY `notify_status` (`notify_status`)
);

INSERT INTO `___topics_watch___`
SELECT tw.topic_id, tw.forum_id, tw.user_id, tw.notify_status
FROM `phpbb_topics_watch` tw
GROUP BY tw.topic_id, tw.forum_id, tw.user_id
ORDER BY tw.topic_id, tw.user_id;

DROP TABLE IF EXISTS `_old_topics_watch`;
RENAME TABLE `phpbb_topics_watch` TO `_old_topics_watch`;
RENAME TABLE `___topics_watch___` TO `phpbb_topics_watch`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('google_custom_search', '');



########################################
##              BUILD 089             ##
########################################
ALTER TABLE `phpbb_users` DROP `user_pc_timeOffsets`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('use_jquery_tags', '');



########################################
##              BUILD 090             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_ip` VARCHAR(40) NOT NULL DEFAULT '' AFTER `user_level`;
ALTER TABLE `phpbb_users` ADD `user_email_hash` BIGINT(20) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_email`;
ALTER TABLE `phpbb_users` CHANGE `user_color_group` `group_id` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_users` CHANGE COLUMN `user_email` `user_email` VARCHAR(255) DEFAULT NULL AFTER `username_clean`;
ALTER TABLE `phpbb_users` CHANGE COLUMN `user_email_hash` `user_email_hash` BIGINT(20) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_email`;
ALTER TABLE `phpbb_users` CHANGE COLUMN `user_website` `user_website` VARCHAR(255) DEFAULT NULL AFTER `user_email_hash`;
ALTER TABLE `phpbb_users` CHANGE COLUMN `user_ip` `user_ip` VARCHAR(40) DEFAULT '' AFTER `user_website`;
## rename user_color to user_colour?



########################################
##              BUILD 091             ##
########################################



########################################
##              BUILD 092             ##
########################################
##ALTER TABLE `phpbb_topics` ADD `topic_trashed` TINYINT(1) NOT NULL DEFAULT '0' AFTER `topic_status`;
##ALTER TABLE `phpbb_topics` ADD `topic_approved` TINYINT(1) NOT NULL DEFAULT '0' AFTER `topic_status`;
ALTER TABLE `phpbb_posts` ADD `post_locked` TINYINT(1) NOT NULL DEFAULT '0' AFTER `post_bluecard`;
##ALTER TABLE `phpbb_posts` ADD `post_trashed` TINYINT(1) NOT NULL DEFAULT '0' AFTER `post_locked`;
##ALTER TABLE `phpbb_posts` ADD `post_approved` TINYINT(1) NOT NULL DEFAULT '0' AFTER `post_bluecard`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('user_allow_pm_register', '1');



########################################
##              BUILD 093             ##
########################################
## EDITS ON GROUPS SUSPENDED
##ALTER TABLE `phpbb_groups` DROP `group_single_user`;
##ALTER TABLE `phpbb_auth_access` ADD `group_id` MEDIUMINT(8) NOT NULL DEFAULT '0' AFTER `group_id`;
##ALTER TABLE `phpbb_users` ADD `user_groups_ids` MEDIUMTEXT NOT NULL AFTER `user_mask`;
##ALTER TABLE `phpbb_users` ADD `user_groups_refresh` TINYINT(1) DEFAULT '0' AFTER `user_groups_ids`;
DELETE FROM `phpbb_config` WHERE `config_name` = 'admin_protect';



########################################
##              BUILD 094             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_facebook_id` VARCHAR(40) NOT NULL DEFAULT '' AFTER `user_email_hash`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_social_connect', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_facebook_login', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('facebook_app_id', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('facebook_app_secret', '');
DELETE FROM `phpbb_config` WHERE `config_name` = 'index_links';
DELETE FROM `phpbb_cms_blocks` WHERE bs_id IN (SELECT bs_id FROM `phpbb_cms_block_settings` WHERE blockfile = 'links');
DELETE FROM `phpbb_cms_block_variable` WHERE block = 'links';
DELETE FROM `phpbb_cms_block_settings` WHERE blockfile = 'links';
DELETE FROM `phpbb_cms_layout_special` WHERE page_id = 'links';
DELETE FROM `phpbb_cms_nav_menu` WHERE menu_link = 'links.php';



########################################
##              BUILD 095             ##
########################################
ALTER TABLE `phpbb_images` ADD `exif` text NOT NULL;
ALTER TABLE `phpbb_images` ADD `camera_model` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_images` ADD `lens` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_images` ADD `focal_length` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_images` ADD `exposure` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_images` ADD `aperture` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_images` ADD `iso` varchar(255) DEFAULT '' NOT NULL;



########################################
##              BUILD 096             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_vimeo` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`;
ALTER TABLE `phpbb_users` ADD `user_pinterest` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`;
ALTER TABLE `phpbb_users` ADD `user_instagram` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`;
ALTER TABLE `phpbb_users` ADD `user_github` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`;
ALTER TABLE `phpbb_users` ADD `user_500px` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`;



########################################
##              BUILD 097             ##
########################################
DELETE FROM `phpbb_config` WHERE `config_name` = 'disable_thanks_topics';
ALTER TABLE `phpbb_forums` DROP `forum_thanks`;
ALTER TABLE `phpbb_topics` ADD `topic_likes` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0' AFTER `topic_replies`;

INSERT IGNORE INTO `phpbb_posts_likes`
SELECT th.topic_id, t.topic_first_post_id, th.user_id, th.thanks_time
FROM `phpbb_thanks` th, `phpbb_topics` t
WHERE t.topic_id = th.topic_id;

ALTER IGNORE TABLE `phpbb_posts_likes` ADD UNIQUE INDEX unique_idx_name (topic_id, post_id, user_id);
ALTER IGNORE TABLE `phpbb_posts_likes` DROP INDEX unique_idx_name;
#DELETE n1 FROM `phpbb_posts_likes` n1, `phpbb_posts_likes` n2 WHERE n1.like_time > n2.like_time AND ((n1.topic_id = n2.topic_id) AND (n1.post_id = n2.post_id) AND (n1.user_id = n2.user_id));
DROP TABLE IF EXISTS `phpbb_thanks`;
DELETE pl FROM `phpbb_posts_likes` pl, `phpbb_posts` p WHERE pl.post_id = p.post_id AND pl.user_id = p.poster_id;
UPDATE `phpbb_posts` p SET p.post_likes = (SELECT COUNT(pl.post_id) FROM `phpbb_posts_likes` pl WHERE pl.post_id = p.post_id);
UPDATE `phpbb_posts` p, `phpbb_posts_likes` pl SET pl.topic_id = p.topic_id WHERE pl.post_id = p.post_id;
UPDATE `phpbb_topics` t SET t.topic_likes = (SELECT COUNT(pl.topic_id) FROM `phpbb_posts_likes` pl WHERE pl.topic_id = t.topic_id);

DELETE FROM `phpbb_config` WHERE `config_name` = 'switch_header_dropdown';
DELETE FROM `phpbb_config` WHERE `config_name` = 'xmas_fx';



########################################
##              BUILD 098             ##
########################################



########################################
##              BUILD 099             ##
########################################
ALTER TABLE `phpbb_images` ADD `post_id` MEDIUMINT(8) NOT NULL DEFAULT '0' AFTER `pic_id`;
ALTER TABLE `phpbb_images` ADD `attach_id` MEDIUMINT(8) NOT NULL DEFAULT '0' AFTER `post_id`;
DROP TABLE IF EXISTS `phpbb_google_bot_detector`;
DELETE FROM `phpbb_config` WHERE `config_name` = 'google_bot_detector';
DELETE FROM `phpbb_config` WHERE `config_name` = 'yahoo_search_savepath';
DELETE FROM `phpbb_config` WHERE `config_name` = 'yahoo_search_additional_urls';
DELETE FROM `phpbb_config` WHERE `config_name` = 'yahoo_search_compress';
DELETE FROM `phpbb_config` WHERE `config_name` = 'yahoo_search_compression_level';



########################################
##              BUILD 100             ##
########################################
ALTER TABLE `phpbb_title_infos` ADD `title_html` VARCHAR(255) NOT NULL DEFAULT '' AFTER `title_info`;
UPDATE `phpbb_title_infos` SET `title_html` = `title_info`;



########################################
##              BUILD 101             ##
########################################
ALTER TABLE `phpbb_plugins` ADD `plugin_constants` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_enabled`;
ALTER TABLE `phpbb_plugins` ADD `plugin_common` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_constants`;
ALTER TABLE `phpbb_plugins` ADD `plugin_functions` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_common`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_s_size', '120');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_list_cols', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_list_rows', '5');

UPDATE `phpbb_plugins` SET `plugin_functions` = '1' WHERE `plugin_name` = 'cash';

DELETE FROM `phpbb_cms_blocks` WHERE bs_id IN (SELECT bs_id FROM `phpbb_cms_block_settings` WHERE blockfile = 'album');
DELETE FROM `phpbb_cms_block_variable` WHERE block = 'album';
DELETE FROM `phpbb_cms_block_settings` WHERE blockfile = 'album';
DELETE FROM `phpbb_cms_layout_special` WHERE page_id = 'album';
DELETE FROM `phpbb_cms_nav_menu` WHERE menu_link = 'album.php';



########################################
##              BUILD 102             ##
########################################
UPDATE `phpbb_acl_roles` SET `role_description` = 'ROLE_PLUGINS_NOACCESS_DESCRIPTION' WHERE `role_description` = 'ROLE_PLUGINS_NOACCES_DESCRIPTIONS';

CREATE TABLE `phpbb_notifications` (
	`notification_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`notification_type_id` smallint(4) unsigned NOT NULL DEFAULT '0',
	`item_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`item_parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`notification_read` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`notification_time` int(11) unsigned NOT NULL DEFAULT '1',
	`notification_data` text COLLATE utf8_bin NOT NULL,
	PRIMARY KEY (`notification_id`),
	KEY `item_ident` (`notification_type_id`,`item_id`),
	KEY `user` (`user_id`,`notification_read`)
);



########################################
##              BUILD 103             ##
########################################
ALTER TABLE `phpbb_forums` ADD `forum_recurring_first_post` TINYINT(1) NOT NULL DEFAULT '0' AFTER `forum_rules_in_posting`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cookie_law', '0');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('privacy_policy', 'privacy_policy', 'privacy_policy.php', 0, '', 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('cookie_policy', 'cookie_policy', 'cookie_policy.php', 0, '', 0, '');



########################################
##              BUILD 104             ##
########################################



########################################
##              BUILD 105             ##
########################################



########################################
########################################
##     CONTINUE ON THE OTHER FILE     ##
########################################
########################################
