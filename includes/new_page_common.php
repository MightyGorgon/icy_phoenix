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

$meta_content['page_title'] = ($meta_content['page_title'] == '') ? $config['sitename'] : $meta_content['page_title'];
$meta_content['description'] = ($meta_content['description'] == '') ? '' : $meta_content['description'];
$meta_content['keywords'] = ($meta_content['keywords'] == '') ? '' : $meta_content['keywords'];

if (!defined('CMS_INIT'))
{
	define('CMS_INIT', true);
}
$cms_config_vars = (empty($cms_config_vars)) ? $cache->obtain_cms_config() : $cms_config_vars;
$cms_config_global_blocks = (empty($cms_config_global_blocks)) ? $cache->obtain_cms_global_blocks_config(false) : $cms_config_global_blocks;

if (defined('IN_CMS_USERS') && !empty($cms_config_vars['style']))
{
	$config['default_style'] = $cms_config_vars['style'];
}

if (empty($user->data))
{
	// Start session management
	$user->session_begin();
	$auth->acl($user->data);
	$user->setup();
	// End session management
}

if (defined('IN_CMS_PAGE_INDEX'))
{
	if(!empty($_GET['page']))
	{
		$layout = intval($_GET['page']);
		$layout = ($layout <= 0) ? $cms_config_vars['default_portal'] : $layout;
	}
	else
	{
		$layout = $cms_config_vars['default_portal'];
	}
}
else
{
	$page_filename = $db->sql_escape(basename($_SERVER['SCRIPT_NAME']));

	$sql = "SELECT * FROM " . $ip_cms->tables['layout_table'] . " WHERE filename = '" . $page_filename . "'";
	$layout_result = $db->sql_query($sql, 0, 'cms_', CMS_CACHE_FOLDER);
	while ($row = $db->sql_fetchrow($layout_result))
	{
		$layout_row = $row;
	}
	$db->sql_freeresult($layout_result);

	$layout = intval($layout_row['lid']);
	$layout = ($layout <= 0) ? $cms_config_vars['default_portal'] : $layout;
}

if (!($cms_config_vars['status']))
{
	$layout = $cms_config_vars['locked_page'] > 0 ? $cms_config_vars['locked_page'] : $layout;
}

$cms_default_page = (($layout == $cms_config_vars['default_portal']) ? true : false);

$sql = "SELECT * FROM " . $ip_cms->tables['layout_table'] . " WHERE lid = '" . $layout . "'";
$layout_result = $db->sql_query($sql, 0, 'cms_', CMS_CACHE_FOLDER);
$layout_row = $db->sql_fetchrow($layout_result);
$db->sql_freeresult($layout_result);
$layout_name = $cms_default_page ? false : (!empty($layout_row['name']) ? $layout_row['name'] : (!empty($meta_content['page_title']) ? $meta_content['page_title'] : false));
$layout_template = $layout_row['template'];
$cms_page['global_blocks'] = ($layout_row['global_blocks'] == 0) ? false : true;
$cms_page['page_nav'] = ($layout_row['page_nav'] == 0) ? false : true;

$is_auth_view = false;
$auth_level = $ip_cms->cms_auth_view();
$is_auth_view = in_array($layout_row['view'], $auth_level);

if(!$is_auth_view)
{
	if(!empty($layout_row['groups']))
	{
		$is_auth_view = false;
		$group_content = explode(',', $layout_row['groups']);
		for ($i = 0; $i < sizeof($group_content); $i++)
		{
			if(in_array(intval($group_content[$i]), $ip_cms->cms_groups($user->data['user_id'])))
			{
				$is_auth_view = true;
				break;
			}
		}
	}
}

if(!$is_auth_view)
{
	if (!$user->data['session_logged_in'])
	{
		$page_array = array();
		$page_array = extract_current_page(IP_ROOT_PATH);
		redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . str_replace(('.' . PHP_EXT . '?'), ('.' . PHP_EXT . '&'), $page_array['page']), true));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
	}
}

if(empty($layout_template))
{
	$layout = $cms_config_vars['default_portal'];
	$sql = "SELECT * FROM " . $ip_cms->tables['layout_table'] . " WHERE lid = '" . $layout . "'";
	$layout_result = $db->sql_query($sql, 0, 'cms_', CMS_CACHE_FOLDER);
	while ($row = $db->sql_fetchrow($layout_result))
	{
		$layout_row = $row;
	}
	$db->sql_freeresult($layout_result);
	$layout_name = false;
	$layout_template = $layout_row['template'];
	$cms_page['global_blocks'] = ($layout_row['global_blocks'] == 0) ? false : true;
	$cms_page['page_nav'] = ($layout_row['page_nav'] == 0) ? false : true;
}


if (!$cms_default_page)
{
	$meta_content['page_title'] = (!empty($layout_name) ? $layout_name : $config['sitename']);
	$breadcrumbs['address'] = $lang['Nav_Separator'] . '<a class="nav-current" href="#">' . $meta_content['page_title'] . '</a>';
}

// Let's remove $auth->acl_get('a_') until I finish coding permissions properly... and also add/remove 'a_' when users are added/removed from administrators in ACP
//$is_admin = (($user->data['user_level'] == ADMIN) || $auth->acl_get('a_')) ? true : false;
$is_admin = ($user->data['user_level'] == ADMIN) ? true : false;
$cms_acp_url = '';
if ($is_admin || $auth->acl_get('cms_admin') || !empty($user->data['user_cms_auth']['cmsl_admin'][$layout]))
{
	$cms_acp_url = '<br /><br /><div style="text-align: center;">';
	$cms_acp_url .= '<a href="' . append_sid('cms.' . PHP_EXT . '?mode=blocks&amp;l_id=' . $layout . (!empty($user->session_id) ? ('&amp;sid=' . $user->session_id) : '')) . '">' . $lang['CMS_ACP'] . '</a>';
	$cms_acp_url .= '</div>';
}

$layout_name_add = '';
$query = $_SERVER['QUERY_STRING'];
if (preg_match("/news=categories/", $query))
{
	$layout_name_add = $lang['LINK_NEWS_CAT'];
}
elseif (preg_match("/news=archives/", $query))
{
	$layout_name_add = $lang['LINK_NEWS_ARC'];
}

if (!empty($layout_name_add))
{
	$layout_name = (empty($layout_name) ? '' : ($layout_name . ' - ')) . $layout_name_add;
}

$template->assign_vars(array(
	'CMS_PAGE_TITLE' => (!empty($layout_name) ? $layout_name : $config['sitename']),
	'S_PAGE_NAV' => $cms_page['page_nav'],
	'S_GLOBAL_BLOCKS' => $cms_page['global_blocks'],
	)
);

// Start Blocks
$ip_cms->cms_parse_blocks($layout, false, false, '');

full_page_generation('layout/' . $layout_template, $meta_content['page_title'], $meta_content['description'], $meta_content['keywords']);

?>