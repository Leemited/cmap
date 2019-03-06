<?php
include_once ("../common.php");

$sql = "update `cmap_content` set submit_date_type = '{$type}' where pk_id = '{$pk_id}'";

sql_query($sql);

?>