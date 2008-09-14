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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function kb_auth($type, $cat_id, $userdata, $f_access = '', $f_access_group = '')
{
	global $db, $lang;

	switch ($type)
	{
		case AUTH_ALL:
			$a_sql = 'a.auth_view, a.auth_post, a.auth_rate, a.auth_comment, a.auth_edit, a.auth_delete, a.auth_approval, a.auth_approval_edit';
			$a_sql_groups = 'a.auth_view_groups, a.auth_post_groups, a.auth_rate_groups, a.auth_comment_groups, a.auth_edit_groups, a.auth_delete_groups, a.auth_approval_groups, a.auth_approval_edit_groups';
			$auth_fields = array('auth_view', 'auth_post', 'auth_rate', 'auth_comment', 'auth_edit', 'auth_delete', 'auth_approval', 'auth_approval_edit');
			$auth_fields_groups = array('auth_view_groups', 'auth_post_groups', 'auth_rate_groups', 'auth_comment_groups', 'auth_edit_groups', 'auth_delete_groups', 'auth_approval_groups', 'auth_approval_edit_groups');
			break;

		case AUTH_VIEW:
			$a_sql = 'a.auth_view';
			$a_sql_groups = 'a.auth_view_groups';
			$auth_fields = array('auth_view');
			$auth_fields_groups = array('auth_view_groups');
			break;

		case AUTH_POST:
			$a_sql = 'a.auth_post';
			$a_sql_groups = 'a.auth_post_groups';
			$auth_fields = array('auth_post');
			$auth_fields_groups = array('auth_post_groups');
			break;

		case AUTH_RATE:
			$a_sql = 'a.auth_rate';
			$a_sql_groups = 'a.auth_rate_groups';
			$auth_fields = array('auth_rate');
			$auth_fields_groups = array('auth_rate_groups');
			break;

		case AUTH_COMMENT:
			$a_sql = 'a.auth_comment';
			$a_sql_groups = 'a.auth_comment_groups';
			$auth_fields = array('auth_comment');
			$auth_fields_groups = array('auth_comment_groups');
			break;

		case AUTH_EDIT:
			$a_sql = 'a.auth_edit';
			$a_sql_groups = 'a.auth_edit_groups';
			$auth_fields = array('auth_edit');
			$auth_fields_groups = array('auth_edit_groups');
			break;

		case AUTH_DELETE:
			$a_sql = 'a.auth_delete';
			$a_sql_groups = 'a.auth_delete_groups';
			$auth_fields = array('auth_delete');
			$auth_fields_groups = array('auth_delete_groups');
			break;

		case AUTH_APPROVAL:
			$a_sql = 'a.auth_approval';
			$a_sql_groups = 'a.auth_approval_groups';
			$auth_fields = array('auth_approval');
			$auth_fields_groups = array('auth_approval_groups');
			break;

		case AUTH_APPROVAL_EDIT:
			$a_sql = 'a.auth_approval_edit';
			$a_sql_groups = 'a.auth_approval_edit_groups';
			$auth_fields = array('auth_approval_edit');
			$auth_fields_groups = array('auth_approval_edit_groups');
			break;

		default:
			break;
	}

	$is_admin = ($userdata['user_level'] == ADMIN && $userdata['session_logged_in']) ? true : 0;

	//
	// If f_access has not been passed, or auth is needed to return an array of forums
	// then we need to pull the auth information on the given forum (or all forums)
	//
	if (empty($f_access))
	{
		$forum_match_sql = ($cat_id != AUTH_LIST_ALL) ? "WHERE a.category_id = $cat_id" : '';

		$sql = "SELECT a.category_id, $a_sql
			FROM " . KB_CATEGORIES_TABLE . " a
			$forum_match_sql";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Failed obtaining forum access control lists', '', __LINE__, __FILE__, $sql);
		}

		$sql_fetchrow = ($cat_id != AUTH_LIST_ALL) ? 'sql_fetchrow' : 'sql_fetchrowset';

		if (!($f_access = $db->$sql_fetchrow($result)))
		{
			$db->sql_freeresult($result);
			return array();
		}
		$db->sql_freeresult($result);
	}

	//
	// If f_access_group has not been passed, or auth is needed to return an array of forums
	// then we need to pull the auth information on the given forum (or all forums)
	//
	if (empty($f_access_group))
	{
		$forum_match_sql = ($cat_id != AUTH_LIST_ALL) ? "WHERE a.category_id = $cat_id" : '';

		$sql = "SELECT a.category_id, $a_sql_groups, a.auth_moderator_groups
			FROM " . KB_CATEGORIES_TABLE . " a
			$forum_match_sql";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Failed obtaining forum access control lists', '', __LINE__, __FILE__, $sql);
		}

		$sql_fetchrow = ($cat_id != AUTH_LIST_ALL) ? 'sql_fetchrow' : 'sql_fetchrowset';

		if (!($f_access_group = $db->$sql_fetchrow($result)))
		{
			$db->sql_freeresult($result);
			return array();
		}
		$db->sql_freeresult($result);
	}

	$auth_user = array();
	for($i = 0; $i < count($auth_fields); $i++)
	{
		$key = $auth_fields[$i];
		$key_groups = $auth_fields_groups[$i];
		// If the user is logged on and the module type is either ALL or REG then the user has access
		// If the type if ACL, MOD or ADMIN then we need to see if the user has specific permissions
		// to do whatever it is they want to do ... to do this we pull relevant information for the
		// user (and any groups they belong to)
		// Now we compare the users access level against the modules. We assume here that a moderator
		// and admin automatically have access to an ACL module, similarly we assume admins meet an
		// auth requirement of MOD

		if ($cat_id != AUTH_LIST_ALL)
		{
			$value = $f_access[$key];
			$value_groups = $f_access_group[$key_groups];

			switch ($value)
			{
				case AUTH_ALL:
					$auth_user[$key] = true;
					$auth_user[$key . '_type'] = $lang['Auth_Anonymous_users'];
					break;

				case AUTH_REG:
					$auth_user[$key] = ($userdata['session_logged_in']) ? true : 0;
					$auth_user[$key . '_type'] = $lang['Auth_Registered_Users'];
					break;

				case AUTH_ANONYMOUS:
					$auth_user[$key] = (! $userdata['session_logged_in']) ? true : 0;
					$auth_user[$key . '_type'] = $lang['Auth_Anonymous_users'];
					break;

				case AUTH_ACL: // PRIVATE
					$auth_user[$key] = ($userdata['session_logged_in']) ? mx_is_group_member($value_groups) || $is_admin : 0;
					$auth_user[$key . '_type'] = $lang['Auth_Users_granted_access'];
					break;

				case AUTH_MOD:
					$auth_user[$key] = ($userdata['session_logged_in']) ? mx_is_group_member($f_access_group['auth_moderator_groups']) || $is_admin : 0;
					$auth_user[$key . '_type'] = $lang['Auth_Moderators'];
					break;

				case AUTH_ADMIN:
					$auth_user[$key] = $is_admin;
					$auth_user[$key . '_type'] = $lang['Auth_Administrators'];
					break;

				default:
					$auth_user[$key] = 0;
					break;
			}
		}
		else
		{
			for($k = 0; $k < count($f_access); $k++)
			{
				$value = $f_access[$k][$key];
				$value_groups = $f_access_group[$k][$key_groups];

				$f_cat_id = $f_access[$k]['category_id'];

				switch ($value)
				{
					case AUTH_ALL:
						$auth_user[$f_cat_id][$key] = true;
						$auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Anonymous_users'];
						break;

					case AUTH_REG:
						$auth_user[$f_cat_id][$key] = ($userdata['session_logged_in']) ? true : 0;
						$auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Registered_Users'];
						break;

					case AUTH_ANONYMOUS:
						$auth_user[$f_cat_id][$key] = (! $userdata['session_logged_in']) ? true : 0;
						$auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Anonymous_users'];
						break;

					case AUTH_ACL: // PRIVATE
						$auth_user[$f_cat_id][$key] = ($userdata['session_logged_in']) ? mx_is_group_member($value_groups) || $is_admin : 0;
						$auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Users_granted_access'];
						break;

					case AUTH_MOD:
						$auth_user[$f_cat_id][$key] = ($userdata['session_logged_in']) ? mx_is_group_member($f_access_group[$k]['auth_moderator_groups']) || $is_admin : 0;
						$auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Moderators'];
						break;

					case AUTH_ADMIN:
						$auth_user[$f_cat_id][$key] = $is_admin;
						$auth_user[$f_cat_id][$key . '_type'] = $lang['Auth_Administrators'];
						break;

					default:
						$auth_user[$f_cat_id][$key] = 0;
						break;
				}
			}
		}
	}

	//
	// Is user a moderator?
	//
	if ($cat_id != AUTH_LIST_ALL)
	{
		$auth_user['auth_mod'] = ($userdata['session_logged_in']) ? mx_is_group_member($f_access_group['auth_moderator_groups']) || $is_admin : 0;
	}
	else
	{
		for($k = 0; $k < count($f_access); $k++)
		{
			$f_cat_id = $f_access[$k]['category_id'];

			$auth_user[$f_cat_id]['auth_mod'] = ($userdata['session_logged_in']) ? mx_is_group_member($f_access_group[$k]['auth_moderator_groups']) || $is_admin : 0;
		}
	}
	//die(var_export($auth_user));
	return $auth_user;
}

?>