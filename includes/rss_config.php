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
* Egor Naklonyaeff
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

define('CACHE_TIME', 600);					// Cache time for RSS feed in sec, 5 min by default
																		// Set to zero to disable cache (Not recomendated).
define('AUTOSTYLED', false);				// Use XSLT transformation in MSIE by default
define('CACHE_TO_FILE', true);			// Use cache dir for caching defaul page. You MUST set 777 to that dir first
define('UPDATE_VIEW_COUNT', false);	// Update count of viewed topics for non-Anonymous user
																		// If set to false disable Who viewed a topic update too
define('AUTO_WVT_MOD', true);				// Who viewed a topic [2.0.6/EM]
define('LV_MOD_INSTALLED', true);		// Last visit [2.0.10/EM]  http://mods.db9.dk/
define('DEFAULT_ITEMS', 25);				// How many items parsed in feed by default
define('MAX_ITEMS', 100);						// Max number of parsed items
define('AUTOLOGIN', true);					// Try to autologin using cookie if user not set 'login' or 'uid' key
define('SEE_MODIFYED', true);				// Include in feed modified from last visit record in addition to new records
define('MAX_WEEKS_AGO', 2);					// Limit RSS feed by time. Most important in large forums (!)
																		// Set to zero if you don't want use this futures
$show_time = true;									// Show total script time in RSS comments.
$unauthed = '0';										// Comma separated list of unauthed forums. For ex.: '0,1,11';

$cache_root = 'cache/';							// Cache dir
$cache_filename = 'rss_feed.xml';

?>