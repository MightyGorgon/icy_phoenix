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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

class pafiledb_rate extends pafiledb_public
{
	function main($action)
	{
		global $pafiledb_template, $lang, $board_config, $phpEx, $pafiledb_config, $db, $userdata, $phpbb_root_path, $pafiledb_functions, $pafiledb_user;


		if ( isset($_REQUEST['file_id']) )
		{
			$file_id = intval($_REQUEST['file_id']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$rating = ( isset($_POST['rating']) ) ? intval($_POST['rating']) : '';


		$sql = 'SELECT file_name, file_catid
			FROM ' . PA_FILES_TABLE . "
			WHERE file_id = $file_id";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query file info', '', __LINE__, __FILE__, $sql);
		}

		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$db->sql_freeresult($result);

		if( (!$this->auth[$file_data['file_catid']]['auth_rate']) )
		{
			if ( !$userdata['session_logged_in'] )
			{
				redirect(append_sid(LOGIN_MG . '?redirect=dload.' . $phpEx . '&action=rate&file_id=' . $file_id, true));
			}

			$message = sprintf($lang['Sorry_auth_rate'], $this->auth[$file_data['file_catid']]['auth_rate_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		$this->generate_category_nav($file_data['file_catid']);
		$pafiledb_template->assign_vars(array(
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			'L_RATE' => $lang['Rate'],
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),

			'U_INDEX' => append_sid(PORTAL_MG),
			'U_DOWNLOAD_HOME' => append_sid('dload.' . $phpEx),
			'U_FILE_NAME' => append_sid('dload.' . $phpEx . '?action=file&amp;file_id=' . $file_id),

			'FILE_NAME' => $file_data['file_name'],
			'DOWNLOAD' => $pafiledb_config['settings_dbname'])
		);

		if ( isset($_POST['submit']) )
		{
			$result_msg = str_replace("{filename}", $file_data['file_name'], $lang['Rconf']);

			$result_msg = str_replace("{rate}", $rating, $result_msg);

			if( ($rating <= 0) or ($rating > 10) )
			{
				message_die(GENERAL_ERROR, 'Bad submited value');
			}

			$pafiledb_user->update_voter_info($file_id, $rating);

			$rate_info = $pafiledb_functions->get_rating($file_id);

			$result_msg = str_replace("{newrating}", $rate_info, $result_msg);

			$message = $result_msg . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('dload.' . $phpEx . '?action=file&amp;file_id=' . $file_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid('index.' . $phpEx) . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);

		}
		else
		{
			$rate_info = str_replace("{filename}", $file_data['file_name'], $lang['Rateinfo']);

			$pafiledb_template->assign_vars(array(
				'S_RATE_ACTION' => append_sid('dload.' . $phpEx . '?action=rate&amp;file_id=' . $file_id),
				'L_RATE' => $lang['Rate'],
				'L_RERROR' => $lang['Rerror'],
				'L_R1' => $lang['R1'],
				'L_R2' => $lang['R2'],
				'L_R3' => $lang['R3'],
				'L_R4' => $lang['R4'],
				'L_R5' => $lang['R5'],
				'L_R6' => $lang['R6'],
				'L_R7' => $lang['R7'],
				'L_R8' => $lang['R8'],
				'L_R9' => $lang['R9'],
				'L_R10' => $lang['R10'],
				'RATEINFO' => $rate_info,
				'ID' => $file_id
				)
			);
		}
		$this->display($lang['Download'], 'pa_rate_body.tpl');
	}
}

?>