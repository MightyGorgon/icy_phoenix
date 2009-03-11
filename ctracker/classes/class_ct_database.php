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
* Christian Knerr (cback) - (www.cback.de)
*
*/

/**
* This class is responsible for all Database operations performed by
* CrackerTracker.
*
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 16.07.2006 - 02:03:30
* @copyright (c) 2006 www.cback.de
*/

class ct_database
{

	/**
	 * @var $settings (String Array)   The Array to save the CrackerTracker
	 * 								   Settings from the Database
	 *
	 * @var $blocklist (String Array)  Array wich saves the CrackerTracker
	 * 								   Blocklist Information from the Database
	 *
	 * @var $blocklist_id (Int Array)  Sepearate Array for Blocklist IDs
	 *
	 * @var $blocklist_count (Integer) Field-Counter for the Data in Blocklist
	 * 								   Array
	 *
	 * @var $verbose (Boolean) 		   Enables the ID Array for the Blocklist
	 */
	var $settings        = array();
	var $fieldnames_set  = array();
	var $blocklist       = array();
	var $blocklist_id    = array();
	var $blocklist_count = 0;
	var $verbose         = false;
	var $user_ip_value   = '';

	/**
	 * <b>Constructor</b><br />
	 * Loads all Configuration Data from Database
	 */
	function ct_database()
	{
		global $db, $lang;

		// Set Up UserIP
		$this->user_ip_value = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));

		// Load CrackerTracker configuration from database
		$sql = 'SELECT * FROM ' . CTRACKER_CONFIG;

		if (!($result = $db->sql_query($sql, false, 'ct_config_')))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_loading_config'], '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$this->fieldnames_set[] = $row['ct_config_name'];
			$this->settings[$row['ct_config_name']] = $row['ct_config_value'];
		}
	}

	/**
	 * <b>change_configuration</b><br />
	 * This function is responsible to update a configuration value into the
	 * CrackerTracker Config Table. You can use this function for one or more
	 * values as you like.
	 *
	 * @param $setting (String) - Config Name
	 * @param $value (String)   - New Config Value
	 */
	function change_configuration($setting, $value)
	{
		global $db, $lang;
		// Clean up the input
		$setting = trim($setting);
		$setting = str_replace("\'", "'", $setting);

		$value   = trim($value);
		$value   = str_replace("\'", "'", $value);

		// Generate SQL Query
		$sql = 'UPDATE ' . CTRACKER_CONFIG . ' SET ct_config_value = \'' . $value . '\'' .
				'WHERE ct_config_name = \'' . $setting . '\'';

		// Execute SQL Command in database
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_updating_config'], '', __LINE__, __FILE__, $sql);
		}

		// Clear the cache
		$db->clear_cache('ct_config_');
	}

	/**
	 * <b>load_blocklist</b><br />
	 * If requested this function loads the Blocklist Data from the Database
	 */
	function load_blocklist()
	{
		global $db, $lang;

		// Initializing
		$this->blocklist_count = 0;
		$this->blocklist       = array();

		/*
		 * Verbose  Mode active? This also saves ID Values from Database in
		 * a sepearate array wich we can parse faster than all in one.
		 */
		if ($this->verbose == true)
		{
			$this->blocklist_id = array();
		}

		// Load CrackerTracker blocklist from database
		$sql = 'SELECT * FROM ' . CTRACKER_IPBLOCKER . ' ORDER BY ct_blocker_value ASC';

		if (!($result = $db->sql_query($sql, false, 'ct_ip_blocker_')))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_loading_blocklist'], '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$this->blocklist[] = stripslashes($row['ct_blocker_value']);

			/*
			* Verbose  Mode active? This also saves ID Values from Database in a
			* sepearate array wich we can parse faster than all in one.
			*/
			if ($this->verbose == true)
			{
				$this->blocklist_id[] = $row['id'];
			}
		}

		// How much entrys do we have?
		$this->blocklist_count = count($this->blocklist);
	}

	/**
	 * <b>save_to_blocklist</b><br />
	 * This function writes a new entry into the Blocklist
	 *
	 * @param $blocklist_value (String) Value to write into the List
	 */
	function save_to_blocklist($blocklist_value)
	{
		global $db, $lang;

		// Clean up the input
		$blocklist_value = trim($blocklist_value);
		$blocklist_value = str_replace("\'", "''", $blocklist_value);
		if (empty($blocklist_value) || ($blocklist_value == ''))
		{
			return false;
		}

		$sql = "SELECT MAX(id) AS total FROM " . CTRACKER_IPBLOCKER;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}

		if (!($row = $db->sql_fetchrow($result)))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}
		$newid = $row['total'] + 1;

		// Now build an SQL Query
		$sql = 'INSERT INTO ' . CTRACKER_IPBLOCKER . ' (`id`, `ct_blocker_value`)' .
				'VALUES (' . $newid . ', \'' . $blocklist_value . '\')';

		// And lets write it into the database
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_insert_blocklist'], '', __LINE__, __FILE__, $sql);
		}
	}

	/**
	 * <b>delete_from_blocklist</b><br />
	 * This function deletes a record from the CrackerTracker Blocklist
	 *
	 * @param $blocklist_id (Integer) - ID Field of the entry
	 */
	function delete_from_blocklist($blocklist_id)
	{
		global $db, $lang;

		// Clean up the input
		$blocklist_id = intval($blocklist_id);

		// Build an SQL Query
		$sql = 'DELETE FROM ' . CTRACKER_IPBLOCKER . ' WHERE id = ' . $blocklist_id;

		// And lets execute the command into database
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_delete_blocklist'], '', __LINE__, __FILE__, $sql);
		}
	}

	/**
	 * This function enables the ID saving array for the Blocklist information.
	 * We can run faster to two arrays where we need it than to use a 2D Array.
	 * Has some code-layout improvements in my opinion. I don't like foreach in
	 * 2D Arrays with huge constructs. No need for it. ;)
	 */
	function set_blocklist_verbose()
	{
		$this->verbose = true;
	}

	/**
	 * This function disables the ID saving array for the Blocklist information.
	 */
	function unset_blocklist_verbose()
	{
		$this->verbose = false;
	}

	/**
	 * <b>update_blocklist</b><br />
	 * This updates a record in the CrackerTracker Blocklist
	 *
	 * @param $blocklist_id (Integer)  - ID of the value wich should be replaced
	 * @param $blocklist_val (String)  - New entry for the record
	 */
	function update_blocklist($blocklist_id, $blocklist_val)
	{
		/*
		 * Well we don't code a DB Update routine, we use a mix from
		 * our Delete and Insert routine instead.
		 */
		$this->delete_from_blocklist($blocklist_id);
		$this->save_to_blocklist($blocklist_val);
	}


	/**
	 * <b>update_login_history</b><br />
	 * This function manages the login history table if activated
	 *
	 * @param $user_id (Integer) User ID
	 */
	function update_login_history($user_id)
	{
		global $db, $lang;

		// Initialize
		$login_ip   = '';
		$login_time = 0;
		$temp_time  = 0;

		// Set values
		$login_ip   = $this->user_ip_value;
		$login_time = time();

		// Ensure that $user_id is integer
		$user_id = intval($user_id);

		// Create SQL Command to insert new login
		$sql = 'INSERT INTO ' . CTRACKER_LOGINHISTORY . ' (ct_user_id, ct_login_ip, ct_login_time) VALUES ' . "($user_id, '$login_ip', $login_time)";

		// Execute SQL Command
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_login_history'], '', __LINE__, __FILE__, $sql);
		}

		// Delete old values from the Database
		$this->settings['login_history_count']--;
		$sql = 'SELECT * FROM ' . CTRACKER_LOGINHISTORY . ' WHERE ct_user_id = ' . $user_id . ' ORDER BY ct_login_time DESC LIMIT ' . $this->settings['login_history_count'] . ',1';

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_login_history'], '', __LINE__, __FILE__, $sql);
		}

		$row       = $db->sql_fetchrow($result);
 		$temp_time = !empty($row['ct_login_time'])? $row['ct_login_time'] : 0;

		$sql = 'DELETE FROM ' . CTRACKER_LOGINHISTORY . ' WHERE ct_user_id = ' . $user_id . ' AND ct_login_time < ' . $temp_time;

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_login_history'], '', __LINE__, __FILE__, $sql);
		}
	}

	/**
	 * <b>clean_up_login_history</b><br />
	 * Cleans the complete login_history Table
	 */
	function clean_up_login_history()
	{
		global $lang;

		$sql = 'TRUNCATE ' . CTRACKER_LOGINHISTORY;

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_del_login_history'], '', __LINE__, __FILE__, $sql);
		}
	}

	/**
	 * <b>reset_login_system</b><br />
	 * Resets User Wrong Login Markers on successful login
	 *
	 * @param $user_id (Integer) User ID
	 */
	function reset_login_system($user_id)
	{
		global $db, $lang;

		// Ensure that $user_id is integer
		$user_id = intval($user_id);

		$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_login_vconfirm = 0, ct_login_count = 1 WHERE user_id = ' . $user_id;

		// Execute SQL Command in database
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
		}
	}

	/**
	 * <b>set_user_ip</b><br />
	 * Saves last Logged in IP Adress for the IP Scanner
	 *
	 * @param $user_id (Integer) User ID
	 */
	function set_user_ip($user_id)
	{
		global $db, $lang, $userdata;

		// Ensure that $user_id is integer
		$user_id = intval($user_id);

		$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_last_ip = ct_last_used_ip WHERE user_id = ' . $user_id;

		// Execute SQL Command in database
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
		}

		// Update Userdata Array (wich is already available here!)
		$userdata['ct_last_ip'] = $userdata['ct_last_used_ip'];

		$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_last_used_ip = \'' . $this->user_ip_value . '\' WHERE user_id = ' . $user_id;

		// Execute SQL Command in database
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
		}

		// Update Userdata Array (wich is already available here!)
		$userdata['ct_last_used_ip'] = $this->user_ip_value;
	}

	/**
	 * <b>handle_wrong_login</b><br />
	 * Handles wrong Logins in the Database...
	 *
	 * @param $user_id (Integer) User ID
	 * @param $logincount (Integer) Count how often a user tried to login
	 */
	function handle_wrong_login($user_id, $logincount)
	{
		global $db, $lang, $ctracker_config;

		if ($logincount < $ctracker_config->settings['logincount'])
		{
			$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_login_count = ct_login_count + 1 WHERE user_id = ' . $user_id;

			// Execute SQL Command in database
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_login_vconfirm = 1 WHERE user_id = ' . $user_id;

			// Execute SQL Command in database
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
			}
		} // else
	} // handle_wrong_login

	/**
	 * <b>check_login_status</b><br />
	 * Checks if Visual Confirmation for a login is required
	 *
	 * @param $username (String) Name of the User who tried to login
	 */
	function check_login_status($username)
	{
		global $lang, $db;

		// Secure submitted username
		$username = phpbb_clean_username($username);

		// Build SQL Query
		$sql = "SELECT ct_login_vconfirm, user_id FROM " . USERS_TABLE . " WHERE username = '" . str_replace("\'", "''", $username) . "'";

		// Execute SQL Command in database
		if ($result = $db->sql_query($sql))
		{
			if ($row = $db->sql_fetchrow($result))
			{
				if ($row['ct_login_vconfirm'] == 1)
				{
					redirect(append_sid('ctracker_login.' . PHP_EXT . '?uid=' . $row['user_id'], true));
				}
			}
		}
	} // check_login_status

	/**
	 * <b>first_admin_protection</b>
	 * Checks if submitted user id is the user id of the first admin. If so stop
	 * the script.
	 *
	 * @param $user_id
	 */
	function first_admin_protection($user_id)
	{
		global $lang, $userdata;

		if ($user_id != $userdata['user_id'])
		{
			include_once(IP_ROOT_PATH . 'ctracker/constants.' . PHP_EXT);

			if ($user_id == CT_FIRST_ADMIN_UID)
			{
				message_die(GENERAL_MESSAGE, $lang['ctracker_gmb_1stadmin']);
			}
		}
	}

}

function mg_reset_login_system($user_id)
{
	global $db, $lang;

	// Ensure that $user_id is integer
	$user_id = intval($user_id);

	$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_login_vconfirm = 0, ct_login_count = 1, user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $user_id;

	// Execute SQL Command in database
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
	}

	return true;
}
?>