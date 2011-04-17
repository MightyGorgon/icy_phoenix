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

function build_default_link_auth($default_id)
{
	// 0: All, 1: Only Guest, 2: Reg, 3: MOD, 4: ADMIN
	$link_auth_array = '';
	$link_auth_array = array(
		'0' => '',
		'1' => '4', //$lang['Admin_panel'],
		'2' => '4', //$lang['CMS_TITLE'],
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
		//'20' => '4', //$lang['DBGenerator'],
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
		//'34' => '0', //$lang['Site_Hist'],
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
	$link_url_array = '';
	$link_url_array = array(
		'0' => '',
		'1' => 'adm/index.' . PHP_EXT,
		'2' => CMS_PAGE_CMS,
		'3' => CMS_PAGE_HOME,
		'4' => CMS_PAGE_PROFILE_MAIN,
		'5' => CMS_PAGE_FORUM,
		'6' => CMS_PAGE_FAQ,
		'7' => CMS_PAGE_SEARCH,
		'8' => 'sitemap.' . PHP_EXT,
		'9' => CMS_PAGE_ALBUM,
		'10' => CMS_PAGE_CALENDAR,
		'11' => CMS_PAGE_DL_DEFAULT,
		'12' => CMS_PAGE_SEARCH . '?search_id=bookmarks',
		'13' => CMS_PAGE_DRAFTS,
		'14' => 'posted_img_list.' . PHP_EXT,
		'15' => CMS_PAGE_AJAX_CHAT,
		'16' => CMS_PAGE_LINKS,
		'17' => 'kb.' . PHP_EXT,
		'18' => CMS_PAGE_CONTACT_US,
		'19' => CMS_PAGE_RULES,
		//'20' => 'db_generator.' . PHP_EXT,
		'21' => 'sudoku.' . PHP_EXT,
		'22' => CMS_PAGE_HOME . '?news=categories',
		'23' => CMS_PAGE_HOME . '?news=archives',
		'24' => CMS_PAGE_SEARCH . '?search_id=newposts',
		'25' => CMS_PAGE_SEARCH . '?search_id=upi2db&s2=new',
		'26' => CMS_PAGE_SEARCH . '?search_id=upi2db&s2=mark',
		'27' => CMS_PAGE_SEARCH . '?search_id=upi2db&s2=perm',
		'28' => '',
		'29' => 'digests.' . PHP_EXT,
		'30' => CMS_PAGE_CREDITS,
		'31' => CMS_PAGE_REFERRERS,
		'32' => CMS_PAGE_VIEWONLINE,
		'33' => CMS_PAGE_STATISTICS,
		//'34' => 'site_hist.' . PHP_EXT,
		'35' => 'remove_cookies.' . PHP_EXT,
		'36' => CMS_PAGE_MEMBERLIST,
		'37' => CMS_PAGE_GROUP_CP,
		'38' => 'ranks.' . PHP_EXT,
		'39' => CMS_PAGE_MEMBERLIST . '?mode=staff',
		'40' => '',
		'41' => '',
		'42' => '',
		'43' => 'profile.' . PHP_EXT . '?mode=register',
		'44' => ''
	);
	return $link_url_array[$default_id];
}

?>