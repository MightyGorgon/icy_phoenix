<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

$lang['ENCODING'] = 'iso-8859-1';
$lang['ENCODING_ALT'] = 'utf8';
$lang['DIRECTION'] = 'ltr';
$lang['HEADER_LANG'] = 'en-gb';
$lang['HEADER_XML_LANG'] = 'en-gb';
$lang['LEFT'] = 'left';
$lang['RIGHT'] = 'right';

$lang['Welcome_install'] = 'Icy Phoenix Installation';
$lang['Initial_config'] = 'Configuration';
$lang['DB_config'] = 'Database Configuration';
$lang['Admin_config'] = 'Admin Configuration';
$lang['continue_upgrade'] = 'Once you have downloaded your config file to your local machine you may\'Continue Upgrade\' button below to move forward with the upgrade process. Please wait to upload the config file until the upgrade process is complete.';
$lang['upgrade_submit'] = 'Continue Upgrade';

$lang['Installer_Error'] = 'An error has occurred during installation';
$lang['Previous_Install'] = 'A previous installation has been detected';
$lang['Install_db_error'] = 'An error occurred trying to update the database';

$lang['Re_install'] = 'Your previous installation is still active.<br /><br />If you would like to re-install Icy Phoenix you should click the Yes button below. Please be aware that doing so will destroy all existing data and no backups will be made! The Administrator username and password you have used to log in to the board will be re-created after the re-installation and no other settings will be retained.<br /><br />Think carefully before pressing Yes!';

$lang['Inst_Step_0'] = 'Thank you for choosing Icy Phoenix. This wizard will guide you through the installation process.<br /><span class="text_red">Please note that you should have uploaded all Icy Phoenix files to your server and that the database you install into should already exist.</span>';

$lang['Inst_Step_1'] = 'In order to complete this install please fill out the details requested below.<br /><span class="text_red">Please note that the database you are going to install into should already exist (Setup procedure cannot create one).</span>';

$lang['Start_Install'] = 'Start Install';
$lang['Start_Install_Anyway'] = 'Start Install Anyway';
$lang['Finish_Install'] = 'Finish Installation';
$lang['Continue_Install'] = 'Continue Installation';

$lang['CHMOD_Files'] = 'Files &amp; Folders Permissions';
$lang['CHMOD_OK'] = 'Ok';
$lang['CHMOD_Error'] = 'Error';
$lang['CHMOD_777'] = 'CHMOD 777';
$lang['CHMOD_666'] = 'CHMOD 666';
$lang['CHMOD_Files_Explain_Error'] = 'Some errors occurred while verifying CHMOD permissions. Please make sure that all files/folders from the installation package exist and they have the correct CHMOD permissions, otherwise Icy Phoenix cannot run correctly.';
$lang['Confirm_Install_anyway'] = ' If you have double checked everything you may proceed by clicking on "<i>' . $lang['Start_Install_Anyway'] . '</i>".';
$lang['CHMOD_Files_Explain_Ok'] = 'All CHMOD permissions seems to be fine.';
$lang['Can_Install'] = 'You can proceed to next step.';
$lang['CHMOD_File_Exists'] = 'This File/Folder exists and its permissions have been applied correctly.';
$lang['CHMOD_File_NotExists'] = 'This File/Folder doesn\'t exist, please upload it and apply the correct CHMOD permissions.';
$lang['CHMOD_File_Exists_Read_Only'] = 'This File/Folder exists but its permissions may not be modified automatically, please apply CHMOD manually and then click on "<i>' . $lang['Start_Install_Anyway'] . '</i>".';
$lang['CHMOD_File_UnknownError'] = 'Unknown error while checking this File/Folder. Please make sure that this File/Folder exists on the server, that it has the correct CHMOD permissions and then click on "<i>' . $lang['Start_Install_Anyway'] . '</i>".';
$lang['CHMOD_Apply'] = 'Apply CHMOD permissions to Icy Phoenix files and folders via PHP';
$lang['CHMOD_Apply_Warn'] = 'Please note that not all servers support CHMOD via PHP, manual editing may be required!!!';

$lang['Default_lang'] = 'Default site language';
$lang['Select_lang'] = 'Language';
$lang['DB_Host'] = 'Database Server Hostname / DSN';
$lang['DB_Name'] = 'Your Database Name';
$lang['DB_Username'] = 'Database Username';
$lang['DB_Password'] = 'Database Password';
$lang['Database'] = 'Your Database';
$lang['Install_lang'] = 'Choose Language for Installation';
$lang['dbms'] = 'Database Type';
$lang['Table_Prefix'] = 'Prefix for tables in database';
$lang['Admin_Username'] = 'Administrator Username';
$lang['Admin_Password'] = 'Administrator Password';
$lang['Admin_Password_confirm'] = 'Administrator Password [ Confirm ]';

$lang['Inst_Step_2'] = 'Your admin username has been created.<br />At this point to complete the setup process you need to <span class="text_red">remove <u>install</u> and <u>contrib</u> (if you have it on your server) folders</span>. Finally you should click on <b>Finish Installation</b> and then access ACP (Admin Control Panel) and CMS (Content Management System) where you can manage all Icy Phoenix settings and preferences.<br />In ACP you can adjust the main settings and preferences for the whole site, (styles, languages, time, forums, download, users, album, etc.) and while in CMS section you can configure options regarding the site pages (define permissions, add blocks, create new pages, create new menu, etc.). You may also want to configure <b>.htaccess</b> and <b>lang_main_settings.php</b> (for each installed lang) to fine tune some other preferences, like error reporting, url rewrite, keywords, welcome message, charset and so on.<br /><br />Thank you for choosing Icy Phoenix and remember to backup your db periodically.<br /><br />';

$lang['Unwriteable_config'] = 'Your config file is not writable. A copy of the config file will be downloaded to your computer when you click the button below. You should upload this file to the same directory where Icy Phoenix has been uploaded. Once this is done you should delete the install folder and log in using the administrator name and password you provided on the previous form to visit the admin control centre; (a link will appear at the bottom of each screen once logged in) to check the general configuration. Thank you for choosing Icy Phoenix.';
$lang['Download_config'] = 'Download Config';

$lang['ftp_choose'] = 'Choose Download Method';
$lang['ftp_option'] = '<br />Since FTP extensions are enabled in this version of PHP you may also be given the option of first trying to automatically FTP the config file into place.';
$lang['ftp_instructs'] = 'You have chosen to FTP the file to the account containing Icy Phoenix automatically. Please enter the information below to facilitate this process. Note that the FTP path should be the exact path via FTP to your Icy Phoenix installation as if you were FTPing it using any normal client.';
$lang['ftp_info'] = 'Enter Your FTP Information';
$lang['Attempt_ftp'] = 'Attempt to FTP config file into place';
$lang['Send_file'] = 'Just send the file to me and I\'ll FTP it manually';
$lang['ftp_path'] = 'FTP path to Icy Phoenix';
$lang['ftp_username'] = 'Your FTP Username';
$lang['ftp_password'] = 'Your FTP Password';
$lang['Transfer_config'] = 'Start Transfer';
$lang['NoFTP_config'] = 'The attempt to FTP the config file into place failed. Please download the config file and FTP it into place manually.';

$lang['Install'] = 'Install';
$lang['Upgrade'] = 'Upgrade';

$lang['Install_Method'] = 'Choose your installation method';
$lang['Install_No_Ext'] = 'The PHP configuration on your server doesn\'t support the database type that you chose';
$lang['Install_No_PCRE'] = 'Icy Phoenix Requires the Perl-Compatible Regular Expressions Module for PHP which your PHP configuration doesn\'t appear to support!';

$lang['Server_name'] = 'Domain Name';
$lang['Script_path'] = 'Script Path';
$lang['Server_port'] = 'Server Port';
$lang['Admin_email'] = 'Admin Email Address';

$lang['IP_Utilities'] = 'Icy Phoenix Utilities';
$lang['Upgrade_Options'] = 'Upgrade Options:';
$lang['Upgrade_From'] = 'Upgrade to latest Icy Phoenix';
$lang['Upgrade_From_Version'] = 'from version';
$lang['Upgrade_From_phpBB'] = 'from phpBB or any older phpBB XS version';
$lang['Upgrade_Higher'] = 'or higher';

$lang['IcyPhoenix'] = 'Icy Phoenix';
$lang['phpBB'] = 'phpBB';
$lang['Information'] = 'Information';
$lang['VersionInformation'] = 'Server And Version Information';
$lang['NotInstalled'] = 'Not Installed';
$lang['Current_IP_Version'] = 'Installed Icy Phoenix version';
$lang['Current_phpBB_Version'] = 'Installed phpBB version';
$lang['Latest_Release'] = 'Latest release';
$lang['Version_UpToDate'] = 'Version up-to-date';
$lang['Version_NotUpdated'] = 'Version not updated';
$lang['UpdateInProgress'] = 'Update in progress';
$lang['CleaningInProgress'] = 'Files cleaning in progress';
$lang['UpdateCompleted'] = 'Update completed!';
$lang['UpdateInProgress_Schema'] = 'Updating database schema';
$lang['UpdateInProgress_Data'] = 'Updating data';
$lang['Optimizing_Tables'] = 'Optimizing tables';
$lang['Progress'] = 'Progress';
$lang['Done'] = 'Done';
$lang['NotDone'] = 'Not Done';
$lang['Result'] = 'Result';
$lang['Error'] = 'Error';
$lang['Successful'] = 'Successful';
$lang['NoErrors'] = 'No Errors';
$lang['NoUpdate'] = 'No updates required';
$lang['phpBB_NotDetected'] = 'phpBB has not been detected the script cannot proceed. Please check that you are really running phpBB.';
$lang['Update_Errors'] = 'Some queries failed, the statements and errors are listing below';

$lang['DBUpdate_Success'] = 'The following SQL have been executed successfully';
$lang['DBUpdate_Errors'] = 'The following SQL have not been executed';

$lang['FileWriting'] = 'File Writing';
$lang['FileCreation_OK'] = 'Your server seems to support files creation and editing.';
$lang['FileCreation_OK_Explain'] = 'The script will attempt to automatically create / edit all needed files.';
$lang['FileCreation_ERROR'] = 'Your server doesn\'t support file creation and editing.';
$lang['FileCreation_ERROR_Explain'] = 'The script cannot create / edit files for you automatically. Unfortunately you will need to do it on your own.';

$lang['IcyPhoenix_Version_UpToDate'] = 'Your Icy Phoenix is version is up-to-date';
$lang['IcyPhoenix_Version_NotUpToDate'] = 'Your Icy Phoenix is version is not up-to-date';
$lang['IcyPhoenix_Version_NotInstalled'] = 'Icy Phoenix is not installed';
$lang['phpBB_Version_UpToDate'] = 'Your phpBB is version is up-to-date';
$lang['phpBB_Version_NotUpToDate'] = 'Your phpBB is version is not up-to-date';
$lang['ClickUpdate'] = 'Please click %shere%s to update!';
$lang['ClickReturn'] = 'Please click %shere%s to return to menu!';

$lang['Clean_OldFiles_Explain'] = 'Remove all unused Icy Phoenix (files still on your server from older versions)';
$lang['ActionUndone'] = 'Please note that this action cannot be undone. Make sure you have a backup!!!';
$lang['ClickToClean'] = 'Please click on the link below to proceed';
$lang['FileDeletion_OK'] = 'File deleted successfully';
$lang['FileDeletion_ERROR'] = 'File cannot be deleted';
$lang['FileDeletion_NF'] = 'Files cannot be found';
$lang['FilesDeletion_OK'] = 'Files deleted successfully';
$lang['FilesDeletion_NO'] = 'Files not deleted';
$lang['FilesDeletion_ERROR'] = 'Files cannot be automatically deleted';
$lang['FilesDeletion_NF'] = 'Files cannot be found';
$lang['FilesDeletion_None'] = 'None';
$lang['FileDeletion_Complete'] = 'Files cleaning complete!';

$lang['Spoiler'] = 'Spoiler';
$lang['Show'] = 'Show';
$lang['Hide'] = 'Hide';
$lang['None'] = 'None';
$lang['Start'] = 'Start';

$lang['Upgrade_Steps'] = 'Upgrade Steps';
$lang['MakeFullBackup'] = 'Make a full backup (both files and DB) and keep it in a safe place!';
$lang['Update_phpBB'] = 'Update phpBB';
$lang['MoveImages'] = 'Move Images (optional: only if you want to use posted images into subfolders)';
$lang['Adjust_CMSPages'] = 'Update constants in CMS pages (your CMS pages should be writeable for this to succeed)';
$lang['Remove_BBCodeUID'] = 'Remove BBCode UID';
$lang['Merge_PostsTables'] = 'Merge Posts Tables';
$lang['Update_IcyPhoenix'] = 'Update Icy Phoenix';
$lang['Clean_OldFiles'] = 'Clean Old Files';
$lang['Adjust_Config'] = 'Update constants config.php (your config.php should be writeable for this to succeed)';
$lang['Upload_NewFiles'] = 'Upload all new files';

$lang['FixConstantsInFiles'] = 'Fix Constants';
$lang['FixConstantsInFilesExplain'] = 'Fix all files with new Icy Phoenix constants';
$lang['FixingInProgress'] = 'Fixing files in progress';
$lang['FixingComplete'] = 'Fixing files complete';
$lang['ClickToFix'] = 'Please click on one of the link below to proceed';
$lang['FixAllFiles'] = 'Fix all files (both CMS pages and config.php)';
$lang['FixCMSPages'] = 'Fix only CMS pages';
$lang['Fixed'] = 'Fixed';
$lang['NotFixed'] = 'Not Fixed';
$lang['FilesProcessed'] = 'Files processed';

$lang['FixPosts'] = 'Fix Posts';
$lang['FixPostsExplain'] = 'This feature will allow you to fix all posts in your forums. You can use this feature to: find and replace any text in your posts, remove all BBCode UID, automatically adjust the address of posted images.';
$lang['FixingPostsInProgress'] = 'Fixing posts in progress';
$lang['FixingPostsInProgressRedirect'] = 'You will be automatically redirected to next step in three seconds';
$lang['FixingPostsInProgressRedirectClick'] = 'If you are not automatically redirected within three seconds you may click %sHERE%s';
$lang['FixingPostsFrom'] = 'Posts modified this step from %s to %s';
$lang['FixingPostsTotal'] = '%s posts of %s modified so far';
$lang['FixingPostsModified'] = ' posts fixed';
$lang['FixingPostsComplete'] = 'Fixing posts complete';
$lang['SearchWhat'] = 'Search what';
$lang['ReplaceWith'] = 'Replace with';
$lang['PostsPerStep'] = 'Number of posts per step';
$lang['StartFrom'] = 'Start from post';
$lang['RemoveBBCodeUID'] = 'Remove BBCode UID (get it from posts table)';
$lang['RemoveBBCodeUID_Guess'] = 'Try to guess and remove BBCode UID';
$lang['FixPostedImagesPaths'] = 'Fix all posted images paths (adjust paths to reflect users subfolders)';

$lang['FixPics'] = 'Fix Album Pics Paths';
$lang['FixPicsExplain'] = 'This feature will move all album pics from the main folder into users subfolders and will also update the database with the new paths';
$lang['FixingPicsInProgress'] = 'Fixing pics in progress';
$lang['FixingPicsInProgressRedirect'] = 'You will be automatically redirected to next step in three seconds';
$lang['FixingPicsInProgressRedirectClick'] = 'If you are not automatically redirected within three seconds you may click %sHERE%s';
$lang['FixingPicsFrom'] = 'Pics modified this step from %s to %s';
$lang['FixingPicsTotal'] = '%s pics of %s modified so far';
$lang['FixingPicsModified'] = ' pics fixed';
$lang['FixingPicsComplete'] = 'Fixing pics complete';
$lang['PicStartFrom'] = 'Start from pic';
$lang['PicsPerStep'] = 'Number of pics per step';

$lang['RenMovePics'] = 'Rename And Move Posted Pics';
$lang['RenMovePicsExplain'] = 'This feature will rename and move all posted pics from the main folder into users subfolders: you will then need to update posts table using the <i>Fix Posts</i> function to adjust all paths in posts';

$lang['BBC_IP_CREDITS_STATIC'] = '
<a href="http://www.icyphoenix.com" title="Icy Phoenix"><img src="./style/icy_phoenix_small.png" alt="Icy Phoenix" title="Icy Phoenix" /></a><br />
<span style="color: #FF5500;"><b>Mighty Gorgon</b></span><br />
<i>(Luca Libralato)</i><br />
<b><i>Developer</i></b><br />
Interests: Heroes Of Might And Magic III, 69, #FF5522<br />
Location: Homer\'s Head<br />
<br />
<br />
<span style="color: #DD2222;"><b>hpl</b></span><br />
<i>(Alessandro Drago)</i><br />
<b><i>Developer</i></b><br />
Interests: CMS, little animals<br />
Location: Global Header<br />
<br />
<br />
<span style="color: #DD2222;"><b>Bicet</b></span><br />
<b><i>phpBB XS Developer</i></b><br />
<br />
<br />
<b><i>Valued Contributors</i></b><br />
<span style="color: #228844;"><b>Andrea75</b></span><br />
<span style="color: #DD2222;"><b>Artie</b></span><br />
<span style="color: #228844;"><b>buldo</b></span><br />
<span style="color: #228844;"><b>casimedicos</b></span><br />
<span style="color: #DD2222;"><b>CyberAlien</b></span><br />
<span style="color: #800080;"><b>darkone</b></span><br />
<span style="color: #228844;"><b>difus</b></span><br />
<span style="color: #800080;"><b>fare85</b></span><br />
<span style="color: #228844;"><b>fracs</b></span><br />
<span style="color: #800080;"><b>ganesh</b></span><br />
<span style="color: #228844;"><b>JANU1535</b></span><br />
<span style="color: #800080;"><b>jz</b></span><br />
<span style="color: #228844;"><b>KasLimon</b></span><br />
<span style="color: #AAFF00;"><b>KugeLSichA</b></span><br />
<span style="color: #228844;"><b>Lopalong</b></span><br />
<span style="color: #228844;"><b>moreteavicar</b></span><br />
<span style="color: #228844;"><b>Nikola</b></span><br />
<span style="color: #228844;"><b>novice programmer</b></span><br />
<span style="color: #228844;"><b>ThE KuKa</b></span><br />
<span style="color: #FF7700;"><b>TheSteffen</b></span><br />
<span style="color: #0000BB;"><b>Tom</b></span><br />
<span style="color: #228844;"><b>z3d0</b></span><br />
<span style="color: #228844;"><b>Zuker</b></span><br />
<br />
Interests: Icy Phoenix<br />
Location: <a href="http://www.icyphoenix.com/">http://www.icyphoenix.com</a>
';

$lang['BBC_IP_CREDITS'] = '<div class="center-block"><marquee behavior="scroll" direction="up" scrolldelay="120">' . $lang['BBC_IP_CREDITS_STATIC'] . '</marquee></div>';

?>