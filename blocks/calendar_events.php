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
		global $db, $cache, $config, $template, $theme, $userdata, $lang, $bbcode, $block_id, $cms_config_vars;

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
				while ($event_row = $db->sql_fetchrow($result))
				{
					$event_row['topic_title'] = censor_text($event_row['topic_title']);

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
						'L_EVENT_TITLE' => $event_row['topic_title'],
						)
					);
					$i++;
				}
				$db->sql_freeresult($result);
			}
		}
	}
}

cms_block_calendar_events();

?>