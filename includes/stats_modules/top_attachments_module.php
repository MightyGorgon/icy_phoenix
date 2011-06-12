<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Top Downloaded Attachments
$template->assign_vars(array(
	'L_ATTACHMENTS' => $lang['Attachments'],
	'L_FILENAME' => $lang['File_name'],
	'L_FILECOMMENT' => $lang['File_comment'],
	'L_SIZE' => $lang['Size_in_kb'],
	'L_DOWNLOADS' => $lang['Downloads'],
	'L_POST_TIME' => $lang['Post_time'],
	'L_POSTED_IN_TOPIC' => $lang['Posted_in_topic'],
	'L_TOP_DOWNLOADS' => $lang['module_name_top_attachments']
	)
);

$attachment_mod_installed = (defined('ATTACH_VERSION')) ? true : false;
$attachment_version = ($attachment_mod_installed) ? ATTACH_VERSION : '';

if (!$attachment_mod_installed)
{
	message_die(GENERAL_MESSAGE, "The Attachment Mod have to be installed in order to see the Top Downloaded Attachments.");
}

if (($attachment_version != '2.2.4') && (!strstr($attachment_version, '2.3.')) && (!strstr($attachment_version, '2.4.')))
{
	message_die(GENERAL_MESSAGE, 'Wrong Attachment Version detected.<br />Please update your Attachment Mod (V' . $attachment_version . ') to at least Version 2.2.4.');
}

if ((strstr($attachment_version, '2.3.')) || (strstr($attachment_version, '2.4.')))
{
	$real_filename = 'real_filename';
	$attach_table = ATTACHMENTS_TABLE;
	$attach_desc_table = ATTACHMENTS_DESC_TABLE;
	$sql_query = TRUE;
}
else
{
	$real_filename = 'filename';
	$attach_table = ATTACH_TABLE;
	$attach_desc_table = ATTACH_DESC_TABLE;
	$sql_query = FALSE;
}

setup_extra_lang(array('lang_admin_attach'));

$order_by = 'download_count DESC LIMIT ' . $return_limit;

// Get Valid Forum ID's to search
$sql = 'SELECT forum_id FROM ' . FORUMS_TABLE . ' WHERE forum_type = ' . FORUM_POST;
$result = $stat_db->sql_query($sql);
$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $user->data);
$is_download_auth_ary = auth(AUTH_DOWNLOAD, AUTH_LIST_ALL, $user->data);

$forum_ids = array();
while($row = $stat_db->sql_fetchrow($result))
{
	if (($is_auth_ary[$row['forum_id']]['auth_read']) && ($is_download_auth_ary[$row['forum_id']]['auth_download']))
	{
		$forum_ids[] = $row['forum_id'];
	}
}

/*if (sizeof($forum_ids) == 0)
{
	message_die(GENERAL_MESSAGE, "You are not authorized to view Attachments at all.");
}*/

if (sizeof($forum_ids) > 0)
{
	$sql = "SELECT a.post_id, t.topic_title, d.*
	FROM " . $attach_table . " a, " . $attach_desc_table . " d, "  . * . " p, " . TOPICS_TABLE . " t
	WHERE (a.post_id = p.post_id) AND p.deleted = 0 AND (p.forum_id IN (" . implode(', ', $forum_ids) . ")) AND (p.topic_id = t.topic_id) AND (a.attach_id = d.attach_id)
	ORDER BY $order_by";
	$result = $stat_db->sql_query($sql);
	$attachments = $stat_db->sql_fetchrowset($result);
	$num_attachments = $stat_db->sql_numrows($result);
}
else
{
	$attachments = array();
	$num_attachments = 0;
}

$template->_tpldata['stats_row.'] = array();

for ($i = 0; $i < $num_attachments; $i++)
{
	$class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	$post_title = $attachments[$i]['topic_title'];
	$post_title_2 = '';

	if (strlen($post_title) > 32)
	{
		$post_title_2 = substr($post_title, 0, 30) . '...';
	}

	$view_topic = append_sid(CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $attachments[$i]['post_id'] . '#p' . $attachments[$i]['post_id']);
	if ($post_title_2 != '')
	{
		$post_title = '<a href="' . $view_topic . '" class="gen" title="' . $post_title . '" target="_blank">' . $post_title_2 . '</a>';
	}
	else
	{
		$post_title = '<a href="' . $view_topic . '" class="gen" target="_blank">' . $post_title . '</a>';
	}

	$comment = $attachments[$i]['comment'];
	$comment_2 = '';

	if (strlen($comment) > 32)
	{
		$comment_2 = substr($comment, 0, 30) . '...';
	}

	if ($comment_2 != '')
	{
		$comment_field = '<span title="' . $comment . '">' . $comment_2 . '</span>';
	}
	else
	{
		$comment_field = $comment;
	}

	$filename = $attachments[$i][$real_filename];
	$filename_2 = '';

	if (strlen($filename) > 32)
	{
		$filename_2 = substr($filename, 0, 30) . '...';
	}

	$view_attachment = append_sid('download.' . PHP_EXT . '?id=' . $attachments[$i]['attach_id']);
	if ($filename_2 != '')
	{
		$filename_link = '<a href="' . $view_attachment . '" class="gen" title="' . $filename . '" target="_blank">' . $filename_2 . '</a>';
	}
	else
	{
		$filename_link = '<a href="' . $view_attachment . '" class="gen" target="_blank">' . $filename . '</a>';
	}

	$template->assign_block_vars('stats_row', array(
		'ROW_NUMBER' => $i + 1,
		'RANK' => $i + 1,
		'CLASS' => $class,

		'FILENAME' => $filename,
		'COMMENT' => $comment_field,
		'SIZE' => round(($attachments[$i]['filesize'] / 1024), 2),
		'DOWNLOAD_COUNT' => $attachments[$i]['download_count'],
		'POST_TIME' => create_date($config['default_dateformat'], $attachments[$i]['filetime'], $config['board_timezone']),
		'POST_TITLE' => $post_title,

		'VIEW_ATTACHMENT' => $filename_link
		)
	);
}

?>