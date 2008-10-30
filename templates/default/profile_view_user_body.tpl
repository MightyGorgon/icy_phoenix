{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_PROFILE}">{L_CPL_NAV}</a>{NAV_SEP}<a href="#">{L_Profile_viewed}</a>{NAV_SEP}{PROFILE}
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

<!-- INCLUDE profile_cpl_menu_inc_start.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PROFILE_VIEWED}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="50%" nowrap="nowrap">{L_VIEWER}</th>
	<th width="25%" nowrap="nowrap">{L_STAMP}</th>
	<th width="25%" nowrap="nowrap">{L_NUMBER}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="row1 row-center">{row.AVATAR}<br />{row.VIEW_BY}</td>
	<td class="row1 row-center">{row.STAMP}</td>
	<td class="row1 row-center">{row.NUMBER}</td>
</tr>
<!-- END row -->
<tr><td class="cat" colspan="3">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right" valign="top" nowrap="nowrap"><span class="pagination">{PAGINATION}</span><br /></td></tr>
</table>

<!-- INCLUDE profile_cpl_menu_inc_end.tpl -->