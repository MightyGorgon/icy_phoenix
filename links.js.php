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
* OOHOO < webdev@phpbb-tw.net >
* Stefan2k1 and ddonker from www.portedmods.com
* CRLin from http://mail.dhjh.tcc.edu.tw/~gzqbyr/
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// gzip_compression
$do_gzip_compress = false;
if($config['gzip_compress'])
{
	$phpver = phpversion();

	if($phpver >= "4.0.4pl1")
	{
		if(extension_loaded("zlib"))
		{
			ob_start("ob_gzhandler");
		}
	}
	elseif($phpver > "4.0")
	{
		if(strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		{
			if(extension_loaded("zlib"))
			{
				$do_gzip_compress = true;
				ob_start();
				ob_implicit_flush(0);

				header("Content-Encoding: gzip");
			}
		}
	}
}

header ("Cache-Control: no-store, no-cache, must-revalidate");
header ("Cache-Control: pre-check=0, post-check=0, max-age=0", false);
header ("Pragma: no-cache");
header ("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include_once(IP_ROOT_PATH . 'includes/functions_links.' . PHP_EXT);
$links_config = get_links_config(true);
$link_self_img = $links_config['site_logo'];
$site_logo_height = $links_config['height'];
$site_logo_width = $links_config['width'];
$display_interval = $links_config['display_interval'];
$display_logo_num = $links_config['display_logo_num'];

$template->set_filenames(array('body' => 'links_js_body.tpl'));

$sql = "SELECT link_id, link_title, link_logo_src
	FROM " . LINKS_TABLE . "
	WHERE link_active = 1
	ORDER BY link_hits DESC";
$result = $db->sql_query($sql);
$links_logo = '';
while($row = $db->sql_fetchrow($result))
{
	//if (empty($row['link_logo_src'])) $row['link_logo_src'] = 'images/links/no_logo88a.gif';
	if ($row['link_logo_src'])
	{
		//$links_logo .= ('"<a href=\"' . append_sid('links.' . PHP_EXT . '?action=go&link_id=' . $row['link_id']) . '\" target=\"_blank\"><img src=\"' . $row['link_logo_src'] . '\" alt=\"' . stripslashes($row['link_title']) . '\" width=\"' . $site_logo_width . '\" height=\"' . $site_logo_height . '\" border=\"0\" hspace=\"1\" \/><\/a>\",' . "\n");
		$links_logo .= ('\'<a href="' . append_sid('links.' . PHP_EXT . '?action=go&link_id=' . $row['link_id']) . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . stripslashes($row['link_title']) . '" width="' . $site_logo_width . '" height="' . $site_logo_height . '" border="0" hspace="1" \/><\/a>\',' . "\n");
	}
}
$db->sql_freeresult($result);

if($links_logo)
{
	$links_logo = substr($links_logo, 0, -2);

	$template->assign_vars(array(
		'S_CONTENT_ENCODING' => $lang['ENCODING'],
		'T_BODY_BGCOLOR' => '#' . $theme['td_color1'],

		'DISPLAY_INTERVAL' => $display_interval,
		'DISPLAY_LOGO_NUM' => $display_logo_num,
		'LINKS_LOGO' => $links_logo
		)
	);
}

$template->pparse('body');

$db->sql_close();

// Compress buffered output if required and send to browser
if($do_gzip_compress)
{
	//
	// Borrowed from php.net!
	//
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack("V", $gzip_crc);
	echo pack("V", $gzip_size);
}

exit;
?>