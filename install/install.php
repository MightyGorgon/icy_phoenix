<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human

// Begin
error_reporting (E_ERROR | E_WARNING | E_PARSE); // This will NOT report uninitialized variables
set_magic_quotes_runtime(0); // Disable magic_quotes_runtime

// Slash data if it isn't slashed
if (!get_magic_quotes_gpc())
{
	if (is_array($_GET))
	{
		while (list($k, $v) = each($_GET))
		{
			if (is_array($_GET[$k]))
			{
				while (list($k2, $v2) = each($_GET[$k]))
				{
					$_GET[$k][$k2] = addslashes($v2);
				}
				@reset($_GET[$k]);
			}
			else
			{
				$_GET[$k] = addslashes($v);
			}
		}
		@reset($_GET);
	}

	if (is_array($_POST))
	{
		while (list($k, $v) = each($_POST))
		{
			if (is_array($_POST[$k]))
			{
				while (list($k2, $v2) = each($_POST[$k]))
				{
					$_POST[$k][$k2] = addslashes($v2);
				}
				@reset($_POST[$k]);
			}
			else
			{
				$_POST[$k] = addslashes($v);
			}
		}
		@reset($_POST);
	}

	if (is_array($_COOKIE))
	{
		while (list($k, $v) = each($_COOKIE))
		{
			if (is_array($_COOKIE[$k]))
			{
				while (list($k2, $v2) = each($_COOKIE[$k]))
				{
					$_COOKIE[$k][$k2] = addslashes($v2);
				}
				@reset($_COOKIE[$k]);
			}
			else
			{
				$_COOKIE[$k] = addslashes($v);
			}
		}
		@reset($_COOKIE);
	}
}

// Begin main prog
define('IN_PHPBB', true);
// Uncomment the following line to completely disable the ftp option...
// define('NO_FTP', true);
$phpbb_root_path = './../';
include($phpbb_root_path . 'extension.inc');

// Initialise some basic arrays
$userdata = array();
$lang = array();
$error = false;

// Include some required functions
include($phpbb_root_path . 'includes/constants.' . $phpEx);
include($phpbb_root_path . 'includes/functions.' . $phpEx);
include($phpbb_root_path . 'includes/sessions.' . $phpEx);

// Define schema info
$available_dbms = array(
	'mysql4' => array(
		'LABEL'				=> 'MySQL 4.x/5.x',
		'SCHEMA'			=> 'mysql',
		'DELIM'				=> ';',
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_remarks'
	),
	'mysql'=> array(
		'LABEL'				=> 'MySQL 3.x',
		'SCHEMA'			=> 'mysql',
		'DELIM'				=> ';',
		'DELIM_BASIC'	=> ';',
		'COMMENTS'		=> 'remove_remarks'
	),
);

// Obtain various vars
$confirm = (isset($_POST['confirm'])) ? true : false;
$cancel = (isset($_POST['cancel'])) ? true : false;

if (isset($_POST['install_step']) || isset($_GET['install_step']))
{
	$install_step = (isset($_POST['install_step'])) ? $_POST['install_step'] : $_GET['install_step'];
}
else
{
	$install_step = '0';
}

$upgrade = (!empty($_POST['upgrade'])) ? $_POST['upgrade']: '';
$upgrade_now = (!empty($_POST['upgrade_now'])) ? $_POST['upgrade_now']:'';

$dbms = isset($_POST['dbms']) ? $_POST['dbms'] : '';

$dbhost = (!empty($_POST['dbhost'])) ? $_POST['dbhost'] : 'localhost';
$dbuser = (!empty($_POST['dbuser'])) ? $_POST['dbuser'] : '';
$dbpasswd = (!empty($_POST['dbpasswd'])) ? $_POST['dbpasswd'] : '';
$dbname = (!empty($_POST['dbname'])) ? $_POST['dbname'] : '';

$table_prefix = (!empty($_POST['prefix'])) ? $_POST['prefix'] : '';

$admin_name = (!empty($_POST['admin_name'])) ? $_POST['admin_name'] : '';
$admin_pass1 = (!empty($_POST['admin_pass1'])) ? $_POST['admin_pass1'] : '';
$admin_pass2 = (!empty($_POST['admin_pass2'])) ? $_POST['admin_pass2'] : '';

$ftp_path = (!empty($_POST['ftp_path'])) ? $_POST['ftp_path'] : '';
$ftp_user = (!empty($_POST['ftp_user'])) ? $_POST['ftp_user'] : '';
$ftp_pass = (!empty($_POST['ftp_pass'])) ? $_POST['ftp_pass'] : '';

if (isset($_POST['lang']) && preg_match('#^[a-z_]+$#', $_POST['lang']))
{
	$language = strip_tags($_POST['lang']);
}
else
{
	$language = guess_lang();
}

$board_email = (!empty($_POST['board_email'])) ? $_POST['board_email'] : '';
$script_path = (!empty($_POST['script_path'])) ? $_POST['script_path'] : str_replace('install', '', dirname($_SERVER['PHP_SELF']));

if (!empty($_POST['server_name']))
{
	$server_name = $_POST['server_name'];
}
else
{
	// Guess at some basic info used for install...
	if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
	{
		$server_name = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];
	}
	else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
	{
		$server_name = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
	}
	else
	{
		$server_name = '';
	}
}

if (!empty($_POST['server_port']))
{
	$server_port = $_POST['server_port'];
}
else
{
	if (!empty($_SERVER['SERVER_PORT']) || !empty($_ENV['SERVER_PORT']))
	{
		$server_port = (!empty($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : $_ENV['SERVER_PORT'];
	}
	else
	{
		$server_port = '80';
	}
}

// Open config.php... if it exists
if (@file_exists(@phpbb_realpath('config.' . $phpEx)))
{
	include($phpbb_root_path . 'config.' . $phpEx);
}

// Is phpBB already installed? Yes? Redirect to the index
if (defined('PHPBB_INSTALLED'))
{
	redirect('../index.' . $phpEx);
}

// Import language file, setup template ...
include($phpbb_root_path . 'language/lang_' . $language . '/lang_bbc_tags.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $language . '/lang_main.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $language . '/lang_main_settings.' . $phpEx);
include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin.' . $phpEx);

if ($install_step == 0)
{
	page_header($lang['Inst_Step_0'], false, false);
	apply_chmod();
	page_footer();
	exit;
}

// Ok for the time being I'm commenting this out whilst I'm working on
// better integration of the install with upgrade as per Bart's request JLH
if ($upgrade == 1)
{
	// require('upgrade.' . $phpEx);
	$install_step = 1;
}

// What do we need to do?
if ( !empty($_POST['send_file']) && ($_POST['send_file'] == 1) && empty($_POST['upgrade_now']) )
{
	header('Content-Type: text/x-delimtext; name="config.' . $phpEx . '"');
	header('Content-disposition: attachment; filename="config.' . $phpEx . '"');

	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	// because we add slashes at the top if its off, and they are added automatically if it is on.
	echo stripslashes($_POST['config_data']);

	exit;
}
elseif ( !empty($_POST['send_file']) && ($_POST['send_file'] == 2) )
{
	$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars(stripslashes($_POST['config_data'])) . '" />';
	$s_hidden_fields .= '<input type="hidden" name="ftp_file" value="1" />';

	if ($upgrade == 1)
	{
		$s_hidden_fields .= '<input type="hidden" name="upgrade" value="1" />';
	}
	else
	{
		$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';
	}

	page_header($lang['ftp_instructs']);
?>
<tr>
	<td width="100%" colspan="3" style="padding-left:10px;padding-right:10px;">
	<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
	<tr><th colspan="2"><?php echo $lang['ftp_info']; ?></th></tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo $lang['ftp_path']; ?></span></td>
		<td class="row2"><input class="post" type="text" name="ftp_dir"></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo $lang['ftp_username']; ?></span></td>
		<td class="row2"><input type="text" name="ftp_user"></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo $lang['ftp_password']; ?></span></td>
		<td class="row2"><input type="password" name="ftp_pass"></td>
	</tr>
<?php
	page_common_form($s_hidden_fields, $lang['Transfer_config']);
?>
		</td>
	</tr>
	</table>
<?php
	page_footer();
	exit;
}
elseif (!empty($_POST['ftp_file']))
{
	// Try to connect ...
	$conn_id = @ftp_connect('localhost');
	$login_result = @ftp_login($conn_id, "$ftp_user", "$ftp_pass");

	if (!$conn_id || !$login_result)
	{
		page_header($lang['NoFTP_config']);

		// Error couldn't get connected... Go back to option to send file...
		$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars(stripslashes($_POST['config_data'])) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" />';

		// If we're upgrading ...
		if ($upgrade == 1)
		{
			$s_hidden_fields .= '<input type="hidden" name="upgrade" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="dbms" value="' . $dmbs. '" />';
			$s_hidden_fields .= '<input type="hidden" name="prefix" value="' . $table_prefix. '" />';
			$s_hidden_fields .= '<input type="hidden" name="dbhost" value="' . $dbhost. '" />';
			$s_hidden_fields .= '<input type="hidden" name="dbname" value="' . $dbname. '" />';
			$s_hidden_fields .= '<input type="hidden" name="dbuser" value="' . $dbuser. '" />';
			$s_hidden_fields .= '<input type="hidden" name="dbpasswd" value="' . $dbpasswd. '" />';
			$s_hidden_fields .= '<input type="hidden" name="admin_pass1" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="admin_pass2" value="1" />';
			$s_hidden_fields .= '<input type="hidden" name="server_port" value="' . $server_port. '" />';
			$s_hidden_fields .= '<input type="hidden" name="server_name" value="' . $server_name. '" />';
			$s_hidden_fields .= '<input type="hidden" name="script_path" value="' . $script_path. '" />';
			$s_hidden_fields .= '<input type="hidden" name="board_email" value="' . $board_email. '" />';
			$s_hidden_fields .= '<input type="hidden" name="install_step" value="2" />';
			page_upgrade_form();
		}
		else
		{
			$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';
			page_common_form($s_hidden_fields, $lang['Download_config']);
		}
		page_footer();
		exit;
	}
	else
	{
		// Write out a temp file...
		$tmpfname = @tempnam('/tmp', 'cfg');

		@unlink($tmpfname); // unlink for safety on php4.0.3+

		$fp = @fopen($tmpfname, 'w');

		@fwrite($fp, stripslashes($_POST['config_data']));

		@fclose($fp);

		// Now ftp it across.
		@ftp_chdir($conn_id, $ftp_dir);

		$res = ftp_put($conn_id, 'config.' . $phpEx, $tmpfname, FTP_ASCII);

		@ftp_quit($conn_id);

		unlink($tmpfname);

		if ($upgrade == 1)
		{
			require('upgrade.' . $phpEx);
			exit;
		}

		// Ok we are basically done with the install process let's go on
		// and let the user configure their board now. We are going to do
		// this by calling the admin_board.php from the normal board admin section.
		$s_hidden_fields = '<input type="hidden" name="username" value="' . $admin_name . '" />';
		$s_hidden_fields .= '<input type="hidden" name="password" value="' . $admin_pass1 . '" />';
		$s_hidden_fields .= '<input type="hidden" name="redirect" value="../adm/index.' . $phpEx . '" />';
		$s_hidden_fields .= '<input type="hidden" name="submit" value="' . $lang['Login'] . '" />';
		$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';

		page_header($lang['Inst_Step_2']);
		finish_install($s_hidden_fields);
		page_footer();
		exit();
	}
}
elseif ( ($install_step == 1) || ($admin_pass1 != $admin_pass2) || empty($admin_pass1) || empty($dbhost))
{
	// Ok we haven't installed before so lets work our way through the various
	// steps of the install process. This could turn out to be quite a lenghty process.

	// Step 0 gather the pertinant info for database setup...
	// Namely dbms, dbhost, dbname, dbuser, and dbpasswd.
	$instruction_text = $lang['Inst_Step_1'];

	if ($install_step > 1)
	{
		if ((($_POST['admin_pass1'] != $_POST['admin_pass2'])) || (empty($_POST['admin_pass1']) || empty($dbhost)) && $_POST['cur_lang'] == $language)
		{
			$error = $lang['Password_mismatch'];
		}
	}

	$lang_select = build_lang_select($language);

	$dbms_select = '<select name="dbms" onchange="if(this.form.upgrade.options[this.form.upgrade.selectedIndex].value == 1){ this.selectedIndex = 0;}">';
	while (list($dbms_name, $details) = @each($available_dbms))
	{
		$selected = ($dbms_name == $dbms) ? 'selected="selected"' : '';
		$dbms_select .= '<option value="' . $dbms_name . '">' . $details['LABEL'] . '</option>';
	}
	$dbms_select .= '</select>';

	$upgrade_option = '<select name="upgrade"';
	$upgrade_option .= 'onchange="if (this.options[this.selectedIndex].value == 1) { this.form.dbms.selectedIndex = 0; }">';
	$upgrade_option .= '<option value="0">' . $lang['Install'] . '</option></select>';
	//$upgrade_option .= '<option value="1">' . $lang['Upgrade'] . '</option></select>';

	$s_hidden_fields = '';
	$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';
	//$s_hidden_fields .= '<input type="hidden" name="install_step" value="2" />';

	page_header($instruction_text);
	setup_form($error, $lang_select, $dbms_select, $upgrade_option, $dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix, $board_email, $server_name, $server_port, $script_path, $admin_name, $admin_pass1, $admin_pass2, $language, $s_hidden_fields);
	page_footer();
	exit;
}
else
{
	// Go ahead and create the DB, then populate it
	//
	if (isset($dbms))
	{
		switch($dbms)
		{
			case 'mysql':
			case 'mysql4':
				$check_exts = 'mysql';
				$check_other = 'mysql';
				break;
		}

		if (!extension_loaded($check_exts) && !extension_loaded($check_other))
		{
			page_header($lang['Install'], '');
			page_error($lang['Installer_Error'], $lang['Install_No_Ext']);
			page_footer();
			exit;
		}

		include($phpbb_root_path . 'includes/db.' . $phpEx);
	}

	$dbms_schema = 'schemas/' . $available_dbms[$dbms]['SCHEMA'] . '_schema.sql';
	$dbms_basic = 'schemas/' . $available_dbms[$dbms]['SCHEMA'] . '_basic.sql';

	$remove_remarks = $available_dbms[$dbms]['COMMENTS'];
	$delimiter = $available_dbms[$dbms]['DELIM'];
	$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC'];

	if ($install_step == 2)
	{
		if ($upgrade != 1)
		{
			// Load in the sql parser
			include($phpbb_root_path . 'includes/sql_parse.' . $phpEx);

			// Ok we have the db info go ahead and read in the relevant schema
			// and work on building the table.. probably ought to provide some
			// kind of feedback to the user as we are working here in order
			// to let them know we are actually doing something.
			$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
			$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_query);

			$sql_query = $remove_remarks($sql_query);
			$sql_query = split_sql_file($sql_query, $delimiter);

			for ($i = 0; $i < count($sql_query); $i++)
			{
				if (trim($sql_query[$i]) != '')
				{
					if (!($result = $db->sql_query($sql_query[$i])))
					{
						$error = $db->sql_error();
						page_header($lang['Install'], '');
						page_error($lang['Installer_Error'], $lang['Install_db_error'] . '<br />' . $error['message']);
						page_footer();
						exit;
					}
				}
			}

			// Ok tables have been built, let's fill in the basic information
			$sql_query = @fread(@fopen($dbms_basic, 'r'), @filesize($dbms_basic));
			$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_query);

			$sql_query = $remove_remarks($sql_query);
			$sql_query = split_sql_file($sql_query, $delimiter_basic);

			for($i = 0; $i < count($sql_query); $i++)
			{
				if (trim($sql_query[$i]) != '')
				{
					if (!($result = $db->sql_query($sql_query[$i])))
					{
						$error = $db->sql_error();
						page_header($lang['Install'], '');
						page_error($lang['Installer_Error'], $lang['Install_db_error'] . '<br />' . $error['message']);
						page_footer();
						exit;
					}
				}
			}

			// Ok at this point they have entered their admin password, let's go
			// ahead and create the admin account with some basic default information
			// that they can customize later, and write out the config file. After
			// this we are going to pass them over to the admin_forum.php script
			// to set up their forum defaults.
			$error = '';
			$sql = "INSERT INTO " . $table_prefix . "stats_config (config_name, config_value)
				VALUES ('install_date', " . time() . ")";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not insert statistics_mod_startdate :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}

			$sql = "INSERT INTO " . $table_prefix . "config (config_name, config_value)
				VALUES ('board_startdate', " . time() . ")";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not insert board_startdate :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}

			$sql = "INSERT INTO " . $table_prefix . "config (config_name, config_value)
							VALUES ('default_lang', '" . str_replace("\'", "''", $language) . "')";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not insert default_lang :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}

			$sql = "INSERT INTO " . $table_prefix . "link_config (config_name, config_value)
							VALUES ('site_logo', 'http://" . $server_name . $script_path . "images/links/banner_ip.gif')";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not insert site logo data :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}

			$sql = "INSERT INTO " . $table_prefix . "link_config (config_name, config_value)
							VALUES ('site_url', 'http://" . $server_name . $script_path . "')";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not insert site url data :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}
			$update_config = array(
				'board_email' => $board_email,
				'script_path' => $script_path,
				'server_port' => $server_port,
				'server_name' => $server_name,
				'upi2db_install_time' => time(),
			);

			while (list($config_name, $config_value) = each($update_config))
			{
				$sql = "UPDATE " . $table_prefix . "config
					SET config_value = '$config_value'
					WHERE config_name = '$config_name'";
				if (!$db->sql_query($sql))
				{
					$error .= "Could not insert default_lang :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
				}
			}

			$admin_pass_md5 = ($confirm && $userdata['user_level'] == ADMIN) ? $admin_pass1 : md5($admin_pass1);

			$sql = "UPDATE " . $table_prefix . "users
				SET username = '" . str_replace("\'", "''", $admin_name) . "', user_password='" . str_replace("\'", "''", $admin_pass_md5) . "', user_lang = '" . str_replace("\'", "''", $language) . "', user_email='" . str_replace("\'", "''", $board_email) . "'
				WHERE username = 'Admin'";
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update admin info :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}

			$sql = "UPDATE " . $table_prefix . "users
				SET user_regdate = " . time();
			if (!$db->sql_query($sql))
			{
				$error .= "Could not update user_regdate :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}

			if ($error != '')
			{
				page_header($lang['Install'], '');
				page_error($lang['Installer_Error'], $lang['Install_db_error'] . '<br /><br />' . $error);
				page_footer();
				exit;
			}
		}

		if (!$upgrade_now)
		{
			// Write out the config file.
			$config_data = '<?php' . "\n\n";
			$config_data .= "\n// Icy Phoenix auto-generated config file\n// Do not change anything in this file!\n\n";
			$config_data .= '$dbms = \'' . $dbms . '\';' . "\n\n";
			$config_data .= '$dbhost = \'' . $dbhost . '\';' . "\n";
			$config_data .= '$dbname = \'' . $dbname . '\';' . "\n";
			$config_data .= '$dbuser = \'' . $dbuser . '\';' . "\n";
			$config_data .= '$dbpasswd = \'' . $dbpasswd . '\';' . "\n\n";
			$config_data .= '$table_prefix = \'' . $table_prefix . '\';' . "\n\n";
			$config_data .= 'define(\'PHPBB_INSTALLED\', true);' . "\n\n";
			$config_data .= '?' . '>'; // Done this to prevent highlighting editors getting confused!

			@umask(0111);
			$no_open = false;

			// Unable to open the file writeable do something here as an attempt to get around that...
			if (!($fp = @fopen($phpbb_root_path . 'config.' . $phpEx, 'w')))
			{
				$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars($config_data) . '" />';
				$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';

				if (@extension_loaded('ftp') && !defined('NO_FTP'))
				{
					page_header($lang['Unwriteable_config'] . '<p>' . $lang['ftp_option'] . '</p>');

?>
<tr>
	<td width="100%" colspan="3" style="padding-left:10px;padding-right:10px;">
	<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
	<tr><th colspan="2"><?php echo $lang['ftp_choose']; ?></th></tr>
	<tr>
		<td class="row1" align="right" width="50%"><span class="gen"><?php echo $lang['Attempt_ftp']; ?></span></td>
		<td class="row2"><input type="radio" name="send_file" value="2" /></td>
	</tr>
	<tr>
		<td class="row1" align="right" width="50%"><span class="gen"><?php echo $lang['Send_file']; ?></span></td>
		<td class="row2"><input type="radio" name="send_file" value="1" /></td>
	</tr>
<?php

				}
				else
				{
					page_header($lang['Unwriteable_config']);
					$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" />';
				}

				if ($upgrade == 1)
				{
					$s_hidden_fields .= '<input type="hidden" name="upgrade" value="1" />';
					$s_hidden_fields .= '<input type="hidden" name="dbms" value="' . $dbms . '" />';
					$s_hidden_fields .= '<input type="hidden" name="prefix" value="' . $table_prefix . '" />';
					$s_hidden_fields .= '<input type="hidden" name="dbhost" value="' . $dbhost . '" />';
					$s_hidden_fields .= '<input type="hidden" name="dbname" value="' . $dbname . '" />';
					$s_hidden_fields .= '<input type="hidden" name="dbuser" value="' . $dbuser . '" />';
					$s_hidden_fields .= '<input type="hidden" name="dbpasswd" value="' . $dbpasswd . '" />';
					$s_hidden_fields .= '<input type="hidden" name="admin_pass1" value="1" />';
					$s_hidden_fields .= '<input type="hidden" name="admin_pass2" value="1" />';
					$s_hidden_fields .= '<input type="hidden" name="server_port" value="' . $server_port . '" />';
					$s_hidden_fields .= '<input type="hidden" name="server_name" value="' . $server_name . '" />';
					$s_hidden_fields .= '<input type="hidden" name="script_path" value="' . $script_path . '" />';
					$s_hidden_fields .= '<input type="hidden" name="board_email" value="' . $board_email . '" />';
					$s_hidden_fields .= '<input type="hidden" name="install_step" value="2" />';
					page_upgrade_form();
				}
				else
				{
					$s_hidden_fields .= '<input type="hidden" name="install_step" value="2" />';
					$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language . '" />';
					page_common_form($s_hidden_fields, $lang['Download_config']);
?>
		</td>
	</tr>
	</table>
<?php
				}

				page_footer();
				exit;
			}

			$result = @fputs($fp, $config_data, strlen($config_data));

			@fclose($fp);
			$upgrade_now = $lang['upgrade_submit'];
		}

		// First off let's check and see if we are supposed to be doing an upgrade.
		if ( ($upgrade == 1) && ($upgrade_now == $lang['upgrade_submit']) )
		{
			define('INSTALLING', true);
			//require('update_to_ip.' . $phpEx);
			exit;
		}

		// Ok we are basically done with the install process let's go on
		// and let the user configure their board now. We are going to do
		// this by calling the admin_board.php from the normal board admin section.
		$s_hidden_fields = '<input type="hidden" name="username" value="' . $admin_name . '" />';
		$s_hidden_fields .= '<input type="hidden" name="password" value="' . $admin_pass1 . '" />';
		$s_hidden_fields .= '<input type="hidden" name="redirect" value="adm/index.' . $phpEx . '" />';
		$s_hidden_fields .= '<input type="hidden" name="login" value="true" />';
		$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';

		page_header('<center>' . $lang['BBC_IP_CREDITS'] . '</center>', '../login_ip.' . $phpEx);
		finish_install($s_hidden_fields);
		page_footer();
		exit;
	}
}


// ---------
// FUNCTIONS
//

function page_header($text, $form_action = false, $write_form = true)
{
	global $phpEx, $lang;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['ENCODING']; ?>" />
	<meta name="author" content="Icy Phoenix Team" />
	<title><?php echo $lang['Welcome_install']; ?> :: Icy Phoenix</title>
	<link rel="stylesheet" href="../templates/common/acp.css" type="text/css" />
	<link rel="shortcut icon" href="../images/favicon.ico" />
	<!--[if lt IE 7]>
	<script type="text/javascript" src="../templates/common/js/pngfix.js"></script>
	<![endif]-->
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td class="leftshadow" width="9" valign="top"><img src="../images/spacer.gif" alt="" width="9" height="1" /></td>
		<td width="100%" valign="top">

<?php
if ($write_form == true)
{
?>
	<form action="<?php echo ($form_action) ? $form_action : 'install.' . $phpEx; ?>" name="install" method="post">
<?php
}
?>

<table id="forumtable" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" colspan="3" valign="top">
		<div id="top_logo">
			<table class="" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td height="150" align="left" valign="middle">
					<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="../images/logo_ip.png" alt="Icy Phoenix" title="Icy Phoenix" /></a>
				</td>
			</tr>
			</table>
		</div>
	</td>
</tr>
<tr>
	<td width="100%" colspan="3" style="padding-left:10px;padding-right:10px;">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header" colspan="2"><span><?php echo $lang['Welcome_install']; ?></span></td></tr>
			<tr><td class="row1" colspan="2"><br /><div class="gen"><?php echo $text; ?></div><br /></td></tr>
		</table>
	</td>
</tr>
<?php

}

function page_footer($write_form = true)
{

?>
<tr>
	<td width="100%" colspan="3">
	<div id="bottom_logo_ext">
	<div id="bottom_logo">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td nowrap="nowrap" width="45%" align="left">
					<span class="copyright">&nbsp;Powered by <a href="http://www.icyphoenix.com/" target="_blank">Icy Phoenix</a> based on <a href="http://www.phpbb.com/" target="_blank">phpBB</a></span>
				</td>
				<td nowrap="nowrap" align="center">&nbsp;<br />&nbsp;</td>
				<td nowrap="nowrap" width="45%" align="right">
					<span class="copyright">Design by <a href="http://www.mightygorgon.com" target="_blank">Mighty Gorgon</a>&nbsp;</span>
				</td>
			</tr>
		</table>
	</div>
	</div>
	</td>
</tr>
</table>

<?php
if ($write_form == true)
{
?>
	</form>
<?php
}
?>

		</td>
		<td class="rightshadow" width="9" valign="top"><img src="../images/spacer.gif" alt="" width="9" height="1" /></td>
	</tr>
</table>
</body>
</html>
<?php

}

function page_common_form($hidden, $submit)
{

?>
	<tr><td class="cat" align="center" colspan="2"><?php echo $hidden; ?><input class="mainoption" type="submit" value="<?php echo $submit; ?>" /></td></tr>
<?php

}

function page_upgrade_form()
{
	global $lang;

?>
	<tr><td class="cat" align="center" colspan="2"><?php echo $lang['continue_upgrade']; ?></td></tr>
	<tr><td class="cat" align="center" colspan="2"><input type="submit" name="upgrade_now" value="<?php echo $lang['upgrade_submit']; ?>" /></td></tr>
<?php

}

function page_error($error_title, $error)
{

?>
	<tr><th><?php echo $error_title; ?></th></tr>
	<tr><td class="row1" align="center"><span class="gen"><?php echo $error; ?></span></td></tr>
<?php

}

function setup_form($error, $lang_select, $dbms_select, $upgrade_option, $dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix, $board_email, $server_name, $server_port, $script_path, $admin_name, $admin_pass1, $admin_pass2, $language, $hidden_fields)
{
	global $lang;
?>
<tr>
	<td width="100%" colspan="3" style="padding-left: 10px; padding-right: 10px;">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header" colspan="3"><span><?php echo($lang['Initial_config']); ?></span></td></tr>

	<tr><th colspan="3"><?php echo($lang['Admin_config']); ?></th></tr>
<?php
	if ($error)
	{
?>
	<tr><td class="row1" colspan="3" align="center"><span class="gen" style="color:red"><?php echo($error); ?></span></td></tr>
<?php
	}
?>
	<tr><td class="row1 row-center" rowspan="9" width="90"><img src="../images/cms/cms_server_install.png" alt="<?php echo($lang['Initial_config']); ?>" title="<?php echo($lang['Initial_config']); ?>" /></td></tr>
	<!--
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Default_lang']); ?>: </span></td>
		<td class="row2"><?php echo($lang_select); ?></td>
	</tr>
	-->
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Install_Method']); ?>:</span></td>
		<td class="row2"><?php echo($upgrade_option); ?></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Admin_Username']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="admin_name" value="<?php echo(($admin_name != '') ? $admin_name : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Admin_Password']); ?>: </span></td>
		<td class="row2"><input type="password" class="post" name="admin_pass1" value="<?php echo(($admin_pass1 != '') ? $admin_pass1 : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Admin_Password_confirm']); ?>: </span></td>
		<td class="row2"><input type="password" class="post" name="admin_pass2" value="<?php echo(($admin_pass2 != '') ? $admin_pass2 : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Admin_email']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="board_email" value="<?php echo(($board_email != '') ? $board_email : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Server_name']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="server_name" value="<?php echo($server_name); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Server_port']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="server_port" value="<?php echo($server_port); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Script_path']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="script_path" value="<?php echo($script_path); ?>" /></td>
	</tr>

	<tr><th colspan="3"><?php echo($lang['DB_config']); ?></th></tr>
	<tr><td class="row1 row-center" rowspan="7" width="90"><img src="../images/cms/db_status.png" alt="<?php echo($lang['DB_config']); ?>" title="<?php echo($lang['DB_config']); ?>" /></td></tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['dbms']); ?>: </span></td>
		<td class="row2"><?php echo($dbms_select); ?></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['DB_Host']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="dbhost" value="<?php echo(($dbhost != '') ? $dbhost : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['DB_Name']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="dbname" value="<?php echo(($dbname != '') ? $dbname : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['DB_Username']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="dbuser" value="<?php echo(($dbuser != '') ? $dbuser : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['DB_Password']); ?>: </span></td>
		<td class="row2"><input type="password" class="post" name="dbpasswd" value="<?php echo(($dbpasswd != '') ? $dbpasswd : ''); ?>" /></td>
	</tr>
	<tr>
		<td class="row1" align="right"><span class="gen"><?php echo($lang['Table_Prefix']); ?>: </span></td>
		<td class="row2"><input type="text" class="post" name="prefix" value="<?php echo((!empty($table_prefix)) ? $table_prefix : 'ip_'); ?>" /></td>
	</tr>

	<tr>
		<td class="cat" colspan="3" align="center">
			<?php echo($hidden_fields); ?>
			<input type="hidden" name="install_step" value="2" />
			<input type="hidden" name="cur_lang" value="<?php echo($language); ?>" />
			<input class="mainoption" type="submit" value="<?php echo($lang['Continue_Install']); ?>" />
		</td>
	</tr>

		</table>
	</td>
</tr>
<?php
}

function finish_install($hidden_fields)
{
	global $lang;
?>
<tr>
	<td width="100%" colspan="3" style="padding-left: 10px; padding-right: 10px;">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header" colspan="2"><span><?php echo($lang['Finish_Install']); ?></span></td></tr>

	<tr><th colspan="2"><?php echo($lang['Finish_Install']); ?></th></tr>
	<tr><td class="row1 row-center" rowspan="2" width="90"><img src="../images/cms/cms_setup.png" alt="<?php echo($lang['Finish_Install']); ?>" title="<?php echo($lang['Finish_Install']); ?>" /></td></tr>
	<tr><td class="row1" align="left"><span class="gen"><?php echo($lang['Inst_Step_2']); ?></span></td></tr>
	<tr><td class="cat" colspan="2" align="center"><?php $hidden_fields; ?><input class="mainoption" type="submit" value="<?php echo($lang['Finish_Install']); ?>" /></td></tr>

		</table>
	</td>
</tr>
<?php
}

function apply_chmod()
{
	global $phpEx, $lang, $language;

	$chmod_777 = array();
	$chmod_777[] = '../backup';
	$chmod_777[] = '../cache';
	$chmod_777[] = '../ctracker/logfiles/logfile_attempt_counter.txt';
	$chmod_777[] = '../ctracker/logfiles/logfile_blocklist.txt';
	$chmod_777[] = '../ctracker/logfiles/logfile_debug_mode.txt';
	$chmod_777[] = '../ctracker/logfiles/logfile_malformed_logins.txt';
	$chmod_777[] = '../ctracker/logfiles/logfile_spammer.txt';
	$chmod_777[] = '../ctracker/logfiles/logfile_worms.txt';
	$chmod_777[] = '../downloads';
	$chmod_777[] = '../files';
	$chmod_777[] = '../files/album';
	$chmod_777[] = '../files/album/cache';
	$chmod_777[] = '../files/album/jupload';
	$chmod_777[] = '../files/album/med_cache';
	$chmod_777[] = '../files/album/wm_cache';
	$chmod_777[] = '../files/posted_images';
	$chmod_777[] = '../files/thumbs';
	$chmod_777[] = '../images/avatars';
	$chmod_777[] = '../logs';
	$chmod_777[] = '../pafiledb/uploads';
	$chmod_777[] = '../pafiledb/cache';
	$chmod_777[] = '../pafiledb/cache/templates';
	//$chmod_777[] = '../pafiledb/cache/templates/ca_aphrodite';
	$chmod_777[] = '../pafiledb/cache/templates/mg_themes';
	$chmod_777[] = '../pafiledb/images/screenshots';

	$chmod_666 = array();
	$chmod_666[] = '../includes/def_themes.php';
	$chmod_666[] = '../includes/def_tree.php';
	$chmod_666[] = '../includes/def_words.php';
	//$chmod_666[] = '../language/lang_english/lang_extend.php';

	$chmod_errors = false;
	$file_exists_errors = false;
	$read_only_errors = false;
	$report_string_append = '';

	$img_ok = '<img src="../images/cms/b_ok.png" alt="' . $lang['CHMOD_OK'] . '" title="' . $lang['CHMOD_OK'] . '" />';
	$img_error = '<img src="../images/cms/b_cancel.png" alt="' . $lang['CHMOD_Error'] . '" title="' . $lang['CHMOD_Error'] . '" />';

	$table_header_01 = '<tr><th colspan="3"><span class="gen"><b>' . $lang['CHMOD_777'] . '</b></span></th></tr>';
	$table_content_01 = '';
	$table_content_01 .= '<tr><td class="row1 row-center" rowspan="' . (count($chmod_777) + 1) . '" width="90"><img src="../images/cms/cms_folder_blue.png" alt="' . $lang['CHMOD_777'] . '" title="' . $lang['CHMOD_777'] . '" /></td></tr>';
	for ( $i = 0; $i < count($chmod_777); $i++ )
	{
		if (!@chmod($chmod_777[$i], 0777))
		{
			if (!file_exists($chmod_777[$i]))
			{
				$file_exists_errors = true;
				$report_string_append = '&nbsp;<span class="genmed" style="color:#DD3333;"><b>' . $lang['CHMOD_File_NotExists'] . '</b></span>';
			}
			else
			{
				if (!is_writable($chmod_777[$i]))
				{
					$read_only_errors = true;
					$report_string_append = '&nbsp;<span class="genmed" style="color:#DD3333;"><b>' . $lang['CHMOD_File_Exists_Read_Only'] . '</b></span>';
				}
				else
				{
					$report_string_append = '&nbsp;<span class="genmed" style="color:#DD3333;"><b>' . $lang['CHMOD_File_UnknownError'] . '</b></span>';
				}
			}
			//$table_content_01 .= '<tr><td class="row1" width="40%"><span class="gen"><b>' . $chmod_777[$i] . '</b></span></td><td class="row2"><span style="color:#DD3333;"><b>' . $lang['CHMOD_Error'] . '</b></span></td></tr>';
			$table_content_01 .= '<tr><td class="row1" width="40%"><span class="gen" style="color:#DD3333;"><b>' . $chmod_777[$i] . '</b></span></td><td class="row2">' . $img_error . $report_string_append . '</td></tr>';
			$chmod_errors = true;
		}
		else
		{
			$report_string_append = '&nbsp;<span class="genmed" style="color:#228844;">' . $lang['CHMOD_File_Exists'] . '</span>';
			//$table_content_01 .= '<tr><td class="row1" width="40%"><span class="gen"><b>' . $chmod_777[$i] . '</b></span></td><td class="row2"><span style="color:#228844;"><b>' . $lang['CHMOD_OK'] . '</b></span></td></tr>';
			$table_content_01 .= '<tr><td class="row1" width="40%"><span class="gen" style="color:#228844;"><b>' . $chmod_777[$i] . '</b></span></td><td class="row2">' . $img_ok . $report_string_append . '</td></tr>';
		}
	}

	$table_header_02 = '<tr><th colspan="3"><span class="gen"><b>' . $lang['CHMOD_666'] . '</b></span></th></tr>';
	$table_content_02 = '';
	$table_content_02 .= '<tr><td class="row1 row-center" rowspan="' . (count($chmod_666) + 1) . '" width="90"><img src="../images/cms/cms_folder_red.png" alt="' . $lang['CHMOD_666'] . '" title="' . $lang['CHMOD_666'] . '" /></td></tr>';
	for ( $i = 0; $i < count($chmod_666); $i++ )
	{
		if (!@chmod($chmod_666[$i], 0666))
		{
			if (!file_exists($chmod_666[$i]))
			{
				$file_exists_errors = true;
				$report_string_append = '&nbsp;<span class="genmed" style="color:#DD3333;"><b>' . $lang['CHMOD_File_NotExists'] . '</b></span>';
			}
			else
			{
				if (!is_writable($chmod_666[$i]))
				{
					$read_only_errors = true;
					$report_string_append = '&nbsp;<span class="genmed" style="color:#DD3333;"><b>' . $lang['CHMOD_File_Exists_Read_Only'] . '</b></span>';
				}
				else
				{
					$report_string_append = '&nbsp;<span class="genmed" style="color:#DD3333;"><b>' . $lang['CHMOD_File_UnknownError'] . '</b></span>';
				}
			}
			$table_content_02 .= '<tr><td class="row1"><span class="gen" style="color:#DD3333;"><b>' . $chmod_666[$i] . '</b></span></td><td class="row2">' . $img_error . $report_string_append . '</td></tr>';
			$chmod_errors = true;
		}
		else
		{
			$report_string_append = '&nbsp;<span class="genmed" style="color:#228844;">' . $lang['CHMOD_File_Exists'] . '</span>';
			$table_content_02 .= '<tr><td class="row1"><span class="gen" style="color:#228844;"><b>' . $chmod_666[$i] . '</b></span></td><td class="row2">' . $img_ok . $report_string_append . '</td></tr>';
		}
	}


	$table_start = '<tr><td width="100%" colspan="3" style="padding-left: 10px; padding-right: 10px;"><form action="install.' . $phpEx . '" name="lang" method="post"><table class="forumline" width="100%" cellspacing="0" cellpadding="0"><tr><td class="row-header" colspan="3"><span>' . $lang['CHMOD_Files'] . '</span></td></tr>';

	$s_hidden_fields = '<input type="hidden" name="install_step" value="1" />';
	$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';

	$table_footer = '<tr><th colspan="3"><span class="gen"><b>&nbsp;</b></span></th></tr>';
	$table_end = '</table></form></td></tr>';

	if ($chmod_errors == true)
	{
		$table_footer .= '<tr><td class="row2 row-center" colspan="3"><span style="color:#DD3333;"><b>' . $lang['CHMOD_Files_Explain_Error'] . '</b></span></td></tr>';
		$table_footer .= '<tr><td class="cat" colspan="3" align="center">' . $s_hidden_fields . '<input class="mainoption" type="submit" value="' . $lang['Start_Install_Anyway'] .'" /></td></tr>';
	}
	else
	{
		$table_footer .= '<tr><td class="row2 row-center" colspan="3"><span style="color:#228844;"><b>' . $lang['CHMOD_Files_Explain_Ok'] . '</b></span></td></tr>';
		$table_footer .= '<tr><td class="cat" colspan="3" align="center">' . $s_hidden_fields . '<input class="mainoption" type="submit" value="' . $lang['Start_Install'] .'" /></td></tr>';
	}

	$lang_select = build_lang_select($language);
	$table_lang_select = '<tr><td width="100%" colspan="3" style="padding-left: 10px; padding-right: 10px;"><form action="install.' . $phpEx . '" name="install" method="post"><table class="forumline" width="100%" cellspacing="0" cellpadding="0"><tr><td class="row-header" colspan="2"><span>' . $lang['Default_lang'] . '</span></td></tr>';
	$table_lang_select .= '<tr><td class="row1" align="right"><span class="gen">' . $lang['Default_lang'] .':</span></td><td class="row2">' . $lang_select . '</td></tr>';
	$table_lang_select .= '</table></form></td></tr>';

	echo $table_lang_select . $table_start . $table_title . $table_header_01 . $table_content_01 . $table_header_02 . $table_content_02 . $table_footer . $table_end;

	return $chmod_errors;
}

function build_lang_select($language = 'english')
{
	global $phpbb_root_path;
	$language = ($language == '') ? 'english' : $language;
	$dirname = $phpbb_root_path . 'language';
	$dir = opendir($dirname);

	$lang_options = array();
	while ($file = readdir($dir))
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($dirname . '/' . $file)) && !is_link(@phpbb_realpath($dirname . '/' . $file)))
		{
			$filename = trim(str_replace('lang_', '', $file));
			$displayname = preg_replace('/^(.*?)_(.*)$/', '\1 [ \2 ]', $filename);
			$displayname = preg_replace('/\[(.*?)_(.*)\]/', '[ \1 - \2 ]', $displayname);
			$lang_options[$displayname] = $filename;
		}
	}

	closedir($dir);

	@asort($lang_options);
	@reset($lang_options);

	$lang_select = '<select name="lang" onchange="this.form.submit()">';
	while (list($displayname, $filename) = @each($lang_options))
	{
		$selected = ($language == $filename) ? ' selected="selected"' : '';
		$lang_select .= '<option value="' . $filename . '"' . $selected . '>' . ucwords($displayname) . '</option>';
	}
	$lang_select .= '</select>';
	return $lang_select;
}


// Guess an initial language ... borrowed from phpBB 2.2 it's not perfect,
// really it should do a straight match first pass and then try a "fuzzy"
// match on a second pass instead of a straight "fuzzy" match.
function guess_lang()
{
	global $phpbb_root_path;

	// The order here _is_ important, at least for major_minor matches.
	// Don't go moving these around without checking with me first - psoTFX
	$match_lang = array(
		'arabic'					=> 'ar([_-][a-z]+)?',
		'bulgarian'					=> 'bg',
		'catalan'					=> 'ca',
		'czech'						=> 'cs',
		'danish'					=> 'da',
		'german'					=> 'de([_-][a-z]+)?',
		'english'					=> 'en([_-][a-z]+)?',
		'estonian'					=> 'et',
		'finnish'					=> 'fi',
		'french'					=> 'fr([_-][a-z]+)?',
		'greek'						=> 'el',
		'spanish_argentina'			=> 'es[_-]ar',
		'spanish'					=> 'es([_-][a-z]+)?',
		'gaelic'					=> 'gd',
		'galego'					=> 'gl',
		'gujarati'					=> 'gu',
		'hebrew'					=> 'he',
		'hindi'						=> 'hi',
		'croatian'					=> 'hr',
		'hungarian'					=> 'hu',
		'icelandic'					=> 'is',
		'indonesian'				=> 'id([_-][a-z]+)?',
		'italian'					=> 'it([_-][a-z]+)?',
		'japanese'					=> 'ja([_-][a-z]+)?',
		'korean'					=> 'ko([_-][a-z]+)?',
		'latvian'					=> 'lv',
		'lithuanian'				=> 'lt',
		'macedonian'				=> 'mk',
		'dutch'						=> 'nl([_-][a-z]+)?',
		'norwegian'					=> 'no',
		'punjabi'					=> 'pa',
		'polish'					=> 'pl',
		'portuguese_brazil'			=> 'pt[_-]br',
		'portuguese'				=> 'pt([_-][a-z]+)?',
		'romanian'					=> 'ro([_-][a-z]+)?',
		'russian'					=> 'ru([_-][a-z]+)?',
		'slovenian'					=> 'sl([_-][a-z]+)?',
		'albanian'					=> 'sq',
		'serbian'					=> 'sr([_-][a-z]+)?',
		'slovak'					=> 'sv([_-][a-z]+)?',
		'swedish'					=> 'sv([_-][a-z]+)?',
		'thai'						=> 'th([_-][a-z]+)?',
		'turkish'					=> 'tr([_-][a-z]+)?',
		'ukranian'					=> 'uk([_-][a-z]+)?',
		'urdu'						=> 'ur',
		'viatnamese'				=> 'vi',
		'chinese_traditional_taiwan'=> 'zh[_-]tw',
		'chinese_simplified'		=> 'zh',
	);

	if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
	{
		$accept_lang_ary = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		for ($i = 0; $i < count($accept_lang_ary); $i++)
		{
			@reset($match_lang);
			while (list($lang, $match) = each($match_lang))
			{
				if (preg_match('#' . $match . '#i', trim($accept_lang_ary[$i])))
				{
					if (file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $lang)))
					{
						return $lang;
					}
				}
			}
		}
	}
	return 'english';
}

?>