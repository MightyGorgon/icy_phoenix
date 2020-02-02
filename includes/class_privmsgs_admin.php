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

class aprvmUtils
{
	var $modVersion;
	var $modName;
	var $copyrightYear;
	var $archiveText;
	var $inArchiveText;
	var $urlPage;
	var $urlStart;

	/**
	* Construct
	*/
	function __construct()
	{
		$this->archiveText = '_archive';
	}

	function init()
	{
		global $lang, $mode, $config;

		$this->modName = ($config['aprvmArchive'] && ($mode == 'archive')) ? $lang['Private_Messages_Archive'] : $lang['Private_Messages'];
		$this->setupConfig();
		$this->makeURLStart();
		$this->inArchiveText = ($mode == 'archive') ? '_archive' : '';
	}

	function makeURLStart()
	{
		global $filter_from, $filter_to, $order;
		global $mode, $pmtype, $sort, $pmtype_text, $start;

		//$this->urlBase = basename(__FILE__) . "?order=$order&amp;sort=$sort&amp;pmtype=$pmtype&filter_from=$filter_from&filter_to=$filter_to";
		$this->urlBase = 'admin_priv_msgs.' . PHP_EXT . '?order=' . $order . '&amp;sort=' . $sort . '&amp;pmtype=' . $pmtype . '&amp;filter_from=' . $filter_from . '&amp;filter_to=' . $filter_to;
		$this->urlPage = $this->urlBase . "&mode=$mode";
		$this->urlStart = $this->urlPage . '&start='.$start;
	}


	function setupConfig()
	{
		global $config, $db, $status_message, $lang;

		$configList = array('aprvmArchive', 'aprvmVersion', 'aprvmView', 'aprvmRows', 'aprvmIP');
		$configLangs = array(
			'aprvmArchive' => $lang['Archive_Feature'],
			'aprvmVersion' => $lang['Version'],
			'aprvmView' => $lang['PM_View_Type'],
			'aprvmRows' => $lang['Rows_Per_Page'],
			'aprvmIP' => $lang['Show_IP']
		);
		$configDefaults = array('0', $this->modVersion, '0', '25', '1');
													//off, version, inline, 25, yes
		//Check for an update config command
		//Also do an array check to make sure our config is in our config list array to update
		$config_name = request_get_var('config_name', '');
		$config_value = request_get_var('config_value', '');
		if (!empty($config_name) && in_array($config_name, $configList))
		{
			set_config($config_name, $config_value);
			$status_message .= sprintf($lang['Updated_Config'], $configLangs[$config_name]);
		}

		//Loop through and see if a config name is set, if not set up a default
		foreach($configList as $num => $val)
		{
			if (!isset($config[$val]))
			{
				set_config($val, $configDefaults[$num]);
				$status_message .= sprintf($lang['Inserted_Default_Value'], $configLangs[$val]);
			}
		}

		//If archive is enabled, check to see if the archive table exists
		if ($config['aprvmArchive'])
		{
			$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . $this->archiveText;
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				//Cheap way for checking if the archive table exists
				$errorMessage = $db->sql_error();
				if (strpos($errorMessage['message'], 'exist') !== false)
				{
					$this->doArchiveTable();
				}
			}
		}

		//Check to see if board_config has the right version we are running
		if ($config['aprvmVersion'] != $this->modVersion)
		{
			$sql = 'UPDATE '. CONFIG_TABLE . "
					set config_value = '{$this->modVersion}'
					WHERE config_name = 'aprvmVersion'";
			$db->sql_query($sql);
			$config['aprvmVersion'] = $this->modVersion;
			$status_message .= sprintf($lang['Updated_Config'], $configLangs['aprvmVersion']);
		}
	}

	function resync($type, $user_id, $num = 1)
	{
		global $db;

		if (($type == PRIVMSGS_NEW_MAIL || $type == PRIVMSGS_UNREAD_MAIL))
		{
			// Update appropriate counter
			switch ($type)
			{
				case PRIVMSGS_NEW_MAIL:
				$sql = "user_new_privmsg = user_new_privmsg - $num";
				break;
				case PRIVMSGS_UNREAD_MAIL:
				$sql = "user_unread_privmsg = user_unread_privmsg - $num";
				break;
			}

			$sql = "UPDATE " . USERS_TABLE . "
				SET $sql
				WHERE user_id = $user_id";
			$db->sql_query($sql);
		}
	}

	function make_drop_box($prefix = 'sort')
	{
		global $sort_types, $order_types, $pmtypes, $lang, $sort, $order, $pmtype;

		$rval = '<select name="' . $prefix . '">';

		switch($prefix)
		{
			case 'sort':
			foreach($sort_types as $val)
			{
				$selected = ($sort == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
			case 'order':
			foreach($order_types as $val)
			{
				$selected = ($order == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang[$val] . '</option>';
			}
			break;
			case 'pmtype':
			foreach($pmtypes as $val)
			{
				$selected = ($pmtype == $val) ? 'selected="selected"' : '';
				$rval .= "<option value=\"$val\" $selected>" . $lang['PM_' . $val] . '</option>';
			}
			break;
		}
		$rval .= '</select>';

		return $rval;
	}

	function id_2_name($id, $mode = 'user')
	{
		global $db;

		static $nameCache;
		//Stores names we've already sent a query for
		//Has array sections ['user'] and ['reverse']
		//['user']['user_id'] => ['username']
		//['reverse']['username'] => ['user_id']

		if ($id == '')
		{
			return '?';
		}

		switch($mode)
		{
			case 'user_formatted':
			{
				if (isset($nameCache['user_formatted'][$id]))
				{
					return $nameCache['user_formatted'][$id];
				}

				$sql = "SELECT user_id, username, username_clean, user_active, user_color FROM " . USERS_TABLE . " WHERE user_id = '" . $id . "'";
				break;
			}
			case 'user':
			{
				if (isset($nameCache['user'][$id]))
				{
					return $nameCache['user'][$id];
				}

				$sql = "SELECT user_id, username, username_clean, user_active, user_color FROM " . USERS_TABLE . " WHERE user_id = " . $id;
				break;
			}
			case 'reverse':
			{
				if (isset($nameCache['reverse'][$id]))
				{
					return $nameCache['reverse'][$id];
				}
				$sql = get_users_sql($id, false, false, true, false);
				break;
			}
		}

		if (!empty($sql))
		{
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			if (empty($row['user_id']))
			{
				return false;
			}
			else
			{
				// Compile cache
				$formatted_username = colorize_username($row['user_id'], $row['username'], $row['user_color'], $row['user_active']);
				$nameCache['user'][$row['user_id']] = $row['username'];
				$nameCache['user_formatted'][$row['user_id']] = $formatted_username;
				$nameCache['reverse'][$row['username']] = $row['user_id'];

				switch($mode)
				{
					case 'user_formatted':
					{
						return $formatted_username;
						break;
					}
					case 'user':
					{
						return $row['username'];
						break;
					}
					case 'reverse':
					{
						return $row['user_id'];
						break;
					}
				}
			}
		}

		return false;
	}

	function do_pagination($mode = 'normal')
	{
		global $db, $template, $lang, $filter_from_text, $filter_to_text, $filter_from, $filter_to, $order;
		global $mode, $pmtype, $sort, $pmtype_text, $archive_text, $start, $archive_start, $topics_per_pg;

		$sql = 'SELECT count(*) AS total FROM ' . PRIVMSGS_TABLE . $this->inArchiveText . " pm
			WHERE 1
			$pmtype_text
			$filter_from_text
			$filter_to_text";
		$result = $db->sql_query($sql);
		$total = $db->sql_fetchrow($result);
		$total_pms = ($total['total'] > 0) ? $total['total'] : 1;

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination($this->urlPage, $total_pms, $topics_per_pg, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], (floor($start / $topics_per_pg) + 1), ceil($total_pms / $topics_per_pg)),
			'L_GOTO_PAGE' => $lang['Goto_page']
			)
		);
	}

	/**
	* @return void
	* @desc Prints a sytlized line of copyright for module
	*/
	function copyright()
	{
		printf('<br /><div class="copyright" style="text-align: center;"> %s &copy; %s <a href="http://www.nivisec.com" class="copyright">Nivisec.com</a>.', $this->modName, $this->copyrightYear);
		printf('<br />PHP Version %s</div>', phpversion());
	}

	function doArchiveTable()
	{
		global $db, $lang, $table_prefix, $status_message;

		switch (SQL_LAYER)
		{
			case 'mysql':
			case 'mysql4':
			{
				$create[] = "CREATE TABLE `" . $table_prefix . "privmsgs_archive` (
					`privmsgs_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT ,
					`privmsgs_type` tinyint(4) NOT NULL default '0',
					`privmsgs_subject` varchar(255) NOT NULL default '0',
					`privmsgs_text` text,
					`privmsgs_from_userid` mediumint(8) NOT NULL default '0',
					`privmsgs_to_userid` mediumint(8) NOT NULL default '0',
					`privmsgs_date` int(11) NOT NULL default '0',
					`privmsgs_ip` varchar(8) NOT NULL default '',
					`privmsgs_enable_bbcode` tinyint(1) NOT NULL default '1',
					`privmsgs_enable_html` tinyint(1) NOT NULL default '0',
					`privmsgs_enable_smilies` tinyint(1) NOT NULL default '1',
					`privmsgs_enable_autolinks_acronyms` tinyint(1) NOT NULL default '1',
					`privmsgs_attach_sig` tinyint(1) NOT NULL default '1',
					`privmsgs_attachment` tinyint(1) NOT NULL default '0',
					PRIMARY KEY (`privmsgs_id`) ,
					KEY `privmsgs_from_userid` (`privmsgs_from_userid`) ,
					KEY `privmsgs_to_userid` (`privmsgs_to_userid`)
					)";
				break;
			}
		}

		foreach($create as $sql)
		{
			$result = $db->sql_query($sql);
		}
		$status_message .= $lang['Archive_Table_Inserted'];
	}
}

class aprvmManager
{
	var $deleteQueue;
	var $archiveQueue;
	var $syncNums;

	function __construct()
	{
	}

	function addArchiveItem($post_id)
	{
		$this->archiveQueue[] = $post_id;
	}

	function addDeleteItem($post_id)
	{
		$this->deleteQueue[] = $post_id;
	}

	function doArchive()
	{
		global $lang, $db, $status_message, $aprvmUtil;

		if (!sizeof($this->archiveQueue)) return;

		$postList = '';
		foreach($this->archiveQueue as $post_id)
		{
			$postList .= ($postList != '') ? ', '.$post_id : $post_id;
		}

		$sql = 'SELECT * FROM ' . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($postList)";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$sql = 'INSERT INTO ' . PRIVMSGS_TABLE . $aprvmUtil->archiveText . ' (privmsgs_id, privmsgs_type, privmsgs_subject, privmsgs_text, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_bbcode, privmsgs_enable_html, privmsgs_enable_smilies, privmsgs_enable_autolinks_acronyms, privmsgs_attach_sig, privmsgs_attachment)
			VALUES
				(' . $row['privmsgs_id'] . ', ' . $row['privmsgs_type'] . ", '" . $db->sql_escape($row['privmsgs_subject']) . "', '" . $db->sql_escape($row['privmsgs_text']) . "', " .
				$row['privmsgs_from_userid'] . ', ' . $row['privmsgs_to_userid'] . ', ' . $row['privmsgs_date'] . ", '" .
				$row['privmsgs_ip'] . "', " . $row['privmsgs_enable_bbcode'] . ', ' . $row['privmsgs_enable_html'] . ', ' .
				$row['privmsgs_enable_smilies'] . ', ' . $row['privmsgs_enable_autolinks_acronyms'] . ', ' . $row['privmsgs_attach_sig'] . ', ' . $row['privmsgs_attachment'] . ')';
			$db->sql_query($sql);
			$status_message .= sprintf($lang['Archived_Message'], $row['privmsgs_subject']);
			$this->syncNums[$row['privmsgs_to_userid']][$row['privmsgs_type']]++;
		}
		$sql = 'DELETE FROM ' . PRIVMSGS_TABLE . "
				WHERE privmsgs_id IN ($postList)";
		$db->sql_query($sql);
	}

	function doDelete()
	{
		global $config, $db, $lang, $status_message, $aprvmUtil, $mode;

		if (!sizeof($this->deleteQueue)) return;

		$postList = '';
		foreach($this->deleteQueue as $post_id)
		{
			if ($config['aprvmArchive'] && isset($_POST['archive_id_' . $post_id]))
			{
				/* This query isn't really needed, but makes the hey we deleted this title isntead of id show up */
				$sql = 'SELECT privmsgs_subject FROM ' . PRIVMSGS_TABLE . $aprvmUtil->archiveText . "
							WHERE privmsgs_id = $post_id";
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$status_message .= sprintf($lang['Archived_Message_No_Delete'], $row['privmsgs_subject']);
			}
			else
			{
				$postList .= ($postList != '') ? ', '.$post_id : $post_id;
			}
		}

			$sql = 'SELECT privmsgs_subject, privmsgs_to_userid, privmsgs_type FROM ' . PRIVMSGS_TABLE . $aprvmUtil->inArchiveText . "
							WHERE privmsgs_id IN ($postList)";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$status_message .= sprintf($lang['Deleted_Message'], $row['privmsgs_subject']);

				if (!$config['aprvmArchive'] || $mode != 'archive')
				{
					$this->syncNums[$row['privmsgs_to_userid']][$row['privmsgs_type']]++;
				}
			}

			$sql = "DELETE FROM " . PRIVMSGS_TABLE . $aprvmUtil->inArchiveText . "
							WHERE privmsgs_id IN ($postList)";
			$db->sql_query($sql);
	}

	function go()
	{
		global $aprvmUtil;

		$this->doArchive();
		$this->doDelete();
		if (sizeof($this->syncNums))
		{
			foreach($this->syncNums as $user_id => $type)
			{
				foreach($type as $pmType => $num)
				{
					$aprvmUtil->resync($pmType, $user_id, $num);
				}
			}
		}
	}
}

?>