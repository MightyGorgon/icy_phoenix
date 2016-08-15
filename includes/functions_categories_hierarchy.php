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
* Ptirhiik (admin@rpgnet-fr.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//--------------------------------------------------------------------------------------------------
//
// $tree : designed to get all the hierarchy
// ------
//
//	indexes :
//		- id : full designation : ie Root, f3, c20
//		- idx : rank order
//
//	$tree['keys'][id] => idx,
//	$tree['auth'][id] => auth_value array : ie tree['auth'][id]['auth_view'],
//	$tree['sub'][id] => array of sub-level ids,
//	$tree['main'][idx] => parent id,
//	$tree['type'][idx] => type of the row, can be 'c' for categories or 'f' for forums,
//	$tree['id'][idx] => value of the row id : forum_id for cats, forum_id for forums,
//	$tree['data'][idx] => db table row,
//	$tree['unread_topics'][idx] => boolean value to true if there is new topics
//--------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------
// get_object_lang() : return the translated value of field depending on row type in the hierarchy
//--------------------------------------------------------------------------------------------------
function get_object_lang($cur, $field, $all = false)
{
	global $config, $lang, $tree;
	$res = '';
	$CH_this = (isset($tree['keys'][$cur]) ? $tree['keys'][$cur] : '');
	$type = (isset($tree['type'][$CH_this]) ? $tree['type'][$CH_this] : '');
	if ($cur == 'Root')
	{
		switch($field)
		{
			case 'name':
				$res = sprintf($lang['Forum_Index'], isset($lang[$config['sitename']]) ? $lang[$config['sitename']] : $config['sitename']);
				break;
			case 'desc':
				$res = isset($lang[$config['site_desc']]) ? $lang[$config['site_desc']] : $config['site_desc'];
				break;
		}
	}
	else
	{
		switch($field)
		{
			case 'name':
				$field = 'forum_name';
				break;
			case 'desc':
				$field = 'forum_desc';
				break;
		}
		$res = ($tree['auth'][$cur]['auth_view'] || $all) ? stripslashes($tree['data'][$CH_this][$field]) : '';
		if (isset($lang[$res]))
		{
			$res = $lang[$res];
		}
	}
	return $res;
}

//--------------------------------------------------------------------------------------------------
// cache_tree() : buid the cache tree file
//--------------------------------------------------------------------------------------------------
function cache_tree_output()
{
	global $tree, $user;

	if (!defined('CACHE_TREE'))
	{
		return;
	}

	// template
	include_once(IP_ROOT_PATH . 'includes/template.' . PHP_EXT);
	$template = new Template(IP_ROOT_PATH);

	$template->set_filenames(array('def_tree' => 'includes/def_tree_def.tpl'));

	$template->assign_vars(array(
		'TIME' => gmdate('Y-m-d H:i:s') . ' (GMT)',
		'USERNAME' => $user->data['username'],
		)
	);

	// keys
	$cells = array();
	@reset($tree['keys']);
	while (list($key, $value) = @each($tree['keys']))
	{
		$cells[] = sprintf("'%s' => %s", $key, $value);
	}
	$keys = @implode(', ', $cells);

	// types
	$cells = array();
	for ($i = 0; $i < sizeof($tree['type']); $i++)
	{
		$cells[] = sprintf("'%s'", $tree['type'][$i]);
	}
	$types = @implode(', ', $cells);

	// ids
	$cells = array();
	for ($i = 0; $i < sizeof($tree['id']); $i++)
	{
		$cells[] = sprintf("'%s'", $tree['id'][$i]);
	}
	$ids = @implode(', ', $cells);

	// mains
	$cells = array();
	for ($i = 0; $i < sizeof($tree['main']); $i++)
	{
		$cells[] = sprintf("'%s'", $tree['main'][$i]);
	}
	$mains = @implode(', ', $cells);

	$template->assign_vars(array(
		'KEYS' => $keys,
		'TYPES' => $types,
		'IDS' => $ids,
		'MAINS' => $mains,
		)
	);

	// data
	for ($i = 0; $i < sizeof($tree['data']); $i++)
	{
		$template->assign_block_vars('data', array());

		@reset($tree['data'][$i]);
		while (list($key, $value) = @each($tree['data'][$i]))
		{
			$nkey = intval($key);
			if ($key != "$nkey")
			{
				$template->assign_block_vars('data.field', array(
					'FIELD_NAME' => $key,
					//'FIELD_VALUE' => str_replace("\n", "' . \"\\n\" . '", str_replace("\r\n", "' . \"\\r\\n\" . '", str_replace("'", "\'", $value))),
					'FIELD_VALUE' => str_replace("\n", "' . \"\\n\" . '", str_replace("\r\n", "' . \"\\r\\n\" . '", addslashes($value))),
					)
				);
			}
		}
	}

	// subs
	@reset($tree['sub']);
	while (list($main, $data) = @each($tree['sub']))
	{
		$cells = array();
		for ($i = 0; $i < sizeof($data); $i++)
		{
			$cells[] = sprintf("'%s'", $data[$i]);
		}
		$subs = @implode(', ', $cells);
		$template->assign_block_vars('sub', array(
			'THIS' => $main,
			'SUBS' => $subs,
			)
		);
	}

	// moderators
	@reset($tree['mods']);
	while (list($idx, $data) = @each($tree['mods']))
	{
		$s_user_ids = empty($data['user_id']) ? '' : implode(', ', $data['user_id']);
		$s_user_actives = empty($data['user_active']) ? '' : implode(', ', $data['user_active']);
		$s_group_ids = empty($data['group_id']) ? '' : implode(', ', $data['group_id']);
		$s_usernames = '';
		for ($j = 0; $j < sizeof($data['username']); $j++)
		{
			$s_usernames .= (empty($s_usernames) ? '' : ', ') . sprintf("'%s'", str_replace("'", "\'", $data['username'][$j]));
		}
		$s_user_colors = '';
		for ($j = 0; $j < sizeof($data['user_color']); $j++)
		{
			$s_user_colors .= (empty($s_user_colors) ? '' : ', ') . sprintf("'%s'", str_replace("'", "\'", $data['user_color'][$j]));
		}
		$s_group_names = '';
		for ($j = 0; $j < sizeof($data['group_name']); $j++)
		{
			$s_group_names .= (empty($s_group_names) ? '' : ', ') . sprintf("'%s'", str_replace("'", "\'", $data['group_name'][$j]));
		}
		$s_group_colors = '';
		for ($j = 0; $j < sizeof($data['group_color']); $j++)
		{
			$s_group_colors .= (empty($s_group_colors) ? '' : ', ') . sprintf("'%s'", str_replace("'", "\'", $data['group_color'][$j]));
		}
		$template->assign_block_vars('mods', array(
			'IDX' => $idx,
			'USER_IDS' => $s_user_ids,
			'USERNAMES' => $s_usernames,
			'USER_ACTIVES' => $s_user_actives,
			'USER_COLORS' => $s_user_colors,
			'GROUP_IDS' => $s_group_ids,
			'GROUP_NAMES' => $s_group_names,
			'GROUP_COLORS' => $s_group_colors,
			)
		);
	}

	// transfert to a var
	$template->assign_var_from_handle('def_tree', 'def_tree');
	$res = '<' . '?' . 'php' . "\n" . $template->_tpldata['.'][0]['def_tree'] . "\n" . '$cache_included = true;' . "\n" . 'return;' . "\n" . '?' . '>';
	// output to file
	$fname = MAIN_CACHE_FOLDER . CACHE_TREE_FILE;
	@chmod($fname, 0666);
	$handle = @fopen($fname, 'w');
	@fwrite($handle, $res);
	@fclose($handle);
}

function cache_tree_level($main, &$parents, &$cats, &$forums)
{
	global $tree;

	// read all parents
	$tree_level = array();

	// get the forums of the level
	for ($i = 0; $i < sizeof($parents[POST_FORUM_URL][$main]); $i++)
	{
		$idx = $parents[POST_FORUM_URL][$main][$i];
		$tree_level['type'][] = POST_FORUM_URL;
		$tree_level['id'][] = $forums[$idx]['forum_id'];
		$tree_level['sort'][] = $forums[$idx]['forum_order'];
		$tree_level['data'][] = $forums[$idx];
	}

	// add the categories of this level
	for ($i = 0; $i < sizeof($parents[POST_CAT_URL][$main]); $i++)
	{
		$idx = $parents[POST_CAT_URL][$main][$i];
		$tree_level['type'][] = POST_CAT_URL;
		$tree_level['id'][] = $cats[$idx]['forum_id'];
		$tree_level['sort'][] = $cats[$idx]['forum_order'];
		$tree_level['data'][] = $cats[$idx];
	}

	// sort the level
	@array_multisort($tree_level['sort'], $tree_level['type'], $tree_level['id'], $tree_level['data']);

	// add the tree_level to the tree
	$order = 0;
	for ($i = 0; $i < sizeof($tree_level['data']); $i++)
	{
		$CH_this = sizeof($tree['data']);
		$key = $tree_level['type'][$i] . $tree_level['id'][$i];
		$order = $order + 10;
		$tree['keys'][$key] = $CH_this;
		$tree['main'][] = $main;
		$tree['type'][] = $tree_level['type'][$i];
		$tree['id'][] = $tree_level['id'][$i];
		$tree['data'][] = $tree_level['data'][$i];
		$tree['sub'][$main][] = $key;

		cache_tree_level($key, $parents, $cats, $forums);
	}
}

function cache_tree($write = false)
{
	global $db, $cache, $config, $user, $lang, $tree;

	$parents = array();

	// read categories
	$cats = array();
	$sql = "SELECT forum_id, parent_id, main_type, forum_name, forum_name_clean, forum_desc, icon, forum_order
					FROM " . FORUMS_TABLE . "
					WHERE forum_type = " . FORUM_CAT . "
					ORDER BY forum_order, forum_id";
	$result = $db->sql_query($sql, 0, 'forums_cats_', FORUMS_CACHE_FOLDER);

	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['parent_id'] == $row['forum_id'])
		{
			$row['parent_id'] = 0;
		}
		if (empty($row['main_type']))
		{
			$row['main_type'] = POST_CAT_URL;
			$row['forum_order'] = $row['forum_order'] + 9000000;
		}
		$row['main'] = ($row['parent_id'] == 0) ? 'Root' : $row['main_type'] . $row['parent_id'];
		$idx = sizeof($cats);
		if (empty($row['forum_name_clean']))
		{
			if (!function_exists('update_clean_forum_name'))
			{
				@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
			}
			$row['forum_name_clean'] = substr(ip_clean_string($row['forum_name_clean'], $lang['ENCODING']), 0, 254);
			update_clean_forum_name($row['forum_id'], $row['forum_name_clean']);
		}
		$cats[$idx] = $row;
		$parents[POST_CAT_URL][$row['main']][] = $idx;
	}
	$db->sql_freeresult($result);

	// read forums
	$sql = "SELECT * FROM " . FORUMS_TABLE . " WHERE forum_type <> " . FORUM_CAT . " ORDER BY forum_order, forum_id";
	$result = $db->sql_query($sql, 0, 'forums_', FORUMS_CACHE_FOLDER);

	while ($row = $db->sql_fetchrow($result))
	{
		$main_type = (empty($row['main_type'])) ? POST_CAT_URL : $row['main_type'];
		$row['main'] = ($row['parent_id'] == 0) ? 'Root' : $main_type . $row['parent_id'];
		$idx = sizeof($forums);
		if (empty($row['forum_name_clean']))
		{
			if (!function_exists('update_clean_forum_name'))
			{
				@include_once(IP_ROOT_PATH . 'includes/functions_admin_forums.' . PHP_EXT);
			}
			$row['forum_name_clean'] = substr(ip_clean_string($row['forum_name'], $lang['ENCODING']), 0, 254);
			update_clean_forum_name($row['forum_id'], $row['forum_name_clean']);
		}
		$forums[$idx] = $row;
		$parents[POST_FORUM_URL][$row['main']][] = $idx;
	}
	$db->sql_freeresult($result);

	// build the tree
	$tree = array();
	cache_tree_level('Root', $parents, $cats, $forums);

	// Obtain list of moderators of each forum
	$moderators = array();
	$moderators = $cache->obtain_moderators(true);
	foreach ($moderators as $k => $v)
	{
		if ($k == 'users')
		{
			foreach ($moderators[$k] as $moderator_row)
			{
				$idx = $tree['keys'][POST_FORUM_URL . $moderator_row['forum_id']];
				$tree['mods'][$idx]['user_id'][] = $moderator_row['user_id'];
				$tree['mods'][$idx]['username'][] = $moderator_row['username'];
				$tree['mods'][$idx]['user_active'][] = $moderator_row['user_active'];
				$tree['mods'][$idx]['user_color'][] = $moderator_row['user_color'];
			}
		}
		elseif ($k == 'groups')
		{
			foreach ($moderators[$k] as $moderator_row)
			{
				$idx = $tree['keys'][POST_FORUM_URL . $moderator_row['forum_id']];
				$tree['mods'][$idx]['group_id'][] = $moderator_row['group_id'];
				$tree['mods'][$idx]['group_name'][] = $moderator_row['group_name'];
				$tree['mods'][$idx]['group_color'][] = $moderator_row['group_color'];
			}
		}
	}

	if ($write)
	{
		cache_tree_output();
	}
}

//--------------------------------------------------------------------------------------------------
// read_tree() : read the tables and fill the hierarchical tree
//--------------------------------------------------------------------------------------------------
function read_tree($force = false)
{

	global $db, $config, $user, $tree;

// UPI2DB - BEGIN
	if($user->data['upi2db_access'])
	{
		if (!defined('UPI2DB_UNREAD'))
		{
			$user->data['upi2db_unread'] = upi2db_unread();
		}
	}
// UPI2DB - END

	// read the user cookie
	$tracking_forums = (isset($_COOKIE[$config['cookie_name'] . '_f'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_f']) : array();
	$tracking_topics = (isset($_COOKIE[$config['cookie_name'] . '_t'])) ? unserialize($_COOKIE[$config['cookie_name'] . '_t']) : array();
	$tracking_all = (isset($_COOKIE[$config['cookie_name'] . '_f_all'])) ? intval($_COOKIE[$config['cookie_name'] . '_f_all']) : -1;

	// try the cache
	$use_cache_file = false;
	if (defined('CACHE_TREE'))
	{
		$cache_included = false;
		$cache_file = MAIN_CACHE_FOLDER . CACHE_TREE_FILE;
		if (!file_exists($cache_file))
		{
			cache_tree(true);
		}
		@include($cache_file);
		if (!$cache_included || empty($tree) || $force)
		{
			cache_tree(true);
			@include($cache_file);
		}
		if (!empty($tree))
		{
			$use_cache_file = true;
		}
	}
	else
	{
		cache_tree();
	}

	// New SQL based only on Forums table
	// Get last posts details for each forum
	$sql = "SELECT f.forum_id, f.forum_last_post_id, f.forum_last_topic_id as topic_id, f.forum_last_post_time as post_time, f.forum_last_post_subject as topic_title, f.forum_last_poster_id as user_id, f.forum_last_poster_name as username, f.forum_last_poster_color as user_color
				FROM " . FORUMS_TABLE . " f
				ORDER BY f.forum_id";
	$result = CACHE_CH_SQL ? $db->sql_query($sql, 3600, 'posts_', POSTS_CACHE_FOLDER) : $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result))
	{
		if (!empty($row['forum_last_post_id']))
		{
			$row['user_active'] = 1;
			$row['topic_title'] = censor_text($row['topic_title']);
			// store the added columns
			$idx = $tree['keys'][POST_FORUM_URL . $row['forum_id']];
			@reset($row);
			while (list($key, $value) = @each($row))
			{
				$nkey = intval($key);
				if ($key != "$nkey")
				{
					$tree['data'][$idx][$key] = $row[$key];
				}
			}
		}
	}
	$db->sql_freeresult($result);

	// set the unread flag
// UPI2DB - BEGIN
	if(!$user->data['upi2db_access'])
	{
// UPI2DB - END

		// Get new posts since last visit... only for registered users
		if ($user->data['session_logged_in'])
		{
			$time_limit = time() - (LAST_LOGIN_DAYS_NEW_POSTS_RESET * 24 * 60 * 60);
			$user_lastvisit = ($user->data['user_lastvisit'] < $time_limit) ? $time_limit : $user->data['user_lastvisit'];
			$sql_limit = " LIMIT " . LAST_LOGIN_NEW_POSTS_LIMIT;

			$sql = "SELECT p.forum_id, p.topic_id, p.post_time
						FROM " . POSTS_TABLE . " p
						WHERE (p.post_time > " . $user_lastvisit . ")
						ORDER BY p.post_time DESC
						" . $sql_limit;
			//$result = (CACHE_CH_SQL ? $db->sql_query($sql, 3600, 'posts_', POSTS_CACHE_FOLDER) : $db->sql_query($sql));
			$result = $db->sql_query($sql);

			$new_topic_data = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$new_topic_data[$row['forum_id']][$row['topic_id']] = $row['post_time'];
			}
			$db->sql_freeresult($result);
		}

		$tree['unread_topics'] = array();
		for ($i = 0; $i < sizeof($tree['data']); $i++)
		{
			if ($tree['type'][$i] == POST_FORUM_URL)
			{
				// get the last post time per forums
				$forum_id = $tree['id'][$i];
				$unread_topics = false;
				if (!empty($new_topic_data[$forum_id]))
				{
					$forum_last_post_time = 0;
					@reset($new_topic_data[$forum_id]);
					while(list($check_topic_id, $check_post_time) = @each($new_topic_data[$forum_id]))
					{
						if (empty($tracking_topics[$check_topic_id]))
						{
							$unread_topics = true;
							$forum_last_post_time = max($check_post_time, $forum_last_post_time);
						}
						else
						{
							if ($tracking_topics[$check_topic_id] < $check_post_time)
							{
								$unread_topics = true;
								$forum_last_post_time = max($check_post_time, $forum_last_post_time);
							}
						}
					}

					// is there a cookie for this forum ?
					if (!empty($tracking_forums[$forum_id]))
					{
						if ($tracking_forums[$forum_id] > $forum_last_post_time)
						{
							$unread_topics = false;
						}
					}

					// is there a cookie for all forums ?
					if ($tracking_all > $forum_last_post_time)
					{
						$unread_topics = false;
					}
				}

				// store the result
				$tree['unread_topics'][$i] = $unread_topics;
			}
		}
// UPI2DB - BEGIN
	}
	else
	{
		for ($i = 0; $i < sizeof($tree['data']); $i++)
		{
			if ($tree['type'][$i] == POST_FORUM_URL)
			{
				$unread_topics = false;
				$forum_id = $tree['id'][$i];
				if(in_array($forum_id, $user->data['upi2db_unread']['forums']) || in_array('A', $user->data['upi2db_unread']['forums']))
				{
					$unread_topics = true;
				}
				$tree['unread_topics'][$i] = $unread_topics;
			}
		}
	}
// UPI2DB - END
	return;
}

//--------------------------------------------------------------------------------------------------
// set_tree_user_auth() : enhance each row with auths and other things : use get_user_tree() as entry point
//--------------------------------------------------------------------------------------------------
function set_tree_user_auth()
{
	global $config, $user, $lang, $db;
	global $tree;

	// Get users online for each forum
	if ($config['show_forums_online_users'] == true)
	{

		$sql = "SELECT DISTINCT(s.session_ip), s.session_forum_id
			FROM " . SESSIONS_TABLE . " s
			WHERE s.session_time >= " . (time() - ONLINE_REFRESH) . "
				AND s.session_forum_id <> 0
				GROUP BY s.session_ip
				ORDER BY s.session_forum_id ASC";
		$result = $db->sql_query($sql);

		$forum_online = array();
		while($row = $db->sql_fetchrow($result))
		{
			$forum_online[$row['session_forum_id']] = (empty($forum_online[$row['session_forum_id']])) ? 1 : ($forum_online[$row['session_forum_id']] + 1);
		}
	}
	$db->sql_freeresult($result);

	// read the tree from the bottom
	for ($i = sizeof($tree['data']) - 1; $i >= 0; $i--)
	{
		//---------------------
		// full ids
		//---------------------
		$cur = $tree['type'][$i] . $tree['id'][$i];
		$main = $tree['main'][$i];
		$main_idx = ($main == 'Root') ? -1 : $tree['keys'][$main];

		//---------------------
		// auth view
		//---------------------
		$auth_view = false;
		if (isset($tree['auth'][$cur]['auth_view']))
		{
			// forum auth
			$auth_view = $tree['auth'][$cur]['auth_view'];
		}
		elseif (isset($tree['auth'][$cur]['tree.auth_view']))
		{
			// categorie auth : get the sub level one
			$auth_view = $tree['auth'][$cur]['tree.auth_view'];
		}
		$tree['auth'][$cur]['auth_view'] = $auth_view;
		if (!isset($tree['auth'][$cur]['tree.auth_view']))
		{
			$tree['auth'][$cur]['tree.auth_view'] = $auth_view;
		}

		// grant the main level
		if ($main != 'Root')
		{
			// Mighty Gorgon: this is the old working line... please restore it back if not working!!!
			//$tree['auth'][$main]['tree.auth_view'] = ($tree['auth'][$main]['tree.auth_view'] || $tree['auth'][$cur]['tree.auth_view']);
			$tree['auth'][$main]['tree.auth_view'] = (!empty($tree['auth'][$main]['tree.auth_view']) || !empty($tree['auth'][$cur]['tree.auth_view']));
		}

		//---------------------
		// auth read
		//---------------------
		$auth_read = false;
		$auth_lp = false;
		if (isset($tree['auth'][$cur]['auth_read']))
		{
			// forum auth
			$auth_read = $tree['auth'][$cur]['auth_read'];
			//if(((($user->data['user_level'] != ADMIN) && ($user->data['user_level'] != MOD)) || (($user->data['user_level'] == MOD) && ($config['allow_mods_view_self'] == false))) && (intval($tree['auth'][$cur]['auth_read']) == AUTH_SELF))
			if(($user->data['user_level'] != ADMIN) && (intval($tree['auth'][$cur]['auth_read']) == AUTH_SELF))
			{
				$auth_lp = false;
			}
			else
			{
				$auth_lp = true;
			}
		}
		$tree['auth'][$cur]['auth_read'] = $auth_read;

		//---------------------
		// forum information
		//---------------------
		// locked status
		$locked = true;
		if (isset($tree['data'][$i]['forum_status']))
		{
			// forum info
			$locked = ($tree['data'][$i]['forum_status'] == FORUM_LOCKED);
		}
		elseif (isset($tree['data'][$i]['tree.locked']))
		{
			// category info : get the sub levels one
			$locked = $tree['data'][$i]['tree.locked'];
		}
		$tree['data'][$i]['locked'] = $locked;

		// force lock status if no sub levels
		if (!isset($tree['data'][$i]['tree.locked']))
		{
			$tree['data'][$i]['tree.locked'] = $locked;
		}
		$tree['data'][$i]['tree.locked'] = ($tree['data'][$i]['tree.locked'] && $locked);

		// number of posts and topics
		if (!isset($tree['data'][$i]['tree.forum_posts']))
		{
			$tree['data'][$i]['tree.forum_posts'] = 0;
			$tree['data'][$i]['tree.forum_topics'] = 0;
			$tree['data'][$i]['tree.forum_online'] = 0;
		}

		if ($auth_view)
		{
			$tree['data'][$i]['tree.forum_posts'] += isset($tree['data'][$i]['forum_posts']) ? $tree['data'][$i]['forum_posts'] : 0;
			$tree['data'][$i]['tree.forum_topics'] += isset($tree['data'][$i]['forum_topics']) ? $tree['data'][$i]['forum_topics'] : 0;
			$tree['data'][$i]['tree.forum_online'] += (empty($forum_online[$tree['id'][$i]]) ? 0 : (int) $forum_online[$tree['id'][$i]]);
		}

		// grant the main level
		if ($main != 'Root')
		{
			if (!isset($tree['data'][$main_idx]['tree.locked']))
			{
				$tree['data'][$main_idx]['tree.locked'] = $tree['data'][$i]['tree.locked'];
			}
			$tree['data'][$main_idx]['tree.locked'] = ($tree['data'][$main_idx]['tree.locked'] && $tree['data'][$i]['tree.locked']);

			// number of posts and topics
			if (!isset($tree['data'][$main_idx]['tree.forum_posts']))
			{
				$tree['data'][$main_idx]['tree.forum_posts'] = 0;
				$tree['data'][$main_idx]['tree.forum_topics'] = 0;
				$tree['data'][$main_idx]['tree.forum_online'] = 0;
			}
			if ($auth_view)
			{
				$tree['data'][$main_idx]['tree.forum_posts'] += $tree['data'][$i]['tree.forum_posts'];
				$tree['data'][$main_idx]['tree.forum_topics'] += $tree['data'][$i]['tree.forum_topics'];
				$tree['data'][$main_idx]['tree.forum_online'] += $tree['data'][$i]['tree.forum_online'];
			}
		}

		//---------------------
		// last post
		//---------------------
		if ($auth_read)
		{
			// fill the sub
			if (empty($tree['data'][$i]['tree.forum_last_post_id']) || ($tree['data'][$i]['post_time'] > $tree['data'][$i]['tree.post_time']))
			{
				$tree['data'][$i]['tree.topic_last_post_auth'] = $auth_lp;
				$tree['data'][$i]['tree.forum_last_post_id'] = isset($tree['data'][$i]['forum_last_post_id']) ? $tree['data'][$i]['forum_last_post_id'] : '';
				$tree['data'][$i]['tree.post_time'] = isset($tree['data'][$i]['post_time']) ? $tree['data'][$i]['post_time'] : '';
				$tree['data'][$i]['tree.post_user_id'] = isset($tree['data'][$i]['user_id']) ? $tree['data'][$i]['user_id'] : '';
				if (isset($tree['data'][$i]['user_id']) && isset($tree['data'][$i]['username']))
				{
					$tree['data'][$i]['tree.post_username'] = ($tree['data'][$i]['user_id'] != ANONYMOUS) ? $tree['data'][$i]['username'] : ((!empty($tree['data'][$i]['post_username'])) ? $tree['data'][$i]['post_username'] : $lang['Guest']);
				}
				else
				{
					$tree['data'][$i]['tree.post_username'] = '';
				}
				$tree['data'][$i]['tree.user_active'] = isset($tree['data'][$i]['user_active']) ? $tree['data'][$i]['user_active'] : '';
				$tree['data'][$i]['tree.user_color'] = isset($tree['data'][$i]['user_color']) ? $tree['data'][$i]['user_color'] : '';
				$tree['data'][$i]['tree.topic_title'] = isset($tree['data'][$i]['topic_title']) ? $tree['data'][$i]['topic_title'] : '';
				$tree['data'][$i]['tree.unread_topics'] = isset($tree['unread_topics'][$i]) ? $tree['unread_topics'][$i] : '';
			}
		}

		// grant the main level
		if ($main != 'Root')
		{
			if (empty($tree['data'][$main_idx]['tree.forum_last_post_id']) || ($tree['data'][$i]['tree.post_time'] > $tree['data'][$main_idx]['tree.post_time']))
			{
				$tree['data'][$main_idx]['tree.topic_last_post_auth'] = $auth_lp;
				$tree['data'][$main_idx]['tree.forum_last_post_id'] = isset($tree['data'][$i]['tree.forum_last_post_id']) ? $tree['data'][$i]['tree.forum_last_post_id'] : '';
				$tree['data'][$main_idx]['tree.post_time'] = isset($tree['data'][$i]['tree.post_time']) ? $tree['data'][$i]['tree.post_time'] : '';
				$tree['data'][$main_idx]['tree.post_user_id'] = isset($tree['data'][$i]['tree.post_user_id']) ? $tree['data'][$i]['tree.post_user_id'] : '';
				$tree['data'][$main_idx]['tree.post_username'] = isset($tree['data'][$i]['tree.post_username']) ? $tree['data'][$i]['tree.post_username'] : '';
				$tree['data'][$main_idx]['tree.user_active'] = isset($tree['data'][$i]['user_active']) ? $tree['data'][$i]['user_active'] : '';
				$tree['data'][$main_idx]['tree.user_color'] = isset($tree['data'][$i]['user_color']) ? $tree['data'][$i]['user_color'] : '';
				$tree['data'][$main_idx]['tree.topic_title'] = isset($tree['data'][$i]['tree.topic_title']) ? $tree['data'][$i]['tree.topic_title'] : '';
				$tree['data'][$main_idx]['tree.unread_topics'] = isset($tree['data'][$i]['tree.unread_topics']) ? $tree['data'][$i]['tree.unread_topics'] : '';
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------
// get_user_tree() : generate the hierarchy tree - called in $user->setup()
//--------------------------------------------------------------------------------------------------
function get_user_tree(&$user_data)
{
	global $tree;

	if (empty($tree))
	{
		read_tree();
	}

	// read the user auth if requiered
	if (empty($tree['auth']))
	{
		$tree['auth'] = array();
		$wauth = auth(AUTH_ALL, AUTH_LIST_ALL, $user_data);
		if (!empty($wauth))
		{
			reset($wauth);
			while (list($key, $data) = each($wauth))
			{
				$tree['auth'][POST_FORUM_URL . $key] = $data;
			}
		}

		// enhanced each level
		set_tree_user_auth();
	}

	return;
}

//--------------------------------------------------------------------------------------------------
//
// get_auth_keys() : return an array() with only the viewable row id
// returned array :
//		$keys['keys'][id] => n,
//		$keys['id'][n] => id (used by $tree),
//		$keys['real_level'][n] => level in this auth-tree (root=-1),
//		$keys['level'][n] => level adjust for display (sub-level=parent level under certain conditions)
//		$keys['idx'][n] => idx (used by $tree)
//--------------------------------------------------------------------------------------------------
function get_auth_keys($cur = 'Root', $all = false, $level = -1, $max = -1, $auth_key = 'auth_view')
{
	global $config;
	global $tree;

	$keys = array();
	$last_i = -1;

	// add the level
	if (($cur == 'Root') || $tree['auth'][$cur][$auth_key] || $all)
	{
		// push the level
		if (($max < 0) || ($level < $max) || (($level == $max) && ((substr($tree['main'][$tree['keys'][$cur]], 0, 1) == POST_CAT_URL) || ($tree['main'][$tree['keys'][$cur]] == 'Root'))))
		{
			// if child of cat, align the level on the parent one
			$orig_level = $level;
			if (!$all)
			{
				if (($level > 0) && ((substr($cur, 0, 1) == POST_FORUM_URL) || (intval($config['sub_forum']) > 0)) && (substr($tree['main'][$tree['keys'][$cur]], 0, 1) == POST_CAT_URL)) $level = $level-1;
			}

			// store this level
			$last_i++;
			$keys['keys'][$cur] = $last_i;
			$keys['id'][$last_i] = $cur;
			$keys['real_level'][$last_i] = $orig_level;
			$keys['level'][$last_i] = $level;
			$keys['idx'][$last_i] = (isset($tree['keys'][$cur]) ? $tree['keys'][$cur] : -1);

			// get sub-levels
			if (!empty($tree['sub'][$cur]))
			{
				for ($i = 0; $i < sizeof($tree['sub'][$cur]); $i++)
				{
					$tkeys = array();
					$tkeys = get_auth_keys($tree['sub'][$cur][$i], $all, $orig_level + 1, $max, $auth_key);

					// add sub-levels
					if (!empty($tkeys['id']))
					{
						for ($j = 0; $j < sizeof($tkeys['id']); $j++)
						{
							$last_i++;
							$keys['keys'][$tkeys['id'][$j]] = $last_i;
							$keys['id'][$last_i] = $tkeys['id'][$j];
							$keys['real_level'][$last_i] = $tkeys['real_level'][$j];
							$keys['level'][$last_i] = $tkeys['level'][$j];
							$keys['idx'][$last_i] = $tkeys['idx'][$j];
						}
					}
				}
			}
		}
	}

	return $keys;
}

//--------------------------------------------------------------------------------------------------
// get_max_depth() : return the maximum level in the branch of the tree
//--------------------------------------------------------------------------------------------------
function get_max_depth($cur = 'Root', $all = false, $level = -1, &$keys, $max = -1)
{
	global $tree;
	if (empty($keys['id']))
	{
		$keys = array();
		$keys = get_auth_keys($cur, $all);
	}

	$max_level = 0;
	for ($i = 0; $i < sizeof($keys['id']); $i++)
	{
		if ($keys['level'][$i] > $max_level)
		{
			$max_level = $keys['level'][$i];
		}
	}
	return $max_level;
}

//--------------------------------------------------------------------------------------------------
// build_index() : display a level and its sublevels : use dislay_index() as entry point
//--------------------------------------------------------------------------------------------------
function build_index($cur = 'Root', $cat_break = false, &$forum_moderators, $real_level = -1, $max_level = -1, &$keys)
{
	global $template, $db, $cache, $config, $user, $lang, $images, $theme;
	global $tree, $bbcode, $lofi;

	if (empty($bbcode))
	{
		include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	}

	// init
	$display = false;

	// get the sub_forum switch value
	$sub_forum = intval($config['sub_forum']);
	if (($sub_forum == 2) && defined('IN_VIEWFORUM'))
	{
		$sub_forum = 1;
	}
	$pack_first_level = ($sub_forum == 2);

	// verify the cat_break parm
	if (($cur != 'Root') && ($real_level == -1)) $cat_break = false;

	// display the level
	$CH_this = isset($tree['keys'][$cur]) ? $tree['keys'][$cur] : -1;

	// display each kind of row

	// root level head
	if ($real_level == -1)
	{
		// get max inc level
		$max = -1;
		if ($sub_forum == 2) $max = 0;
		if ($sub_forum == 1) $max = 1;
		$keys = array();
		$keys = get_auth_keys($cur, false, -1, $max);
		$max_level = get_max_depth($cur, false, -1, $keys, $max);
	}

	// table header
	if (($config['split_cat'] && $cat_break && ($real_level == 0)) || ((!$config['split_cat'] || !$cat_break) && ($real_level == -1)))
	{
		// if break, get the local max level
		if ($config['split_cat'] && $cat_break && ($real_level == 0))
		{
			$max_level = 0;
			// the array is sorted
			$start = false;
			$stop = false;
			for ($i = 0; ($i < sizeof($keys['id']) && !$stop); $i++)
			{
				if ($start && ($tree['main'][$keys['idx'][$i]] == $tree['main'][$CH_this]))
				{
					$stop = true;
					$break;
				}
				if ($keys['id'][$i] == $cur) $start = true;
				if ($start && !$stop && ($keys['level'][$i] > $max_level)) $max_level = $keys['level'][$i];
			}
		}
		$template->assign_block_vars('catrow', array(
			'MAIN_CAT_ID' => $cur,
			)
		);
		$template->assign_block_vars('catrow.tablehead', array(
			'L_FORUM' => ($CH_this < 0) ? $lang['Forum'] : get_object_lang($cur, 'name'),
			'INC_SPAN' => $max_level + 2,
			)
		);
	}

	// get the level
	$level = $keys['level'][$keys['keys'][$cur]];

	// sub-forum view management
	$pull_down = true;
	if ($sub_forum > 0)
	{
		$pull_down = false;
		// JHL 2012/03/09
		//if (($real_level == 0) && ($sub_forum == 1))
		if (($real_level == 0) && (($sub_forum == 1) || ($sub_forum == 3)))
		{
			$pull_down = true;
		}
	}

	if ($level >= 0)
	{
		// cat header row
		if (($tree['type'][$CH_this] == POST_CAT_URL) && $pull_down)
		{
			// display a cat row
			$cat = $tree['data'][$CH_this];
			$cat_id = $tree['id'][$CH_this];

			// get the class colors
			$class_catLeft = 'cat';
			$class_cat = 'cat';
			$class_rowpic = 'rowpic';

			// send to template
			$template->assign_block_vars('catrow', array(
				'MAIN_CAT_ID' => $cur,
				)
			);
			$template->assign_block_vars('catrow.cathead', array(
				'CAT_TITLE' => get_object_lang($cur, 'name'),
				'CAT_DESC' => preg_replace('/<[^>]+>/', '', get_object_lang($cur, 'desc')),

				'CLASS_CATLEFT' => $class_catLeft,
				'CLASS_CAT' => $class_cat,
				'CLASS_ROWPIC' => $class_rowpic,
				'INC_SPAN' => $max_level - $level + 2,

				'U_VIEWCAT' => append_sid(CMS_PAGE_FORUM . '?' . POST_CAT_URL . '=' . $cat_id),
				)
			);


			// add indentation to the display
			for ($k = 1; $k <= $level; $k++)
			{
				$template->assign_block_vars('catrow.cathead.inc', array(
					'INC_CLASS' => ($k % 2) ? $theme['td_class1'] : $theme['td_class2'],
					)
				);
			}

			// something displayed
			$display = true;
		}
	}

	// forum header row
	if ($level >= 0)
	{
		if (($tree['type'][$CH_this] == POST_FORUM_URL) || (($tree['type'][$CH_this] == POST_CAT_URL) && !$pull_down))
		{
			// get the data
			$data = $tree['data'][$CH_this];
			$id = $tree['id'][$CH_this];
			$type = $tree['type'][$CH_this];
			$sub = (!empty($tree['sub'][$cur]) && $tree['auth'][$cur]['tree.auth_view']);

			// specific to the data type
			$title = get_object_lang($cur, 'name');
			$desc = get_object_lang($cur, 'desc');

			// specific to something attached
			if ($sub)
			{
				$i_new = $images['forum_sub_unread'];
				$a_new = $lang['New_posts'];
				$i_norm = $images['forum_sub_read'];
				$a_norm = $lang['No_new_posts'];
				$i_locked = $images['forum_sub_locked_read'];
				$a_locked = $lang['Forum_locked'];
			}
			else
			{
				$i_new = $images['forum_nor_unread'];
				$a_new = $lang['New_posts'];
				$i_norm = $images['forum_nor_read'];
				$a_norm = $lang['No_new_posts'];
				$i_locked = $images['forum_nor_locked_read'];
				$a_locked = $lang['Forum_locked'];
			}

			// forum link type
			if (($tree['type'][$CH_this] == POST_FORUM_URL) && !empty($tree['data'][$CH_this]['forum_link']))
			{
				$i_new = $images['forum_link'];
				$a_new = $lang['Forum_link'];
				$i_norm = $images['forum_link'];
				$a_norm = $lang['Forum_link'];
				$i_locked = $images['forum_link'];
				$a_locked = $lang['Forum_link'];
			}

			// front icon
			$link_class = !empty($data['tree.unread_topics']) ? '-new' : '';
			$folder_image = !empty($data['tree.unread_topics']) ? $i_new : $i_norm;
			$folder_alt = !empty($data['tree.unread_topics']) ? $a_new : $a_norm;
			if ($data['tree.locked'])
			{
				$folder_image = $i_locked;
				$folder_alt = $a_locked;
			}

			// moderators list
			$l_moderators = '';
			$moderator_list = '';
			if ($type == POST_FORUM_URL)
			{
				if (sizeof($forum_moderators[$id]) > 0)
				{
					$l_moderators = (sizeof($forum_moderators[$id]) == 1) ? $lang['Moderator'] : $lang['Moderators'];
					$moderator_list = implode(', ', $forum_moderators[$id]);
				}
			}

			// last post
			$last_post = $lang['No_Posts'];
			if ((isset($data['tree.forum_last_post_id']) && $data['tree.forum_last_post_id']) && (isset($data['tree.topic_last_post_auth']) && $data['tree.topic_last_post_auth']))
			{
				$topic_title = htmlspecialchars_clean($data['tree.topic_title']);
				$topic_title_plain = $topic_title;
				$topic_title_short = $topic_title;

				// SMILEYS IN TITLE - BEGIN
				if ($config['smilies_topic_title'] && !$lofi)
				{
					$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);
					$topic_title = $bbcode->parse_only_smilies($topic_title);
				}
				// SMILEYS IN TITLE - END

				$topic_title = (empty($data['topic_label_compiled'])) ? $topic_title : $data['topic_label_compiled'] . ' ' . $topic_title;
				if (strlen($topic_title) > (intval($config['last_topic_title_length']) - 3))
				{
					// remove tags from the short version, in case a smiley or a topic label is in there
					$topic_title_short = substr(strip_tags($topic_title), 0, intval($config['last_topic_title_length'])) . '...';
				}

				$topic_title = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . ((!empty($data['forum_id'])) ? (POST_FORUM_URL . '=' . $data['forum_id'] . '&amp;') : '') . POST_POST_URL . '=' . $data['tree.forum_last_post_id']) . '#p' . $data['tree.forum_last_post_id'] . '" title="' . $topic_title_plain . '">' . $topic_title_short . '</a><br />';

				$last_post_time = create_date_ip($config['default_dateformat'], $data['tree.post_time'], $config['board_timezone']);
				$last_post = (($config['last_topic_title']) ? $topic_title : '');
				$last_post .= $last_post_time . '<br />';
				$last_post .= ($data['tree.post_user_id'] == ANONYMOUS) ? $data['tree.post_username'] . ' ' : colorize_username($data['tree.post_user_id'], $data['tree.post_username'], $data['tree.user_color'], $data['tree.user_active']);

				$last_post .= '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?' . ((!empty($data['forum_id'])) ? (POST_FORUM_URL . '=' . $data['forum_id'] . '&amp;') : '') . POST_POST_URL . '=' . $data['tree.forum_last_post_id']) . '#p' . $data['tree.forum_last_post_id'] . '" title="' . $topic_title_plain . '"><img src="' . (($data['tree.unread_topics']) ? $images['icon_newest_reply'] : $images['icon_latest_reply']) . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
			}

			// links to sub-levels
			$links = '';
			// JHL 2012/03/09
			//if ($sub && (!$pull_down || (($type == POST_FORUM_URL) && ($sub_forum > 0))) && (intval($config['sub_level_links']) > 0))
			if ($sub && (!$pull_down || (($type == POST_FORUM_URL) && ($sub_forum > 0))) && ((intval($config['sub_level_links']) > 0) && ($sub_forum != 3)))
			{
				for ($j = 0; $j < sizeof($tree['sub'][$cur]); $j++) if ($tree['auth'][$tree['sub'][$cur][$j]]['auth_view'])
				{
					$wcur = $tree['sub'][$cur][$j];
					$wthis = $tree['keys'][$wcur];
					$wdata = $tree['data'][$wthis];
					$wname = get_object_lang($wcur, 'name');
					$wdesc = get_object_lang($wcur, 'desc');
					switch($tree['type'][$wthis])
					{
						case POST_FORUM_URL:
							$wpgm = append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $tree['id'][$wthis]);
							break;
						case POST_CAT_URL:
							$wpgm = append_sid(CMS_PAGE_FORUM . '?' . POST_CAT_URL . '=' . $tree['id'][$wthis]);
							break;
						default:
							$wpgm = append_sid(CMS_PAGE_FORUM);
							break;
					}
					$link = '';
					$wdesc = preg_replace('/<[^>]+>/', '', $wdesc);


					if (intval($config['sub_level_links']) == 2)
					{
						$wsub = (!empty($tree['sub'][$wcur]) && $tree['auth'][$wcur]['tree.auth_view']);

						// specific to something attached
						if ($wsub)
						{
							$wi_new = $images['icon_minicat_new'];
							$wa_new = $lang['New_posts'];
							$wi_norm = $images['icon_minicat'];
							$wa_norm = $lang['No_new_posts'];
							$wi_locked = $images['icon_minicat_locked'];
							$wa_locked = $lang['Forum_locked'];
						}
						else
						{
							$wi_new = $images['icon_minipost_new'];
							$wa_new = $lang['New_posts'];
							$wi_norm = $images['icon_minipost'];
							$wa_norm = $lang['No_new_posts'];
							$wi_locked = $images['icon_minipost_lock'];
							$wa_locked = $lang['Forum_locked'];
						}

						// forum link type
						if (($tree['type'][$wthis] == POST_FORUM_URL) && !empty($wdata['forum_link']))
						{
							$wi_new = $images['icon_minilink'];
							$wa_new = $lang['Forum_link'];
							$wi_norm = $images['icon_minilink'];
							$wa_norm = $lang['Forum_link'];
							$wi_locked = $images['icon_minilink'];
							$wa_locked = $lang['Forum_link'];
						}

						// front icon
						$wfolder_image = ($wdata['tree.unread_topics']) ? $wi_new : $wi_norm;
						$wfolder_alt = ($wdata['tree.unread_topics']) ? $wa_new : $wa_norm;
						if ($wdata['tree.locked'])
						{
							$wfolder_image = $wi_locked;
							$wfolder_alt = $wa_locked;
						}
						if ($lofi == true)
						{
							$wlast_post = '';
						}
						else
						{
							$wlast_post = '<a href="' . append_sid(CMS_PAGE_VIEWTOPIC . '?'  . POST_POST_URL . '=' . $wdata['tree.forum_last_post_id']) . '#p' . $wdata['tree.forum_last_post_id'] . '">';
							$wlast_post .= '<img src="' . $wfolder_image . '" alt="' . $wfolder_alt . '" title="' . $wfolder_alt . '" /></a>&nbsp;';
						}
					}
					$class = ($wfolder_image == $wi_new) ? 'forumlink2-new' : 'forumlink2';
					if ($wname != '')
					{
						$link = '<a href="' . $wpgm . '" title="' . $wdesc . '" class="' . $class . '">' . $wname . '</a>';
					}
					if ($link != '')
					{
						$links .= (($links != '') ? ', ' : '') . $wlast_post . $link;
					}
				}
			}

			// forum icon
			$icon_img = empty($data['icon']) ? '' : (isset($images[$data['icon']]) ? $images[$data['icon']] : $data['icon']);
// UPI2DB - BEGIN
			if($user->data['upi2db_access'])
			{
				$folder_image_ar_big = $images['forum_nor_ar'];
				$cat_image_ar_big = $images['forum_sub_ar'];
				$forum_id = $data['forum_id'];

				if(!$data['tree.unread_topics'] && !$sub)
				{
					if(is_array($user->data['upi2db_unread']['always_read']['forums']) && !in_array($forum_id, $user->data['upi2db_unread']['always_read']['forums']))
					{
						$mark_always_read = '<a href="' . append_sid(CMS_PAGE_FORUM . '?forum_id=' . $forum_id . '&amp;always_read=set') . '"><img src="' . $folder_image . '" alt="' . $lang['upi2db_always_read_forum']. '" title="' . $lang['upi2db_always_read_forum'] . '" /></a>';
					}
					else
					{
						$mark_always_read = '<a href="' . append_sid(CMS_PAGE_FORUM . '?forum_id=' . $forum_id . '&amp;always_read=unset') . '"><img src="' . $folder_image_ar_big . '" alt="' . $lang['upi2db_always_read_forum_unset'] . '" title="' . $lang['upi2db_always_read_forum_unset'] . '" /></a>';
					}
				}
				else
				{
					if($sub)
					{
						$mark_always_read = '<img src="' . $folder_image. '" alt="' . $lang['upi2db_cat_cant_mark_always_read'] . '" title="' . $lang['upi2db_cat_cant_mark_always_read'] . '" />';
					}
					else
					{
						$mark_always_read = '<img src="' . $folder_image . '" alt="' . $folder_alt . '" title="' . $folder_alt . '" />';
					}
				}
			}
			else
			{
				$mark_always_read = '<img src="' . $folder_image . '" alt="' . $folder_alt . '" title="' . $folder_alt . '" />';
			}
// UPI2DB - END
			if (($config['url_rw'] == true) || (($config['url_rw_guests'] == true) && ($user->data['user_id'] == ANONYMOUS)))
			{
				$url_viewforum = ($type == POST_FORUM_URL) ? append_sid(str_replace ('--', '-', make_url_friendly($title) . '-vf' . $id . '.html')) : append_sid(str_replace ('--', '-', make_url_friendly($title) . '-vc' . $id . '.html'));
			}
			else
			{
				$url_viewforum = ($type == POST_FORUM_URL) ? append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $id) : append_sid(CMS_PAGE_FORUM . '?' . POST_CAT_URL . '=' . $id);
			}
			// send to template
			if ($config['show_rss_forum_icon'] && ($data['forum_index_icons'] == 1) && ($type == POST_FORUM_URL))
			{
				$rss_feed_icon = '';
				if (!$data['tree.locked'] && $user->data['session_logged_in'])
				{
					$rss_feed_icon .= '&nbsp;<a href="' . append_sid(CMS_PAGE_POSTING . '?mode=newtopic&amp;' . POST_FORUM_URL . '=' . $id) . '"><img src="' . $images['vf_topic_nor'] . '" alt="' . $lang['Post_new_topic'] . '" title="' . $lang['Post_new_topic'] . '" /></a>';
				}
				$rss_feed_icon .= '&nbsp;<a href="' . append_sid('rss.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $id) . '"><img src="' . $images['nav_menu_feed'] . '" alt="' . $lang['Rss_news_feeds'] . '" title="' . $lang['Rss_news_feeds'] . '" /></a>';
			}
			else
			{
				$rss_feed_icon = '&nbsp;';
			}
			$template->assign_block_vars('catrow', array());
			$template->assign_block_vars('catrow.forumrow', array(
				'FORUM_FOLDER_IMG' => $folder_image,
				'ICON_IMG' => $icon_img,
				'RSS_FEED_ICON' => $rss_feed_icon,
				/*
				'FORUM_NAME' => str_replace('&', '&amp;', $title),
				'FORUM_DESC' => str_replace('&', '&amp;', $desc),
				*/
				'FORUM_NAME' => $title,
				'FORUM_DESC' => $desc,
				// JHL 2012/03/09
				'FORUM_TYPE' => ($type == POST_FORUM_URL) ? 'forum' : 'category',
				'POSTS' => $data['tree.forum_posts'],
				'TOPICS' => $data['tree.forum_topics'],
				'ONLINE' => (($config['show_forums_online_users'] == true) ? ('<br />' . $lang['Online'] . ':&nbsp;' . $data['tree.forum_online']) : ''),
				'LAST_POST' => $last_post,
				'MODERATORS' => $moderator_list,
				'L_MODERATOR' => empty($moderator_list) ? '' : (empty($l_moderators) ? '<br />' : '<br /><b>' . $l_moderators . ':</b>&nbsp;'),
				//'L_LINKS' => empty($links) ? '' : (empty($lang['Subforums']) ? '<br />' : '<br /><b>' . $lang['Subforums'] . ':</b>&nbsp;'),
				'L_LINKS' => empty($links) ? '' : (empty($lang['Subforums']) ? '' : '<b>' . $lang['Subforums'] . ':</b>&nbsp;'),
				'LINKS_BR' => empty($links) ? '' : '<br />',
				'LINKS_ROWSPAN' => empty($links) ? '' : ' rowspan="2"',
				'LINKS' => $links,
				'L_FORUM_FOLDER_ALT' => $folder_alt,
// UPI2DB - BEGIN
				'U_MARK_ALWAYS_READ' => $mark_always_read,
// UPI2DB - END
				'L_POST_NEW_TOPIC' => $lang['Post_new_topic'],
				'U_VIEWFORUM' => $url_viewforum,
				//'U_VIEWFORUM' => ($type == POST_FORUM_URL) ? append_sid(CMS_PAGE_VIEWFORUM . "?" . POST_FORUM_URL . "=$id") : append_sid(CMS_PAGE_FORUM . "?" . POST_CAT_URL . "=$id"),
				'U_POST_NEW_TOPIC' => append_sid(CMS_PAGE_POSTING . '?mode=newtopic&amp;' . POST_FORUM_URL . '=' . $id),

				'LINK_CLASS' => $link_class,
				'INC_SPAN' => $max_level- $level + 1,
				'INC_CLASS' => (!($level % 2)) ? $theme['td_class1'] : $theme['td_class2'],
				)
			);

			// display icon
			if (!empty($icon_img))
			{
				$template->assign_block_vars('catrow.forumrow.forum_icon', array());
			}

			// add indentation to the display
			for ($k = 1; $k <= $level; $k++)
			{
				$template->assign_block_vars('catrow.forumrow.inc', array(
					'INC_CLASS' => ($k % 2) ? $theme['td_class1'] : $theme['td_class2'],
					)
				);
			}

			// forum link type
			if (($tree['type'][$CH_this] == POST_FORUM_URL) && !empty($tree['data'][$CH_this]['forum_link']))
			{
				$s_hit_count = '';
				if ($tree['data'][$CH_this]['forum_link_hit_count'])
				{
					$s_hit_count = sprintf($lang['Forum_link_visited'], $tree['data'][$CH_this]['forum_link_hit']);
				}
				$template->assign_block_vars('catrow.forumrow.forum_link', array(
					'HIT_COUNT' => $s_hit_count,
					)
				);
			}
			else
			{
				$template->assign_block_vars('catrow.forumrow.forum_link_no', array());
			}

			// something displayed
			$display = true;
		}
	}

	// display sub-levels
	if (!empty($tree['sub'][$cur]))
	{
		for ($i = 0; $i < sizeof($tree['sub'][$cur]); $i++) if (!empty($keys['keys'][$tree['sub'][$cur][$i]]))
		{
			$wdisplay = build_index($tree['sub'][$cur][$i], $cat_break, $forum_moderators, $level + 1, $max_level, $keys);
			if ($wdisplay)
			{
				$display = true;
			}
		}
	}

	if ($level >= 0)
	{
		// forum footer row
		if ($tree['type'][$CH_this] == POST_FORUM_URL)
		{
		}
	}

	if ($level >= 0)
	{
		// cat footer
		if (($tree['type'][$CH_this] == POST_CAT_URL) && $pull_down)
		{
			$template->assign_block_vars('catrow', array());
			$template->assign_block_vars('catrow.catfoot', array('INC_SPAN' => $max_level - $level + 5));

			// add indentation to the display
			for ($k = 1; $k <= $level; $k++)
			{
				$template->assign_block_vars('catrow.catfoot.inc', array(
					'INC_SPAN' => $max_level - $level + 5,
					'INC_CLASS' => ($k % 2) ? $theme['td_class1'] : $theme['td_class2'],
					)
				);
			}
		}
	}

	// root level footer
	if (($config['split_cat'] && $cat_break && ($real_level == 0)) || ((!$config['split_cat'] || !$cat_break) && ($real_level == -1)))
	{
		$template->assign_block_vars('catrow', array());
		$template->assign_block_vars('catrow.tablefoot', array());
	}

	return $display;
}

//--------------------------------------------------------------------------------------------------
// display_index() : display the index using the tpl var {BOARD_INDEX}, return true if the index is not empty
//--------------------------------------------------------------------------------------------------
function display_index($cur = 'Root')
{
	global $db, $config, $template, $images, $user, $lang;
	global $nav_separator, $nav_cat_desc;
	global $tree;

	$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;

	$template->set_filenames(array('index' => 'index_box.tpl'));

	// moderators list
	$forum_moderators = array();
	@reset($tree['mods']);
	while (list($idx, $data) = @each($tree['mods']))
	{
		if ($tree['type'][$idx] == POST_FORUM_URL)
		{
			for ($i = 0; $i < sizeof($data['user_id']); $i++)
			{
				$forum_moderators[ $tree['id'][$idx] ][] = '<a href="' . append_sid(CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $data['user_id'][$i]) . '">' . $data['username'][$i] . '</a>';
			}
			for ($i = 0; $i < sizeof($data['group_id']); $i++)
			{
				$forum_moderators[ $tree['id'][$idx] ][] = '<a href="' . append_sid(CMS_PAGE_GROUP_CP . '?' . POST_GROUPS_URL . '=' . $data['group_id'][$i]) . '">' . $data['group_name'][$i] . '</a>';
			}
		}
	}

	// let's dump all of this on the template
	$keys = array();
	$display = build_index($cur, $config['split_cat'], $forum_moderators, -1, -1, $keys);

	// constants
	$template->assign_vars(array(
		'L_FORUM' => $lang['Forum'],
		'L_TOPICS' => $lang['Topics'],
		'L_POSTS' => $lang['Posts'],
		'L_LASTPOST' => $lang['Last_Post'],
		)
	);
	$template->assign_vars(array(
		'SPACER' => $images['spacer'],
		'NAV_SEPARATOR' => $nav_separator,
		'NAV_CAT_DESC' => $nav_cat_desc,
		)
	);
	if ($display) $template->assign_var_from_handle('BOARD_INDEX', 'index');

	return $display;
}

//--------------------------------------------------------------------------------------------------
// make_cat_nav_tree() : build the nav sentence
//--------------------------------------------------------------------------------------------------
function make_cat_nav_tree($cur, $pgm = '', $meta_content = '', $nav_class = 'nav')
{
	global $tree, $config, $user, $db, $nav_separator;
	global $global_orig_word, $global_replacement_word;

	$nav_separator = empty($nav_separator) ? (empty($lang['Nav_Separator']) ? '&nbsp;&raquo;&nbsp;' : $lang['Nav_Separator']) : $nav_separator;

	// Settings this to false will add the topic title to breadcrumbs
	$skip_topics = true;

	$kb_mode_append = '';
	$kb_mode_var = request_var('kb', '');
	if (!empty($kb_mode_var) && !$user->data['is_bot'])
	{
		if ($kb_mode_var == 'on')
		{
			$kb_mode_append = '&amp;kb=on';
		}
		elseif ($kb_mode_var == 'off')
		{
			$kb_mode_append = '&amp;kb=off';
		}
	}

	// get topic or post level
	$type = substr($cur, 0, 1);
	$id = intval(substr($cur, 1));
	$topic_title = '';
	$fcur = '';
	if (($type == POST_TOPIC_URL) || ($type == POST_POST_URL))
	{
		if ($type == POST_TOPIC_URL)
		{
			$sql_where = " WHERE t.topic_id = " . $id . " LIMIT 1";
		}
		elseif ($type == POST_POST_URL)
		{
			$sql_from = ", " . POSTS_TABLE . " p";
			$sql_where = " WHERE t.topic_id = p.topic_id AND p.post_id = " . $id . " LIMIT 1";
		}

		if (empty($meta_content['forum_id']) || empty($meta_content['topic_title']))
		{
			$sql = "SELECT t.forum_id, t.topic_title, t.topic_label_compiled
							FROM " . TOPICS_TABLE . " t" . $sql_from . $sql_where;
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				$meta_content['forum_id'] = $row['forum_id'];
				$meta_content['topic_title'] = $row['topic_title'];
				$meta_content['topic_label_compiled'] = $row['topic_label_compiled'];
			}
			$db->sql_freeresult($result);
		}

		$fcur = POST_FORUM_URL . $meta_content['forum_id'];
		$topic_title = (empty($meta_content['topic_label_compiled']) ? '' : ($meta_content['topic_label_compiled'] . ' ')) . $meta_content['topic_title'];
		$topic_title = censor_text($topic_title);
	}

	// keep the compliancy with prec versions
	if (!isset($tree['keys'][$cur]))
	{
		$cur = isset($tree['keys'][POST_CAT_URL . $cur]) ? POST_CAT_URL . $cur : $cur;
	}

	// find the object
	$CH_this = isset($tree['keys'][$cur]) ? $tree['keys'][$cur] : -1;

	$res = '';

	$cur_file_path_parts = pathinfo($_SERVER['SCRIPT_NAME']);
	$cur_filename = substr($cur_file_path_parts['basename'], 0, 5);
	$filenames_to_exclude = array('posti');

	// Convert and clean special chars!
	$topic_title = htmlspecialchars_clean($topic_title);
	while (($CH_this >= 0) || ($fcur != ''))
	{
		$type = (substr($fcur, 0, 1) != '') ? substr($cur, 0, 1) : $tree['type'][$CH_this];
		$is_topic = false;
		switch($type)
		{
			case POST_CAT_URL:
				$field_name = get_object_lang($cur, 'name');
				$param_type = POST_CAT_URL;
				$param_value = $tree['id'][$CH_this];
				$pgm_name = CMS_PAGE_FORUM;
				break;
			case POST_FORUM_URL:
				$field_name = get_object_lang($cur, 'name');
				$param_type = POST_FORUM_URL;
				$param_value = $tree['id'][$CH_this];
				$pgm_name = CMS_PAGE_VIEWFORUM;
				break;
			case POST_TOPIC_URL:
				$is_topic = true;
				$field_name = $topic_title;
				$param_type = POST_TOPIC_URL;
				$param_value = $id;
				$pgm_name = CMS_PAGE_VIEWTOPIC ;
				break;
			case POST_POST_URL:
				$is_topic = true;
				$field_name = $topic_title;
				$param_type = POST_POST_URL;
				$param_value = $id . '#p' . $id;
				$pgm_name = CMS_PAGE_VIEWTOPIC;
				break;
			default :
				$field_name = '';
				$param_type = '';
				$param_value = '';
				$pgm_name = CMS_PAGE_FORUM;
				break;
		}
		if ($pgm != '')
		{
			$pgm_name = $pgm . '.' . PHP_EXT;
		}

		//Dynamic Class Assignment - BEGIN
		$k = (empty($k) ? 1 : ($k + 1));
		$process_res = false;
		if ($k == 1)
		{
			$process_res = ($skip_topics && $is_topic) ? false : true;
			$res_class = (!in_array($cur_filename, $filenames_to_exclude) ? 'nav-current' : 'nav');
		}
		else
		{
			if (!empty($field_name) && ($fcur == ''))
			{
				$process_res = true;
				$res_class = $nav_class;
			}
		}
		//Dynamic Class Assignment - END

		if ($process_res)
		{
			$res = '<a href="' . append_sid($pgm_name . (($field_name != '') ? ('?' . $param_type . '=' . $param_value . $kb_mode_append) : '')) . '" class="' . $res_class . '">' . $field_name . '</a>' . (($res != '') ? $nav_separator . $res : '');
		}

		// find parent object
		if ($fcur != '')
		{
			$cur = $fcur;
			$pgm = '';
			$fcur = '';
			$topic_title = '';
		}
		else
		{
			$cur = $tree['main'][$CH_this];
		}
		$CH_this = isset($tree['keys'][$cur]) ? $tree['keys'][$cur] : -1;
	}

	return $res;
}

//--------------------------------------------------------------------------------------------------
// get_tree_option() : return a drop down menu list of <option></option>
//--------------------------------------------------------------------------------------------------
function get_tree_option($cur = '', $all = false)
{
	global $tree, $lang;

	$keys = array();
	$keys = get_auth_keys('Root', $all);
	$last_level = -1;
	$res = '';

	for ($i = 0; $i < sizeof($keys['id']); $i++)
	{
		// only get object that are not forum links type
		if (empty($tree['type'][$keys['idx'][$i]]) || empty($tree['data'][$keys['idx'][$i]]['forum_link']) || ($tree['type'][$keys['idx'][$i]] != POST_FORUM_URL))
		{
			$level = $keys['real_level'][$i];

			$inc = '';
			for ($k = 0; $k < $level; $k++)
			{
				$inc .= "[*$k*]&nbsp;&nbsp;&nbsp;";
			}

			if ($level < $last_level)
			{
			//insert spacer if level goes down
				$res .= '<option value="-1">' . $inc . '|&nbsp;&nbsp;&nbsp;</option>';
			// make valid lines solid
				$res = str_replace("[*$level*]", "|", $res);

			// erase all unnessecary lines
				for ($k = $level + 1; $k < $last_level; $k++)
				{
					$res = str_replace("[*$k*]", "&nbsp;", $res);
				}

			}
			elseif ($level == 0 && $last_level == -1)
			{
				$res .='<option value="-1">|</option>';
			}

			$last_level = $level;

			$selected = ($cur == $keys['id'][$i]) ? ' selected="selected"' : '';
			$res .= '<option value="' . $keys['id'][$i] . '"' . $selected . '>';

			// name
			$name = strip_tags(get_object_lang($keys['id'][$i], 'name', $all));

			if ($keys['level'][$i] >=0) $res .= $inc . '|--';

			$res .= $name . '</option>';
		}
	}

	// erase all unnessecary lines
	for ($k = 0; $k < $last_level; $k++)
	{
		$res = str_replace("[*$k*]", "&nbsp;", $res);
	}

	return $res;
}

/**
* Get Forums ID for several purpose
*/
function get_forums_ids($forum_types, $from_cache = false, $all_fields = false, $auth_view = false, $auth_read = false)
{
	global $db, $cache;

	$forums_array = array();
	$forum_types = (empty($forum_types) || !is_array($forum_types)) ? array(FORUM_POST) : $forum_types;
	$sql_what = $all_fields ? "*" : "forum_id, forum_name";
	$sql_append = '';
	$sql_append .= $auth_view ? (" AND auth_view = " . AUTH_ALL) : '';
	$sql_append .= $auth_read ? (" AND auth_read = " . AUTH_ALL) : '';
	$sql = "SELECT " . $sql_what . "
		FROM " . FORUMS_TABLE . "
		WHERE " . $db->sql_in_set('forum_type', $forum_types) . $sql_append . "
		ORDER BY forum_order";
	$result = $from_cache ? $db->sql_query($sql, 0, 'forums_', FORUMS_CACHE_FOLDER) : $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result))
	{
		$forums_array[] = $row;
	}
	$db->sql_freeresult($result);

	return $forums_array;
}

?>