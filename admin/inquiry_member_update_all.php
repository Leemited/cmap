<?php
include_once ("./_common.php");

$mem = explode(",",$members);
$today = date("Y-m-d");
$sys = true;
for($i=0;$i<count($mem);$i++){
    $mbs = explode("||",$mem[$i]);
    if($mbs[2]==0){
        $mb = get_member($mbs[0]);
        if($mb["mb_level"]==5) {
            if ($mbs[1] == 1) {
                $price = 473000;
                $end_date = date("Y-m-d",strtotime("+ 30day"));
                $od_type = 3;
            }else if($mbs[1]==6){
                $price = 2750000;
                $end_date = date("Y-m-d",strtotime("+ 180day"));
                $od_type = 4;
            }else{
                $price = 4620000;
                $end_date = date("Y-m-d",strtotime("+ 365day"));
                $od_type = 5;
            }
        }else{
            if ($mbs[1] == 1) {
                $price = 99000;
                $end_date = date("Y-m-d",strtotime("+ 30day"));
                $od_type = 1;
            }else if($mbs[1]==6){
                $price = 528000;
                $end_date = date("Y-m-d",strtotime("+ 180day"));
                $od_type = 2;
            }else{
                $price = 924000;
                $end_date = date("Y-m-d",strtotime("+ 365day"));
                $od_type = 3;
            }
        }
        $sql = "insert into `cmap_payments` set mb_id ='{$mbs[0]}', order_type = '{$od_type}', order_payment_type = '',order_id='', order_amount = '{$price}', payment_date = now(), payment_time = now(), payment_month = '{$mbs[1]}',payment_start_date = '{$today}', payment_end_date = '{$end_date}'";
        sql_query($sql);
        $mem[$i] = $mbs[0]."||".$mbs[1]."||1";

    }
}
$mems = implode(",",$mem);
$sql = "update `cmap_inquiry` set payments_mb_id = '{$mems}' where id = '{$id}'";
sql_query($sql);

alert("승인되었습니다.");
?>