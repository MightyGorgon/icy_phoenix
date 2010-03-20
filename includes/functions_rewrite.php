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
	$url = strtolower(str_replace('Re: ', '', $url));
	$url = ip_clean_string($url, $lang['ENCODING']);

	$url = ($url == '') ? 'urlrw' : $url;

	return $url;
}

function if_query($amp)
{
	if($amp != '')
	{
		return '?';
	}
}

/*
// FUNCTIONS
function rewrite_urls($content)
{

	$url_in = array(
		// Forums, topics and posts
		//'/(?<!\/)' . CMS_PAGE_HOME . '\?topic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . CMS_PAGE_FORUM . '\?' . POST_CAT_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . CMS_PAGE_VIEWFORUM . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',
		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',
		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',

		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		// Profile
		'/(?<!\/)(.\/){0,1}' . CMS_PAGE_PROFILE . '\?mode=viewprofile((&amp;)|(&)){0,1}' . POST_USERS_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)title=\"(.*?)\"(.*?)<\/a>/e',
		'/(?<!\/)(.\/){0,1}' . CMS_PAGE_PROFILE . '\?mode=viewprofile((&amp;)|(&)){0,1}' . POST_USERS_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		// Album
		'/(?<!\/)album_cat.php\?cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		//'/(?<!\/)album_showpage.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		// Replacing pic_id with IMG ALT content
		'/(?<!\/)album_showpage.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/e',
		'/(?<!\/)album_personal.php\?user_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_pic.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/e',
		'/(?<!\/)album_picm.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/e',
		'/(?<!\/)album_thumbnail.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/e',

		// Files
		'/(?<!\/)dload.php\?action=category&cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)dload.php\?action=file&file_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',

		// KB
		'/(?<!\/)kb.php\?mode=cat&amp;cat=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=article&amp;k=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
	);

	$url_out = array(
		// Forums, topics and posts
		//"make_url_friendly('\\6') . '-na\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		"make_url_friendly('\\6') . '-vc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		"make_url_friendly('\\6') . '-vf\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		"make_url_friendly('\\14') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\11') . stripslashes('\\13') . 'title=\"' . stripslashes('\\14\\15\\16\\17') . '</a>'",
		"make_url_friendly('\\15') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\11') . stripslashes('\\13\\14') . 'alt=\"' . stripslashes('\\15') . '\"' . stripslashes('\\16') . '</a>'",
		"make_url_friendly('\\14') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\10') . stripslashes('\\13\\14') . '</a>'",

		"make_url_friendly('\\10') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9') . 'title=\"' . stripslashes('\\10\\11\\12\\13') . '</a>'",
		"make_url_friendly('\\10') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9') . 'title=\"' . stripslashes('\\10\\11\\12\\13') . '</a>'",

		"make_url_friendly('\\11') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'alt=\"' . stripslashes('\\11') . '\"' . stripslashes('\\12') . '</a>'",
		"make_url_friendly('\\11') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'alt=\"' . stripslashes('\\11') . '\"' . stripslashes('\\12') . '</a>'",

		"make_url_friendly('\\10') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",
		"make_url_friendly('\\10') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",

		"make_url_friendly('\\6') . '-vt\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-vp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		// Profile
		"make_url_friendly('\\11') . '-profile-u\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'title=\"' . stripslashes('\\11\"\\12') . '</a>'",
		"make_url_friendly('\\10') . '-profile-u\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",

		// Album
		"make_url_friendly('\\6') . '-ac\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		// Replacing pic_id with IMG ALT content
		"make_url_friendly('\\7') . '-asp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . 'alt=\"' . stripslashes('\\7') . '\"' . stripslashes('\\8') . '</a>'",
		"make_url_friendly('\\6') . '-aper\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-apic\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
		"make_url_friendly('\\6') . '-apm\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
		"make_url_friendly('\\6') . '-at\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",

		// Files
		"make_url_friendly('\\6') . '-dc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-df\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",

		// KB
		"make_url_friendly('\\6') . '-kbc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-kba\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	);

	$content = preg_replace($url_in, $url_out, $content);

	return $content;
}
*/

// second version
function rewrite_urls($content)
{
	$regex_ary = array(
		// Forums, topics and posts
		'f1' => '/(?<!\/)' . CMS_PAGE_FORUM . '\?' . POST_CAT_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',

		'vf1' => '/(?<!\/)' . CMS_PAGE_VIEWFORUM . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',

		'vt1' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/',
		'vt2' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/',
		'vt3' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',

		'vt4' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/',
		'vt5' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}(.*?)title=\"(.*?)(["]+>){1}([^>]+>)(.*?)<\/a>/',

		'vt6' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/',
		'vt7' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/',

		'vt8' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',
		'vt9' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&))' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',

		'vt10' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',
		'vt11' => '/(?<!\/)' . CMS_PAGE_VIEWTOPIC . '\?' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',

		// Profile
		//'pr1' => '/(?<!\/)(.\/){0,1}' . CMS_PAGE_PROFILE . '\?mode=viewprofile((&amp;)|(&)){0,1}' . POST_USERS_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)title=\"(.*?)\"(.*?)<\/a>/',
		'pr2' => '/(?<!\/)(.\/){0,1}' . CMS_PAGE_PROFILE . '\?mode=viewprofile((&amp;)|(&)){0,1}' . POST_USERS_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',

		// Album
		'a1' => '/(?<!\/)album_cat.php\?cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',
		'a2' => '/(?<!\/)album_showpage.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)alt=\"(.*?)\"(.*?)<\/a>/',
		'a3' => '/(?<!\/)album_personal.php\?user_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',
		// Replacing pic_id with IMG ALT content
		'ai1' => '/(?<!\/)album_pic.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/',
		'ai2' => '/(?<!\/)album_picm.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/',
		'ai3' => '/(?<!\/)album_thumbnail.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+alt=\")(.*?)\"/',

		// Files
		'd1' => '/(?<!\/)dload.php\?action=category&cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',
		'd2' => '/(?<!\/)dload.php\?action=file&file_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',

		// KB
		'kb1' => '/(?<!\/)kb.php\?mode=cat&amp;cat=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',
		'kb2' => '/(?<!\/)kb.php\?mode=article&amp;k=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/',
	);

	foreach ($regex_ary as $k => $regex)
	{
		//var_dump(preg_match($regex, $content));
		$callback = "url_replace_callback_$k";
		$content = preg_replace_callback($regex, $callback, $content);
	}

	return $content;
}

// Forums, topics and posts
function url_replace_callback_f1($matches)
{
	//"make_url_friendly('\\6') . '-vc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-vc' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

function url_replace_callback_vf1($matches)
{
	//"make_url_friendly('\\6') . '-vf\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-vf' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

function url_replace_callback_vt1($matches)
{
	//"make_url_friendly('\\14') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\11') . stripslashes('\\13') . 'title=\"' . stripslashes('\\14\\15\\16\\17') . '</a>'",
	return make_url_friendly($matches[14]) . '-vf' . $matches[1] . '-vt' . $matches[5] . '-vp' . $matches[9] . '.html' . if_query($matches[11]) . stripslashes($matches[13]) . 'title="' . stripslashes($matches[14] . $matches[15] . $matches[16] . $matches[17]) . '</a>';
}

function url_replace_callback_vt2($matches)
{
	//"make_url_friendly('\\15') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\11') . stripslashes('\\13\\14') . 'alt=\"' . stripslashes('\\15') . '\"' . stripslashes('\\16') . '</a>'",
	return make_url_friendly($matches[15]) . '-vf' . $matches[1] . '-vt' . $matches[5] . '-vp' . $matches[9] . '.html' . if_query($matches[11]) . stripslashes($matches[13] . $matches[14]) . 'alt="' . stripslashes($matches[15]) . '"' . stripslashes($matches[16]) . '</a>';
}

function url_replace_callback_vt3($matches)
{
	//"make_url_friendly('\\14') . '-vf\\1-vt\\5-vp\\9.html' . if_query('\\10') . stripslashes('\\13\\14') . '</a>'",
	return make_url_friendly($matches[14]) . '-vf' . $matches[1] . '-vt' . $matches[5] . '-vp' . $matches[9] . '.html' . if_query($matches[10]) . stripslashes($matches[13] . $matches[14]) . '</a>';
}

function url_replace_callback_vt4($matches)
{
	//"make_url_friendly('\\10') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9') . 'title=\"' . stripslashes('\\10\\11\\12\\13') . '</a>'",
	return make_url_friendly($matches[10]) . '-vf' . $matches[1] . '-vp' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9]) . 'title="' . stripslashes($matches[10] . $matches[11] . $matches[12] . $matches[13]) . '</a>';
}

function url_replace_callback_vt5($matches)
{
	//"make_url_friendly('\\10') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9') . 'title=\"' . stripslashes('\\10\\11\\12\\13') . '</a>'",
	return make_url_friendly($matches[10]) . '-vf' . $matches[1] . '-vt' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9]) . 'title="' . stripslashes($matches[10] . $matches[11] . $matches[12] . $matches[13]) . '</a>';
}

function url_replace_callback_vt6($matches)
{
	//"make_url_friendly('\\11') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'alt=\"' . stripslashes('\\11') . '\"' . stripslashes('\\12') . '</a>'",
	return make_url_friendly($matches[11]) . '-vf' . $matches[1] . '-vp' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9] . $matches[10]) . 'alt="' . stripslashes($matches[11]) . '"' . stripslashes($matches[12]) . '</a>';
}

function url_replace_callback_vt7($matches)
{
	//"make_url_friendly('\\11') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'alt=\"' . stripslashes('\\11') . '\"' . stripslashes('\\12') . '</a>'",
	return make_url_friendly($matches[11]) . '-vf' . $matches[1] . '-vt' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9] . $matches[10]) . 'alt="' . stripslashes($matches[11]) . '"' . stripslashes($matches[12]) . '</a>';
}

function url_replace_callback_vt8($matches)
{
	//"make_url_friendly('\\10') . '-vf\\1-vp\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",
	return make_url_friendly($matches[10]) . '-vf' . $matches[1] . '-vp' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9] . $matches[10]) . '</a>';
}

function url_replace_callback_vt9($matches)
{
	//"make_url_friendly('\\10') . '-vf\\1-vt\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",
	return make_url_friendly($matches[10]) . '-vf' . $matches[1] . '-vt' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9] . $matches[10]) . '</a>';
}

function url_replace_callback_vt10($matches)
{
	//"make_url_friendly('\\6') . '-vt\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-vt' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

function url_replace_callback_vt11($matches)
{
	//"make_url_friendly('\\6') . '-vp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-vp' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

// Profile
function url_replace_callback_pr1($matches)
{
	//"make_url_friendly('\\11') . '-profile-u\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . 'title=\"' . stripslashes('\\11\"\\12') . '</a>'",
	return make_url_friendly($matches[11]) . '-profile-u' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9] . $matches[10]) . 'title="' . stripslashes($matches[11] . $matches[12]) . '</a>';
}

function url_replace_callback_pr2($matches)
{
	//"make_url_friendly('\\10') . '-profile-u\\5.html' . if_query('\\6') . stripslashes('\\9\\10') . '</a>'",
	return make_url_friendly($matches[10]) . '-profile-u' . $matches[5] . '.html' . if_query($matches[6]) . stripslashes($matches[9] . $matches[10]) . '</a>';
}

// Album
function url_replace_callback_a1($matches)
{
	//"make_url_friendly('\\6') . '-ac\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-ac' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

function url_replace_callback_a2($matches)
{
	//"make_url_friendly('\\7') . '-asp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . 'alt=\"' . stripslashes('\\7') . '\"' . stripslashes('\\8') . '</a>'",
	return make_url_friendly($matches[7]) . '-asp' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . 'alt="' . stripslashes($matches[7]) . '"' . stripslashes($matches[8]) . '</a>';
}

function url_replace_callback_a3($matches)
{
	//"make_url_friendly('\\6') . '-aper\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-aper' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

function url_replace_callback_ai1($matches)
{
	//"make_url_friendly('\\6') . '-apic\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
	return make_url_friendly($matches[6]) . '-apic' . $matches[1] . '.jpg' . '" alt="' . stripslashes($matches[6]) . '"';
}

function url_replace_callback_ai2($matches)
{
	//"make_url_friendly('\\6') . '-apm\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
	return make_url_friendly($matches[6]) . '-apm' . $matches[1] . '.jpg' . '" alt="' . stripslashes($matches[6]) . '"';
}

function url_replace_callback_ai3($matches)
{
	//"make_url_friendly('\\6') . '-at\\1.jpg' . '\" alt=\"' . stripslashes('\\6') . '\"'",
	return make_url_friendly($matches[6]) . '-at' . $matches[1] . '.jpg' . '" alt="' . stripslashes($matches[6]) . '"';
}

// Files
function url_replace_callback_d1($matches)
{
	//"make_url_friendly('\\6') . '-dc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-dc' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

function url_replace_callback_d2($matches)
{
	//"make_url_friendly('\\6') . '-df\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-df' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

// KB
function url_replace_callback_kb1($matches)
{
	//"make_url_friendly('\\6') . '-kbc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-kbc' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}

function url_replace_callback_kb2($matches)
{
	//"make_url_friendly('\\6') . '-kba\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
	return make_url_friendly($matches[6]) . '-kba' . $matches[1] . '.html' . if_query($matches[2]) . stripslashes($matches[5] . $matches[6]) . '</a>';
}
/*
*/
?>