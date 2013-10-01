<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:44:17 +0000 (time = 1380635057)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php if ($this->vars['S_SHOW_AVATARS']) {  ?>
<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<?php

$topposter_count = ( isset($this->_tpldata['topposter.']) ) ? sizeof($this->_tpldata['topposter.']) : 0;
for ($topposter_i = 0; $topposter_i < $topposter_count; $topposter_i++)
{
 $topposter_item = &$this->_tpldata['topposter.'][$topposter_i];
 $topposter_item['S_ROW_COUNT'] = $topposter_i;
 $topposter_item['S_NUM_ROWS'] = $topposter_count;

?>
<tr>
	<td class="row1 row-center">
		<br /><a href="<?php echo isset($topposter_item['U_VIEWPOSTER']) ? $topposter_item['U_VIEWPOSTER'] : ''; ?>" class="gensmall"><?php echo isset($topposter_item['AVATAR_IMG']) ? $topposter_item['AVATAR_IMG'] : ''; ?></a><br /><br />
		<?php echo isset($topposter_item['USERNAME']) ? $topposter_item['USERNAME'] : ''; ?>&nbsp;[<b><a href="<?php echo isset($topposter_item['U_VIEWPOSTS']) ? $topposter_item['U_VIEWPOSTS'] : ''; ?>" class="gensmall"><?php echo isset($topposter_item['POSTS']) ? $topposter_item['POSTS'] : ''; ?></a></b>]<br /><br />
	</td>
</tr>
<?php

} // END topposter

if(isset($topposter_item)) { unset($topposter_item); } 

?>
</table>
<br />
<?php } else { ?>
<?php

$topposter_count = ( isset($this->_tpldata['topposter.']) ) ? sizeof($this->_tpldata['topposter.']) : 0;
for ($topposter_i = 0; $topposter_i < $topposter_count; $topposter_i++)
{
 $topposter_item = &$this->_tpldata['topposter.'][$topposter_i];
 $topposter_item['S_ROW_COUNT'] = $topposter_i;
 $topposter_item['S_NUM_ROWS'] = $topposter_count;

?>
<div style="float: right;">[<b><a href="<?php echo isset($topposter_item['U_VIEWPOSTS']) ? $topposter_item['U_VIEWPOSTS'] : ''; ?>" class="gensmall"><?php echo isset($topposter_item['POSTS']) ? $topposter_item['POSTS'] : ''; ?></a></b>]</div><?php echo isset($topposter_item['USERNAME']) ? $topposter_item['USERNAME'] : ''; ?>&nbsp;<br clear="all" />
<?php

} // END topposter

if(isset($topposter_item)) { unset($topposter_item); } 

?>
<?php } ?>
