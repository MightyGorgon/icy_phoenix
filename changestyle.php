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


if (isset($_GET[STYLE_URL]))
{
	$style = intval($_GET[STYLE_URL]);

	if(strpos($url, "?" . STYLE_URL . '=') != false || strpos($url, "&" . STYLE_URL . '=') != false)
	{
		// replace STYLE_URL parameter
		$url = ereg_replace("([\?&])" . STYLE_URL . "=[^&]*", "\\1" . STYLE_URL . "=" . $style, $url);
	}
	else
	{
		// add STYLE_URL parameter
		$url .= ((strpos($url, '?') != false) ? '&' : '?') . STYLE_URL . "=" . $style;
	}
}

header("Location: " . $url);

?>