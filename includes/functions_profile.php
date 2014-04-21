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

// Add function mkrealdate for Birthday MOD
// the originate php "mktime()", does not work proberly on all OS, especially when going back in time
// before year 1970 (year 0), this function "mkrealtime()", has a mutch larger valid date range,
// from 1901 - 2099. it returns a "like" UNIX timestamp divided by 86400, so
// calculation from the originate php date and mktime is easy.
// mkrealdate, returns the number of day (with sign) from 1.1.1970.

function mkrealdate($day, $month, $birth_year)
{
	// range check months
	if (($month < 1) || ($month > 12))
	{
		return 'error';
	}
	// range check days
	switch ($month)
	{
		case 1:
			if ($day > 31) return 'error';
			break;
		case 2:
			if ($day > 29) return 'error';
			$epoch = $epoch + 31;
			break;
		case 3:
			if ($day > 31) return 'error';
			$epoch = $epoch + 59;
			break;
		case 4:
			if ($day > 30) return 'error' ;
			$epoch = $epoch + 90;
			break;
		case 5:
			if ($day > 31) return 'error';
			$epoch = $epoch + 120;
			break;
		case 6:
			if ($day > 30) return 'error';
			$epoch = $epoch + 151;
			break;
		case 7:
			if ($day > 31) return 'error';
			$epoch = $epoch + 181;
			break;
		case 8:
			if ($day > 31) return 'error';
			$epoch = $epoch + 212;
			break;
		case 9:
			if ($day > 30) return 'error';
			$epoch = $epoch + 243;
			break;
		case 10:
			if ($day > 31) return 'error';
			$epoch = $epoch + 273;
			break;
		case 11:
			if ($day > 30) return 'error';
			$epoch = $epoch + 304;
			break;
		case 12:
			if ($day > 31) return 'error';
			$epoch = $epoch + 334;
			break;
	}
	$epoch = $epoch + $day;
	$epoch_Y = sqrt(($birth_year - 1970) * ($birth_year - 1970));
	$leapyear = round((($epoch_Y + 2) / 4) - .5);
	if (($epoch_Y + 2) % 4 == 0)
	{// curent year is leapyear
		$leapyear--;
		if ($birth_year > 1970 && $month >= 3)
		{
			$epoch = $epoch + 1;
		}
		if ($birth_year < 1970 && $month < 3)
		{
			$epoch = $epoch - 1;
		}
	}
	elseif (($month == 2) && ($day > 28))
	{
		return 'error'; //only 28 days in feb.
	}
	//year
	if ($birth_year > 1970)
	{
		$epoch = $epoch + $epoch_Y * 365 - 1 + $leapyear;
	}
	else
	{
		$epoch = $epoch - $epoch_Y * 365 - 1 - $leapyear;
	}
	return $epoch;
}

// LAST VISIT - BEGIN
function make_hours($base_time)
{
	global $lang;
	$years = floor($base_time / 31536000);
	$base_time = $base_time - ($years * 31536000);
	$weeks = floor($base_time / 604800);
	$base_time = $base_time - ($weeks * 604800);
	$days = floor($base_time / 86400);
	$base_time = $base_time - ($days * 86400);
	$hours = floor($base_time / 3600);
	$base_time = $base_time - ($hours * 3600);
	$min = floor($base_time / 60);
	$sek = $base_time - ($min * 60);
	if ($sek < 10)
	{
		$sek = '0' . $sek;
	}
	if ($min < 10)
	{
		$min ='0' . $min;
	}
	if ($hours < 10)
	{
		$hours = '0' . $hours;
	}
	$result = (($years) ? $years . ' ' . (($years == 1) ? $lang['Year'] : $lang['Years']) . ', ' : '') . (($years || $weeks) ? $weeks . ' ' . (($weeks == 1) ? $lang['Week'] : $lang['Weeks']) . ', ' : '') . (($years || $weeks || $days) ? $days . ' ' . (($days == 1) ? $lang['Day'] : $lang['Days']) . ', ' : '') . (($hours) ? $hours . ':' : '00:') . (($min) ? $min . ':' : '00:') . $sek;
	return ($result) ? $result : $lang['None'];
}
// LAST VISIT - END

function get_forum_most_active($user_id)
{
	global $db, $cache, $config, $auth, $user, $lang;

	$user_id = (int) $user_id;
	if (empty($user_id))
	{
		return $user_most_active;
	}

	$most_active_id = array();

	$allowed_forums = build_allowed_forums_list(true);
	$forum_sql = (sizeof($allowed_forums) ? ' AND ' . $db->sql_in_set('forum_id', $allowed_forums) : '');

	// Obtain active forum
	// Maybe we should add a check on post count switch for forums? ==> " AND f.forum_postcount = 1"
	$sql = "SELECT forum_id, COUNT(post_id) AS num_posts
		FROM " . POSTS_TABLE . "
		WHERE poster_id = " . (int) $user_id . "
			$forum_sql
		GROUP BY forum_id
		ORDER BY num_posts DESC";
	$result = $db->sql_query_limit($sql, 1);
	$active_f_row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	if (!empty($active_f_row))
	{
		$sql = "SELECT forum_name
			FROM " . FORUMS_TABLE . "
			WHERE forum_id = " . $active_f_row['forum_id'];
		$result = $db->sql_query($sql, 0, 'forum_name_', FORUMS_CACHE_FOLDER);
		$active_f_row['forum_name'] = (string) $db->sql_fetchfield('forum_name');
		$db->sql_freeresult($result);
	}

	$active_f_name = $lang['No_Posts'];
	$active_f_id = 0;
	$active_f_count = 0;
	/*
	$active_f_pct = '';
	*/
	if (!empty($active_f_row['num_posts']))
	{
		$active_f_name = $active_f_row['forum_name'];
		$active_f_id = $active_f_row['forum_id'];
		$active_f_count = $active_f_row['num_posts'];
		/*
		$active_f_pct = ($userdata['user_posts']) ? ($active_f_count / $userdata['user_posts']) * 100 : 0;
		*/
	}

	return array('forum_id' => $active_f_id, 'forum_name' => $active_f_name, 'posts' => $active_f_count);
}

function user_get_thanks_received($user_id)
{
	global $db;

	$total_thanks_received = 0;
	$sql = "SELECT COUNT(th.topic_id) AS total_thanks
					FROM " . POSTS_LIKES_TABLE . " th, " . TOPICS_TABLE . " t
					WHERE t.topic_poster = '" . $user_id . "'
						AND t.topic_id = th.topic_id
						AND th.post_id = t.topic_first_post_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$total_thanks_received = $row['total_thanks'];
	$db->sql_freeresult($result);

	return $total_thanks_received;
}

// PROFILE FIELDS - BEGIN

function get_fields($where_clause = '', $expect_multiple = true, $selection = '*')
{
	global $db;

	$sql = "SELECT $selection FROM " . PROFILE_FIELDS_TABLE . "
		$where_clause
		ORDER BY field_id ASC";
	$result = $db->sql_query($sql, 0, 'profile_fields_');

	if($expect_multiple)
	{
		$profile_data = array();
		while($temp = $db->sql_fetchrow($result))
		{
			if(!empty($temp))
			{
				$profile_data[] = $temp;
			}
		}
	}
	else
	{
		$profile_data = $db->sql_fetchrow($result);
	}

	return $profile_data;
}

function text_to_column($text)
{
	$text = utf8_decode($text);
	$pattern = array("#&quot;#", "#&amp;#", "#&lt;#", "#&gt;#");
	$replace = array('"', '&', '<', '>');
	$text = preg_replace($pattern, $replace,$text);
	$pattern = "#[\s\*\$\(\)!\.,\-\?\/\\\[\]\{\};\:'´`\"&\^+=<>\|]#";
	$replace = "_";
	$text = preg_replace($pattern, $replace,$text);
	return strtolower($text);
}

function displayable_field_data($data, $type)
{
	global $lang;
	switch($type)
	{
		case TEXTAREA:
			return str_replace("\r\n", "<br />", $data);
			break;
		case TEXT_FIELD:
		case RADIO:
			return $data;
			break;
		case CHECKBOX:
			$data_list = explode(',', $data);
			$tmp = array();
			foreach($data_list as $val)
			{
				if(!empty($val))
				{
					$tmp[] = $val;
				}
			}
			$data_list = $tmp;
			$list_size = sizeof($data_list);
			$data = str_replace(',', ', ', $data);

			if($list_size == 0)
			{
				return '';
			}
			elseif($list_size == 1)
			{
				return $data_list[0];
			}
			else
			{
				return substr($data, 0, strrpos($data, ', ')) . $lang['and'] . substr($data, strrpos($data, ', ') + 2);
			}
	}
}

function get_topic_udata($postrow_data, $profile_data)
{
	global $lang;
	static $cp_udata_cache;

	$id = $postrow_data['user_id'];

	if (!$cp_udata_cache[$id])
	{
		$profile_names = array();
		$cp_udata_cache[$id]['aboves'] = array();
		$cp_udata_cache[$id]['belows'] = array();
		$cp_udata_cache[$id]['author'] = array();
		foreach($profile_data as $field)
		{
			$name = $field['field_name'];
			$col_name = text_to_column($field['field_name']);
			$type = $field['field_type'];
			$location = $field['topic_location'];

			$field_id = $field['field_id'];
			$field_name = $field['field_name'];
			if (isset($lang[$field_id . '_' . $field_name]))
			{
				$field_name = $lang[$field_id . '_' . $field_name];
			}

			$profile_names[$name] = displayable_field_data($postrow_data[$col_name], $field['field_type']);
			$tmp_field = $profile_names[$name];
			if (isset($lang[$field_id . '_' . $tmp_field]))
			{
				$profile_names[$name] = $lang[$field_id . '_' . $tmp_field];
			}

			if($location == AUTHOR)
			{
				//$cp_udata_cache[$id]['author'][] = ($profile_names[$name]) ? $name . ': ' . $profile_names[$name] : '';
				$cp_udata_cache[$id]['author'][] = ($profile_names[$name]) ? $field_name . ': ' . $profile_names[$name] : '';
			}
			elseif($location == ABOVE_SIGNATURE)
			{
				//$cp_udata_cache[$id]['aboves'][] = ($profile_names[$name]) ? $name . ': ' . $profile_names[$name] : '';
				$cp_udata_cache[$id]['aboves'][] = ($profile_names[$name]) ? $field_name . ': ' . $profile_names[$name] : '';
			}
			else
			{
				//$cp_udata_cache[$id]['belows'][] = ($profile_names[$name]) ? $name . ': ' . $profile_names[$name] : '';
				$cp_udata_cache[$id]['belows'][] = ($profile_names[$name]) ? $field_name . ': ' . $profile_names[$name] : '';
			}
		}
	}

	return $cp_udata_cache[$id];
}

function get_udata_txt($profile_data, $add = '')
{
	$cp_sql_txt = '';
	foreach($profile_data as $field)
	{
		$cp_sql_txt .= ', ' . $add . text_to_column($field['field_name']);
	}

	return $cp_sql_txt;
}

// PROFILE FIELDS - END

?>