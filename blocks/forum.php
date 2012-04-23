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

if(!function_exists('cms_block_forum'))
{
	function cms_block_forum()
	{
		global $db, $cache, $config, $template, $images, $lang, $bbcode, $block_id, $cms_config_vars;

		$template->_tpldata['fetchpost_row.'] = array();

		if (!class_exists('class_topics'))
		{
			include(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
		}
		$class_topics = new class_topics();

		$template->assign_vars(array(
			'L_COMMENTS' => $lang['Comments'],
			'L_VIEW_COMMENTS' => $lang['View_comments'],
			'L_POST_COMMENT' => $lang['Post_your_comment'],
			'L_POSTED' => $lang['Posted'],
			'L_ANNOUNCEMENT' => $lang['Post_Announcement'],
			'L_REPLIES' => $lang['Replies'],
			'L_REPLY_NEWS' => $lang['News_Reply'],
			'L_PRINT_NEWS' => $lang['News_Print'],
			'L_EMAIL_NEWS' => $lang['News_Email'],
			'MINIPOST_IMG' => $images['icon_minipost'],
			'NEWS_REPLY_IMG' => $images['news_reply'],
			'NEWS_PRINT_IMG' => $images['news_print'],
			'NEWS_EMAIL_IMG' => $images['news_email'],
			'IMG_CLOCK' => $images['news_clock'],
			)
		);

		$article = request_var('article', 0);
		if(!empty($article))
		{
			$cms_config_vars['md_news_length'][$block_id] = 0;
		}

		$fetchposts = $class_topics->fetch_posts($cms_config_vars['md_news_forum_id'][$block_id], $cms_config_vars['md_num_news'][$block_id], $cms_config_vars['md_news_length'][$block_id]);

		for ($i = 0; $i < sizeof($fetchposts); $i++)
		{
			$open_bracket = '';
			$close_bracket = '';
			$read_full = '';
			if(empty($article) && $fetchposts[$i]['striped'] == 1)
			{
				$open_bracket = '[ ';
				$close_bracket = ' ]';
				$read_full = $lang['Read_Full'];
			}

			// Convert and clean special chars!
			$topic_title = htmlspecialchars_clean($fetchposts[$i]['topic_title']);
			$template->assign_block_vars('fetchpost_row', array(
				'TOPIC_ID' => $fetchposts[$i]['topic_id'],
				'TITLE' => $topic_title,
				'POSTER' => $fetchposts[$i]['username'],
				'POSTER_CG' => colorize_username($fetchposts[$i]['user_id'], $fetchposts[$i]['username'], $fetchposts[$i]['user_color'], $fetchposts[$i]['user_active']),
				'TIME' => $fetchposts[$i]['topic_time'],
				'TEXT' => $fetchposts[$i]['post_text'],
				'REPLIES' => $fetchposts[$i]['topic_replies'],
				'U_VIEW_COMMENTS' => append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'] . '&amp;' . POST_POST_URL . '=' . $fetchposts[$i]['post_id'] . '#p' . $fetchposts[$i]['post_id'], true),
				'U_POST_COMMENT' => append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id']),
				'U_PRINT_TOPIC' => append_sid('printview.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'] . '&amp;start=0'),
				'U_EMAIL_TOPIC' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . urlencode(ip_utf8_decode($fetchposts[$i]['topic_title'])) . '&amp;topic_id=' . $fetchposts[$i]['topic_id']),
				'U_READ_FULL' => append_sid($index_file . '?article=' . $i),
				'L_READ_FULL' => $read_full,
				'OPEN' => $open_bracket,
				'CLOSE' => $close_bracket,
				)
			);
		}
	}
}

cms_block_forum();

?>
