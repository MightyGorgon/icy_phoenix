<!-- INCLUDE overall_header.tpl -->

<!-- IF S_SEARCH_USER -->
<!-- INCLUDE memberlist_search.tpl -->
<!-- ENDIF -->

<form method="post" action="{S_MODE_ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MEMBERLIST}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="cat" width="100%"><!-- IF S_ADMIN --><b><a href="{U_FIND_MEMBER}" class="genmed">{L_FIND_MEMBER}</a></b>&nbsp;&bull;&nbsp;<!-- ENDIF --><!-- BEGIN alphanumsearch -->&nbsp;<b><a href="{alphanumsearch.SEARCH_LINK}" class="genmed">{alphanumsearch.SEARCH_TERM}</a></b>&nbsp;<!-- END alphanumsearch --></td></tr>
<tr>
	<td class="row1 row-center" align="center" nowrap="nowrap">
		<span class="genmed">
			{L_USERS_PER_PAGE}&nbsp;<input type="text" name="users_per_page" value="{S_USERS_PER_PAGE}" size="5" class="post" />&nbsp;
			{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
			<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
		</span>
	</td>
</tr>
<tr><td class="row1"><span class="gensmall"><b>{L_LEGEND}:&nbsp;</b>{GROUPS_LIST_LEGEND}</span></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MEMBERLIST}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- IF S_NO_USERS -->
<tr><th>{L_NO_USERS_FOUND}</th></tr>
<tr><td>{L_NO_USERS_FOUND}</td></tr>
<!-- ELSE -->
<tr>
	<th width="30">#</th>
	<th>{L_ONLINE_STATUS}</th>
	<th>{L_USERNAME}</th>
	<th>{L_CONTACTS}</th>
	<th>{L_USER_RANK}</th>
	<th width="50">{L_POSTS}</th>
	<!-- BEGIN cashrow -->
	<th nowrap="nowrap">{cashrow.NAME}</th>
	<!-- END cashrow -->
	<th>{L_FROM}</th>
	<th>{L_JOINED}</th>
	<th>{L_LOGON}</th>
	<!-- BEGIN custom_field_names -->
	<th>{custom_field_names.FIELD_NAME}</th>
	<!-- END custom_field_names -->
	<!-- IF S_ADMIN -->
	<th>{L_DELETE}</th>
	<!-- ENDIF -->
</tr>
<!-- BEGIN memberrow -->
<tr>
	<td class="row1 row-center" nowrap="nowrap">{memberrow.ROW_NUMBER}</td>
	<td class="row1 row-center-small">{memberrow.ONLINE_STATUS_IMG}</td>
	<td class="row1 row-center" height="100%">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td align="left" valign="middle">{memberrow.AVATAR_IMG}</td>
			<td align="left" width="100%" nowrap="nowrap" style="text-align: left;">&nbsp;{memberrow.USERNAME}&nbsp;{memberrow.POSTER_GENDER}&nbsp;{memberrow.AGE}{memberrow.STYLE}</td>
		</tr>
		</table>
	</td>
	<td class="row1 post-buttons-single" align="center">&nbsp;{memberrow.PM_IMG}&nbsp;{memberrow.EMAIL_IMG}&nbsp;{memberrow.WWW_IMG}&nbsp;</td>
	<td class="row1 row-center">
		<span class="gensmall">{memberrow.USER_RANK_01}{memberrow.USER_RANK_01_IMG}{memberrow.USER_RANK_02}{memberrow.USER_RANK_02_IMG}{memberrow.USER_RANK_03}{memberrow.USER_RANK_03_IMG}{memberrow.USER_RANK_04}{memberrow.USER_RANK_04_IMG}{memberrow.USER_RANK_05}{memberrow.USER_RANK_05_IMG}&nbsp;</span>
	</td>

	<td class="row1 row-center">{memberrow.POSTS}</td>
	<!-- BEGIN cashrow -->
	<td class="row1 row-center"><span class="gen">{memberrow.cashrow.CASH_DISPLAY}</span></td>
	<!-- END cashrow -->
	<td class="row1 row-center-small">&nbsp;{memberrow.FROM}&nbsp;</td>
	<td class="row1 row-center-small">{memberrow.JOINED}</td>
	<td class="row1 row-center-small">{memberrow.LAST_LOGON}</td>
	<!-- BEGIN custom_fields -->
	<td class="row1 row-center-small">{memberrow.custom_fields.CUSTOM_FIELD}</td>
	<!-- END custom_fields -->
	<!-- IF S_ADMIN -->
	<td class="row1 row-center-small post-buttons-single">{memberrow.DELETE}</td>
	<!-- ENDIF -->
</tr>
<!-- END memberrow -->
<tr>
	<td class="cat" width="100%" colspan="{NUMCOLS}">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
		<tr><!-- BEGIN alphanumsearch --><td align="center" width="{alphanumsearch.SEARCH_SIZE}"><span class="nav"><a href="{alphanumsearch.SEARCH_LINK}" class="genmed">{alphanumsearch.SEARCH_TERM}</a></span></td><!-- END alphanumsearch --></tr>
		</table>
	</td>
</tr>
<!-- ENDIF -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="pagination">{PAGINATION}</span></td>
	<td align="right" valign="top" nowrap="nowrap"><span class="genmed">&nbsp;</span></td>
</tr>
<tr>
	<td align="left"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td valign="top" align="right">{JUMPBOX}</td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->