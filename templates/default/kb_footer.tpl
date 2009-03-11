<tr>
	<td>
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td align="left" valign="top">
				<!-- BEGIN quick_nav -->
				<form method="get" name="jumpbox" action="{QUICK_JUMP_ACTION}" onsubmit="if(document.jumpbox.cat.value == -1){return false;}">
				<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td nowrap="nowrap"><span class="genmed">{L_QUICK_NAV}&nbsp;<select name="cat" onchange="if(this.options[this.selectedIndex].value != 0){ forms['jumpbox'].submit() }"><option value="0">{L_QUICK_JUMP}</option>{QUICK_NAV}</select>{S_HIDDEN_VARS}<input type="submit" value="{L_QUICK_JUMP}" class="liteoption" /></span></td>
				</tr>
				</table>
				</form>
				<!-- END quick_nav -->
			</td>
			<td align="right">
				<!-- BEGIN auth_can -->
				<span class="gensmall">{S_AUTH_LIST}</span>
				<!-- END auth_can -->
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<!-- BEGIN copy_footer -->
<div align="center">
<span class="copyright">
<br />
Powered by Knowledge Base MOD, {L_MODULE_ORIG_AUTHOR} &amp; <a href="http://www.mx-system.com/" target="_blank">{L_MODULE_AUTHOR}</a> © 2002-2005<br />
<a href="http://www.phpbb.com/phpBB/viewtopic.php?t=200195" target="_blank">PHPBB.com MOD</a><br />
</span>
</div>
<!-- END copy_footer -->