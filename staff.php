<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_groups.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$page_title = $lang['Staff'];
$meta_description = '';
$meta_keywords = '';
include('includes/page_header.' . PHP_EXT);

$template->set_filenames(array('body' => 'staff_body.tpl'));

$is_auth_ary = array();
$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_data);

$sql_forums = "SELECT ug.user_id, f.forum_id, f.forum_name
		FROM ". AUTH_ACCESS_TABLE ." aa, ". USER_GROUP_TABLE ." ug, ". FORUMS_TABLE ." f
		WHERE aa.auth_mod = ". TRUE . "
			AND ug.group_id = aa.group_id
			AND f.forum_id = aa.forum_id";
if( !$result_forums = $db->sql_query($sql_forums) )
{
	message_die(GENERAL_ERROR, 'Could not query forums.', '', __LINE__, __FILE__, $sql_forums);
}
while( $row = $db->sql_fetchrow($result_forums) )
{
	$display_forums = ( $is_auth_ary[$row['forum_id']]['auth_view'] ) ? true : false;
	if( $display_forums )
	{
		$forum_id = $row['forum_id'];
		$staff2[$row['user_id']][$row['forum_id']] = '<a href="'. append_sid(VIEWFORUM_MG . '?' . POST_FORUM_URL . '=' . $forum_id) . '" class="genmed">'. $row['forum_name'] .'</a><br />';
	}
}

$sql_ranks = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_special ASC, rank_min ASC";
if( !($results_ranks = $db->sql_query($sql_ranks, false, 'ranks_')) )
{
	message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql_ranks);
}
$ranksrow = array();
while( $row = $db->sql_fetchrow($results_ranks) )
{
	$ranksrow[] = $row;
}
$db->sql_freeresult($result);

$level_cat = $lang['Staff_level'];
for($i = 0; $i < count($level_cat); $i++)
{
	$user_level = $level_cat[$i];
	$template->assign_block_vars('user_level', array(
		'USER_LEVEL' => $user_level,
		)
	);

	if($level_cat['0'])
	{
		$where = 'user_level IN ('. JUNIOR_ADMIN . ', ' . ADMIN . ')';
	}
	elseif($level_cat['1'])
	{
		$where = 'user_level = '. MOD;
	}
	$level_cat[$i] = '';

	$sql_user = "SELECT * FROM ". USERS_TABLE ." WHERE $where";
	if( !($result_user = $db->sql_query($sql_user)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user information.', '', __LINE__, __FILE__, $sql_user);
	}
	if( $staff = $db->sql_fetchrow($result_user) )
	{
		$k = 0;
		do
		{
			$row_class = ( !($k % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			$user_id = $staff['user_id'];

			$rank = '';
			$rank_image = '';
			if( $staff['user_rank'] )
			{
				for( $j = 0; $j < count($ranksrow); $j++ )
				{
					if( $staff['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special'] )
					{
						$rank = $ranksrow[$j]['rank_title'];
						$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="'. $ranksrow[$j]['rank_image'] .'" alt="'. $rank .'" title="'. $rank .'" />' : '';
					}
				}
			}
			else
			{
				for( $j = 0; $j < count($ranksrow); $j++ )
				{
					if( $staff['user_posts'] >= $ranksrow[$j]['rank_min'] && !$ranksrow[$j]['rank_special'] )
					{
						$rank = $ranksrow[$j]['rank_title'];
						$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="'. $ranksrow[$j]['rank_image'] .'" alt="'. $rank .'" title="'. $rank .'" />' : '';
					}
				}
			}

			$avatar = user_get_avatar($staff['user_id'], $staff['user_avatar'], $staff['user_avatar_type'], $staff['user_allowavatar']);

			$forums = '';
			if( !empty($staff2[$staff['user_id']]) )
			{
				asort($staff2[$staff['user_id']]);
				$forums = implode(' ',$staff2[$staff['user_id']]);
			}

			$sql_posts = "SELECT DISTINCT p.post_time, p.post_id, count(DISTINCT t.topic_id) AS user_topics
					FROM ". POSTS_TABLE ." p, ". TOPICS_TABLE ." t
					WHERE p.poster_id = '$user_id' AND t.topic_poster = '$user_id'
					GROUP BY p.post_time
					ORDER BY p.post_time DESC LIMIT 1";
			if( !($results_posts = $db->sql_query($sql_posts)) )
			{
				message_die(GENERAL_ERROR, 'Error getting user last post.', '', __LINE__, __FILE__, $sql_posts);
			}
			$row = $db->sql_fetchrow($results_posts);
			$last_post = ( isset($row['post_time']) ) ? '<a href="' . append_sid(VIEWTOPIC_MG . '?'. POST_POST_URL . '=' . $row[post_id] . '#p' . $row[post_id]) . '"  style:text-decoration:none>' . create_date2($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']) .'</a>' : $lang['None'];
			$user_topics = $row['user_topics'];

			$memberdays = max(1, round( ( time() - $staff['user_regdate'] ) / 86400 ));
			$posts_per_day = $staff['user_posts'] / $memberdays;
			$topics_per_day = $user_topics / $memberdays;
			if( $staff['user_posts'] != '0' )
			{
				$total_topics = $board_config['max_topics'];
				$total_posts = $board_config['max_posts'];
				$post_percent = ( $total_posts ) ? min(100, ($staff['user_posts'] / $total_posts) * 100) : 0;
				$topic_percent = ( $total_topics ) ? min(100, ($user_topics / $total_topics) * 100) : 0;
			}
			else
			{
				$post_percent = 0;
				$topic_percent = 0;
			}

			$pmto = append_sid('privmsg.' . PHP_EXT . '?mode=post&amp;' . POST_USERS_URL . '=' . $staff[user_id]);
			$pm = '<a href="' . $pmto . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
			$mailto = ( $board_config['board_email_form'] ) ? append_sid(PROFILE_MG . '?mode=email&amp;' . POST_USERS_URL .'=' . $staff['user_id']) : 'mailto:' . $staff['user_email'];
			$mail = ( $staff['user_email'] ) ? '<a href="' . $mailto . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>' : '';

			$msn = ( $staff['user_msnm'] ) ? '<a href="mailto: '.$staff['user_msnm'].'"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" /></a>' : '';
			$yim = ( $staff['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $staff['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" /></a>' : '';
			$aim = ( $staff['user_aim'] ) ? '<a href="aim:goim?screenname=' . $staff['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>' : '';
			$icq = ( $staff['user_icq'] ) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto=' . $staff['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" /></a>' : '';
			$skype_img = ( $staff['user_skype'] ) ? '<a href="callto://' . $staff['user_skype'] . '/"><img src="' . $images['icon_skype'] . '" alt="' . $lang['SKYPE'] . '" title="' . $lang['SKYPE'] . '" /></a>' : '';
			$skype = ( $staff['user_skype'] ) ? '<a href="callto://' . $staff['user_skype'] . '/">' . $lang['SKYPE'] . '</a>' : '';

			$www = ( $staff['user_website'] ) ? '<a href="' . $staff['user_website'] . '" target="_blank"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';

			$template->assign_block_vars('user_level.staff', array(
				'ROW_CLASS' => $row_class,
				'USERNAME' => colorize_username($staff['user_id']),
				'RANK' => $rank,
				'RANK_IMAGE' => $rank_image,
				'AVATAR' => $avatar,
				'FORUMS' => $forums,
				'POSTS' => $staff['user_posts'],
				'POST_PERCENT' => sprintf($lang['User_post_pct_stats'], $post_percent),
				'POSTS_PER_DAY' => sprintf($lang['User_post_day_stats'], $posts_per_day),
				'TOPICS' => $user_topics,
				'TOPIC_PERCENT' => sprintf($lang['User_post_pct_stats'], $topic_percent),
				'TOPICS_PER_DAY' => sprintf($lang['Staff_user_topic_day_stats'], $topics_per_day),
				'LAST_POST' => $last_post,
				'JOINED' => create_date($lang['JOINED_DATE_FORMAT'], $staff['user_regdate'], $board_config['board_timezone']),
				'PERIOD' => sprintf($lang['Staff_period'], $memberdays),
				'PM' => $pm,
				'EMAIL' => $mail,
				'MSN' => $msn,
				'YIM' => $yim,
				'AIM' => $aim,
				'ICQ' => $icq,
				'WWW' => $www,
				'SKYPE_IMG' => $skype_img,
				'SKYPE' => $skype,
				)
			);
			$k++;
		}
		while( $staff = $db->sql_fetchrow($result_user) );
	}
}

$template->assign_vars(array(
	'L_USERNAME' => $lang['Username'],
	'L_FORUMS' => $lang['Staff_forums'],
	'L_STATS' => $lang['Staff_stats'],
	'L_POSTS' => $lang['Posts'],
	'L_TOPICS' => $lang['Topics'],
	'L_LAST_POST' => $lang['Last_Post'],
	'L_JOINED' => $lang['Joined'],
	'L_CONTACT' => $lang['Staff_contact'],
	'L_MESSENGER' => $lang['Staff_messenger'],
	'L_WWW' => $lang['Website'],
));

$template->pparse('body');
include('includes/page_tail.' . PHP_EXT);

?>