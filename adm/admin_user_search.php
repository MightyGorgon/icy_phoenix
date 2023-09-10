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
* Adam Alkins (phpbb at rasadam dot com)
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);
if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['280_User_Search'] = $filename;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

setup_extra_lang(array('lang_user_search'));

$meta_content['page_title'] = $lang['Search_users_advanced'];

if(!isset($_POST['dosearch']) && !isset($_GET['dosearch']))
{
	$sql = "SELECT group_id, group_name
				FROM " . GROUPS_TABLE . "
					WHERE group_single_user = 0
						ORDER BY group_name ASC";
	$result = $db->sql_query($sql);

	$group_list = '';

	if($db->sql_numrows($result) != 0)
	{
		$template->assign_block_vars('groups_exist', array());

		while($row = $db->sql_fetchrow($result))
		{
			$group_list .= '<option value="' . $row['group_id'] . '">' . strip_tags(htmlspecialchars($row['group_name'])) . '</option>';
		}
	}

	$language_list = language_select('language_type', '');
	$timezone_list = tz_select('timezone_type', '');

	$sql = "SELECT f.forum_id, f.forum_name, c.forum_id AS cat_id, c.forum_name AS cat_title
				FROM (". FORUMS_TABLE ." AS f INNER JOIN ". FORUMS_TABLE ." AS c ON c.forum_id = f.parent_id)
				ORDER BY f.forum_order ASC";
	$result = $db->sql_query($sql);

	$forums = array();

	if($db->sql_numrows($result) != 0)
	{
		$template->assign_block_vars('forums_exist', array());

		$last_cat_id = -1;

		$forums_list = '';

		while($row = $db->sql_fetchrow($result))
		{
			if($row['cat_id'] != $last_cat_id)
			{
				$forums_list .= '<optgroup label="' . $row['cat_title'] . '">';
				$last_cat_id = $row['parent_id'];
			}

			$forums_list .= '<option value="' . $row['forum_id'] . '">' . $row['forum_name'] . '</option>';
		}
	}

	$styles_list = style_select('style_type');

	$lastvisited = array(1, 7, 14, 30, 60, 120, 365, 500, 730, 1000);
	$lastvisited_list = '';

	foreach($lastvisited as $days)
	{
		$lastvisited_list .= '<option value="' . $days . '">' . $days . ' ' . (($days > 1) ? $lang['Days'] : $lang['Day']) . '</option>';
	}

	$template->set_filenames(array('body' => ADM_TPL . 'admin_user_search_form.tpl'));

	$template->assign_vars(array(
		'L_USER_SEARCH' => $lang['Search_users_advanced'],
		'L_SEARCH_EXPLAIN' => $lang['Search_users_explain'],
		'L_SEARCH' => $lang['Search'],
		'L_USERNAME' => $lang['Username'],
		'L_USERNAME_EXPLAIN' => $lang['Search_username_explain'],
		'L_EMAIL' => $lang['Email_address'],
		'L_EMAIL_EXPLAIN' => $lang['Search_email_explain'],
		'L_IP' => $lang['IP_Address'],
		'L_IP_EXPLAIN' => $lang['Search_ip_explain'],
		'L_USERS_JOINED' => $lang['Search_users_joined'],
		'L_BEFORE' => $lang['Before'],
		'L_AFTER' => $lang['After'],
		'L_REGEX' => $lang['Regular_expression'],
		'L_JOIN_DATE_EXPLAIN' => $lang['Search_users_joined_explain'],
		'L_GROUP_MEMBERS' => $lang['Group_Members'],
		'L_GROUP_EXPLAIN' => $lang['Search_users_groups_explain'],
		'L_ADMINS' => $lang['Administrators'],
		'L_MODS' => $lang['Moderators'],
		'L_BANNED_USERS' => $lang['Banned_users'],
		'L_DISABLED_USERS' => $lang['Disabled_users'],
		'L_USERS_DISABLED_PMS' => $lang['Users_disabled_pms'],
		'L_POSTCOUNT' => $lang['Postcount'],
		'L_POSTCOUNT_EXPLAIN' => $lang['Search_users_postcount_explain'],
		'L_EQUALS' => $lang['Equals'],
		'L_GREATERTHAN' => $lang['Greater_than'],
		'L_LESSERTHAN' => $lang['Less_than'],
		'L_USERFIELD' => $lang['Userfield'],
		'L_USERFIELD_EXPLAIN' => $lang['Search_users_userfield_explain'],
		'L_ICQ' => $lang['ICQ'],
		'L_AIM' => $lang['AIM'],
		'L_YAHOO' => $lang['YIM'],
		'L_MSN' => $lang['MSNM'],
		'L_WEBSITE' => $lang['Website'],
		'L_LOCATION' => $lang['Location'],
		'L_INTERESTS' => $lang['Interests'],
		'L_OCCUPATION' => $lang['Occupation'],
		'L_LASTVISITED' => $lang['Search_users_lastvisited'],
		'L_IN_THE_LAST' => $lang['in_the_last'],
		'L_AFTER_THE_LAST' => $lang['after_the_last'],
		'L_LASTVISITED_EXPLAIN' => $lang['Search_users_lastvisited_explain'],
		'L_LANGUAGE' => $lang['Board_lang'],
		'L_LANGUAGE_EXPLAIN' => $lang['Search_users_language_explain'],
		'L_TIMEZONE' => $lang['Timezone'],
		'L_TIMEZONE_EXPLAIN' => $lang['Search_users_timezone_explain'],
		'L_STYLE' => $lang['Board_style'],
		'L_STYLE_EXPLAIN' => $lang['Search_users_style_explain'],
		'L_MODERATORS_OF' => $lang['Moderators_of'],
		'L_MODERATORS_OF_EXPLAIN' => $lang['Search_users_moderators_explain'],
		'L_MISC_EXPLAIN' => $lang['Search_users_misc_explain'],

		'YEAR' => gmdate('Y'),
		'MONTH' => gmdate('m'),
		'DAY' => gmdate('d'),
		'GROUP_LIST' => $group_list,
		'LANGUAGE_LIST' => $language_list,
		'TIMEZONE_LIST' => $timezone_list,
		'FORUMS_LIST' => $forums_list,
		'STYLE_LIST' => $styles_list,
		'LASTVISITED_LIST' => $lastvisited_list,

		'S_SEARCH_ACTION' => append_sid('admin_user_search.' . PHP_EXT)
		)
	);
}
else
{
	$mode = '';

	// validate mode
	if(isset($_POST['search_username']) || isset($_GET['search_username']))
	{
		$mode = 'search_username';
	}
	elseif(isset($_POST['search_email']) || isset($_GET['search_email']))
	{
		$mode = 'search_email';
	}
	elseif(isset($_POST['search_ip']) || isset($_GET['search_ip']))
	{
		$mode = 'search_ip';
	}
	elseif(isset($_POST['search_joindate']) || isset($_GET['search_joindate']))
	{
		$mode = 'search_joindate';
	}
	elseif(isset($_POST['search_group']) || isset($_GET['search_group']))
	{
		$mode = 'search_group';
	}
	elseif(isset($_POST['search_postcount']) || isset($_GET['search_postcount']))
	{
		$mode = 'search_postcount';
	}
	elseif(isset($_POST['search_userfield']) || isset($_GET['search_userfield']))
	{
		$mode = 'search_userfield';
	}
	elseif(isset($_POST['search_lastvisited']) || isset($_GET['search_lastvisited']))
	{
		$mode = 'search_lastvisited';
	}
	elseif(isset($_POST['search_language']) || isset($_GET['search_language']))
	{
		$mode = 'search_language';
	}
	elseif(isset($_POST['search_timezone']) || isset($_GET['search_timezone']))
	{
		$mode = 'search_timezone';
	}
	elseif(isset($_POST['search_style']) || isset($_GET['search_style']))
	{
		$mode = 'search_style';
	}
	elseif(isset($_POST['search_moderators']) || isset($_GET['search_moderators']))
	{
		$mode = 'search_moderators';
	}
	elseif(isset($_POST['search_misc']) || isset($_GET['search_misc']))
	{
		$mode = 'search_misc';
	}

	// validate fields (that they exist)
	switch($mode)
	{
		case 'search_username':
			$username = request_var('username', '', true);
			$username = htmlspecialchars_decode($username, ENT_COMPAT);
			$regex = ($_POST['search_username_regex']) ? true : (request_var('regex', 0) ? true : false);

			if(!$username)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_username']);
			}

			break;

		case 'search_email':
			$email = request_var('email', '', true);
			$regex = ($_POST['search_email_regex']) ? true : (request_var('regex', 0) ? true : false);

			if(!$email)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_email']);
			}

			break;

		case 'search_ip':
			$ip_address = request_var('ip_address', '');

			if(!$ip_address)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_ip']);
			}
			break;

		case 'search_joindate':
			$date_type = request_var('date_type', '');
			$date_day = request_var('date_day', '');
			$date_month = request_var('date_month', '');
			$date_year = request_var('date_year', '');

			if(!$date_type || !$date_day || !$date_month || !$date_year)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_date']);
			}
			break;

		case 'search_group':
			$group_id = request_var('group_id', 0);
			if(empty($group_id))
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_group']);
			}
			break;

		case 'search_postcount':
			$postcount_type = request_var('postcount_type', '');
			$postcount_value = request_var('postcount_value', 0);

			if(!$postcount_type || (!$postcount_value && ($postcount_value != 0)))
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_postcount']);
			}
			break;

		case 'search_userfield':
			$userfield_type = request_var('userfield_type', '');
			$userfield_value = request_var('userfield_value', '');
			$regex = ($_POST['search_userfield_regex']) ? true : (request_var('regex', 0) ? true : false);

			if(!$userfield_type || !$userfield_value)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_userfield']);
			}

			break;

		case 'search_lastvisited':
			$lastvisited_days = request_var('lastvisited_days', '');
			$lastvisited_type = request_var('lastvisited_type', '');

			if(!$lastvisited_days || !$lastvisited_type)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_lastvisited']);
			}

			break;

		case 'search_language':
			$language_type = request_var('language_type', '');

			if(!$language_type)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_language']);
			}

			break;

		case 'search_timezone':
			$timezone_type = request_var('timezone_type', '');

			if(!$timezone_type && $timezone_type != 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_timezone']);
			}

			break;

		case 'search_style':
			$style_type = request_var('style_type', '');

			if(!$style_type)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_style']);
			}

			break;

		case 'search_moderators':
			$moderators_forum = request_var('moderators_forum', '');

			if(!$moderators_forum)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_moderators']);
			}

			break;

		case 'search_misc':
		default:
			$misc = request_var('misc', '');
			if(!$misc)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid']);
			}
	}

	$base_url = 'admin_user_search.' . PHP_EXT . '?dosearch=true';

	$select_sql = "SELECT u.user_id, u.username, u.user_email, u.user_posts, u.user_regdate, u.user_level, u.user_active, u.user_lastvisit
								FROM " . USERS_TABLE . " AS u";

	$lower_b = 'LOWER(';
	$lower_e = ')';
	if($regex)
	{
		$op = 'REGEXP';
		$lower_b = '';
		$lower_e = '';
	}

	// validate data & prepare sql
	switch($mode)
	{
		case 'search_username':
			$base_url .= '&search_username=true&username=' . rawurlencode($username);

			$text = sprintf($lang['Search_for_username'], strip_tags(htmlspecialchars($username)));

			if(!$regex)
			{
				$username = preg_replace('/\*/', '%', trim(strip_tags(strtolower($username))));

				if(strstr($username, '%'))
				{
					$op = 'LIKE';
				}
				else
				{
					$op = '=';
				}
			}
			else
			{
				$username = preg_replace('/\\\\\\\(?<!\'|"|NULL)/', '\\', $username);
			}

			if($username == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_username']);
			}

			$total_sql .= "SELECT COUNT(user_id) AS total
							FROM " . USERS_TABLE . "
								WHERE {$lower_b}username{$lower_e} $op '".$db->sql_escape($username)."'
									AND user_id <> " . ANONYMOUS;

			$select_sql .= " WHERE {$lower_b}u.username{$lower_e} $op '".$db->sql_escape($username)."'
									AND u.user_id <> " . ANONYMOUS;
			break;
		case 'search_email':
			$base_url .= '&search_email=true&email=' . rawurlencode($email);

			$text = sprintf($lang['Search_for_email'], strip_tags($email));

			if(!$regex)
			{
				$email = preg_replace('/\*/', '%', trim(strip_tags(strtolower($email))));

				if(strstr($email, '%'))
				{
					$op = 'LIKE';
				}
				else
				{
					$op = '=';
				}
			}
			else
			{
				$email = preg_replace('/\\\\\\\(?<!\'|"|NULL)/', '\\', $email);
			}

			if($email == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_email']);
			}

			$total_sql .= "SELECT COUNT(user_id) AS total
							FROM " . USERS_TABLE . "
								WHERE {$lower_b}user_email{$lower_e} $op '".$db->sql_escape($email)."'
									AND user_id <> " . ANONYMOUS;

			$select_sql .= " WHERE {$lower_b}u.user_email{$lower_e} $op '".$db->sql_escape($email)."'
									AND u.user_id <> " . ANONYMOUS;
			break;
		case 'search_ip':
			$base_url .= '&search_ip=true&ip_address=' . rawurlencode($ip_address);

			// Remove any whitespace
			$ip_address = trim($ip_address);

			$text = sprintf($lang['Search_for_ip'], strip_tags($ip_address));

			unset($users);
			$users = array();

			// Let's see if they entered a full valid IPv4 address
			if(preg_match('/^([0-9]{1,2}|[0-2][0-9]{0,2})(\.([0-9]{1,2}|[0-2][0-9]{0,2})){3}$/', $ip_address))
			{
				// Because we will be deleting based on IP's, we will store the encoded IP alone
				$users[] = $ip_address;
			}
			// We will also support wildcards, is this an xxx.xxx.* address?
			elseif(preg_match('/^([0-9]{1,2}|[0-2][0-9]{0,2})(\.([0-9]{1,2}|[0-2][0-9]{0,2})){0,2}\.\*/', $ip_address))
			{
				// Alright, now we do the ugly part, converting them to encoded ips
				// We need to deal with the three ways it can be done
				// xxx.*
				// xxx.xxx.*
				// xxx.xxx.xxx.*

				// First we will split the IP into its quads
				$ip_split = explode('.', $ip_address);

				// Now we'll work with which type of wildcard we have
				switch(sizeof($ip_split))
				{
					// xxx.xxx.xxx.*
					case 4:
						// We will encode the ip into hexademical quads
						$users[] = $ip_split[0] . "." . $ip_split[1] . "." . $ip_split[2] . ".255";
						break;
					// xxx.xxx.*
					case 3:
						// We will encode the ip into hexademical quads again..
						$users[] = $ip_split[0] . "." . $ip_split[1] . ".255.255";
						break;
					// xxx.*
					case 2:
						// We will encode the ip into hexademical quads again again....
						$users[] = $ip_split[0] . ".255.255.255";
						break;
				}
			}
			// Lastly, let's see if they have a range in the last quad, like xxx.xxx.xxx.xxx - xxx.xxx.xxx.yyy
			elseif(preg_match('/^([0-9]{1,2}|[0-2][0-9]{0,2})(\.([0-9]{1,2}|[0-2][0-9]{0,2})){3}(\s)*-(\s)*([0-9]{1,2}|[0-2][0-9]{0,2})(\.([0-9]{1,2}|[0-2][0-9]{0,2})){3}$/', $ip_address))
			{
				// We will split the two ranges
				$range = preg_split('/[-\s]+/', $ip_address);

				// This is where break the start and end ips into quads
				$start_range = explode('.', $range[0]);
				$end_range = explode('.', $range[1]);

				// Confirm if we are in the same subnet or the last quad in the beginning range is greater than the last in the ending range
				if(($start_range[0] . $start_range[1] . $start_range[2] != $end_range[0] . $end_range[1] . $end_range[2]) || ($start_range[3] > $end_range[3]))
				{
					message_die(GENERAL_MESSAGE, $lang['Search_invalid_ip']);
				}

				// Ok, we need to store each IP in the range..
				for($i = $start_range[3]; $i <= $end_range[3]; $i++)
				{
					// let's put it in the big array..
					$users[] = $start_range[0] . "." . $start_range[1] . "." . $start_range[2] . "." . $i;
				}
			}
			// This is not a valid IP based on what we want..
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_ip']);
			}

			$ip_in_sql = '';
			$ip_like_sql = '';

			foreach($users as $address)
			{
				// Is this IP a range?
				if(preg_match('/(ff){1,3}$/i', $address))
				{
					// num.xxx.xxx.xxx
					if(preg_match('/[0-9a-f]{2}ffffff/i', $address))
					{
						$ip_start = substr($address, 0, 2);
					}
					// num.num.xxx.xxx
					elseif(preg_match('/[0-9a-f]{4}ffff/i', $address))
					{
						$ip_start = substr($address, 0, 4);

					}
					// num.num.num.xxx
					elseif(preg_match('/[0-9a-f]{6}ff/i', $address))
					{
						$ip_start = substr($address, 0, 6);
					}

					$ip_like_sql .= ($ip_like_sql != '') ? (" OR poster_ip LIKE '" . $ip_start . "%'") : ("poster_ip LIKE '" . $ip_start . "%'");
				}
				else
				{
					$ip_in_sql .= ($ip_in_sql == '') ? "'$address'" : ", '$address'";
				}
			}

			$where_sql = '';

			$where_sql .= ($ip_in_sql != '') ? "poster_ip IN ($ip_in_sql)": "";

			$where_sql .= ($ip_like_sql != '') ? ($where_sql != "") ? " OR $ip_like_sql" : "$ip_like_sql": "";

			$sql = "SELECT poster_id
							FROM " . POSTS_TABLE . "
							WHERE poster_id <> " . ANONYMOUS . "
								AND ($where_sql)
							GROUP BY poster_id";
			$result = $db->sql_query($sql);

			if($db->sql_numrows($result) == 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_no_results']);
			}
			else
			{
				$total_pages['total'] = $db->sql_numrows($result);

				$total_sql = NULL;

				$ip_users_sql = '';

				while($row = $db->sql_fetchrow($result))
				{
					$ip_users_sql .= ($ip_users_sql == '') ? $row['poster_id'] : ', '.$row['poster_id'];
				}
			}

			$select_sql .= " WHERE u.user_id IN ($ip_users_sql)";

			break;
		case 'search_joindate':
			$base_url .= '&search_joindate=true&date_type=' . rawurlencode($date_type) . '&date_day=' . rawurlencode($date_day) . '&date_month=' . rawurlencode($date_month) . '&date_year=' . rawurlencode($date_year);

			$date_type = trim(strtolower($date_type));

			if($date_type != 'before' && $date_type != 'after')
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_date']);
			}

			$date_day = intval($date_day);

			if(!preg_match('/^([1-9]|[0-2][0-9]|3[0-1])$/', $date_day))
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_day']);
			}

			$date_month = intval($date_month);

			if(!preg_match('/^(0?[1-9]|1[0-2])$/', $date_month))
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_month']);
			}

			$date_year = intval($date_year);

			if(!preg_match('/^(20[0-9]{2}|19[0-9]{2})$/', $date_year))
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_year']);
			}

			$text = sprintf($lang['Search_for_date'], strip_tags($date_type), $date_year, $date_month, $date_day);

			$time = gmmktime(0, 0, 0, $date_month, $date_day, $date_year);

			if($date_type == 'before')
			{
				$arg = '<';
			}
			else
			{
				$arg = '>';
			}

			$total_sql .= "SELECT COUNT(user_id) AS total
								FROM " . USERS_TABLE . "
								WHERE user_regdate $arg $time
									AND user_id <> " . ANONYMOUS;

			$select_sql .= " WHERE u.user_regdate $arg $time
									AND u.user_id <> " . ANONYMOUS;

			break;
		case 'search_group':
			$group_id = intval($group_id);

			$base_url .= '&search_group=true&group_id=' . rawurlencode($group_id);

			if(!$group_id)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_group']);
			}

			$sql = "SELECT group_name
							FROM " . GROUPS_TABLE . "
							WHERE group_id = $group_id
								AND group_single_user = 0";
			$result = $db->sql_query($sql);

			if($db->sql_numrows($result) == 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_group']);
			}

			$group_name = $db->sql_fetchrow($result);

			$text = sprintf($lang['Search_for_group'], strip_tags($group_name['group_name']));

			$total_sql .= "SELECT COUNT(u.user_id) AS total
								FROM " . USERS_TABLE . " AS u, " . USER_GROUP_TABLE . " AS ug
								WHERE u.user_id = ug.user_id
										AND ug.group_id = $group_id
										AND u.user_id <> " . ANONYMOUS;

			$select_sql .= ", " . USER_GROUP_TABLE . " AS ug
									WHERE u.user_id = ug.user_id
										AND ug.group_id = $group_id
										AND u.user_id <> " . ANONYMOUS;

			break;
		case 'search_postcount':
			$postcount_type = trim(strtolower($postcount_type));
			$postcount_value = trim(strtolower($postcount_value));

			$base_url .= '&search_postcount=true&postcount_type=' . rawurlencode($postcount_type) . '&postcount_value=' . rawurlencode($postcount_value);

			switch($postcount_type)
			{
				case 'greater':
					$postcount_value = intval($postcount_value);

					$text = sprintf($lang['Search_for_postcount_greater'], $postcount_value);

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_posts > $postcount_value
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_posts > $postcount_value
											AND u.user_id <> " . ANONYMOUS;
					break;
				case 'lesser':
					$postcount_value = intval($postcount_value);

					$text = sprintf($lang['Search_for_postcount_lesser'], $postcount_value);

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_posts < $postcount_value
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_posts < $postcount_value
											AND u.user_id <> " . ANONYMOUS;
					break;
				case 'equals':
					// looking for a -
					if(strstr($postcount_value, '-'))
					{
						$range = preg_split('/[-\s]+/', $postcount_value);

						$range_begin = intval($range[0]);
						$range_end = intval($range[1]);

						if($range_begin > $range_end)
						{
							message_die(GENERAL_MESSAGE, $lang['Search_invalid_postcount']);
						}

						$text = sprintf($lang['Search_for_postcount_range'], $range_begin, $range_end);

						$total_sql .= "SELECT COUNT(user_id) AS total
											FROM " . USERS_TABLE . "
											WHERE user_posts >= $range_begin
												AND user_posts <= $range_end
												AND user_id <> " . ANONYMOUS;

						$select_sql .= " WHERE u.user_posts >= $range_begin
												AND u.user_posts <= $range_end
												AND u.user_id <> " . ANONYMOUS;
					}
					else
					{
						$postcount_value = intval($postcount_value);

						$text = sprintf($lang['Search_for_postcount_equals'], $postcount_value);

						$total_sql .= "SELECT COUNT(user_id) AS total
											FROM " . USERS_TABLE . "
											WHERE user_posts = $postcount_value
												AND user_id <> " . ANONYMOUS;

						$select_sql .= " WHERE u.user_posts = $postcount_value
												AND u.user_id <> " . ANONYMOUS;
					}
					break;
				default:
					message_die(GENERAL_MESSAGE, $lang['Search_invalid']);
			}

			break;
		case 'search_userfield':
			$base_url .= '&search_userfield=true&userfield_type=' . rawurlencode($userfield_type) . '&userfield_value=' . rawurlencode($userfield_value);

			$text = strip_tags(htmlspecialchars($userfield_value));

			if(!$regex)
			{
				$userfield_value = preg_replace('/\*/', '%', trim(strip_tags(strtolower($userfield_value))));

				if(strstr($userfield_value, '%'))
				{
					$op = 'LIKE';
				}
				else
				{
					$op = '=';
				}
			}
			else
			{
				$userfield_value = preg_replace('/\\\\\\\(?<!\'|"|NULL)/', '\\', $userfield_value);
			}

			if($userfield_value == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_userfield']);
			}

			$userfield_type = trim(strtolower($userfield_type));

			switch($userfield_type)
			{
				case 'icq':
					$text = sprintf($lang['Search_for_userfield_icq'],$text);
					$field = 'user_icq';
					break;
				case 'aim':
					$text = sprintf($lang['Search_for_userfield_aim'],$text);
					$field = 'user_aim';
					break;
				case 'msn':
					$text = sprintf($lang['Search_for_userfield_msn'],$text);
					$field = 'user_msnm';
					break;
				case 'yahoo':
					$text = sprintf($lang['Search_for_userfield_yahoo'],$text);
					$field = 'user_yahoo';
					break;
				case 'website':
					$text = sprintf($lang['Search_for_userfield_website'],$text);
					$field = 'user_website';
					break;
				case 'location':
					$text = sprintf($lang['Search_for_userfield_location'],$text);
					$field = 'user_from';
					break;
				case 'interests':
					$text = sprintf($lang['Search_for_userfield_interests'],$text);
					$field = 'user_interests';
					break;
				case 'occupation':
					$text = sprintf($lang['Search_for_userfield_occupation'],$text);
					$field = 'user_occ';
					break;
				default:
					message_die(GENERAL_MESSAGE, $lang['Search_invalid']);
			}

			$total_sql .= "SELECT COUNT(user_id) AS total
							FROM " . USERS_TABLE . "
								WHERE {$lower_b}$field{$lower_e} $op '" . $db->sql_escape($userfield_value) . "'
									AND user_id <> " . ANONYMOUS;

			$select_sql .= " WHERE {$lower_b}u.$field{$lower_e} $op '".$db->sql_escape($userfield_value)."'
									AND u.user_id <> " . ANONYMOUS;

			break;
		case 'search_lastvisited':
			$lastvisited_type = trim(strtolower($lastvisited_type));
			$lastvisited_days = intval($lastvisited_days);

			$base_url .= '&search_lastvisited=true&lastvisited_type=' . rawurlencode($lastvisited_type) . '&lastvisited_days=' . rawurlencode($lastvisited_days);

			$lastvisited_seconds = (time() - ((($lastvisited_days * 24) * 60) * 60));

			switch($lastvisited_type)
			{
				case 'in':
					$text = sprintf($lang['Search_for_lastvisited_inthelast'], $lastvisited_days, (($lastvisited_days > 1) ? $lang['Days'] : $lang['Day']));

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_lastvisit >= $lastvisited_seconds
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_lastvisit >= $lastvisited_seconds
											AND u.user_id <> " . ANONYMOUS;
					break;
				case 'after':
					$text = sprintf($lang['Search_for_lastvisited_afterthelast'], $lastvisited_days, (($lastvisited_days > 1) ? $lang['Days'] : $lang['Day']));

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_lastvisit < $lastvisited_seconds
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_lastvisit < $lastvisited_seconds
											AND u.user_id <> " . ANONYMOUS;

					break;
				default:
					message_die(GENERAL_MESSAGE, $lang['Search_invalid_lastvisited']);
			}

			break;
		case 'search_language':
			$base_url .= '&search_language=true&language_type=' . rawurlencode($language_type);

			$language_type = trim(strtolower(stripslashes($language_type)));

			if($language_type == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_language']);
			}

			$text = sprintf($lang['Search_for_language'], strip_tags($language_type));

			$total_sql .= "SELECT COUNT(user_id) AS total
								FROM " . USERS_TABLE . "
								WHERE user_lang = '" . $db->sql_escape($language_type) . "'
									AND user_id <> " . ANONYMOUS;

			$select_sql .= " WHERE u.user_lang = '" . $db->sql_escape($language_type) . "'
									AND u.user_id <> " . ANONYMOUS;

			break;
		case 'search_timezone':
			$base_url .= '&search_timezone=true&timezone_type=' . rawurlencode($timezone_type);
			$text = sprintf($lang['Search_for_timezone'], strip_tags($timezone_type));

			$timezone_type = intval($timezone_type);

			$total_sql .= "SELECT COUNT(user_id) AS total
								FROM " . USERS_TABLE . "
								WHERE user_timezone = $timezone_type
									AND user_id <> " . ANONYMOUS;

			$select_sql .= " WHERE u.user_timezone = $timezone_type
									AND u.user_id <> " . ANONYMOUS;

			break;
		case 'search_style':
			$base_url .= '&search_style=true&style_type=' . rawurlencode($style_type);

			$style_type = intval($style_type);

			$sql = "SELECT style_name
							FROM " . THEMES_TABLE . "
							WHERE themes_id = " . $style_type;
			$result = $db->sql_query($sql);

			if($db->sql_numrows($result) == 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_style']);
			}

			$style_name = $db->sql_fetchrow($result);

			$text = sprintf($lang['Search_for_style'], strip_tags($style_name['style_name']));

			$total_sql .= "SELECT COUNT(user_id) AS total
								FROM " . USERS_TABLE . "
								WHERE user_style = $style_type
									AND user_id <> " . ANONYMOUS;

			$select_sql .= " WHERE u.user_style = $style_type
									AND u.user_id <> " . ANONYMOUS;

			break;
		case 'search_moderators':
			$base_url .= '&search_moderators=true&moderators_forum=' . rawurlencode($moderators_forum);
			$moderators_forum = intval($moderators_forum);

			$sql = "SELECT forum_name
							FROM " . FORUMS_TABLE . "
							WHERE forum_id = " . $moderators_forum;
			$result = $db->sql_query($sql);

			if($db->sql_numrows($result)==0)
			{
				message_die(GENERAL_MESSAGE, $lang['Search_invalid_moderators']);
			}

			$forum_name = $db->sql_fetchrow($result);

			$text = sprintf($lang['Search_for_moderators'], strip_tags($forum_name['forum_name']));

			$total_sql .= "SELECT COUNT(DISTINCT u.user_id) AS total
							FROM " . USERS_TABLE . " AS u, " . GROUPS_TABLE . " AS g, " . USER_GROUP_TABLE . " AS ug, " . AUTH_ACCESS_TABLE . " AS aa
								WHERE u.user_id = ug.user_id
									AND ug.group_id = g.group_id
									AND g.group_id = aa.group_id
									AND aa.forum_id = " . $moderators_forum . "
									AND aa.auth_mod = 1
									AND u.user_id <> " . ANONYMOUS;

			$select_sql .= ", " . GROUPS_TABLE . " AS g, " . USER_GROUP_TABLE . " AS ug, " . AUTH_ACCESS_TABLE . " AS aa
								WHERE u.user_id = ug.user_id
									AND ug.group_id = g.group_id
									AND g.group_id = aa.group_id
									AND aa.forum_id = " . $moderators_forum . "
									AND aa.auth_mod = 1
									AND u.user_id <> ".ANONYMOUS."
								GROUP BY u.user_id, u.username, u.user_email, u.user_posts, u.user_regdate, u.user_level, u.user_active, u.user_lastvisit";
			break;
		case 'search_misc':
		default:
			$misc = trim(strtolower($misc));

			$base_url .= '&search_misc=true&misc=' . rawurlencode($misc);

			switch($misc)
			{
				case 'admins':
					$text = $lang['Search_for_admins'];

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")
											AND u.user_id <> " . ANONYMOUS;
					break;
				case 'mods':
					$text = $lang['Search_for_mods'];

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_level = " . MOD . "
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_level = ".MOD."
											AND u.user_id <> " . ANONYMOUS;
					break;
				case 'banned':
					$text = $lang['Search_for_banned'];

					$total_sql .= "SELECT COUNT(u.user_id) AS total
										FROM " . USERS_TABLE . " AS u, ".BANLIST_TABLE." AS b
										WHERE u.user_id = b.ban_userid
											AND u.user_id <> " . ANONYMOUS;

					$select_sql .= ", ".BANLIST_TABLE." AS b
										WHERE u.user_id = b.ban_userid
											AND u.user_id <> " . ANONYMOUS;

					break;
				case 'disabled':
					$text = $lang['Search_for_disabled'];

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_active = 0
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_active = 0
											AND u.user_id <> " . ANONYMOUS;

					break;
				case 'disabled_pms':
					$text = $lang['Search_for_disabled_pms'];

					$total_sql .= "SELECT COUNT(user_id) AS total
										FROM " . USERS_TABLE . "
										WHERE user_allow_pm = 0
											AND user_id <> " . ANONYMOUS;

					$select_sql .= " WHERE u.user_allow_pm = 0
											AND u.user_id <> " . ANONYMOUS;

					break;
				default:
					message_die(GENERAL_MESSAGE, $lang['Search_invalid']);
			}
	}

	if($regex)
	{
		$base_url .= '&regex=1';
	}

	$select_sql .= " ORDER BY ";

	$sort_method = request_var('sort', '');
	switch(strtolower($sort_method))
	{
		case 'regdate':
			$sort = 'regdate';
			$select_sql .= 'u.user_regdate';
			break;
		case 'posts':
			$sort = 'posts';
			$select_sql .= 'u.user_posts';
			break;
		case 'user_email':
			$sort = 'user_email';
			$select_sql .= 'u.user_email';
			break;
		case 'lastvisit':
			$sort = 'lastvisit';
			$select_sql .= 'u.user_lastvisit';
			break;
		case 'username':
		default:
			$sort = 'username';
			$select_sql .= 'u.username';
	}

	$sort_order = request_var('order', 'DESC');
	switch($sort_order)
	{
		case 'DESC':
			$order = 'DESC';
			$o_order = 'ASC';
			break;
		case 'DESC':
		default:
			$o_order = 'DESC';
			$order = 'ASC';
	}

	$select_sql .= " $order";

	$page = request_var('page', 0);

	if($page < 1)
	{
		$page = 1;
	}

	if($page == 1)
	{
		$offset = 0;
	}
	else
	{
		$offset = (($page - 1) * $config['topics_per_page']);
	}

	$limit = "LIMIT $offset, ".$config['topics_per_page'];

	$select_sql .= " $limit";

	if(!is_null($total_sql))
	{
		$result = $db->sql_query($total_sql);
		$total_pages = $db->sql_fetchrow($result);

		if($total_pages['total'] == 0)
		{
			message_die(GENERAL_MESSAGE, $lang['Search_no_results']);
		}
	}
	$num_pages = ceil(($total_pages['total'] / $config['topics_per_page']));

	$pagination = '';

	if($page > 1)
	{
		$pagination .= '<a href="' . append_sid("$base_url&sort=$sort&order=$order&page=" . ($page - 1)) . '">' . $lang['Previous'] . '</a>';
	}

	if($page < $num_pages)
	{
		$pagination .= ($pagination == '') ? '<a href="'.append_sid("$base_url&sort=$sort&order=$order&page=" . ($page + 1)) . '">' . $lang['Next'] . '</a>' : ' | <a href="' . append_sid("$base_url&sort=$sort&order=$order&page=" . ($page + 1)) . '">' . $lang['Next'] . '</a>';
	}

	if($num_pages > 2)
	{
		$pagination .= '&nbsp;&nbsp;<input type="text" name="page" maxlength="5" size="2" class="post" />&nbsp;<input type="submit" name="submit" value="' . $lang['Go'] . '" class="post" />';
	}

	$template->set_filenames(array('body' => ADM_TPL . 'admin_user_search_results.tpl'));

	$template->assign_vars(array(
		'L_USER_SEARCH' => $lang['Search_users_advanced'],
		'L_SORT_OPTIONS' => $lang['Sort_options'],
		'L_USERNAME' => $lang['Username'],
		'L_EMAIL' => $lang['Email_address'],
		'L_POSTS' => $lang['Posts'],
		'L_JOINDATE' => $lang['Joined'],
		'L_LASTVISIT' => $lang['Last_visit'],
		'L_MANAGE' => $lang['Manage'],
		'L_PERMISSIONS' => $lang['Permissions'],
		'L_ACCOUNT_STATUS' => $lang['Account_status'],

		'PAGE_NUMBER' => sprintf($lang['Page_of'], $page, $num_pages),
		'PAGINATION' => $pagination,
		'NEW_SEARCH' => sprintf($lang['Search_users_new'],$text, $total_pages['total'],append_sid('admin_user_search.' . PHP_EXT)),

		'U_USERNAME' => (($sort == 'username') ? append_sid("$base_url&sort=$sort&order=$o_order") : append_sid("$base_url&sort=username&order=$order")),
		'U_EMAIL' => (($sort == 'user_email') ? append_sid("$base_url&sort=$sort&order=$o_order") : append_sid("$base_url&sort=user_email&order=$order")),
		'U_POSTS' => (($sort == 'posts') ? append_sid("$base_url&sort=$sort&order=$o_order") : append_sid("$base_url&sort=posts&order=$order")),
		'U_JOINDATE' => (($sort == 'regdate') ? append_sid("$base_url&sort=$sort&order=$o_order") : append_sid("$base_url&sort=regdate&order=$order")),
		'U_LASTVISIT' => (($sort == 'lastvisit') ? append_sid("$base_url&sort=$sort&order=$o_order") : append_sid("$base_url&sort=lastvisit&order=$order")),

		'S_POST_ACTION' => append_sid("$base_url&sort=$sort&order=$order")
		)
	);

	$result = $db->sql_query($select_sql);
	$rowset = $db->sql_fetchrowset($result);

	$users_sql = '';
	foreach($rowset as $array)
	{
		$users_sql .= ($users_sql == '') ? $array['user_id'] : ', '.$array['user_id'];
	}

	$sql = "SELECT ban_userid AS user_id
					FROM " . BANLIST_TABLE . "
					WHERE ban_userid IN ($users_sql)";
	$result = $db->sql_query($sql);

	unset($banned);
	$banned = array();

	while($row = $db->sql_fetchrow($result))
	{
		$banned[$row['user_id']] = true;
	}

	for($i = 0; $i < sizeof($rowset); $i++)
	{
		$row_class = (($i % 2) == 1) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('userrow', array(
			'ROW_CLASS' => $row_class,
			'USERNAME' => $rowset[$i]['username'],
			'EMAIL' => $rowset[$i]['user_email'],
			'JOINDATE' => create_date($config['default_dateformat'], $rowset[$i]['user_regdate'], $config['board_timezone']),
			'LASTVISIT' => create_date($config['default_dateformat'], $rowset[$i]['user_lastvisit'], $config['board_timezone']),
			'POSTS' => $rowset[$i]['user_posts'],
			'BAN' => ((!isset($banned[$rowset[$i]['user_id']])) ? $lang['Not_banned'] : $lang['Banned']),
			'ABLED' => (($rowset[$i]['user_active']) ? $lang['Enabled'] : $lang['Disabled']),

			'U_VIEWPROFILE' => append_sid('../' . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $rowset[$i]['user_id']),
			'U_VIEWPOSTS' => append_sid('../' . CMS_PAGE_SEARCH . '?search_author=' . rawurlencode($rowset[$i]['username'])),
			'U_MANAGE' => append_sid('admin_users.' . PHP_EXT . '?mode=edit&amp;' . POST_USERS_URL . '=' . $rowset[$i]['user_id']),
			'U_PERMISSIONS' => append_sid('admin_ug_auth.' . PHP_EXT . '?mode=user&amp;' . POST_USERS_URL . '=' . $rowset[$i]['user_id']),
			)
		);
	}
}

// Spit out the page.
$template->pparse('body');

include('page_footer_admin.' . PHP_EXT);

?>