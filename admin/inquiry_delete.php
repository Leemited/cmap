<?php
include_once ("./_common.php");

if(!$id || $id==""){
    alert("삭제할 문의를 선택해주세요.");
}


$sql = "delete from `cmap_inquiry` where id = '{$id}'";
if(sql_query($sql)){
    alert("삭제되었습니다.");
}else{
    alert("삭제오류입니다.");
}

?>