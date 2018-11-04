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

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['110_Manage'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
if (!class_exists('ct_database'))
{
	include(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_database.' . PHP_EXT);
	$ctracker_config = new ct_database();
}
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users_delete.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

$html_entities_match = array('#<#', '#>#');
$html_entities_replace = array('&lt;', '&gt;');

// Disallow other admins to delete or edit the first admin - BEGIN
$selected_user_id = request_post_var('id', 0);
$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
if (($selected_user_id == $founder_id) && ($user->data['user_id'] != $founder_id))
{
	$edituser = $user->data['username'];
	$editok = $user->data['user_id'];
	$sql = "INSERT INTO " . ADMINEDIT_TABLE . " (edituser, editok) VALUES ('" . $db->sql_escape($edituser) . "','" . $editok . "')";
	$result = $db->sql_query($sql);
	message_die(GENERAL_MESSAGE, $lang['L_ADMINEDITMSG']);
}
// Disallow other admins to delete or edit the first admin - END

$mode = request_var('mode', '');
$redirect = request_var('redirect', '');

// Begin program
if (($mode == 'edit') || (($mode == 'save') && (isset($_POST['acp_username']) || isset($_GET[POST_USERS_URL]) || isset($_POST[POST_USERS_URL]))))
{
	// Reset some config values which are overridden from user->setup()
	$config_tmp = get_config(false);
	$config['default_lang'] = $config_tmp['default_lang'];
	$config['board_timezone'] = $config_tmp['board_timezone'];
	$config['default_dateformat'] = $config_tmp['default_dateformat'];

	attachment_quota_settings('user', $_POST['submit'], $mode);

	// Ok, the profile has been modified and submitted, let's update
	if ((($mode == 'save') && isset($_POST['submit'])) || isset($_POST['avatargallery']) || isset($_POST['submitavatar']) || isset($_POST['cancelavatar']))
	{
		$user_id = $selected_user_id;

		// CrackerTracker v5.x
		$ctracker_config->first_admin_protection($user_id);
		// CrackerTracker v5.x

		if (!($this_userdata = get_userdata($user_id)))
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_USER');
		}

		if($_POST['deleteuser'] && ($user->data['user_id'] != $user_id))
		{
			$killed = ip_user_kill($user_id);

			$message = $lang['User_deleted'] . '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid('admin_users.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}

		$trim_var_list = array(
			'username' => 'acp_username',
			'password' => 'password_change',
			'password_confirm' => 'password_confirm',
			'signature' => 'signature',
			'selfdes' => 'selfdes'
		);
		while(list($var, $param) = @each($trim_var_list))
		{
			$$var = request_post_var($param, '', true);
			$$var = htmlspecialchars_decode($$var, ENT_COMPAT);
		}
		$username = phpbb_clean_username($username);
		$signature = str_replace('<br />', "\n", $signature);
		$selfdes = str_replace('<br />', "\n", $selfdes);

		$strip_var_list = array(
			'user_first_name' => 'user_first_name',
			'user_last_name' => 'user_last_name',
			'email' => 'email',
			'website' => 'website',
			'location' => 'location',
			'occupation' => 'occupation',
			'interests' => 'interests',
			'phone' => 'phone',
		);

		$user_sn_im_array = get_user_sn_im_array();
		foreach ($user_sn_im_array as $k => $v)
		{
			$strip_var_list[$v['form']] = $v['form'];
		}

		// Strip all tags from data ... may p**s some people off, bah, strip_tags is doing the job but can still break HTML output ... have no choice, have to use htmlspecialchars ... be prepared to be moaned at.
		while(list($var, $param) = @each($strip_var_list))
		{
			$$var = request_post_var($param, '', true);
		}

		validate_optional_fields($icq, $aim, $msn, $yim, $skype, $website, $location, $occupation, $interests, $phone, $selfdes, $signature);

		$gender = request_post_var('gender', 0);
		$birthday = request_post_var('birthday', 0);
		$birthday_day = request_post_var('b_day', 0);
		$birthday_month = request_post_var('b_md', 0);
		$birthday_year = request_post_var('b_year', 0);

		if (!empty($birthday))
		{
			$birthday_day = realdate('j', $birthday);
			$birthday_month = realdate('n', $birthday);
			$birthday_year = realdate('Y', $birthday);
		}
		else
		{
			$birthday = mkrealdate($birthday_day, $birthday_month, $birthday_year);
		}
		$next_birthday_greeting = request_post_var('next_birthday_greeting', 0);

		$allowviewonline = request_post_var('hideonline', 0);
		$allowviewonline = !empty($allowviewonline) ? 0 : 1;
		$profile_view_popup = request_post_var('profile_view_popup', 0);
		$viewemail = request_post_var('viewemail', 0);
		$allowmassemail = request_post_var('allowmassemail', 1);
		$allowpmin = request_post_var('allowpmin', 1);
		$notifyreply = request_post_var('notifyreply', 0);
		$notifypm = request_post_var('notifypm', 1);
		$popup_pm = request_post_var('popup_pm', 1);
		$attachsig = request_post_var('attachsig', 0);
		$setbm = request_post_var('setbm', 0);
		$user_showavatars = request_post_var('user_showavatars', 1);
		$user_showsignatures = request_post_var('user_showsignatures', 1);
		$user_allowswearywords = request_post_var('user_allowswearywords', 0);

		$user_topics_per_page = request_post_var('user_topics_per_page', $config['topics_per_page']);
		$user_posts_per_page = request_post_var('user_posts_per_page', $config['posts_per_page']);
		$user_hot_threshold = request_post_var('user_hot_threshold', $config['hot_threshold']);

		$user_topics_per_page = ($user_topics_per_page > 100) ? 100 : $user_topics_per_page;
		$user_posts_per_page = ($user_posts_per_page > 50) ? 50 : $user_posts_per_page;
		$user_hot_threshold = ($user_posts_per_page > 50) ? 50 : $user_posts_per_page;

		$user_topic_show_days = (!empty($this_userdata['user_topic_show_days']) ? $this_userdata['user_topic_show_days'] : 0);
		$user_topic_sortby_type = (!empty($this_userdata['user_topic_sortby_type']) ? $this_userdata['user_topic_sortby_type'] : 't');
		$user_topic_sortby_dir = (!empty($this_userdata['user_topic_sortby_dir']) ? $this_userdata['user_topic_sortby_dir'] : 'd');
		$user_post_show_days = (!empty($this_userdata['user_post_show_days']) ? $this_userdata['user_post_show_days'] : 0);
		$user_post_sortby_type = (!empty($this_userdata['user_post_sortby_type']) ? $this_userdata['user_post_sortby_type'] : 't');
		$user_post_sortby_dir = (!empty($this_userdata['user_post_sortby_dir']) ? $this_userdata['user_post_sortby_dir'] : 'a');

		$user_topic_show_days = request_post_var('user_topic_show_days', $user_topic_show_days);
		$user_topic_sortby_type = request_post_var('user_topic_sortby_type', $user_topic_sortby_type);
		$user_topic_sortby_dir = request_post_var('user_topic_sortby_dir', $user_topic_sortby_dir);
		$user_post_show_days = request_post_var('user_post_show_days', $user_post_show_days);
		$user_post_sortby_type = request_post_var('user_post_sortby_type', $user_post_sortby_type);
		$user_post_sortby_dir = request_post_var('user_post_sortby_dir', $user_post_sortby_dir);

		$allowhtml = request_post_var('allowhtml', $config['allow_html']);
		$allowbbcode = request_post_var('allowbbcode', $config['allow_bbcode']);
		$allowsmilies = request_post_var('allowsmilies', $config['allow_smilies']);

		$user_style = request_post_var('style', $config['default_style']);
		$user_lang = request_post_var('language', $config['default_lang']);
		$user_flag = request_post_var('user_flag', '');

		$user_timezone = request_post_var('timezone', $config['board_timezone']);
		$time_mode = request_post_var('time_mode', $config['default_time_mode']);
		$dst_time_lag = request_post_var('dst_time_lag', $config['default_dst_time_lag']);
		$user_dateformat = request_post_var('dateformat', $config['default_dateformat']);

		$user_avatar_local = (isset($_POST['avatarselect']) && !empty($_POST['submitavatar']) && $config['allow_avatar_local']) ? request_post_var('avatarselect', '') : request_post_var('avatarlocal', '');
		$user_avatar_category = (isset($_POST['avatarcatname']) && $config['allow_avatar_local']) ? request_post_var('avatarcatname', '') : '' ;
		$user_avatar_remoteurl = request_post_var('avatarremoteurl', '');
		$user_avatar_url = request_post_var('avatarurl', '');
		$user_avatar_loc = ($_FILES['avatar']['tmp_name'] != 'none') ? $_FILES['avatar']['tmp_name'] : '';
		$user_avatar_name = (!empty($_FILES['avatar']['name'])) ? $_FILES['avatar']['name'] : '';
		$user_avatar_size = (!empty($_FILES['avatar']['size'])) ? $_FILES['avatar']['size'] : 0;
		$user_avatar_filetype = (!empty($_FILES['avatar']['type'])) ? $_FILES['avatar']['type'] : '';
		$user_gravatar = request_post_var('gravatar', '');

		$user_avatar = (empty($user_avatar_loc)) ? $this_userdata['user_avatar'] : '';
		$user_avatar_type = (empty($user_avatar_loc)) ? $this_userdata['user_avatar_type'] : '';

		$user_status = request_post_var('user_status', 0);
		$user_mask = request_post_var('user_mask', 0);
		$user_mask = (!empty($user_status) ? 0 : $user_mask);
		$user_ycard = request_post_var('user_ycard', 0);
		$user_login_attempts = request_post_var('user_login_attempts', 0);

		$user_allowpm = request_post_var('user_allowpm', 0);
		$user_rank = request_post_var('user_rank', 0);
		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_rank2 = request_post_var('user_rank2', 0);
		$user_rank3 = request_post_var('user_rank3', 0);
		$user_rank4 = request_post_var('user_rank4', 0);
		$user_rank5 = request_post_var('user_rank5', 0);
		// Mighty Gorgon - Multiple Ranks - END
		$user_allowavatar = request_post_var('user_allowavatar', 0);
		$user_posts = request_post_var('user_posts', 0);

		$user_group_id = request_post_var('group_id', '0');
		$user_color = check_valid_color(request_post_var('user_color', ''));
		$user_color = (!empty($user_color) ? $user_color : '');

		if ($user_group_id > 0)
		{
			$sql = "SELECT g.group_color, g.group_rank
							FROM " . GROUPS_TABLE . " as g
							WHERE g.group_id = '" . $user_group_id . "'
							LIMIT 1";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$user_color = ($row['group_color'] != '') ? $row['group_color'] : $user_color;
			$user_rank = ($row['user_rank'] != 0) ? $row['user_rank'] : $user_rank;
			$db->sql_freeresult($result);
		}

// UPI2DB - BEGIN
		$user_upi2db_disable = request_post_var('user_upi2db_disable', 0);
// UPI2DB - END

		if(isset($_POST['avatargallery']) || isset($_POST['submitavatar']) || isset($_POST['cancelavatar']))
		{
			$password = '';
			$password_confirm = '';

			if (!isset($_POST['cancelavatar']))
			{
				$user_avatar = $user_avatar_category . '/' . $user_avatar_local;
				$user_avatar_type = USER_AVATAR_GALLERY;
			}
		}
	}

	// PROFILE EDIT BRIDGE - BEGIN
	$target_profile_data = array(
		'user_id' => '',
		'username' => '',
		'first_name' => '',
		'last_name' => '',
		'password' => '',
		'email' => ''
	);
	// PROFILE EDIT BRIDGE - END

	if(isset($_POST['submit']))
	{
		include(IP_ROOT_PATH . 'includes/usercp_avatar.' . PHP_EXT);

		$error = false;

		if ($username != $this_userdata['username'])
		{
			unset($rename_user);

			if (strtolower($username) != strtolower($this_userdata['username']))
			{
				$result = validate_username($username);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
				}
				elseif (strtolower($db->sql_escape($username)) == strtolower($user->data['username']))
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Username_taken'];
				}
			}

			if (!$error)
			{
				$username_sql = "username = '" . $db->sql_escape($username) . "', username_clean = '" . $db->sql_escape(utf8_clean_string($username)) . "', ";
				$rename_user = $username; // Used for renaming usergroup
			}
		}
		// PROFILE EDIT BRIDGE - BEGIN
		$target_profile_data['username'] = $username;
		// PROFILE EDIT BRIDGE - END

		// Custom Profile Fields MOD - BEGIN
		$profile_data = get_fields();
		$profile_names = array();

		foreach($profile_data as $fields)
		{
			$name = text_to_column($fields['field_name']);
			$type = $fields['field_type'];
			$required = $fields['is_required'] == REQUIRED ? true : false;

			// Mighty Gorgon: maybe better using the old way instead of request_var... unless we decide to rewrite the way this mod works
			//$temp = request_post_var($name, '', true);
			$temp = (isset($_POST[$name])) ? $_POST[$name] : array();
			if($type == CHECKBOX)
			{
				$temp2 = '';
				if (!empty($temp))
				{
					foreach($temp as $temp3)
					{
						$temp2 .= htmlspecialchars($temp3) . ',';
					}
					$temp2 = substr($temp2, 0, strlen($temp2) - 1);
				}
				$temp = $temp2;
			}
			else
				//$temp = is_numeric($temp) ? intval($temp) : htmlspecialchars($temp);
				$temp = is_numeric($temp) ? intval($temp) : (is_array($temp) ? array_map('htmlspecialchars', $temp) : htmlspecialchars($temp));
				$profile_names[$name] = $temp;

				if($required && empty($profile_names[$name]))
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
				}
		}
		// Custom Profile Fields MOD - END

		$passwd_sql = '';
		if(!empty($password) && !empty($password_confirm))
		{
			// Awww, the user wants to change their password, isn't that cute..
			if($password != $password_confirm)
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Password_mismatch'];
			}
			else
			{
				// PROFILE EDIT BRIDGE - BEGIN
				$target_profile_data['password'] = $password;
				// PROFILE EDIT BRIDGE - END
				$password = phpbb_hash($password);
				$passwd_sql = "user_password = '" . $db->sql_escape($password) . "', " . ((strlen($password) == 34) ? "user_pass_convert = 0, " : "");
			}
		}
		elseif($password && !$password_confirm)
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Password_mismatch'];
		}
		elseif(!$password && $password_confirm)
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Password_mismatch'];
		}

		if ($signature != '')
		{
			$sig_length_check = preg_replace('/(\[.*?)(=.*?)\]/is', '\\1]', $signature);
			if ($allowhtml)
			{
				$sig_length_check = preg_replace('/(\<.*?)(=.*?)(.*?=.*?)?([ \/]?\>)/is', '\\1\\3\\4', $sig_length_check);
			}

			$signature = prepare_message($signature, $allowhtml, $allowbbcode, $allowsmilies);

			if (strlen($sig_length_check) > $config['max_sig_chars'])
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Signature_too_long'];
			}
		}
		if (preg_match("/[^0-9]/", $_POST['dst_time_lag']) || ($dst_time_lag < 0) || ($dst_time_lag > 120))
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['dst_time_lag_error'];
		}

		// Avatar stuff
		$avatar_sql = '';
		if(isset($_POST['avatardel']))
		{
			if($this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "")
			{
				if(@file_exists(@phpbb_realpath('./../' . $config['avatar_path'] . "/" . $this_userdata['user_avatar'])))
				{
					@unlink('./../' . $config['avatar_path'] . "/" . $this_userdata['user_avatar']);
				}
			}
			$avatar_sql = ", user_avatar = '', user_avatar_type = " . USER_AVATAR_NONE;
		}
		elseif(($user_avatar_loc != "" || !empty($user_avatar_url)) && !$error)
		{
			// Only allow one type of upload, either a filename or a URL
			if(!empty($user_avatar_loc) && !empty($user_avatar_url))
			{
				$error = true;
				if(isset($error_msg))
				{
					$error_msg .= '<br />';
				}
				$error_msg .= $lang['Only_one_avatar'];
			}

			if($user_avatar_loc != '')
			{
				if(@file_exists(@phpbb_realpath($user_avatar_loc)) && preg_match("/.jpg$|.gif$|.png$/", $user_avatar_name))
				{
					if(($user_avatar_size <= $config['avatar_filesize']) && ($user_avatar_size > 0))
					{
						$error_type = false;

						//
						// Opera appends the image name after the type, not big, not clever!
						//
						preg_match("'image\/[x\-]*([a-z]+)'", $user_avatar_filetype, $user_avatar_filetype);
						$user_avatar_filetype = $user_avatar_filetype[1];

						switch($user_avatar_filetype)
						{
							case 'jpeg':
							case 'pjpeg':
							case 'jpg':
								$imgtype = '.jpg';
								break;
							case 'gif':
								$imgtype = '.gif';
								break;
							case 'png':
								$imgtype = '.png';
								break;
							default:
								$error = true;
								$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
								break;
						}

						if(!$error)
						{
							list($width, $height) = @getimagesize($user_avatar_loc);

							if($width <= $config['avatar_max_width'] && $height <= $config['avatar_max_height'])
							{
								$user_id = $this_userdata['user_id'];

								$avatar_filename = $user_id . $imgtype;

								if($this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "")
								{
									if(@file_exists(@phpbb_realpath('./../' . $config['avatar_path'] . "/" . $this_userdata['user_avatar'])))
									{
										@unlink('./../' . $config['avatar_path'] . "/" . $this_userdata['user_avatar']);
									}
								}
								@copy($user_avatar_loc, './../' . $config['avatar_path'] . "/$avatar_filename");

								$avatar_sql = ", user_avatar = '" . $avatar_filename . "', user_avatar_type = " . USER_AVATAR_UPLOAD;
							}
							else
							{
								$l_avatar_size = sprintf($lang['Avatar_imagesize'], $config['avatar_max_width'], $config['avatar_max_height']);

								$error = true;
								$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
							}
						}
					}
					else
					{
						$l_avatar_size = sprintf($lang['Avatar_filesize'], round($config['avatar_filesize'] / 1024));

						$error = true;
						$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
					}
				}
				else
				{
					$error = true;
					$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
				}
			}
			elseif(!empty($user_avatar_url))
			{
				// First check what port we should connect to, look for a :[xxxx]/ or, if that doesn't exist assume port 80 (http)
				preg_match("/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/", $user_avatar_url, $url_ary);

				if(!empty($url_ary[4]))
				{
					$port = (!empty($url_ary[3])) ? $url_ary[3] : 80;

					$fsock = @fsockopen($url_ary[2], $port, $errno, $errstr);
					if($fsock)
					{
						$base_get = "/" . $url_ary[4];

						// Uses HTTP 1.1, could use HTTP 1.0 ...
						@fwrite($fsock, "GET $base_get HTTP/1.1\r\n");
						@fwrite($fsock, "HOST: " . $url_ary[2] . "\r\n");
						@fwrite($fsock, "Connection: close\r\n\r\n");

						unset($avatar_data);
						while(!@feof($fsock))
						{
							$avatar_data .= @fread($fsock, $config['avatar_filesize']);
						}
						@fclose($fsock);

						if(preg_match("/Content-Length\: ([0-9]+)[^\/ ][\s]+/i", $avatar_data, $file_data1) && preg_match("/Content-Type\: image\/[x\-]*([a-z]+)[\s]+/i", $avatar_data, $file_data2))
						{
							$file_size = $file_data1[1];
							$file_type = $file_data2[1];

							switch($file_type)
							{
								case 'jpeg':
								case 'pjpeg':
								case 'jpg':
									$imgtype = '.jpg';
									break;
								case 'gif':
									$imgtype = '.gif';
									break;
								case 'png':
									$imgtype = '.png';
									break;
								default:
									$error = true;
									$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
									break;
							}

							if(!$error && $file_size > 0 && $file_size < $config['avatar_filesize'])
							{
								$avatar_data = substr($avatar_data, strlen($avatar_data) - $file_size, $file_size);

								$tmp_filename = tempnam('/tmp', $this_userdata['user_id'] . '-');
								$fptr = @fopen($tmp_filename, 'wb');
								$bytes_written = @fwrite($fptr, $avatar_data, $file_size);
								@fclose($fptr);

								if($bytes_written == $file_size)
								{
									list($width, $height) = @getimagesize($tmp_filename);

									if($width <= $config['avatar_max_width'] && $height <= $config['avatar_max_height'])
									{
										$user_id = $this_userdata['user_id'];

										$avatar_filename = $user_id . $imgtype;

										if($this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "")
										{
											if(file_exists(@phpbb_realpath('./../' . $config['avatar_path'] . "/" . $this_userdata['user_avatar'])))
											{
												@unlink('./../' . $config['avatar_path'] . "/" . $this_userdata['user_avatar']);
											}
										}
										@copy($tmp_filename, './../' . $config['avatar_path'] . "/$avatar_filename");
										@unlink($tmp_filename);

										$avatar_sql = ", user_avatar = '" . $db->sql_escape($avatar_filename) . "', user_avatar_type = " . USER_AVATAR_UPLOAD;
									}
									else
									{
										$l_avatar_size = sprintf($lang['Avatar_imagesize'], $config['avatar_max_width'], $config['avatar_max_height']);

										$error = true;
										$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
									}
								}
								else
								{
									// Error writing file
									@unlink($tmp_filename);
									message_die(GENERAL_ERROR, "Could not write avatar file to local storage. Please contact the board administrator with this message", "", __LINE__, __FILE__);
								}
							}
						}
						else
						{
							// No data
							$error = true;
							$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['File_no_data'] : $lang['File_no_data'];
						}
					}
					else
					{
						// No connection
						$error = true;
						$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['No_connection_URL'] : $lang['No_connection_URL'];
					}
				}
				else
				{
					$error = true;
					$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Incomplete_URL'] : $lang['Incomplete_URL'];
				}
			}
			elseif(!empty($user_avatar_name))
			{
				$l_avatar_size = sprintf($lang['Avatar_filesize'], round($config['avatar_filesize'] / 1024));

				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
			}
		}
		elseif(($user_avatar_remoteurl != '') && ($avatar_sql == '') && !$error)
		{
			if(!preg_match("#^http:\/\/#i", $user_avatar_remoteurl))
			{
				$user_avatar_remoteurl = "http://" . $user_avatar_remoteurl;
			}

			if(preg_match("#^(http:\/\/[a-z0-9\-]+?\.([a-z0-9\-]+\.)*[a-z]+\/.*?\.(gif|jpg|png)$)#is", $user_avatar_remoteurl))
			{
				$avatar_sql = ", user_avatar = '" . $db->sql_escape($user_avatar_remoteurl) . "', user_avatar_type = " . USER_AVATAR_REMOTE;
			}
			else
			{
				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Wrong_remote_avatar_format'] : $lang['Wrong_remote_avatar_format'];
			}
		}
		elseif($user_avatar_local != "" && $avatar_sql == "" && !$error)
		{
			$avatar_sql = ", user_avatar = '" . $db->sql_escape(ltrim(basename($user_avatar_category), "'") . '/' . ltrim(basename($user_avatar_local), "'")) . "', user_avatar_type = " . USER_AVATAR_GALLERY;
		}
		elseif(($user_gravatar != '') && ($avatar_sql == '') && !$error)
		{
			$avatar_sql = ", user_avatar = '" . $db->sql_escape($user_gravatar) . "', user_avatar_type = " . USER_GRAVATAR;
		}

		// Update users post count
		$user_posts = request_var('user_posts', 0);

		// BIRTHDAY - BEGIN
		// find the birthday values, reflected by the $lang['Submit_date_format']
		if ($birthday_day || $birthday_month || $birthday_year) //if a birthday is submited, then validate it
		{
			$user_age = (gmdate('md') >= $birthday_month . (($birthday_day <= 9) ? '0' : '') . $birthday_day) ? gmdate('Y') - $birthday_year : gmdate('Y') - $birthday_year - 1;
			// Check date, maximum / minimum user age
			if (!checkdate($birthday_month,$birthday_day,$birthday_year))
			{
				$error = true;
				if(isset($error_msg))$error_msg .= '<br />';
				$error_msg .= $lang['Wrong_birthday_format'];
			}
			elseif ($user_age>$config['max_user_age'])
			{
				$error = true;
				if(isset($error_msg))$error_msg .= '<br />';
				$error_msg .= sprintf($lang['Birthday_to_high'], $config['max_user_age']);
			}
			elseif ($user_age<$config['min_user_age'])
			{
				$error = true;
				if(isset($error_msg))$error_msg .= '<br />';
				$error_msg .= sprintf($lang['Birthday_to_low'], $config['min_user_age']);
			}
			else
			{
				$birthday = ($error) ? $birthday : mkrealdate($birthday_day, $birthday_month, $birthday_year);
			}
		}
		else
		{
			$birthday = ($error) ? '' : 999999;
		}
		// BIRTHDAY - END

		// Update entry in DB
		if(!$error)
		{
			$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
			if ($user_ycard > $config['max_user_bancard'])
			{
				$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . " WHERE ban_userid = '" . $user_id . "'";
				$result = $db->sql_query($sql);
				if ((!$db->sql_fetchrowset($result)) && ($user_id != ANONYMOUS) && ($user_id != $founder_id))
				{
					// insert the user in the ban list
					$ban_insert_array = array(
						'ban_userid' => $user_id,
						'ban_by_userid' => $user->data['user_id'],
						'ban_start' => time()
					);
					$sql = "INSERT INTO " . BANLIST_TABLE . " " . $db->sql_build_insert_update($ban_insert_array, true);
					$result = $db->sql_query($sql);
					$no_error_ban = true;
				}
				else
				{
					$no_error_ban = true;
				}
			}
			else
			{
				// remove the ban, if there is any
				$sql = "DELETE FROM " . BANLIST_TABLE . " WHERE ban_userid = $user_id";
				$result = $db->sql_query($sql);
				$no_error_ban = true;
			}

			$db->clear_cache('ban_', USERS_CACHE_FOLDER);
			clear_user_color_cache($user_id);

			// PROFILE EDIT BRIDGE - BEGIN
			$target_profile_data['user_id'] = $user_id;
			$target_profile_data['email'] = $email;
			$target_profile_data['first_name'] = $user_first_name;
			$target_profile_data['last_name'] = $user_last_name;
			// PROFILE EDIT BRIDGE - END

			$sn_im_sql = '';
			$user_sn_im_array = get_user_sn_im_array();
			foreach ($user_sn_im_array as $k => $v)
			{
				$sn_im_sql .= ", " . $v['field'] . " = '" . $db->sql_escape(str_replace(' ', '+', trim($$v['form']))) . "'";
			}

			$sql = "UPDATE " . USERS_TABLE . "
				SET " . $username_sql . $passwd_sql . "user_email = '" . $db->sql_escape($email) . "', user_email_hash = '" . $db->sql_escape(phpbb_email_hash($email)) . "'" . $sn_im_sql . ", user_website = '" . $db->sql_escape($website) . "', user_occ = '" . $db->sql_escape($occupation) . "', user_from = '" . $db->sql_escape($location) . "', user_from_flag = '$user_flag', user_first_name = '" . $db->sql_escape($user_first_name) . "', user_last_name = '" . $db->sql_escape($user_last_name) . "', user_interests = '" . $db->sql_escape($interests) . "', user_phone = '" . $db->sql_escape($phone) . "', user_selfdes = '" . $db->sql_escape($selfdes) . "', user_profile_view_popup = $profile_view_popup, user_birthday = '$birthday', user_birthday_y = '$birthday_year', user_birthday_m = '$birthday_month', user_birthday_d = '$birthday_day', user_next_birthday_greeting = $next_birthday_greeting, user_sig = '" . $db->sql_escape($signature) . "', user_allow_viewemail = $viewemail, user_attachsig = $attachsig, user_setbm = $setbm, user_allowswearywords = $user_allowswearywords, user_showavatars = $user_showavatars, user_showsignatures = $user_showsignatures, user_allowsmile = $allowsmilies, user_allowhtml = $allowhtml, user_allowavatar = $user_allowavatar, user_upi2db_disable = $user_upi2db_disable, user_allowbbcode = $allowbbcode, user_allow_mass_email = $allowmassemail, user_allow_pm_in = $allowpmin, user_allow_viewonline = $allowviewonline, user_notify = $notifyreply, user_allow_pm = $user_allowpm, user_notify_pm = $notifypm, user_popup_pm = $popup_pm, user_lang = '" . $db->sql_escape($user_lang) . "', user_style = $user_style, user_posts = $user_posts, user_timezone = '" . $db->sql_escape($user_timezone) . "', user_time_mode = '" . $db->sql_escape($time_mode) . "', user_dst_time_lag = '" . $db->sql_escape($dst_time_lag) . "', user_dateformat = '" . $db->sql_escape($user_dateformat) . "', user_posts_per_page = '" . $db->sql_escape($user_posts_per_page) . "', user_topics_per_page = '" . $db->sql_escape($user_topics_per_page) . "', user_hot_threshold = '" . $db->sql_escape($user_hot_threshold) . "', user_topic_show_days = '" . $db->sql_escape($user_topic_show_days) . "', user_topic_sortby_type = '" . $db->sql_escape($user_topic_sortby_type) . "', user_topic_sortby_dir = '" . $db->sql_escape($user_topic_sortby_dir) . "', user_post_show_days = '" . $db->sql_escape($user_post_show_days) . "', user_post_sortby_type = '" . $db->sql_escape($user_post_sortby_type) . "', user_post_sortby_dir = '" . $db->sql_escape($user_post_sortby_dir) . "', user_active = $user_status, user_mask = $user_mask, user_warnings = $user_ycard, user_login_attempts = $user_login_attempts, user_gender = '$gender', user_rank = '" . $user_rank . "', user_rank2 = '" . $user_rank2 . "', user_rank3 = '" . $user_rank3 . "', user_rank4 = '" . $user_rank4 . "', user_rank5 = '" . $user_rank5 . "', group_id = '" . $user_group_id . "', user_color = '" . $user_color . "'" . $avatar_sql . "
				WHERE user_id = '" . $user_id . "'";
			$result = $db->sql_query($sql);

			if(isset($rename_user))
			{
				$sql = "UPDATE " . GROUPS_TABLE . "
					SET group_name = '" . $db->sql_escape($rename_user) . "'
					WHERE group_name = '" . str_replace("'", "''", $this_userdata['username']) . "'";
				$result = $db->sql_query($sql);
			}

			// Delete user session, to prevent the user navigating the forum (if logged in) when disabled
			if (empty($user_status))
			{
				$sql = "DELETE FROM " . SESSIONS_TABLE . "
					WHERE session_user_id = " . $user_id;
				$db->sql_query($sql);
			}

			// We remove all stored login keys since the password has been updated and change the current one (if applicable)
			if (!empty($passwd_sql))
			{
				$user->reset_login_keys($user_id);
			}

			// Custom Profile Fields - BEGIN
			$profile_data = get_fields();
			$profile_names = array();
			if ($profile_data)
			{
				$sql2 = "UPDATE " . USERS_TABLE . "
					SET ";
				foreach($profile_data as $fields)
				{
					$name = text_to_column($fields['field_name']);
					$type = $fields['field_type'];
					$required = $fields['is_required'] == REQUIRED ? true : false;

					// Mighty Gorgon: maybe better using the old way instead of request_var... unless we decide to rewrite the way this mod works
					//$temp = request_post_var($name, '', true);
					$temp = (isset($_POST[$name])) ? $_POST[$name] : array();
					if($type == CHECKBOX)
					{
						$temp2 = '';
						if (!empty($temp))
						{
							foreach($temp as $temp3)
							{
								$temp2 .= htmlspecialchars($temp3) . ',';
							}
							$temp2 = substr($temp2, 0, strlen($temp2) - 1);
						}
						$temp = $temp2;
					}
					else
					{
						//$temp = is_numeric($temp) ? intval($temp) : htmlspecialchars($temp);
						$temp = is_numeric($temp) ? intval($temp) : (is_array($temp) ? array_map('htmlspecialchars', $temp) : htmlspecialchars($temp));
					}
					$profile_names[$name] = $temp;

					$sql2 .= $name . " = '" . $db->sql_escape($profile_names[$name]) . "', ";
				}
				$sql2 = substr($sql2, 0, strlen($sql2) - 2) . " WHERE user_id = " . $this_userdata['user_id'];
				$db->sql_query($sql2);
			}
			// Custom Profile Fields - END

			update_user_posts_details($this_userdata['user_id'], $user_color, '', true, true);

			// Delete forums/topics notifications if user has been deactivated
			if (empty($user_status))
			{
				if (!class_exists('class_notifications'))
				{
					include(IP_ROOT_PATH . 'includes/class_notifications.' . PHP_EXT);
					$class_notifications = new class_notifications();
				}
				$class_notifications->delete_user_notifications($poster_id);
			}

			// PROFILE EDIT BRIDGE - BEGIN
			if (!class_exists('class_users'))
			{
				include_once(IP_ROOT_PATH . 'includes/class_users.' . PHP_EXT);
			}
			if (empty($class_users))
			{
				$class_users = new class_users();
			}
			$class_users->profile_update($target_profile_data);
			unset($target_profile_data);
			// PROFILE EDIT BRIDGE - END

			$message .= $lang['Admin_user_updated'];

			$message .= '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid('admin_users.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
			//Start Quick Administrator User Options and Information MOD
			if($redirect != '')
			{
				$message = $lang['Admin_user_updated'] . '<br /><br />' . sprintf($lang['Click_return_userprofile'], '<a href="' . append_sid('../' . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT) . '">', '</a>');
			}
			//End Quick Administrator User Options and Information MOD

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$template->set_filenames(array('reg_header' => 'error_body.tpl'));

			$template->assign_vars(array(
				'ERROR_MESSAGE' => $error_msg
				)
			);

			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');

			$username = htmlspecialchars(stripslashes($username));
			$email = $email;
			$password = '';
			$password_confirm = '';

			$aim = str_replace('+', ' ', $aim);

			$selfdes = htmlspecialchars($selfdes);
			$signature = htmlspecialchars($signature);
		}
	}
	elseif(!isset($_POST['submit']) && ($mode != 'save') && !isset($_POST['avatargallery']) && !isset($_POST['submitavatar']) && !isset($_POST['cancelavatar']))
	{
		if(isset($_GET[POST_USERS_URL]) || isset($_POST[POST_USERS_URL]))
		{
			$user_id = request_var(POST_USERS_URL, 0);
			$this_userdata = get_userdata($user_id);
		}
		else
		{
			$username_temp = request_var('username', '', true);
			$this_userdata = get_userdata($username_temp, true);
		}
		if(empty($this_userdata))
		{
			if (!defined('STATUS_404')) define('STATUS_404', true);
			message_die(GENERAL_MESSAGE, 'NO_USER');
		}

		// Now parse and display it as a template
		$user_id = $this_userdata['user_id'];
		$username = $this_userdata['username'];
		$user_first_name = $this_userdata['user_first_name'];
		$user_last_name = $this_userdata['user_last_name'];
		$email = $this_userdata['user_email'];
		$password = '';
		$password_confirm = '';

		$user_sn_im_array = get_user_sn_im_array();
		foreach ($user_sn_im_array as $k => $v)
		{
			$$v['form'] = $this_userdata[$v['field']];
		}

		$website = $this_userdata['user_website'];
		$location = $this_userdata['user_from'];
		$user_flag = $this_userdata['user_from_flag'];
		$phone = $this_userdata['user_phone'];
		$occupation = $this_userdata['user_occ'];
		$interests = $this_userdata['user_interests'];
		$next_birthday_greeting = $this_userdata['user_next_birthday_greeting'];
		if ($this_userdata['user_birthday'] != 999999)
		{
			$birthday = realdate($lang['Submit_date_format'], $this_userdata['user_birthday']);
			$birthday_day = realdate('j', $this_userdata['user_birthday']);
			$birthday_month = realdate('n', $this_userdata['user_birthday']);
			$birthday_year = realdate('Y', $this_userdata['user_birthday']);
		}
		else
		{
			$birthday_day = '';
			$birthday_month = '';
			$birthday_year = '';
			$birthday = '';
		}
		$gender = $this_userdata['user_gender'];
		// Start replacement - BBCodes & smilies enhancement MOD
		$signature = htmlspecialchars($this_userdata['user_sig']);
		$selfdes = htmlspecialchars($this_userdata['user_selfdes']);
		// End replacement - BBCodes & smilies enhancement MOD

		// This should not be needed anymore...
		//$signature = preg_replace($html_entities_match, $html_entities_replace, $signature);

		$viewemail = $this_userdata['user_allow_viewemail'];
		$allowmassemail = $this_userdata['user_allow_mass_email'];
		$allowpmin = $this_userdata['user_allow_pm_in'];
		$notifypm = $this_userdata['user_notify_pm'];
		$popup_pm = $this_userdata['user_popup_pm'];
		$notifyreply = $this_userdata['user_notify'];
		$attachsig = $this_userdata['user_attachsig'];
		$setbm = $this_userdata['user_setbm'];
		$user_showavatars = $this_userdata['user_showavatars'];
		$user_showsignatures = $this_userdata['user_showsignatures'];
		$user_allowswearywords = $this_userdata['user_allowswearywords'];

		$user_topics_per_page = $this_userdata['user_topics_per_page'];
		$user_posts_per_page = $this_userdata['user_posts_per_page'];
		$user_hot_threshold = $this_userdata['user_hot_threshold'];

		$user_topics_per_page = ($user_topics_per_page > 100) ? 100 : $user_topics_per_page;
		$user_posts_per_page = ($user_posts_per_page > 50) ? 50 : $user_posts_per_page;
		$user_hot_threshold = ($user_posts_per_page > 50) ? 50 : $user_posts_per_page;

		$user_topic_show_days = (!empty($this_userdata['user_topic_show_days']) ? $this_userdata['user_topic_show_days'] : 0);
		$user_topic_sortby_type = (!empty($this_userdata['user_topic_sortby_type']) ? $this_userdata['user_topic_sortby_type'] : 't');
		$user_topic_sortby_dir = (!empty($this_userdata['user_topic_sortby_dir']) ? $this_userdata['user_topic_sortby_dir'] : 'd');
		$user_post_show_days = (!empty($this_userdata['user_post_show_days']) ? $this_userdata['user_post_show_days'] : 0);
		$user_post_sortby_type = (!empty($this_userdata['user_post_sortby_type']) ? $this_userdata['user_post_sortby_type'] : 't');
		$user_post_sortby_dir = (!empty($this_userdata['user_post_sortby_dir']) ? $this_userdata['user_post_sortby_dir'] : 'a');

		$allowhtml = $this_userdata['user_allowhtml'];
		$allowbbcode = $this_userdata['user_allowbbcode'];
		$allowsmilies = $this_userdata['user_allowsmile'];
		$allowviewonline = $this_userdata['user_allow_viewonline'];
		$profile_view_popup = $this_userdata['user_profile_view_popup'];

		$user_avatar = $this_userdata['user_avatar'];
		$user_avatar_type = $this_userdata['user_avatar_type'];
		$user_style = $this_userdata['user_style'];
		$user_lang = $this_userdata['user_lang'];
		$user_timezone = $this_userdata['user_timezone'];
		$time_mode = $this_userdata['user_time_mode'];
		$dst_time_lag = $this_userdata['user_dst_time_lag'];
		$user_dateformat = $this_userdata['user_dateformat'];

		$user_status = $this_userdata['user_active'];
		$user_mask = $this_userdata['user_mask'];
		$user_ycard = $this_userdata['user_warnings'];
		$user_login_attempts = $this_userdata['user_login_attempts'];

		$user_allowavatar = $this_userdata['user_allowavatar'];
// UPI2DB - BEGIN
		$user_upi2db_disable = $this_userdata['user_upi2db_disable'];
// UPI2DB - BEGIN
		$user_allowpm = $this_userdata['user_allow_pm'];
		$user_posts = $this_userdata['user_posts'];

		$coppa = false;

		$html_status = ($this_userdata['user_allowhtml']) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
		$bbcode_status = ($this_userdata['user_allowbbcode']) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
		$smilies_status = ($this_userdata['user_allowsmile']) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
	}

	if(isset($_POST['avatargallery']) && !$error)
	{
		if(!$error)
		{
			include(IP_ROOT_PATH . 'includes/usercp_avatar.' . PHP_EXT);

			$user_id = $selected_user_id;
			$this_userdata = get_userdata($user_id);
			$username = empty($username) ? $this_userdata['username'] : $username;
			$interests = empty($interests) ? $this_userdata['user_interests'] : $interests;

			$template->set_filenames(array('body' => ADM_TPL . 'user_avatar_gallery.tpl'));

			$avatar_category = request_post_var('avatarcategory', '');

			// Replaced: $aim, $facebook, $flickr, $googleplus, $icq, $jabber, $linkedin, $msn, $skype, $twitter, $yim, $youtube,
			$user_sn_im_array = get_user_sn_im_array();
			foreach ($user_sn_im_array as $k => $v)
			{
				$this_user_im[$v['form']] = $$v['form'];
			}

			display_avatar_gallery($mode, $avatar_category, $user_id, $email, $current_email, $email_confirm, $coppa, $username, $new_password, $cur_password, $password_confirm, $this_user_im, $website, $location, $user_flag, $user_first_name, $user_last_name, $occupation, $interests, $phone, $selfdes, $signature, $viewemail, $notifypm, $popup_pm, $notifyreply, $attachsig, $setbm, $allowhtml, $allowbbcode, $allowsmilies, $showavatars, $showsignatures, $allowswearywords, $allowmassemail, $allowpmin, $allowviewonline, $user_style, $user_lang, $user_timezone, $time_mode, $dst_time_lag, $user_dateformat, $profile_view_popup, $user->data['session_id'], $birthday, $gender, $upi2db_which_system, $upi2db_new_word, $upi2db_edit_word, $upi2db_unread_color);

			$s_hidden_fields = '';

			$hidden_fields_array = array(
				'mode' => 'edit',
				'agreed' => 'true',
				'avatarcatname' => $avatar_category,
				'coppa' => $coppa,
				'u' => $user_id,
				'id' => $user_id,
				'username' => $username,
				'acp_username' => $username,
				'email' => $email,
				'website' => $website,
				'location' => $location,
				'user_flag' => $user_flag,
				'phone' => $phone,
				'user_first_name' => $user_first_name,
				'user_last_name' => $user_last_name,
				'occupation' => $occupation,
				'interests' => $interests,
				'birthday' => $birthday,
				'next_birthday_greeting' => $next_birthday_greeting,
				'selfdes' => $selfdes,
				'signature' => $signature,
				'viewemail' => $viewemail,
				'allowmassemail' => $allowmassemail,
				'allowpmin' => $allowpmin,
				'gender' => $gender,
				'notifypm' => $notifypm,
				'popup_pm' => $popup_pm,
				'notifyreply' => $notifyreply,
				'attachsig' => $attachsig,
				'setbm' => $setbm,
				'user_showavatars' => $user_showavatars,
				'user_showsignatures' => $user_showsignatures,
				'user_allowswearywords' => $user_allowswearywords,
				'allowhtml' => $allowhtml,
				'allowbbcode' => $allowbbcode,
				'allowsmilies' => $allowsmilies,
				'hideonline' => !$allowviewonline,
				'profile_view_popup' => $profile_view_popup,
				'style' => $user_style,
				'language' => $user_lang,
				'timezone' => $user_timezone,
				'time_mode' => $time_mode,
				'dst_time_lag' => $dst_time_lag,
				'dateformat' => $user_dateformat,
				'user_status' => $user_status,
				'user_mask' => $user_mask,
				'user_ycard' => $user_ycard,
				'user_login_attempts' => $user_login_attempts,
				'user_allowpm' => $user_allowpm,
				'user_allowavatar' => $user_allowavatar,
				'user_topics_per_page' => $user_topics_per_page,
				'user_posts_per_page' => $user_posts_per_page,
				'user_hot_threshold' => $user_hot_threshold,
				'user_topic_show_days' => $user_topic_show_days,
				'user_topic_sortby_type' => $user_topic_sortby_type,
				'user_topic_sortby_dir' => $user_topic_sortby_dir,
				'user_post_show_days' => $user_post_show_days,
				'user_post_sortby_type' => $user_post_sortby_type,
				'user_post_sortby_dir' => $user_post_sortby_dir,
// UPI2DB - BEGIN
				'user_upi2db_disable' => $user_upi2db_disable,
// UPI2DB - END
				'user_posts' => $user_posts,
				'user_rank' => $user_rank,
				'user_rank2' => $user_rank2,
				'user_rank3' => $user_rank3,
				'user_rank4' => $user_rank4,
				'user_rank5' => $user_rank5,
				'group_id' => $user_group_id,
				'user_color' => $user_color,
			);

			$user_sn_im_array = get_user_sn_im_array();
			foreach ($user_sn_im_array as $k => $v)
			{
				$hidden_fields_array[$v['form']] = $$v['form'];
			}

			$s_hidden_fields = build_hidden_fields($hidden_fields_array, false, false);

			$template->assign_vars(array(
				'S_HIDDEN_FIELDS' => $s_hidden_fields
				)
			);
		}
	}
	else
	{
		$s_hidden_fields = '<input type="hidden" name="mode" value="save" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="coppa" value="' . $coppa . '" />';
		$s_hidden_fields .= '<input type="hidden" name="id" value="' . $this_userdata['user_id'] . '" />';
		//Start Quick Administrator User Options and Information MOD
		$s_hidden_fields .= '<input type="hidden" name="redirect" value="' . $redirect .'" />';
		//End Quick Administrator User Options and Information MOD

		if(!empty($user_avatar_local))
		{
			$s_hidden_fields .= '<input type="hidden" name="avatarlocal" value="' . $user_avatar_local . '" /><input type="hidden" name="avatarcatname" value="' . $user_avatar_category . '" />';
		}

		if($user_avatar_type)
		{
			switch($user_avatar_type)
			{
				case USER_AVATAR_UPLOAD:
					$avatar = '<img src="../' . $config['avatar_path'] . '/' . $user_avatar . '" alt="" />';
					break;
				case USER_AVATAR_REMOTE:
					$avatar = '<img src="' . $user_avatar . '" alt="" />';
					break;
				case USER_AVATAR_GALLERY:
					$avatar = '<img src="../' . $config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="" />';
					break;
			}
		}
		else
		{
			$avatar = '';
		}

		$sql = "SELECT * FROM " . RANKS_TABLE . "
			WHERE rank_special = 1
			ORDER BY rank_title";
		$result = $db->sql_query($sql);

		// Mighty Gorgon - Multiple Ranks - BEGIN
		$selected1 = ($this_userdata['user_rank'] == '-2') ? ' selected="selected"' : '';
		$selected2 = ($this_userdata['user_rank2'] == '-2') ? ' selected="selected"' : '';
		$selected3 = ($this_userdata['user_rank3'] == '-2') ? ' selected="selected"' : '';
		$selected4 = ($this_userdata['user_rank4'] == '-2') ? ' selected="selected"' : '';
		$selected5 = ($this_userdata['user_rank5'] == '-2') ? ' selected="selected"' : '';
		$rank1_select_box = '<option value="-2"' . $selected1 . '>' . $lang['No_Rank'] . '</option>';
		$rank2_select_box = '<option value="-2"' . $selected2 . '>' . $lang['No_Rank'] . '</option>';
		$rank3_select_box = '<option value="-2"' . $selected3 . '>' . $lang['No_Rank'] . '</option>';
		$rank4_select_box = '<option value="-2"' . $selected4 . '>' . $lang['No_Rank'] . '</option>';
		$rank5_select_box = '<option value="-2"' . $selected5 . '>' . $lang['No_Rank'] . '</option>';
		$selected1 = ($this_userdata['user_rank'] == '-1') ? ' selected="selected"' : '';
		$selected2 = ($this_userdata['user_rank2'] == '-1') ? ' selected="selected"' : '';
		$selected3 = ($this_userdata['user_rank3'] == '-1') ? ' selected="selected"' : '';
		$selected4 = ($this_userdata['user_rank4'] == '-1') ? ' selected="selected"' : '';
		$selected5 = ($this_userdata['user_rank5'] == '-1') ? ' selected="selected"' : '';
		$rank1_select_box .= '<option value="-1"' . $selected1 . '>' . $lang['Rank_Days_Count'] . '</option>';
		$rank2_select_box .= '<option value="-1"' . $selected2 . '>' . $lang['Rank_Days_Count'] . '</option>';
		$rank3_select_box .= '<option value="-1"' . $selected3 . '>' . $lang['Rank_Days_Count'] . '</option>';
		$rank4_select_box .= '<option value="-1"' . $selected4 . '>' . $lang['Rank_Days_Count'] . '</option>';
		$rank5_select_box .= '<option value="-1"' . $selected5 . '>' . $lang['Rank_Days_Count'] . '</option>';
		$selected1 = ($this_userdata['user_rank'] == '0') ? ' selected="selected"' : '';
		$selected2 = ($this_userdata['user_rank2'] == '0') ? ' selected="selected"' : '';
		$selected3 = ($this_userdata['user_rank3'] == '0') ? ' selected="selected"' : '';
		$selected4 = ($this_userdata['user_rank4'] == '0') ? ' selected="selected"' : '';
		$selected5 = ($this_userdata['user_rank5'] == '0') ? ' selected="selected"' : '';
		$rank1_select_box .= '<option value="0"' . $selected1 . '>' . $lang['Rank_Posts_Count'] . '</option>';
		$rank2_select_box .= '<option value="0"' . $selected2 . '>' . $lang['Rank_Posts_Count'] . '</option>';
		$rank3_select_box .= '<option value="0"' . $selected3 . '>' . $lang['Rank_Posts_Count'] . '</option>';
		$rank4_select_box .= '<option value="0"' . $selected4 . '>' . $lang['Rank_Posts_Count'] . '</option>';
		$rank5_select_box .= '<option value="0"' . $selected5 . '>' . $lang['Rank_Posts_Count'] . '</option>';
		// Mighty Gorgon - Multiple Ranks - END
		while($row = $db->sql_fetchrow($result))
		{
			$rank = $row['rank_title'];
			$rank_id = $row['rank_id'];

			// Mighty Gorgon - Multiple Ranks - BEGIN
			$selected1 = ($this_userdata['user_rank'] == $rank_id) ? ' selected="selected"' : '';
			$selected2 = ($this_userdata['user_rank2'] == $rank_id) ? ' selected="selected"' : '';
			$selected3 = ($this_userdata['user_rank3'] == $rank_id) ? ' selected="selected"' : '';
			$selected4 = ($this_userdata['user_rank4'] == $rank_id) ? ' selected="selected"' : '';
			$selected5 = ($this_userdata['user_rank5'] == $rank_id) ? ' selected="selected"' : '';
			$rank1_select_box .= '<option value="' . $rank_id . '"' . $selected1 . '>' . $rank . '</option>';
			$rank2_select_box .= '<option value="' . $rank_id . '"' . $selected2 . '>' . $rank . '</option>';
			$rank3_select_box .= '<option value="' . $rank_id . '"' . $selected3 . '>' . $rank . '</option>';
			$rank4_select_box .= '<option value="' . $rank_id . '"' . $selected4 . '>' . $rank . '</option>';
			$rank5_select_box .= '<option value="' . $rank_id . '"' . $selected5 . '>' . $rank . '</option>';
			// Mighty Gorgon - Multiple Ranks - END
		}

		$groups_list = build_groups_user($this_userdata['user_id'], true);
		if ($groups_list != false)
		{
			$select_list .= '<select name="' . POST_GROUPS_URL . '">';
			$user_default_group_select = '<select name="group_id">';
			$group_selected = ($this_userdata['group_id'] == 0) ? ' selected="selected"' : '';
			$user_default_group_select .= '<option value="0"' . $group_selected . '>' . $lang['No_Default_Group'] . '</option>';
			for ($i = 1; $i <= sizeof($groups_list); $i++)
			{
				$group_selected = ($this_userdata['group_id'] == $groups_list[$i]['group_id']) ? ' selected="selected"' : '';
				$user_default_group_select .= '<option value="' . $groups_list[$i]['group_id'] . '"' . $group_selected . '>' . $groups_list[$i]['group_name'] . '</option>';
			}
			$user_default_group_select .= '</select>';
		}
		else
		{
			$user_default_group_select = $lang['No_Groups_Membership'];
			$user_default_group_select .= '<input type="hidden" name="group_id" value="0" />';
		}

		$user_color = $this_userdata['user_color'];

		$template->set_filenames(array('body' => ADM_TPL . 'user_edit_body.tpl'));

		// Custom Profile Fields MOD - BEGIN
		$profile_data = get_fields();

		if(sizeof($profile_data) > 0)
		{
			$template->assign_block_vars('switch_custom_fields',array(
				'L_CUSTOM_FIELD_NOTICE' => $lang['custom_field_notice_admin']
				)
			);
		}

		foreach($profile_data as $field)
		{
			$field_name = $field['field_name'];
			$name = text_to_column($field_name);

			if($field['is_required'] == REQUIRED)
			{
				$required = true;
			}
			else
			{
				$required = false;
			}

			if($field['users_can_view'] == DISALLOW_VIEW)
			{
				$admin_only = true;
			}
			else
			{
				$admin_only = false;
			}

			switch($field['field_type'])
			{
				case TEXT_FIELD:
					$value = $this_userdata[$name];
					$length = $field['text_field_maxlen'];
					$field_html_code = "<input type=\"text\" class=\"post\" style=\"width: 200px\" name=\"$name\" size=\"35\" maxlength=\"$length\" value=\"$value\" />";
					break;
				case TEXTAREA:
					$value = $this_userdata[$name];
					$field_html_code = "<textarea name=\"$name\" style=\"width: 300px\" rows=\"6\" cols=\"30\" class=\"post\">$value</textarea>";
					break;
				case RADIO:
					$value = $this_userdata[$name];
					$radio_list = explode(',',$field['radio_button_values']);
					$html_list = array();
					foreach($radio_list as $num => $radio_name)
					{
						$temp = "<input type=\"radio\" name=\"$name\" value=\"$radio_name\"";
						if($radio_name == $value)
							$temp .= ' checked="checked"';
						$temp .= " />&nbsp;<span class=\"gen\">$radio_name</span>";
						if($num < sizeof($radio_list))
							$temp .= '<br />';
						$html_list[] = $temp;
					}
					$field_html_code = '';
					foreach($html_list as $line)
						$field_html_code .= $line . "\n";
					break;
				case CHECKBOX:
					$value_array = explode(',',$this_userdata[$name]);
					$check_list = explode(',',$field['checkbox_values']);
					$html_list = array();
					foreach($check_list as $num => $check_name)
					{
						$temp = "<input type=\"checkbox\" name=\"{$name}[]\" value=\"$check_name\"";
						foreach($value_array as $val)
							if($val == $check_name)
							{
								$temp .= ' checked="checked"';
								break;
							}
						$temp .= " />&nbsp;<span class=\"gen\">$check_name</span>";
						if($num < sizeof($check_list))
							$temp .= '<br />';
						$html_list[] = $temp;
					}
					$field_html_code = '';
					foreach($html_list as $line)
						$field_html_code .= $line . "\n";
					break;
			}

			$template->assign_block_vars('custom_fields',array(
				'NAME' => $field_name,
				'FIELD' => $field_html_code,
				'DESCRIPTION' => $description,
				'REQUIRED' => $required ? ' *' : '',
				'ADMIN_ONLY' => $admin_only ? ' &dagger;' : ''
				)
			);

			if($field['field_description'] != NULL && !empty($field['field_description']))
			{
				$template->assign_block_vars('custom_fields.switch_description',array(
					'DESCRIPTION' => $field['field_description']
					)
				);
			}
		}
		// Custom Profile Fields MOD - END

		// Flag Start
		// Query to get the list of flags
		$sql = "SELECT *
			FROM " . FLAG_TABLE . "
			ORDER BY flag_name ASC";
		$flags_result = $db->sql_query($sql);
		$flag_row = $db->sql_fetchrowset($flags_result);
		$num_flags = $db->sql_numrows($flags_result) ;

		// Build the html select statement
		$flag_start_image = 'blank.gif' ;
		$selected = (isset($user_flag)) ? '' : ' selected="selected"';
		$flag_select = '<select name="user_flag" onchange="document.images[\'user_flag\'].src = \'../images/flags/\' + this.value;" >';
		$flag_select .= '<option value="blank.gif"' . $selected . '>' . $lang['Select_Country'] . '</option>';
		for ($i = 0; $i < $num_flags; $i++)
		{
			$flag_name = $flag_row[$i]['flag_name'];
			$flag_image = $flag_row[$i]['flag_image'];
			$selected = (isset($user_flag)) ? (($user_flag == $flag_image) ? 'selected="selected"' : '') : '' ;
			$flag_select .= "\t" . '<option value="' . $flag_image . '"' . $selected . '>' . $flag_name . '</option>';
			if (isset($user_flag) && ($user_flag == $flag_image))
			{
				$flag_start_image = $flag_image ;
			}
		}
		$flag_select .= '</select>';
		// Flag End

		// BIRTHDAY - BEGIN
		$s_b_day = '<span class="genmed">' . $lang['Day'] . '&nbsp;</span><select name="b_day" size="1" class="gensmall">
			<option value="0">&nbsp;-&nbsp;</option>
			<option value="1">&nbsp;1&nbsp;</option>
			<option value="2">&nbsp;2&nbsp;</option>
			<option value="3">&nbsp;3&nbsp;</option>
			<option value="4">&nbsp;4&nbsp;</option>
			<option value="5">&nbsp;5&nbsp;</option>
			<option value="6">&nbsp;6&nbsp;</option>
			<option value="7">&nbsp;7&nbsp;</option>
			<option value="8">&nbsp;8&nbsp;</option>
			<option value="9">&nbsp;9&nbsp;</option>
			<option value="10">&nbsp;10&nbsp;</option>
			<option value="11">&nbsp;11&nbsp;</option>
			<option value="12">&nbsp;12&nbsp;</option>
			<option value="13">&nbsp;13&nbsp;</option>
			<option value="14">&nbsp;14&nbsp;</option>
			<option value="15">&nbsp;15&nbsp;</option>
			<option value="16">&nbsp;16&nbsp;</option>
			<option value="17">&nbsp;17&nbsp;</option>
			<option value="18">&nbsp;18&nbsp;</option>
			<option value="19">&nbsp;19&nbsp;</option>
			<option value="20">&nbsp;20&nbsp;</option>
			<option value="21">&nbsp;21&nbsp;</option>
			<option value="22">&nbsp;22&nbsp;</option>
			<option value="23">&nbsp;23&nbsp;</option>
			<option value="24">&nbsp;24&nbsp;</option>
			<option value="25">&nbsp;25&nbsp;</option>
			<option value="26">&nbsp;26&nbsp;</option>
			<option value="27">&nbsp;27&nbsp;</option>
			<option value="28">&nbsp;28&nbsp;</option>
			<option value="29">&nbsp;29&nbsp;</option>
			<option value="30">&nbsp;30&nbsp;</option>
			<option value="31">&nbsp;31&nbsp;</option>
			</select>&nbsp;&nbsp;';
		$s_b_md = '<span class="genmed">' . $lang['Month'] . '&nbsp;</span><select name="b_md" size="1" class="gensmall">
			<option value="0">&nbsp;-&nbsp;</option>
			<option value="1">&nbsp;'.$lang['datetime']['January'].'&nbsp;</option>
			<option value="2">&nbsp;'.$lang['datetime']['February'].'&nbsp;</option>
			<option value="3">&nbsp;'.$lang['datetime']['March'].'&nbsp;</option>
			<option value="4">&nbsp;'.$lang['datetime']['April'].'&nbsp;</option>
			<option value="5">&nbsp;'.$lang['datetime']['May'].'&nbsp;</option>
			<option value="6">&nbsp;'.$lang['datetime']['June'].'&nbsp;</option>
			<option value="7">&nbsp;'.$lang['datetime']['July'].'&nbsp;</option>
			<option value="8">&nbsp;'.$lang['datetime']['August'].'&nbsp;</option>
			<option value="9">&nbsp;'.$lang['datetime']['September'].'&nbsp;</option>
			<option value="10">&nbsp;'.$lang['datetime']['October'].'&nbsp;</option>
			<option value="11">&nbsp;'.$lang['datetime']['November'].'&nbsp;</option>
			<option value="12">&nbsp;'.$lang['datetime']['December'].'&nbsp;</option>
			</select>&nbsp;&nbsp;';
			// Mighty Gorgon - Generate Years Select - BEGIN
			$i_start = gmdate('Y') - $config['max_user_age'];
			$i_end = gmdate('Y') - $config['min_user_age'];
			$s_birthday_year = '';
			$y_selected = ($birthday_year == 0) ? ' selected="selected"' : '';
			$s_birthday_year .= '<option value="0"' . $y_selected . '> ---- </option>';
			//for ($i = $i_start; $i <= $i_end; $i++)
			for ($i = $i_end; $i >= $i_start; $i--)
			{
				$y_selected = ($birthday_year == $i) ? ' selected="selected"' : '';
				$s_birthday_year .= '<option value="' . $i . '"' . $y_selected . '>' . $i . '</option>';
			}
			$s_birthday_year = '<select name="b_year">' . $s_birthday_year . '</select>';
			// Mighty Gorgon - Generate Years Select - END
			$s_b_day= str_replace("value=\"".$birthday_day."\">", "value=\"".$birthday_day."\" selected=\"selected\">" ,$s_b_day);
			$s_b_md = str_replace("value=\"".$birthday_month."\">", "value=\"".$birthday_month."\" selected=\"selected\">" ,$s_b_md);
			$s_b_day= str_replace("value=\"".$birthday_day."\">", "value=\"".$birthday_day."\" selected=\"selected\">" ,$s_b_day);
			$s_b_md = str_replace("value=\"".$birthday_month."\">", "value=\"".$birthday_month."\" selected=\"selected\">" ,$s_b_md);
			//$s_b_year = '<span class="genmed">' . $lang['Year'] . '&nbsp;</span><input type="text" class="post" style="width: 50px" name="b_year" size="4" maxlength="4" value="' . $birthday_year . '" />&nbsp;&nbsp;';
			$s_b_year = '<span class="genmed">' . $lang['Year'] . '&nbsp;</span>' . $s_birthday_year . '&nbsp;&nbsp;';
			$i = 0;
			$s_birthday = '';
			for ($i = 0; $i <= strlen($lang['Submit_date_format']); $i++)
			{
				switch ($lang['Submit_date_format'][$i])
				{
					case d:
						$s_birthday .= $s_b_day;
						break;
					case m:
						$s_birthday .= $s_b_md;
						break;
					case Y:
						$s_birthday .= $s_b_year;
						break;
				}
		}
		// BIRTHDAY - END
		// Start add - Gender MOD
		switch ($gender)
		{
			case 1:
				$gender_male_checked = 'checked="checked"';
					break;
			case 2:
				$gender_female_checked = 'checked="checked"';
				break;
			default:
				$gender_no_specify_checked = 'checked="checked"';
		}
		// End add - Gender MOD
		$l_time_mode_0 = '';
		$l_time_mode_1 = '';
		$l_time_mode_2 = $lang['time_mode_dst_server'];

		switch ($config['default_time_mode'])
		{
			case MANUAL_DST:
				$l_time_mode_1 = $l_time_mode_1 . "*";
				break;
			case SERVER_SWITCH:
				$l_time_mode_2 = $l_time_mode_2 . "*";
				break;
			default:
				$l_time_mode_0 = $l_time_mode_0 . "*";
				break;
		}

		switch ($time_mode)
		{
			case MANUAL_DST:
				$time_mode_manual_dst_checked = 'checked="checked"';
				break;
			case SERVER_SWITCH:
				$time_mode_server_switch_checked = 'checked="checked"';
				break;
			default:
				$time_mode_manual_checked = 'checked="checked"';
				break;
		}

		// TOPICS / POSTS - SORTING - BEGIN
		$sort_dir_text = array('a' => $lang['ASCENDING'], 'd' => $lang['DESCENDING']);

		// Topic ordering options
		$limit_topic_days = array(0 => $lang['ALL_TOPICS'], 1 => $lang['1_DAY'], 7 => $lang['7_DAYS'], 14 => $lang['2_WEEKS'], 30 => $lang['1_MONTH'], 90 => $lang['3_MONTHS'], 180 => $lang['6_MONTHS'], 365 => $lang['1_YEAR']);

		$sort_by_topic_text = array('a' => $lang['AUTHOR'], 't' => $lang['POST_TIME'], 'r' => $lang['REPLIES'], 's' => $lang['SUBJECT'], 'v' => $lang['VIEWS']);
		$sort_by_topic_sql = array('a' => 't.topic_first_poster_name', 't' => 't.topic_last_post_time', 'r' => 't.topic_replies', 's' => 't.topic_title', 'v' => 't.topic_views');

		// Post ordering options
		$limit_post_days = array(0 => $lang['ALL_POSTS'], 1 => $lang['1_DAY'], 7 => $lang['7_DAYS'], 14 => $lang['2_WEEKS'], 30 => $lang['1_MONTH'], 90 => $lang['3_MONTHS'], 180 => $lang['6_MONTHS'], 365 => $lang['1_YEAR']);

		$sort_by_post_text = array('a' => $lang['AUTHOR'], 't' => $lang['POST_TIME'], 's' => $lang['SUBJECT']);
		$sort_by_post_sql = array('a' => 'u.username_clean', 't' => 'p.post_id', 's' => 'p.post_subject');

		$_options = array('topic', 'post');
		foreach ($_options as $sort_option)
		{
			${'user_' . $sort_option . '_show_days_select'} = '<select name="user_' . $sort_option . '_show_days">';
			foreach (${'limit_' . $sort_option . '_days'} as $day => $text)
			{
				$selected = (${'user_' . $sort_option . '_show_days'} == $day) ? ' selected="selected"' : '';
				${'user_' . $sort_option . '_show_days_select'} .= '<option value="' . $day . '"' . $selected . '>' . $text . '</option>';
			}
			${'user_' . $sort_option . '_show_days_select'} .= '</select>';

			${'user_' . $sort_option . '_sortby_type_select'} = '<select name="user_' . $sort_option . '_sortby_type">';
			foreach (${'sort_by_' . $sort_option . '_text'} as $key => $text)
			{
				$selected = (${'user_' . $sort_option . '_sortby_type'} == $key) ? ' selected="selected"' : '';
				${'user_' . $sort_option . '_sortby_type_select'} .= '<option value="' . $key . '"' . $selected . '>' . $text . '</option>';
			}
			${'user_' . $sort_option . '_sortby_type_select'} .= '</select>';

			${'user_' . $sort_option . '_sortby_dir_select'} = '<select name="user_' . $sort_option . '_sortby_dir">';
			foreach ($sort_dir_text as $key => $value)
			{
				$selected = (${'user_' . $sort_option . '_sortby_dir'} == $key) ? ' selected="selected"' : '';
				${'user_' . $sort_option . '_sortby_dir_select'} .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			${'user_' . $sort_option . '_sortby_dir_select'} .= '</select>';
		}
		// TOPICS / POSTS - SORTING - END

		// Let's do an overall check for settings/versions which would prevent us from doing file uploads...
		$ini_val = (phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';
		$form_enctype = (!@$ini_val('file_uploads') || (phpversion() == '4.0.4pl1') || !$config['allow_avatar_upload'] || ((phpversion() < '4.0.3') && @$ini_val('open_basedir') != '')) ? '' : 'enctype="multipart/form-data"';

		$user_sn_im_array = get_user_sn_im_array();
		foreach ($user_sn_im_array as $k => $v)
		{
			$template->assign_var(strtoupper($v['form']), $$v['form']);
		}

		$template->assign_vars(array(
			'USERNAME' => $username,
			'EMAIL' => $email,
			'USER_FIRST_NAME' => $user_first_name,
			'USER_LAST_NAME' => $user_last_name,
			'OCCUPATION' => $occupation,
			'INTERESTS' => $interests,

			'FLAG_SELECT' => $flag_select,
			'FLAG_START' => $flag_start_image,
			'PHONE' => $phone,
			'SELFDES' => str_replace('<br />', "\n", $selfdes),
			'L_FLAG' => $lang['Country_Flag'],
			'L_PHONE' => $lang['UserPhone'],
			'L_EXTRA_PROFILE_INFO' => $lang['Extra_profile_info'],
			'L_EXTRA_PROFILE_INFO_EXPLAIN' => sprintf($lang['Extra_profile_info_explain'], $config['extra_max']),

			'PROFILE_VIEW_POPUP_YES' => ($profile_view_popup) ? 'checked="checked"' : '',
			'PROFILE_VIEW_POPUP_NO' => (!$profile_view_popup) ? 'checked="checked"' : '',
			'L_PROFILE_VIEW_POPUP' => $lang['Profile_view_option'],
			'NEXT_BIRTHDAY_GREETING' => $next_birthday_greeting,
			'S_BIRTHDAY' => $s_birthday,
			'GENDER' => $gender,
			'GENDER_NO_SPECIFY_CHECKED' => $gender_no_specify_checked,
			'GENDER_MALE_CHECKED' => $gender_male_checked,
			'GENDER_FEMALE_CHECKED' => $gender_female_checked,
			'LOCATION' => $location,
			'WEBSITE' => $website,
			'SIGNATURE' => str_replace('<br />', "\n", $signature),
			'HIDE_USER_YES' => !$allowviewonline ? 'checked="checked"' : '',
			'HIDE_USER_NO' => $allowviewonline ? 'checked="checked"' : '',
			'VIEW_EMAIL_YES' => $viewemail ? 'checked="checked"' : '',
			'VIEW_EMAIL_NO' => !$viewemail ? 'checked="checked"' : '',
			'ALLOW_MASS_EMAIL_YES' => $allowmassemail ? 'checked="checked"' : '',
			'ALLOW_MASS_EMAIL_NO' => !$allowmassemail ? 'checked="checked"' : '',
			'ALLOW_PM_IN_YES' => $allowpmin ? 'checked="checked"' : '',
			'ALLOW_PM_IN_NO' => !$allowpmin ? 'checked="checked"' : '',
			'NOTIFY_PM_YES' => $notifypm ? 'checked="checked"' : '',
			'NOTIFY_PM_NO' => !$notifypm ? 'checked="checked"' : '',
			'POPUP_PM_YES' => $popup_pm ? 'checked="checked"' : '',
			'POPUP_PM_NO' => !$popup_pm ? 'checked="checked"' : '',
			'ALWAYS_ADD_SIGNATURE_YES' => $attachsig ? 'checked="checked"' : '',
			'ALWAYS_ADD_SIGNATURE_NO' => !$attachsig ? 'checked="checked"' : '',
			'ALWAYS_SET_BOOKMARK_YES' => $setbm ? 'checked="checked"' : '',
			'ALWAYS_SET_BOOKMARK_NO' => !$setbm ? 'checked="checked"' : '',
			'ALWAYS_SHOW_AVATARS_YES' => $user_showavatars ? 'checked="checked"' : '',
			'ALWAYS_SHOW_AVATARS_NO' => !$user_showavatars ? 'checked="checked"' : '',
			'ALWAYS_SHOW_SIGNATURES_YES' => $user_showsignatures ? 'checked="checked"' : '',
			'ALWAYS_SHOW_SIGNATURES_NO' => !$user_showsignatures ? 'checked="checked"' : '',
			'ALWAYS_SWEARY_WORDS_YES' => $user_allowswearywords ? 'checked="checked"' : '',
			'ALWAYS_SWEARY_WORDS_NO' => !$user_allowswearywords ? 'checked="checked"' : '',
			'NOTIFY_REPLY_YES' => $notifyreply ? 'checked="checked"' : '',
			'NOTIFY_REPLY_NO' => !$notifyreply ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_BBCODE_YES' => $allowbbcode ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_BBCODE_NO' => !$allowbbcode ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_HTML_YES' => $allowhtml ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_HTML_NO' => !$allowhtml ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_SMILIES_YES' => $allowsmilies ? 'checked="checked"' : '',
			'ALWAYS_ALLOW_SMILIES_NO' => !$allowsmilies ? 'checked="checked"' : '',
			'POSTS_PER_PAGE' => !$user_posts_per_page ? $config['posts_per_page'] : $user_posts_per_page,
			'TOPICS_PER_PAGE' => !$user_topics_per_page ? $config['topics_per_page'] : $user_topics_per_page,
			'HOT_TOPIC' => !$user_hot_threshold ? $config['hot_threshold'] : $user_hot_threshold,
			'USER_TOPIC_SHOW_DAYS_SELECT' => $user_topic_show_days_select,
			'USER_TOPIC_SORTBY_TYPE_SELECT' => $user_topic_sortby_type_select,
			'USER_TOPIC_SORTBY_DIR_SELECT' => $user_topic_sortby_dir_select,
			'USER_POST_SHOW_DAYS_SELECT' => $user_post_show_days_select,
			'USER_POST_SORTBY_TYPE_SELECT' => $user_post_sortby_type_select,
			'USER_POST_SORTBY_DIR_SELECT' => $user_post_sortby_dir_select,

			'AVATAR' => $avatar,
			'GRAVATAR' => ($user_avatar_type == USER_GRAVATAR) ? $user->data['user_avatar'] : '',
			'STYLE_SELECT' => style_select('style', $user_style),
			'LANGUAGE_SELECT' => language_select('language', $user_lang),
			'TIMEZONE_SELECT' => tz_select('timezone', $user_timezone),
			'DATE_FORMAT' => date_select('dateformat', $user_dateformat),
			'TIME_MODE' => $time_mode,
			'TIME_MODE_MANUAL_CHECKED' => $time_mode_manual_checked,
			'TIME_MODE_MANUAL_DST_CHECKED' => $time_mode_manual_dst_checked,
			'TIME_MODE_SERVER_SWITCH_CHECKED' => $time_mode_server_switch_checked,
			'TIME_MODE_FULL_SERVER_CHECKED' => $time_mode_full_server_checked,
			'TIME_MODE_SERVER_PC_CHECKED' => $time_mode_server_pc_checked,
			'TIME_MODE_FULL_PC_CHECKED' => $time_mode_full_pc_checked,
			'DST_TIME_LAG' => $dst_time_lag,
			'ALLOW_PM_YES' => ($user_allowpm) ? 'checked="checked"' : '',
			'ALLOW_PM_NO' => (!$user_allowpm) ? 'checked="checked"' : '',
			'ALLOW_AVATAR_YES' => ($user_allowavatar) ? 'checked="checked"' : '',
			'ALLOW_AVATAR_NO' => (!$user_allowavatar) ? 'checked="checked"' : '',
// UPI2DB - BEGIN
			'DISABLE_UPI2DB_YES' => ($user_upi2db_disable) ? 'checked="checked"' : '',
			'DISABLE_UPI2DB_NO' => (!$user_upi2db_disable) ? 'checked="checked"' : '',
// UPI2DB - END
			'USER_ACTIVE_YES' => ($user_status) ? 'checked="checked"' : '',
			'USER_ACTIVE_NO' => (!$user_status) ? 'checked="checked"' : '',
			'USER_MASK_YES' => ($user_mask) ? 'checked="checked"' : '',
			'USER_MASK_NO' => (!$user_mask) ? 'checked="checked"' : '',
			'BANCARD' => $user_ycard,
			'FAILED_LOGINS_COUNTER_VALUE' => $user_login_attempts,
			'POSTS' => $user_posts,
			// Mighty Gorgon - Multiple Ranks - BEGIN
			'RANK1_SELECT_BOX' => $rank1_select_box,
			'RANK2_SELECT_BOX' => $rank2_select_box,
			'RANK3_SELECT_BOX' => $rank3_select_box,
			'RANK4_SELECT_BOX' => $rank4_select_box,
			'RANK5_SELECT_BOX' => $rank5_select_box,
			// Mighty Gorgon - Multiple Ranks - END
			'USER_GROUP_ID' => $user_default_group_select,
			'USER_COLOR' => str_replace('#', '', $user_color),
			'USER_COLOR_STYLE' => (($user_color != '') ? ' style="color:' . $user_color . ';font-weight:bold;"' : ' style="font-weight:bold;"'),

			'L_USERNAME' => $lang['Username'],
			'L_USER_TITLE' => $lang['User_admin'],
			'L_USER_EXPLAIN' => $lang['User_admin_explain'],
			'L_NEW_PASSWORD' => $lang['New_password'],
			'L_PASSWORD_IF_CHANGED' => $lang['password_if_changed'],
			'L_CONFIRM_PASSWORD' => $lang['Confirm_password'],
			'L_PASSWORD_CONFIRM_IF_CHANGED' => $lang['password_confirm_if_changed'],
			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'],
			'L_ICQ_NUMBER' => $lang['ICQ'],
			'L_MESSENGER' => $lang['MSNM'],
			'L_YAHOO' => $lang['YIM'],
			'L_SKYPE' => $lang['SKYPE'],
			'L_WEBSITE' => $lang['Website'],
			'L_AIM' => $lang['AIM'],
			'L_LOCATION' => $lang['Location'],
			'L_OCCUPATION' => $lang['Occupation'],
			'L_BOARD_LANGUAGE' => $lang['Board_lang'],
			'L_BOARD_STYLE' => $lang['Board_style'],
			'L_TIMEZONE' => $lang['Timezone'],
			'L_TIME_MODE' => $lang['time_mode'],
			'L_TIME_MODE_TEXT' => $lang['time_mode_text'],
			'L_TIME_MODE_MANUAL' => $lang['time_mode_manual'],
			'L_TIME_MODE_DST' => $lang['time_mode_dst'],
			'L_TIME_MODE_DST_OFF' => $l_time_mode_0,
			'L_TIME_MODE_DST_ON' => $l_time_mode_1,
			'L_TIME_MODE_DST_SERVER' => $l_time_mode_2,
			'L_TIME_MODE_DST_TIME_LAG' => $lang['time_mode_dst_time_lag'],
			'L_TIME_MODE_DST_MN' => $lang['time_mode_dst_mn'],
			'L_TIME_MODE_TIMEZONE' => $lang['time_mode_timezone'],
			'L_TIME_MODE_AUTO' => $lang['time_mode_auto'],
			'L_TIME_MODE_FULL_SERVER' => $l_time_mode_3,
			'L_TIME_MODE_SERVER_PC' => $l_time_mode_4,
			'L_TIME_MODE_FULL_PC' => $l_time_mode_6,
			'L_DATE_FORMAT' => $lang['Date_format'],
			'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_INTERESTS' => $lang['Interests'],
			'L_BIRTHDAY' => $lang['Birthday'],
			'L_NEXT_BIRTHDAY_GREETING' => $lang['Next_birthday_greeting'],
			'L_NEXT_BIRTHDAY_GREETING_EXPLAIN' => $lang['Next_birthday_greeting_explain'],
			'L_GENDER' =>$lang['Gender'],
			'L_GENDER_MALE' =>$lang['Male'],
			'L_GENDER_FEMALE' =>$lang['Female'],
			'L_GENDER_NOT_SPECIFY' =>$lang['No_gender_specify'],
			'L_BANCARD' => $lang['ban_card'],
			'L_BANCARD_EXPLAIN' => sprintf($lang['ban_card_explain'], $config['max_user_bancard']),
			'L_HOT_THRESHOLD' => $lang['Hot_threshold'],
			'L_POSTS_PER_PAGE' => $lang['Posts_per_page'],
			'L_TOPICS_PER_PAGE' => $lang['Topics_per_page'],
			'L_ALWAYS_ALLOW_SMILIES' => $lang['Always_smile'],
			'L_ALWAYS_ALLOW_BBCODE' => $lang['Always_bbcode'],
			'L_ALWAYS_ALLOW_HTML' => $lang['Always_html'],
			'L_HIDE_USER' => $lang['Hide_user'],
			'L_ALWAYS_ADD_SIGNATURE' => $lang['Always_add_sig'],
			'L_ALWAYS_SET_BOOKMARK' => $lang['Always_set_bm'],
			'L_SHOW_AVATARS' => $lang['Show_avatars'],
			'L_SHOW_SIGNATURES' => $lang['Show_signatures'],
			'L_ALWAYS_ALLOW_SWEARYWORDS' => $lang['Always_swear'],

			'L_SPECIAL' => $lang['User_special'],
			'L_SPECIAL_EXPLAIN' => $lang['User_special_explain'],
			'L_USER_ACTIVE' => $lang['User_status'],
			'L_ALLOW_PM' => $lang['User_allowpm'],
			'L_ALLOW_AVATAR' => $lang['User_allowavatar'],
// UPI2DB - BEGIN
			'L_DISABLE_UPI2DB' => $lang['user_disable_upi2db'],
// UPI2DB - END
			'L_POSTCOUNT' => $lang['Modify_post_counts'],
			'L_POSTCOUNT_EXPLAIN' => $lang['Post_count_explain'],

			'L_AVATAR_PANEL' => $lang['Avatar_panel'],
			'L_AVATAR_EXPLAIN' => $lang['Admin_avatar_explain'],
			'L_DELETE_AVATAR' => $lang['Delete_Image'],
			'L_CURRENT_IMAGE' => $lang['Current_Image'],
			'L_UPLOAD_AVATAR_FILE' => $lang['Upload_Avatar_file'],
			'L_UPLOAD_AVATAR_URL' => $lang['Upload_Avatar_URL'],
			'L_AVATAR_GALLERY' => $lang['Select_from_gallery'],
			'L_SHOW_GALLERY' => $lang['View_avatar_gallery'],
			'L_LINK_REMOTE_AVATAR' => $lang['Link_remote_Avatar'],
			'L_GRAVATAR' => $lang['Gravatar'],
			'L_GRAVATAR_EXPLAIN' => $lang['Gravatar_explain'],

			'L_SIGNATURE' => $lang['Signature'],
			'L_SIGNATURE_EXPLAIN' => sprintf($lang['Signature_explain'], $config['max_sig_chars']),
			'L_NOTIFY_ON_PRIVMSG' => $lang['Notify_on_privmsg'],
			'L_NOTIFY_ON_REPLY' => $lang['Always_notify'],
			'L_POPUP_ON_PRIVMSG' => $lang['Popup_on_privmsg'],
			'L_PREFERENCES' => $lang['Preferences'],
			'L_PUBLIC_VIEW_EMAIL' => $lang['Public_view_email'],
			'L_MASS_EMAIL' => $lang['Admin_Emails'],
			'L_PM_IN' => $lang['Allow_PM_IN'],
			'L_PM_IN_EXPLAIN' => $lang['Allow_PM_IN_Explain'],
			'L_ITEMS_REQUIRED' => $lang['Items_required'],
			'L_REGISTRATION_INFO' => $lang['Registration_info'],
			'L_PROFILE_INFO' => $lang['Profile_info'],
			'L_PROFILE_INFO_NOTICE' => $lang['Profile_info_warn'],
			'L_EMAIL_ADDRESS' => $lang['Email_address'],
			'S_FORM_ENCTYPE' => $form_enctype,

			'HTML_STATUS' => $html_status,
			'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="../' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_blank">', '</a>'),
			'SMILIES_STATUS' => $smilies_status,

			'L_DELETE_USER' => $lang['User_delete'],
			'L_DELETE_USER_EXPLAIN' => $lang['User_delete_explain'],
			// Mighty Gorgon - Multiple Ranks - BEGIN
			'L_SELECT_RANK1' => $lang['Rank1_title'],
			'L_SELECT_RANK2' => $lang['Rank2_title'],
			'L_SELECT_RANK3' => $lang['Rank3_title'],
			'L_SELECT_RANK4' => $lang['Rank4_title'],
			'L_SELECT_RANK5' => $lang['Rank5_title'],
			// Mighty Gorgon - Multiple Ranks - END

			'L_GROUP_DEFAULT' => $lang['Group_Default_Membership'],
			'L_GROUP_DEFAULT_EXPLAIN' => $lang['Group_Default_Membership_Explain'],
			'L_USER_COLOR' => $lang['User_Color'],
			'L_USER_COLOR_EXPLAIN' => $lang['User_Color_Explain'],

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_PROFILE_ACTION' => append_sid('admin_users.' . PHP_EXT)
			)
		);

		if(file_exists(@phpbb_realpath('./../' . $config['avatar_path'])) && ($config['allow_avatar_upload'] == true))
		{
			if ($form_enctype != '')
			{
				$template->assign_block_vars('avatar_local_upload', array());
			}
			$template->assign_block_vars('avatar_remote_upload', array());
		}

		if(file_exists(@phpbb_realpath('./../' . $config['avatar_gallery_path'])) && ($config['allow_avatar_local'] == true))
		{
			$template->assign_block_vars('avatar_local_gallery', array());
		}

		if($config['allow_avatar_remote'] == true)
		{
			$template->assign_block_vars('avatar_remote_link', array());
		}
		if($config['enable_gravatars'])
		{
			$template->assign_block_vars('switch_gravatar', array());
		}
	}

	$template->pparse('body');
}
else
{
	//
	// Default user selection box
	//
	$template->set_filenames(array('body' => ADM_TPL . 'user_select_body.tpl'));

	$template->assign_vars(array(
		'L_USER_TITLE' => $lang['User_admin'],
		'L_USER_EXPLAIN' => $lang['User_admin_explain'],
		'L_USER_SELECT' => $lang['Select_a_User'],
		'L_LOOK_UP' => $lang['Look_up_user'],

		'U_SEARCH_USER' => append_sid('../' . CMS_PAGE_SEARCH . '?mode=searchuser'),

		'S_USER_ACTION' => append_sid('admin_users.' . PHP_EXT),
		'S_USER_SELECT' => $select_list)
	);
	$template->pparse('body');

}

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>