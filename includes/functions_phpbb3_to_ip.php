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

/**
* Alias for auth class
*/
class auth
{
	var $acl = array();
	var $cache = array();
	var $acl_options = array();
	var $acl_forum_ids = false;

	/**
	* ACL
	*/
	function acl(&$user_data)
	{

		return true;
	}

	/**
	* ACL GET
	*/
	function acl_get($opt, $f = 0)
	{
		global $user;
		$return_value = true;

		if (substr($opt, 0, 2) === 'a_')
		{
			$return_value = (($user->data['user_level'] == ADMIN) ? true : false);
		}
		elseif ((substr($opt, 0, 2) === 'm_') || (substr($opt, 0, 2) === 'f_'))
		{
			$return_value = ((($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD)) ? true : false);
		}

		return $return_value;
	}

}

/**
* Generate board url (example: http://www.example.com/phpBB)
* @param bool $without_script_path if set to true the script path gets not appended (example: http://www.example.com)
*/
function generate_board_url($without_script_path = false)
{
	return create_server_url($without_script_path);
}

/**
* BBCodes
*/

/**
* nl2br
*/
function bbcode_nl2br($message)
{

	return $message;
}

/**
* Smileys
*/
function smiley_text($message)
{

	return $message;
}

if (empty($bbcode) || !class_exists('bbcode'))
{
	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
}

class phpbb3_bbcode extends bbcode
{

	/**
	* Smileys
	*/
	function bbcode_second_pass($message, $bbcode_uid, $bbcode_bitfield)
	{
		global $config, $user;

		$this->allow_html = ($user->data['user_allowhtml'] && $config['allow_html']) ? true : false;
		$this->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
		$this->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
		$message = $this->parse($message);

		return $message;
	}

}

// Initialazing vars and classes...
define('IN_PHPBB', true);
$phpbb_root_path = IP_ROOT_PATH;
$phpEx = PHP_EXT;

$user = new user();
$auth = new auth();
unset($bbcode);
$bbcode = new phpbb3_bbcode();

?>