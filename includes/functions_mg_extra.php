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

// Force Word Wrapping (by TerraFrost)
/*
if(!function_exists(kb_word_wrap_pass))
{
	function kb_word_wrap_pass($message)
	{
		$tempText = "";
		$finalText = "";
		$curCount = $tempCount = 0;
		$longestAmp = 9;
		$inTag = false;
		$ampText = "";

		for ($num = 0;$num < strlen($message);$num++)
		{
			$curChar = $message{$num};

			if ($curChar == "<")
			{
				for ($snum = 0;$snum < strlen($ampText);$snum++)
				{
					kb_addWrap($ampText{$snum}, $ampText{$snum+1}, $finalText, $tempText, $curCount, $tempCount);
				}
				$ampText = "";
				$tempText .= "<";
				$inTag = true;
			}
			elseif ($inTag && $curChar == ">")
			{
				$tempText .= ">";
				$inTag = false;
			}
			elseif ($inTag)
			{
				$tempText .= $curChar;
			}
			elseif ($curChar == "&")
			{
				for ($snum = 0;$snum < strlen($ampText);$snum++)
				{
					kb_addWrap($ampText{$snum}, $ampText{$snum+1}, $finalText, $tempText, $curCount, $tempCount);
				}
				$ampText = "&";
			}
			elseif (strlen($ampText) < $longestAmp && $curChar == ";" &&
					(strlen(html_entity_decode("$ampText;")) == 1 || preg_match('/^&#[0-9][0-9]*$/', $ampText)))
			{
				kb_addWrap("$ampText;", $message{$num+1}, $finalText, $tempText, $curCount, $tempCount);
				$ampText = "";
			}
			elseif (strlen($ampText) >= $longestAmp || $curChar == ";")
			{
				for ($snum = 0;$snum < strlen($ampText);$snum++)
				{
					kb_addWrap($ampText{$snum}, $ampText{$snum+1}, $finalText, $tempText, $curCount, $tempCount);
				}
				kb_addWrap($curChar, $message{$num+1}, $finalText, $tempText, $curCount, $tempCount);
				$ampText = "";
			}
			elseif (strlen($ampText) != 0 && strlen($ampText) < $longestAmp)
			{
				$ampText .= $curChar;
			}
			else
			{
				kb_addWrap($curChar, $message{$num+1}, $finalText, $tempText, $curCount, $tempCount);
			}
		}

		return $finalText . $tempText;
	}
}

if(!function_exists(kb_addWrap))
{
	function kb_addWrap($curChar, $nextChar, &$finalText, &$tempText, &$curCount, &$tempCount)
	{
		$softHyph = (!preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) ? '&#8203;': '&shy;';
		$maxChars = 10;
		$wrapProhibitedChars = "([{!;,:?}])";

		if ($curChar == " " || $curChar == "\n")
		{
			$finalText .= $tempText . $curChar;
			$tempText = "";
			$curCount = 0;
			$curChar = "";
		}
		elseif ($curCount >= $maxChars)
		{
			$finalText .= $tempText . $softHyph;
			$tempText = "";
			$curCount = 1;
		}
		else
		{
			$tempText .= $curChar;
			$curCount++;
		}
		// the following code takes care of (unicode) characters prohibiting non-mandatory breaks directly before them.
		// $curChar isn't a " " or "\n"
		if ($tempText != "" && $curChar != "")
		{
			$tempCount++;
		}
		// $curChar is " " or "\n", but $nextChar prohibits wrapping.
		elseif (($curCount == 1 && strstr($wrapProhibitedChars, $curChar) !== false) ||
				($curCount == 0 && $nextChar != "" && $nextChar != " " && $nextChar != "\n" && strstr($wrapProhibitedChars, $nextChar) !== false))
		{
			$tempCount++;
		}
		// $curChar and $nextChar aren't both either " " or "\n"
		elseif (!($curCount == 0 && ($nextChar == " " || $nextChar == "\n")))
		{
			$tempCount = 0;
		}

		if ($tempCount >= $maxChars && $tempText == "")
		{
			$finalText .= "&nbsp;";
			$tempCount = 1;
			$curCount = 2;
		}

		if ($tempText == "" && $curCount > 0)
		{
			$finalText .= $curChar;
		}
	}
}
*/

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
	if (count($data_array) > 31) return FALSE;
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