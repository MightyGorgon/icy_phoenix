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

$unhtml_specialchars_match = array('#>#', '#<#', '#"#', '#&#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

$error = false;
$meta_content['page_title'] = $lang['Register'];

$coppa = (empty($_POST['coppa']) && empty($_GET['coppa'])) ? 0 : true;

$sql = "SELECT config_value
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'board_timezone'";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$config['board_timezone'] = $row['config_value'];
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

	$user_style = (isset($_POST['style'])) ? intval($_POST['style']) : $config['default_style'];

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
		$user_lang = $config['default_lang'];
	}

	$user_timezone = (isset($_POST['timezone'])) ? doubleval($_POST['timezone']) : $config['board_timezone'];
	$sql = "SELECT config_value
		FROM " . CONFIG_TABLE . "
		WHERE config_name = 'default_dateformat'";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$config['default_dateformat'] = $row['config_value'];
	$db->sql_freeresult($result);

	$user_dateformat = (!empty($_POST['dateformat'])) ? trim(htmlspecialchars($_POST['dateformat'])) : $config['default_dateformat'];

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
if ($mode == 'register' && ($userdata['session_logged_in'] || ($username == $userdata['username'])))
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
	if (($email != $userdata['user_email']) || ($mode == 'register'))
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
	elseif (($username != $userdata['username']) || ($mode == 'register'))
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
		$result = $db->sql_query($sql);

		if (!($row = $db->sql_fetchrow($result)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
		}
		$user_id = $row['total'] + 1;

		$new_password = md5($new_password);

		$sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_regdate, user_password, user_email, user_style, user_timezone, user_dateformat, user_lang, user_level, user_active, user_actkey)
			VALUES ($user_id, '" . str_replace("\'", "''", $username) . "', " . time() . ", '" . str_replace("\'", "''", $new_password) . "', '" . str_replace("\'", "''", $email) . "', $user_style, $user_timezone, '" . str_replace("\'", "''", $user_dateformat) . "', '" . str_replace("\'", "''", $user_lang) . "', 0, 1, 'user_actkey')";
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

// Default pages
include(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

$coppa = false;

if (!isset($user_template))
{
	$selected_template = $config['system_template'];
}

$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="coppa" value="' . $coppa . '" />';

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
	'LANGUAGE_SELECT' => language_select($config['default_lang'], 'language'),
	'STYLE_SELECT' => style_select($config['default_style'], 'style'),
	'TIMEZONE_SELECT' => tz_select($config['board_timezone'], 'timezone'),
	'DATE_FORMAT_SELECT' => date_select($config['default_dateformat'], 'dateformat'),

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
	'S_PROFILE_ACTION' => append_sid('admin_user_register.' . PHP_EXT)
	)
);

$template->pparse('body');

include(IP_ROOT_PATH . ADM .'/page_footer_admin.' . PHP_EXT);

?>