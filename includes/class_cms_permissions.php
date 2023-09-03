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

class cms_permissions
{
	var $u_action;
	var $permission_dropdown;
	var $id_type;

	/**
	* Construct
	*/
	function __construct()
	{

	}

	function main($id, $mode)
	{
		global $db, $cache, $config, $user, $lang, $auth, $template, $cms_admin;

		add_permission_language();

		include_once(IP_ROOT_PATH . 'includes/class_auth_admin.' . PHP_EXT);
		$auth_admin = new auth_admin();

		// Set some vars
		$action = request_var('paction', array('' => 0));
		$action = key($action);
		$action = (isset($_POST['psubmit'])) ? 'apply_permissions' : $action;

		$all_forums = request_var('all_forums', 0);
		$subforum_id = request_var('subforum_id', 0);
		$forum_id = request_var('forum_id', array(0));

		$id_type = request_var('id_type', '');
		$id_types_array = array('forum', 'block', 'layout', 'layout_special');
		$id_type = in_array($id_type, $id_types_array) ? $id_type : $id_types_array[0];
		$this->id_type = $id_type;

		$username = request_var('username', array(''), true);
		$usernames = request_var('usernames', '', true);
		$user_id = request_var('user_id', array(0));

		$group_id = request_var('group_id', array(0));
		$select_all_groups = request_var('select_all_groups', 0);

		$form_name = 'acp_permissions';
		add_form_key($form_name);

		$this->tpl_name = 'cms_permissions.tpl';
		$this->u_action = append_sid($cms_admin->root . '?mode=auth&amp;pmode=' . $mode);

		/*
		// Trace has other vars
		if ($mode == 'trace')
		{
			$user_id = request_var('u', 0);
			$forum_id = request_var('f', 0);
			$permission = request_var('auth', '');

			$this->tpl_name = 'cms_permission_trace.tpl';

			if ($user_id && isset($auth_admin->acl_options['id'][$permission]))
			{
				$this->page_title = sprintf($user->lang['TRACE_PERMISSION'], $user->lang['acl_' . $permission]['lang']);
				$this->permission_trace($user_id, $forum_id, $permission);
				return;
			}
			trigger_error('NO_MODE', E_USER_ERROR);
		}
		*/

		// If select all groups is set, we pre-build the group id array (this option is used for other screens to link to the permission settings screen)
		if ($select_all_groups)
		{
			$sql = 'SELECT group_id
				FROM ' . GROUPS_TABLE . '
				WHERE group_single_user = 0';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$group_id[] = $row['group_id'];
			}
			$db->sql_freeresult($result);
		}

		// Map usernames to ids and vice versa
		if ($usernames)
		{
			$username = explode("\n", $usernames);
		}
		unset($usernames);

		if (sizeof($username) && !sizeof($user_id))
		{
			user_get_id_name($user_id, $username);

			if (!sizeof($user_id))
			{
				trigger_error($user->lang['SELECTED_USER_NOT_EXIST'] . page_back_link($this->u_action), E_USER_WARNING);
			}
		}
		unset($username);

		// Define some common variables for every mode
		$error = array();

		$permission_scope = (strpos($mode, '_global') !== false) ? 'global' : 'local';

		// Showing introductionary page?
		if ($mode == 'intro')
		{
			$this->page_title = 'CMS_PERMISSIONS';
			$template->assign_vars(array(
				'S_INTRO' => true
				)
			);
			return;
		}

		switch ($mode)
		{
			case 'setting_admin_global':
			case 'setting_mod_global':
				$this->permission_dropdown = (strpos($mode, '_admin_') !== false) ? array('a_') : array('m_');
				$permission_victim = array('usergroup');
				$this->page_title = ($mode == 'setting_admin_global') ? 'CMS_PERMISSIONS_ADMINS' : 'CMS_PERMISSIONS_MODS';
			break;

			case 'setting_cms_user_global':
			case 'setting_cms_group_global':
				$this->permission_dropdown = array('cms_', 'pl_', 'u_');
				$permission_victim = array('usergroup');
				//$permission_victim = ($mode == 'setting_cms_user_global') ? array('user') : array('group');
				$this->page_title = ($mode == 'setting_cms_user_global') ? 'CMS_PERMISSIONS_CMS_USERS' : 'CMS_PERMISSIONS_CMS_GROUPS';
			break;

			case 'setting_cms_user_local':
			case 'setting_cms_group_local':
				$this->permission_dropdown = ($this->id_type == 'layout') ? array('cmsl_') : (($this->id_type == 'layout_special') ? array('cmss_') : array('cmsb_'));
				$permission_victim = array('cms', 'usergroup');
				//$permission_victim = ($mode == 'setting_cms_user_local') ? array('user') : array('group');
				$this->page_title = ($mode == 'setting_cms_user_local') ? 'CMS_PERMISSIONS_CMS_USERS' : 'CMS_PERMISSIONS_CMS_GROUPS';
			break;

			case 'setting_plugins_user_global':
			case 'setting_plugins_group_global':
				$this->permission_dropdown = array('pl_', 'cms_', 'u_');
				$permission_victim = array('usergroup');
				//$permission_victim = ($mode == 'setting_plugins_user_global') ? array('user') : array('group');
				$this->page_title = ($mode == 'setting_plugins_user_global') ? 'CMS_PERMISSIONS_PLUGINS_USERS' : 'CMS_PERMISSIONS_PLUGINS_GROUPS';
			break;

			case 'setting_user_global':
			case 'setting_group_global':
				$this->permission_dropdown = array('u_', 'cms_', 'pl_');
				$permission_victim = ($mode == 'setting_user_global') ? array('user') : array('group');
				$this->page_title = ($mode == 'setting_user_global') ? 'CMS_PERMISSIONS_USERS' : 'CMS_PERMISSIONS_GROUPS';
			break;

			case 'setting_user_local':
			case 'setting_group_local':
				$this->permission_dropdown = array('f_', 'm_');
				$permission_victim = ($mode == 'setting_user_local') ? array('user') : array('group');
				$this->page_title = ($mode == 'setting_user_local') ? 'ACP_USERS_FORUM_PERMISSIONS' : 'ACP_GROUPS_FORUM_PERMISSIONS';
			break;

			case 'setting_mod_local':
			case 'setting_forum_local':
				$this->permission_dropdown = ($mode == 'setting_mod_local') ? array('m_') : array('f_');
				$permission_victim = array('forums', 'usergroup');
				$this->page_title = ($mode == 'setting_mod_local') ? 'ACP_FORUM_MODERATORS' : 'ACP_FORUM_PERMISSIONS';
			break;

			case 'view_admin_global':
			case 'view_user_global':
			case 'view_mod_global':
				$this->permission_dropdown = ($mode == 'view_admin_global') ? array('a_') : (($mode == 'view_user_global') ? array('u_') : array('m_'));
				$permission_victim = array('usergroup_view');
				$this->page_title = ($mode == 'view_admin_global') ? 'CMS_PERMISSIONS_ADMINS' : (($mode == 'view_user_global') ? 'CMS_PERMISSIONS_USERS' : 'CMS_PERMISSIONS_MODS');
			break;

			case 'view_mod_local':
			case 'view_forum_local':
				$this->permission_dropdown = ($mode == 'view_mod_local') ? array('m_') : array('f_');
				$permission_victim = array('usergroup_view');
				$this->page_title = ($mode == 'view_mod_local') ? 'ACP_VIEW_FORUM_MOD_PERMISSIONS' : 'ACP_VIEW_FORUM_PERMISSIONS';
			break;

			default:
				trigger_error('NO_MODE', E_USER_ERROR);
			break;
		}

		$template->assign_vars(array(
			'L_TITLE' => $lang[$this->page_title],
			'L_EXPLAIN' => $lang[$this->page_title . '_EXPLAIN']
			)
		);

		// Get permission type
		$permission_type = request_var('type', $this->permission_dropdown[0]);

		if (!in_array($permission_type, $this->permission_dropdown))
		{
			trigger_error($user->lang['WRONG_PERMISSION_TYPE'] . page_back_link($this->u_action), E_USER_WARNING);
		}

		// Handle actions
		if ((strpos($mode, 'setting_') === 0) && $action)
		{
			switch ($action)
			{
				case 'delete':
					if (confirm_box(true))
					{
						// All users/groups selected?
						$all_users = (isset($_POST['all_users'])) ? true : false;
						$all_groups = (isset($_POST['all_groups'])) ? true : false;

						if ($all_users || $all_groups)
						{
							$items = $this->retrieve_defined_user_groups($permission_scope, $forum_id, $permission_type);

							if ($all_users && sizeof($items['user_ids']))
							{
								$user_id = $items['user_ids'];
							}
							elseif ($all_groups && sizeof($items['group_ids']))
							{
								$group_id = $items['group_ids'];
							}
						}

						if (sizeof($user_id) || sizeof($group_id))
						{
							$this->remove_permissions($mode, $permission_type, $auth_admin, $user_id, $group_id, $forum_id);
						}
						else
						{
							trigger_error($user->lang['NO_USER_GROUP_SELECTED'] . page_back_link($this->u_action), E_USER_WARNING);
						}
					}
					else
					{
						if (isset($_POST['cancel']))
						{
							$u_redirect = $this->u_action . '&amp;type=' . $permission_type;
							foreach ($forum_id as $fid)
							{
								$u_redirect .= '&amp;forum_id[]=' . $fid;
							}
							redirect($u_redirect);
						}

						$s_hidden_fields = array(
							'i' => $id,
							'pmode' => $mode,
							'paction' => array($action => 1),
							'user_id' => $user_id,
							'group_id' => $group_id,
							'forum_id' => $forum_id,
							'id_type' => $this->id_type,
							'type' => $permission_type,
						);
						if (isset($_POST['all_users']))
						{
							$s_hidden_fields['all_users'] = 1;
						}
						if (isset($_POST['all_groups']))
						{
							$s_hidden_fields['all_groups'] = 1;
						}
						confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields($s_hidden_fields));
					}
				break;

				case 'apply_permissions':
					if (!isset($_POST['setting']))
					{
						trigger_error($user->lang['NO_AUTH_SETTING_FOUND'] . page_back_link($this->u_action), E_USER_WARNING);
					}
					if (!check_form_key($form_name))
					{
						trigger_error($user->lang['FORM_INVALID'] . page_back_link($this->u_action), E_USER_WARNING);
					}

					$this->set_permissions($mode, $permission_type, $auth_admin, $user_id, $group_id);
				break;

				case 'apply_all_permissions':
					if (!isset($_POST['setting']))
					{
						trigger_error($user->lang['NO_AUTH_SETTING_FOUND'] . page_back_link($this->u_action), E_USER_WARNING);
					}
					if (!check_form_key($form_name))
					{
						trigger_error($user->lang['FORM_INVALID'] . page_back_link($this->u_action), E_USER_WARNING);
					}

					$this->set_all_permissions($mode, $permission_type, $auth_admin, $user_id, $group_id);
				break;
			}
		}

		// Setting permissions screen
		$s_hidden_fields_array = array(
			'user_id' => $user_id,
			'group_id' => $group_id,
			'forum_id' => $forum_id,
		);

		$s_hidden_fields = build_hidden_fields($s_hidden_fields_array);

		$s_hidden_fields_extra = true;

		// Go through the screens/options needed and present them in correct order
		foreach ($permission_victim as $victim)
		{
			switch ($victim)
			{
				case 'cms':

					if (sizeof($forum_id))
					{
						$this->check_existence($this->id_type, $forum_id);
						continue 2;
					}

					// We don't have anything selected, so we need to unset these types, because they will be set properly in template...
					$s_hidden_fields_extra = false;
					unset($s_hidden_fields_array['id_type']);
					unset($s_hidden_fields_array['type']);

					$ids = array();
					$ids_data = array();
					$types_array = array(
						'l' => 'layout',
						's' => 'layout_special',
						'b' => 'block'
					);
					foreach ($types_array as $type_key => $type_value)
					{
						$s_options = '';
						$ids_data = $this->check_existence($type_value, $ids, true);
						foreach ($ids_data as $id_data)
						{
							$option_name = $id_data['name'];
							if ($type_key == 's')
							{
								$option_name = isset($lang['auth_view_' . strtolower($option_name)]) ? $lang['auth_view_' . strtolower($option_name)] : (isset($lang['cms_page_name_' . strtolower($option_name)]) ? $lang['cms_page_name_' . strtolower($option_name)] : ucfirst($option_name));
							}

							$s_options .= '<option value="' . (int) $id_data['id'] . '">' . $option_name . ' [' . (int) $id_data['id'] . ']' . '</option>';
						}

						$template->assign_vars(array(
							'S_CMS_' . strtoupper($type_key) . '_OPTIONS' => $s_options
							)
						);

					}

					$template->assign_vars(array(
						'S_SELECT_CMS' => true,
						)
					);

				break;

				case 'user':
				case 'group':

					if (sizeof($user_id))
					{
						$this->check_existence('user', $user_id);
						continue 2;
					}

					if (sizeof($group_id))
					{
						$this->check_existence('group', $group_id);
						continue 2;
					}

					$template->assign_vars(array(
						'S_SELECT_USER' => true,
						'S_SELECT_GROUP' => true,
						'U_FIND_USERNAME' => append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser'),
						'S_GROUP_OPTIONS' => group_select_options(false, false, false), // Show all groups
						)
					);

				break;

				case 'usergroup':
				case 'usergroup_view':

					$all_users = (isset($_POST['all_users'])) ? true : false;
					$all_groups = (isset($_POST['all_groups'])) ? true : false;

					if ((sizeof($user_id) && !$all_users) || (sizeof($group_id) && !$all_groups))
					{
						if (sizeof($user_id))
						{
							$this->check_existence('user', $user_id);
						}

						if (sizeof($group_id))
						{
							$this->check_existence('group', $group_id);
						}

						continue 2;
					}

					// Now we check the users... because the "all"-selection is different here (all defined users/groups)
					$items = $this->retrieve_defined_user_groups($permission_scope, $forum_id, $permission_type);

					if ($all_users && sizeof($items['user_ids']))
					{
						$user_id = $items['user_ids'];
						continue 2;
					}

					if ($all_groups && sizeof($items['group_ids']))
					{
						$group_id = $items['group_ids'];
						continue 2;
					}

					$template->assign_vars(array(
						'S_SELECT_USERGROUP' => ($victim == 'usergroup') ? true : false,
						'S_SELECT_USERGROUP_VIEW' => ($victim == 'usergroup_view') ? true : false,
						'S_DEFINED_USER_OPTIONS' => $items['user_ids_options'],
						'S_DEFINED_GROUP_OPTIONS' => $items['group_ids_options'],
						'S_ADD_GROUP_OPTIONS' => group_select_options(false, $items['group_ids'], false), // Show all groups
						'U_FIND_USERNAME' => append_sid(IP_ROOT_PATH . CMS_PAGE_SEARCH . '?mode=searchuser'),
						)
					);

				break;
			}

			// Let the item names being displayed
			if (sizeof($forum_id))
			{
				$ids_data = $this->check_existence($this->id_type, $forum_id);

				$forum_names = array();
				foreach ($ids_data as $id_data)
				{
					$forum_names[] = $id_data['name'];
				}

				$template->assign_vars(array(
					'S_FORUM_NAMES' => (sizeof($forum_names)) ? true : false,
					'FORUM_NAMES' => implode(', ', $forum_names)
					)
				);
			}

			if (!empty($s_hidden_fields_extra))
			{
				// Setting other hidden fields
				$s_hidden_fields_array = array(
					'id_type' => $this->id_type,
					'type' => $permission_type
				);
				$s_hidden_fields .= build_hidden_fields($s_hidden_fields_array);
			}

			// The S_ALLOW_SELECT parameter below is a measure to lower memory usage.
			// If there are more than 5 forums selected the admin is not able to select all users/groups too.
			// We need to see if the number of forums can be increased or need to be decreased.

			$template->assign_vars(array(
				'U_ACTION' => $this->u_action,
				'U_ACTION_USERS' => str_replace('_group_global', '_user_global', $this->u_action),
				'U_ACTION_GROUPS' => str_replace('_user_global', '_group_global', $this->u_action),
				'ANONYMOUS_USER_ID' => ANONYMOUS,

				'S_SELECT_VICTIM' => true,
				'S_ALLOW_ALL_SELECT' => false,
				/*
				'S_CAN_SELECT_USER' => ($auth->acl_get('a_authusers')) ? true : false,
				'S_CAN_SELECT_GROUP' => ($auth->acl_get('a_authgroups')) ? true : false,
				*/
				'S_CAN_SELECT_USER' => true,
				'S_CAN_SELECT_GROUP' => true,

				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			return;
		}

		$template->assign_vars(array(
			'S_PERMISSION_DROPDOWN' => (sizeof($this->permission_dropdown) > 1) ? $this->build_permission_dropdown($this->permission_dropdown, $permission_type, $permission_scope) : false,
			'L_PERMISSION_TYPE' => $user->lang['ACL_TYPE_' . strtoupper($permission_type)],

			'U_ACTION' => $this->u_action,
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		if (strpos($mode, 'setting_') === 0)
		{
			$template->assign_vars(array(
				'S_SETTING_PERMISSIONS' => true
				)
			);

			$hold_ary = $auth_admin->get_mask('set', (sizeof($user_id)) ? $user_id : false, (sizeof($group_id)) ? $group_id : false, (sizeof($forum_id)) ? $forum_id : false, $permission_type, $permission_scope, ACL_NO);
			$auth_admin->display_mask('set', $permission_type, $hold_ary, ((sizeof($user_id)) ? 'user' : 'group'), (($permission_scope == 'local') ? true : false));
		}
		else
		{
			$template->assign_vars(array(
				'S_VIEWING_PERMISSIONS' => true
				)
			);

			$hold_ary = $auth_admin->get_mask('view', (sizeof($user_id)) ? $user_id : false, (sizeof($group_id)) ? $group_id : false, (sizeof($forum_id)) ? $forum_id : false, $permission_type, $permission_scope, ACL_NEVER);
			$auth_admin->display_mask('view', $permission_type, $hold_ary, ((sizeof($user_id)) ? 'user' : 'group'), (($permission_scope == 'local') ? true : false));
		}
	}

	/**
	* Build dropdown field for changing permission types
	*/
	function build_permission_dropdown($options, $default_option, $permission_scope)
	{
		global $user, $auth;

		$s_dropdown_options = '';
		foreach ($options as $setting)
		{
			// Mighty Gorgon: we don't check permissions yet...
			/*
			if (!$auth->acl_get('a_' . str_replace('_', '', $setting) . 'auth'))
			{
				continue;
			}
			*/

			$selected = ($setting == $default_option) ? ' selected="selected"' : '';
			$l_setting = (isset($user->lang['permission_type'][$permission_scope][$setting])) ? $user->lang['permission_type'][$permission_scope][$setting] : $user->lang['permission_type'][$setting];
			$s_dropdown_options .= '<option value="' . $setting . '"' . $selected . '>' . $l_setting . '</option>';
		}

		return $s_dropdown_options;
	}

	/**
	* Check if selected items exist. Remove not found ids and if empty return error.
	*/
	function check_existence($mode, &$ids, $all_ids = false)
	{
		global $db, $user;

		switch ($mode)
		{
			case 'user':
				$table = USERS_TABLE;
				$sql_select = array(
					'id' => 'user_id',
					'name' => 'username'
				);
				$sql_where = '';
			break;

			case 'group':
				$table = GROUPS_TABLE;
				$sql_select = array(
					'id' => 'group_id',
					'name' => 'group_name'
				);
				$sql_where = ' group_single_user = 0 ';
			break;

			case 'layout':
				$table = CMS_LAYOUT_TABLE;
				$sql_select = array(
					'id' => 'lid',
					'name' => 'name'
				);
				$sql_where = '';
			break;

			case 'layout_special':
				$table = CMS_LAYOUT_SPECIAL_TABLE;
				$sql_select = array(
					'id' => 'lsid',
					'name' => 'name'
				);
				$sql_where = '';
			break;

			case 'block':
				$table = CMS_BLOCKS_TABLE;
				$sql_select = array(
					'id' => 'bid',
					'name' => 'title'
				);
				$sql_where = '';
			break;

			case 'forum':
				$table = FORUMS_TABLE;
				$sql_select = array(
					'id' => 'forum_id',
					'name' => 'forum_name'
				);
				$sql_where = '';
			break;
		}

		if (!empty($all_ids) || sizeof($ids))
		{
			if (!empty($all_ids))
			{
				$sql_where = !empty($sql_where) ? (" WHERE " . $sql_where) : "";
			}
			else
			{
				$sql_where = " WHERE " . $db->sql_in_set($sql_select['id'], $ids) . " " . (!empty($sql_where) ? (" AND " . $sql_where) : "");
			}

			$sql = "SELECT " . $sql_select['id'] . ", " . $sql_select['name'] . "
				FROM " . $table . "
				" . $sql_where . "
				ORDER BY LOWER(" . $sql_select['name'] . ") ASC";
			$result = $db->sql_query($sql);

			$ids = array();
			$ids_data = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$ids[] = (int) $row[$sql_select['id']];
				$ids_data[] = array('id' => (int) $row[$sql_select['id']], 'name' => $row[$sql_select['name']]);
			}
			$db->sql_freeresult($result);
		}

		if (!sizeof($ids))
		{
			trigger_error($user->lang['SELECTED_' . strtoupper($mode) . '_NOT_EXIST'] . page_back_link($this->u_action), E_USER_WARNING);
		}

		return $ids_data;
	}

	/**
	* Apply permissions
	*/
	function set_permissions($mode, $permission_type, &$auth_admin, &$user_id, &$group_id)
	{
		global $user, $auth;

		$psubmit = request_var('psubmit', array(0 => array(0 => 0)));

		// User or group to be set?
		$ug_type = (sizeof($user_id)) ? 'user' : 'group';

		// Mighty Gorgon: we need to decide if we wish to leave this check upon specific permissions in settings permissions
		/*
		// Check the permission setting again
		if (!$auth->acl_get('a_' . str_replace('_', '', $permission_type) . 'auth') || !$auth->acl_get('a_auth' . $ug_type . 's'))
		{
			trigger_error($user->lang['NO_AUTH_OPERATION'] . page_back_link($this->u_action), E_USER_WARNING);
		}
		*/

		$ug_id = $forum_id = 0;

		// We loop through the auth settings defined in our submit
		/*
		list($ug_id, ) = each($psubmit);
		list($forum_id, ) = each($psubmit[$ug_id]);
		*/
		$ug_id = key($psubmit);
		$forum_id = key($psubmit[$ug_id]);

		if (empty($_POST['setting']) || empty($_POST['setting'][$ug_id]) || empty($_POST['setting'][$ug_id][$forum_id]) || !is_array($_POST['setting'][$ug_id][$forum_id]))
		{
			trigger_error('WRONG_PERMISSION_SETTING_FORMAT' . page_back_link($this->u_action), E_USER_WARNING);
		}

		// We obtain and check $_POST['setting'][$ug_id][$forum_id] directly and not using request_var() because request_var()
		// currently does not support the amount of dimensions required. ;)
		//		$auth_settings = request_var('setting', array(0 => array(0 => array('' => 0))));
		$auth_settings = array_map('intval', $_POST['setting'][$ug_id][$forum_id]);

		// Do we have a role we want to set?
		$assigned_role = (isset($_POST['role'][$ug_id][$forum_id])) ? (int) $_POST['role'][$ug_id][$forum_id] : 0;

		// Do the admin want to set these permissions to other items too?
		$inherit = request_var('inherit', array(0 => array(0)));

		$ug_id = array($ug_id);
		$forum_id = array($forum_id);

		if (sizeof($inherit))
		{
			foreach ($inherit as $_ug_id => $forum_id_ary)
			{
				// Inherit users/groups?
				if (!in_array($_ug_id, $ug_id))
				{
					$ug_id[] = $_ug_id;
				}

				// Inherit forums?
				$forum_id = array_merge($forum_id, array_keys($forum_id_ary));
			}
		}

		$forum_id = array_unique($forum_id);

		// If the auth settings differ from the assigned role, then do not set a role...
		if ($assigned_role)
		{
			if (!$this->check_assigned_role($assigned_role, $auth_settings))
			{
				$assigned_role = 0;
			}
		}

		// Update the permission set...
		$auth_admin->acl_set($ug_type, $forum_id, $ug_id, $auth_settings, $assigned_role);

		// Do we need to recache the moderator lists?
		if ($permission_type == 'm_')
		{
			cache_moderators();
		}

		// Do we need to recache the moderator lists?
		if ($permission_type == 'm_')
		{
			cache_moderators();
		}

		// Remove users who are now moderators or admins from everyones foes list
		if (($permission_type == 'm_') || ($permission_type == 'a_'))
		{
			// MIGHTY GORGON: We don't use foes...
			//update_foes($group_id, $user_id);
		}

		$this->log_action($mode, 'add', $permission_type, $ug_type, $ug_id, $forum_id);

		$redirect_url = $this->u_action;
		meta_refresh(3, $redirect_url);

		trigger_error($user->lang['AUTH_UPDATED'] . page_back_link($this->u_action));
	}

	/**
	* Apply all permissions
	*/
	function set_all_permissions($mode, $permission_type, &$auth_admin, &$user_id, &$group_id)
	{
		global $user, $auth;

		// User or group to be set?
		$ug_type = (sizeof($user_id)) ? 'user' : 'group';

		// Mighty Gorgon: we need to decide if we wish to leave this check upon specific permissions in settings permissions
		/*
		// Check the permission setting again
		if (!$auth->acl_get('a_' . str_replace('_', '', $permission_type) . 'auth') || !$auth->acl_get('a_auth' . $ug_type . 's'))
		{
			trigger_error($user->lang['NO_AUTH_OPERATION'] . page_back_link($this->u_action), E_USER_WARNING);
		}
		*/

		$auth_settings = (isset($_POST['setting'])) ? $_POST['setting'] : array();
		$auth_roles = (isset($_POST['role'])) ? $_POST['role'] : array();
		$ug_ids = $forum_ids = array();

		// We need to go through the auth settings
		foreach ($auth_settings as $ug_id => $forum_auth_row)
		{
			$ug_id = (int) $ug_id;
			$ug_ids[] = $ug_id;

			foreach ($forum_auth_row as $forum_id => $auth_options)
			{
				$forum_id = (int) $forum_id;
				$forum_ids[] = $forum_id;

				// Check role...
				$assigned_role = (isset($auth_roles[$ug_id][$forum_id])) ? (int) $auth_roles[$ug_id][$forum_id] : 0;

				// If the auth settings differ from the assigned role, then do not set a role...
				if ($assigned_role)
				{
					if (!$this->check_assigned_role($assigned_role, $auth_options))
					{
						$assigned_role = 0;
					}
				}

				// Update the permission set...
				$auth_admin->acl_set($ug_type, $forum_id, $ug_id, $auth_options, $assigned_role, false);
			}
		}

		$auth_admin->acl_clear_prefetch();

		// Do we need to recache the moderator lists?
		if ($permission_type == 'm_')
		{
			cache_moderators();
		}

		$this->log_action($mode, 'add', $permission_type, $ug_type, $ug_ids, $forum_ids);

		$redirect_url = $this->u_action;
		meta_refresh(3, $redirect_url);

		if (($mode == 'setting_forum_local') || ($mode == 'setting_mod_local'))
		{
			trigger_error($user->lang['AUTH_UPDATED'] . page_back_link($this->u_action . '&amp;forum_id[]=' . implode('&amp;forum_id[]=', $forum_ids)));
		}
		else
		{
			trigger_error($user->lang['AUTH_UPDATED'] . page_back_link($this->u_action));
		}
	}

	/**
	* Compare auth settings with auth settings from role
	* returns false if they differ, true if they are equal
	*/
	function check_assigned_role($role_id, &$auth_settings)
	{
		global $db;

		$sql = 'SELECT o.auth_option, r.auth_setting
			FROM ' . ACL_OPTIONS_TABLE . ' o, ' . ACL_ROLES_DATA_TABLE . ' r
			WHERE o.auth_option_id = r.auth_option_id
				AND r.role_id = ' . $role_id;
		$result = $db->sql_query($sql);

		$test_auth_settings = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$test_auth_settings[$row['auth_option']] = $row['auth_setting'];
		}
		$db->sql_freeresult($result);

		// We need to add any ACL_NO setting from auth_settings to compare correctly
		foreach ($auth_settings as $option => $setting)
		{
			if ($setting == ACL_NO)
			{
				$test_auth_settings[$option] = $setting;
			}
		}

		if (sizeof(array_diff_assoc($auth_settings, $test_auth_settings)))
		{
			return false;
		}

		return true;
	}

	/**
	* Remove permissions
	*/
	function remove_permissions($mode, $permission_type, &$auth_admin, &$user_id, &$group_id, &$forum_id)
	{
		global $db, $user, $auth;

		// User or group to be set?
		$ug_type = (sizeof($user_id)) ? 'user' : 'group';

		// Check the permission setting again
		if (!$auth->acl_get('a_' . str_replace('_', '', $permission_type) . 'auth') || !$auth->acl_get('a_auth' . $ug_type . 's'))
		{
			trigger_error($user->lang['NO_AUTH_OPERATION'] . page_back_link($this->u_action), E_USER_WARNING);
		}

		$auth_admin->acl_delete($ug_type, (($ug_type == 'user') ? $user_id : $group_id), (sizeof($forum_id) ? $forum_id : false), $permission_type);

		// Do we need to recache the moderator lists?
		if ($permission_type == 'm_')
		{
			cache_moderators();
		}

		$this->log_action($mode, 'del', $permission_type, $ug_type, (($ug_type == 'user') ? $user_id : $group_id), (sizeof($forum_id) ? $forum_id : array(0 => 0)));

		if (($mode == 'setting_forum_local') || ($mode == 'setting_mod_local'))
		{
			trigger_error($user->lang['AUTH_UPDATED'] . page_back_link($this->u_action . '&amp;forum_id[]=' . implode('&amp;forum_id[]=', $forum_id)));
		}
		else
		{
			trigger_error($user->lang['AUTH_UPDATED'] . page_back_link($this->u_action));
		}
	}

	/**
	* Log permission changes
	*/
	function log_action($mode, $action, $permission_type, $ug_type, $ug_id, $forum_id)
	{
		global $db, $user;

		if (!is_array($ug_id))
		{
			$ug_id = array($ug_id);
		}

		if (!is_array($forum_id))
		{
			$forum_id = array($forum_id);
		}

		// Logging ... first grab user or groupnames ...
		$sql = ($ug_type == 'group') ? 'SELECT group_name as name, group_type FROM ' . GROUPS_TABLE . ' WHERE ' : 'SELECT username as name FROM ' . USERS_TABLE . ' WHERE ';
		$sql .= $db->sql_in_set(($ug_type == 'group') ? 'group_id' : 'user_id', array_map('intval', $ug_id));
		$sql .= ($ug_type == 'group') ? ' AND group_single_user = 0' : '';
		$result = $db->sql_query($sql);

		$l_ug_list = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$l_ug_list .= (($l_ug_list != '') ? ', ' : '') . ((isset($row['group_type']) && $row['group_type'] == GROUP_SPECIAL) ? '<span class="sep">' . $user->lang['G_' . $row['name']] . '</span>' : $row['name']);
		}
		$db->sql_freeresult($result);

		$mode = str_replace('setting_', '', $mode);

		add_log('admin', 'LOG_ACL_' . strtoupper($action) . '_' . strtoupper($mode) . '_' . strtoupper($permission_type), $l_ug_list);
	}

	/**
	* Get already assigned users/groups
	*/
	function retrieve_defined_user_groups($permission_scope, $forum_id, $permission_type)
	{
		global $db, $user;

		$sql_forum_id = ($permission_scope == 'global') ? 'AND a.forum_id = 0' : ((sizeof($forum_id)) ? 'AND ' . $db->sql_in_set('a.forum_id', $forum_id) : 'AND a.forum_id <> 0');

		// Permission options are only able to be a permission set... therefore we will pre-fetch the possible options and also the possible roles
		$option_ids = $role_ids = array();

		$sql = 'SELECT auth_option_id
			FROM ' . ACL_OPTIONS_TABLE . '
			WHERE auth_option ' . $db->sql_like_expression($permission_type . $db->any_char);
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$option_ids[] = (int) $row['auth_option_id'];
		}
		$db->sql_freeresult($result);

		if (sizeof($option_ids))
		{
			$sql = 'SELECT DISTINCT role_id
				FROM ' . ACL_ROLES_DATA_TABLE . '
				WHERE ' . $db->sql_in_set('auth_option_id', $option_ids);
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$role_ids[] = (int) $row['role_id'];
			}
			$db->sql_freeresult($result);
		}

		if (sizeof($option_ids) && sizeof($role_ids))
		{
			$sql_where = 'AND (' . $db->sql_in_set('a.auth_option_id', $option_ids) . ' OR ' . $db->sql_in_set('a.auth_role_id', $role_ids) . ')';
		}
		elseif (sizeof($role_ids))
		{
			$sql_where = 'AND ' . $db->sql_in_set('a.auth_role_id', $role_ids);
		}
		elseif (sizeof($option_ids))
		{
			$sql_where = 'AND ' . $db->sql_in_set('a.auth_option_id', $option_ids);
		}

		// Not ideal, due to the filesort, non-use of indexes, etc.
		$sql = 'SELECT DISTINCT u.user_id, u.username, u.username_clean, u.user_regdate
			FROM ' . USERS_TABLE . ' u, ' . ACL_USERS_TABLE . " a
			WHERE u.user_id = a.user_id
				$sql_forum_id
				$sql_where
			ORDER BY u.username_clean, u.user_regdate ASC";
		$result = $db->sql_query($sql);

		$s_defined_user_options = '';
		$defined_user_ids = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$s_defined_user_options .= '<option value="' . $row['user_id'] . '">' . $row['username'] . '</option>';
			$defined_user_ids[] = $row['user_id'];
		}
		$db->sql_freeresult($result);

		$sql = 'SELECT DISTINCT g.group_type, g.group_name, g.group_id
			FROM ' . GROUPS_TABLE . ' g, ' . ACL_GROUPS_TABLE . " a
			WHERE g.group_id = a.group_id
				AND g.group_single_user = 0
				$sql_forum_id
				$sql_where
			ORDER BY g.group_name ASC";
		$result = $db->sql_query($sql);

		$s_defined_group_options = '';
		$defined_group_ids = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$s_defined_group_options .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
			$defined_group_ids[] = $row['group_id'];
		}
		$db->sql_freeresult($result);

		return array(
			'group_ids' => $defined_group_ids,
			'group_ids_options' => $s_defined_group_options,
			'user_ids' => $defined_user_ids,
			'user_ids_options' => $s_defined_user_options
		);
	}

}

?>