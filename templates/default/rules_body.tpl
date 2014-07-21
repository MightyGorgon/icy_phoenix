<!-- INCLUDE overall_header.tpl -->

<script type="text/javascript" src="{T_COMMON_TPL_PATH}js/sitemap.js"></script>
{IMG_THL}{IMG_THC}<span class="forumlink">{L_BOARDRULES}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td class="row1g-left">
		<div class="post-text">
		<!-- BEGIN rules_block -->
		<h2 style="padding: 5px;">{rules_block.BLOCK_TITLE}</h2><br />
		<!-- BEGIN rules_row -->
		<h4 style="padding: 5px;">{rules_block.rules_row.RULES_QUESTION}</h4>
		<ul><li>{rules_block.rules_row.RULES_ANSWER}</li></ul><br /><br />
		<!-- END rules_row -->
		<!-- END rules_block -->
		</div>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<div align="right">{JUMPBOX}</div>

<!-- INCLUDE overall_footer.tpl -->