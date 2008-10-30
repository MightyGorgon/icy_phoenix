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
	'None' => 'None',
	'Allow_Access' => 'Allow Access',

	'Jr_Admin' => 'Junior Admin',
	'Options' => 'Options',
	'Example' => 'Example',
	'Version' => 'Version',
	'Add_Arrow' => 'Add ->',
	'Super_Mod' => 'Super Moderator',
	'Update' => 'Update',
	'Modules' => 'Modules',
	'Module_Info' => 'Module Info',
	'Module_Count' => 'Module Count',
	'Modules_Owned' => '(%d Modules)',
	'Updated_Permissions' => 'Updated User Module Permissions<br />',
	'Color_Group' => 'Color Group',
	'Users_with_Access' => 'Users With Access',
	'Users_without_Access' => 'Users Without Access',
	'Check_All' => 'Select / Unselect All',
	'Cat_Check_All' => 'Category: Select / Unselect All',
	'Edit_Permissions' => 'Edit User Permissions',
	'View_Profile' => 'View User Profile',
	'Edit_User_Details' => 'Edit User Details',
	'Notes' => 'Notes',
	'Allow_View' => 'Allow User To View',
	'Start_Date' => 'Permissions First Granted On',
	'Update_Date' => 'Permissions Last Updated On',
	'Edit_Modules' => 'Edit Modules',
	'Rank' => 'Rank',
	'Allow_PM' => 'Allow PM',
	'Allow_Avatar' => 'Allow Avatar',
	'User_Active' => 'User Active',
	'User_Info' => 'User Info',
	'User_Stats' => 'User Stats',
	'Junior_Admin_Info' => 'Your Junior Admin Info',
	'Admin_Notes' => 'Admin Notes',

//Descriptions
	'Levels_Page_Desc' => 'This page allows you to define user levels.  Choose a username on the list to add it or manually enter it.  Usernames MUST be separated by a , (comma) on each list!',
	'Permissions_Page_Desc' => 'Change certain admin only user options and also edit their module list.<br />You may click on each table heading to apply sorting by that heading.',

//Errors
	'Error_Users_Table' => 'Error querying the users table.',
	'Error_Module_Table' => 'Error querying the Jr Admin module permissions table.',
	'Error_Module_ID' => 'The requested module does not exist or you are not an authorized user.',
	'Disabled_Color_Groups' => 'Colour Groups Mod not found, unable to assign a colour group.',
	'Admin_Note' => 'Notice:  This user is classified as an Administrator Level User.  Any restrictions placed here will not work until you change their access to User instead of Administrator.',
	'No_Special_Ranks' => 'No special ranks defined.',

//This is the bookmark ASCII search list!  If you have odd usernames, you should add your own ASCII search numbers.
//It uses a special format.
// Smaller-case letters are ignored also.  Don't bother listing them as everything is converted to upper case for eval.
// It searches and prepares the bookmark heading IN THE ORDER you have it below.  It will not sort lowest to highest.
//
// Item-Item2 will search the code from item to item2 AND give each their own bookmark heading (ex. A-Z)
// Item&Item2 will search the code from item to item2 BUT NOT give each their own heading, they will appear like 1-9
// You can add single entries, ie 67
// Separate entry areas by a ,
//
	'ASCII_Search_Codes' => '48&57, 65-90',

//Images
// Don't change these unless you need to
	'ASC_Image' => 'images/sort_asc.png',
	'DESC_Image' => 'images/sort_desc.png',
	)
);

?>