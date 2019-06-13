<?php
include_once ("../../common.php");

if(!$id){
    echo "1";
    return false;
}

$sql = "delete from `mylocation_save_log` where id = '{$id}'";
if(sql_query($sql)){
    echo "2";
}else{
    echo "3";
}
