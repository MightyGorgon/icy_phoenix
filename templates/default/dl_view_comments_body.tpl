<form action="{S_FORM_ACTION}" method="post" name="comments_view">
{IMG_THL}{IMG_THC}<span class="forumlink">{DESCRIPTION}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_POSTER}</th>
	<th>{L_MESSAGE}</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN comment_row -->
<tr>
	<td class="{comment_row.ROW_CLASS} row-center" width="20%" valign="top">
		<span class="genmed">{comment_row.POSTER}</span>
		<br /><span class="gensmall">{comment_row.POST_TIME}</span>
	</td>
	<td class="{comment_row.ROW_CLASS}" width="70%" valign="top"><span class="postdetails">{comment_row.MESSAGE}{comment_row.EDITED_BY}</span></td>
	<td class="{comment_row.ROW_CLASS} row-center" width="10%">
		<!-- BEGIN action_button -->
		<a href="{comment_row.U_DELETE_COMMENT}" class="genmed">{L_DL_DELETE}</a><br /><a href="{comment_row.U_EDIT_COMMENT}" class="genmed">{L_DL_EDIT}</a>
		<!-- END action_button -->
	</td>
</tr>
<!-- END comment_row -->
<!-- BEGIN comment_button -->
<tr><td colspan="3" class="cat" align="center"><input type="submit" name="post" value="{L_POST_COMMENT}" class="liteoption" />&nbsp;</td></tr>
<!-- END comment_button -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right" valign="top"><span class="pagination">{PAGINATION}</span></td></tr>
</table>
<br />
{S_HIDDEN_FIELDS}
</form>
