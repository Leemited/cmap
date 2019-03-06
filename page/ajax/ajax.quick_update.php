<?php
include_once ("../../common.php");

if($mb_id=="" || !$mb_id){
    $result["msg"] = "로그인이 필요합니다.";
    echo json_encode($result);
    return false;
}

$sql = "select count(*) as cnt from `cmap_myquick` where mb_id = '{$mb_id}'";
$num = sql_fetch($sql);

if($num["cnt"]==0){
    $sql = "insert into `cmap_myquick` set mb_id ='{$mb_id}', quick = '{$quick}', insert_date=now() , update_date=now(), quick_menu='{$quick_menu}', quick_menu_name = '{$quick_menu_name}', quick_menu_status = '{$quick_menu_status}'";
    if(sql_query($sql)){
        $result["msg"]="적용완료";
    }else{
        $result["msg"]="적용실패";
    }
}else{
    $sql = "update `cmap_myquick` set quick = '{$quick}', update_date=now(), quick_menu='{$quick_menu}', quick_menu_name = '{$quick_menu_name}', quick_menu_status = '{$quick_menu_status}' where mb_id = '{$mb_id}'";
    if(sql_query($sql)){
        $result["msg"]="적용완료";
    }else{
        $result["msg"]="적용실패";
    }
}

echo json_encode($result);