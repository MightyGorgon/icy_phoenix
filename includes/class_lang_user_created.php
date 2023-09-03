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

class lang_management
{

	/**
	* Construct
	*/
	function __construct()
	{

	}

	function get_lang($key)
	{
		global $lang;
		return ((!empty($key) && isset($lang[$key])) ? $lang[$key] : $key);
	}

	function get_countries()
	{
		// get all countries installed
		$countries = array();
		$dir = @opendir(IP_ROOT_PATH . 'language');
		while ($file = @readdir($dir))
		{
			if (preg_match('#^lang_#i', $file) && !is_file(IP_ROOT_PATH . 'language/' . $file) && !is_link(IP_ROOT_PATH . 'language/' . $file))
			{
				$filename = trim(str_replace('lang_', '', $file));
				$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
				$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
				$countries[$file] = ucfirst($displayname);
			}
		}
		@closedir($dir);
		@asort($countries);

		return $countries;
	}

	function get_packs()
	{
			global $countries;

		/* MG Lang DB - BEGIN */
		$skip_files = array(('lang_bbcode.' . PHP_EXT), ('lang_faq.' . PHP_EXT), ('lang_rules.' . PHP_EXT));
		/* MG Lang DB - END */

		// get all the extensions installed
		$packs = array();
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			$dir = @opendir(IP_ROOT_PATH . 'language/' . $country_dir);
			while ($file = @readdir($dir))
			{
				//if(preg_match("/^lang_.*?\." . PHP_EXT . "$/", $file))
				//if((preg_match("/^lang_user_created.*?\." . PHP_EXT . "$/", $file)) || (preg_match("/^lang_main_settings.*?\." . PHP_EXT . "$/", $file)))
				//if((preg_match("/^lang_user_created.*?\." . PHP_EXT . "$/", $file)) || (preg_match("/^lang_profile_fields.*?\." . PHP_EXT . "$/", $file)))
				if(preg_match("/^lang_user_created.*?\." . PHP_EXT . "$/", $file))
				{
					/* MG Lang DB - BEGIN */
					if (!in_array($file, $skip_files))
					/* MG Lang DB - END */
					{
						$displayname = $file;
						$packs[$file] = $displayname;
					}
				}
				/* MG Lang DB - BEGIN */
				/*
				if(preg_match("/^lang_extend_.*?\." . PHP_EXT . "$/", $file))
				{
					$displayname = trim(str_replace(('.' . PHP_EXT), '', str_replace('lang_extend_', '', $file)));
					$packs[$file] = $displayname;
				}
				*/
				/* MG Lang DB - END */
			}
			@closedir($dir);
		}
		/* MG Lang DB - BEGIN */
		/*
		$packs['lang'] = '_phpBB';
		$packs['custom'] = '_custom';
		*/
		/* MG Lang DB - END */
		@asort($packs);

		return $packs;
	}

	function read_one_pack($country_dir, $pack_file, &$entries)
	{
		global $countries, $packs;

		// get filename
		$file = IP_ROOT_PATH . 'language/' . $country_dir . '/' . $pack_file;
		if (($pack_file != 'lang') && ($pack_file != 'custom') && !file_exists($file))
		{
			die('This file doesn\'t exist: ' . $file);
			//echo('This file doesn\'t exist: ' . $file . '<br />');
		}

		// process first admin then standard keys
		for ($i = 0; $i < 2; $i++)
		{
			$lang_extend_admin = ($i == 0);

			/* MG Lang DB - BEGIN */
			/*
			// fix the filename for standard keys
			if ($pack_file == 'lang')
			{
				$file = IP_ROOT_PATH . 'language/' . $country_dir . '/' . ($lang_extend_admin ? 'lang_admin.' : 'lang_main.') . PHP_EXT;
			}
			// fix the filename for custom keys
			if ($pack_file == 'custom')
			{
				$file = IP_ROOT_PATH . 'language/' . $country_dir . '/' . 'lang_extend.' . PHP_EXT;
			}
			*/
			/* MG Lang DB - END */

			// process
			$lang = array();
			@include($file);
			@reset($lang);
			while (list($key_main, $data) = @each($lang))
			{
				$custom = ($pack_file == 'custom');
				$first = !is_array($data);
				while ((is_array($data) && (list($key_sub, $value) = @each($data))) || $first)
				{
					$first = false;
					if (!is_array($data))
					{
						$key_sub = '';
						$value = $data;
					}
					$pack = $pack_file;
					$original = '';
					if ($custom && isset($entries['pack'][$key_main][$key_sub]))
					{
						$pack = $entries['pack'][$key_main][$key_sub];
						$original = $entries['pack'][$key_main][$key_sub][$country_dir];
					}
					$entries['pack'][$key_main][$key_sub] = $pack;
					$entries['value'][$key_main][$key_sub][$country_dir] = $value;
					$entries['original'][$key_main][$key_sub][$country_dir] = $original;
					$entries['admin'][$key_main][$key_sub] = $lang_extend_admin;
					// status : 0 = original, 1 = modified, 2 = added
					$entries['status'][$key_main][$key_sub][$country_dir] = (!$custom ? 0 : (($pack != $pack_file) ? 1 : 2));
				}
			}
		}
	}

	function get_entries($modified = true)
	{
		global $config;
		global $countries, $packs;

		// init
		$entries = array();

		// process by countries first
		/* MG Lang DB - BEGIN */
		/*
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			// phpBB lang keys
			$pack_file = 'lang';
			$this->read_one_pack($country_dir, $pack_file, $entries);
		}

		// process other packs except custom one
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			@reset($packs);
			while (list($pack_file, $pack_name) = @each($packs))
			{
				if (($pack_file != 'lang') && ($pack_file != 'custom'))
				{
					$this->read_one_pack($country_dir, $pack_file, $entries);
				}
			}
		}
		*/
		/* MG Lang DB - END */

		/* MG Lang DB - BEGIN */
		@reset($countries);
		while (list($country_dir, $country_name) = @each($countries))
		{
			@reset($packs);
			while (list($pack_file, $pack_name) = @each($packs))
			{
				$this->read_one_pack($country_dir, $pack_file, $entries);
			}
		}
		/* MG Lang DB - END */

		// process the added/modified keys
		if ($modified)
		{
			/* MG Lang DB - BEGIN */
			/*
			@reset($countries);
			while (list($country_dir, $country_name) = @each($countries))
			{
				$pack_file = 'custom';
				$this->read_one_pack($country_dir, $pack_file, $entries);
			}
			*/
			/* MG Lang DB - END */

			// add the missing keys in a language
			$default_lang = 'lang_' . $config['default_lang'];
			$english_lang = 'lang_english';
			@reset($entries['pack']);
			while (list($key_main, $data) = @each($entries['pack']))
			{
				@reset($data);
				while (list($key_sub, $pack_file) = @each($data))
				{
					// add the key to the default lang if missing by using the english one
					if (!isset($entries['value'][$key_main][$key_sub][$default_lang]))
					{
						// add the key to english lang if missing
						if (!isset($entries['value'][$key_main][$key_sub][$english_lang]))
						{
							// find the first not empty value
							$found = false;
							$new_value = '';
							@reset($entries['value'][$key_main][$key_sub]);
							while (list($country_dir, $value) = @each($entries['value'][$key_main][$key_sub]))
							{
								$found = !empty($value);
								if ($found)
								{
									$new_value = $value;
								}
							}
							// add it (even if empty)
							$entries['value'][$key_main][$key_sub][$english_lang] = $new_value;
							$entries['status'][$key_main][$key_sub][$english_lang] = 2; // 2=added
						}

						// fill the default lang
						if ($default_lang!= $english_lang)
						{
							$entries['value'][$key_main][$key_sub][$default_lang] = $entries['value'][$key_main][$key_sub][$english_lang];
							$entries['status'][$key_main][$key_sub][$default_lang] = 2; // 2=added
						}
					}

					// process all langs for this key
					@reset($countries);
					while (list($country_dir, $country_name) = @each($countries))
					{
						if (!isset($entries['value'][$key_main][$key_sub][$country_dir]))
						{
							$entries['value'][$key_main][$key_sub][$country_dir] = $entries['value'][$key_main][$key_sub][$default_lang];
							$entries['status'][$key_main][$key_sub][$country_dir] = 2; // 2=added
						}
					}
				}
			}
		}

		// all is done : return the result
		return $entries;
	}

	function write($entries)
	{
		global $template, $user, $lang;
		global $countries, $packs;

		// read old values
		$old_entries = $this->get_entries(false);

		// regenerate each file (per country then per pack)
		@reset($countries);
		while (list($country_dir, $country_name) = each($countries))
		{
			// init
			$local = array();
			// read packs
			@reset($packs);
			while (list($pack_file, $pack_name) = @each($packs))
			{
				// read keys
				@reset($entries['admin']);
				while (list($key_main, $data) = @each($entries['admin']))
				{
					@reset($data);
					while (list($key_sub, $admin) = @each($data))
					{
						$std = !$entries['admin'][$key_main][$key_sub];
						$local[$std][$pack_name][$key_main][$key_sub] = $entries['value'][$key_main][$key_sub][$country_dir];
					}
				}
			}
			@ksort($local);

			$file_content = $this->empty_file_header();

			// read admin/standard type of key
			@reset($local);
			while (list($std, $pack_data) = @each($local))
			{
				$prefix = ($std ? 'normal' : 'admin');
				if ($prefix == 'admin')
				{
					$file_content .= 'if($lang_extend_admin)' . "\n";
					$file_content .= '{' . "\n";
				}
				// read pack
				@reset($pack_data);
				while (list($pack_file, $key_data) = @each($pack_data))
				{
					$pack_filename = $pack_file;
					/*
					$file_content .= '// Pack: ' . $pack_file . "\n";
					$file_content .= "\n";
					*/
					// read key main
					@reset($key_data);
					while (list($key_main, $sub_data) = @each($key_data))
					{
						// read key sub
						@reset($sub_data);
						while (list($key_sub, $value) = @each($sub_data))
						{
							$key_main = $this->clean_string($key_main);
							$key_sub = $this->clean_string($key_sub);
							$value = $this->clean_string($value);
							$n_sub = intval($key_sub);
							$file_content .= (($prefix == 'admin') ? "\t" : '');
							if (!empty($key_sub) || ($key_sub == "$n_sub"))
							{
								$file_content .= '$lang[\'' . $key_main . '\'][\'' . $key_sub . '\'] = \'' . $value . '\';' . "\n";
							}
							else
							{
								$file_content .= '$lang[\'' . $key_main . '\'] = \'' . $value . '\';' . "\n";
							}
						}
					}
				}
				if ($prefix == 'admin')
				{
					$file_content .= '}' . "\n";
					$file_content .= "\n";
				}
			}
			$file_content .= "\n";
			$file_content .= '?' . '>';

			$edittime = gmdate('Y-m-d H:i:s');

			$array_find = array(
				'$' . 'Id' . '$',
				'{COUNTRY_NAME}',
				'{EDITTIME}',
				'{USERNAME}',
			);

			$array_replace = array(
				'$' . 'Id: ' . $pack_filename . ' ' . $edittime . ' ' . $user->data['username'] . ' $',
				$country_name,
				$edittime,
				$user->data['username'],
			);

			$file_content = str_replace($array_find, $array_replace, $file_content);

			$filename = IP_ROOT_PATH . 'language/' . $country_dir . '/' . $pack_filename;
			$this->write_file($filename, $file_content);
		}
	}

	function clean_string($string)
	{
		$array_find = array(
			"''",
			"'",
			"\r\n",
		);

		$array_replace = array(
			"'",
			"\'",
			"\n",
		);

		$string = str_replace($array_find, $array_replace, stripslashes($string));
		return $string;
	}

	function write_file($filename, $content)
	{
		@chmod($filename, 0666);
		@unlink($filename);
		$f = @fopen($filename, 'w');
		@fwrite($f, $content);
		@ftruncate($f);
		@fclose($f);
		@chmod($filename, 0666);
	}

	function empty_file_header()
	{
		$file_content = '';
		$file_content .= '<' . '?' . 'php' . "\n";
		$file_content .= '/**' . "\n";
		$file_content .= '*' . "\n";
		$file_content .= '* @package Icy Phoenix' . "\n";
		$file_content .= '* @version $' . 'Id' . '$' . "\n";
		$file_content .= '* @copyright (c) 2008 Icy Phoenix' . "\n";
		$file_content .= '* @license http://opensource.org/licenses/gpl-license.php GNU Public License' . "\n";
		$file_content .= '*' . "\n";
		$file_content .= '*/' . "\n";
		$file_content .= '' . "\n";
		$file_content .= '/**' . "\n";
		$file_content .= '*' . "\n";
		$file_content .= '* @Extra credits for this file' . "\n";
		$file_content .= '* Ptirhiik (admin@rpgnet-fr.com)' . "\n";
		$file_content .= '*' . "\n";
		$file_content .= '*/' . "\n";
		$file_content .= '' . "\n";
		$file_content .= '/**' . "\n";
		$file_content .= '*' . "\n";
		$file_content .= '* [{COUNTRY_NAME}]' . "\n";
		$file_content .= '* LAST UPDATE {EDITTIME} by {USERNAME}' . "\n";
		$file_content .= '*' . "\n";
		$file_content .= '*/' . "\n";
		$file_content .= '' . "\n";
		$file_content .= 'if (!defined(\'IN_ICYPHOENIX\'))' . "\n";
		$file_content .= '{' . "\n";
		$file_content .= '' . "\t" . 'die(\'Hacking attempt\');' . "\n";
		$file_content .= '}' . "\n";
		$file_content .= '' . "\n";
		return $file_content;
	}
}

?>