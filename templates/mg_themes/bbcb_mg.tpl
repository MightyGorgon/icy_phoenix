<script type="text/javascript" src="{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}js/color_bar.js"></script>

<script type="text/javascript">
<!--
s_help = "{L_BBCODE_S_HELP}";
s_s_help = "{L_BBCODE_S_HELP}";
var height1 = 8; // define the height of the color bar
var pas = 20; // define the number of color in the color bar
var width1 = Math.floor(-2 / 15 * pas + 6); // define the width of the color bar
var text1 = s_help.substring(0,search(s_help,"="));
var text2 = s_help.substring(search(s_help,"]"),search(s_help,"/"));
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

<table width="99%" border="0" cellspacing="0" cellpadding="2">
<tr>
	<td width="100%" align="left" nowrap="nowrap">
		&nbsp;
		<select style="height:18px;" name="ft" onChange="BBCft();this.selectedIndex=0;" onMouseOver="helpline('ft')">
			<option value="" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Verdana; background-color: {T_TD_COLOR1}">{L_FONT_TYPE}</option>
			<option value="Arial" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Arial; background-color: {T_TD_COLOR1}">{L_FONT_ARIAL}</option>
			<option value="Comic Sans MS" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Comic Sans MS'; background-color: {T_TD_COLOR1}">{L_FONT_COMIC_SANS_MS}</option>
			<option value="Courier New" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Courier New'; background-color: {T_TD_COLOR1}">{L_FONT_COURIER_NEW}</option>
			<option value="Impact" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Impact; background-color: {T_TD_COLOR1}">{L_FONT_IMPACT}</option>
			<option value="Lucida Console" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Lucida Console'; background-color: {T_TD_COLOR1}">{L_FONT_LUCIDA_CONSOLE}</option>
			<option value="Lucida Sans Unicode" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Lucida Sans Unicode'; background-color: {T_TD_COLOR1}">{L_FONT_LUCIDA_SANS_UNICODE}</option>
			<option value="Microsoft Sans Serif" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Microsoft Sans Serif'; background-color: {T_TD_COLOR1}">{L_FONT_MICROSOFT_SANS_SERIF}</option>
			<option value="Symbol" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Symbol; background-color: {T_TD_COLOR1}">{L_FONT_SYMBOL}</option>
			<option value="Tahoma" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Tahoma; background-color: {T_TD_COLOR1}">{L_FONT_TAHOMA}</option>
			<option value="Times New Roman" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Times New Roman'; background-color: {T_TD_COLOR1}">{L_FONT_TIMES_NEW_ROMAN}</option>
			<option value="Traditional Arabic" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Traditional Arabic'; background-color: {T_TD_COLOR1}">{L_FONT_TRADITIONAL_ARABIC}</option>
			<option value="Trebuchet MS" class="genmed" style="color:{T_FONTCOLOR1}; font-family:'Trebuchet MS'; background-color: {T_TD_COLOR1}">{L_FONT_TREBUCHET_MS}</option>
			<option value="Verdana" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Verdana; background-color: {T_TD_COLOR1}">{L_FONT_VERDANA}</option>
			<option value="Webdings" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Webdings; background-color: {T_TD_COLOR1}">{L_FONT_WEBDINGS}</option>
			<option value="Wingdings" class="genmed" style="color:{T_FONTCOLOR1}; font-family:Wingdings; background-color: {T_TD_COLOR1}">{L_FONT_WINGDINGS}</option>
		</select>
		&nbsp;
		<select style="height:18px;" name="fs" onChange="BBCfs();this.selectedIndex=0;" onMouseOver="helpline('fs')">
			<option value="" selected class="genmed" style="color:{T_FONTCOLOR1}; background-color: {T_TD_COLOR1}">{L_FONT_SIZE}</option>
			<option value="8" class="genmed" style="color:{T_FONTCOLOR1}; background-color: {T_TD_COLOR1}; font-size:8px">{L_FONT_TINY}</option>
			<option value="10" class="genmed" style="color:{T_FONTCOLOR1}; background-color: {T_TD_COLOR1}; font-size:10px">{L_FONT_SMALL}</option>
			<option value="12" class="genmed" style="color:{T_FONTCOLOR1}; background-color: {T_TD_COLOR1}; font-size:12px">{L_FONT_NORMAL}</option>
			<option value="14" class="genmed" style="color:{T_FONTCOLOR1}; background-color: {T_TD_COLOR1}; font-size:14px">{L_FONT_LARGE}</option>
			<option value="18" class="genmed" style="color:{T_FONTCOLOR1};  background-color: {T_TD_COLOR1}; font-size:18px">{L_FONT_HUGE}</option>
			<option value="24" class="genmed" style="color:{T_FONTCOLOR1}; background-color: {T_TD_COLOR1}; font-size:24px">{L_FONT_XL}</option>
		</select>
		&nbsp;
		<select style="height:18px;" name="fc" onChange="BBCfc();this.selectedIndex=0;" onMouseOver="helpline('fc')">
			<option style="color:{T_FONTCOLOR1}; background-color: {T_TD_COLOR1}" value="{T_FONTCOLOR1}" class="genmed">{L_FONT_COLOR}</option>
			<option style="color:brown; background-color: {T_TD_COLOR1}" value="brown" class="genmed">{L_COLOR_BROWN}</option>
			<option style="color:chocolate; background-color: {T_TD_COLOR1}" value="chocolate" class="genmed">{L_COLOR_CHOCOLATE}</option>
			<option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred" class="genmed">{L_COLOR_DARK_RED}</option>
			<option style="color:crimson; background-color: {T_TD_COLOR1}" value="crimson" class="genmed">{L_COLOR_CRIMSON}</option>
			<option style="color:red; background-color: {T_TD_COLOR1}" value="red" class="genmed">{L_COLOR_RED}</option>
			<option style="color:#FF8866; background-color: {T_TD_COLOR1}" value="#FF8866" class="genmed">{L_COLOR_LIGHT_ORANGE}</option>
			<option style="color:#FF5500; background-color: {T_TD_COLOR1}" value="#FF5500" class="genmed">{L_COLOR_POWER_ORANGE}</option>
			<option style="color:orange; background-color: {T_TD_COLOR1}" value="orange" class="genmed">{L_COLOR_ORANGE}</option>
			<option style="color:gold; background-color: {T_TD_COLOR1}" value="gold" class="genmed">{L_COLOR_GOLD}</option>
			<option style="color:peachpuff; background-color: {T_TD_COLOR1}" value="peachpuff" class="genmed">{L_COLOR_PEACH}</option>
			<option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow" class="genmed">{L_COLOR_YELLOW}</option>
			<option style="color:#00FF00; background-color: {T_TD_COLOR1}" value="#00FF00" class="genmed">{L_COLOR_LIGHT_GREEN}</option>
			<option style="color:seagreen; background-color: {T_TD_COLOR1}" value="seagreen" class="genmed">{L_COLOR_SEA_GREEN}</option>
			<option style="color:green; background-color: {T_TD_COLOR1}" value="green" class="genmed">{L_COLOR_GREEN}</option>
			<option style="color:olive; background-color: {T_TD_COLOR1}" value="olive" class="genmed">{L_COLOR_OLIVE}</option>
			<option style="color:darkgreen; background-color: {T_TD_COLOR1}" value="darkgreen" class="genmed">{L_COLOR_DARKGREEN}</option>
			<option style="color:#DDEEFF; background-color: {T_TD_COLOR1}" value="#DDEEFF" class="genmed">{L_COLOR_LIGHT_CYAN}</option>
			<option style="color:#AACCEE; background-color: {T_TD_COLOR1}" value="#AACCEE" class="genmed">{L_COLOR_LIGHT_BLUE}</option>
			<option style="color:cadetblue; background-color: {T_TD_COLOR1}" value="cadetblue" class="genmed">{L_COLOR_CADET_BLUE}</option>
			<option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan" class="genmed">{L_COLOR_CYAN}</option>
			<option style="color:#666699; background-color: {T_TD_COLOR1}" value="#666699" class="genmed">{L_COLOR_TURQUOISE}</option>
			<option style="color:blue; background-color: {T_TD_COLOR1}" value="blue" class="genmed">{L_COLOR_BLUE}</option>
			<option style="color:deepskyblue; background-color: {T_TD_COLOR1}" value="deepskyblue" class="genmed">{L_COLOR_DEEPSKYBLUE}</option>
			<option style="color:midnightblue; background-color: {T_TD_COLOR1}" value="midnightblue" class="genmed">{L_COLOR_MIDNIGHTBLUE}</option>
			<option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue" class="genmed">{L_COLOR_DARK_BLUE}</option>
			<option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo" class="genmed">{L_COLOR_INDIGO}</option>
			<option style="color:darkorchid; background-color: {T_TD_COLOR1}" value="darkorchid" class="genmed">{L_COLOR_DARK_ORCHID}</option>
			<option style="color:violet; background-color: {T_TD_COLOR1}" value="violet" class="genmed">{L_COLOR_VIOLET}</option>
			<option style="color:white; background-color: {T_TD_COLOR1}" value="white" class="genmed">{L_COLOR_WHITE}</option>
			<option style="color:lightgrey; background-color: {T_TD_COLOR1}" value="lightgrey" class="genmed">{L_COLOR_LIGHT_GREY}</option>
			<option style="color:silver; background-color: {T_TD_COLOR1}" value="silver" class="genmed">{L_COLOR_SILVER}</option>
			<option style="color:darkgrey; background-color: {T_TD_COLOR1}" value="darkgrey" class="genmed">{L_COLOR_DARK_GREY}</option>
			<option style="color:gray; background-color: {T_TD_COLOR1}" value="gray" class="genmed">{L_COLOR_GRAY}</option>
			<option style="color:black; background-color: {T_TD_COLOR1}" value="black" class="genmed">{L_COLOR_BLACK}</option>
		</select>
		<!-- BEGIN switch_colorpicker -->
		<a href="{U_BBCODE_COLORPICKER}" onClick="javascript:popup=window.open('{U_BBCODE_COLORPICKER}','_color_picker','height=420,width=530,scrollbars=no,resizable=yes');" target="_color_picker"><img src="{BBCB_MG_IMG_PATH}colorpicker{BBCB_MG_IMG_EXT}" onMouseOver="helpline('fc')" alt="{L_BBCB_MG_COLOR_PICKER}" title="{L_BBCB_MG_COLOR_PICKER}" style="vertical-align:middle;" class="bbimages" /></a>
		<!-- END switch_colorpicker -->
	</td>
	<td width="20%" align="right" nowrap="nowrap" valign="middle">
		<div class="genmed"><a href="{U_BBCODE_HELP}" class="gensmall" target="_blank"><img src="{BBCB_MG_IMG_PATH}help{BBCB_MG_IMG_EXT}" name="help" onMouseOver="helpline('help')" alt="{L_BBCODE_HELP}" title="{L_BBCODE_HELP}" /></a></div>
		<!-- <span class="moderators">&nbsp;<a href="javascript:bbstyle(-1)" onMouseOver="helpline('a')">{L_BBCODE_CLOSE_TAGS}</a>&nbsp;</span> -->
	</td>
</tr>
<tr>
	<td width="100%" align="left" valign="middle" colspan="2">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
				<div class="gennull">
					<span class="genmed">&nbsp;</span>
					<a href="javascript:BBCleft()"><img src="{BBCB_MG_IMG_PATH}left{BBCB_MG_IMG_EXT}" name="left" onMouseOver="helpline('left')" alt="{L_BBCB_MG_L}" title="{L_BBCB_MG_L}" class="bbimages" /></a>
					<a href="javascript:BBCcenter()"><img src="{BBCB_MG_IMG_PATH}center{BBCB_MG_IMG_EXT}" name="center" onMouseOver="helpline('center')" alt="{L_BBCB_MG_C}" title="{L_BBCB_MG_C}" class="bbimages" /></a>
					<a href="javascript:BBCright()"><img src="{BBCB_MG_IMG_PATH}right{BBCB_MG_IMG_EXT}" name="right" onMouseOver="helpline('right')" alt="{L_BBCB_MG_R}" title="{L_BBCB_MG_R}" class="bbimages" /></a>
					<a href="javascript:BBCjustify()"><img src="{BBCB_MG_IMG_PATH}justify{BBCB_MG_IMG_EXT}" name="justify" onMouseOver="helpline('justify')" alt="{L_BBCB_MG_J}" title="{L_BBCB_MG_J}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCbold()" accesskey="b"><img src="{BBCB_MG_IMG_PATH}bold{BBCB_MG_IMG_EXT}" name="bold_img" onMouseOver="helpline('b')" alt="{L_BBCB_MG_B}" title="{L_BBCB_MG_B}" class="bbimages" /></a>
					<a href="javascript:BBCitalic()" accesskey="i"><img src="{BBCB_MG_IMG_PATH}italic{BBCB_MG_IMG_EXT}" name="italic" onMouseOver="helpline('i')" alt="{L_BBCB_MG_I}" title="{L_BBCB_MG_I}" class="bbimages" /></a>
					<a href="javascript:BBCunder()" accesskey="u"><img src="{BBCB_MG_IMG_PATH}under{BBCB_MG_IMG_EXT}" name="under" onMouseOver="helpline('u')" alt="{L_BBCB_MG_U}" title="{L_BBCB_MG_U}" class="bbimages" /></a>
					<a href="javascript:BBCstrike()"><img src="{BBCB_MG_IMG_PATH}strike{BBCB_MG_IMG_EXT}" name="strik" onMouseOver="helpline('strike')" alt="{L_BBCB_MG_S}" title="{L_BBCB_MG_S}" class="bbimages" /></a>
					<a href="javascript:BBCsup()"><img src="{BBCB_MG_IMG_PATH}sup{BBCB_MG_IMG_EXT}" name="supscript" onMouseOver="helpline('sup')" alt="{L_BBCB_MG_SUP}" title="{L_BBCB_MG_SUP}" class="bbimages" /></a>
					<a href="javascript:BBCsub()"><img src="{BBCB_MG_IMG_PATH}sub{BBCB_MG_IMG_EXT}" name="subs" onMouseOver="helpline('sub')" alt="{L_BBCB_MG_SUB}" title="{L_BBCB_MG_SUB}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBClist()" accesskey="l"><img src="{BBCB_MG_IMG_PATH}list{BBCB_MG_IMG_EXT}" name="listdf" onMouseOver="helpline('list')" alt="{L_BBCB_MG_LST}" title="{L_BBCB_MG_LST}" class="bbimages" /></a>
					<a href="javascript:BBClistO()" accesskey="l"><img src="{BBCB_MG_IMG_PATH}list_o{BBCB_MG_IMG_EXT}" name="listodf" onMouseOver="helpline('listo')" alt="{L_BBCB_MG_LST}" title="{L_BBCB_MG_LSTO}" class="bbimages" /></a>
					<a href="javascript:BBCbullet()" accesskey="*"><img src="{BBCB_MG_IMG_PATH}list_ast{BBCB_MG_IMG_EXT}" name="listbullet" onMouseOver="helpline('bullet')" alt="{L_BBCB_MG_BULLET}" title="{L_BBCB_MG_BULLET}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBChl()" ><img src="{BBCB_MG_IMG_PATH}highlight{BBCB_MG_IMG_EXT}" name="highlight" onMouseOver="helpline('highlight')" alt="{L_BBCB_MG_HIGHLIGHT}" title="{L_BBCB_MG_HIGHLIGHT}" class="bbimages" /></a>
					<a href="javascript:BBChr()"><img src="{BBCB_MG_IMG_PATH}hr{BBCB_MG_IMG_EXT}" name="hr" onMouseOver="helpline('hr')" alt="{L_BBCB_MG_HR}" title="{L_BBCB_MG_HR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<!-- BEGIN switch_active_content -->
					<a href="javascript:BBCflash()"><img src="{BBCB_MG_IMG_PATH}flash{BBCB_MG_IMG_EXT}" name="flash" onMouseOver="helpline('flash')" alt="L_BBCB_MG_FLSH}" title="{L_BBCB_MG_FLSH}" class="bbimages" /></a>
					<a href="javascript:BBCvideo()"><img src="{BBCB_MG_IMG_PATH}video{BBCB_MG_IMG_EXT}" name="video" onMouseOver="helpline('video')" alt="{L_BBCB_MG_VID}" title="{L_BBCB_MG_VID}" class="bbimages" /></a>
					<a href="javascript:BBCgooglevideo()"><img src="{BBCB_MG_IMG_PATH}googlevideo{BBCB_MG_IMG_EXT}" name="googlevideo" onMouseOver="helpline('googlevideo')" alt="{L_BBCB_MG_GVID}" title="{L_BBCB_MG_GVID}" class="bbimages" /></a>
					<a href="javascript:BBCyoutube()"><img src="{BBCB_MG_IMG_PATH}youtube{BBCB_MG_IMG_EXT}" name="youtube" onMouseOver="helpline('youtube')" alt="{L_BBCB_MG_YOUTUBE}" title="{L_BBCB_MG_YOUTUBE}" class="bbimages" /></a>
					<a href="javascript:BBCram()"><img src="{BBCB_MG_IMG_PATH}ram{BBCB_MG_IMG_EXT}" name="ram" onMouseOver="helpline('ram')" alt="{L_BBCB_MG_RAM}" title="{L_BBCB_MG_RAM}" class="bbimages" /></a>
					<a href="javascript:BBCquick()"><img src="{BBCB_MG_IMG_PATH}quick{BBCB_MG_IMG_EXT}" name="quick" onMouseOver="helpline('quick')" alt="Quicktime" title="Quicktime" class="bbimages" /></a>
					<a href="javascript:BBCstream()"><img src="{BBCB_MG_IMG_PATH}sound{BBCB_MG_IMG_EXT}" name="stream" onMouseOver="helpline('stream')" alt="{L_BBCB_MG_STRM}" title="{L_BBCB_MG_STRM}" class="bbimages" /></a>
					<a href="javascript:BBCemff()"><img src="{BBCB_MG_IMG_PATH}emff{BBCB_MG_IMG_EXT}" name="emff" onMouseOver="helpline('emff')" alt="{L_BBCB_MG_EMFF}" title="{L_BBCB_MG_EMFF}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>
					<!-- END switch_active_content -->

				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="gennull">
					<span class="genmed">&nbsp;</span>
					<a href="javascript:BBCquote()" accesskey="q"><img src="{BBCB_MG_IMG_PATH}quote{BBCB_MG_IMG_EXT}" name="quote" onMouseOver="helpline('quote')" alt="{L_BBCB_MG_QUOTE}" title="{L_BBCB_MG_QUOTE}" class="bbimages" /></a>
					<a href="javascript:BBCcode()" accesskey="c"><img src="{BBCB_MG_IMG_PATH}code{BBCB_MG_IMG_EXT}" name="code" onMouseOver="helpline('code')" alt="{L_BBCB_MG_CODE}" title="{L_BBCB_MG_CODE}" class="bbimages" /></a>
					<a href="javascript:BBCphpbbmod()" accesskey="p"><img src="{BBCB_MG_IMG_PATH}code{BBCB_MG_IMG_EXT}" name="phpbbmod" onMouseOver="helpline('phpbbmod')" alt="{L_BBCB_MG_PHPBBMOD}" title="{L_BBCB_MG_PHPBBMOD}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCurl()" accesskey="w"><img src="{BBCB_MG_IMG_PATH}url{BBCB_MG_IMG_EXT}" name="url" onMouseOver="helpline('url')" alt="{L_BBCB_MG_URL}" title="{L_BBCB_MG_URL}" class="bbimages" /></a>
					<a href="javascript:BBCmail()" accesskey="e"><img src="{BBCB_MG_IMG_PATH}email{BBCB_MG_IMG_EXT}" name="email" onMouseOver="helpline('mail')" alt="{L_BBCB_MG_EML}" title="{L_BBCB_MG_EML}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<!-- BEGIN switch_pic_upload -->
					<a href="{U_BBCODE_POSTICYIMAGE}" onClick="javascript:popup=window.open('{U_BBCODE_POSTICYIMAGE}','_upload_image','height=300,width=700,scrollbars=no,resizable=yes');" target="_upload_image" accesskey="g"><img src="{BBCB_MG_IMG_PATH}post_icy_image{BBCB_MG_IMG_EXT}" name="posticyimage" onMouseOver="helpline('posticyimage')" alt="{L_BBCB_MG_POSTICYIMAGE}" title="{L_BBCB_MG_POSTICYIMAGE}" class="bbimages" /></a>
					<!-- END switch_pic_upload -->
					<!-- BEGIN switch_postimage_org -->
					<a href="{U_BBCODE_POSTIMAGE}" accesskey="g"><img src="{BBCB_MG_IMG_PATH}image_add{BBCB_MG_IMG_EXT}" name="uploadimg" onMouseOver="helpline('image_upload')" alt="{L_BBCB_MG_UPLOAD_IMG}" title="{L_BBCB_MG_UPLOAD_IMG}" class="bbimages" /></a>
					<!-- END switch_postimage_org -->
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCimgl()"><img src="{BBCB_MG_IMG_PATH}image_linkl{BBCB_MG_IMG_EXT}" name="imgl" onMouseOver="helpline('imgl')" alt="{L_BBCB_MG_IMGL}" title="{L_BBCB_MG_IMGL}" class="bbimages" /></a>
					<a href="javascript:BBCimg()" accesskey="p"><img src="{BBCB_MG_IMG_PATH}image_link{BBCB_MG_IMG_EXT}" name="img" onMouseOver="helpline('img')" alt="{L_BBCB_MG_IMG}" title="{L_BBCB_MG_IMG}" class="bbimages" /></a>
					<a href="javascript:BBCimgr()"><img src="{BBCB_MG_IMG_PATH}image_linkr{BBCB_MG_IMG_EXT}" name="imgr" onMouseOver="helpline('imgr')" alt="{L_BBCB_MG_IMGR}" title="{L_BBCB_MG_IMGR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCalbumimgl()"><img src="{BBCB_MG_IMG_PATH}image_gall{BBCB_MG_IMG_EXT}" name="albumimgl" onMouseOver="helpline('albumimgl')" alt="{L_BBCB_MG_ALBUMIMGL}" title="{L_BBCB_MG_ALBUMIMGL}" class="bbimages" /></a>
					<a href="javascript:BBCalbumimg()"><img src="{BBCB_MG_IMG_PATH}image_gal{BBCB_MG_IMG_EXT}" name="albumimg" onMouseOver="helpline('albumimg')" alt="{L_BBCB_MG_ALBUMIMG}" title="{L_BBCB_MG_ALBUMIMG}" class="bbimages" /></a>
					<a href="javascript:BBCalbumimgr()"><img src="{BBCB_MG_IMG_PATH}image_galr{BBCB_MG_IMG_EXT}" name="albumimgr" onMouseOver="helpline('albumimgr')" alt="{L_BBCB_MG_ALBUMIMGR}" title="{L_BBCB_MG_ALBUMIMGR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCspoiler()" ><img src="{BBCB_MG_IMG_PATH}spoiler{BBCB_MG_IMG_EXT}" name="spoiler" onMouseOver="helpline('spoiler')" alt="{L_BBCB_MG_SPOILER}" title="{L_BBCB_MG_SPOILER}" class="bbimages" /></a>
					<a href="javascript:BBCcell()" ><img src="{BBCB_MG_IMG_PATH}cell{BBCB_MG_IMG_EXT}" name="cell" onMouseOver="helpline('cell')" alt="{L_BBCB_MG_CELL}" title="{L_BBCB_MG_CELL}" class="bbimages" /></a>
					<a href="javascript:BBCfade()"><img src="{BBCB_MG_IMG_PATH}fade{BBCB_MG_IMG_EXT}" name="fade" onMouseOver="helpline('fade')" alt="{L_BBCB_MG_FADE}" title="{L_BBCB_MG_FADE}" class="bbimages" /></a>
					<a href="javascript:BBCgrad()"><img src="{BBCB_MG_IMG_PATH}grad{BBCB_MG_IMG_EXT}" name="rainb" onMouseOver="helpline('grad')" alt="{L_BBCB_MG_GRAD}" title="{L_BBCB_MG_GRAD}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>

					<a href="javascript:BBCmarqd()"><img src="{BBCB_MG_IMG_PATH}marqd{BBCB_MG_IMG_EXT}" name="marqd" onMouseOver="helpline('marqd')" alt="{L_BBCB_MG_MD}" title="{L_BBCB_MG_MD}" class="bbimages" /></a>
					<a href="javascript:BBCmarqu()"><img src="{BBCB_MG_IMG_PATH}marqu{BBCB_MG_IMG_EXT}" name="marqu" onMouseOver="helpline('marqu')" alt="{L_BBCB_MG_MU}" title="{L_BBCB_MG_MU}" class="bbimages" /></a>
					<a href="javascript:BBCmarql()"><img src="{BBCB_MG_IMG_PATH}marql{BBCB_MG_IMG_EXT}" name="marql" onMouseOver="helpline('marql')" alt="{L_BBCB_MG_ML}" title="{L_BBCB_MG_ML}" class="bbimages" /></a>
					<a href="javascript:BBCmarqr()"><img src="{BBCB_MG_IMG_PATH}marqr{BBCB_MG_IMG_EXT}" name="marqr" onMouseOver="helpline('marqr')" alt="{L_BBCB_MG_MR}" title="{L_BBCB_MG_MR}" class="bbimages" /></a>
					<span class="genmed">&nbsp;&nbsp;</span>
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<table class="bbcbmg" style="width:550px;" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="100%" align="left">
		<table id="ColorPanel" width="100%" align="left" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td id="ColorUsed" width="10" onMouseOver="helpline('s'); this.style.cursor='hand';" onclick="if(this.bgColor.length > 0) InsertTag2(this.bgColor);" align="center">
				<script type="text/javascript">document.write('<img height=' + height1 + ' src="{BBCB_MG_IMG_PATH}spacer.gif" width="10" border="1" />');</script>
			</td>
			<td width="5">
				<script type="text/javascript">document.write('<img height=' + height1 + ' src="{BBCB_MG_IMG_PATH}spacer.gif" width="5" />');</script>
			</td>
			<td id="ColorUsed1" width="10" onMouseOver="helpline('s'); this.style.cursor='hand';" onclick="if(this.bgColor.length > 0) InsertTag2(this.bgColor);" align="center">
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
<tr><td><input type="text" name="helpbox" size="50" maxlength="100" style="width:550px;font-size:10px;" class="helpline" value="{L_STYLES_TIP}" readonly="readonly" /></td></tr>
</table>