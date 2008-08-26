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
* Lopalong
*
*/

// MG CMS - BEGIN
// Some langs borrowed from IM Portal
$lang['CMS_WELCOME'] = 'Welcome in Icy Phoenix';
$lang['BP_Title'] = 'Blocks Position Tag';
$lang['BP_Explain'] = 'Add, edit or delete blocks position that can be used in site pages. The default positions are \'header\', \'footer\', \'right\', \'left\' and \'centre\'. These positions correspond to the layout being used for a specific site page. Only existing positions per site page must be added here. Position keys that are not existing in the specified layout will not appear in the site page. Each position tag key and character must be unique per site page.';
$lang['BP_Position'] = 'Position character';
$lang['BP_Key'] = 'Position Tag Key';
$lang['BP_Layout'] = 'Site Page';
$lang['BP_Add_Position'] = 'Add New Position';
$lang['No_bp_selected'] = 'No position selected for editing';
$lang['BP_Edit_Position'] = 'Edit block position';
$lang['Must_enter_bp'] = 'You must enter a position tag key, position character and site page';
$lang['BP_updated'] = 'Block position updated';
$lang['BP_added'] = 'Block position added';
$lang['Click_return_bpadmin'] = 'Click %sHere%s to return to Blocks Position Administration';
$lang['BP_removed'] = 'Block position removed';
$lang['Portal_wide'] = 'Global Blocks';

$lang['No_layout_selected'] = 'No site page selected for editing';
$lang['Layout_Title'] = 'Site Pages';
$lang['Layout_Explain'] = 'Add, edit or delete layout information for your site pages. Multiple site pages can use the same layout. The layout template file selected must reside in the layout directory under your forum template directory. You are not allowed to delete the default site page. Deleting a site page also deletes the corresponding block positions for that page and all the blocks assigned to it. You can also edit the blocks assigned to each page and create new pages from scratch even with a new physical name (if your server allows it you can create for example a new page called <b>mypage.php</b>).';
$lang['Layout_Special_Explain'] = 'Add, edit or delete blocks for standard pages of your site (i.e.: forum, view topic, memberlist, downloads, album, etc.).';
$lang['Layout_Name'] = 'Name';
$lang['Layout_Template'] = 'Template File';
$lang['Layout_Edit'] = 'Edit site page';
$lang['Layout_Page'] = 'Page ID';
$lang['Layout_View'] = 'View permissions';
$lang['Layout_Edit_Perm'] = 'Edit permissions';
$lang['Layout_Global_Blocks'] = 'Show Global Blocks';
$lang['Layout_Page_Nav'] = 'Page Navigation Block (Breadcrumbs)';
$lang['Must_enter_layout'] = 'You must enter a name and a template file';
$lang['Layout_updated'] = 'Site Page Updated';
$lang['Click_return_layoutadmin'] = 'Click %sHere%s to return to Site Page Administration';
$lang['Layout_added'] = 'Site Page added';
$lang['Layout_removed'] = 'Site Page removed';
$lang['Layout_Add'] = 'Add Site Page';
$lang['Layout_BP_added'] = 'Layout Config file available: Block Position Tags automatically inserted';
$lang['Layout_default'] = 'Default';
$lang['Layout_make_default'] = 'Make Default';

$lang['Blocks_Title'] = 'Blocks Management';
$lang['Blocks_Explain'] = 'Add, edit, delete and move blocks for each available site page. A block template must exist for every block file added. If you want to add a text only block (with BBCodes or HTML) you should not choose any block file and then you\'ll be redirected to the editor page. Blocks creation/edit setup is split among two pages: the first where you will be able to choose the general settings for the block, and a second page where you will be able to set the block specific variables.';
$lang['Blocks_Duplicate_Explain'] = 'Duplicate blocks from other layouts. Only blocks compatible with selected layout will be shown. To duplicate blocks you have just to tick the box for each block you want to add to the current template and then click the button "Duplicate Blocks" located at page bottom.';
$lang['Blocks_Creation_01'] = 'Add/Edit Block Page 1 of 2';
$lang['Blocks_Creation_02'] = 'Add/Edit Block Page 2 of 2';
$lang['Standard_Pages'] = 'Standard Pages';
$lang['Custom_Pages'] = 'CMS Pages';
$lang['Custom_Pages_ADV'] = 'CMS Pages ADV';
$lang['Choose_Layout'] = 'Choose Page';
$lang['B_Title'] = 'Title';
$lang['B_Position'] = 'Position';
$lang['B_Active'] = 'Status';
$lang['B_Display'] = 'Content';
$lang['B_HTML'] = 'HTML';
$lang['B_BBCode'] = 'BBCode';
$lang['B_Type'] = 'Type';
$lang['B_Border'] = 'Show Border';
$lang['B_Border_and_Background'] = 'Border and Background';
$lang['B_Titlebar'] = 'Show Titlebar';
$lang['B_Titlebar_Content'] = 'Titlebar';
$lang['B_Background'] = 'Show Background';
$lang['B_Local'] = 'Localize Titlebar';
$lang['B_Cache'] = 'Cache?';
$lang['B_Cachetime'] = 'Cache Duration';
$lang['B_Groups'] = 'Usergroups';
$lang['B_All'] = 'All';
$lang['B_Guests'] = 'Guests Only';
$lang['B_Reg'] = 'Registered Users';
$lang['B_Mod'] = 'Moderators';
$lang['B_Admin'] = 'Administrators';
$lang['B_None'] = 'None';
$lang['B_Layout'] = 'Site Page';
$lang['B_Page'] = 'Page ID';
$lang['B_Add'] = 'Add Block';
$lang['B_Duplicate'] = 'Duplicate Blocks';
$lang['B_Update'] = 'Update Blocks';
$lang['Yes'] = 'Yes';
$lang['No'] = 'No';
$lang['Enabled'] = 'Enabled';
$lang['Disabled'] = 'Disabled';
$lang['B_Text'] = 'Text';
$lang['B_File'] = 'Block File';
$lang['B_Move_Up'] = 'Move Up';
$lang['B_Move_Down'] = 'Move Down';
$lang['B_View'] = 'View By';
$lang['B_Text_Block'] = 'Text or HTML block';
$lang['No_blocks_selected'] = 'No block file selected';
$lang['B_Content'] = 'Content';
$lang['B_Blockfile'] = 'Block File';
$lang['Block_Edit'] = 'Block Edit';
$lang['Block_updated'] = 'Block updated';
$lang['Blocks_updated'] = 'Blocks updated';
$lang['Must_enter_block'] = 'You must enter a block title';
$lang['Block_added'] = 'Block added';
$lang['Blocks_added'] = 'Blocks added';
$lang['Blocks_duplicated'] = 'Blocks duplicated';
$lang['Click_return_blocksadmin'] = 'Click %sHere%s to return to Blocks Management';
$lang['Block_removed'] = 'Block removed';
$lang['B_BV_added'] = 'Block Config file available: Block Variables automatically inserted';

$lang['BV_Title'] = 'Blocks Variables';
$lang['BV_Explain'] = 'Add, edit or delete blocks config variables that are used in blocks in Site Pages. These variables can then be configured through the Home Page Configuration page to customize your portal.';
$lang['BV_Label'] = 'Field Label';
$lang['BV_Sub_Label'] = 'Field Info';
$lang['BV_Name'] = 'Config Name';
$lang['BV_Options'] = 'Options';
$lang['BV_Values'] = 'Field Values';
$lang['BV_Type'] = 'Control Type';
$lang['BV_Block'] = 'Block';
$lang['BV_Add_Variable'] = 'Add Block Variable';
$lang['No_bv_selected'] = 'No block variable to be configured, click on SUBMIT to save this block.';
$lang['BV_Edit_Variable'] = 'Edit block variable';
$lang['Must_enter_bv'] = 'You must enter a field label and config name';
$lang['BV_updated'] = 'Block variable updated';
$lang['BV_added'] = 'Block variable added';
$lang['Click_return_bvadmin'] = 'Click %sHere%s to return to Blocks Variables Administration';
$lang['Config_Name_Explain'] = 'Must have no space';
$lang['Field_Options_Explain'] = 'Mandatory for dropdown lists and<br />radio buttons (comma delimited).';
$lang['Field_Values_Explain'] = 'Mandatory for dropdown lists and<br />radio buttons (comma delimited).';
$lang['BV_removed'] = 'Block variable removed';

$lang['Config_updated'] = 'Configuration updated';
$lang['Click_return_config'] = 'Click %sHere%s to return to Configuration';
$lang['Portal_Config'] = 'Site Pages Configuration';
$lang['Portal_Explain'] = 'In page, you can set the configuration needed for your customized site pages. Block variables listed in this page can be created/updated in <b>Blocks Variables Configuration</b> page. Global blocks for standard pages may be enabled/disabled in <b>Page Permissions</b>.';
$lang['Portal_General_Config'] = 'General Configuration';
$lang['Default_Portal'] = 'Default Site Page';
$lang['Default_Portal_Explain'] = 'Homepage of the site';
$lang['Cache_Enabled'] = 'Enable cache system';
$lang['Cache_Enabled_Explain'] = 'For faster loading of site pages related information';
$lang['Confirm_delete_item'] = 'Are you sure you want to delete this item?';
$lang['Cache_cleared'] = 'Cache files removed';

$lang['cms_pos_header'] = 'Page Header';
$lang['cms_pos_headerleft'] = 'Left';
$lang['cms_pos_headercenter'] = 'Top/Centre';
$lang['cms_pos_footercenter'] = 'Bottom/Centre';
$lang['cms_pos_footerright'] = 'Right';
$lang['cms_pos_footer'] = 'Page Footer';
$lang['cms_pos_nav'] = 'Navigation Bar';
$lang['cms_pos_left'] = 'Left';
$lang['cms_pos_center'] = 'Centre';
$lang['cms_pos_right'] = 'Right';
$lang['cms_pos_xsnews'] = 'News (Centre)';
$lang['cms_pos_centerbottom'] = 'Bottom/Centre';
$lang['cms_pos_toprow'] = 'Top';
$lang['cms_pos_column1'] = 'Column 1 (Left)';
$lang['cms_pos_column2'] = 'Column 2 (Right)';
$lang['cms_pos_bottomrow'] = 'Bottom';
$lang['cms_pos_ghtop'] = 'Header Top';
$lang['cms_pos_ghbottom'] = 'Header Bottom';
$lang['cms_pos_ghleft'] = 'Header Centre (Left)';
$lang['cms_pos_ghright'] = 'Header Centre (Right)';
//$lang['cms_pos_'] = '';

$lang['cms_block_ads'] = 'Ads';
$lang['cms_block_ajax_shoutbox'] = 'AJAX Chat';
$lang['cms_block_album'] = 'Album';
$lang['cms_block_birthdays'] = 'Birthdays';
$lang['cms_block_calendar'] = 'Calendar';
//$lang['cms_block_center_downloads'] = 'Centre Downloads';
$lang['cms_block_center_downloads'] = 'Downloads';
$lang['cms_block_clock'] = 'Clock';
$lang['cms_block_donate'] = 'Donations';
$lang['cms_block_dyn_menu'] = 'Dynamic Menu';
//$lang['cms_block_dyn_menu'] = 'Quick Links';
$lang['cms_block_forum'] = 'News';
$lang['cms_block_forum_attach'] = 'Advanced News';
$lang['cms_block_forum_list'] = 'Forum List';
$lang['cms_block_full_search'] = 'Full Search';
$lang['cms_block_global_header'] = 'Global Header';
$lang['cms_block_global_header_simple'] = 'Global Header Simple';
$lang['cms_block_gsearch'] = 'Google Search';
$lang['cms_block_gsearch_hor'] = 'Google Search Horizontal';
$lang['cms_block_index'] = 'Site Map';
$lang['cms_block_jumpbox'] = 'Jumpbox';
$lang['cms_block_kb'] = 'Knowledge Base';
$lang['cms_block_links'] = 'Links';
$lang['cms_block_menu'] = 'Site Navigation';
$lang['cms_block_nav_header'] = 'Header';
$lang['cms_block_nav_links'] = 'Site Map';
$lang['cms_block_nav_logo'] = 'Logo';
$lang['cms_block_new_downloads'] = 'New Downloads';
$lang['cms_block_news'] = 'News';
$lang['cms_block_news_archive'] = 'News Archive';
$lang['cms_block_news_posters'] = 'News Posters';
$lang['cms_block_online_users'] = 'Who is Online';
$lang['cms_block_online_users2'] = 'Who is Online';
$lang['cms_block_paypal'] = 'PayPal';
$lang['cms_block_poll'] = 'Poll';
$lang['cms_block_random_attach'] = 'Random Attach';
$lang['cms_block_random_quote'] = 'Random Quote';
$lang['cms_block_random_topics'] = 'Random Topics';
$lang['cms_block_random_topics_ver'] = 'Random Topics';
$lang['cms_block_recent_articles'] = 'Recent Articles';
$lang['cms_block_recent_topics'] = 'Recent Topics';
$lang['cms_block_recent_topics_wide'] = 'Recent Topics';
$lang['cms_block_referers'] = 'Referrers';
$lang['cms_block_rss'] = 'RSS';
$lang['cms_block_search'] = 'Search';
$lang['cms_block_sec_menu'] = 'Extra Menu';
$lang['cms_block_shoutbox'] = 'Shoutbox';
$lang['cms_block_staff'] = 'Staff';
$lang['cms_block_statistics'] = 'Statistics';
$lang['cms_block_style'] = 'Style';
$lang['cms_block_top_downloads'] = 'Top Downloads';
$lang['cms_block_top_nav'] = 'Top Nav';
$lang['cms_block_top_posters'] = 'Top Posters';
$lang['cms_block_user_block'] = 'User Block';
$lang['cms_block_users_visited'] = 'Active Users';
$lang['cms_block_visit_counter'] = 'Visit Counter';
$lang['cms_block_welcome'] = 'Welcome';
$lang['cms_block_wordgraph'] = 'Tags';
$lang['cms_block_xs_news'] = 'News';
//$lang['cms_block_'] = '';

$lang['cms_var_cms_style'] = 'CMS Modern Style';
$lang['cms_var_cms_style_explain'] = 'Modern Style for CMS consists in a modern layout with top transparent menu, while classic style has side menu';
$lang['cms_var_header_width'] = 'Global Left Column Width';
$lang['cms_var_header_width_explain'] = 'Width of site-wide left column in pixels (only for global blocks where applicable)';
$lang['cms_var_footer_width'] = 'Global Right Column Width';
$lang['cms_var_footer_width_explain'] = 'Width of site-wide right column in pixels (only for global blocks where applicable)';
$lang['cms_var_md_cache_file_locking'] = 'Cache File Locking';
$lang['cms_var_md_cache_file_locking_explain'] = 'Can avoid cache corruption under bad circumstances';
$lang['cms_var_md_cache_write_control'] = 'Cache Write Control';
$lang['cms_var_md_cache_write_control_explain'] = 'Detect some corrupt cache files';
$lang['cms_var_md_cache_read_control'] = 'Cache Read Control';
$lang['cms_var_md_cache_read_control_explain'] = 'A control key is embeded in cache file';
$lang['cms_var_md_cache_read_type'] = 'Cache Read Control Type';
$lang['cms_var_md_cache_read_type_explain'] = 'Type of read control (only if read control is enabled)';
$lang['cms_var_md_cache_filename_protect'] = 'Cache File Name Protection';
$lang['cms_var_md_cache_filename_protect_explain'] = 'Encrypt cache file names for higher security';
$lang['cms_var_md_cache_serialize'] = 'Cache Automatic Serialization';
$lang['cms_var_md_cache_serialize_explain'] = 'Enable / disable automatic serialization';
//$lang['cms_var_'] = '';

$lang['CMS_Config_updated'] = 'Configuration Updated Successfully';
$lang['CMS_Click_return_config'] = 'Click %sHere%s to return to settings';
$lang['CMS_Click_return_cms'] = 'Click %sHere%s to return to CMS Management';
$lang['CMS_Title'] = 'CMS';
$lang['CMS_Management'] = 'CMS Management';
$lang['CMS_Config'] = 'CMS Configuration';
$lang['CMS_Guest'] = 'Guest';
$lang['CMS_Reg'] = 'Registered';
$lang['CMS_VIP'] = 'VIP';
$lang['CMS_Publisher'] = 'Publisher';
$lang['CMS_Reviewer'] = 'Reviewer';
$lang['CMS_Content_Manager'] = 'Content Manager';
$lang['CMS_ACP'] = 'Edit This Page';
$lang['CMS_Pages'] = 'Site Pages';
$lang['CMS_ID'] = 'ID';
$lang['CMS_Actions'] = 'Actions';
$lang['CMS_Layout'] = 'Layout';
$lang['CMS_Blocks'] = 'Blocks';
$lang['CMS_Name'] = 'Name';
$lang['CMS_Description'] = 'Description';
$lang['CMS_Filename'] = 'Filename';
$lang['CMS_Filename_Explain'] = 'If you specify a filename then a new page is created with the physical name you have chosen.';
$lang['CMS_Filename_Explain_OK'] = '<i>After a quick test it seems that your server allows automatic files creation, so the file should be created automatically.</i>';
$lang['CMS_Filename_Explain_NO'] = '<i>After a quick test it seems that your server <b>DOES NOT ALLOW</b> automatic files creation on your root. If you want to create a new page with a new physical name you should manually create the file on the server by duplicating <b>index_empty.php</b> and renaming it according to name you have chosen here. Alternatively you may assign the correct permissions to the root of your site so the procedure may run automatically.</i>';
$lang['CMS_Template'] = 'Template';
$lang['CMS_FileAlreadyExists'] = 'The file you are trying to create already exists. Please choose another filename.';
$lang['CMS_FileCreationSuccess'] = 'The new page has been automatically created.';
$lang['CMS_FileCreationError'] = 'The file cannot be automatically created.';
$lang['CMS_FileCreationManual'] = 'Please create a copy of index_empty.php, assign it the name you have inserted in the page creation form and upload it in your site root.';
$lang['CMS_Permissions'] = 'Permissions';
$lang['CMS_Global_Header'] = 'Global Header';
$lang['CMS_Global_Blocks'] = 'Global Blocks';
$lang['CMS_Edit'] = 'Edit';
$lang['CMS_Delete'] = 'Delete';
$lang['CMS_Preview'] = 'Preview';
$lang['CMS_Configure_Blocks'] = 'Configure Blocks';
$lang['CMS_Page_Permissions'] = 'Page Permissions';
$lang['CMS_Page_Permissions_Explain'] = 'Configure the user level required to view standard pages. For each page you can also choose if the global blocks should be displayed or not (for this feature Global Blocks should be enabled in <b>CMS Configuration</b>).';
$lang['CMS_Page'] = 'Page';
//$lang['CMS_'] = '';
// MG CMS - END

// MG CMS MENU - BEGIN
$lang['CMS_Menu_Page'] = 'Dynamic Menu';
$lang['CMS_Menu_Page_Explain'] = 'Create a customized menu to show in your CMS pages as a block. When creating a category or a link you can use language keys to assign names or specify your own name. You may even choose the permission level required for each link or whether the link should be opened in the same window or a new one.';

$lang['CMS_Menu_New_Menu'] = 'Create a new menu block';
$lang['CMS_Menu_New_cat'] = 'Create a new category';
$lang['CMS_Menu_New_link'] = 'Create a new link';
$lang['CMS_Menu_Edit_menu_links_button'] = 'Edit links in this menu';
$lang['CMS_Menu_Edit_cat_button'] = 'Edit category';
$lang['CMS_Menu_Edit_link_button'] = 'Edit link';
$lang['CMS_Menu_Set_auth'] = 'Permission';
$lang['CMS_Menu_New_menu_name'] = 'Menu name';
$lang['CMS_Menu_New_cat_name'] = 'Category name';
$lang['CMS_Menu_New_link_name'] = 'Link name';
$lang['CMS_Menu_Default_link'] = 'Default link (<b>*</b> will be ignored)';
$lang['CMS_Menu_No_default_link'] = 'No default link (Custom settings)';
$lang['CMS_Menu_New_menu_des'] = 'Menu description';
$lang['CMS_Menu_New_cat_des'] = 'Category description';
$lang['CMS_Menu_New_link_des'] = 'Link description';
$lang['CMS_Menu_Choose_cat'] = 'Choose category';
$lang['CMS_Menu_Choose_cat_desc'] = 'Choose the category where the link is related to.';
$lang['CMS_Menu_link_status'] = 'Status';
$lang['CMS_Menu_Icon'] = 'Icon';
$lang['CMS_Menu_No_Icon'] = 'No Icon';
$lang['CMS_Menu_Standard_Icon'] = 'Standard Icon';
$lang['CMS_Menu_link_external'] = 'External link';
$lang['CMS_Menu_New_link_name_key'] = 'Language var (specified in <b>lang_dyn_menu.php</b>)';
$lang['CMS_Menu_No_lang_key'] = 'No lang var (use the specified name)';
$lang['CMS_Menu_New_link_url'] = 'URL of the link';
$lang['CMS_Menu_New_cat_link_url'] = 'URL of the category (if no URL is entered, clicking on category link you can expand/collapse category items)';
$lang['CMS_Menu_Update'] = 'Update Menu';

$lang['Click_Return_CMS_Menu'] = 'Click %shere%s to return to the menu administration';

$lang['Menu_created'] = 'Menu successfully created.';
$lang['Cat_created'] = 'Category successfully created.';
$lang['Link_created'] = 'Link successfully created.';
$lang['Menu_updated'] = 'Menu successfully updated.';
$lang['Cat_updated'] = 'Category successfully updated.';
$lang['Link_updated'] = 'Link successfully updated.';
$lang['Menu_deleted'] = 'Menu successfully removed.';
$lang['Cat_deleted'] = 'Category successfully removed.';
$lang['Link_deleted'] = 'Link successfully removed.';

$lang['CMS_Menu_Not_Exist'] = 'This menu doesn\'t exist.';
$lang['CMS_Menu_Items_Not_Exist'] = 'This menu block doesn\'t have any category. Click on add to add one.';
$lang['CMS_Menu_No_Cats_Exist'] = 'This menu block doesn\'t have any category. Before creating a link you need to create a category.';
$lang['CMS_Menu_Item_Not_Exist'] = 'This menu item doesn\'t exist.';
$lang['CMS_Menu_Item_Add_Edit'] = 'Add/Edit Menu Item';
// MG CMS MENU - END

// CMS - ADV - BEGIN
$lang['CMS_ADV'] = 'CMS ADV';
$lang['CMS_LINKS'] = 'Links';
$lang['CMS_SETTINGS'] = 'Settings';
$lang['CMS_ADV_USERSLIST'] = 'CMS Users';
$lang['CMS_ADV_CUSTOM_PAGES'] = 'Light Pages';
$lang['CMS_ADV_DEFAULT_TEMPLATE'] = 'Default';
$lang['CMS_ADV_DEFAULT_TEMPLATE_OPTION'] = '-- Template default --';

$lang['BLOCK_MOVE'] = 'Move Block';
$lang['TURN_ACTIVE'] = 'Enable/Disable Block';
$lang['TURN_BORDER'] = 'Enable/Disable Border';
$lang['TURN_TITLEBAR'] = 'Enable/Disable Title Bar';
$lang['TURN_LOCAL'] = 'Enable/Disable Location Title';
$lang['TURN_BACKGROUND'] = 'Enable/Disable Background';

$lang['BLOCKS_POSITION_SAVE'] = 'Save blocks position';
$lang['BLOCKS_POSITION_UPDATED'] = 'Blocks location successfully updated';

$lang['INVALID_BLOCKS'] = 'Invalid blocks position';
// CMS - ADV - END

?>