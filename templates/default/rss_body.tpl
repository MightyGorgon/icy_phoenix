<?xml version="1.0" encoding="{S_CONTENT_ENCODING}" ?>
<!-- BEGIN switch_enable_xslt -->
<?xml-stylesheet type="text/xsl" href="templates/common/rss.xsl"?> 
<!-- END switch_enable_xslt -->
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:annotate="http://purl.org/rss/1.0/modules/annotate/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
<!--
	This feed generated for {READER}
	More info at http://naklon.info/rss/about.htm
-->
<channel>
<title><![CDATA[{BOARD_TITLE}]]></title>
<link>{BOARD_URL}</link>
<description><![CDATA[{BOARD_DESCRIPTION}]]></description>
<managingEditor>{BOARD_MANAGING_EDITOR}</managingEditor>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>{PROGRAM}</generator>{LANGUAGE}
<lastBuildDate>{BUILD_DATE}</lastBuildDate>
<image>
	<url>{BOARD_URL}images/links/banner_ip.gif</url>
	<title><![CDATA[{BOARD_TITLE}]]></title>
	<link>{BOARD_URL}</link>
	<width>88</width>
	<height>31</height>
</image>
<!-- BEGIN post_item -->
<item>
<title><![CDATA[{post_item.FORUM_NAME} :: {post_item.TOPIC_TITLE}]]></title>
<link>{post_item.POST_URL}</link>
<pubDate>{post_item.UTF_TIME}</pubDate>
<guid isPermaLink="true">{post_item.POST_URL}</guid>
<description>
<![CDATA[
{L_AUTHOR}: {post_item.AUTHOR}<br />
{post_item.POST_SUBJECT}
{L_POSTED}: {post_item.POST_TIME}<br />

<br /><div class="post-text post-text-hide-flow">{post_item.POST_TEXT}{post_item.USER_SIG}</div><br />
]]>
</description>
<dc:creator><![CDATA[{post_item.AUTHOR0}]]></dc:creator>
<dc:subject><![CDATA[{post_item.FORUM_NAME}]]></dc:subject>
<annotate:reference rdf:resource="{post_item.FIRST_POST_URL}" />
<comments>{post_item.REPLY_URL}</comments>
</item>
<!-- END post_item -->
</channel>
</rss>
