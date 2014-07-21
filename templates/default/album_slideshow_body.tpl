<!-- INCLUDE simple_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink"><span id="PicHeader">{PIC_TITLE}</span></span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td class="row1 row-center">
		<form name="slideshow" action="{U_SLIDESHOW}" method="post" onsubmit="return true;">
			<input type="submit" class="button" value="{L_SLIDESHOW_ONOFF}" style="width: 100px" /><br />
			<!-- BEGIN switch_slideshow_no_scripts -->
			<br /><span class="genmed"><b>{PIC_COUNT}</b></span><br />
			<!-- END switch_slideshow_no_scripts -->
			<div class="center-block">{U_PIC_L1}<img class="vs10px" id="SlideShowPic" src="{U_PIC}" border="0" alt="{PIC_TITLE}" title="{PIC_TITLE}" />{U_PIC_L2}</div>
		</form>
		<!-- IF U_PIC_CLICK --><span class="genmed">{U_PIC_CLICK}</span><!-- ENDIF -->
	</td>
</tr>
<tr>
	<td class="tw100pct tdalignc">
		<table>
			<!-- BEGIN switch_slideshow_no_scripts -->
			<tr>
				<td class="tw50pct tdalignr"><span class="genmed">{L_POSTER}:</span></td>
				<td><span class="genmed"><b>{POSTER}</b></span></td>
			</tr>
			<!-- END switch_slideshow_no_scripts -->
			<tr>
				<td class="tw50pct tdalignr"><span class="genmed">{L_PIC_TITLE}:</span></td>
				<td><b><span id="PicTitle" class="genmed">{PIC_TITLE}</span></b></td>
			</tr>
			<!-- BEGIN switch_slideshow_no_scripts -->
			<tr>
				<td class="tw50pct tdalignr"><span class="genmed">{L_PIC_DETAILS}:</span></td>
				<td><b><span class="genmed">{L_PIC_ID}:&nbsp;{PIC_ID}&nbsp;-&nbsp;{L_PIC_TYPE}:&nbsp;{PIC_TYPE}&nbsp;-&nbsp;{L_PIC_SIZE}:&nbsp;{PIC_SIZE}</span></b></td>
			</tr>
			<tr>
				<td class="tw50pct tdalignr"><span class="genmed">{L_POSTED}:</span></td>
				<td><b><span class="genmed">{PIC_TIME}</span></b></td>
			</tr>
			<tr>
				<td class="tw50pct tdalignr"><span class="genmed">{L_VIEW}:</span></td>
				<td><b><span class="genmed">{PIC_VIEW}</span></b></td>
			</tr>
			<!-- END switch_slideshow_no_scripts -->
			<tr>
				<td class="tw50pct tdalignr"><span class="genmed">{L_PIC_DESC}:</span></td>
				<td><b><span id="PicDes" class="genmed">{PIC_DESC}</span></b></td>
			</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />

<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}

<!-- INCLUDE simple_footer.tpl -->

