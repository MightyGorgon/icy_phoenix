SET storage_engine=MYISAM;

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



#####################

##UPDATE phpbb_config SET config_value = '2' WHERE config_name = 'main_admin_id';

#-- DB CHANGES FOR VERSIONING
UPDATE phpbb_config SET config_value = '2.0.4.90' WHERE config_name = 'ip_version';
UPDATE phpbb_config SET config_value = '.0.23' WHERE config_name = 'version';
UPDATE phpbb_config SET config_value = '2.0.0' WHERE config_name = 'cms_version';
UPDATE phpbb_album_config SET config_value = '1.5.0' WHERE config_name = 'fap_version';
UPDATE phpbb_config SET config_value = '2.4.5' WHERE config_name = 'attach_version';
UPDATE phpbb_config SET config_value = '3.0.7' WHERE config_name = 'upi2db_version';
