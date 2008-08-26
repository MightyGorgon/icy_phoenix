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
* Mark D. Hamill (mhamill@computer.org)
* Lopalong
*
*/

//
// Written by Mark D. Hamill, mhamill@computer.org
// This software is designed to work with phpBB Version 2.0.20

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

// This block goes as default text in the emailed digest (mail_digests.php)
$lang['digest_introduction'] = 'As you requested, here is the latest digest of messages posted on ' . $board_config['sitename'] . ' forums. Please come and join the discussion!';
$lang['digest_from_text_name'] = $board_config['sitename'] . ' Digest Robot';
$lang['digest_from_email_address'] = $board_config['board_email'];
$lang['digest_subject_line'] = $board_config['sitename'] . ' Digests';
$lang['digest_disclaimer_html'] = "\r\n" . 'This digest is being sent to registered members of <a href="' . DIGEST_SITE_URL . '">' . $board_config['sitename'] . '</a> forums and only because you explicitly requested it. ' . $board_config['sitename'] . ' is completely commercial free. Your email address is never disclosed to outside parties. See our <a href="' . DIGEST_SITE_URL . 'faq.' . $phpEx . '">FAQ</a> for more information on our privacy policies. You can change or delete your subscription by logging into ' . $board_config['sitename'] . ' from the <a href="' . DIGEST_SITE_URL . 'digests.' . $phpEx . '">Digest Page</a>. (You must be logged in to change your digest settings.) If you have questions or feedback on the format of this digest please send it to the <a href="mailto:' . $board_config['board_email'] . '">' . $board_config['sitename'] . ' Webmaster</a>.';
$lang['digest_disclaimer_text'] = "\r\n" . 'This digest is being sent to registered members of ' . $board_config['sitename'] . ' forums and only because you explicitly requested it. ' . $board_config['sitename'] . ' is completely commercial free. Your email address is never disclosed to outside parties. See our FAQ for more information on our privacy policies. You can change or delete your subscription by logging into ' . $board_config['sitename'] . ' from the Digest Page. (You must be logged in to change your digest settings.) If you have questions or feedback on the format of this digest please send it to the ' . $board_config['board_email'] . '.';
$lang['digest_forum'] = 'Forum: ';
$lang['digest_topic'] = 'Topic: ';
$lang['digest_link'] = 'Link';
$lang['digest_post_time'] = 'Post Time';
$lang['digest_author'] = 'Author';
$lang['digest_message_excerpt'] = 'Message Excerpt';
$lang['digest_posted_by'] = 'Posted by ';
$lang['digest_posted_at'] = ' at ';
$lang['digest_forums_message_digest'] = 'Forums Message Digest'; // used in <head> tag
$lang['digest_salutation'] = 'Dear ';
$lang['digest_your_digest_options'] = 'Your digest options:';
$lang['digest_format'] = 'Format:';
$lang['digest_show_message_text'] = 'Show Message Text:';
$lang['digest_show_my_messages'] = 'Show My Messages:';
$lang['digest_frequency'] = 'Digest Frequency:';
$lang['digest_show_only_new_messages'] = 'Show only new messages since last time I logged in:';
$lang['digest_send_if_no_new_messages'] = 'Send digest if no new messages:';
$lang['digest_period_24_hrs'] = '24 hours';
$lang['digest_period_1_week'] = '1 week';
$lang['digest_no_new_messages'] = 'There are no new messages for the forums you selected.';
$lang['digest_message_size'] = 'Maximum characters per message in digest:';
$lang['digest_summary'] = 'Digest Summary';
$lang['digest_a_total_of'] = 'A total of';
$lang['digest_were_emailed'] = 'digests were emailed.';
$lang['digest_server_date'] = 'Server Date:';
$lang['digest_server_hour'] = 'Server Hour:';
$lang['digest_server_time_zone'] = 'Server Time Zone:';
$lang['digest_or'] = 'or';
$lang['digest_a_digest_containing'] = 'A digest containing';
$lang['digest_posts_was_sent_to'] = 'posts was sent to';

// This block goes on the digest settings user interface page (digests.php)
$lang['digest_page_title'] = 'Digests';
$lang['digest_explanation'] = 'Digests are email summaries of messages posted here that are sent to you periodically. Digests can be sent daily or weekly at any hour of the day you select. You can specify those particular forums for which you want message summaries, or you can elect to receive all messages for all forums for which you are allowed access.<br /><br />' . "\r\n" . 'Consistent with our privacy policy digests contain no "spam", nor is your email address connected in any way to advertisements. You can, of course, cancel your digest subscription at any time by simply coming back to this page. Most users find digests to be very useful. We encourage you to give it a try!' . "\r\n";
$lang['digest_wanted'] = '<b>Type of Digest Wanted:</b><br />(Weekly digests are sent on Sunday)';
$lang['digest_none'] = 'None (Unsubscribe)';
$lang['digest_daily'] = 'Daily';
$lang['digest_weekly'] = 'Weekly';
$lang['digest_format_short'] = 'Format of the Digest:';
$lang['digest_format'] = '<b>Digest Format:</b><br />(HTML is highly recommended unless your email program cannot display HTML)';
$lang['digest_html'] = 'HTML';
$lang['digest_text'] = 'Text';
$lang['digest_excerpt'] = '<b>Display Excerpt of Message:</b>';
$lang['digest_yes'] = 'Yes';
$lang['digest_no'] = 'No';
$lang['digest_l_show_my_messages'] = '<b>Show my messages in the digest:</b>';
$lang['digest_l_show_new_only'] = '<b>Show new messages only:</b><br />(This will filter out any messages posted prior to the date and time you last visited that would otherwise be included in the digest.)';
$lang['digest_send_if_no_msgs'] = '<b>Send a digest if no new messages were posted:</b>';
$lang['digest_hour_to_send'] = '<b>Time of day to send digest:</b><br />(This is the time based on the time zone you set in your profile. If you change your profile time zone and you want digests to arrive at the same time of day then change this value too.)';
$lang['digest_hour_to_send_short'] = 'Time digest sent (based on time zone in your profile):';
$lang['digest_midnight'] = 'Midnight';
$lang['digest_1am'] = '1 AM';
$lang['digest_2am'] = '2 AM';
$lang['digest_3am'] = '3 AM';
$lang['digest_4am'] = '4 AM';
$lang['digest_5am'] = '5 AM';
$lang['digest_6am'] = '6 AM';
$lang['digest_7am'] = '7 AM';
$lang['digest_8am'] = '8 AM';
$lang['digest_9am'] = '9 AM';
$lang['digest_10am'] = '10 AM';
$lang['digest_11am'] = '11 AM';
$lang['digest_12pm'] = '12 PM';
$lang['digest_1pm'] = '1 PM';
$lang['digest_2pm'] = '2 PM';
$lang['digest_3pm'] = '3 PM';
$lang['digest_4pm'] = '4 PM';
$lang['digest_5pm'] = '5 PM';
$lang['digest_6pm'] = '6 PM';
$lang['digest_7pm'] = '7 PM';
$lang['digest_8pm'] = '8 PM';
$lang['digest_9pm'] = '9 PM';
$lang['digest_10pm'] = '10 PM';
$lang['digest_11pm'] = '11 PM';
$lang['digest_select_forums'] = '<b>Send digests for these forums:</b>';
$lang['digest_create'] = 'Your digest settings were successfully created';
$lang['digest_modify'] = 'Your digest settings were successfully updated';
$lang['digest_unsubscribe'] = 'You have been unsubscribed and will no longer receive a digest';
$lang['digest_no_forums_selected'] = 'You have not selected any forums, so you will be unsubscribed';
$lang['digest_all_forums'] = 'All Subscribed Forums';
$lang['digest_submit_text'] = 'Make Digest Changes';
$lang['digest_reset_text'] = 'Reset';
$lang['digest_size'] = '<b>Maximum characters to display per message:</b><br />(Caution: setting this too high may make for very long digests. A link is provided for each message that will let you see the full content of the message.)';
$lang['digest_size_50'] = '50';
$lang['digest_size_100'] = '100';
$lang['digest_size_150'] = '150';
$lang['digest_size_300'] = '300';
$lang['digest_size_600'] = '600';
$lang['digest_size_max'] = 'Maximum (32000)';
$lang['digest_version_text'] = 'Version';

?>