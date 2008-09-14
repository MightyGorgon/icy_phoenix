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
* Majorflam - (majorflam@majormod.com) - (http://majormod.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

//
// constants
//
define( EASY, 1 );
define( MEDIUM, 2 );
define( HARD, 3 );
define( VERY_HARD, 4 );

//
// begin functions
//

//
// Grab relevant starting data
//
function sudoku_starting_data($game_pack, $game_num, $db_table, $and_clause)
{
	global $db, $line, $lrow;
	$sql=" SELECT * FROM " . $db_table . "
	WHERE game_pack=$game_pack
	AND game_num=$game_num
	$and_clause
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error in retrieving solutions', '', __LINE__, __FILE__, $sql);
	}
	$line=array();
	while ( $row=$db->sql_fetchrow($result) )
	{
		$lrow=$row;
		$line[]=explode('a', $row['line_1']);
		$line[]=explode('a', $row['line_2']);
		$line[]=explode('a', $row['line_3']);
		$line[]=explode('a', $row['line_4']);
		$line[]=explode('a', $row['line_5']);
		$line[]=explode('a', $row['line_6']);
		$line[]=explode('a', $row['line_7']);
		$line[]=explode('a', $row['line_8']);
		$line[]=explode('a', $row['line_9']);
	}
	return;
}
//
// end starting data
//

//
// begin grid build
//
function sudoku_grid_build()
{
	global $template, $images, $lang, $line, $pack, $num, $level, $mode, $co_ord, $input_box;
	//
	// build lines for template
	//
	$tile=array();
	for ( $x=0; $x<9; $x++ )
	{
		$line_key=$x+1;
		for ( $i=0; $i<9; $i++ )
		{
			$key=$i+1;
			$tile_key=$line_key . '_' . $key;
			$tile[$tile_key]=$line[$x][$i];
			$tile_image='sudoku_' . $tile[$tile_key];
			if ( !$mode )
			{
				if ( $tile[$tile_key] == 'x' || ( $tile[$tile_key] > 9 && $tile[$tile_key] < 20 ) )
				{
					$tile_text=( $tile[$tile_key] == 'x' ) ? $lang['suduko_blank_tile'] : $lang['suduko_user_tile'];
					$tile_url=( $tile[$tile_key] == 'x' ) ? append_sid('sudoku.' . PHP_EXT . '?mode=insert&amp;tile=' . $line_key . '_' . $key . '&amp;p=' . $pack . '&amp;n=' . $num . '&amp;l=' . $level . '#grid') : append_sid('sudoku.' . PHP_EXT . '?mode=edit&amp;tile=' . $line_key . '_' . $key . '&amp;p=' . $pack . '&amp;n=' . $num . '&amp;l=' . $level . '&amp;val=' . ($tile[$tile_key]-10) . '#grid');
					$on_click = ($tile[$tile_key] == 'x') ? 'sudoku(\'' . append_sid('sudoku.' . PHP_EXT . '?tile=' . $line_key . '_' . $key . '&amp;p=' . $pack . '&amp;n=' . $num . '&amp;type=insert#grid') . "','','?')" : 'sudoku(\'' . append_sid('sudoku.' . PHP_EXT . '?tile=' . $line_key . '_' . $key . '&amp;p=' . $pack . '&amp;n=' . $num . '&amp;type=edit#grid') . "','" . ($tile[$tile_key]) . "','" . ($tile[$tile_key]-10) . "')";
					$tile_object='<a href="' . $tile_url . '" onClick="' . $on_click . '; return false;"><img src="' . IP_ROOT_PATH . $images[$tile_image] . '" alt="' . $tile_text . '" title="' . $tile_text . '" hspace="0" vspace="0" border="0"></a>';
				}
				else
				{
					$tile_object='<img src="' . IP_ROOT_PATH . $images[$tile_image] . '" hspace="0" vspace="0" border="0">';
				}
			}
			//else if ( $mode == 'insert' )
			//{
			//	$tile_object=( $line_key != $co_ord[0] || $key != $co_ord[1] ) ? '<img src="' . IP_ROOT_PATH . $images[$tile_image] . '" hspace="0" vspace="0" border="0">' : '<select name="num_input">' . $input_box;
			//}
			//else if ( $mode == 'edit' )
			//{
			//	$tile_object=( $line_key != $co_ord[0] || $key != $co_ord[1] ) ? '<img src="' . IP_ROOT_PATH . $images[$tile_image] . '" hspace="0" vspace="0" border="0">' : '<select name="num_input">' . $input_box;
			//}
			//
			// set the template var
			//
			$temp_pos='TILE' . $tile_key;
			$template->assign_vars(array(
			$temp_pos=>$tile_object,
			));
		}
	}
	return;
}
//
// end grid build
//

function sudoku_tasks($Sud_user_id, $pack, $num, $level)
{
	global $db, $games, $points, $s_users_today, $s_users_active, $alltime_played, $alltime_players;
	// clean repetitive instances of this users current game in the users table
	$sql=" SELECT count(user_id) AS entrys FROM " . SUDOKU_USERS . "
	WHERE game_pack=$pack
	AND game_num=$num
	AND user_id=$Sud_user_id
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error performing Sudoku Tasks', '', __LINE__, __FILE__, $sql);
	}
	$row=$db->sql_fetchrow($result);
	if ( $row['entrys'] > 1 )
	{
		$sql=" SELECT * FROM " . SUDOKU_USERS . "
		WHERE game_pack=$pack
		AND game_num=$num
		AND user_id=$Sud_user_id
		LIMIT 1
		";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error performing Sudoku Tasks', '', __LINE__, __FILE__, $sql);
		}
		$row=$db->sql_fetchrow($result);

		// delete all the instances
		$sql=" DELETE FROM " . SUDOKU_USERS . "
		WHERE game_pack=$pack
		AND game_num=$num
		AND user_id=$Sud_user_id
		";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error performing Sudoku Tasks', '', __LINE__, __FILE__, $sql);
		}
		// now insert one instance to replace.
		$sql=" INSERT INTO " . SUDOKU_USERS . "
		(user_id,game_pack,game_num,game_level,line_1,line_2,line_3,line_4,line_5,line_6,line_7,line_8,line_9)
		VALUES
		($Sud_user_id,$pack,$num,$level,'" . $row['line_1'] . "','" . $row['line_2'] . "','" . $row['line_3'] . "','" . $row['line_4'] . "','" . $row['line_5'] . "','" . $row['line_6'] . "','" . $row['line_7'] . "','" . $row['line_8'] . "','" . $row['line_9'] . "')
		";
		if (!$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error inserting Sudoku userdata to database', '', __LINE__, __FILE__, $sql);
		}
	}
	// end repitition clean up
	// update the stats for this user and clean past games if neccessary
	$sql=" SELECT * FROM " . SUDOKU_STATS . "
	WHERE user_id=$Sud_user_id
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error performing Sudoku Tasks', '', __LINE__, __FILE__, $sql);
	}
	$row=$db->sql_fetchrow($result);
	if ( !$row )
	{
		$s_games=0;
		$s_points=0;
		// do for first run stats
		$sql=" SELECT SUM(points) AS total_points FROM " . SUDOKU_USERS . "
		WHERE user_id=$Sud_user_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error in retrieving Sudoku userdata', '', __LINE__, __FILE__, $sql);
		}
		$row=$db->sql_fetchrow($result);
		$s_points=$row['total_points'];

		$sql=" SELECT COUNT(user_id) AS total_games FROM " . SUDOKU_USERS . "
		WHERE user_id=$Sud_user_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error in retrieving Sudoku userdata', '', __LINE__, __FILE__, $sql);
		}
		$row=$db->sql_fetchrow($result);
		$s_games=$row['total_games'];
		$s_games=$s_games-1;

		$sql=" INSERT INTO " . SUDOKU_STATS . "
		(user_id, played, points)
		VALUES
		($Sud_user_id, '$s_games','$s_points')
		";
		if (!$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error inserting Sudoku userdata to database', '', __LINE__, __FILE__, $sql);
		}
	}
	$games[$Sud_user_id]=$row['played'];
	$points[$Sud_user_id]=$row['points'];
	// now we must remove old games
	$sql=" DELETE FROM " . SUDOKU_USERS . "
	WHERE user_id=$Sud_user_id
	AND game_pack != $pack
	";
	if (!$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Error inserting Sudoku userdata to database', '', __LINE__, __FILE__, $sql);
	}

	$sql=" DELETE FROM " . SUDOKU_USERS . "
	WHERE user_id=$Sud_user_id
	AND game_num != $num
	";
	if (!$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Error inserting Sudoku userdata to database', '', __LINE__, __FILE__, $sql);
	}
	// do session tasks
	// update session for this user
	$time=time();
	$time_limit=$time-86400;
	$active_time=$time-300;
	$s_users_today=array();
	$s_users_active=array();
	$sql=" INSERT INTO " . SUDOKU_SESSIONS . "
	(user_id, session_time)
	VALUES
	($Sud_user_id, $time)
	";
	if (!$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Error updating Sudoku Sessions', '', __LINE__, __FILE__, $sql);
	}
	// delete previous data for this user
	$sql=" DELETE FROM " . SUDOKU_SESSIONS . "
	WHERE user_id=$Sud_user_id
	AND session_time != $time
	";
	if (!$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Error updating Sudoku Sessions', '', __LINE__, __FILE__, $sql);
	}
	// delete redundant data
	$sql=" DELETE FROM " . SUDOKU_SESSIONS . "
	WHERE session_time < $time_limit
	";
	if (!$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Error updating Sudoku Sessions', '', __LINE__, __FILE__, $sql);
	}

	// list users who have played in last 24 hours, and who is active in last 5 minutes
	$sql=" SELECT * FROM " . SUDOKU_SESSIONS . "
	WHERE session_time > $time_limit
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error in retrieving Sudoku userdata', '', __LINE__, __FILE__, $sql);
	}
	while ( $row=$db->sql_fetchrow($result) )
	{
		$s_users_today[]=$row['user_id'];
		if ( $row['session_time'] > $active_time )
		{
			$s_users_active[]=$row['user_id'];
		}
	}
	// grab all time stats
	$sql=" SELECT count(user_id) AS total_all FROM " . USERS_TABLE . "
	WHERE user_sudoku_playing=1
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error in retrieving Sudoku userdata', '', __LINE__, __FILE__, $sql);
	}
	$row=$db->sql_fetchrow($result);
	$alltime_players=$row['total_all'];

	$sql=" SELECT SUM(played) AS total_all_played FROM " . SUDOKU_STATS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error in retrieving Sudoku userdata', '', __LINE__, __FILE__, $sql);
	}
	$row=$db->sql_fetchrow($result);
	$alltime_played=$row['total_all_played'];

	return;
}
function sudoku_resynch()
{
	global $db;

	// synch the game count
	// create an array of games and numbers
	$sql=" SELECT game_pack, game_num FROM " . SUDOKU_STARTS . "
	ORDER BY game_pack ASC, game_num ASC
	";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error in synchronisation', '', __LINE__, __FILE__, $sql);
	}
	$x=0;
	while ( $row=$db->sql_fetchrow($result) )
	{
		$sql_a=" SELECT user_id FROM " . SUDOKU_USERS . "
		WHERE game_pack=" . $row['game_pack'] . "
		AND game_num=" . $row['game_num'] . "
		";
		if ( !($result_a = $db->sql_query($sql_a)) )
		{
			message_die(GENERAL_ERROR, 'Error in synchronisation', '', __LINE__, __FILE__, $sql_a);
		}
		while ( $row_a=$db->sql_fetchrow($result_a) )
		{
			$sql_b=" UPDATE " . SUDOKU_STATS . "
			SET played='$x'
			WHERE user_id=" . $row_a['user_id'];
			if ( !$db->sql_query($sql_b) )
			{
				message_die(GENERAL_ERROR, 'Error in synchronisation', '', __LINE__, __FILE__, $sql_b);
			}
		}
		$x++;
	}
	return;
}

function sudoku_grid_success($pack, $num, $curr_points, $redirect)
{
	global $db, $userdata, $lang, $lrow, $line;
	// update the user stats
		$sql=" UPDATE " . SUDOKU_STATS . "
		SET played=played+1, points=points+'$curr_points'
		WHERE user_id=" . $userdata['user_id'];
		if (!$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error inserting Sudoku userdata to database', '', __LINE__, __FILE__, $sql);
		}

		$line='';
		sudoku_starting_data($pack, ($num+1), SUDOKU_STARTS, '');
		if ( !$line )
		{
			sudoku_starting_data(($pack+1),1, SUDOKU_STARTS, '');
		}
		if ( !$line )
		{
			$message=$lang['sudoku_nomore_grids'];
			message_die(GENERAL_MESSAGE, $message);
		}
		// ok, so let's update the users game and tell them the good news
		$sql=" INSERT INTO " . SUDOKU_USERS . "
		(user_id,game_pack,game_num,game_level,line_1,line_2,line_3,line_4,line_5,line_6,line_7,line_8,line_9)
		VALUES
		(" . $userdata['user_id'] . "," . $lrow['game_pack'] . "," . $lrow['game_num'] . "," . $lrow['game_level'] . ",'" . $lrow['line_1'] . "','" . $lrow['line_2'] . "','" . $lrow['line_3'] . "','" . $lrow['line_4'] . "','" . $lrow['line_5'] . "','" . $lrow['line_6'] . "','" . $lrow['line_7'] . "','" . $lrow['line_8'] . "','" . $lrow['line_9'] . "')
		";
		if (!$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Error inserting Sudoku userdata to database', '', __LINE__, __FILE__, $sql);
		}

		$message=$lang['sudoku_load_new'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);

		return;
}

//
// end functions
//

?>