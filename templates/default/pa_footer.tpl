<form method="get" name="jumpbox" action="{S_JUMPBOX_ACTION}" onSubmit="if(document.jumpbox.cat_id.value == -1){return false;}">
<table class="empty-table" width="100%" align="center" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td align="left" valign="top">
	<input type="hidden" name="action" value="category" />
	<select name="cat_id" onchange="if(this.options[this.selectedIndex].value != -1){ forms['jumpbox'].submit() }">
	<option value="-1">{L_JUMP}</option>
	{JUMPMENU}
	</select>
	</td>
	<td align="right" valign="top"><span class="gensmall">{S_TIMEZONE}</span></td>
</tr>
</table>
</form>
<div align="center">
<span class="copyright">
<br />
Powered by paFileDB 3.1 ©2002 <a href="http://www.phparena.net/" target="_blank">PHP Arena</a><br />
This script (pafiledb integration v 0.0.9d + <a href="http://www.mx-system.com/" target="_blank">MX Addon 1.0</a>) is modified by <a href="http://www.hostsector.com/~mohd/" target="_blank">Mohd</a><br />
</span>
</div>

<!-- INCLUDE overall_footer.tpl -->