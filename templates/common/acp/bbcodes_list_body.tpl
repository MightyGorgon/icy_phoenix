<h1>{L_BBCODES_CUSTOM_BBCODES}</h1>
<p>{L_BBCODES_CUSTOM_BBCODES_EXPLAIN}</p>
<br />

<form method="post" action="{S_BBCODES_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_BBCODE_TAG}</th>
	<th width="120" style="width: 120px;">{L_ACTIONS}</th>
</tr>
<!-- IF S_NO_BBCODES -->
<tr><td class="row1 row-center" colspan="2">{L_BBCODES_NO_BBCODES}</td></tr>
<!-- ELSE -->
<!-- BEGIN bbcode -->
<tr>
	<td class="{bbcode.ROW_CLASS}"><b>{bbcode.BBCODE_TAG}</b></td>
	<td class="{bbcode.ROW_CLASS} row-center"><a href="{bbcode.U_EDIT}"><img src="../images/cms/b_edit.png" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;<a href="{bbcode.U_DELETE}"><img src="../images/cms/b_delete.png" alt="{L_DELETE}" title="{L_DELETE}" /></a></td>
</tr>
<!-- END bbcode -->
<!-- ENDIF -->
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_BBCODES_DB_ADD}" class="mainoption" /></td></tr>
</table>
</form>
<br />