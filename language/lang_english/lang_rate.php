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

/* NOTES */
/* This was just thrown together from the old version, some of these might
** not be used anymore, but I don't have the time to go through it. */

/* If you are translating this, please send me the translation */

$lang['Already_Rated'] = 'You rated: <b>%d</b>'; //%d = their rate
$lang['Rate_Stats'] = '<b>%.2f</b>'; //%.2f = average, %d = min, %d = max, %d # of ratings
$lang['Rate'] = 'Rate';
$lang['Choose_Rating'] = 'Choose Rating';
$lang['Topic_Rated'] = 'Topic has been rated.';
$lang['Anon_Rate_Disabled'] = 'Anonymous users are not allowed to rate';
$lang['Not_Authorized_To_Rate'] = 'You are not authorized to rate this topic';
$lang['Change_Rating'] = 'Change Rating';
$lang['View_Details'] = '<a href="%s" title="View Details">View detailed info</a>'; //%s = detailed URL
$lang['View_Details_2'] = '<a href="%s" title="View Details">View detailed info</a>'; //%s = detailed URL
$lang['Username_Gave_Rate_of_Max'] = '<b>%s</b> rated this topic <b>%d</b> of a possible <b>%d</b> on %s.'; //%s = username, %d = user rate, %d = max rate, %s = date with create_date()
$lang['Detailed_Rating'] = 'Detailed Rating';
$lang['Details_For_Topic'] = 'Rating Details For&nbsp;&raquo;&nbsp;<b>%s</b>'; //%s = topic title
$lang['Or_Someone_From_IP'] = '(ip logged)'; // makes it smaller for smaller res screens
$lang['Disable_Rating_ON'] = 'Disable Rating in this post';
$lang['Summary'] = 'Summary Rating';
$lang['Topic_Rating_Details'] = 'Topic Rating Details';

$lang['Anonymous'] = 'Anonymous';
$lang['All_Forums'] = 'All Forums';

$lang['Max_Rate'] = 'Max Rating';
$lang['User_Rate'] = 'User Rating';
$lang['Rate_Date'] = 'Date Rated';
$lang['Rate_Time'] = 'Time Rated';
$lang['Rate_Order'] = 'Rate Number';

$lang['No_Topics_Rated'] = 'No topics have been rated';
$lang['Top_Topics'] = 'Top %d Rated Topics'; //%d = number of topics
$lang['Top_Topics_For_Forum'] = 'Top %d Rated Topics for %s'; //%d = number of topics, %s is forum name
$lang['For_Forum'] = '%s Only'; //%s = forum name
$lang['Last_Rated'] = 'Last Rated';
$lang['Number_of_Rates'] = '# of Ratings';
$lang['Rating'] = 'Rating';
$lang['Min'] = 'Min';
$lang['Max'] = 'Max';
$lang['Min_Rating'] = 'Min Rating';
$lang['By_Forum'] = 'List By Forum';
$lang['Details_For_Topic'] = '<b>%s</b>'; //%s = topic title

//admin
$lang['Status'] = 'Status';
$lang['Auth_Description'] = 'Descriptions';
$lang['NONE'] = 'Rating is totally disabled and no rating bar will display above topics';
$lang['ALL'] = 'All users may rate and view the bar, which includes anonymous and registered';
$lang['REG'] = 'Only registered users can rate, but everyone can view the bar';
$lang['PRIVATE'] = 'Only registered users can rate and view the bar';
$lang['MOD'] = 'Only forum moderators and admins can rate and everyone can view the bar';
$lang['ADMIN'] = 'Only admins can rate and everyone can view the bar';
$lang['Allow_Poster_To_Disable_Rating'] = 'Allow The Poster To Disable Rating Ability';
$lang['Allow_Detailed_Ratings_Page'] = 'Allow Users To View Detailed Ratings Page';
$lang['Max_Rating'] = 'Max Rating Allowed (1 to MAX)';
$lang['Allow_Users_To_ReRate'] = 'Allow users to change their rating';
$lang['Check_Anon_IP'] = 'Check Anonymous User\'s IP when voting to see if they\'ve already rated';
$lang['Anon_Rate_ID'] = 'Next Anonymous User rating IP.<br />Don\'t change this unless you know what you are doing and really need to.<br />Setting it to a higher value than what is listed is a very bad idea';
$lang['Big_Page_Number'] = 'Number of topics to display on the separate ratings page (if you choose to use it)';
$lang['Main_Page_Number'] = 'Number of topics to display on the main page display (if you choose to use it)';
$lang['Header_Page_Number'] = 'Number of topics to display on the header page display (if you choose to use it)';
$lang['Mass_Update'] = 'Mass Update';
$lang['Purge_Old_Ratings'] = 'Purge Old Ratings';
$lang['Min_Rates'] = 'Minimum Number of Raters a Topic Must Have Before Displaying on Top Rated Lists';
$lang['Purge'] = 'Purge';
$lang['Purged'] = 'Purged';
$lang['Purge_Desc'] = 'Purges ratings from deleted topics that could have somehow not been originally deleted with the topic.';
$lang['Clear'] = 'Clear';
$lang['Clear_Desc'] = 'Clear <b>ALL</b> your rating details.  Only do this if you are having unusual problems or want to <b>erase all</b> your voting data.  Click the box and also type YES in the box beside it.';
$lang['Complete'] = 'Complete';
$lang['Authorization'] = 'Authorization';
$lang['rate_average'] = 'Average Rate';
$lang['rate_minimum'] = 'Minimum Rate';
$lang['rate_maximum'] = 'Maximum Rate';
$lang['Number_of_Rates'] = 'Number Of Rates';
$lang['Rank2'] = '#';
$lang['Rating'] = 'Rating';

//Error Messages
$lang['Database_Error'] = 'Database Error';
$lang['Error_Dbase_Config'] = 'Error retrieving or updating Configuration data.';
$lang['Error_Dbase_Ratings'] = 'Error retrieving or updating Ratings data.';
$lang['Error_Dbase_Auth'] = 'Error retrieving or updating Ratings Authorization data.';
$lang['No_Topic_ID'] = 'No Topic was specified to obtain details for.';

?>