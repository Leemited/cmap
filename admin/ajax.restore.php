<?php
include_once ("./_common.php");

$path = G5_PATH."/admin/backup/";

$command = "mysql --user=".G5_MYSQL_USER." --password=".G5_MYSQL_PASSWORD." -h ".G5_MYSQL_HOST." -D ".G5_MYSQL_DB." < ".$path;

$output = shell_exec($command."/".$filename);

echo $output;