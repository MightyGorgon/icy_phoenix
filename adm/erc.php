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
* Philipp Kordowich
*/

// CTracker_Ignore: File checked by human
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'config.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/constants.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_cron.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/functions_dbmtnc.' . PHP_EXT);
include(IP_ROOT_PATH . 'includes/db.' . PHP_EXT);

@set_time_limit(0);
$mem_limit = check_mem_limit();
@ini_set('memory_limit', $mem_limit);

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
if(!get_magic_quotes_gpc())
{
	if(is_array($_GET))
	{
		while(list($k, $v) = each($_GET))
		{
			if(is_array($_GET[$k]))
			{
				while(list($k2, $v2) = each($_GET[$k]))
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

	if(is_array($_POST))
	{
		while(list($k, $v) = each($_POST))
		{
			if(is_array($_POST[$k]))
			{
				while(list($k2, $v2) = each($_POST[$k]))
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

	if(is_array($_COOKIE))
	{
		while(list($k, $v) = each($_COOKIE))
		{
			if(is_array($_COOKIE[$k]))
			{
				while(list($k2, $v2) = each($_COOKIE[$k]))
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

$mode = (isset($_POST['mode'])) ? htmlspecialchars($_POST['mode']) : ((isset($_GET['mode'])) ? htmlspecialchars($_GET['mode']) : 'start');
$option = (isset($_POST['option'])) ? htmlspecialchars($_POST['option']) : '';

// Before doing anything else send config.php if requested
if ($mode == 'download')
{
	// Get and convert Variables
	$new_dbms = (isset($_GET['ndbms'])) ? $_GET['ndbms'] : '';
	$new_dbhost = (isset($_GET['ndbh'])) ? $_GET['ndbh'] : '';
	$new_dbname = (isset($_GET['ndbn'])) ? $_GET['ndbn'] : '';
	$new_dbuser = (isset($_GET['ndbu'])) ? $_GET['ndbu'] : '';
	$new_dbpasswd = (isset($_GET['ndbp'])) ? $_GET['ndbp'] : '';
	$new_table_prefix = (isset($_GET['ntp'])) ? $_GET['ntp'] : '';

	$var_array = array('new_dbms', 'new_dbhost', 'new_dbname', 'new_dbuser', 'new_dbpasswd', 'new_table_prefix');
	reset($var_array);
	while (list(, $var) = each ($var_array))
	{
		$$var = stripslashes($$var);
		$$var = str_replace("'", "\\'", str_replace("\\", "\\\\", $$var));
	}

	// Create the config.php
	$data = "<?php\n" .
		"\n" .
		"//\n" .
		"// Icy Phoenix auto-generated config file\n" .
		"// Do not change anything in this file!\n" .
		"//\n" .
		"\n" .
		"\$dbms = '$new_dbms';\n" .
		"\n" .
		"\$dbhost = '$new_dbhost';\n" .
		"\$dbname = '$new_dbname';\n" .
		"\$dbuser = '$new_dbuser';\n" .
		"\$dbpasswd = '$new_dbpasswd';\n" .
		"\n" .
		"\$table_prefix = '$new_table_prefix';\n" .
		"\n" .
		"define('IP_INSTALLED', true);\n" .
		"\n" .
		"?>";
	header('Content-type: text/plain');
	header("Content-Disposition: attachment; filename=config." . PHP_EXT);
	echo $data;
	exit;
}

// Load a language if one was selected
if (isset($_POST['lg']) || isset($_GET['lg']))
{
	$lg = (isset($_POST['lg'])) ? htmlspecialchars($_POST['lg']) : htmlspecialchars($_GET['lg']);
	if (file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $lg . '/lang_dbmtnc.' . PHP_EXT)))
	{
		include(IP_ROOT_PATH . 'language/lang_' . $lg . '/lang_dbmtnc.' . PHP_EXT);
		include(IP_ROOT_PATH . 'language/lang_' . $lg . '/lang_main.' . PHP_EXT);
		include(IP_ROOT_PATH . 'language/lang_' . $lg . '/lang_main_settings.' . PHP_EXT);
	}
	else
	{
		$lg = '';
	}
}
else
{
	$lg = '';
}

// If no language was selected, check for available languages
if ($lg == '')
{
	$dirname = 'language';
	$dir = opendir(IP_ROOT_PATH . $dirname);
	$lang_list = Array();
	while ($file = readdir($dir))
	{
		if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath(IP_ROOT_PATH . $dirname . '/' . $file)) && !is_link(@phpbb_realpath(IP_ROOT_PATH . $dirname . '/' . $file)) && is_file(@phpbb_realpath(IP_ROOT_PATH . $dirname . '/' . $file . '/lang_dbmtnc.' . PHP_EXT)))
		{
			$filename = trim(str_replace("lang_", "", $file));
			$lang_list[] = $filename;
		}
	}
	closedir($dir);
	if (count($lang_list) == 1)
	{
		$lg = $lang_list[0];
		include(IP_ROOT_PATH . 'language/lang_' . $lg . '/lang_dbmtnc.' . PHP_EXT);
		include(IP_ROOT_PATH . 'language/lang_' . $lg . '/lang_main.' . PHP_EXT);
		include(IP_ROOT_PATH . 'language/lang_' . $lg . '/lang_main_settings.' . PHP_EXT);
	}
	else // Try to load english language
	{
		if (file_exists(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_english/lang_dbmtnc.' . PHP_EXT)))
		{
			include(IP_ROOT_PATH . 'language/lang_english/lang_dbmtnc.' . PHP_EXT);
			include(IP_ROOT_PATH . 'language/lang_english/lang_main.' . PHP_EXT);
			include(IP_ROOT_PATH . 'language/lang_english/lang_main_settings.' . PHP_EXT);
			$mode = 'select_lang';
		}
		else
		{
			$lang['Forum_Home'] = 'Forum Home';
			$lang['ERC'] = 'Emergency Recovery Console';
			$lang['Submit_text'] = 'Send';
			$lang['Select_Language'] = 'Select a language';
			$lang['no_selectable_language'] = 'No selectable language exist';
			$mode = 'select_lang';
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<title><?php echo $lang['ERC']; ?></title>
	<link rel="stylesheet" href="../templates/common/acp.css" type="text/css" />
	<link rel="shortcut icon" href="../images/favicon.ico" />
	<!--[if lt IE 7]>
	<script type="text/javascript" src="../templates/common/js/pngfix.js"></script>
	<![endif]-->
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center">
	<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><img src="../images/logo_ip_small.png" alt="<?php echo $lang['Forum_Home']; ?>" vspace="1" /></td>
				<td align="center" width="100%" valign="middle"><span class="maintitle"><?php echo $lang['ERC']; ?></span><br />
					<?php echo ($option == '') ? '' : $lang[$option] ?></td>
			</tr>
		</table></td>
	</tr>
</table>

<br clear="all" />

<?php
switch($mode)
{
	case 'select_lang':
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><b><?php echo $lang['Select_Language']; ?>:</b></td>
				<td width="10">&nbsp;</td>
				<td><?php echo language_select('english', 'lg', 'dbmtnc'); ?>&nbsp;<input type="submit" value="<?php echo $lang['Submit_text']; ?>" /></td>
			</tr>
		</table></td>
	</tr>
</table>
</form>
<?php
		break;
	case 'start':
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td nowrap="nowrap"><b><?php echo $lang['Select_Option']; ?>:</b></td>
				<td width="10">&nbsp;</td>
				<td><input type="hidden" name="lg" value="<?php echo $lg ?>" />
					<input type="hidden" name="mode" value="datainput" />
					<select size="1" name="option">
					<option value="cls"><?php echo $lang['cls']; ?></option>
<?php
	if (check_mysql_version())
	{
?>
					<option value="rdb"><?php echo $lang['rdb']; ?></option>
<?php
	}
?>
					<option value="cct"><?php echo $lang['cct']; ?></option>
					<option value="ecf"><?php echo $lang['ecf']; ?></option>
					<option value="fdt"><?php echo $lang['fdt']; ?></option>
					<option value="rpd"><?php echo $lang['rpd']; ?></option>
					<option value="rcd"><?php echo $lang['rcd']; ?></option>
					<option value="rld"><?php echo $lang['rld']; ?></option>
					<option value="rtd"><?php echo $lang['rtd']; ?></option>
					<option value="dgc"><?php echo $lang['dgc']; ?></option>
					<option value="cbl"><?php echo $lang['cbl']; ?></option>
					<option value="raa"><?php echo $lang['raa']; ?></option>
					<option value="mua"><?php echo $lang['mua']; ?></option>
					<option value="rcp"><?php echo $lang['rcp']; ?></option>
				</select>&nbsp;<input type="submit" value="<?php echo $lang['Submit_text']; ?>" /></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td nowrap="nowrap"><b><?php echo $lang['Option_Help']; ?>:</td>
				<td>&nbsp;</td>
				<td><?php echo $lang['Option_Help_Text']; ?></td>
			</tr>
		</table></td>
	</tr>
</table>
</form>
<?php
		break;
	case 'datainput':
		if ($option != 'rcp')
		{
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="0" cellspacing="0" cellpadding="10">
<?php
			if ($option != 'rld' && $option != 'rtd')
			{
?>
	<tr>
		<td><b><?php echo $lang['Authenticate_methods']; ?>:</b></td>
	</tr>
	<tr>
		<td><?php echo $lang['Authenticate_methods_help_text']; ?></td>
	</tr>
<?php
			}
			else
			{
?>
	<tr>
		<td><b><?php echo $lang['Authenticate_user_only']; ?>:</b></td>
	</tr>
	<tr>
		<td><?php echo $lang['Authenticate_user_only_help_text']; ?></td>
	</tr>
<?php
			}
?>
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['Admin_Account']; ?></b></td>
<?php
			if ($option != 'rld' && $option != 'rtd')
			{
?>
					<td width="20">&nbsp;</td>
					<td><b><?php echo $lang['Database_Login']; ?></b></td>
<?php
			}
?>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td><input type="radio" name="auth_method" value="board" checked="checked" /></td>
								<td><?php echo $lang['Username']; ?>:</td>
								<td><input type="text" name="board_user" size="30" maxlength="25" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><?php echo $lang['Password']; ?>:</td>
								<td><input type="password" name="board_password" size="30" maxlength="100" /></td>
							</tr>
						</table>
					</td>
<?php
			if ($option != 'rld' && $option != 'rtd')
			{
?>
					<td>&nbsp;</td>
					<td>
						<table border="0" cellspacing="0" cellpadding="2">
							<tr>
								<td><input type="radio" name="auth_method" value="db" /></td>
								<td><?php echo $lang['Username']; ?>:</td>
								<td><input type="text" name="db_user" size="30" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><?php echo $lang['Password']; ?>:</td>
								<td><input type="password" name="db_password" size="30" /></td>
							</tr>
						</table>
					</td>
<?php
			}
?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
	</tr>
<?php
		}
		else
		{
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<table border="0" cellspacing="0" cellpadding="10">
<?php
		}
		switch ($option)
		{
			case 'cls': // Clear Sessions
?>
	<tr><td><?php echo $lang['cls_info']; ?></td></tr>
<?php
				break;
			case 'ecf': // Clear Cache
?>
	<tr><td><?php echo $lang['ecf_info']; ?></td></tr>
<?php
				break;
			case 'fdt': // Fix def_tree.php
?>
	<tr><td><?php echo $lang['fdt_info']; ?></td></tr>
<?php
				break;
			case 'rdb': // Repair Database
?>
	<tr><td><?php echo $lang['rdb_info']; ?></td></tr>
<?php
				break;
			case 'cct': // Check config table
?>
	<tr><td><?php echo $lang['cct_info']; ?></td></tr>
<?php
				break;
			case 'rpd': // Reset path data
				// Get path information
				$secure_cur = get_config_data('cookie_secure');
				if (!empty($_SERVER['SERVER_PROTOCOL']) || !empty($_ENV['SERVER_PROTOCOL']))
				{
					$protocol = (!empty($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : $_ENV['SERVER_PROTOCOL'];
					$secure_rec = (strtolower(substr($protocol, 0 , 5)) == 'https') ? '1' : '0';
				}
				else
				{
					$secure_rec = '0';
				}
				$domain_cur = get_config_data('server_name');
				if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
				{
					$domain_rec = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];
				}
				else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
				{
					$domain_rec = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
				}
				else
				{
					$domain_rec = '';
				}
				$port_cur = get_config_data('server_port');
				if (!empty($_SERVER['SERVER_PORT']) || !empty($_ENV['SERVER_PORT']))
				{
					$port_rec = (!empty($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : $_ENV['SERVER_PORT'];
				}
				else
				{
					$port_rec = '80';
				}
				$path_cur = get_config_data('script_path');
				$path_rec = str_replace('admin', '', dirname($_SERVER['PHP_SELF']));
?>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td>&nbsp;</td>
					<td colspan="2"><b><?php echo $lang['cur_setting']; ?></b></td>
					<td width="10">&nbsp;</td>
					<td colspan="2"><b><?php echo $lang['rec_setting']; ?></b></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['secure']; ?></b></td>
					<td><input type="radio" name="secure_select" value="0"<?php echo ($secure_cur == $secure_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo $lang[($secure_cur == '1') ? 'secure_yes' : 'secure_no' ]; ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="secure_select" value="1"<?php echo ($secure_cur != $secure_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="radio" name="secure" value="1"<?php echo ($secure_rec == '1') ? ' checked="checked"' : '' ?> /><?php echo $lang['secure_yes']; ?><input type="radio" name="secure" value="0"<?php echo ($secure_rec == '0') ? ' checked="checked"' : '' ?> /><?php echo $lang['secure_no']; ?></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['domain']; ?></b></td>
					<td><input type="radio" name="domain_select" value="0"<?php echo ($domain_cur == $domain_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo htmlspecialchars($domain_cur); ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="domain_select" value="1"<?php echo ($domain_cur != $domain_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="input" name="domain" value="<?php echo htmlspecialchars($domain_rec); ?>" maxlength="255" size="40" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['port']; ?></b></td>
					<td><input type="radio" name="port_select" value="0"<?php echo ($port_cur == $port_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo htmlspecialchars($port_cur); ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="port_select" value="1"<?php echo ($port_cur != $port_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="input" name="port" value="<?php echo htmlspecialchars($port_rec); ?>" maxlength="5" size="5" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['path']; ?></b></td>
					<td><input type="radio" name="path_select" value="0"<?php echo ($path_cur == $path_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><?php echo htmlspecialchars($path_cur); ?></td>
					<td>&nbsp;</td>
					<td><input type="radio" name="path_select" value="1"<?php echo ($path_cur != $path_rec) ? ' checked="checked"' : '' ?> /></td>
					<td><input type="input" name="path" value="<?php echo htmlspecialchars($path_rec); ?>" maxlength="255" size="40" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['rpd_info']; ?></td>
	</tr>
<?php
				break;
			case 'rcd': // Reset cookie data
				// Get cookie information
				$cookie_domain = get_config_data('cookie_domain');
				$cookie_name = get_config_data('cookie_name');
				$cookie_path = get_config_data('cookie_path');
?>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['Cookie_domain']; ?></b></td>
					<td><input type="input" name="cookie_domain" value="<?php echo htmlspecialchars($cookie_domain); ?>" maxlength="255" size="40" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['Cookie_name']; ?></b></td>
					<td><input type="input" name="cookie_name" value="<?php echo htmlspecialchars($cookie_name); ?>" maxlength="16" size="40" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['Cookie_path']; ?></b></td>
					<td><input type="input" name="cookie_path" value="<?php echo htmlspecialchars($cookie_path); ?>" maxlength="255" size="40" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['rcd_info']; ?></td>
	</tr>
<?php
				break;
			case 'rld': // Reset language data
?>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['select_language']; ?>:</b></td>
					<td><?php echo language_select('english', 'new_lang'); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['rld_info']; ?></td>
	</tr>
<?php
				break;
			case 'rtd': // Reset template data
				$sql = "SELECT count(*) AS themes_count
					FROM " . THEMES_TABLE;
				if (!($result = $db->sql_query($sql)))
				{
					erc_throw_error("Couldn't count records of themes table!", __LINE__, __FILE__, $sql);
				}
				if ($row = $db->sql_fetchrow($result))
				{
					$themes_count = $row['themes_count'];
				}
				else
				{
					$themes_count = 0;
				}
				$db->sql_freeresult($result);
?>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="0">
<?php
				if ($themes_count != 0)
				{
?>
				<tr>
					<td><input type="radio" name="method" value="select_theme" checked="checked" /></td>
					<td><?php echo $lang['select_theme']; ?></td>
					<td><?php echo style_select('', 'new_style'); ?></td>
				</tr>
<?php
				}
?>
				<tr>
					<td><input type="radio" name="method" value="recreate_theme"<?php echo ($themes_count == 0) ? ' checked="checked"' : '' ?> /></td>
					<td colspan="2"><?php echo $lang['reset_thmeme']; ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo ($themes_count != 0) ? $lang['rtd_info'] : $lang['rtd_info_no_theme'] ; ?></td>
	</tr>
<?php
				break;
			case 'dgc': // disable GZip compression
?>
	<tr><td><?php echo $lang['dgc_info']; ?></td></tr>
<?php
				break;
			case 'cbl': // Clear ban list
?>
	<tr><td><?php echo $lang['cbl_info']; ?></td></tr>
<?php
				break;
			case 'raa': // Remove all administrators
?>
	<tr><td><?php echo $lang['raa_info']; ?></td></tr>
<?php
				break;
			case 'mua': // Grant user admin privileges
?>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['new_admin_user']; ?>:</b></td>
					<td><input type="input" name="username" maxlength="30" size="25" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><?php echo $lang['mua_info']; ?></td>
	</tr>
<?php
				break;
			case 'rcp': // Recreate config.php
				$available_dbms = array(
					'mysql'=> array(
					'LABEL'			=> 'MySQL 3.x'
					),
					'mysql4' => array(
					'LABEL'			=> 'MySQL 4.x or greater'
					)
				);
				$dbms_select = '<select name="new_dbms">';
				while (list($dbms_name, $details) = @each($available_dbms))
				{
					$dbms_select .= '<option value="' . $dbms_name . '">' . $details['LABEL'] . '</option>';
				}
				$dbms_select .= '</select>';

?>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="0">
				<tr>
					<td><b><?php echo $lang['dbms']; ?>:</b></td>
					<td><?php echo $dbms_select; ?></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Host']; ?>:</b></td>
					<td><input type="input" name="new_dbhost" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Name']; ?>:</b></td>
					<td><input type="input" name="new_dbname" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Username']; ?>:</b></td>
					<td><input type="input" name="new_dbuser" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['DB_Password']; ?>:</b></td>
					<td><input type="password" name="new_dbpasswd" size="30" /></td>
				</tr>
				<tr>
					<td><b><?php echo $lang['Table_Prefix']; ?>:</b></td>
					<td><input type="input" name="new_table_prefix" size="30" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><?php echo $lang['rcp_info']; ?></td></tr>
<?php
				break;
			default:
?>
</table>
</form>
<p><b><?php echo $lang['dbmntc_Invalid_Option']; ?></b></p>
</body>
</html>
<?php
				die();
		}
?>
	<tr>
		<td>
			<input type="hidden" name="option" value="<?php echo $option; ?>" />
			<input type="hidden" name="mode" value="execute" />
			<input type="hidden" name="lg" value="<?php echo $lg ?>" />
			<input type="submit" value="<?php echo $lang['Submit_text']; ?>" />
			- <a href="<?php echo $_SERVER['PHP_SELF'] . '?lg=' . $lg; ?>"><?php echo $lang['Cancel']; ?></a>
		</td>
	</tr>
</table>
</form>
<?php
		break;
	case 'execute':
		switch ($option)
		{
			case 'cls': // Clear Sessions
				check_authorisation();

				$sql = "DELETE FROM " . SESSIONS_TABLE;
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't delete sessions table!", __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . AJAX_SHOUTBOX_SESSIONS_TABLE;
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't delete AJAX Shoubox sessions table!", __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . SEARCH_TABLE;
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't delete search result table!", __LINE__, __FILE__, $sql);
				}

				success_message($lang['cls_success']);
				break;
			case 'ecf': // Empty Cache
				$db->clear_cache();
				$db->clear_cache('', TOPICS_CACHE_FOLDER);
				empty_cache_folders();
				success_message($lang['ecf_success']);
				break;
			case 'fdt': // Fix def_tree.php
				$db->clear_cache();
				$db->clear_cache('', TOPICS_CACHE_FOLDER);
				empty_cache_folders();
				$res = '<' . '?' . 'php' . "\n\n" . '?' . '>';

				$fname = IP_ROOT_PATH . './includes/def_themes.' . PHP_EXT;
				@chmod($fname, 0666);
				$handle = @fopen($fname, 'w');
				@fwrite($handle, $res);
				@fclose($handle);

				$fname = IP_ROOT_PATH . './includes/def_tree.' . PHP_EXT;
				@chmod($fname, 0666);
				$handle = @fopen($fname, 'w');
				@fwrite($handle, $res);
				@fclose($handle);

				success_message($lang['fdt_success']);
				break;
			case 'rdb': // Clear Sessions
				check_authorisation();
				if (!check_mysql_version())
				{
?>
	<p><span class="text_red"><?php echo $lang['Old_MySQL_Version'] ?></span></p>
<?php
				}
				else
				{
?>
	<p><?php echo $lang['Repairing_tables'] ?>:</p>
	<ul>
<?php
					for($i = 0; $i < count($tables); $i++)
					{
						$tablename = $table_prefix . $tables[$i];
						$sql = "REPAIR TABLE $tablename";
						$result = $db->sql_query($sql);
						if (!$result)
						{
							throw_error("Couldn't repair table!", __LINE__, __FILE__, $sql);
						}
						if ($row = $db->sql_fetchrow($result))
						{
							if ($row['Msg_type'] == 'status')
							{
?>
		<li><?php echo "$tablename: " . $lang['Table_OK']?></li>
<?php
							}
							else //  We got an error
							{
								// Check whether the error results from HEAP-table type
								$sql2 = "SHOW TABLE STATUS LIKE '$tablename'";
								$result2 = $db->sql_query($sql2);
								$row2 = $db->sql_fetchrow($result2);
								if ($row2['Type'] == 'HEAP')
								{
									// Table is from HEAP-table type
?>
		<li><?php echo "$tablename: " . $lang['Table_HEAP_info']?></li>
<?php
								}
								else
								{
?>
		<li><?php echo "<b>$tablename:</b> " . htmlspecialchars($row['Msg_text'])?></li>
<?php
								}
								$db->sql_freeresult($result2);
							}
						}
						$db->sql_freeresult($result);
					}
?>
	</ul>
<?php
					success_message($lang['rdb_success']);
				}
				break;
			case 'cct': // Check config table
				check_authorisation();

				// Update config data to match current configuration
				if (!empty($_SERVER['SERVER_PROTOCOL']) || !empty($_ENV['SERVER_PROTOCOL']))
				{
					$protocol = (!empty($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : $_ENV['SERVER_PROTOCOL'];
					if (strtolower(substr($protocol, 0 , 5)) == 'https')
					{
						$default_config['cookie_secure'] = '1';
					}
				}
				if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
				{
					$default_config['server_name'] = (!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME'];
				}
				else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
				{
					$default_config['server_name'] = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST'];
				}
				if (!empty($_SERVER['SERVER_PORT']) || !empty($_ENV['SERVER_PORT']))
				{
					$default_config['server_port'] = (!empty($_SERVER['SERVER_PORT'])) ? $_SERVER['SERVER_PORT'] : $_ENV['SERVER_PORT'];
				}
				$default_config['script_path'] = str_replace('admin', '', dirname($_SERVER['PHP_SELF']));
				$sql = "SELECT Min(topic_time) as startdate FROM " . TOPICS_TABLE;
				if ($result = $db->sql_query($sql))
				{
					if (($row = $db->sql_fetchrow($result)) && $row['startdate'] > 0)
					{
						$default_config['board_startdate'] = $row['startdate'];
					}
				}

				// Start the job
?>
	<p><?php echo $lang['Restoring_config'] . ':'; ?></p>
	<ul>
<?php
				reset($default_config);
				while (list($key, $value) = each($default_config))
				{
					$sql = 'SELECT config_value FROM ' . CONFIG_TABLE . "
						WHERE config_name = '$key'";
					$result = $db->sql_query($sql);
					if (!$result)
					{
						erc_throw_error("Couldn't query config table!", __LINE__, __FILE__, $sql);
					}
					if (!($row = $db->sql_fetchrow($result)))
					{
						echo("<li><b>$key:</b> $value</li>\n");
						$sql = "INSERT INTO " . CONFIG_TABLE . " (config_name, config_value)
							VALUES ('$key', '$value')";
						$result = $db->sql_query($sql);
						if (!$result)
						{
							erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
						}
					}
				}
?>
	</ul>
<?php
				success_message($lang['cct_success']);
				break;
			case 'rpd': // Reset path data
				check_authorisation();
				// Get variables
				$secure_select = (isset($_POST['secure_select'])) ? intval($_POST['secure_select']) : 1;
				$domain_select = (isset($_POST['domain_select'])) ? intval($_POST['domain_select']) : 1;
				$port_select = (isset($_POST['port_select'])) ? intval($_POST['port_select']) : 1;
				$path_select = (isset($_POST['path_select'])) ? intval($_POST['path_select']) : 1;
				$secure = (isset($_POST['secure'])) ? intval($_POST['secure']) : 0;
				$domain = (isset($_POST['domain'])) ? str_replace("\\'", "''", $_POST['domain']) : '';
				$port = (isset($_POST['port'])) ? str_replace("\\'", "''", $_POST['port']) : '';
				$path = (isset($_POST['path'])) ? str_replace("\\'", "''", $_POST['path']) : '';

				if ($secure_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$secure'
						WHERE config_name = 'cookie_secure'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				if ($domain_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$domain'
						WHERE config_name = 'server_name'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				if ($port_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$port'
						WHERE config_name = 'server_port'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				if ($path_select == 1)
				{
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$path'
						WHERE config_name = 'script_path'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				}
				success_message($lang['rpd_success']);
				break;
			case 'rcd': // Reset cookie data
				check_authorisation();
				// Get variables
				$cookie_domain = (isset($_POST['cookie_domain'])) ? str_replace("\\'", "''", $_POST['cookie_domain']) : '';
				$cookie_name = (isset($_POST['cookie_name'])) ? str_replace("\\'", "''", $_POST['cookie_name']) : '';
				$cookie_path = (isset($_POST['cookie_path'])) ? str_replace("\\'", "''", $_POST['cookie_path']) : '';

				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '$cookie_domain'
					WHERE config_name = 'cookie_domain'";
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '$cookie_name'
					WHERE config_name = 'cookie_name'";
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '$cookie_path'
					WHERE config_name = 'cookie_path'";
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
				}
				success_message($lang['rcd_success']);
				break;
			case 'rld': // Reset language data
				check_authorisation();
				$new_lang = (isset($_POST['new_lang'])) ? str_replace("\\'", "''", $_POST['new_lang']) : '';
				$board_user = isset($_POST['board_user']) ? trim(htmlspecialchars($_POST['board_user'])) : '';
				$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);
				$board_user = str_replace("'", "\\'", $board_user);

				if (is_file(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $new_lang . '/lang_main.' . PHP_EXT)) && is_file(@phpbb_realpath(IP_ROOT_PATH . 'language/lang_' . $new_lang . '/lang_admin.' . PHP_EXT)))
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_lang = '$new_lang'
						WHERE username = '$board_user'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update user table!", __LINE__, __FILE__, $sql);
					}
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$new_lang'
						WHERE config_name = 'default_lang'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
					success_message($lang['rld_success']);
				}
				else
				{
					success_message($lang['rld_failed']);
				}
				break;
			case 'rtd': // Reset template data
				check_authorisation();
				$method = (isset($_POST['method'])) ? htmlspecialchars($_POST['method']) : '';
				$new_style = (isset($_POST['new_style'])) ? intval($_POST['new_style']) : 0;
				$board_user = isset($_POST['board_user']) ? trim(htmlspecialchars($_POST['board_user'])) : '';
				$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);
				$board_user = str_replace("'", "\\'", $board_user);

				if ($method == 'recreate_theme')
				{
					$sql = "INSERT INTO " . THEMES_TABLE . "
						(template_name, style_name, head_stylesheet, body_background, td_class1, td_class2, td_class3) VALUES
						('icy_phoenix', 'Icy Phoenix', 'style_ice.css', 'ice', 'row1', 'row2', 'row3')";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update themes table!", __LINE__, __FILE__, $sql);
					}
					$method = 'select_theme';
					$new_style = $db->sql_nextid();
?>
	<p><?php echo $lang['rtd_restore_success'];?></p>
<?php
				}
				if ($method == 'select_theme')
				{
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_style = $new_style
						WHERE username = '$board_user'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update user table!", __LINE__, __FILE__, $sql);
					}
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '$new_style'
						WHERE config_name = 'default_style'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
					success_message($lang['rtd_success']);
				}
				break;
			case 'dgc': // Disable GZip compression
				check_authorisation();
					$sql = "UPDATE " . CONFIG_TABLE . "
						SET config_value = '0'
						WHERE config_name = 'gzip_compress'";
					$result = $db->sql_query($sql);
					if(!$result)
					{
						erc_throw_error("Couldn't update config table!", __LINE__, __FILE__, $sql);
					}
				success_message($lang['dgc_success']);
				break;
			case 'cbl': // Clear ban list
				check_authorisation();
				$sql = "DELETE FROM " . BANLIST_TABLE;
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't delete ban list table!", __LINE__, __FILE__, $sql);
				}
				$sql = "DELETE FROM " . DISALLOW_TABLE;
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't delete disallowed users table!", __LINE__, __FILE__, $sql);
				}
				$sql = "SELECT user_id FROM " . USERS_TABLE . "
					WHERE user_id = " . ANONYMOUS;
				$result = $db->sql_query($sql);
				if (!$result)
				{
					erc_throw_error("Couldn't get user information!", __LINE__, __FILE__, $sql);
				}
				if ($row = $db->sql_fetchrow($result)) // anonymous user exists
				{
					success_message($lang['cbl_success']);
				}
				else // anonymous user does not exist
				{
					// Recreate entry
					$sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active)
						VALUES (" . ANONYMOUS . ", 'Anonymous', 0, 0, '', '', '', '', '', '', '', '', 0, NULL, '', '', '', 0, 0, 1, 0, 1, 0, 1, 1, NULL, '', '', 0, '', '', '', 0, 0)";
					$result = $db->sql_query($sql);
					if (!$result)
					{
						throw_error("Couldn't add user data!", __LINE__, __FILE__, $sql);
					}
					success_message($lang['cbl_success_anonymous']);
				}
				$db->clear_cache('ban_', USERS_CACHE_FOLDER);
				break;
			case 'raa': // Remove all administrators
				check_authorisation();
				// Get userdata to check for current user
				$auth_method = (isset($_POST['auth_method'])) ? htmlspecialchars($_POST['auth_method']) : '';
				$board_user = isset($_POST['board_user']) ? trim(htmlspecialchars($_POST['board_user'])) : '';
				$board_user = substr(str_replace("\\'", "'", $board_user), 0, 25);

				$sql = "SELECT user_id, username
					FROM " . USERS_TABLE . "
					WHERE user_level IN (" . JUNIOR_ADMIN . ", " . ADMIN . ")";
				$result = $db->sql_query($sql);
				if (!$result)
				{
					erc_throw_error("Couldn't get user data!", __LINE__, __FILE__, $sql);
				}
?>
	<p><?php echo $lang['Removing_admins'] . ':'; ?></p>
	<ul>
<?php
				while ($row = $db->sql_fetchrow($result))
				{
					if ($auth_method != 'board' || $board_user != $row['username'])
					{
						// Checking whether user is a moderator
						if(check_mysql_version())
						{
							$sql2 = "SELECT ug.user_id
								FROM " . USER_GROUP_TABLE . " ug
									INNER JOIN " . AUTH_ACCESS_TABLE . " aa ON ug.group_id = aa.group_id
								WHERE ug.user_id = " . $row['user_id'] . " AND ug.user_pending <> 1 AND aa.auth_mod = 1";
						}
						else
						{
							$sql2 = "SELECT ug.user_id
								FROM " . USER_GROUP_TABLE . " ug, " .
									AUTH_ACCESS_TABLE . " aa
								WHERE ug.group_id = aa.group_id
									AND ug.user_id = " . $row['user_id'] . "
									AND ug.user_pending <> 1 AND aa.auth_mod = 1";
						}
						$result2 = $db->sql_query($sql2);
						if (!$result2)
						{
							erc_throw_error("Couldn't get moderator data!", __LINE__, __FILE__, $sql2);
						}
						$new_state = intval(($row2 = $db->sql_fetchrow($result2)) ? MOD : USER);
						$db->sql_freeresult($result2);
						$sql2 = "UPDATE " . USERS_TABLE . "
							SET user_level = $new_state
							WHERE user_id = " . $row['user_id'];
						$result2 = $db->sql_query($sql2);
						if (!$result2)
						{
							erc_throw_error("Couldn't update user data!", __LINE__, __FILE__, $sql2);
						}
?>
	<li><?php echo htmlspecialchars($row['username']) ?></li>
<?php
					}
				}
				$db->sql_freeresult($result);
?>
	</ul>
<?php
				success_message($lang['raa_success']);
				break;
			case 'mua': // Grant user admin privileges
				check_authorisation();
				$username = (isset($_POST['username'])) ? str_replace("\\'", "''", $_POST['username']) : '';

				$sql = "UPDATE " . USERS_TABLE . "
					SET user_active = 1, user_level = " . ADMIN . "
					WHERE username = '$username' AND user_id <> -1";
				$result = $db->sql_query($sql);
				if(!$result)
				{
					erc_throw_error("Couldn't update user table!", __LINE__, __FILE__, $sql);
				}
				$affected_rows = $db->sql_affectedrows();
				if ($affected_rows == 0)
				{
					success_message($lang['mua_failed']);
				}
				else
				{
					success_message($lang['mua_success']);
				}
				break;
			case 'rcp': // Recreate config.php
				// Get Variables
				$var_array = array('new_dbms', 'new_dbhost', 'new_dbname', 'new_dbuser', 'new_dbpasswd', 'new_table_prefix');
				reset($var_array);
				while (list(, $var) = each ($var_array))
				{
					$$var = (isset($_POST[$var])) ? stripslashes($_POST[$var]) : '';
				}

?>
	<p><b><?php echo $lang['New_config_php']; ?>:</b></p>
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="20">&nbsp;</td>
			<td>
				&lt;?php<br />
				<br />
				//<br />
				// Icy Phoenix auto-generated config file<br />
				// Do not change anything in this file!<br />
				//<br />
				<br />
				$dbms = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbms))); ?>';<br />
				<br />
				$dbhost = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbhost))); ?>';<br />
				$dbname = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbname))); ?>';<br />
				$dbuser = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbuser))); ?>';<br />
				$dbpasswd = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_dbpasswd))); ?>';<br />
				<br />
				$table_prefix = '<?php echo htmlspecialchars(str_replace("'", "\\'", str_replace("\\", "\\\\", $new_table_prefix))); ?>';<br />
				<br />
				define('IP_INSTALLED', true);<br />
				<br />
				?&gt;
			</td>
		</tr>
	</table>
<?php
				$ndbms = urlencode($new_dbms);
				$ndbh = urlencode($new_dbhost);
				$ndbn = urlencode($new_dbname);
				$ndbu = urlencode($new_dbuser);
				$ndbp = urlencode($new_dbpasswd);
				$ntp = urlencode($new_table_prefix);
				success_message(sprintf($lang['rcp_success'], '<a href="' . $_SERVER['PHP_SELF'] . '?mode=download&amp;ndbms=' . $ndbms . '&amp;ndbh=' . $ndbh . '&amp;ndbn=' . $ndbn . '&amp;ndbu=' . $ndbu . '&amp;ndbp=' . $ndbp . '&amp;ntp=' . $ntp . '">', '</a>'));
				break;
			default:
?>
<p><b>Invalid Option</b></p>
</body>
</html>
<?php
				die();
		}
		break;
	default:
?>
<p><b>Invalid Option</b></p>
</body>
</html>
<?php
		die();
}
?>

<br clear="all" />

</body>
</html>
