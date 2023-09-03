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
* <b>CrackerTracker File: class_ct_userfunctions.php</b><br /><br />
*
* This class implements all userfunctions for the CrackerTracker security
* system. These are the Database handling of the userdata field as well
* as the handling of security relevant functions for the Board internal
* engines.
*
*
* @author Christian Knerr (cback)
* @package ctracker
* @version 5.0.0
* @since 20.07.2006 - 21:08:18
* @copyright (c) 2006 www.cback.de
*
*/

class ct_userfunctions
{

	/**
	* <b>search_handler</b><br />
	* This controls the CrackerTracker Search Security functions and
	* outputs an wait-message if a user or guest has executed more searches
	* than allowed.
	*/
	function search_handler()
	{
		global $db, $config, $user, $lang, $is_ajax;

		if (($config['ctracker_search_feature_enabled'] == 0) || ($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD))
		{
			// Search feature function was disabled
			return;
		}

		// Initialize later used vars
		$max_searches = 0;
		$wait_time = 0;

		if ($user->data['user_id'] == ANONYMOUS)
		{
			$max_searches = $config['ctracker_search_count_guest'];
			$wait_time = $config['ctracker_search_time_guest'];
		}
		else
		{
			$max_searches = $config['ctracker_search_count_user'];
			$wait_time = $config['ctracker_search_time_user'];
		}


		/*
		* Now we do the Search control
		*/
		if (($user->data['user_level'] != ADMIN) || !$is_ajax)
		{
			if ($user->data['ct_search_time'] < time())
			{
				/*
				* Block-Time is not there, so reset the values in Usertable
				*/
				$search_time_new = time() + $wait_time;

				$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_search_time = ' . $search_time_new . ', ct_search_count = 1 WHERE user_id = ' . $user->data['user_id'];

				// Execute SQL Command in database
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
				}

			}
			elseif ($user->data['ct_search_count'] < $max_searches)
			{
				/*
				* We're still in the time limitations, but the user has
				* a possibility to start multiple searches in this time
				* but we have to count all these searches.
				*/
				$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_search_count = ct_search_count + 1 WHERE user_id = ' . $user->data['user_id'];

				// Execute SQL Command in database
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
				}
			}
			else
			{
				// How long a user really has to wait?
				$real_wait_time = $user->data['ct_search_time'] - time();

				/*
				* So a user or guest wanted to search once more so to really
				* have an efficient blocking we will set a new Time Span to wait.
				* Also we increment the Search counter one time that no new search
				* wait time will be set if someone tries AGAIN to search.
				*/
				if ($user->data['ct_search_count'] == $max_searches)
				{
					$search_time_new = time() + $wait_time;
					$real_wait_time  = $search_time_new - time();

					$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_search_time = ' . $search_time_new . ', ct_search_count = ct_search_count + 1 WHERE user_id = ' . $user->data['user_id'] . ';';

					// Execute SQL Command in database
					if (!$result = $db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
					}
				}


				/*
				* Output the wait message
				*/
				$waitmessage = '';
				$waitmessage = sprintf($lang['ctracker_info_search_time'], $max_searches, $wait_time, $real_wait_time, $real_wait_time);

				message_die(GENERAL_MESSAGE, $waitmessage);

			} // else
		}

		/*
		if ($is_ajax)
		{
			$result_ar = array(
				'search_id' => 0,
				'results' => 0,
				'keywords' => ''
			);
			AJAX_message_die($result_ar);
		}
		*/
	} // search_handler


	/**
	* <b>check_ip_range</b><br />
	* This function checks the IP Range of an user after login.
	* Its part of the IP Range Scanner.
	*
	* @return (String) (the info message itself)
	*/
	function check_ip_range()
	{
		global $user, $lang;

		if ($user->data['ct_last_ip'] == '0.0.0.0' || $user->data['ct_last_used_ip'] == '0.0.0.0')
		{
			return 'allclear'; // not yet initialized
		}

		$first_ip_range  = array();
		$second_ip_range = array();

		$first_ip_range  = explode('.', $user->data['ct_last_used_ip']);
		$second_ip_range = explode('.', $user->data['ct_last_ip']);

		if ($first_ip_range[0] == $second_ip_range[0] && $first_ip_range[1] == $second_ip_range[1])
		{
			return 'allclear';
		}

		return sprintf($lang['ctracker_ipwarn_chng'], $first_ip_range[0] . '.' . $first_ip_range[1] . '.x.x', $second_ip_range[0] . '.' . $second_ip_range[1] . '.x.x');
	}


	/**
	* <b>handle_postings</b>
	* This is the spammer post detection. Every features for post scanning you
	* can find here in one place. This function includes two features. Standard
	* Spammer detection system and the System for Spam Detection Boost and Spam
	* Detection Wordfilter.
	*
	* <br /><br />
	*
	* I will show in a little diagram how this function works because the code
	* is little bit tricky if you have not programmed it. ;-)
	*
	* <br /><br />
	*
	* First we check if time() >= ctracker_spammer_time. If so we have to start a new
	* counting for this user. So we write ct_last_post = time() +
	* $config['ctracker_spammer_time'] into the usertable and we set
	* the Database field ct_post_counter to 1.
	*
	* If time is not >= ctracker_spammer_time we have to check if ct_post_counter
	* < $config['ctracker_spammer_postcount'] to see if the maximum
	* number of posts in the timespan is exceeded. One post before banning the
	* user we output a warning message that a user is informed.
	*
	* We do the warning message in a very simple way: If the new counter value
	* == the maximum post count in the timespan we output a message_die() and
	* we don't write the post into the database then.
	*
	* If the user starts his next attempt we handle it as spammer and block the
	* user.
	*/
	function handle_postings()
	{
		global $db, $config, $user, $lang;

		// MOD or ADMIN? - No Action please.
		if ($user->data['user_level'] > 0)
		{
			return;
		}

		// Standard Spammer detection system
		// Why String and Int Check? Well some servers have problems to cast values from an Object so we make here little compatibility tricks
		if ($config['ctracker_spammer_blockmode'] != '0' || intval($config['ctracker_spammer_blockmode']) > 0)
		{
			if (time() >= $user->data['ct_last_post'])
			{
				$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_post_counter = 1, ct_last_post = ' . time() . '+' . $config['ctracker_spammer_time'] . ' WHERE user_id = ' . $user->data['user_id'];
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
				}
			}
			else if ($user->data['ct_post_counter'] < intval($config['ctracker_spammer_postcount']))
			{
				$sql = 'UPDATE ' . USERS_TABLE . ' SET ct_post_counter = ct_post_counter + 1 WHERE user_id = ' . $user->data['user_id'];
				if (!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
				}

				$user->data['ct_post_counter']++;
				if ($user->data['ct_post_counter'] == intval($config['ctracker_spammer_postcount']))
				{
					message_die(GENERAL_MESSAGE, sprintf($lang['ctracker_binf_spammer'], $config['ctracker_spammer_time'], $config['spammer_time']));
				}
			}
			else
			{
				$this->block_handler();
			} // else
		} // standard spammer detection


		// Spammer Boost
		if ($config['ctracker_spam_attack_boost'] == '1' || intval($config['ctracker_spam_attack_boost']) == 1)
		{
			if ($user->data['user_posts'] >= 2)
			{
				return;
			}

			$url_count = 0;
			$match1 = array();
			$match2 = array();
			$match1 = preg_split('/\\[url=|www\\.|http:\/\//', $_POST['message']);
			$match2 = preg_split('/\\[url=|www\\.|http:\/\//', $_POST['subject']);
			$url_count = sizeof($match1) + sizeof($match2) - 2;

			$eur_count = 0;
			$match1 = array();
			$match2 = array();
			$match1 = preg_split('/US|\\$|€/m', $_POST['message']);
			$match2 = preg_split('/US|\\$|€/m', $_POST['subject']);
			$eur_count = sizeof($match1) + sizeof($match2) - 2;

			if ($url_count > 6 || $eur_count > 6)
			{
				$this->block_handler();
			}

			if ($config['ctracker_spam_keyword_det'] == '2' || intval($config['ctracker_spam_keyword_det']) == 2)
			{
				// Did this that Eclipse does not output warning message because
				// the IDE doesn't know that we initialize this in the included
				// file!
				$ct_spammer_def = array();

				include_once(IP_ROOT_PATH . 'includes/ctracker/constants.' . PHP_EXT);

				for($i = 0; $i < sizeof($ct_spammer_def); $i++)
				{
					$current_value = preg_quote($ct_spammer_def[$i]);
		 			$current_value = str_replace('\*', '.*?', $current_value);

					$clean_message = str_replace("\xAD", '', $_POST['message']);
					$clean_title   = str_replace("\xAD", '', $_POST['subject']);

					if (preg_match('/^' . $current_value . '$/is', $clean_message) || preg_match('/^' . $current_value . '$/is', $clean_title))
					{
						$this->block_handler();
					} // if
				} // for
			} // if
		} // spammer boost
	}


	/**
	* <b>block_handler</b>
	* Blocks a user if required
	*/
	function block_handler()
	{
		global $db, $config, $user, $lang;

		if ($user->data['user_id'] == ANONYMOUS)
		{
			return;
		}

		if ((intval($config['ctracker_spammer_blockmode']) == 1) && ($user->data['user_id'] != ANONYMOUS))
		{
			// Ban user
			$sql = "INSERT INTO " . BANLIST_TABLE . "(`ban_id` , `ban_userid` , `ban_ip` , `ban_email`) VALUES ('', '" . $user->data['user_id'] . "', '', NULL);";
			if(!$db->sql_query($sql))
			{
				message_die(CRITICAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
			}
			$db->clear_cache('ban_', USERS_CACHE_FOLDER);
		}
		elseif (intval($config['ctracker_spammer_blockmode']) == 2)
		{
			// Block user
			$sql = 'UPDATE ' . USERS_TABLE . ' SET user_active = 0 WHERE user_id = ' . $user->data['user_id'];
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
			}
			// Mighty Gorgon: Remove all notifications...
			if (!function_exists('user_clear_notifications'))
			{
				include_once(IP_ROOT_PATH . 'includes/functions_users_delete.' . PHP_EXT);
			}
			$clear_notification = user_clear_notifications($user->data['user_id']);
		}

		// Remove Profile data
		// Removed user_email = 'info@example.com'
		$sql = "UPDATE " . USERS_TABLE . " SET user_allowavatar = 0, user_icq = '', user_website = '', user_from = '', user_sig = '', user_aim = '', user_yim = '', user_msnm = '', user_occ = '', user_interests = '' WHERE user_id = " . $user->data['user_id'];
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
		}

		// Log it
		include_once(IP_ROOT_PATH . 'includes/ctracker/classes/class_log_manager.' . PHP_EXT);
		$logfile = new log_manager();
		$logfile->prepare_log($user->data['username']);
		$logfile->write_general_logfile($config['ctracker_logsize_spammer'], 5);
		unset($logfile);

		// Log out user
		if($user->data['session_logged_in'])
		{
			$user->session_kill();
		}

		// Output Info Message
		message_die(GENERAL_MESSAGE, $lang['ctracker_binf_sban']);
	}


	/**
	* <b>handle_profile</b>
	* This function includes all the register protection
	* features of CrackerTracker. So we just have to call this function from
	* the registersite and we can manage all features at this one place. :)
	*
	* Includes:
	* - Register Protection (Time)
	* - Register IP Protection
	* - Spammer detection (Username & Mails)
	* - Spammer words detection in Profile
	*/
	function handle_profile()
	{
		global $config, $user, $lang, $mode, $ctracker_config;

		/*
		* Done this that Eclipse or another Code-Checker does not output
		* warning messages here because it does not know that the Vars are
		* initialized in the included file and so they have not to be defined
		* as global in this function.
		*/
		$ct_spammer_def = array();
		$ct_mailscn_def = array();
		$ct_userspm_def = array();

		// We need the constants file so we include it now
		include_once(IP_ROOT_PATH . 'includes/ctracker/constants.' . PHP_EXT);

		// Register Protection (TIME)
		if (intval($config['ctracker_reg_protection']) == 1 && $mode == 'register')
		{
			if (time() <= intval($config['ctracker_reg_last_reg']))
			{
				$waittime_new = intval($config['ctracker_reg_last_reg']) - time();
				message_die(GENERAL_MESSAGE, sprintf($lang['ctracker_info_regist_time'], $config['ctracker_reg_blocktime'], $waittime_new, $waittime_new));
			}
		}

		// Register IP Feature
		if (intval($config['ctracker_reg_ip_scan']) == 1 && $mode == 'register')
		{
			if ($config['ctracker_user_ip_value'] == $config['ctracker_reg_lastip'])
			{
				message_die(GENERAL_MESSAGE, $lang['ctracker_info_regip_double']);
			}
		}

		// Registration Scan blocked Mails
		if (isset($_POST['submit']) && intval($config['ctracker_autoban_mails']) == 1 && $mode == 'register')
		{
			for($i = 0; $i < sizeof($ct_userspm_def); $i++)
			{
				if ($_POST['username'] == $ct_userspm_def[$i])
				{
					message_die(GENERAL_MESSAGE, $lang['ctracker_info_profile_spammer']);
				}
			}

			for($i = 0; $i < sizeof($ct_mailscn_def); $i++)
			{
				$current_value = preg_quote($ct_mailscn_def[$i]);
		 		$current_value = str_replace('\*', '.*?', $current_value);

				if (preg_match('/^' . $current_value . '$/is', $_POST['email']))
				{
					message_die(GENERAL_MESSAGE, $lang['ctracker_info_profile_spammer']);
				}
			}
		}

		// Registration Scan blocked Words
		if (isset($_POST['submit']) && intval($config['ctracker_spam_keyword_det']) >= 1)
		{
			for($i = 0; $i < sizeof($ct_spammer_def); $i++)
			{
				$current_value = preg_quote($ct_spammer_def[$i]);
				$current_value = str_replace('\*', '.*?', $current_value);

				$clean_aim = str_replace("\xAD", '', $_POST['aim']);
				$clean_msn = str_replace("\xAD", '', $_POST['msn']);
				$clean_yim = str_replace("\xAD", '', $_POST['yim']);
				$clean_website = str_replace("\xAD", '', $_POST['website']);
				$clean_location = str_replace("\xAD", '', $_POST['location']);
				$clean_occupation = str_replace("\xAD", '', $_POST['occupation']);
				$clean_interests = str_replace("\xAD", '', $_POST['interests']);
				$clean_signature = str_replace("\xAD", '', $_POST['signature']);

				if (preg_match('/^' . $current_value . '$/is', $clean_aim) || preg_match('/^' . $current_value . '$/is', $clean_msn) || preg_match('/^' . $current_value . '$/is', $clean_yim) || preg_match('/^' . $current_value . '$/is', $clean_website) || preg_match('/^' . $current_value . '$/is', $clean_location) || preg_match('/^' . $current_value . '$/is', $clean_occupation) || preg_match('/^' . $current_value . '$/is', $clean_interests) || preg_match('/^' . $current_value . '$/is', $clean_signature))
				{
					(($mode != 'register') && ($user->data['user_level'] == 0)) ? $this->block_handler() : null;
					message_die(GENERAL_MESSAGE, $lang['ctracker_info_profile_spammer']);
				} // if
			} // for
		} // reg scan blocked words
	} // function


	/**
	* <b>reg_done</b>
	* This handles everything when a registration was done
	*/
	function reg_done()
	{
		global $db, $cache, $config, $ctracker_config;

		// Regtime
		$waittime_new = time() + intval($config['ctracker_reg_blocktime']);
		set_config('ctracker_reg_last_reg', $waittime_new, false);

		// Reg IP
		set_config('ctracker_reg_lastip', $config['ctracker_user_ip_value'], false);

		$db->clear_cache();
	}

	/**
	* <b>password_functions</b>
	* All Password security functions of CrackerTracker in one place
	*/
	function password_functions()
	{
		global $db, $config, $user, $lang, $mode;

		// Password length check
		$pw_length = !empty($_POST['new_password']) ? strlen($_POST['new_password']) : 0;
		if (($pw_length < $config['ctracker_pw_complex_min']) && !empty($_POST['new_password']))
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['ctracker_info_password_minlng'], $config['ctracker_pw_complex_min'], $pw_length));
		}

		// Password complexity
		if ((intval($config['ctracker_pw_complex']) == 1) && !empty($_POST['new_password']))
		{
			$p_patterns = '';
			$active_pw_prot = '';
			$p_pass = $_POST['new_password'];

			switch (intval($config['ctracker_pw_complex_mode']))
			{
				case 1: $p_patterns = '/^.*(?=.+)(?=.*\\d).*$/'; // [0-9]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_1'];
						break;

				case 2: $p_patterns = '/^.*(?=.+)(?=.*[a-z]).*$/'; // [a-z]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_2'];
						break;

				case 3: $p_patterns = '/^.*(?=.+)(?=.*[A-Z]).*$/'; // [A-Z]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_3'];
						break;

				case 4: $p_patterns = '/^.*(?=.+)(?=.*\\d)(?=.*[a-z]).*$/'; // [0-9][a-z]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_1'] . ', ' . $lang['ctracker_info_password_cmplx_2'];
						break;

				case 5: $p_patterns = '/^.*(?=.+)(?=.*\\d)(?=.*[A-Z]).*$/'; // [0-9][A-Z]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_1'] . ', ' . $lang['ctracker_info_password_cmplx_3'];
						break;

				case 6: $p_patterns = '/^.*(?=.+)(?=.*\\d)(?=.*[a-z])(?=.*[A-Z]).*$/'; // [0-9][a-z][A-Z]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_1'] . ', ' . $lang['ctracker_info_password_cmplx_2'] . ', ' . $lang['ctracker_info_password_cmplx_3'];
						break;

				case 7: $p_patterns = '/^.*(?=.+)(?=.*\\d)(?=.\\W).*$/'; // [0-9][*]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_1'] . ', ' . $lang['ctracker_info_password_cmplx_4'];
						break;

				case 8: $p_patterns = '/^.*(?=.+)(?=.*\\d)(?=.*[a-z])(?=.*\\W).*$/'; // [0-9][a-z][*]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_1'] . ', ' . $lang['ctracker_info_password_cmplx_2'] . ', ' . $lang['ctracker_info_password_cmplx_4'];
						break;

				case 9: $p_patterns = '/^.*(?=.+)(?=.*\\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\\W).*$/'; // [0-9][a-z][A-Z][*]
						$active_pw_prot = $lang['ctracker_info_password_cmplx_1'] . ', ' . $lang['ctracker_info_password_cmplx_2'] . ', ' . $lang['ctracker_info_password_cmplx_3'] . ', ' . $lang['ctracker_info_password_cmplx_4'];
						break;
			}

			if (!preg_match($p_patterns, $p_pass))
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['ctracker_info_password_cmplx'], $active_pw_prot));
			}
		}
	}


	/**
	* <b>pw_create_date</b>
	* Writes the PW Create Date into the User Table for the PW
	* Expiry Feature
	*
	* @param $user_id (Integer) - ID of the User
	*/
	function pw_create_date($user_id)
	{
		global $db, $lang, $ctracker_config;

		// Ensure $user_id is integer
		$user_id = (int) $user_id;

		$sql = 'UPDATE ' . USERS_TABLE . ' SET user_passchg = ' . time() . ' WHERE user_id = ' . $user_id;
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['ctracker_error_updating_userdata'], '', __LINE__, __FILE__, $sql);
		}
	}

}

?>