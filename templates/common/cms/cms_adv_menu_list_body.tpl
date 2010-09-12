<!-- INCLUDE ../common/cms/page_header.tpl -->

<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_block.png" alt="{L_CMS_MENU_TITLE}" title="{L_CMS_MENU_TITLE}" /></td>
	<td class="row1" valign="top"><h1>{L_CMS_MENU_TITLE}</h1><span class="genmed">{L_CMS_MENU_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_MENU_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">&nbsp;{L_QUICK_LINKS}&nbsp;</th></tr>
<tr>
	<td class="row1" style="padding:0px" valign="top">
		<table class="nav-div" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th width="20" align="center">{L_CMS_ID}</th>
			<th width="70" align="center">{L_CMS_ACTIONS}</th>
			<th width="320" align="center">{L_CMS_NAME}</th>
			<th align="center">{L_CMS_DESCRIPTION}</th>
		</tr>
		<!-- BEGIN menu_row -->
		<tr class="row1h" style="background-image: none;">
			<td class="row1 row-center" style="background: none;"><b>{menu_row.MENU_ID}</b></td>
			<td class="row1 row-center" style="background: none;" nowrap="nowrap">
				<a href="{menu_row.U_ITEMS_EDIT}"><img src="{IMG_LAYOUT_BLOCKS_EDIT}" alt="{L_CMS_EDIT_MENU_ITEMS}" title="{L_CMS_EDIT_MENU_ITEMS}" /></a>&nbsp;
				<a href="{menu_row.U_EDIT}"><img src="{IMG_BLOCK_EDIT}" alt="{L_EDIT}" title="{L_EDIT}" /></a>&nbsp;
				<a href="{menu_row.U_DELETE}"><img src="{IMG_BLOCK_DELETE}" alt="{L_DELETE}" title="{L_DELETE}" /></a>
			</td>
			<td class="row1" style="padding-left:5px;background: none;"><a href="{menu_row.U_ITEMS_EDIT}">{menu_row.MENU_NAME}</a>&nbsp;</td>
			<td class="row1" style="padding-left:5px;background: none;">{menu_row.MENU_DESCRIPTION}&nbsp;</td>
		</tr>
		<!-- END menu_row -->
		</table>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="add" value="{L_MENU_ADD}" class="mainoption" />
	</td>
</tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->