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

// Versioning
$fap_version = '1.5.0';
$phpbb_version = '.0.23';
$ip_version = '2.2.6.111';

// CHMOD
$chmod_777 = array();
$chmod_777[] = '../backup';
$chmod_777[] = '../cache';
$chmod_777[] = '../cache/cms';
$chmod_777[] = '../cache/forums';
$chmod_777[] = '../cache/posts';
$chmod_777[] = '../cache/sql';
$chmod_777[] = '../cache/topics';
$chmod_777[] = '../cache/uploads';
$chmod_777[] = '../cache/users';
$chmod_777[] = '../downloads';
$chmod_777[] = '../files';
$chmod_777[] = '../files/album';
$chmod_777[] = '../files/album/cache';
//$chmod_777[] = '../files/album/jupload';
$chmod_777[] = '../files/album/med_cache';
$chmod_777[] = '../files/album/users';
$chmod_777[] = '../files/album/wm_cache';
$chmod_777[] = '../files/images';
$chmod_777[] = '../files/screenshots';
$chmod_777[] = '../files/thumbs';
$chmod_777[] = '../files/thumbs/s';
$chmod_777[] = '../images/avatars';
$chmod_777[] = '../logs';

$chmod_666 = array();
$chmod_666[] = '../logs/logfile_attempt_counter.txt';
$chmod_666[] = '../logs/logfile_blocklist.txt';
$chmod_666[] = '../logs/logfile_debug_mode.txt';
$chmod_666[] = '../logs/logfile_malformed_logins.txt';
$chmod_666[] = '../logs/logfile_spammer.txt';
$chmod_666[] = '../logs/logfile_worms.txt';
//$chmod_666[] = '../language/lang_english/lang_extend.php';

?>