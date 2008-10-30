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
* MX-System - (jonohlsson@hotmail.com) - (www.mx-system.com)
*
*/

if(!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

include(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);

$category_id = (isset($_GET['cat'])) ? intval ($_GET['cat'])  : intval ($_POST['cat']);
$article_id = (isset($_GET['a'])) ? intval ($_GET['a'])  : intval ($_POST['a']);
$page_id = (isset($_GET['page'])) ? intval ($_GET['page'])  : intval ($_POST['page']);
$ref_stats = (isset($_GET['ref'])) ? true : 0;
$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;

// Start auth check
//
	$kb_is_auth = array();
	$kb_is_auth = kb_auth(AUTH_ALL, $category_id, $userdata);

// End of auth check
//

if (!(($kb_is_auth['auth_delete'] || $kb_is_auth['auth_mod']) && $userdata['session_logged_in']))
{
	$message = $lang['No_add'] . '<br /><br />' . sprintf($lang['Click_return_kb'], '<a href="' . append_sid(this_kb_mxurl()) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG) . '">', '</a>');
	mx_message_die(GENERAL_MESSAGE, $message);
}

if (isset($_POST['action']) || isset($_GET['action']))
{
	$action = (isset($_POST['action'])) ? $_POST['action'] : $_GET['action'];
}
else
{
	if ($approve && $kb_is_auth['auth_mod'])
	{
		$action = 'approve';
	}
	else if ($unapprove && $kb_is_auth['auth_mod'])
	{
		$action = 'unapprove';
	}
	else if ($delete && ($kb_is_auth['auth_mod'] || $kb_is_auth['auth_delete']))
	{
		$action = 'delete';
	}
	else
	{
		$action = '';
	}
}

switch ($action)
{
	case 'approve':

		$sql = "SELECT * FROM " . KB_ARTICLES_TABLE . " WHERE article_id = " . $article_id;
		if (!($results = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, "Could not obtain article data", '', __LINE__, __FILE__, $sql);
		}

		$kb_row = $db->sql_fetchrow($results);

		$topic_sql = '';

		$kb_comment = array();

		// Populate the kb_comment variable
		$kb_comment = kb_get_data($kb_row, $userdata);

		// Compose post header
		$subject = $lang['KB_comment_prefix'] . $kb_comment['article_title'];
		$message_temp = kb_compose_comment($kb_comment);

		$kb_message = $message_temp['message'];
		$kb_update_message = $message_temp['update_message'];

		if ($kb_config['use_comments'])
		{
			if (!$kb_row['topic_id'])
			{
				// Post
				$topic_data = kb_insert_post($kb_message, $subject, $kb_comment['category_forum_id'], $kb_comment['article_editor_id'], $kb_comment['article_editor'], $kb_comment['article_editor_sig'], $kb_comment['topic_id'], $kb_update_message);

				$topic_sql = ", topic_id = " . $topic_data['topic_id'];
			}
		}

		$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET approved = 1 " . $topic_sql . "
		 WHERE article_id = " . $article_id;

		if (!($result = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql);
		}

		$article_category_id = $kb_row['article_category_id'];

		update_kb_number($article_category_id, '+ 1');
		kb_notify($kb_config['notify'], $kb_message, $kb_config['admin_id'], $kb_comment['article_editor_id'], 'approved');
		mx_add_search_words('single', $article_id, stripslashes($kb_row['article_body']), stripslashes($kb_row['article_title']), 'kb');

		$message = $lang['Article_approved'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid(this_kb_mxurl("page=$page_id&mode=cat&cat=$category_id&start=$start")) . '">', '</a>') ;

		mx_message_die(GENERAL_MESSAGE, $message);
		break;

	case 'unapprove':

		$sql = "UPDATE " . KB_ARTICLES_TABLE . " SET approved = 0
		 WHERE article_id = " . $article_id;

		if (!($result = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, "Could not update article data", '', __LINE__, __FILE__, $sql);
		}

		$sql = "SELECT *
	 		FROM " . KB_ARTICLES_TABLE . "
			 WHERE article_id = " . $article_id;

		if (!($result = $db->sql_query($sql)))
		{
			mx_message_die(GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql);
		}

		if ($kb_row = $db->sql_fetchrow($result))
		{
			$article_category_id = $kb_row['article_category_id'];
		}

		update_kb_number($article_category_id, '- 1');
		mx_remove_search_post($article_id, 'kb');

		$message = $lang['Article_unapproved'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid(this_kb_mxurl("page=$page_id&mode=cat&cat=$category_id&start=$start")) . '">', '</a>') ;

		mx_message_die(GENERAL_MESSAGE, $message);
		break;

	case 'delete':

		if ($_GET['c'] == "yes")
		{
			$sql = "SELECT *
	 			FROM " . KB_ARTICLES_TABLE . "
	 			WHERE article_id = " . $article_id;

			if (!($result = $db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql);
			}

			if ($article = $db->sql_fetchrow($result))
			{
				$article_category_id = $article['article_category_id'];
			}

			if ($article['approved'] == 1)
			{
				update_kb_number($article_category_id, '- 1');
			}

			if ($kb_config['del_topic'] && $article['topic_id'])
			{
				$topic = $article['topic_id'];

				$sql = "SELECT poster_id, COUNT(post_id) AS posts
				FROM " . POSTS_TABLE . "
				WHERE topic_id = " . $topic . "
				GROUP BY poster_id";
				if (!($result = $db->sql_query($sql)))
				{
					mx_message_die(GENERAL_ERROR, 'Could not get poster id information', '', __LINE__, __FILE__, $sql);
				}

				$count_sql = array();
				while ($kb_row = $db->sql_fetchrow($result))
				{
					$count_sql[] = "UPDATE " . USERS_TABLE . "
					SET user_posts = user_posts - " . $kb_row['posts'] . "
					WHERE user_id = " . $kb_row['poster_id'];
				}
				$db->sql_freeresult($result);

				if (count($count_sql))
				{
					for($i = 0; $i < count($count_sql); $i++)
					{
						if (!$db->sql_query($count_sql[$i]))
						{
							mx_message_die(GENERAL_ERROR, 'Could not update user post count information', '', __LINE__, __FILE__, $sql);
						}
					}
				}

				$sql = "SELECT forum_id
			    FROM " . TOPICS_TABLE . "
				WHERE topic_id = $topic";

				if (!($result = $db->sql_query($sql)))
				{
					mx_message_die(GENERAL_ERROR, 'Could not get forum id information', '', __LINE__, __FILE__, $sql);
				}

				$forum_id = array();
				while ($kb_row = $db->sql_fetchrow($result))
				{
					$forum_id = $kb_row['forum_id'];
				}
				$db->sql_freeresult($result);

				$sql = "SELECT post_id
				FROM " . POSTS_TABLE . "
				WHERE topic_id = $topic";
				if (!($result = $db->sql_query($sql)))
				{
					mx_message_die(GENERAL_ERROR, 'Could not get post id information', '', __LINE__, __FILE__, $sql);
				}

				$post_array = array();
				$ii = 0;
				$post_id_sql = '';
				while ($kb_row = $db->sql_fetchrow($result))
				{
					$post_array[$ii] = $kb_row['post_id'];
					$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . $kb_row['post_id'];
					$ii++;
				}
				$db->sql_freeresult($result);

				// Got all required info so go ahead and start deleting everything

				$sql = "DELETE
				FROM " . TOPICS_TABLE . "
				WHERE topic_id = $topic
					OR topic_moved_id = $topic";
				if (!$db->sql_query($sql, BEGIN_TRANSACTION))
				{
					mx_message_die(GENERAL_ERROR, 'Could not delete topics', '', __LINE__, __FILE__, $sql);
				}

				if ($post_id_sql != '')
				{
					$sql = "DELETE
					FROM " . POSTS_TABLE . "
					WHERE topic_id = $topic";
					if (!$db->sql_query($sql))
					{
						mx_message_die(GENERAL_ERROR, 'Could not delete posts', '', __LINE__, __FILE__, $sql);
					}

					for ($i = 0; $i < count($post_array); $i++)
					{
						$sql = "DELETE
						FROM " . POSTS_TABLE . "
						WHERE post_id = " . $post_array[$i];
						if (!$db->sql_query($sql))
						{
							mx_message_die(GENERAL_ERROR, 'Could not delete posts', '', __LINE__, __FILE__, $sql);
						}
					}

					remove_search_post($post_id_sql);
				}

				$sql = "DELETE
				FROM " . TOPICS_WATCH_TABLE . "
				WHERE topic_id = $topic";
				if (!$db->sql_query($sql, END_TRANSACTION))
				{
					mx_message_die(GENERAL_ERROR, 'Could not delete watched post list', '', __LINE__, __FILE__, $sql);
				}
				if (!empty($forum_id))
				{
					sync('forum', $forum_id);
				}
			}

			$sql = "DELETE FROM  " . KB_ARTICLES_TABLE . " WHERE article_id = " . $article_id;

			if (!($result = $db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, "Could not delete article data", '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM  " . KB_MATCH_TABLE . " WHERE article_id = " . $article_id;

			if (!($result = $db->sql_query($sql)))
			{
				mx_message_die(GENERAL_ERROR, "Could not delete article wordmatch data", '', __LINE__, __FILE__, $sql);
			}

			mx_remove_search_post($article_id, 'kb');

			$message = $lang['Article_deleted'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid(this_kb_mxurl("page=$page_id&mode=cat&cat=$category_id&start=$start")) . '">', '</a>') ;

			mx_message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$category_id = ($ref_stats ? 1 : $category_id);

			$message = $lang['Confirm_art_delete'] . '<br /><br />' . sprintf($lang['Confirm_art_delete_yes'], '<a href="' . append_sid(this_kb_mxurl("mode=moderate&action=delete&page=$page_id&cat=$category_id&c=yes&a=$article_id&start=$start")) . '">', '</a>') . '<br /><br />' . sprintf($lang['Confirm_art_delete_no'], '<a href="' . append_sid(IP_ROOT_PATH . FORUM_MG . "?page=$page_id&mode=cat&cat=$category_id&start=$start") . '">', '</a>');
			mx_message_die(GENERAL_MESSAGE, $message);
		}
		break;


}

$template->pparse('body');

?>