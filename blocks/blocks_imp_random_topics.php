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

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

if(!function_exists(imp_random_topics_block_func))
{
	function imp_random_topics_block_func()
	{
		global $template, $cms_config_vars, $block_id, $userdata, $board_config, $db, $phpEx, $var_cache, $lang, $bbcode;
		@include_once($phpbb_root_path . 'includes/bbcode.' . $phpEx);

		$template->_tpldata['random_topic_row.'] = array();
		//reset($template->_tpldata['random_topic_row.']);

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;

		$allowed_forum_id = build_allowed_forums_list();

		if ($allowed_forum_id != '')
		{
			$allow_forum_id = $cms_config_vars['md_random_topics_forums'][$block_id];

			if ($allow_forum_id == '0')
			{
				$allowed_forums_sql = 'AND t.forum_id IN (' . $allowed_forum_id . ')';
			}
			else
			{
				$allowed_forums = explode(',', $allow_forum_id);
				$allowed_forums_tmp = explode(',', $allowed_forum_id);
				$allowed_forum_id = '';
				for ($i = 0; $i < count($allowed_forums); $i++)
				{
					for ($j = 0; $j < count($allowed_forums_tmp); $j++)
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

		$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username, f.forum_name
			FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u, " . FORUMS_TABLE . " AS f
			WHERE t.topic_status <> 2
				" . $allowed_forums_sql . "
				AND f.forum_id = t.forum_id
				AND p.post_id = t.topic_last_post_id
				AND p.poster_id = u.user_id
			ORDER BY RAND()
			LIMIT " . $cms_config_vars['md_num_random_topics'][$block_id];

		if (!$result1 = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query random topics information', '', __LINE__, __FILE__, $sql);
		}

		$number_random_topics = $db->sql_numrows($result1);
		$random_topic_row = array();

		while ($row1 = $db->sql_fetchrow($result1))
		{
			$random_topic_row[] = $row1;
		}

		if (($number_random_topics == 0) || ($no_topics_found == true))
		{
			$template->assign_block_vars('no_topics', array(
				'L_NO_TOPICS' => $lang['No_topics_found'],
				)
			);
		}
		else
		{
			if($cms_config_vars['md_random_topics_style'][$block_id])
			{
				$style_row = 'scroll';
			}
			else
			{
				$style_row = 'static';
			}

			$template->assign_block_vars($style_row, '');

			for ($i = 0; $i < $number_random_topics; $i++)
			{
				$orig_word = array();
				$replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);

				if (!empty($orig_word))
				{
					$random_topic_row[$i]['topic_title'] = (!empty($random_topic_row[$i]['topic_title'])) ? preg_replace($orig_word, $replacement_word, $random_topic_row[$i]['topic_title']) : '';
				}
				$random_topic_row[$i]['username'] = colorize_username($random_topic_row[$i]['user_id']);
				if ($random_topic_row[$i]['user_id'] != -1)
				{
					$template->assign_block_vars($style_row . '.random_topic_row', array(
						'U_FORUM' => append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $random_topic_row[$i]['forum_id']),
						'L_FORUM' => $random_topic_row[$i]['forum_name'],
						'U_TITLE' => append_sid(VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $random_topic_row[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $random_topic_row[$i]['topic_id'] . '&amp;' . POST_POST_URL . '=' . $random_topic_row[$i]['post_id']) . '#p' . $random_topic_row[$i]['post_id'],
						'L_TITLE' => $bbcode->parse($random_topic_row[$i]['topic_title'], $bbcode_uid),
						'L_BY' => $lang['By'],
						'L_ON' => $lang['On'],
						'U_POSTER' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $random_topic_row[$i]['user_id']),
						'S_POSTER' => $random_topic_row[$i]['username'],
						'S_POSTTIME' => create_date2($board_config['default_dateformat'], $random_topic_row[$i]['post_time'], $board_config['board_timezone'])
						)
					);
				}
				else
				{
					$template->assign_block_vars($style_row . '.random_topic_row', array(
						'U_TITLE' => append_sid(VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $random_topic_row[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $random_topic_row[$i]['topic_id'] . '&amp;' . POST_POST_URL . '=' . $random_topic_row[$i]['post_id']) . '#p' .$random_topic_row[$i]['post_id'],
						'L_TITLE' => $bbcode->parse($random_topic_row[$i]['topic_title'], $bbcode_uid),
						'L_BY' => $lang['By'],
						'U_POSTER' => append_sid(PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $random_topic_row[$i]['user_id']),
						'S_POSTER' => $random_topic_row[$i]['post_username'],
						'S_POSTTIME' => create_date2($board_config['default_dateformat'], $random_topic_row[$i]['post_time'], $board_config['board_timezone'])
						)
					);
				}
			}
		}
	}
}

imp_random_topics_block_func();

?>