<h1>{L_CONFIGURATION_TITLE}</h1>

<p>{L_CONFIGURATION_EXPLAIN}</p>

<form id="configform" action="{S_CONFIG_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_GENERAL_SETTINGS}</th></tr>
<tr>
	<td class="row1"><strong>{L_AUTOLINK_FIRST}</strong></td>
	<td class="row2"><input type="radio" name="autolink_first" value="1" {AUTOLINK_FIRST_YES} />{L_YES}&nbsp; &nbsp;<input type="radio" name="autolink_first" value="0" {AUTOLINK_FIRST_NO} />{L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_FLOOD_INTERVAL}</strong><br /><span class="gensmall">{L_FLOOD_INTERVAL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="flood_interval" value="{FLOOD_INTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SEARCH_FLOOD_INTERVAL}</strong><br /><span class="gensmall">{L_SEARCH_FLOOD_INTERVAL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="search_flood_interval" value="{SEARCH_FLOOD_INTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_TOPICS_PER_PAGE}</strong></td>
	<td class="row2"><input class="post" type="text" name="topics_per_page" size="3" maxlength="4" value="{TOPICS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_POSTS_PER_PAGE}</strong></td>
	<td class="row2"><input class="post" type="text" name="posts_per_page" size="3" maxlength="4" value="{POSTS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_HOT_THRESHOLD}</strong></td>
	<td class="row2"><input class="post" type="text" name="hot_threshold" size="3" maxlength="4" value="{HOT_TOPIC}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_DEFAULT_STYLE}</strong></td>
	<td class="row2">{STYLE_SELECT}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_OVERRIDE_STYLE}</strong><br /><span class="gensmall">{L_OVERRIDE_STYLE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="override_user_style" value="1" {OVERRIDE_STYLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="override_user_style" value="0" {OVERRIDE_STYLE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_DEFAULT_LANGUAGE}</strong></td>
	<td class="row2">{LANG_SELECT}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_DATE_FORMAT}</strong></td>
	<td class="row2"><strong>{DEFAULT_DATEFORMAT}</strong></td>
</tr>
<tr>
	<td class="row1">
		<span class="gen"><strong>{L_TIME_MODE}</strong></span><br />
		<span class="gensmall">{L_TIME_MODE_TEXT}</span>
	</td>
	<td class="row2" nowrap="nowrap" valign="top">
		<!--
		<span class="gen">{L_TIME_MODE_AUTO}</span><br />
		<input type="radio" name="default_time_mode" value="6" {TIME_MODE_FULL_PC_CHECKED}/>
		<span class="gen">{L_TIME_MODE_FULL_PC}</span>&nbsp;&nbsp;<br />
		<input type="radio" name="default_time_mode" value="4" {TIME_MODE_SERVER_PC_CHECKED}/>
		<span class="gen">{L_TIME_MODE_SERVER_PC}</span>&nbsp;&nbsp;<br />
		<input type="radio" name="default_time_mode" value="3" {TIME_MODE_FULL_SERVER_CHECKED}/>
		<span class="gen">{L_TIME_MODE_FULL_SERVER}</span>
		<br /><br />
		-->
		<br />
		<span class="gen">&nbsp;&nbsp;{L_TIME_MODE_DST}:</span><input type="radio" name="default_time_mode" value="1" {TIME_MODE_MANUAL_DST_CHECKED}/><span class="gen">{L_YES}</span>&nbsp;<input type="radio" name="default_time_mode" value="0" {TIME_MODE_MANUAL_CHECKED}/><span class="gen">{L_NO}</span>&nbsp;<input type="radio" name="default_time_mode" value="2" {TIME_MODE_SERVER_SWITCH_CHECKED}/><span class="gen">{L_TIME_MODE_DST_SERVER}</span><br />
		<span class="gen">&nbsp;&nbsp;{L_TIME_MODE_DST_TIME_LAG}: </span><input type="text" name="dst_time_lag" value="{DST_TIME_LAG}" maxlength="3" size="3" class="post" /><span class="gen">{L_TIME_MODE_DST_MN}</span><br />
		<span class="gen">&nbsp;&nbsp;{L_TIME_MODE_TIMEZONE}: </span><span class="gensmall">{TIMEZONE_SELECT}</span></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ONLINE_TIME}</strong><br /><span class="gensmall">{L_ONLINE_TIME_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="online_time" value="{ONLINE_TIME}" /></td>
</tr>
<!--
<tr>
	<td class="row1"><strong>{L_ENABLE_GZIP}</strong></td>
	<td class="row2"><input type="radio" name="gzip_compress" value="1" {GZIP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gzip_compress" value="0" {GZIP_NO} /> {L_NO}</td>
</tr>
-->
<tr>
	<td class="row1"><strong>{L_ENABLE_PRUNE}</strong></td>
	<td class="row2"><input type="radio" name="prune_enable" value="1" {PRUNE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="prune_enable" value="0" {PRUNE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_PRUNE_SHOUTS}</strong><br /><span class="gensmall">{L_PRUNE_SHOUTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="6" maxlength="6" name="prune_shouts" value="{PRUNE_SHOUTS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_REPORT_FORUM}</strong><br /><span class="gensmall">{L_REPORT_FORUM_EXPLAIN}</span></td>
	<td class="row2" valign="top">{S_REPORT_FORUM}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_BIN_FORUM}</strong><br /><span class="gensmall">{L_BIN_FORUM_EXPLAIN}</span><br /></td>
	<td class="row2" valign="top">{S_BIN_FORUM}</td>
</tr>
<tr><th colspan="2">{L_HEADER_FOOTER}</th></tr>
<tr>
	<td class="row1"><strong>{L_HEADER_TABLE_SWITCH}</strong><br /><span class="gensmall">{L_HEADER_TABLE_SWITCH_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="switch_header_table" value="1" {HEADER_TBL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="switch_header_table" value="0" {HEADER_TBL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_HEADER_TABLE_TEXT}</strong></td>
	<td class="row2"><textarea name="header_table_text" rows="10" cols="35" style="width:300" class="post" >{HEADER_TBL_TXT}</textarea></td>
</tr>
<tr><th colspan="2">{L_PRIVATE_MESSAGING}</th></tr>
<tr>
	<td class="row1"><strong>{L_DISABLE_PRIVATE_MESSAGING}</strong></td>
	<td class="row2"><input type="radio" name="privmsg_disable" value="0" {S_PRIVMSG_ENABLED} />{L_ENABLED}&nbsp; &nbsp;<input type="radio" name="privmsg_disable" value="1" {S_PRIVMSG_DISABLED} />{L_DISABLED}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_INBOX_LIMIT}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_inbox_privmsgs" value="{INBOX_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SENTBOX_LIMIT}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_sentbox_privmsgs" value="{SENTBOX_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SAVEBOX_LIMIT}</strong></td>
	<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_savebox_privmsgs" value="{SAVEBOX_LIMIT}" /></td>
</tr>
<tr><th colspan="2">{L_ABILITIES_SETTINGS}</th></tr>
<tr>
	<td class="row1"><strong>{L_MAX_POLL_OPTIONS}</strong></td>
	<td class="row2"><input class="post" type="text" name="max_poll_options" size="4" maxlength="4" value="{MAX_POLL_OPTIONS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_HTML}</strong></td>
	<td class="row2"><input type="radio" name="allow_html" value="1" {HTML_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_html" value="0" {HTML_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOWED_TAGS}</strong><br /><span class="gensmall">{L_ALLOWED_TAGS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="30" maxlength="255" name="allow_html_tags" value="{HTML_TAGS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_BBCODE}</strong></td>
	<td class="row2"><input type="radio" name="allow_bbcode" value="1" {BBCODE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_bbcode" value="0" {BBCODE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_SMILIES}</strong></td>
	<td class="row2"><input type="radio" name="allow_smilies" value="1" {SMILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_smilies" value="0" {SMILE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMILIES_PATH}</strong><br /><span class="gensmall">{L_SMILIES_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="smilies_path" value="{SMILIES_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMILIE_TABLE_COLUMNS}</strong></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="2" name="smilie_columns" value="{SMILIE_COLUMNS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMILIE_TABLE_ROWS}</strong></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="2" name="smilie_rows" value="{SMILIE_ROWS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMILIE_WINDOW_COLUMNS}</strong></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="2" name="smilie_window_columns" value="{SMILIE_WINDOW_COLUMNS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMILIE_WINDOW_ROWS}</strong></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="3" name="smilie_window_rows" value="{SMILIE_WINDOW_ROWS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SMILIE_SINGLE_ROW}</strong></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="3" name="smilie_single_row" value="{SMILIE_SINGLE_ROW}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_SIG}</strong></td>
	<td class="row2"><input type="radio" name="allow_sig" value="1" {SIG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_sig" value="0" {SIG_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_SIG_LENGTH}</strong><br /><span class="gensmall">{L_MAX_SIG_LENGTH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="4" name="max_sig_chars" value="{SIG_SIZE}" /></td>
</tr>
<!--
<tr><th colspan="2">{L_SIG_TITLE}</th></tr>
-->
<tr>
	<td class="row1"><strong>{L_SIG_INPUT}</strong><br /><span class="gensmall">{L_SIG_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="255" name="sig_line" value="{SIG_DIVIDERS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_NAME_CHANGE}</strong></td>
	<td class="row2"><input type="radio" name="allow_namechange" value="1" {NAMECHANGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_namechange" value="0" {NAMECHANGE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_HIDDE_LAST_LOGON}</strong><br /><span class="gensmall">{L_HIDDE_LAST_LOGON_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="hidde_last_logon" value="1" {HIDDE_LAST_LOGON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="hidde_last_logon" value="0" {HIDDE_LAST_LOGON_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_BLUECARD_LIMIT_2}</strong><br /><span class="gensmall">{L_BLUECARD_LIMIT_2_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="bluecard_limit_2" value="{BLUECARD_LIMIT_2}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_BLUECARD_LIMIT}</strong><br /><span class="gensmall">{L_BLUECARD_LIMIT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="bluecard_limit" value="{BLUECARD_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_USER_BANCARD}</strong><br /><span class="gensmall">{L_MAX_USER_BANCARD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="max_user_bancard" value="{MAX_USER_BANCARD}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_GENDER_REQUIRED}</strong></td>
	<td class="row2"><input type="radio" name="gender_required" value="1"{GENDER_REQUIRED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gender_required" value="0"{GENDER_REQUIRED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_BIRTHDAY_REQUIRED}</strong><br /></td>
	<td class="row2"><input type="radio" name="birthday_required" value="1" {BIRTHDAY_REQUIRED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_required" value="0" {BIRTHDAY_REQUIRED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ENABLE_BIRTHDAY_GREETING}</strong><br /><span class="gensmall">{L_BIRTHDAY_GREETING_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="birthday_greeting" value="1" {BIRTHDAY_GREETING_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_greeting" value="0" {BIRTHDAY_GREETING_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_USER_AGE}</strong></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="max_user_age" value="{MAX_USER_AGE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_MIN_USER_AGE}</strong></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="min_user_age" value="{MIN_USER_AGE}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_BIRTHDAY_LOOKFORWARD}</strong><br /><span class="gensmall">{L_BIRTHDAY_LOOKFORWARD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="birthday_check_day" value="{BIRTHDAY_LOOKFORWARD}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_LINK_BOOKMARKS}</strong><br /><span class="gensmall">{L_MAX_LINK_BOOKMARKS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="3" name="max_link_bookmarks" value="{LINK_BOOKMARKS}" /></td>
</tr>

<tr><th colspan="2">{L_AVATAR_SETTINGS}</th></tr>
<tr>
	<td class="row1"><strong>{L_DEFAULT_AVATAR}</strong><br /><span class="gensmall">{L_DEFAULT_AVATAR_EXPLAIN}</span></td>
	<td class="row2">
		<table>
			<tr>
				<td><input type="radio" name="default_avatar_set" value="0" {DEFAULT_AVATAR_GUESTS} /> {L_DEFAULT_AVATAR_GUESTS}</td>
				<td><input class="post" type="text" name="default_avatar_guests_url" maxlength="255" value="{DEFAULT_AVATAR_GUESTS_URL}" /></td>
			</tr>
			<tr>
				<td><input type="radio" name="default_avatar_set" value="1" {DEFAULT_AVATAR_USERS} /> {L_DEFAULT_AVATAR_USERS}</td>
				<td><input class="post" type="text" name="default_avatar_users_url" maxlength="255" value="{DEFAULT_AVATAR_USERS_URL}" /></td>
			</tr>
			<tr>
				<td colspan="2"><input type="radio" name="default_avatar_set" value="2" {DEFAULT_AVATAR_BOTH} /> {L_DEFAULT_AVATAR_BOTH}</td>
				<td colspan="2"><input type="radio" name="default_avatar_set" value="3" {DEFAULT_AVATAR_NONE} /> {L_DEFAULT_AVATAR_NONE}</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_LOCAL}</strong></td>
	<td class="row2"><input type="radio" name="allow_avatar_local" value="1" {AVATARS_LOCAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_local" value="0" {AVATARS_LOCAL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_REMOTE}</strong><br /><span class="gensmall">{L_ALLOW_REMOTE_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="allow_avatar_remote" value="1" {AVATARS_REMOTE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_remote" value="0" {AVATARS_REMOTE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_UPLOAD}</strong></td>
	<td class="row2"><input type="radio" name="allow_avatar_upload" value="1" {AVATARS_UPLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_upload" value="0" {AVATARS_UPLOAD_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_ALLOW_GENERATOR}</strong></td>
	<td class="row2"><input type="radio" name="allow_avatar_generator" value="1" {AVATAR_GENERATOR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_avatar_generator" value="0" {AVATAR_GENERATOR_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_FILESIZE}</strong><br /><span class="gensmall">{L_MAX_FILESIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="4" maxlength="10" name="avatar_filesize" value="{AVATAR_FILESIZE}" /> Bytes</td>
</tr>
<tr>
	<td class="row1"><strong>{L_MAX_AVATAR_SIZE}</strong><br /><span class="gensmall">{L_MAX_AVATAR_SIZE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="avatar_max_height" value="{AVATAR_MAX_HEIGHT}" /> x <input class="post" type="text" size="3" maxlength="4" name="avatar_max_width" value="{AVATAR_MAX_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_AVATAR_STORAGE_PATH}</strong><br /><span class="gensmall">{L_AVATAR_STORAGE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="avatar_path" value="{AVATAR_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_AVATAR_GENERATOR_TEMPLATE_PATH}</strong><br /><span class="gensmall">{L_AVATAR_GENERATOR_TEMPLATE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="avatar_generator_template_path" value="{AVATAR_GENERATOR_TEMPLATE_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_AVATAR_GALLERY_PATH}</strong><br /><span class="gensmall">{L_AVATAR_GALLERY_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="avatar_gallery_path" value="{AVATAR_GALLERY_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_ENABLE_GRAVATARS}</strong></td>
	<td class="row2"><input type="radio" name="enable_gravatars" value="1" {ENABLE_GRAVATARS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_gravatars" value="0" {ENABLE_GRAVATARS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_GRAVATAR_RATING}</strong><br /><span class="gensmall">{L_GRAVATAR_RATING_EXPLAIN}</span></td>
	<td class="row2">{GRAVATAR_RATING}</td>
</tr>
<tr>
	<td class="row1"><strong>{L_GRAVATAR_DEFAULT_IMAGE}</strong><br /><span class="gensmall">{L_GRAVATAR_DEFAULT_IMAGE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="gravatar_default_image" value="{GRAVATAR_DEFAULT_IMAGE}" /></td>
</tr>
<tr><th colspan="2">{L_SHOUTBOX_CONFIG}</th></tr>
<tr>
	<td class="row1"><strong>{L_SHOUTBOX_REFRESHTIME}</strong><br /><span class="gensmall">{L_SHOUTBOX_REFRESH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="shoutbox_refreshtime" size="5" maxlength="4" value="{SHOUTBOX_REFRESHTIME}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_DISPLAYED_SHOUTS}</strong><br /><span class="gensmall">{L_DISPLAYED_SHOUTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="display_shouts" size="3" maxlength="4" value="{DISPLAYED_SHOUTS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_STORED_SHOUTS}</strong><br /><span class="gensmall">{L_STORED_SHOUTS_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="stored_shouts" size="3" maxlength="4" value="{STORED_SHOUTS}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_SHOUTBOX_FLOOD}</strong><br /><span class="gensmall">{L_SHOUTBOX_FLOOD_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" name="shoutbox_floodinterval" size="3" maxlength="4" value="{SHOUTBOX_FLOODINTERVAL}" /></td>
</tr>
<tr>
	<td class="row1"><strong>{L_GUEST_ALLOWED}</strong></td>
	<td class="row2"><input type="radio" name="shout_allow_guest" value="1" {SHOUT_GUEST_YES} />{L_YES}&nbsp;&nbsp;<input type="radio" name="shout_allow_guest" value="2" {SHOUT_GUEST_READONLY} />{L_SHOUT_GUEST_READONLY}&nbsp;&nbsp;<input type="radio" name="shout_allow_guest" value="0" {SHOUT_GUEST_NO} />{L_NO}</td>
</tr>
{BB_USAGE_STATS_ADMIN_TEMPLATE}
<tr><td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>
<br clear="all" />