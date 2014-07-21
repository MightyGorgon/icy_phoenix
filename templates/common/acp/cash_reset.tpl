{NAVBAR}
<h1>{L_CASH_RESET_TITLE}</h1>
<p>{L_CASH_RESET_EXPLAIN}</p>

<form action="{S_CASH_RESET_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="3">{L_CASH_RESET_TITLE}</th></tr>
<!-- BEGIN cashrow -->
<tr>
	<td class="{cashrow.ROW_CLASS}" width="50"><input type="checkbox" name="cash_check[{cashrow.ID}]" checked="checked" /></td>
	<td class="{cashrow.ROW_CLASS}" width="200">{cashrow.NAME}</td>
	<td class="{cashrow.ROW_CLASS}" width="200"><input class="post" type="text" maxlength="32" size="15" name="cash_amount[{cashrow.ID}]" value="{cashrow.DEFAULT}" /></td>
</tr>
<!-- END cashrow -->
<tr>
	<td class="cat" colspan="3">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SET_CHECKED}" class="mainoption" />&nbsp;&nbsp;
	<input type="submit" name="submit" value="{L_RECOUNT_CHECKED}" class="liteoption" />&nbsp;&nbsp;
	<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>

<br clear="all" />
