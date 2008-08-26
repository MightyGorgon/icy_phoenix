{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}">{L_HOME}</a>{NAV_SEP}<a href="{U_INDEX}">{L_INDEX}</a>{NAV_SEP}<a href="{U_DL_TOP}">{L_DL_TOP}</a>{NAV_SEP}<a href="#" class="nav-current">{L_PAGE}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">{CURRENT_TIME}</div>
		&nbsp;
	</div>
</div>{IMG_TBR}

{IMG_THL}{IMG_THC}<span class="forumlink">{L_DL_TOP}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_LATEST_DOWNLOADS}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="50%" class="cat"><span class="genmed"><b>{L_DOWNLOAD}</b></span></td>
			<td width="40%" class="cat" align="center"><span class="genmed"><b>{L_TIME}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_latest -->
		<tr>
			<td width="10%" class="{top_ten_latest.ROW_CLASS} row-center"><b>{top_ten_latest.POS}</b></td>
			<td width="50%" class="{top_ten_latest.ROW_CLASS}"><a href="{top_ten_latest.U_FILE_LINK}"><b>{top_ten_latest.DESCRIPTION}</b></a><br /><span class="gensmall">[ {top_ten_latest.CAT_NAME} ]</span></td>
			<td width="40%" class="{top_ten_latest.ROW_CLASS} row-center">{top_ten_latest.U_USER_LINK}<br /><span class="gensmall">{top_ten_latest.DL_TIME}</span></td>
		</tr>
		<!-- END top_ten_latest -->
		</table>
	</td>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_LATEST_UPLOADS}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="50%" class="cat"><span class="genmed"><b>{L_DOWNLOAD}</b></span></td>
			<td width="40%" class="cat" align="center"><span class="genmed"><b>{L_TIME}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_uploads -->
		<tr>
			<td width="10%" class="{top_ten_uploads.ROW_CLASS} row-center"><b>{top_ten_uploads.POS}</b></td>
			<td width="50%" class="{top_ten_uploads.ROW_CLASS}"><a href="{top_ten_uploads.U_FILE_LINK}"><b>{top_ten_uploads.DESCTIPTION}</b></a><br /><span class="gensmall">[ {top_ten_uploads.CAT_NAME} ]</span></td>
			<td width="40%" class="{top_ten_uploads.ROW_CLASS} row-center">{top_ten_uploads.U_USER_LINK}<br /><span class="gensmall">{top_ten_uploads.DL_TIME}</span></td>
		</tr>
		<!-- END top_ten_uploads -->
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_DOWNLOADS_CUR_MONTH}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_DOWNLOAD}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_KLICKS}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_dl_cur_month -->
		<tr>
			<td width="10%" class="{top_ten_dl_cur_month.ROW_CLASS} row-center"><b>{top_ten_dl_cur_month.POS}</b></td>
			<td width="60%" class="{top_ten_dl_cur_month.ROW_CLASS}"><a href="{top_ten_dl_cur_month.U_FILE_LINK}"><b>{top_ten_dl_cur_month.DESCRIPTION}</b></a><br /><span class="gensmall">[ {top_ten_dl_cur_month.CAT_NAME} ]</span></td>
			<td width="30%" class="{top_ten_dl_cur_month.ROW_CLASS} row-center"><span class="gen">{top_ten_dl_cur_month.DL_KLICKS}</span></td>
		</tr>
		<!-- END top_ten_dl_cur_month -->
		</table>
	</td>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_DOWNLOADS_OVERALL}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_DOWNLOAD}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_KLICKS_OVERALL}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_dl_overall -->
		<tr>
			<td width="10%" class="{top_ten_dl_overall.ROW_CLASS} row-center"><b>{top_ten_dl_overall.POS}</b></td>
			<td width="60%" class="{top_ten_dl_overall.ROW_CLASS}"><a href="{top_ten_dl_overall.U_FILE_LINK}"><b>{top_ten_dl_overall.DESCRIPTION}</b></a><br /><span class="gensmall">[ {top_ten_dl_overall.CAT_NAME} ]</span></td>
			<td width="30%" class="{top_ten_dl_overall.ROW_CLASS} row-center"><span class="gen">{top_ten_dl_overall.DL_KLICKS}</span></td>
		</tr>
		<!-- END top_ten_dl_overall -->
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_TRAFFIC_CUR_MONTH}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_DOWNLOAD}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_TRAFFIC_CUR_MONTH}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_traffic_cur_month -->
		<tr>
			<td width="10%" class="{top_ten_traffic_cur_month.ROW_CLASS} row-center"><b>{top_ten_traffic_cur_month.POS}</b></span></td>
			<td width="60%" class="{top_ten_traffic_cur_month.ROW_CLASS}"><a href="{top_ten_traffic_cur_month.U_FILE_LINK}" class="nav">{top_ten_traffic_cur_month.DESCRIPTION}</a><br /><span class="gensmall">[ {top_ten_traffic_cur_month.CAT_NAME} ]</span></td>
			<td width="30%" class="{top_ten_traffic_cur_month.ROW_CLASS} row-center"><span class="gen">{top_ten_traffic_cur_month.DL_TRAFFIC}</span></td>
		</tr>
		<!-- END top_ten_traffic_cur_month -->
		</table>
	</td>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_TRAFFIC_OVERALL}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_DOWNLOAD}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_TRAFFIC_OVERALL}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_traffic_overall -->
		<tr>
			<td width="10%" class="{top_ten_traffic_overall.ROW_CLASS} row-center"><b>{top_ten_traffic_overall.POS}</b></td>
			<td width="60%" class="{top_ten_traffic_overall.ROW_CLASS}"><a href="{top_ten_traffic_overall.U_FILE_LINK}" class="nav">{top_ten_traffic_overall.DESCRIPTION}</a><br /><span class="gensmall">[ {top_ten_traffic_overall.CAT_NAME} ]</span></td>
			<td width="30%" class="{top_ten_traffic_overall.ROW_CLASS} row-center"><span class="gen">{top_ten_traffic_overall.DL_TRAFFIC}</span></td>
		</tr>
		<!-- END top_ten_traffic_overall -->
		</table>
	</td>
</tr>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_DOWNLOADS_DOWNLOADS_COUNT}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_USERNAME}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_DL_COUNTS}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_dl_counts -->
		<tr>
			<td width="10%" class="{top_ten_dl_counts.ROW_CLASS} row-center"><b>{top_ten_dl_counts.POS}</b></td>
			<td width="60%" class="{top_ten_dl_counts.ROW_CLASS}">{top_ten_dl_counts.U_USER_LINK}</td>
			<td width="30%" class="{top_ten_dl_counts.ROW_CLASS} row-center"><span class="gen">{top_ten_dl_counts.DL_COUNTS}</span></td>
		</tr>
		<!-- END top_ten_dl_counts -->
		</table>
	</td>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_DOWNLOADS_DOWNLOADS_TRAFFIC}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_USERNAME}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_DL_TRAFFIC}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_dl_traffic -->
		<tr>
			<td width="10%" class="{top_ten_dl_traffic.ROW_CLASS} row-center"><b>{top_ten_dl_traffic.POS}</b></span></td>
			<td width="60%" class="{top_ten_dl_traffic.ROW_CLASS}">{top_ten_dl_traffic.U_USER_LINK}</td>
			<td width="30%" class="{top_ten_dl_traffic.ROW_CLASS} row-center"><span class="gen">{top_ten_dl_traffic.DL_TRAFFIC}</span></td>
		</tr>
		<!-- END top_ten_dl_traffic -->
		</table>
	</td>
</tr>
<tr>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_DOWNLOADS_UPLOADS_COUNT}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_USERNAME}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_UP_COUNTS}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_up_counts -->
		<tr>
			<td width="10%" class="{top_ten_up_counts.ROW_CLASS} row-center"><b>{top_ten_up_counts.POS}</b></span></td>
			<td width="60%" class="{top_ten_up_counts.ROW_CLASS}">{top_ten_up_counts.U_USER_LINK}</td>
			<td width="30%" class="{top_ten_up_counts.ROW_CLASS} row-center"><span class="gen">{top_ten_up_counts.DL_COUNTS}</span></td>
		</tr>
		<!-- END top_ten_up_counts -->
		</table>
	</td>
	<td colspan="2" width="50%" valign="top">
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<th colspan="3">{L_DOWNLOADS_UPLOADS_TRAFFIC}</th>
		<tr>
			<td width="10%" class="cat"><span class="genmed"><b>{L_POSITION}</b></span></td>
			<td width="60%" class="cat"><span class="genmed"><b>{L_USERNAME}</b></span></td>
			<td width="30%" class="cat" align="center"><span class="genmed"><b>{L_DL_TRAFFIC}</b></span></td>
		</tr>
		<!-- BEGIN top_ten_up_traffic -->
		<tr>
			<td width="10%" class="{top_ten_up_traffic.ROW_CLASS} row-center"><b>{top_ten_up_traffic.POS}</b></span></td>
			<td width="60%" class="{top_ten_up_traffic.ROW_CLASS}">{top_ten_up_traffic.U_USER_LINK}</td>
			<td width="30%" class="{top_ten_up_traffic.ROW_CLASS} row-center"><span class="gen">{top_ten_up_traffic.DL_TRAFFIC}</span></td>
		</tr>
		<!-- END top_ten_up_traffic -->
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />