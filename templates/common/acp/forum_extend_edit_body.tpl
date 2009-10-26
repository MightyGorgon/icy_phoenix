<script type="text/javascript">
<!--
function update_icon(newimage)
{
	if(newimage != '')
	{
		document.icon_image.src = '../' + newimage;
		document.edit.icon.value = newimage;
	}
	else
	{
		document.icon_image.src = '../images/spacer.gif';
		document.edit.icon.value = '';
	}
}
//-->
</script>

<h1>{L_TITLE}</h1>

<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}" class="nav">{L_INDEX}</a>{NAV_CAT_DESC}</span>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{L_TITLE_EXPLAIN}</div>
		&nbsp;<br />&nbsp;
	</div>
</div>

<form method="post" name="edit" action="{S_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2" width="70%">{L_TITLE}</th></tr>
<!-- BEGIN move -->
<tr>
	<td class="row1"><span class="genmed"><b>{L_MOVE}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<select name="move">{S_MOVE_OPT}</select></span></td>
</tr>
<!-- END move -->
<tr>
	<td class="row1" width="40%"><span class="genmed"><b>{L_TYPE}</b></span></td>
	<td class="row2" width="60%"><span class="genmed">&nbsp;<select name="type" onchange="this.form.submit();">{S_TYPE_OPT}</select></span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_NAME}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<input name="name" value="{NAME}" type="text" class="post" size="60" /></span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_NAME_CLEAN}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<input name="name_clean" value="{NAME_CLEAN}" type="text" class="post" size="60" /></span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_DESC}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<textarea name="desc" rows="5" cols="60" class="post">{DESC}</textarea></span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_MAIN}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<select name="main" onchange="this.form.submit();">{S_FORUMS_OPT}</select></span></td>
</tr>
<!-- IF not S_FORUM_DELETE -->
<tr>
	<td class="row1"><span class="genmed"><b>{L_COPY_AUTH}</b></span><!-- <br /><span class="gensmall">{L_COPY_AUTH_EXPLAIN}</span> --></td>
	<td class="row2"><span class="genmed">&nbsp;<select name="dup_auth">{S_FORUM_LIST}</select></span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_ICON}</b></span><br /><span class="gensmall">{L_ICON_EXPLAIN}</span></td>
	<td class="row2">&nbsp;{ICON_LIST}</td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_POSITION}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<select name="position">{S_POS_OPT}</select></span></td>
</tr>
<!-- BEGIN forum -->
<tr>
	<td class="row1"><span class="genmed"><b>{L_STATUS}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<select name="status">{S_STATUS_OPT}</select></span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_LIMIT_EDIT_TIME}</b></span><br /><span class="gensmall">{L_FORUM_LIMIT_EDIT_TIME_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_limit_edit_time" value="1"{FORUM_LIMIT_EDIT_TIME_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_limit_edit_time" value="0"{FORUM_LIMIT_EDIT_TIME_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_THANK}</b></span></td>
	<td class="row2"><input type="radio" name="forum_thanks" value="1"{FORUM_THANK_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_thanks" value="0"{FORUM_THANK_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_SIMILAR_TOPICS}</b></span><br /><span class="gensmall">{L_FORUM_SIMILAR_TOPICS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_similar_topics" value="1"{FORUM_SIMILAR_TOPICS_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_similar_topics" value="0"{FORUM_SIMILAR_TOPICS_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_TOPIC_VIEWS}</b></span><br /><span class="gensmall">{L_FORUM_TOPIC_VIEWS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_topic_views" value="1"{FORUM_TOPIC_VIEWS_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_topic_views" value="0"{FORUM_TOPIC_VIEWS_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_TAGS}</b></span><br /><span class="gensmall">{L_FORUM_TAGS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_tags" value="1"{FORUM_TAGS_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_tags" value="0"{FORUM_TAGS_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_SORT_BOX}</b></span><br /><span class="gensmall">{L_FORUM_SORT_BOX_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_sort_box" value="1"{FORUM_SORT_BOX_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_sort_box" value="0"{FORUM_SORT_BOX_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_KB_MODE}</b></span><br /><span class="gensmall">{L_FORUM_KB_MODE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_kb_mode" value="1"{FORUM_KB_MODE_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_kb_mode" value="0"{FORUM_KB_MODE_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_INDEX_ICONS}</b></span><br /><span class="gensmall">{L_FORUM_INDEX_ICONS_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="forum_index_icons" value="1"{FORUM_INDEX_ICONS_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_index_icons" value="0"{FORUM_INDEX_ICONS_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_NOTIFY}</b></span></td>
	<td class="row2"><input type="radio" name="forum_notify" value="1"{FORUM_NOTIFY_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_notify" value="0"{FORUM_NOTIFY_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_POSTCOUNT}</b></span></td>
	<td class="row2"><input type="radio" name="forum_postcount" value="1"{FORUM_POST_COUNT_YES} />&nbsp;<span class="genmed">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="forum_postcount" value="0"{FORUM_POST_COUNT_NO} />&nbsp;<span class="genmed">{L_NO}</span></td>
</tr>
<tr><th colspan="2">{L_PRUNE_ENABLE}</th></tr>
<tr>
	<td class="row1" align="right"><span class="genmed"><b>{L_ENABLED}</b></span></td>
	<td class="row2"><span class="genmed"><input name="prune_enable" type="radio" value="1" {PRUNE_ENABLE_YES} />{L_YES}&nbsp;&nbsp;<input name="prune_enable" type="radio" value="0" {PRUNE_ENABLE_NO} />{L_NO}</span></td>
</tr>
<tr>
	<td class="row1" align="right"><span class="genmed"><b>{L_PRUNE_DAYS}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<input name="prune_days" type="text" class="post" value="{PRUNE_DAYS}" size="3" />&nbsp;{L_DAYS}</span></td>
</tr>
<tr>
	<td class="row1" align="right"><span class="genmed"><b>{L_PRUNE_FREQ}</b></span></td>
	<td class="row2"><span class="genmed">&nbsp;<input name="prune_freq" type="text" class="post" value="{PRUNE_FREQ}" size="3" />&nbsp;{L_DAYS}</span></td>
</tr>
<tr><th colspan="2">{L_MOD_OS_FORUMRULES}</th></tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_RULES_DISPLAY_TITLE}</b></td>
	<td class="row2"><input type="checkbox" name="rules_display_title" value="1" {S_RULES_DISPLAY_TITLE_ENABLED} />&nbsp;<span class="genmed">{L_ENABLED}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_RULES_CUSTOM_TITLE}</b></span></td>
	<td class="row2"><input type="text" name="rules_custom_title" value="{RULES_CUSTOM_TITLE}" size="50" maxlength="80" class="post" /></td>
</tr>
<tr>
	<td class="row1" valign="top"><span class="genmed"><b>{L_FORUM_RULES}</b></span></td>
	<td class="row2"><textarea rows="8" cols="70" name="rules" class="post">{RULES}</textarea></td>
</tr>
<tr>
	<td class="row1" valign="top"><span class="genmed"><b>{L_RULES_APPEAR_IN}</b></span></td>
	<td class="row2">
		<input type="checkbox" name="rules_in_viewforum" value="1" {S_RULES_VIEWFORUM_ENABLED} />&nbsp;<span class="genmed">{L_RULES_IN_VIEWFORUM}</span><br />
		<input type="checkbox" name="rules_in_viewtopic" value="1" {S_RULES_VIEWTOPIC_ENABLED} />&nbsp;<span class="genmed">{L_RULES_IN_VIEWTOPIC}</span><br />
		<input type="checkbox" name="rules_in_posting" value="1" {S_RULES_POSTING_ENABLED} />&nbsp;<span class="genmed">{L_RULES_IN_POSTING}</span><br />
	</td>
</tr>
<!-- END forum -->
<!-- ENDIF -->
<!-- BEGIN link -->
<tr><th class="cat" colspan="2">{L_LINK}</th></tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_LINK}</b></span><br /><span class="gensmall">{L_FORUM_LINK_EXPLAIN}</span></td>
	<td class="row2"><span class="genmed">&nbsp;<input name="link" type="text" class="post" value="{FORUM_LINK}" size="60" /></span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_LINK_INTERNAL}</b></span><span class="gensmall"><br />{L_FORUM_LINK_INTERNAL_EXPLAIN}</span></td>
	<td class="row2"><span class="genmed"><input name="link_internal" type="radio" value="1" {LINK_INTERNAL_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input name="link_internal" type="radio" value="0" {LINK_INTERNAL_NO} />&nbsp;{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><b>{L_FORUM_LINK_HIT_COUNT}</b></span><span class="gensmall"><br />{L_FORUM_LINK_HIT_COUNT_EXPLAIN}</span></td>
	<td class="row2"><span class="genmed"><input name="link_hit_count" type="radio" value="1" {LINK_COUNT_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input name="link_hit_count" type="radio" value="0" {LINK_COUNT_NO} />&nbsp;{L_NO}</span></td>
</tr>
<!-- END link -->
<!-- BEGIN forum_link -->
<tr>
	<td width="100%" colspan="5" style="padding: 0px;">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td class="cat" colspan="{AUTH_SPAN}" style="padding: 0px;">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
				<tr>
					<td align="left" width="100%"><span class="forumlink"><b>{L_AUTH}</b></span></td>
					<!-- BEGIN no_link -->
					<td align="right" nowrap="nowrap">
						<span class="genmed"><input type="hidden" name="preset_choice" value="0" />
							&nbsp;<b>{L_PRESET}:&nbsp;</b>
							<select name="forum_preset" onChange="this.form.preset_choice.value=1; this.form.submit();">{S_PRESET_OPT}</select>
						</span>
					</td>
					<!-- END no_link -->
				</tr>
				</table>
			</td>
		</tr>
		<!-- BEGIN auth -->
		<tr>
			<!-- BEGIN cell -->
			<td width="25%" class="{forum_link.auth.cell.COLOR}" align="center">
				<table width="100%" cellpadding="2" cellspacing="0" border="0">
				<tr>
					<td align="right" width="50%"><span class="genmed">{forum_link.auth.cell.L_AUTH}:</span></td>
					<td align="left" nowrap="nowrap"><select name="{forum_link.auth.cell.AUTH}">{forum_link.auth.cell.S_AUTH_OPT}</select></td>
				</tr>
				</table>
			</td>
			<!-- END cell -->
			<!-- BEGIN empty -->
			<td class="row3" colspan="{forum_link.auth.empty.SPAN}"><span class="genmed">&nbsp;</span></td>
			<!-- END empty -->
		</tr>
		<!-- END auth -->
		</table>
	</td>
</tr>
<!-- END forum_link -->
<tr>
	<td class="cat" align="center" colspan="5">{S_HIDDEN_FIELDS}
		<span class="cattitle">
			<input type="submit" name="update" value="{L_SUBMIT}" class="mainoption" />&nbsp;
			<input type="submit" name="cancel" value="{L_CANCEL}" class="liteoption" />&nbsp;
			<input type="submit" name="refresh" value="{L_REFRESH}" class="liteoption" />&nbsp;
		</span>
	</td>
</tr>
</table>
</form>