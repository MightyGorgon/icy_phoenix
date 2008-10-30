<!-- BEGIN switch_ajax_features -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ajax/ajax_searchfunctions.js"></script>
<!-- END switch_ajax_features -->
<h1>{L_USER_TITLE}</h1>
<p>{L_USER_EXPLAIN}</p>

<form method="post" name="post" action="{S_USER_ACTION}">
<table class="forumline" align="center" cellspacing="0" cellpadding="0">
<tr><th>{L_USER_SELECT}</th></tr>
<tr>
	<td class="row1 row-center">
		<input type="text" class="post" name="username" id="username" maxlength="50" size="20" {S_AJAX_USER_CHECK} />&nbsp;
		<span id="username_list" style="display:none;"><span id="username_select">&nbsp;</span>&nbsp;</span>
		<input type="hidden" name="mode" value="edit" />&nbsp;
		{S_HIDDEN_FIELDS}
		<input type="submit" name="submituser" value="{L_LOOK_UP}" class="mainoption" />&nbsp;
		<input type="button" name="usersubmit" value="{L_FIND_USERNAME}" class="liteoption" onClick="window.open('{U_SEARCH_USER}','_phpbbsearch','width=400,height=250,resizable=yes');" />
	</td>
</tr>
<tr id="username_error_tbl" style="display:none;"><td class="row1" id="username_error_text">&nbsp;</td></tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>
</form>
