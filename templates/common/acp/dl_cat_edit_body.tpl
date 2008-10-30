<form action="{S_CATEGORY_ACTION}" method="post" name="dl_edit_cat">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="3">{L_DL_CAT_TITLE} :: {L_DL_CAT_MODE}</th></tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_NAME}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_NAME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%"><input type="text" class="post" name="cat_name" size="40" maxlength="255" value="{CAT_NAME}" /></td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_CAT_PATH}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_CAT_PATH_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%"><input type="text" class="post" name="path" size="40" maxlength="255" value="{CAT_PATH}" /></td>
</tr>
<tr>
	<td class="row1" width="49%" valign="top"><span class="genmed"><strong>{L_DL_DESCRIPTION}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_DESCRIPTION_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%"><textarea name="description" class="post" rows="3" cols="40">{DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1" width="49%" valign="top"><span class="genmed"><strong>{L_DL_RULES}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_RULES_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%"><textarea name="rules" class="post" rows="3" cols="40">{RULES}</textarea></td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_PARENT}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_PARENT_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">{CAT_PARENT}</td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_MUST_APPROVE}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_MUST_APPROVE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="radio" name="must_approve" value="1" {MUST_APPROVE_YES} />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="must_approve" value="0" {MUST_APPROVE_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_ALLOW_MOD_DESC}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_ALLOW_MOD_DESC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="radio" name="allow_mod_desc" value="1" {ALLOW_MOD_DESC_YES} />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="allow_mod_desc" value="0" {ALLOW_MOD_DESC_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_STATISTICS}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_STATISTICS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="radio" name="statistics" value="1" {STATS_YES} />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="statistics" value="0" {STATS_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_STATS_PRUNE}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_STATS_PRUNE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%"><input type="text" class="post" name="stats_prune" value="{STATS_PRUNE}" size="10" maxlength="8" /></td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_COMMENTS}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_COMMENTS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="radio" name="comments" value="1" {COMMENTS_YES} />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="comments" value="0" {COMMENTS_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_APPROVE}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_APPROVE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="radio" name="approve_comments" value="1" {APPROVE_COMMENTS_YES} />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="approve_comments" value="0" {APPROVE_COMMENTS_NO} />&nbsp;{L_NO}
	</td>
</tr>
<!-- BEGIN thumbnails -->
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_THUMBNAIL}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_THUMBNAIL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="radio" name="allow_thumbs" value="1" {ALLOW_THUMBS_YES} />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="allow_thumbs" value="0" {ALLOW_THUMBS_NO} />&nbsp;{L_NO}
	</td>
</tr>
<!-- END thumbnails -->
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_BUG_TRACKER}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_BUG_TRACKER_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="radio" name="bug_tracker" value="1" {BUG_TRACKER_YES} />&nbsp;{L_YES}&nbsp;
		<input type="radio" name="bug_tracker" value="0" {BUG_TRACKER_NO} />&nbsp;{L_NO}
	</td>
</tr>
<tr>
	<td class="row1" width="49%"><span class="genmed"><strong>{L_DL_CAT_TRAFFIC}:</strong></span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_CAT_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="49%">
		<input type="text" class="post" name="cat_traffic" size="10" maxlength="10" value="{CAT_TRAFFIC}" />
		<input name="cat_traffic_range" type="radio" value="KB" {CAT_TRAFFIC_RANGE_KB} />&nbsp;{L_DL_KB}&nbsp;&nbsp;&nbsp;
		<input name="cat_traffic_range" type="radio" value="MB" {CAT_TRAFFIC_RANGE_MB} />&nbsp;{L_DL_MB}&nbsp;&nbsp;&nbsp;
		<input name="cat_traffic_range" type="radio" value="GB" {CAT_TRAFFIC_RANGE_GB} />&nbsp;{L_DL_GB}
	</td>
</tr>
<tr><td class="cat" colspan="6"><input type="submit" name="save_cat" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="6">{L_PERMISSIONS_ALL}</th></tr>
<tr>
	<td class="row1 row-center" width="15%"><span class="nav"><strong>{L_AUTH_VIEW}</strong></span></td>
	<td class="row2 row-center" width="15%"><span class="nav"><strong>{L_AUTH_DL}</strong></span></td>
	<td class="row1 row-center" width="15%"><span class="nav"><strong>{L_AUTH_UP}</strong></span></td>
	<td class="row2 row-center" width="15%"><span class="nav"><strong>{L_AUTH_MOD}</strong></span></td>
	<td class="row1 row-center" width="15%"><span class="nav"><strong>{L_AUTH_CREAD}</strong></span></td>
	<td class="row2 row-center" width="15%"><span class="nav"><strong>{L_AUTH_CPOST}</strong></span></td>
</tr>
<tr>
	<td class="row1 row-center" width="15%">{S_AUTH_VIEW}</td>
	<td class="row2 row-center" width="15%">{S_AUTH_DL}</td>
	<td class="row1 row-center" width="15%">{S_AUTH_UP}</td>
	<td class="row2 row-center" width="15%">{S_AUTH_MOD}</td>
	<td class="row1 row-center" width="15%">{S_COMMENT_VIEW}</td>
	<td class="row2 row-center" width="15%">{S_COMMENT_POST}</td>
</tr>
<tr><td class="cat" colspan="6" align="center"><input type="submit" name="save_cat" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>

<!-- BEGIN group_block -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="5">{L_PERMISSIONS}</th></tr>
<tr>
	<td class="row3 row-center"><span class="genmed"><strong>{L_GROUP}</strong></span></td>
	<td class="row3 row-center"><span class="genmed"><strong>{L_AUTH_VIEW}</strong></span></td>
	<td class="row3 row-center"><span class="genmed"><strong>{L_AUTH_DL}</strong></span></td>
	<td class="row3 row-center"><span class="genmed"><strong>{L_AUTH_UP}</strong></span></td>
	<td class="row3 row-center"><span class="genmed"><strong>{L_AUTH_MOD}</strong></span></td>
</tr>
<!-- BEGIN group_row -->
<tr>
	<td class="row1" width="20%"><span class="nav"><strong>{group_block.group_row.GROUP_NAME}</strong></span></td>
	<td class="row2 row-center"><input name="auth_view_set[{group_block.group_row.GROUP_ID}]" type="checkbox" value="1" {group_block.group_row.AUTH_VIEW_GROUP} /></td>
	<td class="row1 row-center"><input name="auth_dl_set[{group_block.group_row.GROUP_ID}]" type="checkbox" value="1" {group_block.group_row.AUTH_DL_GROUP} /></td>
	<td class="row2 row-center"><input name="auth_up_set[{group_block.group_row.GROUP_ID}]" type="checkbox" value="1" {group_block.group_row.AUTH_UP_GROUP} /></td>
	<td class="row1 row-center"><input name="auth_mod_set[{group_block.group_row.GROUP_ID}]" type="checkbox" value="1" {group_block.group_row.AUTH_MOD_GROUP} /></td>
</tr>
<!-- END group_row -->
<tr><td class="cat" colspan="5" align="center"><input type="submit" name="save_cat" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
<!-- END group_block -->
{S_HIDDEN_FIELDS}
</form>
