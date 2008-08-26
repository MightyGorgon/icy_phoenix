
<script type="text/javascript">
<!--

message = new Array();
<!-- BEGIN postrow -->
message[{postrow.U_POST_ID}] = " user=\"{postrow.POSTER_NAME}\" post=\"{postrow.U_POST_ID}\"]{postrow.PLAIN_MESSAGE}[/";
<!-- END postrow -->

function addquote(post_id, tag)
{
	str_find = new Array("&lt;","&gt;", "--&gt;","&quot;")
	str_replace = new Array("&lt;","&gt;", "-->","\"")
	for(var i = 0; i < message[post_id].length; i++)
	{
		for (var j = 0; j < str_find.length; j++)
		{
			if (message[post_id].search(str_find[j]) != -1)
			{
				message[post_id] = message[post_id].replace(str_find[j],str_replace[j]);
			}
		}
	}
	//message[post_id] = message[post_id].replace('&lt;','<')
	//message[post_id] = message[post_id].replace('&gt;','>')
	window.parent.document.post.message.value += "[" + tag + message[post_id] + tag + "]";
	window.parent.document.post.message.focus();
	return;
}

function open_postreview(ref)
{
	height = screen.height / 3;
	width = screen.width / 2;
	window.open(ref,'_phpbbpostreview','height=' + height + ',width=' + width + ',resizable=yes,scrollbars=yes');
	return;
}
//-->
</script>

<!-- BEGIN switch_inline_mode -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_TOPIC_REVIEW}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1">
		<iframe frameborder="0" width="100%" height="300" src="{U_REVIEW_TOPIC}">
		<!-- END switch_inline_mode -->
		<table cellspacing="0" cellpadding="0" width="100%" class="forumline">
			<tr><td colspan="2" class="row-header"><span>{L_Post_review}</span></td></tr>
			<tr>
				<th>{L_AUTHOR}</th>
				<th>{L_MESSAGE}</th>
			</tr>
			<!-- BEGIN postrow -->
			<tr>
				<td class="row-post-author">
					<span class="post-name"><a name="{postrow.U_POST_ID}"></a>{postrow.POSTER_NAME}</span><br />
					<img src="{SPACER}" width="150" height="1" alt="" />
				</td>
				<td class="row-post" width="100%">
					<table width="100%" align="center" cellspacing="2" cellpadding="2" border="0">
						<tr>
							<td align="left"><div class="post-subject">{postrow.POST_SUBJECT}</div></td>
							<td align="right">
								<span class="post-buttons">
									<a href="javascript:addquote(%27{postrow.U_POST_ID}%27,%27quote%27);"><img src="{IMG_QUICK_QUOTE}" alt="{L_QUICK_QUOTE}" title="{L_QUICK_QUOTE}" /></a>
									<a href="javascript:addquote(%27{postrow.U_POST_ID}%27,%27ot%27);"><img src="{IMG_OFFTOPIC}" alt="{L_OFFTOPIC}" title="{L_OFFTOPIC}" /></a>
								</span>
							</td>
						</tr>
					</table>
					<div class="post-text">{postrow.MESSAGE}{postrow.ATTACHMENTS}</div>
				</td>
			</tr>
			<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
			<!-- END postrow -->
		</table>
		<!-- BEGIN switch_inline_mode -->
		</iframe>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END switch_inline_mode -->