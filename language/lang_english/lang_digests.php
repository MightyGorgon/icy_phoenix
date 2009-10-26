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

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
// This block goes as default text in the emailed digest (mail_digests.php)
	'digest_introduction' => 'As you requested, here is the latest digest of messages posted on ' . $config['sitename'] . ' forums. Please come and join the discussion!',
	'digest_from_text_name' => $config['sitename'] . ' Digest Robot',
	'digest_from_email_address' => $config['board_email'],
	'digest_subject_line' => $config['sitename'] . ' Digests',
	'digest_disclaimer_html' => "\n" . 'This digest is being sent to registered members of <a href="' . DIGEST_SITE_URL . '">' . $config['sitename'] . '</a> forums and only because you explicitly requested it. ' . $config['sitename'] . ' is completely commercial free. Your email address is never disclosed to outside parties. See our <a href="' . DIGEST_SITE_URL . 'faq.' . PHP_EXT . '">FAQ</a> for more information on our privacy policies. You can change or delete your subscription by logging into ' . $config['sitename'] . ' from the <a href="' . DIGEST_SITE_URL . 'digests.' . PHP_EXT . '">Digest Page</a>. (You must be logged in to change your digest settings.) If you have questions or feedback on the format of this digest please send it to the <a href="mailto:' . $config['board_email'] . '">' . $config['sitename'] . ' Webmaster</a>.',
	'digest_disclaimer_text' => "\n" . 'This digest is being sent to registered members of ' . $config['sitename'] . ' forums and only because you explicitly requested it. ' . $config['sitename'] . ' is completely commercial free. Your email address is never disclosed to outside parties. See our FAQ for more information on our privacy policies. You can change or delete your subscription by logging into ' . $config['sitename'] . ' from the Digest Page. (You must be logged in to change your digest settings.) If you have questions or feedback on the format of this digest please send it to the ' . $config['board_email'] . '.',
	'digest_forum' => 'Forum: ',
	'digest_topic' => 'Topic: ',
	'digest_link' => 'Link',
	'digest_post_time' => 'Post Time',
	'digest_author' => 'Author',
	'digest_message_excerpt' => 'Message Excerpt',
	'digest_posted_by' => 'Posted by ',
	'digest_posted_at' => ' at ',
	'digest_forums_message_digest' => 'Forums Message Digest', // used in <head> tag
	'digest_salutation' => 'Dear ',
	'digest_your_digest_options' => 'Your digest options:',
	'digest_format' => 'Format:',
	'digest_show_message_text' => 'Show Message Text:',
	'digest_show_my_messages' => 'Show My Messages:',
	'digest_frequency' => 'Digest Frequency:',
	'digest_show_only_new_messages' => 'Show only new messages since last time I logged in:',
	'digest_send_if_no_new_messages' => 'Send digest if no new messages:',
	'digest_period_24_hrs' => '24 hours',
	'digest_period_1_week' => '1 week',
	'digest_no_new_messages' => 'There are no new messages for the forums you selected.',
	'digest_message_size' => 'Maximum characters per message in digest:',
	'digest_summary' => 'Digest Summary',
	'digest_a_total_of' => 'A total of',
	'digest_were_emailed' => 'digests were emailed.',
	'digest_server_date' => 'Server Date:',
	'digest_server_hour' => 'Server Hour:',
	'digest_server_time_zone' => 'Server Time Zone:',
	'digest_or' => 'or',
	'digest_a_digest_containing' => 'A digest containing',
	'digest_posts_was_sent_to' => 'posts was sent to',

// This block goes on the digest settings user interface page (digests.php)
	'digest_page_title' => 'Digests',
	'digest_explanation' => 'Digests are email summaries of messages posted here that are sent to you periodically. Digests can be sent daily or weekly at any hour of the day you select. You can specify those particular forums for which you want message summaries, or you can elect to receive all messages for all forums for which you are allowed access.<br /><br />' . "\n" . 'Consistent with our privacy policy digests contain no "spam", nor is your email address connected in any way to advertisements. You can, of course, cancel your digest subscription at any time by simply coming back to this page. Most users find digests to be very useful. We encourage you to give it a try!' . "\n",
	'digest_wanted' => '<b>Type of Digest Wanted:</b><br />(Weekly digests are sent on Sunday)',
	'digest_none' => 'None (Unsubscribe)',
	'digest_daily' => 'Daily',
	'digest_weekly' => 'Weekly',
	'digest_format_short' => 'Format of the Digest:',
	'digest_format' => '<b>Digest Format:</b><br />(HTML is highly recommended unless your email program cannot display HTML)',
	'digest_html' => 'HTML',
	'digest_text' => 'Text',
	'digest_excerpt' => '<b>Display Excerpt of Message:</b>',
	'digest_yes' => 'Yes',
	'digest_no' => 'No',
	'digest_l_show_my_messages' => '<b>Show my messages in the digest:</b>',
	'digest_l_show_new_only' => '<b>Show new messages only:</b><br />(This will filter out any messages posted prior to the date and time you last visited that would otherwise be included in the digest.)',
	'digest_send_if_no_msgs' => '<b>Send a digest if no new messages were posted:</b>',
	'digest_hour_to_send' => '<b>Time of day to send digest:</b><br />(This is the time based on the time zone you set in your profile. If you change your profile time zone and you want digests to arrive at the same time of day then change this value too.)',
	'digest_hour_to_send_short' => 'Time digest sent (based on time zone in your profile):',
	'digest_midnight' => 'Midnight',
	'digest_1am' => '1 AM',
	'digest_2am' => '2 AM',
	'digest_3am' => '3 AM',
	'digest_4am' => '4 AM',
	'digest_5am' => '5 AM',
	'digest_6am' => '6 AM',
	'digest_7am' => '7 AM',
	'digest_8am' => '8 AM',
	'digest_9am' => '9 AM',
	'digest_10am' => '10 AM',
	'digest_11am' => '11 AM',
	'digest_12pm' => '12 PM',
	'digest_1pm' => '1 PM',
	'digest_2pm' => '2 PM',
	'digest_3pm' => '3 PM',
	'digest_4pm' => '4 PM',
	'digest_5pm' => '5 PM',
	'digest_6pm' => '6 PM',
	'digest_7pm' => '7 PM',
	'digest_8pm' => '8 PM',
	'digest_9pm' => '9 PM',
	'digest_10pm' => '10 PM',
	'digest_11pm' => '11 PM',
	'digest_click_return' => 'Click %sHere%s to return to Digests settings', // %s's here are for uris, do not remove!
	'digest_select_forums' => '<b>Send digests for these forums:</b>',
	'digest_create' => 'Your digest settings were successfully created',
	'digest_modify' => 'Your digest settings were successfully updated',
	'digest_unsubscribe' => 'You have been unsubscribed and will no longer receive a digest',
	'digest_no_forums_selected' => 'You have not selected any forums, so you will be unsubscribed',
	'digest_all_forums' => 'All Subscribed Forums',
	'digest_submit_text' => 'Make Digest Changes',
	'digest_reset_text' => 'Reset',
	'digest_size' => '<b>Maximum characters to display per message:</b><br />(Caution: setting this too high may make for very long digests. A link is provided for each message that will let you see the full content of the message.)',
	'digest_size_50' => '50',
	'digest_size_100' => '100',
	'digest_size_150' => '150',
	'digest_size_300' => '300',
	'digest_size_600' => '600',
	'digest_size_max' => 'Maximum (32000)',
	'digest_version_text' => 'Version',
	)
);

?>