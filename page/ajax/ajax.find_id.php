<?php
include_once ("../../common.php");

if($type=="hp"){
    $sel = " and REPLACE(mb_hp, '-', '') = '{$hp}'";
}else if($type=="email"){
    $sel = " and mb_email = '{$email}'";
}

$sql = "select * from `g5_member` where mb_name = '{$name}' {$sel}";
$find = sql_fetch($sql);

if($find["mb_id"]){
    $sns =  explode("_",$find["mb_id"]);
    if(stripos($find["mb_id"],"kakao,naver")===false){
        $result["sns"] = true;
        $result["snsid"] = strtoupper($sns[0]);
    }else{
        $result["sns"] = false;
    }
    $result["msg"] = 1;
    $result["mb_id"] = $find["mb_id"];
}else{
    $result["msg"] = 2;
}

echo json_encode($result);