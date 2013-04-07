<!-- IF S_HIGHSLIDER and not S_NO_PICS -->

<script type="text/javascript">
// <![CDATA[
var {HS_GALLERY_ID} = {
	thumbnailId: '{HS_PIC_ID}',
	wrapperClassName: 'dark',
	captionEval: 'this.a.title',
	numberPosition: 'caption',
	useBox: true,
	width: 600,
	height: 450
};
// ]]>
</script>

<div class="highslide-gallery">
	<a class="highslide" id="{HS_PIC_ID}" href="{HS_PIC_FULL}" title="{HS_PIC_TITLE}" onclick="return hs.expand(this, {HS_GALLERY_ID})"><img src="{HS_PIC_THUMB}" alt="" /></a>
	<div class="hidden-container">
	<!-- BEGIN recent_pics -->
		<!-- BEGIN recent_detail -->
		<!-- IF not recent_pics.recent_detail.IS_FIRST_PIC -->
		<a class="highslide" href="{recent_pics.recent_detail.U_PIC_DL}" title="{recent_pics.recent_detail.TITLE}" onclick="return hs.expand(this, {HS_GALLERY_ID})"><img src="{recent_pics.recent_detail.THUMBNAIL}" alt="{recent_pics.recent_detail.TITLE}" /></a>
		<!-- ENDIF -->
		<!-- END recent_detail -->
	<!-- END recent_pics -->
	</div>
</div>

<!-- ELSE -->

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN no_pics -->
<tr><td align="center" height="50"><span class="gen">{L_NO_PICS}</span></td></tr>
<!-- END no_pics -->

<!-- BEGIN recent_pics -->
<tr>
	<!-- BEGIN recent_detail -->
	<td class="row1 row-center" width="{S_COL_WIDTH}">
		<span class="genmed" style="line-height:150%">
			<b>{recent_pics.recent_detail.TITLE}</b><br />
			<a href="{recent_pics.recent_detail.U_PIC}" {TARGET_BLANK}><img src="{recent_pics.recent_detail.THUMBNAIL}" alt="{recent_pics.recent_detail.TITLE}" title="{recent_pics.recent_detail.TITLE}" /></a><br />
			<b>{recent_pics.recent_detail.POSTER}</b><br />
			{recent_pics.recent_detail.TIME}
		</span>
		<br /><br />
	</td>
	<!-- END recent_detail -->
	<!-- BEGIN recent_no_detail -->
	<td class="row1 row-center"><span class="genmed" style="line-height: 150%">&nbsp;</span><br /><br /></td>
	<!-- END recent_no_detail -->
</tr>
<!-- END recent_pics -->

</table>

<!-- ENDIF -->
