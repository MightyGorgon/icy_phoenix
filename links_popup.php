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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management
require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main_link.' . $phpEx);

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
include($phpbb_root_path . 'includes/page_header.' . $phpEx);
$template->set_filenames(array(
	'body' => 'links_me.tpl')
);

$template->assign_vars(array(
	'L_LINK_US' => $lang['Link_us'] . $board_config['sitename'],
	'L_LINK_US_EXPLAIN' => sprintf($lang['Link_us_explain'], $board_config['sitename']),'L_SUBMIT' => $lang['Submit'],
	'L_CLOSE_WINDOW'		=> $lang['Close_window'],
	'LINK_US_SYNTAX' => str_replace(" ", "&nbsp;", sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $link_config['site_url'], $link_config['site_logo'], $link_config['width'],$link_config['height'], $board_config['sitename'])),

	'U_SITE_LOGO' => $link_config['site_logo'],
	)
);

$template->pparse('body');
include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
?>
