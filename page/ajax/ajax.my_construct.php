<?php
include_once ("../../common.php");

$sql = "select * from `cmap_my_construct` where id= '{$id}'";
$cons = sql_fetch($sql);

$sql = "select * from `cmap_construct_invite` where (read_mb_id = '{$member["mb_id"]}' or send_mb_id = '{$member["mb_id"]}') and const_id = '{$id}' and msg_status = 0";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    $invite[] = $row;
}
$result["invite_cnt"] = count($invite);
echo json_encode($result);
?>