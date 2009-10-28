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
// Commonly Used
	'Ftr_msg_error' => 'Error',
	'Ftr_msg_success' => 'Success',

// Buttons
	'Ftr_select_button' => ' Select ',
	'Ftr_change_button' => ' Change ',
	'Ftr_delete_button' => ' Delete ',
	'Ftr_save_button' => ' Save ',

// Admin Panel
	'Ftr_admin_users' => 'FTR: Users Who Have Viewed The Topic',
	'Ftr_total_user_error' => 'Error Getting Total Users.',
	'Ftr_username' => '<strong>Username</strong>',
	'Ftr_post_date_time' => '<strong>Viewed Post Date &amp; Time</strong>',
	'Ftr_admin_user_delete' => 'FTR: Complete User Deletion',
	'Ftr_user_del_success' => 'All Users Were successfully Deleted.',
	'Ftr_save_config' => 'FTR: Save Configuration',
	'Ftr_save_config_success' => 'The New Config Settings Have Been Saved.',
	'Ftr_select_forum' => 'FTR: Select Forum',
	'Ftr_forum_choose' => '<strong>Select a Forum:</strong>',
	'Ftr_set_config' => 'FTR: Set Configuration',
	'Ftr_topic_choose' => 'Select the topic to force them to read:',
	'Ftr_message' => 'Enter a message the User(s) will receive telling them to view this topic.',
	'Ftr_config' => 'FTR: Configuration',
	'Ftr_post_changed' => '<strong>Delete FTR flags:</strong><br /> This forces \'all\' members to Re-Read the post.',
	'Ftr_current_topic' => '<strong>Current FTR Topic:</strong>',
	'Ftr_current_message' => '<strong>Current Message:</strong>',
	'Ftr_default' => 'Choose A Forum',
	'Ftr_default2' => 'Choose A Topic',

// Added in 1.0.2
	'Ftr_user_deleted' => 'User Deleted!',
	'Ftr_deactivate' => '<strong>Deactivate FTR?</strong><br /><span class="gensmall"><b>Yes</b> will turn off FTR.</span>',
	'Ftr_whos_effected' => '<strong>Who is forced to read this?</strong><br /><span class="gensmall">\'New Members\' only affect new registrations.</span>',
	'Ftr_whos_effected_a' => 'All Members',
	'Ftr_whos_effected_n' => 'New Members',
	'Ftr_deactivate_y' => 'Yes',
	'Ftr_deactivate_n' => 'No',
	'Ftr_effected_1' => 'Only New Users Will Be Forced To Read This.',
	'Ftr_effected_2' => 'All Members Will Be Forced To Read This.',
	'Ftr_active_1' => 'You Have Disabled FTR. No one Will Be Forced To Read This.',
	'Ftr_active_2' => 'You Have Activated FTR!',
	)
);

?>