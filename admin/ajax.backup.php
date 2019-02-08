<?php
include_once ("./_common.php");

$backupTable = array('cmap_depth1','cmap_depth2','cmap_depth3','cmap_depth4','cmap_content');

echo backup_tables(G5_MYSQL_HOST,G5_MYSQL_USER,G5_MYSQL_PASSWORD,G5_MYSQL_DB, $backupTable);

?>