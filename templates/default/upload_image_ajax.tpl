<!-- INCLUDE simple_header.tpl -->

<script type="text/javascript">
// <![CDATA[
	$(function(){
		var btnUpload = $('#upload');
		var status = $('#status');
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
				if(response === '1')
				{
					$('<div><\/div>').appendTo('#files').html('<div class="text_green_cont"><img src="{S_UPLOAD_DIR}' + file + '" alt="" /><br />' + '<span class="text_green">' + file + '<\/span><\/div>').addClass('thumb');
				}
				else
				{
					$('<div><\/div>').appendTo('#files').html('<div class="text_red_cont"><span class="text_red">' + file + '<\/span><\/div>').addClass('thumb');
				}
			}
		});

	});
// ]]>
</script>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_UPLOAD_IMAGE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1 row-center">
		<input id="upload" type="button" class="liteoption" value="Upload File" />
		<span id="status"></span>
		<div id="files"></div>
		<br />
	</td>
</tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- INCLUDE simple_footer.tpl -->