<!-- BEGIN catheader -->
{IMG_THL}{IMG_THC}<span class="forumlink">{catheader.L_PUBLIC_CATS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th height="25" nowrap="nowrap" colspan="2">&nbsp;{catheader.L_CATEGORY}&nbsp;</th>
	<!-- BEGIN thumb -->
	<th width="5%">&nbsp;{catheader.thumb.L_LAST_PIC_THUMB}&nbsp;</th>
	<!-- END thumb -->
	<!-- BEGIN total_pics -->
	<th width="5%">&nbsp;{catheader.total_pics.L_TOTAL_PICS}&nbsp;</th>
	<!-- END total_pics -->
	<!-- BEGIN total_comments -->
	<th width="5%">&nbsp;{catheader.total_comments.L_TOTAL_COMMENTS}&nbsp;</th>
	<!-- END total_comments -->
	<!-- BEGIN pics -->
	<th width="5%">&nbsp;{catheader.pics.L_PICS}&nbsp;</th>
	<!-- END pics -->
	<!-- BEGIN comments -->
	<th width="5%" nowrap="nowrap">&nbsp;{catheader.comments.L_COMMENTS}&nbsp;</th>
	<!-- END comments -->
	<!-- BEGIN last_comment -->
	<th width="15%" nowrap="nowrap">&nbsp;{catheader.last_comment.L_LAST_COMMENT_INFO}&nbsp;</th>
	<!-- END last_comment -->
	<!-- BEGIN last_pic -->
	<th nowrap="nowrap">&nbsp;{catheader.last_pic.L_LAST_PIC}&nbsp;</th>
	<!-- END last_pic -->
</tr>
<!-- END catheader -->

<!-- BEGIN catmain -->
	<!-- BEGIN catrow -->
	<tr>
		<td class="row1" style="padding-right:5px;"><img src="{catmain.catrow.CAT_IMG}" alt="" /></td>
		<td class="row1h{catmain.catrow.XS_NEW} row-forum" onclick="window.location.href='{catmain.catrow.U_VIEWCAT}'"height="50" width="100%">
			<span class="forumlink{catmain.catrow.XS_NEW}">
			<a href="{catmain.catrow.U_VIEWCAT}" class="forumlink{catmain.catrow.XS_NEW}">{catmain.catrow.CAT_TITLE}</a>
				<!-- BEGIN newpics -->
				<!--
				<img src="{catmain.catrow.newpics.I_NEWEST_PICS}" alt="{catmain.catrow.newpics.L_NEWEST_PICS}" title="{catmain.catrow.newpics.L_NEWEST_PICS}">
				-->
				<!-- END newpics -->
			</span>
			<span class="gensmall">{catmain.catrow.SLIDESHOW}</span>
			<br />
			<span class="genmed">{catmain.catrow.CAT_DESC}</span>
			<span class="moderators">{catmain.catrow.L_MODERATORS} {catmain.catrow.MODERATORS}&nbsp;</span>
			<!-- BEGIN subcat_link -->
				<span class="gensmall"><b>{catmain.catrow.subcat_link.L_LINKS}:</b> {catmain.catrow.subcat_link.LINKS}</span>
			<!-- END subcat_link -->
		</td>
		<!-- BEGIN thumb -->
		<td class="{COL0} row-center" ><span class="gensmall">{catmain.catrow.thumb.LAST_PIC_URL}</span></td>
		<!-- END thumb -->
		<!-- BEGIN total_pics -->
		<td class="{COL1} row-center" ><span class="gensmall">{catmain.catrow.total_pics.TOTAL_PICS}</span></td>
		<!-- END total_pics -->
		<!-- BEGIN total_comments -->
		<td class="{COL2} row-center" ><span class="gensmall">{catmain.catrow.total_comments.TOTAL_COMMENTS}</span></td>
		<!-- END total_comments -->
		<!-- BEGIN pics -->
		<td class="{COL3} row-center" ><span class="gensmall">{catmain.catrow.pics.PICS}</span></td>
		<!-- END pics -->
		<!-- BEGIN comments -->
		<td class="{COL4} row-center" ><span class="gensmall">{catmain.catrow.comments.COMMENTS}</span></td>
		<!-- END comments -->
		<!-- BEGIN last_comment -->
		<td class="{COL5} row-center" nowrap="nowrap" ><span class="gensmall">{catmain.catrow.last_comment.LAST_COMMENT_INFO}</span></td>
		<!-- END last_comment -->
		<!-- BEGIN last_pic -->
		<td class="{COL6} row-center" align="center" nowrap="nowrap" ><span class="gensmall">{catmain.catrow.last_pic.LAST_PIC_INFO}</span></td>
		<!-- END last_pic -->
	</tr>
	<!-- END catrow -->
<!-- END catmain -->

<!-- BEGIN catfooter -->
	<!-- BEGIN cat_public_footer -->
		<!-- BEGIN show_all_pics_link -->
		<tr>
			<td class="row1" height="50" colspan="{catfooter.cat_public_footer.FOOTER_COL_SPAN}" onMouseOver="this.className='row2';" onMouseOut="this.className='row1';">
				<span class="forumlink">
					&nbsp;{NAV_DOT}&nbsp;<a href="{U_ALBUM_ALLPICS}" class="cattitle">{L_ALBUM_ALLPICS}</a>
				</span>
			</td>
		</tr>
		<!-- END show_all_pics_link -->
		<!-- BEGIN show_otf_link -->
		<tr>
			<td class="row1" height="50" colspan="{catfooter.cat_public_footer.FOOTER_COL_SPAN}" onMouseOver="this.className='row2';" onMouseOut="this.className='row1';">
				<span class="forumlink">&nbsp;{NAV_DOT}&nbsp;<a href="{U_ALBUM_OTF}" class="cattitle">{L_ALBUM_OTF}</a></span>
			</td>
		</tr>
		<!-- END show_otf_link -->
		<tr>
			<td class="row1" height="50" colspan="{catfooter.cat_public_footer.FOOTER_COL_SPAN}" onMouseOver="this.className='row2';" onMouseOut="this.className='row1';">
				<span class="forumlink">&nbsp;{NAV_DOT}&nbsp;<a href="{U_ALBUM_HON}" class="cattitle">{L_ALBUM_HON}</a></span>
			</td>
		</tr>
		<tr>
			<td class="row1" height="50" colspan="{catfooter.cat_public_footer.FOOTER_COL_SPAN}" onMouseOver="this.className='row2';" onMouseOut="this.className='row1';">
				<span class="forumlink">&nbsp;{NAV_DOT}&nbsp;<a href="{U_ALBUM_RDF}" class="cattitle">{L_ALBUM_RDF}</a>&nbsp;|&nbsp;<a href="{U_ALBUM_RSS}" class="cattitle">{L_ALBUM_RSS}</a></span>
			</td>
		</tr>
		<!-- BEGIN show_personal_galleries_link -->
		<tr>
			<td class="row1" height="50" colspan="{catfooter.cat_public_footer.FOOTER_COL_SPAN}" onMouseOver="this.className='row2';" onMouseOut="this.className='row1';">
				<span class="forumlink">&nbsp;{NAV_DOT}&nbsp;<a href="{catfooter.cat_public_footer.U_USERS_PERSONAL_GALLERIES}" class="cattitle">{catfooter.cat_public_footer.L_USERS_PERSONAL_GALLERIES}</a></span>
				<br />
				<img src="{SPACER}" width="5" height="15" alt="" />
				<span class="gensmall">&nbsp;{NAV_DOT}&nbsp;<a href="{catfooter.cat_public_footer.U_YOUR_PERSONAL_GALLERY}" class="gensmall"><b>{catfooter.cat_public_footer.L_YOUR_PERSONAL_GALLERY}</b></a></span>
			</td>
		</tr>
		<!-- END show_personal_galleries_link -->
	<!-- END cat_public_footer -->
<!-- END catfooter -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br clear="all" />