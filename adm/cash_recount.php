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
* @Extra credits for this file
* Xore (mods@xore.ca)
*
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
define('IN_CASHMOD', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if (isset($_POST['cancel']) || empty($_POST['confirm']))
{
	message_die(GENERAL_MESSAGE, '<br />' . sprintf($lang['Click_return_cash_reset'], '<a href="' . append_sid('cash_reset.' . PHP_EXT) . '">', '</a>') . '<br /><br />');
}

if (isset($_POST['cids']))
{
	$cids = explode(",",$_POST['cids']);
	$cash_check = array();
	for ($i = 0; $i < sizeof($cids);$i++)
	{
		$cash_check[$cids[$i]] = 1;
	}
	$old_user_abort = ignore_user_abort(true);

	$sql = "SELECT user_id, username, user_level, user_posts
			FROM " . USERS_TABLE;
	$result = $db->sql_query($sql);

	$userlist = array();
	$max_user = -1;
	while ($row = $db->sql_fetchrow($result))
	{
		$userlist[] = $row;
		if ($max_user < intval($row['user_id']))
		{
			$max_user = intval($row['user_id']);
		}
	}

	define('CASH_POSTS',0);
	define('CASH_BONUS',1);
	define('CASH_REPLIES',2);
	define('FLUSH','                                                                                                                                                                                                                                                                ');
	$cm_groups->load(true, true);
	$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value) VALUES ('cash_resetting','-1,$max_user')";
	$db->sql_query($sql);

	for ($i = 0; $i < sizeof($userlist); $i++)
	{
		if ($userlist['user_id'] != ANONYMOUS)
		{
			$c_user = new cash_user($userlist[$i]['user_id'],$userlist[$i]);
			$sql = "SELECT t.forum_id, t.topic_id, t.topic_poster, t.topic_replies, count(p.post_id) AS user_replies
					FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
					WHERE t.topic_id = p.topic_id AND p.poster_id = " . $c_user->id() . "
					GROUP BY t.topic_id";
			$result = $db->sql_query($sql);

			$forums = array();
			$forum_list = array(CASH_POSTS => 0, CASH_BONUS => 0, CASH_REPLIES => 0);
			//
			// $forums is an array of arrays. the first index is the forum_id. the remainder are a count of
			//  [0] => topics started
			//  [1] => bonus earned on those topics
			//  [2] => replies
			//
			while ($row = $db->sql_fetchrow($result))
			{
				$forum_id = intval($row['forum_id']);
				$topic_poster = intval($row['topic_poster']);
				$topic_replies = intval($row['topic_replies']);
				$user_replies = intval($row['user_replies']);
				if (empty($forums[$forum_id]))
				{
					$forum_list[] = $forum_id;
					$forums[$forum_id] = array();
				}
				if ($topic_poster == $c_user->id())
				{
					$forums[$forum_id][CASH_POSTS] += 1;
					$user_replies -= 1;
					$topic_replies -= $user_replies;
					$forums[$forum_id][CASH_BONUS] += $topic_replies;
					$forums[$forum_id][CASH_REPLIES] += $user_replies;
				}
				else
				{
					$forums[$forum_id][CASH_REPLIES] += $user_replies;
				}
			}
			$cash_amount = array();
			while ($c_cur = &$cash->currency_next($cm_i))
			{
				if (isset($cash_check[$c_cur->id()]))
				{
					$cash_counts = array(CASH_POSTS => 0,CASH_BONUS => 0,CASH_REPLIES => 0);
					$cash_amount[$c_cur->id()] = $c_cur->data('cash_default');
					for ($j = 0; $j < sizeof($forum_list); $j++)
					{
						$forum_id = $forum_list[$j];
						if ($c_cur->forum_active($forum_id))
						{
							$cash_counts[CASH_POSTS] += $forums[$forum_id][CASH_POSTS];
							$cash_counts[CASH_BONUS] += $forums[$forum_id][CASH_BONUS];
							$cash_counts[CASH_REPLIES] += $forums[$forum_id][CASH_REPLIES];
						}
					}
					$cash_amount[$c_cur->id()] += ($c_user->get_setting($c_cur->id(),'cash_perpost') * $cash_counts[CASH_POSTS]);
					$cash_amount[$c_cur->id()] += ($c_user->get_setting($c_cur->id(),'cash_postbonus') * $cash_counts[CASH_BONUS]);
					$cash_amount[$c_cur->id()] += ($c_user->get_setting($c_cur->id(),'cash_perreply') * $cash_counts[CASH_REPLIES]);
				}
			}
			$c_user->set_by_id_array($cash_amount);
			$config_update = $c_user->id() . "," . $max_user;
			$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '$config_update' WHERE config_name = 'cash_resetting'";
			$db->sql_query($sql);

			print(sprintf($lang['User_updated'],$c_user->name()).FLUSH);
			flush();
		}
	}
	$sql = "DELETE FROM " . CONFIG_TABLE . "  WHERE config_name = 'cash_resetting'";
	$db->sql_query($sql);

	ignore_user_abort($old_user_abort);
	message_die(GENERAL_MESSAGE, '<br />' . sprintf($lang['Click_return_cash_reset'], '<a href="' . append_sid('cash_reset.' . PHP_EXT) . '">', '</a>') . '<br /><br />');
}

?>