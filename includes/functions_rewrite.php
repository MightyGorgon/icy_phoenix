<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Make URL Friendly rewritten by MG
// Some ideas and code borrowed by seofilter => http://seofilter.org
function make_url_friendly($url)
{
	global $lang;

	// Remove Re: in case of replies
	$url = strtolower(str_replace('Re: ', '', strip_tags($url)));

	// Remove all HTML tags
	$url = strip_tags($url);
	// Convert &
	$url = str_replace(array('&amp;', '&nbsp;'), array('&', ' '), $url);
	// Decode all HTML entities
	$url = html_entity_decode($url, ENT_COMPAT, $lang['ENCODING']);

	// Some common chars replacements
	// are we sure we may replace "&"???
	$find = array(' ', '&', '@', '©', '®', '€', '$', '£');
	$repl = array('-', 'and', 'at', 'copyright', 'rights', 'euro', 'dollar', 'pound');
	$url = str_replace($find, $repl, $url);

	// Attempt to convert all HTML numeric entities.
	if (preg_match('@\&\#\d+;@s', $url))
	{
		$url = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $url);
	}

	// Convert back all HTML entities into their aliases
	$url = htmlentities($url, ENT_COMPAT, $lang['ENCODING']);

	// Replace some known HTML entities
	$find = array(
		'&#268;', '&#269;', // c
		'&#356;', '&#357;', // t
		'&#270;', '&#271;', // d
		'&#317;', '&#318;', // L, l
		'&#327;', '&#328;', // N, n
		'&#381;', '&#382;', 'Ž', 'ž', // z
		'œ', '&#338;', '&#339;', // OE, oe
		'&#198;', '&#230;', // AE, ae
		'&#223;', '&#946;', // ß
		'š', 'Š', // 'š','Š'
		'&#273;', '&#272;', // ?', '?', // 'dj','dj'
		'`', '‘', '’',
	);

	$repl = array(
		'c', 'c',
		't', 't',
		'd', 'd',
		'l', 'l',
		'n', 'n',
		'z', 'z', 'z', 'z',
		'oe', 'oe', 'oe',
		'ae', 'ae',
		'sz', 'sz',
		's', 's',
		'dj', 'dj',
		'-', '-', '-',
	);

	$url = str_replace($find, $repl, $url);

	// Convert localized special chars
	$url = preg_replace('/&([a-z][ez]?)(?:acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);/','$1', $url);

	// Convert all remaining special chars
	$url = preg_replace('/&([a-z]+);/', '$1', $url);

	// If still some unrecognized HTML entities are there... kill them!!!
	$url = preg_replace('@\&\#\d+;@s', '', $url);

	// Replace all illegal chars with '-'
	$url = preg_replace('![^a-z0-9\-]!s', '-', $url);

	// Convert every white space char with "-"
	$url = preg_replace('!\s+!s', '-', $url);
	// Replace multiple "-"
	$url = preg_replace('!-+!s', '-', $url);
	// Replace multiple "_"
	$url = preg_replace('!_+!s', '_', $url);
	// Remove leading / trailing "-"/"_"...
	$url = preg_replace('!^[-_]|[-_]$!s', '', $url);

	// Old ending cleaning code
	/*
	$find = array(
		'/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/',
	);

	$repl = array(
		'', '-', '',
	);

	$url = preg_replace($find, $repl, $url);

	$url = str_replace('--', '-', $url);
	*/

	return $url;
}

// FUNCTIONS
function rewrite_urls($content)
{
	function if_query($amp)
	{
		if($amp != '')
		{
			return '?';
		}
	}

	$url_in = array(
		// Forums, topics and posts
		//'/(?<!\/)' . PORTAL_MG . '\?topic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . FORUM_MG . '\?' . POST_CAT_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . VIEWFORUM_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',

		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		// Profile
		'/(?<!\/)(.\/){0,1}' . PROFILE_MG . '\?mode=viewprofile((&amp;)|(&)){0,1}' . POST_USERS_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		// Album
		'/(?<!\/)album_cat.php\?cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		//'/(?<!\/)album_showpage.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		// Replacing pic_id with IMG ALT content
		'/(?<!\/)album_showpage.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',
		'/(?<!\/)album_personal.php\?user_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		/*
		'/(?<!\/)album_pic.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_picm.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_thumbnail.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		*/
		/* */
		// Replacing pic_id with IMG ALT content
		'/(?<!\/)album_pic.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/e',
		'/(?<!\/)album_picm.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/e',
		'/(?<!\/)album_thumbnail.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/e',
		/* */

		// Files
		'/(?<!\/)dload.php\?action=category&cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)dload.php\?action=category&cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)dload.php\?action=file&file_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		// KB
		'/(?<!\/)kb.php\?mode=cat&amp;cat=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=article&amp;k=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=stats&amp;stats=mostpopular((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=stats&amp;stats=toprated((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=stats&amp;stats=latest((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
	);

	$url_out = array(
		// Forums, topics and posts
		//"make_url_friendly('\\6') . '-na\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		"make_url_friendly('\\6') . '-vc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		"make_url_friendly('\\6') . '-vf\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		"make_url_friendly('\\14') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\11') . stripslashes('\\13') . 'title=\"' . stripslashes('\\14\\15\\16\\17') . '</a>'",
		"make_url_friendly('\\15') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\11') . stripslashes('\\13\\14') . 'alt=\"' . stripslashes('\\15') . '\"' . stripslashes('\\16') . '</a>'",
		"make_url_friendly('\\14') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\10') . stripslashes('\\13\\14') . '</a>'",

		"make_url_friendly('\\10') . '-vf\\1-v9\\5.html' . if_query('\\6') . stripslashes('\\9') . 'title=\"' . stripslashes('\\10\\11\\12\\13') . '</a>'",
		"make_url_friendly('\\10') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9') . 'title=\"' . stripslashes('\\10\\11\\12\\13') . '</a>'",

		"make_url_friendly('\\11') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'alt=\"' . stripslashes('\\11') . '\"' . stripslashes('\\12') . '</a>'",
		"make_url_friendly('\\11') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'alt=\"' . stripslashes('\\11') . '\"' . stripslashes('\\12') . '</a>'",

		"make_url_friendly('\\10') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",
		"make_url_friendly('\\10') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",

		"make_url_friendly('\\6') . '-vt\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-vp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		// Profile
		"make_url_friendly('\\10') . '-profile-u\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",

		// Album
		"make_url_friendly('\\6') . '-ac\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		//"make_url_friendly('\\6') . '-asp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		// Replacing pic_id with IMG ALT content
		"make_url_friendly('\\7') . '-asp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . 'alt=\"' . stripslashes('\\7') . '\"' . stripslashes('\\8') . '</a>'",
		"make_url_friendly('\\6') . '-aper\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		/*
		"make_url_friendly('\\6') . '-apic\\1.jpg' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-apm\\1.jpg' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-at\\1.jpg' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		*/
		/* */
		// Replacing pic_id with IMG ALT content
		"make_url_friendly('\\6') . '-apic\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
		"make_url_friendly('\\6') . '-apm\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
		"make_url_friendly('\\6') . '-at\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
		/* */

		// Files
		"make_url_friendly('\\6') . '-dc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-dc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-df\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		// KB
		"make_url_friendly('\\6') . '-kbc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-kba\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\5') . '-kbsmp.html' . if_query('\\1') . stripslashes('\\4\\5') . '</a>'",
		"make_url_friendly('\\5') . '-kbstr.html' . if_query('\\1') . stripslashes('\\4\\5') . '</a>'",
		"make_url_friendly('\\5') . '-kbsl.html' . if_query('\\1') . stripslashes('\\4\\5') . '</a>'",
	);

	$content = preg_replace($url_in, $url_out, $content);

	return $content;
}

?>