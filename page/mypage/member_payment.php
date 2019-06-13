<?php
include_once ("../../common.php");
if($payment_type=="1"){
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d",strtotime("+ 1 month"));
    $payment_month = 1;
    $mb_level = 3;
    $mb_title = "개인 사용자 구매";
}else if($payment_type=="2"){
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d",strtotime("+ 6 month"));
    $payment_month = 6;
    $mb_level = 3;
    $mb_title = "개인 사용자 구매";
}else if($payment_type=="3"){
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d",strtotime("+ 12 month"));
    $payment_month = 12;
    $mb_level = 3;
    $mb_title = "개인 사용자 구매";
}else if($payment_type=="4"){
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d",strtotime("+ 1 month"));
    $payment_month = 1;
    $mb_level = 5;
    $mb_title = "기업/관리자(PM_MODE) 구매";
}else if($payment_type=="5"){
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d",strtotime("+ 6 month"));
    $payment_month = 6;
    $mb_level = 5;
    $mb_title = "기업/관리자(PM_MODE) 구매";
}else if($payment_type=="6"){
    $start_date = date("Y-m-d");
    $end_date = date("Y-m-d",strtotime("+ 12 month"));
    $payment_month = 12;
    $mb_level = 5;
    $mb_title = "기업/관리자(PM_MODE) 구매";
}


$today = date("Y-m-d");
//추가 구매 확인
$sql = "select count(*) as cnt from `cmap_payments` where mb_id = '{$member["mb_id"]}' and '{$today}' BETWEEN payment_start_date and payment_end_date and order_cancel = 0";
$pays = sql_fetch($sql);

if($pays["cnt"]>0){//구매연장

    //구매이력 있으면 해당 구매가 사용중인지 파악후 연장
    //최근 구매내역에서 종료일 체크
    $sql = "select * from `cmap_payments` where mb_id = '{$member["mb_id"]}' and order_cancel = 0 order by payment_date desc limit 0, 1";
    $chkEndDate = sql_fetch($sql);


    //시작일을 종료일 다음으로 부터 시작
    //만약 개인구매였다가 PM_MODE로 변경일경우??
    //개인용 1:1개월,2:6개월,3:12개월
    //PM_MODE 4:1개월,5:6개월,6:12개월
    
    if($chkEndDate['order_type'] < 4 && $payment_type < 4){//개인 에서 연장
        $start_date = date("Y-m-d",strtotime("+ 1 day", strtotime($chkEndDate["payment_end_date"])));
        $end_date = date("Y-m-d",strtotime("+ {$payment_month} month", strtotime($start_date)));
    }else if($chkEndDate['order_type'] > 3 && $payment_type > 3){//PM_MODE 연장
        $start_date = date("Y-m-d",strtotime("+ 1 day", strtotime($chkEndDate["payment_end_date"])));
        $end_date = date("Y-m-d",strtotime("+ {$payment_month} month", strtotime($start_date)));
    }

    $sql = "insert into `cmap_payments` set mb_id ='{$member["mb_id"]}', order_type = '{$payment_type}', order_payment_type = '{$order_type}',order_id='{$merchant_uid}', order_amount = '{$amount}', payment_date = now(), payment_time = now(), payment_month = '{$payment_month}',payment_start_date = '{$start_date}', payment_end_date = '{$end_date}'";

    if(sql_query($sql)){
        $sql = "update `g5_member` set mb_level = '{$mb_level}' where mb_id = '{$member["mb_id"]}'";
        sql_query($sql);
        alert($mb_title."연장이 완료 되었습니다.");
    }else{
        alert($mb_title."연장에 오류가 있습니다.\\r관리자에게 문의바립니다.");
    }

}else{//만료상태 or 신규구매
    //구매이력 업기 때문에 그냥 등록 하면 됨

    $sql = "insert into `cmap_payments` set mb_id ='{$member["mb_id"]}', order_type = '{$payment_type}', order_payment_type = '{$order_type}',order_id='{$merchant_uid}', order_amount = '{$amount}', payment_date = now(), payment_time = now(), payment_month = '{$payment_month}',payment_start_date = '{$start_date}', payment_end_date = '{$end_date}'";
    if(sql_query($sql)){
        $sql = "update `g5_member` set mb_level = '{$mb_level}' where mb_id = '{$member["mb_id"]}'";
        sql_query($sql);
        alert($mb_title."가 완료 되었습니다.");
    }else{
        alert($mb_title."에 오류가 있습니다.\\r관리자에게 문의바립니다.");
    }
}
?>