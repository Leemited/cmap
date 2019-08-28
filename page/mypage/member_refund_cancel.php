<?php
include_once ("../../common.php");

if(!$mb_id || $mb_id == ""){
    alert("회원정보가 없습니다.");
}

$sql = "select * from `cmap_payments_cancel` where mb_id = '{$mb_id}'";
$canChk = sql_fetch($sql);

if($canChk==null){
    alert("등록된 취소요청이 없습니다.");
}

//업데이트 처리 (취소요청 삭제/회원정보 수정)
$sql = "delete from `cmap_payments_cancel` where mb_id = '{$mb_id}'  ";
sql_query($sql);

$sql = "update `g5_member` set `mb_paused_status`  = 0 where mb_id = '{$mb_id}'";
sql_query($sql);

?>