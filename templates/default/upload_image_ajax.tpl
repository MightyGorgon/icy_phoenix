<!-- INCLUDE simple_header.tpl -->

<script type="text/javascript">
// <![CDATA[
var form_name_original = form_name;
var text_name_original = text_name;
var form_name_thisform = '{BBCB_FORM_NAME}';
var text_name_thisform = '{BBCB_TEXT_NAME}';
var img_bbcode = '{IMG_BBCODE}';

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
// ]]>
</script>
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/bbcb_mg_small.js"></script>

<script type="text/javascript">
// <![CDATA[
var pic_counter = {S_AJAX_PIC_START};
var t_image_html_code = '';
var ajax_pic_start = 0;
var ajax_pic_limit = 5;

function image_html_code(image_name, f_pc)
{
	// Maybe use PHP var S_UPLOAD_DIR?
	var t_image_code = '<div class="picframe-cont"><img src="image_thumbnail_s.' + php_ext + '?pic_id=' + escape('{S_USER_UPLOAD_DIR}' + image_name) + '" alt="" /><br />' + '<input class="post" name="bbci_' + f_pc + '" size="20" maxlength="200" value="' + img_bbcode.replace('___IMAGE___', image_name) + '" style="width: 120px; max-width: 120px;" type="text" readonly="readonly" onclick="this.form.bbci_' + f_pc + '.focus(); this.form.bbci_' + f_pc + '.select();" />' + '<br /><input type="button" class="mainoption" value="{L_INSERT_BBC}" onclick="bbcb_ui_vars_reassign_start(); emoticon_sc(this.form.bbci_' + f_pc + '.value); bbcb_ui_vars_reassign_end();" /><\/div>';

	return t_image_code;
}

function get_imgs_func()
{
	$('#status').html('{IMG_LOADING_JS}');
	$('#status').show();

	ajax_pic_start = pic_counter;
	var jqxreq = $.ajax({
		type: 'GET',
		url: '{U_AJAX_GET_MORE_IMAGES}&start=' + ajax_pic_start + '&limit=' + ajax_pic_limit + '&json=1',
		cache: false,
		dataType: 'json'
	})

	.done(function(data) {
		//console.log(data);
		var t_data_len = data.length;

		if (t_data_len > 0)
		{
			for (var i = 0; i < t_data_len; i++)
			{
				pic_counter++;
				t_image_html_code = image_html_code(data[i].name, pic_counter);
				$('#files').append(t_image_html_code);
			}
		}

		if ((t_data_len == 0) || (t_data_len < ajax_pic_limit))
		{
			$('#get_imgs').hide();
		}

		$('#status').html('');
		//alert('Success');
	})

	.fail(function() {
		$('#status').html('<span class="text_red">{L_AJAX_REQ_ERROR}<\/span>');
		$('#status').fadeIn(1600);
		//alert('Error');
	})

	.always(function() {
		$('#status').html('<span class="text_green">{AJAX_REQ_SUCCESS}<\/span>');
		$('#status').fadeIn(1600);
		//$('#status').fadeOut(1600);
		//alert('Complete');
	});

	return true;
}

$(function(){
	var btnUpload = $('#upload');
	//var btnGetImgs = $('#get_imgs');
	new AjaxUpload(btnUpload, {
		action: '{S_AJAX_UPLOAD}',
		name: 'userfile',
		onSubmit: function(file, ext) {
			// extension is not allowed
			if (!(ext && /^({S_ALLOWED_EXTENSIONS})$/.test(ext)))
			{
				$('#status').html('<span class="text_red">{L_ALLOWED_EXT_JS}<\/span>');
				$('#status').fadeIn(1600);
				return false;
			}
			$('#status').html('{IMG_LOADING_JS}');
		},
		onComplete: function(file, response) {
			//On completion clear the status
			$('#status').html('<span class="text_green">{AJAX_REQ_SUCCESS}<\/span>');
			$('#status').fadeIn(1600);
			//$('#status').fadeOut(1600);
			$('#status').html('');
			//Add uploaded file to list
			var res_array = response.split("|");
			if (res_array[0] == '1')
			{
				pic_counter++;
				t_image_html_code = image_html_code(res_array[1], pic_counter);
				$('#files').prepend(t_image_html_code);
				//console.log(t_image_html_code);
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
				$('#status').html('<span class="text_red">' + error_message + file + '<\/span>');
				$('#status').fadeIn(1600);
			}
		}
	});

});
// ]]>
</script>

<form action="{S_ACTION}" name="upload_ajax_form" method="post" enctype="multipart/form-data">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_IMAGE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center">
		{L_UPLOAD_IMAGE_EXPLAIN}&nbsp;<input id="upload" type="button" class="liteoption" value="{L_UPLOAD_IMAGE}" /><br />
		<span class="gensmall"><i>{L_ALLOWED_EXT}</i></span><br /><br />
		<span id="status"></span>
		<div id="files">
		<!-- BEGIN pic_img -->
		<div class="picframe-cont"><img src="{pic_img.PIC_THUMB}" alt="{pic_img.PIC_NAME}" title="{pic_img.PIC_BBC}" /><br /><input class="post" name="{pic_img.PIC_BBC_INPUT}" size="20" maxlength="200" value="{pic_img.PIC_BBC}" type="text" readonly="readonly" onclick="this.form.{pic_img.PIC_BBC_INPUT}.focus(); this.form.{pic_img.PIC_BBC_INPUT}.select();" style="width: 120px; max-width: 120px;" /><br /><input type="button" class="mainoption" value="{L_INSERT_BBC}" onclick="bbcb_ui_vars_reassign_start(); emoticon_sc(this.form.{pic_img.PIC_BBC_INPUT}.value); bbcb_ui_vars_reassign_end();" /></div>
		<!-- END pic_img -->
		</div>
		<br clear="all" />
		<br />
		<br />
		<span class="gensmall"><i>{L_ALL_UPLOADED_IMAGES}&nbsp;<a href="{U_PERSONAL_IMAGES}" target="_parent" onclick="window.opener.location.href='{U_PERSONAL_IMAGES}'; return false;">{L_UPLOADED_IMAGES}</a></i></span><br /><br />
	</td>
</tr>
<tr><td class="cat"><input id="get_imgs" type="button" class="liteoption" value="{L_GET_MORE_IMGS}" onclick="get_imgs_func();" />&nbsp;<input type="button" class="liteoption" value="{L_CLOSE_WINDOW}" onclick="window.close();" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE simple_footer.tpl -->