<?xml version="1.0" encoding="{CONTENT_ENCODING}" ?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:annotate="http://purl.org/rss/1.0/modules/annotate/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<channel>
	<title>{TITLE}</title>
	<link>{URL}</link>
	<!-- BEGIN image -->
	<image>
		<title>{TITLE}</title>
		<description>{image.IMAGE_TITLE}</description>
		<url>{image.IMAGE}</url>
		<link>{URL}</link>
	</image>
	<!-- END image -->
	<description>{DESC}</description>
	<language>{LANGUAGE}</language>
	<copyright>{COPYRIGHT}</copyright>
	<lastBuildDate>{LAST_BUILD}</lastBuildDate>
	<docs>http://backend.userland.com/rss</docs>
	<generator>{GENERATOR}</generator>
	<category>{CATEGORY}</category>
	<managingEditor>{EDITOR}</managingEditor>
	<webMaster>{WEBMASTER}</webMaster>
	<ttl>{TTL}</ttl>
	<!-- BEGIN articles -->
	<item>
		<title>{articles.L_TITLE}</title>
		<link>{INDEX_FILE}?topic_id={articles.ID}</link>
		<pubDate>{articles.RFC_POST_DATE}</pubDate>
		<guid isPermaLink="true">{articles.U_COMMENTS}</guid>
		<category><![CDATA[{articles.FORUM_NAME}]]></category>
		<description><![CDATA[{articles.BODY}]]></description>
		<comments>{articles.U_COMMENTS}</comments>
	</item>
	<!-- END articles -->
</channel>
</rss>