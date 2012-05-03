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

/**
* Configurable block 'forum_articles' - similar to forum_attach, except that:
* - It produces a list of forum topics (articles) or
* - displays a single forum topic (article) in 'news' format
* - It provides a print page without references to the original topic
* - It provides a 'tell a friend' link without reference to the original topic
* - It works in a CMS page without requiring a physical file
*
* Designed to be used where a private forum is used to produce articles.
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!function_exists('cms_block_forum_articles'))
{
	function cms_block_forum_articles()
	{
		global $db, $cache, $config, $template, $images, $lang, $bbcode, $block_id, $cms_config_vars, $meta_content, $breadcrumbs;

		if (!class_exists('class_topics'))
		{
			include(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
		}
		$class_topics = new class_topics();

		@include_once(IP_ROOT_PATH . ATTACH_MOD_PATH . 'displaying.' . PHP_EXT);

		$template->_tpldata['articles_fa.'] = array();

		$index_file = (!empty($_SERVER['SCRIPT_NAME'])) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
		$index_file = htmlspecialchars(urldecode($index_file));
		while (substr($index_file, 0, 1) == '/')
		{
			$index_file = substr($index_file, 1);
		}
		if ($index_file == ('index.' . PHP_EXT))
		{
			$prefix = '?page=' . request_var('page', 0) . '&';
		}
		else {
			$prefix = '?';
		}

		$meta_content['page_title_clean'] = empty($meta_content['page_title_clean']) ? strip_tags($meta_content['page_title']) : $meta_content['page_title_clean'];

		$template->set_filenames(array('forum_articles_block' => 'blocks/forum_articles_block.tpl'));

		$title = empty($cms_config_vars['md_posts_title'][$block_id]) ? $meta_content['page_title_clean'] : htmlspecialchars_clean($cms_config_vars['md_posts_title'][$block_id]);
		$template->assign_vars(array(
			'L_TITLE' => $title,
			'L_POSTED' => $lang['Posted'],
			'L_ANNOUNCEMENT' => $lang['Post_Announcement'],
			'L_PRINT_NEWS' => $lang['News_Print'],
			'L_EMAIL_NEWS' => $lang['News_Email'],
			'NEWS_PRINT_IMG' => $images['news_print'],
			'NEWS_EMAIL_IMG' => $images['news_email'],
			)
		);

		// $only_auth_view must have the opposite value of $cms_config_vars['md_ignore_auth_view'][$block_id]
		$only_auth_view = (!empty($cms_config_vars['md_ignore_auth_view'][$block_id]) || ($cms_config_vars['md_ignore_auth_view'][$block_id] == true)) ? false : true;

		$single_post_id = request_var('post_id', 0);
		if (!empty($single_post_id)) // single post
		{
			$fetchposts = $class_topics->fetch_posts($single_post_id, 1, 0, false, 0, true, $only_auth_view);
			for ($i = 0; $i < sizeof($fetchposts); $i++)
			{
				init_display_post_attachments($fetchposts[$i]['topic_attachment'], $fetchposts[$i], true, $block_id);

				$topic_title = htmlspecialchars_clean($fetchposts[$i]['topic_title']);
				$topic_link = $index_file . $prefix . 'post_id=' . $single_post_id;
				$template->assign_block_vars('articles_fa', array(
					'TOPIC_ID' => $fetchposts[$i]['topic_id'],
					'FORUM_ID' => $fetchposts[$i]['forum_id'],
					'TITLE' => $topic_title,
					'POSTER' => $fetchposts[$i]['username'],
					'POSTER_CG' => colorize_username($fetchposts[$i]['user_id'], $fetchposts[$i]['username'], $fetchposts[$i]['user_color'], $fetchposts[$i]['user_active']),
					'TIME' => $fetchposts[$i]['topic_time'],
					'VIEWS' => $fetchposts[$i]['topic_views'],
					'TEXT' => $fetchposts[$i]['post_text'],
					'U_PRINT_TOPIC' => append_sid('printarticle.' . PHP_EXT . '?' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id']),
					'U_EMAIL_TOPIC' => append_sid('tellafriend.' . PHP_EXT . '?topic_title=' . urlencode(ip_utf8_decode($fetchposts[$i]['topic_title'])) . '&amp;topic_url=' . urlencode(ip_utf8_decode($topic_link))),
					)
				);
				display_attachments($fetchposts[$i]['post_id'], 'articles');
			}
			if ($cms_config_vars['md_posts_breadcrumbs'][$block_id])
			{
				$meta_content['page_title_clean'] = empty($meta_content['page_title_clean']) ? strip_tags($meta_content['page_title']) : $meta_content['page_title_clean'];
				$breadcrumbs['address'] = '';
				if ($meta_content['page_title_clean'] != $config['sitename'])
				{
					$index_url = $index_file;
					if ($index_file == ('index.' . PHP_EXT))
					{
						$index_url .= '?page=' . request_var('page', 0);
					}
					$breadcrumbs['address'] .= $lang['Nav_Separator'] . '<a href="' . append_sid($index_url) . '">' . $meta_content['page_title_clean'] . '</a>';
				}
				$breadcrumbs['address'] .= $lang['Nav_Separator'] . '<a href="#" class="nav-current">' . $topic_title . '</a>';
			}
		}
		else // list
		{
			$fetchposts = $class_topics->fetch_posts($cms_config_vars['md_posts_forum_id'][$block_id], $cms_config_vars['md_num_posts'][$block_id], -1, $cms_config_vars['md_posts_show_portal'][$block_id], $cms_config_vars['md_posts_random'][$block_id], false, $only_auth_view);
			for ($i = 0; $i < sizeof($fetchposts); $i++)
			{
				// Convert and clean special chars!
				$topic_title = htmlspecialchars_clean($fetchposts[$i]['topic_title']);
				$template->assign_block_vars('articles_fa', array(
					'TOPIC_ID' => $fetchposts[$i]['topic_id'],
					'FORUM_ID' => $fetchposts[$i]['forum_id'],
					'TITLE' => $topic_title,
					'POSTER' => $fetchposts[$i]['username'],
					'POSTER_CG' => colorize_username($fetchposts[$i]['user_id'], $fetchposts[$i]['username'], $fetchposts[$i]['user_color'], $fetchposts[$i]['user_active']),
					'TIME' => $fetchposts[$i]['topic_time'],
					'VIEWS' => $fetchposts[$i]['topic_views'],
					'U_VIEW_TOPIC' => append_sid($index_file . $prefix . 'post_id=' . $fetchposts[$i]['topic_first_post_id']),
					)
				);
			}
			$template->assign_vars(array(
				'IS_LIST' => true,
				'L_TOPICS' => $lang['Topics'],
				'L_AUTHOR' => $lang['Author'],
				'L_VIEWS' => $lang['Views'],
				)
			);
			if ($cms_config_vars['md_posts_breadcrumbs'][$block_id])
			{
				$breadcrumbs['address'] = '';
				if ($meta_content['page_title_clean'] != $config['sitename'])
				{
					$breadcrumbs['address'] .= $lang['Nav_Separator'] . '<a href="#" class="nav-current">' . $meta_content['page_title_clean'] . '</a>';
				}
			}
		}
	}
}

cms_block_forum_articles();

?>
