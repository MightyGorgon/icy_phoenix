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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/


if(defined('IN_ICYPHOENIX') && !empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1610_Users']['120_Ranks'] = $file;
	return;
}
define('IN_ICYPHOENIX', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$cancel = isset($_POST['cancel']) ? true : false;
$no_page_header = $cancel;
require('pagestart.' . PHP_EXT);
if ($cancel)
{
	redirect(ADM . '/' . append_sid('admin_ranks.' . PHP_EXT, true));
}

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

// Restrict mode input to valid options
$mode = (in_array($mode, array('add', 'edit', 'save', 'delete'))) ? $mode : '';

if($mode != '')
{
	if(($mode == 'edit') || ($mode == 'add'))
	{
		// They want to add a new rank, show the form.
		$rank_id = request_get_var('id', 0);

		$s_hidden_fields = '';

		if($mode == 'edit')
		{
			if(empty($rank_id))
			{
				message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
			}

			$sql = "SELECT * FROM " . RANKS_TABLE . " WHERE rank_id = $rank_id";
			$result = $db->sql_query($sql);
			$rank_info = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $rank_id . '" />';

		}
		else
		{
			$rank_info['rank_special'] = 0;
			$rank_info['rank_show_title'] = 1;
		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$rank_no_rank = ($rank_info['rank_special'] == '-2') ? 'checked="checked"' : '';
		$rank_day_counter = ($rank_info['rank_special'] == '-1') ? 'checked="checked"' : '';
		$rank_is_not_special = ($rank_info['rank_special'] == '0') ? 'checked="checked"' : '';
		$rank_is_special = ($rank_info['rank_special'] == '1') ? 'checked="checked"' : '';
		$rank_is_guest = ($rank_info['rank_special'] == '2') ? 'checked="checked"' : '';
		$rank_is_banned = ($rank_info['rank_special'] == '3') ? 'checked="checked"' : '';

		$rank_path = '../images/ranks/';

		if (@is_dir($rank_path))
		{
			$skip_files = array(
				'.',
				'..',
				'.htaccess',
				'index.htm',
				'index.html',
				'index.' . PHP_EXT,
			);

			$ranks_array = array();
			$dir = @opendir($rank_path);
			while($file = @readdir($dir))
			{
				$file_part = explode('.', strtolower($file));
				$file_ext = $file_part[sizeof($file_part) - 1];
				if(!@is_dir($file) && !in_array($file, $skip_files) && in_array($file_ext, array('gif', 'jpg', 'png')))
				{
					$ranks_array[] = $file;
				}
			}
			@closedir($dir);
			if (!empty($ranks_array))
			{
				sort($ranks_array);
				reset($ranks_array);
			}
		}

		if (!empty($ranks_array))
		{
			$ranks_list = '<select name="rank_image_sel" onchange="update_rank(this.options[selectedIndex].value);">';
			if ($rank_info['rank_image'] == '')
			{
				$ranks_list .= '<option value="" selected="selected">' . $lang['No_Rank_Image'] . '</option>';
			}
			else
			{
				$ranks_list .= '<option value="">' . $lang['No_Rank_Image'] . '</option>';
				$ranks_list .= '<option value="' . $rank_info['rank_image'] . '" selected="selected">' . str_replace($rank_path, '', $rank_info['rank_image']) . '</option>';
			}
			for($k = 0; $k <= sizeof($ranks_array); $k++)
			{
				if ($ranks_array[$k] != "")
				{
					$ranks_list .= '<option value="images/ranks/' . $ranks_array[$k] . '">images/ranks/' . $ranks_array[$k] . '</option>';
				}
			}
			$rank_img_sp = (($rank_info['rank_image'] != '') ? ('../' . $rank_info['rank_image']) : $images['spacer']);
			$rank_img_path = ($rank_info['rank_image'] != '') ? $rank_info['rank_image'] : '';
			$ranks_list .= '</select>';
			$ranks_list .= '&nbsp;&nbsp;<img name="rank_image" src="' . $rank_img_sp . '" alt="" align="middle" />';
			$ranks_list .= '<br /><br />';
			$ranks_list .= '<input class="post" type="text" name="rank_image_path" size="40" maxlength="255" value="' . $rank_img_path . '" />';
			$ranks_list .= '<br />';
		}
		else
		{
			$rank_img_path = ($rank_info['rank_image'] != '') ? $rank_info['rank_image'] : '';
			$ranks_list = '<input class="post" type="text" name="rank_image_path" size="40" maxlength="255" value="' . $rank_img_path . '" /><br />';
		}
		// Mighty Gorgon - Multiple Ranks - END

		$template->set_filenames(array('body' => ADM_TPL . 'ranks_edit_body.tpl'));

		$template->assign_vars(array(
			'RANK' => $rank_info['rank_title'],
			// Mighty Gorgon - Multiple Ranks - BEGIN
			'NO_RANK' => $rank_no_rank,
			'DAYS_RANK' => $rank_day_counter,
			'NOT_SPECIAL_RANK' => $rank_is_not_special,
			'MINIMUM' => (($rank_info['rank_special'] == '0') || ($rank_info['rank_special'] == '-1')) ? $rank_info['rank_min'] : '',
			'SPECIAL_RANK' => $rank_is_special,
			'GUEST_RANK' => $rank_is_guest,
			'BANNED_RANK' => $rank_is_banned,
			'RANK_LIST' => $ranks_list,
			'RANK_IMG' => ($rank_info['rank_image'] != '') ? '../' . $rank_info['rank_image'] : $images['spacer'],

			'RANK_SHOW_TITLE_YES' => (!empty($rank_info['rank_show_title']) ? 'checked="checked"' : ''),
			'RANK_SHOW_TITLE_NO' => (empty($rank_info['rank_show_title']) ? 'checked="checked"' : ''),

			'L_NO_RANK' => $lang['No_Rank'],
			'L_DAYS_RANK' => $lang['Rank_Days_Count'],
			'L_POSTS_RANK' => $lang['Rank_Posts_Count'],
			'L_MIN_M_D' => $lang['Rank_Min_Des'],
			'L_SPECIAL_RANK' => $lang['Rank_Special'],
			'L_GUEST' => $lang['Guest_User'],
			'L_BANNED' => $lang['Banned_User'],
			'L_CURRENT_RANK' => $lang['Current_Rank_Image'],
			// Mighty Gorgon - Multiple Ranks - END
			'IMAGE' => ($rank_info['rank_image'] != '') ? $rank_info['rank_image'] : '',
			'IMAGE_DISPLAY' => ($rank_info['rank_image'] != '') ? '<img src="../' . $rank_info['rank_image'] . '" />' : '',

			'L_RANKS_TITLE' => $lang['Ranks_title'],
			'L_RANKS_TEXT' => $lang['Ranks_explain'],
			'L_RANK_TITLE' => $lang['Rank_title'],
			'L_RANK_SPECIAL' => $lang['Rank_special'],
			'L_RANK_MINIMUM' => $lang['Rank_minimum'],
			'L_RANK_IMAGE' => $lang['Rank_image'],
			'L_RANK_IMAGE_EXPLAIN' => $lang['Rank_image_explain'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_RANK_ACTION' => append_sid('admin_ranks.' . PHP_EXT),
			'S_HIDDEN_FIELDS' => $s_hidden_fields
			)
		);

	}
	elseif($mode == 'save')
	{
		// Ok, they sent us our info, let's update it.
		$rank_id = request_post_var('id', 0);
		$rank_title = request_post_var('title', '', true);
		$rank_show_title = request_post_var('rank_show_title', 1);
		$rank_show_title = empty($rank_show_title) ? 0 : 1;
		// Mighty Gorgon - Multiple Ranks - BEGIN
		$special_rank = request_post_var('special_rank', 0);
		$min_posts = request_post_var('min_posts', -1);
		$rank_image = request_post_var('rank_image_path', '', true);
		// Mighty Gorgon - Multiple Ranks - END

		if(empty($rank_title))
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
		}

		// Mighty Gorgon - Multiple Ranks - BEGIN
		if($special_rank > 0)
		// Mighty Gorgon - Multiple Ranks - END
		{
			$max_posts = -1;
			$min_posts = -1;
		}

		// The rank image has to be a jpg, gif or png
		if($rank_image != '')
		{
			if (!preg_match("/(\.gif|\.jpg|\.png)$/is", $rank_image))
			{
				$rank_image = '';
			}
		}

		if ($rank_id)
		{
			/*
			// Mighty Gorgon - Multiple Ranks - BEGIN
			if ($special_rank == 1)
			// Mighty Gorgon - Multiple Ranks - END
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_rank = 0
					WHERE user_rank = $rank_id";
				$result = $db->sql_query($sql);
			}
			*/
			$sql = "UPDATE " . RANKS_TABLE . "
				SET rank_title = '" . $db->sql_escape($rank_title) . "', rank_special = $special_rank, rank_min = $min_posts, rank_image = '" . $db->sql_escape($rank_image) . "', rank_show_title = $rank_show_title
				WHERE rank_id = $rank_id";

			$message = $lang['Rank_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . RANKS_TABLE . " (rank_title, rank_special, rank_min, rank_image, rank_show_title)
				VALUES ('" . $db->sql_escape($rank_title) . "', $special_rank, $min_posts, '" . $db->sql_escape($rank_image) . "', $rank_show_title)";

			$message = $lang['Rank_added'];
		}
		$result = $db->sql_query($sql);

		$cache->destroy('_ranks');
		$db->clear_cache('ranks_');
		$message .= '<br /><br />' . sprintf($lang['Click_return_rankadmin'], '<a href="' . append_sid('admin_ranks.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);

	}
	elseif($mode == 'delete')
	{
		// Ok, they want to delete their rank
		$rank_id = request_var('id', 0);

		$confirm = isset($_POST['confirm']);

		if($rank_id && $confirm)
		{
			$sql = "DELETE FROM " . RANKS_TABLE . "
				WHERE rank_id = $rank_id";
			$result = $db->sql_query($sql);

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_rank = 0
				WHERE user_rank = $rank_id";
			$result = $db->sql_query($sql);

			$sql = "UPDATE " . GROUPS_TABLE . "
				SET group_rank = 0
				WHERE group_rank = $rank_id";
			$result = $db->sql_query($sql);

			$message = $lang['Rank_removed'] . '<br /><br />' . sprintf($lang['Click_return_rankadmin'], '<a href="' . append_sid('admin_ranks.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			$cache->destroy('_ranks');
			$db->clear_cache('ranks_');
			message_die(GENERAL_MESSAGE, $message);
		}
		elseif($rank_id && !$confirm)
		{
			// Present the confirmation screen to the user
			$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));

			$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="id" value="' . $rank_id . '" />';

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_rank'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid('admin_ranks.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields
				)
			);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
		}
	}

	$template->pparse('body');

	include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
}

// Show the default page
$template->set_filenames(array('body' => ADM_TPL . 'ranks_list_body.tpl'));

$sql = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_min ASC, rank_special ASC";
$result = $db->sql_query($sql);
$rank_count = $db->sql_numrows($result);
$rank_rows = $db->sql_fetchrowset($result);

$template->assign_vars(array(
	'L_RANKS_TITLE' => $lang['Ranks_title'],
	'L_RANKS_TEXT' => $lang['Ranks_explain'],
	'L_RANK' => $lang['Rank_title'],
	'L_RANK_MINIMUM' => $lang['Rank_minimum'],
	'L_SPECIAL_RANK' => $lang['Rank_special'],
	'L_EDIT' => $lang['Edit'],
	'L_DELETE' => $lang['Delete'],
	'L_ADD_RANK' => $lang['Add_new_rank'],
	'L_ACTION' => $lang['Action'],

	'S_RANKS_ACTION' => append_sid('admin_ranks.' . PHP_EXT)
	)
);

for($i = 0; $i < $rank_count; $i++)
{
	$rank = $rank_rows[$i]['rank_title'];
	$special_rank = $rank_rows[$i]['rank_special'];
	$rank_id = $rank_rows[$i]['rank_id'];
	$rank_min = $rank_rows[$i]['rank_min'];

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$rank_img_sp = (($rank_rows[$i]['rank_image'] != '') ? ('../' . $rank_rows[$i]['rank_image']) : $images['spacer']);
	$rank .= '<br /><img name="rank_image" src="' . $rank_img_sp . '" alt="" />';

	if(($special_rank > 0) || ($special_rank == '-2'))
	// Mighty Gorgon - Multiple Ranks - END
	{
		$rank_min = $rank_max = '-';
	}

	$row_class = ($i % 2) ? $theme['td_class2'] : $theme['td_class1'];

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$rank_is_special = ($special_rank > 0) ? $lang['Yes'] : $lang['No'];
	// Mighty Gorgon - Multiple Ranks - END

	$template->assign_block_vars('ranks', array(
		'ROW_CLASS' => $row_class,
		'RANK' => $rank,
		'SPECIAL_RANK' => $rank_is_special,
		'RANK_MIN' => $rank_min,

		'U_RANK_EDIT' => append_sid('admin_ranks.' . PHP_EXT . '?mode=edit&amp;id=' . $rank_id),
		'U_RANK_DELETE' => append_sid('admin_ranks.' . PHP_EXT . '?mode=delete&amp;id=' . $rank_id)
		)
	);

}

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>