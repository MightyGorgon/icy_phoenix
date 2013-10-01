INSERT INTO `phpbb_cms_layout_special` (`page_id`, `name`, `filename`, `global_blocks`, `config_vars`, `view`, `groups`) VALUES ('links', 'links', 'links.php', 0, '', 0, '');

INSERT INTO `phpbb_cms_block_settings` (`bs_id`, `user_id`, `name`, `content`, `blockfile`, `view`, `type`, `groups`, `locked`) VALUES(11, 0, 'Links', '', 'links', 0, 0, '', 1);


## `phpbb_link_categories`

INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (1, 'Arts', 1);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (2, 'Business', 2);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (3, 'Children and Teens', 3);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (4, 'Computers', 4);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (5, 'Games', 5);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (6, 'Health', 6);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (7, 'Home', 7);
INSERT INTO `phpbb_link_categories` (`cat_id`, `cat_title`, `cat_order`) VALUES (8, 'News', 8);

## `phpbb_link_config`

INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('width', '88');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('height', '31');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('linkspp', '10');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('display_interval', '6000');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('display_logo_num', '10');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('display_links_logo', '1');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('email_notify', '1');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('pm_notify', '0');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('lock_submit_site', '0');
INSERT INTO `phpbb_link_config` (`config_name`, `config_value`) VALUES ('allow_no_logo', '0');

## `phpbb_links`

INSERT INTO `phpbb_links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (1, 'Icy Phoenix Official Website', 'Icy Phoenix', 4, 'http://www.icyphoenix.com/', 'images/links/banner_ip.gif', 1241136000, 1, 0, 2, '', '');
INSERT INTO `phpbb_links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (2, 'Mighty Gorgon Community', 'Mighty Gorgon Community', 4, 'http://www.mightygorgon.com/', 'images/links/banner_mightygorgon.gif', 1241136000, 1, 0, 2, '', '');
INSERT INTO `phpbb_links` (`link_id`, `link_title`, `link_desc`, `link_category`, `link_url`, `link_logo_src`, `link_joined`, `link_active`, `link_hits`, `user_id`, `user_ip`, `last_user_ip`) VALUES (3, 'phpBB Official Website', 'Official phpBB Website', 4, 'http://www.phpbb.com/', 'images/links/banner_phpbb88a.gif', 1241136000, 1, 0, 2, '', '');
