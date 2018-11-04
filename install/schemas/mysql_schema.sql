SET default_storage_engine = MYISAM;

## `phpbb_acronyms`

CREATE TABLE `phpbb_acronyms` (
	`acronym_id` MEDIUMINT(9) NOT NULL auto_increment,
	`acronym` VARCHAR(80) NOT NULL DEFAULT '',
	`description` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`acronym_id`)
);

## `phpbb_acronyms`


## --------------------------------------------------------

## `phpbb_adminedit`

CREATE TABLE `phpbb_adminedit` (
	`edit_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`edituser` CHAR(100) NOT NULL DEFAULT '',
	`editok` CHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`edit_id`)
);

## `phpbb_adminedit`


## --------------------------------------------------------

## `phpbb_ads`

CREATE TABLE `phpbb_ads` (
	`ad_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`ad_title` VARCHAR(255) NOT NULL,
	`ad_text` TEXT NOT NULL,
	`ad_position` VARCHAR(255) NOT NULL,
	`ad_auth` TINYINT(1) NOT NULL default '0',
	`ad_format` TINYINT(1) NOT NULL default '0',
	`ad_active` TINYINT(1) NOT NULL default '0',
	PRIMARY KEY (`ad_id`)
);

## `phpbb_ads`


## --------------------------------------------------------

## `phpbb_attach_quota`

CREATE TABLE `phpbb_attach_quota` (
	`user_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`group_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`quota_type` SMALLINT(2) NOT NULL DEFAULT '0',
	`quota_limit_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	KEY `quota_type` (`quota_type`)
);

## `phpbb_attach_quota`


## --------------------------------------------------------

## `phpbb_attachments`

CREATE TABLE `phpbb_attachments` (
	`attach_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`privmsgs_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_id_1` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`user_id_2` MEDIUMINT(8) NOT NULL DEFAULT '0',
	KEY `attach_id_post_id` (`attach_id`,`post_id`),
	KEY `attach_id_privmsgs_id` (`attach_id`,`privmsgs_id`),
	KEY `post_id` (`post_id`),
	KEY `privmsgs_id` (`privmsgs_id`)
);

## `phpbb_attachments`


## --------------------------------------------------------

## `phpbb_attachments_desc`

CREATE TABLE `phpbb_attachments_desc` (
	`attach_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`physical_filename` VARCHAR(255) NOT NULL DEFAULT '',
	`real_filename` VARCHAR(255) NOT NULL DEFAULT '',
	`download_count` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`comment` VARCHAR(255) DEFAULT NULL,
	`extension` VARCHAR(100) DEFAULT NULL,
	`mimetype` VARCHAR(100) DEFAULT NULL,
	`filesize` INT(20) NOT NULL DEFAULT '0',
	`filetime` INT(11) NOT NULL DEFAULT '0',
	`thumbnail` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`attach_id`),
	KEY `filetime` (`filetime`),
	KEY `physical_filename` (`physical_filename`(10)),
	KEY `filesize` (`filesize`)
);

## `phpbb_attachments_desc`


## --------------------------------------------------------

## `phpbb_attachments_stats`

CREATE TABLE `phpbb_attachments_stats` (
	`attach_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`user_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`user_browser` VARCHAR(255) NOT NULL DEFAULT '',
	`download_time` INT(11) NOT NULL DEFAULT '0',
	KEY `attach_id` (`attach_id`)
);

## `phpbb_attachments_stats`


## --------------------------------------------------------

## `phpbb_auth_access`

CREATE TABLE `phpbb_auth_access` (
	`group_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`forum_id` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`auth_view` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_read` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_post` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_reply` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_edit` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_delete` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_sticky` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_announce` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_globalannounce` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_news` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_cal` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_vote` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_pollcreate` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_attachments` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_download` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_ban` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_greencard` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_bluecard` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_rate` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_mod` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `group_id` (`group_id`),
	KEY `forum_id` (`forum_id`)
);

## `phpbb_auth_access`


## --------------------------------------------------------

## `phpbb_autolinks`

CREATE TABLE `phpbb_autolinks` (
	`link_id` MEDIUMINT(5) unsigned NOT NULL auto_increment,
	`link_keyword` VARCHAR(50) NOT NULL DEFAULT '',
	`link_title` VARCHAR(50) NOT NULL DEFAULT '',
	`link_url` VARCHAR(200) NOT NULL DEFAULT '',
	`link_comment` VARCHAR(200) NOT NULL DEFAULT '',
	`link_style` VARCHAR(200) NOT NULL DEFAULT '',
	`link_forum` TINYINT(1) NOT NULL DEFAULT '0',
	`link_int` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `link_id` (`link_id`)
);

## `phpbb_autolinks`


## --------------------------------------------------------

## `phpbb_banlist`

CREATE TABLE `phpbb_banlist` (
	`ban_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`ban_userid` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`ban_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`ban_email` VARCHAR(255) DEFAULT NULL,
	`ban_start` INT(11) DEFAULT NULL,
	`ban_end` INT(11) DEFAULT NULL,
	`ban_by_userid` MEDIUMINT(8) DEFAULT NULL,
	`ban_priv_reason` TEXT NOT NULL,
	`ban_pub_reason_mode` TINYINT(1) DEFAULT NULL,
	`ban_pub_reason` TEXT NOT NULL,
	PRIMARY KEY (`ban_id`),
	KEY `ban_ip_user_id` (`ban_ip`,`ban_userid`)
);

## `phpbb_banlist`


## --------------------------------------------------------

## `phpbb_bbcodes`

CREATE TABLE `phpbb_bbcodes` (
	bbcode_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
	bbcode_tag VARCHAR(16) DEFAULT '' NOT NULL,
	bbcode_helpline VARCHAR(255) DEFAULT '' NOT NULL,
	display_on_posting TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	bbcode_match TEXT NOT NULL,
	bbcode_tpl MEDIUMTEXT NOT NULL,
	first_pass_match MEDIUMTEXT NOT NULL,
	first_pass_replace MEDIUMTEXT NOT NULL,
	second_pass_match MEDIUMTEXT NOT NULL,
	second_pass_replace MEDIUMTEXT NOT NULL,
	PRIMARY KEY (bbcode_id),
	KEY display_on_post (display_on_posting)
);

## `phpbb_bbcodes`


## --------------------------------------------------------

## `phpbb_bookmarks`

CREATE TABLE `phpbb_bookmarks` (
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	KEY `topic_id` (`topic_id`),
	KEY `user_id` (`user_id`)
);

## `phpbb_bookmarks`


## --------------------------------------------------------

## `phpbb_bots`

CREATE TABLE phpbb_bots (
	bot_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
	bot_active TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	bot_name VARCHAR(255) DEFAULT '' NOT NULL,
	bot_color VARCHAR(255) DEFAULT '' NOT NULL,
	bot_agent VARCHAR(255) DEFAULT '' NOT NULL,
	bot_ip VARCHAR(255) DEFAULT '' NOT NULL,
	bot_last_visit VARCHAR(11) DEFAULT '0' NOT NULL,
	bot_visit_counter MEDIUMINT(8) DEFAULT '0' NOT NULL,
	PRIMARY KEY (bot_id),
	KEY bot_name (bot_name),
	KEY bot_active (bot_active)
);

## `phpbb_bots`


## --------------------------------------------------------

## `phpbb_config`

CREATE TABLE `phpbb_config` (
	`config_name` VARCHAR(255) NOT NULL DEFAULT '',
	`config_value` TEXT NOT NULL,
	PRIMARY KEY (`config_name`)
);

## `phpbb_config`


## --------------------------------------------------------

## `phpbb_confirm`

CREATE TABLE `phpbb_confirm` (
	confirm_id CHAR(32) DEFAULT '' NOT NULL,
	session_id CHAR(32) DEFAULT '' NOT NULL,
	confirm_type TINYINT(3) DEFAULT '0' NOT NULL,
	code VARCHAR(8) DEFAULT '' NOT NULL,
	seed INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (session_id, confirm_id),
	KEY confirm_type (confirm_type)
);

## `phpbb_confirm`


## --------------------------------------------------------

## `phpbb_disallow`

CREATE TABLE `phpbb_disallow` (
	`disallow_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`disallow_username` VARCHAR(25) NOT NULL DEFAULT '',
	PRIMARY KEY (`disallow_id`)
);

## `phpbb_disallow`


## --------------------------------------------------------

## `phpbb_extension_groups`

CREATE TABLE `phpbb_extension_groups` (
	`group_id` MEDIUMINT(8) NOT NULL auto_increment,
	`group_name` VARCHAR(20) NOT NULL DEFAULT '',
	`cat_id` TINYINT(2) NOT NULL DEFAULT '0',
	`allow_group` TINYINT(1) NOT NULL DEFAULT '0',
	`download_mode` TINYINT(1) unsigned NOT NULL DEFAULT '1',
	`upload_icon` VARCHAR(100) DEFAULT '',
	`max_filesize` INT(20) NOT NULL DEFAULT '0',
	`forum_permissions` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`group_id`)
);

## `phpbb_extension_groups`


## --------------------------------------------------------

## `phpbb_extensions`

CREATE TABLE `phpbb_extensions` (
	`ext_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`group_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`extension` VARCHAR(100) NOT NULL DEFAULT '',
	`comment` VARCHAR(100) DEFAULT NULL,
	PRIMARY KEY (`ext_id`)
);

## `phpbb_extensions`


## --------------------------------------------------------

## `phpbb_flags`

CREATE TABLE `phpbb_flags` (
	`flag_id` INT(10) NOT NULL auto_increment,
	`flag_name` VARCHAR(30) DEFAULT NULL,
	`flag_image` VARCHAR(30) DEFAULT NULL,
	PRIMARY KEY (`flag_id`)
);

## `phpbb_flags`


## --------------------------------------------------------

## `phpbb_forbidden_extensions`

CREATE TABLE `phpbb_forbidden_extensions` (
	`ext_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`extension` VARCHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`ext_id`)
);

## `phpbb_forbidden_extensions`


## --------------------------------------------------------

## `phpbb_force_read_users`

CREATE TABLE `phpbb_force_read_users` (
	`user` VARCHAR(255) NOT NULL DEFAULT '',
	`time` INT(10) NOT NULL DEFAULT '0'
);

## `phpbb_force_read_users`


## --------------------------------------------------------

## `phpbb_forum_prune`

CREATE TABLE `phpbb_forum_prune` (
	`prune_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`forum_id` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`prune_days` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`prune_freq` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`prune_id`),
	KEY `forum_id` (`forum_id`)
);

## `phpbb_forum_prune`


## --------------------------------------------------------

## `phpbb_forums`

CREATE TABLE `phpbb_forums` (
	`forum_id` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	`forum_type` TINYINT(4) DEFAULT '0' NOT NULL,
	`parent_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`main_type` CHAR(1) DEFAULT 'c',
	`left_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`right_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_parents` MEDIUMTEXT NOT NULL,
	`forum_name` VARCHAR(255) DEFAULT NULL,
	`forum_name_clean` VARCHAR(255) DEFAULT NULL,
	`forum_desc` TEXT NOT NULL,
	`forum_status` TINYINT(4) NOT NULL DEFAULT '0',
	`forum_order` MEDIUMINT(8) unsigned NOT NULL DEFAULT '1',
	`forum_posts` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_topics` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_last_topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_last_post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_last_poster_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_last_post_subject` VARCHAR(255) DEFAULT '' NOT NULL,
	`forum_last_post_time` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_last_poster_name` VARCHAR(255) DEFAULT '' NOT NULL,
	`forum_last_poster_color` VARCHAR(16) DEFAULT '' NOT NULL,
	`forum_postcount` TINYINT(1) NOT NULL DEFAULT '1',
	`forum_likes` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_notify` TINYINT(1) unsigned NOT NULL DEFAULT '1',
	`forum_limit_edit_time` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_similar_topics` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_topic_views` TINYINT(1) NOT NULL DEFAULT '1',
	`forum_tags` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_sort_box` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_kb_mode` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_index_icons` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_rules_switch` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`forum_rules` TEXT NOT NULL,
	`forum_rules_display_title` TINYINT(1) NOT NULL DEFAULT '1',
	`forum_rules_custom_title` VARCHAR(80) NOT NULL DEFAULT '',
	`forum_rules_in_viewforum` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`forum_rules_in_viewtopic` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`forum_rules_in_posting` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`forum_recurring_first_post` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`forum_link` VARCHAR(255) DEFAULT NULL,
	`forum_link_internal` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_link_hit_count` TINYINT(1) NOT NULL DEFAULT '0',
	`forum_link_hit` bigint(20) unsigned NOT NULL DEFAULT '0',
	`icon` VARCHAR(255) DEFAULT NULL,
	`prune_next` INT(11) DEFAULT NULL,
	`prune_enable` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_view` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_read` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_post` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_reply` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_edit` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_delete` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_sticky` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_announce` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_globalannounce` TINYINT(2) NOT NULL DEFAULT '3',
	`auth_news` TINYINT(2) NOT NULL DEFAULT '2',
	`auth_cal` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_vote` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_pollcreate` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_attachments` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_download` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_ban` TINYINT(2) NOT NULL DEFAULT '3',
	`auth_greencard` TINYINT(2) NOT NULL DEFAULT '5',
	`auth_bluecard` TINYINT(2) NOT NULL DEFAULT '1',
	`auth_rate` TINYINT(2) NOT NULL DEFAULT '-1',
	PRIMARY KEY (`forum_id`),
	KEY `forums_order` (`forum_order`),
	KEY `parent_id` (`parent_id`),
	KEY `forum_last_post_id` (`forum_last_post_id`)
);

## `phpbb_forums`


## --------------------------------------------------------

## `phpbb_forums_watch`

CREATE TABLE `phpbb_forums_watch` (
	`forum_id` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`notify_status` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `forum_id` (`forum_id`),
	KEY `user_id` (`user_id`),
	KEY `notify_status` (`notify_status`)
);

## `phpbb_forums_watch`


## --------------------------------------------------------

## `phpbb_groups`

CREATE TABLE `phpbb_groups` (
	`group_id` MEDIUMINT(8) NOT NULL auto_increment,
	`group_type` TINYINT(4) NOT NULL DEFAULT '1',
	`group_founder_manage` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_name` VARCHAR(255) DEFAULT '' NOT NULL,
	`group_description` TEXT NOT NULL,
	`group_display` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_moderator` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`group_single_user` TINYINT(1) NOT NULL DEFAULT '1',
	`group_rank` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_color` VARCHAR(16) DEFAULT '' NOT NULL,
	`group_legend` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_legend_order` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_sig_chars` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_receive_pm` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_message_limit` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_max_recipients` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_skip_auth` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`group_count` INT(4) unsigned DEFAULT '99999999',
	`group_count_max` INT(4) unsigned DEFAULT '99999999',
	`group_count_enable` SMALLINT(2) unsigned DEFAULT '0',
	`upi2db_on` TINYINT(1) NOT NULL DEFAULT '1',
	`upi2db_min_posts` MEDIUMINT(4) NOT NULL DEFAULT '0',
	`upi2db_min_regdays` MEDIUMINT(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`group_id`),
	KEY `group_legend_name` (`group_legend`, `group_name`),
	KEY `group_single_user` (`group_single_user`)
);

## `phpbb_groups`


## --------------------------------------------------------

## `phpbb_hacks_list`

CREATE TABLE `phpbb_hacks_list` (
	`hack_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`hack_name` VARCHAR(255) NOT NULL DEFAULT '',
	`hack_desc` VARCHAR(255) NOT NULL DEFAULT '',
	`hack_author` VARCHAR(255) NOT NULL DEFAULT '',
	`hack_author_email` VARCHAR(255) NOT NULL DEFAULT '',
	`hack_author_website` TINYTEXT,
	`hack_version` VARCHAR(32) NOT NULL DEFAULT '',
	`hack_hide` enum('Yes','No') NOT NULL DEFAULT 'No',
	`hack_download_url` TINYTEXT,
	`hack_file` VARCHAR(255) NOT NULL DEFAULT '',
	`hack_file_mtime` INT(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`hack_id`),
	UNIQUE KEY `hack_name` (`hack_name`),
	KEY `hack_file` (`hack_file`),
	KEY `hack_hide` (`hack_hide`)
);

## `phpbb_hacks_list`


## --------------------------------------------------------

## `phpbb_jr_admin_users`

CREATE TABLE `phpbb_jr_admin_users` (
	`user_id` MEDIUMINT(9) NOT NULL DEFAULT '0',
	`user_jr_admin` LONGTEXT NOT NULL,
	`start_date` INT(10) unsigned NOT NULL DEFAULT '0',
	`update_date` INT(10) unsigned NOT NULL DEFAULT '0',
	`admin_notes` TEXT NOT NULL,
	`notes_view` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`user_id`)
);

## `phpbb_jr_admin_users`


## --------------------------------------------------------

## `phpbb_kb_articles`

CREATE TABLE `phpbb_kb_articles` (
	`article_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`article_category_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`article_title` VARCHAR(255) binary NOT NULL DEFAULT '',
	`article_description` VARCHAR(255) binary NOT NULL DEFAULT '',
	`article_date` VARCHAR(255) binary NOT NULL DEFAULT '',
	`article_author_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`username` VARCHAR(255) DEFAULT NULL,
	`article_body` TEXT NOT NULL,
	`article_type` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`approved` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`views` bigint(8) NOT NULL DEFAULT '0',
	`article_rating` DOUBLE(6,4) NOT NULL DEFAULT '0.0000',
	`article_totalvotes` INT(255) NOT NULL DEFAULT '0',
	KEY `article_id` (`article_id`)
) AUTO_INCREMENT=2 ;

## `phpbb_kb_articles`


## --------------------------------------------------------

## `phpbb_kb_categories`

CREATE TABLE `phpbb_kb_categories` (
	`category_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`category_name` VARCHAR(255) binary NOT NULL DEFAULT '',
	`category_details` VARCHAR(255) binary NOT NULL DEFAULT '',
	`number_articles` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`parent` MEDIUMINT(8) unsigned DEFAULT NULL,
	`cat_order` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_view` TINYINT(3) NOT NULL DEFAULT '0',
	`auth_post` TINYINT(3) NOT NULL DEFAULT '0',
	`auth_rate` TINYINT(3) NOT NULL DEFAULT '0',
	`auth_comment` TINYINT(3) NOT NULL DEFAULT '0',
	`auth_edit` TINYINT(3) NOT NULL DEFAULT '0',
	`auth_delete` TINYINT(3) NOT NULL DEFAULT '2',
	`auth_approval` TINYINT(3) NOT NULL DEFAULT '0',
	`auth_approval_edit` TINYINT(3) NOT NULL DEFAULT '0',
	`auth_view_groups` VARCHAR(255) DEFAULT NULL,
	`auth_post_groups` VARCHAR(255) DEFAULT NULL,
	`auth_rate_groups` VARCHAR(255) DEFAULT NULL,
	`auth_comment_groups` VARCHAR(255) DEFAULT NULL,
	`auth_edit_groups` VARCHAR(255) DEFAULT NULL,
	`auth_delete_groups` VARCHAR(255) DEFAULT NULL,
	`auth_approval_groups` VARCHAR(255) DEFAULT NULL,
	`auth_approval_edit_groups` VARCHAR(255) DEFAULT NULL,
	`auth_moderator_groups` VARCHAR(255) DEFAULT NULL,
	`comments_forum_id` TINYINT(3) NOT NULL DEFAULT '-1',
	KEY `category_id` (`category_id`)
) AUTO_INCREMENT=2 ;

## `phpbb_kb_categories`


## --------------------------------------------------------

## `phpbb_kb_config`

CREATE TABLE `phpbb_kb_config` (
	`config_name` VARCHAR(255) NOT NULL DEFAULT '',
	`config_value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_kb_config`


## --------------------------------------------------------

## `phpbb_kb_custom`

CREATE TABLE `phpbb_kb_custom` (
	`custom_id` INT(50) NOT NULL auto_increment,
	`custom_name` TEXT NOT NULL,
	`custom_description` TEXT NOT NULL,
	`data` TEXT NOT NULL,
	`field_order` INT(20) NOT NULL DEFAULT '0',
	`field_type` TINYINT(2) NOT NULL DEFAULT '0',
	`regex` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`custom_id`)
);

## `phpbb_kb_custom`


## --------------------------------------------------------

## `phpbb_kb_customdata`

CREATE TABLE `phpbb_kb_customdata` (
	`customdata_file` INT(50) NOT NULL DEFAULT '0',
	`customdata_custom` INT(50) NOT NULL DEFAULT '0',
	`data` TEXT
);

## `phpbb_kb_customdata`


## --------------------------------------------------------

## `phpbb_kb_results`

CREATE TABLE `phpbb_kb_results` (
	`search_id` INT(11) unsigned NOT NULL DEFAULT '0',
	`session_id` VARCHAR(32) NOT NULL DEFAULT '',
	`search_array` TEXT NOT NULL,
	PRIMARY KEY (`search_id`),
	KEY `session_id` (`session_id`)
);

## `phpbb_kb_results`


## --------------------------------------------------------

## `phpbb_kb_search`

CREATE TABLE `phpbb_kb_search` (
	`search_id` INT(11) unsigned NOT NULL DEFAULT '0',
	`session_id` VARCHAR(32) NOT NULL DEFAULT '',
	`search_array` TEXT NOT NULL,
	PRIMARY KEY (`search_id`),
	KEY `session_id` (`session_id`)
);

## `phpbb_kb_search`


## --------------------------------------------------------

## `phpbb_kb_types`

CREATE TABLE `phpbb_kb_types` (
	`id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`type` VARCHAR(255) binary NOT NULL DEFAULT '',
	KEY `id` (`id`)
) AUTO_INCREMENT=2 ;

## `phpbb_kb_types`


## --------------------------------------------------------

## `phpbb_kb_votes`

CREATE TABLE `phpbb_kb_votes` (
	`votes_ip` VARCHAR(50) NOT NULL DEFAULT '0',
	`votes_userid` INT(50) NOT NULL DEFAULT '0',
	`votes_file` INT(50) NOT NULL DEFAULT '0'
);

## `phpbb_kb_votes`


## --------------------------------------------------------

## `phpbb_kb_wordlist`

CREATE TABLE `phpbb_kb_wordlist` (
	`word_text` VARCHAR(50) binary NOT NULL DEFAULT '',
	`word_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`word_common` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`word_text`),
	KEY `word_id` (`word_id`)
);

## `phpbb_kb_wordlist`


## --------------------------------------------------------

## `phpbb_kb_wordmatch`

CREATE TABLE `phpbb_kb_wordmatch` (
	`article_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`word_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`title_match` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `post_id` (`article_id`),
	KEY `word_id` (`word_id`)
);

## `phpbb_kb_wordmatch`

## --------------------------------------------------------

## `phpbb_liw_cache`

CREATE TABLE `phpbb_liw_cache` (
	`image_checksum` VARCHAR(32) NOT NULL DEFAULT '',
	`image_width` VARCHAR(10) DEFAULT NULL,
	`image_height` VARCHAR(10) DEFAULT NULL,
	PRIMARY KEY (`image_checksum`)
);

## `phpbb_liw_cache`


## --------------------------------------------------------

## `phpbb_logins`

CREATE TABLE `phpbb_logins` (
	`login_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`login_userid` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`login_ip` VARCHAR(40) NOT NULL DEFAULT '0',
	`login_user_agent` VARCHAR(255) NOT NULL DEFAULT 'n/a',
	`login_time` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`login_id`)
);

## `phpbb_logins`


## --------------------------------------------------------

## `phpbb_megamail`

CREATE TABLE `phpbb_megamail` (
	`mail_id` smallint unsigned NOT NULL auto_increment,
	`mailsession_id` VARCHAR(32) NOT NULL,
	`mass_pm` TINYINT(1) NOT NULL default '0',
	`user_id` MEDIUMINT(8) NOT NULL,
	`group_id` MEDIUMINT(8) NOT NULL,
	`email_subject` VARCHAR(255) NOT NULL,
	`email_body` TEXT NOT NULL,
	`email_format` TINYINT(1) NOT NULL default '0',
	`batch_start` MEDIUMINT(8) NOT NULL,
	`batch_size` smallint UNSIGNED NOT NULL,
	`batch_wait` smallint NOT NULL,
	`status` smallint NOT NULL,
	PRIMARY KEY (`mail_id`)
);

## `phpbb_megamail`


## --------------------------------------------------------

## `phpbb_moderator_cache`

CREATE TABLE `phpbb_moderator_cache` (
	`forum_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`user_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`username` VARCHAR(255) DEFAULT '' NOT NULL,
	`group_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`group_name` VARCHAR(255) DEFAULT '' NOT NULL,
	`display_on_index` TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	KEY `disp_idx` (`display_on_index`),
	KEY `forum_id` (`forum_id`)
);

## `phpbb_moderator_cache`


## --------------------------------------------------------

## `phpbb_modules`

CREATE TABLE `phpbb_modules` (
	`module_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
	`module_enabled` TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	`module_display` TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	`module_basename` VARCHAR(255) DEFAULT '' NOT NULL,
	`module_class` VARCHAR(10) DEFAULT '' NOT NULL,
	`parent_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`left_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`right_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`module_langname` VARCHAR(255) DEFAULT '' NOT NULL,
	`module_mode` VARCHAR(255) DEFAULT '' NOT NULL,
	`module_auth` VARCHAR(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (`module_id`),
	KEY `left_right_id` (`left_id`, `right_id`),
	KEY `module_enabled` (`module_enabled`),
	KEY `class_left_id` (`module_class`, `left_id`)
);

## `phpbb_modules`


## --------------------------------------------------------

## `phpbb_news`

CREATE TABLE `phpbb_news` (
	`news_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`news_category` VARCHAR(70) NOT NULL DEFAULT '',
	`news_image` VARCHAR(70) NOT NULL DEFAULT '',
	PRIMARY KEY (`news_id`)
);

## `phpbb_news`


## --------------------------------------------------------

## `phpbb_notes`

CREATE TABLE `phpbb_notes` (
	`id` INT(8) NOT NULL DEFAULT '0',
	`text` text
);

## `phpbb_notes`


## --------------------------------------------------------

## `phpbb_pa_auth`

CREATE TABLE `phpbb_pa_auth` (
	`group_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`cat_id` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`auth_view` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_read` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_view_file` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_edit_file` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_delete_file` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_upload` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_download` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_rate` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_email` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_view_comment` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_post_comment` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_edit_comment` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_delete_comment` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_mod` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_search` TINYINT(1) NOT NULL DEFAULT '1',
	`auth_stats` TINYINT(1) NOT NULL DEFAULT '1',
	`auth_toplist` TINYINT(1) NOT NULL DEFAULT '1',
	`auth_viewall` TINYINT(1) NOT NULL DEFAULT '1',
	KEY `group_id` (`group_id`),
	KEY `cat_id` (`cat_id`)
);

## `phpbb_pa_auth`


## --------------------------------------------------------

## `phpbb_pa_cat`

CREATE TABLE `phpbb_pa_cat` (
	`cat_id` INT(10) NOT NULL auto_increment,
	`cat_name` TEXT NOT NULL,
	`cat_desc` TEXT NOT NULL,
	`cat_parent` INT(50) DEFAULT NULL,
	`parents_data` TEXT NOT NULL,
	`cat_order` INT(50) DEFAULT NULL,
	`cat_allow_file` TINYINT(2) NOT NULL DEFAULT '0',
	`cat_allow_ratings` TINYINT(2) NOT NULL DEFAULT '1',
	`cat_allow_comments` TINYINT(2) NOT NULL DEFAULT '1',
	`cat_files` MEDIUMINT(8) NOT NULL DEFAULT '-1',
	`cat_last_file_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`cat_last_file_name` VARCHAR(255) NOT NULL DEFAULT '',
	`cat_last_file_time` INT(50) unsigned NOT NULL DEFAULT '0',
	`auth_view` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_read` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_view_file` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_edit_file` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_delete_file` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_upload` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_download` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_rate` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_email` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_view_comment` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_post_comment` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_edit_comment` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_delete_comment` TINYINT(2) NOT NULL DEFAULT '0',
	PRIMARY KEY (`cat_id`)
);

## `phpbb_pa_cat`


## --------------------------------------------------------

## `phpbb_pa_comments`

CREATE TABLE `phpbb_pa_comments` (
	`comments_id` INT(10) NOT NULL auto_increment,
	`file_id` INT(10) NOT NULL DEFAULT '0',
	`comments_text` TEXT NOT NULL,
	`comments_title` TEXT NOT NULL,
	`comments_time` INT(50) NOT NULL DEFAULT '0',
	`poster_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	PRIMARY KEY (`comments_id`)
);

## `phpbb_pa_comments`


## --------------------------------------------------------

## `phpbb_pa_config`

CREATE TABLE `phpbb_pa_config` (
	`config_name` VARCHAR(255) NOT NULL DEFAULT '',
	`config_value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_pa_config`


## --------------------------------------------------------

## `phpbb_pa_custom`

CREATE TABLE `phpbb_pa_custom` (
	`custom_id` INT(50) NOT NULL auto_increment,
	`custom_name` TEXT NOT NULL,
	`custom_description` TEXT NOT NULL,
	`data` TEXT NOT NULL,
	`field_order` INT(20) NOT NULL DEFAULT '0',
	`field_type` TINYINT(2) NOT NULL DEFAULT '0',
	`regex` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`custom_id`)
);

## `phpbb_pa_custom`


## --------------------------------------------------------

## `phpbb_pa_customdata`

CREATE TABLE `phpbb_pa_customdata` (
	`customdata_file` INT(50) NOT NULL DEFAULT '0',
	`customdata_custom` INT(50) NOT NULL DEFAULT '0',
	`data` TEXT
);

## `phpbb_pa_customdata`


## --------------------------------------------------------

## `phpbb_pa_download_info`

CREATE TABLE `phpbb_pa_download_info` (
	`file_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`download_time` INT(11) NOT NULL DEFAULT '0',
	`downloader_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`downloader_os` VARCHAR(255) NOT NULL DEFAULT '',
	`downloader_browser` VARCHAR(255) NOT NULL DEFAULT '',
	`browser_version` VARCHAR(255) NOT NULL DEFAULT ''
);

## `phpbb_pa_download_info`


## --------------------------------------------------------

## `phpbb_pa_files`

CREATE TABLE `phpbb_pa_files` (
	`file_id` INT(10) NOT NULL auto_increment,
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`poster_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`file_name` TEXT NOT NULL,
	`file_size` INT(20) NOT NULL DEFAULT '0',
	`unique_name` VARCHAR(255) NOT NULL DEFAULT '',
	`real_name` VARCHAR(255) NOT NULL DEFAULT '',
	`file_dir` VARCHAR(255) NOT NULL DEFAULT '',
	`file_desc` TEXT NOT NULL,
	`file_creator` TEXT NOT NULL,
	`file_version` TEXT NOT NULL,
	`file_longdesc` TEXT NOT NULL,
	`file_ssurl` TEXT NOT NULL,
	`file_sshot_link` TINYINT(2) NOT NULL DEFAULT '0',
	`file_dlurl` TEXT NOT NULL,
	`file_time` INT(50) DEFAULT NULL,
	`file_update_time` INT(50) NOT NULL DEFAULT '0',
	`file_catid` INT(10) DEFAULT NULL,
	`file_posticon` TEXT NOT NULL,
	`file_license` INT(10) DEFAULT NULL,
	`file_dls` INT(10) DEFAULT NULL,
	`file_last` INT(50) DEFAULT NULL,
	`file_pin` INT(2) DEFAULT NULL,
	`file_docsurl` TEXT NOT NULL,
	`file_approved` TINYINT(1) NOT NULL DEFAULT '1',
	`file_broken` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`file_id`)
);

## `phpbb_pa_files`


## --------------------------------------------------------

## `phpbb_pa_license`

CREATE TABLE `phpbb_pa_license` (
	`license_id` INT(10) NOT NULL auto_increment,
	`license_name` TEXT NOT NULL,
	`license_text` TEXT NOT NULL,
	PRIMARY KEY (`license_id`)
);

## `phpbb_pa_license`


## --------------------------------------------------------

## `phpbb_pa_mirrors`

CREATE TABLE `phpbb_pa_mirrors` (
	`mirror_id` MEDIUMINT(8) NOT NULL auto_increment,
	`file_id` INT(10) NOT NULL DEFAULT '0',
	`unique_name` VARCHAR(255) NOT NULL DEFAULT '',
	`file_dir` VARCHAR(255) NOT NULL DEFAULT '',
	`file_dlurl` VARCHAR(255) NOT NULL DEFAULT '',
	`mirror_location` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`mirror_id`),
	KEY `file_id` (`file_id`)
);

## `phpbb_pa_mirrors`


## --------------------------------------------------------

## `phpbb_pa_votes`

CREATE TABLE `phpbb_pa_votes` (
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`votes_ip` VARCHAR(50) NOT NULL DEFAULT '0',
	`votes_file` INT(50) NOT NULL DEFAULT '0',
	`rate_point` TINYINT(3) unsigned NOT NULL DEFAULT '0',
	`voter_os` VARCHAR(255) NOT NULL DEFAULT '',
	`voter_browser` VARCHAR(255) NOT NULL DEFAULT '',
	`browser_version` VARCHAR(8) NOT NULL DEFAULT '',
	KEY `user_id` (`user_id`)
);

## `phpbb_pa_votes`


## --------------------------------------------------------

## `phpbb_plugins`

CREATE TABLE `phpbb_plugins` (
	`plugin_name` VARCHAR(255) NOT NULL DEFAULT '',
	`plugin_version` VARCHAR(255) NOT NULL DEFAULT '',
	`plugin_dir` VARCHAR(255) NOT NULL DEFAULT '',
	`plugin_enabled` TINYINT(2) NOT NULL DEFAULT 0,
	`plugin_constants` TINYINT(2) NOT NULL DEFAULT 0,
	`plugin_common` TINYINT(2) NOT NULL DEFAULT 0,
	`plugin_functions` TINYINT(2) NOT NULL DEFAULT 0,
	`plugin_class` TINYINT(2) NOT NULL DEFAULT 0,
	PRIMARY KEY (`plugin_name`)
);

## `phpbb_plugins`


## --------------------------------------------------------

## `phpbb_plugins_config`

CREATE TABLE `phpbb_plugins_config` (
	`config_name` VARCHAR(255) NOT NULL DEFAULT '',
	`config_value` TEXT NOT NULL,
	PRIMARY KEY (`config_name`)
);

## `phpbb_plugins_config`


## --------------------------------------------------------

## `phpbb_poll_options`

CREATE TABLE `phpbb_poll_options` (
	`poll_option_id` TINYINT(4) DEFAULT '0' NOT NULL,
	`topic_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_option_text` TEXT NOT NULL,
	`poll_option_total` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	KEY `poll_opt_id` (`poll_option_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_poll_options`


## --------------------------------------------------------

## `phpbb_poll_votes`

CREATE TABLE `phpbb_poll_votes` (
	`topic_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_option_id` TINYINT(4) DEFAULT '0' NOT NULL,
	`vote_user_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`vote_user_ip` VARCHAR(40) DEFAULT '' NOT NULL,
	KEY `topic_id` (`topic_id`),
	KEY `vote_user_id` (`vote_user_id`),
	KEY `vote_user_ip` (`vote_user_ip`)
);

## `phpbb_poll_votes`


## --------------------------------------------------------

## `phpbb_posts`

CREATE TABLE `phpbb_posts` (
	`post_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`poster_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`post_time` INT(11) NOT NULL DEFAULT '0',
	`poster_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`post_username` VARCHAR(25) DEFAULT NULL,
	`post_subject` VARCHAR(255) DEFAULT NULL,
	`post_text` MEDIUMTEXT NOT NULL,
	`post_text_compiled` MEDIUMTEXT NOT NULL,
	`enable_bbcode` TINYINT(1) NOT NULL DEFAULT '1',
	`enable_html` TINYINT(1) NOT NULL DEFAULT '0',
	`enable_smilies` TINYINT(1) NOT NULL DEFAULT '1',
	`enable_autolinks_acronyms` TINYINT(1) NOT NULL DEFAULT '1',
	`enable_sig` TINYINT(1) NOT NULL DEFAULT '1',
	`edit_notes` MEDIUMTEXT NOT NULL,
	`post_edit_time` INT(11) DEFAULT NULL,
	`post_edit_count` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`post_edit_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`post_attachment` TINYINT(1) NOT NULL DEFAULT '0',
	`post_bluecard` TINYINT(1) DEFAULT NULL,
	`post_locked` TINYINT(1) NOT NULL DEFAULT '0',
	`post_likes` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`post_images` MEDIUMTEXT NOT NULL,
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
	topic_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	post_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	like_time  INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	KEY topic_id (topic_id),
	KEY post_id (post_id),
	KEY user_id (user_id)
);

## `phpbb_posts_likes`


## --------------------------------------------------------

## `phpbb_privmsgs`

CREATE TABLE `phpbb_privmsgs` (
	`privmsgs_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`privmsgs_type` TINYINT(4) NOT NULL DEFAULT '0',
	`privmsgs_subject` VARCHAR(255) NOT NULL DEFAULT '',
	`privmsgs_text` TEXT NOT NULL,
	`privmsgs_from_userid` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`privmsgs_to_userid` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`privmsgs_date` INT(11) NOT NULL DEFAULT '0',
	`privmsgs_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`privmsgs_enable_bbcode` TINYINT(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_html` TINYINT(1) NOT NULL DEFAULT '0',
	`privmsgs_enable_smilies` TINYINT(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_autolinks_acronyms` TINYINT(1) NOT NULL DEFAULT '0',
	`privmsgs_attach_sig` TINYINT(1) NOT NULL DEFAULT '1',
	`privmsgs_attachment` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`privmsgs_id`),
	KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
	KEY `privmsgs_to_userid` (`privmsgs_to_userid`)
);

## `phpbb_privmsgs`


## --------------------------------------------------------

## `phpbb_privmsgs_archive`

CREATE TABLE `phpbb_privmsgs_archive` (
	`privmsgs_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`privmsgs_type` TINYINT(4) NOT NULL DEFAULT '0',
	`privmsgs_subject` VARCHAR(255) NOT NULL DEFAULT '',
	`privmsgs_text` TEXT NOT NULL,
	`privmsgs_from_userid` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`privmsgs_to_userid` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`privmsgs_date` INT(11) NOT NULL DEFAULT '0',
	`privmsgs_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`privmsgs_enable_bbcode` TINYINT(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_html` TINYINT(1) NOT NULL DEFAULT '0',
	`privmsgs_enable_smilies` TINYINT(1) NOT NULL DEFAULT '1',
	`privmsgs_enable_autolinks_acronyms` TINYINT(1) NOT NULL DEFAULT '0',
	`privmsgs_attach_sig` TINYINT(1) NOT NULL DEFAULT '1',
	`privmsgs_attachment` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`privmsgs_id`),
	KEY `privmsgs_from_userid` (`privmsgs_from_userid`),
	KEY `privmsgs_to_userid` (`privmsgs_to_userid`)
);

## `phpbb_privmsgs_archive`


## --------------------------------------------------------

## `phpbb_profile_fields`

CREATE TABLE `phpbb_profile_fields` (
	`field_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`field_name` VARCHAR(255) NOT NULL DEFAULT '',
	`field_description` VARCHAR(255) DEFAULT NULL,
	`field_type` TINYINT(4) unsigned NOT NULL DEFAULT '0',
	`text_field_default` VARCHAR(255) DEFAULT NULL,
	`text_field_maxlen` INT(255) unsigned NOT NULL DEFAULT '255',
	`text_area_default` TEXT NOT NULL,
	`text_area_maxlen` INT(255) unsigned NOT NULL DEFAULT '1024',
	`radio_button_default` VARCHAR(255) DEFAULT NULL,
	`radio_button_values` TEXT NOT NULL,
	`checkbox_default` TEXT NOT NULL,
	`checkbox_values` TEXT NOT NULL,
	`is_required` TINYINT(2) unsigned NOT NULL DEFAULT '0',
	`users_can_view` TINYINT(2) unsigned NOT NULL DEFAULT '1',
	`view_in_profile` TINYINT(2) unsigned NOT NULL DEFAULT '1',
	`profile_location` TINYINT(2) unsigned NOT NULL DEFAULT '2',
	`view_in_memberlist` TINYINT(2) unsigned NOT NULL DEFAULT '0',
	`view_in_topic` TINYINT(2) unsigned NOT NULL DEFAULT '0',
	`topic_location` TINYINT(2) unsigned NOT NULL DEFAULT '1',
	PRIMARY KEY (`field_id`),
	UNIQUE KEY `field_name` (`field_name`),
	KEY `field_type` (`field_type`)
);

## `phpbb_profile_fields`


## --------------------------------------------------------

## `phpbb_profile_view`

CREATE TABLE `phpbb_profile_view` (
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`viewername` VARCHAR(25) NOT NULL DEFAULT '',
	`viewer_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`view_stamp` INT(11) NOT NULL DEFAULT '0',
	`counter` MEDIUMINT(8) NOT NULL DEFAULT '0'
);

## `phpbb_profile_view`


## --------------------------------------------------------

## `phpbb_quota_limits`

CREATE TABLE `phpbb_quota_limits` (
	`quota_limit_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`quota_desc` VARCHAR(20) NOT NULL DEFAULT '',
	`quota_limit` bigint(20) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`quota_limit_id`)
);

## `phpbb_quota_limits`


## --------------------------------------------------------

## `phpbb_ranks`

CREATE TABLE `phpbb_ranks` (
	`rank_id` SMALLINT(5) unsigned NOT NULL auto_increment,
	`rank_title` VARCHAR(50) NOT NULL DEFAULT '',
	`rank_min` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`rank_special` TINYINT(1) DEFAULT '0',
	`rank_show_title` TINYINT(1) DEFAULT '1',
	`rank_image` VARCHAR(255) DEFAULT NULL,
	PRIMARY KEY (`rank_id`)
);

## `phpbb_ranks`


## --------------------------------------------------------

## `phpbb_rate_results`

CREATE TABLE `phpbb_rate_results` (
	`rating_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`user_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`rating` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`rating_time` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`rating_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_rate_results`


## --------------------------------------------------------

## `phpbb_referers`

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

## `phpbb_referers`


## --------------------------------------------------------

## `phpbb_search_results`

CREATE TABLE `phpbb_search_results` (
	`search_id` INT(11) unsigned NOT NULL DEFAULT '0',
	`session_id` VARCHAR(32) NOT NULL DEFAULT '',
	`search_array` MEDIUMTEXT NOT NULL,
	`search_time` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`search_id`),
	KEY `session_id` (`session_id`)
);

## `phpbb_search_results`


## --------------------------------------------------------

## `phpbb_search_wordlist`

CREATE TABLE `phpbb_search_wordlist` (
	`word_text` VARCHAR(50) binary NOT NULL DEFAULT '',
	`word_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`word_common` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`word_text`),
	KEY `word_id` (`word_id`)
);

## `phpbb_search_wordlist`


## --------------------------------------------------------

## `phpbb_search_wordmatch`

CREATE TABLE `phpbb_search_wordmatch` (
	`post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`word_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`title_match` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `post_id` (`post_id`),
	KEY `word_id` (`word_id`)
);

## `phpbb_search_wordmatch`


## --------------------------------------------------------

## `phpbb_sessions`

CREATE TABLE `phpbb_sessions` (
	`session_id` VARCHAR(32) NOT NULL DEFAULT '',
	`session_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`session_start` INT(11) NOT NULL DEFAULT '0',
	`session_time` INT(11) NOT NULL DEFAULT '0',
	`session_ip` VARCHAR(40) NOT NULL DEFAULT '0',
	`session_browser` VARCHAR(255) DEFAULT '' NOT NULL,
	`session_page` VARCHAR(255) NOT NULL DEFAULT '',
	`session_logged_in` TINYINT(1) NOT NULL DEFAULT '0',
	`session_forum_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`session_topic_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`session_last_visit` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`session_forwarded_for` VARCHAR(255) DEFAULT '' NOT NULL,
	`session_viewonline` TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	`session_autologin` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`session_admin` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`session_id`),
	KEY `session_user_id` (`session_user_id`),
	KEY `session_fid` (`session_forum_id`)
);

## `phpbb_sessions`


## --------------------------------------------------------

## `phpbb_sessions_keys`

CREATE TABLE `phpbb_sessions_keys` (
	`key_id` VARCHAR(32) NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`last_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`last_login` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`key_id`,`user_id`),
	KEY `last_login` (`last_login`)
);

## `phpbb_sessions_keys`


## --------------------------------------------------------

## `phpbb_shout`

CREATE TABLE `phpbb_shout` (
	`shout_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`shout_username` VARCHAR(25) NOT NULL DEFAULT '',
	`shout_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`shout_group_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`shout_session_time` INT(11) NOT NULL DEFAULT '0',
	`shout_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`shout_text` TEXT NOT NULL,
	`shout_active` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`enable_bbcode` TINYINT(1) NOT NULL DEFAULT '0',
	`enable_html` TINYINT(1) NOT NULL DEFAULT '0',
	`enable_smilies` TINYINT(1) NOT NULL DEFAULT '0',
	`enable_sig` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `shout_id` (`shout_id`)
);

## `phpbb_shout`


## --------------------------------------------------------

## `phpbb_site_history`

CREATE TABLE `phpbb_site_history` (
	`date` INT(11) NOT NULL DEFAULT '0',
	`reg` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`hidden` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`guests` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`new_topics` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`new_posts` MEDIUMINT(8) NOT NULL DEFAULT '0',
	UNIQUE KEY `date` (`date`)
);

## `phpbb_site_history`


## --------------------------------------------------------

## `phpbb_smilies`

CREATE TABLE `phpbb_smilies` (
	`smilies_id` SMALLINT(5) unsigned NOT NULL auto_increment,
	`code` VARCHAR(50) DEFAULT NULL,
	`smile_url` VARCHAR(100) DEFAULT NULL,
	`emoticon` VARCHAR(75) DEFAULT NULL,
	`smilies_order` INT(5) NOT NULL DEFAULT '0',
	PRIMARY KEY (`smilies_id`)
);

## `phpbb_smilies`


## --------------------------------------------------------

## `phpbb_stats_config`

CREATE TABLE `phpbb_stats_config` (
	`config_name` VARCHAR(50) NOT NULL DEFAULT '',
	`config_value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`config_name`)
);

## `phpbb_stats_config`


## --------------------------------------------------------

## `phpbb_stats_modules`

CREATE TABLE `phpbb_stats_modules` (
	`module_id` TINYINT(8) NOT NULL DEFAULT '0',
	`name` VARCHAR(150) NOT NULL DEFAULT '',
	`active` TINYINT(1) NOT NULL DEFAULT '0',
	`installed` TINYINT(1) NOT NULL DEFAULT '0',
	`display_order` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`update_time` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_value` TINYINT(2) NOT NULL DEFAULT '0',
	`module_info_cache` blob,
	`module_db_cache` blob,
	`module_result_cache` blob,
	`module_info_time` INT(10) unsigned NOT NULL DEFAULT '0',
	`module_cache_time` INT(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`module_id`)
);

## `phpbb_stats_modules`


## --------------------------------------------------------

## `phpbb_themes`

CREATE TABLE `phpbb_themes` (
	`themes_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`template_name` VARCHAR(30) NOT NULL DEFAULT '',
	`style_name` VARCHAR(30) NOT NULL DEFAULT '',
	`head_stylesheet` VARCHAR(100) DEFAULT NULL,
	`body_background` VARCHAR(100) DEFAULT NULL,
	`body_bgcolor` VARCHAR(6) DEFAULT NULL,
	`tr_class1` VARCHAR(25) DEFAULT NULL,
	`tr_class2` VARCHAR(25) DEFAULT NULL,
	`tr_class3` VARCHAR(25) DEFAULT NULL,
	`td_class1` VARCHAR(25) DEFAULT NULL,
	`td_class2` VARCHAR(25) DEFAULT NULL,
	`td_class3` VARCHAR(25) DEFAULT NULL,
	PRIMARY KEY (`themes_id`)
);

## `phpbb_themes`


## --------------------------------------------------------

## `phpbb_topic_view`

CREATE TABLE `phpbb_topic_view` (
	`topic_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`view_time` INT(11) NOT NULL DEFAULT '0',
	`view_count` INT(11) NOT NULL DEFAULT '0'
);

## `phpbb_topic_view`


## --------------------------------------------------------

## `phpbb_topics`

CREATE TABLE `phpbb_topics` (
	`topic_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`forum_id` SMALLINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_title` VARCHAR(255) NOT NULL DEFAULT '',
	`topic_title_clean` VARCHAR(255) NOT NULL DEFAULT '',
	`topic_ftitle_clean` VARCHAR(255) NOT NULL DEFAULT '',
	`topic_tags` VARCHAR(255) NOT NULL DEFAULT '',
	`topic_desc` VARCHAR(255) DEFAULT '',
	`topic_similar_topics` VARCHAR(255) NOT NULL DEFAULT '',
	`topic_poster` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`topic_time` INT(11) unsigned NOT NULL DEFAULT '0',
	`topic_views` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_replies` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_likes` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_status` TINYINT(3) NOT NULL DEFAULT '0',
	`topic_type` TINYINT(3) NOT NULL DEFAULT '0',
	`poll_title` VARCHAR(255) DEFAULT '' NOT NULL,
	`poll_start` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_length` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_max_options` TINYINT(4) DEFAULT '1' NOT NULL,
	`poll_last_vote` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`poll_vote_change` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`topic_first_post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_first_post_time` INT(11) unsigned NOT NULL DEFAULT '0',
	`topic_first_poster_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_first_poster_name` VARCHAR(255) NOT NULL DEFAULT '',
	`topic_first_poster_color` VARCHAR(16) NOT NULL DEFAULT '',
	`topic_last_post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_last_post_time` INT(11) unsigned NOT NULL DEFAULT '0',
	`topic_last_poster_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_last_poster_name` VARCHAR(255) NOT NULL DEFAULT '',
	`topic_last_poster_color` VARCHAR(16) NOT NULL DEFAULT '',
	`topic_moved_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_attachment` TINYINT(1) NOT NULL DEFAULT '0',
	`topic_label_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`topic_label_compiled` VARCHAR(255) DEFAULT NULL,
	`news_id` INT(10) unsigned NOT NULL DEFAULT '0',
	`topic_calendar_time` INT(11) DEFAULT NULL,
	`topic_calendar_duration` INT(11) DEFAULT NULL,
	`topic_rating` double unsigned NOT NULL DEFAULT '0',
	`topic_show_portal` TINYINT(1) NOT NULL DEFAULT '0',
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

## `phpbb_topics_labels`

CREATE TABLE `phpbb_topics_labels` (
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

## `phpbb_topics_labels`


## --------------------------------------------------------

## `phpbb_topics_tags_list`

CREATE TABLE `phpbb_topics_tags_list` (
	`tag_text` VARCHAR(50) binary NOT NULL DEFAULT '',
	`tag_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`tag_count` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`tag_text`),
	KEY `tag_id` (`tag_id`)
);

## `phpbb_topics_tags_list`


## --------------------------------------------------------

## `phpbb_topics_tags_match`

CREATE TABLE `phpbb_topics_tags_match` (
	`tag_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	KEY `tag_id` (`tag_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_topics_tags_match`


## --------------------------------------------------------

## `phpbb_topics_watch`

CREATE TABLE `phpbb_topics_watch` (
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`notify_status` TINYINT(1) NOT NULL DEFAULT '0',
	KEY `topic_id` (`topic_id`),
	KEY `user_id` (`user_id`),
	KEY `notify_status` (`notify_status`)
);

## `phpbb_topics_watch`


## --------------------------------------------------------

## `phpbb_upi2db_always_read`

CREATE TABLE `phpbb_upi2db_always_read` (
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`last_update` INT(11) NOT NULL DEFAULT '0',
	KEY `forum_id` (`forum_id`),
	KEY `topic_id` (`topic_id`)
);

## `phpbb_upi2db_always_read`


## --------------------------------------------------------

## `phpbb_upi2db_last_posts`

CREATE TABLE `phpbb_upi2db_last_posts` (
	`post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`poster_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`post_time` INT(11) NOT NULL DEFAULT '0',
	`post_edit_time` INT(11) NOT NULL DEFAULT '0',
	`topic_type` TINYINT(1) NOT NULL DEFAULT '0',
	`post_edit_by` MEDIUMINT(8) NOT NULL DEFAULT '0',
	PRIMARY KEY (`post_id`)
);

## `phpbb_upi2db_last_posts`


## --------------------------------------------------------

## `phpbb_upi2db_unread_posts`

CREATE TABLE `phpbb_upi2db_unread_posts` (
	`post_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`topic_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`status` TINYINT(1) NOT NULL DEFAULT '0',
	`topic_type` TINYINT(1) NOT NULL DEFAULT '0',
	`last_update` INT(11) NOT NULL DEFAULT '0',
	KEY `post_id` (`post_id`),
	KEY `user_id` (`user_id`)
);

## `phpbb_upi2db_unread_posts`


## --------------------------------------------------------

## `phpbb_user_group`

CREATE TABLE `phpbb_user_group` (
	`group_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`group_leader` TINYINT(1) unsigned DEFAULT '0' NOT NULL,
	`user_pending` TINYINT(1) DEFAULT '1' NOT NULL,
	KEY `group_id` (`group_id`),
	KEY `user_id` (`user_id`),
	KEY `group_leader` (`group_leader`)
);

## `phpbb_user_group`


## --------------------------------------------------------

## `phpbb_users`

CREATE TABLE `phpbb_users` (
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`user_active` TINYINT(1) DEFAULT '1',
	`user_mask` TINYINT(1) DEFAULT '0',
	`user_cms_auth` TEXT NOT NULL,
	`user_permissions` MEDIUMTEXT NOT NULL,
	`user_perm_from` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`username` VARCHAR(36) NOT NULL DEFAULT '',
	`username_clean` VARCHAR(255) NOT NULL DEFAULT '',
	`user_email` VARCHAR(255) DEFAULT NULL,
	`user_email_hash` BIGINT(20) DEFAULT '0' NOT NULL,
	`user_facebook_id` VARCHAR(40) NOT NULL DEFAULT '',
	`user_google_id` VARCHAR(40) NOT NULL DEFAULT '',
	`user_website` VARCHAR(255) DEFAULT NULL,
	`user_ip` VARCHAR(40) DEFAULT '' NOT NULL,
	`user_first_name` VARCHAR(255) NOT NULL DEFAULT '',
	`user_last_name` VARCHAR(255) NOT NULL DEFAULT '',
	`user_password` VARCHAR(40) NOT NULL DEFAULT '',
	`user_newpasswd` VARCHAR(40) NOT NULL DEFAULT '',
	`user_passchg` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`user_pass_convert` TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	`user_form_salt` VARCHAR(32) DEFAULT '' NOT NULL,
	`user_session_time` INT(11) NOT NULL DEFAULT '0',
	`user_session_page` VARCHAR(255) NOT NULL DEFAULT '',
	`user_browser` VARCHAR(255) NOT NULL DEFAULT '',
	`user_lastvisit` INT(11) NOT NULL DEFAULT '0',
	`user_regdate` INT(11) NOT NULL DEFAULT '0',
	`user_type` TINYINT(2) DEFAULT '0' NOT NULL,
	`user_level` TINYINT(4) DEFAULT '0',
	`user_posts` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_timezone` DECIMAL(5,2) NOT NULL DEFAULT '0.00',
	`user_style` MEDIUMINT(8) DEFAULT NULL,
	`user_lang` VARCHAR(255) DEFAULT NULL,
	`user_dateformat` VARCHAR(14) NOT NULL DEFAULT 'd M Y H:i',
	`user_new_privmsg` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`user_unread_privmsg` SMALLINT(5) unsigned NOT NULL DEFAULT '0',
	`user_last_privmsg` INT(11) NOT NULL DEFAULT '0',
	`user_private_chat_alert` VARCHAR(255) NOT NULL DEFAULT '0',
	`user_emailtime` INT(11) DEFAULT NULL,
	`user_profile_view_popup` TINYINT(1) DEFAULT '0',
	`user_attachsig` TINYINT(1) NOT NULL DEFAULT '1',
	`user_setbm` TINYINT(1) NOT NULL DEFAULT '0',
	`user_options` INT(11) UNSIGNED DEFAULT '895' NOT NULL,
	`user_allowhtml` TINYINT(1) DEFAULT '1',
	`user_allowbbcode` TINYINT(1) DEFAULT '1',
	`user_allowsmile` TINYINT(1) DEFAULT '1',
	`user_allowavatar` TINYINT(1) NOT NULL DEFAULT '1',
	`user_allow_pm` TINYINT(1) NOT NULL DEFAULT '1',
	`user_allow_pm_in` TINYINT(1) NOT NULL DEFAULT '1',
	`user_allow_viewemail` TINYINT(1) NOT NULL DEFAULT '0',
	`user_allow_mass_email` TINYINT(1) NOT NULL DEFAULT '1',
	`user_allow_viewonline` TINYINT(1) NOT NULL DEFAULT '1',
	`user_notify` TINYINT(1) NOT NULL DEFAULT '1',
	`user_notify_pm` TINYINT(1) NOT NULL DEFAULT '0',
	`user_popup_pm` TINYINT(1) NOT NULL DEFAULT '0',
	`user_privacy_policy_notify` TINYINT(1) NOT NULL DEFAULT '0',
	`user_rank` INT(11) DEFAULT '0',
	`user_rank2` INT(11) DEFAULT '-1',
	`user_rank3` INT(11) DEFAULT '-2',
	`user_rank4` INT(11) DEFAULT '-2',
	`user_rank5` INT(11) DEFAULT '-2',
	`user_avatar` VARCHAR(100) DEFAULT NULL,
	`user_avatar_type` TINYINT(4) NOT NULL DEFAULT '0',
	`user_from` VARCHAR(100) DEFAULT NULL,
	`user_sig` TEXT NOT NULL,
	`user_500px` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_aim` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_facebook` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_flickr` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_github` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_googleplus` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_icq` VARCHAR(15) DEFAULT '' NOT NULL,
	`user_instagram` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_jabber` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_linkedin` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_msnm` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_pinterest` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_twitter` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_skype` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_vimeo` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_yim` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_youtube` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_occ` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_interests` VARCHAR(255) DEFAULT '' NOT NULL,
	`user_actkey` VARCHAR(32) DEFAULT NULL,
	`user_birthday` INT(11) NOT NULL DEFAULT '999999',
	`user_birthday_y` VARCHAR(4) NOT NULL DEFAULT '',
	`user_birthday_m` VARCHAR(2) NOT NULL DEFAULT '',
	`user_birthday_d` VARCHAR(2) NOT NULL DEFAULT '',
	`user_next_birthday_greeting` INT(11) NOT NULL DEFAULT '0',
	`user_sub_forum` TINYINT(1) NOT NULL DEFAULT '1',
	`user_split_cat` TINYINT(1) NOT NULL DEFAULT '1',
	`user_last_topic_title` TINYINT(1) NOT NULL DEFAULT '1',
	`user_sub_level_links` TINYINT(1) NOT NULL DEFAULT '2',
	`user_display_viewonline` TINYINT(1) NOT NULL DEFAULT '2',
	`group_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`user_color` VARCHAR(16) NOT NULL DEFAULT '',
	`user_gender` TINYINT(4) NOT NULL DEFAULT '0',
	`user_totaltime` INT(11) DEFAULT '0',
	`user_totallogon` INT(11) DEFAULT '0',
	`user_totalpages` INT(11) DEFAULT '0',
	`user_calendar_display_open` TINYINT(1) NOT NULL DEFAULT '0',
	`user_calendar_header_cells` TINYINT(1) NOT NULL DEFAULT '0',
	`user_calendar_week_start` TINYINT(1) NOT NULL DEFAULT '1',
	`user_calendar_nb_row` TINYINT(2) unsigned NOT NULL DEFAULT '5',
	`user_calendar_birthday` TINYINT(1) NOT NULL DEFAULT '1',
	`user_calendar_forum` TINYINT(1) NOT NULL DEFAULT '1',
	`user_warnings` SMALLINT(5) DEFAULT '0',
	`user_time_mode` TINYINT(4) NOT NULL DEFAULT '5',
	`user_dst_time_lag` TINYINT(4) NOT NULL DEFAULT '60',
	`user_registered_ip` VARCHAR(40) DEFAULT NULL,
	`user_registered_hostname` VARCHAR(255) DEFAULT NULL,
	`user_profile_view` SMALLINT(5) NOT NULL DEFAULT '0',
	`user_last_profile_view` INT(11) NOT NULL DEFAULT '0',
	`user_topics_per_page` VARCHAR(5) DEFAULT NULL,
	`user_hot_threshold` VARCHAR(5) DEFAULT NULL,
	`user_posts_per_page` VARCHAR(5) DEFAULT NULL,
	`user_topic_show_days` SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
	`user_topic_sortby_type` VARCHAR(1) DEFAULT 't' NOT NULL,
	`user_topic_sortby_dir` VARCHAR(1) DEFAULT 'd' NOT NULL,
	`user_post_show_days` SMALLINT(4) UNSIGNED DEFAULT '0' NOT NULL,
	`user_post_sortby_type` VARCHAR(1) DEFAULT 't' NOT NULL,
	`user_post_sortby_dir` VARCHAR(1) DEFAULT 'a' NOT NULL,
	`user_allowswearywords` TINYINT(1) NOT NULL DEFAULT '0',
	`user_showavatars` TINYINT(1) DEFAULT '1',
	`user_showsignatures` TINYINT(1) DEFAULT '1',
	`user_login_attempts` TINYINT(4) DEFAULT '0' NOT NULL,
	`user_last_login_attempt` INT(11) NOT NULL DEFAULT '0',
	`user_sudoku_playing` INT(1) NOT NULL DEFAULT '0',
	`user_from_flag` VARCHAR(30) DEFAULT NULL,
	`user_phone` VARCHAR(255) DEFAULT NULL,
	`user_selfdes` TEXT NOT NULL,
	`user_upi2db_which_system` TINYINT(1) NOT NULL DEFAULT '1',
	`user_upi2db_disable` TINYINT(1) NOT NULL DEFAULT '0',
	`user_upi2db_datasync` INT(11) NOT NULL DEFAULT '0',
	`user_upi2db_new_word` TINYINT(1) NOT NULL DEFAULT '1',
	`user_upi2db_edit_word` TINYINT(1) NOT NULL DEFAULT '1',
	`user_upi2db_unread_color` TINYINT(1) NOT NULL DEFAULT '1',
	`user_personal_pics_count` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`user_id`),
	KEY `user_session_time` (`user_session_time`)
);

## `phpbb_users`


## --------------------------------------------------------

## `phpbb_words`

CREATE TABLE `phpbb_words` (
	`word_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`word` CHAR(100) NOT NULL DEFAULT '',
	`replacement` CHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (`word_id`)
);

## `phpbb_words`


## --------------------------------------------------------

## `phpbb_xs_news`

CREATE TABLE `phpbb_xs_news` (
	`news_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`news_date` INT(11) NOT NULL DEFAULT '0',
	`news_text` TEXT NOT NULL,
	`news_display` TINYINT(1) NOT NULL DEFAULT '1',
	`news_smilies` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`news_id`)
);

## `phpbb_xs_news`


## --------------------------------------------------------

## `phpbb_xs_news_xml`

CREATE TABLE `phpbb_xs_news_xml` (
	`xml_id` MEDIUMINT(8) NOT NULL auto_increment,
	`xml_title` VARCHAR(255) NOT NULL DEFAULT '',
	`xml_show` TINYINT(1) NOT NULL DEFAULT '0',
	`xml_feed` TEXT NOT NULL,
	`xml_is_feed` TINYINT(1) NOT NULL DEFAULT '1',
	`xml_width` VARCHAR(4) NOT NULL DEFAULT '98%',
	`xml_height` CHAR(3) NOT NULL DEFAULT '20',
	`xml_font` CHAR(3) NOT NULL DEFAULT '0',
	`xml_speed` CHAR(2) NOT NULL DEFAULT '3',
	`xml_direction` TINYINT(1) NOT NULL DEFAULT '0',
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
	`hash` VARCHAR(32) DEFAULT NULL
	);

CREATE TABLE `phpbb_ctracker_filescanner` (
	`id` SMALLINT(5) NOT NULL,
	`filepath` TEXT NOT NULL,
	`safety` SMALLINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	);

CREATE TABLE `phpbb_ctracker_ipblocker` (
	`id` MEDIUMINT(8) unsigned NOT NULL,
	`ct_blocker_value` VARCHAR(250) DEFAULT NULL,
	PRIMARY KEY (`id`)
	);

CREATE TABLE `phpbb_ctracker_loginhistory` (
	`ct_user_id` INT(10) DEFAULT NULL,
	`ct_login_ip` VARCHAR(40) DEFAULT NULL,
	`ct_login_time` INT(11) NOT NULL DEFAULT '0'
	);
## Cracker Tracker - END


## DRAFTS - BEGIN

CREATE TABLE `phpbb_drafts` (
	`draft_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
	`user_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`topic_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`save_time` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`draft_subject` VARCHAR(100) DEFAULT '' NOT NULL,
	`draft_message` TEXT NOT NULL,
	PRIMARY KEY (`draft_id`),
	KEY `save_time` (`save_time`)
);

## DRAFTS - END


## FRIENDS AND FOES - BEGIN

CREATE TABLE phpbb_zebra (
	user_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	zebra_id MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	friend TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	foe TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (user_id, zebra_id)
);

## FRIENDS AND FOES - END


## ICY PHOENIX LOGS - BEGIN

CREATE TABLE `phpbb_log` (
	`log_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
	`log_type` TINYINT(4) DEFAULT '0' NOT NULL,
	`user_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`forum_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`topic_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`reportee_id` MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL,
	`log_ip` VARCHAR(40) DEFAULT '' NOT NULL,
	`log_time` INT(11) UNSIGNED DEFAULT '0' NOT NULL,
	`log_operation` TEXT NOT NULL,
	`log_data` MEDIUMTEXT NOT NULL,
	PRIMARY KEY (`log_id`),
	KEY log_type (`log_type`),
	KEY forum_id (`forum_id`),
	KEY topic_id (`topic_id`),
	KEY reportee_id (`reportee_id`),
	KEY user_id (`user_id`)
);

CREATE TABLE `phpbb_logs` (
	`log_id` INT(11) unsigned NOT NULL auto_increment,
	`log_time` VARCHAR(11) NOT NULL,
	`log_page` VARCHAR(255) NOT NULL DEFAULT '',
	`log_user_id` INT(10) NOT NULL,
	`log_action` VARCHAR(60) NOT NULL DEFAULT '',
	`log_desc` MEDIUMTEXT NOT NULL,
	`log_target` INT(10) NOT NULL DEFAULT '0',
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
	`bpid` INT(10) NOT NULL auto_increment,
	`layout` INT(10) NOT NULL DEFAULT '1',
	`pkey` VARCHAR(30) NOT NULL DEFAULT '',
	`bposition` CHAR(2) NOT NULL DEFAULT '',
	PRIMARY KEY (`bpid`)
);

CREATE TABLE `phpbb_cms_block_settings` (
	`bs_id` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NOT NULL,
	`name` VARCHAR(255) NOT NULL default '',
	`content` TEXT NOT NULL ,
	`blockfile` VARCHAR(255) NOT NULL default '',
	`view` TINYINT(1) NOT NULL default 0,
	`type` TINYINT(1) NOT NULL default 1,
	`groups` tinytext NOT NULL,
	`locked` TINYINT(1) NOT NULL DEFAULT 1,
	PRIMARY KEY (`bs_id`)
);

CREATE TABLE `phpbb_cms_block_variable` (
	`bvid` INT(10) NOT NULL auto_increment,
	`bid` INT(10) NOT NULL DEFAULT '0',
	`label` VARCHAR(30) NOT NULL DEFAULT '',
	`sub_label` VARCHAR(255) DEFAULT NULL,
	`config_name` VARCHAR(30) NOT NULL DEFAULT '',
	`field_options` VARCHAR(255) DEFAULT NULL,
	`field_values` VARCHAR(255) DEFAULT NULL,
	`type` TINYINT(1) NOT NULL DEFAULT '0',
	`block` VARCHAR(255) DEFAULT NULL,
	PRIMARY KEY (`bvid`)
);

CREATE TABLE `phpbb_cms_blocks` (
	`bid` INT(10) NOT NULL auto_increment,
	`bs_id` INT(10) UNSIGNED NOT NULL,
	`block_cms_id` INT(10) UNSIGNED NOT NULL,
	`layout` INT(10) NOT NULL DEFAULT '0',
	`layout_special` INT(10) NOT NULL DEFAULT '0',
	`title` VARCHAR(60) NOT NULL DEFAULT '',
	`bposition` CHAR(2) NOT NULL DEFAULT '',
	`weight` INT(10) NOT NULL DEFAULT '1',
	`active` TINYINT(1) NOT NULL DEFAULT '1',
	`border` TINYINT(1) NOT NULL DEFAULT '1',
	`titlebar` TINYINT(1) NOT NULL DEFAULT '1',
	`background` TINYINT(1) NOT NULL DEFAULT '1',
	`local` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`bid`)
);

CREATE TABLE `phpbb_cms_config` (
	`id` INT(10) unsigned NOT NULL auto_increment,
	`bid` INT(10) NOT NULL DEFAULT '0',
	`config_name` VARCHAR(255) NOT NULL DEFAULT '',
	`config_value` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)
);

CREATE TABLE `phpbb_cms_layout` (
	`lid` INT(10) unsigned NOT NULL auto_increment,
	`name` VARCHAR(100) NOT NULL DEFAULT '',
	`filename` VARCHAR(100) NOT NULL DEFAULT '',
	`template` VARCHAR(100) NOT NULL DEFAULT '',
	`layout_cms_id` INT(10) UNSIGNED NOT NULL,
	`global_blocks` TINYINT(1) NOT NULL DEFAULT '0',
	`page_nav` TINYINT(1) NOT NULL DEFAULT '1',
	`config_vars` TEXT NOT NULL,
	`view` TINYINT(1) NOT NULL DEFAULT '0',
	`groups` TINYTEXT NOT NULL,
	PRIMARY KEY (`lid`)
);

CREATE TABLE `phpbb_cms_layout_special` (
	`lsid` INT(10) unsigned NOT NULL auto_increment,
	`page_id` VARCHAR(100) NOT NULL DEFAULT '',
	`locked` TINYINT(1) NOT NULL DEFAULT '1',
	`name` VARCHAR(100) NOT NULL DEFAULT '',
	`filename` VARCHAR(100) NOT NULL DEFAULT '',
	`template` VARCHAR(100) NOT NULL DEFAULT '',
	`global_blocks` TINYINT(1) NOT NULL DEFAULT '0',
	`page_nav` TINYINT(1) NOT NULL DEFAULT '1',
	`config_vars` TEXT NOT NULL,
	`view` TINYINT(1) NOT NULL DEFAULT '0',
	`groups` TINYTEXT NOT NULL,
	PRIMARY KEY (`lsid`),
	UNIQUE KEY `page_id` (`page_id`)
);

CREATE TABLE `phpbb_cms_nav_menu` (
	`menu_item_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`menu_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`menu_parent_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`cat_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`cat_parent_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`menu_default` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`menu_status` TINYINT(1) NOT NULL DEFAULT '0',
	`menu_order` SMALLINT(5) NOT NULL DEFAULT '0',
	`menu_icon` VARCHAR(255) DEFAULT NULL,
	`menu_name_lang` VARCHAR(150) DEFAULT NULL,
	`menu_name` VARCHAR(150) DEFAULT NULL,
	`menu_desc` TEXT NOT NULL,
	`menu_link` VARCHAR(255) DEFAULT NULL,
	`menu_link_external` TINYINT(1) NOT NULL DEFAULT '0',
	`auth_view` TINYINT(2) NOT NULL DEFAULT '0',
	`auth_view_group` SMALLINT(5) NOT NULL DEFAULT '0',
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
	`session_id` INT(10) NOT NULL,
	`session_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`session_username` VARCHAR(25) NOT NULL DEFAULT '',
	`session_ip` VARCHAR(40) NOT NULL DEFAULT '0',
	`session_start` INT(11) NOT NULL DEFAULT '0',
	`session_time` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`session_id`)
);

## AJAX Shoutbox - END


## CASH Mod - BEGIN

CREATE TABLE phpbb_cash (
	cash_id SMALLINT(6) NOT NULL auto_increment,
	cash_order SMALLINT(6) NOT NULL DEFAULT '0',
	cash_settings SMALLINT(4) NOT NULL DEFAULT '3313',
	cash_dbfield VARCHAR(64) NOT NULL DEFAULT '',
	cash_name VARCHAR(64) NOT NULL DEFAULT 'GP',
	cash_default INT(11) NOT NULL DEFAULT '0',
	cash_decimals TINYINT(2) NOT NULL DEFAULT '0',
	cash_imageurl VARCHAR(255) NOT NULL DEFAULT '',
	cash_exchange INT(11) NOT NULL DEFAULT '1',
	cash_perpost INT(11) NOT NULL DEFAULT '25',
	cash_postbonus INT(11) NOT NULL DEFAULT '2',
	cash_perreply INT(11) NOT NULL DEFAULT '25',
	cash_perthanks INT(11) NOT NULL DEFAULT '5',
	cash_maxearn INT(11) NOT NULL DEFAULT '75',
	cash_perpm INT(11) NOT NULL DEFAULT '0',
	cash_perchar INT(11) NOT NULL DEFAULT '20',
	cash_allowance TINYINT(1) NOT NULL DEFAULT '0',
	cash_allowanceamount INT(11) NOT NULL DEFAULT '0',
	cash_allowancetime TINYINT(2) NOT NULL DEFAULT '2',
	cash_allowancenext INT(11) NOT NULL DEFAULT '0',
	cash_forumlist VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (cash_id)
);

CREATE TABLE phpbb_cash_events (
	event_name VARCHAR(32) NOT NULL DEFAULT '',
	event_data VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (event_name)
);

CREATE TABLE phpbb_cash_exchange (
	ex_cash_id1 INT(11) NOT NULL DEFAULT '0',
	ex_cash_id2 INT(11) NOT NULL DEFAULT '0',
	ex_cash_enabled INT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (ex_cash_id1,ex_cash_id2)
);

CREATE TABLE phpbb_cash_groups (
	group_id MEDIUMINT(6) NOT NULL DEFAULT '0',
	group_type TINYINT(2) NOT NULL DEFAULT '0',
	cash_id SMALLINT(6) NOT NULL DEFAULT '0',
	cash_perpost INT(11) NOT NULL DEFAULT '0',
	cash_postbonus INT(11) NOT NULL DEFAULT '0',
	cash_perreply INT(11) NOT NULL DEFAULT '0',
	cash_perchar INT(11) NOT NULL DEFAULT '0',
	cash_maxearn INT(11) NOT NULL DEFAULT '0',
	cash_perpm INT(11) NOT NULL DEFAULT '0',
	cash_allowance TINYINT(1) NOT NULL DEFAULT '0',
	cash_allowanceamount INT(11) NOT NULL DEFAULT '0',
	cash_allowancetime TINYINT(2) NOT NULL DEFAULT '2',
	cash_allowancenext INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (group_id,group_type,cash_id)
);

CREATE TABLE phpbb_cash_log (
	log_id INT(11) NOT NULL auto_increment,
	log_time INT(11) NOT NULL DEFAULT '0',
	log_type SMALLINT(6) NOT NULL DEFAULT '0',
	log_action VARCHAR(255) NOT NULL DEFAULT '',
	log_text VARCHAR(255) NOT NULL DEFAULT '',
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
	test VARCHAR(50) DEFAULT '',
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
	topic_id MEDIUMINT(8) unsigned NOT NULL default '0',
	registration_user_id MEDIUMINT(8) NOT NULL default '0',
	registration_user_ip VARCHAR(40) NOT NULL default '',
	registration_time INT(11) NOT NULL default '0',
	registration_status TINYINT(1) NOT NULL default '0',
	KEY topic_id (topic_id),
	KEY registration_user_id (registration_user_id),
	KEY registration_user_ip (registration_user_ip)
);

CREATE TABLE phpbb_registration_desc (
	reg_id MEDIUMINT(8) unsigned NOT NULL auto_increment,
	topic_id MEDIUMINT(8) unsigned NOT NULL default '0',
	reg_active TINYINT(1) NOT NULL default '0',
	reg_max_option1 SMALLINT(5) unsigned NOT NULL default '0',
	reg_max_option2 SMALLINT(5) unsigned NOT NULL default '0',
	reg_max_option3 SMALLINT(5) unsigned NOT NULL default '0',
	reg_start INT(11) NOT NULL default '0',
	reg_length INT(11) NOT NULL default '0',
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
	`group_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_option_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_role_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_setting` TINYINT(2) NOT NULL DEFAULT '0',
	KEY `group_id` (`group_id`),
	KEY `auth_opt_id` (`auth_option_id`),
	KEY `auth_role_id` (`auth_role_id`)
);

CREATE TABLE `phpbb_acl_options` (
	`auth_option_id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`auth_option` VARCHAR(50) COLLATE utf8_bin NOT NULL DEFAULT '',
	`is_global` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`is_local` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	`founder_only` TINYINT(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`auth_option_id`),
	UNIQUE KEY `auth_option` (`auth_option`)
);

CREATE TABLE `phpbb_acl_roles` (
	`role_id` MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
	`role_name` VARCHAR(255) COLLATE utf8_bin NOT NULL DEFAULT '',
	`role_description` text COLLATE utf8_bin NOT NULL,
	`role_type` VARCHAR(10) COLLATE utf8_bin NOT NULL DEFAULT '',
	`role_order` SMALLINT(4) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`role_id`),
	KEY `role_type` (`role_type`),
	KEY `role_order` (`role_order`)
);

CREATE TABLE `phpbb_acl_roles_data` (
	`role_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_option_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_setting` TINYINT(2) NOT NULL DEFAULT '0',
	PRIMARY KEY (`role_id`,`auth_option_id`),
	KEY `ath_op_id` (`auth_option_id`)
);

CREATE TABLE `phpbb_acl_users` (
	`user_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`forum_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_option_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_role_id` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`auth_setting` TINYINT(2) NOT NULL DEFAULT '0',
	KEY `user_id` (`user_id`),
	KEY `auth_option_id` (`auth_option_id`),
	KEY `auth_role_id` (`auth_role_id`)
);

## AUTH SYSTEM - END

## IMAGES - BEGIN

CREATE TABLE `phpbb_images` (
	`pic_id` INT(11) unsigned NOT NULL auto_increment,
	`post_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`attach_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`pic_filename` VARCHAR(255) NOT NULL DEFAULT '',
	`pic_size` INT(15) unsigned NOT NULL DEFAULT '0',
	`pic_title` VARCHAR(255) NOT NULL DEFAULT '',
	`pic_desc` TEXT NOT NULL,
	`pic_user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`pic_user_ip` VARCHAR(40) NOT NULL DEFAULT '0',
	`pic_time` INT(11) unsigned NOT NULL DEFAULT '0',
	`pic_approval` TINYINT(3) NOT NULL DEFAULT '1',
	`exif` text NOT NULL,
	`camera_model` varchar(255) DEFAULT '' NOT NULL,
	`lens` varchar(255) DEFAULT '' NOT NULL,
	`focal_length` varchar(255) DEFAULT '' NOT NULL,
	`exposure` varchar(255) DEFAULT '' NOT NULL,
	`aperture` varchar(255) DEFAULT '' NOT NULL,
	`iso` varchar(255) DEFAULT '' NOT NULL,
	PRIMARY KEY (`pic_id`),
	KEY `pic_user_id` (`pic_user_id`),
	KEY `pic_time` (`pic_time`)
);

## IMAGES - END

## NOTIFICATIONS - BEGIN

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

## NOTIFICATIONS - END
