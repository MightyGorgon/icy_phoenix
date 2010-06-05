<!-- IF S_POSTS_LIKES -->
<script type="text/javascript" src="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}js/ajax/ajax_topicfunctions.js"></script>
<!-- ENDIF -->

<script type="text/javascript">
// <![CDATA[
<!-- BEGIN switch_quick_quote -->
message = new Array();
<!-- END switch_quick_quote -->

post_like = new Array();
post_like_js = new Array();
post_like_js_new = new Array();

<!-- BEGIN postrow -->
post_like[{postrow.U_POST_ID}] = <!-- IF postrow.READER_LIKES -->1<!-- ELSE -->0<!-- ENDIF -->;
post_like_js[{postrow.U_POST_ID}] = <!-- IF postrow.READER_LIKES -->'{postrow.POST_LIKE_TEXT_JS}'<!-- ELSE -->'{postrow.POST_LIKE_TEXT_JS_NEW}'<!-- ENDIF -->;
post_like_js_new[{postrow.U_POST_ID}] = <!-- IF postrow.READER_LIKES -->'{postrow.POST_LIKE_TEXT_JS_NEW}'<!-- ELSE -->'{postrow.POST_LIKE_TEXT_JS}'<!-- ENDIF -->;
<!-- BEGIN switch_quick_quote -->
message[{postrow.U_POST_ID}] = " user=\"{postrow.POSTER_NAME_QQ}\" post=\"{postrow.U_POST_ID}\"]{postrow.PLAIN_MESSAGE}[/";
<!-- END switch_quick_quote -->
<!-- END postrow -->

function post_like_ajax(topic_id, post_id)
{
	if (post_like[post_id] == 0)
	{
		mode = 'like';
		lang_var_link = '{L_UNLIKE}';
		lang_var_span = post_like_js[post_id];
		post_like[post_id] = 1;
	}
	else
	{
		mode = 'unlike';
		lang_var_link = '{L_LIKE}';
		lang_var_span = post_like_js_new[post_id];
		post_like[post_id] = 0;
	}
	AJAXPostLike(mode, topic_id, post_id);

	var post_span_content = getElementById('like_s_p' + post_id);
	var post_url_content = getElementById('like_a_p' + post_id);

	if ((post_span_content == null) || (post_url_content == null))
	{
		return;
	}

	//alert(lang_var_span);
	post_span_content.innerHTML = ((lang_var_span != '') ? (lang_var_span + '&nbsp;&bull;&nbsp;') : '&nbsp;');
	post_url_content.innerHTML = lang_var_link;
}

function post_time_edit(url)
{
	window.open(url, '_postedittime', 'width=600,height=300,resizable=no,scrollbars=no');
}
// ]]>
</script>
