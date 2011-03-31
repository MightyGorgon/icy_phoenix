## `phpbb_acronyms`

CREATE TABLE `phpbb_acronyms` (
	`acronym_id` mediumint(9) NOT NULL auto_increment,
	`acronym` varchar(80) NOT NULL DEFAULT '',
	`description` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`acronym_id`)
);

## `phpbb_acronyms`


## --------------------------------------------------------

## `phpbb_adminedit`

CREATE TABLE `phpbb_adminedit` (
	`edit_id` mediumint(8) unsigned NOT NULL auto_increment,
	`edituser` char(100) NOT NULL DEFAULT '',
	`editok` char(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`edit_id`)
);

## `phpbb_adminedit`


## --------------------------------------------------------

## `phpbb_ads`

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

## `phpbb_ads`


## --------------------------------------------------------

## `phpbb_album`

CREATE TABLE `phpbb_album` (
	`pic_id` int(11) unsigned NOT NULL auto_increment,
	`pic_filename` varchar(255) NOT NULL DEFAULT '',
	`pic_size` int(15) unsigned NOT NULL DEFAULT '0',
	`pic_thumbnail` varchar(255) DEFAULT '',
	`pic_title` varchar(255) NOT NULL DEFAULT '',
	`pic_desc` TEXT NOT NULL,
	`pic_user_id` mediumint(8) NOT NULL DEFAULT '0',
	`pic_username` varchar(32) DEFAULT '',
	`pic_user_ip` varchar(8) NOT NULL DEFAULT '0',
	`pic_time` int(11) unsigned NOT NULL DEFAULT '0',
	`pic_cat_id` mediumint(8) unsigned NOT NULL DEFAULT '1',
	`pic_view_count` int(11) unsigned NOT NULL DEFAULT '0',
	`pic_lock` tinyint(3) NOT NULL DEFAULT '0',
	`pic_approval` tinyint(3) NOT NULL DEFAULT '1',
	PRIMARY KEY (`pic_id`),
	KEY `pic_cat_id` (`pic_cat_id`),
	KEY `pic_user_id` (`pic_user_id`),
	KEY `pic_time` (`pic_time`)
);

## `phpbb_album`


## --------------------------------------------------------

## `phpbb_album_cat`

CREATE TABLE `phpbb_album_cat` (
	`cat_id` mediumint(8) unsigned NOT NULL auto_increment,
	`cat_title` varchar(255) NOT NULL DEFAULT '',
	`cat_desc` TEXT NOT NULL,
	`cat_wm` TEXT NOT NULL,
	`cat_pics` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`cat_order` mediumint(8) NOT NULL DEFAULT '0',
	`cat_view_level` tinyint(3) NOT NULL DEFAULT '-1',
	`cat_upload_level` tinyint(3) NOT NULL DEFAULT '0',
	`cat_rate_level` tinyint(3) NOT NULL DEFAULT '0',
	`cat_comment_level` tinyint(3) NOT NULL DEFAULT '0',
	`cat_edit_level` tinyint(3) NOT NULL DEFAULT '0',
	`cat_delete_level` tinyint(3) NOT NULL DEFAULT '2',
	`cat_view_groups` varchar(255) DEFAULT '',
	`cat_upload_groups` varchar(255) DEFAULT '',
	`cat_rate_groups` varchar(255) DEFAULT '',
	`cat_comment_groups` varchar(255) DEFAULT '',
	`cat_edit_groups` varchar(255) DEFAULT '',
	`cat_delete_groups` varchar(255) DEFAULT '',
	`cat_moderator_groups` varchar(255) DEFAULT '',
	`cat_approval` tinyint(3) NOT NULL DEFAULT '0',
	`cat_parent` mediumint(8) unsigned DEFAULT '0',
	`cat_user_id` mediumint(8) unsigned DEFAULT '0',
	PRIMARY KEY (`cat_id`),
	KEY `cat_order` (`cat_order`)
);

## `phpbb_album_cat`


## --------------------------------------------------------

## `phpbb_album_comment`

CREATE TABLE `phpbb_album_comment` (
	`comment_id` int(11) unsigned NOT NULL auto_increment,
	`comment_pic_id` int(11) unsigned NOT NULL DEFAULT '0',
	`comment_cat_id` int(11) NOT NULL DEFAULT '0',
	`comment_user_id` mediumint(8) NOT NULL DEFAULT '0',
	`comment_username` varchar(32) DEFAULT '',
	`comment_user_ip` varchar(40) NOT NULL DEFAULT '',
	`comment_time` int(11) unsigned NOT NULL DEFAULT '0',
	`comment_text` TEXT NOT NULL,
	`comment_edit_time` int(11) unsigned DEFAULT NULL,
	`comment_edit_count` smallint(5) unsigned NOT NULL DEFAULT '0',
	`comment_edit_user_id` mediumint(8) DEFAULT NULL,
	PRIMARY KEY (`comment_id`),
	KEY `comment_pic_id` (`comment_pic_id`),
	KEY `comment_user_id` (`comment_user_id`),
	KEY `comment_user_ip` (`comment_user_ip`),
	KEY `comment_time` (`comment_time`)
);

## `phpbb_album_comment`

## --------------------------------------------------------

## `phpbb_album_comment_watch`

CREATE TABLE `phpbb_album_comment_watch` (
	pic_id mediumint(8) UNSIGNED NOT NULL DEFAULT '0',
	user_id mediumint(8) NOT NULL DEFAULT '0',
	notify_status tinyint(1) NOT NULL DEFAULT '0',
	KEY pic_id (pic_id),
	KEY user_id (user_id),
	KEY notify_status (notify_status)
);

## `phpbb_album_comment_watch`

## --------------------------------------------------------

## `phpbb_album_config`

CREATE TABLE `phpbb_album_config` (
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_album_config`


## --------------------------------------------------------

## `phpbb_album_rate`

CREATE TABLE `phpbb_album_rate` (
	`rate_pic_id` int(11) unsigned NOT NULL DEFAULT '0',
	`rate_user_id` mediumint(8) NOT NULL DEFAULT '0',
	`rate_user_ip` varchar(40) NOT NULL DEFAULT '',
	`rate_point` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`rate_hon_point` tinyint(3) NOT NULL DEFAULT '0',
	KEY `rate_pic_id` (`rate_pic_id`),
	KEY `rate_user_id` (`rate_user_id`),
	KEY `rate_user_ip` (`rate_user_ip`),
	KEY `rate_point` (`rate_point`)
);

## `phpbb_album_rate`


## --------------------------------------------------------

## `phpbb_attach_quota`

CREATE TABLE `phpbb_attach_quota` (
	`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`quota_type` smallint(2) NOT NULL DEFAULT '0',
	`quota_limit_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	KEY `quota_type` (`quota_type`)
);

## `phpbb_attach_quota`


## --------------------------------------------------------

## `phpbb_attachments`

CREATE TABLE `phpbb_attachments` (
	`attach_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`privmsgs_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_id_1` mediumint(8) NOT NULL DEFAULT '0',
	`user_id_2` mediumint(8) NOT NULL DEFAULT '0',
	KEY `attach_id_post_id` (`attach_id`,`post_id`),
	KEY `attach_id_privmsgs_id` (`attach_id`,`privmsgs_id`),
	KEY `post_id` (`post_id`),
	KEY `privmsgs_id` (`privmsgs_id`)
);

## `phpbb_attachments`


## --------------------------------------------------------

## `phpbb_attachments_desc`

CREATE TABLE `phpbb_attachments_desc` (
	`attach_id` mediumint(8) unsigned NOT NULL auto_increment,
	`physical_filename` varchar(255) NOT NULL DEFAULT '',
	`real_filename` varchar(255) NOT NULL DEFAULT '',
	`download_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`comment` varchar(255) DEFAULT NULL,
	`extension` varchar(100) DEFAULT NULL,
	`mimetype` varchar(100) DEFAULT NULL,
	`filesize` int(20) NOT NULL DEFAULT '0',
	`filetime` int(11) NOT NULL DEFAULT '0',
	`thumbnail` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`attach_id`),
	KEY `filetime` (`filetime`),
	KEY `physical_filename` (`physical_filename`(10)),
	KEY `filesize` (`filesize`)
);

## `phpbb_attachments_desc`


## --------------------------------------------------------

## `phpbb_attachments_stats`

CREATE TABLE `phpbb_attachments_stats` (
	`attach_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`user_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`user_browser` VARCHAR(255) NOT NULL DEFAULT '',
	`download_time` INT(11) NOT NULL DEFAULT '0',
	KEY `attach_id` (`attach_id`)
);

## `phpbb_attachments_stats`


## --------------------------------------------------------

## `phpbb_auth_access`

CREATE TABLE `phpbb_auth_access` (
	`group_id` mediumint(8) NOT NULL DEFAULT '0',
	`forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`auth_view` tinyint(1) NOT NULL DEFAULT '0',
	`auth_read` tinyint(1) NOT NULL DEFAULT '0',
	`auth_post` tinyint(1) NOT NULL DEFAULT '0',
	`auth_reply` tinyint(1) NOT NULL DEFAULT '0',
	`auth_edit` tinyint(1) NOT NULL DEFAULT '0',
	`auth_delete` tinyint(1) NOT NULL DEFAULT '0',
	`auth_sticky` tinyint(1) NOT NULL DEFAULT '0',
	`auth_announce` tinyint(1) NOT NULL DEFAULT '0',
	`auth_globalannounce` tinyint(1) NOT NULL DEFAULT '0',
	`auth_news` tinyint(1) NOT NULL DEFAULT '0',
	`auth_cal` tinyint(1) NOT NULL DEFAULT '0',
	`auth_vote` tinyint(1) NOT NULL DEFAULT '0',
	`auth_pollcreate` tinyint(1) NOT NULL DEFAULT '0',
	`auth_attachments` tinyint(1) NOT NULL DEFAULT '0',
	`auth_download` tinyint(1) NOT NULL DEFAULT '0',
	`auth_ban` tinyint(1) NOT NULL DEFAULT '0',
	`auth_greencard` tinyint(1) NOT NULL DEFAULT '0',
	`auth_bluecard` tinyint(1) NOT NULL DEFAULT '0',
	`auth_rate` tinyint(1) NOT NULL DEFAULT '0',
	`auth_mod` tinyint(1) NOT NULL DEFAULT '0',
	KEY `group_id` (`group_id`),
	KEY `forum_id` (`forum_id`)
);

## `phpbb_auth_access`


## --------------------------------------------------------

## `phpbb_autolinks`

CREATE TABLE `phpbb_autolinks` (
	`link_id` mediumint(5) unsigned NOT NULL auto_increment,
	`link_keyword` varchar(50) NOT NULL DEFAULT '',
	`link_title` varchar(50) NOT NULL DEFAULT '',
	`link_url` varchar(200) NOT NULL DEFAULT '',
	`link_comment` varchar(200) NOT NULL DEFAULT '',
	`link_style` varchar(200) NOT NULL DEFAULT '',
	`link_forum` tinyint(1) NOT NULL DEFAULT '0',
	`link_int` tinyint(1) NOT NULL DEFAULT '0',
	KEY `link_id` (`link_id`)
);

## `phpbb_autolinks`


## --------------------------------------------------------

## `phpbb_banlist`

CREATE TABLE `phpbb_banlist` (
	`ban_id` mediumint(8) unsigned NOT NULL auto_increment,
	`ban_userid` mediumint(8) NOT NULL DEFAULT '0',
	`ban_ip` varchar(40) NOT NULL DEFAULT '',
	`ban_email` varchar(255) DEFAULT NULL,
	`ban_start` int(11) DEFAULT NULL,
	`ban_end` int(11) DEFAULT NULL,
	`ban_by_userid` mediumint(8) DEFAULT NULL,
	`ban_priv_reason` TEXT NOT NULL,
	`ban_pub_reason_mode` tinyint(1) DEFAULT NULL,
	`ban_pub_reason` TEXT NOT NULL,
	PRIMARY KEY (`ban_id`),
	KEY `ban_ip_user_id` (`ban_ip`,`ban_userid`)
);

## `phpbb_banlist`


## --------------------------------------------------------

## `phpbb_bbcodes`

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

## `phpbb_bbcodes`


## --------------------------------------------------------

## `phpbb_bookmarks`

CREATE TABLE `phpbb_bookmarks` (
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	KEY `topic_id` (`topic_id`),
	KEY `user_id` (`user_id`)
);

## `phpbb_bookmarks`


## --------------------------------------------------------

## `phpbb_bots`

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

## `phpbb_bots`


## --------------------------------------------------------

## `phpbb_config`

CREATE TABLE `phpbb_config` (
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` TEXT NOT NULL,
	PRIMARY KEY (`config_name`)
);

## `phpbb_config`


## --------------------------------------------------------

## `phpbb_confirm`

CREATE TABLE `phpbb_confirm` (
	confirm_id char(32) DEFAULT '' NOT NULL,
	session_id char(32) DEFAULT '' NOT NULL,
	confirm_type tinyint(3) DEFAULT '0' NOT NULL,
	code varchar(8) DEFAULT '' NOT NULL,
	seed int(10) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (session_id, confirm_id),
	KEY confirm_type (confirm_type)
);

## `phpbb_confirm`


## --------------------------------------------------------

## `phpbb_disallow`

CREATE TABLE `phpbb_disallow` (
	`disallow_id` mediumint(8) unsigned NOT NULL auto_increment,
	`disallow_username` varchar(25) NOT NULL DEFAULT '',
	PRIMARY KEY (`disallow_id`)
);

## `phpbb_disallow`


## --------------------------------------------------------

## `phpbb_extension_groups`

CREATE TABLE `phpbb_extension_groups` (
	`group_id` mediumint(8) NOT NULL auto_increment,
	`group_name` varchar(20) NOT NULL DEFAULT '',
	`cat_id` tinyint(2) NOT NULL DEFAULT '0',
	`allow_group` tinyint(1) NOT NULL DEFAULT '0',
	`download_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`upload_icon` varchar(100) DEFAULT '',
	`max_filesize` int(20) NOT NULL DEFAULT '0',
	`forum_permissions` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`group_id`)
);

## `phpbb_extension_groups`


## --------------------------------------------------------

## `phpbb_extensions`

CREATE TABLE `phpbb_extensions` (
	`ext_id` mediumint(8) unsigned NOT NULL auto_increment,
	`group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`extension` varchar(100) NOT NULL DEFAULT '',
	`comment` varchar(100) DEFAULT NULL,
	PRIMARY KEY (`ext_id`)
);

## `phpbb_extensions`


## --------------------------------------------------------

## `phpbb_flags`

CREATE TABLE `phpbb_flags` (
	`flag_id` int(10) NOT NULL auto_increment,
	`flag_name` varchar(30) DEFAULT NULL,
	`flag_image` varchar(30) DEFAULT NULL,
	PRIMARY KEY (`flag_id`)
);

## `phpbb_flags`


## --------------------------------------------------------

## `phpbb_forbidden_extensions`

CREATE TABLE `phpbb_forbidden_extensions` (
	`ext_id` mediumint(8) unsigned NOT NULL auto_increment,
	`extension` varchar(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`ext_id`)
);

## `phpbb_forbidden_extensions`


## --------------------------------------------------------

## `phpbb_force_read_users`

CREATE TABLE `phpbb_force_read_users` (
	`user` varchar(255) NOT NULL DEFAULT '',
	`time` int(10) NOT NULL DEFAULT '0'
);

## `phpbb_force_read_users`


## --------------------------------------------------------

## `phpbb_forum_prune`

CREATE TABLE `phpbb_forum_prune` (
	`prune_id` mediumint(8) unsigned NOT NULL auto_increment,
	`forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`prune_days` smallint(5) unsigned NOT NULL DEFAULT '0',
	`prune_freq` smallint(5) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`prune_id`),
	KEY `forum_id` (`forum_id`)
);

## `phpbb_forum_prune`


## --------------------------------------------------------

## `phpbb_forums`

CREATE TABLE `phpbb_forums` (
	`forum_id` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
	`forum_type` tinyint(4) DEFAULT '0' NOT NULL,
	`parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`main_type` char(1) DEFAULT NULL,
	`left_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`right_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_parents` MEDIUMTEXT NOT NULL,
	`forum_name` varchar(255) DEFAULT NULL,
	`forum_name_clean` varchar(255) DEFAULT NULL,
	`forum_desc` TEXT NOT NULL,
	`forum_status` tinyint(4) NOT NULL DEFAULT '0',
	`forum_order` mediumint(8) unsigned NOT NULL DEFAULT '1',
	`forum_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_topics` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_last_topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_last_poster_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_last_post_subject` varchar(255) DEFAULT '' NOT NULL,
	`forum_last_post_time` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_last_poster_name` varchar(255) DEFAULT '' NOT NULL,
	`forum_last_poster_color` varchar(16) DEFAULT '' NOT NULL,
	`forum_postcount` tinyint(1) NOT NULL DEFAULT '1',
	`forum_thanks` tinyint(1) NOT NULL DEFAULT '0',
	`forum_likes` tinyint(1) NOT NULL DEFAULT '0',
	`forum_notify` tinyint(1) unsigned NOT NULL DEFAULT '1',
	`forum_limit_edit_time` tinyint(1) NOT NULL DEFAULT '0',
	`forum_similar_topics` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_topic_views` TINYINT(1) NOT NULL DEFAULT '1',
	`forum_tags` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_sort_box` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_kb_mode` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_index_icons` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_rules` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`forum_link` varchar(255) DEFAULT NULL,
	`forum_link_internal` tinyint(1) NOT NULL DEFAULT '0',
	`forum_link_hit_count` tinyint(1) NOT NULL DEFAULT '0',
	`forum_link_hit` bigint(20) unsigned NOT NULL DEFAULT '0',
	`icon` varchar(255) DEFAULT NULL,
	`prune_next` int(11) DEFAULT NULL,
	`prune_enable` tinyint(1) NOT NULL DEFAULT '0',
	`auth_view` tinyint(2) NOT NULL DEFAULT '0',
	`auth_read` tinyint(2) NOT NULL DEFAULT '0',
	`auth_post` tinyint(2) NOT NULL DEFAULT '0',
	`auth_reply` tinyint(2) NOT NULL DEFAULT '0',
	`auth_edit` tinyint(2) NOT NULL DEFAULT '0',
	`auth_delete` tinyint(2) NOT NULL DEFAULT '0',
	`auth_sticky` tinyint(2) NOT NULL DEFAULT '0',
	`auth_announce` tinyint(2) NOT NULL DEFAULT '0',
	`auth_globalannounce` tinyint(2) NOT NULL DEFAULT '3',
	`auth_news` tinyint(2) NOT NULL DEFAULT '2',
	`auth_cal` tinyint(2) NOT NULL DEFAULT '0',
	`auth_vote` tinyint(2) NOT NULL DEFAULT '0',
	`auth_pollcreate` tinyint(2) NOT NULL DEFAULT '0',
	`auth_attachments` tinyint(2) NOT NULL DEFAULT '0',
	`auth_download` tinyint(2) NOT NULL DEFAULT '0',
	`auth_ban` tinyint(2) NOT NULL DEFAULT '3',
	`auth_greencard` tinyint(2) NOT NULL DEFAULT '5',
	`auth_bluecard` tinyint(2) NOT NULL DEFAULT '1',
	`auth_rate` tinyint(2) NOT NULL DEFAULT '-1',
	PRIMARY KEY (`forum_id`),
	KEY `forums_order` (`forum_order`),
	KEY `parent_id` (`parent_id`),
	KEY `forum_last_post_id` (`forum_last_post_id`)
);

## `phpbb_forums`


## --------------------------------------------------------

## `phpbb_forums_rules`

CREATE TABLE `phpbb_forums_rules` (
	`forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`rules` TEXT NOT NULL,
	`rules_display_title` tinyint(1) NOT NULL DEFAULT '1',
	`rules_custom_title` varchar(80) NOT NULL DEFAULT '',
	`rules_in_viewforum` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`rules_in_viewtopic` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`rules_in_posting` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`forum_id`)
);

## `phpbb_forums_rules`


## --------------------------------------------------------

## `phpbb_forums_watch`

CREATE TABLE `phpbb_forums_watch` (
	`forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`notify_status` tinyint(1) NOT NULL DEFAULT '0',
	KEY `forum_id` (`forum_id`),
	KEY `user_id` (`user_id`),
	KEY `notify_status` (`notify_status`)
);

## `phpbb_forums_watch`


## --------------------------------------------------------

## `phpbb_google_bot_detector`

CREATE TABLE `phpbb_google_bot_detector` (
	`detect_id` int(8) NOT NULL auto_increment,
	`detect_time` int(11) NOT NULL DEFAULT '0',
	`detect_url` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`detect_id`)
);

## `phpbb_google_bot_detector`


## --------------------------------------------------------

## `phpbb_groups`

CREATE TABLE `phpbb_groups` (
	`group_id` mediumint(8) NOT NULL auto_increment,
	`group_type` tinyint(4) NOT NULL DEFAULT '1',
	`group_founder_manage` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_name` varchar(255) DEFAULT '' NOT NULL,
	`group_description` text NOT NULL,
	`group_display` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_moderator` mediumint(8) NOT NULL DEFAULT '0',
	`group_single_user` tinyint(1) NOT NULL DEFAULT '1',
	`group_rank` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_color` varchar(16) DEFAULT '' NOT NULL,
	`group_legend` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_legend_order` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_sig_chars` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_receive_pm` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_message_limit` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_max_recipients` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_skip_auth` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_count` int(4) unsigned DEFAULT '99999999',
	`group_count_max` int(4) unsigned DEFAULT '99999999',
	`group_count_enable` smallint(2) unsigned DEFAULT '0',
	`upi2db_on` tinyint(1) NOT NULL DEFAULT '1',
	`upi2db_min_posts` mediumint(4) NOT NULL DEFAULT '0',
	`upi2db_min_regdays` mediumint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`group_id`),
	KEY `group_legend_name` (`group_legend`, `group_name`),
	KEY `group_single_user` (`group_single_user`)
);

## `phpbb_groups`


## --------------------------------------------------------

## `phpbb_hacks_list`

CREATE TABLE `phpbb_hacks_list` (
	`hack_id` mediumint(8) unsigned NOT NULL auto_increment,
	`hack_name` varchar(255) NOT NULL DEFAULT '',
	`hack_desc` varchar(255) NOT NULL DEFAULT '',
	`hack_author` varchar(255) NOT NULL DEFAULT '',
	`hack_author_email` varchar(255) NOT NULL DEFAULT '',
	`hack_author_website` TINYTEXT,
	`hack_version` varchar(32) NOT NULL DEFAULT '',
	`hack_hide` enum('Yes','No') NOT NULL DEFAULT 'No',
	`hack_download_url` TINYTEXT,
	`hack_file` varchar(255) NOT NULL DEFAULT '',
	`hack_file_mtime` int(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`hack_id`),
	UNIQUE KEY `hack_name` (`hack_name`),
	KEY `hack_file` (`hack_file`),
	KEY `hack_hide` (`hack_hide`)
);

## `phpbb_hacks_list`


## --------------------------------------------------------

## `phpbb_jr_admin_users`

CREATE TABLE `phpbb_jr_admin_users` (
	`user_id` mediumint(9) NOT NULL DEFAULT '0',
	`user_jr_admin` LONGTEXT NOT NULL,
	`start_date` int(10) unsigned NOT NULL DEFAULT '0',
	`update_date` int(10) unsigned NOT NULL DEFAULT '0',
	`admin_notes` TEXT NOT NULL,
	`notes_view` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`user_id`)
);

## `phpbb_jr_admin_users`


## --------------------------------------------------------

## `phpbb_kb_articles`

CREATE TABLE `phpbb_kb_articles` (
	`article_id` mediumint(8) unsigned NOT NULL auto_increment,
	`article_category_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`article_title` varchar(255) binary NOT NULL DEFAULT '',
	`article_description` varchar(255) binary NOT NULL DEFAULT '',
	`article_date` varchar(255) binary NOT NULL DEFAULT '',
	`article_author_id` mediumint(8) NOT NULL DEFAULT '0',
	`username` varchar(255) DEFAULT NULL,
	`article_body` TEXT NOT NULL,
	`article_type` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`approved` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`views` bigint(8) NOT NULL DEFAULT '0',
	`article_rating` double(6,4) NOT NULL DEFAULT '0.0000',
	`article_totalvotes` int(255) NOT NULL DEFAULT '0',
	KEY `article_id` (`article_id`)
) AUTO_INCREMENT=2 ;

## `phpbb_kb_articles`


## --------------------------------------------------------

## `phpbb_kb_categories`

CREATE TABLE `phpbb_kb_categories` (
	`category_id` mediumint(8) unsigned NOT NULL auto_increment,
	`category_name` varchar(255) binary NOT NULL DEFAULT '',
	`category_details` varchar(255) binary NOT NULL DEFAULT '',
	`number_articles` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`parent` mediumint(8) unsigned DEFAULT NULL,
	`cat_order` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_view` tinyint(3) NOT NULL DEFAULT '0',
	`auth_post` tinyint(3) NOT NULL DEFAULT '0',
	`auth_rate` tinyint(3) NOT NULL DEFAULT '0',
	`auth_comment` tinyint(3) NOT NULL DEFAULT '0',
	`auth_edit` tinyint(3) NOT NULL DEFAULT '0',
	`auth_delete` tinyint(3) NOT NULL DEFAULT '2',
	`auth_approval` tinyint(3) NOT NULL DEFAULT '0',
	`auth_approval_edit` tinyint(3) NOT NULL DEFAULT '0',
	`auth_view_groups` varchar(255) DEFAULT NULL,
	`auth_post_groups` varchar(255) DEFAULT NULL,
	`auth_rate_groups` varchar(255) DEFAULT NULL,
	`auth_comment_groups` varchar(255) DEFAULT NULL,
	`auth_edit_groups` varchar(255) DEFAULT NULL,
	`auth_delete_groups` varchar(255) DEFAULT NULL,
	`auth_approval_groups` varchar(255) DEFAULT NULL,
	`auth_approval_edit_groups` varchar(255) DEFAULT NULL,
	`auth_moderator_groups` varchar(255) DEFAULT NULL,
	`comments_forum_id` tinyint(3) NOT NULL DEFAULT '-1',
	KEY `category_id` (`category_id`)
) AUTO_INCREMENT=2 ;

## `phpbb_kb_categories`


## --------------------------------------------------------

## `phpbb_kb_config`

CREATE TABLE `phpbb_kb_config` (
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_kb_config`


## --------------------------------------------------------

## `phpbb_kb_custom`

CREATE TABLE `phpbb_kb_custom` (
	`custom_id` int(50) NOT NULL auto_increment,
	`custom_name` TEXT NOT NULL,
	`custom_description` TEXT NOT NULL,
	`data` TEXT NOT NULL,
	`field_order` int(20) NOT NULL DEFAULT '0',
	`field_type` tinyint(2) NOT NULL DEFAULT '0',
	`regex` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`custom_id`)
);

## `phpbb_kb_custom`


## --------------------------------------------------------

## `phpbb_kb_customdata`

CREATE TABLE `phpbb_kb_customdata` (
	`customdata_file` int(50) NOT NULL DEFAULT '0',
	`customdata_custom` int(50) NOT NULL DEFAULT '0',
	`data` TEXT
);

## `phpbb_kb_customdata`


## --------------------------------------------------------

## `phpbb_kb_results`

CREATE TABLE `phpbb_kb_results` (
	`search_id` int(11) unsigned NOT NULL DEFAULT '0',
	`session_id` varchar(32) NOT NULL DEFAULT '',
	`search_array` TEXT NOT NULL,
	PRIMARY KEY (`search_id`),
	KEY `session_id` (`session_id`)
);

## `phpbb_kb_results`


## --------------------------------------------------------

## `phpbb_kb_search`

CREATE TABLE `phpbb_kb_search` (
	`search_id` int(11) unsigned NOT NULL DEFAULT '0',
	`session_id` varchar(32) NOT NULL DEFAULT '',
	`search_array` TEXT NOT NULL,
	PRIMARY KEY (`search_id`),
	KEY `session_id` (`session_id`)
);

## `phpbb_kb_search`


## --------------------------------------------------------

## `phpbb_kb_types`

CREATE TABLE `phpbb_kb_types` (
	`id` mediumint(8) unsigned NOT NULL auto_increment,
	`type` varchar(255) binary NOT NULL DEFAULT '',
	KEY `id` (`id`)
) AUTO_INCREMENT=2 ;

## `phpbb_kb_types`


## --------------------------------------------------------

## `phpbb_kb_votes`

CREATE TABLE `phpbb_kb_votes` (
	`votes_ip` varchar(50) NOT NULL DEFAULT '0',
	`votes_userid` int(50) NOT NULL DEFAULT '0',
	`votes_file` int(50) NOT NULL DEFAULT '0'
);

## `phpbb_kb_votes`


## --------------------------------------------------------

## `phpbb_kb_wordlist`

CREATE TABLE `phpbb_kb_wordlist` (
	`word_text` varchar(50) binary NOT NULL DEFAULT '',
	`word_id` mediumint(8) unsigned NOT NULL auto_increment,
	`word_common` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`word_text`),
	KEY `word_id` (`word_id`)
);

## `phpbb_kb_wordlist`


## --------------------------------------------------------

## `phpbb_kb_wordmatch`

CREATE TABLE `phpbb_kb_wordmatch` (
	`article_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`word_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`title_match` tinyint(1) NOT NULL DEFAULT '0',
	KEY `post_id` (`article_id`),
	KEY `word_id` (`word_id`)
);

## `phpbb_kb_wordmatch`


## --------------------------------------------------------

## `phpbb_link_categories`

CREATE TABLE `phpbb_link_categories` (
	`cat_id` mediumint(8) unsigned NOT NULL auto_increment,
	`cat_title` varchar(100) NOT NULL DEFAULT '',
	`cat_order` mediumint(8) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`cat_id`),
	KEY `cat_order` (`cat_order`)
);

## `phpbb_link_categories`


## --------------------------------------------------------

## `phpbb_link_config`

CREATE TABLE `phpbb_link_config` (
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` varchar(255) NOT NULL DEFAULT ''
);

## `phpbb_link_config`


## --------------------------------------------------------

## `phpbb_links`

CREATE TABLE `phpbb_links` (
	`link_id` mediumint(8) unsigned NOT NULL auto_increment,
	`link_title` varchar(100) NOT NULL DEFAULT '',
	`link_desc` varchar(255) DEFAULT NULL,
	`link_category` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`link_url` varchar(100) NOT NULL DEFAULT '',
	`link_logo_src` varchar(120) DEFAULT NULL,
	`link_joined` int(11) NOT NULL DEFAULT '0',
	`link_active` tinyint(1) NOT NULL DEFAULT '0',
	`link_hits` int(10) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`user_ip` varchar(40) NOT NULL DEFAULT '',
	`last_user_ip` varchar(40) NOT NULL DEFAULT '',
	PRIMARY KEY (`link_id`)
);

## `phpbb_links`


## --------------------------------------------------------

## `phpbb_liw_cache`

CREATE TABLE `phpbb_liw_cache` (
	`image_checksum` varchar(32) NOT NULL DEFAULT '',
	`image_width` varchar(10) DEFAULT NULL,
	`image_height` varchar(10) DEFAULT NULL,
	PRIMARY KEY (`image_checksum`)
);

## `phpbb_liw_cache`


## --------------------------------------------------------

## `phpbb_logins`

CREATE TABLE `phpbb_logins` (
	`login_id` mediumint(8) unsigned NOT NULL auto_increment,
	`login_userid` mediumint(8) NOT NULL DEFAULT '0',
	`login_ip` varchar(40) NOT NULL DEFAULT '0',
	`login_user_agent` varchar(255) NOT NULL DEFAULT 'n/a',
	`login_time` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`login_id`)
);

## `phpbb_logins`


## --------------------------------------------------------

## `phpbb_megamail`

CREATE TABLE `phpbb_megamail` (
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

## `phpbb_megamail`


## --------------------------------------------------------

## `phpbb_news`

CREATE TABLE `phpbb_news` (
	`news_id` mediumint(8) unsigned NOT NULL auto_increment,
	`news_category` varchar(70) NOT NULL DEFAULT '',
	`news_image` varchar(70) NOT NULL DEFAULT '',
	PRIMARY KEY (`news_id`)
);

## `phpbb_news`


## --------------------------------------------------------

## `phpbb_notes`

CREATE TABLE `phpbb_notes` (
	`id` int(8) NOT NULL DEFAULT '0',
	`text` text
);

## `phpbb_notes`


## --------------------------------------------------------

## `phpbb_pa_auth`

CREATE TABLE `phpbb_pa_auth` (
	`group_id` mediumint(8) NOT NULL DEFAULT '0',
	`cat_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`auth_view` tinyint(1) NOT NULL DEFAULT '0',
	`auth_read` tinyint(1) NOT NULL DEFAULT '0',
	`auth_view_file` tinyint(1) NOT NULL DEFAULT '0',
	`auth_edit_file` tinyint(1) NOT NULL DEFAULT '0',
	`auth_delete_file` tinyint(1) NOT NULL DEFAULT '0',
	`auth_upload` tinyint(1) NOT NULL DEFAULT '0',
	`auth_download` tinyint(1) NOT NULL DEFAULT '0',
	`auth_rate` tinyint(1) NOT NULL DEFAULT '0',
	`auth_email` tinyint(1) NOT NULL DEFAULT '0',
	`auth_view_comment` tinyint(1) NOT NULL DEFAULT '0',
	`auth_post_comment` tinyint(1) NOT NULL DEFAULT '0',
	`auth_edit_comment` tinyint(1) NOT NULL DEFAULT '0',
	`auth_delete_comment` tinyint(1) NOT NULL DEFAULT '0',
	`auth_mod` tinyint(1) NOT NULL DEFAULT '0',
	`auth_search` tinyint(1) NOT NULL DEFAULT '1',
	`auth_stats` tinyint(1) NOT NULL DEFAULT '1',
	`auth_toplist` tinyint(1) NOT NULL DEFAULT '1',
	`auth_viewall` tinyint(1) NOT NULL DEFAULT '1',
	KEY `group_id` (`group_id`),
	KEY `cat_id` (`cat_id`)
);

## `phpbb_pa_auth`


## --------------------------------------------------------

## `phpbb_pa_cat`

CREATE TABLE `phpbb_pa_cat` (
	`cat_id` int(10) NOT NULL auto_increment,
	`cat_name` TEXT NOT NULL,
	`cat_desc` TEXT NOT NULL,
	`cat_parent` int(50) DEFAULT NULL,
	`parents_data` TEXT NOT NULL,
	`cat_order` int(50) DEFAULT NULL,
	`cat_allow_file` tinyint(2) NOT NULL DEFAULT '0',
	`cat_allow_ratings` tinyint(2) NOT NULL DEFAULT '1',
	`cat_allow_comments` tinyint(2) NOT NULL DEFAULT '1',
	`cat_files` mediumint(8) NOT NULL DEFAULT '-1',
	`cat_last_file_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`cat_last_file_name` varchar(255) NOT NULL DEFAULT '',
	`cat_last_file_time` int(50) unsigned NOT NULL DEFAULT '0',
	`auth_view` tinyint(2) NOT NULL DEFAULT '0',
	`auth_read` tinyint(2) NOT NULL DEFAULT '0',
	`auth_view_file` tinyint(2) NOT NULL DEFAULT '0',
	`auth_edit_file` tinyint(1) NOT NULL DEFAULT '0',
	`auth_delete_file` tinyint(1) NOT NULL DEFAULT '0',
	`auth_upload` tinyint(2) NOT NULL DEFAULT '0',
	`auth_download` tinyint(2) NOT NULL DEFAULT '0',
	`auth_rate` tinyint(2) NOT NULL DEFAULT '0',
	`auth_email` tinyint(2) NOT NULL DEFAULT '0',
	`auth_view_comment` tinyint(2) NOT NULL DEFAULT '0',
	`auth_post_comment` tinyint(2) NOT NULL DEFAULT '0',
	`auth_edit_comment` tinyint(2) NOT NULL DEFAULT '0',
	`auth_delete_comment` tinyint(2) NOT NULL DEFAULT '0',
	PRIMARY KEY (`cat_id`)
);

## `phpbb_pa_cat`


## --------------------------------------------------------

## `phpbb_pa_comments`

CREATE TABLE `phpbb_pa_comments` (
	`comments_id` int(10) NOT NULL auto_increment,
	`file_id` int(10) NOT NULL DEFAULT '0',
	`comments_text` TEXT NOT NULL,
	`comments_title` TEXT NOT NULL,
	`comments_time` int(50) NOT NULL DEFAULT '0',
	`poster_id` mediumint(8) NOT NULL DEFAULT '0',
	PRIMARY KEY (`comments_id`)
);

## `phpbb_pa_comments`


## --------------------------------------------------------

## `phpbb_pa_config`

CREATE TABLE `phpbb_pa_config` (
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_pa_config`


## --------------------------------------------------------

## `phpbb_pa_custom`

CREATE TABLE `phpbb_pa_custom` (
	`custom_id` int(50) NOT NULL auto_increment,
	`custom_name` TEXT NOT NULL,
	`custom_description` TEXT NOT NULL,
	`data` TEXT NOT NULL,
	`field_order` int(20) NOT NULL DEFAULT '0',
	`field_type` tinyint(2) NOT NULL DEFAULT '0',
	`regex` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`custom_id`)
);

## `phpbb_pa_custom`


## --------------------------------------------------------

## `phpbb_pa_customdata`

CREATE TABLE `phpbb_pa_customdata` (
	`customdata_file` int(50) NOT NULL DEFAULT '0',
	`customdata_custom` int(50) NOT NULL DEFAULT '0',
	`data` TEXT
);

## `phpbb_pa_customdata`


## --------------------------------------------------------

## `phpbb_pa_download_info`

CREATE TABLE `phpbb_pa_download_info` (
	`file_id` mediumint(8) NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`download_time` int(11) NOT NULL DEFAULT '0',
	`downloader_ip` varchar(40) NOT NULL DEFAULT '',
	`downloader_os` varchar(255) NOT NULL DEFAULT '',
	`downloader_browser` varchar(255) NOT NULL DEFAULT '',
	`browser_version` varchar(255) NOT NULL DEFAULT ''
);

## `phpbb_pa_download_info`


## --------------------------------------------------------

## `phpbb_pa_files`

CREATE TABLE `phpbb_pa_files` (
	`file_id` int(10) NOT NULL auto_increment,
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`poster_ip` varchar(40) NOT NULL DEFAULT '',
	`file_name` text NOT NULL,
	`file_size` int(20) NOT NULL DEFAULT '0',
	`unique_name` varchar(255) NOT NULL DEFAULT '',
	`real_name` varchar(255) NOT NULL DEFAULT '',
	`file_dir` varchar(255) NOT NULL DEFAULT '',
	`file_desc` text NOT NULL,
	`file_creator` text NOT NULL,
	`file_version` text NOT NULL,
	`file_longdesc` text NOT NULL,
	`file_ssurl` text NOT NULL,
	`file_sshot_link` tinyint(2) NOT NULL DEFAULT '0',
	`file_dlurl` text NOT NULL,
	`file_time` int(50) DEFAULT NULL,
	`file_update_time` int(50) NOT NULL DEFAULT '0',
	`file_catid` int(10) DEFAULT NULL,
	`file_posticon` text NOT NULL,
	`file_license` int(10) DEFAULT NULL,
	`file_dls` int(10) DEFAULT NULL,
	`file_last` int(50) DEFAULT NULL,
	`file_pin` int(2) DEFAULT NULL,
	`file_docsurl` text NOT NULL,
	`file_approved` tinyint(1) NOT NULL DEFAULT '1',
	`file_broken` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`file_id`)
);

## `phpbb_pa_files`


## --------------------------------------------------------

## `phpbb_pa_license`

CREATE TABLE `phpbb_pa_license` (
	`license_id` int(10) NOT NULL auto_increment,
	`license_name` TEXT NOT NULL,
	`license_text` TEXT NOT NULL,
	PRIMARY KEY (`license_id`)
);

## `phpbb_pa_license`


## --------------------------------------------------------

## `phpbb_pa_mirrors`

CREATE TABLE `phpbb_pa_mirrors` (
	`mirror_id` mediumint(8) NOT NULL auto_increment,
	`file_id` int(10) NOT NULL DEFAULT '0',
	`unique_name` varchar(255) NOT NULL DEFAULT '',
	`file_dir` varchar(255) NOT NULL DEFAULT '',
	`file_dlurl` varchar(255) NOT NULL DEFAULT '',
	`mirror_location` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`mirror_id`),
	KEY `file_id` (`file_id`)
);

## `phpbb_pa_mirrors`


## --------------------------------------------------------

## `phpbb_pa_votes`

CREATE TABLE `phpbb_pa_votes` (
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`votes_ip` varchar(50) NOT NULL DEFAULT '0',
	`votes_file` int(50) NOT NULL DEFAULT '0',
	`rate_point` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`voter_os` varchar(255) NOT NULL DEFAULT '',
	`voter_browser` varchar(255) NOT NULL DEFAULT '',
	`browser_version` varchar(8) NOT NULL DEFAULT '',
	KEY `user_id` (`user_id`)
);

## `phpbb_pa_votes`


## --------------------------------------------------------

## `phpbb_plugins`

CREATE TABLE `phpbb_plugins` (
	`plugin_name` varchar(255) NOT NULL DEFAULT '',
	`plugin_version` varchar(255) NOT NULL DEFAULT '',
	`plugin_dir` varchar(255) NOT NULL DEFAULT '',
	`plugin_enabled` tinyint(2) NOT NULL DEFAULT 0,
	PRIMARY KEY (`plugin_name`)
);

## `phpbb_plugins`


## --------------------------------------------------------

## `phpbb_plugins_config`

CREATE TABLE `phpbb_plugins_config` (
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` TEXT NOT NULL,
	PRIMARY KEY (`config_name`)
);

## `phpbb_plugins_config`


## --------------------------------------------------------

## `phpbb_poll_options`

CREATE TABLE `phpbb_poll_options` (
	`poll_option_id` tinyint(4) DEFAULT '0' NOT NULL,
	`topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_option_text` text NOT NULL,
	`poll_option_total` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	KEY `poll_opt_id` (`poll_option_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_poll_options`


## --------------------------------------------------------

## `phpbb_poll_votes`

CREATE TABLE `phpbb_poll_votes` (
	`topic_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_option_id` tinyint(4) DEFAULT '0' NOT NULL,
	`vote_user_id` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`vote_user_ip` varchar(40) DEFAULT '' NOT NULL,
	KEY `topic_id` (`topic_id`),
	KEY `vote_user_id` (`vote_user_id`),
	KEY `vote_user_ip` (`vote_user_ip`)
);

## `phpbb_poll_votes`


## --------------------------------------------------------

## `phpbb_posts`

CREATE TABLE `phpbb_posts` (
	`post_id` mediumint(8) unsigned NOT NULL auto_increment,
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`poster_id` mediumint(8) NOT NULL DEFAULT '0',
	`post_time` int(11) NOT NULL DEFAULT '0',
	`poster_ip` varchar(40) NOT NULL DEFAULT '',
	`post_username` varchar(25) DEFAULT NULL,
	`post_subject` varchar(255) DEFAULT NULL,
	`post_text` text NOT NULL,
	`post_text_compiled` TEXT NOT NULL,
	`enable_bbcode` tinyint(1) NOT NULL DEFAULT '1',
	`enable_html` tinyint(1) NOT NULL DEFAULT '0',
	`enable_smilies` tinyint(1) NOT NULL DEFAULT '1',
	`enable_autolinks_acronyms` tinyint(1) NOT NULL DEFAULT '1',
	`enable_sig` tinyint(1) NOT NULL DEFAULT '1',
	`edit_notes` mediumtext,
	`post_edit_time` int(11) DEFAULT NULL,
	`post_edit_count` smallint(5) unsigned NOT NULL DEFAULT '0',
	`post_edit_id` mediumint(8) NOT NULL DEFAULT '0',
	`post_attachment` tinyint(1) NOT NULL DEFAULT '0',
	`post_bluecard` tinyint(1) DEFAULT NULL,
	`post_likes` mediumint(8) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`post_id`),
	KEY `forum_id` (`forum_id`),
	KEY `topic_id` (`topic_id`),
	KEY `poster_id` (`poster_id`),
	KEY `post_time` (`post_time`)
);

## `phpbb_posts`


## --------------------------------------------------------

## `phpbb_posts_likes`

CREATE TABLE `phpbb_posts_likes` (
	topic_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	like_time  int(11) UNSIGNED DEFAULT '0' NOT NULL,
	KEY topic_id (topic_id),
	KEY post_id (post_id),
	KEY user_id (user_id)
);

## `phpbb_posts_likes`


## --------------------------------------------------------

## `phpbb_privmsgs`

CREATE TABLE `phpbb_privmsgs` (
	`privmsgs_id` mediumint(8) unsigned NOT NULL auto_increment,
	`privmsgs_type` tinyint(4) NOT NULL DEFAULT '0',
	`privmsgs_subject` varchar(255) NOT NULL DEFAULT '',
	`privmsgs_text` text NOT NULL,
	`privmsgs_from_userid` mediumint(8) NOT NULL DEFAULT '0',
	`privmsgs_to_userid` mediumint(8) NOT NULL DEFAULT '0',
	`privmsgs_date` int(11) NOT NULL DEFAULT '0',
	`privmsgs_ip` varchar(40) NOT NULL DEFAULT '',
	`privmsgs_enable_bbcode` tinyint(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_html` tinyint(1) NOT NULL DEFAULT '0',
	`privmsgs_enable_smilies` tinyint(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_autolinks_acronyms` tinyint(1) NOT NULL DEFAULT '0',
	`privmsgs_attach_sig` tinyint(1) NOT NULL DEFAULT '1',
	`privmsgs_attachment` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`privmsgs_id`),
	KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
	KEY `privmsgs_to_userid` (`privmsgs_to_userid`)
);

## `phpbb_privmsgs`


## --------------------------------------------------------

## `phpbb_privmsgs_archive`

CREATE TABLE `phpbb_privmsgs_archive` (
	`privmsgs_id` mediumint(8) unsigned NOT NULL auto_increment,
	`privmsgs_type` tinyint(4) NOT NULL DEFAULT '0',
	`privmsgs_subject` varchar(255) NOT NULL DEFAULT '',
	`privmsgs_text` text NOT NULL,
	`privmsgs_from_userid` mediumint(8) NOT NULL DEFAULT '0',
	`privmsgs_to_userid` mediumint(8) NOT NULL DEFAULT '0',
	`privmsgs_date` int(11) NOT NULL DEFAULT '0',
	`privmsgs_ip` varchar(40) NOT NULL DEFAULT '',
	`privmsgs_enable_bbcode` tinyint(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_html` tinyint(1) NOT NULL DEFAULT '0',
	`privmsgs_enable_smilies` tinyint(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_autolinks_acronyms` tinyint(1) NOT NULL DEFAULT '0',
	`privmsgs_attach_sig` tinyint(1) NOT NULL DEFAULT '1',
	`privmsgs_attachment` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`privmsgs_id`),
	KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
	KEY `privmsgs_to_userid` (`privmsgs_to_userid`)
);

## `phpbb_privmsgs_archive`


## --------------------------------------------------------

## `phpbb_profile_fields`

CREATE TABLE `phpbb_profile_fields` (
	`field_id` mediumint(8) unsigned NOT NULL auto_increment,
	`field_name` varchar(255) NOT NULL DEFAULT '',
	`field_description` varchar(255) DEFAULT NULL,
	`field_type` tinyint(4) unsigned NOT NULL DEFAULT '0',
	`text_field_default` varchar(255) DEFAULT NULL,
	`text_field_maxlen` int(255) unsigned NOT NULL DEFAULT '255',
	`text_area_default` TEXT NOT NULL,
	`text_area_maxlen` int(255) unsigned NOT NULL DEFAULT '1024',
	`radio_button_default` varchar(255) DEFAULT NULL,
	`radio_button_values` text NOT NULL,
	`checkbox_default` text NOT NULL,
	`checkbox_values` text NOT NULL,
	`is_required` tinyint(2) unsigned NOT NULL DEFAULT '0',
	`users_can_view` tinyint(2) unsigned NOT NULL DEFAULT '1',
	`view_in_profile` tinyint(2) unsigned NOT NULL DEFAULT '1',
	`profile_location` tinyint(2) unsigned NOT NULL DEFAULT '2',
	`view_in_memberlist` tinyint(2) unsigned NOT NULL DEFAULT '0',
	`view_in_topic` tinyint(2) unsigned NOT NULL DEFAULT '0',
	`topic_location` tinyint(2) unsigned NOT NULL DEFAULT '1',
	PRIMARY KEY (`field_id`),
	UNIQUE KEY `field_name` (`field_name`),
	KEY `field_type` (`field_type`)
);

## `phpbb_profile_fields`


## --------------------------------------------------------

## `phpbb_profile_view`

CREATE TABLE `phpbb_profile_view` (
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`viewername` varchar(25) NOT NULL DEFAULT '',
	`viewer_id` mediumint(8) NOT NULL DEFAULT '0',
	`view_stamp` int(11) NOT NULL DEFAULT '0',
	`counter` mediumint(8) NOT NULL DEFAULT '0'
);

## `phpbb_profile_view`


## --------------------------------------------------------

## `phpbb_quota_limits`

CREATE TABLE `phpbb_quota_limits` (
	`quota_limit_id` mediumint(8) unsigned NOT NULL auto_increment,
	`quota_desc` varchar(20) NOT NULL DEFAULT '',
	`quota_limit` bigint(20) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`quota_limit_id`)
);

## `phpbb_quota_limits`


## --------------------------------------------------------

## `phpbb_ranks`

CREATE TABLE `phpbb_ranks` (
	`rank_id` smallint(5) unsigned NOT NULL auto_increment,
	`rank_title` varchar(50) NOT NULL DEFAULT '',
	`rank_min` mediumint(8) NOT NULL DEFAULT '0',
	`rank_special` tinyint(1) DEFAULT '0',
	`rank_show_title` tinyint(1) DEFAULT '1',
	`rank_image` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`rank_id`)
);

## `phpbb_ranks`


## --------------------------------------------------------

## `phpbb_rate_results`

CREATE TABLE `phpbb_rate_results` (
	`rating_id` mediumint(8) unsigned NOT NULL auto_increment,
	`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`rating` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_ip` varchar(40) NOT NULL DEFAULT '',
	`rating_time` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`rating_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_rate_results`


## --------------------------------------------------------

## `phpbb_referrers`

CREATE TABLE `phpbb_referrers` (
	`referrer_id` int(11) NOT NULL auto_increment,
	`referrer_host` varchar(255) NOT NULL DEFAULT '',
	`referrer_url` varchar(255) NOT NULL DEFAULT '',
	`referrer_ip` varchar(40) NOT NULL DEFAULT '',
	`referrer_hits` int(11) NOT NULL DEFAULT '1',
	`referrer_firstvisit` int(11) NOT NULL DEFAULT '0',
	`referrer_lastvisit` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`referrer_id`)
);

## `phpbb_referrers`


## --------------------------------------------------------

## `phpbb_search_results`

CREATE TABLE `phpbb_search_results` (
	`search_id` int(11) unsigned NOT NULL DEFAULT '0',
	`session_id` varchar(32) NOT NULL DEFAULT '',
	`search_array` MEDIUMTEXT NOT NULL,
	`search_time` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`search_id`),
	KEY `session_id` (`session_id`)
);

## `phpbb_search_results`


## --------------------------------------------------------

## `phpbb_search_wordlist`

CREATE TABLE `phpbb_search_wordlist` (
	`word_text` varchar(50) binary NOT NULL DEFAULT '',
	`word_id` mediumint(8) unsigned NOT NULL auto_increment,
	`word_common` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`word_text`),
	KEY `word_id` (`word_id`)
);

## `phpbb_search_wordlist`


## --------------------------------------------------------

## `phpbb_search_wordmatch`

CREATE TABLE `phpbb_search_wordmatch` (
	`post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`word_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`title_match` tinyint(1) NOT NULL DEFAULT '0',
	KEY `post_id` (`post_id`),
	KEY `word_id` (`word_id`)
);

## `phpbb_search_wordmatch`


## --------------------------------------------------------

## `phpbb_sessions`

CREATE TABLE `phpbb_sessions` (
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

## `phpbb_sessions`


## --------------------------------------------------------

## `phpbb_sessions_keys`

CREATE TABLE `phpbb_sessions_keys` (
	`key_id` varchar(32) NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`last_ip` varchar(40) NOT NULL DEFAULT '',
	`last_login` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`key_id`,`user_id`),
	KEY `last_login` (`last_login`)
);

## `phpbb_sessions_keys`


## --------------------------------------------------------

## `phpbb_shout`

CREATE TABLE `phpbb_shout` (
	`shout_id` mediumint(8) unsigned NOT NULL auto_increment,
	`shout_username` varchar(25) NOT NULL DEFAULT '',
	`shout_user_id` mediumint(8) NOT NULL DEFAULT '0',
	`shout_group_id` mediumint(8) NOT NULL DEFAULT '0',
	`shout_session_time` int(11) NOT NULL DEFAULT '0',
	`shout_ip` varchar(40) NOT NULL DEFAULT '',
	`shout_text` TEXT NOT NULL,
	`shout_active` mediumint(8) NOT NULL DEFAULT '0',
	`enable_bbcode` tinyint(1) NOT NULL DEFAULT '0',
	`enable_html` tinyint(1) NOT NULL DEFAULT '0',
	`enable_smilies` tinyint(1) NOT NULL DEFAULT '0',
	`enable_sig` tinyint(1) NOT NULL DEFAULT '0',
	KEY `shout_id` (`shout_id`)
);

## `phpbb_shout`


## --------------------------------------------------------

## `phpbb_site_history`

CREATE TABLE `phpbb_site_history` (
	`date` int(11) NOT NULL DEFAULT '0',
	`reg` mediumint(8) NOT NULL DEFAULT '0',
	`hidden` mediumint(8) NOT NULL DEFAULT '0',
	`guests` mediumint(8) NOT NULL DEFAULT '0',
	`new_topics` mediumint(8) NOT NULL DEFAULT '0',
	`new_posts` mediumint(8) NOT NULL DEFAULT '0',
	UNIQUE KEY `date` (`date`)
);

## `phpbb_site_history`


## --------------------------------------------------------

## `phpbb_smilies`

CREATE TABLE `phpbb_smilies` (
	`smilies_id` smallint(5) unsigned NOT NULL auto_increment,
	`code` varchar(50) DEFAULT NULL,
	`smile_url` varchar(100) DEFAULT NULL,
	`emoticon` varchar(75) DEFAULT NULL,
	`smilies_order` int(5) NOT NULL DEFAULT '0',
	PRIMARY KEY (`smilies_id`)
);

## `phpbb_smilies`


## --------------------------------------------------------

## `phpbb_stats_config`

CREATE TABLE `phpbb_stats_config` (
	`config_name` varchar(50) NOT NULL DEFAULT '',
	`config_value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_stats_config`


## --------------------------------------------------------

## `phpbb_stats_modules`

CREATE TABLE `phpbb_stats_modules` (
	`module_id` tinyint(8) NOT NULL DEFAULT '0',
	`name` varchar(150) NOT NULL DEFAULT '',
	`active` tinyint(1) NOT NULL DEFAULT '0',
	`installed` tinyint(1) NOT NULL DEFAULT '0',
	`display_order` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`update_time` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`auth_value` tinyint(2) NOT NULL DEFAULT '0',
	`module_info_cache` blob,
	`module_db_cache` blob,
	`module_result_cache` blob,
	`module_info_time` int(10) unsigned NOT NULL DEFAULT '0',
	`module_cache_time` int(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`module_id`)
);

## `phpbb_stats_modules`


## --------------------------------------------------------

## `phpbb_sudoku_sessions`

CREATE TABLE `phpbb_sudoku_sessions` (
	`user_id` int(11) NOT NULL DEFAULT '0',
	`session_time` int(11) NOT NULL DEFAULT '0'
);

## `phpbb_sudoku_sessions`


## --------------------------------------------------------

## `phpbb_sudoku_solutions`

CREATE TABLE `phpbb_sudoku_solutions` (
	`game_pack` int(5) NOT NULL DEFAULT '0',
	`game_num` int(5) NOT NULL DEFAULT '0',
	`line_1` varchar(20) NOT NULL DEFAULT '',
	`line_2` varchar(20) NOT NULL DEFAULT '',
	`line_3` varchar(20) NOT NULL DEFAULT '',
	`line_4` varchar(20) NOT NULL DEFAULT '',
	`line_5` varchar(20) NOT NULL DEFAULT '',
	`line_6` varchar(20) NOT NULL DEFAULT '',
	`line_7` varchar(20) NOT NULL DEFAULT '',
	`line_8` varchar(20) NOT NULL DEFAULT '',
	`line_9` varchar(20) NOT NULL DEFAULT '',
	KEY `game_pack` (`game_pack`)
);

## `phpbb_sudoku_solutions`


## --------------------------------------------------------

## `phpbb_sudoku_starts`

CREATE TABLE `phpbb_sudoku_starts` (
	`game_pack` int(5) NOT NULL DEFAULT '0',
	`game_num` int(5) NOT NULL DEFAULT '0',
	`game_level` int(1) NOT NULL DEFAULT '0',
	`line_1` varchar(20) NOT NULL DEFAULT '',
	`line_2` varchar(20) NOT NULL DEFAULT '',
	`line_3` varchar(20) NOT NULL DEFAULT '',
	`line_4` varchar(20) NOT NULL DEFAULT '',
	`line_5` varchar(20) NOT NULL DEFAULT '',
	`line_6` varchar(20) NOT NULL DEFAULT '',
	`line_7` varchar(20) NOT NULL DEFAULT '',
	`line_8` varchar(20) NOT NULL DEFAULT '',
	`line_9` varchar(20) NOT NULL DEFAULT '',
	KEY `game_pack` (`game_pack`)
);

## `phpbb_sudoku_starts`


## --------------------------------------------------------

## `phpbb_sudoku_stats`

CREATE TABLE `phpbb_sudoku_stats` (
	`user_id` int(11) NOT NULL DEFAULT '0',
	`played` int(11) NOT NULL DEFAULT '0',
	`points` int(11) NOT NULL DEFAULT '0',
	KEY `user_id` (`user_id`)
);

## `phpbb_sudoku_stats`


## --------------------------------------------------------

## `phpbb_sudoku_users`

CREATE TABLE `phpbb_sudoku_users` (
	`user_id` int(11) NOT NULL DEFAULT '0',
	`game_pack` int(5) NOT NULL DEFAULT '0',
	`game_num` int(5) NOT NULL DEFAULT '0',
	`game_level` int(1) NOT NULL DEFAULT '0',
	`line_1` varchar(30) NOT NULL DEFAULT '',
	`line_2` varchar(30) NOT NULL DEFAULT '',
	`line_3` varchar(30) NOT NULL DEFAULT '',
	`line_4` varchar(30) NOT NULL DEFAULT '',
	`line_5` varchar(30) NOT NULL DEFAULT '',
	`line_6` varchar(30) NOT NULL DEFAULT '',
	`line_7` varchar(30) NOT NULL DEFAULT '',
	`line_8` varchar(30) NOT NULL DEFAULT '',
	`line_9` varchar(30) NOT NULL DEFAULT '',
	`points` int(11) NOT NULL DEFAULT '0',
	`done` int(1) NOT NULL DEFAULT '0',
	KEY `user_id` (`user_id`)
);

## `phpbb_sudoku_users`


## --------------------------------------------------------

## `phpbb_thanks`

CREATE TABLE `phpbb_thanks` (
	`topic_id` mediumint(8) NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`thanks_time` int(11) NOT NULL DEFAULT '0'
);

## `phpbb_thanks`


## --------------------------------------------------------

## `phpbb_themes`

CREATE TABLE `phpbb_themes` (
	`themes_id` mediumint(8) unsigned NOT NULL auto_increment,
	`template_name` varchar(30) NOT NULL DEFAULT '',
	`style_name` varchar(30) NOT NULL DEFAULT '',
	`head_stylesheet` varchar(100) DEFAULT NULL,
	`body_background` varchar(100) DEFAULT NULL,
	`body_bgcolor` varchar(6) DEFAULT NULL,
	`tr_class1` varchar(25) DEFAULT NULL,
	`tr_class2` varchar(25) DEFAULT NULL,
	`tr_class3` varchar(25) DEFAULT NULL,
	`td_class1` varchar(25) DEFAULT NULL,
	`td_class2` varchar(25) DEFAULT NULL,
	`td_class3` varchar(25) DEFAULT NULL,
	PRIMARY KEY (`themes_id`)
);

## `phpbb_themes`


## --------------------------------------------------------

## `phpbb_title_infos`

CREATE TABLE `phpbb_title_infos` (
	`id` int(11) NOT NULL auto_increment,
	`title_info` varchar(255) NOT NULL DEFAULT '',
	`date_format` varchar(25) DEFAULT NULL,
	`admin_auth` tinyint(1) DEFAULT '0',
	`mod_auth` tinyint(1) DEFAULT '0',
	`poster_auth` tinyint(1) DEFAULT '0',
	UNIQUE KEY `id` (`id`)
);

## `phpbb_title_infos`


## --------------------------------------------------------

## `phpbb_topic_view`

CREATE TABLE `phpbb_topic_view` (
	`topic_id` mediumint(8) NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`view_time` int(11) NOT NULL DEFAULT '0',
	`view_count` int(11) NOT NULL DEFAULT '0'
);

## `phpbb_topic_view`


## --------------------------------------------------------

## `phpbb_topics`

CREATE TABLE `phpbb_topics` (
	`topic_id` mediumint(8) unsigned NOT NULL auto_increment,
	`forum_id` smallint(8) unsigned NOT NULL DEFAULT '0',
	`topic_title` varchar(255) NOT NULL DEFAULT '',
	`topic_title_clean` varchar(255) NOT NULL DEFAULT '',
	`topic_ftitle_clean` varchar(255) NOT NULL DEFAULT '',
	`topic_tags` varchar(255) NOT NULL DEFAULT '',
	`topic_desc` varchar(255) DEFAULT '',
	`topic_similar_topics` varchar(255) NOT NULL DEFAULT '',
	`topic_poster` mediumint(8) NOT NULL DEFAULT '0',
	`topic_time` int(11) unsigned NOT NULL DEFAULT '0',
	`topic_views` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_replies` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_status` tinyint(3) NOT NULL DEFAULT '0',
	`topic_type` tinyint(3) NOT NULL DEFAULT '0',
	`poll_title` varchar(255) DEFAULT '' NOT NULL,
	`poll_start` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_length` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_max_options` tinyint(4) DEFAULT '1' NOT NULL,
	`poll_last_vote` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_vote_change` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`topic_first_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_first_post_time` int(11) unsigned NOT NULL DEFAULT '0',
	`topic_first_poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_first_poster_name` varchar(255) NOT NULL DEFAULT '',
	`topic_first_poster_color` varchar(16) NOT NULL DEFAULT '',
	`topic_last_post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_last_post_time` int(11) unsigned NOT NULL DEFAULT '0',
	`topic_last_poster_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_last_poster_name` varchar(255) NOT NULL DEFAULT '',
	`topic_last_poster_color` varchar(16) NOT NULL DEFAULT '',
	`topic_moved_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_attachment` tinyint(1) NOT NULL DEFAULT '0',
	`title_compl_infos` varchar(255) DEFAULT NULL,
	`news_id` int(10) unsigned NOT NULL DEFAULT '0',
	`topic_calendar_time` int(11) DEFAULT NULL,
	`topic_calendar_duration` int(11) DEFAULT NULL,
	`topic_rating` double unsigned NOT NULL DEFAULT '0',
	`topic_show_portal` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`topic_id`),
	KEY `topic_calendar_time` (`topic_calendar_time`),
	KEY `news_id` (`news_id`),
	KEY `forum_id` (`forum_id`),
	KEY `topic_moved_id` (`topic_moved_id`),
	KEY `topic_status` (`topic_status`),
	KEY `topic_type` (`topic_type`)
) ENGINE = MyISAM;

ALTER TABLE `phpbb_topics` ADD FULLTEXT (topic_title);
## ALTER TABLE `phpbb_topics` ADD FULLTEXT (topic_desc);


## `phpbb_topics`


## --------------------------------------------------------

## `phpbb_topics_tags_list`

CREATE TABLE `phpbb_topics_tags_list` (
	`tag_text` varchar(50) binary NOT NULL DEFAULT '',
	`tag_id` mediumint(8) unsigned NOT NULL auto_increment,
	`tag_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`tag_text`),
	KEY `tag_id` (`tag_id`)
);

## `phpbb_topics_tags_list`


## --------------------------------------------------------

## `phpbb_topics_tags_match`

CREATE TABLE `phpbb_topics_tags_match` (
	`tag_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	KEY `tag_id` (`tag_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_topics_tags_match`


## --------------------------------------------------------

## `phpbb_topics_watch`

CREATE TABLE `phpbb_topics_watch` (
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`notify_status` tinyint(1) NOT NULL DEFAULT '0',
	KEY `topic_id` (`topic_id`),
	KEY `user_id` (`user_id`),
	KEY `notify_status` (`notify_status`)
);

## `phpbb_topics_watch`


## --------------------------------------------------------

## `phpbb_upi2db_always_read`

CREATE TABLE `phpbb_upi2db_always_read` (
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`last_update` int(11) NOT NULL DEFAULT '0',
	KEY `forum_id` (`forum_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_upi2db_always_read`


## --------------------------------------------------------

## `phpbb_upi2db_last_posts`

CREATE TABLE `phpbb_upi2db_last_posts` (
	`post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`poster_id` mediumint(8) NOT NULL DEFAULT '0',
	`post_time` int(11) NOT NULL DEFAULT '0',
	`post_edit_time` int(11) NOT NULL DEFAULT '0',
	`topic_type` tinyint(1) NOT NULL DEFAULT '0',
	`post_edit_by` mediumint(8) NOT NULL DEFAULT '0',
	PRIMARY KEY (`post_id`)
);

## `phpbb_upi2db_last_posts`


## --------------------------------------------------------

## `phpbb_upi2db_unread_posts`

CREATE TABLE `phpbb_upi2db_unread_posts` (
	`post_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`status` tinyint(1) NOT NULL DEFAULT '0',
	`topic_type` tinyint(1) NOT NULL DEFAULT '0',
	`last_update` int(11) NOT NULL DEFAULT '0',
	KEY `post_id` (`post_id`),
	KEY `user_id` (`user_id`)
);

## `phpbb_upi2db_unread_posts`


## --------------------------------------------------------

## `phpbb_user_group`

CREATE TABLE `phpbb_user_group` (
	`group_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`group_leader` tinyint(1) unsigned DEFAULT '0' NOT NULL,
	`user_pending` tinyint(1) DEFAULT '1' NOT NULL,
	KEY `group_id` (`group_id`),
	KEY `user_id` (`user_id`),
	KEY `group_leader` (`group_leader`)
);

## `phpbb_user_group`


## --------------------------------------------------------

## `phpbb_users`

CREATE TABLE `phpbb_users` (
	`user_id` mediumint(8) NOT NULL DEFAULT '0',
	`user_active` tinyint(1) DEFAULT '1',
	`user_mask` tinyint(1) DEFAULT '0',
	`user_permissions` mediumtext NOT NULL,
	`user_perm_from` mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	`username` varchar(36) NOT NULL DEFAULT '',
	`username_clean` varchar(255) NOT NULL DEFAULT '',
	`user_first_name` varchar(255) NOT NULL DEFAULT '',
	`user_last_name` varchar(255) NOT NULL DEFAULT '',
	`user_password` varchar(40) NOT NULL DEFAULT '',
	`user_newpasswd` varchar(40) NOT NULL DEFAULT '',
	`user_passchg` int(11) UNSIGNED DEFAULT '0' NOT NULL,
	`user_pass_convert` tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	`user_form_salt` varchar(32) DEFAULT '' NOT NULL,
	`user_session_time` int(11) NOT NULL DEFAULT '0',
	`user_session_page` varchar(255) NOT NULL DEFAULT '',
	`user_browser` varchar(255) NOT NULL DEFAULT '',
	`user_lastvisit` int(11) NOT NULL DEFAULT '0',
	`user_regdate` int(11) NOT NULL DEFAULT '0',
	`user_type` tinyint(2) DEFAULT '0' NOT NULL,
	`user_level` tinyint(4) DEFAULT '0',
	`user_cms_level` tinyint(4) DEFAULT '0',
	`user_posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_timezone` decimal(5,2) NOT NULL DEFAULT '0.00',
	`user_style` tinyint(4) DEFAULT NULL,
	`user_lang` varchar(255) DEFAULT NULL,
	`user_dateformat` varchar(14) NOT NULL DEFAULT 'd M Y H:i',
	`user_new_privmsg` smallint(5) unsigned NOT NULL DEFAULT '0',
	`user_unread_privmsg` smallint(5) unsigned NOT NULL DEFAULT '0',
	`user_last_privmsg` int(11) NOT NULL DEFAULT '0',
	`user_private_chat_alert` varchar(255) NOT NULL DEFAULT '0',
	`user_emailtime` int(11) DEFAULT NULL,
	`user_viewemail` tinyint(1) DEFAULT NULL,
	`user_profile_view_popup` tinyint(1) DEFAULT '0',
	`user_attachsig` tinyint(1) NOT NULL DEFAULT '1',
	`user_setbm` tinyint(1) NOT NULL DEFAULT '0',
	`user_options` int(11) UNSIGNED DEFAULT '895' NOT NULL,
	`user_allowhtml` tinyint(1) DEFAULT '1',
	`user_allowbbcode` tinyint(1) DEFAULT '1',
	`user_allowsmile` tinyint(1) DEFAULT '1',
	`user_allowavatar` tinyint(1) NOT NULL DEFAULT '1',
	`user_allow_pm` tinyint(1) NOT NULL DEFAULT '1',
	`user_allow_pm_in` tinyint(1) NOT NULL DEFAULT '1',
	`user_allow_mass_email` tinyint(1) NOT NULL DEFAULT '1',
	`user_allow_viewonline` tinyint(1) NOT NULL DEFAULT '1',
	`user_notify` tinyint(1) NOT NULL DEFAULT '1',
	`user_notify_pm` tinyint(1) NOT NULL DEFAULT '0',
	`user_popup_pm` tinyint(1) NOT NULL DEFAULT '0',
	`user_rank` int(11) DEFAULT '0',
	`user_rank2` int(11) DEFAULT '-1',
	`user_rank3` int(11) DEFAULT '-2',
	`user_rank4` int(11) DEFAULT '-2',
	`user_rank5` int(11) DEFAULT '-2',
	`user_avatar` varchar(100) DEFAULT NULL,
	`user_avatar_type` tinyint(4) NOT NULL DEFAULT '0',
	`user_email` varchar(255) DEFAULT NULL,
	`user_email_hash` bigint(20) DEFAULT '0' NOT NULL,
	`user_website` varchar(100) DEFAULT NULL,
	`user_from` varchar(100) DEFAULT NULL,
	`user_sig` TEXT NOT NULL,
	`user_aim` varchar(255) DEFAULT '' NOT NULL,
	`user_icq` varchar(15) DEFAULT '' NOT NULL,
	`user_jabber` varchar(255) DEFAULT '' NOT NULL,
	`user_msnm` varchar(255) DEFAULT '' NOT NULL,
	`user_skype` varchar(255) DEFAULT '' NOT NULL,
	`user_yim` varchar(255) DEFAULT '' NOT NULL,
	`user_facebook` varchar(255) DEFAULT '' NOT NULL,
	`user_twitter` varchar(255) DEFAULT '' NOT NULL,
	`user_occ` varchar(255) DEFAULT '' NOT NULL,
	`user_interests` varchar(255) DEFAULT '' NOT NULL,
	`user_actkey` varchar(32) DEFAULT NULL,
	`user_birthday` int(11) NOT NULL DEFAULT '999999',
	`user_birthday_y` varchar(4) NOT NULL DEFAULT '',
	`user_birthday_m` varchar(2) NOT NULL DEFAULT '',
	`user_birthday_d` varchar(2) NOT NULL DEFAULT '',
	`user_next_birthday_greeting` int(11) NOT NULL DEFAULT '0',
	`user_sub_forum` tinyint(1) NOT NULL DEFAULT '1',
	`user_split_cat` tinyint(1) NOT NULL DEFAULT '1',
	`user_last_topic_title` tinyint(1) NOT NULL DEFAULT '1',
	`user_sub_level_links` tinyint(1) NOT NULL DEFAULT '2',
	`user_display_viewonline` tinyint(1) NOT NULL DEFAULT '2',
	`user_color_group` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`user_color` varchar(16) NOT NULL DEFAULT '',
	`user_gender` tinyint(4) NOT NULL DEFAULT '0',
	`user_lastlogon` int(11) NOT NULL DEFAULT '0',
	`user_totaltime` int(11) DEFAULT '0',
	`user_totallogon` int(11) DEFAULT '0',
	`user_totalpages` int(11) DEFAULT '0',
	`user_calendar_display_open` tinyint(1) NOT NULL DEFAULT '0',
	`user_calendar_header_cells` tinyint(1) NOT NULL DEFAULT '0',
	`user_calendar_week_start` tinyint(1) NOT NULL DEFAULT '1',
	`user_calendar_nb_row` tinyint(2) unsigned NOT NULL DEFAULT '5',
	`user_calendar_birthday` tinyint(1) NOT NULL DEFAULT '1',
	`user_calendar_forum` tinyint(1) NOT NULL DEFAULT '1',
	`user_warnings` smallint(5) DEFAULT '0',
	`user_time_mode` tinyint(4) NOT NULL DEFAULT '5',
	`user_dst_time_lag` tinyint(4) NOT NULL DEFAULT '60',
	`user_pc_timeOffsets` varchar(11) NOT NULL DEFAULT '0',
	`user_registered_ip` varchar(40) DEFAULT NULL,
	`user_registered_hostname` varchar(255) DEFAULT NULL,
	`user_profile_view` smallint(5) NOT NULL DEFAULT '0',
	`user_last_profile_view` int(11) NOT NULL DEFAULT '0',
	`user_topics_per_page` varchar(5) DEFAULT NULL,
	`user_hot_threshold` varchar(5) DEFAULT NULL,
	`user_posts_per_page` varchar(5) DEFAULT NULL,
	`user_allowswearywords` tinyint(1) NOT NULL DEFAULT '0',
	`user_showavatars` tinyint(1) DEFAULT '1',
	`user_showsignatures` tinyint(1) DEFAULT '1',
	`user_login_attempts` tinyint(4) DEFAULT '0' NOT NULL,
	`user_last_login_attempt` int(11) NOT NULL DEFAULT '0',
	`user_sudoku_playing` int(1) NOT NULL DEFAULT '0',
	`user_from_flag` varchar(30) DEFAULT NULL,
	`user_phone` varchar(255) DEFAULT NULL,
	`user_selfdes` TEXT NOT NULL,
	`user_upi2db_which_system` tinyint(1) NOT NULL DEFAULT '1',
	`user_upi2db_disable` tinyint(1) NOT NULL DEFAULT '0',
	`user_upi2db_datasync` int(11) NOT NULL DEFAULT '0',
	`user_upi2db_new_word` tinyint(1) NOT NULL DEFAULT '1',
	`user_upi2db_edit_word` tinyint(1) NOT NULL DEFAULT '1',
	`user_upi2db_unread_color` tinyint(1) NOT NULL DEFAULT '1',
	`user_personal_pics_count` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`user_id`),
	KEY `user_session_time` (`user_session_time`)
);

## `phpbb_users`


## --------------------------------------------------------

## `phpbb_words`

CREATE TABLE `phpbb_words` (
	`word_id` mediumint(8) unsigned NOT NULL auto_increment,
	`word` char(100) NOT NULL DEFAULT '',
	`replacement` char(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`word_id`)
);

## `phpbb_words`


## --------------------------------------------------------

## `phpbb_xs_news`

CREATE TABLE `phpbb_xs_news` (
	`news_id` mediumint(8) unsigned NOT NULL auto_increment,
	`news_date` int(11) NOT NULL DEFAULT '0',
	`news_text` TEXT NOT NULL,
	`news_display` tinyint(1) NOT NULL DEFAULT '1',
	`news_smilies` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`news_id`)
);

## `phpbb_xs_news`


## --------------------------------------------------------

## `phpbb_xs_news_xml`

CREATE TABLE `phpbb_xs_news_xml` (
	`xml_id` mediumint(8) NOT NULL auto_increment,
	`xml_title` varchar(255) NOT NULL DEFAULT '',
	`xml_show` tinyint(1) NOT NULL DEFAULT '0',
	`xml_feed` TEXT NOT NULL,
	`xml_is_feed` tinyint(1) NOT NULL DEFAULT '1',
	`xml_width` varchar(4) NOT NULL DEFAULT '98%',
	`xml_height` char(3) NOT NULL DEFAULT '20',
	`xml_font` char(3) NOT NULL DEFAULT '0',
	`xml_speed` char(2) NOT NULL DEFAULT '3',
	`xml_direction` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`xml_id`)
);

## `phpbb_xs_news_xml`


########################################
##             NEW ADDONS             ##
########################################

## Cracker Tracker - BEGIN
ALTER TABLE `phpbb_users` ADD `ct_search_time` INT( 11 ) NULL DEFAULT 1 AFTER `user_newpasswd`;
ALTER TABLE `phpbb_users` ADD `ct_search_count` MEDIUMINT( 8 ) NULL DEFAULT 1 AFTER `ct_search_time`;
ALTER TABLE `phpbb_users` ADD `ct_last_post` INT( 11 ) NULL DEFAULT 1 AFTER `ct_search_count`;
ALTER TABLE `phpbb_users` ADD `ct_post_counter` MEDIUMINT( 8 ) NULL DEFAULT 1 AFTER `ct_last_post`;
ALTER TABLE `phpbb_users` ADD `ct_enable_ip_warn` TINYINT( 1 ) NULL DEFAULT 1 AFTER `ct_post_counter`;
ALTER TABLE `phpbb_users` ADD `ct_last_used_ip` VARCHAR( 16 ) NULL DEFAULT '0.0.0.0' AFTER `ct_enable_ip_warn`;
ALTER TABLE `phpbb_users` ADD `ct_last_ip` VARCHAR( 16 ) NULL DEFAULT '0.0.0.0' AFTER `ct_last_used_ip`;
ALTER TABLE `phpbb_users` ADD `ct_global_msg_read` TINYINT( 1 ) NULL DEFAULT 0 AFTER `ct_last_ip`;
ALTER TABLE `phpbb_users` ADD `ct_miserable_user` TINYINT( 1 ) NULL DEFAULT 0 AFTER `ct_global_msg_read`;

CREATE TABLE `phpbb_ctracker_filechk` (
	`filepath` TEXT NOT NULL,
	`hash` varchar(32) DEFAULT NULL
	);

CREATE TABLE `phpbb_ctracker_filescanner` (
	`id` smallint(5) NOT NULL,
	`filepath` TEXT NOT NULL,
	`safety` smallint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	);

CREATE TABLE `phpbb_ctracker_ipblocker` (
	`id` mediumint(8) unsigned NOT NULL,
	`ct_blocker_value` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`id`)
	);

CREATE TABLE `phpbb_ctracker_loginhistory` (
	`ct_user_id` int(10) DEFAULT NULL,
	`ct_login_ip` varchar(40) DEFAULT NULL,
	`ct_login_time` int(11) NOT NULL DEFAULT '0'
	);
## Cracker Tracker - END


## DRAFTS - BEGIN

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

## DRAFTS - END


## FRIENDS AND FOES - BEGIN

CREATE TABLE phpbb_zebra (
	user_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	zebra_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
	friend tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	foe tinyint(1) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (user_id, zebra_id)
);

## FRIENDS AND FOES - END


## ICY PHOENIX LOGS - BEGIN

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

CREATE TABLE `phpbb_logs` (
	`log_id` int(11) unsigned NOT NULL auto_increment,
	`log_time` varchar(11) NOT NULL,
	`log_page` varchar(255) NOT NULL DEFAULT '',
	`log_user_id` int(10) NOT NULL,
	`log_action` varchar(60) NOT NULL DEFAULT '',
	`log_desc` varchar(255) NOT NULL DEFAULT '',
	`log_target` int(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`log_id`)
);

## ICY PHOENIX LOGS - END


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

CREATE TABLE `phpbb_digest_subscribed_forums` (
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT 0,
	`forum_id` SMALLINT(5) NOT NULL DEFAULT 0,
	UNIQUE user_id (user_id, forum_id)
);

## Digests - END


## Icy Phoenix CMS - BEGIN

CREATE TABLE `phpbb_cms_block_position` (
	`bpid` int(10) NOT NULL auto_increment,
	`layout` int(10) NOT NULL DEFAULT '1',
	`pkey` varchar(30) NOT NULL DEFAULT '',
	`bposition` char(2) NOT NULL DEFAULT '',
	PRIMARY KEY (`bpid`)
);

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

CREATE TABLE `phpbb_cms_block_variable` (
	`bvid` int(10) NOT NULL auto_increment,
	`bid` int(10) NOT NULL DEFAULT '0',
	`label` varchar(30) NOT NULL DEFAULT '',
	`sub_label` varchar(255) DEFAULT NULL,
	`config_name` varchar(30) NOT NULL DEFAULT '',
	`field_options` varchar(255) DEFAULT NULL,
	`field_values` varchar(255) DEFAULT NULL,
	`type` tinyint(1) NOT NULL DEFAULT '0',
	`block` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`bvid`)
);

CREATE TABLE `phpbb_cms_blocks` (
	`bid` int(10) NOT NULL auto_increment,
	`bs_id` int(10) UNSIGNED NOT NULL,
	`block_cms_id` int(10) UNSIGNED NOT NULL,
	`layout` int(10) NOT NULL DEFAULT '0',
	`layout_special` int(10) NOT NULL DEFAULT '0',
	`title` varchar(60) NOT NULL DEFAULT '',
	`bposition` char(2) NOT NULL DEFAULT '',
	`weight` int(10) NOT NULL DEFAULT '1',
	`active` tinyint(1) NOT NULL DEFAULT '1',
	`border` tinyint(1) NOT NULL DEFAULT '1',
	`titlebar` tinyint(1) NOT NULL DEFAULT '1',
	`background` tinyint(1) NOT NULL DEFAULT '1',
	`local` tinyint(1) NOT NULL DEFAULT '0',
	`edit_auth` tinyint(1) NOT NULL DEFAULT '5',
	PRIMARY KEY (`bid`)
);

CREATE TABLE `phpbb_cms_config` (
	`id` int(10) unsigned NOT NULL auto_increment,
	`bid` int(10) NOT NULL DEFAULT '0',
	`config_name` varchar(255) NOT NULL DEFAULT '',
	`config_value` varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
);

CREATE TABLE `phpbb_cms_layout` (
	`lid` int(10) unsigned NOT NULL auto_increment,
	`name` varchar(100) NOT NULL DEFAULT '',
	`filename` varchar(100) NOT NULL DEFAULT '',
	`template` varchar(100) NOT NULL DEFAULT '',
	`layout_cms_id` int(10) UNSIGNED NOT NULL,
	`global_blocks` tinyint(1) NOT NULL DEFAULT '0',
	`page_nav` tinyint(1) NOT NULL DEFAULT '1',
	`config_vars` TEXT NOT NULL,
	`view` tinyint(1) NOT NULL DEFAULT '0',
	`edit_auth` tinyint(1) NOT NULL DEFAULT '5',
	`groups` TINYTEXT NOT NULL,
	PRIMARY KEY (`lid`)
);

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
	`edit_auth` tinyint(1) NOT NULL DEFAULT '5',
	`groups` TINYTEXT NOT NULL,
	PRIMARY KEY (`lsid`),
	UNIQUE KEY `page_id` (`page_id`)
);

CREATE TABLE `phpbb_cms_nav_menu` (
	`menu_item_id` mediumint(8) unsigned NOT NULL auto_increment,
	`menu_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`menu_parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`cat_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`cat_parent_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`menu_default` mediumint(8) unsigned NOT NULL DEFAULT '0',
	`menu_status` tinyint(1) NOT NULL DEFAULT '0',
	`menu_order` smallint(5) NOT NULL DEFAULT '0',
	`menu_icon` varchar(255) DEFAULT NULL,
	`menu_name_lang` varchar(150) DEFAULT NULL,
	`menu_name` varchar(150) DEFAULT NULL,
	`menu_desc` TEXT NOT NULL,
	`menu_link` varchar(255) DEFAULT NULL,
	`menu_link_external` tinyint(1) NOT NULL DEFAULT '0',
	`auth_view` tinyint(2) NOT NULL DEFAULT '0',
	`auth_view_group` smallint(5) NOT NULL DEFAULT '0',
	PRIMARY KEY (`menu_item_id`),
	KEY `cat_id` (`cat_id`)
);

## Icy Phoenix CMS - END


## AJAX Shoutbox - BEGIN

CREATE TABLE phpbb_ajax_shoutbox (
	shout_id MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id MEDIUMINT(8) NOT NULL,
	shouter_name VARCHAR(30) NOT NULL DEFAULT 'guest',
	shout_text TEXT NOT NULL,
	shouter_ip VARCHAR(40) NOT NULL DEFAULT '',
	shout_time INT(11) NOT NULL,
	shout_room VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY ( shout_id )
);


CREATE TABLE `phpbb_ajax_shoutbox_sessions` (
	`session_id` int(10) NOT NULL,
	`session_user_id` mediumint(8) NOT NULL DEFAULT '0',
	`session_username` varchar(25) NOT NULL DEFAULT '',
	`session_ip` varchar(40) NOT NULL DEFAULT '0',
	`session_start` int(11) NOT NULL DEFAULT '0',
	`session_time` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`session_id`)
);

## AJAX Shoutbox - END


## CASH Mod - BEGIN

CREATE TABLE phpbb_cash (
	cash_id smallint(6) NOT NULL auto_increment,
	cash_order smallint(6) NOT NULL DEFAULT '0',
	cash_settings smallint(4) NOT NULL DEFAULT '3313',
	cash_dbfield varchar(64) NOT NULL DEFAULT '',
	cash_name varchar(64) NOT NULL DEFAULT 'GP',
	cash_default int(11) NOT NULL DEFAULT '0',
	cash_decimals tinyint(2) NOT NULL DEFAULT '0',
	cash_imageurl varchar(255) NOT NULL DEFAULT '',
	cash_exchange int(11) NOT NULL DEFAULT '1',
	cash_perpost int(11) NOT NULL DEFAULT '25',
	cash_postbonus int(11) NOT NULL DEFAULT '2',
	cash_perreply int(11) NOT NULL DEFAULT '25',
	cash_perthanks int(11) NOT NULL DEFAULT '5',
	cash_maxearn int(11) NOT NULL DEFAULT '75',
	cash_perpm int(11) NOT NULL DEFAULT '0',
	cash_perchar int(11) NOT NULL DEFAULT '20',
	cash_allowance tinyint(1) NOT NULL DEFAULT '0',
	cash_allowanceamount int(11) NOT NULL DEFAULT '0',
	cash_allowancetime tinyint(2) NOT NULL DEFAULT '2',
	cash_allowancenext int(11) NOT NULL DEFAULT '0',
	cash_forumlist varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (cash_id)
);

CREATE TABLE phpbb_cash_events (
	event_name varchar(32) NOT NULL DEFAULT '',
	event_data varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (event_name)
);

CREATE TABLE phpbb_cash_exchange (
	ex_cash_id1 int(11) NOT NULL DEFAULT '0',
	ex_cash_id2 int(11) NOT NULL DEFAULT '0',
	ex_cash_enabled int(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (ex_cash_id1,ex_cash_id2)
);

CREATE TABLE phpbb_cash_groups (
	group_id mediumint(6) NOT NULL DEFAULT '0',
	group_type tinyint(2) NOT NULL DEFAULT '0',
	cash_id smallint(6) NOT NULL DEFAULT '0',
	cash_perpost int(11) NOT NULL DEFAULT '0',
	cash_postbonus int(11) NOT NULL DEFAULT '0',
	cash_perreply int(11) NOT NULL DEFAULT '0',
	cash_perchar int(11) NOT NULL DEFAULT '0',
	cash_maxearn int(11) NOT NULL DEFAULT '0',
	cash_perpm int(11) NOT NULL DEFAULT '0',
	cash_allowance tinyint(1) NOT NULL DEFAULT '0',
	cash_allowanceamount int(11) NOT NULL DEFAULT '0',
	cash_allowancetime tinyint(2) NOT NULL DEFAULT '2',
	cash_allowancenext int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (group_id,group_type,cash_id)
);

CREATE TABLE phpbb_cash_log (
	log_id int(11) NOT NULL auto_increment,
	log_time int(11) NOT NULL DEFAULT '0',
	log_type smallint(6) NOT NULL DEFAULT '0',
	log_action varchar(255) NOT NULL DEFAULT '',
	log_text varchar(255) NOT NULL DEFAULT '',
	PRIMARY KEY (log_id)
);

## CASH Mod - END


## DOWNLOADS - BEGIN

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
	user_ip VARCHAR(40) DEFAULT '' NOT NULL,
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
PRIMARY KEY (config_name)
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
ALTER TABLE phpbb_users ADD COLUMN user_dl_note_type TINYINT(1) DEFAULT '1' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_sort_fix TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_sort_opt TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_dl_sort_dir TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_new_download TINYINT(1) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_traffic BIGINT(20) DEFAULT '0' NOT NULL;
ALTER TABLE phpbb_users ADD COLUMN user_download_counter MEDIUMINT(8) DEFAULT '0' NOT NULL;

## DOWNLOADS - END

## EVENT REG - BEGIN
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
	PRIMARY KEY (reg_id),
	KEY `topic_id` (topic_id)
);

ALTER TABLE phpbb_topics ADD topic_reg TINYINT(1) DEFAULT '0' NOT NULL AFTER topic_calendar_duration;
## EVENT REG - END

## TICKETS - BEGIN
CREATE TABLE phpbb_tickets_cat (
	ticket_cat_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	ticket_cat_title VARCHAR(255) NOT NULL DEFAULT '',
	ticket_cat_des TEXT NOT NULL,
	ticket_cat_emails TEXT NOT NULL,
	PRIMARY KEY (ticket_cat_id)
);
## TICKETS - END

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
