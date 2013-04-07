<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_block.png" alt="{L_CMS_MENU_TITLE}" title="{L_CMS_MENU_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_MENU_TITLE}</h1><span class="genmed">{L_CMS_MENU_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_MENU_ACTION}" name="post">
<table class="forumline cells-no-rounded" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_EDIT_MENU_ITEM}</th></tr>
<tr>
	<td class="row1">{L_MENU_NAME}</td>
	<td class="row2"><input type="text" maxlength="60" size="30" name="menu_name" value="{MI_MENU_NAME}" class="post" /></td>
</tr>
<tr>
	<td class="row1">{L_MENU_NAME_KEY}</td>
	<td class="row2"><select name="menu_name_lang" class="post">{MI_MENU_NAME_LANG}</select></td>
</tr>
<tr>
	<td class="row1">{L_MENU_DESC}</td>
	<td class="row2"><textarea name="menu_desc" rows="6" cols="35" style="width: 98%;" class="post">{MI_MENU_DESC}</textarea></td>
</tr>
<!--
<tr>
	<td class="row1">{L_LINK_PERMISSION}</td>
	<td class="row2"><select name="auth_view" class="post">{MI_AUTH_VIEW}</select></td>
</tr>
<tr>
	<td class="row1">{L_LINK_PERMISSION}</td>
	<td class="row2">{MI_AUTH_VIEW_GROUP}</td>
</tr>
-->
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" align="center" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="save" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;<input type="reset" name="reset" class="liteoption" value="{L_RESET}" /></td></tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->