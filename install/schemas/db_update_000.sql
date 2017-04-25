SET default_storage_engine = MYISAM;

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
ALTER TABLE `phpbb_users` ADD `ct_last_post` INT( 11 ) NULL DEFAULT 1 AFTER `ct_search_count`;
ALTER TABLE `phpbb_users` ADD `ct_post_counter` MEDIUMINT( 8 ) NULL DEFAULT 1 AFTER `ct_last_post`;
ALTER TABLE `phpbb_users` ADD `ct_enable_ip_warn` TINYINT( 1 ) NULL DEFAULT 1 AFTER `ct_post_counter`;
ALTER TABLE `phpbb_users` ADD `ct_last_used_ip` VARCHAR( 40 ) NULL DEFAULT '0.0.0.0' AFTER `ct_enable_ip_warn`;
ALTER TABLE `phpbb_users` ADD `ct_last_ip` VARCHAR( 40 ) NULL DEFAULT '0.0.0.0' AFTER `ct_last_used_ip`;
ALTER TABLE `phpbb_users` ADD `ct_global_msg_read` TINYINT( 1 ) NULL DEFAULT 0 AFTER `ct_last_ip`;
ALTER TABLE `phpbb_users` ADD `ct_miserable_user` TINYINT( 1 ) NULL DEFAULT 0 AFTER `ct_global_msg_read`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_enabled', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_logsize', '100');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_auto_recovery', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_vconfirm_guest', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_autoban_mails', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_guest', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_user', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_guest', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_user', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_protection', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_protection', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_blocktime', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_lastip', '0.0.0.0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pwreset_time', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_time', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_time', '30');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_postcount', '4');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_blockmode', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_loginfeature', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_pw_reset_feature', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_reg_last_reg', '1155944976');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_history', '1');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_history_count', '10');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ctracker_login_ip_check', '1');
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

CREATE TABLE `phpbb_ctracker_filechk` (
	`filepath` TEXT NOT NULL,
	`hash` varchar(32) default NULL
	);

CREATE TABLE `phpbb_ctracker_filescanner` (
	`id` smallint(5) NOT NULL,
	`filepath` TEXT NOT NULL,
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
	`ct_login_ip` varchar(40) default NULL,
	`ct_login_time` int(11) NOT NULL default '0'
	);
## Cracker Tracker - END

INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('quick_thumbs', '0');
ALTER TABLE `phpbb_album_cat` ADD `cat_wm` TEXT AFTER `cat_desc`;

INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('set_memory', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('lb_preview', '0');

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
  `content` TEXT NOT NULL,
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
  `groups` TINYTEXT NOT NULL,
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
  `groups` TINYTEXT NOT NULL,
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

INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (1, 'Nav Links', '', 'hl', 1, 1, 'nav_links', 0, 0, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (2, 'Nav Links', '', 'l', 1, 1, 'nav_links', 0, 1, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (3, 'Recent', '', 'l', 3, 0, 'recent_topics', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (4, 'Poll', '', 'r', 4, 1, 'poll', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (5, 'Welcome', '', 'c', 1, 1, 'welcome', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (6, 'News', '', 'x', 1, 1, 'news', 0, 1, 0, 0, 0, 0, 0, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (7, 'User Block', '', 'r', 1, 1, 'user_block', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (8, 'Top Posters', '', 'r', 5, 1, 'top_posters', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (9, 'Search', '', 'l', 1, 1, 'search', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (10, 'Who is Online', '', 'r', 2, 1, 'online_users', 0, 1, 1, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (11, 'Album', '', 'l', 2, 1, 'album', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (12, 'Links', '', 'l', 4, 1, 'links', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (13, 'Statistics', '', 'r', 3, 1, 'statistics', 0, 1, 0, 1, 1, 1, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (14, 'Wordgraph', '', 'b', 2, 1, 'wordgraph', 0, 1, 0, 0, 0, 0, 1, '');
INSERT INTO `phpbb_cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (15, 'Welcome', '<table>\r\n	<tr>\r\n		<td width=\"5%\"><img src=\"images/icy_phoenix_small.png\" alt=\"\" /></td>\r\n		<td width=\"90%\" align=\"center\"><div class=\"post-text\">Welcome To <b>Icy Phoenix</b></div><br /><br /></td>\r\n		<td width=\"5%\"><img src=\"images/icy_phoenix_small_l.png\" alt=\"\" /></td>\r\n	</tr>\r\n</table>', 'c', 2, 1, '', 0, 1, 0, 1, 1, 1, 1, '');

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

INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('use_old_pics_gen', '0');
INSERT INTO `phpbb_album_config` (`config_name`, `config_value`) VALUES ('show_last_comments', '0');

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shoutbox_floodinterval', '3');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('display_shouts', '20');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('stored_shouts', '1000');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('shout_allow_guest', '0');
CREATE TABLE phpbb_ajax_shoutbox (
	shout_id MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id MEDIUMINT(8) NOT NULL,
	shouter_name VARCHAR(30) NOT NULL DEFAULT 'guest',
	shout_text TEXT NOT NULL,
	shouter_ip VARCHAR(40) NOT NULL DEFAULT '',
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


########################################
##              BUILD 005             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('logs_path', 'logs');
ALTER TABLE `phpbb_search_results` MODIFY COLUMN search_array MEDIUMTEXT NOT NULL;
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('inactive_users_memberlists', '0');
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
  `session_ip` varchar(40) NOT NULL default '0',
  `session_start` int(11) NOT NULL default '0',
  `session_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`session_id`)
);

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
	`menu_desc` TEXT NOT NULL,
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
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('page_title_simple', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('disable_referers', '0');

ALTER TABLE `phpbb_config` CHANGE `config_value` `config_value` TEXT;

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
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (19, 0, 1, 0, 1, 1, 14, '', 'kb', 'Knowledge Base', 'Knowledge Base', 'kb.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (20, 0, 1, 0, 1, 1, 15, '', 'contact_us', 'Contact Us', 'Contact Us', 'contact_us.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (22, 0, 1, 0, 2, 1, 1, '', 'news_cat', 'News Categories', 'News Categories', 'index.php?news=categories', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (23, 0, 1, 0, 2, 1, 2, '', 'news_arc', 'News Archives', 'News Archives', 'index.php?news=archives', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (24, 0, 1, 0, 2, 1, 3, '', 'digests', 'Digests', 'Digests', 'digests.php', 0, 2, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (25, 0, 1, 0, 3, 1, 1, '', 'credits', 'Credits', 'Credits', 'credits.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (26, 0, 1, 0, 3, 1, 2, '', 'viewonline', 'Who Is Online', 'Who Is Online', 'viewonline.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (27, 0, 1, 0, 3, 1, 3, '', 'statistics', 'Statistics', 'Statistics', 'statistics.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (28, 0, 1, 0, 4, 1, 1, '', 'memberlist', 'Memberlist', 'Memberlist', 'memberlist.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (29, 0, 1, 0, 4, 1, 2, '', 'usergroups', 'Usergroups', 'Usergroups', 'groupcp.php', 0, 0, 0);
INSERT INTO `phpbb_cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (30, 0, 1, 0, 4, 1, 3, '', 'staff', 'Staff', 'Staff', 'memberlist.php?mode=staff', 0, 0, 0);


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
	`page_id` varchar(100) NOT NULL DEFAULT '',
	`locked` tinyint(1) NOT NULL DEFAULT '1',
	`name` varchar(100) NOT NULL DEFAULT '',
	`filename` varchar(100) NOT NULL DEFAULT '',
	`template` varchar(100) NOT NULL DEFAULT '',
	`global_blocks` tinyint(1) NOT NULL DEFAULT '0',
	`page_nav` tinyint(1) NOT NULL DEFAULT '1',
	`config_vars` TEXT NOT NULL,
	`view` tinyint(1) NOT NULL DEFAULT '0',
	`groups` TINYTEXT NOT NULL,
	PRIMARY KEY (`lsid`),
	UNIQUE KEY `page_id` (`page_id`)
);

ALTER TABLE `phpbb_cms_blocks` CHANGE `layout` `layout` int(10) NOT NULL default '0';
ALTER TABLE `phpbb_cms_blocks` ADD `layout_special` int(10) NOT NULL default '0' AFTER `layout`;

INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('forum', 'forum', 'forum.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('viewf', 'viewf', 'viewforum.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('viewt', 'viewt', 'viewtopic.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('viewonline', 'viewonline', 'viewonline.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('search', 'search', 'search.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('profile', 'profile', 'profile.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('memberlist', 'memberlist', 'memberlist.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('group_cp', 'group_cp', 'groupcp.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('faq', 'faq', 'faq.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('rules', 'rules', 'rules.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('download', 'download', 'dload.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('album', 'album', 'album.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('links', 'links', 'links.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('statistics', 'statistics', 'statistics.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('calendar', 'calendar', 'calendar.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('recent', 'recent', 'recent.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('referers', 'referers', 'referers.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('shoutbox', 'shoutbox', 'shoutbox_max.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('kb', 'kb', 'kb.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('contact_us', 'contact_us', 'contact_us.php', 0, 0, '');



########################################
##              BUILD 019             ##
########################################
UPDATE `phpbb_users` SET `user_session_page` = 'index.php';
UPDATE `phpbb_sessions` SET `session_page` = 'index.php';
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_forums_online_users', '0');



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
	`draft_message` TEXT NOT NULL,
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
	long_desc TEXT NOT NULL,
	sort INT(11) DEFAULT '0',
	cat INT(11) DEFAULT '0',
	hacklist TINYINT(1) DEFAULT '0',
	hack_author VARCHAR(255) DEFAULT '',
	hack_author_email VARCHAR(255) DEFAULT '',
	hack_author_website TINYTEXT,
	hack_version VARCHAR(32) DEFAULT '',
	hack_dl_url TINYTEXT,
	test varchar(50) DEFAULT '',
	req TEXT NOT NULL,
	todo TEXT NOT NULL,
	warning TEXT NOT NULL,
	mod_desc TEXT NOT NULL,
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
	description TEXT NOT NULL,
	rules TEXT NOT NULL,
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
	user_ip CHAR(40) DEFAULT '' NOT NULL,
	user_agent VARCHAR(50) DEFAULT '' NOT NULL,
	username VARCHAR(25) DEFAULT '' NOT NULL,
	guests TINYINT(1) DEFAULT 0 NOT NULL,
PRIMARY KEY (ban_id)
);

CREATE TABLE phpbb_dl_bug_tracker (
	report_id INT(11) AUTO_INCREMENT NOT NULL,
	df_id INT(11) NOT NULL DEFAULT '0',
	report_title VARCHAR(255) DEFAULT '',
	report_text TEXT NOT NULL,
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
	comment_text TEXT NOT NULL,
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
	user_ip VARCHAR(40) NOT NULL DEFAULT '',
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
##ALTER TABLE `phpbb_posts` CHANGE `post_edit_count` `post_edit_count` TINYTEXT;
ALTER TABLE `phpbb_posts` ADD COLUMN `post_edit_id` MEDIUMINT(8) DEFAULT '0' NOT NULL AFTER `post_edit_count`;
ALTER TABLE `phpbb_posts` CHANGE `edit_notes` `edit_notes` MEDIUMTEXT DEFAULT '';



########################################
##              BUILD 028             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_thanks_profile', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('show_thanks_viewtopic', '0');
CREATE TABLE `phpbb_attachments_stats` (
	`attach_id` mediumint(8) unsigned NOT NULL default '0',
	`user_id` mediumint(8) NOT NULL default '0',
	`user_ip` VARCHAR(8) NOT NULL DEFAULT '',
	`user_browser` VARCHAR(255) NOT NULL DEFAULT '',
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
ALTER TABLE `phpbb_cms_layout` ADD `config_vars` TEXT AFTER `page_nav`;
ALTER TABLE `phpbb_cms_layout_special` CHANGE `forum_wide` `global_blocks` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `phpbb_cms_layout_special` ADD `page_nav` TINYINT(1) NOT NULL DEFAULT '1' AFTER `global_blocks`;
ALTER TABLE `phpbb_cms_layout_special` ADD `config_vars` TEXT AFTER `page_nav`;
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
## Someone may not have this!!!
##INSERT INTO `phpbb_cms_block_variable` (`bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (0, 'Default Portal', 'Default Portal', 'default_portal', '', '', 1, '@Portal Config');



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
	`log_desc` mediumtext NOT NULL,
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
ALTER TABLE `phpbb_posts` DROP `bbcode_uid`;
ALTER TABLE `phpbb_privmsgs_text` DROP `privmsgs_bbcode_uid`;
ALTER TABLE `phpbb_shout` DROP `shout_bbcode_uid`;
ALTER TABLE `phpbb_users` DROP `user_sig_bbcode_uid`;

ALTER TABLE `phpbb_privmsgs` CHANGE `privmsgs_subject` `privmsgs_subject` VARCHAR(255) NOT NULL;
ALTER TABLE `phpbb_topics` CHANGE `topic_title` `topic_title` VARCHAR(255) NOT NULL;
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
ALTER TABLE `phpbb_users` ADD COLUMN `user_birthday_y` VARCHAR(4) DEFAULT '' NOT NULL AFTER `user_birthday`;
ALTER TABLE `phpbb_users` ADD COLUMN `user_birthday_m` VARCHAR(2) DEFAULT '' NOT NULL AFTER `user_birthday_y`;
ALTER TABLE `phpbb_users` ADD COLUMN `user_birthday_d` VARCHAR(2) DEFAULT '' NOT NULL AFTER `user_birthday_m`;
#ALTER TABLE `phpbb_users` DROP `user_birthday`;

DELETE FROM `phpbb_config` WHERE `config_name` = 'allow_only_main_admin_id';



########################################
##              BUILD 038             ##
########################################
CREATE TABLE phpbb_bots (
	bot_id mediumint(8) UNSIGNED NOT NULL auto_increment,
	bot_active tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
	bot_name varchar(255) DEFAULT '' NOT NULL,
	bot_color varchar(255) DEFAULT '' NOT NULL,
	bot_agent varchar(255) DEFAULT '' NOT NULL,
	bot_ip varchar(255) DEFAULT '' NOT NULL,
	bot_last_visit varchar(11) DEFAULT '0' NOT NULL,
	bot_visit_counter mediumint(8) DEFAULT '0' NOT NULL,
	PRIMARY KEY (bot_id),
	KEY bot_name (bot_name),
	KEY bot_active (bot_active)
);

INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo! Slurp', '<b style="color:#d22;">Yahoo!</b><b style="color:#24b;"> Slurp</b>', 'Yahoo! Slurp', '66.106, 68.142, 72.30, 74.6, 202.160.180');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b>', 'Googlebot', '66.249');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSN', '<b style="color:#468;">MSN</b>', 'msnbot/', '207.66.146, 207.46, 65.54.188, 65.54.246, 65.54.165, 65.55.210, 65.55.213');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('LiveBot', '<b style="color:#468;">LiveBot</b>', 'LiveBot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AdsBot [Google]', '', 'AdsBot-Google', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Adsense', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b><b style="color:#d22;"> Adsense</b>', 'Mediapartners-Google', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo! DE Slurp', '<b style="color:#d22;">Yahoo!</b><b style="color:#24b;"> DE Slurp</b><b style="color:#888;"> [Bot]</b>', 'Yahoo! DE Slurp', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo MMCrawler', '<b style="color:#d22;">Yahoo!</b><b style="color:#24b;"> MMCrawler</b>', 'Yahoo-MMCrawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YahooSeeker', '', 'YahooSeeker/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Desktop', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b><b style="color:#d22;"> Desktop</b>', 'Google Desktop', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Feedfetcher', '<b style="color:#24b;">G</b><b style="color:#d22;">o</b><b style="color:#eb0;">o</b><b style="color:#24b;">g</b><b style="color:#393;">l</b><b style="color:#d22;">e</b><b style="color:#d22;"> Feedfetcher</b>', 'Feedfetcher-Google', '72.14.199');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSN NewsBlogs', '<b style="color:#468;">MSN NewsBlogs</b>', 'msnbot-NewsBlogs/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSNbot Media', '<b style="color:#468;">MSNbot Media</b>', 'msnbot-media/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alexa', '', 'ia_archiver', '207.209.238');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alta Vista', '', 'Scooter/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AllTheWeb', '', 'alltheweb', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Arianna', '', 'www.arianna.it', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves', '', 'Ask Jeeves', '65.214.44');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves Teoma', '', 'teoma', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Baidu [Spider]', '', 'Baiduspider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Become', '', 'BecomeBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Charlotte', '', 'Charlotte/1.1', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eBay', '', '', '212.222.51');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eDintorni Crawler', '', 'eDintorni', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Exabot', '', 'Exabot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FAST Enterprise [Crawler]', '', 'FAST Enterprise Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FAST WebCrawler [Crawler]', '', 'FAST-WebCrawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Francis', '', 'http://www.neomo.de/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Gigablast', '', '', '66.154.102, 66.154.103');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Gigabot', '', 'Gigabot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Heise IT-Markt [Crawler]', '', 'heise-IT-Markt-Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Heritrix [Crawler]', '', 'heritrix/1.', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('JetBot', '', 'Jetbot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Kosmix', '', 'www.kosmix.com', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IBM Research', '', 'ibm.com/cs/crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ICCrawler - ICjobs', '', 'ICCrawler - ICjobs', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ichiro [Crawler]', '', 'ichiro/2', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IEAutoDiscovery', '', 'IEAutoDiscovery', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Indy Library', '', 'Indy Library', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Infoseek', '', 'Infoseek', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Inktomi', '', '', '66.94.229, 66.228.165');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('LookSmart', '', 'MARTINI', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Lycos', '', 'Lycos', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MagpieRSS', '', 'MagpieRSS', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Majestic-12', '', 'MJ12bot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Metager', '', 'MetagerBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Microsoft Research', '', 'MSRBOT', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('NG-Search', '', 'NG-Search/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Noxtrum [Crawler]', '', 'noxtrumbot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Nutch', '', 'http://lucene.apache.org/nutch/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Nutch/CVS', '', 'NutchCVS/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Omgili', '', 'omgilibot/0.3', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('OmniExplorer', '', 'OmniExplorer_Bot/', '65.19.150');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Online link [Validator]', '', 'online link validator', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Perl Script', '', 'libwww-perl/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Pompos', '', '', '212.27.41');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('psbot [Picsearch]', '', 'psbot/0', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seekport', '', 'Seekbot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Sensis [Crawler]', '', 'Sensis Web Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEO Crawler [Crawler]', '', 'SEO search Crawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seoma [Crawler]', '', 'Seoma', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEOSearch [Crawler]', '', 'SEOsearch/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snap Bot', '', 'Snapbot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snappy', '', 'Snappy/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Sogou', '', 'www.sogou.com', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Speedy Spider', '', 'Speedy Spider', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Steeler [Crawler]', '', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Synoo', '', 'SynooBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Telekom', '', 'crawleradmin.t-info@telekom.de', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('TurnitinBot', '', 'TurnitinBot/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Twiceler', '', 'Twiceler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Virgilio', '', '', '212.48.8');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voyager', '', 'voyager/1.0', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voila', '', 'VoilaBot', '195.101.94');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3 [Sitesearch]', '', 'W3 SiteSearch Crawler', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Linkcheck]', '', 'W3C-checklink/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Validator]', '', 'W3C_', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WiseNut', '', 'http://www.WISEnutbot.com', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YaCy', '', 'yacybot', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yanga WorldSearch', '', 'Yanga WorldSearch Bot', '');

ALTER TABLE `phpbb_posts` CHANGE `post_subject` `post_subject` VARCHAR(255) NOT NULL;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('bots_reg_auth', '0');



########################################
##              BUILD 039             ##
########################################
DROP TABLE `phpbb_themes_name`;
DROP TABLE `phpbb_themes`;
CREATE TABLE `phpbb_themes` (
	`themes_id` mediumint(8) unsigned NOT NULL auto_increment,
	`template_name` varchar(30) NOT NULL default '',
	`style_name` varchar(30) NOT NULL default '',
	`head_stylesheet` varchar(100) default NULL,
	`body_background` varchar(100) default NULL,
	`body_bgcolor` varchar(6) default NULL,
	`tr_class1` varchar(25) default NULL,
	`tr_class2` varchar(25) default NULL,
	`tr_class3` varchar(25) default NULL,
	`td_class1` varchar(25) default NULL,
	`td_class2` varchar(25) default NULL,
	`td_class3` varchar(25) default NULL,
	PRIMARY KEY (`themes_id`)
);
INSERT `phpbb_themes` VALUES (1, 'icy_phoenix', 'Frozen Phoenix', 'style_cyan.css', 'cyan', '', 'row1', 'row2', 'row3', 'row1', 'row2', 'row3');
UPDATE `phpbb_users` SET `user_style` = '1';
UPDATE `phpbb_config` SET `config_value` = '1' WHERE `config_name` = 'default_style';
UPDATE `phpbb_config` SET `config_value` = 'default' WHERE `config_name` = 'xs_def_template';
UPDATE `phpbb_pa_config` SET `config_value` = 'downloads/' WHERE `config_name` = 'upload_dir';
UPDATE `phpbb_pa_config` SET `config_value` = 'files/screenshots/' WHERE `config_name` = 'screenshots_dir';



########################################
##              BUILD 040             ##
########################################
CREATE TABLE `___posts___` (
	`post_id` mediumint(8) unsigned NOT NULL auto_increment,
	`topic_id` mediumint(8) unsigned NOT NULL default '0',
	`forum_id` smallint(5) unsigned NOT NULL default '0',
	`poster_id` mediumint(8) NOT NULL default '0',
	`post_time` int(11) NOT NULL default '0',
	`poster_ip` varchar(40) NOT NULL default '',
	`post_username` varchar(25) default NULL,
	`post_subject` varchar(255) default NULL,
	`post_text` MEDIUMTEXT NOT NULL,
	`post_text_compiled` MEDIUMTEXT NOT NULL,
	`enable_bbcode` tinyint(1) NOT NULL default '1',
	`enable_html` tinyint(1) NOT NULL default '0',
	`enable_smilies` tinyint(1) NOT NULL default '1',
	`enable_autolinks_acronyms` tinyint(1) NOT NULL default '1',
	`enable_sig` tinyint(1) NOT NULL default '1',
	`edit_notes` MEDIUMTEXT,
	`post_edit_time` int(11) default NULL,
	`post_edit_count` smallint(5) unsigned NOT NULL default '0',
	`post_edit_id` mediumint(8) NOT NULL default '0',
	`post_attachment` tinyint(1) NOT NULL default '0',
	`post_bluecard` tinyint(1) default NULL,
	PRIMARY KEY (`post_id`),
	KEY `forum_id` (`forum_id`),
	KEY `topic_id` (`topic_id`),
	KEY `poster_id` (`poster_id`),
	KEY `post_time` (`post_time`)
);

## Needed for standard phpBB
##ALTER TABLE `phpbb_posts_text` ADD `post_text_compiled` MEDIUMTEXT NOT NULL AFTER `post_text`;
##ALTER TABLE `phpbb_posts_text` ADD `edit_notes` MEDIUMTEXT NOT NULL AFTER `post_text_compiled`;

INSERT INTO `___posts___`
SELECT p.post_id, p.topic_id, p.forum_id, p.poster_id, p.post_time, p.poster_ip, p.post_username, t.post_subject, t.post_text, t.post_text_compiled, p.enable_bbcode, p.enable_html, p.enable_smilies, p.enable_autolinks_acronyms, p.enable_sig, t.edit_notes, p.post_edit_time, p.post_edit_count, p.post_edit_id, p.post_attachment, p.post_bluecard
FROM `phpbb_posts` p, `phpbb_posts_text` t
WHERE p.post_id = t.post_id
ORDER BY p.post_id;

RENAME TABLE `phpbb_posts` TO `_old_phpbb_posts`;
RENAME TABLE `phpbb_posts_text` TO `_old_phpbb_posts_text`;
RENAME TABLE `___posts___` TO `phpbb_posts`;

CREATE TABLE `___privmsgs___` (
	`privmsgs_id` mediumint(8) unsigned NOT NULL auto_increment,
	`privmsgs_type` tinyint(4) NOT NULL default '0',
	`privmsgs_subject` varchar(255) NOT NULL default '',
	`privmsgs_text` TEXT NOT NULL,
	`privmsgs_from_userid` mediumint(8) NOT NULL default '0',
	`privmsgs_to_userid` mediumint(8) NOT NULL default '0',
	`privmsgs_date` int(11) NOT NULL default '0',
	`privmsgs_ip` varchar(40) NOT NULL default '',
	`privmsgs_enable_bbcode` tinyint(1) NOT NULL default '1',
	`privmsgs_enable_html` tinyint(1) NOT NULL default '0',
	`privmsgs_enable_smilies` tinyint(1) NOT NULL default '1',
	`privmsgs_enable_autolinks_acronyms` tinyint(1) NOT NULL default '0',
	`privmsgs_attach_sig` tinyint(1) NOT NULL default '1',
	`privmsgs_attachment` tinyint(1) NOT NULL default '0',
	PRIMARY KEY (`privmsgs_id`),
	KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
	KEY `privmsgs_to_userid` (`privmsgs_to_userid`)
);

INSERT INTO `___privmsgs___`
SELECT p.privmsgs_id, p.privmsgs_type, p.privmsgs_subject, t.privmsgs_text, p.privmsgs_from_userid, p.privmsgs_to_userid, p.privmsgs_date, p.privmsgs_ip, p.privmsgs_enable_bbcode, p.privmsgs_enable_html, p.privmsgs_enable_smilies, p.privmsgs_enable_autolinks_acronyms, p.privmsgs_attach_sig, p.privmsgs_attachment
FROM `phpbb_privmsgs` p, `phpbb_privmsgs_text` t
WHERE p.privmsgs_id = t.privmsgs_text_id
ORDER BY p.privmsgs_id;

RENAME TABLE `phpbb_privmsgs` TO `_old_phpbb_privmsgs`;
RENAME TABLE `phpbb_privmsgs_text` TO `_old_phpbb_privmsgs_text`;
RENAME TABLE `___privmsgs___` TO `phpbb_privmsgs`;

ALTER TABLE `phpbb_privmsgs_archive` ADD COLUMN `privmsgs_text` text AFTER `privmsgs_subject`;



########################################
##              BUILD 041             ##
########################################
CREATE TABLE `___forums___` (
	`forum_id` smallint(5) unsigned NOT NULL default '0',
	`cat_id` mediumint(8) unsigned NOT NULL default '0',
	`main_type` char(1) default 'c',
	`forum_name` varchar(150) default NULL,
	`forum_desc` TEXT NOT NULL,
	`forum_status` tinyint(4) NOT NULL default '0',
	`forum_order` mediumint(8) unsigned NOT NULL default '1',
	`forum_posts` mediumint(8) unsigned NOT NULL default '0',
	`forum_topics` mediumint(8) unsigned NOT NULL default '0',
	`forum_last_post_id` mediumint(8) unsigned NOT NULL default '0',
	`forum_postcount` tinyint(1) NOT NULL default '1',
	`forum_notify` tinyint(1) unsigned NOT NULL default '1',
	`forum_similar_topics` tinyint(1) NOT NULL default '0',
	`forum_tags` tinyint(1) NOT NULL default '0',
	`forum_sort_box` tinyint(1) NOT NULL default '0',
	`forum_kb_mode` tinyint(1) NOT NULL default '0',
	`forum_index_icons` tinyint(1) NOT NULL default '0',
	`forum_rules` tinyint(1) unsigned NOT NULL default '0',
	`forum_link` varchar(255) default NULL,
	`forum_link_internal` tinyint(1) NOT NULL default '0',
	`forum_link_hit_count` tinyint(1) NOT NULL default '0',
	`forum_link_hit` bigint(20) unsigned NOT NULL default '0',
	`icon` varchar(255) default NULL,
	`prune_next` int(11) default NULL,
	`prune_enable` tinyint(1) NOT NULL default '0',
	`auth_view` tinyint(2) NOT NULL default '0',
	`auth_read` tinyint(2) NOT NULL default '0',
	`auth_post` tinyint(2) NOT NULL default '0',
	`auth_reply` tinyint(2) NOT NULL default '0',
	`auth_edit` tinyint(2) NOT NULL default '0',
	`auth_delete` tinyint(2) NOT NULL default '0',
	`auth_sticky` tinyint(2) NOT NULL default '0',
	`auth_announce` tinyint(2) NOT NULL default '0',
	`auth_globalannounce` tinyint(2) NOT NULL default '3',
	`auth_news` tinyint(2) NOT NULL default '2',
	`auth_cal` tinyint(2) NOT NULL default '0',
	`auth_vote` tinyint(2) NOT NULL default '0',
	`auth_pollcreate` tinyint(2) NOT NULL default '0',
	`auth_attachments` tinyint(2) NOT NULL default '0',
	`auth_download` tinyint(2) NOT NULL default '0',
	`auth_ban` tinyint(2) NOT NULL default '3',
	`auth_greencard` tinyint(2) NOT NULL default '5',
	`auth_bluecard` tinyint(2) NOT NULL default '1',
	`auth_rate` tinyint(2) NOT NULL default '-1',
	PRIMARY KEY (`forum_id`),
	KEY `forums_order` (`forum_order`),
	KEY `cat_id` (`cat_id`),
	KEY `forum_last_post_id` (`forum_last_post_id`)
);

INSERT INTO `___forums___`
SELECT f.forum_id, f.cat_id, f.main_type, f.forum_name, f.forum_desc, f.forum_status, f.forum_order, f.forum_posts, f.forum_topics, f.forum_last_post_id, f.forum_postcount, f.forum_notify, 0, 0, 0, 0, 0, 1, forum_link, f.forum_link_internal, f.forum_link_hit_count, f.forum_link_hit, f.icon, f.prune_next, f.prune_enable, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_globalannounce, f.auth_news, f.auth_cal, f.auth_vote, f.auth_pollcreate, f.auth_attachments, f.auth_download, f.auth_ban, f.auth_greencard, f.auth_bluecard, f.auth_rate
FROM `phpbb_forums` f
ORDER BY f.forum_id;

RENAME TABLE `phpbb_forums` TO `_old_phpbb_forums`;
RENAME TABLE `___forums___` TO `phpbb_forums`;

UPDATE phpbb_stats_config SET config_value = 'includes/stats_modules' WHERE config_name = 'modules_dir';



########################################
##              BUILD 042             ##
########################################
#DELETE FROM `phpbb_cms_layout_special` WHERE `lsid` = 15;
DELETE FROM `phpbb_cms_nav_menu` WHERE `menu_link` = 'site_hist.php';

CREATE TABLE `___categories___` (
	`cat_id` mediumint(8) unsigned NOT NULL auto_increment,
	`cat_main` mediumint(8) unsigned NOT NULL default '0',
	`cat_main_type` char(1) default 'c',
	`cat_title` varchar(100) default NULL,
	`cat_desc` TEXT NOT NULL,
	`icon` varchar(255) default NULL,
	`cat_order` mediumint(8) unsigned NOT NULL default '0',
	PRIMARY KEY (`cat_id`),
	KEY `cat_order` (`cat_order`)
);

INSERT INTO `___categories___`
SELECT c.cat_id, c.cat_main, c.cat_main_type, c.cat_title, c.cat_desc, c.icon, c.cat_order
FROM `phpbb_categories` c
ORDER BY c.cat_id;

RENAME TABLE `phpbb_categories` TO `_old_phpbb_categories`;
RENAME TABLE `___categories___` TO `phpbb_categories`;



########################################
##              BUILD 043             ##
########################################
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ScoutJet', '', 'http://www.scoutjet.com/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yandex', '', 'Yandex/', '');

ALTER TABLE phpbb_forums ADD `forum_topic_views` TINYINT(1) NOT NULL DEFAULT '1' AFTER `forum_similar_topics`;



########################################
##              BUILD 044             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_global_switch', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_lock', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_queue_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_queue_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_digests_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_digests_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_files_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_files_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_database_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_database_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_cache_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_cache_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sql_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sql_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_users_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_users_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_topics_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_topics_last_run', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sessions_interval', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_sessions_last_run', '0');

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_db_count', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_db_show_begin_for', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('cron_db_show_not_optimized', '0');

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('rand_seed_last_update', '0');

DELETE FROM `phpbb_config` WHERE `config_name` = 'db_cron';

DROP TABLE `phpbb_optimize_db`;

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('gsearch_guests', '0');

CREATE TABLE `___megamail___` (
	`mail_id` smallint unsigned NOT NULL auto_increment,
	`mailsession_id` varchar(32) NOT NULL,
	`mass_pm` tinyint(1) NOT NULL default '0',
	`user_id` mediumint(8) NOT NULL,
	`group_id` mediumint(8) NOT NULL,
	`email_subject` varchar(255) NOT NULL,
	`email_body` TEXT NOT NULL,
	`email_format` tinyint(1) NOT NULL default '0',
	`batch_start` mediumint(8) NOT NULL,
	`batch_size` smallint UNSIGNED NOT NULL,
	`batch_wait` smallint NOT NULL,
	`status` smallint NOT NULL,
	PRIMARY KEY (`mail_id`)
);

INSERT INTO `___megamail___`
SELECT m.mail_id, m.mailsession_id, 0, m.user_id, m.group_id, m.email_subject, m.email_body, 0, m.batch_start, m.batch_size, m.batch_wait, m.status
FROM `phpbb_megamail` m
ORDER BY m.mail_id;

RENAME TABLE `phpbb_megamail` TO `_old_phpbb_megamail`;
RENAME TABLE `___megamail___` TO `phpbb_megamail`;



########################################
##              BUILD 045             ##
########################################
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Bloglines', '', 'Bloglines/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('DotBot', '', 'dotnetdotcom.org/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FeedBurner', '', 'FeedBurner/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Feedreader', '', 'Feedreader', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Netvibes', '', 'Netvibes', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('NewsGatorOnline', '', 'NewsGatorOnline/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snarfer', '', 'Snarfer/', '');
INSERT INTO `phpbb_bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WikioFeedBot', '', 'WikioFeedBot', '');

INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glh', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_glf', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_fix', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_fit', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_fib', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vfx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vft', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vfb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vtx', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vtt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_vtb', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_nmt', '0');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('ads_nmb', '0');

CREATE TABLE `phpbb_ads` (
	`ad_id` mediumint(8) unsigned NOT NULL auto_increment,
	`ad_title` varchar(255) NOT NULL,
	`ad_text` TEXT NOT NULL,
	`ad_position` varchar(255) NOT NULL,
	`ad_auth` tinyint(1) NOT NULL default '0',
	`ad_format` tinyint(1) NOT NULL default '0',
	`ad_active` tinyint(1) NOT NULL default '0',
	PRIMARY KEY (`ad_id`)
);

DELETE FROM `phpbb_config` WHERE `config_name` = 'switch_top_html_block';
DELETE FROM `phpbb_config` WHERE `config_name` = 'switch_bottom_html_block';
DELETE FROM `phpbb_config` WHERE `config_name` = 'switch_footer_table';
DELETE FROM `phpbb_config` WHERE `config_name` = 'switch_header_banner';
DELETE FROM `phpbb_config` WHERE `config_name` = 'switch_viewtopic_banner';

DELETE FROM `phpbb_config` WHERE `config_name` = 'top_html_block_text';
DELETE FROM `phpbb_config` WHERE `config_name` = 'bottom_html_block_text';
DELETE FROM `phpbb_config` WHERE `config_name` = 'footer_table_text';
DELETE FROM `phpbb_config` WHERE `config_name` = 'header_banner_text';
DELETE FROM `phpbb_config` WHERE `config_name` = 'viewtopic_banner_text';

DELETE FROM `phpbb_extensions` WHERE `extension` = 'tif';
DELETE FROM `phpbb_extensions` WHERE `extension` = 'tga';



########################################
##              BUILD 046             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('adsense_code', '');
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('google_analytics', '');
ALTER TABLE `phpbb_ajax_shoutbox_sessions` CHANGE `session_id` `session_id` INT( 10 ) NOT NULL;
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('thumbnail_highslide', '1');



########################################
##              BUILD 047             ##
########################################



########################################
##              BUILD 048             ##
########################################
INSERT INTO `phpbb_flags` (`flag_name`, `flag_image`) VALUES ('Montenegro', 'montenegro.png');



########################################
##              BUILD 049             ##
########################################
CREATE TABLE phpbb_registration (
	topic_id mediumint(8) unsigned NOT NULL default '0',
	registration_user_id mediumint(8) NOT NULL default '0',
	registration_user_ip varchar(40) NOT NULL default '',
	registration_time int(11) NOT NULL default '0',
	registration_status tinyint(1) NOT NULL default '0',
	KEY topic_id (topic_id),
	KEY registration_user_id (registration_user_id),
	KEY registration_user_ip (registration_user_ip)
);

CREATE TABLE phpbb_registration_desc (
	reg_id mediumint(8) unsigned NOT NULL auto_increment,
	topic_id mediumint(8) unsigned NOT NULL default '0',
	reg_active tinyint(1) NOT NULL default '0',
	reg_max_option1 smallint(5) unsigned NOT NULL default '0',
	reg_max_option2 smallint(5) unsigned NOT NULL default '0',
	reg_max_option3 smallint(5) unsigned NOT NULL default '0',
	reg_start int(11) NOT NULL default '0',
	reg_length int(11) NOT NULL default '0',
	PRIMARY KEY  (reg_id),
	KEY `topic_id` (topic_id)
);

ALTER TABLE phpbb_topics ADD topic_reg TINYINT(1) DEFAULT '0' NOT NULL AFTER topic_calendar_duration;

## FIX FOR CMS_ADV
ALTER TABLE `phpbb_cms_blocks` ADD `border` TINYINT(1) SET DEFAULT '1' AFTER `border_explain`;
ALTER TABLE `phpbb_cms_blocks` ADD `titlebar` TINYINT(1) SET DEFAULT '1' AFTER `border`;
UPDATE `phpbb_cms_blocks` SET border= '0' WHERE border_explain= '0,0,0,0';
UPDATE `phpbb_cms_blocks` SET border= '1' WHERE border_explain= '1,1,1,1';
UPDATE `phpbb_cms_blocks` SET titlebar= '0' WHERE titlebar_explain= '0,0';
UPDATE `phpbb_cms_blocks` SET titlebar= '1' WHERE titlebar_explain= '1,1';



########################################
##              BUILD 050             ##
########################################



########################################
##              BUILD 051             ##
########################################
ALTER TABLE `phpbb_registration_desc` DROP `reg_option1`;
ALTER TABLE `phpbb_registration_desc` DROP `reg_option2`;
ALTER TABLE `phpbb_registration_desc` DROP `reg_option3`;
TRUNCATE TABLE `phpbb_cms_layout_special`;
ALTER TABLE `phpbb_cms_layout_special` ADD `page_id` varchar(100) NOT NULL DEFAULT '' AFTER `lsid`;
ALTER TABLE `phpbb_cms_layout_special` ADD UNIQUE (`page_id`);
ALTER TABLE `phpbb_cms_layout_special` ADD `locked` tinyint(1) NOT NULL DEFAULT '1' AFTER `page_id`;
TRUNCATE TABLE `phpbb_cms_layout_special`;
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('forum', 'forum', 'forum.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('viewforum', 'viewforum', 'viewforum.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('viewtopic', 'viewtopic', 'viewtopic.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('viewonline', 'viewonline', 'viewonline.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('search', 'search', 'search.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('profile', 'profile', 'profile.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('memberlist', 'memberlist', 'memberlist.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('groupcp', 'groupcp', 'groupcp.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('faq', 'faq', 'faq.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('rules', 'rules', 'rules.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('download', 'download', 'dload.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('album', 'album', 'album.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('links', 'links', 'links.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('statistics', 'statistics', 'statistics.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('calendar', 'calendar', 'calendar.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('recent', 'recent', 'recent.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('referers', 'referers', 'referers.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('shoutbox', 'shoutbox', 'shoutbox_max.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('kb', 'kb', 'kb.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('contact_us', 'contact_us', 'contact_us.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('attachments', 'attachments', 'attachments.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('ranks', 'ranks', 'ranks.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('ajax_chat', 'ajax_chat', 'ajax_chat.php', 0, 0, '');
INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('ajax_chat_archive', 'ajax_chat_archive', 'ajax_chat.php', 0, 0, '');
#INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `view`, `groups`) VALUES ('pic_upload', 'pic_upload', 'upload.php', 0, 0, '');

#SELECT * FROM `phpbb_config` WHERE config_name LIKE "auth_view_%";
DELETE FROM `phpbb_config` WHERE config_name LIKE "auth_view_%" AND config_name <> 'auth_view_pic_upload';
DELETE FROM `phpbb_config` WHERE config_name LIKE "wide_blocks_%";



########################################
##              BUILD 052             ##
########################################
INSERT INTO `phpbb_config` (`config_name`, `config_value`) VALUES ('read_only_forum', '0');



########################################
##              BUILD 053             ##
########################################



########################################
########################################
##     CONTINUE ON THE OTHER FILE     ##
########################################
########################################

