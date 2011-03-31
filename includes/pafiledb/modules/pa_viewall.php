<?php
/*
* paFileDB 3.0
* ©2001/2002 PHP Arena
* Written by Todd
* todd@phparena.net
* http://www.phparena.net
* Keep all copyright links on the script visible
* Please read the license included with this script for more information.
*/

class pafiledb_viewall extends pafiledb_public
{
	function main($action)
	{
		global $db, $config, $template, $lang, $user;
		global $pafiledb_config, $pafiledb_functions;

		$start = request_var('start', 0);
		$start = ($start < 0) ? 0 : $start;

		$sort_method = request_var('sort_method', $pafiledb_config['sort_method']);
		$sort_method = check_var_value($sort_method, array('file_name', 'file_time', 'file_dls', 'file_rating', 'file_update_time'));
		$sort_method = ($sort_method == 'file_rating') ? 'rating' : $sort_method;

		$sort_order = request_var('order', $pafiledb_config['sort_order']);
		$sort_order = check_var_value($sort_order, array('DESC', 'ASC'));

		if (!$pafiledb_config['settings_viewall'])
		{
			message_die(GENERAL_MESSAGE, $lang['viewall_disabled']);
		}
		elseif(!$this->auth_global['auth_viewall'])
		{
			if (!$user->data['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=viewall', true));
			}

			$message = sprintf($lang['Sorry_auth_viewall'], $this->auth_global['auth_viewall_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		$template->assign_vars(array(
			'L_VIEWALL' => $lang['Viewall'],
			'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
			'L_HOME' => $lang['Home'],
			'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),

			'U_INDEX' => append_sid(CMS_PAGE_HOME),
			'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT),

			'DOWNLOAD' => $pafiledb_config['settings_dbname']
			)
		);

		$this->display_files($sort_method, $sort_order, $start, true);

		$this->display($lang['Download'], 'pa_viewall_body.tpl');
	}
}

?>