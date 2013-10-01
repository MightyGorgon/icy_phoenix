<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:44:17 +0000 (time = 1380635057)

if (!defined('IN_ICYPHOENIX')) exit;

?><script type="text/javascript">
<!--
function links_me()
{
	window.open("links_popup.php", '_links_me', 'height=220,width=500,resizable=no,scrollbars=no');
}
//-->
</script>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<?php

$links_own1_count = ( isset($this->_tpldata['links_own1.']) ) ? sizeof($this->_tpldata['links_own1.']) : 0;
for ($links_own1_i = 0; $links_own1_i < $links_own1_count; $links_own1_i++)
{
 $links_own1_item = &$this->_tpldata['links_own1.'][$links_own1_i];
 $links_own1_item['S_ROW_COUNT'] = $links_own1_i;
 $links_own1_item['S_NUM_ROWS'] = $links_own1_count;

?>
<tr><td align="center"><a href="javascript:links_me()"><img src="<?php echo isset($this->vars['U_SITE_LOGO']) ? $this->vars['U_SITE_LOGO'] : $this->lang('U_SITE_LOGO'); ?>" alt="<?php echo isset($this->vars['SITENAME']) ? $this->vars['SITENAME'] : $this->lang('SITENAME'); ?>" title="<?php echo isset($this->vars['SITENAME']) ? $this->vars['SITENAME'] : $this->lang('SITENAME'); ?>"/></a><br /><br /></td></tr>
<?php

} // END links_own1

if(isset($links_own1_item)) { unset($links_own1_item); } 

?>
<?php

$links_scroll_count = ( isset($this->_tpldata['links_scroll.']) ) ? sizeof($this->_tpldata['links_scroll.']) : 0;
for ($links_scroll_i = 0; $links_scroll_i < $links_scroll_count; $links_scroll_i++)
{
 $links_scroll_item = &$this->_tpldata['links_scroll.'][$links_scroll_i];
 $links_scroll_item['S_ROW_COUNT'] = $links_scroll_i;
 $links_scroll_item['S_NUM_ROWS'] = $links_scroll_count;

?>
<tr>
	<td class="gen" height="100">
	<marquee id="links_block" behavior="scroll" direction="up" scrolldelay="100" height="80" scrollamount="2" loop="true" onmouseover="this.stop()" onmouseout="this.start()">
		<div class="center-block-text">
			<div class="gen">
			<br />
			<?php

$links_row_count = ( isset($links_scroll_item['links_row.']) ) ? sizeof($links_scroll_item['links_row.']) : 0;
for ($links_row_i = 0; $links_row_i < $links_row_count; $links_row_i++)
{
 $links_row_item = &$links_scroll_item['links_row.'][$links_row_i];
 $links_row_item['S_ROW_COUNT'] = $links_row_i;
 $links_row_item['S_NUM_ROWS'] = $links_row_count;

?>
			<a href="<?php echo isset($links_row_item['LINK_HREF']) ? $links_row_item['LINK_HREF'] : ''; ?>" target="_blank" onmouseover="document.all.links_block.stop()" onmouseout="document.all.links_block.start()"><img src="<?php echo isset($links_row_item['LINK_LOGO_SRC']) ? $links_row_item['LINK_LOGO_SRC'] : ''; ?>" alt="<?php echo isset($links_row_item['LINK_TITLE']) ? $links_row_item['LINK_TITLE'] : ''; ?>" title="<?php echo isset($links_row_item['LINK_TITLE']) ? $links_row_item['LINK_TITLE'] : ''; ?>" width="<?php echo isset($this->vars['SITE_LOGO_WIDTH']) ? $this->vars['SITE_LOGO_WIDTH'] : $this->lang('SITE_LOGO_WIDTH'); ?>" height="<?php echo isset($this->vars['SITE_LOGO_HEIGHT']) ? $this->vars['SITE_LOGO_HEIGHT'] : $this->lang('SITE_LOGO_HEIGHT'); ?>" border="0" vspace="3" /></a><br /><br />
			<?php

} // END links_row

if(isset($links_row_item)) { unset($links_row_item); } 

?>
			<br />
			</div>
		</div>
	</marquee>
	</td>
</tr>
<?php

} // END links_scroll

if(isset($links_scroll_item)) { unset($links_scroll_item); } 

?>
<?php

$links_static_count = ( isset($this->_tpldata['links_static.']) ) ? sizeof($this->_tpldata['links_static.']) : 0;
for ($links_static_i = 0; $links_static_i < $links_static_count; $links_static_i++)
{
 $links_static_item = &$this->_tpldata['links_static.'][$links_static_i];
 $links_static_item['S_ROW_COUNT'] = $links_static_i;
 $links_static_item['S_NUM_ROWS'] = $links_static_count;

?>
<tr>
	<td>
	<?php

$links_row_count = ( isset($links_static_item['links_row.']) ) ? sizeof($links_static_item['links_row.']) : 0;
for ($links_row_i = 0; $links_row_i < $links_row_count; $links_row_i++)
{
 $links_row_item = &$links_static_item['links_row.'][$links_row_i];
 $links_row_item['S_ROW_COUNT'] = $links_row_i;
 $links_row_item['S_NUM_ROWS'] = $links_row_count;

?>
	<div class="genmed" style="text-align: center;"><a href="<?php echo isset($links_row_item['LINK_HREF']) ? $links_row_item['LINK_HREF'] : ''; ?>" target="_blank"><img src="<?php echo isset($links_row_item['LINK_LOGO_SRC']) ? $links_row_item['LINK_LOGO_SRC'] : ''; ?>" alt="<?php echo isset($links_row_item['LINK_TITLE']) ? $links_row_item['LINK_TITLE'] : ''; ?>" title="<?php echo isset($links_row_item['LINK_TITLE']) ? $links_row_item['LINK_TITLE'] : ''; ?>" width="<?php echo isset($this->vars['SITE_LOGO_WIDTH']) ? $this->vars['SITE_LOGO_WIDTH'] : $this->lang('SITE_LOGO_WIDTH'); ?>" height="<?php echo isset($this->vars['SITE_LOGO_HEIGHT']) ? $this->vars['SITE_LOGO_HEIGHT'] : $this->lang('SITE_LOGO_HEIGHT'); ?>" border="0" vspace="3" /></a></div><br />
	<?php

} // END links_row

if(isset($links_row_item)) { unset($links_row_item); } 

?>
	</td>
</tr>
<?php

} // END links_static

if(isset($links_static_item)) { unset($links_static_item); } 

?>
<?php

$links_own2_count = ( isset($this->_tpldata['links_own2.']) ) ? sizeof($this->_tpldata['links_own2.']) : 0;
for ($links_own2_i = 0; $links_own2_i < $links_own2_count; $links_own2_i++)
{
 $links_own2_item = &$this->_tpldata['links_own2.'][$links_own2_i];
 $links_own2_item['S_ROW_COUNT'] = $links_own2_i;
 $links_own2_item['S_NUM_ROWS'] = $links_own2_count;

?>
<tr><td align="center"><a href="javascript:links_me()"><img src="<?php echo isset($this->vars['U_SITE_LOGO']) ? $this->vars['U_SITE_LOGO'] : $this->lang('U_SITE_LOGO'); ?>" alt="<?php echo isset($this->vars['SITENAME']) ? $this->vars['SITENAME'] : $this->lang('SITENAME'); ?>" title="<?php echo isset($this->vars['SITENAME']) ? $this->vars['SITENAME'] : $this->lang('SITENAME'); ?>"/></a><br /></td></tr>
<?php

} // END links_own2

if(isset($links_own2_item)) { unset($links_own2_item); } 

?>
</table>