<?php

$api->setMainStylesheet("29o3default", "internal");
$api->setLayoutInfo("29o3 default layout", "Ulrik G&uuml;nther", "kpanic@00t.org", "");

$api->setOmitBranding();

$api->beginLayout();
?>
<div class="stripediv">
	<div class="bluestripe">
	<img src="lib/images/logo.png" />
	</div>
	<div class="textunderstripe">
	<?php $api->getBoxContent("Content"); ?>
	</div>
</div>
<?php

$api->endLayout();

?>
