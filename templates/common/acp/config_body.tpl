<script type="text/javascript" src="../templates/rollout.js"></script>
<h1>{L_NEWS_SETTINGS}</h1>
<p>{L_NEWS_SETTINGS_EXPLAIN}</p>

<form action="{S_MAP_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="row1" width="22%"><span class="gen"><b>{L_BLOCK_TITLE}</b></span></td>
	<td class="row2"><input type="text" size="65" name="block_title" value="{E_BLOCK_TITLE}" class="post" /></td>
</tr>

<tr>
	<td class="row1"><span class="gen">{L_BLOCK_DESC}</span></td>
	<td class="row2"><input type="text" size="65" name="block_desc" value="{E_BLOCK_DESC}" class="post" /></td>
</tr>

<tr>
	<td class="row1" width="50%"><span class="gen">{L_SHOW_TITLE}</span><br /><span class="gensmall">{L_SHOW_TITLE_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="show_title" value="1" {S_SHOW_TITLE_YES} /> <span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="show_title" value="0" {S_SHOW_TITLE_NO} /> <span class="gen">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1" width="22%"><span class="gen">{L_SHOW_STATS}</span></td>
	<td class="row2" width="78%"><input type="radio" name="show_stats" value="1" {S_SHOW_STATS_YES} /> <span class="gen">{L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_stats" value="0" {S_SHOW_STATS_NO} /> <span class="gen">{L_NO}</span></td>
</tr>

<tr>
	<td class="row1" width="22%"><span class="gen">{L_SHOW_BLOCK}</span></td>
	<td class="row2" width="78%"><input type="radio" name="show_block" value="1" {S_SHOW_BLOCK_YES} /> <span class="gen">{L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block" value="0" {S_SHOW_BLOCK_NO} /> <span class="gen">{L_NO}</span></td>
</tr>

<tr>
	<td class="row1" width="50%">{L_DEFAULT_CAT}<br /><span class="gensmall">{L_DEFAULT_CAT_EXPLAIN}</span></td>
	<td class="row2" width="50%"><select name="pa_quick_cat" class="post">{S_DEFAULT_CAT_LIST}</select></td>
</tr>

<tr>
	<td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="hidden" name="pa_mode" value="update" /><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</table>
</form>
<br />

<form action="{S_MAP_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN map_row -->
<tr>
	<td class="row1 row-center" valign="top"><select name="map_cat_id_{map_row.CAT_ID}" class="post">{map_row.CAT_LIST}</span></td>
	<td class="row1" align="left" valign="top">{map_row.DYN_LIST}</td>
	<td class="row1" align="left" valign="top"><a href="{map_row.DELETE}">{map_row.L_DELETE}</a></td>
</tr>
<!-- END map_row -->
<tr><td class="cat" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="hidden" name="pa_mode" value="update_map" /><input type="submit" name="submit" value="{L_EDIT}" class="mainoption" /></td></tr>
</table>
</form>

<br />
<form action="{S_MAP_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="right" class="row2" width="50%"><select name="map_cat_id" class="post">{S_MAP_CAT_LIST}</select></td>
	<td align="left" class="row2" width="50%">{S_MAP_DYN_LIST}</td>
</tr>
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="hidden" name="pa_mode" value="new_map" /><input type="submit" name="submit" value="{L_ADD}" class="mainoption" /></td></tr>
</table>
</form>

<br clear="all" />