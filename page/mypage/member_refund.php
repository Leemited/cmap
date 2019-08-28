<?php
include_once ("../../common.php");

if(!$mb_id){
    alert("회원정보가 없습니다.");
}

//해당 아이디의 결제정보 가져오기
$sql = "select * from `cmap_payments` where mb_id = '{$mb_id}' and '{$todays}' BETWEEN payment_start_date and payment_end_date and order_cancel = 0";
$res = sql_query($sql);
while($row = sql_fetch_array($res)){
    //결제정보의 아이디만 가져오자
    $ids[] = $row["id"];
}

$id = implode(",",$ids);

//취소 정보 등록
$sql = "insert into `cmap_payments_cancel` set pay_id = '{$id}', cancel_date = now(), cancel_time = now(),cancel_status=0,mb_id ='{$mb_id}' ,cancel_account = '{$cancel_account}', cancel_bank_name = '{$cancel_bank_name}', cancel_bank_number='{$cancel_bank_number}'";
if(sql_query($sql)){
    //회원 접근 차단
    $sql = "update `g5_member` set mb_paused_status = 1 where mb_id ='{$mb_id}'";
    sql_query($sql);
    alert("정상 처리되었습니다. \\n맵버쉽 취소로 인해 홈페이지 이용에 제한이 있을 수 있습니다.");
}else{
    alert("정보 오류로 인해 정상 처리되지 않았습니다.\\n다시 시도해 주세요.");
}