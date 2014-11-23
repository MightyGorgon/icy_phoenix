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
* Xavier Olive (xavier@2037.biz)
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1200_Forums']['160_Title_infos'] = $file;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
if (empty($bbcode)) $bbcode = new bbcode();
$bbcode->allow_html = true;
$bbcode->allow_bbcode = true;
$bbcode->allow_smilies = true;

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$mode = request_var('mode', '');

if(empty($mode))
{
	// These could be entered via a form button
	if(isset($_POST['add']))
	{
		$mode = 'add';
	}
	elseif(isset($_POST['save']))
	{
		$mode = 'save';
	}
}


if(!empty($mode))
{
	if(($mode == 'edit') || ($mode == 'add'))
	{
		// They want to add a new title info, show the form.
		$title_id = request_var('id', 0);

		$s_hidden_fields = '';

		if($mode == 'edit')
		{
			if(empty($title_id))
			{
				message_die(GENERAL_MESSAGE, $lang['Must_select_title']);
			}

			$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . "
				WHERE id = '" . $title_id . "'";
			$result = $db->sql_query($sql);
			$title_info = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $title_id . '" />';
		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		$template->set_filenames(array('body' => ADM_TPL . 'title_edit_body.tpl'));

		$template->assign_vars(array(
			'TITLE_INFO' => str_replace("\"", "'", $title_info['title_info']),
			'TITLE_HTML' => htmlspecialchars(str_replace("\"", "'", $title_info['title_html'])),
			'ADMIN_CHECKED' => ($title_info['admin_auth'] == 1) ? ' checked="checked"' : '',
			'MOD_CHECKED' => ($title_info['mod_auth'] == 1) ? ' checked="checked"' : '',
			'POSTER_CHECKED' => ($title_info['poster_auth'] == 1) ? ' checked="checked"' : '',
			'ADMIN_TITLE' => $lang['Title_infos'],
			'ADMIN_TITLE_EXPLAIN' => $lang['Quick_title_explain'],
			'S_TITLE_ACTION' => append_sid('admin_quick_title.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'ADMIN' => $lang['Administrator'],
			'MODERATOR' => $lang['Moderator'],
			'POSTER' => $lang['Topic_poster'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
			'L_TITLE_TITLE' => $lang['Add_new_title_info'],
			'L_PERM_INFO' => $lang['Title_perm_info'],
			'L_TITLE_INFO' => $lang['Title_info'],
			'L_TITLE_HTML' => $lang['Title_html'],
			'L_TITLE_HTML_EXPLAIN' => $lang['Title_html_explain'],
			'L_PERM_EXPLAIN' => $lang['Title_perm_info_explain'],
			'L_DATE_FORMAT' => $lang['Date_format'],
			'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
			'DATE_FORMAT' => $title_info['date_format']
			)
		);

	}
	elseif($mode == 'save')
	{
		// Ok, they sent us our info, let's update it.
		$title_id = request_post_var('id', 0);
		$admin = (!empty($_POST['admin_auth'])) ? 1 : 0;
		$mod = (!empty($_POST['mod_auth'])) ? 1 : 0;
		$poster = (!empty($_POST['poster_auth'])) ? 1 : 0;
		$name = request_post_var('title_info', '', true);
		$html = request_post_var('title_html', '', true);
		$html = htmlspecialchars_decode($html, ENT_COMPAT);
		$date = request_post_var('date_format', '');

		if(empty($name))
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_title']);
		}

		$input_table = TITLE_INFOS_TABLE;

		$input_array = array(
			'title_info' => trim($name),
			'title_html' => trim($html),
			'date_format' => $date,
			'admin_auth' => $admin,
			'mod_auth' => $mod,
			'poster_auth' => $poster,
		);

		$where_sql = ' WHERE id = ' . $title_id;

		if (!empty($title_id))
		{
			$sql = "UPDATE " . $input_table . " SET " . $db->sql_build_insert_update($input_array, false) . $where_sql;
			$message = $lang['Title_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . $input_table . " " . $db->sql_build_insert_update($input_array, true);
			$message = $lang['Title_added'];
		}
		$result = $db->sql_query($sql);
		$db->clear_cache('', TOPICS_CACHE_FOLDER);

		$message .= '<br /><br />' . sprintf($lang['Click_return_titleadmin'], '<a href="' . append_sid('admin_quick_title.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($mode == 'delete')
	{
		// Ok, they want to delete the title
		$title_id = request_var('id', 0);

		if (!empty($title_id))
		{
			$sql = "DELETE FROM " . TITLE_INFOS_TABLE . "
							WHERE id = '" . $title_id . "'";
			$result = $db->sql_query($sql);
			$db->clear_cache('', TOPICS_CACHE_FOLDER);

			$message = $lang['Title_removed'] . '<br /><br />' . sprintf($lang['Click_return_titleadmin'], '<a href="' . append_sid('admin_quick_title.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_title']);
		}
	}
	else
	{
		// They didn't feel like giving us any information. Oh, too bad, we'll just display the list then...
		$template->set_filenames(array('body' => ADM_TPL . 'title_list_body.tpl'));
		$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . "
						ORDER BY id ASC";
		$result = $db->sql_query($sql);
		$title_rows = $db->sql_fetchrowset($result);
		$title_count = sizeof($title_rows);

		$template->assign_vars(array(
			'S_TITLE_ACTION' => append_sid('admin_quick_title.' . PHP_EXT),
			'ADMIN_TITLE' => $lang['Title_infos'],
			'ADMIN_TITLE_EXPLAIN' => $lang['Quick_title_explain'],
			'HEAD_TITLE' => $lang['Title_head'],
			'HEAD_HTML' => $lang['Title_html'],
			'HEAD_AUTH' => $lang['Title_auth'],
			'ADD_NEW' => $lang['Add_new'],
			'HEAD_DATE' => $lang['Date_format'],
			'L_EDIT' => $lang['Edit'],
			'L_DELETE' => $lang['Delete']
			)
		);

		for($i = 0; $i < $title_count; $i++)
		{
			$title_id=$title_rows[$i]['id'];
			$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			$perm = ($title_rows[$i]['admin_auth'] == 1) ? $lang['Administrator'] . '<br />' : '';
			$perm .= ($title_rows[$i]['mod_auth'] == 1) ? $lang['Moderator'] . '<br />' : '';
			$perm .= ($title_rows[$i]['poster_auth'] == 1) ? $lang['Topic_poster'] : '';
			$template->assign_block_vars('title', array(
				'ROW_CLASS' => $row_class,
				'TITLE' => $title_rows[$i]['title_info'],
				'HTML' => $bbcode->parse($title_rows[$i]['title_html']),
				'PERMISSIONS' => $perm,
				'DATE_FORMAT' => $title_rows[$i]['date_format'],
				'U_TITLE_EDIT' => append_sid('admin_quick_title.' . PHP_EXT . '?mode=edit&amp;id=' . $title_id),
				'U_TITLE_DELETE' => append_sid('admin_quick_title.' . PHP_EXT . '?mode=delete&amp;id=' . $title_id)
				)
			);
		}
	}
}
else
{
	// Show the default page
	$template->set_filenames(array('body' => ADM_TPL . 'title_list_body.tpl'));

	$sql = "SELECT * FROM " . TITLE_INFOS_TABLE . "
					ORDER BY id ASC LIMIT $start, 40";
	$result = $db->sql_query($sql);
	$title_rows = $db->sql_fetchrowset($result);
	$title_count = sizeof($title_rows);

	$sql = "SELECT count(*) AS total
					FROM " . TITLE_INFOS_TABLE;
	$result = $db->sql_query($sql);

	if ($total = $db->sql_fetchrow($result))
	{
		$total_records = $total['total'];
		$pagination = generate_pagination('admin_quick_title.' . PHP_EXT . '?mode=' . $mode, $total_records, 40, $start). ' ';
	}

	$template->assign_vars(array(
		'ADMIN_TITLE' => $lang['Title_infos'],
		'ADMIN_TITLE_EXPLAIN' => $lang['Quick_title_explain'],
		'HEAD_TITLE' => $lang['Title_head'],
		'HEAD_HTML' => $lang['Title_html'],
		'HEAD_AUTH' => $lang['Title_auth'],
		'HEAD_DATE' => $lang['Date_format'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'PAGINATION' => $pagination,
		'ADD_NEW' => $lang['Add_new'],
		'S_TITLE_ACTION' => append_sid('admin_quick_title.' . PHP_EXT)
		)
	);

	for($i = 0; $i < $title_count; $i++)
	{
		$title_id=$title_rows[$i]['id'];
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$perm = ($title_rows[$i]['admin_auth']==1) ? $lang['Administrator'].'<br />' : '';
		$perm .= ($title_rows[$i]['mod_auth']==1) ? $lang['Moderator'].'<br />' : '';
		$perm .= ($title_rows[$i]['poster_auth']==1) ? $lang['Topic_poster'] : '';

		$template->assign_block_vars('title', array(
			'ROW_CLASS' => $row_class,
			'TITLE' => $title_rows[$i]['title_info'],
			'HTML' => $bbcode->parse($title_rows[$i]['title_html']),
			'PERMISSIONS' => $perm,
			'DATE_FORMAT' => $title_rows[$i]['date_format'],

			'U_TITLE_EDIT' => append_sid('admin_quick_title.' . PHP_EXT . '?mode=edit&amp;id=' . $title_id),
			'U_TITLE_DELETE' => append_sid('admin_quick_title.' . PHP_EXT . '?mode=delete&amp;id=' . $title_id)
			)
		);
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>