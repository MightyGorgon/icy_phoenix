<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function change_cat_order($mi_id, $m_parent_id, $move)
{
	global $db, $lang;

	$move = ($move == '1') ? '1' : '0';
	$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
				WHERE menu_parent_id = '" . $m_parent_id . "'
					AND cat_parent_id = '0'
				ORDER BY menu_order ASC";
	$result = $db->sql_query($sql);

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
		$result_alt = $db->sql_query($sql_alt);
		$last_mi_id = $row['menu_item_id'];
	}

	if ($to_change_mi_id != 0)
	{
		$item_order = ($move == '1') ? ($weight_assigned + 1) : ($weight_assigned - 1);
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $mi_id . "'";
		$result = $db->sql_query($sql);

		$item_order = ($move == '1') ? ($weight_assigned - 1) : ($weight_assigned + 1);
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $to_change_mi_id . "'";
		$result = $db->sql_query($sql);
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
	$result = $db->sql_query($sql);

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
		$result_alt = $db->sql_query($sql_alt);
		$last_mi_id = $row['menu_item_id'];
	}
	if ($to_change_mi_id != 0)
	{
		$item_order = ($move == '1') ? ($weight_assigned + 1) : ($weight_assigned - 1);
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $mi_id . "'";
		$result = $db->sql_query($sql);

		//$item_order = ($move == '1') ? ($weight_assigned - 1) : ($weight_assigned + 1);
		$item_order = $weight_assigned;
		$sql = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $to_change_mi_id . "'";
		$result = $db->sql_query($sql);
	}
}

function adjust_item_order($m_parent_id, $cat_parent_id)
{
	global $db, $lang;

	$sql = "SELECT * FROM " . CMS_NAV_MENU_TABLE . "
				WHERE menu_parent_id = '" . $m_parent_id . "'
					AND cat_parent_id = '" . $cat_parent_id . "'
				ORDER BY menu_order ASC";
	$result = $db->sql_query($sql);
	$item_order = 0;
	while($row = $db->sql_fetchrow($result))
	{
		$item_order++;
		$sql_alt = "UPDATE " . CMS_NAV_MENU_TABLE . " SET menu_order = '" . $item_order . "' WHERE menu_item_id = '" . $row['menu_item_id'] . "'";
		$result_alt = $db->sql_query($sql_alt);
	}
}

function build_menu_item_id_list($m_id)
{
	global $db;

	$sql = "SELECT menu_item_id
		FROM " . CMS_NAV_MENU_TABLE . "
		WHERE menu_parent_id = '" . $m_id . "'";
	$result = $db->sql_query($sql);
	$menu_item_id_list = array();
	while($row = $db->sql_fetchrow($result))
	{
		$menu_item_id_list[] = $row['menu_item_id'];
	}
	return $menu_item_id_list;
}

function build_icons_list($select_name = 'icon_img_sel', $input_name = 'icon_img_path', $selected_icon = '', $icons_path = '', $standard_icon = '')
{
	global $lang, $images;
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
			$extension = end($file_split);
			if(in_array($extension, $types))
			{
				$file1[$l] = $file;
				$l++;
			}
		}
		closedir($dir);
		sort($file1);
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

?>