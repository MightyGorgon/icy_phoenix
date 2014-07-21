<!-- BEGIN statusrow -->
<table class="s2px p2px tb1px">
<tr>
	<td class="tdalignc">
		<span class="gen">{L_STATUS}</span><br />
		<span class="genmed"><b>{I_STATUS_MESSAGE}</b></span><br />
	</td>
</tr>
</table>
<!-- END statusrow -->

<table class="s2px p2px">
<tr>
	<td>
		<span class="maintitle">{L_PAGE_NAME}</span>
		<br /><span class="gensmall"><b>{L_VERSION} {VERSION}
		<br />{NIVISEC_CHECKER_VERSION}</b></span><br /><br />
		<span class="genmed">{L_PAGE_DESC}<br /><br />{VERSION_CHECK_DATA}</span>
	</td>
</tr>
</table>

<br />
<form action="{S_ACTION}" name="user_list_form" method="post">

<p><table align="center" cellspacing="0" cellpadding="0" border="0"><tr><td>{LETTER_HEADING}</tr></td></table></p>

<table class="forumline">
<tr>
	<th><a href="{S_USERNAME}" class="cattitle">{L_USERNAME}</a>&nbsp;{IMG_USERNAME}</th>
	<th><a href="{S_MODULES}" class="cattitle">{L_MODULES}</a>&nbsp;{IMG_MODULES}</th>
	<th><a href="{S_RANK}" class="cattitle">{L_RANK}</a>&nbsp;{IMG_RANK}</th>
	<th><a href="{S_AVATAR}" class="cattitle">{L_AVATAR}</a>&nbsp;{IMG_AVATAR}</th>
	<th><a href="{S_PM}" class="cattitle">{L_PM}</a>&nbsp;{IMG_PM}</th>
	<th><a href="{S_ACTIVE}" class="cattitle">{L_ACTIVE}</a>&nbsp;{IMG_ACTIVE}</th>
	<th>&nbsp;</th>
</tr>
<!-- BEGIN userrow -->
<tr>
	<td class="{userrow.ROW_CLASS}"><span class="gensmall">{userrow.BOOKMARK}{userrow.USERNAME_FULL}{userrow.BOOKMARK_END}</span>&nbsp;&nbsp;<span class="gensmall">{userrow.MODULE_COUNT}</span></td>
	<td class="{userrow.ROW_CLASS} row-center">{userrow.MODULES}</td>
	<td class="{userrow.ROW_CLASS} row-center">{userrow.RANK_LIST}</td>
	<td class="{userrow.ROW_CLASS} row-center"><input type="checkbox" name="allow_pm_user_{userrow.ID}" {userrow.ALLOW_PM}></td>
	<td class="{userrow.ROW_CLASS} row-center"><input type="checkbox" name="allow_avatar_user_{userrow.ID}" {userrow.ALLOW_AVATAR}></td>
	<td class="{userrow.ROW_CLASS} row-center"><input type="checkbox" name="active_user_{userrow.ID}" {userrow.ACTIVE}></td>
	<td class="{userrow.ROW_CLASS} row-center"><input type="submit" name="edit_user_{userrow.ID}" value="{L_EDIT_LIST}" class="liteoption"></td>
</tr>
<!-- END userrow -->
<tr><td class="cat" colspan="7" align="center" height="28"><input type="hidden" name="mode" value="{S_MODE}" />&nbsp;</td></tr>
</table>
</form>
<table class="forumline">
<tr>
	<td><span class="mainmenu">{PAGE_NUMBER}</span></td>
	<td class="tdalignr"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>