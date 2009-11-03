{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{FULL_SITE_PATH}{U_PORTAL}">{L_HOME}</a>{BREADCRUMBS_ADDRESS}
	</p>
	<div class="nav-links">
		<div class="nav-links-left"><!-- IF S_BREADCRUMBS_LINKS_LEFT -->{BREADCRUMBS_LINKS_LEFT}<br /><!-- ENDIF -->{CURRENT_TIME}</div>
		<!-- IF S_LOGGED_IN --><a href="{FULL_SITE_PATH}{U_PRIVATEMSGS}">{PRIVATE_MESSAGE_INFO}</a>&nbsp;|&nbsp;<!-- ENDIF --><a href="{FULL_SITE_PATH}{U_RECENT}" class="gensmall">{L_RECENT}</a>&nbsp;|&nbsp;<a href="{FULL_SITE_PATH}{U_SEARCH_UNANSWERED}">{L_SEARCH_UNANSWERED}</a>
		<!-- IF S_BREADCRUMBS_LINKS_RIGHT --><br />{BREADCRUMBS_LINKS_RIGHT}<!-- ENDIF -->
	</div>
</div>{IMG_TBR}
