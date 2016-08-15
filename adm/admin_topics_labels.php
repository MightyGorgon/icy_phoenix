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
	$module['1200_Forums']['160_Topics_Labels'] = $file;
	return;
}

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

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

$display_list = false;

if(!empty($mode))
{
	if(($mode == 'edit') || ($mode == 'add'))
	{
		include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);

		// They want to add a new title info, show the form.
		$label_id = request_var('id', 0);

		$s_hidden_fields = '';

		$topic_label = array(
			'label_name' => '',
			'label_code' => '',
			'label_code_switch' => 0,
			'label_bg_color' => '',
			'label_text_color' => '',
			'label_icon' => '',
			'admin_auth' => '0',
			'mod_auth' => '0',
			'poster_auth' => '0',
			'date_format' => ''
		);

		if($mode == 'edit')
		{
			if(empty($label_id))
			{
				message_die(GENERAL_MESSAGE, $lang['MUST_SELECT_LABEL']);
			}

			$sql = "SELECT * FROM " . TOPICS_LABELS_TABLE . "
				WHERE id = '" . $label_id . "'";
			$result = $db->sql_query($sql);
			$topic_label = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $label_id . '" />';
		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		$template->set_filenames(array('body' => ADM_TPL . 'topics_labels_edit_body.tpl'));

		$template->assign_vars(array(
			'S_TITLE_ACTION' => append_sid('admin_topics_labels.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields,

			'U_BBCODE_COLORPICKER_BG' => append_sid('bbcb_mg_cp.' . PHP_EXT . '?cpf=topic_label&amp;cpo=label_bg_color&amp;cpt=color&amp;cpie=1&amp;cpip=1'),
			'U_BBCODE_COLORPICKER_TEXT' => append_sid('bbcb_mg_cp.' . PHP_EXT . '?cpf=topic_label&amp;cpo=label_text_color&amp;cpt=color&amp;cpie=1&amp;cpip=1'),

			'LABEL_NAME' => str_replace("\"", "'", $topic_label['label_name']),
			'LABEL_CODE' => htmlspecialchars(str_replace("\"", "'", $topic_label['label_code'])),
			'LABEL_CODE_SW_PT' => ($topic_label['label_code_switch'] == 0) ? ' checked="checked"' : '',
			'LABEL_CODE_SW_BBC' => ($topic_label['label_code_switch'] == 1) ? ' checked="checked"' : '',
			'LABEL_CODE_SW_HTML' => ($topic_label['label_code_switch'] == 2) ? ' checked="checked"' : '',
			'LABEL_CODE_SW_BBC_HTML' => ($topic_label['label_code_switch'] == 3) ? ' checked="checked"' : '',
			'LABEL_BG_COLOR' => $topic_label['label_bg_color'],
			'LABEL_TEXT_COLOR' => $topic_label['label_text_color'],
			'LABEL_ICON' => $topic_label['label_icon'],
			'ADMIN_CHECKED' => ($topic_label['admin_auth'] == 1) ? ' checked="checked"' : '',
			'MOD_CHECKED' => ($topic_label['mod_auth'] == 1) ? ' checked="checked"' : '',
			'POSTER_CHECKED' => ($topic_label['poster_auth'] == 1) ? ' checked="checked"' : '',
			//'DATE_FORMAT' => $topic_label['date_format'],
			'DATE_FORMAT' => date_select('date_format', $topic_label['date_format']),

			'ADMIN_TITLE' => $lang['TOPICS_LABELS'],
			'ADMIN_TITLE_EXPLAIN' => $lang['TOPICS_LABELS_EXPLAIN'],

			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
			'L_DATE_FORMAT' => $lang['Date_format'],
			'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
			)
		);

	}
	elseif($mode == 'save')
	{
		// Ok, they sent us our info, let's update it.
		$label_id = request_post_var('id', 0);
		$label_name = request_post_var('label_name', '', true);
		$label_code = request_post_var('label_code', '', true);
		$label_code = htmlspecialchars_decode($label_code, ENT_COMPAT);
		$label_code_switch = request_post_var('label_code_switch', 0);
		$label_bg_color = request_post_var('label_bg_color', '');
		$label_text_color = request_post_var('label_text_color', '');
		$label_icon = request_post_var('label_icon', '');
		$admin = (!empty($_POST['admin_auth'])) ? 1 : 0;
		$mod = (!empty($_POST['mod_auth'])) ? 1 : 0;
		$poster = (!empty($_POST['poster_auth'])) ? 1 : 0;
		$date = request_post_var('date_format', '');

		if(empty($label_name) || empty($label_code))
		{
			message_die(GENERAL_MESSAGE, $lang['MUST_SELECT_LABEL']);
		}

		$input_table = TOPICS_LABELS_TABLE;

		$input_array = array(
			'label_name' => trim($label_name),
			'label_code' => trim($label_code),
			'label_code_switch' => $label_code_switch,
			'label_bg_color' => trim($label_bg_color),
			'label_text_color' => trim($label_text_color),
			'label_icon' => trim($label_icon),
			'admin_auth' => $admin,
			'mod_auth' => $mod,
			'poster_auth' => $poster,
			'date_format' => $date,
		);

		$where_sql = ' WHERE id = ' . $label_id;

		if (!empty($label_id))
		{
			if (!class_exists('bbcode')) include(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
			if (empty($bbcode)) $bbcode = new bbcode();

			if (!class_exists('class_topics')) include(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
			if (empty($class_topics)) $class_topics = new class_topics();

			// First try to get previous data, to be able to update all standard labels...
			$sql_old = "SELECT * FROM " . TOPICS_LABELS_TABLE . "
				WHERE id = '" . $label_id . "'";
			$result_old = $db->sql_query($sql_old);
			$topic_label_old = $db->sql_fetchrow($result_old);

			$label_compiled_old = $class_topics->gen_label_compiled($topic_label_old);
			$label_compiled_new = $class_topics->gen_label_compiled($input_array);

			$sql = "UPDATE " . $input_table . " SET " . $db->sql_build_insert_update($input_array, false) . $where_sql;
			$message = $lang['LABEL_UPDATED'];
		}
		else
		{
			$sql = "INSERT INTO " . $input_table . " " . $db->sql_build_insert_update($input_array, true);
			$message = $lang['LABEL_ADDED'];
		}
		$result = $db->sql_query($sql);
		$db->clear_cache('topics_labels_', TOPICS_CACHE_FOLDER);

		// Now try to update all labels compiled which don't have %mod% or %date% (maybe in the future we can try to add 2 extra fields to topics table to be able to modify also those labels... but for now we avoid doing it to keep SQL charge at a minimum
		if (!empty($label_id))
		{
			$sql = "UPDATE " . TOPICS_TABLE . "
				SET topic_label_compiled = '" . $db->sql_escape(trim($label_compiled_new)) . "'
				WHERE topic_label_id = " . $db->sql_escape($label_id) . "
					AND topic_label_compiled = '" . $db->sql_escape(trim($label_compiled_old)) . "'";
			$result = $db->sql_query($sql);
			$db->clear_cache('', TOPICS_CACHE_FOLDER);
		}

		$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_TOPICS_LABELS'], '<a href="' . append_sid('admin_topics_labels.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($mode == 'delete')
	{
		// Ok, they want to delete the title
		$label_id = request_var('id', 0);

		if (!empty($label_id))
		{
			$sql = "DELETE FROM " . TOPICS_LABELS_TABLE . "
							WHERE id = '" . $label_id . "'";
			$result = $db->sql_query($sql);
			$db->clear_cache('', TOPICS_CACHE_FOLDER);

			$message = $lang['LABEL_REMOVED'] . '<br /><br />' . sprintf($lang['CLICK_RETURN_TOPICS_LABELS'], '<a href="' . append_sid('admin_topics_labels.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['MUST_SELECT_LABEL']);
		}
	}
	else
	{
		// They didn't feel like giving us any information. Oh, too bad, we'll just display the list then...
		$display_list = true;
	}
}
else
{
	// Show the default page
	$display_list = true;
}

if (!empty($display_list))
{
	$per_page = $config['topics_per_page'];
	$template->set_filenames(array('body' => ADM_TPL . 'topics_labels_list_body.tpl'));

	$sql = "SELECT * FROM " . TOPICS_LABELS_TABLE . "
					ORDER BY id ASC LIMIT $start, $per_page";
	$result = $db->sql_query($sql);
	$topics_labels_rows = $db->sql_fetchrowset($result);
	$topics_labels_count = sizeof($topics_labels_rows);

	$sql = "SELECT count(*) AS total
					FROM " . TOPICS_LABELS_TABLE;
	$result = $db->sql_query($sql);

	if ($total = $db->sql_fetchrow($result))
	{
		$total_records = $total['total'];
		$pagination = generate_pagination('admin_topics_labels.' . PHP_EXT . '?mode=' . $mode, $total_records, $per_page, $start). ' ';
	}

	$template->assign_vars(array(
		'S_TITLE_ACTION' => append_sid('admin_topics_labels.' . PHP_EXT),

		'ADMIN_TITLE' => $lang['TOPICS_LABELS'],
		'ADMIN_TITLE_EXPLAIN' => $lang['TOPICS_LABELS_EXPLAIN'],
		'L_DATE_FORMAT' => $lang['Date_format'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],
		'ADD_NEW' => $lang['Add_new'],

		'PAGINATION' => $pagination,
		)
	);

	if (!class_exists('class_topics')) include(IP_ROOT_PATH . 'includes/class_topics.' . PHP_EXT);
	if (empty($class_topics)) $class_topics = new class_topics();

	for($i = 0; $i < $topics_labels_count; $i++)
	{
		$label_id = $topics_labels_rows[$i]['id'];
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		$perm = ($topics_labels_rows[$i]['admin_auth'] == 1) ? $lang['LABEL_AUTH_ADMIN'] . '<br />' : '';
		$perm .= ($topics_labels_rows[$i]['mod_auth'] == 1) ? $lang['LABEL_AUTH_MOD'] . '<br />' : '';
		$perm .= ($topics_labels_rows[$i]['poster_auth'] == 1) ? $lang['LABEL_AUTH_TOPIC_POSTER'] : '';

		$label_compiled = $class_topics->gen_label_compiled($topics_labels_rows[$i]);

		$template->assign_block_vars('topic_label', array(
			'ROW_CLASS' => $row_class,
			'TITLE' => $topics_labels_rows[$i]['label_name'],
			//'HTML' => $bbcode->parse($topics_labels_rows[$i]['label_code']),
			'HTML' => $label_compiled,
			'PERMISSIONS' => $perm,
			'DATE_FORMAT' => $topics_labels_rows[$i]['date_format'],

			'U_TITLE_EDIT' => append_sid('admin_topics_labels.' . PHP_EXT . '?mode=edit&amp;id=' . $label_id),
			'U_TITLE_DELETE' => append_sid('admin_topics_labels.' . PHP_EXT . '?mode=delete&amp;id=' . $label_id)
			)
		);
	}
}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>