<h1>{L_PLUGINS}</h1>
<p>{L_PLUGINS_EXPLAIN}</p>
<br />

<form method="post" action="{S_PLUGINS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th>{L_PLUGINS_DESCRIPTION}</th>
	<th width="200" style="width: 200px;">{L_PLUGINS_VERSION}</th>
	<th width="200" style="width: 200px;">{L_ACTIONS}</th>
</tr>
<!-- IF S_NO_PLUGINS -->
<tr><td class="row1 row-center" colspan="3">{L_PLUGINS_NO_PLUGINS}</td></tr>
<!-- ELSE -->
<!-- BEGIN plugin -->
<tr>
	<td class="{plugin.ROW_CLASS}">
		<span class="gensmall" style="float: right; text-align: right; padding: 3px;"><!-- IF plugin.PLUGIN_INSTALLED --><!-- IF not plugin.PLUGIN_UP_TO_DATE -->{plugin.PLUGIN_LINK_UPDATE}&nbsp;&bull;&nbsp;<!-- ENDIF -->{plugin.PLUGIN_LINK_UNINSTALL}<!-- ELSE -->{plugin.PLUGIN_LINK_INSTALL}<!-- ENDIF --></span>
		<b>{plugin.PLUGIN_NAME}</b><br />
		<span class="gensmall"><b>{L_PLUGINS_FOLDER}</b>:&nbsp;<i>{plugin.PLUGIN_DIR}</i></span>
		<!-- IF plugin.PLUGIN_DESCRIPTION --><br /><span class="gensmall">{plugin.PLUGIN_DESCRIPTION}</span><!-- ENDIF -->
	</td>
	<td class="{plugin.ROW_CLASS} row-center">
		<span class="genmed"><!-- IF plugin.PLUGIN_CURRENT_VERSION -->{L_PLUGINS_CURRENT_VERSION}:&nbsp;<b{plugin.PLUGIN_STATUS_COLOR}>{plugin.PLUGIN_CURRENT_VERSION}</b></span><br />
		<span class="genmed"><!-- IF plugin.PLUGIN_UP_TO_DATE --><i{plugin.PLUGIN_STATUS_COLOR}>{plugin.PLUGIN_STATUS}</i><!-- ELSE -->{L_PLUGINS_LAST_VERSION}:&nbsp;<b>{plugin.PLUGIN_LAST_VERSION}</b><!-- ENDIF --><!-- ELSE --><b{plugin.PLUGIN_STATUS_COLOR}>{L_PLUGINS_NOT_INSTALLED}</b><!-- ENDIF --></span>
	</td>
	<td class="{plugin.ROW_CLASS} row-center">{plugin.PLUGIN_RADIO}</td>
</tr>
<!-- END plugin -->
<!-- ENDIF -->
<tr><td class="cat" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_PLUGINS_UPDATE_CONFIG}" class="mainoption" /></td></tr>
</table>
</form>
<br />