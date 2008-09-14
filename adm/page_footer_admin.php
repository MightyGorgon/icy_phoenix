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

global $do_gzip_compress;

// Show the overall footer.
$template->set_filenames(array('page_footer' =>  ADM_TPL . 'page_footer.tpl'));

$template->assign_vars(array(
	'PHPBB_VERSION' => (($userdata['user_level'] == ADMIN) && ($userdata['user_id'] != ANONYMOUS)) ? '2' . $board_config['version'] : '',
	'IP_VERSION' => $board_config['ip_version'],
	'TRANSLATION_INFO' => (isset($lang['TRANSLATION_INFO'])) ? $lang['TRANSLATION_INFO'] : ((isset($lang['TRANSLATION'])) ? $lang['TRANSLATION'] : '')
	)
);

$template->pparse('page_footer');

// Close our DB connection.
$db->clear_cache();
$db->sql_close();

// Compress buffered output if required and send to browser
if( $do_gzip_compress )
{
	// Borrowed from php.net!
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}

exit;

?>