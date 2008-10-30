{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_NAV1}">{L_NAV1}</a>{NAV_SEP}<a href="{U_NAV2}" class="nav-current">{L_NAV2}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

<form action="{S_SEARCH_ACTION}" method="post" >
{IMG_THL}{IMG_THC}<span class="forumlink">{L_NAV2}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_SEARCH_QUERY}</th></tr>
<tr>
	<td class="row1" width="50%" align="right"><span class="gen">{L_SEARCH_KEYWORDS}:</span></td>
	<td class="row2" valign="top"><span class="genmed"><input type="text" class="post" name="search_keywords" size="30" maxlength="255" /></span></td>
</tr>
<tr>
	<td class="row1" width="50%" align="right"><span class="gen">{L_SEARCH_AUTHOR}:</span></td>
	<td class="row2" valign="top"><span class="genmed"><input type="text" class="post" name="search_author" size="30" maxlength="32" /></span></td>
</tr>
<tr><th colspan="2">{L_SEARCH_OPTIONS}</th></tr>
<tr>
	<td class="row1" align="right"><span class="gen">{L_CATEGORY}:&nbsp;</span></td>
	<td class="row2"><span class="genmed">{S_CATEGORY_OPTIONS}</span></td>
</tr>
<tr>
	<td class="row1" align="right"><span class="gen">{L_SORT_BY}:&nbsp;</span></td>
	<td class="row2"><span class="genmed">{S_SORT_OPTIONS}</span></td>
</tr>
<tr>
	<td class="row1" align="right"><span class="gen">{L_SORT_DIR}:&nbsp;</span></td>
	<td class="row2"><span class="genmed">{S_SORT_ORDER}</span></td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" name="submit" value="{L_SEARCH}" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
<tr><td align="right" valign="middle">{JUMPBOX}</td></tr>
</table>