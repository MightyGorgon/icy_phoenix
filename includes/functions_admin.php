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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
 * Function needed to fix config values before passing them to DB
*/
function fix_config_values($config_name, $config_value)
{
	global $new;
	if ($config_name == 'cookie_name')
	{
		$new['cookie_name'] = str_replace('.', '_', $new['cookie_name']);
	}

	// Attempt to prevent a common mistake with this value,
	// http:// is the protocol and not part of the server name
	if ($config_name == 'server_name')
	{
		$new['server_name'] = str_replace('http://', '', $new['server_name']);
	}

	if ($config_name == 'report_forum')
	{
		$new['report_forum'] = str_replace('f', '', $new['report_forum']);
	}

	if ($config_name == 'bin_forum')
	{
		$new['bin_forum'] = str_replace('f', '', $new['bin_forum']);
	}

	// Attempt to prevent a mistake with this value.
	if ($config_name == 'avatar_path')
	{
		$new['avatar_path'] = trim($new['avatar_path']);
		if (strstr($new['avatar_path'], "\0") || !is_dir(IP_ROOT_PATH . $new['avatar_path']) || !is_writable(IP_ROOT_PATH . $new['avatar_path']))
		{
			$new['avatar_path'] = $default_config['avatar_path'];
		}
	}
}

// Simple version of jumpbox, just lists authed forums
function make_forum_select($box_name, $ignore_forum = false, $select_forum = '')
{
	global $db, $userdata, $lang;

	$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

	$sql = 'SELECT f.forum_id, f.forum_name
		FROM ' . CATEGORIES_TABLE . ' c, ' . FORUMS_TABLE . ' f
		WHERE f.cat_id = c.cat_id
		ORDER BY c.cat_order, f.forum_order';
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn not obtain forums information', '', __LINE__, __FILE__, $sql);
	}

	$forum_list = '';
	while($row = $db->sql_fetchrow($result))
	{
		if ($is_auth_ary[$row['forum_id']]['auth_read'] && $ignore_forum != $row['forum_id'])
		{
			$selected = ($select_forum == $row['forum_id']) ? ' selected="selected"' : '';
			$forum_list .= '<option value="' . $row['forum_id'] . '"' . $selected .'>' . $row['forum_name'] . '</option>';
		}
	}

	$forum_list = ($forum_list == '') ? $lang['No_forums'] : '<select name="' . $box_name . '">' . $forum_list . '</select>';

	return $forum_list;
}

function make_topic_select($box_name, $forum_id)
{
	global $db, $userdata;

	$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

	$sql = "SELECT topic_id, topic_title
		FROM " . TOPICS_TABLE . "
		WHERE forum_id = $forum_id
		ORDER BY topic_title";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Couldn not obtain topics information', '', __LINE__, __FILE__, $sql);
	}

	$topic_list = '';
	while($row = $db->sql_fetchrow($result))
	{
		$topic_list .= '<option value="' . $row['topic_id'] . '">' . $row['topic_title'] . '</option>';
	}

	$topic_list = ($topic_list == '') ? '<option value="-1">-- ! No Topics ! --</option>' : '<select name="' . $box_name . '">' . $topic_list . '</select>';

	return $topic_list;
}

// Synchronise functions for forums/topics
function sync($type, $id = false)
{
	global $db;

	switch($type)
	{
		case 'all forums':
			$sql = "SELECT forum_id
				FROM " . FORUMS_TABLE;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get forum IDs', '', __LINE__, __FILE__, $sql);
			}

			while($row = $db->sql_fetchrow($result))
			{
				sync('forum', $row['forum_id']);
			}
			break;

		case 'all topics':
			$sql = "SELECT topic_id
				FROM " . TOPICS_TABLE;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get topic ID', '', __LINE__, __FILE__, $sql);
			}

			while($row = $db->sql_fetchrow($result))
			{
				sync('topic', $row['topic_id']);
			}
			break;

		case 'forum':
			$sql = "SELECT MAX(post_id) AS last_post, COUNT(post_id) AS total
				FROM " . POSTS_TABLE . "
				WHERE forum_id = $id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get post ID', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$last_post = ($row['last_post']) ? $row['last_post'] : 0;
				$total_posts = ($row['total']) ? $row['total'] : 0;
			}
			else
			{
				$last_post = 0;
				$total_posts = 0;
			}

			$sql = "SELECT COUNT(topic_id) AS total
				FROM " . TOPICS_TABLE . "
				WHERE forum_id = $id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get topic count', '', __LINE__, __FILE__, $sql);
			}

			$total_topics = ($row = $db->sql_fetchrow($result)) ? (($row['total']) ? $row['total'] : 0) : 0;

			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_last_post_id = $last_post, forum_posts = $total_posts, forum_topics = $total_topics
				WHERE forum_id = $id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update forum', '', __LINE__, __FILE__, $sql);
			}
			break;

		case 'topic':
			$sql = "SELECT MAX(post_id) AS last_post, MIN(post_id) AS first_post, COUNT(post_id) AS total_posts
				FROM " . POSTS_TABLE . "
				WHERE topic_id = $id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get post ID', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				if ($row['total_posts'])
				{
					// Correct the details of this topic
					$sql = 'UPDATE ' . TOPICS_TABLE . '
						SET topic_replies = ' . ($row['total_posts'] - 1) . ', topic_first_post_id = ' . $row['first_post'] . ', topic_last_post_id = ' . $row['last_post'] . "
						WHERE topic_id = $id";

					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not update topic', '', __LINE__, __FILE__, $sql);
					}
				}
				else
				{
					// There are no replies to this topic
					// Check if it is a move stub
					$sql = 'SELECT topic_moved_id
						FROM ' . TOPICS_TABLE . "
						WHERE topic_id = $id";

					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not get topic ID', '', __LINE__, __FILE__, $sql);
					}

					if ($row = $db->sql_fetchrow($result))
					{
						if (!$row['topic_moved_id'])
						{
							$sql = 'DELETE FROM ' . TOPICS_TABLE . " WHERE topic_id = $id";

							if (!$db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not remove topic', '', __LINE__, __FILE__, $sql);
							}
						}
					}

					$db->sql_freeresult($result);
				}
			}
			attachment_sync_topic($id);
			break;
	}

	global $board_config;
	board_stats();
	return true;
}

// Duplicate forum auth
function duplicate_auth($source_id, $target_id)
{
	global $db, $forum_auth_fields;

	$sql = "SELECT * FROM " . FORUMS_TABLE . "
					WHERE forum_id = '" . intval($source_id) . "'";
	if(!$result = $db->sql_query($sql))
	{
		return false;
	}

	$row = $db->sql_fetchrow($result);
	$auth_sql = '';
	for ($i = 0; $i < count($forum_auth_fields); $i++)
	{
		if ($i < (count($forum_auth_fields) - 1))
		{
			$comma_append = ', ';
		}
		else
		{
			$comma_append = '';
		}
		$auth_sql .= $forum_auth_fields[$i] . ' = \'' . $row[$forum_auth_fields[$i]] . '\'' . $comma_append;
	}

	$sql = "UPDATE " . FORUMS_TABLE . "
		SET ". $auth_sql . "
		WHERE forum_id = '" . intval($target_id) . "'";
	if(!$db->sql_query($sql))
	{
		return false;
	}
	return true;
}

/**
* Check MEM Limit
*/
function check_mem_limit()
{
	$mem_limit = @ini_get('memory_limit');
	if (!empty($mem_limit))
	{
		$unit = strtolower(substr($mem_limit, -1, 1));
		$mem_limit = (int) $mem_limit;

		if ($unit == 'k')
		{
			$mem_limit = floor($mem_limit / 1024);
		}
		elseif ($unit == 'g')
		{
			$mem_limit *= 1024;
		}
		elseif (is_numeric($unit))
		{
			$mem_limit = floor((int) ($mem_limit . $unit) / 1048576);
		}
		$mem_limit = max(128, $mem_limit) . 'M';
	}
	else
	{
		$mem_limit = '128M';
	}
	return $mem_limit;
}

/**
* Retrieve contents from remotely stored file
*/
function get_remote_file($host, $directory, $filename, &$errstr, &$errno, $port = 80, $timeout = 10)
{
	global $lang;

	if ($fsock = @fsockopen($host, $port, $errno, $errstr, $timeout))
	{
		@fputs($fsock, "GET $directory/$filename HTTP/1.1\r\n");
		@fputs($fsock, "HOST: $host\r\n");
		@fputs($fsock, "Connection: close\r\n\r\n");

		$file_info = '';
		$get_info = false;

		while (!@feof($fsock))
		{
			if ($get_info)
			{
				$file_info .= @fread($fsock, 1024);
			}
			else
			{
				$line = @fgets($fsock, 1024);
				if ($line == "\r\n")
				{
					$get_info = true;
				}
				else if (stripos($line, '404 not found') !== false)
				{
					$errstr = $lang['FILE_NOT_FOUND'] . ': ' . $filename;
					return false;
				}
			}
		}
		@fclose($fsock);
	}
	else
	{
		if ($errstr)
		{
			return false;
		}
		else
		{
			$errstr = $lang['FSOCK_DISABLED'];
			return false;
		}
	}

	return $file_info;
}

?>