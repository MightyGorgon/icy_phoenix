{NAVBAR}
<h1>{L_CASH_EVENTS_TITLE}</h1>
<p>{L_CASH_EVENTS_EXPLAIN}</p>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr><th colspan="3">{L_EXISTING_EVENTS}</th></tr>
<!-- BEGIN eventrow -->
	<form action="{S_CASH_EVENTS_ACTION}" method="post"><input type="hidden" name="mode" value="edit" /><input type="hidden" name="event_name" value="{eventrow.NAME}" />
	<tr>
		<td class="row1" width="200">{eventrow.NAME}</td>
		<td class="row1" width="100"><input type="submit" name="edit" value="{L_EDIT}" class="mainoption" /></td><td class="row1" width="100"><input type="submit" name="delete" value="{L_DELETE}" class="liteoption" /></td>
	</tr>
	</form>
<!-- END eventrow -->
<!-- BEGIN switch_noevents -->
	<tr><td class="row1" colspan="2">{L_NO_EVENTS}</td></tr>
<!-- END switch_noevents -->
	<tr><td class="cat" colspan="3" align="center">&nbsp;</td></tr>
</table>
<br />
<br />
<form action="{S_CASH_EVENTS_ACTION}" method="post">
<input type="hidden" name="mode" value="add" />
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr><th colspan="2">{L_ADD_AN_EVENT}</th></tr>
	<tr>
		<td class="row1"><input class="post" type="text" maxlength="32" size="15" name="new_event_name" value="" /></td>
		<td><input type="submit" name="add" value="{L_ADD}" class="mainoption" /></td>
	</tr>
</table>
</form>

<br clear="all" />
