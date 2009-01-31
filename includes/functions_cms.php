<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

function cms_assign_var_from_handle($template_var, $handle)
{
	ob_start();
	$template_var->pparse($handle);
	$str = ob_get_contents();
	ob_end_clean();
	return $str;
}

function cms_config_init(&$cms_config_vars)
{
	global $db;

	$cms_config_vars = array();
	$sql = "SELECT bid, config_name, config_value
					FROM " . CMS_CONFIG_TABLE;
	if(!($result = $db->sql_query($sql, false, 'cms_config_')))
	{
		message_die(CRITICAL_ERROR, "Could not query portal config table", "", __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['bid'] > 0)
		{
			$cms_config_vars[$row['config_name']][$row['bid']] = $row['config_value'];
		}
		else
		{
			$cms_config_vars[$row['config_name']] = $row['config_value'];
		}
	}
	$db->sql_freeresult($result);
}

function cms_blocks_view($type = true)
{
	global $userdata, $board_config;

	$is_reg = ((($board_config['bots_reg_auth'] == true) && ($userdata['bot_id'] !== false)) || $userdata['session_logged_in']) ? true : false;
	if (!$is_reg)
	{
		$bview = '(0,1)';
		$append = '01';
	}
	else
	{
		$access_level = $is_reg ? USER : $userdata['user_level'];
		switch($access_level)
		{
			case USER:
				$bview = '(0,2)';
				$append = '02';
				break;
			case MOD:
				$bview = '(0,2,3)';
				$append = '023';
				break;
			case ADMIN:
				// If you want admin to see also GUEST ONLY blocks you need to use these settings...
				/*
				$bview = '(0,1,2,3,4)';
				$append = '01234';
				*/
				$bview = '(0,2,3,4)';
				$append = '0234';
				break;
			default:
				$bview = '(0)';
				$append = '0';
		}
	}
	$return_value = $type ? $bview : $append;
	return $return_value;
}

function cms_groups($user_id)
{
	global $db;
	static $layout_groups;

	if(!isset($layout_groups))
	{
		$sql = "SELECT group_id FROM " . USER_GROUP_TABLE . " WHERE user_id = '" . $user_id . "' AND user_pending = 0";
		if(!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, "Could not query user group information", "", __LINE__, __FILE__, $sql);
		}
		$layout_groups = array();
		$i = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			$layout_groups[$i] = intval($row['group_id']);
			$i++;
		}
		$db->sql_freeresult($result);
	}
	return $layout_groups;
}

function cms_parse_blocks($layout, $is_special = false, $global_blocks = false, $type = '')
{
	global $db, $template, $userdata, $board_config, $lang, $cms_config_vars, $bbcode, $block_id;

	if(!$is_special)
	{
		$id_var_name = 'l_id';
		$id_var_value = $layout;
		$table_name = CMS_LAYOUT_TABLE;
		$field_name = 'lid';
		$block_layout_field = 'layout';
		$layout_value = $id_var_value;
		$layout_special_value = 0;
		$empty_block_tpl = 'cms_block_inc_wrapper.tpl';
	}
	else
	{
		$id_var_name = 'ls_id';
		$id_var_value = $layout;
		$table_name = CMS_LAYOUT_SPECIAL_TABLE;
		$field_name = 'lsid';
		$block_layout_field = 'layout_special';
		$layout_value = 0;
		$layout_special_value = $id_var_value;
		$empty_block_tpl = 'cms_block_inc_wrapper.tpl';
	}

	if(!file_exists(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_blocks.' . PHP_EXT))
	{
		$board_config['default_lang'] = 'english';
	}
	include_once(IP_ROOT_PATH . 'language/lang_' . $board_config['default_lang'] . '/lang_blocks.' . PHP_EXT);

	if(!$global_blocks && !$is_special)
	{
		$layout_pos = array();
		$sql_pos = "SELECT * FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = '" . $layout . "'";
		if(!($block_pos_result = $db->sql_query($sql_pos, false, 'cms_bp_')))
		{
			message_die(CRITICAL_ERROR, "Could not query portal blocks position", "", __LINE__, __FILE__, $sql);
		}
		while ($block_pos_row = $db->sql_fetchrow($block_pos_result))
		{
			$layout_pos[$block_pos_row['bposition']] = $block_pos_row['pkey'];
		}
		$db->sql_freeresult($block_pos_result);
	}

	$block_info = array();
	if($is_special || $global_blocks)
	{
		$temp_type = $type;
	}
	else
	{
		$temp_type = 's' . strval($layout);
	}

	if(!$is_special && !$global_blocks)
	{
		$sql = "SELECT *
			FROM " . CMS_BLOCKS_TABLE . "
			WHERE layout = '" . $layout . "'
			AND active = '1'
			AND view IN " . cms_blocks_view() . "
			AND bposition NOT IN ('gt','gb','gl','gr','hh','hl','hc','fc','fr','ff')
			ORDER BY bposition ASC, weight ASC";
	}
	else
	{
		if ($is_special && !$global_blocks)
		{
			$sql_where = "AND layout_special = '" . $layout . "'";
		}
		elseif ($is_special && $global_blocks && ($layout != 0))
		{
			$sql_where = "AND layout_special IN('0', '" . $layout . "')";
		}
		else
		{
			$sql_where = "AND layout_special = '0'";
		}
		$is_global_block = false;
		$is_gh_block = false;
		switch ($type)
		{
			case 'ghtop':
				$temp_pos = 'gt';
				$empty_block_tpl = 'cms_block_inc_wrapper_buttons.tpl';
				$is_gh_block = true;
				break;
			case 'ghbottom':
				$temp_pos = 'gb';
				$empty_block_tpl = 'cms_block_inc_wrapper_buttons.tpl';
				$is_gh_block = true;
				break;
			case 'ghleft':
				$temp_pos = 'gl';
				$is_gh_block = true;
				$empty_block_tpl = 'cms_block_inc_wrapper_plain.tpl';
				break;
			case 'ghright':
				$temp_pos = 'gr';
				$is_gh_block = true;
				$empty_block_tpl = 'cms_block_inc_wrapper_plain.tpl';
				break;
			case 'header':
				$temp_pos = 'hh';
				break;
			case 'headerleft':
				$is_global_block = true;
				$temp_pos = 'hl';
				break;
			case 'headercenter':
				$is_global_block = true;
				$temp_pos = 'hc';
				break;
			case 'tailcenter':
				$is_global_block = true;
				$temp_pos = 'fc';
				break;
			case 'tailright':
				$is_global_block = true;
				$temp_pos = 'fr';
				break;
			case 'tail':
				$temp_pos = 'ff';
				break;
			default:
				$temp_pos = 'tt';
				break;
		}
		$sql = "SELECT *
			FROM " . CMS_BLOCKS_TABLE . "
			WHERE layout = '0'
			" . $sql_where . "
			AND active = '1'
			AND view IN " . cms_blocks_view() . "
			AND bposition = '" . $temp_pos . "'
			ORDER BY layout ASC, weight ASC";
	}
	if(!($block_im_result = $db->sql_query($sql, false, 'cms_blocks_')))
	{
		message_die(CRITICAL_ERROR, "Could not query portal blocks information", "", __LINE__, __FILE__, $sql);
	}
	$block_info = array();
	while ($row = $db->sql_fetchrow($block_im_result))
	{
		$block_info[] = $row;
	}
	$db->sql_freeresult($block_im_result);

	$block_count = count($block_info);
	if (($is_global_block || $is_gh_block) && ($block_count == 0))
	{
		return false;
	}

	for ($b_counter = 0; $b_counter < $block_count; $b_counter++)
	{
		$block_id = $block_info[$b_counter]['bid'];
		$is_group_allowed = true;
		if(!empty($block_info[$b_counter]['groups']))
		{
			$is_group_allowed = false;
			$group_content = explode(',', $block_info[$b_counter]['groups']);
			for ($i = 0; $i < count($group_content); $i++)
			{
				if(in_array(intval($group_content[$i]), cms_groups($userdata['user_id'])))
				{
					$is_group_allowed = true;
				}
			}
		}

		if($is_group_allowed)
		{
			if($is_special || $global_blocks)
			{
				$position = $type;
			}
			else
			{
				$position = $layout_pos[$block_info[$b_counter]['bposition']];
			}
			$position_prefix = $position . '_';

			$block_name = ereg_replace('blocks_imp_', '', $block_info[$b_counter]['blockfile']);

			if(($block_info[$b_counter]['local'] == 1) && !empty($lang['Title_' . $block_name]))
			{
				$title_string = $lang['Title_' . $block_name];
			}
			else
			{
				$title_string = stripslashes($block_info[$b_counter]['title']);
			}

			if(!empty($block_info[$b_counter]['blockfile']))
			{
				$block_handle = $block_name . '_block_' . $block_info[$b_counter]['bid'];
				$template->set_filenames(array($block_handle => 'blocks/' . $block_name . '_block.tpl'));
				$output_block = '';
				include(IP_ROOT_PATH . 'blocks/' . $block_info[$b_counter]['blockfile'] . '.' . PHP_EXT);
				$output_block = cms_assign_var_from_handle($template, $block_handle);
			}
			else
			{
				$message = stripslashes($block_info[$b_counter]['content']);
				if($block_info[$b_counter]['type'] == true)
				{
					@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
					$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
					//$bbcode->allow_html = true;
					$bbcode->allow_html = false;
					$bbcode->allow_bbcode = true;
					$bbcode->allow_smilies = true;
					$message = $bbcode->parse($message);
					//$message = str_replace("\n", "\n<br />\n", $message);
					$message = '<div class="post-text">' . $message . '</div>';
				}
				else
				{
					// You shouldn't convert NEW LINES to <br /> because you are parsing HTML, so linebreaks must be inserted as <br />
					// If you want linebreaks to be converted automatically, just decomment this line.
					//$message = str_replace("\n", "\n<br />\n", $message);
				}
				$output_block = $message;
			}

			$block_handle = 'block_' . $block_info[$b_counter]['bid'];
			$template->set_filenames(array($block_handle => $empty_block_tpl));
			$template->assign_vars(array(
				'POSITION' => $position,
				'OUTPUT' => $output_block,
				'TITLE_CONTENT' => (($title_string == '') ? '&nbsp;' : $title_string),
				'TITLE' => (($block_info[$b_counter]['titlebar'] == 1) ? true : false),
				'BORDER' => (($block_info[$b_counter]['border'] == 1) ? true : false),
				'BACKGROUND' => (($block_info[$b_counter]['background'] == 1) ? true : false),
				)
			);
			$cms_block = cms_assign_var_from_handle($template, $block_handle);
			$template->assign_block_vars($position_prefix . 'blocks_row', array(
				'CMS_BLOCK' => $cms_block,
				'OUTPUT' => $output_block
				)
			);

		}
	}
	return true;
}

?>