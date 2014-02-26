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
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}
//define('MINI_CAL_FLAG', true);

if(!function_exists('cms_block_calendar_events'))
{
	function cms_block_calendar_events()
	{
		global $db, $cache, $config, $template, $theme, $user, $lang, $bbcode, $block_id, $cms_config_vars;

		$show_end_date = !empty($cms_config_vars['md_events_end'][$block_id]) ? true : false;
		$events_number = (int) $cms_config_vars['md_events_num'][$block_id];
		$events_number = ($events_number < 2) ? 10 : $events_number;
		$allow_forum_id = str_replace(' ', '', $cms_config_vars['md_events_forums_id'][$block_id]);
		$allow_forum_id_array = explode(',', $allow_forum_id);
		$allowed_forum_ids = build_allowed_forums_list(true);
		$allowed_forum_id_array = (!empty($allow_forum_id) ? array_intersect($allowed_forum_ids, $allow_forum_id_array) : $allowed_forum_ids);

		if (empty($allowed_forum_id_array))
		{
			$template->assign_var('NO_EVENTS', true);
		}
		else
		{
			$allowed_forum_ids_sql = ' AND t.forum_id IN (' . implode(',', $allowed_forum_id_array) . ') ';

			$sql = "SELECT t.*, f.forum_name
				FROM " . FORUMS_TABLE . " AS f, " . TOPICS_TABLE . " AS t
				WHERE t.topic_status <> 2
					" . $allowed_forums_sql . "
					AND f.forum_id = t.forum_id
					AND t.topic_calendar_time > " . time() . "
				ORDER BY t.topic_calendar_time ASC
				LIMIT " . $events_number;
			$result = $db->sql_query($sql);
			$events_number_counter = $db->sql_numrows($result);
			if (empty($result) || empty($events_number_counter))
			{
				$template->assign_var('NO_EVENTS', true);
			}
			else
			{
				$template->assign_var('SHOW_END_TIME', $show_end_date);
				$i = 0;
				$event_rows = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);
				$topic_ids = array();
				foreach ($event_rows as $event_row)
				{
					$topic_ids[] = $event_row['topic_id'];
				}

				if ($user->data['session_logged_in'] && !$user->data['is_bot'])
				{
					$sql = "SELECT topic_id, registration_status FROM " . REGISTRATION_TABLE . "
							WHERE " . $db->sql_in_set('topic_id', $topic_ids) . "
							AND registration_user_id = " . $user->data['user_id'];
					$result = $db->sql_query($sql);
					$reg_rows = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);
					$reg_array = array();
					foreach ($reg_rows as $reg_row)
					{
						$reg_array[$reg_row['topic_id']] = $reg_row['registration_status'];
					}
				}

				foreach ($event_rows as $event_row)
				{
					$event_row['topic_title'] = censor_text($event_row['topic_title']);

					$reg_info = '';
					if (!empty($event_row['topic_reg']) && $user->data['session_logged_in'] && !$user->data['is_bot'])
					{
						$reg_info = '&nbsp;<span class="text_orange">&bull;</span>';
						if (!empty($reg_array[$event_row['topic_id']]))
						{
							/*
							define('REG_OPTION1', 1);
							define('REG_OPTION2', 2);
							define('REG_OPTION3', 3);
							define('REG_UNREGISTER', 4);
							*/
							if ($reg_array[$event_row['topic_id']] == REG_OPTION1)
							{
								$reg_info = '&nbsp;<span class="text_green">&bull;</span>';
							}
							elseif ($reg_array[$event_row['topic_id']] == REG_OPTION2)
							{
								$reg_info = '&nbsp;<span class="text_blue">&bull;</span>';
							}
							elseif ($reg_array[$event_row['topic_id']] == REG_OPTION3)
							{
								$reg_info = '&nbsp;<span class="text_red">&bull;</span>';
							}
						}
					}

					$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
					$template->assign_block_vars('event_row', array(
						'ROW_CLASS' => $row_class,

						'EVENT_START_DATE' => gmdate($lang['DATE_FORMAT_DATE'], $event_row['topic_calendar_time']),
						'EVENT_START_TIME' => gmdate($lang['DATE_FORMAT_TIME'], $event_row['topic_calendar_time']),
						'EVENT_END_DATE' => gmdate($lang['DATE_FORMAT_DATE'], $event_row['topic_calendar_time'] + $event_row['topic_calendar_duration']),
						'EVENT_END_TIME' => gmdate($lang['DATE_FORMAT_TIME'], $event_row['topic_calendar_time'] + $event_row['topic_calendar_duration']),
						/*
						'EVENT_START_DATE' => create_date($lang['DATE_FORMAT_DATE'], $event_row['topic_calendar_time'], $config['board_timezone']),
						'EVENT_START_TIME' => create_date($lang['DATE_FORMAT_TIME'], $event_row['topic_calendar_time'], $config['board_timezone']),
						'EVENT_END_DATE' => create_date($lang['DATE_FORMAT_DATE'], $event_row['topic_calendar_time'], $config['board_timezone']),
						'EVENT_END_TIME' => create_date($lang['DATE_FORMAT_TIME'], $event_row['topic_calendar_time'], $config['board_timezone']),
						*/

						'U_EVENT_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $event_row['forum_id']),
						'L_EVENT_FORUM' => $event_row['forum_name'],
						'U_EVENT_TITLE' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $event_row['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $event_row['topic_id']),
						'L_EVENT_TITLE' => $event_row['topic_title'] . $reg_info,
						)
					);
					$i++;
				}
			}
		}
	}
}

cms_block_calendar_events();

?>