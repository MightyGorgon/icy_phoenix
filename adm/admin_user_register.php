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
require('pagestart.' . PHP_EXT);

$unhtml_specialchars_match = array('#>#', '#<#', '#"#', '#&#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

$error = false;
$meta_content['page_title'] = $lang['Register'];

$sql = "SELECT config_value
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'board_timezone'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$config['board_timezone'] = $row['config_value'];
$db->sql_freeresult($result);

// Check and initialize some variables if needed
if (isset($_POST['submit']))
{
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

	$username = request_post_var('username', '', true);
	$username = htmlspecialchars_decode($username, ENT_COMPAT);
	$new_password = request_post_var('new_password', '', true);
	$new_password = htmlspecialchars_decode($new_password, ENT_COMPAT);
	$password_confirm = request_post_var('password_confirm', '', true);
	$password_confirm = htmlspecialchars_decode($password_confirm, ENT_COMPAT);

	$strip_var_list = array(
		'user_first_name' => 'user_first_name',
		'user_last_name' => 'user_last_name',
		'email' => 'email',
	);

	while(list($var, $param) = @each($strip_var_list))
	{
		$$var = request_post_var($param, '', true);
	}

	$user_style = request_post_var('style', $config['default_style']);
	$user_lang = request_post_var('language', $config['default_lang']);
	$user_timezone = request_post_var('timezone', $config['board_timezone']);
	$user_dateformat = request_post_var('dateformat', $config['default_dateformat']);
}

if (!empty($username) && ($username == $user->data['username']))
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
	if ($email != $user->data['user_email'])
	{
		$result = validate_email($email);
		if ($result['error'])
		{
			$email = $user->data['user_email'];

			$error = true;
			$error_msg .= ((isset($error_msg)) ? '<br />' : '') . $result['error_msg'];
		}
	}

	$username_sql = '';
	if (empty($username))
	{
		$error = true;
	}
	elseif ($username != $user->data['username'])
	{
		if (strtolower($username) != strtolower($user->data['username']))
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
			$username_sql = "username = '" . $db->sql_escape($username) . "', username_clean = '" . $db->sql_escape(utf8_clean_string($username)) . "', ";
		}
	}

	if (!$error)
	{
		$sql = "SELECT MAX(user_id) AS total
			FROM " . USERS_TABLE;
		$result = $db->sql_query($sql);

		if (!($row = $db->sql_fetchrow($result)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}
		$user_id = $row['total'] + 1;

		$clean_password = $new_password;
		$new_password = phpbb_hash($new_password);

		$user_insert_array = array(
			'user_id' => $user_id,
			'username' => $username,
			'username_clean' => utf8_clean_string($username),
			'user_first_name' => $user_first_name,
			'user_last_name' => $user_last_name,
			'user_regdate' => time(),
			'user_password' => $new_password,
			'user_email' => $email,
			'user_email_hash' => phpbb_email_hash($email),
			'user_style' => $user_style,
			'user_timezone' => $user_timezone,
			'user_dateformat' => $user_dateformat,
			'user_lang' => $user_lang,
			'user_level' => 0,
			'user_active' => 1,
			'user_actkey' => 'user_actkey'
		);
		$sql = "INSERT INTO " . USERS_TABLE . " " . $db->sql_build_insert_update($user_insert_array, true);
		$db->sql_transaction('begin');
		$result = $db->sql_query($sql);

		$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
			VALUES ('', 'Personal User', 1, 0)";
		$result = $db->sql_query($sql);

		$group_id = $db->sql_nextid();

		$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
			VALUES ($user_id, $group_id, 0)";
		$result = $db->sql_query($sql);
		$db->sql_transaction('commit');

		// PROFILE EDIT BRIDGE - BEGIN
		$target_profile_data = array(
			'user_id' => $user_id,
			'username' => $username,
			'first_name' => $user_first_name,
			'last_name' => $user_last_name,
			'password' => $clean_password,
			'email' => $email
		);
		if (!class_exists('class_users'))
		{
			include_once(IP_ROOT_PATH . 'includes/class_users.' . PHP_EXT);
		}
		if (empty($class_users))
		{
			$class_users = new class_users();
		}
		$class_users->profile_update($target_profile_data);
		unset($clean_password);
		unset($target_profile_data);
		// PROFILE EDIT BRIDGE - END

		board_stats();

		$message = $lang['Account_added'];
		message_die(GENERAL_MESSAGE, $message);
	}
} // End of submit

if ($error)
{
	// If an error occured we need to htmlspecialchars again username for output on returned data
	$username = htmlspecialchars($username);
	$new_password = '';
	$password_confirm = '';
}

// Default pages
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if ($error)
{
	$template->set_filenames(array('reg_header' => 'error_body.tpl'));
	$template->assign_vars(array(
		'ERROR_MESSAGE' => $error_msg
		)
	);
	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
}

$template->set_filenames(array('body' => ADM_TPL . 'admin_add_user_body.tpl'));

// Let's do an overall check for settings/versions which would prevent us from doing file uploads....
$template->assign_vars(array(
	'USERNAME' => $username,
	'USER_FIRST_NAME' => $user_first_name,
	'USER_LAST_NAME' => $user_last_name,
	'CUR_PASSWORD' => $cur_password,
	'NEW_PASSWORD' => $new_password,
	'PASSWORD_CONFIRM' => $password_confirm,
	'EMAIL' => $email,
	'LANGUAGE_SELECT' => language_select('language', $config['default_lang']),
	'STYLE_SELECT' => style_select('style', $config['default_style']),
	'TIMEZONE_SELECT' => tz_select('timezone', $config['board_timezone']),
	'DATE_FORMAT_SELECT' => date_select('dateformat', $config['default_dateformat']),

	'L_USERNAME' => $lang['Username'],
	'L_CURRENT_PASSWORD' => $lang['Current_password'],
	'L_NEW_PASSWORD' => $lang['Password'],
	'L_CONFIRM_PASSWORD' => $lang['Confirm_password'],
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
	'S_PROFILE_ACTION' => append_sid('admin_user_register.' . PHP_EXT)
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>