<?php

$xmlstr = '<?xml version="1.0"?>
<news>
	<item date="3274837283">
		<caption>UberTesting...</caption>
		<content>
		<![CDATA[
		dies ist ein testtest...
		]]>
		</content>
	</item>
	<item date="3247283732">
		<caption>Testing...</caption>
		<content>
		<![CDATA[
		dies ist ein zweiter test...
		]]>
		</content>
	</item>
</news>';

$xsl2str = '<xsl:stylesheet version = "1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
	<xsl:apply-templates />
</xsl:template>

<xsl:template match="content">
	<b><xsl:value-of select="." /></b>
</xsl:template>
';


$xml = new DomDocument;
$xml->loadxml($xmlstr);
$xsl = new DomDocument;
$xsl->load("test1.xsl");
$xsl2 = new DomDocument;
$xsl2->load("test1-2.xsl");

$proc = new xsltprocessor;
$proc->importStyleSheet($xsl2);
$proc->importStyleSheet($xsl);
echo $proc->transformToXML($xml);

echo '<br><br>';
printf("%s<br>Count: %d", md5("29o3_DEFAULT_THEME"), strlen(md5("test")));
?>