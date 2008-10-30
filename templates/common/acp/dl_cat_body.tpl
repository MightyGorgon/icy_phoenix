<form action="{S_CATEGORY_ACTION}" method="post" name="dl_cat">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="5">{L_DL_CAT_TITLE}</th></tr>
<tr><td class="row3 row-center" colspan="5"><span class="gensmall">{L_DL_CAT_EDIT_TEXT}</span></td></tr>
<tr>
	<th nowrap="nowrap">{L_DL_NAME}</th>
	<th nowrap="nowrap">{L_EDIT}</th>
	<th nowrap="nowrap">{L_DELETE}</th>
	<th nowrap="nowrap" colspan="2">{L_ORDER}</th>
</tr>
<!-- BEGIN categories -->
<tr>
	<td class="{categories.ROW_CLASS}"><span class="gen"><strong>{categories.CAT_NAME}</strong></span></td>
	<td class="{categories.ROW_CLASS} row-center"><a href="{categories.U_CAT_EDIT}" class="genmed">{L_EDIT}</a></td>
	<td class="{categories.ROW_CLASS} row-center">
	<a href="{categories.U_CAT_DELETE}" class="genmed">{L_DELETE_CAT}</a>
	<a href="{categories.U_DELETE_STATS}" class="genmed">{categories.L_DELETE_STATS}</a>
	<a href="{categories.U_DELETE_COMMENTS}" class="genmed">{categories.L_DELETE_COMMENTS}</a></td>
	<td class="{categories.ROW_CLASS} row-center">
		<a href="{categories.U_CATEGORY_MOVE_UP}" class="genmed">{L_UP}</a> | <a href="{categories.U_CATEGORY_MOVE_DOWN}" class="genmed">{L_DOWN}</a><br />
		<a href="{categories.U_CATEGORY_ASC_SORT}" class="genmed">{categories.L_SORT_ASC}</a>
	</td>
</tr>
<!-- END categories -->
<tr><td class="cat" colspan="5"><input type="hidden" name="action" value="add" /><input type="submit" class="mainoption" name="submit" value="{L_DL_ADD_CAT}" /></td></tr>
</table>

<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
	<td align="center"><a href="{U_DELETE_STATS_ALL}" class="nav">{L_DELETE_STATS_ALL}</a></td>
	<td align="center"><a href="{U_SORT_LEVEL_ZERO}" class="nav">{L_SORT_ASC_LEVEL_ZERO}</a></td>
	<td align="center"><a href="{U_DELETE_COMMENTS_ALL}" class="nav">{L_DELETE_COMMENTS_ALL}</a></td>
</tr>
</table>
</form>
