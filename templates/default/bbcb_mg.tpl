<script type="text/javascript">
// <![CDATA[
s_help = "{L_BBCODE_S_HELP}";
s_s_help = "{L_BBCODE_S_HELP}";

{JAVASCRIPT_LANG_VARS}

var bbcb_mg_img_path = "{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/bbcb_mg/images/gif/";
var bbcb_mg_img_ext = ".gif";

function openAllSmiles()
{
	height = screen.height / 1.5;
	width = screen.width / 1.7;
	smiles = window.open('{U_MORE_SMILIES}','_phpbbsmilies','height=' + height + ',width=' + width + ',resizable=yes,scrollbars=yes');
	smiles.focus();
	return false;
}
// ]]>
</script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/bbcb_mg.js"></script>

<table width="99%" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td width="100%" align="left" nowrap="nowrap">
		&nbsp;
		<select style="height: 18px;" name="ft" onchange="BBCft(); this.selectedIndex=0;" onmouseover="helpline('ft')">
			<option value="" class="genmed" style="font-family:Verdana;">{L_FONT_TYPE}</option>
			<option value="Arial" class="genmed" style="font-family:Arial;">{L_FONT_ARIAL}</option>
			<option value="Comic Sans MS" class="genmed" style="font-family:'Comic Sans MS';">{L_FONT_COMIC_SANS_MS}</option>
			<option value="Courier New" class="genmed" style="font-family:'Courier New';">{L_FONT_COURIER_NEW}</option>
			<option value="Impact" class="genmed" style="font-family:Impact;">{L_FONT_IMPACT}</option>
			<option value="Lucida Console" class="genmed" style="font-family:'Lucida Console';">{L_FONT_LUCIDA_CONSOLE}</option>
			<option value="Lucida Sans Unicode" class="genmed" style="font-family:'Lucida Sans Unicode';">{L_FONT_LUCIDA_SANS_UNICODE}</option>
			<option value="Microsoft Sans Serif" class="genmed" style="font-family:'Microsoft Sans Serif';">{L_FONT_MICROSOFT_SANS_SERIF}</option>
			<option value="Symbol" class="genmed" style="font-family:Symbol;">{L_FONT_SYMBOL}</option>
			<option value="Tahoma" class="genmed" style="font-family:Tahoma;">{L_FONT_TAHOMA}</option>
			<option value="Times New Roman" class="genmed" style="font-family:'Times New Roman';">{L_FONT_TIMES_NEW_ROMAN}</option>
			<option value="Traditional Arabic" class="genmed" style="font-family:'Traditional Arabic';">{L_FONT_TRADITIONAL_ARABIC}</option>
			<option value="Trebuchet MS" class="genmed" style="font-family:'Trebuchet MS';">{L_FONT_TREBUCHET_MS}</option>
			<option value="Verdana" class="genmed" style="font-family:Verdana;">{L_FONT_VERDANA}</option>
			<option value="Webdings" class="genmed" style="font-family:Webdings;">{L_FONT_WEBDINGS}</option>
			<option value="Wingdings" class="genmed" style="font-family:Wingdings;">{L_FONT_WINGDINGS}</option>
		</select>
		&nbsp;
		<select style="height: 18px;" name="fs" onchange="BBCfs(); this.selectedIndex=0;" onmouseover="helpline('fs')">
			<option value="" class="genmed" style="color:{T_FONTCOLOR1}">{L_FONT_SIZE}</option>
			<option value="8" class="genmed" style="font-size:8px;">{L_FONT_TINY}</option>
			<option value="10" class="genmed" style="font-size:10px;">{L_FONT_SMALL}</option>
			<option value="12" class="genmed" style="font-size:12px;">{L_FONT_NORMAL}</option>
			<option value="14" class="genmed" style="font-size:14px;">{L_FONT_LARGE}</option>
			<option value="18" class="genmed" style="font-size:18px;">{L_FONT_HUGE}</option>
			<option value="24" class="genmed" style="font-size:24px;">{L_FONT_XL}</option>
		</select>
		&nbsp;
		<select style="height: 18px;" name="fc" onchange="BBCfc(); this.selectedIndex=0;" onmouseover="helpline('fc')">
			<option value="" class="genmed">{L_FONT_COLOR}</option>
			<option style="color: brown;" value="brown" class="genmed">{L_COLOR_BROWN}</option>
			<option style="color: chocolate;" value="chocolate" class="genmed">{L_COLOR_CHOCOLATE}</option>
			<option style="color: darkred;" value="darkred" class="genmed">{L_COLOR_DARK_RED}</option>
			<option style="color: crimson;" value="crimson" class="genmed">{L_COLOR_CRIMSON}</option>
			<option style="color: red;" value="red" class="genmed">{L_COLOR_RED}</option>
			<option style="color: #ff8866;" value="#ff8866" class="genmed">{L_COLOR_LIGHT_ORANGE}</option>
			<option style="color: #ff5500;" value="#ff5500" class="genmed">{L_COLOR_POWER_ORANGE}</option>
			<option style="color: orange;" value="orange" class="genmed">{L_COLOR_ORANGE}</option>
			<option style="color: gold;" value="gold" class="genmed">{L_COLOR_GOLD}</option>
			<option style="color: peachpuff;" value="peachpuff" class="genmed">{L_COLOR_PEACH}</option>
			<option style="color: yellow;" value="yellow" class="genmed">{L_COLOR_YELLOW}</option>
			<option style="color: #00ff00;" value="#00ff00" class="genmed">{L_COLOR_LIGHT_GREEN}</option>
			<option style="color: seagreen;" value="seagreen" class="genmed">{L_COLOR_SEA_GREEN}</option>
			<option style="color: green;" value="green" class="genmed">{L_COLOR_GREEN}</option>
			<option style="color: olive;" value="olive" class="genmed">{L_COLOR_OLIVE}</option>
			<option style="color: darkgreen;" value="darkgreen" class="genmed">{L_COLOR_DARKGREEN}</option>
			<option style="color: #ddeeff;" value="#ddeeff" class="genmed">{L_COLOR_LIGHT_CYAN}</option>
			<option style="color: #aaccee;" value="#aaccee" class="genmed">{L_COLOR_LIGHT_BLUE}</option>
			<option style="color: cadetblue;" value="cadetblue" class="genmed">{L_COLOR_CADET_BLUE}</option>
			<option style="color: cyan;" value="cyan" class="genmed">{L_COLOR_CYAN}</option>
			<option style="color: #666699;" value="#666699" class="genmed">{L_COLOR_TURQUOISE}</option>
			<option style="color: blue;" value="blue" class="genmed">{L_COLOR_BLUE}</option>
			<option style="color: deepskyblue;" value="deepskyblue" class="genmed">{L_COLOR_DEEPSKYBLUE}</option>
			<option style="color: midnightblue;" value="midnightblue" class="genmed">{L_COLOR_MIDNIGHTBLUE}</option>
			<option style="color: darkblue;" value="darkblue" class="genmed">{L_COLOR_DARK_BLUE}</option>
			<option style="color: indigo;" value="indigo" class="genmed">{L_COLOR_INDIGO}</option>
			<option style="color: darkorchid;" value="darkorchid" class="genmed">{L_COLOR_DARK_ORCHID}</option>
			<option style="color: violet;" value="violet" class="genmed">{L_COLOR_VIOLET}</option>
			<option style="color: white;" value="white" class="genmed">{L_COLOR_WHITE}</option>
			<option style="color: lightgrey;" value="lightgrey" class="genmed">{L_COLOR_LIGHT_GREY}</option>
			<option style="color: silver;" value="silver" class="genmed">{L_COLOR_SILVER}</option>
			<option style="color: darkgrey;" value="darkgrey" class="genmed">{L_COLOR_DARK_GREY}</option>
			<option style="color: gray;" value="gray" class="genmed">{L_COLOR_GRAY}</option>
			<option style="color: black;" value="black" class="genmed">{L_COLOR_BLACK}</option>
		</select>
		<!-- IF S_COLORPICKER -->
		<a href="{U_BBCODE_COLORPICKER}" onclick="popup('{U_BBCODE_COLORPICKER}', 530, 420, '_color_picker'); return false;"><img src="{BBCB_MG_IMG_PATH}colorpicker{BBCB_MG_IMG_EXT}" onmouseover="helpline('fc')" alt="{L_BBCB_MG_COLOR_PICKER}" title="{L_BBCB_MG_COLOR_PICKER}" style="vertical-align: middle;" class="bbimages" /></a>
		<!-- ENDIF -->
	</td>
	<td width="20%" align="right" nowrap="nowrap" valign="middle">
		<div class="genmed"><a href="{U_BBCODE_HELP}" class="gensmall" target="_blank"><img src="{BBCB_MG_IMG_PATH}help{BBCB_MG_IMG_EXT}" name="help" onmouseover="helpline('help')" alt="{L_BBCODE_HELP}" title="{L_BBCODE_HELP}" /></a></div>
		<!-- <span class="moderators">&nbsp;<a href="javascript:bbstyle(-1)" onmouseover="helpline('a')">{L_BBCODE_CLOSE_TAGS}</a>&nbsp;</span> -->
	</td>
</tr>
<tr>
	<td width="100%" align="left" valign="middle" colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div class="gennull">
					<span class="genmed">&nbsp;</span>
					<a href="javascript:BBCleft()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}left{BBCB_MG_IMG_EXT}" name="left" onmouseover="helpline('left')" alt="{L_BBCB_MG_L}" title="{L_BBCB_MG_L}" class="bbimages" /></a>
					<a href="javascript:BBCcenter()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}center{BBCB_MG_IMG_EXT}" name="center" onmouseover="helpline('center')" alt="{L_BBCB_MG_C}" title="{L_BBCB_MG_C}" class="bbimages" /></a>
					<a href="javascript:BBCright()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}right{BBCB_MG_IMG_EXT}" name="right" onmouseover="helpline('right')" alt="{L_BBCB_MG_R}" title="{L_BBCB_MG_R}" class="bbimages" /></a>
					<a href="javascript:BBCjustify()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}justify{BBCB_MG_IMG_EXT}" name="justify" onmouseover="helpline('justify')" alt="{L_BBCB_MG_J}" title="{L_BBCB_MG_J}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCbold()" accesskey="b"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}bold{BBCB_MG_IMG_EXT}" name="bold_img" onmouseover="helpline('b')" alt="{L_BBCB_MG_B}" title="{L_BBCB_MG_B}" class="bbimages" /></a>
					<a href="javascript:BBCitalic()" accesskey="i"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}italic{BBCB_MG_IMG_EXT}" name="italic" onmouseover="helpline('i')" alt="{L_BBCB_MG_I}" title="{L_BBCB_MG_I}" class="bbimages" /></a>
					<a href="javascript:BBCunder()" accesskey="u"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}under{BBCB_MG_IMG_EXT}" name="under" onmouseover="helpline('u')" alt="{L_BBCB_MG_U}" title="{L_BBCB_MG_U}" class="bbimages" /></a>
					<a href="javascript:BBCstrike()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}strike{BBCB_MG_IMG_EXT}" name="strik" onmouseover="helpline('strike')" alt="{L_BBCB_MG_S}" title="{L_BBCB_MG_S}" class="bbimages" /></a>
					<a href="javascript:BBCsup()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}sup{BBCB_MG_IMG_EXT}" name="supscript" onmouseover="helpline('sup')" alt="{L_BBCB_MG_SUP}" title="{L_BBCB_MG_SUP}" class="bbimages" /></a>
					<a href="javascript:BBCsub()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}sub{BBCB_MG_IMG_EXT}" name="subs" onmouseover="helpline('sub')" alt="{L_BBCB_MG_SUB}" title="{L_BBCB_MG_SUB}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBClist()" accesskey="l"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}list{BBCB_MG_IMG_EXT}" name="listdf" onmouseover="helpline('list')" alt="{L_BBCB_MG_LST}" title="{L_BBCB_MG_LST}" class="bbimages" /></a>
					<a href="javascript:BBClistO()" accesskey="l"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}list_o{BBCB_MG_IMG_EXT}" name="listodf" onmouseover="helpline('listo')" alt="{L_BBCB_MG_LST}" title="{L_BBCB_MG_LSTO}" class="bbimages" /></a>
					<a href="javascript:BBCbullet()" accesskey="*"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}list_ast{BBCB_MG_IMG_EXT}" name="listbullet" onmouseover="helpline('bullet')" alt="{L_BBCB_MG_BULLET}" title="{L_BBCB_MG_BULLET}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBChl()" ><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}highlight{BBCB_MG_IMG_EXT}" name="highlight" onmouseover="helpline('highlight')" alt="{L_BBCB_MG_HIGHLIGHT}" title="{L_BBCB_MG_HIGHLIGHT}" class="bbimages" /></a>
					<a href="javascript:BBChr()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}hr{BBCB_MG_IMG_EXT}" name="hr" onmouseover="helpline('hr')" alt="{L_BBCB_MG_HR}" title="{L_BBCB_MG_HR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<!-- IF S_ACTIVE_CONTENT -->
					<a href="javascript:BBCflash()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}flash{BBCB_MG_IMG_EXT}" name="flash" onmouseover="helpline('flash')" alt="{L_BBCB_MG_FLSH}" title="{L_BBCB_MG_FLSH}" class="bbimages" /></a>
					<a href="javascript:BBCvideo()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}video{BBCB_MG_IMG_EXT}" name="video" onmouseover="helpline('video')" alt="{L_BBCB_MG_VID}" title="{L_BBCB_MG_VID}" class="bbimages" /></a>
					<a href="javascript:BBCgooglevideo()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}googlevideo{BBCB_MG_IMG_EXT}" name="googlevideo" onmouseover="helpline('googlevideo')" alt="{L_BBCB_MG_GVID}" title="{L_BBCB_MG_GVID}" class="bbimages" /></a>
					<a href="javascript:BBCyoutube()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}youtube{BBCB_MG_IMG_EXT}" name="youtube" onmouseover="helpline('youtube')" alt="{L_BBCB_MG_YOUTUBE}" title="{L_BBCB_MG_YOUTUBE}" class="bbimages" /></a>
					<a href="javascript:BBCram()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}ram{BBCB_MG_IMG_EXT}" name="ram" onmouseover="helpline('ram')" alt="{L_BBCB_MG_RAM}" title="{L_BBCB_MG_RAM}" class="bbimages" /></a>
					<a href="javascript:BBCquick()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}quick{BBCB_MG_IMG_EXT}" name="quick" onmouseover="helpline('quick')" alt="Quicktime" title="Quicktime" class="bbimages" /></a>
					<a href="javascript:BBCstream()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}sound{BBCB_MG_IMG_EXT}" name="stream" onmouseover="helpline('stream')" alt="{L_BBCB_MG_STRM}" title="{L_BBCB_MG_STRM}" class="bbimages" /></a>
					<a href="javascript:BBCemff()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}emff{BBCB_MG_IMG_EXT}" name="emff" onmouseover="helpline('emff')" alt="{L_BBCB_MG_EMFF}" title="{L_BBCB_MG_EMFF}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>
					<!-- ENDIF -->

				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="gennull">
					<span class="genmed">&nbsp;</span>
					<a href="javascript:BBCquote()" accesskey="q"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}quote{BBCB_MG_IMG_EXT}" name="quote" onmouseover="helpline('quote')" alt="{L_BBCB_MG_QUOTE}" title="{L_BBCB_MG_QUOTE}" class="bbimages" /></a>
					<a href="javascript:BBCcode()" accesskey="c"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}code{BBCB_MG_IMG_EXT}" name="code" onmouseover="helpline('code')" alt="{L_BBCB_MG_CODE}" title="{L_BBCB_MG_CODE}" class="bbimages" /></a>
					<a href="javascript:BBCphpbbmod()" accesskey="p"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}code{BBCB_MG_IMG_EXT}" name="phpbbmod" onmouseover="helpline('phpbbmod')" alt="{L_BBCB_MG_PHPBBMOD}" title="{L_BBCB_MG_PHPBBMOD}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCurl()" accesskey="w"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}url{BBCB_MG_IMG_EXT}" name="url" onmouseover="helpline('url')" alt="{L_BBCB_MG_URL}" title="{L_BBCB_MG_URL}" class="bbimages" /></a>
					<a href="javascript:BBCmail()" accesskey="e"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}email{BBCB_MG_IMG_EXT}" name="email" onmouseover="helpline('mail')" alt="{L_BBCB_MG_EML}" title="{L_BBCB_MG_EML}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<!-- IF S_PIC_UPLOAD -->
					<a href="{U_BBCODE_POSTICYIMAGE}" onclick="popup('{U_BBCODE_POSTICYIMAGE}', 700, 500, '_upload_image'); return false;"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}post_icy_image{BBCB_MG_IMG_EXT}" name="posticyimage" onmouseover="helpline('posticyimage')" alt="{L_BBCB_MG_POSTICYIMAGE}" title="{L_BBCB_MG_POSTICYIMAGE}" class="bbimages" /></a>
					<!-- ENDIF -->
					<!-- IF S_POSTIMAGE_ORG -->
					<a href="{U_BBCODE_POSTIMAGE}" accesskey="g"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}image_add{BBCB_MG_IMG_EXT}" name="uploadimg" onmouseover="helpline('image_upload')" alt="{L_BBCB_MG_UPLOAD_IMG}" title="{L_BBCB_MG_UPLOAD_IMG}" class="bbimages" /></a>
					<!-- ENDIF -->
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCimgl()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}image_linkl{BBCB_MG_IMG_EXT}" name="imgl" onmouseover="helpline('imgl')" alt="{L_BBCB_MG_IMGL}" title="{L_BBCB_MG_IMGL}" class="bbimages" /></a>
					<a href="javascript:BBCimg()" accesskey="p"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}image_link{BBCB_MG_IMG_EXT}" name="img" onmouseover="helpline('img')" alt="{L_BBCB_MG_IMG}" title="{L_BBCB_MG_IMG}" class="bbimages" /></a>
					<a href="javascript:BBCimgr()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}image_linkr{BBCB_MG_IMG_EXT}" name="imgr" onmouseover="helpline('imgr')" alt="{L_BBCB_MG_IMGR}" title="{L_BBCB_MG_IMGR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCalbumimgl()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}image_gall{BBCB_MG_IMG_EXT}" name="albumimgl" onmouseover="helpline('albumimgl')" alt="{L_BBCB_MG_ALBUMIMGL}" title="{L_BBCB_MG_ALBUMIMGL}" class="bbimages" /></a>
					<a href="javascript:BBCalbumimg()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}image_gal{BBCB_MG_IMG_EXT}" name="albumimg" onmouseover="helpline('albumimg')" alt="{L_BBCB_MG_ALBUMIMG}" title="{L_BBCB_MG_ALBUMIMG}" class="bbimages" /></a>
					<a href="javascript:BBCalbumimgr()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}image_galr{BBCB_MG_IMG_EXT}" name="albumimgr" onmouseover="helpline('albumimgr')" alt="{L_BBCB_MG_ALBUMIMGR}" title="{L_BBCB_MG_ALBUMIMGR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCspoiler()" ><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}spoiler{BBCB_MG_IMG_EXT}" name="spoiler" onmouseover="helpline('spoiler')" alt="{L_BBCB_MG_SPOILER}" title="{L_BBCB_MG_SPOILER}" class="bbimages" /></a>
					<a href="javascript:BBCcell()" ><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}cell{BBCB_MG_IMG_EXT}" name="cell" onmouseover="helpline('cell')" alt="{L_BBCB_MG_CELL}" title="{L_BBCB_MG_CELL}" class="bbimages" /></a>
					<a href="javascript:BBCfade()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}fade{BBCB_MG_IMG_EXT}" name="fade" onmouseover="helpline('fade')" alt="{L_BBCB_MG_FADE}" title="{L_BBCB_MG_FADE}" class="bbimages" /></a>
					<a href="javascript:BBCgrad()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}grad{BBCB_MG_IMG_EXT}" name="rainb" onmouseover="helpline('grad')" alt="{L_BBCB_MG_GRAD}" title="{L_BBCB_MG_GRAD}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCmarqd()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}marqd{BBCB_MG_IMG_EXT}" name="marqd" onmouseover="helpline('marqd')" alt="{L_BBCB_MG_MD}" title="{L_BBCB_MG_MD}" class="bbimages" /></a>
					<a href="javascript:BBCmarqu()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}marqu{BBCB_MG_IMG_EXT}" name="marqu" onmouseover="helpline('marqu')" alt="{L_BBCB_MG_MU}" title="{L_BBCB_MG_MU}" class="bbimages" /></a>
					<a href="javascript:BBCmarql()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}marql{BBCB_MG_IMG_EXT}" name="marql" onmouseover="helpline('marql')" alt="{L_BBCB_MG_ML}" title="{L_BBCB_MG_ML}" class="bbimages" /></a>
					<a href="javascript:BBCmarqr()"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}marqr{BBCB_MG_IMG_EXT}" name="marqr" onmouseover="helpline('marqr')" alt="{L_BBCB_MG_MR}" title="{L_BBCB_MG_MR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

<!-- INCLUDE bbcb_mg_colorbar.tpl -->

<div id="helpbox" style="width: 550px; height: 16px; font-size: 10px; margin-bottom: 3px;" class="helpline">{L_STYLES_TIP}</div>
