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
* masterdavid - Ronald John David
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

/*
$cms_page_id = '0';
$cms_page_name = 'custom_pages';
*/
$auth_level_req = $board_config['auth_view_custom_pages'];
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
//$cms_global_blocks = ($board_config['wide_blocks_custom_pages'] == 1) ? true : false;

define('PORTAL_INIT', true);
include(IP_ROOT_PATH . 'includes/functions_cms.' . PHP_EXT);
cms_config_init($cms_config_vars);

$page_filename = mysql_real_escape_string(basename($_SERVER['PHP_SELF']));

$sql = "SELECT * FROM " . CMS_LAYOUT_TABLE . " WHERE filename = '" . $page_filename . "'";
if(!($layout_result = $db->sql_query($sql, false, 'cms_')))
{
	message_die(CRITICAL_ERROR, "Could not query portal layout information", "", __LINE__, __FILE__, $sql);
}
while ($row = $db->sql_fetchrow($layout_result))
{
	$layout_row = $row;
}
$db->sql_freeresult($layout_result);

$layout = intval($layout_row['lid']);
$layout = ($layout <= 0) ? $cms_config_vars['default_portal'] : $layout;

$sql = "SELECT * FROM " . CMS_LAYOUT_TABLE . " WHERE lid = '" . $layout . "'";
if(!($layout_result = $db->sql_query($sql, false, 'cms_')))
{
	message_die(CRITICAL_ERROR, "Could not query portal layout information", "", __LINE__, __FILE__, $sql);
}
while ($row = $db->sql_fetchrow($layout_result))
{
	$layout_row = $row;
}
$db->sql_freeresult($layout_result);
$layout_name = $layout_row['name'];
$layout_template = $layout_row['template'];
$cms_global_blocks = ($layout_row['global_blocks'] == 0) ? false : true;
$cms_page_nav = ($layout_row['page_nav'] == 0) ? false : true;

if ($userdata['user_id'] == ANONYMOUS)
{
	$lview = in_array($layout_row['view'], array(0,1));
}
else
{
	switch($userdata['user_level'])
	{
		case USER:
			$lview = in_array($layout_row['view'], array(0,2));
			break;
		case MOD:
			$lview = in_array($layout_row['view'], array(0,2,3));
			break;
		case ADMIN:
			$lview = in_array($layout_row['view'], array(0,1,2,3,4));
			break;
		default:
			$lview = in_array($layout_row['view'], array(0));
	}
}

$not_group_allowed = false;
if(!empty($layout_row['groups']))
{
	$not_group_allowed = true;
	$group_content = explode(",", $layout_row['groups']);
	for ($i = 0; $i < count($group_content); $i++)
	{
		if(in_array(intval($group_content[$i]), cms_groups($userdata['user_id'])))
		{
			$not_group_allowed = false;
		}
	}
}

if(($layout_template=='') || (!$lview) || ($not_group_allowed))
{
	$layout = $cms_config_vars['default_portal'];
	$sql = "SELECT template, global_blocks, page_nav FROM " . CMS_LAYOUT_TABLE . " WHERE lid = '" . $layout . "'";
	if(!($layout_result = $db->sql_query($sql, false, 'cms_')))
	{
		message_die(CRITICAL_ERROR, "Could not query portal layout information", "", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($layout_result))
	{
		$layout_row = $row;
	}
	$db->sql_freeresult($layout_result);
	$layout_name = $layout_row['name'];
	$layout_template = $layout_row['template'];
	$cms_global_blocks = ($layout_row['global_blocks'] == 0) ? false : true;
	$cms_page_nav = ($layout_row['page_nav'] == 0) ? false : true;
}

// Start output of page
$page_title = $board_config['sitename'];
$meta_description = '';
$meta_keywords = '';
//define('SHOW_ONLINE', true);
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

// Tell the template class which template to use.
$template->set_filenames(array('body' => 'layout/' . $layout_template));

if (($userdata['user_level'] == ADMIN) || ($userdata['user_cms_level'] >= CMS_CONTENT_MANAGER))
{
	$cms_acp_url = '<br /><br /><div style="text-align:center;">';
	$cms_acp_url .= '<a href="' . append_sid('cms.' . PHP_EXT . '?mode=blocks&amp;l_id=' . $layout) . '">' . $lang['CMS_ACP'] . '</a>';
	$cms_acp_url .= '</div>';
}
else
{
	$cms_acp_url;
}

$query = $_SERVER['QUERY_STRING'];
if (preg_match("/news=categories/", $query))
{
	$layout_name = $layout_name . ' - ' . $lang['NEWS_CAT'];
}
elseif (preg_match("/news=archives/", $query))
{
	$layout_name = $layout_name . ' - ' . $lang['NEWS_ARC'];
}

$template->assign_vars(array(
	'CMS_PAGE_TITLE' => htmlspecialchars($layout_name),
	'S_PAGE_NAV' => $cms_page_nav,
	'S_GLOBAL_BLOCKS' => $cms_global_blocks,
	)
);

// Start Blocks
cms_parse_blocks($layout, false, false, '');

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
?>