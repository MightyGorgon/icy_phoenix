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
* Bicet (bicets@gmail.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$ref_black_list = array(
	'.wst.st',
	'.neyon.com',
	'.byethost',
	'cialis.w48.54.ru',
	'.volja.net',
	'.siol.net',
	'.alice.it',
	'xoomer.alice.it',
	'.skynet.be',
	'users.skynet.be',
);

if (!function_exists('arrayinstr'))
{
	function arrayinstr($haystack, $needle)
	{
		$foundit = false;
		foreach($needle as $value)
		{
			if (!strpos($haystack, $value) === false)
			{
				$foundit = true;
				return $foundit;
			}
		}
		return $foundit;
	}
}

if ($_SERVER['HTTP_REFERER'] && !eregi($_SERVER['HTTP_HOST'] . $config['script_path'], $_SERVER['HTTP_REFERER']))
{
	$referrer_url = (!empty($_SERVER['HTTP_REFERER'])) ? (string) $_SERVER['HTTP_REFERER'] : '';
	$referrer_host = $referrer_url;
	$referrer_host = str_replace ('http://', '', $referrer_host);
	$referrer_host = substr($referrer_host, 0, strpos($referrer_host, "/"));
	if (arrayinstr($referrer_host, $ref_black_list) === false)
	{
		if (!($referrer_host == $config['server_name']))
		{
			$sql = "SELECT * FROM " . REFERRERS_TABLE . "
				WHERE referrer_url = '$referrer_url'";
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			if (!$row)
			{
				$sql = "INSERT INTO " . REFERRERS_TABLE . " (referrer_host, referrer_url, referrer_ip, referrer_hits, referrer_firstvisit, referrer_lastvisit)
					VALUES ('" . $db->sql_escape($referrer_host) . "', '" . $db->sql_escape($referrer_url) . "', '$user_ip', 1, '" . time() . "', '" . time() . "')";
				$result = $db->sql_query($sql);
			}
			else
			{
				$sql = "UPDATE " . REFERRERS_TABLE . "
					SET referrer_hits = referrer_hits + 1, referrer_lastvisit = " . time() . ", referrer_ip = '" . $user_ip . "'
					WHERE referrer_url = '" . $db->sql_escape($referrer_url) . "'";
				$result = $db->sql_query($sql);
			}
		}
	}
}
?>