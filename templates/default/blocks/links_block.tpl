<script type="text/javascript">
<!--
function links_me()
{
	window.open("links_popup.php", '_links_me', 'height=220,width=500,resizable=no,scrollbars=no');
}
//-->
</script>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<!-- BEGIN links_own1 -->
<tr><td align="center"><a href="javascript:links_me()"><img src="{U_SITE_LOGO}" alt="{SITENAME}" title="{SITENAME}"/></a><br /><br /></td></tr>
<!-- END links_own1 -->
<!-- BEGIN links_scroll -->
<tr>
	<td class="gen" height="100">
	<marquee id="links_block" behavior="scroll" direction="up" scrolldelay="100" height="80" scrollamount="2" loop="true" onmouseover="this.stop()" onmouseout="this.start()">
		<div class="center-block-text">
			<div class="gen">
			<br />
			<!-- BEGIN links_row -->
			<a href="{static.links_row.LINK_HREF}" target="_blank" onmouseover="document.all.links_block.stop()" onmouseout="document.all.links_block.start()"><img src="{scroll.links_row.LINK_LOGO_SRC}" alt="{scroll.links_row.LINK_TITLE}" title="{scroll.links_row.LINK_TITLE}" width="{SITE_LOGO_WIDTH}" height="{SITE_LOGO_HEIGHT}" border="0"  vspace="3" /></a><br /><br />
			<!-- END links_row -->
			<br />
			</div>
		</div>
	</marquee>
	</td>
</tr>
<!-- END links_scroll -->
<!-- BEGIN links_static -->
<tr>
	<td>
	<!-- BEGIN links_row -->
	<div class="genmed" style="text-align:center;"><a href="{static.links_row.LINK_HREF}" target="_blank"><img src="{static.links_row.LINK_LOGO_SRC}" alt="{static.links_row.LINK_TITLE}" title="{static.links_row.LINK_TITLE}" width="{SITE_LOGO_WIDTH}" height="{SITE_LOGO_HEIGHT}" border="0" vspace="3" /></a></div><br />
	<!-- END links_row -->
	</td>
</tr>
<!-- END links_static -->
<!-- BEGIN links_own2 -->
<tr><td align="center"><a href="javascript:links_me()"><img src="{U_SITE_LOGO}" alt="{SITENAME}" title="{SITENAME}"/></a><br /></td></tr>
<!-- END links_own2 -->
</table>