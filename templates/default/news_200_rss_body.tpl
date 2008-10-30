<?xml version="1.0" encoding="{CONTENT_ENCODING}" ?>

<rss version="2.0">
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
	<copyright>{COPY_RIGHT}</copyright>
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
		<pubDate>{articles.RFC_POST_DATE}</pubDate>
		<link>{INDEX_FILE}?topic_id={articles.ID}</link>
		<category>{articles.FORUM_NAME}</category>
		<description>
		<![CDATA[{articles.BODY}]]>
		</description>
		<comments>{articles.U_COMMENTS}</comments>
	</item>
	<!-- END articles -->
</channel>
</rss>