<?php
include_once ("./_common.php");

echo sql_query("SET GLOBAL max_allowed_packet=1073741824");
/*
$path = G5_PATH."/admin/backup/";

$command = "mysql --user=".G5_MYSQL_USER." --password=".G5_MYSQL_PASSWORD." -h ".G5_MYSQL_HOST." -D ".G5_MYSQL_DB." < ".$path;
$output = shell_exec($command."/".$filename);

echo "<pre>$output</pre>";
*/