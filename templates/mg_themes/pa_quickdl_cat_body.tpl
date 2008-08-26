<table width="100%" cellpadding="3" cellspacing="0">
<tr><td valign="bottom"><span class="nav"><a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a></span></td></tr>
</table>

<!-- IF CAT_PARENT -->
<table class="forumline" width="100%" cellspacing="0">
<!-- BEGIN no_cat_parent -->
<!-- IF no_cat_parent.IS_HIGHER_CAT -->
<tr>
	<td class="cat" valign="middle"><a href="{no_cat_parent.U_CAT}" class="cattitle">{no_cat_parent.CAT_NAME}</a></td>
	<td class="rowpic" align="right">&nbsp;</td>
</tr>
<!-- ELSE -->
<tr>
	<td class="row1 row-center" valign="middle"><a href="{no_cat_parent.U_CAT}" class="cattitle"><img src="{no_cat_parent.CAT_IMAGE}" alt="{no_cat_parent.CAT_NEW_FILE}"></a></td>
	<td class="row1" valign="middle"><a href="{no_cat_parent.U_CAT}" class="cattitle">{no_cat_parent.CAT_NAME}</a><br /><span class="gensmall">{no_cat_parent.CAT_DESC}</span></td>
</tr>
<!-- ENDIF -->
<!-- END no_cat_parent -->
</table>

<!-- ENDIF -->

<!-- IF FILELIST -->
<table class="forumline" width="100%" cellspacing="0">
<!-- BEGIN file_rows -->
<tr>
	<td class="row1 row-center" valign="middle"><a href="{file_rows.U_FILE}" class="topictitle"><img src="{file_rows.PIN_IMAGE}" alt="" /></a></td>
	<td class="row1" valign="middle"><a href="{file_rows.U_FILE}" class="topictitle">{file_rows.FILE_NAME}</a><br /><span class="gensmall">{file_rows.FILE_DESC} ({file_rows.DATE})</span></td>
</tr>
<!-- END file_rows -->
</table>
<!-- ENDIF -->

<!-- IF NO_FILE -->
<table class="forumline" width="100%" cellspacing="0">
<tr><th>{L_NO_FILES}</th></tr>
<tr><td class="row1 row-center" height="30"><span class="genmed">{L_NO_FILES_CAT}</span></td></tr>
</table>
<!-- ENDIF -->