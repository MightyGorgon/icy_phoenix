<?xml version="1.0" encoding="{S_CONTENT_ENCODING}" ?>
<feed version="0.3" xmlns="http://purl.org/atom/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/">
<!--
	This feed generated for {READER}
	More info at http://naklon.info/rss/about.htm
-->
	<title>{BOARD_TITLE}</title>
	<generator>{PROGRAM}</generator>
	<link rel="alternate" type="text/html" href="{BOARD_URL}"/>
	<modified>{ATOM_BUILD_DATE}</modified>
<!-- BEGIN post_item -->
	<entry>
		<title mode="escaped">{post_item.FORUM_NAME} :: {post_item.TOPIC_TITLE}</title>
		<link rel="alternate" type="text/html" href="{post_item.POST_URL}"/>
		<dc:creator>{post_item.AUTHOR0}</dc:creator>
		<dc:subject>{post_item.FORUM_NAME}</dc:subject>
		<author>
		<name>{post_item.AUTHOR0}</name>
		</author>
		<id>{post_item.POST_URL}</id>
		<issued>{post_item.ATOM_TIME}</issued>
		<modified>{post_item.ATOM_TIME_M}</modified>
	<content type="text/html" mode="escaped">{L_AUTHOR}: {post_item.AUTHOR}&lt;br /&gt;
	{post_item.POST_SUBJECT}{L_POSTED}: {post_item.POST_TIME}&lt;br /&gt;&lt;br /&gt;&lt;div class="post-text post-text-hide-flow"&gt;
	{post_item.POST_TEXT}{post_item.USER_SIG}&lt;/div&gt;&lt;br /&gt;
	</content>
	</entry>
<!-- END post_item -->
</feed>