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
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

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