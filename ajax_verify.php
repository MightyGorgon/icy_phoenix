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
include($phpbb_root_path . 'includes/functions_validate.' . $phpEx);

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
$verify = isset($_GET['verify']) ? $_GET['verify'] : '';

if ( $mode == 'username' )
{
	$result_validation = validate_username($verify);
	if ( $result_validation['error'] )
	{
		echo '1';
	}
	else
	{
		echo '2';
	}
}
elseif ( $mode == 'password' )
{
	$result_validation = validate_password($verify);
	if ( $result_validation['error'] )
	{
		echo '1';
	}
	else
	{
		echo '2';
	}
}
elseif ( $mode == 'email' )
{
	$result_validation = validate_email($verify);
	if ( $result_validation['error'] )
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