<!-- INCLUDE pa_header.tpl -->
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
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_INDEX}">{L_HOME}</a>{NAV_SEP}<a href="{U_DOWNLOAD}" class="nav">{DOWNLOAD}</a><!-- BEGIN navlinks -->{NAV_SEP}<a href="{navlinks.U_VIEW_CAT}" class="nav">{navlinks.CAT_NAME}</a><!-- END navlinks -->{NAV_SEP}<a href="{U_FILE_NAME}" class="nav">{FILE_NAME}</a>{NAV_SEP}<a href="#" class="nav-current">{L_RATE}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		<!-- INCLUDE pa_links.tpl -->
	</div>
</div>{IMG_TBR}

<form name="rateform" action="{S_RATE_ACTION}" method="post" onsubmit="return checkRateForm();">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RATE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1" width="90%"><div class="post-text">{RATEINFO}</div></td>
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
<tr><td colspan="2" class="cat" align="center"><input class="liteoption" type="submit" value="{L_RATE}" name="submit">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />
<!-- INCLUDE pa_footer.tpl -->