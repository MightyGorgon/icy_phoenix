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
* @Icy Phoenix is based on phpBB
* @copyright (c) 2008 phpBB Group
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}


/**
* Cache management class
*/
class ip_cache extends acm
{

	/*
	* Get config values
	*/
	function obtain_config()
	{
		if (($config = $this->get('config')) === false)
		{
			$config = array();
			$config = get_config(false);
			$this->put('config', $config);
		}

		return $config;
	}

	/*
	* Get CMS config values
	*/
	function obtain_cms_config()
	{
		if (($config = $this->get('_cms_config')) === false)
		{
			$config = array();
			$config = get_cms_config(false);
			$this->put('_cms_config', $config);
		}

		return $config;
	}

	/*
	* Get CMS layouts config values
	*/
	function obtain_cms_layouts_config()
	{
		if (($config = $this->get('_cms_layouts_config')) === false)
		{
			$config = array();
			$config = get_layouts_config(false);
			$this->put('_cms_layouts_config', $config);
		}

		return $config;
	}

	/*
	* Get CMS global blocks config values
	*/
	function obtain_cms_global_blocks_config($from_cache = false)
	{
		global $db, $ip_cms;

		$auth_level = $ip_cms->cms_blocks_view();
		$auth_level_suffix = implode('', $auth_level);
		if (($config = $this->get('_cms_global_blocks_config_' . $auth_level_suffix)) === false)
		{
			$config = array();
			$sql = "SELECT *
				FROM " . CMS_BLOCKS_TABLE . "
				WHERE layout = 0
				AND active = 1
				AND " . $db->sql_in_set('view', $auth_level) . "
				AND bposition IN ('gh','gf','gt','gb','gl','gr','hh','hl','hc','fc','fr','ff')
				ORDER BY bposition ASC, layout ASC, layout_special ASC, weight ASC";
			$result = $from_cache ? $db->sql_query($sql, 0, 'cms_global_blocks_', CMS_CACHE_FOLDER) : $db->sql_query($sql);

			$config = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$config[$row['bposition']][] = $row;
			}
			$db->sql_freeresult($result);
			$this->put('_cms_global_blocks_config_' . $auth_level_suffix, $config);
		}

		return $config;
	}

	/*
	* Get default style
	*/
	function obtain_default_style($from_cache = false)
	{
		global $db, $config;

		if (($default_style = $this->get('config_style')) === false)
		{
			$default_style = array();
			$style_id = (int) $config['default_style'];
			$default_style = get_style($style_id, $from_cache);

			$this->put('config_style', $default_style);
		}

		return $default_style;
	}

	/*
	* Get attachments config values
	*/
	function obtain_attachments_config($from_cache = false)
	{
		global $db;

		if (($config = $this->get('config_attach')) === false)
		{
			$config = array();

			$sql = "SELECT * FROM " . ATTACH_CONFIG_TABLE;
			$result = $from_cache ? $db->sql_query($sql, 0, 'attach_config_') : $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$config[$row['config_name']] = trim($row['config_value']);
			}
			$db->sql_freeresult($result);

			$this->put('config_attach', $config);
		}

		return $config;
	}

	/*
	* Get CTracker config values
	*/
	function obtain_ctracker_config($from_cache = false)
	{
		global $db;

		if (($config = $this->get('config_ctracker')) === false)
		{
			$config = array();

			$sql = "SELECT * FROM " . CTRACKER_CONFIG;
			$result = $from_cache ? $db->sql_query($sql, 0, 'ct_config_') : $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$config[$row['ct_config_name']] = trim($row['ct_config_value']);
			}
			$db->sql_freeresult($result);

			$this->put('config_ctracker', $config);
		}

		return $config;
	}

	/**
	* Obtain list of naughty words and build preg style replacement arrays for use by the calling script
	*/
	function obtain_word_list()
	{
		global $db;

		if (($censors = $this->get('_word_censors')) === false)
		{
			$sql = "SELECT word, replacement FROM " . WORDS_TABLE . " ORDER BY length(word) DESC";
			$result = $db->sql_query($sql);

			$censors = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$censors['match'][] = '#(?<!\w)(' . str_replace('\*', '\w*?', preg_quote($row['word'], '#')) . ')(?!\w)#i';
				$censors['replace'][] = $row['replacement'];
			}
			$db->sql_freeresult($result);

			$this->put('_word_censors', $censors);
		}

		return $censors;
	}

	/*
	* Get newest user
	*/
	function obtain_newest_user()
	{
		global $config;

		if (($newest_user = $this->get('newest_user')) === false)
		{
			$newest_user = colorize_username($config['last_user_id']);

			$this->put('newest_user', $newest_user);
		}

		return $newest_user;
	}

	/*
	* Get moderators
	*/
	function obtain_moderators($from_cache = false)
	{
		global $db, $config;

		if (($moderators = $this->get('_moderators')) === false)
		{
			$moderators = array();

			//
			// Obtain list of moderators of each forum
			// First users, then groups ... broken into two queries
			//
			$sql = "SELECT aa.forum_id, u.user_id, u.username, u.user_active, u.user_color
					FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
					WHERE aa.auth_mod = " . TRUE . "
						AND g.group_single_user = 1
						AND ug.group_id = aa.group_id
						AND g.group_id = aa.group_id
						AND u.user_id = ug.user_id
					GROUP BY u.user_id, u.username, aa.forum_id
					ORDER BY aa.forum_id, u.user_id";
			$result = $from_cache ? $db->sql_query($sql, 0, 'moderators_') : $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$moderators['users'][] = $row;
			}
			$db->sql_freeresult($result);

			$sql = "SELECT aa.forum_id, g.group_id, g.group_name, g.group_color
					FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
					WHERE aa.auth_mod = " . TRUE . "
						AND g.group_single_user = 0
						AND g.group_type <> " . GROUP_HIDDEN . "
						AND ug.group_id = aa.group_id
						AND g.group_id = aa.group_id
					GROUP BY g.group_id, g.group_name, aa.forum_id
					ORDER BY aa.forum_id, g.group_id";
				$result = $from_cache ? $db->sql_query($sql, 0, 'moderators_') : $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$moderators['groups'][] = $row;
			}
			$db->sql_freeresult($result);

			$this->put('_moderators', $moderators);
		}

		return $moderators;
	}

	/*
	* Get smileys
	*/
	function obtain_smileys($from_cache = false)
	{
		global $db, $config;

		if (($smileys = $this->get('_smileys')) === false)
		{
			$smileys = array();
			$smileys_path = create_server_url() . $config['smilies_path'] . '/';

			$sql = "SELECT code, smile_url FROM " . SMILIES_TABLE . " ORDER BY smilies_order";
			$result = $from_cache ? $db->sql_query($sql, 0, 'smileys_') : $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$smileys[] = array(
					'code' => $row['code'],
					'replace' => '<img src="' . $smileys_path . $row['smile_url'] . '" alt="" />'
				);
			}
			$db->sql_freeresult($result);

			$this->put('_smileys', $smileys);
		}

		return $smileys;
	}

	/*
	* Get styles
	*/
	function obtain_styles($from_cache = false)
	{
		global $db;

		if (($styles = $this->get('_styles')) === false)
		{
			$styles = array();
			//$sql = "SELECT * FROM " . THEMES_TABLE . " ORDER BY style_name, themes_id";
			$sql = "SELECT themes_id, style_name FROM " . THEMES_TABLE . " ORDER BY style_name, themes_id";
			$result = $from_cache ? $db->sql_query($sql, 0, 'styles_') : $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$styles[$row['themes_id']] = $row['style_name'];
			}
			$db->sql_freeresult($result);

			$this->put('_styles', $styles);
		}

		return $styles;
	}

	/*
	* Get bbcodes
	*/
	function obtain_bbcodes($from_cache = false)
	{
		global $db, $config;

		if (($bbcodes = $this->get('_bbcodes')) === false)
		{
			$bbcodes = array();

			$sql = "SELECT * FROM " . BBCODES_TABLE . " ORDER BY bbcode_id";
			$result = $from_cache ? $db->sql_query($sql, 0, 'bbcodes_') : $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$bbcodes[] = $row;
			}
			$db->sql_freeresult($result);

			$this->put('_bbcodes', $bbcodes);
		}

		return $bbcodes;
	}

	/*
	* Get ranks
	*/
	function obtain_ranks($from_cache = false)
	{
		global $db;

		if (($ranks = $this->get('_ranks')) === false)
		{
			$ranks = array();

			$sql = "SELECT ban_userid FROM " . BANLIST_TABLE . " WHERE ban_userid <> 0 ORDER BY ban_userid ASC";
			$result = $from_cache ? $db->sql_query($sql, 0, 'ban_', USERS_CACHE_FOLDER) : $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$ranks['bannedrow'][] = $row;
			}
			$db->sql_freeresult($result);

			$sql = "SELECT * FROM " . RANKS_TABLE . " ORDER BY rank_special ASC, rank_min ASC";
			$result = $from_cache ? $db->sql_query($sql, 0, 'ranks_') : $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$ranks['ranksrow'][] = $row;
			}
			$db->sql_freeresult($result);

			$this->put('_ranks', $ranks);
		}

		return $ranks;
	}

	/**
	* Get today visitors
	*/
	function obtain_today_visitors()
	{
		global $db, $config, $lang;

		if (($today_visitors = $this->get('_today_visitors_' . $config['board_timezone'])) === false)
		{

			$today_visitors['admins'] = '';
			$today_visitors['mods'] = '';
			$today_visitors['users'] = '';
			$today_visitors['reg_hidden'] = 0;
			$today_visitors['reg_visible'] = 0;
			$today_visitors['last_hour'] = 0;

			$time_now = time();
			$time1Hour = $time_now - 3600;
			$minutes = gmdate('is', $time_now);
			$hour_now = $time_now - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]);
			$dato = create_date('H', $time_now, $config['board_timezone']);
			$timetoday = $hour_now - (3600 * $dato);
			$sql = 'SELECT session_ip, MAX(session_time) as session_time
							FROM ' . SESSIONS_TABLE . '
							WHERE session_user_id="' . ANONYMOUS . '"
								AND session_time >= ' . $timetoday . '
								AND session_time < ' . ($timetoday + 86399) . '
							GROUP BY session_ip';
			$result = $db->sql_query($sql);

			while($guest_list = $db->sql_fetchrow($result))
			{
				if ($guest_list['session_time'] > $time1Hour)
				{
					$today_visitors['last_hour']++;
				}
			}
			$today_visitors['total_guests'] = $db->sql_numrows($result);
			$db->sql_freeresult($result);

			$sql = 'SELECT user_id, username, user_active, user_color, user_allow_viewonline, user_level, user_lastlogon
							FROM ' . USERS_TABLE . '
							WHERE user_id != "' . ANONYMOUS . '"
								AND user_session_time >= ' . $timetoday . '
								AND user_session_time < ' . ($timetoday + 86399) . '
							ORDER BY username';
			$result = $db->sql_query($sql);

			while($todayrow = $db->sql_fetchrow($result))
			{
				$todayrow['user_level'] = ($todayrow['user_level'] == JUNIOR_ADMIN) ? ADMIN : $todayrow['user_level'];
				$style_color = '';
				if ($todayrow['user_lastlogon'] >= $time1Hour)
				{
					$today_visitors['last_hour']++;
				}
				$colored_user = colorize_username($todayrow['user_id'], $todayrow['username'], $todayrow['user_color'], $todayrow['user_active']);
				$colored_user = (($todayrow['user_allow_viewonline']) ? $colored_user : (($userdata['user_level'] == ADMIN) ? '<i>' . $colored_user . '</i>' : ''));
				if ($todayrow['user_allow_viewonline'] || ($userdata['user_level'] == ADMIN))
				{
					switch ($todayrow['user_level'])
					{
						case ADMIN:
							$today_visitors['admins'] .= (empty($today_visitors['admins']) ? '' : ', ') . $colored_user;
						break;
						case MOD:
							$today_visitors['mods'] .= (empty($today_visitors['mods']) ? '' : ', ') . $colored_user;
						break;
						default:
							$today_visitors['users'] .= (empty($today_visitors['users']) ? '' : ', ') . $colored_user;
						break;
					}
				}

				if (!$todayrow['user_allow_viewonline'])
				{
					$today_visitors['reg_hidden']++;
				}
				else
				{
					$today_visitors['reg_visible']++;
				}
			}

			$today_visitors['total_users'] = $db->sql_numrows($result) + $today_visitors['total_guests'];
			$db->sql_freeresult($result);

			//You can set once per day... but that is too restrictive... better once every hour!
			//$expiry = create_date_midnight(time(), $config['board_timezone']) - time() + 86400;
			$expiry = 3600 - ((int) gmdate('i') * 60) - (int) gmdate('s');
			$this->put('_today_visitors_' . $config['board_timezone'], $today_visitors, $expiry);
		}

		return $today_visitors;
	}

	/**
	* Obtain mods settings files...
	*/
	function obtain_mods_settings()
	{
		if (($mods_files = $this->get('_mods')) === false)
		{
			$mods_files = array();

			// Now search for mods...
			$dir = @opendir(IP_ROOT_PATH . 'includes/mods_settings/');

			if ($dir)
			{
				while (($file = readdir($dir)) !== false)
				{
					if ((strpos($file, 'mod_') === 0) && (substr($file, -(strlen(PHP_EXT) + 1)) === '.' . PHP_EXT))
					{
						$mods_files[] = substr($file, 0, -(strlen(PHP_EXT) + 1));
					}
				}
				closedir($dir);
			}

			$this->put('_mods', $mods_files);
		}

		return $mods_files;
	}

	/**
	* Obtain lang files...
	*/
	function obtain_lang_files()
	{
		global $config;

		if (($lang_files = $this->get('_lang_' . $config['default_lang'])) === false)
		{
			$lang_files = array();

			// Now search for langs...
			$dir = @opendir(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/');

			if ($dir)
			{
				while (($file = readdir($dir)) !== false)
				{
					if ((strpos($file, 'lang_extend_') === 0) && (substr($file, -(strlen(PHP_EXT) + 1)) === '.' . PHP_EXT))
					{
						$lang_files[] = substr($file, 0, -(strlen(PHP_EXT) + 1));
					}
				}
				closedir($dir);
			}

			$this->put('_lang_' . $config['default_lang'], $lang_files);
		}

		return $lang_files;
	}

	/**
	* Obtain hooks...
	*/
	function obtain_hooks()
	{
		if (($hook_files = $this->get('_hooks')) === false)
		{
			$hook_files = array();

			// Now search for hooks...
			$dh = @opendir(IP_ROOT_PATH . 'includes/hooks/');

			if ($dh)
			{
				while (($file = readdir($dh)) !== false)
				{
					if ((strpos($file, 'hook_') === 0) && (substr($file, -(strlen(PHP_EXT) + 1)) === '.' . PHP_EXT))
					{
						$hook_files[] = substr($file, 0, -(strlen(PHP_EXT) + 1));
					}
				}
				closedir($dh);
			}

			$this->put('_hooks', $hook_files);
		}

		return $hook_files;
	}
}

?>