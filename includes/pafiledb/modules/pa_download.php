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

class pafiledb_download extends pafiledb_public
{
	function main($action)
	{
		global $db, $config, $template, $theme, $user, $lang;
		global $gen_simple_header, $starttime, $debug;
		global $cms_config_vars, $cms_page;
		global $pafiledb_config, $pafiledb_user, $pafiledb_functions;

		$cat_id = request_var('cat_id', 0);
		$file_id = request_var('file_id', 0);
		$action = request_var('action', '');

		if (!empty($file_id))
		{
			$file_id = $file_id;
		}
		elseif (($file_id == 0) && ($action != ''))
		{
			$file_id_array = array();
			$file_id_array = explode('=', $action);
			$file_id = $file_id_array[1];
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$mirror_id = request_var('mirror_id', 0);

		$sql = 'SELECT *
			FROM ' . PA_FILES_TABLE . " AS f
			WHERE f.file_id = $file_id";
		$result = $db->sql_query($sql);

		//=========================================================================
		// Id doesn't match with any file in the database another nice error message
		//=========================================================================

		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['File_not_exist']);
		}

		$db->sql_freeresult($result);

		//=========================================================================
		// Check if the user is authorized to download the file
		//=========================================================================

		if((!$this->auth[$file_data['file_catid']]['auth_download']))
		{
			if (!$user->data['session_logged_in'])
			{
				redirect(append_sid(CMS_PAGE_LOGIN . '?redirect=dload.' . PHP_EXT . '&action=download&file_id=' . $file_id, true));
			}

			$message = sprintf($lang['Sorry_auth_download'], $this->auth[$file_data['file_catid']]['auth_download_type']);
			message_die(GENERAL_MESSAGE, $message);
		}

		//=========================================================================
		// Check for hot links
		// Borrowed from Smartor Album mod, thanks Smartor
		//=========================================================================


		$url_referer = (!empty($_SERVER['HTTP_REFERER'])) ? (string) $_SERVER['HTTP_REFERER'] : '';

		if(($pafiledb_config['hotlink_prevent']) && (!empty($url_referer)))
		{
			$check_referer = explode('?', $url_referer);
			$check_referer = trim($check_referer[0]);

			$good_referers = array();

			if ($pafiledb_config['hotlink_allowed'] != '')
			{
				$good_referers = explode(',', $pafiledb_config['hotlink_allowed']);
			}

			$good_referers[] = $config['server_name'];

			$errored = true;

			for ($i = 0; $i < sizeof($good_referers); $i++)
			{
				$good_referers[$i] = trim($good_referers[$i]);

				if(!empty($good_referers[$i]) && (strstr($check_referer, $good_referers[$i]) !== false))
				{
					$errored = false;
					break;
				}
			}

			if ($errored)
			{
				message_die(GENERAL_MESSAGE, $lang['Directly_linked']);
			}
		}


		$sql = 'SELECT *
			FROM ' . PA_MIRRORS_TABLE . " AS f
			WHERE f.file_id = $file_id
			ORDER BY mirror_id";
		$result = $db->sql_query($sql);

		$mirrors_data = array();
		while($row = $db->sql_fetchrow($result))
		{
			$mirrors_data[$row['mirror_id']] = $row;
		}

		$db->sql_freeresult($result);

		if(!empty($mirrors_data) && !$mirror_id)
		{

			$this->generate_category_nav($file_data['file_catid']);

			$template->assign_vars(array(
				'L_INDEX' => sprintf($lang['Forum_Index'], $config['sitename']),
				'L_MIRRORS' => $lang['Mirrors'],
				'L_MIRROR_LOCATION' => $lang['Mirror_location'],
				'L_DOWNLOAD' => $lang['Download_file'],
				'L_HOME' => $lang['Home'],
				'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($config['default_dateformat'], time(), $config['board_timezone'])),

				'U_INDEX_HOME' => append_sid(CMS_PAGE_HOME),
				'U_DOWNLOAD_HOME' => append_sid('dload.' . PHP_EXT),

				'FILE_NAME' => $file_data['file_name'],
				'DOWNLOAD' => $pafiledb_config['settings_dbname']
				)
			);

			$template->assign_block_vars('mirror_row', array(
				'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT . '?action=download&amp;file_id=' . $file_id . '&amp;mirror_id=-1'),
				'MIRROR_LOCATION' => $config['sitename']
				)
			);

			foreach($mirrors_data as $mir_id => $mirror_data)
			{
				$template->assign_block_vars('mirror_row', array(
					'U_DOWNLOAD' => append_sid('dload.' . PHP_EXT . '?action=download&amp;file_id=' . $file_id . '&amp;mirror_id=' . $mir_id),
					'MIRROR_LOCATION' => $mirror_data['mirror_location']
					)
				);
			}

			page_header('', true);
			$this->display($lang['Download'], 'pa_mirrors_body.tpl');
			page_footer(true, '', true);
		}
		elseif((!empty($mirrors_data) && ($mirror_id == -1)) || (empty($mirrors_data)))
		{
			$real_filename = $file_data['real_name'];
			//$real_filename = '"' . $file_data['real_name'] . '"';
			$physical_filename = $file_data['unique_name'];
			$upload_dir = (!empty($file_data['upload_dir'])) ? $file_data['upload_dir'] : $pafiledb_config['upload_dir'];
			$file_url = $file_data['file_dlurl'];
		}
		elseif(($mirror_id > 0) && !empty($mirrors_data[$mirror_id]))
		{
			$real_filename = $mirrors_data[$mirror_id]['real_name'];
			//$real_filename = '"' . $mirrors_data[$mirror_id]['real_name'] . '"';
			$physical_filename = $mirrors_data[$mirror_id]['unique_name'];
			$upload_dir = (!empty($mirrors_data[$mirror_id]['upload_dir'])) ? $mirrors_data[$mirror_id]['upload_dir'] : $pafiledb_config['upload_dir'];
			$file_url = $mirrors_data[$mirror_id]['file_dlurl'];
		}
		else
		{
			message_die(GENERAL_MESSAGE, 'Mirror doesn\'t exist');
		}


		//=========================================================================
		// Update download counter and the last downloaded date
		//=========================================================================

		$current_time = time();
		$file_dls = intval($file_data['file_dls']) + 1;
		$sql = 'UPDATE ' . PA_FILES_TABLE . "
			SET file_dls = $file_dls, file_last = $current_time
			WHERE file_id = $file_id";
		$db->sql_query($sql);

		//=========================================================================
		// Update downloader Info for the given file
		//=========================================================================

		$pafiledb_user->update_downloader_info($file_id);

		if (!empty($file_url))
		{
			$file_url = ((!strstr($file_url, '://')) && (strpos($file_url, DOWNLOADS_PATH) === false)) ? 'http://' . $file_url : ((strpos($file_url, DOWNLOADS_PATH) && (!strstr($file_url, '://'))) ? IP_ROOT_PATH . $file_url : $file_url);
			pa_redirect($file_url);
		}
		else
		{

			//=========================================================================
			// now send the file to the user so he can enjoy it :D
			//=========================================================================
			/*
			if($pafiledb_functions->get_extension($physical_filename) == 'pdf')
			{
				$file_url = IP_ROOT_PATH . $upload_dir . $physical_filename;
				pa_redirect($file_url);
			}
			elseif(!send_file_to_browser($real_filename, 'application/force-download', $physical_filename, IP_ROOT_PATH . $upload_dir))
			{
				$file_url = IP_ROOT_PATH . $upload_dir . $physical_filename;
				pa_redirect($file_url);
			}
		*/
			if($pafiledb_functions->get_extension($physical_filename) == 'pdf')
			{
				$mimetype = 'application/pdf';
			}
			else
			{
				$mimetype = 'application/force-download';
			}

			if(!send_file_to_browser($real_filename, $mimetype, $physical_filename, IP_ROOT_PATH . $upload_dir))
			{
				message_die(GENERAL_ERROR, $lang['Error_no_download'] . '<br /><br /><b>404 File Not Found:</b> The File <i>' . $real_filename . '</i> does not exist.');
			}
		}
	}
}

//=========================================================================
// this function Borrowed from Acyd Burn attachment mod, (thanks Acyd for this great mod)
//=========================================================================

function send_file_to_browser($real_filename, $mimetype, $physical_filename, $upload_dir)
{
	global $HTTP_USER_AGENT, $lang, $db, $pafiledb_functions;

	if ($upload_dir == '')
	{
		$filename = $physical_filename;
	}
	else
	{
		$filename = $upload_dir . $physical_filename;
	}

	$gotit = false;


	if (@!file_exists(@$pafiledb_functions->pafiledb_realpath($filename)))
	{
		message_die(GENERAL_ERROR, $lang['Error_no_download'] . '<br /><br /><b>404 File Not Found:</b> The File <i>' . $filename . '</i> does not exist.');
	}
	else
	{
		$gotit = true;
		$size = @filesize($filename);
		if($size > (1048575 * 512))
		{
			return false;
		}
	}

	// Send out the Headers
	@ob_end_clean();
	@ini_set('zlib.output_compression', 'Off');
	header('Pragma: public');
	header('Content-Transfer-Encoding: none');
	header('Content-Type: ' . $mimetype . '; name="' . $real_filename . '"');
	header('Content-Disposition: inline; filename="' . $real_filename . '"');

	// Now send the File Contents to the Browser
	if ($gotit)
	{
		if ($size)
		{
			header("Content-length: $size");
		}

		$result = @readfile($filename);

		if (!$result)
		{
			return true;
		}
	}
	else
	{
		return false;
	}

	@flush();
	exit();
}

function pa_redirect($file_url)
{
	global $db, $cache, $lang;

	// Close our DB connection and do some cleanup...
	garbage_collection();

	// Make sure no &amp;'s are in, this will break the redirect
	$file_url = str_replace('&amp;', '&', $file_url);

	// Make sure no linebreaks are there... to prevent http response splitting for PHP < 4.4.2
	if ((strpos(urldecode($file_url), "\n") !== false) || (strpos(urldecode($file_url), "\r") !== false) || (strpos($file_url, ';') !== false))
	{
		message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url');
	}

	// Redirect via an HTML form for PITA webservers
	if (@preg_match('#Microsoft|WebSTAR|Xitami#', getenv('SERVER_SOFTWARE')))
	{
		header('Refresh: 0; URL=' . $url);
		$encoding_charset = !empty($lang['ENCODING']) ? $lang['ENCODING'] : 'UTF-8';

		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
		echo '<html xmlns="http://www.w3.org/1999/xhtml" dir="' . $lang['DIRECTION'] . '" lang="' . $lang['HEADER_LANG'] . '" xml:lang="' . $lang['HEADER_LANG_XML'] . '">';
		echo '<head>';
		echo '<meta http-equiv="content-type" content="text/html; charset=' . $encoding_charset . '" />';
		echo '<meta http-equiv="refresh" content="0; url=' . str_replace('&', '&amp;', $file_url) . '" />';
		echo '<title>' . $lang['Redirect'] . '</title>';
		echo '</head>';
		echo '<body>';
		echo '<div style="text-align: center;">' . sprintf($lang['Redirect_to'], '<a href="' . str_replace('&', '&amp;', $file_url) . '">', '</a>') . '</div>';
		echo '</body>';
		echo '</html>';

		exit;
	}

	// Behave as per HTTP/1.1 spec for others
	header('Location: ' . $file_url);
	exit;
}

?>