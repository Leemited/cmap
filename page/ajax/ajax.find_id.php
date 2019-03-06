<?php
include_once ("../../common.php");

if($type=="hp"){
    $sel = " and REPLACE(mb_hp, '-', '') = '{$hp}'";
}else if($type=="email"){
    $sel = " and mb_email = '{$email}'";
}

$sql = "select * from `g5_member` where mb_name = '{$name}' {$sel}";
$result["sql"] = $sql;
$find = sql_fetch($sql);

if($find["mb_id"]){
    $sns =  explode("_",$find["mb_id"]);
    if(strpos($find["mb_id"],"kakao,naver")!==false){
        $result["sns"] = true;
        $result["snsid"] = strtoupper($sns[0]);
    }else{
        $result["sns"] = false;
    }
    $result["msg"] = 1;
    $id =  str_pad(substr($find["mb_id"], 0, 4), strlen($find["mb_id"]), "*");
    $result["mb_id"] = $id;
}else{
    $result["msg"] = 2;
}

echo json_encode($result);