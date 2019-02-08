<?php
include_once ("./_common.php");

$sql = "select content , span from `cmap_content` where pk_id = '{$pk_id}'";
$con = sql_fetch($sql);

$evals = explode("``",$con["content"]);

$evals[$index] = $text;