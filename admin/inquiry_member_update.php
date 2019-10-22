<?php
include_once ("./_common.php");

if(!$mb_id || $mb_id ==""){
    alert("결제 완료할 회원정보가 없습니다.");
}

$today = date("Y-m-d");
$mb = get_member($mb_id);
$chk = $mb_id."||".$month."||0";
$mbs = explode(",",$members);

if($mb["mb_level"] == 5){
    if($month==1){
        $price = 473000;
        $end_date = date("Y-m-d",strtotime("+ 30day"));
        $od_type = 3;
    }else if($month==6){
        $price = 2750000;
        $end_date = date("Y-m-d",strtotime("+ 180day"));
        $od_type = 4;
    }else{
        $price = 4620000;
        $end_date = date("Y-m-d",strtotime("+ 365day"));
        $od_type = 5;
    }
}else{
    if($month==1){
        $price = 99000;
        $end_date = date("Y-m-d",strtotime("+ 30day"));
        $od_type = 1;
    }else if($month==6){
        $price = 528000;
        $end_date = date("Y-m-d",strtotime("+ 180day"));
        $od_type = 2;
    }else{
        $price = 924000;
        $end_date = date("Y-m-d",strtotime("+ 365day"));
        $od_type = 3;
    }
}




$sql = "insert into `cmap_payments` set mb_id ='{$mb_id}', order_type = '{$od_type}', order_payment_type = '',order_id='', order_amount = '{$price}', payment_date = now(), payment_time = now(), payment_month = '{$month}',payment_start_date = '{$today}', payment_end_date = '{$end_date}'";
if(sql_query($sql)){
    for($i=0;$i<count($mbs);$i++){
        if($mbs[$i]==$chk){
            $mbs[$i] = $mb_id."||".$month."||1";
        }
    }
    $memb = implode(",",$mbs);
    $sql = "update `cmap_inquiry` set payments_mb_id = '{$memb}' where id = '{$id}'";
    sql_query($sql);
    alert("결제처리 되었습니다.");
}else{
    alert("결제처리가 실패되었습니다.");
}
?>