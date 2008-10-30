<h1>Limit Image Width MOD</h1>
<p>{L_EXPLAIN}</p>

<br />

<form action="{S_CONFIG_ACTION}" method="post">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="2">{L_COMPATIBILITY_CHECKS}</th></tr>
<tr>
	<td class="row1" width="40%"><b>{L_GETIMAGESIZE}</b><br /><span class="gensmall">{L_GETIMAGESIZE_EXPLAIN}</span></td>
	<td class="row2" width="60%"><span class="genmed"><b>{L_COMP_GETIMAGESIZE_STATUS}</b><br /><span class="gensmall">{L_COMP_GETIMAGESIZE_TEXT}</span></td>
</tr>
<tr>
	<td class="row1" width="40%"><b>{L_URLAWARE}</b><br /><span class="gensmall">{L_URLAWARE_EXPLAIN}</span></td>
	<td class="row2" width="60%"><span class="genmed"><b>{L_COMP_URLAWARE_STATUS}</b><br /><span class="gensmall">{L_COMP_URLAWARE_TEXT}</span></td>
</tr>
<tr>
	<td class="row1" width="40%"><b>{L_OPENSSL}</b><br /><span class="gensmall">{L_OPENSSL_EXPLAIN}</span></td>
	<td class="row2" width="60%"><span class="genmed"><b>{L_COMP_OPENSSL_STATUS}</b><br /><span class="gensmall">{L_COMP_OPENSSL_TEXT}</span></td>
</tr>
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th colspan="3">{L_MOD_CONFIG}</th></tr>
<tr>
	<td class="row1" width="40%"><b>{L_ENABLE}</b><br /><span class="gensmall">{L_ENABLE_EXPLAIN}</span></td>
	<td class="row2" colspan="2" width="60%"><input type="radio" name="liw_enabled" value="1" {S_ENABLED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="liw_enabled" value="0" {S_ENABLED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1" width="40%"><b>{L_SIG_ENABLE}</b><br /><span class="gensmall">{L_SIG_ENABLE_EXPLAIN}</span></td>
	<td class="row2" colspan="2" width="60%"><input type="radio" name="liw_sig_enabled" value="1" {S_SIG_ENABLED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="liw_sig_enabled" value="0" {S_SIG_ENABLED_NO} /> {L_NO}</td>
</tr>
<!-- BEGIN switch_attach_mod_installed -->
<tr>
	<td class="row1" width="40%"><b>{L_ATTACH_ENABLE}</b><br /><span class="gensmall">{L_ATTACH_ENABLE_EXPLAIN}</span></td>
	<td class="row2" colspan="2" width="60%"><input type="radio" name="liw_attach_enabled" value="1" {S_ATTACH_ENABLED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="liw_attach_enabled" value="0" {S_ATTACH_ENABLED_NO} /> {L_NO}</td>
</tr>
<!-- END switch_attach_mod_installed -->
<tr>
	<td class="row1" width="40%"><b>{L_MAX_WIDTH}</b><br /><span class="gensmall">{L_MAX_WIDTH_EXPLAIN}</span></td>
	<td class="row2" colspan="2" width="60%"><input class="post" type="text" size="5" maxlength="4" name="liw_max_width" value="{MAX_IMG_WIDTH}" /></td>
</tr>
<tr>
	<td class="row1" width="40%"><b>{L_EMPTY_CACHE}</b><br /><span class="gensmall">{L_EMPTY_CACHE_EXPLAIN}</span></td>
	<td class="row2"><input type="submit" name="empty_cache" value="{L_EMPTY_CACHE_BUTTON}" class="liteoption"></td>
	<td class="row2"><span class="gensmall">{L_EMPTY_CACHE_NOTE}</span></td>
</tr>
<tr><td class="cat" colspan="3"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
</form>

<br clear="all" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center">
		<span class="copyright">Limit Image Width MOD &copy; 2004 Vic D'Elfant<br />[ <a href="http://www.phpbb.com/phpBB/profile.php?mode=viewprofile&u=118634" class="copyright" target="_blank">phpBB.com Profile</a> :: <a href="http://www.pythago.nl" class="copyright" target="_blank">Website</a> ]</span>
	</td>
</tr>
</table>
<br />