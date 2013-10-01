<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:44:17 +0000 (time = 1380635057)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php if (! $this->vars['S_ACP_CMS']) {  ?>
<?php  $this->set_filename('xs_include_640d8e1022f56cac8dd6d4f49e743453', 'overall_header.tpl', true);  $this->pparse('xs_include_640d8e1022f56cac8dd6d4f49e743453');  ?>
<?php } ?>

<?php

$nav_blocks_row_count = ( isset($this->_tpldata['nav_blocks_row.']) ) ? sizeof($this->_tpldata['nav_blocks_row.']) : 0;
for ($nav_blocks_row_i = 0; $nav_blocks_row_i < $nav_blocks_row_count; $nav_blocks_row_i++)
{
 $nav_blocks_row_item = &$this->_tpldata['nav_blocks_row.'][$nav_blocks_row_i];
 $nav_blocks_row_item['S_ROW_COUNT'] = $nav_blocks_row_i;
 $nav_blocks_row_item['S_NUM_ROWS'] = $nav_blocks_row_count;

?><?php echo isset($nav_blocks_row_item['CMS_BLOCK']) ? $nav_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END nav_blocks_row

if(isset($nav_blocks_row_item)) { unset($nav_blocks_row_item); } 

?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="180" valign="top"><?php

$left_blocks_row_count = ( isset($this->_tpldata['left_blocks_row.']) ) ? sizeof($this->_tpldata['left_blocks_row.']) : 0;
for ($left_blocks_row_i = 0; $left_blocks_row_i < $left_blocks_row_count; $left_blocks_row_i++)
{
 $left_blocks_row_item = &$this->_tpldata['left_blocks_row.'][$left_blocks_row_i];
 $left_blocks_row_item['S_ROW_COUNT'] = $left_blocks_row_i;
 $left_blocks_row_item['S_NUM_ROWS'] = $left_blocks_row_count;

?><?php echo isset($left_blocks_row_item['CMS_BLOCK']) ? $left_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END left_blocks_row

if(isset($left_blocks_row_item)) { unset($left_blocks_row_item); } 

?></td>
	<td valign="top" style="padding-left: 7px; padding-right: 7px;">
		<?php

$center_blocks_row_count = ( isset($this->_tpldata['center_blocks_row.']) ) ? sizeof($this->_tpldata['center_blocks_row.']) : 0;
for ($center_blocks_row_i = 0; $center_blocks_row_i < $center_blocks_row_count; $center_blocks_row_i++)
{
 $center_blocks_row_item = &$this->_tpldata['center_blocks_row.'][$center_blocks_row_i];
 $center_blocks_row_item['S_ROW_COUNT'] = $center_blocks_row_i;
 $center_blocks_row_item['S_NUM_ROWS'] = $center_blocks_row_count;

?><?php echo isset($center_blocks_row_item['CMS_BLOCK']) ? $center_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END center_blocks_row

if(isset($center_blocks_row_item)) { unset($center_blocks_row_item); } 

?>
		<?php

$xsnews_blocks_row_count = ( isset($this->_tpldata['xsnews_blocks_row.']) ) ? sizeof($this->_tpldata['xsnews_blocks_row.']) : 0;
for ($xsnews_blocks_row_i = 0; $xsnews_blocks_row_i < $xsnews_blocks_row_count; $xsnews_blocks_row_i++)
{
 $xsnews_blocks_row_item = &$this->_tpldata['xsnews_blocks_row.'][$xsnews_blocks_row_i];
 $xsnews_blocks_row_item['S_ROW_COUNT'] = $xsnews_blocks_row_i;
 $xsnews_blocks_row_item['S_NUM_ROWS'] = $xsnews_blocks_row_count;

?><?php echo isset($xsnews_blocks_row_item['OUTPUT']) ? $xsnews_blocks_row_item['OUTPUT'] : ''; ?><?php

} // END xsnews_blocks_row

if(isset($xsnews_blocks_row_item)) { unset($xsnews_blocks_row_item); } 

?>
		<?php

$centerbottom_blocks_row_count = ( isset($this->_tpldata['centerbottom_blocks_row.']) ) ? sizeof($this->_tpldata['centerbottom_blocks_row.']) : 0;
for ($centerbottom_blocks_row_i = 0; $centerbottom_blocks_row_i < $centerbottom_blocks_row_count; $centerbottom_blocks_row_i++)
{
 $centerbottom_blocks_row_item = &$this->_tpldata['centerbottom_blocks_row.'][$centerbottom_blocks_row_i];
 $centerbottom_blocks_row_item['S_ROW_COUNT'] = $centerbottom_blocks_row_i;
 $centerbottom_blocks_row_item['S_NUM_ROWS'] = $centerbottom_blocks_row_count;

?><?php echo isset($centerbottom_blocks_row_item['CMS_BLOCK']) ? $centerbottom_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END centerbottom_blocks_row

if(isset($centerbottom_blocks_row_item)) { unset($centerbottom_blocks_row_item); } 

?>
	</td>
	<td width="180" valign="top"><?php

$right_blocks_row_count = ( isset($this->_tpldata['right_blocks_row.']) ) ? sizeof($this->_tpldata['right_blocks_row.']) : 0;
for ($right_blocks_row_i = 0; $right_blocks_row_i < $right_blocks_row_count; $right_blocks_row_i++)
{
 $right_blocks_row_item = &$this->_tpldata['right_blocks_row.'][$right_blocks_row_i];
 $right_blocks_row_item['S_ROW_COUNT'] = $right_blocks_row_i;
 $right_blocks_row_item['S_NUM_ROWS'] = $right_blocks_row_count;

?><?php echo isset($right_blocks_row_item['CMS_BLOCK']) ? $right_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END right_blocks_row

if(isset($right_blocks_row_item)) { unset($right_blocks_row_item); } 

?></td>
</tr>
</table>

<?php if (! $this->vars['S_ACP_CMS']) {  ?>
<?php  $this->set_filename('xs_include_d3cad4a2f87520e1692f9d3144dd4b0e', 'overall_footer.tpl', true);  $this->pparse('xs_include_d3cad4a2f87520e1692f9d3144dd4b0e');  ?>
<?php } ?>