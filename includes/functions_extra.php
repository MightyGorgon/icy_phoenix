<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

//--------------------------------------------------------------------------------------------------
// cache_words() : build the cache words file
//--------------------------------------------------------------------------------------------------
function cache_words()
{
	global $tree, $phpbb_root_path, $phpEx, $userdata, $db;

	if ( !defined('CACHE_WORDS') )
	{
		return;
	}

	// template
	include_once($phpbb_root_path . 'includes/template.' . $phpEx);
	$template = new Template($phpbb_root_path);

	$template->set_filenames(array('def_words' => 'includes/cache_tpls/def_words_def.tpl'));

	$template->assign_vars(array(
		'TIME' => date('Y-m-d H:i:s', time()) . ' (GMT)',
		'USERNAME' => $userdata['username'],
		)
	);

	$sql = "SELECT word, replacement FROM  " . WORDS_TABLE;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get censored words from database', '', __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$template->assign_block_vars('word', array(
			'WORD' => str_replace( "'", "\'", $row['word']),
			'REPLACEMENT' => str_replace( "'", "\'", $row['replacement']),
			)
		);
	}

	// transfert to a var
	$template->assign_var_from_handle('def_words', 'def_words');
	$res = '<' . '?' . 'php' . "\n" . $template->_tpldata['.'][0]['def_words'] . "\n" . 'return;' . "\n" . '?' . '>';
	// output to file
	$fname = $phpbb_root_path . './includes/def_words.' . $phpEx;
	@chmod($fname, 0666);
	$handle = @fopen($fname, 'w');
	@fwrite($handle, $res);
	@fclose($handle);
}

//--------------------------------------------------------------------------------------------------
// cache_themes() : buid the cache theme file
//--------------------------------------------------------------------------------------------------
function cache_themes()
{
	global $tree, $phpbb_root_path, $phpEx, $userdata, $db;

	if ( !defined('CACHE_THEMES') )
	{
		return;
	}

	// template
	include_once($phpbb_root_path . 'includes/template.' . $phpEx);
	$template = new Template($phpbb_root_path);

	$template->set_filenames(array('def_themes' => 'includes/cache_tpls/def_themes_def.tpl'));

	$template->assign_vars(array(
		'TIME' => date('Y-m-d H:i:s', time()) . ' (GMT)',
		'USERNAME' => $userdata['username'],
		)
	);

	$sql = "SELECT * FROM " . THEMES_TABLE;
	if( !$result = $db->sql_query($sql, false, 'themes_') )
	{
		message_die(GENERAL_ERROR, 'Could not read themes table', '', __LINE__, __FILE__, $sql);
	}
	while ( $row = $db->sql_fetchrow($result) )
	{
		$id = $row['themes_id'];
		$cells = array();
		@reset($row);
		while ( list($key, $value) = @each($row) )
		{
			$nkey = intval($key);
			if ( $key != "$nkey" )
			{
				$cells[] = sprintf( "'%s' => '%s'", str_replace("'", "\'", $key), str_replace("'", "\'", $value));
			}
		}
		$s_cells = empty($cells) ? '' : implode(', ', $cells);

		$template->assign_block_vars('theme', array(
			'ID' => $id,
			'CELLS' => $s_cells,
			)
		);
	}

	// transfert to a var
	$template->assign_var_from_handle('def_themes', 'def_themes');
	$res = '<' . '?' . 'php' . "\n" . $template->_tpldata['.'][0]['def_themes'] . "\n" . 'return;' . "\n" . '?' . '>';
	// output to file
	$fname = $phpbb_root_path . './includes/def_themes.' . $phpEx;
	@chmod($fname, 0666);
	$handle = @fopen($fname, 'w');
	@fwrite($handle, $res);
	@fclose($handle);
}

//--------------------------------------------------------------------------------------------------
// get_tree_option_optg() : return a drop down menu list of <option></option>
//--------------------------------------------------------------------------------------------------
function get_tree_option_optg($cur = '', $all = false, $opt_prefix = true)
{
	global $tree, $lang;

	$keys = array();
	$keys = get_auth_keys('Root', $all);
	$last_level = -1;
	$cat_open = false;

	for ($i = 0; $i < count($keys['id']); $i++)
	{
		// only get object that are not forum links type
		if ( ($tree['type'][$keys['idx'][$i]] != POST_FORUM_URL) || empty($tree['data'][$keys['idx'][$i]]['forum_link']) )
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
				//$res .='<option value="-1">' . $inc . '|&nbsp;&nbsp;&nbsp;</option>';
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
				//$res .='<option value="-1">|</option>';
			}

			$last_level = $level;

			if ($tree['type'][$keys['idx'][$i]] == POST_CAT_URL)
			{
				if ($cat_open == true)
				{
					$res .= '</optgroup>';
				}
				else
				{
					$cat_open = true;
				}
				$res .= '<optgroup label="';

				// name
				$name = get_object_lang($keys['id'][$i], 'name', $all);

				if ($keys['level'][$i] >= 0)
				{
					$res .= $inc . '|--';
				}

				$res .= $name . '">';
			}
			else
			{
				if ($keys['id'][$i] != 'Root')
				{
					$selected = ($cur == $keys['id'][$i]) ? ' selected="selected"' : '';
					if ($opt_prefix == true)
					{
						$res .= '<option value="' . $keys['id'][$i] . '"' . $selected . '>';
					}
					else
					{
						$res .= '<option value="' . str_replace(POST_FORUM_URL, '', $keys['id'][$i]) . '"' . $selected . '>';
					}

					// name
					$name = get_object_lang($keys['id'][$i], 'name', $all);

					if ($keys['level'][$i] >= 0)
					{
						$res .= $inc . '|--';
					}

					$res .= $name . '</option>';
				}
			}
		}
	}
	if ($cat_open == true)
	{
		$res .= '</optgroup>';
	}

	// erase all unnessecary lines
	for ($k = 0; $k < $last_level; $k++)
	{
		$res = str_replace("[*$k*]", "&nbsp;", $res);
	}

	return $res;
}

function get_db_stat($mode)
{
	global $db, $board_config;

	switch($mode)
	{
		case 'usercount':
			$sql = "SELECT COUNT(user_id) AS total
				FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS;
			break;

		case 'newestuser':
			if ($board_config['inactive_users_memberlists'] == true)
			{
				$sql_active_users = '';
			}
			else
			{
				$sql_active_users = 'AND user_active = 1';
			}
			$sql = "SELECT user_id, username
				FROM " . USERS_TABLE . "
				WHERE user_id <> " . ANONYMOUS . "
				$sql_active_users
				ORDER BY user_id DESC
				LIMIT 1";
			break;
		//Battle of the Sexes disabled
		/*
		case 'gender-male':
				$sql = "SELECT COUNT(user_id) AS total_male
						FROM " . USERS_TABLE . "
						WHERE user_gender = '1'";
				break;
		case 'gender-female':
				$sql = "SELECT COUNT(user_id) AS total_female
						FROM " . USERS_TABLE . "
						WHERE user_gender = '2'";
				break;
		*/
		//End Of Battle Sexes disabled
		case 'postcount':
		case 'topiccount':
			$sql = "SELECT SUM(forum_topics) AS topic_total, SUM(forum_posts) AS post_total FROM " . FORUMS_TABLE;
			break;
	}

	if (!($result = $db->sql_query($sql)))
	{
		return false;
	}

	$row = $db->sql_fetchrow($result);

	switch ($mode)
	{
		case 'usercount':
			return $row['total'];
			break;

		case 'newestuser':
			return $row;
			break;

		case 'postcount':
			return $row['post_total'];
			break;

		case 'topiccount':
			return $row['topic_total'];
			break;
		//Battle of the Sexes disabled
		/*
		case 'gender-male':
			return $row['total_male'];
			break;
		case 'gender-female':
			return $row['total_female'];
			break;
		*/
		//End Of Battle Sexes disabled
	}

	return false;
}

?>