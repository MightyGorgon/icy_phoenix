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

abstract class SocialConnect
{
	private static $social_networks = array("facebook", "google");
	private static $available_networks = array();

	private $network_name;
	private $network_name_clean;

	public function __construct($network_name)
	{
		global $lang, $redirect;
		$this->network_name = empty($lang[strtoupper($network_name)]) ? $network_name : $lang[strtoupper($network_name)];
		$this->network_name_clean = $network_name;
	}

	public static function get_available_networks()
	{
		global $config;

		if (empty(self::$available_networks))
		{
			foreach (self::$social_networks as $network_name)
			{
				if (in_array($network_name, self::$social_networks) && !empty($config['enable_' . $network_name . '_login']))
				{
					include(IP_ROOT_PATH . 'includes/social_connect/class_' . $network_name . '_connect.' . PHP_EXT);
					$class_name = strtoupper(substr($network_name, 0, 1)) . substr($network_name, 1) . 'Connect';
					$network = new $class_name($network_name);
					self::$available_networks[$network_name] = $network;
				}
			}
		}
		return self::$available_networks;
	}

	public function get_name()
	{
		return $this->network_name;
	}

	public function get_name_clean()
	{
		return $this->network_name_clean;
	}

	/**
	* Override this function if your authentication provider
	*  can't handle passing a $redirect with query parameters (Google can't)
	*
	* In this function, you'll be able to input fake values into $_GET and $_POST
	*  to mimic what an user would fill to register (includes "I agree to terms" and other variables).
	*/
	public function shim_register_request()
	{
	}

	public abstract function do_login($redirect, $force_retry = false);
	public abstract function get_user_data();

	protected function get_redirect_url($redirect = '', $only_php = false)
	{
		// Build the social network return url
		$current_page = extract_current_page(IP_ROOT_PATH);
		$return_url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://';
		$return_url .= extract_current_hostname() . $current_page['script_path'] . $current_page['page'];
		if ($only_php)
		{
			// trim query parameters
			$return_url = explode('?', $return_url);
			return $return_url[0];
		}
		$return_url .= (strpos($return_url, '?') ? '&' : '?') . 'redirect=' . $redirect . '&confirm=1';
		$return_url .= (!empty($_GET['admin'])) ? '&admin=1' : '';
		return $return_url;
	}
}

?>