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
* (c) 2005 oxpus (Karsten Ude) <webmaster@oxpus.de> http://www.oxpus.de
* (c) hotschi / demolition fabi / oxpus
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

$action = ($add) ? 'add' : $action;
$action = ($edit) ? 'edit' : $action;
$action = ($move) ? 'category_order' : $action;
$action = ($save_cat) ? 'save_cat' : $action;

if ($cancel)
{
	$action = '';
}

$index = array();
$index = $dl_mod->full_index();

if (!count($index) && $action != 'save_cat')
{
	$action = 'add';
}

if($action == 'edit' || $action == 'add')
{
	$s_hidden_fields = '<input type="hidden" name="action" value="save_cat" />';

	$s_auth_view = '<select name="auth_view">';
	$s_auth_dl = '<select name="auth_dl">';
	$s_auth_up = '<select name="auth_up">';
	$s_auth_mod = '<select name="auth_mod">';

	$s_auth_all = '<option value="1">' . $lang['Dl_perm_all'] . '</option>';
	$s_auth_all .= '<option value="2">' . $lang['Dl_perm_reg'] . '</option>';
	$s_auth_all .= '<option value="0">' . $lang['Dl_perm_grg'] . '</option>';
	$s_auth_all .= '</select>';

	$s_auth_view .= $s_auth_all;
	$s_auth_dl .= $s_auth_all;
	$s_auth_up .= $s_auth_all;
	$s_auth_mod .= $s_auth_all;

	if($action == 'edit' && $cat_id)
	{
		$cat_name = $index[$cat_id]['cat_name'];
		$cat_name = str_replace('&nbsp;&nbsp;|___&nbsp;', '', $cat_name);
		$description = $index[$cat_id]['description'];
		$rules = $index[$cat_id]['rules'];
		$cat_path = $index[$cat_id]['cat_path'];
		$cat_parent = '<select name="parent">';
		$cat_parent .= '<option value="0">&nbsp;&raquo;&nbsp;' . $lang['Dl_cat_index'] . '</option>';
		$cat_parent .= $dl_mod->dl_dropdown(0, 0, $index[$cat_id]['parent'], 'auth_view', $cat_id);
		$cat_parent .= '</select>';
		$bbcode_uid = $index[$cat_id]['bbcode_uid'];
		$description = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $description);
		$rules = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $rules);
		$statistics = $index[$cat_id]['statistics'];
		$stats_prune = $index[$cat_id]['stats_prune'];
		$comments = $index[$cat_id]['comments'];
		$must_approve = $index[$cat_id]['must_approve'];
		$allow_mod_desc = $index[$cat_id]['allow_mod_desc'];
		$cat_traffic = $index[$cat_id]['cat_traffic'];
		$cat_remain_traffic = $index[$cat_id]['cat_traffic'] - $index[$cat_id]['cat_traffic_use'];
		$allow_thumbs = $index[$cat_id]['allow_thumbs'];
		$auth_cread = $index[$cat_id]['auth_cread'];
		$auth_cpost = $index[$cat_id]['auth_cpost'];
		$approve_comments = $index[$cat_id]['approve_comments'];
		$bug_tracker = $index[$cat_id]['bug_tracker'];

		$s_auth_view = str_replace('value="'.$index[$cat_id]['auth_view_real'] . '">', 'value="'.$index[$cat_id]['auth_view_real'] . '" selected="selected">', $s_auth_view);
		$s_auth_dl = str_replace('value="'.$index[$cat_id]['auth_dl_real'] . '">', 'value="'.$index[$cat_id]['auth_dl_real'] . '" selected="selected">', $s_auth_dl);
		$s_auth_up = str_replace('value="'.$index[$cat_id]['auth_up_real'] . '">', 'value="'.$index[$cat_id]['auth_up_real'] . '" selected="selected">', $s_auth_up);
		$s_auth_mod = str_replace('value="'.$index[$cat_id]['auth_mod_real'] . '">', 'value="'.$index[$cat_id]['auth_mod_real'] . '" selected="selected">', $s_auth_mod);

		$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
	}
	else
	{
		$must_approve = 1;
		$allow_mod_desc = 0;
		$statistics = 1;
		$comments = 1;
		$stats_prune = 100000;
		$cat_parent = '<select name="parent">';
		$cat_parent .= '<option value="0">&nbsp;&raquo;&nbsp;' . $lang['Dl_cat_index'] . '</option>';
		$cat_parent .= $dl_mod->dl_dropdown(0, 0, 0, 'auth_view');
		$cat_parent .= '</select>';
		$cat_traffic = 0;
		$cat_remain_traffic = 0;
		$allow_thumbs = 0;
		$auth_cread = 0;
		$auth_cpost = 1;
		$approve_comments = 0;
		$bug_tracker = 0;

		$s_auth_view = str_replace('value="1">', 'value="0" selected="selected">', $s_auth_view);
		$s_auth_dl = str_replace('value="1">', 'value="0" selected="selected">', $s_auth_dl);
		$s_auth_up = str_replace('value="2">', 'value="1" selected="selected">', $s_auth_up);
		$s_auth_mod = str_replace('value="0">', 'value="9" selected="selected">', $s_auth_mod);
	}

	$cat_traffic_range_kb = 'checked="checked"';
	$cat_traffic_out = 0;
	$cat_remain_traffic = ($cat_remain_traffic < 0) ? 0 : $cat_remain_traffic;
	$cat_remain_traffic = $dl_mod->dl_size($cat_remain_traffic);

	if ($cat_traffic > 1023)
	{
		$cat_traffic_out = number_format($cat_traffic / 1024, 2);
		$cat_traffic_range_mb = '';
		$cat_traffic_range_gb = '';
	}
	if ($cat_traffic > 1048575)
	{
		$cat_traffic_out = number_format($cat_traffic / 1048576, 2);
		$cat_traffic_range_kb = '';
		$cat_traffic_range_mb = 'checked="checked"';
		$cat_traffic_range_gb = '';
	}
	if ($cat_traffic > 1073741823)
	{
		$cat_traffic_out = number_format($cat_traffic / 1073741824, 2);
		$cat_traffic_range_kb = '';
		$cat_traffic_range_mb = '';
		$cat_traffic_range_gb = 'checked="checked"';
	}

	$approve_yes = ($must_approve) ? 'checked="checked"' : '';
	$approve_no = (!$must_approve) ? 'checked="checked"' : '';

	$allow_mod_desc_yes = ($allow_mod_desc) ? 'checked="checked"' : '';
	$allow_mod_desc_no = (!$allow_mod_desc) ? 'checked="checked"' : '';

	$stats_yes = ($statistics) ? 'checked="checked"' : '';
	$stats_no = (!$statistics) ? 'checked="checked"' : '';

	$comments_yes = ($comments) ? 'checked="checked"' : '';
	$comments_no = (!$comments) ? 'checked="checked"' : '';

	$allow_thumbs_yes = ($allow_thumbs) ? 'checked="checked"' : '';
	$allow_thumbs_no = (!$allow_thumbs) ? 'checked="checked"' : '';

	$approve_comments_yes = ($approve_comments) ? 'checked="checked"' : '';
	$approve_comments_no = (!$approve_comments) ? 'checked="checked"' : '';

	$s_auth_cread = '<select name="auth_cread">';
	$s_auth_cread .= '<option value="0">' . $lang['Dl_stat_perm_all'] . '</option>';
	$s_auth_cread .= '<option value="1">' . $lang['Dl_stat_perm_user'] . '</option>';
	$s_auth_cread .= '<option value="2">' . $lang['Dl_stat_perm_mod'] . '</option>';
	$s_auth_cread .= '<option value="3">' . $lang['Dl_stat_perm_admin'] . '</option>';
	$s_auth_cread .= '</select>';
	$s_auth_cread = str_replace('value="'.$auth_cread.'">', 'value="'.$auth_cread.'" selected="selected">', $s_auth_cread);

	$s_auth_cpost = '<select name="auth_cpost">';
	$s_auth_cpost .= '<option value="0">' . $lang['Dl_stat_perm_all'] . '</option>';
	$s_auth_cpost .= '<option value="1">' . $lang['Dl_stat_perm_user'] . '</option>';
	$s_auth_cpost .= '<option value="2">' . $lang['Dl_stat_perm_mod'] . '</option>';
	$s_auth_cpost .= '<option value="3">' . $lang['Dl_stat_perm_admin'] . '</option>';
	$s_auth_cpost .= '</select>';
	$s_auth_cpost = str_replace('value="'.$auth_cpost.'">', 'value="'.$auth_cpost.'" selected="selected">', $s_auth_cpost);

	$bug_tracker_yes = ($bug_tracker) ? 'checked="checked"' : '';
	$bug_tracker_no = (!$bug_tracker) ? 'checked="checked"' : '';

	$template->set_filenames(array('category' => ADM_TPL . 'dl_cat_edit_body.tpl'));

	if ($dl_config['thumb_fsize'])
	{
		$template->assign_block_vars('thumbnails', array());
	}

	$template->assign_vars(array(
		'L_DL_CAT_TITLE' => $lang['Dl_cat_title'],
		'L_DL_CAT_PATH' => $lang['Dl_cat_path'],
		'L_DL_CAT_PATH_EXPLAIN' => 'Dl_cat_path',
		'L_DL_CAT_EDIT_TEXT' => $lang['Dl_cat_edit_explain'],
		'L_DL_NAME' => $lang['Dl_cat_name'],
		'L_DL_NAME_EXPLAIN' => 'Dl_cat_name',
		'L_DL_DESCRIPTION' => $lang['Dl_cat_description'],
		'L_DL_DESCRIPTION_EXPLAIN' => 'Dl_cat_description',
		'L_DL_RULES' => $lang['Dl_cat_rules'],
		'L_DL_RULES_EXPLAIN' => 'Dl_cat_rules',
		'L_DL_PARENT' => $lang['Dl_cat_parent'],
		'L_DL_PARENT_EXPLAIN' => 'Dl_cat_parent',
		'L_DL_MUST_APPROVE' => $lang['Dl_must_approve'],
		'L_DL_MUST_APPROVE_EXPLAIN' => 'Dl_must_approve',
		'L_DL_ALLOW_MOD_DESC' => $lang['Dl_mod_desc_allow'],
		'L_DL_ALLOW_MOD_DESC_EXPLAIN' => 'Dl_mod_desc_allow',
		'L_DL_STATISTICS' => $lang['Dl_statistics'],
		'L_DL_STATISTICS_EXPLAIN' => 'Dl_statistics',
		'L_DL_STATS_PRUNE' => $lang['Dl_stats_prune'],
		'L_DL_STATS_PRUNE_EXPLAIN' => 'Dl_stats_prune',
		'L_DL_COMMENTS' => $lang['Dl_comments'],
		'L_DL_COMMENTS_EXPLAIN' => 'Dl_comments',
		'L_DL_CAT_MODE' => ($action == 'edit') ? $lang['Edit'] : $lang['Add_new'],
		'L_DL_CAT_TRAFFIC' => ($index[$cat_id]['cat_traffic']) ? sprintf($lang['Dl_cat_traffic'], $cat_remain_traffic) : $lang['Dl_cat_traffic_off'],
		'L_DL_CAT_TRAFFIC_EXPLAIN' => 'Dl_cat_traffic',
		'L_DL_THUMBNAIL' => $lang['Dl_thumb_cat'],
		'L_DL_THUMBNAIL_EXPLAIN' => 'Dl_thumb_cat',
		'L_DL_APPROVE' => $lang['Dl_approve_comments'],
		'L_DL_APPROVE_EXPLAIN' => 'Dl_approve_comments',
		'L_DL_BUG_TRACKER' => $lang['Dl_bug_tracker_cat'],
		'L_DL_BUG_TRACKER_EXPLAIN' => 'Dl_bug_tracker_cat',
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_DL_KB' => $lang['Dl_KB'],
		'L_DL_MB' => $lang['Dl_MB'],
		'L_DL_GB' => $lang['Dl_GB'],

		'L_PERMISSIONS' => $lang['Dl_permissions'],
		'L_PERMISSIONS_ALL' => $lang['Dl_permissions_all'],
		'L_GROUP' => $lang['Groups'],
		'L_AUTH_VIEW' => $lang['Dl_auth_view'],
		'L_AUTH_DL' => $lang['Dl_auth_dl'],
		'L_AUTH_UP' => $lang['Dl_auth_up'],
		'L_AUTH_MOD' => $lang['Dl_auth_mod'],
		'L_AUTH_CREAD' => $lang['Dl_auth_cread'],
		'L_AUTH_CPOST' => $lang['Dl_auth_cpost'],

		'CATEGORY' => sprintf($lang['Dl_permission_cat'], $index[$cat]['cat_name']),

		'CAT_PATH' => (!$cat_path) ? '/' : $cat_path,
		'MUST_APPROVE_YES' => $approve_yes,
		'MUST_APPROVE_NO' => $approve_no,
		'ALLOW_MOD_DESC_YES' => $allow_mod_desc_yes,
		'ALLOW_MOD_DESC_NO' => $allow_mod_desc_no,
		'STATS_YES' => $stats_yes,
		'STATS_NO' => $stats_no,
		'STATS_PRUNE' => $stats_prune,
		'COMMENTS_YES' => $comments_yes,
		'COMMENTS_NO' => $comments_no,
		'CAT_NAME' => $cat_name,
		'DESCRIPTION' => $description,
		'RULES' => $rules,
		'CAT_PARENT' => $cat_parent,
		'CAT_TRAFFIC' => $cat_traffic_out,
		'CAT_TRAFFIC_RANGE_KB' => $cat_traffic_range_kb,
		'CAT_TRAFFIC_RANGE_MB' => $cat_traffic_range_mb,
		'CAT_TRAFFIC_RANGE_GB' => $cat_traffic_range_gb,
		'ALLOW_THUMBS_YES' => $allow_thumbs_yes,
		'ALLOW_THUMBS_NO' => $allow_thumbs_no,
		'APPROVE_COMMENTS_YES' => $approve_comments_yes,
		'APPROVE_COMMENTS_NO' => $approve_comments_no,
		'BUG_TRACKER_YES' => $bug_tracker_yes,
		'BUG_TRACKER_NO' => $bug_tracker_no,

		'S_AUTH_VIEW' => $s_auth_view,
		'S_AUTH_DL' => $s_auth_dl,
		'S_AUTH_UP' => $s_auth_up,
		'S_AUTH_MOD' => $s_auth_mod,

		'S_COMMENT_VIEW' => $s_auth_cread,
		'S_COMMENT_POST' => $s_auth_cpost,
		'S_CATEGORY_ACTION' => append_sid('admin_downloads.' . $phpEx . '?submod=categories'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

	$group_auth = array();

	$sql = "SELECT * FROM " . DL_AUTH_TABLE . "
		WHERE cat_id = $cat_id";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Could not query group permission information", "", __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$group_id = $row['group_id'];
		$group_auth[$group_id]['auth_view'] = $row['auth_view'];
		$group_auth[$group_id]['auth_dl'] = $row['auth_dl'];
		$group_auth[$group_id]['auth_up'] = $row['auth_up'];
		$group_auth[$group_id]['auth_mod'] = $row['auth_mod'];
	}

	$db->sql_freeresult($result);

	$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . "
		WHERE group_single_user <> " . TRUE . "
		ORDER BY group_name";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, "Could not query group permission information", "", __LINE__, __FILE__, $sql);
	}

	$total_groups = $db->sql_numrows($result);
	if ($total_groups)
	{
		$template->assign_block_vars('group_block', array());

		while( $row = $db->sql_fetchrow($result) )
		{
			$group_id = $row['group_id'];
			$group_name = $row['group_name'];

			$auth_view_group = ($group_auth[$group_id]['auth_view']) ? 'checked="checked"' : '';
			$auth_dl_group = ($group_auth[$group_id]['auth_dl']) ? 'checked="checked"' : '';
			$auth_up_group = ($group_auth[$group_id]['auth_up']) ? 'checked="checked"' : '';
			$auth_mod_group = ($group_auth[$group_id]['auth_mod']) ? 'checked="checked"' : '';

			$template->assign_block_vars('group_block.group_row',array(
				'GROUP_ID' => $group_id,
				'GROUP_NAME' => $group_name,
				'AUTH_VIEW_GROUP' => $auth_view_group,
				'AUTH_DL_GROUP' => $auth_dl_group,
				'AUTH_UP_GROUP' => $auth_up_group,
				'AUTH_MOD_GROUP' => $auth_mod_group
				)
			);
		}
	}

	$template->pparse('category');

	include('./page_footer_admin.' . $phpEx);
}
elseif($action == 'save_cat')
{
	$cat_parent = ( isset($_POST['parent']) ) ? intval($_POST['parent']) : 0;
	$description = ( isset($_POST['description']) ) ? trim($_POST['description']) : '';
	$rules = ( isset($_POST['rules']) ) ? trim($_POST['rules']) : '';
	$cat_name = ( isset($_POST['cat_name']) ) ? trim($_POST['cat_name']) : '';
	$path = ( isset($_POST['path']) ) ? trim($_POST['path']) : '';
	$bbcode_uid = ($board_config['allow_bbcode']) ? make_bbcode_uid() : '';
	$bbcode_on = $board_config['allow_bbcode'];
	$smilie_on = $board_config['allow_smilies'];
	$html_on = $board_config['allow_html'];
	$description = prepare_message(trim($description), $html_on, $bbcode_on, $smilie_on, $bbcode_uid);
	$rules = prepare_message(trim($rules), $html_on, $bbcode_on, $smilie_on, $bbcode_uid);
	$must_approve = ( isset($_POST['must_approve']) ) ? intval($_POST['must_approve']) : 0;
	$allow_mod_desc = ( isset($_POST['allow_mod_desc']) ) ? intval($_POST['allow_mod_desc']) : 0;
	$statistics = ( isset($_POST['allow_mod_desc']) ) ? intval($_POST['statistics']) : 0;
	$stats_prune = ( isset($_POST['allow_mod_desc']) ) ? intval($_POST['stats_prune']) : 0;
	$comments = ( isset($_POST['comments']) ) ? intval($_POST['comments']) : 0;
	$cat_traffic = ( isset($_POST['cat_traffic']) ) ? intval($_POST['cat_traffic']) : 0;
	$cat_traffic_range = ( isset($_POST['cat_traffic_range']) ) ? trim($_POST['cat_traffic_range']) : "";
	$allow_thumbs = ( isset($_POST['allow_thumbs']) ) ? intval($_POST['allow_thumbs']) : 0;
	$approve_comments = ( isset($_POST['approve_comments']) ) ? intval($_POST['approve_comments']) : 0;
	$auth_view = ( isset($_POST['auth_view']) ) ? intval($_POST['auth_view']) : 0;
	$auth_dl = ( isset($_POST['auth_dl']) ) ? intval($_POST['auth_dl']) : 0;
	$auth_up = ( isset($_POST['auth_up']) ) ? intval($_POST['auth_up']) : 0;
	$auth_mod = ( isset($_POST['auth_mod']) ) ? intval($_POST['auth_mod']) : 0;
	$auth_cread = ( isset($_POST['auth_cread']) ) ? intval($_POST['auth_cread']) : 3;
	$auth_cpost = ( isset($_POST['auth_cpost']) ) ? intval($_POST['auth_cpost']) : 3;
	$bug_tracker = ( isset($_POST['bug_tracker']) ) ? intval($_POST['bug_tracker']) : 0;

	if ($cat_traffic_range == 'KB')
	{
		$cat_traffic = $cat_traffic * 1024;
	}
	elseif ($cat_traffic_range == 'MB')
	{
		$cat_traffic = $cat_traffic * 1048576;
	}
	elseif ($cat_traffic_range == 'GB')
	{
		$cat_traffic = $cat_traffic * 1073741824;
	}

	if (!@file_exists($dl_config['dl_path'] . $path) || (substr($path, strlen($path) - 1, 1) <> '/'))
	{
		$message = sprintf($lang['Dl_path_not_exist'], $path);
	}
	elseif($cat_id)
	{
		$sql = "UPDATE " . DL_CAT_TABLE . " SET
			description = '" . str_replace("\'", "''", $description) . "',
			rules = '" . str_replace("\'", "''", $rules) . "',
			parent = $cat_parent,
			cat_name = '" . str_replace("\'", "''", $cat_name) . "',
			path= '".str_replace("\'", "''", $path)."',
			bbcode_uid = '$bbcode_uid',
			must_approve = $must_approve,
			allow_mod_desc = $allow_mod_desc,
			statistics = $statistics,
			stats_prune = $stats_prune,
			comments = $comments,
			cat_traffic = $cat_traffic,
			allow_thumbs = $allow_thumbs,
			approve_comments = $approve_comments,
			auth_view = $auth_view,
			auth_dl = $auth_dl,
			auth_up = $auth_up,
			auth_mod = $auth_mod,
			auth_cread = $auth_cread,
			auth_cpost = $auth_cpost,
			bug_tracker = $bug_tracker
			WHERE id = $cat_id";

		$message = $lang['Dl_category_updated'];
	}
	else
	{
		$sql = "INSERT INTO " . DL_CAT_TABLE . "
			(cat_name, parent, description, rules, path, bbcode_uid, must_approve, allow_mod_desc, statistics, stats_prune, comments, cat_traffic, allow_thumbs, approve_comments, auth_view, auth_dl, auth_up, auth_mod, auth_cread, auth_cpost, bug_tracker)
			VALUES
			('" . str_replace("\'", "''", $cat_name) . "', $cat_parent, '" . str_replace("\'", "''", $description) . "', '" . str_replace("\'", "''", $rules) . "', '".str_replace("\'", "''", $path)."', '$bbcode_uid', $must_approve, $allow_mod_desc, $statistics, $stats_prune, $comments, $cat_traffic, $allow_thumbs, $approve_comments, $auth_view, $auth_dl, $auth_up, $auth_mod, $auth_cread, $auth_cpost, $bug_tracker)";

		$message = $lang['Dl_category_added'];
	}

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't update/insert into category table", "", __LINE__, __FILE__, $sql);
	}

	$auth_view_set = (isset($_POST['auth_view_set'])) ? $_POST['auth_view_set'] : array();
	$auth_dl_set = (isset($_POST['auth_dl_set'])) ? $_POST['auth_dl_set'] : array();
	$auth_up_set = (isset($_POST['auth_up_set'])) ? $_POST['auth_up_set'] : array();
	$auth_mod_set = (isset($_POST['auth_mod_set'])) ? $_POST['auth_mod_set'] : array();

	$sql = "DELETE FROM " . DL_AUTH_TABLE . "
		WHERE cat_id = $cat_id";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, "Could not delete existing group permissions", "", __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT group_id FROM " . GROUPS_TABLE . "
		WHERE group_single_user <> " . TRUE;
	if(!($result = $db->sql_query($sql)))
	{
		message_die(CRITICAL_ERROR, "Could not query group permission config information", "", __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$group_id = $row['group_id'];

		$auth_view = intval($auth_view_set[$group_id]);
		$auth_dl = intval($auth_dl_set[$group_id]);
		$auth_up = intval($auth_up_set[$group_id]);
		$auth_mod = intval($auth_mod_set[$group_id]);

		$sql_insert = "INSERT INTO " . DL_AUTH_TABLE . " (cat_id, group_id, auth_view, auth_dl, auth_up, auth_mod)
				VALUES ($cat_id, $group_id, $auth_view, $auth_dl, $auth_up, $auth_mod)";
		if ( !$db->sql_query($sql_insert) )
		{
			message_die(GENERAL_ERROR, 'Could not insert new group permissions', '', __LINE__, __FILE__, $sql_insert);
		}
	}

	$db->sql_freeresult($result);

	$message .= '<br /><br />' . sprintf($lang['Click_return_categoryadmin'], '<a href="' . append_sid('admin_downloads.' . $phpEx . '?submod=categories') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
elseif($action == 'delete')
{
	if ($cat_id && $confirm)
	{
		if( $new_cat_id == -1 )
		{
			$sql = "SELECT c.path, d.file_name FROM " . DL_CAT_TABLE . " c, " . DOWNLOADS_TABLE . " d
				WHERE d.cat = c.id
					AND c.id = $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not delete category data", "", __LINE__, __FILE__, $sql);
			}

			while ($row = $db->sql_fetchrow($result))
			{
				$path = $row['path'];
				$file_name = $row['file_name'];
				@unlink($dl_config['dl_path'] . $path . $file_name);
			}

			$db->sql_freeresult($result);

			$sql = "DELETE FROM " . DOWNLOADS_TABLE . "
				WHERE cat = $cat_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not delete download data", "", __LINE__, __FILE__, $sql);
			}
		}

		if ($new_cat_id > 0)
		{
			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET cat = $new_cat_id
				WHERE cat = $cat_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not move downloads to new category", "", __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . DL_STATS_TABLE . "
				SET cat_id = $new_cat_id
				WHERE cat_id = $cat_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not delete category statistical data", "", __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . DL_COMMENTS_TABLE . "
				SET cat_id = $new_cat_id
				WHERE cat_id = $cat_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not delete category statistical data", "", __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "DELETE FROM " . DL_STATS_TABLE . "
				WHERE cat_id = $cat_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not delete category statistical data", "", __LINE__, __FILE__, $sql);
			}
		}

		$sql = "DELETE FROM " . DL_CAT_TABLE . "
			WHERE id = $cat_id";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Could not delete category data", "", __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . DL_COMMENTS_TABLE . "
			WHERE cat_id = $cat_id";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Could not delete category data", "", __LINE__, __FILE__, $sql);
		}

		$message = $lang['Dl_category_removed'] . '<br /><br />' . sprintf($lang['Click_return_categoryadmin'], '<a href="' . append_sid('admin_downloads.' . $phpEx . '?submod=categories') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	elseif($cat_id && !$confirm)
	{
		$cat_name = $index[$cat_id]['cat_name'];
		$cat_name = str_replace('&nbsp;&nbsp;|___&nbsp;', '', $cat_name);

		$s_switch_cat = '<select name="new_cat_id">';
		$s_switch_cat .= '<option value="0">' . $lang['Dl_delete_cat_only'] . '</option>';
		$s_switch_cat .= '<option value="-1" SELECTED>' . $lang['Dl_delete_cat_and_files'] . '</option>';
		$s_switch_cat .= '<option value="---">----------------------------------------</option>';
		$s_switch_cat .= $dl_mod->dl_dropdown(0, 0, $cat_id, 'auth_move');
		$s_switch_cat .= '</select>';

		$template->set_filenames(array(
			'confirm_body' => 'dl_confirm_body.tpl')
		);

		$template->assign_block_vars('choose_new_cat', array());

		$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';
		$s_hidden_fields .= '<input type="hidden" name="confirm" value="1" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => sprintf($lang['Dl_confirm_cat_delete'], $cat_name),

			'L_SWITCH_CAT' => $lang['Dl_delete_cat_confirm'],
			'S_SWITCH_CAT' => $s_switch_cat,

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . $phpEx . '?submod=categories'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include('./page_footer_admin.' . $phpEx);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Must_select_category']);
	}
}
elseif($action == 'delete_stats')
{
	if(!$confirm)
	{
		$cat_name = $index[$cat_id]['cat_name'];
		$cat_name = str_replace('&nbsp;&nbsp;|___&nbsp;', '', $cat_name);

		$template->set_filenames(array('confirm_body' => 'dl_confirm_body.tpl'));

		$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="action" value="delete_stats" />';
		$s_hidden_fields .= '<input type="hidden" name="confirm" value="1" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => ($cat_id == 'all') ? $lang['Dl_confirm_all_stats_delete'] : sprintf($lang['Dl_confirm_cat_stats_delete'], $cat_name),

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . $phpEx . '?submod=categories'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include('./page_footer_admin.' . $phpEx);
	}

	if($cat_id == 'all')
	{
		$sql = "DELETE FROM " . DL_STATS_TABLE;
		if(!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, "Could not delete all statistics data", "", __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		if ($cat_id)
		{
			$sql = "DELETE FROM " . DL_STATS_TABLE . "
				WHERE cat_id = " . (int)$cat_id;
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Could not delete statistics data", "", __LINE__, __FILE__, $sql);
			}
		}
	}
}
elseif($action == 'delete_comments')
{
	if(!$confirm)
	{
		$cat_name = $index[$cat_id]['cat_name'];
		$cat_name = str_replace('&nbsp;&nbsp;|___&nbsp;', '', $cat_name);

		$template->set_filenames(array(
			'confirm_body' => 'dl_confirm_body.tpl')
		);

		$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
		$s_hidden_fields .= '<input type="hidden" name="action" value="delete_comments" />';
		$s_hidden_fields .= '<input type="hidden" name="confirm" value="1" />';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => ($cat_id == 'all') ? $lang['Dl_confirm_all_comments_delete'] : sprintf($lang['Dl_confirm_cat_comments_delete'], $cat_name),

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid('admin_downloads.' . $phpEx . '?submod=categories'),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

		$template->pparse('confirm_body');

		include('./page_footer_admin.' . $phpEx);
	}

	if($cat_id == 'all')
	{
		$sql = "DELETE FROM " . DL_COMMENTS_TABLE;
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Could not delete all comments data", "", __LINE__, __FILE__, $sql);
		}
	}
	else
	{
		if ($cat_id)
		{
			$sql = "DELETE FROM " . DL_COMMENTS_TABLE . "
				WHERE cat_id = " . (int)$cat_id;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not delete comments data", "", __LINE__, __FILE__, $sql);
			}
		}
	}
}
elseif($action == 'category_order')
{
	$sql_move = ($move) ? '+ 15' : '- 15';

	$sql = "UPDATE " . DL_CAT_TABLE . "
		SET sort = sort $sql_move
		WHERE id = $cat_id";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't change category order", "", __LINE__, __FILE__, $sql);
	}

	$par_cat = $index[$cat_id]['parent'];

	$sql = "SELECT * FROM " . DL_CAT_TABLE . "
		WHERE parent = " .(int)$par_cat . "
		ORDER BY sort";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql_move = "UPDATE " . DL_CAT_TABLE . "
				SET sort = $i
				WHERE id = " . $row['id'];
		if( !$db->sql_query($sql_move) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql_move);
		}
		$i += 10;
	}

	$db->sql_freeresult($result);
}
elseif($action == 'asc_sort')
{
	$sql = "SELECT * FROM " . DL_CAT_TABLE . "
		WHERE parent = " .intval($cat_id) . "
		ORDER BY cat_name ASC";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql_move = "UPDATE " . DL_CAT_TABLE . "
				SET sort = $i
				WHERE id = " . $row['id'];
		if( !$db->sql_query($sql_move) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql_move);
		}
		$i += 10;
	}

	$db->sql_freeresult($result);
}

/*
* show the default page
*/
$template->set_filenames(array('categories' => ADM_TPL . 'dl_cat_body.tpl'));

$stats_cats = array();
$comments_cats = array();

$sql = "SELECT cat_id, count(dl_id) as total_stats FROM " . DL_STATS_TABLE . "
	GROUP BY cat_id";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not count statstical data', '', __LINE__, __FILE__, $sql);
}

while($row = $db->sql_fetchrow($result))
{
	$stats_cats[$row['cat_id']] = $row['total_stats'];
}

$db->sql_freeresult($result);

$sql = "SELECT cat_id, count(dl_id) as total_comments FROM " . DL_COMMENTS_TABLE . "
	GROUP BY cat_id";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not count comments data', '', __LINE__, __FILE__, $sql);
}

while($row = $db->sql_fetchrow($result))
{
	$comments_cats[$row['cat_id']] = $row['total_comments'];
}

$db->sql_freeresult($result);

$stats_total = 0;
$comments_total = 0;
$i = 0;

unset($index);
unset($dl_index);
unset($tree_dl);
$index = array();
$dl_index = array();
$tree_dl = array();
$dl_mod = new dlmod();
$index = $dl_mod->full_index();

foreach (array_keys($index) as $key)
{
	$cat_id = $key;
	$cat_name = $index[$cat_id]['cat_name'];

	$cat_edit = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=edit&amp;cat_id=' . $cat_id);
	$cat_delete = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=delete&amp;cat_id=' . $cat_id);

	$dl_move_up = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=category_order&amp;move=0&amp;cat_id=' . $cat_id);
	$dl_move_down = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=category_order&amp;move=1&amp;cat_id=' . $cat_id);

	if ($dl_mod->count_sublevel($cat_id) > 1)
	{
		$l_sort_asc = $lang['Dl_sub_sort_asc'];
		$dl_sort_asc = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=asc_sort&amp;cat_id=' . $cat_id);
	}
	else
	{
		$l_sort_asc = '';
		$dl_sort_asc = '';
	}

	$l_delete_stats = '';
	$l_delete_comments = '';
	$u_delete_stats = '';
	$u_delete_comments = '';

	if ($stats_cats[$cat_id] > 0)
	{
		$l_delete_stats = '<br />' . $lang['Dl_stats_delete'];
		$u_delete_stats = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=delete_stats&amp;cat_id=' . $cat_id);
		$stats_total++;
	}

	if ($comments_cats[$cat_id] > 0)
	{
		$l_delete_comments = '<br />' . $lang['Dl_comments_delete'];
		$u_delete_comments = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=delete_comments&amp;cat_id=' . $cat_id);
		$comments_total++;
	}

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('categories', array(
		'L_DELETE_STATS' => $l_delete_stats,
		'L_DELETE_COMMENTS' => $l_delete_comments,
		'L_SORT_ASC' => $l_sort_asc,

		'ROW_CLASS' => $row_class,
		'CAT_NAME' => $cat_name,

		'U_CAT_EDIT' => $cat_edit,
		'U_CAT_DELETE' => $cat_delete,
		'U_CATEGORY_MOVE_UP' => $dl_move_up,
		'U_CATEGORY_MOVE_DOWN' => $dl_move_down,
		'U_CATEGORY_ASC_SORT' => $dl_sort_asc,
		'U_DELETE_STATS' => $u_delete_stats,
		'U_DELETE_COMMENTS' => $u_delete_comments)
	);

	$i++;
}

if ($stats_total)
{
	$l_delete_stats_all = $lang['Dl_stats_delete_all'];
	$u_delete_stats_all = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=delete_stats&amp;cat_id=all');
}

if ($comments_total)
{
	$l_delete_comments_all = $lang['Dl_comments_delete_all'];
	$u_delete_comments_all = append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=delete_comments&amp;cat_id=all');
}

$template->assign_vars(array(
	'L_DELETE_STATS_ALL' => $l_delete_stats_all,
	'L_DELETE_COMMENTS_ALL' => $l_delete_comments_all,
	'L_DL_CAT_TITLE' => $lang['Dl_cat_title'],
	'L_DL_CAT_EDIT_TEXT' => $lang['Dl_cat_edit_explain'],
	'L_DL_NAME' => $lang['Dl_cat_name'],
	'L_EDIT' => $lang['Edit'],
	'L_DELETE_CAT' => $lang['Dl_delete_cat'],
	'L_ORDER' => $lang['Order'],
	'L_DL_ADD_CAT' => $lang['Dl_add_category'],
	'L_ACTION' => $lang['Action'],
	'L_UP' => $lang['Dl_up'],
	'L_DOWN' => $lang['Dl_down'],
	'L_SORT_ASC_LEVEL_ZERO' => $lang['Dl_sub_sort_asc_zero'],

	'CAT_PATH' => $cat_path,
	'CAT_NAME' => $cat_name,

	'S_CATEGORY_ACTION' => append_sid('admin_downloads.' . $phpEx . '?submod=categories'),

	'U_SORT_LEVEL_ZERO' => append_sid('admin_downloads.' . $phpEx . '?submod=categories&amp;action=asc_sort&amp;cat_id=0'),
	'U_DELETE_STATS_ALL' => $u_delete_stats_all,
	'U_DELETE_COMMENTS_ALL' => $u_delete_comments_all
	)
);

$template->pparse('categories');

?>