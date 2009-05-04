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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

define('IN_ICYPHOENIX', true);

// Load default header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
$no_page_header = true;
require('./pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_mg_online.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_mg_log_admin.' . PHP_EXT);

// ---------------
// Begin functions
//
function inarray($needle, $haystack)
{
	for($i = 0; $i < sizeof($haystack); $i++)
	{
		if($haystack[$i] == $needle)
		{
			return true;
		}
	}
	return false;
}
//
// End functions
// -------------

include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_pafiledb.' . PHP_EXT);

// Generate relevant output
if(isset($_GET['pane']) && ($_GET['pane'] == 'left'))
{
	//Needed to avoid emptying cache when generating ACP Modules... do not remove or change, unless you also change it in common.php
	define('ACP_MODULES', true);

	$jr_admin_userdata = jr_admin_get_user_info($userdata['user_id']);
	$module = jr_admin_get_module_list($jr_admin_userdata['user_jr_admin']);

	include('./page_header_admin.' . PHP_EXT);

	$template->set_filenames(array('body' => ADM_TPL . 'index_navigate.tpl'));

	$template->assign_vars(array(
		'U_FORUM_INDEX' => append_sid(IP_ROOT_PATH . FORUM_MG),
		'U_PORTAL' => append_sid(IP_ROOT_PATH . PORTAL_MG),
		'U_ADMIN_INDEX' => append_sid('index.' . PHP_EXT . '?pane=right'),

		//+MOD: DHTML Menu for ACP
		'COOKIE_NAME' => $board_config['cookie_name'],
		'COOKIE_PATH' => $board_config['cookie_path'],
		'COOKIE_DOMAIN' => $board_config['cookie_domain'],
		'COOKIE_SECURE' => $board_config['cookie_secure'],
		//-MOD: DHTML Menu for ACP

		'L_FORUM_INDEX' => $lang['Main_index'],
		'L_ADMIN_INDEX' => $lang['Admin_Index'],
		'L_PREVIEW_FORUM' => $lang['Preview_forum'],
		'L_PORTAL' => $lang['Portal'],
		'L_PREVIEW_PORTAL' => $lang['Preview_Portal']
		)
	);

	jr_admin_make_left_pane();

	$template->pparse('body');

	include('./page_footer_admin.' . PHP_EXT);
}
elseif(isset($_GET['pane']) && ($_GET['pane'] == 'right'))
{

	include('./page_header_admin.' . PHP_EXT);

	$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
	$is_allowed = ($userdata['user_id'] == $founder_id) ? true : false;

	$template->set_filenames(array('body' => ADM_TPL . 'index_body.tpl'));

	$template->assign_vars(array(
		'S_IS_FOUNDER' => $is_allowed,
		'U_ADMIN_LOGS' => append_sid('admin_logs.' . PHP_EXT),
		'L_WELCOME' => $lang['Welcome_phpBB'],
		'L_ADMIN_INTRO' => $lang['Admin_intro'],
		'L_PAYPAL_INFO' => $lang['PayPalInfo'],
		'L_SITE_STATS' => $lang['Forum_stats'],
		'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
		'L_USERNAME' => $lang['Username'],
		'L_LOCATION' => $lang['Location'],
		'L_LAST_UPDATE' => $lang['Last_updated'],
		'L_IP_ADDRESS' => $lang['IP_Address'],
		'L_STATISTIC' => $lang['Statistic'],
		'L_VALUE' => $lang['Value'],
		'L_NUMBER_POSTS' => $lang['Number_posts'],
		'L_POSTS_PER_DAY' => $lang['Posts_per_day'],
		'L_NUMBER_TOPICS' => $lang['Number_topics'],
		'L_TOPICS_PER_DAY' => $lang['Topics_per_day'],
		'L_NUMBER_USERS' => $lang['Number_users'],
		'L_USERS_PER_DAY' => $lang['Users_per_day'],
		'L_BOARD_STARTED' => $lang['Board_started'],
		'L_AVATAR_DIR_SIZE' => $lang['Avatar_dir_size'],
		'L_DB_SIZE' => $lang['Database_size'],
		'L_FORUM_LOCATION' => $lang['Forum_Location'],
		'L_STARTED' => $lang['Login'],
		'L_LISTOFADMINEDIT' => $lang['L_LISTOFADMINEDIT'],
		'L_LISTOFADMINEDITEXP' => $lang['L_LISTOFADMINEDITEXP'],
		'L_LISTOFADMINEDITUSERS' => $lang['L_LISTOFADMINEDITUSERS'],
		'L_LISTOFADMINTEXT' => $lang['L_LISTOFADMINTEXT'],
		'L_DELETEMSG' => $lang['L_DELETEMSG'],
		'L_NUMBER_DEACTIVATED_USERS' => $lang['Thereof_deactivated_users'],
		'L_NAME_DEACTIVATED_USERS' => $lang['Deactivated_Users'],
		'L_NUMBER_MODERATORS' => $lang['Thereof_Moderators'],
		'L_NAME_MODERATORS' => $lang['Users_with_Mod_Privileges'],
		'L_NUMBER_JUNIOR_ADMINISTRATORS' => $lang['Thereof_Junior_Administrators'],
		'L_NAME_JUNIOR_ADMINISTRATORS' => $lang['Users_with_Junior_Admin_Privileges'],
		'L_NUMBER_ADMINISTRATORS' => $lang['Thereof_Administrators'],
		'L_NAME_ADMINISTRATORS' => $lang['Users_with_Admin_Privileges'],
		'L_DB_SIZE' => $lang['DB_size'],
		'L_IP_VERSION' => $lang['Version_of_ip'],
		'L_PHPBB_VERSION' => $lang['Version_of_board'],
		'L_PHP_VERSION' => $lang['Version_of_PHP'],
		'L_MYSQL_VERSION' => $lang['Version_of_MySQL'],
		'L_GZIP_COMPRESSION' => $lang['Gzip_compression']
		)
	);

	$sql = "SELECT COUNT(*) AS total FROM " . ADMINEDIT_TABLE;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'SQL ERROR IN ADMINEDIT_TABLE - MODE = QUERY 0', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	if(($userdata['user_id'] == $founder_id) && ($row['total'] > 0))
	{
		$template->assign_block_vars('switch_firstadmin', array());
	}

	if(isset($_POST['deleteedituser']))
	{
		$mode = 'deleteedituser';
	}
	if($mode == 'deleteedituser')
	{
		$sql = "DELETE FROM " . ADMINEDIT_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "SQL ERROR IN ADMINEDIT_TABLE - MODE = DELETE", $lang['Error'], __LINE__, __FILE__, $sql);
		}
		$message = $lang['L_DELETESUCMSG'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);

	}
	$sql = "SELECT COUNT(*) AS total FROM " . ADMINEDIT_TABLE;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'SQL ERROR IN ADMINEDIT_TABLE - MODE = QUERY 1', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	if($row['total'] > 0)
	{
		$template->assign_block_vars('switch_adminedit', array());
	}
	$sql = "SELECT * FROM " . ADMINEDIT_TABLE;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_MESSAGE, 'SQL ERROR IN ADMINEDIT_TABLE - MODE = QUERY 2', '', __LINE__, __FILE__, $sql);
	}
	$i == '0';
	while ($row = $db->sql_fetchrow($result))
	{
		$i = $i + '1';
		$template->assign_block_vars('adminedit', array(
			'EDITCOUNT' => $i,
			'EDITUSER' => colorize_username($row['editok'], '', '', '', true),
			'EDITOK' => $row['editok']
			)
		);
	}
	// Disallow other admins to delete or edit the first admin - END

	// Get forum statistics
	$total_posts = get_db_stat('postcount');
	$total_users = get_db_stat('usercount');
	$total_topics = get_db_stat('topiccount');
	$sql = "SELECT COUNT(user_id) AS total
					FROM " . USERS_TABLE . "
					WHERE user_active = 0
						AND user_id <> " . ANONYMOUS;
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	if ($row = $db->sql_fetchrow($result))
	{
		$total_deactivated_users = $row['total'];
	}
	else
	{
		throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	$deactivated_names = '';
	$sql = "SELECT username, user_id, user_active, user_color
		FROM " . USERS_TABLE . "
		WHERE user_active = 0
			AND user_id <> " . ANONYMOUS . "
		ORDER BY username";
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		$deactivated_names .= (($deactivated_names == '') ? '' : ', ') . $username;
	}
	$db->sql_freeresult($result);

	$sql = "SELECT COUNT(user_id) AS total
		FROM " . USERS_TABLE . "
		WHERE user_level = " . MOD . "
			AND user_id <> " . ANONYMOUS;
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	if ($row = $db->sql_fetchrow($result))
	{
		$total_moderators = $row['total'];
	}
	else
	{
		throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	$moderator_names = '';
	$sql = "SELECT username, user_id, user_active, user_color
		FROM " . USERS_TABLE . "
		WHERE user_level = " . MOD . "
			AND user_id <> " . ANONYMOUS . "
		ORDER BY username";
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		$moderator_names .= (($moderator_names == '') ? '' : ', ') . $username;
	}
	$db->sql_freeresult($result);

	$sql = "SELECT COUNT(user_id) AS total
		FROM " . USERS_TABLE . "
		WHERE user_level = " . JUNIOR_ADMIN . "
			AND user_id <> " . ANONYMOUS;
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	if ($row = $db->sql_fetchrow($result))
	{
		$total_junior_administrators = $row['total'];
	}
	else
	{
		throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	$junior_administrator_names = '';
	$sql = "SELECT username, user_id, user_active, user_color
		FROM " . USERS_TABLE . "
		WHERE user_level = " . JUNIOR_ADMIN . "
			AND user_id <> " . ANONYMOUS . "
		ORDER BY username";
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		$junior_administrator_names .= (($junior_administrator_names == '') ? '' : ', ') . $username;
	}

	$sql = "SELECT COUNT(user_id) AS total
		FROM " . USERS_TABLE . "
		WHERE user_level = " . ADMIN . "
			AND user_id <> " . ANONYMOUS;
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	if ($row = $db->sql_fetchrow($result))
	{
		$total_administrators = $row['total'];
	}
	else
	{
		throw_error("Couldn't update pending information!", __LINE__, __FILE__, $sql);
	}
	$db->sql_freeresult($result);

	$administrator_names = '';
	$sql = "SELECT username, user_id, user_active, user_color
		FROM " . USERS_TABLE . "
		WHERE user_level = " . ADMIN . "
			AND user_id <> " . ANONYMOUS . "
		ORDER BY username";
	if (!($result = $db->sql_query($sql)))
	{
		throw_error("Couldn't get statistic data!", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		$username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
		$administrator_names .= (($administrator_names == '') ? '' : ', ') . $username;
	}

	$start_date = create_date($board_config['default_dateformat'], $board_config['board_startdate'], $board_config['board_timezone']);

	$boarddays = (time() - $board_config['board_startdate']) / 86400;

	$posts_per_day = sprintf("%.2f", $total_posts / $boarddays);
	$topics_per_day = sprintf("%.2f", $total_topics / $boarddays);
	$users_per_day = sprintf("%.2f", $total_users / $boarddays);

	$avatar_dir_size = 0;

	if ($avatar_dir = @opendir(IP_ROOT_PATH . $board_config['avatar_path']))
	{
		while($file = @readdir($avatar_dir))
		{
			if(($file != '.') && ($file != '..'))
			{
				$avatar_dir_size += @filesize(IP_ROOT_PATH . $board_config['avatar_path'] . '/' . $file);
			}
		}
		@closedir($avatar_dir);

		//
		// This bit of code translates the avatar directory size into human readable format
		// Borrowed the code from the PHP.net annoted manual, origanally written by:
		// Jesse (jesse@jess.on.ca)
		//
		if($avatar_dir_size >= 1048576)
		{
			$avatar_dir_size = round($avatar_dir_size / 1048576 * 100) / 100 . ' MB';
		}
		else if($avatar_dir_size >= 1024)
		{
			$avatar_dir_size = round($avatar_dir_size / 1024 * 100) / 100 . ' KB';
		}
		else
		{
			$avatar_dir_size = $avatar_dir_size . ' Bytes';
		}
	}
	else
	{
		// Couldn't open Avatar dir.
		$avatar_dir_size = $lang['Not_available'];
	}

	if($posts_per_day > $total_posts)
	{
		$posts_per_day = $total_posts;
	}

	if($topics_per_day > $total_topics)
	{
		$topics_per_day = $total_topics;
	}

	if($users_per_day > $total_users)
	{
		$users_per_day = $total_users;
	}

	//
	// DB size ... MySQL only
	//
	// This code is heavily influenced by a similar routine in phpMyAdmin 2.2.0
	//
	if(preg_match("/^mysql/", SQL_LAYER))
	{
		$sql = "SELECT VERSION() AS mysql_version";
		if($result = $db->sql_query($sql))
		{
			$row = $db->sql_fetchrow($result);
			$version = $row['mysql_version'];

			if(preg_match("/^(3\.23|4\.|5\.)/", $version))
			{
				$db_name = (preg_match("/^(3\.23\.[6-9])|(3\.23\.[1-9][1-9])|(4\.)|(5\.)/", $version)) ? "`$dbname`" : $dbname;

				$sql = "SHOW TABLE STATUS
					FROM " . $db_name;
				if($result = $db->sql_query($sql))
				{
					$tabledata_ary = $db->sql_fetchrowset($result);

					$dbsize = 0;
					for($i = 0; $i < count($tabledata_ary); $i++)
					{
						if($tabledata_ary[$i]['Type'] != "MRG_MyISAM")
						{
							if($table_prefix != "")
							{
								if(strstr($tabledata_ary[$i]['Name'], $table_prefix))
								{
									$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
								}
							}
							else
							{
								$dbsize += $tabledata_ary[$i]['Data_length'] + $tabledata_ary[$i]['Index_length'];
							}
						}
					}
				} // Else we couldn't get the table status.
			}
			else
			{
				$dbsize = $lang['Not_available'];
			}
		}
		else
		{
			$dbsize = $lang['Not_available'];
		}
	}
	elseif(preg_match("/^mssql/", SQL_LAYER))
	{
		$sql = "SELECT ((SUM(size) * 8.0) * 1024.0) as dbsize
			FROM sysfiles";
		if($result = $db->sql_query($sql))
		{
			$dbsize = ($row = $db->sql_fetchrow($result)) ? intval($row['dbsize']) : $lang['Not_available'];
		}
		else
		{
			$dbsize = $lang['Not_available'];
		}
	}
	else
	{
		$dbsize = $lang['Not_available'];
	}

	if (is_integer($dbsize))
	{
		if($dbsize >= 1048576)
		{
			$dbsize = sprintf("%.2f MB", ($dbsize / 1048576));
		}
		else if($dbsize >= 1024)
		{
			$dbsize = sprintf("%.2f KB", ($dbsize / 1024));
		}
		else
		{
			$dbsize = sprintf("%.2f Bytes", $dbsize);
		}
	}
	$sql = "SELECT VERSION() AS mysql_version";
	$result = $db->sql_query($sql);
	if (!$result)
	{
		throw_error("Couldn't obtain MySQL Version", __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$mysql_version = $row['mysql_version'];
	$db->sql_freeresult($result);
	$template->assign_vars(array(
		'NUMBER_OF_POSTS' => $total_posts,
		'NUMBER_OF_TOPICS' => $total_topics,
		'NUMBER_OF_USERS' => $total_users,
		'START_DATE' => $start_date,
		'POSTS_PER_DAY' => $posts_per_day,
		'TOPICS_PER_DAY' => $topics_per_day,
		'USERS_PER_DAY' => $users_per_day,
		'AVATAR_DIR_SIZE' => $avatar_dir_size,
		'DB_SIZE' => $dbsize,
		'IP_VERSION' => $board_config['ip_version'],
		'PHPBB_VERSION' => '2' . $board_config['version'],
		'PHP_VERSION' => phpversion(),
		'MYSQL_VERSION' => $mysql_version,
		'NUMBER_OF_DEACTIVATED_USERS' => $total_deactivated_users,
		'NUMBER_OF_MODERATORS' => $total_moderators,
		'NUMBER_OF_JUNIOR_ADMINISTRATORS' => $total_junior_administrators,
		'NUMBER_OF_ADMINISTRATORS' => $total_administrators,
		/*
		'NAMES_OF_DEACTIVATED' => htmlspecialchars($deactivated_names),
		'NAMES_OF_MODERATORS' => htmlspecialchars($moderator_names),
		'NAMES_OF_JUNIOR_ADMINISTRATORS' => htmlspecialchars($junior_administrator_names),
		'NAMES_OF_ADMINISTRATORS' => htmlspecialchars($administrator_names),
		*/
		'NAMES_OF_DEACTIVATED' => $deactivated_names,
		'NAMES_OF_MODERATORS' => $moderator_names,
		'NAMES_OF_JUNIOR_ADMINISTRATORS' => $junior_administrator_names,
		'NAMES_OF_ADMINISTRATORS' => $administrator_names,

		'GZIP_COMPRESSION' => ($board_config['gzip_compress']) ? $lang['ON'] : $lang['OFF']
		)
	);
	// End forum statistics

	// Get users online information.
	$sql = "SELECT u.user_id, u.username, u.user_active, u.user_color, u.user_session_time, u.user_session_page, s.session_logged_in, s.session_ip, s.session_start, s.session_page, s.session_user_agent
		FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s
		WHERE s.session_logged_in = '1'
			AND u.user_id = s.session_user_id
			AND u.user_id <> " . ANONYMOUS . "
			AND s.session_time >= " . (time() - ONLINE_REFRESH) . "
		ORDER BY u.user_session_time DESC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain regd user/online information.", "", __LINE__, __FILE__, $sql);
	}
	$onlinerow_reg = $db->sql_fetchrowset($result);

	$sql = "SELECT session_page, session_logged_in, session_time, session_ip, session_start, session_user_agent
		FROM " . SESSIONS_TABLE . "
		WHERE session_logged_in = '0'
			AND session_time >= " . (time() - ONLINE_REFRESH) . "
		ORDER BY session_time DESC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain guest user/online information.", "", __LINE__, __FILE__, $sql);
	}
	$onlinerow_guest = $db->sql_fetchrowset($result);

	// Forum info
	$sql = "SELECT forum_name, forum_id FROM " . FORUMS_TABLE;
	if($forums_result = $db->sql_query($sql))
	{
		while($forumsrow = $db->sql_fetchrow($forums_result))
		{
			//$forum_data[$forumsrow['forum_id']] = get_object_lang(POST_FORUM_URL . $forumsrow['forum_id'], 'name');
			$forum_data[$forumsrow['forum_id']] = $forumsrow['forum_name'];
		}
	}
	else
	{
		message_die(GENERAL_ERROR, 'Couldn\'t obtain user/online forums information.', '', __LINE__, __FILE__, $sql);
	}

	$reg_userid_ary = array();

	if(count($onlinerow_reg))
	{
		$registered_users = 0;

		for($i = 0; $i < count($onlinerow_reg); $i++)
		{
			if(!inarray($onlinerow_reg[$i]['user_id'], $reg_userid_ary))
			{
				$reg_userid_ary[] = $onlinerow_reg[$i]['user_id'];

				//$username = $onlinerow_reg[$i]['username'];
				$username = colorize_username($onlinerow_reg[$i]['user_id'], $onlinerow_reg[$i]['username'], $onlinerow_reg[$i]['user_color'], $onlinerow_reg[$i]['user_active']);
				if($onlinerow_reg[$i]['user_allow_viewonline'] || ($userdata['user_level'] == ADMIN))
				{
					$registered_users++;
					$hidden = false;
				}
				else
				{
					$hidden_users++;
					$hidden = true;
				}

				$forum_id = false;
				$topic_id = false;
				if ((strpos($onlinerow_reg[$i]['user_session_page'], VIEWFORUM_MG) !== false) || (strpos($onlinerow_reg[$i]['user_session_page'], VIEWTOPIC_MG) !== false))
				{
					$results = array();
					ereg('_f_=([0-9]*)x', $onlinerow_reg[$i]['user_session_page'], $results);
					if (!empty($results[0]))
					{
						$forum_id = str_replace(array('_f_=', 'x'), array('', ''), $results[0]);
					}

					$results = array();
					ereg('_t_=([0-9]*)x', $onlinerow_reg[$i]['user_session_page'], $results);
					if (!empty($results[0]))
					{
						$topic_id = str_replace(array('_t_=', 'x'), array('', ''), $results[0]);
					}
				}

				if (!empty($topic_id))
				{
					// Topic info
					$sql_tt = "SELECT topic_title, forum_id FROM " . TOPICS_TABLE . " WHERE topic_id='" . $topic_id . "' LIMIT 1";
					if ($result_tt = $db->sql_query($sql_tt))
					{
						$topic_title = $db->sql_fetchrow($result_tt);
					}
					else
					{
						message_die(GENERAL_ERROR, 'Could not obtain user/online forums information', '', __LINE__, __FILE__, $sql_tt);
					}
					/*
					$location['lang'] = ((!empty($forum_id)) ? ($forum_data[$forum_id] . '&nbsp;&raquo;&nbsp;') : '') . htmlspecialchars($topic_title['topic_title']);
					$location['url'] = VIEWTOPIC_MG . '?' . ((!empty($forum_id)) ? (POST_FORUM_URL . '=' . $forum_id . '&amp;') : '') . POST_TOPIC_URL . '=' . $topic_id;
					*/
					$location['lang'] = $forum_data[$topic_title['forum_id']] . '&nbsp;&raquo;&nbsp;' . htmlspecialchars($topic_title['topic_title']);
					$location['url'] = VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $topic_title['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $topic_id;
				}
				else
				{
					if (!empty($forum_id))
					{
						$location['lang'] = $forum_data[$forum_id];
						$location['url'] = VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id;
					}
					else
					{
						$location = get_online_page($onlinerow_reg[$i]['user_session_page']);
					}
				}

				$location['url'] = append_sid(IP_ROOT_PATH . $location['url']);

				$row_class = ($registered_users % 2) ? $theme['td_class1'] : $theme['td_class2'];

				$reg_ip = decode_ip($onlinerow_reg[$i]['session_ip']);

				$template->assign_block_vars('reg_user_row', array(
					'ROW_CLASS' => $row_class,
					'USERNAME' => $username,
					'STARTED' => create_date($board_config['default_dateformat'], $onlinerow_reg[$i]['session_start'], $board_config['board_timezone']),
					'LASTUPDATE' => create_date($board_config['default_dateformat'], $onlinerow_reg[$i]['user_session_time'], $board_config['board_timezone']),
					'FORUM_LOCATION' => $location['lang'],
					'IP_ADDRESS' => $reg_ip,

					'U_WHOIS_IP' => 'http://whois.sc/' . $reg_ip,
					'U_USER_PROFILE' => append_sid('admin_users.' . PHP_EXT . '?mode=edit&amp;' . POST_USERS_URL . '=' . $onlinerow_reg[$i]['user_id']),
					'U_FORUM_LOCATION' => $location['url']
					)
				);
			}
		}

	}
	else
	{
		$template->assign_vars(array(
			'L_NO_REGISTERED_USERS_BROWSING' => $lang['No_users_browsing'])
		);
	}

	// Guest users
	if(count($onlinerow_guest))
	{
		$guest_users = 0;

		for($i = 0; $i < count($onlinerow_guest); $i++)
		{
			$guest_userip_ary[] = $onlinerow_guest[$i]['session_ip'];
			$guest_users++;

			$forum_id = false;
			$topic_id = false;
			if ((strpos($onlinerow_guest[$i]['session_page'], VIEWFORUM_MG) !== false) || (strpos($onlinerow_guest[$i]['session_page'], VIEWTOPIC_MG) !== false))
			{
				$results = array();
				ereg('_f_=([0-9]*)x', $onlinerow_guest[$i]['session_page'], $results);
				if (!empty($results[0]))
				{
					$forum_id = str_replace(array('_f_=', 'x'), array('', ''), $results[0]);
				}

				$results = array();
				ereg('_t_=([0-9]*)x', $onlinerow_guest[$i]['session_page'], $results);
				if (!empty($results[0]))
				{
					$topic_id = str_replace(array('_t_=', 'x'), array('', ''), $results[0]);
				}
			}

			if (!empty($topic_id))
			{
				// Topic info
				$sql_tt = "SELECT topic_title, forum_id FROM " . TOPICS_TABLE . " WHERE topic_id='" . $topic_id . "'";
				if ($result_tt = $db->sql_query($sql_tt))
				{
					$topic_title = $db->sql_fetchrow($result_tt);
				}
				else
				{
					message_die(GENERAL_ERROR, 'Could not obtain user/online forums information', '', __LINE__, __FILE__, $sql_tt);
				}
				/*
				$location['lang'] = ((!empty($forum_id)) ? ($forum_data[$forum_id] . '&nbsp;&raquo;&nbsp;') : '') . $topic_title['topic_title'];
				$location['url'] = VIEWTOPIC_MG . '?' . ((!empty($forum_id)) ? (POST_FORUM_URL . '=' . $forum_id . '&amp;') : '') . POST_TOPIC_URL . '=' . $topic_id;
				*/
				$location['lang'] = $forum_data[$topic_title['forum_id']] . '&nbsp;&raquo;&nbsp;' . $topic_title['topic_title'];
				$location['url'] = VIEWTOPIC_MG . '?' . POST_FORUM_URL . '=' . $topic_title['forum_id'] . '&amp;' . POST_TOPIC_URL . '=' . $topic_id;
			}
			else
			{
				if (!empty($forum_id))
				{
					$location['lang'] = $forum_data[$forum_id];
					$location['url'] = VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id;
				}
				else
				{
					$location = get_online_page($onlinerow_guest[$i]['session_page']);
				}
			}

			$location['url'] = append_sid(IP_ROOT_PATH . $location['url']);

			$row_class = ($guest_users % 2) ? $theme['td_class1'] : $theme['td_class2'];

			// MG BOTS Parsing - BEGIN
			$guest_ip = decode_ip($onlinerow_guest[$i]['session_ip']);

			$bot_name_tmp = bots_parse($onlinerow_guest[$i]['session_ip'], $board_config['bots_color']);
			if ($bot_name_tmp != false)
			{
				$name_guest = $bot_name_tmp;
			}
			else
			{
				$name_guest = '<b>' . $lang['Guest'] . '</b>';
			}
			// MG BOTS Parsing - END

			$template->assign_block_vars('guest_user_row', array(
				'ROW_CLASS' => $row_class,
				'USERNAME' => $name_guest,
				'STARTED' => create_date($board_config['default_dateformat'], $onlinerow_guest[$i]['session_start'], $board_config['board_timezone']),
				'LASTUPDATE' => create_date($board_config['default_dateformat'], $onlinerow_guest[$i]['session_time'], $board_config['board_timezone']),
				'FORUM_LOCATION' => $location['lang'],
				'IP_ADDRESS' => $guest_ip,

				'U_WHOIS_IP' => 'http://whois.sc/' . $guest_ip,
				'U_FORUM_LOCATION' => $location['url']
				)
			);
		}

	}
	else
	{
		$template->assign_vars(array(
			'L_NO_GUESTS_BROWSING' => $lang['No_users_browsing']
			)
		);
	}
	jr_admin_make_info_box();
	// phpBB version check disabled since phpBB 2 is not supported any more!!!
	/*
	// Check for new version
	$current_version = explode('.', '2' . $board_config['version']);
	$minor_revision = (int) $current_version[2];

	$errno = 0;
	$errstr = $version_info = '';
	// Version cache mod start
	// Change following two variables if you need to:
	$cache_update = 86400 * 30; // 24 hours cache timeout. change it to whatever you want
	$cache_file = MAIN_CACHE_FOLDER . 'phpbb_update_' . $board_config['default_lang'] . $board_config['version'] . '.php'; // file where to store cache

	$do_update = true;
	if(@file_exists($cache_file))
	{
		$last_update = 0;
		$version_info = '';
		@include($cache_file);
		if($last_update && !empty($version_info) && $last_update > (time() - $cache_update))
		{
			$do_update = false;
		}
		else
		{
			$version_info = '';
		}
	}

	if($do_update)
	{
		// Version cache mod end
		if ($fsock = @fsockopen('www.phpbb.com', 80, $errno, $errstr, 10))
		{
			@fwrite($fsock, "GET /updatecheck/20x.txt HTTP/1.1\r\n");
			@fwrite($fsock, "HOST: www.phpbb.com\r\n");
			@fwrite($fsock, "Connection: close\r\n\r\n");

			$get_info = false;
			while (!@feof($fsock))
			{
				if ($get_info)
				{
					$version_info .= @fread($fsock, 1024);
				}
				else
				{
					if (@fgets($fsock, 1024) == "\r\n")
					{
						$get_info = true;
					}
				}
			}
			@fclose($fsock);

			$version_info = explode("\n", $version_info);
			$latest_head_revision = (int) $version_info[0];
			$latest_minor_revision = (int) $version_info[2];
			$latest_version = (int) $version_info[0] . '.' . (int) $version_info[1] . '.' . (int) $version_info[2];

			if ($latest_head_revision == 2 && $minor_revision == $latest_minor_revision)
			{
				$version_info = '<p style="color:green">' . $lang['Version_up_to_date'] . '</p>';
			}
			else
			{
				$version_info = '<p style="color:red">' . $lang['Version_not_up_to_date'];
				$version_info .= '<br />' . sprintf($lang['Latest_version_info'], $latest_version) . ' ' . sprintf($lang['Current_version_info'], '2' . $board_config['version']) . '</p>';
			}
		}
		else
		{
			if ($errstr)
			{
				$version_info = '<p style="color:red">' . sprintf($lang['Connect_socket_error'], $errstr) . '</p>';
			}
			else
			{
				$version_info = '<p>' . $lang['Socket_functions_disabled'] . '</p>';
			}
		}

		$version_info .= '<p>' . $lang['Mailing_list_subscribe_reminder'] . '</p>';
		// Version cache mod start
		if(@$f = fopen($cache_file, 'w'))
		{
			$search = array('\\', '\'');
			$replace = array('\\\\', '\\\'');
			fwrite($f, '<' . '?php $last_update = ' . time() . '; $version_info = \'' . str_replace($search, $replace, $version_info) . '\'; ?' . '>');
			fclose($f);
			@chmod($cache_file, 0777);
		}
	}
	// Version cache mod end
	*/

	$version_info = '<p style="color:green">' . $lang['Version_up_to_date'] . '</p>';
	$version_info .= '<p>' . $lang['Mailing_list_subscribe_reminder'] . '</p>';
	$template->assign_vars(array(
		'VERSION_INFO' => $version_info,
		'L_VERSION_INFORMATION' => $lang['Version_information']
		)
	);

	// Get latest logs entry - BEGIN
	$log_item = array();
	$log_item = get_logs('', 0, $board_config['posts_per_page'], 'log_id', 'DESC');

	foreach ($log_item as $log_item_data)
	{
		$log_username = colorize_username($log_item_data['log_user_id']);
		$log_target = ($log_item_data['log_target'] >= 2) ? colorize_username($log_item_data['log_target']) : '&nbsp;';
		$log_action = parse_logs_action($log_item_data['log_id'], $log_item_data['log_action'], $log_item_data['log_desc'], $log_username, $log_target);
		$template->assign_block_vars('log_row', array(
				'LOG_ID' => $log_item_data['log_id'],
				'LOG_TIME' => create_date2($board_config['default_dateformat'], $log_item_data['log_time'], $board_config['board_timezone']),
				'LOG_PAGE' => $log_item_data['log_page'],
				'LOG_ACTION' => $log_item_data['log_action'],
				'LOG_USERNAME' => $log_username,
				'LOG_TARGET' => $log_target,
				'LOG_DESC' => $log_action['desc'],
				'S_LOG_DESC_EXTRA' => ($log_action['desc_extra'] != '') ? true : false,
				'LOG_DESC_EXTRA' => $log_action['desc_extra'],
			)
		);
	}
	// Get latest logs entry - END


	$template->pparse('body');

	include('./page_footer_admin.' . PHP_EXT);

}
else
{
	// Generate frameset
	$template->set_filenames(array('body' => ADM_TPL . 'index_frameset.tpl'));

	$template->assign_vars(array(
		'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
		'S_CONTENT_ENCODING' => $lang['ENCODING'],
		'S_FRAME_HEADER' => append_sid('ip_header.' . PHP_EXT),
		'S_FRAME_NAV' => append_sid('index.' . PHP_EXT . '?pane=left'),
		'S_FRAME_MAIN' => append_sid('index.' . PHP_EXT . '?pane=right')
		)
	);

	header ("Expires: " . gmdate("D, d M Y H:i:s", time()) . " GMT");
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

	$template->pparse('body');

	$db->sql_close();
	exit;
}

?>