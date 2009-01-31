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

// ******************************************************************************************
// Define your own modes here

switch ($mode)
{
	case 'user_name':
		$mode_des = 'Username';
		$sql = "FROM " . USERS_TABLE . "
						WHERE username = '" . str_replace("'", "\'", $del_user) . "'";
		break;

	case 'user_id':
		$mode_des = 'User ID';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id = '" . $del_user . "'";
		break;

	case 'prune_mg':
		$mode_des = 'MG Selection!!!';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_level = " . REG . "
							AND user_posts = '0'
							AND user_lastvisit < '" . (time() - 86400 * $days) . "'";
		break;

	case 'prune_0':
		$mode_des = 'Zero posters';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_posts = '0'
							AND user_regdate < '" . (time() - (86400 * $days)) . "'";
		break;

	case 'prune_1':
		$mode_des = 'Not logged in';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_lastvisit = '0'
							AND user_regdate < '" . (time() - (86400 * $days)) . "'";
		break;

	case 'prune_2':
		$mode_des = 'Inactive';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_lastvisit = '0'
							AND user_active = '0'
							AND user_actkey <> ''
							AND user_regdate < '" . (time() - (86400 * $days)) . "'";
		break;

	case 'prune_3':
		$mode_des = 'Long time no visit';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_lastvisit < '" . (time() - (86400 * 60)) . "'
							AND user_regdate < '" . (time() - (86400 * $days)) . "'";
		break;

	case 'prune_4':
		$mode_des = 'Average posts';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_posts / ((user_lastvisit - user_regdate) / 86400) < '0.1'
							AND user_regdate < '" . (time() - (86400 * $days)) . "'";
		break;

	case 'prune_5':
		$mode_des = 'Long time no visit zero posters';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_posts = '0'
							AND user_lastlogon < '" . (time() - (86400 * 180)) . "'
							AND user_regdate < '" . (time() - (86400 * $days)) . "'";
		break;

	case 'prune_6':
		$mode_des = 'Custom time visit zero posters';
		$sql = "FROM " . USERS_TABLE . "
						WHERE user_id <> '" . ANONYMOUS . "'
							AND user_posts = '0'
							AND user_lastlogon < '" . (time() - (86400 * $days)) . "'
							AND user_regdate < '" . (time() - (86400 * $days)) . "'";
		break;

	default:
		message_die(GENERAL_ERROR, 'No mode specified', '', __LINE__, __FILE__);
}

if ($mode == 'prune_mg')
{
	$sql_full = "SELECT user_id , username, user_email, user_lang
							" . $sql . "
							ORDER BY username
							LIMIT 0, " . $users_number;
}
else
{
	$sql_full = "SELECT user_id , username, user_email, user_lang
							" . $sql . "
							ORDER BY username
							LIMIT 800";
}

if(!$result = $db->sql_query($sql_full))
{
	message_die(GENERAL_ERROR, 'Error obtaining userdata', '', __LINE__, __FILE__, $sql);
}
$users_list = $db->sql_fetchrowset($result);

$i = 0;
$name_list = '';

$server_url = create_server_url();
$profile_server_url = $server_url . PROFILE_MG . '?mode=register';

while (isset($users_list[$i]['user_id']))
{
	$user_id = $users_list[$i]['user_id'];
	$username = str_replace("'", "\'", $users_list[$i]['username']);
	$user_email = $users_list[$i]['user_email'];
	$user_lang =  $users_list[$i]['user_lang'];

	$killed = ip_user_kill($user_id);

	if (NOTIFY_USERS && !empty($user_email))
	{
		$emailer = new emailer($board_config['smtp_delivery']);

		$email_headers = 'X-AntiAbuse: Board servername - ' . trim($board_config['server_name']) . "\n";
		$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
		$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
		$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

		$emailer->use_template('delete_users', (file_exists(IP_ROOT_PATH . 'language/lang_' . $user_lang . '/email/delete_users.tpl')) ? $user_lang : 'english');
		$emailer->email_address($user_email);
		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);
		$emailer->extra_headers($email_headers);

		$emailer->assign_vars(array(
			'U_REGISTER' => $profile_server_url,
			'USER' => $userdata['username'],
			'USERNAME' => $username,
			'SITENAME' => $board_config['sitename'],
			'BOARD_EMAIL' => $board_config['board_email']
			)
		);
		$emailer->send();
		$emailer->reset();
	}

	$name_list .= (!empty($name_list) ? ', ' : '<br />') . $username;
	$i++;
}

?>