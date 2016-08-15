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
* mosymuis (mods@mosymuis.nl)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty ($setmodules))
{
	$filename = basename(__FILE__);
	$module['1200_Forums']['240_Replace_title'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$str_old = request_post_var('str_old', '', true);
$str_new = request_post_var('str_new', '', true);

if ($_POST['submit'] && !empty($str_old) && ($str_old != $str_new))
{
	$template->assign_block_vars("switch_forum_sent", array());

	$sql = "SELECT f.forum_id, f.forum_name, t.topic_id, t.topic_title, p.post_id, p.post_time, p.post_text, u.user_id, u.username
		FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . USERS_TABLE . " u
		WHERE post_text LIKE '%" . $db->sql_escape($str_old) . "%'
		AND p.topic_id = t.topic_id
		AND p.forum_id = f.forum_id
		AND p.poster_id = u.user_id
		ORDER BY p.post_id DESC;";
	$result = $db->sql_query($sql);

	if ($db->sql_numrows($result) >= 1)
	{
		for ($i = 1; $row = $db->sql_fetchrow($result); $i++)
		{
			$template->assign_block_vars('switch_forum_sent.replaced', array(
				'ROW_CLASS' => (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'],
				'NUMBER' => $i,
				'FORUM_NAME' => $row['forum_name'],
				'TOPIC_TITLE' => $row['topic_title'],
				'AUTHOR' => $row['username'],
				'POST' => create_date($config['default_dateformat'], $row['post_time'], $config['board_timezone']),

				'U_FORUM' => append_sid('../' . CMS_PAGE_VIEWFORUM . '?' . POST_FORUM_URL . '=' . $row['forum_id']),
				'U_TOPIC' => append_sid('../' . CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $row['topic_id']),
				'U_AUTHOR' => append_sid('../' . CMS_PAGE_PROFILE . '?mode=viewprofile&' . POST_USERS_URL . '=' . $row['user_id']),
				'U_POST' => append_sid('../' . CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $row['post_id']) . '#p' . $row['post_id'])
			);

			$sql = "UPDATE " . POSTS_TABLE . "
				SET post_text = '" . $db->sql_escape(str_replace($str_old, $str_new, $row['post_text'])) . "'
				WHERE post_id = " . $row['post_id'];
			$result = $db->sql_query($sql);
		}

	}
	else
	{
		$template->assign_block_vars('switch_forum_sent.switch_no_results', array());
	}
}

$template->set_filenames(array('body' => ADM_TPL . 'replace_body.tpl'));

$template->assign_vars(array(
	'S_FORM_ACTION' => append_sid('admin_replace.' . PHP_EXT),

	'L_REPLACE_TITLE' => $lang['Replace_title'],
	'L_REPLACE_TEXT' => $lang['Replace_text'],
	'L_STR_OLD' => $lang['Str_old'],
	'L_STR_NEW' => $lang['Str_new'],
	'L_FORUM' => $lang['Forum'],
	'L_TOPIC' => $lang['Topic'],
	'L_AUTHOR' => $lang['Author'],
	'L_LINK' => $lang['Link'],
	'L_NO_RESULTS' => $lang['No_results'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'REPLACED_COUNT' => ($i == 0) ? '&nbsp;' : sprintf($lang['Replaced_count'], $i - 1),

	'STR_OLD' => $str_old,
	'STR_NEW' => $str_new,
	'POST_IMG' => $images['acp_icon_latest_reply']
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>