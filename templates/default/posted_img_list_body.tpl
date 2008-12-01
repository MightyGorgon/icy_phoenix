<form action="{S_ACTION}" name="select_all" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_PIC_GALLERY}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN pic_row -->
<tr>
<!-- BEGIN pic_column -->
	<td class="row1g row-center" align="center">
		<center>
		<table><tr><td><div class="picshadow"><div class="picframe">
			<a href="{pic_row.pic_column.PIC_IMAGE}"><img src="{pic_row.pic_column.PIC_THUMB}" alt="{pic_row.pic_column.PIC_NAME}" title="{pic_row.pic_column.PIC_BBC}" /></a>
		</div></div></td></tr></table>
		</center>
		<br />
		<span class="genmed"><b>{pic_row.pic_column.PIC_NAME}</b></span>{pic_row.pic_column.PIC_DELETE}<br />
		<input class="post" name="{pic_row.pic_column.PIC_BBC_INPUT}" size="20" maxlength="200" value="{pic_row.pic_column.PIC_BBC}" type="text" readonly="readonly" onClick="javascript:this.form.{pic_row.pic_column.PIC_BBC_INPUT}.focus(); this.form.{pic_row.pic_column.PIC_BBC_INPUT}.select();" />
	</td>
<!-- END pic_column -->
<!-- BEGIN pic_end_row -->
	<td class="row1g row-center" align="center">&nbsp;</td>
<!-- END pic_end_row -->
</tr>
<!-- END pic_row -->
<tr><td class="cat" colspan="{S_COLSPAN}" align="center" height="28">&nbsp;&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="bottom" width="70%">&nbsp;</td>
	<td align="right" valign="bottom"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<br />