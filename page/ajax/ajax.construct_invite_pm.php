<?php
include_once ("../../common.php");

$sql = "select mb_id from `cmap_my_construct` where id = '{$pm_constid}'";
$chkm = sql_fetch($sql);

if($chkm["mb_id"]==$member["mb_id"]){
    $result["msg"] = "현장개설 회원입니다.";
    echo json_encode($result);
    return;
}

$sql = "select count(*) as cnt from `cmap_construct_invite` where const_id = '{$pm_constid}' and read_mb_id = '{$mb_id}' and msg_status = 0";
$chkmem = sql_fetch($sql);

if($chkmem["cnt"]>0){
    $result["msg"] = "이미 요청된 현장으로 승인 대기중입니다.";
    echo json_encode($result);
    return;
}

$sql = "insert into `cmap_construct_invite` set const_id = '{$pm_constid}', send_mb_id = '{$member["mb_id"]}', read_mb_id = '{$mb_id}', send_insertdate = now(), msg_type = 3 ";
if(sql_query($sql)){
    $result["msg"] = "요청 되었습니다.";
    $result["status"] = 1;
    //알림 보내기
}else{
    $result["sss"]=$sql;
    $result["msg"] = "처리 오류로 요청이 되지 않았습니다.";
}
echo json_encode($result);