<table>
<tr>
	<td class="no-padding" width="50%" valign="top">
		<table>
		<tr><th align="center" colspan="2"><span class="gensmall"><b>{TOP_DOWNLOADS}</b></span><br /></th></tr>
		<!-- BEGIN dlrow -->
		<tr>
			<td class="{dlrow.ROW_CLASS} row-center" width="5%"><span class="genmed"><b>{dlrow.NUMBER_MOST}.</b></span></td>
			<td class="{dlrow.ROW_CLASS}">
				<span class="genmed"><a href="{dlrow.FILELINK_MOST}">{dlrow.FILENAME_MOST} ({dlrow.INFO_MOST})</a></span><br />
				<span class="genmed">{dlrow.DESCRIP_MOST}</span>
			</td>
		</tr>
		<!-- END dlrow -->
		</table>
	</td>
	<td class="no-padding" width="50%" valign="top">
		<table>
			<tr><th align="center" colspan="2"><span class="gensmall"><b>{NEW_DOWNLOADS}</b></span><br /></th></tr>
			<!-- BEGIN dlrow2 -->
			<tr>
				<td class="{dlrow2.ROW_CLASS} row-center" width="5%"><span class="genmed"><b>{dlrow2.NUMBER_LATEST}.</b></span></td>
				<td class="{dlrow2.ROW_CLASS}">
					<span class="genmed"><a href="{dlrow2.FILELINK_LATEST}">{dlrow2.FILENAME_LATEST} ({dlrow2.INFO_LATEST})</a></span><br />
					<span class="genmed">{dlrow2.DESCRIP_LATEST}</span>
				</td>
			</tr>
			<!-- END dlrow2 -->
		</table>
	</td>
</tr>
</table>