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
* This class is responsible for all Logfile operations of the CrackerTracker
* Security System.
*
* <h1>Used File Identification IDs</h1><br />
*
* We use some File Identification Numbers to identify wich logfile should
* be written:<br /><br />
*
* 1:	logfile_attempt_counter.txt
* 2:	logfile_worms.txt <br />
* 3:	logfile_blocklist.txt <br />
* 4:	logfile_malformed_logins.txt <br />
* 5:	logfile_spammer.txt <br />
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 16.07.2006 - 02:02:47
* @copyright (c) 2006 www.cback.de
*
*/

class log_manager
{

	/**
	 * Some vars wich are used to save the Data from the Attacker for the
	 * Logfile, etc.
	 *
	 * @var $ct_type_msg <i>(Integer)</i> Shows the CrackerTracker if its a
	 * Attack warning or a System Information
	 *
	 * @var $ct_timestamp <i>(Integer)</i> Current Timestamp
	 *
	 * @var $ct_request <i>(String)</i> How was the attack?
	 *
	 * @var $ct_referer <i>(String)</i> Where was the Attacker coming from?
	 * (Mostly empty)
	 *
	 * @var $ct_user_agent <i>(String)</i> UserAgent of the Attacker
	 *
	 * @var $ct_remote_addr <i>(String)</i> IP Adress of the Attacker
	 *
	 * @var $ct_remote_host <i>(String)</i> Remote Host of the Attacker
	 *
	 * @var $ct_counter_value <i>(Integer)</i> Counter Value of the
	 * CrackerTracker Attack Counter
	 */
	var $ct_type_msg = 0;
	var $ct_timestamp = 0;
	var $ct_request = '';
	var $ct_referer = '';
	var $ct_user_agent  = '';
	var $ct_remote_addr = '';
	var $ct_remote_host = '';
	var $ct_counter_value = 0;


	/**
	 * <b>Constructor</b><br />
	 * Write User Information to Vars we need these informations later into the
	 * Log File
	 */
	function log_manager()
	{
		$this->ct_type_msg = 0;
		$this->ct_timestamp = time();
		$this->ct_request = $_SERVER['SCRIPT_NAME'] . '?' . $_SERVER['QUERY_STRING'];
		$this->ct_referer = $_SERVER['HTTP_REFERER'];
		$this->ct_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->ct_remote_addr = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : getenv('REMOTE_ADDR'));
		$this->ct_remote_addr = (!empty($this->ct_remote_addr) && ($this->ct_remote_addr != '::1')) ? $this->ct_remote_addr : '127.0.0.1';
		$this->ct_remote_host = (!empty($_SERVER['REMOTE_HOST'])) ? $_SERVER['REMOTE_HOST'] : ((!empty($_ENV['REMOTE_HOST'])) ? $_ENV['REMOTE_HOST'] : getenv('REMOTE_HOST'));
		$this->ct_counter_value = 0;
	}


	/**
	 * This is responsible to create a String for the Logfile
	 *
	 * @return $log_entry - Logfile formatted String
	 */
	function to_string()
	{
		$log_entry = ''; // Logfile String
		define('SPLITTER', '|||'); // File Token

		// Write information into Logfile String
		$log_entry .= $this->ct_type_msg;
		$log_entry .= SPLITTER;
		$log_entry .= $this->ct_timestamp;
		$log_entry .= SPLITTER;
		$log_entry .= str_replace(SPLITTER, '', $this->ct_request);
		$log_entry .= SPLITTER;
		$log_entry .= str_replace(SPLITTER, '', $this->ct_referer);
		$log_entry .= SPLITTER;
		$log_entry .= str_replace(SPLITTER, '', $this->ct_user_agent);
		$log_entry .= SPLITTER;
		$log_entry .= str_replace(SPLITTER, '', $this->ct_remote_addr);
		$log_entry .= SPLITTER;
		$log_entry .= str_replace(SPLITTER, '', $this->ct_remote_host);

		// Return String to write into the Logfile
		return $log_entry;
	}


	/**
	 * This little function translates the file Identification numbers into the
	 * correct path to the selected file.
	 *
	 * @param $file_id File Identification Number
	 * @return $ct_filepath Path to the Logfile
	 */
	function create_ct_path($file_id)
	{
		global $config;

		$ct_filepath = '';
		$logs_path = trim(basename($config['logs_path']));
		$logs_path = !empty($logs_path) ? ($logs_path . '/') : 'logs/';

		switch($file_id)
		{
			case 1: $ct_filepath = IP_ROOT_PATH . $logs_path . 'logfile_attempt_counter.txt';
				break;

			case 2: $ct_filepath = IP_ROOT_PATH . $logs_path . 'logfile_worms.txt';
				break;

			case 3: $ct_filepath = IP_ROOT_PATH . $logs_path . 'logfile_blocklist.txt';
				break;

			case 4: $ct_filepath = IP_ROOT_PATH . $logs_path . 'logfile_malformed_logins.txt';
				break;

			case 5: $ct_filepath = IP_ROOT_PATH . $logs_path . 'logfile_spammer.txt';
				break;

			case 6: $ct_filepath = IP_ROOT_PATH . $logs_path . 'logfile_debug_mode.txt';
				break;
		}

		return $ct_filepath;
	}


	/**
	 * Just delete a File in a way wich works without delete it and
	 * recreate it later
	 *
	 * @param $file_id File Identification Number
	 */
	function delete_logfile($file_id)
	{
		// Set Vars
		$path = $this->create_ct_path($file_id);
		$resetstring = ($file_id != 6) ? '1|||' . time() . "|||null|||null|||null|||null|||null\n" : '';

		// Delete now
		$logentry = @fopen($path, 'a') or $this->ct_file_error();
		@ftruncate($logentry, 0);
		@fwrite($logentry, $resetstring);
		@fclose($logentry);
	}


	/**
	 * This function writes the log entry into a logfile
	 *
	 * @param $file_id File Identification Number
	 * @param $str_log String to write into the Logfile
	 */
	function write_to_log($file_id, $str_log)
	{
		// Set Vars
		$path = $this->create_ct_path($file_id);

		// Write down new log entry
		$logentry = @fopen($path, 'a') or $this->ct_file_error();
		@fwrite($logentry, $str_log . "\n");
		@fclose($logentry);
	}


	/**
	 * This function sets new values into the attack Counter
	 *
	 * @param $value Increment Step
	 */
	function increment_counter($value)
	{
		// Variable Reset
		$path = '';
		$this->ct_counter_value = 0;

		// Create Path to Counter file and load the current Status
		$path = $this->create_ct_path(1);
		$this->ct_counter_value = @file_get_contents($path);

		// Set up new counter value
		$this->ct_counter_value += $value;

		// Write the new value into the Counter File
		$counterfile = @fopen($path, 'a') or $this->ct_file_error();
		@ftruncate($counterfile, 0);
		@fwrite($counterfile, $this->ct_counter_value);
		@fclose($counterfile);
	}


	/**
	 * check_log_size is responsible to check how much entrys are in a Log file
	 *
	 * @param $file_id Identification of the Log File
	 * @return $logsize Count how many entrys are in the Log
	 */
	function check_log_size($file_id)
	{
		$logsize = 0;
		$path = '';

		$path = $this->create_ct_path($file_id);
		if ($file_id != 6)
		{
		$logsize  = count(file($path)) - 1;
		}
		else
		{
			$debug_array = file($path);
			$debug_delimiter = $debug_array[0];
			$logsize  = sizeof($debug_array) - count(array_diff($debug_array, (array) $debug_delimiter));
		}

		return $logsize;
	}


	/**
	 * Stops the script on file operation errors
	 * We use this because we're unsure where we use this file.
	 *
	 * For example a Problem occurs in the logfile of the Exploit protection we
	 * don't have the message_die() function from phpBB available!
	 */
	function ct_file_error()
	{
		// Generate HTML Output
		$htmloutput = '
<html>
	<head><title>CBACK CrackerTracker :: Error</title></head>
	<body>
	<br />
	<div align="center">
		<table style="border:2px solid #000000" border="0" width="600" cellpadding="10" cellspacing="0">
		<tr>
			<td align="left" bgcolor="#000000"><font face="Tahoma, Arial, Helvetica" size="4" color="#FFFFFF"><b>ERROR MESSAGE &raquo; &raquo; &raquo; &raquo;</b></font></td>
		</tr>
		<tr>
			<td bgcolor="#FFF4BF" align="left">
				<font face="Tahoma, Arial, Helvetica" size="2" color="#000000">CBACK CrackerTracker could not perform file operations.<br /><br />Please ensure, that you have setted CHMOD777 to all required files as shown in the install file of this MOD and ensure you really have a PHP Interpreter Version >= 4.3.9 installed!</font>
			</td>
		</tr>
		</table>
		</div>
</body>
</html>';

		// Stop the script
		die($htmloutput);
	}


	/**
	 * This function writes an entry into the Worm Logfile
	 */
	function write_worm()
	{
		/*
		 * Because we don't want to contact the database on exploit attacks we
		 * have to use a fixed logfile size value here for this logfile. The
		 * default value is set to 100 lines. Feel free to change it!
		 */
		define('WORM_LOG_SIZE', 100);


		if ( $this->check_log_size(2) > WORM_LOG_SIZE )
		{
			$this->delete_logfile(2);
			$this->increment_counter(WORM_LOG_SIZE);
		}

		$this->write_to_log(2, $this->to_string());
	}


	/**
	 * This function writes an entry into the IP Blocker Logfile
	 *
	 * @param $logsize Allowed size of the Logfile
	 */
	function write_general_logfile($logsize, $file_id)
	{
		if ( $this->check_log_size($file_id) > $logsize)
		{
			$this->delete_logfile($file_id);
			$this->increment_counter($logsize);
		}

		$this->write_to_log($file_id, $this->to_string());
	}


	/**
	 * This function changes the $ct_request var in our Object
	 * to the Username someone has in your forum. We do this step
	 * because the Logfiles 4 and 5 have to display wich Board user
	 * tried to Login or tried to Spam.
	 *
	 * @param $username (String) - Username
	 */
	function prepare_log($username)
	{
		$this->ct_request = $username;
	}


	/**
	 * This function creates a correct value for the counter
	 * (Because of performance reasons we don't increment the counter each time
	 * an attack occurs, we just write it into the logfile and increment the
	 * counter value when we have to delete a full logfile. But when you want to
	 * display the attack counter inside the footer we need to build our correct
	 * counter value with this function)
	 *
	 * @return $this->ct_counter_value New counter Value
	 */
	function get_counter_value()
	{
		// Variable Reset
		$path = '';
		$this->ct_counter_value = 0;

		// Create Path to Counter file and load the current value
		$path = $this->create_ct_path(1);
		$this->ct_counter_value = @file_get_contents($path);

		// Current entries in the logfiles have to be added
		for($i = 2; $i <= 5; $i++)
		{
			// Ignore the wrong logins
			if ($i == 4) continue;
			$this->ct_counter_value += $this->check_log_size($i);
		}

		$this->ct_counter_value - 4; // System comment will not be counted

		// Return Counter Value
		return $this->ct_counter_value;
	}
}

?>