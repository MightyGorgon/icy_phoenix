<h1>{L_TICKETS_EMAILS}</h1>
<p>{L_TICKETS_EMAILS_EXPLAIN}</p>
<br />

<form method="post" action="{S_TICKETS_ACTION}">
<table class="forumline">
<tr><th colspan="2">{L_TICKET_CAT}</th></tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_TICKET_CAT_TITLE}:</strong></span></td>
	<td class="row2"><input type="text" name="ticket_cat_title" value="{TICKET_TITLE}" class="post" maxlength="255" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_TICKET_CAT_DES}:</strong></span></td>
	<td class="row2"><textarea name="ticket_cat_des" rows="15" cols="35" style="width: 98%;" class="post">{TICKET_DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_TICKET_CAT_EMAILS}:</strong></span><br /><span class="genmed">{L_TICKET_CAT_EMAILS_EXPLAIN}</span></td>
	<td class="row2"><textarea name="ticket_cat_emails" rows="15" cols="35" style="width: 98%;" class="post">{TICKET_EMAILS}</textarea></td>
</tr>
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>
<br />
