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

define('IN_ICYPHOENIX', true);
define('IN_SUDOKU', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

// Get language Variables and page specific functions
setup_extra_lang(array('lang_sudoku'));

include_once(IP_ROOT_PATH . 'includes/functions_sudoku.' . PHP_EXT);

 // Make sure the player is registered
$user_id = $userdata['user_id'];
if (!$userdata['session_logged_in'])
{
	$header_location = (@preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE"))) ? "Refresh: 0; URL=" : "Location: ";
	header($header_location . append_sid(CMS_PAGE_LOGIN . "?redirect=sudoku." . PHP_EXT, true));
	exit;
}

$sql = " SELECT game_pack FROM " . SUDOKU_STARTS . "
	ORDER BY game_pack DESC LIMIT 1";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
$latest_pack = $row['game_pack'];

// set standard vars
$mode = request_var('mode', '');
$type = request_var('type', '');
$pack = request_var('p', 0);
$num = request_var('n', 0);
$level = request_var('l', 0);
$num_insert = request_post_var('num_input', 0);
$co_ord = explode('_', htmlspecialchars($_GET['tile']));

$redirect = '<meta http-equiv = "refresh" content = "3;url = ' . append_sid('sudoku.' . PHP_EXT) . '">';
$games = array();
$points = array();
$admin_tools = ($userdata['user_level'] == ADMIN) ? '|| <a href="' . append_sid('sudoku.' . PHP_EXT . '?&amp;mode=resynch') . '" class="nav">' . $lang['sudoku_resynch'] . '</a> || <a href="' . append_sid('sudoku.' . PHP_EXT . '?&amp;mode=reset_game') . '" class="nav">' . $lang['sudoku_reset_game'] . '</a>' : '';
// Set template Vars
$template->assign_vars(array(
	'SUDOKU_VERSION' => sprintf($lang['Sudoku_Version'], $config['sudoku_version'], $latest_pack),
	'L_SUBMIT' => $lang['Submit'],
	'INSTRUCTIONS' => $lang['sudoku_instructions'],
	'HOW_TO' => $lang['sudoku_howto'],
	'STATS' => $lang['sudoku_stats'],
	'STATISTICS' => $lang['sudoku_statistics'],
	'PLAYER_STATS' => $lang['sudoku_player_stats'],
	'LEADERBOARD' => $lang['sudoku_leaderboard'],
	'USERNAME' => $lang['Username'],
	'PLAYED' => $lang['sudoku_played'],
	'POINTS' => $lang['sudoku_points'],
	'LEAD_PLAYED' => $lang['sudoku_lead_played'],
	'LEAD_POINTS' => $lang['sudoku_lead_points'],
	'THESE_POINTS' => $lang['sudoku_these_points'],
	'LEAD_CURRENT_GAME' => $lang['sudoku_lead_current_game'],
	'PLACE' => $lang['sudoku_place'],
	// navigation
	'RESET' => '<a href="' . append_sid('sudoku.' . PHP_EXT . '?mode=reset') . '" class="nav">' . $lang['sudoku_reset_grid'] . '</a>',
	'ADMIN_TOOLS' => $admin_tools
	)
);
//
// end vars
//
if ($mode == 'resynch')
{
	if ($userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorized'] . $redirect);
	}
	sudoku_resynch();
	message_die(GENERAL_MESSAGE, $lang['sudoku_resynch_success'] . $redirect);
}

if ($mode == 'reset_game')
{
	if ($userdata['user_level'] != ADMIN)
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorized'] . $redirect);
	}
	if (!isset($_POST['confirm']) && !isset($_POST['cancel']))
	{
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['sudoku_reset_game'],
			'S_CONFIRM_ACTION' => append_sid('sudoku.' . PHP_EXT . '?mode=reset_game'),
			'MESSAGE_TEXT' => $lang['sudoku_reset_game_text'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			)
		);
		full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
	}
	if (isset($_POST['cancel']))
	{
		$message = $lang['sudoku_reset_game_cancelled'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	// OK, reset the game
	// first truncate sudoku tables
	$sql = " DELETE FROM " . SUDOKU_SESSIONS;
	$db->sql_query($sql);

	$sql = " DELETE FROM " . SUDOKU_USERS;
	$db->sql_query($sql);

	$sql = " DELETE FROM " . SUDOKU_STATS;
	$db->sql_query($sql);

	$sql = " UPDATE " . USERS_TABLE . "
	SET user_sudoku_playing=0
	WHERE user_sudoku_playing>0
	";
	$db->sql_query($sql);

	// let them know the good news
	$redirect='<meta http-equiv="refresh" content="3;url=' . append_sid('sudoku.' . PHP_EXT . '?#grid') . '">';
	message_die(GENERAL_MESSAGE,$lang['sudoku_rest_game_success'] . $redirect);
}

if ($mode == 'buy')
{
	if (!isset($_POST['confirm']) && !isset($_POST['cancel']))
	{
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['sudoku_buy_number'],
			'S_CONFIRM_ACTION' => append_sid('sudoku.' . PHP_EXT . '?mode=buy&amp;p=' . $pack . '&amp;n=' . $num),
			'MESSAGE_TEXT' => $lang['sudoku_confirm_buy_text'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			)
		);
		full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
	}
	if (isset($_POST['cancel']))
	{
		$message = $lang['sudoku_buy_cancelled'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	// buy the number
	$and_clause = '';
	sudoku_starting_data($pack, $num, SUDOKU_SOLUTIONS, $and_clause);
	$solutions_ary = $line;
	unset($line);
	unset($lrow);
	$and_clause = 'AND user_id = ' . $userdata['user_id'];
	sudoku_starting_data($pack, $num, SUDOKU_USERS, $and_clause);
	$users_ary = $line;
	unset($line);

	// find the unknowns
	$unknown_ary = array();
	for ($i = 0; $i < 9; $i++)
	{
		for ($x = 0; $x < 9; $x++)
		{
			if ($users_ary[$i][$x] == 'x')
			{
				$unknown_ary[] = $i . '_' . $x;
			}
		}
	}
	// create a 3 dimensional array
	$unknowns = array();
	for ($i = 0; $i < sizeof($unknown_ary); $i++)
	{
		$unknowns[] = explode('_', $unknown_ary[$i]);
	}
	// grab the random number to insert
	$random_insertion = rand(0, (sizeof($unknowns)-1));

	// grab the solution for that co-ord
	$x_co = $unknowns[$random_insertion][0];
	$y_co = $unknowns[$random_insertion][1];
	$ran_sol = $solutions_ary[$x_co][$y_co];

	// insert the new number
	$key = 'line_' . ($x_co+1);
	$user_line_upd = explode('a',$lrow[$key]);
	$val = $user_line_upd[$y_co];
	if ($val != 'x' || sizeof($user_line_upd) != 9)
	{
		message_die(GENERAL_MESSAGE, $lang['sudoku_ran_error']);
	}
	$user_line_upd[$y_co] = $ran_sol + 20;
	$new_line = implode('a', $user_line_upd);

	$sql = " UPDATE " . SUDOKU_USERS . "
	SET $key = '$new_line', points = points - 30
	WHERE game_pack = $pack
	AND game_num = $num
	AND user_id = " . $userdata['user_id'];
	$db->sql_query($sql);

	$redirect='<meta http-equiv="refresh" content="3;url=' . append_sid('sudoku.' . PHP_EXT . '?#grid') . '">';
	message_die(GENERAL_MESSAGE,$lang['sudoku_ran_success'] . $redirect);
}

if (isset($_POST['input_num']))
{
	// insert the number to the users data
	if (!$num_insert)
	{
		$message = $lang['sudoku_no_number'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	if ($num_insert > 19)
	{
		$message = $lang['sudoku_invalid_number'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	$this_line = 'line_' . $co_ord[0];
	$sql = " SELECT $this_line FROM " . SUDOKU_USERS . "
	WHERE game_pack = $pack
	AND game_num = $num
	AND user_id = " . $userdata['user_id'];
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$insert_line = explode('a', $row[$this_line]);
	$pos = ($co_ord['1'] - 1);
	// add for blank number
	if (($type == 'insert') && ($num_insert == -1))
	{
		$message = $lang['sudoku_no_blank_starter'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	// end blank number
	// test for url tricks
	if (($type == 'insert') && ($insert_line[$pos] == 'x'))
	{
		$insert_line[$pos] = $num_insert;
	}
	elseif (($type == 'edit') && ($insert_line[$pos] > 9))
	{
		$insert_line[$pos] = ($num_insert > 0) ? $num_insert : 'x';
	}
	elseif ($insert_line[$pos] < 10)
	{
		$message = $lang['sudoku_no_change_starter'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$message = $lang['sudoku_no_url_tricks'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
	// end url tricks test

	$inserted_line = implode('a',$insert_line);
	// changed for blank number
	//$points_addition= ($type == 'insert') ? 10 : -15;
	if ($num_insert > -1)
	{
		$points_addition = ($type == 'insert') ? 10 : -15;
	}
	else
	{
		$points_addition = -25;
	}
	// end blank number

	$sql = " UPDATE " . SUDOKU_USERS . "
	SET $this_line = '" . $inserted_line . "',points=points+$points_addition
	WHERE game_pack = $pack
	AND game_num = $num
	AND user_id = " . $userdata['user_id'];
	$db->sql_query($sql);
}

// check to see if this user has played the game, if not we'll set them up for it
$sql = " SELECT user_id, game_pack, game_num, game_level, points FROM " . SUDOKU_USERS . "
WHERE user_id=" . $userdata['user_id'] . "
ORDER BY game_pack DESC, game_num DESC
LIMIT 1
";
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);
if (!$row['user_id'])
{
	//
	// insert user to database for first run
	//
	sudoku_starting_data(1,1, SUDOKU_STARTS,'');

	$sql = " INSERT INTO " . SUDOKU_USERS . "
	(user_id,game_pack,game_num,game_level,line_1,line_2,line_3,line_4,line_5,line_6,line_7,line_8,line_9)
	VALUES
	(" . $userdata['user_id'] . ",1,1,1,'" . $lrow['line_1'] . "','" . $lrow['line_2'] . "','" . $lrow['line_3'] . "','" . $lrow['line_4'] . "','" . $lrow['line_5'] . "','" . $lrow['line_6'] . "','" . $lrow['line_7'] . "','" . $lrow['line_8'] . "','" . $lrow['line_9'] . "')
	";
	$db->sql_query($sql);

	$row['game_pack'] = 1;
	$row['game_num'] = 1;
	$row['game_level'] = 1;

	$sql = " UPDATE " . USERS_TABLE . "
	SET user_sudoku_playing = 1
	WHERE user_id=" . $userdata['user_id'];
	$db->sql_query($sql);
}

//
// set sudokudata for this user
//
$pack = $row['game_pack'];
$num = $row['game_num'];
$level = $row['game_level'];
$curr_points = $row['points'];
$and_clause='AND user_id=' . $userdata['user_id'];
switch ($level)
{
	case EASY:
	$v_level = $lang['sudoku_level_easy'];
	break;

	case MEDIUM:
	$v_level = $lang['sudoku_level_medium'];
	break;

	case HARD:
	$v_level = $lang['sudoku_level_hard'];
	break;

	case VERY_HARD:
	$v_level = $lang['sudoku_level_very_hard'];
	break;

	default:
	$v_level = $lang['sudoku_level_easy'];
}

//
// are we resetting?
//
if ($mode == 'reset')
{
	if (!isset($_POST['confirm']) && !isset($_POST['cancel']))
	{
		$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['sudoku_confirm_reset'],
		'S_CONFIRM_ACTION' => append_sid('sudoku.' . PHP_EXT . '?mode=reset'),
		'MESSAGE_TEXT' => $lang['sudoku_confirm_reset_text'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		));
		full_page_generation('confirm_body.tpl', $lang['Confirm'], '', '');
	}
	if (isset($_POST['cancel']))
	{
		$message = $lang['sudoku_reset_cancelled'] . $redirect;
		message_die(GENERAL_MESSAGE, $message);
	}

	sudoku_starting_data($pack,$num, SUDOKU_STARTS,'');
	$sql = " UPDATE " . SUDOKU_USERS . "
	SET points=-200,line_1='" . $lrow['line_1'] . "',line_2='" . $lrow['line_2'] . "',line_3='" . $lrow['line_3'] . "',line_4='" . $lrow['line_4'] . "',line_5='" . $lrow['line_5'] . "',line_6='" . $lrow['line_6'] . "',line_7='" . $lrow['line_7'] . "',line_8='" . $lrow['line_8'] . "',line_9='" . $lrow['line_9'] . "'
	WHERE user_id=" . $userdata['user_id'] . "
	AND game_pack = $pack
	AND game_num = $num
	";
	$db->sql_query($sql);
	$mode = 0;
	$message = $lang['sudoku_reset_confirmed'] . $redirect;
	message_die(GENERAL_MESSAGE, $message);
}

// perform tasks
sudoku_tasks($userdata['user_id'], $pack, $num, $level);
sudoku_starting_data($pack, $num, SUDOKU_USERS, $and_clause);

// check for completed grid
if (!in_array('x', $line[0]) && !in_array('x', $line[1]) && !in_array('x', $line[2]) && !in_array('x', $line[3]) && !in_array('x', $line[4]) &&
!in_array('x', $line[5]) && !in_array('x', $line[6]) && !in_array('x', $line[7]) && !in_array('x', $line[8]))
{
	// OK, so grid is complete, but is it right according to default grid solution?
	$u_line = array();
	// let's lower the user and random numbers to do the check
	for ($i = 0; $i < 9; $i++)
	{
		for ($x = 0; $x < 9; $x++)
		{
			// first lower the random numbers
			if ($line[$i][$x] > 19)
			{
				$line[$i][$x] = $line[$i][$x]-20;
			}
			// then the user numbers
			if ($line[$i][$x] > 9)
			{
				$line[$i][$x] = $line[$i][$x]-10;
			}
		}
	}

	$u_line[0] = $line[0];
	$u_line[1] = $line[1];
	$u_line[2] = $line[2];
	$u_line[3] = $line[3];
	$u_line[4] = $line[4];
	$u_line[5] = $line[5];
	$u_line[6] = $line[6];
	$u_line[7] = $line[7];
	$u_line[8] = $line[8];

	$test_line = $line;
	unset($line);

	sudoku_starting_data($pack, $num, SUDOKU_SOLUTIONS, '');

	// OK, so lets compare!
	if ($u_line[0] == $line[0] && $u_line[1] == $line[1] && $u_line[2] == $line[2] && $u_line[3] == $line[3] && $u_line[4] == $line[4] &&
	$u_line[5] == $line[5] && $u_line[6] == $line[6] && $u_line[7] == $line[7] && $u_line[8] == $line[8])
	{
		// success!
		sudoku_grid_success($pack, $num, $curr_points, $redirect);
	}
	else
	{
		$wrong_required=false;
		$bad=array();
		// OK, so we need to check for an alternative solution that the user may have entered
		// if successful, then $wrong_required=false
		// do the basic check on the 9 grids

		$bad[] = (count(array_unique($test_line[0])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[1])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[2])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[3])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[4])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[5])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[6])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[7])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($test_line[8])) == 9) ? 0 : 1;

		// now we must create horizontal arrays
		$hor_test = array();

		$hor_test[0] = array($test_line[0][0],$test_line[0][1],$test_line[0][2],$test_line[1][0],$test_line[1][1],$test_line[1][2],$test_line[2][0],$test_line[2][1],$test_line[2][2]);

		$hor_test[1] = array($test_line[0][3],$test_line[0][4],$test_line[0][5],$test_line[1][3],$test_line[1][4],$test_line[1][5],$test_line[2][3],$test_line[2][4],$test_line[2][5]);

		$hor_test[2] = array($test_line[0][6],$test_line[0][7],$test_line[0][8],$test_line[1][6],$test_line[1][7],$test_line[1][8],$test_line[2][6],$test_line[2][7],$test_line[2][8]);

		$hor_test[3] = array($test_line[3][0],$test_line[3][1],$test_line[3][2],$test_line[4][0],$test_line[4][1],$test_line[4][2],$test_line[5][0],$test_line[5][1],$test_line[5][2]);

		$hor_test[4] = array($test_line[3][3],$test_line[3][4],$test_line[3][5],$test_line[4][3],$test_line[4][4],$test_line[4][5],$test_line[5][3],$test_line[5][4],$test_line[5][5]);

		$hor_test[5] = array($test_line[3][6],$test_line[3][7],$test_line[3][8],$test_line[4][6],$test_line[4][7],$test_line[4][8],$test_line[5][6],$test_line[5][7],$test_line[5][8]);

		$hor_test[6] = array($test_line[6][0],$test_line[6][1],$test_line[6][2],$test_line[7][0],$test_line[7][1],$test_line[7][2],$test_line[8][0],$test_line[8][1],$test_line[8][2]);

		$hor_test[7] = array($test_line[6][3],$test_line[6][4],$test_line[6][5],$test_line[7][3],$test_line[7][4],$test_line[7][5],$test_line[8][3],$test_line[8][4],$test_line[8][5]);

		$hor_test[8] = array($test_line[6][6],$test_line[6][7],$test_line[6][8],$test_line[7][6],$test_line[7][7],$test_line[7][8],$test_line[8][6],$test_line[8][7],$test_line[8][8]);

		$bad[] = (count(array_unique($hor_test[0])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[1])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[2])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[3])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[4])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[5])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[6])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[7])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($hor_test[8])) == 9) ? 0 : 1;

		// finally, vertical arrays
		$ver_test = array();

		$ver_test[0] = array($test_line[0][0],$test_line[0][3],$test_line[0][6],$test_line[3][0],$test_line[3][3],$test_line[3][6],$test_line[6][0],$test_line[6][3],$test_line[6][6]);

		$ver_test[1] = array($test_line[0][1],$test_line[0][4],$test_line[0][7],$test_line[3][1],$test_line[3][4],$test_line[3][7],$test_line[6][1],$test_line[6][4],$test_line[6][7]);

		$ver_test[2] = array($test_line[0][2],$test_line[0][5],$test_line[0][8],$test_line[3][2],$test_line[3][5],$test_line[3][8],$test_line[6][2],$test_line[6][5],$test_line[6][8]);

		$ver_test[3] = array($test_line[1][0],$test_line[1][3],$test_line[1][6],$test_line[4][0],$test_line[4][3],$test_line[4][6],$test_line[7][0],$test_line[7][3],$test_line[7][6]);

		$ver_test[4] = array($test_line[1][1],$test_line[1][4],$test_line[1][7],$test_line[4][1],$test_line[4][4],$test_line[4][7],$test_line[7][1],$test_line[7][4],$test_line[7][7]);

		$ver_test[5] = array($test_line[1][2],$test_line[1][5],$test_line[1][8],$test_line[4][2],$test_line[4][5],$test_line[4][8],$test_line[7][2],$test_line[7][5],$test_line[7][8]);

		$ver_test[6] = array($test_line[2][0],$test_line[2][3],$test_line[2][6],$test_line[5][0],$test_line[5][3],$test_line[5][6],$test_line[8][0],$test_line[8][3],$test_line[8][6]);

		$ver_test[7] = array($test_line[2][1],$test_line[2][4],$test_line[2][7],$test_line[5][1],$test_line[5][4],$test_line[5][7],$test_line[8][1],$test_line[8][4],$test_line[8][7]);

		$ver_test[8] = array($test_line[2][2],$test_line[2][5],$test_line[2][8],$test_line[5][2],$test_line[5][5],$test_line[5][8],$test_line[8][2],$test_line[8][5],$test_line[8][8]);

		$bad[] = (count(array_unique($ver_test[0])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[1])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[2])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[3])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[4])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[5])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[6])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[7])) == 9) ? 0 : 1;
		$bad[] = (count(array_unique($ver_test[8])) == 9) ? 0 : 1;

		if (in_array(1, $bad))
		{
			$wrong_required=true;
		}
		else
		{
			// success!
			sudoku_grid_success($pack, $num, $curr_points, $redirect);
		}
	}
	if ($wrong_required == true)
	{
		// OK, so we need to suss the wrong numbers according to our default solution
		$u_line_insert=array();
		for ($i=0; $i<9; $i++)
		{
			for ($x=0; $x<9; $x++)
			{
				if ($line[$i][$x] != $u_line[$i][$x])
				{
					$u_line[$i][$x]='x';
					$points_minus = $points_minus+20;
					$bad_numbers++;
				}
			}
			$u_line_insert[]=implode('a', $u_line[$i]);
		}
		// now we update the users grid
		$sql = " UPDATE " . SUDOKU_USERS . "
		SET line_1 = '" . $u_line_insert[0] . "',line_2 = '" . $u_line_insert[1] . "',line_3 = '" . $u_line_insert[2] . "',line_4 = '" . $u_line_insert[3] . "',line_5 = '" . $u_line_insert[4] . "',
		line_6 = '" . $u_line_insert[5] . "',line_7 = '" . $u_line_insert[6] . "',line_8 = '" . $u_line_insert[7] . "',line_9 = '" . $u_line_insert[8] . "',points = points-$points_minus
		WHERE user_id = " . $userdata['user_id'] . "
		AND game_pack = $pack
		AND game_num = $num
		";
		$db->sql_query($sql);
		$new_redirect='<meta http-equiv="refresh" content="6;url=' . append_sid('sudoku.' . PHP_EXT) . '">';
		$message=sprintf($lang['sudoku_wrong_numbers'], $bad_numbers, $points_minus) . $new_redirect;
		message_die(GENERAL_MESSAGE, $message);
	}
}
// end completion check

// build lines for template
sudoku_grid_build();
// end lines for template

// get statistics data

// grab the top ten
$sql = " SELECT * FROM " . SUDOKU_STATS . " ORDER BY points DESC LIMIT 10";
$result = $db->sql_query($sql);
$x = 1;
while ($row = $db->sql_fetchrow($result))
{
	$stat_points = $row['points'];
	$stat_played = $row['played'];
	$stat_userid = $row['user_id'];

	$sql_a="SELECT username
					FROM " . USERS_TABLE . "
					WHERE user_id = " . $row['user_id'];
	$result_a = $db->sql_query($sql_a);
	$row_a = $db->sql_fetchrow($result_a);
	$stat_username = $row_a['username'];
	$stat_user_id = $row_a['user_id'];
	// grab current game
	$sql_a = "SELECT game_pack, game_num FROM " . SUDOKU_USERS . "
					WHERE user_id = " . $row['user_id'] . "
					ORDER BY game_pack DESC, game_num DESC
					LIMIT 1";
	$result_a = $db->sql_query($sql_a);
	$row_a = $db->sql_fetchrow($result_a);
	$stat_gamepack = $row_a['game_pack'];
	$stat_gamenum = $row_a['game_num'];

	// send to template
	$row_class = (!($x % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('leaderboard', array(
		'ROW_CLASS' => $row_class,
		'USERNAME' => colorize_username($row['user_id']),
		'POINTS' => $stat_points,
		'PLAYED' => $stat_played,
		'POS' => $x,
		'CURRENT_GAME' => sprintf($lang['sudoku_current_game_text'], $stat_gamepack, $stat_gamenum),
		)
	);
	$x++;
}

// grab online info
$s_users_online_today = sizeof($s_users_today);
$s_users_active_now = sizeof($s_users_active);

// apply usernames to users
$s_users_today_names = array();
$name = array();
$user_active = array();
$user_color = array();
$s_users_active_names = array();
for ($i = 0; $i < sizeof($s_users_today); $i++)
{
	$sql = "SELECT username, user_active, user_color FROM " . USERS_TABLE . "
				WHERE user_id = " . $s_users_today[$i] . "
				ORDER BY username ASC";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$disp_userid = $s_users_today[$i];
	$name[$disp_userid] = $row['username'];
	$user_active[$disp_userid] = $row['user_active'];
	$user_color[$disp_userid] = $row['user_color'];
}
asort($name);
$name_keys = array_keys($name);
for ($i = 0; $i < sizeof($name); $i++)
{
	$disp_userid = $name_keys[$i];
	$s_users_today_names[] = colorize_username($disp_userid, $name[$disp_userid], $user_color[$disp_userid], $user_active[$disp_userid]);
}
$active_name=array();
for ($i = 0; $i < sizeof($s_users_active); $i++)
{
	$disp_userid = $s_users_active[$i];
	$active_name[$disp_userid] = $name[$disp_userid];
}

asort($active_name);
$active_name_keys = array_keys($active_name);
for ($i = 0; $i < sizeof($active_name); $i++)
{
	$disp_userid = $active_name_keys[$i];
	$s_users_active_names[] = colorize_username($disp_userid, $name[$disp_userid], $user_color[$disp_userid], $user_active[$disp_userid]);
}

$s_users_today_disp=implode(', ', $s_users_today_names);
$s_users_active_disp=implode(', ', $s_users_active_names);

$this_userid = $userdata['user_id'];
// parse to template
$template->assign_vars(array(
	'POINTS_VALUE' => number_format($points[$this_userid]),
	'PLAYED_VALUE' => number_format($games[$this_userid]),
	'THESE_POINTS_VALUE' => number_format($curr_points),
	'GAME_INFO' => sprintf($lang['sudoku_game_info'], $pack,$num,$v_level),
	'WHO_IS_ONLINE' => $lang['sudoku_who_is_online'],
	'ONLINE_EXPLAIN' => $lang['sudoku_online_explain'],
	'SUDOKU_GAME_STATS' => sprintf($lang['sudoku_game_stats'], number_format($alltime_players), number_format($alltime_played)),
	'BUY_NUMBER' => '<a href="' . append_sid('sudoku.' . PHP_EXT . '?&amp;mode=buy&amp;p=' . $pack . '&amp;n=' . $num) . '" class="nav">' . $lang['sudoku_buy_number'] . '</a>',

	'L_TOTAL_USERS_ONLINE' => sprintf($lang['sudoku_total_online'], number_format($s_users_online_today)),
	'L_LOGGED_IN_USER_LIST' => $lang['sudoku_logged_in_list'],
	'L_TODAY_USER_LIST' => $s_users_today_disp,
	'L_ACTIVE_USER_LIST' => $s_users_active_disp,
	)
);

full_page_generation('sudoku.tpl', $lang['Sudoku'], '', '');

?>