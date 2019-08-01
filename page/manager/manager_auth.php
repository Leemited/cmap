<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$member["mb_auth"]=0;

if($member["mb_level"]!=5){
    $member["mb_auth"]=1;
}else {
    $todays = date("Y-m-d");
    $sql = "select count(*) as cnt from `cmap_payments` where mb_id = '{$member["mb_id"]}' and '{$todays}' BETWEEN payment_start_date and payment_end_date and order_cancel = 0";
    $chkMembeship = sql_fetch($sql);

    if ($chkMembeship["cnt"] == 0) {
        $member["mb_auth"] = 2;
    }
}

if ($member["mb_auth"] == 1) {
    alert("접근 권한이 없는 회원등급니다.");
}else if($member["mb_auth"]==2){
    alert("이용권 구매 후 이용가능합니다.");
}