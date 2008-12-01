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
		global $pafiledb_template, $lang, $pafiledb_config, $userdata, $board_config;

		// =======================================================
		// Get the id
		// =======================================================

		if ( isset($_REQUEST['cat_id']))
		{
			$cat_id = intval($_REQUEST['cat_id']);
		}
		elseif ($file_id == 0 && $action != '')
		{
			$cat_id_array = array();
			$cat_id_array = explode('=', $action);
			$cat_id = $cat_id_array[1];
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Cat_not_exist']);
		}

		$start = ( isset($_REQUEST['start']) ) ? intval($_REQUEST['start']) : 0;
		$start = ($start < 0) ? 0 : $start;

		if(isset($_REQUEST['sort_method']))
		{
			switch ($_REQUEST['sort_method'])
			{
				case 'file_name':
					$sort_method = 'file_name';
					break;
				case 'file_time':
					$sort_method = 'file_time';
					break;
				case 'file_dls':
					$sort_method = 'file_dls';
					break;
				case 'file_rating':
					$sort_method = 'rating';
					break;
				case 'file_update_time':
					$sort_method = 'file_update_time';
					break;
				default:
					$sort_method = $pafiledb_config['sort_method'];
			}
		}
		else
		{
			$sort_method = $pafiledb_config['sort_method'];
		}

		if( isset($_REQUEST['sort_order']) )
		{
			switch ($_REQUEST['sort_order'])
			{
				case 'ASC':
					$sort_order = 'ASC';
					break;
				case 'DESC':
					$sort_order = 'DESC';
					break;
				default:
					$sort_order = $pafiledb_config['sort_order'];
			}
		}
		else
		{
			$sort_order = $pafiledb_config['sort_order'];
		}

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
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid(LOGIN_MG . '?redirect=dload.' . PHP_EXT . '&action=category&cat_id=' . $cat_id, true));
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

		$pafiledb_template->assign_vars(array(
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),


			'U_INDEX' => append_sid(PORTAL_MG),
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