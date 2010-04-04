<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function mg_text_cleaning($text)
{
	$look_up_array = array(
		"&agrave;",
		"&egrave;",
		"&igrave;",
		"&ograve;",
		"&ugrave;",
		"&eacute;",
		"&nbsp;",
	);

	$replacement_array = array(
		"à",
		"è",
		"ì",
		"ò",
		"ù",
		"é",
		" ",
	);

	$text = str_replace($look_up_array, $replacement_array, $text);

	return $text;
}

function mg_text_format($text)
{
	$look_up_array = array(
		"à",
		"è",
		"ì",
		"ò",
		"ù",
		"é",
		" ",
	);

	$replacement_array = array(
		"&agrave;",
		"&egrave;",
		"&igrave;",
		"&ograve;",
		"&ugrave;",
		"&eacute;",
		"&nbsp;",
	);

	$text = str_replace($look_up_array, $replacement_array, $text);

	return $text;
}

/**
* Will convert an array of binary values into an integer for storage
*
* @param        array      $data_array    Array of 31 or less binary values
* @return      integer                    Encoded integer
*/
function array_to_binary_int($data_array)
{
	if (sizeof($data_array) > 31) return FALSE;
	foreach ($data_array as $key => $value)
	{
		if ($value) $data_array[$key] = 1;
		if (!$value) $data_array[$key] = 0;
	}
	$binstring = strrev(implode('', $data_array));
	$bit_integer = bindec($binstring);
	return $bit_integer;
}

/**
* Will convert a stored integer into an array of binary values
*
* @param        integer      $data_integer    Encoded integer
* @return      integer                        Array of binary values
*/
function binary_int_to_array($data_integer)
{
	if (($data_integer > 2147483647) || ($data_integer < 0))
	{
		return false;
	}
	$binstring = strrev(str_pad(decbin($data_integer), 31, "0", STR_PAD_LEFT));
	$bitarray = explode(":", chunk_split($binstring, 1, ":"));
	return $bitarray;
}

?>