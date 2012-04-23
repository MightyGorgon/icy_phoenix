<!-- INCLUDE simple_header.tpl -->

<script type="text/javascript">//<![CDATA[
var form_name_original = form_name;
var text_name_original = text_name;
var form_name_thisform = '{BBCB_FORM_NAME}';
var text_name_thisform = '{BBCB_TEXT_NAME}';

{JAVASCRIPT_LANG_VARS}

s_help = "{L_BBCODE_S_HELP}";
s_s_help = "{L_BBCODE_S_HELP}";

var bbcb_mg_img_path = "{FULL_SITE_PATH}{BBCB_MG_PATH_PREFIX}images/bbcb_mg/images/gif/";
var bbcb_mg_img_ext = ".gif";

function bbcb_vars_reassign_start()
{
	form_name = form_name_thisform;
	text_name = text_name_thisform;
}

function bbcb_vars_reassign_end()
{
	form_name = form_name_original;
	text_name = text_name_original;
}
//]]>
</script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/bbcb_mg_small.js"></script>

<script type="text/javascript">//<![CDATA[
	$(function(){
		var btnUpload = $('#upload');
		var status = $('#status');
		var pic_counter = 0;
		new AjaxUpload(btnUpload, {
			action: '{S_AJAX_UPLOAD}',
			name: 'userfile',
			onSubmit: function(file, ext) {
				// extension is not allowed
				if (!(ext && /^({S_ALLOWED_EXTENSIONS})$/.test(ext)))
				{
					status.html('<span class="text_red">{L_ALLOWED_EXT_JS}<\/span>');
					return false;
				}
				status.html('{IMG_LOADING_JS}');
			},
			onComplete: function(file, response) {
				//On completion clear the status
				status.html('');
				//Add uploaded file to list
				var res_array = response.split("|");
				if (res_array[0] == '1')
				{
					pic_counter++;
					img_bbcode = '{IMG_BBCODE}';
					//$('<div><\/div>').appendTo('#files').html('<img src="{S_UPLOAD_DIR}' + file + '" alt="" /><br />' + '<span class="text_green">' + file + '<\/span>').addClass('picframe-cont');
					$('<div><\/div>').appendTo('#files').html('<img src="posted_img_list_thumbnail.' + php_ext + '?pic_id=' + escape('{S_USER_UPLOAD_DIR}' + res_array[1]) + '" alt="" /><br />' + '<input class="post" name="bbci_' + pic_counter + '" size="80" maxlength="200" value="' + img_bbcode.replace('___IMAGE___', res_array[1]) + '" style="width: 160px; max-width: 160px;" type="text" readonly="readonly" onclick="this.form.bbci_' + pic_counter + '.focus(); this.form.bbci_' + pic_counter + '.select();" />' + '<br /><input type="button" class="mainoption" value="{L_INSERT_BBC}" onclick="bbcb_ui_vars_reassign_start(); emoticon_sc(this.form.bbci_' + pic_counter + '.value); bbcb_ui_vars_reassign_end();" />').addClass('picframe-cont');
				}
				else
				{
					var error_message = '';
					if (res_array[0] == '5')
					{
						error_message = '{L_UPLOAD_ERROR_SIZE}<br />';
					}
					else if (res_array[0] == '3')
					{
						error_message = '{L_UPLOAD_ERROR_TYPE}<br />';
					}
					else
					{
						error_message = '{L_UPLOAD_ERROR}<br />';
					}
					status.html('<span class="text_red">' + error_message + file + '<\/span>');
					//$('<div><\/div>').appendTo('#files').html('<span class="text_red">' + error_message + file + '<\/span>').addClass('picframe-cont');
				}
			}
		});

	});
//]]>
</script>

<form action="{S_ACTION}" name="upload_ajax_form" method="post" enctype="multipart/form-data">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_IMAGE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center">
		{L_UPLOAD_IMAGE_EXPLAIN}&nbsp;<input id="upload" type="button" class="liteoption" value="{L_UPLOAD_IMAGE}" /><br />
		<span class="gensmall"><i>{L_ALLOWED_EXT}</i></span><br /><br />
		<span id="status"></span>
		<div id="files"></div>
		<br />
	</td>
</tr>
<tr><td class="cat"><input type="button" class="liteoption" value="{L_CLOSE_WINDOW}" onclick="window.close();" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE simple_footer.tpl -->
