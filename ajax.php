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
* alcaeus (mods@alcaeus.org)
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

// Define constant to keep page_header.php from sending headers
define('AJAX_HEADERS', true);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// Get SID and check it
if (isset($_POST['sid']) || isset($_GET['sid']))
{
	$sid = (isset($_POST['sid'])) ? $_POST['sid'] : $_GET['sid'];
}
else
{
	$sid = '';
}
if ($sid != $userdata['session_id'])
{
	$result_ar = array(
		'result' => AJAX_ERROR,
		'error_msg' => 'Invalid session_id'
	);
	AJAX_message_die($result_ar);
}

// Get mode
if (isset($_POST['mode']) || isset($_GET['mode']))
{
	$mode = (isset($_POST['mode'])) ? $_POST['mode'] : $_GET['mode'];
}
else
{
	$mode = '';
}

// Send AJAX headers - this is to prevent browsers from caching possible error pages
AJAX_headers();

if ($mode == 'checkusername_post')
{
	include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

	if (isset($_GET['username']) || isset($_POST['username']))
	{
		$username = (isset($_POST['username'])) ? utf8_rawurldecode($_POST['username']) : utf8_rawurldecode($_GET['username']);
	}
	else
	{
		$username = '';
	}

	$result_code = AJAX_OP_COMPLETED;
	$error_msg = '';
	if (!empty($username))
	{
		$username = phpbb_clean_username($username);

		if (!$userdata['session_logged_in'] || ($userdata['session_logged_in'] && $username != $userdata['username']))
		{
			$result = validate_username($username);
			if ($result['error'])
			{
				$result_code = AJAX_ERROR;
				$error_msg = $result['error_msg'];
			}
		}
	}

	$result_ar = array(
		'result' => $result_code
	);
	if (!empty($error_msg))
	{
		$result_ar['error_msg'] = $error_msg;
	}
	AJAX_message_die($result_ar);
}
elseif (($mode == 'checkusername_pm') || ($mode == 'search_user'))
{
	include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

	// Get username
	if (isset($_GET['username']) || isset($_POST['username']))
	{
		$username = (isset($_POST['username'])) ? utf8_rawurldecode($_POST['username']) : utf8_rawurldecode($_GET['username']);
	}
	else
	{
		$username = '';
	}
	if (isset($_GET['search']) || isset($_POST['search']))
	{
		$search = (isset($_POST['search'])) ? intval($_POST['search']) : intval($_GET['search']);
	}
	else
	{
		$search = 0;
	}

	if (empty($username))
	{
		if ($mode == 'checkusername_pm')
		{
			$error_msg = $lang['No_to_user'];
		}
		elseif (!$search)
		{
			$error_msg = $lang['No_username'];
		}
		else
		{
			$error_msg = '&nbsp;';
		}
		$result_ar = array(
			'result' => AJAX_PM_USERNAME_ERROR,
			'error_msg' => $error_msg
		);
		AJAX_message_die($result_ar);
	}

	$username = phpbb_clean_username($username);
	if ($mode == 'search_user')
	{
		$has_wildcards = (strpos($username, '*') !== false) ? true : false;
		$username = preg_replace('#\*#', '%', phpbb_clean_username($username));
	}

	$username_row = false;
	if (($mode == 'checkusername_pm') || (($mode == 'search_user') && !$has_wildcards))
	{
		$sql = 'SELECT user_id
						FROM '. USERS_TABLE ."
						WHERE username = '$username'
						AND user_id <> " . ANONYMOUS;
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			$result_ar = array(
				'result' => AJAX_OP_COMPLETED
			);
			AJAX_message_die($result_ar);
		}
		$username_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
	}

	if ($username_row)
	{
		$result_ar = array(
			'result' => AJAX_PM_USERNAME_FOUND
		);
		AJAX_message_die($result_ar);
	}
	else
	{
		if (substr($username, -1, 1) !== '%')
		{
			$username .= '%';
		}
		$sql = "SELECT username
						FROM " . USERS_TABLE . "
						WHERE username LIKE '" . $username . "'
						AND user_id <> " . ANONYMOUS . "
						ORDER BY username";
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql);
		$db->sql_return_on_error(false);
		if (!$result)
		{
			$result_ar = array(
				'result' => AJAX_OP_COMPLETED
			);
			AJAX_message_die($result_ar);
		}
		$username_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		if (!($username_count = sizeof($username_rows)))
		{
			$result_ar = array(
				'result' => AJAX_PM_USERNAME_ERROR,
				'error_msg' => $lang['No_such_user']
			);
			AJAX_message_die($result_ar);
		}
		else
		{
			if ($mode == 'checkusername_pm')
			{
				$username_select = '&nbsp;<select onclick="AJAXSelectPMUsername(this)" onblur="AJAXSelectPMUsername(this);" tabindex="1">';
			}
			else
			{
				if ($search)
				{
					$username_select = '<select name="username_list" onclick="refresh_username(this.form.username_list.options[this.form.username_list.selectedIndex].value);" onblur="refresh_username(this.form.username_list.options[this.form.username_list.selectedIndex].value);">';
				}
				else
				{
					$username_select = '&nbsp;<select onclick="AJAXSelectUsername(this)" onblur="AJAXSelectUsername(this);" tabindex="1">';
				}
			}
			$username_select .= '<option value="-1"> --- </option>';
			for ($i = 0; $i < $username_count; $i++)
			{
				$username_select .= '<option value="'. $username_rows[$i]['username'] .'">'. $username_rows[$i]['username'] .'</option>';
			}
			$username_select .= '</select>';

			$result_ar = array(
				'result' => AJAX_PM_USERNAME_SELECT,
				'error_msg' => $username_select
			);
			AJAX_message_die($result_ar);
		}
	}

	$result_ar = array(
		'result' => AJAX_OP_COMPLETED
	);
	AJAX_message_die($result_ar);
}
elseif ($mode == 'checkemail')
{
	include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

	if (isset($_GET['email']) || isset($_POST['email']))
	{
		$email = (isset($_POST['email'])) ? stripslashes(utf8_rawurldecode($_POST['email'])) : stripslashes(utf8_rawurldecode($_GET['email']));
	}
	else
	{
		$email = '';
	}

	$result_code = AJAX_OP_COMPLETED;
	$error_msg = '';
	if ((!empty($email)) && ((($email != $userdata['user_email']) && $userdata['session_logged_in']) || !$userdata['session_logged_in']))
	{
		$result = validate_email($email);
		if ($result['error'])
		{
			$result_code = AJAX_ERROR;
			$error_msg = $result['error_msg'];
		}
	}

	$result_ar = array(
		'result' => $result_code
	);
	if (!empty($error_msg))
	{
		$result_ar['error_msg'] = $error_msg;
	}
	AJAX_message_die($result_ar);
}
else
{
	$result_ar = array(
		'result' => AJAX_ERROR,
		'error_msg' => 'Invalid mode: ' . $mode
	);
	AJAX_message_die($result_ar);
}

?>