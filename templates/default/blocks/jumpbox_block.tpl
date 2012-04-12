<script type="text/javascript">//<![CDATA[
function redirect_{JUMPBOX_ID}(form)
{
	if (form.{JUMPBOX_ID}.value != -1)
	{
		location.href = form.{JUMPBOX_ID}.value;
	} 
}
//]]>
</script>
<div style="text-align:{MENU_JUMPBOX_ALIGN};">
{MAIN_MENU_NAME}:&nbsp;<form>{MENU_JUMPBOX}&nbsp;<input class="liteoption jumpbox" type ="button" value="{MENU_JUMPBOX_GO}" onclick="redirect_{JUMPBOX_ID}(this.form)"></form>
</div>
