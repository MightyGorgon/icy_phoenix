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
* Mohd - (mohdalbasri@hotmail.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// define('PAFILEDB_DEBUG', 0); // Pafiledb Mod Debugging off
define('PAFILEDB_DEBUG', 1); // Pafiledb Mod Debugging on
define('PAFILEDB_QUERY_DEBUG', 1);

define('PA_ROOT_CAT', 0);
define('PA_CAT_ALLOW_FILE', 1);

define('PA_AUTH_LIST_ALL', 0);
define('PA_AUTH_ALL', 0);

define('FILE_PINNED', 1);

define('PA_AUTH_VIEW', 1);
define('PA_AUTH_READ', 2);
define('PA_AUTH_VIEW_FILE', 3);
define('PA_AUTH_UPLOAD', 4);
define('PA_AUTH_DOWNLOAD', 5);
define('PA_AUTH_RATE', 6);
define('PA_AUTH_EMAIL', 7);
define('PA_AUTH_COMMENT_VIEW', 8);
define('PA_AUTH_COMMENT_POST', 9);
define('PA_AUTH_COMMENT_EDIT', 10);
define('PA_AUTH_COMMENT_DELETE', 11);

//Field Types
define('INPUT', 0);
define('TEXTAREA', 1);
define('RADIO', 2);
define('SELECT', 3);
define('SELECT_MULTIPLE', 4);
define('CHECKBOX', 5);

define('ICONS_DIR', PA_FILE_DB_PATH . 'images/icons/');

//tables
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

?>