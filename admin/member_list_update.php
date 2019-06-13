<?php
include_once ("./_common.php");

if($act_button=="선택삭제"){
    $today = date("Y-m-d");
    for($i=0;$i<count($chk);$i++) {
        //삭제시 나의 설정 및 현장 제거
       $sql = "select * from `cmap_payments` where mb_id='{$mb_id[$chk[$i]]}' and payment_end_date > '{$today}' and order_cancel = 0 order by payment_end_date desc limit 0, 1";
       $pays = sql_fetch($sql);
       if($pays!=null){
           $noDel[] = $mb_id[$chk[$i]];
       }

       $sql = "select * from `cmap_my_construct` ";
    }

    print_r2($noDel);
}