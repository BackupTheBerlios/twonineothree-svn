<?php

$api->setMainStylesheet("GreenStripeLayout", "internal");
$api->setLayoutInfo("00t.org Green Stripe Layout", "Ulrik G&uuml;nther", "kpanic@00t.org", "");

$api->setOmitBranding();

$api->beginLayout();
?>
<div class="stripediv">
	<div class="greenstripe">
	&nbsp;
	</div>
	<div class="textunderstripe">
	<strong><?php $api->getBoxContent("Title"); ?></strong><br/>
	<?php $api->getBoxContent("Content"); ?>
	<div style="text-align: right; font-style: italic;"><?php $api->getProperty("author_nickname"); ?></div>
	<br/>
	<div style="font-size: 9px; color: #CCCCCC; text-align: center;">
	<a href="http://validator.w3.org/check/referer">valid xhtml</a> &middot; 
	<a href="http://jigsaw.w3.org/css-validator/check/referer">valid css</a><br/>
	<?php $api->getSysSignature() ?>
	</div>
	</div>
</div>
<?php
$api->endLayout();
?>
