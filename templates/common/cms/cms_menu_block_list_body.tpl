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
			<th align="center" style="width:100px;">{L_CMS_ACTIONS}</th>
			<th align="center" style="width:20px;">&nbsp;</th>
			<th align="center" style="width:290px;">{L_CMS_NAME}</th>
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
			<th align="center" style="width:100px;">
				{cat_row.U_MOVE_UP}
				{cat_row.U_MOVE_DOWN}
				{cat_row.U_EDIT}
				{cat_row.U_DELETE}
			</th>
			<th align="center" style="width:20px;"><input type="checkbox" name="cb_mid[]" value="{cat_row.CAT_CB_ID}"{cat_row.CAT_CHECKED} /></th>
			<th style="width:290px;">{cat_row.CAT_ICON}<b>{cat_row.CAT_ITEM}</b></th>
			<th align="center"><b>{cat_row.CAT_DESC}</b></th>
			<!-- <td class="row1" style="padding-left:5px;"><b>{cat_row.CAT_DESC}</b></td> -->
		</tr>
		</table>
		<ul id="list_{cat_row.CAT_CB_ID}" style="margin:0px;padding:0px;list-style-type:none;">
		<!-- BEGIN menu_row -->
		<li id="item_{cat_row.menu_row.MENU_CB_ID}" style="cursor:move;">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr class="row1h">
			<td class="row1 row-center" style="padding:0px;background:none;width:110px;">
				{cat_row.menu_row.U_MOVE_UP}
				{cat_row.menu_row.U_MOVE_DOWN}
				{cat_row.menu_row.U_EDIT}
				{cat_row.menu_row.U_DELETE}
			</td>
			<td class="row1 row-center" style="padding:0px;background:none;width:30px;"><input type="checkbox" name="cb_mid[]" value="{cat_row.menu_row.MENU_CB_ID}"{cat_row.menu_row.MENU_CHECKED} /></td>
			<td class="row1" style="padding-left:10px;background:none;width:288px;">{cat_row.menu_row.MENU_URL}</td>
			<td class="row1" style="padding-left:5px;background:none;">{cat_row.menu_row.MENU_DESC}</td>
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

<div id="sort-info-box" class="row-center" style="position: fixed; top: 0px; right: 25px; z-index: 1; background: none; border: none; width: 300px; padding: 3px;"></div>

<script type="text/javascript">
//<![CDATA[
var box_begin = '<div id="result-box" style="height: 16px; border: solid 1px #228844; background: #77dd99;"><span class="text_green">';
var box_end = '<\/span><\/div>';
<!-- BEGIN cat_row -->
function update_order_{cat_row.CAT_CB_ID}()
{
	var request_options = {method: 'post', parameters: 'mode=update_menu_order&cat=' + {cat_row.CAT_CB_ID} + '&' + Sortable.serialize("list_{cat_row.CAT_CB_ID}") + '&sid=' + S_SID};
	new Ajax.Request(ip_root_path + 'cms_db_update.' + php_ext, request_options);
}
Sortable.create('list_{cat_row.CAT_CB_ID}', {onUpdate:function(){update_order_{cat_row.CAT_CB_ID}(); $('sort-info-box').innerHTML = box_begin + '{L_MENU_UPDATED}' + box_end; new Effect.Highlight('result-box', {duration: 0.5}); window.setTimeout("new Effect.Fade('result-box',{duration: 0.5})", 2500);}});
//Sortable.create("list_{cat_row.CAT_CB_ID}", {onUpdate:function(){new Ajax.Updater('sort-info-box', 'cms_db_update.php', {asynchronous: true, evalScripts: true, onComplete: function(request){new Effect.Highlight("result-box",{duration: 0.5});}, parameters: 'mode=update_menu_order&cat=' + {cat_row.CAT_CB_ID} + '&' + Sortable.serialize("list_{cat_row.CAT_CB_ID}") + '&sid=' + S_SID})}});
<!-- END cat_row -->
//]]>
</script>