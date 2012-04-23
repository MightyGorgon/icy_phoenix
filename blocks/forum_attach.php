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

if (!function_exists('cms_block_forum_attach'))
{
	function cms_block_forum_attach()
	{
		global $db, $cache, $config, $template, $images, $lang, $bbcode, $block_id, $cms_config_vars;

		if (!class_exists('class_topics'))
		{
			include(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
		}
		$class_topics = new class_topics();

		@include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'displaying.' . PHP_EXT);

		$template->_tpldata['articles_fp.'] = array();

		$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
		$index_file = htmlspecialchars(urldecode($index_file));

		$template->set_filenames(array('forum_attach_block' => 'blocks/forum_attach_block.tpl'));

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

		// $only_auth_view must have the opposite value of $cms_config_vars['md_ignore_auth_view'][$block_id]
		$only_auth_view = (!empty($cms_config_vars['md_ignore_auth_view'][$block_id]) || ($cms_config_vars['md_ignore_auth_view'][$block_id] == true)) ? false : true;
		if ($cms_config_vars['md_single_post_retrieve'][$block_id])
		{
			$single_post_id = request_var('post_id', 0);
			if ($cms_config_vars['md_single_post_auto_id'][$block_id])
			{
				$single_post_id = !empty($single_post_id) ? $single_post_id : $cms_config_vars['md_single_post_id'][$block_id];
			}
			else
			{
				$single_post_id = $cms_config_vars['md_single_post_id'][$block_id];
			}

			$fetchposts = $class_topics->fetch_posts($single_post_id, 1, $cms_config_vars['md_single_post_length'][$block_id], false, 0, true, $only_auth_view);
		}
		else
		{
			$fetchposts = $class_topics->fetch_posts($cms_config_vars['md_posts_forum_id'][$block_id], $cms_config_vars['md_num_posts'][$block_id], $cms_config_vars['md_posts_length'][$block_id], $cms_config_vars['md_posts_show_portal'][$block_id], $cms_config_vars['md_posts_random'][$block_id], false, $only_auth_view);
		}

		for ($i = 0; $i < sizeof($fetchposts); $i++)
		{
			init_display_post_attachments($fetchposts[$i]['topic_attachment'], $fetchposts[$i], true, $block_id);
			$open_bracket = '';
			$close_bracket = '';
			$read_full = '';
			if($fetchposts[$i]['striped'] == 1)
			{
				$open_bracket = '[ ';
				$close_bracket = ' ]';
				$read_full = $lang['Read_Full'];
			}

			// Convert and clean special chars!
			$topic_title = htmlspecialchars_clean($fetchposts[$i]['topic_title']);
			$template->assign_block_vars('articles_fp', array(
				'TOPIC_ID' => $fetchposts[$i]['topic_id'],
				'FORUM_ID' => $fetchposts[$i]['forum_id'],
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
				'U_READ_FULL' => append_sid($index_file . '?article=' . $i), // JHL never used thankfully - I don't think it would work
				'L_READ_FULL' => $read_full,
				'OPEN' => $open_bracket,
				'CLOSE' => $close_bracket,
				)
			);
			display_attachments($fetchposts[$i]['post_id'], 'articles_fp');
		}
	}
}

cms_block_forum_attach();

?>
