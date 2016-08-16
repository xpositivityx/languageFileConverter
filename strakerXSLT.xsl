<?xml version="1.0" ?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="text"/>
	
	<xsl:template match="/">
		<xsl:for-each select="root/data">
     	<xsl:variable name='context'><xsl:value-of select="string-length(@content_context)"/></xsl:variable>
$lang['<xsl:value-of select="@name"/>'] = "<xsl:value-of select="value"/>";<xsl:if test="$context > 1"> //<xsl:value-of select="@content_context"/></xsl:if><xsl:text>&#xd;</xsl:text>
		</xsl:for-each>
	</xsl:template>
	
</xsl:stylesheet>