<!-- INCLUDE overall_header.tpl -->

<!-- BEGIN mooshow -->
{JS_INCLUDE}
<!-- END mooshow -->

<!-- BEGIN mooshow -->
<div id="{SELECTED_CAT_REG}" class="mooshow">
	this.speed=300;
	this.fadeSpeed=500;
	this.topNav='yes';
	this.overlayNav='yes';
	this.dropShadow='yes';
	this.captions='yes';
	this.border=20;
	this.copyright='yes';
	this.IPTCinfo='yes'
</div>
<!-- END mooshow -->
<form action="{S_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_PIC_GALLERY}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdalignc tvalignm" colspan="{S_COLSPAN}">
		<span class="genmed">
			{L_CATEGORY}:&nbsp;{S_CATEGORY_SELECT}&nbsp;
			<input type="submit" class="liteoption" value="{L_GO}" name="pic_gallery" />
		</span>
	</th>
</tr>
<!-- BEGIN pic_row -->
<tr>
<!-- BEGIN pic_column -->
	<td class="row1g row-center">
		<!-- IF S_HIGHSLIDE --><a href="{pic_row.pic_column.PIC_IMAGE}" class="highslide" onclick="return hs.expand(this)"><!-- ELSE --><a href="{pic_row.pic_column.PIC_IMAGE}"><!-- ENDIF --><img class="picframe" src="{pic_row.pic_column.PIC_THUMB}" alt="{pic_row.pic_column.PIC_NAME}" title="{pic_row.pic_column.PIC_NAME}" /></a>
		<br />
		<span class="genmed"><b>{pic_row.pic_column.PIC_NAME}</b></span>
	</td>
<!-- END pic_column -->
</tr>
<tr>
<!-- BEGIN pic_option_column -->
	<!-- <td class="row2 row-center"><input type="radio" name="pic_select" value="{pic_row.pic_option_column.S_OPTIONS_PIC}" /></td> -->
<!-- END pic_option_column -->
</tr>

<!-- END pic_row -->
<tr><td class="catBottom tdalignc" colspan="{S_COLSPAN}">&nbsp;&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN upload_allowed -->
<br />
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_PICS}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td class="row1h row-center">
		<span class="genmed">
			{SELECT_CAT}&nbsp;
			<input type="submit" class="liteoption" value="{L_GO}" name="pic_upload" />
		</span>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END upload_allowed -->

</form>

<br />
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE overall_footer.tpl -->