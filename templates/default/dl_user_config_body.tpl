<script language="Javascript" type="text/javascript">
<!--
function select_switch(status)
{
	for (i = 0; i < document.user_dl_favorites.length; i++)
	{
		document.user_dl_favorites.elements[i].checked = status;
	}
}
//-->
</script>

<form action="{S_CONFIG_ACTION}" method="post" name="user_dl_settings">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_CONFIGURATION_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN no_dl_popup_notify -->
<tr>
	<td class="row2" width="10%" rowspan="3"><span class="gen"><b>{L_DOWNLOAD_POPUP}</b></span></td>
	<td class="row1" width="50%"><span class="gen">{L_ALLOW_NEW_DOWNLOAD_POPUP}</span></td>
	<td class="row2" width="40%"><span class="gen"><input type="radio" name="user_allow_new_download_popup" value="1" {ALLOW_NEW_DOWNLOAD_POPUP_YES}/>&nbsp;{L_YES}
		&nbsp;&nbsp;&nbsp;<input type="radio" name="user_allow_new_download_popup" value="0" {ALLOW_NEW_DOWNLOAD_POPUP_NO}/>&nbsp;{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row1" width="50%"><span class="gen">{L_ALLOW_FAV_DOWNLOAD_POPUP}</span></td>
	<td class="row2" width="40%"><span class="gen"><input type="radio" name="user_allow_fav_download_popup" value="1" {ALLOW_FAV_DOWNLOAD_POPUP_YES}/>&nbsp;{L_YES}
		&nbsp;&nbsp;&nbsp;<input type="radio" name="user_allow_fav_download_popup" value="0" {ALLOW_FAV_DOWNLOAD_POPUP_NO}/>&nbsp;{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row1" width="50%"><span class="gen">{L_DOWNLOAD_NOTIFY_TYPE}</span></td>
	<td class="row2" width="40%"><span class="gen"><input type="radio" name="user_dl_note_type" value="1" {USER_DL_NOTE_TYPE_POPUP}/>&nbsp;{L_DOWNLOAD_NOTIFY_TYPE_POPUP}
		&nbsp;&nbsp;&nbsp;<input type="radio" name="user_dl_note_type" value="0" {USER_DL_NOTE_TYPE_MESSAGE}/>&nbsp;{L_DOWNLOAD_NOTIFY_TYPE_MESSAGE}</span>
	</td>
</tr>
<!-- END no_dl_popup_notify -->
<!-- BEGIN no_dl_email_notify -->
<tr>
	<td class="row2" width="10%" rowspan="2"><span class="gen"><b>{L_DOWNLOAD_EMAIL}</b></span></td>
	<td class="row1" width="50%"><span class="gen">{L_ALLOW_NEW_DOWNLOAD_EMAIL}</span></td>
	<td class="row2" width="40%"><span class="gen"><input type="radio" name="user_allow_new_download_email" value="1" {ALLOW_NEW_DOWNLOAD_EMAIL_YES}/>&nbsp;{L_YES}
		&nbsp;&nbsp;&nbsp;<input type="radio" name="user_allow_new_download_email" value="0" {ALLOW_NEW_DOWNLOAD_EMAIL_NO}/>&nbsp;{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row1" width="50%"><span class="gen"><b>{L_ALLOW_FAV_DOWNLOAD_EMAIL}</b></span></td>
	<td class="row2" width="40%"><span class="gen"><input type="radio" name="user_allow_fav_download_email" value="1" {ALLOW_FAV_DOWNLOAD_EMAIL_YES}/>&nbsp;{L_YES}
		&nbsp;&nbsp;&nbsp;<input type="radio" name="user_allow_fav_download_email" value="0" {ALLOW_FAV_DOWNLOAD_EMAIL_NO}/>&nbsp;{L_NO}</span>
	</td>
</tr>
<!-- END no_dl_email_notify -->
<!-- BEGIN sort_config_options -->
<tr>
	<td class="row1" width="60%" colspan="2" valign="top"><span class="gen"><b>{sort_config_options.L_DL_SORT_USER_OPT}</b></span></td>
	<td class="row2" width="40%"><span class="gen">{sort_config_options.S_DL_SORT_USER_OPT}&nbsp;{sort_config_options.S_DL_SORT_USER_DIR}
		<br /><input type="checkbox" class="post" name="user_dl_sort_opt" value="1" {sort_config_options.S_DL_SORT_USER_EXT} />&nbsp;{sort_config_options.L_DL_SORT_USER_EXT}</span></td>
</tr>
<!-- END sort_config_options -->
<!-- BEGIN cash_title -->
<tr><th colspan="3">{cash_title.L_EXCHANGE_TITLE}</th></tr>
<!-- END cash_title -->
<!-- BEGIN exchange_row -->
<tr>
	<td class="row1" width="60%" colspan="2"><span class="gen"><b>{exchange_row.CASH_NAME}</b></span><br /><span class="gensmall">{exchange_row.CASH_EXCHANGE}</span></td>
	<td class="row2" width="50%"><span class="gen"><input type="text" class="post" size="10" maxlength="10" name="cash_to_traffic_{exchange_row.CASH_ID}" value="" />&nbsp;{exchange_row.CASH_NAME}</span><br /><span class="gensmall">({exchange_row.CASH_AMOUNT})</span></td>
</tr>
<!-- END exchange_row -->
<tr>
	<td class="cat" colspan="3" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
		&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- BEGIN fav_block -->
<br />

<form action="{fav_block.S_FORM_ACTION}" method="post" name="user_dl_favorites">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_CONFIGURATION_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="90%">{fav_block.L_DOWNLOAD}</th>
	<th width="10%">{fav_block.L_DELETE}</th>
</tr>
<!-- BEGIN favorite_row -->
<tr>
	<td width="90%" class="{fav_block.favorite_row.ROW_CLASS}">{fav_block.favorite_row.DL_CAT}<a href="{fav_block.favorite_row.U_DOWNLOAD}" class="nav">{fav_block.favorite_row.DOWNLOAD}</a></td>
	<td width="10%" align="center" class="{fav_block.favorite_row.ROW_CLASS}"><input type="checkbox" name="fav_id[]" value="{fav_block.favorite_row.DL_ID}" /></td>
</tr>
<!-- END favorite_row -->
<tr><td colspan="2" class="cat" align="right"><input type="submit" name="submit" value="{fav_block.L_DELETE}" class="mainoption" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table border="0" width="100%">
<tr>
	<td align="right" nowrap="nowrap">
		<a href="javascript:select_switch(true);" class="gensmall">{fav_block.L_MARK_ALL}</a>&nbsp;::&nbsp;<a href="javascript:select_switch(false);" class="gensmall">{fav_block.L_UNMARK_ALL}</a>
	</td>
</tr>
</table>
<input type="hidden" name="action" value="drop" />
</form>
<br />
<!-- END fav_block -->