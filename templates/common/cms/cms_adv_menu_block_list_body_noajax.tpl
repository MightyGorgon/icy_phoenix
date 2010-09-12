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
<tr>
	<td class="row1" style="padding:0px" valign="top">
		<table class="nav-div" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th width="100" align="center" colspan="2">{L_CMS_ACTIONS}</th>
			<th width="320" align="center">{L_CMS_NAME}</th>
			<th align="center">{L_CMS_DESCRIPTION}</th>
		</tr>
		<!-- BEGIN no_items -->
		<tr>
			<td class="row1 row-center" colspan="4">
			<br /><br />
			<span class="gen"><span class="text_red"><b>{no_items.NO_ITEMS}</b></span></span>
			<br /><br /><br />
			</td>
		</tr>
		<!-- END no_items -->
		<!-- BEGIN cat_row -->
		<tr>
			<th align="center">
				{cat_row.U_MOVE_UP}
				{cat_row.U_MOVE_DOWN}
				{cat_row.U_EDIT}
				{cat_row.U_DELETE}
			</th>
			<th align="center"><input type="checkbox" name="cb_mid[]" value="{cat_row.CAT_CB_ID}"{cat_row.CAT_CHECKED} /></th>
			<th>{cat_row.CAT_ICON}<b>{cat_row.CAT_ITEM}</b></th>
			<th align="center"><b>{cat_row.CAT_DESC}</b></th>
			<!-- <td class="row1" style="padding-left:5px;"><b>{cat_row.CAT_DESC}</b></td> -->
		</tr>
		<!-- BEGIN menu_row -->
		<tr class="row1h" style="background-image: none;">
			<td class="row1 row-center" width="80" style="padding:0px;background: none;">
				{cat_row.menu_row.U_MOVE_UP}
				{cat_row.menu_row.U_MOVE_DOWN}
				{cat_row.menu_row.U_EDIT}
				{cat_row.menu_row.U_DELETE}
			</td>
			<td class="row1 row-center" width="20" style="padding:0px;background: none;"><input type="checkbox" name="cb_mid[]" value="{cat_row.menu_row.MENU_CB_ID}"{cat_row.menu_row.MENU_CHECKED} /></td>
			<td class="row1" style="padding-left:10px;background: none;">{cat_row.menu_row.MENU_URL}</td>
			<td class="row1" style="padding-left:5px;background: none;">{cat_row.menu_row.MENU_DESC}</td>
		</tr>
		<!-- END menu_row -->
		<!-- END cat_row -->
		</table>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="add_cat" value="{L_CAT_ADD}" class="mainoption" />
		&nbsp;&nbsp;<input type="submit" name="add" value="{L_MENU_ADD}" class="mainoption" />
		&nbsp;&nbsp;<input type="submit" name="action_update" value="{L_MENU_UPDATE}" class="liteoption" />
	</td>
</tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->