<h1>{L_TICKETS_EMAILS}</h1>
<p>{L_TICKETS_EMAILS_EXPLAIN}</p>
<br />

<form method="post" action="{S_TICKETS_ACTION}">
<table class="forumline">
<tr>
	<th>{L_TICKET_CAT_TITLE}</th>
	<th>{L_TICKET_CAT_DES}</th>
	<th>{L_TICKET_CAT_EMAILS}</th>
	<th>{L_ACTIONS}</th>
</tr>
<!-- IF S_NO_TICKETS -->
<tr><td class="row1 row-center" colspan="4">{L_TICKETS_NO_TICKETS}</td></tr>
<!-- ELSE -->
<!-- BEGIN ticket -->
<tr>
	<td class="{ticket.ROW_CLASS}"><b>{ticket.TICKET_TITLE}</b></td>
	<td class="{ticket.ROW_CLASS}"><span class="gensmall">{ticket.TICKET_DESCRIPTION}</span></td>
	<td class="{ticket.ROW_CLASS}"><span class="gensmall">{ticket.TICKET_EMAILS}</span></td>
	<td class="{ticket.ROW_CLASS} row-center"><a href="{ticket.U_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{ticket.U_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a></td>
</tr>
<!-- END ticket -->
<!-- ENDIF -->
<tr><td class="cat" colspan="4" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_TICKETS_DB_ADD}" class="mainoption" /></td></tr>
</table>
</form>
<br />