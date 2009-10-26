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
* Compress JS CSS class
*/
/*
USAGE
echo $class_compress_js_css->compress_css('whatever.css', ''); // reads whatever.css and outputs compressed code
echo $class_compress_js_css->compress_js('whatever.js'); // reads whatever.js and outputs compressed code
*/

class class_compress_js_css
{

	function compress_css($file, $urls = false)
	{
		$code = @file_get_contents($file);
		// replace urls
		if(is_string($urls))
		{
			$code = str_replace('url(' . $urls, 'url(', $code);
		}
		// remove comments
		$code = $this->_compress_clean($code, 'css');

		return $code;
	}

	function compress_js($file)
	{
		$code = @file_get_contents($file);
		// remove comments
		$code = $this->_compress_clean($code, 'js');

		return $code;
	}

	function _compress_clean($code, $type)
	{
		$start = 0;
		$lines = explode("\n", $code);
		// remove "//" type comments
		for($i = 0; $i < sizeof($lines); $i++)
		{
			if (($pos = strpos($lines[$i], '//')) !== false)
			{
				// check if its in string
				$prev = $pos > 0 ? substr($lines[$i], 0, $pos) : '';
				$count = substr_count($prev, "'") - substr_count($prev, "\\'");
				if(($count % 2) == 1) continue;
				$count = substr_count($prev, '"') - substr_count($prev, '\\"');
				if(($count % 2) == 1) continue;
				$lines[$i] = $pos > 0 ? substr($lines[$i], 0, $pos) : '';
			}
		}
		// trim lines
		for($i = 0; $i < sizeof($lines); $i++)
		{
			$lines[$i] = trim($lines[$i]);
		}
		$code = implode("\n", $lines);
		// remove /* */ comments
		$start = 0;
		while(($pos = strpos($code, '/*', $start)) !== false)
		{
			$pos2 = strpos($code, '*/', $start);
			$code = substr($code, 0, $pos) . ($pos2 === false ? '' : substr($code, $pos2 + 2));
			$start = $pos2 === false ? strlen($code) : $pos;
		}
		// remove spaces
		if ($type == 'js')
		{
			$code = str_replace(array("\t", "\n", "\r", '  '), array(' ', '', '', ' '), $code);
			while(strpos($code, '  ') !== false)
			{
				$code = str_replace('  ', ' ', $code);
			}
		}
		else
		{
			$code = str_replace(array("\t", "\n", "\r", '  '), array(' ', '', '', ' '), $code);
			while(strpos($code, '  ') !== false)
			{
				$code = str_replace('  ', ' ', $code);
			}
		}
		// remove extra characters
		if ($type == 'css')
		{
			$search = array('} ', ' }', ' {', '{ ', ': ', '; ', ', ');
			$replace = array('}', '}', '{', '{', ':', ';', ',');
			$code = str_replace($search, $replace, $code);
		}
		else
		{
			$search = array('} ', ' }', ' {', '{ ', '; ',
				' || ', ' && ', ') {', ' =', '= ', ' !', ' :', ': {',
				' +', '+ ', ' > ', ' < ', ' ? ', ') ');
			$replace = array('}', '}', '{', '{', ';',
				'||', '&&', '){', '=', '=', '!', ':', ':{',
				'+', '+', '>', '<', '?', ')');
			$code = str_replace($search, $replace, $code);
			$code = str_replace($search, $replace, $code);
		}
		return $code;
	}

	function full_compression($source_dir, $type, $files_to_compress = false)
	{
		$files = array();
		$time = 0;
		$type = ($type == 'css') ? 'css' : 'js';
		$res = @opendir($source_dir);
		$result = '';
		if($res === false)
		{
			return false;
		}
		if (!empty($files_to_compress) && is_array($files_to_compress))
		{
			foreach ($files_to_compress as $file_to_compress)
			{
				$file = $source_dir . '/' . $file_to_compress;
				$filetype = substr(strrchr($file_to_compress, '.'), 1);
				if ((file_exists($file)) && ($filetype == $type))
				{
					$files[] = $file_to_compress;
					$time = max($time, @filemtime($file));
				}
			}
		}
		else
		{
			while(($file = readdir($res)) !== false)
			{
				if(!is_dir($file) && !is_link($file) && ($file !== '.') && ($file !== '..'))
				{
					$filetype = substr(strrchr($file, '.'), 1);
					if ($filetype == $type)
					{
						$files[] = $file;
						$time = max($time, @filemtime($source_dir . '/' . $file));
					}
				}
			}
			@closedir($res);
		}

		if (empty($files))
		{
			return false;
		}

		if ($time > 0)
		{
			// check if file already exists
			$file_suffix = preg_replace('/__+/', '_', preg_replace('/[^a-z0-9_]/', '_', strtolower($source_dir)));
			while (substr($file_suffix, 0, 1) == '_')
			{
				$file_suffix = substr($file_suffix, 1);
			}
			while (substr($file_suffix, -1) == '_')
			{
				$file_suffix = substr($file_suffix, 0, -1);
			}
			$filename = MAIN_CACHE_FOLDER . $type . '_' .$file_suffix  . '_' . $time . '.' . $type;
			if (!@file_exists($filename))
			{
				// compress
				sort($files);
				$code = '';
				$size_old = 0;
				for($i = 0; $i < sizeof($files); $i++)
				{
					$size_old += filesize($source_dir . '/' . $files[$i]);
					if ($type == 'css')
					{
						$code .= $this->compress_css($source_dir . '/' . $files[$i], false);
					}
					else
					{
						$code .= $this->compress_js($source_dir . '/' . $files[$i]);
					}
				}
				$cache_name = $filename . '.temp' . mt_rand(0, 10000);
				$f = @fopen($cache_name, 'w');
				@fputs($f, $code);
				@fclose($f);
				@chmod($f, 0777);
				@unlink($filename);
				@rename($cache_name, $filename);
				$result .= '<!-- compressed from ' . $size_old . ' to ' . strlen($code) . ' -->' . "\n";
			}
			if ($type == 'css')
			{
				$result .= '<link rel="stylesheet" href="' . $filename . '" type="text/css" />' . "\n";
			}
			else
			{
				$result .= '<script src="' . $filename . '" type="text/javascript"></script>' . "\n";
			}
			return $result;
		}
		sort($files);
		for($i = 0; $i < sizeof($files); $i++)
		{
			if ($type == 'css')
			{
				$result .= '<link rel="stylesheet" href="' . $source_dir . '/' . $files[$i] . '" type="text/css" />' . "\n";
			}
			else
			{
				$result .= '<script src="' . $source_dir . '/' . $files[$i] . '" type="text/javascript"></script>' . "\n";
			}
		}
		return $result;
	}

}

?>