<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$sql = array();

$req_version = str_replace('update_', '', $mode);
switch ($req_version)
{
	case '1055': $current_ip_version = '1.0.5.5'; break;
	case '101010': $current_ip_version = '1.0.10.10'; break;
	case '101111': $current_ip_version = '1.0.11.11'; break;
	case '11015': $current_ip_version = '1.1.0.15'; break;
	case '11116': $current_ip_version = '1.1.1.16'; break;
	case '11318': $current_ip_version = '1.1.3.18'; break;
	case '11520': $current_ip_version = '1.1.5.20'; break;
	case '11621': $current_ip_version = '1.1.6.21'; break;
	case '11722': $current_ip_version = '1.1.7.22'; break;
	case '11924': $current_ip_version = '1.1.9.24'; break;
	case '111025': $current_ip_version = '1.1.10.25'; break;
	case '12027': $current_ip_version = '1.2.0.27'; break;
	case '12128': $current_ip_version = '1.2.1.28'; break;
	case '12229': $current_ip_version = '1.2.2.29'; break;
	case '12431': $current_ip_version = '1.2.4.31'; break;
	case '12734': $current_ip_version = '1.2.7.34'; break;
	case '12835': $current_ip_version = '1.2.8.35'; break;
	case '12936': $current_ip_version = '1.2.9.36'; break;
	case '121037': $current_ip_version = '1.2.10.37'; break;
	case '121138': $current_ip_version = '1.2.11.38'; break;
	case '121239': $current_ip_version = '1.2.12.39'; break;
	case '121340': $current_ip_version = '1.2.13.40'; break;
	case '121441': $current_ip_version = '1.2.14.41'; break;
	case '121542': $current_ip_version = '1.2.15.42'; break;
	case '121643': $current_ip_version = '1.2.16.43'; break;
	case '121744': $current_ip_version = '1.2.17.44'; break;
	case '121845': $current_ip_version = '1.2.18.45'; break;
	case '121946': $current_ip_version = '1.2.19.46'; break;
	case '122047': $current_ip_version = '1.2.20.47'; break;
	case '122148': $current_ip_version = '1.2.21.48'; break;
	case '122249': $current_ip_version = '1.2.22.49'; break;
	case '122350': $current_ip_version = '1.2.23.50'; break;
	case '122451': $current_ip_version = '1.2.24.51'; break;
	case '122552': $current_ip_version = '1.2.25.52'; break;
	case '13053': $current_ip_version = '1.3.0.53'; break;
	case '13053a': $current_ip_version = '1.3.0.53a'; break;
	case '13053b': $current_ip_version = '1.3.0.53b'; break;
	case '13154': $current_ip_version = '1.3.1.54'; break;
	case '13255': $current_ip_version = '1.3.2.55'; break;
	case '13356': $current_ip_version = '1.3.3.56'; break;
	case '13457': $current_ip_version = '1.3.4.57'; break;
	case '13558': $current_ip_version = '1.3.5.58'; break;
	case '13659': $current_ip_version = '1.3.6.59'; break;
	case '13760': $current_ip_version = '1.3.7.60'; break;
	case '13861': $current_ip_version = '1.3.8.61'; break;
	case '13962': $current_ip_version = '1.3.9.62'; break;
	case '131063': $current_ip_version = '1.3.10.63'; break;
	case '131164': $current_ip_version = '1.3.11.64'; break;
	case '131265': $current_ip_version = '1.3.12.65'; break;
	case '131366': $current_ip_version = '1.3.13.66'; break;
	case '131467': $current_ip_version = '1.3.14.67'; break;
	case '131568': $current_ip_version = '1.3.15.68'; break;
	case '131669': $current_ip_version = '1.3.16.69'; break;
	case '131770': $current_ip_version = '1.3.17.70'; break;
	case '131871': $current_ip_version = '1.3.18.71'; break;
	case '131972': $current_ip_version = '1.3.19.72'; break;
	case '132073': $current_ip_version = '1.3.20.73'; break;
	case '132174': $current_ip_version = '1.3.21.74'; break;
	case '132275': $current_ip_version = '1.3.22.75'; break;
	case '132376': $current_ip_version = '1.3.23.76'; break;
	case '132477': $current_ip_version = '1.3.24.77'; break;
	case '132578': $current_ip_version = '1.3.25.78'; break;
	case '132679': $current_ip_version = '1.3.26.79'; break;
	case '132780': $current_ip_version = '1.3.27.80'; break;
	case '132881': $current_ip_version = '1.3.28.81'; break;
	case '132982': $current_ip_version = '1.3.29.82'; break;
	case '133083': $current_ip_version = '1.3.30.83'; break;
	case '20084': $current_ip_version = '2.0.0.84'; break;
	case '20084rc1': $current_ip_version = '2.0.0.84RC1'; break;
	case '20085': $current_ip_version = '2.0.0.85'; break;
	case '20085rc2': $current_ip_version = '2.0.0.85RC2'; break;
	case '20086': $current_ip_version = '2.0.0.86'; break;
	case '20187': $current_ip_version = '2.0.1.87'; break;
	case '20288': $current_ip_version = '2.0.2.88'; break;
	case '20389': $current_ip_version = '2.0.3.89'; break;
	case '20490': $current_ip_version = '2.0.4.90'; break;
	case '20591': $current_ip_version = '2.0.5.91'; break;
	case '20692': $current_ip_version = '2.0.6.92'; break;
	case '20793': $current_ip_version = '2.0.7.93'; break;
	case '20894': $current_ip_version = '2.0.8.94'; break;
	case '20995': $current_ip_version = '2.0.9.95'; break;
	case '201096': $current_ip_version = '2.0.10.96'; break;
	case '201197': $current_ip_version = '2.0.11.97'; break;
	case '201298': $current_ip_version = '2.0.12.98'; break;
	case '201399': $current_ip_version = '2.0.13.99'; break;
	case '2014100': $current_ip_version = '2.0.14.100'; break;
	case '2015101': $current_ip_version = '2.0.15.101'; break;
	case '2016102': $current_ip_version = '2.0.16.102'; break;
	case '2017103': $current_ip_version = '2.0.17.103'; break;
	case '2018104': $current_ip_version = '2.0.18.104'; break;
	case '220105': $current_ip_version = '2.2.0.105'; break;
	case '221106': $current_ip_version = '2.2.1.106'; break;
	case '222107': $current_ip_version = '2.2.2.107'; break;
	case '223108': $current_ip_version = '2.2.3.108'; break;
	case '224109': $current_ip_version = '2.2.4.109'; break;
	case '225110': $current_ip_version = '2.2.5.110'; break;
	case '226111': $current_ip_version = '2.2.6.111'; break;
}

// We need to force this because in MySQL 5.5.5 the new default DB Engine is InnoDB, not MyISAM any more
$sql[] = "SET default_storage_engine = MYISAM";

// Icy Phoenix Part...
if (substr($mode, 0, 6) == 'update')
{

	switch ($current_ip_version)
	{
		case '':

		$sql[] = "CREATE TABLE `" . $table_prefix . "search_results` (
			`search_id` INT(11) unsigned NOT NULL DEFAULT '0',
			`session_id` VARCHAR(32) NOT NULL DEFAULT '',
			`search_array` MEDIUMTEXT NOT NULL,
			`search_time` INT(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`search_id`),
			KEY `session_id` (`session_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "search_wordlist` (
			`word_text` VARCHAR(50) binary NOT NULL DEFAULT '',
			`word_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
			`word_common` TINYINT(1) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`word_text`),
			KEY `word_id` (`word_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "search_wordmatch` (
			`post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
			`word_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
			`title_match` TINYINT(1) NOT NULL DEFAULT '0',
			KEY `post_id` (`post_id`),
			KEY `word_id` (`word_id`)
		)";

		$sql[] = "ALTER TABLE " . $table_prefix . "config CHANGE `config_value` `config_value` TEXT";

		$sql[] = "CREATE TABLE `" . $table_prefix . "acronyms` (
			`acronym_id` mediumint(9) NOT NULL auto_increment,
			`acronym` varchar(80) NOT NULL default '',
			`description` varchar(255) NOT NULL default '',
			PRIMARY KEY (`acronym_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "adminedit` (
			`edit_id` mediumint(8) unsigned NOT NULL auto_increment,
			`edituser` char(100) NOT NULL default '',
			`editok` char(100) NOT NULL default '',
			PRIMARY KEY (`edit_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "album` (
			`pic_id` int(11) unsigned NOT NULL auto_increment,
			`pic_filename` varchar(255) NOT NULL default '',
			`pic_thumbnail` varchar(255) NULL default '',
			`pic_title` varchar(255) NOT NULL default '',
			`pic_desc` TEXT NOT NULL,
			`pic_user_id` mediumint(8) NOT NULL default '0',
			`pic_username` varchar(32) NULL default '',
			`pic_user_ip` varchar(40) NOT NULL default '',
			`pic_time` int(11) unsigned NOT NULL default '0',
			`pic_cat_id` mediumint(8) unsigned NOT NULL default '1',
			`pic_view_count` int(11) unsigned NOT NULL default '0',
			`pic_lock` tinyint(3) NOT NULL default '0',
			`pic_approval` tinyint(3) NOT NULL default '1',
			PRIMARY KEY (`pic_id`),
			KEY `pic_cat_id` (`pic_cat_id`),
			KEY `pic_user_id` (`pic_user_id`),
			KEY `pic_time` (`pic_time`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "album_cat` (
			`cat_id` mediumint(8) unsigned NOT NULL auto_increment,
			`cat_title` varchar(255) NOT NULL default '',
			`cat_desc` TEXT NOT NULL,
			`cat_order` mediumint(8) NOT NULL default '0',
			`cat_view_level` tinyint(3) NOT NULL default '-1',
			`cat_upload_level` tinyint(3) NOT NULL default '0',
			`cat_rate_level` tinyint(3) NOT NULL default '0',
			`cat_comment_level` tinyint(3) NOT NULL default '0',
			`cat_edit_level` tinyint(3) NOT NULL default '0',
			`cat_delete_level` tinyint(3) NOT NULL default '2',
			`cat_view_groups` varchar(255) NULL default '',
			`cat_upload_groups` varchar(255) NULL default '',
			`cat_rate_groups` varchar(255) NULL default '',
			`cat_comment_groups` varchar(255) NULL default '',
			`cat_edit_groups` varchar(255) NULL default '',
			`cat_delete_groups` varchar(255) NULL default '',
			`cat_moderator_groups` varchar(255) NULL default '',
			`cat_approval` tinyint(3) NOT NULL default '0',
			`cat_parent` mediumint(8) unsigned default '0',
			`cat_user_id` mediumint(8) unsigned default '0',
			PRIMARY KEY (`cat_id`),
			KEY `cat_order` (`cat_order`)
		)";

		// Standard Smartor Album fix - BEGIN
		// Added these again to fix upgrade for some standard Smartor Album.
		$sql[] = "ALTER TABLE `" . $table_prefix . "album_cat` ADD `cat_parent` MEDIUMINT(8) UNSIGNED DEFAULT '0' NULL AFTER `cat_approval`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "album_cat` ADD `cat_user_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NULL AFTER `cat_parent`";
		// Standard Smartor Album fix - BEGIN

		$sql[] = "CREATE TABLE `" . $table_prefix . "album_comment` (
			`comment_id` int(11) unsigned NOT NULL auto_increment,
			`comment_pic_id` int(11) unsigned NOT NULL default '0',
			`comment_cat_id` int(11) NOT NULL default '0',
			`comment_user_id` mediumint(8) NOT NULL default '0',
			`comment_username` varchar(32) default '',
			`comment_user_ip` varchar(40) NOT NULL default '',
			`comment_time` int(11) unsigned NOT NULL default '0',
			`comment_text` TEXT NOT NULL,
			`comment_edit_time` int(11) unsigned default NULL,
			`comment_edit_count` smallint(5) unsigned NOT NULL default '0',
			`comment_edit_user_id` mediumint(8) default NULL,
			PRIMARY KEY (`comment_id`),
			KEY `comment_pic_id` (`comment_pic_id`),
			KEY `comment_user_id` (`comment_user_id`),
			KEY `comment_user_ip` (`comment_user_ip`),
			KEY `comment_time` (`comment_time`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "album_config` (
			`config_name` varchar(255) NOT NULL default '',
			`config_value` varchar(255) NOT NULL default '',
			PRIMARY KEY (`config_name`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "album_rate` (
			`rate_pic_id` int(11) unsigned NOT NULL default '0',
			`rate_user_id` mediumint(8) NOT NULL default '0',
			`rate_user_ip` varchar(40) NOT NULL default '',
			`rate_point` tinyint(3) unsigned NOT NULL default '0',
			`rate_hon_point` tinyint(3) NOT NULL default '0',
			KEY `rate_pic_id` (`rate_pic_id`),
			KEY `rate_user_id` (`rate_user_id`),
			KEY `rate_user_ip` (`rate_user_ip`),
			KEY `rate_point` (`rate_point`)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "forbidden_extensions (
			ext_id mediumint(8) UNSIGNED NOT NULL auto_increment,
			extension varchar(100) NOT NULL,
			PRIMARY KEY (ext_id)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "extension_groups (
			group_id mediumint(8) NOT NULL auto_increment,
			group_name char(20) NOT NULL,
			cat_id tinyint(2) DEFAULT '0' NOT NULL,
			allow_group tinyint(1) DEFAULT '0' NOT NULL,
			download_mode tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
			upload_icon varchar(100) DEFAULT '',
			max_filesize int(20) DEFAULT '0' NOT NULL,
			forum_permissions varchar(255) default '' NOT NULL,
			PRIMARY KEY group_id (group_id)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "extensions (
			ext_id mediumint(8) UNSIGNED NOT NULL auto_increment,
			group_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			extension varchar(100) NOT NULL,
			comment varchar(100),
			PRIMARY KEY ext_id (ext_id)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "attachments_desc (
			attach_id mediumint(8) UNSIGNED NOT NULL auto_increment,
			physical_filename varchar(255) NOT NULL,
			real_filename varchar(255) NOT NULL,
			download_count mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			comment varchar(255),
			extension varchar(100),
			mimetype varchar(100),
			filesize int(20) NOT NULL,
			filetime int(11) DEFAULT '0' NOT NULL,
			thumbnail tinyint(1) DEFAULT '0' NOT NULL,
			PRIMARY KEY (attach_id),
			KEY filetime (filetime),
			KEY physical_filename (physical_filename(10)),
			KEY filesize (filesize)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "attachments (
			attach_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			privmsgs_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			user_id_1 mediumint(8) NOT NULL,
			user_id_2 mediumint(8) NOT NULL,
			KEY attach_id_post_id (attach_id, post_id),
			KEY attach_id_privmsgs_id (attach_id, privmsgs_id),
			KEY post_id (post_id),
			KEY privmsgs_id (privmsgs_id)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "quota_limits (
			quota_limit_id mediumint(8) unsigned NOT NULL auto_increment,
			quota_desc varchar(20) NOT NULL default '',
			quota_limit bigint(20) unsigned NOT NULL default '0',
			PRIMARY KEY (quota_limit_id)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "attach_quota (
			user_id mediumint(8) unsigned NOT NULL default '0',
			group_id mediumint(8) unsigned NOT NULL default '0',
			quota_type smallint(2) NOT NULL default '0',
			quota_limit_id mediumint(8) unsigned NOT NULL default '0',
			KEY quota_type (quota_type)
		)";

		$sql[] = "ALTER TABLE " . $table_prefix . "forums ADD auth_download TINYINT(2) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD auth_download TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "posts ADD post_attachment TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "topics ADD topic_attachment TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "privmsgs ADD privmsgs_attachment TINYINT(1) DEFAULT '0' NOT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "autolinks` (
			`link_id` mediumint(5) unsigned NOT NULL auto_increment,
			`link_keyword` varchar(50) NOT NULL default '',
			`link_title` varchar(50) NOT NULL default '',
			`link_url` varchar(200) NOT NULL default '',
			`link_comment` varchar(200) NOT NULL default '',
			`link_style` varchar(200) NOT NULL default '',
			`link_forum` tinyint(1) NOT NULL default '0',
			`link_int` tinyint(1) NOT NULL default '0',
			KEY `link_id` (`link_id`)
		)";

		$sql[] = "ALTER TABLE " . $table_prefix . "forums ADD auth_globalannounce TINYINT (2) DEFAULT '3' NOT NULL AFTER auth_announce";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD auth_globalannounce TINYINT (1) not null AFTER auth_announce";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD `auth_ban` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD `auth_greencard` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD `auth_bluecard` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD `auth_rate` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD `auth_mod` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD `auth_news` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE " . $table_prefix . "auth_access ADD `auth_cal` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` ADD `ban_start` INT(11) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` ADD `ban_end` INT(11) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` ADD `ban_by_userid` MEDIUMINT(8) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` ADD `ban_priv_reason` TEXT DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` ADD `ban_pub_reason_mode` TINYINT(1) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` ADD `ban_pub_reason` TEXT DEFAULT NULL";

		$sql[] = "ALTER TABLE `" . $table_prefix . "categories` ADD `cat_main_type` CHAR(1) DEFAULT 'c'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "categories` ADD `cat_main` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "categories` ADD `cat_desc` TEXT";
		$sql[] = "ALTER TABLE `" . $table_prefix . "categories` ADD `icon` VARCHAR(255) DEFAULT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "force_read` (
			`topic_number` int(25) NOT NULL default '0',
			`message` TEXT NOT NULL,
			`install_date` int(15) NOT NULL default '0',
			`active` tinyint(2) NOT NULL default '1',
			`effected` tinyint(1) NOT NULL default '1'
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "force_read_users` (
			`user` varchar(255) NOT NULL default '',
			`read` int(1) NOT NULL default '0',
			`time` int(10) NOT NULL default '0'
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_notify` TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_link` VARCHAR(255) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_link_internal` TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_link_hit_count` TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_link_hit` BIGINT(20) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `icon` VARCHAR(255) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `main_type` CHAR(1) DEFAULT 'c'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `auth_news` TINYINT(2) DEFAULT '2' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `auth_cal` TINYINT(2) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `auth_ban` TINYINT(2) DEFAULT '3' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `auth_greencard` TINYINT(2) DEFAULT '5' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `auth_bluecard` TINYINT(2) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `auth_rate` TINYINT(2) DEFAULT '-1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_rules` TEXT";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `rules_display_title` TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `rules_custom_title` VARCHAR(80) NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `rules_in_viewforum` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `rules_in_viewtopic` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `rules_in_posting` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "forums_watch` (
			`forum_id` smallint(5) unsigned NOT NULL default '0',
			`user_id` mediumint(8) NOT NULL default '0',
			`notify_status` tinyint(1) NOT NULL default '0',
			KEY `forum_id` (`forum_id`),
			KEY `user_id` (`user_id`),
			KEY `notify_status` (`notify_status`)
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_count` INT(4) UNSIGNED DEFAULT '99999999'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_count_max` INT(4) UNSIGNED DEFAULT '99999999'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_count_enable` SMALLINT(2) UNSIGNED DEFAULT '0'";

		$sql[] = "CREATE TABLE `" . $table_prefix . "hacks_list` (
			`hack_id` mediumint(8) unsigned NOT NULL auto_increment,
			`hack_name` varchar(255) NOT NULL default '',
			`hack_desc` varchar(255) NOT NULL default '',
			`hack_author` varchar(255) NOT NULL default '',
			`hack_author_email` varchar(255) NOT NULL default '',
			`hack_author_website` TINYTEXT,
			`hack_version` varchar(32) NOT NULL default '',
			`hack_hide` enum('Yes','No') NOT NULL default 'No',
			`hack_download_url` TINYTEXT,
			`hack_file` varchar(255) NOT NULL default '',
			`hack_file_mtime` int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (`hack_id`),
			UNIQUE KEY `hack_name` (`hack_name`),
			KEY `hack_file` (`hack_file`),
			KEY `hack_hide` (`hack_hide`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "jr_admin_users` (
			`user_id` mediumint(9) NOT NULL default '0',
			`user_jr_admin` LONGTEXT NOT NULL,
			`start_date` int(10) unsigned NOT NULL default '0',
			`update_date` int(10) unsigned NOT NULL default '0',
			`admin_notes` TEXT NOT NULL,
			`notes_view` tinyint(1) NOT NULL default '0',
			PRIMARY KEY (`user_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_articles` (
			`article_id` mediumint(8) unsigned NOT NULL auto_increment,
			`article_category_id` mediumint(8) unsigned NOT NULL default '0',
			`article_title` varchar(255) binary NOT NULL default '',
			`article_description` varchar(255) binary NOT NULL default '',
			`article_date` varchar(255) binary NOT NULL default '',
			`article_author_id` mediumint(8) NOT NULL default '0',
			`username` varchar(255) default NULL,
			`article_body` TEXT NOT NULL,
			`article_type` mediumint(8) unsigned NOT NULL default '0',
			`approved` tinyint(1) unsigned NOT NULL default '0',
			`topic_id` mediumint(8) unsigned NOT NULL default '0',
			`views` bigint(8) NOT NULL default '0',
			`article_rating` double(6,4) NOT NULL default '0.0000',
			`article_totalvotes` int(255) NOT NULL default '0',
			KEY `article_id` (`article_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_categories` (
			`category_id` mediumint(8) unsigned NOT NULL auto_increment,
			`category_name` varchar(255) binary NOT NULL default '',
			`category_details` varchar(255) binary NOT NULL default '',
			`number_articles` mediumint(8) unsigned NOT NULL default '0',
			`parent` mediumint(8) unsigned default NULL,
			`cat_order` mediumint(8) unsigned NOT NULL default '0',
			`auth_view` tinyint(3) NOT NULL default '0',
			`auth_post` tinyint(3) NOT NULL default '0',
			`auth_rate` tinyint(3) NOT NULL default '0',
			`auth_comment` tinyint(3) NOT NULL default '0',
			`auth_edit` tinyint(3) NOT NULL default '0',
			`auth_delete` tinyint(3) NOT NULL default '2',
			`auth_approval` tinyint(3) NOT NULL default '0',
			`auth_approval_edit` tinyint(3) NOT NULL default '0',
			`auth_view_groups` varchar(255) default NULL,
			`auth_post_groups` varchar(255) default NULL,
			`auth_rate_groups` varchar(255) default NULL,
			`auth_comment_groups` varchar(255) default NULL,
			`auth_edit_groups` varchar(255) default NULL,
			`auth_delete_groups` varchar(255) default NULL,
			`auth_approval_groups` varchar(255) default NULL,
			`auth_approval_edit_groups` varchar(255) default NULL,
			`auth_moderator_groups` varchar(255) default NULL,
			`comments_forum_id` tinyint(3) NOT NULL default '-1',
			KEY `category_id` (`category_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_config` (
			`config_name` varchar(255) NOT NULL default '',
			`config_value` varchar(255) NOT NULL default '',
			PRIMARY KEY (`config_name`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_custom` (
			`custom_id` int(50) NOT NULL auto_increment,
			`custom_name` TEXT NOT NULL,
			`custom_description` TEXT NOT NULL,
			`data` TEXT NOT NULL,
			`field_order` int(20) NOT NULL default '0',
			`field_type` tinyint(2) NOT NULL default '0',
			`regex` varchar(255) NOT NULL default '',
			PRIMARY KEY (`custom_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_customdata` (
			`customdata_file` int(50) NOT NULL default '0',
			`customdata_custom` int(50) NOT NULL default '0',
			`data` TEXT
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_results` (
			`search_id` int(11) unsigned NOT NULL default '0',
			`session_id` varchar(32) NOT NULL default '',
			`search_array` TEXT NOT NULL,
			PRIMARY KEY (`search_id`),
			KEY `session_id` (`session_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_search` (
			`search_id` int(11) unsigned NOT NULL default '0',
			`session_id` varchar(32) NOT NULL default '',
			`search_array` TEXT NOT NULL,
			PRIMARY KEY (`search_id`),
			KEY `session_id` (`session_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_types` (
			`id` mediumint(8) unsigned NOT NULL auto_increment,
			`type` varchar(255) binary NOT NULL default '',
			KEY `id` (`id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_votes` (
			`votes_ip` varchar(50) NOT NULL default '0',
			`votes_userid` int(50) NOT NULL default '0',
			`votes_file` int(50) NOT NULL default '0'
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_wordlist` (
			`word_text` varchar(50) binary NOT NULL default '',
			`word_id` mediumint(8) unsigned NOT NULL auto_increment,
			`word_common` tinyint(1) unsigned NOT NULL default '0',
			PRIMARY KEY (`word_text`),
			KEY `word_id` (`word_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "kb_wordmatch` (
			`article_id` mediumint(8) unsigned NOT NULL default '0',
			`word_id` mediumint(8) unsigned NOT NULL default '0',
			`title_match` tinyint(1) NOT NULL default '0',
			KEY `post_id` (`article_id`),
			KEY `word_id` (`word_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "link_categories` (
			`cat_id` mediumint(8) unsigned NOT NULL auto_increment,
			`cat_title` varchar(100) NOT NULL default '',
			`cat_order` mediumint(8) unsigned NOT NULL default '0',
			PRIMARY KEY (`cat_id`),
			KEY `cat_order` (`cat_order`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "link_config` (
			`config_name` varchar(255) NOT NULL default '',
			`config_value` varchar(255) NOT NULL default ''
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "links` (
			`link_id` mediumint(8) unsigned NOT NULL auto_increment,
			`link_title` varchar(100) NOT NULL default '',
			`link_desc` varchar(255) default NULL,
			`link_category` mediumint(8) unsigned NOT NULL default '0',
			`link_url` varchar(100) NOT NULL default '',
			`link_logo_src` varchar(120) default NULL,
			`link_joined` int(11) NOT NULL default '0',
			`link_active` tinyint(1) NOT NULL default '0',
			`link_hits` int(10) unsigned NOT NULL default '0',
			`user_id` mediumint(8) NOT NULL default '0',
			`user_ip` varchar(40) NOT NULL default '',
			`last_user_ip` varchar(40) NOT NULL default '',
			PRIMARY KEY (`link_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "liw_cache` (
			`image_checksum` varchar(32) NOT NULL default '',
			`image_width` varchar(10) default NULL,
			`image_height` varchar(10) default NULL,
			PRIMARY KEY (`image_checksum`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "logins` (
			`login_id` mediumint(8) unsigned NOT NULL auto_increment,
			`login_userid` mediumint(8) NOT NULL default '0',
			`login_ip` varchar(40) NOT NULL default '0',
			`login_user_agent` varchar(255) NOT NULL default 'n/a',
			`login_time` int(11) NOT NULL default '0',
			PRIMARY KEY (`login_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "news` (
			`news_id` mediumint(8) unsigned NOT NULL auto_increment,
			`news_category` varchar(70) NOT NULL default '',
			`news_image` varchar(70) NOT NULL default '',
			PRIMARY KEY (`news_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "notes` (
			`id` int(8) NOT NULL default '0',
			`text` text
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_auth` (
			`group_id` mediumint(8) NOT NULL default '0',
			`cat_id` smallint(5) unsigned NOT NULL default '0',
			`auth_view` tinyint(1) NOT NULL default '0',
			`auth_read` tinyint(1) NOT NULL default '0',
			`auth_view_file` tinyint(1) NOT NULL default '0',
			`auth_edit_file` tinyint(1) NOT NULL default '0',
			`auth_delete_file` tinyint(1) NOT NULL default '0',
			`auth_upload` tinyint(1) NOT NULL default '0',
			`auth_download` tinyint(1) NOT NULL default '0',
			`auth_rate` tinyint(1) NOT NULL default '0',
			`auth_email` tinyint(1) NOT NULL default '0',
			`auth_view_comment` tinyint(1) NOT NULL default '0',
			`auth_post_comment` tinyint(1) NOT NULL default '0',
			`auth_edit_comment` tinyint(1) NOT NULL default '0',
			`auth_delete_comment` tinyint(1) NOT NULL default '0',
			`auth_mod` tinyint(1) NOT NULL default '0',
			`auth_search` tinyint(1) NOT NULL default '1',
			`auth_stats` tinyint(1) NOT NULL default '1',
			`auth_toplist` tinyint(1) NOT NULL default '1',
			`auth_viewall` tinyint(1) NOT NULL default '1',
			KEY `group_id` (`group_id`),
			KEY `cat_id` (`cat_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_cat` (
			`cat_id` int(10) NOT NULL auto_increment,
			`cat_name` TEXT NOT NULL,
			`cat_desc` TEXT NOT NULL,
			`cat_parent` int(50) default NULL,
			`parents_data` TEXT NOT NULL,
			`cat_order` int(50) default NULL,
			`cat_allow_file` tinyint(2) NOT NULL default '0',
			`cat_allow_ratings` tinyint(2) NOT NULL default '1',
			`cat_allow_comments` tinyint(2) NOT NULL default '1',
			`cat_files` mediumint(8) NOT NULL default '-1',
			`cat_last_file_id` mediumint(8) unsigned NOT NULL default '0',
			`cat_last_file_name` varchar(255) NOT NULL default '',
			`cat_last_file_time` int(50) unsigned NOT NULL default '0',
			`auth_view` tinyint(2) NOT NULL default '0',
			`auth_read` tinyint(2) NOT NULL default '0',
			`auth_view_file` tinyint(2) NOT NULL default '0',
			`auth_edit_file` tinyint(1) NOT NULL default '0',
			`auth_delete_file` tinyint(1) NOT NULL default '0',
			`auth_upload` tinyint(2) NOT NULL default '0',
			`auth_download` tinyint(2) NOT NULL default '0',
			`auth_rate` tinyint(2) NOT NULL default '0',
			`auth_email` tinyint(2) NOT NULL default '0',
			`auth_view_comment` tinyint(2) NOT NULL default '0',
			`auth_post_comment` tinyint(2) NOT NULL default '0',
			`auth_edit_comment` tinyint(2) NOT NULL default '0',
			`auth_delete_comment` tinyint(2) NOT NULL default '0',
			PRIMARY KEY (`cat_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_comments` (
			`comments_id` int(10) NOT NULL auto_increment,
			`file_id` int(10) NOT NULL default '0',
			`comments_text` TEXT NOT NULL,
			`comments_title` TEXT NOT NULL,
			`comments_time` int(50) NOT NULL default '0',
			`poster_id` mediumint(8) NOT NULL default '0',
			PRIMARY KEY (`comments_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_config` (
			`config_name` varchar(255) NOT NULL default '',
			`config_value` varchar(255) NOT NULL default '',
			PRIMARY KEY (`config_name`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_custom` (
			`custom_id` int(50) NOT NULL auto_increment,
			`custom_name` TEXT NOT NULL,
			`custom_description` TEXT NOT NULL,
			`data` TEXT NOT NULL,
			`field_order` int(20) NOT NULL default '0',
			`field_type` tinyint(2) NOT NULL default '0',
			`regex` varchar(255) NOT NULL default '',
			PRIMARY KEY (`custom_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_customdata` (
			`customdata_file` int(50) NOT NULL default '0',
			`customdata_custom` int(50) NOT NULL default '0',
			`data` TEXT
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_download_info` (
			`file_id` mediumint(8) NOT NULL default '0',
			`user_id` mediumint(8) NOT NULL default '0',
			`downloader_ip` varchar(40) NOT NULL default '',
			`downloader_os` varchar(255) NOT NULL default '',
			`downloader_browser` varchar(255) NOT NULL default '',
			`browser_version` varchar(255) NOT NULL default ''
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_files` (
			`file_id` int(10) NOT NULL auto_increment,
			`user_id` mediumint(8) NOT NULL default '0',
			`poster_ip` varchar(40) NOT NULL default '',
			`file_name` TEXT NOT NULL,
			`file_size` int(20) NOT NULL default '0',
			`unique_name` varchar(255) NOT NULL default '',
			`real_name` varchar(255) NOT NULL default '',
			`file_dir` varchar(255) NOT NULL default '',
			`file_desc` TEXT NOT NULL,
			`file_creator` TEXT NOT NULL,
			`file_version` TEXT NOT NULL,
			`file_longdesc` TEXT NOT NULL,
			`file_ssurl` TEXT NOT NULL,
			`file_sshot_link` tinyint(2) NOT NULL default '0',
			`file_dlurl` TEXT NOT NULL,
			`file_time` int(50) default NULL,
			`file_update_time` int(50) NOT NULL default '0',
			`file_catid` int(10) default NULL,
			`file_posticon` TEXT NOT NULL,
			`file_license` int(10) default NULL,
			`file_dls` int(10) default NULL,
			`file_last` int(50) default NULL,
			`file_pin` int(2) default NULL,
			`file_docsurl` TEXT NOT NULL,
			`file_approved` tinyint(1) NOT NULL default '1',
			`file_broken` tinyint(1) NOT NULL default '0',
			PRIMARY KEY (`file_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_license` (
			`license_id` int(10) NOT NULL auto_increment,
			`license_name` TEXT NOT NULL,
			`license_text` TEXT NOT NULL,
			PRIMARY KEY (`license_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_mirrors` (
			`mirror_id` mediumint(8) NOT NULL auto_increment,
			`file_id` int(10) NOT NULL default '0',
			`unique_name` varchar(255) NOT NULL default '',
			`file_dir` varchar(255) NOT NULL default '',
			`file_dlurl` varchar(255) NOT NULL default '',
			`mirror_location` varchar(255) NOT NULL default '',
			PRIMARY KEY (`mirror_id`),
			KEY `file_id` (`file_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "pa_votes` (
			`user_id` mediumint(8) NOT NULL default '0',
			`votes_ip` varchar(50) NOT NULL default '0',
			`votes_file` int(50) NOT NULL default '0',
			`rate_point` tinyint(3) unsigned NOT NULL default '0',
			`voter_os` varchar(255) NOT NULL default '',
			`voter_browser` varchar(255) NOT NULL default '',
			`browser_version` varchar(8) NOT NULL default '',
			KEY `user_id` (`user_id`)
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` ADD `post_bluecard` TINYINT(1) DEFAULT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "privmsgs_archive` (
			`privmsgs_id` mediumint(8) unsigned NOT NULL auto_increment,
			`privmsgs_type` tinyint(4) NOT NULL default '0',
			`privmsgs_subject` varchar(255) NOT NULL default '0',
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
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "profile_view` (
			`user_id` mediumint(8) NOT NULL default '0',
			`viewername` varchar(25) NOT NULL default '',
			`viewer_id` mediumint(8) NOT NULL default '0',
			`view_stamp` int(11) NOT NULL default '0',
			`counter` mediumint(8) NOT NULL default '0'
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "rate_results` (
			`rating_id` mediumint(8) unsigned NOT NULL auto_increment,
			`user_id` mediumint(8) unsigned NOT NULL default '0',
			`topic_id` mediumint(8) unsigned NOT NULL default '0',
			`rating` mediumint(8) unsigned NOT NULL default '0',
			`user_ip` varchar(40) NOT NULL default '',
			`rating_time` int(11) NOT NULL default '0',
			PRIMARY KEY (`rating_id`),
			KEY `topic_id` (`topic_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "referers` (
			`id` int(11) NOT NULL auto_increment,
			`host` varchar(255) NOT NULL default '',
			`url` varchar(255) NOT NULL default '',
			`ip` varchar(40) NOT NULL default '',
			`hits` int(11) NOT NULL default '1',
			`firstvisit` int(11) NOT NULL default '0',
			`lastvisit` int(11) NOT NULL default '0',
			PRIMARY KEY (`id`)
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "sessions` ADD `session_browser` VARCHAR(255) NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "sessions` ADD `session_admin` TINYINT(2) DEFAULT '0' NOT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "sessions_keys` (
			`key_id` varchar(32) NOT NULL default '0',
			`user_id` mediumint(8) NOT NULL default '0',
			`last_ip` varchar(8) NOT NULL default '0',
			`last_login` int(11) NOT NULL default '0',
			PRIMARY KEY (`key_id`,`user_id`),
			KEY `last_login` (`last_login`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "shout` (
			`shout_id` mediumint(8) unsigned NOT NULL auto_increment,
			`shout_username` varchar(25) NOT NULL default '',
			`shout_user_id` mediumint(8) NOT NULL default '0',
			`shout_group_id` mediumint(8) NOT NULL default '0',
			`shout_session_time` int(11) NOT NULL default '0',
			`shout_ip` varchar(40) NOT NULL default '',
			`shout_text` TEXT NOT NULL,
			`shout_active` mediumint(8) NOT NULL default '0',
			`enable_bbcode` tinyint(1) NOT NULL default '0',
			`enable_html` tinyint(1) NOT NULL default '0',
			`enable_smilies` tinyint(1) NOT NULL default '0',
			`enable_sig` tinyint(1) NOT NULL default '0',
			KEY `shout_id` (`shout_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "site_history` (
			`date` int(11) NOT NULL default '0',
			`reg` mediumint(8) NOT NULL default '0',
			`hidden` mediumint(8) NOT NULL default '0',
			`guests` mediumint(8) NOT NULL default '0',
			`new_topics` mediumint(8) NOT NULL default '0',
			`new_posts` mediumint(8) NOT NULL default '0',
			UNIQUE KEY `date` (`date`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "stats_config` (
			`config_name` varchar(50) NOT NULL default '',
			`config_value` varchar(255) NOT NULL default '',
			PRIMARY KEY (`config_name`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "stats_modules` (
			`module_id` tinyint(8) NOT NULL default '0',
			`name` varchar(150) NOT NULL default '',
			`active` tinyint(1) NOT NULL default '0',
			`installed` tinyint(1) NOT NULL default '0',
			`display_order` mediumint(8) unsigned NOT NULL default '0',
			`update_time` mediumint(8) unsigned NOT NULL default '0',
			`auth_value` tinyint(2) NOT NULL default '0',
			`module_info_cache` blob,
			`module_db_cache` blob,
			`module_result_cache` blob,
			`module_info_time` int(10) unsigned NOT NULL default '0',
			`module_cache_time` int(10) unsigned NOT NULL default '0',
			PRIMARY KEY (`module_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "title_infos` (
			`id` int(11) NOT NULL auto_increment,
			`title_info` varchar(255) NOT NULL default '',
			`date_format` varchar(25) default NULL,
			`admin_auth` tinyint(1) default '0',
			`mod_auth` tinyint(1) default '0',
			`poster_auth` tinyint(1) default '0',
			UNIQUE KEY `id` (`id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "topic_view` (
			`topic_id` mediumint(8) NOT NULL default '0',
			`user_id` mediumint(8) NOT NULL default '0',
			`view_time` int(11) NOT NULL default '0',
			`view_count` int(11) NOT NULL default '0'
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_desc` VARCHAR(255)";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `title_compl_infos` VARCHAR(255) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `news_id` INT(10) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_calendar_time` INT(11) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_calendar_duration` INT(11) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_rating` DOUBLE UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_browser` VARCHAR(255) NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_rank2` INT(11) DEFAULT '-1'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_rank3` INT(11) DEFAULT '-2'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_rank4` INT(11) DEFAULT '-2'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_rank5` INT(11) DEFAULT '-2'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_search` VARCHAR(10) NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_birthday` INT(11) DEFAULT '999999' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_next_birthday_greeting` INT(11) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_sub_forum` TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_split_cat` TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_last_topic_title` TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_sub_level_links` TINYINT(1) DEFAULT '2' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_display_viewonline` TINYINT(1) DEFAULT '2' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_color_group` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_gender` TINYINT(4) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_totaltime` INT(11) DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_totallogon` INT(11) DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_totalpages` INT(11) DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_calendar_display_open` TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_calendar_header_cells` TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_calendar_week_start` TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_calendar_nb_row` TINYINT(2) UNSIGNED DEFAULT '5' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_calendar_birthday` TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_calendar_forum` TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_warnings` SMALLINT(5) DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_time_mode` TINYINT(4) DEFAULT '5' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_dst_time_lag` TINYINT(4) DEFAULT '60' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_skype` VARCHAR(255) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_registered_ip` VARCHAR(8) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_registered_hostname` VARCHAR(255) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_profile_view` SMALLINT(5) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_last_profile_view` INT(11) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_topics_per_page` VARCHAR(5) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_hot_threshold` VARCHAR(5) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_posts_per_page` VARCHAR(5) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_allowswearywords` TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_showavatars` TINYINT(1) DEFAULT '1'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_showsignatures` TINYINT(1) DEFAULT '1'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_profile_view_popup` TINYINT(1) DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "vote_voters` ADD `vote_cast` TINYINT(4) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD ct_mailcount INT(10) NOT NULL AFTER user_newpasswd";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD ct_pwreset INT(2) NOT NULL AFTER user_newpasswd";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD ct_unsucclogin INT(10) DEFAULT NULL AFTER user_newpasswd";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD ct_logintry INT(2) DEFAULT 0 AFTER user_newpasswd";

		$sql[] = "CREATE TABLE " . $table_prefix . "ct_viskey (
			confirm_id char(32) NOT NULL default '',
			session_id char(32) NOT NULL default '',
			code char(6) NOT NULL default '',
			PRIMARY KEY (session_id,confirm_id))";

		$sql[] = "CREATE TABLE `" . $table_prefix . "xs_news` (
			`news_id` mediumint(8) unsigned NOT NULL auto_increment,
			`news_date` int(11) NOT NULL default '0',
			`news_text` TEXT NOT NULL,
			`news_display` tinyint(1) NOT NULL default '1',
			`news_smilies` tinyint(1) NOT NULL default '0',
			PRIMARY KEY (`news_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "xs_news_xml` (
			`xml_id` mediumint(8) NOT NULL auto_increment,
			`xml_title` varchar(255) NOT NULL default '',
			`xml_show` tinyint(1) NOT NULL default '0',
			`xml_feed` TEXT NOT NULL,
			`xml_is_feed` tinyint(1) NOT NULL default '1',
			`xml_width` varchar(4) NOT NULL default '98%',
			`xml_height` char(3) NOT NULL default '20',
			`xml_font` char(3) NOT NULL default '0',
			`xml_speed` char(2) NOT NULL default '3',
			`xml_direction` tinyint(1) NOT NULL default '0',
			PRIMARY KEY (`xml_id`)
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_pics', '1024')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('user_pics_limit', '-1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('mod_pics_limit', '-1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_file_size', '128000')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_width', '1024')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_height', '768')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('rows_per_page', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('cols_per_page', '4')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('fullpic_popup', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('thumbnail_quality', '75')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('thumbnail_size', '125')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('thumbnail_cache', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('sort_method', 'pic_time')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('sort_order', 'DESC')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('jpg_allowed', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('png_allowed', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('gif_allowed', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('desc_length', '512')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('hotlink_prevent', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('hotlink_allowed', 'icyphoenix.com,lucalibralato.com,mightygorgon.com')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_gallery', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_gallery_private', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_gallery_limit', '-1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_gallery_view', '-1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('rate', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('rate_scale', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('comment', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('gd_version', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('album_version', '.0.56')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('fap_version', '1.2.4')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_thumb', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_total_pics', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_total_comments', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_comments', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_last_comment', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_last_pic', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_pics', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_recent_in_subcats', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_recent_instead_of_nopics', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('line_break_subcats', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_subcats', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_allow_gallery_mod', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_allow_sub_categories', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_sub_category_limit', '-1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_show_subcats_in_index', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_show_recent_in_subcats', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_show_recent_instead_of_nopics', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_personal_gallery_link', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('album_category_sorting', 'cat_order')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('album_category_sorting_direction', 'ASC')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('album_debug_mode', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_all_in_personal_gallery', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('new_pic_check_interval', '1M')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('index_enable_supercells', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('email_notification', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_download', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_slideshow', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_pic_size_on_thumb', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('hon_rate_users', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('hon_rate_where', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('hon_rate_sep', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('hon_rate_times', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('disp_watermark_at', '3')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('wut_users', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('use_watermark', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('rate_type', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('disp_rand', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('disp_mostv', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('disp_high', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('disp_late', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('img_cols', '4')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('img_rows', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('midthumb_use', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('midthumb_height', '450')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('midthumb_width', '600')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('midthumb_cache', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_files_to_upload', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_pregenerated_fields', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('dynamic_fields', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('pregenerate_fields', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('propercase_pic_title', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_index_last_pic_lv', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_pics_approval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_img_no_gd', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('dynamic_pic_resampling', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_file_size_resampling', '1024000')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('switch_nuffload', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('path_to_bin', './cgi-bin/')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('perl_uploader', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_progress_bar', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('close_on_finish', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_pause', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('simple_format', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('multiple_uploads', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('max_uploads', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('zip_uploads', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('resize_pic', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('resize_width', '600')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('resize_height', '600')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('resize_quality', '70')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_pics_nav', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_inline_copyright', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('enable_nuffimage', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('enable_sepia_bw', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('personal_allow_avatar_gallery', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_gif_mid_thumb', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('slideshow_script', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upload_dir','files')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upload_img','images/attach_post.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('topic_icon','images/disk_multiple.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('display_order','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('max_filesize','262144')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('attachment_quota','52428800')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('max_filesize_pm','262144')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('max_attachments','3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('max_attachments_pm','1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('disable_attachments_mod','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('allow_pm_attach','1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('attachment_topic_review','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('allow_ftp_upload','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('show_apcp','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('attach_version','2.4.0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('default_upload_quota', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('default_pm_quota', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ftp_server','')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ftp_path','')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('download_path','')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ftp_user','')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ftp_pass','')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ftp_pasv_mode','1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_display_inlined','1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_max_width','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_max_height','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_link_width','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_link_height','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_create_thumbnail','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_min_thumb_filesize','12000')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('img_imagick', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('use_gd2','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('wma_autoplay','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('flash_autoplay','0')";
		$sql[] = "INSERT INTO " . $table_prefix . "forbidden_extensions (ext_id, extension) VALUES (1,'php')";
		$sql[] = "INSERT INTO " . $table_prefix . "forbidden_extensions (ext_id, extension) VALUES (2,'php3')";
		$sql[] = "INSERT INTO " . $table_prefix . "forbidden_extensions (ext_id, extension) VALUES (3,'php4')";
		$sql[] = "INSERT INTO " . $table_prefix . "forbidden_extensions (ext_id, extension) VALUES (4,'phtml')";
		$sql[] = "INSERT INTO " . $table_prefix . "forbidden_extensions (ext_id, extension) VALUES (5,'pl')";
		$sql[] = "INSERT INTO " . $table_prefix . "forbidden_extensions (ext_id, extension) VALUES (6,'asp')";
		$sql[] = "INSERT INTO " . $table_prefix . "forbidden_extensions (ext_id, extension) VALUES (7,'cgi')";
		$sql[] = "INSERT INTO " . $table_prefix . "extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (1,'Images',1,1,1,'',0,'')";
		$sql[] = "INSERT INTO " . $table_prefix . "extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (2,'Archives',0,1,1,'',0,'')";
		$sql[] = "INSERT INTO " . $table_prefix . "extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (3,'Plain Text',0,0,1,'',0,'')";
		$sql[] = "INSERT INTO " . $table_prefix . "extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (4,'Documents',0,0,1,'',0,'')";
		$sql[] = "INSERT INTO " . $table_prefix . "extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (5,'Real Media',0,0,2,'',0,'')";
		$sql[] = "INSERT INTO " . $table_prefix . "extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (6,'Streams',2,0,1,'',0,'')";
		$sql[] = "INSERT INTO " . $table_prefix . "extension_groups (group_id, group_name, cat_id, allow_group, download_mode, upload_icon, max_filesize, forum_permissions) VALUES (7,'Flash Files',3,0,1,'',0,'')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (1, 1,'gif', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (2, 1,'png', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (3, 1,'jpeg', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (4, 1,'jpg', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (5, 1,'tif', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (6, 1,'tga', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (7, 2,'gtar', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (8, 2,'gz', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (9, 2,'tar', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (10, 2,'zip', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (11, 2,'rar', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (12, 2,'ace', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (13, 3,'txt', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (14, 3,'c', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (15, 3,'h', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (16, 3,'cpp', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (17, 3,'hpp', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (18, 3,'diz', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (19, 4,'xls', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (20, 4,'doc', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (21, 4,'dot', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (22, 4,'pdf', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (23, 4,'ai', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (24, 4,'ps', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (25, 4,'ppt', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (26, 5,'rm', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (27, 6,'wma', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "extensions (ext_id, group_id, extension, comment) VALUES (28, 7,'swf', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (1, 'Low', 262144)";
		$sql[] = "INSERT INTO " . $table_prefix . "quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (2, 'Medium', 2097152)";
		$sql[] = "INSERT INTO " . $table_prefix . "quota_limits (quota_limit_id, quota_desc, quota_limit) VALUES (3, 'High', 5242880)";
		$sql[] = "INSERT INTO `" . $table_prefix . "autolinks` (`link_id`, `link_keyword`, `link_title`, `link_url`, `link_comment`, `link_style`, `link_forum`, `link_int`) VALUES (1, 'phpbb', 'phpBB', 'http://www.phpbb.com', 'phpBB creating communities', 'text-decoration: none;', 0, 0)";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sig_line', '____________')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('birthday_required', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('birthday_greeting', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('max_user_age', '100')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('min_user_age', '5')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('birthday_check_day', '7')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('bluecard_limit', '3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('bluecard_limit_2', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('max_user_bancard', '3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('report_forum', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('index_rating_return', '10')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('min_rates_number', '5')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('rating_max', '10')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('allow_ext_rating', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('large_rating_return_limit', '30')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('check_anon_ip_when_rating', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('allow_rerate', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('header_rating_return_limit', '3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('default_time_mode', '6')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('default_dst_time_lag', '60')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('allow_news', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_item_trim', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_title_trim', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_item_num', '10')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_path', 'images/news')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('allow_rss', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_desc', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_language', 'en_us')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_ttl', '60')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_cat', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_image', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_image_desc', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_item_count', '15')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_rss_show_abstract', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_base_url', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('news_index_file', 'index.php')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuild_end', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuild_pos', '-1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuildcfg_maxmemory', '500')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuildcfg_minposts', '3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuildcfg_php3only', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuildcfg_php3pps', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuildcfg_php4pps', '8')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuildcfg_timelimit', '240')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_rebuildcfg_timeoverwrite', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_disallow_postcounter', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('dbmtnc_disallow_rebuild', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('default_avatar_guests_url', 'images/avatars/default_avatars/guest.gif')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('default_avatar_users_url', 'images/avatars/default_avatars/member.gif')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('enable_gravatars', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('gravatar_rating', 'PG')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('gravatar_default_image', 'images/avatars/default_avatars/member.gif')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('default_avatar_set', '2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('bin_forum', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('liw_enabled', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('liw_sig_enabled', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('liw_max_width', '500')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('liw_attach_enabled', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_news_version', '2.0.3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('board_disable_message', 'Site disabled')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('board_disable_mess_st', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sitemap_announce_priority', '1.0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sitemap_default_priority', '0.5')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sitemap_sort', 'DESC')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sitemap_sticky_priority', '0.75')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sitemap_topic_limit', '250')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('registration_status', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('registration_closed', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('prune_shouts', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_shownav', '17')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('allow_avatar_generator', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('avatar_generator_template_path', 'images/avatars/generator_templates')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('avatar_generator_version', '2.0.2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('hidde_last_logon', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('online_time', '60')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('gzip_level', '9')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('gender_required', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('smilie_columns', '6')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('smilie_rows', '6')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('smilie_window_columns', '10')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('allow_autologin', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('max_autologin_time', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('autolink_first', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_display_open', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_display_open_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_header_cells', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_header_cells_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_week_start', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_week_start_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_title_length', '30')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_text_length', '200')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_nb_row', '5')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_nb_row_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_birthday', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_birthday_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_forum', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('calendar_forum_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sub_forum', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sub_forum_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('split_cat', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('split_cat_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('last_topic_title', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('last_topic_title_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('last_topic_title_length', '24')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sub_level_links', '2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('sub_level_links_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('display_viewonline', '2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('display_viewonline_over', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('max_posts', '2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('max_topics', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('max_users', '2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_auto_compile', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_auto_recompile', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_use_cache', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_php', 'php')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_def_template', 'default')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_check_switches', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_warn_includes', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_add_comments', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_ftp_host', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_ftp_login', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_ftp_path', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_downloads_count', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_downloads_default', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_template_time', '1132930673')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xs_version', '7')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('url_rw', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('switch_header_table', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('header_table_text', 'Text')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('fast_n_furious', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('new_msgs_mumber', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('index_last_msgs', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('portal_last_msgs', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('online_last_msgs', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('index_shoutbox', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('portal_shoutbox', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('online_shoutbox', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('last_msgs_n', '5')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('last_msgs_x', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('posts_precompiled', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('index_birthday', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('site_history', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('smilies_topic_title', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('html_email', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('config_cache', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('ftr_disable', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('disable_logins', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('last_logins_n', '20')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('edit_notes', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('edit_notes_n', '5')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('quote_iterations', '2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('page_gen', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('birthday_viewtopic', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('img_shoutbox', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('split_ga_ann_sticky', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('email_notification_html', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('select_theme', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('select_lang', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('show_icons', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "force_read` VALUES (1, 'Before going on... please make sure you have read and understood this post. It contains important informations regarding this site.', 0, 1, 1)";

		$sql[] = "INSERT INTO `" . $table_prefix . "kb_articles` VALUES (1, 1, 'Test Article', 'This is a test article for your KB', '1057708235', 2, '', '93074f48a9', 'This is a test article for your Knowledge Base. This MOD is based on code written by wGEric < eric@egcnetwork.com > (Eric Faerber) - http://eric.best-1.biz/, now supervised by _Haplo < jonohlsson@hotmail.com > (Jon Ohlsson) - http://www.mx-system.com/ \r\n\r\nBe sure you add categories and article types in the ACP and also change the Configuration to your liking.\r\n\r\nHave fun and enjoy your new Knowledge Base!  :D', 1, 1, 0, 0, '0.0000', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_categories` VALUES (1, 'Test Category 1', 'This is a test category', 0, 0, 10, 0, 0, 0, 0, 0, 2, 0, 0, '', '', '', '', '', '', '', '', '', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('allow_new', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('notify', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('admin_id', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('show_pretext', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('pt_header', 'Article Submission Instructions')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('pt_body', 'Please check your references and include as much information as you can.')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('use_comments', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('del_topic', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('use_ratings', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('comments_show', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('bump_post', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('stats_list', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('header_banner', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('votes_check_userid', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('votes_check_ip', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('art_pagination', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('comments_pagination', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('news_sort', 'Alphabetic')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('news_sort_par', 'ASC')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('wysiwyg', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('wysiwyg_path', 'modules/')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('allow_html', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('allow_bbcode', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('allow_smilies', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('formatting_fixup', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_config` VALUES ('allowed_html_tags', 'a,b,i,u')";
		$sql[] = "INSERT INTO `" . $table_prefix . "kb_types` VALUES (1, 'Test Type 1')";

		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (1, 'Arts', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (2, 'Business', 2)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (3, 'Children and Teens', 3)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (4, 'Computers', 4)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (5, 'Games', 5)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (6, 'Health', 6)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (7, 'Home', 7)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_categories` VALUES (8, 'News', 8)";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('width', '88')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('height', '31')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('linkspp', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('display_interval', '6000')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('display_logo_num', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('display_links_logo', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('email_notify', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('pm_notify', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('lock_submit_site', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('allow_no_logo', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('site_logo', 'http://www.icyphoenix.com/images/icy_phoenix_logo.png')";
		$sql[] = "INSERT INTO `" . $table_prefix . "link_config` VALUES ('site_url', 'http://www.icyphoenix.com/')";
		$sql[] = "INSERT INTO `" . $table_prefix . "links` VALUES (1, 'phpBB Official Website', 'Official phpBB Website', 4, 'http://www.phpbb.com/', 'images/links/banner_phpbb88a.gif', 1125353670, 1, 0, 2, '', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "links` VALUES (2, 'Icy Phoenix Official Website', 'Icy Phoenix', 4, 'http://www.icyphoenix.com/', 'images/links/banner_ip.gif', 1125353670, 1, 0, 2, '', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "links` VALUES (3, 'Mighty Gorgon Community', 'Mighty Gorgon Community', 4, 'http://www.mightygorgon.com/', 'images/links/banner_mightygorgon.gif', 1125353670, 1, 0, 2, '', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "news` VALUES (1, 'News', '48_leaf_orange.png')";
		$sql[] = "INSERT INTO `" . $table_prefix . "notes` VALUES (1, 'Write here your notes')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_cat` VALUES (1, 'My Category', '', 0, '', 1, 0, 0, 0, 0, 0, '0', 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_cat` VALUES (2, 'Test Cagegory', 'Just a test category', 1, '', 2, 1, 0, 0, 0, 0, '0', 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('allow_comment_images', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('no_comment_image_message', '[No image please]')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('allow_smilies', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('allow_comment_links', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('no_comment_link_message', '[No links please]')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_disable', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('allow_html', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('allow_bbcode', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_topnumber', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_newdays', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_stats', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_viewall', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_dbname', 'Download Database')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_dbdescription', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('max_comment_chars', '5000')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('tpl_php', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('settings_file_page', '20')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('hotlink_prevent', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('hotlink_allowed', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('sort_method', 'file_time')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('sort_order', 'DESC')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('auth_search', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('auth_stats', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('auth_toplist', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('auth_viewall', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('max_file_size', '262144')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('upload_dir', 'downloads/')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('screenshots_dir', 'files/screenshots/')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('forbidden_extensions', 'php, php3, php4, phtml, pl, asp, aspx, cgi')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('need_validation', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('validator', 'validator_admin')";
		$sql[] = "INSERT INTO `" . $table_prefix . "pa_config` VALUES ('pm_notify', '0')";

		$sql[] = "INSERT INTO `" . $table_prefix . "referers` VALUES (1, 'icyphoenix.com', 'http://icyphoenix.com', '127.0.0.1', 1, 1121336515, 1121336515)";
		$sql[] = "INSERT INTO `" . $table_prefix . "stats_config` VALUES ('return_limit', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "stats_config` VALUES ('version', '2.1.5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "stats_config` VALUES ('modules_dir', 'includes/stat_modules')";
		$sql[] = "INSERT INTO `" . $table_prefix . "stats_config` VALUES ('page_views', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "stats_config` VALUES ('install_date', '1132930604')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` VALUES ('xs_show_news', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` VALUES ('xs_show_ticker', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` VALUES ('xs_news_dateformat', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` VALUES ('xs_show_ticker_subtitle', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` VALUES ('xs_show_news_subtitle', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "xs_news_xml` VALUES (1, 'BBC News UK Edition', 1, 'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/world/rss.xml', 1, '98%', '20', '0', '3', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "xs_news_xml` VALUES (2, 'Simple Text Test', 1, 'This is just some text I want to scroll, it could contain just about anything you like', 0, '98%', '20', '13', '3', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "xs_news_xml` VALUES (3, 'Exchange', 1, 'http://rss.msexchange.org/allnews.xml', 1, '98%', '20', '0', '3', 0)";
		$sql[] = "DROP TABLE " . $table_prefix . "cracktrack";
		$sql[] = "ALTER TABLE " . $table_prefix . "users DROP ct_search";

		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_login_tries smallint(5) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_last_login_try int(11) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "smilies ADD smilies_order INT(5) NOT NULL";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('smilies_insert', '1')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_show_portal` TINYINT(1) NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` ADD `enable_autolinks_acronyms` TINYINT(1) NOT NULL DEFAULT '1' AFTER `enable_smilies`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "privmsgs` ADD `privmsgs_enable_autolinks_acronyms` TINYINT(1) NOT NULL DEFAULT '0' AFTER `privmsgs_enable_smilies`";
		/*
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_session_topic` INT(11) NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "sessions` ADD `session_topic` INT(11) NOT NULL";
		*/

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('show_random_quote', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "album_config (config_name, config_value) VALUES ('show_exif', '0')";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD user_sudoku_playing INT(1) DEFAULT '0' NOT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "bookmarks` (
			topic_id mediumint(8) unsigned NOT NULL default '0',
			user_id mediumint(8) NOT NULL default '0',
			KEY topic_id (topic_id),
			KEY user_id (user_id))";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD user_setbm tinyint(1) NOT NULL default '0' AFTER user_attachsig";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('max_link_bookmarks', '0')";

		$sql[] = "ALTER TABLE `" . $table_prefix . "search_results` ADD COLUMN search_time int(11) DEFAULT '0' NOT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "profile_fields` (
		`field_id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		`field_name` VARCHAR(255) NOT NULL ,
		`field_description` VARCHAR(255) NULL ,
		`field_type` TINYINT(4) UNSIGNED NOT NULL DEFAULT '0',
		`text_field_default` VARCHAR(255) NULL ,
		`text_field_maxlen` INT(255) UNSIGNED NOT NULL DEFAULT '255',
		`text_area_default` TEXT NULL ,
		`text_area_maxlen` INT(255) UNSIGNED NOT NULL DEFAULT '1024',
		`radio_button_default` VARCHAR(255) NULL ,
		`radio_button_values` TEXT NULL ,
		`checkbox_default` TEXT NULL ,
		`checkbox_values` TEXT NULL ,
		`is_required` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
		`users_can_view` TINYINT(2) UNSIGNED NOT NULL DEFAULT '1',
		`view_in_profile` TINYINT(2) UNSIGNED NOT NULL DEFAULT '1',
		`profile_location` TINYINT(2) UNSIGNED NOT NULL DEFAULT '2',
		`view_in_memberlist` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
		`view_in_topic` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0',
		`topic_location` TINYINT(2) UNSIGNED NOT NULL DEFAULT '1',
		PRIMARY KEY (field_id),
		INDEX (`field_type`) ,
		UNIQUE (`field_name`)
		)";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('visit_counter', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('visit_counter_switch', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('emails_only_to_admins', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('no_right_click', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('word_graph_max_words', '250')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('word_graph_word_counts', '0')";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('show_pic_size_on_thumb', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('show_img_no_gd', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('thumbnail_cache', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('gd_version', '2')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('thumbnail_quality', '85')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('thumbnail_size', '400')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('thumbnail_posts', '0')";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('search_min_chars', '3')";

		/* Updating from 049 */
		$sql[] = "ALTER TABLE " . $table_prefix . "config CHANGE `config_value` `config_value` TEXT";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('disable_html_guests', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('disable_email_error', '1')";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('extra_max', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('extra_display', '0')";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_from_flag` varchar(30) default NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_phone` varchar(255) default NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_selfdes` text";

		$sql[] = "DROP TABLE " . $table_prefix . "flags";

		$sql[] = "CREATE TABLE " . $table_prefix . "flags (
			flag_id int(10) NOT NULL auto_increment,
			flag_name varchar(25) default NULL,
			flag_image varchar(25) default NULL,
			PRIMARY KEY (flag_id)
		)";

		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Afghanistan', 'afghanistan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('AI', 'ai.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Albania', 'albania.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Algeria', 'algeria.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('AN', 'an.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Andorra', 'andorra.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Antiguabarbuda', 'antiguabarbuda.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('AO', 'ao.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Argentina', 'argentina.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Armenia', 'armenia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('AS', 'as.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Australia', 'australia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Austria', 'austria.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('AW', 'aw.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('AX', 'ax.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Azerbaijan', 'azerbaijan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Bahamas', 'bahamas.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Bahrain', 'bahrain.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Bangladesh', 'bangladesh.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Barbados', 'barbados.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Belarus', 'belarus.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Belgium', 'belgium.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Belize', 'belize.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Benin', 'benin.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Bhutan', 'bhutan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('BM', 'bm.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Bolivia', 'bolivia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Bosnia Herzegovina', 'bosnia_herzegovina.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Botswana', 'botswana.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Brazil', 'brazil.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Brunei', 'brunei.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Bulgaria', 'bulgaria.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Burkinafaso', 'burkinafaso.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Burma', 'burma.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Burundi', 'burundi.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('BV', 'bv.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Cambodia', 'cambodia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Cameroon', 'cameroon.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Canada', 'canada.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('CC', 'cc.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Central African Republic', 'central_african_republic.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Chad', 'chad.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Chile', 'chile.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('China', 'china.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('CK', 'ck.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Columbia', 'columbia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Comoros', 'comoros.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Congo', 'congo.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Costa Rica', 'costa_rica.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Croatia', 'croatia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Cuba', 'cuba.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('CV', 'cv.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('CX', 'cx.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Cyprus', 'cyprus.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Czech Republic', 'czech_republic.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Dem Rep Congo', 'dem_rep_congo.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Denmark', 'denmark.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Djibouti', 'djibouti.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Dominica', 'dominica.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Dominican Rep', 'dominican_rep.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Ecuador', 'ecuador.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Egypt', 'egypt.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('EH', 'eh.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Elsalvador', 'elsalvador.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('England', 'england.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Eq Guinea', 'eq_guinea.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Eritrea', 'eritrea.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Estonia', 'estonia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Ethiopia', 'ethiopia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('FAM', 'fam.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Fiji', 'fiji.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Finland', 'finland.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('FK', 'fk.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('FO', 'fo.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('France', 'france.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Gabon', 'gabon.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Gambia', 'gambia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Georgia', 'georgia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Germany', 'germany.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Ghana', 'ghana.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('GI', 'gi.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('GL', 'gl.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('GP', 'gp.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Greece', 'greece.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Grenada', 'grenada.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Grenadines', 'grenadines.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('GS', 'gs.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('GU', 'gu.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Guatemala', 'guatemala.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Guinea', 'guinea.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Guinea Bissau', 'guinea_bissau.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Guyana', 'guyana.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Haiti', 'haiti.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Honduras', 'honduras.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Hong Kong', 'hong_kong.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Hungary', 'hungary.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Iceland', 'iceland.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('India', 'india.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Indonesia', 'indonesia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('IO', 'io.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Iran', 'iran.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Iraq', 'iraq.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Ireland', 'ireland.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Israel', 'israel.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Italia', 'italia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Ivory Coast', 'ivory_coast.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Jamaica', 'jamaica.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Japan', 'japan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Jordan', 'jordan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Kazakhstan', 'kazakhstan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Kenya', 'kenya.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Kiribati', 'kiribati.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('KP', 'kp.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Kuwait', 'kuwait.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('KY', 'ky.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Kyrgyzstan', 'kyrgyzstan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Laos', 'laos.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Latvia', 'latvia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Lebanon', 'lebanon.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Liberia', 'liberia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Libya', 'libya.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Liechtenstein', 'liechtenstein.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Lithuania', 'lithuania.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('LS', 'ls.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Luxembourg', 'luxembourg.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Macau', 'macau.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Madagascar', 'madagascar.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Malawi', 'malawi.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Malaysia', 'malaysia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Maldives', 'maldives.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Mali', 'mali.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Malta', 'malta.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Mauritania', 'mauritania.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Mauritius', 'mauritius.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Mexico', 'mexico.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('MH', 'mh.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Micronesia', 'micronesia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('MK', 'mk.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Moldova', 'moldova.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Monaco', 'monaco.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Mongolia', 'mongolia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Morocco', 'morocco.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Mozambique', 'mozambique.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('MP', 'mp.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('MS', 'ms.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Namibia', 'namibia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Nauru', 'nauru.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('NC', 'nc.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Nepal', 'nepal.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Netherlands', 'netherlands.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('New Zealand', 'new_zealand.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('NF', 'nf.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Nicaragua', 'nicaragua.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Niger', 'niger.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Nigeria', 'nigeria.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Norway', 'norway.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('NU', 'nu.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Oman', 'oman.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Pakistan', 'pakistan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Panama', 'panama.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Papua New Guinea', 'papua_new_guinea.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Paraguay', 'paraguay.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Peru', 'peru.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('PF', 'pf.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Philippines', 'philippines.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('PM', 'pm.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('PN', 'pn.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Poland', 'poland.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Portugal', 'portugal.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('PS', 'ps.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Puerto Rico', 'puerto_rico.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('PW', 'pw.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Qatar', 'qatar.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Quebec', 'quebec.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Romania', 'romania.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Russia', 'russia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Rwanda', 'rwanda.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Sao Tome', 'sao_tome.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Saudi Arabia', 'saudi_arabia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Scotland', 'scotland.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Senegal', 'senegal.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Serbia', 'serbia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Seychelles', 'seychelles.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('SH', 'sh.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Sierraleone', 'sierraleone.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Singapore', 'singapore.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Slovakia', 'slovakia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Slovenia', 'slovenia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('SM', 'sm.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Solomon Islands', 'solomon_islands.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Somalia', 'somalia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('South Africa', 'south_africa.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('South Korea', 'south_korea.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Spain', 'spain.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Sri Lanka', 'sri_lanka.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Stkitts Nevis', 'stkitts_nevis.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Stlucia', 'stlucia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Sudan', 'sudan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Suriname', 'suriname.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Sweden', 'sweden.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Switzerland', 'switzerland.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Syria', 'syria.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('SZ', 'sz.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Taiwan', 'taiwan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Tajikistan', 'tajikistan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Tanzania', 'tanzania.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('TF', 'tf.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Thailand', 'thailand.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('TK', 'tk.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('TL', 'tl.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Togo', 'togo.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Tonga', 'tonga.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Trinidat And Tobago', 'trinidat_and_tobago.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Tunisia', 'tunisia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Turkey', 'turkey.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Turkmenistan', 'turkmenistan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Tuvala', 'tuvala.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('TV', 'tv.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Uganda', 'uganda.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('UK', 'uk.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Ukraine', 'ukraine.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('UM', 'um.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('United Arabic Emirates', 'united_arabic_emirates.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Uruguay', 'uruguay.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('USA', 'usa.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Uzbekistan', 'uzbekistan.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Vanuatu', 'vanuatu.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Vatican', 'vatican.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Venezuela', 'venezuela.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('VG', 'vg.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('VI', 'vi.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Vietnam', 'vietnam.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Wales', 'wales.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Western Samoa', 'western_samoa.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('WF', 'wf.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Yemen', 'yemen.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('YT', 'yt.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Yugoslavia', 'yugoslavia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Zambia', 'zambia.png')";
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Zimbabwe', 'zimbabwe.png')";

		$sql[] = "CREATE TABLE " . $table_prefix . "upi2db_always_read (
			topic_id mediumint(8) unsigned NOT NULL default 0,
			forum_id mediumint(8) unsigned NOT NULL default 0,
			user_id mediumint(8) unsigned NOT NULL default 0,
			last_update int(11) NOT NULL default 0,
			KEY forum_id (forum_id),
			KEY topic_id (topic_id)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "upi2db_last_posts (
			post_id mediumint(8) unsigned NOT NULL default 0,
			topic_id mediumint(8) unsigned NOT NULL default 0,
			forum_id smallint(5) unsigned NOT NULL default 0,
			poster_id mediumint(8) NOT NULL default 0,
			post_time int(11) NOT NULL default 0,
			post_edit_time int(11) NOT NULL default 0,
			topic_type tinyint(1) NOT NULL default 0,
			post_edit_by mediumint(8) NOT NULL default 0,
			PRIMARY KEY (post_id)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "upi2db_unread_posts (
			post_id mediumint(8) unsigned NOT NULL default 0,
			topic_id mediumint(8) unsigned NOT NULL default 0,
			forum_id smallint(5) unsigned NOT NULL default 0,
			user_id mediumint(8) unsigned NOT NULL default 0,
			status tinyint(1) NOT NULL default 0,
			topic_type tinyint(1) NOT NULL default 0,
			last_update int(11) NOT NULL default 0,
			KEY post_id (post_id),
			KEY user_id (user_id)
		)";

		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_upi2db_which_system TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_upi2db_disable TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_upi2db_datasync INT(11) DEFAULT '0' NOT NULL";

		$sql[] = "ALTER TABLE " . $table_prefix . "groups ADD upi2db_on TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "groups ADD upi2db_min_posts MEDIUMINT(4) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "groups ADD upi2db_min_regdays MEDIUMINT(4) DEFAULT '0' NOT NULL";

		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_upi2db_new_word TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_upi2db_edit_word TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_upi2db_unread_color TINYINT(1) DEFAULT '1' NOT NULL";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_max_permanent_topics', '20')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_del_mark', '60')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_del_perm', '120')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_max_mark_posts', '10')";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_auto_read', '30')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_edit_as_new', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_last_edit_as_new', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_on', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_edit_topic_first', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_no_group_min_regdays', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_no_group_min_posts', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_no_group_upi2db_on', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_install_time', '$install_time')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_delete_old_data', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_max_new_posts', '1000')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('upi2db_version', '3.0.7')";

		$sql[] = "ALTER TABLE " . $table_prefix . "forums ADD forum_postcount TINYINT(1) DEFAULT '1' NOT NULL";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('switch_poster_info_topic', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('switch_bbcb_active_content', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('enable_quick_quote', '0')";

		$sql[] = "ALTER TABLE " . $table_prefix . "users ADD user_personal_pics_count INT DEFAULT '0' NOT NULL";

		$sql[] = "INSERT INTO " . $table_prefix . "links VALUES (4, 'Icy Phoenix German Support', 'Icy Phoenix German Support', 4, 'http://www.icyphoenix.de/', 'images/links/ip_de.gif', 1125353670, 1, 0, 2, '', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "links VALUES (5, 'Icy Phoenix Spanish Support', 'Icy Phoenix Spanish Support', 4, 'http://www.icyphoenix.es/', 'images/links/ip_es.gif', 1125353670, 1, 0, 2, '', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "links VALUES (6, 'Icy Phoenix Italian Support', 'Icy Phoenix Italian Support', 4, 'http://www.icyphoenix.it/', 'images/links/ip_it.gif', 1125353670, 1, 0, 2, '', '')";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('use_captcha', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('enable_xs_version_check', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('allow_all_bbcode', '0')";

		/* Updating from 058 */
		$sql[] = "ALTER TABLE " . $table_prefix . "config CHANGE `config_value` `config_value` TEXT";

		$sql[] = "DROP TABLE `" . $table_prefix . "ctrack`";
		$sql[] = "DROP TABLE `" . $table_prefix . "ct_filter`";
		$sql[] = "DROP TABLE `" . $table_prefix . "ct_viskey`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_logintry`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_unsucclogin`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_pwreset`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_mailcount`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_postcount`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_posttime`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_searchcount`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_searchtime`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_search_time` INT(11) NULL DEFAULT 1 AFTER `user_newpasswd`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_search_count` MEDIUMINT(8) NULL DEFAULT 1 AFTER `ct_search_time`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_last_post` INT(11) NULL DEFAULT 1 AFTER `ct_search_count`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_post_counter` MEDIUMINT(8) NULL DEFAULT 1 AFTER `ct_last_post`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_enable_ip_warn` TINYINT(1) NULL DEFAULT 1 AFTER `ct_post_counter`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_last_used_ip` VARCHAR(16) NULL DEFAULT '0.0.0.0' AFTER `ct_enable_ip_warn`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_last_ip` VARCHAR(16) NULL DEFAULT '0.0.0.0' AFTER `ct_last_used_ip`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_global_msg_read` TINYINT(1) NULL DEFAULT 0 AFTER `ct_last_ip`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `ct_miserable_user` TINYINT(1) NULL DEFAULT 0 AFTER `ct_global_msg_read`;";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_enabled', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_ipblock_logsize', '100');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_auto_recovery', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_vconfirm_guest', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_autoban_mails', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_guest', '30');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_search_time_user', '20');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_guest', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_search_count_user', '4');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_protection', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_reg_protection', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_reg_blocktime', '30');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_reg_lastip', '0.0.0.0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_pwreset_time', '20');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_massmail_time', '20');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_time', '30');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_postcount', '4');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_spammer_blockmode', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_loginfeature', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_pw_reset_feature', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_reg_last_reg', '1155944976');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_login_history', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_login_history_count', '10');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_login_ip_check', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_pw_validity', '30');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex_min', '4');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex_mode', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_pw_control', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_pw_complex', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_last_file_scan', '1156000091');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_last_checksum_scan', '1156000082');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_logsize_logins', '100');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_logsize_spammer', '100');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_reg_ip_scan', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_global_message', 'Hello world!');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_global_message_type', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_search_feature_enabled', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_spam_attack_boost', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_spam_keyword_det', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ctracker_footer_layout', '6');";

		// Create File Check Table
		$sql[] = "CREATE TABLE `" . $table_prefix . "ctracker_filechk` (
				`filepath` TEXT NOT NULL,
				`hash` varchar(32) default NULL
				);";

		// Create File Scanner Table
		$sql[] = "CREATE TABLE `" . $table_prefix . "ctracker_filescanner` (
				`id` smallint(5) NOT NULL,
				`filepath` TEXT NOT NULL,
				`safety` smallint(1) NOT NULL default '0',
				PRIMARY KEY (`id`)
				);";

		// Create IP Blocker Table with its entrys
		$sql[] = "CREATE TABLE `" . $table_prefix . "ctracker_ipblocker` (
				`id` mediumint(8) unsigned NOT NULL,
				`ct_blocker_value` varchar(250) default NULL,
				PRIMARY KEY (`id`)
				);";

		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (1, '*WebStripper*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (2, '*NetMechanic*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (3, '*CherryPicker*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (4, '*EmailCollector*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (5, '*EmailSiphon*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (6, '*WebBandit*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (7, '*EmailWolf*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (8, '*ExtractorPro*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (9, '*SiteSnagger*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (10, '*CheeseBot*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (11, '*ia_archiver*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (12, '*Website Quester*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (13, '*WebZip*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (14, '*moget*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (15, '*WebSauger*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (16, '*WebCopier*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (17, '*WWW-Collector*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (18, '*InfoNaviRobot*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (19, '*Harvest*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (20, '*Bullseye*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (21, '*LinkWalker*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (22, '*LinkextractorPro*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (23, '*Proxy*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (24, '*BlowFish*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (25, '*WebEnhancer*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (26, '*TightTwatBot*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (27, '*LinkScan*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (28, '*WebDownloader*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (29, 'lwp');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (30, '*BruteForce*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (31, 'lwp-*');";
		$sql[] = "INSERT INTO `" . $table_prefix . "ctracker_ipblocker` (`id`, `ct_blocker_value`) VALUES (32, '*anonym*');";

		// Create Login History Table
		$sql[] = "CREATE TABLE `" . $table_prefix . "ctracker_loginhistory` (
				`ct_user_id` int(10) default NULL,
				`ct_login_ip` varchar(40) default NULL,
				`ct_login_time` int(11) NOT NULL default '0'
				);";

		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('quick_thumbs', '0');";
		$sql[] = "ALTER TABLE `" . $table_prefix . "album_cat` ADD `cat_wm` TEXT AFTER `cat_desc`;";

		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('set_memory', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('lb_preview', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('use_old_pics_gen', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_last_comments', '0');";

		$sql[] = "UPDATE " . $table_prefix . "stats_config SET config_value = 'includes/stat_modules' WHERE config_name = 'modules_dir'";

		$sql[] = "CREATE TABLE `" . $table_prefix . "digest_subscriptions` (
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
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "digest_subscribed_forums` (
			`user_id` MEDIUMINT(8) NOT NULL DEFAULT 0,
			`forum_id` SMALLINT(5) NOT NULL DEFAULT 0,
			UNIQUE user_id (user_id, forum_id)
		)";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('forum_wordgraph', '0')";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD FULLTEXT (topic_title)";
		//$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD FULLTEXT (topic_desc)";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('similar_topics', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('similar_stopwords', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('similar_ignore_forums_ids', '')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('similar_sort_type', 'relev')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('similar_max_topics', '5')";
		//$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('similar_topicdesc', '0')";

		$sql[] = "UPDATE " . $table_prefix . "config SET `config_value` = 'images/avatars/generator_templates' WHERE `config_name` = 'avatar_generator_template_path'";
		$sql[] = "UPDATE " . $table_prefix . "config SET `config_value` = 'images/avatars/default_avatars/guest.gif' WHERE `config_name` = 'default_avatar_guests_url'";
		$sql[] = "UPDATE " . $table_prefix . "config SET `config_value` = 'images/avatars/default_avatars/member.gif' WHERE `config_name` = 'default_avatar_users_url'";
		$sql[] = "UPDATE " . $table_prefix . "config SET `config_value` = 'images/avatars/default_avatars/member.gif' WHERE `config_name` = 'gravatar_default_image'";

		// Icy Phoenix CMS - BEGIN

		$sql[] = "DROP TABLE `" . $table_prefix . "block_position`";
		$sql[] = "DROP TABLE `" . $table_prefix . "block_variable`";
		$sql[] = "DROP TABLE `" . $table_prefix . "blocks`";
		$sql[] = "DROP TABLE `" . $table_prefix . "layout`";
		$sql[] = "DROP TABLE `" . $table_prefix . "portal_config`";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_block_position` (
			`bpid` int(10) NOT NULL auto_increment,
			`layout` int(10) NOT NULL default '1',
			`pkey` varchar(30) NOT NULL default '',
			`bposition` char(2) NOT NULL default '',
			PRIMARY KEY (`bpid`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_block_variable` (
			`bvid` int(10) NOT NULL auto_increment,
			`bid` int(10) NOT NULL default '0',
			`label` varchar(30) NOT NULL default '',
			`sub_label` varchar(255) default NULL,
			`config_name` varchar(30) NOT NULL default '',
			`field_options` varchar(255) default NULL,
			`field_values` varchar(255) default NULL,
			`type` tinyint(1) NOT NULL default '0',
			`block` varchar(255) default NULL,
			PRIMARY KEY (`bvid`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_blocks` (
			`bid` int(10) NOT NULL auto_increment,
			`layout` int(10) NOT NULL default '0',
			`layout_special` int(10) NOT NULL default '0',
			`title` varchar(60) NOT NULL default '',
			`content` TEXT NOT NULL,
			`bposition` char(2) NOT NULL default '',
			`weight` int(10) NOT NULL default '1',
			`active` tinyint(1) NOT NULL default '1',
			`blockfile` varchar(255) NOT NULL default '',
			`view` tinyint(1) NOT NULL default '0',
			`cache` tinyint(1) NOT NULL default '0',
			`cache_time` int(10) NOT NULL default '0',
			`type` tinyint(1) NOT NULL default '1',
			`border` tinyint(1) NOT NULL default '1',
			`titlebar` tinyint(1) NOT NULL default '1',
			`background` tinyint(1) NOT NULL default '1',
			`local` tinyint(1) NOT NULL default '0',
			`groups` TINYTEXT NOT NULL,
			PRIMARY KEY (`bid`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_config` (
			`id` int(10) unsigned NOT NULL auto_increment,
			`bid` int(10) NOT NULL default '0',
			`config_name` varchar(255) NOT NULL default '',
			`config_value` varchar(255) NOT NULL default '',
			PRIMARY KEY (`id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_layout` (
			`lid` int(10) unsigned NOT NULL auto_increment,
			`name` varchar(100) NOT NULL default '',
			`filename` varchar(100) NOT NULL default '',
			`template` varchar(100) NOT NULL default '',
			`global_blocks` tinyint(1) NOT NULL default '0',
			`view` tinyint(1) NOT NULL default '0',
			`groups` TINYTEXT NOT NULL,
			PRIMARY KEY (`lid`)
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (1, 'header', 'hh', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (2, 'headerleft', 'hl', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (3, 'headercenter', 'hc', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (4, 'footercenter', 'fc', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (5, 'footerright', 'fr', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (6, 'footer', 'ff', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (7, 'left', 'l', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (8, 'center', 'c', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (9, 'right', 'r', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (10, 'xsnews', 'x', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (11, 'nav', 'n', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (12, 'centerbottom', 'b', 1)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (13, 'left', 'l', 2)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (14, 'center', 'c', 2)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (15, 'xsnews', 'x', 2)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (16, 'nav', 'n', 2)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`bpid`, `pkey`, `bposition`, `layout`) VALUES (17, 'centerbottom', 'b', 2)";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (1, 0, 'Default Portal', 'Default Portal', 'default_portal', '', '', 1, '@Portal Config')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (2, 0, 'Header width', 'Width of forum-wide left column in pixels', 'header_width', '', '', 1, '@Portal Config')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (3, 0, 'Footer width', 'Width of forum-wide right column in pixels', 'footer_width', '', '', 1, '@Portal Config')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (4, 3, 'Number of recent topics', 'number of topics displayed', 'md_num_recent_topics', '', '', 1, 'recent_topics')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (5, 3, 'Recent Topics Style', 'choose static display or scrolling display', 'md_recent_topics_style', 'Scroll,Static', '1,0', 3, 'recent_topics')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (6, 4, 'Poll Bar Length', 'decrease/increase the value for 1 vote bar length', 'md_poll_bar_length', '', '', 1, 'poll')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (7, 4, 'Poll Forum ID(s)', 'comma delimited', 'md_poll_forum_id', '', '', 1, 'poll')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (8, 8, 'Number of Top Posters', '', 'md_total_poster', '', '', 1, 'top_posters')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (9, 9, 'Search option text', 'Text displayed as the default option', 'md_search_option_text', '', '', 1, 'search')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (10, 11, 'Category to retrieve pics from', 'Enter 0 for all categories or comma delimited entries', 'md_cat_id', '', '', 1, 'album')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (11, 11, 'Display from what galleries?', '', 'md_pics_all', 'Public,Public and Personal', '0,1', 3, 'album')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (12, 11, 'Random or newest pics?', '', 'md_pics_sort', 'Newest,Random', '0,1', 3, 'album')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (13, 11, 'Number of images to display', '', 'md_pics_number', '', '', 1, 'album')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (14, 11, 'Number of columns', '', 'md_pics_cols_number', '1,2,3,4,5', '1,2,3,4,5', 3, 'album')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (15, 11, 'Number of rows', '', 'md_pics_rows_number', '1,2,3,4', '1,2,3,4', 3, 'album')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (16, 12, 'Links -> Style', 'choose static display or scrolling display', 'md_links_style', 'Scroll,Static', '1,0', 3, 'links')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (17, 12, 'Links -> Own (Top)', 'show your own link button above other buttons', 'md_links_own1', 'Yes,No', '1,0', 3, 'links')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (18, 12, 'Links -> Own (Bottom)', 'show your own link button below other buttons', 'md_links_own2', 'Yes,No', '1,0', 3, 'links')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (19, 12, 'Links -> Code', 'show HTML for your own link button', 'md_links_code', 'Yes,No', '1,0', 3, 'links')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (20, 14, 'Maximum Words', 'Select the maximum number of words to display', 'md_wordgraph_words', '', '', 1, 'wordgraph')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bvid`, `bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (21, 14, 'Enable Word Counts', 'Display the total number of words next to each word', 'md_wordgraph_count', 'Yes,No', '1,0', 3, 'wordgraph')";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (1, 'Nav Links', '', 'hl', 1, 0, 'nav_links', 0, 0, 0, 0, 0, 0, 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (2, 'Nav Links', '', 'l', 1, 1, 'nav_links', 0, 1, 0, 0, 0, 0, 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (3, 'Recent', '', 'l', 3, 0, 'recent_topics', 0, 1, 0, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (4, 'Poll', '', 'r', 4, 1, 'poll', 0, 1, 0, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (5, 'Welcome', '', 'c', 1, 1, 'welcome', 0, 1, 0, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (6, 'News', '', 'x', 1, 1, 'news', 0, 1, 0, 0, 0, 0, 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (7, 'User Block', '', 'r', 1, 1, 'user_block', 0, 1, 1, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (8, 'Top Posters', '', 'r', 5, 1, 'top_posters', 0, 1, 0, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (9, 'Search', '', 'l', 1, 1, 'search', 0, 1, 1, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (10, 'Who is Online', '', 'r', 2, 1, 'online_users', 0, 1, 1, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (11, 'Album', '', 'l', 2, 1, 'album', 0, 1, 0, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (12, 'Links', '', 'l', 4, 1, 'links', 0, 1, 0, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (13, 'Statistics', '', 'r', 3, 1, 'statistics', 0, 1, 0, 1, 1, 1, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (14, 'Wordgraph', '', 'b', 2, 1, 'wordgraph', 0, 1, 0, 0, 0, 0, 1, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_blocks` (`bid`, `title`, `content`, `bposition`, `weight`, `active`, `blockfile`, `view`, `layout`, `type`, `border`, `titlebar`, `background`, `local`, `groups`) VALUES (15, 'Welcome', '<table>\r\n	<tr>\r\n		<td width=\"5%\"><img src=\"images/icy_phoenix_small.png\" alt=\"\" /></td>\r\n		<td width=\"90%\" align=\"center\"><div class=\"post-text\">Welcome To <b>Icy Phoenix</b></div><br /><br /></td>\r\n		<td width=\"5%\"><img src=\"images/icy_phoenix_small_l.png\" alt=\"\" /></td>\r\n	</tr>\r\n</table>', 'c', 2, 1, '', 0, 1, 0, 1, 1, 1, 1, '')";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (1, 0, 'default_portal', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (2, 0, 'header_width', '180')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (3, 0, 'footer_width', '150')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (4, 3, 'md_recent_topics_style', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (5, 3, 'md_num_recent_topics', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (6, 4, 'md_poll_bar_length', '65')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (7, 4, 'md_poll_forum_id', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (8, 8, 'md_total_poster', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (9, 9, 'md_search_option_text', 'Icy Phoenix')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (10, 11, 'md_cat_id', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (11, 11, 'md_pics_all', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (12, 11, 'md_pics_sort', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (13, 11, 'md_pics_number', '3')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (14, 11, 'md_pics_cols_number', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (15, 11, 'md_pics_rows_number', '3')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (16, 12, 'md_links_style', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (17, 12, 'md_links_own1', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (18, 12, 'md_links_own2', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (19, 12, 'md_links_code', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (20, 14, 'md_wordgraph_words', '250')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_config` (`id`, `bid`, `config_name`, `config_value`) VALUES (21, 14, 'md_wordgraph_count', '1')";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout` (`lid`, `name`, `template`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES (1, '3 Columns', '3_column.tpl', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout` (`lid`, `name`, `template`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES (2, '2 Columns', '2_column.tpl', 0, '', 0, '')";
		/*
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout` (`lid`, `name`, `template`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES (3, 'Central Block', 'central_block.tpl', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout` (`lid`, `name`, `template`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES (4, 'Quad Layout', 'quad_layout.tpl', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout` (`lid`, `name`, `template`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES (5, 'Portal Body', 'portal_body.tpl', 0, '', 0, '')";
		*/

		// Icy Phoenix CMS - END

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('shoutbox_floodinterval', '3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('display_shouts', '20')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('stored_shouts', '1000')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('shout_allow_guest', '0')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "ajax_shoutbox` (
			`shout_id` MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT,
			`user_id` MEDIUMINT(8) NOT NULL,
			`shouter_name` VARCHAR(30) NOT NULL DEFAULT 'guest',
			`shout_text` TEXT NOT NULL,
			`shouter_ip` VARCHAR(40) NOT NULL DEFAULT '',
			`shout_time` INT(11) NOT NULL,
			PRIMARY KEY (shout_id)
		)";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('xmas_gfx', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('logs_path', 'logs')";
		$sql[] = "ALTER TABLE " . $table_prefix . "search_results MODIFY COLUMN search_array MEDIUMTEXT NOT NULL";

		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('upi2db_max_new_posts_admin', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('upi2db_max_new_posts_mod', '0')";

		/* Updating from IP 1.0.5.5 */
		case '1.0.5.5':
		$sql[] = "ALTER TABLE " . $table_prefix . "config CHANGE `config_value` `config_value` TEXT";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('url_rw_guests', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('lofi_bots', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ajax_checks_register', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('inactive_users_memberlists', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('smilie_window_rows', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('invert_nav_arrows', '0');";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_otf_link', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_all_pics_link', '1');";
		$sql[] = "INSERT INTO `" . $table_prefix . "album_config` VALUES ('show_personal_galleries_link', '1');";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('bots_color', '#888888')";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('auth_view_pic_upload', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('enable_postimage_org', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('enable_new_messages_number', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('show_calendar_box_index', '0')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "ajax_shoutbox_sessions` (
			`session_id` int(10) NOT NULL auto_increment,
			`session_user_id` mediumint(8) NOT NULL default '0',
			`session_username` varchar(25) NOT NULL default '',
			`session_ip` varchar(40) NOT NULL default '0',
			`session_start` int(11) NOT NULL default '0',
			`session_time` int(11) NOT NULL default '0',
			PRIMARY KEY (`session_id`)
		)";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ajax_features', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('show_rss_forum_icon', '0')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_nav_menu` (
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
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (1, 1, 0, 0, 0, 0, 0, NULL, 'main_links', 'Main Links', 'Main Links Block', NULL, 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (2, 0, 1, 1, 0, 1, 1, './images/menu/application_view_tile.png', 'main_links', 'Main Links', 'Main Links', '', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (3, 0, 1, 2, 0, 1, 2, './images/menu/newspaper.png', 'news', 'News', 'News', '', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (4, 0, 1, 3, 0, 1, 3, './images/menu/information.png', 'info_links', 'Info', 'Info', '', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (5, 0, 1, 4, 0, 1, 4, './images/menu/group.png', 'users_links', 'Users', 'Users & Groups', '', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (6, 0, 1, 0, 1, 1, 1, '', 'acp', 'ACP', 'ACP', 'adm/index.php', 0, 4, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (7, 0, 1, 0, 1, 1, 2, '', 'cms', 'CMS', 'CMS', 'cms.php', 0, 4, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (8, 0, 1, 0, 1, 1, 3, '', 'home', 'Home', 'Home Page', 'index.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (9, 0, 1, 0, 1, 1, 4, '', 'forum', 'Forum', 'Forum', 'forum.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (10, 0, 1, 0, 1, 1, 5, '', 'rules', 'Rules', 'Rules', 'rules.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (11, 0, 1, 0, 1, 1, 6, '', 'faq', 'FAQ', 'FAQ', 'faq.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (12, 0, 1, 0, 1, 1, 7, '', 'search', 'Search', 'Search', 'search.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (13, 0, 1, 0, 1, 1, 8, '', 'album', 'Album', 'Album', 'album.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (14, 0, 1, 0, 1, 1, 9, '', 'calendar', 'Calendar', 'Calendar', 'calendar.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (15, 0, 1, 0, 1, 1, 10, '', 'downloads', 'Downloads', 'Downloads', 'dload.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (16, 0, 1, 0, 1, 1, 11, '', 'profile', 'Profile', 'Profile', 'profile_main.php', 0, 2, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (17, 0, 1, 0, 1, 1, 12, '', 'ajax_chat', 'Chat', 'Chat', 'ajax_chat.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (19, 0, 1, 0, 1, 1, 14, '', 'kb', 'Knowledge Base', 'Knowledge Base', 'kb.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (20, 0, 1, 0, 1, 1, 15, '', 'contact_us', 'Contact Us', 'Contact Us', 'contact_us.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (22, 0, 1, 0, 2, 1, 1, '', 'news_cat', 'News Categories', 'News Categories', 'index.php?news=categories', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (23, 0, 1, 0, 2, 1, 2, '', 'news_arc', 'News Archives', 'News Archives', 'index.php?news=archives', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (24, 0, 1, 0, 2, 1, 3, '', 'digests', 'Digests', 'Digests', 'digests.php', 0, 2, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (25, 0, 1, 0, 3, 1, 1, '', 'credits', 'Credits', 'Credits', 'credits.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (26, 0, 1, 0, 3, 1, 2, '', 'viewonline', 'Who Is Online', 'Who Is Online', 'viewonline.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (27, 0, 1, 0, 3, 1, 3, '', 'statistics', 'Statistics', 'Statistics', 'statistics.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (28, 0, 1, 0, 4, 1, 1, '', 'memberlist', 'Memberlist', 'Memberlist', 'memberlist.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (29, 0, 1, 0, 4, 1, 2, '', 'usergroups', 'Usergroups', 'Usergroups', 'groupcp.php', 0, 0, 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (30, 0, 1, 0, 4, 1, 3, '', 'staff', 'Staff', 'Staff', 'memberlist.php?mode=staff', 0, 0, 0)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_color` VARCHAR(50) DEFAULT '' NOT NULL AFTER `user_color_group`";

		/* Updating from IP 1.0.10.10 */
		case '1.0.10.10':
		$sql[] = "ALTER TABLE " . $table_prefix . "config CHANGE `config_value` `config_value` TEXT";

		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('global_disable_acronyms', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('global_disable_autolinks', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('global_disable_censor', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('disable_topic_view', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('page_title_simple', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('disable_referers', '0')";

		$sql_tmp = "SELECT * FROM " . $table_prefix . "config_mg";
		$result_tmp = $db->sql_query($sql_tmp);
		while ($row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('" . $row['config_name'] . "', '" . $row['config_value'] . "')";
			$sql[] = "UPDATE " . $table_prefix . "config SET config_value = '" . $row['config_value'] . "' WHERE config_name = '" . $row['config_name'] . "'";
		}
		$db->sql_freeresult($result_tmp);
		$sql[] = "DROP TABLE `" . $table_prefix . "config_mg`";

		/* Updating from IP 1.0.11.11 */
		case '1.0.11.11':
		$older_update = true;
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `cache`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `cache_time`";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'portal_header'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'portal_tail'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'cache_enabled'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'md_cache_file_locking'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'md_cache_write_control'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'md_cache_read_control'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'md_cache_read_type'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'md_cache_filename_protect'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_config` WHERE `config_name` = 'md_cache_serialize'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'portal_header'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'portal_tail'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'cache_enabled'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'md_cache_file_locking'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'md_cache_write_control'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'md_cache_read_control'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'md_cache_read_type'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'md_cache_filename_protect'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE `config_name` = 'md_cache_serialize'";
		$sql[] = "UPDATE " . $table_prefix . "album_config SET config_value = '.0.56' WHERE config_name = 'album_version'";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('allow_only_id2_admin', '0')";

		/* Updating from IP 1.1.0.15 */
		case '1.1.0.15':
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('mg_log_actions', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_disable', 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_display_after_posts', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_post_message', 'You earned %s for that post')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_disable_spam_num', 10)";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_disable_spam_time', 24)";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_disable_spam_message', 'You have exceeded the alloted amount of posts and will not earn anything for your post')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_installed', 'yes')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_version', '2.2.3')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_adminbig', '0')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('cash_adminnavbar', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('points_name', 'Points')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cash` (
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
			PRIMARY KEY (cash_id)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cash_events` (
			event_name varchar(32) NOT NULL default '',
			event_data varchar(255) NOT NULL default '',
			PRIMARY KEY (event_name)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cash_exchange` (
			ex_cash_id1 int(11) NOT NULL default '0',
			ex_cash_id2 int(11) NOT NULL default '0',
			ex_cash_enabled int(1) NOT NULL default '0',
			PRIMARY KEY (ex_cash_id1,ex_cash_id2)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cash_groups` (
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
			PRIMARY KEY (group_id,group_type,cash_id)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cash_log` (
			log_id int(11) NOT NULL auto_increment,
			log_time int(11) NOT NULL default '0',
			log_type smallint(6) NOT NULL default '0',
			log_action varchar(255) NOT NULL default '',
			log_text varchar(255) NOT NULL default '',
			PRIMARY KEY (log_id)
		)";

		//Not needed
		//$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_actmail_last_checked` INT(11) NOT NULL DEFAULT 0 AFTER `user_actkey`";

		$sql[] = "CREATE TABLE `" . $table_prefix . "album_comment_watch` (
			pic_id mediumint(8) UNSIGNED NOT NULL default '0',
			user_id mediumint(8) NOT NULL default '0',
			notify_status tinyint(1) NOT NULL default '0',
			KEY pic_id (pic_id),
			KEY user_id (user_id),
			KEY notify_status (notify_status)
		)";

		$sql[] = "UPDATE `" . $table_prefix . "users` SET user_color_group = '0'";
		$sql[] = "ALTER TABLE " . $table_prefix . "groups ADD COLUMN group_rank mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER group_single_user";
		$sql[] = "ALTER TABLE " . $table_prefix . "groups ADD COLUMN group_color varchar(50) DEFAULT '' NOT NULL AFTER group_rank";
		$sql[] = "ALTER TABLE " . $table_prefix . "groups ADD COLUMN group_legend tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER group_color";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('active_users_color', '#224455')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('active_users_legend', '1')";
		$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('bots_legend', '1')";
		$sql[] = "DROP TABLE `" . $table_prefix . "color_groups`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` DROP `group_color_group`";

		/* Updating from IP 1.1.1.16 */
		case '1.1.1.16':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_social_bookmarks', '0')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "album_cat` ADD `cat_pics` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `cat_wm`;";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `user_session_topic`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "sessions` DROP `session_topic`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_session_page` `user_session_page` varchar(255) NOT NULL default ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "sessions` CHANGE `session_page` `session_page` varchar(255) NOT NULL default ''";

		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_layout_special` (
			`lsid` int(10) unsigned NOT NULL auto_increment,
			`page_id` varchar(100) NOT NULL DEFAULT '',
			`locked` tinyint(1) NOT NULL DEFAULT '1',
			`name` varchar(100) NOT NULL default '',
			`filename` varchar(100) NOT NULL default '',
			`template` varchar(100) NOT NULL default '',
			`global_blocks` tinyint(1) NOT NULL default '0',
			`view` tinyint(1) NOT NULL default '0',
			`groups` TINYTEXT NOT NULL,
			PRIMARY KEY (`lsid`),
			UNIQUE KEY `page_id` (`page_id`)
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout` CHANGE `global_blocks` `global_blocks` tinyint(1) NOT NULL default '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` CHANGE `layout` `layout` int(10) NOT NULL default '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` ADD `layout_special` int(10) NOT NULL default '0' AFTER `layout`";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('forum', 'forum', 'forum.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewf', 'viewf', 'viewforum.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewt', 'viewt', 'viewtopic.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewonline', 'viewonline', 'viewonline.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('search', 'search', 'search.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('profile', 'profile', 'profile.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('memberlist', 'memberlist', 'memberlist.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('group_cp', 'group_cp', 'groupcp.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('faq', 'faq', 'faq.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('rules', 'rules', 'rules.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('download', 'download', 'dload.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('album', 'album', 'album.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('links', 'links', 'links.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('statistics', 'statistics', 'statistics.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('calendar', 'calendar', 'calendar.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('recent', 'recent', 'recent.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('referers', 'referers', 'referers.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('shoutbox', 'shoutbox', 'shoutbox_max.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('kb', 'kb', 'kb.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('contact_us', 'contact_us', 'contact_us.php', 0, '', 0, '')";

		/* Updating from IP 1.1.3.18 */
		case '1.1.3.18':
		$sql[] = "UPDATE `" . $table_prefix . "users` SET `user_session_page` = 'index.php'";
		$sql[] = "UPDATE `" . $table_prefix . "sessions` SET `session_page` = 'index.php'";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_forums_online_users', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('allow_drafts', '1')";
		$sql[] = "CREATE TABLE `" . $table_prefix . "drafts` (
			`draft_id` mediumint(8) UNSIGNED NOT NULL auto_increment,
			`user_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			`topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			`forum_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			`save_time` int(11) UNSIGNED DEFAULT '0' NOT NULL,
			`draft_subject` varchar(100) DEFAULT '' NOT NULL,
			`draft_message` TEXT NOT NULL,
			PRIMARY KEY (`draft_id`),
			KEY `save_time` (`save_time`)
		)";

		/* Updating from IP 1.1.5.20 */
		case '1.1.5.20':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('smilie_single_row', '20')";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'allow_only_id2_admin'";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('main_admin_id', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('allow_mods_edit_admin_posts', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('force_large_caps_mods', '1')";

		// DOWNLOADS - BEGIN
		$sql[] = "CREATE TABLE `" . $table_prefix . "downloads` (
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
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "downloads_cat` (
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
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_auth` (
			cat_id INT(11) NOT NULL,
			group_id INT(11) NOT NULL,
			auth_view TINYINT(1) DEFAULT '1' NOT NULL,
			auth_dl TINYINT(1) DEFAULT '1' NOT NULL,
			auth_up TINYINT(1) DEFAULT '1' NOT NULL,
			auth_mod TINYINT(1) DEFAULT '0' NOT NULL
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_banlist` (
			ban_id INT(11) AUTO_INCREMENT NOT NULL,
			user_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
			user_ip VARCHAR(40) DEFAULT '' NOT NULL,
			user_agent VARCHAR(50) DEFAULT '' NOT NULL,
			username VARCHAR(25) DEFAULT '' NOT NULL,
			guests TINYINT(1) DEFAULT 0 NOT NULL,
		PRIMARY KEY (ban_id)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_bug_tracker` (
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
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_bug_history` (
			report_his_id INT(11) AUTO_INCREMENT NOT NULL,
			df_id INT(11) NOT NULL DEFAULT '0',
			report_id INT(11) NOT NULL,
			report_his_type VARCHAR(10) DEFAULT '',
			report_his_date INT(11) DEFAULT '0',
			report_his_value VARCHAR(255),
		PRIMARY KEY (report_his_id)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_comments` (
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
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_config` (
			config_name VARCHAR(255) NOT NULL DEFAULT '',
			config_value VARCHAR(255) NOT NULL DEFAULT '',
		PRIMARY KEY (config_name)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_ext_blacklist` (
			extention VARCHAR(10) DEFAULT '' NOT NULL
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_favorites` (
			fav_id INT(11) AUTO_INCREMENT NOT NULL,
			fav_dl_id INT(11) DEFAULT 0 NOT NULL,
			fav_dl_cat INT(11) DEFAULT 0 NOT NULL,
			fav_user_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
		PRIMARY KEY (fav_id)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_hotlink` (
			user_id MEDIUMINT(8) DEFAULT 0 NOT NULL,
			session_id VARCHAR(32) DEFAULT '' NOT NULL,
			hotlink_id VARCHAR(32) DEFAULT '' NOT NULL,
			code VARCHAR(5) DEFAULT '' NOT NULL
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_notraf` (
			user_id MEDIUMINT(8) NOT NULL DEFAULT 0,
			dl_id INT(11) NOT NULL DEFAULT 0
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_ratings` (
			dl_id INT(11) DEFAULT '0',
			user_id MEDIUMINT(8) DEFAULT '0',
			rate_point VARCHAR(10) DEFAULT '0'
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_stats` (
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
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD COLUMN group_dl_auto_traffic BIGINT(20) DEFAULT '0' NOT NULL AFTER `group_count_enable`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_allow_new_download_email TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_allow_fav_download_email TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_allow_new_download_popup TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_allow_fav_download_popup TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_dl_update_time INT(11) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_new_download TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_traffic BIGINT(20) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_dl_note_type TINYINT(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_dl_sort_fix TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_dl_sort_opt TINYINT(1) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN user_dl_sort_dir TINYINT(1) DEFAULT '0' NOT NULL";

		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_mod_version', '5.3.0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('delay_auto_traffic', '30')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('delay_post_traffic', '30')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('disable_email', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('disable_popup', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('disable_popup_notify', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_click_reset_time', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_direct', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_edit_time', '3')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_links_per_page', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_method', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_method_quota', '2097152')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_new_time', '3')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_posts', '25')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('dl_stats_perm', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('download_dir', 'downloads/')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('download_vc', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('edit_own_downloads', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('enable_post_dl_traffic', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('guest_stats_show', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('hotlink_action', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('icon_free_for_reg', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('latest_comments', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('limit_desc_on_index', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('newtopic_traffic', '524288')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('overall_traffic', '104857600')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('physical_quota', '524288000')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('prevent_hotlink', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('recent_downloads', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('remain_traffic', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('reply_traffic', '262144')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('report_broken', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('report_broken_lock', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('report_broken_message', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('report_broken_vc', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('shorten_extern_links', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('show_footer_legend', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('show_footer_stat', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('show_real_filetime', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('stop_uploads', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('sort_preform', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('thumb_fsize', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('thumb_xsize', '200')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('thumb_ysize', '150')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('traffic_retime', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('upload_traffic_count', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('use_ext_blacklist', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('use_hacklist', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('user_dl_auto_traffic', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('user_traffic_once', '0')";

		$sql[] = "INSERT INTO `" . $table_prefix . "dl_banlist` (user_agent) VALUES ('n/a')";

		$sql[] = "INSERT INTO `" . $table_prefix . "dl_ext_blacklist` (extention) VALUES
			('asp'), ('cgi'), ('dhtm'), ('dhtml'), ('exe'), ('htm'), ('html'), ('jar'), ('js'), ('php'), ('php3'), ('pl'), ('sh'), ('shtm'), ('shtml')";
		// DOWNLOADS - END

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('enable_colorpicker', '0')";

		/* Updating from IP 1.1.7.22 */
		case '1.1.7.22':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('always_show_edit_by', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_new_reply_posting', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_chat_online', '0')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cash` ADD COLUMN `cash_perthanks` INT(11) DEFAULT '5' NOT NULL AFTER `cash_perreply`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN `user_allow_pm_in` TINYINT(1) DEFAULT '1' NOT NULL AFTER `user_allow_pm`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN `user_allow_mass_email` TINYINT(1) DEFAULT '1' NOT NULL AFTER `user_allow_pm_in`";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('allow_zebra', '1')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "zebra` (
			user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			zebra_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			friend tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
			foe tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY (user_id, zebra_id)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "dl_notraf` (
			user_id MEDIUMINT(8) NOT NULL DEFAULT 0,
			dl_id INT(11) NOT NULL DEFAULT 0
		)";

		/* Updating from IP 1.1.9.24 */
		case '1.1.9.24':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('allow_mods_view_self', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('enable_own_icons', '1')";
		$sql[] = "UPDATE `" . $table_prefix . "ajax_shoutbox` SET shout_text = REPLACE(shout_text, 'http://', 'http:_/_/')";
		$sql[] = "UPDATE `" . $table_prefix . "ajax_shoutbox` SET shout_text = REPLACE(shout_text, 'www.', 'http:_/_/www.')";
		$sql[] = "UPDATE `" . $table_prefix . "ajax_shoutbox` SET shout_text = REPLACE(shout_text, 'http:_/_/http:_/_/', 'http:_/_/')";
		$sql[] = "UPDATE `" . $table_prefix . "users` SET user_allow_pm_in = 1, user_allow_mass_email = 1";

		/* Updating from IP 1.1.10.25 */
		case '1.1.10.25':
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD COLUMN group_legend_order MEDIUMINT(8) DEFAULT '0' NOT NULL AFTER `group_legend`";
		//$sql[] = "ALTER TABLE `" . $table_prefix . "posts` CHANGE `post_edit_count` `post_edit_count` TINYTEXT";
		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` ADD COLUMN `post_edit_id` MEDIUMINT(8) DEFAULT '0' NOT NULL AFTER `post_edit_count`";

		/* Updating from IP 1.2.0.27 */
		case '1.2.0.27':
		case '1.2.0.27c':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_thanks_profile', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_thanks_viewtopic', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('index_top_posters', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('global_disable_upi2db', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('last_user_id', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('write_errors_log', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('write_digests_log', '0')";
		$sql[] = "CREATE TABLE `" . $table_prefix . "attachments_stats` (
			`attach_id` MEDIUMINT(8) unsigned NOT NULL default '0',
			`user_id` MEDIUMINT(8) NOT NULL default '0',
			`user_ip` VARCHAR(40) NOT NULL DEFAULT '',
			`user_browser` VARCHAR(255) NOT NULL DEFAULT '',
			`download_time` INT(11) NOT NULL DEFAULT '0',
			KEY `attach_id` (`attach_id`)
		)";
		$sql[] = "ALTER TABLE `" . $table_prefix . "pa_download_info` ADD COLUMN `download_time` INT(11) DEFAULT '0' NOT NULL AFTER `user_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN `user_download_counter` MEDIUMINT(8) DEFAULT '0' NOT NULL AFTER `user_traffic`";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('user_download_limit_flag', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "dl_config` (config_name, config_value) VALUES ('user_download_limit', '30')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_nav_menu` ADD COLUMN menu_default MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_allow_pm_in` `user_allow_pm_in` TINYINT(1) NOT NULL DEFAULT '1'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_allow_mass_email` `user_allow_mass_email` TINYINT(1) NOT NULL DEFAULT '1'";
		$sql[] = "UPDATE `" . $table_prefix . "users` SET user_allow_pm_in = '1', user_allow_mass_email = '1'";

		/* Updating from IP 1.2.1.28 */
		case '1.2.1.28':
		$older_update = true;
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('no_bump', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('link_this_topic', '0')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout` CHANGE `forum_wide` `global_blocks` TINYINT(1) NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout` ADD `page_nav` TINYINT(1) NOT NULL DEFAULT '1' AFTER `global_blocks`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout` ADD `config_vars` TEXT AFTER `page_nav`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout_special` CHANGE `forum_wide` `global_blocks` TINYINT(1) NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout_special` ADD `page_nav` TINYINT(1) NOT NULL DEFAULT '1' AFTER `global_blocks`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout_special` ADD `config_vars` TEXT AFTER `page_nav`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('link_this_topic', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghtop', 'gt', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghbottom', 'gb', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghleft', 'gl', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('ghright', 'gr', 0)";

		/* Updating from IP 1.2.2.29 */
		case '1.2.2.29':

		/* Updating from IP 1.2.3.30 */
		case '1.2.3.30':
		// Someone may not have this... better check!
		$sql_tmp = "SELECT * FROM " . $table_prefix . "cms_block_variable WHERE config_name = 'default_portal'";
		$result_tmp = $db->sql_query($sql_tmp);
		if (!($row = $db->sql_fetchrow($result_tmp)))
		{
			$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_variable` (`bid`, `label`, `sub_label`, `config_name`, `field_options`, `field_values`, `type`, `block`) VALUES (0, 'Default Portal', 'Default Portal', 'default_portal', '', '', 1, '@Portal Config')";
		}
		$db->sql_freeresult($result_tmp);

		/* Updating from IP 1.2.4.31 */
		case '1.2.4.31':

		/* Updating from IP 1.2.5.32 */
		case '1.2.5.32':

		/* Updating from IP 1.2.6.33 */
		case '1.2.6.33':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_alpha_bar', '0')";

		/* Updating from IP 1.2.7.34 */
		case '1.2.7.34':
		$sql[] = "ALTER TABLE `" . $table_prefix . "album` ADD COLUMN `pic_size` int(15) unsigned default '0' NOT NULL AFTER `pic_filename`";

		/* Updating from IP 1.2.8.35 */
		case '1.2.8.35':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('db_log_actions', '1')";
		$sql[] = "CREATE TABLE `" . $table_prefix . "logs` (
			`log_id` int(11) unsigned NOT NULL auto_increment,
			`log_time` varchar(11) NOT NULL,
			`log_page` varchar(255) NOT NULL default '',
			`log_user_id` int(10) NOT NULL,
			`log_action` varchar(60) NOT NULL default '',
			`log_desc` mediumtext NOT NULL,
			`log_target` int(10) NOT NULL default '0',
			PRIMARY KEY (`log_id`)
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "ajax_shoutbox` DROP `shout_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `block_bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "dl_bug_tracker` DROP `report_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "dl_comments` DROP `bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "downloads` DROP `bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "downloads_cat` DROP `bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "kb_articles` DROP `bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "pa_comments` DROP INDEX `comments_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "pa_comments` DROP INDEX `comment_bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "pa_comments` DROP `comment_bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "privmsgs_text` DROP `privmsgs_bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "shout` DROP `shout_bbcode_uid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `user_sig_bbcode_uid`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` CHANGE `topic_title` `topic_title` VARCHAR(255) NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "privmsgs` CHANGE `privmsgs_subject` `privmsgs_subject` VARCHAR(255) NOT NULL";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('show_topic_description', '0')";

		/* Updating from IP 1.2.9.36 */
		case '1.2.9.36':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN `user_birthday_y` VARCHAR(4) DEFAULT '' NOT NULL AFTER `user_birthday`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN `user_birthday_m` VARCHAR(2) DEFAULT '' NOT NULL AFTER `user_birthday_y`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD COLUMN `user_birthday_d` VARCHAR(2) DEFAULT '' NOT NULL AFTER `user_birthday_m`";
		//$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `user_birthday`";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'allow_only_main_admin_id'";

		/* Updating from IP 1.2.10.37 */
		case '1.2.10.37':
		$sql[] = "CREATE TABLE `" . $table_prefix . "bots` (
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
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo! Slurp', '<b style=\"color:#d22;\">Yahoo!</b><b style=\"color:#24b;\"> Slurp</b>', 'Yahoo! Slurp', '66.106, 68.142, 72.30, 74.6, 202.160.180')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google', '<b style=\"color:#24b;\">G</b><b style=\"color:#d22;\">o</b><b style=\"color:#eb0;\">o</b><b style=\"color:#24b;\">g</b><b style=\"color:#393;\">l</b><b style=\"color:#d22;\">e</b>', 'Googlebot', '66.249')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSN', '<b style=\"color:#468;\">MSN</b>', 'msnbot/', '207.66.146, 207.46, 65.54.188, 65.54.246, 65.54.165, 65.55.210, 65.55.213')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('LiveBot', '<b style=\"color:#468;\">LiveBot</b>', 'LiveBot', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AdsBot [Google]', '', 'AdsBot-Google', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Adsense', '<b style=\"color:#24b;\">G</b><b style=\"color:#d22;\">o</b><b style=\"color:#eb0;\">o</b><b style=\"color:#24b;\">g</b><b style=\"color:#393;\">l</b><b style=\"color:#d22;\">e</b><b style=\"color:#d22;\"> Adsense</b>', 'Mediapartners-Google', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo! DE Slurp', '<b style=\"color:#d22;\">Yahoo!</b><b style=\"color:#24b;\"> DE Slurp</b><b style=\"color:#888;\"> [Bot]</b>', 'Yahoo! DE Slurp', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yahoo MMCrawler', '<b style=\"color:#d22;\">Yahoo!</b><b style=\"color:#24b;\"> MMCrawler</b>', 'Yahoo-MMCrawler/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YahooSeeker', '', 'YahooSeeker/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Desktop', '<b style=\"color:#24b;\">G</b><b style=\"color:#d22;\">o</b><b style=\"color:#eb0;\">o</b><b style=\"color:#24b;\">g</b><b style=\"color:#393;\">l</b><b style=\"color:#d22;\">e</b><b style=\"color:#d22;\"> Desktop</b>', 'Google Desktop', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Google Feedfetcher', '<b style=\"color:#24b;\">G</b><b style=\"color:#d22;\">o</b><b style=\"color:#eb0;\">o</b><b style=\"color:#24b;\">g</b><b style=\"color:#393;\">l</b><b style=\"color:#d22;\">e</b><b style=\"color:#d22;\"> Feedfetcher</b>', 'Feedfetcher-Google', '72.14.199')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSN NewsBlogs', '<b style=\"color:#468;\">MSN NewsBlogs</b>', 'msnbot-NewsBlogs/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MSNbot Media', '<b style=\"color:#468;\">MSNbot Media</b>', 'msnbot-media/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alexa', '', 'ia_archiver', '207.209.238')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Alta Vista', '', 'Scooter/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AllTheWeb', '', 'alltheweb', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Arianna', '', 'www.arianna.it', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves', '', 'Ask Jeeves', '65.214.44')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ask Jeeves', '', 'teoma', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Baidu [Spider]', '', 'Baiduspider', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Become', '', 'BecomeBot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Charlotte', '', 'Charlotte/1.1', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eBay', '', '', '212.222.51')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('eDintorni Crawler', '', 'eDintorni', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Exabot', '', 'Exabot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FAST Enterprise [Crawler]', '', 'FAST Enterprise Crawler', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FAST WebCrawler [Crawler]', '', 'FAST-WebCrawler/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Francis', '', 'http://www.neomo.de/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Gigablast', '', '', '66.154.102, 66.154.103')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Gigabot', '', 'Gigabot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Heise IT-Markt [Crawler]', '', 'heise-IT-Markt-Crawler', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Heritrix [Crawler]', '', 'heritrix/1.', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('JetBot', '', 'Jetbot', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Kosmix', '', 'www.kosmix.com', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IBM Research', '', 'ibm.com/cs/crawler', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ICCrawler - ICjobs', '', 'ICCrawler - ICjobs', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ichiro [Crawler]', '', 'ichiro/2', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('IEAutoDiscovery', '', 'IEAutoDiscovery', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Indy Library', '', 'Indy Library', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Infoseek', '', 'Infoseek', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Inktomi', '', '', '66.94.229, 66.228.165')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('LookSmart', '', 'MARTINI', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Lycos', '', 'Lycos', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('MagpieRSS', '', 'MagpieRSS', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Majestic-12', '', 'MJ12bot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Metager', '', 'MetagerBot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Microsoft Research', '', 'MSRBOT', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('NG-Search', '', 'NG-Search/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Noxtrum [Crawler]', '', 'noxtrumbot', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Nutch', '', 'http://lucene.apache.org/nutch/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Nutch/CVS', '', 'NutchCVS/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Omgili', '', 'omgilibot/0.3', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('OmniExplorer', '', 'OmniExplorer_Bot/', '65.19.150')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Online link [Validator]', '', 'online link validator', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Perl Script', '', 'libwww-perl/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Pompos', '', '', '212.27.41')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('psbot [Picsearch]', '', 'psbot/0', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seekport', '', 'Seekbot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Sensis [Crawler]', '', 'Sensis Web Crawler', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEO Crawler [Crawler]', '', 'SEO search Crawler/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Seoma [Crawler]', '', 'Seoma', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('SEOSearch [Crawler]', '', 'SEOsearch/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snap Bot', '', 'Snapbot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snappy', '', 'Snappy/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Sogou', '', 'www.sogou.com', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Speedy Spider', '', 'Speedy Spider', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Steeler [Crawler]', '', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Synoo', '', 'SynooBot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Telekom', '', 'crawleradmin.t-info@telekom.de', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('TurnitinBot', '', 'TurnitinBot/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Twiceler', '', 'Twiceler', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Virgilio', '', '', '212.48.8')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voyager', '', 'voyager/1.0', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Voila', '', 'VoilaBot', '195.101.94')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3 [Sitesearch]', '', 'W3 SiteSearch Crawler', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Linkcheck]', '', 'W3C-checklink/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('W3C [Validator]', '', 'W3C_', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WiseNut', '', 'http://www.WISEnutbot.com', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YaCy', '', 'yacybot', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yanga WorldSearch', '', 'Yanga WorldSearch Bot', '')";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (config_name, config_value) VALUES ('bots_reg_auth', '0')";

		/* Updating from IP 1.2.11.38 */
		case '1.2.11.38':
		$sql[] = "DROP TABLE `" . $table_prefix . "themes_name`";
		$sql[] = "DROP TABLE " . $table_prefix . "themes";

		$sql[] = "CREATE TABLE `" . $table_prefix . "themes` (
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
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "themes` VALUES (1, 'icy_phoenix', 'Frozen Phoenix', 'style_cyan.css', 'cyan', '', 'row1', 'row2', 'row3', 'row1', 'row2', 'row3')";

		$sql[] = "UPDATE `" . $table_prefix . "users` SET `user_style` = '1'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = '1' WHERE `config_name` = 'default_style'";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_value` = 'default' WHERE `config_name` = 'xs_def_template'";
		$sql[] = "UPDATE `" . $table_prefix . "pa_config` SET `config_value` = 'downloads/' WHERE `config_name` = 'upload_dir'";
		$sql[] = "UPDATE `" . $table_prefix . "pa_config` SET `config_value` = 'files/screenshots/' WHERE `config_name` = 'screenshots_dir'";

		/* Updating from IP 1.2.12.39 */
		case '1.2.12.39':
		$sql_tmp = "SHOW TABLES LIKE '" . $table_prefix . "posts_text'";
		$result_tmp = $db->sql_query($sql_tmp);
		if ($row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "CREATE TABLE `___posts___` (
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
			)";

			// Needed for standard phpBB
			$sql[] = "ALTER TABLE `" . $table_prefix . "posts_text` ADD `post_text_compiled` MEDIUMTEXT NOT NULL AFTER `post_text`";
			$sql[] = "ALTER TABLE `" . $table_prefix . "posts_text` ADD `edit_notes` MEDIUMTEXT NOT NULL AFTER `post_text_compiled`";

			$sql[] = "INSERT INTO `___posts___`
				SELECT p.post_id, p.topic_id, p.forum_id, p.poster_id, p.post_time, p.poster_ip, p.post_username, t.post_subject, t.post_text, t.post_text_compiled, p.enable_bbcode, p.enable_html, p.enable_smilies, p.enable_autolinks_acronyms, p.enable_sig, t.edit_notes, p.post_edit_time, p.post_edit_count, p.post_edit_id, p.post_attachment, p.post_bluecard
				FROM `" . $table_prefix . "posts` p, `" . $table_prefix . "posts_text` t
				WHERE p.post_id = t.post_id
				ORDER BY p.post_id";

			$sql[] = "RENAME TABLE `" . $table_prefix . "posts` TO `_old_" . $table_prefix . "posts`";
			$sql[] = "RENAME TABLE `" . $table_prefix . "posts_text` TO `_old_" . $table_prefix . "posts_text`";
			$sql[] = "RENAME TABLE `___posts___` TO `" . $table_prefix . "posts`";
		}

		$sql_tmp = "SHOW TABLES LIKE '" . $table_prefix . "privmsgs_text'";
		$result_tmp = $db->sql_query($sql_tmp);
		if ($row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "CREATE TABLE `___privmsgs___` (
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
			)";

			$sql[] = "INSERT INTO `___privmsgs___`
				SELECT p.privmsgs_id, p.privmsgs_type, p.privmsgs_subject, t.privmsgs_text, p.privmsgs_from_userid, p.privmsgs_to_userid, p.privmsgs_date, p.privmsgs_ip, p.privmsgs_enable_bbcode, p.privmsgs_enable_html, p.privmsgs_enable_smilies, p.privmsgs_enable_autolinks_acronyms, p.privmsgs_attach_sig, p.privmsgs_attachment
				FROM `" . $table_prefix . "privmsgs` p, `" . $table_prefix . "privmsgs_text` t
				WHERE p.privmsgs_id = t.privmsgs_text_id
				ORDER BY p.privmsgs_id";

			$sql[] = "RENAME TABLE `" . $table_prefix . "privmsgs` TO `_old_" . $table_prefix . "privmsgs`";
			$sql[] = "RENAME TABLE `" . $table_prefix . "privmsgs_text` TO `_old_" . $table_prefix . "privmsgs_text`";
			$sql[] = "RENAME TABLE `___privmsgs___` TO `" . $table_prefix . "privmsgs`";
		}

		$sql[] = "ALTER TABLE `" . $table_prefix . "privmsgs_archive` ADD COLUMN `privmsgs_text` text AFTER `privmsgs_subject`";

		/* Updating from IP 1.2.13.40 */
		case '1.2.13.40':
		$sql_tmp = "SHOW TABLES LIKE '" . $table_prefix . "forums_rules'";
		$result_tmp = $db->sql_query($sql_tmp);
		if (!$row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "CREATE TABLE `___forums___` (
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
				`forum_similar_topics` TINYINT(1) NOT NULL DEFAULT '0',
				`forum_tags` TINYINT(1) NOT NULL DEFAULT '0',
				`forum_sort_box` TINYINT(1) NOT NULL DEFAULT '0',
				`forum_kb_mode` TINYINT(1) NOT NULL DEFAULT '0',
				`forum_index_icons` TINYINT(1) NOT NULL DEFAULT '0',
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
			)";

			$sql[] = "CREATE TABLE `" . $table_prefix . "forums_rules` (
				`forum_id` smallint(5) unsigned NOT NULL default '0',
				`rules` TEXT NOT NULL,
				`rules_display_title` tinyint(1) NOT NULL default '1',
				`rules_custom_title` varchar(80) NOT NULL default '',
				`rules_in_viewforum` tinyint(1) unsigned NOT NULL default '0',
				`rules_in_viewtopic` tinyint(1) unsigned NOT NULL default '0',
				`rules_in_posting` tinyint(1) unsigned NOT NULL default '0',
				PRIMARY KEY (`forum_id`)
			)";

			$sql[] = "INSERT INTO `___forums___`
			SELECT f.forum_id, f.cat_id, f.main_type, f.forum_name, f.forum_desc, f.forum_status, f.forum_order, f.forum_posts, f.forum_topics, f.forum_last_post_id, f.forum_postcount, f.forum_notify, 0, 0, 0, 0, 0, 1, forum_link, f.forum_link_internal, f.forum_link_hit_count, f.forum_link_hit, f.icon, f.prune_next, f.prune_enable, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_globalannounce, f.auth_news, f.auth_cal, f.auth_vote, f.auth_pollcreate, f.auth_attachments, f.auth_download, f.auth_ban, f.auth_greencard, f.auth_bluecard, f.auth_rate
			FROM `" . $table_prefix . "forums` f
			ORDER BY f.forum_id";

			$sql[] = "INSERT INTO `" . $table_prefix . "forums_rules`
			SELECT f.forum_id, f.forum_rules, f.rules_display_title, f.rules_custom_title, f.rules_in_viewforum, f.rules_in_viewtopic, f.rules_in_posting
			FROM `" . $table_prefix . "forums` f
			ORDER BY f.forum_id";

			$sql[] = "RENAME TABLE `" . $table_prefix . "forums` TO `_old_" . $table_prefix . "forums`";
			$sql[] = "RENAME TABLE `___forums___` TO `" . $table_prefix . "forums`";
		}

		$sql[] = "UPDATE " . $table_prefix . "stats_config SET config_value = 'includes/stats_modules' WHERE config_name = 'modules_dir'";

		/* Updating from IP 1.2.14.41 */
		case '1.2.14.41':
		//$sql[] = "DELETE FROM " . $table_prefix . "cms_layout_special WHERE `lsid` = 15";
		$sql[] = "DELETE FROM " . $table_prefix . "cms_nav_menu WHERE `menu_link` = 'site_hist.php'";

		$sql[] = "CREATE TABLE `___categories___` (
			`cat_id` mediumint(8) unsigned NOT NULL auto_increment,
			`cat_main` mediumint(8) unsigned NOT NULL default '0',
			`cat_main_type` char(1) default 'c',
			`cat_title` varchar(100) default NULL,
			`cat_desc` TEXT NOT NULL,
			`icon` varchar(255) default NULL,
			`cat_order` mediumint(8) unsigned NOT NULL default '0',
			PRIMARY KEY (`cat_id`),
			KEY `cat_order` (`cat_order`)
		)";

		$sql[] = "INSERT INTO `___categories___`
			SELECT c.cat_id, c.cat_main, c.cat_main_type, c.cat_title, c.cat_desc, c.icon, c.cat_order
			FROM `" . $table_prefix . "categories` c
			ORDER BY c.cat_id";

		$sql[] = "RENAME TABLE `" . $table_prefix . "categories` TO `_old_" . $table_prefix . "categories`";
		$sql[] = "RENAME TABLE `___categories___` TO `" . $table_prefix . "categories`";

		/* Updating from IP 1.2.15.42 */
		case '1.2.15.42':
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('ScoutJet', '', 'http://www.scoutjet.com/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Yandex', '', 'Yandex/', '')";

		$sql[] = "ALTER TABLE " . $table_prefix . "forums ADD `forum_topic_views` TINYINT(1) NOT NULL DEFAULT '1' AFTER `forum_similar_topics`";

		/* Updating from IP 1.2.16.43 */
		case '1.2.16.43':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_global_switch', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_lock', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_queue_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_queue_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_digests_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_digests_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_files_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_files_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_database_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_database_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_cache_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_cache_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_sql_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_sql_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_users_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_users_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_topics_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_topics_last_run', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_sessions_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_sessions_last_run', '0')";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_db_count', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_db_show_begin_for', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_db_show_not_optimized', '0')";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('rand_seed_last_update', '0')";

		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'db_cron'";

		$sql[] = "DROP TABLE `" . $table_prefix . "optimize_db`";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('gsearch_guests', '0')";

		$sql_tmp = "SHOW TABLES LIKE '_old_" . $table_prefix . "megamail'";
		$result_tmp = $db->sql_query($sql_tmp);
		if (!$row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "CREATE TABLE `___megamail___` (
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
			)";

			$sql[] = "INSERT INTO `___megamail___`
			SELECT m.mail_id, m.mailsession_id, 0, m.user_id, m.group_id, m.email_subject, m.email_body, 0, m.batch_start, m.batch_size, m.batch_wait, m.status
			FROM `" . $table_prefix . "megamail` m
			ORDER BY m.mail_id";

			$sql[] = "RENAME TABLE `" . $table_prefix . "megamail` TO `_old_" . $table_prefix . "megamail`";
			$sql[] = "RENAME TABLE `___megamail___` TO `" . $table_prefix . "megamail`";
		}

		/* Updating from IP 1.2.17.44 */
		case '1.2.17.44':
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Bloglines', '', 'Bloglines/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('DotBot', '', 'dotnetdotcom.org/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('FeedBurner', '', 'FeedBurner/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Feedreader', '', 'Feedreader', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Netvibes', '', 'Netvibes', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('NewsGatorOnline', '', 'NewsGatorOnline/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Snarfer', '', 'Snarfer/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WikioFeedBot', '', 'WikioFeedBot', '')";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_glt', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_glb', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_glh', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_glf', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_fix', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_fit', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_fib', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_vfx', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_vft', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_vfb', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_vtx', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_vtt', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_vtb', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_nmt', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ads_nmb', '0')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "ads` (
			`ad_id` mediumint(8) unsigned NOT NULL auto_increment,
			`ad_title` varchar(255) NOT NULL,
			`ad_text` TEXT NOT NULL,
			`ad_position` varchar(255) NOT NULL,
			`ad_auth` tinyint(1) NOT NULL default '0',
			`ad_format` tinyint(1) NOT NULL default '0',
			`ad_active` tinyint(1) NOT NULL default '0',
			PRIMARY KEY (`ad_id`)
		)";

		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'switch_top_html_block'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'switch_bottom_html_block'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'switch_footer_table'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'switch_header_banner'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'switch_viewtopic_banner'";

		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'top_html_block_text'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'bottom_html_block_text'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'footer_table_text'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'header_banner_text'";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'viewtopic_banner_text'";

		$sql[] = "DELETE FROM " . $table_prefix . "extensions WHERE `extension` = 'tif'";
		$sql[] = "DELETE FROM " . $table_prefix . "extensions WHERE `extension` = 'tga'";

		/* Updating from IP 1.2.18.45 */
		case '1.2.18.45':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('adsense_code', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('google_analytics', '')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "ajax_shoutbox_sessions` CHANGE `session_id` `session_id` INT( 10 ) NOT NULL";
		$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('thumbnail_highslide', '1')";

		/* Updating from IP 1.2.19.46 */
		case '1.2.19.46':

		/* Updating from IP 1.2.20.47 */
		case '1.2.20.47':
		$sql[] = "INSERT INTO " . $table_prefix . "flags (flag_name, flag_image) VALUES ('Montenegro', 'montenegro.png')";

		/* Updating from IP 1.2.21.48 */
		case '1.2.21.48':
		$sql[] = "CREATE TABLE " . $table_prefix . "registration (
			topic_id mediumint(8) unsigned NOT NULL default '0',
			registration_user_id mediumint(8) NOT NULL default '0',
			registration_user_ip varchar(40) NOT NULL default '',
			registration_time int(11) NOT NULL default '0',
			registration_status tinyint(1) NOT NULL default '0',
			KEY topic_id (topic_id),
			KEY registration_user_id (registration_user_id),
			KEY registration_user_ip (registration_user_ip)
		)";

		$sql[] = "CREATE TABLE " . $table_prefix . "registration_desc (
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
			)";

		$sql[] = "ALTER TABLE " . $table_prefix . "topics ADD topic_reg TINYINT(1) DEFAULT '0' NOT NULL AFTER topic_calendar_duration";

		// FIX FOR CMS_ADV
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` ADD `border` TINYINT(1) SET DEFAULT '1' AFTER `border_explain`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` ADD `titlebar` TINYINT(1) SET DEFAULT '1' AFTER `border`";
		$sql[] = "UPDATE `" . $table_prefix . "cms_blocks` SET border= '0' WHERE border_explain= '0,0,0,0'";
		$sql[] = "UPDATE `" . $table_prefix . "cms_blocks` SET border= '1' WHERE border_explain= '1,1,1,1'";
		$sql[] = "UPDATE `" . $table_prefix . "cms_blocks` SET titlebar= '0' WHERE titlebar_explain= '0,0'";
		$sql[] = "UPDATE `" . $table_prefix . "cms_blocks` SET titlebar= '1' WHERE titlebar_explain= '1,1'";

		/* Updating from IP 1.2.22.49 */
		case '1.2.22.49':

		/* Updating from IP 1.2.23.50 */
		case '1.2.23.50':
		$sql[] = "ALTER TABLE " . $table_prefix . "registration_desc DROP `reg_option1`";
		$sql[] = "ALTER TABLE " . $table_prefix . "registration_desc DROP `reg_option2`";
		$sql[] = "ALTER TABLE " . $table_prefix . "registration_desc DROP `reg_option3`";
		$sql[] = "TRUNCATE TABLE `" . $table_prefix . "cms_layout_special`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout_special` ADD `page_id` varchar(100) NOT NULL DEFAULT '' AFTER `lsid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout_special` ADD UNIQUE (`page_id`)";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout_special` ADD `locked` tinyint(1) NOT NULL DEFAULT '1' AFTER `page_id`";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('forum', 'forum', 'forum.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewforum', 'viewforum', 'viewforum.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewtopic', 'viewtopic', 'viewtopic.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('viewonline', 'viewonline', 'viewonline.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('search', 'search', 'search.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('profile', 'profile', 'profile.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('memberlist', 'memberlist', 'memberlist.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('groupcp', 'groupcp', 'groupcp.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('faq', 'faq', 'faq.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('rules', 'rules', 'rules.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('download', 'download', 'dload.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('album', 'album', 'album.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('links', 'links', 'links.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('statistics', 'statistics', 'statistics.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('calendar', 'calendar', 'calendar.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('recent', 'recent', 'recent.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('referers', 'referers', 'referers.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('shoutbox', 'shoutbox', 'shoutbox_max.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('kb', 'kb', 'kb.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('contact_us', 'contact_us', 'contact_us.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('attachments', 'attachments', 'attachments.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('ranks', 'ranks', 'ranks.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('ajax_chat', 'ajax_chat', 'ajax_chat.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('ajax_chat_archive', 'ajax_chat_archive', 'ajax_chat.php', 0, '', 0, '')";
		//$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('pic_upload', 'pic_upload', 'upload.php', 0, '', 0, '')";

		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name LIKE \"auth_view_%\" AND config_name <> 'auth_view_pic_upload'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name LIKE \"wide_blocks_%\"";

		/* Updating from IP 1.2.24.51 */
		case '1.2.24.51':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('read_only_forum', '0')";

		/* Updating from IP 1.2.25.52 */
		case '1.2.25.52':

		/* Updating from IP 1.3.0.53 */
		case '1.3.0.53':
		case '1.3.0.53a':
		case '1.3.0.53b':
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('gheader', 'gh', 0)";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_position` (`pkey`, `bposition`, `layout`) VALUES ('gfooter', 'gf', 0)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "tickets_cat` (
				ticket_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
				ticket_cat_title VARCHAR(255) NOT NULL DEFAULT '',
				ticket_cat_des TEXT NOT NULL,
				ticket_cat_emails TEXT NOT NULL,
				PRIMARY KEY (ticket_cat_id)
			)";
		$sql[] = "INSERT INTO `" . $table_prefix . "tickets_cat` (ticket_cat_title, ticket_cat_des, ticket_cat_emails) VALUES ('General', 'General', '')";

		$sql[] = "UPDATE `" . $table_prefix . "cms_layout_special` SET page_id = 'viewforum' WHERE page_id = 'viewfforum'";

		/* Updating from IP 1.3.1.54 */
		case '1.3.1.54':
		define('FIX_FORUMS', true);
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('forum_limit_edit_time_interval', '1440')";

		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` CHANGE `cat_id` `parent_id` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "categories` CHANGE `cat_title` `cat_title` varchar(255) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` CHANGE `forum_name` `forum_name` varchar(255) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` CHANGE `group_color` `group_color` VARCHAR(16) NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `username` `username` varchar(36) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_color` `user_color` VARCHAR(16) NOT NULL";

		$sql[] = "ALTER TABLE `" . $table_prefix . "categories` ADD `cat_title_clean` varchar(255) DEFAULT '' NOT NULL AFTER `cat_title`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_type` tinyint(4) DEFAULT '0' NOT NULL AFTER `forum_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_parents` MEDIUMTEXT NOT NULL AFTER `main_type`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `right_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `main_type`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `left_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `main_type`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_name_clean` varchar(255) DEFAULT '' NOT NULL AFTER `forum_name`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_limit_edit_time` tinyint(1) default '0' NOT NULL AFTER `forum_notify`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_last_topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `forum_topics`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_last_poster_color` varchar(16) DEFAULT '' NOT NULL AFTER `forum_last_post_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_last_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `forum_last_post_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_last_post_time` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `forum_last_post_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_last_post_subject` varchar(255) DEFAULT '' NOT NULL AFTER `forum_last_post_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_last_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `forum_last_post_id`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_tags` varchar(255) DEFAULT '' NOT NULL AFTER `topic_title`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_similar_topics` varchar(255) DEFAULT '' NOT NULL AFTER `topic_desc`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_title_clean` varchar(255) DEFAULT '' NOT NULL AFTER `topic_title`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_ftitle_clean` varchar(255) DEFAULT '' NOT NULL AFTER `topic_title_clean`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_first_post_time` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_first_post_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_first_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_first_post_time`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_first_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `topic_first_poster_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_first_poster_color` varchar(16) DEFAULT '' NOT NULL AFTER `topic_first_poster_name`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_last_post_time` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_last_post_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_last_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `topic_last_post_time`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_last_poster_name` varchar(255) DEFAULT '' NOT NULL AFTER `topic_last_post_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_last_poster_color` varchar(16) DEFAULT '' NOT NULL AFTER `topic_last_poster_name`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `username_clean` varchar(255) DEFAULT '' NOT NULL AFTER `username`";

		$sql[] = "UPDATE `" . $table_prefix . "forums` SET forum_type = 1";

		$sql[] = "UPDATE `" . $table_prefix . "forums` f, `" . $table_prefix . "topics` t, `" . $table_prefix . "posts` p, `" . $table_prefix . "users` u SET f.forum_last_topic_id = p.topic_id, f.forum_last_poster_id = p.poster_id, f.forum_last_post_subject = t.topic_title, f.forum_last_post_time = p.post_time, f.forum_last_poster_name = u.username, f.forum_last_poster_color = u.user_color
		WHERE f.forum_last_post_id = p.post_id
		AND t.topic_id = p.topic_id
		AND p.poster_id = u.user_id";

		$sql[] = "UPDATE `" . $table_prefix . "topics` t, `" . $table_prefix . "posts` p, `" . $table_prefix . "posts` p2, `" . $table_prefix . "users` u, `" . $table_prefix . "users` u2 SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
		WHERE t.topic_first_post_id = p.post_id
		AND p.poster_id = u.user_id
		AND t.topic_last_post_id = p2.post_id
		AND p2.poster_id = u2.user_id";

		/*
		$sql = "UPDATE " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . USERS_TABLE . " u
						SET f.forum_last_poster_id = p.poster_id, f.forum_last_post_subject = t.topic_title, f.forum_last_post_time = p.post_time, f.forum_last_poster_name = u.username, f.forum_last_poster_color = u.user_color
						WHERE f.forum_last_post_id = p.post_id
						AND t.topic_id = p.topic_id
						AND p.poster_id = u.user_id";

		$sql = "UPDATE " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u, " . USERS_TABLE . " u2
						SET t.topic_first_post_id = p.post_id, t.topic_first_post_time = p.post_time, t.topic_first_poster_id = p.poster_id, t.topic_first_poster_name = u.username, t.topic_first_poster_color = u.user_color, t.topic_last_post_id = p2.post_id, t.topic_last_post_time = p2.post_time, t.topic_last_poster_id = p2.poster_id, t.topic_last_poster_name = u2.username, t.topic_last_poster_color = u2.user_color
						WHERE t.topic_first_post_id = p.post_id
						AND p.poster_id = u.user_id
						AND t.topic_last_post_id = p2.post_id
						AND p2.poster_id = u2.user_id";
		*/

		$sql[] = "CREATE TABLE `" . $table_prefix . "topics_tags_list` (
			`tag_text` varchar(50) binary NOT NULL DEFAULT '',
			`tag_id` mediumint(8) unsigned NOT NULL auto_increment,
			`tag_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`tag_text`),
			KEY `tag_id` (`tag_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "topics_tags_match` (
			`tag_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			KEY `tag_id` (`tag_id`),
			KEY `topic_id` (`topic_id`)
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "config`
		SELECT x.config_name, x.config_value
		FROM `" . $table_prefix . "xs_news_cfg` x";
		$sql[] = "RENAME TABLE `" . $table_prefix . "xs_news_cfg` TO `_old_" . $table_prefix . "xs_news_cfg`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "ina_scores` ADD `user_plays` int(6) default '0' AFTER `score`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "ina_scores` ADD `play_time` int(11) default '0' AFTER `user_plays`";

		$sql[] = "UPDATE `" . $table_prefix . "cms_blocks` SET blockfile = REPLACE(blockfile,'blocks_imp_','')";

		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'smart_header'";

		/* Updating from IP 1.3.2.55 */
		case '1.3.2.55':
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'disable_ftr'";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ftr_topic_number', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ftr_message', 'Before going on... please make sure you have read and understood this post. It contains important informations regarding this site.')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ftr_install_time', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ftr_disable', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ftr_all_users', '0')";

		$sql[] = "DROP TABLE `" . $table_prefix . "force_read`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "force_read_users` DROP `read`";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('allow_html_only_for_admins', '0')";

		/* Updating from IP 1.3.3.56 */
		case '1.3.3.56':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('display_tags_box', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('allow_moderators_edit_tags', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('enable_custom_bbcodes', '0')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "bbcodes` (
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
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "ajax_shoutbox` ADD `shout_room` VARCHAR(255) NOT NULL DEFAULT ''";

		$sql[] = "CREATE TABLE `" . $table_prefix . "plugins` (
			plugin_name VARCHAR(255) NOT NULL DEFAULT '',
			plugin_dir VARCHAR(255) NOT NULL DEFAULT '',
			plugin_enabled tinyint(2) NOT NULL DEFAULT 0,
			PRIMARY KEY (plugin_name)
		)";

		/* Updating from IP 1.3.4.57 */
		case '1.3.4.57':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('forum_tags_type', '0')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_private_chat_alert` VARCHAR(255) NOT NULL AFTER `user_last_privmsg`";

		/* Updating from IP 1.3.5.58 */
		case '1.3.5.58':
		$sql[] = "CREATE TABLE `" . $table_prefix . "plugins_config` (
			`config_name` varchar(255) NOT NULL DEFAULT '',
			`config_value` TEXT NOT NULL,
			PRIMARY KEY (`config_name`)
		)";

		/* Updating from IP 1.3.6.59 */
		case '1.3.6.59':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_login_tries` `user_login_attempts` TINYINT(4) DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_last_login_try` `user_last_login_attempt` INT(11) NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_password` `user_password` VARCHAR(40) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_newpasswd` `user_newpasswd` VARCHAR(40) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_passchg` INT(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_password`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_pass_convert` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_passchg`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_form_salt` VARCHAR(32) DEFAULT '' NOT NULL AFTER `user_pass_convert`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_email_hash` BIGINT(20) DEFAULT '0' NOT NULL AFTER `user_email`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_options` INT(11) UNSIGNED DEFAULT '895' NOT NULL AFTER `user_setbm`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_last_pw_reset`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_last_pw_change`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_login_count`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_login_vconfirm`";

		$sql_tmp = "SELECT * FROM " . $table_prefix . "ctracker_config";
		$result_tmp = $db->sql_query($sql_tmp);
		while ($row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('ctracker_" . $row['ct_config_name'] . "', '" . $row['ct_config_value'] . "')";
			$sql[] = "UPDATE " . $table_prefix . "config SET config_value = '" . $row['ct_config_value'] . "' WHERE config_name = 'ctracker_" . $row['ct_config_name'] . "'";
		}
		$db->sql_freeresult($result_tmp);
		$sql[] = "DROP TABLE `" . $table_prefix . "ctracker_config`";

		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'ctracker_detect_misconfiguration'";

		$sql_tmp = "SELECT * FROM " . $table_prefix . "attachments_config";
		$result_tmp = $db->sql_query($sql_tmp);
		while ($row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "INSERT INTO " . $table_prefix . "config (config_name, config_value) VALUES ('" . $row['config_name'] . "', '" . $row['config_value'] . "')";
			$sql[] = "UPDATE " . $table_prefix . "config SET config_value = '" . $row['config_value'] . "' WHERE config_name = '" . $row['config_name'] . "'";
		}
		$db->sql_freeresult($result_tmp);
		$sql[] = "DROP TABLE `" . $table_prefix . "attachments_config`";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_site_history_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_site_history_last_run', '0')";

		// New AUTH System - BEGIN
		$sql[] = "CREATE TABLE `" . $table_prefix . "acl_groups` (
			`group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_setting` tinyint(2) NOT NULL DEFAULT '0',
			KEY `group_id` (`group_id`),
			KEY `auth_opt_id` (`auth_option_id`),
			KEY `auth_role_id` (`auth_role_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "acl_options` (
			`auth_option_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			`auth_option` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
			`is_global` tinyint(1) unsigned NOT NULL DEFAULT '0',
			`is_local` tinyint(1) unsigned NOT NULL DEFAULT '0',
			`founder_only` tinyint(1) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`auth_option_id`),
			UNIQUE KEY `auth_option` (`auth_option`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "acl_roles` (
			`role_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			`role_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
			`role_description` text COLLATE utf8_bin NOT NULL,
			`role_type` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
			`role_order` smallint(4) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`role_id`),
			KEY `role_type` (`role_type`),
			KEY `role_order` (`role_order`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "acl_roles_data` (
			`role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_setting` tinyint(2) NOT NULL DEFAULT '0',
			PRIMARY KEY (`role_id`,`auth_option_id`),
			KEY `ath_op_id` (`auth_option_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "acl_users` (
			`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_option_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_role_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`auth_setting` tinyint(2) NOT NULL DEFAULT '0',
			KEY `user_id` (`user_id`),
			KEY `auth_option_id` (`auth_option_id`),
			KEY `auth_role_id` (`auth_role_id`)
		)";
		// New AUTH System - END

		/* Updating from IP 1.3.7.60 */
		case '1.3.7.60':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `ct_last_mail`";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_width', '316')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_height', '61')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_background_color', '#E5ECF9')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_jpeg', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_jpeg_quality', '50')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_pre_letters', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_pre_letters_great', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_font', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_chess', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_ellipses', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_arcs', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_lines', '2')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_image', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_gammacorrect', '1.4')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_foreground_lattice_x', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_foreground_lattice_y', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('captcha_lattice_color', '#FFFFFF')";

		$sql[] = "DROP TABLE `" . $table_prefix . "captcha_config`";

		$sql[] = "TRUNCATE TABLE `" . $table_prefix . "hacks_list`";

		$sql[] = "UPDATE `" . $table_prefix . "users` SET `user_pass_convert` = '1'";

		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'thumbnail_lightbox'";
		$sql[] = "DELETE FROM `" . $table_prefix . "album_config` WHERE config_name = 'enable_mooshow'";

		/* Updating from IP 1.3.8.61 */
		case '1.3.8.61':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('smtp_port', '25')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "plugins` ADD `plugin_version` VARCHAR(255) NOT NULL DEFAULT '' AFTER `plugin_name`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cms_version', '2.0.0')";

		// NEW CMS - BEGIN
		$sql[] = "CREATE TABLE `" . $table_prefix . "cms_block_settings` (
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
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "cms_block_settings`
		SELECT b.bid, 0, b.title, b.content, b.blockfile, b.view, b.type, b.groups, 1
		FROM `" . $table_prefix . "cms_blocks` b
		ORDER BY b.bid";

		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `content`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `blockfile`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `view`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `type`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `groups`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` ADD `bs_id` int(10) UNSIGNED NOT NULL AFTER `bid`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` ADD `block_cms_id` int(10) UNSIGNED NOT NULL AFTER `bs_id`";

		$sql[] = "UPDATE `" . $table_prefix . "cms_blocks` SET `bs_id` = `bid`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout` ADD `layout_cms_id` int(10) UNSIGNED NOT NULL AFTER `template`";
		// NEW CMS - END

		/* Updating from IP 1.3.9.62 */
		case '1.3.9.62':
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics_watch` ADD `forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `topic_id`";
		$sql[] = "UPDATE `" . $table_prefix . "topics_watch` tw, `" . $table_prefix . "topics` t SET tw.forum_id = t.forum_id WHERE tw.topic_id = t.topic_id";

		/* Updating from IP 1.3.10.63 */
		case '1.3.10.63':
		$sql[] = "DROP TABLE `" . $table_prefix . "confirm`";

		$sql[] = "CREATE TABLE `" . $table_prefix . "confirm` (
			confirm_id char(32) DEFAULT '' NOT NULL,
			session_id char(32) DEFAULT '' NOT NULL,
			confirm_type tinyint(3) DEFAULT '0' NOT NULL,
			code varchar(8) DEFAULT '' NOT NULL,
			seed int(10) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY (session_id, confirm_id),
			KEY confirm_type (confirm_type)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "poll_options` (
			poll_option_id tinyint(4) DEFAULT '0' NOT NULL,
			topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			poll_option_text text NOT NULL,
			poll_option_total mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			KEY poll_opt_id (poll_option_id),
			KEY topic_id (topic_id)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "poll_votes` (
			topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			poll_option_id tinyint(4) DEFAULT '0' NOT NULL,
			vote_user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			vote_user_ip varchar(40) DEFAULT '' NOT NULL,
			KEY topic_id (topic_id),
			KEY vote_user_id (vote_user_id),
			KEY vote_user_ip (vote_user_ip)
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `poll_title` varchar(255) DEFAULT '' NOT NULL AFTER `topic_type`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `poll_start` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_title`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `poll_length` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_start`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `poll_max_options` tinyint(4) DEFAULT '1' NOT NULL AFTER `poll_length`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `poll_last_vote` int(11) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_max_options`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `poll_vote_change` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `poll_last_vote`";

		$sql[] = "UPDATE " . $table_prefix . "topics t, " . $table_prefix . "vote_desc vd
			SET t.poll_title = vd.vote_text, t.poll_start = vd.vote_start, t.poll_length = vd.vote_length, t.poll_max_options = 1, t.poll_vote_change = 0
			WHERE t.topic_vote = 1
			AND vd.topic_id = t.topic_id";

		$sql[] = "INSERT INTO `" . $table_prefix . "poll_options`
			SELECT vr.vote_option_id, vd.topic_id, vr.vote_option_text, vr.vote_result
			FROM `" . $table_prefix . "vote_desc` vd, `" . $table_prefix . "vote_results` vr
			WHERE vr.vote_id = vd.vote_id
			ORDER BY vd.topic_id ASC, vd.vote_id ASC, vr.vote_option_id ASC";

		$sql[] = "INSERT INTO `" . $table_prefix . "poll_votes`
			SELECT vd.topic_id, vv.vote_cast, vv.vote_user_id, vv.vote_user_ip
			FROM `" . $table_prefix . "vote_desc` vd, `" . $table_prefix . "vote_voters` vv
			WHERE vd.vote_id = vv.vote_id
			ORDER BY vd.topic_id ASC, vv.vote_user_id ASC";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` DROP `topic_vote`";
		$sql[] = "DROP TABLE `" . $table_prefix . "vote_desc`";
		$sql[] = "DROP TABLE `" . $table_prefix . "vote_results`";
		$sql[] = "DROP TABLE `" . $table_prefix . "vote_voters`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_jabber` varchar(255) DEFAULT '' NOT NULL AFTER `user_icq`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_aim` `user_aim` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_icq` `user_icq` varchar(15) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_msnm` `user_msnm` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_skype` `user_skype` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_yim` `user_yim` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_interests` `user_interests` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_occ` `user_occ` varchar(255) DEFAULT '' NOT NULL";

		$sql[] = "CREATE TABLE `" . $table_prefix . "posts_likes` (
			topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			like_time  int(11) UNSIGNED DEFAULT '0' NOT NULL,
			KEY topic_id (topic_id),
			KEY post_id (post_id),
			KEY user_id (user_id)
		)";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('disable_likes_posts', '1')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_likes` tinyint(1) NOT NULL DEFAULT '0' AFTER `forum_postcount`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` ADD `post_likes` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `post_bluecard`";

		// Still not sure which one of this code will do the trick... anyway it's not really important... :-)
		/*
		$sql[] = "UPDATE `" . $table_prefix . "cms_block_settings` SET `content` = REPLACE(`content`, '\\\"', '\"')";
		$sql[] = "UPDATE `" . $table_prefix . "cms_block_settings` SET `content` = REPLACE(`content`, \"\'\", \"'\")";
		*/
		$sql[] = "UPDATE `" . $table_prefix . "cms_block_settings` SET `content` = REPLACE(`content`, '\\\\\"', '\\\"')";
		$sql[] = "UPDATE `" . $table_prefix . "cms_block_settings` SET `content` = REPLACE(`content`, \"\\'\", \"\'\")";

		/* Updating from IP 1.3.11.64 */
		case '1.3.11.64':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_first_name` varchar(255) DEFAULT '' NOT NULL AFTER `username_clean`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_last_name` varchar(255) DEFAULT '' NOT NULL AFTER `user_first_name`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_facebook` varchar(255) DEFAULT '' NOT NULL AFTER `user_yim`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_twitter` varchar(255) DEFAULT '' NOT NULL AFTER `user_facebook`";

		/* Updating from IP 1.3.12.65 */
		case '1.3.12.65':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_type` tinyint(2) DEFAULT '0' NOT NULL AFTER `user_regdate`";
		$page_framework->convert_usernames();
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_mask` tinyint(1) DEFAULT '0' NOT NULL AFTER `user_active`";

		/* Updating from IP 1.3.13.66 */
		case '1.3.13.66':
		$sql[] = "ALTER TABLE `" . $table_prefix . "ranks` ADD `rank_show_title` tinyint(1) DEFAULT '1' NOT NULL AFTER `rank_special`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ip_admins_only', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('attachments_stats', '0')";

		/* Updating from IP 1.3.14.67 */
		case '1.3.14.67':
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` CHANGE `block_settings_id` `bs_id` INT(10) UNSIGNED NOT NULL";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_lock_hour', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_birthdays_interval', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cron_birthdays_last_run', '0')";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'enable_digests'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'digests_php_cron'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'digests_php_cron_lock'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'digests_last_send_time'";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('robots_index_topics_no_replies', '1')";
		$sql[] = "ALTER TABLE `" . $table_prefix . "rate_results` CHANGE `rating_time` `rating_time` int(11) NOT NULL DEFAULT '0'";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_permissions` mediumtext NOT NULL AFTER `user_mask`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_perm_from` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_permissions`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_http_agents` `user_browser` varchar(255) DEFAULT '' NOT NULL";

		$sql[] = "ALTER TABLE `" . $table_prefix . "attachments_stats` CHANGE `user_http_agents` `user_browser` varchar(255) DEFAULT '' NOT NULL";

		$sql_tmp = "SHOW TABLES LIKE '_old_" . $table_prefix . "sessions'";
		$result_tmp = $db->sql_query($sql_tmp);
		if (!$row = $db->sql_fetchrow($result_tmp))
		{
			$sql[] = "CREATE TABLE `___sessions___` (
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
			)";

			$sql[] = "INSERT INTO `___sessions___`
			SELECT s.session_id, s.session_user_id, s.session_start, s.session_time, s.session_ip, s.session_user_agent, s.session_page, s.session_logged_in, 0, 0, " . time() . ", '', 1, 0, s.session_admin
			FROM `" . $table_prefix . "sessions` s
			ORDER BY s.session_id";

			$sql[] = "RENAME TABLE `" . $table_prefix . "sessions` TO `_old_" . $table_prefix . "sessions`";
			$sql[] = "RENAME TABLE `___sessions___` TO `" . $table_prefix . "sessions`";
		}

		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` CHANGE `group_name` `group_name` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` CHANGE `group_description` `group_description` text NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_founder_manage` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_type`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_display` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_description`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_sig_chars` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_legend_order`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_receive_pm` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_sig_chars`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_message_limit` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_receive_pm`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_max_recipients` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_message_limit`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD `group_skip_auth` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `group_max_recipients`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "groups` ADD INDEX `group_legend_name` (`group_legend`, `group_name`)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "user_group` CHANGE `user_pending` `user_pending` tinyint(1) DEFAULT '1' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "user_group` ADD `group_leader` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "user_group` ADD INDEX `group_leader` (`group_leader`)";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('limit_load', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('limit_search_load', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ip_check', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('browser_check', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('referer_validation', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('force_server_vars', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('session_last_gc', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('active_sessions', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('form_token_lifetime', '7200')";

		$sql[] = "CREATE TABLE `" . $table_prefix . "log` (
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
		)";

		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` CHANGE `ban_time` `ban_start` int(11) DEFAULT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` CHANGE `ban_expire_time` `ban_end` int(11) DEFAULT NULL";

		$sql[] = "ALTER TABLE `" . $table_prefix . "ajax_shoutbox` CHANGE `shouter_ip` `shouter_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "ajax_shoutbox_sessions` CHANGE `session_ip` `session_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "album_comment` CHANGE `comment_user_ip` `comment_user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "album_rate` CHANGE `rate_user_ip` `rate_user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "attachments_stats` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "banlist` CHANGE `ban_ip` `ban_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "ctracker_loginhistory` CHANGE `ct_login_ip` `ct_login_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "dl_banlist` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "dl_stats` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "kb_votes` CHANGE `votes_ip` `votes_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "links` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "links` CHANGE `last_user_ip` `last_user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "logins` CHANGE `login_ip` `login_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "pa_download_info` CHANGE `downloader_ip` `downloader_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "pa_files` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "pa_votes` CHANGE `votes_ip` `votes_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "poll_votes` CHANGE `vote_user_ip` `vote_user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "privmsgs` CHANGE `privmsgs_ip` `privmsgs_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "privmsgs_archive` CHANGE `privmsgs_ip` `privmsgs_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "rate_results` CHANGE `user_ip` `user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "referers` CHANGE `ip` `ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "registration` CHANGE `registration_user_ip` `registration_user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "sessions` CHANGE `session_ip` `session_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "sessions_keys` CHANGE `last_ip` `last_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "shout` CHANGE `shout_ip` `shout_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `ct_last_used_ip` `ct_last_used_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `ct_last_ip` `ct_last_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_registered_ip` `user_registered_ip` varchar(40) NOT NULL DEFAULT ''";

		$sql[] = "ALTER TABLE `" . $table_prefix . "blogs_posts` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "ALTER TABLE `" . $table_prefix . "guestbooks_posts` CHANGE `poster_ip` `poster_ip` varchar(40) NOT NULL DEFAULT ''";

		$sql[] = "UPDATE `" . $table_prefix . "ajax_shoutbox` ip SET ip.shouter_ip = INET_NTOA(CONV(ip.shouter_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "ajax_shoutbox_sessions` ip SET ip.session_ip = INET_NTOA(CONV(ip.session_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "album_comment` ip SET ip.comment_user_ip = INET_NTOA(CONV(ip.comment_user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "album_rate` ip SET ip.rate_user_ip = INET_NTOA(CONV(ip.rate_user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "attachments_stats` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "banlist` ip SET ip.ban_ip = INET_NTOA(CONV(ip.ban_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "dl_banlist` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "dl_stats` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "kb_votes` ip SET ip.votes_ip = INET_NTOA(CONV(ip.votes_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "links` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "links` ip SET ip.last_user_ip = INET_NTOA(CONV(ip.last_user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "logins` ip SET ip.login_ip = INET_NTOA(CONV(ip.login_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "pa_download_info` ip SET ip.downloader_ip = INET_NTOA(CONV(ip.downloader_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "pa_files` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "pa_votes` ip SET ip.votes_ip = INET_NTOA(CONV(ip.votes_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "poll_votes` ip SET ip.vote_user_ip = INET_NTOA(CONV(ip.vote_user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "posts` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "privmsgs` ip SET ip.privmsgs_ip = INET_NTOA(CONV(ip.privmsgs_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "privmsgs_archive` ip SET ip.privmsgs_ip = INET_NTOA(CONV(ip.privmsgs_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "rate_results` ip SET ip.user_ip = INET_NTOA(CONV(ip.user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "referers` ip SET ip.ip = INET_NTOA(CONV(ip.ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "registration` ip SET ip.registration_user_ip = INET_NTOA(CONV(ip.registration_user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "sessions` ip SET ip.session_ip = INET_NTOA(CONV(ip.session_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "sessions_keys` ip SET ip.last_ip = INET_NTOA(CONV(ip.last_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "shout` ip SET ip.shout_ip = INET_NTOA(CONV(ip.shout_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "users` ip SET ip.user_registered_ip = INET_NTOA(CONV(ip.user_registered_ip, 16, 10))";

		$sql[] = "UPDATE `" . $table_prefix . "blogs_posts` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "guestbooks_posts` ip SET ip.poster_ip = INET_NTOA(CONV(ip.poster_ip, 16, 10))";

		/* Updating from IP 1.3.15.68 */
		case '1.3.15.68':
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Bing', '<b style=\"color:#468;\">Bing</b>', 'bingbot/', '')";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_post_sortby_dir` varchar(1) DEFAULT 'a' NOT NULL AFTER `user_posts_per_page`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_post_sortby_type` varchar(1) DEFAULT 't' NOT NULL AFTER `user_posts_per_page`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_post_show_days` smallint(4) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_posts_per_page`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_topic_sortby_dir` varchar(1) DEFAULT 'd' NOT NULL AFTER `user_posts_per_page`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_topic_sortby_type` varchar(1) DEFAULT 't' NOT NULL AFTER `user_posts_per_page`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_topic_show_days` smallint(4) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_posts_per_page`";

		/* Updating from IP 1.3.16.69 */
		case '1.3.16.69':
		$sql[] = "CREATE TABLE `" . $table_prefix . "moderator_cache` (
			`forum_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			`user_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			`username` varchar(255) DEFAULT '' NOT NULL,
			`group_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			`group_name` varchar(255) DEFAULT '' NOT NULL,
			`display_on_index` tinyint(1) UNSIGNED DEFAULT '1' NOT NULL,
			KEY `disp_idx` (`display_on_index`),
			KEY `forum_id` (`forum_id`)
		)";

		$sql[] = "CREATE TABLE `" . $table_prefix . "modules` (
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
		)";

		$sql[] = "UPDATE " . $table_prefix . "cms_blocks SET active = 0 WHERE bposition IN ('hh', 'hl', 'hc', 'fc', 'fr', 'ff')";

		/* Updating from IP 1.3.17.70 */
		case '1.3.17.70':
		$sql[] = "ALTER TABLE `" . $table_prefix . "logs` CHANGE `log_desc` `log_desc` mediumtext NOT NULL";

		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` CHANGE `forum_rules` `forum_rules_switch` tinyint(1) unsigned NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_rules_in_posting` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `forum_rules_switch`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_rules_in_viewtopic` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `forum_rules_switch`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_rules_in_viewforum` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `forum_rules_switch`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_rules_custom_title` varchar(80) NOT NULL DEFAULT '' AFTER `forum_rules_switch`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_rules_display_title` tinyint(1) NOT NULL DEFAULT '1' AFTER `forum_rules_switch`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_rules` text NOT NULL AFTER `forum_rules_switch`";

		$sql[] = "UPDATE " . $table_prefix . "forums f, " . $table_prefix . "forums_rules fr
		SET f.forum_rules = fr.rules, f.forum_rules_display_title = fr.rules_display_title, f.forum_rules_custom_title = fr.rules_custom_title, f.forum_rules_in_viewforum = fr.rules_in_viewforum, f.forum_rules_in_viewtopic = fr.rules_in_viewtopic, f.forum_rules_in_posting = fr.rules_in_posting
		WHERE f.forum_id = fr.forum_id";

		$sql[] = "DROP TABLE `" . $table_prefix . "forums_rules`";

		/* Updating from IP 1.3.18.71 */
		case '1.3.18.71':
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'cms_dock'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE config_name = 'cms_style'";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_flickr` varchar(255) DEFAULT '' NOT NULL AFTER `user_twitter`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_googleplus` varchar(255) DEFAULT '' NOT NULL AFTER `user_flickr`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_youtube` varchar(255) DEFAULT '' NOT NULL AFTER `user_googleplus`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_linkedin` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`";

		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_style` `user_style` MEDIUMINT(8) NULL DEFAULT NULL";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_keywords', 'your keywords, comma, separated')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_keywords_switch', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_description', 'Your Site Description')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_description_switch', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_author', 'Author')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_author_switch', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_copyright', 'Copyright')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('site_meta_copyright_switch', '1')";

		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` CHANGE `post_text` `post_text` MEDIUMTEXT NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` CHANGE `post_text_compiled` `post_text_compiled` MEDIUMTEXT NOT NULL";

		/* Updating from IP 1.3.19.72 */
		case '1.3.19.72':

		/* Updating from IP 1.3.20.73 */
		case '1.3.20.73':

		/* Updating from IP 1.3.21.74 */
		case '1.3.21.74':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `user_cms_level`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_block_settings` DROP `edit_auth`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_blocks` DROP `edit_auth`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout` DROP `edit_auth`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "cms_layout_special` DROP `edit_auth`";

		// AUTH SYSTEM - BEGIN
		$sql[] = "TRUNCATE TABLE `" . $table_prefix . "acl_groups`";
		$sql[] = "TRUNCATE TABLE `" . $table_prefix . "acl_options`";
		$sql[] = "TRUNCATE TABLE `" . $table_prefix . "acl_roles`";
		$sql[] = "TRUNCATE TABLE `" . $table_prefix . "acl_roles_data`";
		$sql[] = "TRUNCATE TABLE `" . $table_prefix . "acl_users`";

		// -- CMS related auth options
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_admin', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_settings', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_layouts', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_layouts_special', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_blocks', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_blocks_global', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_permissions', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_menu', 1, 0, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cms_ads', 1, 0, 0)";

		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsl_', 0, 1, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsl_admin', 0, 1, 0)";

		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmss_', 0, 1, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmss_admin', 0, 1, 0)";

		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsb_', 0, 1, 0)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global, is_local, founder_only) VALUES ('cmsb_admin', 0, 1, 0)";

		// -- Admin related auth options
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_modules', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_roles', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_aauth', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_mauth', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_uauth', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_fauth', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_authgroups', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_authusers', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_viewauth', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_group', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('a_user', 1)";

		// -- Moderator related auth options
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_local, is_global) VALUES ('m_', 1, 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_local, is_global) VALUES ('m_topicdelete', 1, 1)";

		// -- User related auth options
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('u_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('u_html', 1)";

		// -- Forum related auth options
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_local) VALUES ('f_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_local) VALUES ('f_html', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_local) VALUES ('f_topicdelete', 1)";

		// -- Plugins related auth options
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('pl_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('pl_admin', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('pl_input', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('pl_edit', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_options (auth_option, is_global) VALUES ('pl_delete', 1)";

		// -- Standard auth roles
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (1, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cms_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (2, 'ROLE_CMS_REVIEWER', 'ROLE_CMS_REVIEWER_DESCRIPTION', 'cms_', 2)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (3, 'ROLE_CMS_PUBLISHER', 'ROLE_CMS_PUBLISHER_DESCRIPTION', 'cms_', 3)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (4, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmsl_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (5, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmss_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (6, 'ROLE_CMS_CONTENT_MANAGER', 'ROLE_CMS_CONTENT_MANAGER_DESCRIPTION', 'cmsb_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (7, 'ROLE_ADMIN_FULL', 'ROLE_ADMIN_FULL_DESCRIPTION', 'a_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (8, 'ROLE_ADMIN_STANDARD', 'ROLE_ADMIN_STANDARD_DESCRIPTION', 'a_', 2)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (9, 'ROLE_MOD_FULL', 'ROLE_MOD_FULL_DESCRIPTION', 'm_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (10, 'ROLE_MOD_STANDARD', 'ROLE_MOD_STANDARD_DESCRIPTION', 'm_', 2)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (11, 'ROLE_MOD_SIMPLE', 'ROLE_MOD_SIMPLE_DESCRIPTION', 'm_', 3)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (12, 'ROLE_USER_FULL', 'ROLE_USER_FULL_DESCRIPTION', 'u_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (13, 'ROLE_USER_STANDARD', 'ROLE_USER_STANDARD_DESCRIPTION', 'u_', 2)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (14, 'ROLE_USER_LIMITED', 'ROLE_USER_LIMITED_DESCRIPTION', 'u_', 3)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (15, 'ROLE_FORUM_FULL', 'ROLE_FORUM_FULL_DESCRIPTION', 'f_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (16, 'ROLE_FORUM_STANDARD', 'ROLE_FORUM_STANDARD_DESCRIPTION', 'f_', 2)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (17, 'ROLE_FORUM_NOACCESS', 'ROLE_FORUM_NOACCES_DESCRIPTIONS', 'f_', 3)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (18, 'ROLE_PLUGINS_FULL', 'ROLE_PLUGINS_FULL_DESCRIPTION', 'pl_', 1)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (19, 'ROLE_PLUGINS_STANDARD', 'ROLE_PLUGINS_STANDARD_DESCRIPTION', 'pl_', 2)";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles (role_id, role_name, role_description, role_type, role_order) VALUES (20, 'ROLE_PLUGINS_NOACCESS', 'ROLE_PLUGINS_NOACCESS_DESCRIPTION', 'pl_', 3)";

		// -- Roles data

		// CMS Content Manager (cms_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 1, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'cms_%' AND is_global = 1";

		// CMS Reviewer (cms_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 2, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'cms_%' AND auth_option NOT IN ('cms_admin', 'cms_settings', 'cms_permissions', 'cms_menu', 'cms_ads') AND is_global = 1";

		// CMS Publisher (cms_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 3, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option = 'cms_blocks' AND is_global = 1";

		// CMS Content Manager Layouts (cmsl_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 4, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'cmsl_%' AND is_local = 1";

		// CMS Content Manager Special Layouts (cmss_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 5, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'cmss_%' AND is_local = 1";

		// CMS Content Manager Blocks (cmsb_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 6, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'cmsb_%' AND is_local = 1";

		// Full Admin (a_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 7, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'a_%'";

		// Standard Admin (a_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 8, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'a_%' AND auth_option NOT IN ('a_modules', 'a_aauth', 'a_roles')";

		// Full Moderator (m_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 9, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'm_%'";

		// Standard Moderator (m_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 10, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'm_%' AND auth_option NOT IN ('m_topicdelete')";

		// Simple Moderator (m_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 11, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'm_%' AND auth_option IN ('m_', 'm_topicdelete')";

		// All Features (u_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 12, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'u_%'";

		// Standard Features (u_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 13, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'u_%' AND auth_option NOT IN ('u_html')";

		// Limited Features (u_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 14, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'u_%' AND auth_option NOT IN ('u_html')";

		// Full Access (f_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 15, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'f_%'";

		// Standard Access (f_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 16, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'f_%' AND auth_option NOT IN ('f_html')";

		// No Access (f_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 17, auth_option_id, 0 FROM " . $table_prefix . "acl_options WHERE auth_option = 'f_'";

		// Full Access (pl_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 18, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'pl_%'";

		// Standard Access (pl_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 19, auth_option_id, 1 FROM " . $table_prefix . "acl_options WHERE auth_option LIKE 'pl_%' AND auth_option NOT IN ('pl_admin', 'pl_delete')";

		// No Access (pl_)
		$sql[] = "INSERT INTO " . $table_prefix . "acl_roles_data (role_id, auth_option_id, auth_setting) SELECT 20, auth_option_id, 0 FROM " . $table_prefix . "acl_options WHERE auth_option = 'pl_'";

		// Permissions

		// Admin users - full features
		//$sql[] = "INSERT INTO " . $table_prefix . "acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 1, 0 FROM " . $table_prefix . "users WHERE user_level = 1";
		$sql[] = "INSERT INTO " . $table_prefix . "acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 7, 0 FROM " . $table_prefix . "users WHERE user_level = 1";
		//$sql[] = "INSERT INTO " . $table_prefix . "acl_users (user_id, forum_id, auth_option_id, auth_role_id, auth_setting) SELECT user_id, 0, 0, 18, 0 FROM " . $table_prefix . "users WHERE user_level = 1";
		// AUTH SYSTEM - END

		/* Updating from IP 1.3.22.75 */
		case '1.3.22.75':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_cms_auth` text NOT NULL AFTER `user_mask`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `user_lastlogon`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('spam_posts_number', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('spam_disable_url', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('spam_hide_signature', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('spam_post_edit_interval', '60')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('mobile_style_disable', '1')";

		/* Updating from IP 1.3.23.76 */
		case '1.3.23.76':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('session_gc', '3600')";

		/* Updating from IP 1.3.24.77 */
		case '1.3.24.77':
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('session_last_visit_reset', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('check_dnsbl', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('check_dnsbl_posting', '0')";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'disable_registration_ip_check'";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Jike Spider', '', 'jikespider', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Magpie Crawler', '', 'www.brandwatch.net', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('P3W Bot', '', 'www.p3w.it', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Radian 6', '', 'www.radian6.com/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Soso Spider', '', 'Sosospider', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Synthesio Crawler', '', 'synthesio', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Vik Spider', '', 'vikspider', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('WangID Spider', '', 'WangIDSpider/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('YandexBot 3.0', '', 'yandex.com/bots', '')";

		/* Updating from IP 1.3.25.78 */
		case '1.3.25.78':
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Ezooms', '', 'Ezooms/', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('Archive ORG BOT', '', 'www.archive.org/', '')";
		$sql[] = "UPDATE `" . $table_prefix . "config` SET `config_name` = 'disable_referers' WHERE `config_name` = 'disable_referrers'";
		$sql[] = "UPDATE `" . $table_prefix . "cms_layout_special` SET `page_id` = 'referers', `name` = 'referers', `filename` = 'referers.php' WHERE `page_id` = 'referrers'";
		$sql[] = "DROP TABLE `" . $table_prefix . "referrers`";
		$sql[] = "CREATE TABLE `" . $table_prefix . "referers` (
			`id` INT(11) NOT NULL auto_increment,
			`host` VARCHAR(255) NOT NULL DEFAULT '',
			`url` VARCHAR(255) NOT NULL DEFAULT '',
			`t_url` VARCHAR(255) NOT NULL DEFAULT '',
			`ip` VARCHAR(40) NOT NULL DEFAULT '',
			`hits` INT(11) NOT NULL DEFAULT '1',
			`firstvisit` INT(11) NOT NULL DEFAULT '0',
			`lastvisit` INT(11) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
		)";

		/* Updating from IP 1.3.26.79 */
		case '1.3.26.79':

		/* Updating from IP 1.3.27.80 */
		case '1.3.27.80':
		$sql[] = "ALTER TABLE `" . $table_prefix . "album` CHANGE `pic_user_ip` `pic_user_ip` varchar(40) NOT NULL DEFAULT ''";
		$sql[] = "UPDATE `" . $table_prefix . "album` ip SET ip.pic_user_ip = INET_NTOA(CONV(ip.pic_user_ip, 16, 10))";
		$sql[] = "UPDATE `" . $table_prefix . "ajax_shoutbox` SET shout_room = CONCAT(CONCAT('|', shout_room), '|') WHERE shout_room LIKE '%|%'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'shoutbox_refreshtime'";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ajax_chat_msgs_refresh', '5')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ajax_chat_session_refresh', '10')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ajax_chat_link_type', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ajax_chat_notification', '1')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('ajax_chat_check_online', '0')";

		/* Updating from IP 1.3.28.81 */
		case '1.3.28.81':

		/* Updating from IP 1.3.29.82 */
		case '1.3.29.82':

		/* Updating from IP 1.3.30.83 */
		case '1.3.30.83':

		/* Updating from IP 2.0.0.84RC1 */
		case '2.0.0.84':
		case '2.0.0.84RC1':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_viewemail` `user_allow_viewemail` TINYINT(1) NOT NULL DEFAULT '0'";

		if (!defined('POSTED_IMAGES_PATH')) define('POSTED_IMAGES_PATH', IP_ROOT_PATH . 'files/images/');
		if (!defined('POSTED_IMAGES_THUMBS_PATH')) define('POSTED_IMAGES_THUMBS_PATH', IP_ROOT_PATH . 'files/thumbs/');
		if (!defined('POSTED_IMAGES_THUMBS_S_PATH')) define('POSTED_IMAGES_THUMBS_S_PATH', POSTED_IMAGES_THUMBS_PATH . 's/');
		if (!@file_exists(POSTED_IMAGES_THUMBS_S_PATH))
		{
			$new_folder = substr(POSTED_IMAGES_THUMBS_S_PATH, 0, -1);
			$dir_creation = @mkdir(POSTED_IMAGES_THUMBS_S_PATH, 0777);
		}
		if (!@file_exists(POSTED_IMAGES_PATH))
		{
			// We need to remove last trailing slash "/"
			$old_folder = IP_ROOT_PATH . 'files/posted_images';
			$new_folder = substr(POSTED_IMAGES_PATH, 0, -1);
			$dir_rename = rename($old_folder, $new_folder);
			@chmod($new_folder, 0777);
		}

		$sql[] = "CREATE TABLE `" . $table_prefix . "images` (
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
		)";

		$sql[] = "UPDATE `" . $table_prefix . "cms_nav_menu` SET `menu_link` = 'images_list.php' WHERE `menu_link` = 'posted_img_list.php'";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text` = REPLACE(`post_text`,'posted_images/','images/')";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text` = REPLACE(`post_text`,'posted_img_list.php','images_list.php')";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text` = REPLACE(`post_text`,'posted_img_list_thumbnail.php','image_thumbnail_s.php')";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text` = REPLACE(`post_text`,'posted_img_thumbnail.php','image_thumbnail.php')";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_images/','images/')";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_img_list.php','images_list.php')";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_img_list_thumbnail.php','image_thumbnail_s.php')";
		$sql[] = "UPDATE `" . $table_prefix . "posts` SET `post_text_compiled` = REPLACE(`post_text_compiled`,'posted_img_thumbnail.php','image_thumbnail.php')";
		$sql[] = "UPDATE `" . $table_prefix . "cms_block_settings` SET `content` = REPLACE(`content`,'posted_images/','images/')";
		$sql[] = "UPDATE `" . $table_prefix . "users` SET `user_sig` = REPLACE(`user_sig`,'posted_images/','images/')";

		/* Updating from IP 2.0.0.85RC2 */
		case '2.0.0.85':
		case '2.0.0.85RC2':

		/* Updating from IP 2.0.0.86 */
		case '2.0.0.86':
		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` ADD COLUMN `post_images` MEDIUMTEXT NOT NULL AFTER `post_likes`";

		/* Updating from IP 2.0.1.87 */
		case '2.0.1.87':
		$sql[] = "DROP TABLE IF EXISTS `___topics_watch___`";
		$sql[] = "CREATE TABLE `___topics_watch___` (
			`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
			`forum_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
			`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
			`notify_status` TINYINT(1) NOT NULL DEFAULT '0',
			KEY `topic_id` (`topic_id`),
			KEY `user_id` (`user_id`),
			KEY `notify_status` (`notify_status`)
		)";

		$sql[] = "INSERT INTO `___topics_watch___`
			SELECT tw.topic_id, tw.forum_id, tw.user_id, tw.notify_status
				FROM `" . $table_prefix . "topics_watch` tw
				GROUP BY tw.topic_id, tw.forum_id, tw.user_id
				ORDER BY tw.topic_id, tw.user_id";

		$sql[] = "DROP TABLE IF EXISTS `_old_topics_watch`";
		$sql[] = "RENAME TABLE `phpbb_topics_watch` TO `_old_topics_watch`";
		$sql[] = "RENAME TABLE `___topics_watch___` TO `phpbb_topics_watch`";

		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('google_custom_search', '')";

		/* Updating from IP 2.0.2.88 */
		case '2.0.2.88':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` DROP `user_pc_timeOffsets`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('use_jquery_tags', '0')";

		/* Updating from IP 2.0.3.89 */
		case '2.0.3.89':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_ip` VARCHAR(40) NOT NULL DEFAULT '' AFTER `user_level`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_email_hash` BIGINT(20) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_email`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE `user_color_group` `group_id` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE COLUMN `user_email` `user_email` VARCHAR(255) DEFAULT NULL AFTER `username_clean`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE COLUMN `user_email_hash` `user_email_hash` BIGINT(20) UNSIGNED DEFAULT '0' NOT NULL AFTER `user_email`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE COLUMN `user_website` `user_website` VARCHAR(255) DEFAULT NULL AFTER `user_email_hash`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` CHANGE COLUMN `user_ip` `user_ip` VARCHAR(40) DEFAULT '' AFTER `user_website`";

		// email hash
		if (function_exists('phpbb_email_hash'))
		{
			// Now select all user_ids and email and then create email hash (this can take quite a while!)
			$sql_tmp = "SELECT user_id, user_email, user_email_hash
				FROM " . USERS_TABLE . "
				WHERE user_email <> ''";
			$result_tmp = $db->sql_query($sql_tmp);
			while ($row_tmp = $db->sql_fetchrow($result_tmp))
			{
				$user_email_hash = phpbb_email_hash($row_tmp['user_email']);

				if ($user_email_hash != $row_tmp['user_email_hash'])
				{
					$sql_update = "UPDATE " . $table_prefix . "users SET user_email_hash = '" . $db->sql_escape($user_email_hash) . "' WHERE user_id = " . (int) $row_tmp['user_id'];
					$db->sql_return_on_error(true);
					$db->sql_query($sql_update);
					$db->sql_return_on_error(false);
				}
			}
			$db->sql_freeresult($result_tmp);
		}

		/* Updating from IP 2.0.4.90 */
		case '2.0.4.90':

		/* Updating from IP 2.0.5.91 */
		case '2.0.5.91':
		$sql[] = "ALTER TABLE `" . $table_prefix . "posts` ADD COLUMN `post_locked` TINYINT(1) NOT NULL DEFAULT '0' AFTER `post_bluecard`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('user_allow_pm_register', '1')";

		/* Updating from IP 2.0.6.92 */
		case '2.0.6.92':
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'admin_protect'";

		/* Updating from IP 2.0.7.93 */
		case '2.0.7.93':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_facebook_id` VARCHAR(40) NOT NULL DEFAULT '' AFTER `user_email_hash`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('enable_social_connect', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('enable_facebook_login', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('facebook_app_id', '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('facebook_app_secret', '')";
		$sql[] = "DELETE FROM " . $table_prefix . "config WHERE `config_name` = 'index_links'";
		$sql[] = "DELETE FROM " . $table_prefix . "cms_blocks WHERE bs_id IN (SELECT bs_id FROM " . $table_prefix . "cms_block_settings WHERE blockfile = 'links')";
		$sql[] = "DELETE FROM " . $table_prefix . "cms_block_variable WHERE block = 'links'";
		$sql[] = "DELETE FROM " . $table_prefix . "cms_block_settings WHERE blockfile = 'links'";
		$sql[] = "DELETE FROM " . $table_prefix . "cms_layout_special WHERE page_id = 'links'";
		$sql[] = "DELETE FROM " . $table_prefix . "cms_nav_menu WHERE menu_link = 'links.php'";

		/* Updating from IP 2.0.8.94 */
		case '2.0.8.94':
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `exif` text NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `camera_model` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `lens` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `focal_length` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `exposure` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `aperture` varchar(255) DEFAULT '' NOT NULL";
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `iso` varchar(255) DEFAULT '' NOT NULL";

		/* Updating from IP 2.0.9.95 */
		case '2.0.9.95':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_vimeo` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_pinterest` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_instagram` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_github` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_500px` varchar(255) DEFAULT '' NOT NULL AFTER `user_youtube`";

		/* Updating from IP 2.0.10.96 */
		case '2.0.10.96':
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'disable_thanks_topics'";
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` DROP `forum_thanks`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_likes` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0' AFTER `topic_replies`";
		$sql[] = "INSERT IGNORE INTO `" . $table_prefix . "posts_likes` SELECT th.topic_id, t.topic_first_post_id, th.user_id, th.thanks_time FROM `" . $table_prefix . "thanks` th, `" . $table_prefix . "topics` t WHERE t.topic_id = th.topic_id";

		$sql[] = "ALTER IGNORE TABLE `" . $table_prefix . "posts_likes` ADD UNIQUE INDEX unique_idx_name (topic_id, post_id, user_id)";
		$sql[] = "ALTER IGNORE TABLE `" . $table_prefix . "posts_likes` DROP INDEX unique_idx_name";
		//$sql[] = "DELETE n1 FROM `" . $table_prefix . "posts_likes` n1, `" . $table_prefix . "posts_likes` n2 WHERE n1.like_time > n2.like_time AND ((n1.topic_id = n2.topic_id) AND (n1.post_id = n2.post_id) AND (n1.user_id = n2.user_id))";
		$sql[] = "DROP TABLE IF EXISTS `" . $table_prefix . "thanks`";
		$sql[] = "DELETE pl FROM `" . $table_prefix . "posts_likes` pl, `" . $table_prefix . "posts` p WHERE pl.post_id = p.post_id AND pl.user_id = p.poster_id";
		$sql[] = "UPDATE `" . $table_prefix . "posts` p SET p.post_likes = (SELECT COUNT(pl.post_id) FROM `" . $table_prefix . "posts_likes` pl WHERE pl.post_id = p.post_id)";
		$sql[] = "UPDATE `" . $table_prefix . "posts` p, `" . $table_prefix . "posts_likes` pl SET pl.topic_id = p.topic_id WHERE pl.post_id = p.post_id";
		$sql[] = "UPDATE `" . $table_prefix . "topics` t SET t.topic_likes = (SELECT COUNT(pl.topic_id) FROM `" . $table_prefix . "posts_likes` pl WHERE pl.topic_id = t.topic_id)";

		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'switch_header_dropdown'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'xmas_fx'";

		/* Updating from IP 2.0.11.97 */
		case '2.0.11.97':

		/* Updating from IP 2.0.12.98 */
		case '2.0.12.98':
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `post_id` MEDIUMINT(8) NOT NULL DEFAULT '0' AFTER `pic_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "images` ADD `attach_id` MEDIUMINT(8) NOT NULL DEFAULT '0' AFTER `post_id`";
		$sql[] = "DROP TABLE IF EXISTS `" . $table_prefix . "google_bot_detector`";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'google_bot_detector'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'yahoo_search_savepath'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'yahoo_search_additional_urls'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'yahoo_search_compress'";
		$sql[] = "DELETE FROM `" . $table_prefix . "config` WHERE `config_name` = 'yahoo_search_compression_level'";

		/* Updating from IP 2.0.13.99 */
		case '2.0.13.99':
		$sql[] = "ALTER TABLE `" . $table_prefix . "title_infos` ADD `title_html` VARCHAR(255) NOT NULL DEFAULT '' AFTER `title_info`";
		$sql[] = "UPDATE `" . $table_prefix . "title_infos` SET `title_html` = `title_info`";

		/* Updating from IP 2.0.14.100 */
		case '2.0.14.100':
		$sql[] = "ALTER TABLE `" . $table_prefix . "plugins` ADD `plugin_constants` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_enabled`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "plugins` ADD `plugin_common` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_constants`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "plugins` ADD `plugin_functions` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_common`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('thumbnail_s_size', '120')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('img_list_cols', '4')";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('img_list_rows', '5')";

		$sql[] = "UPDATE `" . $table_prefix . "plugins` SET `plugin_functions` = '1' WHERE `plugin_name` = 'cash'";

		$sql[] = "DELETE FROM `" . $table_prefix . "cms_blocks` WHERE bs_id IN (SELECT bs_id FROM `" . $table_prefix . "cms_block_settings` WHERE blockfile = 'album')";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_variable` WHERE block = 'album'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_block_settings` WHERE blockfile = 'album'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_layout_special` WHERE page_id = 'album'";
		$sql[] = "DELETE FROM `" . $table_prefix . "cms_nav_menu` WHERE menu_link = 'album.php'";

		/* Updating from IP 2.0.15.101 */
		case '2.0.15.101':
		$sql[] = "UPDATE `" . $table_prefix . "acl_roles` SET `role_description` = 'ROLE_PLUGINS_NOACCESS_DESCRIPTION' WHERE `role_description` = 'ROLE_PLUGINS_NOACCES_DESCRIPTIONS'";
		$sql[] = "CREATE TABLE `" . $table_prefix . "notifications` (
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
		)";

		/* Updating from IP 2.0.16.102 */
		case '2.0.16.102':
		$sql[] = "ALTER TABLE `" . $table_prefix . "forums` ADD `forum_recurring_first_post` TINYINT(1) NOT NULL DEFAULT '0' AFTER `forum_rules_in_posting`";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('cookie_law', '0')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('privacy_policy', 'privacy_policy', 'privacy_policy.php', 0, '', 0, '')";
		$sql[] = "INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('cookie_policy', 'cookie_policy', 'cookie_policy.php', 0, '', 0, '')";

		/* Updating from IP 2.0.17.103 */
		case '2.0.17.103':

		/* Updating from IP 2.0.18.104 */
		case '2.0.18.104':

		/* Updating from IP 2.2.0.105 */
		case '2.2.0.105':
		$sql[] = "INSERT INTO `" . $table_prefix . "bots` (`bot_name`, `bot_color`, `bot_agent`, `bot_ip`) VALUES ('AhrefsBot', '', 'AhrefsBot/', '')";
		$sql[] = "UPDATE `" . $table_prefix . "bots` SET bot_agent = 'DotBot/' WHERE bot_name = 'DotBot'";
		$sql[] = "INSERT INTO `" . $table_prefix . "config` (`config_name`, `config_value`) VALUES ('img_size_max_mp', '1')";

		$sql[] = "DROP TABLE IF EXISTS `___topics_labels___`";
		$sql[] = "CREATE TABLE `___topics_labels___` (
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
				)";

		$sql[] = "INSERT INTO `___topics_labels___`
				SELECT tt.id, tt.title_info, tt.title_html, '0', '', '', '', tt.date_format, tt.admin_auth, tt.mod_auth, tt.poster_auth
				FROM `" . $table_prefix . "title_infos` tt
				ORDER BY tt.id";

		$sql[] = "DROP TABLE IF EXISTS `_old_topics_labels_`";
		$sql[] = "RENAME TABLE `" . $table_prefix . "title_infos` TO `_old_topics_labels`";
		$sql[] = "RENAME TABLE `___topics_labels___` TO `" . $table_prefix . "topics_labels`";

		$sql[] = "UPDATE `" . $table_prefix . "topics_labels` SET `label_code` = `label_name` WHERE `label_code` = ''";

		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_label_id` MEDIUMINT(8) NOT NULL DEFAULT '0' AFTER `topic_attachment`";
		/*
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_label_d` INT(11) unsigned NOT NULL DEFAULT '0' AFTER `topic_label_id`";
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` ADD `topic_label_u` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0' AFTER `topic_label_d`";
		*/
		$sql[] = "ALTER TABLE `" . $table_prefix . "topics` CHANGE `title_compl_infos` `topic_label_compiled` VARCHAR(255) NULL DEFAULT NULL AFTER `topic_label_id`";

		// Now try to update all old labels id... only plain text labels... to avoid problems with BBCodes
		$sql_tmp = "SELECT * FROM `" . $table_prefix . "topics_labels`";
		$result_tmp = $db->sql_query($sql_tmp);
		while ($row_tmp = $db->sql_fetchrow($result_tmp))
		{
			$current_label_text = $row_tmp['label_name'];
			$sql_update = "UPDATE `" . $table_prefix . "topics`
					SET `topic_label_id` = '" . $db->sql_escape($row_tmp['id']) . "'
					WHERE `topic_label_compiled` = '" . $db->sql_escape($row_tmp['label_name']) . "'";
			$db->sql_return_on_error(true);
			$db->sql_query($sql_update);
			$db->sql_return_on_error(false);
		}
		$db->sql_freeresult($result_tmp);

		/* Updating from IP 2.2.1.106 */
		case '2.2.1.106':

		/* Updating from IP 2.2.2.107 */
		case '2.2.2.107':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_google_id` VARCHAR(40) NOT NULL DEFAULT '' AFTER `user_facebook_id`";

		/* Updating from IP 2.2.3.108 */
		case '2.2.3.108':

		/* Updating from IP 2.2.4.109 */
		case '2.2.4.109':
			$sql[] = "ALTER TABLE `" . $table_prefix . "plugins` ADD `plugin_class` TINYINT(2) NOT NULL DEFAULT 0 AFTER `plugin_functions`";

		/* Updating from IP 2.2.5.110 */
		case '2.2.5.110':
		$sql[] = "ALTER TABLE `" . $table_prefix . "users` ADD `user_privacy_policy_notify` TINYINT(2) NOT NULL DEFAULT 0 AFTER `user_popup_pm`";

		/* Updating from IP 2.2.6.111 */
		case '2.2.5.110':

	}

	$sql[] = "INSERT INTO " . $table_prefix . "config VALUES ('ip_version', '" . $ip_version . "')";
	$sql[] = "UPDATE " . $table_prefix . "config SET config_value = '" . $ip_version . "' WHERE config_name = 'ip_version'";
	$sql[] = "UPDATE " . $table_prefix . "config SET config_value = '" . $phpbb_version . "' WHERE `config_name` = 'version'";
	$sql[] = "UPDATE " . $table_prefix . "config SET config_value = '2.0.0' WHERE config_name = 'cms_version'";
	$sql[] = "UPDATE " . $table_prefix . "album_config SET config_value = '" . $fap_version . "' WHERE config_name = 'fap_version'";
	$sql[] = "UPDATE " . $table_prefix . "config SET `config_value` = '2.4.5' WHERE `config_name` = 'attach_version'";
	$sql[] = "UPDATE " . $table_prefix . "config SET `config_value` = '3.0.7' WHERE `config_name` = 'upi2db_version'";
}

?>