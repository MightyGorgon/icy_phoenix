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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	//$module['Download']['User_Permissions'] = $filename . "?mode=user";
	//$module['Download']['Group_Permissions'] = $filename . "?mode=group";
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/pafiledb_common.' . PHP_EXT);

$pafiledb->init();

$mode = request_var('mode', '');
$user_id = request_var(POST_USERS_URL, 0);
$group_id = request_var(POST_GROUPS_URL, 0);

// MX Addon
$cat_auth_fields = array('auth_view', 'auth_read', 'auth_view_file', 'auth_edit_file', 'auth_delete_file', 'auth_upload', 'auth_download', 'auth_rate', 'auth_email', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment');
$global_auth_fields = array('auth_search', 'auth_stats', 'auth_toplist', 'auth_viewall');

$global_fields_names = array(
	'auth_search' => $lang['Auth_search'],
	'auth_stats' => $lang['Auth_stats'],
	'auth_toplist' => $lang['Auth_toplist'],
	'auth_viewall' => $lang['Auth_viewall']
);

$field_names = array(
	'auth_view' => $lang['View'],
	'auth_read' => $lang['Read'],
	'auth_view_file' => $lang['View_file'],
// MX Addon
	'auth_edit_file' => $lang['Edit_file'],
	'auth_delete_file' => $lang['Delete_file'],
// End
	'auth_upload' => $lang['Upload'],
	'auth_download' => $lang['Download_file'],
	'auth_rate' => $lang['Rate'],
	'auth_email' => $lang['Email'],
	'auth_view_comment' => $lang['View_comment'],
	'auth_post_comment' => $lang['Post_comment'],
	'auth_edit_comment' => $lang['Edit_comment'],
	'auth_delete_comment' => $lang['Delete_comment']
);

$permissions_menu = array(
	append_sid('admin_pa_catauth.' . PHP_EXT) => $lang['Cat_Permissions'],
	append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=user') => $lang['User_Permissions'],
	append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=group') => $lang['Group_Permissions'],
	append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=glob_user') => $lang['User_Global_Permissions'],
	append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=glob_group') => $lang['Group_Global_Permissions']
);

foreach($permissions_menu as $url => $l_name)
{
	$template->assign_block_vars('pertype', array(
		'U_NAME' => $url,
		'L_NAME' => $l_name
		)
	);
}


if (isset($_POST['submit']) && ((($mode == 'user') && $user_id) || (($mode == 'group') && $group_id)))
{
	if($mode == 'user')
	{
		$sql = "SELECT g.group_id
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id = $user_id
			AND g.group_id = ug.group_id
			AND g.group_single_user = '1'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$group_id = $row['group_id'];
		$db->sql_freeresult($result);
	}

	$change_mod_list = request_post_var('moderator', array(''));

	$change_acl_list = array();
	for($j = 0; $j < sizeof($cat_auth_fields); $j++)
	{
		$auth_field = $cat_auth_fields[$j];

		while(list($cat_id, $value) = @each($_POST['private_' . $auth_field]))
		{
			$change_acl_list[$cat_id][$auth_field] = $value;
		}
	}

	$sql = ($mode == 'user') ? "SELECT aa.* FROM " . PA_AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = " . TRUE : "SELECT * FROM " . PA_AUTH_ACCESS_TABLE . " WHERE group_id = $group_id";
	$result = $db->sql_query($sql);

	$auth_access = array();
	while($row = $db->sql_fetchrow($result))
	{
		$auth_access[$row['cat_id']] = $row;
	}
	$db->sql_freeresult($result);

	$cat_auth_action = array();
	$update_acl_status = array();
	$update_mod_status = array();

	foreach($pafiledb->cat_rowset as $cat_id => $cat_data)
	{
		if ((isset($auth_access[$cat_id]['auth_mod']) && $change_mod_list[$cat_id]['auth_mod'] != $auth_access[$cat_id]['auth_mod']) || (!isset($auth_access[$cat_id]['auth_mod']) && !empty($change_mod_list[$cat_id]['auth_mod'])))
		{
			$update_mod_status[$cat_id] = $change_mod_list[$cat_id]['auth_mod'];

			if (!$update_mod_status[$cat_id])
			{
				$cat_auth_action[$cat_id] = 'delete';
			}
			elseif (!isset($auth_access[$cat_id]['auth_mod']))
			{
				$cat_auth_action[$cat_id] = 'insert';
			}
			else
			{
				$cat_auth_action[$cat_id] = 'update';
			}
		}

		for($j = 0; $j < sizeof($cat_auth_fields); $j++)
		{
			$auth_field = $cat_auth_fields[$j];

			if($cat_data[$auth_field] == AUTH_ACL && isset($change_acl_list[$cat_id][$auth_field]))
			{
				if ((empty($auth_access[$cat_id]['auth_mod']) && (isset($auth_access[$cat_id][$auth_field]) && $change_acl_list[$cat_id][$auth_field] != $auth_access[$cat_id][$auth_field]) || (!isset($auth_access[$cat_id][$auth_field]) && !empty($change_acl_list[$cat_id][$auth_field]))) || !empty($update_mod_status[$cat_id])
				)
				{
					$update_acl_status[$cat_id][$auth_field] = (!empty($update_mod_status[$cat_id])) ? 0 :  $change_acl_list[$cat_id][$auth_field];

					if (isset($auth_access[$cat_id][$auth_field]) && empty($update_acl_status[$cat_id][$auth_field]) && $cat_auth_action[$cat_id] != 'insert' && $cat_auth_action[$cat_id] != 'update')
					{
						$cat_auth_action[$cat_id] = 'delete';
					}
					elseif (!isset($auth_access[$cat_id][$auth_field]) && !($cat_auth_action[$cat_id] == 'delete' && empty($update_acl_status[$cat_id][$auth_field])))
					{
						$cat_auth_action[$cat_id] = 'insert';
					}
					elseif (isset($auth_access[$cat_id][$auth_field]) && !empty($update_acl_status[$cat_id][$auth_field]))
					{
						$cat_auth_action[$cat_id] = 'update';
					}
				}
				elseif ((empty($auth_access[$cat_id]['auth_mod']) && (isset($auth_access[$cat_id][$auth_field]) && $change_acl_list[$cat_id][$auth_field] == $auth_access[$cat_id][$auth_field])) && $cat_auth_action[$cat_id] == 'delete')
				{
					$cat_auth_action[$cat_id] = 'update';
				}
			}
		}
	}

	// Checks complete, make updates to DB
	$delete_sql = '';
	while(list($cat_id, $action) = @each($cat_auth_action))
	{
		if ($action == 'delete')
		{
			$delete_sql .= (($delete_sql != '') ? ', ' : '') . $cat_id;
		}
		else
		{
			if ($action == 'insert')
			{
				$sql_field = '';
				$sql_value = '';
				while (list($auth_type, $value) = @each($update_acl_status[$cat_id]))
				{
					$sql_field .= (($sql_field != '') ? ', ' : '') . $auth_type;
					$sql_value .= (($sql_value != '') ? ', ' : '') . $value;
				}
				$sql_field .= (($sql_field != '') ? ', ' : '') . 'auth_mod';
				$sql_value .= (($sql_value != '') ? ', ' : '') . ((!isset($update_mod_status[$cat_id])) ? 0 : $update_mod_status[$cat_id]);

				$sql = "INSERT INTO " . PA_AUTH_ACCESS_TABLE . " (cat_id, group_id, $sql_field)
							VALUES ($cat_id, $group_id, $sql_value)";
			}
			else
			{
				$sql_values = '';
				while (list($auth_type, $value) = @each($update_acl_status[$cat_id]))
				{
					$sql_values .= (($sql_values != '') ? ', ' : '') . $auth_type . ' = ' . $value;
				}
				$sql_values .= (($sql_values != '') ? ', ' : '') . 'auth_mod = ' . ((!isset($update_mod_status[$cat_id])) ? 0 : $update_mod_status[$cat_id]);

				$sql = "UPDATE " . PA_AUTH_ACCESS_TABLE . "
					SET $sql_values
					WHERE group_id = $group_id
					AND cat_id = $cat_id";
			}
			$result = $db->sql_query($sql);
		}
	}

	if ($delete_sql != '')
	{
		$sql = "DELETE FROM " . PA_AUTH_ACCESS_TABLE . "
			WHERE group_id = $group_id
			AND cat_id IN ($delete_sql)";
		$result = $db->sql_query($sql);
	}

	$l_auth_return = ($mode == 'user') ? $lang['Click_return_userauth'] : $lang['Click_return_groupauth'];
	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($l_auth_return, '<a href="' . append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif (isset($_POST['submit']) && ((($mode == 'glob_user') && $user_id) || (($mode == 'glob_group') && $group_id)))
{
	if($mode == 'glob_user')
	{
		$sql = "SELECT g.group_id
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id = $user_id
			AND g.group_id = ug.group_id
			AND g.group_single_user = '1'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$group_id = $row['group_id'];
		$db->sql_freeresult($result);
	}

	$change_acl_list = array();
	for($j = 0; $j < sizeof($global_auth_fields); $j++)
	{
		$auth_field = $global_auth_fields[$j];
		$change_acl_list[$auth_field] = $_POST['private_' . $auth_field];

	}

	$sql = ($mode == 'glob_user') ? "SELECT aa.* FROM " . PA_AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = " . TRUE . " AND aa.cat_id = '0'" : "SELECT * FROM " . PA_AUTH_ACCESS_TABLE . " WHERE group_id = $group_id AND cat_id = '0'";
	$result = $db->sql_query($sql);

	$auth_access = '';
	if($row = $db->sql_fetchrow($result))
	{
		$auth_access = $row;
	}
	$db->sql_freeresult($result);

	$global_auth_action = array();
	$update_acl_status = array();

	for($j = 0; $j < sizeof($global_auth_fields); $j++)
	{
		$auth_field = $global_auth_fields[$j];

		if($pafiledb_config[$auth_field] == AUTH_ACL && isset($change_acl_list[$auth_field]))
		{
			if ((!is_moderator($group_id) && (isset($auth_access[$auth_field]) && $change_acl_list[$auth_field] != $auth_access[$auth_field]) || (!isset($auth_access[$cat_id][$auth_field]) && !empty($change_acl_list[$auth_field]))))
			{
				$update_acl_status[$auth_field] = $change_acl_list[$auth_field];

				if (isset($auth_access[$auth_field]) && empty($update_acl_status[$auth_field]) && $global_auth_action != 'insert' && $global_auth_action != 'update')
				{
					$global_auth_action = 'delete';
				}
				elseif (!isset($auth_access[$auth_field]) && !($global_auth_action == 'delete' && empty($update_acl_status[$auth_field])))
				{
					$global_auth_action = 'insert';
				}
				elseif (isset($auth_access[$auth_field]) && !empty($update_acl_status[$auth_field]))
				{
					$global_auth_action = 'update';
				}
			}
			elseif ((!is_moderator($auth_access['group_id']) && (isset($auth_access[$auth_field]) && $change_acl_list[$auth_field] == $auth_access[$auth_field])) && $global_auth_action == 'delete')
			{
				$global_auth_action = 'update';
			}
		}
	}

	// Checks complete, make updates to DB
	$delete_sql = 0;


	if ($global_auth_action == 'delete')
	{
		$delete_sql = 1;
	}
	else
	{
		if ($global_auth_action == 'insert')
		{
			$sql_field = '';
			$sql_value = '';
			while (list($auth_type, $value) = @each($update_acl_status))
			{
				$sql_field .= (($sql_field != '') ? ', ' : '') . $auth_type;
				$sql_value .= (($sql_value != '') ? ', ' : '') . $value;
			}
			$sql = "INSERT INTO " . PA_AUTH_ACCESS_TABLE . " (cat_id, group_id, $sql_field)
						VALUES (0, $group_id, $sql_value)";
		}
		else
		{
			$sql_values = '';
			while (list($auth_type, $value) = @each($update_acl_status))
			{
				$sql_values .= (($sql_values != '') ? ', ' : '') . $auth_type . ' = ' . $value;
			}
				$sql = "UPDATE " . PA_AUTH_ACCESS_TABLE . "
					SET $sql_values
					WHERE group_id = $group_id
					AND cat_id = 0";
		}
		$result = $db->sql_query($sql);
	}


	if ($delete_sql)
	{
		$sql = "DELETE FROM " . PA_AUTH_ACCESS_TABLE . "
			WHERE group_id = $group_id
			AND cat_id = 0";
		$result = $db->sql_query($sql);
	}

	$l_auth_return = ($mode == 'glob_user') ? $lang['Click_return_userauth'] : $lang['Click_return_groupauth'];
	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($l_auth_return, '<a href="' . append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
elseif ((($mode == 'user') && (isset($_POST['username']) || $user_id)) || (($mode == 'group') && $group_id))
{
	if (isset($_POST['username']))
	{
		$this_userdata = get_userdata($_POST['username'], true);
		if (!is_array($this_userdata))
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_user']);
		}
		$user_id = $this_userdata['user_id'];
	}

	// Front end
	$sql = "SELECT u.user_id, u.username, u.user_level, g.group_id, g.group_name, g.group_single_user FROM " . USERS_TABLE . " u, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug WHERE ";
	$sql .= ($mode == 'user') ? "u.user_id = $user_id AND ug.user_id = u.user_id AND g.group_id = ug.group_id" : "g.group_id = $group_id AND ug.group_id = g.group_id AND u.user_id = ug.user_id";
	$result = $db->sql_query($sql);

	$ug_info = array();
	while($row = $db->sql_fetchrow($result))
	{
		$ug_info[] = $row;
	}
	$db->sql_freeresult($result);

	$sql = ($mode == 'user') ? "SELECT aa.*, g.group_single_user FROM " . PA_AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = 1" : "SELECT * FROM " . PA_AUTH_ACCESS_TABLE . " WHERE group_id = $group_id";
	$result = $db->sql_query($sql);

	$auth_access = array();
	$auth_access_count = array();
	while($row = $db->sql_fetchrow($result))
	{
		$auth_access[$row['cat_id']][] = $row;
		$auth_access_count[$row['cat_id']]++;
	}
	$db->sql_freeresult($result);

	$is_admin = ($mode == 'user') ? ((($ug_info[0]['user_level'] == ADMIN) && ($ug_info[0]['user_id'] != ANONYMOUS)) ? 1 : 0) : 0;

	foreach($pafiledb->cat_rowset as $cat_id => $cat_data)
	{
		for($j = 0; $j < sizeof($cat_auth_fields); $j++)
		{
			$key = $cat_auth_fields[$j];
			$value = $cat_data[$key];

			switch($value)
			{
				case AUTH_ALL:
				case AUTH_REG:
					$auth_ug[$cat_id][$key] = 1;
					break;

				case AUTH_ACL:
					$auth_ug[$cat_id][$key] = (!empty($auth_access_count[$cat_id])) ? $pafiledb->auth_check_user(AUTH_ACL, $key, $auth_access[$cat_id], $is_admin) : 0;
					$auth_field_acl[$cat_id][$key] = $auth_ug[$cat_id][$key];
					break;

				case AUTH_MOD:
					$auth_ug[$cat_id][$key] = (!empty($auth_access_count[$cat_id])) ? $pafiledb->auth_check_user(AUTH_MOD, $key, $auth_access[$cat_id], $is_admin) : 0;
					break;

				case AUTH_ADMIN:
					$auth_ug[$cat_id][$key] = $is_admin;
					break;

				default:
					$auth_ug[$cat_id][$key] = 0;
					break;
			}
		}

		// Is user a moderator?
		$auth_ug[$cat_id]['auth_mod'] = (!empty($auth_access_count[$cat_id])) ? $pafiledb->auth_check_user(AUTH_MOD, 'auth_mod', $auth_access[$cat_id], 0) : 0;
	}

	$optionlist_acl_adv = array();
	$optionlist_mod = array();

	foreach($auth_ug as $cat_id => $user_ary)
	{
		for($k = 0; $k < sizeof($cat_auth_fields); $k++)
		{
			$field_name = $cat_auth_fields[$k];
			if($pafiledb->cat_rowset[$cat_id][$field_name] == AUTH_ACL)
			{
				$optionlist_acl_adv[$cat_id][$k] = '<select name="private_' . $field_name . '[' . $cat_id . ']">';
				if(isset($auth_field_acl[$cat_id][$field_name]) && !($is_admin || $user_ary['auth_mod']))
				{
					if(!$auth_field_acl[$cat_id][$field_name])
					{
						$optionlist_acl_adv[$cat_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
					}
					else
					{
						$optionlist_acl_adv[$cat_id][$k] .= '<option value="1" selected="selected">' . $lang['ON'] . '</option><option value="0">' . $lang['OFF'] . '</option>';
					}
				}
				else
				{
					if($is_admin || $user_ary['auth_mod'])
					{
						$optionlist_acl_adv[$cat_id][$k] .= '<option value="1">' . $lang['ON'] . '</option>';
					}
					else
					{
						$optionlist_acl_adv[$cat_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
					}
				}
				$optionlist_acl_adv[$cat_id][$k] .= '</select>';
			}
		}

		$optionlist_mod[$cat_id] = '<select name="moderator[' . $cat_id . ']">';
		$optionlist_mod[$cat_id] .= ($user_ary['auth_mod']) ? '<option value="1" selected="selected">' . $lang['Is_Moderator'] . '</option><option value="0">' . $lang['Not_Moderator'] . '</option>' : '<option value="1">' . $lang['Is_Moderator'] . '</option><option value="0" selected="selected">' . $lang['Not_Moderator'] . '</option>';
		$optionlist_mod[$cat_id] .= '</select>';
	}
	admin_display_category_auth();

	if ($mode == 'user')
	{
		$t_username = $ug_info[0]['username'];
	}
	else
	{
		$t_groupname = $ug_info[0]['group_name'];
	}

	$name = array();
	$id = array();
	for($i = 0; $i < sizeof($ug_info); $i++)
	{
		if(($mode == 'user' && !$ug_info[$i]['group_single_user']) || $mode == 'group')
		{
			$name[] = ($mode == 'user') ? $ug_info[$i]['group_name'] : $ug_info[$i]['username'];
			$id[] = ($mode == 'user') ? intval($ug_info[$i]['group_id']) : intval($ug_info[$i]['user_id']);
		}
	}

	if(sizeof($name))
	{
		$t_usergroup_list = '';
		for($i = 0; $i < sizeof($ug_info); $i++)
		{
			$ug = ($mode == 'user') ? 'group&amp;' . POST_GROUPS_URL : 'user&amp;' . POST_USERS_URL;
			$user_color = ($mode == 'user') ? '' : (' ' . colorize_username($id[$i], '', '', '', false, true));
			$t_usergroup_list .= (($t_usergroup_list != '') ? ', ' : '') . '<a href="' . append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=' . $ug . '=' . $id[$i]) . '"' . $user_color . '>' . $name[$i] . '</a>';
		}
	}
	else
	{
		$t_usergroup_list = $lang['None'];
	}

	for($i = 0; $i < sizeof($cat_auth_fields); $i++)
	{
		$cell_title = $field_names[$cat_auth_fields[$i]];

		$template->assign_block_vars('acltype', array(
			'L_UG_ACL_TYPE' => $cell_title
			)
		);
		$s_column_span++;
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= ($mode == 'user') ? '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />' : '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';


	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ADM_TPL . 'pa_auth_ug_body.tpl'));

	if ($mode == 'user')
	{
		$template->assign_vars(array(
			'USER' => TRUE,
			'USERNAME' => $t_username,
			'USER_LEVEL' => $lang['User_Level'],
			'USER_GROUP_MEMBERSHIPS' => sprintf($lang['Group_memberships'], sizeof($name)) . ': ' . $t_usergroup_list
			)
		);
	}
	else
	{
		$template->assign_vars(array(
			'USER' => FALSE,
			'USERNAME' => $t_groupname,
			'GROUP_MEMBERSHIP' => sprintf($lang['Usergroup_members'], sizeof($name)) . ': ' . $t_usergroup_list
			)
		);
	}

	$template->assign_vars(array(
		'SHOW_MOD' => true,

		'L_USER_OR_GROUPNAME' => ($mode == 'user') ? $lang['Username'] : $lang['Group_name'],

		'L_AUTH_TITLE' => ($mode == 'user') ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
		'L_AUTH_EXPLAIN' => ($mode == 'user') ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
		'L_MODERATOR_STATUS' => $lang['Moderator_status'],
		'L_PERMISSIONS' => $lang['Permissions'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_CAT' => $lang['Category'],

		'U_USER_OR_GROUP' => append_sid('admin_pa_ug_auth.' . PHP_EXT),

		'S_COLUMN_SPAN' => $s_column_span + 2,
		'S_AUTH_ACTION' => append_sid('admin_pa_ug_auth.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
elseif((($mode == 'glob_user') && (isset($_POST['username']) || $user_id)) || (($mode == 'glob_group') && $group_id))
{
	if (isset($_POST['username']))
	{
		$this_userdata = get_userdata($_POST['username'], true);
		if (!is_array($this_userdata))
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_user']);
		}
		$user_id = $this_userdata['user_id'];
	}

	// Front end
	if($mode == 'glob_user')
	{
		$sql = "SELECT g.group_id
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id = $user_id
			AND g.group_id = ug.group_id
			AND g.group_single_user = '1'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$group_id = $row['group_id'];
		$db->sql_freeresult($result);
	}


	$sql = "SELECT u.user_id, u.username, u.user_level, g.group_id, g.group_name, g.group_single_user FROM " . USERS_TABLE . " u, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug WHERE ";
	$sql .= ($mode == 'glob_user') ? "u.user_id = $user_id AND ug.user_id = u.user_id AND g.group_id = ug.group_id" : "g.group_id = $group_id AND ug.group_id = g.group_id AND u.user_id = ug.user_id";
	$result = $db->sql_query($sql);

	$ug_info = array();
	while($row = $db->sql_fetchrow($result))
	{
		$ug_info[] = $row;
	}
	$db->sql_freeresult($result);

	$sql = ($mode == 'glob_user') ? "SELECT aa.*, g.group_single_user FROM " . PA_AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = 1 AND aa.cat_id = '0'" : "SELECT * FROM " . PA_AUTH_ACCESS_TABLE . " WHERE group_id = $group_id AND cat_id = '0'";
	$result = $db->sql_query($sql);
	$auth_access = array();
	$auth_access_count = 0;
	if($row = $db->sql_fetchrow($result))
	{
		$auth_access = $row;
		$auth_access_count++;
	}
	$db->sql_freeresult($result);

	$is_admin = ($mode == 'glob_user') ? ((($ug_info[0]['user_level'] == ADMIN) && ($ug_info[0]['user_id'] != ANONYMOUS)) ? 1 : 0) : 0;

	for($j = 0; $j < sizeof($global_auth_fields); $j++)
	{
		$key = $global_auth_fields[$j];
		$value = $pafiledb_config[$key];

		switch($value)
		{
			case AUTH_ALL:
			case AUTH_REG:
				$auth_ug[$key] = 1;
				break;

			case AUTH_ACL:
				$auth_ug[$key] = (!empty($auth_access_count)) ? global_auth_check_user(AUTH_ACL, $key, $auth_access, $is_admin) : 0;
				$auth_field_acl[$key] = $auth_ug[$key];
				break;

			case AUTH_MOD:
				$auth_ug[$key] = (!empty($auth_access_count)) ? global_auth_check_user(AUTH_MOD, $key, $auth_access, $is_admin) : 0;
				break;

			case AUTH_ADMIN:
				$auth_ug[$key] = $is_admin;
				break;

			default:
				$auth_ug[$key] = 0;
				break;
		}
	}


	for($k = 0; $k < sizeof($global_auth_fields); $k++)
	{
		$field_name = $global_auth_fields[$k];

		if($pafiledb_config[$field_name] == AUTH_ACL)
		{
			$optionlist_acl_adv[$k] = '<select name="private_' . $field_name . '">';

			if(isset($auth_field_acl[$field_name]) && !($is_admin || is_moderator($group_id)))
			{
				if(!$auth_field_acl[$field_name])
				{
					$optionlist_acl_adv[$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
				}
				else
				{
					$optionlist_acl_adv[$k] .= '<option value="1" selected="selected">' . $lang['ON'] . '</option><option value="0">' . $lang['OFF'] . '</option>';
				}
			}
			else
			{
				if($is_admin || is_moderator($group_id))
				{
					$optionlist_acl_adv[$k] .= '<option value="1">' . $lang['ON'] . '</option>';
				}
				else
				{
					$optionlist_acl_adv[$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
				}
			}

			$optionlist_acl_adv[$k] .= '</select>';

		}
	}

	$template->assign_block_vars('cat_row', array(
		'CAT_NAME' => ($mode == 'glob_user') ? $lang['User_Global_Permissions'] : $lang['Group_Global_Permissions'],
		'IS_HIGHER_CAT' => false,
		'PRE' => '',

		'U_CAT' => append_sid('admin_pa_settings.' . PHP_EXT)
		)
	);

	for($j = 0; $j < sizeof($global_auth_fields); $j++)
	{
		$template->assign_block_vars('cat_row.aclvalues', array(
			'S_ACL_SELECT' => $optionlist_acl_adv[$j]
			)
		);
	}

	if ($mode == 'glob_user')
	{
		$t_username = $ug_info[0]['username'];
	}
	else
	{
		$t_groupname = $ug_info[0]['group_name'];
	}

	$name = array();
	$id = array();
	for($i = 0; $i < sizeof($ug_info); $i++)
	{
		if(($mode == 'glob_user' && !$ug_info[$i]['group_single_user']) || $mode == 'glob_group')
		{
			$name[] = ($mode == 'glob_user') ? $ug_info[$i]['group_name'] :  $ug_info[$i]['username'];
			$id[] = ($mode == 'glob_user') ? intval($ug_info[$i]['group_id']) : intval($ug_info[$i]['user_id']);
		}
	}

	if(sizeof($name))
	{
		$t_usergroup_list = '';
		for($i = 0; $i < sizeof($ug_info); $i++)
		{
			$ug = ($mode == 'glob_user') ? 'glob_group&amp;' . POST_GROUPS_URL : 'glob_user&amp;' . POST_USERS_URL;
			$user_color = ($mode == 'glob_user') ? '' : (' ' . colorize_username($id[$i], '', '', '', false, true));
			$t_usergroup_list .= (($t_usergroup_list != '') ? ', ' : '') . '<a href="' . append_sid('admin_pa_ug_auth.' . PHP_EXT . '?mode=' . $ug . '=' . $id[$i]) . '"' . $user_color . '>' . $name[$i] . '</a>';
		}
	}
	else
	{
		$t_usergroup_list = $lang['None'];
	}

	for($i = 0; $i < sizeof($global_auth_fields); $i++)
	{
		$cell_title = $global_fields_names[$global_auth_fields[$i]];

		$template->assign_block_vars('acltype', array(
			'L_UG_ACL_TYPE' => $cell_title)
		);
		$s_column_span++;
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= ($mode == 'glob_user') ? '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />' : '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';


	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ADM_TPL . 'pa_auth_ug_body.tpl'));

	if ($mode == 'glob_user')
	{
		$template->assign_vars(array(
			'USER' => TRUE,
			'USERNAME' => $t_username,
			'USER_LEVEL' => $lang['User_Level'],
			'USER_GROUP_MEMBERSHIPS' => sprintf($lang['Group_memberships'], sizeof($name)) . ': ' . $t_usergroup_list
			)
		);
	}
	else
	{
		$template->assign_vars(array(
			'USER' => FALSE,
			'USERNAME' => $t_groupname,
			'GROUP_MEMBERSHIP' => sprintf($lang['Usergroup_members'], sizeof($name)) . ': ' . $t_usergroup_list
			)
		);
	}

	$template->assign_vars(array(
		'SHOW_MOD' => false,

		'L_USER_OR_GROUPNAME' => ($mode == 'glob_user') ? $lang['Username'] : $lang['Group_name'],

		'L_AUTH_TITLE' => ($mode == 'glob_user') ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
		'L_AUTH_EXPLAIN' => ($mode == 'glob_user') ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
		'L_PERMISSIONS' => $lang['Permissions'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_CAT' => ($mode == 'glob_user') ? $lang['User_Global_Permissions'] : $lang['Group_Global_Permissions'],

		'U_USER_OR_GROUP' => append_sid('admin_pa_ug_auth.' . PHP_EXT),

		'S_COLUMN_SPAN' => $s_column_span + 1,
		'S_AUTH_ACTION' => append_sid('admin_pa_ug_auth.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}
else
{
	// Select a user/group
	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ($mode == 'user' || $mode == 'glob_user') ? ADM_TPL . 'user_select_body.tpl' : ADM_TPL . 'auth_select_body.tpl'));

	if ($mode == 'user' || $mode == 'glob_user')
	{
		$template->assign_vars(array(
			'L_FIND_USERNAME' => $lang['Find_username'],

			'U_SEARCH_USER' => append_sid('../' . CMS_PAGE_SEARCH . '?mode=searchuser')
			)
		);
	}
	else
	{
		$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE;
		$result = $db->sql_query($sql);

		if ($row = $db->sql_fetchrow($result))
		{
			$select_list = '<select name="' . POST_GROUPS_URL . '">';
			do
			{
				$select_list .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
			}
			while ($row = $db->sql_fetchrow($result));
			$select_list .= '</select>';
		}

		$template->assign_vars(array(
			'S_AUTH_SELECT' => $select_list
			)
		);
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';

	$l_type = ($mode == 'user' || $mode == 'glob_user') ? 'USER' : 'AUTH';

	$template->assign_vars(array(
		'L_' . $l_type . '_TITLE' => ($mode == 'user' || $mode == 'glob_user') ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
		'L_' . $l_type . '_EXPLAIN' => ($mode == 'user' || $mode == 'glob_user') ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
		'L_' . $l_type . '_SELECT' => ($mode == 'user' || $mode == 'glob_user') ? $lang['Select_a_User'] : $lang['Select_a_Group'],
		'L_LOOK_UP' => ($mode == 'user' || $mode == 'glob_user') ? $lang['Look_up_User'] : $lang['Look_up_Group'],
		// Start add - Admin add user MOD
		'L_CREATE_USER' => '',
		// End add - Admin add user MOD

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_' . $l_type . '_ACTION' => append_sid('admin_pa_ug_auth.' . PHP_EXT)
		)
	);
}


$template->display('body');

$pafiledb->_pafiledb();

include('./page_footer_admin.' . PHP_EXT);

?>