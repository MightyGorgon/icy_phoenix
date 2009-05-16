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

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

define('PORTAL_INIT', true);
include(IP_ROOT_PATH . 'includes/functions_cms.' . PHP_EXT);
cms_config_init($cms_config_vars);

$page_filename = $db->sql_escape(basename($_SERVER['PHP_SELF']));

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

$is_auth_view = false;
if ($userdata['user_id'] == ANONYMOUS)
{
	$is_auth_view = in_array($layout_row['view'], array(0, 1));
}
else
{
	switch($userdata['user_level'])
	{
		case USER:
			$is_auth_view = in_array($layout_row['view'], array(0, 2));
			break;
		case MOD:
			$is_auth_view = in_array($layout_row['view'], array(0, 2, 3));
			break;
		case ADMIN:
			$is_auth_view = in_array($layout_row['view'], array(0, 1, 2, 3, 4));
			break;
		default:
			$is_auth_view = in_array($layout_row['view'], array(0));
	}
}

if(!$is_auth_view)
{
	if(!empty($layout_row['groups']))
	{
		$is_auth_view = false;
		$group_content = explode(',', $layout_row['groups']);
		for ($i = 0; $i < count($group_content); $i++)
		{
			if(in_array(intval($group_content[$i]), cms_groups($userdata['user_id'])))
			{
				$is_auth_view = true;
				break;
			}
		}
	}
}

if(!$is_auth_view)
{
	if (!$userdata['session_logged_in'])
	{
		$page_array = array();
		$page_array = extract_current_page(IP_ROOT_PATH);
		redirect(append_sid(IP_ROOT_PATH . LOGIN_MG . '?redirect=' . str_replace(('.' . PHP_EXT . '?'), ('.' . PHP_EXT . '&'), $page_array['page']), true));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}

// Start output of page
$page_title = $board_config['sitename'] . ' - ' . $layout_name;
$meta_description = '';
$meta_keywords = '';
$breadcrumbs_address = $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $layout_name . '</a>';
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