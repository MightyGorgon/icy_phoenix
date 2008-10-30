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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!defined('IN_ADMIN'))
{
	define('IN_ADMIN', true);
}

define('MG_KILL_CTRACK', true);
//define('MG_CTRACK_FLAG', true);

// Include files
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include_once(IP_ROOT_PATH . 'includes/functions_jr_admin.' . PHP_EXT);
//find_lang_file_nivisec('lang_jr_admin');

if (!$userdata['session_logged_in'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=' . ADM . '/index.' . PHP_EXT, true));
}
elseif (!jr_admin_secure(basename($_SERVER['REQUEST_URI'])))
{
	message_die(GENERAL_ERROR, $lang['Error_Module_ID'], '', __LINE__, __FILE__);
}


if ($_GET['sid'] != $userdata['session_id'])
{
	redirect('index.' . PHP_EXT . '?sid=' . $userdata['session_id']);
}

if (!$userdata['session_admin'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=' . ADM . '/index.' . PHP_EXT . '&admin=1', true));
}

if (empty($no_page_header))
{
	// Not including the pageheader can be neccesarry if META tags are needed in the calling script.
	include('page_header_admin.' . PHP_EXT);
}

?>