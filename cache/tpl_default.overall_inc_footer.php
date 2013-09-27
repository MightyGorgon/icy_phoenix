<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:12 +0000 (time = 1380296052)

if (!defined('IN_ICYPHOENIX')) exit;

?>	<?php if ($this->vars['SWITCH_CMS_GLOBAL_BLOCKS']) {  ?>
	<?php if ($this->vars['TC_BLOCK']) {  ?><div style="vertical-align: top;"><?php

$tailcenter_blocks_row_count = ( isset($this->_tpldata['tailcenter_blocks_row.']) ) ? sizeof($this->_tpldata['tailcenter_blocks_row.']) : 0;
for ($tailcenter_blocks_row_i = 0; $tailcenter_blocks_row_i < $tailcenter_blocks_row_count; $tailcenter_blocks_row_i++)
{
 $tailcenter_blocks_row_item = &$this->_tpldata['tailcenter_blocks_row.'][$tailcenter_blocks_row_i];
 $tailcenter_blocks_row_item['S_ROW_COUNT'] = $tailcenter_blocks_row_i;
 $tailcenter_blocks_row_item['S_NUM_ROWS'] = $tailcenter_blocks_row_count;

?><?php echo isset($tailcenter_blocks_row_item['CMS_BLOCK']) ? $tailcenter_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END tailcenter_blocks_row

if(isset($tailcenter_blocks_row_item)) { unset($tailcenter_blocks_row_item); } 

?></div><?php } ?>
	</td>
	<?php if ($this->vars['TR_BLOCK']) {  ?><td width="5"><img src="<?php echo isset($this->vars['SPACER']) ? $this->vars['SPACER'] : $this->lang('SPACER'); ?>" alt="" width="5" height="10" /></td><td width="<?php echo isset($this->vars['FOOTER_WIDTH']) ? $this->vars['FOOTER_WIDTH'] : $this->lang('FOOTER_WIDTH'); ?>" style="width: <?php echo isset($this->vars['FOOTER_WIDTH']) ? $this->vars['FOOTER_WIDTH'] : $this->lang('FOOTER_WIDTH'); ?>px !important;" valign="top"><?php

$tailright_blocks_row_count = ( isset($this->_tpldata['tailright_blocks_row.']) ) ? sizeof($this->_tpldata['tailright_blocks_row.']) : 0;
for ($tailright_blocks_row_i = 0; $tailright_blocks_row_i < $tailright_blocks_row_count; $tailright_blocks_row_i++)
{
 $tailright_blocks_row_item = &$this->_tpldata['tailright_blocks_row.'][$tailright_blocks_row_i];
 $tailright_blocks_row_item['S_ROW_COUNT'] = $tailright_blocks_row_i;
 $tailright_blocks_row_item['S_NUM_ROWS'] = $tailright_blocks_row_count;

?><?php echo isset($tailright_blocks_row_item['CMS_BLOCK']) ? $tailright_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END tailright_blocks_row

if(isset($tailright_blocks_row_item)) { unset($tailright_blocks_row_item); } 

?></td><?php } ?>
	</tr>
	</table>
	<div style="vertical-align: top;"><?php

$tail_blocks_row_count = ( isset($this->_tpldata['tail_blocks_row.']) ) ? sizeof($this->_tpldata['tail_blocks_row.']) : 0;
for ($tail_blocks_row_i = 0; $tail_blocks_row_i < $tail_blocks_row_count; $tail_blocks_row_i++)
{
 $tail_blocks_row_item = &$this->_tpldata['tail_blocks_row.'][$tail_blocks_row_i];
 $tail_blocks_row_item['S_ROW_COUNT'] = $tail_blocks_row_i;
 $tail_blocks_row_item['S_NUM_ROWS'] = $tail_blocks_row_count;

?><?php echo isset($tail_blocks_row_item['CMS_BLOCK']) ? $tail_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END tail_blocks_row

if(isset($tail_blocks_row_item)) { unset($tail_blocks_row_item); } 

?></div>
	<?php } ?>

	<?php echo isset($this->vars['CMS_ACP']) ? $this->vars['CMS_ACP'] : $this->lang('CMS_ACP'); ?>
	<div style="text-align: center;"><br /><span class="admin-link"><?php echo isset($this->vars['ADMIN_LINK']) ? $this->vars['ADMIN_LINK'] : $this->lang('ADMIN_LINK'); ?></span><br /><br /></div>

	</td>
</tr>
<?php if ($this->vars['FOOTER_BANNER_BLOCK']) {  ?>
<tr><td width="100%" colspan="3"><?php echo isset($this->vars['FOOTER_BANNER_BLOCK']) ? $this->vars['FOOTER_BANNER_BLOCK'] : $this->lang('FOOTER_BANNER_BLOCK'); ?></td></tr>
<?php } ?>
