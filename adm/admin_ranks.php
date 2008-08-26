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

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);

if(!empty($setmodules))
{
	$file = basename(__FILE__);
	$module['1610_Users']['120_Ranks'] = $file;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
$cancel = isset($_POST['cancel']) ? true : false;
$no_page_header = $cancel;
require('./pagestart.' . $phpEx);
if ($cancel)
{
	redirect(ADM . '/' . append_sid('admin_ranks.' . $phpEx, true));
}

if(isset($_GET['mode']) || isset($_POST['mode']))
{
	$mode = (isset($_GET['mode'])) ? $_GET['mode'] : $_POST['mode'];
	$mode = htmlspecialchars($mode);
}
else
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
	else
	{
		$mode = '';
	}
}

// Restrict mode input to valid options
$mode = (in_array($mode, array('add', 'edit', 'save', 'delete'))) ? $mode : '';

if($mode != "")
{
	if($mode == "edit" || $mode == "add")
	{
		//
		// They want to add a new rank, show the form.
		//
		$rank_id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;

		$s_hidden_fields = "";

		if($mode == "edit")
		{
			if(empty($rank_id))
			{
				message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
			}

			$sql = "SELECT * FROM " . RANKS_TABLE . "
				WHERE rank_id = $rank_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain rank data", "", __LINE__, __FILE__, $sql);
			}

			$rank_info = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $rank_id . '" />';

		}
		else
		{
			$rank_info['rank_special'] = 0;
		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$rank_no_rank = ($rank_info['rank_special'] == '-2') ? "checked=\"checked\"" : "";
		$rank_day_counter = ($rank_info['rank_special'] == '-1') ? "checked=\"checked\"" : "";
		$rank_is_not_special = ($rank_info['rank_special'] == '0') ? "checked=\"checked\"" : "";
		$rank_is_special = ($rank_info['rank_special'] == '1') ? "checked=\"checked\"" : "";
		$rank_is_guest = ($rank_info['rank_special'] == '2') ? "checked=\"checked\"" : "";
		$rank_is_banned = ($rank_info['rank_special'] == '3') ? "checked=\"checked\"" : "";

		$rank_path = "../images/ranks/";
		if (is_dir($rank_path))
		{
			$dir = opendir($rank_path);
			$l = 0;
			while($file = readdir($dir))
			{
				if (strpos($file, '.gif') || strpos($file, '.png') || strpos($file, '.jpg'))
				{
					$file1[$l] = $file;
					$l++;
				}
			}
			closedir($dir);
			$ranks_list = '<select name="rank_image_sel" onchange="update_rank(this.options[selectedIndex].value);">';
			if ($rank_info['rank_image'] == '')
			{
				$ranks_list .= "<option value=\"\" selected=\"selected\">" . $lang['No_Rank_Image'] . "</option>";
			}
			else
			{
				$ranks_list .= "<option value=\"\">" . $lang['No_Rank_Image'] . "</option>";
				$ranks_list .= "<option value=\"" . $rank_info['rank_image'] . "\" selected=\"selected\">" . str_replace($rank_path, "", $rank_info['rank_image']) . "</option>";
			}
			for($k=0; $k<=$l;$k++)
			{
				if ($file1[$k] != "")
				{
					$ranks_list .= "<option value=\"images/ranks/" . $file1[$k] . "\">images/ranks/" . $file1[$k] . "</option>";
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

		$template->set_filenames(array(
			'body' => ADM_TPL . 'ranks_edit_body.tpl')
		);

		$template->assign_vars(array(
			"RANK" => $rank_info['rank_title'],
			// Mighty Gorgon - Multiple Ranks - BEGIN
			"NO_RANK" => $rank_no_rank,
			"DAYS_RANK" => $rank_day_counter,
			"NOT_SPECIAL_RANK" => $rank_is_not_special,
			"MINIMUM" => (($rank_info['rank_special'] == '0') || ($rank_info['rank_special'] == '-1')) ? $rank_info['rank_min'] : "",
			"SPECIAL_RANK" => $rank_is_special,
			"GUEST_RANK" => $rank_is_guest,
			"BANNED_RANK" => $rank_is_banned,
			"RANK_LIST" => $ranks_list,
			"RANK_IMG" => ($rank_info['rank_image'] != "") ? '../' . $rank_info['rank_image'] : $images['spacer'],

			"L_NO_RANK" => $lang['No_Rank'],
			"L_DAYS_RANK" => $lang['Rank_Days_Count'],
			"L_POSTS_RANK" => $lang['Rank_Posts_Count'],
			"L_MIN_M_D" => $lang['Rank_Min_Des'],
			"L_SPECIAL_RANK" => $lang['Rank_Special'],
			"L_GUEST" => $lang['Guest_User'],
			"L_BANNED" => $lang['Banned_User'],
			"L_CURRENT_RANK" => $lang['Current_Rank_Image'],
			// Mighty Gorgon - Multiple Ranks - END
			"IMAGE" => ($rank_info['rank_image'] != "") ? $rank_info['rank_image'] : "",
			"IMAGE_DISPLAY" => ($rank_info['rank_image'] != "") ? '<img src="../' . $rank_info['rank_image'] . '" />' : "",

			"L_RANKS_TITLE" => $lang['Ranks_title'],
			"L_RANKS_TEXT" => $lang['Ranks_explain'],
			"L_RANK_TITLE" => $lang['Rank_title'],
			"L_RANK_SPECIAL" => $lang['Rank_special'],
			"L_RANK_MINIMUM" => $lang['Rank_minimum'],
			"L_RANK_IMAGE" => $lang['Rank_image'],
			"L_RANK_IMAGE_EXPLAIN" => $lang['Rank_image_explain'],
			"L_SUBMIT" => $lang['Submit'],
			"L_RESET" => $lang['Reset'],
			"L_YES" => $lang['Yes'],
			"L_NO" => $lang['No'],

			"S_RANK_ACTION" => append_sid('admin_ranks.' . $phpEx),
			"S_HIDDEN_FIELDS" => $s_hidden_fields
			)
		);

	}
	elseif($mode == "save")
	{
		//
		// Ok, they sent us our info, let's update it.
		//

		$rank_id = (isset($_POST['id'])) ? intval($_POST['id']) : 0;
		$rank_title = (isset($_POST['title'])) ? trim($_POST['title']) : "";
		// Mighty Gorgon - Multiple Ranks - BEGIN
		$special_rank = $_POST['special_rank'];
		$min_posts = (isset($_POST['min_posts'])) ? intval($_POST['min_posts']) : -1;
		$rank_image = ((isset($_POST['rank_image_path']))) ? trim($_POST['rank_image_path']) : '';
		// Mighty Gorgon - Multiple Ranks - END

		if($rank_title == "")
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

		//
		// The rank image has to be a jpg, gif or png
		//
		if($rank_image != "")
		{
			if (!preg_match("/(\.gif|\.png|\.jpg)$/is", $rank_image))
			{
				$rank_image = "";
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

				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['No_update_ranks'], "", __LINE__, __FILE__, $sql);
				}
			}
			*/
			$sql = "UPDATE " . RANKS_TABLE . "
				SET rank_title = '" . str_replace("\'", "''", $rank_title) . "', rank_special = $special_rank, rank_min = $min_posts, rank_image = '" . str_replace("\'", "''", $rank_image) . "'
				WHERE rank_id = $rank_id";

			$message = $lang['Rank_updated'];
		}
		else
		{
			$sql = "INSERT INTO " . RANKS_TABLE . " (rank_title, rank_special, rank_min, rank_image)
				VALUES ('" . str_replace("\'", "''", $rank_title) . "', $special_rank, $min_posts, '" . str_replace("\'", "''", $rank_image) . "')";

			$message = $lang['Rank_added'];
		}

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't update/insert into ranks table", "", __LINE__, __FILE__, $sql);
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_rankadmin'], "<a href=\"" . append_sid("admin_ranks.$phpEx") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid('index.' . $phpEx . '?pane=right') . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);

	}
	elseif($mode == "delete")
	{
		//
		// Ok, they want to delete their rank
		//

		if(isset($_POST['id']) || isset($_GET['id']))
		{
			$rank_id = (isset($_POST['id'])) ? intval($_POST['id']) : intval($_GET['id']);
		}
		else
		{
			$rank_id = 0;
		}

		$confirm = isset($_POST['confirm']);

		if($rank_id && $confirm)
		{
			$sql = "DELETE FROM " . RANKS_TABLE . "
				WHERE rank_id = $rank_id";

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't delete rank data", "", __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_rank = 0
				WHERE user_rank = $rank_id";

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['No_update_ranks'], "", __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . GROUPS_TABLE . "
				SET group_rank = 0
				WHERE group_rank = $rank_id";

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['No_update_ranks'], "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Rank_removed'] . '<br /><br />' . sprintf($lang['Click_return_rankadmin'], "<a href=\"" . append_sid("admin_ranks.$phpEx") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid('index.' . $phpEx . '?pane=right') . "\">", "</a>");

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

				'S_CONFIRM_ACTION' => append_sid("admin_ranks.$phpEx"),
				'S_HIDDEN_FIELDS' => $hidden_fields)
			);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Must_select_rank']);
		}
	}

	$template->pparse('body');

	include('./page_footer_admin.' . $phpEx);
}

//
// Show the default page
//
$template->set_filenames(array(
	'body' => ADM_TPL . 'ranks_list_body.tpl')
);

$sql = "SELECT * FROM " . RANKS_TABLE . "
	ORDER BY rank_min ASC, rank_special ASC";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Couldn't obtain ranks data", "", __LINE__, __FILE__, $sql);
}
$rank_count = $db->sql_numrows($result);

$rank_rows = $db->sql_fetchrowset($result);

$template->assign_vars(array(
	"L_RANKS_TITLE" => $lang['Ranks_title'],
	"L_RANKS_TEXT" => $lang['Ranks_explain'],
	"L_RANK" => $lang['Rank_title'],
	"L_RANK_MINIMUM" => $lang['Rank_minimum'],
	"L_SPECIAL_RANK" => $lang['Rank_special'],
	"L_EDIT" => $lang['Edit'],
	"L_DELETE" => $lang['Delete'],
	"L_ADD_RANK" => $lang['Add_new_rank'],
	"L_ACTION" => $lang['Action'],

	"S_RANKS_ACTION" => append_sid("admin_ranks.$phpEx"))
);

for($i = 0; $i < $rank_count; $i++)
{
	$rank = $rank_rows[$i]['rank_title'];
	$special_rank = $rank_rows[$i]['rank_special'];
	$rank_id = $rank_rows[$i]['rank_id'];
	$rank_min = $rank_rows[$i]['rank_min'];

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$rank_img_sp = (($rank_rows[$i]['rank_image'] != "") ? ('../' . $rank_rows[$i]['rank_image']) : $images['spacer']);
	$rank .= '<br /><img name="rank_image" src="' . $rank_img_sp . '" alt="" />';

	if(($special_rank > 0) || ($special_rank == '-2'))
	// Mighty Gorgon - Multiple Ranks - END
	{
		$rank_min = $rank_max = "-";
	}

	$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	// Mighty Gorgon - Multiple Ranks - BEGIN
	$rank_is_special = ($special_rank > 0) ? $lang['Yes'] : $lang['No'];
	// Mighty Gorgon - Multiple Ranks - END

	$template->assign_block_vars("ranks", array(
		"ROW_COLOR" => "#" . $row_color,
		"ROW_CLASS" => $row_class,
		"RANK" => $rank,
		"SPECIAL_RANK" => $rank_is_special,
		"RANK_MIN" => $rank_min,

		"U_RANK_EDIT" => append_sid("admin_ranks.$phpEx?mode=edit&amp;id=$rank_id"),
		"U_RANK_DELETE" => append_sid("admin_ranks.$phpEx?mode=delete&amp;id=$rank_id"))
	);

}

$template->pparse('body');

include('./page_footer_admin.' . $phpEx);

?>