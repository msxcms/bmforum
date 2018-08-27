<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="html" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" />
	<xsl:variable name="title" select="/rss/channel/title"/>
	<xsl:variable name="feedUrl" select="/rss/channel/atom:link[@rel='self']/@href" xmlns:atom="http://purl.org/atom/ns#"/>
	<xsl:template match="/">
	<xsl:element name="html">
			<head>
				<title>
					<xsl:value-of select="$title"/> - powered by BMForum</title>
				<link rel="stylesheet" href="images/rss/fbxslt.css" type="text/css"/>
				<link rel="alternate" type="application/rss+xml" title="RSS" href="{$feedUrl}" /> 
			</head>
			<xsl:apply-templates select="rss/channel"/>
	</xsl:element>
	</xsl:template>
	<xsl:template match="channel">
		<body>
			<div id="cometestme" style="display:none;">
			<xsl:text disable-output-escaping="yes" >&amp;amp;</xsl:text>
			</div>
			<div id="bodyfence">
				<xsl:apply-templates select="image"/>
				<div id="header">
					<h1>
						<a href="{link}"><xsl:value-of select="$title"/></a>
					</h1>
				</div>
				<div id="content">
					<xsl:apply-templates select="item"/>
				</div>
				<div id="footer">
					<p>Powered by <a href="http://www.bmforum.com">BMForum RSS Feed</a></p>
				</div>
			</div>
		</body>
	</xsl:template>
	<xsl:template match="item">
		<dl xmlns="http://www.w3.org/1999/xhtml">
			<dt>
				<a href="{link}">
					<xsl:value-of select="title"/>
				</a>
			</dt>
			<dd>
				<xsl:value-of select="substring(pubDate,5)"/>
			</dd>
			<dd name="decodeable">
				<xsl:call-template name="outputContent"/>
			</dd>
		</dl>
	</xsl:template>
	<xsl:template match="image">
		<xsl:element name="img" namespace="http://www.w3.org/1999/xhtml">
			<xsl:attribute name="src"><xsl:value-of select="url"/></xsl:attribute>
			<xsl:attribute name="alt"><xsl:value-of select="title"/></xsl:attribute>
			<xsl:attribute name="id">feedimage</xsl:attribute>
		</xsl:element>
		<xsl:text/>
	</xsl:template>
	<xsl:template match="bmforum:browserFriendly" xmlns:bmforum="http://rssnamespace.org/bmforum/ext/1.0">
		<p id="ownerblurb" xmlns="http://www.w3.org/1999/xhtml">
			<em>A message from the feed publisher:</em><xsl:text> </xsl:text>
<xsl:apply-templates/>
		</p>
	</xsl:template>
	<xsl:template name="outputContent">
		<xsl:choose>
			<xsl:when test="xhtml:body" xmlns:xhtml="http://www.w3.org/1999/xhtml">
				<xsl:copy-of select="xhtml:body/*"/>
			</xsl:when>
			<xsl:when test="xhtml:div" xmlns:xhtml="http://www.w3.org/1999/xhtml">
				<xsl:copy-of select="xhtml:div"/>
			</xsl:when>
			<xsl:when test="content:encoded" xmlns:content="http://purl.org/rss/1.0/modules/content/">
				<xsl:value-of select="content:encoded" disable-output-escaping="yes"/>
			</xsl:when>
			<xsl:when test="description">
				<xsl:value-of select="description" disable-output-escaping="yes"/>
			</xsl:when>
		</xsl:choose>
	</xsl:template>
</xsl:stylesheet>
