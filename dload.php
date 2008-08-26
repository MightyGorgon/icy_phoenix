<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);
define('IN_DOWNLOAD', true);
//define('MG_KILL_CTRACK', true);
define('MG_CTRACK_FLAG', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '11';
$cms_page_name = 'download';
$auth_level_req = $board_config['auth_view_download'];
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
$cms_global_blocks = ($board_config['wide_blocks_download'] == 1) ? true : false;


//===================================================
// Include the common file
//===================================================

include($phpbb_root_path . PA_FILE_DB_PATH . 'pafiledb_common.' . $phpEx);

//===================================================
// Get action variable other wise set it to the main
//===================================================

$action = (isset($_REQUEST['action'])) ? htmlspecialchars($_REQUEST['action']) : 'main';

//===================================================
// if the database disabled give them a nice message
//===================================================

if(intval($pafiledb_config['settings_disable']))
{
	message_die(GENERAL_MESSAGE, $lang['pafiledb_disable']);
}

//===================================================
// an array of all expected actions
//===================================================

$actions = array(
					'download' => 'download',
					'category' => 'category',
					'file' => 'file',
					'viewall' => 'viewall',
					'search' => 'search',
					'license' => 'license',
					'rate' => 'rate',
					'email' => 'email',
					'stats' => 'stats',
					'toplist' => 'toplist',
					'user_upload' => 'user_upload',
					'post_comment' => 'post_comment',
					'mcp' => 'mcp',
					'ucp' => 'ucp',
					'main' => 'main'
				);


//===================================================
// Lets Build the page
//===================================================

$action_mod = array();
$action_mod = explode('?', $action);

$pafiledb->module($actions[$action_mod[0]]);
$pafiledb->modules[$actions[$action_mod[0]]]->main($action_mod[1]);

?>