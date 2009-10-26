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

/**
* CMS class
*/
class ip_cms
{

	function cms_assign_var_from_handle($template_var, $handle)
	{
		ob_start();
		$template_var->pparse($handle);
		$str = ob_get_contents();
		ob_end_clean();
		return $str;
	}

	function cms_blocks_view()
	{
		global $userdata, $config;

		$is_reg = (($config['bots_reg_auth'] && $userdata['is_bot']) || $userdata['session_logged_in']) ? true : false;
		if (!$is_reg)
		{
			$result = array(0, 1);
		}
		else
		{
			// User is not a guest here...
			switch($userdata['user_level'])
			{
				case ADMIN:
					// If you want admin to see also GUEST ONLY blocks you need to use these settings...
					//$result = array(0, 1, 2, 3, 4);
					$result = array(0, 2, 3, 4);
					break;
				case MOD:
					$result = array(0, 2, 3);
					break;
				default:
					$result = array(0, 2);
					break;
			}
		}
		return $result;
	}

	function cms_groups($user_id)
	{
		global $db;
		static $layout_groups;

		if(!isset($layout_groups))
		{
			$sql = "SELECT group_id FROM " . USER_GROUP_TABLE . " WHERE user_id = '" . $user_id . "' AND user_pending = 0";
			$result = $db->sql_query($sql);

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
		global $db, $cache, $config, $template, $userdata, $lang, $bbcode;
		global $cms_config_vars, $cms_config_layouts, $cms_config_global_blocks, $block_id;

		if(!$is_special)
		{
			$id_var_name = 'l_id';
			$table_name = CMS_LAYOUT_TABLE;
			$field_name = 'lid';
			$empty_block_tpl = 'cms_block_inc_wrapper.tpl';
		}
		else
		{
			$id_var_name = 'ls_id';
			$table_name = CMS_LAYOUT_SPECIAL_TABLE;
			$field_name = 'lsid';
			$empty_block_tpl = 'cms_block_inc_wrapper.tpl';
			$layout = (isset($cms_config_layouts[$layout][$field_name]) ? $cms_config_layouts[$layout][$field_name] : 0);
		}

		if (!defined('CMS_BLOCKS_LANG_INCLUDED'))
		{
			$include_lang = $config['default_lang'];
			if(!@file_exists(IP_ROOT_PATH . 'language/lang_' . $include_lang . '/lang_blocks.' . PHP_EXT))
			{
				$include_lang = 'english';
			}
			include_once(IP_ROOT_PATH . 'language/lang_' . $include_lang . '/lang_blocks.' . PHP_EXT);
			define('CMS_BLOCKS_LANG_INCLUDED', true);
		}

		if(!$global_blocks && !$is_special)
		{
			$layout_pos = array();
			$sql_pos = "SELECT * FROM " . CMS_BLOCK_POSITION_TABLE . " WHERE layout = " . $layout;
			$block_pos_result = $db->sql_query($sql_pos, 0, 'cms_bp_', CMS_CACHE_FOLDER);

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

		$is_global_block = false;
		$is_gh_block = false;
		if(!$is_special && !$global_blocks)
		{
			$sql = "SELECT *
				FROM " . CMS_BLOCKS_TABLE . "
				WHERE layout = " . $layout . "
				AND active = 1
				AND " . $db->sql_in_set('view', $this->cms_blocks_view()) . "
				AND bposition NOT IN ('gh','gf','gt','gb','gl','gr','hh','hl','hc','fc','fr','ff')
				ORDER BY bposition ASC, layout ASC, layout_special ASC, weight ASC";
			$block_im_result = $db->sql_query($sql, 0, 'cms_blocks_', CMS_CACHE_FOLDER);

			$block_info = array();
			while ($row = $db->sql_fetchrow($block_im_result))
			{
				$block_info[] = $row;
			}
			$db->sql_freeresult($block_im_result);
		}
		else
		{
			switch ($type)
			{
				case 'gheader':
					$temp_pos = 'gh';
					break;
				case 'gfooter':
					$temp_pos = 'gf';
					break;
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
					$empty_block_tpl = 'cms_block_inc_wrapper_plain.tpl';
					$is_gh_block = true;
					break;
				case 'ghright':
					$temp_pos = 'gr';
					$empty_block_tpl = 'cms_block_inc_wrapper_plain.tpl';
					$is_gh_block = true;
					break;
				case 'header':
					$temp_pos = 'hh';
					break;
				case 'headerleft':
					$temp_pos = 'hl';
					$is_global_block = true;
					break;
				case 'headercenter':
					$temp_pos = 'hc';
					$is_global_block = true;
					break;
				case 'tailcenter':
					$temp_pos = 'fc';
					$is_global_block = true;
					break;
				case 'tailright':
					$temp_pos = 'fr';
					$is_global_block = true;
					break;
				case 'tail':
					$temp_pos = 'ff';
					break;
				default:
					$temp_pos = 'tt';
					break;
			}
			if ($is_special && !$global_blocks)
			{
				$sql_where = "AND layout_special = " . $layout;
				$check_array = array($layout);
			}
			elseif ($is_special && $global_blocks && ($layout != 0))
			{
				$sql_where = "AND layout_special IN(0, " . $layout . ")";
				$check_array = array(0, $layout);
			}
			else
			{
				$sql_where = "AND layout_special = 0";
				$check_array = array(0);
			}

			if (empty($cms_config_global_blocks))
			{
				$cms_config_global_blocks = $cache->obtain_cms_global_blocks_config(false);
			}
			$block_info = array();
			if (!empty($cms_config_global_blocks[$temp_pos]))
			{
				foreach ($cms_config_global_blocks[$temp_pos] as $row)
				{
					if (in_array($row['layout_special'], $check_array))
					{
						$block_info[] = $row;
					}
				}
			}
			/*
			$sql = "SELECT *
				FROM " . CMS_BLOCKS_TABLE . "
				WHERE layout = 0
				" . $sql_where . "
				AND active = 1
				AND " . $db->sql_in_set('view', $this->cms_blocks_view()) . "
				AND bposition = '" . $temp_pos . "'
				ORDER BY layout ASC, weight ASC";
			$block_im_result = $db->sql_query($sql, 0, 'cms_blocks_', CMS_CACHE_FOLDER);

			$block_info = array();
			while ($row = $db->sql_fetchrow($block_im_result))
			{
				$block_info[] = $row;
			}
			$db->sql_freeresult($block_im_result);
			*/
		}

		$block_count = sizeof($block_info);
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
				for ($i = 0; $i < sizeof($group_content); $i++)
				{
					if(in_array(intval($group_content[$i]), $this->cms_groups($userdata['user_id'])))
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

				$block_name = $block_info[$b_counter]['blockfile'];

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
					$output_block = $this->cms_assign_var_from_handle($template, $block_handle);
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
				$cms_block = $this->cms_assign_var_from_handle($template, $block_handle);
				$template->assign_block_vars($position_prefix . 'blocks_row', array(
					'CMS_BLOCK' => $cms_block,
					'OUTPUT' => $output_block
					)
				);

			}
		}
		return true;
	}
}

?>