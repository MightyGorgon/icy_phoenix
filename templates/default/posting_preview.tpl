<script type="text/javascript">
// <![CDATA[
function img_popup(image_url, image_width, image_height, popup_rand)
{
	screenwidth = false;
	screenwidth = screen.Width;
	if (!screenwidth)
	{
		screenwidth = window.outerWidth;
	}

	screenheight = false;
	screenheight = screen.Height;
	if (!screenheight)
	{
		screenheight = window.outerHeight;
	}

	if (screenwidth < (image_width + 30) || screenheight < (image_height + 30) || image_width == null || image_height == null)
	{
		window.open(image_url,'limit_image_mod_popup_img_' + popup_rand,'resizable=yes,top=0,left=0,screenX=0,screenY=0,scrollbars=yes', false);
	}
	else
	{
		window.open(image_url,'limit_image_mod_popup_img_' + popup_rand,'resizable=yes,top=0,left=0,screenX=0,screenY=0,height=' + (image_height + 30) + ',width=' + (image_width + 30), false);
	}
}
// ]]>
</script>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_PREVIEW}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row-post" width="100%">
		<div class="post-subject">{PREVIEW_SUBJECT}&nbsp;</div>
		<div class="post-text post-text-hide-flow">
			{PREVIEW_MESSAGE}
			<!-- BEGIN postrow -->
			<br />
			{ATTACHMENTS}
			<!-- END postrow -->
		</div>
		<div class="post-text post-text-hide-flow"><br /><br /><br />{USER_SIG}</div>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}