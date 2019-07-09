<?php
include_once ("../../common.php");

if(!$msg_id){
    alert("선택된 업무연락서가 없습니다.");
    return false;
}

//수신자 수 확인
$sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
$mem = sql_fetch($sql);
$read_mb_ids = explode(",",$mem["msg_read_member"]);

//우선 수신확인 데이터 입력
if($mem["msg_read_member"]==""){
    $in_read_member = $member["mb_id"];
    $in_read_date = date("Y-m-d");
    $in_read_time = date("h:i:s");

    $where = " read_date = '{$in_read_date}', read_time = '{$in_read_time}', msg_read_member = '{$in_read_member}'";
}else {
    $memchk = false;
    for ($i = 0; $i < count($read_mb_ids); $i++) {
        if ($read_mb_ids[$i] == $member["mb_id"]) {
            $memchk = true;
        }
    }

    if($memchk==false){
        $in_read_member = $member["mb_id"];
        $in_read_date = date("Y-m-d");
        $in_read_time = date("h:i:s");
        $where = " read_date = CONCAT(read_date,',','{$in_read_date}'), read_time = CONCAT(read_time,',','{$in_read_time}'), msg_read_member = CONCAT(msg_read_member,',','{$in_read_member}')";
    }
}

$sql = "update `cmap_construct_work_msg` set {$where} where id = '{$msg_id}'";

if(sql_query($sql)){
    //입력후 전체 수신완료 인지 체크
    $sql = "select * from `cmap_construct_work_msg` where id = '{$msg_id}'";
    $countchk = sql_fetch($sql);
    $read_members = explode(",",$countchk["msg_read_member"]);
    $count_read_mbs = explode(",",$countchk["read_mb_id"]);

    if(count($read_members)==count($count_read_mbs)){
        $sql = "update `cmap_construct_work_msg` set read_status = 1 where id = '{$msg_id}'";
        sql_query($sql);
    }
    alert("업무연락서 확인이 완료 되었습니다.");
}else{
    alert("알수 없는 요청입니다.");
}
?>
