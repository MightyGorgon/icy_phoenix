<form method="post" action="{S_AJAX_SEARCH}" onsubmit="return false;"><label><!-- {L_SEARCH}: -->&nbsp;<input name="ajax_text" id="ajax_text_search" type="text" class="post search" style="width: 160px;" value="{L_AJAX_SEARCH}" onclick="if(this.value=='{L_AJAX_SEARCH}')this.value='';" onblur="if(this.value=='')this.value='{L_AJAX_SEARCH}';" /></label></form>

<script type="text/javascript">
// <![CDATA[
$(function()
{
	$("#ajax_text_search").autocomplete(
	{
		source: "{U_AJAX_SEARCH}",
		minLength: 2,
		select: function(event, ui)
		{
			if (ui.item)
			{
				window.location = ui.item.url;
			}
		}
	});
});
// ]]>
</script>
