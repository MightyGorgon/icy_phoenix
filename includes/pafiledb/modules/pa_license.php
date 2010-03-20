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

class pafiledb_license extends pafiledb_public
{
	function main($action)
	{
		global $template, $lang, $config, $pafiledb_config, $db, $images, $userdata;

		$license_id = request_var('license_id', 0);
		if (empty($license_id))
		{
			message_die(GENERAL_MESSAGE, $lang['License_not_exist']);
		}

		$file_id = request_var('file_id', 0);
		if (empty($file_id))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$sql = 'SELECT file_catid, file_name
			FROM ' . PA_FILES_TABLE . "
			WHERE file_id = $file_id";
		$result = $db->sql_query($sql);

		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$db->sql_freeresult($result);

		if((!$this->auth[$file_data['file_catid']]['auth_download']))
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=license&license_id=' . $license_id . '&amp;file_id=' . $file_id, true));
			}

			$message = sprintf($lang['Sorry_auth_download'], $this->auth[$file_data['file_catid']]['auth_download_type']);
			message_die(GENERAL_MESSAGE, $message);
		}


		$sql = 'SELECT *
			FROM ' . PA_LICENSE_TABLE . "
			WHERE license_id = $license_id";
		$result = $db->sql_query($sql);

		if(!$license = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['License_not_exist']);
		}

		$db->sql_freeresult($result);

		$this->generate_category_nav($file_data['file_catid']);

		$template->assign_vars(array(
			'L_INDEX' => sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename'])),
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),

			'L_HOME' => $lang['Home'],
			'L_LICENSE' => $lang['License'],
			'L_LEWARN' => $lang['Licensewarn'],
			'L_AGREE' => $lang['Iagree'],
			'L_NOT_AGREE' => $lang['Dontagree'],

			'U_INDEX' => append_sid(CMS_PAGE_HOME),
			'U_DOWNLOAD_HOME' => append_sid('dload.' . PHP_EXT),
			'U_FILE_NAME' => append_sid('dload.' . PHP_EXT . '?action=file&amp;file_id=' . $file_id),
			'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT . '?action=download&amp;file_id=' . $file_id),

			'L_PREVIEW' => $lang['Preview'],
			'LE_NAME' => $license['license_name'],
			'FILE_NAME' => $file_data['file_name'],
			'LE_TEXT' => nl2br($license['license_text']),
			'DOWNLOAD' => $pafiledb_config['settings_dbname']
			)
		);

		$this->display($lang['Download'], 'pa_license_body.tpl');
	}
}

?>