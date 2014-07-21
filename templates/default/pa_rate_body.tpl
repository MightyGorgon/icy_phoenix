<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

<script type="text/javascript">
<!--
function checkRateForm()
{
	if (document.rateform.rating.value == -1)
	{
		return false;
	}
	else
	{
		return true;
	}
}
// -->
</script>

<form name="rateform" action="{S_RATE_ACTION}" method="post" onsubmit="return checkRateForm();">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RATE}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td class="row1 tw90pct"><div class="post-text">{RATEINFO}</div></td>
	<td class="row2">
		<select size="1" name="rating" class="forminput">
			<option value="-1" selected>{L_RATE}</option>
			<option value="1">{L_R1}</option>
			<option value="2">{L_R2}</option>
			<option value="3">{L_R3}</option>
			<option value="4">{L_R4}</option>
			<option value="5">{L_R5}</option>
			<option value="6">{L_R6}</option>
			<option value="7">{L_R7}</option>
			<option value="8">{L_R8}</option>
			<option value="9">{L_R9}</option>
			<option value="10">{L_R10}</option>
		</select>
		<input type="hidden" name="action" value="rate" />
		<input type="hidden" name="file_id" value="{ID}" />
	</td>
</tr>
<tr><td class="cat tdalignc" colspan="2"><input class="liteoption" type="submit" value="{L_RATE}" name="submit">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />
<!-- INCLUDE pa_footer.tpl -->