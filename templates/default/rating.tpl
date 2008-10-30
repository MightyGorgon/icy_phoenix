<table class="forumline" width="100%" cellspacing="0">
<tr>
	<td>
	<p class="maintitle">{HEADING}</p>
	<p class="gen">{L_POSTER}: <b>{POSTER}</b><br />
	<br />
	{L_TOPIC}: <b>{TOPIC_TITLE}</b><br />
	{L_TOPIC_RANK}: {TOPIC_RANK}<br />
	{MESSAGE}</p>

	<form name="rating_form" method="post" action="{FORM_ACTION}">
	<input type="hidden" name="rating_form_submitted" value="y" />
	<table class="empty-table" width="100%" cellspacing="10">
		<tr>
			<td valign="top">
				<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr><td colspan="2"><span class="gen">{RATE_POST_MSG}</span></td></tr>
					<!-- BEGIN option -->
					<tr>
						<td><input type="radio" name="rating" value="{option.ID}" {option.SELECTED} /></td>
						<td class="gen">{option.LABEL}</td>
					</tr>
					<!-- END option -->
				</table>
				{SUBMIT_BUTTON}
			</td>
		</tr>
		<tr>
			<td valign="top" align="center">
				<table class="forumline" width="100%" cellspacing="0">
					<caption class="gen"><b>{L_POST_RANK}:&nbsp;{POST_RANK}</b></caption>
					<tr>
						<th>{L_RATED_BY}</th>
						<th>{L_BIAS}</th>
						<th>{L_RATED_ON}</th>
						<th>{L_RATING}</th>
					</tr>
					<!-- BEGIN current -->
					<tr>
						<td class="{current.ROWCSS}"><span class="gen">{current.WHO}</span></td>
						<td class="{current.ROWCSS}"><span class="gen">{current.BIAS}</span></td>
						<td class="{current.ROWCSS}"><span class="gen">{current.RATING_TIME}</span></td>
						<td class="{current.ROWCSS}"><span class="gen">{current.RATING}</span></td>
					</tr>
					<!-- END current -->
				</table>
			</td>
		</tr>
	</table>
	</form>
	<p class="maintitle"><a href="{U_END_LINK}">{L_END_LINK}</a></p>
	</td>
</tr>
</table>