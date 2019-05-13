<?php
include_once ("../../common.php");

$sql = "select count(*) as cnt from `cmap_my_current_construct` where mb_id = '{$member["mb_id"]}'";
$mycurrent = sql_fetch($sql);

if($mycurrent["cnt"]==0){
    $sql = "insert into `cmap_my_current_construct` set mb_id = '{$member["mb_id"]}', const_id = '{$const_id}' ";
}else{
    $sql = "update `cmap_my_current_construct` set const_id = '{$const_id}' where mb_id = '{$member["mb_id"]}'";
}

sql_query($sql);

?>