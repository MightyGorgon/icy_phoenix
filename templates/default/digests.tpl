<script type="text/javascript">
<!-- Hide Javascript from validators
	function toggleAllForums ()
	{
		// If any particular forum is selected, this will unselect the all forums checkbox
		if (document.subscribe.all_forums.checked)
		{
			document.subscribe.all_forums.checked = false;
		}
		return;
	}

	function unCheckSubscribedForums (checkbox)
	{
		// If all forums checkbox is checked, must uncheck all the individual forums.
		is_checked= checkbox.checked;

		var element_id = new String();
		var x = document.getElementById('dynamicforums');
		for(i=0;i<x.childNodes.length;i++)
		{
			thisobject = x.childNodes[i];
			element_id = thisobject.id;
			if(element_id != null)
			{
				if(element_id.substr(0,5) == "forum")
				{
					thisobject.checked = is_checked;
				}
			}
		}
		return;
	}

	function unsubscribeCheck()
	{
		// If all forums is unchecked and none of the elected forums are checked, this
		// means unsubscribe. An unsubscribe message will occur on form submittal in this case,
		// unless the user cancels the confirm.
		var num_checked = 0;
		var process_form = true;

		var element_id = new String();
		var x = document.getElementById('dynamicforums');
		for(i=0;i<x.childNodes.length;i++)
		{
			thisobject = x.childNodes[i];
			element_id = thisobject.id;
			if(element_id != null)
			{
				if(element_id.substr(0,5) == "forum")
				{
					if (thisobject.checked == true)
					{
						num_checked++;
					}
				}
			}
		}

		// If no forums were checked but the user did not request to cancel subscription then
		// this probably means to cancel the subscription. Confirm this is the case. If the user
		// cancels the form will not be submitted.
		if ((num_checked==0) && (document.subscribe.all_forums.checked==false) && (document.subscribe.digest_type[0].checked==false))
		{
			process_form = confirm("{NO_FORUMS_SELECTED}");
			if (process_form)
			{
				document.subscribe.digest_type[0].checked = true; // set "None" radio button for digest_type
			}
		}

		return process_form;
	}
// End hiding Javascript -->
</script>

<form name="subscribe" action="{S_POST_ACTION}" method="post" onsubmit="return unsubscribeCheck();">
	<input type="hidden" name="create_new" value="{DIGEST_CREATE_NEW_VALUE}" />

	{IMG_THL}{IMG_THC}<span class="forumlink">{L_DIGESTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
		<tr><td class="row2" colspan="2" style="padding:5px;"><span class="gen">{DIGEST_EXPLANATION}</span></td></tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_DIGEST_TYPE}</span></td>
			<td class="row2" style="padding:5px;">
				<input type="radio" name="digest_type" id="none" value="NONE" /><span class="gen">{L_NONE}</span><br />
				<input type="radio" name="digest_type" {DAY_CHECKED} value="DAY" /><span class="gen">{L_DAILY}</span><br />
				<input type="radio" name="digest_type" {WEEK_CHECKED} value="WEEK" /><span class="gen">{L_WEEKLY}</span><br />
			</td>
		</tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_FORMAT}</span></td>
			<td class="row2" style="padding:5px;">
				<input type="radio" name="format" {HTML_CHECKED} value="HTML" /> <span class="gen">{L_HTML}</span><br />
				<input type="radio" name="format" {TEXT_CHECKED} value="TEXT" /> <span class="gen">{L_TEXT}</span>
			</td>
		</tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_SHOW_TEXT}</span></td>
			<td class="row2" style="padding:5px;">
				<input type="radio" name="show_text" {SHOW_TEXT_YES_CHECKED} value="YES" /> <span class="gen">{L_YES}</span>
				<input type="radio" name="show_text" {SHOW_TEXT_NO_CHECKED} value="NO" /> <span class="gen">{L_NO}</span>
			</td>
		</tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_SHOW_MINE}</span></td>
			<td class="row2" style="padding:5px;">
				<input type="radio" name="show_mine" {SHOW_MINE_YES_CHECKED} value="YES" /> <span class="gen">{L_YES}</span>
				<input type="radio" name="show_mine" {SHOW_MINE_NO_CHECKED} value="NO" /> <span class="gen">{L_NO}</span>
			</td>
		</tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_NEW_ONLY}</span></td>
			<td class="row2" style="padding:5px;">
				<input type="radio" name="new_only" {NEW_ONLY_YES_CHECKED} value="TRUE" /> <span class="gen">{L_YES}</span>
				<input type="radio" name="new_only" {NEW_ONLY_NO_CHECKED} value="FALSE" /> <span class="gen">{L_NO}</span>
			</td>
		</tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_SEND_ON_NO_MESSAGES}</span></td>
			<td class="row2" style="padding:5px;">
				<input type="radio" name="send_on_no_messages" {SEND_ON_NO_MESSAGES_YES_CHECKED} value="YES" /> <span class="gen">{L_YES}</span>
				<input type="radio" name="send_on_no_messages" {SEND_ON_NO_MESSAGES_NO_CHECKED} value="NO" /> <span class="gen">{L_NO}</span>
			</td>
		</tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_SEND_HOUR}</span></td>
			<td class="row2" style="padding:5px;">
				<select name="send_hour">
					<option value="0" {MIDNIGHT_SELECTED}>{L_MIDNIGHT}</option>
					<option value="1" {1AM_SELECTED}>{L_1AM}</option>
					<option value="2" {2AM_SELECTED}>{L_2AM}</option>
					<option value="3" {3AM_SELECTED}>{L_3AM}</option>
					<option value="4" {4AM_SELECTED}>{L_4AM}</option>
					<option value="5" {5AM_SELECTED}>{L_5AM}</option>
					<option value="6" {6AM_SELECTED}>{L_6AM}</option>
					<option value="7" {7AM_SELECTED}>{L_7AM}</option>
					<option value="8" {8AM_SELECTED}>{L_8AM}</option>
					<option value="9" {9AM_SELECTED}>{L_9AM}</option>
					<option value="10" {10AM_SELECTED}>{L_10AM}</option>
					<option value="11" {11AM_SELECTED}>{L_11AM}</option>
					<option value="12" {12PM_SELECTED}>{L_12PM}</option>
					<option value="13" {1PM_SELECTED}>{L_1PM}</option>
					<option value="14" {2PM_SELECTED}>{L_2PM}</option>
					<option value="15" {3PM_SELECTED}>{L_3PM}</option>
					<option value="16" {4PM_SELECTED}>{L_4PM}</option>
					<option value="17" {5PM_SELECTED}>{L_5PM}</option>
					<option value="18" {6PM_SELECTED}>{L_6PM}</option>
					<option value="19" {7PM_SELECTED}>{L_7PM}</option>
					<option value="20" {8PM_SELECTED}>{L_8PM}</option>
					<option value="21" {9PM_SELECTED}>{L_9PM}</option>
					<option value="22" {10PM_SELECTED}>{L_10PM}</option>
					<option value="23" {11PM_SELECTED}>{L_11PM}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="row1" style="padding:5px;"><span class="gen">{L_TEXT_LENGTH}</span></td>
			<td class="row2" style="padding:5px;">
				<select name="text_length">
					<option value="50" {50_SELECTED}>{L_50}</option>
					<option value="100" {100_SELECTED}>{L_100}</option>
					<option value="150" {150_SELECTED}>{L_150}</option>
					<option value="300" {300_SELECTED}>{L_300}</option>
					<option value="600" {600_SELECTED}>{L_600}</option>
					<option value="32000" {MAX_SELECTED}>{L_MAX}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td valign="top" class="row1" style="padding:5px;"><span class="gen">{L_FORUM_SELECTION}</span></td>
			<td class="row2" style="padding:5px;">
				<input type="checkbox" name="all_forums" {ALL_FORUMS_CHECKED} onclick="unCheckSubscribedForums(this);" /> <span class="gen">{L_ALL_SUBSCRIBED_FORUMS}</span><br />
				<div id="dynamicforums">
					<!-- BEGIN forums -->
					<input type="checkbox" name="{forums.FORUM_NAME}" id="{forums.FORUM_NAME}" {forums.CHECKED} onclick="toggleAllForums ();" /> <span class="gen">{forums.FORUM_LABEL}</span><br />
					<!-- END forums -->
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" class="catBottom" height="28"><button type="submit" class="mainoption"><span class="gen">{L_SUBMIT}</span></button>&nbsp;<button type="reset" class="liteoption"><span class="gen">{L_RESET}</span></button></td>
		</tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>