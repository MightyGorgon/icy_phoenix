<?php

// eXtreme Styles mod cache. Generated on Fri, 27 Sep 2013 15:34:11 +0000 (time = 1380296051)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php

$switch_header_table_count = ( isset($this->_tpldata['switch_header_table.']) ) ? sizeof($this->_tpldata['switch_header_table.']) : 0;
for ($switch_header_table_i = 0; $switch_header_table_i < $switch_header_table_count; $switch_header_table_i++)
{
 $switch_header_table_item = &$this->_tpldata['switch_header_table.'][$switch_header_table_i];
 $switch_header_table_item['S_ROW_COUNT'] = $switch_header_table_i;
 $switch_header_table_item['S_NUM_ROWS'] = $switch_header_table_count;

?>
<tr>
	<td width="100%" colspan="3" align="center">
	<div style="width: 90%; margin: 0 auto; clear: both; text-align: center; padding: 10px;">
		<?php echo isset($this->vars['IMG_TBL']) ? $this->vars['IMG_TBL'] : $this->lang('IMG_TBL'); ?><table class="forumline" width="100%" cellspacing="0" cellpadding="0">
			<tr><td class="row-header"><span><?php echo isset($this->vars['L_STAFF_MESSAGE']) ? $this->vars['L_STAFF_MESSAGE'] : $this->lang('L_STAFF_MESSAGE'); ?></span></td></tr>
			<tr><td class="row-post"><div class="post-text"><?php echo isset($switch_header_table_item['HEADER_TEXT']) ? $switch_header_table_item['HEADER_TEXT'] : ''; ?></div><br /><br /></td></tr>
		</table><?php echo isset($this->vars['IMG_TBR']) ? $this->vars['IMG_TBR'] : $this->lang('IMG_TBR'); ?>
	</div>
	</td>
</tr>
<?php

} // END switch_header_table

if(isset($switch_header_table_item)) { unset($switch_header_table_item); } 

?>

<?php

$ctracker_message_count = ( isset($this->_tpldata['ctracker_message.']) ) ? sizeof($this->_tpldata['ctracker_message.']) : 0;
for ($ctracker_message_i = 0; $ctracker_message_i < $ctracker_message_count; $ctracker_message_i++)
{
 $ctracker_message_item = &$this->_tpldata['ctracker_message.'][$ctracker_message_i];
 $ctracker_message_item['S_ROW_COUNT'] = $ctracker_message_i;
 $ctracker_message_item['S_NUM_ROWS'] = $ctracker_message_count;

?>
<tr>
	<td width="100%" colspan="3" align="center">
	<div style="width: 90%; margin: 0 auto; clear: both; text-align: center; padding: 10px;">
		<?php echo isset($this->vars['IMG_TBL']) ? $this->vars['IMG_TBL'] : $this->lang('IMG_TBL'); ?><table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td width="80" align="center" style="background-color:#<?php echo isset($ctracker_message_item['ROW_COLOR']) ? $ctracker_message_item['ROW_COLOR'] : ''; ?>;"><img src="<?php echo isset($ctracker_message_item['ICON_GLOB']) ? $ctracker_message_item['ICON_GLOB'] : ''; ?>" alt="" title="" /></td>
			<td style="background-color:#<?php echo isset($ctracker_message_item['ROW_COLOR']) ? $ctracker_message_item['ROW_COLOR'] : ''; ?>;"><div class="gensmall"><?php echo isset($ctracker_message_item['L_MESSAGE_TEXT']) ? $ctracker_message_item['L_MESSAGE_TEXT'] : ''; ?></div></td>
		</tr>
		<tr><td class="row1 row-center" colspan="2"><span class="gensmall"><?php if ($ctracker_message_item['U_MARK_MESSAGE']) {  ?><b><a href="<?php echo isset($ctracker_message_item['U_MARK_MESSAGE']) ? $ctracker_message_item['U_MARK_MESSAGE'] : ''; ?>"><?php echo isset($ctracker_message_item['L_MARK_MESSAGE']) ? $ctracker_message_item['L_MARK_MESSAGE'] : ''; ?></a></b><?php } else { ?>&nbsp;<?php } ?></span><br /></td></tr>
		</table><?php echo isset($this->vars['IMG_TBR']) ? $this->vars['IMG_TBR'] : $this->lang('IMG_TBR'); ?>
	</div>
	</td>
</tr>
<?php

} // END ctracker_message

if(isset($ctracker_message_item)) { unset($ctracker_message_item); } 

?>

<tr>
	<td colspan="3" id="content">
	<?php if ($this->vars['S_LOGGED_IN']) {  ?>
	<?php if ($this->vars['NEW_PM_SWITCH']) {  ?><div class="popup<?php echo isset($this->vars['PRIVMSG_IMG']) ? $this->vars['PRIVMSG_IMG'] : $this->lang('PRIVMSG_IMG'); ?>"><a href="<?php echo isset($this->vars['FULL_SITE_PATH']) ? $this->vars['FULL_SITE_PATH'] : $this->lang('FULL_SITE_PATH'); ?><?php echo isset($this->vars['U_PRIVATEMSGS']) ? $this->vars['U_PRIVATEMSGS'] : $this->lang('U_PRIVATEMSGS'); ?>"><?php echo isset($this->vars['PRIVATE_MESSAGE_INFO']) ? $this->vars['PRIVATE_MESSAGE_INFO'] : $this->lang('PRIVATE_MESSAGE_INFO'); ?></a></div><?php } ?>
	<?php if ($this->vars['NEW_PRIVATE_CHAT_SWITCH']) {  ?><div id="shoutbox_pvt_alert" class="popup<?php echo isset($this->vars['PRIVATE_CHAT_IMG']) ? $this->vars['PRIVATE_CHAT_IMG'] : $this->lang('PRIVATE_CHAT_IMG'); ?>"><a class="gensmall" href="<?php echo isset($this->vars['U_PRIVATE_CHAT']) ? $this->vars['U_PRIVATE_CHAT'] : $this->lang('U_PRIVATE_CHAT'); ?>"><?php echo isset($this->vars['L_AJAX_SHOUTBOX_PVT_ALERT']) ? $this->vars['L_AJAX_SHOUTBOX_PVT_ALERT'] : $this->lang('L_AJAX_SHOUTBOX_PVT_ALERT'); ?></a></div><?php } ?>
	<?php } ?>
	<?php

$switch_admin_disable_board_count = ( isset($this->_tpldata['switch_admin_disable_board.']) ) ? sizeof($this->_tpldata['switch_admin_disable_board.']) : 0;
for ($switch_admin_disable_board_i = 0; $switch_admin_disable_board_i < $switch_admin_disable_board_count; $switch_admin_disable_board_i++)
{
 $switch_admin_disable_board_item = &$this->_tpldata['switch_admin_disable_board.'][$switch_admin_disable_board_i];
 $switch_admin_disable_board_item['S_ROW_COUNT'] = $switch_admin_disable_board_i;
 $switch_admin_disable_board_item['S_NUM_ROWS'] = $switch_admin_disable_board_count;

?>
	<table width="100%" align="center" cellspacing="0" cellpadding="10" border="0">
	<tr><td align="center" class="forumline-no"><div class="genmed"><div class="topic_ann"><?php echo isset($this->vars['L_BOARD_DISABLE']) ? $this->vars['L_BOARD_DISABLE'] : $this->lang('L_BOARD_DISABLE'); ?></div></div></td></tr>
	</table>
	<?php

} // END switch_admin_disable_board

if(isset($switch_admin_disable_board_item)) { unset($switch_admin_disable_board_item); } 

?>

	<?php if ($this->vars['SWITCH_CMS_GLOBAL_BLOCKS']) {  ?>
	<div style="vertical-align: top;"><?php

$header_blocks_row_count = ( isset($this->_tpldata['header_blocks_row.']) ) ? sizeof($this->_tpldata['header_blocks_row.']) : 0;
for ($header_blocks_row_i = 0; $header_blocks_row_i < $header_blocks_row_count; $header_blocks_row_i++)
{
 $header_blocks_row_item = &$this->_tpldata['header_blocks_row.'][$header_blocks_row_i];
 $header_blocks_row_item['S_ROW_COUNT'] = $header_blocks_row_i;
 $header_blocks_row_item['S_NUM_ROWS'] = $header_blocks_row_count;

?><?php echo isset($header_blocks_row_item['CMS_BLOCK']) ? $header_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END header_blocks_row

if(isset($header_blocks_row_item)) { unset($header_blocks_row_item); } 

?></div>
	<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
	<?php if ($this->vars['HL_BLOCK']) {  ?>
	<td id="var_width" width="<?php echo isset($this->vars['HEADER_WIDTH']) ? $this->vars['HEADER_WIDTH'] : $this->lang('HEADER_WIDTH'); ?>" style="width: <?php echo isset($this->vars['HEADER_WIDTH']) ? $this->vars['HEADER_WIDTH'] : $this->lang('HEADER_WIDTH'); ?>px !important;" valign="top">
	<div id="quick_links2" style="padding-top: 5px; display: none; margin-left: 0px; text-align: left; position: relative; float: left;"><a href="javascript:ShowHide('quick_links','quick_links2','quick_links');setWidth('var_width',<?php echo isset($this->vars['HEADER_WIDTH']) ? $this->vars['HEADER_WIDTH'] : $this->lang('HEADER_WIDTH'); ?>);setWidth('full_width','auto');setWidth('full_width_cpl','auto');" title="<?php echo isset($this->vars['L_SHOW']) ? $this->vars['L_SHOW'] : $this->lang('L_SHOW'); ?> <?php echo isset($this->vars['L_QUICK_LINKS']) ? $this->vars['L_QUICK_LINKS'] : $this->lang('L_QUICK_LINKS'); ?>"><img src="<?php echo isset($this->vars['IMG_NAV_MENU_APPLICATION']) ? $this->vars['IMG_NAV_MENU_APPLICATION'] : $this->lang('IMG_NAV_MENU_APPLICATION'); ?>" alt="<?php echo isset($this->vars['L_SHOW']) ? $this->vars['L_SHOW'] : $this->lang('L_SHOW'); ?> <?php echo isset($this->vars['L_QUICK_LINKS']) ? $this->vars['L_QUICK_LINKS'] : $this->lang('L_QUICK_LINKS'); ?>" /></a></div>
	<div id="quick_links"><?php

$headerleft_blocks_row_count = ( isset($this->_tpldata['headerleft_blocks_row.']) ) ? sizeof($this->_tpldata['headerleft_blocks_row.']) : 0;
for ($headerleft_blocks_row_i = 0; $headerleft_blocks_row_i < $headerleft_blocks_row_count; $headerleft_blocks_row_i++)
{
 $headerleft_blocks_row_item = &$this->_tpldata['headerleft_blocks_row.'][$headerleft_blocks_row_i];
 $headerleft_blocks_row_item['S_ROW_COUNT'] = $headerleft_blocks_row_i;
 $headerleft_blocks_row_item['S_NUM_ROWS'] = $headerleft_blocks_row_count;

?><?php echo isset($headerleft_blocks_row_item['CMS_BLOCK']) ? $headerleft_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END headerleft_blocks_row

if(isset($headerleft_blocks_row_item)) { unset($headerleft_blocks_row_item); } 

?></div>
	</td>
	<td width="5"><img src="<?php echo isset($this->vars['SPACER']) ? $this->vars['SPACER'] : $this->lang('SPACER'); ?>" alt="" width="5" height="10" /></td>
	<?php } ?>
	<td id="full_width" valign="top">
	<script type="text/javascript">
	// <![CDATA[
	cellobject = document.getElementById('var_width');
	if((cellobject != null) && !is_ie && ((getWidth('var_width') == 16) | (getWidth('var_width') == '16px')))
	{
		setWidth('full_width', '100%');
		setWidth('full_width_cpl', '100%');
	}
	// ]]>
	</script>
	<?php if ($this->vars['HC_BLOCK']) {  ?><div style="vertical-align: top;"><?php

$headercenter_blocks_row_count = ( isset($this->_tpldata['headercenter_blocks_row.']) ) ? sizeof($this->_tpldata['headercenter_blocks_row.']) : 0;
for ($headercenter_blocks_row_i = 0; $headercenter_blocks_row_i < $headercenter_blocks_row_count; $headercenter_blocks_row_i++)
{
 $headercenter_blocks_row_item = &$this->_tpldata['headercenter_blocks_row.'][$headercenter_blocks_row_i];
 $headercenter_blocks_row_item['S_ROW_COUNT'] = $headercenter_blocks_row_i;
 $headercenter_blocks_row_item['S_NUM_ROWS'] = $headercenter_blocks_row_count;

?><?php echo isset($headercenter_blocks_row_item['CMS_BLOCK']) ? $headercenter_blocks_row_item['CMS_BLOCK'] : ''; ?><?php

} // END headercenter_blocks_row

if(isset($headercenter_blocks_row_item)) { unset($headercenter_blocks_row_item); } 

?></div><?php } ?>
	<?php } ?>