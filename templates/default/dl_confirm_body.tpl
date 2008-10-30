<form action="{S_CONFIRM_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{MESSAGE_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center">
		<span class="gen"><br />{MESSAGE_TEXT}
		<br /><br /><input type="submit" name="confirm" value="{L_YES}" class="mainoption" />&nbsp;&nbsp;
		<input type="submit" name="cancel" value="{L_NO}" class="liteoption" />{S_HIDDEN_FIELDS}
<!-- BEGIN delete_files_confirm -->
		<br /><br />{L_DELETE_FILE_TOO}&nbsp;<input type="checkbox" name="del_file" value="1">
<!-- END delete_files_confirm -->
<!-- BEGIN choose_new_cat -->
		<br /><br /><b>{L_SWITCH_CAT}:</b><br /><br />{S_SWITCH_CAT}
<!-- END choose_new_cat -->
		</span>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>