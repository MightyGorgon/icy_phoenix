<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_ALBUM}">{L_ALBUM}</a>{NAV_SEP}<a href="" class="nav-current">{L_USERS_PERSONAL_GALLERIES}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="nav">&nbsp;</td>
	<td align="right" nowrap="nowrap"><span class="gensmall">{ALBUM_SEARCH_BOX}</td>
</tr>
</table>

{ALBUM_BOARD_INDEX}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_USERS_PERSONAL_GALLERIES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="100%" height="25" nowrap="nowrap">&nbsp;{L_USERS}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_JOINED}&nbsp;</th>
	<th nowrap="nowrap">&nbsp;{L_PICS}&nbsp;</th>
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td height="28" class="row1h">&nbsp;<span class="gen"><a href="{memberrow.U_VIEWGALLERY}" class="gen">{memberrow.USERNAME}</a></span></td>
	<td class="row1 row-center" nowrap="nowrap"><span class="gensmall">&nbsp;{memberrow.JOINED}&nbsp;</span></td>
	<td class="row1 row-center" align="center"><span class="gensmall">{memberrow.PICS}</span></td>
</tr>
<!-- END memberrow -->
<tr>
	<td class="cat" colspan="3" align="center">
		<form method="post" action="{S_MODE_ACTION}">
			<span class="gensmall">
			{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
			<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
			</span>
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td align="right" valign="bottom"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></tr>
</table>
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}