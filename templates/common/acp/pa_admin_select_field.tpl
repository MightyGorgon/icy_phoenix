<h1>{L_FIELD_TITLE}</h1>
<p>{L_FIELD_EXPLAIN}</p>

<form action="{S_FIELD_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_FIELD_TITLE}</th></tr>
<!-- BEGIN field_row -->
<tr>
	<td width="3%" class="row1 row-center tvalignm"><input type="radio" name="field_id" value="{field_row.FIELD_ID}" /></td>
	<td width="97%" class="row1"><b>{field_row.FIELD_NAME}</b><br /><span class="gensmall">{field_row.FIELD_DESC}</span></td>
</tr>
<!-- END field_row -->
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{L_FIELD_TITLE}" name="submit" /></td></tr>
</table>
</form>

