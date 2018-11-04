SET default_storage_engine = MYISAM;

########################################
##              BUILD 106             ##
########################################
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AhrefsBot', '', 'AhrefsBot/', '');
UPDATE `phpbb_bots` SET bot_agent = 'DotBot/' WHERE bot_name = 'DotBot';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_size_max_mp', '1');

DROP TABLE IF EXISTS `___topics_labels___`;
CREATE TABLE `___topics_labels___` (
	`id` INT(11) NOT NULL auto_increment,
	`label_name` VARCHAR(255) NOT NULL DEFAULT '',
	`label_code` VARCHAR(255) NOT NULL DEFAULT '',
	`label_code_switch` TINYINT(1) DEFAULT '0',
	`label_bg_color` VARCHAR(255) NOT NULL DEFAULT '',
	`label_text_color` VARCHAR(255) NOT NULL DEFAULT '',
	`label_icon` VARCHAR(255) NOT NULL DEFAULT '',
	`date_format` VARCHAR(25) DEFAULT NULL,
	`admin_auth` TINYINT(1) DEFAULT '0',
	`mod_auth` TINYINT(1) DEFAULT '0',
	`poster_auth` TINYINT(1) DEFAULT '0',
	PRIMARY KEY `id` (`id`)
);

INSERT INTO `___topics_labels___`
SELECT tt.id, tt.title_info, tt.title_html, '0', '', '', '', tt.date_format, tt.admin_auth, tt.mod_auth, tt.poster_auth
FROM `phpbb_title_infos` tt
ORDER BY tt.id;

DROP TABLE IF EXISTS `_old_topics_labels_`;
RENAME TABLE `phpbb_title_infos` TO `_old_topics_labels`;
RENAME TABLE `___topics_labels___` TO `phpbb_topics_labels`;

UPDATE `phpbb_topics_labels` SET `label_code` = `label_name` WHERE `label_code` = '';

ALTER TABLE `phpbb_topics` ADD `topic_label_id` MEDIUMINT(8) NOT NULL DEFAULT '0' AFTER `topic_attachment`;
#ALTER TABLE `phpbb_topics` ADD `topic_label_d` INT(11) unsigned NOT NULL DEFAULT '0' AFTER `topic_label_id`;
#ALTER TABLE `phpbb_topics` ADD `topic_label_u` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0' AFTER `topic_label_d`;
ALTER TABLE `phpbb_topics` CHANGE `title_compl_infos` `topic_label_compiled` VARCHAR(255) NULL DEFAULT NULL AFTER `topic_label_id`;



########################################
##              BUILD 107             ##
########################################



########################################
##              BUILD 108             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_google_id` VARCHAR(40) NOT NULL DEFAULT '' AFTER `user_facebook_id`;



########################################
##              BUILD 109             ##
########################################



########################################
##              BUILD 110             ##
########################################
ALTER TABLE `phpbb_plugins` ADD `plugin_class` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_functions`;



########################################
##              BUILD 110             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_privacy_policy_notify` TINYINT(2) NOT NULL DEFAULT 0 AFTER `user_popup_pm`;
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_name', 'My Name');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_site', 'My Site');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_company', 'My Company');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_details', 'My Details');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_address', 'My Address');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_server', 'My Server');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_data', 'My Data Owner');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('owner_data_address', 'My Data Owner Address');





#####################

##UPDATE phpbb_config SET config_value = '2' WHERE config_name = 'main_admin_id';

#-- DB CHANGES FOR VERSIONING
UPDATE phpbb_config SET config_value = '2.2.2.110' WHERE config_name = 'ip_version';
UPDATE phpbb_config SET config_value = '.0.23' WHERE config_name = 'version';
UPDATE phpbb_config SET config_value = '2.0.0' WHERE config_name = 'cms_version';
UPDATE phpbb_album_config SET config_value = '1.5.0' WHERE config_name = 'fap_version';
UPDATE phpbb_config SET config_value = '2.4.5' WHERE config_name = 'attach_version';
UPDATE phpbb_config SET config_value = '3.0.7' WHERE config_name = 'upi2db_version';
