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
	$file = basename(__FILE__);
	$module['1000_Configuration']['190_Spider_Bots'] = $file;
	return;
}

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bots.' . PHP_EXT);

$mode_array = array('add', 'delete', 'save', 'update');
$mode = request_var('mode', '');
$mode = (in_array($mode, $mode_array) ? $mode : '');

$update = request_var('update', false);
if ($update)
{
	$mode = 'update';
}

$bot_id = request_var('bot_id', 0);
$bot_active = request_var('bot_active', 0);
$bot_name = request_var('bot_name', '');
$bot_color = request_var('bot_color', '');
$bot_agent = request_var('bot_agent', '');
$bot_ip = request_var('bot_ip', '');
$bot_last_visit = request_var('bot_last_visit', 0);
$bot_visit_counter = request_var('bot_visit_counter', 0);

$bot_sort_by = request_var('sort_by', '');
$bot_sort_by_array = array('bot_id', 'bot_name', 'bot_agent', 'bot_ip', 'bot_active', 'bot_last_visit', 'bot_visit_counter');
$bot_sort_by = in_array($bot_sort_by, $bot_sort_by_array) ? $bot_sort_by : $bot_sort_by_array[0];
$bot_sort_order = request_var('sort_order', '');

if($mode == 'save')
{
	if($bot_name == '')
	{
		message_die(GENERAL_MESSAGE, $lang['ERR_BOT_ADD']);
	}
	elseif (($bot_agent == '') && ($bot_ip == ''))
	{
		message_die(GENERAL_MESSAGE, $lang['ERR_BOT_ADD']);
	}

	$input_table = BOTS_TABLE;
	// htmlspecialchars_decode is supported only since PHP 5+ (an alias has been added into functions.php, if you want to use a PHP 4 default function you can use html_entity_decode instead)
	$input_array = array(
		'bot_active' => $bot_active,
		'bot_name' => '\'' . ((STRIP) ? addslashes($bot_name) : $bot_name) . '\'',
		'bot_color' => '\'' . ((STRIP) ? htmlspecialchars_decode(addslashes($bot_color)) : htmlspecialchars_decode($bot_color)) . '\'',
		'bot_agent' => '\'' . ((STRIP) ? addslashes($bot_agent) : $bot_agent) . '\'',
		'bot_ip' => '\'' . ((STRIP) ? addslashes($bot_ip) : $bot_ip) . '\'',
		'bot_visit_counter' => ((STRIP) ? addslashes($bot_visit_counter) : $bot_visit_counter),
	);

	$input_fields_sql = '';
	$input_values_sql = '';
	$update_sql = '';
	foreach ($input_array as $k => $v)
	{
		$input_fields_sql .= (($input_fields_sql == '') ? ('(' . $k) : (', ' . $k));
		$input_values_sql .= (($input_values_sql == '') ? ('(' . $v) : (', ' . $v));
		$update_sql .= (($update_sql == '') ? ($k . ' = ' . $v) : (', ' . $k . ' = ' . $v));
	}
	$input_fields_sql .= (($input_fields_sql == '') ? '' : ')');
	$input_values_sql .= (($input_values_sql == '') ? '' : ')');

	$where_sql = ' WHERE bot_id = ' . $bot_id;

	if(($bot_id > 0) && !empty($update_sql))
	{
		$message = $lang['BOT_UPDATED'];
		$sql = "UPDATE " . $input_table . " SET " . $update_sql . $where_sql;
	}
	elseif(!empty($input_fields_sql))
	{
		$message = $lang['BOT_ADDED'];
		$sql = "INSERT INTO " . $input_table . " " . $input_fields_sql . " VALUES " . $input_values_sql;
	}
	else
	{
		$message = $lang['Error'];
	}

	if(($message != $lang['Error']) && !$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not insert data into bots table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$db->clear_cache('bots_list_');
	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_BOTS'], '<a href="' . append_sid('admin_bots.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);

}
elseif ($mode == 'delete')
{
	$sql = "DELETE FROM " . BOTS_TABLE . "
		WHERE bot_id = " . $bot_id;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not remove data from bots table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$db->clear_cache('bots_list_');
	$message = $lang['BOT_DELETED'] . '<br /><br />' . sprintf($lang['CLICK_RETURN_BOTS'], '<a href="' . append_sid('admin_bots.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);

}
elseif ($mode == 'update')
{
	$bots_upd = array();
	$bots_upd = $_POST['bots'];
	$bots_upd_n = count($bots_upd);
	$sql_no_gb = '';

	$sql = "SELECT * FROM " . BOTS_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query bots table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	while($row = $db->sql_fetchrow($result))
	{
		$b_active = empty($bots_upd) ? 0 : (in_array($row['bot_id'], $bots_upd) ? 1 : 0);
		$sql_upd = "UPDATE " . BOTS_TABLE . "
						SET bot_active = '" . $b_active . "'
						WHERE bot_id = " . $row['bot_id'];
		if(!$result_upd = $db->sql_query($sql_upd))
		{
			message_die(GENERAL_ERROR, 'Could not update bots table', $lang['Error'], __LINE__, __FILE__, $sql_upd);
		}
	}
	$db->sql_freeresult($result);

	$db->clear_cache('bots_list_');
	$message = $lang['BOT_UPDATED'];
	$message .= '<br /><br />' . sprintf($lang['CLICK_RETURN_BOTS'], '<a href="' . append_sid('admin_bots.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);

}
elseif ($mode == 'add')
{
	$template->set_filenames(array('body' => ADM_TPL . 'bots_add_body.tpl'));

	if ($bot_id > 0)
	{
		$sql = "SELECT *
			FROM " . BOTS_TABLE . "
			WHERE bot_id = " . $bot_id;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query bots table', $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		$bot_id = $row['bot_id'];
		$bot_active = $row['bot_active'];
		$bot_name = ((STRIP) ? stripslashes($row['bot_name']) : $row['bot_name']);
		$bot_color = ((STRIP) ? htmlspecialchars(stripslashes($row['bot_color'])) : htmlspecialchars($row['bot_color']));
		$bot_agent = ((STRIP) ? stripslashes($row['bot_agent']) : $row['bot_agent']);
		$bot_ip = ((STRIP) ? stripslashes($row['bot_ip']) : $row['bot_ip']);
		$bot_last_visit = $row['bot_last_visit'];
		$bot_visit_counter = ((STRIP) ? stripslashes($row['bot_visit_counter']) : $row['bot_visit_counter']);
	}

	$bot_active = ($bot_id > 0) ? $bot_active : true;

	$template->assign_vars(array(
		'L_FORM_TITLE' => (($bot_id > 0) ? $lang['BOT_EDIT'] : $lang['BOT_ADD']),
		'BOT_NAME' => $bot_name,
		'BOT_COLOR' => $bot_color,
		'BOT_AGENT' => $bot_agent,
		'BOT_IP' => $bot_ip,
		'BOT_COUNTER' => $bot_visit_counter,
		'BOT_ACTIVE_YES' => ($bot_active) ? ' checked="checked"' : '',
		'BOT_ACTIVE_NO' => (!$bot_active) ? ' checked="checked"' : '',

		'S_HIDDEN_FIELDS' => '<input type="hidden" name="bot_id" value="' . $bot_id . '" /><input type="hidden" name="mode" value="save" />',
		'S_BOTS_ACTION' => append_sid('admin_bots.' . PHP_EXT . '?mode=save'),
		)
	);

}
else
{
	// Main Page
	$template->set_filenames(array('body' => ADM_TPL . 'bots_body.tpl'));

	$u_sort_order = (($bot_sort_order == 'ASC') ? 'DESC' : 'ASC');
	$template->assign_vars(array(
		'U_BOT_SORT_ID' => append_sid('admin_bots.' . PHP_EXT . '?sort_by=bot_id&amp;sort_order=' . $u_sort_order),
		'U_BOT_SORT_NAME' => append_sid('admin_bots.' . PHP_EXT . '?sort_by=bot_name&amp;sort_order=' . $u_sort_order),
		'U_BOT_SORT_AGENT' => append_sid('admin_bots.' . PHP_EXT . '?sort_by=bot_agent&amp;sort_order=' . $u_sort_order),
		'U_BOT_SORT_IP' => append_sid('admin_bots.' . PHP_EXT . '?sort_by=bot_ip&amp;sort_order=' . $u_sort_order),
		'U_BOT_SORT_ACTIVE' => append_sid('admin_bots.' . PHP_EXT . '?sort_by=bot_active&amp;sort_order=' . $u_sort_order),
		'U_BOT_SORT_LAST_VISIT' => append_sid('admin_bots.' . PHP_EXT . '?sort_by=bot_last_visit&amp;sort_order=' . $u_sort_order),
		'U_BOT_SORT_COUNTER' => append_sid('admin_bots.' . PHP_EXT . '?sort_by=bot_visit_counter&amp;sort_order=' . $u_sort_order),

		'S_HIDDEN_FIELDS' => '<input type="hidden" name="mode" value="add" />',
		'S_BOTS_ACTION' => append_sid('admin_bots.' . PHP_EXT . '?mode=add'),
		)
	);

	$sql_sort = 'bot_id ASC';
	if ($bot_sort_by != '')
	{
		$sql_sort = $bot_sort_by . (($bot_sort_order == 'DESC') ? ' DESC' : ' ASC');
	}

	$sql = "SELECT *
		FROM " . BOTS_TABLE . "
		ORDER BY " . $sql_sort;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query bots table', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$i = 0;
	while($row = $db->sql_fetchrow($result))
	{
		$i++;
		$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('bots', array(
			'ROW_COLOR' => '#' . $row_color,
			'ROW_CLASS' => $row_class,
			'BOT_ID' => $row['bot_id'],
			'BOT_ACTIVE' => ($row['bot_active'] ? $lang['YES'] : $lang['NO']),
			'BOT_ACTIVE_CHECKED' => ($row['bot_active'] ? ' checked="checked"' : ''),
			'BOT_NAME' => $row['bot_name'],
			'BOT_COLOR' => (($row['bot_color'] == '') ? '&nbsp;' : $row['bot_color']),
			'BOT_AGENT' => (($row['bot_agent'] == '') ? '&nbsp;' : $row['bot_agent']),
			'BOT_IP' => (($row['bot_ip'] == '') ? '&nbsp;' : $row['bot_ip']),
			'BOT_LAST_VISIT' => (($row['bot_last_visit'] == 0) ? '-' : create_date2($board_config['default_dateformat'], $row['bot_last_visit'], $board_config['board_timezone'])),
			'BOT_COUNTER' => $row['bot_visit_counter'],

			'U_EDIT' => append_sid('admin_bots.' . PHP_EXT . '?mode=add&amp;bot_id=' . $row['bot_id']),
			'U_DELETE' => append_sid('admin_bots.' . PHP_EXT . '?mode=delete&amp;bot_id=' . $row['bot_id'])
			)
		);
	}

	if($i == 0)
	{
		$template->assign_block_vars('no_bots', array());
	}
	$db->sql_freeresult($result);

}

$template->pparse('body');

include('./page_footer_admin.' . PHP_EXT);

?>