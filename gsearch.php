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

define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$cms_page['page_id'] = 'search';
$cms_page['page_nav'] = (!empty($cms_config_layouts[$cms_page['page_id']]['page_nav']) ? true : false);
$cms_page['global_blocks'] = (!empty($cms_config_layouts[$cms_page['page_id']]['global_blocks']) ? true : false);
$cms_auth_level = (isset($cms_config_layouts[$cms_page['page_id']]['view']) ? $cms_config_layouts[$cms_page['page_id']]['view'] : AUTH_ALL);
// Skip page auth... we are in GSearch baby!
//check_page_auth($cms_page['page_id'], $cms_auth_level);

$google_q = request_var('q', '', true);
if (!empty($google_q))
{
	$template->assign_vars(array('GSEARCH_RESULTS' => true));
}

$template->assign_vars(array(
	'S_SEARCH_ACTION' => append_sid('gsearch.' . PHP_EXT),
	'S_SEARCH_DOMAIN' => preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name'])),
	//'S_SEARCH_DOMAIN' => 'icyphoenix.com',
	'S_ADSENSE_CODE' => (!empty($config['adsense_code']) ? ('<input type="hidden" name="client" value="' . $config['adsense_code'] . '" />') : ''),
	'L_SEARCH' => $lang['Search']
	)
);

full_page_generation('gsearch_body.tpl', $lang['Search'], '', '');

?>