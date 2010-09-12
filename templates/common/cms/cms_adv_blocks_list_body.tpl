<script type="text/javaScript">
<!--
function goForm(form)
{

	document.submit();
}
-->
</script>

<!-- INCLUDE ../common/cms/page_header.tpl -->

<!-- INCLUDE ../common/cms/breadcrumbs.tpl -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100" valign="middle"><img src="images/cms/cms_blocks.png" alt="{L_BLOCKS_TITLE}" title="{L_BLOCKS_TITLE}" /></td>
	<td class="row1" valign="top"><h1>{L_BLOCKS_TITLE} - {LAYOUT_NAME}</h1><span class="genmed">{L_BLOCKS_TEXT}</span></td>
</tr>
</table>

<form method="post" action="{S_BLOCKS_ACTION}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="cat" colspan="9" style="text-align:left">
		{S_HIDDEN_FIELDS}
		<input type="hidden" name="action" /> 
		<input class="cms-button" type="submit" name="add" value="{L_BLOCKS_ADD}" />&nbsp;
		<!-- BEGIN duplicate_switch -->
		<input class="cms-button" type="submit" name="action_duplicate" value="{L_BLOCKS_DUPLICATE}" />&nbsp;
		<!-- END duplicate_switch -->
		<input class="cms-button" type="submit" name="action_update" value="{L_BLOCKS_POSITION_SAVE}" />&nbsp;
		<!-- BEGIN blocks_updated -->
		<script type="text/javascript">
		// <![CDATA[
			//var box_begin = '<div id="result-box" style="height: 16px; border: solid 1px green; background: #00ff00;"><span class="text_green">';
			//var box_end = '<\/span><\/div>';
			//$('sort-info-box').innerHTML = box_begin + '{L_BLOCKS_POSITION_UPDATED}' + box_end; new Effect.Highlight('result-box', {duration: 0.5}); window.setTimeout("new Effect.Fade('result-box',{duration: 0.5})", 2500);}});
			window.setTimeout("new Effect.Fade('box-updated',{duration:0.5})", 2500);
		// ]]>
		</script>
		<div id="box-updated" class="row-center" style=" position: fixed; top: 0px; right: 0px; z-index: 1; background: none; border: none; width: 300px; padding: 3px;">
			<div id="result-box" style="height: 16px; border: solid 1px green; background: #00ff00;"><span class="text_green">{L_BLOCKS_POSITION_UPDATED}</span></div>
		</div>
		<!-- END blocks_updated -->
	</td>
</tr>
<tr><td class="spaceRow" colspan="9"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td align="center">
		<div style="min-width:750px">
		{LAYOUT_BLOCKS}
		{INVALID_BLOCKS}
		<!-- BEGIN drop_blocks -->
		<input type="hidden" id="list_{drop_blocks.BPOSITION}_debug" name="list_{drop_blocks.BPOSITION}"></input>
		<!-- END drop_blocks -->
		<script type="text/javascript">
		// <![CDATA[
		<!-- BEGIN drop_blocks -->
			Sortable.create("list_{drop_blocks.BPOSITION}",
			{dropOnEmpty:true,handle:'handle',containment:[{CONTAINMENT}],constraint:false,
			onChange:function(){$('list_{drop_blocks.BPOSITION}_debug').value = Sortable.serialize('list_{drop_blocks.BPOSITION}') }});
		<!-- END drop_blocks -->
		// ]]>
		</script>
		</div>
	</td>
</tr>
</table>
</form>

<!-- INCLUDE ../common/cms/page_footer.tpl -->