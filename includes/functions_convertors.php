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
* Replace values in DB: useful for UTF8 Conversion
* Example: UPDATE ip_cms_blocks SET content = REPLACE(content, '\"', '&quot;');
*/
function sql_replace($table, $fields, $html_encode = true, $stripslashes = false)
{
	global $db, $cache;

	if (empty($fields) || empty($table))
	{
		return false;
	}

	if (!is_array($fields))
	{
		$fields = array($fields);
	}

	$sql_replace_array = array();

	if ($html_encode)
	{
		foreach ($fields as $field)
		{
			/*
			UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, '\\"', '\"');
			UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, "\\'", "\'");

			UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, '&', '&amp;');
			UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, '&&amp;', '&amp;');
			UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, '\"', '&quot;');
			UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, '<', '&lt;');
			UPDATE `phpbb_cms_block_settings` SET `content` = REPLACE(`content`, '>', '&gt;');
			*/
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '&', '&amp;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '&&amp;', '&amp;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '\"', '&quot;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '<', '&lt;')";
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", '>', '&gt;')";
		}
	}

	if ($stripslashes)
	{
		foreach ($fields as $field)
		{
			$sql_replace_array[] = "UPDATE " . $table . " SET " . $field . " = REPLACE(" . $field . ", \"\'\", \"'\")";
		}
	}

	for ($i = 0; $i < sizeof($sql_replace_array); $i++)
	{
		$db->sql_return_on_error(true);
		$result = $db->sql_query($sql_replace_array[$i]);
		$db->sql_return_on_error(false);
	}

	return true;
}

/**
* Convert nulls to zeros for fields which allowed a NULL value in the source but not the destination
*/
function null_to_zero($value)
{
	return ($value === NULL) ? 0 : $value;
}

/**
* Convert nulls to empty strings for fields which allowed a NULL value in the source but not the destination
*/
function null_to_str($value)
{
	return ($value === NULL) ? '' : $value;
}

/**
* Convert an IP address from the hexadecimal notation to normal dotted-quad notation
*/
function decode_ip($int_ip)
{
	if (!$int_ip)
	{
		return $int_ip;
	}

	$hexipbang = explode('.', chunk_split($int_ip, 2, '.'));

	// Any mod changing the way ips are stored? Then we are not able to convert and enter the ip "as is" to not "destroy" anything...
	if (sizeof($hexipbang) < 4)
	{
		return $int_ip;
	}

	return hexdec($hexipbang[0]) . '.' . hexdec($hexipbang[1]) . '.' . hexdec($hexipbang[2]) . '.' . hexdec($hexipbang[3]);
}

/**
* Reverse the encoding of wild-carded bans
*/
function decode_ban_ip($int_ip)
{
	return str_replace('255', '*', decode_ip($int_ip));
}

?>