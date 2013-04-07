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

$sql = array();

// UPDATE IF - BEGIN
if (substr($mode, 0, 6) == 'update')
{

$page_framework->table_begin($lang['phpBB'] . ' - ' . $lang['UpdateInProgress'], 'row-post');

// Schema updates
switch ($current_phpbb_version)
{
	case '':
		$sql[] = "ALTER TABLE " . USERS_TABLE . " DROP
			COLUMN user_autologin_key";

		$sql[] = "ALTER TABLE " . RANKS_TABLE . " DROP
			COLUMN rank_max";

		$sql[] = "ALTER TABLE " . USERS_TABLE . "
			ADD COLUMN user_session_time int(11) DEFAULT '0' NOT NULL,
			ADD COLUMN user_session_page smallint(5) DEFAULT '0' NOT NULL,
			ADD INDEX (user_session_time)";
		$sql[] = "ALTER TABLE " . SEARCH_TABLE . "
			MODIFY search_id int(11) NOT NULL";

		$sql[] = "ALTER TABLE " . TOPICS_TABLE . "
			MODIFY topic_moved_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			ADD COLUMN topic_first_post_id mediumint(8) UNSIGNED DEFAULT '0' NOT NULL,
			ADD INDEX (topic_first_post_id)";

	case 'RC-3':
	case 'RC-4':
	case '.0.0':
		$sql[] = "ALTER TABLE " . USERS_TABLE . "
			MODIFY COLUMN user_id  mediumint(8) NOT NULL,
			MODIFY COLUMN user_timezone decimal(5,2) DEFAULT '0' NOT NULL";

	case '.0.1':
		$sql[] = "ALTER TABLE " . GROUPS_TABLE . "
			MODIFY COLUMN group_id mediumint(8) NOT NULL auto_increment";

	case '.0.2':
	case '.0.3':
		// Add indexes to post_id in search match table (+ word_id for MS Access)
		$sql[] = "ALTER TABLE " . SEARCH_MATCH_TABLE . "
			ADD INDEX post_id (post_id)";

		// Modify user_timezone to decimal(5,2) for mysql ... mysql4/mssql/pgsql/msaccess
		// should be completely unaffected
		// Change default user_notify to 0
		$sql[] = "ALTER TABLE " . USERS_TABLE . "
			MODIFY COLUMN user_timezone decimal(5,2) DEFAULT '0' NOT NULL,
			MODIFY COLUMN user_notify tinyint(1) DEFAULT '0' NOT NULL";

		// Adjust field type for prune_days, prune_freq ... was too small
		$sql[] = "ALTER TABLE " . PRUNE_TABLE . "
			MODIFY COLUMN prune_days smallint(5) UNSIGNED NOT NULL,
			MODIFY COLUMN prune_freq smallint(5) UNSIGNED NOT NULL";

	case '.0.4':
		$sql[] = 'CREATE TABLE ' . $table_prefix . 'confirm (confirm_id char(32) DEFAULT \'\' NOT NULL, session_id char(32) DEFAULT \'\' NOT NULL, code char(6) DEFAULT \'\' NOT NULL, PRIMARY KEY (session_id, confirm_id))';

	case '.0.5':
	case '.0.6':
	case '.0.7':
	case '.0.8':
	case '.0.9':
	case '.0.10':
	case '.0.11':
	case '.0.12':
	case '.0.13':
	case '.0.14':
		$sql[] = "ALTER TABLE " . SESSIONS_TABLE . "
			ADD COLUMN session_admin tinyint(2) DEFAULT '0' NOT NULL";

	case '.0.15':
	case '.0.16':
	case '.0.17':
		$sql[] = 'CREATE TABLE ' . $table_prefix . 'sessions_keys (key_id varchar(32) DEFAULT \'0\' NOT NULL, user_id mediumint(8) DEFAULT \'0\' NOT NULL, last_ip varchar(8) DEFAULT \'0\' NOT NULL, last_login int(11) DEFAULT \'0\' NOT NULL, PRIMARY KEY (key_id, user_id), KEY last_login (last_login))';

	case '.0.18':
		$sql[] = "ALTER TABLE " . USERS_TABLE . "
			ADD COLUMN user_login_tries smallint(5) UNSIGNED DEFAULT '0' NOT NULL";
		$sql[] = "ALTER TABLE " . USERS_TABLE . "
			ADD COLUMN user_last_login_try int(11) DEFAULT '0' NOT NULL";

	case '.0.19':
		$sql[] = "ALTER TABLE " . SEARCH_TABLE . "
			ADD COLUMN search_time int(11) DEFAULT '0' NOT NULL";

	case '.0.21':
		$sql[] = 'ALTER TABLE ' . SEARCH_TABLE . '
				MODIFY COLUMN search_array MEDIUMTEXT NOT NULL';
}

echo '<h2>' . $lang['UpdateInProgress_Schema'] . '</h2><br />';
echo('<div class="post-text">' . "\n");
echo '<p>' . $lang['Progress'] . '...';
flush();

$error_ary = array();
$errored = false;
if (sizeof($sql))
{
	for ($i = 0; $i < sizeof($sql); $i++)
	{
		$ip_sql->_sql($sql[$i], $errored, $error_ary);
	}

	echo '<span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['Done'] . '</b></span></p>';
	echo '<p>' . $lang['Result'] . ' :: ';

	if ($errored)
	{
		echo '<b>' . $lang['Update_Errors'] . '</b></p><br /><br />';
		echo '<div class="genmed"><ul type="circle">';

		for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
		{
			echo '<li>' . $error_ary['sql'][$i] . '<br /> +++ <span style="color:' . $page_framework->color_error . ';"><b>' . $lang['Error'] . ':</b></span> ' . htmlspecialchars($error_ary['error_code'][$i]['message']) . '<br /><br /></li>';
		}

		echo '</ul></div><br />';
	}
	else
	{
		echo '<span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['NoErrors'] . '</b></span></p><br /><br />';
	}
}
else
{
	echo '<span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['NoUpdate'] . '</b></span></p><br />';
	echo '<p><span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['Done'] . '</b></span></p><br /><br />';
}
echo('</div>' . "\n");
echo('<br clear="all"/><br /><br />' . "\n");

// Data updates
unset($sql);
$error_ary = array();
$errored = false;

echo '<h2>' . $lang['UpdateInProgress_Data'] . '</h2><br />';
echo('<div class="post-text">' . "\n");
echo '<p>' . $lang['Progress'] . '...';
flush();

$no_update = false;
switch ($current_phpbb_version)
{
	case '':
		$sql = "SELECT themes_id
			FROM " . THEMES_TABLE . "
			WHERE template_name = 'subSilver'";
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		if ($row = $db->sql_fetchrow($result))
		{
			$theme_id = $row['themes_id'];

			$sql = "UPDATE " . THEMES_TABLE . "
				SET head_stylesheet = 'subSilver.css', body_background = '', body_bgcolor = 'E5E5E5', tr_class1 = '', tr_class2 = '', tr_class3 = '', td_class1 = 'row1', td_class2 = 'row2', td_class3 = ''
				WHERE themes_id = $theme_id";
			$ip_sql->_sql($sql, $errored, $error_ary);

		}
		$db->sql_freeresult($result);

		$sql = "SELECT MIN(post_id) AS first_post_id, topic_id
			FROM " . POSTS_TABLE . "
			GROUP BY topic_id
			ORDER BY topic_id ASC";
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				$sql = "UPDATE " . TOPICS_TABLE . "
					SET topic_first_post_id = " . $row['first_post_id'] . "
					WHERE topic_id = " . $row['topic_id'];
				$ip_sql->_sql($sql, $errored, $error_ary);
			}
			while ($row = $db->sql_fetchrow($result));
		}
		$db->sql_freeresult($result);

		$sql = "SELECT DISTINCT u.user_id
			FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug, " . AUTH_ACCESS_TABLE . " aa
			WHERE aa.auth_mod = 1
				AND ug.group_id = aa.group_id
				AND u.user_id = ug.user_id
				AND u.user_level <> " . ADMIN;
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		$mod_user = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$mod_user[] = $row['user_id'];
		}
		$db->sql_freeresult($result);

		if (sizeof($mod_user))
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_level = " . MOD . "
				WHERE user_id IN (" . implode(', ', $mod_user) . ")";
			$ip_sql->_sql($sql, $errored, $error_ary);
		}

		$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('server_name', 'www.myserver.tld')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('script_path', '/phpBB2/')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('server_port', '80')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('record_online_users', '1')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('record_online_date', '" . time() . "')";
		$ip_sql->_sql($sql, $errored, $error_ary);

	case 'RC-3':
	case 'RC-4':
	case '.0.0':
	case '.0.1':
		$sql = "SELECT topic_id, topic_moved_id
			FROM " . TOPICS_TABLE . "
			WHERE topic_moved_id <> 0
				AND topic_status = " . TOPIC_MOVED;
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		$topic_ary = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$topic_ary[$row['topic_id']] = $row['topic_moved_id'];
		}
		$db->sql_freeresult($result);

		while (list($topic_id, $topic_moved_id) = each($topic_ary))
		{
			$sql = "SELECT MAX(post_id) AS last_post, MIN(post_id) AS first_post, COUNT(post_id) AS total_posts
				FROM " . POSTS_TABLE . "
				WHERE topic_id = $topic_moved_id";
			$result = $ip_sql->_sql($sql, $errored, $error_ary);

			$sql = ($row = $db->sql_fetchrow($result)) ? "UPDATE " . TOPICS_TABLE . " SET topic_replies = " . ($row['total_posts'] - 1) . ", topic_first_post_id = " . $row['first_post'] . ", topic_last_post_id = " . $row['last_post'] . " WHERE topic_id = $topic_id" : "DELETE FROM " . TOPICS_TABLE . " WHERE topic_id = " . $row['topic_id'];
			$ip_sql->_sql($sql, $errored, $error_ary);
		}

		unset($sql);

		sync('all_forums');

	case '.0.2':
	case '.0.3':
		// Topics will resync automatically

		// Remove stop words from search match and search words
		$dirname = 'language';
		$dir = opendir(IP_ROOT_PATH . $dirname);

		while ($file = readdir($dir))
		{
			if (preg_match("#^lang_#i", $file) && !is_file(IP_ROOT_PATH . $dirname . "/" . $file) && !is_link(IP_ROOT_PATH . $dirname . "/" . $file) && file_exists(IP_ROOT_PATH . $dirname . "/" . $file . '/search_stopwords.txt'))
			{

				$stopword_list = trim(preg_replace('#([\w\.\-_\+\'-\\\]+?)[ \n\r]*?(,|$)#', '\'\1\'\2', str_replace("'", "\'", implode(', ', file(IP_ROOT_PATH . $dirname . "/" . $file . '/search_stopwords.txt')))));

				$sql = "SELECT word_id
					FROM " . SEARCH_WORD_TABLE . "
					WHERE word_text IN ($stopword_list)";
				$result = $ip_sql->_sql($sql, $errored, $error_ary);

				$word_id_sql = '';
				if ($row = $db->sql_fetchrow($result))
				{
					do
					{
						$word_id_sql .= (($word_id_sql != '') ? ', ' : '') . $row['word_id'];
					}
					while ($row = $db->sql_fetchrow($result));

					$sql = "DELETE FROM " . SEARCH_WORD_TABLE . "
						WHERE word_id IN ($word_id_sql)";
					$ip_sql->_sql($sql, $errored, $error_ary);

					$sql = "DELETE FROM " . SEARCH_MATCH_TABLE . "
						WHERE word_id IN ($word_id_sql)";
					$ip_sql->_sql($sql, $errored, $error_ary);
				}
				$db->sql_freeresult($result);
			}
		}
		closedir($dir);

		// Mark common words ...
		remove_common('global', 4/10);

		// remove superfluous polls ... grab polls with topics then delete polls
		// not in that list
		$sql = "SELECT v.vote_id
			FROM " . TOPICS_TABLE . " t, " . VOTE_DESC_TABLE . " v
			WHERE v.topic_id = t.topic_id";
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		$vote_id_sql = '';
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				$vote_id_sql .= (($vote_id_sql != '') ? ', ' : '') . $row['vote_id'];
			}
			while ($row = $db->sql_fetchrow($result));

			$sql = "DELETE FROM " . VOTE_DESC_TABLE . "
				WHERE vote_id NOT IN ($vote_id_sql)";
			$ip_sql->_sql($sql, $errored, $error_ary);

			$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . "
				WHERE vote_id NOT IN ($vote_id_sql)";
			$ip_sql->_sql($sql, $errored, $error_ary);

			$sql = "DELETE FROM " . VOTE_USERS_TABLE . "
				WHERE vote_id NOT IN ($vote_id_sql)";
			$ip_sql->_sql($sql, $errored, $error_ary);
		}
		$db->sql_freeresult($result);

		// update pm counters
		$sql = "SELECT privmsgs_to_userid, COUNT(privmsgs_id) AS unread_count
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "
			GROUP BY privmsgs_to_userid";
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		if ($row = $db->sql_fetchrow($result))
		{
			$update_users = array();
			do
			{
				$update_users[$row['unread_count']][] = $row['privmsgs_to_userid'];
			}
			while ($row = $db->sql_fetchrow($result));

			while (list($num, $user_ary) = each($update_users))
			{
				$user_ids = implode(', ', $user_ary);

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_unread_privmsg = $num
					WHERE user_id IN ($user_ids)";
				$ip_sql->_sql($sql, $errored, $error_ary);
			}
			unset($update_list);
		}
		$db->sql_freeresult($result);

		$sql = "SELECT privmsgs_to_userid, COUNT(privmsgs_id) AS new_count
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_type = " . PRIVMSGS_NEW_MAIL . "
			GROUP BY privmsgs_to_userid";
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		if ($row = $db->sql_fetchrow($result))
		{
			$update_users = array();
			do
			{
				$update_users[$row['new_count']][] = $row['privmsgs_to_userid'];
			}
			while ($row = $db->sql_fetchrow($result));

			while (list($num, $user_ary) = each($update_users))
			{
				$user_ids = implode(', ', $user_ary);

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_new_privmsg = $num
					WHERE user_id IN ($user_ids)";
				$ip_sql->_sql($sql, $errored, $error_ary);
			}
			unset($update_list);
		}
		$db->sql_freeresult($result);

		// Remove superfluous watched topics
		$sql = "SELECT t.topic_id
			FROM " . TOPICS_TABLE . " t, " . TOPICS_WATCH_TABLE . " w
			WHERE w.topic_id = t.topic_id";
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		$topic_id_sql = '';
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . $row['topic_id'];
			}
			while ($row = $db->sql_fetchrow($result));

			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE topic_id NOT IN ($topic_id_sql)";
			$ip_sql->_sql($sql, $errored, $error_ary);
		}
		$db->sql_freeresult($result);

		// Reset any email addresses which are non-compliant ... something
		// not done in the upgrade script and thus which may affect some
		// mysql users
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_email = ''
			WHERE user_email NOT REGEXP '^[a-zA-Z0-9_\+\.\-]+@.*[a-zA-Z0-9_\-]+\.[a-zA-Z]{2,}$'";
		$ip_sql->_sql($sql, $errored, $error_ary);

	case '.0.4':
		// Add the confirmation code switch ... save time and trouble elsewhere
		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('enable_confirm', '0')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('sendmail_fix', '0')";
		$ip_sql->_sql($sql, $errored, $error_ary);

	case '.0.5':
		$sql = "SELECT user_id, username
			FROM " . USERS_TABLE;
		$result = $ip_sql->_sql($sql, $errored, $error_ary);

		while ($row = $db->sql_fetchrow($result))
		{
			if (!preg_match('#(&gt;)|(&lt;)|(&quot)|(&amp;)#', $row['username']))
			{
				if ($row['username'] != htmlspecialchars($row['username']))
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET username = '" . str_replace("'", "''", htmlspecialchars($row['username'])) . "'
						WHERE user_id = " . $row['user_id'];
					$ip_sql->_sql($sql, $errored, $error_ary);
				}
			}
		}
		$db->sql_freeresult($result);

	case '.0.6':
	case '.0.7':
	case '.0.8':
	case '.0.9':
	case '.0.10':
	case '.0.11':
	case '.0.12':
	case '.0.13':
	case '.0.14':
		$sql = 'UPDATE ' . USERS_TABLE . ' SET user_allowhtml = 1 WHERE user_id = ' . ANONYMOUS;
		$ip_sql->_sql($sql, $errored, $error_ary);

	case '.0.15':
	case '.0.16':
	case '.0.17':
		$sql = 'UPDATE ' . USERS_TABLE . ' SET user_active = 0 WHERE user_id = ' . ANONYMOUS;
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('allow_autologin', '1')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('max_autologin_time', '0')";
		$ip_sql->_sql($sql, $errored, $error_ary);

	case '.0.18':
		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('max_login_attempts', '5')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('login_reset_time', '30')";
		$ip_sql->_sql($sql, $errored, $error_ary);

	case '.0.19':
		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('search_flood_interval', '15')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('rand_seed', '0')";
		$ip_sql->_sql($sql, $errored, $error_ary);

	case '.0.20':
		$sql = 'INSERT INTO ' . CONFIG_TABLE . " (config_name, config_value)
			VALUES ('search_min_chars', '3')";
		$ip_sql->_sql($sql, $errored, $error_ary);

		// We reset those having autologin enabled and forcing the re-assignment of a session id
		// since there have been changes to the way these are handled from previous versions
		$sql = 'DELETE FROM ' . SESSIONS_TABLE;
		$ip_sql->_sql($sql, $errored, $error_ary);

		$sql = 'DELETE FROM ' . SESSIONS_KEYS_TABLE;
		$ip_sql->_sql($sql, $errored, $error_ary);

	case '.0.21':
	case '.0.22':
		// update the version
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $phpbb_version . "'
			WHERE config_name = 'version'";
		$ip_sql->_sql($sql, $errored, $error_ary);

		break;

	default:
		$no_update = true;
		break;
}

echo '<span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['Done'] . '</b></span></p>';
if ($no_update)
{
	echo '<p><span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['NoUpdate'] . '</b></span></p><br />';
}

if ($errored)
{
	echo '<p>' . $lang['Result'] . ' :: <b>' . $lang['Update_Errors'] . '</b></p><br /><br />';
	echo '<div class="genmed"><ul type="circle">';

	for ($i = 0; $i < sizeof($error_ary['sql']); $i++)
	{
		echo '<li>' . $error_ary['sql'][$i] . '<br /> +++ <span style="color:' . $page_framework->color_error . ';"><b>' . $lang['Error'] . ':</b></span> ' . htmlspecialchars($error_ary['error_code'][$i]['message']) . '<br /><br /></li>';
	}

	echo '</ul></div><br />';
}
else
{
	echo '<br /><br />';
}
echo('</div>' . "\n");
echo('<br clear="all"/><br /><br />' . "\n");

echo '<h2>' . $lang['Optimizing_Tables'] . '</h2><br />';
echo('<div class="post-text">' . "\n");
echo '<p>' . $lang['Progress'] . '...';
flush();

$errored = array();
$error_ary = array();

// Optimize/vacuum analyze the tables where appropriate
// this should be done for each version in future along with
// the version number update
$sql = 'OPTIMIZE TABLE ' . $table_prefix . 'auth_access, ' . $table_prefix . 'banlist, ' . $table_prefix . 'categories, ' . $table_prefix . 'config, ' . $table_prefix . 'disallow, ' . $table_prefix . 'forum_prune, ' . $table_prefix . 'forums, ' . $table_prefix . 'groups, ' . $table_prefix . 'posts, ' . $table_prefix . 'privmsgs, ' . $table_prefix . 'privmsgs_text, ' . $table_prefix . 'ranks, ' . $table_prefix . 'search_results, ' . $table_prefix . 'search_wordlist, ' . $table_prefix . 'search_wordmatch, ' . $table_prefix . 'sessions_keys, ' . $table_prefix . 'smilies, ' . $table_prefix . 'themes, ' . $table_prefix . 'topics, ' . $table_prefix . 'topics_watch, ' . $table_prefix . 'user_group, ' . $table_prefix . 'users, ' . $table_prefix . 'vote_desc, ' . $table_prefix . 'vote_results, ' . $table_prefix . 'vote_voters, ' . $table_prefix . 'words';

$ip_sql->_sql($sql, $errored, $error_ary);

echo '<span style="color:' . $page_framework->color_ok . ';"><b>' . $lang['Done'] . '</b></span></p><br /><br />';
echo('</div>' . "\n");
echo('<br clear="all"/>' . "\n");
echo '</td></tr></table>';
flush();
}
// UPDATE IF - END

?>