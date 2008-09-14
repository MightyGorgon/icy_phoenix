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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

/*
* class dlmod
*
* This class contains the following functions:
* --------------------------------------------
* $dl_mod->dl_cat_auth($cat_id); * Return the group_permissions for the given category
* $dl_mod->get_config(); * Read the basic configuration
* $dl_mod->get_ext_blacklist(); * Returns the extention blacklist, if exists
* $dl_mod->user_auth($cat_id, $perm); * Check a single auth value for the current user
* $dl_mod->files($cat_id, $sql_sort_by, $sql_order, $start, $limit); * Return the download data for each file in the given category
* $dl_mod->all_files($cat_id, $sql_sort_by, $sql_order, $extra_where, $df_id); * Return the download data for all files e.g. for the overview
* $dl_mod->mini_status_cat($parent); * Returns the mini icon for new/edited files in the given category
* $dl_mod->mini_status_file($parent, $file_id); * Returns the mini icon for one given file
* $dl_mod->index(); * Create the viewable index for all or one category
* $dl_mod->full_index($only_cat, $parent, $level, $auth_level); * Create the complete index, e.g. for admin view or downloading an unviewable file
* $dl_mod->get_todo(); * Return all data for the todo list
* $dl_mod->get_dl_overall_size(); * Return the overall file size
* $dl_mod->count_dl_approve(); * Return the number for not approved downloads filtered by user permissions
* $dl_mod->count_comment_approve(); * Return the number for not approved comments filtered by user permissions
* $dl_mod->find_latest_dl($last_data, $parent); * Return the time from the last added or edited download for the given category
* $dl_mod->get_sublevel($cat_id); * Read the sublevel for the given category
* $dl_mod->count_sublevel($cat_id); * Count the existing sub categories of a given category
* $dl_mod->get_sublevel_count($cat_id); * Read the downloads for the given sublevel and each cat in this. Will also be used by $dlmod->get_sublevel($cat_id)!
* $dl_mod->dl_nav($cat_id, $disp_art); * Create the navigation path for the given cat
* $dl_mod->dl_dropdown($cur, $parent, $level, $select_cat, $perm, $rem_cat); * Create the download dropdown for jumpbox or cat select while upload
* $dl_mod->rating_img($rating_points); * Choose the rating image for the given rating points
* $dl_mod->dl_client($client); * Returns the client from the current user
* $dl_mod->dl_size($input_value, $rnd, $out_type); * Format the size fromthe given download filesize
* $dl_mod->dl_prune_stats($cat_id, $stats_prune); * Delete all old stats data
* $dl_mod->stats_perm($cats = array()); * Manage the access permissions for statistics
* $dl_mod->cat_auth_comment_read($cat_id); * Manage the access permissions for reading comments
* $dl_mod->cat_auth_comment_post($cat_id); * Manage the access permissions for posting comments
* $dl_mod->read_exist_files(); * Read all files from the database
* $dl_mod->read_dl_dirs($download_dir, $path); * Read all existing download folders from the server
* $dl_mod->read_dl_files($download_dir, $path); * Read all existing download files from the server
* $dl_mod->read_dl_sizes($download_dir, $path); * Read all existing download filesizes from the server
* $dl_mod->readfile_chunked($filename, $retbytes); * Read the file chunked for download
* $dl_mod->dl_status($df_id); * Get the download status for the given file id
* $dl_mod->dl_auth_users($cat_id, $perm); * Read all user ids for the given download permission
* $dl_mod->bug_tracker(); * Check if the bug tracker will be enabled for at least one category
*/

class dlmod
{
	/*
	* init basic variables
	*/
	var $dl_auth = array();
	var $dl_config = array();
	var $dl_file = array();
	var $dl_file_icon = array();
	var $dl_index = array();
	var $path_dl_array = array();
	var $ext_blacklist = array();
	var $user_client = 'n/a';
	var $user_dl_update_time = 0;
	var $user_id = -1;
	var $user_level = 0;
	var $user_logged_in = 0;
	var $user_posts = 0;
	var $user_regdate = 0;
	var $user_traffic = 0;
	var $user_download_counter = 0;
	var $user_banned = 0;

	/*
	* run the class constructor
	*/
	function dlmod()
	{
		global $db, $userdata, $board_config, $enable_desc, $enable_rule;

		/*
		* define the current user
		*/
		$this->user_level = $userdata['user_level'];
		$this->user_id = $userdata['user_id'];
		$this->user_ip = $userdata['session_ip'];
		$this->user_regdate = $userdata['user_regdate'];
		$this->user_dl_update_time = $userdata['user_dl_update_time'];
		$this->user_traffic = $userdata['user_traffic'];
		$this->user_download_counter = $userdata['user_download_counter'];
		$this->user_logged_in = $userdata['session_logged_in'];
		$this->user_posts = $userdata['user_posts'];
		$this->user_client = strtolower($_SERVER['HTTP_USER_AGENT']);
		$this->username = $userdata['username'];

		/*
		* read the basic configuration
		*/
		$sql = "SELECT * FROM " . DL_CONFIG_TABLE;
		if( !($result = $db->sql_query($sql, false, 'dl_config_')) )
		{
			message_die(CRITICAL_ERROR, "Could not query download mod configuration", "", __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$this->dl_config[$row['config_name']] = $row['config_value'];
		}
		$db->sql_freeresult($result);

		/*
		* read the extention blacklist if enabled
		*/
		if ($this->dl_config['use_ext_blacklist'])
		{
			$sql = "SELECT extention FROM " . DL_EXT_BLACKLIST . "
				ORDER BY extention";
			if( !($result = $db->sql_query($sql, false, 'dl_ext_bl_')) )
			{
				message_die(CRITICAL_ERROR, "Could not query extentions blacklist", "", __LINE__, __FILE__, $sql);
			}

			while ( $row = $db->sql_fetchrow($result) )
			{
				$this->ext_blacklist[] = $row['extention'];
			}
			$db->sql_freeresult($result);
		}

		/*
		* disable the extention blacklist if it will be empty
		*/
		if (count($this->ext_blacklist))
		{
			$this->dl_config['enable_blacklist'] = true;
		}
		else
		{
			$this->dl_config['enable_blacklist'] = 0;
		}

		/*
		* set the overall traffic and categories traffic if needed (each first day of a month)
		*/
		$auto_overall_traffic_month = create_date('Ym', $this->dl_config['traffic_retime'], $board_config['board_timezone']);
		$current_traffic_month = create_date('Ym', time(), $board_config['board_timezone']);

		if ($auto_overall_traffic_month < $current_traffic_month)
		{
			$this->dl_config['traffic_retime'] = time();
			$this->dl_config['remain_traffic'] = 0;

			$sql = "UPDATE " . DL_CONFIG_TABLE . "
				SET config_value = '0'
				WHERE config_name = 'remain_traffic'";
			if (!($db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update download data', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . DL_CONFIG_TABLE . "
				SET config_value = '" . $this->dl_config['traffic_retime'] . "'
				WHERE config_name = 'traffic_retime'";
			if (!($db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update download data', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . DL_CAT_TABLE . "
				SET cat_traffic_use = 0";
			if (!($db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update categories data', '', __LINE__, __FILE__, $sql);
			}
			$db->clear_cache('dl_');
		}

		/*
		* reset download clicks (each first day of a month)
		*/
		$auto_click_reset_month = create_date('Ym', $this->dl_config['dl_click_reset_time'], $board_config['board_timezone']);
		$current_traffic_month = create_date('Ym', time(), $board_config['board_timezone']);

		if ($auto_click_reset_month < $current_traffic_month)
		{
			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET klicks = 0";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not reset monthly clicks for downloads', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . DL_CONFIG_TABLE . "
				SET config_value = ".time()."
				WHERE config_name = 'dl_click_reset_time'";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not reset monthly clicks for downloads', '', __LINE__, __FILE__, $sql);
			}
			$db->clear_cache('dl_');

			// MG DL Counter - BEGIN
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_download_counter = '0'";
			$db->sql_query($sql);
			// MG DL Counter - END
		}

		/*
		* set the user traffic if needed (each first day of the month)
		*/
		if ($this->user_id != ANONYMOUS && (intval($this->dl_config['delay_auto_traffic']) == 0 || (time() - $this->user_regdate) / 84600 > $this->dl_config['delay_auto_traffic']))
		{
			$user_auto_traffic_month = create_date('Ym', $userdata['user_dl_update_time'], $board_config['board_timezone']);
			$current_traffic_month = create_date('Ym', time(), $board_config['board_timezone']);

			if ($user_auto_traffic_month < $current_traffic_month)
			{
				$sql = "SELECT max(g.group_dl_auto_traffic) as max_traffic FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
					WHERE g.group_id = ug.group_id
						AND ug.user_id = " . $this->user_id . "
						AND g.group_single_user <> " . TRUE . "
						AND ug.user_pending <> " . TRUE;
				if ($result = $db->sql_query($sql))
				{
					while ($row = $db->sql_fetchrow($result))
					{
						$max_group_row = $row['max_traffic'];
					}
				}
				$db->sql_freeresult($result);

				$user_traffic = (intval($max_group_row) != 0) ? $max_group_row : $this->dl_config['user_dl_auto_traffic'];

				if ($user_traffic > $this->user_traffic)
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_traffic = " . $user_traffic . ", user_dl_update_time = " . time() . "
						WHERE user_id = " . $this->user_id;
					$db->sql_query($sql);

					$this->user_traffic = $user_traffic;
				}
			}
		}

		/*
		* read the index
		*/
		$cat_fields = 'id, parent, path, cat_name, sort, bbcode_uid, auth_view, auth_dl, auth_up, auth_mod, must_approve, allow_mod_desc, statistics, stats_prune, comments, cat_traffic, cat_traffic_use, allow_thumbs, auth_cread, auth_cpost, approve_comments, bug_tracker';

		if ($enable_desc)
		{
			$cat_fields .= ', description';
		}

		if ($enable_rule)
		{
			$cat_fields .= ', rules';
		}

		$sql = "SELECT $cat_fields FROM " . DL_CAT_TABLE . "
			ORDER BY parent, sort";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not read download index data', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$this->dl_index[$row['id']] = $row;

			$this->dl_index[$row['id']]['auth_view_real'] = $this->dl_index[$row['id']]['auth_view'];
			$this->dl_index[$row['id']]['auth_dl_real'] = $this->dl_index[$row['id']]['auth_dl'];
			$this->dl_index[$row['id']]['auth_up_real'] = $this->dl_index[$row['id']]['auth_up'];
			$this->dl_index[$row['id']]['auth_mod_real'] = $this->dl_index[$row['id']]['auth_mod'];

			// check the default cat permissions
			if ($this->dl_index[$row['id']]['auth_view'] == 1 || ($this->dl_index[$row['id']]['auth_view'] == 2 && $this->user_logged_in))
			{
				$this->dl_index[$row['id']]['auth_view'] = true;
			}
			else
			{
				$this->dl_index[$row['id']]['auth_view'] = false;
			}

			if ($this->dl_index[$row['id']]['auth_dl'] == 1 || ($this->dl_index[$row['id']]['auth_dl'] == 2 && $this->user_logged_in))
			{
				$this->dl_index[$row['id']]['auth_dl'] = true;
			}
			else
			{
				$this->dl_index[$row['id']]['auth_dl'] = false;
			}

			if ($this->dl_index[$row['id']]['auth_up'] == 1 || ($this->dl_index[$row['id']]['auth_up'] == 2 && $this->user_logged_in))
			{
				$this->dl_index[$row['id']]['auth_up'] = true;
			}
			else
			{
				$this->dl_index[$row['id']]['auth_up'] = false;
			}

			if ($this->dl_index[$row['id']]['auth_mod'] == 1 || ($this->dl_index[$row['id']]['auth_mod'] == 2 && $this->user_logged_in))
			{
				$this->dl_index[$row['id']]['auth_mod'] = true;
			}
			else
			{
				$this->dl_index[$row['id']]['auth_mod'] = false;
			}
		}

		$db->sql_freeresult($result);

		/*
		* count all files per category
		*/
		$sql = "SELECT count(id) AS total, cat FROM " . DOWNLOADS_TABLE . "
			GROUP BY cat";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not count the download files', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$this->dl_index[$row['cat']]['total'] = $row['total'];
		}
		$db->sql_freeresult($result);

		/*
		* get the user permissions
		*/
		$auth_perm = $auth_cat = $cat_auth_array = $group_ids = $group_perm_ids = array();

		$sql = "SELECT * FROM " . DL_AUTH_TABLE;
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not read group permissions for downloads', '', __LINE__, __FILE__, $sql);
		}

		$total_perms = $db->sql_numrows($result);

		if ($total_perms)
		{
			while ($row = $db->sql_fetchrow($result))
			{
				$cat_id = $row['cat_id'];
				$group_id = $row['group_id'];

				$auth_cat[] = $cat_id;
				$group_perm_ids[] = $group_id;

				$auth_perm[$cat_id][$group_id]['auth_view'] = $row['auth_view'];
				$auth_perm[$cat_id][$group_id]['auth_dl'] = $row['auth_dl'];
				$auth_perm[$cat_id][$group_id]['auth_up'] = $row['auth_up'];
				$auth_perm[$cat_id][$group_id]['auth_mod'] = $row['auth_mod'];
			}
			$db->sql_freeresult($result);

			if ($total_perms > 1)
			{
				$auth_cat = array_unique($auth_cat);
				sort($auth_cat);
			}

			$sql = "SELECT g.group_id FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
				WHERE g.group_id IN (" . implode(', ', $group_perm_ids) . ")
					AND g.group_id = ug.group_id
					AND ug.user_id = " . $this->user_id . "
					AND g.group_single_user <> " . TRUE . "
					AND ug.user_pending <> " . TRUE;
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not fetch group ids for user', '', __LINE__, __FILE__, $sql);
			}

			while ($row = $db->sql_fetchrow($result))
			{
				$group_ids[] = $row['group_id'];
			}
			$db->sql_freeresult($result);

			for ($i = 0; $i < count($auth_cat); $i++)
			{
				$auth_view = $auth_dl = $auth_up = $auth_mod = 0;
				$cat = $auth_cat[$i];

				for ($j = 0; $j < count($group_ids); $j++)
				{
					$user_group = $group_ids[$j];

					if ($auth_perm[$cat][$user_group]['auth_view'] == true)
					{
						$auth_view = true;
					}
					if ($auth_perm[$cat][$user_group]['auth_dl'] == true)
					{
						$auth_dl = true;
					}
					if ($auth_perm[$cat][$user_group]['auth_up'] == true)
					{
						$auth_up = true;
					}
					if ($auth_perm[$cat][$user_group]['auth_mod'] == true)
					{
						$auth_mod = true;
					}
				}

				$cat_auth_array[$cat]['auth_view'] = $auth_view;
				$cat_auth_array[$cat]['auth_dl'] = $auth_dl;
				$cat_auth_array[$cat]['auth_up'] = $auth_up;
				$cat_auth_array[$cat]['auth_mod'] = $auth_mod;
			}
		}
		else
		{
			$db->sql_freeresult($result);
		}

		$this->dl_auth = $cat_auth_array;

		/*
		* preset all files
		*/
		$sql = "SELECT change_time, add_time, id, cat, file_name, file_size, extern, free, file_traffic, klicks FROM " . DOWNLOADS_TABLE . "
				WHERE approve = " . TRUE;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not preset the download files', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$change_time = $row['change_time'];
			$add_time = $row['add_time'];

			$count_new = ($change_time == $add_time && ((time() - $change_time)) / 86400 <= $this->dl_config['dl_new_time'] && $this->dl_config['dl_new_time'] > 0) ? 1 : 0;
			$count_edit = ($change_time != $add_time && ((time() - $change_time) / 86400) <= $this->dl_config['dl_edit_time'] && $this->dl_config['dl_edit_time'] > 0) ? 1 : 0;

			$this->dl_file_icon['new'][$row['cat']][$row['id']] = $count_new;
			$this->dl_file_icon['new_sum'][$row['cat']] += $count_new;
			$this->dl_file_icon['edit'][$row['cat']][$row['id']] = $count_edit;
			$this->dl_file_icon['edit_sum'][$row['cat']] += $count_edit;
			$this->dl_file[$row['id']] = $row;
		}
		$db->sql_freeresult($result);

		/*
		* get ban status for current user
		*/
		$sql_guests = (!$this_>user_logged_in) ? " OR guests = 1 " : '';
		$sql = "SELECT ban_id FROM " . DL_BANLIST_TABLE . "
			WHERE user_id = " . $this->user_id . "
				OR user_ip = '" . $this->user_ip . "'
				OR user_agent LIKE '%" . $this->dl_client() . "%'
				OR username = '" . str_replace("'", "%", $this->username) . "'
				$sql_guests";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not fetch banlist', '', __LINE__, __FILE__, $sql);
		}
		$total_ban_ids = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if ($total_ban_ids)
		{
			$this->user_banned = true;
		}

		return;
	}

	function dl_cat_auth($cat_id)
	{
		$cat_perm = array();

		$cat_perm['auth_view'] = intval($this->dl_auth[$cat_id]['auth_view']);
		$cat_perm['auth_dl'] = intval($this->dl_auth[$cat_id]['auth_dl']);
		$cat_perm['auth_mod'] = intval($this->dl_auth[$cat_id]['auth_mod']);
		$cat_perm['auth_up'] = intval($this->dl_auth[$cat_id]['auth_up']);
		$cat_perm['auth_cread'] = intval($this->dl_auth[$cat_id]['auth_cread']);
		$cat_perm['auth_cpost'] = intval($this->dl_auth[$cat_id]['auth_cpost']);

		return $cat_perm;
	}

	function get_config()
	{

		$this->dl_config['dl_path'] = IP_ROOT_PATH . $this->dl_config['download_dir'];

		return $this->dl_config;
	}

	function get_ext_blacklist()
	{
		return $this->ext_blacklist;
	}

	function user_auth($cat_id, $perm)
	{
		if ($this->dl_auth[$cat_id][$perm] || $this->dl_index[$cat_id][$perm] || $this->user_level == ADMIN)
		{
			return true;
		}

		return false;
	}

	function files($cat_id, $sql_sort_by, $sql_order, $start, $limit, $sql_fields = '*')
	{
		global $db;

		$dl_files = array();

		$sql = "SELECT $sql_fields FROM " . DOWNLOADS_TABLE . "
			WHERE cat = $cat_id
				AND approve = " . TRUE . "
			ORDER BY $sql_sort_by $sql_order
			LIMIT $start, $limit";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not preset the download files', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$dl_files[] = $row;
		}
		$db->sql_freeresult($result);

		return $dl_files;
	}

	function all_files($cat_id = 0, $sql_sort_by = '', $sql_order = 'ASC', $extra_where = '', $df_id = 0, $modcp = 0, $sql_fields = '*')
	{
		global $db;

		$dl_files = array();

		$sql = "SELECT $sql_fields FROM " . DOWNLOADS_TABLE;
		$sql .= ($modcp) ? " WHERE approve IN (0, 1)" : " WHERE approve = " . TRUE;
		$sql .= ($cat_id) ? " AND cat = $cat_id " : '';
		$sql .= ($df_id) ? " AND id = $df_id " : '';
		$sql .= ($extra_where) ? " $extra_where " : '';
		$sql .= ($sql_sort_by) ? " ORDER BY $sql_sort_by $sql_order" : '';
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not preset the download files', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$dl_files[] = $row;
		}
		$db->sql_freeresult($result);

		return ($df_id) ? $dl_files[0] : $dl_files;
	}

	function mini_status_cat($cur, $parent, $flag = 0)
	{
		$mini_status_icon = array();

		foreach($this->dl_index as $cat_id => $value)
		{
			if ($cat_id == $parent && !$flag)
			{
				if ($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN)
				{
					if ($this->dl_index[$cat_id]['total'])
					{
						$mini_status_icon[$cur]['new'] += $this->dl_file_icon['new_sum'][$cat_id];
						$mini_status_icon[$cur]['edit'] += $this->dl_file_icon['edit_sum'][$cat_id];
					}
				}

				$mini_icon = $this->mini_status_cat($cur, $cat_id, 1);
				$mini_status_icon[$cur]['new'] += $mini_icon[$cur]['new'];
				$mini_status_icon[$cur]['edit'] += $mini_icon[$cur]['edit'];
			}

			if ($this->dl_index[$cat_id]['parent'] == $parent && $flag)
			{
				if ($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN)
				{
					if ($this->dl_index[$cat_id]['total'])
					{
						$mini_status_icon[$cur]['new'] += $this->dl_file_icon['new_sum'][$cat_id];
						$mini_status_icon[$cur]['edit'] += $this->dl_file_icon['edit_sum'][$cat_id];
					}
				}
			}
		}

		return $mini_status_icon;
	}

	function mini_status_file($parent, $file_id)
	{
		global $images;

		if ($this->dl_file_icon['new'][$parent][$file_id])
		{
			$mini_icon_img = '<img src="' . $images['Dl_new'] . '" alt="" title="" border="0" />';
		}
		elseif ($this->dl_file_icon['edit'][$parent][$file_id])
		{
			$mini_icon_img = '<img src="' . $images['Dl_edit'] . '" alt="" title="" border="0" />';
		}
		else
		{
			$mini_icon_img = '';
		}

		return $mini_icon_img;
	}

	function index($parent = 0)
	{

		$tree_dl = array();

		foreach($this->dl_index as $cat_id => $value)
		{
			if (($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN) && $this->dl_index[$cat_id]['parent'] == $parent)
			{
				$tree_dl[$cat_id]['description'] = $this->dl_index[$cat_id]['description'];
				$tree_dl[$cat_id]['rules'] = $this->dl_index[$cat_id]['rules'];
				$tree_dl[$cat_id]['cat_name'] = $this->dl_index[$cat_id]['cat_name'];
				$tree_dl[$cat_id]['bbcode_uid'] = $this->dl_index[$cat_id]['bbcode_uid'];
				$tree_dl[$cat_id]['nav_path'] = append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id);
				$tree_dl[$cat_id]['cat_path'] = $this->dl_index[$cat_id]['path'];
				$tree_dl[$cat_id]['total'] = $this->dl_index[$cat_id]['total'];

				$tree_dl[$cat_id]['auth_view_real'] = $this->dl_index[$cat_id]['auth_view_real'];
				$tree_dl[$cat_id]['auth_dl_real'] = $this->dl_index[$cat_id]['auth_dl_real'];
				$tree_dl[$cat_id]['auth_up_real'] = $this->dl_index[$cat_id]['auth_up_real'];
				$tree_dl[$cat_id]['auth_mod_real'] = $this->dl_index[$cat_id]['auth_mod_real'];
				$tree_dl[$cat_id]['auth_view'] = $this->dl_index[$cat_id]['auth_view'];
				$tree_dl[$cat_id]['auth_dl'] = $this->dl_index[$cat_id]['auth_dl'];
				$tree_dl[$cat_id]['auth_up'] = $this->dl_index[$cat_id]['auth_up'];
				$tree_dl[$cat_id]['auth_mod'] = $this->dl_index[$cat_id]['auth_mod'];
				$tree_dl[$cat_id]['auth_cread'] = $this->dl_index[$cat_id]['auth_cread'];
				$tree_dl[$cat_id]['auth_cpost'] = $this->dl_index[$cat_id]['auth_cpost'];

				$tree_dl[$cat_id]['allow_mod_desc'] = $this->dl_index[$cat_id]['allow_mod_desc'];
				$tree_dl[$cat_id]['must_approve'] = $this->dl_index[$cat_id]['must_approve'];

				$tree_dl[$cat_id]['comments'] = $this->dl_index[$cat_id]['comments'];
				$tree_dl[$cat_id]['statistics'] = $this->dl_index[$cat_id]['statistics'];
				$tree_dl[$cat_id]['stats_prune'] = $this->dl_index[$cat_id]['stats_prune'];

				$tree_dl[$cat_id]['cat_traffic'] = $this->dl_index[$cat_id]['cat_traffic'];
				$tree_dl[$cat_id]['cat_traffic_use'] = $this->dl_index[$cat_id]['cat_traffic_use'];

				$tree_dl[$cat_id]['allow_thumbs'] = $this->dl_index[$cat_id]['allow_thumbs'];
				$tree_dl[$cat_id]['approve_comments'] = $this->dl_index[$cat_id]['approve_comments'];

				$tree_dl[$cat_id]['bug_tracker'] = $this->dl_index[$cat_id]['bug_tracker'];

				$tree_dl[$cat_id]['sublevel'] = $this->get_sublevel($cat_id);
			}
		}
		return $tree_dl;
	}

	function full_index($only_cat = 0, $parent = 0, $level = 0, $auth_level = 0)
	{
		global $tree_dl;

		if ($only_cat)
		{
			$tree_dl[$only_cat]['description'] = $this->dl_index[$only_cat]['description'];
			$tree_dl[$only_cat]['rules'] = $this->dl_index[$only_cat]['rules'];
			$tree_dl[$only_cat]['cat_name'] = $this->dl_index[$only_cat]['cat_name'];
			$tree_dl[$only_cat]['bbcode_uid'] = $this->dl_index[$only_cat]['bbcode_uid'];
			$tree_dl[$only_cat]['nav_path'] = append_sid('downloads.' . PHP_EXT . "?cat=$only_cat");
			$tree_dl[$only_cat]['cat_path'] = $this->dl_index[$only_cat]['path'];
			$tree_dl[$only_cat]['total'] = $this->dl_index[$only_cat]['total'];

			$tree_dl[$only_cat]['auth_view_real'] = $this->dl_index[$only_cat]['auth_view_real'];
			$tree_dl[$only_cat]['auth_dl_real'] = $this->dl_index[$only_cat]['auth_dl_real'];
			$tree_dl[$only_cat]['auth_up_real'] = $this->dl_index[$only_cat]['auth_up_real'];
			$tree_dl[$only_cat]['auth_mod_real'] = $this->dl_index[$only_cat]['auth_mod_real'];
			$tree_dl[$only_cat]['auth_view'] = $this->dl_index[$only_cat]['auth_view'];
			$tree_dl[$only_cat]['auth_dl'] = $this->dl_index[$only_cat]['auth_dl'];
			$tree_dl[$only_cat]['auth_up'] = $this->dl_index[$only_cat]['auth_up'];
			$tree_dl[$only_cat]['auth_mod'] = $this->dl_index[$only_cat]['auth_mod'];
			$tree_dl[$only_cat]['auth_cread'] = $this->dl_index[$only_cat]['auth_cread'];
			$tree_dl[$only_cat]['auth_cpost'] = $this->dl_index[$only_cat]['auth_cpost'];

			$tree_dl[$only_cat]['allow_mod_desc'] = $this->dl_index[$only_cat]['allow_mod_desc'];
			$tree_dl[$only_cat]['must_approve'] = $this->dl_index[$only_cat]['must_approve'];

			$tree_dl[$only_cat]['comments'] = $this->dl_index[$only_cat]['comments'];
			$tree_dl[$only_cat]['statistics'] = $this->dl_index[$only_cat]['statistics'];
			$tree_dl[$only_cat]['stats_prune'] = $this->dl_index[$only_cat]['stats_prune'];

			$tree_dl[$only_cat]['cat_traffic'] = $this->dl_index[$only_cat]['cat_traffic'];
			$tree_dl[$only_cat]['cat_traffic_use'] = $this->dl_index[$only_cat]['cat_traffic_use'];

			$tree_dl[$only_cat]['allow_thumbs'] = $this->dl_index[$only_cat]['allow_thumbs'];
			$tree_dl[$only_cat]['approve_comments'] = $this->dl_index[$only_cat]['approve_comments'];

			$tree_dl[$only_cat]['bug_tracker'] = $this->dl_index[$only_cat]['bug_tracker'];
		}
		else
		{
			foreach($this->dl_index as $cat_id => $value)
			{
				if ($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN)
				{
					/*
					* $auth level will return the following data
					* 0 = Default values for each category
					* 1 = IDs from all viewable categories
					* 2 = IDs from moderated categories
					* 3 = IDs from upload categories
					*/

					if ($auth_level == 1 && $this->dl_index[$cat_id]['id'])
					{
						$access_ids[] = $cat_id;
					}
					elseif ($auth_level == 2 && $this->dl_index[$cat_id]['id'] && ($this->dl_index[$cat_id]['auth_mod'] || $this->dl_auth[$cat_id]['auth_mod'] || $this->user_level == ADMIN))
					{
						$access_ids[] = $cat_id;
					}
					elseif ($auth_level == 3 && $this->dl_index[$cat_id]['id'] && ($this->dl_index[$cat_id]['auth_up'] || $this->dl_auth[$cat_id]['auth_up'] || $this->user_level == ADMIN))
					{
						$access_ids[] = $cat_id;
					}
					elseif ($this->dl_index[$cat_id]['parent'] == $parent)
					{
						$seperator = '';
						for ($i = 0; $i < $level; $i++)
						{
							$seperator .= ($this->dl_index[$cat_id]['parent'] != 0) ? '&nbsp;&nbsp;|___&nbsp;' : '';
						}

						$tree_dl[$cat_id]['description'] = $this->dl_index[$cat_id]['description'];
						$tree_dl[$cat_id]['rules'] = $this->dl_index[$cat_id]['rules'];
						$tree_dl[$cat_id]['cat_name'] = $seperator.$this->dl_index[$cat_id]['cat_name'];
						$tree_dl[$cat_id]['bbcode_uid'] = $this->dl_index[$cat_id]['bbcode_uid'];
						$tree_dl[$cat_id]['nav_path'] = append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id);
						$tree_dl[$cat_id]['cat_path'] = $this->dl_index[$cat_id]['path'];
						$tree_dl[$cat_id]['total'] = intval($this->dl_index[$cat_id]['total']);
						$tree_dl[$cat_id]['parent'] = intval($this->dl_index[$cat_id]['parent']);

						$tree_dl[$cat_id]['auth_view_real'] = $this->dl_index[$cat_id]['auth_view_real'];
						$tree_dl[$cat_id]['auth_dl_real'] = $this->dl_index[$cat_id]['auth_dl_real'];
						$tree_dl[$cat_id]['auth_up_real'] = $this->dl_index[$cat_id]['auth_up_real'];
						$tree_dl[$cat_id]['auth_mod_real'] = $this->dl_index[$cat_id]['auth_mod_real'];
						$tree_dl[$cat_id]['auth_view'] = $this->dl_index[$cat_id]['auth_view'];
						$tree_dl[$cat_id]['auth_dl'] = $this->dl_index[$cat_id]['auth_dl'];
						$tree_dl[$cat_id]['auth_up'] = $this->dl_index[$cat_id]['auth_up'];
						$tree_dl[$cat_id]['auth_mod'] = $this->dl_index[$cat_id]['auth_mod'];
						$tree_dl[$cat_id]['auth_cread'] = $this->dl_index[$cat_id]['auth_cread'];
						$tree_dl[$cat_id]['auth_cpost'] = $this->dl_index[$cat_id]['auth_cpost'];

						$tree_dl[$cat_id]['allow_mod_desc'] = $this->dl_index[$cat_id]['allow_mod_desc'];
						$tree_dl[$cat_id]['must_approve'] = $this->dl_index[$cat_id]['must_approve'];

						$tree_dl[$cat_id]['comments'] = $this->dl_index[$cat_id]['comments'];
						$tree_dl[$cat_id]['statistics'] = $this->dl_index[$cat_id]['statistics'];
						$tree_dl[$cat_id]['stats_prune'] = $this->dl_index[$cat_id]['stats_prune'];

						$tree_dl[$cat_id]['cat_traffic'] = $this->dl_index[$cat_id]['cat_traffic'];
						$tree_dl[$cat_id]['cat_traffic_use'] = $this->dl_index[$cat_id]['cat_traffic_use'];

						$tree_dl[$cat_id]['allow_thumbs'] = $this->dl_index[$cat_id]['allow_thumbs'];
						$tree_dl[$cat_id]['approve_comments'] = $this->dl_index[$cat_id]['approve_comments'];

						$tree_dl[$cat_id]['bug_tracker'] = $this->dl_index[$cat_id]['bug_tracker'];

						$level++;
						$this->full_index(0, $cat_id, $level, 0);
						$level--;
					}
				}
			}
		}

		return ($auth_level) ? $access_ids : $tree_dl;
	}

	function get_todo()
	{

		$todo = array();

		$dl_files = $this->all_files(0, '', 'ASC', "AND todo <> '' AND todo IS NOT NULL");
		$dl_cats = $this->full_index(0, 0, 0, 1);

		for ($i = 0; $i < count($dl_files); $i++)
		{
			$cat_id = $dl_files[$i]['cat'];
			if (in_array($cat_id, $dl_cats))
			{
				$file_link = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id='.$dl_files[$i]['id']);
				$file_name = $dl_files[$i]['description'];
				$hack_version = ( $dl_files[$i]['hack_version'] != '' ) ? ' ' . $dl_files[$i]['hack_version'] : '';

				$todo_text = $dl_files[$i]['todo'];
				$todo_text = str_replace("\n", "\n<br />\n", $todo_text);

				$todo['file_link'][] = $file_link;
				$todo['file_name'][] = $file_name;
				$todo['hack_version'][] = $hack_version;
				$todo['todo'][] = $todo_text;
			}
		}

		return $todo;
	}

	function get_dl_overall_size()
	{

		$overall_size = 0;

		$dl_files = array();
		$dl_files = $this->all_files();
		$dl_cats = array();
		$dl_cats = $this->full_index(0, 0, 0, 1);

		if (count($dl_cats))
		{
			for ($i = 0; $i < count($dl_files); $i++)
			{
				$cat_id = $dl_files[$i]['cat'];
				if (in_array($cat_id, $dl_cats))
				{
					if ($dl_files[$i]['file_size'] >= 0)
					{
						$overall_size += $dl_files[$i]['file_size'];
					}
				}
			}
		}

		return $overall_size;
	}

	function count_dl_approve()
	{
		global $db;

		if (!$this->user_logged_in)
		{
			return 0;
		}

		$access_cats = array();
		$access_cats = $this->full_index(0, 0, 0, 2);
		if (!$access_cats && $this->user_level != ADMIN)
		{
			return 0;
		}

		$sql_access_cats = ($this->user_level == ADMIN) ? '' : ' AND cat IN (' . implode(',', $access_cats) . ')';

		$sql = "SELECT count(id) AS total FROM " . DOWNLOADS_TABLE . "
			WHERE approve = 0
				$sql_access_cats";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query unapproved downloads', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		return $row['total'];
	}

	function count_comments_approve()
	{
		global $db;

		if (!$this->user_logged_in)
		{
			return 0;
		}

		$access_cats = array();
		$access_cats = $this->full_index(0, 0, 0, 2);
		if (!$access_cats && $this->user_level != ADMIN)
		{
			return 0;
		}

		$sql_access_cats = ($this->user_level == ADMIN) ? '' : ' AND cat_id IN (' . implode(',', $access_cats) . ')';

		$sql = "SELECT count(dl_id) AS total FROM " . DL_COMMENTS_TABLE . "
			WHERE approve = 0
				$sql_access_cats";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query unapproved comments', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		return $row['total'];
	}

	function find_latest_dl($last_data, $parent, $last_dl_time = array())
	{
		foreach($this->dl_index as $cat_id => $value)
		{
			if ($this->dl_index[$cat_id]['parent'] == $parent)
			{
				if ($last_data[$cat_id]['change_time'] > $last_dl_time['change_time'] && ($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN))
				{
					$last_dl_time['change_time'] = $last_data[$cat_id]['change_time'];
					$last_dl_time['cat_id'] = $cat_id;
				}

				$last_temp = $this->find_latest_dl($last_data, $cat_id, $last_dl_time);

				if ($last_temp['change_time'] > $last_dl_time['change_time'])
				{
					$last_dl_time['change_time'] = $last_temp['change_time'];
					$last_dl_time['cat_id'] = $last_temp['cat_id'];
				}
			}
		}

		return $last_dl_time;
	}

	function get_sublevel($parent)
	{

		$sublevel = array();
		$i = 0;

		foreach($this->dl_index as $cat_id => $value)
		{
			if (($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN) && $this->dl_index[$cat_id]['parent'] == $parent)
			{
				$sublevel['cat_name'][$i] = $this->dl_index[$cat_id]['cat_name'];
				$sublevel['total'][$i] = $this->dl_index[$cat_id]['total'];
				$sublevel['cat_id'][$i] = $this->dl_index[$cat_id]['id'];
				$sublevel['cat_path'][$i] = append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id);
				$sublevel['cat_sub'][$i] = $cat_id;
				$i++;
			}
		}

		return $sublevel;
	}

	function count_sublevel($parent)
	{
		$sublevel = 0;

		foreach($this->dl_index as $cat_id => $value)
		{
			if (($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN) && $this->dl_index[$cat_id]['parent'] == $parent)
			{
				$sublevel++;
			}
		}

		return $sublevel;
	}

	function get_sublevel_count($parent = 0)
	{
		$sublevel_count = 0;

		foreach($this->dl_index as $cat_id => $value)
		{
			if ($this->dl_index[$cat_id]['parent'] == $parent && ($this->dl_index[$cat_id]['auth_view'] || $this->dl_auth[$cat_id]['auth_view'] || $this->user_level == ADMIN))
			{
				$sublevel_count += $this->dl_index[$cat_id]['total'];
				$sublevel_count += $this->get_sublevel_count($cat_id);
			}
		}

		return $sublevel_count;
	}

	function dl_nav($parent, $disp_art)
	{
		global $board_config, $path_dl_array, $bbcode;

		$cat_id = $this->dl_index[$parent]['id'];
		$temp_url = append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id);
		$temp_title = $this->dl_index[$parent]['cat_name'];
		$temp_title = str_replace('&nbsp;&nbsp;|', '', $temp_title);
		$temp_title = str_replace('___&nbsp;', '', $temp_title);
		$cat_bbcode_uid = $this->dl_index[$parent]['bbcode_uid'];

		if ($disp_art == 'url')
		{
			/*
			$temp_title = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $temp_title);
			$temp_title = ($board_config['allow_bbcode'] && $cat_bbcode_uid != '') ? $bbcode->parse($temp_title, $cat_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $temp_title);
			$temp_title = ($board_config['allow_smilies']) ? smilies_pass($temp_title) : $temp_title;
			*/
			//$bbcode->allow_html = ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? true : false;
			$bbcode->allow_html = false;
			$bbcode->allow_bbcode = ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode'] ) ? true : false;
			$bbcode->allow_smilies = ( $userdata['user_allowsmile'] && $board_config['allow_smilies'] ) ? true : false;
			$temp_title = $bbcode->parse($temp_title, $cat_bbcode_uid);
			$seperator = '&nbsp;&raquo;&nbsp;';
		}
		else
		{
			$temp_title = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $temp_title);
			$seperator = '&nbsp;&raquo;&nbsp;';
		}

		if (($this->dl_index[$parent]['auth_view'] || $this->dl_auth[$parent]['auth_view'] || $this->user_level == ADMIN) && $disp_art == 'url')
		{
			$path_dl_array[] = $seperator.'<a href="' . $temp_url . '">' . $temp_title . '</a>';
		}
		else
		{
			$path_dl_array[] = $seperator.$temp_title;
		}

		if ($this->dl_index[$parent]['parent'] != 0)
		{
			$this->dl_nav($this->dl_index[$parent]['parent'], $disp_art);
		}

		$path_dl = '';

		for ($i = count($path_dl_array); $i >= 0 ; $i--)
		{
			$path_dl .= $path_dl_array[$i];
		}

		return $path_dl;
	}

	function dl_dropdown($parent = 0, $level = 0, $select_cat = 0, $perm, $rem_cat = 0)
	{
		foreach($this->dl_index as $cat_id => $value)
		{
			if ($this->dl_index[$cat_id]['parent'] == $parent)
			{
				if ($this->dl_index[$cat_id][$perm] || $this->dl_auth[$cat_id][$perm] || $this->user_level == ADMIN)
				{
					$cat_name = $this->dl_index[$cat_id]['cat_name'];

					$seperator = '';

					if ($this->dl_index[$cat_id]['parent'] != 0)
					{
						for($i = 0; $i < $level; $i++)
						{
							$seperator .= '&nbsp;&nbsp;|';
						}
						$seperator .= '___&nbsp;';
					}

					if ($perm == 'auth_up' || $rem_cat)
					{
						$status = ($select_cat == $cat_id) ? 'selected="selected"' : '';
					}
					else
					{
						$status = '';
					}

					if ($rem_cat != $cat_id)
					{
						$catlist .= '<option value="' . $cat_id . '" '.$status.'>'.$seperator.$cat_name.'</option>';
					}
				}

				$level++;
				$catlist .= $this->dl_dropdown($cat_id, $level, $select_cat, $perm, $rem_cat);
				$level--;
			}
		}

		return $catlist;
	}

	function rating_img($rating_points)
	{
		global $images;

		$rate_points = ceil($rating_points);
		$rate_image = '';

		for ($i = 0; $i < 10; $i++)
		{
			$j = $i + 1;
			$rate_image .= ($j <= $rate_points ) ? '<img src="' . $images['Dl_rate_yes'] . '" border="0" alt="" title="" />' : '<img src="' . $images['Dl_rate_no'] . '" border="0" alt="" title="" />';
		}

		return $rate_image;
	}

	function dl_client()
	{
		$client = $this->user_client;

		if (strstr($client, 'gecko'))
		{
			if (strstr($client, 'safari'))
			{
				$browser_name = 'Safari';
			}
			elseif (strstr($client, 'camino'))
			{
				$browser_name = 'Camino';
			}
			elseif (strstr($client, 'epiphany'))
			{
				$browser_name = 'Epiphany';
			}
			elseif (strstr($client, 'firefo'))
			{
				$browser_name = 'Firefox';
			}
			elseif (strstr($client, 'konqueror'))
			{
				$browser_name = 'Konqueror';
			}
			elseif (strstr($client, 'netscape'))
			{
				$browser_name = 'Netscape';
			}
			elseif (strstr($client, 'seamonk'))
			{
				$browser_name = 'SeaMonkey';
			}
			elseif (strstr($client, 'cback'))
			{
				$browser_name = 'CBACK';
			}
			else
			{
				$browser_name = 'Mozilla';
			}
		}
		elseif (strstr($client, 'opera'))
		{
			$browser_name = 'Opera';
		}
		elseif (strstr($client, 'abolimba'))
		{
			$browser_name = 'Abolimba';
		}
		elseif (strstr($client, 'msie'))
		{
			if (strstr($client, 'maxthon'))
			{
				$browser_name = 'Maxthon';
			}
			else
			{
				$browser_name = 'Internet Explorer';
			}
		}
		elseif (strstr($client,'voyager'))
		{
			$browser_name = 'Voyager';
		}
		elseif (strstr($client,'lynx'))
		{
			$browser_name = 'Lynx';
		}
		else
		{
			$browser_name = 'n/a';
		}

		return $browser_name;
	}

	function dl_size($input_value, $rnd = 2, $out_type = 'combine')
	{
		global $lang;

		if ($input_value < 1024)
		{
			$output_value = $input_value;
			$output_desc = '&nbsp;&nbsp;' . $lang['Dl_Bytes'];
		}
		elseif ($input_value < 1048576)
		{
			$output_value = $input_value / 1024;
			$output_desc = '&nbsp;' . $lang['Dl_KB'];
		}
		elseif ($input_value < 1073741824)
		{
			$output_value = $input_value / 1048576;
			$output_desc = '&nbsp;' . $lang['Dl_MB'];
		}
		else
		{
			$output_value = $input_value / 1073741824;
			$output_desc = '&nbsp;' . $lang['Dl_GB'];
		}

		$output_value = round($output_value, $rnd);

		$data_out = ($out_type == 'combine') ? $output_value . $output_desc : array('size_out' => $output_value, 'range' => $output_desc);

		return $data_out;
	}

	function dl_prune_stats($cat_id, $stats_prune)
	{
		global $db;

		$stats_prune--;

		if ($stats_prune)
		{
			$sql = "SELECT time_stamp FROM " . DL_STATS_TABLE . "
				WHERE cat_id = $cat_id
				ORDER BY time_stamp DESC
				LIMIT $stats_prune, 1";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not prune statistics for download category', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$first_time_stamp = $row['time_stamp'];

			if ($first_time_stamp)
			{
				$sql = "DELETE FROM " . DL_STATS_TABLE . "
					WHERE time_stamp <= $first_time_stamp
						AND cat_id = $cat_id";
				if (!($db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not prune statistics for download category', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		return true;
	}

	function stats_perm()
	{
		global $cat_id;

		$stats_view = 0;

		switch($this->dl_config['dl_stats_perm'])
		{
			case 0:
				$stats_view = true;
				break;

			case 1:
				if ($this->user_logged_in)
				{
					$stats_view = true;
				}
				break;

			case 2:
				if ($this->user_auth($cat_id, 'auth_mod'))
				{
					$stats_view = true;
				}
				break;

			case 3:
				if ($this->user_level == ADMIN)
				{
					$stats_view = true;
				}
				break;

			default:
				$stats_view = 0;
		}

		return $stats_view;
	}

	function cat_auth_comment_read($cat_id)
	{
		$auth_cread = 0;

		switch($this->dl_index[$cat_id]['auth_cread'])
		{
			case 0:
				$auth_cread = true;
				break;

			case 1:
				if ($this->user_logged_in)
				{
					$auth_cread = true;
				}
				break;

			case 2:
				if ($this->user_auth($cat_id, 'auth_mod'))
				{
					$auth_cread = true;
				}
				break;

			case 3:
				if ($this->user_level == ADMIN)
				{
					$auth_cread = true;
				}
				break;

			default:
				$auth_cread = 0;
		}

		return $auth_cread;
	}

	function cat_auth_comment_post($cat_id)
	{
		$auth_cpost = 0;

		switch($this->dl_index[$cat_id]['auth_cpost'])
		{
			case 0:
				$auth_cpost = true;
				break;

			case 1:
				if ($this->user_logged_in)
				{
					$auth_cpost = true;
				}
				break;

			case 2:
				if ($this->user_auth($cat_id, 'auth_mod'))
				{
					$auth_cpost = true;
				}
				break;

			case 3:
				if ($this->user_level == ADMIN)
				{
					$auth_cpost = true;
				}
				break;

			default:
				$auth_cpost = 0;
		}

		return $auth_cpost;
	}

	function read_exist_files()
	{
		$dl_files = $this->all_files();

		$exist_files = array();

		for ($i = 0; $i < count($dl_files); $i++)
		{
			$exist_files[] = $dl_files[$i]['file_name'];
		}

		return $exist_files;
	}

	function read_dl_dirs($download_dir, $path = '')
	{
		global $lang, $cur, $unas_files;

		$folders = '';

		$dl_dir = substr($download_dir, 0, strlen($download_dir)-1);

		@$dir = opendir($download_dir . $path);

		while (false !== ($file=@readdir($dir)))
		{
			if ($file{0} != ".")
			{
				if(is_dir($download_dir . $path . '/' . $file))
				{
					$temp_dir = $dl_dir . $path . '/' . $file;
					$temp_dir = str_replace(IP_ROOT_PATH, '', $temp_dir);
					$folders .= ('/'.$cur != $path . '/' . $file) ? '<option value="' . $dl_dir . $path . '/' . $file . '/">' . $lang['Dl_move'] . ' &raquo; ' . $temp_dir . '/</option>' : '';
					$folders .= $this->read_dl_dirs($download_dir, $path . '/' . $file);
				}
			}
		}

		closedir($dir);

		return $folders;
	}

	function read_dl_files($download_dir, $path = '')
	{
		global $unas_files;

		$files = '';

		$dl_dir = ($path) ? $download_dir : substr($download_dir, 0, strlen($download_dir)-1);

		@$dir = opendir($dl_dir . $path);

		while (false !== ($file=@readdir($dir)))
		{
			if ($file{0} != ".")
			{
				$files .= (in_array($file, $unas_files)) ? $path . '/' . $file . '|' : '';
				$files .= $this->read_dl_files($download_dir, $path . '/' . $file);
			}
		}

		@closedir($dir);

		return $files;
	}

	function read_dl_sizes($download_dir, $path = '')
	{
		$file_size = 0;

		$dl_dir = substr($download_dir, 0, strlen($download_dir)-1);

		@$dir = opendir($dl_dir . $path);

		while (false !== ($file=@readdir($dir)))
		{
			if ($file{0} != ".")
			{
				$file_size += sprintf("%u", @filesize($dl_dir . $path . '/' . $file));
				$file_size += $this->read_dl_sizes($download_dir, $path . '/' . $file);
			}
		}

		@closedir($dir);

		return $file_size;
	}

	// Added by suggestion from Neverbirth. Thx to him!!!
	function readfile_chunked($filename, $retbytes = true)
	{
		$chunksize = 1048576;
		$buffer = '';
		$cnt =0;
		$handle = fopen($filename, 'rb');

		if ($handle === false)
		{
			return false;
		}

		while (!feof($handle))
		{
			$buffer = fread($handle, $chunksize);
			echo $buffer;
			if ($retbytes)
			{
				$cnt += strlen($buffer);
			}
		}

		$status = fclose($handle);

		if ($retbytes && $status)
		{
			return $cnt;
		}

		return $status;
	}

	function dl_status($df_id)
	{
		global $images, $lang;

		$lang['Dl_red_explain_alt'] = sprintf($lang['Dl_red_explain_alt'], $this->dl_config['dl_posts']);

		$cat_id = $this->dl_file[$df_id]['cat'];
		$cat_auth = array();
		$cat_auth = $this->dl_cat_auth($cat_id);
		$index = array();
		$index = $this->full_index($cat_id);
		$status = '';
		$file_name = '';
		$auth_dl = 0;

		$file_name = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '">'.$this->dl_file[$df_id]['file_name'] . '</a>';
		$file_detail = $this->dl_file[$df_id]['file_name'];

		if ($this->user_banned)
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_acp_banlist'] . '" border="0" alt="' . $lang['Dl_banned'] . '" title="' . $lang['Dl_banned'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_acp_banlist'] . '" border="0" alt="' . $lang['Dl_banned'] . '" title="' . $lang['Dl_banned'] . '" />';
			$auth_dl = 0;
			return array('status' => $status, 'file_name' => $file_detail, 'auth_dl' => $auth_dl, 'file_detail' => $file_detail, 'status_detail' => $status_detail);
		}

		$dl_traffic_flag = false;
		if ($this->user_logged_in && (intval($this->user_traffic) > $this->dl_file[$df_id]['file_size']) && !$this->dl_file[$df_id]['extern'])
		{
			$dl_traffic_flag = true;
		}
		// MG DL Counter - BEGIN
		if (($this->dl_config['user_download_limit_flag'] == true) && ($this->user_level != ADMIN) && ($this->user_level != MOD) && ($this->user_download_counter >= $this->dl_config['user_download_limit']))
		{
			$dl_traffic_flag = false;
		}
		// MG DL Counter - END

		if ($dl_traffic_flag == true)
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_yellow'] . '" border="0" alt="' . $lang['Dl_yellow_explain'] . '" title="' . $lang['Dl_yellow_explain'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_yellow'] . '" border="0" alt="' . $lang['Dl_yellow_explain'] . '" title="' . $lang['Dl_yellow_explain'] . '" />';
			$auth_dl = true;
		}
		else
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_red'] . '" border="0" alt="' . $lang['Dl_red_explain_alt'] . '" title="' . $lang['Dl_red_explain_alt'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_red'] . '" border="0" alt="' . $lang['Dl_red_explain_alt'] . '" title="' . $lang['Dl_red_explain_alt'] . '" />';
			$auth_dl = 0;
		}

		if ($this->user_posts < $this->dl_config['dl_posts'] && !$this->dl_file[$df_id]['extern'] && !$this->dl_file[$df_id]['free'])
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_red'] . '" border="0" alt="' . $lang['Dl_red_explain_alt'] . '" title="' . $lang['Dl_red_explain_alt'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_red'] . '" border="0" alt="' . $lang['Dl_red_explain_alt'] . '" title="' . $lang['Dl_red_explain_alt'] . '" />';
			$auth_dl = 0;
		}

		if ($this->dl_file[$df_id]['free'] == 1)
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_green'] . '" border="0" alt="' . $lang['Dl_green_explain'] . '" title="' . $lang['Dl_green_explain'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_green'] . '" border="0" alt="' . $lang['Dl_green_explain'] . '" title="' . $lang['Dl_green_explain'] . '" />';
			$auth_dl = true;
		}

		if ($this->dl_file[$df_id]['free'] == 2)
		{
			if ($this->dl_config['icon_free_for_reg'] || (!$this->dl_config['icon_free_for_reg'] && $this->user_logged_in))
			{
				$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_white'] . '" border="0" alt="' . $lang['Dl_white_explain'] . '" title="' . $lang['Dl_white_explain'] . '" /></a>';
				$status_detail = '<img src="' . $images['Dl_white'] . '" border="0" alt="' . $lang['Dl_white_explain'] . '" title="' . $lang['Dl_white_explain'] . '" />';
			}

			if ($this->user_logged_in)
			{
				$auth_dl = true;
			}
			else
			{
				$auth_dl = 0;
			}
		}

		if (!$cat_auth['auth_dl'] && !$index[$cat_id]['auth_dl'] && $this->user_level != ADMIN)
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_red'] . '" border="0" alt="' . $lang['Dl_red_explain_perm'] . '" title="' . $lang['Dl_red_explain_perm'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_red'] . '" border="0" alt="' . $lang['Dl_red_explain_perm'] . '" title="' . $lang['Dl_red_explain_perm'] . '" />';
			$auth_dl = 0;
		}

		if ($this->dl_file[$df_id]['file_traffic'] && $this->dl_file[$df_id]['klicks'] * $this->dl_file[$df_id]['file_size'] >= $this->dl_file[$df_id]['file_traffic'])
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_blue'] . '" border="0" alt="' . $lang['Dl_blue_explain_file'] . '" title="' . $lang['Dl_blue_explain_file'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_blue'] . '" border="0" alt="' . $lang['Dl_blue_explain_file'] . '" title="' . $lang['Dl_blue_explain_file'] . '" />';
			$auth_dl = 0;
		}

		if (($this->dl_config['overall_traffic'] - $this->dl_config['remain_traffic'] <= 0) || ($index[$cat_id]['cat_traffic'] && ($index[$cat_id]['cat_traffic'] - $index[$cat_id]['cat_traffic_use'] <= 0)))
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '"><img src="' . $images['Dl_blue'] . '" border="0" alt="' . $lang['Dl_blue_explain'] . '" title="' . $lang['Dl_blue_explain'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_blue'] . '" border="0" alt="' . $lang['Dl_blue_explain'] . '" title="' . $lang['Dl_blue_explain'] . '" />';
			$auth_dl = 0;
		}

		if ($this->dl_file[$df_id]['extern'])
		{
			$status = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id).'" target="_'.$this->dl_file[$df_id]['file_name'] . '"><img src="' . $images['Dl_grey'] . '" border="0" alt="' . $lang['Dl_grey_explain'] . '" title="' . $lang['Dl_grey_explain'] . '" /></a>';
			$status_detail = '<img src="' . $images['Dl_grey'] . '" border="0" alt="' . $lang['Dl_grey_explain'] . '" title="' . $lang['Dl_grey_explain'] . '" />';
			$file_name = '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id).'" target="_blank">' . $lang['Dl_extern'] . '</a>';
			$auth_dl = TRUE;
		}

		return array('status' => $status, 'file_name' => $file_name, 'auth_dl' => $auth_dl, 'file_detail' => $file_detail, 'status_detail' => $status_detail);
	}

	function dl_auth_users($cat_id, $perm)
	{
		global $db;

		$user_ids = '';

		if ($this->dl_index[$cat_id][$perm])
		{
			$sql = "SELECT user_id FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS . "
					AND user_id <> " . $this->user_id;
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not fetch user ids for notification', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "SELECT group_id FROM " . DL_AUTH_TABLE . "
				WHERE cat_id = $cat_id
					AND $perm = " . TRUE ."
				GROUP BY group_id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not read group permissions for downloads', '', __LINE__, __FILE__, $sql);
			}

			$total_group_perms = $db->sql_numrows($result);
			if (!$total_group_perms)
			{
				$db->sql_freeresult($result);
				return '';
			}

			$group_ids = array();

			while ($row = $db->sql_fetchrow($result))
			{
				$group_ids[] = $row['group_id'];
			}
			$db->sql_freeresult($result);

			$groups = implode(', ', $group_ids);

			$sql = "SELECT ug.user_id FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
				WHERE g.group_id = ug.group_id
					AND ug.user_id <> " . $this->user_id . "
					AND g.group_id IN ($groups)
					AND g.group_single_user <> " . TRUE . "
					AND ug.user_pending <> " . TRUE;
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not fetch group_ids for user', '', __LINE__, __FILE__, $sql);
			}

		}

		while ($row = $db->sql_fetchrow($result))
		{
			$user_ids .= ($user_ids == '') ? $row['user_id'] : ', '.$row['user_id'];
		}
		$db->sql_freeresult($result);

		return $user_ids;
	}

	function bug_tracker()
	{
		$bug_tracker = false;

		foreach($this->dl_index as $cat_id => $value)
		{
			if ($this->dl_index[$cat_id]['bug_tracker'])
			{
				$bug_tracker = true;
				break;
			}
		}

		return $bug_tracker;
	}
}

?>