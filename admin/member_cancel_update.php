<?php
include_once ("./_common.php");
if(!$id || $id==""){
    alert("요청된 취소정보를 찾을 수 없습니다.");
}

if($type=="del"){
    $sql = "delete from `cmap_payments_cancel` where id = '{$id}'";
    sql_query($sql);
    $sql = "update `g5_member` set `mb_paused_status` = 0 where mb_id = '{$mb_id}'";
    sql_query($sql);

    alert("맴버쉽 환불요청이 삭제 되었습니다.");
}else{
    //취소요청 업데이트 1 = 승인완료
    $sql = "update `cmap_payments_cancel` set cancel_status = 1 where id = '{$id}'";
    sql_query($sql);

    //결제정보 업데이트 취소일 및 취소상태
    $sql = "update `cmap_payments` set `order_cancel_date` = now(), `order_cancel_time` = now(), order_cancel = 1 where mb_id = '{$mb_id}'";
    sql_query($sql);

    alert("맴버쉽 환불요청이 처리 완료 되었습니다.");
}

?>