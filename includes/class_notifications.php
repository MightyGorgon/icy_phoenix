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

class class_notifications
{
	var $notify_userdata = array();
	var $notify_userid = array();
	var $notify_forum_name = '';

	var $exclude_users = array();

	/**
	* Initialize the class
	*/
	function class_notifications()
	{
		global $config, $db, $user;

		// Build exclusion list
		$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . " WHERE ban_userid <> 0 ORDER BY ban_userid ASC";
		$result = $db->sql_query($sql, 86400, 'ban_', USERS_CACHE_FOLDER);

		$this->exclude_users = array($user->data['user_id'], ANONYMOUS);
		while ($row = $db->sql_fetchrow($result))
		{
			if (isset($row['ban_userid']) && !empty($row['ban_userid']))
			{
				$this->exclude_users[] = $row['ban_userid'];
			}
		}
		$db->sql_freeresult($result);

		// Sixty second limit
		@set_time_limit(60);

		// Let's do some checking to make sure that mass mail functions are working in win32 versions of php.
		if (!$config['smtp_delivery'] && preg_match('/[c-z]:\\\.*/i', getenv('PATH')))
		{
			$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions since php's are broken by default
			$config['smtp_delivery'] = 1;
			$config['smtp_host'] = @$ini_val('SMTP');
		}
	}

	/**
	* Send user notifications on new topic or reply
	*/
	function send_notifications($mode, &$post_data, &$topic_title, &$forum_id, &$topic_id, &$post_id, &$notify_user)
	{
		global $config, $lang, $db, $user;
		global $bbcode;

		$current_time = time();
		include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

		if ($mode != 'delete')
		{
			if ($mode == 'reply')
			{
				// Look for users with notification enabled
				$sql = "SELECT u.user_id, u.user_email, u.user_lang, u.username, f.forum_name
					FROM " . USERS_TABLE . " u, " . TOPICS_WATCH_TABLE . " tw, " . FORUMS_TABLE . " f
					WHERE tw.topic_id = " . $topic_id . "
						AND " . $db->sql_in_set('tw.user_id', $this->exclude_users, true, true) . "
						AND tw.notify_status = " . TOPIC_WATCH_UN_NOTIFIED . "
						AND f.forum_id = " . $forum_id . "
						AND u.user_id = tw.user_id
						AND u.user_active = 1";
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					if (!in_array($row['user_id'], $this->notify_userid))
					{
						if ($row['user_email'] != '')
						{
							$this->notify_userdata[] = array(
								'username' => $row['username'],
								'user_email' => $row['user_email'],
								'user_lang' => $row['user_lang']
							);
						}

						$this->notify_userid[] = $row['user_id'];
						$this->notify_forum_name = $row['forum_name'];
					}
				}
				$db->sql_freeresult($result);
			}

			if (($mode == 'newtopic') || ($mode == 'reply'))
			{
				// Reply or New Topic forum notification
				$sql = "SELECT u.user_id, u.user_email, u.user_lang, f.forum_name
					FROM " . USERS_TABLE . " u, " . FORUMS_WATCH_TABLE . " fw, " . FORUMS_TABLE . " f
					WHERE fw.forum_id = " . $forum_id . "
						AND " . $db->sql_in_set('fw.user_id', array_merge($this->exclude_users, $this->notify_userid), true, true) . "
						AND fw.notify_status = " . TOPIC_WATCH_UN_NOTIFIED . "
						AND f.forum_id = " . $forum_id . "
						AND f.forum_notify = '1'
						AND u.user_id = fw.user_id
						AND u.user_active = 1";
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					if (!in_array($row['user_id'], $this->notify_userid))
					{
						if ($row['user_email'] != '')
						{
							$this->notify_userdata[] = array(
								'username' => $row['username'],
								'user_email' => $row['user_email'],
								'user_lang' => $row['user_lang']
							);
						}

						$this->notify_userid[] = $row['user_id'];
						$this->notify_forum_name = $row['forum_name'];
					}
				}
				$db->sql_freeresult($result);
			}

			// Users array built, so start sending notifications
			if (sizeof($this->notify_userdata) > 0)
			{
				include_once(IP_ROOT_PATH . 'includes/emailer.' . PHP_EXT);
				$emailer = new emailer();
				$server_url = create_server_url();

				$topic_title = unprepare_message($topic_title);
				$topic_title = censor_text($topic_title);
				$post_text = unprepare_message($post_data['message']);
				$post_text = censor_text($post_text);

				if (!empty($config['html_email']))
				{
					$bbcode->allow_bbcode = (!empty($config['allow_bbcode']) ? $config['allow_bbcode'] : false);
					$bbcode->allow_html = (!empty($config['allow_html']) ? $config['allow_html'] : false);
					$bbcode->allow_smilies = (!empty($config['allow_smilies']) ? $config['allow_smilies'] : false);
					$post_text = $bbcode->parse($post_text);
				}
				else
				{
					$post_text = $bbcode->plain_message($post_text, '');
				}

				for ($i = 0; $i < sizeof($this->notify_userdata); $i++)
				{
					$emailer->use_template('topic_notify', $this->notify_userdata[$i]['user_lang']);
					$emailer->bcc($this->notify_userdata[$i]['user_email']);

					// The Topic_reply_notification lang string below will be used
					// if for some reason the mail template subject cannot be read
					// ... note it will not necessarily be in the posters own language!
					$emailer->set_subject($lang['Topic_reply_notification']);

					// This is a nasty kludge to remove the username var ... till (if?) translators update their templates
					$emailer->msg = preg_replace('#[ ]?{USERNAME}#', $this->notify_userdata[$i]['username'], $emailer->msg);

					if ($config['url_rw'] == '1')
					{
						$topic_url = $server_url . str_replace ('--', '-', make_url_friendly($topic_title) . '-vp' . $post_id . '.html#p' . $post_id);
					}
					else
					{
						$topic_url = $server_url . CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . $post_id . '#p' . $post_id;
					}

					$email_sig = create_signature($config['board_email_sig']);
					$emailer->assign_vars(array(
						'EMAIL_SIG' => $email_sig,
						'SITENAME' => $config['sitename'],
						'TOPIC_TITLE' => $topic_title,
						'POST_TEXT' => $post_text,
						'POSTERNAME' => $post_data['username'],
						'FORUM_NAME' => $this->notify_forum_name,
						'ROOT' => $server_url,

						'U_TOPIC' => $topic_url,
						'U_STOP_WATCHING_TOPIC' => $server_url . CMS_PAGE_VIEWTOPIC . '?' . POST_TOPIC_URL . '=' . $topic_id . '&unwatch=topic'
						)
					);

					$emailer->send();
					$emailer->reset();
				}
			}

			// Emails sent, so set users were notified
			$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
				SET notify_status = " . TOPIC_WATCH_NOTIFIED . "
				WHERE topic_id = " . $topic_id . "
				AND " . $db->sql_in_set('user_id', $this->notify_userid, false, true);
			$db->sql_query($sql);

			$sql = "UPDATE " . FORUMS_WATCH_TABLE . "
				SET notify_status = " . TOPIC_WATCH_NOTIFIED . "
				WHERE forum_id = " . $forum_id . "
				AND " . $db->sql_in_set('user_id', $this->notify_userid, false, true);
			$db->sql_query($sql);

			// Delete notification for poster if present, or re-activate it if requested
			if (!$notify_user && !empty($row['topic_id']))
			{
				$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
					WHERE topic_id = " . $topic_id . "
						AND user_id = " . $user->data['user_id'];
				$db->sql_query($sql);
			}
			elseif ($notify_user && empty($row['topic_id']))
			{
				$sql = "INSERT INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, forum_id, notify_status)
					VALUES (" . $user->data['user_id'] . ", " . $topic_id . ", " . $forum_id . ", " . TOPIC_WATCH_UN_NOTIFIED . ")";
				$db->sql_query($sql);
			}
		}
	}

	/**
	* Delete user(s) notifications
	*/
	function delete_user_notifications($user_id)
	{
		global $db;

		if (!is_array($user_id))
		{
			$user_id = array($user_id);
		}

		// Delete users notifications
		$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
			WHERE " . $db->sql_in_set('user_id', $user_id);
		$db->sql_query($sql);

		$sql = "DELETE FROM " . FORUMS_WATCH_TABLE . "
			WHERE " . $db->sql_in_set('user_id', $user_id);
		$db->sql_query($sql);
	}

	/**
	* Delete notifications for users that cannot read in a forum
	*/
	function delete_not_auth_notifications($forum_ids = '')
	{
		global $db;

		// Build the forums array
		if (!empty($forum_ids) && !is_array($forum_ids))
		{
			$forum_ids = array($forum_ids);
		}
		elseif(empty($forum_ids))
		{
			$sql = "SELECT forum_id
					FROM " . FORUMS_TABLE . "
					WHERE forum_type = " . FORUM_POST;
			$result = $db->sql_query($sql);

			$forum_ids = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$forum_ids[] = $row['forum_id'];
			}
		}

		// Seek for users with topic/forum notifications enabled within this forum(s)
		$sql = "SELECT u.user_id, u.user_level
			FROM " . USERS_TABLE . " u, " . TOPICS_WATCH_TABLE . " tw
			WHERE u.user_id = tw.user_id
				AND " . $db->sql_in_set('tw.forum_id', $forum_ids) . "
			GROUP BY (u.user_id)
			ORDER BY u.user_id ASC";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		$exclude_sql = '';
		for ($i = 0; $i < sizeof($row); $i++)
		{
			$exclude_sql .= (empty($exclude_sql) ? ',' : '') . $row[$i]['user_id'];
		}
		$exclude_sql = !empty($exclude_sql) ? ' AND u.user_id NOT IN (' . $exclude_sql . ')' : '';

		$sql = "SELECT u.user_id, u.user_level
			FROM " . USERS_TABLE . " u, " . FORUMS_WATCH_TABLE . " fw
			WHERE u.user_id = fw.user_id
				AND " . $db->sql_in_set('fw.forum_id', $forum_ids) . $exclude_sql . "
			GROUP BY (u.user_id)
			ORDER BY u.user_id ASC";
		$result = $db->sql_query($sql);
		$row2 = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		if (!is_array($row2))
		{
			$row2 = array();
		}

		// Build the not authed user array
		$row = array_merge($row, $row2);
		$not_auth_data = array();
		for ($i = 0; $i < sizeof($row); $i++)
		{
			$user_info = array(
				'user_id' => intval($row[$i]['user_id']),
				'user_level' => $row[$i]['user_level'],
				'session_logged_in' => true,
				'is_bot' => false
			);

			for ($j = 0; $j < sizeof($forum_ids); $j++)
			{
				$is_auth = auth(AUTH_READ, $forum_ids[$j], $user_info);

				if (!$is_auth['auth_read'])
				{
					if (!isset($not_auth_data[$user_info['user_id']]))
					{
						$not_auth_data[$user_info['user_id']] = array();
					}

					$not_auth_data[$user_info['user_id']] = array_merge($not_auth_data[$user_info['user_id']], array($forum_ids[$j]));
				}
			}
		}

		// Build the sql statement and execute the delete query
		if (sizeof($not_auth_data) > 0)
		{
			$where_sql = '';
			foreach($not_auth_data as $user_id => $forum_array)
			{
				$where_sql .= (empty($where_sql) ? ' WHERE' : ' OR') . ' (user_id = ' . $user_id . ' AND ' . $db->sql_in_set('forum_id', $forum_array) . ') ';
			}

			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . $where_sql;
			$db->sql_query($sql);

			$sql = "DELETE FROM " . FORUMS_WATCH_TABLE . $where_sql;
			$db->sql_query($sql);
		}
	}

	/**
	* ReSync forum_id in notifications table
	*/
	function topic_notify_resync()
	{
		global $db, $cache;

		$sql = "UPDATE " . TOPICS_TABLE . " t, " . TOPICS_WATCH_TABLE . " tw
		SET tw.forum_id = t.forum_id
		WHERE tw.topic_id = t.topic_id";
		$result = $db->sql_query($sql);

		return true;
	}

}

$notifications = new class_notifications();

?>