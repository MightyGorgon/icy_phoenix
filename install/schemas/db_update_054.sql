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
INSERT INTO phpbb_tickets_cat (ticket_cat_title, ticket_cat_des) VALUES ('General', 'General');
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
RENAME TABLE `phpbb_xs_news_cfg` TO `_old_phpbb_xs_news_cfg`";

ALTER TABLE `phpbb_ina_scores` ADD `user_plays` int(6) default '0' AFTER `score`;
ALTER TABLE `phpbb_ina_scores` ADD `play_time` int(11) default '0' AFTER `user_plays`;

UPDATE `phpbb_cms_blocks` SET blockfile = REPLACE(blockfile,'blocks_imp_','');

DELETE FROM `phpbb_config` WHERE config_name = 'smart_header';




########################################
##              BUILD 056             ##
########################################



########################################
##              BUILD 057             ##
########################################



########################################
##              BUILD 058             ##
########################################



########################################
##              BUILD 059             ##
########################################



########################################
##              BUILD 060             ##
########################################





#####################

##UPDATE phpbb_config SET config_value = '2' WHERE config_name = 'main_admin_id';

#-- DB CHANGES FOR VERSIONING
UPDATE phpbb_attachments_config SET config_value = '2.4.5' WHERE config_name = 'attach_version';
UPDATE phpbb_config SET config_value = '3.0.7' WHERE config_name = 'upi2db_version';
UPDATE phpbb_album_config SET config_value = '1.5.0' WHERE config_name = 'fap_version';
UPDATE phpbb_config SET config_value = '.0.23' WHERE config_name = 'version';
UPDATE phpbb_config SET config_value = '1.3.2.55' WHERE config_name = 'ip_version';
