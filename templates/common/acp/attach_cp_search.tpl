<h1>{L_CONTROL_PANEL_TITLE}</h1>
<p>{L_CONTROL_PANEL_EXPLAIN}</p>

<form method="post" action="{S_MODE_ACTION}">
<table class="s2px p2px">
<tr>
	<td class="tdalignr tdnw"><span class="genmed">{L_VIEW}:&nbsp;{S_VIEW_SELECT}&nbsp;&nbsp;
	<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
	</span></td>
</tr>
</table>

<table class="forumline">
<tr><th colspan="4" height="25">{L_ATTACH_SEARCH_QUERY}</th></tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_FILENAME}:</span><br /><span class="gensmall">{L_WILDCARD_EXPLAIN}</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 200px" class="post" name="search_keyword_fname" size="20" /></span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_COMMENT}:</span><br /><span class="gensmall">{L_WILDCARD_EXPLAIN}</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 200px" class="post" name="search_keyword_comment" size="20" /></span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_SEARCH_AUTHOR}:</span><br /><span class="gensmall">{L_WILDCARD_EXPLAIN}</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 200px" class="post" name="search_author" size="20" /></span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_SIZE_SMALLER_THAN}:</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 100px" class="post" name="search_size_smaller" size="10" /></span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_SIZE_GREATER_THAN}:</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 100px" class="post" name="search_size_greater" size="10" /></span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_COUNT_SMALLER_THAN}:</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 100px" class="post" name="search_count_smaller" size="10" /></span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_COUNT_GREATER_THAN}:</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 100px" class="post" name="search_count_greater" size="10" /></span></td>
</tr>
<tr>
	<td class="row1" colspan="2"><span class="gen">{L_MORE_DAYS_OLD}:</span></td>
	<td class="row2 tvalignm" colspan="2"><span class="genmed"><input type="text" style="width: 100px" class="post" name="search_days_greater" size="10" /></span></td>
</tr>
<tr><th colspan="4">{L_SEARCH_OPTIONS}</th></tr>
<tr>
	<td class="row1" colspan="2" align="right"><span class="gen">{L_FORUM}:</span></td>
	<td class="row2 tvalignm" colspan="2"><select class="post" name="search_forum">{S_FORUM_OPTIONS}</select></span></td>
</tr>
<tr>
	<td class="row1" colspan="2" align="right"><span class="gen">{L_SORT_BY}:&nbsp;</span></td>
	<td class="row2 tvalignm" colspan="2">{S_SORT_OPTIONS}</span></td>
<tr>
	<td class="row1" colspan="2" align="right"><span class="gen">{L_ORDER}:&nbsp;</span></td>
	<td class="row2 tvalignm" colspan="2">{S_SORT_ORDER}</span></td>
</tr>
<tr><td class="cat" colspan="4" align="center">{S_HIDDEN_FIELDS}<input class="liteoption" type="submit" name="search" value="{L_SEARCH}" /></td></tr>
</table>

</form>

<br />
<div align="center"><span class="copyright">{ATTACH_VERSION}</span></div>
