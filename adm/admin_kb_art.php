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

// CTracker_Ignore: File Checked By Human
// Tell the Security Scanner that reachable code in this file is not a security issue

define('IN_ICYPHOENIX', true);

if (!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1800_KB_title']['110_Art_man'] = $file;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'config.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_admin.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/kb_constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_auth.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_field.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_kb_mx.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_search.' . PHP_EXT);

include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

// Pull all config data

$sql = "SELECT *
	FROM " . KB_CONFIG_TABLE;
if (!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query config information in kb_config", "", __LINE__, __FILE__, $sql);
}
else
{
	while ($kb_row = $db->sql_fetchrow($result))
	{
		$config_name = $kb_row['config_name'];
		$config_value = $kb_row['config_value'];
		$kb_config[$config_name] = $config_value;
	}
}

if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
}
else
{
	if ($approve)
	{
		$mode = 'approve';
	}
	else if ($unapprove)
	{
		$mode = 'unapprove';
	}
	else if ($delete)
	{
		$mode = 'delete';
	}
	else
	{
		$mode = '';
	}
}

$start = (isset($_GET['start'])) ? intval($_GET['start']) : 0;
$start = ($start < 0) ? 0 : $start;
$article_id = intval($_GET['a']);

switch ($mode)
{
	case 'approve':
		$kb_custom_field = new kb_custom_field();

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

		// Insert comment, if not already present
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

		$message = $lang['Article_approved'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid('admin_kb_art.' . PHP_EXT) . '&amp;start=' . $start . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);

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
			message_die(GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql);
		}

		if ($kb_row = $db->sql_fetchrow($result))
		{
			$article_category_id = $kb_row['article_category_id'];
		}

		update_kb_number($article_category_id, '- 1');
		mx_remove_search_post($article_id, 'kb');

		$message = $lang['Article_unapproved'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid('admin_kb_art.' . PHP_EXT) . '&amp;start=' . $start  . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
		break;

	case 'delete':

		if ($_GET['c'] == 'yes')
		{
			$sql = "SELECT *
				FROM " . KB_ARTICLES_TABLE . "
				WHERE article_id = " . $article_id;

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Could not obtain article category", '', __LINE__, __FILE__, $sql);
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
					message_die(GENERAL_ERROR, 'Could not get poster id information', '', __LINE__, __FILE__, $sql);
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
							message_die(GENERAL_ERROR, 'Could not update user post count information', '', __LINE__, __FILE__, $sql);
						}
					}
				}

				$sql = "SELECT forum_id
					FROM " . TOPICS_TABLE . "
				WHERE topic_id = $topic";

				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not get forum id information', '', __LINE__, __FILE__, $sql);
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
					message_die(GENERAL_ERROR, 'Could not get post id information', '', __LINE__, __FILE__, $sql);
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
					message_die(GENERAL_ERROR, 'Could not delete topics', '', __LINE__, __FILE__, $sql);
				}

				if ($post_id_sql != '')
				{
					$sql = "DELETE
					FROM " . POSTS_TABLE . "
					WHERE topic_id = $topic";
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete posts', '', __LINE__, __FILE__, $sql);
					}

					for ($i = 0; $i < count($post_array); $i++)
					{
						$sql = "DELETE
						FROM " . POSTS_TEXT_TABLE . "
						WHERE post_id = $post_array[$i]";
						if (!$db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, 'Could not delete posts text', '', __LINE__, __FILE__, $sql);
						}
					}

					remove_search_post($post_id_sql);
				}

				$sql = "DELETE
				FROM " . TOPICS_WATCH_TABLE . "
				WHERE topic_id = $topic";
				if (!$db->sql_query($sql, END_TRANSACTION))
				{
					message_die(GENERAL_ERROR, 'Could not delete watched post list', '', __LINE__, __FILE__, $sql);
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

			$message = $lang['Article_deleted'] . '<br /><br />' . sprintf($lang['Click_return_article_manager'], '<a href="' . append_sid('admin_kb_art.' . PHP_EXT) . '&amp;start=' . $start . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid(IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?pane=right') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$message = $lang['Confirm_art_delete'] . '<br /><br />' . sprintf($lang['Confirm_art_delete_yes'], '<a href="' . append_sid('admin_kb_art.' . PHP_EXT) . '&amp;mode=delete&amp;c=yes&amp;a=' . $article_id . '&amp;start=' . $start . '">', '</a>') . '<br /><br />' . sprintf($lang['Confirm_art_delete_no'], '<a href="' . append_sid('admin_kb_art.' . PHP_EXT) . '&amp;start=' . $start . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		break;

	default:

		// Generate page

		$template->set_filenames(array('body' => ADM_TPL . 'kb_art_body.tpl'));

		// edited articles
		get_kb_articles('', 2, 'editrow', $start);
		// need to be approved
		get_kb_articles('', 0, 'notrow', $start);
		// Articles that are approved
		$total_articles = get_kb_articles('', 1, 'approverow', $start, $kb_config['art_pagination']);

		// Pagination
		$sql_pag = "SELECT count(article_id) AS total
			FROM " . KB_ARTICLES_TABLE . "
			WHERE ";

		$sql_pag .= " approved = '1'";

		if (!($result = $db->sql_query($sql_pag)))
		{
			mx_message_die(GENERAL_ERROR, 'Error getting total articles', '', __LINE__, __FILE__, $sql);
		}

		if ($total = $db->sql_fetchrow($result))
		{
			$total_articles = $total['total'];
			$pagination = generate_pagination(append_sid('admin_kb_art.' . PHP_EXT), $total_articles, $kb_config['art_pagination'], $start) . '&nbsp;';
		}

		if ($total_articles > 0)
		{
			$template->assign_block_vars('pagination', array());
		}

		$template->assign_vars(array(
				'PAGINATION' => $pagination,
				'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $kb_config['art_pagination']) + 1), ceil($total_articles / $kb_config['art_pagination'])),
				'L_GOTO_PAGE' => $lang['Goto_page'],
				'L_ARTICLE' => $lang['Article'],
				'L_ARTICLE_CAT' => $lang['Category'],
				'L_ARTICLE_TYPE' => $lang['Article_type'],
				'L_ARTICLE_AUTHOR' => $lang['Author'],
				'L_ACTION' => $lang['Art_action'],

				'L_APPROVED' => $lang['Art_approved'],
				'L_NOT_APPROVED' => $lang['Art_not_approved'],
				'L_EDITED' => $lang['Art_edit'],

				'L_KB_ART_TITLE' => $lang['Art_man'],
				'L_KB_ART_DESCRIPTION' => $lang['KB_art_description']
				)
			);

		break;
}

$template->pparse('body');

include_once(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>