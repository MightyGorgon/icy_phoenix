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

define('ICYPHOENIX_VERSION', '2.2.6.111');

// FOUNDER ID, this is the ID of the main admin of the site, only this user can access special features of the site and this ID is also used to send Welcome and Birthday PM
define('FOUNDER_ID', '2');
// ALLOW ONLY FOUNDER ACP, by setting this to true only the FOUNDER will be able to access ACP
define('ONLY_FOUNDER_ACP', false);
// Insert here the IDs of the main admins separated by commas, they will be able to access special features in ACP
// Allowed features: Action Logs, Private Messages, DB Tools, Templates Edit (XS Style)
// If the constant is deleted then every admin is able to access the Full ACP
define('MAIN_ADMINS_ID', '2');

define('EMAILER_DISABLED', false);

// CRON - BEGIN
// Cron refresh time: seconds needed to a cron job to be considered DIED
define('CRON_REFRESH', 900);
// Add all files separated by commas... e.g.: 'cron_file_1.php,cron_file_2.php,cron_file_3.php'
define('CRON_FILES', '');
// CRON - END

//@define('SQL_DEBUG_LOG', true); // This will output all SQL to a file in cache... do not use unless strictly needed!!!
define('CACHE_TREE', true); // Caching Forum Tree ON/OFF => TRUE/FALSE (do not disable this unless you need to debug or you have really good reasons!)
define('CACHE_TREE_FILE', 'data_full_tree.' . PHP_EXT); // Caching Forum Tree ON/OFF => TRUE/FALSE (do not disable this unless you need to debug or you have really good reasons!)
define('CACHE_SQL', true); // Caching SQL Queries ON/OFF => TRUE/FALSE
define('CACHE_CFG', true); // Caching Config Tables ON/OFF => TRUE/FALSE
define('CACHE_CH_SQL', true); // Caching CH SQL ON/OFF => TRUE/FALSE
define('CACHE_SQL_EXPIRY', 31536000); // (31536000 = 365 * 24 * 60 * 60) The default number of seconds before SQL cache expires
define('CACHE_TOPICS_META', false); // Caching Topics Meta KW And Descriptions ON/OFF => TRUE/FALSE (if you have thousands of topics, better switch it off)
define('CACHE_BAN_INFO', false); // Caching Ban informations for each user ON/OFF => TRUE/FALSE (if you have thousands of users, better switch it off)
define('CACHE_COLORIZE', false); // Caching Users Colors ON/OFF => TRUE/FALSE (if you have thousands of users, better switch it off)
define('CACHE_FILES_PER_STEP', 500); // The number of files that will be deleted per step when emptying cache folder to avoid timeout
define('META_TAGS_ATTACH', false); // Attach standard META TAGS (defined in lang_main_settings or DB) to the ones specific for that page? ON/OFF => TRUE/FALSE

// Script Time Limit: if your site is large you may want to set a time limit to force scripts shut down and avoid server overload
define('TIME_LIMIT', 30); // Script Time Limit in seconds

// Session Refresh Seconds
define('SESSION_REFRESH', 120); // Session Refresh Time (Seconds)
define('ONLINE_REFRESH', 300); // Online Refresh Time (Seconds)

define('LAST_LOGIN_DAYS_NEW_POSTS_RESET', 30); // Number of days after which new posts are not count (to avoid high CPU SQL request)
define('LAST_LOGIN_NEW_POSTS_LIMIT', 2000); // Maximum number of posts for new posts counter (to avoid high CPU SQL request)
define('UPI2DB_MAX_UNREAD_POSTS', 2500); // Maximum amount of stored unread messages if no limits is set... we don't want our dB to explode!!!
define('UPI2DB_RESYNC_TIME', 30); // Seconds needed to refresh UPI2DB data...

// Mighty Gorgon - Constants Pages And Paths - BEGIN
// Pages
define('CMS_PAGE_CMS', 'cms.' . PHP_EXT);
define('CMS_PAGE_LOGIN', 'login_ip.' . PHP_EXT);
define('CMS_PAGE_ERRORS', 'errors.' . PHP_EXT);
define('CMS_PAGE_HOME', 'index.' . PHP_EXT);
define('CMS_PAGE_FORUM', 'forum.' . PHP_EXT);
define('CMS_PAGE_VIEWFORUM', 'viewforum.' . PHP_EXT);
define('CMS_PAGE_VIEWFORUMLIST', 'viewforumlist.' . PHP_EXT);
define('CMS_PAGE_VIEWTOPIC', 'viewtopic.' . PHP_EXT);
define('CMS_PAGE_VIEWONLINE', 'viewonline.' . PHP_EXT);
define('CMS_PAGE_SEARCH', 'search.' . PHP_EXT);
define('CMS_PAGE_PROFILE', 'profile.' . PHP_EXT);
define('CMS_PAGE_PROFILE_MAIN', 'profile_main.' . PHP_EXT);
define('CMS_PAGE_UCP', 'ucp.' . PHP_EXT);
define('CMS_PAGE_POSTING', 'posting.' . PHP_EXT);
define('CMS_PAGE_DRAFTS', 'drafts.' . PHP_EXT);
define('CMS_PAGE_MEMBERLIST', 'memberlist.' . PHP_EXT);
define('CMS_PAGE_GROUP_CP', 'groupcp.' . PHP_EXT);
define('CMS_PAGE_PRIVMSG', 'privmsg.' . PHP_EXT);
define('CMS_PAGE_PRIVACY_POLICY', 'privacy_policy.' . PHP_EXT);
define('CMS_PAGE_COOKIE_POLICY', 'cookie_policy.' . PHP_EXT);
define('CMS_PAGE_FAQ', 'faq.' . PHP_EXT);
define('CMS_PAGE_RULES', 'rules.' . PHP_EXT);
define('CMS_PAGE_DLOAD', 'dload.' . PHP_EXT);
define('CMS_PAGE_DOWNLOADS', 'downloads.' . PHP_EXT);
define('CMS_PAGE_DL_DEFAULT', CMS_PAGE_DLOAD);
//define('CMS_PAGE_DL_DEFAULT', CMS_PAGE_DOWNLOADS);
define('CMS_PAGE_IMAGES', 'images_list.' . PHP_EXT);
define('CMS_PAGE_IMAGE_THUMBNAIL', 'image_thumbnail.' . PHP_EXT);
define('CMS_PAGE_IMAGE_THUMBNAIL_S', 'image_thumbnail_s.' . PHP_EXT);
define('CMS_PAGE_LINKS', 'links.' . PHP_EXT);
define('CMS_PAGE_STATISTICS', 'statistics.' . PHP_EXT);
define('CMS_PAGE_CALENDAR', 'calendar.' . PHP_EXT);
define('CMS_PAGE_RECENT', 'recent.' . PHP_EXT);
define('CMS_PAGE_REFERERS', 'referers.' . PHP_EXT);
define('CMS_PAGE_AJAX_CHAT', 'ajax_chat.' . PHP_EXT);
define('CMS_PAGE_AJAX_SHOUTBOX', 'ajax_shoutbox.' . PHP_EXT);
define('CMS_PAGE_SHOUTBOX', 'shoutbox_max.' . PHP_EXT);
define('CMS_PAGE_KB', 'kb.' . PHP_EXT);
define('CMS_PAGE_CONTACT_US', 'contact_us.' . PHP_EXT);
define('CMS_PAGE_CREDITS', 'credits.' . PHP_EXT);
define('CMS_PAGE_TAGS', 'tags.' . PHP_EXT);
// You can customize this page to be able to redirect users wherever you like after logout or login with redirect var not set
define('CMS_LOGIN_REDIRECT_PAGE', CMS_PAGE_HOME);

// Paths
define('ADM', 'adm');
define('COMMON_TPL', '../common/');
define('ADM_TPL', COMMON_TPL . 'acp/');
//define('ADM_TPL', '../../' . ADM . '/templates/');
define('CMS_TPL', COMMON_TPL . 'cms/');
define('CMS_TPL_ABS_PATH', IP_ROOT_PATH . 'templates/common/cms/');
define('BLOCKS_DIR_NAME', 'blocks/');
define('BLOCKS_DIR', IP_ROOT_PATH . BLOCKS_DIR_NAME);
define('BLOCKS_PREFIX', '');
define('STATS_TPL', 'stats_modules/');
define('STYLES_PATH', 'cms/styles/');
define('ATTACH_MOD_PATH', 'includes/attach_mod/');
define('DOWNLOADS_PATH', 'downloads/');
define('PA_FILE_DB_PATH', 'includes/pafiledb/');
define('FILES_ICONS_DIR', 'images/files/');
define('FONTS_DIR', IP_ROOT_PATH . 'images/fonts/');
define('POSTED_IMAGES_PATH', IP_ROOT_PATH . 'files/images/');
define('POSTED_IMAGES_THUMBS_PATH', IP_ROOT_PATH . 'files/thumbs/');
define('POSTED_IMAGES_THUMBS_S_PATH', POSTED_IMAGES_THUMBS_PATH . 's/');
define('MAIN_CACHE_FOLDER', IP_ROOT_PATH . 'cache/');
define('CMS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'cms/');
define('FORUMS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'forums/');
define('POSTS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'posts/');
define('SQL_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'sql/');
define('TOPICS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'topics/');
define('UPLOADS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'uploads/');
define('USERS_CACHE_FOLDER', MAIN_CACHE_FOLDER . 'users/');
define('SETTINGS_PATH', 'settings/');
define('PLUGINS_PATH', 'plugins/');
define('BACKUP_PATH', 'backup/');
define('TPL_EXTENSION', 'tpl');
define('UPI2DB_PATH', IP_ROOT_PATH . 'includes/upi2db/');
define('USERS_SUBFOLDERS_IMG', true); // Creates users subfolders for uploaded images
// Mighty Gorgon - Constants Pages And Paths - END

// CHMOD permissions
if (!defined('CHMOD_ALL')) @define('CHMOD_ALL', 7);
if (!defined('CHMOD_READ')) @define('CHMOD_READ', 4);
if (!defined('CHMOD_WRITE')) @define('CHMOD_WRITE', 2);
if (!defined('CHMOD_EXECUTE')) @define('CHMOD_EXECUTE', 1);

// Referer validation
define('REFERER_VALIDATE_NONE', 0);
define('REFERER_VALIDATE_HOST', 1);
define('REFERER_VALIDATE_PATH', 2);

// User Levels <- Do not change the values of USER or ADMIN
define('DELETED', -1);
define('ANONYMOUS', -1);
define('BOT', -2);

define('USER_NORMAL', 0);
define('USER_INACTIVE', 1);
define('USER_IGNORE', 2);
define('USER_FOUNDER', 3);

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

// ACL
define('ACL_NEVER', 0);
define('ACL_YES', 1);
define('ACL_NO', -1);

// CMS Auth
define('AUTH_CMS_NONE', -1);
define('AUTH_CMS_ALL', 0);
define('AUTH_CMS_GUESTS_ONLY', 1);
define('AUTH_CMS_REG', 2);
define('AUTH_CMS_MOD', 3);
define('AUTH_CMS_ADMIN', 4);
define('AUTH_CMS_FOUNDER', 5);
define('AUTH_CMS_OWNER', 6);
define('AUTH_CMS_ALL_NO_BOTS', 8);

// Auth settings - Levels
define('AUTH_NONE', -1);
define('AUTH_LIST_ALL', 0);
define('AUTH_ALL', 0);
define('AUTH_REG', 1);
define('AUTH_ACL', 2);
define('AUTH_MOD', 3);
define('AUTH_JADMIN', 4);
define('AUTH_ADMIN', 5);
define('AUTH_MAIN_ADMIN', 6);
define('AUTH_FOUNDER', 7);
define('AUTH_OWNER', 8);
// Self AUTH - BEGIN
define('AUTH_SELF', 9);
// Self AUTH - END
define('AUTH_GUEST_ONLY', 10);
define('AUTH_GUEST_ONLY_STRICT', 11);

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

// CMS Styles
define('CMS_USERS', 1);

// User related
define('USER_ACTIVATION_NONE', 0);
define('USER_ACTIVATION_SELF', 1);
define('USER_ACTIVATION_ADMIN', 2);
define('USER_ACTIVATION_DISABLE', 3);

define('USER_AVATAR_NONE', 0);
define('USER_AVATAR_UPLOAD', 1);
define('USER_AVATAR_REMOTE', 2);
define('USER_AVATAR_GALLERY', 3);
define('USER_GRAVATAR', 4);
define('USER_AVATAR_GENERATOR', 5);

// Optional text flags
define('OPTION_FLAG_BBCODE', 1);
define('OPTION_FLAG_SMILIES', 2);
define('OPTION_FLAG_LINKS', 4);
define('OPTION_FLAG_HTML', 8);
define('OPTION_FLAG_ACRO_AUTO', 16);

// Login error codes
define('LOGIN_CONTINUE', 1);
define('LOGIN_BREAK', 2);
define('LOGIN_SUCCESS', 3);
define('LOGIN_SUCCESS_CREATE_PROFILE', 20);
define('LOGIN_ERROR_USERNAME', 10);
define('LOGIN_ERROR_PASSWORD', 11);
define('LOGIN_ERROR_ACTIVE', 12);
define('LOGIN_ERROR_ATTEMPTS', 13);
define('LOGIN_ERROR_EXTERNAL_AUTH', 14);
define('LOGIN_ERROR_PASSWORD_CONVERT', 15);

// Group settings
define('GROUP_OPEN', 0);
define('GROUP_CLOSED', 1);
define('GROUP_HIDDEN', 2);
define('GROUP_SPECIAL', 3);
define('GROUP_FREE', 4);

// Forum types
define('FORUM_CAT', 0);
define('FORUM_POST', 1);
define('FORUM_LINK', 2);

// Forum state
define('FORUM_UNLOCKED', 0);
define('FORUM_LOCKED', 1);

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

// Post status
define('ITEM_UNAPPROVED', 0);
define('ITEM_APPROVED', 1);
define('ITEM_DELETED', 2);

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

// Notify methods
define('NOTIFY_EMAIL', 0);
define('NOTIFY_IM', 1);
define('NOTIFY_BOTH', 2);

// Email Priority Settings
define('MAIL_LOW_PRIORITY', 4);
define('MAIL_NORMAL_PRIORITY', 3);
define('MAIL_HIGH_PRIORITY', 2);

// Log types
define('LOG_ADMIN', 0);
define('LOG_MOD', 1);
define('LOG_CRITICAL', 2);
define('LOG_USERS', 3);

// Captcha code length
define('CAPTCHA_MIN_CHARS', 4);
define('CAPTCHA_MAX_CHARS', 7);

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

// Smilies Order
define('TOP_LIST', -1);
define('BOTTOM_LIST', 1);

// Custom Profile Fields - BEGIN
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
// Custom Profile Fields - END

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
define('AJAX_POST_LIKE', 18);
define('AJAX_POST_UNLIKE', 19);
// Ajaxed - END

// Event Registration - BEGIN
define('REG_OPTION1', 1);
define('REG_OPTION2', 2);
define('REG_OPTION3', 3);
define('REG_UNREGISTER', 4);
// Event Registration - END

// Some of the most used colors names
define('RGB_COLORS_LIST', 'aqua,black,blue,brown,cadetblue,chocolate,crimson,cyan,darkblue,darkgreen,darkgrey,darkorchid,darkred,deepskyblue,fuchsia,gold,gray,green,indigo,lightgrey,lime,maroon,navy,olive,orange,peachpuff,purple,red,seagreen,silver,teal,violet,white,yellow');

define('VOTE_CONVERTED', 127);

// Do not insert anything but tables below!
// Table names
define('ACRONYMS_TABLE', $table_prefix . 'acronyms');
define('ADMINEDIT_TABLE', $table_prefix . 'adminedit');
define('ADS_TABLE', $table_prefix . 'ads');
define('AUTH_ACCESS_TABLE', $table_prefix . 'auth_access');
define('AUTOLINKS', $table_prefix . 'autolinks');
define('BANLIST_TABLE', $table_prefix . 'banlist');
define('BBCODES_TABLE', $table_prefix . 'bbcodes');
define('BOOKMARK_TABLE', $table_prefix . 'bookmarks');
define('BOTS_TABLE', $table_prefix . 'bots');
define('CONFIG_TABLE', $table_prefix . 'config');
define('CONFIRM_TABLE', $table_prefix . 'confirm');
define('DIGEST_SUBSCRIPTIONS_TABLE', $table_prefix . 'digest_subscriptions');
define('DIGEST_SUBSCRIBED_FORUMS_TABLE', $table_prefix . 'digest_subscribed_forums');
define('DISALLOW_TABLE', $table_prefix . 'disallow');
define('DRAFTS_TABLE', $table_prefix . 'drafts');
define('FLAG_TABLE', $table_prefix . 'flags');
define('FORCE_READ_USERS_TABLE', $table_prefix . 'force_read_users');
define('FORUMS_TABLE', $table_prefix . 'forums');
define('FORUMS_WATCH_TABLE', $table_prefix . 'forums_watch');
define('GROUPS_TABLE', $table_prefix . 'groups');
define('HACKS_LIST_TABLE', $table_prefix . 'hacks_list');
define('JR_ADMIN_TABLE', $table_prefix . 'jr_admin_users');
define('KB_ARTICLES_TABLE', $table_prefix . 'kb_articles');
define('IMAGES_TABLE', $table_prefix . 'images');
define('LIW_CACHE_TABLE', $table_prefix . 'liw_cache');
define('LOGINS_TABLE', $table_prefix . 'logins');
define('LOG_TABLE', $table_prefix . 'log');
define('LOGS_TABLE', $table_prefix . 'logs');
define('MODERATOR_CACHE_TABLE', $table_prefix . 'moderator_cache');
define('MODULES_TABLE', $table_prefix . 'modules');
define('NEWS_TABLE', $table_prefix . 'news');
define('NOTES_ADMIN_TABLE',$table_prefix . 'notes');
define('PLUGINS_TABLE', $table_prefix . 'plugins');
define('PLUGINS_CONFIG_TABLE', $table_prefix . 'plugins_config');
define('POLL_OPTIONS_TABLE', $table_prefix . 'poll_options');
define('POLL_VOTES_TABLE', $table_prefix . 'poll_votes');
define('POSTS_TABLE', $table_prefix . 'posts');
define('POSTS_LIKES_TABLE', $table_prefix . 'posts_likes');
define('PRIVMSGS_TABLE', $table_prefix . 'privmsgs');
define('PROFILE_FIELDS_TABLE', $table_prefix . 'profile_fields');
define('PROFILE_VIEW_TABLE', $table_prefix . 'profile_view');
define('PRUNE_TABLE', $table_prefix . 'forum_prune');
define('RANKS_TABLE', $table_prefix . 'ranks');
define('RATINGS_TABLE', $table_prefix . 'rate_results');
define('REFERERS_TABLE', $table_prefix . 'referers');
define('SEARCH_MATCH_TABLE', $table_prefix . 'search_wordmatch');
define('SEARCH_TABLE', $table_prefix . 'search_results');
define('SEARCH_WORD_TABLE', $table_prefix . 'search_wordlist');
define('SESSIONS_KEYS_TABLE', $table_prefix . 'sessions_keys');
define('SESSIONS_TABLE', $table_prefix . 'sessions');
define('SHOUTBOX_TABLE', $table_prefix . 'shout');
define('SITE_HISTORY_TABLE', $table_prefix . 'site_history');
define('SMILIES_TABLE', $table_prefix . 'smilies');
define('STATS_CONFIG_TABLE', $table_prefix . 'stats_config');
define('STATS_MODULES_TABLE', $table_prefix . 'stats_modules');
define('THEMES_TABLE', $table_prefix . 'themes');
define('TICKETS_CAT_TABLE', $table_prefix . 'tickets_cat');
define('TOPIC_VIEW_TABLE', $table_prefix . 'topic_view');
define('TOPICS_TABLE', $table_prefix . 'topics');
define('TOPICS_LABELS_TABLE', $table_prefix . 'topics_labels');
define('TOPICS_TAGS_LIST_TABLE', $table_prefix . 'topics_tags_list');
define('TOPICS_TAGS_MATCH_TABLE', $table_prefix . 'topics_tags_match');
define('TOPICS_WATCH_TABLE', $table_prefix . 'topics_watch');
define('USER_GROUP_TABLE', $table_prefix . 'user_group');
define('USERS_TABLE', $table_prefix . 'users');
define('WORDS_TABLE', $table_prefix . 'words');
define('XS_NEWS_TABLE', $table_prefix . 'xs_news');
define('XS_NEWS_XML_TABLE', $table_prefix . 'xs_news_xml');
define('ZEBRA_TABLE', $table_prefix . 'zebra');

// Ajax Shoutbox - BEGIN
define('AJAX_SHOUTBOX_TABLE', $table_prefix . 'ajax_shoutbox');
define('AJAX_SHOUTBOX_SESSIONS_TABLE', $table_prefix . 'ajax_shoutbox_sessions');
// Ajax Shoutbox - END

// Attachments - BEGIN
define('EXTENSION_GROUPS_TABLE', $table_prefix . 'extension_groups');
define('EXTENSIONS_TABLE', $table_prefix . 'extensions');
define('FORBIDDEN_EXTENSIONS_TABLE', $table_prefix . 'forbidden_extensions');
define('ATTACHMENTS_DESC_TABLE', $table_prefix . 'attachments_desc');
define('ATTACHMENTS_STATS_TABLE', $table_prefix . 'attachments_stats');
define('ATTACHMENTS_TABLE', $table_prefix . 'attachments');
define('QUOTA_TABLE', $table_prefix . 'attach_quota');
define('QUOTA_LIMITS_TABLE', $table_prefix . 'quota_limits');
// Attachments - END

// ACL AUTH - BEGIN
define('ACL_GROUPS_TABLE', $table_prefix . 'acl_groups');
define('ACL_OPTIONS_TABLE', $table_prefix . 'acl_options');
define('ACL_ROLES_DATA_TABLE', $table_prefix . 'acl_roles_data');
define('ACL_ROLES_TABLE', $table_prefix . 'acl_roles');
define('ACL_USERS_TABLE', $table_prefix . 'acl_users');
// ACL AUTH - END

// CMS - BEGIN
define('CMS_BLOCK_POSITION_TABLE', $table_prefix . 'cms_block_position');
define('CMS_BLOCK_SETTINGS_TABLE', $table_prefix . 'cms_block_settings');
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
define('CTRACKER_VERSION', '5.0.6');
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

// UPI2DB - BEGIN
define('UPI2DB_ALWAYS_READ_TABLE', $table_prefix . 'upi2db_always_read');
define('UPI2DB_LAST_POSTS_TABLE', $table_prefix . 'upi2db_last_posts');
define('UPI2DB_UNREAD_POSTS_TABLE', $table_prefix . 'upi2db_unread_posts');
// UPI2DB - END

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

// LINKS - BEGIN
define('LINKS_TABLE', $table_prefix . 'links');
define('LINK_CATEGORIES_TABLE', $table_prefix . 'link_categories');
define('LINK_CONFIG_TABLE', $table_prefix . 'link_config');
// LINKS - END

// SUDOKU - BEGIN
define('SUDOKU_SESSIONS', $table_prefix . 'sudoku_sessions');
define('SUDOKU_SOLUTIONS', $table_prefix . 'sudoku_solutions');
define('SUDOKU_STARTS', $table_prefix . 'sudoku_starts');
define('SUDOKU_STATS', $table_prefix . 'sudoku_stats');
define('SUDOKU_USERS', $table_prefix . 'sudoku_users');
// SUDOKU - END

// Event Registration - BEGIN
define('REGISTRATION_TABLE', $table_prefix . 'registration');
define('REGISTRATION_DESC_TABLE', $table_prefix . 'registration_desc');
// Event Registration - END

?>