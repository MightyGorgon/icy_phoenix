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
* AmigaLink
*
*/

// First we do the setmodules stuff for the admin cp.
if(defined('IN_ICYPHOENIX') && !empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['1000_Configuration']['145_Captcha_Config'] = $filename;

	return;
}
define('IN_ICYPHOENIX', true);

// Load default Header
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);

$captcha_config_array = array('enable_confirm', 'use_captcha', 'captcha_width', 'captcha_height', 'captcha_background_color', 'captcha_jpeg', 'captcha_jpeg_quality', 'captcha_pre_letters', 'captcha_pre_letters_great', 'captcha_font', 'captcha_chess', 'captcha_ellipses', 'captcha_arcs', 'captcha_lines', 'captcha_image', 'captcha_gammacorrect', 'captcha_foreground_lattice_x', 'captcha_foreground_lattice_y', 'captcha_lattice_color');

for($i = 0; $i < sizeof($captcha_config_array); $i++)
{
	$config_name = $captcha_config_array[$i];
	$config_value = trim($config[$captcha_config_array[$i]]);
	$new[$config_name] = request_post_var($config_name, $config_value, true);

	if(isset($_POST['submit']) && isset($_POST[$config_name]))
	{
		set_config($config_name, $new[$config_name], false);
	}
}

if(isset($_POST['submit']))
{
	$cache->destroy('config');

	$message = $lang['captcha_config_updated'] . '<br />' . sprintf($lang['Click_return_captcha_config'], '<a href="' . append_sid('admin_captcha_config.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>') . '<br /><br />';

	message_die(GENERAL_MESSAGE, $message);
}

$template->set_filenames(array('body' => ADM_TPL . 'admin_captcha_config.tpl'));

$template->assign_vars(array(
	'L_CAPTCHA_CONFIGURATION' => $lang['VC_Captcha_Config'],
	'L_CAPTCHA_CONFIGURATION_EXPLAIN' => $lang['captcha_config_explain'],
	'L_VC_ACTIVE' => ($config['enable_confirm']) ? $lang['VC_active'] : $lang['VC_inactive'],
	'L_BACKGROUND_CONFIG' => $lang['background_configs'],
	'L_RANDOM' => $lang['Random'],
	'L_DISABLED' => $lang['Disabled'],
	'L_ENABLED' => $lang['Enabled'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],

	'L_WIDTH' => $lang['CAPTCHA_width'],
	'L_HEIGHT' => $lang['CAPTCHA_height'],
	'L_BACKGROUND_COLOR' => $lang['background_color'],
	'L_BACKGROUND_COLOR_EXPLAIN' => $lang['background_color_explain'],
	'L_PRE_LETTERS' => $lang['pre_letters'],
	'L_PRE_LETTERS_EXPLAIN' => $lang['pre_letters_explain'],
	'L_GREAT_PRE_LETTERS' => $lang['great_pre_letters'],
	'L_GREAT_PRE_LETTERS_EXPLAIN' => $lang['great_pre_letters_explain'],
	'L_RND_FONT_PER_LETTER' => $lang['random_font_per_letter'],
	'L_RND_FONT_PER_LETTER_EXPLAIN' => $lang['random_font_per_letter_explain'],
	'L_ALLOW_CHESS' => $lang['back_chess'],
	'L_ALLOW_CHESS_EXPLAIN' => $lang['back_chess_explain'],
	'L_ALLOW_ELLIPSES' => $lang['back_ellipses'],
	'L_ALLOW_ARCS' => $lang['back_arcs'],
	'L_ALLOW_LINES' => $lang['back_lines'],
	'L_ALLOW_IMAGE' => $lang['back_image'],
	'L_ALLOW_IMAGE_EXPLAIN' => $lang['back_image_explain'],
	'L_FOREGROUND_LATTICE' => $lang['foreground_lattice'],
	'L_FOREGROUND_LATTICE_EXPLAIN' => $lang['foreground_lattice_explain'],
	'L_FOREGROUND_LATTICE_COLOR' => $lang['foreground_lattice_color'],
	'L_FOREGROUND_LATTICE_COLOR_EXPLAIN' => $lang['foreground_lattice_color_explain'],
	'L_GAMMACORRECT' => $lang['gammacorrect'],
	'L_GAMMACORRECT_EXPAIN' => $lang['gammacorrect_axplain'],
	'L_GENERATE_JPEG' => $lang['generate_jpeg'],
	'L_GENERATE_JPEG_EXPAIN' => $lang['generate_jpeg_explain'],
	'L_JPEG_QUALITY' => $lang['generate_jpeg_quality'],

	'WIDTH' => $new['captcha_width'],
	'HEIGHT' => $new['captcha_height'],
	'BACKGROUND_COLOR' => $new['captcha_background_color'],
	'PRE_LETTERS' => $new['captcha_pre_letters'],
	'LATTICE_X_LINES' => $new['captcha_foreground_lattice_x'],
	'LATTICE_Y_LINES' => $new['captcha_foreground_lattice_y'],
	'LATTICE_COLOR' => $new['captcha_lattice_color'],
	'GAMMACORRECT' => $new['captcha_gammacorrect'],
	'JPEG_QUALITY' => $new['captcha_jpeg_quality'],

	'CAPTCHA_IMG' => '<img src="' . append_sid(IP_ROOT_PATH . CMS_PAGE_PROFILE . '?mode=confirm&amp;confirm_id=Admin') . '" alt="" />',

	'L_ENABLE_CONFIRM' => $lang['Visual_confirm'],
	'L_ENABLE_CONFIRM_EXPLAIN' => $lang['Visual_confirm_explain'],
	'S_ENABLE_CONFIRM_YES' => ($new['enable_confirm'] == 1) ? 'checked="checked"' : '',
	'S_ENABLE_CONFIRM_NO' => ($new['enable_confirm'] == 0) ? 'checked="checked"' : '',
	'L_USE_CAPTCHA' => $lang['Use_Captcha'],
	'L_USE_CAPTCHA_EXPLAIN' => $lang['Use_Captcha_Explain'],
	'S_USE_CAPTCHA_YES' => ($new['use_captcha'] == 1) ? 'checked="checked"' : '',
	'S_USE_CAPTCHA_NO' => ($new['use_captcha'] == 0) ? 'checked="checked"' : '',

	'S_GREAT_PRE_LETTERS_YES' => ($new['captcha_pre_letters_great'] == 1) ? 'checked="checked"' : '',
	'S_GREAT_PRE_LETTERS_NO' => ($new['captcha_pre_letters_great'] == 0) ? 'checked="checked"' : '',
	'S_RND_FONT_PER_LETTER_YES' => ($new['captcha_font'] == 1) ? 'checked="checked"' : '',
	'S_RND_FONT_PER_LETTER_NO' => ($new['captcha_font'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_CHESS_YES' => ($new['captcha_chess'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_CHESS_NO' => ($new['captcha_chess'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_CHESS_RND' => ($new['captcha_chess'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_ELLIPSES_YES' => ($new['captcha_ellipses'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_ELLIPSES_NO' => ($new['captcha_ellipses'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_ELLIPSES_RND' => ($new['captcha_ellipses'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_ARCS_YES' => ($new['captcha_arcs'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_ARCS_NO' => ($new['captcha_arcs'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_ARCS_RND' => ($new['captcha_arcs'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_LINES_YES' => ($new['captcha_lines'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_LINES_NO' => ($new['captcha_lines'] == 0) ? 'checked="checked"' : '',
	'S_ALLOW_LINES_RND' => ($new['captcha_lines'] == 2) ? 'checked="checked"' : '',
	'S_ALLOW_IMAGE_YES' => ($new['captcha_image'] == 1) ? 'checked="checked"' : '',
	'S_ALLOW_IMAGE_NO' => ($new['captcha_image'] == 0) ? 'checked="checked"' : '',
	'S_JPEG_IMAGE_YES' => ($new['captcha_jpeg'] == 1) ? 'checked="checked"' : '',
	'S_JPEG_IMAGE_NO' => ($new['captcha_jpeg'] == 0) ? 'checked="checked"' : '',

	'S_HIDDEN_FIELDS' => '',
	'S_CAPTCHA_CONFIG_ACTION' => append_sid('admin_captcha_config.' . PHP_EXT)
	)
);

$template->pparse('body');

echo '<div align="center"><span class="copyright">Advanced Visual Confirmation &copy; 2006 <a href="http://www.amigalink.de" target="_blank">AmigaLink</a></span></div>';
include(IP_ROOT_PATH . ADM . '/page_footer_admin.' . PHP_EXT);

?>