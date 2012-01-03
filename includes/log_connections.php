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
Add in page_header function
// Mighty Gorgon - LOG Connections - BEGIN
@include('includes/log_connections.' . PHP_EXT);
// Mighty Gorgon - LOG Connections - END
*/

$mgl_server_url = create_server_url();
$mgl_notification_email = $config['board_email'];
$mgl_sitename = $config['sitename'];
$mgl_datecode = gmdate('Ymd');
$mgl_logs_path = !empty($config['logs_path']) ? $config['logs_path'] : 'logs';
$mgl_log_file = $mgl_logs_path . '/connections_' . $mgl_datecode . '.csv';
$mgl_subject = 'Connections Log ' . $mgl_datecode;

mgl_write_log('L', $mgl_sitename, $mgl_subject, $mgl_log_file, $mgl_notification_email);
//mgl_write_log('M', $mgl_sitename, $mgl_subject, $mgl_log_file, $mgl_notification_email);

function mgl_write_log($action, $sitename, $subject, $log_file, $notification_email)
{
	global $user;

	$remote_address = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
	$remote_address = (!empty($remote_address) && ($remote_address != '::1')) ? $remote_address : '127.0.0.1';
	$user_agent_log = (!empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : (!empty($_ENV['HTTP_USER_AGENT']) ? trim($_ENV['HTTP_USER_AGENT']) : trim(getenv('HTTP_USER_AGENT'))));
	$referer = (!empty($_SERVER['HTTP_REFERER'])) ? (string) $_SERVER['HTTP_REFERER'] : '';
	$referer = preg_replace('/[?&]{1}sid=[A-Za-z0-9]{32}/', '', $referer);
	$script_name = (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$script_name = preg_replace('/[?&]{1}sid=[A-Za-z0-9]{32}/', '', $script_name);
	$server_name = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME');

	$date = gmdate('Y/m/d - H:i:s');

	if (($action == 'L') || ($action == 'LM'))
	{
		/*
		$message = '[' . $date . ']';
		$message .= '[USER: ' . $user->data['username'] . ' - (ID: ' . $user->data['user_id'] . ')]';
		$message .= ' [URL: ' . $script_name . ' ]';
		$message .= ' [REF: ' . $referer . ' ]';
		$message .= ' [IP: ' . $remote_address . ']';
		$message .= ' [Client: ' . $user_agent_log . ']';
		$message .= "\n";
		*/
		$message = '"' . $date . '";';
		$message .= '"' . $user->data['username'] . '";';
		$message .= '"' . $user->data['user_id'] . '";';
		$message .= '"' . str_replace('"', '\'', $script_name) . '";';
		$message .= '"' . str_replace('"', '\'', $referer) . '";';
		$message .= '"' . $remote_address . '";';
		$message .= '"' . str_replace('"', '\'', $user_agent_log) . '";';
		$message .= "\n";
		$fp = fopen ($log_file, "a+");
		fwrite($fp, $message);
		fclose($fp);
	}

	if (($action == 'M') || ($action == 'LM'))
	{
		$message_full = "
		==============================================================================
		------------------------------------------------------------------------------
		==============================================================================
		Site:       $sitename ($server_name)
		Date:       $date
		==============================================================================
		------------------------------------------------------------------------------
		==============================================================================
		";
		$message = 'Connections Logs';
		mail($notification_email, $subject, $message, 'From: ' . $server_name . "\r\n" . 'X-Mailer: PHP/' . phpversion());
	}
}

?>