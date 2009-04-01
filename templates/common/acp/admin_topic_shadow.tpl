<script type="text/javascript">
<!--
function toggle_check_all()
{
	for (var i = 0; i < document.delete_ids.elements.length; i++)
	{
		var checkbox_element = document.delete_ids.elements[i];
		if ((checkbox_element.name != 'check_all_box') && (checkbox_element.type == 'checkbox'))
		{
			checkbox_element.checked = document.delete_ids.check_all_box.checked;
		}
	}
}
-->
</script>

<!-- BEGIN statusrow -->
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr><td align="center"><span class="gen">{L_STATUS}<br /></span><span class="genmed"><b>{I_STATUS_MESSAGE}</b></span><br /></td></tr>
</table>
<!-- END statusrow -->


<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left">
		<span class="maintitle">{L_PAGE_NAME}</span>
		<br /><span class="gensmall"><b>{L_VERSION} {VERSION}
		<br />{NIVISEC_CHECKER_VERSION}</b></span><br /><br />
		<span class="genmed">{L_PAGE_DESC}<br /><br />{VERSION_CHECK_DATA}</span>
	</td>
</tr>
</table>

<form method="post" action="{S_MODE_ACTION}" name="sort_and_mode">
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="right" nowrap="nowrap">
		<span class="genmed">{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
		<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
		</span>
	</td>
</tr>
</table>
</form>

<form method="post" action="{S_MODE_ACTION}" name="delete_ids">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th align="right" width="3%"><input type="checkbox" name="check_all_box" onclick="toggle_check_all()" /></th>
	<th align="left" width="45%">{L_TITLE}</th>
	<th>{L_POSTER}</th>
	<th>{L_TIME}</th>
	<th>{L_MOVED_FROM}</th>
	<th>{L_MOVED_TO}</th>
</tr>
<!-- BEGIN topicrow -->
<tr>
	<td class="{topicrow.ROW_CLASS}" align="right"><input type="checkbox" name="delete_id_{topicrow.TOPIC_ID}" /></td>
	<td class="{topicrow.ROW_CLASS}"  align="left"><span class="gen">{topicrow.TITLE}</span></td>
	<td class="{topicrow.ROW_CLASS} row-center" valign="middle"><span class="gen">{topicrow.POSTER}</span></td>
	<td class="{topicrow.ROW_CLASS} row-center" valign="middle"><span class="gensmall">{topicrow.TIME}</span></td>
	<td class="{topicrow.ROW_CLASS} row-center" valign="middle"><span class="gensmall">{topicrow.MOVED_FROM}</span></td>
	<td class="{topicrow.ROW_CLASS} row-center" valign="middle"><span class="gensmall">{topicrow.MOVED_TO}</span></td>
</tr>
<!-- END topicrow -->
<!-- BEGIN emptyrow -->
<tr><td class="row1 row-center" colspan="6"><span class="gen">{L_NO_TOPICS_FOUND}</span></td></tr>
<!-- END emptyrow -->
<tr><td class="cat" colspan="6"><input type="submit" class="mainoption" value="{L_DELETE}" />&nbsp;&nbsp;<input type="reset" class="liteoption" value="{L_CLEAR}" /></td></tr>
</table>
</form>

<form method="post" action="{S_MODE_ACTION}" name="delete_all_before">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="3">{L_DELETE_FROM_EXPLAN}</th></tr>
<tr>
	<th>{L_MONTH}<br />1 - 12</th>
	<th>{L_DAY}<br />1 - 31</th>
	<th>{L_YEAR}<br />1970 - 2038</th>
</tr>
<tr>
	<td class="row1 row-center"><input class="post" type="text" name="del_month" value="{S_MONTH}" size="2" maxlength="2" /></td>
	<td class="row2 row-center"><input class="post" type="text" name="del_day" value="{S_DAY}" size="2" maxlength="2" /></td>
	<td class="row1 row-center"><input class="post" type="text" name="del_year" value="{S_YEAR}" size="4" maxlength="4" /></td>
</tr>
<tr>
	<td class="cat" colspan="3">
		<input type="hidden" name="delete_all_before_date" value="1" />
		<input type="hidden" name="mode" value="{S_MODE}" />
		<input type="hidden" name="order" value="{S_ORDER}" />
		<input type="submit" value="{L_DELETE_BEFORE}" class="mainoption" />
	</td>
</tr>
</table>
</form>