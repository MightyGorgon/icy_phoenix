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
* Lopalong
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

$lang['10_Main_Settings_Icy_Phoenix'] = 'Icy Phoenix';
$lang['20_SQL_Charge'] = 'SQL Optimization';
$lang['30_Posting'] = 'Posting And Messages';
$lang['40_IMG_Posting'] = 'Images In Posts';
$lang['50_Hierarchy_setting'] = 'Forum';
$lang['60_Calendar_settings'] = 'Calendar';
$lang['70_SEO'] = 'SEO';
$lang['80_Security'] = 'Logs And Security';

// admin part
if ($lang_extend_admin)
{
	$lang['Lang_extend_icy_phoenix'] = 'Icy Phoenix';

	// TAB - Icy Phoenix
	$lang['IP_enable_xs_version_check'] = 'Enable Icy Phoenix Version Check';
	$lang['IP_enable_xs_version_check_explain'] = 'Enable this option to check if a newer Icy Phoenix version is available to download each time you enter the ACP. Disabling this option may speed up ACP loading a bit. <br /><b>Note:</b> This check is done just once per day and then cached.';

	$lang['IP_disable_email_error'] = 'Disable errors on email sending';

	$lang['IP_html_email'] = 'HTML Email';
	$lang['IP_html_email_explain'] = 'Enabling this option will enable HTML emails, otherwise they will be simple text mail';

	$lang['IP_enable_digests'] = 'Enable Digests';

	$lang['IP_digests_php_cron'] = 'Enable Digests PHP Cron';
	$lang['IP_digests_php_cron_explain'] = 'This feature will enable a PHP emulation of the CRON trying to send the emails once per hour, but since it is based on a PHP emulation it may not be correctly executed every time. This means that sometimes emails may not be sent. If you can enable CRON on your server, use CRON instead of this feature.';

	$lang['IP_emails_only_to_admins'] = 'Emails Only To Admins';
	$lang['IP_emails_only_to_admins_explain'] = 'Allow email system only for sending emails to admins';

	$lang['IP_ajax_features'] = 'Enable AJAX Features';
	$lang['IP_ajax_features_explain'] = 'Some AJAX features integrated into the site';

	$lang['IP_ajax_checks_register'] = 'AJAX Check While Registering';
	$lang['IP_ajax_checks_register_explain'] = 'By enabling this option some real time checks are performed while filling some fields in the register form (Warning: this option may slow down the register page).';

	$lang['IP_inactive_users_memberlists'] = 'Inactive Users In Memberlist And Birthdays\' Block';
	$lang['IP_inactive_users_memberlists_explain'] = 'By enabling this option inactive users will be shown in the memberlist and birthdays\' block.';

	$lang['IP_page_gen'] = 'Show Page Generation Time on Footer';

	$lang['IP_switch_header_dropdown'] = 'Activate Dropdown Menu in Header';
	$lang['IP_switch_header_dropdown_explain'] = 'This will activate a dropdown menu in the forum header for search and for posts.';

	$lang['IP_show_alpha_bar'] = 'Show Alphabetical Sort Bar In View Forum';
	$lang['IP_show_alpha_bar_explain'] = 'This option will show an alphabetical sort bar on top of viewforum page.';

	$lang['IP_show_rss_forum_icon'] = 'Forum Index Icons';
	$lang['IP_show_rss_forum_icon_explain'] = 'This option will show icons on the right of every forum title (on Forum Index): RSS, New Topic...';

	$lang['IP_allow_mods_view_self'] = 'Allow ALL Moderators to View Self Auth Topics';
	$lang['IP_allow_mods_view_self_explain'] = 'If a forum has been set to SELF AUTH access only admins and forum moderators can access those topics through viewforum and viewtopic. However there are many features that may show contents of these kind of posts even if not authed: Recent Topics, Search, Recent Messages Blocks, Random Topics Blocks, etc. To prevent this, an extra feature has been coded which doesn\'t allow non authed people to access these topics via secondary ways. Anyway you can allow ALL moderators (not only admins) to view these messages through these secondary ways. If you set this to YES then ALL moderators will be allowed to see the content of these messages through: Recent, Search, Topics related blocks... Unfortunately if you switch this OFF then neither AUTHED moderators may access SELF AUTHED topics through secondary ways. The feature has been coded in this way to save extra SQL charge. If you don\'t use SELF AUTHED forums, then you don\'t need this function as well.';

	$lang['IP_xmas_fx'] = 'Christmas Snow';
	$lang['IP_xmas_fx_explain'] = 'This option enables Snow Effect.';

	$lang['IP_xmas_gfx'] = 'Christmas Graphics';
	$lang['IP_xmas_gfx_explain'] = 'By enabling this option Christmas Graphics will be applied.';

	$lang['IP_select_theme'] = 'Change Style';
	$lang['IP_select_theme_explain'] = 'By enabling this option a select box with all the available styles will be added for fast style switching.';

	$lang['IP_select_lang'] = 'Change Lang';
	$lang['IP_select_lang_explain'] = 'By enabling this option a link to each available language will be created on forum index, for quick language switching.';

	$lang['IP_cms_dock'] = 'Show Apple style Dock in CMS';

	$lang['IP_cms_style'] = 'CMS Modern Style';
	$lang['IP_cms_style_explain'] = 'Modern Style for CMS consists in a modern layout with top transparent menu, while classic style has side menu';

	$lang['IP_split_ga_ann_sticky'] = 'Split Topic by Type';
	$lang['IP_split_ga_ann_sticky_explain'] = 'Here you can choose a way to split Topics by Type on the viewforum page';
	$lang['IP_split_topic_0'] = '<p>All Topic Types Together (no Split)</p>';
	$lang['IP_split_topic_1'] = '<p>Global Announcements, Announcements and Stickies together, Normal Topics split</p>';
	$lang['IP_split_topic_2'] = '<p>Global Announcements split, Announcements and Stickies joined together, Normal Topics split</p>';
	$lang['IP_split_topic_3'] = '<p>All Topic Types Split</p>';

	// TAB - SQL Charge
	$lang['IP_fast_n_furious'] = 'Fast And Furious';
	$lang['IP_fast_n_furious_explain'] = 'Enabling this option some heavy SQL functions will be disabled, to hopefully speed up your site!';

	$lang['IP_db_cron'] = 'Database Optimize';
	$lang['IP_db_cron_explain'] = 'Enabling this option will enable Database Optimization.';

	$lang['IP_site_history'] = 'Site History';
	$lang['IP_site_history_explain'] = 'Enabling this option will enable Site History.';

	$lang['IP_global_disable_upi2db'] = 'Disable UPI2DB globally';
	$lang['IP_global_disable_upi2db_explain'] = 'This option lets you disable UPI2DB globally thus saving extra memory.';

	$lang['IP_enable_own_icons'] = 'Own Messages Icons';
	$lang['IP_enable_own_icons_explain'] = 'By enabling this option icons for topics which contain own messages will be marked.';

	$lang['IP_show_forums_online_users'] = 'Show Users Online In Forums';
	$lang['IP_show_forums_online_users_explain'] = 'This will enable online users counter for each forum on the index.';

	$lang['IP_google_bot_detector'] = 'Enable GoogleBot Detector';

	$lang['IP_visit_counter_switch'] = 'Enable Visit Counter';

	$lang['IP_enable_new_messages_number'] = 'Show the number of new messages since last visit';

	$lang['IP_disable_thanks_topics'] = 'Disable Thanks';

	$lang['IP_show_thanks_profile'] = 'Show Thanks received when viewing profile';

	$lang['IP_show_thanks_viewtopic'] = 'Show Thanks received when viewing topics';

	$lang['IP_disable_topic_view'] = 'Disable "Who read this topic"';
	$lang['IP_disable_topic_view_explain'] = 'This option allows you to disable "Who read this topic" feature (this saves SQL space).';

	$lang['IP_disable_referrers'] = 'Disable Referrers';
	$lang['IP_disable_referrers_explain'] = 'This option allows you to disable Referrers feature (this saves SQL space).';

	$lang['IP_disable_logins'] = 'Disable Logins Recording';
	$lang['IP_disable_logins_explain'] = 'By enabling this option User\'s Logins will no longer be recorded.';

	$lang['IP_last_logins_n'] = 'Number of Logins to Record';

	$lang['IP_index_top_posters'] = 'Top Posters :: Forum Index';
	$lang['IP_index_top_posters_explain'] = 'Enable this option to show top posters on <b>Forum Index</b>.';

	$lang['IP_index_last_msgs'] = 'Last Messages :: Forum Index';
	$lang['IP_index_last_msgs_explain'] = 'Enable this option to show the last messages on <b>Forum Index</b>.';

	$lang['IP_online_last_msgs'] = 'Last Messages :: Who Is Online';
	$lang['IP_online_last_msgs_explain'] = 'Enable this option to show the last messages on <b>Who Is Online</b>.';

	$lang['IP_last_msgs_n'] = 'Number of last messages to be shown.';

	$lang['IP_last_msgs_x'] = 'Forum To Exclude';
	$lang['IP_last_msgs_x_explain'] = 'Please, insert the IDs of the forums to be excluded in Last Messages Box (you can separate each forum ID with a comma).';

	$lang['IP_show_chat_online'] = 'AJAX Chat Online :: Forum Index';
	$lang['IP_show_chat_online_explain'] = 'Enabling this option will show on <b>Forum Index</b> users online in AJAX Chat.';

	$lang['IP_index_shoutbox'] = 'Shoutbox :: Forum Index';
	$lang['IP_index_shoutbox_explain'] = 'Enabling this option will enable Shoutbox on <b>Forum Index</b>.';

	$lang['IP_online_shoutbox'] = 'Shoutbox :: Who Is Online';
	$lang['IP_online_shoutbox_explain'] = 'Enabling this option will enable Shoutbox on <b>Who Is Online</b>.';

	$lang['IP_img_shoutbox'] = 'Disable [img][/img] bbcode on Shoutbox';
	$lang['IP_img_shoutbox_explain'] = 'Enabling this option will disable [img][/img] bbcode on Shoutbox.';

	$lang['IP_index_links'] = 'Links :: Forum Index';
	$lang['IP_index_links_explain'] = 'Enabling this option will enable Links on <b>Forum Index</b>.';

	$lang['IP_index_birthday'] = 'Birthday :: Forum Index';
	$lang['IP_index_birthday_explain'] = 'Enabling this option will enable Birthdays on <b>Forum Index</b>.';

	$lang['IP_show_random_quote'] = 'Random Quotes :: Forum Index';
	$lang['IP_show_random_quote_explain'] = 'Enabling this option will enable random quotes to be shown on <b>Forum Index</b>';

	// TAB - Posting
	$lang['IP_posts_precompiled'] = 'Disable precompiled posts';
	$lang['IP_posts_precompiled_explain'] = 'By enabling this option viewtopic will always compile posts text without using the precompiled text, (this is slower, but it can be useful in some cases).';

	$lang['IP_allow_drafts'] = 'Allow Drafts';
	$lang['IP_allow_drafts_explain'] = 'Allow users to save posts as drafts';

	$lang['IP_allow_mods_edit_admin_posts'] = 'Can Moderators edit Admin posts?';
	$lang['IP_allow_mods_edit_admin_posts_explain'] = 'Allow moderators to edit admin posts';

	$lang['IP_force_large_caps_mods'] = 'ProperCase subjects';
	$lang['IP_force_large_caps_mods_explain'] = 'Topic subjects will be converted to proper case for all users except admins';

	$lang['IP_show_new_reply_posting'] = 'Warn For New Replies';
	$lang['IP_show_new_reply_posting_explain'] = 'If you enable this, a warning will be shown when there are new replies while you are replying a topic';

	$lang['IP_no_bump'] = 'Forbid bumping within 24 hours';
	$lang['IP_no_bump_explain'] = 'Enabling this option last posters won\'t be able to post within 24 hours from their last post unless someone else has posted a reply';

	$lang['IP_show_topic_description'] = 'Enable Topic Description';
	$lang['IP_show_topic_description_explain'] = 'Enabling this option will enable Topic Description while posting and browsing forums';

	$lang['IP_edit_notes'] = 'Enable Edit Notes';
	$lang['IP_edit_notes_explain'] = 'Enabling this option will enable Edit Notes';

	$lang['IP_edit_notes_n'] = 'Maximum Edit Notes';

	$lang['IP_always_show_edit_by'] = 'Always Show Posts Edit';
	$lang['IP_always_show_edit_by_explain'] = 'Enabling this option will always show "Last edit by..." on the message footer when someone modifies it. Admins edits are not shown by default';

	$lang['IP_show_social_bookmarks'] = 'Social Bookmarks';
	$lang['IP_show_social_bookmarks_explain'] = 'Show Social Bookmarks section when viewing topics';

	$lang['IP_link_this_topic'] = 'Link this topic';
	$lang['IP_link_this_topic_explain'] = 'Show "Link this topic" box when viewing topics';

	$lang['IP_smilies_topic_title'] = 'Smileys for Topic Title and Description';
	$lang['IP_smilies_topic_title_explain'] = 'Enabling this option will enable smileys for Topic Title and Topic Description';

	$lang['IP_enable_colorpicker'] = 'Enable ColorPicker in posting';

	$lang['IP_quote_iterations'] = 'Max number of nested quotes';

	$lang['IP_disable_ftr'] = 'Completely disable Force Topic Read';
	$lang['IP_disable_ftr_explain'] = 'By enabling this option Force Topic Read will be completely disabled';

	$lang['IP_disable_html_guests'] = 'Disable HTML links for guests';

	$lang['IP_birthday_viewtopic'] = 'Show poster\'s Age on Topics';

	$lang['IP_switch_poster_info_topic'] = 'Show poster\'s Info on Topics (Lang, Style, etc.)';

	$lang['IP_enable_quick_quote'] = 'Enable Quick Quote and Off Topic';
	$lang['IP_enable_quick_quote_explain'] = 'Quick Quote allows users to quote any post in a topic with a simple click. This feature uses JavaScript, and enabling it can result in large pages if there are a lot of posts with a lot of text in a topic.';

	$lang['IP_allow_all_bbcode'] = 'Enable all BBCodes';
	$lang['IP_allow_all_bbcode_explain'] = 'Enabling this option will allow all BBCodes in signatures and other places where usually they are not active. BBCodes which are usually disabled in signature are: IMG, ALBUMIMG and some intensive formatting BBCodes. If you enable this option, some signatures may result in consuming both space and resources.';

	$lang['IP_switch_bbcb_active_content'] = 'Allow BBCode for Active Content in posts';
	$lang['IP_switch_bbcb_active_content_explain'] = 'Activates BBCode for Flash, Video, Audio Streams, RealMedia and Quicktime.';

	// TAB - Images In Posts
	$lang['IP_auth_view_pic_upload'] = 'Pics Upload Permissions (Post Icy Images)';

	$lang['IP_enable_postimage_org'] = 'Enable PostImage button in post form';

	$lang['IP_gd_version'] = 'GD Version:';
	$lang['GD_0'] = 'No GD';
	$lang['GD_1'] = 'GD1';
	$lang['GD_2'] = 'GD2';

	$lang['IP_show_img_no_gd'] = 'Show GIF thumbnails without using GD libraries (full images are loaded and then just shown resized).';

	$lang['IP_thumbnail_posts'] = 'Thumbnails in posts';
	$lang['IP_thumbnail_posts_explain'] = 'With this option a thumbnail will be shown instead of a full picture when an image is posted using IMG BBCode';

	$lang['IP_show_pic_size_on_thumb'] = 'Show the pic size on thumbnail';

	$lang['IP_thumbnail_lightbox'] = 'Use Lightbox JavaScript if Thumbnails are enabled';
	$lang['IP_thumbnail_lightbox_explain'] = 'Opens the image in front of the current page instead of opening it in a new window. More about <a href="http://www.huddletogether.com/projects/lightbox/" target="_blank">Lightbox JS...</a><br />Make sure you empty the precompiled posts after changing this setting!';

	$lang['IP_thumbnail_cache'] = 'Thumbnails cache';

	$lang['IP_thumbnail_quality'] = 'Thumbnails quality (1-100)';

	$lang['IP_thumbnail_size'] = 'Thumbnails size (in pixels)';

	// TAB - Forum
	$lang['Lang_extend_categories_hierarchy'] = 'Categories Hierarchy';

	$lang['Category_attachment'] = 'Attached to';
	$lang['Category_desc'] = 'Description';
	$lang['Category_config_error_fixed'] = 'An error in the category setup has been fixed';
	$lang['Attach_forum_wrong'] = 'You can\'t attach a forum to a forum';
	$lang['Attach_root_wrong'] = 'You can\'t attach a forum to the forum index';
	$lang['Forum_name_missing'] = 'You can\'t create a forum without a name';
	$lang['Category_name_missing'] = 'You can\'t create a category without a name';
	$lang['Only_forum_for_topics'] = 'Topics can only be found in forums';
	$lang['Delete_forum_with_attachment_denied'] = 'You can\'t delete forums having sub-levels';

	$lang['Category_delete'] = 'Delete Category';
	$lang['Category_delete_explain'] = 'The form below will allow you to delete a category and decide where you want to put all forums and categories it contained.';

	// forum links type
	$lang['Forum_link_url'] = 'Link URL';
	$lang['Forum_link_url_explain'] = 'Set a URI to an Icy Phoenix prog, or a full URL to an external server';
	$lang['Forum_link_internal'] = 'Icy Phoenix prog';
	$lang['Forum_link_internal_explain'] = 'Choose yes if you invoke a program that stands in the Icy Phoenix dirs';
	$lang['Forum_link_hit_count'] = 'Hit count';
	$lang['Forum_link_hit_count_explain'] = 'Choose yes if you want the board to count and display the number of hits using this link';
	$lang['Forum_link_with_attachment_deny'] = 'You can\'t set a forum as a link if it has existing sub-levels';
	$lang['Forum_link_with_topics_deny'] = 'You can\'t set a forum as a link if it has existing topics in it';
	$lang['Forum_attached_to_link_denied'] = 'You can\'t attach a forum or a category to a forum link';

	$lang['Manage_extend'] = 'Management +';
	$lang['No_subforums'] = 'No sub-forums';
	$lang['Forum_type'] = 'Choose the kind of forum you want';
	$lang['Presets'] = 'Presets';
	$lang['Refresh'] = 'Refresh';
	$lang['Position_after'] = 'Position this forum after';
	$lang['Link_missing'] = 'The link is missing';
	$lang['Category_with_topics_deny'] = 'Topics remain in this forum. You can\'t change it into a category.';
	$lang['Recursive_attachment'] = 'You can\'t attach a forum to a lowest level of its own branch (recursive attachment)';
	$lang['Forum_with_attachment_denied'] = 'You can\'t change a category with forums attached to it into a forum';
	$lang['icon'] = 'Icon';
	$lang['icon_explain'] = 'This icon will be displayed in front of the forum title. You can set a direct URI or a $image[] key entry (see <i>your_template</i>/<i>your_template</i>.cfg).';

	// TAB - Calendar
	$lang['Lang_extend_topic_calendar'] = 'Topic Calendar';

	// TAB - SEO
	$lang['IP_url_rw'] = 'URL Rewrite';
	$lang['IP_url_rw_explain'] = 'By enabling this option URL Rewrite will be enabled (HTML links instead of PHP, for better bot spidering) for everybody.';

	$lang['IP_url_rw_guests'] = 'URL Rewrite For Guests';
	$lang['IP_url_rw_guests_explain'] = 'By enabling this option URL Rewrite will be enabled only for guests and bots.';

	$lang['IP_lofi_bots'] = 'LoFi For Bots';
	$lang['IP_lofi_bots_explain'] = 'By enabling this option LoFi will be enabled for bots.';

	//Sitemap
	$lang['Sitemap_settings'] = 'Sitemap Settings';

	$lang['IP_sitemap_topic_limit'] = 'Google Sitemap :: Topic Limit';
	$lang['IP_sitemap_topic_limit_explain'] = 'Maximum number of topics to fetch with a single database query';

	$lang['IP_sitemap_announce_priority'] = 'Google Sitemap :: Announcement Priority';
	$lang['IP_sitemap_announce_priority_explain'] = 'Priority for announcements (must be a number between 0.0 &amp; 1.0 inclusive)';

	$lang['IP_sitemap_sticky_priority'] = 'Google Sitemap :: Sticky Priority';
	$lang['IP_sitemap_sticky_priority_explain'] = 'Priority for sticky topics (must be a number between 0.0 &amp; 1.0 inclusive)';

	$lang['IP_sitemap_default_priority'] = 'Google Sitemap :: Default Priority';
	$lang['IP_sitemap_default_priority_explain'] = 'Priority for regular topics (must be a number between 0.0 &amp; 1.0 inclusive)';

	$lang['IP_sitemap_sort'] = 'Google Sitemap :: Sort Order';
	$lang['IP_sitemap_new_first'] = 'New posts first';
	$lang['IP_sitemap_old_first'] = 'Old posts first';

	$lang['Word_graph'] = 'TAGS';

	$lang['IP_word_graph_max_words'] = 'TAGS :: Maximum Words';
	$lang['IP_word_graph_max_words_explain'] = 'Select the maximum number of words to display. A higher number could affect server load. The recommended number is 250.';

	$lang['IP_word_graph_word_counts'] = 'TAGS :: Enable Word Counts';
	$lang['IP_word_graph_word_counts_explain'] = 'Display the total number of words next to each word?<br />Example: <b>Icy Phoenix (365)</b>?';

	$lang['IP_forum_wordgraph'] = 'TAGS :: Forum Tags';
	$lang['IP_forum_wordgraph_explain'] = 'This feature will enable a forum based tags table at the bottom of each forum';

	$lang['Similar_topics'] = 'Similar Topics';
	$lang['Similar_topics_explain'] = 'Configure search of similar topics.';

	$lang['IP_similar_topics'] = 'Similar Topics :: Enable Similar Topics';

	$lang['IP_similar_topics_desc'] = 'Similar Topics :: Take into account the description of a topics';

	$lang['IP_similar_stopwords'] = 'Similar Topics :: Exclude stop-words';

	$lang['IP_similar_max_topics'] = 'Similar Topics :: Maximum number of topics to show';

	$lang['IP_similar_sort_type'] = 'Similar Topics :: Sort by';
	$lang['IP_similar_sort_type_explain'] = 'Select sort method for the similar topics';
	$lang['IP_similar_sort_type_time'] = 'Post Time';
	$lang['IP_similar_sort_type_relev'] = 'Relevance';

	$lang['IP_similar_ignore_forums_ids'] = 'Similar Topics :: Ignored forums';
	$lang['IP_similar_ignore_forums_ids_explain'] = 'Enter the ID\'s of forums, in which the similar topics will be ignored (for example test forum, forum for talk, etc.). One ID per line.';

	// TAB - Logging And Security
	$lang['IP_admin_protect'] = 'Protect Main Admin Account';
	$lang['IP_admin_protect_explain'] = 'Enabling this option will add more security to Main Admin: it can\'t be demoted by others administrators or users.';

	$lang['IP_db_log_actions'] = 'Enable DB Actions Log';
	$lang['IP_db_log_actions_explain'] = 'By enabling this option any action that modifies the DB will be stored in a the DB. If this option has been set as true in constants.php, then cannot be disabled in ACP. If you select to have the reports, then extra files will be stored with all errors logged.';

	$lang['IP_mg_log_actions'] = 'Enable TXT Actions Log';
	$lang['IP_mg_log_actions_explain'] = 'By enabling this option any action that modifies the DB will be stored in a text file on the server (file will be stored in the LOGS folder). This file is not easy to read, but it may be useful under certain conditions. Enable it only if you know what your doing (site may slow down if you enable it).';

	$lang['IP_write_errors_log'] = 'Enable Site Errors Log';
	$lang['IP_write_errors_log_explain'] = 'By enabling this option all site errors (i.e. 404 file missing, bad requests, etc) will be logged into a daily TXT file. Remember that you need to enable errors redirecting to errors.php in .htaccess to use this feature (an example is included in .htaccess, just insert your domain and decomment the lines).';

	$lang['IP_write_digests_log'] = 'Enable Digests Log';
	$lang['IP_write_digests_log_explain'] = 'By enabling this option all digests sent will be logged into a daily TXT file.';

	$lang['IP_logs_path'] = 'Path for Logs (remember to CHMOD this folder to 0755 or 0777 as required)';
	$lang['IP_logs_path_explain'] = 'Insert the path for the errors and other logs relative to your root and without ending slash. Example: <b>logs</b>.';


	// lang_extend_mods_settings.php
	$lang['Lang_extend_mods_settings'] = 'Icy Phoenix Settings';
	$lang['Configuration_extend'] = 'Icy Phoenix';
	$lang['Override_user_choices'] = 'Override user choices';
}

$lang['CFG_NONE'] = 'NONE';
$lang['CFG_ALL'] = 'ALL';
$lang['CFG_REG'] = 'REG';
$lang['CFG_SELF'] = 'SELF';
$lang['CFG_PRIVATE'] = 'PRIVATE';
$lang['CFG_MOD'] = 'MOD';
$lang['CFG_ADMIN'] = 'ADMIN';

// lang_extend_categories_hierarchy.php - BEGIN
$lang['Hierarchy_setting'] = 'Forum';
$lang['Forum_link'] = 'Link redirection';
$lang['Forum_link_visited'] = 'This link has been visited %d times';

$lang['Use_sub_forum'] = 'Index packing';
$lang['Index_packing_explain'] = 'Choose the level of packing you want for the index';
$lang['Medium'] = 'Medium';
$lang['Full'] = 'Full';
$lang['Split_categories'] = 'Split categories on index';
$lang['Use_last_topic_title'] = 'Show the last topic titles on index';
$lang['Last_topic_title_length'] = 'Title length of the last topic on index';
$lang['Sub_level_links'] = 'Sub-level links on index';
$lang['Sub_level_links_explain'] = 'Add the links to the sub-levels in the forum or category description';
$lang['With_pics'] = 'With icons';
$lang['Display_viewonline'] = 'Display viewonline information box on index';
$lang['Never'] = 'Never';
$lang['Root_index_only'] = 'On root index only';
$lang['Always'] = 'Always';
$lang['Subforums'] = 'Subforums';
// lang_extend_categories_hierarchy.php - END

// lang_extend_topic_calendar.php - BEGIN
$lang['Calendar_settings'] = 'Calendar';
$lang['Calendar'] = 'Calendar';
$lang['Calendar_scheduler'] = 'Scheduler';
$lang['Calendar_event'] = 'Calendar event';
$lang['Calendar_from_to'] = 'From %s to %s (inclusive)';
$lang['Calendar_time'] = '%s';
$lang['Calendar_duration'] = 'During';

$lang['Calendar_week_start'] = 'First day of the week';
$lang['Calendar_header_cells'] = 'Number of cells to display on the board header (0 for no display)';
$lang['Calendar_title_length'] = 'Length of the title displayed in the calendar cells';
$lang['Calendar_text_length'] = 'Length of the text displayed in the overview windows';
$lang['Calendar_block_display'] = 'Display the calendar row on the board header';
$lang['Calendar_display_open'] = 'Display the calendar row on the board header opened (if calendar row enabled)';
$lang['Calendar_nb_row'] = 'Number of rows per day on the board header';
$lang['Calendar_birthday'] = 'Display birthday(s) in the calendar';
$lang['Calendar_forum'] = 'Display the forum name under the topic title in the scheduler';

$lang['Sorry_auth_cal'] = 'Sorry, but only %s can post calendar events in this forum.';
$lang['Date_error'] = 'day %d, month %d, year %d is not a valid date';

$lang['Event_time'] = 'Event time';
$lang['Minutes'] = 'Minutes';
$lang['Today'] = 'Today';
$lang['Yesterday'] = 'Yesterday';
$lang['All_events'] = 'All events';

/*
$lang['Rules_calendar_can'] = 'You <b>can</b> post calendar events in this forum';
$lang['Rules_calendar_cannot'] = 'You <b>cannot</b> post calendar events in this forum';
*/
$lang['Rules_calendar_can'] = 'You <b>can</b> post calendar events';
$lang['Rules_calendar_cannot'] = 'You <b>cannot</b> post calendar events';

$lang['birthday_header'] = 'Happy Birthday!';
$lang['birthday'] = '<b>%s</b> has a birthday today!';
// lang_extend_topic_calendar.php - END

$lang['DB_LOG_ALL'] = 'Yes with error reports';

//$lang[''] = '';

?>