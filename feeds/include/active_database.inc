<?php
	require("sysDBsysdb.inc");

	$int_master_db = getGroupDBIPS($akken_group_id,$sysdb,"Master");
	$db=getActiveDatabase($int_master_db);

	$akken_master_db=$int_master_db[0];

	require("cdatabase.inc");
?>