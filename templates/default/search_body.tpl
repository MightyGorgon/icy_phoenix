<!-- INCLUDE overall_header.tpl -->

<form action="{S_SEARCH_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_QUERY}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" colspan="2" width="50%" style="vertical-align: top;">
		<span class="gensmall">{L_SEARCH_KEYWORDS}:</span><br />
		<span class="gensmall">{L_SEARCH_KEYWORDS_EXPLAIN}</span>
	</td>
	<td class="row2" colspan="2">
		<input type="text" style="width: 300px" class="post" name="search_keywords" size="30" /><br /><br />
		<b>{L_SEARCH_WHAT}:</b><br />
		<input type="radio" name="search_terms" value="any" checked="checked" />&nbsp;{L_SEARCH_ANY_TERMS}<br />
		<input type="radio" name="search_terms" value="all" />&nbsp;{L_SEARCH_ALL_TERMS}<br /><br />
		<b>{L_SEARCH_WHERE}:</b><br />
		<input type="radio" name="search_fields" value="titleonly" checked="checked" />&nbsp;{L_SEARCH_TITLE_ONLY}<br />
		<input type="radio" name="search_fields" value="msgonly" />&nbsp;{L_SEARCH_MESSAGE_ONLY}<br />
		<input type="radio" name="search_fields" value="all" />&nbsp;{L_SEARCH_MESSAGE_TITLE}<br />
		{L_ONLY_BLUECARDS}<br />
	</td>
</tr>
<tr>
	<td class="row1" colspan="2" style="vertical-align: top;">
		<span class="gensmall">{L_SEARCH_AUTHOR}:</span><br />
		<span class="gensmall">{L_SEARCH_AUTHOR_EXPLAIN}</span>
	</td>
	<td class="row2" colspan="2">
		<input type="text" style="width: 300px;" class="post" name="search_author" size="30" /><br />
		<input type="checkbox" name="search_topic_starter" />&nbsp;{L_SEARCH_AUTHOR_TOPIC_STARTER}
	</td>
</tr>
<!-- IF S_ADMIN_MOD -->
<tr>
	<td class="row1" colspan="2" style="vertical-align: top;"><span class="gensmall">{L_SEARCH_IP}:</span><br /><span class="gensmall">{L_SEARCH_IP_EXPLAIN}</span></td>
	<td class="row2" colspan="2" valign="middle"><span class="genmed"><input type="text" style="width: 300px;" class="post" name="search_ip" size="30" /></span></td>
</tr>
<!-- ENDIF -->
<tr><th colspan="4">{L_SEARCH_OPTIONS}</th></tr>
<tr>
	<td class="row1" style="vertical-align: top;"><span class="gensmall">{L_FORUM}:</span></td>
	<td class="row2"><select class="post" name="search_where">{S_FORUM_OPTIONS}</select></td>
	<td class="row1" nowrap="nowrap" style="vertical-align: top;"><span class="gensmall">{L_SEARCH_PREVIOUS}:</span></td>
	<td class="row2"><select class="post" name="search_time">{S_TIME_OPTIONS}</select></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap" style="vertical-align: top;"><span class="gensmall">{L_DISPLAY_RESULTS}:</span></td>
	<td class="row2" nowrap="nowrap">
		<input type="radio" name="show_results" value="posts" /><span class="genmed">{L_POSTS}</span>&nbsp;&nbsp;
		<input type="radio" name="show_results" value="topics" checked="checked" /><span class="genmed">{L_TOPICS}</span>
	</td>
	<td class="row1" rowspan="2" style="vertical-align: top;"><span class="gensmall">{L_SORT_BY}:</span></td>
	<td class="row2" nowrap="nowrap"  rowspan="2">
		<select class="post" name="sort_by">{S_SORT_OPTIONS}</select><br />
		<input type="radio" name="sort_dir" value="ASC" />&nbsp;{L_SORT_ASCENDING}
		<input type="radio" name="sort_dir" value="DESC" checked="checked" />&nbsp;{L_SORT_DESCENDING}
	</td>
</tr>
<tr>
	<td class="row1" style="vertical-align: top;"><span class="gensmall">{L_RETURN_FIRST}:</span></td>
	<td class="row2"><select class="post" name="return_chars">{S_CHARACTER_OPTIONS}</select>&nbsp;{L_CHARACTERS}</td>
</tr>
<tr>
	<td class="cat" colspan="4" align="center">
		<input type="hidden" name="ctracker_validation" value="{CTVALID}" />{S_HIDDEN_FIELDS}
		<input class="mainoption" type="submit" value="{L_SEARCH}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<div align="right">{JUMPBOX}</div>

<!-- INCLUDE overall_footer.tpl -->