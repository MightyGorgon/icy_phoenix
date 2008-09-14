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
define('IN_ICYPHOENIX', true);
define('MG_CTRACK_FLAG', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_ajax_chat.' . PHP_EXT);

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
include(IP_ROOT_PATH . 'includes/ajax_shoutbox_inc.' . PHP_EXT);

?>