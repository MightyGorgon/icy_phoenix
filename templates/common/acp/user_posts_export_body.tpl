<h1>{L_ACP_USER_POSTS_EXPORT_TITLE}</h1>
<p>{L_ACP_USER_POSTS_EXPORT_EXPLAIN}</p>

<form action="{S_USER_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_UPE_TITLE}</th></tr>
<tr>
	<td class="row1 tw30pct"><span class="genmed">{L_UPE_LIMIT}</span><br /><span class="gensmall">{L_UPE_LIMIT_EXPLAIN}</span></td>
	<td class="row2">{S_SELECT_LIMIT}</td>
</tr>
<!--
<tr>
	<td class="row1 tw30pct"><span class="genmed">{L_UPE_POSTS_TYPE}</span><br /><span class="gensmall">{L_UPE_POSTS_TYPE_EXPLAIN}</span></td>
	<td class="row2">{S_SELECT_POST_TYPE}</td>
</tr>
<tr>
	<td class="row1 tw30pct"><span class="genmed">{L_UPE_USER_IDS}</span><br /><span class="gensmall">{L_UPE_USER_IDS_EXPLAIN}</span></td>
	<td class="row2"><input id="user_ids" name="user_ids" type="text" class="post" maxlength="256" size="80" value="" /></td>
</tr>
-->
<tr>
	<td class="row1 tw30pct"><span class="genmed">{L_UPE_USER_IDS_JQ}</span><br /><span class="gensmall">{L_UPE_USER_IDS_JQ_EXPLAIN}</span></td>
	<td class="row2"><input id="jq_username" name="jq_username" type="text" class="post" maxlength="256" size="80" value="" /></td>
</tr>
<tr>
	<td class="row1 tw30pct"><span class="genmed">{L_UPE_USER_IDS_JQ_LIST}</span><br /><span class="gensmall">{L_UPE_USER_IDS_JQ_LIST_EXPLAIN}</span></td>
	<td class="row2">
		<ul id="sortable-src" class="sortable">
			<li id="src-no-user" class="state-src">{L_UPE_NO_USER}</li>
		</ul>
		<ul id="sortable-dst" class="sortable">
			<li id="dst-no-user" class="state-dst state-disabled">{L_UPE_NO_USER}</li>
		</ul>
	</td>
</tr>
<tr>
	<td class="row1 tw30pct"><span class="genmed">{L_UPE_USER_IDS}</span></td>
	<td class="row2"><input id="user_ids" name="user_ids" type="text" class="post" maxlength="256" size="80" value="{S_USER_IDS}" readonly="readonly" /></td>
</tr>
<tr><td colspan="2" class="cat" align="center">{S_HIDDEN_FIELDS}<input id="submit" name="submit" type="submit" class="mainoption" value="{L_SUBMIT}" /></td></tr>
</table>
</form>

<form id="files_list" action="{S_USER_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_UPE_FILES_LIST}</th></tr>
<tr>
	<td class="row1" nowrap="nowrap"><label for="file"><b>{L_UPE_FILES_LIST_SELECT}</b></label><br /><span class="gensmall">{L_UPE_FILES_LIST_SELECT_EXPLAIN}</span></td>
	<td class="row1 row-center tw100pct">
		<select id="file" name="file" size="10" style="min-height: 200px; min-width: 600px;">
		<!-- BEGIN files -->
			<option value="{files.FILE}">{files.NAME}</option>
		<!-- END files -->
		</select>
	</td>
</tr>
<tr>
	<td colspan="2" class="cat" align="center">
		<input id="download" name="download" type="submit" class="mainoption" value="{L_UPE_FILES_LIST_DOWNLOAD}" />&nbsp;
		<input id="delete" name="delete" type="submit" class="altoption" value="{L_UPE_FILES_LIST_DELETE}" />
	</td>
</tr>
</table>
</form>

<div id="img-loader" class="talignc tdalignc"><br /><img src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}jquery/loader.gif" alt="" /><br /><br /><br /></div>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {

	// Vars
	var all_user_ids = [];
	var tmp_user_id = 0;

	// Variable to hold request
	var request;
	var request_data;
	var request_url_base = '{FULL_SITE_PATH}ajax.php';
	var request_url_base_append = '?json=1&sid={S_SID}';
	var request_url_user_search = request_url_base + request_url_base_append + '&mode=user_search_json&term=';

	// Hide what is not needed
	$('#img-loader').hide();
	dl_del_disable();

	// Enable/Disable buttons
	$('#file').change(function() {
		var str = '';
		$('#file option:selected').each(function() {
			str += $(this).text() + ' ';
		});
		if (str == '')
		{
			dl_del_disable();
		}
		else
		{
			dl_del_enable();
		}
	});
	// Submit
	$('#submit').click(function () {
		//$(this).prop('disabled', true);
		console.log($('#user_ids').val());
		$('#img-loader').show();
	});

	// Username lookup
	// Please note that:
	// - change(): only works when the input element has lost focus
	// - keyup(function(event){}): will catch even special keys and selection, so this will trigger extra AJAX requests...
	$('#jq_username').on('input', function (e) {
		var len = $(this).val().length;
		if (len >= 2)
		{

			// Abort any pending request
			if (request)
			{
				request.abort();
			}

			// Setup some local variables
			var $form = $(this);

			// Serialize the data in the form
			var serializedData = $form.serialize();

			// Fire off the request
			request = $.ajax({
				url: request_url_user_search,
				type: 'post',
				data: serializedData
			});

			// Callback handler that will be called on success
			request.done(function (response, textStatus, jqXHR) {
				//console.log(response);
				var users = $.parseJSON(response);
				//console.log(users);
				if (users.length)
				{
					$('#src-no-user').hide();
					sortable_clear('#sortable-src');
					for (var i = 0, len = users.length; i < len; i++)
					{
						$('#sortable-src').append($('<li id="u' + users[i]['id'] + '">').append(users[i]['value']));
						//$('#sortable-src').append($('<li>').append(users[i]['value'] + ' [' + users[i]['id'] + ']'));
						//console.log(users[i]['id'] + '->' + users[i]['value']);
					}
					sortable_add_class('#sortable-src', 'state-src');
				}
				else
				{
					$('#src-no-user').show();
				}
			});

			// Callback handler that will be called on failure
			request.fail(function (jqXHR, textStatus, errorThrown){
				// Log the error to the console
				console.error("The following error occurred: " + textStatus, errorThrown);
			});

			// Callback handler that will be called regardless if the request failed or succeeded
			request.always(function () {
				//console.log('AJAX Always');
			});

		}
	});

	$('#jq_username').keydown(function (event) {
		if (event.which == 13)
		{
			event.preventDefault();
		}
	});

	function sortable_add_class(ul_id, li_class)
	{
		$(ul_id + ' li').each(function () {
			$(this).addClass(li_class);
		});
	}

	function sortable_clear(ul_id)
	{
		$(ul_id + ' li').each(function () {
			$(this).remove();
		});
	}

	$(function() {
		$('#sortable-src, #sortable-dst').sortable({
			connectWith: ".sortable"
		}).disableSelection();
	});

	$('#sortable-dst').sortable({
		items: "li:not(.state-disabled)",
		update: function () {
			var lsize = $("#sortable-dst li").length;
			//console.log(lsize);
			if (lsize > 1)
			{
				$('#dst-no-user').hide();
			}
			else
			{
				$('#dst-no-user').show();
			}
			$('#sortable-dst li').removeClass('state-src');
			$('#sortable-dst li').removeClass('state-dst');
			$('#sortable-dst li').addClass('state-dst');
			$('#sortable-src li').removeClass('state-src');
			$('#sortable-src li').removeClass('state-dst');
			$('#sortable-src li').addClass('state-src');
			all_user_ids = [];
			tmp_user_id = 0;
			$("#sortable-dst li" ).each(function (index) {
				if ($(this).attr('id') != 'dst-no-user')
				{
					tmp_user_id = $(this).attr('id');
					tmp_user_id = tmp_user_id.replace('u', '');
					if ($.inArray(tmp_user_id, all_user_ids) == -1)
					{
						all_user_ids.push(tmp_user_id);
					}
					else
					{
						$(this).remove();
					}
				}
			});
			//console.log(all_user_ids);
			$('#user_ids').val(all_user_ids.join());
		}
	});

	function dl_del_disable()
	{
		$('#download').prop('disabled', true);
		$('#download').removeClass('mainoption');
		$('#download').addClass('liteoption');
		$('#delete').prop('disabled', true);
		$('#delete').removeClass('altoption');
		$('#delete').addClass('liteoption');
	}

	function dl_del_enable()
	{
		$('#download').prop('disabled', false);
		$('#download').removeClass('liteoption');
		$('#download').addClass('mainoption');
		$('#delete').prop('disabled', false);
		$('#delete').removeClass('liteoption');
		$('#delete').addClass('altoption');
	}

});
// ]]>
</script>