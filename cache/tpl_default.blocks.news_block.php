<?php

// eXtreme Styles mod cache. Generated on Tue, 01 Oct 2013 13:44:17 +0000 (time = 1380635057)

if (!defined('IN_ICYPHOENIX')) exit;

?><table class="empty-table" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
		<?php

$news_categories_count = ( isset($this->_tpldata['news_categories.']) ) ? sizeof($this->_tpldata['news_categories.']) : 0;
for ($news_categories_i = 0; $news_categories_i < $news_categories_count; $news_categories_i++)
{
 $news_categories_item = &$this->_tpldata['news_categories.'][$news_categories_i];
 $news_categories_item['S_ROW_COUNT'] = $news_categories_i;
 $news_categories_item['S_NUM_ROWS'] = $news_categories_count;

?>
		<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td class="row-header" colspan="<?php echo isset($this->vars['S_COLS']) ? $this->vars['S_COLS'] : $this->lang('S_COLS'); ?>" nowrap="nowrap"><span><?php echo isset($this->vars['L_NEWS_CATS']) ? $this->vars['L_NEWS_CATS'] : $this->lang('L_NEWS_CATS'); ?></span></td></tr>
		<?php

} // END news_categories

if(isset($news_categories_item)) { unset($news_categories_item); } 

?>
		<?php

$no_news_count = ( isset($this->_tpldata['no_news.']) ) ? sizeof($this->_tpldata['no_news.']) : 0;
for ($no_news_i = 0; $no_news_i < $no_news_count; $no_news_i++)
{
 $no_news_item = &$this->_tpldata['no_news.'][$no_news_i];
 $no_news_item['S_ROW_COUNT'] = $no_news_i;
 $no_news_item['S_NUM_ROWS'] = $no_news_count;

?>
		<tr><td class="row1 row-center" height="50"><span class="gen"><?php echo isset($this->vars['L_NO_NEWS_CATS']) ? $this->vars['L_NO_NEWS_CATS'] : $this->lang('L_NO_NEWS_CATS'); ?></span></td></tr>
		<?php

} // END no_news

if(isset($no_news_item)) { unset($no_news_item); } 

?>

		<?php

$newsrow_count = ( isset($this->_tpldata['newsrow.']) ) ? sizeof($this->_tpldata['newsrow.']) : 0;
for ($newsrow_i = 0; $newsrow_i < $newsrow_count; $newsrow_i++)
{
 $newsrow_item = &$this->_tpldata['newsrow.'][$newsrow_i];
 $newsrow_item['S_ROW_COUNT'] = $newsrow_i;
 $newsrow_item['S_NUM_ROWS'] = $newsrow_count;

?>
		<tr>
			<?php

$newscol_count = ( isset($newsrow_item['newscol.']) ) ? sizeof($newsrow_item['newscol.']) : 0;
for ($newscol_i = 0; $newscol_i < $newscol_count; $newscol_i++)
{
 $newscol_item = &$newsrow_item['newscol.'][$newscol_i];
 $newscol_item['S_ROW_COUNT'] = $newscol_i;
 $newscol_item['S_NUM_ROWS'] = $newscol_count;

?>
			<td width="25%" class="row1 row-center"><span class="genmed"><a href="<?php echo isset($this->vars['INDEX_FILE']) ? $this->vars['INDEX_FILE'] : $this->lang('INDEX_FILE'); ?>?<?php echo isset($this->vars['PORTAL_PAGE_ID']) ? $this->vars['PORTAL_PAGE_ID'] : $this->lang('PORTAL_PAGE_ID'); ?>cat_id=<?php echo isset($newscol_item['ID']) ? $newscol_item['ID'] : ''; ?>"><img src="<?php echo isset($newscol_item['THUMBNAIL']) ? $newscol_item['THUMBNAIL'] : ''; ?>" alt="<?php echo isset($newscol_item['DESC']) ? $newscol_item['DESC'] : ''; ?>" title="<?php echo isset($newscol_item['DESC']) ? $newscol_item['DESC'] : ''; ?>" vspace="10" /></a></span></td>
			<?php

} // END newscol

if(isset($newscol_item)) { unset($newscol_item); } 

?>
		</tr>
		<tr>
			<?php

$news_detail_count = ( isset($newsrow_item['news_detail.']) ) ? sizeof($newsrow_item['news_detail.']) : 0;
for ($news_detail_i = 0; $news_detail_i < $news_detail_count; $news_detail_i++)
{
 $news_detail_item = &$newsrow_item['news_detail.'][$news_detail_i];
 $news_detail_item['S_ROW_COUNT'] = $news_detail_i;
 $news_detail_item['S_NUM_ROWS'] = $news_detail_count;

?>
			<td class="row1g-left row-center"><span class="forumlink"><?php echo isset($news_detail_item['NEWSCAT']) ? $news_detail_item['NEWSCAT'] : ''; ?></span></td>
			<?php

} // END news_detail

if(isset($news_detail_item)) { unset($news_detail_item); } 

?>
		</tr>
		<?php

} // END newsrow

if(isset($newsrow_item)) { unset($newsrow_item); } 

?>

		<?php

$news_categories_count = ( isset($this->_tpldata['news_categories.']) ) ? sizeof($this->_tpldata['news_categories.']) : 0;
for ($news_categories_i = 0; $news_categories_i < $news_categories_count; $news_categories_i++)
{
 $news_categories_item = &$this->_tpldata['news_categories.'][$news_categories_i];
 $news_categories_item['S_ROW_COUNT'] = $news_categories_i;
 $news_categories_item['S_NUM_ROWS'] = $news_categories_count;

?>
		</table>
		<br />
		<?php

} // END news_categories

if(isset($news_categories_item)) { unset($news_categories_item); } 

?>

		<?php

$news_archives_count = ( isset($this->_tpldata['news_archives.']) ) ? sizeof($this->_tpldata['news_archives.']) : 0;
for ($news_archives_i = 0; $news_archives_i < $news_archives_count; $news_archives_i++)
{
 $news_archives_item = &$this->_tpldata['news_archives.'][$news_archives_i];
 $news_archives_item['S_ROW_COUNT'] = $news_archives_i;
 $news_archives_item['S_NUM_ROWS'] = $news_archives_count;

?>
		<?php echo isset($this->vars['IMG_TBL']) ? $this->vars['IMG_TBL'] : $this->lang('IMG_TBL'); ?><table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr><td class="row-header"><span><?php echo isset($this->vars['L_NEWS_ARCHIVES']) ? $this->vars['L_NEWS_ARCHIVES'] : $this->lang('L_NEWS_ARCHIVES'); ?></span></td></tr>
		<tr>
			<td class="row1">
		<?php

} // END news_archives

if(isset($news_archives_item)) { unset($news_archives_item); } 

?>
			<?php

$arch_count = ( isset($this->_tpldata['arch.']) ) ? sizeof($this->_tpldata['arch.']) : 0;
for ($arch_i = 0; $arch_i < $arch_count; $arch_i++)
{
 $arch_item = &$this->_tpldata['arch.'][$arch_i];
 $arch_item['S_ROW_COUNT'] = $arch_i;
 $arch_item['S_NUM_ROWS'] = $arch_count;

?>
				<ul style=" padding: 0 1.3em; margin: 5px 10px;">
				<?php

$year_count = ( isset($arch_item['year.']) ) ? sizeof($arch_item['year.']) : 0;
for ($year_i = 0; $year_i < $year_count; $year_i++)
{
 $year_item = &$arch_item['year.'][$year_i];
 $year_item['S_ROW_COUNT'] = $year_i;
 $year_item['S_NUM_ROWS'] = $year_count;

?>
					<li class="gen"><a href="<?php echo isset($this->vars['INDEX_FILE']) ? $this->vars['INDEX_FILE'] : $this->lang('INDEX_FILE'); ?>?<?php echo isset($this->vars['PORTAL_PAGE_ID']) ? $this->vars['PORTAL_PAGE_ID'] : $this->lang('PORTAL_PAGE_ID'); ?>news=archives&amp;year=<?php echo isset($year_item['YEAR']) ? $year_item['YEAR'] : ''; ?>"><?php echo isset($year_item['YEAR']) ? $year_item['YEAR'] : ''; ?></a></li>
					<?php

$month_count = ( isset($year_item['month.']) ) ? sizeof($year_item['month.']) : 0;
for ($month_i = 0; $month_i < $month_count; $month_i++)
{
 $month_item = &$year_item['month.'][$month_i];
 $month_item['S_ROW_COUNT'] = $month_i;
 $month_item['S_NUM_ROWS'] = $month_count;

?>
					<li class="gen" style="margin-left: 1em;"> <a href="<?php echo isset($this->vars['INDEX_FILE']) ? $this->vars['INDEX_FILE'] : $this->lang('INDEX_FILE'); ?>?<?php echo isset($this->vars['PORTAL_PAGE_ID']) ? $this->vars['PORTAL_PAGE_ID'] : $this->lang('PORTAL_PAGE_ID'); ?>news=archives&amp;year=<?php echo isset($year_item['YEAR']) ? $year_item['YEAR'] : ''; ?>&amp;month=<?php echo isset($month_item['MONTH']) ? $month_item['MONTH'] : ''; ?>"><?php echo isset($month_item['L_MONTH']) ? $month_item['L_MONTH'] : ''; ?> <?php echo isset($month_item['POST_COUNT']) ? $month_item['POST_COUNT'] : ''; ?> </a></li>
					<?php

$day_count = ( isset($month_item['day.']) ) ? sizeof($month_item['day.']) : 0;
for ($day_i = 0; $day_i < $day_count; $day_i++)
{
 $day_item = &$month_item['day.'][$day_i];
 $day_item['S_ROW_COUNT'] = $day_i;
 $day_item['S_NUM_ROWS'] = $day_count;

?>
					<li class="gen" style="margin-left: 2em;"> <a href="<?php echo isset($this->vars['INDEX_FILE']) ? $this->vars['INDEX_FILE'] : $this->lang('INDEX_FILE'); ?>?<?php echo isset($this->vars['PORTAL_PAGE_ID']) ? $this->vars['PORTAL_PAGE_ID'] : $this->lang('PORTAL_PAGE_ID'); ?>news=archives&amp;year=<?php echo isset($year_item['YEAR']) ? $year_item['YEAR'] : ''; ?>&amp;month=<?php echo isset($month_item['MONTH']) ? $month_item['MONTH'] : ''; ?>&amp;day=<?php echo isset($day_item['DAY']) ? $day_item['DAY'] : ''; ?>"><?php echo isset($day_item['L_DAY3']) ? $day_item['L_DAY3'] : ''; ?> <?php echo isset($day_item['L_DAY2']) ? $day_item['L_DAY2'] : ''; ?> <?php echo isset($day_item['L_DAY']) ? $day_item['L_DAY'] : ''; ?> <?php echo isset($day_item['POST_COUNT']) ? $day_item['POST_COUNT'] : ''; ?></a></li>
					<?php

} // END day

if(isset($day_item)) { unset($day_item); } 

?>
					<?php

} // END month

if(isset($month_item)) { unset($month_item); } 

?>
				<?php

} // END year

if(isset($year_item)) { unset($year_item); } 

?>
				</ul>
			<?php

} // END arch

if(isset($arch_item)) { unset($arch_item); } 

?>
		<?php

$news_archives_count = ( isset($this->_tpldata['news_archives.']) ) ? sizeof($this->_tpldata['news_archives.']) : 0;
for ($news_archives_i = 0; $news_archives_i < $news_archives_count; $news_archives_i++)
{
 $news_archives_item = &$this->_tpldata['news_archives.'][$news_archives_i];
 $news_archives_item['S_ROW_COUNT'] = $news_archives_i;
 $news_archives_item['S_NUM_ROWS'] = $news_archives_count;

?>
			</td>
		</tr>
		</table><?php echo isset($this->vars['IMG_TBR']) ? $this->vars['IMG_TBR'] : $this->lang('IMG_TBR'); ?>
		<br />
		<?php

} // END news_archives

if(isset($news_archives_item)) { unset($news_archives_item); } 

?>

		<?php

$no_articles_count = ( isset($this->_tpldata['no_articles.']) ) ? sizeof($this->_tpldata['no_articles.']) : 0;
for ($no_articles_i = 0; $no_articles_i < $no_articles_count; $no_articles_i++)
{
 $no_articles_item = &$this->_tpldata['no_articles.'][$no_articles_i];
 $no_articles_item['S_ROW_COUNT'] = $no_articles_i;
 $no_articles_item['S_NUM_ROWS'] = $no_articles_count;

?>
		<table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><td class="row1 row-center" height="50"><span class="gen"><?php echo isset($this->vars['L_NO_NEWS']) ? $this->vars['L_NO_NEWS'] : $this->lang('L_NO_NEWS'); ?></span></td></tr>
		</table>
		<br />
		<?php

} // END no_articles

if(isset($no_articles_item)) { unset($no_articles_item); } 

?>
		<?php

$articles_count = ( isset($this->_tpldata['articles.']) ) ? sizeof($this->_tpldata['articles.']) : 0;
for ($articles_i = 0; $articles_i < $articles_count; $articles_i++)
{
 $articles_item = &$this->_tpldata['articles.'][$articles_i];
 $articles_item['S_ROW_COUNT'] = $articles_i;
 $articles_item['S_NUM_ROWS'] = $articles_count;

?>
		<?php echo isset($this->vars['IMG_THL']) ? $this->vars['IMG_THL'] : $this->lang('IMG_THL'); ?><?php echo isset($this->vars['IMG_THC']) ? $this->vars['IMG_THC'] : $this->lang('IMG_THC'); ?><a href="<?php echo isset($articles_item['U_COMMENT']) ? $articles_item['U_COMMENT'] : ''; ?>" class="forumlink"><?php echo isset($articles_item['L_TITLE']) ? $articles_item['L_TITLE'] : ''; ?></a><?php echo isset($this->vars['IMG_THR']) ? $this->vars['IMG_THR'] : $this->lang('IMG_THR'); ?><table class="forumlinenb" width="100%" cellspacing="0" cellpadding="2" border="0">
		<tr>
			<th align="left" colspan="2"><span class="gensmall"><a href="<?php echo isset($articles_item['U_COMMENT']) ? $articles_item['U_COMMENT'] : ''; ?>"><img src="<?php echo isset($this->vars['MINIPOST_IMG']) ? $this->vars['MINIPOST_IMG'] : $this->lang('MINIPOST_IMG'); ?>" alt="<?php echo isset($articles_item['L_TITLE']) ? $articles_item['L_TITLE'] : ''; ?>" /></a>&nbsp;<?php echo isset($this->vars['L_POSTED']) ? $this->vars['L_POSTED'] : $this->lang('L_POSTED'); ?>&nbsp;<?php echo isset($this->vars['L_WORD_ON']) ? $this->vars['L_WORD_ON'] : $this->lang('L_WORD_ON'); ?>&nbsp;<?php echo isset($articles_item['POST_DATE']) ? $articles_item['POST_DATE'] : ''; ?>&nbsp;<?php echo isset($this->vars['L_BY']) ? $this->vars['L_BY'] : $this->lang('L_BY'); ?>&nbsp;<?php echo isset($articles_item['L_POSTER']) ? $articles_item['L_POSTER'] : ''; ?></span></th>
		</tr>
		<tr>
			<td class="row-post" style="border-right-width: 0px;">
				<div style="padding-left: 5px; padding-right: 5px; vertical-align: top; text-align: center;"><a href="<?php echo isset($this->vars['INDEX_FILE']) ? $this->vars['INDEX_FILE'] : $this->lang('INDEX_FILE'); ?>?<?php echo isset($this->vars['PORTAL_PAGE_ID']) ? $this->vars['PORTAL_PAGE_ID'] : $this->lang('PORTAL_PAGE_ID'); ?>cat_id=<?php echo isset($articles_item['CAT_ID']) ? $articles_item['CAT_ID'] : ''; ?>" title ="<?php echo isset($articles_item['CATEGORY']) ? $articles_item['CATEGORY'] : ''; ?>"><img src="<?php echo isset($articles_item['CAT_IMG']) ? $articles_item['CAT_IMG'] : ''; ?>" alt="<?php echo isset($articles_item['CATEGORY']) ? $articles_item['CATEGORY'] : ''; ?>" /></a></div>
			</td>
			<td class="row-post" style="border-left-width: 0px; width: 100%;">
				<div class="post-text" style="padding: 2px;"><?php echo isset($articles_item['BODY']) ? $articles_item['BODY'] : ''; ?></div>
				<div class="content-padding"><?php echo isset($articles_item['ATTACHMENTS']) ? $articles_item['ATTACHMENTS'] : ''; ?></div><br /><br />
				<span class="gensmall"><?php echo isset($articles_item['READ_MORE_LINK']) ? $articles_item['READ_MORE_LINK'] : ''; ?>&nbsp;</span><br /><br />
			</td>
		</tr>
		<tr>
			<td class="cat" colspan="2">
				<span style="float: right; text-align: right; padding-right: 5px; padding-top: 5px;"><?php if (! $this->vars['S_BOT']) {  ?><a href="<?php echo isset($articles_item['U_POST_COMMENT']) ? $articles_item['U_POST_COMMENT'] : ''; ?>"><img src="<?php echo isset($this->vars['NEWS_REPLY_IMG']) ? $this->vars['NEWS_REPLY_IMG'] : $this->lang('NEWS_REPLY_IMG'); ?>" alt="<?php echo isset($this->vars['L_REPLY_NEWS']) ? $this->vars['L_REPLY_NEWS'] : $this->lang('L_REPLY_NEWS'); ?>" title="<?php echo isset($this->vars['L_REPLY_NEWS']) ? $this->vars['L_REPLY_NEWS'] : $this->lang('L_REPLY_NEWS'); ?>" /></a>&nbsp;<a href="<?php echo isset($articles_item['U_PRINT_TOPIC']) ? $articles_item['U_PRINT_TOPIC'] : ''; ?>" target="_blank"><img src="<?php echo isset($this->vars['NEWS_PRINT_IMG']) ? $this->vars['NEWS_PRINT_IMG'] : $this->lang('NEWS_PRINT_IMG'); ?>" alt="<?php echo isset($this->vars['L_PRINT_NEWS']) ? $this->vars['L_PRINT_NEWS'] : $this->lang('L_PRINT_NEWS'); ?>" title="<?php echo isset($this->vars['L_PRINT_NEWS']) ? $this->vars['L_PRINT_NEWS'] : $this->lang('L_PRINT_NEWS'); ?>" /></a>&nbsp;<a href="<?php echo isset($articles_item['U_EMAIL_TOPIC']) ? $articles_item['U_EMAIL_TOPIC'] : ''; ?>"><img src="<?php echo isset($this->vars['NEWS_EMAIL_IMG']) ? $this->vars['NEWS_EMAIL_IMG'] : $this->lang('NEWS_EMAIL_IMG'); ?>" alt="<?php echo isset($this->vars['L_EMAIL_NEWS']) ? $this->vars['L_EMAIL_NEWS'] : $this->lang('L_EMAIL_NEWS'); ?>" title="<?php echo isset($this->vars['L_EMAIL_NEWS']) ? $this->vars['L_EMAIL_NEWS'] : $this->lang('L_EMAIL_NEWS'); ?>" /></a><?php } else { ?>&nbsp;<?php } ?></span>
				<div class="gensmall" style="text-align: left; padding-left: 5px; padding-top: 5px;"><?php echo isset($this->vars['L_NEWS_SUMMARY']) ? $this->vars['L_NEWS_SUMMARY'] : $this->lang('L_NEWS_SUMMARY'); ?>&nbsp;<?php if ($this->vars['S_NEWS_VIEWS']) {  ?><a href="<?php echo isset($articles_item['U_VIEWS']) ? $articles_item['U_VIEWS'] : ''; ?>"><?php } ?><b><?php echo isset($articles_item['COUNT_VIEWS']) ? $articles_item['COUNT_VIEWS'] : ''; ?></b>&nbsp;<?php echo isset($this->vars['L_NEWS_VIEWS']) ? $this->vars['L_NEWS_VIEWS'] : $this->lang('L_NEWS_VIEWS'); ?><?php if ($this->vars['S_NEWS_VIEWS']) {  ?></a><?php } ?>&nbsp;<?php echo isset($this->vars['L_NEWS_AND']) ? $this->vars['L_NEWS_AND'] : $this->lang('L_NEWS_AND'); ?>&nbsp;<?php if (! $this->vars['S_BOT']) {  ?><a href="<?php echo isset($this->vars['INDEX_FILE']) ? $this->vars['INDEX_FILE'] : $this->lang('INDEX_FILE'); ?>?<?php echo isset($this->vars['PORTAL_PAGE_ID']) ? $this->vars['PORTAL_PAGE_ID'] : $this->lang('PORTAL_PAGE_ID'); ?>topic_id=<?php echo isset($articles_item['ID']) ? $articles_item['ID'] : ''; ?>" rel="nofollow" title="<?php echo isset($articles_item['L_TITLE']) ? $articles_item['L_TITLE'] : ''; ?>"><?php } ?><b><?php echo isset($articles_item['COUNT_COMMENTS']) ? $articles_item['COUNT_COMMENTS'] : ''; ?></b>&nbsp;<?php echo isset($this->vars['L_NEWS_COMMENTS']) ? $this->vars['L_NEWS_COMMENTS'] : $this->lang('L_NEWS_COMMENTS'); ?><?php if (! $this->vars['S_BOT']) {  ?></a><?php } ?></div>
			</td>
		</tr>
		</table><?php echo isset($this->vars['IMG_TFL']) ? $this->vars['IMG_TFL'] : $this->lang('IMG_TFL'); ?><?php echo isset($this->vars['IMG_TFC']) ? $this->vars['IMG_TFC'] : $this->lang('IMG_TFC'); ?><?php echo isset($this->vars['IMG_TFR']) ? $this->vars['IMG_TFR'] : $this->lang('IMG_TFR'); ?>
		<?php

} // END articles

if(isset($articles_item)) { unset($articles_item); } 

?>
		<?php

$comments_count = ( isset($this->_tpldata['comments.']) ) ? sizeof($this->_tpldata['comments.']) : 0;
for ($comments_i = 0; $comments_i < $comments_count; $comments_i++)
{
 $comments_item = &$this->_tpldata['comments.'][$comments_i];
 $comments_item['S_ROW_COUNT'] = $comments_i;
 $comments_item['S_NUM_ROWS'] = $comments_count;

?>
		<?php echo isset($this->vars['IMG_TBL']) ? $this->vars['IMG_TBL'] : $this->lang('IMG_TBL'); ?><table class="forumline" width="100%" cellspacing="0" cellpadding="0">
		<tr><th align="left"><span class="gensmall"><span style="float: right; text-align: right;"><?php echo isset($comments_item['POST_DATE']) ? $comments_item['POST_DATE'] : ''; ?> <?php echo isset($this->vars['L_BY']) ? $this->vars['L_BY'] : $this->lang('L_BY'); ?> <?php echo isset($comments_item['L_POSTER']) ? $comments_item['L_POSTER'] : ''; ?></span><?php echo isset($comments_item['L_TITLE']) ? $comments_item['L_TITLE'] : ''; ?></span></th></tr>
		<tr><td class="row-post" width="100%"><div class="post-text"><?php echo isset($comments_item['BODY']) ? $comments_item['BODY'] : ''; ?></div></td></tr>
		</table><?php echo isset($this->vars['IMG_TBR']) ? $this->vars['IMG_TBR'] : $this->lang('IMG_TBR'); ?>
		<?php

} // END comments

if(isset($comments_item)) { unset($comments_item); } 

?>
		<?php

$pagination_count = ( isset($this->_tpldata['pagination.']) ) ? sizeof($this->_tpldata['pagination.']) : 0;
for ($pagination_i = 0; $pagination_i < $pagination_count; $pagination_i++)
{
 $pagination_item = &$this->_tpldata['pagination.'][$pagination_i];
 $pagination_item['S_ROW_COUNT'] = $pagination_i;
 $pagination_item['S_NUM_ROWS'] = $pagination_count;

?>
		<div style="text-align: right; padding: 10px; margin: 10px 0 10px 20px; clear: both;"><span class="pagination"><?php echo isset($pagination_item['PAGINATION']) ? $pagination_item['PAGINATION'] : ''; ?></span></div>
		<?php

} // END pagination

if(isset($pagination_item)) { unset($pagination_item); } 

?>
	</td>
</tr>
</table>