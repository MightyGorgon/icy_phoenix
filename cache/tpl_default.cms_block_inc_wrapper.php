<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:42:23 +0000 (time = 1380634943)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php if ($this->vars['B_ADMIN']) {  ?><div class="block-container"><a class="block-edit-link" href="<?php echo isset($this->vars['B_EDIT_LINK']) ? $this->vars['B_EDIT_LINK'] : $this->lang('B_EDIT_LINK'); ?>"><img src="<?php echo isset($this->vars['IMG_CMS_ICON_EDIT']) ? $this->vars['IMG_CMS_ICON_EDIT'] : $this->lang('IMG_CMS_ICON_EDIT'); ?>" alt="" title="<?php echo isset($this->vars['L_CMS_EDIT_PARENT_BLOCK']) ? $this->vars['L_CMS_EDIT_PARENT_BLOCK'] : $this->lang('L_CMS_EDIT_PARENT_BLOCK'); ?>" /></a><?php } ?>
<?php if ($this->vars['TITLE']) {  ?><?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><span class="forumlink"><?php echo isset($this->vars['TITLE_CONTENT']) ? $this->vars['TITLE_CONTENT'] : $this->lang('TITLE_CONTENT'); ?></span><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><?php } ?>
<?php if ($this->vars['BORDER']) {  ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0"><?php } else { ?><table width="100%" cellspacing="0" cellpadding="0"><?php } ?>
<tr><?php if ($this->vars['BACKGROUND']) {  ?><td class="row1"><?php } else { ?><td><?php } ?><?php echo isset($this->vars['OUTPUT']) ? $this->vars['OUTPUT'] : $this->lang('OUTPUT'); ?></td></tr>
</table>
<?php if ($this->vars['TITLE']) {  ?><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?><?php } ?>
<?php if ($this->vars['B_ADMIN']) {  ?></div><?php } ?>