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
* Chris Lennert - (calennert@users.sourceforge.net) - (http://lennertmods.sourceforge.net)
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
	'BBUS_Mod_Title' => 'Board Usage Statistics',
	'BBUS_Misc' => 'Miscellaneous',

	'BBUS_ColHeader_PostRate' => 'Post Rate',
	'BBUS_ColHeader_PctUTP' => '%UTP',
	'BBUS_ColHeader_PctUTUP' => '%UTUP',
	'BBUS_ColHeader_NewTopics' => 'New Topics',
	'BBUS_ColHeader_TopicRate' => 'Topic Rate',
	'BBUS_ColHeader_Topics_Watched' => 'Watched',
	'BBUS_ColHeader_Header' => 'Header',
	'BBUS_ColHeader_Description' => 'Description',

	'BBUS_ColHeader_Posts_Explain' => 'Total number of posts.',
	'BBUS_ColHeader_PostRate_Explain' => 'Average number of posts per day.',
	'BBUS_ColHeader_PctUTP_Explain' => 'Percentage of user\'s total posts.',
	'BBUS_ColHeader_PctUTUP_Explain' => 'Percentage of user\'s total un-pruned posts.',
	'BBUS_ColHeader_NewTopics_Explain' => 'Total number of new topics initiated by user.',
	'BBUS_ColHeader_TopicRate_Explain' => 'Average number of new topics initiated per day.',
	'BBUS_ColHeader_Topics_Watched_Explain' => 'Total number of topics watched.',

	'BBUS_Col_Descriptions_Caption' => 'Column Descriptions',

	'BBUS_Msg_NoPosts' => 'User has not posted to any forums.',
	'BBUS_Unpruned_Posts' => 'Total posts pruned',
	'BBUS_Scale_By' => 'Scale By:',

// Admin Configuration page
	'BBUS_Settings_Caption' => 'Board Usage Statistics Settings',
	'BBUS_Settings_Explain' => 'These settings allow you to configure which board users may view the board usage statistics on a user\'s Profile page and to configure various options related to the data presented on that page.',

	'BBUS_Setting_ViewLevel_Caption' => 'View Level',
	'BBUS_Setting_ViewLevel_Note' => '<i>Note</i>: Textbox is read-only.<br />Use the checkboxes to modify this value.',

	'BBUS_Setting_ViewLevel_Explain1' => 'The view level setting is a composite sum of one or more flags that collectively determine whether a given user will see the board usage statistics on the User Profile page.  <b>Use the check boxes below to enable or disable the viewing of usage stats by particular classes of users.</b><br /><br />The View Level access flags are defined as follows:<br />',

	'BBUS_Setting_ViewLevel_Explain2' => '<br />The most common setting for many boards will be 24 (16 + 8), which allows administrators and moderators to monitor board usage statistics, while all other users will be presented with only the basic User Profile page.<br /><br /> It is recommended that you restrict the viewing of statistics, particularly for large boards, to avoid performance degradation issues arising from the stats being generated for many users at the same time.',

	'BBUS_Setting_ViewOptions_Caption' => 'View Options',
	'BBUS_Setting_ViewOptions_Note' => '<i>Note</i>: Textbox is read-only.<br />Use the checkboxes to modify this value.',
	'BBUS_Setting_ViewOptions_Explain1' => 'Like View Level, the View Options setting is a sum of flags that enable and disable various features of the Board Usage Statistics Mod. <b>Use the check boxes below to enable or disable these features.</b><br /><br />The View Option flags are defined as follows:<br />',
	'BBUS_Setting_ViewOptions_Explain2' => '',

	'BBUS_Settings_Default_Post_Rate_Scaling_Caption' => 'Default Post Rate Scaling Factor',
	'BBUS_Settings_Default_Post_Rate_Scaling_Explain' => 'Sets the default scaling factor applied to values in the Post Rate stats column.',

	'BBUS_Settings_Default_Topic_Rate_Scaling_Caption' => 'Default Topic Rate Scaling Factor',
	'BBUS_Settings_Default_Topic_Rate_Scaling_Explain' => 'Sets the default scaling factor applied to values in the Topic Rate stats column.',

	'BBUS_ViewLevel_Anonymous_Caption' => 'Anonymous',
	'BBUS_ViewLevel_Self_Caption' => 'Self',
	'BBUS_ViewLevel_Users_Caption' => 'Users',
	'BBUS_ViewLevel_Moderators_Caption' => 'Moderators',
	'BBUS_ViewLevel_Admins_Caption' => 'Admins',
	'BBUS_ViewLevel_SpecialGrp_Caption' => 'Special Group',

	'BBUS_ViewLevel_Anonymous_Explain' => 'Allow anonymous users to view stats.',
	'BBUS_ViewLevel_Self_Explain' => 'Allow each user to view his/her own stats.',
	'BBUS_ViewLevel_Users_Explain' => 'Allow any users to view other users\' stats.',
	'BBUS_ViewLevel_Moderators_Explain' => 'Allow moderators to view stats.',
	'BBUS_ViewLevel_Admins_Explain' => 'Allow administrators to view stats. (Recommended)',
	'BBUS_ViewLevel_SpecialGrp_Explain' => 'Allow a designated special user group to view stats.',

	'BBUS_ViewOption_Show_All_Forums_Caption' => 'Show All Forums, regardless of user\'s post count.',
	'BBUS_ViewOption_PCTUTUP_Column_Visible_Caption' => 'Display %UTUP Column in Stats Table',
	'BBUS_ViewOption_Misc_Section_Visible_Caption' => 'Display Miscellaneous Info Section',
	'BBUS_ViewOption_Misc_TotPrunedPosts_Visible_Caption' => 'Display "Total Un-pruned Posts" in Misc Section',
	'BBUS_ViewOption_Viewer_Scalable_PR_Caption' => 'Viewer Scalable Post Rate',
	'BBUS_ViewOption_Viewer_Scalable_TR_Caption' => 'Viewer Scalable Topic Rate',

	'BBUS_ViewOption_Show_All_Forums_Explain' => '',
	'BBUS_ViewOption_PCTUTUP_Column_Visible_Explain' => '',
	'BBUS_ViewOption_Misc_Section_Visible_Explain' => '',
	'BBUS_ViewOption_Misc_TotPrunedPosts_Visible_Explain' => '',
	'BBUS_ViewOption_Viewer_Scalable_PR_Explain' => '',
	'BBUS_ViewOption_Viewer_Scalable_TR_Explain' => '',

	'BBUS_Setting_SpecialGrp_Caption' => 'Special Access Group',
	'BBUS_Setting_SpecialGrp_Explain' => 'Designates the special group that will be allowed to view board usage statistics. <i>Note</i>: This setting will only have an effect if the \'Special Group\' flag is enabled in the View Level above.',
	)
);

?>