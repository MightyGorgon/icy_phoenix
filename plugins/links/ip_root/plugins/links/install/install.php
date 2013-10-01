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

$install_data = array(
	'1.0.0' => array(
		'sql' => array(
			// schema
			"CREATE TABLE `" . $table_prefix . "link_categories` (
				`cat_id` MEDIUMINT(8) unsigned NOT NULL auto_increment,
				`cat_title` VARCHAR(100) NOT NULL DEFAULT '',
				`cat_order` MEDIUMINT(8) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`cat_id`),
				KEY `cat_order` (`cat_order`)
			);",

			"CREATE TABLE `" . $table_prefix . "link_config` (
				`config_name` VARCHAR(255) NOT NULL DEFAULT '',
				`config_value` VARCHAR(255) NOT NULL DEFAULT ''
			);",

			"CREATE TABLE `" . $table_prefix . "links` (
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
			);",

			// basic
			"INSERT INTO `" . $table_prefix . "cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(11, 0, 'Links', '', 'links', 0, 0, '', 1);",

			"INSERT INTO `" . $table_prefix . "cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('links', 'links', 'links.php', 0, '', 0, '');",

			"INSERT INTO `" . $table_prefix . "cms_nav_menu` (`menu_item_id`, `menu_id`, `menu_parent_id`, `cat_id`, `cat_parent_id`, `menu_default`, `menu_status`, `menu_order`, `menu_icon`, `menu_name_lang`, `menu_name`, `menu_desc`, `menu_link`, `menu_link_external`, `auth_view`, `auth_view_group`) VALUES (22, 0, 1, 0, 1, 16, 1, 16, '', '', 'Links', 'Links', 'links.php', 0, 0, 0);",
		),
		'functions' => array(),
	),
);

$uninstall_data = array(
	'sql' => array(
		// schema
		"DROP TABLE `" . $table_prefix . "link_categories`;",
		"DROP TABLE `" . $table_prefix . "link_config`;",
		"DROP TABLE `" . $table_prefix . "links`;",

		// basic
		"DELETE FROM " . $table_prefix . "cms_block_settings WHERE name = 'Links';",

		"DELETE FROM " . $table_prefix . "cms_layout_special WHERE page_id = 'links';",

		"DELETE FROM " . $table_prefix . "cms_nav_menu WHERE menu_links = 'links.php';",
	),
	'functions' => array(
		//@todo clean blocks
	),
);

?>