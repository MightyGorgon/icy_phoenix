<div class="align-right"><form method="post" action="{U_TAGS_SEARCH_PAGE}" id="tag_text_search_form" onsubmit="return false;"><label><!-- {L_SEARCH}: -->&nbsp;<input name="tag_text" id="tag_text_search" type="text" class="post search" style="width: 160px;" value="{L_TAGS_SEARCH}" onclick="if(this.value=='{L_TAGS_SEARCH}')this.value='';" onblur="if(this.value=='')this.value='{L_TAGS_SEARCH}';" /></label></form></div>

<script type="text/javascript">
// <![CDATA[
$(function()
{
	$("#tag_text_search_form #tag_text_search").autocomplete(
	{
		source: "ajax.php?mode=tags_search_json&json=1&sid={S_SID}",
		minLength: 2,
		select: function(event, ui)
		{
			//ui.item
			//ui.item.value
			//ui.item.id
			if (ui.item)
			{
				window.location = ui.item.url;
				//alert(ui.item.url);
			}
		}
	});
});
// ]]>
</script>
