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
		if (($tree['type'][$keys['idx'][$i]] != POST_FORUM_URL) || empty($tree['data'][$keys['idx'][$i]]['forum_link']))
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

	// erase all unnecessary lines
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