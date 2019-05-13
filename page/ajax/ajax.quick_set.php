<?php
include_once ("../../common.php");

if(!$is_member){
    $result["msg"]="로그인이 필요합니다.";
    echo json_encode($result);
    return false;
}

$sql = "select count(*) as cnt from `cmap_myquick` where mb_id ='{$mb_id}'";
$num = sql_fetch($sql);

if($num["cnt"]==0){
    $sql = "insert into `cmap_myquick` set quick = '{$quick}' , insert_date = now() , update_date = now(), mb_id ='{$mb_id}' ";
    if(sql_query($sql)){
        $result["msg"]="설정완료";
    }else{
        $result["msg"]="설정오류";
    }
}else{
    $sql = "update `cmap_myquick` set quick = '{$quick}', update_date = now() where mb_id = '{$mb_id}'";
    $result["sql"]=$sql;
    if(sql_query($sql)){
        $result["msg"]="설정완료";
    }else{
        $result["msg"]="설정오류";
    }
}
echo json_encode($result);