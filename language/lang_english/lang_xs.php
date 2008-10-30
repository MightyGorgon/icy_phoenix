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
* Vjacheslav Trushkin (http://www.stsoftware.biz)
* Lopalong
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'Extreme_Styles' => 'eXtreme Styles',
	'xs_title' => 'eXtreme Styles mod',

	'xs_file' => 'File',
	'xs_template' => 'Template',
	'xs_id' => 'ID',
	'xs_style' => 'Style',
	'xs_styles' => 'Styles',
	'xs_users' => 'Users',
	'xs_options' => 'Options',
	'xs_comment' => 'Comment',
	'xs_upload_time' => 'Upload Time',
	'xs_select' => 'Select',

	'xs_continue' => 'Continue',	// button

	'xs_click_here_lc' => 'click here',
	'xs_edit_lc' => 'edit',

/*
* navigation
*/
	'xs_config_shownav' => array(
		'Configuration',
		'Install Styles',
		'Uninstall Styles',
		'Default Style',
		'Manage Cache',
		'Import Styles',
		'Export Styles',
		'Clone Styles',
		'Download Styles',
		'Edit Templates',
		'Edit Styles',
		'Export Database',
		'Check Updates',
	),

/*
* frame_top.tpl
*/
	'xs_menu_lc' => 'extreme styles mod menu',
	'xs_support_forum_lc' => 'support forum',
	'xs_download_styles_lc' => 'download styles',
	'xs_install_styles_lc' => 'install styles',

/*
* index.tpl
*/

	'xs_main_comment1' => 'eXtreme Styles mod main menu. There are quite a few functions within this interface, so this page is here as a guide. There is a short explanation of every function below the function name.<br /><br />Note: This mod replaces the Icy Phoenix styles management. You will find the default Icy Phoenix functions in this list, but these functions are now optimized and have extra features.<br /><br />If you have any questions please visit <a href="http://www.stsoftware.biz/forum" target="_blank">support forum</a> where you can get assistance for this mod.',
	'xs_main_comment2' => 'The eXtreme Styles mod allows an admin to store entire styles in .style files. Styles are stored in a small compressed file and by doing so this saves the trouble of downloading/uploading many files. Style files are compressed so download/upload is much more efficient than downloading/uploading usual style files.',
	'xs_main_comment3' => 'All functions of Icy Phoenix styles management are replaced with eXtreme Styles mod.<br /><br /><a href="{URL}">Click here</a> to see menu.',
	'xs_main_title' => 'eXtreme Styles Navigation Menu',
	'xs_menu' => 'eXtreme Styles Menu',

	'xs_manage_styles' => 'Manage Styles',
	'xs_import_export_styles' => 'Import/Export Styles',
	'xs_install_uninstall_styles' => 'Install/Uninstall Styles',
	'xs_edit_templates' => 'Edit Templates',
	'xs_other_functions' => 'Other Functions',

	'xs_configuration' => 'Configuration',
	'xs_configuration_explain' => 'Change the eXtreme Styles configuration.',
	'xs_default_style' => 'Default Style',
	'xs_default_style_explain' => 'Change the default forum style and switch users from one style to another.',
	'xs_manage_cache' => 'Manage Cache',
	'xs_manage_cache_explain' => 'Manage cached files.',
	'xs_import_styles' => 'Import Styles',
	'xs_import_styles_explain' => 'Download and install .style files.',
	'xs_export_styles' => 'Export Styles',
	'xs_export_styles_explain' => 'Save a style from your forum as a .style file and then easily transfer it to another forum or another website.',
	'xs_clone_styles' => 'Clone Styles',
	'xs_clone_styles_explain' => 'Quickly clone styles or a whole template.',
	'xs_download_styles' => 'Download Styles',
	'xs_download_styles_explain' => 'Quickly download and install styles from websites. You can configure a list of websites yourself.',
	'xs_install_styles' => 'Install Styles',
	'xs_install_styles_explain' => 'Install styles that are already uploaded to your forum.',
	'xs_uninstall_styles' => 'Uninstall Styles',
	'xs_uninstall_styles_explain' => 'Remove styles from your forum.',
	'xs_edit_templates_explain' => 'Edit tpl files online.',
	'xs_edit_styles_data' => 'Edit Styles Data',
	'xs_edit_styles_data_explain' => 'Edit style variables. It is used by some styles, but most styles don\'t use it and use a css file instead.',
	'xs_export_styles_data' => 'Export Styles Data',
	'xs_export_styles_data_explain' => 'Save style variables to theme_info.cfg.',
	'xs_check_for_updates' => 'Check for Updates',
	'xs_check_for_updates_explain' => 'Check for updated versions of styles and mods installed on your forum.',

	'xs_set_configuration_lc' => 'set configuration',
	'xs_set_default_style_lc' => 'set default style',
	'xs_manage_cache_lc' => 'manage cache',
	'xs_import_styles_lc' => 'import styles',
	'xs_export_styles_lc' => 'export styles',
	'xs_clone_styles_lc' => 'clone styles',
	'xs_uninstall_styles_lc' => 'uninstall styles',
	'xs_edit_templates_lc' => 'edit templates',
	'xs_edit_styles_data_lc' => 'edit styles data',
	'xs_export_styles_data_lc' => 'export styles data',
	'xs_check_for_updates_lc' => 'check for updates',

/*
* ftp.tpl, ftp functions
*/

	'xs_ftp_comment1' => 'To use this feature you must select the file upload method. If you select FTP, then a password will not be stored and eXtreme Styles will ask you for a password every time you select functions that requires FTP access. If you select local file system then make sure all required directories are writable.',
	'xs_ftp_comment2' => 'To use this feature you must set FTP settings. A password will not be stored and eXtreme Styles will ask you for a password every time you select functions that requires FTP access.',
	'xs_ftp_comment3' => 'Warning: FTP functions are disabled on this server. You will not be able to use eXtreme Styles functions that require FTP access.',

	'xs_ftp_title' => 'FTP Configuration',

	'xs_ftp_explain' => 'FTP is used to upload new styles. If you want to use the import styles feature then you should configure FTP settings accordingly. eXtreme Styles tries to auto-detect settings if and when possible.',

	'xs_ftp_error_fatal' => 'FTP functions are disabled on this server. Cannot continue.',
	'xs_ftp_error_connect' => 'FTP error: cannot connect to {HOST}',
	'xs_ftp_error_login' => 'FTP error: cannot login',
	'xs_ftp_error_chdir' => 'FTP error: cannot change directory to {DIR}',
	'xs_ftp_error_nonphpbbdir' => 'FTP error: you have set an invalid directory. There are no Icy Phoenix files in that directory',
	'xs_ftp_error_noconnect' => 'Cannot connect to ftp server',
	'xs_ftp_error_login2' => 'Invalid ftp login or password',

	'xs_ftp_log_disabled' => 'ftp functions are disabled on this server. script cannot continue.',
	'xs_ftp_log_connecting' => 'connecting to {HOST}',
	'xs_ftp_log_noconnect' => 'cannot connect to {HOST}',
	'xs_ftp_log_connected' => 'connected. logging in...',
	'xs_ftp_log_nologin' => 'cannot login as {USER}',
	'xs_ftp_log_loggedin' => 'logged in',
	'xs_ftp_log_end' => 'finished executing script',
	'xs_ftp_log_nopwd' => 'error: cannot retrieve current directory',
	'xs_ftp_log_nomkdir' => 'error: cannot create directory {DIR}',
	'xs_ftp_log_mkdir' => 'created directory {DIR}',
	'xs_ftp_log_nochdir' => 'error: cannot change directory to {DIR}',
	'xs_ftp_log_normdir' => 'error: cannot remove directory {DIR}',
	'xs_ftp_log_rmdir' => 'removed directory {DIR}',
	'xs_ftp_log_chdir' => 'changed directory to {DIR}',
	'xs_ftp_log_noupload' => 'error: cannot upload file {FILE}',
	'xs_ftp_log_upload' => 'uploaded file {FILE}',
	'xs_ftp_log_nochmod' => 'warning: cannot chmod file {FILE}',
	'xs_ftp_log_chmod' => 'chmod file {FILE} to {MODE}',
	'xs_ftp_log_invalidcommand' => 'error: unknown command: {COMMAND}',
	'xs_ftp_log_chdir2' => 'changing current directory back to {DIR}',
	'xs_ftp_log_nochdir2' => 'cannot change directory to {DIR}',

	'xs_ftp_config' => 'FTP Configuration',
	'xs_ftp_select_method' => 'Select upload method',
	'xs_ftp_select_local' => 'Use local file system (no configuration required)',
	'xs_ftp_select_ftp' => 'Use FTP (set ftp settings below)',

	'xs_ftp_settings' => 'FTP Settings',
	'xs_ftp_host' => 'FTP Host',
	'xs_ftp_login' => 'FTP Login',
	'xs_ftp_path' => 'FTP Path to Icy Phoenix',
	'xs_ftp_pass' => 'FTP Password',
	'xs_ftp_remotedir' => 'Remote Directory',

	'xs_ftp_host_guess' => ' (probably "{HOST}" [<a href="javascript: void(0)" onclick="{CLICK}">set host</a>])',
	'xs_ftp_login_guess' => ' (probably "{LOGIN}" [<a href="javascript: void(0)" onclick="{CLICK}">set host</a>])',
	'xs_ftp_path_guess' => ' (probably "{PATH}" [<a href="javascript: void(0)" onclick="{CLICK}">set path</a>])',


/*
* config.tpl
*/
	'xs_config_updated' => 'Configuration updated.',
	'xs_config_updated_explain' => 'You need to refresh this page before the new configuration can take effect. <a href="{URL}">Click here</a> to refresh page.',
	'xs_config_warning' => 'Warning: cache cannot be written.',
	'xs_config_warning_explain' => 'Cache directory is not writable. eXtreme Styles can attempt to fix this problem.<br /><a href="{URL}">Click here</a> to try to change access mode to cache directory.<br /><br />If cache doesn\'t work on your server for some reason don\'t worry - eXtreme Styles<br />increases forum speed many times even without cache.',

	'xs_config_maintitle' => 'eXtreme Styles mod Configuration',
	'xs_config_subtitle' => 'The configuration for eXtreme Styles. If you don\'t understand what certain variables do then don\'t change it.',
	'xs_config_title' => 'eXtreme Styles mod v{VERSION} settings',
	'xs_config_cache' => 'Cache configuration',

	'xs_config_navbar' => 'Show on left frame:',
	'xs_config_navbar_explain' => 'You can select what items to show on left frame in the Admin Control Panel.',

	'xs_config_def_template' => 'Default template directory',
	'xs_config_def_template_explain' => 'If a required tpl file is not found in the current template directory (that might happen if you modded Icy Phoenix incorrectly) then the template system will look for the same file in a related directory (like if current template is "myTemplate" and script requires file "myTemplate/myfile.tpl" and that file isn\'t there the template system will look for that file as "default/myfile.tpl"). Set to empty to disable this feature.',

	'xs_config_check_switches' => 'Check switches while compiling',
	'xs_config_check_switches_explain' => 'This feature checks for errors in templates. Turning it off will speed up compilation, but the compiler may skip some errors in templates if it contains errors.<br /><br />Smart check will check templates for errors and automatically fix all known errors (there are few known typos in different mods). Works a little bit slower than simple check.<br /><br />But sometimes templates look proper only when error checking is disabled; this happens because of bad html coding - contact whoever wrote the tpl file if you want to fix errors.<br /><br />If the cache feature is disabled, then turn this off for faster compilation.',
	'xs_config_check_switches_0' => 'Off',
	'xs_config_check_switches_1' => 'Smart check',
	'xs_config_check_switches_2' => 'Simple check',

	'xs_config_show_errors' => 'Shows errors when files are incorrectly included in tpl files',
	'xs_config_show_error_explain' => 'This feature enables/disables errors in tpl files that the user has used incorrectly &lt;!-- INCLUDE filename --&gt;',

	'xs_config_tpl_comments' => 'Add tpl filenames in html',
	'xs_config_tpl_comments_explain' => 'This feature adds comments to html code that allow style designers to detect which tpl file is displayed.',

	'xs_config_use_cache' => 'Use cache',
	'xs_config_use_cache_explain' => 'Cache is saved to disk and it will accelerate the templates system because there would be no need to compile template every time it is shown.',

	'xs_config_auto_compile' => 'Automatically save cache',
	'xs_config_auto_compile_explain' => 'This will automatically compile templates that are not cached and save to the cache directory.',

	'xs_config_auto_recompile' => 'Automatically re-compile cache',
	'xs_config_auto_recompile_explain' => 'This will automatically re-compile templates if a template was changed.',

	'xs_config_php' => 'Extension of cache filenames',
	'xs_config_php_explain' => 'This is the extension of cached files. Files are stored in php format so default extension is "php". Do not include the dot',

	'xs_config_back' => '<a href="{URL}">Click here</a> to return to configuration.',
	'xs_config_sql_error' => 'Failed to update general configuration for {VAR}',

// Debug info
	'xs_debug_header' => 'Debug info',
	'xs_debug_explain' => 'This is debug info. Used to find/fix problems when configuring cache.',
	'xs_debug_vars' => 'Template variables',
	'xs_debug_tpl_name' => 'Template filename:',
	'xs_debug_cache_filename' => 'Cache filename:',
	'xs_debug_data' => 'Debug data:',

	'xs_check_hdr' => 'Checking cache for %s',
	'xs_check_filename' => 'Error: invalid filename',
	'xs_check_openfile1' => 'Error: cannot open file "%s". Will try to create directories...',
	'xs_check_openfile2' => 'Error: cannot open file "%s" for the second time. Giving up...',
	'xs_check_nodir' => 'Checking "%s" - no such directory.',
	'xs_check_nodir2' => 'Error: cannot create directory "%s" - you might need to check permissions.',
	'xs_check_createddir' => 'Created directory "%s"',
	'xs_check_dir' => 'Checking "%s" - directory exists.',
	'xs_check_ok' => 'Opened file "%s" for writing. Everything seems to be ok.',
	'xs_error_demo_edit' => 'you cannot edit file in demo mode',
	'xs_error_not_installed' => 'eXtreme Styles mod is not installed. You forgot to upload includes/template.php',

/*
* chmod
*/
	'xs_chmod' => 'CHMOD',
	'xs_chmod_return' => '<br /><br /><a href="{URL}">Click here</a> to return to configuration.',
	'xs_chmod_message1' => 'Configuration changed.',
	'xs_chmod_error1' => 'Cannot change access mode to cache directory',


/*
* default style
*/
	'xs_def_title' => 'Set Default Style',
	'xs_def_explain' => 'This feature allows you to quickly change default forum style and also switch users from one style to another.',

	'xs_styles_set_default' => 'set default',
	'xs_styles_no_override' => 'do not override user settings',
	'xs_styles_do_override' => 'override user settings',
	'xs_styles_switch_all' => 'switch all users to this style',
	'xs_styles_switch_all2' => 'switch all users to:',
	'xs_styles_defstyle' => 'default style',
	'xs_styles_available' => 'Available styles',
	'xs_styles_make_public' => 'make style public',
	'xs_styles_make_admin' => 'make style admin-only',
	'xs_styles_users' => 'Users List',


/*
* cache management
*/
	'xs_manage_cache_explain2' => 'This feature allows you to compile or remove cached files for styles.',
	'xs_clear_all_lc' => 'clear all',
	'xs_compile_all_lc' => 'compile all',
	'xs_clear_cache_lc' => 'clear cache',
	'xs_compile_cache_lc' => 'compile cache',
	'xs_cache_confirm' => 'If you have many styles it might cause a huge server load. Are you sure you want to continue?',

	'xs_cache_nowrite' => 'Error: cannot access cache directory',
	'xs_cache_log_deleted' => 'Deleted {FILE}',
	'xs_cache_log_nodelete' => 'Error: cannot delete file {FILE}',
	'xs_cache_log_nothing' => 'Nothing to delete for template {TPL}',
	'xs_cache_log_nothing2' => 'Nothing to delete in cache directory',
	'xs_cache_log_count' => 'Successfully deleted {NUM} files',
	'xs_cache_log_count2' => 'Error deleting {NUM} files',
	'xs_cache_log_compiled' => 'Compiled: {NUM} files',
	'xs_cache_log_errors' => 'Errors: {NUM}',
	'xs_cache_log_noaccess' => 'Error: cannot access directory {DIR}',
	'xs_cache_log_compiled2' => 'Compiled: {FILE}',
	'xs_cache_log_nocompile' => 'Error compiling: {FILE}',

/*
* export/import/download/clone
*/
	'xs_import_explain' => 'Import styles. You can also automatically install and update styles.<br /><br />Note: If you have added any mods (except for eXtreme Styles mod) on this forum then you should be careful when importing styles because not all are compatible with your forum. You can only install styles that have the same modifications as the other styles that you\'ve configured on your forums.',

	'xs_import_lc' => 'import',
	'xs_list_files_lc' => 'list files',
	'xs_delete_file_lc' => 'delete file',
	'xs_export_style_lc' => 'export style',

	'xs_import_no_cached' => 'There are no cached styles to import',
	'xs_add_styles' => 'Add Styles',
	'xs_add_styles_web' => 'Download from web',
	'xs_add_styles_web_get' => 'Get it',
	'xs_add_styles_copy' => 'Copy from local file',
	'xs_add_styles_copy_get' => 'Copy',
	'xs_add_styles_upload' => 'Upload from computer',
	'xs_add_styles_upload_get' => 'Upload',

	'xs_export_style' => 'Export Style',
	'xs_export_style_explain' => 'Export a style as a single file. This single file is very small - smaller than a .zip file (because it is compressed with gzip, which works better than zip) and all styles inside is a single file. In turn, it is very easy to transfer styles from one forum to another.<br /><br />This feature also allows you to upload exported styles using ftp to a server. It also allows you to transfer a style to another forum quickly without manually copying it.',

	'xs_export_style_title' => 'Export Template "{TPL}"',
	'xs_export_tpl_name' => 'Export as (template name)',
	'xs_export_style_names' => 'Select style(s) to export',
	'xs_export_style_name' => 'Style to export (style name)',
	'xs_export_style_comment' => 'Comment',
	'xs_export_where' => 'Where to export',
	'xs_export_where_download' => 'Download as file',
	'xs_export_where_store' => 'Store as file on server',
	'xs_export_where_store_dir' => 'Directory',
	'xs_export_where_ftp' => 'Upload via FTP',
	'xs_export_filename' => 'Export filename',

	'xs_download_explain2' => 'Quickly download and install styles directly from different websites. Click on the link near the website name and you will be redirected to a style downloads page.<br /><br />You can also manage the list of websites.',

	'xs_download_locations' => 'Download Locations',
	'xs_edit_link' => 'Edit Link',
	'xs_add_link' => 'Add Link',
	'xs_link_title' => 'Link Title',
	'xs_link_url' => 'Link URL',
	'xs_delete' => 'Delete',

	'xs_style_header_error_file' => 'Cannot open local file',
	'xs_style_header_error_server' => 'Error on server: ',
	'xs_style_header_error_invalid' => 'Invalid file header',
	'xs_style_header_error_reason' => 'Error reading file header: ',
	'xs_style_header_error_incomplete' => 'File is incomplete',
	'xs_style_header_error_incomplete2' => 'Invalid file size. Probably file is incomplete.',
	'xs_style_header_error_invalid2' => 'Invalid file. Presumably, the file is not an eXtreme Styles mod-compatible style or invalid version.',
	'xs_error_cannot_open' => 'Cannot open file.',
	'xs_error_decompress_style' => 'Error decompressing file. Probably file is corrupted.',
	'xs_error_cannot_create_file' => 'Cannot create file "{FILE}"',
	'xs_error_cannot_create_tmp' => 'Cannot create temporary file "{FILE}"',
	'xs_import_invalid_file' => 'Invalid file',
	'xs_import_incomplete_file' => 'Incomplete file',
	'xs_import_uploaded' => 'Style uploaded.',
	'xs_import_installed' => 'Style uploaded and installed.',
	'xs_import_notinstall' => 'Style uploaded, but error installing style (sql error).',
	'xs_import_notinstall2' => 'Style uploaded, but error installing style: no styles found in theme_info.cfg',
	'xs_import_notinstall3' => 'Style uploaded, but error installing style: no entry for "{STYLE}" found in theme_info.cfg',
	'xs_import_notinstall4' => 'Style uploaded, but error installing style: could not obtain next themes_id information',
	'xs_import_notinstall5' => 'Style uploaded, but error installing style: could not update styles table',
	'xs_import_nodownload' => 'Cannot download style from {URL}',
	'xs_import_nodownload2' => 'Cannot copy style from {URL}',
	'xs_import_nodownload3' => 'File not uploaded.',
	'xs_import_uploaded2' => 'Style downloaded. You can now import it.<br /><br /><a href="{URL}">Click here</a> to import style.',
	'xs_import_uploaded3' => 'Style copied. You can now import it.<br /><br /><a href="{URL}">Click here</a> to import style.',
	'xs_import_uploaded4' => 'Style uploaded. You can now import it.<br /><br /><a href="{URL}">Click here</a> to import style.',
	'xs_export_no_open_dir' => 'Cannot open directory {DIR}',
	'xs_export_no_open_file' => 'Cannot open file {FILE}',
	'xs_export_no_read_file' => 'Error reading file {FILE}',
	'xs_no_theme_data' => 'Could not get style data for selected template',
	'xs_no_style_info' => 'Could not get style information',
	'xs_export_noselect_themes' => 'You should select at least one style',
	'xs_export_error' => 'Cannot export template "{TPL}": ',
	'xs_export_error2' => 'Cannot export template "{TPL}": style is empty',
	'xs_export_saved' => 'Style is saved as "{FILE}"',
	'xs_export_error_uploading' => 'Error uploading file',
	'xs_export_uploaded' => 'File uploaded.',
	'xs_clone_taken' => 'This style name is already used.',
	'xs_error_new_row' => 'Could not insert new row in table.',
	'xs_theme_cloned' => 'Style cloned.',
	'xs_invalid_style_name' => 'Invalid style name.',
	'xs_clone_style_exists' => 'That template already exists',
	'xs_clone_no_select' => 'You should select at least one style to clone.',
	'xs_no_themes' => 'Style not found in database.',

	'xs_import_back' => '<a href="{URL}">Click here</a> to return to import styles page.',
	'xs_import_back_download' => '<a href="{URL}" target="main">Click here</a> to return to downloads.',
	'xs_export_back' => '<a href="{URL}">Click here</a> to return to export styles page.',
	'xs_clone_back' => '<a href="{URL}">Click here</a> to return to clone styles page.',
	'xs_download_back' => '<a href="{URL}">Click here</a> to return to downloads page.',

	'xs_import_tpl' => 'Import Template "{TPL}"',
	'xs_import_tpl_comment' => 'Upload a template to your forum. If a template with this name already exists on your forum this feature will automatically overwrite old files so it can also be used to update styles.<br /><br />This feature can also automatically install styles. If you want to install a style after importing it then select one or more styles below.',
	'xs_import_tpl_filename' => 'Filename:',
	'xs_import_tpl_tplname' => 'Template name:',
	'xs_import_tpl_comment2' => 'Comment:',
	'xs_import_select_styles' => 'Select style(s) to install:',
	'xs_import_install_def_lc' => 'Make default forum style',
	'xs_import_install_style' => 'Install style:',
	'xs_import' => 'Import',

	'xs_import_list_contents' => 'Contents of file: ',
	'xs_import_list_filename' => 'Filename: ',
	'xs_import_list_template' => 'Template: ',
	'xs_import_list_comment' => 'Comment: ',
	'xs_import_list_styles' => 'Style(s): ',
	'xs_import_list_files' => 'Files ({NUM}):',
	'xs_import_download_lc' => 'download file',
	'xs_import_view_lc' => 'view file',
	'xs_import_file_size' => '({NUM} bytes)',

	'xs_import_nogzip' => 'This function requires gz compression, and apparently that isn\'t supported on this server.',
	'xs_import_nowrite_cache' => 'Cannot write to cache. This function requires cache to be writable. Check mod configuration.<br /><br /><a href="{URL1}">Click here</a> to make cache writable.<br /><br /><a href="{URL2}">Click here</a> to return to import page.',

	'xs_import_download_warning' => 'This will take you to an external website where you can quickly download styles with a few simple clicks using the eXtreme Styles import feature.',

	'xs_clone_style' => 'Clone Style',
	'xs_clone_style_explain' => 'Quickly clone style or whole template.<br /><br />Warning: If you are copying a template make sure author of the original template allows you to do this (unless it is subSilver - you can do whatever you want with subSilver). Usually authors allow you to modify their styles, but modified styles should not be distributed.',
	'xs_clone_style_explain2' => 'Create a new style for a template. This feature will not copy any files - it will add an entry in database for your new style. Both old and new style will share same templates.',
	'xs_clone_style_explain3' => 'Enter name for the new style that you are going to create and click "clone" button.',
	'xs_clone_style_explain4' => 'This feature allows you to clone a template. You can also copy all styles associated with that template. Later you can safely edit the tpl files for the new template and the old template will not be affected.',

	'xs_clone_style_lc' => 'clone style',
	'xs_clone_style2' => 'Clone style "{STYLE}":',
	'xs_clone_style3' => 'Clone Template "{STYLE}"',
	'xs_clone_newdir_name' => 'New template (directory) name:',
	'xs_clone_select' => 'Select style(s) to clone:',
	'xs_clone_select_explain' => 'You should select at least one style.',
	'xs_clone_newname' => 'New style name:',


/*
* install/uninstall
*/
	'xs_install_styles_explain2' => 'This is a list of styles that are uploaded on your forum, but aren\'t installed. Click on the "install" link for the style that you want to install, or select several styles and click submit button.',
	'xs_uninstall_styles_explain2' => 'This is a list of styles that are installed on your forum. Click on the "uninstall" link to remove some styles from the forum. Uninstalling is safe - all users who employ the style that is being uninstalled will be switched to the default forum style. Also, uninstalling will automatically delete the cache for that style.',

	'xs_install' => 'Install',
	'xs_install_lc' => 'install',
	'xs_uninstall' => 'Uninstall',
	'xs_remove_files' => 'Remove Files',
	'xs_style_removed' => 'Style removed.',
	'xs_uninstall_lc' => 'uninstall',
	'xs_uninstall2_lc' => 'uninstall and delete files',

	'xs_install_back' => '<a href="{URL}">Click here</a> to return to styles installation.',
	'xs_uninstall_back' => '<a href="{URL}">Click here</a> to return to styles uninstallation.',
	'xs_goto_default' => '<a href="{URL}">Click here</a> to change default style.',

	'xs_install_installed' => 'Style(s) installed.',
	'xs_install_error' => 'Error installing style.',
	'xs_install_none' => 'There are no new styles to install. All available styles are already installed.',

	'xs_uninstall_default' => 'You cannot remove the default style. To change default style <a href="{URL}">click here</a>.',

/*
* export theme_info.cfg
*/
	'xs_export_styles_data_explain2' => 'This feature saves style data in theme_info.cfg. It can be used to save database information before transferring styles from one forum to another.<br /><br />Note: If you are using the eXtreme Styles export feature to move a style to another forum you don\'t need to save theme_info.cfg - it is done automatically by the style export feature.',
	'xs_export_styles_data_explain3' => 'Select styles that you want to export.',

	'xs_export_data_back' => '<a href="{URL}">Click here</a> to return to export style data page.',
	'xs_export_style_data_lc' => 'export style data',

	'xs_export_data_saved' => 'Data exported.',

/*
* edit templates (file manager)
*/
	'xs_edit_template_comment1' => 'Edit templates. File browser shows only editable files.',
	'xs_edit_template_comment2' => 'Edit templates.',
	'xs_edit_file_saved' => 'File is saved.',
	'xs_edit_not_found' => 'File not found.',
	'xs_edittpl_back_dir' => '<a href="{URL}">Click here</a> to return to file manager.',

	'xs_fileman_browser' => 'File Browser',
	'xs_fileman_directory' => 'Directory:',
	'xs_fileman_dircount' => 'Directories ({COUNT}):',
	'xs_fileman_filter' => 'Filter',
	'xs_fileman_filter_ext' => 'Show only files with extension:',
	'xs_fileman_filter_content' => 'Show only files that contain:',
	'xs_fileman_filter_clear' => 'Clear Filter',
	'xs_fileman_filename' => 'Filename',
	'xs_fileman_filesize' => 'Size',
	'xs_fileman_filetime' => 'Modification',
	'xs_fileman_options' => 'Options',
	'xs_fileman_time_today' => '(today)',
	'xs_fileman_edit_lc' => 'edit',

	'xs_fileedit_search_nomatch' => 'Match not found',
	'xs_fileedit_search_match1' => 'Replaced 1 match',
	'xs_fileedit_search_matches' => "Replaced ' + count + ' matches",
	'xs_fileedit_noundo' => 'There is nothing to undo',
	'xs_fileedit_undo_complete' => 'Old content restored',
	'xs_fileedit_edit_name' => 'Edit file:',
	'xs_fileedit_location' => 'Location:',
	'xs_fileedit_reload_lc' => 'reload file',
	'xs_fileedit_download_lc' => 'download file',
	'xs_fileedit_trim' => 'Automatically trim spaces at beginning and end of file.',
	'xs_fileedit_functions' => 'Edit Functions',
	'xs_fileedit_replace1' => 'Replace ',
	'xs_fileedit_replace2' => ' with ',
	'xs_fileedit_replace_first_lc' => 'replace first match',
	'xs_fileedit_replace_all_lc' => 'replace all matches',
	'xs_fileedit_replace_undo_lc' => 'undo replacement',
	'xs_fileedit_backups' => 'Backups',
	'xs_fileedit_backups_save_lc' => 'save backup',
	'xs_fileedit_backups_show_lc' => 'show contents',
	'xs_fileedit_backups_restore_lc' => 'restore',
	'xs_fileedit_backups_download_lc' => 'download',
	'xs_fileedit_backups_delete_lc' => 'delete',
	'xs_fileedit_upload' => 'Upload',
	'xs_fileedit_upload_file' => 'Upload file:',

/*
* edit styles data (theme_info)
*/
	'xs_data_head_stylesheet' => 'CSS Stylesheet',
	'xs_data_body_background' => 'Background Image',
	'xs_data_body_bgcolor' => 'Background Colour',
	'xs_data_style_name' => 'Style Name',
	'xs_data_body_link' => 'Link Colour',
	'xs_data_body_text' => 'Text Colour',
	'xs_data_body_vlink' => 'Visited Link Colour',
	'xs_data_body_alink' => 'Active Link Colour',
	'xs_data_body_hlink' => 'Hover Link Colour',
	'xs_data_tr_color' => 'Table Row Colour %s',
	'xs_data_tr_class' => 'Table Row Class %s',
	'xs_data_th_color' => 'Table Header Colour %s',
	'xs_data_th_class' => 'Table Header Class %s',
	'xs_data_td_color' => 'Table Cell Colour %s',
	'xs_data_td_class' => 'Table Cell Class %s',
	'xs_data_fontface' => 'Font Face %s',
	'xs_data_fontsize' => 'Font Size %s',
	'xs_data_fontcolor' => 'Font Colour %s',
	'xs_data_span_class' => 'Span Class %s',
	'xs_data_img_size_poll' => 'Polling Image Size [px]',
	'xs_data_img_size_privmsg' => 'Private Message Status size [px]',
	'xs_data_theme_public' => 'Public Style (1 or 0)',
	'xs_data_unknown' => 'Description is not available (%s)',

	'xs_edittpl_error_updating' => 'Error updating style.',
	'xs_edittpl_style_updated' => 'Style updated.',
	'xs_invalid_style_id' => 'Invalid style id.',

	'xs_edittpl_back_edit' => '<a href="{URL}">Click here</a> to return to editing.',
	'xs_edittpl_back_list' => '<a href="{URL}">Click here</a> to return to styles list.',

	'xs_editdata_explain' => 'Edit the database data for installed styles. Some styles ignore database values and use css files instead, and some styles use only some of database values.',
	'xs_editdata_var' => 'Variable',
	'xs_editdata_value' => 'Value',
	'xs_editdata_comment' => 'Comment',

/*
* updates
*/

	'xs_updates' => 'Updates',
	'xs_updates_comment' => 'Checks for updates of some styles and mods. It works only with items that have relevant update information.',
	'xs_updates_comment2' => 'This is result of version check.',
	'xs_update_total1' => 'Total: {NUM} items',
	'xs_update_info1' => 'This administration feature will check for available updates of phpBB, certain mods, and some styles installed on your forum. When it finds available updates it shows you the link where you can download the updated file.<br /><br />This function requires sockets to be enabled. Most free web hosts do not have this feature so if this forum is on free host (like lycos) then you cannot use the update feature, but if this forum is on normal server then everything should be okay.<br /><br />When you click "continue", the script will check all software installed on forum. If your website is slow it might take some time. Be patient and don\'t click "stop" in your browser if process is delayed. If this server is slow or update website is slow then the script might timeout - if this happens you should increase timeout value.',
	'xs_update_name' => 'Name',
	'xs_update_type' => 'Type',
	'xs_update_current_version' => 'Your version',
	'xs_update_latest_version' => 'Latest version',
	'xs_update_downloadinfo' => 'Download URL',
	'xs_update_timeout' => 'Update script timeout (seconds):',
	'xs_update_continue' => 'Continue',


	'xs_update_total2' => 'Errors: {NUM}',
	'xs_update_total3' => 'Updates available: {NUM} items',
	'xs_update_select1' => 'Select items to update',
	'xs_update_types' => array(
		0 => 'Unknown',
		1 => 'Style',
		2 => 'Mod',
		3 => 'phpBB',
		4 => 'Icy Phoenix'
	),
	'xs_update_fileinfo' => 'More info',
	'xs_update_nothing' => 'There is nothing to update.',
	'xs_update_noupdate' => 'You are using the latest version.',

	'xs_update_error_url' => 'Error: cannot retrieve url %s',
	'xs_update_error_noitem' => 'Error: No update information available',
	'xs_update_error_noconnect' => 'Error: Cannot connect to update server',

	'xs_update_download' => 'download',
	'xs_update_downloadinfo2' => 'download/info',
	'xs_update_info' => 'website',

	'xs_permission_denied' => 'Permission Denied',

	'xs_download_lc' => 'download',
	'xs_info_lc' => 'info',

/*
* style configuration
*/
	'Template_Config' => 'Template Config',
	'xs_style_configuration' => 'Template Configuration',
	)
);

?>