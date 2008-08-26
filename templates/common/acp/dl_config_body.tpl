<form action="{S_CONFIG_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="3">{L_CONFIGURATION_TITLE}</th></tr>
<tr><td class="row3 row-center" colspan="3"><span class="gensmall">{L_CONFIGURATION_EXPLAIN}</span></td></tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_PATH}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_PATH_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" size="20" maxlength="255" name="download_dir" value="{DL_PATH}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_USER_DOWNLOAD_LIMIT_FLAG}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_USER_DOWNLOAD_LIMIT_FLAG_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="user_download_limit_flag" value="1" {USER_DOWNLOAD_LIMIT_FLAG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_download_limit_flag" value="0" {USER_DOWNLOAD_LIMIT_FLAG_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_USER_DOWNLOAD_LIMIT}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_USER_DOWNLOAD_LIMIT_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="user_download_limit" size="5" maxlength="5" value="{USER_DOWNLOAD_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_METHOD}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_METHOD_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="dl_method" value="1" {METHOD_OLD} /> {L_OLD}&nbsp;&nbsp;<input type="radio" name="dl_method" value="2" {METHOD_NEW} /> {L_NEW}&nbsp;&nbsp;<strong>&raquo;</strong>&nbsp;&nbsp;<input type="checkbox" name="dl_direct" value="1" {DL_DIRECT} /> {L_DL_DIRECT}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_METHOD_QUOTA}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_METHOD_QUOTA_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%">
		<input type="text" class="post" size="10" maxlength="20" name="dl_method_quota" value="{DL_METHOD_QUOTA}" />&nbsp;<br />
		<input type="radio" name="m_quota" value="byte" {M_QUOTA1} />&nbsp;{L_BYTES}&nbsp;
		<input type="radio" name="m_quota" value="kb" {M_QUOTA2} />&nbsp;{L_KB}&nbsp;
		<input type="radio" name="m_quota" value="mb" {M_QUOTA3} />&nbsp;{L_MB}&nbsp;
		<input type="radio" name="m_quota" value="gb" {M_QUOTA4} />&nbsp;{L_GB}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_ICON_FREE_FOR_REG}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_ICON_FREE_FOR_REG_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="icon_free_for_reg" value="1" {ICON_FREE_FOR_REG_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="icon_free_for_reg" value="0" {ICON_FREE_FOR_REG_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_NEW_TIME}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_NEW_TIME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="dl_new_time" size="3" maxlength="4" value="{DL_NEW_TIME}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_EDIT_TIME}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_EDIT_TIME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2"><input class="post" type="text" name="dl_edit_time" size="3" maxlength="4" value="{DL_EDIT_TIME}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_EDIT_OWN_DOWNLOADS}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_EDIT_OWN_DOWNLOADS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="edit_own_downloads" value="1" {EDIT_OWN_DOWNLOADS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="edit_own_downloads" value="0" {EDIT_OWN_DOWNLOADS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_SHOW_FOOTER_LEGEND}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_SHOW_FOOTER_LEGEND_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="show_footer_legend" value="1" {SHOW_FOOTER_LEGEND_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_footer_legend" value="0" {SHOW_FOOTER_LEGEND_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_SHOW_FOOTER_STATS}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_SHOW_FOOTER_STATS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="show_footer_stat" value="1" {SHOW_FOOTER_STATS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_footer_stat" value="0" {SHOW_FOOTER_STATS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_SHOW_REAL_FILETIME}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_SHOW_REAL_FILETIME_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="show_real_filetime" value="1" {SHOW_REAL_FILETIME_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_real_filetime" value="0" {SHOW_REAL_FILETIME_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_SHORTEN_EXTERN_LINKS}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_SHORTEN_EXTERN_LINKS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="shorten_extern_links" size="3" maxlength="4" value="{SHORTEN_EXTERN_LINKS}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_LIMIT_DESC_ON_INDEX}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_LIMIT_DESC_ON_INDEX_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="limit_desc_on_index" size="5" maxlength="10" value="{LIMIT_DESC_ON_INDEX}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DOWNLOAD_VC}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DOWNLOAD_VC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="download_vc" value="1" {DOWNLOAD_VC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="download_vc" value="0" {DOWNLOAD_VC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_REPORT_BROKEN}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_REPORT_BROKEN_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="report_broken" value="1" {REPORT_BROCKEN_ON} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="report_broken" value="2" {REPORT_BROCKEN_GUESTS} /> {L_NO_GUESTS}&nbsp;&nbsp;<input type="radio" name="report_broken" value="0" {REPORT_BROCKEN_OFF} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_REPORT_BROKEN_LOCK}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_REPORT_BROKEN_LOCK_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="report_broken_lock" value="1" {REPORT_BROCKEN_LOCK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="report_broken_lock" value="0" {REPORT_BROCKEN_LOCK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_REPORT_BROKEN_MESSAGE}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_REPORT_BROKEN_MESSAGE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="report_broken_message" value="1" {REPORT_BROCKEN_MESSAGE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="report_broken_message" value="0" {REPORT_BROCKEN_MESSAGE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_REPORT_BROKEN_VC}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_REPORT_BROKEN_VC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="report_broken_vc" value="1" {REPORT_BROCKEN_VC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="report_broken_vc" value="0" {REPORT_BROCKEN_VC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_LINKS_PER_PAGE}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_LINKS_PER_PAGE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="dl_links_per_page" size="3" maxlength="4" value="{DL_LINKS_PER_PAGE}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_SORT_PREFORM}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_SORT_PREFORM_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="sort_preform" value="1" {SORT_PREFORM_FIX} />&nbsp;{L_SORT_FIX}&nbsp;<input type="radio" name="sort_preform" value="0" {SORT_PREFORM_USER} />&nbsp;{L_SORT_USER}</td>
</tr>
<!-- BEGIN portal_block -->
<tr>
	<td class="row1" width="68%"><strong>{L_DL_RECENT}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_RECENT_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="recent_downloads" size="3" maxlength="4" value="{DL_RECENT}" /></td>
</tr>
<!-- END portal_block -->
<tr>
	<td class="row1" width="68%"><strong>{L_LATEST_COMMENTS}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_LATEST_COMMENTS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="latest_comments" size="3" maxlength="4" value="{LATEST_COMMENTS}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_POSTS}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_POSTS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="dl_posts" size="3" maxlength="4" value="{DL_POSTS}" /></td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_PHYSICAL_QUOTA}</strong><br /><span class="gensmall">{L_DL_PHYSICAL_QUOTA_SECOND}</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_PHYSICAL_QUOTA_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%">
		<input type="text" class="post" size="10" maxlength="20" name="physical_quota" value="{PHYSICAL_QUOTA}" />&nbsp;
		<input type="radio" name="x_quota" value="byte" {X_QUOTA1} />&nbsp;{L_BYTES}&nbsp;
		<input type="radio" name="x_quota" value="kb" {X_QUOTA2} />&nbsp;{L_KB}&nbsp;
		<input type="radio" name="x_quota" value="mb" {X_QUOTA3} />&nbsp;{L_MB}&nbsp;
		<input type="radio" name="x_quota" value="gb" {X_QUOTA4} />&nbsp;{L_GB}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_OVERALL_TRAFFIC}</strong><br /><span class="gensmall">{REMAINING_OVERALL_TRAFFIC}</span></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_OVERALL_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%">
		<input type="text" class="post" size="10" maxlength="20" name="overall_traffic" value="{OVERALL_TRAFFIC}" />&nbsp;
		<input type="radio" name="x_over" value="byte" {X_OVER1} />&nbsp;{L_BYTES}&nbsp;
		<input type="radio" name="x_over" value="kb" {X_OVER2} />&nbsp;{L_KB}&nbsp;
		<input type="radio" name="x_over" value="mb" {X_OVER3} />&nbsp;{L_MB}&nbsp;
		<input type="radio" name="x_over" value="gb" {X_OVER4} />&nbsp;{L_GB}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_USER_TRAFFIC_ONCE}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_USER_TRAFFIC_ONCE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="user_traffic_once" value="1" {USER_TRAFFIC_ONCE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="user_traffic_once" value="0" {USER_TRAFFIC_ONCE_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_ENABLE_POST_TRAFFIC}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_ENABLE_POST_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="enable_post_dl_traffic" value="1" {ENABLE_POST_TRAFFIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_post_dl_traffic" value="0" {ENABLE_POST_TRAFFIC_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_NEWTOPIC_TRAFFIC}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_NEWTOPIC_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="text" class="post" size="10" maxlength="20" name="newtopic_traffic" value="{NEWTOPIC_TRAFFIC}" />&nbsp;
		<input type="radio" name="x_new" value="byte" {X_NEW1} />&nbsp;{L_BYTES}&nbsp;
		<input type="radio" name="x_new" value="kb" {X_NEW2} />&nbsp;{L_KB}&nbsp;
		<input type="radio" name="x_new" value="mb" {X_NEW3} />&nbsp;{L_MB}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_REPLY_TRAFFIC}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_REPLY_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%">
		<input type="text" class="post" size="10" maxlength="20" name="reply_traffic" value="{REPLY_TRAFFIC}" />&nbsp;
		<input type="radio" name="x_reply" value="byte" {X_REPLY1} />&nbsp;{L_BYTES}&nbsp;
		<input type="radio" name="x_reply" value="kb" {X_REPLY2} />&nbsp;{L_KB}&nbsp;
		<input type="radio" name="x_reply" value="mb" {X_REPLY3} />&nbsp;{L_MB}&nbsp;
	</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_DELAY_AUTO_TRAFFIC}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_DELAY_AUTO_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="delay_auto_traffic" size="3" maxlength="4" value="{DELAY_AUTO_TRAFFIC}" />&nbsp;{L_DAYS}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_DELAY_POST_TRAFFIC}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_DELAY_POST_TRAFFIC_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input class="post" type="text" name="delay_post_traffic" size="3" maxlength="4" value="{DELAY_POST_TRAFFIC}" />&nbsp;{L_DAYS}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_STOP_UPLOADS}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_STOP_UPLOADS_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="stop_uploads" value="1" {STOP_UPLOADS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="stop_uploads" value="0" {STOP_UPLOADS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_UPLOAD_TRAFFIC_COUNT}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_UPLOAD_TRAFFIC_COUNT_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="upload_traffic_count" value="1" {UPLOAD_TRAFFIC_COUNT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="upload_traffic_count" value="0" {UPLOAD_TRAFFIC_COUNT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_THUMBSNAIL_SIZE}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_THUMBSNAIL_SIZE_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%">
		<input type="text" size="10" class="post" maxlength="20" name="thumb_fsize" value="{THUMB_FSIZE}" />&nbsp;
		<input type="radio" name="f_quote" value="byte" {THUMB_F_RANGE_1} />&nbsp;{L_BYTES}&nbsp;
		<input type="radio" name="f_quote" value="kb" {THUMB_F_RANGE_2} />&nbsp;{L_KB}
	</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_THUMBSNAIL_DIM}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_THUMBSNAIL_DIM_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%">
		<input type="text" size="5" maxlength="5" class="post" name="thumb_xsize" value="{THUMB_XSIZE}" />&nbsp;*&nbsp;
		<input type="text" size="5" maxlength="5" class="post" name="thumb_ysize" value="{THUMB_YSIZE}" />
	</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_DISABLE_EMAIL}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_DISABLE_EMAIL_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="disable_email" value="1" {DISABLE_EMAIL_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_email" value="0" {DISABLE_EMAIL_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_DISABLE_POPUP}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_DISABLE_POPUP_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="disable_popup" value="1" {DISABLE_POPUP_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_popup" value="0" {DISABLE_POPUP_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_DISABLE_POPUP_NOTIFY}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_DISABLE_POPUP_NOTIFY_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="disable_popup_notify" value="1" {DISABLE_POPUP_NOTIFY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="disable_popup_notify" value="0" {DISABLE_POPUP_NOTIFY_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_STATS_PERM}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_STATS_PERM_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%">{S_STATS_PERM_SELECT}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_GUEST_STATS_SHOW}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_GUEST_STATS_SHOW_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="guest_stats_show" value="1" {GUEST_STATS_SHOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="guest_stats_show" value="0" {GUEST_STATS_SHOW_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_PREVENT_HOTLINK}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_PREVENT_HOTLINK_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="prevent_hotlink" value="1" {PREVENT_HOTLINK_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="prevent_hotlink" value="0" {PREVENT_HOTLINK_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_HOTLINK_ACTION}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_HOTLINK_ACTION_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="hotlink_action" value="1" {HOTLINK_ACTION_ONE} /> {L_DL_HOTLINK_ACTION_ONE}&nbsp;&nbsp;<input type="radio" name="hotlink_action" value="0" {HOTLINK_ACTION_TWO} /> {L_DL_HOTLINK_ACTION_TWO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_USE_HACKLIST}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_USE_HACKLIST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="use_hacklist" value="1" {USE_HACKLIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_hacklist" value="0" {USE_HACKLIST_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="68%"><strong>{L_DL_USE_EXT_BLACKLIST}</strong></td>
	<td class="row1" width="2%">&nbsp;[<a href="javascript:void(0)" onclick="javascript:help_popup('{L_DL_USE_EXT_BLACKLIST_EXPLAIN}')" class="nav">?</a>]&nbsp;</td>
	<td class="row2" width="30%"><input type="radio" name="use_ext_blacklist" value="1" {USE_EXT_BLACKLIST_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="use_ext_blacklist" value="0" {USE_EXT_BLACKLIST_NO} /> {L_NO}</td>
</tr>
<tr><td class="cat" colspan="3" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>
