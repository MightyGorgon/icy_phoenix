<!-- INCLUDE overall_header.tpl -->

<div class="block">
[ <a href="{U_RECENT_TODAY}" class="mainmenu">{L_TODAY}</a> ]
[ <a href="{U_RECENT_YESTERDAY}" class="mainmenu">{L_YESTERDAY}</a> ]
[ <a href="{U_RECENT_LAST24}" class="mainmenu">{L_LAST24}</a> ]
[ <a href="{U_RECENT_LASTWEEK}" class="mainmenu">{L_LASTWEEK}</a> ]
</div>
<br />

<!-- IF PAGINATION -->
<div class="block-empty">{PAGE_NUMBER} {PAGINATION}</div>
<!-- ENDIF -->

<!-- BEGIN recent -->
<div class="forum<!-- IF recent.CLASS_NEW --> forum{recent.CLASS_NEW}<!-- ENDIF -->" onclick="document.location.href='{recent.U_VIEW_TOPIC}'; return false;">
	<p><a href="{recent.U_VIEW_TOPIC}" class="{recent.TOPIC_CLASS}">{recent.TOPIC_TITLE}</a></p>
	<!-- IF recent.GOTO_PAGE_FULL --><p>{recent.GOTO_PAGE_FULL}</p><!-- ENDIF -->
	<p><span class="extra">{recent.REPLIES}</span> {recent.LAST_POST_TIME}</p>
</div>
<!-- END recent -->
<!-- BEGIN switch_no_topics -->
<div class="block">{L_NO_TOPICS}</div>
<!-- END switch_no_topics -->

<!-- IF PAGINATION -->
<div class="block-empty">{PAGE_NUMBER} {PAGINATION}</div>
<!-- ENDIF -->

<!-- INCLUDE overall_footer.tpl -->