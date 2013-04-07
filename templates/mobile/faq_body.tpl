<?php
$this->vars['_TITLE'] = $this->vars['L_FAQ_TITLE'];
?><!-- INCLUDE overall_header.tpl -->



<!-- BEGIN faq_block -->
<div class="block">
	<h2>{faq_block.BLOCK_TITLE}</h2>
	<ul class="sitemap">
	<!-- BEGIN faq_row -->
	<li>
		<h2 class="sitemap">{faq_block.faq_row.FAQ_QUESTION}</h2>
		<ul class="sitemap"><li><div class="post-text">{faq_block.faq_row.FAQ_ANSWER}</div><br clear="all" /></li></ul>
	</li>
	<!-- END faq_row -->
	</ul>
</div>
<!-- END faq_block -->

<div align="right">{JUMPBOX}</div>

<!-- INCLUDE overall_footer.tpl -->