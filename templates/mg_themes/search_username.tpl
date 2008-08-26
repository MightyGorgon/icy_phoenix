<!-- BEGIN switch_ajax_features -->
<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/ajax/ajax_searchfunctions.js"></script>
<!-- END switch_ajax_features -->

<script type="text/javascript">
<!--
function refresh_username(selected_username)
{
	if (selected_username == '-1')
	{
		return;
	}
	opener.document.forms['post'].username.value = selected_username;
	opener.focus();
	window.close();
}
//-->
</script>

<form method="post" name="search" action="{S_SEARCH_ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_USERNAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td class="row1 row-center">
			<br />
			<span class="genmed">
				<input class="post" type="text" name="search_username" value="{USERNAME}" {S_AJAX_USER_CHECK_ALT} />&nbsp;
				<input class="liteoption" type="submit" name="search" value="{L_SEARCH}" />
			</span>
			<br /><span class="gensmall">{L_SEARCH_EXPLAIN}</span><br />
			<span class="genmed" id="username_list" {USERNAME_LIST_VIS}>
				{L_UPDATE_USERNAME}<br />
				<span id="username_select"><select name="username_list">{S_USERNAME_OPTIONS}</select></span>&nbsp;
				<input type="submit" class="liteoption" onclick="refresh_username(this.form.username_list.options[this.form.username_list.selectedIndex].value);return false;" name="use" value="{L_SELECT}" />
			</span><br />
			<br /><span class="genmed"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></span>
		</td>
	</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>