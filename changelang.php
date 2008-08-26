<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

$url = FORUM_MG; // fallback, if HTTP_REFERER is not set

if (isset($_SERVER['HTTP_REFERER']))
{
	$url = $_SERVER['HTTP_REFERER'];
}


if (isset($_GET[LANG_URL]))
{
	$language = urldecode($_GET[LANG_URL]);
	$language = strtr($language, array_flip(get_html_translation_table(HTML_ENTITIES)));
	$language = htmlspecialchars($language);
	/*
	$look_up_array = array("<", ">", "\n", chr(13));
	$replacement_array = array("", "", "", "");
	$language = str_replace($look_up_array, $replacement_array, $language);
	*/

	if(strpos($url, "?" . LANG_URL . '=') != false || strpos($url, "&" . LANG_URL . '=') != false)
	{
		// replace LANG_URL parameter
		$url = ereg_replace("([\?&])" . LANG_URL . "=[^&]*", "\\1" . LANG_URL . "=" . $language, $url);
	}
	else
	{
		// add LANG_URL parameter
		$url .= ((strpos($url, '?') != false) ? '&' : '?') . LANG_URL . "=" . $language;
	}
}

header("Location: " . $url);

?>