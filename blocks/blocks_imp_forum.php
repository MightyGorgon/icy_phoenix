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

if(!function_exists(imp_forum_block_func))
{
	function imp_forum_block_func()
	{
		global $template, $lang, $cms_config_vars, $block_id, $board_config, $images, $_GET;

		$template->_tpldata['fetchpost_row.'] = array();
		//reset($template->_tpldata['fetchpost_row.']);

		include_once(IP_ROOT_PATH . 'fetchposts.' . PHP_EXT);

		$template->assign_vars(array(
			'L_COMMENTS' => $lang['Comments'],
			'L_VIEW_COMMENTS' => $lang['View_comments'],
			'L_POST_COMMENT' => $lang['Post_your_comment'],
			'L_POSTED' => $lang['Posted'],
			'L_ANNOUNCEMENT' => $lang['Post_Announcement']
			)
		);

		if(!isset($_GET['article']))
		{
			$template->assign_block_vars('welcome_text', array());

			$fetchposts = phpbb_fetch_posts($cms_config_vars['md_news_forum_id'][$block_id], $cms_config_vars['md_num_news'][$block_id], $cms_config_vars['md_news_length'][$block_id]);

			for ($i = 0; $i < count($fetchposts); $i++)
			{
				if($fetchposts[$i]['striped'] == 1)
				{
					$open_bracket = '[ ';
					$close_bracket = ' ]';
					$read_full = $lang['Read_Full'];
				}
				else
				{
					$open_bracket = '';
					$close_bracket = '';
					$read_full = '';
				}

				$template->assign_block_vars('fetchpost_row', array(
					'TITLE' => $fetchposts[$i]['topic_title'],
					'POSTER' => $fetchposts[$i]['username'],
					'TIME' => $fetchposts[$i]['topic_time'],
					'TEXT' => $fetchposts[$i]['post_text'],
					'REPLIES' => $fetchposts[$i]['topic_replies'],
					'U_VIEW_COMMENTS' => append_sid(VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id']),
					'U_POST_COMMENT' => append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id']),
					'U_READ_FULL' => append_sid(PORTAL_MG . '?article=' . $i),
					'L_READ_FULL' => $read_full,
					'OPEN' => $open_bracket,
					'CLOSE' => $close_bracket)
				);
			}
		}
		else
		{
			$fetchposts = phpbb_fetch_posts($cms_config_vars['md_news_forum_id'][$block_id], $cms_config_vars['md_num_news'][$block_id], 0);

			$i = intval($_GET['article']);

			$template->assign_block_vars('fetchpost_row', array(
				'TITLE' => $fetchposts[$i]['topic_title'],
				'POSTER' => $fetchposts[$i]['username'],
				'TIME' => $fetchposts[$i]['topic_time'],
				'TEXT' => $fetchposts[$i]['post_text'],
				'REPLIES' => $fetchposts[$i]['topic_replies'],
				'U_VIEW_COMMENTS' => append_sid(VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id']),
				'U_POST_COMMENT' => append_sid('posting.' . PHP_EXT . '?mode=reply&amp;' . POST_FORUM_URL . '=' . $fetchposts[$i]['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $fetchposts[$i]['topic_id'])
				)
			);
		}
	}
}

imp_forum_block_func();

?>