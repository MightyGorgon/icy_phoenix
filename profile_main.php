<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if (!$userdata['session_logged_in'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=profile_main.' . PHP_EXT, true));
	exit;
}

//Start Output of Page
$link_name = '';
$nav_server_url = create_server_url();
$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('profile_main.' . PHP_EXT) . '"' . (!empty($link_name) ? '' : ' class="nav-current"') . '>' . $lang['Profile'] . '</a>' . (!empty($link_name) ? ($lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $link_name . '</a>') : '');

full_page_generation('profile_main_body.tpl', $lang['Profile'], '', '');

?>