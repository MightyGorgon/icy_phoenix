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

/*
* addslashes to vars if magic_quotes_gpc is off this is a security precaution to prevent someone trying to break out of a SQL statement.
*/
function globals_addslashes()
{
	if(!STRIP)
	{
		if(is_array($_GET))
		{
			//while(list($k, $v) = each($_GET))
			foreach ($_GET as $k => $v)
			{
				if(is_array($_GET[$k]))
				{
					//while(list($k2, $v2) = each($_GET[$k]))
					foreach ($_GET[$k] as $k2 => $v2)
					{
						$_GET[$k][$k2] = addslashes($v2);
					}
					@reset($_GET[$k]);
				}
				else
				{
					$_GET[$k] = addslashes($v);
				}
			}
			@reset($_GET);
		}

		if(is_array($_POST))
		{
			//while(list($k, $v) = each($_POST))
			foreach ($_POST as $k => $v)
			{
				if(is_array($_POST[$k]))
				{
					//while(list($k2, $v2) = each($_POST[$k]))
					foreach ($_POST[$k] as $k2 => $v2)
					{
						$_POST[$k][$k2] = addslashes($v2);
					}
					@reset($_POST[$k]);
				}
				else
				{
					$_POST[$k] = addslashes($v);
				}
			}
			@reset($_POST);
		}

		if(is_array($_COOKIE))
		{
			//while(list($k, $v) = each($_COOKIE))
			foreach ($_COOKIE as $k => $v)
			{
				if(is_array($_COOKIE[$k]))
				{
					//while(list($k2, $v2) = each($_COOKIE[$k]))
					foreach ($_COOKIE[$k] as $k2 => $v2)
					{
						$_COOKIE[$k][$k2] = addslashes($v2);
					}
					@reset($_COOKIE[$k]);
				}
				else
				{
					$_COOKIE[$k] = addslashes($v);
				}
			}
			@reset($_COOKIE);
		}
	}
}

?>