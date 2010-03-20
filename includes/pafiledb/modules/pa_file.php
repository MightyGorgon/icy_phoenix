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

class pafiledb_file extends pafiledb_public
{
	function main($action)
	{
		global $db, $cache, $config, $images, $userdata, $lang, $bbcode, $pafiledb_config, $template, $pafiledb_functions;

		@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

		$cat_id = request_var('cat_id', 0);
		$file_id = request_var('file_id', 0);
		$action = request_var('action', '');

		if (!empty($file_id))
		{
			$file_id = $file_id;
		}
		elseif (($file_id == 0) && ($action != ''))
		{
			$file_id_array = array();
			$file_id_array = explode('=', $action);
			$file_id = $file_id_array[1];
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		// =======================================================
		// file id is not set, give him/her a nice error message
		// =======================================================

		$sql = "SELECT f.*, AVG(r.rate_point) AS rating, COUNT(r.votes_file) AS total_votes, u.user_id, u.username, u.user_active, u.user_color, COUNT(c.comments_id) as total_comments
			FROM " . PA_FILES_TABLE . " AS f
				LEFT JOIN " . PA_VOTES_TABLE . " AS r ON f.file_id = r.votes_file
				LEFT JOIN ". USERS_TABLE ." AS u ON f.user_id = u.user_id
				LEFT JOIN " . PA_COMMENTS_TABLE . " AS c ON f.file_id = c.file_id
			WHERE f.file_id = $file_id
			AND f.file_approved = 1
			GROUP BY f.file_id ";
		$result = $db->sql_query($sql);

		//===================================================
		// file doesn't exist'
		//===================================================
		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}
		$db->sql_freeresult($result);

		//===================================================
		// Pafiledb auth for viewing file
		//===================================================

		if((!$this->auth[$file_data['file_catid']]['auth_view_file']))
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=file&file_id=' . $file_id, true));
			}

			$message = sprintf($lang['Sorry_auth_view'], $this->auth[$file_data['file_catid']]['auth_view_file_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		$this->generate_category_nav($file_data['file_catid']);

		$template->assign_vars(array(
			'L_INDEX' => sprintf($lang['Forum_Index'], htmlspecialchars($config['sitename'])),
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),

			'U_INDEX' => append_sid(CMS_PAGE_HOME),
			'U_DOWNLOAD_HOME' => append_sid('dload.' . PHP_EXT),

			'FILE_NAME' => $file_data['file_name'],
			'DOWNLOAD' => $pafiledb_config['settings_dbname']
			)
		);

		//===================================================
		// Prepare file info to display them
		//===================================================

		$file_time = create_date_ip($config['default_dateformat'], $file_data['file_time'], $config['board_timezone']);

		$file_last_download = ($file_data['file_last']) ? create_date_ip($config['default_dateformat'], $file_data['file_last'], $config['board_timezone']) : $lang['never'];

		$file_update_time = ($file_data['file_update_time']) ? create_date_ip($config['default_dateformat'], $file_data['file_update_time'], $config['board_timezone']) : $lang['never'];

		$file_author = trim($file_data['file_creator']);

		$file_version = trim($file_data['file_version']);

		$file_screenshot_url = trim($file_data['file_ssurl']);

		$file_website_url = trim($file_data['file_docsurl']);

		//$file_rating = ($file_data['rating'] != 0) ? round($file_data['rating'], 2) . ' / 10' : $lang['Not_rated'];
		//$file_rating2 = ($file_data['rating'] != 0) ? sprintf("%.1f", round(($file_data['rating']), 2)/2) : '0.0';
		$file_rating2 = ($file_data['rating'] != 0) ? sprintf("%.1f", round(($file_data['rating']), 0)/2) : '0.0';
		$file_download_link = ($file_data['file_license'] > 0) ? append_sid('dload.' . PHP_EXT . '?action=license&amp;license_id=' . $file_data['file_license'] . '&amp;file_id=' . $file_id) : append_sid('dload.' . PHP_EXT . '?action=download&amp;file_id=' . $file_id);

		$file_size = $pafiledb_functions->get_file_size($file_id, $file_data);
		/*
		$file_poster = ($file_data['user_id'] != ANONYMOUS) ? '<a href="' . append_sid(CMS_PAGE_PROFILE.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $file_data['user_id']) . '">' : '';
		$file_poster .= ($file_data['user_id'] != ANONYMOUS) ? $file_data['username'] : $lang['Guest'];
		$file_poster .= ($file_data['user_id'] != ANONYMOUS) ? '</a>' : '';
		*/
		$file_poster = ($file_data['user_id'] == ANONYMOUS) ? $lang['Guest'] : colorize_username($file_data['user_id'], $file_data['username'], $file_data['user_color'], $file_data['user_active']);

		$bbcode->allow_html = ($config['allow_html'] ? true : false);
		$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
		$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);
		$file_long_desc = $bbcode->parse($file_data['file_longdesc']);

		$template->assign_vars(array(
			'L_CLICK_HERE' => $lang['Click_here'],
			'L_AUTHOR' => $lang['Creator'],
			'L_VERSION' => $lang['Version'],
			'L_SCREENSHOT' => $lang['Scrsht'],
			'L_WEBSITE' => $lang['Docs'],
			'L_FILE' => $lang['File'],
// MX Addon
			'L_EDIT' => $lang['Editfile'],
			'L_DELETE' => $lang['Deletefile'],

		 	'L_DESC' => $lang['Desc'],
			'L_VOTES' => $lang['Votes'],
			'L_DATE' => $lang['Date'],
			'L_UPDATE_TIME' => $lang['Update_time'],
			'L_LASTTDL' => $lang['Lastdl'],
			'L_DLS' => $lang['Dls'],
			'L_RATING' => $lang['DlRating'],
			'L_SIZE' => $lang['File_size'],
			'L_DOWNLOAD' => $lang['Downloadfile'],
			'L_RATE' => $lang['Rate'],
			'L_EMAIL' => $lang['Emailfile'],
			'L_SUBMITED_BY' => $lang['Submiter'],

			'SHOW_AUTHOR' => (!empty($file_author)) ? true : false,
			'SHOW_VERSION' => (!empty($file_version)) ? true : false,
			'SHOW_SCREENSHOT' => (!empty($file_screenshot_url)) ? true : false,
			'SHOW_WEBSITE' => (!empty($file_website_url)) ? true : false,
			'SS_AS_LINK' => ($file_data['file_sshot_link']) ? true : false,
			'FILE_NAME' => $file_data['file_name'],
			//'FILE_LONGDESC' => nl2br($file_data['file_longdesc']),
			'FILE_LONGDESC' => $file_long_desc,
			'FILE_SUBMITED_BY' => $file_poster,
			'FILE_AUTHOR' => $file_author,
			'FILE_VERSION' => $file_version,
			'FILE_SCREENSHOT' => $file_screenshot_url,
			'FILE_WEBSITE' => $file_website_url,
// MX Addon
			'AUTH_EDIT' => (($this->auth[$file_data['file_catid']]['auth_edit_file'] && $file_data['user_id'] == $userdata['user_id']) || $this->auth[$file_data['file_catid']]['auth_mod']) ? true : false,
			'AUTH_DELETE' => (($this->auth[$file_data['file_catid']]['auth_delete_file'] && $file_data['user_id'] == $userdata['user_id']) || $this->auth[$file_data['file_catid']]['auth_mod']) ? true : false,

			'AUTH_DOWNLOAD' => ($this->auth[$file_data['file_catid']]['auth_download']) ? true : false,
			'AUTH_RATE' => ($this->auth[$file_data['file_catid']]['auth_rate']) ? true : false,
			'AUTH_EMAIL' => ($this->auth[$file_data['file_catid']]['auth_email']) ? true : false,
			'INCLUDE_COMMENTS' => ($this->auth[$file_data['file_catid']]['auth_view_comment']) ? true : false,
// MX Addon
			'DELETE_IMG' => $images['icon_delpost'],
			'EDIT_IMG' => $images['icon_edit'],

			'DOWNLOAD_IMG' => $images['pa_download'],
			'RATE_IMG' => $images['pa_rate'],
			'EMAIL_IMG' => $images['pa_email'],
			'FILE_VOTES' => $file_data['total_votes'],
			'TIME' => $file_time,
			'UPDATE_TIME' => ($file_data['file_update_time'] != $file_data['file_time']) ? $file_update_time : $lang['never'],
			'RATING' => $file_rating2,
			'FILE_DLS' => intval($file_data['file_dls']),
			'FILE_SIZE' => $file_size,
			'LAST' => $file_last_download,

// MX Addon
			'U_DELETE' => append_sid('dload.' . PHP_EXT . '?action=user_upload&amp;do=delete&amp;file_id=' . $file_id),
			'U_EDIT' => append_sid('dload.' . PHP_EXT . '?action=user_upload&amp;file_id=' . $file_id),

			'U_DOWNLOAD' => $file_download_link,
			'U_RATE' => append_sid('dload.' . PHP_EXT . '?action=rate&amp;file_id=' . $file_id),
			'U_EMAIL' => append_sid('dload.' . PHP_EXT . '?action=email&amp;file_id=' . $file_id)
			)
		);

		$custom_fields = new custom_fields();
		$custom_fields->custom_table = PA_CUSTOM_TABLE;
		$custom_fields->custom_data_table = PA_CUSTOM_DATA_TABLE;
		$custom_fields->init();
		$custom_fields->display_data($file_id);


		if($this->auth[$file_data['file_catid']]['auth_view_comment'])
		{
			include(IP_ROOT_PATH . PA_FILE_DB_PATH . 'functions_comment.' . PHP_EXT);
			display_comments($file_data);
		}
		$this->display($lang['Download'], 'pa_file_body.tpl');
	}
}

?>