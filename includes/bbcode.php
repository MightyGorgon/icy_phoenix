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
* Vjacheslav Trushkin (http://www.stsoftware.biz)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

/*

=================
Includes
=================

include_once(IP_ROOT_PATH . 'includes/bbcode.' . PHP_EXT);

=================
Globals
=================

global $bbcode;

=================
BBCode Parsing
=================

$text = $bbcode->parse($text);

=================
BBCode Conditions
=================

$bbcode->allow_html = ($user->data['user_allowhtml'] && $config['allow_html']) ? true : false;
$bbcode->allow_bbcode = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? true : false;
$bbcode->allow_smilies = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? true : false;

=================

$html_on = ($user->data['user_allowhtml'] && $config['allow_html']) ? 1 : 0 ;
$bbcode_on = ($user->data['user_allowbbcode'] && $config['allow_bbcode']) ? 1 : 0 ;
$smilies_on = ($user->data['user_allowsmile'] && $config['allow_smilies']) ? 1 : 0 ;

$bbcode->allow_html = $html_on;
$bbcode->allow_bbcode = $bbcode_on;
$bbcode->allow_smilies = $smilies_on;

=================

$bbcode->allow_html = ($config['allow_html'] ? true : false);
$bbcode->allow_bbcode = ($config['allow_bbcode'] ? true : false);
$bbcode->allow_smilies = ($config['allow_smilies'] ? true : false);

=================

$bbcode->allow_html = (($config['allow_html'] && $row['enable_bbcode']) ? true : false);
$bbcode->allow_bbcode = (($config['allow_bbcode'] && $row['enable_bbcode']) ? true : false);
$bbcode->allow_smilies = (($config['allow_smilies'] && $row['enable_smilies']) ? true : false);

=================

$bbcode->allow_html = ($config['allow_html'] && $postrow[$i]['enable_bbcode'] ? true : false);
$bbcode->allow_bbcode = ($config['allow_bbcode'] && $postrow[$i]['enable_bbcode'] ? true : false);
$bbcode->allow_smilies = ($config['allow_smilies'] && $postrow[$i]['enable_smilies'] ? true : false);

=================

=================================
Acronyms, Autolinks
=================================

$text = $bbcode->acronym_pass($text);
$text = $bbcode->autolink_text($text, $forum_id);
====================


*/

// If included via function we need to make sure to have the requested globals...
global $db, $cache, $config, $lang;

// To use this file outside Icy Phoenix you need to comment the define below and remove the check on top of the file.
define('IS_ICYPHOENIX', true);
if(defined('IS_ICYPHOENIX'))
{
	// Include moved to functions... to avoid including wrong lang file ($config['default_lang'] is only assigned after session request)
	//setup_extra_lang(array('lang_bbcb_mg'));
}
else
{
	if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
	if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
	$config['allow_all_bbcode'] = 0;
	$config['allow_html'] = false;
	$config['allow_bbcode'] = true;
	$config['allow_smilies'] = true;
	$config['default_lang'] = 'english';
	$config['cookie_secure'] = 0;
	$config['server_name'] = 'icyphoenix.com';
	$config['script_path'] = '/';
	$config['liw_enabled'] = 0;
	$config['liw_max_width'] = 0;
	$config['thumbnail_cache'] = 0;
	$config['thumbnail_posts'] = 0;
	$config['thumbnail_highslide'] = 0;
	$config['disable_html_guests'] = 0;
	$config['quote_iterations'] = 3;
	$config['switch_bbcb_active_content'] = 1;
	$user->data['is_bot'] = false;
	$user->data['session_logged_in'] = 0;
	$lang['OpenNewWindow'] = 'Open in new window';
	$lang['Click_enlarge_pic'] = 'Click to enlarge the image';
	$lang['Links_For_Guests'] = 'You must be logged in to see this link';
	$lang['Quote'] = 'Quote';
	$lang['Code'] = 'Code';
	$lang['OffTopic'] = 'Off Topic';
	$lang['ReviewPost'] = 'Review Post';
	$lang['wrote'] = 'wrote';
	$lang['Description'] = 'Description';
	$lang['Download'] = 'Download';
	$lang['Hide'] = 'Hide';
	$lang['Show'] = 'Show';
	$lang['Select'] = 'Select';
	$lang['xs_bbc_hide_message'] = 'Hidden';
	$lang['xs_bbc_hide_message_explain'] = 'This message is hidden, you have to answer this topic to see it.';
	$lang['DOWNLOADED'] = 'Downloaded';
	$lang['FILESIZE'] = 'Filesize';
	$lang['FILENAME'] = 'Filename';
	$lang['Not_Authorized'] = 'Not Authorized';
	$lang['FILE_NOT_AUTH'] = 'You are not authorized to download this file';
}

$server_protocol = !empty($config['cookie_secure']) ? 'https://' : 'http://';
$local_urls = array(
	$server_protocol . 'www.' . $config['server_name'] . $config['script_path'],
	$server_protocol . $config['server_name'] . $config['script_path']
);

if (function_exists('create_server_url'))
{
	$server_url = create_server_url();
}
else
{
	$host = getenv('HTTP_HOST');
	$host = (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (!empty($host) ? $host : $config['server_name']));
	$server_url = $server_protocol . $host . $config['script_path'];
}
$smileys_path = $server_url . $config['smilies_path'] . '/';

define('BBCODE_UID_LEN', 10);
define('BBCODE_NOSMILIES_START', '<!-- no smilies start -->');
define('BBCODE_NOSMILIES_END', '<!-- no smilies end -->');
define('BBCODE_SMILIES_PATH', $smileys_path);
define('AUTOURL', time());

// Need to initialize the random numbers only ONCE
mt_srand((double) microtime() * 1000000);

class bbcode
{
	var $text = '';
	var $html = '';
	var $tag = '';

	var $code_counter = 0;
	var $code_post_id = 0;

	var $allow_html = false;
	var $allow_styling = true;
	var $allow_bbcode = true;
	var $allow_smilies = true;
	var $allow_hs = true;
	var $plain_html = false;
	var $is_sig = false;

	var $params = array();
	var $data = array();
	var $replaced_smilies = array();

	var $self_closing_tags = array('[*]', '[hr]');

	var $allowed_bbcode = array(
		'b'						=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'strong'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'em'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'i'						=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'u'						=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'tt'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'strike'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'sup'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'sub'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),

		'color'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'highlight'		=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'rainbow'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'gradient'		=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'fade'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'opacity'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),

		'align'				=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'center'			=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'font'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'size'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'hr'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => true),

		'url'					=> array('nested' => false, 'inurl' => false),
		'a'						=> array('nested' => false, 'inurl' => false),
		'email'				=> array('nested' => false, 'inurl' => false),

		'list'				=> array('nested' => true, 'inurl' => false),
		'ul'					=> array('nested' => true, 'inurl' => false),
		'ol'					=> array('nested' => true, 'inurl' => false),
		'li'					=> array('nested' => true, 'inurl' => false),
		'*'						=> array('nested' => true, 'inurl' => false),

		'div'					=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'span'				=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'cell'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'spoiler'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'hide'				=> array('nested' => false, 'inurl' => true, 'allow_empty' => false),

		'quote'				=> array('nested' => true, 'inurl' => false),
		'ot'					=> array('nested' => true, 'inurl' => false),
		'code'				=> array('nested' => false, 'inurl' => false),
		'codeblock'		=> array('nested' => false, 'inurl' => false),
		'c'						=> array('nested' => false, 'inurl' => false),

		'img'					=> array('nested' => false, 'inurl' => true),
		'imgba'				=> array('nested' => false, 'inurl' => true),
		'albumimg'		=> array('nested' => false, 'inurl' => true),
		'attachment'	=> array('nested' => false, 'inurl' => false, 'allow_empty' => true),
		'download'		=> array('nested' => false, 'inurl' => false, 'allow_empty' => true),

		'user'				=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'search'			=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'tag'					=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'langvar'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => true),
		'language'		=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),

		'random'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => true),
		'marquee'			=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'smiley'			=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),

		'flash'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'swf'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'flv'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'video'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'ram'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'quick'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'stream'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'emff'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'mp3'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'vimeo'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'youtube'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'googlevideo'	=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),

		// All these tags require HTML 4 specification (NON XHTML) and only work with IE!
		// Decomment below to use these properly...
		/*
		'glow'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'shadow'		=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'blur'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'wave'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'fliph'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'flipv'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		*/

		// Requires external file for parsing TEX
		//'tex'				=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),

		// To use tables you just need to decomment this... no need to decomment even TR and TD
		//'table'			=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		/*
		'tr'				=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'td'				=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		*/

		// To use IFRAMES you just need to decomment this line (and the block some hundreds lines below)... good luck!
		//'iframe'		=> array('nested' => true, 'inurl' => false, 'allow_empty' => true),
	);

	var $allowed_html = array(
		'b'						=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'strong'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'em'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'i'						=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'u'						=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'tt'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'strike'			=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'sup'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),
		'sub'					=> array('nested' => true, 'inurl' => true, 'allow_empty' => false),

		'div'					=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'span'				=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'center'			=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'hr'					=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),

		'a'						=> array('nested' => false, 'inurl' => false),
		'ul'					=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'ol'					=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'li'					=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'code'				=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),
		'blockquote'	=> array('nested' => true, 'inurl' => false, 'allow_empty' => false),

		'table'			=> array('nested' => true, 'inurl' => false),
		/*
		'tr' => array('nested' => true, 'inurl' => false),
		'td' => array('nested' => true, 'inurl' => false),
		*/

		// To use IFRAMES you just need to decomment this line (and the block some hundreds lines below)... good luck!
		//'iframe' => array('nested' => true, 'inurl' => false, 'allow_empty' => true),
	);

	var $allowed_smilies = array(
		array('code' => ':wink:', 'replace' => '(wink)'),
		array('code' => ';)', 'replace' => '(smile1)'),
		array('code' => ':)', 'replace' => '(smile2)'),
	);

	/**
	* Instantiate class
	*/
	function bbcode()
	{
		global $config;

		$this->allow_html = (!empty($config['allow_html']) ? true : false);
		$this->allow_bbcode = (!empty($config['allow_bbcode']) ? true : false);
		$this->allow_smilies = (!empty($config['allow_smilies']) ? true : false);
	}

	/*
	Clean bbcode/html tag.
	*/
	function clean_tag(&$item)
	{
		$tag = $item['tag'];
		//echo 'clean_tag(', $tag, ')<br />';
		$start = substr($this->text, $item['start'], $item['start_len']);
		$end = substr($this->text, $item['end'], $item['end_len']);
		$content = substr($this->text, $item['start'] + $item['start_len'], $item['end'] - $item['start'] - $item['start_len']);
		$error = array(
			'valid' => false,
			'start' => $this->process_text($start),
			'end' => $this->process_text($end)
		);
		if(isset($item['valid']) && $item['valid'] == false)
		{
			return $error;
		}

		// check if empty item is allowed
		if(!strlen($content))
		{
			$allow_empty = true;
			if($item['is_html'] && isset($this->allowed_html[$tag]['allow_empty']) && !$this->allowed_html[$tag]['allow_empty'])
			{
				$allow_empty = false;
			}
			if(!$item['is_html'] && isset($this->allowed_bbcode[$tag]['allow_empty']) && !$this->allowed_bbcode[$tag]['allow_empty'])
			{
				$allow_empty = false;
			}
			if(!$allow_empty)
			{
				return array(
					'valid' => true,
					'html' => '',
					'end' => '',
					'allow_nested' => false,
				);
			}
		}

		return array(
			'valid' => true,
			'start' => '',
			'end' => ''
		);
	}

	/*
	Process bbcode/html tag.
	This is the only function you would want to modify to add your own bbcode/html tags.
	Note: this bbcode parser doesn't make any differece of bbcode and html, so <b> and [b] are treated exactly same way
	*/
	function process_tag(&$item)
	{
		global $db, $cache, $config, $user, $lang, $topic_id, $local_urls, $meta_content;

		$server_protocol = !empty($config['cookie_secure']) ? 'https://' : 'http://';
		if (function_exists('create_server_url'))
		{
			$server_url = create_server_url();
			$local_urls = empty($local_urls) ? array($server_url) : array_merge(array($server_url), $local_urls);
		}
		else
		{
			$host = getenv('HTTP_HOST');
			$host = (!empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (!empty($host) ? $host : $config['server_name']));
			$server_url = $server_protocol . $host . $config['script_path'];
		}

		//LIW - BEGIN
		$max_image_width = intval($config['liw_max_width']);
		//LIW - END
		$tag = $item['tag'];
		//echo 'process_tag(', $tag, ')<br />';
		$start = substr($this->text, $item['start'], $item['start_len']);
		$end = substr($this->text, $item['end'], $item['end_len']);
		$content = substr($this->text, $item['start'] + $item['start_len'], $item['end'] - $item['start'] - $item['start_len']);
		$error = array(
			'valid' => false,
			'start' => $this->process_text($start),
			'end' => $this->process_text($end)
		);
		if(isset($item['valid']) && $item['valid'] == false)
		{
			return $error;
		}

		// check if empty item is allowed
		if(!strlen($content))
		{
			$allow_empty = true;
			if($item['is_html'] && isset($this->allowed_html[$tag]['allow_empty']) && !$this->allowed_html[$tag]['allow_empty'])
			{
				$allow_empty = false;
			}
			if(!$item['is_html'] && isset($this->allowed_bbcode[$tag]['allow_empty']) && !$this->allowed_bbcode[$tag]['allow_empty'])
			{
				$allow_empty = false;
			}
			if(!$allow_empty)
			{
				return array(
					'valid' => true,
					'html' => '',
					'end' => '',
					'allow_nested' => false,
				);
			}
		}

		// check if nested item is allowed
		if($item['iteration'])
		{
			if($item['is_html'] && !$this->allowed_html[$tag]['nested'])
			{
				return $error;
			}
			if(!$item['is_html'] && !$this->allowed_bbcode[$tag]['nested'])
			{
				return $error;
			}
		}

		// Simple tags: B, EM, STRONG, I, U, TT, STRIKE, SUP, SUB, DIV, SPAN, CENTER
		if(($tag === 'b') || ($tag === 'em') || ($tag === 'strong') || ($tag === 'i') || ($tag === 'u') || ($tag === 'tt') || ($tag === 'strike') || ($tag === 'sup') || ($tag === 'sub') || ($tag === 'div') || ($tag === 'span') || ($tag === 'center'))
		{
			$extras = $this->allow_styling ? array('style', 'class', 'name') : array('class', 'name');
			$html = '<' . $tag . $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</' . $tag . '>'
			);
		}

		// COLOR
		if($tag === 'color')
		{
			$extras = $this->allow_styling ? array('class') : array();
			$color = $this->valid_color((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['color']) ? $item['params']['color'] : false)));

			if($color === false)
			{
				return $error;
			}

			$html = '<span style="' . ($this->allow_styling && isset($item['params']['style']) ? htmlspecialchars($this->valid_style($item['params']['style'], '')) : '') . 'color: ' . $color . ';"' . $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</span>',
			);
		}

		// RAINBOW
		if($tag === 'rainbow')
		{
			$html = $this->rainbow($content);
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// GRADIENT
		if($tag === 'gradient')
		{
			$default_color1 = '#000080';
			$color1 = $this->valid_color((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['cols']) ? $item['params']['cols'] : $default_color1)), true);
			$color1 = (($color1 === false) ? $default_color1 : $color1);

			$default_color2 = '#aaccee';
			$color2 = $this->valid_color((isset($item['params']['cole']) ? $item['params']['cole'] : $default_color2), true);
			$color2 = (($color2 === false) ? $default_color2 : $color2);

			$mode = $this->process_text((isset($item['params']['mode']) ? $item['params']['mode'] : ''));

			$default_iterations = 10;
			$iterations = intval(isset($item['params']['iterations']) ? $item['params']['iterations'] : $default_iterations);
			$iterations = ((($iterations < 10) || ($iterations > 100)) ? $default_iterations : $iterations);

			$html = $this->gradient($content, $color1, $color2, $mode, $iterations);
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// HIGHLIGHT
		if($tag === 'highlight')
		{
			$extras = $this->allow_styling ? array('class') : array();
			$default_param = '#ffffaa';
			$color = (isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['color']) ? $item['params']['color'] : $default_param));
			$color = $this->valid_color($color);
			if($color === false)
			{
				return $error;
			}
			$html = '<span style="' . ($this->allow_styling && isset($item['params']['style']) ? htmlspecialchars($this->valid_style($item['params']['style'], '')) : '') . 'background-color: ' . $color . ';"' . $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</span>',
			);
		}

		// SIZE
		if($tag === 'size')
		{
			$extras = $this->allow_styling ? array('class') : array();
			$default_param = 0;
			$size = intval((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['size']) ? $item['params']['size'] : $default_param)));
			if($size > 0 && $size < 7)
			{
				// vBulletin-style sizes
				switch($size)
				{
					case 1: $size = 7; break;
					case 2: $size = 8; break;
					case 3: $size = 10; break;
					case 4: $size = 12; break;
					case 5: $size = 15; break;
					case 6: $size = 24; break;
				}
			}
			if(($size < 6) || ($size > 48))
			{
				return $error;
			}
			$html = '<span style="' . ($this->allow_styling && isset($item['params']['style']) ? htmlspecialchars($this->valid_style($item['params']['style'], '')) : '') . 'font-size: ' . $size . 'px; line-height: 116%;"' . $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</span>',
			);
		}

		// Single tags: HR
		if($tag === 'hr')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;

			$extras = $this->allow_styling ? array('style', 'class') : array();
			$color = $this->valid_color((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['color']) ? $item['params']['color'] : false)));

			$html = '<' . $tag . (($color === false) ? ($this->allow_styling && isset($item['params']['style']) ? (' style="' . htmlspecialchars($this->valid_style($item['params']['style'], '')) . '"') : '') : (' style="border-color: ' . $color . ';"')) . ' />';
			return array(
				'valid' => true,
				'html' => $html
			);
		}

		// ALIGN
		if($tag === 'align')
		{
			$extras = $this->allow_styling ? array('style', 'class') : array();
			$default_param = 'left';
			$align = (isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['align']) ? $item['params']['align'] : $default_param));
			if (($align === 'left') || ($align === 'right') || ($align === 'center') || ($align === 'justify'))
			{
				$html = '<div style="text-align: ' . $align . ';' . (($align === 'center') ? (' margin-left: auto; margin-right: auto;') : '') . '">';
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div>',
				);
			}
			else
			{
				return $error;
			}
		}

		// IMG
		if($tag === 'img')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;

			// main parameters
			$params = array(
				'src' => false,
				'alt' => false,
				'slide' => false,
			);

			// additional allowed parameters
			$extras = $this->allow_styling ? array('width', 'height', 'border', 'style', 'class', 'title', 'align') : array('width', 'height', 'border', 'title', 'align');
			if ($config['thumbnail_highslide'])
			{
				$slideshow = !empty($item['params']['slide']) ? ', { slideshowGroup: \'' . $this->process_text($item['params']['slide']) . '\' } ' : '';
			}
			$liw_bypass = false;

			// [img=blah]blah2[/img]
			if(isset($item['params']['param']))
			{
				$params['src'] = $item['params']['param'];
				$img_url = $params['src'];
				$img_url_enc = urlencode(ip_utf8_decode($params['src']));
				$path_parts = pathinfo($img_url);
				$params['alt'] = (!empty($content) ? $content : ip_clean_string($path_parts['filename'], $lang['ENCODING'], true));
			}
			// [img src=blah alt=blah width=123][/img]
			elseif(isset($item['params']['src']))
			{
				$params['src'] = $item['params']['src'];
				$img_url = $params['src'];
				$img_url_enc = urlencode(ip_utf8_decode($params['src']));
				$path_parts = pathinfo($img_url);
				$params['alt'] = (isset($item['params']['alt']) ? $item['params']['alt'] : (!empty($content) ? $content : ip_clean_string($path_parts['filename'], $lang['ENCODING'], true)));
				for($i = 0; $i < sizeof($extras); $i++)
				{
					if(!empty($item['params'][$extras[$i]]))
					{
						if($extras[$i] === 'style')
						{
							$style = $this->valid_style($item['params']['style']);
							if($style !== false)
							{
								$params['style'] = $style;
							}
						}
						else
						{
							$params[$extras[$i]] = $item['params'][$extras[$i]];
						}
					}
				}
			}
			// [img]blah[/img], [img width=blah]blah[/img]
			elseif(!empty($content))
			{
				$params['src'] = $content;
				$img_url = $params['src'];
				$img_url_enc = urlencode(ip_utf8_decode($params['src']));
				$path_parts = pathinfo($img_url);
				$params['alt'] = (isset($item['params']['alt']) ? $item['params']['alt'] : (isset($params['title']) ? $params['title'] : ip_clean_string($path_parts['filename'], $lang['ENCODING'], true)));
				// LIW - BEGIN
				if (($config['liw_enabled'] == 1) && ($max_image_width > 0) && ($config['thumbnail_posts'] == 0) && empty($this->plain_html))
				{
					$liw_bypass = true;
					if (isset($item['params']['width']))
					{
						$item['params']['width'] = ($item['params']['width'] > $max_image_width) ? $max_image_width : $item['params']['width'];
					}
					else
					{
						$image_size = @getimagesize($content);
						$item['params']['width'] = ($image_size[0] > $max_image_width) ? $max_image_width : $image_size[0];
					}
				}
				// LIW - END
				for($i = 0; $i < sizeof($extras); $i++)
				{
					if(!empty($item['params'][$extras[$i]]))
					{
						if($extras[$i] === 'style')
						{
							$style = $this->valid_style($item['params']['style']);
							if($style !== false)
							{
								$params['style'] = $style;
							}
						}
						else
						{
							$params[$extras[$i]] = $item['params'][$extras[$i]];
						}
					}
				}
			}

			$is_smiley = false;
			if (substr($params['src'], 0, strlen(BBCODE_SMILIES_PATH)) == BBCODE_SMILIES_PATH)
			{
				$is_smiley = true;
			}

			if (!$is_smiley && $config['thumbnail_posts'] && ($liw_bypass == false) && empty($this->plain_html))
			{
				$process_thumb = !empty($config['thumbnail_cache']) ? true : false;
				$thumb_exists = false;
				$thumb_processed = false;
				$is_light_view = false;
				if (isset($item['params']['thumb']))
				{
					if ($item['params']['thumb'] == 'false')
					{
						$process_thumb = false;
					}
				}
				if(!empty($process_thumb))
				{
					$thumb_processed = true;
					$pic_id = $img_url;
					$pic_fullpath = str_replace(array(' '), array('%20'), $pic_id);
					$pic_id = str_replace('http://', '', str_replace('https://', '', $pic_id));
					$pic_path[] = array();
					$pic_path = explode('/', $pic_id);
					$pic_filename = end($pic_path);
					$file_part = explode('.', strtolower($pic_filename));
					$pic_filetype = end($file_part);
					$thumb_ext_array = array('gif', 'jpg', 'png');
					if (in_array($pic_filetype, $thumb_ext_array))
					{
						$user_dir = '';
						$users_images_path = str_replace('http://', '', str_replace('https://', '', $server_url . str_replace(IP_ROOT_PATH, '', POSTED_IMAGES_PATH)));
						$pic_title = substr($pic_filename, 0, strlen($pic_filename) - strlen($pic_filetype) - 1);
						$pic_title_reg = preg_replace('/[^A-Za-z0-9]+/', '_', $pic_title);
						$pic_thumbnail = 'mid_' . md5($pic_id) . '_' . $pic_filename;
						if (strpos($pic_id, $users_images_path) !== false)
						{
							$user_dir = str_replace($pic_filename, '', str_replace($users_images_path, '', $pic_id));
							$pic_thumbnail = $pic_filename;
						}
						$pic_thumbnail_fullpath = POSTED_IMAGES_THUMBS_PATH . $user_dir . $pic_thumbnail;
						// Light View - BEGIN
						$light_view = request_var('light_view', 0);
						// Force to false for debugging purpose...
						$light_view = 0;
						if (!empty($light_view) && !empty($user_dir))
						{
							$is_light_view = true;
							$pic_thumbnail_fullpath = POSTED_IMAGES_THUMBS_S_PATH . $user_dir . $pic_thumbnail;
						}
						// Light View - END
						if(file_exists($pic_thumbnail_fullpath))
						{
							$thumb_exists = true;
							$params['src'] = $server_url . str_replace(IP_ROOT_PATH, '', $pic_thumbnail_fullpath);
						}
					}
				}
				$cache_image = true;
				$cache_append = '';
				if (isset($item['params']['cache']))
				{
					if ($item['params']['cache'] == 'false')
					{
						//$bbc_eamp = '&amp;';
						$bbc_eamp = '&';
						$cache_image = false;
						$cache_append = 'cache=false' . $bbc_eamp . 'rand=' . md5(rand()) . $bbc_eamp;
					}
					else
					{
						$cache_image = true;
					}
				}
				if (!empty($process_thumb) && (($thumb_exists == false) || ($cache_image == false)))
				{
					$pic_thumbnail_script = $server_url . CMS_PAGE_IMAGE_THUMBNAIL . '?' . $cache_append . 'pic_id=' . $img_url_enc;
					// Light View - BEGIN
					if (!empty($thumb_processed) && !empty($is_light_view))
					{
						$img_url_enc = $user_dir . $pic_thumbnail;
						$pic_thumbnail_script = $server_url . CMS_PAGE_IMAGE_THUMBNAIL_S . '?' . $cache_append . 'pic_id=' . $img_url_enc;
					}
					// Light View - END
					$params['src'] = $pic_thumbnail_script;
				}
			}

			// generate html
			$html = '<img';
			foreach($params as $var => $value)
			{
				if ($this->process_text($value) != '')
				{
					$html .= ' ' . $var . '="' . $this->process_text($value) . '"';
				}
				if (($var == 'src') && (!$this->is_sig))
				{
					$meta_content['og_img'][] = $value;
				}
			}
			if(!isset($params['title']))
			{
				$html .= ' title="' . $this->process_text($params['alt']) . '"';
			}
			$html .= ' />';
			// add url
			/*
			if (strpos($params['src'], trim($config['server_name'])) == false)
			{
				$html = $this->process_text($params['alt']);
			}
			*/
			// Light View - BEGIN
			if (!empty($thumb_processed) && !empty($is_light_view) && empty($this->plain_html))
			{
				$item['inurl'] = true;
			}
			// Light View - END
			if(empty($item['inurl']) && !$is_smiley && empty($this->plain_html))
			{
				if ($this->allow_hs && $config['thumbnail_posts'] && $config['thumbnail_highslide'])
				{
					$extra_html = ' class="highslide" onclick="return hs.expand(this' . $slideshow . ')"';
				}
				else
				{
					$extra_html = ' target="_blank" title="' . $lang['OpenNewWindow'] . '"';
				}
				$html = '<a href="' . $this->process_text($img_url) . '"' . $extra_html . '>' . $html . '</a>';
			}
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// IMGBA
		if($tag === 'imgba')
		{
			if($this->is_sig) return $error;
			if (!empty($this->plain_html)) return $error;

			// main parameters
			$params = array(
				'before' => false,
				'after' => false,
				'width' => false,
				'w' => false,
				'height' => false,
				'h' => false,
				'alt' => false,
				'title' => false,
			);

			foreach ($params as $k => $v)
			{
				$params[$k] = $item['params'][$k];
			}

			if (empty($params['before']) || empty($params['after']))
			{
				return $error;
			}

			$path_parts = pathinfo($params['before']);
			(int) $params['width'] = !empty($params['w']) ? intval($params['w']) : intval($params['width']);
			(int) $params['height'] = !empty($params['h']) ? intval($params['h']) : intval($params['height']);
			$params['alt'] = (!empty($params['alt']) ? $params['alt'] : ip_clean_string($path_parts['filename'], $lang['ENCODING'], true));

			if (empty($params['width']) || empty($params['height']))
			{
				return $error;
			}

			// Since we passed the main tests, we may force all needed JS inclusions...
			$config['jquery_ui'] = true;
			$config['jquery_ui_ba'] = true;

			$max_width = 600;
			$or_width = $params['width'];
			$or_height = $params['height'];
			if ($params['width'] > $max_width)
			{
				$params['width'] = $max_width;
				$params['height'] = $max_width / ($or_width / $or_height);
			}

			// additional allowed parameters
			$extras = $this->allow_styling ? array('style', 'class') : array();

			for($i = 0; $i < sizeof($extras); $i++)
			{
				if(!empty($item['params'][$extras[$i]]))
				{
					if($extras[$i] === 'style')
					{
						$style = $this->valid_style($item['params']['style']);
						if($style !== false)
						{
							$params['style'] = $style;
						}
					}
					else
					{
						$params[$extras[$i]] = $item['params'][$extras[$i]];
					}
				}
			}

			$container = 'imgba_' . substr(md5($params['before']), 0, 6);

			$imgba_error = false;
			$allowed_ext = array('gif', 'jpeg', 'jpg', 'png');
			$img_test_array = array('before', 'after');
			// Few "pseudo-security" tests
			foreach ($img_test_array as $img_test)
			{
				$file_ext = substr(strrchr($params[$img_test], '.'), 1);
				//if (!in_array($file_ext, $allowed_ext) || (strpos($params[$img_test], $server_url) !== 0) || (strpos($params[$img_test], '?') !== 0))
				if (!in_array($file_ext, $allowed_ext))
				{
					$imgba_error = true;
				}
			}

			if (!empty($imgba_error))
			{
				return $error;
			}

			// generate html
			$html = '';
			$html .= '<div id="' . $container . '"';
			foreach($params as $var => $value)
			{
				if (in_array($value, array('width', 'height')) && ($this->process_text($value) != ''))
				{
					$html .= ' ' . $var . '="' . $this->process_text($value) . '"';
				}
			}
			$html .= '>';

			$img_alt = $this->process_text($params['alt']);
			$img_title = (!empty($params['title']) ? ' title="' . $this->process_text($params['title']) . '"' : '');
			$html .= '<div><img src="' . $params['before'] . '" width="' . $params['width'] . '" height="' . $params['height'] . '" alt="Before: ' . $img_alt . '"' . $img_title . ' /></div>';
			$html .= '<div><img src="' . $params['after'] . '" width="' . $params['width'] . '" height="' . $params['height'] . '" alt="After: ' . $img_alt . '"' . $img_title . ' /></div>';
			$html .= '</div>';
			$html .= '<script type="text/javascript">$(function(){ $(\'#' . $container . '\').beforeAfter({imagePath: \'' . $server_url . 'templates/common/jquery/\', showFullLinks: true, cursor: \'e-resize\', dividerColor: \'#dd2222\', beforeLinkText: \'' . $lang['IMG_BA_SHOW_ONLY_BEFORE'] . '\', afterLinkText: \'' . $lang['IMG_BA_SHOW_ONLY_AFTER'] . '\'}); });</script>';
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// ALBUMIMG
		if($tag === 'albumimg')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			// main parameters
			$params = array(
				'src' => false,
				'alt' => false,
			);
			// additional allowed parameters
			$extras = $this->allow_styling ? array('width', 'height', 'border', 'style', 'class', 'title', 'align') : array('width', 'height', 'border', 'title', 'align');
			// [albumimg=blah]blah2[/albumimg]
			if(isset($item['params']['param']))
			{
				$params['src'] = $item['params']['param'];
				$pic_url = $item['params']['param'];
				$params['alt'] = $content;
			}
			// [albumimg src=blah alt=blah width=123][/albumimg]
			elseif(isset($item['params']['src']))
			{
				$params['src'] = $item['params']['src'];
				$pic_url = $item['params']['src'];
				$params['alt'] = isset($item['params']['alt']) ? $item['params']['alt'] : $content;
				for($i = 0; $i < sizeof($extras); $i++)
				{
					if(!empty($item['params'][$extras[$i]]))
					{
						if($extras[$i] === 'style')
						{
							$style = $this->valid_style($item['params']['style']);
							if($style !== false)
							{
								$params['style'] = $style;
							}
						}
						else
						{
							$params[$extras[$i]] = $item['params'][$extras[$i]];
						}
					}
				}
			}
			// [albumimg]blah[/albumimg], [albumimg width=blah]blah[/albumimg]
			elseif(!empty($content))
			{
				$params['src'] = $content;
				$pic_url = $content;
				$params['alt'] = isset($item['params']['alt']) ? $item['params']['alt'] : (isset($params['title']) ? $params['title'] : '');
				for($i = 0; $i < sizeof($extras); $i++)
				{
					if(!empty($item['params'][$extras[$i]]))
					{
						if($extras[$i] === 'style')
						{
							$style = $this->valid_style($item['params']['style']);
							if($style !== false)
							{
								$params['style'] = $style;
							}
						}
						else
						{
							$params[$extras[$i]] = $item['params'][$extras[$i]];
						}
					}
				}
			}
			// generate html
			$pic_url = $server_url . 'album_showpage.' . PHP_EXT . '?pic_id=' . $pic_url;
			if(isset($item['params']['mode']))
			{
				$pic_mode = $item['params']['mode'];
				if ($pic_mode === 'full')
				{
					$params['src'] = $server_url . 'album_picm.' . PHP_EXT . '?pic_id=' . $params['src'];
				}
				else
				{
					$params['src'] = $server_url . 'album_thumbnail.' . PHP_EXT . '?pic_id=' . $params['src'];
				}
			}
			else
			{
				$params['src'] = $server_url . 'album_thumbnail.' . PHP_EXT . '?pic_id=' . $params['src'];
			}
			$html = '<img';
			foreach($params as $var => $value)
			{
				$html .= ' ' . $var . '="' . $this->process_text($value) . '"';
				if (($var == 'src') && (!$this->is_sig))
				{
					$meta_content['og_img'][] = $value;
				}
			}
			if(!isset($params['title']))
			{
				$html .= ' title="' . $this->process_text($params['alt']) . '"';
			}
			$html .= ' />';
			// add url
			if(empty($item['inurl']))
			{
				$html = '<a href="' . $this->process_text($pic_url) . '" title="' . $lang['Click_enlarge_pic'] . '">' . $html . '</a>';
			}
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// ATTACHMENT
		if(($tag === 'attachment') || ($tag === 'download'))
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			$html = '';
			$params['id'] = isset($item['params']['param']) ? intval($item['params']['param']) : (isset($item['params']['id']) ? intval($item['params']['id']) : false);
			$params['title'] = isset($item['params']['title']) ? $this->process_text($item['params']['title']) : false;
			$params['description'] = isset($item['params']['description']) ? $this->process_text($item['params']['description']) : (!empty($content) ? $this->process_text($content) : false);
			$params['icon'] = isset($item['params']['icon']) ? $this->process_text($item['params']['icon']) : false;
			$color = $this->valid_color(isset($item['params']['color']) ? $item['params']['color'] : false);
			$bgcolor = $this->valid_color(isset($item['params']['bgcolor']) ? $item['params']['bgcolor'] : false);

			$errored = false;
			if ($params['id'] <= 0)
			{
				$errored = true;
			}

			if (!$errored)
			{
				if ($tag === 'attachment')
				{
					if (!function_exists('get_attachment_details'))
					{
						include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
					}
					$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $user->data);
					$is_download_auth_ary = auth(AUTH_DOWNLOAD, AUTH_LIST_ALL, $user->data);
					$attachment_details = get_attachment_details($params['id']);
					if (($attachment_details == false) || !$is_auth_ary[$attachment_details['forum_id']]['auth_read'] || !$is_download_auth_ary[$attachment_details['forum_id']]['auth_download'])
					{
						$errored = true;
					}
				}
				else
				{
					if (!function_exists('get_download_details'))
					{
						include_once(IP_ROOT_PATH . 'includes/functions_bbcode.' . PHP_EXT);
					}
					$attachment_details = get_download_details($params['id']);
					$errored = ($attachment_details == false) ? true : false;
				}
			}

			if (!$errored)
			{
				if ($tag === 'attachment')
				{
					$params['title'] = $params['title'] ? $params['title'] : (!empty($attachment_details['real_filename']) ? $attachment_details['real_filename'] : '&nbsp;');
					$params['description'] = $params['description'] ? $params['description'] : (!empty($attachment_details['comment']) ? $attachment_details['comment'] : ' ');
					$params['icon'] = IP_ROOT_PATH . FILES_ICONS_DIR . ($params['icon'] ? $params['icon'] : 'default.png');
					$download_url = IP_ROOT_PATH . 'download.' . PHP_EXT . '?id=' . $params['id'];
				}
				else
				{
					$params['title'] = $params['title'] ? $params['title'] : (!empty($attachment_details['file_name']) ? $attachment_details['file_name'] : '&nbsp;');
					$params['description'] = $params['description'] ? $params['description'] : (!empty($attachment_details['file_desc']) ? $attachment_details['file_desc'] : ' ');
					$params['icon'] = IP_ROOT_PATH . FILES_ICONS_DIR . ($params['icon'] ? $params['icon'] : (!empty($attachment_details['file_posticon']) ? $attachment_details['file_posticon'] : 'default.png'));
					$attachment_details['filesize'] = $attachment_details['file_size'];
					$attachment_details['download_count'] = $attachment_details['file_dls'];
					$download_url = IP_ROOT_PATH . 'dload.' . PHP_EXT . '?action=file&amp;file_id=' . $params['id'];
				}

				$params['title'] = htmlspecialchars($params['title']);
				$params['description'] = htmlspecialchars($params['description']);
				$params['icon'] = file_exists($params['icon']) ? $params['icon'] : (IP_ROOT_PATH . FILES_ICONS_DIR . 'default.png');
				$style = ($color || $bgcolor) ? (' style="' . ($color ? 'color: ' . $color . ';' : '') . ($bgcolor ? 'background-color: ' . $bgcolor . ';' : '') . '"') : '';

				$html .= '<div class="mg_attachtitle"' . $style . '>' . $params['title'] . '</div>';
				$html .= '<div class="mg_attachdiv"><table>';
				$html .= '<tr><td style="width: 15%;"><b class="gensmall">' . $lang['Description'] . ':</b></td><td style="width: 75%;"><span class="gensmall">' . $params['description'] . '</span></td><td rowspan="3" class="row-center" style="width: 10%;"><img src="' . $params['icon'] . '" alt="' . $params['description'] . '" /><br /><a href="' . append_sid($download_url) . '" title="' . $lang['Download'] . ' ' . $params['title'] . '"><b>' . $lang['Download'] . '</b></a></td></tr>';
				$html .= '<tr><td><b class="gensmall">' . $lang['FILESIZE'] . ':</b></td><td><span class="gensmall">' . round(($attachment_details['filesize'] / 1024), 2) . ' KB</span></td></tr>';
				$html .= '<tr><td><b class="gensmall">' . $lang['DOWNLOADED'] . ':</b></td><td><span class="gensmall">' . $attachment_details['download_count'] . '</span></td></tr>';
				$html .= '</table></div>';
			}
			else
			{
				$style = ($color || $bgcolor) ? (' style="' . ($color ? 'color: ' . $color . ';' : '') . ($bgcolor ? 'background-color: ' . $bgcolor . ';' : '') . '"') : '';
				$html .= '<div class="mg_attachtitle"' . $style . '>' . $lang['Not_Authorized'] . '</div>';
				$html .= '<div class="mg_attachdiv"><div style="text-align: center;">' . $lang['FILE_NOT_AUTH'] . '</div></div>';
			}

			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// LIST
		if(($tag === 'list') || ($tag === 'ul') || ($tag === 'ol'))
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			$extras = $this->allow_styling ? array('style', 'class') : array();
			// check if nested tags are all [*]
			$nested_count = 0;
			for($i = 0; $i < sizeof($item['items']); $i++)
			{
				$tag2 = $item['items'][$i]['tag'];
				if(($tag2 === '*') || ($tag2 === 'li'))
				{
					$nested_count++;
				}
			}
			if(!$nested_count)
			{
				// no <li> items. return error
				return $error;
			}
			// replace "list" with html tag
			if($tag === 'list')
			{
				if(isset($item['params']['param']) || isset($item['params']['type']))
				{
					$tag = 'ol';
				}
				else
				{
					$tag = 'ul';
				}
			}
			// valid tag. process subitems to make sure there are no extra items and remove all code between elements
			$last_item = false;
			for($i = 0; $i < sizeof($item['items']); $i++)
			{
				$item2 = &$item['items'][$i];
				$tag2 = $item2['tag'];
				if(($tag2 === '*') || ($tag2 === 'li'))
				{
					// mark as valid
					$item2['list_valid'] = true;
					if($last_item === false)
					{
						// change start position to end of [list]
						$pos = !empty($pos) ? $pos : 0;
						$pos2 = $item2['start'] + $item2['start_len'];
						$item2['start'] = $pos;
						$item2['start_len'] = $pos2 - $pos;
						$item2['first_entry'] = true;
					}
					$last_item = &$item['items'][$i];
				}
			}
			// generate html
			$html = '<' . $tag;
			if(isset($item['params']['param']))
			{
				$html .= ' type="' . htmlspecialchars($item['params']['param']) . '"';
			}
			elseif(isset($item['params']['type']))
			{
				$html .= ' type="' . htmlspecialchars($item['params']['type']) . '"';
			}
			$html .= $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</li></' . $tag . '>'
			);
		}

		// [*], LI
		if(($tag === '*') || ($tag === 'li'))
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			$extras = $this->allow_styling ? array('style', 'class') : array();
			// if not marked as valid return error
			if(empty($item['list_valid']))
			{
				return $error;
			}
			$html = '<li';
			if(empty($item['first_entry']))
			{
				// add closing tag for previous list entry
				$html = '</li>' . $html;
			}
			$html .= $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '',
			);
		}

		// FONT
		if($tag === 'font')
		{
			$fonts = array(
				'Arial',
				'Arial Black',
				'Comic Sans MS',
				'Courier New',
				'Impact',
				'Lucida Console',
				'Lucida Sans Unicode',
				'Microsoft Sans Serif',
				'Symbol',
				'Tahoma',
				'Times New Roman',
				'Traditional Arabic',
				'Trebuchet MS',
				'Verdana',
				'Webdings',
				'Wingdings'
			);
			if (defined('FONTS_DIR'))
			{
				foreach ($cache->obtain_fonts() as $font_file)
				{
					$fonts[] = substr($font_file, 0, -4);
				}
			}
			$extras = $this->allow_styling ? array('style', 'class') : array();
			$default_param = 'Verdana';
			$font = (isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['font']) ? $item['params']['font'] : $default_param));
			$font = in_array($font, $fonts) ? $font : $default_param;
			$html = '<span style="font-family: \'' . $font . '\';">';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</span>',
			);
		}

		// CELL
		if($tag === 'cell')
		{
			$extras = $this->allow_styling ? array('style', 'class', 'align', 'border') : array('class', 'align');

			$width = (isset($item['params']['width']) ? (' width: ' . intval($item['params']['width']) . 'px;') : '');
			$height = (isset($item['params']['height']) ? (' height: ' . intval($item['params']['height']) . 'px;') : '');
			$padding = (isset($item['params']['padding']) ? (' padding: ' . intval($item['params']['padding']) . 'px;') : '');
			$margin = (isset($item['params']['margin']) ? (' margin: ' . intval($item['params']['margin']) . 'px;') : '');
			$borderwidth = (isset($item['params']['borderwidth']) ? (' border-width: ' . intval($item['params']['borderwidth']) . 'px;') : '');

			$bgcolor = $this->valid_color((isset($item['params']['bgcolor']) ? $item['params']['bgcolor'] : false));
			$bgcolor = (($bgcolor !== false) ? (' background-color: ' . $bgcolor . ';') : '');

			$bordercolor = $this->valid_color((isset($item['params']['bordercolor']) ? $item['params']['bordercolor'] : false));
			$bordercolor = (($bordercolor !== false) ? (' border-color: ' . $bordercolor . ';') : '');

			$color = $this->valid_color((isset($item['params']['color']) ? $item['params']['color'] : false));
			$color = (($color !== false) ? (' color: ' . $color . ';') : '');

			$html = '<div style="' . ($this->allow_styling && isset($item['params']['style']) ? htmlspecialchars($this->valid_style($item['params']['style'], '')) : '') . $height . $width . $bgcolor . $bordercolor . $borderwidth . $color . $padding . $margin . '"' . $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</div>',
			);
		}

		// URL, A
		if(($tag === 'url') || ($tag === 'a'))
		{
			$extras = $this->allow_styling ? array('style', 'class', 'name', 'title') : array('name', 'title');
			$allow_nested = true;
			$strip_text = false;
			$show_content = true;
			$url = '';
			// get url
			if(!empty($item['params']['param']))
			{
				$url = $item['params']['param'];
			}
			elseif(!empty($item['params']['href']))
			{
				$url = $item['params']['href'];
			}
			elseif(!$item['is_html'])
			{
				$url = $content;
				$allow_nested = false;
				$strip_text = true;
			}
			else
			{
				return $error;
			}
			if(($url === $content) && (strlen($content) > 64))
			{
				$content = htmlspecialchars(substr($content, 0, 35) . '...' . substr($content, strlen($content) - 15));
				$show_content = false;
			}
			// check if its email
			if(substr(strtolower($url), 0, 7) === 'mailto:')
			{
				$item['tag'] = 'email';
				return $this->process_tag($item);
			}
			// check for invalid urls
			$url = $this->valid_url($url, '');
			if(empty($url))
			{
				return $error;
			}
			// check nested items
			if(!$allow_nested)
			{
				for($i = 0; $i < sizeof($item['items']); $i++)
				{
					$item['items'][$i]['valid'] = false;
				}
			}
			else
			{
				for($i = 0; $i < sizeof($item['next']); $i++)
				{
					$tag2 = $item['next'][$i]['tag'];
					$is_html = $item['next'][$i]['item']['is_html'];
					$item['next'][$i]['item']['inurl'] = true;
					if($is_html && !$this->allowed_html[$tag2]['inurl'])
					{
						$item['next'][$i]['item']['valid'] = false;
					}
					if(!$is_html && !$this->allowed_bbcode[$tag2]['inurl'])
					{
						$item['next'][$i]['item']['valid'] = false;
					}
				}
			}
			// check for incomplete url
			if(substr(strtolower($url), 0, 4) === 'www.')
			{
				$url = 'http://' . $url;
			}
			// remove extra characters at the end
			$last_char = substr($url, strlen($url) - 1);
			$last_char_i = ord($last_char);
			if((($last_char_i > 32) && ($last_char_i < 47)) || (($last_char_i > 57) && ($last_char_i < 65)))
			{
				$url = substr($url, 0, strlen($url) - 1);
			}

			// check if url is local
			$is_local_url = false;
			if (!empty($local_urls))
			{
				foreach ($local_urls as $local_url)
				{
					if((strlen($url) > strlen($local_url)) && strpos($url, $local_url) === 0)
					{
						$is_local_url = true;
					}
				}
			}
			if(empty($is_local_url) && (strpos($url, ':') === false))
			{
				$is_local_url = true;
			}
			// generate html
			$url_target = ((isset($item['params']['target']) && (($item['params']['target'] != 0) || ($item['params']['target'] != 'false'))) ? true : false);
			$url_class = empty($this->plain_html) ? ' class="post-url"' : '';
			$html = '<a' . ($this->allow_styling && isset($item['params']['class']) ? '' : $url_class) . ' href="' . htmlspecialchars($url) . '"' . (($is_local_url && empty($url_target)) ? '' : (' target="_blank"' . ((!empty($item['params']['nofollow']) || $this->is_sig) ? ' rel="nofollow"' : ''))) . $this->add_extras($item['params'], $extras) . '>';

			if ($config['disable_html_guests'] && !$user->data['session_logged_in'])
			{
				return array(
					'valid' => true,
					'html' => $lang['Links_For_Guests'],
					'allow_nested' => false,
				);
			}
			else
			{
				if($show_content)
				{
					return array(
						'valid' => true,
						'start' => $html,
						'end' => '</a>',
					);
				}
				else
				{
					return array(
						'valid' => true,
						'html' => $html . $content . '</a>',
						'allow_nested' => false,
					);
				}
			}
		}

		// EMAIL
		if($tag === 'email')
		{
			$extras = $this->allow_styling ? array('style', 'class', 'name', 'title') : array('name', 'title');
			$allow_nested = true;
			$strip_text = false;
			$url = '';
			// get url
			if(!empty($item['params']['param']))
			{
				$url = $item['params']['param'];
			}
			elseif(!empty($item['params']['href']))
			{
				$url = $item['params']['href'];
			}
			elseif(!empty($item['params']['addr']))
			{
				$url = $item['params']['addr'];
			}
			else
			{
				$url = $content;
				$pos = strpos($url, '?');
				if($pos)
				{
					$content = substr($url, 0, $pos);
				}
				if(substr(strtolower($url), 0, 7) === 'mailto:')
				{
					$content = substr($content, 7);
				}
				$allow_nested = false;
				$strip_text = true;
			}
			if(empty($url))
			{
				return $error;
			}
			// disable nested items
			for($i = 0; $i < sizeof($item['items']); $i++)
			{
				$item['items'][$i]['valid'] = false;
			}
			// generate html
			if(substr(strtolower($url), 0, 7) === 'mailto:')
			{
				$url = substr($url, 7);
			}
			$email = '<a' . ($this->allow_styling && isset($item['params']['class']) ? '' : ' class="post-email"') . ' href="mailto:' . htmlspecialchars($url) . '"' . $this->add_extras($item['params'], $extras) . '>' . $content . '</a>';
			$pos = strpos($url, '?');
			if($pos)
			{
				$str = substr($url, 0, $pos);
			}
			else
			{
				$str = $url;
			}
			if (!empty($this->plain_html))
			{
				$html = '<a href="mailto:' . htmlspecialchars($url) . '"' . $this->add_extras($item['params'], $extras) . '>' . $content . '</a>';
			}
			else
			{
				if (defined('IN_AJAX_CHAT'))
				{
					$html = htmlspecialchars(str_replace(array('@', '.'), array(' [at] ', ' [dot] '), $str));
				}
				else
				{
					$noscript = '<noscript>' . htmlspecialchars(str_replace(array('@', '.'), array(' [at] ', ' [dot] '), $str)) . '</noscript>';
					// make javascript from it
					$html = BBCODE_NOSMILIES_START . '<script type="text/javascript">' . "\n" . '// <![CDATA[' . "\n";
					$bit_lenght = 5;
					for($i = 0; $i < strlen($email); $i += $bit_lenght)
					{
						$str = substr($email, $i, $bit_lenght);
						//$str = preg_replace('/[^A-Za-z0-9_\-@.]+/', '_', $str);
						$html .= 'document.write(\'' . str_replace('/', '\/', addslashes($str)) . '\');' . "\n";
					}
					$html .= "\n" . '// ]]>' . "\n" . '</script>' . "\n" . $noscript . BBCODE_NOSMILIES_END;
				}
			}
			return array(
				'valid' => true,
				'html' => $html,
				//'html' => $email,
				'allow_nested' => false,
			);
		}

		// QUOTE
		if(($tag === 'quote') || ($tag === 'blockquote') || ($tag === 'ot'))
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if($item['iteration'] > ($config['quote_iterations']))
			{
				return $error;
			}
			// check user
			$target_user = '';
			$post_rev = '';
			if(isset($item['params']['param']))
			{
				$target_user = htmlspecialchars($item['params']['param']);
			}
			elseif(isset($item['params']['user']))
			{
				$target_user = htmlspecialchars($item['params']['user']);
				if(isset($item['params']['userid']) && intval($item['params']['userid']))
				{
					$target_user = '<a href="' . CMS_PAGE_PROFILE . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . intval($item['params']['userid']) . '">' . $target_user . '</a>';
				}
			}
			if (!empty($this->plain_html))
			{
				// generate html
				$html = '<blockquote>';
				if($target_user)
				{
					$html .= '<div class="quote-user">' . $target_user . '&nbsp;' . $lang['wrote'] . ':&nbsp;' . $post_rev . '</div>';
				}
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</blockquote>'
				);
			}
			else
			{
				// generate html
				$html = '<blockquote class="quote"';
				if(isset($item['params']['post']) && intval($item['params']['post']))
				{
					$post_rev = ($user->data['is_bot'] ? '&nbsp;' : ('[<a href="#" onclick="open_postreview(\'show_post.php?p=' . intval($item['params']['post']) . '\'); return false;" class="genmed">' . $lang['ReviewPost'] . '</a>]'));
					$html .= ' cite="'. CMS_PAGE_VIEWTOPIC . '?' . POST_POST_URL . '=' . intval($item['params']['post']) . '#p' . intval($item['params']['post']) . '"';
				}
				$html .= '>';
				if($target_user)
				{
					if ($tag === 'ot')
					{
						$html .= '<div class="quote-user"><div class="error-message" style="display:inline;">' . $lang['OffTopic'] . '</div>&nbsp;' . $target_user . ':&nbsp;' . $post_rev . '</div>';
					}
					else
					{
						$html .= '<div class="quote-user">' . $target_user . '&nbsp;' . $lang['wrote'] . ':&nbsp;' . $post_rev . '</div>';
					}
				}
				else
				{
					if ($tag === 'ot')
					{
						$html .= '<div class="quote-nouser">&nbsp;<div class="error-message" style="display: inline;">' . $lang['OffTopic'] . '</div>:</div>';
					}
					else
					{
						$html .= '<div class="quote-nouser">' . $lang['Quote'] . ':</div>';
					}
				}
				$html .= '<div class="post-text post-text-hide-flow">';
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div></blockquote>'
				);
			}
		}

		// INLINE CODE
		if($tag === 'c')
		{
			$extras = $this->allow_styling ? array('style', 'name') : array('name');
			$html = '<code class="inline"' . $this->add_extras($item['params'], $extras) . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</code>'
			);
		}

		// CODE, CODEBLOCK
		if(($tag === 'code') || ($tag === 'codeblock'))
		{
			if (!empty($this->plain_html))
			{
				if($this->is_sig && !$config['allow_all_bbcode'])
				{
					return $error;
				}
				$text = $this->process_text($content, false, true);
				$html = BBCODE_NOSMILIES_START . '<code>' . $text . '</code>' . BBCODE_NOSMILIES_END;
				return array(
					'valid' => true,
					'html' => $html,
					'allow_nested' => false
				);
			}
			else
			{

				// CODE
				if($tag === 'code')
				{
					if($this->is_sig && !$config['allow_all_bbcode'])
					{
						return $error;
					}
					// replace spaces and tabs with &nbsp;
					if(!defined('EXTRACT_CODE'))
					{
						/*
						$search = array(
							'  ',
							"\t"
						);
						$replace = array(
							'&nbsp; ',
							'&nbsp; &nbsp; '
						);
						$text = str_replace($search, $replace, $this->process_text($content, false, true));
						*/
						$text = $this->process_text($content, false, true);
					}
					else
					{
						$text = $this->process_text($content, false, true);
						$search = array('[highlight]', '[/highlight]');
						$replace = array('', '');
						$text = str_replace($search, $replace, $text);
					}

					// check filename
					if(isset($item['params']['filename']))
					{
						$item['params']['file'] = $item['params']['filename'];
					}
					if(defined('EXTRACT_CODE') && ($this->code_counter == EXTRACT_CODE))
					{
						$GLOBALS['code_text'] = $text;
						if(!empty($item['params']['file']))
						{
							$GLOBALS['code_filename'] = $item['params']['file'];
						}
					}
					if(substr($text, 0, 1) === "\n")
					{
						$text = substr($text, 1);
					}
					elseif(substr($text, 0, 2) === "\r\n")
					{
						$text = substr($text, 2);
					}
					$linenumbers = true;
					if(isset($item['params']['linenumbers']))
					{
						$linenumbers = ($item['params']['linenumbers'] == 'true') ? true : false;
					}

					if ($linenumbers == true)
					{
						// convert to list
						if(isset($item['params']['syntax']))
						{
							if ($item['params']['syntax'] == 'php')
							{
								/*
								$html = strtr($text, array_flip(get_html_translation_table(HTML_ENTITIES)));
								$html = highlight_string($html, true);
								$html_search = array('<font color="', '</font', '&nbsp;');
								$xhtml_replace = array('<code style="color:', '</code', ' ');
								//$xhtml_replace = array('<div style="display:inline;color:', '</div', ' ');
								//$xhtml_replace = array('<span style="display:inline;color:', '</span', ' ');
								$html = str_replace ($html_search, $xhtml_replace, $html);
								$html = '<li class="code-row"><div class="code-row-text">' . $html . '</div></li>';
								*/
								/*
								$html_search = array('<br />');
								$xhtml_replace = array('</div></li><li class="code-row"><div class="code-row-text">');
								$html = str_replace ($html_search, $xhtml_replace, $html);
								*/

								//PHP Highlight - Start
								$code_ary = explode("\n", $text);

								$open_php_tag = 0;
								$close_php_tag = 0;
								for ($i = 0; $i < sizeof($code_ary); $i++)
								{
									if (($code_ary[$i] == '') || ($code_ary[$i] == ' ') || ($code_ary[$i] == '&nbsp;') || ($code_ary[$i] == "\n") || ($code_ary[$i] == "\r") || ($code_ary[$i] == "\n\r"))
									{
										$html .= '<li class="code-row"><span class="code-row-text">&nbsp;&nbsp;</span></li>';
									}
									else
									{
										$prefix = (strpos(' ' . $code_ary[$i], '&lt;?')) ? '' : '<?php ';
										$suffix = (strpos(' ' . $code_ary[$i], '?&gt;')) ? '' : '?>';

										$code_ary[$i] = str_replace(array('&lt;', '&gt;'), array('<', '>'), $code_ary[$i]);
										$code_ary[$i] = highlight_string(strtr($prefix . $code_ary[$i] . $suffix, array_flip(get_html_translation_table(HTML_ENTITIES))), true);

										$html_search = array('<code>', '</code>');
										$xhtml_replace = array('', '');
										$code_ary[$i] = str_replace($html_search, $xhtml_replace, $code_ary[$i]);

										if ($open_php_tag || ($prefix != ''))
										{
											$html_search = array('&lt;?php');
											$xhtml_replace = array('');
											$code_ary[$i] = str_replace($html_search, $xhtml_replace, $code_ary[$i]);
										}

										if ($close_php_tag || ($suffix != ''))
										{
											$html_search = array('?&gt;&nbsp;', '?&gt;');
											$xhtml_replace = array('', '');
											$code_ary[$i] = str_replace($html_search, $xhtml_replace, $code_ary[$i]);
										}

										($prefix == '') ? $open_php_tag++ : (($open_php_tag) ? $open_php_tag-- : '');
										($suffix == '') ? $close_php_tag++ : (($close_php_tag) ? $close_php_tag-- : '');

										$html .= '<li class="code-row"><span class="code-row-text">' . $code_ary[$i] . '&nbsp;</span></li>';
									}
								}

								$html_search = array('<font color="', '</font', '&nbsp;', '<code style="color:#0000BB"></code>', '<code style="color:#0000BB"> </code>', '>  <');
								$xhtml_replace = array('<code style="color:', '</code', ' ', '', '', '>&nbsp;<');
								$html = str_replace($html_search, $xhtml_replace, $html);
								//PHP Highlight - End
							}
							else
							{
								$search = array("\n", '[highlight]', '[/highlight]');
								$replace = array('&nbsp;</span></li><li class="code-row"><span class="code-row-text">', '<span class="code-row-highlight">', '</span>');
								$html = '<li class="code-row code-row-first"><span class="code-row-text">' . str_replace($search, $replace, $text) . '&nbsp;</span></li>';
							}
						}
						else
						{
							$search = array("\n", '[highlight]', '[/highlight]');
							$replace = array('&nbsp;</span></li><li class="code-row"><span class="code-row-text">', '<span class="code-row-highlight">', '</span>');
							$html = '<li class="code-row code-row-first"><span class="code-row-text">' . str_replace($search, $replace, $text) . '&nbsp;</span></li>';
						}

						$str = '<li class="code-row"><div class="code-row-text">&nbsp;</div></li>';
						if(substr($html, strlen($html) - strlen($str)) === $str)
						{
							$html = substr($html, 0, strlen($html) - strlen($str));
						}
						$start = isset($item['params']['start']) ? intval($item['params']['start']) : 1;
						$can_download = !empty($this->code_post_id) ? $this->code_post_id : 0;
						if($can_download)
						{
							//$download_text = ' [<a href="download.php?post=' . $can_download;
							$download_text = ' [<a href="download_post.' . PHP_EXT . '?post=' . $can_download;
							if($this->code_counter)
							{
								$download_text .= '&amp;item=' . $this->code_counter;
							}
							$download_text .= '">' . $lang['Download'] . '</a>]';
						}
						else
						{
							$download_text = '';
						}
						$code_id = substr(md5($content . mt_rand()), 0, 8);
						$str = BBCODE_NOSMILIES_START . '<div class="code">';
						$str .= '<div class="code-header" id="codehdr2_' . $code_id . '" style="position: relative;">' . $lang['Code'] . ':' . (empty($item['params']['file']) ? '' : ' (' . htmlspecialchars($item['params']['file']) . ')') . $download_text . ' [<a href="#" onclick="ShowHide(\'code_' . $code_id . '\',\'code2_' . $code_id . '\',\'\'); ShowHide(\'codehdr_' . $code_id . '\', \'codehdr2_' . $code_id . '\', \'\'); return false;">' . $lang['Hide'] . '</a>]</div>';
						$str .= '<div class="code-header" id="codehdr_' . $code_id . '" style="position: relative; display: none;">' . $lang['Code'] . ':' . (empty($item['params']['file']) ? '' : ' (' . htmlspecialchars($item['params']['file']) . ')') . $download_text . ' [<a href="#" onclick="ShowHide(\'code_' . $code_id . '\',\'code2_' . $code_id . '\',\'\'); ShowHide(\'codehdr_' . $code_id . '\',\'codehdr2_' . $code_id . '\',\'\'); return false;">' . $lang['Show'] . '</a>]</div>';
						$html = $str . '<div class="code-content" id="code_' . $code_id . '" style="position: relative;"><ol class="code-list" start="' . $start . '">' . $html . '</ol></div></div>' . BBCODE_NOSMILIES_END;
						// check highlight
						// format: highlight="1,2,3-10"
						if(isset($item['params']['highlight']))
						{
							$search = '<li class="code-row';
							$replace = '<li class="code-row code-row-highlight';
							$search_len = strlen($search);
							$replace_len = strlen($replace);
							// get highlight string
							$items = array();
							$str = $item['params']['highlight'];
							$list = explode(',', $str);
							for($i = 0; $i < sizeof($list); $i++)
							{
								$str = trim($list[$i]);
								if(strpos($str, '-'))
								{
									$row = explode('-', $str);
									if(sizeof($row) == 2)
									{
										$num1 = intval($row[0]);
										if($num1 == 0)
										{
											$num1 = 1;
										}
										$num2 = intval($row[1]);
										if($num1 > 0 && $num2 > $num1 && ($num2 - $num1) < 256)
										{
											for($j=$num1; $j<=$num2; $j++)
											{
												$items['row' . $j] = true;
											}
										}
									}
								}
								else
								{
									$num = intval($str);
									if($num)
									{
										$items['row' . $num] = true;
									}
								}
							}
							if(sizeof($items))
							{
								// process all lines
								$num = $start - 1;
								$pos = strpos($html, $search);
								$total = sizeof($items);
								$found = 0;
								while($pos !== false)
								{
									$num++;
									if(isset($items['row' . $num]))
									{
										$found++;
										$html = substr($html, 0, $pos) . $replace . substr($html, $pos + $search_len);
										$pos += $replace_len;
									}
									else
									{
										$pos += $search_len;
									}
									$pos = $found < $total ? strpos($html, $search, $pos) : false;
								}
							}
						}
						// $html = BBCODE_NOSMILIES_START . '<div class="code"><div class="code-header">Code:</div><div class="code-content">' . $text . '</div></div>' . BBCODE_NOSMILIES_END;
						$this->code_counter++;
						return array(
							'valid' => true,
							'html' => $html,
							'allow_nested' => false
						);
					}
					else
					{
						$syntax_highlight = false;
						if(isset($item['params']['syntax']))
						{
							if ($item['params']['syntax'] == 'php')
							{
								$html = strtr($text, array_flip(get_html_translation_table(HTML_ENTITIES)));
								$html = highlight_string($html, true);
								$html_search = array('<code>', '</code>', '<font color="', '</font', '&nbsp;', '<code style="color:#0000BB"></code>', '<code style="color:#0000BB"> </code>');
								$xhtml_replace = array('', '', '<code style="color:', '</code', ' ', '', '');
								$html = str_replace ($html_search, $xhtml_replace, $html);
								$syntax_highlight = true;
							}
						}
						if ($syntax_highlight == false)
						{
							$html = $text;
							$search = array('[highlight]', '[/highlight]');
							$replace = array('</span><span class="code-row code-row-highlight">', '</span><span class="code-row-text">');
							$html = str_replace($search, $replace, $html);
							$html = str_replace(array("\n", "\r\n"), array("<br />\n", "<br />\r\n"), $html);
						}

						$can_download = !empty($this->code_post_id) ? $this->code_post_id : 0;
						if($can_download)
						{
							$download_text = ' [<a href="download_post.' . PHP_EXT . '?post=' . $can_download;
							if($this->code_counter)
							{
								$download_text .= '&amp;item=' . $this->code_counter;
							}
							$download_text .= '">' . $lang['Download'] . '</a>]';
						}
						else
						{
							$download_text = '';
						}
						$code_id = substr(md5($content . mt_rand()), 0, 8);
						$str = BBCODE_NOSMILIES_START . '<div class="code">';
						$str .= '<div class="code-header" id="codehdr2_' . $code_id . '" style="position: relative;">' . $lang['Code'] . ':' . (empty($item['params']['file']) ? '' : ' (' . htmlspecialchars($item['params']['file']) . ')') . $download_text . ' [<a href="#" onclick="ShowHide(\'code_' . $code_id . '\',\'code2_' . $code_id . '\',\'\'); ShowHide(\'codehdr_' . $code_id . '\',\'codehdr2_' . $code_id . '\',\'\'); return false;">' . $lang['Hide'] . '</a>] [<a href="#" onclick="select_text(\'code_' . $code_id . '\'); return false;">' . $lang['Select'] . '</a>]</div>';
						$str .= '<div class="code-header" id="codehdr_' . $code_id . '" style="position: relative; display: none;">' . $lang['Code'] . ':' . (empty($item['params']['file']) ? '' : ' (' . htmlspecialchars($item['params']['file']) . ')') . $download_text . ' [<a href="#" onclick="ShowHide(\'code_' . $code_id . '\',\'code2_' . $code_id . '\',\'\'); ShowHide(\'codehdr_' . $code_id . '\',\'codehdr2_' . $code_id . '\',\'\'); return false;">' . $lang['Show'] . '</a>]</div>';
						$html = $str . '<div class="code-content" id="code_' . $code_id . '" style="position: relative;"><span class="code-row-text">' . $html . '</span></div></div>' . BBCODE_NOSMILIES_END;

						$this->code_counter++;
						return array(
							'valid' => true,
							'html' => $html,
							'allow_nested' => false
						);
					}
				}

				// CODEBLOCK
				if($tag === 'codeblock')
				{
					if($this->is_sig && !$config['allow_all_bbcode'])
					{
						return $error;
					}
					if(!defined('EXTRACT_CODE'))
					{
						/*
						$search = array(
							'  ',
							"\t"
						);
						$replace = array(
							'&nbsp; ',
							'&nbsp; &nbsp; '
						);
						$text = str_replace($search, $replace, $this->process_text($content, false, true));
						*/
						$text = $this->process_text($content, false, true);
					}
					else
					{
						$text = $this->process_text($content, false, true);
						$search = array('[highlight]', '[/highlight]');
						$replace = array('', '');
						$text = str_replace($search, $replace, $text);
					}
					// check filename
					if(isset($item['params']['filename']))
					{
						$item['params']['file'] = $item['params']['filename'];
					}
					if(defined('EXTRACT_CODE') && $this->code_counter == EXTRACT_CODE)
					{
						$GLOBALS['code_text'] = $text;
						if(!empty($item['params']['file']))
						{
							$GLOBALS['code_filename'] = $item['params']['file'];
						}
					}
					if(substr($text, 0, 1) === "\n")
					{
						$text = substr($text, 1);
					}
					elseif(substr($text, 0, 2) === "\r\n")
					{
						$text = substr($text, 2);
					}

					$syntax_highlight = false;
					if(isset($item['params']['syntax']))
					{
						if ($item['params']['syntax'] == 'php')
						{
							$html = strtr($text, array_flip(get_html_translation_table(HTML_ENTITIES)));
							$html = highlight_string($html, true);
							$html_search = array('<code>', '</code>', '<font color="', '</font', '&nbsp;', '<code style="color:#0000BB"></code>', '<code style="color:#0000BB"> </code>');
							$xhtml_replace = array('', '', '<code style="color:', '</code', ' ', '', '');
							$html = str_replace ($html_search, $xhtml_replace, $html);
							$syntax_highlight = true;
						}
					}
					if ($syntax_highlight == false)
					{
						$html = $text;
						$search = array('[highlight]', '[/highlight]');
						$replace = array('</span><span class="code-row code-row-highlight">', '</span><span class="code-row-text">');
						$html = str_replace($search, $replace, $html);
						$html = str_replace(array("\n", "\r\n"), array("<br />\n", "<br />\r\n"), $html);
					}

					$can_download = !empty($this->code_post_id) ? $this->code_post_id : 0;
					if($can_download)
					{
						$download_text = ' [<a href="download_post.' . PHP_EXT . '?post=' . $can_download;
						if($this->code_counter)
						{
							$download_text .= '&amp;item=' . $this->code_counter;
						}
						$download_text .= '">' . $lang['Download'] . '</a>]';
					}
					else
					{
						$download_text = '';
					}
					$code_id = substr(md5($content . mt_rand()), 0, 8);
					$str = BBCODE_NOSMILIES_START . '<div class="code">';
					$str .= '<div class="code-header" id="codehdr2_' . $code_id . '" style="position: relative;">' . $lang['Code'] . ':' . (empty($item['params']['file']) ? '' : ' (' . htmlspecialchars($item['params']['file']) . ')') . $download_text . ' [<a href="#" onclick="ShowHide(\'code_' . $code_id . '\',\'code2_' . $code_id . '\',\'\'); ShowHide(\'codehdr_' . $code_id . '\',\'codehdr2_' . $code_id . '\',\'\'); return false;">' . $lang['Hide'] . '</a>] [<a href="#" onclick="select_text(\'code_' . $code_id . '\'); return false;">' . $lang['Select'] . '</a>]</div>';
					$str .= '<div class="code-header" id="codehdr_' . $code_id . '" style="position: relative; display: none;">' . $lang['Code'] . ':' . (empty($item['params']['file']) ? '' : ' (' . htmlspecialchars($item['params']['file']) . ')') . $download_text . ' [<a href="#" onclick="ShowHide(\'code_' . $code_id . '\',\'code2_' . $code_id . '\',\'\'); ShowHide(\'codehdr_' . $code_id . '\',\'codehdr2_' . $code_id . '\',\'\'); return false;">' . $lang['Show'] . '</a>]</div>';
					$html = $str . '<div class="code-content" id="code_' . $code_id . '" style="position: relative;"><span class="code-row-text">' . $html . '</span></div></div>' . BBCODE_NOSMILIES_END;

					$this->code_counter++;
					return array(
						'valid' => true,
						'html' => $html,
						'allow_nested' => false
					);
				}

			}
		}

		// HIDE
		if($tag === 'hide')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			if($item['iteration'] > 1)
			{
				return $error;
			}
			$show = false;
			if(defined('IS_ICYPHOENIX') && $user->data['session_logged_in'])
			{
				if (($user->data['user_level'] == ADMIN) || ($user->data['user_level'] == MOD))
				{
					$show = true;
				}
				else
				{
					$sql = "SELECT p.poster_id, p.topic_id
						FROM " . POSTS_TABLE . " p
						WHERE p.topic_id = " . intval($topic_id) . "
						AND p.poster_id = " . $user->data['user_id'];
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if ($result)
					{
						$show = $db->sql_numrows($result) ? true : false;
						$db->sql_freeresult($result);
					}

					$sql = "SELECT *
						FROM " . POSTS_LIKES_TABLE . "
						WHERE topic_id = " . intval($topic_id) . "
						AND user_id = " . $user->data['user_id'];
					$db->sql_return_on_error(true);
					$result = $db->sql_query($sql);
					$db->sql_return_on_error(false);
					if ($result)
					{
						$show = ($db->sql_numrows($result) || ($show == true))? true : false;
						$db->sql_freeresult($result);
					}
				}
			}
			// generate html
			$html = '<blockquote class="quote"><div class="quote-nouser">' . $lang['xs_bbc_hide_message'] . ':</div><div class="post-text post-text-hide-flow">';
			if(!$show)
			{
				return array(
					'valid' => true,
					'html' => $html . $lang['xs_bbc_hide_message_explain'] . '</div></blockquote>',
					'allow_nested' => false,
				);
			}
			else
			{
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div></blockquote>'
				);
			}
		}

		// SPOILER
		if($tag === 'spoiler')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			if($item['iteration'] > 1)
			{
				return $error;
			}
			$spoiler_id = substr(md5($content . mt_rand()), 0, 8);
			$str = '<div class="spoiler">';
			$str .= '<div class="code-header" id="spoilerhdr_' . $spoiler_id . '" style="position: relative;">' . $lang['bbcb_mg_spoiler'] . ': [ <a href="#" onclick="ShowHide(\'spoiler_' . $spoiler_id . '\', \'spoiler2_' . $spoiler_id . '\', \'\'); ShowHide(\'spoilerhdr_' . $spoiler_id . '\', \'spoilerhdr2_' . $spoiler_id . '\', \'\'); return false;">' . $lang['Show'] . '</a> ]</div>';
			$str .= '<div class="code-header" id="spoilerhdr2_' . $spoiler_id . '" style="position: relative; display: none;">' . $lang['bbcb_mg_spoiler'] . ': [ <a href="#" onclick="ShowHide(\'spoiler_' . $spoiler_id . '\', \'spoiler2_' . $spoiler_id . '\', \'\'); ShowHide(\'spoilerhdr_' . $spoiler_id . '\', \'spoilerhdr2_' . $spoiler_id . '\', \'\'); return false;">' . $lang['Hide'] . '</a> ]</div>';
			$str .= '<div class="spoiler-content" id="spoiler2_' . $spoiler_id . '" style="position: relative; display: none;">' . $html;
			return array(
				'valid' => true,
				'start' => $str,
				'end' => '</div></div>'
			);
		}

		// USER
		// Insert the username and avatar for the selected id
		if($tag === 'user')
		{
			if($this->is_sig) return $error;
			if (!empty($this->plain_html)) return $error;

			if(isset($item['params']['param']))
			{
				$bb_userid = (int) $item['params']['param'];
			}
			else
			{
				$bb_userid = (int) $content;
			}

			if ($bb_userid < 2)
			{
				return $error;
			}

			$bb_user_data = get_userdata($bb_userid);
			if (empty($bb_user_data))
			{
				return $error;
			}

			$bb_name_link = colorize_username($bb_user_data['user_id'], $bb_user_data['username'], $bb_user_data['user_color'], $bb_user_data['user_active']);
			$bb_avatar_img = user_get_avatar($bb_user_data['user_id'], $bb_user_data['user_level'], $bb_user_data['user_avatar'], $bb_user_data['user_avatar_type'], $bb_user_data['user_allowavatar'], '', 30);

			$html = $bb_avatar_img . ' ' . $bb_name_link;
			return array(
				'valid' => true,
				'html' => $html
			);
		}

		// LANGVAR
		// Insert the content of a lang var into post... maybe we need to filter something?
		if($tag === 'langvar')
		{
			if(isset($item['params']['param']))
			{
				$langvar = $item['params']['param'];
			}
			else
			{
				$langvar = $content;
			}
			$html = (isset($lang[$langvar]) ? $lang[$langvar] : '');
			return array(
				'valid' => true,
				'html' => $html
			);
		}

		// LANGUAGE
		// Parse the content only if in the same language of the user viewing it!!!
		if($tag === 'language')
		{
			$language = '';
			if(isset($item['params']['param']))
			{
				$language = $item['params']['param'];
			}
			$content = ($config['default_lang'] != $language) ? '' : $content;
			// We need this trick to process BBCodes withing language BBCode
			if(empty($content))
			{
				return array(
					'valid' => true,
					'html' => '',
				);
			}
			else
			{
				return array(
					'valid' => true,
					'start' => '',
					'end' => ''
				);
			}
		}

		// SEARCH
		if($tag === 'search')
		{
			if(empty($content)) return $error;
			if (!empty($this->plain_html)) return $error;

			$str = '<a href="' . CMS_PAGE_SEARCH . '?search_keywords=' . urlencode($this->process_text($content)) . '">';
			return array(
				'valid' => true,
				'start' => $str,
				'end' => '</a>'
			);
		}

		// TAG
		if($tag === 'tag')
		{
			if(empty($content)) return $error;
			if (!empty($this->plain_html)) return $error;

			$str = '<a href="tags.' . PHP_EXT . '?tag_text=' . urlencode($this->process_text($content)) . '">';
			return array(
				'valid' => true,
				'start' => $str,
				'end' => '</a>'
			);
		}

		// Random number or quote (quote not implemented yet)
		if($tag === 'random')
		{
			$max_n = 6;
			$max_n = intval((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['max']) ? $item['params']['max'] : 6)));
			$max_n = ($max_n <= 0) ? 6 : $max_n;
			/*
			include_once(IP_ROOT_PATH . 'language/lang_' . $config['default_lang'] . '/lang_randomquote.' . PHP_EXT);
			$randomquote_phrase = $randomquote[rand(0, sizeof($randomquote) - 1)];
			*/
			$html = rand(1, $max_n);
			return array(
				'valid' => true,
				'html' => $html
			);
		}

		// MARQUEE
		if($tag === 'marquee')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			$extras = $this->allow_styling ? array('style', 'class') : array();

			$directions_array = array('up', 'right', 'down', 'left');
			$default_param = 'right';
			$direction = (isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['direction']) ? $item['params']['direction'] : $default_param));
			$direction = (in_array($direction, $directions_array) ? $direction : $default_param);

			$default_scroll = '120';
			$scrolldelay = (isset($item['params']['scrolldelay']) ? intval($item['params']['scrolldelay']) : $default_scroll);
			$scrolldelay = ((($scrolldelay > 10) && ($scrolldelay < 601)) ? $scrolldelay : $default_scroll);

			$default_behavior = 'scroll';
			$behavior = (isset($item['params']['behavior']) ? intval($item['params']['behavior']) : $default_behavior);
			$behavior = ((($behavior === 'alternate') || ($behavior === 'slide')) ? $behavior : $default_behavior);

			$html = '<marquee behavior="' . $behavior . '" direction="' . $direction . '" scrolldelay="' . $scrolldelay . '" loop="true" onmouseover="this.stop()" onmouseout="this.start()">';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</marquee>',
			);
		}

		// Active Content - BEGIN
		// Added by Tom XS2 Build 054
		if ($config['switch_bbcb_active_content'] == 1)
		{
			// FLASH, SWF, FLV, VIDEO, REAL, QUICK, STREAM, EMFF, VIMEO, YOUTUBE, GOOGLEVIDEO
			if(($tag === 'flash') || ($tag === 'swf') || ($tag === 'flv') || ($tag === 'video') || ($tag === 'ram') || ($tag === 'quick') || ($tag === 'stream') || ($tag === 'emff') || ($tag === 'mp3') || ($tag === 'vimeo') || ($tag === 'youtube') || ($tag === 'googlevideo'))
			{
				if($this->is_sig && !$config['allow_all_bbcode'])
				{
					return $error;
				}
				$content = $this->process_text(isset($item['params']['param']) ? $item['params']['param'] : $content);

				$color_1 = $this->valid_color((isset($item['params']['colors']) ? $item['params']['colors'] : false));
				$color_2 = $this->valid_color((isset($item['params']['colore']) ? $item['params']['colore'] : false));

				// 4/3 YouTube width and height: 425x350
				// 16/9 YouTube width and height: 640x385
				$default_width = 320;
				$default_height = 240;
				$default_ratio = $default_width / $default_height;
				if (($tag === 'vimeo') || ($tag === 'youtube') || ($tag === 'googlevideo'))
				{
					$default_width = 640;
					$default_height = 385;
					$default_ratio = 16 / 9;
				}

				$width_array = array(240, 270, 300, 320, 400, 425, 426, 480, 540, 600, 640, 720, 854);
				$height_array = array(135, 152, 169, 180, 202, 203, 225, 240, 270, 300, 304, 319, 320, 337, 338, 360, 405, 420, 450, 480, 540, 640);

				// Allows both WIDTH and W
				if (isset($item['params']['width']) || isset($item['params']['w']))
				{
					$item['params']['width'] = isset($item['params']['width']) ? $item['params']['width'] : $item['params']['w'];
				}
				$width = (isset($item['params']['width']) ? intval($item['params']['width']) : $default_width);
				$width = ((($width > 10) && ($width < 855)) ? $width : $default_width);

				// Allows both HEIGHT and H
				if (isset($item['params']['height']) || isset($item['params']['h']))
				{
					$item['params']['height'] = isset($item['params']['height']) ? $item['params']['width'] : $item['params']['h'];
				}
				$height = (isset($item['params']['height']) ? intval($item['params']['height']) : $default_height);
				$height = ((($height > 10) && ($height < 641)) ? $height : $default_height);

				// Width and Height check-in...
				// 4/3 YouTube width and height: 425x350
				// 16/9 YouTube width and height: 640x385
				if (($tag === 'vimeo') || ($tag === 'youtube') || ($tag === 'googlevideo'))
				{
					// Consider that YouTube HTML code may need 25px extra in height to display the control bar... hence the edited code below!
					$width = in_array($width, $width_array) ? $width : 640;
					$height = (in_array($height, $height_array) || in_array($height + 25, $height_array)) ? $height : 385;
				}

				// Set Ratio
				if (isset($item['params']['ratio']) || isset($item['params']['r']))
				{
					$item['params']['ratio'] = isset($item['params']['ratio']) ? $item['params']['ratio'] : $item['params']['r'];
					$ratio = (isset($item['params']['ratio']) ? floatval($item['params']['ratio']) : $default_ratio);
					if (($ratio > 1) && ($ratio < 2))
					{
						$height = round($width / $ratio, 0);
						if (($tag === 'vimeo') || ($tag === 'youtube') || ($tag === 'googlevideo'))
						{
							// Adds extra height for the control bar... to be checked for futures updates...
							$height = $height + 25;
						}
					}
				}

				if (($tag === 'flash') || ($tag === 'swf'))
				{
					$html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="//download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="' . $width . '" height="' . $height . '"><param name="movie" value="' . $content . '"><param name="quality" value="high"><param name="scale" value="noborder"><param name="wmode" value="transparent"><param name="bgcolor" value="#000000"><embed src="' . $content . '" quality="high" scale="noborder" wmode="transparent" bgcolor="#000000" width="' . $width . '" height="' . $height . '" type="application/x-shockwave-flash" pluginspage="//www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed></object>';
				}
				elseif ($tag === 'flv')
				{
					$html = '<object type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '" wmode="transparent" data="flv_player.swf?file=' . $content . '&amp;autoStart=false"><param name="movie" value="flv_player.swf?file=' . $content . '&amp;autoStart=false"/><param name="wmode" value="transparent"/></object>';
				}
				elseif ($tag === 'video')
				{
					$html = '<div align="center"><embed src="' . $content . '" width="' . $width . '" height="' . $height . '" autostart="false"></embed></div>';
				}
				elseif ($tag === 'ram')
				{
					$html = '<div align="center"><embed src="' . $content . '" align="center" width="275" height="40" type="audio/x-pn-realaudio-plugin" console="cons" controls="ControlPanel" autostart="false"></embed></div>';
				}
				elseif ($tag === 'quick')
				{
					$html = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="//www.apple.com/qtactivex/qtplugin.cab#version=6,0,2,0" width="' . $width . '" height="' . $height . '" align="middle"><param name="controller" value="true"><param name="type" value="video/quicktime"><param name="autoplay" value="true"><param name="target" value="myself"><param name="src" value="' . $content . '"><param name="pluginspage" value="//www.apple.com/quicktime/download/indext.html"><param name="kioskmode" value="true"><embed src="' . $content . '" width="' . $width . '" height="' . $height . '" align="middle" kioskmode="true" controller="true" target="myself" type="video/quicktime" border="0" pluginspage="//www.apple.com/quicktime/download/indext.html"></embed></object>';
				}
				elseif ($tag === 'stream')
				{
					$html = '<object id="wmp" width="' . $width . '" height="' . $height . '" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="//activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject"><param name="FileName" value="' . $content . '"><param name="ShowControls" value="1"><param name="ShowDisplay" value="0"><param name="ShowStatusBar" value="1"><param name="AutoSize" value="1"><embed type="application/x-mplayer2" pluginspage="//www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="' . $content . '" name="MediaPlayer2" showcontrols="1" showdisplay="0" showstatusbar="1" autosize="1" visible="1" animationatstart="0" transparentatstart="1" loop="0" height="70" width="300"></embed></object>';
				}
				elseif (($tag === 'emff') || ($tag === 'mp3'))
				{
					$html = '<object data="emff_player.swf" type="application/x-shockwave-flash" width="200" height="55" align="top" ><param name="FlashVars" value="src=' . $content . '" /><param name="movie" value="emff_player.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#f8f8f8" /></object>';
				}
				elseif ($tag === 'vimeo')
				{
					$html = '<object type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '" data="//www.vimeo.com/moogaloop.swf?clip_id=' . $content . '"><param name="quality" value="best" /><param name="allowfullscreen" value="true" /><param name="scale" value="showAll" /><param name="movie" value="//www.vimeo.com/moogaloop.swf?clip_id=' . $content . '" /></object><br /><a href="//www.vimeo.com/moogaloop.swf?clip_id=' . $content . '" target="_blank">Link</a><br />';
				}
				elseif ($tag === 'youtube')
				{
					//check URL type
					$video_file = $content;
					if (strpos($content, 'youtu.be') !== false)
					{
						// Short URL
						// parse the URL to split it in parts
						$parsed_url = parse_url($content);
						// get the path and delete the initial / simbol
						$video_file = str_replace('/', '', $parsed_url['path']);
					}
					elseif (strrpos($content, 'youtube') !== false)
					{
						// Long URL
						// parse the URL to split it in parts
						$parsed_url = parse_url($content);
						// get the query part (vars) and parse them into name and value
						parse_str($parsed_url['query'], $qvars);
						// send the value to the destination var.
						$video_file = $qvars['v'];
					}
					$video_file = preg_replace('/[^A-Za-z0-9_-]+/', '', $video_file);

					$color_append = '';
					if ($color_1 || $color_2)
					{
						$color_append .= ($color_1 ? ('&amp;color1=0x' . str_replace('#', '', $color_1)) : '');
						$color_append .= ($color_2 ? ('&amp;color2=0x' . str_replace('#', '', $color_2)) : '');
					}
					$video_link = '<br /><a href="//youtube.com/watch?v=' . $video_file . $color_append . '" target="_blank">YouTube Link</a><br />';
					// OLD OBJECT Version
					//$html = '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="//www.youtube.com/v/' . $video_file . $color_append . '" /><embed src="//www.youtube.com/v/' . $video_file . $color_append . '" type="application/x-shockwave-flash" width="' . $width . '" height="' . $height . '"></embed></object>' . $video_link;
					// IFRAME Version
					$html = '<iframe width="' . $width . '" height="' . $height . '" src="//www.youtube.com/embed/' . $video_file . '?autoplay=0' . $color_append . '" frameborder="0"></iframe>' . $video_link;
				}
				elseif ($tag === 'googlevideo')
				{
					$html = '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="//video.google.com/googleplayer.swf?docId=' . $content . '"></param><embed style="width:' . $width . 'px; height:' . $height . 'px;" id="VideoPlayback" align="middle" type="application/x-shockwave-flash" src="//video.google.com/googleplayer.swf?docId=' . $content . '" allowScriptAccess="sameDomain" quality="best" bgcolor="#f8f8f8" scale="noScale" salign="TL" FlashVars="playerMode=embedded"></embed></object><br /><a href="//video.google.com/videoplay?docid=' . $content . '" target="_blank">Link</a><br />';
				}
				return array(
					'valid' => true,
					'html' => $html
				);
			}
		}
		// Active Content - END

		// SMILEY
		if($tag === 'smiley')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			$extras = $this->allow_styling ? array('style', 'class') : array();

			$text = htmlspecialchars((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['text']) ? $item['params']['text'] : $content)));

			if(isset($item['params']['smilie']))
			{
				if (($item['params']['smilie'] == 'standard') || ($item['params']['smilie'] == 'random'))
				{
					//$smilie = $item['params']['smilie'];
					$smilie = '1';
				}
				else
				{
					$smilie = intval($item['params']['smilie']);
				}
			}
			else
			{
				$smilie = '1';
			}

			$default_fontcolor = '000000';
			$fontcolor = $this->valid_color((isset($item['params']['fontcolor']) ? $item['params']['fontcolor'] : $default_fontcolor));
			$fontcolor = (($fontcolor === false) ? $default_fontcolor : str_replace('#', '', $fontcolor));

			$default_shadowcolor = '888888';
			$shadowcolor = $this->valid_color((isset($item['params']['shadowcolor']) ? $item['params']['shadowcolor'] : $default_shadowcolor));
			$shadowcolor = (($shadowcolor === false) ? $default_shadowcolor : str_replace('#', '', $shadowcolor));

			$default_shieldshadow = 0;
			$shieldshadow = (isset($item['params']['shieldshadow']) ? (($item['params']['shieldshadow'] == 1) ? 1 : $default_param) : $default_param);

			//$html = '<img src="text2shield.' . PHP_EXT . '?smilie=' . $smilie . '&amp;fontcolor=' . $fontcolor . '&amp;shadowcolor=' . $shadowcolor . '&amp;shieldshadow=' . $shieldshadow . '&amp;text=' . $text . '" alt="Smiley" title="Smiley" />';
			$html = '<img src="text2shield.' . PHP_EXT . '?smilie=' . $smilie . '&amp;fontcolor=' . $fontcolor . '&amp;shadowcolor=' . $shadowcolor . '&amp;shieldshadow=' . $shieldshadow . '&amp;text=' . urlencode(ip_utf8_decode($text)) . '" alt="'. $text . '" title="' . $text . '" />';
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// OPACITY
		if($tag === 'opacity')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			if(isset($item['params']['param']))
			{
				$opacity = intval($item['params']['param']);
				if (($opacity > 0) && ($opacity < 101))
				{
					$opacity = $opacity;
				}
			}
			else
			{
				$opacity = '100';
			}
			$opacity_dec = $opacity / 100;
			$html = '<div class="opacity" style="opacity: ' . $opacity_dec . '; filter: Alpha(Opacity=' . $opacity . ');">';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</div>',
			);
		}

		// FADE
		if($tag === 'fade')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			if(isset($item['params']['param']))
			{
				$opacity = intval($item['params']['param']);
				if (($opacity > 0) && ($opacity < 101))
				{
					$opacity = $opacity;
				}
			}
			else
			{
				$opacity = '100';
			}
			$opacity_dec = $opacity / 100;
			$html = '<div style="display: inline; height: 1; opacity: ' . $opacity_dec . '; filter: Alpha(Opacity=' . $opacity . ',FinishOpacity=0,Style=1,StartX=0,FinishX=100%);">';
			//$html = '<div style="display:inline;height:1;filter:Alpha(Opacity=' . $opacity . ',FinishOpacity=0,Style=1,StartX=0,FinishX=100%);">';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</div>',
			);
		}

		// IE AND HTML 4 ONLY TAGS - BEGIN
		// Let's add a global IF so we can skip them all in once to speed up things...
		// Enable these tags only if you know how to make them work...
		if(($tag === 'glow') || ($tag === 'shadow') || ($tag === 'blur') || ($tag === 'wave') || ($tag === 'fliph') || ($tag === 'flipv'))
		{
			return array(
				'valid' => true,
				'start' => '',
				'end' => '',
			);
		}
		/*
		if(($tag === 'glow') || ($tag === 'shadow') || ($tag === 'blur') || ($tag === 'wave') || ($tag === 'fliph') || ($tag === 'flipv'))
		{
			// GLOW
			if($tag === 'glow')
			{
				$default_color = '#fffffa';
				$color = $this->valid_color((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['color']) ? $item['params']['color'] : $default_color)));
				if($color === false)
				{
					return $error;
				}
				$html = '<div style="display: inline; filter: glow(color=' . $color . '); height: 20px;">';
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div>',
				);
			}

			// SHADOW
			if($tag === 'shadow')
			{
				$default_color = '#666666';
				$color = $this->valid_color((isset($item['params']['param']) ? $item['params']['param'] : (isset($item['params']['color']) ? $item['params']['color'] : $default_color)));
				if($color === false)
				{
					return $error;
				}
				$html = '<div style="display: inline; filter: shadow(color=' . $color . '); height: 20;">';
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div>',
				);
			}

			// BLUR
			if($tag === 'blur')
			{
				if($this->is_sig && !$config['allow_all_bbcode'])
				{
					return $error;
				}
				if(isset($item['params']['param']))
				{
					$strenght = intval($item['params']['param']);
					if (($strenght > 0) && ($strenght < 101))
					{
						$strenght = $strenght;
					}
				}
				else
				{
					$strenght = '100';
				}
				$strenght_dec = $strenght / 100;
				$html = '<div style="display: inline; width: 100%; height: 20; filter: Blur(add=1,direction=270,strength=' . $strenght . ');">';
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div>',
				);
			}

			// WAVE
			if($tag === 'wave')
			{
				if($this->is_sig && !$config['allow_all_bbcode'])
				{
					return $error;
				}
				if(isset($item['params']['param']))
				{
					$strenght = intval($item['params']['param']);
					if (($strenght > 0) && ($strenght < 101))
					{
						$strenght = $strenght;
					}
				}
				else
				{
					$strenght = '100';
				}
				$strenght_dec = $strenght / 100;
				$html = '<div style="display: inline; width: 100%; height: 20; filter: Wave(add=1,direction=270,strength=' . $strenght . ');">';
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div>',
				);
			}

			// FLIPH, FLIPV
			if(($tag === 'fliph') || ($tag === 'flipv'))
			{
				if($this->is_sig && !$config['allow_all_bbcode'])
				{
					return $error;
				}
				$html = '<div style="display: inline; filter: ' . $tag . '; height: 1;">';
				return array(
					'valid' => true,
					'start' => $html,
					'end' => '</div>',
				);
			}
		}
		*/
		// OLD IE AND HTML 4 ONLY TAGS - END

		// TEX
		if($tag === 'tex')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			$html = '<img src="cgi-bin/mimetex.cgi?' . $content . '" alt="" border="0" style="vertical-align: middle;" />';
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => false,
			);
		}

		// TABLE
		if($tag === 'table')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			if (!empty($this->plain_html)) return $error;

			// additional allowed parameters
			$extras = $this->allow_styling ? array('style', 'class', 'align', 'width', 'height', 'border', 'cellspacing', 'cellpadding') : array('style', 'class', 'align', 'width');
			if(isset($item['params']['param']))
			{
				$table_class = $item['params']['param'];
			}
			else
			{
				$table_class = '';
			}

			for($i = 0; $i < sizeof($extras); $i++)
			{
				if(!empty($item['params'][$extras[$i]]))
				{
					if($extras[$i] === 'style')
					{
						$style = $this->valid_style($item['params']['style']);
						if($style !== false)
						{
							$params['style'] = $style;
						}
					}
					else
					{
						$params[$extras[$i]] = $item['params'][$extras[$i]];
					}
				}
			}
			if (!isset($params['class']))
			{
				$params['class'] = $table_class;
			}
			// generate html
			$html = '<table';
			foreach($params as $var => $value)
			{
				$html .= ' ' . $var . '="' . $this->process_text($value) . '"';
			}
			$html .= ' >' . $content . '</table>';
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => true,
			);
		}

		/*
		// TR
		if($tag === 'tr')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			// generate html
			$html = '<tr>' . $content . '</tr>';
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => true,
			);
		}

		// TD
		if($tag === 'td')
		{
			if($this->is_sig && !$config['allow_all_bbcode']) return $error;
			// additional allowed parameters
			$extras = $this->allow_styling ? array('class', 'align', 'width', 'height') : array('class', 'align', 'width', 'height');

			for($i = 0; $i < sizeof($extras); $i++)
			{
				if(!empty($item['params'][$extras[$i]]))
				{
					if($extras[$i] === 'style')
					{
						$style = $this->valid_style($item['params']['style']);
						if($style !== false)
						{
							$params['style'] = $style;
						}
					}
					else
					{
						$params[$extras[$i]] = $item['params'][$extras[$i]];
					}
				}
			}
			// generate html
			$html = '<td';
			foreach($params as $var => $value)
			{
				$html .= ' ' . $var . '="' . $this->process_text($value) . '"';
			}
			$html .= ' >' . $content . '</td>';
			return array(
				'valid' => true,
				'html' => $html,
				'allow_nested' => true,
			);
		}
		*/

		// To use IFRAMES you just need to decomment this block of code (and allow the tag on top of this file)... good luck!
		// IFRAME
		//<iframe src="index.html" scrolling="no" width="100%" height="190" frameborder="0" marginheight="0" marginwidth="0"></iframe>
		//[iframe height=100]docs/index.html[/iframe]
		//[iframe src=docs/index.html height=100] [/iframe]
		/*
		if($tag === 'iframe')
		{
			if(isset($item['params']['param']))
			{
				$params['src'] = $item['params']['param'];
			}
			elseif(isset($item['params']['src']))
			{
				$params['src'] = $item['params']['src'];
			}
			elseif(!empty($content))
			{
				$params['src'] = $content;
			}
			if(isset($item['params']['scrolling']) && ($params['scrolling'] == 'no'))
			{
				$params['scrolling'] = 'no';
				//$params['scrolling'] = $item['params']['scrolling'];
			}
			else
			{
				$params['scrolling'] = 'yes';
			}
			if(isset($item['params']['width']))
			{
				$params['width'] = $item['params']['width'];
			}
			else
			{
				$params['width'] = '100%';
			}
			if(isset($item['params']['height']))
			{
				$params['height'] = $item['params']['height'];
			}
			else
			{
				$params['height'] = '600';
			}

			foreach($params as $var => $value)
			{
				if ($this->process_text($value) != '')
				{
					$html .= ' ' . $var . '="' . $this->process_text($value) . '"';
				}
			}
			$extras = $this->allow_styling ? array('style', 'class') : array('class');
			$html = '<iframe' . $html . '>';
			return array(
				'valid' => true,
				'start' => $html,
				'end' => '</iframe>'
			);
		}
		*/

		// Invalid tag
		return $error;
	}

	// Check if bbcode tag is valid
	function valid_tag($tag, $is_html)
	{
		if($is_html)
		{
			return (isset($this->allowed_html[$tag]) && preg_match('/^[a-z]+$/', $tag)) ? true : false;
		}
		else
		{
			$tag_ok = false;
			if(($tag === '*') || ($tag === '[*]') || ($tag === '[hr]'))
			{
				$tag_ok = true;
			}
			return (isset($this->allowed_bbcode[$tag]) && (preg_match('/^[a-z]+$/', $tag) || ($tag_ok === true))) ? true : false;
		}
	}

	// Check if parameter name is valid
	function valid_param($param)
	{
		return preg_match('/^[a-z]+$/', $param);
	}

	// Check if color is valid
	function valid_color($color, $hex_only = false)
	{
		if ($color === false)
		{
			return false;
		}
		$color = strtolower($color);
		if(substr($color, 0, 1) === '#')
		{
			// normal color
			if(preg_match('/^[0-9a-f]+$/', substr($color, 1)))
			{
				if ($hex_only == true)
				{
					if(strlen($color) == 7)
					{
						return $color;
					}
				}
				else
				{
					if((strlen($color) == 4) || (strlen($color) == 7))
					{
						return $color;
					}
				}
			}
			return false;
		}
		// color with missing #
		if(preg_match('/^[0-9a-f]+$/', $color))
		{
			if ($hex_only == true)
			{
				if(strlen($color) == 6)
				{
					return '#' . $color;
				}
			}
			else
			{
				if((strlen($color) == 3) || (strlen($color) == 6))
				{
					return '#' . $color;
				}
			}
		}
		if($hex_only == true)
		{
			// We didn't find any valid 6 digits hex color
			return false;
		}
		if(preg_match('/^[a-z]+$/', $color))
		{
			// text color
			return $color;
		}

		// rgb(ddd, ddd, ddd) color
		// OLD RGB FUNCTION
		/*
		if((substr($color, 0, 4) === 'rgb(') && preg_match('/^rgb\([0-9]+,[0-9]+,[0-9]+\)$/', $color))
		{
			$colors = explode(',', substr($color, 4, strlen($color) - 5));
			for($i = 0; $i < 3; $i++)
			{
				if($colors[$i] > 255)
				{
					return false;
				}
			}
			return sprintf('#%02X%02X%02X', $colors[0], $colors[1], $colors[2]);
		}
		*/

		if(substr($color, 0, 4) === 'rgb(')
		{
			$valid = preg_replace_callback('#^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$#', 'bbcode::valid_rgb_match', $color);
			return !empty($valid) ? $color : false;
		}
		return false;
	}

	// Check for valid RGB match
	function valid_rgb_match($matches)
	{
		$valid = true;
		if (sizeof($matches) != 4)
		{
			$valid = false;
		}
		else
		{
			$red = (int) $matches[1];
			$green = (int) $matches[2];
			$blue = (int) $matches[3];
			if (($red > 255) || ($green > 255) || ($blue > 255))
			{
				$valid = false;
			}
		}
		return $valid;
	}

	// Parse style
	function valid_style($style, $error = false)
	{
		$style = str_replace(array('\\', '"', '@'), array('', '', ''), $style);
		$str = strtolower($style);
		if((strpos($str, 'expression') !== false) || (strpos($str, 'javascript:') !== false) || (strpos($str, 'vbscript:') !== false) || (strpos($str, 'about:') !== false))
		{
			// attempt to use javascript
			return $error;
		}
		if(strpos($str, '//') !== false)
		{
			// attempt to use external file
			return $error;
		}
		if(strpos($str, '!important') !== false)
		{
			// attempt to completely mess up forum layout?
			return $error;
		}
		return $style;
	}

	// Validate url
	function valid_url($url, $error = '')
	{
		$str = strtolower($url);
		if(substr($str, 0, 11) === 'javascript:')
		{
			// attempt to use javascript
			return $error;
		}

		if(substr($str, 0, 9) === 'vbscript:')
		{
			// attempt to use vbscript
			return $error;
		}

		if(substr($str, 0, 6) === 'about:')
		{
			// attempt to use about: url
			return $error;
		}

		return $url;
	}

	// Add extras
	function add_extras($params, $extras)
	{
		$html = '';
		for($i = 0; $i < sizeof($extras); $i++)
		{
			if(isset($params[$extras[$i]]))
			{
				if($extras[$i] === 'style')
				{
					$style = $this->valid_style($params['style']);
					if($style !== false)
					{
						$html .= ' style="' . htmlspecialchars($style) . '"';
					}
				}
				else
				{
					$html .= ' ' . $extras[$i] . '="' . htmlspecialchars($params[$extras[$i]]) . '"';
				}
			}
		}
		return $html;
	}

	// Splits string to tag and parameters
	function extract_params($tag, $is_html)
	{
		$this->tag = $tag;
		$this->params = array();
		$tag = str_replace("\t", ' ', $tag);
		// get parameters
		$pos_eq = strpos($tag, '=');
		$pos_space = strpos($tag, ' ');
		if(($pos_space !== false) && ($pos_eq !== false) && ($pos_space < $pos_eq))
		{
			// mutiple parameters
			$param_start = 0;
			$param_str = substr($tag, $pos_space + 1);
			$param_len = strlen($param_str);
			$this->tag = strtolower(substr($tag, 0, $pos_space));
			if(!$this->valid_tag($this->tag, $is_html))
			{
				return false;
			}
			while($param_start < $param_len)
			{
				// find entry for '='
				$pos = strpos($param_str, '=', $param_start);
				if($pos === false)
				{
					return false;
				}
				else
				{
					// get parameter name
					$str = substr($param_str, $param_start, $pos - $param_start);
					if(!$this->valid_param($str))
					{
						return false;
					}
					// get value
					$pos++;
					$quoted = false;
					if(substr($param_str, $pos, 1) === '"')
					{
						$pos2 = strpos($param_str, '"', $pos + 1);
						if($pos2 === false)
						{
							// invalid quote. search for space instead
							$pos2 = strpos($param_str, ' ', $pos + 1);
						}
						else
						{
							$pos++;
							$quoted = true;
						}
					}
					else
					{
						$pos2 = strpos($param_str, ' ', $pos);
					}
					// end not found. counting until end of expression
					if($pos2 === false)
					{
						$pos2 = $param_len;
					}
					$this->params[$str] = substr($param_str, $pos, $pos2 - $pos);
					$param_start = $pos2 + 1;
					if($quoted)
					{
						$param_start++;
					}
				}
			}
		}
		elseif($pos_eq !== false)
		{
			// single parameter
			$str = substr($tag, $pos_eq + 1);
			$this->tag = strtolower(substr($tag, 0, $pos_eq));
			if(!$this->valid_tag($this->tag, $is_html))
			{
				return false;
			}
			if(strlen($str) > 1 && substr($str, 0, 1) === '"' && substr($str, strlen($str) - 1) === '"')
			{
				$str = substr($str, 1, strlen($str) - 2);
			}
			if(trim($str) !== $str)
			{
				return false;
			}
			$this->params['param'] = $str;
		}
		else
		{
			// no parameters
			$this->tag = strtolower($tag);
			if(!$this->valid_tag($this->tag, $is_html))
			{
				return false;
			}
		}
		return true;
	}

	// Recursive function that converts text to bbcode tree
	function push($start, $level, $prev_tags)
	{
		//echo '<b>push</b>(', $start, ', ', $level, ', (', implode(',', $prev_tags), '))<br />';
		$items = array();
		$pos_start_bbcode = $this->allow_bbcode ? strpos($this->text, '[', $start) : false;
		$pos_start_html = $this->allow_html ? strpos($this->text, '<', $start) : false;
		while($pos_start_bbcode !== false || $pos_start_html !== false)
		{
			$pos_start = ($pos_start_bbcode === false) ? $pos_start_html : (($pos_start_html === false) ? $pos_start_bbcode : min($pos_start_bbcode, $pos_start_html));
			$is_html = ($pos_start_html === $pos_start) ? true : false;
			$prev_start = $start;
			// found tag. get data.
			$pos_end = strpos($this->text, $is_html ? '>' : ']', $pos_start);
			if($pos_end === false)
			{
				$tag_valid = false;
			}
			else
			{
				$code = substr($this->text, $pos_start, $pos_end - $pos_start + 1);
				// check if tag is valid and get type of tag
				$tag_valid = true;
				$tag_closing = false;
				$tag_self_closing = false;
				if(strlen($code) < 3)
				{
					$tag_valid = false;
				}
				elseif(!$is_html && strpos($code, '[', 1) !== false)
				{
					$tag_valid = false;
				}
				elseif($is_html && strpos($code, '<', 1) !== false)
				{
					$tag_valid = false;
				}
				elseif(!$is_html && strpos($code, "\n") !== false)
				{
					$tag_valid = false;
				}
				elseif(substr($code, 0, 2) === ($is_html ? '</' : '[/'))
				{
					$tag_closing = true;
					$tag = substr($code, 2, strlen($code) - 3);
				}
				elseif(substr($code, strlen($code) - 3) === ($is_html ? ' />' : ' /]'))
				{
					$tag_self_closing = true;
					$tag = substr($code, 1, strlen($code) - 4);
				}
				else
				{
					$tag = substr($code, 1, strlen($code) - 2);
				}

				// do not process tag if it requires too much recursion
				if($level > 12 && (!$tag_closing && !$tag_self_closing))
				{
					$tag_valid = false;
				}

				// special tags
				if(in_array($code, $this->self_closing_tags) != false)
				{
					$tag_self_closing = true;
				}
			}
			if($tag_valid)
			{
				$start = $pos_end;
				$params = array();
				if(!$tag_closing)
				{
					if(!$this->extract_params($tag, $is_html))
					{
						$tag_valid = false;
					}
					else
					{
						$tag = $this->tag;
						$params = $this->params;
					}
				}
				else
				{
					if(strpos($tag, ' autourl=' . AUTOURL . ' nofollow=1'))
					{
						$tag = str_replace(' autourl=' . AUTOURL . ' nofollow=1', '', $tag);
					}
					$tag = strtolower($tag);
					if(!$this->valid_tag($tag, $is_html))
					{
						$tag_valid = false;
					}
				}
			}
			if($tag_valid)
			{
				if($tag_closing)
				{
					// check if this is correct closing tag
					if(in_array($tag, $prev_tags))
					{
						return array(
							'items' => $items,
							'tag' => $tag,
							'pos' => $pos_end,
							'start' => $pos_start,
							'len' => strlen($code)
						);
					}
				}
				elseif($tag_self_closing)
				{
					// found self-closing tag
					$items[] = array(
						'tag' => $tag,
						'code' => $code,
						'params' => $params,
						'start' => $pos_start,
						'start_len' => strlen($code),
						'end' => $pos_end + 1,
						'end_len' => 0,
						'level' => $level + 1,
						'iteration' => 0,
						'self_closing' => 1,
						'prev' => array(),
						'next' => array(),
						'is_html' => $is_html,
						'items' => array()
					);
				}
				else
				{
					// found correct tag. call recursive search
					$result = $this->push($pos_end, $level + 1, array_merge($prev_tags, array($tag)));
					if($result['tag'] === $tag)
					{
						// found correctly finished tag
						$items[] = array(
							'tag' => $tag,
							'code' => $code,
							'params' => $params,
							'start' => $pos_start,
							'start_len' => strlen($code),
							'end' => $result['start'],
							'end_len' => $result['len'],
							'level' => $level + 1,
							'iteration' => 0,
							'self_closing' => 2,
							'prev' => array(),
							'next' => array(),
							'is_html' => $is_html,
							'items' => $result['items']
						);
						$start = $result['pos'];
					}
					else
					{
						$items = array_merge($items, $result['items']);
						return array(
							'items' => $items,
							'tag' => $result['tag'],
							'pos' => !empty($result['pos']) ? $result['pos'] : 0,
							'start' => !empty($result['start']) ? $result['start'] : 0,
							'len' => !empty($result['len']) ? $result['len'] : 0
						);
					}
				}
			}
			else
			{
				$start = $pos_start + 1;
			}
			$pos_start_bbcode = $this->allow_bbcode ? strpos($this->text, '[', $start) : false;
			$pos_start_html = $this->allow_html ? strpos($this->text, '<', $start) : false;
		}
		return array(
			'items' => $items,
			'tag' => false,
		);
	}

	// Debug fuction. Prints tree of bbcode
	function debug($items)
	{
		for($i = 0; $i < sizeof($items); $i++)
		{
			$item = $items[$i];
			if($item['tag'])
			{
				for($j=0; $j<$item['level']; $j++)
				{
					echo '-';
				}
				echo ' ', $item['tag'], ' (';
				$first = true;
				foreach($item['params'] as $var => $value)
				{
					if(!$first) echo ', ';
					$first = false;
					echo $var, '="', htmlspecialchars($value), '"';
				}
				echo ")<br />\n";
				$this->debug($item['items']);
			}
		}
	}

	// Post-processing. Adds previous/next items to every item.
	function add_pointers(&$items, $prev_tags)
	{
		$tags = array();
		for($i = 0; $i < sizeof($items); $i++)
		{
			$item = &$items[$i];
			$tags[] = array(
				'tag' => $item['tag'],
				'item' => &$items[$i]
				);
			$iterations = 0;
			for($j = 0; $j < sizeof($prev_tags); $j++)
			{
				if($prev_tags[$j]['tag'] === $item['tag'])
				{
					$iterations++;
				}
			}
			$item['iteration'] = $iterations;
			$item['prev'] = $prev_tags;
			// todo: check if subitems are allowed
			// parse sub-items
			if(sizeof($item['items']))
			{
				$arr = array(
					'tag' => $item['tag'],
					'item' => &$items[$i]
					);
				$item['next'] = $this->add_pointers($item['items'], array_merge($prev_tags, array($arr)));
				$tags = array_merge($tags, $item['next']);
			}
		}
		return $tags;
	}

	// Process text
	function process_text($text, $br = true, $chars = true)
	{
		$search = array(
			'[url autourl=' . AUTOURL . ' nofollow=1]',
			'[/url autourl=' . AUTOURL .' nofollow=1]',
			'[email autourl=' . AUTOURL . ' nofollow=1]',
			'[/email autourl=' . AUTOURL . ' nofollow=1]'
		);
		$replace = array('', '', '', '');
		$text = str_replace($search, $replace, $text);
		if($chars)
		{
			$text = htmlspecialchars($text);
			$text = str_replace('&amp;#', '&#', $text);
		}
		else
		{
			$text = str_replace(
				array('&amp;', '>', '%3E', '<', '%3C', '"', '&amp;#'),
				array('&amp;amp;', '&gt;', '&gt;', '&lt;', '&lt;', '&quot;', '&#'),
				$text
			);
		}
		if($br)
		{
			$text = str_replace("\n", "<br />\n", $text);
		}
		return $text;
	}

	// Process tree
	function process($start, $end, &$items, $clean_tags = false)
	{
		$html = '';
		for($i = 0; $i < sizeof($items); $i++)
		{
			$item = &$items[$i];

			// check code before item
			if($item['start'] > $start)
			{
				$html .= $this->process_text(substr($this->text, $start, $item['start'] - $start));
			}

			if ($clean_tags === true)
			{
				// clean tag
				$result = $this->clean_tag($item);
			}
			else
			{
				// process tag
				$result = $this->process_tag($item);
			}

			if($result['valid'] && !isset($result['html']))
			{
				$html .= $result['start'];
				if(!isset($result['allow_nested']) || $result['allow_nested'])
				{
					// process code inside tag
					$html .= $this->process($item['start'] + $item['start_len'], $item['end'], $item['items'], $clean_tags);
				}
				$html .= $result['end'];
			}
			elseif($result['valid'])
			{
				$html .= $result['html'];
			}
			else
			{
				// invalid tag. show html code for it and process nested tags
				$item['valid'] = false;
				if($item['start_len'])
				{
					$html .= $this->process_text(substr($this->text, $item['start'], $item['start_len']));
				}
				$html .= $this->process($item['start'] + $item['start_len'], $item['end'], $item['items']);
				if($item['end_len'])
				{
					$html .= $this->process_text(substr($this->text, $item['end'], $item['end_len']));
				}
			}

			$start = $item['end'] + $item['end_len'];
		}
		// process code after item
		if($start < $end)
		{
			$html .= $this->process_text(substr($this->text, $start, $end - $start));
		}
		return $html;
	}

	// Prepare smilies list
	function prepare_smilies()
	{
		if(!$this->allow_smilies)
		{
			return;
		}
		$this->replaced_smilies = array();
		for($i = 0; $i < sizeof($this->allowed_smilies); $i++)
		{
			if(strpos($this->text, $this->allowed_smilies[$i]['code']) !== false)
			{
				$this->replaced_smilies[] = $this->allowed_smilies[$i];
			}
		}
	}

	// Parse only smilies
	function parse_only_smilies($text)
	{
		if(!$this->allow_smilies || (sizeof($this->allowed_smilies) == 0))
		{
			return $text;
		}
		$smilies_code = array();
		$smilies_replace = array();
		for($i = 0; $i < sizeof($this->allowed_smilies); $i++)
		{
			$smilies_code_prev[] = ' ' . $this->allowed_smilies[$i]['code'];
			$smilies_code_next[] = $this->allowed_smilies[$i]['code'] . ' ';
			$smilies_replace_prev[] = ' ' . $this->allowed_smilies[$i]['replace'];
			$smilies_replace_next[] = $this->allowed_smilies[$i]['replace'] . ' ';
		}
		$text = str_replace($smilies_code_prev, $smilies_replace_prev, $text);
		$text = str_replace($smilies_code_next, $smilies_replace_next, $text);
		return $text;
	}

	// Process smilies
	function process_smilies()
	{
		$valid_chars_prev = array('', ' ', "\n", "\r", "\t", '>');
		$valid_chars_next = array('', ' ', "\n", "\r", "\t", '<');
		if(!$this->allow_smilies && !sizeof($this->replaced_smilies))
		{
			return;
		}
		for($i = 0; $i < sizeof($this->replaced_smilies); $i++)
		{
			$code = $this->replaced_smilies[$i]['code'];
			$text = $this->replaced_smilies[$i]['replace'];
			$code_len = strlen($code);
			$text_len = strlen($text);
			$pos = strpos($this->html, $code);
			while($pos !== false)
			{
				$valid = false;
				// check previous character
				$prev_char = $pos > 0 ? substr($this->html, $pos - 1, 1) : '';
				if(in_array($prev_char, $valid_chars_prev))
				{
					// check next character
					$next_char = substr($this->html, $pos + $code_len, 1);
					if(in_array($next_char, $valid_chars_next))
					{
						// make sure we aren't inside html code
						$pos1 = strpos($this->html, '<', $pos + $code_len);
						$pos2 = strpos($this->html, '>', $pos + $code_len);
						if($pos2 === false || ($pos1 && $pos1 < $pos2))
						{
							// make sure we aren't inside nosmilies zone
							$pos1 = strpos($this->html, BBCODE_NOSMILIES_START, $pos + $code_len);
							$pos2 = strpos($this->html, BBCODE_NOSMILIES_END, $pos + $code_len);
							if($pos2 === false || ($pos1 && $pos1 < $pos2))
							{
								$valid = true;
							}
						}
					}
				}
				if($valid)
				{
					$this->html = substr($this->html, 0, $pos) . $text . substr($this->html, $pos + $code_len);
					$pos += $text_len;
				}
				else
				{
					$pos++;
				}
				$pos = strpos($this->html, $code, $pos);
			}
		}
	}

	// Make urls clickable
	function process_urls()
	{
		// characters allowed in email
		$chars = array();
		for($i = 224; $i < 256; $i++)
		{
			if($i != 247)
			{
				$chars .= chr($i);
			}
		}
		// search and replace arrays
		$search = array(
			"/([\s>])((https?|ftp):\/\/|www\.)([^ \r\n\(\)\^\$!`\"'\|\[\]\{\}<>]+)/si",
			"/([\s>])([_a-zA-Z0-9\-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9\-{$chars}]+(\.[a-zA-Z0-9\-{$chars}]+)*(\.[a-zA-Z]{2,}))/si",
		);
		$replace = array(
			"\\1[url autourl=" . AUTOURL . " nofollow=1]\\2\\4[/url autourl=" . AUTOURL . " nofollow=1]",
			"\\1[email autourl=" . AUTOURL . " nofollow=1]\\2[/email autourl=" . AUTOURL . " nofollow=1]",
		);
		$this->text = preg_replace($search, $replace, ' ' . $this->text . ' ');
		$this->text = substr($this->text, 1, strlen($this->text) - 2);
	}

	// Remove bbcode_uid from old posts
	function bbcuid_clean($text, $id = false)
	{
		if ($id != false)
		{
			$text = str_replace(':' . $id, '', $text);
		}
		else
		{
			$text = preg_replace("/\:([a-f0-9]{10})/s", '', $text);
			// phpBB 3
			//$text = preg_replace("/\:([a-z0-9]{8})/s", '', $text);
		}
		return $text;
	}

	// Converts text to html code
	function parse($text, $id = false, $light = false, $clean_tags = false)
	{
		if(defined('IN_ICYPHOENIX'))
		{
			// if you have an old phpBB based site with old posts, you may want to enable this BBCode UID strip REG EX Replace
			//$text = preg_replace("/\:([a-f0-9]{10})/s", '', $text);
			$search = array(
				$id ? ':' . $id : '',
				'code:1]',
				'list:o]',
			);
			$replace = array(
				'',
				'code]',
				'list]',
			);
			$text = str_replace($search, $replace, $text);
			// We need this after having removed bbcode_uid... but don't know why
			$text = $this->undo_htmlspecialchars($text);
			/*
			if($id)
			{
				$text = $this->undo_htmlspecialchars($text);
			}
			*/
		}
		// reset variables
		$this->text = $text;
		$this->data = array();
		$this->html = '';
		$this->prepare_smilies();
		if (!$light)
		{
			$this->process_urls();
		}
		$this->code_counter = 0;
		// if bbcode and html are disabled then return unprocessed text
		if(!$this->allow_bbcode && !$this->allow_html)
		{
			// Mighty Gorgon: I had to add htmlspecialchars to text, otherwise users were able to post html by disabling both HTML and BBCodes in topics... still to be fully verified...
			//$this->html = $this->text;
			$this->html = htmlspecialchars($this->text);
			$this->process_smilies();
			return $this->html;
		}
		// convert to tree structure
		$result = $this->push(0, 0, array());
		$this->data = $result['items'];

		/*
		ob_start();
		$this->debug($this->data);
		$str = ob_get_contents();
		ob_end_clean();
		$this->html = 'Debug:<br />' . $str;
		return $this->html;
		*/

		// add prev/next pointers and count iterations
		$this->add_pointers($this->data, array());
		if ($clean_tags !== false)
		{
			$clean_tags = true;
		}
		// convert to html
		$this->html = $this->process(0, strlen($this->text), $this->data, $clean_tags);
		$this->process_smilies();

		if(defined('IN_ICYPHOENIX'))
		{
			global $db, $cache, $config, $lang;
			if (!empty($config['enable_custom_bbcodes']))
			{
				$bbcodes = $cache->obtain_bbcodes(true);
				if (!empty($bbcodes))
				{
					$bbcode_regexp = array();
					foreach ($bbcodes as $k => $v)
					{
						$v = array_map('stripslashes', $v);
						$bbcode_regexp = $this->build_regexp($v['bbcode_match'], $v['bbcode_tpl']);
						$this->html = preg_replace($bbcode_regexp['second_pass_match'], $bbcode_regexp['second_pass_replace'], $this->html);
					}
				}
			}
		}

		return $this->html;
	}

	/*
	* Build regular expression for custom bbcode
	*/
	function build_regexp(&$bbcode_match, &$bbcode_tpl)
	{
		$bbcode_match = trim($bbcode_match);
		$bbcode_tpl = trim($bbcode_tpl);

		$fp_match = preg_quote($bbcode_match, '!');
		$fp_replace = preg_replace('#^\[(.*?)\]#', '[$1]', $bbcode_match);
		$fp_replace = preg_replace('#\[/(.*?)\]$#', '[/$1]', $fp_replace);

		$sp_match = preg_quote($bbcode_match, '!');
		$sp_match = preg_replace('#^\\\\\[(.*?)\\\\\]#', '\[$1\]', $sp_match);
		$sp_match = preg_replace('#\\\\\[/(.*?)\\\\\]$#', '\[/$1\]', $sp_match);
		$sp_replace = $bbcode_tpl;

		// @todo Make sure to change this too if something changed in message parsing
		$tokens = array(
			'URL' => array(
				'!(?:(' . str_replace(array('!', '\#'), array('\!', '#'), $this->get_preg_expression('url')) . ')|(' . str_replace(array('!', '\#'), array('\!', '#'), $this->get_preg_expression('www_url')) . '))!ie' => "\$this->bbcode_specialchars(('\$1') ? '\$1' : 'http://\$2')"
			),
			'LOCAL_URL' => array(
				'!(' . str_replace(array('!', '\#'), array('\!', '#'), $this->get_preg_expression('relative_url')) . ')!e' => "\$this->bbcode_specialchars('$1')"
			),
			'EMAIL' => array(
				'!(' . $this->get_preg_expression('email') . ')!ie' => "\$this->bbcode_specialchars('$1')"
			),
			'TEXT' => array(
				'!(.*?)!es' => "str_replace(array(\"\\r\\n\", '\\\"', '\\'', '(', ')'), array(\"\\n\", '\"', '&#39;', '&#40;', '&#41;'), trim('\$1'))"
			),
			'SIMPLETEXT' => array(
				'!([a-zA-Z0-9-+.,_ ]+)!' => "$1"
			),
			'IDENTIFIER' => array(
				'!([a-zA-Z0-9-_]+)!' => "$1"
			),
			'COLOR' => array(
				'!([a-z]+|#[0-9abcdef]+)!i' => '$1'
			),
			'NUMBER' => array(
				'!([0-9]+)!' => '$1'
			)
		);

		$sp_tokens = array(
			'URL' => '(?i)((?:' . str_replace(array('!', '\#'), array('\!', '#'), $this->get_preg_expression('url')) . ')|(?:' . str_replace(array('!', '\#'), array('\!', '#'), $this->get_preg_expression('www_url')) . '))(?-i)',
			'LOCAL_URL' => '(?i)(' . str_replace(array('!', '\#'), array('\!', '#'), $this->get_preg_expression('relative_url')) . ')(?-i)',
			'EMAIL' => '(' . $this->get_preg_expression('email') . ')',
			'TEXT' => '(.*?)',
			'SIMPLETEXT' => '([a-zA-Z0-9-+.,_ ]+)',
			'IDENTIFIER' => '([a-zA-Z0-9-_]+)',
			'COLOR' => '([a-zA-Z]+|#[0-9abcdefABCDEF]+)',
			'NUMBER' => '([0-9]+)',
		);

		$pad = 0;
		$modifiers = 'i';

		if (preg_match_all('/\{(' . implode('|', array_keys($tokens)) . ')[0-9]*\}/i', $bbcode_match, $m))
		{
			foreach ($m[0] as $n => $token)
			{
				$token_type = $m[1][$n];

				reset($tokens[strtoupper($token_type)]);
				list($match, $replace) = each($tokens[strtoupper($token_type)]);

				// Pad backreference numbers from tokens
				if (preg_match_all('/(?<!\\\\)\$([0-9]+)/', $replace, $repad))
				{
					$repad = $pad + sizeof(array_unique($repad[0]));
					$replace = preg_replace_callback('/(?<!\\\\)\$([0-9]+)/', function ($m) use ($pad) {
						return '${' . $m[1] + $pad . '}';
					}, $replace);
					$pad = $repad;
				}

				// Obtain pattern modifiers to use and alter the regex accordingly
				$regex = preg_replace('/!(.*)!([a-z]*)/', '$1', $match);
				$regex_modifiers = preg_replace('/!(.*)!([a-z]*)/', '$2', $match);

				for ($i = 0, $size = strlen($regex_modifiers); $i < $size; ++$i)
				{
					if (strpos($modifiers, $regex_modifiers[$i]) === false)
					{
						$modifiers .= $regex_modifiers[$i];

						if ($regex_modifiers[$i] == 'e')
						{
							$fp_replace = "'" . str_replace("'", "\\'", $fp_replace) . "'";
						}
					}

					if ($regex_modifiers[$i] == 'e')
					{
						$replace = "'.$replace.'";
					}
				}

				$fp_match = str_replace(preg_quote($token, '!'), $regex, $fp_match);
				$fp_replace = str_replace($token, $replace, $fp_replace);

				$sp_match = str_replace(preg_quote($token, '!'), $sp_tokens[$token_type], $sp_match);
				$sp_replace = str_replace($token, '${' . ($n + 1) . '}', $sp_replace);
			}

			$fp_match = '!' . $fp_match . '!' . $modifiers;
			$sp_match = '!' . $sp_match . '!s';

			if (strpos($fp_match, 'e') !== false)
			{
				$fp_replace = str_replace("'.'", '', $fp_replace);
				$fp_replace = str_replace(".''.", '.', $fp_replace);
			}
		}
		else
		{
			// No replacement is present, no need for a second-pass pattern replacement
			// A simple str_replace will suffice
			$fp_match = '!' . $fp_match . '!' . $modifiers;
			$sp_match = $fp_replace;
			$sp_replace = '';
		}

		// Lowercase tags
		$bbcode_tag = preg_replace('/.*?\[([a-z0-9_-]+=?).*/i', '$1', $bbcode_match);
		$bbcode_search = preg_replace('/.*?\[([a-z0-9_-]+)=?.*/i', '$1', $bbcode_match);

		if (!preg_match('/^[a-zA-Z0-9_-]+=?$/', $bbcode_tag))
		{
			return false;
		}

		$fp_match = preg_replace_callback('#\[/?' . $bbcode_search . '#i', function ($m) {
			return strtolower($m[0]);
		}, $fp_match);
		$fp_replace = preg_replace_callback('#\[/?' . $bbcode_search . '#i', function ($m) {
			return strtolower($m[0]);
		}, $fp_replace);
		$sp_match = preg_replace_callback('#\[/?' . $bbcode_search . '#i', function ($m) {
			return strtolower($m[0]);
		}, $sp_match);
		$sp_replace = preg_replace_callback('#\[/?' . $bbcode_search . '#i', function ($m) {
			return strtolower($m[0]);
		}, $sp_replace);

		return array(
			'bbcode_tag'						=> $bbcode_tag,
			'first_pass_match'			=> $fp_match,
			'first_pass_replace'		=> $fp_replace,
			'second_pass_match'			=> $sp_match,
			'second_pass_replace'		=> $sp_replace
		);
	}


	/**
	* This function returns a regular expression pattern for commonly used expressions
	* Use with / as delimiter for email mode and # for url modes
	* mode can be: email|bbcode_htm|url|url_inline|www_url|www_url_inline|relative_url|relative_url_inline
	*/
	function get_preg_expression($mode)
	{
		switch ($mode)
		{
			case 'email':
				//return '(?:[a-z0-9\'\.\-_\+\|]++|&amp;)+@[a-z0-9\-]+\.(?:[a-z0-9\-]+\.)*[a-z]+';
				return '([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*(?:[\w\!\#$\%\'\*\+\-\/\=\?\^\`{\|\}\~]|&amp;)+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,63})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)';
			break;

			case 'bbcode_htm':
				return array(
					'#<!\-\- e \-\-><a href="mailto:(.*?)">.*?</a><!\-\- e \-\->#',
					'#<!\-\- l \-\-><a (?:class="[\w-]+" )?href="(.*?)(?:(&amp;|\?)sid=[0-9a-f]{32})?">.*?</a><!\-\- l \-\->#',
					'#<!\-\- ([mw]) \-\-><a (?:class="[\w-]+" )?href="(.*?)">.*?</a><!\-\- \1 \-\->#',
					'#<!\-\- s(.*?) \-\-><img src="\{SMILIES_PATH\}\/.*? \/><!\-\- s\1 \-\->#',
					'#<!\-\- .*? \-\->#s',
					'#<.*?>#s',
				);
			break;

			case 'url':
			case 'url_inline':
				$inline = ($mode == 'url') ? ')' : '';
				$scheme = ($mode == 'url') ? '[a-z\d+\-.]' : '[a-z\d+]'; // avoid automatic parsing of "word" in "last word.http://..."
				// generated with regex generation file in the develop folder
				return "[a-z]$scheme*:/{2}(?:(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+|[0-9.]+|\[[a-z0-9.]+:[a-z0-9.]+:[a-z0-9.:]+\])(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
			break;

			case 'www_url':
			case 'www_url_inline':
				$inline = ($mode == 'www_url') ? ')' : '';
				return "www\.(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})+(?::\d*)?(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
			break;

			case 'relative_url':
			case 'relative_url_inline':
				$inline = ($mode == 'relative_url') ? ')' : '';
				return "(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*(?:/(?:[a-z0-9\-._~!$&'($inline*+,;=:@|]+|%[\dA-F]{2})*)*(?:\?(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?(?:\#(?:[a-z0-9\-._~!$&'($inline*+,;=:@/?|]+|%[\dA-F]{2})*)?";
			break;
		}

		return '';
	}

	/**
	* Transform some characters in valid bbcodes
	*/
	function bbcode_specialchars($text)
	{
		$str_from = array('<', '>', '[', ']', '.', ':');
		$str_to = array('&lt;', '&gt;', '&#91;', '&#93;', '&#46;', '&#58;');

		return str_replace($str_from, $str_to, $text);
	}

	/**
	* Load rainbow colors
	*/
	function load_rainbow_colors()
	{
		return array(
			1 => 'red',
			2 => 'orange',
			3 => 'yellow',
			4 => 'green',
			5 => 'blue',
			6 => 'indigo',
			7 => 'violet'
		);
	}

	/**
	* Apply rainbow effect
	*/
	function rainbow($text)
	{
		// Returns text highlighted in rainbow colours
		$colors = $this->load_rainbow_colors();
		$text = trim($text);
		$length = strlen($text);
		$result = '';
		$color_counter = 0;
		$TAG_OPEN = false;
		for ($i = 0; $i < $length; $i++)
		{
			$char = substr($text, $i, 1);
			// UTF-8 character encoding - BEGIN
			// See http://www.faqs.org/rfcs/rfc2279.html
			$code = ord($char);
			if ($code >= 0x80)
			{
				if ($code < 0xE0)
				{
					// Two byte
					$char = substr($text, $i, 2);
					$i = $i + 1;
				}
				elseif ($code < 0xF0)
				{
					// Three byte
					$char = substr($text, $i, 3);
					$i = $i + 2;
				}
				elseif ($code < 0xF8)
				{
					// Four byte
					$char = substr($text, $i, 4);
					$i = $i + 3;
				}
				elseif ($code < 0xFC)
				{
					// Five byte
					$char = substr($text, $i, 5);
					$i = $i + 4;
				}
				elseif ($code < 0xFE)
				{
					// Six byte
					$char = substr($text, $i, 6);
					$i = $i + 5;
				}
			}
			// UTF-8 character encoding - END
			if (!$TAG_OPEN)
			{
				if ($char == '<')
				{
					$TAG_OPEN = true;
					$result .= $char;
				}
				elseif (preg_match("#\S#i", $char))
				{
					$color_counter++;
					$result .= '<span style="color: ' . $colors[$color_counter] . ';">' . $char . '</span>';
					$color_counter = ($color_counter == 7) ? 0 : $color_counter;
				}
				else
				{
					$result .= $char;
				}
			}
			else
			{
				if ($char == '>')
				{
					$TAG_OPEN = false;
				}
				$result .= $char;
			}
		}
		return $result;
	}

	function rand_color()
	{
		$color_code = mt_rand(0, 255);
		if ($color_code < 16)
		{
			return ('0' . dechex($color_code));
		}
		else
		{
			return dechex($color_code);
		}
	}

	function load_random_colors($iterations = 10)
	{
		$random_color = array();
		for ($i = 0; $i < $iterations; $i++)
		{
			$random_color[$i + 1] = '#' . $this->rand_color() . $this->rand_color() . $this->rand_color();
		}
		return $random_color;
	}

	function load_gradient_colors($color1, $color2, $iterations = 10)
	{
		$col1_array = array();
		$col2_array = array();
		$col_dif_array = array();
		$gradient_color = array();
		$col1_array[0] = hexdec(substr($color1, 1, 2));
		$col1_array[1] = hexdec(substr($color1, 3, 2));
		$col1_array[2] = hexdec(substr($color1, 5, 2));
		$col2_array[0] = hexdec(substr($color2, 1, 2));
		$col2_array[1] = hexdec(substr($color2, 3, 2));
		$col2_array[2] = hexdec(substr($color2, 5, 2));
		$col_dif_array[0] = ($col2_array[0] - $col1_array[0]) / ($iterations - 1);
		$col_dif_array[1] = ($col2_array[1] - $col1_array[1]) / ($iterations - 1);
		$col_dif_array[2] = ($col2_array[2] - $col1_array[2]) / ($iterations - 1);
		for ($i = 0; $i < $iterations; $i++)
		{
			$part1 = round($col1_array[0] + ($col_dif_array[0] * $i));
			$part2 = round($col1_array[1] + ($col_dif_array[1] * $i));
			$part3 = round($col1_array[2] + ($col_dif_array[2] * $i));
			$part1 = ($part1 < 16) ? ('0' . dechex($part1)) : (dechex($part1));
			$part2 = ($part2 < 16) ? ('0' . dechex($part2)) : (dechex($part2));
			$part3 = ($part3 < 16) ? ('0' . dechex($part3)) : (dechex($part3));
			$gradient_color[$i + 1] = '#' . $part1 . $part2 . $part3;
		}

		return $gradient_color;
	}

	function gradient($text, $color1, $color2, $mode = 'random', $iterations = 10)
	{
		// Returns text highlighted in random gradient colours
		if ($mode == 'random')
		{
			$colors = $this->load_random_colors();
		}
		else
		{
			$colors = $this->load_gradient_colors($color1, $color2, $iterations);
		}
		$text = trim(stripslashes($text));
		$length = strlen($text);
		$result = '';
		$color_counter = 0;
		$TAG_OPEN = false;
		for ($i = 0; $i < $length; $i++)
		{
			$char = substr($text, $i, 1);
			// UTF-8 character encoding - BEGIN
			// See http://www.faqs.org/rfcs/rfc2279.html
			$code = ord($char);
			if ($code >= 0x80)
			{
				if ($code < 0xE0)
				{
					// Two byte
					$char = substr($text, $i, 2);
					$i = $i + 1;
				}
				elseif ($code < 0xF0)
				{
					// Three byte
					$char = substr($text, $i, 3);
					$i = $i + 2;
				}
				elseif ($code < 0xF8)
				{
					// Four byte
					$char = substr($text, $i, 4);
					$i = $i + 3;
				}
				elseif ($code < 0xFC)
				{
					// Five byte
					$char = substr($text, $i, 5);
					$i = $i + 4;
				}
				elseif ($code < 0xFE)
				{
					// Six byte
					$char = substr($text, $i, 6);
					$i = $i + 5;
				}
			}
			// UTF-8 character encoding - END
			if (!$TAG_OPEN)
			{
				if ($char == '<')
				{
					$TAG_OPEN = true;
					$result .= $char;
				}
				elseif (preg_match("#\S#i", $char))
				{
					$color_counter++;
					$result .= '<span style="color: ' . $colors[$color_counter] . ';">' . $char . '</span>';
					$color_counter = ($color_counter == $iterations) ? 0 : $color_counter;
				}
				else
				{
					$result .= $char;
				}
			}
			else
			{
				if ($char == '>')
				{
					$TAG_OPEN = false;
				}
				$result .= $char;
			}
		}
		return $result;
	}

	/*
	* Strip only specified tags
	* $tags array of tags
	* $strip_content if set to true, also text within the specified tag is removed
	*/
	function strip_only($text, $tags, $strip_content = false)
	{
		if (empty($text) || empty($tags))
		{
			return $text;
		}

		$content = '';
		if(!is_array($tags))
		{
			$tags = array($tags);
		}

		foreach($tags as $tag)
		{
			if ($strip_content)
			{
				$content = '(.+</' . $tag . '[^>]*>|)';
			}
			$text = preg_replace('#</?' . $tag . '[^>]*>' . $content . '#is', '', $text);
		}

		return $text;
	}

	/*
	* Undo HTML special chars
	*/
	function html2txt($text)
	{
		$search = array(
			'@<script[^>]*?>.*?</script>@si',  // Strip out javascript
			'@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
			'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
			'@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
		);
		$text = preg_replace($search, '', $text);
		return $text;
	}

	/*
	* Undo HTML special chars
	*/
	function undo_htmlspecialchars($input, $full_undo = false)
	{
		if($full_undo)
		{
			$input = str_replace('&nbsp;', '', $input);
		}
		$input = preg_replace("/&gt;/i", ">", $input);
		$input = preg_replace("/&lt;/i", "<", $input);
		$input = preg_replace("/&quot;/i", "\"", $input);
		$input = preg_replace("/&amp;/i", "&", $input);

		if($full_undo)
		{
			if(preg_match_all('/&\#([0-9]+);/', $input, $matches) && sizeof($matches))
			{
				$list = array();
				for($i = 0; $i < sizeof($matches[1]); $i++)
				{
					$list[$matches[1][$i]] = true;
				}
				$search = array();
				$replace = array();
				foreach($list as $var => $value)
				{
					$search[] = '&#' . $var . ';';
					$replace[] = chr($var);
				}
				$input = str_replace($search, $replace, $input);
			}
		}

		return $input;
	}

	/*
	* This function will strip common BBCodes tags, but some of them will be left there (such as CODE or QUOTE)
	*/
	function bbcode_killer($text, $id = false)
	{
		// Pad it with a space so we can distinguish between FALSE and matching the 1st char (index 0).
		// This is important; bbencode_quote(), bbencode_list(), and bbencode_code() all depend on it.
		$text = " " . $text;

		// First: If there isn't a "[" and a "]" in the message, don't bother.
		if (!(strpos($text, "[") && strpos($text, "]")))
		{
			// Remove padding, return.
			$text = substr($text, 1);
			return $text;
		}

		// Stripping out old bbcode_uid
		if (!empty($id))
		{
			$text = preg_replace("/\:(([a-z0-9]:)?)" . $id . "/s", "", $text);
		}

		// Strip simple tags
		$look_up_array = array(
			//"[code]", "[/code]",
			//"[php]","[/php]",
			//"[cpp]","[/cpp]",
			"[b]", "[/b]",
			"[u]", "[/u]",
			"[tt]", "[/tt]",
			"[i]", "[/i]",
			"[list]", "[/list]",
			"[list=1]",
			"[list=a]",
			"[*]",
			"[url]", "[/url]",
			"[email]", "[/email]",
			"[img]", "[img align=left]", "[img align=right]", "[/img]",
			"[imgl]", "[/imgl]",
			"[imgr]", "[/imgr]",
			"[albumimg]", "[/albumimg]",
			"[albumimgl]", "[/albumimgl]",
			"[albumimgr]", "[/albumimgr]",
			"[blur]", "[/blur]",
			"[fade]", "[/fade]",
			"[rainbow]", "[/rainbow]",
			"[gradient]", "[/gradient]",
			"[jiggle]", "[/jiggle]",
			"[pulse]", "[/pulse]",
			"[neon]", "[/neon]",
			"[updown]", "[/updown]",
			"[flipv]", "[/flipv]",
			"[fliph]", "[/fliph]",
			"[wave]", "[/wave]",
			"[offtopic]", "[/offtopic]",
			"[strike]", "[/strike]",
			"[sup]", "[/sup]",
			"[sub]", "[/sub]",
			"[spoil]", "[/spoil]",
			"[spoiler]", "[/spoiler]",
			"[table]", "[/table]",
			"[tr]", "[/tr]",
			"[td]", "[/td]",
			"[em]", "[/em]",
			"[strong]", "[/strong]",
			"[center]", "[/center]",
			"[hide]", "[/hide]",
			//"[]", "[/]",
			"[hr]",
		);

		$text = str_replace($look_up_array, "", $text);

		// Colours
		$color_code = "(\#[0-9A-F]{6}|[a-z]+)";
		$look_up_array = array(
			"/\[color=" . $color_code . "\]/si", "/\[\/color\]/si",
			"/\[glow=" . $color_code . "\]/si", "/\[\/glow\]/si",
			"/\[shadow=" . $color_code . "\]/si", "/\[\/shadow\]/si",
			"/\[highlight=" . $color_code . "\]/si", "/\[\/highlight\]/si",
			"/\[size=([\-\+]?[1-3]?[0-9])\]/si", "/\[\/size\]/si",
			"/\[url=([a-z0-9\-\.,\?!%\*_\/:;~\\&$@\/=\+]+)\]/si", "/\[\/url\]/si",
			"/\[web=([a-z0-9\-\.,\?!%\*_\/:;~\\&$@\/=\+]+)\]/si", "/\[\/web\]/si",
			"/\[font=(Arial|Arial Black|Arial Bold|Arial Bold Italic|Arial Italic|Comic Sans MS|Comic Sans MS Bold|Courier New|Courier New Bold|Courier New Bold Italic|Courier New Italic|Impact|Lucida Console|Lucida Sans Unicode|Microsoft Sans Serif|Symbol|Tahoma|Tahoma Bold|Times New Roman|Times New Roman Bold|Times New Roman Bold Italic|Times New Roman Italic|Traditional Arabic|Trebuchet MS|Trebuchet MS Bold|Trebuchet MS Bold Italic|Trebuchet MS Italic|Verdana|Verdana Bold|Verdana Bold Italic|Verdana Italic|Webdings|Wingdings|)\]/si", "/\[\/font\]/si",
			"/\[font=\"(Arial|Arial Black|Arial Bold|Arial Bold Italic|Arial Italic|Comic Sans MS|Comic Sans MS Bold|Courier New|Courier New Bold|Courier New Bold Italic|Courier New Italic|Impact|Lucida Console|Lucida Sans Unicode|Microsoft Sans Serif|Symbol|Tahoma|Tahoma Bold|Times New Roman|Times New Roman Bold|Times New Roman Bold Italic|Times New Roman Italic|Traditional Arabic|Trebuchet MS|Trebuchet MS Bold|Trebuchet MS Bold Italic|Trebuchet MS Italic|Verdana|Verdana Bold|Verdana Bold Italic|Verdana Italic|Webdings|Wingdings|)\"\]/si", "/\[\/font\]/si",
			"/\[marq=(left|right|up|down)\]/si", "/\[\/marq\]/si",
			"/\[marquee direction=(left|right|up|down)\]/si", "/\[\/marquee\]/si",
			"/\[align=(left|center|right|justify)\]/si", "/\[\/align\]/si",
		);

		$text = preg_replace($look_up_array, "", $text);

		// [QUOTE] and [/QUOTE]
		/*
		$text = str_replace("[quote]","", $text);
		$text = str_replace("[/quote]", "", $text);
		$text = preg_replace("/\[quote=(?:\"?([^\"]*)\"?)\]/si", "", $text);
		*/

		// Remove our padding from the string..
		$text = substr($text, 1);

		return $text;
	}

	/*
	* This function will strip from a message some BBCodes, all BBCodes $uid, and some other formattings.
	* The result will be suitable for email sendings.
	*/
	function plain_message($text, $id = false)
	{
		$text = $this->bbcode_killer($text, $id);
		//$text = preg_replace("/\r\n/", "<br />", $text);
		$text = preg_replace("/\r\n/", "\n", $text);
		$text = str_replace('<br />', "\n", $text);

		return $text;
	}

	/*
	* This function will strip all specified BBCodes tags or all BBCodes tags
	*/
	function bbcode_clean($text, &$tags)
	{
		if (is_array($tags) && (sizeof($tags) > 0))
		{
			for ($i = 0; $i < sizeof($tags); $i++)
			{
				$tags[$i] = ($tags[$i] == '*') ? '\*' : $tags[$i];
				$text = preg_replace("/\[" . $tags[$i] . "[^]^[]*\]/", '', $text);
				$text = preg_replace("/\[(/?)[^]^[]" . $tags[$i] . "\]/", '', $text);
			}
		}
		else
		{
			$text = preg_replace("/\[(/?)[^]^[]*\]/", '', $text);
		}

		$text = nl2br($text);
		return $text;
	}

	function acronym_sort($a, $b)
	{
		if (strlen($a['acronym']) == strlen($b['acronym']))
		{
			return 0;
		}

		return (strlen($a['acronym']) > strlen($b['acronym'])) ? -1 : 1;
	}

	function acronym_pass($text)
	{
		if (!defined('IS_ICYPHOENIX'))
		{
			return $text;
		}

		static $orig, $repl;

		if(!isset($orig))
		{
			global $db, $config;
			$orig = $repl = array();

			$sql = 'SELECT * FROM ' . ACRONYMS_TABLE;
			$db->sql_return_on_error(true);
			$result = $db->sql_query($sql, 0, 'acronyms_', TOPICS_CACHE_FOLDER);
			$db->sql_return_on_error(false);
			if (!$result)
			{
				return $text;
			}

			while ($row = $db->sql_fetchrow($result))
			{
				$acronyms[] = $row;
			}
			$db->sql_freeresult($result);

			if(sizeof($acronyms))
			{
				//usort($acronyms, 'acronym_sort');
				// This use acronym_sort calling it from within BBCode object
				usort($acronyms, array('BBCode', 'acronym_sort'));
			}

			for ($i = 0; $i < sizeof($acronyms); $i++)
			{
				/* OLD CODE FOR ACRONYMS
				$orig[] = '#\b(' . phpbb_preg_quote($acronyms[$i]['acronym'], "/") . ')\b#';
				$orig[] = "/(?<=.\W|\W.|^\W)" . phpbb_preg_quote($acronyms[$i]['acronym'], "/") . "(?=.\W|\W.|\W$)/";
				*/
				$orig[] = '#\b(' . str_replace('\*', '\w*?', preg_quote(stripslashes($acronyms[$i]['acronym']), '#')) . ')\b#i';
				$repl[] = '<abbr title="' . $acronyms[$i]['description'] . '">' . $acronyms[$i]['acronym'] . '</abbr>'; ;
			}
		}

		if(sizeof($orig))
		{

			$segments = preg_split('#(<abbr.+?>.+?</abbr>|<.+?>)#s' , $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			//<?php
			//Insert for formating purpose
			$text = '';

			foreach($segments as $seg)
			{
				if(($seg[0] != '<') && ($seg[0] != '['))
				{
					$text .= str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig, \$repl, '\\0')", '>' . $seg . '<'), 1, -1));
				}
				else
				{
					$text .= $seg;
				}
			}
		}

		return $text;
	}

	// Autolinks - BEGIN
	//
	// Obtain list of autolink words and build preg style replacement arrays for use by the calling script, note that the vars are passed as references this just makes it easier to return both sets of arrays
	//
	function obtain_autolinks_list($forum_id)
	{
		global $db;

		$where = ($forum_id) ? ' WHERE link_forum = 0 OR link_forum IN (' . $forum_id . ')' : ' WHERE link_forum = -1';
		$sql = "SELECT * FROM " . AUTOLINKS . $where;
		$result = $db->sql_query($sql, 0, 'autolinks_', TOPICS_CACHE_FOLDER);

		$autolinks = array();
		while($row = $db->sql_fetchrow($result))
		{
			// Munge word boundaries to stop autolinks from linking to
			// themselves or other autolinks in step 2 in the function below.
			$row['link_url'] = preg_replace('/(\b)/', '\\1ALSPACEHOLDER', $row['link_url']);
			$row['link_comment'] = preg_replace('/(\b)/', '\\1ALSPACEHOLDER', $row['link_comment']);

			if($row['link_style'])
			{
				$row['link_style'] = preg_replace('/(\b)/', '\\1ALSPACEHOLDER', $row['link_style']);
				$style = ' style="' . htmlspecialchars($row['link_style']) . '" ';
			}
			else
			{
				$style = ' ';
			}
			$autolinks['match'][] = '/(?<![\/\w@\.:-])(?!\.\w)(' . phpbb_preg_quote($row['link_keyword'], '/'). ')(?![\/\w@:-])(?!\.\w)/i';
			if($row['link_int'])
			{
				$autolinks['replace'][] = '<a href="' . append_sid(htmlspecialchars($row['link_url'])) . '" target="_self"' . $style . 'title="' . htmlspecialchars($row['link_comment']) . '">' . htmlspecialchars($row['link_title']) . '</a>';
			}
			else
			{
				$autolinks['replace'][] = '<a href="' . htmlspecialchars($row['link_url']) . '" target="_blank"' . $style . 'title="' . htmlspecialchars($row['link_comment']) . '">' . htmlspecialchars($row['link_title']) . '</a>';
			}
		}
		$db->sql_freeresult($result);

		return $autolinks;
	}

	/**
	* Autolinks
	* Original Author - Jim McDonald - Edited by Mighty Gorgon
	*/
	function autolink_text($text, $forum_id = '')
	{
		static $autolinks;

		if (empty($text))
		{
			return $text;
		}

		if (!isset($autolinks) || !is_array($autolinks))
		{
			$autolinks = $this->obtain_autolinks_list($forum_id);
		}

		if (sizeof($autolinks))
		{
			global $config;
			// Step 1 - move all tags out of the text and replace them with placeholders
			preg_match_all('/(<a\s+.*?\/a>|<[^>]+>)/i', $text, $matches);
			$matchnum = sizeof($matches[1]);
			for($i = 0; $i < $matchnum; $i++)
			{
				$text = preg_replace('/' . preg_quote($matches[1][$i], '/') . '/', "ALPLACEHOLDER{$i}PH", $text, 1);
			}

			// Step 2 - s/r of the remaining text
			if($config['autolink_first'])
			{
				$text = preg_replace($autolinks['match'], $autolinks['replace'], $text, 1);
			}
			else
			{
				$text = preg_replace($autolinks['match'], $autolinks['replace'], $text);
			}

			// Step 3 - replace the spaces we munged in step 1
			$text = preg_replace('/ALSPACEHOLDER/', '', $text);

			// Step 4 - replace the HTML tags that we removed in step 1
			for($i = 0; $i < $matchnum; $i++)
			{
				$text = preg_replace("/ALPLACEHOLDER{$i}PH/", $matches[1][$i], $text, 1);
			}
		}

		return $text;
	}
	// Autolinks - END

	/*
	* Generate bbcode uid
	*/
	function make_bbcode_uid()
	{
		// Unique ID for this message..
		$uid = unique_id();
		$uid = substr($uid, 0, BBCODE_UID_LEN);
		return $uid;
	}

	/*
	* Make a link clickable
	*/
	function make_clickable($text)
	{
		$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1:", $text);
		$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1&#058;", $text);

		// pad it with a space so we can match things at the start of the 1st line.
		$ret = ' ' . $text;

		// matches an "xxxx://yyyy" URL at the start of a line, or after a space.
		// xxxx can only be alpha characters.
		// yyyy is anything up to the first space, newline, comma, double quote or <
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);

		// matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
		// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
		// zzzz is optional.. will contain everything up to the first space, newline,
		// comma, double quote or <.
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);


		// matches an email@domain type address at the start of a line, or after a space.
		// Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
		$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);

		// Remove our padding..
		$ret = substr($ret, 1);

		return($ret);
	}

}

$bbcode = new bbcode();

if (defined('SMILIES_TABLE'))
{
	$bbcode->allowed_smilies = array();
	$bbcode->allowed_smilies = $cache->obtain_smileys(false);
}

?>