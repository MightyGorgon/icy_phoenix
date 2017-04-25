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

// NOTE: this file is included from within a function!
// If we need to access general variables they must be declared global!
//global $_POST, $_GET, $_SERVER;

// Grab page data
$page_array = array();
$page_array = extract_current_page(IP_ROOT_PATH);
//dump_ary($page_array);

// Temp vars
$_varary = array();
$_tmp1 = '';
$_tmp2 = '';
// DONE: get protocol: GET, HEAD, POST, PUT
$_prot = $_SERVER['REQUEST_METHOD'];
$update_log = false;
$content = '';
$db_log = array();
$db_log_actions = (($config['db_log_actions'] == '1') || ($config['db_log_actions'] == '2')) ? true : false;

// Simplify often used variables
$_mode = urldecode(request_var('mode', ''));
if (empty($_mode))
{
	$_mode = (isset($_POST['lock']) ? 'lock' : $_mode);
	$_mode = (isset($_POST['unlock']) ? 'unlock' : $_mode);
	$_mode = (isset($_POST['recycle']) ? 'recycle' : $_mode);
}
if (!empty($_mode))
{
	$content .= '[MODE: ' . $_mode . '] - ';
}

$_confirm = (isset($_POST['confirm'])) ? true : 0;
$content .= '[CONFIRM: ' . $_confirm . '] - ';


$_forum = request_var(POST_FORUM_URL, 0);
$_forum = ($_forum < 0) ? 0 : $_forum;

$_topic = request_var(POST_TOPIC_URL, 0);
$_topic = ($_topic < 0) ? 0 : $_topic;

$_post = request_var(POST_POST_URL, 0);
$_post = ($_post < 0) ? 0 : $_post;

$_user = request_var(POST_USERS_URL, 0);
$_user = ($_user < 0) ? 0 : $_user;
//$_user = ($_user < 2) ? ANONYMOUS : $_user;

if (!empty($_forum))
{
	$content .= '[FORUM: ' . $_forum . '] - ';
}
if (!empty($_topic))
{
	$content .= '[TOPIC: ' . $_topic . '] - ';
}
if (!empty($_post))
{
	$content .= '[POST: ' . $_post . '] - ';
}
if (!empty($_user))
{
	$content .= '[USER: ' . $_user . '] - ';
}

// Log general visits - do this before action-logging (makes sure $user->data['log_id'] relates to the action rather than visit)
// Skip Visits-logging on certain pages by adding them to this switch-case
/*
switch($page_array['page_name'])
{
	case CMS_PAGE_POSTING:
		$content .= 'POSTING';
		if($mode == 'topicreview')
		{
			break;
		}
		// Log if not review (review is loaded in the frame when replying)
		$update_log = true;
		break;
	default:
		//ip_log($content);
}
*/

// Diff log-schemas for each page
/*
if(!empty($_POST))
{
	dump_ary($_POST); // easy way to figure out scheme-variables to check
}
*/
if(($page_array['page_dir'] == ADM) || ($page_array['page_dir'] == ('../' . ADM)))
{
	// ACP Logging
	switch($page_array['page_name'])
	{
		case 'admin_forums_extend.' . PHP_EXT:
			if(isset($_POST['update']) && !empty($_POST['name']))
			{
				$content .= '[Forum: ' . $_POST['name'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_CAT_ADD',
						'desc' => $_POST['name'],
						'target' => '',
					);
				}
				$update_log = true;
			}
			break;
		case 'admin_forumauth.' . PHP_EXT:
			if(isset($_POST['simpleauth']) && $_forum)
			{
				$content .= '[Forum Auth: ' . $_forum . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_FORUM_AUTH',
						'desc' => $_forum,
						'target' => '',
					);
				}
				$update_log = true;
			}
			break;
		case 'admin_db_backup.' . PHP_EXT:
			if(isset($_POST['submit']))
			{
				if($_mode == 'backup')
				{
					$content .= '[DB Backup: ' . $_POST['type'] . ' => ' . $_POST['where'] . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'ADMIN_DB_UTILITIES_BACKUP',
							'desc' => $_POST['type'] . ';' . $_POST['where'],
							'target' => '',
						);
					}
					$update_log = true;
				}
				elseif ($_mode == 'restore')
				{
					$content .= '[DB Restore: ' . $_POST['backup_file'] . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'ADMIN_DB_UTILITIES_RESTORE',
							'desc' => $_POST['backup_file'],
							'target' => '',
						);
					}
					$update_log = true;
				}
			}
			break;
		case ('admin_config_settings.' . PHP_EXT):
			if(isset($_POST['submit']))
			{
				$content .= '[Icy Phoenix Config]';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_BOARD_IP_CONFIG',
						'desc' => '',
						'target' => '',
					);
				}
				$update_log = true;
			}
			break;
		case 'admin_board.' . PHP_EXT:
		case 'admin_board_clearcache.' . PHP_EXT:
		case 'admin_board_quick_settings.' . PHP_EXT:
			if(isset($_POST['submit']))
			{
				$content .= '[Board Config]';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_BOARD_CONFIG',
						'desc' => '',
						'target' => '',
					);
				}
				$update_log = true;
			}
			break;
		case 'admin_groups.' . PHP_EXT:
			if($_mode == 'newgroup')
			{
				$content .= '[Group Name: ' . $_POST['group_name'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_GROUP_NEW',
						'desc' => $_POST['group_name'],
						'target' => '',
					);
				}
				$update_log = true;
			}
			elseif($_mode == 'editgroup' && $_POST['group_delete'] == 1)
			{
				$content .= '[Group Delete: ' . $_POST['group_name'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_GROUP_DELETE',
						'desc' => $_POST['group_name'],
						'target' => '',
					);
				}
				$update_log = true;
			}
			elseif($_mode == 'editgroup' && isset($_POST[POST_GROUPS_URL]))
			{
				$content .= '[Group Edit: ' . $_POST['group_name'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_GROUP_EDIT',
						'desc' => $_POST['group_name'],
						'target' => '',
					);
				}
				$update_log = true;
			}
			break;
		case 'admin_ug_auth.' . PHP_EXT:
			if($_mode == 'user' && isset($_POST['submit']))
			{
				$content .= '[User Auth: ' . $_POST[POST_USERS_URL] . ' ==> . ' . $_POST['userlevel'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_USER_AUTH',
						'desc' => $_POST['userlevel'],
						'target' => $_POST[POST_USERS_URL],
					);
				}
				$update_log = true;
			}
			elseif(isset($_POST[POST_GROUPS_URL]) && isset($_POST['adv']))
			{
				$content .= '[Group Auth: ' . $_POST[POST_GROUPS_URL] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_GROUP_AUTH',
						'desc' => $_POST[POST_GROUPS_URL],
						'target' => '',
					);
				}
				$update_log = true;
			}
			break;
		case 'admin_user_ban.' . PHP_EXT:
			if($_mode == 'edit')
			{
				$_data = '';
				if(!empty($_POST['ban_email']))
				{
					$_data = $_POST['ban_email'];
				}
				if(!empty($_POST['ban_ip']))
				{
					$_data .= (($_data != '') ? ', ' : '') . $_POST['ban_ip'];
				}
				if(!empty($_POST['username']))
				{
					$_data .= (($_data != '') ? ', ' : '') . $_POST['username'];
				}
				if(!empty($_data))
				{
					$content .= '[Ban Edit: ' . $_data . ']';
					if ($db_log_actions == true)
					{
						if(!empty($_POST['username']))
						{
							$sql = get_users_sql(phpbb_clean_username($_POST['username']), false, false, true, true);
							$result = $db->sql_query($sql);
							$user_row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);
						}

						$db_log = array(
							'action' => 'ADMIN_USER_BAN',
							'desc' => $_data,
							'target' => $user_row['user_id'],
						);
					}
					$update_log = true;
				}
				$_data = '';
				// NOTE: we only know the ban_id being unbanned... not what user,ip or email
				if(!empty($_POST['unban_user']))
				{
					foreach($_POST['unban_user'] as $key => $val)
					{
						if($val > 0)
						{
							$_data .= (($_data != '') ? ', ' : '') . $val;
						}
					}
				}
				if(!empty($_POST['unban_ip']))
				{
					foreach($_POST['unban_ip'] as $key => $val)
					{
						if($val > 0)
						{
							$_data .= (($_data != '') ? ', ' : '') . $val;
						}
					}
				}
				if(!empty($_POST['unban_email']))
				{
					foreach($_POST['unban_email'] as $key => $val)
					{
						if($val > 0)
						{
							$_data .= (($_data != '') ? ', ' : '') . $val;
						}
					}
				}
				if(!empty($_data))
				{
					$content .= '[Ban Edit: ' . $_data . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'ADMIN_USER_UNBAN',
							'desc' => $_data,
							'target' => '',
						);
					}
					$update_log = true;
				}
			}
			break;
		case 'admin_users.' . PHP_EXT:
			if($_mode == 'save' && isset($_POST['id']) && isset($_POST['deleteuser']))
			{
				$content .= '[User Delete: ' . $_POST['id'] . ' ==> ' . $_POST['acp_username'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_USER_DELETE',
						'desc' => $_POST['acp_username'],
						'target' => '',
					);
				}
				$update_log = true;
			}
			elseif($_mode == 'save' && isset($_POST['id']))
			{
				$content .= '[User Edit: ' . $_POST['id'] . ' ==> ' . $_POST['acp_username'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'ADMIN_USER_EDIT',
						'desc' => $_POST['acp_username'],
						'target' => $_POST['id'],
					);
				}
				$update_log = true;
			}
			break;
		default:
			// No default action
	}
}
elseif(($page_array['page_dir'] == '') || ($page_array['page_dir'] == './'))
{
	switch($page_array['page_name'])
	{
		case CMS_PAGE_POSTING:
			// post-deletion, edits
			if ($db_log_actions && !empty($_post))
			{
				$sql = "SELECT poster_id FROM " . POSTS_TABLE . " WHERE post_id = '" . $_post . "'";
				$result = $db->sql_query($sql);
				$post_row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
			}
			if(($_mode == 'delete') && $_confirm && $_post)
			{
				$content .= '[Post Delete: ' . $_post . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'POST_DELETE',
						'desc' => $_post,
						'target' => $post_row['poster_id'],
					);
				}
				$update_log = true;
			}
			elseif(($_mode == 'editpost') && $_post && isset($_POST['post']))
			{
				$content .= '[Post Edit: ' . $_POST['subject'] . ' ==> ' . $_post . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'POST_EDIT',
						'desc' => $_post . ';' . $_POST['subject'],
						'target' => $post_row['poster_id'],
					);
				}
				$update_log = true;
			}
			break;
		case CMS_PAGE_GROUP_CP:
			if((isset($_GET[POST_GROUPS_URL]) || isset($_POST[POST_GROUPS_URL])))
			{
				// both the POST and the GET POST_GROUPS_URL var should be set
				$_tmp1 = isset($_GET[POST_GROUPS_URL]) ? intval($_GET[POST_GROUPS_URL]) : intval($_POST[POST_GROUPS_URL]);
			}
			if($_tmp1 > 0)
			{
				// Only log if we actually have a group_id
				if(isset($_POST['joingroup']))
				{
					$content .= '[Group Join: ' . $_tmp1 . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'GROUP_JOIN',
							'desc' => $_tmp1,
							'target' => '',
						);
					}
					$update_log = true;
				}
				if (((isset($_POST['approve']) || isset($_POST['deny'])) && isset($_POST['pending_members'])) || (isset($_POST['remove']) && isset($_POST['members'])))
				{
					if(isset($_POST['remove']))
					{
						$_varary = $_POST['members'];
					}
					else
					{
						$_varary = $_POST['pending_members'];
					}

					$_data = '';
					for($i = 0; $i < sizeof($_varary); $i++)
					{
						$_data .= (($_data != '') ? ', ' : '') . intval($_varary[$i]);
					}
					$content .= '[Group Edit: ' . $_tmp1 . ' ==> ' . $_data . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'GROUP_EDIT',
							'desc' => $_tmp1,
							'target' => $_data,
						);
					}
					$update_log = true;
				}
				elseif(isset($_POST['add']) && isset($_POST['username']))
				{
					$content .= '[Group Add: ' . $_tmp1 . ' ==> ' . $_POST['username'] . ']';
					if ($db_log_actions == true)
					{
						$sql = get_users_sql(phpbb_clean_username($_POST['username']), false, false, true, true);
						$result = $db->sql_query($sql);
						$user_row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						$db_log = array(
							'action' => 'GROUP_ADD',
							'desc' => $_tmp1,
							'target' => $user_row['user_id'],
						);
					}
					$update_log = true;
				}
				elseif(isset($_POST['groupstatus']) && isset($_POST['group_type']))
				{
					$content .= '[Group Type: ' . $_tmp1 . ' ==> ' . intval($_POST['group_type']) . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'GROUP_TYPE',
							'desc' => $_tmp1 . ';' . intval($_POST['group_type']),
							'target' => '',
						);
					}
					$update_log = true;
				}
			}
			break;
		case CMS_PAGE_PROFILE:
			/*
			if(($_mode == 'register') && isset($_POST['agreed']) && ($_prot == 'POST'))
			{
				if(!empty($_POST['username']) && !empty($_POST['website']))
				{
					$content .= '[User Register: ' . $_tmp1 . ' ==> ' . $_POST['website'] . ']';
					$update_log = true;
				}
				elseif(!empty($_POST['username']))
				{
					$content .= '[Profile Edit: ' . $_tmp1 . ' ==> ' . $_POST['website'] . ']';
					$update_log = true;
				}
			}
			*/
			// Adding users into groups from profile page
			if((isset($_GET[POST_GROUPS_URL]) || isset($_POST[POST_GROUPS_URL])))
			{
				// both the POST and the GET POST_GROUPS_URL var should be set
				$_tmp1 = isset($_GET[POST_GROUPS_URL]) ? intval($_GET[POST_GROUPS_URL]) : intval($_POST[POST_GROUPS_URL]);
			}
			if((isset($_GET[POST_USERS_URL]) || isset($_POST[POST_USERS_URL])))
			{
				// both the POST and the GET POST_USERS_URL var should be set
				$_tmp2 = isset($_GET[POST_USERS_URL]) ? intval($_GET[POST_USERS_URL]) : intval($_POST[POST_USERS_URL]);
			}
			// Only log if we actually have both user_id and group_id
			if(($_tmp1 > 0) && ($_tmp2 > 0))
			{
				if(isset($_POST['ug_add']))
				{
					$content .= '[Group Add: ' . $_tmp1 . ' ==> ' . $_tmp2 . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'GROUP_ADD',
							'desc' => $_tmp1,
							'target' => $_tmp2,
						);
					}
					$update_log = true;
				}
				elseif (isset($_POST['ug_rem']))
				{
					$content .= '[Group Edit: ' . $_tmp1 . ' ==> ' . $_tmp2 . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'GROUP_EDIT',
							'desc' => $_tmp1,
							'target' => $_tmp2,
						);
					}
					$update_log = true;
				}
			}
			break;
		case 'modcp.' . PHP_EXT:
			if(($_mode == 'move') || ($_mode == 'delete') || ($_mode == 'lock') || ($_mode == 'unlock') || ($_mode == 'merge') || ($_mode == 'recycle'))
			{
				$_varary = (isset($_POST['topic_id_list'])) ? $_POST['topic_id_list'] : array($_topic);
				$_data = '';
				$_users = '';
				$user_ids = array();
				for($i = 0; $i < sizeof($_varary); $i++)
				{
					$_data .= (($_data != '') ? ', ' : '') . intval($_varary[$i]);

					if ($db_log_actions == true)
					{
						$sql = "SELECT topic_poster FROM " . TOPICS_TABLE . " WHERE topic_id = '" . intval($_varary[$i]) . "'";
						$result = $db->sql_query($sql);
						$user_id = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						if (!in_array(intval($user_id['topic_poster']), $user_ids))
						{
							$user_ids[] = intval($user_id['topic_poster']);
							$_users .= (($_users != '') ? ', ' : '') . intval($user_id['topic_poster']);
						}
					}
				}
				if($_confirm)
				{
					if($_mode == 'delete')
					{
						$content .= '[Topic Delete: ' . $_data . ']';
						if ($db_log_actions == true)
						{
							$db_log = array(
								'action' => 'MODCP_DELETE',
								'desc' => $_data,
								'target' => $_users,
							);
						}
					}
					if($_mode == 'move')
					{
						$new_forum_id = intval(substr($_POST['new_forum'], 1));
						$content .= '[Topic Move: ' . $_data . ' ==> ' . $new_forum_id . ']';
						if ($db_log_actions == true)
						{
							$sql = "SELECT forum_name FROM " . FORUMS_TABLE . " WHERE forum_id = '" . $new_forum_id . "'";
							$result = $db->sql_query($sql);
							$forum_row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);

							$db_log = array(
								'action' => 'MODCP_MOVE',
								'desc' => $_data . ';' . $new_forum_id . ';' . $forum_row['forum_name'],
								'target' => $_users,
							);
						}
					}
					if($_mode == 'merge')
					{
						$content .= '[Topic Merge: ' . $_data . ' ==> ' . intval($_POST['new_topic']) . ']';
						if ($db_log_actions == true)
						{
							$sql = "SELECT topic_title FROM " . TOPICS_TABLE . " WHERE topic_id = '" . intval($_POST['new_topic']) . "'";
							$result = $db->sql_query($sql);
							$topic_row = $db->sql_fetchrow($result);
							$db->sql_freeresult($result);

							$db_log = array(
								'action' => 'MODCP_MERGE',
								'desc' => $_data . ';' . intval($_POST['new_topic']) . ';' . $topic_row['topic_title'],
								'target' => $_users,
							);
						}
					}
					$update_log = true;
				}
				if($_mode == 'recycle')
				{
					$content .= '[Topic Recycle: ' . $_data . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'MODCP_RECYCLE',
							'desc' => $_data,
							'target' => $_users,
						);
					}
					$update_log = true;
				}
				if($_mode == 'lock')
				{
					$content .= '[Topic Lock: ' . $_data . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'MODCP_LOCK',
							'desc' => $_data,
							'target' => $_users,
						);
					}
					$update_log = true;
				}
				if($_mode == 'unlock')
				{
					$content .= '[Topic Unlock: ' . $_data . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'MODCP_UNLOCK',
							'desc' => $_data,
							'target' => $_users,
						);
					}
					$update_log = true;
				}
			}
			if($_mode == 'split')
			{
				if (isset($_POST['split_type_all']) || isset($_POST['split_type_beyond']))
				{
					$_varary = $_POST['post_id_list'];

					for ($i = 0; $i < sizeof($_varary); $i++)
					{
						$_data .= (($_data != '') ? ', ' : '') . intval($_varary[$i]);
					}
					$new_forum_id = intval(substr($_POST['new_forum_id'], 1));
					$content .= '[Topic Split: ' . $_data . ' ==> ' . $new_forum_id . ']';
					if ($db_log_actions == true)
					{
						$sql = "SELECT forum_name FROM " . FORUMS_TABLE . " WHERE forum_id = '" . $new_forum_id . "'";
						$result = $db->sql_query($sql);
						$forum_row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						$db_log = array(
							'action' => 'MODCP_SPLIT',
							'desc' => $_data . ';' . $new_forum_id . ';' . $forum_row['forum_name'] . ';' . $_POST['subject'],
							'target' => $_users,
						);
					}
					$update_log = true;
				}
			}
			break;
		case 'bin.' . PHP_EXT:
			$content .= '[Topic Recycle: ' . $_topic . ']';
			if ($db_log_actions == true)
			{
				$sql = "SELECT topic_title, topic_poster FROM " . TOPICS_TABLE . " WHERE topic_id = '" . intval($_topic) . "'";
				$result = $db->sql_query($sql);
				$topic_row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);

				$db_log = array(
					'action' => 'TOPIC_BIN',
					'desc' => $_topic . ';' . $topic_row['topic_title'],
					'target' => $topic_row['topic_poster'],
				);
			}
			$update_log = ($this->data['user_id'] != ANONYMOUS) ? true : false;
			break;
		case 'viewtopic.' . PHP_EXT:
			// Log hackattacks to warnings log
			if (isset($_GET['highlight']))
			{
				if(preg_match('/system\(chr\(\d+\)/', $_GET['highlight']))
				{
					$content .= '[Viewtopic Attack: ' . $_GET['highlight'] . ']';
					if ($db_log_actions == true)
					{
						$sql = "SELECT topic_title, topic_poster FROM " . TOPICS_TABLE . " WHERE topic_id = " . intval($_topic) . "";
						$result = $db->sql_query($sql);
						$topic_row = $db->sql_fetchrow($result);
						$db->sql_freeresult($result);

						$db_log = array(
							'action' => 'TOPIC_ATTACK',
							'desc' => $_topic . ';' . $topic_row['topic_title'],
							'target' => $topic_row['topic_poster'],
						);
					}
					$update_log = true;
				}
			}
			break;
		case 'card.' . PHP_EXT:
			if ($db_log_actions == true)
			{
				$sql = "SELECT poster_id FROM " . POSTS_TABLE . " WHERE post_id = '" . intval($_GET['post_id']) . "'";
				$result = $db->sql_query($sql);
				$post_row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
			}
			if($_mode == 'ban')
			{
				$content .= '[Ban: ' . $post_row['poster_id'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'CARD_BAN',
						'desc' => '',
						'target' => $post_row['poster_id'],
					);
				}
			}
			if($_mode == 'warn')
			{
				$content .= '[Warn: ' . $post_row['poster_id'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'CARD_WARN',
						'desc' => '',
						'target' => $post_row['poster_id'],
					);
				}
			}
			if($_mode == 'unban')
			{
				$content .= '[Unban: ' . $post_row['poster_id'] . ']';
				if ($db_log_actions == true)
				{
					$db_log = array(
						'action' => 'CARD_UNBAN',
						'desc' => '',
						'target' => $post_row['poster_id'],
					);
				}
			}
			$update_log = ($this->data['user_id'] != ANONYMOUS) ? true : false;
		break;
		case 'cms.' . PHP_EXT:
			if($_mode == 'layouts')
			{
				if (($_GET['action'] == 'edit') && isset($_POST['save']))
				{
					$l_id = (intval($_GET['l_id']));
					$content .= '[CMS Layout Edit: ' . $l_id . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'CMS_LAYOUT_EDIT',
							'desc' => $l_id,
							'target' => '',
						);
					}
				$update_log = true;
				}
				elseif(($_GET['action'] == 'delete') && $_confirm)
				{
					$l_id = (intval($_GET['l_id']));
					$content .= '[CMS Layout Delete: ' . $l_id . ']';
					if ($db_log_actions == true)
					{
						$db_log = array(
							'action' => 'CMS_LAYOUT_DELETE',
							'desc' => $l_id,
							'target' => '',
						);
					}
				$update_log = true;
				}
			}
			if($_mode == 'blocks')
			{
				if (($_GET['action'] == 'edit') && isset($_POST['save']))
				{
					$b_id = (intval($_GET['b_id']));
					$l_id = (intval($_GET['l_id']));
					$ls_id = (intval($_GET['ls_id']));
					if ($l_id)
					{
						$content .= '[CMS Block Edit: ' . $b_id . ']';
						if ($db_log_actions == true)
						{
							$db_log = array(
								'action' => 'CMS_BLOCK_EDIT',
								'desc' => $b_id . ';' . $l_id,
								'target' => '',
							);
						}
					}
					elseif ($ls_id)
					{
						$content .= '[CMS Block Edit: ' . $b_id . ']';
						if ($db_log_actions == true)
						{
							$db_log = array(
								'action' => 'CMS_BLOCK_EDIT_LS',
								'desc' => $b_id . ';' . $ls_id,
								'target' => '',
							);
						}
					}
				$update_log = true;
				}
				elseif(($_GET['action'] == 'delete') && $_confirm)
				{
					$b_id = (intval($_GET['b_id']));
					$l_id = (intval($_GET['l_id']));
					$ls_id = (intval($_GET['ls_id']));
					if ($l_id)
					{
						$content .= '[CMS Block Delete: ' . $b_id . ']';
						if ($db_log_actions == true)
						{
							$db_log = array(
								'action' => 'CMS_BLOCK_DELETE',
								'desc' => $b_id . ';' . $l_id,
								'target' => '',
							);
						}
					}
					elseif ($ls_id)
					{
						$content .= '[CMS Block Delete: ' . $b_id . ']';
						if ($db_log_actions == true)
						{
							$db_log = array(
								'action' => 'CMS_BLOCK_DELETE_LS',
								'desc' => $b_id . ';' . $ls_id,
								'target' => '',
							);
						}
					}
				$update_log = true;
				}
			}
			break;
		default:
	}
}

if ($update_log)
{
	if (!function_exists('ip_log'))
	{
		include(IP_ROOT_PATH . 'includes/functions_mg_log.' . PHP_EXT);
	}
	ip_log($content, $db_log);
}

// unset temp-vars
unset($_mode, $_data, $_post, $_topic, $_forum, $_user, $_confirm, $_content, $_tmp1, $_tmp2, $_varary);

?>