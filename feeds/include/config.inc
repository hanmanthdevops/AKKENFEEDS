<?php
	require("sysdb.inc");

	$WDOCUMENT_ROOT="/var/www/feeds";

	if(!is_dir($WDOCUMENT_ROOT))
		mkdir($WDOCUMENT_ROOT,0777);
?>