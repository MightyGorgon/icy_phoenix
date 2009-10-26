<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/sitemap.js"></script>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_BOARDRULES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1g-left gen">
		<!-- BEGIN rules_block -->
		<h2 class="sitemap">{rules_block.BLOCK_TITLE}</h2>
		<ul class="sitemap">
		<!-- BEGIN rules_row -->
		<li>
			<h2 class="sitemap">{rules_block.rules_row.RULES_QUESTION}</h2>
			<ul class="sitemap"><li>{rules_block.rules_row.RULES_ANSWER}<br /><br /></li></ul>
		</li>
		<!-- END rules_row -->
		</ul>
		<!-- END rules_block -->
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<div align="right">{JUMPBOX}</div>

<!-- INCLUDE overall_footer.tpl -->