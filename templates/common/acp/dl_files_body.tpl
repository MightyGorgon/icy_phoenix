<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="3">{L_DL_FILES_TITLE}</th></tr>
<tr><td class="row3 row-center" colspan="3"><span class="gensmall">{L_DL_FILES_TEXT}</span></td></tr>
</table>
<br />

<form method="post" action="{S_DOWNLOADS_ACTION}" name="add_dl">
<table cellspacing="0" cellpadding="0" border="0" align="center">
<tr>
	<td align="center">
		<input type="submit" class="mainoption" name="submit" value="{L_ADD_DOWNLOAD}" />
		<input type="hidden" name="action" value="add" />
	</td>
</tr>
</table>
</form>

<form action="{S_DOWNLOADS_ACTION}" method="post" name="cat_id" onsubmit="if(this.options[this.selectedIndex].value == -1){ return false; }">
<table width="100%" cellpadding="3" cellspacing="1" border="0">
<tr>
	<td width="30%"><span class="nav">{CATEGORIES}</span></td>
	<td width="40%" align="center"><span class="nav">{DL_COUNT}</span></td>
	<td width="30%" align="right"><span class="nav"><a href="{U_DOWNLOAD_ORDER_ALL}">{L_SORT}</a></span></td>
</tr>
</table>
</form>

<br />

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap">#</th>
	<th nowrap="nowrap" colspan="3">{L_DL_NAME} / {L_DL_FILE_NAME}</th>
	<th nowrap="nowrap">{L_DL_FILE_SIZE}</th>
	<th nowrap="nowrap">{L_DL_IS_FREE}</th>
	<th nowrap="nowrap">{L_DL_EXTERN}</th>
	<th nowrap="nowrap">{L_DL_FILE_KLICKS}<br />{L_DL_FILE_OVERALL_KLICKS}</th>
	<th nowrap="nowrap">{L_DL_FILE_TRAFFIC}</th>
	<th>{L_DL_HACKLIST}</th>
	<th colspan="2">{L_ACTION}</th>
</tr>
<!-- BEGIN downloads -->
<tr>
	<td class="{downloads.ROW_CLASS}" align="center"><span class="nav">{downloads.FILE_ID}</span></td>
	<td class="{downloads.ROW_CLASS}" align="left"><span class="gen"><b>{downloads.DESCRIPTION}</b></span><br /><span class="gensmall">{downloads.FILE_NAME}</span></td>
	<td class="{downloads.ROW_CLASS}" align="left"><span class="gen">{downloads.VERSION}</span></td>
	<td class="{downloads.ROW_CLASS}" align="left"><span class="gensmall">{downloads.TEST}{downloads.UNAPPROVED}</span></td>
	<td class="{downloads.ROW_CLASS}" align="center"><span class="genmed">{downloads.FILE_SIZE}</span></td>
	<td class="{downloads.ROW_CLASS}" align="center"><span class="genmed">{downloads.FILE_FREE}</span></td>
	<td class="{downloads.ROW_CLASS}" align="center"><span class="genmed">{downloads.FILE_EXTERN}</span></td>
	<td class="{downloads.ROW_CLASS}" align="center"><span class="genmed">{downloads.FILE_KLICKS} / {downloads.FILE_OVERALL_KLICKS}</span></td>
	<td class="{downloads.ROW_CLASS}" align="center"><span class="genmed">{downloads.FILE_TRAFFIC}</span></td>
	<td class="{downloads.ROW_CLASS}" align="center"><span class="genmed">{downloads.HACKLIST}</span></td>
	<td class="{downloads.ROW_CLASS}" align="center"><a href="{downloads.U_FILE_EDIT}" class="gensmall">{L_EDIT}</a><br /><a href="{downloads.U_FILE_DELETE}" class="gensmall">{L_DELETE}</a></td>
	<td class="{downloads.ROW_CLASS}" align="center">
		<span class="gensmall">
		<a href="{downloads.U_DOWNLOAD_MOVE_UP}" class="gensmall">{L_DL_UP}</a><br />
		<a href="{downloads.U_DOWNLOAD_MOVE_DOWN}" class="gensmall">{L_DL_DOWN}</a>
		</span>
	</td>
</tr>
<!-- END downloads -->
</table>
<br />