<!-- INCLUDE overall_header.tpl -->

<form action="{S_ACTION}" name="select_all" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_PIC_GALLERY}</span>{IMG_THR}<table class="forumlinenb">
<!-- BEGIN pic_row -->
<tr>
<!-- BEGIN pic_column -->
	<td class="row1g row-center">
		<!-- IF S_HIGHSLIDE --><a href="{pic_row.pic_column.PIC_IMAGE}" class="highslide" onclick="return hs.expand(this)"><!-- ELSE --><a href="{pic_row.pic_column.PIC_IMAGE}"><!-- ENDIF --><img class="picframe" src="{pic_row.pic_column.PIC_THUMB}" alt="{pic_row.pic_column.PIC_NAME}" title="{pic_row.pic_column.PIC_BBC}" /></a>
		<br />
		<span class="genmed"><b>{pic_row.pic_column.PIC_NAME}</b></span>{pic_row.pic_column.PIC_DELETE}<br />
		<input class="post" name="{pic_row.pic_column.PIC_BBC_INPUT}" size="20" maxlength="200" value="{pic_row.pic_column.PIC_BBC}" type="text" readonly="readonly" onclick="this.form.{pic_row.pic_column.PIC_BBC_INPUT}.focus(); this.form.{pic_row.pic_column.PIC_BBC_INPUT}.select();" />
	</td>
<!-- END pic_column -->
<!-- BEGIN pic_end_row -->
	<td class="row1g row-center">&nbsp;</td>
<!-- END pic_end_row -->
</tr>
<!-- END pic_row -->
<tr><td class="cat tdalignc" colspan="{S_COLSPAN}">&nbsp;&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<table>
<tr>
	<td class="tw70pct tvalignb">&nbsp;</td>
	<td class="tdalignr tvalignb"><span class="gensmall">{PAGE_NUMBER}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<br />

<!-- INCLUDE overall_footer.tpl -->