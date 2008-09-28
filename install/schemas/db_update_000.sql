########################################
##              BUILD 000             ##
########################################


## Cracker Tracker - BEGIN
DROP TABLE `phpbb_ctrack`;
DROP TABLE `phpbb_ct_filter`;
DROP TABLE `phpbb_ct_viskey`;
ALTER TABLE `phpbb_users` DROP `ct_logintry`;
ALTER TABLE `phpbb_users` DROP `ct_unsucclogin`;
ALTER TABLE `phpbb_users` DROP `ct_pwreset`;
ALTER TABLE `phpbb_users` DROP `ct_mailcount`;
ALTER TABLE `phpbb_users` DROP `ct_postcount`;
ALTER TABLE `phpbb_users` DROP `ct_posttime`;
ALTER TABLE `phpbb_users` DROP `ct_searchcount`;
ALTER TABLE `phpbb_users` DROP `ct_searchtime`;

ALTER TABLE `phpbb_users` ADD `ct_search_time` INT( 11 ) NULL DEFAULT 1 AFTER `user_newpasswd`;
ALTER TABLE `phpbb_users` ADD `ct_search_count` MEDIUMINT( 8 ) NULL DEFAULT 1 AFTER `ct_search_time`;
ALTER TABLE `phpbb_users` ADD `ct_last_mail` INT( 11 ) NULL DEFAULT 1 AFTER `ct_search_count`;
ALTER TABLE `phpbb_users` ADD `ct_last_post` INT( 11 ) NULL DEFAULT 1 AFTER `ct_last_mail`;
ALTER TABLE `phpbb_users` ADD `ct_post_counter` MEDIUMINT( 8 ) NULL DEFAULT 1 AFTER `ct_last_post`;
ALTER TABLE `phpbb_users` ADD `ct_last_pw_reset` INT( 11 ) NULL DEFAULT 1 AFTER `ct_post_counter`;
ALTER TABLE `phpbb_users` ADD `ct_enable_ip_warn` TINYINT( 1 ) NULL DEFAULT 1 AFTER `ct_last_pw_reset`;
ALTER TABLE `phpbb_users` ADD `ct_last_used_ip` VARCHAR( 16 ) NULL DEFAULT '0.0.0.0' AFTER `ct_enable_ip_warn`;
ALTER TABLE `phpbb_users` ADD `ct_last_ip` VARCHAR( 16 ) NULL DEFAULT '0.0.0.0' AFTER `ct_last_used_ip`;
ALTER TABLE `phpbb_users` ADD `ct_login_count` MEDIUMINT( 8 ) NULL DEFAULT 1 AFTER `ct_last_used_ip`;
ALTER TABLE `phpbb_users` ADD `ct_login_vconfirm` TINYINT( 1 ) NULL DEFAULT 0 AFTER `ct_login_count`;
ALTER TABLE `phpbb_users` ADD `ct_last_pw_change` INT( 11 ) NULL DEFAULT 1 AFTER `ct_login_vconfirm`;
ALTER TABLE `phpbb_users` ADD `ct_global_msg_read` TINYINT( 1 ) NULL DEFAULT 0 AFTER `ct_last_pw_change`;
ALTER TABLE `phpbb_users` ADD `ct_miserable_user` TINYINT( 1 ) NULL DEFAULT 0 AFTER `ct_global_msg_read`;

CREATE TABLE `phpbb_ctracker_config` (
	`ct_config_name` varchar(255) NOT NULL,
	`ct_config_value` varchar(255) NOT NULL,
	PRIMARY KEY  (`ct_config_name`)
	);

INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('ipblock_enabled', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('ipblock_logsize', '100');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('auto_recovery', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('vconfirm_guest', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('autoban_mails', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('detect_misconfiguration', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_time_guest', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_time_user', '20');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_count_guest', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_count_user', '4');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('massmail_protection', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_protection', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_blocktime', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_lastip', '0.0.0.0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pwreset_time', '20');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('massmail_time', '20');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spammer_time', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spammer_postcount', '4');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spammer_blockmode', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('loginfeature', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_reset_feature', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_last_reg', '1155944976');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('login_history', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('login_history_count', '10');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('login_ip_check', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_validity', '30');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_complex_min', '4');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_complex_mode', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_control', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('pw_complex', '0');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('last_file_scan', '1156000091');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('last_checksum_scan', '1156000082');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('logsize_logins', '100');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('logsize_spammer', '100');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('reg_ip_scan', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('global_message', 'Hello world!');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('global_message_type', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('logincount', '2');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('search_feature_enabled', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spam_attack_boost', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('spam_keyword_det', '1');
INSERT INTO `phpbb_ctracker_config` (`ct_config_name`, `ct_config_value`) VALUES ('footer_layout', '6');

CREATE TABLE `phpbb_ctracker_filechk` (
	`filepath` text,
	`hash` varchar(32) default NULL
	);

CREATE TABLE `phpbb_ctracker_filescanner` (
	`id` smallint(5) NOT NULL,
	`filepath` text,
	`safety` smallint(1) NOT NULL default '0',
	PRIMARY KEY  (`id`)
	);

CREATE TABLE `phpbb_ctracker_ipblocker` (
	`id` mediumint(8) unsigned NOT NULL,
	`ct_blocker_value` varchar(250) default NULL,
	PRIMARY KEY  (`id`)
	);

INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (1, '*WebStripper*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (2, '*NetMechanic*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (3, '*CherryPicker*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (4, '*EmailCollector*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (5, '*EmailSiphon*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (6, '*WebBandit*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (7, '*EmailWolf*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (8, '*ExtractorPro*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (9, '*SiteSnagger*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (10, '*CheeseBot*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (11, '*ia_archiver*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (12, '*Website Quester*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (13, '*WebZip*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (14, '*moget*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (15, '*WebSauger*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (16, '*WebCopier*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (17, '*WWW-Collector*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (18, '*InfoNaviRobot*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (19, '*Harvest*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (20, '*Bullseye*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (21, '*LinkWalker*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (22, '*LinkextractorPro*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (23, '*Proxy*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (24, '*BlowFish*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (25, '*WebEnhancer*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (26, '*TightTwatBot*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (27, '*LinkScan*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (28, '*WebDownloader*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (29, 'lwp');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (30, '*BruteForce*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (31, 'lwp-*');
INSERT INTO `phpbb_ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (32, '*anonym*');

CREATE TABLE `phpbb_ctracker_loginhistory` (
	`ct_user_id` int(10) default NULL,
	`ct_login_ip` varchar(16) default NULL,
	`ct_login_time` int(11) NOT NULL default '0'
	);
## Cracker Tracker - END

INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('quick_thumbs', '0');
ALTER TABLE `phpbb_album_cat` ADD `cat_wm` TEXT AFTER `cat_desc`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_portal', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_forum', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_viewf', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_viewt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_faq', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_memberlist', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_group_cp', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_profile', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_search', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_album', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_links', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_calendar', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_attachments', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_download', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_kb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_ranks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_statistics', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_recent', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_referrers', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_rules', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_site_hist', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_shoutbox', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_viewonline', '0');

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_custom_pages', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_custom_pages', '0');

INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('set_memory', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('lb_preview', '0');

ALTER TABLE `phpbb_users` ADD `user_cms_level` TINYINT( 4 ) DEFAULT '0' NOT NULL;

UPDATE phpbb_stats_config SET config_value = 'includes/stat_modules' WHERE config_name = 'modules_dir';


########################################
##              BUILD 001             ##
########################################


## Digests - BEGIN
CREATE TABLE `phpbb_digest_subscriptions` (
	`user_id` INTEGER NOT NULL DEFAULT 0,
	`digest_type` CHAR(4) NOT NULL DEFAULT 'DAY',
	`format` CHAR(4) NOT NULL DEFAULT 'TEXT',
	`show_text` CHAR(3) NOT NULL DEFAULT 'YES',
	`show_mine` CHAR(3) NOT NULL DEFAULT 'YES',
	`new_only` CHAR(5) NOT NULL DEFAULT 'TRUE',
	`send_on_no_messages` CHAR(3) NOT NULL DEFAULT 'NO',
	`send_hour` SMALLINT NOT NULL DEFAULT 0,
	`text_length` INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY (user_id)
);
##
CREATE TABLE `phpbb_digest_subscribed_forums` (
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT 0,
	`forum_id` SMALLINT(5) NOT NULL DEFAULT 0,
	UNIQUE user_id (user_id, forum_id)
);

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_digests', '0');
## Digests - END

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('forum_wordgraph', '0');

ALTER TABLE `phpbb_topics` ADD FULLTEXT (topic_title);
## ALTER TABLE `phpbb_topics` ADD FULLTEXT (topic_desc);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_topics', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_stopwords', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_ignore_forums_ids', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_sort_type', 'relev');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_max_topics', '5');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('similar_topicdesc', '0');

UPDATE phpbb_config SET config_value = 'images/avatars/generator_templates' WHERE config_name = 'avatar_generator_template_path';
UPDATE phpbb_config SET config_value = 'images/avatars/default_avatars/guest.gif' WHERE config_name = 'default_avatar_guests_url';
UPDATE phpbb_config SET config_value = 'images/avatars/default_avatars/member.gif' WHERE config_name = 'default_avatar_users_url';
UPDATE phpbb_config SET config_value = 'images/avatars/default_avatars/member.gif' WHERE config_name = 'gravatar_default_image';

########################################
##              BUILD 002             ##
########################################


## Icy Phoenix CMS - BEGIN

DROP TABLE `phpbb_block_position`;
DROP TABLE `phpbb_block_variable`;
DROP TABLE `phpbb_blocks`;
DROP TABLE `phpbb_layout`;
DROP TABLE `phpbb_portal_config`;

CREATE TABLE `phpbb_cms_block_position` (
  `bpid` int(10) NOT NULL auto_increment,
  `layout` int(10) NOT NULL default '1',
  `pkey` varchar(30) NOT NULL default '',
  `bposition` char(2) NOT NULL default '',
  PRIMARY KEY  (`bpid`)
);

CREATE TABLE `phpbb_cms_block_variable` (
  `bvid` int(10) NOT NULL auto_increment,
  `bid` int(10) NOT NULL default '0',
  `label` varchar(30) NOT NULL default '',
  `sub_label` varchar(255) default NULL,
  `config_name` varchar(30) NOT NULL default '',
  `field_options` varchar(255) default NULL,
  `field_values` varchar(255) default NULL,
  `type` tinyint(1) NOT NULL default '0',
  `block` varchar(255) default NULL,
  PRIMARY KEY  (`bvid`)
);

CREATE TABLE `phpbb_cms_blocks` (
  `bid` int(10) NOT NULL auto_increment,
  `layout` int(10) NOT NULL default '0',
  `title` varchar(60) NOT NULL default '',
  `content` text NOT NULL,
  `bposition` char(2) NOT NULL default '',
  `weight` int(10) NOT NULL default '1',
  `active` tinyint(1) NOT NULL default '1',
  `blockfile` varchar(255) NOT NULL default '',
  `view` tinyint(1) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '1',
  `border` tinyint(1) NOT NULL default '1',
  `titlebar` tinyint(1) NOT NULL default '1',
  `background` tinyint(1) NOT NULL default '1',
  `local` tinyint(1) NOT NULL default '0',
  `edit_auth` tinyint(1) NOT NULL default '5',
  `groups` tinytext NOT NULL,
  PRIMARY KEY  (`bid`)
);

CREATE TABLE `phpbb_cms_config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `bid` int(10) NOT NULL default '0',
  `config_name` varchar(255) NOT NULL default '',
  `config_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `phpbb_cms_layout` (
  `lid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `filename` varchar(100) NOT NULL default '',
  `template` varchar(100) NOT NULL default '',
  `global_blocks` tinyint(1) NOT NULL default '0',
  `view` tinyint(1) NOT NULL default '0',
  `edit_auth` tinyint(1) NOT NULL default '5',
  `groups` tinytext NOT NULL,
  PRIMARY KEY  (`lid`)
);

INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (1, 'header', 'hh', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (2, 'headerleft', 'hl', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (3, 'headercenter', 'hc', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (4, 'footercenter', 'fc', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (5, 'footerright', 'fr', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (6, 'footer', 'ff', 0);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (7, 'left', 'l', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (8, 'center', 'c', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (9, 'right', 'r', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (10, 'xsnews', 'x', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (11, 'nav', 'n', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (12, 'centerbottom', 'b', 1);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (13, 'left', 'l', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (14, 'center', 'c', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (15, 'xsnews', 'x', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (16, 'nav', 'n', 2);
INSERT INTO `phpbb_cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (17, 'centerbottom', 'b', 2);

INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (1, 0, 'Default Portal', 'Default Portal', 'default_portal', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (2, 0, 'Header width', 'Width of forum-wide left column in pixels', 'header_width', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (3, 0, 'Footer width', 'Width of forum-wide right column in pixels', 'footer_width', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (4, 3, 'Number of recent topics', 'number of topics displayed', 'md_num_recent_topics', '', '', 1, 'recent_topics');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (5, 3, 'Recent Topics Style', 'choose static display or scrolling display', 'md_recent_topics_style', 'Scroll,Static', '1,0', 3, 'recent_topics');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (6, 4, 'Poll Bar Length', 'decrease/increase the value for 1 vote bar length', 'md_poll_bar_length', '', '', 1, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (7, 4, 'Poll Forum ID(s)', 'comma delimited', 'md_poll_forum_id', '', '', 1, 'poll');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (8, 8, 'Number of Top Posters', '', 'md_total_poster', '', '', 1, 'top_posters');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (9, 9, 'Search option text', 'Text displayed as the default option', 'md_search_option_text', '', '', 1, 'search');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (10, 11, 'Category to retrieve pics from', 'Enter 0 for all categories or comma delimited entries', 'md_cat_id', '', '', 1, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (11, 11, 'Display from what galleries?', '', 'md_pics_all', 'Public,Public and Personal', '0,1', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (12, 11, 'Random or newest pics?', '', 'md_pics_sort', 'Newest,Random', '0,1', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (13, 11, 'Number of images to display', '', 'md_pics_number', '', '', 1, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (14, 11, 'Number of columns', '', 'md_pics_cols_number', '1,2,3,4,5', '1,2,3,4,5', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (15, 11, 'Number of rows', '', 'md_pics_rows_number', '1,2,3,4', '1,2,3,4', 3, 'album');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (16, 12, 'Links -> Style', 'choose static display or scrolling display', 'md_links_style', 'Scroll,Static', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (17, 12, 'Links -> Own (Top)', 'show your own link button above other buttons', 'md_links_own1', 'Yes,No', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (18, 12, 'Links -> Own (Bottom)', 'show your own link button below other buttons', 'md_links_own2', 'Yes,No', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (19, 12, 'Links -> Code', 'show HTML for your own link button', 'md_links_code', 'Yes,No', '1,0', 3, 'links');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (20, 14, 'Maximum Words', 'Select the maximum number of words to display', 'md_wordgraph_words', '', '', 1, 'wordgraph');
INSERT INTO `phpbb_cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (21, 14, 'Enable Word Counts', 'Display the total number of words next to each word', 'md_wordgraph_count', 'Yes,No', '1,0', 3, 'wordgraph');

INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (1, 'Nav Links', '', 'hl', 1, 1, 'blocks_imp_nav_links', 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (2, 'Nav Links', '', 'l', 1, 1, 'blocks_imp_nav_links', 0, 1, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (3, 'Recent', '', 'l', 3, 0, 'blocks_imp_recent_topics', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (4, 'Poll', '', 'r', 4, 1, 'blocks_imp_poll', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (5, 'Welcome', '', 'c', 1, 1, 'blocks_imp_welcome', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (6, 'News', '', 'x', 1, 1, 'blocks_imp_news', 0, 1, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (7, 'User Block', '', 'r', 1, 1, 'blocks_imp_user_block', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (8, 'Top Posters', '', 'r', 5, 1, 'blocks_imp_top_posters', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (9, 'Search', '', 'l', 1, 1, 'blocks_imp_search', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (10, 'Who is Online', '', 'r', 2, 1, 'blocks_imp_online_users', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (11, 'Album', '', 'l', 2, 1, 'blocks_imp_album', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (12, 'Links', '', 'l', 4, 1, 'blocks_imp_links', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (13, 'Statistics', '', 'r', 3, 1, 'blocks_imp_statistics', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (14, 'Wordgraph', '', 'b', 2, 1, 'blocks_imp_wordgraph', 0, 1, 0, 0, 0, 0, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (15, 'Welcome', '<table class=\\"empty-table\\" width=\\"100%\\" cellspacing=\\"0\\" cellpadding=\\"0\\" border=\\"0\\">\r\n	<tr>\r\n		<td width=\\"5%\\"><img src=\\"images/icy_phoenix_small.png\\" alt=\\"\\" /></td>\r\n		<td width=\\"90%\\" align=\\"center\\"><div class=\\"post-text\\">Welcome To <b>Icy Phoenix</b></div><br /><br /></td>\r\n		<td width=\\"5%\\"><img src=\\"images/icy_phoenix_small_l.png\\" alt=\\"\\" /></td>\r\n	</tr>\r\n</table>', 'c', 2, 1, '', 0, 1, 0, 1, 1, 1, 1, '');

INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (1, 0, 'default_portal', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (2, 0, 'header_width', '180');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (3, 0, 'footer_width', '150');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (4, 3, 'md_recent_topics_style', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (5, 3, 'md_num_recent_topics', '10');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (6, 4, 'md_poll_bar_length', '65');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (7, 4, 'md_poll_forum_id', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (8, 8, 'md_total_poster', '5');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (9, 9, 'md_search_option_text', 'Icy Phoenix');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (10, 11, 'md_cat_id', '0');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (11, 11, 'md_pics_all', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (12, 11, 'md_pics_sort', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (13, 11, 'md_pics_number', '3');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (14, 11, 'md_pics_cols_number', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (15, 11, 'md_pics_rows_number', '3');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (16, 12, 'md_links_style', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (17, 12, 'md_links_own1', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (18, 12, 'md_links_own2', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (19, 12, 'md_links_code', '1');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (20, 14, 'md_wordgraph_words', '250');
INSERT INTO `phpbb_cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (21, 14, 'md_wordgraph_count', '1');

INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (1, '3 Columns', '3_column.tpl', 0, 0, '');
INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (2, '2 Columns', '2_column.tpl', 0, 0, '');
INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (3, 'Central Block', 'central_block.tpl', 0, 0, '');
INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (4, 'Quad Layout', 'quad_layout.tpl', 0, 0, '');
INSERT INTO `phpbb_cms_layout` (`lid`, `name`, `template`, `global_blocks`, `view`, `groups`) VALUES (5, 'Portal Body', 'portal_body.tpl', 0, 0, '');

## Icy Phoenix CMS - END

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('digests_php_cron', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('digests_last_send_time', '0');

INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('use_old_pics_gen', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_last_comments', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('enable_mooshow', '0');

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shoutbox_floodinterval', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_shouts', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('stored_shouts', '1000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shoutbox_refreshtime', '5000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shout_allow_guest', '0');
CREATE TABLE phpbb_ajax_shoutbox (
	shout_id MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id MEDIUMINT(8) NOT NULL,
	shouter_name VARCHAR(30) NOT NULL DEFAULT 'guest',
	shout_text TEXT NOT NULL,
	shouter_ip VARCHAR(8) NOT NULL DEFAULT '',
	shout_time INT(11) NOT NULL,
	PRIMARY KEY ( shout_id )
);


########################################
##              BUILD 003             ##
########################################



########################################
##              BUILD 004             ##
########################################

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('xmas_gfx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('google_bot_detector', '0');


########################################
##              BUILD 005             ##
########################################

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('logs_path', 'logs');
ALTER TABLE `phpbb_search_results` MODIFY COLUMN search_array MEDIUMTEXT NOT NULL;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_contact_us', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_contact_us', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_new_posts_admin', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('upi2db_max_new_posts_mod', '0');


########################################
##              BUILD 006             ##
########################################



########################################
##              BUILD 007             ##
########################################

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('url_rw_guests', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('lofi_bots', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_checks_register', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('inactive_users_memberlists', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilie_window_rows', '10');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('invert_nav_arrows', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_otf_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_all_pics_link', '1');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_personal_galleries_link', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_color', '#888888');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_pic_upload', '1');


########################################
##              BUILD 008             ##
########################################

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_postimage_org', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_new_messages_number', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_thanks_topics', '0');
## INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_calendar_box_index', '0');


########################################
##              BUILD 009             ##
########################################



########################################
##              BUILD 010             ##
########################################

CREATE TABLE `phpbb_ajax_shoutbox_sessions` (
  `session_id` int(10) NOT NULL auto_increment,
  `session_user_id` mediumint(8) NOT NULL default '0',
  `session_username` varchar(25) NOT NULL default '',
  `session_ip` varchar(8) NOT NULL default '0',
  `session_start` int(11) NOT NULL default '0',
  `session_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`session_id`)
);

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_ajax_chat', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_ajax_chat', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('auth_view_ajax_chat_archive', '5');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('wide_blocks_ajax_chat_archive', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ajax_features', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_rss_forum_icon', '0');

CREATE TABLE `phpbb_cms_nav_menu` (
	`menu_item_id` mediumint(8) unsigned NOT NULL auto_increment,
	`menu_id` mediumint(8) unsigned NOT NULL default '0',
	`menu_parent_id` mediumint(8) unsigned NOT NULL default '0',
	`cat_id` mediumint(8) unsigned NOT NULL default '0',
	`cat_parent_id` mediumint(8) unsigned NOT NULL default '0',
	`menu_status` tinyint(1) NOT NULL default '0',
	`menu_order` smallint(5) NOT NULL default '0',
	`menu_icon` varchar(255) default NULL,
	`menu_name_lang` varchar(150) default NULL,
	`menu_name` varchar(150) default NULL,
	`menu_desc` text,
	`menu_link` varchar(255) default NULL,
	`menu_link_external` tinyint(1) NOT NULL default '0',
	`auth_view` tinyint(2) NOT NULL default '0',
	`auth_view_group` smallint(5) NOT NULL default '0',
	PRIMARY KEY (`menu_item_id`),
	KEY `cat_id` (`cat_id`)
);

ALTER TABLE `phpbb_users` ADD `user_color` VARCHAR(50) DEFAULT '' NOT NULL AFTER `user_color_group`;


########################################
##              BUILD 011             ##
########################################

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_acronyms', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_autolinks', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_censor', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_topic_view', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smart_header', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('page_title_simple', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_referrers', '0');

ALTER TABLE `phpbb_config` CHANGE `config_value` `config_value` TEXT NOT NULL;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_top_html_block', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('top_html_block_text', 'Text');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('switch_bottom_html_block', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bottom_html_block_text', 'Text');

INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (1, 1, 0, 0, 0, 0, 0, NULL, 'main_links', 'Main Links', 'Main Links Block', NULL, 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (2, 0, 1, 1, 0, 1, 1, './images/menu/application_view_tile.png', 'main_links', 'Main Links', 'Main Links', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (3, 0, 1, 2, 0, 1, 2, './images/menu/newspaper.png', 'news', 'News', 'News', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (4, 0, 1, 3, 0, 1, 3, './images/menu/information.png', 'info_links', 'Info', 'Info', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (5, 0, 1, 4, 0, 1, 4, './images/menu/group.png', 'users_links', 'Users', 'Users & Groups', '', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (6, 0, 1, 0, 1, 1, 1, '', 'acp', 'ACP', 'ACP', 'adm/index.php', 0, 4, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (7, 0, 1, 0, 1, 1, 2, '', 'cms', 'CMS', 'CMS', 'cms.php', 0, 4, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (8, 0, 1, 0, 1, 1, 3, '', 'home', 'Home', 'Home Page', 'index.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (9, 0, 1, 0, 1, 1, 4, '', 'forum', 'Forum', 'Forum', 'forum.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (10, 0, 1, 0, 1, 1, 5, '', 'rules', 'Rules', 'Rules', 'rules.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (11, 0, 1, 0, 1, 1, 6, '', 'faq', 'FAQ', 'FAQ', 'faq.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (12, 0, 1, 0, 1, 1, 7, '', 'search', 'Search', 'Search', 'search.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (13, 0, 1, 0, 1, 1, 8, '', 'album', 'Album', 'Album', 'album.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (14, 0, 1, 0, 1, 1, 9, '', 'calendar', 'Calendar', 'Calendar', 'calendar.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (15, 0, 1, 0, 1, 1, 10, '', 'downloads', 'Downloads', 'Downloads', 'dload.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (16, 0, 1, 0, 1, 1, 11, '', 'profile', 'Profile', 'Profile', 'profile_main.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (17, 0, 1, 0, 1, 1, 12, '', 'ajax_chat', 'Chat', 'Chat', 'ajax_chat.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (18, 0, 1, 0, 1, 1, 13, '', 'links', 'Links', 'Links', 'links.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (19, 0, 1, 0, 1, 1, 14, '', 'kb', 'Knowledge Base', 'Knowledge Base', 'kb.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (20, 0, 1, 0, 1, 1, 15, '', 'contact_us', 'Contact Us', 'Contact Us', 'contact_us.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (21, 0, 1, 0, 1, 1, 16, '', 'sudoku', 'Sudoku', 'Sudoku', 'sudoku.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (22, 0, 1, 0, 2, 1, 1, '', 'news_cat', 'News Categories', 'News Categories', 'index.php?news=categories', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (23, 0, 1, 0, 2, 1, 2, '', 'news_arc', 'News Archives', 'News Archives', 'index.php?news=archives', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (24, 0, 1, 0, 2, 1, 3, '', 'digests', 'Digests', 'Digests', 'digests.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (25, 0, 1, 0, 3, 1, 1, '', 'credits', 'Credits', 'Credits', 'credits.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (26, 0, 1, 0, 3, 1, 2, '', 'viewonline', 'Who Is Online', 'Who Is Online', 'viewonline.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (27, 0, 1, 0, 3, 1, 3, '', 'statistics', 'Statistics', 'Statistics', 'statistics.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (28, 0, 1, 0, 3, 1, 4, '', 'site_hist', 'Site History', 'Site History', 'site_hist.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (29, 0, 1, 0, 4, 1, 1, '', 'memberlist', 'Memberlist', 'Memberlist', 'memberlist.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (30, 0, 1, 0, 4, 1, 2, '', 'usergroups', 'Usergroups', 'Usergroups', 'groupcp.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (31, 0, 1, 0, 4, 1, 3, '', 'staff', 'Staff', 'Staff', 'memberlist.php?mode=staff', 0, 0, 0);


########################################
##              BUILD 012             ##
########################################
ALTER TABLE `phpbb_cms_blocks` DROP `cache`;
ALTER TABLE `phpbb_cms_blocks` DROP `cache_time`;
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'portal_header';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'portal_tail';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'cache_enabled';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'md_cache_file_locking';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'md_cache_write_control';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'md_cache_read_control';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'md_cache_read_type';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'md_cache_filename_protect';
DELETE FROM `phpbb_cms_config` WHERE `config_name` = 'md_cache_serialize';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'portal_header';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'portal_tail';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'cache_enabled';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'md_cache_file_locking';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'md_cache_write_control';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'md_cache_read_control';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'md_cache_read_type';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'md_cache_filename_protect';
DELETE FROM `phpbb_cms_block_variable` WHERE `config_name` = 'md_cache_serialize';
UPDATE phpbb_album_config SET config_value = '.0.56' WHERE config_name = 'album_version';



########################################
##              BUILD 013             ##
########################################



########################################
##              BUILD 014             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('digests_php_cron_lock', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_only_id2_admin', '0');



########################################
##              BUILD 015             ##
########################################



########################################
##              BUILD 016             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('mg_log_actions', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable', 0);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_display_after_posts', 1);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_post_message', 'You earned %s for that post');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable_spam_num', 10);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable_spam_time', 24);
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_disable_spam_message', 'You have exceeded the alloted amount of posts and will not earn anything for your post');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_installed', 'yes');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_version', '2.2.3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_adminbig', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cash_adminnavbar', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('points_name', 'Points');

CREATE TABLE phpbb_cash (
  cash_id smallint(6) NOT NULL auto_increment,
  cash_order smallint(6) NOT NULL default '0',
  cash_settings smallint(4) NOT NULL default '3313',
  cash_dbfield varchar(64) NOT NULL default '',
  cash_name varchar(64) NOT NULL default 'GP',
  cash_default int(11) NOT NULL default '0',
  cash_decimals tinyint(2) NOT NULL default '0',
  cash_imageurl varchar(255) NOT NULL default '',
  cash_exchange int(11) NOT NULL default '1',
  cash_perpost int(11) NOT NULL default '25',
  cash_postbonus int(11) NOT NULL default '2',
  cash_perreply int(11) NOT NULL default '25',
  cash_maxearn int(11) NOT NULL default '75',
  cash_perpm int(11) NOT NULL default '0',
  cash_perchar int(11) NOT NULL default '20',
  cash_allowance tinyint(1) NOT NULL default '0',
  cash_allowanceamount int(11) NOT NULL default '0',
  cash_allowancetime tinyint(2) NOT NULL default '2',
  cash_allowancenext int(11) NOT NULL default '0',
  cash_forumlist varchar(255) NOT NULL default '',
  PRIMARY KEY  (cash_id)
);

CREATE TABLE phpbb_cash_events (
  event_name varchar(32) NOT NULL default '',
  event_data varchar(255) NOT NULL default '',
  PRIMARY KEY  (event_name)
);

CREATE TABLE phpbb_cash_exchange (
  ex_cash_id1 int(11) NOT NULL default '0',
  ex_cash_id2 int(11) NOT NULL default '0',
  ex_cash_enabled int(1) NOT NULL default '0',
  PRIMARY KEY  (ex_cash_id1,ex_cash_id2)
);

CREATE TABLE phpbb_cash_groups (
  group_id mediumint(6) NOT NULL default '0',
  group_type tinyint(2) NOT NULL default '0',
  cash_id smallint(6) NOT NULL default '0',
  cash_perpost int(11) NOT NULL default '0',
  cash_postbonus int(11) NOT NULL default '0',
  cash_perreply int(11) NOT NULL default '0',
  cash_perchar int(11) NOT NULL default '0',
  cash_maxearn int(11) NOT NULL default '0',
  cash_perpm int(11) NOT NULL default '0',
  cash_allowance tinyint(1) NOT NULL default '0',
  cash_allowanceamount int(11) NOT NULL default '0',
  cash_allowancetime tinyint(2) NOT NULL default '2',
  cash_allowancenext int(11) NOT NULL default '0',
  PRIMARY KEY  (group_id,group_type,cash_id)
);

CREATE TABLE phpbb_cash_log (
  log_id int(11) NOT NULL auto_increment,
  log_time int(11) NOT NULL default '0',
  log_type smallint(6) NOT NULL default '0',
  log_action varchar(255) NOT NULL default '',
  log_text varchar(255) NOT NULL default '',
  PRIMARY KEY  (log_id)
);

##
## NOT NEEDED
## ALTER TABLE phpbb_users ADD COLUMN user_actmail_last_checked INT(11) NOT NULL DEFAULT 0 AFTER `user_actkey`;
##

CREATE TABLE phpbb_album_comment_watch (
  pic_id mediumint(8) UNSIGNED NOT NULL default '0',
  user_id mediumint(8) NOT NULL default '0',
  notify_status tinyint(1) NOT NULL default '0',
  KEY pic_id (pic_id),
  KEY user_id (user_id),
  KEY notify_status (notify_status)
);

ALTER TABLE phpbb_groups ADD COLUMN group_rank mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER group_single_user;
ALTER TABLE phpbb_groups ADD COLUMN group_color varchar(50) DEFAULT '' NOT NULL AFTER group_rank;
ALTER TABLE phpbb_groups ADD COLUMN group_legend tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER group_color;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_users_color', '#224455');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('active_users_legend', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_legend', '1');
DROP TABLE `phpbb_color_groups`;
ALTER TABLE `phpbb_groups` DROP `group_color_group`;
UPDATE phpbb_users SET user_color_group = '0';



########################################
##              BUILD 017             ##
########################################
UPDATE `phpbb_themes` SET td_class3 = 'row3';



########################################
##              BUILD 018             ##
########################################
ALTER TABLE `phpbb_album_cat` ADD COLUMN `cat_pics` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `cat_wm`;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_social_bookmarks', '0');
ALTER TABLE `phpbb_users` DROP `user_session_topic`;
ALTER TABLE `phpbb_sessions` DROP `session_topic`;
ALTER TABLE `phpbb_users` CHANGE `user_session_page` `user_session_page` varchar(255) NOT NULL default '';
ALTER TABLE `phpbb_sessions` CHANGE `session_page` `session_page` varchar(255) NOT NULL default '';

CREATE TABLE `phpbb_cms_layout_special` (
  `lsid` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `filename` varchar(100) NOT NULL default '',
  `template` varchar(100) NOT NULL default '',
  `global_blocks` tinyint(1) NOT NULL default '0',
  `view` tinyint(1) NOT NULL default '0',
  `edit_auth` tinyint(1) NOT NULL default '5',
  `groups` tinytext NOT NULL,
  PRIMARY KEY  (`lsid`)
);

ALTER TABLE `phpbb_cms_blocks` CHANGE `layout` `layout` int(10) NOT NULL default '0';
ALTER TABLE `phpbb_cms_blocks` ADD `layout_special` int(10) NOT NULL default '0' AFTER `layout`;

INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('1', 'forum', 'forum', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('2', 'viewf', 'viewforum', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('3', 'viewt', 'viewtopic', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('4', 'viewonline', 'viewonline', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('5', 'search', 'search', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('6', 'profile', 'profile', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('7', 'memberlist', 'memberlist', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('8', 'group_cp', 'groupcp', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('9', 'faq', 'faq', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('10', 'rules', 'rules', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('11', 'download', 'dload', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('12', 'album', 'album', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('13', 'links', 'links', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('14', 'statistics', 'statistics', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('15', 'site_hist', 'site_hist', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('16', 'calendar', 'calendar', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('17', 'recent', 'recent', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('18', 'referrers', 'referrers', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('19', 'shoutbox', 'shoutbox_max', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('20', 'kb', 'kb', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`lsid`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('21', 'contact_us', 'contact_us', 0, 0, '');



########################################
##              BUILD 019             ##
########################################
UPDATE `phpbb_users` SET `user_session_page` = 'index.php';
UPDATE `phpbb_sessions` SET `session_page` = 'index.php';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_forums_online_users', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cms_dock', '0');



########################################
##              BUILD 020             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_drafts', '1');
CREATE TABLE `phpbb_drafts` (
	`draft_id` mediumint(8) UNSIGNED NOT NULL auto_increment,
	`user_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`save_time` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`draft_subject` varchar(100) DEFAULT '' NOT NULL,
	`draft_message` text,
	PRIMARY KEY (`draft_id`),
	KEY `save_time` (`save_time`)
);



########################################
##              BUILD 021             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('smilie_single_row', '20');
DELETE FROM `phpbb_config` WHERE `config_name` = 'allow_only_id2_admin';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_only_main_admin_id', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('main_admin_id', '2');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_mods_edit_admin_posts', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('force_large_caps_mods', '1');

## DOWNLOADS - BEGIN
##
CREATE TABLE phpbb_downloads (
	id INT(11) auto_increment NOT NULL,
	description VARCHAR(255),
	file_name VARCHAR(255) DEFAULT '',
	klicks INT(11) DEFAULT '0',
	free TINYINT(1) DEFAULT '0',
	extern TINYINT(1) DEFAULT '0',
	long_desc TEXT DEFAULT '',
	sort INT(11) DEFAULT '0',
	cat INT(11) DEFAULT '0',
	hacklist TINYINT(1) DEFAULT '0',
	hack_author VARCHAR(255) DEFAULT '',
	hack_author_email VARCHAR(255) DEFAULT '',
	hack_author_website TINYTEXT DEFAULT '',
	hack_version VARCHAR(32) DEFAULT '',
	hack_dl_url TINYTEXT DEFAULT '',
	test varchar(50) DEFAULT '',
	req TEXT DEFAULT '',
	todo TEXT DEFAULT '',
	warning TEXT DEFAULT '',
	mod_desc TEXT DEFAULT '',
	mod_list TINYINT(1) DEFAULT '0',
	file_size BIGINT(20) NOT NULL DEFAULT '0',
	change_time INT(11) DEFAULT '0',
	add_time INT(11) DEFAULT '0',
	rating SMALLINT(5) DEFAULT '0' NOT NULL,
	file_traffic BIGINT(20) NOT NULL DEFAULT '0',
	overall_klicks INT(11) DEFAULT '0',
	approve TINYINT(1) DEFAULT '0',
	add_user MEDIUMINT(8) DEFAULT '0',
	change_user MEDIUMINT(8) DEFAULT '0',
	last_time INT(11) DEFAULT '0',
	down_user MEDIUMINT(8) DEFAULT '0' NOT NULL,
	thumbnail VARCHAR(255) DEFAULT '' NOT NULL,
	broken TINYINT(1) NOT NULL DEFAULT 0,
PRIMARY KEY (id)
);

CREATE TABLE phpbb_downloads_cat (
	id INT(11) auto_increment NOT NULL,
	parent INT(11) DEFAULT '0',
	path VARCHAR(255) DEFAULT '',
	cat_name VARCHAR(255) DEFAULT '',
	sort INT(11) DEFAULT '0',
	description TEXT DEFAULT '',
	rules TEXT DEFAULT '',
	auth_view TINYINT(1) NOT NULL DEFAULT '1',
	auth_dl TINYINT(1) NOT NULL DEFAULT '1',
	auth_up TINYINT(1) NOT NULL DEFAULT '0',
	auth_mod TINYINT(1) NOT NULL DEFAULT '0',
	must_approve TINYINT(1) NOT NULL DEFAULT '0',
	allow_mod_desc TINYINT(1) NOT NULL DEFAULT '0',
	statistics TINYINT(1) NOT NULL DEFAULT '1',
	stats_prune MEDIUMINT(8) NOT NULL DEFAULT '0',
	comments TINYINT(1) NOT NULL DEFAULT '1',
	cat_traffic BIGINT(20) NOT NULL DEFAULT '0',
	cat_traffic_use BIGINT(20) NOT NULL DEFAULT '0',
	allow_thumbs TINYINT(1) NOT NULL DEFAULT '0',
	auth_cread TINYINT(1) NOT NULL DEFAULT '0',
	auth_cpost TINYINT(1) NOT NULL DEFAULT '1',
	approve_comments TINYINT(1) NOT NULL DEFAULT '1',
	bug_tracker TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (id)
);

CREATE TABLE phpbb_dl_auth (
	cat_id INT(11) NOT NULL,
	group_id INT(11) NOT NULL,
	auth_view TINYINT(1) DEFAULT '1' NOT NULL,
	auth_dl TINYINT(1) DEFAULT '1' NOT NULL,
	auth_up TINYINT(1) DEFAULT '1' NOT NULL,
	auth_mod TINYINT(1) DEFAULT '0' NOT NULL
);

CREATE TABLE phpbb_dl_banlist (
	ban_id INT(11) AUTO_INCREMENT NOT NULL,
	user_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
	user_ip CHAR(8) DEFAULT '' NOT NULL,
	user_agent VARCHAR(50) DEFAULT '' NOT NULL,
	username VARCHAR(25) DEFAULT '' NOT NULL,
	guests TINYINT(1) DEFAULT 0 NOT NULL,
PRIMARY KEY (ban_id)
);

CREATE TABLE phpbb_dl_bug_tracker (
	report_id INT(11) AUTO_INCREMENT NOT NULL,
	df_id INT(11) NOT NULL DEFAULT '0',
	report_title VARCHAR(255) DEFAULT '',
	report_text TEXT,
	report_file_ver VARCHAR(50) DEFAULT '',
	report_date INT(11) DEFAULT '0',
	report_author_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
	report_assign_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
	report_assign_date INT(11) DEFAULT '0',
	report_status TINYINT(1) DEFAULT '0' NOT NULL,
	report_status_date INT(11) DEFAULT '0',
	report_php VARCHAR(50) DEFAULT '',
	report_db VARCHAR(50) DEFAULT '',
	report_forum VARCHAR(50) DEFAULT '',
PRIMARY KEY (report_id)
);

CREATE TABLE phpbb_dl_bug_history (
	report_his_id INT(11) AUTO_INCREMENT NOT NULL,
	df_id INT(11) NOT NULL DEFAULT '0',
	report_id INT(11) NOT NULL,
	report_his_type VARCHAR(10) DEFAULT '',
	report_his_date INT(11) DEFAULT '0',
	report_his_value VARCHAR(255),
PRIMARY KEY (report_his_id)
);

CREATE TABLE phpbb_dl_comments (
	dl_id BIGINT(20) unsigned auto_increment NOT NULL,
	id INT(11) NOT NULL DEFAULT '0',
	cat_id INT(11) NOT NULL DEFAULT '0',
	user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
	username VARCHAR(32) NOT NULL DEFAULT '',
	comment_time INT(11) NOT NULL DEFAULT '0',
	comment_edit_time INT(11) NOT NULL DEFAULT '0',
	comment_text TEXT NOT NULL DEFAULT '',
	approve TINYINT(1) NOT NULL DEFAULT '0',
PRIMARY KEY (dl_id)
);

CREATE TABLE phpbb_dl_config (
	config_name VARCHAR(255) NOT NULL DEFAULT '',
	config_value VARCHAR(255) NOT NULL DEFAULT '',
PRIMARY KEY  (config_name)
);

CREATE TABLE phpbb_dl_ext_blacklist (
	extention VARCHAR(10) DEFAULT '' NOT NULL
);

CREATE TABLE phpbb_dl_favorites (
	fav_id INT(11) AUTO_INCREMENT NOT NULL,
	fav_dl_id INT(11) DEFAULT 0 NOT NULL,
	fav_dl_cat INT(11) DEFAULT 0 NOT NULL,
	fav_user_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
PRIMARY KEY (fav_id)
);

CREATE TABLE phpbb_dl_hotlink (
	user_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
	session_id VARCHAR(32) DEFAULT '' NOT NULL,
	hotlink_id VARCHAR(32) DEFAULT '' NOT NULL,
	code VARCHAR(5) DEFAULT '' NOT NULL
);

CREATE TABLE phpbb_dl_notraf (
	user_id MEDIUMINT(8) NOT NULL DEFAULT 0,
	dl_id INT(11) NOT NULL DEFAULT 0
);

CREATE TABLE phpbb_dl_ratings (
	dl_id INT(11) DEFAULT '0',
	user_id MEDIUMINT(8) DEFAULT '0',
	rate_point VARCHAR(10) DEFAULT '0'
);

CREATE TABLE phpbb_dl_stats (
	dl_id BIGINT(20) unsigned auto_increment NOT NULL,
	id INT(11) NOT NULL DEFAULT '0',
	cat_id INT(11) NOT NULL DEFAULT '0',
	user_id MEDIUMINT(8) NOT NULL DEFAULT '0',
	username VARCHAR(32) NOT NULL DEFAULT '',
	traffic BIGINT(20) NOT NULL DEFAULT '0',
	direction TINYINT(1) NOT NULL DEFAULT '0',
	user_ip VARCHAR(8) NOT NULL DEFAULT '',
	browser VARCHAR(20) NOT NULL DEFAULT '',
	time_stamp INT(11) NOT NULL DEFAULT '0',
PRIMARY KEY (dl_id)
);

ALTER TABLE phpbb_groups ADD COLUMN group_dl_auto_traffic BIGINT(20) DEFAULT '0' NOT NULL AFTER `group_count_enable`;

ALTER TABLE phpbb_users ADD COLUMN user_allow_new_download_email TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_allow_fav_download_email TINYINT(1) DEFAULT '1' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_allow_new_download_popup TINYINT(1) DEFAULT '1' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_allow_fav_download_popup TINYINT(1) DEFAULT '1' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_update_time INT( 11 ) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_new_download TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_traffic BIGINT(20) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_note_type TINYINT(1) DEFAULT '1' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_sort_fix TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_sort_opt TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_sort_dir TINYINT(1) DEFAULT '0' NOT NULL;

INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_mod_version', '5.3.0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('delay_auto_traffic', '30');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('delay_post_traffic', '30');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('disable_email', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('disable_popup', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('disable_popup_notify', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_click_reset_time', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_direct', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_edit_time', '3');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_links_per_page', '10');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_method', '2');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_method_quota', '2097152');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_new_time', '3');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_posts', '25');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('dl_stats_perm', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('download_dir', 'downloads/');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('download_vc', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('edit_own_downloads', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('enable_post_dl_traffic', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('guest_stats_show', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('hotlink_action', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('icon_free_for_reg', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('latest_comments', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('limit_desc_on_index', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('newtopic_traffic', '524288');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('overall_traffic', '104857600');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('physical_quota', '524288000');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('prevent_hotlink', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('recent_downloads', '10');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('remain_traffic', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('reply_traffic', '262144');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken_lock', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken_message', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('report_broken_vc', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('shorten_extern_links', '10');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('show_footer_legend', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('show_footer_stat', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('show_real_filetime', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('stop_uploads', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('sort_preform', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('thumb_fsize', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('thumb_xsize', '200');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('thumb_ysize', '150');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('traffic_retime', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('upload_traffic_count', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('use_ext_blacklist', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('use_hacklist', '1');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_dl_auto_traffic', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_traffic_once', '0');

INSERT INTO phpbb_dl_banlist (user_agent) VALUES ('n/a');

INSERT INTO phpbb_dl_ext_blacklist (extention) VALUES
	('asp'), ('cgi'), ('dhtm'), ('dhtml'), ('exe'), ('htm'), ('html'), ('jar'), ('js'), ('php'), ('php3'), ('pl'), ('sh'), ('shtm'), ('shtml');
##
## DOWNLOADS - END



########################################
##              BUILD 022             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_colorpicker', '0');



########################################
##              BUILD 023             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('always_show_edit_by', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_new_reply_posting', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_chat_online', '0');
ALTER TABLE `phpbb_cash` ADD COLUMN `cash_perthanks` INT(11) DEFAULT '5' NOT NULL AFTER `cash_perreply`;

ALTER TABLE `phpbb_users` ADD COLUMN `user_allow_pm_in` TINYINT(1) DEFAULT '1' NOT NULL AFTER `user_allow_pm`;
ALTER TABLE `phpbb_users` ADD COLUMN `user_allow_mass_email` TINYINT(1) DEFAULT '1' NOT NULL AFTER `user_allow_pm_in`;

## FRIENDS AND FOES - BEGIN

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_zebra', '1');

CREATE TABLE phpbb_zebra (
	user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	zebra_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	friend tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	foe tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (user_id, zebra_id)
);

## FRIENDS AND FOES - END



########################################
##              BUILD 025             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('allow_mods_view_self', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('enable_own_icons', '1');
UPDATE `phpbb_ajax_shoutbox` SET shout_text = REPLACE(shout_text, 'http://', 'http:_/_/');
UPDATE `phpbb_ajax_shoutbox` SET shout_text = REPLACE(shout_text, 'www.', 'http:_/_/www.');
UPDATE `phpbb_ajax_shoutbox` SET shout_text = REPLACE(shout_text, 'http:_/_/http:_/_/', 'http:_/_/');
UPDATE `phpbb_users` SET user_allow_pm_in = 1, user_allow_mass_email = 1;



########################################
##              BUILD 026             ##
########################################
ALTER TABLE phpbb_groups ADD COLUMN group_legend_order MEDIUMINT(8) DEFAULT '0' NOT NULL AFTER `group_legend`;
##ALTER TABLE `phpbb_posts` CHANGE `post_edit_count` `post_edit_count` TINYTEXT DEFAULT '';
ALTER TABLE `phpbb_posts` ADD COLUMN `post_edit_id` MEDIUMINT(8) DEFAULT '0' NOT NULL AFTER `post_edit_count`;
ALTER TABLE `phpbb_posts_text` CHANGE `edit_notes` `edit_notes` MEDIUMTEXT DEFAULT '';



########################################
##              BUILD 028             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_thanks_profile', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_thanks_viewtopic', '0');
CREATE TABLE `phpbb_attachments_stats` (
	`attach_id` mediumint(8) unsigned NOT NULL default '0',
	`user_id` mediumint(8) NOT NULL default '0',
	`user_ip` VARCHAR(8) NOT NULL DEFAULT '',
	`user_http_agents` VARCHAR(255) NOT NULL DEFAULT '',
	`download_time` INT(11) NOT NULL DEFAULT '0',
	KEY `attach_id` (`attach_id`)
);
ALTER TABLE `phpbb_pa_download_info` ADD COLUMN `download_time` INT(11) DEFAULT '0' NOT NULL AFTER `user_id`;
ALTER TABLE phpbb_users ADD COLUMN `user_download_counter` MEDIUMINT(8) DEFAULT '0' NOT NULL AFTER `user_traffic`;
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_download_limit_flag', '0');
INSERT INTO phpbb_dl_config (config_name, config_value) VALUES ('user_download_limit', '30');
ALTER TABLE `phpbb_cms_nav_menu` ADD COLUMN menu_default MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('index_top_posters', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('global_disable_upi2db', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('last_user_id', '2');
ALTER TABLE `phpbb_users` CHANGE `user_allow_pm_in` `user_allow_pm_in` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `phpbb_users` CHANGE `user_allow_mass_email` `user_allow_mass_email` TINYINT(1) NOT NULL DEFAULT '1';
UPDATE `phpbb_users` SET user_allow_pm_in = '1', user_allow_mass_email = '1';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('write_errors_log', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('write_digests_log', '0');



########################################
##              BUILD 029             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('no_bump', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('link_this_topic', '0');
ALTER TABLE `phpbb_cms_layout` CHANGE `forum_wide` `global_blocks` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_cms_layout` ADD `page_nav` TINYINT(1) NOT NULL DEFAULT '1' AFTER `global_blocks`;
ALTER TABLE `phpbb_cms_layout` ADD `config_vars` TEXT NOT NULL DEFAULT '' AFTER `page_nav`;
ALTER TABLE `phpbb_cms_layout_special` CHANGE `forum_wide` `global_blocks` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_cms_layout_special` ADD `page_nav` TINYINT(1) NOT NULL DEFAULT '1' AFTER `global_blocks`;
ALTER TABLE `phpbb_cms_layout_special` ADD `config_vars` TEXT NOT NULL DEFAULT '' AFTER `page_nav`;
INSERT INTO `phpbb_cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghtop', 'gt', 0);
INSERT INTO `phpbb_cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghbottom', 'gb', 0);
INSERT INTO `phpbb_cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghleft', 'gl', 0);
INSERT INTO `phpbb_cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghright', 'gr', 0);



########################################
##              BUILD 030             ##
########################################



########################################
##              BUILD 031             ##
########################################
##INSERT INTO `phpbb_cms_config` (`bid`, `config_name`, `config_value`) VALUES (0, 'cms_style', '1');
##INSERT INTO `phpbb_cms_block_variable` (`bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (0, 'CMS Style', 'Select the CMS Style', 'cms_style', 'Yes,No', '1,0', 3, '@Portal Config');
## Someone may not have this!!!
##INSERT INTO `phpbb_cms_block_variable` (`bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (0, 'Default Portal', 'Default Portal', 'default_portal', '', '', 1, '@Portal Config');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cms_style', '0');



########################################
##              BUILD 032             ##
########################################



########################################
##              BUILD 033             ##
########################################



########################################
##              BUILD 034             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_alpha_bar', '0');



########################################
##              BUILD 035             ##
########################################
ALTER TABLE `phpbb_album` ADD COLUMN `pic_size` int(15) unsigned default '0' NOT NULL AFTER `pic_filename`;



########################################
##              BUILD 036             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('db_log_actions', '1');
CREATE TABLE `phpbb_logs` (
	`log_id` int(11) unsigned NOT NULL auto_increment,
	`log_time` varchar(11) NOT NULL,
	`log_page` varchar(255) NOT NULL default '',
	`log_user_id` int(10) NOT NULL,
	`log_action` varchar(60) NOT NULL default '',
	`log_desc` varchar(255) NOT NULL default '',
	`log_target` int(10) NOT NULL default '0',
	PRIMARY KEY  (`log_id`)
);

ALTER TABLE `phpbb_ajax_shoutbox` DROP `shout_uid`;
ALTER TABLE `phpbb_cms_blocks` DROP `block_bbcode_uid`;
ALTER TABLE `phpbb_dl_bug_tracker` DROP `report_uid`;
ALTER TABLE `phpbb_dl_comments` DROP `bbcode_uid`;
ALTER TABLE `phpbb_downloads` DROP `bbcode_uid`;
ALTER TABLE `phpbb_downloads_cat` DROP `bbcode_uid`;
ALTER TABLE `phpbb_kb_articles` DROP `bbcode_uid`;
ALTER TABLE `phpbb_pa_comments` DROP INDEX `comments_id`;
ALTER TABLE `phpbb_pa_comments` DROP INDEX `comment_bbcode_uid`;
ALTER TABLE `phpbb_pa_comments` DROP `comment_bbcode_uid`;
ALTER TABLE `phpbb_posts_text` DROP `bbcode_uid`;
ALTER TABLE `phpbb_privmsgs_text` DROP `privmsgs_bbcode_uid`;
ALTER TABLE `phpbb_shout` DROP `shout_bbcode_uid`;
ALTER TABLE `phpbb_users` DROP `user_sig_bbcode_uid`;

ALTER TABLE `phpbb_topics` CHANGE `topic_title` `topic_title` VARCHAR(255) NOT NULL;
ALTER TABLE `phpbb_privmsgs` CHANGE `privmsgs_subject` `privmsgs_subject` VARCHAR(255) NOT NULL;
##ALTER TABLE `phpbb_topics` ADD `topic_first_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_first_post_id`;
##ALTER TABLE `phpbb_topics` ADD `topic_first_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `topic_first_poster_id`;
##ALTER TABLE `phpbb_topics` ADD `topic_first_poster_colour` varchar(6) DEFAULT '' NOT NULL AFTER `topic_first_poster_name`;
##ALTER TABLE `phpbb_topics` ADD `topic_last_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_last_post_id`;
##ALTER TABLE `phpbb_topics` ADD `topic_last_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `topic_first_post_id`;
##ALTER TABLE `phpbb_topics` ADD `topic_last_poster_colour` varchar(6) DEFAULT '' NOT NULL AFTER `topic_first_poster_name`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_topic_description', '0');



########################################
##              BUILD 037             ##
########################################



#####################

##UPDATE phpbb_config SET config_value = '1' WHERE config_name = 'allow_only_main_admin_id';
##UPDATE phpbb_config SET config_value = '2' WHERE config_name = 'main_admin_id';

#-- DB CHANGES FOR VERSIONING
UPDATE phpbb_config SET config_value = '3.0.7' WHERE config_name = 'upi2db_version';
UPDATE phpbb_album_config SET config_value = '1.5.0' WHERE config_name = 'fap_version';
UPDATE phpbb_config SET config_value = '.0.23' WHERE config_name = 'version';
UPDATE phpbb_config SET config_value = '1.2.10.37' WHERE config_name = 'ip_version';
