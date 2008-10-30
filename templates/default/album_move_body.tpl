<form action="{S_ALBUM_ACTION}" method="post">
<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_ALBUM}" class="nav">{L_ALBUM}</a>{NAV_SEP}<a href="" class="nav-current">{L_MOVE}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MOVE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1 row-center"><br /><span class="gen">{L_MOVE_TO_CATEGORY}</span> &nbsp; {S_CATEGORY_SELECT} &nbsp; <input class="mainoption" type="submit" name="move" value="{L_MOVE}" /><br />&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- BEGIN pic_id_array -->
<input type="hidden" name="pic_id[]" value="{pic_id_array.VALUE}" />
<!-- END pic_id_array -->
</form>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}