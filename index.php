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
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_groups.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

/*
$cms_page_id = '0';
$cms_page_name = 'index';
*/
$auth_level_req = $board_config['auth_view_portal'];
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
//$cms_global_blocks = ($board_config['wide_blocks_portal'] == 1) ? true : false;

define('PORTAL_INIT', true);
include($phpbb_root_path . 'includes/functions_cms.' . $phpEx);
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
	$group_content = explode(",",$layout_row['groups']);
	for ($i = 0; $i < count($group_content); $i++)
	{
		if(in_array(intval($group_content[$i]), cms_groups($userdata['user_id'])))
		{
			$not_group_allowed = false;
		}
	}
}

if(($layout_template == '') || (!$lview) || ($not_group_allowed))
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
$page_title = $board_config['sitename'];
$meta_description = '';
$meta_keywords = '';
//define('SHOW_ONLINE', true);
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

// Tell the template class which template to use.
$template->set_filenames(array('body' => 'layout/' . $layout_template));

if (($userdata['user_level'] == ADMIN) || ($userdata['user_cms_level'] >= CMS_CONTENT_MANAGER))
{
	$cms_acp_url = '<br /><br /><div style="text-align:center;">';
	$cms_acp_url .= '<a href="' . append_sid('cms.' . $phpEx . '?mode=blocks&amp;l_id=' . $layout) . '">' . $lang['CMS_ACP'] . '</a>';
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

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>