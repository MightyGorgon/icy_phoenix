<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

$gen_simple_header = true;
$page_title = $lang['Smiley_creator'];
$meta_description = '';
$meta_keywords = '';
include(IP_ROOT_PATH . 'includes/page_header.' . PHP_EXT);
include(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_bbcb_mg.' . PHP_EXT);

if (isset($_GET['mode']))
{
	$mode = $_GET['mode'];
}

if($mode == 'text2shield')
{
	$anz_smilie = -1;
	$hdl = opendir('images/smiles/smiley_creator/');
	while($res = readdir($hdl))
	{
		if(strtolower(substr($res, (strlen($res) - 3), 3)) == 'png')
		{
			$anz_smilie++;
		}
	}
	closedir($hdl);

	$i = 1;
	$ii = 1;
	$smilies_wahl = '';
	$smilies_js = '';
	while($i <= $anz_smilie)
	{
		$smilies_wahl .= '<td><input type="radio" name="smiley" value="' . $i . '"><img src="images/smiles/smiley_creator/smilie' . $i . '.png" alt="" /></td>';
		$smilies_js .= 'if(document.shielderstellung.smiley[' . ($i - 1) . '].checked) var sm_id = document.shielderstellung.smiley[' . ($i - 1) . '].value;' . "\n";
		if($ii >= 5)
		{
			$smilies_wahl .= '</tr><tr>';
			$ii = 0;
		}
		$i++;
		$ii++;
	}
	//$smilies_js .= 'if(document.schilderstellung.smiley[' . ($i - 1) . '].checked) var sm_id = document.schilderstellung.smiley[' . ($i - 1) . '].value;' . "\n";
	//$smilies_js .= 'if(document.schilderstellung.smiley[' . $i . '].checked) var sm_id = document.schilderstellung.smiley[' . $i . '].value;' . "\n";
}

$template->set_filenames(array('body' => 'smiley_creator.tpl',));

$jumpbox = make_jumpbox($forum_id);
$template->assign_vars(array(
	'L_GO' => $lang['Go'],
	'SMILIES_WAHL' => $smilies_wahl,
	'SMILIES_JS' => $smilies_js,
	'L_SMILEY_CREATOR' => $lang['Smiley_creator'],
	'L_CREATE_SMILIE' => $lang['SC_create_smilie'],
	'L_STOP_CREATING' => $lang['SC_stop_creating'],
	'L_SHIELDSHADOW_ON' => $lang['SC_shieldshadow_on'],
	'L_SHIELDSHADOW_OFF' => $lang['SC_shieldshadow_off'],
	'L_SHIELDTEXT' => $lang['SC_shieldtext'],
	'L_SHADOWCOLOR' => $lang['SC_shadowcolor'],
	'L_SHIELDSHADOW' => $lang['SC_shieldshadow'],
	'L_SMILIECHOOSER' => $lang['SC_smiliechooser'],
	'L_RANDOM_SMILIE' => $lang['SC_random_smilie'],
	'L_DEFAULT_SMILIE' => $lang['SC_default_smilie'],
	'L_FONTCOLOR' => $lang['SC_fontcolor'],
	'L_COLOR_DEFAULT' => $lang['color_default'],
	'L_COLOR_DARK_RED' => $lang['color_dark_red'],
	'L_COLOR_RED' => $lang['color_red'],
	'L_COLOR_ORANGE' => $lang['color_orange'],
	'L_COLOR_BROWN' => $lang['color_brown'],
	'L_COLOR_YELLOW' => $lang['color_yellow'],
	'L_COLOR_GREEN' => $lang['color_green'],
	'L_COLOR_OLIVE' => $lang['color_olive'],
	'L_COLOR_CYAN' => $lang['color_cyan'],
	'L_COLOR_BLUE' => $lang['color_blue'],
	'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'],
	'L_COLOR_INDIGO' => $lang['color_indigo'],
	'L_COLOR_VIOLET' => $lang['color_violet'],
	'L_COLOR_WHITE' => $lang['color_white'],
	'L_COLOR_BLACK' => $lang['color_black'],
	'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
	'L_COLOR_CORAL' => $lang['color_coral'],
	'L_COLOR_CRIMSON' => $lang['color_crimson'],
	'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'],
	'L_COLOR_DARK_GREY' => $lang['color_dark_grey'],
	'L_COLOR_GOLD' => $lang['color_gold'],
	'L_COLOR_GRAY' => $lang['color_gray'],
	'L_COLOR_LIGHT_BLUE' => $lang['color_light_blue'],
	'L_COLOR_LIGHT_CYAN' => $lang['color_light_cyan'],
	'L_COLOR_LIGHT_GREEN' => $lang['color_light_green'],
	'L_COLOR_LIGHT_GREY' => $lang['color_light_grey'],
	'L_COLOR_LIGHT_ORANGE' => $lang['color_light_orange'],
	'L_COLOR_PEACH' => $lang['color_peach'],
	'L_COLOR_POWER_ORANGE' => $lang['color_power_orange'],
	'L_COLOR_SEA_GREEN' => $lang['color_sea_green'],
	'L_COLOR_SILVER' => $lang['color_silver'],
	'L_COLOR_TOMATO' => $lang['color_tomato'],
	'L_COLOR_TURQUOISE' => $lang['color_turquoise'],
	'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
	'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'],
	'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'],
	'L_COLOR_DARKGREEN' => $lang['color_darkgreen'],
	'L_JUMP_TO' => $lang['Jump_to'],
	'L_SELECT_FORUM' => $lang['Select_forum'],
	'L_ANOTHER_SHIELD' => $lang['SC_another_shield'],
	'L_NOTEXT_ERROR' => $lang['SC_notext_error'],

	'S_JUMPBOX_LIST' => $jumpbox,
	'S_JUMPBOX_ACTION' => append_sid(VIEWFORUM_MG)
	)
);
$template->assign_var_from_handle('JUMPBOX', 'jumpbox');

$template->pparse('body');

include(IP_ROOT_PATH . 'includes/page_tail.' . PHP_EXT);

?>