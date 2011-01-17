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
* Lopalong
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'BIRTHDAY_GREETING_EMAIL_SUBJECT' => 'Happy Birthday From %s', //%s is substituted with site title
	'BIRTHDAY_GREETING_EMAIL_CONTENT' => 'We would like to wish you congratulations on reaching %s years old today.', //%s is substituted with the users age
	)
);

?>