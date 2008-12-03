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
* Afkamm
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1100_General']['110_Autolinks'] = $file;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

if(isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
	$mode = htmlspecialchars($mode);
}

if($mode == 'save')
{
	$link_id = (isset($_POST['id'])) ? intval($_POST['id']) : 0;
	$keyword = (isset($_POST['keyword'])) ? trim($_POST['keyword']) : '';
	$title = (isset($_POST['title'])) ? trim($_POST['title']) : '';
	$url = (isset($_POST['url'])) ? trim($_POST['url']) : '';
	$comment = (isset($_POST['comment'])) ? trim($_POST['comment']) : '';
	$style = (isset($_POST['style'])) ? trim($_POST['style']) : '';
	$internal = (isset($_POST['internal'])) ? intval($_POST['internal']) : 0;
	$forum = (isset($_POST['link_forum'])) ? intval($_POST['link_forum']) : 0;
	$delete = (isset($_POST['delete'])) ? intval($_POST['delete']) : 0;

	if($delete)
	{
		$sql = "DELETE FROM " . AUTOLINKS . "
			WHERE link_id = " . $link_id;

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not remove data from autolinks table", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$db->clear_cache('autolinks_', TOPICS_CACHE_FOLDER);

		$message = $lang['Autolink_removed'] . '<br /><br />' . sprintf($lang['Click_return_autolinkadmin'], '<a href="' . append_sid('admin_autolinks.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		if($keyword == '' || $title == '' || $url == '')
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_autolink']);
		}

		if($link_id)
		{
			$sql = "UPDATE " . AUTOLINKS . "
				SET link_keyword = '" . str_replace("\'", "''", $keyword) . "', link_title = '" . str_replace("\'", "''", $title) . "', link_url = '" . str_replace("\'", "''", $url) . "', link_comment = '" . str_replace("\'", "''", $comment) . "', link_style = '" . str_replace("\'", "''", $style) . "', link_forum = '" . $forum . "', link_int = '" . $internal . "'
				WHERE link_id = " . $link_id;

			$message = $lang['Autolink_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . AUTOLINKS . " (link_keyword, link_title, link_url, link_comment, link_style, link_forum, link_int)
				VALUES ('" . str_replace("\'", "''", $keyword) . "', '" . str_replace("\'", "''", $title) . "', '" . str_replace("\'", "''", $url) . "', '" . str_replace("\'", "''", $comment) . "', '" . str_replace("\'", "''", $style) . "', $forum, $internal)";

			$message = $lang['Autolink_added'];
		}

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not insert data into autolinks table", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$db->clear_cache('autolinks_', TOPICS_CACHE_FOLDER);

		$message .= '<br /><br />' . sprintf($lang['Click_return_autolinkadmin'], '<a href="' . append_sid('admin_autolinks.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}
else
{
	$link_id_edit = (isset($_GET['id'])) ? intval($_GET['id']) : '';
	$forum_id = (isset($_GET['forum_id'])) ? intval($_GET['forum_id']) : 0;

	$template->set_filenames(array('body' => ADM_TPL . 'autolinks_body.tpl'));

	$sql = "SELECT forum_id, forum_name
		FROM " . FORUMS_TABLE . "
		ORDER BY cat_id, forum_order ASC";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
	}

	$forum_ids = array();
	$forum_names = array();
	$forum_ids[0] = '0';
	$forum_names[0] = $lang['Select_all_forums'];
	while($row = $db->sql_fetchrow($result))
	{
		$forum_ids[] = $row['forum_id'];
		$forum_names[] = $row['forum_name'];
	}

	$forum_list = '<select name="link_forum"><option value="0">' . $lang['Select_a_Forum'] . '</option>';
	$forum_list .= '<option value="0">&nbsp;</option>';

	if($link_id_edit)
	{
		$sql = "SELECT *
			FROM " . AUTOLINKS . "
			WHERE link_id = " . $link_id_edit;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query autolinks table", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		if($total_forums = count($forum_ids))
		{
			for($j = 0; $j < $total_forums; $j++)
			{
				$selected = ($forum_ids[$j] == $row['link_forum']) ? ' selected="selected"' : '';
				$forum_list .=  '<option value="' . $forum_ids[$j] . '"' . $selected . '>' . $forum_names[$j] . '</option>';

				if($j == 0)
				{
					$forum_list .= '<option value="0">&nbsp;</option>';
				}
			}
		}

		$forum_list .= '</select>';

		$template->assign_vars(array(
			'KEYWORD' => $row['link_keyword'],
			'TITLE' => $row['link_title'],
			'URL' => $row['link_url'],
			'COMMENT' => $row['link_comment'],
			'STYLE' => $row['link_style'],
			'S_JUMPBOX_SELECT' => $forum_list,
			'INTERNAL_NO' => ($row['link_int'] == '0') ? ' checked="checked"' : '',
			'INTERNAL_YES' => ($row['link_int'] == '1') ? ' checked="checked"' : ''
			)
		);

		$template->assign_block_vars('delete_link', array(
			'L_DELETE_LINK' => $lang['Delete_link']
			)
		);

	}
	else
	{
		$forum_list .= '<option value="0">' . $lang['Select_all_forums'] . '</option>';
		$forum_list .= '<option value="0">&nbsp;</option>';

		if($total_forums = count($forum_ids))
		{
			for($j = 1; $j < $total_forums; $j++)
			{
				$forum_list .=  '<option value="' . $forum_ids[$j] . '">' . $forum_names[$j] . '</option>';
			}
		}

		$forum_list .= '</select>';

		$template->assign_vars(array(
			'S_JUMPBOX_SELECT' => $forum_list
			)
		);
	}

	$template->assign_vars(array(
		'L_AUTOLINKS_TITLE' => $lang['Autolinks_title'],
		'L_AUTOLINKS_TEXT' => $lang['Autolinks_explain'],
		'L_FORM_TITLE' => ($mode == 'edit') ? $lang['Autolinks_edit'] : $lang['Autolinks_add'],
		'L_KEYWORD' => $lang['links_keyword'],
		'L_TITLE' => $lang['links_title'],
		'L_URL' => $lang['links_url'],
		'L_COMMENT' => $lang['links_comment'],
		'L_STYLE' => $lang['links_style'],
		'L_FORUM' => $lang['links_forum2'],
		'L_FORUMS' => $lang['links_forum'],
		'L_INTERNAL' => $lang['links_internal'],
		'L_SELECT_FORUM' => $lang['Select_forum'],
		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],
		'L_EDIT' => $lang['Edit'],
		'L_SUBMIT' => ($mode == 'edit') ? $lang['Edit_keyword'] : $lang['Add_keyword'],
		'L_ACTION' => $lang['Action'],

		'S_AUTOLINKS_ACTION' => append_sid('admin_autolinks.' . PHP_EXT),
		'S_HIDDEN_FIELDS' => ($mode == 'edit') ? '<input type="hidden" name="mode" value="save" /><input type="hidden" name="id" value="' . $link_id_edit . '" /><input type="hidden" name="forum_id" value="' . $forum_id . '" />' : '<input type="hidden" name="mode" value="save" />'
		)
	);

	$sql = "SELECT *
		FROM " . AUTOLINKS . "
		ORDER BY link_keyword";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query autolinks table", $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$autolink_rows = $db->sql_fetchrowset($result);
	if($autolink_count = count($autolink_rows))
	{
		for($i = 0; $i < $autolink_count; $i++)
		{
			$link_id = $autolink_rows[$i]['link_id'];
			$link_keyword = htmlspecialchars($autolink_rows[$i]['link_keyword']);
			$link_title = htmlspecialchars($autolink_rows[$i]['link_title']);
			$link_url = htmlspecialchars($autolink_rows[$i]['link_url']);
			$link_comment = htmlspecialchars($autolink_rows[$i]['link_comment']);
			$link_style = htmlspecialchars($autolink_rows[$i]['link_style']);
			$link_forum = $autolink_rows[$i]['link_forum'];
			$link_int = $autolink_rows[$i]['link_int'];

			$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

			$forum_id2 = array_search($link_forum, $forum_ids);

			$template->assign_block_vars('autolinks', array(
				'ROW_COLOR' => '#' . $row_color,
				'ROW_CLASS' => $row_class,
				'NUMBER' => $i + 1,
				'KEYWORD' => $link_keyword,
				'TITLE' => $link_title,
				'URL' => $link_url,
				'COMMENT' => $link_comment,
				'STYLE' => $link_style,
				'FORUM' => $forum_names[$forum_id2],
				'INTERNAL' => ($link_int == '1') ? $lang['Yes'] : $lang['No'],

				'U_KEYWORD_EDIT' => append_sid('admin_autolinks.' . PHP_EXT . '?mode=edit&amp;id=' . $link_id . '&amp;forum_id=' . $forum_id) . '#edit'
				)
			);
		}
	}
	else
	{
		$template->assign_block_vars('no_autolinks', array(
			'NO_AUTOLINKS' => $lang['No_autolinks'])
		);
	}
}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>