<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* Bicet (bicets@gmail.com)
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management
require(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_main_link.' . PHP_EXT);

$sql = "SELECT *
		FROM ". LINK_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query Link config information", "", __LINE__, __FILE__, $sql);
}
while( $row = $db->sql_fetchrow($result) )
{
	$link_config_name = $row['config_name'];
	$link_config_value = $row['config_value'];
	$link_config[$link_config_name] = $link_config_value;
	$linkspp=$link_config['linkspp'];
}

$gen_simple_header = true;
$page_title = $lang['Link_ME'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
$template->set_filenames(array(
	'body' => 'links_me.tpl')
);

$template->assign_vars(array(
	'L_LINK_US' => $lang['Link_us'] . ip_stripslashes($board_config['sitename']),
	'L_LINK_US_EXPLAIN' => sprintf($lang['Link_us_explain'], ip_stripslashes($board_config['sitename'])),'L_SUBMIT' => $lang['Submit'],
	'L_CLOSE_WINDOW'		=> $lang['Close_window'],
	'LINK_US_SYNTAX' => str_replace(" ", "&nbsp;", sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $link_config['site_url'], $link_config['site_logo'], $link_config['width'],$link_config['height'], ip_stripslashes($board_config['sitename']))),

	'U_SITE_LOGO' => $link_config['site_logo'],
	)
);

$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
?>
