<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File checked by human
define('IN_PHPBB', true);
define('MG_KILL_CTRACK', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
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

/*
$subject = array (
	'000' => 'Unknown Error',
	'400' => 'Error 400',
	'401' => 'Not Authorized',
	'403' => 'Errore 403',
	'404' => 'File not found',
	'500' => 'Configuration Error'
);
*/
$subject = array (
	'000' => $lang['Errors_000'],
	'400' => $lang['Errors_400'],
	'401' => $lang['Errors_401'],
	'403' => $lang['Errors_403'],
	'404' => $lang['Errors_404'],
	'500' => $lang['Errors_500']
);


//$result = $QUERY_STRING;
if (isset($_GET['code']))
{
	$result = intval($_GET['code']);
}

switch($result)
{
	case 400:
		$error_msg = $lang['Errors_400_Full'];
		break;
	case 401:
		$error_msg = $lang['Errors_401_Full'];
		break;
	case 403:
		$error_msg = $lang['Errors_403_Full'];
		break;
	case 404:
		$error_msg = $lang['Errors_404_Full'];
		break;
	case 500:
		$error_msg = $lang['Errors_500_Full'];
		break;
	default:
		$result = '000';
		$error_msg = $lang['Errors_000_Full'];
}

// Error notification details
$server_url = create_server_url();

$notification_email = $board_config['board_email'];
$sitename = $board_config['sitename'];
$datecode = date('Ymd');
$logs_path = !empty($board_config['logs_path']) ? $board_config['logs_path'] : 'logs';
$errors_log = $logs_path . '/errors_' . $datecode . '.txt';
//$errors_log = 'logs/errors.txt';

if (($board_config['write_errors_log'] == true) && ($log[$result] == 'Y'))
{
	errors_notification('L', $result, $sitename, $subject, $errors_log, $notification_email);
}

if ($email[$result] == 'Y')
{
	errors_notification('M', $result, $sitename, $subject, $errors_log, $notification_email);
}

// Start output of page
$page_title = $lang['Portal'];
$meta_description = '';
$meta_keywords = '';
$board_config['thumbnail_lightbox'] = false;
$board_config['ajax_features'] = false;
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

$template->set_filenames(array('body' => 'errors_body.tpl'));

$template->assign_vars(array(
	'ERROR_MESSAGE' => $error_msg
	)
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);


function errors_notification($action, $result, $sitename, $subject, $errors_log, $notification_email)
{
	global $REQUEST_URI, $REMOTE_ADDR, $HTTP_USER_AGENT, $REDIRECT_ERROR_NOTES, $SERVER_NAME, $HTTP_REFERER;
	global $lang;

	$remote_address = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
	$user_agent = (!empty($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : (!empty($_ENV['HTTP_USER_AGENT']) ? trim($_ENV['HTTP_USER_AGENT']) : trim(getenv('HTTP_USER_AGENT'))));
	$referer = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : getenv('HTTP_REFERER');
	//$referer = ($referer == '') ? $HTTP_REFERER : $HTTP_REFERER;
	$referer = ($referer == '') ? $HTTP_REFERER : $referer;
	$referer = preg_replace('/sid=[A-Za-z0-9]{32}/', '', $referer);
	$script_name = (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
	$server_name = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME');

	$date = date('Y/m/d - H:i:s');

	if ( ($action == 'L') || ($action == 'LM') )
	{
		$message = '[' . $date . ']';
		$message .= ' [URL: ' . $script_name . ' ]';
		$message .= ' [REF: ' . $referer . ' ]';
		$message .= ' [IP: ' . $remote_address . ']';
		$message .= ' [Client: ' . $user_agent . ']';
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
		Browser:    $user_agent
		==============================================================================
		------------------------------------------------------------------------------
		==============================================================================
		";
		$message = $lang['Errors_Email_Body'];
		$subject_prefix = $lang['Errors_Email_Subject'];
		$email_from_prefix = $lang['Errors_Email_Addrress_Prefix'];
		mail($notification_email, '[ ' . $subject_prefix . $subject[$result] . ' ]', $message, 'From: ' . $email_from_prefix . @$server_name . "\r\n" . 'X-Mailer: PHP/' . phpversion());
	}
}

?>