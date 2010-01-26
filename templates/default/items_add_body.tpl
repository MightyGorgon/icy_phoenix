<!-- INCLUDE overall_header.tpl -->

<form name="input_form" method="post" action="{S_MODE_ACTION}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN field -->
<tr>
	<td class="row1" width="30%" style="vertical-align: top; padding: 5px;">
		<span class="gen"><b>{field.L_NAME}</b></span>
		<!-- IF field.L_EXPLAIN --><br /><div class="gensmall">{field.L_EXPLAIN}</div><!-- ENDIF -->
	</td>
	<td class="row2" style="padding: 5px;" nowrap="nowrap">
		<!-- IF field.S_BBCB -->{BBCB_MG}<!-- ENDIF -->
		<div class="gen">{field.INPUT}</div>
	</td>
</tr>
<!-- END field -->
<tr>
	<td class="cat" colspan="2">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE overall_footer.tpl -->