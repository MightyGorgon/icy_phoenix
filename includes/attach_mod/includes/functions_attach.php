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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

/**
* All Attachment Functions needed everywhere
*/

/**
* html_entity_decode replacement (from php manual)
*/
if (!function_exists('html_entity_decode'))
{
	function html_entity_decode($given_html, $quote_style = ENT_QUOTES)
	{
		$trans_table = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style));
		$trans_table['&#39;'] = "'";
		return (strtr($given_html, $trans_table));
	}
}

/**
* A simple dectobase64 function
*/
function base64_pack($number)
{
	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-';
	$base = strlen($chars);

	if ($number > 4096)
	{
		return;
	}
	elseif ($number < $base)
	{
		return $chars[$number];
	}

	$hexval = '';

	while ($number > 0)
	{
		$remainder = $number%$base;

		if ($remainder < $base)
		{
			$hexval = $chars[$remainder] . $hexval;
		}

		$number = floor($number/$base);
	}

	return $hexval;
}

/**
* base64todec function
*/
function base64_unpack($string)
{
	$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-';
	$base = strlen($chars);

	$length = strlen($string);
	$number = 0;

	for($i = 1; $i <= $length; $i++)
	{
		$pos = $length - $i;
		$operand = strpos($chars, substr($string,$pos,1));
		$exponent = pow($base, $i-1);
		$decValue = $operand * $exponent;
		$number += $decValue;
	}

	return $number;
}

/**
* Used for determining if Forum ID is authed, please use this Function on all Posting Screens
*/
function is_forum_authed($auth_cache, $check_forum_id)
{
	$one_char_encoding = '#';
	$two_char_encoding = '.';

	if (trim($auth_cache) == '')
	{
		return true;
	}

	$auth = array();
	$auth_len = 1;

	for ($pos = 0; $pos < strlen($auth_cache); $pos+=$auth_len)
	{
		$forum_auth = substr($auth_cache, $pos, 1);
		if ($forum_auth == $one_char_encoding)
		{
			$auth_len = 1;
			continue;
		}
		elseif ($forum_auth == $two_char_encoding)
		{
			$auth_len = 2;
			$pos--;
			continue;
		}

		$forum_auth = substr($auth_cache, $pos, $auth_len);
		$forum_id = (int) base64_unpack($forum_auth);
		if ($forum_id == $check_forum_id)
		{
			return true;
		}
	}
	return false;
}

/**
* Init FTP Session
*/
function attach_init_ftp($mode = false)
{
	global $lang, $attach_config;

	$server = (trim($attach_config['ftp_server']) == '') ? 'localhost' : trim($attach_config['ftp_server']);

	$ftp_path = ($mode == MODE_THUMBNAIL) ? trim($attach_config['ftp_path']) . '/' . THUMB_DIR : trim($attach_config['ftp_path']);

	$conn_id = @ftp_connect($server);

	if (!$conn_id)
	{
		message_die(GENERAL_ERROR, sprintf($lang['Ftp_error_connect'], $server));
	}

	$login_result = @ftp_login($conn_id, $attach_config['ftp_user'], $attach_config['ftp_pass']);

	if (!$login_result)
	{
		message_die(GENERAL_ERROR, sprintf($lang['Ftp_error_login'], $attach_config['ftp_user']));
	}

	if (!@ftp_pasv($conn_id, intval($attach_config['ftp_pasv_mode'])))
	{
		message_die(GENERAL_ERROR, $lang['Ftp_error_pasv_mode']);
	}

	$result = @ftp_chdir($conn_id, $ftp_path);

	if (!$result)
	{
		message_die(GENERAL_ERROR, sprintf($lang['Ftp_error_path'], $ftp_path));
	}

	return $conn_id;
}

/**
* Count Filesize of Attachments in Database based on the attachment id
*/
function get_total_attach_filesize($attach_ids)
{
	global $db;

	if (!is_array($attach_ids) || !count($attach_ids))
	{
		return 0;
	}

	$attach_ids = implode(', ', array_map('intval', $attach_ids));

	if (!$attach_ids)
	{
		return 0;
	}

	$sql = 'SELECT filesize
		FROM ' . ATTACHMENTS_DESC_TABLE . "
		WHERE attach_id IN ($attach_ids)";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query Total Filesize', '', __LINE__, __FILE__, $sql);
	}

	$total_filesize = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		$total_filesize += (int) $row['filesize'];
	}
	$db->sql_freeresult($result);

	return $total_filesize;
}

/**
* Realpath replacement for attachment mod
*/
function amod_realpath($path)
{
	return (function_exists('realpath')) ? realpath($path) : $path;
}

/**
* Escaping SQL
*/
function attach_mod_sql_escape($text)
{
	switch (SQL_LAYER)
	{
		case 'mysql':
		case 'mysql4':
			if (function_exists('mysql_escape_string'))
			{
				return mysql_escape_string($text);
			}
			else
			{
				return str_replace("'", "''", str_replace('\\', '\\\\', $text));
			}
		break;

		default:
			return str_replace("'", "''", str_replace('\\', '\\\\', $text));
		break;
	}
}

/**
* get all attachments from a post (could be an post array too)
*/
function get_attachments_from_post($post_id_array)
{
	global $db, $attach_config;

	$attachments = array();

	if (!is_array($post_id_array))
	{
		if (empty($post_id_array))
		{
			return $attachments;
		}

		$post_id = intval($post_id_array);

		$post_id_array = array();
		$post_id_array[] = $post_id;
	}

	$post_id_array = implode(', ', array_map('intval', $post_id_array));

	if ($post_id_array == '')
	{
		return $attachments;
	}

	$display_order = (intval($attach_config['display_order']) == 0) ? 'DESC' : 'ASC';

	$sql = 'SELECT a.post_id, d.*
		FROM ' . ATTACHMENTS_TABLE . ' a, ' . ATTACHMENTS_DESC_TABLE . " d
		WHERE a.post_id IN ($post_id_array)
			AND a.attach_id = d.attach_id
		ORDER BY d.filetime $display_order";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get Attachment Informations for post number ' . $post_id_array, '', __LINE__, __FILE__, $sql);
	}

	$num_rows = $db->sql_numrows($result);
	$attachments = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);

	if ($num_rows == 0)
	{
		return array();
	}

	return $attachments;
}

/**
* Update attachments stats
*/
function update_attachments_stats($attach_id)
{
	global $db, $userdata, $user_ip, $user_agent, $lang;

	$sql = 'UPDATE ' . ATTACHMENTS_DESC_TABLE . '
	SET download_count = download_count + 1
	WHERE attach_id = ' . (int) $attach_id;
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Couldn\'t update attachment download count', $lang['Error'], __LINE__, __FILE__, $sql);
	}

	if (($userdata['bot_id'] != false) && defined('USE_ATTACHMENTS_STATS') && (USE_ATTACHMENTS_STATS == true))
	{
		$sql = "INSERT INTO " . ATTACHMENTS_STATS_TABLE . " (`attach_id`, `user_id`, `user_ip`, `user_http_agents`, `download_time`)
			VALUES ('" . $attach_id . "', '" . $userdata['user_id'] . "', '" . $user_ip . "', '" . addslashes($user_agent) . "', '" . time() . "')";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not insert data into attachments stats table', $lang['Error'], __LINE__, __FILE__, $sql);
		}
	}

	return true;
}

?>