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
* OOHOO < webdev@phpbb-tw.net >
* Stefan2k1 and ddonker from www.portedmods.com
* CRLin from http://mail.dhjh.tcc.edu.tw/~gzqbyr/
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
	'Site_links' => 'Links',
	'Link_lock_submit_site' => 'Submit site was locked',
	'Link_title' => 'Site Name',
	'Link_desc' => 'Site Description',
	'Link_url' => 'Site URL',
	'Link_logo_src' => 'Site Logo (88x31 pixels, size no more than 10K), or leave blank:',
	'Link_logo_src1' => 'Site Logo (88x31 pixels, size no more than 10K):',
	'Links_Preview' => 'Preview',
	'Link_category' => 'Site Category',
	'link_hits' => 'Hits',
	'Link_us' => 'Link to ',
	'Link_us_explain' => 'Please feel free to link to <b>%s</b>. Use the following HTML:',
	'Link_us_syntax' => '<a href="%s" target="_blank"><img src="%s" width="%d" height="%d" alt="%s" /></a>',
	'Link_register' => 'Submit a site',
	'Link_register_guest_rule' => 'Log in as a registered user, then you can submit site.',
	'Link_register_rule' => 'You have to fill in the form below, and your site will be put into our database after validation.',
	'Link_pm_notify_subject' => 'Link added',
	'Link_pm_notify_message' => "\nLink %s added,\n please go to Links Management and validate it.",
	'Link_update_success' => 'Your information was submitted',
	'Link_update_fail' => 'Sorry!! Your information was unable to be submitted, please go back and try again',
	'Link_incomplete' => 'Sorry!! You did not complete the form, please go back and try again',
	'Link_intval_warning' => 'Sorry!! You can\'t submit several sites in succession, please try again later',
	'Click_return_links' => 'Click %sHere%s to return to Links Index',
	'Please_enter_your' => 'Please enter your ',
	'No_Logo_img' => '<span class="text_blue">&#8226;</span>', // You can edit color
	'No_Display_Links_Logo' => '<span class="text_red">&#8226;</span>', // Don't display Links logo
	'Links_home' => 'Links Home',
	'Search_site' => 'Search Site',
	'Search_site_title' => 'Search Site Name/Description:',
	'Descend_by_hits' => 'Descend By Hits',
	'Descend_by_joindate' => 'Descend By Date',
	'Logo' => 'Logo',
	'Site' => 'Site',
	'Link_ME' => 'Link to us',
	'Remember_Me' => 'Automatic Login',
	)
);

?>