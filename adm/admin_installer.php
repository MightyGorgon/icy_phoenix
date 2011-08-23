<?php
 /***************************************************************************
 *             admin_installer.php
 *                -------------------
 *   begin        : Tuesday, August 17, 2011
 *   copyright    : (C) 2011 Spydie 
 *   website      : http://www.icy-mods.com
 *   email        : spydie@icy-mods.com
 *   Version      : v.1.0
 *   note: removing the original copyright is illegal even you have modified
 *     the code.  Just append yours if you have modified it.
 ***************************************************************************/
 /***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
    define('IN_ICYPHOENIX', true);

    if(!empty($setmodules))
    {
    $file = basename(__FILE__);
    $module['3500_Plugins_Uploader']['3510_Unzipper_Uploader'] = $file;
    return;
    }
    // Load default Header
    if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
    if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
    require('pagestart.' . PHP_EXT);

    setup_extra_lang(array('lang_admin_installer'));
    //define the Uploader vars
    if(isset($_FILES['fupload'])) {
    $filename = $_FILES['fupload']['name'];
    $source = $_FILES['fupload']['tmp_name'];
    $type = $_FILES['fupload']['type'];

    $name = explode('.', $filename);

    // Ensures that the correct file was chosen
    $accepted_types = array('application/zip',
                                'application/x-zip-compressed',
                                'multipart/x-zip',
                                'application/s-compressed');

    foreach($accepted_types as $mime_type) {
        if($mime_type == $type) {
            $okay = true;
            break;
        }
    }
	
	//Safari and Chrome don't register zip mime types. Something better could be used here.
    $okay = strtolower($name[1]) == 'zip' ? true: false;

    if(!$okay) {
          die("Please choose a zip file, dummy!");
    }
    //Load the actual unzipper   
    require_once (IP_ROOT_PATH .'/includes/pclzip.lib.'.PHP_EXT);

                 $archive = new PclZip($source);
    if ($archive->extract(PCLZIP_OPT_PATH, '../', 
                        PCLZIP_OPT_REMOVE_PATH, $name[0].'/ip_root/') == 0) { 
    die("Error : ".$archive->errorInfo(true)); 
  }
  
    //show the OK message and ask what to do
    else
    {$message = $lang['Message'] . '<br /><br />';
     $message .= sprintf($lang['Plugins_Redirect_Click'], '<a href="' . append_sid('admin_plugins.' . PHP_EXT) . '">', '</a>') . '<br /><br />' .  sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>');
    message_die(GENERAL_MESSAGE, $message);}
}
    //send all to template
    $template->set_filenames(array('body' => ADM_TPL . 'admin_installer.tpl')); 
    $template->assign_vars(array(
            'L_UPLOADER' => $lang['Uploader'],
            'L_SELECT_ZIP' => $lang['Select_Archive'],
            'L_UPLOAD_ZIP' => $lang['Upload_Zip'],
            'L_MESSAGE' => $lang['Message'],
            
                        )
        );
	// footer
    $template->pparse('body');
    include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);
 
?>	