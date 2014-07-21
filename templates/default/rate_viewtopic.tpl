<tr>
	<td class="row1 row-center" colspan="4">
		<br />
		<!-- BEGIN rated -->
		<span class="gen">&nbsp;&nbsp;{rated.RATE_TOPIC_USER}&nbsp;&nbsp;{L_RATE_TOPIC_USER_ANON}</span>
		<!-- END rated -->
		<span class="gensmall">{FULL_STATS_URL}&nbsp;</span><br /><br />

		<div class="gen">
<script type="text/javascript">
function submitform()
{
	document.forms["ratingform"].submit();
}
</script>
			<!-- BEGIN rerate -->
			<form id="ratingform" name="ratingform" method="post" action="{rerate.S_RATE_ACTION}">
			{rerate.S_HIDDEN_FIELDS}{rerate.L_CHANGE_RATING}:&nbsp;
			<!-- END rerate -->
			<!-- BEGIN rerate_link -->
			<!-- BEGIN rate_row -->
			{rerate_link.rate_row.RATE_LINK}
			<!-- END rate_row -->
			<!-- END rerate_link -->
			<!-- BEGIN rerate -->
			{rerate.S_RATE_SELECT}&nbsp;<input type="submit" value="{rerate.L_RATE}" class="liteoption" />{rerate.RATE_TOPIC_USER}
			<!-- END rerate -->

			<!-- BEGIN rate -->
			<form id="ratingform" name="ratingform" method="post" action="{rate.S_RATE_ACTION}">
			{rate.S_HIDDEN_FIELDS}{rate.L_CHOOSE_RATING}:&nbsp;
			<!-- END rate -->
			<!-- BEGIN rate_link -->
			<!-- BEGIN rate_row -->
			{rate_link.rate_row.RATE_LINK}
			<!-- END rate_row -->
			<!-- END rate_link -->
			<!-- BEGIN rate -->
			{rate.S_RATE_SELECT}&nbsp;<input type="submit" value="{rate.L_RATE}" class="liteoption" />
			{rate.RATE_TOPIC_USER}
			<!-- END rate -->

			<!-- BEGIN noauth -->
			{noauth.RATE_TOPIC_USER}
			<!-- END noauth -->
			<br /><br />
			</form>
		</div>
	</td>
</tr>
<tr>
	<th class="tw25pct">{L_RATE_AVERAGE}</th>
	<th class="tw25pct">{L_RATE_MINIMUM}</th>
	<th class="tw25pct">{L_RATE_MAXIMUM}</th>
	<th class="tw25pct">{L_Number_of_Rates}</th>
</tr>
<tr>
	<td class="row1 row-center"><span class="gen">{RATE_TOPIC_STATS}</span></td>
	<td class="row1 row-center"><span class="gen"><b>{RATE_MINIMUM}</b></span></td>
	<td class="row1 row-center"><span class="gen"><b>{RATE_MAXIMUM}</b></span></td>
	<td class="row1 row-center"><span class="gen"><b>{NUMBER_OF_RATES}</b></span></td>
</tr>
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>