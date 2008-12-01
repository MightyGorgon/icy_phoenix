{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header"><a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_CAT_DESC}</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}&nbsp;&bull;&nbsp;{S_TIMEZONE}</div>
		<!-- IF S_SHOW_ICONS -->
		<a href="{U_VIEW_OLDER_TOPIC}"><img src="{IMG_LEFT}" alt="{L_VIEW_PREVIOUS_TOPIC}" title="{L_VIEW_PREVIOUS_TOPIC}" /></a>
		{S_WATCH_TOPIC_IMG}
		{S_MARK_AR_IMG}
		<!-- IF not S_BOT -->{S_KB_MODE_IMG}<!-- ENDIF -->
		<a href="{DOWNLOAD_TOPIC}" title="{L_DOWNLOAD_TOPIC}"><img src="{IMG_FLOPPY}" alt="{L_DOWNLOAD_TOPIC}" title="{L_DOWNLOAD_TOPIC}" /></a>
		<!-- IF S_LOGGED_IN --><a href="{U_BOOKMARK_ACTION}"><img src="{IMG_BOOKMARK}" alt="{L_BOOKMARK_ACTION}" title="{L_BOOKMARK_ACTION}" /></a><!-- ENDIF -->
		<!-- IF U_TOPIC_VIEWED --><a href="{U_TOPIC_VIEWED}"><img src="{IMG_VIEWED}" alt="{L_TOPIC_VIEWED}" title="{L_TOPIC_VIEWED}" /></a><!-- ENDIF -->
		<a href="{U_PRINT}" title="{L_PRINT}"><img src="{IMG_PRINT}" alt="{L_PRINT}" title="{L_PRINT}" /></a>
		<a href="{U_TELL}" title="{L_TELL}"><img src="{IMG_EMAIL}" alt="{L_TELL}" title="{L_TELL}" /></a>
		<a href="{U_VIEW_NEWER_TOPIC}"><img src="{IMG_RIGHT}" alt="{L_VIEW_NEXT_TOPIC}" title="{L_VIEW_NEXT_TOPIC}" /></a>
		<!-- ENDIF -->
		<!-- IF S_SHOW_LINKS -->
		<a href="{U_PRINT}" title="{L_PRINT}">{L_PRINT}</a>&nbsp;&bull;&nbsp;
		<a href="{U_TELL}" title="{L_TELL}">{L_TELL}</a>&nbsp;&bull;&nbsp;
		<!-- IF S_LOGGED_IN --><a href="{U_BOOKMARK_ACTION}" class="genmed">{L_BOOKMARK_ACTION}</a>&nbsp;&bull;&nbsp;<!-- ENDIF -->
		<!-- IF U_TOPIC_VIEWED --><a href="{U_TOPIC_VIEWED}" class="genmed">{L_TOPIC_VIEWED}</a>&nbsp;&bull;&nbsp;<!-- ENDIF -->
		{S_WATCH_TOPIC}&nbsp;&bull;&nbsp;
		{U_MARK_ALWAYS_READ}&nbsp;&bull;&nbsp;
		<a href="{U_VIEW_OLDER_TOPIC}">{L_VIEW_PREVIOUS_TOPIC}</a>&nbsp;&bull;&nbsp;
		<a href="{U_VIEW_NEWER_TOPIC}">{L_VIEW_NEXT_TOPIC}</a>
		<!-- ENDIF -->
	</div>
</div>{IMG_TBR}