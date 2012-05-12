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

		$cms_config_vars['md_tlikes_timeframe'][$block_id] = 1 * 24 * 60 * 60;
		$cms_config_vars['md_tlikes_topics'][$block_id] = 10;
		$current_time = time();
		$delta_time = $current_time - $cms_config_vars['md_tlikes_timeframe'][$block_id];
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
				LIMIT 0, " . (int) $cms_config_vars['md_tlikes_topics'][$block_id];
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
			'S_TOPICS_LIKES' => $switch_topics_likes,
			'S_TOPICS_LIKES_BLOCK_ID' => $block_id,
			)
		);
	}
}

cms_block_top_likes();

?>