<!-- INCLUDE overall_header.tpl -->

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="49%" align="center" valign="top">
		<table class="forumline" width="100%" cellspacing="0">
		<tr>
			<th>{L_RANKS_IMAGE}</th>
			<th>{L_RANK_TITLE}</th>
		</tr>
		<!-- BEGIN ranks_no_special -->
		<tr>
			<td class="row1 row-center" colspan="2"><span class="gensmall">{ranks_no_special.L_RANK_NO_NORMAL}</span></td>
		</tr>
		<!-- END ranks_no_special -->
		<!-- BEGIN ranks_special -->
		<tr>
			<td class="row1 row-center"><img src="{ranks_special.IMAGE}" alt="{ranks_special.RANK}" title="{ranks_special.RANK}" border="0"><br /><span class="gensmall"><b>{ranks_special.RANK_SPECIAL_DES}</b></span></td>
			<td class="row1 row-center"><span class="genmed"><b>{ranks_special.RANK}</b></span></td>
		</tr>
		<!-- END ranks_special -->
		<tr><td class="cat" colspan="2"></td></tr>
		</table>
	</td>
	<td width="2%" align="center"></td>
	<td width="49%" align="center" valign="top">
		<table class="forumline" width="100%" cellspacing="0">
		<tr>
			<th>{L_RANKS_IMAGE}</th>
			<th>{L_RANK_TITLE}</th>
			<th>{L_RANK_MIN_M}</th>
		</tr>
		<!-- BEGIN ranks_no_normal -->
		<tr><td class="row1 row-center" colspan="3"><span class="gensmall">{ranks_no_normal.L_RANK_NO_NORMAL}</span></td>
		</tr>
		<!-- END ranks_no_normal -->
		<!-- BEGIN ranks_normal -->
		<tr>
			<td class="row1 row-center">
				<!-- BEGIN switch_image -->
				<img src="{ranks_normal.switch_image.IMAGE}" alt="{ranks_normal.switch_image.RANK}" title="{ranks_normal.switch_image.RANK}" border="0"><br />
				<!-- END switch_image -->
				<span class="gensmall"><b>{ranks_normal.RANK_SPECIAL_DES}</b></span>
			</td>
			<td class="row1 row-center"><span class="genmed"><b>{ranks_normal.RANK}</b></span></td>
			<td class="{ranks_normal.ROW_CLASS} row-center"><span class="genmed"><b>{ranks_normal.RANK_MIN}</b></span></td>
		</tr>
		<!-- END ranks_normal -->
		<tr><td class="cat" colspan="3"></td></tr>
		</table>
	</td>
</tr>
</table>

<!-- INCLUDE overall_footer.tpl -->