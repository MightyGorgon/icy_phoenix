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
	var $root = '';
	var $mode = false;
	var $action = false;
	var $l_id = false; // Layout ID
	var $ls_id = false; // Layout Special ID
	var $b_id = false; // Block ID
	var $bs_id = false; // Block Settings ID

	var $gb_pos = array('gh', 'gf', 'gt', 'gb', 'gl', 'gr', 'hh', 'hl', 'hc', 'fc', 'fr', 'ff'); // Global Blocks Positions

	var $sort_cid_prefix = 'c_'; // Sortables Parent Container Prefix
	var $sort_sid_prefix = 's_'; // Sortables UL Container Prefix
	var $sort_eid_prefix = 'e_'; // Sortables LI Element Prefix

	var $menu_images_root = 'templates/common/images/menu/';

	var $is_auth = array();

	/**
	* Some defaults
	*/
	function cms_admin()
	{
		global $db, $cache, $config, $auth, $user, $lang, $template;

		$this->root = CMS_PAGE_CMS;

		// Let's remove $auth->acl_get('a_') until I finish coding permissions properly... and also add/remove 'a_' when users are added/removed from administrators in ACP
		//$is_admin = (($user->data['user_level'] == ADMIN) || $auth->acl_get('a_')) ? true : false;
		$is_admin = ($user->data['user_level'] == ADMIN) ? true : false;

		$this->is_auth['cms_admin'] = ($is_admin || $auth->acl_get('cms_admin')) ? true : false;
		$this->is_auth['cms_ads'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_ads')) ? true : false;
		$this->is_auth['cms_blocks'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_blocks')) ? true : false;
		$this->is_auth['cms_blocks_global'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_blocks_global')) ? true : false;
		$this->is_auth['cms_layouts'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_layouts')) ? true : false;
		$this->is_auth['cms_layouts_special'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_layouts_special')) ? true : false;
		$this->is_auth['cms_menu'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_menu')) ? true : false;
		$this->is_auth['cms_permissions'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_permissions')) ? true : false;
		$this->is_auth['cms_settings'] = ($is_admin || $auth->acl_get('cms_admin') || $auth->acl_get('cms_settings')) ? true : false;

		$template->assign_vars(array(
			'S_AUTH_CMS_ADMIN' => $this->is_auth['cms_admin'],
			'S_AUTH_CMS_ADS' => $this->is_auth['cms_ads'],
			'S_AUTH_CMS_BLOCKS' => $this->is_auth['cms_blocks'],
			'S_AUTH_CMS_BLOCKS_GLOBAL' => $this->is_auth['cms_blocks_global'],
			'S_AUTH_CMS_LAYOUTS' => $this->is_auth['cms_layouts'],
			'S_AUTH_CMS_LAYOUTS_SPECIAL' => $this->is_auth['cms_layouts_special'],
			'S_AUTH_CMS_MENU' => $this->is_auth['cms_menu'],
			'S_AUTH_CMS_PERMISSIONS' => $this->is_auth['cms_permissions'],
			'S_AUTH_CMS_SETTINGS' => $this->is_auth['cms_settings'],
			)
		);
	}

	/*
	* Init CMS vars
	*/
	function init_vars($mode_array, $action_array)
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

		if (isset($_REQUEST['l_id']))
		{
			$l_id = request_var('l_id', 0);
			$this->l_id = ($l_id < 0) ? false : $l_id;
		}

		if (isset($_REQUEST['ls_id']))
		{
			$ls_id = request_var('ls_id', 0);
			$this->ls_id = ($ls_id < 0) ? false : $ls_id;
		}

		if ($this->l_id !== false)
		{
			$this->ls_id = false;
		}
		elseif ($this->ls_id !== false)
		{
			$this->l_id = false;
		}

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

		return true;
	}

	/*
	* Generate nav tabs
	*/
	function generate_nav_tabs($mode, $sep = '&nbsp;|&nbsp;')
	{
		global $db, $cache, $config, $user, $lang, $template;

		$tabs_array = array();

		$tabs_array[] = array(
			'TITLE' => $lang['CMS_TITLE'],
			'MODE' => array(false, 'layouts', 'block_settings', 'blocks'),
			'LINKS' => array(
				'<a href="' . IP_ROOT_PATH . $this->root . '">' . strtoupper($lang['CMS_USERS_INDEX']) . '</a>',
				'<a href="' . IP_ROOT_PATH . $this->root . '?mode=layouts">' . strtoupper($lang['CMS_USERS_LAYOUTS']) . '</a>',
				'<a href="' . IP_ROOT_PATH . $this->root . '?mode=block_settings">' . strtoupper($lang['CMS_BLOCK_SETTINGS']) . '</a>',
				'<a href="' . IP_ROOT_PATH . $this->root . '?mode=blocks&amp;l_id=0&amp;action=editglobal">' . strtoupper($lang['CMS_GLOBAL_BLOCKS']) . '</a>',
				'<a href="' . IP_ROOT_PATH . 'cms_menu.' . PHP_EXT . '">' . $lang['CMS_USERS_MENU_UPPERCASE'] . '</a>'
			),
			'AUTH' => AUTH_ALL,
		);
		$tabs_array[] = array(
			'TITLE' => $lang['CMS_SETTINGS'],
			'MODE' => array('config', 'auth', 'profile'),
			'LINKS' => array(
				'<a href="' . IP_ROOT_PATH . $this->root . '?mode=config">' . strtoupper($lang['CMS_USERS_CONFIG']) . '</a>',
				'<a href="' . IP_ROOT_PATH . $this->root . '?mode=auth">' . strtoupper($lang['CMS_AUTH']) . '</a>',
				'<a href="' . IP_ROOT_PATH . CMS_PAGE_PROFILE_MAIN . '">' . strtoupper($lang['CMS_USERS_PROFILE']) . '</a>'
			),
			'AUTH' => AUTH_ALL,
		);
		if ($mode == 'cms_users')
		{
			$tabs_array[] = array(
				'TITLE' => $lang['CMS_USERS'],
				'MODE' => array('userlist', 'new_users'),
				'LINKS' => array(
					'<a href="' . IP_ROOT_PATH . $this->root . '?mode=userlist">' . strtoupper($lang['CMS_USERS_USERLIST']) . '</a>',
					'<a href="' . IP_ROOT_PATH . $this->root . '?mode=new_users">' . strtoupper($lang['CMS_USERS_USERLIST_NEW']) . '</a>'
				),
				'AUTH' => AUTH_ADMIN,
			);
		}
		$tabs_array[] = array(
			'TITLE' => $lang['CMS_LINKS'],
			'MODE' => array(),
			'LINKS' => array(
				'<a href="' . IP_ROOT_PATH . ADM . '/index.' . PHP_EXT . '?sid=' . $user->data['session_id'] . '">' . strtoupper($lang['LINK_ACP']) . '</a>',
				'<a href="' . IP_ROOT_PATH . CMS_PAGE_CMS . '?sid=' . $user->data['session_id'] . '">' . strtoupper($lang['LINK_CMS']) . '</a>',
				'<a href="' . IP_ROOT_PATH . CMS_PAGE_HOME . '">' . strtoupper($lang['LINK_HOME']) . '</a>',
				'<a href="' . IP_ROOT_PATH . CMS_PAGE_FORUM . '">' . strtoupper($lang['LINK_FORUM']) . '</a>',
				'<a href="http://www.icyphoenix.com" target="_blank">ICY PHOENIX</a>'
			),
			'AUTH' => AUTH_ALL,
		);

		$tabs_counter = 0;
		$current_nav = false;
		foreach ($tabs_array as $tab_data)
		{
			if(check_auth_level($tab_data['AUTH']))
			{
				$selected = '';
				if (in_array($this->mode, $tab_data['MODE']))
				{
					$selected = 'selected';
					$current_nav = empty($current_nav) ? implode($sep, $tab_data['LINKS']) : $current_nav;
				}

				$template->assign_block_vars('tabs', array(
					'TABS_ID' => $tabs_counter,
					'TABS_TITLE' => $tab_data['TITLE'],
					'TABS_NAV' => implode($sep, $tab_data['LINKS']),
					'SELECTED' => $selected,
					)
				);
				$tabs_counter++;
			}
		}

		$template->assign_vars(array(
			'CMS_PAGE_TITLE' => false,
			'N_TABS' => $tabs_counter,
			'CURRENT_NAV' => $current_nav,
			)
		);

		return true;
	}

	/*
	/*
	* Generate tabs
	*/
	function generate_tabs($mode)
	{
		global $db, $cache, $config, $auth, $user, $lang, $template;

		$tabs_array = array();

		// Let's remove $auth->acl_get('a_') until I finish coding permissions properly... and also add/remove 'a_' when users are added/removed from administrators in ACP
		//$is_admin = (($user->data['user_level'] == ADMIN) || $auth->acl_get('a_')) ? true : false;
		$is_admin = ($user->data['user_level'] == ADMIN) ? true : false;

		$tabs_array[] = array('TITLE' => $lang['CMS_TITLE'], 'MODE' => false, 'LINK' => append_sid(IP_ROOT_PATH . $this->root), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_home.png', 'TIP' => $lang['CMS_TIP_TITLE'], 'AUTH' => AUTH_REG);

		if ($this->is_auth['cms_layouts'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_CUSTOM_PAGES'], 'MODE' => 'layouts', 'LINK' => append_sid(IP_ROOT_PATH . $this->root . '?mode=layouts'), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_custom_pages.png', 'TIP' => $lang['CMS_TIP_CUSTOM_PAGES'], 'AUTH' => AUTH_REG);
		}

		if ($this->is_auth['cms_layouts_special'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_STANDARD_PAGES'], 'MODE' => 'layouts_special', 'LINK' => append_sid(IP_ROOT_PATH . $this->root . '?mode=layouts_special'), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_standard_pages.png', 'TIP' => $lang['CMS_TIP_STANDARD_PAGES'], 'AUTH' => AUTH_REG);
		}

		if ($this->is_auth['cms_blocks'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_BLOCK_SETTINGS'], 'MODE' => 'block_settings', 'LINK' => append_sid(IP_ROOT_PATH . $this->root . '?mode=block_settings'), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_blocks.png', 'TIP' => $lang['CMS_TIP_BLOCK_SETTINGS'], 'AUTH' => AUTH_REG);
		}

		if ($this->is_auth['cms_blocks_global'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_GLOBAL_BLOCKS'], 'MODE' => 'blocks', 'LINK' => append_sid(IP_ROOT_PATH . $this->root . '?mode=blocks&amp;l_id=0&amp;action=editglobal'), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_blocks_global.png', 'TIP' => $lang['CMS_TIP_GLOBAL_BLOCKS'], 'AUTH' => AUTH_REG);
		}

		if ($this->is_auth['cms_permissions'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_AUTH'], 'MODE' => 'auth', 'LINK' => append_sid(IP_ROOT_PATH . $this->root . '?mode=auth'), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_permissions.png', 'TIP' => $lang['CMS_TIP_AUTH'], 'AUTH' => AUTH_REG);
		}

		if ($this->is_auth['cms_settings'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_CONFIG'], 'MODE' => 'config', 'LINK' => append_sid(IP_ROOT_PATH . $this->root . '?mode=config'), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_settings.png', 'TIP' => $lang['CMS_TIP_CONFIG'], 'AUTH' => AUTH_REG);
		}

		if ($this->is_auth['cms_menu'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_MENU_PAGE'], 'MODE' => 'menu', 'LINK' => append_sid(IP_ROOT_PATH . 'cms_menu.' . PHP_EXT), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_menu.png', 'TIP' => $lang['CMS_TIP_MENU'], 'AUTH' => AUTH_REG);
		}

		if ($this->is_auth['cms_ads'])
		{
			$tabs_array[] = array('TITLE' => $lang['CMS_ADS'], 'MODE' => 'ads', 'LINK' => append_sid(IP_ROOT_PATH . 'cms_ads.' . PHP_EXT), 'ICON' => IP_ROOT_PATH . $this->menu_images_root . 'cms_ads.png', 'TIP' => $lang['CMS_TIP_ADS'], 'AUTH' => AUTH_REG);
		}

		$tabs_counter = 0;
		$current_nav = false;
		foreach ($tabs_array as $tab_data)
		{
			$selected = false;
			if(check_auth_level($tab_data['AUTH']))
			{
				if ($mode == $tab_data['MODE'])
				{
					$selected = true;
				}

				$template->assign_block_vars('tabs', array(
					'TAB_ID' => $tabs_counter,
					'TAB_TITLE' => $tab_data['TITLE'],
					'TAB_LINK' => $tab_data['LINK'],
					'TAB_ICON' => $tab_data['ICON'],
					'TAB_TIP' => (empty($tab_data['TIP']) ? $tab_data['TITLE'] : $tab_data['TIP']),
					'S_SELECTED' => $selected,
					)
				);
				$tabs_counter++;
			}
		}

		$template->assign_vars(array(
			'N_TABS' => $tabs_counter,
			)
		);

		return true;
	}

	/*
	* Manage block
	*/
	function manage_block()
	{
		global $class_form, $template, $lang, $auth;

		$l_row = $this->get_global_blocks_layout();

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
		$select_name = 'bs_id';
		$default = $b_info['bs_id'];
		$select_js = '';
		$b_block_parent = $class_form->build_select_box($select_name, $default, $bs_array['ID'], $bs_array['TITLE'], $select_js);

		$b_title = (isset($_POST['title'])) ? request_post_var('title', '', true) : (!empty($b_info['title']) ? $b_info['title'] : '');
		$b_active = (isset($_POST['active'])) ? request_post_var('active', 0) : ($b_info['active'] ? $b_info['active'] : 0);
		$b_local = (isset($_POST['local'])) ? request_post_var('local', 0) : ($b_info['local'] ? $b_info['local'] : 0);
		$b_titlebar = (isset($_POST['titlebar'])) ? request_post_var('titlebar', 0) : ($b_info['titlebar'] ? $b_info['titlebar'] : 0);
		$b_border = (isset($_POST['border'])) ? request_post_var('border', 0) : ($b_info['border'] ? $b_info['border'] : 0);
		$b_background = (isset($_POST['background'])) ? request_post_var('background', 0) : ($b_info['background'] ? $b_info['background'] : 0);

		$template->assign_vars(array(
			'CMS_TITLE' => $b_title,
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

		return true;
	}

	/*
	* Save block
	*/
	function save_block()
	{
		global $db, $class_db, $lang, $user;

		$inputs_array = array(
			'title' => '',
			'bposition' => '',
			'active' => '',
			'bs_id' => '',
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

		if(in_array($data['b_position'], $this->gb_pos))
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

		return true;
	}

	/*
	* Delete block
	*/
	function delete_block_force($settings_remove = false)
	{
		global $db;

		if(!empty($this->b_id))
		{
			$sql = "DELETE FROM " . $this->tables['blocks_table'] . " WHERE bid = " . $this->b_id;
			$result = $db->sql_query($sql);
			if (!empty($settings_remove))
			{
				$this->delete_block_config_all();
			}
			$this->fix_weight_blocks($this->id_var_value, $this->table_name);
			$this->fix_weight_blocks(0, $this->table_name);
			return true;
		}

		return false;
	}


	/*
	* Delete block
	*/
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
			if(!empty($this->b_id))
			{
				$this->delete_block_force(false);
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
			redirect(append_sid($this->root . '?mode=blocks&amp;' . $this->id_var_name . '=' . $this->id_var_value . $redirect_action));
		}

		return true;
	}

	/*
	* Update blocks
	*/
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

			if (!empty($b_count))
			{
				$db->sql_transaction();
				for($i = 0; $i < $b_count; $i++)
				{
					$b_active = empty($blocks_upd) ? 0 : (in_array($b_rows[$i]['bid'], $blocks_upd) ? 1 : 0);
					$b_weight = (isset($_POST['weight'][$b_rows[$i]['bid']]) ? intval($_POST['weight'][$b_rows[$i]['bid']]) : $b_rows[$i]['weight']);
					$sql = "UPDATE " . $this->tables['blocks_table'] . "
									SET active = '" . $b_active . "', weight = '" . $b_weight . "'
									WHERE bid = '" . $b_rows[$i]['bid'] . "'";
					$result = $db->sql_query($sql);
				}
				$db->sql_transaction('commit');
				$this->fix_weight_blocks($this->id_var_value, $this->table_name);
			}

			redirect(append_sid($this->root . '?mode=' . $this->mode . '&amp;' . $this->id_var_name . '=' . $this->id_var_value . $action_append));
		}

		return true;
	}

	/*
	* Move block
	*/
	function move_block($move)
	{
		global $db, $lang;

		$b_weight = request_var('weight', '');
		$b_position = request_var('pos', '');
		if(in_array($b_position, $this->gb_pos))
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

		return true;
	}

	/*
	* Generate URLs used in Ajax
	*/
	function ajax_urls()
	{
		// append_sid($this->root . '?mode=layouts&amp;action=edit' . '&amp;' . $this->id_var_name . '=' . $this->id_var_value)
		return array(
			'blocks' => append_sid($this->root . '?mode=block_settings', true)
			);
	}

	/*
	* Ajax action
	*/
	function show_blocks_list_action($action)
	{
		global $db, $lang;
		$total = intval(request_var('total', 0));
		if(!$total) return false;
		$keys = array(
			'bs_id' => 'intval',
			'title' => 'strval',
			'bposition' => 'strval',
			'weight' => 'intval',
			'active' => 'intval',
			'border' => 'intval',
			'titlebar' => 'intval',
			'background' => 'intval',
			'local' => 'intval',
		);
		switch($action)
		{
			case 'update':
				for($i = 0; $i < $total; $i++)
				{
					$prefix = 'p' . $i . '_';
					$id = intval(request_var($prefix . 'bid', 0));
					if(!$id) return array('error' => true);
					$update = '';
					foreach($keys as $key => $func)
					if(isset($_POST[$prefix . $key]))
					{
						$update .= (strlen($update) ? ', ' : '') . $key . '=\'' . $db->sql_escape($func($_POST[$prefix . $key])) . '\'';
					}
					if(strlen($update))
					{
						// update database
						$sql = "UPDATE " . $this->tables['blocks_table'] . " SET " . $update . " WHERE " . $this->block_layout_field . " = '" . $this->id_var_value . "' AND bid = " . $id;
						$db->sql_query($sql);
					}
				}
				return array('changed' => $total);
			case 'delete':
				$ids = array();
				for($i = 0; $i < $total; $i++)
				{
					$prefix = 'p' . $i . '_';
					$id = intval(request_var($prefix . 'bid', 0));
					if(!$id) return array('error' => true);
					$ids[] = $id;
				}
				if(!count($ids)) return array('reload' => true);
				$sql = "DELETE FROM " . $this->tables['blocks_table'] . " WHERE " . $this->block_layout_field . " = '" . $this->id_var_value . "' AND bid IN (" . implode(', ', $ids) . ")";
				$db->sql_query($sql);
				return array('changed' => $total);
			case 'add':
				// add new blocks
				$ids = array();
				for($i = 0; $i < $total; $i++)
				{
					$prefix = 'p' . $i . '_';
					$vars = array();
					foreach($keys as $key => $func)
					if(isset($_POST[$prefix . $key]))
					{
						$vars[$key] = $db->sql_escape($func($_POST[$prefix . $key]));
					}
					$vars[$this->block_layout_field] = $this->id_var_value;
					$sql = "INSERT INTO " . $this->tables['blocks_table'] . " " . $db->sql_build_insert_update($vars, true);
					$result = $db->sql_query($sql);
					$id = $db->sql_nextid();
					if(!$id) return array('reload' => true);
					$ids[] = $id;
				}
				if(!count($ids)) return array('reload' => true);
				// get full block data from db
				$sql = "SELECT * FROM " . $this->tables['blocks_table'] . " WHERE bid IN (" . implode(', ', $ids) . ")";
				$result = $db->sql_query($sql);
				$items = array();
				while($row = $db->sql_fetchrow($result))
				{
					$items[] = $row;
				}
				$db->sql_freeresult($result);
				return array('added' => true, 'items' => $items);
			default:
				return array('reload' => true);
		}
	}

	/*
	* Show blocks list
	*/
	function show_blocks_list_ajax()
	{
		//if(defined('CMS_NO_AJAX') || ($this->mode_layout_name == 'layouts_special') || (($this->mode != 'blocks') && ($this->action != 'editglobal')))
		if(defined('CMS_NO_AJAX'))
		{
			// invalid action. use old function
			return $this->show_blocks_list();
		}
		// get stuff
		global $db, $template, $lang, $theme;

		$l_info = $this->get_layout_info();
		if(is_array($l_info))
		{
			$l_name = $l_info['name'];
			$l_filename = $l_info['filename'];
		}
		else
		{
			$l_name = '';
			$l_filename = '';
		}

		// json stuff
		$j_request = request_var('json', false);
		$action = request_var('json_action', '');
		if(strlen($action))
		{
			$result = $this->show_blocks_list_action($action);
			if(is_array($result)) return $result;
		}

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
			'JQ_SORT' => true,
			'LAYOUT_NAME' => $l_name,
			'PAGE_URL' => $page_url,
			'PAGE' => strval($this->id_var_value),
			'U_LAYOUT_EDIT' => (($this->block_layout_field == 'layout') ? append_sid($this->root . '?mode=layouts&amp;action=edit' . '&amp;' . $this->id_var_name . '=' . $this->id_var_value) : ''),
			)
		);
		$sql_no_gb = ($this->action == 'editglobal') ? ' AND layout_special = 0 ' : '';
		$b_rows = $this->get_blocks_from_layouts($this->block_layout_field, $l_id_list, $sql_no_gb);
		$b_count = !empty($b_rows) ? sizeof($b_rows) : 0;

		// Reassign $l_id_list
		if ($this->id_var_name == 'l_id')
		{
			$l_id_list = "'" . $this->id_var_value . "', '0'";
		}
		else
		{
			$l_id_list = "'0'";
		}

		$position = $this->get_blocks_positions_layout($l_id_list);
		if (!$b_count)
		{
			// no blocks
			// return $j_request ? array('reload' => true) : false;
		}

		// generate result
		$position_text = array();
		foreach($position as $key => $value) $position_text[$key] = $lang['cms_pos_' . $value];
		$json = array(
			'info' => $l_info,
			'pos' => $position,
			'postext' => $position_text,
			'rows' => $b_rows,
			'blocks' => $this->get_parent_blocks(),
			'urls' => $this->ajax_urls(),
			'edit' => append_sid($this->root . '?mode=block_settings&action=edit&bs_id={ID}', true),
			'post' => array(
				'url' => append_sid($this->root),
				'mode' => $this->mode,
				$this->id_var_name => $this->id_var_value
			)
		);

		if($this->action !== false)
		{
			$json['post']['action'] = $this->action;
		}
		// get list of available blocks
		$bs_rows = $this->get_parent_blocks();
		$json['all'] = $bs_rows;
		if(!$j_request)
		{
			// non-ajax action
			define('AJAX_CMS', true);
			// echo '<pre>', htmlspecialchars(print_r($json, true)), '</pre>';
			foreach($position as $key => $row)
			{
				$template->assign_block_vars($row . '_blocks_row', array('CMS_BLOCK' => '<ul class="cms-editor-container cms-block-' . $key . '"></ul>', 'OUTPUT' => '<ul class="cms-editor-container cms-block-' . $key . '"></ul>'));
			}
			$template->assign_vars(array('JSON_DATA' => json_encode($json)));
			if(is_array($l_info) && ($this->mode_layout_name != 'layouts_special'))
			{
				$template->set_filenames(array('layout_blocks' => 'layout/' . $l_info['template']));
			}
			elseif($this->mode_layout_name == 'layouts_special')
			{
				$template->assign_vars(array('S_PAGE_LAYOUT' => true));
			}
			else
			{
				$template->assign_vars(array('S_GLOBAL_LAYOUT' => true));
			}
			return true;
		}
		return $json;
	}

	/*
	* Show blocks list
	*/
	function show_blocks_list()
	{
		global $db, $template, $lang, $theme;

		$l_info = $this->get_layout_info();
		$l_name = $l_info['name'];
		$l_filename = $l_info['filename'];

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
			'JQ_SORT' => true,
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
				$l_id_list = "'" . $this->id_var_value . "', '0'";
			}
			else
			{
				$l_id_list = "'0'";
			}

			$position = $this->get_blocks_positions_layout($l_id_list);

			if ($b_count > 0)
			{
				$else_counter = 0;
				$pos_change = false;

				// keep track of which block is whose's parent
				// bfp = block for parent
				$bs_rows = $this->get_parent_blocks();
				$bfp_rows = array();
				foreach ($bs_rows as $bfp_row)
				{
					$bfp_rows[$bfp_row['bs_id']] = $bfp_row['name'];
				}

				$row_class = '';
				for($i = 0; $i < $b_count; $i++)
				{
					if (($b_rows[$i]['layout_special'] != 0) && ($this->action == 'editglobal'))
					{
					}
					else
					{
						$bs_id = $b_rows[$i]['bs_id'];
						$b_id = $b_rows[$i]['bid'];
						$b_weight = $b_rows[$i]['weight'];
						$pos_change = (($i == 0) || ($b_position != $b_rows[$i]['bposition'])) ? true : false;
						$b_position = $b_rows[$i]['bposition'];
						$b_position_l = !empty($lang['cms_pos_' . $position[$b_position]]) ? $lang['cms_pos_' . $position[$b_position]] : $row['pkey'];
						$else_counter++;

						if (($this->l_id == 0) && ($this->id_var_name == 'l_id'))
						{
							$redirect_action = '&amp;action=editglobal';
						}
						else
						{
							$redirect_action = '&amp;action=list';
						}

						$row_class = ip_zebra_rows($row_class);
						$template->assign_block_vars('blocks', array(
							'ROW_CLASS' => $row_class,
							'FIRST_ID' => ($i == 0) ? true : false,
							'LAST_ID' => ($i == ($b_count - 1)) ? true : false,
							'SORT_CID' => $this->sort_cid_prefix . $b_rows[$i]['bposition'],
							'SORT_SID' => $this->sort_sid_prefix . $b_rows[$i]['bposition'],
							'SORT_EID' => $this->sort_eid_prefix . $b_rows[$i]['bid'],
							'POSITION_ID' => $b_rows[$i]['bposition'],
							'POSITION_CHANGE' => (!empty($pos_change)) ? true : false,
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

							// Query the block's parent, and add informations about it
							'BLOCK_PARENT' => $bfp_rows[$b_rows[$i]['bs_id']],
							'WEIGHT' => $b_rows[$i]['weight'],
							'BLOCK_TIP' => $lang['CMS_BLOCK_PARENT'] . ': ' . htmlspecialchars($bfp_rows[$b_rows[$i]['bs_id']]) . htmlspecialchars('<br />') . "\r\n" . $lang['B_BORDER'] . ': ' . (($b_rows[$i]['border']) ? $lang['YES'] : $lang['NO']) . htmlspecialchars('<br />') . "\r\n" . $lang['B_TITLEBAR'] . ': ' . (($b_rows[$i]['titlebar']) ? $lang['YES'] : $lang['NO']) . htmlspecialchars('<br />') . "\r\n" . $lang['B_LOCAL'] . ': ' . (($b_rows[$i]['border']) ? $lang['YES'] : $lang['NO']) . htmlspecialchars('<br />') . "\r\n" . $lang['B_BACKGROUND'] . ': ' . (($b_rows[$i]['border']) ? $lang['YES'] : $lang['NO']),

							'U_EDIT_BS' => append_sid($this->root . '?mode=block_settings&amp;action=edit&amp;&amp;bs_id=' . $bs_id),
							'U_EDIT' => append_sid($this->root . '?mode=' . $this->mode . '&amp;action=edit&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;b_id=' . $b_id),
							'U_DELETE' => append_sid($this->root . '?mode=' . $this->mode . '&amp;action=delete&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;b_id=' . $b_id),
							'U_MOVE_UP' => append_sid($this->root . '?mode=' . $this->mode . $redirect_action . '&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;move=1&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position),
							'U_MOVE_DOWN' => append_sid($this->root . '?mode=' . $this->mode . $redirect_action . '&amp;' . $this->id_var_name . '=' . $this->id_var_value . '&amp;move=0&amp;b_id=' . $b_id . '&amp;weight=' . $b_weight . '&amp;pos=' . $b_position)
							)
						);

						if (!empty($pos_change))
						{
							$template->assign_block_vars('jq_sort', array(
								'ID' => $this->sort_sid_prefix . $b_rows[$i]['bposition'],
								//'PROP' => 'containment: "#' . $this->sort_cid_prefix . $b_rows[$i]['bposition'] . '", handle: "img.sort-handler", axis: "y"',
								// generate drag-and-drop code for jQuery,
								// which will set the correct block weight when dragging around.
								'PROP' => 'containment: "#' . $this->sort_cid_prefix . $b_rows[$i]['bposition'] . '", handle: "img.sort-handler", axis: "y",
									stop: function (event, ui)
									{
										var pos = 0;
										$("li", "#' . $this->sort_sid_prefix . $b_rows[$i]['bposition'] . '").each(function ()
										{
											$(this).find("input.block_weight").val(++pos);
										});
									}',
								)
							);
						}
					}
				}
			}
			else
			{
				$template->assign_var('S_NO_BLOCKS', true);
				return false;
			}
		}

		return true;
	}

	/*
	* Manage block settings
	*/
	function manage_block_settings()
	{
		global $db, $lang, $class_form, $template, $user;

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
			if(!empty($this->bs_id))
			{
				$b_info = $this->get_block_settings_info();
				if((($b_info['locked'] == '1') || ($b_info['user_id'] != $user->data['user_id'])) && ($user->data['user_level'] != ADMIN))
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

		if ($user->data['user_level'] == ADMIN)
		{
			$blocks_array = $this->get_blocks_files_list();
			$options_array = array();
			$options_langs_array = array();
			$options_array[] = '';
			$options_langs_array[] = '[&nbsp;' . $lang['B_TEXT_BLOCK'] . '&nbsp;]';
			foreach ($blocks_array as $block_file)
			{
				$options_array[] = BLOCKS_PREFIX . $block_file;
				$lang_key = (!empty($lang['cms_block_' . $block_file]) ? ('&nbsp;' . $lang['cms_block_' . $block_file] . '') : '');
				$options_langs_array[] = $lang_key ? "$lang_key [$block_file]" : $block_file;
			}

			$block_content_file_old = $b_info['blockfile'];

			$b_info['blockfile'] = (isset($_POST['blockfile'])) ? request_var('blockfile', '') : $b_info['blockfile'];

			$select_name = 'blockfile';
			$default = $b_info['blockfile'];
			$select_js = '';

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

		$b_info['view'] = (isset($_POST['view'])) ? request_post_var('view', 0) : $b_info['view'];

		$select_name = 'view';
		$default = $b_info['view'];
		$options_array = array(0, 1, 2, 3, 4, 8);
		$options_langs_array = array($lang['B_ALL'], $lang['B_GUESTS'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN'], $lang['B_ALL_NO_BOTS']);
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
			//$this->s_hidden_fields .= '<input type="hidden" name="name" value="' . htmlspecialchars($b_name) . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="name" value="' . $b_name . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="view" value="' . $b_info['view'] . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="locked" value="' . $locked_hidden . '" />';
			$this->s_hidden_fields .= $b_group_hidden;
		}
		elseif($block_content != false)
		{
			$template->assign_var('CMS_PAGE_TITLE', $lang['BLOCKS_CREATION_02']);
			$this->s_hidden_fields .= '<input type="hidden" name="blockfile" value="' . $block_content_file . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="message" value="" />';
			$this->s_hidden_fields .= '<input type="hidden" name="type" value="0" />';
			//$this->s_hidden_fields .= '<input type="hidden" name="name" value="' . htmlspecialchars($b_name) . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="name" value="' . $b_name . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="view" value="' . $b_info['view'] . '" />';
			$this->s_hidden_fields .= '<input type="hidden" name="locked" value="' . $locked_hidden . '" />';
			$this->s_hidden_fields .= $b_group_hidden;

			$block_vars_default = array();
			$block_count_variables = 0;
			if(!empty($block_content_file))
			{
				$block_vars_default = $this->get_block_vars_default($block_content_file);
				$block_count_variables = sizeof($block_vars_default);
				$block_vars_default_names = array();
				for($i = 0; $i < $block_count_variables; $i++)
				{
					$block_vars_default_names[$block_vars_default[$i]['config_name']] = $i;
				}
			}

			if (($this->bs_id > 0) && ($block_content_file == $block_content_file_old))
			{
				$sql = "SELECT * FROM " . $this->tables['block_config_table'] . " AS c, " . $this->tables['block_variable_table'] . " AS bv
									WHERE c.bid = '" . $this->bs_id . "'
										AND bv.bid = '" . $this->bs_id . "'
										AND c.config_name = bv.config_name
									ORDER BY c.id";
				$result = $db->sql_query($sql);

				$rows_counter = 0;
				$vars_counter = 0;
				$block_vars_existing_names = array();
				while($row = $db->sql_fetchrow($result))
				{
					if (in_array($row['config_name'], $block_vars_default_names))
					{
						create_cms_field_tpl($row, false);
						$vars_counter++;
					}
					$block_vars_existing_names[$row['config_name']] = $rows_counter;
					$rows_counter++;
				}

				for($i = 0; $i < $block_count_variables; $i++)
				{
					if (!in_array($block_vars_default[$i]['config_name'], $block_vars_existing_names))
					{
						create_cms_field_tpl($block_vars_default[$i], false);
						$vars_counter++;
					}
				}

				if (empty($vars_counter))
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
				if(!empty($block_vars_default))
				{
					for($i = 0; $i < $block_count_variables; $i++)
					{
						create_cms_field_tpl($block_vars_default[$i], false);
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

		return true;
	}

	/*
	* Save block settings
	*/
	function save_block_settings()
	{
		global $db, $class_db, $lang, $user;

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
		$data['content'] = htmlspecialchars_decode($data['content'], ENT_COMPAT);
		$data['locked'] = $data['locked'] ? 1 : 0;

		if(!empty($this->bs_id))
		{
			$class_db->update_item($this->bs_id, $data);
		}
		else
		{
			$data['user_id'] = $user->data['user_id'];
			$sql = "INSERT INTO " . $this->tables['block_settings_table'] . " " . $db->sql_build_insert_update($data, true);
			$result = $db->sql_query($sql);
			$this->bs_id = $db->sql_nextid();
		}
		$this->update_block_config($data['blockfile']);
		redirect(append_sid($this->root . '?mode=block_settings', true));

		return true;
	}

	/*
	* Update block config
	*/
	function update_block_config($blockfile)
	{
		global $db;

		$block_vars_default = array();
		$block_count_variables = 0;
		if(!empty($blockfile))
		{
			$block_vars_default = $this->get_block_vars_default($blockfile);
			$block_count_variables = sizeof($block_vars_default);
			$block_vars_default_names = array();
			for($i = 0; $i < $block_count_variables; $i++)
			{
				$block_vars_default_names[$block_vars_default[$i]['config_name']] = $i;
			}
		}

		if(!empty($block_vars_default))
		{
			// Let's empty the previously created config vars...
			$sql = "SELECT * FROM " . $this->tables['block_config_table'] . " WHERE bid = '" . $this->bs_id . "'";
			$result = $db->sql_query($sql);

			while($row = $db->sql_fetchrow($result))
			{
				$delete_var = in_array($row['config_name'], $block_vars_default_names) ? false : true;
				if (!empty($delete_var))
				{
					$this->delete_block_config_single($row['config_name']);
				}
			}
			$db->sql_freeresult($result);

			for($i = 0; $i < $block_count_variables; $i++)
			{
				$config_value_tmp = request_post_var($block_vars_default[$i]['config_name'], '', true);
				$config_value_tmp = htmlspecialchars_decode($config_value_tmp, ENT_COMPAT);
				if (check_http_var_exists($block_vars_default[$i]['config_name'], true))
				{
					$block_vars_default[$i]['config_value'] = $config_value_tmp;
				}

				$block_var_exists = $this->block_var_exists($block_vars_default[$i]['config_name']);

				if(empty($block_var_exists))
				{
					$sql = "INSERT INTO " . $this->tables['block_variable_table'] . " (bid, label, sub_label, config_name, field_options, field_values, type, block)
						VALUES ('" . $this->bs_id ."', '" . $db->sql_escape($block_vars_default[$i]['label']) . "', '" . $db->sql_escape($block_vars_default[$i]['sub_label']) . "', '" . $db->sql_escape($block_vars_default[$i]['config_name']) . "', '" . $db->sql_escape($block_vars_default[$i]['field_options']) . "', '" . $block_vars_default[$i]['field_values'] . "', '" . $block_vars_default[$i]['type'] . "', '" . $db->sql_escape($block_vars_default[$i]['block']) . "')";
					$result = $db->sql_query($sql);

					$sql = "INSERT INTO " . $this->tables['block_config_table'] . " (bid, config_name, config_value)
						VALUES ('" . $this->bs_id ."', '" . $db->sql_escape($block_vars_default[$i]['config_name']) . "', '" . $db->sql_escape($block_vars_default[$i]['config_value']) . "')";
					$result = $db->sql_query($sql);
				}
				else
				{
					$sql = "UPDATE " . $this->tables['block_config_table'] . " SET config_value = '" . $db->sql_escape($block_vars_default[$i]['config_value']) . "'
									WHERE config_name = '" . $db->sql_escape($block_vars_default[$i]['config_name']) . "'
										AND bid = " . $this->bs_id;
					$result = $db->sql_query($sql);
				}
			}
		}
		else
		{
			$this->delete_block_config_all();
		}

		return true;
	}

	/*
	* Delete block settings
	*/
	function delete_block_settings()
	{
		global $db, $template, $lang, $user;

		if(!empty($this->bs_id))
		{
			$b_info = $this->get_block_settings_info();
			if((($b_info['locked'] == '1') || ($b_info['user_id'] != $user->data['user_id'])) && ($user->data['user_level'] != ADMIN))
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

			$sql = "DELETE FROM " . $this->tables['blocks_table'] . " WHERE bs_id = " . $this->bs_id;
			$result = $db->sql_query($sql);

			$this->delete_block_config_all();
			redirect(append_sid($this->root . '?mode=block_settings'));
		}

		return true;
	}

	/*
	* Ajax action
	*/
	function show_blocks_settings_action($action)
	{
		global $db, $lang;
		$id = intval(request_var('bs_id', 0));
		if(!$id) return false;
		$keys = array(
			'user_id' => 'intval',
			'name' => 'strval',
			'content' => 'strval',
			'blockfile' => 'strval',
			'view' => 'intval',
			'type' => 'intval',
			'groups' => 'strval',
			'locked' => 'intval'
		);
		switch($action)
		{
			case 'update':
				$update = '';
				foreach($keys as $key => $func)
				if(isset($_POST[$key]))
				{
					$update .= (strlen($update) ? ', ' : '') . $key . '=\'' . $db->sql_escape($func($_POST[$key])) . '\'';
				}
				if(strlen($update))
				{
					// update database
					$sql = "UPDATE " . $this->tables['block_settings_table'] . " SET " . $update . " WHERE bs_id = " . $id;
					$db->sql_query($sql);
				}
				return array('changed' => 1);
			default:
				return array('reload' => true);
		}
	}

	/*
	* Show blocks settings list
	*/
	function show_blocks_settings_list_ajax()
	{
		global $db, $template, $user, $lang;
		if(defined('CMS_NO_AJAX'))
		{
			return $this->show_blocks_settings_list();
		}
		//return $this->show_blocks_settings_list();
		// do stuff
		$j_request = request_var('json', false);
		$action = request_var('json_action', '');
		if(strlen($action))
		{
			$result = $this->show_blocks_settings_action($action);
			if(is_array($result))
			{
				return $result;
			}
		}
		// get list of blocks
		$blocks = $this->get_parent_blocks();
		// get all layouts
		$layouts = array();
		$sql = "SELECT lid, name FROM " . $this->tables['layout_table'];
		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result))
		{
			$layouts[$row['lid']] = array(
				'lid' => $row['lid'],
				'name' => $row['name'],
				'url' => append_sid($this->root . '?mode=blocks&l_id=' . $row['lid'])
			);
		}
		$db->sql_freeresult($result);
		$layouts_special = array();
		$sql = "SELECT lsid, name FROM " . $this->tables['layout_special_table'];
		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result))
		{
			$layouts_special[$row['lsid']] = array(
				'lsid' => $row['lsid'],
				'name' => isset($lang['auth_view_' . $row['name']]) ? $lang['auth_view_' . $row['name']] : (isset($lang['cms_page_name_' . strtolower($row['name'])]) ? $lang['cms_page_name_' . strtolower($row['name'])] : ucfirst($row['name'])),
				'url' => append_sid($this->root . '?mode=blocks&ls_id=' . $row['lsid'])
			);
		}
		$db->sql_freeresult($result);
		// get list of layouts where blocks used
		$list = array();
		$sql = "SELECT bs_id, layout, layout_special FROM " . $this->tables['blocks_table'];
		$result = $db->sql_query($sql);
		$global_url = append_sid($this->root . '?mode=blocks&l_id=0&action=editglobal');
		while($row = $db->sql_fetchrow($result))
		{
			$bsid = intval($row['bs_id']);
			$layout = intval($row['layout']);
			$special = intval($row['layout_special']);
			if(!isset($list[$bsid]) || !in_array($layout, $list[$bsid]))
			{
				if($layout)
				{
					// layout
					$url = $layouts[$layout]['url'];
					$name = $layouts[$layout]['name'];
				}
				elseif($special)
				{
					// special page
					$url = $layouts_special[$special]['url'];
					$name = $layouts_special[$special]['name'];
				}
				else
				{
					// global
					$url = $global_url;
					$name = $lang['CMS_GLOBAL_BLOCKS'];
				}
				// avoid adding duplicates
				$found = false;
				for($i = 0; $i < count($list[$bsid]); $i++)
				{
					if($list[$bsid][$i]['url'] == $url) $found = true;
				}
				if(!$found)
				{
					$list[$bsid][] = array(
						'bs_id' => $bsid,
						'layout' => $layout,
						'special' => $special,
						'name' => $name,
						'url' => $url
					);
				}
			}
		}
		$db->sql_freeresult($result);
		// blocks list
		$blist = array();
		if($user->data['user_level'] == ADMIN)
		{
			$blocks_array = $this->get_blocks_files_list();
			foreach($blocks_array as $block_file)
			{
				$blist[BLOCKS_PREFIX . $block_file] = $block_file . (!empty($lang['cms_block_' . $block_file]) ? (' [' . $lang['cms_block_' . $block_file] . ']') : '');
			}
		}
		else
		{
			$blist = false;
		}

		// groups list
		$groups = array();
		$groups_data = get_groups_data(false, true, array());
		foreach ($groups_data as $group_data)
		{
			$groups[$group_data['group_id']] = $group_data['group_name'];
		}

		// json data
		$json = array(
			'rows' => $blocks,
			'list' => $list,
			'blist' => $blist,
			'view_id' => array(0, 1, 2, 3, 4, 8),
			'view' => array($lang['B_ALL'], $lang['B_GUESTS'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN'], $lang['B_ALL_NO_BOTS']),
			'groups' => $groups,
			'remove' => append_sid($this->root . '?mode=block_settings&action=delete&bs_id={ID}'),
			'edit' => append_sid($this->root . '?mode=block_settings&action=edit&bs_id={ID}', true),
			'post' => array(
				'url' => append_sid($this->root),
				'mode' => $this->mode
			)
		);
		if($this->action !== false)
		{
			$json['post']['action'] = $this->action;
		}
		// return stuff
		if(!$j_request)
		{
			// non-ajax action
			define('AJAX_CMS', true);
			// echo '<pre>', htmlspecialchars(print_r($json, true)), '</pre>';
			$template->assign_vars(array('JSON_DATA' => json_encode($json)));
			return true;
		}
		return $json;
	}

	/*
	* Show blocks settings list
	*/
	function show_blocks_settings_list()
	{
		global $lang, $theme, $template, $user;

		$b_rows = $this->get_parent_blocks();
		$b_count = !empty($b_rows) ? sizeof($b_rows) : 0;

		if ($b_count > 0)
		{
			$row_class = '';
			for($i = 0; $i < $b_count; $i++)
			{
				$this->bs_id = $b_rows[$i]['bs_id'];
				$b_view = $this->get_block_view_name($b_rows[$i]['view']);
				$groups = (!empty($b_rows[$i]['groups'])) ? get_groups_names($b_rows[$i]['groups']) : $lang['B_ALL'];

				$row_class = ip_zebra_rows($row_class);
				$template->assign_block_vars('blocks', array(
						'ROW_CLASS' => $row_class,
						'NAME' => trim($b_rows[$i]['name']),
						'TYPE' => (empty($b_rows[$i]['blockfile'])) ? (($b_rows[$i]['type']) ? $lang['B_BBCODE'] : $lang['B_HTML']) : '&nbsp;',
						'GROUPS' => $groups,
						'CONTENT' => (empty($b_rows[$i]['blockfile'])) ? $lang['B_TEXT'] : $lang['B_FILE'],
						'VIEW' => $b_view,
						'USERNAME' => colorize_username($b_rows[$i]['user_id']),
						'STATUS' => $b_rows[$i]['locked'],
						'S_MANAGE' => (($b_rows[$i]['locked'] == '1') && ($user->data['user_level'] != ADMIN)) ? false : true,
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

		return true;
	}

	/*
	* Manage layout
	*/
	function manage_layout($is_layout_special)
	{
		global $db, $template, $class_form, $lang;

		if($this->action == 'edit')
		{
			$l_info = $this->get_layout_info();
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

			$layout_details = $this->get_layouts_details($l_info, $template_dir, '.tpl', 'template');
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
			$options_array = array(0, 1, 2, 3, 4, 8);
			$options_langs_array = array($lang['B_ALL'], $lang['B_GUESTS'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN'], $lang['B_ALL_NO_BOTS']);
			$select_js = '';
			$view = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			$group = get_all_usergroups($l_info['groups']);
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

			$group = '';
			$default = empty($l_info['view']) ? 0 : $l_info['view'];
			$view = auth_select('view', $default);
		}

		$template->assign_vars(array(
			'NAME' => (empty($l_info['name']) ? '' : $l_info['name']),
			'FILENAME' => (empty($l_info['filename']) ? '' : $l_info['filename']),
			'PAGE_ID' => (empty($l_info['page_id']) ? '' : $l_info['page_id']),
			'TEMPLATE' => $layout_details,
			'VIEW' => $view,
			'U_EDIT_AUTH' => append_sid($this->root . '?mode=auth&amp;pmode=setting_cms_user_local&amp;id_type=' . ($is_layout_special ? 'layout_special' : 'layout') . '&amp;forum_id[]=' . ($is_layout_special ? $l_info['lsid'] : $l_info['lid'])),
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

		return true;
	}

	/*
	* Save layout
	*/
	function save_layout($is_layout_special)
	{
		global $db, $template, $class_db, $lang, $user;

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
				$data['layout_cms_id'] = !empty($this->cms_id) ? $this->cms_id : 0;
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
				if(!empty($data['locked']))
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
			else
			{
				if(!empty($data['locked']))
				{
					message_die(GENERAL_ERROR, $lang['Not_Authorized']);
				}

				$class_db->insert_item($data);
			}

			$message .= $lang['Layout_added'];
		}

		$message .= '<br /><br />' . sprintf($lang['Click_return_layoutadmin'], '<a href="' . append_sid($this->root . '?mode=' . $this->mode_layout_name) . '">', '</a>');
		$message .= '<br /><br />' . sprintf($lang['Click_return_blocksadmin'], '<a href="' . append_sid($this->root . '?mode=blocks&amp;' . $this->id_var_name . '=' . (!empty($layout_id) ? $layout_id : $this->id_var_value)) . '">', '</a>');
		$message .= '<br /><br />';

		message_die(GENERAL_MESSAGE, $message);

		return true;
	}

	/*
	* Delete layout
	*/
	function delete_layout()
	{
		global $db, $lang, $template;

		if($is_layout_special)
		{
			$l_info = $this->get_layout_info();
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

		return true;
	}

	/*
	* Update layout
	*/
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
		redirect(append_sid($this->root . '?mode=' . $this->mode_layout_name . $action_append . '&amp;changes_saved=true'));

		return true;
	}

	/*
	* Clone layout
	*/
	function clone_layout()
	{
		global $lang, $db;
		// get layout
		$sql = "SELECT * FROM " . $this->tables['layout_table'] . " WHERE lid = " . $this->id_var_value;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if($row === false)
		{
			message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
		}
		// copy it
		unset($row['lid']);
		$row['filename'] = '';
		$sql = "INSERT INTO " . $this->tables['layout_table'] . " " . $db->sql_build_insert_update($row, true);
		$result = $db->sql_query($sql);
		$id = $db->sql_nextid();
		if(!$id)
		{
			message_die(GENERAL_MESSAGE, $lang['No_layout_selected']);
		}
		// get all blocks
		$sql = "SELECT * FROM " . $this->tables['blocks_table'] . " WHERE layout_special = 0 AND layout = " . $this->id_var_value;
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		for($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];
			$row['layout'] = $id;
			unset($row['bid']);
			$sql = "INSERT INTO " . $this->tables['blocks_table'] . " " . $db->sql_build_insert_update($row, true);
			$result = $db->sql_query($sql);
		}
		// copy positions
		$sql = "SELECT * FROM " . $this->tables['block_position_table'] . " WHERE layout = " . $this->id_var_value;
		$result = $db->sql_query($sql);
		$rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		for($i = 0; $i < count($rows); $i++)
		{
			$row = $rows[$i];
			$row['layout'] = $id;
			unset($row['bpid']);
			$sql = "INSERT INTO " . $this->tables['block_position_table'] . " " . $db->sql_build_insert_update($row, true);
			$result = $db->sql_query($sql);
		}
		redirect(append_sid($this->root . '?mode=layouts&amp;l_id=' . $id . '&amp;action=edit'));
	}

	/*
	* Show layouts list
	*/
	function show_layouts_list($is_layout_special)
	{
		global $db, $class_form, $template, $theme, $lang;

		$template->assign_block_vars('layout', array());

		$l_rows = $this->get_layouts_list();
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

		$row_class = '';
		for($i = 0; $i < $l_count; $i++)
		{
			$row_class = ip_zebra_rows($row_class);
			$layout_id = $l_rows[$i][$this->field_name];
			$layout_name = ($is_layout_special ? (isset($lang['auth_view_' . $l_rows[$i]['name']]) ? $lang['auth_view_' . $l_rows[$i]['name']] : (isset($lang['cms_page_name_' . strtolower($l_rows[$i]['name'])]) ? $lang['cms_page_name_' . strtolower($l_rows[$i]['name'])] : ucfirst($l_rows[$i]['name']))) : ucfirst($l_rows[$i]['name']));
			//$layout_name = htmlspecialchars($layout_name);
			$layout_filename = $l_rows[$i]['filename'];
			$layout_preview = ($is_layout_special ? (empty($layout_filename) ? '#' : append_sid($layout_filename)) : (empty($layout_filename) ? (CMS_PAGE_HOME . '?page=' . $layout_id) : append_sid($layout_filename)));
			$layout_locked = false;

			$select_name = 'auth_view_' . $layout_id;
			$default = $l_rows[$i]['view'];
			$select_js = '';

			if ($is_layout_special)
			{
				$layout_locked = !empty($l_rows[$i]['locked']) ? true : false;
				$options_array = array(AUTH_ALL, AUTH_REG, AUTH_MOD, AUTH_ADMIN);
				$options_langs_array = array($lang['B_ALL'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN']);
			}
			else
			{
				$options_array = array(0, 1, 2, 3, 4);
				$options_langs_array = array($lang['B_ALL'], $lang['B_GUESTS'], $lang['B_REG'], $lang['B_MOD'], $lang['B_ADMIN']);
			}

			$auth_view_select_box = $class_form->build_select_box($select_name, $default, $options_array, $options_langs_array, $select_js);

			$template->assign_block_vars('layout.l_row', array(
				'ROW_CLASS' => $row_class,
				'ROW_DEFAULT_STYLE' => ($layout_id == $default_portal_id) ? 'font-weight: bold;' : '',
				'LAYOUT_ID' => $layout_id,
				'LAYOUT_NAME' => $layout_name,
				'LAYOUT_FILENAME' => (empty($layout_filename) ? $lang['None'] : htmlspecialchars($layout_filename)),
				'LAYOUT_BLOCKS' => $this->count_blocks_in_layout('\'' . $layout_id . '\'', $is_layout_special, true) . '/' . $this->count_blocks_in_layout('\'' . $layout_id . '\'', $is_layout_special, false),
				'LAYOUT_TEMPLATE' => $l_rows[$i]['template'],

				'LOCKED' => $layout_locked,
				'PAGE_AUTH' => $auth_view_select_box,
				'GB_CHECKED' => ($l_rows[$i]['global_blocks']) ? ' checked="checked"' : '',
				'BC_CHECKED' => ($l_rows[$i]['page_nav']) ? ' checked="checked"' : '',

				'U_PREVIEW_LAYOUT' => $layout_preview,
				'U_EDIT_LAYOUT' => append_sid($this->root . '?mode=' . $this->mode . '&amp;' . $this->id_var_name . '=' . $layout_id . '&amp;action=edit'),
				'U_EDIT_AUTH' => append_sid($this->root . '?mode=auth&amp;pmode=setting_cms_user_local&amp;id_type=' . ($is_layout_special ? 'layout_special' : 'layout') . '&amp;forum_id[]=' . $layout_id),
				'U_DELETE_LAYOUT' => append_sid($this->root . '?mode=' . $this->mode . '&amp;' . $this->id_var_name . '=' . $layout_id . '&amp;action=delete'),
				'U_LAYOUT' => append_sid($this->root . '?mode=' . $this->mode_blocks_name . '&amp;' . $this->id_var_name . '=' . $layout_id),
				'U_COPY' => $is_layout_special ? '' : append_sid($this->root . '?mode=' . $this->mode . '&amp;' . $this->id_var_name . '=' . $layout_id . '&amp;action=clone'),
				)
			);
		}

		return true;
	}

	/**
	* Check if a user is auth to edit the selected layout
	*/
	function get_layout_edit_auth()
	{
		global $db, $user;

		// If the user is admin... give immediate access and exit!
		if ($user->data['user_level'] == ADMIN)
		{
			return true;
		}

		$cms_auth_level_req = ($this->field_name == 'lid') ? 'cms_layouts' : 'cms_layouts_special';
		$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get($cms_auth_level_req) || $auth->acl_get('cmsb_admin', $this->id_var_value)) ? true : false;

		return $is_auth;
	}

	/**
	* Gets all layouts
	*/
	function get_layouts_list()
	{
		global $db, $user;

		$sql_where = '';

		if (defined('IN_CMS_USERS'))
		{
			$cms_id = !empty($this->cms_id) ? $this->cms_id : 0;
			$sql_where = " WHERE layout_cms_id = '" . $cms_id . "'";
		}
		$sql = "SELECT * FROM " . $this->table_name . $sql_where . " ORDER BY " . $this->field_name;
		$result = $db->sql_query($sql);
		$l_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $l_rows;
	}

	/*
	* Get layout details
	*/
	function get_layouts_details($l_info, $layout_dir, $layout_extension, $layout_field = 'template')
	{
		$layout_details = array();
		$num_layout = 0;
		$layouts = @opendir($layout_dir);
		while ($file = @readdir($layouts))
		{
			$pos = strpos($file, $layout_extension);
			if (($pos !== false) && ($file != 'index.html'))
			{
				$img = 'layout_' . str_replace($layout_extension, '', $file) . '.png';
				$img = (file_exists(CMS_TPL_ABS_PATH . 'images/' . $img)) ? (CMS_TPL_ABS_PATH . 'images/' . $img) : (CMS_TPL_ABS_PATH . 'images/layout_unknown.png');

				$layout_details[$num_layout]['img'] = '<img src="' . $img . '" alt="' . $file . '" title="' . $file . '"/>';
				$layout_details[$num_layout]['file'] = '<input type="radio" name="' . $layout_field . '" value="' . $file . '"';
				if(!empty($l_info) && $l_info['template'] == $file)
				{
					$layout_details[$num_layout]['file'] .= ' checked="checked"';
				}
				$layout_details[$num_layout]['file'] .= '/>';
				$num_layout++;
			}
		}
		@closedir($layout_dir);
		return $layout_details;
	}

	/*
	* Get layout details select
	*/
	function get_layouts_details_select($layout_dir, $layout_extension)
	{
		global $l_info;

		$layouts_array = array();
		$layouts = @opendir($layout_dir);
		while ($file = @readdir($layouts))
		{
			$pos = strpos($file, $layout_extension);
			if (($pos !== false) && ($file != 'index.html'))
			{
				$layouts_array[] = $file;
			}
		}
		@closedir($layout_dir);

		$layout_details = '';
		foreach ($layouts_array as $k => $v)
		{
			$layout_details .= '<option value="' . $v .'" ' . ((!empty($l_info) && ($l_info['template'] == $v)) ? 'selected="selected"' : '') . '>' . $v . '</option>';
		}

		return $layout_details;
	}

	/*
	* Get layout info
	*/
	function get_layout_info()
	{
		global $db;

		$sql = "SELECT * FROM " . $this->table_name . " WHERE " . $this->field_name . " = '" . $this->id_var_value . "'";
		$result = $db->sql_query($sql);
		$l_info = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $l_info;
	}

	/*
	* Count blocks in layout
	*/
	function count_blocks_in_layout($l_id_list, $is_special = false, $only_active = true)
	{
		global $db;

		$only_active_sql = "";
		if ($only_active == true)
		{
			$only_active_sql = " AND active = '1'";
		}

		$layout_field = 'layout';
		if ($is_special == true)
		{
			$layout_field = 'layout_special';
		}

		$sql = "SELECT count(bid) blocks_counter FROM " . $this->tables['blocks_table'] . " WHERE " . $layout_field . " IN (" . $l_id_list . ")" . $only_active_sql;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $row['blocks_counter'];
	}

	/**
	* Gets all blocks installed
	*/
	function get_blocks_installed()
	{
		global $db;

		$bs_array = array();
		$sql = "SELECT bs_id, name FROM " . CMS_BLOCK_SETTINGS_TABLE . " ORDER BY name ASC";
		$result = $db->sql_query($sql);
		$bs_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		foreach($bs_rows as $key => $data)
		{
			$bs_array['ID'][$key] = $data['bs_id'];
			$bs_array['TITLE'][$key] = $data['name'];
		}
		return $bs_array;
	}

	/*
	* Get CMS id
	*/
	function get_cms_id()
	{
		global $db, $user;

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
				$sql = "SELECT cu_id  var_id FROM " . CMS_USERS_TABLE . " WHERE cu_user_id = '" . $user->data['user_id'] . "'";
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
		global $db, $user, $cms_auth;

		$sql = "SELECT cu_id FROM " . CMS_USERS_TABLE . " WHERE cu_user_id = '" . $user->data['user_id'] . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$user_cms_id =  !empty($row['cu_id']) ? $row['cu_id'] : false;

		return $cms_auth->acl_get('cms_view', $this->cms_id) ? true : $user_cms_id;
	}

	/**
	* Check if a user is auth to edit the selected block
	*/
	function get_block_edit_auth()
	{
		global $db, $user, $auth;

		// If the user is admin... give immediate access and exit!
		if ($user->data['user_level'] == ADMIN)
		{
			return true;
		}

		$is_auth = ($auth->acl_get('cms_admin') || $auth->acl_get('cms_blocks') || $auth->acl_get('cmsb_admin', $this->id_var_value)) ? true : false;

		return $is_auth;
	}

	/*
	* Get blocks files list
	*/
	function get_blocks_files_list()
	{
		global $cache, $config;

		$blocks_array = array();
		$blocks = @opendir(BLOCKS_DIR);
		while ($file = @readdir($blocks))
		{
			$ext = substr(strrchr($file, '.'), 1);
			if ((substr($file, 0, strlen(BLOCKS_PREFIX)) == BLOCKS_PREFIX) && ($ext == PHP_EXT))
			{
				$blocks_array[] = substr(substr($file, strlen(BLOCKS_PREFIX)), 0, (strlen($ext) * -1) - 1);
			}
		}
		@closedir($blocks);

		foreach ($config['plugins'] as $k => $plugin)
		{
			if (!$plugin['enabled'])
			{
				continue;
			}
			$plugin_blocks_dir = IP_ROOT_PATH . PLUGINS_PATH . $plugin['dir'] . BLOCKS_DIR_NAME;
			if (is_dir($plugin_blocks_dir))
			{
				$blocks = @opendir($plugin_blocks_dir);
				while ($file = @readdir($blocks))
				{
					$ext = substr(strrchr($file, '.'), 1);
					if ((substr($file, 0, strlen(BLOCKS_PREFIX)) == BLOCKS_PREFIX) && ($ext == PHP_EXT))
					{
						$blocks_array[] = $k . '/' . substr(substr($file, strlen(BLOCKS_PREFIX)), 0, (strlen($ext) * -1) - 1);
					}
				}
				@closedir($blocks);
			}
		}
		sort($blocks_array);

		return $blocks_array;
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

		$bs_rows = $this->get_parent_blocks();
		foreach($bs_rows as $key => $data)
		{
			$bs_array['ID'][$key] = $data['bs_id'];
			$bs_array['TITLE'][$key] = $data['name'];
		}

		return $bs_array;
	}

	/*
	* Get 'View by' lang var
	*/
	function get_block_view_name($value)
	{
		global $lang;

		switch ($value)
		{
			case '0':
				$b_view = $lang['B_ALL'];
				break;
			case '1':
				$b_view = $lang['B_GUESTS'];
				break;
			case '2':
				$b_view = $lang['B_REG'];
				break;
			case '3':
				$b_view = $lang['B_MOD'];
				break;
			case '4':
				$b_view = $lang['B_ADMIN'];
				break;
			case '8':
				$b_view = $lang['B_ALL_NO_BOTS'];
				break;
			default:
				$b_view = $lang['B_ALL'];
				break;
		}
		return $b_view;
	}

	/*
	* Get parent blocks
	*/
	function get_parent_blocks()
	{
		global $db, $user;

		$sql = "SELECT * FROM " . $this->tables['block_settings_table'] . " WHERE user_id = " . $user->data['user_id'] . " OR locked = '1' ORDER BY locked DESC, name ASC";
		$result = $db->sql_query($sql);
		$b_rows = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);

		return $b_rows;
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
	* Get block vars default
	*/
	function get_block_vars_default($block_file)
	{
		global $config;

		$block_vars_default = array();
		if (false !== strpos($block_file, '/'))
		{
			list($plugin_name, $block_file) = explode('/', $block_file);
			$plugin_config = $config['plugins'][$plugin_name];
			$block_cfg_file = IP_ROOT_PATH . PLUGINS_PATH . $plugin_config['dir'] . BLOCKS_DIR_NAME . $block_file . '.cfg';
		}
		else
		{
			$block_cfg_file = BLOCKS_DIR . $block_file . '.cfg';
		}

		if(!empty($block_file) && file_exists($block_cfg_file))
		{
			$block_count_variables = 0;
			include($block_cfg_file);
			if ($block_count_variables > 0)
			{
				for($i = 0; $i < $block_count_variables; $i++)
				{
					$block_vars_default[] = array(
						'label' => $block_variables[$i][0],
						'sub_label' => $block_variables[$i][1],
						'config_name' => $block_variables[$i][2],
						'field_options' => $block_variables[$i][3],
						'field_values' => $block_variables[$i][4],
						'type' => $block_variables[$i][5],
						'block' => $block_variables[$i][6],
						'config_value' => $block_variables[$i][7],
					);
				}
			}
		}

		return $block_vars_default;
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
	* Get global blocks layout
	*/
	function get_global_blocks_layout()
	{
		global $db;

		$sql = "SELECT global_blocks FROM " . $this->table_name . " WHERE " . $this->field_name . " = '" . $this->id_var_value . "'";
		$result = $db->sql_query($sql);
		$l_row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $l_row;
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
	function block_var_exists($block_variable_name)
	{
		global $db;

		$sql = "SELECT count(1) existing FROM " . $this->tables['block_variable_table'] . "
			WHERE config_name = '" . $block_variable_name . "'
				AND bid = '" . $this->bs_id . "'";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$block_var_exists = $row['existing'];

		return $block_var_exists;
	}

	/*
	* Get blocks positions
	*/
	function get_blocks_positions_layout($l_id_list)
	{
		global $db;

		$sql = "SELECT bposition, pkey FROM " . $this->tables['block_position_table'] . " WHERE layout IN (" . $l_id_list . ")";
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
		global $db, $user;

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
			// InformPro: to do... totally rewrite this, first using ONE SELECT WHERE (layout = and bpos = ) or (layout =..) THEN transaction
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

		return true;
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

	/*
	* Get max block id
	*/
	function get_max_block_id()
	{
		global $db;

		$sql = "SELECT max(bid) mbid FROM " . $this->tables['blocks_table'];
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		$b_id = $row['mbid'];

		return $b_id;
	}

}

?>