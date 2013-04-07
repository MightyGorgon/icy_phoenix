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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/**
* Data validation ... used primarily but not exclusively by ucp modules
*
* "Master" function for validating a range of data types
*/
function validate_data($data, $val_ary)
{
	global $lang;

	$error = array();

	foreach ($val_ary as $var => $val_seq)
	{
		if (!is_array($val_seq[0]))
		{
			$val_seq = array($val_seq);
		}

		foreach ($val_seq as $validate)
		{
			$function = array_shift($validate);
			array_unshift($validate, $data[$var]);

			if ($result = call_user_func_array('validate_' . $function, $validate))
			{
				// Since errors are checked later for their language file existence, we need to make sure custom errors are not adjusted.
				$error[] = (empty($lang[$result . '_' . strtoupper($var)])) ? $result : $result . '_' . strtoupper($var);
			}
		}
	}

	return $error;
}

/**
* Validate String
*
* @return boolean|string Either false if validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_string($string, $optional = false, $min = 0, $max = 0)
{
	if (empty($string) && $optional)
	{
		return false;
	}

	if ($min && utf8_strlen(htmlspecialchars_decode($string)) < $min)
	{
		return 'TOO_SHORT';
	}
	elseif ($max && utf8_strlen(htmlspecialchars_decode($string)) > $max)
	{
		return 'TOO_LONG';
	}

	return false;
}

/**
* Validate Number
*
* @return boolean|string Either false if validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_num($num, $optional = false, $min = 0, $max = 1E99)
{
	if (empty($num) && $optional)
	{
		return false;
	}

	if ($num < $min)
	{
		return 'TOO_SMALL';
	}
	elseif ($num > $max)
	{
		return 'TOO_LARGE';
	}

	return false;
}

/**
* Validate Date
* @param String $string a date in the dd-mm-yyyy format
* @return boolean
*/
function validate_date($date_string, $optional = false)
{
	$date = explode('-', $date_string);
	if ((empty($date) || (sizeof($date) != 3)) && $optional)
	{
		return false;
	}
	elseif ($optional)
	{
		for ($field = 0; $field <= 1; $field++)
		{
			$date[$field] = (int) $date[$field];
			if (empty($date[$field]))
			{
				$date[$field] = 1;
			}
		}
		$date[2] = (int) $date[2];
		// assume an arbitrary leap year
		if (empty($date[2]))
		{
			$date[2] = 1980;
		}
	}

	if ((sizeof($date) != 3) || !checkdate($date[1], $date[0], $date[2]))
	{
		return 'INVALID';
	}

	return false;
}


/**
* Validate Match
*
* @return boolean|string Either false if validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_match($string, $optional = false, $match = '')
{
	if (empty($string) && $optional)
	{
		return false;
	}

	if (empty($match))
	{
		return false;
	}

	if (!preg_match($match, $string))
	{
		return 'WRONG_DATA';
	}

	return false;
}

//
// Check to see if the username has been taken, or if it is disallowed.
// Also checks if it includes the " character, which we don't allow in usernames.
// Used for registering, changing names, and posting anonymously with a username
//
function validate_username($username)
{
	global $db, $user, $lang;

	// Remove doubled up spaces
	$username = preg_replace('#\s+#', ' ', trim($username));
	$username = phpbb_clean_username($username);
	$sql = get_users_sql($username, false, false, true, false);
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		while ($row = $db->sql_fetchrow($result))
		{
			if (($user->data['session_logged_in'] && ($row['username'] != $user->data['username'])) || !$user->data['session_logged_in'])
			{
				$db->sql_freeresult($result);
				return array('error' => true, 'error_msg' => $lang['Username_taken']);
			}
		}
	}
	$db->sql_freeresult($result);

	$sql = "SELECT group_name
		FROM " . GROUPS_TABLE . "
		WHERE LOWER(group_name) = '" . $db->sql_escape(strtolower($username)) . "'";
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		if ($row = $db->sql_fetchrow($result))
		{
			$db->sql_freeresult($result);
			return array('error' => true, 'error_msg' => $lang['Username_taken']);
		}
	}
	$db->sql_freeresult($result);

	$sql = "SELECT disallow_username
		FROM " . DISALLOW_TABLE;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				if (preg_match("#\b(" . str_replace("\*", ".*?", preg_quote($row['disallow_username'], '#')) . ")\b#i", $username))
				{
					$db->sql_freeresult($result);
					return array('error' => true, 'error_msg' => $lang['Username_disallowed']);
				}
			}
			while($row = $db->sql_fetchrow($result));
		}
	}
	$db->sql_freeresult($result);

	$sql = "SELECT word
		FROM " . WORDS_TABLE;
	$db->sql_return_on_error(true);
	$result = $db->sql_query($sql);
	$db->sql_return_on_error(false);
	if ($result)
	{
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				if (preg_match("#\b(" . str_replace("\*", ".*?", preg_quote($row['word'], '#')) . ")\b#i", $username))
				{
					$db->sql_freeresult($result);
					return array('error' => true, 'error_msg' => $lang['Username_disallowed']);
				}
			}
			while ($row = $db->sql_fetchrow($result));
		}
	}
	$db->sql_freeresult($result);

	if (!preg_match("/^[a-z0-9&\-_ ]+$/i", $username))
	{
		return array('error' => true, 'error_msg' => $lang['Forbidden_characters']);
	}

	// Disallow " and ALT-255 in username.
	if (strstr($username, '"') || strstr($username, '&quot;') || strstr($username, chr(160)) || strstr($username, chr(173)))
	{
		return array('error' => true, 'error_msg' => $lang['Username_invalid']);
	}

	return array('error' => false, 'error_msg' => '');
}

/**
* Check to see if the password meets the complexity settings
*
* @return	boolean|string	Either false if validation succeeded or a string which will be used as the error message (with the variable name appended)
*/
function validate_password($password)
{
	if (!$password)
	{
		return false;
	}

	$pcre = $mbstring = false;

	// generic UTF-8 character types supported?
	if ((version_compare(PHP_VERSION, '5.1.0', '>=') || (version_compare(PHP_VERSION, '5.0.0-dev', '<=') && version_compare(PHP_VERSION, '4.4.0', '>='))) && @preg_match('/\p{L}/u', 'a') !== false)
	{
		$upp = '\p{Lu}';
		$low = '\p{Ll}';
		$let = '\p{L}';
		$num = '\p{N}';
		$sym = '[^\p{Lu}\p{Ll}\p{N}]';
		$pcre = true;
	}
	elseif (function_exists('mb_ereg_match'))
	{
		mb_regex_encoding('UTF-8');
		$upp = '[[:upper:]]';
		$low = '[[:lower:]]';
		$let = '[[:lower:][:upper:]]';
		$num = '[[:digit:]]';
		$sym = '[^[:upper:][:lower:][:digit:]]';
		$mbstring = true;
	}
	else
	{
		$upp = '[A-Z]';
		$low = '[a-z]';
		$let = '[a-zA-Z]';
		$num = '[0-9]';
		$sym = '[^A-Za-z0-9]';
		$pcre = true;
	}

	$chars = array();

	switch ($config['pass_complex'])
	{
		case 'PASS_TYPE_CASE':
			$chars[] = $low;
			$chars[] = $upp;
		break;

		case 'PASS_TYPE_ALPHA':
			$chars[] = $let;
			$chars[] = $num;
		break;

		case 'PASS_TYPE_SYMBOL':
			$chars[] = $low;
			$chars[] = $upp;
			$chars[] = $num;
			$chars[] = $sym;
		break;
	}

	if ($pcre)
	{
		foreach ($chars as $char)
		{
			if (!preg_match('#' . $char . '#u', $password))
			{
				return 'INVALID_CHARS';
			}
		}
	}
	elseif ($mbstring)
	{
		foreach ($chars as $char)
		{
			if (mb_ereg($char, $password) === false)
			{
				return 'INVALID_CHARS';
			}
		}
	}

	return false;
}

// Check to see if email address is banned or already present in the DB
function validate_email($email)
{
	global $db, $lang;

	if ($email != '')
	{
		if (preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $email))
		{
			$sql = "SELECT ban_email
				FROM " . BANLIST_TABLE;
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if ($result)
			{
				if ($row = $db->sql_fetchrow($result))
				{
					do
					{
						$match_email = str_replace('*', '.*?', $row['ban_email']);
						if (preg_match('/^' . $match_email . '$/is', $email))
						{
							$db->sql_freeresult($result);
							return array('error' => true, 'error_msg' => $lang['Email_banned']);
						}
					}
					while($row = $db->sql_fetchrow($result));
				}
			}
			$db->sql_freeresult($result);

			$sql = "SELECT user_email
				FROM " . USERS_TABLE . "
				WHERE LOWER(user_email) = '" . $db->sql_escape(strtolower($email)) . "'";
			$result = $db->sql_query($sql);

			if ($row = $db->sql_fetchrow($result))
			{
				return array('error' => true, 'error_msg' => $lang['Email_taken']);
			}
			$db->sql_freeresult($result);

			return array('error' => false, 'error_msg' => '');
		}
	}

	return array('error' => true, 'error_msg' => $lang['Email_invalid']);
}

//
// Does supplementary validation of optional profile fields. This expects common stuff like trim() and strip_tags()
// to have already been run. Params are passed by-ref, so we can set them to the empty string if they fail.
//
function validate_optional_fields(&$icq, &$aim, &$msnm, &$yim, &$skype, &$website, &$location, &$occupation, &$interests, &$phone, &$selfdes, &$sig)
{
	$check_var_length = array('aim', 'msnm', 'yim', 'skype', 'location', 'occupation', 'interests', 'sig');

	for($i = 0; $i < sizeof($check_var_length); $i++)
	{
		if (strlen($$check_var_length[$i]) < 2)
		{
			$$check_var_length[$i] = '';
		}
	}

	// ICQ number has to be only numbers.
	if (!preg_match('/^[0-9]+$/', $icq))
	{
		$icq = '';
	}

	// website has to start with http://, followed by something with length at least 3 that
	// contains at least one dot.
	if ($website != '')
	{
		if (!preg_match('#^http[s]?:\/\/#i', $website))
		{
			$website = 'http://' . $website;
		}

		if (!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $website))
		{
			$website = '';
		}
	}

	return;
}

?>