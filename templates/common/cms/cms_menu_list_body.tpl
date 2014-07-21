<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_block.png" alt="{L_CMS_MENU_TITLE}" title="{L_CMS_MENU_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_MENU_TITLE}</h1><span class="genmed">{L_CMS_MENU_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_MENU_ACTION}">
<table class="forumline">
<tr>
	<th style="width: 40px;">{L_CMS_ID}</th>
	<th style="width: 120px;">{L_CMS_ACTIONS}</th>
	<th style="width: 360px;">{L_CMS_NAME}</th>
	<th>{L_CMS_DESCRIPTION}</th>
</tr>
<!-- BEGIN menu_row -->
<tr class="row1 row1h">
	<td class="row1 row-center"><b>{menu_row.MENU_ID}</b></td>
	<td class="row1 row-center tdnw">
		<a href="{menu_row.U_ITEMS_EDIT}"><img src="{IMG_CMS_ICON_BLOCKS}" alt="{L_CMS_EDIT_MENU_ITEMS}" title="{L_CMS_EDIT_MENU_ITEMS}" /></a>&nbsp;
		<a href="{menu_row.U_EDIT}"><img src="{IMG_CMS_ICON_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;
		<a href="{menu_row.U_DELETE}"><img src="{IMG_CMS_ICON_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
	</td>
	<td class="row1" style="padding-left: 5px; background: none;"><a href="{menu_row.U_ITEMS_EDIT}">{menu_row.MENU_NAME}</a>&nbsp;</td>
	<td class="row1" style="padding-left: 5px; background: none;">{menu_row.MENU_DESCRIPTION}&nbsp;</td>
</tr>
<!-- END menu_row -->
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" colspan="4">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_MENU_ADD}" class="mainoption" /></td></tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->