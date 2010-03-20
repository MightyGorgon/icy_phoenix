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

define('IN_ICYPHOENIX', true);
define('IN_CASHMOD', true);

if (!defined('IP_ROOT_PATH')) define('IP_ROOT_PATH', './../');
if (!defined('PHP_EXT')) define('PHP_EXT', substr(strrchr(__FILE__, '.'), 1));
require('pagestart.' . PHP_EXT);
include_once(IP_ROOT_PATH . 'includes/functions_selects.' . PHP_EXT);

if (empty($config['plugins']['cash']['enabled']))
{
	message_die(GENERAL_MESSAGE, 'PLUGIN_DISABLED');
}

if ($config['cash_adminnavbar'])
{
	$navbar = 1;
	include('./admin_cash.' . PHP_EXT);
}

if (!$cash->currency_count())
{
	message_die(GENERAL_MESSAGE, $lang['Insufficient_currencies']);
}

$mode = request_var('mode', 'main');
$event_name = request_var('event_name', '', true);
$new_event_name = request_var('new_event_name', '', true);

switch ($mode)
{

//
// ================= Add event (no data) ================================
//
	case 'add':
		if (!empty($new_event_name))
		{
			$sql = "INSERT INTO " . CASH_EVENTS_TABLE . " (event_name, event_data) VALUES ('" . $db->sql_escape($new_event_name) . "', '')";
			$db->sql_query($sql);

			message_die(GENERAL_MESSAGE, $lang['Cash_events_updated'] . '<br /><br />' . sprintf($lang['Click_return_cash_events'], '<a href="' . append_sid('cash_events.' . PHP_EXT) . '">', '</a>') . '<br /><br />');
		}
		break;
//
// ================= Update board mode (submitted form) ================================
//
	case 'update':
		if (!empty($event_name) && isset($_POST['cash']) && is_array($_POST['cash']))
		{
			$updates = array();
			$updated_data = '';
			while ($c_cur = &$cash->currency_next($cm_i))
			{
				if (isset($_POST['cash'][$c_cur->id()]))
				{
					$entry = cash_floatval($_POST['cash'][$c_cur->id()]);
					if($entry != 0)
					{
						$updates[] = $c_cur->id() . CASH_EVENT_DELIM2 . $entry;
					}
				}
			}
			if (sizeof($updates))
			{
				$updated_data = implode(CASH_EVENT_DELIM1,$updates);
			}
			$sql = "UPDATE " . CASH_EVENTS_TABLE . "
					SET event_data = '" . $updated_data . "'
					WHERE event_name = '" . $db->sql_escape($event_name) . "'";
			$db->sql_query($sql);

			message_die(GENERAL_MESSAGE, $lang['Cash_events_updated'] . '<br /><br />' . sprintf($lang['Click_return_cash_events'], '<a href="' . append_sid('cash_events.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>'));
		}
		break;
//
// ================= Edit board mode (or delete) ================================
//
	case 'edit':
		if (!empty($event_name))
		{
			if (isset($_POST['delete']))
			{
				$sql = "DELETE FROM " . CASH_EVENTS_TABLE . " WHERE event_name = '" . $db->sql_escape($event_name) . "'";
				$db->sql_query($sql);

				message_die(GENERAL_MESSAGE, $lang['Cash_events_updated'] . '<br /><br />' . sprintf($lang['Click_return_cash_events'], '<a href="' . append_sid('cash_events.' . PHP_EXT) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . PHP_EXT . '?pane=right') . '">', '</a>'));
			}
			$sql = "SELECT *
					FROM " . CASH_EVENTS_TABLE . "
					WHERE event_name = '" . $db->sql_escape($event_name) . "'";
			$result = $db->sql_query($sql);

			if (!($row = $db->sql_fetchrow($result)))
			{
				message_die(CRITICAL_ERROR, 'Event does not exist', '', __LINE__, __FILE__, $sql);
			}

			$cash_amounts = cash_event_unpack($row['event_data']);

			$template->set_filenames(array('body' => ADM_TPL . 'cash_event.tpl'));

			$hidden_fields = '<input type="hidden" name="event_name" value="' . $event_name . '" /><input type="hidden" name="mode" value="update" />';
			$template->assign_vars(array(
				'S_CASH_EVENTS_ACTION' => append_sid('cash_events.' . PHP_EXT),
				'S_HIDDEN_FIELDS' => $hidden_fields,

				'L_CASH_EVENTS_TITLE' => $lang['Cash_events'],
				'L_CASH_EVENTS_EXPLAIN' => $lang['Cash_events_explain'],

				'EVENT_NAME' => $event_name,

				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset']
				)
			);

			$i = 0;
			while ($c_cur = &$cash->currency_next($cm_i))
			{
				$i++;
				$template->assign_block_vars('cashrow',array(
					'CLASS' => ((!($i % 2)) ? $theme['td_class1'] : $theme['td_class2']),
					'CASH_NAME' => $c_cur->name(),
					'S_CASH_FIELD' => 'cash[' . $c_cur->id() . ']',
					'AMOUNT' => isset($cash_amounts[$c_cur->id()]) ? $cash_amounts[$c_cur->id()] : 0
					)
				);
			}

			$template->pparse('body');

			include('./page_footer_admin.' . PHP_EXT);

		}
		break;
//
// ================= Default board mode (listing) ================================
//
	default:
		$sql = "SELECT *
				FROM " . CASH_EVENTS_TABLE;
		$result = $db->sql_query($sql);

		$events = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$events[] = $row;
		}

		$template->set_filenames(array('body' => ADM_TPL . 'cash_events.tpl'));

		$template->assign_vars(array(
			'S_CASH_GROUPS_ACTION' => append_sid('cash_events.' . PHP_EXT),

			'L_CASH_EVENTS_TITLE' => $lang['Cash_events'],
			'L_CASH_EVENTS_EXPLAIN' => $lang['Cash_events_explain'],

			'L_EXISTING_EVENTS' => $lang['Existing_events'],
			'L_ADD_AN_EVENT' => $lang['Add_an_event'],

			'L_EDIT' => $lang['Edit'],
			'L_DELETE' => $lang['Delete'],
			'L_ADD' => $lang['Add'],
			'L_NO_EVENTS' => $lang['No_events']
			)
		);

		for ($i = 0; $i < sizeof($events); $i++)
		{
			$template->assign_block_vars('eventrow',array('NAME' => $events[$i]['event_name']));
		}
		if (!sizeof($events))
		{
			$template->assign_block_vars('switch_noevents',array());
		}

		$template->pparse('body');

		include('./page_footer_admin.' . PHP_EXT);

		break;
}

?>