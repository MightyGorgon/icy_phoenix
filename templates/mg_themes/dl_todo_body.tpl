{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_DL_TOP}">{L_DL_TOP}</a>{NAV_SEP}<a href="#" class="nav-current">{L_DL_TODO}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_DL_TODO}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_DESCRIPTION}</th>
	<th>{L_DL_TODO}</th>
</tr>
<!-- BEGIN todolist_row -->
<tr>
	<td class="{todolist_row.ROW_CLASS}" valign="top"><span class="forumlink"><a href="{todolist_row.FILE_LINK}" class="forumlink">{todolist_row.FILENAME}</a></span><span class="gen">{todolist_row.HACK_VERSION}</span></td>
	<td class="{todolist_row.ROW_CLASS}" valign="top"><span class="gensmall">{todolist_row.TODO}</span></td>
</tr>
<!-- END todolist_row -->
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" colspan="2"></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />