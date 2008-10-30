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

?>