<?php
include_once ("../../common.php");

$sql = "update `g5_member` set mb_6 = '{$value}' where mb_id = '{$member["mb_id"]}'";
sql_query($sql);
?>