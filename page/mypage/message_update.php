<?php
include_once ("../../common.php");

if(!$msg_id){
    alert("선택된 업무연락서가 없습니다.");
    return false;
}

//수신자 수 확인
$sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
$mem = sql_fetch($sql);
$chkcount = explode(",",$mem["read_mb_id"]);
if(!strpos($mem["msg_read_member"],$member["mb_id"])!==false){
    if($mem["msg_read_member"]==""){
        $in_read_member = $member["mb_id"];
    }else{
        $in_read_member = $mem["msg_read_member"].",".$member["mb_id"];
    }
}

$chk_read_member = explode(",",$in_read_member);

if(count($chk_read_member)==count($chkcount)){
    $sql = "update `cmap_construct_work_msg` set read_status = 1, msg_read_member = '{$in_read_member}', read_date = now() , read_time = now() where id = '{$msg_id}'";
}else{
    $sql = "update `cmap_construct_work_msg` set msg_read_member = '{$in_read_member}' where id = '{$msg_id}'";
}

if(sql_query($sql)){
    alert("업무연락서 확인이 완료 되었습니다.");
}else{
    alert("알수 없는 요청입니다.");
}
?>
