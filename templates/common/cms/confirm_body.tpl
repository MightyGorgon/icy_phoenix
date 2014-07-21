<!-- INCLUDE ../common/cms/page_header.tpl -->

<table class="forumline">
<tr><th>{MESSAGE_TITLE}</th></tr>
<tr>
	<td class="row1g">
		<form action="{S_CONFIRM_ACTION}" method="post">
			{MESSAGE_TEXT}<br /><br />
			{S_HIDDEN_FIELDS}
			<input type="submit" name="confirm" value="{L_YES}" class="mainoption" />&nbsp;
			<input type="submit" name="cancel" value="{L_NO}" class="liteoption" />
		</form>
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat tdalignc">&nbsp;</td></tr>
</table>

<br clear="all" />

<!-- INCLUDE ../common/cms/page_footer.tpl -->