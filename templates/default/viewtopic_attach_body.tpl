<!-- BEGIN attach -->
<div align="center">
<br />
<!-- BEGIN denyrow -->
<table class="forumline" width="95%" align="center" cellspacing="0">
<tr><td width="100%" class="row1 row-center"><b><span class="gen">{postrow.attach.denyrow.L_DENIED}</span></b></td></tr>
</table>
<!-- END denyrow -->
<!-- BEGIN cat_stream -->
<table class="forumline" width="95%" align="center" cellspacing="0">
<tr><td width="100%" colspan="2" class="row-header"><span>{postrow.attach.cat_stream.DOWNLOAD_NAME}</span></td></tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_DESCRIPTION}:</span></td>
	<td width="75%" class="row2">{postrow.attach.cat_stream.COMMENT}&nbsp;</td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_FILESIZE}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_stream.FILESIZE} {postrow.attach.cat_stream.SIZE_VAR}</span></td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{postrow.attach.cat_stream.L_DOWNLOADED_VIEWED}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_stream.L_DOWNLOAD_COUNT}</span></td>
</tr>
<tr>
	<td colspan="2" class="row1 row-center"><br />
	<object id="wmp" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,0,0,0" standby="Loading Microsoft Windows Media Player components..." type="application/x-oleobject">
	<param name="FileName" value="{postrow.attach.cat_stream.U_DOWNLOAD_LINK}">
	<param name="ShowControls" value="1">
	<param name="ShowDisplay" value="0">
	<param name="ShowStatusBar" value="1">
	<param name="AutoSize" value="1">
	<param name="AutoStart" value="0">
	<param name="Visible" value="1">
	<param name="AnimationStart" value="0">
	<param name="Loop" value="0">
	<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/windows95/downloads/contents/wurecommended/s_wufeatured/mediaplayer/default.asp" src="{postrow.attach.cat_stream.U_DOWNLOAD_LINK}" name=MediaPlayer2 showcontrols=1 showdisplay=0 showstatusbar=1 autosize=1 autostart=0 visible=1 animationatstart=0 loop=0></embed>
	</object> <br /><br />
	</td>
</tr>
</table>
<!-- END cat_stream -->
<!-- BEGIN cat_swf -->
<table class="forumline" width="95%" align="center" cellspacing="0">
<tr><td width="100%" colspan="2" class="row-header"><span>{postrow.attach.cat_swf.DOWNLOAD_NAME}</span></td></tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_DESCRIPTION}:</span></td>
	<td width="75%" class="row2">{postrow.attach.cat_swf.COMMENT}&nbsp;</td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_FILESIZE}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_swf.FILESIZE} {postrow.attach.cat_swf.SIZE_VAR}</span></td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{postrow.attach.cat_swf.L_DOWNLOADED_VIEWED}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_swf.L_DOWNLOAD_COUNT}</span></td>
</tr>
<tr>
	<td colspan="2" class="row1 row-center"><br />
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="{postrow.attach.cat_swf.WIDTH}" height="{postrow.attach.cat_swf.HEIGHT}">
	<param name=movie value="{postrow.attach.cat_swf.U_DOWNLOAD_LINK}">
	<param name=loop value=1>
	<param name=quality value=high>
	<param name=scale value=noborder>
	<param name=wmode value=transparent>
	<param name=bgcolor value=#000000>
	<embed src="{postrow.attach.cat_swf.U_DOWNLOAD_LINK}" loop=1 quality=high scale=noborder wmode=transparent bgcolor=#000000  width="{postrow.attach.cat_swf.WIDTH}" height="{postrow.attach.cat_swf.HEIGHT}" type="application/x-shockwave-flash" pluginspace="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
	</object><br /><br />
	</td>
</tr>
</table>

<!-- END cat_swf -->
<!-- BEGIN cat_images -->
<table class="forumline" width="95%" align="center" cellspacing="0">
<tr><td width="100%" colspan="2" class="row-header"><span>{postrow.attach.cat_images.DOWNLOAD_NAME}</span></td></tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_DESCRIPTION}:</span></td>
	<td width="75%" class="row2">{postrow.attach.cat_images.COMMENT}&nbsp;</td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_FILESIZE}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_images.FILESIZE} {postrow.attach.cat_images.SIZE_VAR}</span></td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{postrow.attach.cat_images.L_DOWNLOADED_VIEWED}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_images.L_DOWNLOAD_COUNT}</span></td>
</tr>
<tr>
	<td colspan="2" class="row1 row-center"><br />{postrow.attach.cat_images.IMG_CODE}<br /><br /></td>
</tr>
</table>
<!-- END cat_images -->
<!-- BEGIN cat_thumb_images -->
<table class="forumline" width="95%" align="center" cellspacing="0">
<tr><td width="100%" colspan="2" class="row-header"><span>{postrow.attach.cat_thumb_images.DOWNLOAD_NAME}</span></td></tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_DESCRIPTION}:</span></td>
	<td width="75%" class="row2">{postrow.attach.cat_thumb_images.COMMENT}&nbsp;</td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_FILESIZE}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_thumb_images.FILESIZE} {postrow.attach.cat_thumb_images.SIZE_VAR}</span></td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{postrow.attach.cat_thumb_images.L_DOWNLOADED_VIEWED}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.cat_thumb_images.L_DOWNLOAD_COUNT}</span></td>
</tr>
<tr>
	<td colspan="2" class="row1 row-center"><br /><a href="{postrow.attach.cat_thumb_images.IMG_SRC}" target="_blank"><img src="{postrow.attach.cat_thumb_images.IMG_THUMB_SRC}" alt="{postrow.attach.cat_thumb_images.DOWNLOAD_NAME}" /></a><br /><br /></td>
</tr>
</table>
<!-- END cat_thumb_images -->
<!-- BEGIN attachrow -->
<table class="forumline" width="95%" align="center" cellspacing="0">
<tr><td width="100%" colspan="3" class="row-header"><span>{postrow.attach.attachrow.DOWNLOAD_NAME}</span></td></tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_DESCRIPTION}:</span></td>
	<td width="75%" class="row2">{postrow.attach.attachrow.COMMENT}&nbsp;</td>
	<td rowspan="4" width="10%" class="row1 row-center">{postrow.attach.attachrow.S_UPLOAD_IMAGE}<br /><a href="{postrow.attach.attachrow.U_DOWNLOAD_LINK}" {postrow.attach.attachrow.TARGET_BLANK} class="genmed"><b>{L_DOWNLOAD}</b></a></td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_FILENAME}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.attachrow.DOWNLOAD_NAME}</span></td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{L_FILESIZE}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.attachrow.FILESIZE} {postrow.attach.attachrow.SIZE_VAR}</span></td>
</tr>
<tr>
	<td width="15%" class="row1"><span class="genmed">{postrow.attach.attachrow.L_DOWNLOADED_VIEWED}:</span></td>
	<td width="75%" class="row2"><span class="genmed">{postrow.attach.attachrow.L_DOWNLOAD_COUNT}</span></td>
</tr>
</table>
<!-- END attachrow -->
<br />
</div>
<!-- END attach -->