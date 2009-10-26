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

if(!function_exists('cms_block_statistics'))
{
	function cms_block_statistics()
	{
		global $config, $template, $lang;
		$total_topics = $config['max_topics'];
		$total_posts = $config['max_posts'];
		$total_topics = $config['max_topics'];
		$total_posts = $config['max_posts'];
		$total_users = $config['max_users'];
		$newest_userdata['user_id'] = $config['last_user_id'];
		$newest_user = colorize_username($newest_userdata['user_id']);
		$newest_uid = $newest_userdata['user_id'];

		if($total_posts == 0)
		{
			$l_total_post_s = $lang['Posted_articles_zero_total'];
		}
		elseif($total_posts == 1)
		{
			$l_total_post_s = $lang['Posted_article_total'];
		}
		else
		{
			$l_total_post_s = $lang['Posted_articles_total'];
		}

		if($total_users == 0)
		{
			$l_total_user_s = $lang['Registered_users_zero_total'];
		}
		elseif($total_users == 1)
		{
			$l_total_user_s = $lang['Registered_user_total'];
		}
		else
		{
			$l_total_user_s = $lang['Registered_users_total'];
		}

		$template->assign_vars(array(
			'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
			'NEWEST_USER' => sprintf($lang['Newest_user'], '', $newest_user, ''),
			'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
			'TOTAL_TOPICS' => sprintf($lang['total_topics'], $total_topics)
			)
		);
	}
}

cms_block_statistics();

?>