<!-- BEGIN cat_rule -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_BOARDRULES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1"><br /><div class="post-text">{cat_rule.CAT_RULE}</div><br /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END cat_rule -->

<form action="{U_DL_TOP}" method="post" name="dl_mod">
<div width="100%" style="text-align:left;">
	<div style="float:right;text-align:right;">
		<!-- BEGIN sort_options -->
		<span class="genmed">{L_SORT_BY}&nbsp;{S_SORT_BY}&nbsp;&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER}</span>&nbsp;
		<input type="submit" class="liteoption" value="{L_GO}" />
		<!-- END sort_options -->
	</div>
	{DL_UPLOAD}{U_SEARCH}&nbsp;
</div>
{SUBCAT_BOX}
<!-- BEGIN download_rows -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DOWNLOADS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_INFO}</th>
	<th>{L_NAME}</th>
	<th>{L_DESCRIPTION}</th>
	<th>{L_SIZE}</th>
	<th nowrap="nowrap">{L_KL_M_T}</th>
	<!-- BEGIN comment_header -->
	<th >{download_rows.comment_header.L_COMMENTS}</th>
	<!-- END comment_header -->
	<th nowrap="nowrap">{L_RATING}</th>
</tr>
<!-- END download_rows -->
<!-- BEGIN downloads -->
<tr>
	<td class="{downloads.ROW_CLASS} row-center">{downloads.STATUS}</td>
	<td class="{downloads.ROW_CLASS}" align="left">{downloads.MINI_IMG}&nbsp;<span class="gen"><b><a href="{downloads.U_FILE}" >{downloads.DESCRIPTION}</a></b>&nbsp;{downloads.HACK_VERSION}</span></td>
	<td class="{downloads.ROW_CLASS}" align="left"><span class="postdetails">{downloads.LONG_DESC}</span></td>
	<td class="{downloads.ROW_CLASS} row-center"><span class="postdetails">{downloads.FILE_SIZE}</span></td>
	<td class="{downloads.ROW_CLASS} row-center"><span class="postdetails">{downloads.FILE_KLICKS} / {downloads.FILE_OVERALL_KLICKS}</span></td>
	<!-- BEGIN comments -->
	<td class="{downloads.ROW_CLASS} row-center"><span class="postdetails">
		<a href="{downloads.comments.U_COMMENT_POST}" class="postdetails">{downloads.comments.L_COMMENT_POST}</a>{downloads.comments.BREAK}<a href="{downloads.comments.U_COMMENT_SHOW}" class="postdetails">{downloads.comments.L_COMMENT_SHOW}</a>
	</span></td>
	<!-- END comments -->
	<td class="{downloads.ROW_CLASS} row-center" nowrap="nowrap">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td align="center" nowrap="nowrap">{downloads.RATING_IMG}&nbsp;<span class="postdetails">{downloads.RATINGS}</span></td></tr>
		<tr><td align="center" nowrap="nowrap"><span class="postdetails">[&nbsp;<a href="{downloads.U_RATING}" class="gensmall">{downloads.L_RATING}</a>&nbsp;]</span></td></tr>
		</table>
	</td>
</tr>
<!-- END downloads -->
<!-- BEGIN download_rows -->
<tr>
	<td class="cat" colspan="{COL_WIDTH}">
		<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td><span class="gen">{PAGE_NUMBER}</span></td>
			<td align="right"><span class="pagination">{PAGINATION}</span></td>
		</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END download_rows -->
<div class="gensmall" style="width: 100%; text-align: left;"><!-- BEGIN cat_traffic --><span style="float: right;">{cat_traffic.CAT_TRAFFIC}&nbsp;</span><!-- END cat_traffic --><!-- BEGIN modcp --><span class="gensmall">{modcp.DL_MODCP}&nbsp;</span><!-- END modcp --></div>
</form>
