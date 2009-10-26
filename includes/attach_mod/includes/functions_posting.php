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
* (c) 2002 Meik Sievertsen (Acyd Burn)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
	exit;
}

/**
* FTP File to Location
*/
function ftp_file($source_file, $dest_file, $mimetype, $disable_error_mode = false)
{
	global $attach_config, $lang, $error, $error_msg;

	$conn_id = attach_init_ftp();

	// Binary or Ascii ?
	$mode = FTP_BINARY;
	if (preg_match("/text/i", $mimetype) || preg_match("/html/i", $mimetype))
	{
		$mode = FTP_ASCII;
	}

	$res = @ftp_put($conn_id, $dest_file, $source_file, $mode);

	if (!$res && !$disable_error_mode)
	{
		$error = true;
		if (!empty($error_msg))
		{
			$error_msg .= '<br />';
		}
		$error_msg = sprintf($lang['Ftp_error_upload'], $attach_config['ftp_path']) . '<br />';
		@ftp_quit($conn_id);
		return false;
	}

	if (!$res)
	{
		return false;
	}

	@ftp_site($conn_id, 'CHMOD 0644 ' . $dest_file);
	@ftp_quit($conn_id);
	return true;
}

?>