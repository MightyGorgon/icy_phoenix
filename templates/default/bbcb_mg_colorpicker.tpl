<!-- INCLUDE simple_header.tpl -->

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/prototype.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/bbcb_mg.js"></script>

<script type="text/javascript" src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}js/color_bar.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}colorpicker/colormethods.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}colorpicker/colorvaluepicker.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}colorpicker/slider.js"></script>
<script type="text/javascript" src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}{T_COMMON_TPL_PATH}colorpicker/colorpicker.js"></script>

<script type="text/javascript">
// <![CDATA[
/*
// Defaults...
var bbcb_mg_cp_form = 'post';
var bbcb_mg_cp_object = 'message';
var bbcb_mg_cp_tag = 'color';
var bbcb_mg_cp_is_ext = true;
var bbcb_mg_cp_is_plain = false;
*/

var form_name = '{BBCB_MG_CP_FORM}';
var text_name = '{BBCB_MG_CP_OBJECT}';
var bbcb_mg_cp_tag = '{BBCB_MG_CP_TAG}';
var bbcb_mg_cp_is_ext = {BBCB_MG_CP_IS_EXT};
var bbcb_mg_cp_is_plain = {BBCB_MG_CP_IS_PLAIN};

function InsertColor(MyString)
{
	InsertTag(MyString, bbcb_mg_cp_tag, bbcb_mg_cp_is_ext, bbcb_mg_cp_is_plain)
}
// ]]>
</script>

<div id="cpdiv" style="background-color: #888888; border: solid 1px #000000; padding: 10px; margin: 5px; width: 460px; height: 350px;">

<div style="width: 440px;">

<div style="display: inline; float: right;">
<div id="cp1_Preview" onclick="InsertColor('#' + document.getElementById('cp1_Hex').value);" style="background-color: #ffffff; width: 60px; height: 60px; padding: 0; margin: 0; border: solid 1px #000000; cursor: pointer;" title="{L_CP_CLICK_APPLY}"><br /></div><br />
<input type="radio" id="cp1_HueRadio" name="cp1_Mode" value="0" tabindex="20" /><label for="cp1_HueRadio"><tt>H:</tt></label><input type="text" id="cp1_Hue" value="0" style="width: 40px;" class="post" tabindex="30" /> &deg;<br />
<input type="radio" id="cp1_SaturationRadio" name="cp1_Mode" value="1" tabindex="21" /><label for="cp1_SaturationRadio"><tt>S:</tt></label><input type="text" id="cp1_Saturation" value="100" style="width: 40px;" class="post" tabindex="31" /> %<br />
<input type="radio" id="cp1_BrightnessRadio" name="cp1_Mode" value="2" tabindex="22" /><label for="cp1_BrightnessRadio"><tt>B:</tt></label><input type="text" id="cp1_Brightness" value="100" style="width: 40px;" class="post" tabindex="32" /> %<br />
<br />
<input type="radio" id="cp1_RedRadio" name="cp1_Mode" value="r" tabindex="23" /><label for="cp1_RedRadio"><tt>R:</tt></label><input type="text" id="cp1_Red" value="255" style="width: 40px;" class="post" tabindex="33" /><br />
<input type="radio" id="cp1_GreenRadio" name="cp1_Mode" value="g" tabindex="24" /><label for="cp1_GreenRadio"><tt>G:</tt></label><input type="text" id="cp1_Green" value="0" style="width: 40px;" class="post" tabindex="34" /><br />
<input type="radio" id="cp1_BlueRadio" name="cp1_Mode" value="b" tabindex="25" /><label for="cp1_BlueRadio"><tt>B:</tt></label><input type="text" id="cp1_Blue" value="0" style="width: 40px;" class="post" tabindex="35" /><br />
<tt>#:</tt><input type="text" id="cp1_Hex" value="FF0000" style="width: 60px;" class="post" tabindex="36" /><br />
</div>

<div style="width: 310px;">
<div style="position: relative; float: right;"><div id="cp1_ColorBar"></div></div>
<div id="cp1_ColorMap"></div>
</div>

<br />
<div id="cpinfotext" class="tdalignl">{L_CP_CLICK_COLOR}</div>

</div>

</div>

<br /><br />
<div style="text-align: center;"><a href="javascript:window.close();" class="genmed">{L_CLOSE_WINDOW}</a></div>

<div style="display: none;">
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/rangearrows.gif" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/mappoint.gif" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-saturation.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-brightness.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-blue-tl.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-blue-tr.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-blue-bl.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-blue-br.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-red-tl.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-red-tr.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-red-bl.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-red-br.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-green-tl.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-green-tr.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-green-bl.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/bar-green-br.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-red-max.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-red-min.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-green-max.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-green-min.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-blue-max.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-blue-min.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-saturation.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-saturation-overlay.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-brightness.png" alt="" />
<img src="{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/colorpicker/map-hue.png" alt="" />
</div>
<script type="text/javascript">
// <![CDATA[

Event.observe(window,'load',function()
{
	cp1 = new Refresh.Web.ColorPicker('cp1');
	//ShowHide('cpdiv','cpdiv_h');
	//ShowHide('cp_mappoint');
	//ShowHide('cp_arrows');
});
// ]]>
</script>

<!-- INCLUDE simple_footer.tpl -->
