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
* Nivisec.com (support@nivisec.com)
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
	'Already_Rated' => 'You rated: <b>%d</b>', //%d = their rate
	'Rate_Stats' => '<b>%.2f</b>', //%.2f = average, %d = min, %d = max, %d # of ratings
	'Rate' => 'Rate',
	'Choose_Rating' => 'Choose Rating',
	'Topic_Rated' => 'Topic has been rated.',
	'Anon_Rate_Disabled' => 'Anonymous users are not allowed to rate',
	'Not_Authorized_To_Rate' => 'You are not authorized to rate this topic',
	'Change_Rating' => 'Change Rating',
	'View_Details' => '<a href="%s" title="View Details">View detailed info</a>', //%s = detailed URL
	'View_Details_2' => '<a href="%s" title="View Details">View detailed info</a>', //%s = detailed URL
	'Username_Gave_Rate_of_Max' => '<b>%s</b> rated this topic <b>%d</b> of a possible <b>%d</b> on %s.', //%s = username, %d = user rate, %d = max rate, %s = date with create_date()
	'Detailed_Rating' => 'Detailed Rating',
	'Details_For_Topic' => 'Rating Details For&nbsp;&raquo;&nbsp;<b>%s</b>', //%s = topic title
	'Or_Someone_From_IP' => '(ip logged)', // makes it smaller for smaller res screens
	'Disable_Rating_ON' => 'Disable Rating in this post',
	'Summary' => 'Summary Rating',
	'Topic_Rating_Details' => 'Topic Rating Details',

	'Anonymous' => 'Anonymous',
	'All_Forums' => 'All Forums',

	'Max_Rate' => 'Max Rating',
	'User_Rate' => 'User Rating',
	'Rate_Date' => 'Date Rated',
	'Rate_Time' => 'Time Rated',
	'Rate_Order' => 'Rate Number',

	'No_Topics_Rated' => 'No topics have been rated',
	'Top_Topics' => 'Top %d Rated Topics', //%d = number of topics
	'Top_Topics_For_Forum' => 'Top %d Rated Topics for %s', //%d = number of topics, %s is forum name
	'For_Forum' => '%s Only', //%s = forum name
	'Last_Rated' => 'Last Rated',
	'Number_of_Rates' => '# of Ratings',
	'Rating' => 'Rating',
	'Min' => 'Min',
	'Max' => 'Max',
	'Min_Rating' => 'Min Rating',
	'By_Forum' => 'List By Forum',
	'Details_For_Topic' => '<b>%s</b>', //%s = topic title

//admin
	'Status' => 'Status',
	'Auth_Description' => 'Descriptions',
	'NONE' => 'Rating is totally disabled and no rating bar will display above topics',
	'ALL' => 'All users may rate and view the bar, which includes anonymous and registered',
	'REG' => 'Only registered users can rate, but everyone can view the bar',
	'PRIVATE' => 'Only registered users can rate and view the bar',
	'MOD' => 'Only forum moderators and admins can rate and everyone can view the bar',
	'ADMIN' => 'Only admins can rate and everyone can view the bar',
	'Allow_Poster_To_Disable_Rating' => 'Allow The Poster To Disable Rating Ability',
	'Allow_Detailed_Ratings_Page' => 'Allow Users To View Detailed Ratings Page',
	'Max_Rating' => 'Max Rating Allowed (1 to MAX)',
	'Allow_Users_To_ReRate' => 'Allow users to change their rating',
	'Check_Anon_IP' => 'Check Anonymous User\'s IP when voting to see if they\'ve already rated',
	'Anon_Rate_ID' => 'Next Anonymous User rating IP.<br />Don\'t change this unless you know what you are doing and really need to.<br />Setting it to a higher value than what is listed is a very bad idea',
	'Big_Page_Number' => 'Number of topics to display on the separate ratings page (if you choose to use it)',
	'Main_Page_Number' => 'Number of topics to display on the main page display (if you choose to use it)',
	'Header_Page_Number' => 'Number of topics to display on the header page display (if you choose to use it)',
	'Mass_Update' => 'Mass Update',
	'Purge_Old_Ratings' => 'Purge Old Ratings',
	'Min_Rates' => 'Minimum Number of Raters a Topic Must Have Before Displaying on Top Rated Lists',
	'Purge' => 'Purge',
	'Purged' => 'Purged',
	'Purge_Desc' => 'Purges ratings from deleted topics that could have somehow not been originally deleted with the topic.',
	'Clear' => 'Clear',
	'Clear_Desc' => 'Clear <b>ALL</b> your rating details.  Only do this if you are having unusual problems or want to <b>erase all</b> your voting data.  Click the box and also type YES in the box beside it.',
	'Complete' => 'Complete',
	'Authorization' => 'Authorization',
	'rate_average' => 'Average Rate',
	'rate_minimum' => 'Minimum Rate',
	'rate_maximum' => 'Maximum Rate',
	'Number_of_Rates' => 'Number Of Rates',
	'Rank2' => '#',
	'Rating' => 'Rating',

//Error Messages
	'Database_Error' => 'Database Error',
	'Error_Dbase_Config' => 'Error retrieving or updating Configuration data.',
	'Error_Dbase_Ratings' => 'Error retrieving or updating Ratings data.',
	'Error_Dbase_Auth' => 'Error retrieving or updating Ratings Authorization data.',
	'No_Topic_ID' => 'No Topic was specified to obtain details for.',
	)
);

?>