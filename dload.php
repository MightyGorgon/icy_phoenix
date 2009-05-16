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
//define('MG_KILL_CTRACK', true);
define('MG_CTRACK_FLAG', true);
define('IN_DOWNLOAD', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = 'download';
$cms_page_nav = (!empty($cms_config_layouts[$cms_page_id]['page_nav']) ? true : false);
$cms_global_blocks = (!empty($cms_config_layouts[$cms_page_id]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page_id]['view']) ? $cms_config_layouts[$cms_page_id]['view'] : AUTH_ALL);
check_page_auth($cms_page_id, $cms_auth_level);

include(IP_ROOT_PATH . 'includes/pafiledb_common.' . PHP_EXT);

if(intval($pafiledb_config['settings_disable']))
{
	message_die(GENERAL_MESSAGE, $lang['pafiledb_disable']);
}

$action = (isset($_REQUEST['action'])) ? htmlspecialchars($_REQUEST['action']) : 'main';

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

$action_mod = array();
$action_mod = explode('?', $action);

$pafiledb->module($actions[$action_mod[0]]);
$pafiledb->modules[$actions[$action_mod[0]]]->main($action_mod[1]);

?>