<script language="JavaScript">
<!--
function redirect (form)
{
	if (form.jumpbox.value != -1)
	{
		location.href = form.jumpbox.value;
	} 
}
-->
</script>

<table width="100%" border="0" align="center" cellspacing="3" cellpadding="0">
<tr>
	<td style="text-align:{MENU_JUMPBOX_ALIGN};">
	{MAIN_MENU_NAME}:&nbsp;
	<form>{MENU_JUMPBOX}&nbsp;<input class="liteoption jumpbox" type ="button" value="{MENU_JUMPBOX_GO}" onclick="redirect(this.form)"></form>
	</td>
</tr>
</table>