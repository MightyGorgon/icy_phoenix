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
define('IN_CMS', true);
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);
include_once($phpbb_root_path . 'includes/functions_cms_menu.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}

if (!$userdata['session_admin'])
{
	redirect(append_sid(LOGIN_MG . '?redirect=cms_menu.' . $phpEx . '&admin=1', true));
}

include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_cms.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_dyn_menu.' . $phpEx);

if(!empty($_GET['mode']) || !empty($_POST['mode']))
{
	$mode = isset($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = false;
}

if(isset($_POST['action_update']))
{
	$mode = 'menu_block';
}

if(!empty($_GET['action']) || !empty($_POST['action']))
{
	$action = isset($_GET['action']) ? $_GET['action'] : $_POST['action'];
	$action = htmlspecialchars($action);
}
else
{
	$action = false;
}

if(!empty($_GET['item_type']) || !empty($_POST['item_type']))
{
	$item_type = isset($_GET['item_type']) ? $_GET['item_type'] : $_POST['item_type'];
	$item_type = htmlspecialchars($item_type);
}
else
{
	$item_type = false;
}

if(isset($_POST['save']))
{
	$action = 'save';
}

if(isset($_POST['add']))
{
	$action = 'add';
}

if(isset($_POST['add_cat']))
{
	$action = 'add';
	$item_type = 'category_item';
}

if(!empty($_GET['mi_id']) || !empty($_POST['mi_id']))
{
	$mi_id = isset($_GET['mi_id']) ? intval($_GET['mi_id']) : intval($_POST['mi_id']);
}
else
{
	$mi_id = false;
}

if(!empty($_GET['m_id']) || !empty($_POST['m_id']))
{
	$m_id = isset($_GET['m_id']) ? intval($_GET['m_id']) : intval($_POST['m_id']);
}
else
{
	$m_id = false;
}

if(isset($_POST['cancel']) || isset($_POST['reset']))
{
	$s_append_url = ($m_id != false) ? '&mode=menu_block&m_id=' . $m_id : '';
	$s_append_url = '?action=nothing' . $s_append_url;
	redirect(append_sid('cms_menu.' . $phpEx . $s_append_url, true));
}

$page_title = $lang['Home'];
$meta_description = '';
$meta_keywords = '';
$template->assign_vars(array('S_CMS_AUTH' => true));
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

if($board_config['cms_dock'] == true)
{
	$template->assign_block_vars('cms_dock_on', array());
}
else
{
	$template->assign_block_vars('cms_dock_off', array());
}

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
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Could not query menu table", "Error", __LINE__, __FILE__, $sql);
				}

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
							AND cat_parent_id = '0'";
					if(!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, "Could not query menu table", "Error", __LINE__, __FILE__, $sql);
					}

					$mi_cat_parent_id = '';
					while ($row = $db->sql_fetchrow($result))
					{
						$row['menu_name'] = !empty($lang['cat_item_' . $row['menu_name_lang']]) ? $lang['cat_item_' . $row['menu_name_lang']] : stripslashes($row['menu_name']);
						$mi_cat_parent_id .= '<option value="' . $row['cat_id'] . '"';
						if($m_info['cat_parent_id'] == $row['cat_id'])
						{
							$mi_cat_parent_id .= ' selected="selected"';
						}
						$mi_cat_parent_id .= '>' . htmlspecialchars($row['menu_name']) . '</option>';
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
				$mi_menu_icon = build_icons_list($mi_menu_icon_sel_name, $mi_menu_icon_input_name, $m_info['menu_icon'], ($phpbb_root_path . 'images/menu/'), ($phpbb_root_path . $images['menu_sep']));

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

				$mi_menu_name = stripslashes($m_info['menu_name']);
				$mi_menu_desc = stripslashes($m_info['menu_desc']);
				$mi_menu_link = $m_info['menu_link'];
				$mi_menu_link_external = $m_info['menu_link_external'];
				$mi_menu_link_external_yes = ($mi_menu_link_external == '1') ? 'checked="checked"' : '';
				$mi_menu_link_external_no = ($mi_menu_link_external == '0') ? 'checked="checked"' : '';

				if($item_type != 'category_item')
				{
					$link_default_array = build_default_link_array();
					$mi_menu_default ='';
					$mi_menu_disabled = ($m_info['menu_default'] != 0) ? 'disabled' : '';
					for ($i = 0; $i < count($link_default_array); $i++)
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
				for ($i = 0; $i < count($view_array); $i++)
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
				if(!($result = $db->sql_query($sql)))
				{
					message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
				}
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
			$mi_menu_icon = build_icons_list($mi_menu_icon_sel_name, $mi_menu_icon_input_name, '', ($phpbb_root_path . 'images/menu/'), ($phpbb_root_path . $images['menu_sep']));

			if($item_type != 'category_item')
			{
				$sql = "SELECT *
					FROM " . CMS_NAV_MENU_TABLE . "
					WHERE menu_parent_id = '" . $mi_menu_parent_id . "'
						AND cat_parent_id = '0'";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Could not query menu table", "Error", __LINE__, __FILE__, $sql);
				}

				//$mi_cat_parent_id = $m_info['cat_parent_id'];
				$parent_cat_item_parsed = false;
				$mi_cat_parent_id = '';
				while ($row = $db->sql_fetchrow($result))
				{
					$parent_cat_item_parsed = true;
					$row['menu_name'] = !empty($lang['cat_item_' . $row['menu_name_lang']]) ? $lang['cat_item_' . $row['menu_name_lang']] : stripslashes($row['menu_name']);
					$mi_cat_parent_id .= '<option value="' . $row['cat_id'] . '"';
					$mi_cat_parent_id .= '>' . htmlspecialchars($row['menu_name']) . '</option>';
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
				for ($i = 0; $i < count($link_default_array); $i++)
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
			for ($i = 0; $i < count($view_array); $i++)
			{
				$mi_auth_view .= '<option value="' . $i .'"';
				$mi_auth_view .= ' />' . $view_array[$i] . '</option>';
			}

			//$mi_auth_view_group = $m_info['auth_view_group'];
			$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . " WHERE group_single_user = 0 ORDER BY group_id";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(CRITICAL_ERROR, "Could not query user groups information", "", __LINE__, __FILE__, $sql);
			}
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

		$template->set_filenames(array('body' => CMS_TPL . 'cms_menu_item_edit_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Menu_Page']);
		$template->assign_vars(array(
			'L_CMS_MENU_TITLE' => $lang['CMS_Menu_Page'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_Menu_Page_Explain'],
			'L_EDIT_MENU_ITEM' => $lang['CMS_Menu_Item_Add_Edit'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_ENABLED' => $lang['Enabled'],
			'L_DISABLED' => $lang['Disabled'],

			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],
			'L_MENU_UPDATE' => $lang['CMS_Menu_Update'],
			'S_MENU_ACTION' => append_sid('cms_menu.' . $phpEx . $s_append_url),
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
		$mi_cat_id = (isset($_POST['cat_id'])) ? intval(trim($_POST['cat_id'])) : '0';
		$mi_cat_parent_id = (isset($_POST['cat_parent_id'])) ? intval(trim($_POST['cat_parent_id'])) : '0';
		$mi_menu_status = (isset($_POST['menu_status'])) ? trim($_POST['menu_status']) : '0';
		$mi_menu_order = (isset($_POST['menu_order'])) ? intval(trim($_POST['menu_order'])) : '0';
		$mi_menu_icon = (isset($_POST['menu_icon'])) ? trim($_POST['menu_icon']) : '';
		$mi_menu_desc = (isset($_POST['menu_desc'])) ? trim($_POST['menu_desc']) : '';
		$mi_menu_default = (isset($_POST['menu_default'])) ? trim($_POST['menu_default']) : '0';
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
			$mi_menu_name = (isset($_POST['menu_name'])) ? trim($_POST['menu_name']) : '';
			$mi_menu_name_lang = (isset($_POST['menu_name_lang'])) ? trim($_POST['menu_name_lang']) : '';
			$mi_menu_link = (isset($_POST['menu_link'])) ? trim($_POST['menu_link']) : '';
			$mi_menu_link_external = (isset($_POST['menu_link_external'])) ? trim($_POST['menu_link_external']) : '0';
			$mi_auth_view = (isset($_POST['auth_view'])) ? trim($_POST['auth_view']) : '0';
		}
		$mi_auth_view_group = (isset($_POST['auth_view_group'])) ? trim($_POST['auth_view_group']) : '0';

		if($mi_id)
		{
			$sql_order = '';
			if (isset($_POST['old_cat_parent_id']) && ($item_type != 'category_item'))
			{
				if (intval(trim($_POST['old_cat_parent_id'])) != $mi_cat_parent_id)
				{
					$sql = "SELECT max(menu_order) max_menu_order FROM " . CMS_NAV_MENU_TABLE . " WHERE menu_parent_id ='" . $mi_menu_id . "' AND cat_parent_id ='" . $mi_cat_parent_id . "'";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, "Could not query from menu table", $lang['Error'], __LINE__, __FILE__, $sql);
					}

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
				menu_icon = '" . $mi_menu_icon . "',
				menu_name_lang = '" . $mi_menu_name_lang . "',
				menu_name = '" . addslashes($mi_menu_name) . "',
				menu_desc = '" . addslashes($mi_menu_desc) . "',
				menu_link = '" . $mi_menu_link . "',
				menu_link_external = '" . $mi_menu_link_external . "',
				auth_view = '" . $mi_auth_view . "',
				auth_view_group = '" . $mi_auth_view_group . "',
				menu_default = '" . $mi_menu_default . "'
				WHERE menu_item_id = '" . $mi_id . "'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not insert data into menu table", $lang['Error'], __LINE__, __FILE__, $sql);
			}

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
				$sql = "SELECT max(cat_id) max_cat_id, max(menu_order) max_menu_order FROM " . CMS_NAV_MENU_TABLE . " WHERE menu_parent_id ='" . $mi_menu_id . "' AND cat_parent_id ='0'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not query from menu table", $lang['Error'], __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrow($result);
				$mi_cat_id = $row['max_cat_id'] ? ($row['max_cat_id'] + 1) : 1;
				$mi_menu_order = $row['max_menu_order'] ? ($row['max_menu_order'] + 1) : 1;
			}
			else
			{
				$sql = "SELECT max(menu_order) max_menu_order FROM " . CMS_NAV_MENU_TABLE . " WHERE menu_parent_id ='" . $mi_menu_id . "' AND cat_parent_id ='" . $mi_cat_parent_id . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not query from menu table", $lang['Error'], __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrow($result);
				$mi_menu_order = $row['max_menu_order'] + 1;
			}

			$sql = "INSERT INTO " . CMS_NAV_MENU_TABLE . " (menu_id, menu_parent_id, cat_id, cat_parent_id, menu_status, menu_order, menu_icon, menu_name_lang, menu_name, menu_desc, menu_link, menu_link_external, auth_view, auth_view_group, menu_default) VALUES ('" . $mi_menu_sql_id . "', '" . $mi_menu_parent_id . "', '" . $mi_cat_id . "', '" . $mi_cat_parent_id . "', '" . $mi_menu_status . "', '" . $mi_menu_order . "', '" . $mi_menu_icon . "', '" . $mi_menu_name_lang . "', '" . addslashes($mi_menu_name) . "', '" . addslashes($mi_menu_desc) . "', '" . $mi_menu_link . "', '" . $mi_menu_link_external . "', '" . $mi_auth_view . "', '" . $mi_auth_view_group . "', '" . $mi_menu_default . "')";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not insert data into menu table", $lang['Error'], __LINE__, __FILE__, $sql);
			}

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
		$db->clear_cache('dyn_menu_');
		$message .= '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block&amp;m_id=' . $mi_menu_id) . '">', '</a>') . '<br />';
		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($action == 'delete')
	{
		$cat_id = 0;
		if(!empty($_GET['cat_id']))
		{
			$cat_id = intval($_GET['cat_id']);
		}
		if ($cat_id < 1)
		{
			$cat_id = 0;
		}
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
			$template->set_filenames(array('confirm' => CMS_TPL . 'confirm_body.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_ENABLED' => $lang['Enabled'],
				'L_DISABLED' => $lang['Disabled'],

				'S_CONFIRM_ACTION' => append_sid('cms_menu.' . $phpEx . '?' . $s_append_url),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template->pparse('confirm');
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
			exit();
		}
		else
		{
			if(($mi_id != 0) && ($m_id != 0))
			{
				if($item_type == 'category_item')
				{
					if($cat_id > 0)
					{
						$message = $lang['Cat_deleted'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block&amp;m_id=' . $m_id) . '">', '</a>') . '<br /><br />';
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
					$message = $lang['Link_deleted'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block&amp;m_id=' . $m_id) . '">', '</a>') . '<br /><br />';
					$sql = "DELETE FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_item_id = '" . $mi_id . "'";
				}
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Could not remove data from menu table", $lang['Error'], __LINE__, __FILE__, $sql);
				}

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
			$menu_upd_n = count($menu_upd);

			$menu_item_id_list = build_menu_item_id_list($m_id);
			$m_count = count($menu_item_id_list);

			for($i = 0; $i < $m_count; $i++)
			{
				$m_active = empty($menu_upd) ? 0 : (in_array($menu_item_id_list[$i], $menu_upd) ? 1 : 0);
				$sql = "UPDATE " . CMS_NAV_MENU_TABLE . "
								SET menu_status = '" . $m_active . "'
								WHERE menu_item_id = '" . $menu_item_id_list[$i] . "'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update menu table', $lang['Error'], __LINE__, __FILE__, $sql);
				}
			}
			$db->clear_cache('cms_');
			$message = '<br /><br />' . $lang['Menu_updated'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block&amp;m_id=' . $m_id) . '">', '</a>') . '<br />';
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
				$cat_parent_id = (isset($_GET['cat_parent_id'])) ? intval($_GET['cat_parent_id']) : 0;
				if ($cat_parent_id != 0)
				{
					change_item_order($mi_id, $cat_parent_id, $m_id, $move);
				}
			}
		}

		$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
						WHERE menu_parent_id = '" . $m_id . "'
						ORDER BY cat_parent_id ASC, menu_order ASC";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not query menu table", "Error", __LINE__, __FILE__, $sql);
		}

		$template->set_filenames(array('body' => CMS_TPL . 'cms_menu_block_list_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Menu_Page']);

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
					$cat_name = (($cat_item_data['menu_name'] != '') ? stripslashes($cat_item_data['menu_name']) : 'cat_item' . $cat_item_data['cat_id']) ;
				}
				$cat_desc = (($cat_item_data['menu_desc'] != '') ? htmlspecialchars(stripslashes($cat_item_data['menu_desc'])) : '') ;
				//$cat_icon = (($cat_item_data['menu_icon'] != '') ? '<img src="' . $cat_item_data['menu_icon'] . '" alt="' . $cat_name . '" title="' . $cat_name . '" style="vertical-align: middle;" />&nbsp;' : '');
				// No icon = Standard icon!
				$cat_icon = (($cat_item_data['menu_icon'] != '') ? '<img src="' . $cat_item_data['menu_icon'] . '" alt="" title="' . $cat_desc . '" style="vertical-align: middle;" />&nbsp;' : '<img src="' . $images['nav_menu_sep'] . '" alt="" title="" style="vertical-align: middle;" />&nbsp;');

				$append_url = '&amp;mi_id=' . $cat_item_data['menu_item_id'] . '&amp;m_id=' . $m_id . '&amp;item_type=category_item';

				$b_move_up = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block' . $append_url . '&amp;move=0') . '"><img src="' . $images['arrows_cms_up'] . '" alt="' . $lang['B_Move_Up'] . '" title="' . $lang['B_Move_Up'] . '" /></a>&nbsp;';
				$b_move_down = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block' . $append_url . '&amp;move=1') . '"><img src="' . $images['arrows_cms_down'] . '" alt="' . $lang['B_Move_Down'] . '" title="' . $lang['B_Move_Down'] . '" /></a>&nbsp;';
				$b_edit = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_item&amp;action=edit' . $append_url) . '"><img src="' . $images['block_edit'] . '" alt="' . $lang['CMS_Edit'] . '" title="' . $lang['CMS_Edit'] . '" /></a>&nbsp;';
				$b_delete = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_item&amp;action=delete&amp;cat_id=' . $cat_item_data['cat_id'] . $append_url) . '"><img src="' . $images['block_delete'] . '" alt="' . $lang['CSM_Delete'] . '" title="' . $lang['CSM_Delete'] . '" /></a>';

				if ((count($cat_item) == 1) && ($cat_counter == 1))
				{
					$b_move_up = '';
					$b_move_down = '';
				}
				elseif ((count($cat_item) > 1) && ($cat_counter == 1))
				{
					$b_move_up = '';
				}
				elseif (count($cat_item) == $cat_counter)
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
						$menu_desc = (($menu_cat_item_data['menu_desc'] != '') ? htmlspecialchars(stripslashes($menu_cat_item_data['menu_desc'])) : '');
						if ($menu_cat_item_data['menu_default'] == 0)
						{
							if (($menu_cat_item_data['menu_name_lang'] != '') && isset($lang['menu_item'][$menu_cat_item_data['menu_name_lang']]))
							{
								$menu_name = $lang['menu_item'][$menu_cat_item_data['menu_name_lang']];
							}
							else
							{
								$menu_name = (($menu_cat_item_data['menu_name'] != '') ? htmlspecialchars(stripslashes($menu_cat_item_data['menu_name'])) : 'cat_item' . $menu_cat_item_data['cat_id']);
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

						$b_move_up = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block' . $append_url . '&amp;move=0') . '"><img src="' . $images['arrows_cms_up'] . '" alt="' . $lang['B_Move_Up'] . '" title="' . $lang['B_Move_Up'] . '" /></a>&nbsp;';
						$b_move_down = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_block' . $append_url . '&amp;move=1') . '"><img src="' . $images['arrows_cms_down'] . '" alt="' . $lang['B_Move_Down'] . '" title="' . $lang['B_Move_Down'] . '" /></a>&nbsp;';
						$b_edit = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_item&amp;action=edit' . $append_url) . '"><img src="' . $images['block_edit'] . '" alt="' . $lang['CMS_Edit'] . '" title="' . $lang['CMS_Edit'] . '" /></a>&nbsp;';
						$b_delete = '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_item&amp;action=delete' . $append_url) . '"><img src="' . $images['block_delete'] . '" alt="' . $lang['CSM_Delete'] . '" title="' . $lang['CSM_Delete'] . '" /></a>';

						if ((count($menu_cat[$cat_id]) == 1) && ($item_counter == 1))
						{
							$b_move_up = '';
							$b_move_down = '';
						}
						elseif ((count($menu_cat[$cat_id]) > 1) && ($item_counter == 1))
						{
							$b_move_up = '';
						}
						elseif (count($menu_cat[$cat_id]) == $item_counter)
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
			'L_CMS_MENU_TITLE' => $lang['CMS_Menu_Page'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_Menu_Page_Explain'],
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
			'S_CAT_ADD_ACTION' => append_sid('cms_menu.' . $phpEx . '?mode=menu_item&amp;action=add&amp;m_id=' . $m_id . '&amp;item_type=category_item'),
			'S_MENU_ACTION' => append_sid('cms_menu.' . $phpEx . '?mode=menu_item&amp;action=add&amp;m_id=' . $m_id),
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
		$template->set_filenames(array('body' => CMS_TPL . 'cms_menu_menu_edit_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Menu_Page']);

		$mi_menu_name = '';
		$mi_menu_name_lang = '';
		$mi_menu_desc = '';
		if($action == 'edit')
		{
			if ($mi_id)
			{
				$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
								WHERE menu_item_id = '" . $mi_id . "'";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Could not query menu table", "Error", __LINE__, __FILE__, $sql);
				}

				$m_info = $db->sql_fetchrow($result);
				$mi_menu_name = htmlspecialchars(stripslashes($m_info['menu_name']));
				$mi_menu_desc = htmlspecialchars(stripslashes($m_info['menu_desc']));
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
			'L_CMS_MENU_TITLE' => $lang['CMS_Menu_Page'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_Menu_Page_Explain'],
			'L_EDIT_MENU_ITEM' => $lang['CMS_Menu_Item_Add_Edit'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_ENABLED' => $lang['Enabled'],
			'L_DISABLED' => $lang['Disabled'],

			'L_SUBMIT' => $lang['Submit'],
			'L_PREVIEW' => $lang['Preview'],
			'S_MENU_ACTION' => append_sid('cms_menu.' . $phpEx . '?mode=menu_list&amp;action=' . $action),
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
		$mi_menu_name = (isset($_POST['menu_name'])) ? trim($_POST['menu_name']) : '';
		$mi_menu_name_lang = (isset($_POST['menu_name_lang'])) ? trim($_POST['menu_name_lang']) : '';
		$mi_menu_desc = (isset($_POST['menu_desc'])) ? trim($_POST['menu_desc']) : '';

		if($mi_id)
		{
			$sql = "UPDATE " . CMS_NAV_MENU_TABLE . "
				SET
				menu_name = '" . addslashes($mi_menu_name) . "',
				menu_name_lang = '" . $mi_menu_name_lang . "',
				menu_desc = '" . addslashes($mi_menu_desc) . "'
				WHERE menu_item_id = '" . $mi_id . "'";

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not insert data into menu table", $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$message = $lang['Menu_updated'];
		}
		else
		{
			$sql = "SELECT max(menu_id) max_menu_id FROM " . CMS_NAV_MENU_TABLE;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not query from menu table", $lang['Error'], __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);
			$mi_menu_id = $row['max_menu_id'] + 1;

			$sql = "INSERT INTO " . CMS_NAV_MENU_TABLE . " (menu_id, menu_name, menu_name_lang, menu_desc) VALUES ('" . $mi_menu_id . "', '" . addslashes($mi_menu_name) . "', '" . $mi_menu_name_lang . "', '" . addslashes($mi_menu_desc) . "')";
			$message = $lang['Menu_created'];
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not insert data into menu table", $lang['Error'], __LINE__, __FILE__, $sql);
			}
		}
		$db->clear_cache('dyn_menu_');
		$message .= '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_list') . '">', '</a>') . '<br />';
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

			// Set template files
			$template->set_filenames(array('confirm' => CMS_TPL . 'confirm_body.tpl'));

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_ENABLED' => $lang['Enabled'],
				'L_DISABLED' => $lang['Disabled'],

				'S_CONFIRM_ACTION' => append_sid('cms_menu.' . $phpEx . '?mode=menu_list'),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template->pparse('confirm');
			include($phpbb_root_path . 'includes/page_tail.' . $phpEx);
			exit();
		}
		else
		{
			if(($mi_id != 0) && ($m_id != 0))
			{
				$sql = "DELETE FROM " . CMS_NAV_MENU_TABLE . "
					WHERE menu_item_id = '" . $mi_id . "'
						OR menu_parent_id = '" . $m_id . "'";

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not remove data from menu table", $lang['Error'], __LINE__, __FILE__, $sql);
			}

			$message = $lang['Menu_deleted'] . '<br /><br />' . sprintf($lang['Click_Return_CMS_Menu'], '<a href="' . append_sid('cms_menu.' . $phpEx . '?mode=menu_list') . '">', '</a>') . '<br /><br />';
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
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not query menu table", "Error", __LINE__, __FILE__, $sql);
		}

		$template->set_filenames(array('body' => CMS_TPL . 'cms_menu_list_body.tpl'));
		$template->assign_var('CMS_PAGE_TITLE', $lang['CMS_Menu_Page']);

		$menu_item = array();
		while ($menu_item = $db->sql_fetchrow($result))
		{
			$menu_id = ($menu_item['menu_id']);

			$append_url = '&amp;mi_id=' . $menu_item['menu_item_id'] . '&amp;m_id=' . $menu_item['menu_id'];

			$template->assign_block_vars('menu_row', array(
				'MENU_ID' => $menu_item['menu_id'],
				'MENU_NAME' => stripslashes($menu_item['menu_name']),
				'MENU_DESCRIPTION' => stripslashes($menu_item['menu_desc']),
				'U_ITEMS_EDIT' => append_sid('cms_menu.' . $phpEx . '?mode=menu_block' . $append_url),
				'U_EDIT' => append_sid('cms_menu.' . $phpEx . '?mode=menu_list&amp;action=edit' . $append_url),
				'U_DELETE' => append_sid('cms_menu.' . $phpEx . '?mode=menu_list&amp;action=delete' . $append_url),
				)
			);
		}

		$template->assign_vars(array(
			'L_CMS_MENU_TITLE' => $lang['CMS_Menu_Page'],
			'L_CMS_MENU_EXPLAIN' => $lang['CMS_Menu_Page_Explain'],
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
			'S_MENU_ACTION' => append_sid('cms_menu.' . $phpEx . '?mode=menu_list'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);
	}

}

$db->clear_cache('dyn_menu_');
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

/*
=====================================
Functions
=====================================
*/

function change_cat_order($mi_id, $m_parent_id, $move)
{
	global $db, $lang;

	$move = ($move == '1') ? '1' : '0';
	$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
				WHERE menu_parent_id = '" . $m_parent_id . "'
					AND cat_parent_id = '0'
				ORDER BY menu_order ASC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu table", $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$item_order = 0;
	$weight_assigned = 0;
	$last_mi_id = 0;
	$to_change_mi_id = 0;
	//echo($db->sql_numrows($result));
	while($row = $db->sql_fetchrow($result))
	{
		$item_order++;

		if ($row['menu_item_id'] == $mi_id)
		{
			$weight_assigned = $item_order;
			if (($move == '0') && ($item_order > 1))
			{
				$to_change_mi_id = $last_mi_id;
			}
		}

		if (($weight_assigned == ($item_order - 1)) && ($move == '1'))
		{
			$to_change_mi_id = $row['menu_item_id'];
		}

		$sql_alt = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $row['menu_item_id'] . "'";
		if(!$result_alt = $db->sql_query($sql_alt))
		{
			message_die(GENERAL_ERROR, "Could not update menu table", $lang['Error'], __LINE__, __FILE__, $sql_alt);
		}
		$last_mi_id = $row['menu_item_id'];
	}

	if ($to_change_mi_id != 0)
	{
		$item_order = ($move == '1') ? ($weight_assigned + 1) : ($weight_assigned - 1);
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $mi_id . "'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not update menu table", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$item_order = ($move == '1') ? ($weight_assigned - 1) : ($weight_assigned + 1);
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $to_change_mi_id . "'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not update menu table", $lang['Error'], __LINE__, __FILE__, $sql);
		}
	}
}

function change_item_order($mi_id, $cat_parent_id, $m_parent_id, $move)
{
	global $db, $lang;

	$move = ($move == '1') ? '1' : '0';
	$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
				WHERE menu_parent_id = '" . $m_parent_id . "'
					AND cat_parent_id = '" . $cat_parent_id . "'
				ORDER BY menu_order ASC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu table", $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$item_order = 0;
	$weight_assigned = 0;
	$last_mi_id = 0;
	$to_change_mi_id = 0;
	//echo($db->sql_numrows($result));
	while($row = $db->sql_fetchrow($result))
	{
		$item_order++;

		if ($row['menu_item_id'] == $mi_id)
		{
			$weight_assigned = $item_order;
			if (($move == '0') && ($item_order > 1))
			{
				$to_change_mi_id = $last_mi_id;
			}
		}

		if (($weight_assigned == ($item_order - 1)) && ($move == '1') && ($item_order > 1))
		{
			$to_change_mi_id = $row['menu_item_id'];
		}

		$sql_alt = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $row['menu_item_id'] . "'";
		if(!$result_alt = $db->sql_query($sql_alt))
		{
			message_die(GENERAL_ERROR, "Could not update menu table", $lang['Error'], __LINE__, __FILE__, $sql_alt);
		}
		$last_mi_id = $row['menu_item_id'];
	}
	if ($to_change_mi_id != 0)
	{
		$item_order = ($move == '1') ? ($weight_assigned + 1) : ($weight_assigned - 1);
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $mi_id . "'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not update menu table", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		//$item_order = ($move == '1') ? ($weight_assigned - 1) : ($weight_assigned + 1);
		$item_order = $weight_assigned;
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $to_change_mi_id . "'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not update menu table", $lang['Error'], __LINE__, __FILE__, $sql);
		}
	}
}

function adjust_item_order($m_parent_id, $cat_parent_id)
{
	global $db, $lang;

	$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
				WHERE menu_parent_id = '" . $m_parent_id . "'
					AND cat_parent_id = '" . $cat_parent_id . "'
				ORDER BY menu_order ASC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu table", $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$item_order = 0;
	while($row = $db->sql_fetchrow($result))
	{
		$item_order++;
		$sql_alt = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $row['menu_item_id'] . "'";
		if(!$result_alt = $db->sql_query($sql_alt))
		{
			message_die(GENERAL_ERROR, "Could not update menu table", $lang['Error'], __LINE__, __FILE__, $sql_alt);
		}
	}
}

function build_menu_list()
{
	global $db;

	$sql = "SELECT *
		FROM " . CMS_NAV_MENU_TABLE . "
		WHERE menu_parent_id = 0
			AND cat_parent_id = 0";

	if(!$menu_list = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu table", "", __LINE__, __FILE__);
	}
	return $menu_list;
}

function build_menu_item_id_list($m_id)
{
	global $db;

	$sql = "SELECT menu_item_id
		FROM " . CMS_NAV_MENU_TABLE . "
		WHERE menu_parent_id = '" . $m_id . "'";

	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu table", "", __LINE__, __FILE__);
	}

	$menu_item_id_list = array();
	while($row = $db->sql_fetchrow($result))
	{
		$menu_item_id_list[] = $row['menu_item_id'];
	}
	return $menu_item_id_list;
}

function build_cat_list($m_id)
{
	global $db;

	$sql = "SELECT *
		FROM " . CMS_NAV_MENU_TABLE . "
		WHERE menu_parent_id = '" . $m_id . "'
			AND cat_parent_id = 0";

	if(!$cat_list = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu table", "", __LINE__, __FILE__);
	}
	return $cat_list;
}

function build_menu_item_list($m_id, $c_id)
{
	global $db;

	$sql = "SELECT *
		FROM " . CMS_NAV_MENU_TABLE . "
		WHERE menu_parent_id = '" . $m_id . "'
			AND cat_parent_id = '" . $c_id . "'";

	if(!$menu_item_list = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query menu table", "", __LINE__, __FILE__);
	}
	return $menu_item_list;
}

function build_icons_list($select_name = 'icon_img_sel', $input_name = 'icon_img_path', $selected_icon = '', $icons_path = '', $standard_icon = '')
{
	global $lang, $images, $phpbb_root_path;
	$icons_list = '';
	if ($icons_path == '')
	{
		$icons_path = 'images/menu/';
	}

	$filetypes = 'jpg,gif,png';
	$types = explode(',', $filetypes);

	if (is_dir($icons_path))
	{
		$dir = opendir($icons_path);
		$l = 0;

		while($file = readdir($dir))
		{
			$file_split = explode('.', strtolower($file));
			$extension = $file_split[count($file_split) - 1];
			if(in_array($extension, $types))
			{
				$file1[$l] = $file;
				$l++;
			}
		}
		closedir($dir);
		$icons_list = '<select name="' . $select_name . '" onchange="update_icon(this.options[selectedIndex].value);">';
		$std_icon_selected = '';
		$no_icon_selected = '';
		if ($selected_icon == '')
		{
			$no_icon_selected = ' selected="selected"';
		}
		else
		{
			if ($selected_icon == $standard_icon)
			{
				$std_icon_selected = ' selected="selected"';
			}
			$icons_list .= '<option value="' . $selected_icon . '" selected="selected">' . $selected_icon . '</option>';
		}
		/*
		$icons_list .= '<option value=""' . $no_icon_selected . '>' . $lang['CMS_Menu_No_Icon'] . '</option>';
		$icons_list .= '<option value="' . $standard_icon . '"' . $std_icon_selected . '>' . $lang['CMS_Menu_Standard_Icon'] . '</option>';
		*/
		// No icon = Standard icon!
		$icons_list .= '<option value=""' . $no_icon_selected . '>' . $lang['CMS_Menu_Standard_Icon'] . '</option>';
		for($k = 0; $k <= $l; $k++)
		{
			if ($file1[$k] != '')
			{
				$icons_list .= '<option value="' . $icons_path . $file1[$k] . '">' . $icons_path . $file1[$k] . '</option>';
			}
		}
		$icon_img_sp = (($selected_icon != '') ? $selected_icon : $images['spacer']);
		$icons_list .= '</select>';
		$icons_list .= '&nbsp;&nbsp;<img name="icon_image" src="' . $icon_img_sp . '" alt="" align="middle" />';
		$icons_list .= '<br /><br />';
	}
	$icon_img_path = ($selected_icon != '') ? $selected_icon : '';
	$icons_list .= '<input class="post" type="text" name="' . $input_name . '" size="40" maxlength="512" value="' . $icon_img_path . '" /><br />';

	return $icons_list;
}

function build_default_link_auth($default_id)
{
	// 0: All, 1: Only Guest, 2: Reg, 3: MOD, 4: ADMIN
	$link_auth_array = '';
	$link_auth_array = array(
		'0' => '',
		'1' => '4', //$lang['Admin_panel'],
		'2' => '4', //$lang['CMS_Title'],
		'3' => '0', //$lang['Home'],
		'4' => '2', //$lang['Profile'],
		'5' => '0', //$lang['Forum_Index'],
		'6' => '0', //$lang['FAQ'],
		'7' => '0', //$lang['Search'],
		'8' => '0', //$lang['Sitemap'],
		'9' => '0', //$lang['Album'],
		'10' => '0', //$lang['Calendar'],
		'11' => '0', //$lang['Downloads'],
		'12' => '2', //$lang['Bookmarks'],
		'13' => '2', //$lang['Drafts'],
		'14' => '2', //$lang['Upload_Image_Local'],
		'15' => '0', //$lang['Ajax_Chat'],
		'16' => '0', //$lang['Links'],
		'17' => '0', //$lang['KB_title'],
		'18' => '0', //$lang['Contact_us'],
		'19' => '0', //$lang['BoardRules'],
		'20' => '4', //$lang['DBGenerator'],
		'21' => '2', //$lang['Sudoku'],
		'22' => '', //$lang['NewsCat'],
		'23' => '', //$lang['NewsArc'],
		'24' => '2', //$lang['New3'],
		'25' => '2', //$lang['upi2db_unread'],
		'26' => '2', //$lang['upi2db_marked'],
		'27' => '2', //$lang['upi2db_perm_read'],
		'28' => '2', //$lang['Posts'] .': '. $lang['New2'] . ' - ' . $lang['upi2db_u'] . ' - ' . $lang['upi2db_m'] . ' - ' . $lang['upi2db_p'],
		'29' => '2', //$lang['Digests'],
		'30' => '0', //$lang['Hacks_List'],
		'31' => '0', //$lang['Referrers'],
		'32' => '0', //$lang['Who_is_Online'],
		'33' => '0', //$lang['Statistics'],
		'34' => '0', //$lang['Site_Hist'],
		'35' => '0', //$lang['Delete_cookies'],
		'36' => '0', //$lang['Memberlist'],
		'37' => '0', //$lang['Usergroups'],
		'38' => '0', //$lang['Rank_Header'],
		'39' => '0', //$lang['Staff'],
		'40' => '0', //$lang['Change_Style'],
		'41' => '1', //$lang['Change_Lang'],
		'42' => '0', //$lang['Rss_news_feeds'],
		'43' => '1', //$lang['Register'],
		'44' => '0', //$lang['Login'] . ' - ' . $lang['Logout']
	);
	return $link_auth_array[$default_id];
}

function build_default_link_url($default_id)
{
	global $phpEx;
	$link_url_array = '';
	$link_url_array = array(
		'0' => '',
		'1' => 'adm/index.' . $phpEx,
		'2' => 'cms.' . $phpEx,
		'3' => 'index.' . $phpEx,
		'4' => 'profile_main.' . $phpEx,
		'5' => 'forum.' . $phpEx,
		'6' => 'faq.' . $phpEx,
		'7' => 'search.' . $phpEx,
		'8' => 'sitemap.' . $phpEx,
		'9' => 'album.' . $phpEx,
		'10' => 'calendar.' . $phpEx,
		'11' => 'dload.' . $phpEx,
		'12' => 'search.' . $phpEx . '?search_id=bookmarks',
		'13' => 'drafts.' . $phpEx,
		'14' => 'posted_img_list.' . $phpEx,
		'15' => 'ajax_chat.' . $phpEx,
		'16' => 'links.' . $phpEx,
		'17' => 'kb.' . $phpEx,
		'18' => 'contact_us.' . $phpEx,
		'19' => 'rules.' . $phpEx,
		'20' => 'db_generator.' . $phpEx,
		'21' => 'sudoku.' . $phpEx,
		'22' => 'index.' . $phpEx . '?news=categories',
		'23' => 'index.' . $phpEx . '?news=archives',
		'24' => 'search.' . $phpEx . '?search_id=newposts',
		'25' => 'search.' . $phpEx . '?search_id=upi2db&s2=new',
		'26' => 'search.' . $phpEx . '?search_id=upi2db&s2=mark',
		'27' => 'search.' . $phpEx . '?search_id=upi2db&s2=perm',
		'28' => '',
		'29' => 'digests.' . $phpEx,
		'30' => 'credits.' . $phpEx,
		'31' => 'referrers.' . $phpEx,
		'32' => 'viewonline.' . $phpEx,
		'33' => 'statistics.' . $phpEx,
		'34' => 'site_hist.' . $phpEx,
		'35' => 'remove_cookies.' . $phpEx,
		'36' => 'memberlist.' . $phpEx,
		'37' => 'groupcp.' . $phpEx,
		'38' => 'ranks.' . $phpEx,
		'39' => 'memberlist.' . $phpEx . '?mode=staff',
		'40' => '',
		'41' => '',
		'42' => '',
		'43' => 'profile.' . $phpEx . '?mode=register',
		'44' => ''
	);
	return $link_url_array[$default_id];
}

?>