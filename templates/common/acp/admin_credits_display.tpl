<!-- BEGIN statusrow -->
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr><td align="center"><span class="gen">{L_STATUS}</span><br /><span class="genmed"><b>{I_STATUS_MESSAGE}</b></span><br /></td></tr>
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

<br />
{S_ACTION_ADD}
<br /><br />

<form method="post" action="{S_MODE_ACTION}" name="listrow_values">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_HACK_NAME}</th>
	<th>{L_AUTHOR}</th>
	<th>{L_DESCRIPTION}</th>
	<th>{L_WEBSITE}</th>
	<th>{L_USER_VIEWABLE}</th>
	<th>&nbsp;</th>
	<th><input type="submit" name="delete_sub" value="{L_DELETE}" class="liteoption" /></th>
</tr>
<!-- BEGIN empty_switch -->
<tr><td colspan="7" class="row1 row-center">{L_NO_HACKS}</td></tr>
<!-- END empty_switch -->

<!-- BEGIN listrow -->
<tr>
	<td class="{listrow.ROW_CLASS}" nowrap="nowrap"><span class="genmed">{listrow.HACK_NAME}&nbsp;{listrow.HACK_VERSION}</span></td>
	<td class="{listrow.ROW_CLASS} row-center"><span class="genmed">{listrow.HACK_AUTHOR}</span></td>
	<td class="{listrow.ROW_CLASS} row-center"><span class="genmed">{listrow.HACK_DESC}</span></td>
	<td class="{listrow.ROW_CLASS} row-center" valign="middle"><span class="gensmall">{listrow.HACK_WEBSITE}</span></td>
	<td class="{listrow.ROW_CLASS} row-center" valign="middle"><span class="genmed">{listrow.HACK_DISPLAY}</span></td>
	<td class="{listrow.ROW_CLASS} row-center" valign="middle"><span class="gen">{listrow.S_ACTION_EDIT}</span></td>
	<td class="{listrow.ROW_CLASS} row-center" valign="middle"><span class="gensmall"><input type="checkbox" name="delete_id_{listrow.HACK_ID}" /></span></td>
</tr>
<!-- END listrow -->
<tr>
	<td class="cat" colspan="6">&nbsp;</td>
	<td class="cat"><input type="submit" name="delete_sub" value="{L_DELETE}" class="liteoption" /></td>
</tr>
</table>
</form>
