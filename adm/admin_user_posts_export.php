<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['310_POSTS_EXPORT'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$admin_index_url = 'index.' . PHP_EXT . '?pane=right';
$this_page_url = 'admin_user_posts_export.' . PHP_EXT;
$this_page_tpl = 'user_posts_export_body.tpl';
$backup_path_base = IP_ROOT_PATH . BACKUP_PATH . 'posts_export/';

$download = request_var('download', '');
$delete = request_var('delete', '');
$file = request_var('file', '');
$confirm = request_var('confirm', '');
$action = request_var('cancel', '') ? '' : request_var('action', '');

if (!empty($file) && (!empty($download) || !empty($delete)))
{
	$path_parts = pathinfo($file);
	$filename = $path_parts['basename'];
	$filename_full_path = $backup_path_base . $filename;

	if (!file_exists($filename_full_path) || !is_readable($filename_full_path))
	{
		$message = $lang['UPE_FILES_LIST_INVALID'] . '<br /><br />';
		$message .= sprintf($lang['UPE_COMPLETE_REDIRECT_CLICK'], '<a href="' . append_sid($this_page_url) . '">', '</a>') . '<br /><br />';
		$message .= sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid($admin_index_url) . '">', '</a>');
		message_die(GENERAL_ERROR, $message, $lang['Error']);
	}

	if (!empty($download))
	{
		@set_time_limit(0);
		$file_name = basename($filename_full_path);
		header("Content-Type: application/zip");
		header("Content-Disposition: attachment; filename=$file_name");
		header("Content-Length: " . filesize($filename_full_path));
		readfile($filename_full_path);
		exit;
	}

	if (!empty($delete))
	{
		if (empty($confirm))
		{
			$hidden_fields_array = array(
				'file' => $file,
				'delete' => $delete,
				'action' => $action
			);
			$s_hidden_fields = build_hidden_fields($hidden_fields_array, false, false);
			$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));
			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Delete'],
				'MESSAGE_TEXT' => $lang['UPE_FILES_LIST_DELETE_SELECTED'],
				'S_CONFIRM_ACTION' => append_sid($this_page_url),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
			$template->pparse('body');
			include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
			exit;
		}
		else
		{
			$file_name = basename($filename_full_path);
			@unlink($filename_full_path);
			$message = $lang['UPE_FILES_LIST_DELETED'] . ': <strong>' . htmlspecialchars($file_name) . '</strong><br /><br />';
			$message .= sprintf($lang['UPE_COMPLETE_REDIRECT_CLICK'], '<a href="' . append_sid($this_page_url) . '">', '</a>') . '<br /><br />';
			$message .= sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid($admin_index_url) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message, $lang['Information']);
		}
	}
}

$export_complete = false;

$user_ids = request_var('user_ids', '');
// Make sure we have numbers...
$user_ids_array = array_map('intval', explode(',', $user_ids));
$user_ids = implode(',', $user_ids_array);

$mode_array = array('export');
$mode = request_var('mode', '');
$mode = in_array($mode, $mode_array) ? $mode : '';

$user_id = request_var(POST_USERS_URL, 0);

$cu = request_var('cu', 0);

$start = request_var('start', 0);
$start = ($start < 0) ? 0 : $start;

$limit = request_var('limit', 10);
$limit = (($limit < 0) || ($limit > 250)) ? 250 : $limit;

$posts_type = request_var('pt', 0);
$posts_type = !empty($posts_type) ? 1 : 0;

$s_hidden_fields = '';

if(($mode == 'export') && !empty($user_ids_array))
{
	$cache_time = 60 * 10;
	$bin_forum_id = intval($config['bin_forum']);
	$bin_forum_exclude = !empty($bin_forum_id) ? (" AND t.forum_id <> " . $bin_forum_id . " ") : '';

	$sql = "SELECT COUNT(t.topic_poster) AS topics_started, t.topic_poster, t.topic_first_poster_name
					FROM " . TOPICS_TABLE . " t
					WHERE t.topic_poster IN(" . $db->sql_escape($user_ids) . ")
						" . $bin_forum_exclude . "
					GROUP BY t.topic_poster
					ORDER BY t.topic_poster";
	$result = $db->sql_query($sql, $cache_time, 'posts_export_data_', TOPICS_CACHE_FOLDER);
	$user_ids_posts = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	$user_ids_array_full = array();
	$user_ids_array = array();
	foreach ($user_ids_posts as $v)
	{
		if (!empty($v['topics_started']))
		{
			$user_ids_array[] = $v['topic_poster'];
			$user_ids_array_full[$v['topic_poster']] = array('p' => $v['topics_started'], 'un' => $v['topic_first_poster_name']);
		}
	}
	$user_ids = implode(',', $user_ids_array);

	if (empty($user_id))
	{
		$cu = 0;
		$user_id = $user_ids_array[$cu];
	}
	$post_username_html = htmlspecialchars($user_ids_array_full[$user_id]['un']);
	$post_username_clean = ip_clean_string($user_ids_array_full[$user_id]['un'], $lang['ENCODING'], false, true);

	$sql = "SELECT t.topic_id, t.forum_id, t.topic_title, t.topic_poster, t.topic_first_post_id, t.topic_first_post_time, t.topic_first_poster_name, t.topic_first_poster_color, p.post_id, p.post_text, f.forum_name
					FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . FORUMS_TABLE . " AS f
					WHERE t.topic_poster = " . $db->sql_escape($user_id) . "
						" . $bin_forum_exclude . "
						AND f.forum_id = t.forum_id
						AND p.post_id = t.topic_first_post_id
					ORDER BY t.topic_id
					LIMIT " . $start . ", " . $limit;
	$result = $db->sql_query($sql);
	$user_topics = $db->sql_fetchrowset($result);
	$user_topics_number = sizeof($user_topics);
	$db->sql_freeresult($result);

	$backup_path = $backup_path_base . $user_id . '_' . $post_username_clean;
	$backup_images_path = $backup_path . '/images';
	ip_mkdir($backup_path);
	ip_mkdir($backup_images_path);
	$posts_exported = '';
	$posts_path_old = '';
	$post_page_html = "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n\t<meta charset=\"utf-8\">\n\t<title>{TITLE}</title>\n</head>\n<body>\n{CONTENT}\n</body>\n</html>";
	$post_eof_html = "\n" . '<div id="eof">&nbsp;</div>' . "\n";

	if (!class_exists('bbcode') || empty($bbcode)) @include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	$bbcode->allow_html = false;
	$bbcode->allow_bbcode = true;
	$bbcode->allow_smilies = true;
	$bbcode->plain_html = true;

	foreach ($user_topics as $k => $v)
	{
		// First get some basics vars
		$post_forum_html = htmlspecialchars($v['forum_name']);
		$post_forum_clean = str_replace(array('-', '__'), array('_', '_'), ip_clean_string($v['forum_name'], $lang['ENCODING'], false, true));
		$post_title_html = htmlspecialchars($v['topic_title']);
		$post_title_clean = str_replace(array('-', '__'), array('_', '_'), ip_clean_string($v['topic_title'], $lang['ENCODING'], false, true));
		$post_time = create_date_ip($lang['DATE_FORMAT_VF'], $v['topic_first_post_time'], $config['board_timezone'], true);
		$post_filename = $v['post_id'] . '_' . $post_title_clean;

		$posts_path = $backup_path . '/' . $v['forum_id'] . '_' . $post_forum_clean;
		$posts_list_file = $posts_path . '/__' . $post_forum_clean . '.html';
		if (empty($posts_path_old) || ($posts_path != $posts_path_old))
		{
			ip_mkdir($posts_path);
			if (!@file_exists($posts_list_file))
			{
				$posts_list_file_content = str_replace(array('{TITLE}', '{CONTENT}'), array($post_forum_html, '<h1>' . $post_forum_html . '</h1>' . $post_eof_html), $post_page_html);
				@file_put_contents($posts_list_file, $posts_list_file_content);
			}
		}
		$posts_path_old = $posts_path;

		$post_text = $v['post_text'];
		// Process BBCodes
		$post_text_html = $bbcode->parse($v['post_text']);
		$posts_text_html_file_content = str_replace(array('{TITLE}', '{CONTENT}'), array($post_title_html, '<h1>' . $post_title_html . '</h1>' . "\n" . '<h2>' . $post_username_html . ' @ ' . $post_time . '</h2>' . "\n" . '<hr />' . "\n" . '<div>' . $post_text_html . '</div>'), $post_page_html);

		// Get all images
		$dom_inspector = new DOMDocument();
		@$dom_inspector->loadHTML($posts_text_html_file_content);
		$tags = $dom_inspector->getElementsByTagName('img');
		foreach ($tags as $tag)
		{
			$img_path = $tag->getAttribute('src');
			if (!empty($img_path))
			{
				$path_parts = pathinfo($img_path);
				$img_path_backup = $backup_images_path . '/' . $path_parts['basename'];
				$img_new_src = '../images/' . $path_parts['basename'];
				$img_replace = false;
				if (!@file_exists($img_path_backup))
				{
					$copy_result = @copy($img_path, $img_path_backup);
					if (!empty($copy_result))
					{
						$img_replace = true;
					}
				}
				else
				{
					$img_replace = true;
				}

				if (!empty($img_replace))
				{
					$posts_text_html_file_content = str_replace($img_path, $img_new_src, $posts_text_html_file_content);
				}
			}
		}

		@file_put_contents($posts_path . '/' . $post_filename . '.txt', $post_text);
		@file_put_contents($posts_path . '/' . $post_filename . '.html', $posts_text_html_file_content);

		// Update posts list
		$posts_list_update = '<a href="' . $post_filename . '.html' . '">' . $post_title_html . '</a><br />';
		$posts_list_file_content = @file_get_contents($posts_list_file);
		$posts_list_file_content = str_replace(array($post_eof_html), array($posts_list_update . $post_eof_html), $posts_list_file_content);
		@file_put_contents($posts_list_file, $posts_list_file_content);

		//$posts_exported .= $post_title_html . '<br />';
	}

	$message_posts_counter = $start;
	$message_total_posts = $user_ids_array_full[$user_id]['p'];
	$start = $start + $user_topics_number;
	if ($start >= $user_ids_array_full[$user_id]['p'])
	{
		if ($cu >= (sizeof($user_ids_array) - 1))
		{
			$export_complete = true;
		}
		else
		{
			$cu++;
			$start = 0;
			$user_id = $user_ids_array[$cu];
		}

		// ZIP current folder...
		if (!class_exists('class_files')) @include(IP_ROOT_PATH . 'includes/class_files.' . PHP_EXT);
		if (!class_exists('PclZip')) @include(IP_ROOT_PATH . 'includes/pclzip.lib.' . PHP_EXT);
		$class_files = new class_files();
		$files_list = $class_files->list_files($backup_path, true, false, false);
		$date_suffix = '_' . gmdate('Ymd');
		$zip_filename = $backup_path . $date_suffix . '.zip';
		$zip = new PclZip($zip_filename);
		$zip->add($files_list, '', $backup_path_base);
		$class_files->clear_dir($backup_path);
		@rmdir($backup_path);
	}

	$meta_tag = '';
	if (empty($export_complete))
	{
		$post_data = array(
			'mode' => $mode,
			POST_USERS_URL => $user_id,
			'cu' => $cu,
			'user_ids' => implode(',', $user_ids_array),
			'start' => $start,
			'limit' => $limit,
			'pt' => $posts_type,
			//'sid' => $user->data['session_id'],
		);

		$post_data_append = http_build_query($post_data, '', '&amp;');

		$redirect_url = append_sid($this_page_url . '?' . $post_data_append);
		//meta_refresh(3, $redirect_url);
		$meta_tag = '</body><head><meta http-equiv="refresh" content="3;url=' . $redirect_url . '"></head><body>';
		$message = '<div id="img-loader" class="talignc tdalignc"><br /><img src="' . IP_ROOT_PATH . 'templates/common/jquery/loader.gif" alt="" /><br /><br /><br /></div>';
		$message .= $lang['UPE_PROCESSING'] . '<br /><br />';
		//$message .= $posts_exported . '<br /><br />';
		$message .= $lang['UPE_PROCESSING_CURRENT_LOOP'] . '&nbsp;' . $post_username_html . '&nbsp;-&gt;&nbsp;(' . $message_posts_counter . ',&nbsp;' . $message_total_posts . ')' . '<br /><br />';
		$message .= $lang['UPE_IN_PROGRESS_REDIRECT'] . '<br /><br />';
		$message .= sprintf($lang['UPE_IN_PROGRESS_REDIRECT_CLICK'], '<a href="' . $redirect_url . '">', '</a>');
	}
	else
	{
		$message = $lang['UPE_EXPORT_COMPLETE'] . '<br /><br />';
		$message .= sprintf($lang['UPE_COMPLETE_REDIRECT_CLICK'], '<a href="' . append_sid($this_page_url) . '">', '</a>') . '<br /><br />';
		$message .= sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid($admin_index_url) . '">', '</a>');
	}
	message_die(GENERAL_MESSAGE, $meta_tag . $message);
}

if (!class_exists('class_files')) @include(IP_ROOT_PATH . 'includes/class_files.' . PHP_EXT);
$class_files = new class_files();
$files_list = $class_files->list_files($backup_path_base, false, array('zip'), false);
foreach ($files_list as $file_path)
{
	$path_parts = pathinfo($file_path);
	$filename = $path_parts['basename'];
	$template->assign_block_vars('files', array(
		'NAME' => $filename,
		'FILE' => $file_path
		)
	);
}

$limit_values = array(10, 20, 50, 100, 250);
$limit_values_text = array('10', '20', '50', '100', '250');
$limit_select = $class_form->build_select_box('limit', $limit, $limit_values, $limit_values_text, '');

$posts_type_values = array(0, 1);
$posts_type_text = array($lang['UPE_POSTS_TYPE_TOPICS'], $lang['UPE_POSTS_TYPE_ALL']);
$posts_type_select = $class_form->build_select_box('pt', $posts_type, $posts_type_values, $posts_type_text, '');

$hidden_fields_array = array('mode' => 'export');
$s_hidden_fields = build_hidden_fields($hidden_fields_array, false, false);

$template->set_filenames(array('body' => ADM_TPL . $this_page_tpl));
$template->assign_vars(array(
	'S_USER_IDS' => $user_ids,
	'S_SELECT_LIMIT' => (!empty($limit_select) ? $limit_select : ''),
	'S_SELECT_POST_TYPE' => (!empty($posts_type_select) ? $posts_type_select : ''),
	'S_USER_ACTION' => append_sid($this_page_url . '?mode=export'),
	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>