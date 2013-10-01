## `phpbb_link_categories`

CREATE TABLE `phpbb_link_categories` (
	`cat_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`cat_title` VARCHAR(100) NOT NULL DEFAULT '',
	`cat_order` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`cat_id`),
	KEY `cat_order` (`cat_order`)
);

## `phpbb_link_config`

CREATE TABLE `phpbb_link_config` (
	`config_name` VARCHAR(255) NOT NULL DEFAULT '',
	`config_value` VARCHAR(255) NOT NULL DEFAULT ''
);

## `phpbb_links`

CREATE TABLE `phpbb_links` (
	`link_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
	`link_title` VARCHAR(100) NOT NULL DEFAULT '',
	`link_desc` VARCHAR(255) DEFAULT NULL,
	`link_category` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
	`link_url` VARCHAR(100) NOT NULL DEFAULT '',
	`link_logo_src` VARCHAR(120) DEFAULT NULL,
	`link_joined` INT(11) NOT NULL DEFAULT '0',
	`link_active` TINYINT(1) NOT NULL DEFAULT '0',
	`link_hits` INT(10) unsigned NOT NULL DEFAULT '0',
	`user_id` MEDIUMINT(8) NOT NULL DEFAULT '0',
	`user_ip` VARCHAR(40) NOT NULL DEFAULT '',
	`last_user_ip` VARCHAR(40) NOT NULL DEFAULT '',
	PRIMARY KEY (`link_id`)
);