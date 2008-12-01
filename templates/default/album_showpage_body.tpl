<script type="text/javascript">
<!--
function checkFormComment()
{
	formErrors = false;

	//if ((document.forms['post'].message.value.length < 2) && (document.forms['post'].rate.value == -1))
	if (document.forms['post'].message.value.length < 2)
	{
		formErrors = "{L_COMMENT_NO_TEXT}";
	}
	else if (document.forms['post'].message.value.length > {S_MAX_LENGTH})
	{
		formErrors = "{L_COMMENT_TOO_LONG}";
	}

	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}

function checkFormRate()
{
	formErrors = false;
	if (document.ratingform.rating.value == -1)
	{
		formErrors = "{L_PLEASE_RATE_IT}";
	}

	if (formErrors)
	{
		alert(formErrors);
		return false;
	}
	else
	{
		return true;
	}
}
// -->
</script>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left">&nbsp;</td>
	<td align="right">{ALBUM_SEARCH_BOX}</td>
</tr>
</table>

<a name="TopPic"></a>

{IMG_THL}{IMG_THC}<center><span class="forumlink">{NEXT_PIC}&nbsp;&nbsp;{PIC_TITLE}&nbsp;&nbsp;{PREV_PIC}</span></center>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center">
		<!-- BEGIN switch_slideshow_enabled -->
		<form name="slideshowf" action="{U_SLIDESHOW}" method="post" onsubmit="return true;">
			<span class="gensmall">{SLIDESHOW_SELECT}</span>
			<input type="submit" class="button" value="{L_SLIDESHOW_ONOFF}" style="width: 120px" /><br />
		</form>
		<!-- END switch_slideshow_enabled -->
		<div class="center-block">
		<br /><span class="genmed"><b>{PIC_COUNT}</b></span><br />
		{U_PIC_L1}<img src="{U_PIC}" border="0" vspace="10" alt="{PIC_TITLE}" title="{PIC_TITLE}" />{U_PIC_L2}
		</div>
		<span class="genmed">{U_PIC_CLICK}</span><br />
		<!-- BEGIN pic_nuffed_enabled -->
		<span class="gensmall"><a href="{pic_nuffed_enabled.U_PIC_NUFFED_CLICK}">{pic_nuffed_enabled.L_PIC_NUFFED_CLICK}</a></span><br />
		<!-- END pic_nuffed_enabled -->
		<span class="gensmall">{U_COMMENT_WATCH_LINK}</span><br />
	</td>
</tr>
<tr>
	<td class="row1">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr><td colspan="2" width="100%" align="center" valign="top"><span class="genmed">{EDIT}&nbsp;{DELETE}&nbsp;{LOCK}&nbsp;{MOVE}&nbsp;{COPY}</span></td></tr>
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
			<td valign="top" align="left"><form name="select_all" action=""><input class="post" name="BBCode" size="50" maxlength="200" value="{PIC_BBCODE}" type="text" readonly="readonly" onClick="javascript:this.form.BBCode.focus();this.form.BBCode.select();" /></form></td>
		</tr>
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_POSTED}:</span></td>
			<td valign="top" align="left"><b><span class="genmed">{PIC_TIME}</span></b></td>
		</tr>
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_VIEW}:</span></td>
			<td valign="top" align="left"><b><span class="genmed">{PIC_VIEW}</span></b></td>
		</tr>
		<!-- BEGIN rate_switch -->
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_RATING}:</span></td>
			<td valign="top" align="left">
				<span class="genmed"><b>{PIC_RATING}</b></span>
				<!-- BEGIN rate_row -->
				<form name="ratingform" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkFormRate();">
					<select name="rating">
						<option value="-1">{S_RATE_MSG}</option>
						<!-- BEGIN rate_scale_row -->
						<option value="{rate_switch.rate_row.rate_scale_row.POINT}">{rate_switch.rate_row.rate_scale_row.POINT}</option>
						<!-- END rate_scale_row -->
					</select>&nbsp;
					<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
				</form>
				<!-- END rate_row -->
				<br />
			</td>
		</tr>
		<!-- END rate_switch -->
		<tr>
			<td valign="top" align="right"><span class="genmed">{L_PIC_DESC}:</span></td>
			<td valign="top" align="left"><b><span class="genmed">{PIC_DESC}</span></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN social_bookmarks -->
<br />
<!-- INCLUDE social_bookmarks_full.tpl -->
<!-- END social_bookmarks -->

<!-- BEGIN switch_exif_enabled -->
<!-- BEGIN exif_switch -->
<table width="100%" cellpadding="1" cellspacing="0" border="0" class="forumline">
<tr><th nowrap="nowrap" colspan="5">EXIF</th></tr>
<!-- BEGIN exif_data -->
<tr>
	<td class="row1" width="25%" align="right"><span class="genmed">{switch_exif_enabled.exif_switch.exif_data.EXIFc1}&nbsp;</span></td>
	<td class="row1" width="25%" ><b><span class="genmed">{switch_exif_enabled.exif_switch.exif_data.EXIFd1}</span></b></td>
	<td class="row1" width="25%" align="right"><span class="genmed">{switch_exif_enabled.exif_switch.exif_data.EXIFc2}&nbsp;</span></td>
	<td class="row1" width="25%" ><b><span class="genmed">{switch_exif_enabled.exif_switch.exif_data.EXIFd2}</span></b></td>
</tr>
<!-- END exif_data -->
</table>
<br />
<!-- END exif_switch -->
<!-- END switch_exif_enabled -->

<!-- BEGIN pics_nav -->
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{pics_nav.L_PICS_NAV}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<!-- BEGIN pics -->
	<td class="row1g row-center" width="20%" align="center">
		<center>
		<table><tr><td><div class="picshadow"><div class="picframe">
			<a href="{pics_nav.pics.U_PIC_LINK}"><img src="{pics_nav.pics.U_PIC_THUMB}" {THUMB_SIZE} alt="{pics_nav.pics.PIC_TITLE}" title="{pics_nav.pics.PIC_TITLE}" vspace="10" style="{pics_nav.pics.STYLE}" {pics_nav.pics.PIC_PREVIEW} /></a>
		</div></div></td></tr></table>
		</center>
	</td>
	<!-- END pics -->
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END pics_nav -->
<!-- BEGIN coment_switcharo_top -->
<br />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_COMMENTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th nowrap="nowrap" width="15%">{L_POSTER}</th>
	<th nowrap="nowrap" width="85%">{L_MESSAGE}</th>
</tr>
<!-- END coment_switcharo_top -->
<!-- BEGIN commentrow -->
<tr >
	<td class="row-post-author" nowrap="nowrap">
		<span class="post-name"><a name="{commentrow.ID}"></a>{commentrow.POSTER_NAME}</span><br />
		<div class="post-rank"><b>{commentrow.POSTER_RANK}</b><br />{commentrow.POSTER_RANK_IMAGE}</div>
		<span class="post-images">{commentrow.POSTER_AVATAR}</span>
		<div class="post-details">
			{commentrow.POSTER_ONLINE_STATUS_IMG}{commentrow.IP_IMG}{commentrow.AIM_IMG}{commentrow.YIM_IMG}{commentrow.MSNM_IMG}{commentrow.SKYPE_IMG}{commentrow.ICQ_IMG}<br />
			{commentrow.POSTER_JOINED}<br />
			{commentrow.POSTER_POSTS}<br />
			{commentrow.POSTER_FROM}
		</div>
	</td>
	<td class="row-post" width="100%" height="100%">
		<div class="post-buttons-top post-buttons">{commentrow.EDIT} {commentrow.DELETE}</div>
		<div class="post-subject">&nbsp;</div>
		<div class="post-text">{commentrow.TEXT}<br /></div>
		<div class="post-text"><br /><br /><br />_______________<br />{commentrow.POSTER_SIGNATURE}</div>
	</td>
</tr>
<tr>
	<td class="row-post-date">{commentrow.COMMENT_TIME}</td>
	<td class="row-post-buttons post-buttons">{commentrow.PROFILE_IMG}{commentrow.PM_IMG}{commentrow.EMAIL_IMG}{commentrow.WWW_IMG}</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" alt="" width="1" height="3" /></td></tr>
<!-- END commentrow -->
<!-- BEGIN switch_comment -->
<tr>
	<td class="cat" align="center" height="28" colspan="2">
		<form action="{S_ALBUM_ACTION}" method="post">
			<span class="gensmall">{L_ORDER}:</span>
			<select name="sort_order">
				<option {SORT_ASC} value='ASC'>{L_ASC}</option>
				<option {SORT_DESC} value='DESC'>{L_DESC}</option>
			</select>&nbsp;
			<input type="submit" name="submit" value="{L_SORT}" class="liteoption" />
		</form>
	</td>
</tr>
<!-- END switch_comment -->
<!-- BEGIN comment_switcharo_bottom -->
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END comment_switcharo_bottom -->

<!-- BEGIN switch_comment_post -->
<br />
<form name="post" action="{S_ALBUM_ACTION}" method="post" onsubmit="return checkFormComment();">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_POST_YOUR_COMMENT}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN logout -->
<tr>
	<td class="row1"><span class="genmed">{L_USERNAME}</span></td>
	<td class="row2"><input class="post" type="text" name="comment_username" size="32" maxlength="32" /></td>
</tr>
<!-- END logout -->
<tr>
	<td class="row1 row-center" width="20%"><br /><br /><br /><br />{BBCB_SMILEYS_MG}</td>
	<td class="row2" valign="top">
		{BBCB_MG}
		<textarea name="message" rows="15" cols="35" wrap="virtual" style="width:96%" tabindex="3" class="post" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{S_MESSAGE}</textarea>
	</td>
</tr>
<tr><td class="cat" align="center" colspan="2" height="28"><input class="mainoption" type="submit" name="submit" value="{L_SUBMIT}" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END switch_comment_post -->

<!-- BEGIN switch_comment -->
<div style="text-align:left;"><div style="float:right;text-align:right;"><span class="pagination">{PAGINATION}</span></div><span class="gensmall">{PAGE_NUMBER}</span></div>
<!--
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td align="right" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
-->
<!-- END switch_comment -->
<br />
{CM_PAGINATION}
<!-- You must keep my copyright notice visible with its original content -->
{ALBUM_COPYRIGHT}