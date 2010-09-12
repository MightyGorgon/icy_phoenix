<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_CMS', true);
define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_menu.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_menu_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/class_cms_admin.' . PHP_EXT);

$cms_admin = new cms_admin();
$cms_admin->root = CMS_PAGE_CMS;
//$cms_admin->init_vars($mode_array, $action_array);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

setup_extra_lang(array('lang_cms', 'lang_dyn_menu'));

$access_allowed = get_cms_access_auth('cms_menu');

if (!$access_allowed)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

if (!$userdata['session_admin'])
{
	redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=cms_menu.' . PHP_EXT . '&admin=1', true));
}

if (!empty($_REQUEST['mode']) && !empty($_GET['mode']) && ($_POST['mode'] != $_GET['mode']))
{
	$_REQUEST['mode'] = $_GET['mode'];
	$_POST['mode'] = $_GET['mode'];
}
$mode_array = array('menu_item', 'menu_block', 'menu_list');
$mode = request_var('mode', '');
$mode = (in_array($mode, $mode_array) ? $mode : false);

if(isset($_POST['action_update']))
{
	$mode = 'menu_block';
}

$item_type = request_var('item_type', '');
$item_type = empty($item_type) ? false : $item_type;
$item_type = isset($_POST['add_cat']) ? 'category_item' : $item_type;

if (!empty($_REQUEST['action']) && !empty($_GET['action']) && ($_POST['action'] != $_GET['action']))
{
	$_REQUEST['action'] = $_GET['action'];
	$_POST['action'] = $_GET['action'];
}
$action_array = array('add', 'delete', 'edit', 'list', 'save');
$action = request_var('action', '');
$action = (isset($_POST['add']) ? 'add' : $action);
$action = (isset($_POST['save']) ? 'save' : $action);
$action = (isset($_POST['add_cat']) ? 'add' : $action);
$action = (in_array($action, $action_array) ? $action : false);

$cms_ajax = request_var('cms_ajax', '');
$cms_ajax = (empty($cms_ajax) && (($_COOKIE['cms_ajax'] == 'true') || ($_COOKIE['cms_ajax'] == 'false')) ? $_COOKIE['cms_ajax'] : $cms_ajax);
$cms_ajax = (($cms_ajax == 'false') ? false : (($cms_ajax == 'true') ? true : ($config['cms_style'] ? true : false)));
if (($cms_ajax && ($_COOKIE['cms_ajax'] != 'true')) || (!$cms_ajax && ($_COOKIE['cms_ajax'] != 'false')))
{
	@setcookie('cms_ajax', ($cms_ajax ? 'true' : 'false'), time() + 31536000);
}
$config['cms_style'] = $cms_ajax ? 1 : 0;
$cms_ajax_append = '&amp;cms_ajax=' . !empty($cms_ajax) ? 'true' : 'false';
$cms_ajax_redirect_append = '&cms_ajax=' . !empty($cms_ajax) ? 'true' : 'false';
$template->assign_vars(array(
	'U_CMS_AJAX_SWITCH' => append_sid(CMS_PAGE_CMS . '?cms_ajax=' . (!empty($cms_ajax) ? 'false' : 'true')),
	'L_CMS_AJAX_SWITCH' => !empty($cms_ajax) ? $lang['CMS_AJAX_DISABLE'] : $lang['CMS_AJAX_ENABLE'],
	)
);

$mi_id = (isset($_GET['mi_id']) ? intval($_GET['mi_id']) : (isset($_POST['mi_id']) ? intval($_POST['mi_id']) : false));
$m_id = (isset($_GET['m_id']) ? intval($_GET['m_id']) : (isset($_POST['m_id']) ? intval($_POST['m_id']) : false));

if(isset($_POST['cancel']) || isset($_POST['reset']))
{
	$s_append_url = ($m_id != false) ? '&mode=menu_block&m_id=' . $m_id : '';
	$s_append_url = '?action=nothing' . $s_append_url;
	redirect(append_sid('cms_menu.' . PHP_EXT . $s_append_url, true));
}

$show_cms_menu = (($userdata['user_level'] == ADMIN) || ($userdata['user_cms_level'] == CMS_CONTENT_MANAGER)) ? true : false;
$template->assign_vars(array(
	'S_CMS_AUTH' => true,
	'S_SHOW_CMS_MENU' => $show_cms_menu
	)
);

if($config['cms_dock'])
{
	$template->assign_block_vars('cms_dock_on', array());
}
else
{
	$template->assign_block_vars('cms_dock_off', array());
}

/* TABS - BEGIN */
$cms_admin->generate_tabs('menu');
/* TABS - END */

$s_hidden_fields = '';
$s_append_url = '';
$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
$s_append_url .= '?mode=' . $mode;
$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
$s_append_url .= '&amp;action=' . $action;

if($mi_id != false)
{
	if($action != 'add')
	{
		$s_hidden_fields .= '<input type="hidden" name="mi_id" value="' . $mi_id . '" />';
		$s_append_url .= '&amp;mi_id=' . $mi_id;
	}
}
else
{
	$mi_id = false;
}

if($m_id != false)
{
	$s_hidden_fields .= '<input type="hidden" name="m_id" value="' . $m_id . '" />';
	$s_append_url .= '&amp;m_id=' . $m_id;
}
else
{
	$m_id = false;
}

if($item_type != false)
{
	$s_hidden_fields .= '<input type="hidden" name="item_type" value="' . $item_type . '" />';
	$s_append_url .= '&amp;item_type=' . $item_type;
}
else
{
	$item_type = false;
}

$mi_menu_icon_sel_name = 'icon_img_sel';
$mi_menu_icon_input_name = 'menu_icon';

//echo($s_hidden_fields);
//echo($s_append_url);

if($mode == 'menu_item')
{
	if(($action == 'add') || ($action == 'edit'))
	{
		//$mi_menu_item_id = '';
		$mi_menu_id = '';
		$mi_menu_parent_id = '';
		$mi_cat_id = '';
		$mi_cat_parent_id = '';
		$mi_menu_status = '';
		$mi_menu_order = '';
		$mi_menu_icon = '';
		$mi_menu_name_lang = '';
		$mi_menu_name = '';
		$mi_menu_desc = '';
		$mi_menu_default = '';
		$mi_menu_disabled = '';
		$mi_menu_link = '';
		$mi_menu_link_external = '';
		$mi_auth_view = '';
		$mi_auth_view_group = '';

		if($item_type != 'category_item')
		{
			$template->assign_block_vars('is_menu_item', array());
			$is_default_link = '&nbsp;*';
		}

		if($action == 'edit')
		{
			if($mi_id != false)
			{
				$sql = "SELECT *
					FROM " . CMS_NAV_MENU_TABLE . "
					WHERE menu_item_id = '" . $mi_id . "'";
				$result = $db->sql_query($sql);
				$m_info = $db->sql_fetchrow($result);
				if(empty($m_info['menu_item_id']) || ($m_info['menu_item_id'] <= 0))
				{
					message_die(GENERAL_ERROR, $lang['CMS_Menu_Item_Not_Exist']);
				}
				$mi_menu_item_id = $m_info['menu_item_id'];
				$mi_menu_id = $m_info['menu_id'];
				$mi_menu_parent_id = $m_info['menu_parent_id'];
				$mi_cat_id = $m_info['cat_id'];
				$mi_cat_parent_id = $m_info['cat_parent_id'];
				$s_hidden_fields .= '<input type="hidden" name="old_cat_parent_id" value="' . $mi_cat_parent_id . '" />';

				if($item_type != 'category_item')
				{
					$sql = "SELECT *
						FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_parent_id = '" . $mi_menu_parent_id . "'
							AND cat_parent_id = '0'
						ORDER BY menu_order ASC";
					$result = $db->sql_query($sql);
					$mi_cat_parent_id = '';
					while ($row = $db->sql_fetchrow($result))
					{
						$row['menu_name'] = !empty($lang['cat_item_' . $row['menu_name_lang']]) ? $lang['cat_item_' . $row['menu_name_lang']] : $row['menu_name'];
						$mi_cat_parent_id .= '<option value="' . $row['cat_id'] . '"';
						if($m_info['cat_parent_id'] == $row['cat_id'])
						{
							$mi_cat_parent_id .= ' selected="selected"';
						}
						$mi_cat_parent_id .= '>' . $row['menu_name'] . '</option>';
					}
					$template->assign_block_vars('parent_cat_sel', array(
						'PARENT_CAT_SEL' => $mi_cat_parent_id,
						)
					);
				}
				else
				{
					$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $mi_cat_id . '" />';
					$s_append_url .= '&amp;cat_id=' . $mi_cat_id;
				}

				$mi_menu_status = $m_info['menu_status'];
				$mi_menu_status_yes = ($mi_menu_status == '1') ? 'checked="checked"' : '';
				$mi_menu_status_no = ($mi_menu_status == '0') ? 'checked="checked"' : '';
				$mi_menu_order = $m_info['menu_order'];
				//$mi_menu_icon = $m_info['menu_icon'];
				$mi_menu_icon = build_icons_list($mi_menu_icon_sel_name, $mi_menu_icon_input_name, $m_info['menu_icon'], (IP_ROOT_PATH . 'images/menu/'), (IP_ROOT_PATH . $images['menu_sep']));

				//$mi_menu_name_lang = $m_info['menu_name_lang'];
				$mi_menu_name_lang = '<option value="">-- ' . $lang['CMS_Menu_No_lang_key'] . ' --</option>';
				foreach($lang['menu_item'] as $lk => $mi_menu_name_lang_key)
				{
					$mi_menu_name_lang .= '<option value="' . $lk .'"';
					if($lk == $m_info['menu_name_lang'])
					{
						$mi_menu_name_lang .= ' selected="selected"';
					}
					$mi_menu_name_lang .= '>' . $mi_menu_name_lang_key . '</option>';
				}

				$mi_menu_name = $m_info['menu_name'];
				$mi_menu_desc = $m_info['menu_desc'];
				$mi_menu_link = $m_info['menu_link'];
				$mi_menu_link_external = $m_info['menu_link_external'];
				$mi_menu_link_external_yes = ($mi_menu_link_external == '1') ? 'checked="checked"' : '';
				$mi_menu_link_external_no = ($mi_menu_link_external == '0') ? 'checked="checked"' : '';

				if($item_type != 'category_item')
				{
					$link_default_array = build_default_link_array();
					$mi_menu_default ='';
					$mi_menu_disabled = ($m_info['menu_default'] != 0) ? 'disabled' : '';
					for ($i = 0; $i < sizeof($link_default_array); $i++)
					{
						$mi_menu_default .= '<option value="' . $i .'"';
						if($m_info['menu_default'] == $i)
						{
							$mi_menu_default .= ' selected="selected"';
						}
						$mi_menu_default .= '>' . $link_default_array[$i] . '</option>';
					}
				}

				//$mi_auth_view = $m_info['auth_view'];
				$view_array = array(
					'0' => $lang['B_All'],
					'1' => $lang['B_Guests'],
					'2' => $lang['B_Reg'],
					'3' => $lang['B_Mod'],
					'4' => $lang['B_Admin']
				);

				$mi_auth_view ='';
				for ($i = 0; $i < sizeof($view_array); $i++)
				{
					$mi_auth_view .= '<option value="' . $i .'"';
					if($m_info['auth_view'] == $i)
					{
						$mi_auth_view .= ' selected="selected"';
					}
					$mi_auth_view .= '>' . $view_array[$i] . '</option>';
				}

				//$mi_auth_view_group = $m_info['auth_view_group'];
				$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
				$result = $db->sql_query($sql);
				$group_array = explode(",", $m_info['auth_view_group']);
				$mi_auth_view_group = '';
				while ($row = $db->sql_fetchrow($result))
				{
					$checked = (in_array($row['group_id'], $group_array)) ? 'checked="checked"' : '';
					$mi_auth_view_group .= '<input type="checkbox" name="group' . strval($row['group_id']) . '" ' . $checked . ' />' . $row['group_name'] . '&nbsp;<br />';
				}
				if(empty($mi_auth_view_group))
				{
					$mi_auth_view_group = '&nbsp;&nbsp;' . $lang['None'];
				}
			}
			else
			{
				message_die(GENERAL_ERROR, $lang['CMS_Menu_Item_Not_Exist']);
			}
		}
		else
		{
			$mi_menu_item_id = '';
			$mi_menu_id = '0';
			$mi_menu_parent_id = $m_id;
			$mi_cat_id = '0';
			$mi_menu_icon = build_icons_list($mi_menu_icon_sel_name, $mi_menu_icon_input_name, '', (IP_ROOT_PATH . 'images/menu/'), (IP_ROOT_PATH . $images['menu_sep']));

			if($item_type != 'category_item')
			{
				$sql = "SELECT *
					FROM " . CMS_NAV_MENU_TABLE . "
					WHERE menu_parent_id = '" . $mi_menu_parent_id . "'
						AND cat_parent_id = '0'
					ORDER BY menu_order ASC";
				$result = $db->sql_query($sql);

				//$mi_cat_parent_id = $m_info['cat_parent_id'];
				$parent_cat_item_parsed = false;
				$mi_cat_parent_id = '';
				while ($row = $db->sql_fetchrow($result))
				{
					$parent_cat_item_parsed = true;
					$row['menu_name'] = !empty($lang['cat_item_' . $row['menu_name_lang']]) ? $lang['cat_item_' . $row['menu_name_lang']] : $row['menu_name'];
					$mi_cat_parent_id .= '<option value="' . $row['cat_id'] . '"';
					$mi_cat_parent_id .= '>' . $row['menu_name'] . '</option>';
				}
				if ($parent_cat_item_parsed == false)
				{
					message_die(GENERAL_ERROR, $lang['CMS_Menu_No_Cats_Exist']);
				}
				$template->assign_block_vars('parent_cat_sel', array(
					'PARENT_CAT_SEL' => $mi_cat_parent_id,
					)
				);
			}

			//$mi_menu_name_lang = $m_info['menu_name_lang'];
			$mi_menu_name_lang = '<option value="">-- ' . $lang['CMS_Menu_No_lang_key'] . ' --</option>';
			foreach($lang['menu_item'] as $lk => $mi_menu_name_lang_key)
			{
				$mi_menu_name_lang .= '<option value="' . $lk .'"';
				$mi_menu_name_lang .= '>' . $mi_menu_name_lang_key . '</option>';
			}

			$mi_menu_status = '1';
			$mi_menu_status_yes = ($mi_menu_status == '1') ? 'checked="checked"' : '';
			$mi_menu_status_no = ($mi_menu_status == '0') ? 'checked="checked"' : '';
			$mi_menu_link_external = '0';
			$mi_menu_link_external_yes = ($mi_menu_link_external == '1') ? 'checked="checked"' : '';
			$mi_menu_link_external_no = ($mi_menu_link_external == '0') ? 'checked="checked"' : '';

			if($item_type != 'category_item')
			{
				$link_default_array = build_default_link_array();
				$mi_menu_default ='';
				$mi_menu_disabled = ($m_info['menu_default'] != 0) ? 'disabled' : '';
				for ($i = 0; $i < sizeof($link_default_array); $i++)
				{
					$mi_menu_default .= '<option value="' . $i .'"';
					if($m_info['menu_default'] == $i)
					{
						$mi_menu_default .= ' selected="selected"';
					}
					$mi_menu_default .= '>' . $link_default_array[$i] . '</option>';
				}
			}

			//$mi_auth_view = $m_info['auth_view'];
			$view_array = array(
				'0' => $lang['B_All'],
				'1' => $lang['B_Guests'],
				'2' => $lang['B_Reg'],
				'3' => $lang['B_Mod'],
				'4' => $lang['B_Admin']
			);

			$mi_auth_view ='';
			for ($i = 0; $i < sizeof($view_array); $i++)
			{
				$mi_auth_view .= '<option value="' . $i .'"';
				$mi_auth_view .= ' />' . $view_array[$i] . '</option>';
			}

			//$mi_auth_view_group = $m_info['auth_view_group'];
			$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
			$result = $db->sql_query($sql);
			$mi_auth_view_group = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$mi_auth_view_group .= '<input type="checkbox" name="group' . strval($row['group_id']) . '" />' . $row['group_name'] . '&nbsp;<br />';
			}
			if(empty($mi_auth_view_group))
			{
				$mi_auth_view_group = '&nbsp;&nbsp;' . $lang['None'];
			}
		}

		$link_name_key = $lang['CMS_Menu_New_link_name_key'];
		$link_cat = $lang['CMS_Menu_Choose_cat'];
		$link_status = $lang['CMS_Menu_link_status'];
		$link_icon = $lang['CMS_Menu_Icon'];
		$link_external = $lang['CMS_Menu_link_external'];
		$link_permission = $lang['CMS_Menu_Set_auth'];
		if($item_type == 'category_item')
		{
			$link_name = $lang['CMS_Menu_New_cat_name'];
			$link_desc = $lang['CMS_Menu_New_cat_des'];
			$link_url = $lang['CMS_Menu_New_cat_link_url'];
		}
		else
		{
			$link_default = $lang['CMS_Menu_Default_link'];
			$link_name = $lang['CMS_Menu_New_link_name'];
			$link_desc = $lang['CMS_Menu_New_link_des'];
			$link_url = $lang['CMS_Menu_New_link_url'];
		}

		$template_to_parse = CMS_TPL . 'cms_menu_item_edit_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_MENU_PAGE']);
		$template->assign_vars(array(
			'L_CMS_MENU_TITLE' => $lang['CMS_MENU_PAGE'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_MENU_PAGE_EXPLAIN'],
			'L_EDIT_MENU_ITEM' => $lang['CMS_Menu_Item_Add_Edit'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_ENABLED' => $lang['Enabled'],
			'L_DISABLED' => $lang['Disabled'],

			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],
			'L_MENU_UPDATE' => $lang['CMS_Menu_Update'],
			'S_MENU_ACTION' => append_sid('cms_menu.' . PHP_EXT . $s_append_url),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,

			'L_LINK_NAME' => $link_name . $is_default_link,
			'L_LINK_NAME_KEY' => $link_name_key . $is_default_link,
			'L_LINK_DEFAULT' => $link_default,
			'L_LINK_DESC' => $link_desc,
			'L_LINK_CAT' => $link_cat,
			'L_LINK_STATUS' => $link_status,
			'L_LINK_ICON' => $link_icon,
			'L_LINK_URL' => $link_url . $is_default_link,
			'L_LINK_EXTERNAL' => $link_external . $is_default_link,
			'L_LINK_PERMISSION' => $link_permission . $is_default_link,

			'MI_ICON_SEL_NAME' => $mi_menu_icon_sel_name,
			'MI_ICON_INPUT_NAME' => $mi_menu_icon_input_name,

			'MI_MENU_ITEM_ID' => $mi_menu_item_id,
			'MI_MENU_ID' => $mi_menu_id,
			'MI_MENU_PARENT_ID' => $mi_menu_parent_id,
			'MI_CAT_ID' => $mi_cat_id,
			'MI_CAT_PARENT_ID' => $mi_cat_parent_id,
			'MI_MENU_STATUS' => $mi_menu_status,
			'MI_MENU_ORDER' => $mi_menu_order,
			'MI_MENU_ICON' => $mi_menu_icon,
			'MI_MENU_NAME_LANG' => $mi_menu_name_lang,
			'MI_MENU_NAME' => $mi_menu_name,
			'MI_MENU_DESC' => $mi_menu_desc,
			'MI_MENU_DEFAULT' => $mi_menu_default,
			'MI_MENU_DISABLED' => $mi_menu_disabled,
			'MI_MENU_LINK' => $mi_menu_link,
			'MI_MENU_LINK_EXTERNAL' => $mi_menu_link_external,
			'MI_AUTH_VIEW' => $mi_auth_view,
			//'MI_AUTH_VIEW_GROUP' => $mi_auth_view_group,
			'MI_MENU_STATUS_YES' => $mi_menu_status_yes,
			'MI_MENU_STATUS_NO' => $mi_menu_status_no,
			'MI_MENU_LINK_EXTERNAL_YES' => $mi_menu_link_external_yes,
			'MI_MENU_LINK_EXTERNAL_NO' => $mi_menu_link_external_no,
			)
		);
	}
	elseif($action == 'save')
	{
		$mi_menu_id = $m_id;
		$mi_menu_sql_id = 0;
		//$mi_menu_parent_id = (isset($_POST['menu_parent_id'])) ? intval(trim($_POST['menu_parent_id'])) : '';
		$mi_menu_parent_id = $m_id;
		$mi_cat_id = request_post_var('cat_id', 0);
		$mi_cat_parent_id = request_post_var('cat_parent_id', 0);
		$mi_menu_status = request_post_var('menu_status', 0);
		$mi_menu_order = request_post_var('menu_order', 0);
		$mi_menu_icon = request_post_var('menu_icon', '', true);
		$mi_menu_desc = request_post_var('menu_desc', '', true);
		$mi_menu_default = request_post_var('menu_default', 0);
		if ($mi_menu_default > '0')
		{
			$mi_menu_name = build_default_link_name($mi_menu_default);
			$mi_menu_name_lang = '';
			$mi_menu_link = build_default_link_url($mi_menu_default);
			$mi_menu_link_external = '0';
			$mi_auth_view = build_default_link_auth($mi_menu_default);
		}
		else
		{
			$mi_menu_name = request_post_var('menu_name', '', true);
			$mi_menu_name_lang = request_post_var('menu_name_lang', '', true);
			$mi_menu_link = request_post_var('menu_link', '', true);
			$mi_menu_link_external = request_post_var('menu_link_external', 0);
			$mi_auth_view = request_post_var('auth_view', 0);
		}
		$mi_auth_view_group = request_post_var('auth_view_group', '0');

		if($mi_id)
		{
			$mi_old_cat_parent_id = request_post_var('old_cat_parent_id', 0);
			$sql_order = '';
			if (isset($_POST['old_cat_parent_id']) && ($item_type != 'category_item'))
			{
				if ($mi_old_cat_parent_id != $mi_cat_parent_id)
				{
					$sql = "SELECT max(menu_order) max_menu_order FROM " . CMS_NAV_MENU_TABLE . " WHERE menu_parent_id ='" . $mi_menu_id . "' AND cat_parent_id ='" . $mi_cat_parent_id . "'";
					$result = $db->sql_query($sql);
					$row = $db->sql_fetchrow($result);
					$mi_menu_order = $row['max_menu_order'] + 1;
					$sql_order = ' menu_order = \'' . $mi_menu_order . '\',';
				}
			}

			$sql = "UPDATE " . CMS_NAV_MENU_TABLE . "
				SET
				menu_id = '" . $mi_menu_sql_id . "',
				menu_parent_id = '" . $mi_menu_parent_id . "',
				cat_id = '" . $mi_cat_id . "',
				cat_parent_id = '" . $mi_cat_parent_id . "',
				" . $sql_order . "
				menu_status = '" . $mi_menu_status . "',
				menu_icon = '" . $db->sql_escape($mi_menu_icon) . "',
				menu_name_lang = '" . $db->sql_escape($mi_menu_name_lang) . "',
				menu_name = '" . $db->sql_escape($mi_menu_name) . "',
				menu_desc = '" . $db->sql_escape($mi_menu_desc) . "',
				menu_link = '" . $db->sql_escape($mi_menu_link) . "',
				menu_link_external = '" . $mi_menu_link_external . "',
				auth_view = '" . $mi_auth_view . "',
				auth_view_group = '" . $db->sql_escape($mi_auth_view_group) . "',
				menu_default = '" . $db->sql_escape($mi_menu_default) . "'
				WHERE menu_item_id = '" . $mi_id . "'";
			$result = $db->sql_query($sql);

			if($item_type == 'category_item')
			{
				$message = $lang['Cat_updated'];
			}
			else
			{
				$message = $lang['Link_updated'];
			}
		}
		else
		{
			if($item_type == 'category_item')
			{
				$sql = "SELECT MAX(cat_id) max_cat_id, MAX(menu_order) max_menu_order FROM " . CMS_NAV_MENU_TABLE . " WHERE menu_parent_id ='" . $mi_menu_id . "' AND cat_parent_id ='0'";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$mi_cat_id = $row['max_cat_id'] ? ($row['max_cat_id'] + 1) : 1;
				$mi_menu_order = $row['max_menu_order'] ? ($row['max_menu_order'] + 1) : 1;
			}
			else
			{
				$sql = "SELECT max(menu_order) max_menu_order FROM " . CMS_NAV_MENU_TABLE . " WHERE menu_parent_id ='" . $mi_menu_id . "' AND cat_parent_id ='" . $mi_cat_parent_id . "'";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$mi_menu_order = $row['max_menu_order'] + 1;
			}

			$sql = "INSERT INTO " . CMS_NAV_MENU_TABLE . " (menu_id, menu_parent_id, cat_id, cat_parent_id, menu_status, menu_order, menu_icon, menu_name_lang, menu_name, menu_desc, menu_link, menu_link_external, auth_view, auth_view_group, menu_default) VALUES ('" . $mi_menu_sql_id . "', '" . $mi_menu_parent_id . "', '" . $mi_cat_id . "', '" . $mi_cat_parent_id . "', '" . $mi_menu_status . "', '" . $mi_menu_order . "', '" . $db->sql_escape($mi_menu_icon) . "', '" . $db->sql_escape($mi_menu_name_lang) . "', '" . $db->sql_escape($mi_menu_name) . "', '" . $db->sql_escape($mi_menu_desc) . "', '" . $db->sql_escape($mi_menu_link) . "', '" . $mi_menu_link_external . "', '" . $mi_auth_view . "', '" . $db->sql_escape($mi_auth_view_group) . "', '" . $db->sql_escape($mi_menu_default) . "')";
			$result = $db->sql_query($sql);

			if($item_type == 'category_item')
			{
				$message = $lang['Cat_created'];
			}
			else
			{
				$message = $lang['Link_created'];
			}
		}
		if($item_type != 'category_item')
		{
			adjust_item_order($mi_menu_parent_id, $mi_cat_parent_id);
		}
		$message .= '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block&amp;m_id=' . $mi_menu_id) . '">', '</a>') . '<br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		$cat_id = request_get_var('cat_id', 0);
		$cat_id = ($cat_id < 1) ? 0 : $cat_id;

		if(!isset($_POST['confirm']))
		{
			$s_hidden_fields = '';
			$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
			$s_hidden_fields .= '<input type="hidden" name="m_id" value="' . $m_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="item_type" value="' . $item_type . '" />';
			$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="mi_id" value="' . $mi_id . '" />';
			$s_append_url = '';
			$s_append_url .= '?mode=' . $mode;
			$s_append_url .= '&amp;action=' . $action;
			$s_append_url .= '&amp;m_id=' . $m_id;
			$s_append_url .= '&amp;item_type=' . $item_type;
			$s_append_url .= '&amp;cat_id=' . $cat_id;
			$s_append_url .= '&amp;mi_id=' . $mi_id;
			// Set template files

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_ENABLED' => $lang['Enabled'],
				'L_DISABLED' => $lang['Disabled'],

				'S_CONFIRM_ACTION' => append_sid('cms_menu.' . PHP_EXT . '?' . $s_append_url),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			if(($mi_id != 0) && ($m_id != 0))
			{
				if($item_type == 'category_item')
				{
					if($cat_id > 0)
					{
						$message = $lang['Cat_deleted'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block&amp;m_id=' . $m_id) . '">', '</a>') . '<br /><br />';
						$sql = "DELETE FROM " . CMS_NAV_MENU_TABLE . "
							WHERE menu_item_id = '" . $mi_id . "'
								OR (menu_parent_id = '" . $m_id . "' AND cat_parent_id = '" . $cat_id . "')";
					}
					else
					{
						message_die(GENERAL_ERROR, "The category specified doesn\'t exist!", $lang['Error'], __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					$message = $lang['Link_deleted'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block&amp;m_id=' . $m_id) . '">', '</a>') . '<br /><br />';
					$sql = "DELETE FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_item_id = '" . $mi_id . "'";
				}
				$result = $db->sql_query($sql);
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['CMS_Menu_Not_Exist']);
			}
		}
	}
}
elseif($mode == 'menu_block')
{
	if($m_id)
	{
		if(isset($_POST['action_update']))
		{
			$menu_upd = array();
			$menu_upd = $_POST['cb_mid'];
			$menu_upd_n = sizeof($menu_upd);

			$menu_item_id_list = build_menu_item_id_list($m_id);
			$m_count = sizeof($menu_item_id_list);

			for($i = 0; $i < $m_count; $i++)
			{
				$m_active = empty($menu_upd) ? 0 : (in_array($menu_item_id_list[$i], $menu_upd) ? 1 : 0);
				$sql = "UPDATE " . CMS_NAV_MENU_TABLE . "
								SET menu_status = '" . $m_active . "'
								WHERE menu_item_id = '" . $menu_item_id_list[$i] . "'";
				$result = $db->sql_query($sql);
			}
			$message = '<br /><br />' . $lang['Menu_updated'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block&amp;m_id=' . $m_id) . '">', '</a>') . '<br />';
			message_die(GENERAL_MESSAGE, $message);
		}

		$move = (isset($_GET['move'])) ? $_GET['move'] : -1;
		if (($move == '0') || ($move == '1'))
		{
			if($item_type == 'category_item')
			{
				change_cat_order($mi_id, $m_id, $move);
			}
			else
			{
				$cat_parent_id = request_get_var('cat_parent_id', 0);
				if ($cat_parent_id != 0)
				{
					change_item_order($mi_id, $cat_parent_id, $m_id, $move);
				}
			}
		}

		$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_parent_id = '" . $m_id . "'
						ORDER BY cat_parent_id ASC, menu_order ASC";
		$result = $db->sql_query($sql);

		$template_to_parse = CMS_TPL . 'cms_menu_block_list_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_MENU_PAGE']);

		$menu_cat = array();
		$cat_item = array();
		$menu_item = array();
		$cat_item_parsed = false;
		$menu_item_parsed = false;
		while ($menu_item = $db->sql_fetchrow($result))
		{
			if ($menu_item['cat_id'] > 0)
			{
				$cat_item_parsed = true;
				$cat_item[$menu_item['cat_id']] = $menu_item;
			}
			if ($menu_item['cat_parent_id'] > 0)
			{
				$menu_item_parsed = true;
				$menu_cat[$menu_item['cat_parent_id']][$menu_item['menu_item_id']] = $menu_item;
			}
		}

		if ($cat_item_parsed == false)
		{
			$template->assign_block_vars('no_items', array(
				'NO_ITEMS' => $lang['CMS_Menu_Items_Not_Exist'],
				)
			);
		}
		else
		{
			$cat_counter = 0;
			foreach($cat_item as $cat_item_data)
			{
				$cat_counter++;
				//echo($cat_item_data['menu_name'] . '<br />');
				$cat_id = ($cat_item_data['cat_id']);
				if (($cat_item_data['menu_name_lang'] != '') && isset($lang[$cat_item_data['menu_name_lang']]))
				{
					$cat_name = $lang[$cat_item_data['menu_name_lang']];
				}
				else
				{
					$cat_name = (($cat_item_data['menu_name'] != '') ? $cat_item_data['menu_name'] : 'cat_item' . $cat_item_data['cat_id']) ;
				}
				$cat_desc = (($cat_item_data['menu_desc'] != '') ? $cat_item_data['menu_desc'] : '') ;
				//$cat_icon = (($cat_item_data['menu_icon'] != '') ? '<img src="' . $cat_item_data['menu_icon'] . '" alt="' . $cat_name . '" title="' . $cat_name . '" style="vertical-align: middle;" />&nbsp;' : '');
				// No icon = Standard icon!
				$cat_icon = (($cat_item_data['menu_icon'] != '') ? '<img src="' . $cat_item_data['menu_icon'] . '" alt="" title="' . $cat_desc . '" style="vertical-align: middle;" />&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align: middle;" />&nbsp;');

				$append_url = '&amp;mi_id=' . $cat_item_data['menu_item_id'] . '&amp;m_id=' . $m_id . '&amp;item_type=category_item';

				$b_move_up = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block' . $append_url . '&amp;move=0') . '"><img src="' . $images['arrows_cms_up'] . '" alt="' . $lang['B_Move_Up'] . '" title="' . $lang['B_Move_Up'] . '" /></a>&nbsp;';
				$b_move_down = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block' . $append_url . '&amp;move=1') . '"><img src="' . $images['arrows_cms_down'] . '" alt="' . $lang['B_Move_Down'] . '" title="' . $lang['B_Move_Down'] . '" /></a>&nbsp;';
				$b_edit = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_item&amp;action=edit' . $append_url) . '"><img src="' . $images['block_edit'] . '" alt="' . $lang['CMS_EDIT'] . '" title="' . $lang['CMS_EDIT'] . '" /></a>&nbsp;';
				$b_delete = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_item&amp;action=delete&amp;cat_id=' . $cat_item_data['cat_id'] . $append_url) . '"><img src="' . $images['block_delete'] . '" alt="' . $lang['CSM_DELETE'] . '" title="' . $lang['CSM_DELETE'] . '" /></a>';

				if ((sizeof($cat_item) == 1) && ($cat_counter == 1))
				{
					$b_move_up = '';
					$b_move_down = '';
				}
				elseif ((sizeof($cat_item) > 1) && ($cat_counter == 1))
				{
					$b_move_up = '';
				}
				elseif (sizeof($cat_item) == $cat_counter)
				{
					$b_move_down = '';
				}

				$template->assign_block_vars('cat_row', array(
					'CAT_ID' => $cat_item_data['cat_id'],
					'CAT_ITEM' => $cat_name,
					'CAT_ICON' => $cat_icon,
					'CAT_DESC' => $cat_desc,
					'CAT_CB_ID' => $cat_item_data['menu_item_id'],
					'CAT_CHECKED' => ($cat_item_data['menu_status']) ? ' checked="checked"' : '',
					'U_EDIT' => $b_edit,
					'U_DELETE' => $b_delete,
					'U_MOVE_UP' => $b_move_up,
					'U_MOVE_DOWN' => $b_move_down,
					)
				);

				if ($menu_cat[$cat_id])
				{
					$item_counter = 0;
					foreach($menu_cat[$cat_id] as $menu_cat_item_data)
					{
						$item_counter++;
						// No icon = Standard icon!
						$menu_icon = (($menu_cat_item_data['menu_icon'] != '') ? '<img src="' . $menu_cat_item_data['menu_icon'] . '" alt="" title="' . $menu_name . '" style="vertical-align: middle;" />&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align: middle;" />&nbsp;');
						$menu_desc = (($menu_cat_item_data['menu_desc'] != '') ? $menu_cat_item_data['menu_desc'] : '');
						if ($menu_cat_item_data['menu_default'] == 0)
						{
							if (($menu_cat_item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$menu_cat_item_data['menu_name_lang']]))
							{
								$menu_name = $lang['menu_item'][$menu_cat_item_data['menu_name_lang']];
							}
							else
							{
								$menu_name = (($menu_cat_item_data['menu_name'] != '') ? $menu_cat_item_data['menu_name'] : 'cat_item' . $menu_cat_item_data['cat_id']);
							}
							if ($menu_cat_item_data['menu_link_external'] == true)
							{
								$menu_link = $menu_cat_item_data['menu_link'];
								$menu_link .= '" target="_blank';
							}
							else
							{
								$menu_link = append_sid($menu_cat_item_data['menu_link']);
							}
							$menu_url = '<a href="' . $menu_link . '">' . $menu_icon . $menu_name . '</a>';
						}
						else
						{
							$menu_url = build_complete_url($menu_cat_item_data['menu_default'], '', $menu_cat_item_data['menu_link'], $menu_icon);
						}

						$append_url = '&amp;mi_id=' . $menu_cat_item_data['menu_item_id'] . '&amp;m_id=' . $m_id . '&amp;cat_parent_id=' . $menu_cat_item_data['cat_parent_id'];

						$b_move_up = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block' . $append_url . '&amp;move=0') . '"><img src="' . $images['arrows_cms_up'] . '" alt="' . $lang['B_Move_Up'] . '" title="' . $lang['B_Move_Up'] . '" /></a>&nbsp;';
						$b_move_down = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block' . $append_url . '&amp;move=1') . '"><img src="' . $images['arrows_cms_down'] . '" alt="' . $lang['B_Move_Down'] . '" title="' . $lang['B_Move_Down'] . '" /></a>&nbsp;';
						$b_edit = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_item&amp;action=edit' . $append_url) . '"><img src="' . $images['block_edit'] . '" alt="' . $lang['CMS_EDIT'] . '" title="' . $lang['CMS_EDIT'] . '" /></a>&nbsp;';
						$b_delete = '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_item&amp;action=delete' . $append_url) . '"><img src="' . $images['block_delete'] . '" alt="' . $lang['CSM_DELETE'] . '" title="' . $lang['CSM_DELETE'] . '" /></a>';

						if ((sizeof($menu_cat[$cat_id]) == 1) && ($item_counter == 1))
						{
							$b_move_up = '';
							$b_move_down = '';
						}
						elseif ((sizeof($menu_cat[$cat_id]) > 1) && ($item_counter == 1))
						{
							$b_move_up = '';
						}
						elseif (sizeof($menu_cat[$cat_id]) == $item_counter)
						{
							$b_move_down = '';
						}

						$template->assign_block_vars('cat_row.menu_row', array(
							'MENU_ITEM' => $menu_name,
							'MENU_LINK' => $menu_link,
							'MENU_ICON' => $menu_icon,
							'MENU_DESC' => $menu_desc,
							'MENU_URL' => $menu_url,
							'MENU_CB_ID' => $menu_cat_item_data['menu_item_id'],
							'MENU_CHECKED' => ($menu_cat_item_data['menu_status']) ? ' checked="checked"' : '',
							'U_EDIT' => $b_edit,
							'U_DELETE' => $b_delete,
							'U_MOVE_UP' => $b_move_up,
							'U_MOVE_DOWN' => $b_move_down,
							)
						);
					}
				}
			}
		}

		$template->assign_vars(array(
			'L_CMS_MENU_TITLE' => $lang['CMS_MENU_PAGE'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_MENU_PAGE_EXPLAIN'],
			'L_CMS_ACTIONS' => $lang['CMS_Actions'],
			'L_CMS_NAME' => $lang['CMS_Name'],
			'L_CMS_DESCRIPTION' => $lang['CMS_Description'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],
			'L_CAT_ADD' => $lang['CMS_Menu_New_cat'],
			'L_MENU_ADD' => $lang['CMS_Menu_New_link'],
			'L_MENU_UPDATE' => $lang['CMS_Menu_Update'],
			'L_MENU_UPDATED' => $lang['Menu_updated'],
			'S_CAT_ADD_ACTION' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_item&amp;action=add&amp;m_id=' . $m_id . '&amp;item_type=category_item'),
			'S_MENU_ACTION' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_item&amp;action=add&amp;m_id=' . $m_id),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['CMS_Menu_Not_Exist']);
	}
}
elseif (($mode == 'menu_list') || ($mode == false))
{
	if(($action == 'edit') || ($action == 'add'))
	{
		$template_to_parse = CMS_TPL . 'cms_menu_menu_edit_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_MENU_PAGE']);

		$mi_menu_name = '';
		$mi_menu_name_lang = '';
		$mi_menu_desc = '';
		if($action == 'edit')
		{
			if ($mi_id)
			{
				$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
								WHERE menu_item_id = '" . $mi_id . "'";
				$result = $db->sql_query($sql);
				$m_info = $db->sql_fetchrow($result);
				$mi_menu_name = $m_info['menu_name'];
				$mi_menu_desc = $m_info['menu_desc'];
				$mi_menu_name_lang = '<option value="">-- ' . $lang['CMS_Menu_No_lang_key'] . ' --</option>';
				foreach($lang['menu_item'] as $lk => $mi_menu_name_lang_key)
				{
					$mi_menu_name_lang .= '<option value="' . $lk .'" ';
					if($lk == $m_info['menu_name_lang'])
					{
						$mi_menu_name_lang .= 'selected="selected"';
					}
					$mi_menu_name_lang .= '>' . $mi_menu_name_lang_key . '</option>';
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['CMS_Menu_Not_Exist']);
			}
		}
		else
		{
			$mi_menu_name_lang = '<option value="">-- ' . $lang['CMS_Menu_No_lang_key'] . ' --</option>';
			foreach($lang['menu_item'] as $lk => $mi_menu_name_lang_key)
			{
				$mi_menu_name_lang .= '<option value="' . $lk . '"';
				$mi_menu_name_lang .= '>' . $mi_menu_name_lang_key . '</option>';
			}
		}

		$template->assign_vars(array(
			'L_CMS_MENU_TITLE' => $lang['CMS_MENU_PAGE'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_MENU_PAGE_EXPLAIN'],
			'L_EDIT_MENU_ITEM' => $lang['CMS_Menu_Item_Add_Edit'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_ENABLED' => $lang['Enabled'],
			'L_DISABLED' => $lang['Disabled'],

			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],
			'S_MENU_ACTION' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_list&amp;action=' . $action),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,

			'L_MENU_NAME' => $lang['CMS_Menu_New_menu_name'],
			'L_MENU_NAME_KEY' => $lang['CMS_Menu_New_link_name_key'],
			'L_MENU_DESC' => $lang['CMS_Menu_New_menu_des'],

			'MI_MENU_NAME' => $mi_menu_name,
			'MI_MENU_NAME_LANG' => $mi_menu_name_lang,
			'MI_MENU_DESC' => $mi_menu_desc,
			)
		);
	}
	elseif($action == 'save')
	{
		$mi_menu_item_id = $mi_id;
		$mi_menu_name = request_post_var('menu_name', '', true);
		$mi_menu_name_lang = request_post_var('menu_name_lang', '', true);
		$mi_menu_desc = request_post_var('menu_desc', '', true);

		if($mi_id)
		{
			$sql = "UPDATE " . CMS_NAV_MENU_TABLE . "
				SET
				menu_name = '" . $db->sql_escape($mi_menu_name) . "',
				menu_name_lang = '" . $db->sql_escape($mi_menu_name_lang) . "',
				menu_desc = '" . $db->sql_escape($mi_menu_desc) . "'
				WHERE menu_item_id = '" . $mi_id . "'";
			$result = $db->sql_query($sql);
			$message = $lang['Menu_updated'];
		}
		else
		{
			$sql = "SELECT max(menu_id) max_menu_id FROM " . CMS_NAV_MENU_TABLE;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$mi_menu_id = $row['max_menu_id'] + 1;

			$sql = "INSERT INTO " . CMS_NAV_MENU_TABLE . " (menu_id, menu_name, menu_name_lang, menu_desc) VALUES ('" . $mi_menu_id . "', '" . $db->sql_escape($mi_menu_name) . "', '" . $db->sql_escape($mi_menu_name_lang) . "', '" . $db->sql_escape($mi_menu_desc) . "')";
			$message = $lang['Menu_created'];
			$result = $db->sql_query($sql);
		}
		$message .= '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_list') . '">', '</a>') . '<br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		if(!isset($_POST['confirm']))
		{
			$s_hidden_fields = '';
			$s_hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="mi_id" value="' . $mi_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="m_id" value="' . $m_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="action" value="' . $action . '" />';
			$s_hidden_fields .= '<input type="hidden" name="item_type" value="' . $item_type . '" />';

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_ENABLED' => $lang['Enabled'],
				'L_DISABLED' => $lang['Disabled'],

				'S_CONFIRM_ACTION' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_list'),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			if(($mi_id != 0) && ($m_id != 0))
			{
				$sql = "DELETE FROM " . CMS_NAV_MENU_TABLE . "
					WHERE menu_item_id = '" . $mi_id . "'
						OR menu_parent_id = '" . $m_id . "'";
				$result = $db->sql_query($sql);

				$message = $lang['Menu_deleted'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . PHP_EXT . '?mode=menu_list') . '">', '</a>') . '<br /><br />';
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['CMS_Menu_Not_Exist']);
			}
		}
	}
	elseif (($action == 'list') || ($action == false))
	{
		$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_parent_id = '0'
						ORDER BY menu_name ASC";
		$result = $db->sql_query($sql);

		$template_to_parse = CMS_TPL . 'cms_menu_list_body.tpl';
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_MENU_PAGE']);

		$menu_item = array();
		while ($menu_item = $db->sql_fetchrow($result))
		{
			$menu_id = ($menu_item['menu_id']);

			$append_url = '&amp;mi_id=' . $menu_item['menu_item_id'] . '&amp;m_id=' . $menu_item['menu_id'];

			$template->assign_block_vars('menu_row', array(
				'MENU_ID' => $menu_item['menu_id'],
				'MENU_NAME' => $menu_item['menu_name'],
				'MENU_DESCRIPTION' => $menu_item['menu_desc'],
				'U_ITEMS_EDIT' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_block' . $append_url),
				'U_EDIT' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_list&amp;action=edit' . $append_url),
				'U_DELETE' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_list&amp;action=delete' . $append_url),
				)
			);
		}

		$template->assign_vars(array(
			'L_CMS_MENU_TITLE' => $lang['CMS_MENU_PAGE'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_MENU_PAGE_EXPLAIN'],
			'L_CMS_ID' => $lang['CMS_ID'],
			'L_CMS_ACTIONS' => $lang['CMS_Actions'],
			'L_CMS_NAME' => $lang['CMS_Name'],
			'L_CMS_DESCRIPTION' => $lang['CMS_Description'],
			'L_CMS_EDIT_MENU_ITEMS' => $lang['CMS_Menu_Edit_menu_links_button'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],
			'L_MENU_ADD' => $lang['CMS_Menu_New_Menu'],
			'S_MENU_ACTION' => append_sid('cms_menu.' . PHP_EXT . '?mode=menu_list'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}

}

full_page_generation($template_to_parse, $lang['Home'], '', '');

?>