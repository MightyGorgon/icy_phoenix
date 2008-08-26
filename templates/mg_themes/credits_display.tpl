{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{S_MODE_ACTION}" class="nav-current">{L_PAGE_NAME}</a>
	</p>
		<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_IP_TEAM}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center" align="center" nowrap="nowrap"><div class="post-text">{L_BBC_IP_CREDITS}</div></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="30%" height="25">{L_HACK_NAME}</th>
	<th width="20%">{L_AUTHOR}</th>
	<th>{L_DESCRIPTION}</th>
	<th>{L_WEBSITE}</th>
</tr>
<!-- BEGIN empty_switch -->
<tr><td colspan="4" class="row1 row-center">{L_NO_HACKS}</td></tr>
<!-- END empty_switch -->

<!-- BEGIN listrow -->
<tr>
	<td class="row1" valign="middle"><span class="gen">&nbsp;&nbsp;{listrow.HACK_NAME}&nbsp;{listrow.HACK_VERSION}</span></td>
	<td class="row1g" valign="middle"><span class="genmed">{listrow.HACK_AUTHOR}</span></td>
	<td class="row1"><span class="genmed">{listrow.HACK_DESC}</span></td>
	<td class="row1g" valign="middle"><span class="gensmall">{listrow.HACK_WEBSITE}</span></td>
</tr>
<!-- END listrow -->

<tr><td class="catbottom" colspan="4" align="right">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}