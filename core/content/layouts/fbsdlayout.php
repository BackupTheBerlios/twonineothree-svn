<?php

$api->setMainStylesheet("fbsd", "internal");
$api->setLayoutInfo("00t.org/fbsd layout", "Ulrik G&uuml;nther", "kpanic@00t.org", "");

$api->setOmitBranding();

$api->beginLayout();
$api->setBoxOrder("title", "asc");
?>
<div class="toc">
	<strong>table of contents 
	</strong><br/><br/>
<?php $api->makeTOC("&middot; <a href=\"#{LINK:ID}\">{LINK:TITLE}</a><br/>", "tips", "toc,ack,welcome", 30) ?>
</div>

<h3>Welcome at 00t.org/fbsd!</h3>
<p>
	<?php $api->getBoxContent("welcome") ?>
</p>	
<h3><a name="ack">&laquo; Acknowledgements</a></h3>
<p>
<?php $api->getBoxContent("ack"); ?>
</p>


<?php
$api->beginSuccessiveBoxes();
?>
<h3><a name="{BOX:ID}">&laquo; {BOX:TITLE} &laquo;</a></h3>
<p>
	{BOX:CONTENT}
</p>
<?php
$api->endSuccessiveBoxes("tips");
?>

<div class="copyright">
	&copy; 2004 by Ulrik Guenther &lt;kpanic at 00t dot org&gt;<br/>
	This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/2.0/">Creative Commons License</a>.<br/>
	Valid <a href="http://validator.w3.org/check/referer">XHTML 1.0 Strict</a> and <a href="http://jigsaw.w3.org/css-validator/validator?uri=http://00t.org/fbsd/">CSS</a>.<br/><br/><br/>
</div>
<?php
$api->endLayout();
?>
