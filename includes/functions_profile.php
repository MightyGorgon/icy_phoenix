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

function get_forum_most_active($user)
{
	global $db, $userdata;

	if ( intval($user) == 0 )
	{
		$user = trim(htmlspecialchars($user));
		$user = substr(str_replace("\\'", "'", $user), 0, 25);
		$user = str_replace("'", "\\'", $user);
	}
	else
	{
		$user = intval($user);
	}

	$sql_forum = "SELECT forum_id, forum_name FROM " . FORUMS_TABLE . " ORDER BY forum_id";
	if (!($result = $db->sql_query($sql_forum, false, 'forums_')))
	{
		message_die(GENERAL_ERROR, 'Could not obtain forums list', '', __LINE__, __FILE__, $sql_forum);
	}

	$most_active_id = array();
	while ( $line = $db->sql_fetchrow($result) )
	{
		$most_active_id[] = $line['forum_id'];
		$most_active_name[$line['forum_id']] = $line['forum_name'];
	}
	$db->sql_freeresult($result);

	$count_most_active_id = count($most_active_id);

	$most_active_posts = 0;
	$num_result = 0;

	foreach ($most_active_id as $i)
	{
		$is_auth = auth(AUTH_VIEW, $i, $userdata);
		if ($is_auth['auth_view'] == 1)
		{
			$sql_most = "SELECT *
				FROM " . POSTS_TABLE . "
				WHERE forum_id = $i AND poster_id = $user";
			if ( !($result = $db->sql_query($sql_most)) )
			{
				message_die(GENERAL_ERROR, 'Tried obtaining data for a non-existent user', '', __LINE__, __FILE__, $sql_most);
			}

			if ( $db->sql_numrows($result) > $most_active_posts )
			{
				$most_active_posts = $db->sql_numrows($result);
				$most_active_foren_id = $i;
				$most_active_forum_name = $most_active_name[$i];
			}
		}
	}

	return array('forum_id' => $most_active_foren_id, 'forum_name' => $most_active_forum_name, 'posts' => $most_active_posts);
}

function user_get_thanks_received($user_id)
{
	global $db;
	$total_thanks_received = 0;
	$sql = "SELECT COUNT(th.topic_id) AS total_thanks
					FROM " . THANKS_TABLE . " th, " . TOPICS_TABLE . " t
					WHERE t.topic_poster = '" . $user_id . "'
						AND t.topic_id = th.topic_id";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query thanks informations", "", __LINE__, __FILE__, $sql);
	}
	if (!$row = $db->sql_fetchrow($result))
	{
		message_die(GENERAL_ERROR, "Could not query thanks informations", "", __LINE__, __FILE__, $sql);
	}
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
	if(!($result = $db->sql_query($sql, false, 'profile_fields_')))
	{
		message_die(GENERAL_ERROR,'Could not select from ' . PROFILE_FIELDS_TABLE,'',__LINE__,__FILE__,$sql);
	}

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
			$list_size = count($data_list);
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