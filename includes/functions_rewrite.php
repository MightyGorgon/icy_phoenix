<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// Make URL Friendly rewritten by MG
function make_url_friendly($url)
{

	$url = strtolower($url);

	$find = array(
		' ', '<b>', '</b>',
		'&quot;', '&amp;', '&lt;', '&gt;', '\r\n', '\n', '/', '\\', '+', '<', '>', 'vfr',
		'á', 'à', 'â', 'ã', 'å', 'Á', 'À', 'Â', 'Ã', 'Å', 'ä', 'Ä',
		'é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë',
		'í', 'ì', 'î', 'ï', 'Í', 'Ì', 'Î', 'Ï',
		'ó', 'ò', 'ô', 'õ', 'ø', 'Ó', 'Ò', 'Ô', 'Õ', 'Ø', 'ö', 'Ö',
		'ú', 'ù', 'û', 'Ú', 'Ù', 'Û', 'ü', 'Ü',
		'ÿ',
		'ç', 'Ç',
		'ñ', 'Ñ',
		'ß',
		'`', '‘', '’',
		' ', '&',
	);

	$repl = array(
		'-', '', '',
		'-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
		'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A', 'ae', 'ae',
		'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E',
		'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I',
		'o', 'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O', 'O', 'oe', 'oe',
		'u', 'u', 'u', 'U', 'U', 'U', 'ue', 'ue',
		'y',
		'c', 'C',
		'n', 'N',
		'ss',
		'-', '-', '-',
		'-', '-',
	);

	$url = str_replace($find, $repl, $url);

	$find = array(
		'/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/',
	);

	$repl = array(
		'', '-', '',
	);

	$url = preg_replace($find, $repl, $url);

	$url = str_replace('--', '-', $url);

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
		//'/(?<!\/)' . PORTAL_MG . '\?topic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . FORUM_MG . '\?' . POST_CAT_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWFORUM_MG . '\?' . POST_FORUM_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_TOPIC_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)' . VIEWTOPIC_MG . '\?' . POST_POST_URL . '=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_cat.php\?cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_showpage.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_personal.php\?user_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		/* */
		'/(?<!\/)album_pic.php\?pic_id=([0-9]+)((&amp;)|(&))full=true{0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_pic.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_picm.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)album_thumbnail.php\?pic_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		/* */
		'/(?<!\/)dload.php\?action=category&cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)dload.php\?action=category&cat_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)dload.php\?action=file&file_id=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=cat&amp;cat=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=article&amp;k=([0-9]+)((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=stats&amp;stats=mostpopular((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=stats&amp;stats=toprated((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
		'/(?<!\/)kb.php\?mode=stats&amp;stats=latest((&amp;)|(&)){0,1}([^>]+>)(.*?)<\/a>/e',
	);

	$url_out = array(
		//"make_url_friendly('\\6') . '-na\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-vc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-vf\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-vt\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-vp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-ac\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-asp\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-aper\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		/* */
		"make_url_friendly('\\6') . '-apicf\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-apic\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-apm\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-at\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		/* */
		"make_url_friendly('\\6') . '-dc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-dc\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
		"make_url_friendly('\\6') . '-df\\1.html' . if_query('\\2') . stripslashes('\\5\\6') . '</a>'",
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