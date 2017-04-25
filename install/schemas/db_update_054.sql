SET default_storage_engine = MYISAM;

########################################
##              BUILD 054             ##
########################################
INSERT INTO `phpbb_cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('gheader', 'gh', 0);
INSERT INTO `phpbb_cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('gfooter', 'gf', 0);

## TICKETS - BEGIN
CREATE TABLE phpbb_tickets_cat (
	ticket_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	ticket_cat_title VARCHAR(255) NOT NULL DEFAULT '',
	ticket_cat_des TEXT NOT NULL,
	ticket_cat_emails TEXT NOT NULL,
	PRIMARY KEY (ticket_cat_id)
);
INSERT INTO phpbb_tickets_cat (ticket_cat_title, ticket_cat_des, ticket_cat_emails) VALUES ('General', 'General', '');
## TICKETS - END
UPDATE `phpbb_cms_layout_special` SET page_id = 'viewforum' WHERE page_id = 'viewfforum';



########################################
##              BUILD 055             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('forum_limit_edit_time_interval', '1440');

ALTER TABLE `phpbb_forums` CHANGE `cat_id` `parent_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_categories` CHANGE `cat_title` `cat_title` varchar(255) DEFAULT NULL;
ALTER TABLE `phpbb_forums` CHANGE `forum_name` `forum_name` varchar(255) DEFAULT NULL;
ALTER TABLE `phpbb_groups` CHANGE `group_color` `group_color` VARCHAR(16) NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `username` `username` varchar(36) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_color` `user_color` VARCHAR(16) NOT NULL;

ALTER TABLE `phpbb_categories` ADD `cat_title_clean` varchar(255) DEFAULT '' NOT NULL AFTER `cat_title`;

ALTER TABLE `phpbb_forums` ADD `forum_type` tinyint(4) DEFAULT '0' NOT NULL AFTER `forum_id`;
ALTER TABLE `phpbb_forums` ADD `forum_parents` MEDIUMTEXT NOT NULL AFTER `main_type`;
ALTER TABLE `phpbb_forums` ADD `right_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `main_type`;
ALTER TABLE `phpbb_forums` ADD `left_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `main_type`;

ALTER TABLE `phpbb_forums` ADD `forum_name_clean` varchar(255) DEFAULT '' NOT NULL AFTER `forum_name`;
ALTER TABLE `phpbb_forums` ADD `forum_limit_edit_time` tinyint(1) default '0' NOT NULL AFTER `forum_notify`;

ALTER TABLE `phpbb_forums` ADD `forum_last_topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `forum_topics`;
ALTER TABLE `phpbb_forums` ADD `forum_last_poster_color` varchar(16) DEFAULT '' NOT NULL AFTER `forum_last_post_id`;
ALTER TABLE `phpbb_forums` ADD `forum_last_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `forum_last_post_id`;
ALTER TABLE `phpbb_forums` ADD `forum_last_post_time` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `forum_last_post_id`;
ALTER TABLE `phpbb_forums` ADD `forum_last_post_subject` varchar(255) DEFAULT '' NOT NULL AFTER `forum_last_post_id`;
ALTER TABLE `phpbb_forums` ADD `forum_last_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `forum_last_post_id`;

ALTER TABLE `phpbb_topics` ADD `topic_tags` varchar(255) DEFAULT '' NOT NULL AFTER `topic_title`;
ALTER TABLE `phpbb_topics` ADD `topic_similar_topics` varchar(255) DEFAULT '' NOT NULL AFTER `topic_desc`;

ALTER TABLE `phpbb_topics` ADD `topic_title_clean` varchar(255) DEFAULT '' NOT NULL AFTER `topic_title`;
ALTER TABLE `phpbb_topics` ADD `topic_ftitle_clean` varchar(255) DEFAULT '' NOT NULL AFTER `topic_title_clean`;

ALTER TABLE `phpbb_topics` ADD `topic_first_post_time` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_first_post_id`;
ALTER TABLE `phpbb_topics` ADD `topic_first_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_first_post_time`;
ALTER TABLE `phpbb_topics` ADD `topic_first_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `topic_first_poster_id`;
ALTER TABLE `phpbb_topics` ADD `topic_first_poster_color` varchar(16) DEFAULT '' NOT NULL AFTER `topic_first_poster_name`;
ALTER TABLE `phpbb_topics` ADD `topic_last_post_time` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_last_post_id`;
ALTER TABLE `phpbb_topics` ADD `topic_last_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_last_post_time`;
ALTER TABLE `phpbb_topics` ADD `topic_last_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `topic_last_post_id`;
ALTER TABLE `phpbb_topics` ADD `topic_last_poster_color` varchar(16) DEFAULT '' NOT NULL AFTER `topic_last_poster_name`;

ALTER TABLE `phpbb_users` ADD `username_clean` varchar(255) DEFAULT '' NOT NULL AFTER `username`;

UPDATE `phpbb_forums` SET forum_type = 1;

UPDATE `phpbb_forums` f, `phpbb_topics` t, `phpbb_posts` p, `phpbb_users` u SET f.forum_last_topic_id = p.topic_id, f.forum_last_poster_id = p.poster_id, f.forum_last_post_subject = t.topic_title, f.forum_last_post_time = p.post_time, f.forum_last_poster_name = u.username, f.forum_last_poster_color = u.user_color
WHERE f.forum_last_post_id = p.post_id
AND t.topic_id = p.topic_id
AND p.poster_id = u.user_id;

UPDATE `phpbb_topics` t, `phpbb_posts` p, `phpbb_posts` p2, `phpbb_users` u, `phpbb_users` u2 SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
WHERE t.topic_first_post_id = p.post_id
AND p.poster_id = u.user_id
AND t.topic_last_post_id = p2.post_id
AND p2.poster_id = u2.user_id;

CREATE TABLE `phpbb_topics_tags_list` (
	`tag_text` varchar(50) binary NOT NULL DEFAULT '',
	`tag_id` mediumint(8) unsigned NOT NULL auto_increment,
	`tag_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`tag_text`),
	KEY `tag_id` (`tag_id`)
);

CREATE TABLE `phpbb_topics_tags_match` (
	`tag_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	KEY `tag_id` (`tag_id`),
	KEY `topic_id` (`topic_id`)
);

INSERT INTO `phpbb_config`
SELECT x.config_name, x.config_value
FROM `phpbb_xs_news_cfg` x;

RENAME TABLE `phpbb_xs_news_cfg` TO `_old_phpbb_xs_news_cfg`;

ALTER TABLE `phpbb_ina_scores` ADD `user_plays` int(6) default '0' AFTER `score`;
ALTER TABLE `phpbb_ina_scores` ADD `play_time` int(11) default '0' AFTER `user_plays`;

UPDATE `phpbb_cms_blocks` SET blockfile = REPLACE(blockfile,'blocks_imp_','');

DELETE FROM `phpbb_config` WHERE config_name = 'smart_header';



########################################
##              BUILD 056             ##
########################################
DELETE FROM `phpbb_config` WHERE config_name = 'disable_ftr';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_topic_number', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_message', 'Before going on... please make sure you have read and understood this post. It contains important informations regarding this site.');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_install_time', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_disable', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftr_all_users', '0');

DROP TABLE `phpbb_force_read`;
ALTER TABLE `phpbb_force_read_users` DROP `read`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_html_only_for_admins', '0');



########################################
##              BUILD 057             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_tags_box', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_moderators_edit_tags', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_custom_bbcodes', '0');

CREATE TABLE `phpbb_bbcodes` (
	bbcode_id mediumint(8) UNSIGNED NOT NULL auto_increment,
	bbcode_tag varchar(16) DEFAULT '' NOT NULL,
	bbcode_helpline varchar(255) DEFAULT '' NOT NULL,
	display_on_posting tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	bbcode_match text NOT NULL,
	bbcode_tpl mediumtext NOT NULL,
	first_pass_match mediumtext NOT NULL,
	first_pass_replace mediumtext NOT NULL,
	second_pass_match mediumtext NOT NULL,
	second_pass_replace mediumtext NOT NULL,
	PRIMARY KEY (bbcode_id),
	KEY display_on_post (display_on_posting)
);

ALTER TABLE `phpbb_ajax_shoutbox` ADD `shout_room` VARCHAR(255) NOT NULL DEFAULT '';

CREATE TABLE phpbb_plugins (
	plugin_name VARCHAR(255) NOT NULL DEFAULT '',
	plugin_dir VARCHAR(255) NOT NULL DEFAULT '',
	plugin_enabled tinyint(2) NOT NULL DEFAULT 0,
	PRIMARY KEY (plugin_name)
);



########################################
##              BUILD 058             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('forum_tags_type', '0');
ALTER TABLE `phpbb_users` ADD `user_private_chat_alert` VARCHAR(255) NOT NULL AFTER `user_last_privmsg`;



########################################
##              BUILD 059             ##
########################################
CREATE TABLE `phpbb_plugins_config` (
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` TEXT NOT NULL,
	PRIMARY KEY (`config_name`)
);



########################################
##              BUILD 060             ##
########################################
ALTER TABLE `phpbb_users` CHANGE `user_login_tries` `user_login_attempts` TINYINT(4) DEFAULT '0' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_last_login_try` `user_last_login_attempt` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_users` CHANGE `user_password` `user_password` VARCHAR(40) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_newpasswd` `user_newpasswd` VARCHAR(40) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` ADD `user_passchg` INT(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_password`;
ALTER TABLE `phpbb_users` ADD `user_pass_convert` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_passchg`;
ALTER TABLE `phpbb_users` ADD `user_form_salt` VARCHAR(32) DEFAULT '' NOT NULL AFTER `user_pass_convert`;
ALTER TABLE `phpbb_users` ADD `user_email_hash` BIGINT(20) DEFAULT '0' NOT NULL AFTER `user_email`;
ALTER TABLE `phpbb_users` ADD `user_options` INT(11) UNSIGNED DEFAULT '895' NOT NULL AFTER `user_setbm`;

ALTER TABLE `phpbb_users` DROP `ct_last_pw_reset`;
ALTER TABLE `phpbb_users` DROP `ct_last_pw_change`;
ALTER TABLE `phpbb_users` DROP `ct_login_count`;
ALTER TABLE `phpbb_users` DROP `ct_login_vconfirm`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_enabled', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_logsize', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_auto_recovery', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_vconfirm_guest', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_autoban_mails', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_guest', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_user', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_guest', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_user', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_protection', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_protection', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_blocktime', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_lastip', '0.0.0.0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pwreset_time', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_time', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_time', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_postcount', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_blockmode', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_loginfeature', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_reset_feature', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_last_reg', '1155944976');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_history', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_history_count', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_ip_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_validity', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex_min', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex_mode', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_control', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_last_file_scan', '1156000091');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_last_checksum_scan', '1156000082');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_logsize_logins', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_logsize_spammer', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_ip_scan', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_global_message', 'Hello world!');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_global_message_type', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_feature_enabled', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spam_attack_boost', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spam_keyword_det', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_footer_layout', '6');

DELETE FROM `phpbb_config` WHERE config_name = 'ctracker_detect_misconfiguration';

DROP TABLE `phpbb_ctracker_config`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upload_dir', 'files');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upload_img', 'images/attach_post.png');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('topic_icon', 'images/disk_multiple.png');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_order', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_filesize', '262144');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attachment_quota', '52428800');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_filesize_pm', '262144');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_attachments', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('max_attachments_pm', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_attachments_mod', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_pm_attach', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attachment_topic_review', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_ftp_upload', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_apcp', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attach_version', '2.4.5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_upload_quota', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('default_pm_quota', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_server', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_path', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('download_path', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_user', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_pass', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ftp_pasv_mode', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_display_inlined', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_max_width', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_max_height', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_link_width', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_link_height', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_create_thumbnail', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_min_thumb_filesize', '12000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('img_imagick', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('use_gd2', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wma_autoplay', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('flash_autoplay', '0');

DROP TABLE `phpbb_attachments_config`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_site_history_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_site_history_last_run', '0');

## AUTH SYSTEM - BEGIN
CREATE TABLE `phpbb_acl_groups` (
	`group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_setting` tinyint(2) NOT NULL DEFAULT '0',
	KEY `group_id` (`group_id`),
	KEY `auth_opt_id` (`auth_option_id`),
	KEY `auth_role_id` (`auth_role_id`)
);

CREATE TABLE `phpbb_acl_options` (
	`auth_option_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`auth_option` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
	`is_global` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`is_local` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`founder_only` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`auth_option_id`),
	UNIQUE KEY `auth_option` (`auth_option`)
);

CREATE TABLE `phpbb_acl_roles` (
	`role_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`role_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
	`role_description` text COLLATE utf8_bin NOT NULL,
	`role_type` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
	`role_order` smallint(4) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`role_id`),
	KEY `role_type` (`role_type`),
	KEY `role_order` (`role_order`)
);

CREATE TABLE `phpbb_acl_roles_data` (
	`role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_setting` tinyint(2) NOT NULL DEFAULT '0',
	PRIMARY KEY (`role_id`,`auth_option_id`),
	KEY `ath_op_id` (`auth_option_id`)
);

CREATE TABLE `phpbb_acl_users` (
	`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_setting` tinyint(2) NOT NULL DEFAULT '0',
	KEY `user_id` (`user_id`),
	KEY `auth_option_id` (`auth_option_id`),
	KEY `auth_role_id` (`auth_role_id`)
);
## AUTH SYSTEM - END

## DB FIX FOR HTMLSPECIALCHARS AND SLASHES
## Created a function to convert all unescaped data to the new format... but it's tricky... pay attention!
##function sql_replace($table, $fields, $html_encode = true, $stripslashes = false)



########################################
##              BUILD 061             ##
########################################
ALTER TABLE `phpbb_users` DROP `ct_last_mail`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_width', '316');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_height', '61');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_background_color', '#E5ECF9');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_jpeg', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_jpeg_quality', '50');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_pre_letters', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_pre_letters_great', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_font', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_chess', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_ellipses', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_arcs', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_lines', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_image', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_gammacorrect', '1.4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_foreground_lattice_x', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_foreground_lattice_y', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('captcha_lattice_color', '#FFFFFF');

DROP TABLE `phpbb_captcha_config`;

TRUNCATE TABLE `phpbb_hacks_list`;

UPDATE `phpbb_users` SET `user_pass_convert` = '1';

DELETE FROM `phpbb_config` WHERE config_name = 'thumbnail_lightbox';
DELETE FROM `phpbb_album_config`` WHERE config_name = 'enable_mooshow';



########################################
##              BUILD 062             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smtp_port', '25');
ALTER TABLE `phpbb_plugins` ADD `plugin_version` VARCHAR(255) NOT NULL DEFAULT '' AFTER `plugin_name`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cms_version', '2.0.0');

## NEW CMS - BEGIN
CREATE TABLE `phpbb_cms_block_settings` (
	`bs_id` int(10) NOT NULL AUTO_INCREMENT,
	`user_id` int(10) NOT NULL,
	`name` varchar(255) NOT NULL default '',
	`content` text NOT NULL ,
	`blockfile` varchar(255) NOT NULL default '',
	`view` tinyint(1) NOT NULL default 0,
	`type` tinyint(1) NOT NULL default 1,
	`groups` tinytext NOT NULL,
	`locked` tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (`bs_id`)
);

INSERT INTO `phpbb_cms_block_settings`
SELECT b.bid, 0, b.title, b.content, b.blockfile, b.view, b.type, b.groups, 1
FROM `phpbb_cms_blocks` b
ORDER BY b.bid;

ALTER TABLE `phpbb_cms_blocks` DROP `content`;
ALTER TABLE `phpbb_cms_blocks` DROP `blockfile`;
ALTER TABLE `phpbb_cms_blocks` DROP `view`;
ALTER TABLE `phpbb_cms_blocks` DROP `type`;
ALTER TABLE `phpbb_cms_blocks` DROP `groups`;

ALTER TABLE `phpbb_cms_blocks` ADD `bs_id` int(10) UNSIGNED NOT NULL AFTER `bid`;
ALTER TABLE `phpbb_cms_blocks` ADD `block_cms_id` int(10) UNSIGNED NOT NULL AFTER `bs_id`;

UPDATE `phpbb_cms_blocks` SET `bs_id` = `bid`;

ALTER TABLE `phpbb_cms_layout` ADD `layout_cms_id` int(10) UNSIGNED NOT NULL AFTER `template`;
## NEW CMS - END



########################################
##              BUILD 063             ##
########################################
ALTER TABLE `phpbb_topics_watch` ADD `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `topic_id`;
UPDATE `phpbb_topics_watch` tw, `phpbb_topics` t SET tw.forum_id = t.forum_id WHERE tw.topic_id = t.topic_id;



########################################
##              BUILD 064             ##
########################################
DROP TABLE `phpbb_confirm`;

CREATE TABLE `phpbb_confirm` (
	confirm_id char(32) DEFAULT '' NOT NULL,
	session_id char(32) DEFAULT '' NOT NULL,
	confirm_type tinyint(3) DEFAULT '0' NOT NULL,
	code varchar(8) DEFAULT '' NOT NULL,
	seed int(10) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (session_id, confirm_id),
	KEY confirm_type (confirm_type)
);

##### POLL CONVERSION - BEGIN
# Table: 'phpbb_poll_options'
CREATE TABLE `phpbb_poll_options` (
	poll_option_id tinyint(4) DEFAULT '0' NOT NULL,
	topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	poll_option_text text NOT NULL,
	poll_option_total mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	KEY poll_opt_id (poll_option_id),
	KEY topic_id (topic_id)
);

# Table: 'phpbb_poll_votes'
CREATE TABLE `phpbb_poll_votes` (
	topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	poll_option_id tinyint(4) DEFAULT '0' NOT NULL,
	vote_user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	vote_user_ip varchar(40) DEFAULT '' NOT NULL,
	KEY topic_id (topic_id),
	KEY vote_user_id (vote_user_id),
	KEY vote_user_ip (vote_user_ip)
);

##ADD
ALTER TABLE `phpbb_topics` ADD `poll_title` varchar(255) DEFAULT '' NOT NULL AFTER `topic_type`;
ALTER TABLE `phpbb_topics` ADD `poll_start` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_title`;
ALTER TABLE `phpbb_topics` ADD `poll_length` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_start`;
ALTER TABLE `phpbb_topics` ADD `poll_max_options` tinyint(4) DEFAULT '1' NOT NULL AFTER `poll_length`;
ALTER TABLE `phpbb_topics` ADD `poll_last_vote` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_max_options`;
ALTER TABLE `phpbb_topics` ADD `poll_vote_change` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_last_vote`;

UPDATE phpbb_topics t, phpbb_vote_desc vd
SET t.poll_title = vd.vote_text, t.poll_start = vd.vote_start, t.poll_length = vd.vote_length, t.poll_max_options = 1, t.poll_vote_change = 0
WHERE t.topic_vote = 1
AND vd.topic_id = t.topic_id;

INSERT INTO `phpbb_poll_options`
SELECT vr.vote_option_id, vd.topic_id, vr.vote_option_text, vr.vote_result
FROM `phpbb_vote_desc` vd, `phpbb_vote_results` vr
WHERE vr.vote_id = vd.vote_id
ORDER BY vd.topic_id ASC, vd.vote_id ASC, vr.vote_option_id ASC;

INSERT INTO `phpbb_poll_votes`
SELECT vd.topic_id, vv.vote_cast, vv.vote_user_id, vv.vote_user_ip
FROM `phpbb_vote_desc` vd, `phpbb_vote_voters` vv
WHERE vd.vote_id = vv.vote_id
ORDER BY vd.topic_id ASC, vv.vote_user_id ASC;

##REMOVE
ALTER TABLE `phpbb_topics` DROP `topic_vote`;
DROP TABLE `phpbb_vote_desc`;
DROP TABLE `phpbb_vote_results`;
DROP TABLE `phpbb_vote_voters`;
##### POLL CONVERSION - END

ALTER TABLE `phpbb_users` ADD `user_jabber` varchar(255) DEFAULT '' NOT NULL AFTER `user_icq`;
ALTER TABLE `phpbb_users` CHANGE `user_aim` `user_aim` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_icq` `user_icq` varchar(15) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_msnm` `user_msnm` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_skype` `user_skype` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_yim` `user_yim` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_interests` `user_interests` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_users` CHANGE `user_occ` `user_occ` varchar(255) DEFAULT '' NOT NULL;

#### POST LIKE - BEGIN
CREATE TABLE `phpbb_posts_likes` (
	topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	like_time  int(11) UNSIGNED DEFAULT '0' NOT NULL,
	KEY topic_id (topic_id),
	KEY post_id (post_id),
	KEY user_id (user_id)
);

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_likes_posts', '1');
ALTER TABLE `phpbb_forums` ADD `forum_likes` tinyint(1) NOT NULL DEFAULT '0' AFTER `forum_postcount`;
ALTER TABLE `phpbb_posts` ADD `post_likes` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `post_bluecard`;
#### POST LIKE - END

UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, '\\"', '\"');
UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, "\\'", "\'");



########################################
##              BUILD 065             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_first_name` varchar(255) DEFAULT '' NOT NULL AFTER `username_clean`;
ALTER TABLE `phpbb_users` ADD `user_last_name` varchar(255) DEFAULT '' NOT NULL AFTER `user_first_name`;
ALTER TABLE `phpbb_users` ADD `user_facebook` varchar(255) DEFAULT '' NOT NULL AFTER `user_yim`;
ALTER TABLE `phpbb_users` ADD `user_twitter` varchar(255) DEFAULT '' NOT NULL AFTER `user_facebook`;



########################################
##              BUILD 066             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_type` tinyint(2) DEFAULT '0' NOT NULL AFTER `user_regdate`;
ALTER TABLE `phpbb_users` ADD `user_mask` tinyint(1) DEFAULT '0' NOT NULL AFTER `user_active`;



########################################
##              BUILD 067             ##
########################################
ALTER TABLE `phpbb_ranks` ADD `rank_show_title` tinyint(1) DEFAULT '1' NOT NULL AFTER `rank_special`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ip_admins_only', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('attachments_stats', '0');



########################################
##              BUILD 068             ##
########################################
ALTER TABLE `phpbb_cms_blocks` CHANGE `block_settings_id` `bs_id` INT(10) UNSIGNED NOT NULL;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_lock_hour', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_birthdays_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_birthdays_last_run', '0');
DELETE FROM `phpbb_config` WHERE config_name = 'enable_digests';
DELETE FROM `phpbb_config` WHERE config_name = 'digests_php_cron';
DELETE FROM `phpbb_config` WHERE config_name = 'digests_php_cron_lock';
DELETE FROM `phpbb_config` WHERE config_name = 'digests_last_send_time';

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('robots_index_topics_no_replies', '1');

ALTER TABLE `phpbb_rate_results` CHANGE `rating_time` `rating_time` int(11) NOT NULL DEFAULT '0';

## SESSIONS - BEGIN

ALTER TABLE `phpbb_users` ADD `user_permissions` mediumtext NOT NULL AFTER `user_mask`;
ALTER TABLE `phpbb_users` ADD `user_perm_from` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_permissions`;
ALTER TABLE `phpbb_users` CHANGE `user_http_agents` `user_browser` varchar(255) DEFAULT '' NOT NULL;

ALTER TABLE `phpbb_attachments_stats` CHANGE `user_http_agents` `user_browser` varchar(255) DEFAULT '' NOT NULL;

CREATE TABLE `___sessions___` (
	`session_id` varchar(32) NOT NULL DEFAULT '',
	`session_user_id` mediumint(8) NOT NULL DEFAULT '0',
	`session_start` int(11) NOT NULL DEFAULT '0',
	`session_time` int(11) NOT NULL DEFAULT '0',
	`session_ip` varchar(40) NOT NULL DEFAULT '0',
	`session_browser` varchar(255) DEFAULT '' NOT NULL,
	`session_page` varchar(255) NOT NULL DEFAULT '',
	`session_logged_in` tinyint(1) NOT NULL DEFAULT '0',
	`session_forum_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`session_topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`session_last_visit` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`session_forwarded_for` varchar(255) DEFAULT '' NOT NULL,
	`session_viewonline` tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	`session_autologin` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`session_admin` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`session_id`),
	KEY `session_user_id` (`session_user_id`),
	KEY `session_fid` (`session_forum_id`)
);

INSERT INTO `___sessions___`
SELECT s.session_id, s.session_user_id, s.session_start, s.session_time, s.session_ip, s.session_user_agent, s.session_page, s.session_logged_in, 0, 0, 0, '', 1, 0, s.session_admin
FROM `phpbb_sessions` s
ORDER BY s.session_id;

RENAME TABLE `phpbb_sessions` TO `_old_phpbb_sessions`;
RENAME TABLE `___sessions___` TO `phpbb_sessions`;

ALTER TABLE `phpbb_groups` CHANGE `group_name` `group_name` varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE `phpbb_groups` CHANGE `group_description` `group_description` text NOT NULL;
ALTER TABLE `phpbb_groups` ADD `group_founder_manage` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_type`;
ALTER TABLE `phpbb_groups` ADD `group_display` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_description`;
ALTER TABLE `phpbb_groups` ADD `group_sig_chars` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_legend_order`;
ALTER TABLE `phpbb_groups` ADD `group_receive_pm` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_sig_chars`;
ALTER TABLE `phpbb_groups` ADD `group_message_limit` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_receive_pm`;
ALTER TABLE `phpbb_groups` ADD `group_max_recipients` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_message_limit`;
ALTER TABLE `phpbb_groups` ADD `group_skip_auth` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_max_recipients`;
ALTER TABLE `phpbb_groups` ADD INDEX `group_legend_name` (`group_legend`, `group_name`);

ALTER TABLE `phpbb_user_group` CHANGE `user_pending` `user_pending` tinyint(1) DEFAULT '1' NOT NULL;
ALTER TABLE `phpbb_user_group` ADD `group_leader` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_id`;
ALTER TABLE `phpbb_user_group` ADD INDEX `group_leader` (`group_leader`);

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('limit_load', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('limit_search_load', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ip_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('browser_check', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('referer_validation', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('force_server_vars', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('session_last_gc', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_sessions', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('form_token_lifetime', '7200');

CREATE TABLE `phpbb_log` (
	`log_id` mediumint(8) UNSIGNED NOT NULL auto_increment,
	`log_type` tinyint(4) DEFAULT '0' NOT NULL,
	`user_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`reportee_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`log_ip` varchar(40) DEFAULT '' NOT NULL,
	`log_time` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`log_operation` text NOT NULL,
	`log_data` mediumtext NOT NULL,
	PRIMARY KEY (`log_id`),
	KEY log_type (`log_type`),
	KEY forum_id (`forum_id`),
	KEY topic_id (`topic_id`),
	KEY reportee_id (`reportee_id`),
	KEY user_id (`user_id`)
);

ALTER TABLE `phpbb_banlist` CHANGE `ban_time` `ban_start` int(11) DEFAULT NULL;
ALTER TABLE `phpbb_banlist` CHANGE `ban_expire_time` `ban_end` int(11) DEFAULT NULL;

ALTER TABLE `phpbb_ajax_shoutbox` CHANGE `shouter_ip` `shouter_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_ajax_shoutbox_sessions` CHANGE `session_ip` `session_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_album_comment` CHANGE `comment_user_ip` `comment_user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_album_rate` CHANGE `rate_user_ip` `rate_user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_attachments_stats` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_banlist` CHANGE `ban_ip` `ban_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_ctracker_loginhistory` CHANGE `ct_login_ip` `ct_login_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_dl_banlist` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_dl_stats` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_kb_votes` CHANGE `votes_ip` `votes_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_links` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_links` CHANGE `last_user_ip` `last_user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_logins` CHANGE `login_ip` `login_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_pa_download_info` CHANGE `downloader_ip` `downloader_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_pa_files` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_pa_votes` CHANGE `votes_ip` `votes_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_poll_votes` CHANGE `vote_user_ip` `vote_user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_posts` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_privmsgs` CHANGE `privmsgs_ip` `privmsgs_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_privmsgs_archive` CHANGE `privmsgs_ip` `privmsgs_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_rate_results` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_referers` CHANGE `ip` `ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_registration` CHANGE `registration_user_ip` `registration_user_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_sessions` CHANGE `session_ip` `session_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_sessions_keys` CHANGE `last_ip` `last_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_shout` CHANGE `shout_ip` `shout_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_users` CHANGE `ct_last_used_ip` `ct_last_used_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_users` CHANGE `ct_last_ip` `ct_last_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_users` CHANGE `user_registered_ip` `user_registered_ip` varchar(40) NOT NULL DEFAULT '';

ALTER TABLE `phpbb_blogs_posts` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT '';
ALTER TABLE `phpbb_guestbooks_posts` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT '';

UPDATE `phpbb_ajax_shoutbox` ip SET ip.shouter_ip = INET_NTOA(CONV(ip.shouter_ip, 16, 10));
UPDATE `phpbb_ajax_shoutbox_sessions` ip SET ip.session_ip = INET_NTOA(CONV(ip.session_ip, 16, 10));
UPDATE `phpbb_album_comment` ip SET ip.comment_user_ip = INET_NTOA(CONV(ip.comment_user_ip, 16, 10));
UPDATE `phpbb_album_rate` ip SET ip.rate_user_ip = INET_NTOA(CONV(ip.rate_user_ip, 16, 10));
UPDATE `phpbb_attachments_stats` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10));
UPDATE `phpbb_banlist` ip SET ip.ban_ip = INET_NTOA(CONV(ip.ban_ip, 16, 10));
UPDATE `phpbb_dl_banlist` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10));
UPDATE `phpbb_dl_stats` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10));
UPDATE `phpbb_kb_votes` ip SET ip.votes_ip = INET_NTOA(CONV(ip.votes_ip, 16, 10));
UPDATE `phpbb_links` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10));
UPDATE `phpbb_links` ip SET ip.last_user_ip = INET_NTOA(CONV(ip.last_user_ip, 16, 10));
UPDATE `phpbb_logins` ip SET ip.login_ip = INET_NTOA(CONV(ip.login_ip, 16, 10));
UPDATE `phpbb_pa_download_info` ip SET ip.downloader_ip = INET_NTOA(CONV(ip.downloader_ip, 16, 10));
UPDATE `phpbb_pa_files` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10));
UPDATE `phpbb_pa_votes` ip SET ip.votes_ip = INET_NTOA(CONV(ip.votes_ip, 16, 10));
UPDATE `phpbb_poll_votes` ip SET ip.vote_user_ip = INET_NTOA(CONV(ip.vote_user_ip, 16, 10));
UPDATE `phpbb_posts` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10));
UPDATE `phpbb_privmsgs` ip SET ip.privmsgs_ip = INET_NTOA(CONV(ip.privmsgs_ip, 16, 10));
UPDATE `phpbb_privmsgs_archive` ip SET ip.privmsgs_ip = INET_NTOA(CONV(ip.privmsgs_ip, 16, 10));
UPDATE `phpbb_rate_results` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10));
UPDATE `phpbb_referers` ip SET ip.ip = INET_NTOA(CONV(ip.ip, 16, 10));
UPDATE `phpbb_registration` ip SET ip.registration_user_ip = INET_NTOA(CONV(ip.registration_user_ip, 16, 10));
UPDATE `phpbb_sessions` ip SET ip.session_ip = INET_NTOA(CONV(ip.session_ip, 16, 10));
UPDATE `phpbb_sessions_keys` ip SET ip.last_ip = INET_NTOA(CONV(ip.last_ip, 16, 10));
UPDATE `phpbb_shout` ip SET ip.shout_ip = INET_NTOA(CONV(ip.shout_ip, 16, 10));
UPDATE `phpbb_users` ip SET ip.user_registered_ip = INET_NTOA(CONV(ip.user_registered_ip, 16, 10));

UPDATE `phpbb_blogs_posts` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10));
UPDATE `phpbb_guestbooks_posts` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10));


## SESSIONS - END



########################################
##              BUILD 069             ##
########################################
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Bing', '<b style="color:#468;">Bing</b>', 'bingbot/', '');

ALTER TABLE `phpbb_users` ADD `user_post_sortby_dir` varchar(1) DEFAULT 'a' NOT NULL AFTER `user_posts_per_page`;
ALTER TABLE `phpbb_users` ADD `user_post_sortby_type` varchar(1) DEFAULT 't' NOT NULL AFTER `user_posts_per_page`;
ALTER TABLE `phpbb_users` ADD `user_post_show_days` smallint(4) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_posts_per_page`;
ALTER TABLE `phpbb_users` ADD `user_topic_sortby_dir` varchar(1) DEFAULT 'd' NOT NULL AFTER `user_posts_per_page`;
ALTER TABLE `phpbb_users` ADD `user_topic_sortby_type` varchar(1) DEFAULT 't' NOT NULL AFTER `user_posts_per_page`;
ALTER TABLE `phpbb_users` ADD `user_topic_show_days` smallint(4) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_posts_per_page`;



########################################
##              BUILD 070             ##
########################################
CREATE TABLE `phpbb_moderator_cache` (
	`forum_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`user_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`username` varchar(255) DEFAULT '' NOT NULL,
	`group_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_name` varchar(255) DEFAULT '' NOT NULL,
	`display_on_index` tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	KEY `disp_idx` (`display_on_index`),
	KEY `forum_id` (`forum_id`)
);

CREATE TABLE `phpbb_modules` (
	`module_id` mediumint(8) UNSIGNED NOT NULL auto_increment,
	`module_enabled` tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	`module_display` tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	`module_basename` varchar(255) DEFAULT '' NOT NULL,
	`module_class` varchar(10) DEFAULT '' NOT NULL,
	`parent_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`left_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`right_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`module_langname` varchar(255) DEFAULT '' NOT NULL,
	`module_mode` varchar(255) DEFAULT '' NOT NULL,
	`module_auth` varchar(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (`module_id`),
	KEY `left_right_id` (`left_id`, `right_id`),
	KEY `module_enabled` (`module_enabled`),
	KEY `class_left_id` (`module_class`, `left_id`)
);

UPDATE phpbb_cms_blocks SET active = 0 WHERE bposition IN ('hh', 'hl', 'hc', 'fc', 'fr', 'ff');



########################################
##              BUILD 071             ##
########################################
ALTER TABLE `phpbb_logs` CHANGE `log_desc` `log_desc` mediumtext NOT NULL;

ALTER TABLE `phpbb_forums` CHANGE `forum_rules` `forum_rules_switch` tinyint(1) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_forums` ADD `forum_rules_in_posting` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `forum_rules_switch`;
ALTER TABLE `phpbb_forums` ADD `forum_rules_in_viewtopic` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `forum_rules_switch`;
ALTER TABLE `phpbb_forums` ADD `forum_rules_in_viewforum` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `forum_rules_switch`;
ALTER TABLE `phpbb_forums` ADD `forum_rules_custom_title` varchar(80) NOT NULL DEFAULT '' AFTER `forum_rules_switch`;
ALTER TABLE `phpbb_forums` ADD `forum_rules_display_title` tinyint(1) NOT NULL DEFAULT '1' AFTER `forum_rules_switch`;
ALTER TABLE `phpbb_forums` ADD `forum_rules` text NOT NULL AFTER `forum_rules_switch`;

UPDATE phpbb_forums f, phpbb_forums_rules fr
SET f.forum_rules = fr.rules, f.forum_rules_display_title = fr.rules_display_title, f.forum_rules_custom_title = fr.rules_custom_title, f.forum_rules_in_viewforum = fr.rules_in_viewforum, f.forum_rules_in_viewtopic = fr.rules_in_viewtopic, f.forum_rules_in_posting = fr.rules_in_posting
WHERE f.forum_id = fr.forum_id;

DROP TABLE `phpbb_forums_rules`;



########################################
##              BUILD 072             ##
########################################
DELETE FROM `phpbb_config` WHERE config_name = "cms_dock";
DELETE FROM `phpbb_config` WHERE config_name = "cms_style";

ALTER TABLE `phpbb_users` ADD `user_flickr` varchar(255) DEFAULT '' NOT NULL AFTER `user_twitter`;
ALTER TABLE `phpbb_users` ADD `user_googleplus` varchar(255) DEFAULT '' NOT NULL AFTER `user_flickr`;
ALTER TABLE `phpbb_users` ADD `user_youtube` varchar(255) DEFAULT '' NOT NULL AFTER `user_googleplus`;
ALTER TABLE `phpbb_users` ADD `user_linkedin` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`;

ALTER TABLE `phpbb_users` CHANGE `user_style` `user_style` MEDIUMINT(8) NULL DEFAULT NULL;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_keywords', 'your keywords, comma, separated');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_keywords_switch', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_description', 'Your Site Description');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_description_switch', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_author', 'Author');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_author_switch', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_copyright', 'Copyright');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('site_meta_copyright_switch', '1');

ALTER TABLE `phpbb_posts` CHANGE `post_text` `post_text` MEDIUMTEXT NOT NULL;
ALTER TABLE `phpbb_posts` CHANGE `post_text_compiled` `post_text_compiled` MEDIUMTEXT NOT NULL;



########################################
##              BUILD 073             ##
########################################



########################################
##              BUILD 074             ##
########################################



########################################
##              BUILD 075             ##
########################################
ALTER TABLE `phpbb_users` DROP `user_cms_level`;
ALTER TABLE `phpbb_cms_block_settings` DROP `edit_auth`;
ALTER TABLE `phpbb_cms_blocks` DROP `edit_auth`;
ALTER TABLE `phpbb_cms_layout` DROP `edit_auth`;
ALTER TABLE `phpbb_cms_layout_special` DROP `edit_auth`;

## AUTH SYSTEM - BEGIN
TRUNCATE TABLE `phpbb_acl_groups`;
TRUNCATE TABLE `phpbb_acl_options`;
TRUNCATE TABLE `phpbb_acl_roles`;
TRUNCATE TABLE `phpbb_acl_roles_data`;
TRUNCATE TABLE `phpbb_acl_users`;

# -- CMS related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_admin', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_settings', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_layouts', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_layouts_special', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_blocks', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_blocks_global', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_permissions', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_menu', 1, 0, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_ads', 1, 0, 0);

INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsl_', 0, 1, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsl_admin', 0, 1, 0);

INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmss_', 0, 1, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmss_admin', 0, 1, 0);

INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsb_', 0, 1, 0);
INSERT INTO phpbb_acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsb_admin', 0, 1, 0);

# -- Admin related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_modules', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_roles', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_aauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_mauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_uauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_fauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_authgroups', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_authusers', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_viewauth', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_group', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('a_user', 1);

# -- Moderator related auth options
INSERT INTO phpbb_acl_options (auth_option, is_local, is_global) VALUES ('m_', 1, 1);
INSERT INTO phpbb_acl_options (auth_option, is_local, is_global) VALUES ('m_topicdelete', 1, 1);

# -- User related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('u_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('u_html', 1);

# -- Forum related auth options
INSERT INTO phpbb_acl_options (auth_option, is_local) VALUES ('f_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_local) VALUES ('f_html', 1);
INSERT INTO phpbb_acl_options (auth_option, is_local) VALUES ('f_topicdelete', 1);

# -- Plugins related auth options
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_admin', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_input', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_edit', 1);
INSERT INTO phpbb_acl_options (auth_option, is_global) VALUES ('pl_delete', 1);

# -- Standard auth roles
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (1, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cms_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (2, 'ROLE_CMS_REVIEWER', 'ROLE_CMS_REVIEWER_DESCRIPTION', 'cms_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (3, 'ROLE_CMS_PUBLISHER', 'ROLE_CMS_PUBLISHER_DESCRIPTION', 'cms_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (4, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmsl_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (5, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmss_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (6, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmsb_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (7, 'ROLE_ADMIN_FULL', 'ROLE_ADMIN_FULL_DESCRIPTION', 'a_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (8, 'ROLE_ADMIN_STANDARD', 'ROLE_ADMIN_STANDARD_DESCRIPTION', 'a_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (9, 'ROLE_MOD_FULL', 'ROLE_MOD_FULL_DESCRIPTION', 'm_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (10, 'ROLE_MOD_STANDARD', 'ROLE_MOD_STANDARD_DESCRIPTION', 'm_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (11, 'ROLE_MOD_SIMPLE', 'ROLE_MOD_SIMPLE_DESCRIPTION', 'm_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (12, 'ROLE_USER_FULL', 'ROLE_USER_FULL_DESCRIPTION', 'u_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (13, 'ROLE_USER_STANDARD', 'ROLE_USER_STANDARD_DESCRIPTION', 'u_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (14, 'ROLE_USER_LIMITED', 'ROLE_USER_LIMITED_DESCRIPTION', 'u_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (15, 'ROLE_FORUM_FULL', 'ROLE_FORUM_FULL_DESCRIPTION', 'f_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (16, 'ROLE_FORUM_STANDARD', 'ROLE_FORUM_STANDARD_DESCRIPTION', 'f_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (17, 'ROLE_FORUM_NOACCESS', 'ROLE_FORUM_NOACCES_DESCRIPTIONS', 'f_', 3);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (18, 'ROLE_PLUGINS_FULL', 'ROLE_PLUGINS_FULL_DESCRIPTION', 'pl_', 1);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (19, 'ROLE_PLUGINS_STANDARD', 'ROLE_PLUGINS_STANDARD_DESCRIPTION', 'pl_', 2);
INSERT INTO phpbb_acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (20, 'ROLE_PLUGINS_NOACCESS', 'ROLE_PLUGINS_NOACCESS_DESCRIPTION', 'pl_', 3);

# -- Roles data

# CMS Content Manager (cms_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 1, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cms_%' AND is_global = 1;

# CMS Reviewer (cms_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 2, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cms_%' AND auth_option NOT IN ('cms_admin', 'cms_settings', 'cms_permissions', 'cms_menu', 'cms_ads') AND is_global = 1;

# CMS Publisher (cms_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 3, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option = 'cms_blocks' AND is_global = 1;

# CMS Content Manager Layouts (cmsl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 4, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cmsl_%' AND is_local = 1;

# CMS Content Manager Special Layouts (cmss_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 5, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cmss_%' AND is_local = 1;

# CMS Content Manager Blocks (cmsb_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 6, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'cmsb_%' AND is_local = 1;

# Full Admin (a_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 7, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'a_%';

# Standard Admin (a_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 8, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'a_%' AND auth_option NOT IN ('a_modules', 'a_aauth', 'a_roles');

# Full Moderator (m_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 9, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'm_%';

# Standard Moderator (m_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 10, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'm_%' AND auth_option NOT IN ('m_topicdelete');

# Simple Moderator (m_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 11, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'm_%' AND auth_option IN ('m_', 'm_topicdelete');

# All Features (u_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 12, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'u_%';

# Standard Features (u_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 13, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'u_%' AND auth_option NOT IN ('u_html');

# Limited Features (u_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 14, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'u_%' AND auth_option NOT IN ('u_html');

# Full Access (f_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 15, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'f_%';

# Standard Access (f_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 16, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'f_%' AND auth_option NOT IN ('f_html');

# No Access (f_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 17, auth_option_id, 0 FROM phpbb_acl_options WHERE auth_option = 'f_';

# Full Access (pl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 18, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'pl_%';

# Standard Access (pl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 19, auth_option_id, 1 FROM phpbb_acl_options WHERE auth_option LIKE 'pl_%' AND auth_option NOT IN ('pl_admin', 'pl_delete');

# No Access (pl_)
INSERT INTO phpbb_acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 20, auth_option_id, 0 FROM phpbb_acl_options WHERE auth_option = 'pl_';

# Permissions

# Admin users - full features
#INSERT INTO phpbb_acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 1, 0 FROM phpbb_users WHERE user_level = 1;
INSERT INTO phpbb_acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 7, 0 FROM phpbb_users WHERE user_level = 1;
#INSERT INTO phpbb_acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 18, 0 FROM phpbb_users WHERE user_level = 1;
## AUTH SYSTEM - END



########################################
##              BUILD 076             ##
########################################
ALTER TABLE `phpbb_users` ADD `user_cms_auth` TEXT NOT NULL AFTER `user_mask`;
ALTER TABLE `phpbb_users` DROP `user_lastlogon`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_posts_number', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_disable_url', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_hide_signature', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('spam_post_edit_interval', '60');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('mobile_style_disable', '1');



########################################
##              BUILD 077             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('session_gc', '3600');



########################################
##              BUILD 078             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('session_last_visit_reset', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('check_dnsbl', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('check_dnsbl_posting', '0');
DELETE FROM `phpbb_config` WHERE `config_name` = 'disable_registration_ip_check';
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Jike Spider', '', 'jikespider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Magpie Crawler', '', 'www.brandwatch.net', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('P3W Bot', '', 'www.p3w.it', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Radian 6', '', 'www.radian6.com/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Soso Spider', '', 'Sosospider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Synthesio Crawler', '', 'synthesio', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Vik Spider', '', 'vikspider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WangID Spider', '', 'WangIDSpider/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YandexBot 3.0', '', 'yandex.com/bots', '');



########################################
##              BUILD 079             ##
########################################
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ezooms', '', 'Ezooms/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Archive ORG BOT', '', 'www.archive.org/', '');
UPDATE `phpbb_config` SET `config_name` = 'disable_referers' WHERE `config_name` = 'disable_referrers';
UPDATE `phpbb_cms_layout_special` SET `page_id` = 'referers', `name` = 'referers', `filename` = 'referers.php' WHERE `page_id` = 'referrers';
DROP TABLE `phpbb_referrers`;
CREATE TABLE `phpbb_referers` (
	`id` INT(11) NOT NULL auto_increment,
	`host` VARCHAR(255) NOT NULL DEFAULT '',
	`url` VARCHAR(255) NOT NULL DEFAULT '',
	`t_url` VARCHAR(255) NOT NULL DEFAULT '',
	`ip` VARCHAR(40) NOT NULL DEFAULT '',
	`hits` INT(11) NOT NULL DEFAULT '1',
	`firstvisit` INT(11) NOT NULL DEFAULT '0',
	`lastvisit` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
);



########################################
##              BUILD 080             ##
########################################



########################################
##              BUILD 081             ##
########################################
ALTER TABLE `phpbb_album` CHANGE `pic_user_ip` `pic_user_ip` varchar(40) NOT NULL DEFAULT '';
UPDATE `phpbb_album` ip SET ip.pic_user_ip = INET_NTOA(CONV(ip.pic_user_ip, 16, 10));
UPDATE `phpbb_ajax_shoutbox` SET shout_room = CONCAT(CONCAT('|', shout_room), '|') WHERE shout_room LIKE '%|%';
DELETE FROM `phpbb_config` WHERE `config_name` = 'shoutbox_refreshtime';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_msgs_refresh', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_session_refresh', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_link_type', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_notification', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_chat_check_online', '0');



########################################
##              BUILD 082             ##
########################################



########################################
##              BUILD 083             ##
########################################



########################################
##              BUILD 084             ##
########################################



########################################
##              BUILD 085             ##
########################################
ALTER TABLE `phpbb_users` CHANGE `user_viewemail` `user_allow_viewemail` TINYINT(1) NOT NULL DEFAULT '0';
CREATE TABLE `phpbb_images` (
	`pic_id` INT(11) unsigned NOT NULL auto_increment,
	`pic_filename` VARCHAR(255) NOT NULL DEFAULT '',
	`pic_size` INT(15) unsigned NOT NULL DEFAULT '0',
	`pic_title` VARCHAR(255) NOT NULL DEFAULT '',
	`pic_desc` TEXT NOT NULL,
	`pic_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`pic_user_ip` VARCHAR(40) NOT NULL DEFAULT '0',
	`pic_time` INT(11) unsigned NOT NULL DEFAULT '0',
	`pic_approval` TINYINT(3) NOT NULL DEFAULT '1',
	PRIMARY KEY (`pic_id`),
	KEY `pic_user_id` (`pic_user_id`),
	KEY `pic_time` (`pic_time`)
);

UPDATE `phpbb_cms_nav_menu` SET `menu_link` = 'images_list.php' WHERE `menu_link` = 'posted_img_list.php';

UPDATE `phpbb_posts` SET `post_text` = REPLACE(`post_text`,'posted_images/','images/');
UPDATE `phpbb_posts` SET `post_text` = REPLACE(`post_text`,'posted_img_list.php','images_list.php');
UPDATE `phpbb_posts` SET `post_text` = REPLACE(`post_text`,'posted_img_list_thumbnail.php','image_thumbnail_s.php');
UPDATE `phpbb_posts` SET `post_text` = REPLACE(`post_text`,'posted_img_thumbnail.php','image_thumbnail.php');
UPDATE `phpbb_posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_images/','images/');
UPDATE `phpbb_posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_img_list.php','images_list.php');
UPDATE `phpbb_posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_img_list_thumbnail.php','image_thumbnail_s.php');
UPDATE `phpbb_posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_img_thumbnail.php','image_thumbnail.php');
UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`,'posted_images/','images/');
UPDATE `phpbb_users` SET `user_sig` = REPLACE(`user_sig`,'posted_images/','images/');
# UPDATE `phpbb_bugs_posts` SET `post_text` = REPLACE(`post_text`,'posted_images/','images/');
# UPDATE `phpbb_hon` SET `hon_description` = REPLACE(`hon_description`,'posted_images/','images/');



########################################
##              BUILD 086             ##
########################################



########################################
########################################
##     CONTINUE ON THE OTHER FILE     ##
########################################
########################################
