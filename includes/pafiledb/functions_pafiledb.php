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

//===================================================
// public pafiledb class
//===================================================

class pafiledb_public extends pafiledb
{
	var $modules = array();
	var $module_name = '';

	//===================================================
	// load module
	// $module name : send module name to load it
	//===================================================

	function module($module_name)
	{
		if (!class_exists('pafiledb_' . $module_name))
		{
			$this->module_name = $module_name;

			if (!file_exists(IP_ROOT_PATH . PA_FILE_DB_PATH . 'modules/pa_' . $module_name . '.' . PHP_EXT))
			{
				global $lang;
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}

			require_once(IP_ROOT_PATH . PA_FILE_DB_PATH . 'modules/pa_' . $module_name . '.' . PHP_EXT);
			eval('$this->modules[' . $module_name . '] = new pafiledb_' . $module_name . '();');

			if (method_exists($this->modules[$module_name], 'init'))
			{
				$this->modules[$module_name]->init();
			}
		}
	}

	//===================================================
	// this will be replaced by the loaded module
	//===================================================

	function main($module_id = false)
	{
		return false;
	}

	//===================================================
	// go ahead and output the page
	// $page title : send page title
	// $tpl_name : template file name
	//===================================================

	function display($page_title, $tpl_name)
	{
		global $template;

		pafiledb_page_header($page_title);

		$template->set_filenames(array('body' => $tpl_name));

		pafiledb_page_footer();
	}
}

//===================================================
// pafiledb class
//===================================================

class pafiledb
{
	var $cat_rowset = array();
	var $subcat_rowset = array();

	var $modified = false;

	var $auth = array();
	var $auth_global = array();

	var $total_cat = 0;
//	var $depth_info = array();

	var $error = array();

	//===================================================
	// Prepare data
	//===================================================
	function init()
	{
		global $db, $user, $debug;

		unset($this->cat_rowset);
		unset($this->subcat_rowset);

		$sql = 'SELECT *
			FROM ' . PA_CATEGORY_TABLE . '
			ORDER BY cat_order ASC';
		$result = $db->sql_query($sql);
		$cat_rowset = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$this->auth($cat_rowset);
		for($i = 0; $i < sizeof($cat_rowset); $i++)
		{
			if($this->auth[$cat_rowset[$i]['cat_id']]['auth_view'])
			{
				$this->cat_rowset[$cat_rowset[$i]['cat_id']] = $cat_rowset[$i];
				$this->subcat_rowset[$cat_rowset[$i]['cat_parent']][$cat_rowset[$i]['cat_id']] = $cat_rowset[$i];
				$this->total_cat++;
			}
		}
	}

	//===================================================
	// Jump menu function
	// $cat_id : to handle parent cat_id
	// $depth : related to function to generate tree
	// $default : the cat you wanted to be selected
	// $for_file: true high category ids will be -1
	// $check_upload: if true permission for upload will be checked
	//===================================================

	function jumpmenu_option($cat_id = 0, $depth = 0, $default = '', $for_file = false, $check_upload = false)
	{
		static $cat_rowset = false;

		if(!is_array($cat_rowset))
		{
			if($check_upload)
			{
				if(!empty($this->cat_rowset))
				{
					foreach($this->cat_rowset as $row)
					{
						if($this->auth[$row['cat_id']]['auth_upload'])
						{
							$cat_rowset[$row['cat_id']] = $row;
						}
					}
				}
			}
			else
			{
				$cat_rowset = $this->cat_rowset;
			}
		}

		$cat_list .= '';

		$pre = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $depth);

		$temp_cat_rowset = $cat_rowset;

		if(!empty($temp_cat_rowset))
		{
			foreach ($temp_cat_rowset as $temp_cat_id => $cat)
			{
				if ($cat['cat_parent'] == $cat_id)
				{
					if (is_array($default))
					{
						if (isset($default[$cat['cat_id']]))
						{
							$sel = ' selected="selected"';
						}
						else
						{
							$sel = '';
						}
					}

					$cat_pre = (!$cat['cat_allow_file']) ? '+ ' : '- ';
					$sub_cat_id = ($for_file) ? ((!$cat['cat_allow_file']) ? -1 : $cat['cat_id']) : $cat['cat_id'];
					$cat_class = (!$cat['cat_allow_file']) ? 'class="greyed"' : '';
					$cat_list .= '<option value="' . $sub_cat_id . '"' . $sel . ' ' . $cat_class . '>' . $pre . $cat_pre . $cat['cat_name'] . '</option>';
					$cat_list .= $this->jumpmenu_option($cat['cat_id'], $depth + 1, $default, $for_file, $check_upload);
				}
			}
			return $cat_list;
		}
		else
		{
			return;
		}
	}

	//===================================================
	// if there is no cat
	//===================================================

	function cat_empty()
	{
		return ($this->total_cat == 0) ? true : false;
	}


	function modified($true_false = false)
	{
		$this->modified = $true_false;
	}

	//===================================================
	// get all sub category in side certain category
	// $cat_id : category id
	//===================================================

	function get_sub_cat($cat_id)
	{
		global $images;
		$mini_img = $images['icon_minicat'];
		$cat_sub .= '';
		if(!empty($this->subcat_rowset[$cat_id]))
		{
			foreach($this->subcat_rowset[$cat_id] as $cat_id => $cat_row)
			{
				if($cat_row['cat_allow_file'])
				{
					$cat_sub .= '<img src="' . $mini_img . '" />&nbsp;<a href="' . append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $cat_row['cat_id']) . '" class="forumlink2">' . $cat_row['cat_name'] . '</a> ';
				}
				else
				{
					if(!empty($this->subcat_rowset[$cat_row['cat_id']]))
					{
						foreach($this->subcat_rowset[$cat_row['cat_id']] as $sub_cat_id => $sub_cat_row)
						{
							if($sub_cat_row['cat_allow_file'])
							{
								$cat_sub .= '<img src="' . $mini_img . '" />&nbsp;<a href="' . append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $sub_cat_row['cat_id']) . '" class="forumlink2">' . $sub_cat_row['cat_name'] . '</a> ';
							}
						}
					}
				}
			}
		}
		return $cat_sub;
	}

	function last_file_in_cat($cat_id, &$file_info)
	{
		if((empty($this->cat_rowset[$cat_id]['cat_last_file_id']) && empty($this->cat_rowset[$cat_id]['cat_last_file_name']) && empty($this->cat_rowset[$cat_id]['cat_last_file_time'])) || $this->modified)
		{
			global $db;

			$sql = 'SELECT file_time, file_id, file_name, file_catid
				FROM ' . PA_FILES_TABLE . "
				WHERE file_approved = '1'
				AND file_catid IN (" . $this->gen_cat_ids($cat_id) . ")
				ORDER BY file_time DESC";
			$result = $db->sql_query($sql);

			while($row = $db->sql_fetchrow($result))
			{
				$temp_cat[] = $row;
			}

			$file_info = $temp_cat[0];
			if(!empty($file_info))
			{
				$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
					SET cat_last_file_id = " . intval($file_info['file_id']) . ",
					cat_last_file_name = '" . $db->sql_escape($file_info['file_name']) . "',
					cat_last_file_time = " . intval($file_info['file_time']) . "
					WHERE cat_id = $cat_id";
				$db->sql_query($sql);
			}
		}
		else
		{
			$file_info['file_id'] = $this->cat_rowset[$cat_id]['cat_last_file_id'];
			$file_info['file_name'] = $this->cat_rowset[$cat_id]['cat_last_file_name'];
			$file_info['file_time'] = $this->cat_rowset[$cat_id]['cat_last_file_time'];
		}
	}

	function generate_category_nav($cat_id)
	{
		global $template, $db;

		if($this->cat_rowset[$cat_id]['parents_data'] == '')
		{
			$cat_nav = array();
			$this->category_nav($this->cat_rowset[$cat_id]['cat_parent'], &$cat_nav);

			$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
				SET parents_data = '" . addslashes(serialize($cat_nav)) . "'
				WHERE cat_parent = " . $this->cat_rowset[$cat_id]['cat_parent'];
			$db->sql_query($sql);
		}
		else
		{
			$cat_nav = unserialize(stripslashes($this->cat_rowset[$cat_id]['parents_data']));
		}

		if(!empty($cat_nav))
		{
			foreach ($cat_nav as $parent_cat_id => $parent_name)
			{
				$template->assign_block_vars('navlinks', array(
					'CLASS' => 'nav',
					'CAT_NAME' => $parent_name,
					'U_VIEW_CAT' => append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $parent_cat_id)
					)
				);
			}
		}

		$template->assign_block_vars('navlinks', array(
			'CLASS' => 'nav-current',
			'CAT_NAME' => $this->cat_rowset[$cat_id]['cat_name'],
			'U_VIEW_CAT' => append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $this->cat_rowset[$cat_id]['cat_id'])
			)
		);

		return;
	}

	function generate_category_nav_links($cat_id, $file_id)
	{
		global $db, $lang;
		$nav_links = '';
		$nav_server_url = create_server_url();

		if ($file_id)
		{
			$sql = 'SELECT *
				FROM ' . PA_FILES_TABLE . '
				WHERE file_id = ' . $file_id . '
				LIMIT 1';
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$file_name = $row['file_name'];
			$cat_id = $row['file_catid'];
			$db->sql_freeresult($result);
		}

		$sql = 'SELECT *
			FROM ' . PA_CATEGORY_TABLE . '
			ORDER BY cat_order ASC';
		$result = $db->sql_query($sql, 0, 'pafiledb_cats_');

		while ($row = $db->sql_fetchrow($result))
		{
			$this->cat_rowset[$row['cat_id']] = $row;
		}
		$db->sql_freeresult($result);

		if($this->cat_rowset[$cat_id]['parents_data'] == '')
		{
			$cat_nav = array();
			$this->category_nav($this->cat_rowset[$cat_id]['cat_parent'], &$cat_nav);
		}
		else
		{
			$cat_nav = unserialize(stripslashes($this->cat_rowset[$cat_id]['parents_data']));
		}

		if(!empty($cat_nav))
		{
			foreach ($cat_nav as $parent_cat_id => $parent_name)
			{
				$nav_links .= $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $parent_cat_id) . '">' . $parent_name . '</a>';
			}
		}

		$nav_links .= $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $this->cat_rowset[$cat_id]['cat_id']) . '"' . ($file_id ? '' : ' class="nav-current"') . '>' . $this->cat_rowset[$cat_id]['cat_name'] . '</a>';

		$nav_links .= ($file_id ? ($lang['Nav_Separator'] . '<a href="#" class="nav-current">' . $file_name . '</a>') : '');

		return $nav_links;
	}

	function category_nav($parent_id, &$cat_nav)
	{
		if(!empty($this->cat_rowset[$parent_id]))
		{
			$this->category_nav($this->cat_rowset[$parent_id]['cat_parent'], &$cat_nav);
			$cat_nav[$parent_id] = $this->cat_rowset[$parent_id]['cat_name'];
		}
		return;
	}

/*
	function init_depth($cat_id = 0, $depth = 0)
	{
		$temp = $depth;
		if(isset($this->subcat_rowset[$cat_id]))
		{
			foreach($this->subcat_rowset[$cat_id] as $temp_cat_id => $void)
			{
				$this->init_depth($temp_cat_id, $depth + 1);
				$this->depth_info[$void['cat_parent']][$temp_cat_id] = $temp;
				$depth = 0;
			}
		}
	}

	function get_max_depth($cat_parent, &$max_depth)
	{
		if(empty($this->depth_info))
		{
			$this->init_depth();
		}

		if(isset($this->depth_info[$cat_parent]))
		{
			foreach($this->depth_info[$cat_parent] as $cat_id => $depth)
			{
				if($max_depth < $depth)
				{
					$max_depth = $depth;
				}
				$this->get_max_depth($cat_id, $max_depth);
			}

		}
	}
*/

	function file_in_cat($cat_id)
	{
		if($this->cat_rowset[$cat_id]['cat_files'] == -1 || $this->modified)
		{
			global $db;

			$sql = 'SELECT COUNT(file_id) as total_files
				FROM ' . PA_FILES_TABLE . "
				WHERE file_approved = '1'
				AND file_catid IN (" . $this->gen_cat_ids($cat_id) . ')
				ORDER BY file_time DESC';
			$result = $db->sql_query($sql);
			$files_no = 0;
			if($row = $db->sql_fetchrow($result))
			{
				$files_no = $row['total_files'];
			}

			$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
					SET cat_files = $files_no
					WHERE cat_id = $cat_id";
			$result = $db->sql_query($sql);
		}
		else
		{
			$files_no = $this->cat_rowset[$cat_id]['cat_files'];
		}


		return $files_no;
	}

	function new_file_in_cat($cat_id)
	{
		global $pafiledb_config, $config, $db, $_COOKIE;

		$files_new = 0;

		$time = time() - ($pafiledb_config['settings_newdays'] * 24 * 60 * 60);

		$sql = 'SELECT file_time, file_catid
			FROM ' . PA_FILES_TABLE . "
			WHERE file_approved = '1'
			AND file_catid IN (" . $this->gen_cat_ids($cat_id) . ')
			AND file_time > ' . $time . '
			ORDER BY file_time DESC';
		$result = $db->sql_query($sql);

		while($row = $db->sql_fetchrow($result))
		{
			if($this->auth[$row['file_catid']]['auth_read'])
			{
				$files_new++;
			}
		}

		return $files_new;
	}

	function gen_cat_ids($cat_id, $cat_ids = '')
	{
		if(!empty($this->subcat_rowset[$cat_id]))
		{
			foreach($this->subcat_rowset[$cat_id] as $subcat_id => $cat_row)
			{
				$cat_ids = $this->gen_cat_ids($subcat_id, $cat_ids);
			}
		}

		if(!empty($this->cat_rowset[$cat_id]))
		{
			$cat_ids .= (($cat_ids != '') ? ', ' : '') . $cat_id;
		}
		return $cat_ids;
	}

	function auth($c_access)
	{
		global $db, $db, $user, $lang, $pafiledb_config;
// Mx Addon
		$a_sql = 'a.auth_view, a.auth_read, a.auth_view_file, a.auth_edit_file, a.auth_delete_file, a.auth_upload, a.auth_download, a.auth_rate, a.auth_email, a.auth_view_comment, a.auth_post_comment, a.auth_edit_comment, a.auth_delete_comment, a.auth_mod, a.auth_search, a.auth_stats, a.auth_toplist, a.auth_viewall';
		$auth_fields = array('auth_view', 'auth_read', 'auth_view_file', 'auth_edit_file', 'auth_delete_file', 'auth_upload', 'auth_download', 'auth_rate', 'auth_email', 'auth_view_comment', 'auth_post_comment', 'auth_edit_comment', 'auth_delete_comment');
		$auth_fields_global = array('auth_search', 'auth_stats', 'auth_toplist', 'auth_viewall');

		//
		// If the user isn't logged on then all we need do is check if the forum
		// has the type set to ALL, if yes they are good to go, if not then they
		// are denied access
		//
		$u_access = array();
		$global_u_access = array();
		if ($user->data['session_logged_in'])
		{
			$sql = "SELECT a.cat_id, a.group_id, $a_sql
				FROM " . PA_AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug
				WHERE ug.user_id = {$user->data['user_id']}
					AND ug.user_pending = 0
					AND a.group_id = ug.group_id";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				do
				{
					if($row['cat_id'])
					{
						$u_access[$row['cat_id']][] = $row;
					}
					else
					{
						$global_u_access = $row;
					}
				}
				while($row = $db->sql_fetchrow($result));
			}
		}

		$is_admin = ($user->data['user_level'] == ADMIN && $user->data['session_logged_in']) ? true : 0;

		for($i = 0; $i < sizeof($auth_fields); $i++)
		{
			$key = $auth_fields[$i];

			//
			// If the user is logged on and the forum type is either ALL or REG then the user has access
			//
			// If the type if ACL, MOD or ADMIN then we need to see if the user has specific permissions
			// to do whatever it is they want to do ... to do this we pull relevant information for the
			// user (and any groups they belong to)
			//
			// Now we compare the users access level against the forums. We assume here that a moderator
			// and admin automatically have access to an ACL forum, similarly we assume admins meet an
			// auth requirement of MOD
			//
			for($k = 0; $k < sizeof($c_access); $k++)
			{
				$value = $c_access[$k][$key];
				$c_cat_id = $c_access[$k]['cat_id'];
				global $lang;

				switch($value)
				{
					case AUTH_ALL:
						$this->auth[$c_cat_id][$key] = true;
						$this->auth[$c_cat_id][$key . '_type'] = $lang['Auth_Anonymous_Users'];
						break;

					case AUTH_REG:
						$this->auth[$c_cat_id][$key] = ($user->data['session_logged_in']) ? true : 0;
						$this->auth[$c_cat_id][$key . '_type'] = $lang['Auth_Registered_Users'];
						break;

					case AUTH_ACL:
						$this->auth[$c_cat_id][$key] = ($user->data['session_logged_in']) ? $this->auth_check_user(AUTH_ACL, $key, $u_access[$c_cat_id], $is_admin) : 0;
						$this->auth[$c_cat_id][$key . '_type'] = $lang['Auth_Users_granted_access'];
						break;

					case AUTH_MOD:
						$this->auth[$c_cat_id][$key] = ($user->data['session_logged_in']) ? $this->auth_check_user(AUTH_MOD, 'auth_mod', $u_access[$c_cat_id], $is_admin) : 0;
						$this->auth[$c_cat_id][$key . '_type'] = $lang['Auth_Moderators'];
						break;

					case AUTH_ADMIN:
						$this->auth[$c_cat_id][$key] = $is_admin;
						$this->auth[$c_cat_id][$key . '_type'] = $lang['Auth_Administrators'];
						break;

					default:
						$this->auth[$c_cat_id][$key] = 0;
						break;
				}
			}
		}
		for($k = 0; $k < sizeof($c_access); $k++)
		{
			$c_cat_id = $c_access[$k]['cat_id'];
			$this->auth[$c_cat_id]['auth_mod'] = ($user->data['session_logged_in']) ? $this->auth_check_user(AUTH_MOD, 'auth_mod', $u_access[$c_cat_id], $is_admin) : 0;
		}

		for($i = 0; $i < sizeof($auth_fields_global); $i++)
		{
			$key = $auth_fields_global[$i];
			$value = $pafiledb_config[$auth_fields_global[$i]];
			global $lang;

			switch($value)
			{
				case AUTH_ALL:
					$this->auth_global[$key] = true;
					$this->auth_global[$key . '_type'] = $lang['Auth_Anonymous_Users'];
					break;

				case AUTH_REG:
					$this->auth_global[$key] = ($user->data['session_logged_in']) ? true : 0;
					$this->auth_global[$key . '_type'] = $lang['Auth_Registered_Users'];
					break;

				case AUTH_ACL:
					$this->auth_global[$key] = ($user->data['session_logged_in']) ? $this->global_auth_check_user(AUTH_ACL, $key, $global_u_access, $is_admin) : 0;
					$this->auth_global[$key . '_type'] = $lang['Auth_Users_granted_access'];
					break;

				case AUTH_MOD:
					$this->auth_global[$key] = ($user->data['session_logged_in']) ? $this->global_auth_check_user(AUTH_MOD, 'auth_mod', $global_u_access, $is_admin) : 0;
					$this->auth_global[$key . '_type'] = $lang['Auth_Moderators'];
					break;

				case AUTH_ADMIN:
					$this->auth_global[$key] = $is_admin;
					$this->auth_global[$key . '_type'] = $lang['Auth_Administrators'];
					break;

				default:
					$this->auth_global[$key] = 0;
					break;
			}
		}
	}

	function auth_check_user($type, $key, $u_access, $is_admin)
	{
		$auth_user = 0;

		if (sizeof($u_access))
		{
			for($j = 0; $j < sizeof($u_access); $j++)
			{
				$result = 0;
				switch($type)
				{
					case AUTH_ACL:
						$result = $u_access[$j][$key];

					case AUTH_MOD:
						$result = $result || $u_access[$j]['auth_mod'];

					case AUTH_ADMIN:
						$result = $result || $is_admin;
						break;
				}

				$auth_user = $auth_user || $result;
			}
		}
		else
		{
			$auth_user = $is_admin;
		}

		return $auth_user;
	}

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
					$result = $result || $this->is_moderator();

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

	function is_moderator()
	{
		if(!empty($this->auth))
		{
			foreach($this->auth as $cat_id => $auth_fields)
			{
				if($auth_fileds['auth_mod'])
				{
					return true;
				}
			}
			return false;
		}
		return false;
	}

	function category_display($cat_id = PA_ROOT_CAT)
	{
		global $db, $config, $template, $images, $lang, $user, $debug;
		global $pafiledb_config;

		if($this->cat_empty())
		{
			if (!$user->data['session_logged_in'])
			{
				$redirect = ($cat_id != PA_ROOT_CAT) ? 'dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $cat_id : 'dload.' . PHP_EXT;
				redirect(append_sid(CMS_PAGE_LOGIN. '?redirect=' . $redirect, true));
			}
			message_die(GENERAL_ERROR, 'Either you are not allowed to view any category, or there is no category in the database');
		}

		$template->assign_vars(array(
			'CAT_PARENT' => true,
			'L_SUB_CAT' => $lang['Sub_category'],
			'L_CATEGORY' => $lang['Category'],
			'L_LAST_FILE' => $lang['Last_file'],
			'L_FILES' => $lang['Files']
			)
		);

		//output the root level category that allow file
		if(isset($this->subcat_rowset[$cat_id]))
		{
			foreach($this->subcat_rowset[$cat_id] as $subcat_id => $subcat_row)
			{
				if(($subcat_row['cat_allow_file'] == PA_CAT_ALLOW_FILE))
				{
					$last_file_info = array();
					$this->last_file_in_cat($subcat_id, $last_file_info);

					if(!empty($last_file_info['file_id']) && $this->auth[$subcat_id]['auth_read'])
					{
						$last_file_time = create_date_ip($config['default_dateformat'], $last_file_info['file_time'], $config['board_timezone']);
						$last_file = $last_file_time . '<br />';
						$last_file_name = (strlen(stripslashes($last_file_info['file_name'])) > 20) ? substr(stripslashes($last_file_info['file_name']), 0, 20) . '...' : stripslashes($last_file_info['file_name']);
						$last_file .= '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&file_id=' . $last_file_info['file_id']) . '" alt="' . stripslashes($last_file_info['file_name']) . '" title="' . stripslashes($last_file_info['file_name']) . '">' . $last_file_name . '</a> ';
						$last_file .= '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&file_id=' . $last_file_info['file_id']) . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_file'] . '" title="' . $lang['View_latest_file'] . '" /></a>';
					}
					else
					{
						$last_file = $lang['No_file'];
					}
					$is_new = false;

					if($this->new_file_in_cat($subcat_id))
					{
						$is_new = true;
					}
					$sub_cat = $this->get_sub_cat($subcat_id);

					$xs_new = ($is_new)  ? '-new' : '';
					$mini_img = $images['icon_minicat'];
					if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
					{
						$url_cat = append_sid(str_replace ('--', '-', make_url_friendly($subcat_row['cat_name']) . '-dc' . $subcat_id . '.html'));
					}
					else
					{
						$url_cat = append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $subcat_id);
					}

					$template->assign_block_vars('no_cat_parent', array(
						'IS_HIGHER_CAT' => false,
						'U_CAT' => $url_cat,
						'SUB_CAT' => (!empty($sub_cat)) ? $sub_cat : '',
						'L_SUB_CAT' => (!empty($sub_cat)) ? $lang['Sub_category'] .': ' : '',
						'CAT_IMAGE' => ($is_new) ? $images['forum_nor_unread'] : $images['forum_nor_read'],
						'XS_NEW' => $xs_new,
						'CAT_NEW_FILE' => ($is_new) ? $lang['New_file'] : $lang['No_new_file'],
						'CAT_NAME' => $subcat_row['cat_name'],
						'FILECAT' => $this->file_in_cat($subcat_id),
						'LAST_FILE' => $last_file,
						'CAT_DESC' => $subcat_row['cat_desc'])
					);
				}
			}
		}
		if(isset($this->subcat_rowset[$cat_id]))
		{
			foreach($this->subcat_rowset[$cat_id] as $subcat_id => $subcat_row)
			{
				$total_sub_cat = 0;
				if(isset($this->subcat_rowset[$subcat_id]))
				{
					foreach($this->subcat_rowset[$subcat_id] as $sub_no_cat_id => $sub_no_cat_row)
					{
						if($sub_no_cat_row['cat_allow_file'] == PA_CAT_ALLOW_FILE)
						{
							$sub_cat_rowset[$total_sub_cat] = $sub_no_cat_row;
							$total_sub_cat++;
						}
					}
				}

				if(($subcat_row['cat_allow_file'] != PA_CAT_ALLOW_FILE))
				{
					if($total_sub_cat)
					{
						if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
						{
							$url_cat = append_sid(str_replace ('--', '-', make_url_friendly($subcat_row['cat_name']) . '-dc' . $subcat_id . '.html'));
						}
						else
						{
							$url_cat = append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $subcat_id);
						}

						$template->assign_block_vars('no_cat_parent', array(
							'IS_HIGHER_CAT' => true,
							'U_CAT' => $url_cat,
							'CAT_NAME' => $subcat_row['cat_name']
							)
						);
					}
					for($k = 0; $k < $total_sub_cat; $k++)
					{
						$last_file_info = array();
						$this->last_file_in_cat($sub_cat_rowset[$k]['cat_id'], $last_file_info);

						if ($sub_cat_rowset[$k]['cat_parent'] == $subcat_id)
						{
							if(!empty($last_file_info['file_id']) && $this->auth[$sub_cat_rowset[$k]['cat_id']]['auth_read'])
							{
								$last_file_time = create_date_ip($config['default_dateformat'], $last_file_info['file_time'], $config['board_timezone']);
								$last_file = $last_file_time . '<br />';
								$last_file_name = (strlen(stripslashes($last_file_info['file_name'])) > 20) ? substr(stripslashes($last_file_info['file_name']), 0, 20) . '...' : stripslashes($last_file_info['file_name']);
								$last_file .= '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $last_file_info['file_id']) . '">' . $last_file_name . '</a> ';
								$last_file .= '<a href="' . append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $last_file_info['file_id']) . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_file'] . '" title="' . $lang['View_latest_file'] . '" /></a>';
							}
							else
							{
								$last_file = $lang['No_file'];
							}

							$is_new = false;

							if($this->new_file_in_cat($sub_cat_rowset[$k]['cat_id']))
							{
								$is_new = true;
							}

							$sub_cat = $this->get_sub_cat($sub_cat_rowset[$k]['cat_id']);
							$xs_new = ($is_new) ? '-new' : '';
							if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
							{
								$url_cat = append_sid(str_replace ('--', '-', make_url_friendly($sub_cat_rowset[$k]['cat_name']) . '-dc' . $sub_cat_rowset[$k]['cat_id'] . '.html'));
							}
							else
							{
								$url_cat = append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $sub_cat_rowset[$k]['cat_id']);
							}

							$template->assign_block_vars('no_cat_parent', array(
								'IS_HIGER_CAT' => false,
								'U_CAT' => $url_cat,
								'SUB_CAT' => (!empty($sub_cat)) ? $sub_cat : '',
								'L_SUB_CAT' => (!empty($sub_cat)) ? $lang['Sub_category'] . ': ' : '',
								'XS_NEW' => $xs_new,
								'CAT_IMAGE' => ($is_new) ? $images['forum_nor_unread'] : $images['forum_nor_read'],
								'CAT_NEW_FILE' => ($is_new) ? $lang['New_file'] : $lang['No_new_file'],
								'CAT_NAME' => $sub_cat_rowset[$k]['cat_name'],
								'FILECAT' => $this->file_in_cat($sub_cat_rowset[$k]['cat_id']),
								'LAST_FILE' => $last_file,
								'CAT_DESC' => $sub_cat_rowset[$k]['cat_desc']
								)
							);
						} // Have a permission to view the category
					} // It is not parent category
				}
			}
		} //higher Category
	}

	function display_files($sort_method, $sort_order, $start, $show_file_message, $cat_id = false)
	{
		global $db, $config, $template, $images, $lang;
		global $pafiledb_config, $pafiledb_functions;

		$filelist = false;

		if(empty($cat_id))
		{
			$cat_where = '';
		}
		else
		{
			$cat_where = "AND f1.file_catid = $cat_id";
		}

		$sql = "SELECT f1.*, f1.file_id, r.votes_file, AVG(r.rate_point) AS rating, COUNT(r.votes_file) AS total_votes, u.user_id, u.username, COUNT(c.comments_id) AS total_comments
			FROM " . PA_FILES_TABLE . " AS f1
				LEFT JOIN " . PA_VOTES_TABLE . " AS r ON f1.file_id = r.votes_file
				LEFT JOIN " . USERS_TABLE . " AS u ON f1.user_id = u.user_id
				LEFT JOIN " . PA_COMMENTS_TABLE . " AS c ON f1.file_id = c.file_id
			WHERE f1.file_pin = " . FILE_PINNED . "
			AND f1.file_approved = 1
			$cat_where
			GROUP BY f1.file_id
			ORDER BY $sort_method $sort_order";
		$result = $db->sql_query($sql);
		$file_rowset = array();
		$total_file = 0;

		while($row = $db->sql_fetchrow($result))
		{
			if($this->auth[$row['file_catid']]['auth_read'])
			{
				$file_rowset[] = $row;
			}
		}

		$db->sql_freeresult($result);

		$sql = "SELECT f1.*, f1.file_id, r.votes_file, AVG(r.rate_point) AS rating, COUNT(r.votes_file) AS total_votes, u.user_id, u.username, COUNT(c.comments_id)
			FROM " . PA_FILES_TABLE . " AS f1
				LEFT JOIN " . PA_VOTES_TABLE . " AS r ON f1.file_id = r.votes_file
				LEFT JOIN " . USERS_TABLE . " AS u ON f1.user_id = u.user_id
				LEFT JOIN " . PA_COMMENTS_TABLE . " AS c ON f1.file_id = c.file_id
			WHERE f1.file_pin <> " . FILE_PINNED . "
			AND f1.file_approved = 1
			$cat_where
			GROUP BY f1.file_id
			ORDER BY $sort_method $sort_order";

		if (!($result = $pafiledb_functions->sql_query_limit($sql, $pafiledb_config['settings_file_page'], $start)))
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get file info for this category', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			if($this->auth[$row['file_catid']]['auth_read'])
			{
				$file_rowset[] = $row;
			}
		}

		$db->sql_freeresult($result);

		$where_sql = (!empty($cat_id)) ? "AND file_catid = $cat_id" : '';
		$sql = "SELECT COUNT(file_id) as total_file
			FROM " . PA_FILES_TABLE . "
			WHERE file_approved='1'
			$where_sql";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$total_file = $row['total_file'];
		unset($row);

		for ($i = 0; $i < sizeof($file_rowset); $i++)
		{
			//===================================================
			// Format the date for the given file
			//===================================================

			$date = create_date_ip($config['default_dateformat'], $file_rowset[$i]['file_time'], $config['board_timezone']);

			//===================================================
			// Get rating for the file and format it
			//===================================================

			//$rating = ($file_rowset[$i]['rating'] != 0) ? round($file_rowset[$i]['rating'], 2) . ' / 10' : $lang['Not_rated'];
			//$rating2 = ($file_rowset[$i]['rating'] != 0) ? sprintf("%.1f", round(($file_rowset[$i]['rating']), 0)/2) : '0.0';
			$rating2 = ($file_rowset[$i]['rating'] != 0) ? sprintf("%.1f", round(($file_rowset[$i]['rating']), 0)/2) : '0.0';

			//===================================================
			// If the file is new then put a new image in front of it
			//===================================================

			$is_new = false;
			if (time() - ($pafiledb_config['settings_newdays'] * 24 * 60 * 60) < $file_rowset[$i]['file_time'])
			{
				$is_new = true;
			}

			$cat_name = (empty($cat_id)) ? $this->cat_rowset[$file_rowset[$i]['file_catid']]['cat_name'] : '';
			$cat_url = append_sid('dload.' . PHP_EXT . '?action=category&amp;cat_id=' . $file_rowset[$i]['file_catid']);


			//===================================================
			// Get the post icon fot this file
			//===================================================
			if($file_rowset[$i]['file_pin'] != FILE_PINNED)
			{
				if (($file_rowset[$i]['file_posticon'] == 'none') || ($file_rowset[$i]['file_posticon'] == 'none.gif'))
				{
					$posticon = IP_ROOT_PATH . FILES_ICONS_DIR .'default.png';
				}
				else
				{
					$posticon = IP_ROOT_PATH . FILES_ICONS_DIR . $file_rowset[$i]['file_posticon'];
				}
			}
			else
			{
				$posticon = IP_ROOT_PATH . $images['forum_link'];
			}
			//echo $posticon;
			//===================================================
			// Assign Vars
			//===================================================
			$xs_new = ($is_new)  ? '-new' : '';

			if (($config['url_rw'] == '1') || (($config['url_rw_guests'] == '1') && ($user->data['user_id'] == ANONYMOUS)))
			{
				$url_file = append_sid(str_replace ('--', '-', make_url_friendly($file_rowset[$i]['file_name']) . '-df' . $file_rowset[$i]['file_id'] . '.html'));
			}
			else
			{
				$url_file = append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_rowset[$i]['file_id']);
			}

			//$url_file = append_sid('dload.' . PHP_EXT . '?action=file&file_id=' . $file_rowset[$i]['file_id']);
			$template->assign_block_vars('file_rows', array(
				'L_NEW_FILE' => $lang['New_file'],
				'PIN_IMAGE' => $posticon,
				'FILE_NEW_IMAGE' => IP_ROOT_PATH . $images['pa_file_new'],
				'HAS_SCREENSHOTS' => (!empty($file_rowset[$i]['file_ssurl'])) ? true : false,
				'SS_AS_LINK' => ($file_rowset[$i]['file_sshot_link']) ? true : false,
				'FILE_SCREENSHOT' => $file_rowset[$i]['file_ssurl'],
				'FILE_SCREENSHOT_URL' => IP_ROOT_PATH . 'images/screenshot.gif',
				'FILE_NAME' => $file_rowset[$i]['file_name'],
				'FILE_DESC' => $file_rowset[$i]['file_desc'],
				'DATE' => $date,
				'RATING' => $rating2,
				'FILE_DLS' => $file_rowset[$i]['file_dls'],
				'CAT_NAME' => $cat_name,
				'IS_NEW_FILE' => $is_new,
				'XS_NEW' => $xs_new,
				'U_CAT' => $cat_url,
				'U_FILE' => $url_file,
				'U_FILE_EDIT' => append_sid('dload.' . PHP_EXT . '?action=user_upload&amp;file_id=' . $file_rowset[$i]['file_id'])
				)
			);
			$filelist = true;
		}

		if ($filelist)
		{
			$action = (empty($cat_id)) ? 'viewall' : 'category&amp;cat_id=' . $cat_id;
			$template->assign_vars(array(
				'L_CATEGORY' => $lang['Category'],
				'L_RATING' => $lang['DlRating'],
				'L_DOWNLOADS' => $lang['Dls'],
				'L_DATE' => $lang['Date'],
				'L_NAME' => $lang['Name'],
				'L_FILE' => $lang['File'],
				'L_UPDATE_TIME' => $lang['Update_time'],
				'L_SCREENSHOTS' => $lang['Scrsht'],

				'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
				'L_ORDER' => $lang['Order'],
				'L_SORT' => $lang['Sort'],

				'L_ASC' => $lang['Sort_Ascending'],
				'L_DESC' => $lang['Sort_Descending'],

				'SORT_NAME' => ($sort_method == 'file_name') ? 'selected="selected"' : '',
				'SORT_TIME' => ($sort_method == 'file_time') ? 'selected="selected"' : '',
				'SORT_RATING' => ($sort_method == 'rating') ? 'selected="selected"' : '',
				'SORT_DOWNLOADS' => ($sort_method == 'file_dls') ? 'selected="selected"' : '',
				'SORT_UPDATE_TIME' => ($sort_method == 'file_update_time') ? 'selected="selected"' : '',

				'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
				'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',
				'PAGINATION' => generate_pagination(append_sid('dload.' . PHP_EXT . '?action=' . $action . '&amp;sort_method=' . $sort_method . '&amp;sort_order=' . $sort_order), $total_file, $pafiledb_config['settings_file_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $pafiledb_config['settings_file_page']) + 1), ceil($total_file / $pafiledb_config['settings_file_page'])),
				'FILELIST' => $filelist,
				'ID' => $cat_id,
				'START' => $start,

				'S_ACTION_SORT' => append_sid('dload.' . PHP_EXT . '?action=' . $action)
				)
			);
		}
		else
		{
			$template->assign_vars(array(
				'NO_FILE' => $show_file_message,
				'L_NO_FILES' => $lang['No_files'],
				'L_NO_FILES_CAT' => $lang['No_files_cat']
				)
			);
		}
	}

	//=============================================
	// Admin and mod functions
	//=============================================

	function update_add_cat($cat_id = false)
	{
		global $db, $lang;

		$cat_name = request_post_var('cat_name', '');
		$cat_desc = request_post_var('cat_desc', '');
		$cat_parent = request_post_var('cat_parent', 0);
		$cat_allow_file = request_post_var('cat_allow_file', 0);
		// MX Addon
		$cat_allow_ratings = request_post_var('cat_allow_ratings', 0);
		$cat_allow_comments = request_post_var('cat_allow_comments', 0);

		if(empty($cat_name))
		{
			$this->error[] = $lang['Cat_name_missing'];
		}

		if($cat_parent)
		{
			if(!$this->cat_rowset[$cat_parent]['cat_allow_file'] && !$cat_allow_file)
			{
				$this->error[] = $lang['Cat_conflict'];
			}
		}

		if(sizeof($this->error))
		{
			return;
		}

		if(!$cat_id)
		{
			$cat_order = 0;
			if(!empty($this->subcat_rowset[$cat_parent]))
			{
				foreach($this->subcat_rowset[$cat_parent] as $cat_data)
				{
					if($cat_order < $cat_data['cat_order'])
					{
						$cat_order = $cat_data['cat_order'];
					}
				}
			}

			$cat_order += 10;

			$sql = 'INSERT INTO ' . PA_CATEGORY_TABLE . " (cat_name, cat_desc, cat_parent, cat_order, cat_allow_file, cat_allow_ratings, cat_allow_comments)
				VALUES('" . $db->sql_escape($cat_name) . "', '" . $db->sql_escape($cat_desc) . "', $cat_parent, $cat_order, $cat_allow_file, $cat_allow_ratings, $cat_allow_comments)";
			$db->sql_query($sql);
		}
		else
		{
			$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
				SET cat_name = '" . $db->sql_escape($cat_name) . "', cat_desc = '" . $db->sql_escape($cat_desc) . "', cat_parent = $cat_parent, cat_allow_file = $cat_allow_file, cat_allow_ratings = $cat_allow_ratings, cat_allow_comments = $cat_allow_comments
				WHERE cat_id = $cat_id";
			$db->sql_query($sql);

			if($cat_parent != $this->cat_rowset[$cat_id]['cat_parent'])
			{
				$this->reorder_cat($this->cat_rowset[$cat_id]['cat_parent']);
				$this->reorder_cat($cat_parent);
			}
			$this->modified(true);
		}

		if($cat_id)
		{
			return $cat_id;
		}
		else
		{
			return $db->sql_nextid();
		}
	}

	function delete_cat($cat_id = false)
	{
		global $db, $lang;

		$file_mode = request_post_var('file_mode', 'move');
		$subcat_mode = request_post_var('subcat_mode', 'move');
		$file_to_cat_id = request_post_var('file_to_cat_id', 0);
		$subcat_to_cat_id = request_post_var('subcat_to_cat_id', 0);

		if (empty($cat_id))
		{
			$this->error[] = $lang['Cdelerror'];
		}
		else
		{
			if (($file_to_cat_id == -1 || empty($file_to_cat_id)) && $file_mode == 'move')
			{
				$this->error[] = $lang['Cdelerror'];
			}

			if($subcat_mode == 'move' && empty($subcat_to_cat_id))
			{
				$this->error[] = $lang['Cdelerror'];
			}

			if(sizeof($this->error))
			{
				return;
			}

			$sql = 'DELETE FROM ' . PA_CATEGORY_TABLE . "
				WHERE cat_id = $cat_id";
			$db->sql_query($sql);
			$this->reorder_cat($this->cat_rowset[$cat_id]['cat_parent']);

			if ($file_mode == 'delete')
			{
				$this->delete_files($cat_id, 'category');
			}
			else
			{
				$this->move_files($cat_id, $file_to_cat_id);
			}

			if($subcat_mode == 'delete')
			{
				$this->delete_subcat($cat_id, $file_mode, $file_to_cat_id);
			}
			else
			{
				$this->move_subcat($cat_id, $subcat_to_cat_id);
			}
			$this->modified(true);
		}
	}

	function delete_files($id, $mode = 'file')
	{
		global $db, $pafiledb_functions;

		if($mode == 'category')
		{
			$file_ids = array();
			$files_data = array();
			$sql = 'SELECT file_id, unique_name, file_dir
				FROM ' . PA_FILES_TABLE . "
				WHERE file_catid = $id";
			$result = $db->sql_query($sql);

			while($row = $db->sql_fetchrow($result))
			{
				$file_ids[] = $row['file_id'];
				$files_data[] = $row;
			}

			$where_sql = "WHERE file_catid = $id";
		}
		else
		{
			$sql = 'SELECT file_id, unique_name, file_dir
				FROM ' . PA_FILES_TABLE . "
				WHERE file_id = $id";
			$result = $db->sql_query($sql);
			$file_data = $db->sql_fetchrow($result);
			$where_sql = "WHERE file_id = $id";
		}

		$sql = 'DELETE FROM ' . PA_FILES_TABLE . " " . $where_sql;
		$db->sql_query($sql);

		$where_sql = ($mode != 'file' && !empty($file_ids)) ? ' IN (' . implode(', ', $file_ids) . ') ' : " = $id";
		$sql = 'DELETE FROM ' . PA_CUSTOM_DATA_TABLE . "
			WHERE customdata_file$where_sql";
		$db->sql_query($sql);

		$sql = 'DELETE FROM ' . PA_MIRRORS_TABLE . "
			WHERE file_id$where_sql";
		$db->sql_query($sql);

		if($mode == 'category')
		{
			foreach($files_data as $file_data)
			{
				if(!empty($file_data['unique_name']))
				{
					$pafiledb_functions->pafiledb_unlink(IP_ROOT_PATH . $file_data['file_dir'] . $file_data['unique_name']);

				}
			}
		}
		else
		{
			if(!empty($file_data['unique_name']))
			{
				$pafiledb_functions->pafiledb_unlink(IP_ROOT_PATH . $file_data['file_dir'] . $file_data['unique_name']);
			}
		}


		if($mode == 'file')
		{
			$this->modified(true);
		}

		return;
	}

	function move_files($from_cat, $to_cat)
	{
		global $db;

		$sql = 'UPDATE ' . PA_FILES_TABLE . "
			SET file_catid = $to_cat
			WHERE file_catid = $from_cat";
		$db->sql_query($sql);
		$this->modified(true);
		return;
	}

	function delete_subcat($cat_id, $file_mode = 'delete', $to_cat = false)
	{
		global $db;

		if (!empty($this->subcat_rowset[$cat_id]))
		{
		foreach($this->subcat_rowset[$cat_id] as $sub_cat_id => $subcat_data)
		{
			$this->delete_subcat($sub_cat_id, $file_mode, $to_cat);

			$sql = 'DELETE FROM ' . PA_CATEGORY_TABLE . "
				WHERE cat_id = $sub_cat_id";
			$db->sql_query($sql);

			if($file_mode == 'delete')
			{
				$this->delete_files($sub_cat_id, 'category');
			}
			else
			{
				$this->move_files($sub_cat_id, $to_cat);
			}
		}
		}
		$this->modified(true);
		return;
	}

	function move_subcat($from_cat, $to_cat)
	{
		global $db;

		$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
			SET cat_parent = $to_cat
			WHERE cat_parent = $from_cat";
		$db->sql_query($sql);
		$this->modified(true);
		return;
	}

	function reorder_cat($cat_parent)
	{
		global $db;

		$sql = 'SELECT cat_id, cat_order
			FROM '. PA_CATEGORY_TABLE ."
			WHERE cat_parent = $cat_parent
			ORDER BY cat_order ASC";
		$result = $db->sql_query($sql);

		$i = 10;
		while($row = $db->sql_fetchrow($result))
		{
			$cat_id = $row['cat_id'];

			$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
					SET cat_order = $i
					WHERE cat_id = $cat_id";
			$db->sql_query($sql);
			$i += 10;
		}
	}

	function order_cat($cat_id)
	{
		global $db;

		$move = request_var('move', 15);
		$cat_parent = $this->cat_rowset[$cat_id]['cat_parent'];

		$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
				SET cat_order = cat_order + $move
				WHERE cat_id = $cat_id";
		$result = $db->sql_query($sql);
		$this->reorder_cat($cat_parent);
		$this->init();
	}

	function sync($cat_id, $init = true)
	{
		global $db;

		$cat_nav = array();
		$this->category_nav($this->cat_rowset[$cat_id]['cat_parent'], &$cat_nav);

		$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
			SET parents_data = ''
			WHERE cat_parent = " . $this->cat_rowset[$cat_id]['cat_parent'];
		$db->sql_query($sql);

		$sql = 'UPDATE ' . PA_CATEGORY_TABLE . "
				SET cat_files = '-1',
				cat_last_file_id = '0',
				cat_last_file_name = '',
				cat_last_file_time = '0'
				WHERE cat_id = '" . $cat_id . "'";
		$db->sql_query($sql);

		if($init)
		{
			$this->init();
		}
		return;
	}

	function sync_all()
	{
		foreach($this->cat_rowset as $cat_id => $void)
		{
			$this->sync($cat_id, false);
		}
		$this->init();
	}

	function update_add_file($file_id = false)
	{
		global $db, $config, $user, $pafiledb_config, $pafiledb_functions;

		$ss_upload = request_post_var('screen_shot_url', '');
		$ss_upload = empty($ss_upload) ? true : false;
		$ss_remote_url = request_post_var('screen_shot_url', '');
		$ss_local = ($_FILES['screen_shot']['tmp_name'] !== 'none') ? $_FILES['screen_shot']['tmp_name'] : '';
		$ss_name = ($_FILES['screen_shot']['name'] !== 'none') ? $_FILES['screen_shot']['name'] : '';
		$ss_size = !empty($_FILES['screen_shot']['size']) ? $_FILES['screen_shot']['size'] : '';

		$file_upload = request_post_var('download_url', '');
		$file_upload = empty($file_upload) ? true : false;
		$file_remote_url = request_post_var('download_url', '');
		$file_remote_url = str_replace(array(' '), array('%20'), $file_remote_url);
		$file_local = ($_FILES['userfile']['tmp_name'] !== 'none') ? $_FILES['userfile']['tmp_name'] : '';
		$file_realname = ($_FILES['userfile']['name'] !== 'none') ? $_FILES['userfile']['name'] : '';
		$file_size = (!empty($_FILES['userfile']['size'])) ? $_FILES['userfile']['size'] : '';
		if (($file_size == '') && ($file_remote_url != ''))
		{
			$file_remote_url_clean = str_replace(create_server_url(), '', $file_remote_url);
			//die("$file_remote_url_clean");
			$file_size = @filesize($file_remote_url_clean);
			$file_size = ($file_size == false) ? 0 : $file_size;
			//die("$file_size");
		}

		$file_type = !empty($_FILES['userfile']['type']) ? $_FILES['userfile']['type'] : '';
		$cat_id = request_var('cat_id', 0);
		$file_name = request_post_var('name', '', true);
		$file_long_desc = request_post_var('long_desc', '', true);
		$file_short_desc = request_post_var('short_desc', '', true);
		$file_short_desc = !empty($file_short_desc) ? $file_short_desc : (!empty($file_long_desc) ? substr($file_long_desc, 0, 50) . '...' : '');
		$file_author = request_post_var('author', '', true);
		$file_author = !empty($file_author) ? $file_author : (($user->data['user_id'] != ANONYMOUS) ? $user->data['username'] : '');
		$file_version = request_post_var('version', '', true);

		$file_website = request_post_var('website', '', true);
		if(!empty($file_website))
		{
			$file_website = (!preg_match('#^http[s]?:\/\/#i', $file_website)) ? 'http://' . $file_website : $file_website;
			$file_website = (preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $file_website)) ? $file_website : '';
		}

		$file_posticon = request_post_var('posticon', '');
		$file_license = request_post_var('license', 0);
		$file_pin = request_post_var('pin', 0);
		$file_ss_link = request_post_var('sshot_link', 0);
		$file_dls = request_post_var('file_download', 0);

		$file_time = time();
		$file_time_sql = '';

		if($cat_id == -1)
		{
			$this->error[] = $lang['Missing_field'];
		}

		if(empty($file_name))
		{
			$this->error[] = $lang['Missing_field'];
		}

		if(empty($file_long_desc))
		{
			$this->error[] = $lang['Missing_field'];
		}

		if(empty($file_remote_url) && empty($file_local) && !$file_id)
		{
			$this->error[] = $lang['Missing_field'];
		}

		$forbidden_extensions = array_map('trim', @explode(',', $pafiledb_config['forbidden_extensions']));
		$file_extension = $pafiledb_functions->get_extension($file_realname);
		if(in_array($file_extension, $forbidden_extensions))
		{
			$this->error[] = 'You are not allowed to upload this type of files';
		}

		if(sizeof($this->error))
		{
			return;
		}

		$physical_file_name = '';

		if($file_id)
		{
			$sql = 'SELECT file_dlurl, file_size, unique_name, file_dir, real_name, file_approved
				FROM ' . PA_FILES_TABLE . "
				WHERE file_id = '$file_id'";
			$result = $db->sql_query($sql);
			$file_data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!empty($file_remote_url) || !empty($file_local))
			{
				$file_time_sql = 'file_time = \'' . time() . '\',';
				if(!empty($file_data['unique_name']))
				{
					$pafiledb_functions->pafiledb_unlink(IP_ROOT_PATH . $file_data['file_dir'] . $file_data['unique_name']);
				}
			}
			else
			{
				$file_remote_url_old = $file_remote_url;
				$file_realname_old = $file_realname;
				$file_remote_url = $file_data['file_dlurl'];
				$physical_file_name = $file_data['unique_name'];
				$file_realname = $file_data['real_name'];
				//die($file_remote_url_old . '<br />' . $file_remote_url . '<br />' . $file_realname_old . '<br />' . $file_realname);
				if (($file_remote_url_old != $file_remote_url) || ($file_realname_old != $file_realname))
				{
					$file_time_sql = 'file_time = \'' . time() . '\',';
				}

				if(empty($file_local))
				{
					$file_upload = false;
				}
			}
		}

		if($file_upload)
		{
			$physical_file_name = $pafiledb_functions->gen_unique_name('.' . $file_extension);

			$file_info = $pafiledb_functions->upload_file($file_local, $physical_file_name, $file_size, $pafiledb_config['upload_dir']);

			if($file_info['error'])
			{
				message_die(GENERAL_ERROR, $file_info['message']);
			}
		}

		$ss_upload = request_post_var('screen_shot_url', '');
		$ss_upload = empty($ss_upload) ? true : false;
		$ss_remote_url = request_post_var('screen_shot_url', '');
		$ss_local = ($_FILES['screen_shot']['tmp_name'] !== 'none') ? $_FILES['screen_shot']['tmp_name'] : '';
		$ss_name = ($_FILES['screen_shot']['name'] !== 'none') ? $_FILES['screen_shot']['name'] : '';
		$ss_size = !empty($_FILES['screen_shot']['size']) ? $_FILES['screen_shot']['size'] : '';
		if(!empty($ss_remote_url) || !empty($ss_local))
		{
			if($ss_local)
			{
				$ss_extension = $pafiledb_functions->get_extension($ss_name);
				if(in_array($ss_extension, $forbidden_extensions))
				{
					// block unwanted screenshots
					//echo(IP_ROOT_PATH . $pafiledb_config['screenshots_dir'] . $ss_name . ' - FORBIDDEN');
					$screen_shot_url = '';
				}
				else
				{
					$screen_shot_info = $pafiledb_functions->upload_file($ss_local, $ss_name, $ss_size, $pafiledb_config['screenshots_dir']);
					if($screen_shot_info['error'])
					{
						message_die(GENERAL_ERROR, $screen_shot_info['message']);
					}
					$screen_shot_url = $screen_shot_info['url'];
				}
			}
			else
			{
				$screen_shot_url = $ss_remote_url;
			}

			if ($screen_shot_url != '')
			{
				$server_path = create_server_url();
				if (strpos($screen_shot_url, $server_path) !== false)
				{
					$ss_localpath = str_replace($server_path, '', $screen_shot_url);
					$is_image = @getimagesize($ss_localpath);
					if($is_image === false)
					{
						// remove unwanted screenshots
						@unlink(IP_ROOT_PATH . $pafiledb_config['screenshots_dir'] . $ss_name);
						$screen_shot_url = '';
					}
				}
			}
		}

		// MX addon
		if ($pafiledb_config['need_validation'] == 0)
		{
			if (!$file_id)
			{
				$file_approved = 1;
			}
			else
			{
				$file_approved = isset($file_data['file_approved'])  ? $file_data['file_approved'] : 0;
			}
		}
		elseif ($pafiledb_config['need_validation'] == 1)
		{
			if (!$file_id)
			{
				$file_approved = (($pafiledb->modules[$pafiledb->module_name]->auth[$_REQUEST['cat_id']]['auth_mod'] || $user->data['user_level'] == ADMIN) && $user->data['session_logged_in']) ? 1 : 0;
			}
			else
			{
				$file_approved = isset($file_data['file_approved']) ? $file_data['file_approved'] : 0;
			}
		}

		if(!$file_id)
		{
			$sql = 'INSERT INTO ' . PA_FILES_TABLE . " (user_id, poster_ip, file_name, file_size, unique_name, real_name, file_dir, file_desc, file_creator, file_version, file_longdesc, file_ssurl, file_sshot_link, file_dlurl, file_time, file_update_time, file_catid, file_posticon, file_license, file_dls, file_last, file_pin, file_docsurl, file_approved)
					VALUES('{$user->data['user_id']}', '" . $db->sql_escape($user->ip) . "', '" . $db->sql_escape($file_name) . "', '$file_size', '$physical_file_name', '$file_realname', '{$pafiledb_config['upload_dir']}', '" . $db->sql_escape($file_short_desc) . "', '" . $db->sql_escape($file_author) . "', '" . $db->sql_escape($file_version) . "', '" . $db->sql_escape($file_long_desc) . "', '$screen_shot_url', '$file_ss_link', '$file_remote_url', '$file_time', '$file_time', '$cat_id', '$file_posticon', '$file_license', '$file_dls', '0', '$file_pin', '$file_website', '$file_approved')";
		}
		else
		{
			$sql = "UPDATE " . PA_FILES_TABLE . "
				SET file_name = '" . $db->sql_escape($file_name) . "',
				file_size = '$file_size',
				unique_name = '$physical_file_name',
				real_name = '$file_realname',
				file_dir = '{$pafiledb_config['upload_dir']}',
				file_desc = '" . $db->sql_escape($file_short_desc) . "',
				file_longdesc = '" . $db->sql_escape($file_long_desc) . "',
				file_creator = '" . $db->sql_escape($file_author) . "',
				file_version = '" . $db->sql_escape($file_version) . "',
				file_ssurl = '$screen_shot_url',
				file_sshot_link = '$file_ss_link',
				file_dlurl = '$file_remote_url',
				" . $file_time_sql . "
				file_update_time = '$file_time',
				file_catid = '$cat_id',
				file_posticon = '$file_posticon',
				file_license = '$file_license',
				file_pin = '$file_pin',
				file_docsurl = '$file_website',
				file_dls = '$file_dls',
				file_approved = '$file_approved'
				WHERE file_id = '$file_id'";
		}
		$db->sql_query($sql);

		$this->modified(true);

		if($file_id)
		{
			return $file_id;
		}
		else
		{
			return $db->sql_nextid();
		}
	}

	function mirror_add_update($file_id, $file_upload, $file_remote_url, $file_local, $file_realname, $file_size, $file_type, $mirror_location, $mirror_id = false)
	{
		global $db, $db, $user, $pafiledb_config, $pafiledb_functions;

		if(empty($file_remote_url) && empty($file_local) && !$file_id)
		{
			$this->error[] = $lang['Missing_field'];
		}

		$forbidden_extensions = array_map('trim', @explode(',', $pafiledb_config['forbidden_extensions']));

		$file_extension = $pafiledb_functions->get_extension($file_realname);


		if(in_array($file_extension, $forbidden_extensions))
		{
			$this->error[] = 'You are not allowed to upload this type of files';
		}

		if(sizeof($this->error))
		{
			return;
		}

		$physical_file_name = '';

		if($mirror_id)
		{
			$sql = 'SELECT file_dlurl, unique_name, file_dir
				FROM ' . PA_MIRRORS_TABLE . "
				WHERE mirror_id = $mirror_id";
			$result = $db->sql_query($sql);
			$mirror_data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			if(!empty($file_remote_url) || !empty($file_local))
			{
				if(!empty($mirror_data['unique_name']))
				{
					$pafiledb_functions->pafiledb_unlink(IP_ROOT_PATH . $mirror_data['file_dir'] . $mirror_data['unique_name']);

				}
			}
			else
			{
				$file_remote_url = $mirror_data['file_dlurl'];
				$physical_file_name = $mirror_data['unique_name'];
				$file_dir = $mirror_data['file_dir'];

				if(empty($file_local))
				{
					$file_upload = false;
				}
			}
		}

		if($file_upload)
		{
			$physical_file_name = $pafiledb_functions->gen_unique_name('.' . $file_extension);

			$file_info = $pafiledb_functions->upload_file($file_local, $physical_file_name, $file_size, $pafiledb_config['upload_dir']);

			if($file_info['error'])
			{
				message_die(GENERAL_ERROR, $file_info['message']);
			}
		}

		if(!$mirror_id)
		{
			$sql = 'INSERT INTO ' . PA_MIRRORS_TABLE . " (file_id, unique_name, file_dir, file_dlurl, mirror_location)
					VALUES($file_id, '" . $db->sql_escape($physical_file_name) . "', '" . $db->sql_escape($pafiledb_config['upload_dir']) . "', '" . $db->sql_escape($file_remote_url) . "', '" . $db->sql_escape($mirror_location) . "')";
		}
		else
		{
			$sql = "UPDATE " . PA_MIRRORS_TABLE . "
				SET file_id = $file_id,
				unique_name = '" . $db->sql_escape($physical_file_name) . "',
				file_dir = '" . $db->sql_escape($pafiledb_config['upload_dir']) . "',
				file_dlurl = '" . $db->sql_escape($file_remote_url) . "',
				mirror_location = '" . $db->sql_escape($mirror_location) . "'
				WHERE mirror_id = '$mirror_id'";
		}
		$db->sql_query($sql);
	}

	function delete_mirror($mirror_id)
	{
		global $db;

		$where_sql = (is_array($mirror_id)) ? 'IN (' . implode(', ', $mirror_id) . ')' : "= $mirror_id";

		$sql = 'DELETE FROM ' . PA_MIRRORS_TABLE . " WHERE mirror_id $where_sql";
		$db->sql_query($sql);
	}

	function file_maintenance()
	{
		return false;
	}

	function file_approve($mode = 'do_approve', $file_id)
	{
		global $db;

		$file_approved = ($mode == 'do_approve') ? 1 : 0;

		$sql = 'UPDATE ' . PA_FILES_TABLE . "
			SET file_approved = $file_approved
			WHERE file_id = $file_id";
		$db->sql_query($sql);

		$this->modified(true);
	}

	function remote_filesize($url)
	{
		$sch = parse_url($url, PHP_URL_SCHEME);
		if (($sch != 'http') && ($sch != 'https') && ($sch != 'ftp') && ($sch != 'ftps'))
		{
				return false;
		}
		if (($sch == 'http') || ($sch == 'https'))
		{
			$headers = get_headers($url, 1);
			if ((!array_key_exists('Content-Length', $headers)))
			{
				return false;
			}
			return $headers['Content-Length'];
		}
		if (($sch == 'ftp') || ($sch == 'ftps'))
		{
			$server = parse_url($url, PHP_URL_HOST);
			$port = parse_url($url, PHP_URL_PORT);
			$path = parse_url($url, PHP_URL_PATH);
			$username = parse_url($url, PHP_URL_USER);
			$password = parse_url($url, PHP_URL_PASS);
			if ((!$server) || (!$path))
			{
				return false;
			}
			if (!$port)
			{
				$port = 21;
			}
			if (!$username)
			{
				$username = 'anonymous';
			}
			if (!$password)
			{
				$password = 'phpos@';
			}
			switch ($sch)
			{
				case 'ftp':
					$ftpid = ftp_connect($server, $port);
					break;
				case 'ftps':
					$ftpid = ftp_ssl_connect($server, $port);
					break;
			}
			if (!$ftpid)
			{
				return false;
			}
			$login = ftp_login($ftpid, $username, $password);
			if (!$login)
			{
				return false;
			}
			$ftpsize = ftp_size($ftpid, $path);
			ftp_close($ftpid);
			if ($ftpsize == -1)
			{
				return false;
			}
			return $ftpsize;
		}
	}

	function _pafiledb()
	{
		if($this->modified)
		{
			$this->sync_all();
		}
	}

}

?>