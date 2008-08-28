<script type="text/javascript">
<!--
function toggle_check_all()
{
	for(var i = 0; i < document.post_ids.elements.length; i++)
	{
		var checkbox_element = document.post_ids.elements[i];
		if( (checkbox_element.name != 'check_all_box') && (checkbox_element.type == 'checkbox') )
		{
			checkbox_element.checked = document.post_ids.check_all_box.checked;
		}
	}
}
-->
</script>

<form method="post" name="post_ids" action="{S_SPLIT_ACTION}">
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_CAT_DESC}
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}
{IMG_THL}{IMG_THC}<span class="forumlink">{L_SPLIT_TOPIC}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="3"><span class="gensmall">{L_SPLIT_TOPIC_EXPLAIN}</span></th></tr>
<tr>
	<td class="row1" nowrap="nowrap"><span class="gen">{L_SPLIT_SUBJECT}</span></td>
	<td class="row2" colspan="2"><input class="post" type="text" size="35" style="width: 350px" maxlength="60" name="subject" /></td>
</tr>
<tr>
	<td class="row1" nowrap="nowrap"><span class="gen">{L_SPLIT_FORUM}</span></td>
	<td class="row2" colspan="2">{S_FORUM_SELECT}</td>
</tr>
<tr><td class="cat" colspan="3"><input class="liteoption" type="submit" name="split_type_all" value="{L_SPLIT_POSTS}" />&nbsp;&nbsp;<input class="liteoption" type="submit" name="split_type_beyond" value="{L_SPLIT_AFTER}" /></td></tr>
<tr><td class="spaceRow" colspan="3"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<th>{L_AUTHOR}</th>
	<th>{L_MESSAGE}</th>
	<th nowrap="nowrap"><input type="checkbox" name="check_all_box" onclick="toggle_check_all()" /></th>
</tr>
<!-- BEGIN postrow -->
<tr>
	<td class="row-post-author"><span class="post-name"><a name="p{postrow.POST_ID}"></a>{postrow.U_PROFILE_COL}</span></td>
	<td width="100%" class="row-post">
		<span class="gensmall"><img src="{MINIPOST_IMG}" alt="{L_POST}" title="{L_POST}" />&nbsp;{L_POSTED}:&nbsp;{postrow.POST_DATE}</span><br />
		<div class="post-subject">{postrow.POST_SUBJECT}&nbsp;</div>
		<div class="post-text">{postrow.MESSAGE}</div>
	</td>
	<td width="5%" class="row1g">&nbsp;{postrow.S_SPLIT_CHECKBOX}&nbsp;</td>
</tr>
<tr><td class="spaceRow" colspan="3"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END postrow -->
<tr>
	<td class="cat" colspan="3">
		{S_HIDDEN_FIELDS}
		<input class="liteoption" type="submit" name="split_type_all" value="{L_SPLIT_POSTS}" />&nbsp;&nbsp;
		<input class="liteoption" type="submit" name="split_type_beyond" value="{L_SPLIT_AFTER}" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="empty-table" width="100%" align="center" cellspacing="0">
<tr><td align="right" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td></tr>
</table>