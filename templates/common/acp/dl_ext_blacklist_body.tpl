<script language="Javascript" type="text/javascript">
<!--
function select_switch(status)
{
	for (i = 0; i < document.extention.length; i++)
	{
		document.extention.elements[i].checked = status;
	}
}
// -->
</script>

<p><b>{L_DL_EXT_BLACKLIST_EXPLAIN}</b></p>

<form action="{S_DOWNLOADS_ACTION}" method="post" name="add_ext">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_DL_EXT_BLACKLIST}</th></tr>
<tr>
	<td width="50%" class="row3" align="center"><span class="nav">{L_DL_EXTENTION}:</span>&nbsp;<input type="text" class="post" name="extention" size="5" maxlength="10" value="" /></td>
	<td width="50%" class="row3" align="center"><input type="submit" class="mainoption" name="submit" value="{L_DL_ADD_EXTENTION}" /></td>
</tr>
</table>
<input type="hidden" name="action" value="add" />
</form>

<form action="{S_DOWNLOADS_ACTION}" method="post" name="extention">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="50%">{L_DL_EXTENTIONS}</th>
	<th width="50%"><input type="submit" name="submit" class="mainoption" value="{L_DL_DEL_EXTENTIONS}" /></th>
</tr>
<!-- BEGIN extention_row -->
<tr>
	<td width="50%" class="{extention_row.ROW_CLASS}" align="center"><span class="nav">{extention_row.EXTENTION}</span></td>
	<td width="50%" class="{extention_row.ROW_CLASS}" align="center"><input type="checkbox" name="extention[]" value="{extention_row.EXTENTION}" /></td>
</tr>
<!-- END extention_row -->
<tr>
	<td class="cat">&nbsp;</td>
	<td class="cat" align="center"><input type="submit" name="submit" class="mainoption" value="{L_DL_DEL_EXTENTIONS}" /></td>
</tr>
</table>
<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
	<td align="right" valign="top" nowrap="nowrap" colspan="2">
		<a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> ::
		<a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a>
	</td>
</tr>
</table>
<input type="hidden" name="action" value="delete" />
</form>