<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:50:25 +0000 (time = 1380635425)

if (!defined('IN_ICYPHOENIX')) exit;

?><form method="get" name="jumpbox" action="<?php echo isset($this->vars['S_JUMPBOX_ACTION']) ? $this->vars['S_JUMPBOX_ACTION'] : $this->lang('S_JUMPBOX_ACTION'); ?>" onsubmit="if(document.jumpbox.f.value == -1){return false;}" style="display: inline;">
<?php echo isset($this->vars['S_JUMPBOX_SELECT']) ? $this->vars['S_JUMPBOX_SELECT'] : $this->lang('S_JUMPBOX_SELECT'); ?>&nbsp;<input type="submit" value="<?php echo isset($this->vars['L_GO']) ? $this->vars['L_GO'] : $this->lang('L_GO'); ?>" class="liteoption jumpbox" />&nbsp;
</form>