<!-- INCLUDE breadcrumbs.tpl -->

<form action="{S_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RATE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN hon_rating -->
<tr>
	<td class="row1 row-center">
		<span class="gen">
		{L_PLEASE_RATE_IT}:&nbsp;&nbsp;&nbsp;
		<!-- BEGIN hon_row -->
		<input type="radio" name="hon_rating" value="{hon_rating.hon_row.VALUE}" onclick="this.form.submit()" />&nbsp;{hon_rating.hon_row.VALUE}&nbsp;&nbsp;
		<!-- END hon_row -->
		<input type="hidden" name="pic_id" value="{PICTURE_ID}" />
		</span>
	</td>
</tr>
<!-- END hon_rating -->
<!-- BEGIN hon_rating_cant -->
<tr><td class="row1 row-center"><span class="gen">{L_ALREADY_RATED}</span></td></tr>
<!-- END hon_rating_cant -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

{IMG_THL}{IMG_THC}<span class="forumlink">{PIC_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1 row-center" width="10%"><img src="{U_PIC}" border="0" vspace="10" alt="{PIC_TITLE}" title="{PIC_TITLE}" /></td></tr>
<tr>
	<td class="row2">
		<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="50%" align="right"><span class="genmed">{L_POSTER}:&nbsp;</span></td>
			<td width="50%"><b><span class="genmed">{POSTER}</span></b></td>
		</tr>
		<tr>
			<td align="right"><span class="genmed">{L_PIC_TITLE}:&nbsp;</span></td>
			<td><b><span class="genmed">{PIC_TITLE}</span></b></td>
		</tr>
		<tr>
			<td align="right"><span class="genmed">{L_PIC_ID}:&nbsp;</span></td>
			<td><b><span class="genmed">{PIC_ID}</span></b></td>
		</tr>
		<tr>
			<td align="right"><span class="genmed">{L_PIC_DESC}:&nbsp;</span></td>
			<td><b><span class="genmed">{PIC_DESC}</span></b></td>
		</tr>
		<tr>
			<td align="right"><span class="genmed">{L_POSTED}:&nbsp;</span></td>
			<td><b><span class="genmed">{PIC_TIME}</span></b></td>
		</tr>
		<tr>
			<td align="right"><span class="genmed">{L_VIEW}:&nbsp;</span></td>
			<td><b><span class="genmed">{PIC_VIEW}</span></b></td>
		</tr>
		<!-- BEGIN rate_switch -->
		<tr>
			<td align="right"><span class="genmed">{L_RATING}:&nbsp;</span></td>
			<td><b><span class="genmed">{PIC_RATING}</span></b></td>
		</tr>
		<!-- END rate_switch -->
		<!-- BEGIN comment_switch -->
		<tr>
			<td align="right"><span class="genmed"><a href="{U_COMMENT}">{L_COMMENTS}:</a>&nbsp;</span></td>
			<td><b><span class="genmed">{PIC_COMMENTS}</span></b></td>
		</tr>
		<!-- END comment_switch -->
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}