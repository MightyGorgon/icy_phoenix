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
define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1100_General']['170_Smilies'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$cancel = isset($_POST['cancel']) ? true : false;
$no_page_header = $cancel;
// Load default header
if (!empty($_GET['export_pack']) && $_GET['export_pack'] == 'send')
{
	$no_page_header = true;
}
require('./pagestart.' . PHP_EXT);
if ($cancel)
{
	redirect(ADM . '/' . append_sid('admin_smilies.' . PHP_EXT, true));
}

// Check to see what mode we should operate in.
if(isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "";
}

$delimeter  = '=+:';

// Read a listing of uploaded smilies for use in the add or edit smliey code...
$dir = @opendir(IP_ROOT_PATH . $board_config['smilies_path']);

while($file = @readdir($dir))
{
	if(!@is_dir(phpbb_realpath(IP_ROOT_PATH . $board_config['smilies_path'] . '/' . $file)))
	{
		$img_size = @getimagesize(IP_ROOT_PATH . $board_config['smilies_path'] . '/' . $file);

		if($img_size[0] && $img_size[1])
		{
			$smiley_images[] = $file;
		}
		else if(eregi('.pak$', $file))
		{
			$smiley_paks[] = $file;
		}
	}
}

@closedir($dir);

// Select main mode
if(isset($_GET['import_pack']) || isset($_POST['import_pack']))
{
	// Import a list a "Smiley Pack"
	$smile_pak = (isset($_POST['smile_pak'])) ? $_POST['smile_pak'] : $_GET['smile_pak'];
	$clear_current = (isset($_POST['clear_current'])) ? $_POST['clear_current'] : $_GET['clear_current'];
	$replace_existing = (isset($_POST['replace'])) ? $_POST['replace'] : $_GET['replace'];

	if (!empty($smile_pak))
	{
		// The user has already selected a smile_pak file.. Import it.
		if(!empty($clear_current) )
		{
			$sql = "DELETE
				FROM " . SMILIES_TABLE;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't delete current smilies", "", __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "SELECT code
				FROM ". SMILIES_TABLE;
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't get current smilies", "", __LINE__, __FILE__, $sql);
			}

			$cur_smilies = $db->sql_fetchrowset($result);

			for($i = 0; $i < count($cur_smilies); $i++)
			{
				$k = $cur_smilies[$i]['code'];
				$smiles[$k] = 1;
			}
		}

		$fcontents = @file(IP_ROOT_PATH . $board_config['smilies_path'] . '/'. $smile_pak);

		if(empty($fcontents))
		{
			message_die(GENERAL_ERROR, "Couldn't read smiley pak file", "", __LINE__, __FILE__, $sql);
		}

//Smilies Order Start
		if($board_config['smilies_insert'] == TOP_LIST)
		{
			$sql = "SELECT MIN(smilies_order) AS smilies_extreme
				FROM " . SMILIES_TABLE;
			$shift_it = -10;
		}
		else
		{
			$sql = "SELECT MAX(smilies_order) AS smilies_extreme
				FROM " . SMILIES_TABLE;
			$shift_it = 10;
		}

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't get extreme values from the smilies table", "", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		$order_extreme = $row['smilies_extreme'] + $shift_it;
//Smilies Order End
		for($i = 0; $i < count($fcontents); $i++)
		{
			$smile_data = explode($delimeter, trim(addslashes($fcontents[$i])));

			for($j = 2; $j < count($smile_data); $j++)
			{
				// Replace > and < with the proper html_entities for matching.
				$smile_data[$j] = str_replace("<", "&lt;", $smile_data[$j]);
				$smile_data[$j] = str_replace(">", "&gt;", $smile_data[$j]);
				$k = $smile_data[$j];

				if($smiles[$k] == 1)
				{
					if(!empty($replace_existing))
					{
						$sql = "UPDATE " . SMILIES_TABLE . "
							SET smile_url = '" . str_replace("\'", "''", $smile_data[0]) . "', emoticon = '" . str_replace("\'", "''", $smile_data[1]) . "'
							WHERE code = '" . str_replace("\'", "''", $smile_data[$j]) . "'";
					}
					else
					{
						$sql = '';
					}
				}
				else
				{
					// Smilies Order in Line add
					// , smilies_order
					// , $order_extreme
					$sql = "INSERT INTO " . SMILIES_TABLE . " (code, smile_url, emoticon, smilies_order)
						VALUES('" . str_replace("\'", "''", $smile_data[$j]) . "', '" . str_replace("\'", "''", $smile_data[0]) . "', '" . str_replace("\'", "''", $smile_data[1]) . "', $order_extreme)";
					// Smilies Order add
					$order_extreme = $order_extreme + $shift_it;
				}

				if($sql != '')
				{
					$result = $db->sql_query($sql);
					if(!$result)
					{
						message_die(GENERAL_ERROR, "Couldn't update smilies!", "", __LINE__, __FILE__, $sql);
					}
				}
			}
		}

		$message = $lang['smiley_import_success'] . '<br /><br />' . sprintf($lang['Click_return_smileadmin'], '<a href="' . append_sid("admin_smilies." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);

	}
	else
	{
		// Display the script to get the smile_pak cfg file...
		$smile_paks_select = "<select name='smile_pak'><option value=''>" . $lang['Select_pak'] . "</option>";
		while(list($key, $value) = @each($smiley_paks))
		{
			if (!empty($value))
			{
				$smile_paks_select .= "<option>" . $value . "</option>";
			}
		}
		$smile_paks_select .= "</select>";

		$hidden_vars = "<input type='hidden' name='mode' value='import'>";

		$template->set_filenames(array(
			'body' => ADM_TPL . 'smile_import_body.tpl')
		);

		$template->assign_vars(array(
			"L_SMILEY_TITLE" => $lang['smiley_title'],
			"L_SMILEY_EXPLAIN" => $lang['smiley_import_inst'],
			"L_SMILEY_IMPORT" => $lang['smiley_import'],
			"L_SELECT_LBL" => $lang['choose_smile_pak'],
			"L_IMPORT" => $lang['import'],
			"L_CONFLICTS" => $lang['smile_conflicts'],
			"L_DEL_EXISTING" => $lang['del_existing_smileys'],
			"L_REPLACE_EXISTING" => $lang['replace_existing'],
			"L_KEEP_EXISTING" => $lang['keep_existing'],

			"S_SMILEY_ACTION" => append_sid("admin_smilies." . PHP_EXT),
			"S_SMILE_SELECT" => $smile_paks_select,
			"S_HIDDEN_FIELDS" => $hidden_vars)
		);

		$template->pparse("body");
	}
}
else if(isset($_POST['export_pack']) || isset($_GET['export_pack']))
{
	// Export our smiley config as a smiley pak...
	if ($_GET['export_pack'] == "send")
	{
		// Smilies Order REPLACE
		//$sql = "SELECT *
		//	FROM " . SMILIES_TABLE;
		$sql = "SELECT *
			FROM " . SMILIES_TABLE ."
			ORDER BY smilies_order";

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not get smiley list", "", __LINE__, __FILE__, $sql);
		}

		$resultset = $db->sql_fetchrowset($result);

		$smile_pak = "";
		for($i = 0; $i < count($resultset); $i++)
		{
			$smile_pak .= $resultset[$i]['smile_url'] . $delimeter;
			$smile_pak .= $resultset[$i]['emoticon'] . $delimeter;
			$smile_pak .= $resultset[$i]['code'] . "\n";
		}

		header("Content-Type: text/x-delimtext; name=\"smiles.pak\"");
		header("Content-disposition: attachment; filename=smiles.pak");

		echo $smile_pak;

		exit;
	}

	$message = sprintf($lang['export_smiles'], '<a href="' . append_sid('admin_smilies.' . PHP_EXT . '?export_pack=send', true) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_smileadmin'], '<a href="' . append_sid('admin_smilies.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);

}
else if(isset($_POST['add']) || isset($_GET['add']))
{
	// Admin has selected to add a smiley.

	$template->set_filenames(array(
		'body' => ADM_TPL . 'smile_edit_body.tpl')
	);

	$filename_list = "";
	for($i = 0; $i < count($smiley_images); $i++)
	{
		$filename_list .= '<option value="' . $smiley_images[$i] . '">' . $smiley_images[$i] . '</option>';
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="savenew" />';

	$template->assign_vars(array(
		"L_SMILEY_TITLE" => $lang['smiley_title'],
		"L_SMILEY_CONFIG" => $lang['smiley_config'],
		"L_SMILEY_EXPLAIN" => $lang['smile_desc'],
		"L_SMILEY_CODE" => $lang['smiley_code'],
		"L_SMILEY_URL" => $lang['smiley_url'],
		"L_SMILEY_EMOTION" => $lang['smiley_emot'],
		"L_SUBMIT" => $lang['Submit'],
		"L_RESET" => $lang['Reset'],

		"SMILEY_IMG" => IP_ROOT_PATH . $board_config['smilies_path'] . '/' . $smiley_images[0],

		"S_SMILEY_ACTION" => append_sid("admin_smilies." . PHP_EXT),
		"S_HIDDEN_FIELDS" => $s_hidden_fields,
		"S_FILENAME_OPTIONS" => $filename_list,
		"S_SMILEY_BASEDIR" => IP_ROOT_PATH . $board_config['smilies_path'])
	);

	$template->pparse("body");
}
else if ($mode != "")
{
	switch($mode)
	{
		case 'delete':
			// Admin has selected to delete a smiley.

			$smiley_id = (!empty($_POST['id'])) ? $_POST['id'] : $_GET['id'];
			$smiley_id = intval($smiley_id);

			$confirm = isset($_POST['confirm']);

			if($confirm)
			{
				$sql = "DELETE FROM " . SMILIES_TABLE . "
					WHERE smilies_id = " . $smiley_id;
				$result = $db->sql_query($sql);
				if(!$result)
				{
					message_die(GENERAL_ERROR, "Couldn't delete smiley", "", __LINE__, __FILE__, $sql);
				}

				$message = $lang['smiley_del_success'] . '<br /><br />' . sprintf($lang['Click_return_smileadmin'], '<a href="' . append_sid("admin_smilies." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				// Present the confirmation screen to the user
				$template->set_filenames(array('body' => ADM_TPL . 'confirm_body.tpl'));

				$hidden_fields = '<input type="hidden" name="mode" value="delete" /><input type="hidden" name="id" value="' . $smiley_id . '" />';

				$template->assign_vars(array(
					'MESSAGE_TITLE' => $lang['Confirm'],
					'MESSAGE_TEXT' => $lang['Confirm_delete_smiley'],

					'L_YES' => $lang['Yes'],
					'L_NO' => $lang['No'],

					'S_CONFIRM_ACTION' => append_sid("admin_smilies." . PHP_EXT),
					'S_HIDDEN_FIELDS' => $hidden_fields)
				);
				$template->pparse('body');
			}
			break;

		case 'edit':
			// Admin has selected to edit a smiley.

			$smiley_id = (!empty($_POST['id'])) ? $_POST['id'] : $_GET['id'];
			$smiley_id = intval($smiley_id);

			$sql = "SELECT *
				FROM " . SMILIES_TABLE . "
				WHERE smilies_id = " . $smiley_id;
			$result = $db->sql_query($sql);
			if(!$result)
			{
				message_die(GENERAL_ERROR, 'Could not obtain emoticon information', "", __LINE__, __FILE__, $sql);
			}
			$smile_data = $db->sql_fetchrow($result);

			$filename_list = "";
			for($i = 0; $i < count($smiley_images); $i++)
			{
				if($smiley_images[$i] == $smile_data['smile_url'])
				{
					$smiley_selected = "selected=\"selected\"";
					$smiley_edit_img = $smiley_images[$i];
				}
				else
				{
					$smiley_selected = "";
				}

				$filename_list .= '<option value="' . $smiley_images[$i] . '"' . $smiley_selected . '>' . $smiley_images[$i] . '</option>';
			}

			$template->set_filenames(array(
				'body' => ADM_TPL . 'smile_edit_body.tpl')
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="smile_id" value="' . $smile_data['smilies_id'] . '" />';

			$template->assign_vars(array(
				"SMILEY_CODE" => $smile_data['code'],
				"SMILEY_EMOTICON" => $smile_data['emoticon'],

				"L_SMILEY_TITLE" => $lang['smiley_title'],
				"L_SMILEY_CONFIG" => $lang['smiley_config'],
				"L_SMILEY_EXPLAIN" => $lang['smile_desc'],
				"L_SMILEY_CODE" => $lang['smiley_code'],
				"L_SMILEY_URL" => $lang['smiley_url'],
				"L_SMILEY_EMOTION" => $lang['smiley_emot'],
				"L_SUBMIT" => $lang['Submit'],
				"L_RESET" => $lang['Reset'],

				"SMILEY_IMG" => IP_ROOT_PATH . $board_config['smilies_path'] . '/' . $smiley_edit_img,

				"S_SMILEY_ACTION" => append_sid("admin_smilies." . PHP_EXT),
				"S_HIDDEN_FIELDS" => $s_hidden_fields,
				"S_FILENAME_OPTIONS" => $filename_list,
				"S_SMILEY_BASEDIR" => IP_ROOT_PATH . $board_config['smilies_path'])
			);

			$template->pparse("body");
			break;

		case "save":
			// Admin has submitted changes while editing a smiley.

			//
			// Get the submitted data, being careful to ensure that we only
			// accept the data we are looking for.
			//
			$smile_code = (isset($_POST['smile_code'])) ? trim($_POST['smile_code']) : '';
			$smile_url = (isset($_POST['smile_url'])) ? trim($_POST['smile_url']) : '';
			$smile_url = phpbb_ltrim(basename($smile_url), "'");
			$smile_emotion = (isset($_POST['smile_emotion'])) ? htmlspecialchars(trim($_POST['smile_emotion'])) : '';
			$smile_id = (isset($_POST['smile_id'])) ? intval($_POST['smile_id']) : 0;
			$smile_code = trim($smile_code);
			$smile_url = trim($smile_url);

			// If no code was entered complain ...
			if ($smile_code == '' || $smile_url == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
			}

			// Convert < and > to proper htmlentities for parsing.
			$smile_code = str_replace('<', '&lt;', $smile_code);
			$smile_code = str_replace('>', '&gt;', $smile_code);

			// Proceed with updating the smiley table.
			$sql = "UPDATE " . SMILIES_TABLE . "
				SET code = '" . str_replace("\'", "''", $smile_code) . "', smile_url = '" . str_replace("\'", "''", $smile_url) . "', emoticon = '" . str_replace("\'", "''", $smile_emotion) . "'
				WHERE smilies_id = $smile_id";
			if(!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Couldn't update smilies info", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['smiley_edit_success'] . '<br /><br />' . sprintf($lang['Click_return_smileadmin'], '<a href="' . append_sid("admin_smilies." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;

		case "savenew":
			// Admin has submitted changes while adding a new smiley.

			//
			// Get the submitted data being careful to ensure the the data
			// we recieve and process is only the data we are looking for.
			//
			$smile_code = (isset($_POST['smile_code'])) ? $_POST['smile_code'] : '';
			$smile_url = (isset($_POST['smile_url'])) ? $_POST['smile_url'] : '';
			$smile_url = phpbb_ltrim(basename($smile_url), "'");
			$smile_emotion = (isset($_POST['smile_emotion'])) ? htmlspecialchars(trim($_POST['smile_emotion'])) : '';
			$smile_code = trim($smile_code);
			$smile_url = trim($smile_url);
			// If no code was entered complain ...
			if ($smile_code == '' || $smile_url == '')
			{
				message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
			}

			// Convert < and > to proper htmlentities for parsing.
			$smile_code = str_replace('<', '&lt;', $smile_code);
			$smile_code = str_replace('>', '&gt;', $smile_code);

			// Save the data to the smiley table.
			// Smilies Order Begin
			if($board_config['smilies_insert'] == TOP_LIST)
			{
				$sql = "SELECT MIN(smilies_order) AS smilies_extreme
					FROM " . SMILIES_TABLE;
				$shift_it = -10;
			}
			else
			{
				$sql = "SELECT MAX(smilies_order) AS smilies_extreme
					FROM " . SMILIES_TABLE;
				$shift_it = 10;
			}

			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't get extreme values from the smilies table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$order_extreme = $row['smilies_extreme'] + $shift_it;
			// Smilies Order END
			// Smilies Order in Line ADD
			// , smilies_order
			// , $order_extreme
			$sql = "INSERT INTO " . SMILIES_TABLE . " (code, smile_url, emoticon, smilies_order)
				VALUES ('" . str_replace("\'", "''", $smile_code) . "', '" . str_replace("\'", "''", $smile_url) . "', '" . str_replace("\'", "''", $smile_emotion) . "', $order_extreme)";
			$result = $db->sql_query($sql);
			if(!$result)
			{
				message_die(GENERAL_ERROR, "Couldn't insert new smiley", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['smiley_add_success'] . '<br /><br />' . sprintf($lang['Click_return_smileadmin'], '<a href="' . append_sid("admin_smilies." . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
			break;
	}
}
else
{
	// Smilies Order BEGIN
	if($_GET['option'] == 'select' && isset($_POST['insert_position']))
	{
		$sql = "UPDATE " . CONFIG_TABLE . " SET
			config_value = '" . $_POST['insert_position'] . "'
			WHERE config_name = 'smilies_insert'";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Failed to update general configuration for smilies_insert", "", __LINE__, __FILE__, $sql);
		}
		$board_config['smilies_insert'] = $_POST['insert_position'];
	}

	if($board_config['smilies_insert'] == TOP_LIST)
	{
		$pos_top_checked = ' selected="selected"';
		$pos_bot_checked = '';
	}
	else
	{
		$pos_top_checked = '';
		$pos_bot_checked = ' selected="selected"';
	}
	$position_select = '<select name="insert_position"><option value="' . TOP_LIST . '"' . $pos_top_checked . '>' . $lang['before'] . '</option><option value="' . BOTTOM_LIST . '"' . $pos_bot_checked . '>' . $lang['after'] . '</option></select>';


	if(isset($_GET['move']) && isset($_GET['id']))
	{
		$moveit = ($_GET['move'] == 'up') ? -15 : 15;
		$sql = "UPDATE " . SMILIES_TABLE . "
			SET smilies_order = smilies_order + $moveit
			WHERE smilies_id = " . $_GET['id'];
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't change smilies order", "", __LINE__, __FILE__, $sql);
		}

		$i = 10;
		$inc = 10;

		$sql = "SELECT *
			FROM " . SMILIES_TABLE . "
			ORDER BY smilies_order";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't query smilies order", "", __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['smilies_order'] != $i)
			{
				$sql = "UPDATE " . SMILIES_TABLE . "
					SET smilies_order = $i
					WHERE smilies_id = " . $row['smilies_id'];
				if(!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
				}
			}
			$i += $inc;
		}

	}
	else if(isset($_GET['send']) && isset($_GET['id']))
	{
		if($_GET['send'] == 'top')
		{
			$sql = "SELECT MIN(smilies_order) AS smilies_extreme
				FROM " . SMILIES_TABLE;
			$shift_it = -10;
		}
		else
		{
			$sql = "SELECT MAX(smilies_order) AS smilies_extreme
				FROM " . SMILIES_TABLE;
			$shift_it = 10;
		}

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't get extreme values from the smilies table", "", __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		$order_extreme = $row['smilies_extreme'] + $shift_it;

		$sql = "UPDATE " . SMILIES_TABLE . "
			SET smilies_order = $order_extreme
			WHERE smilies_id = " . $_GET['id'];
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't change smilies order", "", __LINE__, __FILE__, $sql);
		}
	}
	// Smilies Order END

	// This is the main display of the page before the admin has selected any options.

	// Smilies Order REPLACE
	// $sql = "SELECT *
	// 	FROM " . SMILIES_TABLE;
	$sql = "SELECT *
		FROM " . SMILIES_TABLE . "
		ORDER BY smilies_order";
	$result = $db->sql_query($sql);
	if(!$result)
	{
		message_die(GENERAL_ERROR, "Couldn't obtain smileys from database", "", __LINE__, __FILE__, $sql);
	}

	$smilies = $db->sql_fetchrowset($result);

	$template->set_filenames(array('body' => ADM_TPL . 'smile_list_body.tpl'));

	$template->assign_vars(array(
		'L_ACTION' => $lang['Action'],
		'L_SMILEY_TITLE' => $lang['smiley_title'],
		'L_SMILEY_TEXT' => $lang['smile_desc'],
		'L_DELETE' => $lang['Delete'],
		'L_EDIT' => $lang['Edit'],
		'L_SMILEY_ADD' => $lang['smile_add'],
		'L_CODE' => $lang['Code'],
		'L_EMOT' => $lang['Emotion'],
		'L_SMILE' => $lang['Smile'],
		'L_IMPORT_PACK' => $lang['import_smile_pack'],
		'L_EXPORT_PACK' => $lang['export_smile_pack'],
		// Smilies ORDER BEGIN
		'L_MOVE' => $lang['Move'],
		'L_MOVE_UP' => $lang['Move_up'],
		'L_MOVE_DOWN' => $lang['Move_down'],
		'L_MOVE_TOP' => $lang['Move_top'],
		'L_MOVE_END' => $lang['Move_end'],
		'L_POSITION_NEW_SMILIES' => $lang['position_new_smilies'],
		'L_SMILEY_CHANGE_POSITION' => $lang['smiley_change_position'],
		'L_SMILEY_CONFIG' => $lang['smiley_config'],

		'POSITION_SELECT' => $position_select,
		'S_POSITION_ACTION' => append_sid('admin_smilies.' . PHP_EXT . '?option=select'),
		// Smilies ORDER END

		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_SMILEY_ACTION' => append_sid('admin_smilies.' . PHP_EXT)
		)
	);

	// Loop throuh the rows of smilies setting block vars for the template.
	for($i = 0; $i < count($smilies); $i++)
	{
		// Replace htmlentites for < and > with actual character.
		$smilies[$i]['code'] = str_replace('&lt;', '<', $smilies[$i]['code']);
		$smilies[$i]['code'] = str_replace('&gt;', '>', $smilies[$i]['code']);

		$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('smiles', array(
			'ROW_COLOR' => '#' . $row_color,
			'ROW_CLASS' => $row_class,

			'SMILEY_IMG' =>  IP_ROOT_PATH . $board_config['smilies_path'] . '/' . $smilies[$i]['smile_url'],
			'CODE' => $smilies[$i]['code'],
			'EMOT' => $smilies[$i]['emoticon'],

			// Smilies ORDER BEGIN
			'U_SMILEY_MOVE_UP' => append_sid('admin_smilies.' . PHP_EXT . '?move=up&amp;id=' . $smilies[$i]['smilies_id']),
			'U_SMILEY_MOVE_DOWN' => append_sid('admin_smilies.' . PHP_EXT . '?move=down&amp;id=' . $smilies[$i]['smilies_id']),
			'U_SMILEY_MOVE_TOP' => append_sid('admin_smilies.' . PHP_EXT . '?send=top&amp;id=' . $smilies[$i]['smilies_id']),
			'U_SMILEY_MOVE_END' => append_sid('admin_smilies.' . PHP_EXT . '?send=end&amp;id=' . $smilies[$i]['smilies_id']),
			// Smilies ORDER END
			'U_SMILEY_EDIT' => append_sid('admin_smilies.' . PHP_EXT . '?mode=edit&amp;id=' . $smilies[$i]['smilies_id']),
			'U_SMILEY_DELETE' => append_sid('admin_smilies.' . PHP_EXT . '?mode=delete&amp;id=' . $smilies[$i]['smilies_id']))
		);
	}

	// Spit out the page.
	$template->pparse('body');
}

// Page Footer
include('./page_footer_admin.' . PHP_EXT);

?>