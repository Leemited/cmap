<?php
include_once ("./_common.php");

$sql = "update `cmap_content` set submit_date = '{$text}' where pk_id = '{$pk_id}'";

if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}