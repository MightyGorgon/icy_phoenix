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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

class pafiledb_category extends pafiledb_public
{
	function main($action)
	{
		global $template, $lang, $pafiledb_config, $user, $config;

		// =======================================================
		// Get the id
		// =======================================================

		$cat_id = request_var('cat_id', 0);
		$file_id = request_var('file_id', 0);
		$action = request_var('action', '');

		if (!empty($cat_id))
		{
			$cat_id = $cat_id;
		}
		elseif (($file_id == 0) && ($action != ''))
		{
			$cat_id_array = array();
			$cat_id_array = explode('=', $action);
			$cat_id = $cat_id_array[1];
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Cat_not_exist']);
		}

		$start = request_var('start', 0);
		$start = ($start < 0) ? 0 : $start;

		$sort_method = request_var('sort_method', $pafiledb_config['sort_method']);
		$sort_method = check_var_value($sort_method, array('file_name', 'file_time', 'file_dls', 'file_rating', 'file_update_time'));
		$sort_method = ($sort_method == 'file_rating') ? 'rating' : $sort_method;

		$sort_order = request_var('order', $pafiledb_config['sort_order']);
		$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

		// =======================================================
		// If user not allowed to view file listing (read) and there is no sub Category
		// or the user is not allowed to view these category we gave him a nice message.
		// =======================================================

		$show_category = false;
		if (isset($this->subcat_rowset[$cat_id]))
		{
			foreach($this->subcat_rowset[$cat_id] as $sub_cat_id => $sub_cat_row)
			{
				if($this->auth[$sub_cat_id]['auth_view'])
				{
					$show_category = true;
					break;
				}
			}
		}

		if((!$this->auth[$cat_id]['auth_read']) && (!$show_category))
		{
			if (!$user->data['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=category&cat_id=' . $cat_id, true));
			}

			$message = sprintf($lang['Sorry_auth_view'], $this->auth[$cat_id]['auth_read_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		if(!isset($this->cat_rowset[$cat_id]))
		{
			message_die(GENERAL_MESSAGE, $lang['Cat_not_exist']);
		}

		//===================================================
		// assign var for naviagation
		//===================================================
		$this->generate_category_nav($cat_id);

		$template->assign_vars(array(
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),


			'U_INDEX' => append_sid(CMS_PAGE_HOME),
			'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT),

			'DOWNLOAD' => $pafiledb_config['settings_dbname']
			)
		);

		$no_file_message = true;

		$filelist = false;

		if (isset($this->subcat_rowset[$cat_id]))
		{
			$no_file_message = false;

			$this->category_display($cat_id);
		}

		$this->display_files($sort_method, $sort_order, $start, $no_file_message, $cat_id);

		$this->display($lang['Download'], 'pa_category_body.tpl');
	}
}

?>