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
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'05_Server_Settings' => 'Server',
	'10_Site_Settings' => 'Site Defaults',
	'15_Various_Settings' => 'Various Settings',
	'20_SQL_Charge' => 'SQL Optimization',
	'25_Users' => 'Users',
	'27_Social_Networks' => 'Social Networks',
	'30_Posting' => 'Posting And Messages',
	'40_IMG_Posting' => 'Images In Posts',
	'50_Hierarchy_setting' => 'Forum',
	'60_Calendar_settings' => 'Calendar',
	'70_SEO' => 'SEO',
	'80_Security' => 'Logs And Security',
	'90_Cron' => 'Cron',
	)
);

// admin part
if ($lang_extend_admin)
{
	$lang = array_merge($lang, array(
		'Lang_extend_icy_phoenix' => 'Icy Phoenix',

// TAB - SERVER
		'SITE_META_KEYWORDS' => 'Meta Keywords',
		'SITE_META_KEYWORDS_SWITCH' => 'Enable Meta Keywords From DB',
		'SITE_META_KEYWORDS_SWITCH_EXPLAIN' => 'If you enable meta keywords, then keywords specified above will be used in html pages instead of the keywords defined in <i>lang_main_settings.php</i>.',
		'SITE_META_DESCRIPTION' => 'Meta Description',
		'SITE_META_DESCRIPTION_SWITCH' => 'Enable Meta Description From DB',
		'SITE_META_DESCRIPTION_SWITCH_EXPLAIN' => 'If you enable meta description from db, then description specified above will be used in html pages instead of the one defined in <i>lang_main_settings.php</i>.',
		'SITE_META_AUTHOR' => 'Meta Author',
		'SITE_META_AUTHOR_SWITCH' => 'Enable Meta Author From DB',
		'SITE_META_AUTHOR_SWITCH_EXPLAIN' => 'If you enable meta author, then author specified above will be used in html pages instead of the one defined in <i>lang_main_settings.php</i>.',
		'SITE_META_COPYRIGHT' => 'Meta Copyright',
		'SITE_META_COPYRIGHT_SWITCH' => 'Enable Meta Copyright From DB',
		'SITE_META_COPYRIGHT_SWITCH_EXPLAIN' => 'If you enable meta copyright, then copyright specified above will be used in html pages instead of the copyright defined in <i>lang_main_settings.php</i>.',
		'IP_cookie_law' => 'Cookie Law',
		'IP_cookie_law_explain' => 'By enabling this feature a banner will be shown to guests on site landing to comply with EU cookie law',

// TAB - SITE
		'IP_mobile_style_disable' => 'Disable Mobile Devices Detection',
		'IP_mobile_style_disable_explain' => 'Usually when a mobile device connects to the site, the <b>Mobile</b> style is automatically enabled (it could be manually switched off by each user). If you want to disable this automatic detection, just select this option.',

// TAB - Icy Phoenix
		'IP_enable_xs_version_check' => 'Enable Icy Phoenix Version Check',
		'IP_enable_xs_version_check_explain' => 'Enable this option to check if a newer Icy Phoenix version is available to download each time you enter the ACP. Disabling this option may speed up ACP loading a bit. <br /><b>Note:</b> This check is done just once per day and then cached.',

		'IP_disable_email_error' => 'Disable errors on email sending',

		'IP_html_email' => 'HTML Email',
		'IP_html_email_explain' => 'Enabling this option will enable HTML emails, otherwise they will be simple text mail',

		'IP_emails_only_to_admins' => 'Emails Only To Admins',
		'IP_emails_only_to_admins_explain' => 'Allow email system only for sending emails to admins',

		'IP_ajax_features_title' => 'AJAX Features',

		'IP_ajax_features' => 'Enable AJAX Features',
		'IP_ajax_features_explain' => 'Some AJAX features integrated into the site',

		'IP_ajax_checks_register' => 'AJAX Check While Registering',
		'IP_ajax_checks_register_explain' => 'By enabling this option some real time checks are performed while filling some fields in the register form (Warning: this option may slow down the register page).',

		'IP_inactive_users_memberlists' => 'Inactive Users In Memberlist And Birthdays\' Block',
		'IP_inactive_users_memberlists_explain' => 'By enabling this option inactive users will be shown in the memberlist and birthdays\' block.',

		'IP_page_gen' => 'Show Page Generation Time on Footer',

		'IP_show_alpha_bar' => 'Show Alphabetical Sort Bar In View Forum (Global Switch)',
		'IP_show_alpha_bar_explain' => 'This option will show an alphabetical sort bar on top of viewforum page. You will need also to enable single forums in Forums Management section.',

		'IP_show_rss_forum_icon' => 'Forum Index Icons (Global Switch)',
		'IP_show_rss_forum_icon_explain' => 'This option will show icons on the right of every forum title (on Forum Index): RSS, New Topic. You will need also to enable single forums in Forums Management section.',

		'IP_allow_mods_view_self' => 'Allow ALL Moderators to View Self Auth Topics',
		'IP_allow_mods_view_self_explain' => 'If a forum has been set to SELF AUTH access only admins and forum moderators can access those topics through viewforum and viewtopic. However there are many features that may show contents of these kind of posts even if not authed: Recent Topics, Search, Recent Messages Blocks, Random Topics Blocks, etc. To prevent this, an extra feature has been coded which doesn\'t allow non authed people to access these topics via secondary ways. Anyway you can allow ALL moderators (not only admins) to view these messages through these secondary ways. If you set this to YES then ALL moderators will be allowed to see the content of these messages through: Recent, Search, Topics related blocks... Unfortunately if you switch this OFF then neither AUTHED moderators may access SELF AUTHED topics through secondary ways. The feature has been coded in this way to save extra SQL charge. If you don\'t use SELF AUTHED forums, then you don\'t need this function as well.',

		'IP_xmas_gfx' => 'Christmas Graphics',
		'IP_xmas_gfx_explain' => 'By enabling this option Christmas Graphics will be applied (please note that only some templates support this feature).',

		'IP_select_theme' => 'Change Style',
		'IP_select_theme_explain' => 'By enabling this option a select box with all the available styles will be added for fast style switching.',

		'IP_select_lang' => 'Change Lang',
		'IP_select_lang_explain' => 'By enabling this option a link to each available language will be created on forum index, for quick language switching.',

		'IP_cms_dock' => 'Show Apple style Dock in CMS',

		'IP_cms_style' => 'Enable AJAX in CMS',
		'IP_cms_style_explain' => 'Enabling this option will enable AJAX features in CMS by default',

		'IP_split_ga_ann_sticky' => 'Split Topic by Type',
		'IP_split_ga_ann_sticky_explain' => 'Here you can choose a way to split Topics by Type on the viewforum page',
		'IP_split_topic_0' => 'All Topic Types Together (no Split)',
		'IP_split_topic_1' => 'Global Announcements, Announcements and Stickies together, Normal Topics split',
		'IP_split_topic_2' => 'Global Announcements split, Announcements and Stickies joined together, Normal Topics split',
		'IP_split_topic_3' => 'All Topic Types Split',

// TAB - SQL Charge
		'IP_fast_n_furious' => 'Fast And Furious',
		'IP_fast_n_furious_explain' => 'Enabling this option some heavy SQL functions will be disabled, to hopefully speed up your site!',

		/*
		'IP_db_cron' => 'Database Optimize',
		'IP_db_cron_explain' => 'Enabling this option will enable Database Optimization.',
		*/

		'IP_site_history' => 'Site Statistics',
		'IP_site_history_explain' => 'Enabling this option some extra statistics will be stored in the DB (daily visits, posts, etc.).',

		'IP_active_sessions' => 'Limit Number Of Sessions',
		'IP_active_sessions_explain' => '<b>BE CAREFUL</b> with this value: this number is the maximum allowed number of sessions, if the limit is reached the site will be not accessible. If you don\'t know how to configure this limit, leave it to 0 (ZERO).',

		'IP_global_disable_upi2db' => 'Disable UPI2DB globally',
		'IP_global_disable_upi2db_explain' => 'This option lets you disable UPI2DB globally thus saving extra memory.',

		'IP_enable_own_icons' => 'Own Messages Icons',
		'IP_enable_own_icons_explain' => 'By enabling this option icons for topics which contain own messages will be marked.',

		'IP_show_forums_online_users' => 'Show Users Online In Forums',
		'IP_show_forums_online_users_explain' => 'This will enable online users counter for each forum on the index.',

		'IP_gsearch_guests' => 'Force Google Search for guests',

		'IP_visit_counter_switch' => 'Enable Visit Counter',

		'IP_enable_new_messages_number' => 'Show the number of new messages since last visit',

		'IP_disable_likes_posts' => 'Disable Posts Like (Global Switch)',
		'IP_disable_likes_posts_explain' => 'This option allows you to disable globally &quot;Like This Post&quot; feature',

		'IP_show_thanks_profile' => 'Show Likes received when viewing profile',

		'IP_show_thanks_viewtopic' => 'Show Likes received when viewing topics',

		'IP_disable_topic_view' => 'Disable "Who read this topic" (Global Switch)',
		'IP_disable_topic_view_explain' => 'This option allows you to disable &quot;Who read this topic&quot; feature (this saves SQL space).',

		'IP_disable_referers' => 'Disable Referers',
		'IP_disable_referers_explain' => 'This option allows you to disable Referers feature (this saves SQL space).',

		'IP_disable_logins' => 'Disable Logins Recording',
		'IP_disable_logins_explain' => 'By enabling this option User\'s Logins will no longer be recorded.',

		'IP_last_logins_n' => 'Number of Logins to Record',

		'IP_index_top_posters' => 'Top Posters :: Forum Index',
		'IP_index_top_posters_explain' => 'Enable this option to show top posters on <b>Forum Index</b>.',

		'IP_index_last_msgs' => 'Last Messages :: Forum Index',
		'IP_index_last_msgs_explain' => 'Enable this option to show the last messages on <b>Forum Index</b>.',

		'IP_online_last_msgs' => 'Last Messages :: Who Is Online',
		'IP_online_last_msgs_explain' => 'Enable this option to show the last messages on <b>Who Is Online</b>.',

		'IP_last_msgs_n' => 'Number of last messages to be shown.',

		'IP_last_msgs_x' => 'Forum To Exclude',
		'IP_last_msgs_x_explain' => 'Please, insert the IDs of the forums to be excluded in Last Messages Box (you can separate each forum ID with a comma).',

		'IP_show_chat_online' => 'AJAX Chat Online :: Forum Index',
		'IP_show_chat_online_explain' => 'Enabling this option will show on <b>Forum Index</b> users online in AJAX Chat.',

		'IP_index_shoutbox' => 'Shoutbox :: Forum Index',
		'IP_index_shoutbox_explain' => 'Enabling this option will enable Shoutbox on <b>Forum Index</b>.',

		'IP_online_shoutbox' => 'Shoutbox :: Who Is Online',
		'IP_online_shoutbox_explain' => 'Enabling this option will enable Shoutbox on <b>Who Is Online</b>.',

		'IP_img_shoutbox' => 'Disable [img][/img] bbcode on Shoutbox',
		'IP_img_shoutbox_explain' => 'Enabling this option will disable [img][/img] bbcode on Shoutbox.',

		'IP_index_birthday' => 'Birthday :: Forum Index',
		'IP_index_birthday_explain' => 'Enabling this option will enable Birthdays on <b>Forum Index</b>.',

		'IP_show_random_quote' => 'Random Quotes :: Forum Index',
		'IP_show_random_quote_explain' => 'Enabling this option will enable random quotes to be shown on <b>Forum Index</b>',

// TAB - Users
		'IP_user_allow_pm_register' => 'Enable PM (Private Messages) for new users',
		'IP_user_allow_pm_register_explain' => 'Enabling this option will enable PM for new registered users. If disabled, new users will not be able to send PM unless an admin enables the option for the specific user.',

// Spam Section
		'IP_spam_measures_header' => 'Spam Measures',

		'IP_spam_posts_number' => 'SPAM - Minimum Number Of Posts To Avoid Spam Measures',
		'IP_spam_posts_number_explain' => 'Until a user reaches the specified number of posts spam measures will be kept in place (those with SPAM prefix here below)',
		'IP_spam_p_0' => 'Disabled',
		'IP_spam_p_3' => '3',
		'IP_spam_p_5' => '5',
		'IP_spam_p_10' => '10',
		'IP_spam_p_20' => '20',

		'IP_spam_disable_url' => 'SPAM - Disable URLs In Posts',
		'IP_spam_disable_url_explain' => 'This feature remove all URLs in posts and replace HTTP with H**P, this feature will remain in place for a minimum of posts specified in the field above. Administrators will see the original message anyway.',

		'IP_spam_hide_signature' => 'SPAM - Disable Signature And Web In Posts',
		'IP_spam_hide_signature_explain' => 'This feature will hide the signature and personal website information for all those users who didn\'t reach a minimum of posts specified in the field above. Administrators will see the signature anyway.',

		'IP_spam_post_edit_interval' => 'SPAM - Disable Post Edit',
		'IP_spam_post_edit_interval_explain' => 'By enabling this feature, all users who didn\'t reach the required amount of posts will not be able to edit their posts after the specified interval',
		'IP_time_15m' => '15 Minutes',
		'IP_time_30m' => '30 Minutes',
		'IP_time_1h' => '1 Hour',
		'IP_time_2h' => '2 Hours',
		'IP_time_6h' => '6 Hours',
		'IP_time_12h' => '12 Hours',
		'IP_time_24h' => '24 Hours',

// TAB - Social networks
		'Enable_Social_Networks_Login' => 'Enable Social Networks Login (Global Switch)',
		'Enable_Social_Networks_Login_Explain' => 'Allow users login and register using their social networks accounts.',
		'Facebook_Login_Settings' => 'Facebook Login Settings',
		'Facebook_Login_Settings_explain' => 'Please follow the instructions to get your App ID and App Secret:<br />- Visit the <a href="https://developers.facebook.com/" target="_blank">Facebook Developers website</a>.<br />- Login with your Facebook account.<br />- Create a new application.<br />- Disable "Sandbox Mode".<br />- Include your App Domain, with no http/https nor www (ie icyphoenix.com).<br />- Enable the option "Website with Facebook Login", and insert your website url, with http/https AND www (ie http://www.icyphoenix.com).<br />- Save the changes.<br />- Write your App ID and App Secret below.',
		'Enable_Facebook_Login' => 'Enable Facebook Login',
		'Enable_Facebook_Login_explain' => 'Allow users to login and register using their Facebook account. Remember adding your app tokens below.',
		'Facebook_App_ID' => 'App ID',
		'Facebook_App_Secret' => 'App Secret',
		'Google_Login_Settings' => 'Google Login Settings',
		'Google_Login_Settings_explain' => 'Please follow the instructions to get your App ID and App Secret:<br />- Visit the <a href="https://console.developers.google.com/project?pli=1" target="_blank">Google Developers Console website</a>.<br />- Login with your Google account.<br />- Create a new project.<br />- Click on "Enable and Use Google APIs"<br />- Click "Credentials"<br />- Click on "OAuth consent screen"<br />- Fill the fields (Homepage URL, with http://, i.e. <i>http://icyphoenix.com</i>, and Product Name)<br />- Save<br />- Click "New credentials"<br />- Select "Oauth client ID"<br />- Check "Web application"<br />- Fill in the fields (name, "Authorized JavaScript origins" should be your domain, like <i>icyphoenix.com</i> or <i>web.example.com</i>). In "redirect URIs", add your domain, prefixed with <i>http(s)://</i>, with <i>/login_ip.php</i> at the end, i.e. <i>http://example.com/my/directory/login_ip.php</i><br />- Save the changes.<br />- Write your Client ID and Client Secret below.',
		'Enable_Google_Login' => 'Enable Google Login',
		'Enable_Google_Login_explain' => 'Allow users to login and register using their Google account. Remember adding your app tokens below.',
		'Google_App_ID' => 'Client ID',
		'Google_App_Secret' => 'Client Secret',

// TAB - Posting
		'IP_posts_precompiled' => 'Disable Precompiled Posts For Guests',
		'IP_posts_precompiled_explain' => 'By enabling this option viewtopic will always parse posts text without using the precompiled text for guests (this is slower, but it can be useful in some cases).',

		'IP_read_only_forum' => 'Disable posting in all forums (Read Only Mode)',
		'IP_read_only_forum_explain' => 'This options allows to lock posting in all forums without having to change permissions. This may be useful for limited periods of time when admins would like to disallow users posting without having to lock the site or change all forum authorizations. Admins will still be able to post.',

		'IP_allow_drafts' => 'Allow Drafts',
		'IP_allow_drafts_explain' => 'Allow users to save posts as drafts',

		'IP_allow_mods_edit_admin_posts' => 'Can Moderators edit Admin posts?',
		'IP_allow_mods_edit_admin_posts_explain' => 'Allow moderators to edit admin posts',

		'IP_forum_limit_edit_time_interval' => 'Limit Edit Time Interval',
		'IP_forum_limit_edit_time_interval_explain' => 'This sets the time interval for users to be allowed to edit own messages. Set to ZERO for no limits (feature should be enabled on a per forum basis in Forums Management). This setting will be applied to all users regardless to their number of posts, so it is different from the similar antispam feature which applies only for users who didn\'t reach a certain amount of posts.',

		'IP_force_large_caps_mods' => 'ProperCase subjects',
		'IP_force_large_caps_mods_explain' => 'Topic subjects will be converted to proper case for all users except admins',

		'IP_show_new_reply_posting' => 'Warn For New Replies',
		'IP_show_new_reply_posting_explain' => 'If you enable this, a warning will be shown when there are new replies while you are replying a topic',

		'IP_no_bump' => 'Forbid bumping within 24 hours',
		'IP_no_bump_explain' => 'Enabling this option last posters won\'t be able to post within 24 hours from their last post unless someone else has posted a reply (never applies to admins)',
		'MODS_ALLOWED' => 'Moderators Can Post',

		'IP_robots_index_topics_no_replies' => 'Enable Robots Indexing Topics No Replies',
		'IP_robots_index_topics_no_replies_explain' => 'Enabling this option will enable indexing for topics with no replies, otherwise topics with no replies will not be indexed',

		'IP_use_jquery_tags' => 'TAGS :: Enable jQuery Tags',
		'IP_use_jquery_tags_explain' => 'Enabling this option will enable jQuery Topics Tags when posting or editing a topic (more efficient and nice tags input)',

		'IP_display_tags_box' => 'TAGS :: Display Topics Tags',
		'IP_display_tags_box_explain' => 'Enabling this option will enable Topics Tags (tags could be used for indexing purpose): tags could be inserted / edited only by administrators (or moderators if you enable the switch below) to avoid spam',

		'IP_allow_moderators_edit_tags' => 'TAGS :: Allow Moderators To Edit Tags',
		'IP_allow_moderators_edit_tags_explain' => 'Enabling this option will allow Moderators to edit Topics Tags',

		'IP_show_topic_description' => 'Enable Topic Description',
		'IP_show_topic_description_explain' => 'Enabling this option will enable Topic Description while posting and browsing forums',

		'IP_edit_notes' => 'Enable Edit Notes',
		'IP_edit_notes_explain' => 'Enabling this option will enable Edit Notes',

		'IP_edit_notes_n' => 'Maximum Edit Notes',

		'IP_always_show_edit_by' => 'Always Show Posts Edit',
		'IP_always_show_edit_by_explain' => 'Enabling this option will always show "Last edit by..." on the message footer when someone modifies it. Admins edits are not shown by default',

		'IP_enable_featured_image' => 'Enable Topics Featured Image',
		'IP_enable_featured_image_explain' => 'Enabling this option will allow the user to upload a picture to be used as a &quot;Featured Image&quot; for the topic',

		'IP_show_social_bookmarks' => 'Social Bookmarks',
		'IP_show_social_bookmarks_explain' => 'Show Social Bookmarks section when viewing topics',

		'IP_link_this_topic' => 'Link this topic',
		'IP_link_this_topic_explain' => 'Show "Link this topic" box when viewing topics',

		'IP_smilies_topic_title' => 'Smileys for Topic Title and Description',
		'IP_smilies_topic_title_explain' => 'Enabling this option will enable smileys for Topic Title and Topic Description',

		'IP_enable_colorpicker' => 'Enable ColorPicker in posting',

		'IP_quote_iterations' => 'Max number of nested quotes',

		'IP_ftr_disable' => 'Disable Force Topic Read',
		'IP_ftr_disable_explain' => 'By enabling this option Force Topic Read will be disabled',

		'IP_disable_html_guests' => 'Disable HTML links for guests',

		'IP_birthday_viewtopic' => 'Show poster\'s Age on Topics',

		'IP_switch_poster_info_topic' => 'Show poster\'s Info on Topics (Lang, Style, etc.)',

		'IP_enable_quick_quote' => 'Enable Quick Quote and Off Topic',
		'IP_enable_quick_quote_explain' => 'Quick Quote allows users to quote any post in a topic with a simple click. This feature uses JavaScript, and enabling it can result in large pages if there are a lot of posts with a lot of text in a topic.',

		'IP_allow_html_only_for_admins' => 'Enable HTML for Administrators only',
		'IP_allow_html_only_for_admins_explain' => 'Enabling this option will allow administrators to use HTML tags in posts. Please notice that this feature may lead to security issues or wrong page formatting if not used properly.',

		'IP_enable_custom_bbcodes' => 'Enable Custom BBCodes',
		'IP_enable_custom_bbcodes_explain' => 'This option will enable customized BBCodes created in ACP.',

		'IP_allow_all_bbcode' => 'Enable all BBCodes',
		'IP_allow_all_bbcode_explain' => 'Enabling this option will allow all BBCodes in signatures and other places where usually they are not active. BBCodes which are usually disabled in signature are: IMG, ALBUMIMG and some intensive formatting BBCodes. If you enable this option, some signatures may result in consuming both space and resources.',

		'IP_switch_bbcb_active_content' => 'Allow BBCode for Active Content in posts',
		'IP_switch_bbcb_active_content_explain' => 'Activates BBCode for Flash, Video, Audio Streams, RealMedia and Quicktime.',

// TAB - Images In Posts
		'IP_auth_view_pic_upload' => 'Pics Upload Permissions (Post Icy Images)',

		'IP_enable_postimage_org' => 'Enable PostImage button in post form',

		'IP_gd_version' => 'GD Version:',
		'GD_0' => 'No GD',
		'GD_1' => 'GD1',
		'GD_2' => 'GD2',

		'IP_show_img_no_gd' => 'Show GIF thumbnails without using GD libraries (full images are loaded and then just shown resized).',

		'IP_thumbnail_posts' => 'Thumbnails In Posts',
		'IP_thumbnail_posts_explain' => 'With this option a thumbnail will be shown instead of a full picture when an image is posted using IMG BBCode',

		'IP_show_pic_size_on_thumb' => 'Show Image Size On Thumbnails',

		'IP_thumbnail_highslide' => 'Use HighSlide to show images if thumbnails are enabled',
		'IP_thumbnail_highslide_explain' => 'Opens the image in front of the current page instead of opening it in a new window. More about <a href="http://www.highslide.com/" target="_blank">HighSlide JS...</a><br />Make sure you empty the precompiled posts after changing this setting!',

		'IP_thumbnail_cache' => 'Thumbnails Cache',

		'IP_thumbnail_quality' => 'Thumbnails Quality (1-100)',

		'IP_thumbnail_size' => 'Post Thumbnails Size (in pixels, default = 450)',
		'IP_thumbnail_size_explain' => 'All pictures in posts will be resized to this size if the option to display &quot;Thumbnails In Posts&quot; is enabled',

		'IP_thumbnail_s_size' => 'Images List Thumbnails Size (in pixels, default = 120)',
		'IP_thumbnail_s_size_explain' => 'Size for pictures in images lists pages',

		'IP_img_size_max_mp' => 'Max Image Upload Size',
		'IP_img_size_max_mp_explain' => 'Select the maximum allowed upload size for images (in megabytes, default = 1): make sure your server settings will allow such a size to be uploaded',
		'MB_1' => '1MB',
		'MB_2' => '2MB',
		'MB_3' => '3MB',
		'MB_5' => '5MB',
		'MB_7' => '7MB',

		'IP_img_list_cols' => 'Images List Columns (default = 4)',
		'IP_img_list_cols_explain' => 'Number of columns in Images List page',

		'IP_img_list_rows' => 'Images List Rows (default = 5)',
		'IP_img_list_rows_explain' => 'Number of rows in Images List page',


// TAB - Forum
		'Lang_extend_categories_hierarchy' => 'Categories Hierarchy',

		'Category_attachment' => 'Attached to',
		'Category_desc' => 'Description',
		'Category_config_error_fixed' => 'An error in the category setup has been fixed',
		'Attach_forum_wrong' => 'You can\'t attach a forum to a forum',
		'Attach_root_wrong' => 'You can\'t attach a forum to the forum index',
		'Forum_name_missing' => 'You can\'t create a forum without a name',
		'Category_name_missing' => 'You can\'t create a category without a name',
		'Only_forum_for_topics' => 'Topics can only be found in forums',
		'Delete_forum_with_attachment_denied' => 'You can\'t delete forums having sub-levels',

		'Category_delete' => 'Delete Category',
		'Category_delete_explain' => 'The form below will allow you to delete a category and decide where you want to put all forums and categories it contained.',

// forum links type
		'Forum_link_url' => 'Link URL',
		'Forum_link_url_explain' => 'Set a URI to an Icy Phoenix file or a full URL to an external server',
		'Forum_link_internal' => 'Icy Phoenix File',
		'Forum_link_internal_explain' => 'Choose yes if you invoke a program that stands in the Icy Phoenix dirs',
		'Forum_link_hit_count' => 'Hit count',
		'Forum_link_hit_count_explain' => 'Choose yes if you want the board to count and display the number of hits using this link',
		'Forum_link_with_attachment_deny' => 'You can\'t set a forum as a link if it has existing sub-levels',
		'Forum_link_with_topics_deny' => 'You can\'t set a forum as a link if it has existing topics in it',
		'Forum_attached_to_link_denied' => 'You can\'t attach a forum or a category to a forum link',

		'Manage_extend' => 'Management +',
		'No_subforums' => 'No sub-forums',
		'Forum_type' => 'Choose the kind of forum you want',
		'Presets' => 'Presets',
		'Refresh' => 'Refresh',
		'Position_after' => 'Position this forum after',
		'Link_missing' => 'The link is missing',
		'Category_with_topics_deny' => 'Topics remain in this forum. You can\'t change it into a category.',
		'Recursive_attachment' => 'You can\'t attach a forum to a lowest level of its own branch (recursive attachment)',
		'Forum_with_attachment_denied' => 'You can\'t change a category with forums attached to it into a forum',
		'icon' => 'Icon',
		'icon_explain' => 'This icon will be displayed in front of the forum title. You can set a direct URI or a $image[] key entry (see <i>your_template</i>/<i>your_template</i>.cfg).',

// TAB - Calendar
		'Lang_extend_topic_calendar' => 'Topic Calendar',

// TAB - SEO
		'IP_url_rw' => 'URL Rewrite',
		'IP_url_rw_explain' => 'By enabling this option URL Rewrite will be enabled (HTML links instead of PHP, for better bot spidering) for everybody.',

		'IP_url_rw_guests' => 'URL Rewrite For Guests',
		'IP_url_rw_guests_explain' => 'By enabling this option URL Rewrite will be enabled only for guests and bots.',

		'IP_bots_reg_auth' => 'Bots REG Permission Level',
		'IP_bots_reg_auth_explain' => 'By enabling this option Bots will be given the same access level of registered users.',

		'IP_lofi_bots' => 'LoFi For Bots',
		'IP_lofi_bots_explain' => 'By enabling this option LoFi will be enabled for bots.',

		'IP_seo_cyrillic' => 'Cyrillic Chars Conversion',
		'IP_seo_cyrillic_explain' => 'By enabling this option some cyrillic characters will be converted to latin characters (not in posts, but in keywords, tags and where the clean strings function is used).',

		'IP_adsense_code' => 'Google AdSense Publisher Code',
		'IP_adsense_code_explain' => 'Insert here your Google AdSense Publisher Code and it will be inserted in the Google Search page. If you don\'t want to use it, just leave this field blank.',

		'IP_google_analytics' => 'Google Analytics Code',
		'IP_google_analytics_explain' => 'Insert here your Google Analytics Code (the Javascript provided by Google site) and it will be automatically inserted at the bottom of every page.',

		'IP_google_custom_search' => 'Google Custom Search Code',
		'IP_google_custom_search_explain' => 'Insert here your Google Custom Search Code to enable gsearch.php feature (allow guests to use Google Search to save some SQL).',

//Sitemap
		'Sitemap_settings' => 'Sitemap Settings',

		'IP_sitemap_topic_limit' => 'Google Sitemap :: Topic Limit',
		'IP_sitemap_topic_limit_explain' => 'Maximum number of topics to fetch with a single database query',

		'IP_sitemap_announce_priority' => 'Google Sitemap :: Announcement Priority',
		'IP_sitemap_announce_priority_explain' => 'Priority for announcements (must be a number between 0.0 &amp; 1.0 inclusive)',

		'IP_sitemap_sticky_priority' => 'Google Sitemap :: Sticky Priority',
		'IP_sitemap_sticky_priority_explain' => 'Priority for sticky topics (must be a number between 0.0 &amp; 1.0 inclusive)',

		'IP_sitemap_default_priority' => 'Google Sitemap :: Default Priority',
		'IP_sitemap_default_priority_explain' => 'Priority for regular topics (must be a number between 0.0 &amp; 1.0 inclusive)',

		'IP_sitemap_sort' => 'Google Sitemap :: Sort Order',
		'IP_sitemap_new_first' => 'New posts first',
		'IP_sitemap_old_first' => 'Old posts first',

//Tags
		'IP_word_graph_max_words' => 'TAGS :: Maximum Words',
		'IP_word_graph_max_words_explain' => 'Select the maximum number of words to display. A higher number could affect server load. The recommended number is 250.',

		'IP_word_graph_word_counts' => 'TAGS :: Enable Word Counts',
		'IP_word_graph_word_counts_explain' => 'Display the total number of words next to each word?<br />Example: <b>Icy Phoenix (365)</b>?',

		'IP_forum_wordgraph' => 'TAGS :: Forum Tags (Global Switch)',
		'IP_forum_wordgraph_explain' => 'This feature will enable a forum based tags table at the bottom of each forum. You will need also to enable single forums in Forums Management section.',

		'IP_forum_tags_type' => 'TAGS :: Forum Tags Type',
		'IP_forum_tags_type_explain' => 'You can choose whether to display Wordgraph (word taken from search tables) or Tags (tags specified in topics)',
		'IP_forum_tags_type_tags' => 'Tags',
		'IP_forum_tags_type_wordgraph' => 'Wordgraph',

		'Similar_topics' => 'Similar Topics',
		'Similar_topics_explain' => 'Configure search of similar topics.',

		'IP_similar_topics' => 'Similar Topics :: Similar Topics (Global Switch)',
		'IP_similar_topics_explain' => 'This is the global switch for Similar Topics. If you want to switch on this feature you will need also to enable single forums in Forums Management section.',

		'IP_similar_topics_desc' => 'Similar Topics :: Take into account the description of a topics',

		'IP_similar_stopwords' => 'Similar Topics :: Exclude stop-words',

		'IP_similar_max_topics' => 'Similar Topics :: Maximum number of topics to show',

		'IP_similar_sort_type' => 'Similar Topics :: Sort by',
		'IP_similar_sort_type_explain' => 'Select sort method for the similar topics',
		'IP_similar_sort_type_time' => 'Post Time',
		'IP_similar_sort_type_relev' => 'Relevance',

		'IP_similar_ignore_forums_ids' => 'Similar Topics :: Ignored forums',
		'IP_similar_ignore_forums_ids_explain' => 'Enter the ID\'s of forums, in which the similar topics will be ignored (for example test forum, forum for talk, etc.). One ID per line.',

// TAB - Logging And Security
		'IP_ip_admins_only' => 'Display IP Addresses To Admins Only',
		'IP_ip_admins_only_explain' => 'Enabling this option will allow only administrators to view IP addresses in forums and profiles (disabling this option will allow also moderators to display IP addresses).',

		'IP_db_log_actions' => 'Enable DB Actions Log',
		'IP_db_log_actions_explain' => 'By enabling this option any action that modifies the DB will be stored in a the DB. If this option has been set as true in constants.php, then cannot be disabled in ACP. If you select to have the reports, then extra files will be stored with all errors logged.',

		'IP_mg_log_actions' => 'Enable TXT Actions Log',
		'IP_mg_log_actions_explain' => 'By enabling this option any action that modifies the DB will be stored in a text file on the server (file will be stored in the LOGS folder). This file is not easy to read, but it may be useful under certain conditions. Enable it only if you know what your doing (site may slow down if you enable it).',

		'IP_write_errors_log' => 'Enable Site Errors Log',
		'IP_write_errors_log_explain' => 'By enabling this option all site errors (i.e. 404 file missing, bad requests, etc) will be logged into a daily TXT file. Remember that you need to enable errors redirecting to errors.php in .htaccess to use this feature (an example is included in .htaccess, just insert your domain and decomment the lines).',

		'IP_write_digests_log' => 'Enable Digests Log',
		'IP_write_digests_log_explain' => 'By enabling this option all digests sent will be logged into a daily TXT file.',

		'IP_logs_path' => 'Path for Logs (remember to CHMOD this folder to 0755 or 0777 as required)',
		'IP_logs_path_explain' => 'Insert the path for the errors and other logs relative to your root and without ending slash. Example: <b>logs</b>.',

// TAB - Cron
		'IP_cron_global_switch' => 'Enable PHP Cron [Global Switch]',
		'IP_cron_global_switch_explain' => 'By enabling this option a PHP based cron will be activated: some automatic operations will be executed at fixed time intervals. The optimal time range for each cron feature depends on your site traffic and preferences: if you don\'t know what these settings may impact, please leave this feature disabled, you probably don\'t need it.',

		'IP_cron_digests_interval' => 'Digests PHP Cron',
		'IP_cron_digests_interval_explain' => 'This feature will enable a PHP emulation of the CRON trying to send digests emails once per hour, but since it is based on a PHP emulation it may not be correctly executed every time. This means that sometimes emails may not be sent. If you can enable CRON on your server, please select <b>Server Cron</b> and make sure you manually enable the file to allow digests to be run via server.<br /><br /><b>Last run: ' . (($config['cron_digests_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_digests_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_birthdays_interval' => 'Birthdays PHP Cron',
		'IP_cron_birthdays_interval_explain' => 'This feature will enable a PHP emulation of the CRON trying to send birthdays greeting emails, but since it is based on a PHP emulation it may not be correctly executed every time. This means that sometimes emails may not be sent.<br /><br /><b>Last run: ' . (($config['cron_birthdays_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_birthdays_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_files_interval' => 'Files Executions Cron Interval',
		'IP_cron_files_interval_explain' => 'This kind of cron may be used to automatically run certain files every fixed interval you decide. The files to be executed must be added in <b>constants.php</b> &raquo; <b>define(\'CRON_FILES\', \'\');</b>. Multiple files must be separated by comma.<br /><br /><b>Last run: ' . (($config['cron_files_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_files_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_database_interval' => 'DB Optimization Cron Interval',
		'IP_cron_database_interval_explain' => 'This feature will optimize the database of the site every chosen interval.<br /><br /><b>Last run: ' . (($config['cron_database_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_database_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_cache_interval' => 'Tidy Templates Cache Cron Interval',
		'IP_cron_cache_interval_explain' => 'Templates cache is cleaned every chosen interval.<br /><br /><b>Last run: ' . (($config['cron_cache_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_cache_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_sql_interval' => 'Tidy SQL Cache Cron Interval',
		'IP_cron_sql_interval_explain' => 'SQL cache is cleaned every chosen interval.<br /><br /><b>Last run: ' . (($config['cron_sql_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_sql_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_users_interval' => 'Tidy Users Cache Cron Interval',
		'IP_cron_users_interval_explain' => 'Users cache is cleaned every chosen interval.<br /><br /><b>Last run: ' . (($config['cron_users_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_users_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_topics_interval' => 'Tidy Topics Cache Cron Interval',
		'IP_cron_topics_interval_explain' => 'Topics cache is cleaned every chosen interval.<br /><br /><b>Last run: ' . (($config['cron_topics_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_topics_last_run']), $config['board_timezone'])) . '</b>',

		'IP_cron_sessions_interval' => 'Tidy Sessions Cron Interval',
		'IP_cron_sessions_interval_explain' => 'Sessions tables are cleaned every chosen interval.<br /><br /><b>Last run: ' . (($config['cron_sessions_last_run'] == 0) ? 'NEVER' : create_date('d M Y  - H.i.s', ($config['cron_sessions_last_run']), $config['board_timezone'])) . '</b>',

		'Cron_Disabled' => 'Disabled',
		'Cron_Server' => 'Server Cron',
		'15M' => '15 Minutes',
		'30M' => '30 Minutes',
		'1H' => '1 Hour',
		'2H' => '2 Hours',
		'3H' => '3 Hours',
		'6H' => '6 Hours',
		'12H' => '12 Hours',
		'1D' => '1 Day',
		'3D' => '3 Days',
		'7D' => '1 Week',
		'14D' => '2 Weeks',
		'30D' => '1 Month',


// lang_extend_settings.php
		'Lang_extend_settings' => 'Icy Phoenix Settings',
		'Configuration_extend' => 'Icy Phoenix',
		'Override_user_choices' => 'Override user choices',
		)
	);
}

$lang = array_merge($lang, array(
	'CFG_NONE' => 'NONE',
	'CFG_ALL' => 'ALL',
	'CFG_REG' => 'REG',
	'CFG_SELF' => 'SELF',
	'CFG_PRIVATE' => 'PRIVATE',
	'CFG_MOD' => 'MOD',
	'CFG_ADMIN' => 'ADMIN',

// lang_extend_categories_hierarchy.php - BEGIN
	'Hierarchy_setting' => 'Forum',
	'Forum_link' => 'Link redirection',
	'Forum_link_visited' => 'This link has been visited %d times',

	'Use_sub_forum' => 'Index packing',
	'Index_packing_explain' => 'Choose the level of packing you want for the index',
	'List' => 'List',
	'Medium' => 'Medium',
	'Full' => 'Full',
	'Split_categories' => 'Split categories on index',
	'Use_last_topic_title' => 'Show the last topic titles on index',
	'Last_topic_title_length' => 'Title length of the last topic on index',
	'Sub_level_links' => 'Sub-level links on index',
	'Sub_level_links_explain' => 'Add the links to the sub-levels in the forum or category description',
	'With_pics' => 'With icons',
	'Display_viewonline' => 'Display viewonline information box on index',
	'Never' => 'Never',
	'Root_index_only' => 'On root index only',
	'Always' => 'Always',
	'Subforums' => 'Subforums',
// lang_extend_categories_hierarchy.php - END

// lang_extend_topic_calendar.php - BEGIN
	'Calendar_settings' => 'Calendar',
	'Calendar' => 'Calendar',
	'Calendar_scheduler' => 'Scheduler',
	'Calendar_event' => 'Calendar Event',
	'Calendar_from_to' => 'From %s to %s (inclusive)',
	'Calendar_time' => '%s',
	'Calendar_duration' => 'During',

	'Calendar_week_start' => 'First day of the week',
	'Calendar_header_cells' => 'Number of cells to display on the board header (0 for no display)',
	'Calendar_title_length' => 'Length of the title displayed in the calendar cells',
	'Calendar_text_length' => 'Length of the text displayed in the overview windows',
	'Calendar_block_display' => 'Display the calendar row on the board header',
	'Calendar_display_open' => 'Display the calendar row on the board header opened (if calendar row enabled)',
	'Calendar_nb_row' => 'Number of rows per day on the board header',
	'Calendar_birthday' => 'Display birthday(s) in the calendar',
	'Calendar_forum' => 'Display the forum name under the topic title in the scheduler',

	'Sorry_auth_cal' => 'Sorry, but only %s can post calendar events in this forum.',
	'Date_error' => 'day %d, month %d, year %d is not a valid date',

	'Event_time' => 'Event time',
	'Minutes' => 'Minutes',
	'Today' => 'Today',
	'Yesterday' => 'Yesterday',
	'All_events' => 'All events',

/*
	'Rules_calendar_can' => 'You <b>can</b> post calendar events in this forum',
	'Rules_calendar_cannot' => 'You <b>cannot</b> post calendar events in this forum',
*/
	'Rules_calendar_can' => 'You <b>can</b> post calendar events',
	'Rules_calendar_cannot' => 'You <b>cannot</b> post calendar events',

	'birthday_header' => 'Happy Birthday!',
	'birthday' => '<b>%s</b> has a birthday today!',
// lang_extend_topic_calendar.php - END

	'DB_LOG_ALL' => 'Yes with error reports',

// AJAX Refresh
	'IP_auto_refresh_viewtopic_interval' => 'AJAX :: Viewtopic refresh interval',
	'IP_auto_refresh_viewtopic_interval_explain' => 'Define how often, in milliseconds, should the topic view page refresh itself for new posts. Use 0 to disable the feature. Recommended: 5000 ms, so 5 seconds. Cannot be lower than 300 ms (overloads server).',
	)
);

?>