<h1>{L_NEWS_TITLE}</h1>
<p>{L_NEWS_TEXT}</p>

<form method="post" action="{S_NEWS_ACTION}">
<table class="forumline talignc">
<tr>
	<th>{L_ICON}</th>
	<th>{L_CATEGORY}</th>
	<th>{L_TOPICS}</th>
	<th colspan="2">{L_ACTION}</th>
</tr>
<tr><td class="row1 row-center" colspan="6">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_NEWS_ADD}" class="mainoption" /></td></tr>
<!-- BEGIN news_cats -->
<tr>
	<td class="row1 row-center">
		<!-- IF news_cats.CATEGORY_IMG -->
		<img src="{news_cats.CATEGORY_IMG}" alt="{news_cats.L_CATEGORY}" />
		<!-- ENDIF -->
	</td>
	<td class="row1 row-center">{news_cats.L_CATEGORY}</td>
	<td class="row1 row-center">{news_cats.TOPIC_COUNT}</td>
	<td class="row1 row-center"><a href="{news_cats.U_NEWS_EDIT}">{L_EDIT}</a></td>
	<td class="row1 row-center"><a href="{news_cats.U_NEWS_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END news_cats -->
<tr><td class="cat" colspan="6" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_NEWS_ADD}" class="mainoption" /></td></tr>
</table>
</form>