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

if(!function_exists('cms_block_recent_topics_wide'))
{
	function cms_block_recent_topics_wide()
	{
		global $db, $cache, $config, $template, $user, $lang, $block_id, $cms_config_vars;

		$template->_tpldata['recent_topic_row.'] = array();

		$except_forums = build_exclusion_forums_list();

		//$current_time = time();
		//$extra = "AND t.topic_time <= $current_time";
		$extra = '';

		$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username, u.user_active, u.user_color, f.forum_name, p.deleted
			FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u, " . FORUMS_TABLE . " AS f
			WHERE t.forum_id NOT IN (" . $except_forums . ")
				AND t.topic_status <> 2
				AND p.post_id = t.topic_last_post_id
				AND p.poster_id = u.user_id
				AND f.forum_id = t.forum_id
				AND p.deleted = false
				$extra
			ORDER BY p.post_time DESC
			LIMIT " . $cms_config_vars['md_num_recent_topics_wide'][$block_id];
		$result = $db->sql_query($sql);
		$number_recent_topics = $db->sql_numrows($result);
		$recent_topic_row = array();

		while ($row1 = $db->sql_fetchrow($result))
		{
			$recent_topic_row[] = $row1;
		}
		$db->sql_freeresult($result);

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
			$recent_topic_row[$i]['topic_title'] = censor_text($recent_topic_row[$i]['topic_title']);

			// Convert and clean special chars!
			$topic_title = htmlspecialchars_clean($recent_topic_row[$i]['topic_title']);
			$template->assign_block_vars($style_row . '.recent_topic_row', array(
				'U_FORUM' => append_sid(CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $recent_topic_row[$i]['forum_id']),
				'L_FORUM' => $recent_topic_row[$i]['forum_name'],
				'U_TITLE' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $recent_topic_row[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $recent_topic_row[$i]['topic_id'] . '&amp;' . POST_POST_URL . '=' . $recent_topic_row[$i]['post_id']) . '#p' . $recent_topic_row[$i]['post_id'],
				'L_TITLE' => $topic_title,
				'L_BY' => $lang['By'],
				'S_POSTER' => colorize_username($recent_topic_row[$i]['user_id'], $recent_topic_row[$i]['username'], $recent_topic_row[$i]['user_color'], $recent_topic_row[$i]['user_active']),
				'S_POSTTIME' => create_date_ip($config['default_dateformat'], $recent_topic_row[$i]['post_time'], $config['board_timezone'])
				)
			);
		}
	}
}

cms_block_recent_topics_wide();

?>