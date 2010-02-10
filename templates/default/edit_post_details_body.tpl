<!-- INCLUDE simple_header.tpl -->

<!-- IF S_AJAX_FEATURES -->
<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/ajax/ajax_searchfunctions.js"></script>
<!-- ENDIF -->

<!-- BEGIN entry_page -->
<form action="{S_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_EDIT_POST_DETAILS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center"><span class="genmed"><b>{L_CURRENT_POSTER}</b>:&nbsp;{POSTER_NAME}</span></td></tr>
<tr>
	<td class="row1 row-center">
	<span class="genmed">
		<b>{L_NEW_POSTER}</b>:&nbsp;<input type="text" class="post" name="username" id="username" maxlength="50" size="20" {S_AJAX_USER_CHECK} />&nbsp;
		<span id="username_list" style="display:none;">&nbsp;<span id="username_select">&nbsp;</span></span>
		<input type="submit" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onclick="window.open('{U_SEARCH_USER}', '_search', 'width=400,height=250,resizable=yes'); return false;" />
	</span>
	</td>
</tr>
<tr><td class="row1 row-center"><span class="genmed">{POST_EDIT_STRING}</span></td></tr>
<tr>
<td class="catBottom" align="center">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;&nbsp;
	<input type="submit" name="reset" value="{L_RESET}" class="liteoption" />
</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END entry_page -->

<!-- BEGIN submit_finished -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_INFORMATION}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center" width="100%" align="center"><span class="genmed">{POST_EDIT_STRING}</span></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END submit_finished -->

<!-- IF CLOSE -->
<script type="text/javascript">
<!--
function close_window()
{
		opener.document.location.href = "{U_VIEWTOPIC}";
		opener.document.location.reload();
		window.close();
}
setTimeout('close_window();', 3000);
//-->
</script>
<!-- ENDIF -->

<!-- INCLUDE simple_footer.tpl -->