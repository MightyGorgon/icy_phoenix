<!-- INCLUDE simple_header.tpl -->

<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/bbcb_mg_small.js"></script>

<script type="text/javascript">
// <![CDATA[
	$(function(){
		var btnUpload = $('#upload');
		var status = $('#status');
		var pic_counter = 0;
		new AjaxUpload(btnUpload, {
			action: '{S_AJAX_UPLOAD}',
			name: 'userfile',
			onSubmit: function(file, ext){
				// extension is not allowed
				if (! (ext && /^({S_ALLOWED_EXTENSIONS})$/.test(ext)))
				{
					status.text('{L_ALLOWED_EXT_JS}');
					return false;
				}
				status.html('{IMG_LOADING_JS}');
			},
			onComplete: function(file, response){
				//On completion clear the status
				status.text('');
				//Add uploaded file to list
				if((response !== '0') && (response !== '2') && (response !== '3') && (response !== '4') && (response !== '5'))
				{
					pic_counter++;
					img_bbcode = '{IMG_BBCODE}';
					//$('<div><\/div>').appendTo('#files').html('<img src="{S_UPLOAD_DIR}' + file + '" alt="" /><br />' + '<span class="text_green">' + file + '<\/span>').addClass('picframe-cont');
					$('<div><\/div>').appendTo('#files').html('<img src="posted_img_list_thumbnail.' + php_ext + '?pic_id=' + escape('{S_USER_UPLOAD_DIR}' + file) + '" alt="" /><br />' + '<input class="post" name="bbci_' + pic_counter + '" size="80" maxlength="200" value="' + img_bbcode.replace('___IMAGE___', response) + '" style="width: 160px; max-width: 160px;" type="text" readonly="readonly" onclick="this.form.bbci_' + pic_counter + '.focus(); this.form.bbci_' + pic_counter + '.select();" />' + '<br /><input type="button" class="mainoption" value="{L_INSERT_BBC}" onclick="bbcb_ui_vars_reassign_start(); emoticon_sc(this.form.bbci_' + pic_counter + '.value); bbcb_ui_vars_reassign_end();" />').addClass('picframe-cont');
				}
				else
				{
					$('<div><\/div>').appendTo('#files').html('<span class="text_red">' + file + '<\/span>').addClass('picframe-cont');
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
		<div id="files"></div>
		<br />
	</td>
</tr>
<tr><td class="cat"><input type="button" class="liteoption" value="{L_CLOSE_WINDOW}" onclick="window.close();" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE simple_footer.tpl -->