<?php
include_once ("../../common.php");

if($const_id){
    $where = " (read_mb_id = '{$member["mb_id"]}' or send_mb_id = '{$member["mb_id"]}') and msg_status = 0 and const_id = '{$const_id}' or (read_mb_id = '{$member["mb_id"]}' and msg_status = 0)";
}else{
    $where = " (read_mb_id = '{$member["mb_id"]}' or send_mb_id = '{$member["mb_id"]}') and msg_status = 0 ";
}

$sql = "select count(*) as cnt from `cmap_construct_invite` where {$where}";
$count = sql_fetch($sql);
$result["cnt"] = $count["cnt"];

echo json_encode($result);
?>