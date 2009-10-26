<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_header_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_header.tpl -->
<!-- ENDIF -->

<script type="text/javascript">
<!--
function update_icon(newimage)
{
	if(newimage != '')
	{
		document.icon_image.src = newimage;
		document.post.{MI_ICON_INPUT_NAME}.value = newimage;
	}
	else
	{
		//document.icon_image.src = 'images/spacer.gif';
		document.icon_image.src = '{SPACER}';
		document.post.{MI_ICON_INPUT_NAME}.value = '';
	}
}

function option_disabled (form)
{
	form.menu_name.disabled = (form.menu_default.value != 0) ? 'true' : '';
	form.menu_name_lang.disabled = (form.menu_default.value != 0) ? 'true' : '';
	form.menu_link.disabled = (form.menu_default.value != 0) ? 'true' : '';
	document.getElementById('menu_external_yes').disabled = (form.menu_default.value != 0) ? 'true' : '';
	document.getElementById('menu_external_no').disabled = (form.menu_default.value != 0) ? 'true' : '';
	form.auth_view.disabled = (form.menu_default.value != 0) ? 'true' : '';
}
//-->
</script>

<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_block.png" alt="{L_CMS_MENU_TITLE}" title="{L_CMS_MENU_TITLE}" /></td>
	<td class="row1" valign="top"><h1>{L_CMS_MENU_TITLE}</h1><span class="genmed">{L_CMS_MENU_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_MENU_ACTION}" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_MENU_ITEM}</th></tr>
<tr>
	<td class="row1" style="padding:0px" valign="top">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<!-- BEGIN parent_cat_sel -->
		<tr>
			<td class="row1">{L_LINK_CAT}</td>
			<td class="row2"><select name="cat_parent_id" class="post">{parent_cat_sel.PARENT_CAT_SEL}</select></td>
		</tr>
		<!-- END parent_cat_sel -->
		<!-- BEGIN is_menu_item -->
		<tr>
			<td class="row1">{L_LINK_DEFAULT}</td>
			<td class="row2"><select name="menu_default" class="post" onchange="option_disabled(this.form)">{MI_MENU_DEFAULT}</select></td>
		</tr>
		<!-- END is_menu_item -->
		<tr>
			<td class="row1">{L_LINK_NAME}</td>
			<td class="row2"><input type="text" maxlength="60" size="30" name="menu_name" value="{MI_MENU_NAME}" class="post" {MI_MENU_DISABLED} /></td>
		</tr>
		<tr>
			<td class="row1">{L_LINK_NAME_KEY}</td>
			<td class="row2"><select name="menu_name_lang" class="post" {MI_MENU_DISABLED}>{MI_MENU_NAME_LANG}</select></td>
		</tr>
		<tr>
			<td class="row1">{L_LINK_STATUS}</td>
			<td class="row2">
				<input type="radio" name="menu_status" value="1" {MI_MENU_STATUS_YES} /> {L_ENABLED}&nbsp;&nbsp;
				<input type="radio" name="menu_status" value="0" {MI_MENU_STATUS_NO} /> {L_DISABLED}
			</td>
		</tr>
		<!-- BEGIN is_menu_item -->
		<tr>
			<td class="row1">{L_LINK_URL}</td>
			<td class="row2"><input type="text" maxlength="240" size="30" name="menu_link" value="{MI_MENU_LINK}" class="post" {MI_MENU_DISABLED} /></td>
		</tr>
		<tr>
			<td class="row1" height="27">{L_LINK_EXTERNAL}</td>
			<td class="row2">
				<table cellspacing="0" cellpadding="0"><tr>
				<td width="17" align="center"><input type="radio" name="menu_link_external" value="1" {MI_MENU_LINK_EXTERNAL_YES} {MI_MENU_DISABLED} id="menu_external_yes"/></td>
				<td>{L_YES}&nbsp;&nbsp;</td>
				<td width="17" align="center"><input type="radio" name="menu_link_external" value="0" {MI_MENU_LINK_EXTERNAL_NO} {MI_MENU_DISABLED} id="menu_external_no"/></td>
				<td>{L_NO}</td>
				</tr></table>
			</td>
		</tr>
		<!-- END is_menu_item -->
		<tr>
			<td class="row1">{L_LINK_ICON}</td>
			<td class="row2">{MI_MENU_ICON}</td>
		</tr>
		<tr>
			<td class="row1">{L_LINK_DESC}</td>
			<td class="row2"><textarea name="menu_desc" rows="6" cols="35" style="width: 98%;" class="post">{MI_MENU_DESC}</textarea></td>
		</tr>
		<tr>
			<td class="row1">{L_LINK_PERMISSION}</td>
			<td class="row2"><select name="auth_view" class="post" {MI_MENU_DISABLED}>{MI_AUTH_VIEW}</select></td>
		</tr>
		<!--
		<tr>
			<td class="row1">{L_LINK_PERMISSION}</td>
			<td class="row2">{MI_AUTH_VIEW_GROUP}</td>
		</tr>
		-->
		</table>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="save" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;
		<input type="submit" name="reset" class="liteoption" value="{L_RESET}" />
		<!-- <input type="reset" name="reset" class="liteoption" value="{L_RESET}" /> -->
	</td>
</tr>
</table>
</form>

<!-- IF CMS_STD_TPL -->
<!-- INCLUDE ../common/cms/page_footer_std.tpl -->
<!-- ELSE -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->
<!-- ENDIF -->