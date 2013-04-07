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
* Xore (mods@xore.ca)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

if (!defined('CASH_INCLUDE'))
{
	define('CASH_INCLUDE', true);
	//
	//=============[ BEGIN Defines ]=========================
	//

	// Cash Mod Constants
	define('PERCHAR_DEC_BONUS',3);
	define('CASH_LOG_ACTION_DELIMITER','@-@');

	define('CASH_EVENT_DELIM1','#');
	define('CASH_EVENT_DELIM2','@');

	// Log Settings - the order here matters. don't change it. only add to the end of the list.
	$i = 0;
	define('CASH_LOG_DONATE', $i++);
	define('CASH_LOG_ADMIN_MODEDIT', $i++);
	define('CASH_LOG_ADMIN_CREATE_CURRENCY', $i++);
	define('CASH_LOG_ADMIN_DELETE_CURRENCY', $i++);
	define('CASH_LOG_ADMIN_RENAME_CURRENCY', $i++);
	define('CASH_LOG_ADMIN_COPY_CURRENCY',$i++);
	// insert new log types before this line

	// Allowance Time
	define('CASH_ALLOW_DAY', 1);
	define('CASH_ALLOW_WEEK', 2);
	define('CASH_ALLOW_MONTH', 3);
	define('CASH_ALLOW_YEAR', 4);

	// Cash groups
	define('CASH_GROUPS_LEVEL', 1);
	define('CASH_GROUPS_RANK', 2);
	define('CASH_GROUPS_USERGROUP', 3);
	// Cash groups status
	define('CASH_GROUPS_DEFAULT', 1);
	define('CASH_GROUPS_CUSTOM', 2);
	define('CASH_GROUPS_OFF', 3);

	// Bitmask filters - the order here matters. don't change it. only add to the end of the list.
	$i = 0;
	define('CURRENCY_ENABLED', 1 << $i++);
	define('CURRENCY_IMAGE', 1 << $i++);
	define('CURRENCY_PREFIX', 1 << $i++);
	define('CURRENCY_INCLUDEQUOTES', 1 << $i++);
	define('CURRENCY_VIEWPROFILE', 1 << $i++);
	define('CURRENCY_VIEWTOPIC', 1 << $i++);
	define('CURRENCY_VIEWMEMBERLIST', 1 << $i++);
	define('CURRENCY_DONATE', 1 << $i++);
	define('CURRENCY_MODEDIT', 1 << $i++);
	define('CURRENCY_ALLOWNEG', 1 << $i++);
	define('CURRENCY_FORUMLISTTYPE', 1 << $i++);
	define('CURRENCY_EXCHANGEABLE', 1 << $i++);
	// insert new bitmasks before this line

	// Cash tables
	define('CASH_TABLE', $table_prefix . 'cash');
	define('CASH_EVENTS_TABLE', $table_prefix . 'cash_events');
	define('CASH_EXCHANGE_TABLE', $table_prefix . 'cash_exchange');
	define('CASH_GROUPS_TABLE', $table_prefix . 'cash_groups');
	define('CASH_LOGS_TABLE', $table_prefix . 'cash_log');

	//
	//=============[ END Defines ]=========================
	//
}

// Previous versions, this was higher up, but for CASH EVENTS, CASH_EVENTS_TABLE needs to be defined, this was causing errors with people who had tried to get them working.
if (defined('CM_EVENT') || defined('CM_MEMBERLIST') || defined('CM_VIEWTOPIC') || defined('CM_VIEWPROFILE') || defined('CM_VIEWPROFILE') || defined('CM_POSTING'))
{
	include(IP_ROOT_PATH . 'includes/classes_cash.' . PHP_EXT);
}

if (defined('CASH_INCLUDE2'))
{
	return;
}

define('CASH_INCLUDE2', true);
//
//=============[ BEGIN Minifunctions ]=========================
//

function preversion($ver)
{
	$version = explode('.',phpversion());
	$ver = explode('.',$ver);
	for($i = 0; $i < sizeof($ver); $i++)
	{
		if (intval($version[$i]) < intval($ver[$i]))
		{
			return true;
		}
		if ($i == 2)
		{
			return false;
		}
	}
	return false;
}

//quickswap
function qs(&$int1, &$int2)
{
	$int3 = $int2;
	$int2 = $int1;
	$int1 = $int3;
}

// yay for the euclidean algorithm!
function gcd($int1, $int2)
{
	$int1 = (intval($int1) >= 0) ? intval($int1):intval($int1 * -1);
	$int2 = (intval($int2) >= 0) ? intval($int2):intval($int2 * -1);
	if ($int1 < $int2)
	{
		qs($int1,$int2);
	}
	while ($int1 > $int2)
	{
		$factor = intval($int1/$int2);
		$int1 -= ($factor * $int2);
		if ($int1 > 0)
		{
			qs($int1, $int2);
		}
	}
	return $int2;
}

function quoteslash($text,$quotes)
{
	if (is_array($quotes))
	{
		for ($i = 0; $i < sizeof($quotes); $i++)
		{
			if ($quotes[$i] == '\'')
			{
				$text = str_replace('\'','&#039;',$text);
			}
			else if ($quotes[$i] == '"')
			{
				$text = str_replace('"','&quot;',$text);
			}
			else if ($quotes[$i] != '')
			{
				$text = str_replace($quotes[$i],'\\' . $quotes[$i],$text);
			}
		}

	}
	else
	{
		if ($quotes == '\'')
		{
			$text = str_replace('\'','&#039;',$text);
		}
		else if ($quotes == '"')
		{
			$text = str_replace('"','&quot;',$text);
		}
		else if ($quotes != '')
		{
			$text = str_replace($quotes,'\\' . $quotes,$text);
		}
	}
	return $text;
}

//
//=============[ END Minifunctions ]=========================
//

//
//=============[ BEGIN Back-Compatibility Wrappers ]=========================
//

function cash_floatval($value)
{
	if (preversion('4.3'))
	{
		return (float)$value;
	}
	else
	{
		return floatval($value);
	}
}

function cash_array_merge($array1, $array2)
{
	if (preversion('4.3'))
	{
		while (list($k,$v) = each ($array2))
		{
			if(is_int($k) && isset($array1[$k]))
			{
				$array1[] = $v;
			}
			else
			{
				$array1[$k] = $v;
			}
		}
		reset ($array2);
		return $array1;
	}
	else
	{
		return array_merge($array1,$array2);
	}
}

function cash_array_chunk(&$array, $chunk_size)
{
	$return_array = array();
	if (preversion('4.3'))
	{
		if (preversion('4.0'))
		{
			for ($i = 0; $i < sizeof($array); $i++)
			{
				$return_array[floor($i/$chunk_size)][] = $array[$i];
			}
			return $return_array;
		}
		else
		{
			for ($i = 0; $i < ceil(sizeof($array) / $chunk_size); $i++)
			{
				$return_array[] = array_slice($array,($i*$chunk_size),$chunk_size);
			}
			return $return_array;
		}
	}
	else
	{
		return array_chunk($array,$chunk_size);
	}
}

//
//=============[ END Back-Compatibility Wrappers ]=========================
//

//
//=============[ BEGIN Cash Functions ]=========================
//

function cash_create_log($type, $action, $message = '')
{
	global $db;
	$action = implode(CASH_LOG_ACTION_DELIMITER,$action);
	$current_time = time();
	$sql = "INSERT INTO " . CASH_LOGS_TABLE . "
			(log_time, log_type, log_action, log_text)
			VALUES(" . $current_time . ", " . $type . ", '" . $db->sql_escape($action) . "', '" . $db->sql_escape($message) . "')";
	$db->sql_query($sql);
}

function cash_clause($clause, $action)
{
	$text = '';
	if (preversion('4.0.5'))
	{
		// needs to survive the eval();
		$clause = quoteslash($clause, '"');
		$action = quoteslash($action, '"');
		$action = explode(CASH_LOG_ACTION_DELIMITER, $action);
		eval('$text = sprintf("' . $clause . '","' . implode('","', $action) . '");');
	}
	else
	{
		$action = explode(CASH_LOG_ACTION_DELIMITER,$action);
		$text = call_user_func_array('sprintf',cash_array_merge(array($clause),$action));
	}
	$text = str_replace('&quot;','"',$text);
	return $text;
}

function cash_event_unpack($string)
{
	$cash_amounts = array();
	if (strlen($string))
	{
		$cash_entries = explode(CASH_EVENT_DELIM1,$string);
		for ($i = 0; $i < sizeof($cash_entries); $i++)
		{
			if (strlen($cash_entries[$i]))
			{
				$temp = explode(CASH_EVENT_DELIM2,$cash_entries[$i]);
				if (isset($temp[0]) && isset($temp[1]))
				{
					$cash_amounts[intval($temp[0])] = cash_floatval($temp[1]);
				}
			}
		}
	}
	return $cash_amounts;
}

function cash_quotematch(&$message)
{
	$length = strlen($message);
	$starttag = '[quote';
	$startarray = array();
	$endtag = '[/quote]';
	$endarray = array();
	$locater = 0;
	$current_position = strpos($message, $starttag, $locater);
	while ($current_position !== false)
	{
		$startarray[] = $current_position;
		$locater = $current_position + strlen($starttag);
		$current_position = strpos($message, $starttag, $locater);
	}
	$startarray[] = $length + 10;
	$locater = 0;
	$current_position = strpos($message, $endtag, $locater);
	while ($current_position !== false)
	{
		$endarray[] = $current_position + strlen($endtag);
		$locater = $current_position + strlen($endtag);
		$current_position = strpos($message, $endtag, $locater);
	}
	$endarray[] = $length + 10;
	if (sizeof($startarray) > 1)
	{
		$start = 0;
		$end = 0;
		$stack = 0;
		$startpos = 0;
		$endpos = 0;
		for ($i = 0; $i < (sizeof($startarray) + sizeof($endarray) - 2); $i++)
		{
			if ($stack == 0)
			{
				$startpos = $startarray[$start];
				$start++;
				$stack++;
			}
			else
			{
				if ($startarray[$start] < $endarray[$end])
				{
					$start++;
					$stack++;
				}
				else
				{
					$endpos = $endarray[$end];
					$end++;
					$stack--;
				}
				if ($stack == 0)
				{
					$difference = $endpos - $startpos;
					$length -= $difference;
				}
			}
		}
	}
	return $length;
}

function cash_pm(&$targetdata, $privmsg_subject, &$message)
{
	global $db, $cache, $config, $user, $lang, $bbcode, $html_entities_match, $html_entities_replace;
	//
	// It looks like we're sending a PM!
	// NOTE: most of the following code is shamelessly "reproduced" from privmsg.php
	//

	include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);
	include_once(IP_ROOT_PATH . 'includes/functions_post.' . PHP_EXT);

	$attach_sig = $user->data['user_attachsig'];
	$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html']) ? true : false;
	$bbcode->allow_bbcode = true;
	$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;
	$html_status = $bbcode->allow_html;
	$bbcode_status = $bbcode->allow_bbcode;
	$smilies_status = $bbcode->allow_smilies;
	$acro_auto_status = false;

	include_once(IP_ROOT_PATH . 'includes/class_pm.' . PHP_EXT);
	$privmsg_message = prepare_message($message, $html_status, $bbcode_status, $smilies_status);
	$privmsg_sender = $user->data['user_id'];
	$privmsg_recipient = $targetdata['user_id'];

	$privmsg = new class_pm();
	if (($user->data['user_level'] != ADMIN) && $privmsg->is_flood())
	{
		message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
	}
	$privmsg->delete_older_message('PM_INBOX', $privmsg_recipient);
	$privmsg->send($privmsg_sender, $privmsg_recipient, $privmsg_subject, $privmsg_message, $attach_sig, $html_status, $bbcode_status, $smilies_status, $acro_auto_status);

	if ($targetdata['user_notify_pm'] && !empty($targetdata['user_email']) && $targetdata['user_active'])
	{
		//HTML Message
		$clean_tags = $config['html_email'] ? false : true;
		$bbcode->allow_bbcode = ($config['allow_bbcode'] ? $config['allow_bbcode'] : false);
		$bbcode->allow_html = ($config['allow_html'] ? $config['allow_html'] : false);
		$bbcode->allow_smilies = ($config['allow_smilies'] ? $config['allow_smilies'] : false);
		$message = $bbcode->parse($privmsg_message, '', false, $clean_tags);
		$message = stripslashes($message);
		//HTML Message

		$privmsg->notification($privmsg_sender, $privmsg_recipient, $targetdata['user_email'], $lang['Notification_subject'], $message, false, $privmsg_subject, $targetdata['username'], $targetdata['user_lang'], false);
	}
	unset($privmsg);
}

//
//=============[ BEGIN Cash Classes ]=========================
//

// Menu Category
class cash_menucat
{
	var $category;
	var $items;
	function cash_menucat($category)
	{
		$this->category = $category;
	}
	function additem($menuitem)
	{
		$this->items[] = $menuitem;
	}
	function num()
	{
		return sizeof($this->items);
	}
}

// Menu Item (url does NOT include any extension... this is appended automatically)
class cash_menuitem
{
	var $title;
	var $url;
	var $desc;
	var $id;

	function cash_menuitem(&$id, $title,$url,$desc)
	{
		global $lang;
		$this->url = $url . '%s';
		$this->desc = $desc;
		if ($id < 10)
		{
			$this->id = '0' . $id;
		}
		$id++;
		if (isset($lang[$title]))
		{
			$lang[$this->id . $title] = $lang[$title];
		}
		else
		{
			$lang[$this->id . $title] = preg_replace("/_/", "&nbsp;", $title);
		}
		$this->title = $this->id . $title;
	}

	function data($class, $target, $append_sid = true)
	{
		return array(
			'L_TITLE' => $this->name(),
			'U_ADMIN' => $this->linkage($append_sid),
			'L_DESCRIPTION' => $this->desc,
			'CLASS' => sprintf("row%s", $class),
			'S_TARGET' => $target
		);
	}

	function linkage($append_sid = true)
	{
		if (!$append_sid)
		{
			return sprintf($this->url, '.' . PHP_EXT);
		}
		else
		{
			return append_sid(sprintf($this->url, '.' . PHP_EXT));
		}
	}

	function name()
	{
		global $lang;
		return (isset($lang[$this->title]) ? $lang[$this->title] : preg_replace("/_/", "&nbsp;", $this->title));
	}
}

// Cash table and functions.
class cash_table
{
	var $currencies;
	var $ordered_list;
	var $id_list;

	function cash_table()
	{
		global $db;
		$this->currencies = array();
		$this->ordered_list = array();
		$this->id_list = array();

		$sql = "SELECT * FROM " . CASH_TABLE . " ORDER BY cash_order ASC";
		$result = $db->sql_query($sql, 0, 'cash_');

		while ($row = $db->sql_fetchrow($result))
		{
			$cash_id = $row['cash_id'];
			$this->ordered_list[] = $cash_id;
			$this->id_list[$cash_id] = true;
			$this->currencies[$cash_id] = new cash_currency($row);
		}
	}

	function refresh_table()
	{
		global $db;
		$this->currencies = array();
		$this->ordered_list = array();
		$this->id_list = array();
		$this->count_cache = array();

		$sql = "SELECT * FROM " . CASH_TABLE . " ORDER BY cash_order ASC";
		$result = $db->sql_query($sql, 0, 'cash_');

		while ($row = $db->sql_fetchrow($result))
		{
			$cash_id = $row['cash_id'];
			$this->ordered_list[] = $cash_id;
			$this->id_list[$cash_id] = true;
			$this->currencies[$cash_id] = new cash_currency($row);
		}
	}

	function reorder()
	{
		global $db;
		$bad_ordering = false;
		$sql = array();
		for ($i = 0; $i < sizeof($this->ordered_list); $i++)
		{
			if ($this->currencies[$this->ordered_list[$i]]->data('cash_order') != ($i + 1))
			{
				$sql[] = "UPDATE " . CASH_TABLE . " SET cash_order = " . ($i + 1) . " WHERE cash_id = " . $this->ordered_list[$i];
				$bad_ordering = true;
			}
		}
		if ($bad_ordering)
		{
			for ($i = 0; $i < sizeof($sql); $i++)
			{
				$db->sql_query($sql[$i]);
			}
			$this->refresh_table();
			$db->clear_cache('cash_');
		}
	}

	function currency_exists($cash_id)
	{
		if (isset($this->id_list[$cash_id]))
		{
			return true;
		}
		return false;
	}

	function &currency_next(&$iterater, $mask = false,$forum_id = false)
	{
		if (empty($iterater))
		{
			$iterater = 0;
		}
		if (!($iterater < sizeof($this->ordered_list)))
		{
			$iterater = 0;
			return false;
		}
		$cash_id = $this->ordered_list[$iterater];

		if (!$mask)
		{
			$iterater++;
			return $this->currencies[$cash_id];
		}

		while (!$this->currencies[$cash_id]->mask($mask,$forum_id))
		{
			$iterater++;
			if (!($iterater < sizeof($this->ordered_list)))
			{
				$iterater = 0;
				return false;
			}
			$cash_id = $this->ordered_list[$iterater];
		}
		$iterater++;
		return $this->currencies[$cash_id];
	}

	function currency_count($mask = false,$forum_id = false)
	{
		if (!$mask)
		{
			return sizeof($this->ordered_list);
		}
		$count = 0;
		$i = 0;

		while ($i < sizeof($this->ordered_list))
		{
			$cash_id = $this->ordered_list[$i];
			if ($this->currencies[$cash_id]->mask($mask,$forum_id))
			{
				$count++;
			}
			$i++;
		}
		return $count;
	}

	function currency($cash_id)
	{
		if (!$this->currency_exists($cash_id))
		{
			return false;
		}
		return $this->currencies[$cash_id];
	}
}

class cash_currency
{
	var $currency;
	var $forumlist;

	function cash_currency(&$data, $is_group = false)
	{
		$this->currency = $data;
		if (!$is_group)
		{
			$this->forumlist = array();
			if (strlen($data['cash_forumlist']))
			{
				$templist = explode(',',$data['cash_forumlist']);
				for ($i = 0; $i < sizeof($templist); $i++)
				{
					$this->forumlist[$templist[$i]] = 1;
				}
			}
		}
	}

	function data($identifier)
	{
		return $this->currency[$identifier];
	}

	function id()
	{
		return $this->currency['cash_id'];
	}

	function name($surpress_image = false, $quotes = false)
	{
				if ($this->mask(CURRENCY_IMAGE) && !$surpress_image)
		{
			return '<img src="' . IP_ROOT_PATH . $this->currency['cash_imageurl'] . '" alt="' . quoteslash($this->currency['cash_name'],'"') . '" />';
		}
		else
		{
			return ((!$quotes) ? $this->currency['cash_name'] : quoteslash($this->currency['cash_name'],$quotes));
		}
	}

	function db()
	{
		return $this->currency['cash_dbfield'];
	}

	function display($amount, $surpress_image = false, $quotes = false)
	{

		if($this->mask(CURRENCY_PREFIX))
		{
			return ($this->name($surpress_image, $quotes) . ' ' . $amount);
		}
		else
		{
			return ($amount . ' ' . $this->name($surpress_image, $quotes));
		}
	}

	function mask($bitmask = false, $forum_id = false)
	{
		if (!$bitmask)
		{
			return true;
		}
		if ((($bitmask & $this->currency['cash_settings']) == $bitmask) && (!$forum_id || $this->forum_active($forum_id)))
		{
			return true;
		}
		return false;
	}

	function exchange()
	{
		return $this->currency['cash_exchange']/$this->factor();
	}

	function base($newthread = false)
	{
		return (($newthread) ? $this->perpost() : $this->perreply());
	}

	function perpost()
	{
		return $this->currency['cash_perpost']/$this->factor();
	}

	function postbonus()
	{
		return $this->currency['cash_postbonus']/$this->factor();
	}

	function perreply()
	{
		return $this->currency['cash_perreply']/$this->factor();
	}

	function perchar()
	{
		return $this->currency['cash_perchar']/$this->factor(PERCHAR_DEC_BONUS);
	}

	function perthanks()
	{
		return $this->currency['cash_perthanks']/$this->factor();
	}

	function maxearn()
	{
		return $this->currency['cash_maxearn']/$this->factor();
	}

	function perpm()
	{
		return $this->currency['cash_perpm']/$this->factor();
	}

	function allowanceamount()
	{
		return $this->currency['cash_allowanceamount']/$this->factor();
	}

	function factor($modifier = false)
	{
		$exp = 0;
		if (!$modifier)
		{
			$exp = intval($this->currency['cash_decimals']);
		}
		else
		{
			$exp = intval($this->currency['cash_decimals']) + $modifier;
		}
		return intval(pow(10,intval(max(0,$exp))));
	}

	function attribute_pack($key,$value)
	{
		$keys = array('cash_exchange'=>1,'cash_perpost'=>1,'cash_postbonus'=>1,'cash_perreply'=>1,'cash_perchar'=>1,'cash_maxearn'=>1,'cash_perpm'=>1,'cash_allowanceamount'=>1);
		if (isset($keys[$key]))
		{
			if ($key == 'cash_perchar')
			{
				return intval($value * $this->factor(PERCHAR_DEC_BONUS));
			}
			else
			{
				return intval($value * $this->factor());
			}
		}
		else
		{
			return $value;
		}
	}

	function forum_active($forum_id)
	{
		return ((isset($this->forumlist[$forum_id])) ? (!$this->mask(CURRENCY_FORUMLISTTYPE)) : ($this->mask(CURRENCY_FORUMLISTTYPE)));
	}
}

class cash_groups
{
	var $groups;
	var $groups_ordered_list;
	var $loaded;
	var $ranks;

	function cash_groups()
	{
		$this->loaded = false;
		$this->groups = array(CASH_GROUPS_LEVEL => array(),CASH_GROUPS_RANK => array(),CASH_GROUPS_USERGROUP => array());
		$this->groups_ordered_list = array(CASH_GROUPS_LEVEL => array(),CASH_GROUPS_RANK => array(),CASH_GROUPS_USERGROUP => array());
		$this->ranks = array();
	}

	function load($force_reload = false, $all_entries = false)
	{
		global $db, $cash;
		if (!$force_reload && $this->loaded)
		{
			return;
		}
		$currencies_array = array();
		$mask = false;
		if (!$all_entries)
		{
			while ($c_cur = &$cash->currency_next($cm_i,CURRENCY_ENABLED))
			{
				$currencies_array[] = $c_cur->id();
			}
		}
		if (sizeof($currencies_array) || $all_entries)
		{
			if (!$all_entries)
			{
				$sql = "SELECT *
						FROM " . CASH_GROUPS_TABLE . "
						WHERE cash_id = " . implode(' OR cash_id = ',$currencies_array);
			}
			else
			{
				$sql = "SELECT * FROM " . CASH_GROUPS_TABLE;
			}
			$result = $db->sql_query($sql, 0, 'cash_');

			while ($row = $db->sql_fetchrow($result))
			{
				$type = $row['group_type'];
				$group = $row['group_id'];
				if (!isset($this->groups[$type][$group]))
				{
					$this->groups[$type][$group] = array();
					$this->groups_ordered_list[$type][] = $group;
				}
				$this->groups[$type][$group][] = new cash_group($row);
			}
		}
		$sql = "SELECT *
				FROM " . RANKS_TABLE . "
				WHERE rank_special = 0
				ORDER BY rank_min ASC";
		$result = $db->sql_query($sql, 0, 'ranks_');

		while ($row = $db->sql_fetchrow($result))
		{
			$this->ranks[] = $row;
		}
		$this->loaded = true;
	}

	function get_groups(&$returnarray,$level,$usergroups,$postcount)
	{
		if (isset($this->groups[CASH_GROUPS_LEVEL][$level]))
		{
			for ($j = 0; $j < sizeof($this->groups[CASH_GROUPS_LEVEL][$level]); $j++)
			{
				$returnarray[$this->groups[CASH_GROUPS_LEVEL][$level][$j]->id()][] = &$this->groups[CASH_GROUPS_LEVEL][$level][$j];
			}
		}
		for ($i = 0; $i < sizeof($this->ranks); $i++)
		{
			if (isset($this->groups[CASH_GROUPS_RANK][$this->ranks[$i]['rank_id']]) && ($this->ranks[$i]['rank_min'] <= $postcount))
			{
				for ($j = 0; $j < sizeof($this->groups[CASH_GROUPS_RANK][$this->ranks[$i]['rank_id']]); $j++)
				{
					$returnarray[$this->groups[CASH_GROUPS_RANK][$this->ranks[$i]['rank_id']][$j]->id()][] = &$this->groups[CASH_GROUPS_RANK][$this->ranks[$i]['rank_id']][$j];
				}
			}
		}
		for ($i = 0; $i < sizeof($usergroups); $i++)
		{
			if (isset($this->groups[CASH_GROUPS_USERGROUP][$usergroups[$i]]))
			{
				for ($j = 0; $j < sizeof($this->groups[CASH_GROUPS_USERGROUP][$usergroups[$i]]); $j++)
				{
					$returnarray[$this->groups[CASH_GROUPS_USERGROUP][$usergroups[$i]][$j]->id()][] = &$this->groups[CASH_GROUPS_USERGROUP][$usergroups[$i]][$j];
				}
			}
		}
	}

	function populate(&$returnarray,$type,$id)
	{
		if (isset($this->groups[$type]) && is_array($this->groups[$type]) && isset($this->groups[$type][$id]) && is_array($this->groups[$type][$id]))
		{
			for ($i = 0; $i < sizeof($this->groups[$type][$id]); $i++)
			{
				$returnarray[$this->groups[$type][$id][$i]->id()] = &$this->groups[$type][$id][$i];
				$this->groups[$type][$id][$i]->load();
			}
		}
	}

	function cleanup()
	{
		global $db;
		$clause_1 = array();
		$types = array(CASH_GROUPS_LEVEL, CASH_GROUPS_RANK, CASH_GROUPS_USERGROUP);
		for ($pre_i = 0; $pre_i < sizeof($types); $pre_i++)
		{
			$clause_2 = array();
			$i = $types[$pre_i];
			for ($pre_j = 0; $pre_j < sizeof($this->groups_ordered_list[$i]); $pre_j++)
			{
				$clause_3 = array();
				$j = $this->groups_ordered_list[$i][$pre_j];
				for ($pre_k = 0; $pre_k < sizeof($this->groups[$i][$j]); $pre_k++)
				{
					if(!$this->groups[$i][$j][$pre_k]->is_loaded())
					{
						$k = $this->groups[$i][$j][$pre_k]->id();
						$clause_3[] = 'cash_id = ' . $k;
					}
				}
				if (sizeof($clause_3))
				{
					if (sizeof($clause_3) == sizeof($this->groups[$i][$j]))
					{
						$clause_2[] = 'group_id = ' . $j;
					}
					elseif (sizeof($clause_3) == 1)
					{
						$clause_2[] = 'group_id = ' . $j . ' AND ' . $clause_3[0];
					}
					else
					{
						$clause_2[] = 'group_id = ' . $j . ' AND (' . implode(' OR ',$clause_3) . ')';
					}
				}
			}
			if (sizeof($clause_2))
			{
				if (sizeof($clause_2) == 1)
				{
					$clause_1[] = 'group_type = ' . $i . ' AND ' . $clause_2[0];
				}
				else
				{
					$clause_1[] = 'group_type = ' . $i . ' AND ((' . implode(') OR (',$clause_2) . '))';
				}
			}
		}
		if (sizeof($clause_1))
		{
			$whereclause = '';
			if (sizeof($clause_1) == 1)
			{
				$whereclause = $clause_1[0];
			}
			else
			{
				$whereclause = '(' . implode(') OR (',$clause_1) . ')';
			}
			$sql = "DELETE FROM " . CASH_GROUPS_TABLE . "
					WHERE " . $whereclause;
			$db->sql_query($sql);
		}
	}
}

class cash_forumgroup
{
	var $group_type;
	var $group_id;
	var $group_name;
	var $group_description;
	var $currency_settings;

	function cash_forumgroup($group_type,$group_id,$group_name,$group_description)
	{
		$this->group_type = $group_type;
		$this->group_id = $group_id;
		$this->group_name = $group_name;
		$this->group_description = $group_description;
		$this->currency_settings = array();
	}

	function load()
	{
		global $cm_groups;
		$cm_groups->populate($this->currency_settings,$this->group_type,$this->group_id);
	}

	function has_entries()
	{
		return (sizeof($this->currency_settings) > 0);
	}
}


class cash_group extends cash_currency
{
	var $group_type;
	var $group_id;
	var $cash_id;
	var $loaded;

	function cash_group($rows)
	{
		$this->cash_currency($rows,true);
		$this->group_type = $rows['group_type'];
		$this->group_id = $rows['group_id'];
		$this->cash_id = $rows['cash_id'];
	}

	function name($surpress_image = false,$quotes = false)
	{
		global $cash;
		return $cash->currencies[$this->cash_id]->name($surpress_image,$quotes);
	}

	function factor($modifier = false)
	{
		global $cash;
		return $cash->currencies[$this->cash_id]->factor($modifier);
	}

	function is_loaded()
	{
		return $this->loaded;
	}

	function load()
	{
		$this->loaded = true;
	}
}

class cash_user
{
	var $user_id;
	var $userdata;
	var $userdata_stored;
	var $usergroups;
	var $cashgroups;
	var $cashgroups_init;

	function cash_user($user_id, $user_data = false)
	{
		$this->user_id = $user_id;
		if ($user_data)
		{
			$this->userdata = $user_data;
			$this->userdata_stored = true;
		}
		else
		{
			$this->userdata_stored = false;
		}
		$this->usergroups = array();
		$this->cashgroups = array();
		$this->cashgroups_init = false;
	}

	function get_userdata()
	{
		if (!$this->userdata_stored)
		{
			$this->userdata = get_userdata($this->user_id);
			$this->userdata_stored = true;
		}
	}

	function id()
	{
		return $this->user_id;
	}

	function name()
	{
		$this->get_userdata();
		return $this->userdata['username'];
	}

	function get_cashgroups()
	{
		global $db, $cm_groups, $cash;
		$this->get_userdata();
		if (!$this->cashgroups_init)
		{
			$cm_groups->load();
			$sql = "SELECT group_id
					FROM " . USER_GROUP_TABLE . "
					WHERE user_id = " . $this->user_id . "
						AND user_pending = 0
					ORDER BY group_id ASC";
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$this->usergroups[] = $row['group_id'];
			}
			while ($c_cur = &$cash->currency_next($cm_i))
			{
				$this->cashgroups[$c_cur->id()] = array();
				$this->cashgroups[$c_cur->id()][] = &$cash->currencies[$c_cur->id()];
			}
			$cm_groups->get_groups($this->cashgroups, $this->userdata['user_level'], $this->usergroups, $this->userdata['user_posts']);
			$this->cashgroups_init = true;
		}
	}

	function get_setting($cash_id, $attribute, $modifier = false)
	{
		global $cash;
		if (!$cash->currency_exists($cash_id))
		{
			return false;
		}
		$this->get_cashgroups();
		$sum = 0;
		for ($i = 0; $i < sizeof($this->cashgroups[$cash_id]); $i++)
		{
			$sum += intval($this->cashgroups[$cash_id][$i]->data($attribute));
		}
		$sum /= $cash->currencies[$cash_id]->factor($modifier);
		return $sum;
	}

	function give_bonus($forum_id)
	{
		global $db, $cash;
		if ($this->user_id == ANONYMOUS)
		{
			return;
		}
		$this->get_cashgroups();
		$clause = array();
		while ($c_cur = &$cash->currency_next($cm_i, CURRENCY_ENABLED, $forum_id))
		{
			$sum = $this->get_setting($c_cur->id(), 'cash_postbonus');
			if ($sum != 0)
			{
				$clause[] = $c_cur->db() . ' = ' . $c_cur->db() . ' + ' . $sum;
				$this->userdata[$c_cur->db()] += $sum;
			}
		}
		if (sizeof($clause))
		{
			$sql = "UPDATE " . USERS_TABLE . "
					SET " . implode(',',$clause) . "
					WHERE user_id = " . $this->user_id;
			$db->sql_query($sql);
		}
	}

	function give_pm_amount()
	{
		global $db, $cash;
		if ($this->user_id == ANONYMOUS)
		{
			return;
		}
		$this->get_cashgroups();
		$clause = array();
		while ($c_cur = &$cash->currency_next($cm_i, CURRENCY_ENABLED))
		{
			$sum = $this->get_setting($c_cur->id(), 'cash_perpm');
			if ($sum != 0)
			{
				$clause[] = $c_cur->db() . ' = ' . $c_cur->db() . ' + ' . $sum;
				$this->userdata[$c_cur->db()] += $sum;
			}
		}
		if (sizeof($clause))
		{
			$sql = "UPDATE " . USERS_TABLE . "
					SET " . implode(',',$clause) . "
					WHERE user_id = " . $this->user_id;
			$db->sql_query($sql);
		}
	}

	function has($cash_id,$amount)
	{
		global $cash;
		$this->get_userdata();
		return ($this->userdata[$cash->currencies[$cash_id]->db()] >= $amount);
	}

	function amount($cash_id)
	{
		global $cash;
		$this->get_userdata();
		return $this->userdata[$cash->currencies[$cash_id]->db()];
	}

	function give_by_id_array($id_array)
	{
		global $db, $cash;
		if ($this->user_id == ANONYMOUS)
		{
			return;
		}
		$sql_update = array();
		while ($c_cur = &$cash->currency_next($cm_i))
		{
			if (isset($id_array[$c_cur->id()]))
			{
				$sql_update[] = $c_cur->db() . ' = ' . $c_cur->db() . ' + ' . $id_array[$c_cur->id()];
				if ($this->userdata_stored)
				{
					$this->userdata[$c_cur->db()] += $id_array[$c_cur->id()];
				}
			}
		}
		if (sizeof($sql_update))
		{
			$sql = "UPDATE " . USERS_TABLE . "
					SET " . implode(', ',$sql_update) . "
					WHERE user_id = " . $this->user_id;
			$db->sql_query($sql);
		}
	}

	function remove_by_id_array($id_array)
	{
		global $db, $cash;
		if ($this->user_id == ANONYMOUS)
		{
			return;
		}
		$sql_update = array();
		while ($c_cur = &$cash->currency_next($cm_i))
		{
			if (isset($id_array[$c_cur->id()]))
			{
				$sql_update[] = $c_cur->db() . ' = ' . $c_cur->db() . ' - ' . $id_array[$c_cur->id()];
				if ($this->userdata_stored)
				{
					$this->userdata[$c_cur->db()] -= $id_array[$c_cur->id()];
				}
			}
		}
		if (sizeof($sql_update))
		{
			$sql = "UPDATE " . USERS_TABLE . "
					SET " . implode(', ',$sql_update) . "
					WHERE user_id = " . $this->user_id;
			$db->sql_query($sql);
		}
	}

	function set_by_id_array($id_array)
	{
		global $db, $cash;
		if ($this->user_id == ANONYMOUS)
		{
			return;
		}
		$sql_update = array();
		while ($c_cur = &$cash->currency_next($cm_i))
		{
			if (isset($id_array[$c_cur->id()]))
			{
				$sql_update[] = $c_cur->db() . ' = ' . $id_array[$c_cur->id()];
				if ($this->userdata_stored)
				{
					$this->userdata[$c_cur->db()] = $id_array[$c_cur->id()];
				}
			}
		}
		if (sizeof($sql_update))
		{
			$sql = "UPDATE " . USERS_TABLE . "
					SET " . implode(', ',$sql_update) . "
					WHERE user_id = " . $this->user_id;
			$db->sql_query($sql);
		}
	}
}

//
//=============[ END Cash Classes ]=========================
//

//
// Create Cash Objects
//
$cash = new cash_table();
$cm_groups = new cash_groups();

?>