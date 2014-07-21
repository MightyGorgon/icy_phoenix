<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_BBUS_MOD_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0">
<tr>
	<th class="tdnw">{L_BBUS_COLHEADER_FORUM}</th>
	<th class="tdnw">{L_BBUS_COLHEADER_POSTS}</th>
	<th class="tdnw">{L_BBUS_COLHEADER_POSTRATE}</th>
	<th class="tdnw">{L_BBUS_COLHEADER_PCTUTP}</th>
	<!-- BEGIN bb_usage_switch_pctutup_colhdr -->
	<!--
	<th class="tdnw">%UTUP</th>
	-->
	<!-- END bb_usage_switch_pctutup -->
	<th class="tdnw">{L_BBUS_COLHEADER_NEWTOPICS}</th>
	<th class="tdnw">{L_BBUS_COLHEADER_TOPICRATE}</th>
	<th class="tdnw">{L_BBUS_COLHEADER_TOPICS_WATCHED}</th>
</tr>
<!-- BEGIN bb_usage_section_row -->
<tr>
	<td class="cat tdalignl tvalignm">
		<a style="cursor: pointer" onclick="document.section_form_{bb_usage_section_row.SECTION_ID}.submit()"><b>{bb_usage_section_row.SECTION_NAME}</b></a>
		<form name="section_form_{bb_usage_section_row.SECTION_ID}" action="{bb_usage_section_row.U_SECTION}" method="post"><input name="search_cat" type="hidden" value="{bb_usage_section_row.SECTION_ID}" /></form>
	</td>
	<td class="cat tdalignc tvalignm"><b>{bb_usage_section_row.SECTION_POST_COUNT}</b></td>
	<td class="cat tdalignc tvalignm"><b>{bb_usage_section_row.SECTION_POSTRATE}</b></td>
	<td class="cat tdalignc tvalignm"><b>{bb_usage_section_row.SECTION_POST_PCTUTP}</b></td>
	<!-- BEGIN bb_usage_switch_pctutup_section -->
	<!--
	<td class="cat tdalignc tvalignm"><b><span class="catTitle">{bb_usage_section_row.bb_usage_switch_pctutup_section.SECTION_POST_PCTUTUP}</span></b></td>
	-->
	<!-- END bb_usage_switch_pctutup_section -->
	<td class="cat tdalignc tvalignm"><b>{bb_usage_section_row.SECTION_NEWTOPICS}</b></td>
	<td class="cat tdalignc tvalignm"><b>{bb_usage_section_row.SECTION_TOPICRATE}</b></td>
	<td class="cat tdalignc tvalignm"><b>{bb_usage_section_row.SECTION_TOPICS_WATCHED}&nbsp;</b></td>
</tr>

<!-- BEGIN bb_usage_forum_row -->
<tr>
	<td class="row1 tw100pct">
		<span class="topiclink"><a style="cursor: pointer;" onclick="document.forum_form_{bb_usage_section_row.bb_usage_forum_row.FORUM_ID}.submit()">{bb_usage_section_row.bb_usage_forum_row.FORUM_NAME}</a></span>
		<form name="forum_form_{bb_usage_section_row.bb_usage_forum_row.FORUM_ID}" action="{bb_usage_section_row.bb_usage_forum_row.FORUM_URL}" method="post"><input name="search_forum" type="hidden" value="{bb_usage_section_row.bb_usage_forum_row.FORUM_ID}" /></form>
	</td>
	<td class="row2 row-center tvalignm"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_POST_COUNT}</span></td>
	<td class="row2 row-center tvalignm"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_POSTRATE}</span></td>
	<td class="row3 row-center tvalignm"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_POST_PCTUTP}</span></td>
	<!-- BEGIN bb_usage_switch_pctutup_forum -->
	<!--
	<td class="row3 row-center tvalignm"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.bb_usage_switch_pctutup_forum.FORUM_POST_PCTUTUP}</span></td>
	-->
	<!-- END bb_usage_switch_pctutup_forum -->
	<td class="row2 row-center tvalignm"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_NEWTOPICS}</span></td>
	<td class="row2 row-center tvalignm"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_TOPICRATE}</span></td>
	<td class="row2 row-center tvalignm"><span class="gen">{bb_usage_section_row.bb_usage_forum_row.FORUM_TOPICS_WATCHED}&nbsp;</span></td>
</tr>
<!-- END bb_usage_forum_row -->
<!-- END bb_usage_section_row -->

<!-- BEGIN bb_usage_switch_scaling_row -->
<tr>
	<td class="cat tdalignr tvalignm">{bb_usage_switch_scaling_row.SCALE_TEXT}</td>
	<td class="cat tdalignr tvalignm">&nbsp;</td>
	<td class="cat tdalignc tvalignm"><form method="post" name="scale_form" action="{bb_usage_switch_scaling_row.U_SCALE}">{bb_usage_switch_scaling_row.PRSCALE_SELECT_LIST}</form></td>
	<td class="cat tdalignr tvalignm">&nbsp;</td>
	<!-- BEGIN pctutup_filler_cell -->
	<!--
	{bb_usage_switch_scaling_row.pctutup_filler_cell.FILLER_CELL}
	-->
	<!-- END bb_usage_scaling_filler_cell -->
	<td class="cat tdalignr tvalignm">&nbsp;</td>
	<td class="cat tdalignc tvalignm"><form method="post" name="scale_form" action="{bb_usage_switch_scaling_row.U_SCALE}">{bb_usage_switch_scaling_row.TRSCALE_SELECT_LIST}</form></td>
	<td class="cat tdalignr tvalignm">&nbsp;</td>
	<td class="cat tdalignr tvalignm">&nbsp;</td>
</tr>
</form>
<!-- END bb_usage_switch_scaling_row -->

<!-- BEGIN bb_usage_row_noposts -->
<tr><td class="row1" colspan="8"><b><span class="gen">{bb_usage_row_noposts.L_BBUS_MSG_NOPOSTS}</span></b></td></tr>
<!-- END bb_usage_row_noposts -->

<!-- BEGIN bb_usage_switch_miscellaneous_info -->
<tr>
	<th class="tdnw" colspan="8">
		{bb_usage_switch_miscellaneous_info.L_BBUS_COLHDR_MISC}<a href="{URL_COLDESC}" class="gensmall" onclick="window.open('{URL_COLDESC}','_coldesc','width=400,height=250,resizable=yes');" >{L_BBUS_COL_DESCRIPTIONS_CAPTION}</a>
	</th>
</tr>

<!-- BEGIN bb_usage_switch_misc_prunedposts -->
<tr>
	<td class="row1 tw100pct" colspan="{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.BBUS_PRUNED_POSTS_COLSPAN1}">
		<span class="gen">{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.L_BBUS_PRUNED_POSTS}</span>
	</td>
	<td class="row2 row-center tvalignm" colspan="{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.BBUS_PRUNED_POSTS_COLSPAN2}">
		<span class="gen">{bb_usage_switch_miscellaneous_info.bb_usage_switch_misc_prunedposts.BBUS_PRUNED_POSTS}</span>
	</td>
</tr>
<!-- END bb_usage_switch_misc_prunedposts -->

<!-- END bb_usage_switch_miscellaneous_info -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
