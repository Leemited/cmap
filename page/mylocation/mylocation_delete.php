<?php
include_once ("../../common.php");

if(!$id){
    alert("삭제할 현장을 선택해 주세요.");
    return false;
}

$sql = "update `cmap_my_construct` set status = -1 where id = '{$id}'";
if(sql_query($sql)){
    $sql = "delete from `cmap_myschedule` where construct_id = '{$id}'";
    sql_query($sql);

    alert("삭제되었습니다.");
}else{
    alert("정보 오류로 인해 삭제가 되지 않았습니다.");
}

?>