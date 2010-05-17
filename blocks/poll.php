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

if(!function_exists('cms_block_poll'))
{
	function cms_block_poll()
	{
		global $db, $cache, $config, $template, $images, $userdata, $lang, $block_id, $cms_config_vars;
		global $kb_mode_append, $is_auth, $lofi, $bbcode;

		$template->_tpldata['poll_option.'] = array();

		if ($cms_config_vars['md_poll_type'][$block_id] == 0)
		{
			$order_sql = 'ORDER BY t.topic_time DESC';
		}
		else
		{
			$order_sql = 'ORDER BY RAND()';
		}

		if (($cms_config_vars['md_poll_type'][$block_id] != 2) && !empty($cms_config_vars['md_poll_forum_id'][$block_id]))
		{
			$in_sql = 't.forum_id IN (' . $cms_config_vars['md_poll_forum_id'][$block_id] . ') AND';
		}
		elseif (!empty($cms_config_vars['md_poll_topic_id'][$block_id]))
		{
			$in_sql = 't.topic_id = ' . intval($cms_config_vars['md_poll_topic_id'][$block_id]) . ' AND';
		}

		$sql = "SELECT t.*
			FROM " . TOPICS_TABLE . " AS t
			WHERE " . $in_sql . " t.topic_status <> " . TOPIC_LOCKED . "
				AND t.topic_status <> " . TOPIC_MOVED . "
				AND t.poll_start <> 0
			" . $order_sql . "
			LIMIT 0,1";
		$result = $db->sql_query($sql);
		$total_topics = $db->sql_numrows($result);

		if (!empty($total_topics))
		{
			$topic_data = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			if (!class_exists('class_topics'))
			{
				@include_once(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
			}
			$class_topics = new class_topics();
			// Set some vars to make sure block is displayed correctly
			$forum_id_append = POST_FORUM_URL . '=' . $topic_data['forum_id'];
			$topic_id_append = POST_TOPIC_URL . '=' . $topic_data['topic_id'];

			// Store temp config value
			$portal_vote_graphic_length = $cms_config_vars['md_poll_bar_length'][$block_id];
			$config_vote_graphic_length = $config['vote_graphic_length'];
			$config['vote_graphic_length'] = $portal_vote_graphic_length;

			$class_topics->display_poll($topic_data, true);

			// Reset original config value
			$config['vote_graphic_length'] = $config_vote_graphic_length;

			$template->assign_vars(array(
				'S_POLL_EXISTS' => true,
				'U_VIEW_RESULTS' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . $forum_id_append . '&amp;' . $topic_id_append),
				)
			);
		}
		else
		{
			$template->assign_vars(array(
				'S_POLL_EXISTS' => false,
				)
			);
		}
	}
}

cms_block_poll();

?>