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

if(!function_exists(imp_recent_topics_wide_block_func))
{
	function imp_recent_topics_wide_block_func()
	{
		global $template, $cms_config_vars, $block_id, $userdata, $board_config, $db, $var_cache, $lang, $bbcode;
		global $html_on, $bbcode_on, $smilies_on;
		@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
		@include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

		$template->_tpldata['recent_topic_row.'] = array();
		//reset($template->_tpldata['recent_topic_row.']);

		$bbcode->allow_html = $html_on;
		$bbcode->allow_bbcode = $bbcode_on;
		$bbcode->allow_smilies = $smilies_on;

		$except_forums = build_exclusion_forums_list();

		//$current_time = time();
		//$extra = "AND t.topic_time <= $current_time";
		$extra = '';

		$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username, f.forum_name
			FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u, " . FORUMS_TABLE . " AS f
			WHERE t.forum_id NOT IN (" . $except_forums . ")
				AND t.topic_status <> 2
				AND p.post_id = t.topic_last_post_id
				AND p.poster_id = u.user_id
				AND f.forum_id = t.forum_id
				$extra
			ORDER BY p.post_time DESC
			LIMIT " . $cms_config_vars['md_num_recent_topics_wide'][$block_id];

		if (!$result1 = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query recent topics information', '', __LINE__, __FILE__, $sql);
		}
		$number_recent_topics = $db->sql_numrows($result1);
		$recent_topic_row = array();
		while ($row1 = $db->sql_fetchrow($result1))
		{
			$recent_topic_row[] = $row1;
		}

		if($cms_config_vars['md_recent_topics_wide_style'][$block_id] == 1)
		{
			$style_row = 'scroll';
		}
		else
		{
			$style_row = 'static';
		}

		$template->assign_block_vars($style_row, '');

		for ($i = 0; $i < $number_recent_topics; $i++)
		{
			$orig_word = array();
			$replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);

			if (!empty($orig_word))
			{
				$recent_topic_row[$i]['topic_title'] = (!empty($recent_topic_row[$i]['topic_title'])) ? preg_replace($orig_word, $replacement_word, $recent_topic_row[$i]['topic_title']) : '';
			}

			$recent_topic_row[$i]['username'] = colorize_username($recent_topic_row[$i]['user_id']);
			$template->assign_block_vars($style_row . '.recent_topic_row', array(
				'U_FORUM' => append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $recent_topic_row[$i]['forum_id']),
				'L_FORUM' => $recent_topic_row[$i]['forum_name'],
				'U_TITLE' => append_sid(VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $recent_topic_row[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $recent_topic_row[$i]['topic_id'] . '&amp;' . POST_POST_URL . '=' . $recent_topic_row[$i]['post_id']) . '#p' . $recent_topic_row[$i]['post_id'],
				'L_TITLE' => $bbcode->parse($recent_topic_row[$i]['topic_title'], $bbcode_uid, true),
				'L_BY' => $lang['By'],
				'L_ON' => $lang['On'],
				'S_POSTER' => $recent_topic_row[$i]['username'],
				'S_POSTTIME' => create_date2($board_config['default_dateformat'], $recent_topic_row[$i]['post_time'], $board_config['board_timezone'])
				)
			);
		}
	}
}

imp_recent_topics_wide_block_func();

?>