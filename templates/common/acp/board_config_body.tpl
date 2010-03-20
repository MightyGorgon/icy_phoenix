<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<form id="configform" action="{S_CONFIG_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_HEADER_FOOTER}</th></tr>
<tr>
	<td class="row1"><strong>{L_HEADER_TABLE_SWITCH}</strong><br /><span class="gensmall">{L_HEADER_TABLE_SWITCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_header_table" value="1" {HEADER_TBL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_header_table" value="0" {HEADER_TBL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_HEADER_TABLE_TEXT}</strong></td>
	<td class="row2"><textarea name="header_table_text" rows="10" cols="35" style="width:300" class="post" >{HEADER_TBL_TXT}</textarea></td>
</tr>
<tr><th colspan="2">{L_SHOUTBOX_CONFIG}</th></tr>
<tr>
	<td class="row1"><strong>{L_SHOUTBOX_REFRESHTIME}</strong><br /><span class="gensmall">{L_SHOUTBOX_REFRESH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="shoutbox_refreshtime" size="5" maxlength="4" value="{SHOUTBOX_REFRESHTIME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_DISPLAYED_SHOUTS}</strong><br /><span class="gensmall">{L_DISPLAYED_SHOUTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="display_shouts" size="3" maxlength="4" value="{DISPLAYED_SHOUTS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_STORED_SHOUTS}</strong><br /><span class="gensmall">{L_STORED_SHOUTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="stored_shouts" size="3" maxlength="4" value="{STORED_SHOUTS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SHOUTBOX_FLOOD}</strong><br /><span class="gensmall">{L_SHOUTBOX_FLOOD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="shoutbox_floodinterval" size="3" maxlength="4" value="{SHOUTBOX_FLOODINTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_GUEST_ALLOWED}</strong></td>
	<td class="row2"><input type="radio" name="shout_allow_guest" value="1" {SHOUT_GUEST_YES} />{L_YES}&nbsp;&nbsp;<input type="radio" name="shout_allow_guest" value="2" {SHOUT_GUEST_READONLY} />{L_SHOUT_GUEST_READONLY}&nbsp;&nbsp;<input type="radio" name="shout_allow_guest" value="0" {SHOUT_GUEST_NO} />{L_NO}</td>
</tr>
{BB_USAGE_STATS_ADMIN_TEMPLATE}
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>
<br clear="all" />