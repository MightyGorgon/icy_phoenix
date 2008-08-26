<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

function query_ranks()
{
	global $db;

	$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . "
		ORDER BY ban_userid ASC";
	if ( !($result = $db->sql_query($sql, false, 'ban_')) )
	{
		message_die(GENERAL_ERROR, "Could not obtain banned users information.", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result) )
	{
		$ranks_sql['bannedrow'][] = $row;
	}
	//$ranks_sql['bannedrow'][] = $db->sql->fetchrowset($result);
	$db->sql_freeresult($result);

	$sql = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_special ASC, rank_min ASC";
	if ( !($result = $db->sql_query($sql, false, 'ranks_')) )
	{
		message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql);
	}

	while ($row = $db->sql_fetchrow($result) )
	{
		$ranks_sql['ranksrow'][] = $row;
	}
	//$ranks_sql['ranksrow'][] = $db->sql->fetchrowset($result);
	$db->sql_freeresult($result);

	return $ranks_sql;
}


function generate_ranks($user_row, $ranks_sql)
{
	$user_fields_array = array(
		'user_rank',
		'user_rank2',
		'user_rank3',
		'user_rank4',
		'user_rank5'
	);
	$user_ranks_array = array(
		'rank_01', 'rank_01_img',
		'rank_02', 'rank_02_img',
		'rank_03', 'rank_03_img',
		'rank_04', 'rank_04_img',
		'rank_05', 'rank_05_img',
	);
	$user_ranks = array();

	$is_banned = false;
	$is_guest = false;
	$rank_sw = false;

	for($j = 0; $j < count($user_ranks_array); $j++)
	{
		$user_ranks[$user_ranks_array[$j]] = '';
	}

	if ( $user_row['user_id'] == ANONYMOUS )
	{
		$is_guest = true;
	}

	if ($is_guest == false)
	{
		for($j = 0; $j < count($ranks_sql['bannedrow']); $j++)
		{
			if ( $ranks_sql['bannedrow'][$j]['ban_userid'] == $user_row['user_id'] )
			{
				$is_banned = true;
				break;
			}
		}
	}

	for($j = 0; $j < count($ranks_sql['ranksrow']); $j++)
	{
		$rank_tmp = $ranks_sql['ranksrow'][$j]['rank_title'];
		$rank_img_tmp = ( $ranks_sql['ranksrow'][$j]['rank_image'] ) ? '<img src="' . $ranks_sql['ranksrow'][$j]['rank_image'] . '" alt="' . $ranks_tmp . '" title="' . $ranks_tmp . '" />' : '';
		if ($is_guest == true)
		{
			if ($ranks_sql['ranksrow'][$j]['rank_special'] == '2')
			{
				$user_ranks['rank_01'] = $rank_tmp;
				$user_ranks['rank_01_img'] = $rank_img_tmp;
			}
		}
		elseif ($is_banned == true)
		{
			if ($ranks_sql['ranksrow'][$j]['rank_special'] == '3')
			{
				$user_ranks['rank_01'] = $rank_tmp;
				$user_ranks['rank_01_img'] = $rank_img_tmp;
			}
		}
		else
		{
			$day_diff = intval( (time() - $user_row['user_regdate']) / 86400 );

			for($k = 0; $k < count($user_fields_array); $k++)
			{
				switch ($ranks_sql['ranksrow'][$j]['rank_special'])
				{
					case '1':
						if ($user_row[$user_fields_array[$k]] == $ranks_sql['ranksrow'][$j]['rank_id'])
						{
							$rank_sw = true;
						}
						break;
					case '0':
						if (($user_row[$user_fields_array[$k]] == '0') && ($user_row['user_posts'] >= $ranks_sql['ranksrow'][$j]['rank_min']))
						{
							$rank_sw = true;
						}
						break;
					case '-1':
						if (($user_row[$user_fields_array[$k]] == '-1') && ($day_diff >= $ranks_sql['ranksrow'][$j]['rank_min']))
						{
							$rank_sw = true;
						}
						break;
					default:
						break;
				}

				if ($rank_sw == true)
				{
					$user_ranks[$user_ranks_array[(($k + 1) * 2) - 2]] = $rank_tmp;
					$user_ranks[$user_ranks_array[(($k + 1) * 2) - 1]] = $rank_img_tmp;
					$rank_sw = false;
				}
			}

		}
	}

	return $user_ranks;
}

?>