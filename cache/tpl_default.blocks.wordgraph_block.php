<?php

// eXtreme Styles mod cache. Generated on Fri, 04 Oct 2013 15:34:16 +0000 (time = 1380900856)

if (!defined('IN_ICYPHOENIX')) exit;

?><div id="wordgraph_block_h" style="display: none;">
<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><img class="max-min-right" style="<?php echo isset($this->vars['SHOW_HIDE_PADDING']) ? $this->vars['SHOW_HIDE_PADDING'] : $this->lang('SHOW_HIDE_PADDING'); ?>" src="<?php echo isset($this->vars['IMG_MAXIMISE']) ? $this->vars['IMG_MAXIMISE'] : $this->lang('IMG_MAXIMISE'); ?>" onclick="ShowHide('wordgraph_block','wordgraph_block_h','wordgraph_block');" alt="<?php echo isset($this->vars['L_SHOW']) ? $this->vars['L_SHOW'] : $this->lang('L_SHOW'); ?>" /><span class="forumlink"><?php echo isset($this->vars['L_WORDGRAPH']) ? $this->vars['L_WORDGRAPH'] : $this->lang('L_WORDGRAPH'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row1g row-center">&nbsp;</td></tr>
</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
</div>
<div id="wordgraph_block">
<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><img class="max-min-right" style="<?php echo isset($this->vars['SHOW_HIDE_PADDING']) ? $this->vars['SHOW_HIDE_PADDING'] : $this->lang('SHOW_HIDE_PADDING'); ?>" src="<?php echo isset($this->vars['IMG_MINIMISE']) ? $this->vars['IMG_MINIMISE'] : $this->lang('IMG_MINIMISE'); ?>" onclick="ShowHide('wordgraph_block','wordgraph_block_h','wordgraph_block');" alt="<?php echo isset($this->vars['L_HIDE']) ? $this->vars['L_HIDE'] : $this->lang('L_HIDE'); ?>" /><span class="forumlink"><?php echo isset($this->vars['L_WORDGRAPH']) ? $this->vars['L_WORDGRAPH'] : $this->lang('L_WORDGRAPH'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1g row-center">
		<?php

$wordgraph_loop_count = ( isset($this->_tpldata['wordgraph_loop.']) ) ? sizeof($this->_tpldata['wordgraph_loop.']) : 0;
for ($wordgraph_loop_i = 0; $wordgraph_loop_i < $wordgraph_loop_count; $wordgraph_loop_i++)
{
 $wordgraph_loop_item = &$this->_tpldata['wordgraph_loop.'][$wordgraph_loop_i];
 $wordgraph_loop_item['S_ROW_COUNT'] = $wordgraph_loop_i;
 $wordgraph_loop_item['S_NUM_ROWS'] = $wordgraph_loop_count;

?>
		<span style="font-size: <?php echo isset($wordgraph_loop_item['WORD_FONT_SIZE']) ? $wordgraph_loop_item['WORD_FONT_SIZE'] : ''; ?>"><a href="<?php echo isset($wordgraph_loop_item['WORD_SEARCH_URL']) ? $wordgraph_loop_item['WORD_SEARCH_URL'] : ''; ?>" style="font-size: <?php echo isset($wordgraph_loop_item['WORD_FONT_SIZE']) ? $wordgraph_loop_item['WORD_FONT_SIZE'] : ''; ?>px"><?php echo isset($wordgraph_loop_item['WORD']) ? $wordgraph_loop_item['WORD'] : ''; ?></a></span>
		<?php

} // END wordgraph_loop

if(isset($wordgraph_loop_item)) { unset($wordgraph_loop_item); } 

?>
	</td>
</tr>
</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
</div>
<script type="text/javascript">
<!--
tmp = 'wordgraph_block';
if(GetCookie(tmp) == '2')
{
	ShowHide('wordgraph_block', 'wordgraph_block_h', 'wordgraph_block');
}
//-->
</script>