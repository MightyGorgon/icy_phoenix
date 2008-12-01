<h1>{L_STATS_CONFIG}</h1>

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
<tr><th colspan="2">{L_STATS_CONFIG}</th></tr>
<tr>
	<td class="row1" align="left" valign="middle" width="75%"><span class="genmed"><strong>{L_RETURN_LIMIT}</strong></span><br /><span class="gensmall">{L_RETURN_LIMIT_DESC}</span></td>
	<td class="row2"><input type="text" class="post" name="return_limit_set" value="{RETURN_LIMIT}" /></td>
</tr>
<tr>
	<td class="row1" align="left" valign="middle" width="75%"><span class="genmed"><strong>{L_CLEAR_CACHE}</strong></span><br /><span class="gensmall">{L_CLEAR_CACHE_DESC}</span></td>
	<td class="row2"><input type="checkbox" class="post" name="clear_cache_set" /></td>
</tr>
<tr>
	<td class="row1" align="left" valign="middle" width="75%"><span class="genmed"><strong>{L_MODULES_DIR}</strong></span><br /><span class="gensmall">{L_MODULES_DIR_DESC}</span></td>
	<td class="row2"><input type="text" class="post" name="modules_dir_set" value="{MODULES_DIR}" /></td>
</tr>
<tr>
	<td class="cat" colspan="2" align="center"><input type="hidden" name="submit_update" value="1" /><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</table>
</form>

<!--
	This copyright information must be displayed as per the licence you agreed by using this modification!
-->
<br /><div class="copyright" style="text-align:center;">{VERSION_INFO}<br />{INSTALL_INFO}</div>
