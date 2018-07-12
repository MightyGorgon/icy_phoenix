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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

/**
* All Attachment Functions only needed in Admin
*/

/**
* Per Forum based Extension Group Permissions (Encode Number) -> Theoretically up to 158 Forums saveable. :)
* We are using a base of 64, but splitting it to one-char and two-char numbers. :)
*/
function auth_pack($auth_array)
{
	$one_char_encoding = '#';
	$two_char_encoding = '.';
	$one_char = $two_char = false;
	$auth_cache = '';

	for ($i = 0; $i < sizeof($auth_array); $i++)
	{
		$val = base64_pack(intval($auth_array[$i]));
		if (strlen($val) == 1 && !$one_char)
		{
			$auth_cache .= $one_char_encoding;
			$one_char = true;
		}
		elseif (strlen($val) == 2 && !$two_char)
		{
			$auth_cache .= $two_char_encoding;
			$two_char = true;
		}

		$auth_cache .= $val;
	}

	return $auth_cache;
}

/**
* Reverse the auth_pack process
*/
function auth_unpack($auth_cache)
{
	$one_char_encoding = '#';
	$two_char_encoding = '.';

	$auth = array();
	$auth_len = 1;

	for ($pos = 0; $pos < strlen($auth_cache); $pos += $auth_len)
	{
		$forum_auth = substr($auth_cache, $pos, 1);
		if ($forum_auth == $one_char_encoding)
		{
			$auth_len = 1;
			continue;
		}
		elseif ($forum_auth == $two_char_encoding)
		{
			$auth_len = 2;
			$pos--;
			continue;
		}

		$forum_auth = substr($auth_cache, $pos, $auth_len);
		$forum_id = base64_unpack($forum_auth);
		$auth[] = intval($forum_id);
	}
	return $auth;
}

/**
* Called from admin_users.php and admin_groups.php in order to process Quota Settings (admin/admin_users.php:admin/admin_groups.php)
*/
function attachment_quota_settings($admin_mode, $submit = false, $mode)
{
	global $template, $db, $lang, $lang, $config;

	// Make sure constants got included
	include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'includes/constants.' . PHP_EXT);

	if (!intval($config['allow_ftp_upload']))
	{
		if ($config['upload_dir'][0] == '/' || ($config['upload_dir'][0] != '/' && $config['upload_dir'][1] == ':'))
		{
			$upload_dir = $config['upload_dir'];
		}
		else
		{
			$upload_dir = IP_ROOT_PATH . $config['upload_dir'];
		}
	}
	else
	{
		$upload_dir = $config['download_path'];
	}

	$user_id = 0;

	if ($admin_mode == 'user')
	{
		// We overwrite submit here... to be sure
		$submit = (isset($_POST['submit'])) ? true : false;

		if (!$submit && $mode != 'save')
		{
			$user_id = request_var(POST_USERS_URL, 0);
			$u_name = request_var('username', '');

			if (!$user_id && !$u_name)
			{
				if (!defined('STATUS_404')) define('STATUS_404', true);
				message_die(GENERAL_MESSAGE, 'NO_USER');
			}

			if ($user_id)
			{
				$this_userdata['user_id'] = $user_id;
			}
			else
			{
				// Get userdata is handling the sanitizing of username
				$this_userdata = get_userdata($_POST['username'], true);
			}

			$user_id = (int) $this_userdata['user_id'];
		}
		else
		{
			$user_id = request_var('id', 0);

			if (!$user_id)
			{
				if (!defined('STATUS_404')) define('STATUS_404', true);
				message_die(GENERAL_MESSAGE, 'NO_USER');
			}
		}
	}

	if ($admin_mode == 'user' && !$submit && $mode != 'save')
	{
		// Show the contents
		$sql = 'SELECT quota_limit_id, quota_type FROM ' . QUOTA_TABLE . '
			WHERE user_id = ' . (int) $user_id;
		$result = $db->sql_query($sql);

		$pm_quota = $upload_quota = 0;
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				if ($row['quota_type'] == QUOTA_UPLOAD_LIMIT)
				{
					$upload_quota = $row['quota_limit_id'];
				}
				elseif ($row['quota_type'] == QUOTA_PM_LIMIT)
				{
					$pm_quota = $row['quota_limit_id'];
				}
			}
			while ($row = $db->sql_fetchrow($result));
		}
		else
		{
			// Set Default Quota Limit
			$upload_quota = $config['default_upload_quota'];
			$pm_quota = $config['default_pm_quota'];
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'S_SELECT_UPLOAD_QUOTA' => quota_limit_select('user_upload_quota', $upload_quota),
			'S_SELECT_PM_QUOTA' => quota_limit_select('user_pm_quota', $pm_quota),
			'L_UPLOAD_QUOTA' => $lang['Upload_quota'],
			'L_PM_QUOTA' => $lang['Pm_quota'])
		);
	}

	if ($admin_mode == 'user' && $submit && $_POST['deleteuser'])
	{
		process_quota_settings($admin_mode, $user_id, QUOTA_UPLOAD_LIMIT, 0);
		process_quota_settings($admin_mode, $user_id, QUOTA_PM_LIMIT, 0);
	}
	elseif ($admin_mode == 'user' && $submit && $mode == 'save')
	{
		// Get the contents
		$upload_quota = request_var('user_upload_quota', 0);
		$pm_quota = request_var('user_pm_quota', 0);

		process_quota_settings($admin_mode, $user_id, QUOTA_UPLOAD_LIMIT, $upload_quota);
		process_quota_settings($admin_mode, $user_id, QUOTA_PM_LIMIT, $pm_quota);
	}

	if ($admin_mode == 'group' && $mode == 'newgroup')
	{
		return;
	}

	if (($admin_mode == 'group') && !$submit && (isset($_POST['edit']) || isset($_GET['edit'])))
	{
		// Get group id again, we do not trust phpBB here, Mods may be installed ;)
		$group_id = request_var(POST_GROUPS_URL, 0);

		// Show the contents
		$sql = 'SELECT quota_limit_id, quota_type FROM ' . QUOTA_TABLE . '
			WHERE group_id = ' . (int) $group_id;
		$result = $db->sql_query($sql);

		$pm_quota = $upload_quota = 0;
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				if ($row['quota_type'] == QUOTA_UPLOAD_LIMIT)
				{
					$upload_quota = $row['quota_limit_id'];
				}
				elseif ($row['quota_type'] == QUOTA_PM_LIMIT)
				{
					$pm_quota = $row['quota_limit_id'];
				}
			}
			while ($row = $db->sql_fetchrow($result));
		}
		else
		{
			// Set Default Quota Limit
			$upload_quota = $config['default_upload_quota'];
			$pm_quota = $config['default_pm_quota'];
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'S_SELECT_UPLOAD_QUOTA' => quota_limit_select('group_upload_quota', $upload_quota),
			'S_SELECT_PM_QUOTA' => quota_limit_select('group_pm_quota', $pm_quota),
			'L_UPLOAD_QUOTA' => $lang['Upload_quota'],
			'L_PM_QUOTA' => $lang['Pm_quota'])
		);
	}

	if ($admin_mode == 'group' && $submit && isset($_POST['group_delete']))
	{
		$group_id = request_var(POST_GROUPS_URL, 0);

		process_quota_settings($admin_mode, $group_id, QUOTA_UPLOAD_LIMIT, 0);
		process_quota_settings($admin_mode, $group_id, QUOTA_PM_LIMIT, 0);
	}
	elseif ($admin_mode == 'group' && $submit)
	{
		$group_id = request_var(POST_GROUPS_URL, 0);

		// Get the contents
		$upload_quota = request_var('group_upload_quota', 0);
		$pm_quota = request_var('group_pm_quota', 0);

		process_quota_settings($admin_mode, $group_id, QUOTA_UPLOAD_LIMIT, $upload_quota);
		process_quota_settings($admin_mode, $group_id, QUOTA_PM_LIMIT, $pm_quota);
	}

}

/**
* Set/Change Quotas
*/
function process_quota_settings($mode, $id, $quota_type, $quota_limit_id = 0)
{
	global $db;

	$id = (int) $id;
	$quota_type = (int) $quota_type;
	$quota_limit_id = (int) $quota_limit_id;

	if ($mode == 'user')
	{
		if (!$quota_limit_id)
		{
			$sql = 'DELETE FROM ' . QUOTA_TABLE . "
				WHERE user_id = $id
					AND quota_type = $quota_type";
		}
		else
		{
			// Check if user is already entered
			$sql = 'SELECT user_id
				FROM ' . QUOTA_TABLE . "
				WHERE user_id = $id
					AND quota_type = $quota_type";
			$result = $db->sql_query($sql);

			if ($db->sql_numrows($result) == 0)
			{
				$sql_ary = array(
					'user_id' => (int) $id,
					'group_id' => 0,
					'quota_type' => (int) $quota_type,
					'quota_limit_id'=> (int) $quota_limit_id
				);

				$sql = 'INSERT INTO ' . QUOTA_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary);
			}
			else
			{
				$sql = 'UPDATE ' . QUOTA_TABLE . "
					SET quota_limit_id = $quota_limit_id
					WHERE user_id = $id
						AND quota_type = $quota_type";
			}
			$db->sql_freeresult($result);
		}
		$result = $db->sql_query($sql);
	}
	elseif ($mode == 'group')
	{
		if (!$quota_limit_id)
		{
			$sql = 'DELETE FROM ' . QUOTA_TABLE . "
				WHERE group_id = $id
					AND quota_type = $quota_type";
			$result = $db->sql_query($sql);
		}
		else
		{
			// Check if user is already entered
			$sql = 'SELECT group_id
				FROM ' . QUOTA_TABLE . "
				WHERE group_id = $id
					AND quota_type = $quota_type";
			$result = $db->sql_query($sql);

			if ($db->sql_numrows($result) == 0)
			{
				$sql = 'INSERT INTO ' . QUOTA_TABLE . " (user_id, group_id, quota_type, quota_limit_id)
					VALUES (0, $id, $quota_type, $quota_limit_id)";
			}
			else
			{
				$sql = 'UPDATE ' . QUOTA_TABLE . " SET quota_limit_id = $quota_limit_id
					WHERE group_id = $id AND quota_type = $quota_type";
			}
			$db->sql_query($sql);
		}
	}
}

/**
* sort multi-dimensional Array
*/
function sort_multi_array ($sort_array, $key, $sort_order, $pre_string_sort = 0)
{
	$last_element = sizeof($sort_array) - 1;

	if (!$pre_string_sort)
	{
		$string_sort = (!is_numeric($sort_array[$last_element-1][$key])) ? true : false;
	}
	else
	{
		$string_sort = $pre_string_sort;
	}

	for ($i = 0; $i < $last_element; $i++)
	{
		$num_iterations = $last_element - $i;

		for ($j = 0; $j < $num_iterations; $j++)
		{
			$next = 0;

			// do checks based on key
			$switch = false;
			if (!$string_sort)
			{
				if (($sort_order == 'DESC' && intval($sort_array[$j][$key]) < intval($sort_array[$j + 1][$key])) || ($sort_order == 'ASC' && intval($sort_array[$j][$key]) > intval($sort_array[$j + 1][$key])))
				{
					$switch = true;
				}
			}
			else
			{
				if (($sort_order == 'DESC' && strcasecmp($sort_array[$j][$key], $sort_array[$j + 1][$key]) < 0) || ($sort_order == 'ASC' && strcasecmp($sort_array[$j][$key], $sort_array[$j + 1][$key]) > 0))
				{
					$switch = true;
				}
			}

			if ($switch)
			{
				$temp = $sort_array[$j];
				$sort_array[$j] = $sort_array[$j + 1];
				$sort_array[$j + 1] = $temp;
			}
		}
	}

	return $sort_array;
}

/**
* See if a post or pm really exist
*/
function entry_exists($attach_id)
{
	global $db;

	$attach_id = (int) $attach_id;

	if (!$attach_id)
	{
		return false;
	}

	$sql = 'SELECT post_id, privmsgs_id
		FROM ' . ATTACHMENTS_TABLE . "
		WHERE attach_id = $attach_id";
	$db->sql_query($sql);
	$ids = $db->sql_fetchrowset($result);
	$num_ids = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$exists = false;

	for ($i = 0; $i < $num_ids; $i++)
	{
		if (intval($ids[$i]['post_id']) != 0)
		{
			$sql = 'SELECT post_id
				FROM ' . POSTS_TABLE . '
				WHERE post_id = ' . intval($ids[$i]['post_id']);
		}
		else if (intval($ids[$i]['privmsgs_id']) != 0)
		{
			$sql = 'SELECT privmsgs_id
				FROM ' . PRIVMSGS_TABLE . '
				WHERE privmsgs_id = ' . intval($ids[$i]['privmsgs_id']);
		}
		$db->sql_query($sql);

		if (($db->sql_numrows($result)) > 0)
		{
			$exists = true;
			break;
		}
		$db->sql_freeresult($result);
	}

	return $exists;
}

/**
* Collect all Attachments in Filesystem
*/
function collect_attachments()
{
	global $upload_dir, $config;

	$file_attachments = array();

	if (!intval($config['allow_ftp_upload']))
	{
		if ($dir = @opendir($upload_dir))
		{
			while ($file = @readdir($dir))
			{
				if (($file != 'index.php') && ($file != 'index.html') && ($file != '.htaccess') && !is_dir($upload_dir . '/' . $file) && !is_link($upload_dir . '/' . $file))
				{
					$file_attachments[] = trim($file);
				}
			}

			closedir($dir);
		}
		else
		{
			message_die(GENERAL_ERROR, 'Is Safe Mode Restriction in effect? The Attachment Mod seems to be unable to collect the Attachments within the upload Directory. Try to use FTP Upload to circumvent this error. Another reason could be that the directory ' . $upload_dir . ' does not exist.');
		}
	}
	else
	{
		$conn_id = attach_init_ftp();

		$file_listing = array();

		$file_listing = @ftp_rawlist($conn_id, '');

		if (!$file_listing)
		{
			message_die(GENERAL_ERROR, 'Unable to get Raw File Listing. Please be sure the LIST command is enabled at your FTP Server.');
		}

		for ($i = 0; $i < sizeof($file_listing); $i++)
		{
			if (preg_match("/([-d])[rwxst-]{9}.* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9]) ([0-9]{2}:[0-9]{2}) (.+)/", $file_listing[$i], $regs))
			{
				if ($regs[1] == 'd')
				{
					$dirinfo[0] = 1;	// Directory == 1
				}
				$dirinfo[1] = $regs[2]; // Size
				$dirinfo[2] = $regs[3]; // Date
				$dirinfo[3] = $regs[4]; // Filename
				$dirinfo[4] = $regs[5]; // Time
			}

			if (($dirinfo[0] != 1) && ($dirinfo[4] != 'index.php') && ($dirinfo[4] != 'index.html') && ($dirinfo[4] != '.htaccess'))
			{
				$file_attachments[] = trim($dirinfo[4]);
			}
		}

		@ftp_quit($conn_id);
	}

	return $file_attachments;
}

/**
* Returns the filesize of the upload directory in human readable format
*/
function get_formatted_dirsize()
{
	global $config, $upload_dir, $lang;

	$upload_dir_size = 0;

	if (!intval($config['allow_ftp_upload']))
	{
		if ($dirname = @opendir($upload_dir))
		{
			while ($file = @readdir($dirname))
			{
				if (($file != 'index.php') && ($file != 'index.html') && ($file != '.htaccess') && !is_dir($upload_dir . '/' . $file) && !is_link($upload_dir . '/' . $file))
				{
					$upload_dir_size += @filesize($upload_dir . '/' . $file);
				}
			}
			@closedir($dirname);
		}
		else
		{
			$upload_dir_size = $lang['Not_available'];
			return $upload_dir_size;
		}
	}
	else
	{
		$conn_id = attach_init_ftp();

		$file_listing = array();

		$file_listing = @ftp_rawlist($conn_id, '');

		if (!$file_listing)
		{
			$upload_dir_size = $lang['Not_available'];
			return $upload_dir_size;
		}

		for ($i = 0; $i < sizeof($file_listing); $i++)
		{
			if (preg_match("/([-d])[rwxst-]{9}.* ([0-9]*) ([a-zA-Z]+[0-9: ]*[0-9]) ([0-9]{2}:[0-9]{2}) (.+)/", $file_listing[$i], $regs))
			{
				if ($regs[1] == 'd')
				{
					$dirinfo[0] = 1;	// Directory == 1
				}
				$dirinfo[1] = $regs[2]; // Size
				$dirinfo[2] = $regs[3]; // Date
				$dirinfo[3] = $regs[4]; // Filename
				$dirinfo[4] = $regs[5]; // Time
			}

			if (($dirinfo[0] != 1) && ($dirinfo[4] != 'index.php') && ($dirinfo[4] != 'index.html') && ($dirinfo[4] != '.htaccess'))
			{
				$upload_dir_size += $dirinfo[1];
			}
		}

		@ftp_quit($conn_id);
	}

	if ($upload_dir_size >= 1048576)
	{
		$upload_dir_size = round($upload_dir_size / 1048576 * 100) / 100 . ' ' . $lang['MB'];
	}
	else if ($upload_dir_size >= 1024)
	{
		$upload_dir_size = round($upload_dir_size / 1024 * 100) / 100 . ' ' . $lang['KB'];
	}
	else
	{
		$upload_dir_size = $upload_dir_size . ' ' . $lang['Bytes'];
	}

	return $upload_dir_size;
}

/*
* Build SQL-Statement for the search feature
*/
function search_attachments($order_by, &$total_rows)
{
	global $db, $lang;

	$where_sql = array();

	// Get submitted Vars
	$search_vars = array('search_keyword_fname', 'search_keyword_comment', 'search_author', 'search_size_smaller', 'search_size_greater', 'search_count_smaller', 'search_count_greater', 'search_days_greater', 'search_forum', 'search_cat');

	for ($i = 0; $i < sizeof($search_vars); $i++)
	{
		$$search_vars[$i] = request_var($search_vars[$i], '');
	}

	// Author name search
	if ($search_author != '')
	{
		// Bring in line with 2.0.x expected username
		$search_author = addslashes(html_entity_decode($search_author));
		$search_author = stripslashes(phpbb_clean_username($search_author));

		// Prepare for directly going into sql query
		$search_author = str_replace('*', '%', $db->sql_escape(utf8_clean_string($search_author)));

		// We need the post_id's, because we want to query the Attachment Table
		$sql = get_users_sql($search_author, true, false, false, false);
		$result = $db->sql_query($sql);

		$matching_userids = '';
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				$matching_userids .= (($matching_userids != '') ? ', ' : '') . intval($row['user_id']);
			}
			while ($row = $db->sql_fetchrow($result));

			$db->sql_freeresult($result);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_attach_search_match']);
		}

		$where_sql[] = ' (t.user_id_1 IN (' . $matching_userids . ')) ';
	}

	// Search Keyword
	if ($search_keyword_fname != '')
	{
		$match_word = str_replace('*', '%', $search_keyword_fname);
		$where_sql[] = " (a.real_filename LIKE '" . $db->sql_escape($match_word) . "') ";
	}

	if ($search_keyword_comment != '')
	{
		$match_word = str_replace('*', '%', $search_keyword_comment);
		$where_sql[] = " (a.comment LIKE '" . $db->sql_escape($match_word) . "') ";
	}

	// Search Download Count
	if ($search_count_smaller != '' || $search_count_greater != '')
	{
		if ($search_count_smaller != '')
		{
			$where_sql[] = ' (a.download_count < ' . (int) $search_count_smaller . ') ';
		}
		else if ($search_count_greater != '')
		{
			$where_sql[] = ' (a.download_count > ' . (int) $search_count_greater . ') ';
		}
	}

	// Search Filesize
	if ($search_size_smaller != '' || $search_size_greater != '')
	{
		if ($search_size_smaller != '')
		{
			$where_sql[] = ' (a.filesize < ' . (int) $search_size_smaller . ') ';
		}
		else if ($search_size_greater != '')
		{
			$where_sql[] = ' (a.filesize > ' . (int) $search_size_greater . ') ';
		}
	}

	// Search Attachment Time
	if ($search_days_greater != '')
	{
		$where_sql[] = ' (a.filetime < ' . (time() - ((int) $search_days_greater * 86400)) . ') ';
	}

	// Search Forum
	if ($search_forum)
	{
		$where_sql[] = ' (p.forum_id = ' . intval($search_forum) . ') ';
	}

	// Search Cat... nope... sorry :(

	$sql = 'SELECT a.*, t.post_id, p.post_time, p.topic_id
		FROM ' . ATTACHMENTS_TABLE . ' t, ' . ATTACHMENTS_DESC_TABLE . ' a, ' . POSTS_TABLE . ' p WHERE ';

	if (sizeof($where_sql) > 0)
	{
		$sql .= implode('AND', $where_sql) . ' AND ';
	}

	$sql .= 't.post_id = p.post_id AND a.attach_id = t.attach_id ';

	$total_rows_sql = $sql;

	$sql .= $order_by;
	$result = $db->sql_query($sql);
	$attachments = $db->sql_fetchrowset($result);
	$num_attach = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ($num_attach == 0)
	{
		message_die(GENERAL_MESSAGE, $lang['No_attach_search_match']);
	}

	$result = $db->sql_query($total_rows_sql);
	$total_rows = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	return $attachments;
}

/**
* perform LIMIT statement on arrays
*/
function limit_array($array, $start, $pagelimit)
{
	// array from start - start+pagelimit
	$limit = (sizeof($array) < ($start + $pagelimit)) ? sizeof($array) : $start + $pagelimit;

	$limit_array = array();

	for ($i = $start; $i < $limit; $i++)
	{
		$limit_array[] = $array[$i];
	}

	return $limit_array;
}

?>