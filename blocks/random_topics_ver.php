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

if(!function_exists('cms_block_random_topics_ver'))
{
	function cms_block_random_topics_ver()
	{
		global $db, $cache, $config, $template, $user, $lang, $block_id, $cms_config_vars;

		$template->_tpldata['random_topic_ver_row.'] = array();

		$allowed_forum_id = build_allowed_forums_list();

		if ($allowed_forum_id != '')
		{
			$allow_forum_id = $cms_config_vars['md_random_topics_ver_forums'][$block_id];

			if ($allow_forum_id == '0')
			{
				$allowed_forums_sql = 'AND t.forum_id IN (' . $allowed_forum_id . ')';
			}
			else
			{
				$allowed_forums = explode(',', $allow_forum_id);
				$allowed_forums_tmp = explode(',', $allowed_forum_id);
				$allowed_forum_id = '';
				for ($i = 0; $i < sizeof($allowed_forums); $i++)
				{
					for ($j = 0; $j < sizeof($allowed_forums_tmp); $j++)
					{
						if ($allowed_forums[$i] == $allowed_forums_tmp[$j])
						{
							$allowed_forum_id .= $allowed_forums[$i] . ',';
							break;
						}
					}
				}

				if ($allowed_forum_id != '')
				{
					$allowed_forum_id = (substr($allowed_forum_id, -1, 1) == ',') ? substr($allowed_forum_id, 0, -1) : $allowed_forum_id;
					$allowed_forums_sql = 'AND t.forum_id IN (' . $allowed_forum_id . ')';
				}
				else
				{
					$no_topics_found = true;
				}
			}
		}
		else
		{
			$allowed_forums_sql = '';
		}

		$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username, u.user_active, u.user_color, p.deleted
			FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u, " . FORUMS_TABLE . " AS f
			WHERE t.topic_status <> 2
				" . $allowed_forums_sql . "
				AND f.forum_id = t.forum_id
				AND p.post_id = t.topic_last_post_id
				AND p.poster_id = u.user_id
				WHERE deleted = 0
			ORDER BY RAND()
			LIMIT " . $cms_config_vars['md_num_random_topics_ver'][$block_id];
		$result = $db->sql_query($sql);
		$number_random_topics = $db->sql_numrows($result);
		$random_topic_row = array();

		while ($row1 = $db->sql_fetchrow($result))
		{
			$random_topic_row[] = $row1;
		}
		$db->sql_freeresult($result);

		if (($number_random_topics == 0) || ($no_topics_found == true))
		{
			$template->assign_block_vars('no_topics', array(
				'L_NO_TOPICS' => $lang['No_topics_found'],
				)
			);
		}
		else
		{
			for ($i = 0; $i < $number_random_topics; $i++)
			{
				$random_topic_row[$i]['topic_title'] = censor_text($random_topic_row[$i]['topic_title']);

				// Convert and clean special chars!
				$topic_title = htmlspecialchars_clean($random_topic_row[$i]['topic_title']);
				$template->assign_block_vars('random_topic_ver_row', array(
					'U_TITLE' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $random_topic_row[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $random_topic_row[$i]['topic_id'] . '&amp;' . POST_POST_URL . '=' . $random_topic_row[$i]['post_id']) . '#p' . $random_topic_row[$i]['post_id'],
					'L_TITLE' => $topic_title,
					'L_BY' => $lang['By'],
					'L_ON' => $lang['POSTED_ON'],
					'S_POSTER' => colorize_username($random_topic_row[$i]['user_id'], $random_topic_row[$i]['username'], $random_topic_row[$i]['user_color'], $random_topic_row[$i]['user_active']),
					'S_POSTTIME' => create_date_ip($config['default_dateformat'], $random_topic_row[$i]['post_time'], $config['board_timezone'])
					)
				);
			}
		}
	}
}

cms_block_random_topics_ver();

?>