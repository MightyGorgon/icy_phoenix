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
* Christian Knerr (cback) - (www.cback.de)
*
*/

/**
* This file is the engine for the CrackerTracker IP, UserAgent and
* Remote Host blocking System. You can enable or disable this feature
* in ACP and you can add or remove Blocked Hostnames, IP Adresses, etc.
*
* This scanner also works well with the Joker-Sign <i>"*"</i>. So you have the
* possibility to block for example IPs like <i>"123.456.*.*"</i> or Hostnames
* like <i>"BadBrowser v*"</i>
*
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 16.07.2006 - 02:07:51
* @copyright (c) 2006 www.cback.de
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt!');
}


/*
* We check if the user has activated the IP and Hostname Blocker.
* If so we use our ct_database class to load the Blocklist from the
* Database in an array and check if someone who was blocked is in the list.
*/
if ($config['ctracker_ipblock_enabled'])
{
	if (!class_exists('ct_database'))
	{
		include(IP_ROOT_PATH . 'includes/ctracker/classes/class_ct_database.' . PHP_EXT);
		$ctracker_config = new ct_database();
	}

	// Fetch Blocklist from Database
	$ctracker_config->unset_blocklist_verbose();
	$ctracker_config->load_blocklist();

	// Fetch IP UserAgent and Remote Host
	$ct_client_ip   = $client_ip;
	$ct_user_agent  = $_SERVER['HTTP_USER_AGENT'];
	$ct_remote_host = $_SERVER['REMOTE_HOST'];

	/*
	* Now we check if IP Adress, UserAgent or RemoteHost of the User
	* is blocked by CrackerTracker. You can use the Joker "*" to match
	* all expressions between 2 Words (adjustable in ACP)
	*/
	for($i = 0; $i < $ctracker_config->blocklist_count; $i++)
	{
		/*
		* For easyer handling we write the current Blocklist Value
		* into a new var and do a preg_quote. Because we WANT to allow
		* Joker sing "*" we str_replace the "\*" in a correct preg_match
		* layout.
		*/
		$current_value = preg_quote($ctracker_config->blocklist[$i]);
		$current_value = str_replace('\*', '.*?', $current_value);

		/*
		* Now lets check if we have matches in the blocklist
		*/
		if (preg_match('/^' . $current_value . '$/is', $ct_client_ip) || preg_match('/^' . $current_value . '$/is', $ct_user_agent) || preg_match('/^' . $current_value . '$/is', $ct_remote_host))
		{
			// We have a match, so write the log
			include_once(IP_ROOT_PATH . 'includes/ctracker/classes/class_log_manager.' . PHP_EXT);

			// write data into logfile
			$logfile = new log_manager();
			$logfile->write_general_logfile($config['ctracker_ipblock_logsize'], 3);
			unset($logfile);

			// generate HTML output
			$htmloutput = '<html>
			<head><title>CBACK CrackerTracker :: Security Alert</title></head>
				<body>
					<br />
					<div align="center">
						<table style="border:2px solid #000000" border="0" width="600" cellpadding="10" cellspacing="0">
							<tr>
								<td align="left" bgcolor="#000000"><font face="Tahoma, Arial, Helvetica" size="4" color="#ffffff"><b>SECURITY ALERT&nbsp;&raquo;&nbsp;&raquo;&nbsp;&raquo;&nbsp;&raquo;</b></font></td>
							</tr>
							<tr>
								<td bgcolor="#fff2cf" align="left">
								<font face="Tahoma, Arial, Helvetica" size="2" color="#000000"><b>CBACK CrackerTracker</b> blocked you because the Admin blocked your IP range, useragent or hostname from this board.<br /><br />
								If you think you\'re banned without a reason please tell the Admin from this error message and ask him what happened that he has the possibility to unblock you.
								</font>
								</td>
							</tr>
						</table>
					</div>
				</body>
			</html>';

			// stop the script
			die($htmloutput);
		}
	} // for

	/*
	* Now we don't need the Array with the Blocklist Information anymore so we drop it
	*/
	unset($ctracker_config->blocklist);

} // if

// Tell the self test that this file was included correctly
// Moved to common.php
//define('protection_unit_three', true);

?>