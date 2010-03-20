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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('admin_display_category_auth'))
{
	function admin_display_category_auth($cat_parent = 0, $depth = 0)
	{
		global $pafiledb, $template;
		global $cat_auth_fields, $optionlist_mod, $optionlist_acl_adv;
		$pre = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
		if(isset($pafiledb->subcat_rowset[$cat_parent]))
		{
			foreach($pafiledb->subcat_rowset[$cat_parent] as $sub_cat_id => $cat_data)
			{
				$template->assign_block_vars('cat_row', array(
					'CAT_NAME' => $cat_data['cat_name'],
					'IS_HIGHER_CAT' => ($cat_data['cat_allow_file']) ? false : true,
					'PRE' => $pre,
					'U_CAT' => append_sid('admin_pa_catauth.' . PHP_EXT . '?cat_id=' . $sub_cat_id),
					'S_MOD_SELECT' => $optionlist_mod[$sub_cat_id]
					)
				);

				for($j = 0; $j < sizeof($cat_auth_fields); $j++)
				{
					$template->assign_block_vars('cat_row.aclvalues', array(
						'S_ACL_SELECT' => $optionlist_acl_adv[$sub_cat_id][$j]
						)
					);
				}
				admin_display_category_auth($sub_cat_id, $depth + 1);
			}
			return;
		}
		return;
	}
}

if(!function_exists('global_auth_check_user'))
{
	function global_auth_check_user($type, $key, $global_u_access, $is_admin)
	{
		$auth_user = 0;

		if (!empty($global_u_access))
		{
			$result = 0;
			switch($type)
			{
				case AUTH_ACL:
					$result = $global_u_access[$key];

				case AUTH_MOD:
					$result = $result || is_moderator($global_u_access['group_id']);

				case AUTH_ADMIN:
					$result = $result || $is_admin;
					break;
			}

			$auth_user = $auth_user || $result;
		}
		else
		{
			$auth_user = $is_admin;
		}

		return $auth_user;
	}
}

if(!function_exists('is_moderator'))
{
	function is_moderator($group_id)
	{
		static $is_mod = false;

		if($is_mod !== false)
		{
			return $is_mod;
		}

		global $db;

		$sql = "SELECT *
			FROM " . PA_AUTH_ACCESS_TABLE . "
			WHERE group_id = $group_id
			AND auth_mod = '1'";
		$result = $db->sql_query($sql);

		return ($is_mod = ($db->sql_fetchrow($result)) ? 1 : 0);
	}
}

if(!function_exists('pa_size_select'))
{
	function pa_size_select($select_name, $size_compare)
	{
		global $lang;

		$size_types_text = array($lang['Bytes'], $lang['KB'], $lang['MB']);
		$size_types = array('b', 'kb', 'mb');

		$select_field = '<select name="' . $select_name . '">';

		for ($i = 0; $i < sizeof($size_types_text); $i++)
		{
			$selected = ($size_compare == $size_types[$i]) ? ' selected="selected"' : '';

			$select_field .= '<option value="' . $size_types[$i] . '"' . $selected . '>' . $size_types_text[$i] . '</option>';
		}

		$select_field .= '</select>';

		return ($select_field);
	}
}

if(!function_exists('admin_cat_main'))
{
	function admin_cat_main($cat_parent = 0, $depth = 0)
	{
		global $pafiledb, $template;

		$pre = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
		if(isset($pafiledb->subcat_rowset[$cat_parent]))
		{
			foreach($pafiledb->subcat_rowset[$cat_parent] as $subcat_id => $cat_data)
			{
				$template->assign_block_vars('cat_row', array(
					'IS_HIGHER_CAT' => ($cat_data['cat_allow_file'] == PA_CAT_ALLOW_FILE) ? false : true,
					'U_CAT' => append_sid('admin_pa_category.php?cat_id=' . $subcat_id),
					'U_CAT_EDIT' => append_sid('admin_pa_category.' . PHP_EXT . '?mode=edit&amp;cat_id=' . $subcat_id),
					'U_CAT_DELETE' => append_sid('admin_pa_category.' . PHP_EXT . '?mode=delete&amp;cat_id=' . $subcat_id),
					'U_CAT_MOVE_UP' => append_sid('admin_pa_category.' . PHP_EXT . '?mode=cat_order&amp;move=-15&amp;cat_id_other=' . $subcat_id),
					'U_CAT_MOVE_DOWN' => append_sid('admin_pa_category.' . PHP_EXT . '?mode=cat_order&amp;move=15&amp;cat_id_other=' . $subcat_id),
					'U_CAT_RESYNC' => append_sid('admin_pa_category.' . PHP_EXT . '?mode=sync&amp;cat_id_other=' . $subcat_id),
					'CAT_NAME' => $cat_data['cat_name'],
					'PRE' => $pre
					)
				);
				admin_cat_main($subcat_id, $depth + 1);
			}
			return;
		}
		return;
	}
}

if (!function_exists('admin_display_cat_auth'))
{
	function admin_display_cat_auth($cat_parent = 0, $depth = 0)
	{
		global $pafiledb, $template;
		global $cat_auth_fields, $cat_auth_const, $cat_auth_levels, $lang;
		$pre = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
		if(isset($pafiledb->subcat_rowset[$cat_parent]))
		{
			foreach($pafiledb->subcat_rowset[$cat_parent] as $sub_cat_id => $cat_data)
			{
				$template->assign_block_vars('cat_row', array(
					'CATEGORY_NAME' => $cat_data['cat_name'],
					'IS_HIGHER_CAT' => ($cat_data['cat_allow_file']) ? false : true,
					'PRE' => $pre,
					'U_CAT' => append_sid('admin_pa_catauth.' . PHP_EXT . '?cat_parent=' . $sub_cat_id))
				);

				for($j = 0; $j < sizeof($cat_auth_fields); $j++)
				{
					$custom_auth[$j] = '&nbsp;<select name="' . $cat_auth_fields[$j] . '[' . $sub_cat_id . ']' . '">';

					for($k = 0; $k < sizeof($cat_auth_levels); $k++)
					{
						$selected = ($cat_data[$cat_auth_fields[$j]] == $cat_auth_const[$k]) ? ' selected="selected"' : '';
						$custom_auth[$j] .= '<option value="' . $cat_auth_const[$k] . '"' . $selected . '>' . $lang['Category_' . $cat_auth_levels[$k]] . '</option>';
					}
					$custom_auth[$j] .= '</select>&nbsp;';

					$template->assign_block_vars('cat_row.cat_auth_data', array(
						'S_AUTH_LEVELS_SELECT' => $custom_auth[$j])
					);
				}
				admin_display_cat_auth($sub_cat_id, $depth + 1);
			}
			return;
		}
		return;
	}
}

?>