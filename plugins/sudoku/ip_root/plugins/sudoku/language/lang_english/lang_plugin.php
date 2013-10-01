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
* Lopalong
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'Sudoku_Version' => 'Sudoku Mod Version %s &copy; Majorflam 2005 - Latest Game Pack Installed: Pack %s',
	'suduko_blank_tile' => 'Click To Enter Number',
	'suduko_user_tile' => 'Click To Edit',
	'sudoku_no_tile' => 'No Tile Specified!',
	'sudoku_input_num' => 'Select the desired number to insert in the chosen tile.',
	'sudoku_no_number' => 'You must select a number to place on that tile!',
	'sudoku_instructions' => 'Place a number from 1-9 in each empty cell so that each row, each column and each 3 x 3 block contains all the numbers from 1-9. You have been given some "starter" numbers to help you, and they are shown underlined. Simply click a blank cell to insert your number. If you make a mistake, simply click the number to insert a new one.',
	'sudoku_invalid_number' => 'You have entered an invalid number!',
	'sudoku_howto' => 'How To Play',
	'sudoku_stats' => 'Statistics',
	'sudoku_statistics' => 'Statistics are gathered on your performance at this game, and the performance of others too. The Top Ten players are shown. Every time you insert a number, you gain 10 points. But beware! Every time you change a number, you lose 15 points. If you reset the grid, you start the game again with minus 200 points.',
	'sudoku_game_info' => 'Game Pack:<b>%s</b> || Game Number:<b>%s</b> || Level:<b>%s</b>',
	'sudoku_player_stats' => 'Your Statistics',
	'sudoku_leaderboard' => 'Sudoku Top 10',
	'sudoku_played' => 'Total Games Played',
	'sudoku_points' => 'Total Points Scored',
	'sudoku_these_points' => 'Points - Current Game',
	'sudoku_lead_played' => 'Games Played',
	'sudoku_lead_points' => 'Points',
	'sudoku_level_easy' => 'Easy',
	'sudoku_level_medium' => 'Medium',
	'sudoku_level_hard' => 'Difficult',
	'sudoku_level_very_hard' => 'Very Difficult',
	'sudoku_confirm_reset' => 'Resetting Grid',
	'sudoku_confirm_reset_text' => 'Are you sure you want to reset this grid? If you do, you will start the grid with minus 200 points.',
	'sudoku_reset_cancelled' => 'Grid reset cancelled.',
	'sudoku_lead_current_game' => 'Now Playing',
	'sudoku_current_game_text' => 'Pack:<b>%s ~ </b>Game <b>#%s</b>',
	'sudoku_nomore_grids' => 'Congratulations! You have completed all the grids available. Please contact a Board Administrator to load more grids.',
	'sudoku_load_new' => 'Grid completed successfully! Loading new grid...',
	'sudoku_wrong_numbers' => 'You have completed the grid, but some numbers were inserted in the wrong place. The grid will now load again, and the wrong numbers will be removed. This will cost 20 points for every wrong number. There are %s numbers inserted incorrectly, and this has cost you %s points in total.<br />&nbsp;<br />Loading Grid...',
	'sudoku_reset_confirmed' => 'Your grid will now reset. Loading...',
	'sudoku_no_change_starter' => 'You can\'t change a starter number!',
	'sudoku_no_url_tricks' => 'No url tricks, please!.',
	'sudoku_place' => 'Place!',
	'sudoku_reset_grid' => 'Reset Grid',
	'sudoku_who_is_online' => 'Who is playing Sudoku',
	'sudoku_total_online' => 'In total <b>%s</b> users have played Sudoku in the last 24 hours:',
	'sudoku_logged_in_list' => 'Users currently playing Sudoku:',
	'sudoku_online_explain' => 'This data is based on users playing Sudoku in the past 5 minutes.',
	'sudoku_game_stats' => 'In total, <b>%s</b> users have played Sudoku on this site. They have played <b>%s</b> games overall',
	'sudoku_no_blank_starter' => 'You cannot erase a number that does not exist!',
	'sudoku_view_all' => 'View All',
	'sudoku_buy_number' => 'Buy Number',
	'sudoku_confirm_buy_text' => 'You have chosen to buy a number. A blank tile from your grid will be chosen at random, and the correct number inserted for you. This action will cost you 30 points from your grid total. Are you sure you want to buy a number?',
	'sudoku_buy_cancelled' => 'You have chosen not to buy a number on this occasion',
	'sudoku_ran_error' => 'An error occurred whilst trying to insert your number. Please try again. If this problem persists, please contact a Board Administrator',
	'sudoku_ran_success' => 'Your number has been chosen at random and inserted into your grid. It will be highlighted for your information',
	'sudoku_resynch_success' => 'Synchronization of statistics complete. Redirecting...',
	'sudoku_resynch' => 'Resync',
// for ver 1.0.5
	'sudoku_reset_game' => 'Reset Sudoku Game',
	'sudoku_reset_game_text' => 'Are you sure you want to reset the Sudoku Game? This will remove all player data from the database, and start the game all over again as if it is a fresh install. Please note that your Game Packs will stay untouched, so there will be no need to re-install Game Packs. This action cannot be undone',
	'sudoku_reset_game_cancelled' => 'Game Reset cancelled. No changes have been made.',
	'sudoku_rest_game_success' => 'Sudoku Game is now reset.',
	)
);

?>