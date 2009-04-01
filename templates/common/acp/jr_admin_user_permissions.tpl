<script type="text/javascript">
function toggle_check_all(main, sub_num)
{
	for (var i = 0; i < document.module_form.elements.length; i++)
	{
		var checkbox_element = document.module_form.elements[i];
		if ((main.name.search("check_all_page") != -1) && (checkbox_element.type == 'checkbox'))
		{
			checkbox_element.checked = main.checked;
		}
		else if ((checkbox_element.name.search("check_all") == -1) && (checkbox_element.name.search(sub_num+"_update_module_") != -1) && (checkbox_element.type == 'checkbox'))
		{
			checkbox_element.checked = main.checked;
		}
	}
}
</script>

<!-- BEGIN statusrow -->
<table width="100%" align="center" cellspacing="2" cellpadding="2" border="1">
<tr>
	<td align="center"><span class="gen">{L_STATUS}<br /></span>
	<span class="genmed"><b>{I_STATUS_MESSAGE}</b></span><br /></td>
</tr>
</table>
<!-- END statusrow -->

<table width="100%" align="center" cellspacing="1" cellpadding="2" border="0">
<tr>
	<td align="left">
		<span class="maintitle">{L_PAGE_NAME}</span>
		<br /><span class="gensmall"><b>{L_VERSION} {VERSION}
		<br />{NIVISEC_CHECKER_VERSION}</b></span><br /><br />
		<span class="genmed">{L_PAGE_DESC}<br /><br />{VERSION_CHECK_DATA}</span>
	</td>
</tr>
</table>

<br />
<div class="forumline" style="padding:5px;text-align:center;margin: 0 auto;">
<form name="user_permissions" action="{S_USER_PERM}" method="post">
	<input type="hidden" name="username" value="{USERNAME}" />
	<input type="hidden" name="mode" value="user" />
	<input type="submit" name="submit" value="{L_EDIT_PERMISSIONS}" class="liteoption" />
</form>
<form name="user_profile" action="{S_PROFILE}" method="get">
	<input type="hidden" name="{S_USER_POST_URL}" value="{USER_ID}" />
	<input type="hidden" name="mode" value="viewprofile" />
	<input type="submit" name="submit" value="{L_VIEW_PROFILE}" class="liteoption" />
</form>
<form name="user_management" action="{S_MANAGEMENT}" method="post">
	<input type="hidden" name="username" value="{USERNAME}" />
	<input type="hidden" name="mode" value="edit" />
	<input type="submit" name="submituser" value="{L_EDIT_USER_DETAILS}" class="liteoption" />
</form>
</div>

<br />
<br />
<form action="{S_ACTION}" name="module_form" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="50%" valign="middle">{L_USER_STATS}</th>
	<th width="50%" valign="middle">{L_USER_INFO}</th>
</tr>
<tr>
	<td class="row1 row-center">
		<br /><span class="gen"><b>{USERNAME_FULL}</b></span><br />
		{GROUP_NAME}<br /><span class="gensmall">{ADMIN_TEXT}</span><br /><br />
		<span class="genmed"><b>{L_START_DATE}</b><br />{START_DATE}</span><br /><br />
		<span class="genmed"><b>{L_UPDATE_DATE}</b><br />{UPDATE_DATE}</span><br /><br />
	</td>
	<td class="row2 row-center">
		<span class="gensmall">
			<b>{L_NOTES}</b> &nbsp;&nbsp;&nbsp; {L_ALLOW_VIEW}
			<input type="checkbox" name="notes_view" value="1" {NOTES_VIEW_CHECKED} />
		</span>
		<br />
		<textarea name="admin_notes" class="post" cols="45" rows="5">{NOTES}</textarea>
	</td>
</tr>
</table>

<br />
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th valign="middle" colspan="3">{L_MODULE_INFO}</th></tr>
<tr>
	<td class="row1 row-center" colspan="3">
		<input type="submit" name="update_user" value="{L_UPDATE}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" name="reset" class="liteoption" />
	</td>
</tr>
<tr>
	<td class="cat" align="right">&nbsp;</td>
	<td class="cat" align="center" width="10%"><input type="checkbox" name="check_all_page" onclick="toggle_check_all(check_all_page, 1);" /></td>
	<td class="cat" align="left"><span class="gensmall"><b>&nbsp;&nbsp;<-- {L_CHECK_ALL}&nbsp;&nbsp;</b></span></td>
</tr>
<!-- BEGIN catrow -->
<tr>
	<th><span class="cattitle">{catrow.CAT}</span></th>
	<th width="10%" valign="middle"><input type="checkbox" name="check_all_{catrow.NUM}" onclick="toggle_check_all(check_all_{catrow.NUM}, {catrow.NUM});" /></th>
	<th align="left" valign="middle"><span class="gensmall">&nbsp;&nbsp;<-- {L_CHECK_ALL_IN_CAT}&nbsp;&nbsp;</span></th>
</tr>
<!-- BEGIN modulerow -->
<tr>
	<td class="{catrow.modulerow.ROW}">
		<span class="gen"><b>{catrow.modulerow.NAME}</b></span><br />
		<span class="gensmall">{catrow.modulerow.FILENAME}<br />
		({catrow.modulerow.FILE_HASH})</span>
	</td>
	<td class="{catrow.modulerow.ROW}" width="10%" align="center">
		<span class="gen"><input type="checkbox" {catrow.modulerow.CHECKED} name="{catrow.NUM}_update_module_{catrow.modulerow.FILE_HASH}" /></span>
	</td>
	<td class="{catrow.modulerow.ROW}">&nbsp;</td>
</tr>
<!-- END modulerow -->
<!-- END catrow -->
<tr>
	<td class="cat" align="right">&nbsp;</td>
	<td class="cat" align="center" width="10%">
		<input type="checkbox" name="check_all_page1" onclick="toggle_check_all(check_all_page, 1);" />
	</td>
	<td class="cat" align="left"><span class="gensmall"><b>&nbsp;&nbsp;<-- {L_CHECK_ALL}&nbsp;&nbsp;</b></span></td>
	</tr>
	<tr>
		<td class="cat" colspan="3" align="center" height="28">
			<input type="hidden" name="user_id" value="{USER_ID}" />
			<input type="submit" name="update_user" value="{L_UPDATE}" class="mainoption" />&nbsp;&nbsp;
			<input type="reset" value="{L_RESET}" name="reset" class="liteoption" />
		</td>
	</tr>
</table>
</form>