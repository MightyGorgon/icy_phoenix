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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

/**
*
* @Extra credits for this file
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

$lang['BBC_IP_CREDITS_STATIC'] = '
<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="http://www.icyphoenix.com/images/logo_ip.png" alt="Icy Phoenix" title="Icy Phoenix" /></a><br />
<span style="color: #dd2222;"><b>Mighty Gorgon</b></span>&nbsp;<i>(Luca Libralato)</i><br />
<b><i>Project Manager And Main Developer</i></b><br />
<br />
<br />
<span style="color: #dd2222;"><b>The Steffen</b></span><br />
<b><i>Site Administrator</i></b><br />
<br />
<br />
<span style="color: #228822;"><b>mort</b></span><br />
<b><i>Staff Leader</i></b><br />
<br />
<br />
<span style="color: #ff5500;"><b>KasLimon</b></span><br />
<b><i>Developer</i></b><br />
<br />
<br />
<span style="color: #ff5500;"><b>Informpro</b></span><br />
<b><i>Developer</i></b><br />
<br />
<br />
<b><i>Valued Contributors</i></b><br />
<span style="color: #dd2222;"><b>Andrea75</b></span><br />
<span style="color: #228822;"><b>Artie</b></span><br />
<span style="color: #ff5500;"><b>Bicet</b></span>&nbsp;<i>(phpBB XS Developer)</i><br />
<span style="color: #880088;"><b>brandsrus</b></span><br />
<span style="color: #dd2222;"><b>buldo</b></span><br />
<span style="color: #880088;"><b>casimedicos</b></span><br />
<span style="color: #880088;"><b>Chaotic</b></span><br />
<span style="color: #ff5500;"><b>CyberAlien</b></span>&nbsp;<i>(Many Contributions)</i><br />
<span style="color: #880088;"><b>difus</b></span><br />
<span style="color: #228822;"><b>DWho</b></span><br />
<span style="color: #880088;"><b>fracs</b></span><br />
<span style="color: #880088;"><b>ganesh</b></span><br />
<span style="color: #880088;"><b>Hans</b></span><br />
<span style="color: #ff5500;"><b>hpl</b></span>&nbsp;<i>(Junior Developer)</i><br />
<span style="color: #880088;"><b>JANU1535</b></span><br />
<span style="color: #ff5500;"><b>jhl</b></span>&nbsp;<i>(Junior Developer)</i><br />
<span style="color: #228822;"><b>Joshua203</b></span><br />
<span style="color: #880088;"><b>jz</b></span><br />
<span style="color: #aaff00;"><b>KugeLSichA</b></span><br />
<span style="color: #0000bb;"><b>Limun</b></span><br />
<span style="color: #880088;"><b>Lopalong</b></span><br />
<span style="color: #880088;"><b>moreteavicar</b></span><br />
<span style="color: #880088;"><b>novice programmer</b></span><br />
<span style="color: #dd2222;"><b>ThE KuKa</b></span><br />
<span style="color: #880088;"><b>Tom</b></span><br />
<span style="color: #228822;"><b>TopoMotoV3X</b></span><br />
<span style="color: #aaff00;"><b>TuningBEB2008</b></span><br />
<span style="color: #880088;"><b>z3d0</b></span><br />
<span style="color: #880088;"><b>Zuker</b></span><br />
<br />
';

$lang['BBC_IP_CREDITS'] = '<div class="center-block"><marquee behavior="scroll" direction="up" scrolldelay="120">' . $lang['BBC_IP_CREDITS_STATIC'] . '</marquee></div>';

$lang = array_merge($lang, array(
	'Home' => 'Home',
	'Forum' => 'Forum',
	'Category' => 'Category',
	'Topic' => 'Topic',
	'Topics' => 'Topics',
	'Replies' => 'Replies',
	'Views' => 'Views',
	'Post' => 'Post',
	'Posts' => 'Posts',
	'Posted' => 'Posted',
	'Username' => 'Username',
	'Password' => 'Password',
	'Email' => 'Email',
	'Poster' => 'Poster',
	'Author' => 'Author',
	'Time' => 'Time',
	'Hours' => 'Hours',
	'Message' => 'Message',

	'1_DAY' => '1 Day',
	'7_DAYS' => '7 Days',
	'2_WEEKS' => '2 Weeks',
	'1_MONTH' => '1 Month',
	'3_MONTHS' => '3 Months',
	'6_MONTHS' => '6 Months',
	'1_YEAR' => '1 Year',

	'Go' => 'Go',
	'Jump_to' => 'Jump to',
	'Submit' => 'Submit',
	'Reset' => 'Reset',
	'Cancel' => 'Cancel',
	'Preview' => 'Preview',
	'Confirm' => 'Confirm',
	'Spellcheck' => 'Spellcheck',
	'Yes' => 'Yes',
	'No' => 'No',
	'Enabled' => 'Enabled',
	'Disabled' => 'Disabled',
	'Error' => 'Error',

	'GO' => 'Go',
	'JUMP_TO' => 'Jump to',
	'SUBMIT' => 'Submit',
	'UPDATE' => 'Update',
	'RESET' => 'Reset',
	'CANCEL' => 'Cancel',
	'PREVIEW' => 'Preview',
	'CONFIRM' => 'Confirm',
	'YES' => 'Yes',
	'NO' => 'No',
	'ENABLED' => 'Enabled',
	'DISABLED' => 'Disabled',
	'ERROR' => 'Error',

	'Next' => 'Next',
	'Previous' => 'Previous',
	'Goto_page' => 'Goto page',
	'Joined' => 'Joined',
	'IP_Address' => 'IP Address',

	'Select_forum' => 'Select a forum',
	'View_latest_post' => 'View latest post',
	'View_newest_post' => 'View newest post',
	'Page_of' => 'Page <b>%d</b> of <b>%d</b>', // Replaces with: Page 1 of 2 for example

	'AIM' => 'AIM Address',
	'ICQ' => 'ICQ Number',
	'JABBER' => 'Jabber',
	'MSNM' => 'MSN Live',
	'SKYPE' => 'Skype',
	'YIM' => 'Yahoo Messenger',

	'Forum_Index' => 'Forum',  // eg. sitename Forum Index, %s can be removed if you prefer

	'Post_new_topic' => 'Post new topic',
	'Reply_to_topic' => 'Reply to topic',
	'Reply_with_quote' => 'Reply with quote',

	'Click_return_login' => 'Click %sHere%s to try again',
	'Click_return_index' => 'Click %sHere%s to return to the Index',
	'Click_return_forum' => 'Click %sHere%s to return to the forum',
	'Click_return_topic' => 'Click %sHere%s to return to the topic', // %s's here are for uris, do not remove!
	'Click_return_viewtopic' => 'Click %sHere%s to return to the topic',
	'Click_return_modcp' => 'Click %sHere%s to return to the Moderator Control Panel',
	'Click_return_profile' => 'Click %sHere%s to return to User Profile',
	'Click_return_preferences' => 'Click %sHere%s to return to Preferences',
	'Click_return_group' => 'Click %sHere%s to return to group information',
	'Click_return_drafts' => 'Click %sHere%s to return to Drafts',
	'Click_return_inbox' => 'Click %sHere%s to return to your Inbox',
	'Click_view_message' => 'Click %sHere%s to view your message',
	'Click_view_privmsg' => 'Click %sHere%s to visit your Inbox',

	'Admin_panel' => 'ACP',

	'Board_disabled' => 'Sorry, but this site is currently unavailable. Please try again later.',

// Global Header strings
	'Registered_users' => 'Registered Users:',
	'Browsing_forum' => 'Users browsing this forum:',
	'Online_users_zero_total' => 'In total there are <b>0</b> users online: ',
	'Online_users_total' => 'In total there are <b>%d</b> users online: ',
	'Online_user_total' => 'In total there is <b>%d</b> user online: ',
	'AC_Online_users_zero_total' => 'In total there are <b>0</b> users in chat: ',
	'AC_Online_users_total' => 'In total there are <b>%d</b> users in chat: ',
	'AC_Online_user_total' => 'In total there is <b>1</b> user in chat: ',
	'Reg_users_zero_total' => '0 Registered, ',
	'Reg_users_total' => '%d Registered, ',
	'Reg_user_total' => '%d Registered, ',
	'Hidden_users_zero_total' => '0 Hidden and ',
	'Hidden_user_total' => '%d Hidden and ',
	'Hidden_users_total' => '%d Hidden and ',
	'Guest_users_zero_total' => '0 Guests',
	'Guest_users_total' => '%d Guests',
	'Guest_user_total' => '%d Guest',
	'Record_online_users' => 'Most users ever online was <b>%s</b> on %s', // first %s = number of users, second %s is the date.

	'Admin_online_color' => '%sAdministrator%s',
	'Mod_online_color' => '%sModerator%s',

	'You_last_visit' => 'You last visited on %s', // %s replaced by date/time
	'Current_time' => 'The time now is %s', // %s replaced by time

	'Search_new' => 'View posts since last visit',
	'Search_your_posts' => 'View your posts',
	'Search_unanswered' => 'View unanswered posts',

	'Register' => 'Register',
	'Profile' => 'Profile',
	'Edit_profile' => 'Edit your profile',
	'Search' => 'Search',
	'Memberlist' => 'Memberlist',
	'FAQ' => 'FAQ',
	'KB_title' => 'Knowledge Base',
	'BBCode_guide' => 'BBCode Guide',
	'Usergroups' => 'Usergroups',
	'Last_Post' => 'Last Post',
	'Moderator' => 'Moderator',
	'Moderators' => 'Moderators',

// Stats block text
	'Posted_articles_zero_total' => 'Our users have posted a total of <b>0</b> articles', // Number of posts
	'Posted_articles_total' => 'Our users have posted a total of <b>%d</b> articles', // Number of posts
	'Posted_article_total' => 'Our users have posted a total of <b>%d</b> article', // Number of posts
	'Registered_users_zero_total' => 'We have <b>0</b> registered users', // # registered users
	'Registered_users_total' => 'We have <b>%d</b> registered users', // # registered users
	'Registered_user_total' => 'We have <b>%d</b> registered user', // # registered users
	'Newest_user' => 'The newest registered user is <b>%s%s%s</b>', // a href, username, /a

	'No_new_posts_last_visit' => 'No new posts since your last visit',

	'No_new_posts_global_announcement' => 'No new [ Global Ann. ]',
	'New_posts_global_announcement' => 'New [ Global Ann. ]',
	'No_new_posts_announcement' => 'No new [ Announcement ]',
	'New_posts_announcement' => 'New [ Announcement ]',
	'No_new_posts_sticky' => 'No new [ Sticky ]',
	'New_posts_sticky' => 'New [ Sticky ]',
	'No_new_posts_locked' => 'No new [ Locked ]',
	'New_posts_locked' => 'New [ Locked ]',
	'No_new_posts' => 'No new posts',
	'New_posts' => 'New posts',
	'New_post' => 'New post',
	'No_new_posts_hot' => 'No new [ Popular ]',
	'New_posts_hot' => 'New [ Popular ]',

	'Forum_no_new_posts' => 'No new posts [Forum]',
	'Forum_new_posts' => 'New posts [Forum]',
	'Cat_no_new_posts' => 'No new posts [Category]',
	'Cat_new_posts' => 'New posts [Category]',
	'Forum_is_locked' => 'Forum is locked',

// Login
	'Enter_password' => 'Please enter your username and password to log in.',
	'Login' => 'Log in',
	'Logout' => 'Log out',
	'Forgotten_password' => 'I forgot my password',
	'AUTOLOGIN' => 'Log me on automatically each visit',
	'Error_login' => 'You have specified an incorrect or inactive username, or an invalid password.',

// Index page
	'No_Posts' => 'No Posts',
	'No_forums' => 'This board has no forums',

	'Private_Message' => 'Private Message',
	'Private_Messages' => 'Private Messages',
	'Who_is_Online' => 'Who Is Online',

	'Mark_all_forums' => 'Mark all forums read',
	'Forums_marked_read' => 'All forums have been marked read',

// Viewforum
	'View_forum' => 'View Forum',

	'Reached_on_error' => 'You have reached this page in error.',

	'Display_topics' => 'Display topics from previous',
	'ALL_TOPICS' => 'All Topics',

	'Topic_News_nb' => 'News',
	'Topic_global_announcement_nb' => 'Global Announcement',
	'Topic_Announcement_nb' => 'Announcement',
	'Topic_Sticky_nb' => 'Sticky',
	'Topic_Moved_nb' => 'Moved',
	'Topic_Poll_nb' => 'Poll',
	'Topic_Event_nb' => 'Event',
	'Topic_Announcement' => '<b>Announcement:</b>',
	'Topic_Sticky' => '<b>Sticky:</b>',
	'Topic_Moved' => '<b>Moved:</b>',
	'Topic_Poll' => '<b>Poll:</b>',
	'Topic_Event' => '<b>Event:</b>',
	'Topic_global_announcement' => '<b>Global Announcement:</b>',
	'Post_global_announcement' => 'Global Announcement',

	'Mark_all_topics' => 'Mark all topics read',
	'Topics_marked_read' => 'The topics for this forum have now been marked read',

/*
	'Rules_post_can' => 'You <b>can</b> post new topics in this forum',
	'Rules_post_cannot' => 'You <b>cannot</b> post new topics in this forum',
	'Rules_reply_can' => 'You <b>can</b> reply to topics in this forum',
	'Rules_reply_can_own' => 'You <b>can</b> reply to your topics in this forum',
	'Rules_reply_cannot' => 'You <b>cannot</b> reply to topics in this forum',
	'Rules_edit_can' => 'You <b>can</b> edit your posts in this forum',
	'Rules_edit_cannot' => 'You <b>cannot</b> edit your posts in this forum',
	'Rules_delete_can' => 'You <b>can</b> delete your posts in this forum',
	'Rules_delete_cannot' => 'You <b>cannot</b> delete your posts in this forum',
	'Rules_vote_can' => 'You <b>can</b> vote in polls in this forum',
	'Rules_vote_cannot' => 'You <b>cannot</b> vote in polls in this forum',
*/
	'Rules_post_can' => 'You <b>can</b> post new topics',
	'Rules_post_cannot' => 'You <b>cannot</b> post new topics',
	'Rules_reply_can' => 'You <b>can</b> reply to topics',
	'Rules_reply_can_own' => 'You <b>can</b> reply to your topics',
	'Rules_reply_cannot' => 'You <b>cannot</b> reply to topics',
	'Rules_edit_can' => 'You <b>can</b> edit your posts',
	'Rules_edit_cannot' => 'You <b>cannot</b> edit your posts',
	'Rules_delete_can' => 'You <b>can</b> delete your posts',
	'Rules_delete_cannot' => 'You <b>cannot</b> delete your posts',
	'Rules_vote_can' => 'You <b>can</b> vote in polls',
	'Rules_vote_cannot' => 'You <b>cannot</b> vote in polls',
	'Rules_moderate' => 'You <b>can</b> %smoderate this forum%s', // %s replaced by a href links, do not remove!

// 'No_topics_post_one' => 'There are no posts in this forum.<br />Click on the <b>Post New Topic</b> link on this page to post one.',
	'No_topics_post_one' => 'Either there are no posts in this forum, or there are no matches for the letter you selected.<br />Click on the <b>Post New Topic</b> link on this page to start a new post or select another letter.',

// Viewtopic
	'View_topic' => 'View topic',

	'Guest' => 'Guest',
	'Post_subject' => 'Post subject',
	'View_next_topic' => 'View next topic',
	'View_previous_topic' => 'View previous topic',
	'Submit_vote' => 'Submit Vote',
	'View_results' => 'View Results',

	'No_newer_topics' => 'There are no newer topics in this forum',
	'No_older_topics' => 'There are no older topics in this forum',
	'No_posts_topic' => 'No posts exist for this topic',

	'Display_posts' => 'Display posts from previous',
	'ALL_POSTS' => 'All Posts',
	'Newest_First' => 'Newest First',
	'Oldest_First' => 'Oldest First',

	'Back_to_top' => 'Back to top',
	'Back_to_bottom' => 'Page bottom',

	'Read_profile' => 'View user\'s profile',
	'Send_email' => 'Send e-mail to user',
	'Visit_website' => 'Visit poster\'s website',
	'ICQ_status' => 'ICQ Status',
	'Edit_delete_post' => 'Edit/Delete this post',
	'View_IP' => 'View IP address of poster',
	'Delete_post' => 'Delete this post',

	'wrote' => 'wrote', // proceeds the username and is followed by the quoted text
	'Quote' => 'Quote', // comes before bbcode quote output.
	'Code' => 'Code', // comes before bbcode code output.

	'Edited_time_total' => 'Last edited by %s on %s; edited %d time in total', // Last edited by me on 12 Oct 2001; edited 1 time in total
	'Edited_times_total' => 'Last edited by %s on %s; edited %d times in total', // Last edited by me on 12 Oct 2001; edited 2 times in total

	'Lock_topic' => 'Lock this topic',
	'Unlock_topic' => 'Unlock this topic',
	'Move_topic' => 'Move this topic',
	'Delete_topic' => 'Delete this topic',
	'Split_topic' => 'Split this topic',

	'Stop_watching_topic' => 'Stop watching this topic',
	'Start_watching_topic' => 'Watch this topic for replies',
	'No_longer_watching' => 'You are no longer watching this topic',
	'You_are_watching' => 'You are now watching this topic',

	'Total_votes' => 'Total Votes',

// Posting/Replying (Not private messaging!)
	'Message_body' => 'Message body',
	'Topic_review' => 'Topic review',

	'No_post_mode' => 'No post mode specified', // If posting.php is called without a mode (newtopic/reply/delete/etc, shouldn't be shown normally)

	'Post_a_new_topic' => 'Post a new topic',
	'Post_a_reply' => 'Post a reply',
	'Post_topic_as' => 'Post topic as',
	'Edit_Post' => 'Edit post',
	'Options' => 'Options',

	'PM_Read' => 'PM Read',
	'PM_Unread' => 'PM Unread',
	'PM_Replied' => 'PM Replied',

	'Post_Announcement' => 'Announcement',
	'New_post_Announcement' => 'New announcement',
	'Post_Sticky' => 'Sticky',
	'New_post_Sticky' => 'New sticky',
	'Post_Normal' => 'Normal',

	'Confirm_delete' => 'Are you sure you want to delete this post?',
	'Confirm_delete_poll' => 'Are you sure you want to delete this poll?',

	'Flood_Error' => 'You cannot make another post so soon after your last; please try again in a short while.',
	'Empty_subject' => 'You must specify a subject when posting a new topic.',
	'Empty_message' => 'You must enter a message when posting.',
	'Forum_locked' => 'This forum is locked: you cannot post, reply or edit topics.',
	'Topic_locked' => 'This topic is locked: you cannot edit posts or make replies.',
	'No_post_id' => 'You must select a post to edit',
	'No_topic_id' => 'You must select a topic to reply to',
	'No_valid_mode' => 'You can only post, reply, edit or quote messages. Please return and try again.',
	'No_such_post' => 'There is no such post. Please return and try again.',
	'Edit_own_posts' => 'Sorry, but you can only edit your own posts.',
	'Delete_own_posts' => 'Sorry, but you can only delete your own posts.',
	'Cannot_delete_replied' => 'Sorry, but you may not delete posts that have been replied to.',
	'Cannot_delete_poll' => 'Sorry, but you cannot delete an active poll.',
	'Empty_poll_title' => 'You must enter a title for your poll.',
	'To_few_poll_options' => 'You must enter at least two poll options.',
	'To_many_poll_options' => 'You have tried to enter too many poll options.',
	'Post_has_no_poll' => 'This post has no poll.',
	'Already_voted' => 'You have already voted in this poll.',
	'No_vote_option' => 'You must specify an option when voting.',

	'Add_poll' => 'Add a Poll',
	'Add_poll_explain' => 'If you do not want to add a poll to your topic, leave the fields blank.',
	'Poll_question' => 'Poll question',
	'Poll_option' => 'Poll option',
	'Add_option' => 'Add option',
	'Update' => 'Update',
	'Delete' => 'Delete',
	'Poll_for' => 'Run poll for',
	'Days' => 'Days', // This is used for the Run poll for ... Days + in admin_forums for pruning
	'Poll_for_explain' => '[ Enter 0 or leave blank for a never-ending poll ]',
	'Delete_poll' => 'Delete Poll',

	'POST_ENABLE_BBCODE' => 'Enable BBCode in this post',
	'POST_ENABLE_SMILEYS' => 'Enable Smileys in this post',
	'POST_ENABLE_HTML' => 'Enable HTML in this post',
	'POST_ENABLE_ACRO_AUTO' => 'Enable Acronyms and Autolinks in this post',
	'Disable_HTML_post' => 'Disable HTML in this post',
	'Disable_ACRO_AUTO_post' => 'Disable Acronyms and Autolinks in this post',
	'Disable_BBCode_post' => 'Disable BBCode in this post',
	'Disable_Smilies_post' => 'Disable Smileys in this post',

	'HTML_is_ON' => 'HTML is <u>ON</u>',
	'HTML_is_OFF' => 'HTML is <u>OFF</u>',
	'BBCode_is_ON' => '%sBBCode%s is <u>ON</u>', // %s are replaced with URI pointing to FAQ
	'BBCode_is_OFF' => '%sBBCode%s is <u>OFF</u>',
	'Smilies_are_ON' => 'Smileys are <u>ON</u>',
	'Smilies_are_OFF' => 'Smileys are <u>OFF</u>',

	'Attach_signature' => 'Attach signature (signatures can be changed in profile)',
	'Notify' => 'Notify me when a reply is posted',
	'Delete_post' => 'Delete this post',

	'Stored' => 'Your message has been entered successfully.',
	'Deleted' => 'Your message has been deleted successfully.',
	'Poll_delete' => 'Your poll has been deleted successfully.',
	'Vote_cast' => 'Your vote has been cast.',

	'Topic_reply_notification' => 'Topic Reply Notification',

	'Emoticons' => 'Smileys',
	'More_emoticons' => 'View All Smileys',

// Private Messaging
	'Private_Messaging' => 'Private Messages',

	'Login_check_pm' => 'Log in to check your private messages',
	'New_pms' => 'You have %d new PM', // You have 2 new messages
	'New_pm' => 'You have %d new PM', // You have 1 new message
	'No_new_pm' => 'You have no new PM',
	'Unread_pms' => 'You have %d unread PM',
	'Unread_pm' => 'You have %d unread PM',
	'No_unread_pm' => 'You have no unread PM',
	'You_new_pm' => 'A new private message is waiting for you in your Inbox',
	'You_new_pms' => 'New private messages are waiting for you in your Inbox',
	'You_no_new_pm' => 'No new private messages are waiting for you',

	'Unread_message' => 'Unread message',
	'Read_message' => 'Read message',

	'Read_pm' => 'Read message',
	'Post_new_pm' => 'Post message',
	'Post_reply_pm' => 'Reply to message',
	'Post_quote_pm' => 'Quote message',
	'Edit_pm' => 'Edit message',

	'Inbox' => 'Inbox',
	'Outbox' => 'Outbox',
	'Savebox' => 'Savebox',
	'Sentbox' => 'Sentbox',
	'Flag' => 'Flag',
	'Subject' => 'Subject',
	'From' => 'From',
	'To' => 'To',
	'Date' => 'Date',
	'Mark' => 'Mark',
	'Sent' => 'Sent',
	'Saved' => 'Saved',
	'Delete_marked' => 'Delete Marked',
	'Delete_all' => 'Delete All',
	'Save_marked' => 'Save Marked',
	'Download_marked' => 'Download Marked',
	'Mailbox' => 'Mailbox',
	'Save_message' => 'Save Message',
	'Delete_message' => 'Delete Message',
	'Next_privmsg' => 'Next private message',
	'Previous_privmsg' => 'Previous private message',
	'No_newer_pm' => 'There are no newer private messages.',
	'No_older_pm' => 'There are no older private messages.',
	'Display_messages' => 'Display messages from previous', // Followed by number of days/weeks/months
	'All_Messages' => 'All Messages',

	'No_messages_folder' => 'You have no messages in this folder',

	'PM_disabled' => 'Private messaging has been disabled on this board.',
	'Cannot_send_privmsg' => 'Sorry, but the administrator has prevented you from sending private messages.',
	'No_to_user' => 'You must specify a username to whom to send this message.',

	'Disable_HTML_pm' => 'Disable HTML in this message',
	'Disable_ACRO_AUTO_pm' => 'Disable Acronyms and Autolinks in this message',
	'Disable_BBCode_pm' => 'Disable BBCode in this message',
	'Disable_Smilies_pm' => 'Disable Smileys in this message',

	'Message_sent' => 'Your message has been sent.',

	'Send_a_new_message' => 'Send a new private message',
	'Send_a_reply' => 'Reply to a private message',
	'Edit_message' => 'Edit private message',

	'Notification_subject' => 'New Private Message has arrived!',

	'FIND_USERNAME' => 'Find a username',
	'Find' => 'Find',
	'No_match' => 'No matches found.',

	'No_post_id' => 'No post ID was specified',
	'No_such_folder' => 'No such folder exists',
	'No_folder' => 'No folder specified',

	'MARK_ALL' => 'Mark all',
	'UNMARK_ALL' => 'Unmark all',

	'Confirm_delete_pm' => 'Are you sure you want to delete this message?',
	'Confirm_delete_pms' => 'Are you sure you want to delete these messages?',

	'Inbox_size' => 'Inbox [%d%% full]', // eg. Your Inbox is 50% full
	'Sentbox_size' => 'Sentbox [%d%% full]',
	'Savebox_size' => 'Savebox [%d%% full]',

// Profiles/Registration
	'Viewing_user_profile' => 'Viewing profile :: %s', // %s is username
	'About_user' => 'All about %s', // %s is username
//Start Quick Administrator User Options and Information MOD
	'Quick_admin_options' => 'Quick Administrator User Options and Info',
	'Admin_edit_profile' => 'Edit User\'s Profile',
	'Admin_edit_permissions' => 'Edit User\'s Permissions',
	'User_active' => 'User <b>is</b> active',
	'User_not_active' => 'User <b>is not</b> active',
	'Username_banned' => 'User <b>is</b> banned',
	'Username_not_banned' => 'User <b>is not</b> banned',
	'USER_BAN' => 'Ban',
	'USER_UNBAN' => 'Unban',
	'User_email_banned' => 'User\'s email [ %s ] <b>is</b> banned',
	'User_email_not_banned' => 'User\'s email <b>is not</b> banned',
//End Quick Administrator User Options and Information MOD
	'Preferences' => 'Preferences',
	'Items_required' => 'Items marked with a * are required unless stated otherwise.',
	'Registration_info' => 'Registration Information',
	'Profile_info' => 'Profile Information',
	'Profile_info_warn' => 'This information will be publicly viewable',
	'Avatar_panel' => 'Avatar Control Panel',
	'Avatar_gallery' => 'Avatar Gallery',
	'NO_AVATAR_GALLERY' => 'No Avatar Gallery Available',

	'Website' => 'Website',
	'Location' => 'Location',
	'Contact' => 'Contact',
	'Email_address' => 'E-mail address',
	'Email' => 'E-mail',
	'Send_private_message' => 'Send private message',
	'Hidden_email' => '[ Hidden ]',
	'Search_user_posts' => 'Search for posts by this user',
	'Interests' => 'Interests',
	'Occupation' => 'Occupation',
	'Poster_rank' => 'Poster rank',

	'Total_posts' => 'Total posts',
	'User_post_pct_stats' => '%.2f%% of total', // 1.25% of total
	'User_post_day_stats' => '%.2f posts per day', // 1.5 posts per day
	'Search_user_posts' => 'Find all posts by %s', // Find all posts by username
	'Search_user_topics_started' => 'Find all topics started by %s', // Find all topics started by username

	'Wrong_Profile' => 'You cannot modify a profile that is not your own.',

// Invision View Profile - BEGIN
	'Invision_Active_Stats' => 'Stats',
	'Invision_Communicate' => 'Communicate',
	'Invision_Info' => 'Information',
	'Invision_Member_Group' => 'Member Group',
	'Invision_Member_Title' => 'Member Title',
	'Invision_Most_Active' => 'Most Active In',
	'Invision_Most_Active_Posts' => '%s posts in this forum',
	'Invision_Details' => 'Posting Details',
	'Invision_PPD_Stats' => 'Posts Per Day',
	'Invision_Signature' => 'Signature',
	'Invision_Website' => 'Home Page',
	'Invision_Total_Posts' => 'Total Cumulative Posts',
	'Invision_User_post_pct_stats' => '(%.2f%% of total forum posts)',
	'Invision_User_post_day_stats' => '%.2f posts per day',
	'Invision_Search_user_posts' => 'Find all posts by this member',
	'Invision_Posting_details' => 'Posting Details',
// Invision View Profile - END

	'Only_one_avatar' => 'Only one type of avatar can be specified',
	'File_no_data' => 'The file at the URL you gave contains no data',
	'No_connection_URL' => 'A connection could not be made to the URL you gave',
	'Incomplete_URL' => 'The URL you entered is incomplete',
	'Wrong_remote_avatar_format' => 'The URL of the remote avatar is not valid',
	'No_send_account_inactive' => 'Sorry, but your password cannot be retrieved because your account is currently inactive. Please contact the forum administrator for more information.',

	'Always_smile' => 'Always enable Smileys',
	'Always_html' => 'Always allow HTML',
	'Always_bbcode' => 'Always allow BBCode',
	'Always_add_sig' => 'Always attach my signature',
	'Always_notify' => 'Always notify me of replies',
	'Always_notify_explain' => 'Sends an e-mail when someone replies to a topic you have posted in. This can be changed whenever you post.',

	'Board_style' => 'Style',
	'Board_lang' => 'Language',
	'No_themes' => 'No Themes In database',
	'Timezone' => 'Timezone',
	'Date_format' => 'Date format',
	'Date_format_explain' => 'The syntax used is identical to the PHP <a href=\'http://www.php.net/date\' target=\'_other\'>date()</a> function.',
	'Signature' => 'Signature',
	'Signature_explain' => 'This is a block of text that can be added to posts you make. There is a %d character limit',
	'Public_view_email' => 'Always show my e-mail address',

	'Current_password' => 'Current password',
	'New_password' => 'New password',
	'Confirm_password' => 'Confirm password',
	'Confirm_password_explain' => 'You must confirm your current password if you wish to change it or alter your e-mail address',
	'password_if_changed' => 'You only need to supply a password if you want to change it',
	'password_confirm_if_changed' => 'You only need to confirm your password if you changed it above',

	'Avatar' => 'Avatar',
	'Avatar_explain' => 'Displays a small graphic image below your details in posts. Only one image can be displayed at a time. The dimensions of the image are restricted to a maximum of %d pixels wide, and %d pixels high. Uploaded avatars have a file size limit of %d KB, and must be less than or equal to the maximum dimensions. Remotely hosted avatars will be automatically scaled to fit these dimensions.',
	'Upload_Avatar_file' => 'Upload Avatar from your machine',
	'Upload_Avatar_URL' => 'Upload Avatar from a URL',
	'Upload_Avatar_URL_explain' => 'Enter the URL of the location containing the Avatar image, it will be copied to this site.',
	'Pick_local_Avatar' => 'Select Avatar from the gallery',
	'Link_remote_Avatar' => 'Link to off-site Avatar',
	'Link_remote_Avatar_explain' => 'Enter the URL of the location containing the Avatar image you wish to link to.',
	'Avatar_URL' => 'URL of Avatar Image',
	'Select_from_gallery' => 'Select Avatar from gallery',
	'View_avatar_gallery' => 'Show gallery',

	'Select_avatar' => 'Select avatar',
	'Return_profile' => 'Cancel avatar',
	'Select_category' => 'Select category',

	'Delete_Image' => 'Delete Image',
	'Current_Image' => 'Current Image',

	'Notify_on_privmsg' => 'Notify on new Private Message',
	'Popup_on_privmsg' => 'Pop up window on new Private Message',
	'Popup_on_privmsg_explain' => 'Some templates may open a new window to inform you when new private messages arrive.',
	'Hide_user' => 'Hide your online status',

	'Profile_updated' => 'Your profile has been updated',
	'Profile_updated_inactive' => 'Your profile has been updated. However, you have changed vital details, thus your account is now inactive. Check your e-mail to find out how to reactivate your account, or if admin activation is required, wait for the administrator to reactivate it.',

	'Password_mismatch' => 'The passwords you entered did not match.',
	'Current_password_mismatch' => 'The current password you supplied does not match that stored in the database.',
	'Password_long' => 'Your password must be no more than 32 characters.',
	'Username_taken' => 'Sorry, but this username has already been taken.',
	'Username_invalid' => 'Sorry, but this username contains an invalid character such as \'.',
	'Username_disallowed' => 'Sorry, but this username has been disallowed.',
	'Email_taken' => 'Sorry, but that e-mail address is already registered to a user.',
	'Email_banned' => 'Sorry, but this e-mail address has been banned.',
	'Email_invalid' => 'Sorry, but this e-mail address is invalid.',
	'Signature_too_long' => 'Your signature is too long.',
	'Fields_empty' => 'You must fill in the required fields.',
	'Avatar_filetype' => 'The avatar filetype must be .jpg, .gif or .png',
	'Avatar_filesize' => 'The avatar image file size must be less than %d KB', // The avatar image file size must be less than 6 KB
	'Avatar_imagesize' => 'The avatar must be less than %d pixels wide and %d pixels high',

	'Welcome_subject' => 'Welcome to %s Forums', // Welcome to my.com forums
	'New_account_subject' => 'New user account',
	'Account_activated_subject' => 'Account Activated',

	'Account_added' => 'Thank you for registering. Your account has been created. You may now log in with your username and password',
	'Account_inactive' => 'Your account has been created. However, this forum requires account activation. An activation key has been sent to the e-mail address you provided. Please check your e-mail for further information',
	'Account_inactive_admin' => 'Your account has been created. However, this forum requires account activation by the administrator. An e-mail has been sent to them and you will be informed when your account has been activated',
	'Account_active' => 'Your account has now been activated. Thank you for registering',
	'Account_active_admin' => 'The account has now been activated',
	'Reactivate' => 'Reactivate your account!',
	'Already_activated' => 'You have already activated your account',
	'COPPA' => 'Your account has been created but has to be approved. Please check your e-mail for details.',

	'Registration' => 'Registration Agreement Terms',
	'Reg_agreement' => 'While the administrators and moderators of this forum will attempt to remove or edit any generally objectionable material as quickly as possible, it is impossible to review every message. Therefore you acknowledge that all posts made to these forums express the views and opinions of the author and not the administrators, moderators or webmaster (except for posts by these people) and hence will not be held liable.<br /><br />You agree not to post any abusive, obscene, vulgar, slanderous, hateful, threatening, sexually-oriented or any other material that may violate any applicable laws. Doing so may lead to you being immediately and permanently banned (and your service provider being informed). The IP address of all posts is recorded to aid in enforcing these conditions. You agree that the webmaster, administrator and moderators of this forum have the right to remove, edit, move or close any topic at any time should they see fit. As a user you agree to any information you have entered above being stored in a database. While this information will not be disclosed to any third party without your consent the webmaster, administrator and moderators cannot be held responsible for any hacking attempt that may lead to the data being compromised.<br /><br />This forum system uses cookies to store information on your local computer. These cookies do not contain any of the information you have entered above; they serve only to improve your viewing pleasure. The e-mail address is used only for confirming your registration details and password (and for sending new passwords should you forget your current one).<br /><br />By clicking &quot;<i>I agree to these terms</i>&quot; you agree to be bound by these conditions. Also be aware that your personal data will be treated in respect to the current laws in the country of the site owner.<br /><br />In order to use the Services, you must first agree to the Terms. You cannot use the Services if you do not accept the Terms.<br /><br />You can accept the Terms by either clicking to accept or agree to the Terms or by actually using the Services. In this case, you understand and agree that this website will treat your use of the Services as acceptance of the Terms from that point onwards.<br /><br />Please read our policies about data protections: <a href="privacy_policy.php">Privacy Policy</a> e <a href="cookie_policy.php.php">Cookie Policy</a>.<br /><br />',

	'Agreement' => 'Agreement',
	'Agree_under_13' => 'I agree to these terms and am <b>under</b> 13 years of age',
	'Agree_over_13' => 'I agree to these terms',
	'Agree_not' => 'I do not agree to these terms',
	'Agree_checkbox' => 'I specifically agree to these terms',
	'Agree_checkbox_Error' => 'You need to check the "AGREEMENT" box at the bottom of the page to register!',

	'Wrong_activation' => 'The activation key you supplied does not match any in the database.',
	'Send_password' => 'Send me a new password',
	'Password_updated' => 'A new password has been created; please check your e-mail for details on how to activate it.',
	'No_email_match' => 'The e-mail address you supplied does not match the one listed for that username.',
	'New_password_activation' => 'New password activation',
	'Password_activated' => 'Your account has been reactivated. To log in, please use the password supplied in the e-mail you received.',

	'Send_email_msg' => 'Send an e-mail message',
	'No_user_specified' => 'No user was specified',
	'User_prevent_email' => 'This user does not wish to receive e-mail. Try sending them a private message.',
	'User_not_exist' => 'That user does not exist',
	'CC_email' => 'Send a copy of this e-mail to yourself',
	'Email_message_desc' => 'This message will be sent as plain text, so do not include any HTML or BBCode. The return address for this message will be set to your e-mail address.',
	'Flood_email_limit' => 'You cannot send another e-mail at this time. Try again later.',
	'Recipient' => 'Recipient',
	'Email_sent' => 'The e-mail has been sent.',
	'Send_Email' => 'Send e-mail',
	'Empty_sender_email' => 'You must specify an email sender address.',
	'Empty_subject_email' => 'You must specify a subject for the e-mail.',
	'Empty_message_email' => 'You must enter a message to be e-mailed.',

// Visual confirmation system strings
	'CONFIRM_CODE_WRONG' => 'The confirmation code you entered was incorrect',
	'TOO_MANY_ATTEMPTS' => 'You have exceeded the number of attempts allowed for this session. Please try again later.',
	'CONFIRM_CODE_IMPAIRED' => 'If you are visually impaired or cannot otherwise read this code please contact the %sAdministrator%s for help.',
	'CONFIRM_CODE' => 'Confirmation code',
	'CONFIRM_CODE_EXPLAIN' => 'Enter the code exactly as you see it. There is no ZERO.',

// Memberlist
	'Select_sort_method' => 'Select sort method',
	'Sort' => 'Sort',
	'SORT_TOP_TEN' => 'Top Ten Posters',
	'SORT_JOINED' => 'Joined Date',
	'SORT_USERNAME' => 'Username',
	'SORT_LOCATION' => 'Location',
	'SORT_POSTS' => 'Total posts',
	'SORT_EMAIL' => 'Email',
	'SORT_WEBSITE' => 'Website',
	'Sort_Ascending' => 'Ascending',
	'Sort_Descending' => 'Descending',
	'Order' => 'Order',

// Group control panel
	'Group_Control_Panel' => 'Group Control Panel',
	'Group_member_details' => 'Group Membership Details',
	'Group_member_join' => 'Join a Group',

	'Group_Information' => 'Group Information',
	'Group_name' => 'Group name',
	'Group_description' => 'Group description',
	'Group_membership' => 'Group membership',
	'Group_Members' => 'Group Members',
	'Group_Moderator' => 'Group Leader',
	'Pending_members' => 'Pending Members',

	'Group_type' => 'Group type',
	'Group_open' => 'Open group',
	'Group_closed' => 'Closed group',
	'Group_hidden' => 'Hidden group',

	'Current_memberships' => 'Current Memberships',
	'Non_member_groups' => 'Non-Member Groups',
	'Memberships_pending' => 'Memberships Pending',
	'MEMBERSHIP_PENDING' => 'Membership Pending',

	'No_groups_exist' => 'No Groups Exist',
	'Group_not_exist' => 'That user group does not exist',

	'Join_group' => 'Join Group',
	'No_group_members' => 'This group has no members',
	'Group_hidden_members' => 'This group is hidden; you cannot view its membership',
	'No_pending_group_members' => 'This group has no pending members',
	'Group_joined' => 'You have successfully subscribed to this group.<br />You will be notified when your subscription is approved by the group leader.',
	'Group_request' => 'A request to join your group has been made.',
	'Group_approved' => 'Your request has been approved.',
	'Group_added' => 'You have been added to this usergroup.',
	'GROUP_ADDED_USER' => 'User added successfully to the group.',
	'GROUP_REMOVED_USER' => 'User removed successfully from the group.',
	'Already_member_group' => 'You are already a member of this group',
	'User_is_member_group' => 'User is already a member of this group',
	'Group_type_updated' => 'Successfully updated group type.',
	'Could_not_add_user' => 'The user you selected does not exist.',
	'Could_not_anon_user' => 'You cannot make Anonymous a group member.',
	'Confirm_unsub' => 'Are you sure you want to un-subscribe from this group?',
	'CONFIRM_UNSUB_USER' => 'Are you sure you want to remove the user from this group?',
	'Confirm_unsub_pending' => 'Your subscription to this group has not yet been approved; are you sure you want to un-subscribe?',
	'Unsub_success' => 'You have been un-subscribed from this group.',
	'Approve_selected' => 'Approve Selected',
	'Deny_selected' => 'Deny Selected',
	'Not_logged_in' => 'You must be logged in to join a group.',
	'Remove_selected' => 'Remove Selected',
	'Add_member' => 'Add Member',
	'Not_group_moderator' => 'You are not this group\'s leader, therefore you cannot perform that action.',
	'Login_to_join' => 'Log in to join or manage group memberships',
	'This_open_group' => 'This is an open group: click to request membership',
	'This_closed_group' => 'This is a closed group: %s',
	'This_hidden_group' => 'This is a hidden group: %s',
	'Member_this_group' => 'You are a member of this group',
	'Pending_this_group' => 'Your membership of this group is pending',
	'Are_group_moderator' => 'You are the group moderator',
	'None' => 'None',
	'Subscribe' => 'Subscribe',
	'Unsubscribe' => 'Un-subscribe',
	'View_Information' => 'View Information',

// Search
	'Search_query' => 'Search Query',
	'Search_options' => 'Search Options',

	'Search_keywords' => 'Search for Keywords',
	'Search_keywords_explain' => 'You can use <u>AND</u> to define words which must be in the results, <u>OR</u> to define words which may be in the result and <u>NOT</u> to define words which should not be in the result. Use * as a wildcard for partial matches',
	'Search_author' => 'Search for Author',
	'Search_author_explain' => 'Use * as a wildcard for partial matches',
	'Search_author_topic_starter' => 'Search only topics started by this author',

	'Search_for_any' => 'Search for any terms or use query as entered',
	'Search_for_all' => 'Search for all terms',
	'Search_title_msg' => 'Search topic title and message text',
	'Search_title_only' => 'Search topic title only',
	'Search_msg_only' => 'Search message text only',

	'Return_first' => 'Return first', // followed by xxx characters in a select box
	'characters_posts' => 'characters of posts',

	'Search_previous' => 'Search previous', // followed by days, weeks, months, year, all in a select box

	'Sort_by' => 'Sort by',
	'Sort_Time' => 'Post Time',
	'Sort_Post_Subject' => 'Post Subject',
	'Sort_Topic_Title' => 'Topic Title',
	'Sort_Author' => 'Author',
	'Sort_Forum' => 'Forum',

	'Display_results' => 'Display results as',
	'All_available' => 'All available',
	'No_searchable_forums' => 'You do not have permission to search any forum on this site.',

	'No_search_match' => 'No posts met your search criteria',
	'Found_search_match' => 'Search found %d match', // eg. Search found 1 match
	'Found_search_matches' => 'Search found %d matches', // eg. Search found 24 matches

	'Close_window' => 'Close Window',

// Auth related entries
// Note the %s will be replaced with one of the following 'user' arrays
	'Sorry_auth_announce' => 'Sorry, but only %s can post announcements in this forum.',
	'Sorry_auth_sticky' => 'Sorry, but only %s can post sticky messages in this forum.',
	'Sorry_auth_read' => 'Sorry, but only %s can read topics in this forum.',
	'Sorry_auth_post' => 'Sorry, but only %s can post topics in this forum.',
	'Sorry_auth_reply' => 'Sorry, but only %s can reply to posts in this forum.',
	'Sorry_auth_edit' => 'Sorry, but only %s can edit posts in this forum.',
	'Sorry_auth_delete' => 'Sorry, but only %s can delete posts in this forum.',
	'Sorry_auth_vote' => 'Sorry, but only %s can vote in polls in this forum.',

// These replace the %s in the above strings
	'Auth_Anonymous_Users' => '<b>anonymous users</b>',
	'Auth_Registered_Users' => '<b>registered users</b>',
	'Auth_Self_Users' => '<b>specific users</b>',
	'Auth_Users_granted_access' => '<b>users granted special access</b>',
	'Auth_Moderators' => '<b>moderators</b>',
	'Auth_Administrators' => '<b>administrators</b>',

	'Not_Moderator' => 'You are not a moderator of this forum.',
	'Not_Authorized' => 'Not Authorized',

	'You_been_banned' => 'You have been banned from this website.<br />Please contact the webmaster or the administrator for more information.',

// Viewonline
	'Reg_users_zero_online' => 'There are 0 Registered users and ', // There are 5 Registered and
	'Reg_users_online' => 'There are %d Registered users and ', // There are 5 Registered and
	'Reg_user_online' => 'There is %d Registered user and ', // There is 1 Registered and
	'Hidden_users_zero_online' => '0 Hidden users online', // 6 Hidden users online
	'Hidden_users_online' => '%d Hidden users online', // 6 Hidden users online
	'Hidden_user_online' => '%d Hidden user online', // 6 Hidden users online
	'Guest_users_online' => 'There are %d Guest users online', // There are 10 Guest users online
	'Guest_users_zero_online' => 'There are 0 Guest users online', // There are 10 Guest users online
	'Guest_user_online' => 'There is %d Guest user online', // There is 1 Guest user online
	'No_users_browsing' => 'There are no users currently browsing this forum',

	'ONLINE_EXPLAIN' => 'This data is based on users active over the past ' . intval(ONLINE_REFRESH / 60) . ' minutes',
	'ONLINE_TODAY' => 'This data is based on users active today',

	'Forum_Location' => 'Forum Location',
	'Last_updated' => 'Last Updated',

	'Forum_index' => 'Forum index',
	'Portal' => 'Home Page',
	'Logging_on' => 'Logging in',
	'Posting_message' => 'Posting a message',
	'Searching_forums' => 'Searching forums',
	'Viewing_profile' => 'Viewing profile',
	'Viewing_HACKSLIST' => 'Viewing credits',
	'Viewing_online' => 'Viewing who is online',
	'Viewing_member_list' => 'Viewing memberlist',
	'Viewing_priv_msgs' => 'Viewing private messages',
	'Viewing_FAQ' => 'Viewing FAQ',
	'Viewing_KB' => 'Viewing KB',
	'Viewing_RSS' => 'RSS Feed',


// Moderator Control Panel
	'Mod_CP' => 'Moderator Control Panel',
	'Mod_CP_explain' => 'Perform mass moderation operations on this forum. You can lock, unlock, move or delete any number of topics.',

	'Select' => 'Select',
	'Delete' => 'Delete',
	'Move' => 'Move',
	'Copy' => 'Copy',
	'Lock' => 'Lock',
	'Unlock' => 'Unlock',

	'Topics_Removed' => 'The selected topics have been successfully removed from the database.',
	'Topics_Locked' => 'The selected topics have been locked.',
	'Topics_Moved' => 'The selected topics have been moved.',
	'Topics_Unlocked' => 'The selected topics have been unlocked.',
	'No_Topics_Moved' => 'No topics were moved.',

	'Confirm_delete_topic' => 'Are you sure you want to remove the selected topics?',
	'Confirm_lock_topic' => 'Are you sure you want to lock the selected topics?',
	'Confirm_unlock_topic' => 'Are you sure you want to unlock the selected topics?',
	'Confirm_move_topic' => 'Are you sure you want to move the selected topics?',

	'Move_to_forum' => 'Move to forum',
	'Leave_shadow_topic' => 'Leave shadow topic in old forum.',

	'Split_Topic' => 'Split Topic Control Panel',
	'Split_Topic_explain' => 'Split a topic in two, either by selecting the posts individually or by splitting at a selected post',
	'Split_title' => 'New topic title',
	'Split_forum' => 'Forum for new topic',
	'Split_posts' => 'Split selected posts',
	'Split_after' => 'Split from selected post',
	'Topic_split' => 'The selected topic has been split successfully',

	'Too_many_error' => 'You have selected too many posts. You can only select one post to split a topic after!',

	'None_selected' => 'You have not selected any topics to perform this operation on. Please go back and select at least one.',
	'New_forum' => 'New forum',

	'This_posts_IP' => 'IP address for this post',
	'Other_IP_this_user' => 'Other IP addresses this user has posted from',
	'Users_this_IP' => 'Users posting from this IP address',
	'IP_info' => 'IP Information',
	'Lookup_IP' => 'Look up IP address',
	'WHOIS_IP' => 'WHOIS',

// Errors (not related to a specific failure on a page)
	'Information' => 'Information',
	'Critical_Information' => 'Critical Information',

	'General_Error' => 'General Error',
	'Critical_Error' => 'Critical Error',
	'An_error_occured' => 'An Error Occurred',
	'A_critical_error' => 'A Critical Error Occurred',
	'Admin_reauthenticate' => 'To administer the site you must re-authenticate yourself.',

	'Topic_description' => 'Description of your topic',
// 'Description' => 'Topic Description',

	'Guestbook' => 'Guestbook',
	'Viewing_guestbook' => 'Read the guestbook',

	'Warn_new_post' => 'There is at least one new reply in this thread. Please review new replies in topic review and re-submit your post.',

	'Today_at' => '<b class="date-today">Today</b> at ',
	'Yesterday_at' => '<b class="date-yesterday">Yesterday</b> at ',
	'TODAY' => '<b class="date-today">Today</b>',
	'YESTERDAY' => '<b class="date-yesterday">Yesterday</b>',

// Birthday - BEGIN
	'Birthday' => 'Birthday',
	'No_birthday_specify' => 'None Specified',
	'Age' => 'Age',
	'Wrong_birthday_format' => 'The birthday format was entered incorrectly.',
	'Birthday_to_high' => 'Sorry, this site, does not accept users older than %d years old',
	'Birthday_require' => 'Your Birthday is required on this site',
	'Birthday_to_low' => 'Sorry, this site, does not accept users younger than %d years old',
	'Submit_date_format' => 'd-m-Y', //php date() format - Note: ONLY d, m and Y may be used and SHALL ALL be used (different separators are accepted)
	'Birthday_greeting_today' => 'We would like to wish you congratulations on reaching %s years old today.',//%s is substituted with the users age
	'Birthday_greeting_prev' => 'We would like to give you belated congratulations for becoming %s years old on the %s.',//%s is substituted with the users age, and birthday
	'Greeting_Messaging' => 'Congratulations',
	'Birthday_today' => 'Users with a birthday today:',
	'Birthday_week' => 'Users with a birthday within the next %d days:',
	'Nobirthday_week' => 'No users are having a birthday in the upcoming %d days', // %d is substituted with the number of days
	'Nobirthday_today' => 'No users have a birthday today',
	'Year' => 'Year',
	'Month' => 'Month',
	'Day' => 'Day',
// Birthday - END

// Start add - Gender MOD
	'Gender' => 'Gender',//used in users profile to display which gender he/she is
	'Male' => 'Male',
	'Female' => 'Female',
	'No_gender_specify' => 'None Specified',
	'Gender_require' => 'Your Gender is required on this site.',
// End add - Gender MOD

// Start add - Who viewed a topic MOD
	'Topic_view_users' => 'List users that have viewed this topic',
	'Topic_time' => 'Last viewed',
	'Topic_count' => 'View count',
	'Topic_view_count' => 'Topic view count',
// End add - Who viewed a topic MOD

// Start add - Yellow card admin MOD
	'Give_G_card' => 'Reactivate user',
	'Give_Y_card' => 'Give user warning #%d',
	'Give_R_card' => 'Ban this user now',
	'Ban_update_sucessful' => 'The banlist has been updated successfully',
	'Ban_update_green' => 'The user is now reactivated',
	'Ban_update_yellow' => 'The user has received a warning, and has now a total of %d warnings of a maximum %d warnings',
	'Ban_update_red' => 'The user is now banned',
	'Ban_reactivate' => 'Your account has been reactivated',
	'Ban_warning' => 'You\'ve received a warning',
	'Ban_blocked' => 'Your account is now blocked',
/*
	'Rules_ban_can' => 'You <b>can</b> ban other users in this forum',
	'Rules_greencard_can' => 'You <b>can</b> un-ban users in this forum',
	'Rules_bluecard_can' => 'You <b>can</b> report post to moderators in this forum',
*/
	'Rules_ban_can' => 'You <b>can</b> ban other users',
	'Rules_greencard_can' => 'You <b>can</b> un-ban users',
	'Rules_bluecard_can' => 'You <b>can</b> report post to moderators',
	'user_no_email' => 'The user has no email, therefore no message about this action can be sent. You should submit him/her a private message',
	'user_already_banned' => 'The selected user is already banned',
	'Ban_no_admin' => 'This user in an ADMIN and therefore cannot be warned or banned',
	'Give_b_card' => 'Report this post to the moderators of this forum',
	'Clear_b_card' => 'This post has %d blue cards now. If you press this button you will clear this',
	'No_moderators' => 'The forum has no moderators. No reports can therefore be sent!',
	'Post_reported' => 'This post has now been reported to %d moderators',
	'Post_reported_1' => 'This post has now been reported to the moderator',
	'Post_report' => 'Post Report', //Subject in email notification
	'Post_reset' => 'The blue cards for this post have now been reset',
	'Search_only_bluecards' => 'Search only among posts with blue cards',
	'Send_message' => 'Click %sHere%s to write a message to the moderators or <br />',
	'Send_PM_user' => 'Click %sHere%s to write a PM to the user or',
	'Link_to_post' => 'Click %sHere%s to go to the reported post
--------------------------------

',
	'Post_a_report' => 'Post a report',
	'Report_stored' => 'Your report has been entered successfully',
	'Send_report' => 'Click %sHere%s to go back to the original message',
	'Red_card_warning' => 'You are about to give the user: %s a red card, this will ban the user, are you sure?',
	'Yellow_card_warning' => 'You are about to give the user: %s a yellow card, this will issue a warning to the user, are you sure?',
	'Green_card_warning' => 'You are about to give the user: %s a green card, this will unban the user, are you sure?',
	'Blue_card_warning' => 'You are about to give the post a blue card, this will alert the moderators about this post, Are you sure you want to Alert the moderators about this post?',
	'Clear_blue_card_warning' => 'You are about to reset the blue card counter for this post, Do you want to continue?',
	'Warnings' => 'Warnings: %d', //shown beside users post, if any warnings given to the user
	'Banned' => 'Currently banned',//shown beside users post, if user are banned

// Last visit - BEGIN
	'Last_logon' => 'Last Visit',
	'Hidde_last_logon' => 'Hidden',
	'Never_last_logon' => 'Never',
	'Users_today_zero_total' => 'In total <b>0</b> users have visited this site today: ',
	'Users_today_total' => 'In total <b>%d</b> users have visited this site today: ',
	'User_today_total' => 'In total <b>%d</b> user has visited this site today: ',
	'Users_lasthour_explain' => ', %d of them within the last hour.',
	'Users_lasthour_none_explain' => '', //shown if no one has visited in the last hour, fill if you like

	'Years' => 'Years',
	'Year' => 'Year',
	'Weeks' => 'Weeks',
	'Week' => 'Week',
	'Day' => 'Day',
	'Total_online_time' => 'Total Online Duration',
	'Last_online_time' => 'Last Online Duration',
	'Number_of_visit' => 'Number of visits',
	'Number_of_pages' => 'Number of page hits',
// Last visit - END

	'total_site_hits_key' => '%V% Pages views since %D%.',

	'Message_too_short' => 'Message too short',

// Start add - Online/Offline/Hidden Mod
	'Online' => 'Online',
	'Offline' => 'Offline',
	'Hidden' => 'Hidden',

	'Online_status' => 'Status',
// End add - Online/Offline/Hidden Mod
	'Download' => 'Download',

//signature editor
	'sig_edit_link' => 'Signature',
	'sig_description' => 'Edit Signature (<b>Preview included</b>)',
	'sig_edit' => 'Edit Signature',
	'sig_current' => 'Current Signature',
	'sig_none' => 'No Signature available',
	'sig_save' => 'Save',
	'sig_save_message' => 'Signature saved successfully!',

	'Statistics' => 'Statistics',

// Start add - Global announcement MOD
	'Globalannounce' => 'Global Announce',
	'Globalannounce' => 'New Global Announce',
// End add - Global announcement MOD
	'Global_Announcements' => 'Global Announcements',
	'Announcements' => 'Announcements',
	'Sticky_Topics' => 'Sticky Topics',
	'Announcements_and_Sticky' => 'Announcements and Sticky Topics',
// db_update generator
	'Db_update_generator' => 'DB Update Generator',
	'Instructions' => 'Instructions',
	'SQL_instructions' => '<p>This piece of software will create PHP files that will allow you to update your database using your browser. There are a few things you must look after when you insert an SQL in the box below.<br />First of all, make sure that every SQL query is ending with a semicolon (;). If not, the file created will be faulty. Next, ensure that the tables have the "phpbb_" prefix. This will automatically be replaced with a little piece of code that enables you to use the db_update.php file on any forum regardless of what the table prefix is set to.</p>',
	'Enter_SQL' => '<strong>Enter SQL</strong>',
	'Enter_SQL_explain' => '<strong>MySQL</strong> is the world\'s most popular open source database, recognized for its speed and reliability.',
	'Output_SQL' => '<strong>SQL Output</strong>',
	'Output_SQL_explain' => 'Copy and paste the text from the text area on the right into a blank file. Save the file as <u>db_update.php</u> and upload it to your server. Run it once by accessing with your browser.<br /><br />Alternatively, click the download button to download the file directly to your computer <strong>(recommended)</strong>.',
	'Download' => 'Download',

	'TOP_POSTERS' => 'Top Posters',

// TELL A FRIEND
	'TELL_FRIEND' => 'Email to a Friend',
	'TELL_FRIEND_SENDER_USER' => 'Your Name',
	'TELL_FRIEND_SENDER_EMAIL' => 'Your Email Address',
	'TELL_FRIEND_RECEIVER_USER' => 'Your Friend\'s Name',
	'TELL_FRIEND_RECEIVER_EMAIL' => 'Your Friend\'s Email Address',
	'TELL_FRIEND_WRONG_EMAIL' => 'You have not entered a valid email address.',
	'TELL_FRIEND_MSG' => 'Your message:',
	'TELL_FRIEND_TITLE' => 'Tell A Friend',
	'TELL_FRIEND_BODY' => "Hi,\nI just read the topic &raquo;{TOPIC}&laquo; at {SITENAME} and thought you might be interested.\n\nHere is the link: {LINK}\n\nGo and read it and if you want to reply you can register for your own account if you have not done so already.",

// Begin Thanks Mod
	'thankful' => 'Thankful People',
	'thanks_to' => 'Thanks for the useful Topic',
	'thanks_end' => ':',
	'thanks_alt' => 'Thanks Topic',
	'thanks_add_rate' => 'Thanks the author for the useful topic',
	'thanked_before' => 'You have already thanked this topic',
	'thanks_add' => 'Your thanks has been given',
	'thanks_not_logged' => 'You need to log in to thank someone\'s post',
	'thanks2' => 'Thank you very much!<br />',
// End Thanks Mod

//Begin Lo-Fi Mod
	'Lofi' => 'Lo-Fi Version',
// 'Full_Version' => 'Full Version',
	'Full_Version' => 'This is a "Lo-Fi" version of our main content. To view the full version with more information, formatting and images, please click here.',
	'quote_lofi' => 'Quote',
	'edit_lofi' => 'Edit',
	'ip_lofi' => 'IP',
	'del_lofi' => 'Delete',
	'profile_lofi' => 'Profile',
	'pm_lofi' => 'PM',
	'email_lofi' => 'E-mail',
	'website_lofi' => 'Website',
	'icq_lofi' => 'ICQ',
	'aim_lofi' => 'AIM',
	'yim_lofi' => 'YIM',
	'msnm_lofi' => 'MSN',
	'quick_lofi' => 'Quick Reply',
	'new_pm_lofi' => 'Send a PM',
//End Lo-Fi Mod

// Retroactive Signature MOD
	'Retro_sig' => 'Attach my signature to my prior posts',
	'Retro_sig_explain' => 'If adding/editing your signature, Icy Phoenix normally applies this only to future posts',
	'Retro_sig_checkbox' => 'Check here to attach your signature to your previous posts as well',
// End Retro Sig MOD

	'legend' => 'Legend',
	'users' => 'Users',
//added to autogroup mod
	'No_more' => 'no more users accepted',
	'No_add_allowed' => 'automatic user addition is not allowed',
	'Join_auto' => 'You may join this group, since your post count meets the group criteria',

// merge topics
	'Merge' => 'Merge',
	'Merge_topic' => 'Merge to topic',
	'Topics_Merged' => 'The selected topics have been merged.',
	'No_Topics_Merged' => 'No topics were merged.',
	'Confirm_merge_topic' => 'Are you sure you want to merge the selected topic/s?',

	'Downloads' => 'Downloads',

// Start add - Bin Mod
	'Move_bin' => 'Move this topic to the bin',
	'Topics_Moved_bin' => 'The selected topics have been moved to the bin.',
	'Bin_disabled' => 'Bin has been disabled',
	'Bin_recycle' => 'Recycle',
// End add - Bin Mod
	'Recent_topics' => 'Recent Topics', // Recent Topics

	'Topics_Title_Edited' => 'The titles of the selected topics have been edited.',
	'TOPIC_LABEL' => 'Topic Label',
	'PM' => 'PM',

// Start Advanced IP Tools Pack MOD
	'Moderator_ip_information' => 'IP address information for moderators only',
	'Registered_ip_address' => 'Registered IP address',
	'Registered_hostname' => 'Registered hostname',
	'Other_registered_ips' => 'Other users registering from %s', //%s is the IP address
	'Other_posted_ips' => 'IP addresses this user has posted from',
	'Search_ip' => 'Search for posts by IP address',
	'Search_ip_explain' => 'Enter an IP address in the format like <u>127.0.0.1</u> -- you may use * as a wildcard for partial matches like <u>127.*.*.1</u>',
	'IP' => 'IP',
	'Whois' => 'Whois',
	'Browser' => 'Browser',
	'No_other_registered_ips' => 'No other users have registered under this IP address.',
	'No_other_posted_ips' => 'This user has not made any posts.',
	'Not_recorded' => 'Not recorded',
	'Logins' => 'Logins',
	'No_logins' => 'No logins have been recorded for this user.',
// End Advanced IP Tools Pack MOD

	'LIW_click_image' => 'Click to view this image at its original size',
	'LIW_click_image_explain' => 'Click on the image to view it at its original size',
// Mighty Gorgon - Full Album Pack - BEGIN
	'Album' => 'Album',
	'Personal_Gallery' => 'Personal Gallery',
	'Personal_Gallery_Of_User' => 'Personal Gallery Of %s',
	'Personal_Gallery_Of_User_Profile' => 'Personal Gallery of %s (%d Pictures)',
	'Show_All_Pic_View_Mode_Profile' => 'Show All Pictures In The Personal Gallery of %s (without sub cats)',
	'Not_allowed_to_view_album' => 'Sorry, you are not allowed to view the album.',
	'Not_allowed_to_upload_album' => 'Sorry, you are not allowed to upload a new pic to the album. Please contact the album administrator for more information.',
	'Album_empty' => 'There are no pics in the album<br />Click on the <b>Upload New Pic</b> link on this page to post one.',
	'Album_empty2' => 'There are no pics in the album.',
	'Upload_New_Pic' => 'Upload New Pic.',
	'Pic_Title' => 'Pic Title',
	'Pic_Title_Explain' => 'It is very important to give your pic a good title. It could be a name or subject to make others know what it is without seeing it.',
	'Pic_Upload' => 'Pic Upload',
	'Pic_Upload_Explain' => 'Allowed types are JPG, GIF and PNG. Maximum file size is %s bytes. Maximum image dimensions are %sx%s pixels.',
	'Album_full' => 'Sorry, the album has reached the maximum number of uploaded pics. Please contact the album administrator for more information.',
	'Album_upload_successful' => 'Thank you, your pic has been uploaded successfully.',
	'Click_return_album' => 'Click %sHere%s to return to the Album.',
	'Invalid_upload' => 'Invalid Upload<br /><br />Your pic is too big or its type is not allowed.',
	'Image_too_big' => 'Sorry, your image dimensions are too large.',
	'Uploaded_by' => 'Uploaded by',
	'Category_locked' => 'Sorry, you cannot upload because this category was locked by an admin. Please contact the album administrator for more information.',
	'View_Album_Index' => 'Album Index',
	'View_Album_Personal' => 'Viewing Personal Album of a user',
	'View_Pictures' => 'Viewing Pictures or Posting/Reading comments in the Album',
	'Album_Search' => 'Searching the Album',
	'Pic_Name' => 'Picture Name',
	'Description' => 'Description',
	'Search_Contents' => ' that contains: ',
	'Search_Found' => 'Search found ',
	'Search_Matches' => 'Matches:',
	'Newest_pic' => 'Last Pic', // Album Addon
	'Random_pic' => 'Random Pic', // Album Addon
	'Click_enlarge_pic' => 'Click on image to enlarge it',
// Mighty Gorgon - Full Album Pack - END

// News
	'xs_latest_news' => 'Latest News',
	'xs_no_news' => 'There are no news items.',
	'xs_news_version' => 'XS News Version: %s',
	'xs_news_ticker_feed' => 'XML Feed Source: %s',
	'xs_no_ticker' => 'There are no News Tickers defined, please visit the ACP to create one.',
	'xs_news_ticker_title' => 'News Ticker',
	'xs_news_tickers_title' => 'News Tickers',
	'xs_news_item_title' => 'News Item',
	'xs_news_items_title' => 'News Items',
	'hide' => 'Hide',
	'show' => 'Show',
	'Welcome' => 'Welcome',
	'birthdays' => 'Birthdays',

	'who_viewed' => 'Topic\'s views',
	'BoardRules' => 'Rules',

	'View_post' => 'View Post',
	'Post_review' => 'Post Review',
	'View_next_post' => 'View next Post',
	'View_previous_post' => 'View previous Post',
	'No_newer_posts' => 'There are no newer posts in this forum',
	'No_older_posts' => 'There are no older posts in this forum',

	'StaffSite' => 'Staff Site',
	'Staff_level' => array('Administrator', 'Moderator'),
	'Staff_forums' => 'Forums',
	'Staff_stats' => 'Statistics',
	'Staff_user_topic_day_stats' => '%.2f topics per day', // %.2f = topics per day
	'Staff_period' => 'since %d days', // %d = days
	'Staff_contact' => 'Contacts',
	'Staff_messenger' => 'Messenger',
// Start Edit Notes MOD
	'Edit_notes' => 'Edit Notes',
	'Delete_note' => 'Delete Note',
	'Edited_by' => 'Edited by',
	'Confirm_delete_edit_note' => 'Are you sure you want to delete this edit note?',
	'Edit_note_deleted' => 'The edit note was successfully deleted.',
// End Edit Notes MOD

	'Recent_topics' => 'Recent Topics',
	'Recent_today' => 'Today',
	'Recent_yesterday' => 'Yesterday',
	'Recent_last24' => 'Last 24 Hours',
	'Recent_lastweek' => 'Last Week',
	'Recent_lastXdays' => 'Last %s days',
	'Recent_last' => 'Last',
	'Recent_days' => 'Days',
	'Recent_first' => 'started %s',
	'Recent_first_poster' => '%s',
	'Recent_select_mode' => 'Select mode:',
	'Recent_showing_posts' => 'Showing Posts:',
	'Recent_title_one' => '%s topic %s', // %s = topics; %s = sort method
	'Recent_title_more' => '%s topics %s', // %s = topics; %s = sort method
	'Recent_title_today' => ' from today',
	'Recent_title_yesterday' => ' from yesterday',
	'Recent_title_last24' => ' from the last 24 hours',
	'Recent_title_lastweek' => ' from the last week',
	'Recent_title_lastXdays' => ' from the last %s days', // %s = days
	'Recent_no_topics' => 'No topics were found.',
	'Recent_wrong_mode' => 'You have selected a wrong mode.',
	'Recent_click_return' => 'Click %sHere%s to return to recent site.',

	'Profile_view_option' => 'Pop up window on profile view',
	'Profile_viewed' => 'My Profile Views',

// BEGIN Disable Registration MOD
	'registration_status' => 'Sorry, but registrations on this board are currently closed. Please try again later.',
// END Disable Registration MOD

	'PostHighlight' => 'Highlight',
	'QuickQuote' => 'Quick Quote',
	'Randomquote' => 'Random Quote',

// Mod User CP Organize
	'Cpl_Navigation' => 'Control Panel',
// 'Cpl_Settings_Options' => 'Settings And Options',
	'Cpl_Settings_Options' => 'Settings',
	'Cpl_Board_Settings' => 'Site Settings',
	'Cpl_NewMSG' => 'Send New Message',
	'Cpl_Click_Return_Cpl' => 'Or click %sHere%s to return to your Control Panel',
	'Cpl_Personal_Profile' => 'Personal Profile',
	'Cpl_More_info' => 'Subscriptions',

	'Forbidden_characters' => 'Allowed characters for usernames are a-z, 0-9, -, _ and spaces.',

	'Topics_per_page' => 'Topics per page',
	'Posts_per_page' => 'Posts per page',
	'Hot_threshold' => 'Popular posts threshold',

	'Mod_CP_first_post' => 'First Post',
	'Mod_CP_topic_count' => '<b>%s</b> topic found.',
	'Mod_CP_topics_count' => '<b>%s</b> topics found.',
	'Mod_CP_no_topics' => 'No topics met the criteria.',
	'Mod_CP_sticky' => 'Sticky',
	'Mod_CP_announce' => 'Announce',
	'Mod_CP_global' => 'Globalize',
	'Mod_CP_normal' => 'Normalize',
	'Display_sticky' => 'Sticky',
	'Display_announce' => 'Announcement',
	'Display_global' => 'Global Announcement',
	'Display_poll' => 'Poll',
	'Display_shadow' => 'Moved',
	'Display_locked' => 'Locked',
	'Display_unlocked' => 'Unlocked',
	'Display_unread' => 'Unread',
	'Display_unanswered' => 'Unanswered',
	'Display_all' => 'All',
	'Mod_CP_confirm_delete_polls' => 'Are you sure you want to delete those polls?',
	'Mod_CP_poll_removed' => 'The selected poll has been successfully removed from the topic.',
	'Mod_CP_polls_removed' => 'The selected polls have been successfully removed from the topics.',
	'Mod_CP_topic_removed' => 'The selected topic has been successfully removed from the database.',
	'Mod_CP_topic_moved' => 'The selected topic has been moved from <b>%s</b> to <b>%s</b>.', // %s = old/new forum
	'Mod_CP_topics_moved' => 'The selected topics have been moved from <b>%s</b> to <b>%s</b>.', // %s = old/new forum
	'Mod_CP_topic_locked' => 'The selected topic has been locked.',
	'Mod_CP_topic_unlocked' => 'The selected topic has been unlocked.',
	'Mod_CP_topics_sticked' => 'The selected topics have been sticked.',
	'Mod_CP_topic_sticked' => 'The selected topic has been sticked.',
	'Mod_CP_topics_announced' => 'The selected topics have been announced.',
	'Mod_CP_topic_announced' => 'The selected topic has been announced.',
	'Mod_CP_topics_globalized' => 'The selected topics have been globalized.',
	'Mod_CP_topic_globalized' => 'The selected topic has been globalized.',
	'Mod_CP_topics_normalized' => 'The selected topics have been normalized.',
	'Mod_CP_topic_normalized' => 'The selected topic has been normalized.',
	'Mod_CP_click_return_topic' => 'Click %sHere%s to return to the old topic or %sHere%s to return to the new one.',

	't_starter' => 'You cannot thank yourself',
	'Watched_Topics' => 'Watched Topics',
	'No_Watched_Topics' => 'You are not watching any topics',
	'Watched_Topics_Started' => 'Topic Started',
	'Watched_Topics_Stop' => 'Stop Watching',

	'Stop_watching_forum' => 'Stop watching this forum',
	'Start_watching_forum' => 'Watch this forum for posts',
	'No_longer_watching_forum' => 'You are no longer watching this forum.',
	'You_are_watching_forum' => 'You are now watching this forum.',

	'UCP_SubscForums' => 'Subscribed Forums List',
	'UCP_NoSubscForums' => 'You have no Subscribed Forums',
	'UCP_SubscForums_Flag' => 'Flag',
	'UCP_SubscForums_Forum' => 'Forum',
	'UCP_SubscForums_Forum_subscribed' => 'Forum Subscribed',
	'UCP_SubscForums_Forum_already_subscribed' => 'You are already subscribed to this Forum',
	'UCP_SubscForums_Click_return_forum' => 'Click %sHere%s to return to the Forum',
	'UCP_SubscForums_Topics' => 'Topics',
	'UCP_SubscForums_Posts' => 'Posts',
	'UCP_SubscForums_LastPost' => 'Last Post',
	'UCP_SubscForums_UnSubscribe' => 'Un-Subscribe',
	'UCP_SubscForums_NewTopic' => 'New Topic',

	'profile_main' => 'General Info',
	'profile_explain' => 'Welcome to the User Control Panel. From here you can monitor, view and update your profile, preferences, subscribed forums and topics. You can also send messages to other users (if permitted).',
	'your_activity' => 'Your Profile',

	'Gravatar' => 'Gravatar',
	'Gravatar_explain' => 'If you have a <a href="http://www.gravatar.com" target="_blank">gravatar</a>, enter the email address you have one registered with here.',

	'private_msg_review_title' => 'Message You\'re Replying To',
	'private_msg_review_error' => 'Error Finding Private Message!',

	'BSH_Viewing_Topic' => 'Viewing Topic: %t%',
	'BSH_Viewing_Post' => '%sViewing A Post%s',
	'BSH_Viewing_Profile' => 'Viewing %u%\'s Profile',
	'BSH_Viewing_Groups' => '%sViewing Groups%s',
	'BSH_Viewing_Forums' => 'Viewing Forum: %f%',
	'BSH_Index' => '%sViewing Index%s',
	'BSH_Searching_Forums' => '%sSearching Forums%s',
	'BSH_Viewing_Onlinelist' => '%sViewing Online List%s',
	'BSH_Viewing_Messages' => '%sViewing Private Messages%s',
	'BSH_Viewing_Memberlist' => '%sViewing Memberlist%s',
	'BSH_Login' => '%sLogging In%s',
	'BSH_Logout' => '%sLogging Out%s',
	'BSH_Editing_Profile' => '%sEditing Profile%s',
	'BSH_Viewing_ACP' => '%sViewing ACP%s',
	'BSH_Moderating_Forum' => '%sModerating Forums%s',
	'BSH_Viewing_FAQ' => '%sViewing FAQ%s',
	'BSH_Viewing_Category' => 'Viewing Category: %c%',

	'Board_statistic' => 'Board statistics',
	'Database_statistic' => 'Database statistics',
	'Version_info' => 'Version information',
	'Thereof_deactivated_users' => 'Number of non-active members',
	'Thereof_Moderators' => 'Number of Moderators',
	'Thereof_Junior_Administrators' => 'Number of Junior Administrators',
	'Thereof_Administrators' => 'Number of Administrators',
	'Deactivated_Users' => 'Members in need of Activation',
	'Users_with_Mod_Privileges' => 'Members with moderator privileges',
	'Users_with_Junior_Admin_Privileges' => 'Members with junior administrator privileges',
	'Users_with_Admin_Privileges' => 'Members with administrator privileges',
	'DB_size' => 'Size of the database',
	'Version_of_ip' => 'Version of <a href="http://www.icyphoenix.com/">Icy Phoenix</a>',
	'Version_of_board' => 'Version of <a href="http://www.phpbb.com">phpBB</a>',
	'Version_of_PHP' => 'Version of <a href="http://www.php.net/">PHP</a>',
	'Version_of_MySQL' => 'Version of <a href="http://www.mysql.com/">MySQL</a>',
	'Download_post' => 'Download Post',

	'Download_topic' => 'Download Topic',
	'Always_swear' => 'Always allow swear words',

	'Shoutbox' => 'Shoutbox',
	'Shoutbox_date' => ' d m Y H:i:s',
	'Shout_censor' => '<b>Shout removed!</b>',
	'Shout_refresh' => 'Refresh',
	'Shout_text' => 'Your text',
	'Viewing_Shoutbox' => 'Viewing shoutbox',
	'Censor' => 'Censor',

	'Edit_post_time' => 'Edit Post Time',
	'Edit_post_time_xs' => 'Edit',
	'Topic_time_xs' => 'Topic time',
	'Post_time' => 'Post time',
	'Post_time_successfull_edited' => '<b>Time update successful.</b></span><br /><span class="postdetails">This window will be closed after 3 seconds.',

	'staff_message' => 'Message From The Staff',

	'Board_Rules' => 'Forum Rules',
	'Forum_Rules' => 'Forum Rules',
	'Show_avatars' => 'Show Avatars in Topic',
	'Show_signatures' => 'Show Signatures in Topic',
	'Acronym' => 'Acronym',
	'Acronyms' => 'Acronyms',
	'User_Number' => 'User #',
	'Member_Count' => 'Members',
	'Reply_message' => 'Replied message',
	'Click_read_topic' => 'Click %sHere%s to read it', // %s's here are for uris, do not remove!
	'Create_with_generator' => 'Create Avatar using Avatar Generator',
	'View_avatar_generator' => 'Avatar Generator',
	'Adv_Search' => 'Advanced Search',
	'Search_Explain' => 'Search inside the Site',
	'Please_remove_install_contrib' => 'Please ensure the <b>install/</b> folder is deleted',
	'Search_Engines' => 'Search Engine Bots:',
	'Bots_browsing_forum' => 'Search Engine Bots browsing this forum:',
	'Debug_On' => 'Debug On',
	'Debug_Off' => 'Debug Off',
	'Page_Generation_Time' => 'Generation Time',
	'Memory_Usage' => 'Memory',
	'SQL_Queries' => 'SQL queries',
	'Search_new2' => 'New Posts',
	'Search_new_p' => 'Posts since last visit',
	'Show_In_Portal' => 'Show in Home Page',
	'Not_Auth_View' => 'You are not authorized to view this page.',
	'Site_Hist' => 'Site History',
	'Links' => 'Links',
	'Print_View' => 'Printable Version',
	'Browsing_topic' => 'Users browsing this topic:',
	'SUDOKU' => 'Sudoku',
	'Bookmarks' => 'Bookmarks',
	'Set_Bookmark' => 'Set a bookmark for this topic',
	'Remove_Bookmark' => 'Remove the bookmark for this topic',
	'No_Bookmarks' => 'You do not have any bookmark set',
	'Always_set_bm' => 'Set bookmark automatically when posting',
	'Found_bookmark' => 'You have set %d bookmark.', // eg. Search found 1 match
	'Found_bookmarks' => 'You have set %d bookmarks.', // eg. Search found 24 matches
	'More_bookmarks' => 'More bookmarks...', // For mozilla navigation bar

//RSS Reader Help
	'RSS' => 'RSS',
	'Rss_news_help' => 'What is this?',
	'Rss_news_help_title' => 'RSS Newsreader Help',
	'Rss_news_help_header' => 'What are feeds and how do I use them?',
	'Rss_news_help_explain' => 'A feed is a regularly updated summary of certain Web contents, which have links to the complete version of respective contents. If you subscribe to the feed of a Website with a feed reader, you will get all new contents of these Website in a summary.<br /><br /><b>Attention:</b> For subscribing to Website feeds a <a href="http://www.rssowl.org/" target="new">Feed-Reader</a> must be used. Otherwise, when clicking on a link to an RSS or ATOM feed it will only result in unformatted text and confusion appearing in the Browser.',
	'Rss_news_help_header_2' => '<b>What are RSS and Atom?</b>',
	'Rss_news_help_explain_2' => 'RSS and Atom are two formats for feeds. Most feed readers support both formats. At present atom 0.3 and RSS 2.0 are supported by us.',
	'Rss_news_help_header_3' => '<b>How do I use the News feeds?</b>',
	'Rss_news_help_explain_3' => 'At first you need a feed reader, you get that for example from <a href="http://www.rssowl.org/" target="new">http://www.rssowl.org/</a>.<br /><br />Afterwards you can in the program:<br /><br /><b>1.</b> Search for RSS links on our side. (Extras => Search for new feeds on web page) <b>or</b><br /><b>2.</b> add one of the following feed URL`s:',
	'L_url_rss_explain' => 'URL for all forum topics.',
	'L_url_rss_news_explain' => 'URL only for the forums News.',
	'L_url_rss_atom_explain' => 'URL for Atom RSS Feed.',
	'Rss_news_help_rights' => 'We reserve ourselves the right, to terminate the use of feeds at any time solely at our discretion. Our forum feeds may not be further-driven out.',
	'Rss_news_feeds' => 'RSS News Feeds',

	'Quick_Reply' => 'Quick Reply',
	'Mod_CP_sticky2' => 'Sticky',
	'Mod_CP_announce2' => 'Announce',
	'Mod_CP_global2' => 'Globalize',
	'Mod_CP_normal2' => 'Normalize',

	'Search_Flood_Error' => 'You cannot make another search so soon after your last; please try again in a short while.',

// Custom Profile Fields MOD
	'custom_field_notice' => 'These items have been created by an administrator. They may or may not be publicly viewable. Items marked with a * are required fields.',
	'and' => ' and ',
// END Custom Profile Fields MOD

	'dsbl' => 'Your IP address is on a <a href="%url%">DNS-based Blackhole List</a>. <br />Registration attempt blocked.',
	'Emails_Only_To_Admins_Error' => 'You can use the email system to send email only to admins.',
	'Wordgraph' => 'Tags',
	'Viewing_wordgraph' => 'Viewing Tags',
	'Links_For_Guests' => '<b>You must be registered to view this link</b>',
	'New' => 'N',
	'New_Label' => 'New',
	'New_Messages_Label' => 'New Messages',
	'Show_Personal_Gallery' => 'Personal Gallery',
	'Login_Status' => 'Online Status',
	'Login_Hidden' => 'Hidden',
	'Login_Visible' => 'Visible',
	'Login_Default' => 'Default',
	'ERRORS_NOT_FOUND' => 'The requested address cannot be found on this server',
	'ERRORS_000' => 'Unknown error',
	'ERRORS_000_FULL' => 'The requested address returns an unknown error code.<br />Errors have been registered to the log file and we will check what the problem is.',
	'ERRORS_400' => 'Error 400',
	'ERRORS_400_FULL' => 'The requested address is not a valid address.',
	'ERRORS_401' => 'Error 401 - Authorization Error',
	'ERRORS_401_FULL' => 'You are receiving this message because you are not authorized to access this address.',
	'ERRORS_403' => 'Error 403',
	'ERRORS_403_FULL' => 'Access to this address is forbidden.',
	'ERRORS_404' => 'Error 404 - File Not Found',
	'ERRORS_404_FULL' => 'The address you have requested is not available on this server. You may have misspelled the address, or what you are looking for may have been removed.',
	'ERRORS_500' => 'Error 500 - Configuration error',
	'ERRORS_500_FULL' => 'The address you have requested returns a configuration error.<br />Errors have been registered to the log file, and we will check what the problem is as soon as possible.',
	'ERRORS_EMAIL_SUBJECT' => 'Errors: ',
	'ERRORS_EMAIL_ADDRRESS_PREFIX' => 'errors_management',
	'ERRORS_EMAIL_BODY' => 'An error has occurred on your site. Please check the log file.',
	'Remote_avatar_no_image' => 'The image %s does not exist',
	'Remote_avatar_error_filesize' => 'The image is over the size limit for avatars (%d Bytes)',
	'Remote_avatar_error_dimension' => 'The image is over the dimension limit for avatars (%d x %d pixels)',
	'How_Many_Chatters' => 'There are <b>%d</b> user(s) in chat now',
	'Who_Are_Chatting' => '<b>%s</b>',
	'Click_to_join_chat' => 'Click to join chat',
	'ChatBox' => 'ChatBox',
	'log_out_chat' => 'You have successfully logged out from chat on ',
	'Send' => 'Send',
	'Login_to_join_chat' => 'Login to join chat',

// Hacks List
/* General */
	'Hacks_List' => 'Credits',
	'Page_Desc' => 'This module allows you to add/edit/delete the list of current credits for hacks/mods installed on your board. They are displayed for users when they visit the credits.php page.',
	'Deleted_Hack' => 'Deleted credit for mod %s from the list.<br />',
	'Updated_Hack' => 'Updated credit info for mod %s.<br />',
	'Added_Hack' => 'Added info for mod %s.<br />',
	'Status' => 'Status',
	'No_Website' => 'No website is available.',
	'No_Hacks' => 'No credits to display.',
	'Add_New_Hack' => 'Add A New Credit',
	'User_Viewable' => 'Hide From User List?',
	'Hack_Name' => 'Mod Name',
//	'Description' => 'Description',
	'Required' => 'Required',
	'Author_Email' => 'Author Email',
	'Version' => 'Version',
	'Download_URL' => 'Download Location',

/* Errors */
	'Error_Hacks_List_Table' => 'Error querying the Hacks List Table.',
	'Required_Field_Missing' => 'You failed to enter all required information.',
	'Error_File_Opening' => 'Unable to open the file: %s',

//====================================================
// Mighty Gorgon - LANG - BEGIN
// Mighty Gorgon - HTTP AGENTS - BEGIN
	'Last_Used_IP' => 'Last Used IP',
	'Last_Used_OS' => 'OS',
	'Last_Used_Browser' => 'Browser',
	'Last_Used_Referer' => 'Referer',
// Mighty Gorgon - HTTP AGENTS - END
// Mighty Gorgon - Enhanced Online - BEGIN
	'Users_Admins' => 'Administrators',
	'Users_Mods' => 'Moderators',
	'Users_Global_Mods' => 'Global Moderators',
	'Users_Regs' => 'Users',
	'Users_Guests' => 'Guests',
	'Users_Hidden' => 'Hidden',
// Mighty Gorgon - Enhanced Online - END
// Mighty Gorgon - Power Memberlist - BEGIN
	'Style' => 'Style',
	'User_Contacts' => 'Contacts',
	'Memberlist_Users_Display' => 'Users per page:',
	'SORT_FAST' => 'Fast',
	'SORT_STANDARD' => 'Standard',
	'SORT_RANK' => 'Rank',
	'SORT_STAFF' => 'Staff',
	'SORT_STYLE' => 'Style',
	'SORT_LASTLOGON' => 'Last Logon',
	'SORT_BIRTHDAY' => 'Birthday',
	'SORT_ONLINE' => 'Online',
	'ASCENDING' => 'Ascending',
	'DESCENDING' => 'Descending',
	'LESS_THAN' => 'Less than',
	'EQUAL_TO' => 'Equal to',
	'MORE_THAN' => 'More than',
	'BEFORE' => 'Before',
	'AFTER' => 'After',
// Mighty Gorgon - Power Memberlist - END
// Mighty Gorgon - Multiple Ranks - BEGIN
	'Staff' => 'Staff',
	'Rank' => 'Rank',
	'Rank_Header' => 'Ranks',
	'Rank_Image' => 'Rank Image',
	'Rank_Posts_Count' => 'Automatic ranking by posts',
	'Rank_Days_Count' => 'Automatic ranking by days',
	'Rank_Min_Des' => 'Minimum messages/days',
	'Rank_Min_M' => 'Minimum Messages',
	'Rank_Max_M' => 'Max Messages',
	'Rank_Min_D' => 'Minimum Days',
	'Rank_Max_D' => 'Max Days',
	'Rank_Special' => 'Special Rank',
	'Rank_Special_Guest' => 'Special Rank For Guests',
	'Rank_Special_Banned' => 'Special Rank For Banned',
	'Current_Rank_Image' => 'Current rank image',
	'No_Rank' => 'No rank assigned',
	'No_Rank_Image' => 'No rank image',
	'No_Rank_Special' => 'No special rank assigned',
	'Memberlist_Administrator' => 'Administrator',
	'Memberlist_Moderator' => 'Moderator',
	'Memberlist_User' => 'User',
	'Guest_User' => 'Guest',
	'Banned_User' => 'Banned',
	'Rank1_title' => 'Rank 1 Title',
	'Rank2_title' => 'Rank 2 Title',
	'Rank3_title' => 'Rank 3 Title',
	'Rank4_title' => 'Rank 4 Title',
	'Rank5_title' => 'Rank 5 Title',
// Mighty Gorgon - Multiple Ranks - END

// Mighty Gorgon - Nav Links - BEGIN
	'QUICK_LINKS' => 'Menu',
	'MAIN_LINKS' => 'Main Links',
	'NEWS_LINKS' => 'News',
	'INFO_LINKS' => 'Info',
	'USERS_LINKS' => 'Users &amp; Groups',
	'SELECT_STYLE' => 'Style',
	'SELECT_LANG' => 'Language',
	'RSS_FEEDS' => 'RSS Feeds',
	'SPONSORS_LINKS' => 'Sponsors',
	'TOOLS_LINKS' => 'Tools',
	'HelpDesk' => 'Help Desk',
	'AvatarGenerator' => 'Avatar Generator',
	'DBGenerator' => 'SQL to PHP Generator ',

	'LINK_ACP' => 'ACP',
	'LINK_CMS' => 'CMS',
	'LINK_HOME' => 'Home',
	'LINK_PROFILE' => 'Profile',
	'LINK_FORUM' => 'Forum',
	'LINK_BOARDRULES' => 'Rules',
	'LINK_PRIVACY_POLICY' => 'Privacy Policy',
	'LINK_COOKIE_POLICY' => 'Cookie Policy',
	'LINK_FAQ' => 'FAQ',
	'LINK_SEARCH' => 'Search',
	'LINK_SITEMAP' => 'Sitemap',
	'LINK_ALBUM' => 'Album',
	'LINK_CALENDAR' => 'Calendar',
	'LINK_DOWNLOADS' => 'Downloads',
	'LINK_BOOKMARKS' => 'Bookmarks',
	'LINK_DRAFTS' => 'Drafts',
	'LINK_UPLOADED_IMAGES' => 'Personal Images',
	'LINK_AJAX_SHOUTBOX' => 'Chat',
	'LINK_LINKS' => 'Links',
	'LINK_CONTACT_US' => 'Contact Us',
	'LINK_SUDOKU' => 'Sudoku',

	'LINK_NEWS_CAT' => 'News Categories',
	'LINK_NEWS_ARC' => 'News Archive',
	'LINK_NEW_MESSAGES' => 'New Posts',
	'LINK_DISPLAY_UNREAD_S' => 'Unread',
	'LINK_DISPLAY_MARKED_S' => 'Marked',
	'LINK_DISPLAY_PERMANENT_S' => 'Permanent',
	'LINK_DIGESTS' => 'Digests',

	'LINK_CREDITS' => 'Credits',
	'LINK_REFERERS' => 'HTTP Referers',
	'LINK_VIEWONLINE' => 'Online Users',
	'LINK_STATISTICS' => 'Statistics',
	'LINK_DELETE_COOKIES' => 'Delete Cookies',

	'LINK_MEMBERLIST' => 'Memberlist',
	'LINK_USERGROUPS' => 'Usergroups',
	'LINK_RANKS' => 'Ranks',
	'LINK_STAFF' => 'Staff',
// Mighty Gorgon - Nav Links - END

	'Activity' => 'Games',
	'Games' => 'Games',
	'Trohpy' => 'Game Trophies',
	'quick_links_games' => 'Games Menu',

	'By' => 'By',
	'No_Icon_Image' => 'No Icon',
	'Change_Style' => 'Style',
	'Change_Lang' => 'Language',
	'Permissions_List' => 'Permissions List',
	'IP_TEAM' => 'Icy Phoenix Team',

//	'' => '',
// Mighty Gorgon - LANG - END

// New MG - BEGIN
	'Nav_Separator' => '&nbsp;&raquo;&nbsp;',
	'WeatherForecast' => 'Weather Forecast',
	'ErrorNotFound' => 'File Not Found!',
	'MGC_Users_Online' => 'MGC Users Online',
	'MGC_User_Servertime' => 'Date',
	'MGC_User_Nickname' => 'Nickname',
	'MGC_User_Server' => 'Server',
	'MGC_User_IP' => 'IP',
	'MGC_User_ID' => 'ID',
	'MGC_User_Client_Version' => 'MGC Version',

	'Country_Flag' => 'Country Flag',
	'Select_Country' => 'Select Country',
	'Extra_profile_info' => 'Extra Profile Information',
// 'Extra_profile_info_explain' => 'You can add extra information about yourself or about your hobbies. You can also add photos. You can use some bbcodes as when you post or make your signature. There is a <b>%d</b> character limit (if 0 then no limit)',
	'Extra_profile_info_explain' => 'You can add extra information about yourself or about your hobbies. You can also add photos. You can use some bbcodes as when you post or make your signature.',
	'Extra_window' => 'Open in separate window',
	'Extra_too_long' => 'Your extra field is too long (max. <b>%d</b> are characters allowed)',
	'UserLike' => 'User likes',
	'UserDisLike' => 'User does not like',
	'UserLikeIns' => 'Three things you like',
	'UserDisLikeIns' => 'Three things you do not like',
	'UserPhone' => 'Phone Number',
	'UserSport' => 'Sport/Team',
	'UserMusic' => 'Music/Groups',
	'UserNoInfo' => 'No Information Available',
	'LAST_SEEN' => 'Last Online',
// New MG - END

// MG CMS - BEGIN
	'CMS_TITLE' => 'CMS',
	'CMS_MANAGEMENT' => 'CMS Management',
	'CMS_CONFIG' => 'Configuration',
	'CMS_SETTINGS' => 'Settings',
	'CMS_ACP' => 'Edit This Page',
	'CUSTOM_PAGE' => 'Customized Page',
// MG CMS - END

// Icy Phoenix - BUILD 001
	'SimilarTopics' => 'Similar Topics',
	'Chat' => 'Chat',
	'DIGESTS' => 'Digests',

	'CPL_REG_INFO_EXPLAIN' => 'Username, email address and password',
	'CPL_PROFILE_INFO_EXPLAIN' => 'General contacts information, messenger, birthday, interests and other information',
	'CPL_PROFILE_VIEWED_EXPLAIN' => 'Users who viewed your profile',
	'CPL_AVATAR_PANEL_EXPLAIN' => 'Avatar is a little picture you can link to your name',
	'CPL_SIG_EDIT_EXPLAIN' => 'Your Signature: you can define some text to be attached at the bottom of your posts',
	'CPL_PREFERENCES_EXPLAIN' => 'General preferences regarding posting and reading messages',
	'CPL_BOARD_SETTINGS_EXPLAIN' => 'Global settings regarding time, template and language',
	'Calendar_settings_EXPLAIN' => 'Settings regarding the calendar box',
	'Hierarchy_setting_EXPLAIN' => 'Settings regarding subforums and topics type split',
	'LOGIN_SEC_EXPLAIN' => 'If enabled this section enables you to monitor all the logins of your username',
	'CPL_OWN_POSTS_EXPLAIN' => 'Search your own posts',
	'CPL_OWN_PICTURES_EXPLAIN' => 'Visit your personal gallery',
	'CPL_INBOX_EXPLAIN' => 'Inbox: contains the private messages you have received',
	'CPL_SAVEBOX_EXPLAIN' => 'Saved Messages: contains the private messages you have saved',
	'CPL_OUTBOX_EXPLAIN' => 'Outbox: contains the private messages you have sent, but which have not been read yet',
	'CPL_SENTBOX_EXPLAIN' => 'Sentbox: contains the private messages you have sent and which have been read',
	'CPL_BOOKMARKS_EXPLAIN' => 'All the bookmarks you have assigned for topics',
	'WATCHED_TOPICS_EXPLAIN' => 'A list of all the topics you are watching',
	'CPL_SUBSCFORUMS_EXPLAIN' => 'The forums you have subscribed to for notifications on new topics',
	'DIGESTS_EXPLAIN' => 'Digests are periodical email which are sent automatically with an excerpt of the new messages posted in the forum',
	'DRAFTS_EXPLAIN' => 'Manage your Drafts',

// Ajax Shoutbox - BEGIN
	'Ajax_Shoutbox' => 'Shoutbox',
	'Ajax_Chat' => 'Chat',
	'Ajax_Archive' => 'Chat Archive',
	'Shoutbox_flooderror' => 'You cannot shout so soon after your last shout. Please try again in a short while.',
	'Shoutbox_no_auth' => 'Sorry, only registered users may use the shoutbox',
	'Shoutbox_loading' => 'Loading Shoutbox...',
// Errors
	'Shoutbox_unable' => 'Sorry, the action could not be performed. Please try again.',
	'Shoutbox_timeout' => 'Sorry, the server is not responding. Please try again.',
	'Shoutbox_empty' => 'No messages in Database',
	'Shoutbox_no_mode' => 'No valid mode specified',
// Archive
	'Shouts' => 'Shouts',
	'Statistics' => 'Statistics',
	'Total_shouts' => 'Total Shouts',
	'Stored_shouts' => 'Stored Shouts',
	'My_shouts' => 'My Shouts',
	'Today_shouts' => 'Shouts in the last 24 hours',
	'Top_Ten_Shouters' => 'Top Ten Shouters',
// Online list
	'Online_total' => 'Total',
	'Online_registered' => 'Users',
	'Online_guests' => 'Guests',
	'Who_is_Chatting' => 'Who is Chatting',
	'Shoutbox_online_explain' => 'This data is based on users active over the past thirty seconds',
	'Start_Private_Chat' => 'Click on the name to start a private chat',
// Chat rooms
	'Shout_rooms' => 'Rooms',
	'Admin_rooms' => 'All rooms',
	'Public_room' => 'Public room',
	'Private_room' => 'Private room',
	'My_id' => 'Me',
// Ajax Shoutbox - END

	'Contact_us' => 'Contact Us',
	'Contact_us_explain' => 'Using this page you can send us an email',
	'Session_invalid' => 'Invalid Session. Please re-submit the form.',

// Icy Phoenix - BUILD 007
	'Reg_Username_Short' => ' this username is too short',
	'Reg_Username_Long' => ' this username is too long',
	'Reg_Username_Taken' => ' this username is not available',
	'Reg_Username_Free' => ' this username is available',
	'Reg_PWD_Short' => 'This password is too short',
	'Reg_PWD_Easy' => 'This password is too easy',
	'Reg_PWD_OK' => 'This password is ok',
	'Reg_Email_Invalid' => ' this email address is invalid or is already in the db',
	'Reg_Email_OK' => ' this email address is ok',

// Moved here from lang_adv_time.php
	'time_mode' => 'Time management',
	'time_mode_text' => 'The DST difference is the difference between Daylight Saving Time and normal time for your country (from 0 to 120 minutes, typically 60)',
	'time_mode_auto' => 'Automatic modes...',
	'time_mode_full_pc' => 'Your computer time',
	'time_mode_server_pc' => 'Server universal time, Timezone/DST<br /><span STYLE="margin-left: 25">from your computer</span>',
	'time_mode_full_server' => 'Server local time',
	'time_mode_manual' => 'Manual mode...',
	'time_mode_dst' => 'DST enable',
	'time_mode_dst_server' => 'By the server',
	'time_mode_dst_time_lag' => 'DST difference',
	'time_mode_dst_mn' => 'min',
	'time_mode_dst_mn_explain' => 'Time expressed in minutes',
	'time_mode_timezone' => 'Timezone',

	'dst_time_lag_error' => 'DST difference value error. You must type a number of minutes between 0 and 120.',

	'dst_enabled_mode' => ' [DST enabled]',
	'full_server_mode' => 'Time synchronized with the forum server time',
	'server_pc_mode' => 'Time synchro. with the server - Timezone/DST with your computer',
	'full_pc_mode' => 'Time synchronized with your computer time',

	'Smileys_Per_Page' => 'Smileys Per Page',

/* lang_site_hist.php - BEGIN */
	'Site_history' => 'Site history',
	'Month' => 'Month',
	'Week_day' => 'Day of week',
	'Not_availble' => 'Not available',
	'Total_users' => 'Max users',
	'Reg_users' => 'Registered users',
	'Hidden_users' => 'Hidden users',
	'Guests_users' => 'Guests users',
	'New_users' => 'New users',
	'New_topics' => 'New topics',
	'New_posts_reply' => 'Posts/Reply',
	'Most_online' => 'Most online users %s',
	'Most_online_week' => 'Most online users the past 7 days',
	'Last_24' => 'Most online users the past %s Hours',
	'Top_Posting_Users' => 'Top posting users',
	'Top_Posting_Users_week' => 'Top posting users this week [%s]',
	'Rank' => 'Rank',
	'Percent' => 'Percent',
	'Graph' => 'Graph',
	'Top_Visiting_Users' => 'Most Time Spent Users',
/* lang_site_hist.php - END */

/* lang_referers.php - BEGIN */
	'REFERERS' => 'Http Referers',
	'VIEWING_REFERERS' => 'Viewing Referers',
	'REFERERS_TITLE' => 'Http Referers Management',
	'REFERERS_CLEARED' => 'Referers Cleared',
	'REFERERS_CLEAR' => 'Delete All',
	'CLICK_RETURN_REFERERS' => 'Click %sHere%s to return to Referers',
	'REFERER_HOST' => 'Host',
	'REFERER_URL' => 'URL',
	'REFERER_T_URL' => 'Visited URL',
	'REFERER_IP' => 'IP',
	'REFERER_HITS' => 'Hits',
	'REFERER_FIRST' => 'First Visit',
	'REFERER_LAST' => 'Last Visit',
	'REFERER_DELETE' => 'Delete',
	'REFERER_KILL' => 'Remove from DB (use * for wildcard)',
	'REFERER_GROUP_BY' => 'Group By',
/* lang_referers.php - END */

/* lang_prune_users.php - BEGIN */
// add to prune inactive
	'X_DAYS' => '%d Days',
	'X_WEEKS' => '%d Weeks',
	'X_MONTHS' => '%d Months',
	'X_YEARS' => '%d Years',

	'Confirm_delete_user' => 'Are you really sure that you want to delete this User?',
	'Prune_no_users' => 'No users deleted',
	'Prune_users_number' => 'The following %d users were deleted:',

	'Prune_user_list' => 'Users who will be deleted',
	'Prune_on_click' => 'You are about to delete %d users. Are you sure?',
	'Prune_Action' => 'Click link below to execute',
	'Prune_users_explain' => 'Prune users!. You can choose one of three links: delete old users who have never posted, delete old users who have never logged in, delete users who have never activated their account.<p/><b>Note:</b> There is no undo function.',
/* lang_prune_users.php - END */

/* lang_avatar_generator.php - BEGIN */
	'Avatar_Generator' => 'Avatar Generator',
	'Available' => 'Available Avatars',
	'Random' => 'Random',
	'Avatar_Text' => 'Please enter the text you would like on your Avatar:',
	'Avatar_Preview' => 'Preview Avatar',
	'Your_Avatar' => 'Your Avatar',
	'Submit_Avatar' => 'Submit avatar',
/* lang_avatar_generator.php - END */

/* lang_.php - BEGIN */
/* lang_.php - END */

	'Uploading' => 'Uploading...',
	'Upload_Image_Local' => 'Upload Image',
	'Uploaded_Images_Local' => 'Personal Images',
	'Upload_Image_Local_Explain' => 'Select the file to be uploaded',
	'Uploaded_Image_Success' => 'The image has been uploaded successfully.',
	'Uploaded_Image_BBC' => 'You can use this BBCode to post this image.',
	'Upload_Image_Empty' => 'You can\'t upload air... you know!',
	'Upload_File_Too_Big' => 'The file you are trying to upload is too big! Max allowed size:',
	'Upload_File_Error' => 'Unknown Error',
	'Upload_File_Error_Size' => 'File size not allowed!',
	'Upload_File_Error_Type' => 'File type not allowed!',
	'Upload_File_Type_Allowed' => 'Only these file types may be uploaded',
	'Upload_File_Max_Size' => 'The maximum single file size allowed is',
	'Upload_Insert_Image' => 'Insert BBCode',
	'Upload_Close' => 'Close',
	'BBCode' => 'BBCode',
	'HTML' => 'HTML',

	'No_News' => 'No News',

	'Email_confirm' => 'Confirm your email address',
	'Email_mismatch' => 'Email addresses you have entered do not match.',

	'View_ballot' => 'View Ballot',
	'Full_edit' => 'Switch to full edit form',
	'Save_changes' => 'Save',
	'No_subject' => '(No subject)',
	'Edit_quick_post' => 'Quick-edit this post',
	'AJAX_search_results' => 'A quick search has found %s topics with the keywords in your topic title. Click here to view these topics',
	'AJAX_search_result' => 'A quick search has found one topic with the keywords in your topic title. Click here to view this topic',
	'More_matches_username' => 'More than one username matched your query. Please select a user from the box above.',
	'No_username' => 'You must enter a username.',
	'AJAX_quick_search_results' => 'A quick search has found %s topics with the keywords.<br />On the right side you can see the first results.<br />Click on SEARCH to get the complete result list.',
	'AJAX_quick_search_result' => 'A quick search has found one topic with the keywords.<br />On the right side you can see the result.',

	'Icon_Description' => 'Icon Description',

	'Feature_Disabled' => 'This feature is disabled.',

// Resend Activation - BEGIN
	'Resend_activation_email' => 'Resend activation email',
	'Invalid_activation' => 'User account activation can only be performed by administrators.',
	'No_actkey' => 'There is no activation key for your account. Please contact the board administrator for more information.',
	'Send_actmail_flood_error' => 'You cannot make another request so soon after your last one; please try again in a short while.',
	'Resend_activation_email_done' => 'The activation e-mail has been sent. Please check your email for further information.',
// Resend Activation - END

	'Bots_Group' => 'Bots',
	'Bots_Color' => 'Bots Colour',
	'Active_Users_Group' => 'Active Users',
	'Active_Users_Color' => 'Active Users Colour',
	'Group_Default_Membership' => 'Default Group',
	'Group_Default_Membership_Explain' => 'Choose the default group for user, to assign rank and colour.',
	'User_Color' => 'User Colour',
	'User_Color_Explain' => 'If you specify a colour for this user this will be overwritten by the default group colour if you specify one group in the box above. Use the hex code without <b>#</b> example: ff0000 is the code for RED.',
	'No_Groups_Membership' => 'No Membership',
	'No_Default_Group' => 'No Default Group',
	'Group_members_updated' => 'Successfully updated group members.',
	'Colorize_All' => 'Colorize All Members',
	'Colorize_Selected' => 'Colorize Selected',

	'CompanyWho' => 'The Company',
	'CompanyWhere' => 'How To Reach Us',
	'CompanyServices' => 'Services',
	'CompanyProducts' => 'Products',

	'ShareThisTopic' => 'Share this topic',

	'Remove_cookies' => 'Remove Cookies set by this site',
	'Cookies_deleted' => 'All cookies have been deleted. You are now logged out.<br />To finalize deletion, you must close your browser now.',
	'Delete_cookies' => 'Delete Cookies',
	'cookies_confirm' => 'Are you sure you want to delete all cookies set by this site?<br /><br />This action will also log you out.',

	'CustomIcy' => 'CustomIcy',

	'Drafts' => 'Drafts',
	'Drafts_Action' => 'Action',
	'Drafts_Save' => 'Save',
	'Drafts_Load' => 'Load',
	'Drafts_Saved' => 'This Draft has been saved',
	'Drafts_Loaded' => 'Draft loaded',
	'Drafts_No_Drafts' => 'No drafts saved',
	'Drafts_Delete_Sel' => 'Delete selected',
	'Drafts_Save_Question' => '<br /><br />Are you sure you want to save this message as a draft?<br /><br />Please note that only the message body will be saved while all other settings will be discarded.',
	'Drafts_Delete_Question' => '<br /><br />Are you sure you want to delete the selected drafts?',
	'Drafts_Type' => 'Draft Type',
	'Drafts_Subject' => 'Draft Subject',
	'Drafts_NT' => 'New topic',
	'Drafts_NM' => 'Reply',
	'Drafts_NPM' => 'Private Message',

	'CannotEditAdminsPosts' => 'You cannot edit admins posts',
	'Random_Number' => 'Random Number',

	'New_download' => 'A new download was uploaded or updated.<br />Click %sHere%s to view it.',
	'Dl_bug_tracker' => 'Bug Tracker',
	'Downloads_ADV' => 'Downloads ADV',

	'TopicUseful' => 'Was this topic useful?',
	'Article' => 'Article',
	'Comments' => 'Comments',

	'Sitemap' => 'Sitemap',

	'Delete_My_Account' => 'Delete Account',
	'Delete_My_Account_Explain' => 'If you want to delete your account on this site, please send a request using this form and your account will be disabled as soon as possible.<br />Please specify "Account Deletion" in the subject and (if you wish) write the reason in few words.',

	'KB_MODE_ON' => 'Enable KB mode',
	'KB_MODE_OFF' => 'Disable KB mode',

	'Go_To_Page_Number' => 'Go to page',

	'Admin_Emails' => 'Administrators can email me information',
	'Allow_PM_IN' => 'Allow users to send me private messages',
	'Allow_PM_IN_Explain' => 'Note that system administrators, moderators and friends will always be able to send you messages.',
	'Allow_PM_IN_SEND_ERROR' => 'The user you are trying to send the PM could not receive your message because he/she decided to not receive Private Messages.',

// 'UCP_ZEBRA' => 'Friends &amp; Foes',
	'UCP_ZEBRA' => 'Friends Management',
	'UCP_ZEBRA_FOES' => 'Manage Foes',
	'UCP_ZEBRA_FRIENDS' => 'Manage Friends',

	'ADD_FOES' => 'Add new foes',
	'ADD_FOES_EXPLAIN' => 'You may enter several usernames each on a different line.',
	'YOUR_FOES' => 'Your foes',
	'YOUR_FOES_EXPLAIN' => 'To remove usernames select them and click submit.<br />Tip: use CTRL to select/deselect multiple items.',
	'FOE_MESSAGE' => 'Message from foe',
	'FOES_EXPLAIN' => 'Foes are users which will be ignored by default. Posts by these users will not be fully visible. Personal messages from foes are still permitted. Please note that you cannot ignore moderators or administrators.',
	'FOES_UPDATED' => 'Your foes list has been updated successfully.',
	'FOES_UPDATE_ERROR' => 'Your foes list has NOT been updated.',
	'NO_FOES' => 'No foes currently defined',

	'ADD_FRIENDS' => 'Add new friends',
	'ADD_FRIENDS_EXPLAIN' => 'You may enter several usernames each on a different line.',
	'YOUR_FRIENDS' => 'Your friends',
	'YOUR_FRIENDS_EXPLAIN' => 'To remove usernames select them and click submit.<br />Tip: use CTRL to select/deselect multiple items.',
	'FRIEND_MESSAGE' => 'Message from friend',
	'FRIENDS' => 'Friends',
	'FRIENDS_EXPLAIN' => 'Friends enable you quick access to members you communicate with frequently. If the template has relevant support any posts made by a friend may be highlighted.',
	'FRIENDS_UPDATED' => 'Your friends list has been updated successfully.',
	'FRIENDS_UPDATE_ERROR' => 'Your friends list has NOT been updated.',
	'FRIENDS_ONLINE' => 'Online',
	'FRIENDS_HIDDEN' => 'Hidden',
	'FRIENDS_OFFLINE' => 'Offline',
	'NO_FRIENDS' => 'No friends currently defined',
	'NO_FRIENDS_OFFLINE' => 'No friends offline',
	'NO_FRIENDS_ONLINE' => 'No friends online',

	'Default' => 'Default',

	'Reserved_Author' => '[ HIDDEN ]',
	'Reserved_Topic' => '[ RESERVED TOPIC ]',
	'Reserved_Post' => '[ RESERVED POST ]',

	'THANKS_RECEIVED' => 'Likes received',

	'RECENT_USER_ACTIVITY' => 'Recent user activity',
	'USER_TOPICS_STARTED' => 'Topics started',
	'USER_POSTS' => 'Posts written',
	'USER_TOPICS_VIEWS' => 'Topics viewed',
	'RECENT_USER_STARTED_TITLE' => 'started by %s',
	'RECENT_USER_STARTED_NAV' => 'Topics started by %s',
	'RECENT_USER_POSTS_TITLE' => 'which %s posted in',
	'RECENT_USER_POSTS_NAV' => 'Topics which %s posted in',
	'RECENT_USER_VIEWS_TITLE' => 'viewed by %s',
	'RECENT_USER_VIEWS_NAV' => 'Topics viewed by %s',

	'WARN_NO_BUMP' => 'You are the last poster in this topic: you cannot write new posts within 24 hours from your last post unless someone else answers in the meantime.',

	'LINK_THIS_TOPIC' => 'Link this topic',
	'LINK_URL' => 'URL',
	'LINK_BBCODE' => 'BBCode',
	'LINK_HTML' => 'HTML',

	'NEWS_POSTED' => 'Browse news posted',
	'TOPICS_POSTED' => 'Search topics started',
	'POSTS_POSTED' => 'Search all user posts',

	'ACCOUNT_DELETION_REQUEST' => 'User %s requested to delete the account.',

	'SORT_TOPICS' => 'Sort topics',
	'SORT_TOPICS_NEWEST' => 'Newest',
	'SORT_TOPICS_OLDEST' => 'Oldest',

	'EDIT_POST_DETAILS' => 'Edit Post Details',
	'CURRENT_POSTER' => 'Current Poster',
	'NEW_POSTER' => 'New Poster',
	'DETAILS_CHANGED' => '<b>Post details successfully changed.</b></span><br /><span class="postdetails">This window will be closed after 3 seconds.',

	'Redirect' => 'Redirect',
	'Redirect_to' => 'If your browser does not support meta redirection please click %sHere%s to be redirected',

	'InProgress' => 'In progress',

	'HAPPY_BIRTHDAY' => 'Happy Birthday',

	'DOWNLOAD' => 'Download',
	'DOWNLOADED' => 'Downloaded',
	'FILESIZE' => 'Filesize',
	'FILENAME' => 'Filename',
	'FILE_NOT_AUTH' => 'You are not authorized to download this file',
	'SHOW_POSTS_FROM' => 'Show posts from',
	'SHOW_POSTS_TO' => 'to',

	'SEE_MORE_DETAILS' => 'See more details...',
	'UNKNOWN' => 'Unknown',
	'MASS_PM' => 'Mass PM',
	'TEXT_FORMAT' => 'Format',
	'SENDER' => 'Sender',
	'PM_NOTIFICATION' => 'Hello,<br /><br />You have received a new private message to your account on "{SITENAME}".<br /><br />You can view your new message by clicking on the following link:<br /><br />{U_INBOX}<br /><br />',

	'GSEARCH' => 'Google Search',
	'GSEARCH_ENGINE' => 'Use Google Search Engine',
	'SEARCH_WHAT' => 'Search what',
	'SEARCH_WHERE' => 'Search where',
	'SEARCH_THIS_FORUM' => 'Search this forum...',
	'SEARCH_THIS_TOPIC' => 'Search this topic...',
	'VF_ALL_TOPICS' => 'All Topics',

	'WHITE_LIST_MESSAGE' => 'This site require account confirmation via email. Please check that this domains in the white-list of your antispam system or you could never receive the activation message.',
	'CLICK_RETURN_HOME' => 'Click %sHere%s to return to Home Page',

	'RATINGS' => 'Ratings',

	'ERROR_TABLE' => 'Could not query %s table',

	'SMILEYS' => 'Smileys',
	'SMILEYS_NO_CATEGORIES' => 'No categories defined',
	'SMILEYS_CATEGORY' => 'Category',
	'SMILEYS_GALLERY' => 'Smileys Gallery',
	'SMILEYS_STANDARD' => 'Standard Smileys',

	'QUICK_LIST' => 'Full List',
	'NORMAL_LIST' => 'Normal List',

	'RETURN_PAGE' => '%sReturn to the previous page%s',
	'FILE_NOT_FOUND' => 'File not found',
	'FSOCK_DISABLED' => 'FSOCK Disabled',

	'MANAGEMENT' => 'Management',
	'SORT_ORDER' => 'Sort by',
	'SORT_DIR' => 'Sort direction',

	'CONTACTS' => 'Contacts',

	// Event Registration - BEGIN
	'Reg_Title' => 'Event Registration',
	'Post_Registration' => 'Event Registration',
	'Add_registration' => 'Add Event Registration',
	'Add_reg_explain' => '&nbsp;&bull; Check <i>Activate</i> to show a registration form with this post. Uncheck to hide it.<br />&nbsp;&bull; Check <i>Reset</i> to delete all current registrations for this form.<br />&nbsp;&bull; Enter a number in <i>Slots</i> to limit registrations for an option. "0" or empty = unlimited.',
	'reg_activate' => 'Activate',
	'reg_reset' => 'Reset',
	'Reg_Insert' => 'Registration added for the event.',
	'Reg_Change' => 'Registration changed.',
	'Reg_Confirm' => 'Registration confirmed.',
	'Reg_Unregister' => 'Registration cancelled.',
	'Reg_Max_Registrations' => 'The maximum of registrations for this option is reached. No more registrations will be accepted.',
	'Reg_No_Slots_Left' => 'No slots left to register.',
	'Reg_One_Slot_Left' => 'One slot left to register.',
	'Reg_Slots_Left' => '%s slots left to register.',
	'Reg_Self_Unregister' => 'Unregister',
	'Reg_Own_Confirmation' => 'Confirmation',
	'Reg_Own_Confirmed' => 'confirmed',
	'Reg_Green_Option' => 'Green Option',
	'Reg_Blue_Option' => 'Blue Option',
	'Reg_Red_Option' => 'Red Option',
	'Reg_Value_Max_Registrations' => 'Slots',
	'Reg_Do' => 'Sign Up',
	'Reg_Maybe' => 'Not Sure',
	'Reg_Dont' => 'Not This Time',
	'Reg_Head_Username' => 'User:',
	'Reg_Head_Time' => 'Date:',
	'Reg_for' => 'Run registration for',
	'Reg_for_explain' => '[ Enter 0 or leave blank for a never-ending registration ]',
	'Reg_no_options_specified' => 'You have to specify at least one option to register for.',
	'Reg_event_date' => '<b>Event date: </b>',
	// Event Registration - END

	'REPLY_PREFIX_OLD' => 'Re: ',
	'REPLY_PREFIX' => 'Re: ',

	'READ_ONLY_FORUM' => 'We are sorry, but currently the forum is set in <b>READ ONLY</b> mode which means that you cannot post even if you have the right to. Please try again later.',

	// Tickets Submission - BEGIN
	'TICKET_CAT' => 'Category',
	// Tickets Submission - END

	'LIMIT_EDIT_TIME_WARN' => 'We are sorry, but you are only allowed to edit posts within the first <b>%d</b> minute(s) after submission.',
	'CLEAN_NAME' => 'Clean Name',
	'CLEAN_NAME_EXPLAIN' => 'Only alpha-numeric chars and dashes allowed',
	'TOPIC_TAGS' => 'Tags And Keywords',
	'TOPIC_TAGS_REPLACE' => 'Replace Tag',
	'TOPIC_TAGS_EXPLAIN' => 'Separate each tag using comma',
	'TOPIC_TAGS_CLOUDS' => 'Tags And Keywords Cloud',
	'TOPIC_TAGS_LIST' => 'Tags And Keywords List',
	'TAG_COUNT' => 'Counter',
	'TAG_TEXT' => 'Tag',
	'TAGS_TEXT' => 'Tags',
	'TAG_RESULTS' => 'Selected tag: <b>%s</b>',
	'TAGS_SEARCH' => 'Search a tag...',
	'TAGS_SEARCH_NO_RESULTS' => 'No tags found!',
	'TAGS_NO_TAGS' => 'No tags have been defined',
	'TAGS_NO_TAG' => 'Specified tag doesn\'t exist',

	'SQL_ERROR_OCCURRED' => 'An SQL error occurred while fetching this page. Please contact the Site Administrator if this problem persists.',

	'PLUGIN_DISABLED' => 'This plugin is currently disabled.',

	'AJAX_SHOUTBOX' => 'Chat',
	'AJAX_SHOUTBOX_PVT' => 'Private Chat',
	'AJAX_SHOUTBOX_PVT_LINK' => 'Direct chat with this user',
	'AJAX_SHOUTBOX_PVT_ALERT' => 'You have a chat request',

	// COMMON DB - BEGIN
	'SELECT_SORT_METHOD' => 'Sort by',
	'ORDER' => 'Sort method',
	'DATE' => 'Date',
	'USERID' => 'User ID',
	'USERNAME' => 'Username',
	'EMAIL' => 'Email',
	'WEBSITE' => 'Website',
	'EDIT' => 'Edit',
	'DELETE' => 'Delete',

	'DB_ITEM_VIEW' => 'View Item',
	'DB_ITEM_UPDATED' => 'Item updated successfully',
	'DB_ITEM_ADD' => 'Add Item',
	'DB_ITEM_ADDED' => 'Item added successfully',
	'DB_ITEM_REMOVED' => 'Item removed successfully',
	'DB_ITEM_NO_ITEMS' => 'No Items',
	'DB_ITEM_CLICK_VIEW_ITEM' => 'Click %sHere%s to view added data',
	'DB_ITEM_CLICK_RETURN_ITEMS' => 'Click %sHere%s to return to list page',
	// COMMON DB - END

	'LOGIN_ATTEMPTS_EXCEEDED' => 'The maximum number of %s login attempts has been exceeded. You are not allowed to login for the next %s minutes.',
	'LOGIN_CONFIRM_EXPLAIN' => 'To prevent brute forcing accounts the board requires you to enter a confirmation code after a maximum amount of failed logins. The code is displayed in the image you should see below. If you are visually impaired or cannot otherwise read this code please contact the %sAdministrator%s.',
	'LOGIN_ERROR_ATTEMPTS' => 'You exceeded the maximum allowed number of login attempts. In addition to your username and password you now also have to enter the confirm code from the image you see below.',
	'LOGIN_ERROR_EXTERNAL_AUTH_APACHE' => 'You have not been authenticated by Apache.',
	'LOGIN_ERROR_PASSWORD' => 'You have specified an incorrect password. Please check your password and try again. If you continue to have problems please contact the %sAdministrator%s.',
	'LOGIN_ERROR_PASSWORD_CONVERT' => 'It was not possible to convert your password when updating this website\'s software. Please %srequest a new password%s. If you continue to have problems please contact the %sAdministrator%s.',
	'LOGIN_ERROR_USERNAME' => 'You have specified an incorrect username. Please check your username and try again. If you continue to have problems please contact the %sAdministrator%s.',
	'NO_PASSWORD_SUPPLIED' => 'You cannot login without a password.',
	'ACTIVE_ERROR' => 'The specified username is currently inactive. If you have problems activating your account, please contact the %sAdministrator%s.',

	'Bytes' => 'Bytes',
	'KB' => 'KB',
	'MB' => 'MB',
	'GB' => 'GB',

	'NO_EVENTS' => 'No planned events',
	'EVENT_START_DATE' => 'Start Date',
	'EVENT_START_TIME' => 'Start Time',
	'EVENT_END_DATE' => 'End Date',
	'EVENT_END_TIME' => 'End Time',
	'EVENT_TITLE' => 'Event',
	'EVENT_FORUM' => 'Category',

	'MAX_OPTIONS_SELECT' => 'You may select up to <strong>%d</strong> options',
	'MAX_OPTION_SELECT' => 'You may select <strong>1</strong> option',
	'NO_POLLS' => 'No Polls',
	'NO_VOTE_OPTION' => 'You must specify an option when voting.',
	'NO_VOTES' => 'No votes',
	'POLL_ENDED_AT' => 'Poll ended at %s',
	'POLL_MAX_OPTIONS' => 'Max options',
	'POLL_MAX_OPTIONS_EXPLAIN' => 'Maximum number of options selectable by users',
	'POLL_NO_GUESTS' => 'Sorry, but Guests are not allowed to vote',
	'POLL_RUN_TILL' => 'Poll runs until %s',
	'POLL_VOTE_CHANGE' => 'Allow vote change',
	'POLL_VOTED_OPTION' => 'You voted for this option',
	'TOO_MANY_VOTE_OPTIONS' => 'You have tried to vote for too many options.',
	'VIEW_POLL' => 'View Poll',
	'VOTE_SUBMITTED' => 'Your vote has been cast.',
	'VOTE_CONVERTED' => 'Changing votes is not supported for converted polls.',

	'FORM_INVALID' => 'The submitted form was invalid. Try submitting again.',

	'NO_USERS_FOUND' => 'No users found',
	'POST_IP' => 'IP Address',
	'FIND_USERNAME' => 'Find a member',
	'FIND_USERNAME_HIDE' => 'Hide &quot;Find a member&quot; form',
	'FIND_USERNAME_EXPLAIN' => 'Use this form to search for specific members. You do not need to fill out all fields. To match partial data use * as a wildcard. When entering dates use the format <kbd>YYYY-MM-DD</kbd>, e.g. <samp>2004-02-29</samp>. Use the mark checkboxes to select one or more usernames (several usernames may be accepted depending on the form itself) and click the Select Marked button to return to the previous form.',

	'AUTH_NONE' => 'NONE',
	'AUTH_ALL' => 'ALL',
	'AUTH_REG' => 'REG',
	'AUTH_SELF' => 'SELF',
	'AUTH_PRIVATE' => 'PRIVATE',
	'AUTH_MOD' => 'MOD',
	'AUTH_JADMIN' => 'J ADMIN',
	'AUTH_ADMIN' => 'ADMIN',

	'SHARE' => 'Share',
	'LIKE' => 'Like',
	'UNLIKE' => 'Unlike',
	'LIKE_POST' => 'Like this post',
	'UNLIKE_POST' => 'Unlike this post',
	'LIKE_TIME' => 'Date',
	'LIKE_RECAP' => 'Users who like this post',
	'LIKE_COUNTER_YOU' => 'You like this post',
	'LIKE_COUNTER_YOU_OTHERS_SINGLE' => 'You and %s user like this post',
	'LIKE_COUNTER_YOU_OTHERS' => 'You and %s users like this post',
	'LIKE_COUNTER_OTHERS' => '%s users like this post',
	'LIKE_COUNTER_OTHERS_SINGLE' => '%s user likes this post',

	'FRIENDSHIP_STATUS' => 'Friendship status',
	'FRIEND_ADD' => 'Add as a friend',
	'FRIEND_REMOVE' => 'Remove as a friend',
	'FOE_ADD' => 'Add as a foe',
	'FOE_REMOVE' => 'Remove as a foe',

	'SOCIAL_NETWORKS' => 'Social Networks',
	'USER_FIRST_NAME' => 'First Name',
	'USER_LAST_NAME' => 'Last Name',
	'FACEBOOK' => 'Facebook',
	'TWITTER' => 'Twitter',

	'INACTIVE_USER' => 'Inactive User',
	'SEARCH_MIN_CHARS' => 'You need to input at least %s chars to perform the search.',

	'EXTRA_STATS_SHOW' => 'Show Extra Statistics',
	'EXTRA_STATS_HIDE' => 'Hide Extra Statistics',
	'RESERVED' => 'Reserved',

	'VIEW_TOPICS_DAYS' => 'Display topics from previous days',
	'VIEW_TOPICS_DIR' => 'Display topic order direction',
	'VIEW_TOPICS_KEY' => 'Display topics ordering by',
	'VIEW_POSTS_DAYS' => 'Display posts from previous days',
	'VIEW_POSTS_DIR' => 'Display post order direction',
	'VIEW_POSTS_KEY' => 'Display posts ordering by',

	'AUTHOR' => 'Author',
	'POST_TIME' => 'Time',
	'REPLIES' => 'Replies',
	'SUBJECT' => 'Subject',
	'VIEWS' => 'Views',

	'INVALID' => 'Invalid data.',
	'TOO_LARGE' => 'The value you entered is too large.',
	'TOO_LONG' => 'The value you entered is too long.',
	'TOO_SHORT' => 'The value you entered is too short.',
	'TOO_SMALL' => 'The value you entered is too small.',
	'WRONG_DATA' => 'Invalid data.',

	'ATTACHMENTS' => 'Attachments',
	'ERROR_NO_ATTACHMENT' => 'The file you are trying to download does not exist.',
	'NO_FORUM' => 'The forum you selected does not exist.',
	'NO_TOPIC' => 'The topic or post you requested does not exist.',
	'NO_USER' => 'Sorry, but that user does not exist.',

	'AJAX_SEARCH' => 'Search...',

	'FLICKR' => 'Flickr',
	'GOOGLEPLUS' => 'Google Plus',
	'LINKEDIN' => 'LinkedIn',
	'YOUTUBE' => 'YouTube',

	'MOBILE_STYLE_ENABLE' => 'Enable Mobile Friendly Style',
	'MOBILE_STYLE_DISABLE' => 'Disable Mobile Friendly Style',

	'INVALID_SESSION' => 'Invalid session. If the problem persist, please contact an administrator.',

	'BACK_TO_TOP' => 'Top',
	'BACK_TO_PREV' => 'Back to previous page',

	'CMS_EDIT_PARENT_BLOCK' => 'Edit Parent Block',

	'UPI2DB_DISABLED' => 'UPI2DB Disabled',
	'UPI2DB_LINK_U' => 'UPI2DB Unread',
	'UPI2DB_LINK_M' => 'UPI2DB Marked',
	'UPI2DB_LINK_P' => 'UPI2DB Permanent Read',
	'UPI2DB_LINK_FULL' => 'UPI2DB Full Info',
	'NEW_POSTS_LINK' => 'New Posts',
	'LOGIN_LOGOUT_LINK' => 'Login/Logout',
	'MENU_EMPTY_LINK' => 'Empty Link',

	'IP_BLACKLISTED' => 'Your IP %s has been blocked because it is blacklisted. For details please see <a href="%s">%s</a>.',

	'PRINTABLE_VERSION' => 'Printable Version',
	'CHANGE_FONT_SIZE' => 'Change Font Size',
	'CHANGE_PAGE_SIZE' => 'Change Page Size',

	'FULL_EDITOR' => 'Switch to full Editor',

	'SN_SHOW_POSTS' => 'Show posts text',
	'SN_SHOW_TOPICS' => 'Show topics only',

	'NOT_LOGGED_IN_VIEW_PAGE' => 'You must be logged in to view this page.',

	'TAGS_SEARCH_REPLACE' => 'Search And Replace Tags',
	'TAGS_SEARCH_REPLACE_SRC' => 'Tag to be replaced',
	'TAGS_SEARCH_REPLACE_TGT' => 'New Tag',
	'TAGS_SEARCH_REPLACE_RESULT' => '%s Tags have been replaced',

	'UNABLE_TO_UPLOAD_AVATAR' => 'Unable to upload the image, make sure the image is not too big and of allowed type.',

	'MOVE_ALL' => 'Move All',
	'MOVED_TOPICS_PREFIX' => 'Choose a topics prefix for topics moved (optional)',

	'TAGS_REMOVE_ITEM' => 'Remove Tag',
	'TAGS_EDIT_ITEM' => 'Save changes',
	'TAGS_DELETE_ITEM' => 'Delete this Tag from database',
	'TAGS_DELETE_CONFIRM' => 'Are you sure to delete this Tag?',
	'TAGS_DELETED_ITEM' => 'This Tag will be deleted',
	'TAGS_BREAK_EDIT' => 'Cancel',

	'POST_FEATURED_IMAGE' => 'Featured Image',
	'POST_FEATURED_IMAGE_EXPLAIN' => 'Add a featured image to the topic',

	'NOT_LOGGED_IN_ERROR' => 'You must be logged in to use this feature.',

	'LOCK_POST' => 'Lock this post',
	'POST_LOCKED' => 'This post has been locked by a moderator, you cannot edit it.',
	'POST_AUTO_SPLIT' => '[SPLIT]',

	'SOCIAL_CONNECT' => 'You can login using your social network accounts:',
	'SOCIAL_CONNECT_LOGIN' => 'Login with my %s account',
	'SOCIAL_CONNECT_REGISTER_INFO' => 'Your profile will be automatically filled with the information retrieved from your profile in the social network.',
	'SOCIAL_CONNECT_LINK_ACCOUNT' => 'Please login to link your social network account to your account',
	'SOCIAL_CONNECT_LINK_ACCOUNT_MSG' => 'We couldn\'t find any social network account linked with your %s account. You can either register or link an existing account with your %s account.<br /><br />Click %shere%s if you already have an account.<br/ ><br />Click %shere%s to create a new account.',

	'IMG_BA_SHOW_ONLY_BEFORE' => 'Show Only Before',
	'IMG_BA_SHOW_ONLY_AFTER' => 'Show Only After',

	'GET_MORE_IMGS' => 'Display More Images',
	'AJAX_REQ_SUCCESS' => 'Request successful',
	'AJAX_REQ_ERROR' => 'Errors in processing the request',
	'ALL_UPLOADED_IMAGES' => 'All uploaded images can be found here:',

	'EVENTS_REG_USER' => 'Add a user to the event',

	'NOTES_MOD' => 'Moderation notes',

	'500PX' => '500px',
	'GITHUB' => 'GitHub',
	'INSTAGRAM' => 'Instagram',
	'PINTEREST' => 'Pinterest',
	'VIMEO' => 'Vimeo',

	'PRIVACY_POLICY_TITLE' => 'Privacy Policy',
	'COOKIE_POLICY_TITLE' => 'Cookie Policy',
	'COOKIE_POLICY_SHORT' => 'We use cookies to maximise your navigation experience on this Website. By using this Website you agree on the use of cookies even from third parties.',
	'COOKIE_POLICY_AGREE' => 'I understand',
	'COOKIE_POLICY_DISABLE' => 'Disable Cookies',
	'LEARN_MORE' => 'Learn More...',

	'CP_CLICK_COLOR' => 'Click on the top right color box to apply the color',
	'CP_CLICK_APPLY' => 'Click to apply this color',
	'CP_CLICK_APPLY_SHORT' => 'APPLY',

	'PRIVACY_POLICY_LOGIN' => 'By clicking on LOGIN <strong>you specifically agree</strong> with our <a href="privacy_policy.php">Privacy Policy</a> and <a href="cookie_policy.php.php">Cookie Policy</a>',

	)
);

/*Special Cases, Do not bother to change for another language */
$lang['HL_File_Error'] = $lang['Error_File_Opening'];

$lang['Prune_commands'] = array();
// here you can make more entries if needed
$lang['Prune_commands'][0] = 'Prune non-posting users';
$lang['Prune_explain'][0] = 'Who have never posted, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][1] = 'Prune inactive users';
$lang['Prune_explain'][1] = 'Who have never logged in, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][2] = 'Prune non-activated users';
$lang['Prune_explain'][2] = 'Who have never been activated, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][3] = 'Prune long-time-since users';
$lang['Prune_explain'][3] = 'Who have not visited for 60 days, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][4] = 'Prune not posting so often users';
$lang['Prune_explain'][4] = 'Who have less than an average of 1 post for every 10 days while registered, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][5] = 'Prune non-posting and non-visiting users';
$lang['Prune_explain'][5] = 'Who have never posted and not visited recently, <b>excluding</b> new users from the past %d days';

// Timezones - BEGIN
$lang['All_times'] = 'All times are %s'; // eg. All times are GMT - 12 Hours (times from next block)

// Time zones short
$lang['tz'] = array(
	'-12' => 'UTC - 12 Hours',
	'-11' => 'UTC - 11 Hours',
	'-10' => 'UTC - 10 Hours',
	'-9.5' => 'UTC - 9:30 Hours',
	'-9' => 'UTC - 9 Hours',
	'-8' => 'UTC - 8 Hours',
	'-7' => 'UTC - 7 Hours',
	'-6' => 'UTC - 6 Hours',
	'-5' => 'UTC - 5 Hours',
	'-4.5' => 'UTC - 4:30 Hours',
	'-4' => 'UTC - 4 Hours',
	'-3.5' => 'UTC - 3:30 Hours',
	'-3' => 'UTC - 3 Hours',
	'-2' => 'UTC - 2 Hours',
	'-1' => 'UTC - 1 Hour',
	'0' => 'UTC',
	'1' => 'UTC + 1 Hour',
	'2' => 'UTC + 2 Hours',
	'3' => 'UTC + 3 Hours',
	'3.5' => 'UTC + 3:30 Hours',
	'4' => 'UTC + 4 Hours',
	'4.5' => 'UTC + 4:30 Hours',
	'5' => 'UTC + 5 Hours',
	'5.5' => 'UTC + 5:30 Hours',
	'5.75' => 'UTC + 5:45 Hours',
	'6' => 'UTC + 6 Hours',
	'6.5' => 'UTC + 6:30 Hours',
	'7' => 'UTC + 7 Hours',
	'8' => 'UTC + 8 Hours',
	'8.75' => 'UTC + 8:45 Hours',
	'9' => 'UTC + 9 Hours',
	'9.5' => 'UTC + 9:30 Hours',
	'10' => 'UTC + 10 Hours',
	'10.5' => 'UTC + 10:30 Hours',
	'11' => 'UTC + 11 Hours',
	'11.5' => 'UTC + 11:30 Hours',
	'12' => 'UTC + 12 Hours',
	'12.75' => 'UTC + 12:45 Hours',
	'13' => 'UTC + 13 Hours',
	'14' => 'UTC + 14 Hours',
	'dst' => '[ <abbr title="Daylight Saving Time">DST</abbr> ]',
);

// These are displayed in the timezone select box
$lang['tz_zones'] = array(
	'-12' => '[UTC - 12] Baker Island Time',
	'-11' => '[UTC - 11] Niue Time, Samoa Standard Time',
	'-10' => '[UTC - 10] Hawaii-Aleutian Standard Time, Cook Island Time',
	'-9.5' => '[UTC - 9:30] Marquesas Islands Time',
	'-9' => '[UTC - 9] Alaska Standard Time, Gambier Island Time',
	'-8' => '[UTC - 8] Pacific Standard Time',
	'-7' => '[UTC - 7] Mountain Standard Time',
	'-6' => '[UTC - 6] Central Standard Time',
	'-5' => '[UTC - 5] Eastern Standard Time',
	'-4.5' => '[UTC - 4:30] Venezuelan Standard Time',
	'-4' => '[UTC - 4] Atlantic Standard Time',
	'-3.5' => '[UTC - 3:30] Newfoundland Standard Time',
	'-3' => '[UTC - 3] Amazon Standard Time, Central Greenland Time',
	'-2' => '[UTC - 2] Fernando de Noronha Time, South Georgia &amp; the South Sandwich Islands Time',
	'-1' => '[UTC - 1] Azores Standard Time, Cape Verde Time, Eastern Greenland Time',
	'0' => '[UTC] Western European Time, Greenwich Mean Time',
	'1' => '[UTC + 1] Central European Time, West African Time',
	'2' => '[UTC + 2] Eastern European Time, Central African Time',
	'3' => '[UTC + 3] Moscow Standard Time, Eastern African Time',
	'3.5' => '[UTC + 3:30] Iran Standard Time',
	'4' => '[UTC + 4] Gulf Standard Time, Samara Standard Time',
	'4.5' => '[UTC + 4:30] Afghanistan Time',
	'5' => '[UTC + 5] Pakistan Standard Time, Yekaterinburg Standard Time',
	'5.5' => '[UTC + 5:30] Indian Standard Time, Sri Lanka Time',
	'5.75' => '[UTC + 5:45] Nepal Time',
	'6' => '[UTC + 6] Bangladesh Time, Bhutan Time, Novosibirsk Standard Time',
	'6.5' => '[UTC + 6:30] Cocos Islands Time, Myanmar Time',
	'7' => '[UTC + 7] Indochina Time, Krasnoyarsk Standard Time',
	'8' => '[UTC + 8] Chinese Standard Time, Australian Western Standard Time, Irkutsk Standard Time',
	'8.75' => '[UTC + 8:45] Southeastern Western Australia Standard Time',
	'9' => '[UTC + 9] Japan Standard Time, Korea Standard Time, Chita Standard Time',
	'9.5' => '[UTC + 9:30] Australian Central Standard Time',
	'10' => '[UTC + 10] Australian Eastern Standard Time, Vladivostok Standard Time',
	'10.5' => '[UTC + 10:30] Lord Howe Standard Time',
	'11' => '[UTC + 11] Solomon Island Time, Magadan Standard Time',
	'11.5' => '[UTC + 11:30] Norfolk Island Time',
	'12' => '[UTC + 12] New Zealand Time, Fiji Time, Kamchatka Standard Time',
	'12.75' => '[UTC + 12:45] Chatham Islands Time',
	'13' => '[UTC + 13] Tonga Time, Phoenix Islands Time',
	'14' => '[UTC + 14] Line Island Time',
);
// Timezones - END

// Dates And Times - BEGIN
$lang = array_merge($lang, array(
	'WEEK_DAY_SUNDAY' => 'Sunday',
	'WEEK_DAY_MONDAY' => 'Monday',
	'WEEK_DAY_TUESDAY' => 'Tuesday',
	'WEEK_DAY_WEDNESDAY' => 'Wednesday',
	'WEEK_DAY_THURSDAY' => 'Thursday',
	'WEEK_DAY_FRIDAY' => 'Friday',
	'WEEK_DAY_SATURDAY' => 'Saturday',

	'TIME_YEAR' => 'Year',
	'TIME_MONTH' => 'Month',
	'TIME_DAY' => 'Day',
	'TIME_HOUR' => 'Hour',
	'TIME_MINUTE' => 'Minute',
	'TIME_SECOND' => 'Second',

	// The value is only an example and will get replaced by the current time on view
	'dateformats' => array(
		'd M Y, H:i' => '01 Jan 2007, 13:37',
		'd M Y H:i' => '01 Jan 2007 13:37',
		'M jS, \'y, H:i' => 'Jan 1st, \'07, 13:37',
		'D M d, Y g:i a' => 'Mon Jan 01, 2007 1:37 pm',
		'F jS, Y, g:i a' => 'January 1st, 2007, 1:37 pm',
		'|d M Y|, H:i' => 'Today, 13:37 / 01 Jan 2007, 13:37',
		'|F jS, Y|, g:i a' => 'Today, 1:37 pm / January 1st, 2007, 1:37 pm'
	),

	// The default dateformat which will be used on new installs in this language
	// Translators should change this if a the usual date format is different
	'default_dateformat' => 'D M d, Y g:i a', // Mon Jan 01, 2007 1:37 pm

	)
);

$lang['datetime'] = array(
	'TODAY' => 'Today',
	'TOMORROW' => 'Tomorrow',
	'YESTERDAY' => 'Yesterday',
	'AGO' => array(
		0 => 'less than a minute ago',
		1 => '%d minute ago',
		2 => '%d minutes ago',
		60=> '1 hour ago',
	),

	'Sunday' => 'Sunday',
	'Monday' => 'Monday',
	'Tuesday' => 'Tuesday',
	'Wednesday' => 'Wednesday',
	'Thursday' => 'Thursday',
	'Friday' => 'Friday',
	'Saturday' => 'Saturday',

	'Sun' => 'Sun',
	'Mon' => 'Mon',
	'Tue' => 'Tue',
	'Wed' => 'Wed',
	'Thu' => 'Thu',
	'Fri' => 'Fri',
	'Sat' => 'Sat',

	'SUN_S' => 'Su',
	'MON_S' => 'Mo',
	'TUE_S' => 'Tu',
	'WED_S' => 'We',
	'THU_S' => 'Th',
	'FRI_S' => 'Fr',
	'SAT_S' => 'Sa',

	'January' => 'January',
	'February' => 'February',
	'March' => 'March',
	'April' => 'April',
	'May' => 'May',
	'June' => 'June',
	'July' => 'July',
	'August' => 'August',
	'September' => 'September',
	'October' => 'October',
	'November' => 'November',
	'December' => 'December',

	'JAN' => 'Jan',
	'FEB' => 'Feb',
	'MAR' => 'Mar',
	'APR' => 'Apr',
	'MAY' => 'May',
	'JUN' => 'Jun',
	'JUL' => 'Jul',
	'AUG' => 'Aug',
	'SEP' => 'Sep',
	'OCT' => 'Oct',
	'NOV' => 'Nov',
	'DEC' => 'Dec',

	'Jan_short' => 'Jan',
	'Feb_short' => 'Feb',
	'Mar_short' => 'Mar',
	'Apr_short' => 'Apr',
	'May_short' => 'May',
	'Jun_short' => 'Jun',
	'Jul_short' => 'Jul',
	'Aug_short' => 'Aug',
	'Sep_short' => 'Sep',
	'Oct_short' => 'Oct',
	'Nov_short' => 'Nov',
	'Dec_short' => 'Dec',
);

$lang['day_short'] = array($lang['datetime']['Sun'], $lang['datetime']['Mon'], $lang['datetime']['Tue'], $lang['datetime']['Wed'], $lang['datetime']['Thu'], $lang['datetime']['Fri'], $lang['datetime']['Sat']);

$lang['day_long'] = array($lang['datetime']['Sunday'], $lang['datetime']['Monday'], $lang['datetime']['Tuesday'], $lang['datetime']['Wednesday'], $lang['datetime']['Thursday'], $lang['datetime']['Friday'], $lang['datetime']['Saturday']);

$lang['month_short'] = array($lang['datetime']['JAN'], $lang['datetime']['FEB'], $lang['datetime']['MAR'], $lang['datetime']['APR'], $lang['datetime']['MAY'], $lang['datetime']['JUN'], $lang['datetime']['JUL'], $lang['datetime']['AUG'], $lang['datetime']['SEP'], $lang['datetime']['OCT'], $lang['datetime']['NOV'], $lang['datetime']['DEC']);

$lang['month_long'] = array($lang['datetime']['January'], $lang['datetime']['February'], $lang['datetime']['March'], $lang['datetime']['April'], $lang['datetime']['May'], $lang['datetime']['June'], $lang['datetime']['July'], $lang['datetime']['August'], $lang['datetime']['September'], $lang['datetime']['October'], $lang['datetime']['November'], $lang['datetime']['December']);
// Dates And Times - END

//====================================================
// Do not insert anything below this line
//====================================================

?>