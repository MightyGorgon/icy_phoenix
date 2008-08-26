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
* BigRib (bigrib@gmx.de)
* Lopalong
*
*/

$lang['setup_upi2db'] = 'UPI2DB Configuration';
$lang['setup_upi2db_explain'] = 'Change settings for the Unread Post Information 2 Database Mod.';
$lang['upi2db_on'] = '<strong>UPI2DB Mod active?</strong>';
$lang['upi2db_on_explain'] = 'Turn on/off the UPI2DB Mod or let your users do this.<br />If you turn off this mod the default cookie system will be active.';
$lang['user_select'] = 'User Settings';
$lang['up2db_days'] = '<strong>Posts automatically set as read after&hellip;</strong>';
$lang['up2db_days_tagen'] = 'Days';
$lang['up2db_days_explain'] = 'Number of days, posts will be saved as unread in the database.<br />After these days, all older posts will be marked as read.<br />Attention! The more days you use, the more it affects the database depending on the size of your forum! <strong>Default = 30 Days</strong>';

$lang['edit_as_new'] = '<strong>Mark edited posts as new?</strong>';
$lang['edit_as_new_explain'] = 'Mark posts after editing as unread?';
$lang['last_edit_as_new'] = '<strong>Mark last post in a topic after editing as unread?</strong>';
$lang['edit_topic_first'] = '<strong>Display edited posts at top on the topic view?</strong>';
$lang['edit_topic_first_explain'] = 'If set to "No", all edited topics marked as unread will be sorted in between the other topics (default). Set to "Yes" all edited topics marked as unread will be displayed on the top of the topic list, until they are read.';

$lang['upi2db_condition_setup'] = 'UPI2DB Conditions';
$lang['upi2db_condition_min_posts'] = 'Minimum count of posts';
$lang['upi2db_condition_min_regdays'] = 'Minimum count of registered days';
$lang['upi2db_unread_color'] = 'Colour code for topic title (unread post)';
$lang['upi2db_edit_color'] = 'Colour code for topic title (edited post)';
$lang['upi2db_mark_color'] = 'Colour code for topic title (marked post)';
$lang['group_allow_upi2db'] = 'Allow Group to use UPI2DB?';
$lang['user_allow_upi2db'] = 'Allow users to use UPI2DB?';
$lang['user_disable_upi2db'] = 'Disable UPI2DB Mod';
$lang['group_user'] = 'User';
$lang['user_without_group'] = '<strong>User without any group membership</strong>';

$lang['max_new_posts'] = '<strong>Maximum number of new/edit posts</strong>';
$lang['max_new_posts_explain'] = 'Enter the maximum number of unread posts per user';
$lang['max_permanent_topics'] = '<strong>Maximum number of permanently marked topics per user</strong>';
$lang['max_permanent_topics_explain'] = 'Enter the maximum number of topics each user can mark as "permanently marked as read". Enter "0" to set the number of topics to unlimited.';
$lang['up2db_del_perm'] = '<strong>Prune permanently marked Forums/Topics after...</strong>';
$lang['up2db_del_perm_explain'] = 'Set the number of days, "Permanently marked as read Forums/Topics" will be pruned from the database. This option reduces the size of the database by datarows from inactive users';

$lang['up2db_del_mark'] = '<strong>Prune marked posts after...</strong>';
$lang['up2db_del_mark_explain'] = 'Enter the number of days "marked posts" will be stored before they are pruned, to avoid heavy database loads';
$lang['max_mark_posts'] = '<strong>Maximum number of "marked posts" per user</strong>';
$lang['max_mark_posts_explain'] = 'The maximum number of posts a user can mark.';

?>