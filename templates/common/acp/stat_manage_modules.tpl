<h1>{L_STATS_MANAGE}</h1>

<br />

<!-- IF MESSAGE -->
<table class="forumline" width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr><th>{L_MESSAGES}</th></tr>
<tr><td class="row1 row-center"><span class="genmed"><strong>{MESSAGE}</strong></td></tr>
<tr><td class="cat" colspan="1">&nbsp;</td></tr>
</table>
<!-- ENDIF -->

<br />

<table class="forumline" width="80%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row3 row-center"><span class="genmed">{L_AUTO_SET_UPDATE_TIME}<br /><br /><a href="{U_AUTO_SET}">{L_GO}</a></span></td></tr>
<tr><td class="row3 row-center"><span class="gensmall">{L_STAT_BLOCKS_SORT}</span></td></tr>
</table>

<br />

<form method="post" action="{S_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="row1" style="padding: 0px;" valign="top">
		<table class="nav-div" width="100%" align="center" style="padding: 0px;" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th style="text-align: center; width: 110px;">{L_ACTIONS}</th>
			<th style="text-align: center;">{L_NAME}</th>
			<th style="text-align: center; width: 240px;">{L_MODULE_NAME}</th>
			<th style="text-align: center; width: 80px;">{L_STATUS}</th>
			<th style="text-align: center; width: 90px;">{L_UPDATE_TIME}</th>
		</tr>
		</table>
		<ul id="stats_modules" style="margin: 0px; padding: 0px; list-style-type: none;">
		<!-- BEGIN modulerow -->
		<li id="item_{modulerow.MODULE_ID}">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr class="{modulerow.ROW_CLASS}h">
			<td class="{modulerow.ROW_CLASS} row-center" style="padding: 0px; background: none; width: 120px;"><a class="icon-edit-move-empty" href="javascript:void(0);"><img src="../templates/common/images/cms_icon_move.png" alt="{L_MOVE} " title="{L_MOVE}" /></a>&nbsp;{modulerow.U_MOVE_DOWN}&nbsp;{modulerow.U_MOVE_UP}&nbsp;{modulerow.U_EDIT}</td>
			<td class="{modulerow.ROW_CLASS}" style="padding: 0px; background: none;"><b>{modulerow.NAME}</b></td>
			<td class="{modulerow.ROW_CLASS}" style="padding: 0px; background: none; width: 250px;">{modulerow.DNAME}</td>
			<td class="{modulerow.ROW_CLASS} row-center" style="padding: 0px; background: none; width: 90px;"><!-- IF modulerow.S_STATUS_CHECK --><input type="checkbox" name="module_status[]" value="{modulerow.MODULE_ID}"{modulerow.CHECKED} />&nbsp;<!-- ENDIF -->{modulerow.U_STATE}</td>
			<td class="{modulerow.ROW_CLASS} row-center" style="padding: 0px; background: none; width: 100px;"><input type="text" class="post" maxlength="10" name="module_time_{modulerow.MODULE_ID}" value="{modulerow.UPDATE_TIME}" style="width: 50px;" />
		</tr>
		</table>
		</li>
		<!-- END modulerow -->
		</ul>
	</td>
</tr>
<tr><td class="cat">{S_HIDDEN_FIELDS}<input type="submit" name="update" value="{L_UPDATE_MODULES}" class="mainoption" /></td></tr>
</table>

<!-- This copyright information must be displayed as per the liscence you agree to by using this modification! -->
<br /><div class="copyright" style="text-align: center;">{VERSION_INFO}<br />{INSTALL_INFO}</div>

<div id="sort-info-box" class="row-center" style="position: fixed; top: 10px; right: 10px; z-index: 1; background: none; border: none; width: 300px; padding: 3px;"></div>

<script type="text/javascript">
//<![CDATA[
//var box_begin = '<div id="result-box" style="height: 16px; border: solid 1px #228822; background: #77dd99;"><span class="text_green">';
//var box_end = '<\/span><\/div>';
var box_begin = '<div id="result-box" class="rmbox rmb-green"><p class="rmb-center">';
var box_end = '<\/p><\/div>';
var box_updated = box_begin;
var page_url = ip_root_path;

var sort_info_box = jQuery('#sort-info-box');
var stats_modules = jQuery('#stats_modules');
box_updated += '{L_MODULES_UPDATED}';
box_updated += box_end;
page_url += 'cms_db_update.';
page_url += php_ext;

stats_modules.sortable(
{
	update: function ()
	{
		update_order();
		sort_info_box.html(box_updated);
		setTimeout(function ()
		{
			sort_info_box.html('');
		}, 2500);
	},

	handle: '.icon-edit-move-empty'

}).disableSelection();

function update_order()
{
	$.post(page_url, 'mode=update_modules_order&' + stats_modules.sortable('serialize') + '&sid=' + S_SID);
}
//]]>
</script>