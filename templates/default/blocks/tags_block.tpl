<!-- IF S_TAGS_SEARCH -->
<form method="post" action="{U_TAGS_SEARCH_PAGE}" id="tag_text_search_form_{S_TAGS_BLOCK_ID}" onsubmit="return false;"><input name="tag_text" id="tag_text_search_{S_TAGS_BLOCK_ID}" type="text" class="post search" style="width: 120px;" value="{L_TAGS_SEARCH}" onclick="if(this.value=='{L_TAGS_SEARCH}')this.value='';" onblur="if(this.value=='')this.value='{L_TAGS_SEARCH}';" /></form><br clear="all" />

<script type="text/javascript">
// <![CDATA[
$(function()
{
	$("#tag_text_search_form_{S_TAGS_BLOCK_ID} #tag_text_search_{S_TAGS_BLOCK_ID}").autocomplete(
	{
		source: "ajax.php?mode=tags_search_json&json=1&sid={S_SID}",
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
<!-- ENDIF -->
<!-- BEGIN tags_loop --><span style="font-size: {tags_loop.TAG_FONT_SIZE}px;"><a href="{tags_loop.U_TAG_TEXT}" style="font-size: {tags_loop.TAG_FONT_SIZE}px;">{tags_loop.TAG_TEXT}<!-- IF S_TAGS_COUNT -->&nbsp;({tags_loop.TAG_COUNT})<!-- ENDIF --></a>&nbsp;&nbsp;</span><!-- END tags_loop -->
