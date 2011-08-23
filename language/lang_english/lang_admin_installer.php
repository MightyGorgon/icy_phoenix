<?php
 if (!defined('IN_ICYPHOENIX'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = array();
}

$lang = array_merge($lang, array(
    'Uploader' => 'Icy-Pluggin & Template Uploader/Unzipper',
    'Select_Archive' => 'Select Zip Archive',
    'Upload_Zip' => 'Upload/Unzip',
    'Message' => 'The Archive was Uploaded and Unzipped',
    'Templates_Redirect_Click' => 'Click %sHere%s to go to Templates Configuracion',
    'Plugins_Redirect_Click' => 'Click %sHere%s to go to the Plugins Configuration',
    'Error_unzipping' => 'Sorry, there was an Error, when trying to unzip the Archive!',
    
    )
);    
    
$lang['3500_Plugins_Uploader'] = 'Plugins Uploader'; // admin_bbcodes.php
$lang['3510_Unzipper_Uploader'] = 'The Uploader-Installer'; // admin_replace.php
 
?>
