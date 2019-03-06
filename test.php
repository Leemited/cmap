<?php
include_once ("_common.php");

$sql = "insert into `test_table` set insert_date = now() ";
sql_query($sql);

?>