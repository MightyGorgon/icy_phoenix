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
* masterdavid - Ronald John David
* Zuker
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('imp_birthdays_func'))
{
	function imp_birthdays_func()
	{
		global $lang, $template, $portal_config, $block_id, $board_config, $db, $images;

		include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

		// Birthday Mod, Show users with birthday
		$cache_data_file = MAIN_CACHE_FOLDER . 'birthday_' . $board_config['board_timezone'] . '.dat';
		$cache_update = true;
		$cache_file_time = time();
		if (@is_file($cache_data_file))
		{
			$cache_file_time = @filemtime($cache_data_file);
			if (((date('YzH', time()) - date('YzH', $cache_file_time)) < 1) && ((date('Y', time()) == date('Y', $cache_file_time))))
			{
				$cache_update = false;
			}
		}

		if (!$cache_update)
		{
			include($cache_data_file);
			$birthday_today_list = stripslashes($birthday_today_list);
			$birthday_week_list = stripslashes($birthday_week_list);
		}
		else
		{
			if ($board_config['birthday_check_day'])
			{
				if ($board_config['inactive_users_memberlists'] == false)
				{
					$where_sql = 'AND user_active = 1';
				}
				else
				{
					$where_sql = '';
				}
				$sql = "SELECT user_id, username, user_birthday, user_level
								FROM " . USERS_TABLE . "
								WHERE user_birthday != 999999
									" . $where_sql . "
								ORDER BY username";

				if($result = $db->sql_query($sql))
				{
					if (!empty($result))
					{
						$time_now = time();
						$this_year = create_date('Y', $time_now, $board_config['board_timezone']);
						$date_today = create_date('Ymd', $time_now, $board_config['board_timezone']);
						$date_forward = create_date('Ymd', $time_now+($board_config['birthday_check_day'] * 86400), $board_config['board_timezone']);
						while ($birthdayrow = $db->sql_fetchrow($result))
						{
							$user_birthday2 = $this_year . ($user_birthday = realdate('md', $birthdayrow['user_birthday']));
							if ($user_birthday2 < $date_today)
							{
								$user_birthday2 += 10000;
							}
							if (($user_birthday2 > $date_today) && ($user_birthday2 <= $date_forward))
							{
								// user are having birthday within the next days
								$user_age = ($this_year.$user_birthday < $date_today) ? $this_year - realdate('Y', $birthdayrow['user_birthday']) + 1 : $this_year - realdate('Y', $birthdayrow['user_birthday']);
								$style_color = colorize_username($birthdayrow['user_id']);
								$birthday_week_list .= ' ' . $style_color . ' (' . $user_age . '),';
							}
							elseif ($user_birthday2 == $date_today)
							{
								//user have birthday today
								$user_age = $this_year - realdate('Y', $birthdayrow['user_birthday']);
								$style_color = colorize_username($birthdayrow['user_id']);
								$birthday_today_list .= ' ' . $style_color . ' (' . $user_age . '),';
							}
						}
						if ($birthday_today_list) $birthday_today_list[ strlen($birthday_today_list)-1] = ' ';
						if ($birthday_week_list) $birthday_week_list[ strlen($birthday_week_list)-1] = ' ';
					}
					$db->sql_freeresult($result);
					if (empty($SID))
					{
						// stores the data set in a cache file
						$data = "<?php\n";
						$data .= '$birthday_today_list = \'' . addslashes($birthday_today_list) . "';\n";
						$data .= '$birthday_week_list = \'' . addslashes($birthday_week_list) . "';\n?>";
						$fp = fopen($cache_data_file, "w");
						fwrite($fp, $data);
						fclose($fp);
					}
				}
			}
		}

		$birthday_today_list = stripslashes($birthday_today_list);
		$birthday_week_list = stripslashes($birthday_week_list);

		$template->assign_vars(array(
			'BIRTHDAY_IMG' => $images['birthday_image'],
			'L_WHOSBIRTHDAY_WEEK' => ($board_config['birthday_check_day'] > 1) ? sprintf((($birthday_week_list) ? $lang['Birthday_week'] : $lang['Nobirthday_week']), $board_config['birthday_check_day']) . $birthday_week_list : '',
			'L_WHOSBIRTHDAY_TODAY' => ($board_config['birthday_check_day']) ? ($birthday_today_list) ? $lang['Birthday_today'] . $birthday_today_list : $lang['Nobirthday_today'] : ''
			)
		);
	}
}

imp_birthdays_func();

?>