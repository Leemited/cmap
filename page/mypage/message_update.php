<?php
include_once ("../../common.php");

if(!$msg_id){
    alert("선택된 업무연락서가 없습니다.");
    return false;
}

$sql = "update `cmap_construct_work_msg` set read_status = 1 where id = '{$msg_id}'";

if(sql_query($sql)){
    alert("업무연락서 확인이 완료 되었습니다.");
}else{
    alert("알수 없는 요청입니다.");
}
?>
