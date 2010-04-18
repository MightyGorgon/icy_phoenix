{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_CMS}">{L_CMS_TITLE}</a><!-- IF CMS_PAGE_TITLE -->{NAV_SEP}<a href="#" class="nav-current">{CMS_PAGE_TITLE}</a><!-- ENDIF -->
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<!-- IF LAYOUT_NAME -->
		{L_B_LAYOUT}: [ <a href="{PAGE_URL}">{LAYOUT_NAME}</a> ]<!-- IF U_LAYOUT_EDIT -->&nbsp;|&nbsp;[ <a href="{U_LAYOUT_EDIT}">{L_B_LAYOUT_EDIT}</a> ]<!-- ENDIF -->&nbsp;|&nbsp;{L_B_PAGE}: [ {PAGE} ]
		<!-- ELSE -->
		<a href="{U_CMS}">{L_CMS_MANAGEMENT}</a>&nbsp;|&nbsp;<a href="{U_CMS_CONFIG}">{L_CMS_CONFIG}</a>&nbsp;|&nbsp;<a href="{U_CMS_ADS}">{L_CMS_ADS}</a>&nbsp;|&nbsp;<a href="{U_CMS_MENU}">{L_CMS_MENU_PAGE}</a>
		<!-- ENDIF -->
	</div>
</div>{IMG_TBR}