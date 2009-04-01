<a name="TopPic"></a>

{IMG_THL}{IMG_THC}<span class="forumlink">{NEXT_PIC}&nbsp;&nbsp;{PIC_TITLE}&nbsp;&nbsp;{PREV_PIC}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center" width="100%">
		<span class="genmed"><br /><b>{PIC_COUNT}</b></span><br />
		{U_PIC_L1}<img src="{U_PIC}" border="0" vspace="10" alt="{PIC_TITLE}" title="{PIC_TITLE}" />{U_PIC_L2}<br />
		<span class="genmed">{U_PIC_CLICK}</span>
		<!-- BEGIN disable_pic_nuffed -->
		<br />
		<span class="genmed"><a href="{disable_pic_nuffed.U_PIC_UNNUFFED_CLICK}" class="genmed">{disable_pic_nuffed.L_PIC_UNNUFFED_CLICK}</a></span><br />
		<!-- END disable_pic_nuffed -->
	</td>
</tr>
<tr>
	<td class="row2" width="100%">
		<table width="100%" align="center" border="0" cellspacing="1" cellpadding="2">
			<tr>
				<td width="50%" align="right" valign="top"><span class="genmed">{L_POSTER}:</span></td>
				<td width="50%" align="left" valign="top"><span class="genmed"><b>{POSTER}</b></span></td>
			</tr>
			<tr>
				<td valign="top" align="right"><span class="genmed">{L_PIC_TITLE}:</span></td>
				<td valign="top" align="left"><b><span class="genmed">{PIC_TITLE}</span></b></td>
			</tr>
			<tr>
				<td valign="top" align="right"><span class="genmed">{L_PIC_DETAILS}:</span></td>
				<td valign="top" align="left"><b><span class="genmed">{L_PIC_ID}:&nbsp;{PIC_ID}&nbsp;-&nbsp;{L_PIC_TYPE}:&nbsp;{PIC_TYPE}&nbsp;-&nbsp;{L_PIC_SIZE}:&nbsp;{PIC_SIZE}</span></b></td>
			</tr>
			<tr>
				<td valign="top" align="right"><span class="genmed">{L_PIC_BBCODE}:</span></td>
				<td valign="top" align="left"><b><span class="genmed"><form name="select_all"><input name="BBCode" size="50" maxlength="100" value="{PIC_BBCODE}" type="text" readonly="readonly" onclick="javascript:this.form.BBCode.focus();this.form.BBCode.select();"/></form></span></b></td>
			</tr>
			<tr>
				<td valign="top" align="right"><span class="genmed">{L_POSTED}:</span></td>
				<td valign="top" align="left"><b><span class="genmed">{PIC_TIME}</span></b></td>
			</tr>
			<tr>
				<td valign="top" align="right"><span class="genmed">{L_VIEW}:</span></td>
				<td valign="top" align="left"><b><span class="genmed">{PIC_VIEW}</span></b></td>
			</tr>
			<tr>
				<td valign="top" align="right"><span class="genmed">{L_PIC_DESC}:</span></td>
				<td valign="top" align="left"><b><span class="genmed">{PIC_DESC}</span></b></td>
			</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{NUFFIMAGE_BOX}
<!-- BEGIN pics_nav -->

{IMG_THL}{IMG_THC}<span class="forumlink">{pics_nav.L_PICS_NAV}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN next -->
	<td width="20%" align="center">
		<a href="{pics_nav.next.U_PICS_LINK}"><img src="{pics_nav.next.U_PICS_THUMB}" {THUMB_SIZE} alt="{pics_nav.L_PICS_NAV_NEXT}" title="{pics_nav.L_PICS_NAV_NEXT}" vspace="10" /></a>
	</td>
	<!-- END next -->
	<td width="20%" align="center">
		<img src="{U_PIC_THUMB}" {THUMB_SIZE} border="5px" alt="{PIC_TITLE}" title="{PIC_TITLE}" vspace="10" style="color: #FF8866" />
	</td>
	<!-- BEGIN prev -->
	<td width="20%" align="center">
		<a href="{pics_nav.prev.U_PICS_LINK}"><img src="{pics_nav.prev.U_PICS_THUMB}" {THUMB_SIZE} alt="{pics_nav.L_PICS_NAV_PREV}" title="{pics_nav.L_PICS_NAV_PREV}" vspace="10" /></a>
	</td>
	<!-- END prev -->
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END pics_nav -->
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}