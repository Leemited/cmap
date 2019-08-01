<?php
include_once ("../../common.php");
$today = date("Y-m-d");
$mbs = explode(",",$mb_ids);
$mons = explode(",",$months);
$prices = explode(",",$prices);
$payment_type = $order_type;
if($type=="pm"){
    $mb_title = "PM MODE 일괄 결제";
}else{
    $mb_title = "개인 사용자 일괄 결제";
}
for($i=0;$i<count($mbs);$i++) {
    //구매이력이 있으면 연장
    //없으면 신규 등록
    $sql = "select * from `cmap_payments` where order_cancel = 0 and '{$today}' between payment_start_date and payment_end_date and mb_id ='{$mbs[$i]}'";
    $mbchk = sql_fetch($sql);
    if($mbchk==null){
        //신규
        $start_date = $today;
        if($mons[$i]==1){
            if($type=="pm"){
                $order_type = 4;
            }else{
                $order_type = 1;
            }
            $end_date = date("Y-m-d",strtotime("+ 1 month"));
        }else if($mons[$i]==6){
            if($type=="pm"){
                $order_type = 5;
            }else{
                $order_type = 2;
            }
            $end_date = date("Y-m-d",strtotime("+ 6 month"));
        }else if($mons[$i]==12){
            if($type=="pm"){
                $order_type = 6;
            }else{
                $order_type = 3;
            }
            $end_date = date("Y-m-d",strtotime("+ 12 month"));
        }
        $sql = "insert into `cmap_payments` set mb_id ='{$mbs[$i]}', order_type = '{$payment_type}', order_payment_type = '{$order_type}',order_id='{$merchant_uid}', order_amount = '{$prices[$i]}', payment_date = now(), payment_time = now(), payment_month = '{$mons[$i]}',payment_start_date = '{$start_date}', payment_end_date = '{$end_date}'";
    }else{
        //연장
        $start_date = date("Y-m-d",strtotime("+ 1 day",strtotime($mbchk["payment_end_date"])));
        if($mons[$i]==1){
            if($type=="pm"){
                $order_type = 4;
            }else{
                $order_type = 1;
            }
            $end_date = date("Y-m-d",strtotime("+ 1 month",strtotime($start_date)));
        }else if($mons[$i]==6){
            if($type=="pm"){
                $order_type = 5;
            }else{
                $order_type = 2;
            }
            $end_date = date("Y-m-d",strtotime("+ 6 month",strtotime($start_date)));
        }else if($mons[$i]==12){
            if($type=="pm"){
                $order_type = 6;
            }else{
                $order_type = 3;
            }
            $end_date = date("Y-m-d",strtotime("+ 12 month",strtotime($start_date)));
        }
        $sql = "insert into `cmap_payments` set mb_id ='{$mbs[$i]}', order_type = '{$payment_type}', order_payment_type = '{$order_type}',order_id='{$merchant_uid}', order_amount = '{$prices[$i]}', payment_date = now(), payment_time = now(), payment_month = '{$mons[$i]}',payment_start_date = '{$start_date}', payment_end_date = '{$end_date}'";
        echo $sql."<br>";
    }
    if (sql_query($sql)) {
        $chk = true;
    } else {
        $chk = false;
    }
}

if($chk==true) {
    alert($mb_title . "가 완료 되었습니다.");
}else{
    alert($mb_title . "에 오류가 있습니다.\\r관리자에게 문의바립니다.");
}
?>