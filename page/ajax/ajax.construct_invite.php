<?php
include_once ("../../common.php");

$sql = "select mb_id from `cmap_my_construct` where id = '{$id}'";
$chkm = sql_fetch($sql);

if($chkm["mb_id"]==$mb_id){
    $result["msg"] = "현장개설 회원입니다.";
    echo json_encode($result);
    return;
}


$sql = "select count(*) as cnt from `cmap_my_construct` where mb_id = '{$mb_id}' or INSTR(members,'{$mb_id}') > 0 ";
$chkcnt = sql_fetch($sql);

if($chkcnt["cnt"]>=10){
    $result["msg"] = "참여가능한 현장이 초과되어 초대할 수 없습니다.";
    echo json_encode($result);
    return false;
}

$sql = "select count(*) as cnt from `cmap_construct_invite` where const_id = '{$id}' and read_mb_id = '{$mb_id}' and msg_status = 0";
$chkmem = sql_fetch($sql);

if($chkmem["cnt"]>0){
    $result["msg"] = "이미 초대 된 회원입니다. 초대승인 대기중입니다.";
    echo json_encode($result);
    return;
}

$sql = "insert into `cmap_construct_invite` set const_id = '{$id}', send_mb_id = '{$member["mb_id"]}', read_mb_id = '{$mb_id}', send_insertdate = now() ";
if(sql_query($sql)){
    $result["msg"] = "초대 되었습니다.";
    $result["status"] = 1;
    //알림 보내기
}else{
    $result["msg"] = "처리 오류로 초대가 되지 않았습니다.";
}
echo json_encode($result);