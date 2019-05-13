<?php
include_once ("../../common.php");

$sql = "update `cmap_myschedule` set schedule_content = '{$con}' where id = '{$id}'";
if(sql_query($sql)){
    echo "1";
}else{
    echo "2";
}