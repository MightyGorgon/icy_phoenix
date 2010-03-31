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
<tr><td class="row3 row-center"><span class="gen">{L_AUTO_SET}<br /><a href="{U_AUTO_SET}" class="gen">{L_GO}</a></td></tr>
</table>

<br />

<form method="post" action="{S_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="row1" style="padding: 0px;" valign="top">
		<table class="nav-div" width="100%" align="center" style="padding: 0px;" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th style="text-align: center; width: 90px;">#</th>
			<th style="text-align: center;">{L_NAME}</th>
			<th style="text-align: center; width: 240px;">{L_MODULE_NAME}</th>
			<th style="text-align: center; width: 80px;">{L_STATUS}</th>
			<th style="text-align: center; width: 90px;">{L_UPDATE_TIME}</th>
		</tr>
		</table>
		<ul id="stats_modules" style="margin: 0px; padding: 0px; list-style-type: none;">
		<!-- BEGIN modulerow -->
		<li id="item_{modulerow.MODULE_ID}" style="cursor: move;">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr class="{modulerow.ROW_CLASS}h">
			<td class="{modulerow.ROW_CLASS} row-center" style="padding: 0px; background: none; width: 100px;">{modulerow.U_MOVE_DOWN}&nbsp;{modulerow.U_MOVE_UP}&nbsp;{modulerow.U_EDIT}</td>
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
<br /><div class="copyright" style="text-align:center;">{VERSION_INFO}<br />{INSTALL_INFO}</div>

<div id="sort-info-box" class="row-center" style="position: fixed; top: 10px; right: 10px; z-index: 1; background: none; border: none; width: 300px; padding: 3px;"></div>

<script type="text/javascript">
//<![CDATA[
var box_begin = '<div id="result-box" style="height: 16px; border: solid 1px #228822; background: #77dd99;"><span class="text_green">';
var box_end = '<\/span><\/div>';
function update_order()
{
	var request_options = {method: 'post', parameters: 'mode=update_modules_order&' + Sortable.serialize("stats_modules") + '&sid=' + S_SID};
	new Ajax.Request(ip_root_path + 'cms_db_update.' + php_ext, request_options);
}
Sortable.create('stats_modules', {onUpdate:function(){update_order(); $('sort-info-box').innerHTML = box_begin + '{L_MODULES_UPDATED}' + box_end; new Effect.Highlight('result-box', {duration: 0.5}); window.setTimeout("new Effect.Fade('result-box',{duration: 0.5})", 2500);}});
//]]>
</script>