<div class="forumline nav-div">
	<p class="nav-header"><a href="{U_PORTAL}" class="nav-current">{L_HOME}</a></p>
	<div class="nav-links">
		<div class="nav-links-left">
			<!-- IF S_LOGGED_IN --><a href="{U_PRIVATEMSGS}">{PRIVATE_MESSAGE_INFO}</a><br /><!-- ENDIF -->
			{CURRENT_TIME}&nbsp;|
			{S_TIMEZONE}
		</div>
		<!-- IF S_LOGGED_IN -->
		<a href="{U_RECENT}" class="gensmall">{L_RECENT}</a>&nbsp;|&nbsp;<a href="{U_SEARCH_NEW}">{L_SEARCH_NEW}</a><br />
		<a href="{U_SEARCH_SELF}">{L_SEARCH_SELF}</a>&nbsp;|&nbsp;<a href="{U_SEARCH_UNANSWERED}">{L_SEARCH_UNANSWERED}</a>
		<!-- ELSE -->
		<a href="{U_RECENT}" class="gensmall">{L_RECENT}</a>&nbsp;|&nbsp;<a href="{U_SEARCH_UNANSWERED}">{L_SEARCH_UNANSWERED}</a>
		<!-- ENDIF -->
	</div>
</div>