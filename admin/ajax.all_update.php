<?php
include_once ("./_common.php");

if($depth=="content") {
    $sql = "update `cmap_{$depth}` set content = '{$text}' where pk_id = '{$pk_id}'";
}else{
    $sql = "update `cmap_{$depth}` set depth_name = '{$text}' where pk_id = '{$pk_id}'";
}

if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}