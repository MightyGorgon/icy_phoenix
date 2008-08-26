<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_pages_permissions.png" alt="{L_CONFIGURATION_TITLE}" title="{L_CONFIGURATION_TITLE}" /></td>
	<td class="row1" valign="top"><h1>{L_CONFIGURATION_TITLE}</h1><span class="genmed">{L_CONFIGURATION_EXPLAIN}</span></td>
</tr>
</table>

<form action="{S_CONFIG_ACTION}" method="post" name="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" style="padding:0px" valign="top">
		<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th align="center">{L_PAGE}</th>
			<th align="center">{L_PERMISSION}</th>
			<th align="center">{L_WIDE_BLOCKS}</th>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_PORTAL}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_PORTAL}</td>
			<td class="row1 row-center" style="background:none;">&nbsp;
				<!--
				<input type="radio" name="wide_blocks_portal" value="1" {WIDE_BLOCKS_PORTAL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_portal" value="0" {WIDE_BLOCKS_PORTAL_NO} /> {L_NO}
				-->
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_FORUM}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_FORUM}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_forum" value="1" {WIDE_BLOCKS_FORUM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_forum" value="0" {WIDE_BLOCKS_FORUM_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_VIEWF}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_VIEWF}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_viewf" value="1" {WIDE_BLOCKS_VIEWF_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_viewf" value="0" {WIDE_BLOCKS_VIEWF_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_VIEWT}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_VIEWT}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_viewt" value="1" {WIDE_BLOCKS_VIEWT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_viewt" value="0" {WIDE_BLOCKS_VIEWT_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_FAQ}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_FAQ}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_faq" value="1" {WIDE_BLOCKS_FAQ_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_faq" value="0" {WIDE_BLOCKS_FAQ_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_MEMBERLIST}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_MEMBERLIST}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_memberlist" value="1" {WIDE_BLOCKS_MEMBERLIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_memberlist" value="0" {WIDE_BLOCKS_MEMBERLIST_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_GROUP_CP}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_GROUP_CP}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_group_cp" value="1" {WIDE_BLOCKS_GROUP_CP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_group_cp" value="0" {WIDE_BLOCKS_GROUP_CP_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_PROFILE}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_PROFILE}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_profile" value="1" {WIDE_BLOCKS_PROFILE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_profile" value="0" {WIDE_BLOCKS_PROFILE_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_SEARCH}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_SEARCH}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_search" value="1" {WIDE_BLOCKS_SEARCH_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_search" value="0" {WIDE_BLOCKS_SEARCH_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_ALBUM}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_ALBUM}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_album" value="1" {WIDE_BLOCKS_ALBUM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_album" value="0" {WIDE_BLOCKS_ALBUM_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_LINKS}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_LINKS}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_links" value="1" {WIDE_BLOCKS_LINKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_links" value="0" {WIDE_BLOCKS_LINKS_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_CALENDAR}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_CALENDAR}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_calendar" value="1" {WIDE_BLOCKS_CALENDAR_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_calendar" value="0" {WIDE_BLOCKS_CALENDAR_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_ATTACHMENTS}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_ATTACHMENTS}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_attachments" value="1" {WIDE_BLOCKS_ATTACHMENTS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_attachments" value="0" {WIDE_BLOCKS_ATTACHMENTS_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_DOWNLOAD}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_DOWNLOAD}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_download" value="1" {WIDE_BLOCKS_DOWNLOAD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_download" value="0" {WIDE_BLOCKS_DOWNLOAD_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_PIC_UPLOAD}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_PIC_UPLOAD}</td>
			<td class="row1 row-center" style="background:none;">&nbsp;</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_KB}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_KB}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_kb" value="1" {WIDE_BLOCKS_KB_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_kb" value="0" {WIDE_BLOCKS_KB_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_RANKS}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_RANKS}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_ranks" value="1" {WIDE_BLOCKS_RANKS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_ranks" value="0" {WIDE_BLOCKS_RANKS_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_STATISTICS}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_STATISTICS}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_statistics" value="1" {WIDE_BLOCKS_STATISTICS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_statistics" value="0" {WIDE_BLOCKS_STATISTICS_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_RECENT}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_RECENT}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_recent" value="1" {WIDE_BLOCKS_RECENT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_recent" value="0" {WIDE_BLOCKS_RECENT_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_REFERRERS}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_REFERRERS}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_referrers" value="1" {WIDE_BLOCKS_REFERRERS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_referrers" value="0" {WIDE_BLOCKS_REFERRERS_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_RULES}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_RULES}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_rules" value="1" {WIDE_BLOCKS_RULES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_rules" value="0" {WIDE_BLOCKS_RULES_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_SITE_HIST}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_SITE_HIST}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_site_hist" value="1" {WIDE_BLOCKS_SITE_HIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_site_hist" value="0" {WIDE_BLOCKS_SITE_HIST_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_SHOUTBOX}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_SHOUTBOX}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_shoutbox" value="1" {WIDE_BLOCKS_SHOUTBOX_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_shoutbox" value="0" {WIDE_BLOCKS_SHOUTBOX_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_VIEWONLINE}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_VIEWONLINE}</td>
			<td class="row2 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_viewonline" value="1" {WIDE_BLOCKS_VIEWONLINE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_viewonline" value="0" {WIDE_BLOCKS_VIEWONLINE_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_CONTACT_US}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_CONTACT_US}</td>
			<td class="row1 row-center" style="background:none;">
				<input type="radio" name="wide_blocks_contact_us" value="1" {WIDE_BLOCKS_CONTACT_US_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_contact_us" value="0" {WIDE_BLOCKS_CONTACT_US_NO} /> {L_NO}
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_AJAX_CHAT}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_AJAX_CHAT}</td>
			<td class="row2 row-center" style="background:none;">
				&nbsp;
				<!--
				<input type="radio" name="wide_blocks_ajax_chat" value="1" {WIDE_BLOCKS_AJAX_CHAT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_ajax_chat" value="0" {WIDE_BLOCKS_AJAX_CHAT_NO} /> {L_NO}
				-->
			</td>
		</tr>
		<tr class="row1 row1h" style="background-image:none;">
			<td class="row1" style="background:none;">{L_AUTH_VIEW_AJAX_CHAT_ARCHIVE}</td>
			<td class="row1 row-center" style="background:none;">{S_AUTH_VIEW_AJAX_CHAT_ARCHIVE}</td>
			<td class="row1 row-center" style="background:none;">
				&nbsp;
				<!--
				<input type="radio" name="wide_blocks_ajax_chat_archive" value="1" {WIDE_BLOCKS_AJAX_CHAT_ARCHIVE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_ajax_chat_archive" value="0" {WIDE_BLOCKS_AJAX_CHAT_ARCHIVE_NO} /> {L_NO}
				-->
			</td>
		</tr>
		<tr class="row2 row1h" style="background-image:none;">
			<td class="row2" style="background:none;">{L_AUTH_VIEW_CUSTOM_PAGES}</td>
			<td class="row2 row-center" style="background:none;">{S_AUTH_VIEW_CUSTOM_PAGES}</td>
			<td class="row2 row-center" style="background:none;">&nbsp;
				<!--
				<input type="radio" name="wide_blocks_custom_pages" value="1" {WIDE_BLOCKS_CUSTOM_PAGES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="wide_blocks_custom_pages" value="0" {WIDE_BLOCKS_CUSTOM_PAGES_NO} /> {L_NO}
				-->
			</td>
		</tr>

		</table>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" align="center">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
		<!--
		&nbsp;&nbsp;
		<input type="reset" name="reset" class="liteoption" value="{L_RESET}" />
		-->
	</td>
</tr>
</table>
</form>