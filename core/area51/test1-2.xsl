<xsl:stylesheet version = '1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
<xsl:template match="content">
	<b><em><xsl:value-of select="." /></em></b>
</xsl:template>
</xsl:stylesheet>