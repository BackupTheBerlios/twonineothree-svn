<xsl:stylesheet version = '1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>
<xsl:template match="/">
<html>
	<head>
	 <title>News</title>
	</head>
	<body>
	<table width="80%" border="1">
		<tr>
			<th>The News</th>
		</tr>
		
		<xsl:apply-templates />
		
	</table>
	</body>
</html>
</xsl:template>


<xsl:template match="item">
	<tr>
		<td><xsl:apply-templates select="caption" /> posted on <xsl:value-of select="@date" />
		<xsl:apply-templates select="content" /></td>
	</tr>
</xsl:template>

<xsl:template match="caption">
	<b><xsl:value-of select="." /></b>
</xsl:template>

<xsl:template match="content">
	<xsl:value-of select="." />
</xsl:template>

<xsl:template match="i">
	<em><xsl:value-of select="." /></em>
</xsl:template>

</xsl:stylesheet>