<script type="text/javascript">
<!--
var height1 = 8; // define the height of the color bar
var pas = 20; // define the number of color in the color bar
var width1 = Math.floor(-2 / 15 * pas + 6); // define the width of the color bar
var text1 = '';
var text2 = '';
var bbcb_mg_img_path = "{BBCB_MG_PATH_PREFIX}images/bbcb_mg/images/gif/";
var bbcb_mg_img_ext = ".gif";

function openAllSmiles()
{
	height = screen.height / 1.5;
	width = screen.width / 1.7;
	smiles = window.open('{U_MORE_SMILIES}','_phpbbsmilies','height=' + height + ',width=' + width + ',resizable=yes,scrollbars=yes');
	smiles.focus();
	return false;
}
//-->
</script>

<center>
<table class="forumline" width="650" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center">
<table width="100%" align="center" cellspacing="0" cellpadding="2" border="0">
<tr>
	<td>
		<div class="gennull">
			<span class="genmed">&nbsp;</span>
			<a href="javascript:BBC_Tag_Add('b')" accesskey="b"><img border="0" src="{BBCB_MG_IMG_PATH}bold{BBCB_MG_IMG_EXT}" name="bold_img" alt="{L_BBCB_MG_B}" title="{L_BBCB_MG_B}" class="bbimages" /></a>
			<a href="javascript:BBC_Tag_Add('i')" accesskey="i"><img border="0" src="{BBCB_MG_IMG_PATH}italic{BBCB_MG_IMG_EXT}" name="italic" alt="{L_BBCB_MG_I}" title="{L_BBCB_MG_I}" class="bbimages" /></a>
			<a href="javascript:BBC_Tag_Add('u')" accesskey="u"><img border="0" src="{BBCB_MG_IMG_PATH}under{BBCB_MG_IMG_EXT}" name="under" alt="{L_BBCB_MG_U}" title="{L_BBCB_MG_U}" class="bbimages" /></a>
			<a href="javascript:BBC_Tag_Add('strike')"><img border="0" src="{BBCB_MG_IMG_PATH}strike{BBCB_MG_IMG_EXT}" name="strik" alt="{L_BBCB_MG_S}" title="{L_BBCB_MG_S}" class="bbimages" /></a>

			<span class="genmed">&nbsp;</span>
			<a href="javascript:BBC_Tag_Add('quote')" accesskey="q"><img border="0" src="{BBCB_MG_IMG_PATH}quote{BBCB_MG_IMG_EXT}" name="quote" alt="{L_BBCB_MG_QUOTE}" title="{L_BBCB_MG_QUOTE}" class="bbimages" /></a>

			<span class="genmed">&nbsp;&nbsp;</span>
			<a href="javascript:BBC_Tag_Add('url')" accesskey="w"><img border="0" src="{BBCB_MG_IMG_PATH}url{BBCB_MG_IMG_EXT}" name="url" alt="{L_BBCB_MG_URL}" title="{L_BBCB_MG_URL}" class="bbimages" /></a>
			<a href="javascript:BBC_Tag_Add('email')" accesskey="e"><img border="0" src="{BBCB_MG_IMG_PATH}email{BBCB_MG_IMG_EXT}" name="email" alt="{L_BBCB_MG_EML}" title="{L_BBCB_MG_EML}" class="bbimages" /></a>

			<span class="genmed">&nbsp;&nbsp;</span>
			<a href="javascript:BBC_Tag_Add('img')" accesskey="p"><img border="0" src="{BBCB_MG_IMG_PATH}image_link{BBCB_MG_IMG_EXT}" name="img" alt="{L_BBCB_MG_IMG}" title="{L_BBCB_MG_IMG}" class="bbimages" /></a>
			<a href="javascript:BBC_Tag_Add('rainbow')"><img border="0" src="{BBCB_MG_IMG_PATH}grad{BBCB_MG_IMG_EXT}" name="rainb" alt="{L_BBCB_MG_GRAD}" title="{L_BBCB_MG_GRAD}" class="bbimages" /></a>

			<span class="genmed">&nbsp;&nbsp;</span>

		</div>
	</td>
</tr>
</table>
<table class="bbcbmg" width="600" align="center" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="100%" align="left">
		<table id="ColorPanel" width="100%" align="left" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td id="ColorUsed" width="10" onMouseOver="this.style.cursor='hand';" onclick="if(this.bgColor.length > 0) InsertTag2(this.bgColor)" align="center">
				<script type="text/javascript">document.write('<img height=' + height1 + ' src="{BBCB_MG_IMG_PATH}spacer.gif" width="10" border="1" />');</script>
			</td>
			<td width="5">
				<script type="text/javascript">document.write('<img height=' + height1 + ' src="{BBCB_MG_IMG_PATH}spacer.gif" width="5" />');</script>
			</td>
			<td id="ColorUsed1" width="10" onMouseOver="this.style.cursor='hand';" onclick="if(this.bgColor.length > 0) InsertTag2(this.bgColor)" align="center">
				<script type="text/javascript">document.write('<img height=' + height1 + ' src="{BBCB_MG_IMG_PATH}spacer.gif" width="10" border="1" />');</script>
			</td>
			<td width="5">
				<script type="text/javascript">document.write('<img height=' + height1 + ' src="{BBCB_MG_IMG_PATH}spacer.gif" width="5" />');</script>
			</td>
			<script type="text/javascript">rgb(pas, width1, height1, text1, text2, '{BBCB_MG_PATH_PREFIX}')</script>
		</tr>
		</table>
	</td>
</tr>
</table>
<table width="100%" align="center" cellspacing="0" cellpadding="2">
<tr>
	<td>
		<!-- BEGIN smilies -->
		<img src="{smilies.URL}" onmouseover="this.style.cursor='hand';" onclick="emoticon('{smilies.CODE}');" alt="{smilies.DESC}" title="{smilies.DESC}" />
		<!-- END smilies -->
		<!-- &nbsp;<input type="button" class="button" name="SmilesButt" value="{L_MORE_SMILIES}" onclick="openAllSmiles();" /> -->
	</td>
</tr>
</table>
	</td>
</tr>
</table>
</center>
