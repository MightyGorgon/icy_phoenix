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
* This class includes some things wich are only available into the ACP.
*
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 20.07.2006 - 21:08:18
* @copyright (c) 2006 www.cback.de
*
*/


// Constant check
if ( !defined('IN_PHPBB') || !defined('CTRACKER_ACP') )
{
	die('Hacking attempt!');
}


class ct_adminfunctions
{
	/**
	 * <b>ct_adminfunctions</b>
	 * Constructor
	 */
	function ct_adminfunctions()
	{
		// Currently nothing to do
	}


	/**
	 * <b>ct_spammer_block</b>
	 * Generates the Block Modes Switchfields
	 *
	 * @param $current (Integer) -> Current Setting
	 * @return $switch (String)
	 */
	function ct_spammer_block($current)
	{
		global $lang;

		$switch = '';
		$ch_sel = array();
		$ch_sel[$current] = ' selected="selected"';

		$switch .= '<option value="0"' . $ch_sel[0] . '>' . $lang['ctracker_blockmode_0'] . '</option>';
		$switch .= '<option value="1"' . $ch_sel[1] . '>' . $lang['ctracker_blockmode_1'] . '</option>';
		$switch .= '<option value="2"' . $ch_sel[2] . '>' . $lang['ctracker_blockmode_2'] . '</option>';

		return $switch;
	}


	/**
	 * <b>ct_keyword_b_block</b>
	 * Generates the Block Modes Switchfields
	 *
	 * @param $current (Integer) -> Current Setting
	 * @return $switch (String)
	 */
	function ct_keyword_b_block($current)
	{
		global $lang;

		$switch = '';
		$ch_sel = array();
		$ch_sel[$current] = ' selected="selected"';

		$switch .= '<option value="0"' . $ch_sel[0] . '>' . $lang['ctracker_settings_off'] . '</option>';
		$switch .= '<option value="1"' . $ch_sel[1] . '>' . $lang['Profile'] . '</option>';
		$switch .= '<option value="2"' . $ch_sel[2] . '>' . $lang['Profile'] . '&' . $lang['Post'] . '</option>';

		return $switch;
	}


	/**
	 * <b>ct_complex_mode</b>
	 * Generates the Password Complex Mode Switches
	 *
	 * @param $current (Integer) -> Current Setting
	 * @return $switch (String)
	 */
	function ct_complex_mode($current)
	{
		global $lang;

		$switch = '';
		$ch_sel = array();
		$ch_sel[$current] = ' selected="selected"';

		$switch .= '<option value="1"' . $ch_sel[1] . '>' . $lang['ctracker_complex_1'] . '</option>';
		$switch .= '<option value="2"' . $ch_sel[2] . '>' . $lang['ctracker_complex_2'] . '</option>';
		$switch .= '<option value="3"' . $ch_sel[3] . '>' . $lang['ctracker_complex_3'] . '</option>';
		$switch .= '<option value="4"' . $ch_sel[4] . '>' . $lang['ctracker_complex_4'] . '</option>';
		$switch .= '<option value="5"' . $ch_sel[5] . '>' . $lang['ctracker_complex_5'] . '</option>';
		$switch .= '<option value="6"' . $ch_sel[6] . '>' . $lang['ctracker_complex_6'] . '</option>';
		$switch .= '<option value="7"' . $ch_sel[7] . '>' . $lang['ctracker_complex_7'] . '</option>';
		$switch .= '<option value="8"' . $ch_sel[8] . '>' . $lang['ctracker_complex_8'] . '</option>';
		$switch .= '<option value="9"' . $ch_sel[9] . '>' . $lang['ctracker_complex_9'] . '</option>';

		return $switch;
	}


	/**
	 * <b>ct_generate_number_field</b>
	 * Generates Number Switchboxes
	 *
	 * @param $begin (Integer)   -> Start Number
	 * @param $end   (Integer)   -> End Number
	 * @param $current (Integer) -> Selected Number
	 * @return $switch (String)  -> Switch HTML Code
	 */
	function ct_generate_number_field($begin, $end, $current)
	{
		$switch = '';

		for($i = $begin; $i <= $end; $i++)
		{
			if($current == $i)
			{
				$switch .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
			}
			else
			{
				$switch .= '<option value="' . $i . '">' . $i . '</option>';
			}
		}

		return $switch;
	}


	/**
	 * <b>ct_generate_on_off</b>
	 * Generates Switch Fields to enable or disable functions
	 *
	 * @param $setting (Integer) 0: Off | 1: On
	 * @return $switch (String)
	 */
	function ct_generate_on_off($setting)
	{
		global $lang;

		$switch = '';

		if($setting == 1)
		{
			$switch = '<option value="1" selected="selected">' . $lang['ctracker_settings_on'] . '</option><option value="0">' . $lang['ctracker_settings_off'] . '</option>';
		}
		else
		{
			$switch = '<option value="1">' . $lang['ctracker_settings_on'] . '</option><option value="0" selected="selected">' . $lang['ctracker_settings_off'] . '</option>';
		}

		return $switch;
	}


	/**
	 * <b>do_filechk</b>
	 * This function is responsible for the CrackerTracker File Check
	 * (Hash Checker)
	 */
	function do_filechk()
	{
		global $db, $lang, $phpbb_root_path, $phpEx;

		// First we truncate the Database Table
		$sql = 'TRUNCATE TABLE ' . CTRACKER_FILECHK;

		if( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}

		// Checksum checker ;)
		$this->recursive_filechk($phpbb_root_path, '', $phpEx);
	}


	/**
	 * <b>recursive_filechk</b>
	 * Filewriter for the CrackerTracker Hashcode Checker
	 *
	 * @param $dir       = Recursively scanned Folder
	 * @param $prefix    = Current File Path
	 * @param $extension = File Extension to find
	 */
	function recursive_filechk($dir, $prefix = '', $extension)
	{
		global $db, $lang;

		$directory = @opendir($dir);

		while ($file = @readdir($directory))
		{
			if (!in_array($file, array('.', '..')))
			{
				$is_dir = (@is_dir($dir .'/'. $file)) ? true : false;

				// Create a nice Path for the found Files / Folders
				$temp_path  = '';
				$temp_path  = $dir . '/' . (($is_dir) ? strtoupper($file) : $file);
				$temp_path  = str_replace('//', '/', $temp_path);

				// Remove dots from extension Parameter
				$extension  = str_replace('.', '', $extension);

				// Fill it in our File Array if the found file is matching the extension
				if( preg_match("/^.*?\." . $extension . "$/", $temp_path) && !preg_match('/cache\\//m', $temp_path) )
				{
					$filehash = @filesize($temp_path) . '-' . count(@file($temp_path));
					$filehash = md5($filehash);

					$sql = 'INSERT INTO ' . CTRACKER_FILECHK . " (`filepath`, `hash`) VALUES ('$temp_path', '$filehash')";

					if(!($result = $db->sql_query($sql)))
					{
						message_die(CRITICAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
					}
				}

				// Directory found, so recall this function
				if ($is_dir)
				{
					$this->recursive_filechk($dir .'/'. $file, $dir .'/', $extension);
				}
			}
		}

		@closedir($directory);
	}


	/**
	* <b>ScanFile</b><br />
	* This function scans a file wich is saved into the
	* filescan database for potential security risks. Well this is a really
	* complex function because something like that is little bit complex to do
	* with PHP, but well, this algorithm works really fast and also if you have
	* a huge premodded board 30seconds execution time for a PHP Script should be
	* really enough to scan all PHP files of your board. Sorry for the little
	* spaghetti code in here but i've longtimes optimized this and this one is
	* really the shortest and fastest method to do that what this function
	* should do.
	*
	* @param $fid = File Identification Number in Database
	*/
	function ScanFile()
	{
		global $db, $phpbb_root_path, $lang;

		$sql = 'SELECT id, filepath FROM ' . CTRACKER_FILESCANNER;

		if((!$result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			// Initialize vars
			$common_included = false;
			$constant_check  = false;
			$constant_set    = false;
			$func_or_class   = false;
			$func_class_flag = false;
			$reachable_code  = false;
			$root_path       = false;
			$extension_inc   = false;

			$action_counter  = 0;
			$security_risk   = 0;
			$bcounter        = 0;
			$file_db_id      = 0;

			$scanline        = '';
			$acp_flag        = false;

			$filename        = file($row['filepath']);
			$file_db_id      = intval($row['id']);

			for ($i = 0; $i <= count($filename)-1; $i++)
			{
				$scanline = $filename[$i];
				$scanline = strtolower($scanline);
				$scanline = str_replace(' ', '', $scanline);
				$scanline = str_replace("\t", '', $scanline);
				$scanline = str_replace(chr(13), '', $scanline);

				if(preg_match('/\/\/ctracker_ignore:filecheckedbyhuman/', $scanline))
				{
					// File checked and verified by a human
					$common_included = true;
					$constant_set    = true;
					$root_path       = true;
					$extension_inc   = true;
					break;
				}

				if(!preg_match('/\\$no_page_header|\\$confirm|\\$close/', $scanline) && !preg_match('/^[ \\t]*$\\r?\\n/m', $scanline) && !preg_match('/<\\?php|\\?>|\/\/|\/\/.|\/\\*.|\/\\*|#|#.|\*|\*./m', $scanline) && !empty($scanline))
				{
					$action_counter++;

					if(preg_match('/define\\(\'in_phpbb\'./m', $scanline) && $action_counter == 1)
					{
						$constant_set = true;
					}
					elseif(preg_match('/if\\(!defined\\(\'in_phpbb\'./m', $scanline) && $action_counter == 1)
					{
						$constant_check = true;
						break;
					}
					elseif($constant_set && preg_match('/\\$phpbb_root_path=./m', $scanline) && $action_counter == 2)
					{
						$root_path = true;
					}
					elseif($constant_set && preg_match('/include\\(\\$phpbb_root_path\\.\'extension\\.inc|require\\(\\$phpbb_root_path\\.\'extension\\.inc/', $scanline) && $action_counter == 3)
					{
						$extension_inc = true;
					}
					elseif($constant_set && preg_match('/include\\(\\$phpbb_root_path\\.\'common\\.|include\\(\'\\.\/common\\.|require\\(\'\\.\/common\\.|require\\(\\$phpbb_root_path\\.\'common\\.|require\\(\'\\.\/pagestart\\./', $scanline) && ($action_counter == 4 || $action_counter == 5))
					{
						$common_included = true;
						break;
					}
					elseif($constant_set && preg_match('/if\\(!empty\\(\\$setmodules|if\\(isset|if\\(!isset/', $scanline))
					{
						$action_counter--;
						$acp_flag = true;

						if(preg_match('/{/m', $scanline))
						{
							$bcounter++;
						}

						if(preg_match('/}/m', $scanline))
						{
							$bcounter--;
						}
					}
					elseif($constant_set && $acp_flag)
					{
						if(preg_match('/{/m', $scanline))
						{
							$bcounter++;
						}

						if(preg_match('/}/m', $scanline))
						{
							$bcounter--;
						}

						if($bcounter == 0)
						{
							$acp_flag = false;
						}

						$action_counter--;
					}
					elseif(preg_match('/function.|class./', $scanline))
					{
						$func_or_class = true;
						$func_class_flag = true;

						if(preg_match('/{/m', $scanline))
						{
							$bcounter++;
						}

						if(preg_match('/}/m', $scanline))
						{
							$bcounter--;
						}
					}
					elseif($func_or_class)
					{
						if(preg_match('/{/m', $scanline))
						{
							$bcounter++;
						}

						if(preg_match('/}/m', $scanline))
						{
							$bcounter--;
						}

						if($bcounter == 0)
						{
							$func_or_class = false;
						}
					}
					elseif(!$constant_check || !$common_included || !$func_or_class)
					{
						$reachable_code = true;
						$func_class_flag = false;
						break;
					}
					else
					{
						$reachable_code = true;
						break;
					}// else
				} // if
			} // for

			// wich security scanner value will be written in database?
			if($constant_check)
			{
				// Constant checked, so file OK
				$security_risk = 0;
			}
			elseif($common_included && $constant_set && $root_path && $extension_inc)
			{
				// Every basics are there, so declare file as secure
				$security_risk = 0;
			}
			elseif($common_included && $constant_set && $root_path)
			{
				// We don't have the extension.inc included so someone can change the FileExtension
				$security_risk = 1;
			}
			elseif($common_included && $constant_set && $extension_inc)
			{
				// We don't have the root path defined
				$security_risk = 2;
			}
			elseif($extension_inc || $root_path)
			{
				// We don't have common.php included
				$security_risk = 3;
			}
			elseif($reachable_code)
			{
				// There is reachable code in the file
				$security_risk = 4;
			}
			else if($common_included)
			{
				// We miss everything except the common.php
				$security_risk = 5;
			}
			elseif($func_class_flag)
			{
				// File is a function or class file, so no reachable code detected
				$security_risk = 0;
			}
			else
			{
				// Something happened wich is not defined. Confusing message
				$security_risk = 6;
			}

			// Write value back to database
			$write_back = 'UPDATE ' . CTRACKER_FILESCANNER . ' SET safety = ' . intval($security_risk) . ' WHERE id = ' . $row['id'];
			if((!$backwriter = $db->sql_query($write_back)))
			{
				message_die(CRITICAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $write_back);
			}
		} // while
	}


	/**
	* <b>DropData</b><br />
	* This function cleans up the Database from the FileScanner before rescanning
	*/
	function DropData()
	{
		global $db, $lang;

		$sql = 'TRUNCATE TABLE ' . CTRACKER_FILESCANNER;

		if(!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}
	}


	/**
	 * <b>CreateFileList</b><br />
	 * This is a recursive Function to display all specific files from all
	 * subdirectories for the file security scanner. We save these files into a
	 * database for better FileList handling later - and we can always read out
	 * the results without rescanning. ;)
	 *
	 * @param $dir       = recursively scanned folder
	 * @param $prefix    = current file path
	 * @param $extension = file extension to find
	 */
	function CreateFileList($dir, $prefix = '', $extension)
	{
		global $db, $lang;

		$directory = @opendir($dir);

		while ( @$file = readdir($directory) )
		{
			if ( !in_array( $file, array('.', '..') ) )
			{
				$is_dir = ( is_dir($dir . '/' . $file) ) ? true : false;

				// create real filepath
				$temp_path  = '';
				$temp_path  = $dir . '/' . (($is_dir) ? strtoupper($file) : $file);
				$temp_path  = str_replace('//', '/', $temp_path);

				// remove dot from extension parameter
				$extension  = str_replace('.', '', $extension);

				// special filter for file extension and folders (we don't check language folders or the cache)
				if( preg_match("/^.*?\." . $extension . "$/", $temp_path) )
				{
					if( !preg_match('/language\\/|lang_|db\\/|config\\.php|cache\\/|common\\.php/m', $temp_path) )
					{
						$sql = "SELECT MAX(id) AS total FROM " . CTRACKER_FILESCANNER;
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
						}
						if ( !($row = $db->sql_fetchrow($result)) )
						{
							message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
						}
						$newid = $row['total'] + 1;

						$sql = 'INSERT INTO ' . CTRACKER_FILESCANNER . ' (`id`, `filepath`, `safety`)
									VALUES (' . $newid . ', \'' . $temp_path . '\', 10)';

						if(!($result = $db->sql_query($sql)))
						{
							message_die(CRITICAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
						}
					}
				}

				// Directory found, so recall this function
				if ( $is_dir )
				{
					$this->CreateFileList($dir .'/'. $file, $dir .'/', $extension);
				}
			} // if
		} // while

		@closedir($directory);

	} // CreateFileList


	/**
	 * <b>set_global_message</b>
	 * Sets the global message flag for every user
	 */
	function set_global_message()
	{
		global $db, $lang;

		$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_global_msg_read = 1';

		if( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
		}
	}


	/**
	 * <b>unset_global_message</b>
	 * Unset global message flag for every user
	 */
	function unset_global_message()
	{
		global $db, $lang;

		$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_global_msg_read = 0';

		if( !($result = $db->sql_query($sql)) )
		{
			message_die(CRITICAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
		}
	}


	/**
	 * <b>recover_configuration</b>
	 * Quick Recover phpBB Configuration
	 */
	function recover_configuration()
	{
		global $db, $lang;

		// Drop existing Backup Table
		$sql = 'DROP TABLE IF EXISTS ' . CTRACKER_BACKUP;
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}

		// Create Backup table
		$sql = 'CREATE TABLE ' . CTRACKER_BACKUP . ' (
					`config_name` varchar( 255 ) NOT NULL ,
					`config_value` text NOT NULL ,
					PRIMARY KEY ( `config_name` )
					)';
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}

		// Insert config data
		$sql = 'SELECT * FROM ' . CONFIG_TABLE;

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_loading_config'], '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$row['config_name'] = addslashes($row['config_name']);
			$row['config_value'] = addslashes($row['config_value']);
			$sql2 = 'INSERT INTO ' . CTRACKER_BACKUP . ' (`config_name`, `config_value`) VALUES ("' . $row['config_name'] . '", "'. $row['config_value'] . '")';
			if ( !$result2 = $db->sql_query($sql2) )
			{
				message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql2);
			}
		}

		// Insert Backup Timestamp
		$sql = 'INSERT INTO ' . CTRACKER_BACKUP . ' (`config_name`, `config_value`) VALUES (\'ct_last_backup\', \'' . time() . '\')';
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}
	}


	/**
	 * <b>restore_configuration</b>
	 * Quick restore phpBB Configuration
	 */
	function restore_configuration()
	{
		global $db, $lang;

		// Drop existing Config Table
		$sql = 'DROP TABLE IF EXISTS ' . CONFIG_TABLE;
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}

		// Create Config table
		$sql = 'CREATE TABLE ' . CONFIG_TABLE . ' (
					`config_name` varchar( 255 ) NOT NULL ,
					`config_value` text NOT NULL ,
					PRIMARY KEY ( `config_name` )
					)';
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}

		// Insert config data
		$sql = 'SELECT * FROM ' . CTRACKER_BACKUP;

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_loading_config'], '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$sql2 = 'INSERT INTO ' . CONFIG_TABLE . ' (`config_name`, `config_value`) VALUES (\''. $row['config_name'] . '\', \''. $row['config_value'] . '\')';
			if ( !$result2 = $db->sql_query($sql2) )
			{
				message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
			}
		}

		// Remove Backup Timestamp
		$sql = 'DELETE FROM ' . CONFIG_TABLE . ' WHERE config_name = \'ct_last_backup\'';
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_database_op'], '', __LINE__, __FILE__, $sql);
		}
	}
}

?>