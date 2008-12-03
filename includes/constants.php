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

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// FOUNDER ID, this is the ID used to send Welcome and Birthday PM
define('FOUNDER_ID', '2');
// ALLOW ONLY FOUNDER ACP, by setting this to true only the FOUNDER will be able to access ACP
define('ONLY_FOUNDER_ACP', false);
// Insert here the IDs of the main admins separated by commas, they will be able to access special features in ACP
// Allowed features: Action Logs, Private Messages, DB Tools, Templates Edit (XS Style)
// If the constant is deleted then every admin is able to access the Full ACP
define('MAIN_ADMINS_ID', '2');

// Debug Level
define('DEBUG', true); // Debugging on
//define('DEBUG', false); // Debugging off

// Output all SQL to a file in cache... do not use unless strictly needed!!!
//@define('SQL_DEBUG_LOG', true);

// Cache SQL Queries
define('CACHE_SQL', true); // Caching SQL Queries ON
//define('CACHE_SQL', false); // Caching SQL Queries OFF

// Cache Config Tables
define('CACHE_CFG', true); // Caching Config Tables ON
//define('CACHE_CFG', false); // Caching Config Tables OFF

// Cache CH SQL
define('CACHE_CH_SQL', true); // Caching CH SQL ON
//define('CACHE_CH_SQL', false); // Caching CH SQL OFF

// Session Refresh Seconds
define('SESSION_REFRESH', 120); // Session Refresh Time (Seconds)
define('ONLINE_REFRESH', 600); // Online Refresh Time (Seconds)
define('COLORIZE_CACHE_REFRESH', 2592000); // Caching time for colorize cache (seconds) (60*60*24=86400) (86400*30=2592000)

// Mighty Gorgon - Constants Pages And Paths - BEGIN
define('LOGIN_MG', 'login_ip.' . PHP_EXT);
//define('LOGIN_MG', 'login.' . PHP_EXT);
define('PORTAL_MG', 'index.' . PHP_EXT);
//define('PORTAL_MG', 'portal.' . PHP_EXT);
define('FORUM_MG', 'forum.' . PHP_EXT);
//define('FORUM_MG', 'index.' . PHP_EXT);
define('VIEWFORUM_MG', 'viewforum.' . PHP_EXT);
define('VIEWTOPIC_MG', 'viewtopic.' . PHP_EXT);
define('PROFILE_MG', 'profile.' . PHP_EXT);
define('POSTING_MG', 'posting.' . PHP_EXT);
define('SEARCH_MG', 'search.' . PHP_EXT);
define('DOWNLOADS_MG', 'dload.' . PHP_EXT);
//define('DOWNLOADS_MG', 'downloads.' . PHP_EXT);
define('ADM', 'adm');
define('COMMON_TPL', '../common/');
define('ADM_TPL', COMMON_TPL . 'acp/');
define('CMS_TPL', COMMON_TPL . 'cms/');
define('STATS_TPL', 'stats_modules/');
define('STYLES_PATH', 'styles/');
define('ATTACH_MOD_PATH', 'includes/attach_mod/');
define('DOWNLOADS_PATH', 'downloads/');
define('DL_ROOT_PATH', 'includes/downloads/');
define('PA_FILE_DB_PATH', 'includes/pafiledb/');
define('FILES_ICONS_DIR', 'images/files/');
define('POSTED_IMAGES_PATH', IP_ROOT_PATH . 'files/posted_images/');
define('POSTED_IMAGES_THUMBS_PATH', IP_ROOT_PATH . 'files/thumbs/');
define('MAIN_CACHE_FOLDER', IP_ROOT_PATH . 'cache/');
define('SQL_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'sql/');
define('TOPICS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'topics/');
define('USERS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'users/');
define('BACKUP_PATH', 'backup/');
define('TPL_EXTENSION', 'tpl');
define('UPI2DB_PATH', IP_ROOT_PATH . 'includes/upi2db/');
// Mighty Gorgon - Constants Pages And Paths - END

// Mighty Gorgon - FAP - BEGIN
define('USERS_SUBFOLDERS_IMG', true); // Creates users subfolders for posted images
define('USERS_SUBFOLDERS_ALBUM', true); // Creates users subfolders for images in album
define('ALBUM_MOD_PATH', IP_ROOT_PATH . 'includes/album_mod/');
define('ALBUM_MOD_IMG_PATH', IP_ROOT_PATH . 'images/album/');
define('ALBUM_FILES_PATH', 'files/album/');
//define('ALBUM_FILES_PATH', 'album_mod/upload/');
include_once(ALBUM_MOD_PATH . 'album_constants.' . PHP_EXT);
// Mighty Gorgon - FAP - END

// Mighty Gorgon - ACTIVITY - BEGIN
/*
define('ACTIVITY_MOD', true);
define('ACTIVITY_MOD_PATH', 'amod/');
*/
// Mighty Gorgon - ACTIVITY - END

// Mighty Gorgon - CASH - BEGIN
//define('CASH_MOD', true);
// Mighty Gorgon - CASH - END

// Mighty Gorgon - Feedbacks - BEGIN
//define('MG_FEEDBACKS', true);
// Mighty Gorgon - Feedbacks - END

// Smilies Order
define('TOP_LIST', -1);
define('BOTTOM_LIST', 1);

// User Levels <- Do not change the values of USER or ADMIN
define('DELETED', -1);
define('ANONYMOUS', -1);
define('BOT', -2);

define('USER', 0);
define('ADMIN', 1);
define('MOD', 2);
define('GLOBAL_MOD', 3);
define('JUNIOR_ADMIN', 4);

// URL PARAMETERS
define('POST_CAT_URL', 'c');
define('POST_FORUM_URL', 'f');
define('POST_TOPIC_URL', 't');
define('POST_POST_URL', 'p');
define('POST_GROUPS_URL', 'g');
define('POST_USERS_URL', 'u');
define('STYLE_URL', 's');
define('LANG_URL', 'l');

// Error codes
define('GENERAL_MESSAGE', 200);
define('GENERAL_ERROR', 202);
define('CRITICAL_MESSAGE', 203);
define('CRITICAL_ERROR', 204);

// Auth settings - Levels
define('AUTH_NONE', -1);
define('AUTH_LIST_ALL', 0);
define('AUTH_ALL', 0);
define('AUTH_REG', 1);
define('AUTH_ACL', 2);
define('AUTH_MOD', 3);
define('AUTH_JADMIN', 4);
define('AUTH_ADMIN', 5);
// Self AUTH - BEGIN
define('AUTH_SELF', 9);
// Self AUTH - END

// Auth settings - Methods
define('AUTH_VIEW', 1);
define('AUTH_READ', 2);
define('AUTH_POST', 3);
define('AUTH_REPLY', 4);
define('AUTH_EDIT', 5);
define('AUTH_DELETE', 6);
define('AUTH_STICKY', 7);
define('AUTH_ANNOUNCE', 8);
define('AUTH_GLOBALANNOUNCE', 9);
define('AUTH_NEWS', 10);
define('AUTH_CAL', 11);
define('AUTH_VOTE', 12);
define('AUTH_POLLCREATE', 13);
define('AUTH_ATTACHMENTS', 14);
define('AUTH_DOWNLOAD', 15);
define('AUTH_BAN', 16);
define('AUTH_GREENCARD', 17);
define('AUTH_BLUECARD', 18);
define('AUTH_RATE', 19);

define('HIDDEN_CAT', 0); // NOTE: change this value to the forum id, of the forum, witch you would like to be hidden

// CMS Levels
define('CMS_GUEST', 0);
define('CMS_REG', 1);
define('CMS_VIP', 2);
define('CMS_PUBLISHER', 3);
define('CMS_REVIEWER', 4);
define('CMS_CONTENT_MANAGER', 5);

// CMS AUTH Levels
define('CMS_AUTH_NONE', -1);
define('CMS_AUTH_ALL', 0);
define('CMS_AUTH_REG', 1);
define('CMS_AUTH_VIP', 2);
define('CMS_AUTH_PUBLISHER', 3);
define('CMS_AUTH_REVIEWER', 4);
define('CMS_AUTH_CONTENT_MANAGER', 5);

// User related
define('USER_ACTIVATION_NONE', 0);
define('USER_ACTIVATION_SELF', 1);
define('USER_ACTIVATION_ADMIN', 2);

define('USER_AVATAR_NONE', 0);
define('USER_AVATAR_UPLOAD', 1);
define('USER_AVATAR_REMOTE', 2);
define('USER_AVATAR_GALLERY', 3);
define('USER_GRAVATAR', 4);
define('USER_AVATAR_GENERATOR', 5);

// Group settings
define('GROUP_OPEN', 0);
define('GROUP_CLOSED', 1);
define('GROUP_HIDDEN', 2);

// Forum state
define('FORUM_UNLOCKED', 0);
define('FORUM_LOCKED', 1);
define('FORUM_UNTHANKABLE', 0);
define('FORUM_THANKABLE', 1);

// Topic status
define('TOPIC_UNLOCKED', 0);
define('TOPIC_LOCKED', 1);
define('TOPIC_MOVED', 2);
define('TOPIC_WATCH_NOTIFIED', 1);
define('TOPIC_WATCH_UN_NOTIFIED', 0);

// Topic types
define('POST_NORMAL', 0);
define('POST_STICKY', 1);
define('POST_ANNOUNCE', 2);
define('POST_GLOBAL_ANNOUNCE', 3);
define('POST_NEWS', 4);

// SQL codes
define('BEGIN_TRANSACTION', 1);
define('END_TRANSACTION', 2);

// Private messaging
define('PRIVMSGS_READ_MAIL', 0);
define('PRIVMSGS_NEW_MAIL', 1);
define('PRIVMSGS_SENT_MAIL', 2);
define('PRIVMSGS_SAVED_IN_MAIL', 3);
define('PRIVMSGS_SAVED_OUT_MAIL', 4);
define('PRIVMSGS_UNREAD_MAIL', 5);

// Session parameters
define('SESSION_METHOD_COOKIE', 100);
define('SESSION_METHOD_GET', 101);

// Various constants
define('POST_BIRTHDAY', 9);
define('POST_CALENDAR', 10);

define('MANUAL', 0);
define('MANUAL_DST', 1);
define('SERVER_SWITCH', 2);

// News
define('DEFAULT_NUM_ITEMS', 10);
define('SORT_DATE_DEC', 0);
define('SORT_DATE_ASC', 1);
define('SORT_ALPH_ASC', 2);
define('SORT_ALPH_DEC', 3);

// Custom Profile Fields MOD - BEGIN
define('TEXT_FIELD', 0);
define('TEXTAREA', 1);
define('RADIO', 2);
define('CHECKBOX', 3);
define('REQUIRED', 1);
define('NOT_REQUIRED', 0);
define('TEXT_FIELD_MINLENGTH', 0);
define('TEXT_FIELD_MAXLENGTH', 255);
define('TEXTAREA_MINLENGTH', 0);
define('TEXTAREA_MAXLENGTH', 1024);
define('ALLOW_VIEW', 1);
define('DISALLOW_VIEW', 0);
define('VIEW_IN_PROFILE', 1);
define('NO_VIEW_IN_PROFILE', 0);
define('VIEW_IN_MEMBERLIST', 1);
define('NO_VIEW_IN_MEMBERLIST', 0);
define('VIEW_IN_TOPIC', 1);
define('NO_VIEW_IN_TOPIC', 0);
define('CONTACTS', 1);
define('ABOUT', 2);
define('AUTHOR', 1);
define('ABOVE_SIGNATURE', 2);
define('BELOW_SIGNATURE', 3);
// Custom Profile Fields MOD - END

// Ajax Shoutbox - BEGIN
define('AJAX_SHOUTBOX_NO_ERROR', 0);
define('AJAX_SHOUTBOX_ERROR', 1);
// Ajax Shoutbox - END

// Ajaxed - BEGIN
define('AJAX_OP_COMPLETED', 0);
define('AJAX_ERROR', 1);
define('AJAX_CRITICAL_ERROR', 2);
define('AJAX_POST_SUBJECT_EDITED', 3);
define('AJAX_POST_TEXT_EDITED', 4);
define('AJAX_POLL_RESULT', 5);
define('AJAX_WATCH_TOPIC', 6);
define('AJAX_LOCK_TOPIC', 7);
define('AJAX_MARK_TOPIC', 8);
define('AJAX_MARK_FORUM', 9);
define('AJAX_PM_USERNAME_FOUND', 10);
define('AJAX_PM_USERNAME_SELECT', 11);
define('AJAX_PM_USERNAME_ERROR', 12);
define('AJAX_PREVIEW', 13);
define('AJAX_DELETE_POST', 14);
define('AJAX_DELETE_TOPIC', 15);
define('AJAX_TOPIC_TYPE', 16);
define('AJAX_TOPIC_MOVE', 17);
// Ajaxed - END

// Do not insert anything but tables below!
// Table names
define('ACRONYMS_TABLE', $table_prefix . 'acronyms');
define('ADMINEDIT_TABLE', $table_prefix . 'adminedit');
define('ALBUM_TABLE', $table_prefix . 'album');
define('ALBUM_CAT_TABLE', $table_prefix . 'album_cat');
define('ALBUM_COMMENT_TABLE', $table_prefix . 'album_comment');
define('ALBUM_COMMENT_WATCH_TABLE', $table_prefix . 'album_comment_watch');
define('ALBUM_CONFIG_TABLE', $table_prefix . 'album_config');
define('ALBUM_RATE_TABLE', $table_prefix . 'album_rate');
define('AUTH_ACCESS_TABLE', $table_prefix . 'auth_access');
define('AUTOLINKS', $table_prefix . 'autolinks');
define('BANLIST_TABLE', $table_prefix . 'banlist');
define('BOOKMARK_TABLE', $table_prefix . 'bookmarks');
define('BOTS_TABLE', $table_prefix . 'bots');
define('CAPTCHA_CONFIG_TABLE', $table_prefix . 'captcha_config');
define('CATEGORIES_TABLE', $table_prefix . 'categories');
define('CONFIG_TABLE', $table_prefix . 'config');
define('CONFIRM_TABLE', $table_prefix . 'confirm');
define('DISALLOW_TABLE', $table_prefix . 'disallow');
define('DRAFTS_TABLE', $table_prefix . 'drafts');
define('FLAG_TABLE', $table_prefix . 'flags');
define('FORCE_READ_TABLE', $table_prefix . 'force_read');
define('FORCE_READ_USERS_TABLE', $table_prefix . 'force_read_users');
define('FORUMS_TABLE', $table_prefix . 'forums');
define('FORUMS_RULES_TABLE', $table_prefix . 'forums_rules');
define('FORUMS_WATCH_TABLE', $table_prefix . 'forums_watch');
define('GOOGLE_BOT_DETECTOR_TABLE', $table_prefix . 'google_bot_detector');
define('GROUPS_TABLE', $table_prefix . 'groups');
define('HACKS_LIST_TABLE', $table_prefix . 'hacks_list');
define('JR_ADMIN_TABLE', $table_prefix . 'jr_admin_users');
define('KB_ARTICLES_TABLE', $table_prefix . 'kb_articles');
define('LINK_CATEGORIES_TABLE', $table_prefix . 'link_categories');
define('LINK_CONFIG_TABLE', $table_prefix . 'link_config');
define('LINKS_TABLE', $table_prefix . 'links');
define('LIW_CACHE_TABLE', $table_prefix . 'liw_cache');
define('LOGINS_TABLE', $table_prefix . 'logins');
define('LOGS_TABLE', $table_prefix . 'logs');
define('MODULES_TABLE', $table_prefix . 'stats_modules');
define('NEWS_TABLE', $table_prefix . 'news');
define('NOTES_ADMIN_TABLE',$table_prefix . 'notes');
define('OPTIMIZE_DB_TABLE', $table_prefix . 'optimize_db');
define('POSTS_TABLE', $table_prefix . 'posts');
define('PRIVMSGS_TABLE', $table_prefix . 'privmsgs');
define('PROFILE_FIELDS_TABLE', $table_prefix . 'profile_fields');
define('PROFILE_VIEW_TABLE', $table_prefix . 'profile_view');
define('PRUNE_TABLE', $table_prefix . 'forum_prune');
define('RANKS_TABLE', $table_prefix . 'ranks');
define('RATINGS_TABLE', $table_prefix . 'rate_results');
define('REFERRERS_TABLE', $table_prefix . 'referrers');
define('SEARCH_MATCH_TABLE', $table_prefix . 'search_wordmatch');
define('SEARCH_TABLE', $table_prefix . 'search_results');
define('SEARCH_WORD_TABLE', $table_prefix . 'search_wordlist');
define('SESSIONS_KEYS_TABLE', $table_prefix . 'sessions_keys');
define('SESSIONS_TABLE', $table_prefix . 'sessions');
define('SHOUTBOX_TABLE', $table_prefix . 'shout');
define('SITE_HISTORY_TABLE', $table_prefix . 'site_history');
define('SMILIES_TABLE', $table_prefix . 'smilies');
define('STATS_CONFIG_TABLE', $table_prefix . 'stats_config');
define('SUDOKU_SESSIONS', $table_prefix . 'sudoku_sessions');
define('SUDOKU_SOLUTIONS', $table_prefix . 'sudoku_solutions');
define('SUDOKU_STARTS', $table_prefix . 'sudoku_starts');
define('SUDOKU_STATS', $table_prefix . 'sudoku_stats');
define('SUDOKU_USERS', $table_prefix . 'sudoku_users');
define('THANKS_TABLE', $table_prefix . 'thanks');
define('THEMES_TABLE', $table_prefix . 'themes');
define('TITLE_INFOS_TABLE', $table_prefix . 'title_infos');
define('TOPIC_VIEW_TABLE', $table_prefix . 'topic_view');
define('TOPICS_TABLE', $table_prefix . 'topics');
define('TOPICS_WATCH_TABLE', $table_prefix . 'topics_watch');
define('USER_GROUP_TABLE', $table_prefix . 'user_group');
define('USERS_TABLE', $table_prefix . 'users');
define('VOTE_DESC_TABLE', $table_prefix . 'vote_desc');
define('VOTE_RESULTS_TABLE', $table_prefix . 'vote_results');
define('VOTE_USERS_TABLE', $table_prefix . 'vote_voters');
define('WORDS_TABLE', $table_prefix . 'words');
define('XS_NEWS_CONFIG_TABLE', $table_prefix . 'xs_news_cfg');
define('XS_NEWS_TABLE', $table_prefix . 'xs_news');
define('XS_NEWS_XML_TABLE', $table_prefix . 'xs_news_xml');
define('ZEBRA_TABLE', $table_prefix . 'zebra');

// Ajax Shoutbox - BEGIN
define('AJAX_SHOUTBOX_TABLE', $table_prefix . 'ajax_shoutbox');
define('AJAX_SHOUTBOX_SESSIONS_TABLE', $table_prefix . 'ajax_shoutbox_sessions');
// Ajax Shoutbox - END

// Attachments - BEGIN
define('ATTACH_CONFIG_TABLE', $table_prefix . 'attachments_config');
define('EXTENSION_GROUPS_TABLE', $table_prefix . 'extension_groups');
define('EXTENSIONS_TABLE', $table_prefix . 'extensions');
define('FORBIDDEN_EXTENSIONS_TABLE', $table_prefix . 'forbidden_extensions');
define('ATTACHMENTS_DESC_TABLE', $table_prefix . 'attachments_desc');
define('ATTACHMENTS_STATS_TABLE', $table_prefix . 'attachments_stats');
define('ATTACHMENTS_TABLE', $table_prefix . 'attachments');
define('QUOTA_TABLE', $table_prefix . 'attach_quota');
define('QUOTA_LIMITS_TABLE', $table_prefix . 'quota_limits');
// Attachments - END

// CMS - BEGIN
define('CMS_BLOCK_POSITION_TABLE', $table_prefix . 'cms_block_position');
define('CMS_BLOCK_VARIABLE_TABLE', $table_prefix . 'cms_block_variable');
define('CMS_BLOCKS_TABLE', $table_prefix . 'cms_blocks');
define('CMS_CONFIG_TABLE', $table_prefix . 'cms_config');
define('CMS_LAYOUT_TABLE', $table_prefix . 'cms_layout');
define('CMS_LAYOUT_SPECIAL_TABLE', $table_prefix . 'cms_layout_special');
define('CMS_NAV_MENU_TABLE', $table_prefix . 'cms_nav_menu');
// CMS - END

// CMS ADV - START
define('CMS_ADV_BLOCKS_TABLE', $table_prefix . 'cms_adv_blocks');
define('CMS_ADV_BLOCK_POSITION_TABLE', $table_prefix . 'cms_adv_block_position');
define('CMS_ADV_BLOCK_VARIABLE_TABLE', $table_prefix . 'cms_adv_block_variable');
define('CMS_ADV_CONFIG_TABLE', $table_prefix . 'cms_adv_config');
define('CMS_ADV_PAGES_TABLE', $table_prefix . 'cms_adv_pages');
define('CMS_ADV_USERS_TABLE', $table_prefix . 'cms_adv_users');
// CMS ADV - END

// BEGIN CrackerTracker v5.x
define('CTRACKER_CONFIG', $table_prefix . 'ctracker_config');
define('CTRACKER_IPBLOCKER', $table_prefix . 'ctracker_ipblocker');
define('CTRACKER_LOGINHISTORY', $table_prefix . 'ctracker_loginhistory');
define('CTRACKER_FILECHK', $table_prefix . 'ctracker_filechk');
define('CTRACKER_FILESCANNER', $table_prefix . 'ctracker_filescanner');
define('CTRACKER_BACKUP', $table_prefix . 'ctracker_backup');
// END CrackerTracker v5.x

// Activity - BEGIN
define('iNA', $table_prefix . 'ina_data');
define('iNA_GAMES', $table_prefix . 'ina_games');
define('iNA_SCORES', $table_prefix . 'ina_scores');
// Activity - END

//<!-- BEGIN Unread Post Information to Database Mod -->
define('UPI2DB_ALWAYS_READ_TABLE', $table_prefix . 'upi2db_always_read');
define('UPI2DB_LAST_POSTS_TABLE', $table_prefix . 'upi2db_last_posts');
define('UPI2DB_UNREAD_POSTS_TABLE', $table_prefix . 'upi2db_unread_posts');
//<!-- END Unread Post Information to Database Mod -->

// DOWNLOADS - BEGIN
define('DOWNLOADS_TABLE', $table_prefix . 'downloads');
define('DL_AUTH_TABLE', $table_prefix . 'dl_auth');
define('DL_CAT_TABLE', $table_prefix . 'downloads_cat');
define('DL_CONFIG_TABLE', $table_prefix . 'dl_config');
define('DL_EXT_BLACKLIST', $table_prefix . 'dl_ext_blacklist');
define('DL_RATING_TABLE', $table_prefix . 'dl_ratings');
define('DL_STATS_TABLE', $table_prefix . 'dl_stats');
define('DL_COMMENTS_TABLE', $table_prefix . 'dl_comments');
define('DL_BANLIST_TABLE', $table_prefix . 'dl_banlist');
define('DL_FAVORITES_TABLE', $table_prefix . 'dl_favorites');
define('DL_NOTRAF_TABLE', $table_prefix . 'dl_notraf');
define('DL_HOTLINK_TABLE', $table_prefix . 'dl_hotlink');
define('DL_BUGS_TABLE', $table_prefix . 'dl_bug_tracker');
define('DL_BUG_HISTORY_TABLE', $table_prefix . 'dl_bug_history');
// DOWNLOADS - END

// PA FILES - BEGIN
define('PA_CATEGORY_TABLE', $table_prefix . 'pa_cat');
define('PA_COMMENTS_TABLE', $table_prefix . 'pa_comments');
define('PA_CUSTOM_TABLE', $table_prefix . 'pa_custom');
define('PA_CUSTOM_DATA_TABLE', $table_prefix . 'pa_customdata');
define('PA_DOWNLOAD_INFO_TABLE', $table_prefix . 'pa_download_info');
define('PA_FILES_TABLE', $table_prefix . 'pa_files');
define('PA_LICENSE_TABLE', $table_prefix . 'pa_license');
define('PA_CONFIG_TABLE', $table_prefix . 'pa_config');
define('PA_VOTES_TABLE', $table_prefix . 'pa_votes');
define('PA_AUTH_ACCESS_TABLE', $table_prefix . 'pa_auth');
define('PA_MIRRORS_TABLE', $table_prefix . 'pa_mirrors');
// PA FILES - END

?>