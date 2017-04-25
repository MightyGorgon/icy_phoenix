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

	var $tables = array();

	/*
	* Initialize variables
	*/
	function init_vars()
	{
		if (defined('IN_CMS_USERS'))
		{
			$this->tables = array(
				'blocks_table' => CMS_USERS_BLOCKS_TABLE,
				'block_settings_table' => CMS_USERS_BLOCK_SETTINGS_TABLE,
				'block_position_table' => CMS_USERS_BLOCK_POSITION_TABLE,
				'block_config_table' => CMS_USERS_CONFIG_TABLE,
				'block_variable_table' => CMS_USERS_BLOCK_VARIABLE_TABLE,
				'layout_table' => CMS_USERS_LAYOUT_TABLE,
			);
		}
		else
		{
			$this->tables = array(
				'blocks_table' => CMS_BLOCKS_TABLE,
				'block_settings_table' => CMS_BLOCK_SETTINGS_TABLE,
				'block_position_table' => CMS_BLOCK_POSITION_TABLE,
				'block_config_table' => CMS_CONFIG_TABLE,
				'block_variable_table' => CMS_BLOCK_VARIABLE_TABLE,
				'layout_table' => CMS_LAYOUT_TABLE,
				'layout_special_table' => CMS_LAYOUT_SPECIAL_TABLE,
			);
		}

		return true;
	}

	/*
	* Checks if the user is allowed to view the element
	*/
	function cms_auth_view()
	{
		global $user, $config;

		/*
		* Move these to constants if you want to use...
		* define('CMS_AUTH_ALL', 0); // Everyone
		* define('CMS_AUTH_GUESTS_ONLY', 1); // Guests Only (Registered won't see this!)
		* define('CMS_AUTH_REG', 2); // Registered Users Only
		* define('CMS_AUTH_MOD', 3); // Moderators And Admins
		* define('CMS_AUTH_ADMIN', 4); // Admins Only
		* define('CMS_AUTH_FOUNDER', 5); // Founders Only (NOT USED)
		* define('CMS_AUTH_ALL_NO_BOTS', 8); // Everyone but BOTs
		*/

		if (empty($user->data['session_logged_in']))
		{
			if ($user->data['is_bot'])
			{
				$result = (!empty($config['bots_reg_auth']) ? array(0, 1, 2) :  array(0, 1));
			}
			else
			{
				$result = array(0, 1, 8);
			}
		}
		else
		{
			// User is not a guest here...
			switch($user->data['user_level'])
			{
				case ADMIN:
					// If you want admin to see also GUEST ONLY blocks you need to use these settings...
					//$result = array(0, 1, 2, 3, 4, 5, 8);
					$result = array(0, 2, 3, 4, 5, 8);
					break;
				case MOD:
					$result = array(0, 2, 3, 8);
					break;
				default:
					$result = array(0, 2, 8);
					break;
			}
		}
		return $result;
	}

	/*
	* Creates a list of all groups
	*/
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

	/*
	* Blocks parsing function
	*/
	function cms_parse_blocks($layout, $is_special = false, $global_blocks = false, $type = '')
	{
		global $db, $cache, $config, $auth, $user, $lang, $bbcode, $template;
		global $class_plugins;
		global $cms_config_vars, $cms_config_layouts, $cms_config_global_blocks, $block_id;

		// Let's remove $auth->acl_get('a_') until I finish coding permissions properly... and also add/remove 'a_' when users are added/removed from administrators in ACP
		//$is_admin = (($user->data['user_level'] == ADMIN) || $auth->acl_get('a_')) ? true : false;
		$is_admin = ($user->data['user_level'] == ADMIN) ? true : false;

		$empty_block_tpl = 'cms_block_inc_wrapper.tpl';
		if(!$is_special)
		{
			$id_var_name = 'l_id';
			$table_name = $this->tables['layout_table'];
			$field_name = 'lid';
		}
		else
		{
			$id_var_name = 'ls_id';
			$table_name = $this->tables['layout_special_table'];
			$field_name = 'lsid';
			$layout = (isset($cms_config_layouts[$layout][$field_name]) ? $cms_config_layouts[$layout][$field_name] : 0);
		}

		if (!defined('CMS_BLOCKS_LANG_INCLUDED'))
		{
			// We add lang_user_created again here to make sure we override lang_blocks var with customized ones without having to edit lang_blocks directly...
			setup_extra_lang(array('lang_blocks', 'lang_user_created'));
			define('CMS_BLOCKS_LANG_INCLUDED', true);
		}

		if(!$global_blocks && !$is_special)
		{
			$layout_pos = array();
			$sql_pos = "SELECT * FROM " . $this->tables['block_position_table'] . " WHERE layout = " . $layout;
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
			if (!empty($config['cms_version']))
			{
				$sql = "SELECT b.*, s.*
					FROM " . $this->tables['blocks_table'] . " AS b,
					" . $this->tables['block_settings_table'] . " AS s
					WHERE b.layout = " . $layout . "
					AND b.active = 1
					AND " . $db->sql_in_set('s.view', $this->cms_auth_view()) . "
					AND b.bposition NOT IN ('gh','gf','gt','gb','gl','gr','hh','hl','hc','fc','fr','ff')
					AND b.bs_id = s.bs_id
					ORDER BY b.bposition ASC, b.layout ASC, b.layout_special ASC, b.weight ASC";
			}
			else
			{
				$sql = "SELECT *
					FROM " . $this->tables['blocks_table'] . "
					WHERE layout = " . $layout . "
					AND active = 1
					AND " . $db->sql_in_set('view', $this->cms_auth_view()) . "
					AND bposition NOT IN ('gh','gf','gt','gb','gl','gr','hh','hl','hc','fc','fr','ff')
					ORDER BY bposition ASC, layout ASC, layout_special ASC, weight ASC";
			}
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
			$config['cms_block_pos'] = $temp_pos;
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
				AND " . $db->sql_in_set('view', $this->cms_auth_view()) . "
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
			// We cannot use 'bid' anymore since now blocks settings are identified by 'bs_id'
			//$block_id = $block_info[$b_counter]['bid'];
			$block_id = $block_info[$b_counter]['bs_id'];
			$is_group_allowed = true;
			if(!empty($block_info[$b_counter]['groups']))
			{
				$is_group_allowed = false;
				$group_content = explode(',', $block_info[$b_counter]['groups']);
				for ($i = 0; $i < sizeof($group_content); $i++)
				{
					if(in_array(intval($group_content[$i]), $this->cms_groups($user->data['user_id'])))
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

				if(($block_info[$b_counter]['local'] == 1) && !empty($lang['cms_block_' . $block_name]))
				{
					$title_string = $lang['cms_block_' . $block_name];
				}
				else
				{
					$title_string = $block_info[$b_counter]['title'];
				}

				$content_type = 'block';
				if(!empty($block_info[$b_counter]['blockfile']))
				{
					$block_handle = $block_name . '_block_' . $block_info[$b_counter]['bid'];
					if (false !== strpos($block_name, '/'))
					{
						list($plugin_name, $block_name) = explode('/', $block_name);
						$plugin_config = $config['plugins'][$plugin_name];
						// do not render blocks from disabled plugins
						if (!$plugin_config['enabled'])
						{
							continue;
						}
						// Try to get the TPL path by "guessing" the constant.
						$tpl_constant_name = strtoupper($plugin_name) . '_TPL_PATH';
						if (defined($tpl_constant_name))
						{
							$tpl_dir = constant($tpl_constant_name);
						}
						else
						{
							$tpl_dir = IP_ROOT_PATH . PLUGINS_PATH . $plugin_config['dir'] . 'templates/';
						}
						$block_file = $class_plugins->get_tpl_file($tpl_dir, BLOCKS_DIR_NAME . $block_name . '_block.tpl');
						$block_php_file = IP_ROOT_PATH . PLUGINS_PATH . $plugin_config['dir'] . BLOCKS_DIR_NAME . $block_name;
					}
					else
					{
						$block_file = BLOCKS_DIR_NAME . $block_name . '_block.tpl';
						$block_php_file = IP_ROOT_PATH . 'blocks/' . $block_name;
					}
					$template->set_filenames(array($block_handle => $block_file));
					$output_block = '';
					include($block_php_file . '.' . PHP_EXT);
					$output_block = $template->get_var_from_handle($block_handle);
				}
				else
				{
					$content_type = 'text';
					$message = $block_info[$b_counter]['content'];
					if($block_info[$b_counter]['type'] == true)
					{
						if (!class_exists('bbcode') || empty($bbcode))
						{
							@include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
						}
						//$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
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

				$b_admin_vars = array();
				if ($is_admin || !empty($user->data['user_cms_auth']['cmsb_admin'][$block_id]))
				{
					$b_admin_vars = array(
						'B_ADMIN' => true,
						'B_EDIT_LINK' => append_sid(CMS_PAGE_CMS . '?mode=block_settings&amp;action=edit&amp;bs_id=' . $block_id . '&amp;sid=' . $user->data['session_id']),
					);
				}

				$block_handle = 'block_' . $block_info[$b_counter]['bid'];
				$template->set_filenames(array($block_handle => $empty_block_tpl));
				$template->assign_vars($b_admin_vars);
				$template->assign_vars(array(
					'POSITION' => $position,
					'CONTENT_TYPE' => $content_type,
					'OUTPUT' => $output_block,
					'TITLE_CONTENT' => (($title_string == '') ? '&nbsp;' : $title_string),
					'TITLE' => (($block_info[$b_counter]['titlebar'] == 1) ? true : false),
					'BORDER' => (($block_info[$b_counter]['border'] == 1) ? true : false),
					'BACKGROUND' => (($block_info[$b_counter]['background'] == 1) ? true : false),
					)
				);
				$cms_block = $template->get_var_from_handle($block_handle);
				$template->assign_block_vars($position_prefix . 'blocks_row', $b_admin_vars);
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