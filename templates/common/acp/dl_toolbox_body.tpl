<script language="Javascript" type="text/javascript">
<!--
function select_switch(status)
{
	doc_length = document.create_dir.length;
	for (i = 0; i < doc_length; i++)
	{
		document.create_dir.elements[i].checked = status;
	}
}
//-->
</script>

<form action="{S_MANAGE_ACTION}" method="post" name="create_dir">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_DL_MANAGE}</th></tr>
<tr>
	<td class="row1" width="70%"><span class="gensmall">{L_DL_MANAGE_EXPLAIN}</span></td>
	<td class="row1 row-center" width="30%">
		<input type="button" onclick="javascript:window.open('{U_DOWNLOADS_CHECK_FILES}', '_self');" class="mainoption" value="{L_DOWNLOADS_CHECK_FILES}" />
		<!-- BEGIN create_dir_command -->
		<br /><br />
		<input type="text" class="post" name="dir_name" size="30" maxlength="200" />
		<br />
		<input type="submit" name="dircreate" class="liteoption" value="{L_DL_MANAGE_CREATE_DIR}" />
		<!-- END create_dir_command -->
		<!-- BEGIN unassigned_files -->
		<br /><br />
		<input type="button" onclick="javascript:window.open('{unassigned_files.U_UNASSIGNED_FILES}', '_self');" class="mainoption" value="{unassigned_files.L_UNASSIGNED_FILES}" />
		<!-- END unassigned_files -->
		<!-- BEGIN thumbnail_check -->
		<br /><br />
		<input type="button" onclick="javascript:window.open('{U_DOWNLOADS_CHECK_THUMB}', '_self');" class="mainoption" value="{L_DOWNLOADS_CHECK_THUMBS}" />
		<!-- END thumbnail_check -->
	</td>
</tr>
</table>

<br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row3" colspan="4"><span class="nav">{DL_NAVI}</span></td></tr>
<!-- BEGIN dirs_row -->
<tr>
	<td class="row2" width="75%" colspan="2"><span class="genmed">{dirs_row.DIR_LINK}</span></td>
	<td class="row2" width="25%" colspan="2" align="right"><span class="genmed">{dirs_row.DIR_DELETE_LINK}</span></td>
</tr>
<!-- END dirs_row -->
<!-- BEGIN files_row -->
<tr>
	<td class="row1" width="5%" nowrap="nowrap">{files_row.FILE_EXIST}</td>
	<td class="row1" width="70%" nowrap="nowrap"><span class="genmed">&nbsp;{files_row.FILE_NAME}&nbsp;</span></td>
	<td class="row1" width="20%" nowrap align="right"><span class="genmed">&nbsp;{files_row.FILE_SIZE}&nbsp;</span></td>
	<td class="row1" width="5%"><span class="genmed">&nbsp;{files_row.FILE_SIZE_RANGE}&nbsp;</span></td>
</tr>
<!-- END files_row -->
<!-- BEGIN empty_folder -->
<tr><td class="row1 row-center" colspan="4"><span class="genmed">{empty_folder.L_NO_CONTENT}</span></td></tr>
<!-- END empty_folder -->
<!-- BEGIN overall_size -->
<tr>
	<td class="cat" width="75%" colspan="2"><span class="genmed">&nbsp;{overall_size.S_FILE_ACTION}&nbsp;<input type="submit" name="file_action" value="{L_GO}" /></span></td>
	<td class="cat" width="20%" align="right"><span class="genmed"><b>{overall_size.OVERALL_SIZE}</b>&nbsp;</span></td>
	<td class="cat" width="5%"><span class="genmed">&nbsp;<b>{overall_size.OVERALL_SIZE_RANGE}</b></span></td>
</tr>
<!-- END overall_size -->
<!-- BEGIN default_footer -->
<tr><td class="cat" colspan="4">&nbsp;</td></tr>
<!-- END default_footer -->
</table>

<!-- BEGIN overall_size -->
<table border="0" cellpadding="3" cellspacing="1" align="center" width="50%">
<tr>
	<td nowrap="nowrap"><span class="gensmall">
		<a href="javascript:select_switch(true);" class="gensmall">{L_MARK_ALL}</a> ::
		<a href="javascript:select_switch(false);" class="gensmall">{L_UNMARK_ALL}</a>
		</span>
	</td>
</tr>
</table>
<!-- END overall_size -->
</form>
