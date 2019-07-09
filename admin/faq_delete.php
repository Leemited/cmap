<?php
include_once ("./_common.php");

if(!$fm_id || !$fa_id){
    alert("삭제할 정보가 없습니다.");
}

$sql = "delete from `g5_faq` where fm_id = '{$fm_id}' and fa_id = '{$fa_id}'";
if(sql_query($sql)){
    alert("삭제 되었습니다.");
}else{
    alert('삭제 실패하였습니다.');
}

?>