<?php
/***************************************************************************
 *                               extension.inc
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: extension.inc,v 1.5 2002/04/04 11:52:50 psotfx Exp $
 *
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

//@ini_set('memory_limit', '24M');

$starttime = 0;

// Page generation time
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

// Change this if your extension is not .php!
$phpEx = "php";

// Mighty Gorgon - Extra Debug - BEGIN
@define('DEBUG_EXTRA', true);
//@define('DEBUG_EXTRA_LOG', true);
// Mighty Gorgon - Extra Debug - END

// Mighty Gorgon - Extra Debug - BEGIN
if (defined('DEBUG_EXTRA'))
{
	$base_memory_usage = 0;
	if (function_exists('memory_get_usage'))
	{
		$base_memory_usage = memory_get_usage();
	}
}
// Mighty Gorgon - Extra Debug - END

?>