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

class cms_admin
{
	var $mode = false;
	var $action = false;
	var $l_id = false;	//Layout ID
	var $ls_id = false;	//Layout Special ID
	var $b_id = false;	//Block ID
	var $bs_id = false;	//Block Settings ID

	function check_version()
	{
		global $lang, $db, $template, $config, $table_prefix;

		if ($config['cms_rev'] != '2')
		{
			if(!isset($_POST['confirm']))
			{
				$template->assign_vars(array(
					'L_YES' => $lang['YES'],
					'L_NO' => $lang['NO'],

					'MESSAGE_TITLE' => $lang['Confirm'],
					'MESSAGE_TEXT' => 'Aggiornare CMS?',

					'S_CONFIRM_ACTION' => append_sid($this->root . $this->s_append_url),
					'S_HIDDEN_FIELDS' => $this->s_hidden_fields
					)
				);
				full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
			}
			else
			{
				include(IP_ROOT_PATH . 'includes/cms_updates.' . PHP_EXT);
				foreach($sql as $sql_data)
				{
					$result = $db->sql_query($sql_data);
				}
			}
		}
		return true;
	}

	function init_vars($mode_array, $action_array)
	{
		//$this->check_version();
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

		if (!empty($_REQUEST['mode']) && !empty($_GET['mode']) && ($_POST['mode'] != $_GET['mode']))
		{
			$_REQUEST['mode'] = $_GET['mode'];
			$_POST['mode'] = $_GET['mode'];
		}

		$mode = request_var('mode', '');
		$this->mode = (in_array($mode, $mode_array) ? $mode : false);

		if (!empty($_REQUEST['action']) && !empty($_GET['action']) && ($_POST['action'] != $_GET['action']))
		{
			$_REQUEST['action'] = $_GET['action'];
			$_POST['action'] = $_GET['action'];
		}

		$action = request_var('action', '');
		$action = (isset($_POST['add']) ? 'add' : $action);
		$action = (isset($_POST['save']) ? 'save' : $action);
		$this->action = (in_array($action, $action_array) ? $action : false);

		$l_id = request_var('l_id', 0);
		$this->l_id = ($l_id < 0) ? false : $l_id;

		$ls_id = request_var('ls_id', 0);
		$this->ls_id = ($ls_id < 0) ? false : $ls_id;

		$b_id = request_var('b_id', 0);
		$this->b_id = ($b_id < 0) ? false : $b_id;

		$bs_id = request_var('bs_id', 0);
		$this->bs_id = ($bs_id < 0) ? false : $bs_id;

		$user_id = request_var('user_id', 0);
		$this->user_id = ($user_id < 0) ? false : $user_id;

		$cms_id = request_var('cms_id', 0);
		$this->cms_id = ((!$cms_id) || ($cms_id < 0)) ? $this->get_cms_id() : $cms_id;

		if (defined('IN_CMS_USERS'))
		{
			$this->mode = $this->get_user_cms_id() ? $this->mode : 'new';
		}
	}

	function manage_block()
	{
		global $class_form, $template, $lang, $auth;

		$l_row = get_global_blocks_layout($this->table_name, $this->field_name, $this->id_var_value);

		if (($this->id_var_value == 0) || ($this->id_var_name == 'ls_id'))
		{
			$l_id_list = "'0'";
		}
		else
		{
			$l_id_list = "'" . $this->id_var_value . "'";
		}

		if($this->action == 'edit')
		{
			if($this->b_id)
			{
				$b_info = $this->get_block_info();
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
			}
		}
		else
		{
			$b_info['bposition'] = '';
			$b_info['blockfile'] = '';
			$b_info['view'] = 0;
			$b_info['groups'] = '';
		}

		$b_info['bposition'] = (isset($_POST['bposition'])) ? request_var('bposition', '') : $b_info['bposition'];
		$position = $this->get_blocks_positions($l_id_list, $b_info['bposition']);

		$bs_array = $this->get_blocks_settings_detail();
		$select_name = 'block_settings_id';
		$default = $b_info['block_settings_id'];
		$select_js = '';
		$b_block_parent = $class_form->build_select_box($select_name, $default, $bs_array['ID'], $bs_array['TITLE'], $select_js);

		$b_title = (isset($_POST['title'])) ? request_post_var('title', '', true) : (!empty($b_info['title']) ? $b_info['title'] : '');
		$b_active = (isset($_POST['active'])) ? request_post_var('active', 0) : ($b_info['active'] ? $b_info['active'] : 0);
		$b_local = (isset($_POST['local'])) ? request_post_var('local', 0) : ($b_info['local'] ? $b_info['local'] : 0);
		$b_titlebar = (isset($_POST['titlebar'])) ? request_post_var('titlebar', 0) : ($b_info['titlebar'] ? $b_info['titlebar'] : 0);
		$b_border = (isset($_POST['border'])) ? request_post_var('border', 0) : ($b_info['border'] ? $b_info['border'] : 0);
		$b_background = (isset($_POST['background'])) ? request_post_var('background', 0) : ($b_info['background'] ? $b_info['background'] : 0);

		$template->assign_vars(array(
			'TITLE' => $b_title,
			'POSITION' => $position['select'],
			'ACTIVE' => ($b_active) ? 'checked="checked"' : '',
			'NOT_ACTIVE' => (!$b_active) ? 'checked="checked"' : '',
			'BORDER' => ($b_border) ? 'checked="checked"' : '',
			'NO_BORDER' => (!$b_border) ? 'checked="checked"' : '',
			'TITLEBAR' => ($b_titlebar) ? 'checked="checked"' : '',
			'NO_TITLEBAR' => (!$b_titlebar) ? 'checked="checked"' : '',
			'LOCAL' => ($b_local) ? 'checked="checked"' : '',
			'NOT_LOCAL' => (!$b_local) ? 'checked="checked"' : '',
			'BACKGROUND' => ($b_background) ? 'checked="checked"' : '',
			'NO_BACKGROUND' => (!$b_background) ? 'checked="checked"' : '',
			'GROUP' => $group,
			'BLOCK_PARENT' => $b_block_parent,

			'S_BLOCKS_ACTION' => append_sid($this->root . $this->s_append_url),
			'S_HIDDEN_FIELDS' => $this->s_hidden_fields
			)
		);
	}

	function save_block()
	{
		global $db, $class_db, $lang, $userdata;

		$inputs_array = array(
			'title' => '',
			'bposition' => '',
			'active' => '',
			'block_settings_id' => '',
			'border' => '',
			'titlebar' => '',
			'local' => '',
			'background' => '',
		);

		foreach ($inputs_array as $k => $v)
		{
			$data[$k] = request_post_var($k, $v);
		}

		$data['layout'] = $this->layout_value;
		$data['layout_special'] = $this->layout_special_value;

		$gb_pos = array('gh', 'gf', 'gt', 'gb', 'gl', 'gr', 'hh', 'hl', 'hc', 'fc', 'fr', 'ff');
		if(in_array($data['b_position'], $gb_pos))
		{
			if ($this->id_var_name == 'l_id')
			{
				$this->id_var_value = 0;
			}
		}

		if (($this->id_var_value == 0) && ($this->id_var_name == 'l_id'))
		{
			$redirect_l_id = $this->id_var_value . '&amp;action=editglobal';
		}
		else
		{
			$redirect_l_id = $this->id_var_value;
		}

		if($data['title'] == '')
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_block']);
		}

		if($this->b_id)
		{
			$class_db->update_item($this->b_id, $data);
		}
		else
		{
			$data['block_cms_id'] = ($this->cms_id) ? $this->cms_id : 0;
			$data['weight'] = ($this->get_max_blocks_position($this->id_var_value, $data['bposition'])) + 1;

			$class_db->insert_item($data);
		}

		$this->fix_weight_blocks($this->id_var_value, $this->table_name);

		redirect(append_sid($this->root . '?mode=blocks&amp;' . $this->id_var_name . '=' . $redirect_l_id));
	}

	function delete_block()
	{
		global $db, $template, $lang;

		if(!isset($_POST['confirm']))
		{
			$template->assign_vars(array(
				'L_YES' => $lang['YES'],
				'L_NO' => $lang['NO'],

				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'S_CONFIRM_ACTION' => append_sid($this->root . $this->s_append_url),
				'S_HIDDEN_FIELDS' => $this->s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			if($this->b_id != 0)
			{
				$sql = "DELETE FROM " . $this->tables['blocks_table'] . " WHERE bid = " . $this->b_id;
				$result = $db->sql_query($sql);

				$this->delete_block_config_all();

				if (($this->l_id == 0) && ($this->id_var_name == 'l_id'))
				{
					$redirect_action = '&amp;action=editglobal';
				}
				else
				{
					$redirect_action = '&amp;action=list';
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
			}
			$this->fix_weight_blocks($this->id_var_value, $this->table_name);
			$this->fix_weight_blocks(0, $this->table_name);
			redirect(append_sid($this->root . '?mode=blocks&amp;' . $this->id_var_name . '=' . $this->id_var_value . $redirect_action));
		}
	}

	function update_blocks()
	{
		global $db;

		$blocks_upd = array();
		$blocks_upd = !empty($_POST['block']) ? request_var('block', array(0)) : array();
		$blocks_upd_n = sizeof($blocks_upd);
		$sql_no_gb = '';

		if ($this->action == 'editglobal')
		{
			$l_id_list = "'0'";
			$action_append = '&amp;action=editglobal';
			$action_append .= ($this->cms_id) ? '&amp;cms_id=' . $this->cms_id : '';
			$sql_no_gb = " AND layout_special = '0'";
		}
		else
		{
			$l_id_list = "'" . $this->id_var_value . "'";
			$action_append = '';
		}

		if (($this->mode == 'blocks') || ($this->action == 'editglobal'))
		{
			$b_rows = $this->get_blocks_from_layouts($this->block_layout_field, $l_id_list, $sql_no_gb);
			$b_count = !empty($b_rows) ? sizeof($b_rows) : 0;

			for($i = 0; $i < $b_count; $i++)
			{
				$b_active = empty($blocks_upd) ? 0 : (in_array($b_rows[$i]['bid'], $blocks_upd) ? 1 : 0);
				$sql = "UPDATE " . $this->tables['blocks_table'] . "
								SET active = '" . $b_active . "'
								WHERE bid = '" . $b_rows[$i]['bid'] . "'";
				$result = $db->sql_query($sql);
			}
			$this->fix_weight_blocks($this->id_var_value, $this->table_name);
			redirect(append_sid($this->root . '?mode=' . $this->mode . '&amp;' . $this->id_var_name . '=' . $this->id_var_value . $action_append));
		}
	}

	function move_block($move)
	{
		global $db, $lang;

		$b_weight = request_var('weight', '');
		$b_position = request_var('pos', '');
		$gb_pos = array('gh', 'gf', 'gt', 'gb', 'gl', 'gr', 'hh', 'hl', 'hc', 'fc', 'fr', 'ff');
		if(in_array($b_position, $gb_pos))
		{
			if ($this->id_var_name == 'l_id')
			{
				$this->id_var_value = 0;
				$this->l_id = 0;
				$this->layout_value = 0;
			}
		}

		if((($move == '1') && ($b_weight != '1') && ($b_weight != '0')) || (($move == '0') && ($b_weight != '0')))
		{
			if($move == '1')
			{
				$temp = $b_weight - 1;
			}
			else
			{
				$temp = $b_weight + 1;
			}
			$sql = "UPDATE " . $this->tables['blocks_table'] . " SET weight = '" . $b_weight . "' WHERE " . $this->block_layout_field . " = '" . $this->id_var_value . "' AND weight = '" . $temp . "' AND bposition = '" . $b_position . "'";
			$result = $db->sql_query($sql);
			$sql = "UPDATE " . $this->tables['blocks_table'] . " SET weight = '" . $temp . "' WHERE bid = '" . $this->b_id . "'";
			$result = $db->sql_query($sql);
			$this->fix_weight_blocks($this->id_var_value, $this->table_name);
		}
	}

	function show_blocks_list()
	{
		global $db, $template, $lang, $theme;

		$l_row = get_layout_name($this->table_name, $this->field_name, $this->id_var_value);
		$l_name = $l_row['name'];
		$l_filename = $l_row['filename'];

		if ($this->action == 'editglobal')
		{
			$page_url = append_sid(CMS_PAGE_HOME);
			$l_id_list = "'0'";
		}
		else
		{
			if ($this->id_var_name == 'l_id')
			{
				if (($l_filename != '') && file_exists($l_filename))
				{
					$page_url = append_sid($l_filename);
				}
				else
				{
					$page_url = (substr($l_name, strlen($l_name) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT)) ? append_sid($l_name) : append_sid(CMS_PAGE_HOME . '?page=' . $this->id_var_value);
				}
			}
			else
			{
				$page_url = append_sid($l_filename);
			}
			$l_id_list = "'" . $this->id_var_value . "'";
		}

		if ($this->id_var_name == 'l_id')
		{
			$l_name = ($l_name == '') ? $lang['Portal'] : $l_name;
		}
		else
		{
			$l_name = $l_filename;
		}

		$template->assign_vars(array(
			'LAYOUT_NAME' => $l_name,
			'PAGE_URL' => $page_url,
			'PAGE' => strval($this->id_var_value),
			'U_LAYOUT_EDIT' => (($this->block_layout_field == 'layout') ? append_sid($this->root . '?mode=layouts&amp;action=edit' . '&amp;' . $this->id_var_name . '=' . $this->id_var_value) : ''),
			)
		);

		if (($this->mode == 'blocks') || ($this->action == 'editglobal'))
		{
			$b_rows = $this->get_blocks_from_layouts($this->block_layout_field, $l_id_list, '');
			$b_count = !empty($b_rows) ? sizeof($b_rows) : 0;

			// Reassign $l_id_list
			if ($this->id_var_name == 'l_id')
			{
				$l_id_list = $this->id_var_value . "', '0";
			}
			else
			{
				$l_id_list = '0';
			}

			$position = $this->get_blocks_positions_layout($l_id_list);

			if ($b_count > 0)
			{
				$else_counter = 0;
				for($i = 0; $i < $b_count; $i++)
				{
					if (($b_rows[$i]['layout_special'] != 0) && ($this->action == 'editglobal'))
					{
					}
					else
					{
						$b_id = $b_rows[$i]['bid'];
						$b_weight = $b_rows[$i]['weight'];
						$b_position = $b_rows[$i]['bposition'];
						$b_position_l = !empty($lang['cms_pos_' . $position[$b_position]]) ? $lang['cms_pos_' . $position[$b_position]] : $row['pkey'];

						$row_class = (!($else_counter % 2)) ? $theme['td_class2'] : $theme['td_class1'];
						$else_counter++;

						if (($this->l_id == 0) && ($this->id_var_name == 'l_id'))
						{
							$redirect_action = '&amp;action=editglobal';
						}
						else
						{
							$redirect_action = '&amp;action=list';
						}

						$template->assign_block_vars('blocks', array(
							'ROW_CLASS' => $row_class,
							'TITLE' => trim($b_rows[$i]['title']),
							'BLOCK_CB_ID' => $b_rows[$i]['bid'],
							'POSITION' => $b_position_l,
							'L_POSITION' => $b_position_l,
							'ACTIVE' => ($b_rows[$i]['active']) ? $lang['YES'] : $lang['NO'],
							'BLOCK_CHECKED' => ($b_rows[$i]['active']) ? ' checked="checked"' : '',
							'TYPE' => (empty($b_rows[$i]['blockfile'])) ? (($b_rows[$i]['type']) ? $lang['B_BBCODE'] : $lang['B_HTML']) : '&nbsp;',
							'BORDER' => ($b_rows[$i]['border']) ? $lang['YES'] : $lang['NO'],
							'TITLEBAR' => ($b_rows[$i]['titlebar']) ? $lang['YES'] : $lang['NO'],
							'LOCAL' => ($b_rows[$i]['local']) ? $lang['YES'] : $lang['NO'],
							'BACKGROUND' => ($b_rows[$i]['background']) ? $lang['YES'] : $lang['NO'],
							'GROUPS' => $groups,
							'CONTENT' => (empty($b_rows[$i]['blockfile'])) ? $lang['B_TEXT'] : $lang['B_FILE'],
							'VIEW' => $b_view,

							'U_EDIT' => append_sid($this->root . '?mode=' . $this->mode . '&amp;action=edit&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;b_id=' . $b_id),
							'U_DELETE' => append_sid($this->root . '?mode=' . $this->mode . '&amp;action=delete&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;b_id=' . $b_id),
							'U_MOVE_UP' => append_sid($this->root . '?mode=' . $this->mode . $redirect_action . '&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;move=1&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position),
							'U_MOVE_DOWN' => append_sid($this->root . '?mode=' . $this->mode . $redirect_action . '&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;move=0&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position)
							)
						);
					}
				}
			}
			else
			{
				$template->assign_var('S_NO_BLOCKS', true);
			}
		}
	}

	function manage_block_settings()
	{
		global $db, $lang, $class_form, $template, $userdata;

		if(isset($_POST['hascontent']))
		{
			$block_content = request_var('blockfile', '');
			$block_text = (empty($block_content) ? true : false);
			$hascontent = true;
		}
		else
		{
			$block_content = false;
			$block_text = false;
			$hascontent = false;
		}
		$block_content_file = $block_content;

		if($this->action == 'edit')
		{
			if($this->bs_id)
			{
				$b_info = $this->get_block_settings_info();
				if((($b_info['locked'] == '1') || ($b_info['user_id'] != $userdata['user_id'])) && ($userdata['user_level'] != ADMIN))
				{
					message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
			}
		}
		else
		{
			$b_info['bposition'] = '';
			$b_info['blockfile'] = '';
			$b_info['view'] = 0;
			$b_info['groups'] = '';
		}

		if ($userdata['user_level'] == ADMIN)
		{
			$blocks_array = get_blocks_files_list();
			$options_array = array();
			$options_langs_array = array();
			$options_array[] = '';
			$options_langs_array[] = '[&nbsp;' . $lang['B_TEXT_BLOCK'] . '&nbsp;]';
			foreach ($blocks_array as $block_file)
			{
				$options_array[] = BLOCKS_PREFIX . $block_file;
				$options_langs_array[] = $block_file . (!empty($lang['cms_block_' . $block_file]) ? ('&nbsp;[' . $lang['cms_block_' . $block_file] . ']') : '');
			}

			$block_content_file_old = $b_info['blockfile'];

			$b_info['blockfile'] = (isset($_POST['blockfile'])) ? request_var('blockfile', '') : $b_info['blockfile'];

			$select_name = 'blockfile';
			$default = $b_info['blockfile'];
			$select_js = ($cms_ajax) ? ' id="blockfile" onchange="javascript:ajaxpage(\'cms_ajax.' . PHP_EXT . '\', \'?mode=block_config&amp;blockfile=\'+this.form.blockfile.options[this.form.blockfile.selectedIndex].value, \'block_config\');"' : '';

			$blockfile = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);
			$locked = !empty($b_info['locked']) ? true : false;
			$locked_hidden = (isset($_POST['locked'])) ? true : false;
		}
		else
		{
			$blockfile = $lang['B_TEXT_BLOCK'];
			$locked = false;
			$locked_hidden = false;
		}

		if (!defined('IN_CMS_USERS'))
		{
			$locked = true;
			$locked_hidden = true;
		}

		$b_info['view'] = (isset($_POST['view'])) ? request_var('view', '') : $b_info['view'];

		$select_name = 'view';
		$default = $b_info['view'];
		$options_array = array(0, 1, 2, 3, 4);
		$options_langs_array = array($lang['B_ALL'], $lang['B_GUESTS'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN']);
		$select_js = '';
		$view = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

		if (isset($_POST['message']))
		{
			$message = request_var('message', '', true);
			$message = htmlspecialchars_decode($message, ENT_COMPAT);
		}
		elseif (!empty($b_info['content']))
		{
			$message = $b_info['content'];
		}
		else
		{
			$message = '';
		}

		$group = get_all_usergroups($b_info['groups']);

		if(empty($group))
		{
			$group = '&nbsp;&nbsp;' . $lang['None'];
		}

		$b_name = (isset($_POST['name'])) ? request_var('name', '') : (!empty($b_info['name']) ? trim($b_info['name']) : '');
		$b_type = (isset($_POST['type'])) ? request_var('type', '') : ($b_info['type'] ? $b_info['type'] : 0);

		$max_group_id = get_max_group_id();
		$b_group = '';
		$b_group_hidden = '';
		$not_first = false;
		for($i = 1; $i <= $max_group_id; $i++)
		{
			if(isset($_POST['group' . strval($i)]))
			{
				$b_group_hidden .= '<input type="hidden" name="group' . strval($i) . '" value="1" />';
			}
		}

		if($block_text == true)
		{
			$template->assign_var('CMS_PAGE_TITLE', $lang['BLOCKS_CREATION_02']);
			//generate_smilies('inline');
			$this->s_hidden_fields .= '<input type="hidden" name="blockfile" value="" />';
			$this->s_hidden_fields .= '<input type="hidden" name="hascontent" value="1" />';
			$this->s_hidden_fields .= '<input type="hidden" name="name" value="' . htmlspecialchars($b_name) . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="view" value="' . $b_view . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="locked" value="' . $locked_hidden . '" />';
			$this->s_hidden_fields .= $b_group_hidden;
		}
		elseif($block_content != false)
		{
			$template->assign_var('CMS_PAGE_TITLE', $lang['BLOCKS_CREATION_02']);
			$this->s_hidden_fields .= '<input type="hidden" name="blockfile" value="' . $block_content_file . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="message" value="" />';
			$this->s_hidden_fields .= '<input type="hidden" name="type" value="0" />';
			$this->s_hidden_fields .= '<input type="hidden" name="name" value="' . htmlspecialchars($b_name) . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="view" value="' . $b_view . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="locked" value="' . $locked_hidden . '" />';
			$this->s_hidden_fields .= $b_group_hidden;

			if (($this->bs_id > 0) && ($block_content_file == $block_content_file_old))
			{
				$sql = "SELECT * FROM " . $this->tables['block_config_table'] . " AS c, " . $this->tables['block_variable_table'] . " AS bv
									WHERE c.bid = '" . $this->bs_id . "'
										AND bv.bid = '" . $this->bs_id . "'
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
						$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? request_var($cms_field[$row['config_name']]['name'], '') : $default_portal[$cms_field[$row['config_name']]['name']];
					}

					$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';

					$template->assign_block_vars('cms_block', array(
						'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
						'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
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
				if(file_exists(BLOCKS_DIR . $block_content_file . '.cfg'))
				{
					$block_count_variables = 0;
					include(BLOCKS_DIR . $block_content_file . '.cfg');
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
								$new[$cms_field[$row['config_name']]['name']] = (isset($_POST[$cms_field[$row['config_name']]['name']])) ? request_var($cms_field[$row['config_name']]['name'], '') : $default_portal[$cms_field[$row['config_name']]['name']];
							}

							$is_block = ($cms_field[$row['config_name']]['block'] != '@Portal Config') ? 'block ' : '';

							$template->assign_block_vars('cms_block', array(
								'L_FIELD_LABEL' => $cms_field[$row['config_name']]['label'],
								'L_FIELD_SUBLABEL' => '<br /><br /><span class="gensmall">' . $cms_field[$row['config_name']]['sub_label'] . ' [ ' . str_replace("@", "", $cms_field[$row['config_name']]['block']) . ' ' . $is_block . ']</span>',
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
		else
		{
			$template->assign_var('CMS_PAGE_TITLE', $lang['BLOCKS_CREATION_01']);
			$this->s_hidden_fields .= '<input type="hidden" name="message" value="' . htmlspecialchars($message) . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="type" value="' . $b_type . '" />';
			// Mighty Gorgon: this is not needed because it is assigned via submit button!
			//$this->s_hidden_fields .= '<input type="hidden" name="hascontent" value="1" />';
		}

		$template->assign_vars(array(
			'NAME' => $b_name,
			'HTML' => (!$b_type) ? 'checked="checked"' : '',
			'BBCODE' => ($b_type) ? 'checked="checked"' : '',
			'CONTENT' => htmlspecialchars($message),
			'BLOCKFILE' => $blockfile,
			'BLOCK_CONFIG' => $block_config,
			'VIEWBY' => $view,
			'GROUP' => $group,
			'LOCKED' => (!empty($locked) ? 'checked="checked"' : ''),

			'S_BLOCKS_ACTION' => append_sid($this->root . $this->s_append_url),
			'S_HIDDEN_FIELDS' => $this->s_hidden_fields
			)
		);

		// BBCBMG - BEGIN
		include(IP_ROOT_PATH . 'includes/bbcb_mg.' . PHP_EXT);
		$template->assign_var_from_handle('BBCB_MG', 'bbcb_mg');
		// BBCBMG - END
		// BBCBMG SMILEYS - BEGIN
		generate_smilies('inline');
		include(IP_ROOT_PATH . 'includes/bbcb_smileys_mg.' . PHP_EXT);
		$template->assign_var_from_handle('BBCB_SMILEYS_MG', 'bbcb_smileys_mg');
		// BBCBMG SMILEYS - END
	}

	function save_block_settings()
	{
		global $db, $class_db, $lang, $userdata;

		$inputs_array = array(
			'name' => '',
			'type' => '',
			'blockfile' => '',
			'view' => '',
			'locked' => '',
		);

		foreach ($inputs_array as $k => $v)
		{
			$data[$k] = request_post_var($k, $v);
		}

		if($data['name'] == '')
		{
			message_die(GENERAL_MESSAGE, $lang['Must_enter_block']);
		}

		$data['groups'] = get_selected_groups();
		$data['content'] = request_post_var('message', '', true);
		$data['locked'] = $data['locked'] ? 1 : 0;

		if($this->bs_id)
		{
			$class_db->update_item($this->bs_id, $data);
		}
		else
		{
			$data['user_id'] = $userdata['user_id'];
			$class_db->insert_item($data);
		}
		$this->update_block_config($data['blockfile']);
		redirect(append_sid($this->root . '?mode=block_settings', true));
	}

	function update_block_config($blockfile)
	{
		global $db;

		if($this->bs_id)
		{
			if(!empty($blockfile) && file_exists(BLOCKS_DIR . $blockfile . '.cfg'))
			{
				include(BLOCKS_DIR . $blockfile . '.cfg');

				// let's empty the previously created config vars...
				$sql = "SELECT * FROM " . $this->tables['block_config_table'] . " WHERE bid = '" . $this->bs_id . "'";
				$result = $db->sql_query($sql);

				while($row = $db->sql_fetchrow($result))
				{
					$delete_var = true;
					for($i = 0; $i < $block_count_variables; $i++)
					{
						if ($row['config_name'] == $block_variables[$i][2])
						{
							$delete_var = false;
						}
					}

					if ($delete_var == true)
					{
						$this->delete_block_config_single($row['config_name']);
					}
				}
				$db->sql_freeresult($result);
			}
			else
			{
				$this->delete_block_config_all();
			}
		}

		if(!empty($blockfile) && file_exists(BLOCKS_DIR . $blockfile . '.cfg'))
		{
			include(BLOCKS_DIR . $blockfile . '.cfg');

			for($i = 0; $i < $block_count_variables; $i++)
			{
				if ((!empty($_POST[$block_variables[$i][2]])) || ($_POST[$block_variables[$i][2]] == '0'))
				{
					$block_variables[$i][7] = $db->sql_escape($_POST[$block_variables[$i][2]]);
				}

				$existing = $this->get_existing_block_var($block_variables[$i][2]);

				if(!$existing || !$this->bs_id)
				{
					$sql = "INSERT INTO " . $this->tables['block_variable_table'] . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
						VALUES ('" . $this->bs_id ."', '" . $db->sql_escape($block_variables[$i][0]) . "', '" . $db->sql_escape($block_variables[$i][1]) . "', '" . $db->sql_escape($block_variables[$i][2]) . "', '" . $db->sql_escape($block_variables[$i][3]) . "', '" . $block_variables[$i][4] . "', '" . $block_variables[$i][5] . "', '" . $db->sql_escape($block_variables[$i][6]) . "')";
					$result = $db->sql_query($sql);

					$sql = "INSERT INTO " . $this->tables['block_config_table'] . " (bid, config_name, config_value)
						VALUES ('" . $this->bs_id ."', '" . $db->sql_escape($block_variables[$i][2]) . "', '" . $block_variables[$i][7] . "')";
					$result = $db->sql_query($sql);
				}
				else
				{
					$sql = "UPDATE " . $this->tables['block_config_table'] . " SET config_value = '" . $block_variables[$i][7] . "'
									WHERE config_name = '" . $db->sql_escape($block_variables[$i][2]) . "'
										AND bid = " . $this->bs_id;
					$result = $db->sql_query($sql);
				}
			}
		}
	}

	function delete_block_settings()
	{
		global $db, $template, $lang, $userdata;

		if($this->bs_id != 0)
		{
			$b_info = $this->get_block_settings_info();
			if((($b_info['locked'] == '1') || ($b_info['user_id'] != $userdata['user_id'])) && ($userdata['user_level'] != ADMIN))
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Auth_View']);
			}
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_blocks_selected']);
		}

		if(!isset($_POST['confirm']))
		{
			$template->assign_vars(array(
				'L_YES' => $lang['YES'],
				'L_NO' => $lang['NO'],

				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'S_CONFIRM_ACTION' => append_sid($this->root . $this->s_append_url),
				'S_HIDDEN_FIELDS' => $this->s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			$sql = "DELETE FROM " . $this->tables['block_settings_table'] . " WHERE bs_id = " . $this->bs_id;
			$result = $db->sql_query($sql);

			$sql = "DELETE FROM " . $this->tables['blocks_table'] . " WHERE block_settings_id = " . $this->bs_id;
			$result = $db->sql_query($sql);

			$this->delete_block_config_all();
			redirect(append_sid($this->root . '?mode=block_settings'));
		}
	}

	function show_blocks_settings_list()
	{
		global $lang, $theme, $template, $userdata;

		$b_rows = $this->get_installed_blocks();
		$b_count = !empty($b_rows) ? sizeof($b_rows) : 0;

		if ($b_count > 0)
		{
			for($i = 0; $i < $b_count; $i++)
			{
				$this->bs_id = $b_rows[$i]['bs_id'];

				$row_class = (!($i % 2)) ? $theme['td_class2'] : $theme['td_class1'];

				$b_view = get_block_view_name($b_rows[$i]['view']);

				$groups = (!empty($b_rows[$i]['groups'])) ? get_groups_names($b_rows[$i]['groups']) : $lang['B_ALL'];

				$template->assign_block_vars('blocks', array(
						'ROW_CLASS' => $row_class,
						'NAME' => trim($b_rows[$i]['name']),
						'TYPE' => (empty($b_rows[$i]['blockfile'])) ? (($b_rows[$i]['type']) ? $lang['B_BBCODE'] : $lang['B_HTML']) : '&nbsp;',
						'GROUPS' => $groups,
						'CONTENT' => (empty($b_rows[$i]['blockfile'])) ? $lang['B_TEXT'] : $lang['B_FILE'],
						'VIEW' => $b_view,
						'USERNAME' => colorize_username($b_rows[$i]['user_id']),
						'STATUS' => $b_rows[$i]['locked'],
						'S_MANAGE' => (($b_rows[$i]['locked'] == '1') && ($userdata['user_level'] != ADMIN)) ? false : true,
						'U_EDIT' => append_sid($this->root . '?mode=' . $this->mode . '&amp;action=edit&amp;bs_id=' . $this->bs_id),
						'U_DELETE' => append_sid($this->root . '?mode=' . $this->mode . '&amp;action=delete&amp;bs_id=' . $this->bs_id),
					)
				);
			}
		}
		else
		{
			$template->assign_var('S_NO_BLOCKS', true);
		}
	}

	function manage_layout($is_layout_special)
	{
		global $db, $template, $class_form, $lang;

		if($this->action == 'edit')
		{
			$l_info = get_layout_info($this->table_name, $this->field_name, $this->id_var_value);
			$this->s_hidden_fields .= '<input type="hidden" name="filename_old" value="' . $l_info['filename'] . '" />';
		}

		if (!$is_layout_special)
		{
			if (file_exists('testing_write_access_permissions.test'))
			{
				@unlink('testing_write_access_permissions.test');
			}
			$write_test = @copy('index_empty.' . PHP_EXT, 'testing_write_access_permissions.test');
			if (file_exists('testing_write_access_permissions.test'))
			{
				@chmod('testing_write_access_permissions.test', 0777);
				@unlink('testing_write_access_permissions.test');
			}
			if ($write_test)
			{
				$file_creation_auth = $lang['CMS_Filename_Explain_OK'];
			}
			else
			{
				$file_creation_auth = $lang['CMS_Filename_Explain_NO'];
			}

			$l_info['page_id'] = '';
			$template_name = 'default';
			$template_dir = IP_ROOT_PATH . '/templates/' . $template_name . '/layout';

			$layout_details = get_layouts_details($l_info, $template_dir, '.tpl', 'template');
			for ($i = 0; $i < sizeof($layout_details); $i++)
			{
				$template->assign_block_vars('layouts', array(
					'LAYOUT_IMG' => $layout_details[$i]['img'],
					'LAYOUT_RADIO' => $layout_details[$i]['file']
					)
				);
			}

			$select_name = 'view';
			$default = empty($l_info['view']) ? 0 : $l_info['view'];
			$options_array = array(0, 1, 2, 3, 4);
			$options_langs_array = array($lang['B_ALL'], $lang['B_GUESTS'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN']);
			$select_js = '';
			$view = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			$select_name = 'edit_auth';
			$default = empty($l_info['edit_auth']) ? 0 : $l_info['edit_auth'];
			/*
			$options_array = array(0, 1, 2, 3, 4, 5);
			$options_langs_array = array($lang['CMS_Guest'], $lang['CMS_Reg'], $lang['CMS_VIP'], $lang['CMS_Publisher'], $lang['CMS_Reviewer'], $lang['CMS_Content_Manager']);
			*/
			$options_array = array(3, 4, 5);
			$options_langs_array = array($lang['CMS_PUBLISHER'], $lang['CMS_REVIEWER'], $lang['CMS_CONTENT_MANAGER']);
			$select_js = '';
			$edit_auth = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			$group = (!empty($l_info['groups'])) ? get_all_usergroups($l_info['groups']) : get_all_usergroups('');
			if(empty($group))
			{
				$group = '&nbsp;&nbsp;' . $lang['None'];
			}
		}
		else
		{
			if(($this->action == 'edit') && $l_info['locked'])
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}

			$edit_auth = '';
			$group = '';
			$default = empty($l_info['view']) ? 0 : $l_info['view'];
			$view = auth_select('view', $default);
		}

		$template->assign_vars(array(
			'NAME' => (empty($l_info['name']) ? '' : htmlspecialchars($l_info['name'])),
			'FILENAME' => (empty($l_info['filename']) ? '' : $l_info['filename']),
			'PAGE_ID' => (empty($l_info['page_id']) ? '' : $l_info['page_id']),
			'TEMPLATE' => $layout_details,
			'VIEW' => $view,
			'EDIT_AUTH' => $edit_auth,
			'GROUPS' => $group,
			'GLOBAL_BLOCKS' => ((!empty($l_info['global_blocks']) && $l_info['global_blocks']) ? 'checked="checked"' : ''),
			'NOT_GLOBAL_BLOCKS' => (empty($l_info['global_blocks'])) ? 'checked="checked"' : '',
			'PAGE_NAV' => ((!empty($l_info['page_nav']) && $l_info['page_nav']) ? 'checked="checked"' : ''),
			'NOT_PAGE_NAV' => (empty($l_info['page_nav'])) ? 'checked="checked"' : '',

			'S_LAYOUT_SPECIAL' => $is_layout_special,
			'S_LAYOUT_ACTION' => append_sid($this->root . $this->s_append_url),
			'S_HIDDEN_FIELDS' => $this->s_hidden_fields
			)
		);
	}

	function save_layout($is_layout_special)
	{
		global $db, $template, $class_db, $lang, $userdata;

		$inputs_array = array(
			'name' => '',
			'filename' => '',
			'global_blocks' => '',
			'page_nav' => '',
			'view' => '',
		);

		if (!$is_layout_special)
		{
			$inputs_array['template'] = '';
			$inputs_array['edit_auth'] = '';
		}
		else
		{
			$inputs_array['page_id'] = '';
			$inputs_array['locked'] = '';
		}

		foreach ($inputs_array as $k => $v)
		{
			$data[$k] = request_var($k, $v);
		}

		$l_filename_old = (isset($_POST['filename_old'])) ? request_var('filename_old', '') : '';

		if (!$is_layout_special)
		{
			$data['groups'] = get_selected_groups();

			if(!$this->l_id)
			{
				$data['layout_cms_id'] = ($this->cms_id) ? $this->cms_id : 0;
			}
			if(($data['name'] == '') || ($data['template'] == ''))
			{
				message_die(GENERAL_MESSAGE, $lang['Must_enter_layout']);
			}
		}
		else
		{
			if(($data['name'] == '') || ($data['page_id'] == '') || ($data['filename'] == ''))
			{
				message_die(GENERAL_MESSAGE, $lang['CMS_MUST_FILL_ALL_FIELDS']);
			}
		}

		if($this->id_var_value != 0)
		{
			if (!$is_layout_special)
			{
				if ($l_filename_old != $data['filename'])
				{
					@unlink($l_filename_old);

					if (substr($data['filename'], strlen($data['filename']) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT))
					{
						$data['filename'] = preg_replace('/[^A-Za-z0-9_]+/', '', substr(strtolower($data['filename']), 0, strlen($data['filename']) - (strlen(PHP_EXT) + 1))) . ('.' . PHP_EXT);
						if (file_exists($data['filename']))
						{
							message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
						}
						else
						{
							$creation_success = @copy('index_empty.' . PHP_EXT, $data['filename']);
							if ($creation_success)
							{
								@chmod($data['filename'], 0755);
								$message = $lang['CMS_FileCreationSuccess'] . '<br /><br />';
							}
							else
							{
								//message_die(GENERAL_MESSAGE, $lang['CMS_FileCreationError']);
								$message = $lang['CMS_FileCreationError'] . '<br />' . $lang['CMS_FileCreationManual'] . '<br /><br />';
							}
						}
					}
				}

				$class_db->update_item($this->id_var_value, $data);

				$message .= $lang['Layout_updated'];

				$template_name = 'default';

				if(file_exists(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $data['template'])))
				{
					include(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $data['template']));

					$sql_test = "SELECT * FROM " . $this->tables['block_position_table'] . " WHERE layout = '" . $this->id_var_value . "'";
					$result_test = $db->sql_query($sql_test);

					while ($row_test = $db->sql_fetchrow($result_test))
					{
						$bp_found = false;
						for($i = 0; $i < $layout_count_positions; $i++)
						{
							if (($row_test['bposition'] == $db->sql_escape($layout_block_positions[$i][1])) && ($row_test['pkey'] == $db->sql_escape($layout_block_positions[$i][0])))
							{
								$bp_found = true;
							}
						}
						if ($bp_found == false)
						{
							$sql = "DELETE FROM " . $this->tables['block_position_table'] . "
								WHERE layout = '" . $this->id_var_value . "'
									AND bposition = '" . $row_test['bposition'] . "'";
							$result = $db->sql_query($sql);
						}
					}
					$db->sql_freeresult($result);

					for($i = 0; $i < $layout_count_positions; $i++)
					{
						$sql_test = "SELECT * FROM " . $this->tables['block_position_table'] . "
							WHERE layout = '" . $this->id_var_value . "'
								AND bposition = '" . $layout_block_positions[$i][1] . "'
							LIMIT 1";
						$result_test = $db->sql_query($sql_test);

						if (!($db->sql_fetchrow($result_test)))
						{
							$sql = "INSERT INTO " . $this->tables['block_position_table'] . " (pkey, bposition, layout)
								VALUES ('" . $db->sql_escape($layout_block_positions[$i][0]) . "', '" . $db->sql_escape($layout_block_positions[$i][1]) . "', '" . $this->id_var_value . "')";
							$result = $db->sql_query($sql);
						}
					}
				}
			}
			else
			{
				if($data['locked'])
				{
					message_die(GENERAL_ERROR, $lang['Not_Authorized']);
				}

				$class_db->update_item($this->id_var_value, $data);

				$message .= $lang['Layout_updated'];
			}
		}
		else
		{
			if (!$is_layout_special)
			{
				if ($l_filename_old != $data['filename'])
				{
					@unlink($l_filename_old);
				}
				if (substr($data['filename'], strlen($data['filename']) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT))
				{
					$data['filename'] = preg_replace('/[^A-Za-z0-9_]+/', '', substr(strtolower($data['filename']), 0, strlen($data['filename']) - (strlen(PHP_EXT) + 1))) . ('.' . PHP_EXT);
					if (file_exists($data['filename']))
					{
						message_die(GENERAL_MESSAGE, $lang['CMS_FileAlreadyExists']);
					}
					else
					{
						$creation_success = @copy('index_empty.' . PHP_EXT, $data['filename']);
						if ($creation_success)
						{
							@chmod($data['filename'], 0755);
							$message = $lang['CMS_FileCreationSuccess'] . '<br /><br />';
						}
						else
						{
							//message_die(GENERAL_MESSAGE, $lang['CMS_FileCreationError']);
							$message = $lang['CMS_FileCreationError'] . '<br />' . $lang['CMS_FileCreationManual'] . '<br /><br />';
						}
					}
				}

				$template_name = 'default';
				$class_db->insert_item($data);

				if(file_exists(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $data['template'])))
				{
					include(IP_ROOT_PATH . '/templates/' . $template_name . '/layout/' . str_replace('.tpl', '.cfg', $data['template']));

					$new_layout_id = $this->get_max_layout_id();

					for($i = 0; $i < $layout_count_positions; $i++)
					{
						$sql = "INSERT INTO " . $this->tables['block_position_table'] . " (pkey, bposition, layout)
							VALUES ('" . $db->sql_escape($layout_block_positions[$i][0]) . "', '" . $db->sql_escape($layout_block_positions[$i][1]) . "', '" . $new_layout_id . "')";
						$result = $db->sql_query($sql);
					}

					$message .= '<br /><br />' . $lang['Layout_BP_added'];
				}
			}

			$message .= $lang['Layout_added'];
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid($this->root . '?mode=' . $this->mode_layout_name) . '">', '</a>');
		$message .= '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid($this->root . '?mode=blocks&amp;' . $this->id_var_name . '=' . (!empty($layout_id) ? $layout_id : $this->id_var_value)) . '">', '</a>');
		$message .= '<br /><br />';

		message_die(GENERAL_MESSAGE, $message);
	}

	function delete_layout()
	{
		global $db, $lang, $template;

		if($is_layout_special)
		{
			$l_info = get_layout_info($this->table_name, $this->field_name, $this->id_var_value);
			if (!empty($l_info['locked']))
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorized']);
			}
		}

		if(!isset($_POST['confirm']))
		{
			$template->assign_vars(array(
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_ENABLED' => $lang['Enabled'],
				'L_DISABLED' => $lang['Disabled'],

				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_item'],

				'S_CONFIRM_ACTION' => append_sid($this->root . $this->s_append_url),
				'S_HIDDEN_FIELDS' => $this->s_hidden_fields
				)
			);
			full_page_generation(CMS_TPL . 'confirm_body.tpl', $lang['Confirm'], '', '');
		}
		else
		{
			if($this->id_var_value != 0)
			{
				if (!$is_layout_special)
				{
					if ((substr($data['filename'], strlen($data['filename']) - (strlen(PHP_EXT) + 1), (strlen(PHP_EXT) + 1)) == ('.' . PHP_EXT)) && (file_exists($data['filename'])))
					{
						@unlink($data['filename']);
					}

					$sql = "DELETE FROM " . $this->tables['block_position_table'] . " WHERE layout = " . $this->id_var_value;
					$result = $db->sql_query($sql);
				}
				$sql = "DELETE FROM " . $this->table_name . " WHERE " . $this->field_name . " = " . $this->id_var_value;
				$result = $db->sql_query($sql);

				$sql = "DELETE FROM " . $this->tables['blocks_table'] . " WHERE " . $this->block_layout_field . " = " . $this->id_var_value;
				$result = $db->sql_query($sql);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
			}

			redirect(append_sid($this->root . '?mode=' . $this->mode_layout_name));
		}
	}

	function update_layout()
	{
		global $db;

		$action_append = ($this->cms_id) ? '&amp;cms_id=' . $this->cms_id : '';
		$sql_where = ($this->cms_id) ? " WHERE layout_cms_id = '" . $this->cms_id . "'": '';

		$l_gb_checkbox = !empty($_POST['layout_gb']) ? request_var('layout_gb', array(0)) : array();
		$l_gb_checkbox_n = sizeof($l_gb_checkbox);

		$l_bc_checkbox = !empty($_POST['layout_bc']) ? request_var('layout_bc', array(0)) : array();
		$l_bc_checkbox_n = sizeof($l_bc_checkbox);

		$sql = "SELECT " . $this->field_name . " FROM " . $this->table_name . $sql_where . "";
		$result = $db->sql_query($sql);
		$l_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		for($i = 0; $i < sizeof($l_rows); $i++)
		{
			$view_value = isset($_POST['auth_view_' . $l_rows[$i][$this->field_name]]) ? request_var('auth_view_' . $l_rows[$i][$this->field_name], 0) : 0;
			$gb_value = in_array($l_rows[$i][$this->field_name], $l_gb_checkbox) ? 1 : 0;
			$bc_value = in_array($l_rows[$i][$this->field_name], $l_bc_checkbox) ? 1 : 0;
			$sql = "UPDATE " . $this->table_name . " SET view = " . $view_value . ", global_blocks = " . $gb_value . ", page_nav = " . $bc_value . " WHERE " . $this->field_name . " = " . $l_rows[$i][$this->field_name];
			$result = $db->sql_query($sql);
		}
		redirect(append_sid($this->root . '?mode=' . $this->mode_layout_name . $action_append));
	}

	function show_layouts_list($is_layout_special)
	{
		global $db, $class_form, $template, $theme, $lang;

		$template->assign_block_vars('layout', array());

		$l_rows = get_layouts_list($this->table_name, $this->field_name, $this->cms_id);
		$l_count = sizeof($l_rows);

		$default_portal_id = 0;
		if (!$is_layout_special)
		{
			$sql = "SELECT config_value FROM " . $this->tables['block_config_table'] . " WHERE bid = '0' AND config_name = 'default_portal'";
			$result = $db->sql_query($sql);
			$c_row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$default_portal_id = $c_row['config_value'];
		}

		for($i = 0; $i < $l_count; $i++)
		{
			$row_class = (!($i % 2)) ? $theme['td_class2'] : $theme['td_class1'];
			$lang_var = 'auth_view_' . $l_rows[$i]['name'];
			$layout_id = $l_rows[$i][$this->field_name];
			$layout_name = ($is_layout_special ? (isset($lang[$lang_var]) ? htmlspecialchars($lang[$lang_var]) : htmlspecialchars($l_rows[$i]['name'])) : htmlspecialchars($l_rows[$i]['name']));
			$layout_filename = $l_rows[$i]['filename'];
			$layout_preview = ($is_layout_special ? (empty($layout_filename) ? '#' : append_sid($layout_filename)) : (empty($layout_filename) ? (CMS_PAGE_HOME . '?page=' . $layout_id) : append_sid($layout_filename)));
			$layout_locked = false;

			$select_name = 'auth_view_' . $layout_id;
			$default = $l_rows[$i]['view'];
			$options_array = array(0, 1, 2, 3, 4);
			$options_langs_array = array($lang['B_ALL'], $lang['B_GUESTS'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN']);
			$select_js = '';
			$auth_view_select_box = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			if ($is_layout_special)
			{
				$layout_locked = !empty($l_rows[$i]['locked']) ? true : false;
				$auth_view_select_box = auth_select('auth_view_' . $layout_id, $l_rows[$i]['view']);
			}

			$template->assign_block_vars('layout.l_row', array(
				'ROW_CLASS' => $row_class,
				'ROW_DEFAULT_STYLE' => ($layout_id == $default_portal_id) ? 'font-weight: bold;' : '',
				'LAYOUT_ID' => $layout_id,
				'LAYOUT_NAME' => $layout_name,
				'LAYOUT_FILENAME' => (empty($layout_filename) ? $lang['None'] : htmlspecialchars($layout_filename)),
				'LAYOUT_BLOCKS' => count_blocks_in_layout($this->tables['blocks_table'], '\'' . $layout_id . '\'', $is_layout_special, true) . '/' . count_blocks_in_layout($this->tables['blocks_table'], '\'' . $layout_id . '\'', $is_layout_special, false),
				'LAYOUT_TEMPLATE' => $l_rows[$i]['template'],

				'LOCKED' => $layout_locked,
				'PAGE_AUTH' => $auth_view_select_box,
				'GB_CHECKED' => ($l_rows[$i]['global_blocks']) ? ' checked="checked"' : '',
				'BC_CHECKED' => ($l_rows[$i]['page_nav']) ? ' checked="checked"' : '',

				'U_PREVIEW_LAYOUT' => $layout_preview,
				'U_EDIT_LAYOUT' => append_sid($this->root . '?mode=' . $this->mode . '&amp;' . $this->id_var_name . '=' . $layout_id . '&amp;action=edit'),
				'U_DELETE_LAYOUT' => append_sid($this->root . '?mode=' . $this->mode . '&amp;' . $this->id_var_name . '=' . $layout_id . '&amp;action=delete'),
				'U_LAYOUT' => append_sid($this->root . '?mode=' . $this->mode_blocks_name . '&amp;' . $this->id_var_name . '=' . $layout_id)
				)
			);
		}
	}

	/*
	* Get CMS id
	*/
	function get_cms_id()
	{
		global $db, $userdata;

		if (!defined('IN_CMS_USERS'))
		{
			return 0;
		}
		else
		{
			if (($this->l_id) && (!$this->b_id))
			{
				$sql = "SELECT layout_cms_id var_id FROM " . $this->tables['layout_table'] . " WHERE lid = '" . $this->l_id . "'";
			}
			elseif ($this->b_id)
			{
				$sql = "SELECT block_cms_id var_id FROM " . $this->tables['blocks_table'] . " WHERE bid = '" . $this->b_id . "'";
			}
			else
			{
				$sql = "SELECT cu_id  var_id FROM " . CMS_USERS_TABLE . " WHERE cu_user_id = '" . $userdata['user_id'] . "'";
			}
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			return !empty($row['var_id']) ? $row['var_id'] : false;
		}
	}

	/*
	* Get User CMS id
	*/
	function get_user_cms_id()
	{
		global $db, $userdata, $auth;
		$sql = "SELECT cu_id FROM " . CMS_USERS_TABLE . " WHERE cu_user_id = '" . $userdata['user_id'] . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$user_cms_id =  !empty($row['cu_id']) ? $row['cu_id'] : false;
		return $auth->acl_get('cms_view', $this->cms_id) ? true : $user_cms_id;
	}

	/*
	* Get installed blocks
	*/
	function get_installed_blocks()
	{
		global $db, $userdata;

		$sql = "SELECT * FROM " . $this->tables['block_settings_table'] . " WHERE user_id = " . $userdata['user_id'] . " OR locked = '1' ORDER BY locked DESC, name ASC";
		$result = $db->sql_query($sql);
		$b_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $b_rows;
	}

	/*
	* Get block info
	*/
	function get_block_info()
	{
		global $db;

		$sql = "SELECT * FROM " . $this->tables['blocks_table'] . " WHERE bid = '" . $this->b_id . "'";
		$result = $db->sql_query($sql);
		$b_info = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $b_info;
	}

	/*
	* Get block settings info
	*/
	function get_block_settings_info()
	{
		global $db;

		$sql = "SELECT * FROM " . $this->tables['block_settings_table'] . " WHERE bs_id = '" . $this->bs_id . "'";
		$result = $db->sql_query($sql);
		$b_info = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $b_info;
	}

	/*
	* Get all blocks settings detail
	*/
	function get_blocks_settings_detail()
		{
			global $db;

			$bs_rows = $this->get_installed_blocks();
			foreach($bs_rows as $key => $data)
			{
				$bs_array['ID'][$key] = $data['bs_id'];
				$bs_array['TITLE'][$key] = $data['name'];
			}
			return $bs_array;
		}

	/*
	* Get blocks positions for the selected layouts
	*/
	function get_blocks_positions($l_id_list, $b_info_bposition)
	{
		global $db, $lang;

		$sql = "SELECT pkey, bposition FROM " . $this->tables['block_position_table'] . " WHERE layout IN (" . $l_id_list . ") ORDER BY layout, bpid";
		$result = $db->sql_query($sql);

		$position = array();
		$position['select'] = '';
		while ($row = $db->sql_fetchrow($result))
		{
			$row['pkey'] = !empty($lang['cms_pos_' . $row['pkey']]) ? $lang['cms_pos_' . $row['pkey']] : $row['pkey'];
			$position['select'] .= '<option value="' . $row['bposition'] . '" ';
			if($b_info_bposition == $row['bposition'])
			{
				$position['select'] .= 'selected="selected"';
				$position['block'] = $row['bposition'];
			}
			$position['select'] .= '>' . $row['pkey'] . '</option>';
		}
		$db->sql_freeresult($result);

		return $position;
	}

	/*
	* Get max blocks position
	*/
	function get_max_blocks_position($id_var_value, $b_bposition)
	{
		global $db;

		$sql = "SELECT max(weight) mweight FROM " . $this->tables['blocks_table'] . " WHERE layout = '" . $id_var_value . "' AND bposition = '" . $b_bposition . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$weight = $row['mweight'];

		return $weight;
	}

	/*
	* Clear all block configurations entries
	*/
	function delete_block_config_all()
	{
		global $db;

		$sql = "DELETE FROM " . $this->tables['block_config_table'] . " WHERE bid = '" . $this->bs_id . "'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM " . $this->tables['block_variable_table'] . " WHERE bid = '" . $this->bs_id . "'";
		$result = $db->sql_query($sql);

		return true;
	}

	/*
	* Clear single block configuration entry
	*/
	function delete_block_config_single($config_name)
	{
		global $db;

		$sql = "DELETE FROM " . $this->tables['block_config_table'] . " WHERE bid = '" . $this->bs_id . "' AND config_name = '" . $config_name . "'";
		$result = $db->sql_query($sql);

		$sql = "DELETE FROM " . $this->tables['block_variable_table'] . " WHERE bid = '" . $this->bs_id . "' AND config_name = '" . $config_name . "'";
		$result = $db->sql_query($sql);

		return true;
	}

	/*
	* Check if a configuration entry exists
	*/
	function get_existing_block_var($block_variable_name)
	{
		global $db;

		$sql = "SELECT count(1) existing FROM " . $this->tables['block_variable_table'] . "
			WHERE config_name = '" . $block_variable_name . "'
				AND bid = '" . $this->bs_id . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$existing = $row['existing'];

		return $existing;
	}

	/*
	* Get blocks positions
	*/
	function get_blocks_positions_layout($l_id_list)
	{
		global $db;

		$sql = "SELECT bposition, pkey FROM " . $this->tables['block_position_table'] . " WHERE layout IN ('" . $l_id_list . "')";
		$result = $db->sql_query($sql);

		$position = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$position[$row['bposition']] = $row['pkey'];
		}
		$db->sql_freeresult($result);

		return $position;
	}

	/*
	* Get blocks from layouts
	*/
	function get_blocks_from_layouts($block_layout_field, $l_id_list, $sql_no_gb = '')
	{
		global $db, $userdata;

		//$cms_level_sql = " AND edit_auth <= " . $userdata['user_cms_level'] . " ";
		$user_sql = "";
		if (defined('IN_CMS_USERS') && ($this->action == 'editglobal'))
		{
			$cms_id = ($this->cms_id) ? $this->cms_id : 0;
			$user_sql = " AND block_cms_id = '" . $cms_id . "'";
		}
		$sql = "SELECT * FROM " . $this->tables['blocks_table'] . " WHERE " . $block_layout_field . " IN (" . $l_id_list . ")" . $user_sql . $sql_no_gb . " ORDER BY bposition, weight";
		$result = $db->sql_query($sql);
		$b_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $b_rows;
	}

	/*
	* Adjust blocks order
	*/
	function fix_weight_blocks($id_var_value, $table_name)
	{
		global $db;

		$layout_special_sql = "";
		if ($table_name == $this->tables['layout_table'])
		{
			$layout_value = $id_var_value;
			$layout_special_value = 0;
			$layout_special_sql = " AND layout_special = '" . $layout_special_value . "'";
		}
		elseif ($table_name == $this->tables['layout_special_table'])
		{
			$layout_value = 0;
			$layout_special_value = $id_var_value;
			$layout_special_sql = " AND layout_special = '" . $layout_special_value . "'";
		}
		else
		{
			message_die(GENERAL_ERROR, 'Wrong table');
		}

		$sql = "SELECT DISTINCT bposition FROM " . $this->tables['blocks_table'] . " WHERE layout = '" . $layout_value . "'" . $layout_special_sql;
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$count = sizeof($rows);

		for($i = 0; $i < $count; $i++)
		{
			$sql = "SELECT bid FROM ". $this->tables['blocks_table'] . " WHERE layout = '" . $layout_value . "'" . $layout_special_sql . " AND bposition = '" . $rows[$i]['bposition'] . "' ORDER BY weight ASC";
			$result1 = $db->sql_query($sql);

			$weight = 0;
			while($row = $db->sql_fetchrow($result1))
			{
				$weight++;
				$sql = "UPDATE " . $this->tables['blocks_table'] . " SET weight = '" . $weight . "' WHERE bposition = '" . $rows[$i]['bposition'] . "' AND bid = '" . $row['bid'] . "'";
				$result2 = $db->sql_query($sql);
			}
		}
	}

	/*
	* Get max layout id
	*/
	function get_max_layout_id()
	{
		global $db;

		$sql = "SELECT lid FROM " . $this->tables['layout_table'] . " ORDER BY lid desc LIMIT 1";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row['lid'];
	}
}

?>