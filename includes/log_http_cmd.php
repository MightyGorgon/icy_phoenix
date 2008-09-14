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

// NOTE: this file is included from within a function!
// If we need to access general variables they must be declared global!
//global $_POST, $_GET, $_SERVER;

// Grab page data
$page_array = array();
$page_array = extract_current_page(IP_ROOT_PATH);
//dump_ary($page_array);

// Temp vars
$_varary = array();
$_tmp1 = '';
$_tmp2 = '';
// DONE: get protocol: GET, HEAD, POST, PUT
$_prot = $_SERVER['REQUEST_METHOD'];
$content = '';

// Simplify oft used variables
$_mode = '';
if ( isset($_POST['mode']) || isset($_GET['mode']) )
{
	$_mode = ( isset($_POST['mode']) ) ? $_POST['mode'] : $_GET['mode'];
	$_mode = htmlspecialchars($_mode);
	$content .= '[MODE: ' . $_mode . '] - ';
}
$_confirm = ( isset($_POST['confirm']) ) ? true : 0;
$content .= '[CONFIRM: ' . $_confirm . '] - ';

if ( isset($_GET['p']) || isset($_POST['p']) )
{
	$_post = (isset($_POST['p'])) ? intval($_POST['p']) : intval($_GET['p']);
	$content .= '[POST: ' . $_post . '] - ';
}
if ( isset($_GET['t']) || isset($_POST['t']) )
{
	$_topic = (isset($_POST['t'])) ? intval($_POST['t']) : intval($_GET['t']);
	$content .= '[TOPIC: ' . $_topic . '] - ';
}
if ( isset($_GET['f']) || isset($_POST['f']) )
{
	$_forum = (isset($_POST['f'])) ? intval($_POST['f']) : intval($_GET['f']);
	$content .= '[FORUM: ' . $_forum . '] - ';
}
if ( isset($_GET['u']) || isset($_POST['u']) )
{
	$_user = (isset($_POST['u'])) ? intval($_POST['u']) : intval($_GET['u']);
	$content .= '[USER: ' . $_user . '] - ';
}


// Log general visits - do this before action-logging (makes sure $userdata['log_id'] relates to the action rather than visit)
// Skip Visits-logging on certain pages by adding them to this switch-case
switch($page_array['page_name'])
{
	case POSTING_MG:
		$content .= 'POSTING';
		if($mode == 'topicreview')
		{
			break;
		}
		// Log if not review (review is loaded in the frame when replying)
		mg_log($content);
		break;
	default:
		//mg_log($content);
}


// Diff log-schemas for each page
//if(!empty($_POST)){
	//dump_ary($_POST); // easy way to figure out scheme-variables to check
//}
if($page_array['page_dir'] == ADM)
{
	// ACP Logging
	switch($page_array['page_name'])
	{
		case 'admin_forums.' . PHP_EXT:
			if(isset($_POST['addcategory']) && !empty($_POST['categoryname']))
			{
				$content .= '[Category: ' . $_POST['categoryname'] . ']';
				mg_log($content);
			}
			break;
		case 'admin_forumauth.' . PHP_EXT:
			if( isset($_POST['simpleauth']) && $_forum)
			{
				$content .= '[Forum Auth: ' . $_forum . ']';
				mg_log($content);
			}
			break;
		case 'admin_db_utilities.' . PHP_EXT:
			if(isset($_POST['perform']))
			{
				if($_POST['perform'] == 'backup')
				{
					$content .= '[DB Backup: ' . $_POST['backup_type'] . ']';
					mg_log($content);
				}
				elseif ($_POST['perform'] == 'restore')
				{
					$content .= '[DB Restore: ' . $_POST['backup_file'] . ']';
					mg_log($content);
				}
			}
			break;
		case ( ('admin_board.' . PHP_EXT) || ('admin_board_extend.' . PHP_EXT) || ('admin_board_headers_banners.' . PHP_EXT) || ('admin_board_main.' . PHP_EXT) || ('admin_board_posting.' . PHP_EXT) || ('admin_board_queries.' . PHP_EXT) || ('admin_board_quick_settings.' . PHP_EXT) || ('admin_board_server.' . PHP_EXT) ):
			if(isset($_POST['submit']))
			{
					$content .= '[Board Config]';
					mg_log($content);
			}
			break;
		case 'admin_groups.' . PHP_EXT:
			if($_mode == 'newgroup')
			{
				$content .= '[Group Name: ' . $_POST['group_name'] . ']';
				mg_log($content);
			}
			elseif($_mode == 'editgroup' && $_POST['group_delete'] == 1)
			{
				$content .= '[Group Delete: ' . $_POST['group_name'] . ']';
				mg_log($content);
			}
			elseif($_mode == 'editgroup' && isset($_POST['g']))
			{
				$content .= '[Group Edit: ' . $_POST['group_name'] . ']';
				mg_log($content);
			}
			break;
		case 'admin_ug_auth.' . PHP_EXT:
			if( $_mode == 'user' && isset($_POST['submit']) )
			{
				$content .= '[User Auth: ' . $_POST['u'] . ' ==> . ' . $_POST['userlevel'] . ']';
				mg_log($content);
			}
			elseif( isset($_POST['g']) && isset($_POST['adv']) )
			{
				$content .= '[Group Auth: ' . $_POST['g'] . ']';
				mg_log($content);
			}
			break;
		case 'admin_user_ban.' . PHP_EXT:
			if($_mode == 'edit')
			{
				$_data = '';
				if( !empty($_POST['ban_email']) )
				{
					$_data = $_POST['ban_email'];
				}
				if( !empty($_POST['ban_ip']) )
				{
					$_data .= ( ( $_data != '' ) ? ', ' : '' ) . $_POST['ban_ip'];
				}
				if( !empty($_POST['username']) )
				{
					$_data .= ( ( $_data != '' ) ? ', ' : '' ) . $_POST['username'];
				}
				if( !empty($_data) )
				{
					$content .= '[Ban Edit: ' . $_data . ']';
					mg_log($content);
				}
				$_data = '';
				// NOTE: we only know the ban_id being unbanned... not what user,ip or email
				if( !empty($_POST['unban_user']) )
				{
					foreach($_POST['unban_user'] as $key => $val)
					{
						if($val > 0)
						{
							$_data .= ( ( $_data != '' ) ? ', ' : '' ) . $val;
						}
					}
				}
				if( !empty($_POST['unban_ip']) )
				{
					foreach($_POST['unban_ip'] as $key => $val)
					{
						if($val > 0)
						{
							$_data .= ( ( $_data != '' ) ? ', ' : '' ) . $val;
						}
					}
				}
				if( !empty($_POST['unban_email']) )
				{
					foreach($_POST['unban_email'] as $key => $val)
					{
						if($val > 0)
						{
							$_data .= ( ( $_data != '' ) ? ', ' : '' ) . $val;
						}
					}
				}
				if( !empty($_data) )
				{
					$content .= '[Ban Edit: ' . $_data . ']';
					mg_log($content);
				}
			}
			break;
		case 'admin_users.' . PHP_EXT:
			if( $_mode == 'save' && isset($_POST['id']) && isset($_POST['deleteuser']) )
			{
				$content .= '[User Delete: ' . $_POST['id'] . ' ==> ' . $_POST['username'] . ']';
				mg_log($content);
			}
			elseif( $_mode == 'save' && isset($_POST['id']) )
			{
				$content .= '[User Edit: ' . $_POST['id'] . ' ==> ' . $_POST['username'] . ']';
				mg_log($content);
			}
			break;
		default:
			// No default action
	}
}
elseif($page_array['page_dir'] == '')
{
	switch($page_array['page_name'])
	{
		case POSTING_MG:
			// post-deletion, edits
			if($_mode == 'delete' && $_confirm && $_post)
			{
				$content .= '[Post Delete: ' . $_post . ']';
				mg_log($content);
			}
			elseif($_mode == 'editpost' && $_post && isset($_POST['post']))
			{
				$content .= '[Post Edit: ' . $_POST['subject'] . ' ==> ' . $_post . ']';
				mg_log($content);
			}
			break;
		case 'groupcp.' . PHP_EXT:
			if(( isset($_GET['g']) || isset($_POST['g']) ))
			{
				// both the POST and the GET 'g' var should be set
				$_tmp1 = ( isset($_GET['g']) ) ? intval($_GET['g']) : intval($_POST['g']);
			}
			if($_tmp1 > 0){
				// Only log if we actually have a group_id
				if(isset($_POST['joingroup']))
				{
					$content .= '[Group Join: ' . $_tmp1 . ']';
					mg_log($content);
				}
				if ( ( ( isset($_POST['approve']) || isset($_POST['deny']) ) && isset($_POST['pending_members']) ) || ( isset($_POST['remove']) && isset($_POST['members']) ) )
				{
					if( isset($_POST['remove']) )
					{
						$_varary = $_POST['members'];
					}
					else
					{
						$_varary = $_POST['pending_members'];
					}

					$_data = '';
					for($i = 0; $i < count($_varary); $i++)
					{
						$_data .= ( ( $_data != '' ) ? ', ' : '' ) . intval($_varary[$i]);
					}
					$content .= '[Group Edit: ' . $_tmp1 . ' ==> ' . $_data . ']';
					mg_log($content);
				}
				elseif(isset($_POST['add']) && isset($_POST['username']))
				{
					$content .= '[Group Add: ' . $_tmp1 . ' ==> ' . $_POST['username'] . ']';
					mg_log($content);
				}
				elseif(isset($_POST['groupstatus']) && isset($_POST['group_type']))
				{
					$content .= '[Group Type: ' . $_tmp1 . ' ==> ' . intval($_POST['group_type']) . ']';
					mg_log($content);
				}
			}
			break;
		/*
		case PROFILE_MG:
			if($_mode == 'register' && isset($_POST['agreed']) && $_prot == 'POST')
			{
				if(!empty($_POST['username']) && !empty($_POST['website']))
				{
					$content .= '[User Register: ' . $_tmp1 . ' ==> ' . $_POST['website'] . ']';
					mg_log($content);
				}
				elseif(!empty($_POST['username']))
				{
					$content .= '[Profile Edit: ' . $_tmp1 . ' ==> ' . $_POST['website'] . ']';
					mg_log($content);
				}
			}
			break;
		*/
		case 'modcp.' . PHP_EXT:
			if( ($_mode == 'move') || ($_mode == 'delete') || ($_mode == 'lock') || ($_mode == 'unlock') || ($_mode == 'merge') || ($_mode == 'recycle') )
			{
				if($_confirm)
				{
					$_varary = ( isset($_POST['topic_id_list']) ) ? $_POST['topic_id_list'] : array($_topic);
					$_data = '';
					for($i = 0; $i < count($_varary); $i++)
					{
						$_data .= ( ( $_data != '' ) ? ', ' : '' ) . intval($_varary[$i]);
					}
					if( $_mode == 'delete')
					{
						$content .= '[Topic Delete: ' . $_data . ']';
					}
					if( $_mode == 'recycle')
					{
						$content .= '[Topic Recycle: ' . $_data . ']';
					}
					if( $_mode == 'lock')
					{
						$content .= '[Topic Lock: ' . $_data . ']';
					}
					if( $_mode == 'unlock')
					{
						$content .= '[Topic Unlock: ' . $_data . ']';
					}
					if( $_mode == 'move')
					{
						$content .= '[Topic Move: ' . $_data . ' ==> ' . intval($_POST['new_forum']) . ']';
					}
					if( $_mode == 'merge')
					{
						$content .= '[Topic Merge: ' . $_data . ' ==> ' . intval($_POST['new_topic']) . ']';
					}
					mg_log($content);
				}
			}
			if($_mode == 'split')
			{
				if (isset($_POST['split_type_all']) || isset($_POST['split_type_beyond']))
				{
					$_varary = $_POST['post_id_list'];

					for ($i = 0; $i < count($_varary); $i++)
					{
						$_data .= (($_data != '') ? ', ' : '') . intval($_varary[$i]);
					}
					$content .= '[Topic Split: ' . $_data . ' ==> ' . intval($_POST['new_forum_id']) . ']';
					mg_log($content);
				}
			}
			break;
		case 'bin.' . PHP_EXT:
			$content .= '[Topic Recycle: ' . $_topic . ']';
			mg_log($content);
			break;
		case 'viewtopic.' . PHP_EXT:
			// Log hackattacks to warnings log
			if (isset($_GET['highlight']) )
			{
				if(preg_match('/system\(chr\(\d+\)/', $_GET['highlight']))
				{
					$content .= '[Viewtopic Attack: ' . $_GET['highlight'] . ']';
					mg_log($content);
				}
			}
			break;
		default:
	}
}

// unset temp-vars
unset($_mode, $_data, $_post, $_topic, $_forum, $_user, $_confirm, $_content, $_tmp1, $_tmp2, $_varary);


?>