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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if ($action == 'save' && $submit)
{
	$approve = ( isset($_POST['approve']) ) ? intval($_POST['approve']) : 0;
	$description = ( isset($_POST['description']) ) ? trim($_POST['description']) : '';
	$file_traffic = ( isset($_POST['file_traffic']) ) ? intval($_POST['file_traffic']) : 0;
	$file_traffic_range = ( isset($_POST['file_traffic_range']) ) ? $_POST['file_traffic_range'] : 'KB';

	$long_desc = ( isset($_POST['long_desc']) ) ? trim($_POST['long_desc']) : '';
	$file_name = ( isset($_POST['file_name']) ) ? trim($_POST['file_name']) : '';
	$file_free = ( isset($_POST['file_free']) ) ? intval($_POST['file_free']) : 0;
	$file_extern = ( isset($_POST['file_extern']) ) ? intval($_POST['file_extern']) : 0;

	$test = ( isset($_POST['test']) ) ? trim($_POST['test']) : '';
	$require = ( isset($_POST['require']) ) ? trim($_POST['require']) : '';
	$todo = ( isset($_POST['todo']) ) ? trim($_POST['todo']) : '';
	$warning = ( isset($_POST['warning']) ) ? trim($_POST['warning']) : '';
	$mod_desc = ( isset($_POST['mod_desc']) ) ? trim($_POST['mod_desc']) : '';
	$mod_list = ( $_POST['mod_list'] == 1 ) ? 1 : 0;

	$send_notify = ( isset($_POST['send_notify']) ) ? intval($_POST['send_notify']) : 0;
	$change_time = (isset($_POST['change_time'])) ? intval($_POST['change_time']) : 0;
	$disable_popup_notify = (isset($_POST['disable_popup_notify'])) ? intval($_POST['disable_popup_notify']) : 0;
	$del_thumb = ( isset($_POST['del_thumb']) ) ? intval($_POST['del_thumb']) : 0;

	$mod_desc = make_clickable($mod_desc);
	$warning = make_clickable($warning);

	$html_on = $board_config['allow_html'];
	$bbcode_on = $board_config['allow_bbcode'];
	$smile_on = $board_config['allow_smilies'];

	$long_desc = prepare_message(trim($long_desc), $html_on, $bbcode_on, $smile_on);
	$mod_desc = prepare_message(trim($mod_desc), $html_on, $bbcode_on, $smile_on);
	$warning = prepare_message(trim($warning), $html_on, $bbcode_on, $smile_on);

	$hacklist = (isset($_POST['hacklist'])) ? intval($_POST['hacklist']) : 0;
	$hack_author = (isset($_POST['hack_author'])) ? trim($_POST['hack_author']) : "";
	$hack_author_email = (isset($_POST['hack_author_email'])) ? trim($_POST['hack_author_email']) : "";
	$hack_author_website = (isset($_POST['hack_author_website'])) ? trim($_POST['hack_author_website']) : "";
	$hack_version = (isset($_POST['hack_version'])) ? trim($_POST['hack_version']) : "";
	$hack_dl_url = (isset($_POST['hack_dl_url'])) ? trim($_POST['hack_dl_url']) : "";

	if ($file_traffic_range == 'KB')
	{
		$file_traffic = $file_traffic * 1024;
	}
	elseif ($file_traffic_range == 'MB')
	{
		$file_traffic = $file_traffic * 1048576;
	}
	elseif ($file_traffic_range == 'GB')
	{
		$file_traffic = $file_traffic * 1073741824;
	}

	$dl_file = array();
	$dl_file = $dl_mod->all_files(0, 0, 'ASC', 0, $df_id, 1);

	$file_name_old = $dl_file['file_name'];
	$file_size_old = $dl_file['file_size'];
	$file_cat_old = $dl_file['cat'];

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	if ( @$ini_val('open_basedir') != '' )
	{
		if ( @phpversion() < '4.0.3' )
		{
			message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file<br /><br />Please contact your server admin', '', __LINE__, __FILE__);
		}

		$move_file = 'move_uploaded_file';
	}
	else
	{
		$move_file = 'copy';
	}

	if ($dl_config['thumb_fsize'] && $index[$cat_id]['allow_thumbs'] && !$del_thumb)
	{
		$thumb_size = $HTTP_POST_FILES['thumb_name']['size'];
		$thumb_temp = $HTTP_POST_FILES['thumb_name']['tmp_name'];
		$thumb_name = $HTTP_POST_FILES['thumb_name']['name'];

		if ($HTTP_POST_FILES['thumb_name']['error'] && $thumb_name)
		{
			message_die(GENERAL_MESSAGE, $lang['DL_upload_error']);
		}

		if ($thumb_name)
		{
			$pic_size = @getimagesize($thumb_temp);
			$pic_width = $pic_size[0];
			$pic_height = $pic_size[1];

			if (!$pic_width || !$pic_height)
			{
				message_die(GENERAL_MESSAGE, $lang['DL_upload_error']);
			}

			if ($pic_width > $dl_config['thumb_xsize'] || $pic_height > $dl_config['thumb_ysize'] || (sprintf("%u", @filesize($thumb_temp) > $dl_config['thumb_fsize'])))
			{
				message_die(GENERAL_MESSAGE, $lang['Dl_thumb_to_big']);
			}
		}
	}

	if (!$file_extern)
	{
		$file_size = $HTTP_POST_FILES['dl_name']['size'];
		$file_temp = $HTTP_POST_FILES['dl_name']['tmp_name'];
		$file_name = $HTTP_POST_FILES['dl_name']['name'];

		$extention = str_replace('.', '', trim(strrchr(strtolower($file_name), '.')));
		$ext_blacklist = $dl_mod->get_ext_blacklist();
		if (in_array($extention, $ext_blacklist))
		{
			message_die(GENERAL_MESSAGE, $lang['Dl_forbidden_extention']);
		}

		if ($file_name)
		{
			if ($HTTP_POST_FILES['dl_name']['error'])
			{
				message_die(GENERAL_MESSAGE, $lang['DL_upload_error']);
			}

			$remain_traffic = $dl_config['overall_traffic'] - $dl_config['remain_traffic'];
			if(!$file_size || ($file_size > $remain_traffic && $dl_config['upload_traffic_count']))
			{
				message_die(GENERAL_MESSAGE, $lang['Dl_no_upload_traffic']);
			}

			$dl_path = $index[$cat_id]['cat_path'];

			@unlink($dl_config['dl_path'] . $dl_path . $file_name_old);

			$i = 0;
			while(@file_exists($dl_config['dl_path'] . $dl_path . $file_name));
			{
				$j = ($i == 0) ? '' : $i.'_';
				$file_name = $j . $file_name;
				$i++;
			}

			$move_file($file_temp, $dl_config['dl_path'] . $dl_path . $file_name);

			@chmod($dl_config['dl_path'] . $dl_path . $file_name, 0777);

			if ($index[$cat_id]['statistics'])
			{
				if ($index[$cat_id]['stats_prune'])
				{
					$stat_prune = $dl_mod->dl_prune_stats($cat_id, $index[$cat_id]['stats_prune']);
				}

				$browser = $dl_mod->dl_client();

				$sql = "INSERT INTO " . DL_STATS_TABLE . "
					(cat_id, id, user_id, username, traffic, direction, user_ip, browser, time_stamp) VALUES
					($new_cat, $df_id, " . $userdata['user_id'] . ", '" . str_replace("\'", "''", $userdata['username']) . "', " . $file_size . ", 2, '" . $userdata['session_ip'] . "', '" . str_replace("\'", "''", $browser) . "', " . time() . ")";
				if (!($db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update download statistics data', '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	else
	{
		$file_size = 0;
	}

	if (!$file_name)
	{
		$file_name = $file_name_old;
		$file_size = $file_size_old;
	}

	if($df_id && $new_cat)
	{
		$sql = "UPDATE " . DOWNLOADS_TABLE . " SET
			description = '" . str_replace("\'", "''", $description) . "',
			file_traffic = '" . str_replace("\'", "''", $file_traffic) . "',
			long_desc = '" . str_replace("\'", "''", $long_desc) . "',
			file_name = '" . str_replace("\'", "''", $file_name) . "',
			free = '" . str_replace("\'", "''", $file_free) . "',
			extern = '" . str_replace("\'", "''", $file_extern) . "',
			cat = '" . str_replace("\'", "''", $new_cat) . "',
			approve = '" . str_replace("\'", "''", $approve) . "',
			hacklist = '" . str_replace("\'", "''", $hacklist) . "',
			hack_author = '" . str_replace("\'", "''", $hack_author) . "',
			hack_author_email = '" . str_replace("\'", "''", $hack_author_email) . "',
			hack_author_website = '" . str_replace("\'", "''", $hack_author_website) . "',
			hack_version = '" . str_replace("\'", "''", $hack_version) . "',
			hack_dl_url = '" . str_replace("\'", "''", $hack_dl_url) . "',
			file_size = $file_size";

		if (!$change_time)
		{
			$sql .= ", change_time = " . time() . ", change_user = " . $userdata['user_id'];
		}

		if ($index[$cat_id]['allow_mod_desc'] || $userdata['user_level'] == ADMIN)
		{
			$sql .= ", test = '" . str_replace("\'", "''", $test) . "',
				req = '" . str_replace("\'", "''", $require) . "',
				todo = '" . str_replace("\'", "''", $todo) . "',
				warning = '" . str_replace("\'", "''", $warning) . "',
				mod_desc = '" . str_replace("\'", "''", $mod_desc) . "',
				mod_list = '" . str_replace("\'", "''", $mod_list) . "'";
		}

		$sql .= " WHERE id = $df_id";

		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Could not update download", "", __LINE__, __FILE__, $sql);
		}

		if ($approve)
		{
			$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_view');
			$email_template = 'downloads_change_notify';
		}
		else
		{
			$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_mod');
			$email_template = 'downloads_approve_notify';
		}

		$processing_user .= ($processing_user == '') ? 0 : '';

		$sql = "SELECT fav_user_id FROM " . DL_FAVORITES_TABLE . "
			WHERE fav_dl_id = " . (int) $df_id;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not fetch favorites for this download', '', __LINE__, __FILE__, $sql);
		}

		$fav_user = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$fav_user .= ($fav_user != '') ? ', ' . $row['fav_user_id'] : $row['fav_user_id'];
		}
		$db->sql_freeresult($result);

		$sql_fav_user = ($fav_user) ? ' AND user_id IN (' . $fav_user . ') ' : '';

		if (!$dl_config['disable_email'] && !$send_notify && $sql_fav_user)
		{
			$sql = "SELECT user_email, username, user_lang FROM " . USERS_TABLE . "
				WHERE user_allow_fav_download_email = 1
					$sql_fav_user
					AND user_id IN ($processing_user)";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not send notify email for new downloads', '', __LINE__, __FILE__, $sql);
			}

			$script_path = $board_config['script_path'];
			$server_name = trim($board_config['server_name']);
			$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
			$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

			$server_url = $server_name . $server_port . $script_path;
			$server_url = $server_protocol . str_replace('//', '/', $server_url);

			include(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);

			while ($row = $db->sql_fetchrow($result))
			{
				//
				// Let's do some checking to make sure that mass mail functions
				// are working in win32 versions of php.
				//

				if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
				{
					$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

					// We are running on windows, force delivery to use our smtp functions
					// since php's are broken by default
					$board_config['smtp_delivery'] = 1;
					$board_config['smtp_host'] = @$ini_val('SMTP');
				}

				$emailer = new emailer($board_config['smtp_delivery']);

				$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
				$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
				$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
				$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

				$emailer->use_template($email_template, $row['user_lang']);
				$emailer->email_address($row['user_email']);
				$emailer->from($board_config['board_email']);
				$emailer->replyto($board_config['board_email']);
				$emailer->extra_headers($email_headers);
				$emailer->set_subject();

				$emailer->assign_vars(array(
					'SITENAME' => $board_config['sitename'],
					'BOARD_EMAIL' => $board_config['board_email_sig'],
					'USERNAME' => $row['username'],
					'DOWNLOAD' => $description,
					'DESCRIPTION' => $long_desc,
					'CATEGORY' => str_replace("&nbsp;&nbsp;|___&nbsp;", '', $index[$cat_id]['cat_name']),
					'U_APPROVE' => $server_url.'downloads.' . PHP_EXT . '?view=modcp&action=approve',
					'U_CATEGORY' => $server_url.'downloads.' . PHP_EXT . '?cat=' . $cat_id
					)
				);

				$emailer->send();
				$emailer->reset();
			}
		}

		if (!$dl_config['disable_popup'] && !$disable_popup_notify)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_download = 1
				WHERE user_allow_fav_download_popup = 1
					$sql_fav_user
					AND user_id IN ($processing_user)";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not set popup for new or edited download on users table", "", __LINE__, __FILE__, $sql);
			}
		}

		if ($dl_config['upload_traffic_count'] && !$file_extern)
		{
			$sql = "UPDATE " . DL_CONFIG_TABLE . "
				SET config_value = config_value + $file_size
				WHERE config_name = 'remain_traffic'";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not set new remaining overall traffic", "", __LINE__, __FILE__, $sql);
			}
		}

		if ($thumb_name)
		{
			@unlink(POSTED_IMAGES_THUMBS_PATH . $dl_file['thumbnail']);
			@unlink(POSTED_IMAGES_THUMBS_PATH . $df_id . '_' . $thumb_name);
			$move_file($thumb_temp, POSTED_IMAGES_THUMBS_PATH . $df_id . '_' . $thumb_name);

			@chmod(POSTED_IMAGES_THUMBS_PATH . $df_id . '_' . $thumb_name, 0777);

			$thumb_message = '<br />' . $lang['Dl_thumb_upload'];

			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET thumbnail = '" . str_replace("\'", "''", $df_id . '_' . $thumb_name) . "'
				WHERE id = $df_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not write thumbnail information', '', __LINE__, __FILE__, $sql);
			}
		}
		elseif ($del_thumb)
		{
			$sql = "UPDATE " . DOWNLOADS_TABLE . "
				SET thumbnail = ''
				WHERE id = $df_id";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not write thumbnail information', '', __LINE__, __FILE__, $sql);
			}

			@unlink(POSTED_IMAGES_THUMBS_PATH . $dl_file['thumbnail']);

			$thumb_message = '<br />' . $lang['Dl_thumb_del'];
		}

		if ($file_cat_old <> $new_cat && !$file_extern && !$file_temp)
		{
			$old_path = $index[$file_cat_old]['cat_path'];
			$new_path = $index[$new_cat]['cat_path'];

			if ($new_path != $old_path)
			{
				@copy($dl_config['dl_path'] . $old_path . $file_name, $dl_config['dl_path'] . $new_path . $file_name);
				@unlink($dl_config['dl_path'] . $old_path . $file_name);
			}

			$sql = "UPDATE " . DL_STATS_TABLE . "
				SET cat_id = $new_cat
				WHERE id = $df_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not move downloads', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . DL_COMMENTS_TABLE . "
				SET cat_id = $new_cat
				WHERE id = $df_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not move downloads', '', __LINE__, __FILE__, $sql);
			}
		}

		if ($own_edit)
		{
			$redirect_url = append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id);
			meta_refresh(3, $redirect_url);
			$message = $lang['Download_updated'] . $thumb_message . '<br /><br />' . sprintf($lang['Click_return_download_details'], '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $df_id) . '">', '</a>');
		}
		else
		{
			$redirect_url = append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=manage&amp;cat_id=' . $cat_id);
			$return_string = ($action == 'approve') ? $lang['Click_return_modcp_approve'] : $lang['Click_return_modcp_manage'];
			$message = $lang['Download_updated'] . $thumb_message . '<br /><br />' . sprintf($return_string, '<a href="' . append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=manage&amp;cat_id=' . $cat_id) . '">', '</a>');
		}

		meta_refresh(3, $redirect_url);

		message_die(GENERAL_MESSAGE, $message);
		exit;
	}

	$action = 'manage';
}

if ($action == 'delete' && $cat_id)
{
	if (!empty($dl_id))
	{
		if (!$confirm)
		{
			if (count($dl_id) == 1)
			{
				$dl_file = array();
				$dl_file = $dl_mod->all_files($cat_id, '', 'ASC', '', intval($dl_id[0]));
				$description = $dl_file['description'];
				$delete_confirm_text = $lang['Dl_confirm_delete_single_file'];
			}
			else
			{
				$description = count($dl_id);
				$delete_confirm_text = $lang['Dl_confirm_delete_multiple_files'];
			}

			/*
			* output confirmation page
			*/
			$page_title = $lang['Downloads'];
			$meta_description = '';
			$meta_keywords = '';
			$nav_server_url = create_server_url();
			$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>';
			include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

			$template->set_filenames(array('confirm_body' => 'dl_confirm_body.tpl'));

			$template->assign_block_vars('delete_files_confirm', array());

			$s_hidden_fields = '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';
			$s_hidden_fields .= '<input type="hidden" name="action" value="delete" />';
			$s_hidden_fields .= '<input type="hidden" name="confirm" value="1" />';

			foreach($dl_id as $cat_id => $value)
			{
				$s_hidden_fields .= '<input type="hidden" name="dlo_id[]" value="'.$value.'" />';
			}

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Information'],
				'MESSAGE_TEXT' => sprintf($delete_confirm_text, $description),

				'L_DELETE_FILE_TOO' => $lang['Dl_delete_file_confirm'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=modcp'),
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);

			$template->pparse('confirm_body');

			include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);
		}
		else
		{
			$dl_ids = '';
			for ($i = 0; $i < count($dl_id); $i++)
			{
				$dl_ids .= ($dl_ids) ? ', '.intval($dl_id[$i]) : intval($dl_id[$i]);
			}

			$sql = "SELECT c.path, d.file_name, d.thumbnail FROM " . DL_CAT_TABLE . " c, " . DOWNLOADS_TABLE . " d
				WHERE c.id = $cat_id
					AND c.id = d.cat
					AND d.id IN ($dl_ids)";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete download from server', '', __LINE__, __FILE__, $sql);
			}

			while ($row = $db->sql_fetchrow($result))
			{
				$path = $row['path'];
				$file_name = $row['file_name'];

				@unlink(POSTED_IMAGES_THUMBS_PATH . $row['thumbnail']);
				if ($del_file)
				{
					@unlink($dl_config['dl_path'] . $path . $file_name);
				}

			}
			$db->sql_freeresult($result);

			$sql = "DELETE FROM " . DOWNLOADS_TABLE . "
				WHERE id IN ($dl_ids)
					AND cat = $cat_id";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't delete download data", "", __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . DL_STATS_TABLE . "
				WHERE id IN ($dl_ids)
					AND cat_id = $cat_id";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't delete download statistics", "", __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . DL_COMMENTS_TABLE . "
				WHERE id IN ($dl_ids)
					AND cat_id = $cat_id";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't delete download comments", "", __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . DL_NOTRAF_TABLE . "
				WHERE dl_id IN ($dl_ids)";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Couldn't delete download traffic marks", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	$action = 'manage';
}

if ($action == 'cdelete')
{
	if (!empty($dl_id))
	{
		$dl_ids = '';
		for ($i = 0; $i < count($dl_id); $i++)
		{
			$dl_ids .= ($dl_ids) ? ', '.intval($dl_id[$i]) : intval($dl_id[$i]);
		}

		$sql = "DELETE FROM " . DL_COMMENTS_TABLE . "
			WHERE dl_id IN ($dl_ids)";
		if(!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't delete download comments", "", __LINE__, __FILE__, $sql);
		}
	}

	$dl_id = array();
	$action = 'capprove';
}

if ($action == 'edit')
{
	$dl_file = array();
	$dl_file = $dl_mod->all_files(0, '', 'ASC', '', $df_id, true);

	$s_hidden_fields = '<input type="hidden" name="action" value="save" />';
	$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="df_id" value="' . $df_id . '" />';

	$description = $dl_file['description'];
	$file_traffic = $dl_file['file_traffic'];
	$file_name = $dl_file['file_name'];
	$cat = $dl_file['cat'];
	$long_desc = $dl_file['long_desc'];
	$approve = $dl_file['approve'];
	$hacklist = $dl_file['hacklist'];
	$hack_author = $dl_file['hack_author'];
	$hack_author_email = $dl_file['hack_author_email'];
	$hack_author_website = $dl_file['hack_author_website'];
	$hack_version = $dl_file['hack_version'];
	$hack_dl_url = $dl_file['hack_dl_url'];
	$mod_test = $dl_file['test'];
	$require = $dl_file['req'];
	$todo = $dl_file['todo'];
	$warning = $dl_file['warning'];
	$mod_desc = $dl_file['mod_desc'];
	$mod_list = ($dl_file['mod_list']) ? 'checked="checked"' : '';
	$mod_desc = stripslashes($mod_desc);
	$long_desc = stripslashes($long_desc);
	$warning = stripslashes($warning);

	if ($index[$cat_id]['allow_thumbs'] && $dl_config['thumb_fsize'])
	{
		$thumbnail = $dl_file['thumbnail'];
		$thumbnail_explain = sprintf($lang['Dl_thumb_dim_size'], $dl_config['thumb_xsize'], $dl_config['thumb_ysize'], $dl_mod->dl_size($dl_config['thumb_fsize']));
		$template->assign_block_vars('allow_thumbs', array());

		if ($thumbnail)
		{
			$template->assign_block_vars('allow_thumbs.thumbnail', array(
				'THUMBNAIL' => POSTED_IMAGES_THUMBS_PATH . $thumbnail)
			);
		}
	}

	if ($file_traffic < 1024)
	{
		$file_traffic_range_kb = '';
		$file_traffic_range_mb = '';
		$file_traffic_range_gb = '';
	}
	elseif ($file_traffic < 1048576)
	{
		$file_traffic = floor($file_traffic / 1024);
		$file_traffic_range_kb = 'checked="checked"';
		$file_traffic_range_mb = '';
		$file_traffic_range_gb = '';
	}
	elseif ($file_traffic < 1073741824)
	{
		$file_traffic = floor($file_traffic / 1048576);
		$file_traffic_range_kb = '';
		$file_traffic_range_mb = 'checked="checked"';
		$file_traffic_range_gb = '';
	}
	else
	{
		$file_traffic = floor($file_traffic / 1073741824);
		$file_traffic_range_kb = '';
		$file_traffic_range_mb = '';
		$file_traffic_range_gb = 'checked="checked"';
	}

	switch ($dl_file['free'])
	{
		case 1:
			$check_not_free = '';
			$checkfree = 'checked="checked"';
			$checkfree_reg = '';
			break;

		case 2:
			$check_not_free = '';
			$checkfree = '';
			$checkfree_reg = 'checked="checked"';
			break;

		default:
			$check_not_free = 'checked="checked"';
			$checkfree = '';
			$checkfree_reg = '';
	}

	if ($dl_file['extern'])
	{
		$checkextern = 'checked="checked"';
		$dl_extern_url = $file_name;
	}

	if (!$own_edit)
	{
		$select_code = '<select name="new_cat">';
		$select_code .= $dl_mod->dl_dropdown(0, 0, $cat_id, 'auth_up');
		$select_code .= '</select>';
	}
	else
	{
		$select_code = '';
	}

	$page_title = $lang['Downloads'];
	$meta_description = '';
	$meta_keywords = '';
	$nav_server_url = create_server_url();
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'dl_edit_body.tpl'));

	$template->assign_block_vars('modcp', array());

	if (!$dl_config['disable_email'])
	{
		$template->assign_block_vars('email_block', array());
	}

	if (!$dl_config['disable_popup'])
	{
		$template->assign_block_vars('change_time', array(
			'L_CHANGE_TIME' => $lang['Dl_no_change_edit_time']
			)
		);

		if ($dl_config['disable_popup_notify'])
		{
			$template->assign_block_vars('popup_notify', array(
				'L_DISABLE_POPUP' => $lang['Dl_disable_popup']
				)
			);
		}
	}

	if ($index[$cat_id]['allow_mod_desc'])
	{
		$template->assign_block_vars('allow_edit_mod_desc', array());
	}

	if ($dl_config['use_hacklist'] && $userdata['user_level'] == ADMIN)
	{
		$template->assign_block_vars('use_hacklist', array());
	}

	if (!$own_edit)
	{
		$template->assign_block_vars('cat_choose', array());
	}
	else
	{
		$s_hidden_fields .= '<input type="hidden" name="new_cat" value="' . $cat_id . '" />';
	}

	$ext_blacklist = $dl_mod->get_ext_blacklist();
	if (count($ext_blacklist))
	{
		$blacklist_explain = '<br />' . sprintf($lang['Dl_forbidden_ext_explain'], implode(', ', $ext_blacklist));
	}
	else
	{
		$blacklist_explain = '';
	}

	$template->assign_vars(array(
		'L_DL_FILES_TITLE' => $lang['Dl_files_title'],
		'L_DL_NAME' => $lang['Dl_name'],
		'L_DL_CAT_NAME' => $lang['Dl_cat_name'],
		'L_DL_DESCRIPTION' => $lang['Dl_file_description'],
		'L_LINK_URL' => $lang['Dl_files_url'],
		'L_DL_EXTERN' => $lang['Dl_extern'],
		'L_DL_IS_FREE' => $lang['Dl_is_free'],
		'L_DL_IS_FREE_REG' => $lang['Dl_is_free_reg'],
		'L_DL_TRAFFIC' => $lang['Dl_traffic'],
		'L_DL_APPROVE' => $lang['Dl_approve'],
		'L_DL_MOD_DESC' => $lang['Dl_mod_desc'],
		'L_DL_MOD_LIST' => $lang['Dl_mod_list'],
		'L_DL_MOD_REQUIRE' => $lang['Dl_mod_require'],
		'L_DL_MOD_TEST' => $lang['Dl_mod_test'],
		'L_DL_MOD_TODO' => $lang['Dl_mod_todo'],
		'L_DL_MOD_WARNING' => $lang['Dl_mod_warning'],
		'L_DL_SEND_NOTIFY' => $lang['Dl_disable_email'],
		'L_DL_UPLOAD_FILE' => $lang['Dl_upload_file'],
		'L_DL_THUMBNAIL' => $lang['Dl_thumb'],
		'L_DL_THUMBNAIL_EXPLAIN' => $thumbnail_explain,
		'L_DL_HACK_AUTHOR' => $lang['Dl_hack_autor'],
		'L_DL_HACK_AUTHOR_EMAIL' => $lang['Dl_hack_autor_email'],
		'L_DL_HACK_AUTHOR_WEBSITE' => $lang['Dl_hack_autor_website'],
		'L_DL_HACK_DL_URL' => $lang['Dl_hack_dl_url'],
		'L_DL_HACK_VERSION' => $lang['Dl_hack_version'],
		'L_DL_HACKLIST' => $lang['Dl_hacklist'],
		'L_EXT_BLACKLIST' => $blacklist_explain,
		'L_DL_NAME_EXPLAIN' => 'Dl_name',
		'L_DL_APPROVE_EXPLAIN' => 'Dl_approve',
		'L_DL_CAT_NAME_EXPLAIN' => 'Dl_choose_category',
		'L_DL_DESCRIPTION_EXPLAIN' => 'Dl_file_description',
		'L_DL_EXTERN_EXPLAIN' => 'Dl_extern_up',
		'L_DL_HACK_AUTHOR_EXPLAIN' => 'Dl_hack_autor',
		'L_DL_HACK_AUTHOR_EMAIL_EXPLAIN' => 'Dl_hack_autor_email',
		'L_DL_HACK_AUTHOR_WEBSITE_EXPLAIN' => 'Dl_hack_autor_website',
		'L_DL_HACK_DL_URL_EXPLAIN' => 'Dl_hack_dl_url',
		'L_DL_HACK_VERSION_EXPLAIN' => 'Dl_hack_version',
		'L_DL_HACKLIST_EXPLAIN' => 'Dl_hacklist',
		'L_DL_IS_FREE_EXPLAIN' => 'Dl_is_free',
		'L_DL_MOD_DESC_EXPLAIN' => 'Dl_mod_desc',
		'L_DL_MOD_LIST_EXPLAIN' => 'Dl_mod_list',
		'L_DL_MOD_REQUIRE_EXPLAIN' => 'Dl_mod_require',
		'L_DL_MOD_TEST_EXPLAIN' => 'Dl_mod_test',
		'L_DL_MOD_TODO_EXPLAIN' => 'Dl_mod_todo',
		'L_DL_MOD_WARNING_EXPLAIN' => 'Dl_mod_warning',
		'L_DL_TRAFFIC_EXPLAIN' => 'Dl_traffic',
		'L_DL_UPLOAD_FILE_EXPLAIN' => 'Dl_upload_file',
		'L_DL_THUMBNAIL_EXPLAIN' => 'Dl_thumb',
		'L_CHANGE_TIME_EXPLAIN' => 'Dl_no_change_edit_time',
		'L_DISABLE_POPUP_EXPLAIN' => 'Dl_disable_popup',
		'L_DL_SEND_NOTIFY_EXPLAIN' => 'Dl_disable_email',

		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_DELETE' => $lang['Dl_delete'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_KB' => $lang['Dl_KB'],
		'L_MB' => $lang['Dl_MB'],
		'L_GB' => $lang['Dl_GB'],

		'DESCRIPTION' => $description,
		'SELECT_CAT' => $select_code,
		'LONG_DESC' => $long_desc,
		'URL' => $dl_extern_url,
		'CHECKEXTERN' => $checkextern,
		'CHECKNOTFREE' => $check_not_free,
		'CHECKFREE' => $checkfree,
		'CHECKFREE_REG' => $checkfree_reg,
		'TRAFFIC' => $file_traffic,
		'FILE_TRAFFIC_RANGE_KB' => $file_traffic_range_kb,
		'FILE_TRAFFIC_RANGE_MB' => $file_traffic_range_mb,
		'APPROVE' => ($approve) ? 'checked="checked"' : '',
		'MOD_DESC' => $mod_desc,
		'MOD_LIST' => $mod_list,
		'MOD_REQUIRE' => $require,
		'MOD_TEST' => $mod_test,
		'MOD_TODO' => $todo,
		'MOD_WARNING' => $warning,
		'MAX_UPLOAD_SIZE' => sprintf($lang['Dl_upload_max_filesize'], ini_get('upload_max_filesize')),
		'HACK_AUTHOR' => $hack_author,
		'HACK_AUTHOR_EMAIL' => $hack_author_email,
		'HACK_AUTHOR_WEBSITE' => $hack_author_website,
		'HACK_DL_URL' => $hack_dl_url,
		'HACK_VERSION' => $hack_version,
		'HACKLIST_EVER' => ($hacklist == 2) ? 'checked="checked"' : '',
		'HACKLIST_NO' => ($hacklist == 0) ? 'checked="checked"' : '',
		'HACKLIST_YES' => ($hacklist == 1) ? 'checked="checked"' : '',

		'ENCTYPE' => 'enctype="multipart/form-data"',

		'L_NAV1' => $lang['Dl_cat_title'],
		'L_NAV2' => $lang['Dl_modcp_edit'],
		'U_NAV1' => append_sid('downloads.' . PHP_EXT),
		'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;cat_id=' . $cat_id),

		'S_DOWNLOADS_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=modcp'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);

}

if ($action == 'move' && $new_cat && $cat_id)
{
	if (!empty($dl_id))
	{
		$new_path = $index[$new_cat]['cat_path'];

		for ($i = 0; $i < count($dl_id); $i++)
		{
			$sql = "SELECT c.path, d.file_name FROM " . DOWNLOADS_TABLE . " d, " . DL_CAT_TABLE . " c
				WHERE d.cat = c.id
					AND d.id = " . intval($dl_id[$i]) . "
					AND c.id = $cat_id
					AND d.extern = 0";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not fetch old path for move download', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);
			$old_path = $row['path'];
			$file_name = $row['file_name'];
			$db->sql_freeresult($result);

			if ($new_path != $old_path)
			{
				@copy($dl_config['dl_path'] . $old_path . $file_name, $dl_config['dl_path'] . $new_path . $file_name);
				@unlink($dl_config['dl_path'] . $old_path . $file_name);
			}
		}

		$dl_ids = '';
		for ($i = 0; $i < count($dl_id); $i++)
		{
			$dl_ids .= ($dl_ids) ? ', '.intval($dl_id[$i]) : intval($dl_id[$i]);
		}

		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET cat = $new_cat
			WHERE id IN ($dl_ids)
				AND cat = $cat_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not move downloads', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . DL_STATS_TABLE . "
			SET cat_id = $new_cat
			WHERE id IN ($dl_ids)
				AND cat_id = $cat_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not move downloads', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . DL_COMMENTS_TABLE . "
			SET cat_id = $new_cat
			WHERE id IN ($dl_ids)
				AND cat_id = $cat_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not move downloads', '', __LINE__, __FILE__, $sql);
		}
	}

	$action = 'manage';
}

if ($action == 'lock')
{
	if (!empty($dl_id))
	{
		$dl_ids = '';
		for ($i = 0; $i < count($dl_id); $i++)
		{
			$dl_ids .= ($dl_ids) ? ', '.intval($dl_id[$i]) : intval($dl_id[$i]);
		}

		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET approve = 0
			WHERE id IN ($dl_ids)";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not lock downloads', '', __LINE__, __FILE__, $sql);
		}
	}

	$action = 'manage';
}

if ($action == 'approve')
{
	if (!empty($dl_id))
	{
		$dl_ids = '';
		for ($i = 0; $i < count($dl_id); $i++)
		{
			$dl_ids .= ($dl_ids) ? ', '.intval($dl_id[$i]) : intval($dl_id[$i]);
		}

		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET approve = " . TRUE . "
			WHERE id IN ($dl_ids)";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not approve downloads', '', __LINE__, __FILE__, $sql);
		}
	}

	$sql_access_cats = ($userdata['user_level'] == ADMIN) ? '' : ' AND cat IN (' . implode(',', $access_cat) . ')';

	$sql = "SELECT id FROM " . DOWNLOADS_TABLE . "
		WHERE approve = 0
			$sql_access_cats";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not read download informations', '', __LINE__, __FILE__, $sql);
	}

	$total_approve = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if (!$total_approve)
	{
		redirect(append_sid('downloads.' . PHP_EXT));
	}

	$page_title = $lang['Downloads'];
	$meta_description = '';
	$meta_keywords = '';
	$nav_server_url = create_server_url();
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'dl_modcp_approve.tpl'));

	$sql = "SELECT cat, id, description FROM " . DOWNLOADS_TABLE . "
		WHERE approve = 0
			$sql_access_cats
		ORDER BY cat, description
		LIMIT $start, " . $dl_config['dl_links_per_page'];
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not read download informations', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$cat_id = $row['cat'];
		$cat_name = $index[$cat_id]['cat_name'];
		$cat_name = str_replace('&nbsp;&nbsp;|', '', $cat_name);
		$cat_name = str_replace('___&nbsp;', '', $cat_name);
		$cat_view = $index[$cat_id]['nav_path'];

		$description = stripslashes($row['description']);
		$file_id = $row['id'];

		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('approve_row', array(
			'ROW_CLASS' => $row_class,
			'CAT_NAME' => $cat_name,
			'FILE_ID' => $file_id,
			'DESCRIPTION' => $description,
			'EDIT_IMG' => '<img src="' . $images['icon_edit'] . '" border="0" alt="" title="" />',

			'U_CAT_VIEW' => $cat_view,
			'U_EDIT' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=edit&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id . '&amp;modcp=1'),
			'U_DOWNLOAD' => append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id . '&amp;modcp=1'))
		);

		$i++;
	}
	$db->sql_freeresult($result);

	$s_hidden_fields = '<input type="hidden" name="action" value="approve" />';
	$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="start" value="'.$start.'" />';

	$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=modcp&amp;action=approve&amp;cat_id=' . $cat_id, $total_approve, $dl_config['dl_links_per_page'], $start);

	$template->assign_vars(array(
		'L_APPROVE' => $lang['Dl_approve'],
		'L_MARK_ALL' => $lang['Dl_mark_all'],
		'L_UNMARK_ALL' => $lang['Dl_unmark'],
		'L_DL_CAT_NAME' => $lang['Dl_cat_name'],
		'L_DOWNLOAD' => $lang['Dl_download'],
		'L_SET' => $lang['Dl_edit'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Dl_delete'],

		'PAGINATION' => ($total_approve > $board_config['dl_links_per_page']) ? $pagination : '',

		'L_NAV1' => $lang['Dl_cat_title'],
		'L_NAV2' => $lang['Dl_modcp_approve'],
		'U_NAV1' => append_sid('downloads.' . PHP_EXT),
		'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;cat_id=' . $cat_id),

		'S_DL_MODCP_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=modcp'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}

if ($action == 'capprove')
{
	if (!empty($dl_id))
	{
		$dl_ids = '';
		for ($i = 0; $i < count($dl_id); $i++)
		{
			$dl_ids .= ($dl_ids) ? ', '.intval($dl_id[$i]) : intval($dl_id[$i]);
		}

		$sql = "UPDATE " . DL_COMMENTS_TABLE . "
			SET approve = " . TRUE . "
			WHERE dl_id IN ($dl_ids)";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not approve comments', '', __LINE__, __FILE__, $sql);
		}
	}

	$sql_access_cats = ($userdata['user_level'] == ADMIN) ? '' : ' AND c.cat_id IN (' . implode(',', $access_cat) . ')';

	$sql = "SELECT c.dl_id FROM " . DL_COMMENTS_TABLE . " c
		WHERE c.approve = 0
			$sql_access_cats";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not read download informations', '', __LINE__, __FILE__, $sql);
	}

	$total_approve = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if (!$total_approve)
	{
		redirect(append_sid('downloads.' . PHP_EXT));
	}

	$page_title = $lang['Downloads'];
	$meta_description = '';
	$meta_keywords = '';
	$nav_server_url = create_server_url();
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'dl_modcp_capprove.tpl'));

	$sql = "SELECT d.cat, d.id, d.description, c.comment_text, c.user_id, c.username, c.dl_id FROM " . DOWNLOADS_TABLE . " d, " . DL_COMMENTS_TABLE . " c
		WHERE d.id = c.id
			AND c.approve = 0
			$sql_access_cats
		ORDER BY d.cat, d.description
		LIMIT $start, " . $dl_config['dl_links_per_page'];
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not read download informations', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$cat_id = $row['cat'];
		$cat_name = $index[$cat_id]['cat_name'];
		$cat_name = str_replace('&nbsp;&nbsp;|', '', $cat_name);
		$cat_name = str_replace('___&nbsp;', '', $cat_name);
		$cat_view = $index[$cat_id]['nav_path'];

		$description = stripslashes($row['description']);
		$file_id = $row['id'];
		$comment_id = $row['dl_id'];
		$comment_text = $row['comment_text'];
		$comment_text = (strlen($comment_text) > 200) ? substr($comment_text,0,200).'<br />[...]' : $comment_text;
		$comment_user_id = $row['user_id'];
		$comment_username = $row['username'];
		//$comment_user_link = ($comment_user_id <> ANONYMOUS) ? append_sid(PROFILE_MG . '?mode=viewprofile&amp;'.POST_USERS_URL."=$comment_user_id") : '';
		$comment_user_link = colorize_username($comment_user_id);

		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('approve_row', array(
			'ROW_CLASS' => $row_class,
			'CAT_NAME' => $cat_name,
			'FILE_ID' => $file_id,
			'DESCRIPTION' => $description,
			'COMMENT_USERNAME' => $comment_username,
			'COMMENT_TEXT' => $comment_text,
			'COMMENT_ID' => $comment_id,

			'EDIT_IMG' => '<img src="' . $images['icon_edit'] . '" border="0" alt="" title="" />',

			'U_CAT_VIEW' => $cat_view,
			'U_USER_LINK' => $comment_user_link,
			'U_EDIT' => append_sid('downloads.' . PHP_EXT . '?view=comment&amp;action=edit&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id . '&amp;dl_id=' . $comment_id),
			'U_DOWNLOAD' => append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id)
			)
		);

		$i++;
	}
	$db->sql_freeresult($result);

	$s_hidden_fields = '<input type="hidden" name="action" value="capprove" />';
	$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="start" value="'.$start.'" />';

	$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=modcp&amp;action=capprove&amp;cat_id=' . $cat_id, $total_approve, $dl_config['dl_links_per_page'], $start);

	$template->assign_vars(array(
		'L_APPROVE' => $lang['Dl_approve'],
		'L_MARK_ALL' => $lang['Dl_mark_all'],
		'L_UNMARK_ALL' => $lang['Dl_unmark'],
		'L_DL_CAT_NAME' => $lang['Dl_cat_name'],
		'L_DOWNLOAD' => $lang['Dl_download'],
		'L_COMMENT' => $lang['Dl_comment'],
		'L_SET' => $lang['Dl_edit'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Dl_delete'],

		'PAGINATION' => ($total_approve > $board_config['dl_links_per_page']) ? $pagination : '',

		'L_NAV1' => $lang['Dl_cat_title'],
		'L_NAV2' => $lang['Dl_modcp_capprove'],
		'U_NAV1' => append_sid('downloads.' . PHP_EXT),
		'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=capprove'),

		'S_DL_MODCP_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=modcp'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}

if (($action == 'manage') && $cat_id)
{
	$total_downloads = $index[$cat_id]['total'];

	if ($sort && $userdata['user_level'] == ADMIN)
	{
		$per_page = $total_downloads;
		$start = 0;
		$template->assign_block_vars('modcp_button', array());
	}
	else
	{
		$per_page = $dl_config['dl_links_per_page'];
		if ($userdata['user_level'] == ADMIN)
		{
			$template->assign_block_vars('order_button', array());
		}
	}

	if ($userdata['user_level'] == ADMIN)
	{
		$template->assign_block_vars('sort_asc', array());
	}

	if (!$total_downloads)
	{
		redirect(append_sid('downloads.' . PHP_EXT));
	}

	$page_title = $lang['Downloads'];
	$meta_description = '';
	$meta_keywords = '';
	$nav_server_url = create_server_url();
	$breadcrumbs_address = $lang['Nav_Separator'] . '<a href="' . $nav_server_url . append_sid('downloads.' . PHP_EXT) . '">' . $lang['Downloads'] . '</a>';
	include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

	$template->set_filenames(array('body' => 'dl_modcp_manage.tpl'));

	$sql = "SELECT * FROM " . DOWNLOADS_TABLE . "
		WHERE approve = " . TRUE . "
			AND cat = $cat_id
		ORDER BY cat, sort
		LIMIT $start, $per_page";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not read download informations', '', __LINE__, __FILE__, $sql);
	}

	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$description = stripslashes($row['description']);
		$file_id = $row['id'];

		$mini_icon = $dl_mod->mini_status_file($cat_id, $file_id);

		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('manage_row', array(
			'ROW_CLASS' => $row_class,
			'CAT_NAME' => $cat_name,
			'FILE_ID' => $file_id,
			'MINI_ICON' => $mini_icon,
			'DESCRIPTION' => $description,
			'EDIT_IMG' => '<img src="' . $images['icon_edit'] . '" border="0" alt="" title="" />',

			'U_UP' => ($userdata['user_level'] == ADMIN) ? append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=manage&amp;fmove=-1&amp;sort=1&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id) : '',
			'U_DOWN' => ($userdata['user_level'] == ADMIN) ? append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=manage&amp;fmove=1&amp;sort=1&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id) : '',
			'U_EDIT' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=edit&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id),
			'U_DOWNLOAD' => append_sid('downloads.' . PHP_EXT . '?view=detail&amp;df_id=' . $file_id)
			)
		);

		$i++;
	}
	$db->sql_freeresult($result);

	$s_cat_select = '<select name="new_cat">';
	$s_cat_select .= $dl_mod->dl_dropdown(0, 0, $cat_id, 'auth_view');
	$s_cat_select .= '</select>';

	$s_hidden_fields = '<input type="hidden" name="action" value="manage" />';
	$s_hidden_fields .= '<input type="hidden" name="cat_id" value="' . $cat_id . '" />';
	$s_hidden_fields .= '<input type="hidden" name="start" value="'.$start.'" />';

	$cat_name = $index[$cat_id]['cat_name'];
	$cat_name = str_replace('&nbsp;&nbsp;|', '', $cat_name);
	$cat_name = str_replace('___&nbsp;', '', $cat_name);

	if ($total_downloads > $per_page)
	{
		$pagination = generate_pagination('downloads.' . PHP_EXT . '?view=modcp&amp;cat_id=' . $cat_id, $total_downloads, $per_page, $start);
	}
	else
	{
		$pagination = '';
	}

	$template->assign_vars(array(
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_MARK_ALL' => $lang['Dl_mark_all'],
		'L_UNMARK_ALL' => $lang['Dl_unmark'],
		'L_MOVE' => $lang['Dl_move'],
		'L_DELETE' => $lang['Dl_delete'],
		'L_LOCK' => $lang['Dl_lock'],
		'L_DL_CAT_NAME' => $lang['Dl_cat_name'],
		'L_DOWNLOAD' => $lang['Dl_download'],
		'L_SET' => $lang['Dl_edit'],
		'L_EDIT' => $lang['Edit'],
		'L_DL_UP' => ($sort && $userdata['user_level'] == ADMIN) ? $lang['Dl_up'] : '',
		'L_DL_DOWN' => ($sort && $userdata['user_level'] == ADMIN) ? $lang['Dl_down'] : '',
		'L_DL_SORT' => $lang['Dl_order'],
		'L_DL_MODCP' => $lang['Dl_modcp_manage'],
		'L_DL_ABC' => ($userdata['user_level'] == ADMIN) ? $lang['Sort'] . ' ASC' : '',

		'PAGINATION' => ($total_downloads > $dl_config['dl_links_per_page']) ? $pagination : '',

		'L_NAV1' => $lang['Dl_cat_title'],
		'L_NAV2' => $cat_name,
		'L_NAV3' => $lang['Dl_modcp_manage'],
		'U_NAV1' => append_sid('downloads.' . PHP_EXT),
		'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id),
		'U_NAV3' => append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;cat_id=' . $cat_id),

		'U_SORT_ASC' => ($userdata['user_level'] == ADMIN) ? append_sid('downloads.' . PHP_EXT . '?view=modcp&amp;action=manage&amp;fmove=ABC&amp;sort=' . (($sort) ? 1 : '') . '&amp;df_id=' . $file_id . '&amp;cat_id=' . $cat_id) : '',
		'S_CAT_SELECT' => $s_cat_select,
		'S_DL_MODCP_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=modcp'),
		'S_HIDDEN_FIELDS' => $s_hidden_fields
		)
	);
}

?>