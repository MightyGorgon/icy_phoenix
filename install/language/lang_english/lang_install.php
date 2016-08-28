<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ENCODING' => 'UTF-8',
	'DIRECTION' => 'ltr',
	'HEADER_LANG' => 'en-gb',
	'HEADER_LANG_XML' => 'en-gb',
	'LEFT' => 'left',
	'RIGHT' => 'right',

	'Welcome_install' => 'Icy Phoenix Installation',
	'Initial_config' => 'Configuration',
	'DB_config' => 'Database Configuration',
	'Admin_config' => 'Admin Configuration',
	'continue_upgrade' => 'Once you have downloaded your config file to your local machine you may\'Continue Upgrade\' button below to move forward with the upgrade process. Please wait to upload the config file until the upgrade process is complete.',
	'upgrade_submit' => 'Continue Upgrade',

	'Installer_Error' => 'An error has occurred during installation',
	'Previous_Install' => 'A previous installation has been detected',
	'Install_db_error' => 'An error occurred trying to update the database',

	'Re_install' => 'Your previous installation is still active.<br /><br />If you would like to re-install Icy Phoenix you should click the Yes button below. Please be aware that doing so will destroy all existing data and no backups will be made! The Administrator username and password you have used to log in to the board will be re-created after the re-installation and no other settings will be retained.<br /><br />Think carefully before pressing Yes!',

	'Inst_Step_0' => 'Thank you for choosing Icy Phoenix. This wizard will guide you through the installation process.<br /><span class="text_red">Please note that you should have uploaded all Icy Phoenix files to your server and that the database you install into should already exist.</span>',

	'Inst_Step_1' => 'In order to complete this install please fill out the details requested below.<br /><span class="text_red">Please note that the database you are going to install into should already exist (Setup procedure cannot create one).</span>',

	'Start_Install' => 'Start Install',
	'Start_Install_Anyway' => 'Start Install Anyway',
	'Finish_Install' => 'Finish Installation',
	'Continue_Install' => 'Continue Installation',

	'CHMOD_Files' => 'Files &amp; Folders Permissions',
	'CHMOD_OK' => 'Ok',
	'CHMOD_Error' => 'Error',
	'CHMOD_777' => 'CHMOD 777',
	'CHMOD_666' => 'CHMOD 666',
	'CHMOD_Files_Explain_Error' => 'Some errors occurred while verifying CHMOD permissions. Please make sure that all files/folders from the installation package exist and they have the correct CHMOD permissions, otherwise Icy Phoenix cannot run correctly.',
	'Confirm_Install_anyway' => ' If you have double checked everything you may proceed by clicking on "<i>Start Install Anyway</i>".',
	'CHMOD_Files_Explain_Ok' => 'All CHMOD permissions seems to be fine.',
	'Can_Install' => 'You can proceed to next step.',
	'CHMOD_File_Exists' => 'This File/Folder exists and its permissions have been applied correctly.',
	'CHMOD_File_NotExists' => 'This File/Folder doesn\'t exist, please upload it and apply the correct CHMOD permissions.',
	'CHMOD_File_Exists_Read_Only' => 'This File/Folder exists but its permissions may not be modified automatically, please apply CHMOD manually and then click on "<i>Start Install Anyway</i>".',
	'CHMOD_File_UnknownError' => 'Unknown error while checking this File/Folder. Please make sure that this File/Folder exists on the server, that it has the correct CHMOD permissions and then click on "<i>Start Install Anyway</i>".',
	'CHMOD_Apply' => 'Apply CHMOD permissions to Icy Phoenix files and folders via PHP',
	'CHMOD_Apply_Warn' => 'Please note that not all servers support CHMOD via PHP, manual editing may be required!!!',

	'Default_lang' => 'Default site language',
	'Select_lang' => 'Language',
	'DB_Host' => 'Database Server Hostname / DSN',
	'DB_Name' => 'Your Database Name',
	'DB_Username' => 'Database Username',
	'DB_Password' => 'Database Password',
	'Database' => 'Your Database',
	'Install_lang' => 'Choose Language for Installation',
	'dbms' => 'Database Type',
	'Table_Prefix' => 'Prefix for tables in database',
	'Admin_Username' => 'Administrator Username',
	'Admin_Password' => 'Administrator Password',
	'Admin_Password_confirm' => 'Administrator Password [ Confirm ]',
	'Password_mismatch' => 'The passwords you entered did not match.',

	'Inst_Step_2' => 'Your admin username has been created.<br />At this point to complete the setup process you need to <span class="text_red">remove <u>install</u> folder</span>. Finally you should click on <b>Finish Installation</b> and then access ACP (Admin Control Panel) and CMS (Content Management System) where you can manage all Icy Phoenix settings and preferences.<br />In ACP you can adjust the main settings and preferences for the whole site, (styles, languages, time, forums, download, users, album, etc.) and while in CMS section you can configure options regarding the site pages (define permissions, add blocks, create new pages, create new menu, etc.). You may also want to configure <b>.htaccess</b> and <b>lang_main_settings.php</b> (for each installed lang) to fine tune some other preferences, like error reporting, url rewrite, keywords, welcome message, charset and so on.<br /><br />Thank you for choosing Icy Phoenix and remember to backup your db periodically.<br /><br />',

	'Unwriteable_config' => 'Your config file is not writable. A copy of the config file will be downloaded to your computer when you click the button below. You should upload this file to the same directory where Icy Phoenix has been uploaded. Once this is done you should delete the install folder and log in using the administrator name and password you provided on the previous form to visit the admin control centre; (a link will appear at the bottom of each screen once logged in) to check the general configuration. Thank you for choosing Icy Phoenix.',
	'Download_config' => 'Download Config',

	'ftp_choose' => 'Choose Download Method',
	'ftp_option' => '<br />Since FTP extensions are enabled in this version of PHP you may also be given the option of first trying to automatically FTP the config file into place.',
	'ftp_instructs' => 'You have chosen to FTP the file to the account containing Icy Phoenix automatically. Please enter the information below to facilitate this process. Note that the FTP path should be the exact path via FTP to your Icy Phoenix installation as if you were FTPing it using any normal client.',
	'ftp_info' => 'Enter Your FTP Information',
	'Attempt_ftp' => 'Attempt to FTP config file into place',
	'Send_file' => 'Just send the file to me and I\'ll FTP it manually',
	'ftp_path' => 'FTP path to Icy Phoenix',
	'ftp_username' => 'Your FTP Username',
	'ftp_password' => 'Your FTP Password',
	'Transfer_config' => 'Start Transfer',
	'NoFTP_config' => 'The attempt to FTP the config file into place failed. Please download the config file and FTP it into place manually.',

	'Install' => 'Install',
	'Upgrade' => 'Upgrade',

	'Install_Method' => 'Choose your installation method',
	'Install_No_Ext' => 'The PHP configuration on your server doesn\'t support the database type that you chose',
	'Install_No_PCRE' => 'Icy Phoenix Requires the Perl-Compatible Regular Expressions Module for PHP which your PHP configuration doesn\'t appear to support!',

	'Server_name' => 'Domain Name',
	'Script_path' => 'Script Path',
	'Server_port' => 'Server Port',
	'Admin_email' => 'Admin Email Address',

	'IP_Utilities' => 'Icy Phoenix Utilities',
	'Upgrade_Options' => 'Upgrade Options:',
	'Upgrade_From' => 'Upgrade to latest Icy Phoenix',
	'Upgrade_From_Version' => 'from version',
	'Upgrade_From_phpBB' => 'from phpBB or any older phpBB XS version',
	'Upgrade_Higher' => 'or higher',

	'IcyPhoenix' => 'Icy Phoenix',
	'phpBB' => 'phpBB',
	'Information' => 'Information',
	'VersionInformation' => 'Server And Version Information',
	'NotInstalled' => 'Not Installed',
	'Current_IP_Version' => 'Installed Icy Phoenix version',
	'Current_phpBB_Version' => 'Installed phpBB version',
	'Latest_Release' => 'Latest release',
	'Version_UpToDate' => 'Version up-to-date',
	'Version_NotUpdated' => 'Version not updated',
	'UpdateInProgress' => 'Update in progress',
	'CleaningInProgress' => 'Files cleaning in progress',
	'UpdateCompleted' => 'Update completed!',
	'UpdateCompleted_phpBB' => 'phpBB update completed, now you can upgrade to Icy Phoenix!',
	'UpdateInProgress_Schema' => 'Updating database schema',
	'UpdateInProgress_Data' => 'Updating data',
	'Optimizing_Tables' => 'Optimizing tables',
	'Progress' => 'Progress',
	'Done' => 'Done',
	'NotDone' => 'Not Done',
	'Result' => 'Result',
	'Error' => 'Error',
	'Successful' => 'Successful',
	'NoErrors' => 'No Errors',
	'NoUpdate' => 'No updates required',
	'phpBB_NotDetected' => 'phpBB has not been detected the script cannot proceed. Please check that you are really running phpBB.',
	'Update_Errors' => 'Some queries failed, the statements and errors are listing below',

	'DBUpdate_Success' => 'The following SQL have been executed successfully',
	'DBUpdate_Errors' => 'The following SQL have not been executed',

	'FileWriting' => 'File Writing',
	'FileCreation_OK' => 'Your server seems to support files creation and editing.',
	'FileCreation_OK_Explain' => 'The script will attempt to automatically create / edit all needed files.',
	'FileCreation_ERROR' => 'Your server doesn\'t support file creation and editing.',
	'FileCreation_ERROR_Explain' => 'The script cannot create / edit files for you automatically. Unfortunately you will need to do it on your own.',

	'IcyPhoenix_Version_UpToDate' => 'Your Icy Phoenix is version is up-to-date',
	'IcyPhoenix_Version_NotUpToDate' => 'Your Icy Phoenix is version is not up-to-date',
	'IcyPhoenix_Version_NotInstalled' => 'Icy Phoenix is not installed',
	'phpBB_Version_UpToDate' => 'Your phpBB is version is up-to-date',
	'phpBB_Version_NotUpToDate' => 'Your phpBB is version is not up-to-date',
	'ClickUpdate' => 'Please click %sHere%s to update!',
	'ClickReturn' => 'Please click %sHere%s to return to menu!',

	'Clean_OldFiles_Explain' => 'Remove all unused Icy Phoenix (files still on your server from older versions)',
	'ActionUndone' => 'Please note that this action cannot be undone. Make sure you have a backup!!!',
	'ClickToClean' => 'Please click on the link below to proceed',
	'FileDeletion_OK' => 'File deleted successfully',
	'FileDeletion_ERROR' => 'File cannot be deleted',
	'FileDeletion_NF' => 'Files cannot be found',
	'FilesDeletion_OK' => 'Files deleted successfully',
	'FilesDeletion_NO' => 'Files not deleted',
	'FilesDeletion_ERROR' => 'Files cannot be automatically deleted',
	'FilesDeletion_NF' => 'Files cannot be found',
	'FilesDeletion_None' => 'None',
	'FileDeletion_Complete' => 'Files cleaning complete!',

	'Spoiler' => 'Spoiler',
	'Show' => 'Show',
	'Hide' => 'Hide',
	'None' => 'None',
	'Start' => 'Start',

	'Upgrade_Steps' => 'Upgrade Steps',
	'MakeFullBackup' => 'Make a full backup (both files and DB) and keep it in a safe place!',
	'Update_phpBB' => 'Update phpBB DB (if needed)',
	'Remove_BBCodeUID' => 'Process all posts: remove BBCode UID, replace text, remove old BBCodes',
	'Merge_PostsTables' => 'Merge posts tables',
	'Update_IcyPhoenix' => 'Update Icy Phoenix DB',
	'Upload_NewFiles' => 'Upload all new files',
	'Adjust_Config' => 'Update constants in config.php (only works if files are writeable)',
	'Adjust_CMSPages' => 'Update constants in CMS pages (only works if files are writeable)',
	'MoveImagesAlbum' => 'Move album images (optional: only if you want to use posted images into subfolders)',
	'MoveImages' => 'Move posted images (optional: only if you want to use posted images into subfolders)',
	'Clean_OldFiles' => 'Clean Old Files',

	'ColorsLegend' => 'Colors Legend',
	'ColorsLegendRed' => 'Red: this action is required and have to be performed manually',
	'ColorsLegendOrange' => 'Orange: this action is required and the script could perform it automatically (if requirements are met)',
	'ColorsLegendGray' => 'Gray: this action may not be needed and can be performed automatically',
	'ColorsLegendBlue' => 'Blue: this action is optional and can be performed automatically (may require manual edits on some files though)',
	'ColorsLegendGreen' => 'Green: this action is suggested and can be performed automatically (if requirements are met)',

	'FixBirthdays' => 'Fix Birthdays (Upgrading from Icy Phoenix 1.2 or below)',
	'FixBirthdaysExplain' => 'This feature will allow you to adjust all birthdays for compatibility with new features. You don\'t need to run this feature if you are upgrading from Icy Phoenix 1.3 or above.',
	'FixingBirthdaysInProgress' => 'Fixing birthdays in progress',
	'FixingBirthdaysInProgressRedirect' => 'You will be automatically redirected to next step in three seconds',
	'FixingBirthdaysInProgressRedirectClick' => 'If you are not automatically redirected within three seconds you may click %sHere%s',
	'FixingBirthdaysFrom' => 'Birthdays modified this step from %s to %s',
	'FixingBirthdaysTotal' => '%s birthdays of %s modified so far',
	'FixingBirthdaysModified' => ' birthdays fixed',
	'FixingBirthdaysComplete' => 'Fixing birthdays complete',
	'BirthdaysPerStep' => 'Number of birthdays per step',

	'FixConstantsInFiles' => 'Fix Constants (Upgrading from Icy Phoenix 1.2 or below)',
	'FixConstantsInFilesExplain' => 'Fix all files with new Icy Phoenix constants. You don\'t need to run this feature if you are upgrading from Icy Phoenix 1.3 or above.',
	'FixingInProgress' => 'Fixing files in progress',
	'FixingComplete' => 'Fixing files complete',
	'ClickToFix' => 'Please click on one of the link below to proceed',
	'FixAllFiles' => 'Fix all files (both CMS pages and config.php)',
	'FixCMSPages' => 'Fix only CMS pages',
	'Fixed' => 'Fixed',
	'NotFixed' => 'Not Fixed',
	'FilesProcessed' => 'Files processed',

	'FixForums' => 'Convert Forums (Upgrading from Icy Phoenix 1.2 or below)',
	'FixForumsExplain' => 'This feature will convert forums and categories into the new format. You don\'t need to run this feature if you are upgrading from Icy Phoenix 1.3 or above.',
	'FixingForumsInProgress' => 'Conversion in progress...',
	'FixingForumsComplete' => 'Work complete!',

	'FixPosts' => 'Fix Posts (Upgrading from Icy Phoenix 1.2 or below)',
	'FixPosts_Explain' => 'This feature will allow you to fix all posts in your forums. You can use this feature to: find and replace any text in your posts, remove all BBCode UID, automatically adjust the address of posted images. You don\'t need to run this feature if you are upgrading from Icy Phoenix 1.3 or above.',
	'FixPosts_IP2' => 'Fix Posts (Upgrading from Icy Phoenix 1.3)',
	'FixPosts_IP2_Explain' => 'This feature will allow you to adjust the path of uploaded images, since the path in Icy Phoenix 2.0 is different from the past versions of Icy Phoenix. You can use this feature also to find and replace any text in your posts (by leaving the search fild empty no replacement will be performed and only images paths will be adjusted).',
	'FixingPostsInProgress' => 'Fixing posts in progress',
	'FixingPostsInProgressRedirect' => 'You will be automatically redirected to next step in three seconds',
	'FixingPostsInProgressRedirectClick' => 'If you are not automatically redirected within three seconds you may click %sHere%s',
	'FixingPostsFrom' => 'Posts modified this step from %s to %s',
	'FixingPostsTotal' => '%s posts of %s modified so far',
	'FixingPostsModified' => ' posts fixed',
	'FixingPostsComplete' => 'Fixing posts complete',
	'SearchWhat' => 'Search what',
	'ReplaceWith' => 'Replace with',
	'PostsPerStep' => 'Number of posts per step',
	'StartFrom' => 'Start from post',
	'RemoveBBCodeUID' => 'Remove BBCode UID (get it from posts table)',
	'RemoveBBCodeUID_Guess' => 'Try to guess and remove BBCode UID',
	'FixPostedImagesPaths' => 'Fix all posted images paths (adjust paths to reflect users subfolders)',

	'FixSignatures' => 'Fix Signatures (Upgrading from Icy Phoenix 1.2 or below)',
	'FixSignatures_Explain' => 'This feature will allow you to fix all users signatures. You can use this feature to: find and replace any text in signatures, remove all BBCode UID, automatically adjust the address of posted images. You don\'t need to run this feature if you are upgrading from Icy Phoenix 1.3 or above.',
	'FixSignatures_IP2' => 'Fix Signatures (Upgrading from Icy Phoenix 1.3)',
	'FixSignatures_IP2_Explain' => 'This feature will allow you to adjust the path of uploaded images, since the path in Icy Phoenix 2.0 is different from the past versions of Icy Phoenix. You can use this feature also to find and replace any text in signatures (by leaving the search fild empty no replacement will be performed and only images paths will be adjusted).',
	'FixingSignaturesInProgress' => 'Fixing signatures in progress',
	'FixingSignaturesFrom' => 'Signatures modified this step from %s to %s',
	'FixingSignaturesTotal' => '%s signatures of %s modified so far',
	'FixingSignaturesModified' => ' signatures fixed',
	'FixingSignaturesComplete' => 'Fixing signatures complete',
	'SignaturesPerStep' => 'Number of signatures per step',
	'StartFromSignature' => 'Start from signature',

	'FixPics' => 'Fix Album Pics Paths (Upgrading from Icy Phoenix 1.2 or below)',
	'FixPicsExplain' => 'This feature will move all album pics from the main folder into users subfolders and will also update the database with the new paths. You don\'t need to run this feature if you are upgrading from Icy Phoenix 1.3 or above.',
	'FixingPicsInProgress' => 'Fixing pics in progress',
	'FixingPicsInProgressRedirect' => 'You will be automatically redirected to next step in three seconds',
	'FixingPicsInProgressRedirectClick' => 'If you are not automatically redirected within three seconds you may click %sHere%s',
	'FixingPicsFrom' => 'Pics modified this step from %s to %s',
	'FixingPicsTotal' => '%s pics of %s modified so far',
	'FixingPicsModified' => ' pics fixed',
	'FixingPicsComplete' => 'Fixing pics complete',
	'PicStartFrom' => 'Start from pic',
	'PicsPerStep' => 'Number of pics per step',

	'RenMovePics' => 'Rename And Move Posted Pics (Upgrading from Icy Phoenix 1.2 or below)',
	'RenMovePicsExplain' => 'This feature will rename and move all posted pics from the main folder into users subfolders: you will then need to update posts table using the <i>Fix Posts</i> function to adjust all paths in posts. You don\'t need to run this feature if you are upgrading from Icy Phoenix 1.3 or above.',

	'AddPostedPicsDB' => 'Add Uploaded Images To DB (Upgrading from Icy Phoenix 1.3 or above)',
	'AddPostedPicsDBExplain' => 'This feature will import all uploaded images to DB.',

	'COLLIDING_CLEAN_USERNAME' => '<strong>%s</strong> is the clean username for:',
	'COLLIDING_USERNAMES_FOUND' => 'Colliding usernames were found on your old board. In order to complete the conversion please delete or rename these users so that there is only one user on your old board for each clean username.',
	'COLLIDING_USER' => '&raquo; user id: <strong>%d</strong> username: <strong>%s</strong> (%d posts)',

	)
);

$lang['BBC_IP_CREDITS_STATIC'] = '
<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="http://www.icyphoenix.com/images/logo_ip.png" alt="Icy Phoenix" title="Icy Phoenix" /></a><br />
<span style="color: #dd2222;"><b>Mighty Gorgon</b></span>&nbsp;<i>(Luca Libralato)</i><br />
<b><i>Project Manager And Main Developer</i></b><br />
<br />
<br />
<span style="color: #dd2222;"><b>The Steffen</b></span><br />
<b><i>Site Administrator</i></b><br />
<br />
<br />
<span style="color: #228822;"><b>mort</b></span><br />
<b><i>Staff Leader</i></b><br />
<br />
<br />
<span style="color: #ff5500;"><b>KasLimon</b></span><br />
<b><i>Developer</i></b><br />
<br />
<br />
<span style="color: #ff5500;"><b>Informpro</b></span><br />
<b><i>Developer</i></b><br />
<br />
<br />
<b><i>Valued Contributors</i></b><br />
<span style="color: #dd2222;"><b>Andrea75</b></span><br />
<span style="color: #228822;"><b>Artie</b></span><br />
<span style="color: #ff5500;"><b>Bicet</b></span>&nbsp;<i>(phpBB XS Developer)</i><br />
<span style="color: #880088;"><b>brandsrus</b></span><br />
<span style="color: #dd2222;"><b>buldo</b></span><br />
<span style="color: #880088;"><b>casimedicos</b></span><br />
<span style="color: #880088;"><b>Chaotic</b></span><br />
<span style="color: #ff5500;"><b>CyberAlien</b></span>&nbsp;<i>(Many Contributions)</i><br />
<span style="color: #880088;"><b>difus</b></span><br />
<span style="color: #228822;"><b>DWho</b></span><br />
<span style="color: #880088;"><b>fracs</b></span><br />
<span style="color: #880088;"><b>ganesh</b></span><br />
<span style="color: #880088;"><b>Hans</b></span><br />
<span style="color: #ff5500;"><b>hpl</b></span>&nbsp;<i>(Junior Developer)</i><br />
<span style="color: #880088;"><b>JANU1535</b></span><br />
<span style="color: #ff5500;"><b>jhl</b></span>&nbsp;<i>(Junior Developer)</i><br />
<span style="color: #228822;"><b>Joshua203</b></span><br />
<span style="color: #880088;"><b>jz</b></span><br />
<span style="color: #aaff00;"><b>KugeLSichA</b></span><br />
<span style="color: #0000bb;"><b>Limun</b></span><br />
<span style="color: #880088;"><b>Lopalong</b></span><br />
<span style="color: #880088;"><b>moreteavicar</b></span><br />
<span style="color: #880088;"><b>novice programmer</b></span><br />
<span style="color: #dd2222;"><b>ThE KuKa</b></span><br />
<span style="color: #880088;"><b>Tom</b></span><br />
<span style="color: #228822;"><b>TopoMotoV3X</b></span><br />
<span style="color: #aaff00;"><b>TuningBEB2008</b></span><br />
<span style="color: #880088;"><b>z3d0</b></span><br />
<span style="color: #880088;"><b>Zuker</b></span><br />
<br />
';

$lang['BBC_IP_CREDITS'] = '<div class="center-block"><marquee behavior="scroll" direction="up" scrolldelay="120">' . $lang['BBC_IP_CREDITS_STATIC'] . '</marquee></div>';

?>