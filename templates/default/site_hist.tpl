{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="#" class="nav-current">{L_SITE_HISTORY}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{MONTH_BACK}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_MONTH}</th>
	<th width="10%">{L_USERS_TOTAL}</th>
	<th width="10%">{L_REG_USERS}</th>
	<th width="10%">{L_HIDDEN_USERS}</th>
	<th width="10%">{L_GUESTS_USERS}</th>
	<th width="10%">{L_NEW_TOPICS}</th>
	<th width="10%">{L_NEW_POSTS}</th>
	<th width="10%">{L_NEW_USERS}</th>
</tr>
<!-- BEGIN site_month -->
<tr>
	<td class="row1"><span class="gen">{site_month.MONTH}</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_month.TOTAL}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_month.REG}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_month.HIDDEN}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_month.GUESTS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_month.TOPICS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_month.POSTS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_month.NEW_USERS}&nbsp;</span></td>
</tr>
<!-- END site_month -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{IMG_THL}{IMG_THC}<span class="forumlink">{WEEK_BACK}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_DAY}</th>
	<th width="10%">{L_USERS_TOTAL}</th>
	<th width="10%">{L_REG_USERS}</th>
	<th width="10%">{L_HIDDEN_USERS}</th>
	<th width="10%">{L_GUESTS_USERS}</th>
	<th width="10%">{L_NEW_TOPICS}</th>
	<th width="10%">{L_NEW_POSTS}</th>
	<th width="10%">{L_NEW_USERS}</th>
</tr>
<!-- BEGIN site_week -->
<tr>
	<td class="row1"><span class="gen">{site_week.DAY}</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_week.TOTAL}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_week.REG}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_week.HIDDEN}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_week.GUESTS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_week.TOPICS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_week.POSTS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_week.NEW_USERS}&nbsp;</span></td>
</tr>
<!-- END site_week -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{IMG_THL}{IMG_THC}<span class="forumlink">{24_BACK}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_TIME}</th>
	<th width="10%">{L_USERS_TOTAL}</th>
	<th width="10%">{L_REG_USERS}</th>
	<th width="10%">{L_HIDDEN_USERS}</th>
	<th width="10%">{L_GUESTS_USERS}</th>
	<th width="10%">{L_NEW_TOPICS}</th>
	<th width="10%">{L_NEW_POSTS}</th>
	<th width="10%">{L_NEW_USERS}</th>
</tr>
<!-- BEGIN site_today -->
<tr>
	<td class="row1"><span class="gen">{site_today.TIME}</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_today.TOTAL}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_today.REG}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_today.HIDDEN}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_today.GUESTS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_today.TOPICS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_today.POSTS}&nbsp;</span></td>
	<td class="row1 row-center"><span class="gen">&nbsp;{site_today.NEW_USERS}&nbsp;</span></td>
</tr>
<!-- END site_week -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOP_POSTERS}&nbsp;{THIS_MONTH}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="1%">{L_RANK}</th>
	<th width="10%">{L_USERNAME}</th>
	<th width="10%">{L_POSTS}</th>
	<th width="10%">{L_PERCENTAGE}</th>
	<th>{L_GRAPH}</th>
</tr>
<!-- BEGIN top_posters -->
<tr>
	<td class="row1 row-center"><span class="gen">{top_posters.RANK}</span></td>
	<td class="row1"><span class="gen">{top_posters.USERNAME}</span></td>
	<td class="row1 row-center"><span class="gen">{top_posters.POSTS}</span></td>
	<td class="row1 row-center"><span class="gen">{top_posters.PERCENTAGE}%</span></td>
	<td class="row1" width="50%">
		<table cellspacing="0" cellpadding="0" border="0" align="left">
		<tr>
			<td colspan="3" width="1%" nowrap="nowrap">
				<img src="{LEFT_GRAPH_IMAGE}" width="4" height="12" alt="" /><img src="{GRAPH_IMAGE}" width="{top_posters.BAR}%" height="12" alt="{top_posters.BAR}%" title="{top_posters.BAR}%" /><img src="{RIGHT_GRAPH_IMAGE}" width="4" height="12" alt="" />
			</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END top_posters -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOP_POSTERS_WEEK}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="1%">{L_RANK}</th>
	<th width="10%">{L_USERNAME}</th>
	<th width="10%">{L_POSTS}</th>
	<th width="10%">{L_PERCENTAGE}</th>
	<th>{L_GRAPH}</th>
</tr>
<!-- BEGIN top_posters_week -->
<tr>
	<td class="row1 row-center"><span class="gen">{top_posters_week.RANK}</span></td>
	<td class="row1"><span class="gen">{top_posters_week.USERNAME}</span></td>
	<td class="row1 row-center"><span class="gen">{top_posters_week.POSTS}</span></td>
	<td class="row1 row-center"><span class="gen">{top_posters_week.PERCENTAGE}%</span></td>
	<td class="row1" width="50%">
		<table cellspacing="0" cellpadding="0" border="0" align="left">
		<tr>
			<td colspan="3" width="1%" nowrap="nowrap">
				<img src="{LEFT_GRAPH_IMAGE}" width="4" height="12" alt="" /><img src="{GRAPH_IMAGE}" width="{top_posters_week.BAR}%" height="12" alt="{top_posters_week.BAR}%" title="{top_posters_week.BAR}%" /><img src="{RIGHT_GRAPH_IMAGE}" width="4" height="12" alt="" />
			</td>
		</tr>
		</table>
	</td>
</tr>
<!-- END top_posters_week -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}