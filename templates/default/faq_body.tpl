<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/sitemap.js"></script>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_FAQ_TITLE}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td class="row1g-left gen">
		<!-- BEGIN faq_block -->
		<h2 class="sitemap">{faq_block.BLOCK_TITLE}</h2>
		<ul class="sitemap">
		<!-- BEGIN faq_row -->
		<li>
			<h2 class="sitemap">{faq_block.faq_row.FAQ_QUESTION}</h2>
			<ul class="sitemap"><li><div class="post-text">{faq_block.faq_row.FAQ_ANSWER}</div><br class="clear" /></li></ul>
		</li>
		<!-- END faq_row -->
		</ul>
		<!-- END faq_block -->
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<div align="right">{JUMPBOX}</div>

<!-- INCLUDE overall_footer.tpl -->