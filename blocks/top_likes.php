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
* Bicet
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if(!function_exists('cms_block_top_likes'))
{
	function cms_block_top_likes()
	{
		global $db, $cache, $config, $template, $lang, $block_id, $cms_config_vars;

		$template->_tpldata['likes_row.'] = array();

		$topic_likes_hours = (int) $cms_config_vars['md_top_likes_hours'][$block_id];
		$topic_likes_hours = (!empty($topic_likes_hours) ? $topic_likes_hours : 24);
		$topic_likes_timeframe = $topic_likes_hours * 60 * 60;
		$topic_likes_topics = (int) $cms_config_vars['md_top_likes_topics'][$block_id];
		$topic_likes_topics = (!empty($topic_likes_topics) ? $topic_likes_topics : 10);
		$current_time = time();
		$delta_time = $current_time - $topic_likes_timeframe;
		$cache_expiry = 1 * 1 * 60 * 60;

		$topics_likes = $cache->get('_topics_likes_' . $cache_expiry);

		if ($topics_likes === false)
		{
			$topics_likes = array();
			$sql = "SELECT COUNT(tl.topic_id) AS likes_count, t.topic_id, t.topic_title
				FROM " . POSTS_LIKES_TABLE . " AS tl, " . TOPICS_TABLE . " AS t
				WHERE tl.like_time > " . (int) $delta_time . "
					AND t.topic_id = tl.topic_id
				GROUP BY tl.topic_id
				ORDER BY likes_count DESC
				LIMIT 0, " . (int) $topic_likes_topics;
			$result = $db->sql_query($sql);
			$topics_likes = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);
			$cache->put('_topics_likes_' . $cache_expiry, $topics_likes, $cache_expiry);
		}

		$switch_topics_likes = false;
		if (!empty($topics_likes))
		{
			$switch_topics_likes = true;
			$row_class = '';
			$i = 0;
			foreach ($topics_likes as $topic)
			{
				$topic_title = censor_text($topic['topic_title']);
				$topic_title = htmlspecialchars_clean($topic_title);
				$row_class = ip_zebra_rows($row_class);
				$template->assign_block_vars('likes_row', array(
					'CLASS' => $row_class,
					'ROW_NUMBER' => $i + 1,

					'TOPIC_TITLE' => $topic_title,
					'TOPIC_TITLE_SHORT' => ((strlen($topic_title) > 24) ? substr($topic_title, 0, 18) . '...' : $topic_title),
					'U_TOPIC' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic['topic_id']),
					'LIKES_COUNT' => $topic['likes_count'],
					)
				);
				$i++;
			}
		}

		$template->assign_vars(array(
			'L_TOP_LIKES_NO_TOPICS_T' => sprintf($lang['TOP_LIKES_NO_TOPICS'], $topic_likes_hours),
			'L_TOP_LIKES_DESC_T' => sprintf($lang['TOP_LIKES_DESC'], $topic_likes_hours),

			'S_TOPICS_LIKES' => $switch_topics_likes,
			'S_TOPICS_LIKES_COUNTER' => (!empty($cms_config_vars['md_top_likes_counter'][$block_id]) ? true : false),
			'S_TOPICS_LIKES_BLOCK_ID' => $block_id,
			)
		);
	}
}

cms_block_top_likes();

?>