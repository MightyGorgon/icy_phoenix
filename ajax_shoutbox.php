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
* Javier B (kinfule@lycos.es)
*/

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);
define('MG_CTRACK_FLAG', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_ajax_chat.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip, false);
init_userprefs($userdata);
// End session management

/*
$cms_page_id = '0';
$cms_page_name = 'ajax_chat';
*/
$auth_level_req = $board_config['auth_view_ajax_chat'];
if ($auth_level_req > AUTH_ALL)
{
	if (($auth_level_req == AUTH_REG) && (!$userdata['session_logged_in']))
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
	if ($userdata['user_level'] != ADMIN)
	{
		if ($auth_level_req == AUTH_ADMIN)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
		if (($auth_level_req == AUTH_MOD) && ($userdata['user_level'] != MOD))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
		}
	}
}
$cms_global_blocks = ($board_config['wide_blocks_ajax_chat'] == 1) ? true : false;
// AJAX Chat currently doesn't have its own wide blocks
// I would shut wide blocks off since this may be run as stand alone
//$cms_global_blocks = ($board_config['wide_blocks_shoutbox'] == 1) ? true : false;
$cms_global_blocks = false;

$shoutbox_template_parse = true;
include($phpbb_root_path . 'includes/ajax_shoutbox_inc.' . $phpEx);

?>