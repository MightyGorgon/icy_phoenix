<h1>{L_FIELD_TITLE}</h1>
<p>{L_FIELD_EXPLAIN}</p>

<form action="{S_FIELD_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_FIELD_TITLE}</b></th> </tr>
<!-- BEGIN field_row -->
<tr>
	<td width="3%" class="row1 row-center" valign="middle"><input type="checkbox" name="field_ids[{field_row.FIELD_ID}]" value="yes" /></td>
	<td width="97%" class="row1"><b>{field_row.FIELD_NAME}</b><br /><span class="gensmall">{field_row.FIELD_DESC}</span></td>
</tr>
<!-- END field_row -->
<tr><td align="center" class="cat" colspan="2">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" value="{L_FIELD_TITLE}" name="submit" /></td></tr>
</table>
</form>
