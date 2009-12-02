<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

// CTracker_Ignore: File Checked By Human
define('IN_CMS', true);
define('CTRACKER_DISABLED', true);
define('IN_ICYPHOENIX', true);
if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
include(IP_ROOT_PATH . 'common.' . PHP_EXT);
$common_cms_template = IP_ROOT_PATH . 'templates/common/cms/';
include_once(IP_ROOT_PATH . 'includes/functions_cms_admin.' . PHP_EXT);

// Start session management
$userdata = session_pagestart($user_ip);
init_userprefs($userdata);
// End session management

include(IP_ROOT_PATH . 'includes/class_form.' . PHP_EXT);
$class_form = new class_form();

include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

setup_extra_lang(array('lang_admin', 'lang_cms', 'lang_blocks'));

// Define constant to keep page_header.php from sending headers
define('AJAX_HEADERS', true);
$useragent = (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT'));
$encoding_charset = (strpos($useragent, 'MSIE') ? $lang['ENCODING'] : $lang['ENCODING_ALT']);

// Send AJAX headers - this is to prevent browsers from caching possible error pages
AJAX_headers();
header('Content-Type: text/html; charset=' . $encoding_charset);

$mode_array = array('block_config');
$mode = request_var('mode', '');
$mode = (in_array($mode, $mode_array) ? $mode : false);

$action_array = array('edit');
$action = request_var('action', '');
$action = (in_array($action, $action_array) ? $action : false);

$b_id = (isset($_GET['b_id']) ? intval($_GET['b_id']) : (isset($_POST['b_id']) ? intval($_POST['b_id']) : false));
$b_id = ($b_id < 0) ? false : $b_id;

$blocks_dir = IP_ROOT_PATH . 'blocks/';

$blockfile = (isset($_GET['blockfile']) ? $_GET['blockfile'] : (isset($_POST['blockfile']) ? $_POST['blockfile'] : false));

if ($blockfile == '')
{
	// BBCBMG - BEGIN
	//$bbcbmg_in_acp = true;
	include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
	// BBCBMG - END
	// BBCBMG SMILEYS - BEGIN
	generate_smilies('inline');
	include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
	$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
	// BBCBMG SMILEYS - END
	if (($action == 'edit') && $b_id)
	{
		$b_info = get_block_info(CMS_BLOCKS_TABLE, $b_id);
		$b_type = $b_info['type'];
		$b_content = stripslashes(trim($b_info['content']));
		/*
		$html_find = array('€', '&euro;', '&#8364;');
		$html_replace = array('&#8364;', '&euro;', '&euro;');
		$b_content = str_replace($html_find, $html_replace, $b_content);
		*/
		$b_content = utf8_encode(htmlspecialchars($b_content));
		//$b_content = htmlentities($b_content, ENT_COMPAT, $encoding_charset);
		//$b_content = htmlspecialchars($b_content);
		//$b_content = $b_info['content'];
	}

	$template->assign_vars(array(
		'L_B_CONTENT' => $lang['B_Content'],
		'L_B_TYPE' => $lang['B_Type'],
		'L_B_HTML' => $lang['B_HTML'],
		'L_B_BBCODE' => $lang['B_BBCode'],
		'HTML' => (!$b_type) ? 'checked="checked"' : '',
		'BBCODE' => ($b_type) ? 'checked="checked"' : '',
		'CONTENT' => $b_content,
		)
	);

	$template_to_parse = CMS_TPL . 'ajax/cms_ajax_block_edit_text_body.tpl';
}
else
{
	$template_to_parse = CMS_TPL . 'ajax/cms_ajax_block_edit_body.tpl';

	if (($action == 'edit') && $b_id)
	{
		$sql = "SELECT * FROM " . CMS_CONFIG_TABLE . " AS c, " . CMS_BLOCK_VARIABLE_TABLE . " AS bv
							WHERE c.bid = '" . $b_id . "'
								AND bv.bid = '" . $b_id . "'
								AND c.config_name = bv.config_name
							ORDER BY c.id";
		$result = $db->sql_query($sql);

		$controltype = array('1' => 'textbox', '2' => 'dropdown list', '3' => 'radio buttons', '4' => 'checkbox');
		$rows_counter = 0;
		while($row = $db->sql_fetchrow($result))
		{
			$cms_field = array();
			$cms_field = create_cms_field($row);

			$default_portal[$cms_field[$row['config_name']]['name']] = $cms_field[$row['config_name']]['value'];

			if($cms_field[$row['config_name']]['type'] == '4')
			{
				$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? '1' : '0';
			}
			else
			{
				$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? $_POST[$cms_field[$row['config_name']]['name']] : $default_portal[$cms_field[$row['config_name']]['name']];
			}

			$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';

			$template->assign_block_vars('cms_block', array(
				'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
				'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ' ]</span>',
				'FIELD' => $cms_field[$row['config_name']]['output']
				)
			);
			$rows_counter++;
		}

		if ($rows_counter == 0)
		{
			$template->assign_block_vars('cms_no_bv', array(
				'L_NO_BV' => $lang['No_bv_selected'],
				)
			);
		}
		$db->sql_freeresult($result);

	}
	else
	{
		if(file_exists($blocks_dir . $blockfile . '.cfg'))
		{
			$block_count_variables = 0;
			include($blocks_dir . $blockfile . '.cfg');
			if ($block_count_variables > 0)
			{
				for($i = 0; $i < $block_count_variables; $i++)
				{
					$row = array(
						'config_name' => $block_variables[$i][2],
						'config_value' => $block_variables[$i][7],
						'label' => $block_variables[$i][0],
						'sub_label' => $block_variables[$i][1],
						'field_options' => $block_variables[$i][3],
						'field_values' => $block_variables[$i][4],
						'type' => $block_variables[$i][5],
						'block' => $block_variables[$i][6],
					);

					$cms_field = array();
					$cms_field = create_cms_field($row);

					$default_portal[$cms_field[$row['config_name']]['name']] = $cms_field[$row['config_name']]['value'];

					if($cms_field[$row['config_name']]['type'] == '4')
					{
						$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? '1' : '0';
					}
					else
					{
						$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? $_POST[$cms_field[$row['config_name']]['name']] : $default_portal[$cms_field[$row['config_name']]['name']];
					}

					$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';

					$template->assign_block_vars('cms_block', array(
						'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
						'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ' ]</span>',
						'FIELD' => $cms_field[$row['config_name']]['output']
						)
					);
				}
			}
			else
			{
				$template->assign_block_vars('cms_no_bv', array(
					'L_NO_BV' => $lang['No_bv_selected'],
					)
				);
			}
		}
		else
		{
			$template->assign_block_vars('cms_no_bv', array(
				'L_NO_BV' => $lang['No_bv_selected'],
				)
			);
		}
	}
}

$template->assign_vars(array(
	'MODE' => $mode,
	'BLOCKFILE' => $blockfile
	)
);

full_page_generation($template_to_parse, '', '', '');

?>