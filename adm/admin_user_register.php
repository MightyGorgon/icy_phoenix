<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1610_Users']['180_Add_New_User'] = $filename;
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('./pagestart.' . PHP_EXT);

// FUNCTIONS - BEGIN
if (!function_exists('dateformatselect'))
{
	function dateformatselect($default, $timezone, $select_name = 'dateformat')
	{
		global $board_config;

		//---------------------------------------------------
		$date_formats[] = 'Y/m/d - H:i';
		$date_formats[] = 'Y.m.d - H:i';
		$date_formats[] = 'd/m/Y - H:i';
		$date_formats[] = 'd.m.Y - H:i';
		//---------------------------------------------------
		$date_formats[] = 'F d Y, H:i';
		$date_formats[] = 'F d Y, G:i';
		$date_formats[] = 'F d Y, h:i A';
		$date_formats[] = 'F d Y';
		//---------------------------------------------------
		$date_formats[] = 'd F Y';
		$date_formats[] = 'd F Y, H:i';
		$date_formats[] = 'd F Y, G:i';
		$date_formats[] = 'd F Y, h:i A';
		//---------------------------------------------------
		$date_formats[] = 'l, d F Y';
		$date_formats[] = 'l, d F Y, H:i';
		$date_formats[] = 'l, d F Y, G:i';
		$date_formats[] = 'l, d F Y, h:i A';
		//---------------------------------------------------
		$date_formats[] = 'D, M d Y';
		$date_formats[] = 'D, M d Y, H:i';
		$date_formats[] = 'D, M d Y, G:i';
		$date_formats[] = 'D, M d Y, h:i A';
		//---------------------------------------------------
		$date_formats[] = 'D d M';
		$date_formats[] = 'D d M, Y H:i';
		$date_formats[] = 'D d M, Y G:i';
		$date_formats[] = 'D d M, Y h:i A';
		//---------------------------------------------------
		$date_formats[] = 'd/m/Y';
		$date_formats[] = 'd/m/Y H:i';
		$date_formats[] = 'd/m/Y G:i';
		$date_formats[] = 'd/m/Y h:i A';
		//---------------------------------------------------
		$date_formats[] = 'm/d/Y';
		$date_formats[] = 'm/d/Y H:i';
		$date_formats[] = 'm/d/Y G:i';
		$date_formats[] = 'm/d/Y h:i A';
		//---------------------------------------------------
		$date_formats[] = 'm.d.Y';
		$date_formats[] = 'm.d.Y H:i';
		$date_formats[] = 'm.d.Y G:i';
		$date_formats[] = 'm.d.Y h:i A';
		//---------------------------------------------------
		$date_formats[] = 'd.m.Y';
		$date_formats[] = 'd.m.Y H:i';
		$date_formats[] = 'd.m.Y G:i';
		$date_formats[] = 'd.m.Y h:i A';
		//---------------------------------------------------

		// Include any valid PHP date format strings here, in your preferred order
		/*
		$date_formats = array(
			'D d.M, Y',
			'D d.M, Y g:i a',
			'D d.M, Y H:i',
			'D M d, Y',
			'D M d, Y g:i a',
			'D M d, Y H:i',
			'n.F Y',
			'n.F Y, g:i a',
			'n.F Y, H:i',
			'F jS Y',
			'F jS Y, g:i a',
			'F jS Y, H:i',
			'j/n/Y',
			'j/n/Y, g:i a',
			'j/n/Y, H:i',
			'n/j/Y',
			'n/j/Y, g:i a',
			'n/j/Y, H:i',
			'Y-m-d',
			'Y-m-d, g:i a',
			'Y-m-d, H:i'
		);
		*/

		if (!isset($timezone))
		{
			$timezone == $board_config['board_timezone'];
		}
		$now = time() + (3600 * $timezone);

		$df_select = '<select name="' . $select_name . '">';
		for ($i = 0; $i < count($date_formats); $i++)
		{
			$format = $date_formats[$i];
			$display = date($format, $now);
			$df_select .= '<option value="' . $format . '"';
			if (isset($default) && ($default == $format))
			{
				$df_select .= ' selected';
			}
			$df_select .= '>' . $display . '</option>';
		}
		$df_select .= '</select>';

		return $df_select;
	}
}
// FUNCTIONS - END

$unhtml_specialchars_match = array('#>#', '#<#', '#"#', '#&#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

$error = false;
$page_title = $lang['Register'];

$coppa = (empty($_POST['coppa']) && empty($_GET['coppa'])) ? 0 : TRUE;

$sql = "SELECT config_value
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'board_timezone'";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not select default dateformat', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);
$board_config['board_timezone'] = $row['config_value'];
$db->sql_freeresult($result);

// Check and initialize some variables if needed
if (isset($_POST['submit']) || ($mode == 'register'))
{
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

	$strip_var_list = array('username' => 'username', 'email' => 'email', 'new_password' => 'new_password', 'password_confirm' => 'password_confirm');

	// Strip all tags from data ... may p**s some people off, bah, strip_tags is
	// doing the job but can still break HTML output ... have no choice, have
	// to use htmlspecialchars ... be prepared to be moaned at.
	while(list($var, $param) = @each($strip_var_list))
	{
		if (!empty($_POST[$param]))
		{
			$$var = trim(htmlspecialchars($_POST[$param]));
		}
	}

	$user_style = (isset($_POST['style'])) ? intval($_POST['style']) : $board_config['default_style'];

	if (!empty($_POST['language']))
	{
		if (preg_match('/^[a-z_]+$/i', $_POST['language']))
		{
			$user_lang = htmlspecialchars($_POST['language']);
		}
		else
		{
			$error = true;
			$error_msg = $lang['Fields_empty'];
		}
	}
	else
	{
		$user_lang = $board_config['default_lang'];
	}

	$user_timezone = (isset($_POST['timezone'])) ? doubleval($_POST['timezone']) : $board_config['board_timezone'];
	$sql = "SELECT config_value
		FROM " . CONFIG_TABLE . "
		WHERE config_name = 'default_dateformat'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not select default dateformat', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$board_config['default_dateformat'] = $row['config_value'];
	$db->sql_freeresult($result);

	$user_dateformat = (!empty($_POST['dateformat'])) ? trim(htmlspecialchars($_POST['dateformat'])) : $board_config['default_dateformat'];

	if (!isset($_POST['submit']))
	{
		$username = stripslashes($username);
		$email = stripslashes($email);
		$cur_password = htmlspecialchars(stripslashes($cur_password));
		$new_password = htmlspecialchars(stripslashes($new_password));
		$password_confirm = htmlspecialchars(stripslashes($password_confirm));

		$user_lang = stripslashes($user_lang);
		$user_dateformat = stripslashes($user_dateformat);
	}
}

//
// Let's make sure the user isn't logged in while registering,
// and ensure that they were trying to register a second time
// (Prevents double registrations)
//
if ($mode == 'register' && ($userdata['session_logged_in'] || $username == $userdata['username']))
{
	message_die(GENERAL_MESSAGE, $lang['Username_taken'], '', __LINE__, __FILE__);
}

// Did the user submit? In this case build a query to update the users profile in the DB
if (isset($_POST['submit']))
{
	$passwd_sql = '';
	if (empty($username) || empty($new_password) || empty($password_confirm) || empty($email))
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Fields_empty'];
	}
	elseif ((empty($new_password) && !empty($password_confirm)) || (!empty($new_password) && empty($password_confirm)) || ($new_password != $password_confirm))
	{
		$error = true;
		$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $lang['Password_mismatch'];
	}

	// Do a ban check on this email address
	if ($email != $userdata['user_email'] || $mode == 'register')
	{
		$result = validate_email($email);
		if ($result['error'])
		{
			$email = $userdata['user_email'];

			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
		}
	}

	$username_sql = '';
	if (empty($username))
	{
		$error = true;
	}
	elseif ($username != $userdata['username'] || $mode == 'register')
	{
		if (strtolower($username) != strtolower($userdata['username']))
		{
			$result = validate_username($username);
			if ($result['error'])
			{
				$error = true;
				$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
			}
		}

		if (!$error)
		{
			$username_sql = "username = '" . str_replace("\'", "''", $username) . "', ";
		}
	}

	if (!$error)
	{
		$sql = "SELECT MAX(user_id) AS total
			FROM " . USERS_TABLE;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}

		if (!($row = $db->sql_fetchrow($result)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}
		$user_id = $row['total'] + 1;

		$new_password = md5($new_password);

		$sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_regdate, user_password, user_email, user_style, user_timezone, user_dateformat, user_lang, user_level, user_active, user_actkey)
			VALUES ($user_id, '" . str_replace("\'", "''", $username) . "', " . time() . ", '" . str_replace("\'", "''", $new_password) . "', '" . str_replace("\'", "''", $email) . "', $user_style, $user_timezone, '" . str_replace("\'", "''", $user_dateformat) . "', '" . str_replace("\'", "''", $user_lang) . "', 0, 1, 'user_actkey')";
		if (!($result = $db->sql_query($sql, BEGIN_TRANSACTION)))
		{
			message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
		}

		$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
			VALUES ('', 'Personal User', 1, 0)";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
		}

		$group_id = $db->sql_nextid();

		$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
			VALUES ($user_id, $group_id, 0)";
		if(!($result = $db->sql_query($sql, END_TRANSACTION)))
		{
			message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
		}

		board_stats();

		$message = $lang['Account_added'];
		message_die(GENERAL_MESSAGE, $message);
	}
} // End of submit

if ($error)
{
	//
	// If an error occured we need to stripslashes on returned data
	//
	$username = stripslashes($username);
	$email = stripslashes($email);
	$new_password = '';
	$password_confirm = '';

	$user_lang = stripslashes($user_lang);
	$user_dateformat = stripslashes($user_dateformat);

}

//
// Default pages
//
include(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

$coppa = FALSE;

if (!isset($user_template))
{
	$selected_template = $board_config['system_template'];
}

$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="coppa" value="' . $coppa . '" />';

if ($error)
{
	$template->set_filenames(array('reg_header' => 'error_body.tpl'));
	$template->assign_vars(array(
		'ERROR_MESSAGE' => $error_msg)
	);
	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
}

$template->set_filenames(array('body' => ADM_TPL . 'admin_add_user_body.tpl'));

//
// Let's do an overall check for settings/versions which would prevent
// us from doing file uploads....
//
$template->assign_vars(array(
	'USERNAME' => $username,
	'CUR_PASSWORD' => $cur_password,
	'NEW_PASSWORD' => $new_password,
	'PASSWORD_CONFIRM' => $password_confirm,
	'EMAIL' => $email,
	'LANGUAGE_SELECT' => language_select($board_config['default_lang'], 'language'),
	'STYLE_SELECT' => style_select($board_config['default_style'], 'style'),
	'TIMEZONE_SELECT' => tz_select($board_config['board_timezone'], 'timezone'),
	'DATE_FORMAT_SELECT' => dateformatselect($board_config['default_dateformat'], $user_timezone),

	'L_USERNAME' => $lang['Username'],
	'L_CURRENT_PASSWORD' => $lang['Current_password'],
	'L_NEW_PASSWORD' => ($mode == 'register') ? $lang['Password'] : $lang['New_password'],
	'L_CONFIRM_PASSWORD' => $lang['Confirm_password'],
	'L_CONFIRM_PASSWORD_EXPLAIN' => ($mode == 'editprofile') ? $lang['Confirm_password_explain'] : '',
	'L_PASSWORD_IF_CHANGED' => ($mode == 'editprofile') ? $lang['password_if_changed'] : '',
	'L_PASSWORD_CONFIRM_IF_CHANGED' => ($mode == 'editprofile') ? $lang['password_confirm_if_changed'] : '',
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'L_BOARD_LANGUAGE' => $lang['Board_lang'],
	'L_BOARD_STYLE' => $lang['Board_style'],
	'L_TIMEZONE' => $lang['Timezone'],
	'L_DATE_FORMAT' => $lang['Date_format'],
	'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],

	'L_ITEMS_REQUIRED' => $lang['Items_required'],
	'L_PREFERENCES' => $lang['Preferences'],
	'L_REGISTRATION_INFO' => $lang['Registration_info'],
	'L_PROFILE_INFO' => $lang['Profile_info'],
	'L_PROFILE_INFO_NOTICE' => $lang['Profile_info_warn'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_VALIDATION' => $lang['Validation'],
	'L_VALIDATION_EXPLAIN' => $lang['Validation_explain'],

	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_PROFILE_ACTION' => append_sid("admin_user_register." . PHP_EXT))
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM .'/page_footer_admin.' . PHP_EXT);

?>