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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_blockmode', '1');
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_ip_scan', '1');
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

INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (1, 'cms_', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (2, 'cms_view', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (3, 'cms_edit', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (4, 'cms_l_new', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (5, 'cms_l_edit', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (6, 'cms_l_delete', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (7, 'cms_b_new', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (8, 'cms_b_edit', 0, 1, 0);
INSERT INTO `phpbb_acl_options` (`auth_option_id`, `auth_option`, `is_global`, `is_local`, `founder_only`) VALUES (9, 'cms_b_delete', 0, 1, 0);

INSERT INTO `phpbb_acl_roles` (`role_id`, `role_name`, `role_description`, `role_type`, `role_order`) VALUES (1, 'CMS_CONTENT_MANAGER', 'CMS_CONTENT_MANAGER_TEXT', 'cms_', 1);
INSERT INTO `phpbb_acl_roles` (`role_id`, `role_name`, `role_description`, `role_type`, `role_order`) VALUES (2, 'CMS_REVIEWER', 'CMS_REVIEWER_TEXT', 'cms_', 2);
INSERT INTO `phpbb_acl_roles` (`role_id`, `role_name`, `role_description`, `role_type`, `role_order`) VALUES (3, 'CMS_PUBLISHER', 'CMS_PUBLISHER_TEXT', 'cms_', 3);

INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 2, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 3, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 4, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 5, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 6, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 7, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 8, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (1, 9, 1);
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

INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (2, 2, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (2, 4, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (2, 5, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (2, 7, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (2, 8, 1);

INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (3, 2, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (3, 4, 1);
INSERT INTO `phpbb_acl_roles_data` (`role_id`, `auth_option_id`, `auth_setting`) VALUES (3, 7, 1);

## NEW CMS - BEGIN
CREATE TABLE `phpbb_cms_block_settings` (
	`bs_id` int(10) NOT NULL AUTO_INCREMENT,
	`user_id` int(10) NOT NULL,
	`name` varchar(255) NOT NULL default '',
	`content` text NOT NULL ,
	`blockfile` varchar(255) NOT NULL default '',
	`view` tinyint(1) NOT NULL default 0,
	`type` tinyint(1) NOT NULL default 1,
	`edit_auth` tinyint(1) NOT NULL default 5,
	`groups` tinytext NOT NULL,
	`locked` tinyint(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (`bs_id`)
);

INSERT INTO `phpbb_cms_block_settings`
SELECT b.bid, 0, b.title, b.content, b.blockfile, b.view, b.type, b.edit_auth, b.groups, 1
FROM `phpbb_cms_blocks` b
ORDER BY b.bid;

ALTER TABLE `phpbb_cms_blocks` DROP `content`;
ALTER TABLE `phpbb_cms_blocks` DROP `blockfile`;
ALTER TABLE `phpbb_cms_blocks` DROP `view`;
ALTER TABLE `phpbb_cms_blocks` DROP `type`;
ALTER TABLE `phpbb_cms_blocks` DROP `groups`;

ALTER TABLE `phpbb_cms_blocks` ADD `block_settings_id` int(10) UNSIGNED NOT NULL AFTER `bid`;
ALTER TABLE `phpbb_cms_blocks` ADD `block_cms_id` int(10) UNSIGNED NOT NULL AFTER `block_settings_id`;

UPDATE `phpbb_cms_blocks` SET `block_settings_id` = `bid`;

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



#####################

##UPDATE phpbb_config SET config_value = '2' WHERE config_name = 'main_admin_id';

#-- DB CHANGES FOR VERSIONING
UPDATE phpbb_config SET config_value = '1.3.11.64' WHERE config_name = 'ip_version';
UPDATE phpbb_config SET config_value = '.0.23' WHERE config_name = 'version';
UPDATE phpbb_config SET config_value = '2.0.0' WHERE config_name = 'cms_version';
UPDATE phpbb_album_config SET config_value = '1.5.0' WHERE config_name = 'fap_version';
UPDATE phpbb_config SET config_value = '2.4.5' WHERE config_name = 'attach_version';
UPDATE phpbb_config SET config_value = '3.0.7' WHERE config_name = 'upi2db_version';
