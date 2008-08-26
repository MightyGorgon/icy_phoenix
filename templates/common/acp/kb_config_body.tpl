<h1>{L_CONFIGURATION_TITLE}</h1>
<p>{L_CONFIGURATION_EXPLAIN}</p>

<form action="{S_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_CONFIGURATION_TITLE}</th></tr>
<tr>
	<td class="row1" width="50%"><strong>{L_NEW_NAME}</strong><br /><span class="gensmall">{L_NEW_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="allow_new" value="1" {S_NEW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_new" value="0" {S_NEW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_NOTIFY_NAME}</strong><br /><span class="gensmall">{L_NOTIFY_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="notify" value="0" {S_NOTIFY_NONE} />{L_NONE}&nbsp; &nbsp;<input type="radio" name="notify" value="2" {S_NOTIFY_EMAIL} />{L_EMAIL}&nbsp; &nbsp;<input type="radio" name="notify" value="1" {S_NOTIFY_PM} />{L_PM}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_ADMIN_ID_NAME}</strong><br /><span class="gensmall">{L_ADMIN_ID_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input class="post" type="text" name="admin_id" value="{ADMIN_ID}" size="5" maxlength="4" /></td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_HEADER_BANNER}</strong><br /><span class="gensmall">{L_HEADER_BANNER_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="header_banner" value="1" {S_HEADER_BANNER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="header_banner" value="0" {S_HEADER_BANNER_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_STATS_LIST}</strong><br /><span class="gensmall">{L_STATS_LIST_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="stats_list" value="1" {S_STATS_LIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="stats_list" value="0" {S_STATS_LIST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_ARTICLE_PAG}</strong><br /><span class="gensmall">{L_ARTICLE_PAG_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input class="post" type="text" name="art_pagination" value="{ARTICLE_PAG}" size="5" maxlength="4" /></td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_COMMENTS_PAG}</strong><br /><span class="gensmall">{L_COMMENTS_PAG_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input class="post" type="text" name="comments_pagination" value="{COMMENTS_PAG}" size="5" maxlength="4" /></td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_NEWS_SORT}</strong></td>
	<td class="row2" width="50%">{NEWS_SORT}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_NEWS_SORT_PAR}</strong></td>
	<td class="row2" width="50%">{NEWS_SORT_PAR} </td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_WYSIWYG}</strong><br /><span class="gensmall">{L_WYSIWYG_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="wysiwyg" value="1" {S_WYSIWYG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wysiwyg" value="0" {S_WYSIWYG_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_WYSIWYG_PATH}</strong><br /><span class="gensmall">{L_WYSIWYG_PATH_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input text="text" name="wysiwyg_path" value="{WYSIWYG_PATH}" size="20" maxlength="50" /></td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_ALLOW_HTML}</strong><br /><span class="gensmall">{L_ALLOW_HTML_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input class="radio" type="radio" name="allow_html" value="1" {S_ALLOW_HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0" {S_ALLOW_HTML_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_ALLOWED_HTML_TAGS}</strong><br /><span class="gensmall">{L_ALLOWED_HTML_TAGS_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input text="text" name="allowed_html_tags" value="{ALLOWED_HTML_TAGS}" size="15" maxlength="50" /></td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_ALLOW_BBCODE}</strong><br /><span class="gensmall">{L_ALLOW_BBCODE_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="allow_bbcode" value="1" {S_ALLOW_BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0" {S_ALLOW_BBCODE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_ALLOW_SMILIES}</strong><br /><span class="gensmall">{L_ALLOW_SMILIES_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="allow_smilies" value="1" {S_ALLOW_SMILIES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0" {S_ALLOW_SMILIES_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_FORMATTING_FIXUP}</strong><br /><span class="gensmall">{L_FORMATTING_FIXUP_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="formatting_fixup" value="1" {S_FORMATTING_FIXUP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="formatting_fixup" value="0" {S_FORMATTING_FIXUP_NO} /> {L_NO}</td>
</tr>
<tr><th colspan="2">{L_COMMENTS_INFO}</th></tr>
<tr>
	<td class="row1" width="50%"><strong>{L_USE_COMMENTS}</strong><br /><span class="gensmall">{L_USE_COMMENTS_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="use_comments" value="1" {S_USE_COMMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_comments" value="0" {S_USE_COMMENTS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_COMMENTS_SHOW}</strong><br /><span class="gensmall">{L_COMMENTS_SHOW_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="comments_show" value="1" {S_COMMENTS_SHOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="comments_show" value="0" {S_COMMENTS_SHOW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_BUMP_POST}</strong><br /><span class="gensmall">{L_BUMP_POST_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="bump_post" value="1" {S_BUMP_POST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="bump_post" value="0" {S_BUMP_POST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_DEL_TOPIC}</strong><br /><span class="gensmall">{L_DEL_TOPIC_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="del_topic" value="1" {S_DEL_TOPIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="del_topic" value="0" {S_DEL_TOPIC_NO} /> {L_NO}</td>
</tr>
<tr><th colspan="2">{L_RATINGS_INFO}</th></tr>

<tr>
	<td class="row1" width="50%"><strong>{L_USE_RATINGS}</strong><br /><span class="gensmall">{L_USE_RATINGS_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="use_ratings" value="1" {S_USE_RATINGS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_ratings" value="0" {S_USE_RATINGS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_VOTES_CHECK_IP}</strong><br /><span class="gensmall">{L_VOTES_CHECK_IP_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="votes_check_ip" value="1" {S_VOTES_CHECK_IP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="votes_check_ip" value="0" {S_VOTES_CHECK_IP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_VOTES_CHECK_USERID}</strong><br /><span class="gensmall">{L_VOTES_CHECK_USERID_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="votes_check_userid" value="1" {S_VOTES_CHECK_USERID_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="votes_check_userid" value="0" {S_VOTES_CHECK_USERID_NO} /> {L_NO}</td>
</tr>
<tr><th colspan="2">{L_PRE_TEXT_NAME}</th></tr>
<tr>
	<td class="row1" width="50%"><strong>{L_PRE_TEXT_NAME}</strong><br /><span class="gensmall">{L_PRE_TEXT_EXPLAIN}</span></td>
	<td class="row2" width="50%"><input type="radio" name="show_pretext" value="1" {S_SHOW_PRETEXT} /> {L_SHOW}&nbsp;&nbsp;<input type="radio" name="show_pretext" value="0" {S_HIDE_PRETEXT} /> {L_HIDE}</td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_PRE_TEXT_HEADER}</strong></td>
	<td class="row2" width="50%"><input text="text" name="pt_header" value="{L_PT_HEADER}" size="40" maxlength="100" /></td>
</tr>
<tr>
	<td class="row1" width="50%"><strong>{L_PRE_TEXT_BODY}</strong></td>
	<td class="row2" width="50%"><textarea name="pt_body" cols="40" rows="5">{L_PT_BODY}</textarea></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>
</form>
<br clear="all" />