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
	exit;
}

/*
if ($board_config['allow_zebra'] == false)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
}
*/

$zmode = 'friends';
$zmode_types = array('friends', 'foes');
if ( isset($_GET['zmode']) || isset($_POST['zmode']) )
{
	$zmode = ( isset($_GET['zmode']) ) ? htmlspecialchars($_GET['zmode']) : htmlspecialchars($_POST['zmode']);
	$zmode = htmlspecialchars($zmode);
}
$zmode = in_array($zmode, $zmode_types) ? $zmode : 'friends';

// Forced to friends...
$zmode = 'friends';

if ( isset($_POST['submit']) )
{
	$data = array();
	$error = array();
	$updated = false;

	$var_ary = array(
		'usernames' => array(0),
		'add' => '',
	);

	foreach ($var_ary as $var => $default)
	{
		$data[$var] = request_var($var, $default, true);
	}

	if (!empty($data['add']) || count($data['usernames']))
	{
		if ($data['add'])
		{
			$data['add'] = array_map('trim', explode("\n", $data['add']));

			// Do these name/s exist on a list already? If so, ignore ... we could be
			// 'nice' and automatically handle names added to one list present on
			// the other (by removing the existing one) ... but I have a feeling this
			// may lead to complaints
			$sql = 'SELECT z.*, u.username
				FROM ' . ZEBRA_TABLE . ' z, ' . USERS_TABLE . ' u
				WHERE z.user_id = ' . $userdata['user_id'] . '
					AND u.user_id = z.zebra_id';
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not query ZEBRA table', $lang['Error'], __LINE__, __FILE__, $sql);
			}
			$friends = array();
			$foes = array();
			while ($row = $db->sql_fetchrow($result))
			{
				if ($row['friend'])
				{
					$friends[] = $row['user_id'];
				}
				else
				{
					$foes[] = $row['user_id'];
				}
			}
			$db->sql_freeresult($result);

			// remove friends from the username array
			$n = count($data['add']);
			$data['add'] = array_diff($data['add'], $friends);

			// remove foes from the username array
			$n = count($data['add']);
			$data['add'] = array_diff($data['add'], $foes);

			// remove the user himself from the username array
			$n = count($data['add']);
			$data['add'] = array_diff($data['add'], array($userdata['username']));

			unset($friends, $foes, $n);

			if (count($data['add']))
			{
				$users_to_add = '';
				foreach ($data['add'] as $user_tmp)
				{
					$username_tmp = phpbb_clean_username($user_tmp);
					$users_to_add .= (($users_to_add == '') ? '' : ', ') . '\'' . $username_tmp . '\'';
				}
				//$users_to_add = implode('\',\'', $data['add']);
				$sql = 'SELECT user_id, user_level
					FROM ' . USERS_TABLE . '
					WHERE username IN (' . $users_to_add . ')
						AND user_active = 1';
				//die($sql);
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not query users table', $lang['Error'], __LINE__, __FILE__, $sql);
				}

				$user_id_ary = array();
				$user_id_level = array();
				while ($row = $db->sql_fetchrow($result))
				{
					if ($row['user_id'] != ANONYMOUS)
					{
						$user_id_ary[$row['user_id']] = $row['user_id'];
						$user_id_level[$row['user_id']] = $row['user_level'];
					}
				}
				$db->sql_freeresult($result);

				if (count($user_id_ary))
				{
					// Remove users from foe list if they are admins or moderators
					if ($zmode == 'foes')
					{
						$perms = array();
						foreach ($user_id_ary as $user_tmp)
						{
							if ($user_id_level[$row['user_id']] > 0)
							{
								$perms[] = array_merge($perms, $user_tmp);
							}
						}
						$perms = array_unique($perms);

						// This may not be right ... it may yield true when perms equate to deny
						$user_id_ary = array_diff($user_id_ary, $perms);
						unset($perms);
					}

					if (count($user_id_ary))
					{
						$sql_values = ($zmode == 'friends') ? '\'1\', \'0\'' : '\'0\', \'1\'';

						$sql_ary = array();
						foreach ($user_id_ary as $zebra_id)
						{
							$sql = "INSERT INTO " . ZEBRA_TABLE . " (`user_id` , `zebra_id` , `friend` , `foe` )
											VALUES ('" . $userdata['user_id'] . "', '" . $zebra_id . "', " . $sql_values . ")";
							if ( !($result = $db->sql_query($sql)) )
							{
								message_die(GENERAL_ERROR, 'Could not update ZEBRA table', '', __LINE__, __FILE__, $sql);
							}
						}
						$updated = true;
					}
					unset($user_id_ary);
				}
			}
		}
		elseif (count($data['usernames']))
		{
			// Force integer values
			$data['usernames'] = array_map('intval', $data['usernames']);
			$users_to_del = implode('\',\'', $data['usernames']);
			$sql = 'DELETE FROM ' . ZEBRA_TABLE . '
				WHERE user_id = ' . $userdata['user_id'] . '
					AND zebra_id IN (\'' . $users_to_del . '\')';
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error in deleting ZEBRAS', '', __LINE__, __FILE__, $sql);
			}
			$updated = true;
		}

		$db->clear_cache('zebra_users_');
		if ($updated)
		{
			$redirect_url = append_sid(append_sid(PROFILE_MG . '?mode=zebra&amp;zmode=' . $zmode));
			meta_refresh(3, $redirect_url);
			message_die(GENERAL_MESSAGE, (($zmode == 'friends') ? $lang['FRIENDS_UPDATED'] : $lang['FOES_UPDATED']));
		}
		else
		{
			message_die(GENERAL_ERROR, (($zmode == 'friends') ? $lang['FRIENDS_UPDATE_ERROR'] : $lang['FOES_UPDATE_ERROR']));
		}
	}
}

$page_title = $lang['UCP_ZEBRA_FRIENDS'];
$meta_description = '';
$meta_keywords = '';

$sql_and = ($zmode == 'foes') ? 'z.foe = 1' : 'z.friend = 1';
$sql = "SELECT z.*, u.username
	FROM " . ZEBRA_TABLE . " z, " . USERS_TABLE . " u
	WHERE z.user_id = '" . $userdata['user_id'] . "'
		AND " . $sql_and . "
		AND u.user_id = z.zebra_id
	ORDER BY u.username ASC";
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not query ZEBRA table', $lang['Error'], __LINE__, __FILE__, $sql);
}

$username_count = 0;
$s_username_options = '';
while ($row = $db->sql_fetchrow($result))
{
	$s_username_options .= '<option value="' . $row['zebra_id'] . '">' . $row['username'] . '</option>';
	$username_count++;
}
$db->sql_freeresult($result);

include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'profile_friends_mng_body.tpl'));

if ($username_count > 0)
{
	$template->assign_block_vars('friends', array());
}
else
{
	$template->assign_block_vars('no_friends', array());
}

$template->assign_vars(array(
	'L_TITLE' => $lang['UCP_ZEBRA'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'L_SELECT' => $lang['Select'],
	'L_REMOVE_SELECTED' => $lang['Remove_selected'],
	'L_ADD_MEMBER' => $lang['Add_member'],
	'L_FIND_USERNAME' => $lang['Find_username'],

	'L_ADD_FOES' => $lang['ADD_FOES'],
	'L_ADD_FOES_EXPLAIN' => $lang['ADD_FOES_EXPLAIN'],
	'L_FOES' => $lang['FOES'],
	'L_FOES_EXPLAIN' => $lang['FOES_EXPLAIN'],
	'L_YOUR_FOES' => $lang['YOUR_FOES'],
	'L_YOUR_FOES_EXPLAIN' => $lang['YOUR_FOES_EXPLAIN'],
	'L_NO_FOES' => $lang['NO_FOES'],
	'L_ADD_FRIENDS' => $lang['ADD_FRIENDS'],
	'L_ADD_FRIENDS_EXPLAIN' => $lang['ADD_FRIENDS_EXPLAIN'],
	'L_FRIENDS' => $lang['FRIENDS'],
	'L_FRIENDS_EXPLAIN' => $lang['FRIENDS_EXPLAIN'],
	'L_YOUR_FRIENDS' => $lang['YOUR_FRIENDS'],
	'L_YOUR_FRIENDS_EXPLAIN' => $lang['YOUR_FRIENDS_EXPLAIN'],
	'L_NO_FRIENDS' => $lang['NO_FRIENDS'],

	'U_SEARCH_USER' => append_sid(SEARCH_MG . '?mode=searchuser'),
	'S_USERNAME_OPTIONS' => $s_username_options,
	'S_PROFILE_ACTION' => append_sid(PROFILE_MG . '?mode=zebra&amp;zmode=' . $zmode),
	'S_HIDDEN_FIELDS' => ''
	)
);

$template->pparse('body');
include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>