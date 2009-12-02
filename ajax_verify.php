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
include(IP_ROOT_PATH . 'includes/functions_validate.' . PHP_EXT);

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$verify = isset($_GET['verify']) ? $_GET['verify'] : '';

if ($mode == 'username')
{
	$result_validation = validate_username($verify);
	if ($result_validation['error'])
	{
		echo '1';
	}
	else
	{
		echo '2';
	}
}
elseif ($mode == 'password')
{
	$result_validation = validate_password($verify);
	if ($result_validation['error'])
	{
		echo '1';
	}
	else
	{
		echo '2';
	}
}
elseif ($mode == 'email')
{
	$result_validation = validate_email($verify);
	if ($result_validation['error'])
	{
		echo '1';
	}
	else
	{
		echo '2';
	}
}
else
{
	echo '2';
}

?>