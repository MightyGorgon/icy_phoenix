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
* Todd - (todd@phparena.net) - (http://www.phparena.net)
*
*/

class acm
{
	var $vars = '';
	var $vars_ts = array();
	var $modified = FALSE;

	function acm()
	{
		$this->cache_dir = MAIN_CACHE_FOLDER;
	}

	function load()
	{
		@include($this->cache_dir . 'pa_file_data_global.' . PHP_EXT);
	}

	function unload()
	{
		$this->save();
		unset($this->vars);
		unset($this->vars_ts);
	}

	function save()
	{
		if (!$this->modified)
		{
			return;
		}

		$file = '<?php $this->vars=' . $this->format_array($this->vars) . ";\n\$this->vars_ts=" . $this->format_array($this->vars_ts) . ' ?>';
		/*
		if ($fp = @fopen($this->cache_dir . 'pa_file_data_global.' . PHP_EXT, 'wb'))
		{
			@flock($fp, LOCK_EX);
			fwrite($fp, $file);
			@flock($fp, LOCK_UN);
			fclose($fp);
		}
		*/
		if(@$f = fopen($this->cache_dir . 'pa_file_data_global.' . PHP_EXT, 'w'))
		{
			fwrite($f, $file);
			fclose($f);
			@chmod($this->cache_dir . 'pa_file_data_global.' . PHP_EXT, 0666);
		}

	}

	function get($varname, $expire_time = 0)
	{
		return ($this->exists($varname, $expire_time)) ? $this->vars[$varname] : NULL;
	}

	function put($varname, $var)
	{
		$this->vars[$varname] = $var;
		$this->vars_ts[$varname] = time();
		$this->modified = true;
	}

	function destroy($varname)
	{
		if (isset($this->vars[$varname]))
		{
			$this->modified = true;
			unset($this->vars[$varname]);
			unset($this->vars_ts[$varname]);
		}
	}

	function exists($varname, $expire_time = 0)
	{
		if (!is_array($this->vars))
		{
			$this->load();
		}

		if ($expire_time > 0 && isset($this->vars_ts[$varname]))
		{
			if ($this->vars_ts[$varname] <= time() - $expire_time)
			{
				$this->destroy($varname);
				return false;
			}
		}

		return isset($this->vars[$varname]);
	}

	function format_array($array)
	{
		$lines = array();
		foreach ($array as $k => $v)
		{
			if (is_array($v))
			{
				$lines[] = "'$k'=>" . $this->format_array($v);
			}
			elseif (is_int($v))
			{
				$lines[] = "'$k'=>$v";
			}
			elseif (is_bool($v))
			{
				$lines[] = "'$k'=>" . (($v) ? 'TRUE' : 'FALSE');
			}
			else
			{
				$lines[] = "'$k'=>'" . str_replace("'", "\'", str_replace('\\', '\\\\', $v)) . "'";
			}
		}
		return 'array(' . implode(',', $lines) . ')';
	}
}

?>