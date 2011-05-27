<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center c-r-l" width="100" valign="middle"><img src="{IP_ROOT_PATH}templates/common/images/cms/cms_block.png" alt="{L_CMS_MENU_TITLE}" title="{L_CMS_MENU_TITLE}" /></td>
	<td class="row1 c-r-r" valign="top"><h1>{L_CMS_MENU_TITLE}</h1><span class="genmed">{L_CMS_MENU_EXPLAIN}</span></td>
</tr>
</table>

<form method="post" action="{S_MENU_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" style="padding: 0px !important;" valign="top">
		<table class="nav-div" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th align="center" style="width: 140px;">{L_CMS_ACTIONS}</th>
			<th align="center" style="width: 40px;">&nbsp;</th>
			<th align="center" style="width: 290px;">{L_CMS_NAME}</th>
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
		</table>
		<!-- BEGIN cat_row -->
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th class="r2" align="center" style="width: 145px;">
				{cat_row.U_MOVE_UP}
				{cat_row.U_MOVE_DOWN}
				{cat_row.U_EDIT}
				{cat_row.U_DELETE}
			</th>
			<th class="r2" align="center" style="width: 40px;"><input type="checkbox" name="cb_mid[]" value="{cat_row.CAT_CB_ID}"{cat_row.CAT_CHECKED} /></th>
			<th class="r2" style="width: 290px;">{cat_row.CAT_ICON}<b>{cat_row.CAT_ITEM}</b></th>
			<th class="r2" align="center"><b>{cat_row.CAT_DESC}</b></th>
		</tr>
		</table>
		<ul id="list_{cat_row.CAT_CB_ID}" style="margin: 0px; padding: 0px; list-style-type: none;">
		<!-- BEGIN menu_row -->
		<li id="item_{cat_row.menu_row.MENU_CB_ID}">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr class="row1h">
			<td class="row1 row-center" style="padding: 0px; background: none; width: 140px;">
				<a class="icon-edit-move" href="javascript:void(0);"></a>
				{cat_row.menu_row.U_MOVE_UP}
				{cat_row.menu_row.U_MOVE_DOWN}
				{cat_row.menu_row.U_EDIT}
				{cat_row.menu_row.U_DELETE}
			</td>
			<td class="row1 row-center" style="padding: 0px; background: none; width: 50px;"><input type="checkbox" name="cb_mid[]" value="{cat_row.menu_row.MENU_CB_ID}"{cat_row.menu_row.MENU_CHECKED} /></td>
			<td class="row1 cms-menu-list" style="padding-left: 10px; background: none; width: 288px;">{cat_row.menu_row.MENU_URL}</td>
			<td class="row1" style="padding-left: 5px; background: none;">{cat_row.menu_row.MENU_DESC}</td>
		</tr>
		</table>
		</li>
		<!-- END menu_row -->
		</ul>
		<!-- END cat_row -->
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

<script type="text/javascript">
//<![CDATA[
<!-- BEGIN cat_row -->
function update_order_{cat_row.CAT_CB_ID}()
{
	var request_options = {method: 'post', parameters: 'mode=update_menu_order&cat=' + {cat_row.CAT_CB_ID} + '&' + Sortable.serialize("list_{cat_row.CAT_CB_ID}") + '&sid=' + S_SID};
	new Ajax.Request(ip_root_path + 'cms_db_update.' + php_ext, request_options);
}
Sortable.create('list_{cat_row.CAT_CB_ID}', {handle: 'icon-edit-move', onUpdate: function(){update_order_{cat_row.CAT_CB_ID}(); $('sort-info-box').show(); $('sort-info-box').innerHTML = box_begin + '{L_MENU_UPDATED}' + box_end; new Effect.Highlight('result-box', {duration: 0.5}); window.setTimeout("new Effect.Fade('sort-info-box', {duration: 0.5})", 2500);}});
<!-- END cat_row -->
//]]>
</script>

<!-- INCLUDE ../common/cms/cms_info_box.tpl -->
<!-- INCLUDE ../common/cms/page_footer.tpl -->