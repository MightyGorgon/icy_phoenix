<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_groups.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if ( !$userdata['session_logged_in'] )
{
	redirect(append_sid(LOGIN_MG . '?redirect=profile_main.' . $phpEx, true));
	exit;
}

//Start Output of Page
$page_title = $lang['Profile'];
$meta_description = '';
$meta_keywords = '';
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

$template->set_filenames(array('body' => 'profile_main_body.tpl'));

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>