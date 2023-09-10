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
* aUsTiN-Inc - (austin_inc@hotmail.com) - (phpbb-amod.com)
*
*/

if (!defined('IN_ICYPHOENIX')) define('IN_ICYPHOENIX', true);

if(!empty($setmodules))
{
	$module['1200_Forums']['250_FTR_Config'] = append_sid('admin_force_read.' . PHP_EXT . '?mode=config');
	$module['1200_Forums']['260_FTR_Users'] = append_sid('admin_force_read.' . PHP_EXT . '?mode=users');
	return;
}

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$mode = request_var('mode', '');
if(isset($_POST['config']))
{
	$mode = 'config';
}
elseif(isset($_POST['logs']))
{
	$mode = 'users';
}

setup_extra_lang(array('lang_ftr'));


$topic_id = (int) $config['ftr_topic_number'];
$topic_exists = check_topic_exists($topic_id);
if (empty($topic_exists))
{
	set_config('ftr_disable', '1');
	set_config('ftr_topic_number', '0');
}

$update = $_POST['update'];

if ($mode == 'delete_user')
{
	$who = request_var('user', 0);
	$q = "DELETE FROM ". FORCE_READ_USERS_TABLE . " WHERE user = '" . $who . "'";
	$r = $db->sql_query($q);
	message_die(GENERAL_MESSAGE, $lang['Ftr_user_deleted'], $lang['Ftr_msg_success']);
}

if($mode == 'users')
{
	$start = request_var('start', 0);
	$start = ($start < 0) ? 0 : $start;
	$show = $config['topics_per_page'];

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
	echo "	<tr>";
	echo "		<th colspan=\"2\">";
	echo "			" . $lang['Ftr_admin_users'];
	echo "		</th>";
	echo "	</tr>";
	echo "</table>";
	echo '<br /><br />';

	$sql = "SELECT COUNT(user) AS total FROM ". FORCE_READ_USERS_TABLE;
	$result = $db->sql_query($sql);

	if($total = $db->sql_fetchrow($result))
	{
		$total_users = $total['total'];
		$pagination = generate_pagination('admin_force_read.' . PHP_EXT . '?mode=users', $total_users, $show, $start) . '&nbsp;';
	}
	else
	{
		$pagination = '&nbsp;';
		$total_users = $show;
	}

	$page_number = sprintf($lang['Page_of'], (floor($start / $show) + 1), ceil($total_users / $show));

	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"top\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
	echo "			<span class=\"genmed\">";
	echo "				$page_number";
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
	echo "			<span class=\"genmed\">";
	echo "				$pagination";
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";
	echo "</table>";
	echo '<br /><br />';
	echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
	echo "	<tr>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
	echo "			<span class=\"genmed\">";
	echo "				" . $lang['Ftr_username'];
	echo "			</span>";
	echo "		</td>";
	echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
	echo "			<span class=\"genmed\">";
	echo "				" . $lang['Ftr_post_date_time'];
	echo "			</span>";
	echo "		</td>";
	echo "	</tr>";

	$q1 = "SELECT *
		FROM " . FORCE_READ_USERS_TABLE . "
		GROUP BY user
		ORDER BY time ASC
		LIMIT $start, $show";
	$r1 = $db->sql_query($q1);
	while($row1 = $db->sql_fetchrow($r1))
	{
		$target_user = $row1['user'];
		$time = $row1['time'];
		$time = strftime("%b. %d, %Y @ %H:%M:%S", $time);

		$q = "SELECT username, user_color, user_active
			FROM ". USERS_TABLE ."
			WHERE user_id = " . $target_user;
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		$name = $row['username'];

		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				" . colorize_username($target_user, $name, $row['user_color'], $row['user_active']) . "&nbsp;[<a href=\"" . append_sid($_SERVER['SCRIPT_NAME'] . "?mode=delete_user&amp;user=" . $target_user) ."\">" . $lang['Delete'] . "</a>]";
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				$time";
		echo "			</span>";
		echo "		</td>";
		echo "	</tr>";
	}
	echo "</table>";
}
elseif($mode == 'config')
{
	if ($update == 'new_effected')
	{
		$new = $_POST['effected'];
		$new = (!$new) ? '0' : $new;
		set_config('ftr_all_users', $new);
		$msg = (!$new) ? $lang['Ftr_effected_1'] : $lang['Ftr_effected_2'];
		message_die(GENERAL_MESSAGE, $msg, $lang['Ftr_msg_success']);
	}

	if ($update == 'activate')
	{
		$ftr_disable = !empty($_POST['deactivate']) ? '1' : '0';
		set_config('ftr_disable', $ftr_disable);
		$msg = ($ftr_disable) ? $lang['Ftr_active_1'] : $lang['Ftr_active_2'];
		message_die(GENERAL_MESSAGE, $msg, $lang['Ftr_msg_success']);
	}

	if($update == 'delete_users')
	{
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<th colspan=\"2\">";
		echo "			". $lang['Ftr_admin_user_delete'];
		echo "		</th>";
		echo "	</tr>";
		echo "</table>";
		echo '<br /><br />';

		$q1 = "TRUNCATE " . FORCE_READ_USERS_TABLE;
		$r1 = $db->sql_query($q1);
		message_die(GENERAL_MESSAGE, $lang['Ftr_user_del_success'], $lang['Ftr_msg_success']);
	}
	elseif($update == 'save_config')
	{
		$topic_id = (int) $_POST['topic'];
		$msg = $_POST['message'];

		echo "<table class=\"forumline\" width=\"100%\" valign=\"middle\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
		echo "	<tr>";
		echo "		<th colspan=\"2\">";
		echo "			". $lang['Ftr_save_config'];
		echo "		</th>";
		echo "	</tr>";
		echo "</table>";
		echo '<br /><br />';

		set_config('ftr_topic_number', $topic_id, false);
		set_config('ftr_message', $msg);
		message_die(GENERAL_MESSAGE, $lang['Ftr_save_config_success'], $lang['Ftr_msg_success']);
	}
	elseif($update == 'change_config')
	{
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<th colspan=\"2\">";
		echo "			". $lang['Ftr_select_forum'];
		echo "		</th>";
		echo "	</tr>";
		echo "</table>";
		echo '<br /><br />';
		$change_config_2 = append_sid('admin_force_read.' . PHP_EXT . '?mode=config');
		echo "<form name=\"change_settings_2\" method=\"post\" action=\"$change_config_2\">";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				". $lang['Ftr_forum_choose'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				<select size=\"1\" name=\"change_config_2\">";
		echo "					<option selected value=\"\">". $lang['Ftr_default'] ."</option>";

		$q1 = "SELECT forum_id, forum_name FROM " . FORUMS_TABLE . " WHERE forum_type = " . FORUM_POST;
		$r1 = $db->sql_query($q1);
		while($row1 = $db->sql_fetchrow($r1))
		{
			$id = $row1['forum_id'];
			$name = $row1['forum_name'];
			echo "					<option value=\"$id\" name=\"change_config_2\">$name</option>";
		}
		echo "				</select>";
		echo "			</span>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "<br />";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"middle\">";
		echo "			<input type=\"hidden\" name=\"update\" value=\"change_config_2\" />";
		echo "			<input type=\"submit\" class=\"mainoption\" value=" . $lang['Ftr_select_button'] . " onchange=\"document.change_settings_2.submit()\" />";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";
	}
	elseif($update == "change_config_2")
	{
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<th colspan=\"2\">";
		echo "			". $lang['Ftr_set_config'];
		echo "		</th>";
		echo "	</tr>";
		echo "</table>";
		echo '<br /><br />';
		$forum_to_use = $_POST['change_config_2'];
		$save_config = append_sid('admin_force_read.' . PHP_EXT . '?mode=config');
		echo "<form name=\"save\" method=\"post\" action=\"$save_config\">";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				". $lang['Ftr_topic_choose'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				<select size=\"1\" name=\"topic\">";
		echo "					<option selected value=\"\">". $lang['Ftr_default2'] ."</option>";

		$q1 = "SELECT topic_id, topic_title
			FROM ". TOPICS_TABLE ."
			WHERE forum_id = '$forum_to_use'";
		$r1 = $db->sql_query($q1);
		while($row1 = $db->sql_fetchrow($r1))
		{
			$id = $row1['topic_id'];
			$name = $row1['topic_title'];
			echo "					<option value=\"$id\" name=\"topic\">$name</option>";
		}
		echo "				</select>";
		echo "			</span>";
		echo "		</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				" . $lang['Ftr_message'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
		echo "			<textarea id=\"message\" name=\"message\" cols=\"50\" rows=\"5\">" . $config['ftr_message'] . "</textarea>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "<br />";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"middle\">";
		echo "			<input type=\"hidden\" name=\"update\" value=\"save_config\" />";
		echo "			<input type=\"submit\" class=\"mainoption\" value=". $lang['Ftr_save_button'] ." onchange=\"document.save.submit()\" />";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";
	}
	else
	{
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<th colspan=\"2\">";
		echo "			". $lang['Ftr_config'];
		echo "		</th>";
		echo "	</tr>";
		echo "</table>";
		echo '<br /><br />';

		$topic = (int) $config['ftr_topic_number'];
		$topic_name = '<i>' . $lang['Ftr_default2'] . '</i>';
		$msg = $config['ftr_message'];
		$active = $config['ftr_disable'] ? '0' : '1';
		$effected = $config['ftr_all_users'];

		if (!empty($topic))
		{
			$q1 = "SELECT topic_title
				FROM " . TOPICS_TABLE . "
				WHERE topic_id = " . $topic;
			$r1 = $db->sql_query($q1);
			$row1 = $db->sql_fetchrow($r1);
			if (!empty($row1['topic_title']))
			{
				$topic_name = $row1['topic_title'];
			}
		}

		$delete = append_sid('admin_force_read.' . PHP_EXT . '?mode=config');
		echo "<form name=\"delete_u\" method=\"post\" action=\"$delete\">";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				". $lang['Ftr_post_changed'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
		echo "			<input type=\"hidden\" name=\"update\" value=\"delete_users\" />";
		echo "			<input type=\"submit\" class=\"mainoption\" value=" . $lang['Ftr_delete_button'] . " onchange=\"document.delete_u.submit()\" />";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";
		echo "<br />";
		$change_config = append_sid('admin_force_read.' . PHP_EXT . '?mode=config');
		echo "<form name=\"change_settings\" method=\"post\" action=\"$change_config\">";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				". $lang['Ftr_current_topic'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				$topic_name";
		echo "			</span>";
		echo "		</td>";
		echo "	</tr>";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				". $lang['Ftr_current_message'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				$msg";
		echo "			</span>";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "<br />";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"middle\">";
		echo "			<input type=\"hidden\" name=\"update\" value=\"change_config\" />";
		echo "			<input type=\"submit\" class=\"mainoption\" value=\"" . $lang['Ftr_change_button'] . "\" onchange=\"document.change_settings.submit()\" />";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";

		$on = ($active == '1') ? 'checked="checked"' : '';
		$off = ($active == '1') ? '' : 'checked="checked"';

		echo "<form name=\"change_active\" method=\"post\" action=\"$change_config\">";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				". $lang['Ftr_deactivate'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
		echo "			<input type=\"radio\" value=\"1\" name=\"deactivate\" $off />&nbsp;". $lang['Ftr_deactivate_y'];
		echo "			<input type=\"radio\" value=\"0\" name=\"deactivate\" $on />&nbsp;". $lang['Ftr_deactivate_n'];
		echo "		</td>";
		echo "		<td class=\"row2 row-center\" width=\"100%\" valign=\"middle\">";
		echo "			<input type=\"hidden\" name=\"update\" value=\"activate\" />";
		echo "			<input type=\"submit\" class=\"mainoption\" value=\"" . $lang['Ftr_change_button'] . "\" onchange=\"document.change_active.submit()\" />";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";

		$on = ($effected == '1') ? 'checked="checked"' : '';
		$off = ($effected == '1') ? '' : 'checked="checked"';

		echo "<form name=\"change_effected\" method=\"post\" action=\"$change_config\">";
		echo "<table class=\"forumline\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" valign=\"middle\">";
		echo "	<tr>";
		echo "		<td class=\"row2\" width=\"50%\" valign=\"middle\">";
		echo "			<span class=\"genmed\">";
		echo "				". $lang['Ftr_whos_effected'];
		echo "			</span>";
		echo "		</td>";
		echo "		<td class=\"row2 row-center\" width=\"50%\" valign=\"middle\">";
		echo "			<input type=\"radio\" value=\"0\" name=\"effected\" $off />&nbsp;". $lang['Ftr_whos_effected_n'];
		echo "			<input type=\"radio\" value=\"1\" name=\"effected\" $on />&nbsp;". $lang['Ftr_whos_effected_a'];
		echo "		</td>";
		echo "		<td class=\"row2\" width=\"100%\" valign=\"middle\">";
		echo "			<input type=\"hidden\" name=\"update\" value=\"new_effected\" />";
		echo "			<input type=\"submit\" class=\"mainoption\" value=\"" . $lang['Ftr_change_button'] . "\" onchange=\"document.change_effected.submit()\" />";
		echo "		</td>";
		echo "	</tr>";
		echo "</table>";
		echo "</form>";
	}
}

/*
If this is removed, dont bother asking for any assistance from me.
Give credit where credit is due.
*/
echo "<table>";
echo "	<tr>";
echo "		<td class=\"talignc\">";
echo "			<span class=\"gen\">";
echo "				<a href=\"http://phpbb-amod.com/\" target=\"_blank\">";
echo "					<font class=\"gensmall\">";
echo "							&copy; aUsTiN-Inc";
echo "					</font>";
echo "				</a>";
echo "			</span>";
echo "		</td>";
echo "	</tr>";
echo "</table>";


/**
* Check if a topic exists
*/
function check_topic_exists($topic_id)
{
	global $db, $cache;

	$q1 = "SELECT topic_id
		FROM ". TOPICS_TABLE ."
		WHERE topic_id = " . (int) $topic_id;
	$r1 = $db->sql_query($q1);
	$row1 = $db->sql_fetchrow($r1);
	$db->sql_freeresult($r1);

	return (!empty($row1['topic_id']) ? $row1['topic_id'] : 0);
}

include('page_footer_admin.' . PHP_EXT);
?>