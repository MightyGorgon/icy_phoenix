<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{MESSAGE_TITLE}</span>{IMG_THR}<table class="forumlinenb">
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
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- INCLUDE overall_footer.tpl -->