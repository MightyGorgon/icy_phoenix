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
* <b>CrackerTracker File: ct_security.php</b><br /><br />
*
* This file is responsible for the worm protection Unit of the CrackerTracker
* Security System. The File includes a definition Array wich is used to
* heuristically scan URL requests for attacks.
*
* We included some virtual Troopers for you. ;-)
*
* @author Christian Knerr (cback) and Tekin Birdüzen (cYbercOsmOnauT)
* @package ctracker
* @version 5.0.6
* @since 15.07.2006 - 21:36:24
* @copyright (c) 2006 www.cback.de
*
*/

if(!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt!');
}

$disable_ct_warning = false;
if(defined('MG_CTRACK_FLAG'))
{
	$disable_ct_warning = true;
}

/*
* Change the following to define('CT_DEBUG_MODE', true);
* if you want to activate the debug mode of CrackerTracker
* but don't forget to deactivate it again as soon as possible
*/
define('CT_DEBUG_MODE', false);
//define('CT_DEBUG_MODE', true);

/*
* DO NOT CHANGE ANYTHING BELOW!
* CHANGING STUFF BELOW CAN DRAMATICALLY DECREASE
* THE SECURITY OF YOUR BOARD SO PLEASE
* DO NOT CHANGE ANYTHING BELOW THIS LINE!
*/

/*

/*
* The first thing we do now is checking the integrity of the IP_ROOT_PATH
* because CrackerTracker will need it later. With this step we prevent that
* scripts without the IP_ROOT_PATH or without a validated IP_ROOT_PATH
* can be executed.
*/
if (!defined('IP_ROOT_PATH'))
{
	/*
	* Create a HTML error Message output
	*/
	$htmloutput = <<<EOM
<html>
<head><title>CBACK CrackerTracker :: Security Alert</title></head>
<body>
<br/>
<div align="center">
<table style="border:2px solid #000000" border="0" width="600" cellpadding="10" cellspacing="0">
<tr>
	<td align="left" bgcolor="#000000"><font face="Tahoma, Arial, Helvetica" size="4" color="#ffffff"><b>SECURITY ALERT &raquo; &raquo; &raquo; &raquo;</b></font></td>
</tr>
<tr>
	<td bgcolor="#fff4bf" align="left">
		<font face="Tahoma, Arial, Helvetica" size="2" color="#000000">
			CBACK CrackerTracker stopped your script because the engine detected
			that the script you want to execute has not initialized the constant
			<b>IP_ROOT_PATH</b> correctly.
			<br /><br />
			This could be a potential security risk for this board.
			<br /><br />
			If you are not the admin of this Board please contact him and tell him from
			this warning message and what you have done that he has the possibility to
			fix that problem.
		</font>
		</td>
</tr>
</table>
</div>
</body>
</html>
EOM;

	// Lets stop the script
	die($htmloutput);
}


/*
* Now we define an array where all definition data is saved in.
* After that we check URL committals for potential worm acitivities
*/

$ct_rules = array(
	'http_', '_server', 'delete%20', 'delete ', 'delete-', 'delete(', '(delete', 'drop%20',
	'drop ', 'create%20', 'update-', 'update(', '(update', 'insert-', 'insert(', '(insert',
	'create ', 'create(', 'create-', '(create', 'update%20', 'update ', 'insert%20', 'insert ',
	'select%20', 'select ', 'bulk%20', 'bulk ', 'union%20', 'union ', 'select-', 'select(',
	'(select', 'union-', '(union', 'union(',
	'or%20', 'or ', 'and%20', 'and ', 'exec', '@@', '%22', '"', 'openquery',
	'openrowset', 'msdasql', 'sqloledb', 'sysobjects', 'syscolums',
	'syslogins', 'sysxlogins', 'char%20', 'char ', 'into%20', 'into ',
	'load%20', 'load ', 'msys', 'alert%20', 'alert ', 'eval%20', 'eval ',
	'onkeyup', 'x5cx', 'fromcharcode', 'javascript:', 'javascript.', 'vbscript:',
	'vbscript.', 'http-equiv', '->', 'expression%20', 'expression ',
	'url%20', 'url ', 'innerhtml', 'document.', 'dynsrc', 'jsessionid',
	'style%20', 'style ', 'phpsessid', '<applet', '<div', '<emded', '<iframe', '<img',
	'<meta', '<object', '<script', '<textarea', 'onabort', 'onblur',
	'onchange', 'onclick', 'ondblclick', 'ondragdrop', 'onerror',
	'onfocus', 'onkeydown', 'onkeypress', 'onload', 'onmouse',
	'onmove', 'onreset', 'onresize', 'onselect', 'onsubmit',
	'onunload', 'onreadystatechange', 'xmlhttp', 'uname%20', 'uname ',
	'%2C', 'union+', 'select+', 'delete+', 'create+', 'bulk+', 'or+', 'and+',
	'into+', 'kill+', '+echr', '+chr', 'cmd+', '+1', 'user_password',
	'id%20', 'id ', 'ls%20', 'ls ', 'cat%20', 'cat ', 'rm%20', 'rm ',
	'kill%20', 'kill ', 'mail%20', 'mail ', 'wget%20', 'wget ', 'wget(',
	'pwd%20', 'pwd ', 'objectclass', 'objectcategory', '<!-%20', '<!- ',
	'total%20', 'total ', 'http%20request', 'http request', 'phpb8b4f2a0',
	'phpinfo', 'php:', 'globals', '%2527', '%27', '\'', 'chr(',
	'chr=', 'chr%20', 'chr ', '%20chr', ' chr', 'cmd=', 'cmd%20', 'cmd',
	'%20cmd', ' cmd', 'rush=', '%20rush', ' rush', 'rush%20', 'rush ',
	'union%20', 'union ', '%20union', ' union', 'union(', 'union=',
	'%20echr', ' echr', 'esystem', 'cp%20', 'cp ', 'cp(', '%20cp', ' cp',
	'mdir%20', 'mdir ', '%20mdir', ' mdir', 'mdir(', 'mcd%20', 'mcd ',
	'mrd%20', 'mrd ', 'rm%20', 'rm ', '%20mcd', ' mcd', '%20mrd', ' mrd',
	'%20rm', ' rm', 'mcd(', 'mrd(', 'rm(', 'mcd=', 'mrd=', 'mv%20', 'mv ',
	'rmdir%20', 'rmdir ', 'mv(', 'rmdir(', 'chmod(', 'chmod%20', 'chmod ',
	'cc%20', 'cc ', '%20chmod', ' chmod', 'chmod(', 'chmod=', 'chown%20', 'chown ',
	'chgrp%20', 'chgrp ', 'chown(', 'chgrp(', 'locate%20', 'locate ', 'grep%20', 'grep ',
	'locate(', 'grep(', 'diff%20', 'diff ', 'kill%20', 'kill ', 'kill(', 'killall',
	'passwd%20', 'passwd ', '%20passwd', ' passwd', 'passwd(', 'telnet%20', 'telnet ',
	'vi(', 'vi%20', 'vi ', 'nigga(', '%20nigga', ' nigga', 'nigga%20', 'nigga ',
	'fopen', 'fwrite', '%20like', ' like', 'like%20', 'like ', '$_',
	'$get', '.system', 'http_php', '%20getenv', ' getenv', 'getenv%20', 'getenv ',
	'new_password', '/password', 'etc/', '/groups', '/gshadow',
	'http_user_agent', 'http_host', 'bin/', 'wget%20', 'wget ', 'uname%5c',
	'uname', 'usr', '/chgrp', '=chown', 'usr/bin', 'g%5c',
	'g\\', 'bin/python', 'bin/tclsh', 'bin/nasm', 'perl%20', 'perl ', '.pl',
	'traceroute%20', 'traceroute ', 'tracert%20', 'tracert ', 'ping%20', 'ping ',
	'/usr/x11r6/bin/xterm', 'lsof%20', 'lsof ', '/mail', '.conf', 'motd%20', 'motd ',
	'http/1.', '.inc.php', 'config.php', 'cgi-', '.eml', 'file%5c://',
	'file\:', 'file://', 'window.open', 'img src', 'img%20src', 'img src',
	'.jsp', 'ftp.', 'xp_enumdsn', 'xp_availablemedia',
	'xp_filelist', 'nc.exe', '.htpasswd', 'servlet', '/etc/passwd', '/etc/shadow',
	'wwwacl', '~root', '~ftp', '.js', '.jsp', '.history',
	'bash_history', '~nobody', 'server-info', 'server-status',
	'%20reboot', ' reboot', '%20halt', ' halt', '%20powerdown', ' powerdown',
	'/home/ftp', '=reboot', 'www/', 'init%20', 'init ','=halt', '=powerdown',
	'ereg(', 'secure_site', 'chunked', 'org.apache', '/servlet/con',
	'/robot', 'mod_gzip_status', '.inc', '.system', 'getenv',
	'http_', '_php', 'php_', 'phpinfo()', '<?php', '?>', '%3C%3Fphp',
	'%3F>', 'sql=', '_global', 'global_', 'global[', '_server',
	'server_', 'server[', '/modules', 'modules/', 'phpadmin',
	'root_path', '_globals', 'globals_', 'globals[', 'iso-8859-1',
	'?hl=', '%3fhl=', '.exe', '.sh', '%00', rawurldecode('%00'), '_env', '/*', '\\*'
);

// Some fields in $_POST don't get checked to prevent wrong detection
$unchecked_post_fields = array(
	'username', 'password', 'subject', 'message',
	'poll_title', 'poll_option', 'poll_delete',
	'email', 'confirm_code', 'aim', 'msn', 'yim',
	'interests', 'occupation', 'signature', 'website',
	'location', 'search', 'sitename', 'word',
	'replacement', 'help', 'last_msg', 'quote', 'dl',
	'preview', 'post', 'mode', 'content', 'server_name',
	'script_path', 'sitename', 'site_desc', 'disable_reg_msg',
	'disable_msg', 'cookie', 'avatar', 'file', 'picture',
	'filter', 'xs', 'edit', 'content', 'fileupload', 'filecomment',
	'comment', 'rate', 'pic', 'search_author', 'add_poll_option_text',
	'long_desc', 'submit',
	// Added by MG
	'f', 't', 'p', 'u', 's', 'l',
	'skype', 'selfdes', 'gender', 'user_flag', 'phone', 'topic_desc',
	'title', 'position', 'active', 'type', 'cache', 'cachetime',
	'blockfile', 'view', 'border', 'titlebar', 'local', 'background',
	'edit', 'lid', 'id', 'save', 'post_time', 'usersubmit', 'notes',
	'topictype', 'news_category', 'smiles_button', 'topic_show_portal',
	'disable_html', 'disable_acro_auto', 'disable_bbcode', 'disable_smilies',
	'attach_sig', 'setbm', 'notify', 'mark_edit', 'delete', 'lock', 'unlock',
	'topic_calendar_hour', 'topic_calendar_min', 'topic_calendar_duration_day',
	'topic_calendar_duration_hour', 'topic_calendar_duration_min', 'add_attachment_box',
	'posted_attachments_box', 'attachment_list', 'filename_list', 'extension_list',
	'mimetype_list', 'filesize_list', 'filetime_list', 'attach_id_list',
	'attach_thumbnail_list', 'comment_list', 'lock_subject', 'helpbox', 'link_desc',
	'addcategory', 'chatbarText', 'name', 'act', 'nm', 'co', 'sh', 'su', 'search_keywords',
	'submitavatar', 'del_attachment', 'update_attachment', 'edit_comment',
	'add_poll_option', 'edit_poll_option', 'del_poll_option', 'poll_option_text',
	'pic_title', 'pic_desc', 'article_desc', 'email_confirm', 'article_name', 'article_desc',
	'menu_name', 'menu_desc', 'menu_name_lang', 'remove', 'add', 'add_cat', 'add_item',
	'autologin', 'current_email', 'download', 'menu_link', 'new_password', 'password_confirm',
	'action_update', 'action_duplicate', 'duplicate_blocks', 'deleteall',
	'top_html_block_text', 'bottom_html_block_text', 'cat_title', 'cat_desc',
	'news_category_edit', 'pm_delete_attach',
	// '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
);

// Some fields in $_GET don't get checked to prevent wrong detection
$unchecked_get_fields = array(
	'submit', 'search_author',
	// Added by MG
	'mode', 'l_id', 'b_id', 'bv_id',
	'cachefile', 'avatarfile', 'text_content', 'text_size', 'text_font',
	'text_color', 'text_position',
);

/*
* Let's check if a security level is set
* and prepare our variables
*/
if (!defined('CT_SECLEVEL') || (CT_SECLEVEL == 'HIGH'))
{
	// Empty the variables for security reasons
	$ct_addheuristic = $ct_delheuristic = array();
	$ct_ignoregvar = $ct_ignorepvar = array();
	$ct_regex_ignorep = $ct_regex_ignoreg = array();
}
elseif ((CT_SECLEVEL == 'MEDIUM') || (CT_SECLEVEL == 'LOW'))
{
	// Delete all duplicate heuristics and then merge with the standard rules
	$ct_addheuristic = array_diff((array) $ct_addheuristic, $ct_rules);
	$ct_rules = array_merge($ct_rules, $ct_addheuristic);

	// Now let's check if there are heuristics we want to ignore for this time
	$ct_rules = array_diff($ct_rules, (array) $ct_delheuristic);

	// Maybe also some new $_POST fields to ignore?
	$ct_ignorepvar = array_diff((array) $ct_ignorepvar, $unchecked_post_fields);
	$unchecked_post_fields = array_merge($unchecked_post_fields, $ct_ignorepvar);

	// Last but not least the same with $_GET
	$ct_ignoregvar = array_diff((array) $ct_ignoregvar, $unchecked_get_fields);
	$unchecked_get_fields = array_merge($unchecked_get_fields, $ct_ignoregvar);

	// Oh look, a new regex ignore method for smart admins and mod coders
	if (isset($ct_regex_ignorep))
	{
		$ct_regex_ignorep = implode('|', $ct_regex_ignorep);
	}
	if (isset($ct_regex_ignoreg))
	{
		$ct_regex_ignoreg = implode('|', $ct_regex_ignoreg);
	}
}

// Initialize detector var
$ct_attack_detection = false;

// Write query String in the var $cracktrack and make it lowercase
$cracktrack = strtolower($_SERVER['QUERY_STRING']);

// Filter out the unchecked fields
$unchecked_get_fields = implode('|', $unchecked_get_fields);
$cracktrack = preg_replace('#((' . $unchecked_get_fields . ')=([^&]|&amp;)*)#', '', $cracktrack);

// Prevent Slash Tricks (SQL Tricks with /* are now stopped directly! So we don't replace this now!)
$cracktrack = str_replace('\\', '/', $cracktrack);

// Save copies for the debug mode check
$crackcheck = $cracktrack;

// Now we do a very simple method to mark potential Worm activities
$checkworm = str_replace($ct_rules, '*', $cracktrack);
if ($cracktrack != $checkworm)
{
	$ct_attack_detection = true;
	ct_debugger($crackcheck, 'GET');
}
else
{
	// We also check for rawurldecode-tricks
	$checkworm = str_replace($ct_rules, '*', strtolower(rawurldecode($cracktrack)));
	if (strtolower(rawurldecode($cracktrack)) != $checkworm)
	{
		$ct_attack_detection = true;
		ct_debugger($crackcheck, 'RAWGET');
	}
	elseif (CT_SECLEVEL != 'LOW' || !defined('CT_SECLEVEL'))
	{
		// We create a copy of the $_POST for checking
		$checkpost = (is_array($_POST)) ? $_POST : array();

		// Now we have a look to $_POST
		foreach ($checkpost as $post_var_fieldname => $post_var_field_value)
		{
			if (!in_array($post_var_fieldname, $unchecked_post_fields))
			{
				// We do a preg_replace if a smart admin used the regex ignore
				$post_var_check = (isset($ct_regex_ignorep)) ? preg_replace("#^($ct_regex_ignorep)$#", '*', $post_var_fieldname) : $post_var_fieldname;
				if ($post_var_check == $post_var_fieldname)
				{
					if (is_array($post_var_field_value))
					{
						// We proudly present AT-AT our new imperial array walker
						$post_var_field_value = atatwalk($post_var_field_value);
					}

					// Prevent tricks wich comment out SQL command
					$post_var_field_value = strtolower(str_replace('\\', '/', $post_var_field_value));

					// Now we do a very simple method to mark potential Worm activities
					$check_var = str_replace($ct_rules, '*', strtolower($post_var_field_value));

					if ($post_var_field_value != $check_var)
					{
						ct_debugger($checkpost, 'POST');
						$ct_attack_detection = true;
						// Attack found so we can leave the foreach loop
						break;
					}
					else
					{
						// We again check for rawurldecode tricks
						$check_var = str_replace($ct_rules, '*', strtolower(rawurldecode($post_var_field_value)));
						if (strtolower(rawurldecode($post_var_field_value)) != $check_var)
						{
							ct_debugger($checkpost, 'RAWPOST');
							$ct_attack_detection = true;
							// Attack found so we can leave the foreach loop
							break;
						}
					}
				}
			}
		}
	}
}

if ($ct_attack_detection)
{
	if (CT_DEBUG_MODE !== true)
	{
		// include class for Logfile Management
		include_once(IP_ROOT_PATH . 'ctracker/classes/class_log_manager.' . PHP_EXT);

		// write data into logfile
		$logfile = new log_manager();
		$logfile->write_worm();
		unset($logfile);
	}
	if ($disable_ct_warning == false)
	{

		// generate HTML Message
$htmloutput = <<<EOM
<html>
<head><title>CBACK CrackerTracker :: Security Alert</title></head>
<body>
<br />
<div align="center">
<table style="border:2px solid #000000" border="0" width="600" cellpadding="10" cellspacing="0">
<tr>
	<td align="left" bgcolor="#000000"><font face="Tahoma, Arial, Helvetica" size="4" color="#FFFFFF"><b>SECURITY ALERT&nbsp;&raquo;&nbsp;&raquo;&nbsp;&raquo;&nbsp;&raquo;</b></font></td>
</tr>
<tr>
	<td bgcolor="#FFDFDF" align="left">
	<font face="Tahoma, Arial, Helvetica" size="2" color="#000000">
		<b>CBACK CrackerTracker</b> has detected a potential attack on this site with a worm
		or exploit script so the Security System stopped the script.
		<br /><br /><br />
		If you can see this page after including a new MOD into your board or after clicking
		on a link please contact the Board Administrator with this error message and a description
		what you have done before you could see this page, that the Admin has the possibility to
		fix the problem.
	</font>
	</td>
</tr>
</table>
</div>
</body>
</html>
EOM;

		// stop the script
		die($htmloutput);
	}
}

// Tell the self test that this script was included correctly
define('protection_unit_one', true);
// Unfortunately we cannot unset $ct_rules because they are used in other files
//unset($ct_rules);
unset($unchecked_post_fields);
unset($unchecked_get_fields);

function ct_debugger($checkstring, $checkmode)
{
	if (CT_DEBUG_MODE === false)
	{
		return;
	}
	global $ct_rules, $unchecked_post_fields;

	$dbgunchecked_post_fields = implode('|', $unchecked_post_fields);

	if (in_array($checkmode, array('POST', 'RAWPOST')))
	{
		$temp = '&';
		foreach($checkstring as $key=>$val)
		{
			$val = (is_array($val)) ? atatwalk($val) : $val;
			$temp .= "$key=$val&";
		}
		$checkstring = $temp;

		// Cut out the keys we already ignore
		$checkstring = preg_replace('#((' . $dbgunchecked_post_fields . ')=([^&]|&amp;)*)#', '', $checkstring);
	}
	if (in_array($checkmode, array('RAWGET', 'RAWPOST')))
	{
		$checkstring = rawurldecode($checkstring);
	}

	// Now we start debugging
	$matching_vars = array();
	$found_matches = '';
	foreach($ct_rules as $rule)
	{
		$preg_rule = preg_quote($rule, "#");
		if (preg_match_all('#(^|&)([^&]*?)=[^&]*?' . $preg_rule . '[^&]*($|&)#is', $checkstring, $dbgmatch, PREG_PATTERN_ORDER))
		{
			$found_matches .= "Matching rule: $rule\n";
			foreach($dbgmatch[2] as $matchline)
			{
				$found_matches .= "In variable:   $matchline\n";
				$matching_vars[] = $matchline;
			}
			$found_matches .= "\n";
		}
	}
	$matching_vars = array_unique($matching_vars);
	$matching_vars = implode("','", $matching_vars);
	$matching_vars = "'" . $matching_vars . "'";

	if (count($matching_vars))
	{
		// let's open the debug file and write in some stuff ;)
		$debugstream = @fopen(IP_ROOT_PATH . 'ctracker/logfiles/logfile_debug_mode.txt', 'ab');
		$scriptname = str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']);
		$scriptname = ((substr($scriptname, 0, 1) == '/') ? '' : '/') . $scriptname;
		$scriptname = str_replace('//', '/', $scriptname);

		@fputs($debugstream, "++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n");
		@fputs($debugstream, 'Script-Filename: ' . $scriptname . "\n----------------\n\n");
		@fputs($debugstream, 'Attack-Time: ' . date('d.m.Y G:i a') . "\n------------\n\n");
		@fputs($debugstream, 'Request-Method: ' . (strpos($checkmode, 'POST') !== false ? 'POST' : 'GET') . "\n\n");
		@fputs($debugstream, $found_matches);
		@fputs($debugstream, 'Possible solution:' . "\n------------------\n\n");
		modcommand($debugstream, 'OPEN');
		@fputs($debugstream, "$scriptname\n\n");
		modcommand($debugstream, 'FIND');
		if (preg_match('#^/admin/(admin_|index\.php)#', $scriptname))
		{
			@fputs($debugstream, "require('./pagestart.' . " . PHP_EXT . ");\n\n");
		}
		else
		{
			@fputs($debugstream, "include(IP_ROOT_PATH . 'common.' . " . PHP_EXT . ");\n\n");
		}
		modcommand($debugstream, 'BEFORE, ADD');
		@fputs($debugstream, "define('CT_SECLEVEL', 'MEDIUM');\n");
		if (strpos($checkmode, 'POST') !== false)
		{
			@fputs($debugstream, "\$ct_ignorepvar = array($matching_vars);\n\n");
		}
		else
		{
			@fputs($debugstream, "\$ct_ignoregvar = array($matching_vars);\n\n");
		}
		modcommand($debugstream, 'SAVE/CLOSE ALL FILES');
		@fputs($debugstream, "# EoM\n\n");

		@fclose($debugstream);
	}
}

function modcommand($handle, $command)
{
	@fputs($handle,"#\n");
	@fputs($handle,"#-----[ " . strtoupper($command) . " ]------------------------------------------\n");
	@fputs($handle,"#\n");
}

// Function to walk through arrays
// and find those nasty rebell value hideouts
function atatwalk($var_array)
{
	$complete_post = '';
	foreach($var_array as $var=>$key)
	{
		if (!is_array($key))
		{
			// If we don't need to dive deeper anymore
			// we can use php functions to fastly paste all values together
			return implode('!', $var_array);
		}
		// Deeper into the dungeon my dear
		$complete_post .= atatwalk($key) . '!';
	}
	return $complete_post;
}

?>