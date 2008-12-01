{LOCBAR}
<br />
<!-- BEGIN ip_check -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RATE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th>{L_RATE}</th></tr>
</table>
<!-- END ip_check -->

<!-- BEGIN do_rate -->
<table class="forumline" width="100%" cellspacing="0">
<tr><td class="row-header"><span>{L_RATE}</span></td></tr>
<tr><th>{L_RATE}</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END do_rate -->

<!-- BEGIN rate -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RATE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="4">&nbsp;{L_RATE}</th></tr>
<tr>
	<td class="row1" width="90%"><span class="genmed">{RATEINFO}</span></td>
	<td class="row2"><form action="{S_RATE_ACTION}" method="post">
		<select size="1" name="rating" class="forminput">
			<option value="1">{L_R1}</option>
			<option value="2">{L_R2}</option>
			<option value="3">{L_R3}</option>
			<option value="4">{L_R4}</option>
			<option value="5" selected="selected">{L_R5}</option>
			<option value="6">{L_R6}</option>
			<option value="7">{L_R7}</option>
			<option value="8">{L_R8}</option>
			<option value="9">{L_R9}</option>
			<option value="10">{L_R10}</option>
		</select>
		<input type="hidden" name="action" value="rate" />
		<input type="hidden" name="id" value="{ID}" />
		<input type="hidden" name="rate" value="dorate" />
	</td>
</tr>
<tr><td colspan="4" class="cat"><input class="liteoption" type="submit" value="{L_RATE}" name="B1" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END rate -->