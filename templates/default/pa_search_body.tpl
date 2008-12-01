<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

<form action="{S_SEARCH_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="50%"><span class="gen">{L_SEARCH_KEYWORDS}:</span><br /><span class="gensmall">{L_SEARCH_KEYWORDS_EXPLAIN}</span></td>
	<td class="row2" valign="top"><span class="genmed"><input type="text" style="width: 300px" class="post" name="search_keywords" size="30" /><br /><input type="radio" name="search_terms" value="any" checked="checked" /> {L_SEARCH_ANY_TERMS}<br /><input type="radio" name="search_terms" value="all" /> {L_SEARCH_ALL_TERMS}</span></td>
</tr>
<tr>
	<td class="row1"><span class="gen">{L_SEARCH_AUTHOR}:</span><br /><span class="gensmall">{L_SEARCH_AUTHOR_EXPLAIN}</span></td>
	<td class="row2" valign="middle"><span class="genmed"><input type="text" style="width: 300px" class="post" name="search_author" size="30" /></span></td>
</tr>
<tr><th colspan="2" height="25">{L_SEARCH_OPTIONS}</th></tr>
<tr>
	<td class="row1" width="50%"><span class="genmed">{L_CHOOSE_CAT}&nbsp; </span></td>
	<td class="row2"><select name="cat_id" class="forminput"><option value="0" selected>{L_ALL}</option>{S_CAT_MENU}</select></td>
</tr>
<tr>
	<td class="row1" width="50%"><span class="genmed">{L_INCLUDE_COMMENTS}:&nbsp; </span></td>
	<td class="row2"><span class="genmed"><input type="radio" name="comments_search" value="YES" checked="checked" /> {L_YES} <input type="radio" name="comments_search" value="NO" /> {L_NO}</span></td>
</tr>
<tr>
<td class="row1"><span class="genmed">{L_SORT_BY}:&nbsp;</span></td>
<td class="row2" valign="middle" nowrap="nowrap"><span class="genmed">
<select class="post" name="sort_method">
	<option value='file_name'>{L_NAME}</option>
	<option selected="selected" value='file_time'>{L_DATE}</option>
	<option value='file_rating'>{L_RATING}</option>
	<option value='file_dls'>{L_DOWNLOADS}</option>
	<option value='file_update_time'>{L_UPDATE_TIME}</option>
</select></span>&nbsp;</td>
</tr>
<tr>
	<td class="row1"><span class="genmed">{L_SORT_DIR}:&nbsp;</span></td>
	<td class="row2" valign="middle" nowrap="nowrap"><span class="genmed"><input type="radio" name="sort_order" value="ASC" /> {L_SORT_ASCENDING} <input type="radio" name="sort_order" value="DESC" checked /> {L_SORT_DESCENDING}</span>&nbsp;</td>
</tr>
<tr><td class="cat" align="center" colspan="2"><input type="hidden" name="action" value="search"><input class="liteoption" type="submit" name="submit" value="{L_SEARCH}"></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />
<!-- INCLUDE pa_footer.tpl -->