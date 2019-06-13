<?php
include_once ("../../common.php");

if($const_id){
    $where = " (instr(read_mb_id,'{$member["mb_id"]}') != 0 or send_mb_id = '{$member["mb_id"]}') and msg_status = 0 and const_id = '{$const_id}'";
}else{
    $where = " (instr(read_mb_id,'{$member["mb_id"]}') != 0 or send_mb_id = '{$member["mb_id"]}') and msg_status = 0 and const_id = '{$current_const["const_id"]}'";
}

$sql = "select count(*) as cnt from `cmap_construct_invite` where {$where}";
$count = sql_fetch($sql);
$result["cnt"] = $count["cnt"];

echo json_encode($result);
?>