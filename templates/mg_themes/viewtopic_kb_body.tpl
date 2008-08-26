<script type="text/javascript">
<!--
function post_time_edit(topic_id, post_id)
{
	window.open("edit_post_time.php?t="+topic_id+"&p="+post_id, '_postedittime', 'height=150,width=500,resizable=no,scrollbars=no');
}
//-->
</script>
<!-- INCLUDE viewtopic_nav.tpl -->

<!--
<br />
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="bottom">
		<a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}" /></a>&nbsp;<a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}" /></a>
	</td>
	<td align="right" valign="bottom">
		<span class="gen">{PAGE_NUMBER}<br /></span>
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>
-->
<script type="text/javascript">
<!--
function open_postreview(ref)
{
	height = screen.height / 2.23;
	width = screen.width / 2;
	window.open(ref,'_phpbbpostreview','height=' + height + ',width=' + width + ',resizable=yes,scrollbars=yes');
	return;
}
//-->
</script>
{IMG_THL}{IMG_THC}<span class="forumlink">{TOPIC_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_ARTICLE}</th></tr>
<!-- BEGIN postrow -->
<tr>
	<td class="row-post-buttons post-buttons" width="100%" colspan="2">
		<div class="post-buttons-top post-buttons">
			{postrow.ARROWS}
			{postrow.QUOTE_IMG}
			<a href="{postrow.DOWNLOAD_POST}" class="genmed"><img src="{postrow.DOWNLOAD_IMG}" alt="{L_DOWNLOAD_POST}" title="{L_DOWNLOAD_POST}"></a>
			{postrow.EDIT_IMG}&nbsp;{postrow.DELETE_IMG}&nbsp;{postrow.IP_IMG}&nbsp;
		</div>
		<div class="post-subject {postrow.UNREAD_COLOR}"><a href="{postrow.U_MINI_POST}"><img src="{postrow.MINI_POST_IMG}" alt="{postrow.L_MINI_POST_ALT}" title="{postrow.L_MINI_POST_ALT}" /></a> {postrow.POST_SUBJECT}&nbsp;</div>
	</td>
</tr>
<tr>
	<!-- <td class="row-post-author"></td> -->
	<td class="row-post" width="100%" height="100%" colspan="2">
		&nbsp;
		<div class="post-text-container">
		<div class="post-text">
			{postrow.MESSAGE}<br />
			{postrow.ATTACHMENTS}
		</div>
		</div>
		<br /><br /><br />
		<div style="margin-bottom: 2px;clear: both;display: block;">&nbsp;</div>
		<?php
			global $board_config;
			if ($board_config['edit_notes'] == 1)
			{
		?>
		<!-- IF postrow.NOTES_COUNT -->
		<div class="post-notes">
		<!-- BEGIN notes -->
		<div class="post-note">
			<!-- IF notes.U_DELETE -->
			<div class="post-note-delete">[<a href="{notes.U_DELETE}">{L_DELETE_NOTE}</a>]</div>
			<!-- ENDIF -->
			{L_EDITED_BY} {postrow.notes.POSTER_NAME}, {postrow.notes.TIME}: {postrow.notes.TEXT}
		</div>
		<!-- END notes -->
		</div>
		<!-- ENDIF -->
		<!-- IF postrow.EDITED_MESSAGE -->
		<div class="post-notes"><div class="post-note"><span class="gensmall">{postrow.EDITED_MESSAGE}&nbsp;</span></div></div>
		<!-- ENDIF -->
		<?php
		}
		?>
	</td>
</tr>
<tr>
	<td class="row-post-buttons post-buttons" width="100%" colspan="2">
		<div style="float:right;">{postrow.POSTER_ONLINE_STATUS_IMG}&nbsp;{postrow.PROFILE_IMG}&nbsp;{postrow.PM_IMG}&nbsp;{postrow.EMAIL_IMG}&nbsp;</div>
		<span class="gensmall">&nbsp;{postrow.POSTER_NAME}&nbsp;[&nbsp;{postrow.POST_DATE}&nbsp;]</span>
	</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- BEGIN switch_viewtopic_banner -->
<tr>
	<td align="center" class="row-post-author">
		<span class="post-name"><a href="#" style="font-weight:bold;text-decoration:none;">Sponsor</a></span><br />
		<img src="images/ranks/rank_sponsor.png" alt="" /><br />
		<img src="images/avatars/default_avatars/sponsor.gif" alt="" />
	</td>
	<td class="row-post-ads" align="center"><div class="center-block-text">{VIEWTOPIC_BANNER_CODE}</div></td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END switch_viewtopic_banner -->
<!-- BEGIN switch_first_post -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{TOPIC_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2">{L_COMMENTS}</th></tr>
<!-- END switch_first_post -->
<!-- END postrow -->
<tr>
	<td class="cat" colspan="2">
		<form method="post" action="{S_POST_DAYS_ACTION}" style="display: inline;">
			<center>
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td valign="middle" nowrap="nowrap"><span class="genmed">{L_DISPLAY_POSTS}: </span></td>
					<td valign="middle"> {S_SELECT_POST_DAYS} {S_SELECT_POST_ORDER} <input type="submit" value="{L_GO}" class="liteoption jumpbox" name="submit" /></td>
				</tr>
			</table>
			</center>
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN switch_topic_useful -->
<a id="ratingblock"></a>
<div id="rate_block_h" style="display: none;">
{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MAXIMISE}" onclick="javascript:ShowHide('rate_block','rate_block_h','rate_block');" alt="{L_SHOW}" /><span class="forumlink">{L_TOPIC_RATING}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1g row-center">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>

<div id="rate_block">
{IMG_THL}{IMG_THC}<img style="{SHOW_HIDE_PADDING}float:right;cursor:pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('rate_block','rate_block_h','rate_block');" alt="{L_HIDE}" /><span class="forumlink">{L_TOPIC_RATING}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
{RATING_VIEWTOPIC}
<!-- BEGIN social_bookmarks -->
<tr><th colspan="4">{L_SHARE_TOPIC}</th></tr>
<!-- INCLUDE social_bookmarks.tpl -->
<!-- END social_bookmarks -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'rate_block';
if(GetCookie(tmp) == '2')
{
	ShowHide('rate_block', 'rate_block_h', 'rate_block');
}
//-->
</script>
<!-- END switch_topic_useful -->

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top">
		<a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" alt="{L_POST_NEW_TOPIC}" title="{L_POST_NEW_TOPIC}"/></a>&nbsp;<a href="{U_POST_REPLY_TOPIC}"><img src="{REPLY_IMG}" alt="{L_POST_REPLY_TOPIC}" title="{L_POST_REPLY_TOPIC}"/></a>
	</td>
	<td align="right" valign="top">
		<span class="gen">{PAGE_NUMBER}</span><br />
		<span class="pagination">{PAGINATION}</span>
		<!-- BEGIN extended_pagination -->
		<br /><form method="post" action="{U_VIEW_TOPIC}" style="display:inline;"><span class="gen">{L_GO_TO_PAGE_NUMBER}&nbsp;<input type="text" name="page_number" value="" size="3" class="post">&nbsp;&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" /></span></form>
		<!-- END extended_pagination -->
	</td>
</tr>
</table>
<div class="forumline nav-div">
	<p class="nav-header"><a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_CAT_DESC}</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}&nbsp;|&nbsp;{S_TIMEZONE}</div>
		<a href="{U_VIEW_OLDER_TOPIC}"><img src="{IMG_LEFT}" alt="{L_VIEW_PREVIOUS_TOPIC}" title="{L_VIEW_PREVIOUS_TOPIC}" /></a>
		{S_WATCH_TOPIC_IMG}
		<a href="{DOWNLOAD_TOPIC}" title="{L_DOWNLOAD_TOPIC}"><img src="{IMG_FLOPPY}" alt="{L_DOWNLOAD_TOPIC}" title="{L_DOWNLOAD_TOPIC}" /></a>
		<!-- BEGIN switch_user_logged_in -->
		<a href="{U_BOOKMARK_ACTION}"><img src="{IMG_BOOKMARK}" alt="{L_BOOKMARK_ACTION}" title="{L_BOOKMARK_ACTION}" /></a>
		<!-- END switch_user_logged_in -->
		<a href="{U_PRINT}" title="{L_PRINT}"><img src="{IMG_PRINT}" alt="{L_PRINT}" title="{L_PRINT}" /></a>
		<a href="{U_TELL}" title="{L_TELL}"><img src="{IMG_EMAIL}" alt="{L_TELL}" title="{L_TELL}" /></a>
		<a href="{U_VIEW_NEWER_TOPIC}"><img src="{IMG_RIGHT}" alt="{L_VIEW_NEXT_TOPIC}" title="{L_VIEW_NEXT_TOPIC}" /></a>
	</div>
</div>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top" class="gensmall" width="100%"><span class="gensmall"><b><br/>{TOTAL_USERS_ONLINE}<br/>{LOGGED_IN_USER_LIST}</b></span><br /><br /></td>
</tr>
</table>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top" class="gensmall" width="40%">&nbsp;</td>
	<td align="right" valign="top">{S_TOPIC_ADMIN}<br /><br /><br /></td>
</tr>
</table>