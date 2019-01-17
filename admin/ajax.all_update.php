<?php
include_once ("./_common.php");

if($depth=="content") {
    $sql = "update `cmap_{$depth}` set content = '{$text}' where id = '{$id}'";
}else{
    $sql = "update `cmap_{$depth}` set depth_name = '{$text}' where id = '{$id}'";
}

if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}