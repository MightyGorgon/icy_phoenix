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
* Adam Alkins (phpbb at rasadam dot com)
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
	'Search_invalid_username' => 'Invalid username entered to Search',
	'Search_invalid_email' => 'Invalid email address entered to Search',
	'Search_invalid_ip' => 'Invalid IP address entered to Search',
	'Search_invalid_group' => 'Invalid Group entered to Search',
	'Search_invalid_date' => 'Invalid Date entered to Search',
	'Search_invalid_postcount' => 'Invalid Post Count entered to Search',
	'Search_invalid_userfield' => 'Invalid Userfield data entered',
	'Search_invalid_lastvisited' => 'Invalid data entered for Last Visited search',
	'Search_invalid_language' => 'Invalid Language Selected',
	'Search_invalid_style' => 'Invalid Style Selected',
	'Search_invalid_timezone' => 'Invalid Timezone Selected',
	'Search_invalid_moderators' => 'Invalid Forum Selected',
	'Search_invalid' => 'Invalid Search',
	'Search_invalid_day' => 'The day you entered was invalid',
	'Search_invalid_month' => 'The month you entered was invalid',
	'Search_invalid_year' => 'The year you entered was invalid',
	'Search_no_regexp' => 'Your database does not support Regular Expression searching.',
	'Search_for_username' => 'Searching usernames matching %s',
	'Search_for_email' => 'Searching email addresses matching %s',
	'Search_for_ip' => 'Searching IP addresses matching %s',
	'Search_for_date' => 'Searching users who joined %s %d/%d/%d',
	'Search_for_group' => 'Searching group members of %s',
	'Search_for_banned' => 'Searching banned users',
	'Search_for_admins' => 'Searching Administrators',
	'Search_for_mods' => 'Searching Moderators',
	'Search_for_disabled' => 'Searching for disabled users',
	'Search_for_disabled_pms' => 'Searching for users with disabled Private Messages',
	'Search_for_postcount_greater' => 'Searching for users with a post count greater than %d',
	'Search_for_postcount_lesser' => 'Searching for users with a post count less than %d',
	'Search_for_postcount_range' => 'Searching for users with a post count between %d and %d',
	'Search_for_postcount_equals' => 'Searching for users with a post count value of %d',
	'Search_for_userfield_icq' => 'Searching for users with an ICQ address matching %s',
	'Search_for_userfield_yahoo' => 'Searching for users with a Yahoo IM address matching %s',
	'Search_for_userfield_aim' => 'Searching for users with an AIM address matching %s',
	'Search_for_userfield_msn' => 'Searching for users with a MSN Messenger address matching %s',
	'Search_for_userfield_website' => 'Searching for users with a Website matching %s',
	'Search_for_userfield_location' => 'Searching for users with a Location matching %s',
	'Search_for_userfield_interests' => 'Searching for users with their Interests field matching %s',
	'Search_for_userfield_occupation' => 'Searching for users with their Occupation field matching %s',
	'Search_for_lastvisited_inthelast' => 'Searching for users who have visited in the last %s %s',
	'Search_for_lastvisited_afterthelast' => 'Searching for users who have visited after the last %s %s',
	'Search_for_language' => 'Searching for users who have set %s as their language',
	'Search_for_timezone' => 'Searching for users who have set GMT %s as their timezone',
	'Search_for_style' => 'Searching for users who have set %s as their style',
	'Search_for_moderators' => 'Search for moderators of the Forum -> %s',
	'Search_users_advanced' => 'Advanced User Search',
	'Search_users_explain' => 'This Module allows you to perform advanced searches for users using a wide range of criteria. Please read the descriptions under each field to understand each search option completely.',
	'Search_username_explain' => 'Perform a case insensitive search for usernames. If you would like to match part of the username, use * (an asterisk) as a wildcard. Checking the Regular Expressions box will allow you to search based on your regex pattern. <strong>Note:</strong> Regular Expressions will only work in MySQL, PostgreSQL and Oracle 10g+.',
	'Search_email_explain' => 'Enter an expression to match a user\'s email address. This is case insensitive. If you want to do a partial match, use * (an asterisk) as a wildcard. Checking the Regular Expressions box will allow you to search based on your regex pattern. <strong>Note:</strong> Regular Expressions will only work in MySQL, PostgreSQL and Oracle 10g+.',
	'Search_ip_explain' => 'Search for users who made posts using a specific ip address (xxx.xxx.xxx.xxx), wildcard (xxx.xxx.xxx.*) or range (xxx.xxx.xxx.xxx-yyy.yyy.yyy.yyy). Note: the last quad .255 is considered the range of all the IPs in that quad. If you enter 10.0.0.255, it is just like entering 10.0.0.* (No IP is assigned .255 for that matter, it is reserved). Where you may encounter this is in ranges, 10.0.0.5-10.0.0.255 is the same as "10.0.0.*" . You should really enter 10.0.0.5-10.0.0.254 .',
	'Search_users_joined' => 'Users that joined',
	'Search_users_lastvisited' => 'Users who have visited',
	'in_the_last' => 'in the last',
	'after_the_last' => 'after the last',
	'Before' => 'Before',
	'After' => 'After',
	'Search_users_joined_explain' => 'Search for users who joined Before or After (and on) a specific date. The date format is YYYY/MM/DD.',
	'Search_users_groups_explain' => 'View all members of the selected group.',
	'Administrators' => 'Administrators',
	'Banned_users' => 'Banned Users',
	'Disabled_users' => 'Disabled Users',
	'Users_disabled_pms' => 'Users with disabled PMs',
	'Search_users_misc_explain' => 'Administrators - All users with Administrator Status; Moderators - All forum moderators; Banned Users - All accounts that have been banned on these forums; Disabled Users - All users with disabled accounts (either manually disabled or never verified their email address); Users with disabled PMs - Selects users who have the Private Messages privileges removed (Done via User Management)',
	'Postcount' => 'Postcount',
	'Equals' => 'Equals',
	'Greater_than' => 'Greater than',
	'Less_than' => 'Less than',
	'Search_users_postcount_explain' => 'Search for users based on the Postcount value. You can either search using a specific value, greater than or lesser than a value or between two values. To do the range search, select "Equals" then put the beginning and ending values of the range separated by a dash (-), e.g. 10-15',
	'Userfield' => 'Userfield',
	'Search_users_userfield_explain' => 'Search for users based on various profile fields. Wildcards are supported using an asterisk (*). Checking the Regular Expressions box will allow you to search based on your regex pattern. <strong>Note:</strong> Regular Expressions will only work in MySQL, PostgreSQL and Oracle 10g+.',
	'Search_users_lastvisited_explain' => 'Search for users based on their last login date.',
	'Search_users_language_explain' => 'This will display users who have selected a specific language in their Profile',
	'Search_users_timezone_explain' => 'Users who have selected a specific timezone in their profile',
	'Search_users_style_explain' => 'Display users who have selected a specific style.',
	'Moderators_of' => 'Moderators of',
	'Search_users_moderators_explain' => 'Search for users with Moderator permissions to a specific forum. Moderator permissions are recognised either by User Permissions or by being in a Group with the right Group Permissions.',
	'Regular_expression' => 'Regular Expression?',

	'Manage' => 'Manage',
	'Search_users_new' => '%s yielded %d result(s). Perform <a href="%s">another search</a>.',
	'Banned' => 'Banned',
	'Not_banned' => 'Not Banned',
	'Search_no_results' => 'No users match your selected criteria. Please try another search. If you\'re searching the username or email address fields, for partial matches you must use the wildcard * (an asterisk).',
	'Account_status' => 'Account Status',
	'Sort_options' => 'Sort options:',
	'Last_visit' => 'Last Visit',
	'Day' => 'Day',
	)
);

?>