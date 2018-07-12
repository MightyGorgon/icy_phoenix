<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// MANUAL CONFIG - BEGIN

// If you decomment this line, then you will be able to use this file without sessions handling in latest Icy Phoenix
//define('BYPASS_SESSION', true);

// Just in case these are needed...
/*
$config['server_name'] = 'http://www.icyphoenix.com';
$config['server_port'] = '';
$config['script_path'] = '';
*/

// MANUAL CONFIG - END

define('IN_INSTALL', true);
define('IN_ICYPHOENIX', true);
// Uncomment the following line to completely disable the ftp option...
//define('NO_FTP', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
//define('THIS_PATH', '_install/');
$this_path = dirname(__FILE__);
$path_dirs = explode(DIRECTORY_SEPARATOR, $this_path);
define('THIS_PATH', $path_dirs[sizeof($path_dirs) - 1]);
define('THIS_FILE', 'install.' . PHP_EXT);
//define('THIS_FILE', basename(__FILE__));
require('includes/functions_install.' . PHP_EXT);
require('schemas/versions.' . PHP_EXT);
$ip_functions = new ip_functions();
$ip_sql = new ip_sql();

if (!isset($_POST['install_step']))
{
	// Open config.php... if it exists
	if (@file_exists(@$ip_functions->ip_realpath(IP_ROOT_PATH . 'config.' . PHP_EXT)))
	{
		include(IP_ROOT_PATH . 'config.' . PHP_EXT);
	}

	// Check if Icy Phoenix or phpBB are already installed
	if (defined('IP_INSTALLED') || defined('PHPBB_INSTALLED'))
	{
		if (defined('BYPASS_SESSION'))
		{
			define('BASIC_COMMON', true);
			require('common.' . PHP_EXT);
			$table_prefix = ($table_prefix == '') ? 'phpbb_' : $table_prefix;
		}
		else
		{
			// phpBB only - BEGIN
			// No need to add an IF because these vars won't damage anything if we are in Icy Phoenix ;-)
			define('IN_PHPBB', true);
			$phpbb_root_path = IP_ROOT_PATH;
			$phpEx = PHP_EXT;
			// phpBB only - END
			include(IP_ROOT_PATH . 'common.' . PHP_EXT);

			// Start session management
			$user->session_begin(false);
			$auth->acl($user->data);
			$user->setup();
			// End session management

			if (!$user->data['session_logged_in'])
			{
				if (!defined('CMS_PAGE_LOGIN')) define('CMS_PAGE_LOGIN', 'login.' . PHP_EXT);
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=' . THIS_PATH . THIS_FILE));
				exit;
			}

			if (defined('IP_INSTALLED'))
			{
				$access_allowed = true;
				$founder_id = (defined('FOUNDER_ID') ? FOUNDER_ID : get_founder_id());
				if ($user->data['user_id'] == $founder_id)
				{
					$access_allowed = true;
				}
				if (!$access_allowed && defined('MAIN_ADMINS_ID'))
				{
					$allowed_admins = explode(',', MAIN_ADMINS_ID);
					if (in_array($user->data['user_id'], $allowed_admins))
					{
						$access_allowed = true;
					}
				}
				if (empty($access_allowed))
				{
					message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
				}
			}
			else
			{
				if ($user->data['user_level'] != ADMIN)
				{
					// We need to use $lang['Not_Authorized'] because the $lang['Not_Auth_View'] isn't available in standard phpBB
					message_die(GENERAL_MESSAGE, $lang['Not_Authorized']);
				}
			}
			@set_time_limit(0);
			$mem_limit = $ip_functions->check_mem_limit();
			@ini_set('memory_limit', $mem_limit);
			$language = $config['default_lang'];
			$lang_request = $ip_functions->request_var('lang', '');
			if (!empty($lang_request) && preg_match('#^[a-z_]+$#', $lang_request))
			{
				$language = strip_tags($lang_request);
			}
		}
		include('language/lang_' . $language . '/lang_install.' . PHP_EXT);
		$current_ip_version = $ip_sql->get_config_value('ip_version');
		// Check that IP is installed, otherwise you don't need this table
		if (!empty($current_ip_version) && !defined('CMS_LAYOUT_TABLE'))
		{
			define('CMS_LAYOUT_TABLE', $table_prefix . 'cms_layout');
		}
		if (!empty($current_ip_version) && !defined('ALBUM_TABLE'))
		{
			define('ALBUM_TABLE', $table_prefix . 'album');
		}
		$current_phpbb_version = $ip_sql->get_config_value('version');
		$page_framework = new ip_page();

		include('includes/ip_tools.' . PHP_EXT);
		exit;
		/*
		if (defined('IP_INSTALLED'))
		{
			include('includes/ip_tools.' . PHP_EXT);
			exit;
		}
		else
		{
			$page_framework = new ip_page();
			$page_framework->page_header('Icy Phoenix', '', false, false);
			$page_framework->stats_box($current_ip_version, $current_phpbb_version);
			$page_framework->box_upgrade_info();
			$page_framework->page_footer(false);
			exit;
		}
		*/
	}
}

// If Icy Phoenix not yet installed, then start the install wizard

define('INSTALLING_ICYPHOENIX', true);
require('common.' . PHP_EXT);
include('language/lang_' . $language . '/lang_install.' . PHP_EXT);

$page_framework = new ip_page();

if ($install_step == 0)
{
	$page_framework->page_header($lang['Welcome_install'], $lang['Inst_Step_0'], false, false);
	$page_framework->apply_chmod(true);
	$page_framework->page_footer(false);
	exit;
}

if (!empty($_POST['send_file']) && ($_POST['send_file'] == 1))
{
	header('Content-Type: text/x-delimtext; name="config.' . PHP_EXT . '"');
	header('Content-disposition: attachment; filename="config.' . PHP_EXT . '"');
	// We need to stripslashes no matter what the setting of magic_quotes_gpc is
	// because we add slashes at the top if its off, and they are added automatically if it is on.
	echo stripslashes($_POST['config_data']);
	exit;
}
elseif (!empty($_POST['send_file']) && ($_POST['send_file'] == 2))
{
	$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars(stripslashes($_POST['config_data'])) . '" />';
	$s_hidden_fields .= '<input type="hidden" name="ftp_file" value="1" />';
	$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language . '" />';

	$page_framework->page_header($lang['Welcome_install'], $lang['ftp_instructs']);
	$page_framework->ftp($s_hidden_fields);
	$page_framework->page_footer();
	exit;
}
elseif (!empty($_POST['ftp_file']))
{
	// Try to connect ...
	$conn_id = @ftp_connect('localhost');
	$login_result = @ftp_login($conn_id, $ftp_user, $ftp_pass);

	if (!$conn_id || !$login_result)
	{
		$page_framework->page_header($lang['Welcome_install'], $lang['NoFTP_config']);
		// Error couldn't get connected... Go back to option to send file...
		$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars(stripslashes($_POST['config_data'])) . '" />';
		$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" />';
		$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language . '" />';
		$page_framework->common_form($s_hidden_fields, $lang['Download_config']);
		$page_framework->page_footer();
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
		$res = ftp_put($conn_id, 'config.' . PHP_EXT, $tmpfname, FTP_ASCII);
		@ftp_quit($conn_id);
		unlink($tmpfname);

		// Ok we are basically done with the install process let's go on
		// and let the user configure their board now. We are going to do
		// this by calling the admin_board.php from the normal board admin section.
		$s_hidden_fields = '<input type="hidden" name="username" value="' . $admin_name . '" />';
		$s_hidden_fields .= '<input type="hidden" name="password" value="' . $admin_pass1 . '" />';
		$s_hidden_fields .= '<input type="hidden" name="redirect" value="../adm/index.' . PHP_EXT . '" />';
		$s_hidden_fields .= '<input type="hidden" name="submit" value="' . $lang['Login'] . '" />';
		$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language . '" />';

		$page_framework->page_header($lang['Welcome_install'], $lang['Inst_Step_2']);
		$page_framework->finish_install($s_hidden_fields);
		$page_framework->page_footer();
		exit();
	}
}
elseif (($install_step == 1) || ($admin_pass1 != $admin_pass2) || empty($admin_pass1) || empty($dbhost))
{
	$instruction_text = $lang['Inst_Step_1'];
	if ($install_step > 1)
	{
		if ((($_POST['admin_pass1'] != $_POST['admin_pass2'])) || (empty($_POST['admin_pass1']) || empty($dbhost)) && $_POST['cur_lang'] == $language)
		{
			$error = $lang['Password_mismatch'];
		}
	}

	$lang_select = $page_framework->build_lang_select($language);

	$dbms_select = '<select name="dbms" onchange="if(this.form.upgrade.options[this.form.upgrade.selectedIndex].value == 1){ this.selectedIndex = 0;}">';
	while (list($dbms_name, $details) = @each($available_dbms))
	{
		$selected = ($dbms_name == $dbms) ? 'selected="selected"' : '';
		$dbms_select .= '<option value="' . $dbms_name . '">' . $details['LABEL'] . '</option>';
	}
	$dbms_select .= '</select>';

	$s_hidden_fields = '';
	$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language . '" />';
	//$s_hidden_fields .= '<input type="hidden" name="install_step" value="2" />';

	$upgrade_option = '';
	$page_framework->page_header($lang['Welcome_install'], $instruction_text);
	$page_framework->setup_form($error, $lang_select, $dbms_select, $upgrade_option, $dbhost, $dbname, $dbuser, $dbpasswd, $table_prefix, $board_email, $server_name, $server_port, $script_path, $admin_name, $admin_pass1, $admin_pass2, $language, $s_hidden_fields);
	$page_framework->page_footer();
	exit;
}
else
{
	// DB creation and population
	if (!isset($dbms) || !extension_loaded('mysqli'))
	{
		$page_framework->page_header($lang['Welcome_install'], $lang['Install'], '');
		$page_framework->error($lang['Installer_Error'], $lang['Install_No_Ext']);
		$page_framework->page_footer();
		exit;
	}

	if (!function_exists('utf8_clean_string'))
	{
		include(IP_ROOT_PATH . 'includes/utf/utf_tools.' . PHP_EXT);
	}
	include(IP_ROOT_PATH . 'includes/db.' . PHP_EXT);

	$dbms_schema = 'schemas/' . $available_dbms[$dbms]['SCHEMA'] . '_schema.sql';
	$dbms_basic = 'schemas/' . $available_dbms[$dbms]['SCHEMA'] . '_basic.sql';

	// Not used anymore
	//$remove_remarks = $available_dbms[$dbms]['COMMENTS'];

	$delimiter = $available_dbms[$dbms]['DELIM'];
	$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC'];

	if ($install_step == 2)
	{
		// Ok we have the db info go ahead and read in the relevant schema
		// and work on building the table.. probably ought to provide some
		// kind of feedback to the user as we are working here in order
		// to let them know we are actually doing something.
		$sql_query = @fread(@fopen($dbms_schema, 'r'), @filesize($dbms_schema));
		$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_query);

		$db->remove_remarks($sql_query);
		$sql_query = $db->split_sql_file($sql_query, $delimiter);

		for ($i = 0; $i < sizeof($sql_query); $i++)
		{
			if (trim($sql_query[$i]) != '')
			{
				$result = $db->sql_query($sql_query[$i]);
				if (!$result)
				{
					$error = $db->sql_error();
					$page_framework->page_header($lang['Welcome_install'], $lang['Install'], '');
					$page_framework->error($lang['Installer_Error'], $lang['Install_db_error'] . '<br />' . $error['message']);
					$page_framework->page_footer();
					exit;
				}
			}
		}

		// Ok tables have been built, let's fill in the basic information
		$sql_query = @fread(@fopen($dbms_basic, 'r'), @filesize($dbms_basic));
		$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_query);

		$db->remove_remarks($sql_query);
		$sql_query = $db->split_sql_file($sql_query, $delimiter_basic);

		for($i = 0; $i < sizeof($sql_query); $i++)
		{
			if (trim($sql_query[$i]) != '')
			{
				$result = $db->sql_query($sql_query[$i]);
				if (!$result)
				{
					$error = $db->sql_error();
					$page_framework->page_header($lang['Welcome_install'], $lang['Install'], '');
					$page_framework->error($lang['Installer_Error'], $lang['Install_db_error'] . '<br />' . $error['message']);
					$page_framework->page_footer();
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

		$tables_ary = array(
			'stats_config',
			'config',
			'config'
		);

		$cnames_ary = array(
			'install_date',
			'board_startdate',
			'default_lang'
		);

		$cvalues_ary = array(
			time(),
			time(),
			$db->sql_escape($language)
		);

		for ($i = 0; $i < sizeof($tables_ary); $i++)
		{
			$sql = "INSERT INTO " . $table_prefix . $tables_ary[$i] . " (config_name, config_value)
				VALUES ('" . $cnames_ary[$i] . "', '" . $cvalues_ary[$i] . "')";
			$result = $db->sql_query($sql);
			if (!$result)
			{
				$error .= "Could not insert data in config :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}
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
				SET config_value = '" . $config_value . "'
				WHERE config_name = '" . $config_name . "'";
			$result = $db->sql_query($sql);
			if (!$result)
			{
				$error .= "Could not update config table :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
			}
		}

		$admin_pass_md5 = ($confirm && $user->data['user_level'] == ADMIN) ? $admin_pass1 : md5($admin_pass1);

		if (!function_exists('phpbb_email_hash'))
		{
			require(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
		}

		$sql = "UPDATE " . $table_prefix . "users
			SET username = '" . $db->sql_escape($admin_name) . "', username_clean = '" . $db->sql_escape(utf8_clean_string($admin_name)) . "', user_password='" . $db->sql_escape($admin_pass_md5) . "', user_lang = '" . $db->sql_escape($language) . "', user_email='" . $db->sql_escape($board_email) . "', user_email_hash = '" . $db->sql_escape(phpbb_email_hash($board_email)) . "', user_pass_convert = '1'
			WHERE username = 'Admin'";
		$result = $db->sql_query($sql);
		if (!$result)
		{
			$error .= "Could not update admin info :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
		}

		$sql = "UPDATE " . $table_prefix . "users
			SET user_regdate = " . time();
		$result = $db->sql_query($sql);
		if (!$result)
		{
			$error .= "Could not update user registration date :: " . $sql . " :: " . __LINE__ . " :: " . __FILE__ . '<br /><br />';
		}

		if ($error != '')
		{
			$page_framework->page_header($lang['Welcome_install'], $lang['Install'], '');
			$page_framework->error($lang['Installer_Error'], $lang['Install_db_error'] . '<br /><br />' . $error);
			$page_framework->page_footer();
			exit;
		}

		// Write out the config file.
		$config_data = '<' . '?php' . "\n\n";
		$config_data .= '// Icy Phoenix auto-generated config file' . "\n";
		$config_data .= '// Do not change anything in this file!' . "\n\n";
		$config_data .= '$dbms = \'' . $dbms . '\';' . "\n\n";
		$config_data .= '$dbhost = \'' . $dbhost . '\';' . "\n";
		$config_data .= '$dbname = \'' . $dbname . '\';' . "\n";
		$config_data .= '$dbuser = \'' . $dbuser . '\';' . "\n";
		$config_data .= '$dbpasswd = \'' . $dbpasswd . '\';' . "\n\n";
		$config_data .= '$table_prefix = \'' . $table_prefix . '\';' . "\n\n";
		$config_data .= 'define(\'IP_INSTALLED\', true);' . "\n\n";
		$config_data .= '?' . '>';

		@umask(0111);
		$no_open = false;

		// Unable to open the file writeable do something here as an attempt to get around that...
		if (!($fp = @fopen(IP_ROOT_PATH . 'config.' . PHP_EXT, 'w')))
		{
			$s_hidden_fields = '<input type="hidden" name="config_data" value="' . htmlspecialchars($config_data) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';

			if (@extension_loaded('ftp') && !defined('NO_FTP'))
			{
				$page_framework->page_header($lang['Welcome_install'], $lang['Unwriteable_config'] . '<p>' . $lang['ftp_option'] . '</p>');
				$page_framework->ftp_type();
			}
			else
			{
				$page_framework->page_header($lang['Welcome_install'], $lang['Unwriteable_config']);
				$s_hidden_fields .= '<input type="hidden" name="send_file" value="1" />';
			}

			$s_hidden_fields .= '<input type="hidden" name="install_step" value="2" />';
			$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language . '" />';
			$page_framework->common_form($s_hidden_fields, $lang['Download_config']);
			$page_framework->table_end();
			$page_framework->page_footer();
			exit;
		}

		$result = @fwrite($fp, $config_data, strlen($config_data));
		@fclose($fp);

		// Ok we are basically done with the install process let's go on
		// and let the user configure their board now. We are going to do
		// this by calling the admin_board.php from the normal board admin section.
		$s_hidden_fields = '<input type="hidden" name="username" value="' . $admin_name . '" />';
		$s_hidden_fields .= '<input type="hidden" name="password" value="' . $admin_pass1 . '" />';
		$s_hidden_fields .= '<input type="hidden" name="redirect" value="../adm/index.' . PHP_EXT . '" />';
		$s_hidden_fields .= '<input type="hidden" name="login" value="true" />';
		$s_hidden_fields .= '<input type="hidden" name="lang" value="' . $language. '" />';

		$page_framework->page_header($lang['Welcome_install'], '<center>' . $lang['BBC_IP_CREDITS'] . '</center>', '../login_ip.' . PHP_EXT);
		$page_framework->finish_install($s_hidden_fields);
		$page_framework->page_footer();
		exit;
	}
}

?>