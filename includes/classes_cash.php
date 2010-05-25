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

if (defined('CASH_CLASSES_INCLUDE'))
{
	return;
}

define('CASH_CLASSES_INCLUDE', true);


//
//=============[ Template extended functionality ]=========================
//

class Template_plus extends Template
{
	var $classname = 'Template';
	var $_tpldata = array();
	var $files = array();
	var $root = "";
	var $compiled_code = array();
	var $uncompiled_code = array();
	var $files_cache = array();
	var $files_cache2 = array();
	var $vars = array();
	var $cachedir = '';
	var $tpl = '';
	var $tpldir_len = 0;
	var $tpldir = '';

	function set(&$template)
	{
		$this->classname = &$template->classname;
		$this->_tpldata = &$template->_tpldata;
		$this->files = &$template->files;
		$this->root = &$template->root;
		$this->compiled_code = &$template->compiled_code;
		$this->uncompiled_code = &$template->uncompiled_code;
		$this->files_cache = &$template->files_cache;
		$this->files_cache2 = &$template->files_cache2;
		$this->vars = &$template->vars;
	  $this->cachedir = &$template->cachedir;
	  $this->tpl = &$template->tpl;
	  $this->tpldir_len = &$template->tpldir_len;
	  $this->tpldir = &$template->tpldir;
	  $this->subtemplates = &$template->subtemplates;
	}

	/**
	 * Inserts the uncompiled code for $handle as the
	 * value of $varname in the block-level. This can be used
	 * to effectively include a template in the middle of another
	 * template.
	 * Note that all desired assignments to the variables in $handle should be done
	 * BEFORE calling this function.
	 */
	function assign_block_var_from_handle($varname, $handle)
	{

		global $config;
		$this->xs_startup();
		if (!empty($this->files_cache[$handle]) && empty($this->uncompiled_code[$handle]))
		{
			// php file exists and no uncompiled code - running it instead of tpl
			ob_start();
			$this->execute($this->files_cache[$handle], '', $handle);
			$str = ob_get_contents();
			ob_end_clean();

			if (strstr($varname, '.'))
			{
				$lastposition = strrpos($varname,'.');
				$blockname = substr($varname,0,$lastposition);
				$varname = substr($varname,$lastposition+1);
				$this->reassign_block_vars($blockname,array($varname => $str));
			}
			else
			{
				$this->assign_var($varname, $str);
			}
			return true;
		}


		if (!$this->loadfile($handle))
		{
			die("Template->assign_block_var_from_handle(): Couldn't load template file for handle $handle");
		}

		// Compile it.
		if(!empty($this->files_cache2[$handle]) && empty($this->files_cache[$handle]))
		{
			$code = $this->compile2($this->uncompiled_code[$handle], $handle, $this->files_cache2[$handle]);
		}
		else
		{
			$code = $this->compile2($this->uncompiled_code[$handle], '', '');
		}

		// evaluate the variable assignment.
		ob_start();
		$this->execute('', $code ,$handle);
		$str = ob_get_contents();
		ob_end_clean();
		// assign the value of the generated variable to the given varname.

		if (strstr($varname, '.'))
		{
			$lastposition = strrpos($varname,'.');
			$blockname = substr($varname,0,$lastposition);
			$varname = substr($varname,$lastposition+1);
			$this->reassign_block_vars($blockname,array($varname => $str));
		}
		else
		{
			$this->assign_var($varname, $str);
		}

		return true;
	}

	/**
	 * Block-level variable re-assignment. Prevents new block iteration with the given
	 * variable assignments. Note that once you've iterated to a new block via assign_block_vars,
	 * you won't be able to come back to an old block.
	 */
	function reassign_block_vars($blockname, $vararray)
	{
		if (strstr($blockname, '.'))
		{
			// Nested block.
			$blocks = explode('.', $blockname);
			$blockcount = sizeof($blocks);
			$str = &$this->_tpldata;
			for($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i].'.'];
				$str = &$str[sizeof($str)-1];
			}
			// Now we add the block that we're actually assigning to.
			foreach($vararray as $key => $val)
			{
				$str[$key] = $val;
			}
		}
		else
		{
			// Top-level block.
			// Add a new iteration to this block with the variable assignments
			// we were given.
			$lastiteration = sizeof($this->_tpldata[$blockname . '.']) - 1;
			foreach($vararray as $key => $val)
			{
				$this->_tpldata[$blockname . '.'][$lastiteration][$key] = $val;
			}
		}

		return true;
	}


	/**
	* Block-level variable clearing. Removes a block of data so it can be re-written
	* fresh (for iterative file handled arrays, when different data is needed)
	*/
	function clear_block_var($blockname)
	{
		if (strstr($blockname, '.'))
		{
			// i don't know how the heck this would be used, if ever.
			// i can't think of a situation where it would be useful personally
			// but, who knows... Only the top-level block makes sense to me
			// Nested block.
			$blocks = explode('.', $blockname);
			$blockcount = sizeof($blocks) - 1;
			$str = &$this->_tpldata;
			for ($i = 0; $i < $blockcount; $i++)
			{
				$str = &$str[$blocks[$i].'.'];
				$str = &$str[sizeof($str)-1];
			}
			// Now we add the block that we're actually assigning to.
			// We're adding a new iteration to this block with the given
			// variable assignments.
			$str[$blocks[$blockcount].'.'][] = array();
		}
		else
		{
			// Top-level block.
			// Add a new iteration to this block with the variable assignments
			// we were given.
			$this->_tpldata[$blockname . '.'] = array();
		}

		return true;
	}

}

//
//=============[ Events handler ]=========================
//

if (defined('CM_EVENT'))
{
	class cash_events
	{
		var $events;

		function cash_events()
		{
			global $db;
			$this->events = array();
			$sql = "SELECT * FROM " . CASH_EVENTS_TABLE;
			$result = $db->sql_query($sql, 0, 'cash_');

			while ($row = $db->sql_fetchrow($result))
			{
				$this->events[$row['event_name']] = $row['event_data'];
			}
		}

		function get_event_data($string)
		{
			global $config;
			if ($config['cash_disable'])
			{
				return array();
			}
			if (isset($this->events[$string]))
			{
				return cash_event_unpack($this->events[$string]);
			}
			else
			{
				return array();
			}
		}
	}
	$cm_events = new cash_events();
}

//
//=============[ Memberlist handler ]=========================
//

if (defined('CM_MEMBERLIST'))
{
	class cash_memberlist
	{
		function droplists(&$sort_array)
		{
			global $config, $cash;
			if ($config['cash_disable'])
			{
				return;
			}
			while ($c_cur = &$cash->currency_next($cm_i, CURRENCY_ENABLED | CURRENCY_VIEWMEMBERLIST))
			{
				$sort_array['cash_' . $c_cur->id()] = array('lang' => $c_cur->name(true), 'mode' => 'cash_' . $c_cur->id(), 'sql' => 'u.' . 'cash_' . $c_cur->id());
			}
		}

		function modecheck($mode)
		{
			global $config, $cash;
			if ($config['cash_disable'])
			{
				return 'cash_mod';
			}
			while ($c_cur = &$cash->currency_next($cm_i,CURRENCY_ENABLED | CURRENCY_VIEWMEMBERLIST))
			{
				if ($mode == 'cash_' . $c_cur->id())
				{
					return $mode;
				}
			}
			return 'cash_mod';
		}

		function getfield($mode)
		{
			global $cash;
			$id = substr($mode,5);
			$c_cur = &$cash->currency($id);
			return $c_cur->db();
		}

		function generate_columns(&$template, &$sql, $num_columns = 8)
		{
			global $config, $cash;
			if ($config['cash_disable'])
			{
				$template->assign_var('NUM_COLUMNS',$num_columns);
				return;
			}
			// whee! now that we have the $template, we can do whatever we want with it! yay!
			$cash_field = "";
			$count = $cash->currency_count(CURRENCY_ENABLED | CURRENCY_VIEWMEMBERLIST);
			$template->assign_var('NUM_COLUMNS', $count + $num_columns);
			while ($c_cur = &$cash->currency_next($cm_i, CURRENCY_ENABLED | CURRENCY_VIEWMEMBERLIST))
			{
				$template->assign_block_vars('cashrow',array('NAME' => $c_cur->name()));
				$cash_field .= $c_cur->db() . ', ';
			}
			if (strstr($sql,'*'))
			{
				return;
			}
			$insertpoint = strpos($sql, 'user_id');
			$sql = substr($sql, 0, $insertpoint) . $cash_field . substr($sql, $insertpoint);
		}

		function listing(&$template,&$row)
		{
			global $config, $cash;
			if ($config['cash_disable'])
			{
				return;
			}
			while ($c_cur = &$cash->currency_next($cm_i,CURRENCY_ENABLED | CURRENCY_VIEWMEMBERLIST))
			{
				$template->assign_block_vars('memberrow.cashrow', array('CASH_DISPLAY' => $c_cur->display($row[$c_cur->db()])));
			}
		}
	}
	$cm_memberlist = new cash_memberlist();
}

//
//=============[ Viewtopic handler ]=========================
//

if (defined('CM_VIEWTOPIC'))
{
	class cash_viewtopic
	{
		var $template;

		function generate_columns(&$template, $forum_id, &$sql)
		{
			global $config, $cash;
			if ($config['cash_disable'])
			{
				return '';
			}
			$this->template = new Template_plus();
			$this->template->set($template);
			$this->template->set_filenames(array('cm_viewtopic' => 'cash_viewtopic.tpl'));

			if (strstr($sql, 'u.*'))
			{
				return;
			}
			$cash_field = "";
			while ($c_cur = &$cash->currency_next($cm_i,CURRENCY_ENABLED | CURRENCY_VIEWTOPIC,$forum_id))
			{
				$cash_field .= 'u.' . $c_cur->db() . ', ';
			}
			$insertpoint = strpos($sql,'u.user_id');
			$sql = substr($sql,0,$insertpoint) . $cash_field . substr($sql,$insertpoint);
		}

		function post_vars(&$postdata, &$userdata, $forum_id)
		{
			$template = &$this->template;
			global $config, $lang, $cash;
			if ($config['cash_disable'])
			{
				return;
			}
			$mask = false;
			if ($userdata['user_level'] != ADMIN)
			{
				$mask = CURRENCY_ENABLED;
				if ($postdata['user_id'] != $userdata['user_id'])
				{
					$mask &= CURRENCY_VIEWTOPIC;
				}
			}
			else
			{
				$forum_id = false;
			}
			if ($postdata['user_id'] != -1)
			{
				while ($c_cur = &$cash->currency_next($cm_i,$mask,$forum_id))
				{
					$template->assign_block_vars('cashrow', array(
						'CASH_DISPLAY' => $c_cur->display($postdata[$c_cur->db()])
						)
					);
				}
				if (($cash->currency_count(CURRENCY_ENABLED | CURRENCY_EXCHANGEABLE) >= 2) && $userdata['session_logged_in'])
				{
					$template->assign_block_vars('cashlinks',array(
						'U_LINK' => append_sid('cash.' . PHP_EXT),
						'L_NAME' => $lang['Exchange']
						)
					);
				}
				if ($cash->currency_count(CURRENCY_ENABLED | CURRENCY_DONATE, $forum_id) && ($userdata['user_id'] != $postdata['user_id']) && $userdata['session_logged_in'])
				{
					$template->assign_block_vars('cashlinks',array(
						'U_LINK' => append_sid('cash.' . PHP_EXT . '?mode=donate&amp;ref=viewtopic&amp;' . POST_USERS_URL . '=' . $postdata['user_id'] . '&amp;' . POST_POST_URL . '=' . $postdata['post_id']),
						'L_NAME' => $lang['Donate']
						)
					);
				}
				if ($cash->currency_count() && (($userdata['user_level'] == ADMIN) || (($userdata['user_level'] == MOD) && $cash->currency_count(CURRENCY_ENABLED | CURRENCY_MODEDIT | CURRENCY_VIEWTOPIC, $forum_id))))
				{
					$template->assign_block_vars('cashlinks',array(
						'U_LINK' => append_sid('cash.' . PHP_EXT . '?mode=modedit&amp;ref=viewtopic&amp;' . POST_USERS_URL . '=' . $postdata['user_id'] . '&amp;' . POST_POST_URL . '='.$postdata['post_id']),
						'L_NAME' => sprintf($lang['Mod_usercash'],$postdata['username'])
						)
					);
				}
				$template->assign_block_var_from_handle('postrow.CASH', 'cm_viewtopic');
				$template->clear_block_var('cashrow');
				$template->clear_block_var('cashlinks');
			}
		}
	}
	$cm_viewtopic = new cash_viewtopic();
}

//
//=============[ Viewprofile handler ]=========================
//

if (defined('CM_VIEWPROFILE'))
{
	class cash_viewprofile
	{
		function post_vars(&$old_template, &$profiledata, &$userdata)
		{
			global $config, $lang, $cash;
			if ($config['cash_disable'])
			{
				return;
			}
			$template = new Template_plus();
			$template->set($old_template);
			$mask = false;
			if ($userdata['user_level'] != ADMIN)
			{
				$mask = CURRENCY_ENABLED;
				if ($profiledata['user_id'] != $userdata['user_id'])
				{
					$mask &= CURRENCY_VIEWPROFILE;
				}
			}
			$template->set_filenames(array(
				'cm_viewprofile' => 'cash_viewprofile.tpl')
			);
			while ($c_cur = &$cash->currency_next($cm_i,$mask))
			{
				$template->assign_block_vars('cashrow', array(
					'CASH_NAME' => $c_cur->name(),
					'CASH_AMOUNT' => $profiledata[$c_cur->db()]
					)
				);
			}
			if ($userdata['session_logged_in'] && (($cash->currency_count(CURRENCY_ENABLED | CURRENCY_EXCHANGEABLE) >= 2) || ($cash->currency_count(CURRENCY_ENABLED | CURRENCY_DONATE) && ($userdata['user_id'] != $profiledata['user_id'])) || ($cash->currency_count() && (($userdata['user_level'] == ADMIN) || (($userdata['user_level'] == MOD) && $cash->currency_count(CURRENCY_ENABLED | CURRENCY_MODEDIT))))))
			{
				$template->assign_block_vars('switch_cashlinkson',array());
				if ($cash->currency_count(CURRENCY_ENABLED | CURRENCY_EXCHANGEABLE) >= 2)
				{
					$template->assign_block_vars('switch_cashlinkson.cashlinks',array(
						'U_LINK' => append_sid('cash.' . PHP_EXT),
						'L_NAME' => $lang['Exchange']
						)
					);
				}
				if ($cash->currency_count(CURRENCY_ENABLED | CURRENCY_DONATE) && ($userdata['user_id'] != $profiledata['user_id']))
				{
					$template->assign_block_vars('switch_cashlinkson.cashlinks',array(
						'U_LINK' => append_sid('cash.' . PHP_EXT . '?mode=donate&amp;ref=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
						'L_NAME' => $lang['Donate']
						)
					);
				}
				if ($cash->currency_count() && (($userdata['user_level'] == ADMIN) || (($userdata['user_level'] == MOD) && $cash->currency_count(CURRENCY_ENABLED | CURRENCY_MODEDIT))))
				{
					$template->assign_block_vars('switch_cashlinkson.cashlinks',array(
						'U_LINK' => append_sid('cash.' . PHP_EXT . '?mode=modedit&amp;ref=viewprofile&amp;' . POST_USERS_URL . '=' . $profiledata['user_id']),
						'L_NAME' => sprintf($lang['Mod_usercash'],$profiledata['username'])
						)
					);
				}
			}
			$template->assign_block_var_from_handle('CASH', 'cm_viewprofile');
		}
	}
	$cm_viewprofile = new cash_viewprofile();
}

//
//=============[ Posting handler ]=========================
//

if (defined('CM_POSTING'))
{
	class cash_posting
	{

		function update_post($mode, &$post_data, $forum_id, $topic_id, $post_id, $topic_type, $post_username, &$post_message)
		{
			global $config, $userdata;
			if ($config['cash_disable'] || (($mode != 'newtopic') && ($mode != 'reply') && ($mode != 'editpost')))
			{
				return '';
			}
			$first_post = $post_data['first_post'];
			$poster_id = $userdata['user_id'];
			$old_message = '';
			if ($mode == 'editpost')
			{
				$poster_id = $post_data['poster_id'];
				$old_message = &$post_data['post_text'];
			}
			if ($mode == 'reply')
			{
				$topic_starter = $post_data['topic_poster'];
			}
			else
			{
				$topic_starter = false;
			}
			return $this->cash_update($mode, $poster_id, $first_post, $old_message, $post_message, $forum_id, $topic_id, $post_id, $topic_starter);
		}

		function update_delete($mode, &$post_data, $forum_id, $topic_id, $post_id)
		{
			global $config;
			if ($config['cash_disable'] || ($mode != 'delete'))
			{
				return;
			}
			$first_post = $post_data['first_post'];
			$poster_id = $post_data['poster_id'];
			$topic_starter = ANONYMOUS;
			$this->cash_update($mode, $poster_id, $first_post, $post_data['post_text'], $new_message, $forum_id, $topic_id, $post_id, $topic_starter);
		}

		function cash_update($mode, $poster_id, $first_post, &$old_message, &$new_message, $forum_id, $topic_id, $post_id, $topic_starter)
		{
			global $config, $lang, $db, $userdata, $cash;

			if (($mode == 'reply') && ($poster_id != $topic_starter) && ($topic_userdata = get_userdata($topic_starter)))
			{
				$topic_creator = new cash_user($topic_starter, $topic_userdata);
				$topic_creator->give_bonus($topic_id);
			}
			if ($poster_id == ANONYMOUS)
			{
				return;
			}
			if ($userdata['user_id'] == $poster_id)
			{
				$posting_user = new cash_user($userdata['user_id'], $userdata);
			}
			else
			{
				$posting_user = new cash_user($poster_id);
			}
			$all_active = true;
			$forumcount = array();
			$forumlist = array();
			if ((($mode == 'newtopic') || ($mode == 'reply')) && (intval($config['cash_disable_spam_num']) > 0))
			{
				$all_active = false;
				$interval = time() - (3600 * intval($config['cash_disable_spam_time']));
				$sum = 0;
				$sql = "SELECT forum_id, count(post_id) as postcount
					FROM " . POSTS_TABLE . "
					WHERE poster_id = $poster_id
						AND post_time > $interval
					GROUP BY forum_id";
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$forumlist[] = $row['forum_id'];
					$forumcount[$row['forum_id']] = $row['postcount'];
					$sum += $row['postcount'];
				}
				if ($sum < $config['cash_disable_spam_num'])
				{
					$all_active = true;
				}
			}
			$new_len = array(strlen($new_message), cash_quotematch($new_message));
			$old_len = array(strlen($old_message), cash_quotematch($old_message));
			$sql_clause = array();
			$message_clause = array();
			$reply_bonus = array();
			$all_spam = !$all_active;
			while ($c_cur = &$cash->currency_next($cm_i, CURRENCY_ENABLED, $forum_id))
			{
				$this_enabled = $all_active;
				if (!$all_active)
				{
					$sum = 0;
					for ($i = 0; $i < sizeof($forumlist); $i++)
					{
						if ($c_cur->forum_active($forumlist[$i]))
						{
							$sum += $forumcount[$forumlist[$i]];
						}
					}
					if ($sum < $config['cash_disable_spam_num'])
					{
						$this_enabled = true;
						$all_spam = false;
					}
				}
				if ($this_enabled)
				{
					$base = ($first_post) ? $posting_user->get_setting($c_cur->id(), 'cash_perpost') : $posting_user->get_setting($c_cur->id(), 'cash_perreply');
					$perchar = $posting_user->get_setting($c_cur->id(), 'cash_perchar', PERCHAR_DEC_BONUS);
					$max = $posting_user->get_setting($c_cur->id(), 'cash_maxearn');
					$quotes = ($c_cur->mask(CURRENCY_INCLUDEQUOTES)) ? 0 : 1;

					$total_added = ($mode != 'delete') ? min($max, $base + ($perchar * $new_len[$quotes])) : 0;
					$total_removed = (($mode != 'newtopic') && ($mode != 'reply')) ? min($max, $base + ($perchar * $old_len[$quotes])) : 0;
					$total_change = $total_added - $total_removed;
					if ($total_change != 0)
					{
						$change_sign = ($total_change > 0);
						$change_amount = (($change_sign) ? $total_change : (-$total_change));
						$change_sign = (($change_sign) ? " + " : " - ");
						$sql_clause[] = $c_cur->db() . " = " . $c_cur->db() . $change_sign . $change_amount;
						$message_clause[] = $c_cur->display($change_amount);
					}
				}
			}
			if ($all_spam)
			{
				return $config['cash_disable_spam_message'];
			}
			if (sizeof($sql_clause) > 0)
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET " . implode(', ', $sql_clause) . "
					WHERE user_id = " . $poster_id;
				$db->sql_query($sql);

				return (($userdata['user_id'] == $poster_id) && ($config['cash_display_after_posts'] == 1)) ? sprintf($config['cash_post_message'], implode(', ', $message_clause)) : '';
			}
		}

		function cash_update_thanks($poster_id)
		{
			global $config, $lang, $db, $userdata, $cash;

			$posting_user = new cash_user($poster_id);
			$sql_clause = array();
			$message_clause = array();
			while ($c_cur = &$cash->currency_next($cm_i, CURRENCY_ENABLED, $forum_id))
			{
				$perthanks = $posting_user->get_setting($c_cur->id(), 'cash_perthanks');
				$sql_clause[] = $c_cur->db() . ' = ' . $c_cur->db() . ' + ' . $perthanks;
				$message_clause[] = $c_cur->display($perthanks);
			}
			if (sizeof($sql_clause) > 0)
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET " . implode(', ', $sql_clause) . "
					WHERE user_id = " . $poster_id;
				$db->sql_query($sql);

				// Mighty Gorgon: to be fixed because it returns ARRAY
				//return $message_clause;
				return '';
			}
		}

	}
	$cm_posting = new cash_posting();
}

//
//=============[ END Page-Specific Classes ]=========================
//

?>