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
require('./pagestart.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
require(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_users_delete.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_profile.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);

$html_entities_match = array('#<#', '#>#');
$html_entities_replace = array('&lt;', '&gt;');

// Disallow other admins to delete or edit the first admin - BEGIN
$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
if ((intval($_POST['id']) == $founder_id) && ($userdata['user_id'] != $founder_id))
{
	$edituser = $userdata['username'];
	$editok = $userdata['user_id'];
	$sql = "INSERT INTO " . ADMINEDIT_TABLE . " (edituser, editok) VALUES ('" . str_replace("\'", "''", $edituser) . "','" . $editok . "')";
	if(!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain adminedit information for this user', '', __LINE__, __FILE__, $sql);
	}
	message_die(GENERAL_MESSAGE, $lang['L_ADMINEDITMSG']);
}
// Disallow other admins to delete or edit the first admin - END

// Set mode
if(isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = '';
}
//Start Quick Administrator User Options and Information MOD
if(isset($_POST['redirect']) || isset($_GET['redirect']))
{
	$redirect = (isset($_POST['redirect'])) ? $_POST['redirect'] : $_GET['redirect'];
	$redirect = htmlspecialchars($redirect);
}
else
{
	$redirect = '';
}
//End Quick Administrator User Options and Information MOD

// Begin program
if(isset($_POST['acp_username']))
{
	$_POST['username'] = $_POST['acp_username'];
}

if ($mode == 'edit' || $mode == 'save' && (isset($_POST['username']) || isset($_GET[POST_USERS_URL]) || isset($_POST[POST_USERS_URL])))
{
	attachment_quota_settings('user', $_POST['submit'], $mode);

	// Ok, the profile has been modified and submitted, let's update
	if (($mode == 'save' && isset($_POST['submit'])) || isset($_POST['avatargallery']) || isset($_POST['submitavatar']) || isset($_POST['cancelavatar']))
	{
		$user_id = intval($_POST['id']);

		// CrackerTracker v5.x
		$ctracker_config->first_admin_protection($user_id);
		// CrackerTracker v5.x

		if (!($this_userdata = get_userdata($user_id)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
		}

		if($_POST['deleteuser'] && ($userdata['user_id'] != $user_id))
		{
			$killed = ip_user_kill($user_id);

			$message = $lang['User_deleted'] . '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid('admin_users.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}

		$username = (!empty($_POST['username'])) ? phpbb_clean_username($_POST['username']) : '';
		$email = (!empty($_POST['email'])) ? trim(strip_tags(htmlspecialchars($_POST['email']))) : '';

		$password = (!empty($_POST['password_change'])) ? trim(strip_tags(htmlspecialchars($_POST['password_change']))) : '';
		$password_confirm = (!empty($_POST['password_confirm'])) ? trim(strip_tags(htmlspecialchars($_POST['password_confirm']))) : '';

		$icq = (!empty($_POST['icq'])) ? trim(strip_tags($_POST['icq'])) : '';
		$aim = (!empty($_POST['aim'])) ? trim(strip_tags($_POST['aim'])) : '';
		$msn = (!empty($_POST['msn'])) ? trim(strip_tags($_POST['msn'])) : '';
		$yim = (!empty($_POST['yim'])) ? trim(strip_tags($_POST['yim'])) : '';
		$skype = (!empty($_POST['skype'])) ? trim(strip_tags($_POST['skype'])) : '';

		$website = (!empty($_POST['website'])) ? trim(strip_tags($_POST['website'])) : '';
		$location = (!empty($_POST['location'])) ? trim(strip_tags($_POST['location'])) : '';
		$phone = (!empty($_POST['phone'])) ? trim(strip_tags($_POST['phone'])) : '';
		$occupation = (!empty($_POST['occupation'])) ? trim(strip_tags($_POST['occupation'])) : '';
		$interests = (!empty($_POST['interests'])) ? trim(strip_tags($_POST['interests'])) : '';
		if (isset($_POST['birthday']))
		{
			$birthday = intval ($_POST['birthday']);
			$birthday_day = realdate('j',$birthday);
			$birthday_month = realdate('n',$birthday);
			$birthday_year = realdate('Y',$birthday);
		}
		else
		{
			$birthday_day = (isset($_POST['b_day'])) ? intval ($_POST['b_day']) : 0;
			$birthday_month = (isset($_POST['b_md'])) ? intval ($_POST['b_md']) : 0;
			$birthday_year = (isset($_POST['b_year'])) ? intval ($_POST['b_year']) : 0;
			$birthday = mkrealdate($birthday_day, $birthday_month, $birthday_year);
		}
		$next_birthday_greeting = (!empty($_POST['next_birthday_greeting'])) ? intval($_POST['next_birthday_greeting']) : 0;
		$gender = (isset($_POST['gender'])) ? intval ($_POST['gender']) : 0;
		$selfdes = (!empty($_POST['selfdes'])) ? trim(str_replace('<br />', "\n", $_POST['selfdes'])) : '';
		$signature = (!empty($_POST['signature'])) ? trim(str_replace('<br />', "\n", $_POST['signature'])) : '';

		validate_optional_fields($icq, $aim, $msn, $yim, $skype, $website, $location, $occupation, $interests, $phone, $selfdes, $signature);

		$allowviewonline = (isset($_POST['hideonline'])) ? (($_POST['hideonline']) ? 0 : true) : true;
		$profile_view_popup = (isset($_POST['profile_view_popup'])) ? (($_POST['profile_view_popup']) ? true : 0) : 0;
		$viewemail = (isset($_POST['viewemail'])) ? (($_POST['viewemail']) ? true : 0) : 0;
		$allowmassemail = (isset($_POST['allowmassemail'])) ? (($_POST['allowmassemail']) ? true : 0) : 0;
		$allowpmin = (isset($_POST['allowpmin'])) ? (($_POST['allowpmin']) ? true : 0) : 0;
		$notifyreply = (isset($_POST['notifyreply'])) ? (($_POST['notifyreply']) ? true : 0) : 0;
		$notifypm = (isset($_POST['notifypm'])) ? (($_POST['notifypm']) ? true : 0) : true;
		$popuppm = (isset($_POST['popup_pm'])) ? (($_POST['popup_pm']) ? true : 0) : true;
		$attachsig = (isset($_POST['attachsig'])) ? (($_POST['attachsig']) ? true : 0) : 0;
		$setbm = (isset($_POST['setbm'])) ? (($_POST['setbm']) ? true : 0) : 0;
		$user_showavatars = (isset($_POST['user_showavatars'])) ? (($_POST['user_showavatars']) ? true : 0) : 0;
		$user_showsignatures = (isset($_POST['user_showsignatures'])) ? (($_POST['user_showsignatures']) ? true : 0) : 0;
		$user_allowswearywords = (isset($_POST['user_allowswearywords'])) ? (($_POST['user_allowswearywords']) ? true : 0) : 0;
		$user_topics_per_page = (isset($_POST['user_topics_per_page'])) ? intval ($_POST['user_topics_per_page']) : 0;
		$user_posts_per_page = (isset($_POST['user_posts_per_page'])) ? intval ($_POST['user_posts_per_page']) : 0;
		$user_hot_threshold = (isset($_POST['user_hot_threshold'])) ? intval ($_POST['user_hot_threshold']) : 0;

		$user_topics_per_page = ($user_topics_per_page > 100) ? 100 : $user_topics_per_page;
		$user_posts_per_page = ($user_posts_per_page > 50) ? 50 : $user_posts_per_page;
		$user_hot_threshold = ($user_posts_per_page > 50) ? 50 : $user_posts_per_page;

		$allowhtml = (isset($_POST['allowhtml'])) ? intval($_POST['allowhtml']) : $board_config['allow_html'];
		$allowbbcode = (isset($_POST['allowbbcode'])) ? intval($_POST['allowbbcode']) : $board_config['allow_bbcode'];
		$allowsmilies = (isset($_POST['allowsmilies'])) ? intval($_POST['allowsmilies']) : $board_config['allow_smilies'];

		$user_style = ($_POST['style']) ? intval($_POST['style']) : $board_config['default_style'];
		$user_lang = ($_POST['language']) ? $_POST['language'] : $board_config['default_lang'];
		$user_flag = (!empty($_POST['user_flag'])) ? $_POST['user_flag'] : '' ;
		$user_timezone = (isset($_POST['timezone'])) ? doubleval($_POST['timezone']) : $board_config['board_timezone'];
		$time_mode = (isset($_POST['time_mode'])) ? intval($_POST['time_mode']) : $board_config['default_time_mode'];
		if (!eregi("[^0-9]",$_POST['dst_time_lag']))
		{
			$dst_time_lag = (isset($_POST['dst_time_lag'])) ? intval($_POST['dst_time_lag']) : $board_config['default_dst_time_lag'];
		}
		$user_template = ($_POST['template']) ? $_POST['template'] : $board_config['board_template'];
		$user_dateformat = ($_POST['dateformat']) ? trim($_POST['dateformat']) : $board_config['default_dateformat'];

		$user_avatar_local = (isset($_POST['avatarselect']) && !empty($_POST['submitavatar']) && $board_config['allow_avatar_local']) ? $_POST['avatarselect'] : ((isset($_POST['avatarlocal'])) ? $_POST['avatarlocal'] : '');
		$user_avatar_category = (isset($_POST['avatarcatname']) && $board_config['allow_avatar_local']) ? htmlspecialchars($_POST['avatarcatname']) : '' ;

		$user_avatar_remoteurl = (!empty($_POST['avatarremoteurl'])) ? trim($_POST['avatarremoteurl']) : '';
		$user_avatar_url = (!empty($_POST['avatarurl'])) ? trim($_POST['avatarurl']) : '';
		$user_avatar_loc = ($_FILES['avatar']['tmp_name'] != "none") ? $_FILES['avatar']['tmp_name'] : '';
		$user_avatar_name = (!empty($_FILES['avatar']['name'])) ? $_FILES['avatar']['name'] : '';
		$user_avatar_size = (!empty($_FILES['avatar']['size'])) ? $_FILES['avatar']['size'] : 0;
		$user_avatar_filetype = (!empty($_FILES['avatar']['type'])) ? $_FILES['avatar']['type'] : '';
		$user_gravatar = (!empty($_POST['gravatar'])) ? trim(htmlspecialchars($_POST['gravatar'])) : '';

		$user_avatar = (empty($user_avatar_loc)) ? $this_userdata['user_avatar'] : '';
		$user_avatar_type = (empty($user_avatar_loc)) ? $this_userdata['user_avatar_type'] : '';

		$user_status = (!empty($_POST['user_status'])) ? intval($_POST['user_status']) : 0;
		$user_ycard = (!empty($_POST['user_ycard'])) ? intval($_POST['user_ycard']) : 0;

		$user_allowpm = (!empty($_POST['user_allowpm'])) ? intval($_POST['user_allowpm']) : 0;
		$user_rank = (!empty($_POST['user_rank'])) ? intval($_POST['user_rank']) : 0;
		// Mighty Gorgon - Multiple Ranks - BEGIN
		$user_rank2 = (!empty($_POST['user_rank2'])) ? intval($_POST['user_rank2']) : 0;
		$user_rank3 = (!empty($_POST['user_rank3'])) ? intval($_POST['user_rank3']) : 0;
		$user_rank4 = (!empty($_POST['user_rank4'])) ? intval($_POST['user_rank4']) : 0;
		$user_rank5 = (!empty($_POST['user_rank5'])) ? intval($_POST['user_rank5']) : 0;
		// Mighty Gorgon - Multiple Ranks - END
		$user_allowavatar = (!empty($_POST['user_allowavatar'])) ? intval($_POST['user_allowavatar']) : 0;
		$user_posts = (!empty($_POST['user_posts'])) ? intval($_POST['user_posts']) : 0;

		$user_color_group = (!empty($_POST['user_color_group'])) ? $_POST['user_color_group'] : '0';
		$user_color = (!empty($_POST['user_color'])) ? ((check_valid_color($_POST['user_color']) != false) ? check_valid_color($_POST['user_color']) : '') : '';
		if ($user_color_group > 0)
		{
			$sql = "SELECT g.group_color, g.group_rank
							FROM " . GROUPS_TABLE . " as g
							WHERE g.group_id = '" . $user_color_group . "'
							LIMIT 1";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain group color and rank', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);
			$user_color = ($row['group_color'] != '') ? $row['group_color'] : $user_color;
			$user_rank = ($row['user_rank'] != 0) ? $row['user_rank'] : $user_rank;
			$db->sql_freeresult($result);
		}

//<!-- BEGIN Unread Post Information to Database Mod -->
		$user_upi2db_disable = (!empty($_POST['user_upi2db_disable'])) ? intval($_POST['user_upi2db_disable']) : 0;
//<!-- END Unread Post Information to Database Mod -->

		if(isset($_POST['avatargallery']) || isset($_POST['submitavatar']) || isset($_POST['cancelavatar']))
		{
			$username = stripslashes($username);
			$email = stripslashes($email);
			$password = '';
			$password_confirm = '';

			$icq = stripslashes($icq);
			$aim = htmlspecialchars(stripslashes($aim));
			$msn = htmlspecialchars(stripslashes($msn));
			$yim = htmlspecialchars(stripslashes($yim));
			$skype = htmlspecialchars(stripslashes($skype));

			$website = htmlspecialchars(stripslashes($website));
			$location = htmlspecialchars(stripslashes($location));
			$phone = htmlspecialchars(stripslashes($phone));
			$occupation = htmlspecialchars(stripslashes($occupation));
			$interests = htmlspecialchars(stripslashes($interests));
			$selfdes = htmlspecialchars(stripslashes($selfdes));
			$signature = htmlspecialchars(stripslashes($signature));

			$user_lang = stripslashes($user_lang);
			$user_dateformat = htmlspecialchars(stripslashes($user_dateformat));

			if (!isset($_POST['cancelavatar']))
			{
				$user_avatar = $user_avatar_category . '/' . $user_avatar_local;
				$user_avatar_type = USER_AVATAR_GALLERY;
			}
		}
	}

	if(isset($_POST['submit']))
	{
		include(IP_ROOT_PATH . 'includes/usercp_avatar.' . PHP_EXT);

		$error = false;

		if (stripslashes($username) != $this_userdata['username'])
		{
			unset($rename_user);

			if (stripslashes(strtolower($username)) != strtolower($this_userdata['username']))
			{
				$result = validate_username($username);
				if ($result['error'])
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
				}
				elseif (strtolower(str_replace("\\'", "''", $username)) == strtolower($userdata['username']))
				{
					$error = true;
					$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Username_taken'];
				}
			}

			if (!$error)
			{
				$username_sql = "username = '" . str_replace("\\'", "''", $username) . "', ";
				$rename_user = $username; // Used for renaming usergroup
			}
		}

		// Custom Profile Fields MOD - BEGIN
		$profile_data = get_fields();
		$profile_names = array();

		foreach($profile_data as $fields)
		{
			$name = text_to_column($fields['field_name']);
			$type = $fields['field_type'];
			$required = $fields['is_required'] == REQUIRED ? true : false;

			$temp = $_POST[$name];
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
				$temp = is_numeric($temp) ? intval($temp) : htmlspecialchars($temp);
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
				$password = md5($password);
				$passwd_sql = "user_password = '$password', ";
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
			$sig_length_check = preg_replace('/(\[.*?)(=.*?)\]/is', '\\1]', stripslashes($signature));
			if ($allowhtml)
			{
				$sig_length_check = preg_replace('/(\<.*?)(=.*?)(.*?=.*?)?([ \/]?\>)/is', '\\1\\3\\4', $sig_length_check);
			}

			$signature = prepare_message($signature, $allowhtml, $allowbbcode, $allowsmilies);

			if (strlen($sig_length_check) > $board_config['max_sig_chars'])
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Signature_too_long'];
			}
		}
		if (eregi("[^0-9]", $_POST['dst_time_lag']) || ($dst_time_lag < 0) || ($dst_time_lag > 120))
		{
			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['dst_time_lag_error'];
		}

		// Avatar stuff
		$avatar_sql = "";
		if(isset($_POST['avatardel']))
		{
			if($this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "")
			{
				if(@file_exists(@phpbb_realpath('./../' . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar'])))
				{
					@unlink('./../' . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar']);
				}
			}
			$avatar_sql = ", user_avatar = '', user_avatar_type = " . USER_AVATAR_NONE;
		}
		elseif(($user_avatar_loc != "" || !empty($user_avatar_url)) && !$error)
		{
			//
			// Only allow one type of upload, either a
			// filename or a URL
			//
			if(!empty($user_avatar_loc) && !empty($user_avatar_url))
			{
				$error = true;
				if(isset($error_msg))
				{
					$error_msg .= '<br />';
				}
				$error_msg .= $lang['Only_one_avatar'];
			}

			if($user_avatar_loc != "")
			{
				if(file_exists(@phpbb_realpath($user_avatar_loc)) && ereg(".jpg$|.gif$|.png$", $user_avatar_name))
				{
					if($user_avatar_size <= $board_config['avatar_filesize'] && $user_avatar_size > 0)
					{
						$error_type = false;

						//
						// Opera appends the image name after the type, not big, not clever!
						//
						preg_match("'image\/[x\-]*([a-z]+)'", $user_avatar_filetype, $user_avatar_filetype);
						$user_avatar_filetype = $user_avatar_filetype[1];

						switch($user_avatar_filetype)
						{
							case "jpeg":
							case "pjpeg":
							case "jpg":
								$imgtype = '.jpg';
								break;
							case "gif":
								$imgtype = '.gif';
								break;
							case "png":
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

							if($width <= $board_config['avatar_max_width'] && $height <= $board_config['avatar_max_height'])
							{
								$user_id = $this_userdata['user_id'];

								$avatar_filename = $user_id . $imgtype;

								if($this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "")
								{
									if(@file_exists(@phpbb_realpath('./../' . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar'])))
									{
										@unlink('./../' . $board_config['avatar_path'] . "/". $this_userdata['user_avatar']);
									}
								}
								@copy($user_avatar_loc, './../' . $board_config['avatar_path'] . "/$avatar_filename");

								$avatar_sql = ", user_avatar = '" . $avatar_filename . "', user_avatar_type = " . USER_AVATAR_UPLOAD;
							}
							else
							{
								$l_avatar_size = sprintf($lang['Avatar_imagesize'], $board_config['avatar_max_width'], $board_config['avatar_max_height']);

								$error = true;
								$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
							}
						}
					}
					else
					{
						$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

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
				//
				// First check what port we should connect
				// to, look for a :[xxxx]/ or, if that doesn't
				// exist assume port 80 (http)
				//
				preg_match("/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/", $user_avatar_url, $url_ary);

				if(!empty($url_ary[4]))
				{
					$port = (!empty($url_ary[3])) ? $url_ary[3] : 80;

					$fsock = @fsockopen($url_ary[2], $port, $errno, $errstr);
					if($fsock)
					{
						$base_get = "/" . $url_ary[4];

						//
						// Uses HTTP 1.1, could use HTTP 1.0 ...
						//
						@fputs($fsock, "GET $base_get HTTP/1.1\r\n");
						@fputs($fsock, "HOST: " . $url_ary[2] . "\r\n");
						@fputs($fsock, "Connection: close\r\n\r\n");

						unset($avatar_data);
						while(!@feof($fsock))
						{
							$avatar_data .= @fread($fsock, $board_config['avatar_filesize']);
						}
						@fclose($fsock);

						if(preg_match("/Content-Length\: ([0-9]+)[^\/ ][\s]+/i", $avatar_data, $file_data1) && preg_match("/Content-Type\: image\/[x\-]*([a-z]+)[\s]+/i", $avatar_data, $file_data2))
						{
							$file_size = $file_data1[1];
							$file_type = $file_data2[1];

							switch($file_type)
							{
								case "jpeg":
								case "pjpeg":
								case "jpg":
									$imgtype = '.jpg';
									break;
								case "gif":
									$imgtype = '.gif';
									break;
								case "png":
									$imgtype = '.png';
									break;
								default:
									$error = true;
									$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
									break;
							}

							if(!$error && $file_size > 0 && $file_size < $board_config['avatar_filesize'])
							{
								$avatar_data = substr($avatar_data, strlen($avatar_data) - $file_size, $file_size);

								$tmp_filename = tempnam ("/tmp", $this_userdata['user_id'] . "-");
								$fptr = @fopen($tmp_filename, "wb");
								$bytes_written = @fwrite($fptr, $avatar_data, $file_size);
								@fclose($fptr);

								if($bytes_written == $file_size)
								{
									list($width, $height) = @getimagesize($tmp_filename);

									if($width <= $board_config['avatar_max_width'] && $height <= $board_config['avatar_max_height'])
									{
										$user_id = $this_userdata['user_id'];

										$avatar_filename = $user_id . $imgtype;

										if($this_userdata['user_avatar_type'] == USER_AVATAR_UPLOAD && $this_userdata['user_avatar'] != "")
										{
											if(file_exists(@phpbb_realpath('./../' . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar'])))
											{
												@unlink('./../' . $board_config['avatar_path'] . "/" . $this_userdata['user_avatar']);
											}
										}
										@copy($tmp_filename, './../' . $board_config['avatar_path'] . "/$avatar_filename");
										@unlink($tmp_filename);

										$avatar_sql = ", user_avatar = '$avatar_filename', user_avatar_type = " . USER_AVATAR_UPLOAD;
									}
									else
									{
										$l_avatar_size = sprintf($lang['Avatar_imagesize'], $board_config['avatar_max_width'], $board_config['avatar_max_height']);

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
				$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
			}
		}
		elseif($user_avatar_remoteurl != "" && $avatar_sql == "" && !$error)
		{
			if(!preg_match("#^http:\/\/#i", $user_avatar_remoteurl))
			{
				$user_avatar_remoteurl = "http://" . $user_avatar_remoteurl;
			}

			if(preg_match("#^(http:\/\/[a-z0-9\-]+?\.([a-z0-9\-]+\.)*[a-z]+\/.*?\.(gif|jpg|png)$)#is", $user_avatar_remoteurl))
			{
				$avatar_sql = ", user_avatar = '" . str_replace("\'", "''", $user_avatar_remoteurl) . "', user_avatar_type = " . USER_AVATAR_REMOTE;
			}
			else
			{
				$error = true;
				$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Wrong_remote_avatar_format'] : $lang['Wrong_remote_avatar_format'];
			}
		}
		elseif($user_avatar_local != "" && $avatar_sql == "" && !$error)
		{
			$avatar_sql = ", user_avatar = '" . str_replace("\'", "''", phpbb_ltrim(basename($user_avatar_category), "'") . '/' . phpbb_ltrim(basename($user_avatar_local), "'")) . "', user_avatar_type = " . USER_AVATAR_GALLERY;
		}
		elseif($user_gravatar != '' && $avatar_sql == '' && !$error)
		{
			$avatar_sql = ", user_avatar = '" . str_replace("\'", "''", $user_gravatar) . "', user_avatar_type = " . USER_GRAVATAR;
		}

		// Update users post count
		$user_posts = (isset($_POST['user_posts'])) ? intval($_POST['user_posts']) : 0;

		// Start add - Birthday MOD
		// find the birthday values, reflected by the $lang['Submit_date_format']
		if ($birthday_day || $birthday_month || $birthday_year) //if a birthday is submited, then validate it
		{
			$user_age = (date('md') >= $birthday_month . (($birthday_day <= 9) ? '0' : '') . $birthday_day) ? date('Y') - $birthday_year : date('Y') - $birthday_year - 1;
			// Check date, maximum / minimum user age
			if (!checkdate($birthday_month,$birthday_day,$birthday_year))
			{
				$error = true;
				if(isset($error_msg))$error_msg .= '<br />';
				$error_msg .= $lang['Wrong_birthday_format'];
			}
			elseif ($user_age>$board_config['max_user_age'])
			{
				$error = true;
				if(isset($error_msg))$error_msg .= '<br />';
				$error_msg .= sprintf($lang['Birthday_to_high'], $board_config['max_user_age']);
			}
			elseif ($user_age<$board_config['min_user_age'])
			{
				$error = true;
				if(isset($error_msg))$error_msg .= '<br />';
				$error_msg .= sprintf($lang['Birthday_to_low'], $board_config['min_user_age']);
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
		// End add - Birthday MOD

		// Update entry in DB
		if(!$error)
		{
			if ($user_ycard > $board_config['max_user_bancard'])
			{
				$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . " WHERE ban_userid = '" . $user_id . "'";
				if($result = $db->sql_query($sql))
				{
					if ((!$db->sql_fetchrowset($result)) && ($user_id != ANONYMOUS))
					{
						// insert the user in the ban list
						$sql = "INSERT INTO " . BANLIST_TABLE . " (ban_userid) VALUES ($user_id)";
						if (!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, "Couldn't insert ban_userid info into database", "", __LINE__, __FILE__, $sql);
						}
						else
						{
							$no_error_ban = true;
						}
					}
					else
					{
						$no_error_ban = true;
					}
				}
				else
				{
					message_die(GENERAL_ERROR, "Couldn't obtain banlist information", "", __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				// remove the ban, if there is any
				$sql = "DELETE FROM " . BANLIST_TABLE . " WHERE ban_userid = $user_id";
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Couldn't remove ban_userid info into database", "", __LINE__, __FILE__, $sql);
				}
				else
				{
					$no_error_ban = true;
				}
			}

			$db->clear_cache('ban_', USERS_CACHE_FOLDER);
			clear_user_color_cache($user_id);

			$sql = "UPDATE " . USERS_TABLE . "
				SET " . $username_sql . $passwd_sql . "user_email = '" . str_replace("\'", "''", $email) . "', user_icq = '" . str_replace("\'", "''", $icq) . "', user_website = '" . str_replace("\'", "''", $website) . "', user_occ = '" . str_replace("\'", "''", $occupation) . "', user_from = '" . str_replace("\'", "''", $location) . "', user_from_flag = '$user_flag', user_interests = '" . str_replace("\'", "''", $interests) . "', user_phone = '" . str_replace("\'", "''", $phone) . "', user_selfdes = '" . str_replace("\'", "''", $selfdes) . "', user_profile_view_popup = $profile_view_popup, user_birthday = '$birthday', user_birthday_y = '$birthday_year', user_birthday_m = '$birthday_month', user_birthday_d = '$birthday_day', user_next_birthday_greeting=$next_birthday_greeting, user_sig = '" . str_replace("\'", "''", $signature) . "', user_viewemail = $viewemail, user_aim = '" . str_replace("\'", "''", $aim) . "', user_yim = '" . str_replace("\'", "''", $yim) . "', user_msnm = '" . str_replace("\'", "''", $msn) . "', user_skype = '" . str_replace("\'", "''", $skype) . "', user_attachsig = $attachsig, user_setbm = $setbm, user_allowswearywords = $user_allowswearywords, user_showavatars = $user_showavatars, user_showsignatures = $user_showsignatures, user_allowsmile = $allowsmilies, user_allowhtml = $allowhtml, user_allowavatar = $user_allowavatar, user_upi2db_disable = $user_upi2db_disable, user_allowbbcode = $allowbbcode, user_allow_mass_email = $allowmassemail, user_allow_pm_in = $allowpmin, user_allow_viewonline = $allowviewonline, user_notify = $notifyreply, user_allow_pm = $user_allowpm, user_notify_pm = $notifypm, user_popup_pm = $popuppm, user_lang = '" . str_replace("\'", "''", $user_lang) . "', user_style = $user_style, user_posts = $user_posts, user_timezone = $user_timezone, user_time_mode = '$time_mode', user_dst_time_lag = '$dst_time_lag', user_dateformat = '" . str_replace("\'", "''", $user_dateformat) . "', user_posts_per_page = '" . str_replace("\'", "''", $user_posts_per_page) . "', user_topics_per_page = '" . str_replace("\'", "''", $user_topics_per_page) . "', user_hot_threshold = '" . str_replace("\'", "''", $user_hot_threshold) . "', user_active = $user_status, user_warnings = $user_ycard, user_gender = '$gender', user_rank = '" . $user_rank . "', user_rank2 = '" . $user_rank2 . "', user_rank3 = '" . $user_rank3 . "', user_rank4 = '" . $user_rank4 . "', user_rank5 = '" . $user_rank5 . "', user_color_group = '" . $user_color_group . "', user_color = '" . $user_color . "'" . $avatar_sql . "
				WHERE user_id = '" . $user_id . "'";

			if($result = $db->sql_query($sql))
			{
				if(isset($rename_user))
				{
					$sql = "UPDATE " . GROUPS_TABLE . "
						SET group_name = '".str_replace("\'", "''", $rename_user)."'
						WHERE group_name = '".str_replace("'", "''", $this_userdata['username'])."'";
					if(!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not rename users group', '', __LINE__, __FILE__, $sql);
					}
				}

				// Delete user session, to prevent the user navigating the forum (if logged in) when disabled
				if (!$user_status)
				{
					$sql = "DELETE FROM " . SESSIONS_TABLE . "
						WHERE session_user_id = " . $user_id;

					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Error removing user session', '', __LINE__, __FILE__, $sql);
					}
				}

				// We remove all stored login keys since the password has been updated
				// and change the current one (if applicable)
				if (!empty($passwd_sql))
				{
					session_reset_keys($user_id, $user_ip);
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

						$temp = $_POST[$name];
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
							$temp = is_numeric($temp) ? intval($temp) : htmlspecialchars($temp);
						}
						$profile_names[$name] = $temp;

						$sql2 .= $name . " = '".str_replace("\'","''",$profile_names[$name])."', ";
					}
					$sql2 = substr($sql2,0,strlen($sql2)-2)."
						WHERE user_id = ".$this_userdata['user_id'];
					if(!$db->sql_query($sql2))
						message_die(GENERAL_ERROR,'Could not update custom profile fields','',__LINE__,__FILE__,$sql2);
				}
				// Custom Profile Fields - END


				$message .= $lang['Admin_user_updated'];
			}
			else
			{
				message_die(GENERAL_ERROR, 'Admin_user_fail', '', __LINE__, __FILE__, $sql);

			}

			$message .= '<br /><br />' . sprintf($lang['Click_return_useradmin'], '<a href="' . append_sid('admin_users.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
			//Start Quick Administrator User Options and Information MOD
			if($redirect != '')
			{
				$message = $lang['Admin_user_updated'] . '<br /><br />' . sprintf($lang['Click_return_userprofile'], '<a href="' . append_sid('../' . PROFILE_MG . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT) . '">', '</a>');
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
			$email = stripslashes($email);
			$password = '';
			$password_confirm = '';

			$icq = stripslashes($icq);
			$aim = htmlspecialchars(str_replace('+', ' ', stripslashes($aim)));
			$msn = htmlspecialchars(stripslashes($msn));
			$yim = htmlspecialchars(stripslashes($yim));
			$skype = htmlspecialchars(stripslashes($skype));

			$website = htmlspecialchars(stripslashes($website));
			$location = htmlspecialchars(stripslashes($location));
			$phone = htmlspecialchars(stripslashes($phone));
			$occupation = htmlspecialchars(stripslashes($occupation));
			$interests = htmlspecialchars(stripslashes($interests));
			$selfdes = htmlspecialchars(stripslashes($selfdes));
			$signature = htmlspecialchars(stripslashes($signature));

			$user_lang = stripslashes($user_lang);
			$user_dateformat = htmlspecialchars(stripslashes($user_dateformat));
		}
	}
	elseif(!isset($_POST['submit']) && $mode != 'save' && !isset($_POST['avatargallery']) && !isset($_POST['submitavatar']) && !isset($_POST['cancelavatar']))
	{
		if(isset($_GET[POST_USERS_URL]) || isset($_POST[POST_USERS_URL]))
		{
			$user_id = (isset($_POST[POST_USERS_URL])) ? intval($_POST[POST_USERS_URL]) : intval($_GET[POST_USERS_URL]);
			$this_userdata = get_userdata($user_id);
			if(!$this_userdata)
			{
				message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
			}
		}
		else
		{
			$this_userdata = get_userdata($_POST['username'], true);
			if(!$this_userdata)
			{
				message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
			}
		}

		// Now parse and display it as a template
		$user_id = $this_userdata['user_id'];
		$username = $this_userdata['username'];
		$email = $this_userdata['user_email'];
		$password = '';
		$password_confirm = '';

		$icq = $this_userdata['user_icq'];
		$aim = htmlspecialchars(str_replace('+', ' ', $this_userdata['user_aim']));
		$msn = htmlspecialchars($this_userdata['user_msnm']);
		$yim = htmlspecialchars($this_userdata['user_yim']);
		$skype = htmlspecialchars($this_userdata['user_skype']);

		$website = htmlspecialchars($this_userdata['user_website']);
		$location = htmlspecialchars($this_userdata['user_from']);
		$user_flag = htmlspecialchars($this_userdata['user_from_flag']);
		$phone = htmlspecialchars($this_userdata['user_phone']);
		$occupation = htmlspecialchars($this_userdata['user_occ']);
		$interests = htmlspecialchars($this_userdata['user_interests']);
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
		$signature = $this_userdata['user_sig'];
		$selfdes = $this_userdata['user_selfdes'];
		// End replacement - BBCodes & smilies enhancement MOD

		$signature = preg_replace($html_entities_match, $html_entities_replace, $signature);

		$viewemail = $this_userdata['user_viewemail'];
		$allowmassemail = $this_userdata['user_allow_mass_email'];
		$allowpmin = $this_userdata['user_allow_pm_in'];
		$notifypm = $this_userdata['user_notify_pm'];
		$popuppm = $this_userdata['user_popup_pm'];
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
		$user_dateformat = htmlspecialchars($this_userdata['user_dateformat']);

		$user_status = $this_userdata['user_active'];
		$user_ycard = $this_userdata['user_warnings'];
		$user_allowavatar = $this_userdata['user_allowavatar'];
//<!-- BEGIN Unread Post Information to Database Mod -->
		$user_upi2db_disable = $this_userdata['user_upi2db_disable'];
//<!-- BEGIN Unread Post Information to Database Mod -->
		$user_allowpm = $this_userdata['user_allow_pm'];
		$user_posts = $this_userdata['user_posts'];

		$COPPA = false;

		$html_status =  ($this_userdata['user_allowhtml']) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
		$bbcode_status = ($this_userdata['user_allowbbcode']) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
		$smilies_status = ($this_userdata['user_allowsmile']) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];
	}

	if(isset($_POST['avatargallery']) && !$error)
	{
		if(!$error)
		{
			$user_id = intval($_POST['id']);

			$template->set_filenames(array('body' => ADM_TPL . 'user_avatar_gallery.tpl'));

			$dir = @opendir("../" . $board_config['avatar_gallery_path']);

			$avatar_images = array();
			while($file = @readdir($dir))
			{
				if($file != "." && $file != ".." && !is_file(@phpbb_realpath('./../' . $board_config['avatar_gallery_path'] . "/" . $file)) && !is_link(@phpbb_realpath('./../' . $board_config['avatar_gallery_path'] . "/" . $file)))
				{
					$sub_dir = @opendir("../" . $board_config['avatar_gallery_path'] . "/" . $file);

					$avatar_row_count = 0;
					$avatar_col_count = 0;

					while($sub_file = @readdir($sub_dir))
					{
						if(preg_match("/(\.gif$|\.png$|\.jpg)$/is", $sub_file))
						{
							$avatar_images[$file][$avatar_row_count][$avatar_col_count] = $sub_file;

							$avatar_col_count++;
							if($avatar_col_count == 5)
							{
								$avatar_row_count++;
								$avatar_col_count = 0;
							}
						}
					}
				}
			}

			@closedir($dir);

			if(isset($_POST['avatarcategory']))
			{
				$category = htmlspecialchars($_POST['avatarcategory']);
			}
			else
			{
				list($category,) = each($avatar_images);
			}
			@reset($avatar_images);

			$s_categories = "";
			while(list($key) = each($avatar_images))
			{
				$selected = ($key == $category) ? 'selected="selected"' : '';
				if(count($avatar_images[$key]))
				{
					$s_categories .= '<option value="' . $key . '"' . $selected . '>' . ucfirst($key) . '</option>';
				}
			}

			$s_colspan = 0;
			for($i = 0; $i < count($avatar_images[$category]); $i++)
			{
				$template->assign_block_vars('avatar_row', array());

				$s_colspan = max($s_colspan, count($avatar_images[$category][$i]));

				for($j = 0; $j < count($avatar_images[$category][$i]); $j++)
				{
					$template->assign_block_vars('avatar_row.avatar_column', array(
						'AVATAR_IMAGE' => '../' . $board_config['avatar_gallery_path'] . '/' . $category . '/' . $avatar_images[$category][$i][$j])
					);

					$template->assign_block_vars('avatar_row.avatar_option_column', array(
						'S_OPTIONS_AVATAR' => $avatar_images[$category][$i][$j])
					);
				}
			}

			$coppa = ((!$_POST['coppa'] && !$_GET['coppa']) || $mode == 'register') ? 0 : true;

			$s_hidden_fields = '<input type="hidden" name="mode" value="edit" />';
			$s_hidden_fields .= '<input type="hidden" name="agreed" value="true" />';
			$s_hidden_fields .= '<input type="hidden" name="avatarcatname" value="' . $category . '" />';
			$s_hidden_fields .= '<input type="hidden" name="coppa" value="' . $coppa . '" />';
			$s_hidden_fields .= '<input type="hidden" name="id" value="' . $user_id . '" />';

			$s_hidden_fields .= '<input type="hidden" name="username" value="' . str_replace("\"", "&quot;", $username) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="email" value="' . str_replace("\"", "&quot;", $email) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="icq" value="' . str_replace("\"", "&quot;", $icq) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="aim" value="' . str_replace("\"", "&quot;", $aim) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="msn" value="' . str_replace("\"", "&quot;", $msn) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="yim" value="' . str_replace("\"", "&quot;", $yim) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="skype" value="' . str_replace("\"", "&quot;", $skype) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="website" value="' . str_replace("\"", "&quot;", $website) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="location" value="' . str_replace("\"", "&quot;", $location) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_flag" value="' . str_replace("\"", "&quot;", $user_flag) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="phone" value="' . str_replace("\"", "&quot;", $phone) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="occupation" value="' . str_replace("\"", "&quot;", $occupation) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="interests" value="' . str_replace("\"", "&quot;", $interests) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="birthday" value="'.$birthday.'" />';
			$s_hidden_fields .= '<input type="hidden" name="next_birthday_greeting" value="' . $next_birthday_greeting . '" />';
			$s_hidden_fields .= '<input type="hidden" name="selfdes" value="' . str_replace("\"", "&quot;", $selfdes) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="signature" value="' . str_replace("\"", "&quot;", $signature) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="viewemail" value="' . $viewemail . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowmassemail" value="' . $allowmassemail . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowpmin" value="' . $allowpmin . '" />';
			$s_hidden_fields .= '<input type="hidden" name="gender" value="' . $gender . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifypm" value="' . $notifypm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="popup_pm" value="' . $popuppm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifyreply" value="' . $notifyreply . '" />';
			$s_hidden_fields .= '<input type="hidden" name="attachsig" value="' . $attachsig . '" />';
			$s_hidden_fields .= '<input type="hidden" name="setbm" value="' . $setbm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_showavatars" value="' . $user_showavatars . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_showsignatures" value="' . $user_showsignatures . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_allowswearywords" value="' . $user_allowswearywords . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowhtml" value="' . $allowhtml . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowbbcode" value="' . $allowbbcode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowsmilies" value="' . $allowsmilies . '" />';
			$s_hidden_fields .= '<input type="hidden" name="hideonline" value="' . !$allowviewonline . '" />';
			$s_hidden_fields .= '<input type="hidden" name="profile_view_popup" value="' . $profile_view_popup . '" />';
			$s_hidden_fields .= '<input type="hidden" name="style" value="' . $user_style . '" />';
			$s_hidden_fields .= '<input type="hidden" name="language" value="' . $user_lang . '" />';
			$s_hidden_fields .= '<input type="hidden" name="timezone" value="' . $user_timezone . '" />';
			$s_hidden_fields .= '<input type="hidden" name="time_mode" value="' . $time_mode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="dst_time_lag" value="' . $dst_time_lag . '" />';
			$s_hidden_fields .= '<input type="hidden" name="dateformat" value="' . str_replace("\"", "&quot;", $user_dateformat) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_status" value="' . $user_status . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_ycard" value="' . $user_ycard . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_allowpm" value="' . $user_allowpm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_allowavatar" value="' . $user_allowavatar . '" />';
//<!-- BEGIN Unread Post Information to Database Mod -->
			$s_hidden_fields .= '<input type="hidden" name="user_upi2db_disable" value="' . $user_upi2db_disable . '" />';
//<!-- END Unread Post Information to Database Mod -->
			$s_hidden_fields .= '<input type="hidden" name="user_posts" value="' . $user_posts . '" />';
			// Mighty Gorgon - Multiple Ranks - BEGIN
			$s_hidden_fields .= '<input type="hidden" name="user_rank" value="' . $user_rank . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_rank2" value="' . $user_rank2 . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_rank3" value="' . $user_rank3 . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_rank4" value="' . $user_rank4 . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_rank5" value="' . $user_rank5 . '" />';
			// Mighty Gorgon - Multiple Ranks - END
			$s_hidden_fields .= '<input type="hidden" name="user_color_group" value="' . $user_color_group . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_color" value="' . $user_color . '" />';

			$template->assign_vars(array(
				'L_USER_TITLE' => $lang['User_admin'],
				'L_USER_EXPLAIN' => $lang['User_admin_explain'],
				'L_AVATAR_GALLERY' => $lang['Avatar_gallery'],
				'L_SELECT_AVATAR' => $lang['Select_avatar'],
				'L_RETURN_PROFILE' => $lang['Return_profile'],
				'L_CATEGORY' => $lang['Select_category'],
				'L_GO' => $lang['Go'],

				'S_OPTIONS_CATEGORIES' => $s_categories,
				'S_COLSPAN' => $s_colspan,
				'S_PROFILE_ACTION' => append_sid('admin_users.' . PHP_EXT . '?mode=' . $mode),
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
					$avatar = '<img src="../' . $board_config['avatar_path'] . '/' . $user_avatar . '" alt="" />';
					break;
				case USER_AVATAR_REMOTE:
					$avatar = '<img src="' . $user_avatar . '" alt="" />';
					break;
				case USER_AVATAR_GALLERY:
					$avatar = '<img src="../' . $board_config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="" />';
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
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain ranks data', '', __LINE__, __FILE__, $sql);
		}

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
			$user_default_group_select = '<select name="user_color_group">';
			$group_selected = ($this_userdata['user_color_group'] == 0) ? ' selected="selected"' : '';
			$user_default_group_select .= '<option value="0"' . $group_selected . '>' . $lang['No_Default_Group'] . '</option>';
			for ($i = 1; $i <= count($groups_list); $i++)
			{
				$group_selected = ($this_userdata['user_color_group'] == $groups_list[$i]['group_id']) ? ' selected="selected"' : '';
				$user_default_group_select .= '<option value="' . $groups_list[$i]['group_id'] . '"' . $group_selected . '>' . $groups_list[$i]['group_name'] . '</option>';
			}
			$user_default_group_select .= '</select>';
		}
		else
		{
			$user_default_group_select = $lang['No_Groups_Membership'];
			$user_default_group_select .= '<input type="hidden" name="user_color_group" value="0" />';
		}

		$user_color = $this_userdata['user_color'];

		$template->set_filenames(array('body' => ADM_TPL . 'user_edit_body.tpl'));

		// Custom Profile Fields MOD - BEGIN
		$profile_data = get_fields();

		if(count($profile_data) > 0)
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
						if($num < count($radio_list))
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
						if($num < count($check_list))
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
			ORDER BY flag_id";
		if(!$flags_result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Couldn't obtain flags information.", "", __LINE__, __FILE__, $sql);
		}
		$flag_row = $db->sql_fetchrowset($ranksresult);
		$num_flags = $db->sql_numrows($ranksresult) ;

		// Build the html select statement
		$flag_start_image = 'blank.gif' ;
		$selected = (isset($user_flag)) ? '' : ' selected="selected"'  ;
		$flag_select = "<select name=\"user_flag\" onChange=\"document.images['user_flag'].src = 'images/flags/' + this.value;\" >";
		$flag_select .= "<option value=\"blank.gif\"$selected>" . $lang['Select_Country'] . "</option>";
		for ($i = 0; $i < $num_flags; $i++)
		{
			$flag_name = $flag_row[$i]['flag_name'];
			$flag_image = $flag_row[$i]['flag_image'];
			$selected = (isset($user_flag)) ? (($user_flag == $flag_image) ? 'selected="selected"' : '') : '' ;
			$flag_select .= "\t<option value=\"$flag_image\"$selected>$flag_name</option>";
			if (isset($user_flag) && ($user_flag == $flag_image))
			{
				$flag_start_image = $flag_image ;
			}
		}
		$flag_select .= '</select>';
		// Flag End

		// Start add - Birthday MOD
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
			$i_start = date('Y', time()) - $board_config['max_user_age'];
			$i_end = date('Y', time()) - $board_config['min_user_age'];
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
		// End add - Birthday MOD
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

		switch ($board_config['default_time_mode'])
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

		//
		// Let's do an overall check for settings/versions which would prevent
		// us from doing file uploads....
		//
		$ini_val = (phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';
		$form_enctype = (!@$ini_val('file_uploads') || phpversion() == '4.0.4pl1' || !$board_config['allow_avatar_upload'] || (phpversion() < '4.0.3' && @$ini_val('open_basedir') != '')) ? '' : 'enctype="multipart/form-data"';

		$template->assign_vars(array(
			'USERNAME' => $username,
			'EMAIL' => $email,
			'YIM' => $yim,
			'SKYPE' => $skype,
			'ICQ' => $icq,
			'MSN' => $msn,
			'AIM' => $aim,
			'OCCUPATION' => $occupation,
			'INTERESTS' => $interests,

			'FLAG_SELECT' => $flag_select,
			'FLAG_START' => $flag_start_image,
			'PHONE' => $phone,
			'SELFDES' => str_replace('<br />', "\n", $selfdes),
			'L_FLAG' => $lang['Country_Flag'],
			'L_PHONE' => $lang['UserPhone'],
			'L_EXTRA_PROFILE_INFO' => $lang['Extra_profile_info'],
			'L_EXTRA_PROFILE_INFO_EXPLAIN' => sprintf($lang['Extra_profile_info_explain'], $board_config['extra_max']),

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
			'POPUP_PM_YES' => $popuppm ? 'checked="checked"' : '',
			'POPUP_PM_NO' => !$popuppm ? 'checked="checked"' : '',
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
			'POSTS_PER_PAGE' => !$user_posts_per_page ? $board_config['posts_per_page'] : $user_posts_per_page,
			'TOPICS_PER_PAGE' => !$user_topics_per_page ? $board_config['topics_per_page'] : $user_topics_per_page,
			'HOT_TOPIC' => !$user_hot_threshold ? $board_config['hot_threshold'] : $user_hot_threshold,

			'AVATAR' => $avatar,
			'GRAVATAR' => ($user_avatar_type == USER_GRAVATAR) ? $userdata['user_avatar'] : '',
			'LANGUAGE_SELECT' => language_select($user_lang),
			'TIMEZONE_SELECT' => tz_select($user_timezone),
			'TIME_MODE' => $time_mode,
			'TIME_MODE_MANUAL_CHECKED' => $time_mode_manual_checked,
			'TIME_MODE_MANUAL_DST_CHECKED' => $time_mode_manual_dst_checked,
			'TIME_MODE_SERVER_SWITCH_CHECKED' => $time_mode_server_switch_checked,
			'TIME_MODE_FULL_SERVER_CHECKED' => $time_mode_full_server_checked,
			'TIME_MODE_SERVER_PC_CHECKED' => $time_mode_server_pc_checked,
			'TIME_MODE_FULL_PC_CHECKED' => $time_mode_full_pc_checked,
			'DST_TIME_LAG' => $dst_time_lag,
			'STYLE_SELECT' => style_select($user_style, 'style'),
			//'DATE_FORMAT' => $user_dateformat, //OLD phpBB Format
			'DATE_FORMAT' => date_select($user_dateformat,'dateformat'),
			'ALLOW_PM_YES' => ($user_allowpm) ? 'checked="checked"' : '',
			'ALLOW_PM_NO' => (!$user_allowpm) ? 'checked="checked"' : '',
			'ALLOW_AVATAR_YES' => ($user_allowavatar) ? 'checked="checked"' : '',
			'ALLOW_AVATAR_NO' => (!$user_allowavatar) ? 'checked="checked"' : '',
//<!-- BEGIN Unread Post Information to Database Mod -->
			'DISABLE_UPI2DB_YES' => ($user_upi2db_disable) ? 'checked="checked"' : '',
			'DISABLE_UPI2DB_NO' => (!$user_upi2db_disable) ? 'checked="checked"' : '',
//<!-- END Unread Post Information to Database Mod -->
			'USER_ACTIVE_YES' => ($user_status) ? 'checked="checked"' : '',
			'USER_ACTIVE_NO' => (!$user_status) ? 'checked="checked"' : '',
			'BANCARD' => $user_ycard,
			'POSTS' => $user_posts,
			// Mighty Gorgon - Multiple Ranks - BEGIN
			'RANK1_SELECT_BOX' => $rank1_select_box,
			'RANK2_SELECT_BOX' => $rank2_select_box,
			'RANK3_SELECT_BOX' => $rank3_select_box,
			'RANK4_SELECT_BOX' => $rank4_select_box,
			'RANK5_SELECT_BOX' => $rank5_select_box,
			// Mighty Gorgon - Multiple Ranks - END
			'USER_COLOR_GROUP' => $user_default_group_select,
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
			'L_NEXT_BIRTHDAY_GREETING_EXPLAIN' => $lang['Next_birthday_greeting_expain'],
			'L_GENDER' =>$lang['Gender'],
			'L_GENDER_MALE' =>$lang['Male'],
			'L_GENDER_FEMALE' =>$lang['Female'],
			'L_GENDER_NOT_SPECIFY' =>$lang['No_gender_specify'],
			'L_BANCARD' => $lang['ban_card'],
			'L_BANCARD_EXPLAIN' => sprintf($lang['ban_card_explain'], $board_config['max_user_bancard']),
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
//<!-- BEGIN Unread Post Information to Database Mod -->
			'L_DISABLE_UPI2DB' => $lang['user_disable_upi2db'],
//<!-- END Unread Post Information to Database Mod -->
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
			'L_SIGNATURE_EXPLAIN' => sprintf($lang['Signature_explain'], $board_config['max_sig_chars']),
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
			'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="../' . append_sid('faq.' . PHP_EXT . '?mode=bbcode') . '" target="_phpbbcode">', '</a>'),
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

		if(file_exists(@phpbb_realpath('./../' . $board_config['avatar_path'])) && ($board_config['allow_avatar_upload'] == true))
		{
			if ($form_enctype != '')
			{
				$template->assign_block_vars('avatar_local_upload', array());
			}
			$template->assign_block_vars('avatar_remote_upload', array());
		}

		if(file_exists(@phpbb_realpath('./../' . $board_config['avatar_gallery_path'])) && ($board_config['allow_avatar_local'] == true))
		{
			$template->assign_block_vars('avatar_local_gallery', array());
		}

		if($board_config['allow_avatar_remote'] == true)
		{
			$template->assign_block_vars('avatar_remote_link', array());
		}
		if($board_config['enable_gravatars'])
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
		'L_FIND_USERNAME' => $lang['Find_username'],

		'U_SEARCH_USER' => append_sid('../' . SEARCH_MG . '?mode=searchuser'),

		'S_USER_ACTION' => append_sid('admin_users.' . PHP_EXT),
		'S_USER_SELECT' => $select_list)
	);
	$template->pparse('body');

}

include('./page_footer_admin.' . PHP_EXT);

?>