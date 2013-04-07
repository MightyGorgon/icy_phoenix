<h1>{L_EDIT} : {MODULE_NAME}</h1>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th>{L_PREVIEW}</th></tr>
<tr><td class="row3">{PREVIEW_MODULE}</td></tr>
</table>

<br />

<table class="forumline" width="80%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row3 row-center"><span class="genmed">{L_PREVIEW_DEBUG_INFO}<br />{L_UPDATE_TIME_RECOMMEND}</td></tr>
</table>

<br />

<!-- IF MESSAGE -->
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th>{L_MESSAGES}</th></tr>
<tr><td class="row1 row-center"><span class="genmed"><strong>{MESSAGE}</strong></td></tr>
<tr><td class="cat" colspan="1">&nbsp;</td></tr>
</table>
<!-- ENDIF -->

<br />

<form action="{S_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_EDIT}</th></tr>
<tr>
	<td class="row1" width="50%"><span class="genmed"><strong>{L_ACTIVE}</strong></span><br /><span class="gensmall">{L_ACTIVE_DESC}</span></td>
	<td class="row2" width="50%"><span class="genmed"><input type="radio" name="active" value="1" {ACTIVE_CHECKED_YES} />&nbsp;{L_YES}&nbsp;&nbsp;<input type="radio" name="active" value="0" {ACTIVE_CHECKED_NO} />&nbsp;{L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_UPDATE_TIME}</strong></span><br /><span class="gensmall">{L_UPDATE_TIME_DESC}</span></td>
	<td class="row2"><span class="genmed"><input type="text" class="post" name="updatetime" value="{UPDATE_TIME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_UNINSTALL}</strong></span><br /><span class="gensmall">{L_UNINSTALL_DESC}</span></td>
	<td class="row2"><span class="genmed"><input type="checkbox" name="uninstall" value="0" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_AUTH_SETTINGS}</strong></span></td>
	<td class="row2"><span class="genmed">{S_AUTH_SELECT}</td>
</tr>
<tr><td class="row1 row-center" colspan="2"><span class="genmed"><a href="{U_MANAGEMENT}" class="genmed">{L_BACK_TO_MANAGEMENT}</a></span></td></tr>
<tr><td class="cat" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>

<br /><div class="copyright" style="text-align: center;">{VERSION_INFO}<br />{INSTALL_INFO}</div>
