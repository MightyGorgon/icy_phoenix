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

if ($submit)
{
	$approve = ( isset($_POST['approve']) ) ? intval($_POST['approve']) : 0;
	$description = ( isset($_POST['description']) ) ? trim($_POST['description']) : '';
	$file_traffic = ( isset($_POST['file_traffic']) ) ? intval($_POST['file_traffic']) : 0;
	$file_traffic_range = ( isset($_POST['file_traffic_range']) ) ? $_POST['file_traffic_range'] : 'KB';
	$long_desc = ( isset($_POST['long_desc']) ) ? trim($_POST['long_desc']) : '';
	$file_name_name = ( isset($_POST['file_name']) ) ? trim($_POST['file_name']) : '';

	$file_free = ( isset($_POST['file_free']) ) ? intval($_POST['file_free']) : 0;
	$file_extern = ( isset($_POST['file_extern']) ) ? intval($_POST['file_extern']) : 0;

	$test = ( isset($_POST['test']) ) ? trim($_POST['test']) : '';
	$require = ( isset($_POST['require']) ) ? trim($_POST['require']) : '';
	$todo = ( isset($_POST['todo']) ) ? trim($_POST['todo']) : '';
	$warning = ( isset($_POST['warning']) ) ? trim($_POST['warning']) : '';
	$mod_desc = ( isset($_POST['mod_desc']) ) ? trim($_POST['mod_desc']) : '';
	$mod_list = ( $_POST['mod_list'] == 1 ) ? 1 : 0;

	$send_notify = ( isset($_POST['send_notify']) ) ? intval($_POST['send_notify']) : 0;
	$disable_popup_notify = (isset($_POST['disable_popup_notify'])) ? intval($_POST['disable_popup_notify']) : 0;

	$html_on = $board_config['allow_html'];
	$bbcode_on = $board_config['allow_bbcode'];
	$smile_on = $board_config['allow_smilies'];

	$description = prepare_message($description, $html_on, $bbcode_on, $smile_on);
	$long_desc = prepare_message($long_desc, $html_on, $bbcode_on, $smile_on);
	$mod_desc = prepare_message($mod_desc, $html_on, $bbcode_on, $smile_on);
	$warning = prepare_message($warning, $html_on, $bbcode_on, $smile_on);

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

	if ($dl_config['thumb_fsize'] && $index[$cat_id]['allow_thumbs'])
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

		if (!$file_name)
		{
			message_die(GENERAL_MESSAGE, $lang['Dl_no_filename_entered']);
		}

		if ($HTTP_POST_FILES['dl_name']['error'])
		{
			message_die(GENERAL_MESSAGE, $lang['DL_upload_error']);
		}

		$remain_traffic = $dl_config['overall_traffic'] - $dl_config['remain_traffic'];
		if($file_size == 0 || ($file_size > $remain_traffic && $dl_config['upload_traffic_count']))
		{
			message_die(GENERAL_MESSAGE, $lang['Dl_no_upload_traffic']);
		}

		$dl_path = $index[$cat_id]['cat_path'];

		$i = 0;
		do
		{
			$j = ($i == 0) ? '' : $i.'_';
			$file_name = $j . $file_name;
			$i++;
		}
		while(@file_exists($dl_config['dl_path'] . $dl_path . $file_name));

		$move_file($file_temp, $dl_config['dl_path'] . $dl_path . $file_name);

		@chmod($dl_config['dl_path'] . $dl_path . $file_name, 0777);
	}
	else
	{
		if (empty($file_name_name))
		{
			message_die(GENERAL_MESSAGE, $lang['Dl_no_external_url']);
		}

		$file_name = $file_name_name;
		$file_size = 0;
	}

	if($cat_id)
	{
		$current_time = time();
		$current_user = $userdata['user_id'];

		$approve = ($index[$cat_id]['must_approve'] && !$cat_auth['auth_mod'] && !$index[$cat_id]['auth_mod'] && $userdata['user_level'] != ADMIN) ? 0 : $approve;

		if (!$cat_auth['auth_mod'] && !$index[$cat_id]['auth_mod'] && !$index[$cat_id]['allow_mod_desc'] && $userdata['user_level'] != ADMIN)
		{
			$sql = "INSERT INTO " . DOWNLOADS_TABLE . "
				(file_name, cat, description, long_desc, free, extern,
				hacklist, hack_author, hack_author_email, hack_author_website, hack_version, hack_dl_url,
				approve, file_size, change_time, add_time,
				change_user, add_user, file_traffic)
				VALUES
				('" . str_replace("\'", "''", $file_name) . "',
				'" . str_replace("\'", "''", $cat_id) . "',
				'" . str_replace("\'", "''", $description) . "',
				'" . str_replace("\'", "''", $long_desc) . "',
				'" . str_replace("\'", "''", $file_free) . "',
				'" . str_replace("\'", "''", $file_extern) . "',
				'" . str_replace("\'", "''", $hacklist) . "',
				'" . str_replace("\'", "''", $hack_author) . "',
				'" . str_replace("\'", "''", $hack_author_email) . "',
				'" . str_replace("\'", "''", $hack_author_website) . "',
				'" . str_replace("\'", "''", $hack_version) . "',
				'" . str_replace("\'", "''", $hack_dl_url) . "',
				$approve, $file_size, $current_time, $current_time,
				$current_user, $current_user,
				'" . str_replace("\'", "''", $file_traffic) . "')";
		}
		else
		{
			$sql = "INSERT INTO " . DOWNLOADS_TABLE . "
				(file_name, cat, description, long_desc, free, extern,
				hacklist, hack_author, hack_author_email, hack_author_website, hack_version, hack_dl_url,
				test, req, todo, warning, mod_desc, mod_list,
				approve,  file_size, change_time, add_time,
				change_user, add_user, file_traffic)
				VALUES
				('" . str_replace("\'", "''", $file_name) . "',
				'" . str_replace("\'", "''", $cat_id) . "',
				'" . str_replace("\'", "''", $description) . "',
				'" . str_replace("\'", "''", $long_desc) . "',
				'" . str_replace("\'", "''", $file_free) . "',
				'" . str_replace("\'", "''", $file_extern) . "',
				'" . str_replace("\'", "''", $hacklist) . "',
				'" . str_replace("\'", "''", $hack_author) . "',
				'" . str_replace("\'", "''", $hack_author_email) . "',
				'" . str_replace("\'", "''", $hack_author_website) . "',
				'" . str_replace("\'", "''", $hack_version) . "',
				'" . str_replace("\'", "''", $hack_dl_url) . "',
				'" . str_replace("\'", "''", $test) . "',
				'" . str_replace("\'", "''", $require) . "',
				'" . str_replace("\'", "''", $todo ) . "',
				'" . str_replace("\'", "''", $warning) . "',
				'" . str_replace("\'", "''", $mod_desc) . "',
				$mod_list, $approve, $file_size, $current_time, $current_time,
				$current_user, $current_user,
				'" . str_replace("\'", "''", $file_traffic) . "')";
		}

		if( !$db->sql_query($sql) )
		{
			@unlink($dl_config['dl_path'] . $dl_path . $file_name);
			message_die(GENERAL_ERROR, "Could not insert new download", "", __LINE__, __FILE__, $sql);
		}

		$next_id = $db->sql_nextid();

		if ($thumb_name)
		{
			$move_file($thumb_temp, POSTED_IMAGES_THUMBS_PATH . $next_id . '_' . $thumb_name);

			@chmod(POSTED_IMAGES_THUMBS_PATH . $next_id . '_' . $thumb_name, 0777);

			$thumb_message = '<br />' . $lang['Dl_thumb_upload'];
		}

		$sql = "UPDATE " . DOWNLOADS_TABLE . "
			SET thumbnail = '" . str_replace("\'", "''", $next_id . '_' . $thumb_name) . "'
			WHERE id = $next_id";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not write thumbnail information', '', __LINE__, __FILE__, $sql);
		}

		if ($index[$cat_id]['statistics'])
		{
			if ($index[$cat_id]['stats_prune'])
			{
				$stat_prune = $dl_mod->dl_prune_stats($cat_id, $index[$cat_id]['stats_prune']);
			}

			$browser = $dl_mod->dl_client();

			$sql = "INSERT INTO " . DL_STATS_TABLE . "
				(cat_id, id, user_id, username, traffic, direction, user_ip, browser, time_stamp) VALUES
				($cat_id, $next_id, " . $userdata['user_id'] . ", '" . str_replace("\'", "''", $userdata['username']) . "', " . $file_size . ", 1, '" . $userdata['session_ip'] . "', '" . str_replace("\'", "''", $browser) . "', " . time() . ")";
			if (!($db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not update download statistics data', '', __LINE__, __FILE__, $sql);
			}
		}

		if ($approve)
		{
			$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_dl');
			$email_template = 'downloads_new_notify';
		}
		else
		{
			$processing_user = $dl_mod->dl_auth_users($cat_id, 'auth_mod');
			$email_template = 'downloads_approve_notify';
		}

		$processing_user .= ($processing_user) ? '' : 0;

		if (!$dl_config['disable_email'] && !$send_notify)
		{
			$sql = "SELECT user_email, username, user_lang FROM " . USERS_TABLE . "
				WHERE user_allow_new_download_email = 1
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
					'U_CATEGORY' => $server_url.'downloads.' . PHP_EXT . '?cat=' . $cat_id)
				);

				$emailer->send();
				$emailer->reset();
			}
		}

		if (!$dl_config['disable_popup'] && !$disable_popup_notify)
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_download = 1
				WHERE user_allow_new_download_popup = 1
					AND user_id IN ($processing_user)";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Could not set popup for new download into users table", "", __LINE__, __FILE__, $sql);
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

		$approve_message = ($approve) ? '' : '<br />' . $lang['Dl_must_be_approved'];

		$redirect_url = append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id);
		meta_refresh(3, $redirect_url);

		$message = $lang['Download_added'] . $thumb_message . $approve_message . '<br /><br />' . sprintf($lang['Click_return_downloads'], '<a href="' . append_sid('downloads.' . PHP_EXT . '?cat=' . $cat_id) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array('body' => 'dl_edit_body.tpl'));

if ($cat_auth['auth_mod'] || $index[$cat_id]['auth_mod'] || $userdata['user_level'] == ADMIN)
{
	$template->assign_block_vars('modcp', array());
}

if ($index[$cat_id]['allow_mod_desc'])
{
	$template->assign_block_vars('allow_edit_mod_desc', array());
}

if (!$dl_config['disable_email'])
{
	$template->assign_block_vars('email_block', array());
}

if (!$dl_config['disable_popup'] && $dl_config['disable_popup_notify'])
{
	$template->assign_block_vars('popup_notify', array(
		'L_DISABLE_POPUP' => $lang['Dl_disable_popup']
		)
	);
}

if ($index[$cat_id]['allow_thumbs'] && $dl_config['thumb_fsize'])
{
	$template->assign_block_vars('allow_thumbs', array());
}

if ($dl_config['upload_traffic_count'])
{
	$template->assign_block_vars('upload_traffic', array(
		'L_DL_UPLOAD_TRAFFIC_COUNT' => $lang['Dl_upload_traffic']
		)
	);
}

$s_cat_select = '<select name="cat_id">';
$s_cat_select .= $dl_mod->dl_dropdown(0, 0, $cat_id, 'auth_up');
$s_cat_select .= '</select>';

$thumbnail_explain = sprintf($lang['Dl_thumb_dim_size'], $dl_config['thumb_xsize'], $dl_config['thumb_ysize'], $dl_mod->dl_size($dl_config['thumb_fsize']));

if (!$cat_auth['auth_mod'] && !$index[$cat_id]['auth_mod'] && $userdata['user_level'] != ADMIN)
{
	$approve = ($index[$cat_id]['must_approve']) ? 0 : true;
	$s_hidden_fields = '<input type="hidden" name="approve" value="'.$approve.'" />';
}

if ($dl_config['use_hacklist'] && $userdata['user_level'] == ADMIN)
{
	$template->assign_block_vars('use_hacklist', array());
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

$template->assign_block_vars('cat_choose', array());

$template->assign_vars(array(
	'L_DL_FILES_TITLE' => $lang['Dl_upload'],
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
	'L_DL_UPLOAD_FILE' => $lang['Dl_upload_file'],
	'L_DL_SEND_NOTIFY' => $lang['Dl_disable_email'],
	'L_DL_THUMBNAIL' => $lang['Dl_thumb'],
	'L_DL_THUMBNAIL_SECOND' => $thumbnail_explain,
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
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_KB' => $lang['Dl_KB'],
	'L_MB' => $lang['Dl_MB'],
	'L_GB' => $lang['Dl_GB'],

	'DESCRIPTION' => '',
	'SELECT_CAT' => $s_cat_select,
	'LONG_DESC' => '',
	'URL' => '',
	'CHECKEXTERN' => '',
	'CHECKFREE' => '',
	'CHECKFREE_REG' => '',
	'TRAFFIC' => 0,
	'FILE_TRAFFIC_RANGE_KB' => 'checked="checked"',
	'FILE_TRAFFIC_RANGE_MB' => '',
	'FILE_TRAFFIC_RANGE_GB' => '',
	'APPROVE' => 'checked="checked"',
	'MOD_DESC' => '',
	'MOD_LIST' => '',
	'MOD_REQUIRE' => '',
	'MOD_TEST' => '',
	'MOD_TODO' => '',
	'MOD_WARNING' => '',
	'MAX_UPLOAD_SIZE' => sprintf($lang['Dl_upload_max_filesize'], ini_get('upload_max_filesize')),

	'ENCTYPE' => 'enctype="multipart/form-data"',

	'L_NAV1' => $lang['Dl_cat_title'],
	'L_NAV2' => $lang['Dl_upload'],
	'U_NAV1' => append_sid('downloads.' . PHP_EXT),
	'U_NAV2' => append_sid('downloads.' . PHP_EXT . '?view=upload&amp;cat_id=' . $cat_id),

	'S_DOWNLOADS_ACTION' => append_sid('downloads.' . PHP_EXT . '?view=upload'),
	'S_HIDDEN_FIELDS' => $s_hidden_fields . (($dl_config['disable_email']) ? '<input type="hidden" name="send_notify" value="0" />' : '')
	)
);

?>