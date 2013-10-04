<?php

// eXtreme Styles mod cache. Generated on Fri, 04 Oct 2013 15:39:59 +0000 (time = 1380901199)

if (!defined('IN_ICYPHOENIX')) exit;

?><?php if ($this->vars['S_HIGHSLIDER'] && ! $this->vars['S_NO_PICS']) {  ?>

<script type="text/javascript">
// <![CDATA[
var <?php echo isset($this->vars['HS_GALLERY_ID']) ? $this->vars['HS_GALLERY_ID'] : $this->lang('HS_GALLERY_ID'); ?> = {
	thumbnailId: '<?php echo isset($this->vars['HS_PIC_ID']) ? $this->vars['HS_PIC_ID'] : $this->lang('HS_PIC_ID'); ?>',
	wrapperClassName: 'dark',
	captionEval: 'this.a.title',
	numberPosition: 'caption',
	useBox: true,
	width: 600,
	height: 450
};
// ]]>
</script>

<div class="highslide-gallery">
	<a class="highslide" id="<?php echo isset($this->vars['HS_PIC_ID']) ? $this->vars['HS_PIC_ID'] : $this->lang('HS_PIC_ID'); ?>" href="<?php echo isset($this->vars['HS_PIC_FULL']) ? $this->vars['HS_PIC_FULL'] : $this->lang('HS_PIC_FULL'); ?>" title="<?php echo isset($this->vars['HS_PIC_TITLE']) ? $this->vars['HS_PIC_TITLE'] : $this->lang('HS_PIC_TITLE'); ?>" onclick="return hs.expand(this, <?php echo isset($this->vars['HS_GALLERY_ID']) ? $this->vars['HS_GALLERY_ID'] : $this->lang('HS_GALLERY_ID'); ?>)"><img src="<?php echo isset($this->vars['HS_PIC_THUMB']) ? $this->vars['HS_PIC_THUMB'] : $this->lang('HS_PIC_THUMB'); ?>" alt="" /></a>
	<div class="hidden-container">
	<?php

$recent_pics_count = ( isset($this->_tpldata['recent_pics.']) ) ? sizeof($this->_tpldata['recent_pics.']) : 0;
for ($recent_pics_i = 0; $recent_pics_i < $recent_pics_count; $recent_pics_i++)
{
 $recent_pics_item = &$this->_tpldata['recent_pics.'][$recent_pics_i];
 $recent_pics_item['S_ROW_COUNT'] = $recent_pics_i;
 $recent_pics_item['S_NUM_ROWS'] = $recent_pics_count;

?>
		<?php

$recent_detail_count = ( isset($recent_pics_item['recent_detail.']) ) ? sizeof($recent_pics_item['recent_detail.']) : 0;
for ($recent_detail_i = 0; $recent_detail_i < $recent_detail_count; $recent_detail_i++)
{
 $recent_detail_item = &$recent_pics_item['recent_detail.'][$recent_detail_i];
 $recent_detail_item['S_ROW_COUNT'] = $recent_detail_i;
 $recent_detail_item['S_NUM_ROWS'] = $recent_detail_count;

?>
		<?php if (! $recent_detail_item['IS_FIRST_PIC']) {  ?>
		<a class="highslide" href="<?php echo isset($recent_detail_item['U_PIC_DL']) ? $recent_detail_item['U_PIC_DL'] : ''; ?>" title="<?php echo isset($recent_detail_item['TITLE']) ? $recent_detail_item['TITLE'] : ''; ?>" onclick="return hs.expand(this, <?php echo isset($this->vars['HS_GALLERY_ID']) ? $this->vars['HS_GALLERY_ID'] : $this->lang('HS_GALLERY_ID'); ?>)"><img src="<?php echo isset($recent_detail_item['THUMBNAIL']) ? $recent_detail_item['THUMBNAIL'] : ''; ?>" alt="<?php echo isset($recent_detail_item['TITLE']) ? $recent_detail_item['TITLE'] : ''; ?>" /></a>
		<?php } ?>
		<?php

} // END recent_detail

if(isset($recent_detail_item)) { unset($recent_detail_item); } 

?>
	<?php

} // END recent_pics

if(isset($recent_pics_item)) { unset($recent_pics_item); } 

?>
	</div>
</div>

<?php } else { ?>

<table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">

<?php

$no_pics_count = ( isset($this->_tpldata['no_pics.']) ) ? sizeof($this->_tpldata['no_pics.']) : 0;
for ($no_pics_i = 0; $no_pics_i < $no_pics_count; $no_pics_i++)
{
 $no_pics_item = &$this->_tpldata['no_pics.'][$no_pics_i];
 $no_pics_item['S_ROW_COUNT'] = $no_pics_i;
 $no_pics_item['S_NUM_ROWS'] = $no_pics_count;

?>
<tr><td align="center" height="50"><span class="gen"><?php echo isset($this->vars['L_NO_PICS']) ? $this->vars['L_NO_PICS'] : $this->lang('L_NO_PICS'); ?></span></td></tr>
<?php

} // END no_pics

if(isset($no_pics_item)) { unset($no_pics_item); } 

?>

<?php

$recent_pics_count = ( isset($this->_tpldata['recent_pics.']) ) ? sizeof($this->_tpldata['recent_pics.']) : 0;
for ($recent_pics_i = 0; $recent_pics_i < $recent_pics_count; $recent_pics_i++)
{
 $recent_pics_item = &$this->_tpldata['recent_pics.'][$recent_pics_i];
 $recent_pics_item['S_ROW_COUNT'] = $recent_pics_i;
 $recent_pics_item['S_NUM_ROWS'] = $recent_pics_count;

?>
<tr>
	<?php

$recent_detail_count = ( isset($recent_pics_item['recent_detail.']) ) ? sizeof($recent_pics_item['recent_detail.']) : 0;
for ($recent_detail_i = 0; $recent_detail_i < $recent_detail_count; $recent_detail_i++)
{
 $recent_detail_item = &$recent_pics_item['recent_detail.'][$recent_detail_i];
 $recent_detail_item['S_ROW_COUNT'] = $recent_detail_i;
 $recent_detail_item['S_NUM_ROWS'] = $recent_detail_count;

?>
	<td class="row1 row-center" width="<?php echo isset($this->vars['S_COL_WIDTH']) ? $this->vars['S_COL_WIDTH'] : $this->lang('S_COL_WIDTH'); ?>">
		<span class="genmed" style="line-height:150%">
			<b><?php echo isset($recent_detail_item['TITLE']) ? $recent_detail_item['TITLE'] : ''; ?></b><br />
			<a href="<?php echo isset($recent_detail_item['U_PIC']) ? $recent_detail_item['U_PIC'] : ''; ?>" <?php echo isset($this->vars['TARGET_BLANK']) ? $this->vars['TARGET_BLANK'] : $this->lang('TARGET_BLANK'); ?>><img src="<?php echo isset($recent_detail_item['THUMBNAIL']) ? $recent_detail_item['THUMBNAIL'] : ''; ?>" alt="<?php echo isset($recent_detail_item['TITLE']) ? $recent_detail_item['TITLE'] : ''; ?>" title="<?php echo isset($recent_detail_item['TITLE']) ? $recent_detail_item['TITLE'] : ''; ?>" /></a><br />
			<b><?php echo isset($recent_detail_item['POSTER']) ? $recent_detail_item['POSTER'] : ''; ?></b><br />
			<?php echo isset($recent_detail_item['TIME']) ? $recent_detail_item['TIME'] : ''; ?>
		</span>
		<br /><br />
	</td>
	<?php

} // END recent_detail

if(isset($recent_detail_item)) { unset($recent_detail_item); } 

?>
	<?php

$recent_no_detail_count = ( isset($recent_pics_item['recent_no_detail.']) ) ? sizeof($recent_pics_item['recent_no_detail.']) : 0;
for ($recent_no_detail_i = 0; $recent_no_detail_i < $recent_no_detail_count; $recent_no_detail_i++)
{
 $recent_no_detail_item = &$recent_pics_item['recent_no_detail.'][$recent_no_detail_i];
 $recent_no_detail_item['S_ROW_COUNT'] = $recent_no_detail_i;
 $recent_no_detail_item['S_NUM_ROWS'] = $recent_no_detail_count;

?>
	<td class="row1 row-center"><span class="genmed" style="line-height: 150%">&nbsp;</span><br /><br /></td>
	<?php

} // END recent_no_detail

if(isset($recent_no_detail_item)) { unset($recent_no_detail_item); } 

?>
</tr>
<?php

} // END recent_pics

if(isset($recent_pics_item)) { unset($recent_pics_item); } 

?>

</table>

<?php } ?>
