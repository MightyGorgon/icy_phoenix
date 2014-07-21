<!-- INCLUDE pa_header.tpl -->
<!-- INCLUDE pa_links.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_STATISTICS}</span>{IMG_THR}<table class="forumlinenb">
<tr><th colspan="2">{L_GENERAL_INFO}</th></tr>
<tr><td class="row1" colspan="2"><span class="genmed">{STATS_TEXT}</span></td></tr>
<tr>
	<th class="tw50pct"><span class="cattitle">{L_DOWNLOADS_STATS}</span></th>
	<th class="tw50pct"><span class="cattitle">{L_RATING_STATS}</span></th>
</tr>
<tr><td class="row2 tdalignc" colspan="2"><span class="genmed">{L_OS}</span></td></tr>
<tr>
	<td class="row1 tw50pct">
		<table class="p2px">
		<!-- BEGIN downloads_os -->
		<tr>
			<td><img src="{downloads_os.OS_IMG}" alt="" />&nbsp;<span class="gen">{downloads_os.OS_NAME}</span></td>
			<td class="row1 tw50pct">
				<table>
				<tr>
					<td class="tw1pct tdnw"><img src="{U_VOTE_LCAP}" height="13" /><img src="{downloads_os.OS_OPTION_IMG}" width="{downloads_os.OS_OPTION_IMG_WIDTH}%" height="13" alt="{downloads_os.OS_OPTION_IMG_WIDTH}%" /><img src="{U_VOTE_RCAP}" height="13" alt="" /></td>
				</tr>
				</table>
			</td>
			<td class="tdalignc"><span class="gen">[ {downloads_os.OS_OPTION_RESULT} ]</span></td>
		</tr>
		<!-- END downloads_os -->
		</table>
	</td>
	<td class="row1 tw50pct">
		<table class="p2px">
			<!-- BEGIN rating_os -->
			<tr>
				<td><img src="{rating_os.OS_IMG}" alt="" />&nbsp;<span class="gen">{rating_os.OS_NAME}</span></td>
				<td class="row1 tw50pct">
					<table cellspacing="0" cellpadding="0" border="0" align="left">
					<tr>
						<td class="tw1pct tdnw"><img src="{U_VOTE_LCAP}" alt="" /><img src="{downloads_os.OS_OPTION_IMG}" width="{downloads_os.OS_OPTION_IMG_WIDTH}%" alt="{downloads_os.OS_OPTION_IMG_WIDTH}" /><img src="{U_VOTE_RCAP}" alt="" /></td>
					</tr>
					</table>
				</td>
				<td class="tdalignc"><span class="gen">[ {rating_os.OS_OPTION_RESULT} ]</span></td>
			</tr>
			<!-- END rating_os -->
		</table>
	</td>
</tr>
<tr><td class="row2 tdalignc" colspan="2"><span class="genmed">{L_BROWSERS}</span></td></tr>
<tr>
	<td class="row1 tw50pct">
		<table class="p2px">
		<!-- BEGIN downloads_b -->
		<tr>
			<td><img src="{downloads_b.B_IMG}" alt="" />&nbsp;<span class="gen">{downloads_b.B_NAME}</span></td>
			<td class="row1 tw50pct">
				<table>
				<tr>
					<td class="tw1pct tdnw"><img src="{U_VOTE_LCAP}" alt="" /><img src="{downloads_b.B_OPTION_IMG}" width="{downloads_b.B_OPTION_IMG_WIDTH}%" alt="{downloads_b.B_OPTION_RESULT}" /><img src="{U_VOTE_RCAP}" alt="" /></td>
				</tr>
				</table>
			</td>

			<td class="tdalignc"><span class="gen">[ {downloads_b.B_OPTION_RESULT} ]</span></td>
		</tr>
		<!-- END downloads_b -->
		</table>
	</td>
	<td class="row1 tw50pct">
		<table class="p2px">
		<!-- BEGIN rating_b -->
		<tr>
			<td><img src="{rating_b.B_IMG}" alt="" />&nbsp;<span class="gen">{rating_b.B_NAME}</span></td>
			<td class="row1 tw50pct">
				<table>
				<tr>
					<td class="tw1pct tdnw"><img src="{U_VOTE_LCAP}" alt="" /><img src="{downloads_b.B_OPTION_IMG}" width="{downloads_b.B_OPTION_IMG_WIDTH}%" alt="{downloads_b.B_OPTION_RESULT}" /><img src="{U_VOTE_RCAP}" alt="" /></td>
				</tr>
				</table>
			</td>
			<td class="tdalignc"><span class="gen">[ {rating_b.B_OPTION_RESULT} ]</span></td>
		</tr>
		<!-- END rating_b -->
		</table>
	</td>
</tr>
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- INCLUDE pa_footer.tpl -->