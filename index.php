<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('IN_CMS_PAGE', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

define('PORTAL_INIT', true);
include(IP_ROOT_PATH . 'includes/functions_cms.' . PHP_EXT);
cms_config_init($cms_config_vars);

if(!empty($_GET['page']))
{
	$layout = intval($_GET['page']);
	$layout = ($layout <= 0) ? $cms_config_vars['default_portal'] : $layout;
}
else
{
	$layout = $cms_config_vars['default_portal'];
}

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
$layout_name = ($layout == $cms_config_vars['default_portal']) ? false : $layout_row['name'];
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

if(empty($layout_template))
{
	$layout = $cms_config_vars['default_portal'];
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
	$layout_name = false;
	$layout_template = $layout_row['template'];
	$cms_global_blocks = ($layout_row['global_blocks'] == 0) ? false : true;
	$cms_page_nav = ($layout_row['page_nav'] == 0) ? false : true;
}

// Start output of page
$page_title = ip_stripslashes($board_config['sitename']);
$meta_description = '';
$meta_keywords = '';
//define('SHOW_ONLINE', true);
if ($layout != $cms_config_vars['default_portal'])
{
	$page_title = ip_stripslashes($board_config['sitename']) . ' - ' . $layout_name;
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $layout_name . '</a>';
}
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
	$cms_acp_url = '';
}

$layout_name_add = '';
$query = $_SERVER['QUERY_STRING'];
if (preg_match("/news=categories/", $query))
{
	$layout_name_add = $lang['NEWS_CAT'];
}
elseif (preg_match("/news=archives/", $query))
{
	$layout_name_add = $lang['NEWS_ARC'];
}

if ($layout_name_add != '')
{
	if ($layout_name == false)
	{
		$layout_name = htmlspecialchars($layout_name_add);
	}
	else
	{
		$layout_name = htmlspecialchars($layout_name . ' - ' . $layout_name_add);
	}
}

$template->assign_vars(array(
	'CMS_PAGE_TITLE' => ($layout_name == false) ? false : $layout_name,
	'S_PAGE_NAV' => $cms_page_nav,
	'S_GLOBAL_BLOCKS' => $cms_global_blocks,
	)
);

cms_parse_blocks($layout, false, false, '');

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>