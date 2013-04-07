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

class cms_auth
{
	var $acl = array();
	var $acl_options = array();

	function acl()
	{
		global $db, $user;

		$role_data = $this->get_role_data();
		$option_array = $this->get_options_array();

		if ($role_data['ID'] && $role_data['TARGET'])
		{
			$sql = "SELECT * FROM " . ACL_ROLES_DATA_TABLE . " WHERE role_id = '" . $role_data['ID'] . "'";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->acl_options[$role_data['TARGET']][$option_array[$row['auth_option_id']]] = $row['auth_setting'];
			}

			$db->sql_freeresult($result);
		}
		else
		{
			$sql = 'SELECT o.*, u.*
				FROM ' . ACL_OPTIONS_TABLE . ' as o, ' . ACL_USERS_TABLE. ' as u
				WHERE u.user_id = ' . $user->data['user_id'] . ' AND o.auth_option_id = u.auth_option_id';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->acl_options[$row['forum_id']][$row['auth_option']] = $row['auth_setting'];
			}
			$db->sql_freeresult($result);
		}

		return;
	}

	function acl_get($opt, $target = 0)
	{
		global $user;
		return ($user->data['user_level'] == ADMIN) ? true : $this->acl_options[$target][$opt];
	}

	function get_role_data()
	{
		global $db, $user;

		$sql = "SELECT auth_role_id, forum_id FROM " . ACL_USERS_TABLE . " WHERE user_id = '" . $user->data['user_id'] . "'";

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$role['ID'] = (!empty($row['auth_role_id'])) ? intval($row['auth_role_id']) : false;
		$role['TARGET'] = (!empty($row['forum_id'])) ? intval($row['forum_id']) : false;

		return $role;
	}

	function get_options_array()
	{
		global $db;

		$option_array = array();

		$sql = "SELECT * FROM " . ACL_OPTIONS_TABLE;
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$option_array[$row['auth_option_id']] = $row['auth_option'];
		}

		return $option_array;
	}

	function auth_langs($prefix)
	{
		global $db, $lang;

		$sql = "SELECT auth_option_id, auth_option FROM " . ACL_OPTIONS_TABLE . "
			WHERE auth_option LIKE '" . $prefix . "%' ORDER BY auth_option_id";
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$auth_lang = array();

		if (!empty($rows))
		{
			foreach($rows as $data)
			{
				if (!empty($lang['AUTH'][$data['auth_option']]))
				{
					$auth_langs[$data['auth_option_id']] = $lang['AUTH'][$data['auth_option']];
				}
			}
		}

		return $auth_langs;
	}
}

?>