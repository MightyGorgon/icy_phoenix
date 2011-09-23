<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// We need to force some vars...
$config['thumbnail_highslide'] = false;
$config['ajax_features'] = false;

// Start session management
$user->session_begin(false);
$auth->acl($user->data);
$user->setup();
// End session management

// Errors Configuration Flags

// N = no email / Y = email
$email = array (
	'000' => 'N',
	'400' => 'N',
	'401' => 'N',
	'403' => 'N',
	'404' => 'N',
	'500' => 'N'
);

// N = no log file / Y = log file
$log = array (
	'000' => 'Y',
	'400' => 'Y',
	'401' => 'Y',
	'403' => 'Y',
	'404' => 'Y',
	'500' => 'Y'
);

// Errors description


$errors_english = array (
	'ERRORS_000' => 'Unknown Error',
	'ERRORS_400' => 'Error 400',
	'ERRORS_401' => 'Not Authorized',
	'ERRORS_403' => 'Errore 403',
	'ERRORS_404' => 'File not found',
	'ERRORS_500' => 'Configuration Error'
);

$subject = array (
	'000' => !empty($lang['ERRORS_000']) ? $lang['ERRORS_000'] : $errors_english['ERRORS_000'],
	'400' => !empty($lang['ERRORS_400']) ? $lang['ERRORS_400'] : $errors_english['ERRORS_400'],
	'401' => !empty($lang['ERRORS_401']) ? $lang['ERRORS_401'] : $errors_english['ERRORS_401'],
	'403' => !empty($lang['ERRORS_403']) ? $lang['ERRORS_403'] : $errors_english['ERRORS_403'],
	'404' => !empty($lang['ERRORS_404']) ? $lang['ERRORS_404'] : $errors_english['ERRORS_404'],
	'500' => !empty($lang['ERRORS_500']) ? $lang['ERRORS_500'] : $errors_english['ERRORS_500']
);


//$result = $QUERY_STRING;
$result = request_var('code', 0);

switch($result)
{
	case 400:
		$error_msg = !empty($lang['ERRORS_400_FULL']) ? $lang['ERRORS_400_FULL'] : $errors_english['ERRORS_400_FULL'];
		break;
	case 401:
		$error_msg = !empty($lang['ERRORS_401_FULL']) ? $lang['ERRORS_401_FULL'] : $errors_english['ERRORS_401_FULL'];
		break;
	case 403:
		$error_msg = !empty($lang['ERRORS_403_FULL']) ? $lang['ERRORS_403_FULL'] : $errors_english['ERRORS_403_FULL'];
		break;
	case 404:
		$error_msg = !empty($lang['ERRORS_404_FULL']) ? $lang['ERRORS_404_FULL'] : $errors_english['ERRORS_404_FULL'];
		break;
	case 500:
		$error_msg = !empty($lang['ERRORS_500_FULL']) ? $lang['ERRORS_500_FULL'] : $errors_english['ERRORS_500_FULL'];
		break;
	default:
		$result = '000';
		$error_msg = !empty($lang['ERRORS_000_FULL']) ? $lang['ERRORS_000_FULL'] : $errors_english['ERRORS_000_FULL'];
}

// Error notification details
$server_url = create_server_url();

$notification_email = $config['board_email'];
$sitename = $config['sitename'];
$datecode = gmdate('Ymd');
$logs_path = !empty($config['logs_path']) ? $config['logs_path'] : 'logs';
$errors_log = $logs_path . '/errors_' . $datecode . '.txt';
//$errors_log = 'logs/errors.txt';

if (($config['write_errors_log'] == true) && ($log[$result] == 'Y'))
{
	errors_notification('L', $result, $sitename, $subject, $errors_log, $notification_email);
}

if ($email[$result] == 'Y')
{
	errors_notification('M', $result, $sitename, $subject, $errors_log, $notification_email);
}

// Start output of page
$template->assign_vars(array(
	'ERROR_MESSAGE' => $error_msg
	)
);

send_status_line($result, $error_msg);
full_page_generation('errors_body.tpl', $lang['Error'], '', '');

function errors_notification($action, $result, $sitename, $subject, $errors_log, $notification_email)
{
	global $REQUEST_URI, $REMOTE_ADDR, $HTTP_USER_AGENT, $REDIRECT_ERROR_NOTES, $SERVER_NAME, $HTTP_REFERER;
	global $lang;

	$remote_address = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
	$remote_address = (!empty($remote_address) && ($remote_address != '::1')) ? $remote_address : '127.0.0.1';
	$user_agent_errors = (!empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : (!empty($_ENV['HTTP_USER_AGENT']) ? trim($_ENV['HTTP_USER_AGENT']) : trim(getenv('HTTP_USER_AGENT'))));
	$referer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : getenv('HTTP_REFERER');
	//$referer = ($referer == '') ? $HTTP_REFERER : $HTTP_REFERER;
	$referer = ($referer == '') ? $HTTP_REFERER : $referer;
	$referer = preg_replace('/sid=[A-Za-z0-9]{32}/', '', $referer);
	$script_name = (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$server_name = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME');

	$date = gmdate('Y/m/d - H:i:s');

	if ( ($action == 'L') || ($action == 'LM') )
	{
		$message = '[' . $date . ']';
		$message .= ' [URL: ' . $script_name . ' ]';
		$message .= ' [REF: ' . $referer . ' ]';
		$message .= ' [IP: ' . $remote_address . ']';
		$message .= ' [Client: ' . $user_agent_errors . ']';
		$message .= "\n";
		$fp = fopen ($errors_log, "a+");
		fwrite($fp, $message);
		fclose($fp);
	}

	if ( ($action == 'M') || ($action == 'LM') )
	{
		$message_full = "
		==============================================================================
		------------------------------------------------------------------------------
		==============================================================================
		Site:       $sitename ($server_name)
		Error Code: $result $subject[$result]
		Date:       $date
		URL:        $referer
		IP Address: $remote_address
		Browser:    $user_agent_errors
		==============================================================================
		------------------------------------------------------------------------------
		==============================================================================
		";
		$message = $lang['ERRORS_EMAIL_BODY'];
		$subject_prefix = $lang['ERRORS_EMAIL_SUBJECT'];
		$email_from_prefix = $lang['ERRORS_EMAIL_ADDRRESS_PREFIX'];
		mail($notification_email, '[ ' . $subject_prefix . $subject[$result] . ' ]', $message, 'From: ' . $email_from_prefix . @$server_name . "\r\n" . 'X-Mailer: PHP/' . phpversion());
	}
}

?>