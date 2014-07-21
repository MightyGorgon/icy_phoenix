<h1>{L_CONFIGURATION_TITLE}</h1>
<p>{L_CONFIGURATION_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post">
<table class="forumline">
<tr><th colspan="2">{L_GENERAL_SETTINGS}</th></tr>
<tr>
	<td class="row1">{L_ALLOW_NEWS_POSTING}</td>
	<td class="row2"><input type="radio" name="allow_news" value="1" {NEWS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_news" value="0" {NEWS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_NEWS_BASE_URL} <br /><span class="gensmall">{L_NEWS_BASE_URL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_base_url" value="{NEWS_BASE_URL}" /></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_INDEX_FILE} <br /><span class="gensmall">{L_NEWS_INDEX_FILE_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_index_file" value="{NEWS_INDEX_FILE}" /></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_TRIM} <br /><span class="gensmall">{L_NEWS_TRIM_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_item_trim" value="{NEWS_ITEM_LENGTH}" /></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_TOPIC_TRIM} <br /><span class="gensmall">{L_NEWS_TOPIC_TRIM_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_title_trim" value="{NEWS_TITLE_LENGTH}" /></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_ITEMS_DISPLAY}<br /><span class="gensmall">{L_NEWS_ITEMS_DISPLAY_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_item_num" value="{NEWS_ITEM_NUM}" /></td>
</tr>
<tr>
	<td class="row1">{L_NEWS_PATH} <br /><span class="gensmall">{L_NEWS_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_path" value="{NEWS_PATH}" /></td>
</tr>
<tr>
	<th colspan="2">{L_RSS_SETTINGS}</th>
</tr>
<tr>
	<td class="row1">{L_ALLOW_RSS} <br /><span class="gensmall">{L_ALLOW_RSS_EXPLAIN}</td>
	<td class="row2"><input type="radio" name="allow_rss" value="1" {RSS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_rss" value="0" {RSS_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_NEWS_ITEMS_DISPLAY}</td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="3" name="news_rss_item_count" value="{RSS_ITEM_COUNT}" /></td>
</tr>
<tr>
	<td class="row1">{L_RSS_SHOW_ABSTRACT}</td>
	<td class="row2"><input type="radio" name="news_rss_show_abstract" value="1" {RSS_ABSTRACT_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="news_rss_show_abstract" value="0" {RSS_ABSTRACT_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="row1">{L_RSS_DESC} <br /><span class="gensmall">{L_RSS_DESC_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_rss_desc" value="{RSS_DESC}" /></td>
</tr>
<tr>
	<td class="row1">{L_RSS_LANG} <br /><span class="gensmall">{L_RSS_LANG_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_rss_language" value="{RSS_LANG}" /></td>
</tr>
<tr>
	<td class="row1">{L_RSS_TTL} <br /><span class="gensmall">{L_RSS_TTL_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_rss_ttl" value="{RSS_TTL}" /></td>
</tr>
<tr>
	<td class="row1">{L_RSS_CAT} <br /><span class="gensmall">{L_RSS_CAT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_rss_cat" value="{RSS_CAT}" /></td>
</tr>
<tr>
	<td class="row1">{L_RSS_IMG} <br /><span class="gensmall">{L_RSS_IMG_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_rss_image" value="{RSS_IMG}" /></td>
</tr>
<tr>
	<td class="row1">{L_RSS_IMG_DESC} <br /><span class="gensmall">{L_RSS_IMG_DESC_EXPLAIN}</span></td>
	<td class="row2"><input class="post" type="text" size="20" maxlength="255" name="news_rss_image_desc" value="{RSS_IMG_DESC}" /></td>
</tr>
<tr>
	<td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</table>
</form>

<br clear="all" />
