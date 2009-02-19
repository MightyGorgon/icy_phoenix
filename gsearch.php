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

// CTracker_Ignore: File checked by human
define('MG_KILL_CTRACK', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page_id = '5';
$cms_page_name = 'search';
//check_page_auth($cms_page_id, $cms_page_name);
$cms_global_blocks = ($board_config['wide_blocks_' . $cms_page_name] == 1) ? true : false;

$page_title = $lang['Search'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'gsearch_body.tpl'));

$google_q = request_var('q', '');
if (!empty($google_q))
{
	$template->assign_vars(array('GSEARCH_RESULTS' => true));
}

$template->assign_vars(array(
	'S_SEARCH_ACTION' => append_sid('gsearch.' . PHP_EXT),
	'S_SEARCH_DOMAIN' => preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name'])),
	//'S_SEARCH_DOMAIN' => 'icyphoenix.com',
	'S_ADSENSE_CODE' => (!empty($board_config['adsense_code']) ? ('<input type="hidden" name="client" value="' . $board_config['adsense_code'] . '" />') : ''),
	'L_SEARCH' => $lang['Search']
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>