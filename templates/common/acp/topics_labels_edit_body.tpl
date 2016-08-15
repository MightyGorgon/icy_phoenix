<h1>{ADMIN_TITLE}</h1>
<p>{ADMIN_TITLE_EXPLAIN}</p>

<script type="text/javascript">
// <![CDATA[
var form_name_original = form_name;
var text_name_original = text_name;
var form_name_thisform = 'topic_label';
var text_name_thisform_1 = 'label_bg_color';
var text_name_thisform_2 = 'label_text_color';

function bbcb_vars_reassign_start_1()
{
	form_name = form_name_thisform;
	text_name = text_name_thisform_1;
}

function bbcb_vars_reassign_start_2()
{
	form_name = form_name_thisform;
	text_name = text_name_thisform_2;
}

function bbcb_vars_reassign_end()
{
	form_name = form_name_original;
	text_name = text_name_original;
}
// ]]>
</script>

<form name="topic_label" action="{S_TITLE_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_ADD_NEW_TOPIC_LABEL}</th></tr>
<tr>
	<td class="row1" width="30%"><span class="genmed"><strong>{L_LABEL_EXAMPLE}</strong><br /><span class="gensmall">{L_LABEL_EXAMPLE_EXPLAIN}</span></span></td>
	<td class="row2"><div id="label_example">&nbsp;</div></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_LABEL_NAME}</strong><br /><span class="gensmall">{L_LABEL_NAME_EXPLAIN}</span></span></td>
	<td class="row2"><input class="post" type="text" id="label_name" name="label_name" size="35" maxlength="255" value="{LABEL_NAME}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_LABEL_CODE}</strong></span><br /><span class="gensmall">{L_LABEL_CODE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" id="label_code" name="label_code" size="35" maxlength="255" value="{LABEL_CODE}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_LABEL_CODE_SWITCH}</strong></span><br /><span class="gensmall">{L_LABEL_CODE_SWITCH_EXPLAIN}</span></td>
	<td class="row2"><div class="post"><input type="radio" name="label_code_switch" value="0" {LABEL_CODE_SW_PT}/>&nbsp;{L_LABEL_CODE_SWITCH_PT}&nbsp;&nbsp;&nbsp;<input type="radio" name="label_code_switch" value="1" {LABEL_CODE_SW_BBC}/>&nbsp;{L_LABEL_CODE_SWITCH_BBC}&nbsp;&nbsp;&nbsp;<input type="radio" name="label_code_switch" value="2" {LABEL_CODE_SW_HTML}/>&nbsp;{L_LABEL_CODE_SWITCH_HTML}&nbsp;&nbsp;&nbsp;<input type="radio" name="label_code_switch" value="3" {LABEL_CODE_SW_BBC_HTML}/>&nbsp;{L_LABEL_CODE_SWITCH_BBC_HTML}</div></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_LABEL_BG_COLOR}</strong></span><br /><span class="gensmall">{L_LABEL_BG_COLOR_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" id="label_bg_color" name="label_bg_color" size="15" maxlength="255" value="{LABEL_BG_COLOR}" />&nbsp;<a href="{FULL_SITE_PATH}{U_BBCODE_COLORPICKER_BG}" onclick="bbcb_vars_reassign_start_1();popup('{FULL_SITE_PATH}{U_BBCODE_COLORPICKER_BG}', 640, 480, '_color_picker');bbcb_vars_reassign_end();return false;"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}colorpicker{BBCB_MG_IMG_EXT}" title="{L_BBCB_MG_COLOR_PICKER}" style="vertical-align: middle;" class="bbimages" /></a></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_LABEL_TEXT_COLOR}</strong></span><br /><span class="gensmall">{L_LABEL_TEXT_COLOR_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" id="label_text_color" name="label_text_color" size="15" maxlength="255" value="{LABEL_TEXT_COLOR}" />&nbsp;<a href="{FULL_SITE_PATH}{U_BBCODE_COLORPICKER_TEXT}" onclick="bbcb_vars_reassign_start_2();popup('{FULL_SITE_PATH}{U_BBCODE_COLORPICKER_TXT}', 640, 480, '_color_picker');bbcb_vars_reassign_end();return false;"><img src="{FULL_SITE_PATH}{BBCB_MG_IMG_PATH}colorpicker{BBCB_MG_IMG_EXT}" title="{L_BBCB_MG_COLOR_PICKER}" style="vertical-align: middle;" class="bbimages" /></a></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_LABEL_ICON}</strong></span><br /><span class="gensmall">{L_LABEL_ICON_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" id="label_icon" name="label_icon" size="15" maxlength="255" value="{LABEL_ICON}" /></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_LABEL_AUTH_INFO}</strong></span><br /><span class="gensmall">{L_LABEL_AUTH_INFO_EXPLAIN}</span></td>
	<td class="row2"><div class="post"><input type="checkbox" name="admin_auth" {ADMIN_CHECKED}/>&nbsp;{L_LABEL_AUTH_ADMIN}<br /><input type="checkbox" name="mod_auth" {MOD_CHECKED}/>&nbsp;{L_LABEL_AUTH_MOD}<br /><input type="checkbox" name="poster_auth" {POSTER_CHECKED}/>&nbsp;{L_LABEL_AUTH_TOPIC_POSTER}</div></td>
</tr>
<tr>
	<td class="row1"><span class="genmed"><strong>{L_DATE_FORMAT}</strong></span><br /><span class="gensmall">{L_DATE_FORMAT_EXPLAIN}</span></td>
	<td class="row2"><!-- <input class="post" type="text" name="date_format" size="15" maxlength="255" value="{DATE_FORMAT}" /> -->{DATE_FORMAT}</td>
</tr>
<tr><td class="cat tdalignc" colspan="2"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td></tr>
</table>
{S_HIDDEN_FIELDS}
</form>

<script type="text/javascript">
// <![CDATA[

var label_example = '';
var label_code = '';
var label_bg_color = '';
var label_text_color = '';
var label_icon = '';
var label_icon_compiled = '';

$('#label_code').change(function() { label_example_update(); });
$('#label_bg_color').change(function() { label_example_update(); });
$('#label_text_color').change(function() { label_example_update(); });
$('#label_icon').change(function() { label_example_update(); });

function label_example_update()
{
	label_code = $('#label_code').val();
	label_bg_color = $('#label_bg_color').val();
	label_text_color = $('#label_text_color').val();
	label_icon = $('#label_icon').val();
	label_icon_compiled = '';
	if (label_icon != '')
	{
		label_icon_compiled = '<i class="fa ' + label_icon + '"></i>&nbsp;';
	}
	$('#label_example').html('<span class="label" style="color: ' + label_text_color + '; background-color: ' + label_bg_color + ';">' + label_icon_compiled + label_code + '</span>');
}

label_example_update();
// ]]>
</script>
